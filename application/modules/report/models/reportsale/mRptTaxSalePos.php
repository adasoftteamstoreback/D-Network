<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptTaxSalePos extends CI_Model {
   
    //Calurate Pagination
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
            FROM TRPTPSTaxHDTmp TMP WITH(NOLOCK)
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
    
    //Set PriorityGroup
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
            UPDATE TRPTPSTaxHDTmp SET 
                TRPTPSTaxHDTmp.FNRowPartID = B.PartID
                FROM(
                    SELECT   
                    ROW_NUMBER() OVER(PARTITION BY TMP.FTBchCode,TMP.FTPosCode ORDER BY TMP.FTBchCode ASC,TMP.FTPosCode ASC, TMP.FDXshDocDate ASC ,TMP.FTXshDocNo ASC ) AS PartID ,
                        TMP.FTRptRowSeq
                    FROM TRPTPSTaxHDTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTComName = '$tComName'
                    AND TMP.FTRptCode = '$tRptCode'
                    AND TMP.FTUsrSession = '$tUsrSession'
                    $tAppTypePos
                ) AS B
            WHERE TRPTPSTaxHDTmp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTPSTaxHDTmp.FTComName = '$tComName' 
            AND TRPTPSTaxHDTmp.FTRptCode = '$tRptCode'
            AND TRPTPSTaxHDTmp.FTUsrSession = '$tUsrSession'
            $tAppTypePos
        ";
        $this->db->query($tSQL);
    }

    //Get Data Advance Table
    public function FSaMGetDataReport($paDataWhere){
        $tAppType = $paDataWhere['aRptFilter']['tPosType'];
        if ($tAppType != '') {
            $tAppTypePos = "AND FNAppType = '$tAppType' ";
        }else{
            $tAppTypePos = '';
        }
        $nPage = $paDataWhere['nPage'];
        
        // Call Data Pagination 
        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $aPagination    = '';
            $nTotalPage     = 1;
        }

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tBchNameHQ     = language('report/report/report', 'tRptTaxSaleHeadQuarTers');
        
        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ??????????????????????????????????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????? Sum footer ???????????????????????? 
        if($nPage == $nTotalPage){
            $tRptJoinFooter = " 
                SELECT
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM( 
                        ROUND(ISNULL(FCXshAmt, 0),2)
                    ) AS FCXshAmt_Footer,
                    SUM( 
                        ROUND(ISNULL(FCXshVat, 0),2)
                    ) AS FCXshVat_Footer,
                    SUM( 
                        ROUND(ISNULL(FCXshAmtNV, 0),2)
                    ) AS FCXshAmtNV_Footer,
                    SUM( 
                        ROUND(ISNULL(FCXshGrandTotal, 0),2)
                    ) AS FCXshGrandTotal_Footer
                FROM TRPTPSTaxHDTmp WITH(NOLOCK)
                WHERE FTComName = '$tComName'
                AND FTRptCode = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
        }else{
            $tRptJoinFooter = " 
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    '0' AS FCXshAmt_Footer,
                    '0' AS FCXshVat_Footer,
                    '0' AS FCXshAmtNV_Footer,
                    '0' AS FCXshGrandTotal_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer ";
        }

        $tSQL   =   "   
            SELECT
                L.*,
                SUMBCH.*,
                SUMPOS.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode ASC,  FTPosCode ASC, FNRowPartID ASC, FDXshDocDate ASC) AS RowID,
                    COUNT (FTBchCode) OVER (PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS PARTTITIONBYBCH,
                    ROW_NUMBER () OVER (PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS PARTTITIONBYBCH_COUNT,
                    A.*,
                    S.FNRptGroupMember,
                    S.FCXshAmt_SumSup,
                    S.FCXshVat_SumSup,
                    S.FCXshAmtNV_SumSup,
                    S.FCXshGrandTotal_SumSup,
                    S.tBusiness
                FROM TRPTPSTaxHDTmp A WITH(NOLOCK)
                LEFT JOIN (
                    SELECT
                        FTXshDocNo AS FTXshDocNo_SUM,
                        COUNT(FDXshDocDate) AS FNRptGroupMember,
                        SUM( 
                            ROUND(ISNULL(FCXshAmt, 0),2)
                        ) AS FCXshAmt_SumSup,
                        SUM( 
                            ROUND(ISNULL(FCXshVat, 0),2)
                        ) AS FCXshVat_SumSup,
                        SUM( 
                            ROUND(ISNULL(FCXshAmtNV, 0),2)
                        ) AS FCXshAmtNV_SumSup,
                        SUM( 
                            ROUND(ISNULL(FCXshGrandTotal, 0),2)
                        ) AS FCXshGrandTotal_SumSup,
                        CASE
                        WHEN FTEstablishment = '1' THEN '$tBchNameHQ'
                            ELSE ''
                        END AS tBusiness
                    FROM TRPTPSTaxHDTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName = '$tComName'
                    AND FTRptCode = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    $tAppTypePos
                    GROUP BY FTXshDocNo,FTEstablishment  
                ) AS S ON A.FTXshDocNo = S.FTXshDocNo_SUM
                WHERE A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tUsrSession'
                $tAppTypePos
            ) AS L
            LEFT JOIN (
                SELECT
                    FTUsrSession                                AS FTUsrSession_SUMBCH,
                    FTBchCode                                   AS FTBchCode_SUMBCH,
                    SUM(ROUND(ISNULL(FCXshAmt, 0),2))           AS FCXshAmt_SUMBCH,
                    SUM(ROUND(ISNULL(FCXshVat, 0),2))           AS FCXshVat_SUMBCH,
                    SUM(ROUND(ISNULL(FCXshAmtNV, 0),2))         AS FCXshAmtNV_SUMBCH,
                    SUM(ROUND(ISNULL(FCXshGrandTotal, 0),2))    AS FCXshGrandTotal_SUMBCH
                FROM TRPTPSTaxHDTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName   = '$tComName'
                AND FTRptCode   = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession,FTBchCode
            ) SUMBCH ON L.FTUsrSession = SUMBCH.FTUsrSession_SUMBCH AND L.FTBchCode = SUMBCH.FTBchCode_SUMBCH
            LEFT JOIN (
                SELECT
                    FTUsrSession                                AS FTUsrSession_SUMPOS,
                    FTBchCode                                   AS FTBchCode_SUMPOS,
                    FTPosCode                                   AS FTPosCode_SUMPOS,
                    SUM(ROUND(ISNULL(FCXshAmt, 0),2))           AS FCXshAmt_SUMPOS,
                    SUM(ROUND(ISNULL(FCXshVat, 0),2))           AS FCXshVat_SUMPOS,
                    SUM(ROUND(ISNULL(FCXshAmtNV, 0),2))         AS FCXshAmtNV_SUMPOS,
                    SUM(ROUND(ISNULL(FCXshGrandTotal, 0),2))    AS FCXshGrandTotal_SUMPOS
                FROM TRPTPSTaxHDTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName   = '$tComName'
                AND FTRptCode   = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                $tAppTypePos
                GROUP BY FTUsrSession,FTPosCode,FTBchCode
            ) SUMPOS ON L.FTUsrSession = SUMPOS.FTUsrSession_SUMPOS AND L.FTPosCode = SUMPOS.FTPosCode_SUMPOS AND L.FTBchCode = SUMPOS.FTBchCode_SUMPOS
            LEFT JOIN (
            ".$tRptJoinFooter." ";

        // WHERE ???????????????????????? Page
        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .=  " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd  ";
        }

        //???????????? Order by ???????????????????????????????????????
        $tSQL .=  " ORDER BY L.FTBchCode ASC,L.FTPosCode ASC, L.FDXshDocDate ASC, L.FNRowPartID ASC , LEFT(L.FTXshDocNo,1) DESC ";
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
    
    //Call Store
    public function FSnMExecStoreReport($paDataFilter){
        $nLangID      = $paDataFilter['nLangID'];
        $tComName     = $paDataFilter['tCompName'];
        $tRptCode     = $paDataFilter['tRptCode'];
        $tUserSession = $paDataFilter['tUserSession'];

         // ????????????
         $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']); 
         // ?????????????????????
         $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
         // ?????????????????????????????????
         $tMerCodeSelect = ($paDataFilter['bMerStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tMerCodeSelect']);
         // ?????????????????????????????????????????????????????????
         $tPosCodeSelect = ($paDataFilter['bPosStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tPosCodeSelect']);
  
        $tCallStore = "{CALL SP_RPTxPSSVat1001006(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
            'pnLngID'       => $nLangID , 
            'pnComName'     => $tComName,
            'ptRptCode'     => $tRptCode,
            'ptUsrSession'  => $tUserSession,
            'pnFilterType'  => $paDataFilter['tTypeSelect'],
            'ptBchL'        => $tBchCodeSelect,
            'ptBchF'        => $paDataFilter['tBchCodeFrom'],
            'ptBchT'        => $paDataFilter['tBchCodeTo'],
            'ptMerL'        => $tMerCodeSelect,
            'ptMerF'        => $paDataFilter['tMerCodeFrom'],
            'ptMerT'        => $paDataFilter['tMerCodeTo'],
            'ptShpL'        => $tShpCodeSelect,
            'ptShpF'        => $paDataFilter['tShpCodeFrom'],
            'ptShpT'        => $paDataFilter['tShpCodeTo'],
            'ptPosL'        => $tPosCodeSelect,
            'ptPosF'        => $paDataFilter['tPosCodeFrom'],
            'ptPosT'        => $paDataFilter['tPosCodeTo'],
            'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
            'pthDocDateT'   => $paDataFilter['tDocDateTo'],
            'FTResult'      => 0
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
    
    //Count Row in Temp
    public function FSnMCountRowInTemp($paParams){
        $tComName = $paParams['tCompName'];
        $tRptCode = $paParams['tRptCode'];
        $tUsrSession = $paParams['tSessionID'];
        $tSQL = "   
            SELECT
                TMP.FTRptCode
            FROM TRPTPSTaxHDTmp TMP WITH(NOLOCK)
            WHERE TMP.FTComName = '$tComName'
            AND TMP.FTRptCode = '$tRptCode'
            AND TMP.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        return $nRptAllRecord = $oQuery->num_rows();
    }
        
}



