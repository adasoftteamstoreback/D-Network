<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );


class mRptSaleByBillAndBch extends CI_Model {

    public function FSnMExecStoreCReport($paDataFilter){
        $nLangID        = $paDataFilter['nLangID'];
        $tComName       = $paDataFilter['tCompName'];
        $tRptCode       = $paDataFilter['tRptCode'];
        $tUserSession   = $paDataFilter['tUserSessionID'];
        $tCstCodeFrom   = empty($paDataFilter['tCstCodeFrom']) ? '' : $paDataFilter['tCstCodeFrom']; 
        $tCstCodeTo     = empty($paDataFilter['tCstCodeTo']) ? '' : $paDataFilter['tCstCodeTo'];
        $tDateFrom      = empty($paDataFilter['tDocDateFrom']) ? '' : $paDataFilter['tDocDateFrom']; 
        $tDateTo        = empty($paDataFilter['tDocDateTo']) ? '' : $paDataFilter['tDocDateTo']; 

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']); 
        // ประเภทเครื่องจุดขาย
        $tPosCodeSelect = ($paDataFilter['bPosStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tPosCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxDailySaleByBillAndBch(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID'           => $nLangID,
            'pnComName'         => $tComName,
            'ptRptCode'         => $tRptCode,
            'ptUsrSession'      => $tUserSession,
            'pnFilterType'      => $paDataFilter['tTypeSelect'],
            'ptBchL'            => $tBchCodeSelect,
            'ptBchF'            => $paDataFilter['tBchCodeFrom'],
            'ptBchT'            => $paDataFilter['tBchCodeTo'],
            'ptMerL'            => '',
            'ptMerF'            => '',
            'ptMerT'            => '',
            'ptShpL'            => '',
            'ptShpF'            => '',
            'ptShpT'            => '',
            'ptPosL'            => $tPosCodeSelect,
            'ptPosF'            => $paDataFilter['tPosCodeFrom'],
            'ptPosT'            => $paDataFilter['tPosCodeTo'],
            'ptCstF'            => $tCstCodeFrom,
            'ptCstT'            => $tCstCodeTo,
            'ptDocDateF'        => $tDateFrom,
            'ptDocDateT'        => $tDateTo,
            'FNResult'          => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if($oQuery !== FALSE){
            unset($oQuery);
            return 1 ;                
        }else{
            unset($oQuery);
            return 0;
        }   
    }

    //Get Data Report
    public function FSaMGetDataReport($paDataWhere = [], $paDataFilter = []){

        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
        }else{
            $aPagination    = '';
        }

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUserSession   = $paDataWhere['tUserSessionID'];
        
        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        $tSQL = "";
        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "   SELECT L.* FROM ( ";
        }

        $tSQL .= "      SELECT
                            ROW_NUMBER() OVER(ORDER BY FTBchCode ASC , FNXshDocType ASC, FDXshDocDate ASC, FTXshDocNo ASC, FNType ASC) AS RowID ,
                            COUNT (FTBchCode) OVER (PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS PARTTITIONBYBCH,
                            ROW_NUMBER () OVER (PARTITION BY FTBchCode ORDER BY FTBchCode ASC) AS PARTTITIONBYBCH_COUNT,
                            A.*,
                            S.FNRptGroupMember,
                            S.FCXsdAmt_SubTotal,
                            S.FCXsdDis_SubTotal,
                            S.FCXsdNet_SubTotal,
                            S.FNCount_DT,
                            S.FNCount_RC
                        FROM TRPTSalPdtBillTmp A WITH(NOLOCK)
                        LEFT JOIN (
                            SELECT
                                FTXshDocNo AS FTXshDocNo_SUM,
                                COUNT(FTXshDocNo) AS FNRptGroupMember,
                                COUNT(
                                    CASE
                                        WHEN FNType = 2 THEN FTXshDocNo
                                    END     
                                ) AS FNCount_DT,
                                COUNT(
                                    CASE
                                        WHEN FNType = 3 THEN FTXshDocNo
                                    END     
                                ) AS FNCount_RC,
                                SUM(
                                    CASE
                                        WHEN FNType = 2 THEN FCXsdAmt
                                    END
                                ) AS FCXsdAmt_SubTotal,
                                SUM(
                                    CASE
                                        WHEN FNType = 2 THEN FCXsdDis
                                    END
                                ) AS FCXsdDis_SubTotal,
                                SUM(
                                    CASE
                                        WHEN FNType = 2 THEN FCXsdNet
                                    END
                                ) AS FCXsdNet_SubTotal
                            FROM TRPTSalPdtBillTmp WITH(NOLOCK)
                            WHERE FTComName = '$tComName'
                            AND FTRptCode = '$tRptCode'
                            AND FTUsrSession = '$tUserSession'
                            GROUP BY FTXshDocNo
                        ) AS S ON A.FTXshDocNo = S.FTXshDocNo_SUM
                        WHERE A.FTComName = '$tComName'
                        AND A.FTRptCode = '$tRptCode'
                        AND A.FTUsrSession = '$tUserSession' ";

        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  ) AS L ";
            // WHERE เงื่อนไข Page
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
            $tSQL .= " ORDER BY L.FTBchCode ASC , L.FNXshDocType ASC, L.FDXshDocDate ASC, L.FTXshDocNo ASC, L.FNType ASC";
        }else{
            $tSQL .= " ORDER BY A.FTBchCode ASC , A.FNXshDocType ASC, A.FDXshDocDate ASC, A.FTXshDocNo ASC, A.FNType ASC";
        }

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
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        ];
        unset($oQuery); 
        unset($aData);
        return $aResualt;
    }

    //Functionality: Set Priority Group
    public function FMxMRPTSetPriorityGroup($paDataWhere = []) {
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUserSessionID = $paDataWhere['tUserSessionID'];

        $tSQL = "UPDATE DATAUPD SET 
                DATAUPD.FNRowPartID = B.PartID
            FROM TRPTSalPdtBillTmp AS DATAUPD WITH(NOLOCK)
            INNER JOIN(
                SELECT
                    ROW_NUMBER() OVER(PARTITION BY FTXshDocNo ORDER BY FNXshDocType ASC, FDXshDocDate ASC, FTXshDocNo ASC, FNType ASC) AS PartID,
                    FTRptRowSeq
                FROM TRPTSalPdtBillTmp WITH(NOLOCK)
                WHERE FTComName = '$tCompName'
                AND FTRptCode = '$tRptCode'
                AND FTUsrSession = '$tUserSessionID'
            ) AS B
            ON DATAUPD.FTRptRowSeq = B.FTRptRowSeq
            AND DATAUPD.FTComName = '$tCompName'
            AND DATAUPD.FTRptCode = '$tRptCode'
            AND DATAUPD.FTUsrSession = '$tUserSessionID' ";

        $this->db->query($tSQL);
    }

    //Count Data Report All
    public function FSnMCountDataReportAll($paDataWhere = []){
        $tUserSessionID = $paDataWhere['tUserSessionID'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];

        $tSQL = "SELECT 
                    TMP.FTRptCode
                FROM TRPTSalPdtBillTmp TMP WITH(NOLOCK)
                WHERE FTUsrSession = '$tUserSessionID'
                AND FTComName = '$tCompName'
                AND FTRptCode = '$tRptCode' ";

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->num_rows();
        unset($oQuery);
        return $nRptAllRecord;
    }

    //Get Data Page 
    public function FMaMRPTPagination($paDataWhere = []) {

        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUserSessionID = $paDataWhere['tUserSessionID'];

        $tSQL = " SELECT
                TMP.FTRptCode
            FROM TRPTSalPdtBillTmp AS TMP WITH(NOLOCK)
            WHERE TMP.FTComName = '$tCompName'
            AND TMP.FTRptCode = '$tRptCode'
            AND TMP.FTUsrSession = '$tUserSessionID' ";

        $oQuery             = $this->db->query($tSQL);
        $nRptAllRecord      = $oQuery->num_rows();
        $nPage              = $paDataWhere['nPage'];
        $nPerPage           = $paDataWhere['nPerPage'];
        $nPrevPage          = $nPage - 1;
        $nNextPage          = $nPage + 1;
        $nRowIDStart        = (($nPerPage * $nPage) - $nPerPage);
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
            "nNextPage"     => $nNextPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    //ยอดรวมตามเอกสาร
    public function FMaMRPTGetHDByDocNo($paParams = []) {

        $tDocNo         = $paParams['tDocNo'];
        $tUserSessionID = $this->session->userdata('tSesSessionID');

        $tSQL = " SELECT
                FCXshVatable,
                FCXshVat,
                FCXshDis,
                FCXshRnd,
                FCXshGrand
            FROM TRPTSalPdtBillTmp TMP WITH(NOLOCK)    
            WHERE TMP.FTXshDocNo = '$tDocNo'
            AND TMP.FTUsrSession = '$tUserSessionID'
            AND TMP.FTRptCode = '001001039' 
            AND TMP.FNType = 1 ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array()[0];
        }else{
            return [
                'FCXshVatable' => 0,
                'FCXshVat' => 0,
                'FCXshDis' => 0,
                'FCXshRnd' => 0,
                'FCXshGrand' => 0
            ];
        }
    }

    //ยอดรวมทั้งหมด
    public function FMaMRPTSumFooterAll($paParams = []) {
        $tUserSessionID = $this->session->userdata('tSesSessionID');

        $tSQL = "
            SELECT
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdQty, 0)
                    END
                ) AS FCXsdQty_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdSetPrice, 0)
                    END
                ) AS FCXsdSetPrice_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdAmt, 0)
                    END
                ) AS FCXsdAmt_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdDis, 0)
                    END
                ) AS FCXsdDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdNet, 0)
                    END
                ) AS FCXsdNet_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshVatable, 0)
                    END
                ) AS FCXshVatable_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshVat, 0)
                    END
                ) AS FCXshVat_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshDis, 0)
                    END
                ) AS FCXshDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshRnd, 0)
                    END
                ) AS FCXshRnd_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshGrand, 0)
                    END
                ) AS FCXshGrand_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshTotalAfDis, 0)
                    END
                ) AS FCXshTotalAfDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 3 THEN ISNULL(TMP.FCXrcNet, 0)
                    END
                ) AS FCXrcNet_SumFooter
            FROM TRPTSalPdtBillTmp TMP WITH(NOLOCK)    
            WHERE TMP.FTUsrSession = '$tUserSessionID' AND TMP.FTRptCode = '001001039' ";
                   
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array()[0];
        }else{
            return [
                'FCXsdAmt_SumFooter' => 0,
                'FCXsdDis_SumFooter' => 0,
                'FCXsdNet_SumFooter' => 0,
                'FCXshVatable_SumFooter' => 0,
                'FCXshVat_SumFooter' => 0,
                'FCXshDis_SumFooter' => 0,
                'FCXshRnd_SumFooter' => 0,
                'FCXshGrand_SumFooter' => 0
            ];
        }
    }

    //ยอดรวมแต่ละสาขา
    public function FMaMRPTSumFooterAllByBCH($paParams = []) {
        $tBCHCode       = $paParams['tBCHCode'];
        $tUserSessionID = $this->session->userdata('tSesSessionID');

        $tSQL = "
            SELECT
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdQty, 0)
                    END
                ) AS FCXsdQty_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdSetPrice, 0)
                    END
                ) AS FCXsdSetPrice_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdAmt, 0)
                    END
                ) AS FCXsdAmt_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdDis, 0)
                    END
                ) AS FCXsdDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 2 THEN ISNULL(TMP.FCXsdNet, 0)
                    END
                ) AS FCXsdNet_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshVatable, 0)
                    END
                ) AS FCXshVatable_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshVat, 0)
                    END
                ) AS FCXshVat_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshDis, 0)
                    END
                ) AS FCXshDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshRnd, 0)
                    END
                ) AS FCXshRnd_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshGrand, 0)
                    END
                ) AS FCXshGrand_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 1 THEN ISNULL(TMP.FCXshTotalAfDis, 0)
                    END
                ) AS FCXshTotalAfDis_SumFooter,
                SUM(
                    CASE
                        WHEN TMP.FNType = 3 THEN ISNULL(TMP.FCXrcNet, 0)
                    END
                ) AS FCXrcNet_SumFooter
            FROM TRPTSalPdtBillTmp TMP WITH(NOLOCK)    
            WHERE TMP.FTUsrSession = '$tUserSessionID'
            AND TMP.FTBchCode = '$tBCHCode' AND TMP.FTRptCode = '001001039' ";
                   
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array()[0];
        }else{
            return [
                'FCXsdAmt_SumFooter'        => 0,
                'FCXsdDis_SumFooter'        => 0,
                'FCXsdNet_SumFooter'        => 0,
                'FCXshVatable_SumFooter'    => 0,
                'FCXshVat_SumFooter'        => 0,
                'FCXshDis_SumFooter'        => 0,
                'FCXshRnd_SumFooter'        => 0,
                'FCXshGrand_SumFooter'      => 0
            ];
        }
    }
}


