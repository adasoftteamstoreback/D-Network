<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RptSalesByBills_model extends CI_Model
{

    public function FSnMExecStoreReport($paDataFilter)
    {

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);


        //ถ้าวันที่ไม่ได้ระบุ
        if ($paDataFilter['tDayFrom'] == '' || $paDataFilter['tDayFrom'] == null || $paDataFilter['tDayTo'] == '' || $paDataFilter['tDayTo'] == null) {
            $ptDocDateFrm   = null;
            $ptDocDateTo    = null;
        } else {
            $ptDocDateFrm   = $paDataFilter['tRptYearCode'] . '-' . $paDataFilter['tMonth'] . '-' . str_pad($paDataFilter['tDayFrom'], 2, '0', STR_PAD_LEFT);
            $ptDocDateTo    = $paDataFilter['tRptYearCode'] . '-' . $paDataFilter['tMonth'] . '-' . str_pad($paDataFilter['tDayTo'], 2, '0', STR_PAD_LEFT);
        }

        $tCallStore = "{ CALL SP_RPTxSalesByCountBills(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'ptAgnL'        => $paDataFilter['tAgnCodeSelect'],
            'ptBchL'        => $tBchCodeSelect,
            'tMonth'        => $paDataFilter['tMonth'],
            'tRptYearCode'  => $paDataFilter['tRptYearCode'],
            'ptDocDateFrm'  => $ptDocDateFrm,
            'ptDocDateTo'   => $ptDocDateTo,
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

    //หาจำนวน page
    public function FMaMRPTPagination($paDataWhere)
    {
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = "SELECT
                        COUNT(TMP.FTRptRowSeq) AS rnCountPage
                    FROM TRPTSalesByCountBillsTmp TMP WITH(NOLOCK)
                    WHERE 1=1
                    AND TMP.FTUsrSession = '$tUsrSession' ";

        $oQuery             = $this->db->query($tSQL);
        $nRptAllRecord      = $oQuery->row_array()['rnCountPage'];
        $nPage              = $paDataWhere['nPage'];
        $nPerPage           = $paDataWhere['nPerPage'];
        $nPrevPage          = $nPage - 1;
        $nNextPage          = $nPage + 1;
        $nRowIDStart        = (($nPerPage * $nPage) - $nPerPage); //RowId Start
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

    public function FSaMGetDataReport($paDataWhere)
    {

        $nPage              = $paDataWhere['nPage'];
        $tUsrSession        = $paDataWhere['tUsrSessionID'];

        if ($paDataWhere['nPerPage'] != 0) {
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        } else {
            $aPagination    = '';
            $nTotalPage     = $nPage;
        }

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                    FTUsrSession                AS FTUsrSession_Footer,
                    SUM(FNBillCount)            AS FNBillCount_Footer,
                    SUM(FNBillPerDay)           AS FNBillPerDay_Footer,
                    SUM(FCXsdQty)               AS FCXsdQty_Footer,
                    SUM(FCXshTotal)             AS FCXshTotal_Footer,
                    SUM(FCXshDis)               AS FCXshDis_Footer,
                    SUM(FCXshGrand)             AS FCXshGrand_Footer,
                    SUM(FCXshVatable)           AS FCXshVatable_Footer,
                    SUM(FCXshTotalVat)          AS FCXshTotalVat_Footer
                FROM TRPTSalesByCountBillsTmp WITH(NOLOCK)
                WHERE FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'      AS FTUsrSession_Footer,
                    '0'                 AS FNBillCount_Footer,
                    '0'                 AS FNBillPerDay_Footer,
                    '0'                 AS FCXsdQty_Footer,
                    '0'                 AS FCXshTotal_Footer
                    '0'                 AS FCXshDis_Footer
                    '0'                 AS FCXshGrand_Footer
                    '0'                 AS FCXshVatable_Footer
                    '0'                 AS FCXshTotalVat_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        $tSQL   = " SELECT
                        L.*,
                        T.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FTRptRowSeq DESC) AS RowID ,
                            A.*
                        FROM TRPTSalesByCountBillsTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                    " . $tJoinFoooter . " ";

        // WHERE เงื่อนไข Page
        if ($paDataWhere['nPerPage'] != 0) {
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }

        $tSQL .= " ORDER BY L.FTRptRowSeq";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData      = $oQuery->result_array();
        } else {
            $aData     = NULL;
        }

        $aErrorList = array(
            "nErrInvalidPage" => ""
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
}
