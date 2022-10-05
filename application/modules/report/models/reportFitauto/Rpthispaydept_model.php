<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rpthispaydept_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxAPHisPayDebt(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptBchCode'     => $tBchCodeSelect,
            'ptSplCodeFrm'  => $paDataFilter['tPdtSupplierCodeFrom'],
            'ptSplCodeTo'   => $paDataFilter['tPdtSupplierCodeTo'],
            'pnStaylef'     => $paDataFilter['tPdtRptPhStaPaid'],
            'pdAmsDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdAmsDateTo'   => $paDataFilter['tDocDateTo']
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
                COUNT(ADJSTK_TMP.FTSplName) AS rnCountPage
            FROM TRPTAPHisPayDebtTmp ADJSTK_TMP WITH(NOLOCK)
            WHERE 1=1
            AND ADJSTK_TMP.FTUsrSessID = '$tUsrSession'
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
                    FTUsrSessID            AS FTUsrSessID_Footer,
                    SUM(FCXphGrand)        AS FCXphGrand_Footer,
                    SUM(FCXphPaid)         AS FCXphPaid_Footer,
                    SUM(FCXphLeft)         AS FCXphLeft_Footer
                FROM TRPTAPHisPayDebtTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSessID    = '$tUsrSession'
                GROUP BY FTUsrSessID ) T ON L.FTUsrSessID = T.FTUsrSessID_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "
                SELECT
                    '$tUsrSession'  AS FTUsrSessID_Footer
                ) T ON  L.FTUsrSessID = T.FTUsrSessID_Footer
            ";
        }

        $tSQL   = " SELECT
                        L.*,
                        T.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FTSplName ASC) AS RowID ,
                            ROW_NUMBER() OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC) AS rtPartitionSPL ,
                            A.*
                        FROM TRPTAPHisPayDebtTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSessID    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                    " . $tJoinFoooter . "
        ";

        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        $tSQL .= " ORDER BY L.FTSplName";
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
            FROM TRPTAPHisPayDebtTmp AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSessID    = '$tSessionID'
        ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}
