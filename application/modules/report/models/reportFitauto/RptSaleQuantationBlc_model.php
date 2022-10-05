<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RptSaleQuantationBlc_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxPSSBelowCost(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'pnFilterType'  => 2,
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'     => $tBchCodeSelect,   
            'ptShpCode'     => '',
            'pdAmsDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdAmsDateTo'   => $paDataFilter['tDocDateTo'],
            'pnResult'      => 0,
        );

        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery !== FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    public function FMaMRPTPagination($paDataWhere) {
        
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   
            SELECT
                COUNT(ADJSTK_TMP.FTRptRowSeq) AS rnCountPage
            FROM TRPTSBelowCostTmp ADJSTK_TMP WITH(NOLOCK)
            WHERE 1=1
            AND ADJSTK_TMP.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        $nPrevPage = $nPage - 1;
        $nNextPage = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage); //RowId Start
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
            "nNextPage"     => $nNextPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    public function FSaMGetDataReport($paDataWhere) {
        
        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];

        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                    FTUsrSession            AS FTUsrSession_Footer,
                    SUM(FCXsdCostEX)        AS FCXsdCostEX_Footer,
                    SUM(FCXsdNetSale)       AS FCXsdNetSale_Footer,
                    SUM(FCXsdDiff)          AS FCXsdDiff_Footer,
                    SUM(FCXsdPerDiff)       AS FCXsdPerDiff_Sum
                FROM TRPTSBelowCostTmp WITH(NOLOCK)
                WHERE FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer,
                    '0'  AS FCXsdCostEX_Footer,
                    '0'  AS FCXsdNetSale_Footer,
                    '0'  AS FCXsdDiff_Footer,
                    '0'  AS FCXsdPerDiff_Sum
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = " SELECT
                        L.*,
                        T.*,
                        CAST(CASE WHEN T.FCXsdCostEX_Footer = 0 THEN 0 ELSE (T.FCXsdDiff_Footer/T.FCXsdCostEX_Footer)*100 END  AS DECIMAL(18,4)) AS FCXsdPerDiff_Footer
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FTRptRowSeq DESC) AS RowID ,
                            A.*
                        FROM TRPTSBelowCostTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                        /* End Calculate Misures */
                    ) AS L
                    LEFT JOIN (
                    " . $tJoinFoooter . "
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        
        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTRptRowSeq";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
             $aData = NULL;
        }

        $aErrorList = array(
            "nErrInvalidPage" => ""
        );

        $aResualt = array(
            "aPagination" => $aPagination,
            "aRptData" => $aData,
            "aError" => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Functionality: Count Data Report All
    public function FSnMCountDataReportAll($paDataWhere) {
        $tSessionID = $paDataWhere['tSessionID'];

        $tSQL = "   
            SELECT 
                COUNT(DTTMP.FTRptCode) AS rnCountPage
            FROM TRPTSBelowCostTmp AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSession    = '$tSessionID'
        ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}














