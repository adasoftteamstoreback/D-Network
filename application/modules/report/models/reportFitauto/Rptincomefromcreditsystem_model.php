<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptincomefromcreditsystem_model extends CI_Model{

    public function FSnMExecStoreReport($paDataFilter){

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        //ลูกค้า
        $tCstCodeSelect = FCNtAddSingleQuote($paDataFilter['tCstCodeSelect']);
        
        $tCallStore = "{ CALL SP_RPTxSalInstallment(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptUsrSessionID'    => $paDataFilter['tSessionID'],
            'ptBchCode'         => $tBchCodeSelect,
            'ptCstCodeFrm'      => $tCstCodeSelect,
            'ptCstCodeTo'       => '',
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
            $tJoinFoooter = "   
                SELECT
                    FTUsrSessID                 AS FTUsrSession_Footer,
                    SUM(FCXsdQty)               AS FCXsdQty_Footer,
                    SUM(FCXsdSetPrice)          AS FCXsdSetPrice_Footer,
                    SUM(FCXsdAmt)               AS FCXsdAmt_Footer,
                    SUM(FCXsdDis)               AS FCXshDis_Footer,
                    SUM(FCXsdNet)               AS FCXshGrand_Footer,
                    SUM(FCXshCost)              AS FCXshCost_Footer,
                    SUM(FCXshCostIncludeVat)    AS FCXshCostIncludeVat_Footer,
                    SUM(FCXshCostTotal)         AS FCXshCostTotal_Footer,
                    SUM(FCXshProfit)            AS FCXshProfit_Footer,
                    SUM(FCXshProfitPercent)     AS FCXshProfitPercent_Footer
                FROM TRPTSalInstallmentTmp WITH(NOLOCK)
                WHERE 1=1
                    AND FTUsrSessID    = '$tUsrSession'
                    GROUP BY FTUsrSessID 
                ) T ON L.FTUsrSessID = T.FTUsrSession_Footer ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'              AS FTUsrSession_Footer,
                    '0'                         AS FCXsdQty_Footer,
                    '0'                         AS FCXsdSetPrice_Footer,
                    '0'                         AS FCXsdAmt_Footer,
                    '0'                         AS FCXshDis_Footer,
                    '0'                         AS FCXshGrand_Footer,
                    '0'                         AS FCXshCost_Footer,
                    '0'                         AS FCXshCostIncludeVat_Footer,
                    '0'                         AS FCXshCostTotal_Footer,
                    '0'                         AS FCXshProfit_Footer,
                    '0'                         AS FCXshProfitPercent_Footer
                ) T ON  L.FTUsrSessID = T.FTUsrSession_Footer ";
        }

        $tSQL = "
            SELECT
                COUNT (L.FTCstCode) OVER (PARTITION BY L.FTCstCode ORDER BY L.FTCstCode ASC) AS PARTTITIONBYCSTCODE,
                ROW_NUMBER () OVER (PARTITION BY L.FTXshDocNo ORDER BY L.FTXshDocNo ASC) AS PARTTITIONBYDOC_COUNT,
                ROW_NUMBER () OVER (PARTITION BY L.FTCstCode ORDER BY L.FTCstCode ASC) AS PARTTITIONBYCST_COUNT,
                COUNT (L.FTXshDocNo) OVER (PARTITION BY L.FTXshDocNo ORDER BY L.FTXshDocNo ASC) AS PARTTITIONBYDOC,
                DENSE_RANK () OVER (PARTITION BY L.FTCstCode ORDER BY L.FDXshDocDate , L.FTXshDocNo ASC) AS NUMBERDOC,
                L.*,
                T.FCXsdSetPrice_Footer,
                T.FCXsdQty_Footer,
                T.FCXsdAmt_Footer,
                T.FCXshDis_Footer,
                T.FCXshGrand_Footer,
                T.FCXshCost_Footer,
                T.FCXshCostIncludeVat_Footer,
                T.FCXshCostTotal_Footer,
                T.FCXshProfit_Footer,
                T.FCXshProfitPercent_Footer,
                BILLByDOC.FCXsdQty_Doc_Footer,
                BILLByDOC.FCXsdSetPrice_Doc_Footer,
                BILLByDOC.FCXsdAmt_Doc_Footer,
                BILLByDOC.FCXshDis_Doc_Footer,
                BILLByDOC.FCXshGrand_Doc_Footer,
                BILLByDOC.FCXshCost_Doc_Footer,
                BILLByDOC.FCXshCostIncludeVat_Doc_Footer,
                BILLByDOC.FCXshCostTotal_Doc_Footer,
                BILLByDOC.FCXshProfit_Doc_Footer,
                BILLByDOC.FCXshProfitPercent_Doc_Footer,
                BILLByCST.FCXsdQty_CST_Footer,
                BILLByCST.FCXsdSetPrice_CST_Footer,
                BILLByCST.FCXsdAmt_CST_Footer,
                BILLByCST.FCXshDis_CST_Footer,
                BILLByCST.FCXshGrand_CST_Footer,
                BILLByCST.FCXshCost_CST_Footer,
                BILLByCST.FCXshCostIncludeVat_CST_Footer,
                BILLByCST.FCXshCostTotal_CST_Footer,
                BILLByCST.FCXshProfit_CST_Footer,
                BILLByCST.FCXshProfitPercent_CST_Footer
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTCstCode ASC, FDXshDocDate ASC, FTXshDocNo ASC) AS RowID,
                    A.*
                FROM TRPTSalInstallmentTmp A WITH(NOLOCK)
                WHERE A.FTUsrSessID    = '$tUsrSession'
                AND A.FNRptType = 1
            ) AS L 
            LEFT JOIN (
                SELECT
                    FTXshDocNo ,
                    FTUsrSessID                 AS FTUsrSession_Doc_Footer,
                    SUM(FCXsdQty)               AS FCXsdQty_Doc_Footer,
                    SUM(FCXsdSetPrice)          AS FCXsdSetPrice_Doc_Footer,
                    SUM(FCXsdAmt)               AS FCXsdAmt_Doc_Footer,
                    SUM(FCXsdDis)               AS FCXshDis_Doc_Footer,
                    SUM(FCXsdNet)               AS FCXshGrand_Doc_Footer,
                    SUM(FCXshCost)              AS FCXshCost_Doc_Footer,
                    SUM(FCXshCostIncludeVat)    AS FCXshCostIncludeVat_Doc_Footer,
                    SUM(FCXshCostTotal)         AS FCXshCostTotal_Doc_Footer,
                    SUM(FCXshProfit)            AS FCXshProfit_Doc_Footer,
                    SUM(FCXshProfitPercent)     AS FCXshProfitPercent_Doc_Footer
                FROM TRPTSalInstallmentTmp WITH(NOLOCK)
                WHERE 1=1
                    AND FTUsrSessID    = '$tUsrSession'
                    GROUP BY FTUsrSessID , FTXshDocNo
            ) BILLByDOC ON L.FTUsrSessID = BILLByDOC.FTUsrSession_Doc_Footer AND L.FTXshDocNo = BILLByDOC.FTXshDocNo 
            LEFT JOIN (
                SELECT
                    FTCstCode ,
                    FTUsrSessID                 AS FTUsrSession_CST_Footer,
                    SUM(FCXsdQty)               AS FCXsdQty_CST_Footer,
                    SUM(FCXsdSetPrice)          AS FCXsdSetPrice_CST_Footer,
                    SUM(FCXsdAmt)               AS FCXsdAmt_CST_Footer,
                    SUM(FCXsdDis)               AS FCXshDis_CST_Footer,
                    SUM(FCXsdNet)               AS FCXshGrand_CST_Footer,
                    SUM(FCXshCost)              AS FCXshCost_CST_Footer,
                    SUM(FCXshCostIncludeVat)    AS FCXshCostIncludeVat_CST_Footer,
                    SUM(FCXshCostTotal)         AS FCXshCostTotal_CST_Footer,
                    SUM(FCXshProfit)            AS FCXshProfit_CST_Footer,
                    SUM(FCXshProfitPercent)     AS FCXshProfitPercent_CST_Footer
                FROM TRPTSalInstallmentTmp WITH(NOLOCK)
                WHERE 1=1
                    AND FTUsrSessID    = '$tUsrSession'
                    GROUP BY FTUsrSessID , FTCstCode
            ) BILLByCST ON L.FTUsrSessID = BILLByCST.FTUsrSession_CST_Footer AND L.FTCstCode = BILLByCST.FTCstCode ";
        $tSQL .= " LEFT JOIN (
                " . $tJoinFoooter . "";

        if( $paDataWhere['nPerPage'] != 0 ){ //มาจาก View HTML
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }

        $tSQL .= " ORDER BY L.RowID, L.FTCstCode , L.FTXshDocNo ASC";

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
            "aSQL"          => $tSQL,
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
                    COUNT(RPT.FTUsrSessID) AS rnCountPage
                 FROM TRPTSalInstallmentTmp RPT WITH(NOLOCK)
                 WHERE RPT.FTUsrSessID = '$tUsrSession'";

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
