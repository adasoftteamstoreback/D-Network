<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptdebtoroverdue_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxAccruedReceivable(?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptAgnCode'     => $paDataFilter['tAgnCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'ptBchCode'     => $tBchCodeSelect,
            'ptCstCodeFrom'  => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeTo'   => $paDataFilter['tCstCodeTo'],
            'pdDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdDateTo'   => $paDataFilter['tDocDateTo'],
            'pnLangID'      => $paDataFilter['nLangID']
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        // print_r($oQuery);
        // exit();
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
                COUNT(ADJSTK_TMP.FTUsrCode) AS rnCountPage
            FROM TRPTAccruedReceiTmp ADJSTK_TMP WITH(NOLOCK)
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

        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                            FTUsrSession                        AS FTUsrSession_Footer,
                            SUM(FCXshGrand)                     AS FCXshGrand_Footer,
                            SUM(FCXshPaid)                      AS FCXshPaid_Footer,
                            SUM(FCXshLeft)                      AS FCXshLeft_Footer
                        FROM TRPTAccruedReceiTmp WITH(NOLOCK)
                        WHERE 1=1
                        AND FTUsrSession    = '$tUsrSession'
                        GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        } else {
            $tJoinFoooter = " SELECT
                            '$tUsrSession'  AS FTUsrSession_Footer
                        ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL   = " SELECT
                        L.*,
                        T.*,
                        C.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FTCstCode ASC) AS RowID ,
                            ROW_NUMBER() OVER(PARTITION BY FTCstCode ORDER BY FTCstCode ASC) AS rtPartitionCST ,
                            COUNT(FTCstCode) OVER(PARTITION BY FTCstCode ORDER BY FTCstCode ASC) AS rtPartitionCountCST ,
                            A.*
                        FROM TRPTAccruedReceiTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                        SELECT
                            FTCstCode                           AS FTCstCode_CST_Footer,
                            FTUsrSession                        AS FTUsrSession_CST_Footer,
                            SUM(FCXshGrand)                     AS FCXshGrand_CST_Footer,
                            SUM(FCXshPaid)                      AS FCXshPaid_CST_Footer,
                            SUM(FCXshLeft)                      AS FCXshLeft_CST_Footer
                        FROM TRPTAccruedReceiTmp WITH(NOLOCK)
                        WHERE 1=1
                        AND FTUsrSession    = '$tUsrSession'
                        GROUP BY FTUsrSession , FTCstCode
                    ) C ON L.FTUsrSession = C.FTUsrSession_CST_Footer AND L.FTCstCode = C.FTCstCode_CST_Footer
                    LEFT JOIN (
                    " . $tJoinFoooter . "  ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTCstCode";
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
            FROM TRPTAccruedReceiTmp AS DTTMP WITH(NOLOCK)
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
