<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptinventorytranfer_model extends CI_Model
{
    //Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxPdtHisTnfWah(?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID'       => $paDataFilter['nLangID'],
            'pnComName'     => $paDataFilter['tCompName'],
            'ptRptCode'     => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tSessionID'],
            'pnFilterType'  => $paDataFilter['tTypeSelect'],
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptBchL'        => $tBchCodeSelect,
            'ptWahF'        => $paDataFilter['tWahCodeFromOut'],
            'ptWahT'        => $paDataFilter['tWahCodeFromIn'],
            'ptPdtF'        => $paDataFilter['tPdtCodeFrom'],
            'ptPdtT'        => $paDataFilter['tPdtCodeTo'],
            'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'    => $paDataFilter['tDocDateTo'],
            'FNResult'      => 0

        );


        $oQuery = $this->db->query($tCallStore, $aDataStore);
        // echo $this->db->last_query();
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    //Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere)
    {
        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // $tUsrSession    = '0000220211105144828';


        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT
                FTUsrSession            AS FTUsrSession_Footer
                FROM TRPTPdtHisTnfWahTmp WITH(NOLOCK)
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


        $tSQL = "
            SELECT
                ROW_NUMBER() OVER(PARTITION BY L.FDXthDocDate ORDER BY  L.FTXthDocNo ASC) AS FNFmtPageRow,
                SUM(1) OVER (PARTITION BY L.FTBchCode) AS FNFmtMaxPageRow,
                L.*,
                S.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FDXthDocDate ASC, FTXthDocNo ASC) AS RowID,
                    ROW_NUMBER() OVER(PARTITION BY FDXthDocDate ORDER BY  FDXthDocDate ASC) AS FNFmtAllRow,
                    ROW_NUMBER() OVER(PARTITION BY FTXthDocNo ORDER BY FTXthDocNo ASC) AS FNFmtAllRowDoc, 
                    SUM(1) OVER (PARTITION BY FTXthDocNo) AS FNFmtEndRow,
                    A.*
                FROM TRPTPdtHisTnfWahTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession    = '$tUsrSession'
            ) AS L  
            LEFT JOIN (
                SELECT
                    FTUsrSession            AS FTUsrSession_Footer,
                    FTBchCode              AS BchCode_Footer
                FROM TRPTPdtHisTnfWahTmp WITH(NOLOCK)
                WHERE FTUsrSession    = '$tUsrSession'
                GROUP BY FTBchCode, FTUsrSession
            ) S ON L.FTUsrSession = S.FTUsrSession_Footer AND L.FTBchCode = S.BchCode_Footer 
            
            LEFT JOIN (
                " . $tJoinFoooter . "";

        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";


        // print_r($tSQL);
        // die();
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query(); die();
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
    private function FMaMRPTPagination($paDataWhere)
    {
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // $tUsrSession    = '0000220211105144828';
        $tSQL = "SELECT
                    COUNT(RPT.FTBchCode) AS rnCountPage
                 FROM TRPTPdtHisTnfWahTmp RPT WITH(NOLOCK)
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
