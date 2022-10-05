<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptdebtreceivable_model extends CI_Model
{

    // Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {
        $tCallStore = "{ CALL SP_RPTxRcvAbleCN(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";

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
            'pdDocDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'   => $paDataFilter['tDocDateTo'],
            'ptCstCodeFrom' => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeTo'   => $paDataFilter['tCstCodeTo'],
            'ptCgpCodeFrom' => $paDataFilter['tCstGrpCodeFrom'],
            'ptCgpCodeTo'   => $paDataFilter['tCstGrpCodeTo'],
            'ptCtyCodeFrom' => $paDataFilter['tCstTypeCodeFrom'],
            'ptCtyCodeTo'   => $paDataFilter['tCstTypeCodeTo'],
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
                    FTUsrSession        AS FTUsrSession_Footer,
                    SUM(FCXshGrand)       AS FCXshGrand_Footer,
                    SUM(FCXshAmtNV)     AS FCXshAmtNV_Footer,
                    SUM(FCXshVatable)  AS FCXshVatable_Footer,
                    SUM(FCXshVat)  AS FCXshVat_Footer
                FROM TRPTRcvAbleCNTmp WITH(NOLOCK)
                WHERE FTUsrSession <> '' 
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXshGrand_Footer,
                    0    AS FCXshAmtNV_Footer,
                   0 AS FCXshVatable_Footer,
                   0 AS FCXshVat_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = "
            SELECT
                L.*,
                T.FCXshGrand_Footer,
                T.FCXshAmtNV_Footer,
                T.FCXshVatable_Footer,
                T.FCXshVat_Footer
            FROM (
                SELECT
                ROW_NUMBER() OVER(
                    ORDER BY FTCstCode ASC,FDXshDocDate ASC) AS RowID, 
                               ROW_NUMBER() OVER(PARTITION BY FTCstCode
                    ORDER BY FTCstCode ASC) AS FNFmtAllRow, 
                    SUM(1) OVER(PARTITION BY FTCstCode) AS FNFmtEndRow,
                    A.*, 
                    S.FNRptGroupMember,
                    S.FCXshGrand_SubTotal,
                    S.FCXshAmtNV_SubTotal,
                    S.FCXshVatable_SubTotal,
                    S.FCXshVat_SubTotal
                FROM TRPTRcvAbleCNTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                    FTCstCode           AS FTCstCode_SUM,
                        COUNT(FTCstCode)    AS FNRptGroupMember,
                        SUM(FCXshGrand)       AS FCXshGrand_SubTotal,
                        SUM(FCXshAmtNV)     AS FCXshAmtNV_SubTotal,
                        SUM(FCXshVatable)  AS FCXshVatable_SubTotal,
                        SUM(FCXshVat)  AS FCXshVat_SubTotal
                    FROM TRPTRcvAbleCNTmp WITH ( NOLOCK )
                    WHERE FTUsrSession <> ''
                    AND FTUsrSession    = '$tUsrSession'
                    GROUP BY FTCstCode
                ) AS S ON A.FTCstCode = S.FTCstCode_SUM 
                WHERE FTUsrSession <> ''
                AND A.FTUsrSession  = '$tUsrSession'
                /* End Calculate Misures */
        ";
        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  ) AS L ";
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }else{
            $tSQL .= "  ) AS L ";
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
        }
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
            FROM TRPTRcvAbleCNTmp RPT WITH(NOLOCK)
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
