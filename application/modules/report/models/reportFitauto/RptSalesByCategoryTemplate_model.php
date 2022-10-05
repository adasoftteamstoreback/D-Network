<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RptSalesByCategoryTemplate_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxSalesByCategoryTemplate(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'ptAgnL'        => $paDataFilter['tAgnCodeSelect'],
            'ptBchL'        => $tBchCodeSelect,   
            'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'    => $paDataFilter['tDocDateTo'],
            'ptCate1From'   => FCNtAddSingleQuote($paDataFilter['tCate1From']),
            'ptCate2From'   => FCNtAddSingleQuote($paDataFilter['tCate2From']),
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
    public function FMaMRPTPagination($paDataWhere) {
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = "SELECT
                        COUNT(TMP.FTRptRowSeq) AS rnCountPage
                    FROM TRPTSalesByCategoryTemplate TMP WITH(NOLOCK)
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

    public function FSaMGetDataReport($paDataWhere) {
        
        $nPage              = $paDataWhere['nPage'];
        $tUsrSession        = $paDataWhere['tUsrSessionID'];

        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $aPagination    = '';
            $nTotalPage     = $nPage;
        }

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                    FTUsrSession                AS FTUsrSession_Footer,
                    SUM(FNPdtGroupLube)         AS FNPdtGroupLube_Footer,
                    SUM(FNPdtGroupTire)         AS FNPdtGroupTire_Footer,
                    SUM(FNPdtGroupService)      AS FNPdtGroupService_Footer,
                    SUM(FNPdtGroupOther)        AS FNPdtGroupOther_Footer
                FROM TRPTSalesByCategoryTemplate WITH(NOLOCK)
                WHERE FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'      AS FTUsrSession_Footer,
                    '0'                 AS FNPdtGroupLube_Footer,
                    '0'                 AS FNPdtGroupTire_Footer,
                    '0'                 AS FNPdtGroupService_Footer,
                    '0'                 AS FNPdtGroupOther_Footer
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
                        FROM TRPTSalesByCategoryTemplate A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L
                    LEFT JOIN (
                    " . $tJoinFoooter . " ";

        // WHERE เงื่อนไข Page
        if( $paDataWhere['nPerPage'] != 0 ){
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