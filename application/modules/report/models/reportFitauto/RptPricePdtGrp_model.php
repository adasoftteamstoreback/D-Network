<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RptPricePdtGrp_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxPdtPriceGrp(?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'pnFilterType'  => 2,
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'     => $tBchCodeSelect,   
            'ptPplF'        => $paDataFilter['tRptEffectivePriceGroupCodeFrom'],   
            'ptPplT'        => $paDataFilter['tRptEffectivePriceGroupCodeTo'],   
            'ptPdtF'        => $paDataFilter['tRptPdtPdtCodeFrom'],   
            'ptPdtT'        => $paDataFilter['tRptPdtPdtCodeTo'],   
            'ptDocDateF'    => $paDataFilter['tRptEffectiveDateFrom'],   
            'ptDocDateT'    => $paDataFilter['tRptEffectiveDateTo'],   
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

        $tSQL = "SELECT
                COUNT(ADJSTK_TMP.FTRptRowSeq) AS rnCountPage
            FROM TRPTPdtPriceGrpTmp ADJSTK_TMP WITH(NOLOCK)
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
        
        $nPage          = $paDataWhere['nPage'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        if( $paDataWhere['nPerPage'] != 0){ //มาจาก View HTML
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{  //มาจาก Excel
            $aPagination    = 0;
            $nTotalPage     = $nPage;
        }

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   SELECT
                                    FTUsrSession        AS FTUsrSession_Footer
                                FROM TRPTPdtPriceGrpTmp WITH(NOLOCK)
                                WHERE 1=1
                                AND FTUsrSession    = '$tUsrSession'
                                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        } else { // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   SELECT
                                    '$tUsrSession'  AS FTUsrSession_Footer
                                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL       = "";
        $tSQL       .= " SELECT
                            L.*,
                            T.*
                        FROM ( "; 
        $tSQL       .= " SELECT
                                ROW_NUMBER() OVER(ORDER BY FTRptRowSeq DESC) AS RowID ,
                                A.*
                            FROM TRPTPdtPriceGrpTmp A WITH(NOLOCK)
                            WHERE A.FTUsrSession    = '$tUsrSession'
                        ) AS L
                        LEFT JOIN (
                        " . $tJoinFoooter . " ";

        if( $paDataWhere['nPerPage'] != 0 ){ //มาจาก View HTML
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }

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
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Functionality: Count Data Report All
    public function FSnMCountDataReportAll($paDataWhere) {
        $tSessionID = $paDataWhere['tSessionID'];
        $tSQL = "SELECT 
                COUNT(DTTMP.FTRptCode) AS rnCountPage
            FROM TRPTPdtPriceGrpTmp AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSession    = '$tSessionID' ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}