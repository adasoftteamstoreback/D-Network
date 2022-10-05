<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RptStkAllCompTextFile_model extends CI_Model {

    // Functionality: Call Stored Procedure SQL
    // Parameters:  Function Parameter
    // Creator: 25/04/2022 Wasin
    // LastUpdate:
    // Return: Status Stored Procedure
    // ReturnType: Boolen
    public function FSnMExecStoreReport($paDataFilter){
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $tCallStore     = "{ CALL SP_RPTxStockAllCompareTextfile(?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore     = [
            'ptRptCode'         => $paDataFilter['tRptCode'],
            'ptUsrSessionID'    => $paDataFilter['tSessionID'],
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'         => $tBchCodeSelect,
            'ptPdtCodeFrom'     => $paDataFilter['tPdtCodeFrom'],
            'ptPdtCodeTo'       => $paDataFilter['tPdtCodeTo'],
            'ptPdtUnitCodeFrom' => $paDataFilter['tPdtUnitCodeFrom'],
            'ptPdtUnitCodeTo'   => $paDataFilter['tPdtUnitCodeTo'],
            'ptCate1CodeFrom'   => $paDataFilter['tCate1CodeFrom'],
            'ptCate2CodeFrom'   => $paDataFilter['tCate2CodeFrom'],
            'ptLangID'          => $paDataFilter['nLangID'],
            'pnResult'          => 0
        ];
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Report In Table Temp
    // Parameters:  Function Parameter
    // Creator: 25/04/2022 Wasin
    // LastUpdate:
    // Return: Array Data In Temp
    // ReturnType: Array
    public function FSaMGetDataReport ($paDataWhere){

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

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "
                    SELECT
                        FTUsrSession        AS FTUsrSession_Footer,
                        SUM(FCXtdQty)       AS FCXtdQty_Footer,
                        SUM(FCXtdCost)      AS FCXtdCost_Footer,
                        SUM(FCXtdAmount)    AS FCXtdAmount_Footer,
                        COUNT(FTUsrSession) AS RowID_Footer
                    FROM TRPTStockAllCompareTextfileTmp WITH(NOLOCK)
                    WHERE FTUsrSession <> ''
                    AND FTUsrSession    = ".$this->db->escape($tUsrSession)."
                    GROUP BY FTUsrSession
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    ".$this->db->escape($tUsrSession)." AS FTUsrSession_Footer,
                    '0' AS FCXtdQty_Footer,
                    '0' AS FCXtdCost_Footer, 
                    '0' AS FCXtdAmount_Footer,
                    '0' AS RowID_Footer
                ) T ON  L.FTUsrSession  =   T.FTUsrSession_Footer
            ";
        }

        $tSQL   = "
            SELECT 
                ROW_NUMBER ()       OVER (PARTITION BY L.FTBchCode ORDER BY L.FTBchCode ASC) AS FNFmtPageRow,
                SUM (1)             OVER (PARTITION BY L.FTBchCode) AS FNFmtMaxPageRow,
                B.FCXtdQty_BCH_Footer       AS FCXtdQty_SumBch,
                B.FCXtdCost_BCH_Footer      AS FCXtdCost_SumBch,
                B.FCXtdAmount_BCH_Footer    AS FCXtdAmount_SumBch,
                L.*,
                T.FCXtdQty_Footer,
                T.FCXtdCost_Footer,
                T.FCXtdAmount_Footer,
                T.RowID_Footer
            FROM (
                SELECT
                    ROW_NUMBER () OVER (ORDER BY FTBchCode ASC,FTPdtCode ASC,FTPunCode ASC) AS RowID,
                    ROW_NUMBER() OVER(PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS rtPartitionBCH ,
                    COUNT(FTBchCode) OVER(PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS rtPartitionCountBCH ,
                    A.*
                FROM TRPTStockAllCompareTextfileTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession = ".$this->db->escape($tUsrSession)."
            ) AS L 
            LEFT JOIN (
                SELECT
                    FTBchCode           AS FTBchCode_BCH_Footer,
                    FTUsrSession        AS FTUsrSession_BCH_Footer,
                    SUM(FCXtdQty)       AS FCXtdQty_BCH_Footer,
                    SUM(FCXtdCost)      AS FCXtdCost_BCH_Footer,
                    SUM(FCXtdAmount)    AS FCXtdAmount_BCH_Footer,
                    COUNT(FTUsrSession) AS RowID_Footer
                FROM TRPTStockAllCompareTextfileTmp WITH(NOLOCK)
                WHERE FTUsrSession <> ''
                AND FTUsrSession    = ".$this->db->escape($tUsrSession)."
                GROUP BY FTUsrSession , FTBchCode
            ) B ON L.FTUsrSession = B.FTUsrSession_BCH_Footer AND L.FTBchCode = B.FTBchCode_BCH_Footer
            LEFT JOIN (
                " . $tJoinFoooter . " ";

        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL   .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }

        $oQuery  = $this->db->query($tSQL);
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
        unset($nPage,$aPagination,$nRowIDStart,$nRowIDEnd,$nTotalPage,$tUsrSession,$tJoinFoooter,$tSQL,$oQuery,$aData,$aErrorList);
        return $aResualt;
    }

    // Functionality: Count Page Data All 
    // Parameters:  Function Parameter
    // Creator: 25/04/2022 Wasin
    // LastUpdate:
    // Return: Array Data In Temp
    // ReturnType: Array
    private function FMaMRPTPagination($paDataWhere){
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTBchCode) AS rnCountPage
            FROM TRPTStockAllCompareTextfileTmp RPT WITH(NOLOCK)
            WHERE RPT.FTUsrSession  = '$tUsrSession'    
        ";
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

        unset($tUsrSession,$tSQL,$oQuery,$nRptAllRecord,$nPage,$nPerPage,$nPrevPage,$nNextPage,$nRowIDStart,$nTotalPage,$nRowIDEnd);
        return $aRptMemberDet;
    }




}