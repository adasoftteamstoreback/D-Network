<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptservicebycustomer_model extends CI_Model
{
    //Call Stored
    public function FSnMExecStoreReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxFollowService(?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptUsrSession'      => $paDataFilter['tSessionID'],
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'         =>($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']),
            'ptCstCodeFrm'      => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeTo'       => $paDataFilter['tCstCodeTo'],
            'ptLangID'          => $paDataFilter['nLangID'],
            'pnResult'          => 0
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

    //Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere){
        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            SELECT
                L.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode DESC) AS RowID ,
                    A.*
                FROM TRPTSVFollowServiceTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession    = '$tUsrSession'
            ) AS L ";

        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = null;
        }

        $aErrorList = array(
            "nErrInvalidPage" => "",
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

    //Count จำนวน
    private function FMaMRPTPagination($paDataWhere){
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL = "SELECT
                    COUNT(RPT.FTBchCode) AS rnCountPage
                 FROM TRPTSVFollowServiceTmp RPT WITH(NOLOCK)
                 WHERE RPT.FTUsrSession = '$tUsrSession'";

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
}
