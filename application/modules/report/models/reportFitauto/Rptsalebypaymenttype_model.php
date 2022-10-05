<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptsalebypaymenttype_model extends CI_Model
{
    //Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxSaleRCByBchTmp(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptUsrSessionID'    => $paDataFilter['tSessionID'],
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'         => $tBchCodeSelect,
            'pdDocDateFrm'      => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'       => $paDataFilter['tDocDateTo'],
            'ptRcvCodeFrm'      => $paDataFilter['tRcvCodeFrom'],
            'ptRcvCodeTo '      => $paDataFilter['tRcvCodeTo'],
            'ptLangID'          => $paDataFilter['nLangID'],
            'pnResult'          => 0
        );

        // 'pdDocDateFrm'      => '2021-09-21',
        // 'pdDocDateTo'       => '2021-10-01',

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


        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT
                FTUsrSession            AS FTUsrSession_Footer,
                SUM(FCXrcNet)           AS FCXidQty_Footer,
                COUNT(FTUsrSession)     AS RowID_Footer
                FROM TRPTSaleRCByBchTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer,
                    '0'             AS FCXidQty_Footer,
                    '0'             AS RowID_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        $tSQL = "
            SELECT
                ROW_NUMBER ()   OVER (PARTITION BY L.FTBchCode ORDER BY L.FTBchCode ASC, L.FTRcvCode ASC, L.FDCreateOn DESC )   AS FNFmtPageRow,
                SUM (1)         OVER (PARTITION BY L.FTBchCode) AS FNFmtMaxPageRow,
                SUM (FCXrcNet) 	OVER (PARTITION BY FTBchCode)   AS SumSubFooter,
                L.*,
                T.FCXidQty_Footer,
                T.RowID_Footer
            FROM (
                SELECT
                    ROW_NUMBER ()   OVER (ORDER BY  FTBchCode ASC, FTRcvCode ASC, FDCreateOn DESC ) AS RowID,
                    ROW_NUMBER ()   OVER (PARTITION BY FTBchCode,FTRcvCode ORDER BY FTBchCode ASC, FTRcvCode ASC, FDCreateOn DESC ) AS FNFmtAllRow,
                    SUM(1)          OVER (PARTITION BY FTBchCode,FTRcvCode) AS FNFmtEndRow,
                    SUM(FCXrcNet)   OVER (PARTITION BY FTBchCode,FTRcvCode) AS FCSumSumRC,
                    A.* 
                FROM TRPTSaleRCByBchTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession    = '$tUsrSession'
            ) AS L  LEFT JOIN (
                " . $tJoinFoooter . "";

        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";


        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();
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
        $tSQL = "SELECT
                    COUNT(RPT.FTBchCode) AS rnCountPage
                 FROM TRPTSaleRCByBchTmp RPT WITH(NOLOCK)
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
