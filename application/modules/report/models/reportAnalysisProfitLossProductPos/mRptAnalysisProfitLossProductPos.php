<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptAnalysisProfitLossProductPos extends CI_Model {

    /**
     * Functionality: Call Store
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Saharat(Golf)
     * Last Modified : -
     * Return : Status Return Call Stored Procedure
     * Return Type: Array
    */
    public function FSnMExecStoreReport($paDataFilter){
        $nLangID        = $paDataFilter['nLangID'];
        $tComName       = $paDataFilter['tCompName'];
        $tRptCode       = $paDataFilter['tRptCode'];
        $tUserSession   = $paDataFilter['tUserSession'];
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']); 
        // ร้านค้า
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        // กลุ่มธุรกิจ
        $tMerCodeSelect = ($paDataFilter['bMerStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tMerCodeSelect']);
        // ประเภทเครื่องจุดขาย
        $tPosCodeSelect = ($paDataFilter['bPosStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tPosCodeSelect']);
        $tCallStore     = "{CALL SP_RPTxPSSalByProfitByLoss(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore     = array(
            'pnLngID'           => $nLangID, 
            'pnComName'         => $tComName,
            'ptRptCode'         => $tRptCode,
            'ptUsrSession'      => $tUserSession,
            'pnFilterType'      => $paDataFilter['tTypeSelect'],
            'ptBchL'            => $tBchCodeSelect,
            'ptBchF'            => $paDataFilter['tBchCodeFrom'],
            'ptBchT'            => $paDataFilter['tBchCodeTo'],
            'ptMerL'            => $tMerCodeSelect,
            'ptMerF'            => $paDataFilter['tRptMerCodeFrom'],
            'ptMerT'            => $paDataFilter['tRptMerCodeTo'],
            'ptShpL'            => $tShpCodeSelect,
            'ptShpF'            => $paDataFilter['tRptShpCodeFrom'],
            'ptShpT'            => $paDataFilter['tRptShpCodeTo'],
            'ptPosL'            => $tPosCodeSelect,
            'ptPosF'            => $paDataFilter['tRptPosCodeFrom'],
            'ptPosT'            => $paDataFilter['tRptPosCodeTo'],
            'ptChainCodeF'      => $paDataFilter['tRptPdtGrpCodeFrom'],
            'ptChainCodeT'      => $paDataFilter['tRptPdtGrpCodeTo'],
            'ptProductCodeF'    => $paDataFilter['tRptPdtCodeFrom'],
            'ptProductCodeT'    => $paDataFilter['tRptPdtCodeTo'],
            'ptDocDateF'        => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'        => $paDataFilter['tDocDateTo'],
            'FTResult'          => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if($oQuery !== FALSE){
            $nDataReturn    = 1;
        }else{
            $nDataReturn    = 0;
        }
        unset($nLangID,$tComName,$tRptCode,$tUserSession,$tBchCodeSelect,$tShpCodeSelect,$tMerCodeSelect,$tPosCodeSelect);
        unset($tCallStore,$aDataStore,$oQuery);
        return $nDataReturn;
    }

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Sahaart(Golf)
     * Last Modified : 10/05/2022 Wasin
     * Return : status
     * Return Type: Array
    */
    public function FSaMGetDataReport($paDataWhere){
        $nPage  = $paDataWhere['nPage'];
        if($paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $nTotalPage     = 1;
            $aPagination    = 0;
        }

        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter   = "
                    SELECT
                        FTUsrSession                        AS FTUsrSession_Footer,
                        CONVERT(FLOAT,SUM(FCXsdSaleQty))	AS FCXsdSaleQty_Footer,
                        CONVERT(FLOAT,SUM(FCPdtCost))       AS FCPdtCost_Footer,
                        CONVERT(FLOAT,SUM(FCXshGrand))		AS FCXshGrand_Footer,
                        CONVERT(FLOAT,SUM(FCXsdProfit))		AS FCXsdProfit_Footer,
                        CASE WHEN CONVERT(FLOAT,SUM(FCPdtCost)) <> 0 THEN ((CONVERT(FLOAT,SUM(FCXsdProfit)) / CONVERT(FLOAT,SUM(FCPdtCost)))*100) ELSE 0 END AS FCXsdProfitPercent_Footer,
                        CASE WHEN CONVERT(FLOAT,SUM(FCXshGrand)) <> 0 THEN ((CONVERT(FLOAT,SUM(FCXsdProfit)) / CONVERT(FLOAT,SUM(FCXshGrand)))*100) ELSE 0 END AS FCXsdSalePercent_Footer
                    FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                    WHERE FTUsrSession <> ''
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTUsrSession
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }else{
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXsdSaleQty_Footer,
                    0 AS FCPdtCost_Footer,
                    0 AS FCXshGrand_Footer,
                    0 AS FCXsdProfit_Footer,
                    0 AS FCXsdProfitPercent_Footer,
                    0 AS FCXsdSalePercent_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }
        // L    = List ข้อมูลทั้งหมด
        // A    = SaleDT
        // S    = Misures Summary
        $tSQL   = "
            SELECT 
                L.*,
                T.FCXsdSaleQty_Footer,
                T.FCPdtCost_Footer,
                T.FCXshGrand_Footer,
                T.FCXsdProfit_Footer,
                T.FCXsdProfitPercent_Footer,
                T.FCXsdSalePercent_Footer
            FROM (
                SELECT 
                    ROW_NUMBER() OVER(ORDER BY A.FNAppType ASC,A.FTPdtCode ASC) AS RowID, 
                    ROW_NUMBER() OVER(PARTITION BY A.FNAppType ORDER BY A.FTPdtCode ASC) AS FNFmtAllRow,
                    SUM(1) OVER(PARTITION BY A.FNAppType) AS FNFmtEndRow,
                    A.FNAppType,
                    A.FTPdtCode,
                    A.FTPdtName,
                    A.FTChainName,
                    A.FCXsdSaleQty,
                    A.FCPdtCost,
                    A.FCXshGrand,
                    A.FCXsdProfit,
                    A.FCXsdProfitPercent,
                    A.FCXsdSalePercent,
                    A.FTUsrSession,
                    S.FNRptGroupMember_SUBAPP,
                    S.FCXsdSaleQty_SUBAPP,
                    S.FCXshGrand_SUBAPP,
                    S.FCXsdProfit_SUBAPP,
                    S.FCXsdProfitPercent_SUBAPP,
                    S.FCXsdSalePercent_SUBAPP
                FROM TRPTPSTSaleProfitTmp A WITH(NOLOCK)
                LEFT JOIN (
                    SELECT
                        FNAppType AS FNAppType_SUBAPP,
                        COUNT(FNAppType) AS FNRptGroupMember_SUBAPP,
                        CONVERT(FLOAT,SUM(FCXsdSaleQty))	AS FCXsdSaleQty_SUBAPP,
                        CONVERT(FLOAT,SUM(FCPdtCost))		AS FCPdtCost_SUBAPP,
                        CONVERT(FLOAT,SUM(FCXshGrand))		AS FCXshGrand_SUBAPP,
                        CONVERT(FLOAT,SUM(FCXsdProfit))		AS FCXsdProfit_SUBAPP,
                        CASE WHEN CONVERT(FLOAT,SUM(FCPdtCost)) <> 0 THEN ((CONVERT(FLOAT,SUM(FCXsdProfit)) / CONVERT(FLOAT,SUM(FCPdtCost)))*100)	ELSE 0 END AS FCXsdProfitPercent_SUBAPP,
                        CASE WHEN CONVERT(FLOAT,SUM(FCXshGrand)) <> 0 THEN ((CONVERT(FLOAT,SUM(FCXsdProfit)) / CONVERT(FLOAT,SUM(FCXshGrand)))*100) ELSE 0 END AS FCXsdSalePercent_SUBAPP
                    FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                    WHERE FTUsrSession <> ''
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FNAppType
                ) S ON A.FNAppType = S.FNAppType_SUBAPP
                WHERE FTUsrSession <> ''
                AND A.FTUsrSession = '$tUsrSession'
            ) AS L
        ";
        if($paDataWhere['nPerPage'] != 0 ){
            $tSQL   .= "  LEFT JOIN (" . $tJoinFoooter . "";
            $tSQL   .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }else{
            $tSQL   .= "  LEFT JOIN (" . $tJoinFoooter . "";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData  = $oQuery->result_array();
        } else {
            $aData  = NULL;
        }
        $aErrorList = array(
            "nErrInvalidPage"   => ""
        );
        $aResualt = array(
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($nPage,$aPagination,$nRowIDStart,$nRowIDEnd,$nTotalPage,$tJoinFoooter);
        unset($tSQL,$oQuery,$aData,$aErrorList);
        return $aResualt;
    }

    /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 01/09/2019 Saharat(Golf)
     * Last Modified : 10/05/2022 Wasin
     * Return : Pagination
     * Return Type: Array
    */
    public function FMaMRPTPagination($paDataWhere){
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTUsrSession) AS rnCountPage
            FROM TRPTPSTSaleProfitTmp RPT WITH(NOLOCK)
            WHERE RPT.FTUsrSession    = '$tUsrSession'
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
        unset($tUsrSession,$tSQL,$oQuery,$nRptAllRecord,$nPage,$nPerPage,$nPrevPage,$nNextPage,$nRowIDStart);
        unset($nTotalPage,$nRowIDEnd);
        return $aRptMemberDet;
    }

}
