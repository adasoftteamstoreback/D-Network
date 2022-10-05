<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptpurhistorypdt_model extends CI_Model {
    /**
     * Functionality: Call Store
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Status Return Call Stored Procedure
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter){
        $nLangID = $paDataFilter['nLangID'];
        $tComName = $paDataFilter['tCompName'];
        $tRptCode = $paDataFilter['tRptCode'];
        $tUserSession = $paDataFilter['tUserSession'];
        // ร้านค้า
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        if ($paDataFilter['tPdtRptPdtType']=="0") {
          $tPdtRptPdtType = NULL;
        }else {
          $tPdtRptPdtType = $paDataFilter['tPdtRptPdtType'];
        }
        if ($paDataFilter['tPdtRptPdtType']=="0") {
          $tPdtRptPdtType = NULL;
        }else {
          $tPdtRptPdtType = $paDataFilter['tPdtRptPdtType'];
        }
        if ($paDataFilter['tPdtRptStaVat']=="0") {
          $tPdtRptStaVat = NULL;
        }else {
          $tPdtRptStaVat = $paDataFilter['tPdtRptStaVat'];
        }
        $tCallStore = "{CALL SP_RPTxPurHisPdtBySpl(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
          'pnLngID' => $nLangID,
          'pnComName' => $tComName,
          'ptRptCode' => $tRptCode,
          'ptUsrSession' => $tUserSession,
          'pnFilterType' => $paDataFilter['tTypeSelect'],
          'ptAgnL'=> $paDataFilter['tAgnCode'],
          'ptBchL'=> $tBchCodeSelect,
          'ptShpL'=> $tShpCodeSelect,
          'ptStaApv' => $paDataFilter['tPdtRptPhStaApv'],
          'ptStaPaid' => $paDataFilter['tPdtRptPhStaPaid'],
          'ptSplF' => $paDataFilter['tPdtSupplierCodeFrom'],
          'ptSplT' => $paDataFilter['tPdtSupplierCodeTo'],
          'ptSgpF' => $paDataFilter['tPdtSgpCodeFrom'],
          'ptSgpT' => $paDataFilter['tPdtSgpCodeTo'],
          'ptStyF' => $paDataFilter['tPdtStyCodeFrom'],
          'ptStyT' => $paDataFilter['tPdtStyCodeTo'],
          'ptPdtF' => $paDataFilter['tPdtCodeFrom'],
          'ptPdtT' => $paDataFilter['tPdtCodeTo'],
          'ptPgpF' => $paDataFilter['tPdtGrpCodeFrom'],
          'ptPgpT' => $paDataFilter['tPdtGrpCodeTo'],
          'ptPtyF' => $paDataFilter['tPdtTypeCodeFrom'],
          'ptPtyT' => $paDataFilter['tPdtTypeCodeTo'],
          'ptPbnF' => $paDataFilter['tPdtBrandCodeFrom'],
          'ptPbnT' => $paDataFilter['tPdtBrandCodeTo'],
          'ptPmoF' => $paDataFilter['tPdtModelCodeFrom'],
          'ptPmoT' => $paDataFilter['tPdtModelCodeTo'],
          'ptSaleType' => $paDataFilter['tPdtType'],
          'ptPdtActive' => $paDataFilter['tPdtStaActive'],
          'PdtStaVat' => $paDataFilter['tPdtRptStaVat'],
          'ptDocDateF' => $paDataFilter['tRptDocDateFrom'],
          'ptDocDateT' => $paDataFilter['tRptDocDateTo'],
          'FNResult' => 0,
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
         // echo $this->db->last_query();
         // exit();
        if($oQuery !== FALSE){
            unset($oQuery);
            return 1;
        }else{
            unset($oQuery);
            return 0;
        }
    }

    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */
    public function FSnMCountRowInTemp($paParams){

        $tComName = $paParams['tCompName'];
        $tRptCode = $paParams['tRptCode'];
        $tUsrSession = $paParams['tSessionID'];

        $tSQL = "
            SELECT
                TMP.FTRptCode
            FROM TRPTPurHisPdtBySplTmp TMP WITH(NOLOCK)
            WHERE 1=1
            AND TMP.FTComName = '$tComName'
            AND TMP.FTRptCode = '$tRptCode'
            AND TMP.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : status
     * Return Type: Array
     */

         public function FSaMGetDataReport($paDataWhere) {

             $nPage          = $paDataWhere['nPage'];
             $aPagination    = $this->FMaMRPTPagination($paDataWhere);
             $nRowIDStart    = $aPagination["nRowIDStart"];
             $nRowIDEnd      = $aPagination["nRowIDEnd"];
             $nTotalPage     = $aPagination["nTotalPage"];
             $tUsrSession    = $paDataWhere['tUsrSessionID'];

             // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
             if ($nPage == $nTotalPage) {
                 $tJoinFoooter = " SELECT
                                 FTUsrSession                        AS FTUsrSession_Footer,
                                 SUM(FCXpdQty)                     AS FCXpdQty_Footer,
                                 SUM(FCXpdDis)                      AS FCXpdDis_Footer,
                                 SUM(FCXpdValue)                      AS FCXpdValue_Footer,
                                 SUM(FCXpdVat)                      AS FCXpdVat_Footer,
                                 SUM(FCXpdNetAmt)                      AS FCXpdNetAmt_Footer
                             FROM TRPTPurHisPdtBySplTmp WITH(NOLOCK)
                             WHERE 1=1
                             AND FTUsrSession    = '$tUsrSession'
                             GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
             } else {
                 $tJoinFoooter = " SELECT
                                 '$tUsrSession'  AS FTUsrSession_Footer
                             ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
             }

             $tSQL   = " SELECT
                             L.*,
                             T.*,
                             C.*
                         FROM (
                             SELECT
                                 ROW_NUMBER() OVER(ORDER BY FTSplCode ASC) AS RowID ,
                                 ROW_NUMBER() OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC) AS rtPartitionCST ,
                                 COUNT(FTSplCode) OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC) AS rtPartitionCountCST ,
                                 A.*
                             FROM TRPTPurHisPdtBySplTmp A WITH(NOLOCK)
                             WHERE A.FTUsrSession    = '$tUsrSession'
                         ) AS L
                         LEFT JOIN (
                             SELECT
                                 FTSplCode                           AS FTSplCode_SPL_Footer,
                                 FTUsrSession                        AS FTUsrSession_SPL_Footer,
                                 SUM(FCXpdQty)                     AS FCXpdQty_SPL_Footer,
                                 SUM(FCXpdDis)                      AS FCXpdDis_SPL_Footer,
                                 SUM(FCXpdValue)                      AS FCXpdValue_SPL_Footer,
                                 SUM(FCXpdVat)                      AS FCXpdVat_SPL_Footer,
                                 SUM(FCXpdNetAmt)                      AS FCXpdNetAmt_SPL_Footer
                             FROM TRPTPurHisPdtBySplTmp WITH(NOLOCK)
                             WHERE 1=1
                             AND FTUsrSession    = '$tUsrSession'
                             GROUP BY FTUsrSession , FTSplCode
                         ) C ON L.FTUsrSession = C.FTUsrSession_SPL_Footer AND L.FTSplCode = C.FTSplCode_SPL_Footer
                         LEFT JOIN (
                         " . $tJoinFoooter . "  ";

             // WHERE เงื่อนไข Page
             $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

             //สั่ง Order by ตามข้อมูลหลัก
             $tSQL .= " ORDER BY L.FTSplCode";
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




    /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    private function FMaMRPTPagination($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            SELECT
                TSPT.FTRptCode
            FROM TRPTPurHisPdtBySplTmp TSPT WITH(NOLOCK)
            WHERE TSPT.FTComName = '$tComName'
            AND TSPT.FTRptCode = '$tRptCode'
            AND TSPT.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->num_rows();
        $nPage = $paDataWhere['nPage'];

        $nPerPage = $paDataWhere['nPerPage'];

        $nPrevPage = $nPage-1;
        $nNextPage = $nPage+1;
        $nRowIDStart = (($nPerPage*$nPage)-$nPerPage); //RowId Start
        if($nRptAllRecord<=$nPerPage){
            $nTotalPage = 1;
        }else if(($nRptAllRecord % $nPerPage)==0){
            $nTotalPage = ($nRptAllRecord/$nPerPage) ;
        }else{
            $nTotalPage = ($nRptAllRecord/$nPerPage)+1;
            $nTotalPage = (int)$nTotalPage;
        }

        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if($nRowIDEnd > $nRptAllRecord){
            $nRowIDEnd = $nRptAllRecord;
        }

        $aRptMemberDet = array(
            "nTotalRecord" => $nRptAllRecord,
            "nTotalPage" => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart" => $nRowIDStart,
            "nRowIDEnd" => $nRowIDEnd,
            "nPrevPage" => $nPrevPage,
            "nNextPage" => $nNextPage,
            "nPerPage" => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            UPDATE TRPTPurHisPdtBySplTmp
                SET TRPTPurHisPdtBySplTmp.FNRowPartID = B.PartID
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(ORDER BY FNRowPartID ASC) AS PartID ,TMP.FTRptRowSeq

                    FROM TRPTPurHisPdtBySplTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTComName = '$tComName'
                    AND TMP.FTRptCode = '$tRptCode'
                    AND TMP.FTUsrSession = '$tUsrSession'
        ";

        $tSQL .= "
            ) AS B
            WHERE 1=1
            AND TRPTPurHisPdtBySplTmp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTPurHisPdtBySplTmp.FTComName = '$tComName'
            AND TRPTPurHisPdtBySplTmp.FTRptCode = '$tRptCode'
            AND TRPTPurHisPdtBySplTmp.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
    }

}
