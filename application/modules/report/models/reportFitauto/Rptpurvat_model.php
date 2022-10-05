<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Rptpurvat_model extends CI_Model {

    /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    private function FMaMRPTPagination($paDataWhere){
        $tAppType = $paDataWhere['aRptFilter']['tPosType'];
        if ($tAppType != '') {
            $tAppTypePos = "AND FNAppType = '$tAppType' ";
        }else{
            $tAppTypePos = '';
        }
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = "
            SELECT
                COUNT(TMP.FTRptCode) AS rnCountPage
            FROM TRPTPurVatTmp TMP WITH(NOLOCK)
            WHERE 1=1
            AND TMP.FTComName = '$tComName'
            AND TMP.FTRptCode = '$tRptCode'
            AND TMP.FTUsrSession = '$tUsrSession'
            $tAppTypePos
        ";

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
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
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tAppType = $paDataWhere['aRptFilter']['tPosType'];
        if ($tAppType != '') {
            $tAppTypePos = "AND FNAppType = '$tAppType' ";
        }else{
            $tAppTypePos = '';
        }
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            UPDATE TRPTPurVatTmp SET
            TRPTPurVatTmp.FNRowPartID = B.PartID
                FROM(
                    SELECT
                    ROW_NUMBER() OVER(PARTITION BY TMP.FTBchCode ORDER BY TMP.FTBchCode ASC, TMP.FDXphRefExtDate ASC ,TMP.FTXphDocNo ASC ) AS PartID ,
                        TMP.FTRptRowSeq
                    FROM TRPTPurVatTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTComName = '$tComName'
                    AND TMP.FTRptCode = '$tRptCode'
                    AND TMP.FTUsrSession = '$tUsrSession'
                    $tAppTypePos
                ) AS B
            WHERE TRPTPurVatTmp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTPurVatTmp.FTComName = '$tComName'
            AND TRPTPurVatTmp.FTRptCode = '$tRptCode'
            AND TRPTPurVatTmp.FTUsrSession = '$tUsrSession'
            $tAppTypePos
        ";
        $this->db->query($tSQL);
    }

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : status
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere){
        $tAppType = $paDataWhere['aRptFilter']['tPosType'];
        if ($tAppType != '') {
            $tAppTypePos = "AND FNAppType = '$tAppType' ";
        }else{
            $tAppTypePos = '';
        }
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];

        // Call Data Pagination
        $aPagination = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];

        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tBchNameHQ = language('report/report/report', 'tRptTaxSaleHeadQuarTers');

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if($nPage == $nTotalPage){
            $tRptJoinFooter = "
                SELECT
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM(
                        ISNULL(FCXphAmt, 0)
                    ) AS FCXphAmt_Footer,
                    SUM(
                        ISNULL(FCXphVat, 0)
                    ) AS FCXphVat_Footer,
                    SUM(
                        ISNULL(FCXphAmtNV, 0)
                    ) AS FCXphAmtNV_Footer,
                    SUM(
                        ISNULL(FCXphGrandTotal, 0)
                    ) AS FCXphGrandTotal_Footer
                FROM TRPTPurVatTmp WITH(NOLOCK)
                WHERE FTComName = '$tComName'
                AND FTRptCode = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }else{
            $tRptJoinFooter = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    '0' AS FCXphAmt_Footer,
                    '0' AS FCXphVat_Footer,
                    '0' AS FCXphAmtNV_Footer,
                    '0' AS FCXphGrandTotal_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   =   "
            SELECT
                L.*,
                SUMBCH.*,
                SUMPOS.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode ASC, FNRowPartID ASC, FDXphRefExtDate ASC) AS RowID,
                    A.*,
                    S.FNRptGroupMember,
                    S.FCXphAmt_SumSup,
                    S.FCXphVat_SumSup,
                    S.FCXphAmtNV_SumSup,
                    S.FCXphGrandTotal_SumSup,
                    S.tBusiness
                FROM TRPTPurVatTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                    FTXphDocNo AS FTXphDocNo_SUM,
                        COUNT(FDXphRefExtDate) AS FNRptGroupMember,
                        SUM(
                            ISNULL(FCXphAmt, 0)
                        ) AS FCXphAmt_SumSup,
                        SUM(
                            ISNULL(FCXphVat, 0)
                        ) AS FCXphVat_SumSup,
                        SUM(
                            ISNULL(FCXphAmtNV, 0)
                        ) AS FCXphAmtNV_SumSup,
                        SUM(
                            ISNULL(FCXphGrandTotal, 0)
                        ) AS FCXphGrandTotal_SumSup,
                        CASE

                        WHEN FTEstablishment = '1' THEN '$tBchNameHQ'
                            ELSE ''
                        END AS tBusiness

                    FROM TRPTPurVatTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName = '$tComName'
                    AND FTRptCode = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    $tAppTypePos
                    GROUP BY FTXphDocNo,FTEstablishment
                ) AS S ON A.FTXphDocNo = S.FTXphDocNo_SUM
                WHERE A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tUsrSession'
                $tAppTypePos
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
                SELECT
              FTUsrSession                    AS FTUsrSession_SUMBCH,
              FTBchCode                       AS FTBchCode_SUMBCH,
              SUM(ISNULL(FCXphAmt, 0))        AS FCXphAmt_SUMBCH,
              SUM(ISNULL(FCXphVat, 0))        AS FCXphVat_SUMBCH,
              SUM(ISNULL(FCXphAmtNV, 0))      AS FCXphAmtNV_SUMBCH,
              SUM(ISNULL(FCXphGrandTotal, 0)) AS FCXphGrandTotal_SUMBCH
                FROM TRPTPurVatTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName   = '$tComName'
                AND FTRptCode   = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession,FTBchCode
            ) SUMBCH ON L.FTUsrSession = SUMBCH.FTUsrSession_SUMBCH AND L.FTBchCode = SUMBCH.FTBchCode_SUMBCH
            LEFT JOIN (
                SELECT
              FTUsrSession                    AS FTUsrSession_SUMPOS,
              FTBchCode                       AS FTBchCode_SUMPOS,
              SUM(ISNULL(FCXphAmt, 0))        AS FCXphAmt_SUMPOS,
              SUM(ISNULL(FCXphVat, 0))        AS FCXphVat_SUMPOS,
              SUM(ISNULL(FCXphAmtNV, 0))      AS FCXphAmtNV_SUMPOS,
              SUM(ISNULL(FCXphGrandTotal, 0)) AS FCXphGrandTotal_SUMPOS
                FROM TRPTPurVatTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName   = '$tComName'
                AND FTRptCode   = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession,FTBchCode
            ) SUMPOS ON L.FTUsrSession = SUMPOS.FTUsrSession_SUMPOS  AND L.FTBchCode = SUMPOS.FTBchCode_SUMPOS

            LEFT JOIN (
            ".$tRptJoinFooter."
        ";

        // WHERE เงื่อนไข Page
        $tSQL .=  " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd  ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .=  " ORDER BY L.FTBchCode ASC, L.FDXphRefExtDate ASC, L.FNRowPartID ASC , LEFT(L.FTXphDocNo,1) DESC ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->result_array();
        }else{
            $aData = NULL;
        }

        $aErrorList = [
            "nErrInvalidPage" => ""
        ];

        $aResualt= [
            "aPagination" => $aPagination,
            "aRptData" => $aData,
            "aError" => $aErrorList
        ];
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    /**
     * Functionality: Call Store
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
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
        $tCallStore = "{CALL SP_RPTxPurVat(?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
          'pnLngID' => $nLangID,
          'pnComName' => $tComName,
          'ptRptCode' => $tRptCode,
          'ptUsrSession' => $tUserSession,
          'pnFilterType' => $paDataFilter['tTypeSelect'],
          'ptAgnL'=> $paDataFilter['tAgnCode'],
          'ptBchL'=> $tBchCodeSelect,
          'ptShpL'=> $tShpCodeSelect,
          'ptSplF'    => $paDataFilter['tPdtSupplierCodeFrom'],
          'ptSplT'   => $paDataFilter['tPdtSupplierCodeTo'],
          'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
          'pthDocDateT'   => $paDataFilter['tDocDateTo'],
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
     * Creator: 23/07/2019 Piya
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
            FROM TRPTPurVatTmp TMP WITH(NOLOCK)
            WHERE TMP.FTComName = '$tComName'
            AND TMP.FTRptCode = '$tRptCode'
            AND TMP.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        return $nRptAllRecord = $oQuery->num_rows();
    }

}
