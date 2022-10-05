<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptsaleorderlowcost_model extends CI_Model
{

    // Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {
        $tCallStore = "{ CALL SP_RPTxSoBelowCost(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $aDataStore = array(
            // Systemp Parameter Report
            'pnLngID'       => $paDataFilter['nLangID'],
            'ptComName'     => $paDataFilter['tCompName'],
            'ptRptCode'     => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'pnFilterType'  => $paDataFilter['tTypeSelect'],
            'ptAgnCode'     =>  $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'     => $tBchCodeSelect,
            'pdDocDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'   => $paDataFilter['tDocDateTo'],
            'ptCstCodeF'    => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeT'    => $paDataFilter['tCstCodeTo'],
            'ptCstGrpF'     => $paDataFilter['tCstGrpCodeFrom'],
            'ptCstGrpT'     => $paDataFilter['tCstGrpCodeTo'],
            'ptCstTyF'      => $paDataFilter['tCstTypeCodeFrom'],
            'ptCstTyT'      => $paDataFilter['tCstTypeCodeTo'],
            'ptPdtCodeF'    => $paDataFilter['tRptPdtCodeFrom'],
            'ptPdtCodeT'    => $paDataFilter['tRptPdtCodeTo'],
            'ptPdtGrpF'     => $paDataFilter['tPdtGrpCodeFrom'],
            'ptPdtGrpT'     => $paDataFilter['tPdtGrpCodeTo'],
            'ptPdtTyF'      => $paDataFilter['tPdtTypeCodeFrom'],
            'ptPdtTyT'      => $paDataFilter['tPdtTypeCodeTo'],
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

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Set Priority
        // $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter   = "
                SELECT
                    FTUsrSession        AS FTUsrSession_Footer
                FROM TRPTSoBelowCostTmp WITH(NOLOCK)
                WHERE FTUsrSession <> '' 
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = "
            SELECT
                L.*
            FROM (
                SELECT A.*,
                ROW_NUMBER() OVER(ORDER BY FTXshDocNo ASC,FDXshDocDate ASC) AS RowID,
                ROW_NUMBER () OVER (PARTITION BY FTXshDocNo ORDER BY FTXshDocNo ASC) AS PARTTITIONBYDOC_COUNT,
                COUNT (FTXshDocNo) OVER (PARTITION BY FTXshDocNo ORDER BY FTXshDocNo ASC) AS PARTTITIONBYDOC,
                S.*
                FROM TRPTSoBelowCostTmp A WITH(NOLOCK)
                LEFT JOIN (
                    SELECT
                        FTXshDocNo AS FTXshDocNo_SubTotal,
                        SUM ( FCXsdQty ) AS FCXsdQty_SubTotal,
                        SUM ( FCXsdAmount ) AS FCXsdAmount_SubTotal,
                        SUM ( FCXsdDiscount ) AS FCXsdDiscount_SubTotal,
                        SUM ( FCXshTotal ) AS FCXshTotal_SubTotal,
                        FTUsrSession AS FTUsrSession_SubTotal
                    FROM
                        TRPTSoBelowCostTmp WITH ( NOLOCK ) 
                    WHERE
                        FTUsrSession <> '' 
                        AND FTUsrSession = '$tUsrSession' 
                    GROUP BY
                        FTXshDocNo,FTUsrSession
                ) AS S ON A.FTXshDocNo = S.FTXshDocNo_SubTotal AND A.FTUsrSession = S.FTUsrSession_SubTotal
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
        $tSQL   .= " ORDER BY  L.FTCstCode ASC , L.FNRowPartID ASC";

        // print_r($tSQL);
        // die();

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
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTUsrSession) AS rnCountPage
            FROM TRPTSoBelowCostTmp RPT WITH(NOLOCK)
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
