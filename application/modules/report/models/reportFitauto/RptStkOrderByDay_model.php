<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RptStkOrderByDay_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxStkMntDaily(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'      => $paDataFilter['nLangID'],
            'ptComName'     => 'AdaStoreBack',
            'tRptCode'      => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'ptBchCode'     => $tBchCodeSelect,   
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptCat1'        => FCNtAddSingleQuote($paDataFilter['tCate1From']),
            'ptCat2'        => FCNtAddSingleQuote($paDataFilter['tCate2From']),
            'ptPdtF'        => $paDataFilter['tRptPdtCodeFrom'],
            'ptPdtT'        => $paDataFilter['tRptPdtCodeTo'],
            'ptWahF'        => $paDataFilter['tWahCodeFrom'],
            'ptWahT'        => $paDataFilter['tWahCodeTo'],
            'ptPdtStaActive' => $paDataFilter['tPdtStaActive'],
            'pdDateFrm'     => $paDataFilter['tDocDateFrom'],
            'pdDateTo'      => $paDataFilter['tDocDateTo'],
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
                COUNT(STK.FTRptRowSeq) AS rnCountPage
            FROM TRPTPdtStkMntDailyTmp STK WITH(NOLOCK)
            WHERE 1=1
            AND STK.FTUsrSession = '$tUsrSession'
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
        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $aPagination    = 0;
            $nTotalPage     = 0;
        }

        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        $tSQL = "";
        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  SELECT 
                            ROW_NUMBER ( ) OVER ( PARTITION BY L.FTBchCode ORDER BY L.FTBchCode DESC ) AS PARTITION_BCH,
                            ROW_NUMBER ( ) OVER ( PARTITION BY L.FTBchCode , L.FTPdtDptCode ORDER BY L.FTBchCode  , L.FTPdtDptCode DESC ) AS PARTITION_CAT2,
                            L.* 
                        FROM ( ";
        }else{
            $tSQL .= "  ";
        }

        $tSQL   .= "SELECT
                        ROW_NUMBER ( ) OVER ( ORDER BY A.FTBchCode , A.FTPdtDptCode, A.FTRptRowSeq ASC ) AS RowID,
                        A.*
                    FROM TRPTPdtStkMntDailyTmp A WITH(NOLOCK)
                    WHERE A.FTUsrSession    = '$tUsrSession' ";

        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  ) AS L ";
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }else{
            $tSQL .= " ORDER BY A.FTRptRowSeq ASC";
        }
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