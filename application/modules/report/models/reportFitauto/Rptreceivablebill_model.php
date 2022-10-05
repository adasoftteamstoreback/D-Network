<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptreceivablebill_model extends CI_Model
{

    // Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {
        $tCallStore = "{ CALL SP_RPTxBillSB(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $aDataStore = array(
            // Systemp Parameter Report
            'pnLngID'       => $paDataFilter['nLangID'],
            'ptComName'     => $paDataFilter['tCompName'],
            'ptRptCode'     => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'pnFilterType'      => $paDataFilter['tTypeSelect'],
            'ptBchCode'     => $tBchCodeSelect,
            'ptAgnCode'     =>  $paDataFilter['tAgnCodeSelect'],
            'ptDocDateF'  => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'   => $paDataFilter['tDocDateTo'],
            'ptCstF'  => $paDataFilter['tCstCodeFrom'],
            'ptCstT'   => $paDataFilter['tCstCodeTo'],
            'ptCstgpF' => $paDataFilter['tCstGrpCodeFrom'],
            'ptCstgpT'   => $paDataFilter['tCstGrpCodeTo'],
            'ptCsttyF' => $paDataFilter['tCstTypeCodeFrom'],
            'ptCsttyT'   => $paDataFilter['tCstTypeCodeTo'],
            'pnResult'      => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere)
    {
        $nPage          = $paDataWhere['nPage'];
        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $nTotalPage = 1;
            $aPagination = 0;
        }

        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Set Priority
        // $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter   = "
                SELECT
                    FTUsrSession        AS FTUsrSession_Footer,
                    SUM(FCXpdInvLeft)       AS FCXpdInvLeft_Footer,
                    SUM(FCXpdInvPaid)     AS FCXpdInvPaid_Footer,
                    SUM(FCXpdInvRem)  AS FCXpdInvRem_Footer
                FROM TRPTBillSBTmp WITH(NOLOCK)
                WHERE FTUsrSession <> '' 
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXpdInvLeft_Footer,
                    0    AS FCXpdInvPaid_Footer,
                   0 AS FCXpdInvRem_Footer,
                   0 AS FCXshAmtNV_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = "
            SELECT
                L.*,
                T.* 
            FROM (
                SELECT
                ROW_NUMBER() OVER(
                    ORDER BY FTCstCode ASC,FDXshDocDate ASC) AS RowID, 
                    COUNT (ISNULL(FTCstCode,'')) OVER (PARTITION BY FTCstCode ORDER BY FTCstCode ASC) AS PARTTITIONBYCST,
                    ROW_NUMBER () OVER (PARTITION BY FTCstCode ORDER BY FTCstCode ASC) AS PARTTITIONBYCST_COUNT,
                    ROW_NUMBER () OVER (PARTITION BY FTXshDocNo ORDER BY FTXshDocNo ASC) AS PARTTITIONBYDOC_COUNT,
                    A.*, 
                    S.*,
                    D.*
                FROM TRPTBillSBTmp A WITH(NOLOCK)
                LEFT JOIN (
                    SELECT
                        FTCstCode AS FTCstCode_SubTotal,
                        SUM ( FCXpdInvLeft ) AS FCXpdInvLeft_SubTotal,
                        SUM ( FCXpdInvPaid ) AS FCXpdInvPaid_SubTotal,
                        SUM ( FCXpdInvRem ) AS FCXpdInvRem_SubTotal,
                        FTUsrSession AS FTUsrSession_SubTotal
                    FROM
                        TRPTBillSBTmp WITH ( NOLOCK ) 
                    WHERE
                        FTUsrSession <> '' 
                        AND FTUsrSession = '$tUsrSession' 
                    GROUP BY
                        FTCstCode,FTUsrSession
                ) AS S ON A.FTCstCode = S.FTCstCode_SubTotal AND A.FTUsrSession = S.FTUsrSession_SubTotal
                LEFT JOIN (
                    SELECT
                        FTXshDocNo AS FTDocCode_SUM,
                        SUM ( FCXpdInvLeft ) AS FCXpdInvLeft_SUM,
                        SUM ( FCXpdInvPaid ) AS FCXpdInvPaid_SUM,
                        SUM ( FCXpdInvRem ) AS FCXpdInvRem_SUM,
                        FTUsrSession AS FTUsrSession_SUM
                    FROM
                        TRPTBillSBTmp WITH ( NOLOCK ) 
                    WHERE
                        FTUsrSession <> '' 
                        AND FTUsrSession = '$tUsrSession' 
                    GROUP BY
                        FTXshDocNo,FTUsrSession
                ) AS D ON A.FTXshDocNo = D.FTDocCode_SUM AND A.FTUsrSession = D.FTUsrSession_SUM
                WHERE FTUsrSession <> ''
                AND A.FTUsrSession  = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L
        ";

        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }else{
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
        }

        // WHERE เงื่อนไข Page
        // $tSQL   .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL   .= " ORDER BY  L.FTCstCode ASC , L.PARTTITIONBYCST_COUNT ASC";

        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = NULL;
        }
        $aErrorList = array(
            "nErrInvalidPage"   => ""
        );
        $aResualt = array(
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Count จำนวน
    private function FMaMRPTPagination($paDataWhere)
    {
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTUsrSession) AS rnCountPage
            FROM TRPTBillSBTmp RPT WITH(NOLOCK)
            WHERE 
             RPT.FTUsrSession    = '$tUsrSession'
        ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage - 1;
        $nNextPage      = $nPage + 1;
        $nRowIDStart    = (($nPerPage * $nPage) - $nPerPage);
        if ($nRptAllRecord <= $nPerPage) {
            $nTotalPage = 1;
        } else if (($nRptAllRecord % $nPerPage) == 0) {
            $nTotalPage = ($nRptAllRecord / $nPerPage);
        } else {
            $nTotalPage = ($nRptAllRecord / $nPerPage) + 1;
            $nTotalPage = (int) $nTotalPage;
        }
        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if ($nRowIDEnd > $nRptAllRecord) {
            $nRowIDEnd = $nRptAllRecord;
        }
        $aRptMemberDet = array(
            "nTotalRecord"  => $nRptAllRecord,
            "nTotalPage"    => $nTotalPage,
            "nDisplayPage"  => $paDataWhere['nPage'],
            "nRowIDStart"   => $nRowIDStart,
            "nRowIDEnd"     => $nRowIDEnd,
            "nPrevPage"     => $nPrevPage,
            "nNextPage"     => $nNextPage,
            "nPerPage"      => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    // // Set Priority Group
    // private function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession)
    // {
    //     $tSQL   = "
    //         UPDATE TRPTPurCrOverDueTmp
    //         SET FNRowPartID = B.PartID
    //         FROM(
    //             SELECT
    //                 ROW_NUMBER() OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC , FDXphDocDate ASC) AS PartID, 
    //                 FTRptRowSeq
    //             FROM TRPTPurCrOverDueTmp TMP WITH(NOLOCK)
    //             WHERE 
    //              TMP.FTUsrSession 	= '$ptUsrSession'
    //         ) B
    //         WHERE TRPTPurCrOverDueTmp.FTRptRowSeq   = B.FTRptRowSeq 
    //         AND TRPTPurCrOverDueTmp.FTUsrSession    = '$ptUsrSession'
    //     ";
    //     $this->db->query($tSQL);
    // }
}
