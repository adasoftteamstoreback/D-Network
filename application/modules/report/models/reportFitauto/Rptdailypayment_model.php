<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptdailypayment_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxHisSplPayment(?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptUsrSession'      => $paDataFilter['tSessionID'],
            'pnLangID'          => $paDataFilter['nLangID'],
            'ptBchCode'         => $tBchCodeSelect,
            'ptSplCodeFrm'      => $paDataFilter['tPdtSupplierCodeFrom'],
            'ptSplCodeTo'       => $paDataFilter['tPdtSupplierCodeTo'],
            'pdLastPayDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdLastPayDateTo'   => $paDataFilter['tDocDateTo']
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
            FROM TRPTxHisSplPayment ADJSTK_TMP WITH(NOLOCK)
            WHERE 1=1
            AND ADJSTK_TMP.FTUsrSession = '$tUsrSession' ";

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
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                    FTUsrSession            AS FTUsrSession_Footer,
                    SUM(FCXphGrand)         AS FCXphGrand_Footer,
                    SUM(FCXphPaid)          AS FCXphPaid_Footer,
                    SUM(FCXphLeft)          AS FCXphLeft_Footer
                FROM TRPTxHisSplPayment WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        $tSQL   = " SELECT
                        L.*,
                        T.*,
                        C.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FDXphLastPay ASC) AS RowID ,
                            COUNT(FTSplCode) OVER(PARTITION BY FTSplCode , CONVERT(VARCHAR, FDXphLastPay, 103) ORDER BY FTSplCode ASC) AS rtPartitionSPL ,
                            COUNT(CONVERT(VARCHAR, FDXphLastPay, 103)) OVER(PARTITION BY CONVERT(VARCHAR, FDXphLastPay, 103) ORDER BY CONVERT(VARCHAR, FDXphLastPay, 103) ASC) AS rtPartitionDate ,
                            ROW_NUMBER () OVER (PARTITION BY CONVERT (VARCHAR, FDXphLastPay, 103) ORDER BY FTSplCode ASC) AS rtPartitionDateNumber,
                            CONVERT(VARCHAR, FDXphLastPay, 103) AS FDXphLastPayFormat ,
                            A.*
                        FROM TRPTxHisSplPayment A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                        SELECT
                            FTUsrSession                        AS FTUsrSession_Date_Footer,
                            CONVERT(VARCHAR, FDXphLastPay, 103)	AS FDXphLastPay_Date_Footer,
                            SUM(FCXphGrand)                     AS FCXphGrand_Date_Footer,
                            SUM(FCXphPaid)                      AS FCXphPaid_Date_Footer,
                            SUM(FCXphLeft)                      AS FCXphLeft_Date_Footer
                        FROM TRPTxHisSplPayment WITH(NOLOCK)
                        WHERE 1=1
                        AND FTUsrSession    = '$tUsrSession'
                        GROUP BY FTUsrSession , CONVERT(VARCHAR, FDXphLastPay, 103) ) C ON L.FTUsrSession = C.FTUsrSession_Date_Footer AND L.FDXphLastPayFormat = C.FDXphLastPay_Date_Footer
                    LEFT JOIN (
                    " . $tJoinFoooter . " ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= "  ORDER BY
        L.FTSplCode,
        L.FDXphLastPayFormat,
        L.rtPartitionSPL,
        L.rtPartitionDate,
        L.rtPartitionDateNumber ";
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

        $tSQL = " SELECT
                COUNT(DTTMP.FTRptCode) AS rnCountPage
            FROM TRPTxHisSplPayment AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSession    = '$tSessionID' ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}
