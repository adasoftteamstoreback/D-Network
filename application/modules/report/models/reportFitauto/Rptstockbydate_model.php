<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptstockbydate_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $nLangID        = $paDataFilter['nLangID'];
        $tRptCode       = $paDataFilter['tRptCode'];
        $tCallStore     = "{ CALL SP_RPTxStockCardByDate(?,?,?,?,?,?,?,?,?,?,?,?,?) }";

        //ถ้าวันที่ไม่ได้ระบุ
        if($paDataFilter['tDayFrom'] == '' || $paDataFilter['tDayFrom'] == null || $paDataFilter['tDayTo'] == '' || $paDataFilter['tDayTo'] == null){
            $ptDocDateFrm   = null;
            $ptDocDateTo    = null;
        }else{
            $ptDocDateFrm   = $paDataFilter['tRptYearCode'].'-'.$paDataFilter['tMonth'].'-'.str_pad($paDataFilter['tDayFrom'], 2, '0', STR_PAD_LEFT);
            $ptDocDateTo    = $paDataFilter['tRptYearCode'].'-'.$paDataFilter['tMonth'].'-'.str_pad($paDataFilter['tDayTo'], 2, '0', STR_PAD_LEFT); 
        }

        $aDataStore = array(
            'pnLngID'       => $nLangID,
            'ptRptCode'     => $tRptCode,
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'ptBchCode'     => $tBchCodeSelect,
            'tPdtCodeFrom'  => $paDataFilter['tPdtCodeFrom'],
            'tPdtCodeTo'    => $paDataFilter['tPdtCodeTo'],
            'tWahCodeFrom'  => $paDataFilter['tWahCodeFrom'],
            'tWahCodeTo'    => $paDataFilter['tWahCodeTo'],
            'tMonth'        => $paDataFilter['tMonth'],
            'tRptYearCode'  => $paDataFilter['tRptYearCode'],
            'ptDocDateFrm'  => $ptDocDateFrm,
            'ptDocDateTo'   => $ptDocDateTo,
            'FNResult'      => 0,
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
                COUNT(ADJSTK_TMP.FTPdtName) AS rnCountPage
            FROM TRPTTaxStockCardByDateTmp ADJSTK_TMP WITH(NOLOCK)
            WHERE 1=1
            AND ADJSTK_TMP.FTUsrSession = '$tUsrSession'
        ";

        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage - 1;
        $nNextPage      = $nPage + 1;
        $nRowIDStart    = (($nPerPage * $nPage) - $nPerPage); //RowId Start
        if ($nRptAllRecord <= $nPerPage) {
            $nTotalPage = 1;
        } else if (($nRptAllRecord % $nPerPage) == 0) {
            $nTotalPage = ($nRptAllRecord / $nPerPage);
        } else {
            $nTotalPage = ($nRptAllRecord / $nPerPage) + 1;
            $nTotalPage = (int) $nTotalPage;
        }

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

        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT
                    FTUsrSession                                AS FTUsrSession_Footer,
                    SUM(CAST(FCStkQtyBal AS DECIMAL(10,2)))     AS FCStkQtyBal_Footer,
                    SUM(CAST(FCStkCostStd AS DECIMAL(10,2)))    AS FCStkCostStd_Footer
                FROM TRPTTaxStockCardByDateTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer,
                    0               AS FCStkQtyBal_Footer,
                    0               AS FCStkCostStd_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL   = " SELECT
                        L.*,
                        T.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FTBchCode , FTWahCode ASC) AS RowID ,
                            ROW_NUMBER() OVER(PARTITION BY FTBchCode ORDER BY FTBchCode ) AS RowIDByBCH ,
				            ROW_NUMBER() OVER(PARTITION BY FTBchCode , FTWahCode ORDER BY FTBchCode , FTWahCode ) AS RowIDByWAH ,
                            A.*
                        FROM TRPTTaxStockCardByDateTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                    " . $tJoinFoooter . " ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTBchCode , L.FTWahCode ";

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
            FROM TRPTTaxStockCardByDateTmp AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSession    = '$tSessionID'
        ";
        echo $tSQL;
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}
