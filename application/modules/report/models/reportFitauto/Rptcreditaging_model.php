<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptcreditaging_model extends CI_Model {

    
    public function FSnMExecStoreReport($paDataFilter){
        $nLangID        = $paDataFilter['nLangID'];
        $tComName       = $paDataFilter['tCompName'];
        $tRptCode       = $paDataFilter['tRptCode'];
        $tUserSession   = $paDataFilter['tUserSession'];
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $tCallStore     = "{CALL SP_RPTxPurCreditorAge(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

         //สถานะเอกสาร
        if($paDataFilter['tStaApv'] == 1){  //รออนุมัติ
            $tStaApv = ''; 
        }else if($paDataFilter['tStaApv'] == 2){ //อนุมัติแล้ว
            $tStaApv = 1;
        }else if($paDataFilter['tStaApv'] == 3){ //ยกเลิก
            $tStaApv = 3;
        }else{
            $tStaApv = '';
        }

        $aDataStore     = array(
          'pnLngID'         => $nLangID,
          'pnComName'       => $tComName,
          'ptRptCode'       => $tRptCode,
          'ptUsrSession'    => $tUserSession,
          'pnFilterType'    => $paDataFilter['tTypeSelect'],
          'ptAgnL'          => $paDataFilter['tAgnCode'],
          'ptBchL'          => $tBchCodeSelect,
          'ptShpL'          => $tShpCodeSelect,
          'ptStaApv'        => $tStaApv,
          'ptStaPaid'       => $paDataFilter['tPdtRptPhStaPaid'],
          'ptSplF'          => $paDataFilter['tPdtSupplierCodeFrom'],
          'ptSplT'          => $paDataFilter['tPdtSupplierCodeTo'],
          'ptSgpF'          => $paDataFilter['tPdtSgpCodeFrom'],
          'ptSgpT'          => $paDataFilter['tPdtSgpCodeTo'],
          'ptStyF'          => $paDataFilter['tPdtStyCodeFrom'],
          'ptStyT'          => $paDataFilter['tPdtStyCodeTo'],
          'ptDocDateF'      => $paDataFilter['tRptDocDateFrom'],
          'ptDocDateT'      => $paDataFilter['tRptDocDateTo'],
          'FNResult'        => 0,
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
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
            FROM TRPTPurCreditorAgeTmp TMP WITH(NOLOCK)
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
    public function FSaMGetDataReport($paDataWhere){
        $nPage = $paDataWhere['nPage'];

        // Call Data Pagination
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

         if ($nPage == $nTotalPage) {
            $tJoinFoooter = " SELECT
                            FTUsrSession                        AS FTUsrSession_Footer,
                            SUM(FCXphBFDue60)                   AS FCXphBFDue60_Footer,
                            SUM(FCXphBFDue31And60)              AS FCXphBFDue31And60_Footer,
                            SUM(FCXphBFDue0And30)               AS FCXphBFDue0And30_Footer,
                            SUM(FCXphOVDue1)                    AS FCXphOVDue1_Footer,
                            SUM(FCXphOVDue2And7)                AS FCXphOVDue2And7_Footer,
                            SUM(FCXphOVDue8And15)               AS FCXphOVDue8And15_Footer,
                            SUM(FCXphOVDue16And30)              AS FCXphOVDue16And30_Footer,
                            SUM(FCXphOVDue31And60)              AS FCXphOVDue31And60_Footer,
                            SUM(FCXphOVDue61And90)              AS FCXphOVDue61And90_Footer,
                            SUM(FCXphOVDue90)                   AS FCXphOVDue90_Footer,
                            SUM(FCXshLeft)                      AS FCXshLeft_Footer
                        FROM TRPTPurCreditorAgeTmp WITH(NOLOCK)
                        WHERE 1=1
                        AND FTUsrSession = '$tUsrSession'
                        GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        } else {
            $tJoinFoooter = " SELECT
                            '$tUsrSession'  AS FTUsrSession_Footer
                        ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL   = "SELECT
                        L.*,
                        T.*,
                        C.*
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY A.FNRowPartID ASC) AS rtRowID,
                            ROW_NUMBER() OVER(ORDER BY FTSplCode ASC) AS RowID ,
                            ROW_NUMBER() OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC) AS rtPartitionSPL ,
                            COUNT(FTSplCode) OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC) AS rtPartitionCountSPL ,
                            A.*
                            FROM TRPTPurCreditorAgeTmp A WITH(NOLOCK)
                            WHERE A.FTUsrSession    = '$tUsrSession'
                    ) AS L 
                    LEFT JOIN (
                        SELECT
                            FTSplCode                           AS FTSplCode_SPL_Footer,
                            FTUsrSession                        AS FTUsrSession_SPL_Footer,
                            SUM(FCXphBFDue60)                   AS FCXphBFDue60_SPL_Footer,
                            SUM(FCXphBFDue31And60)              AS FCXphBFDue31And60_SPL_Footer,
                            SUM(FCXphBFDue0And30)               AS FCXphBFDue0And30_SPL_Footer,
                            SUM(FCXphOVDue1)                    AS FCXphOVDue1_SPL_Footer,
                            SUM(FCXphOVDue2And7)                AS FCXphOVDue2And7_SPL_Footer,
                            SUM(FCXphOVDue8And15)               AS FCXphOVDue8And15_SPL_Footer,
                            SUM(FCXphOVDue16And30)              AS FCXphOVDue16And30_SPL_Footer,
                            SUM(FCXphOVDue31And60)              AS FCXphOVDue31And60_SPL_Footer,
                            SUM(FCXphOVDue61And90)              AS FCXphOVDue61And90_SPL_Footer,
                            SUM(FCXphOVDue90)                   AS FCXphOVDue90_SPL_Footer,
                            SUM(FCXshLeft)                      AS FCXshLeft_SPL_Footer
                        FROM TRPTPurCreditorAgeTmp WITH(NOLOCK)
                        WHERE 1=1
                        AND FTUsrSession    = '$tUsrSession'
                        GROUP BY FTUsrSession , FTSplCode
                    ) C ON L.FTUsrSession = C.FTUsrSession_SPL_Footer AND L.FTSplCode = C.FTSplCode_SPL_Footer
                    LEFT JOIN (
                    " . $tJoinFoooter . "  ";
        $tSQL .=    " WHERE L.rtRowID > $nRowIDStart AND L.rtRowID <= $nRowIDEnd";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTSplCode";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->result_array();
        }else{
            $aData = NULL;
        }

        $aErrorList = [
            "nErrInvalidPage"   => ""
        ];

        $aResualt= [
            "aPagination"       => $aPagination,
            "aRptData"          => $aData,
            "aError"            => $aErrorList
        ];
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    //Calurate Pagination
    private function FMaMRPTPagination($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            SELECT
                TSPT.FTRptCode
            FROM TRPTPurCreditorAgeTmp TSPT WITH(NOLOCK)
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

}
