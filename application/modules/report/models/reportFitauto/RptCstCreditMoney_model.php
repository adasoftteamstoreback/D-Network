<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptcstcreditmoney_model extends CI_Model
{
    //Call Stored
    public function FSnMExecStoreReport($paDataFilter) {
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore     = "{ CALL SP_RPTxSalTwoFleet(?,?,?,?,?,?,?) }";
        $aDataStore     = array(
            'ptUsrSession'      => $paDataFilter['tSessionID'],
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'         => $tBchCodeSelect,
            'pdDocDateFrm'      => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'       => $paDataFilter['tDocDateTo'],
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

        //ถ้าเป็นหน้าสุดท้าย
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                    SELECT
                        FTUsrSession            AS FTUsrSession_Footer,
                        SUM(FCXsdQtyAll)        AS FCXsdQtyAll_Total,
                        SUM(FCXsdAmtB4DisChg)   AS FCXsdAmtB4DisChg_Total,
                        SUM(FCXsdDis)           AS FCXsdDis_Total,
                        SUM(FCXsdNetAfHD)       AS FCXsdNetAfHD_Total
                    FROM TRPTSalTwoFleetTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTUsrSession 
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL = "
            SELECT
                L.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode ASC , FDXshDocDate ASC , FTXshDocNo ASC , FTPdtCode ASC ) AS RowID ,
                    A.*
                FROM TRPTSalTwoFleetTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession = '$tUsrSession'
            ) AS L ";
        $tSQL .= " LEFT JOIN (" . $tJoinFoooter . " ";
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
                 FROM TRPTSalTwoFleetTmp RPT WITH(NOLOCK)
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
