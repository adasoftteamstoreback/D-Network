<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Deposit_Model extends CI_Model {

    //ใบมัดจำ
    public function FSaMDPSGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);

        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        // $tSearchDocDateTo    = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaApprove      = $aAdvanceSearch['tSearchStaApprove'];

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        DPHD.FTBchCode,
                                        BCHL.FTBchName,
                                        DPHD.FTXshDocNo,
                                        DPHD.FTXshRefInt,
                                        CONVERT(CHAR(10),DPHD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), DPHD.FDXshDocDate,108) AS FTXshDocTime,
                                        DPHD.FTXshStaDoc,
                                        DPHD.FTXshStaApv,
                                        DPHD.FNXshStaRef,
                                        DPHD.FTCreateBy,
                                        DPHD.FDCreateOn,
                                        DPHD.FTXshStaPaid,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        DPHD.FTXshApvCode,
                                        USRLAPV.FTUsrName   AS FTXshApvName
                                    FROM TARTRcvDepositHD   DPHD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON DPHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON DPHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON DPHD.FTXshApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                WHERE 1=1";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND DPHD.FTBchCode IN ($tBchCode)
            ";
        }
        
        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND DPHD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((DPHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),DPHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
         // ค้นหาจากสาขา - ถึงสาขา
         if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((DPHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (DPHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom)){
            $tSQL .= " AND ((DPHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DPHD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DPHD.FTXshStaApv,'') = '' AND DPHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DPHD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DPHD.FTXshStaApv = '$tSearchStaApprove' OR DPHD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND DPHD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMDPSCountPageDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Functionality: Data Get Data Page All
    // Parameters: function parameters
    // Creator:  15/07/2021 Off
    // Last Modified: -
    // Return: Data Array
    // Return Type: Array
    public function FSnMDPSCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        // $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaApprove  = $aAdvanceSearch['tSearchStaApprove'];
        // $tSearchStaPrcStk   = $aAdvanceSearch['tSearchStaPrcStk'];

        $tSQL   =   "   SELECT COUNT (SOHD.FTXshDocNo) AS counts
                        FROM TARTRcvDepositHD SOHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON SOHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1
                    ";

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND SOHD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND SOHD.FTShpCode = '$tUserLoginShpCode' ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SOHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SOHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom)){
            $tSQL .= " AND SOHD.FTBchCode = '$tSearchBchCodeFrom'";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom)){
            $tSQL .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaApprove' OR SOHD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        // $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        // if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
        //     if ($tSearchStaDocAct == 1) {
        //         $tSQL .= " AND SOHD.FNXshStaDocAct = 1";
        //     } else {
        //         $tSQL .= " AND SOHD.FNXshStaDocAct = 0";
        //     }
        // }

        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Functionality : Delete Purchase Invoice Document
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : Array Status Delete
    // Return Type : array
    public function FSnMDPSDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTRcvDepositHD');

        // Document HD Cst
       $this->db->where_in('FTXshDocNo',$tDataDocNo);
       $this->db->delete('TARTRcvDepositHDCst');
        
        // Document DT
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTRcvDepositDT');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aStaDelDoc     = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        }else{
            $this->db->trans_commit();
            $aStaDelDoc     = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        return $aStaDelDoc;
    }

    // Functionality : Delete Purchase Invoice Document
    // Parameters : function parameters
    // Creator : 06/07/2021 Off
    // Last Modified : -
    // Return : Array Status Delete
    // Return Type : array
    public function FSxMDPSClearDataInDocTemp($paWhereClearTemp){
        $tSODocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tSODocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tSOSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tSODocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tSODocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tSOSessionID'
        ";
        $this->db->query($tClearDocTemp);  
    }

    // Functionality: Get ShopCode From User Login
    // Parameters: function parameters
    // Creator: 24/06/2018 wasin (Yoshi AKA: Mr.JW)
    // Last Modified: -
    // Return: Array Data Shop For User Login
    // ReturnType: array
    public function FSaMDPSGetShpCodeForUsrLogin($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tUsrLogin  = $paDataShp['tUsrLogin'];
        $tSQL       = " SELECT
                            UGP.FTBchCode,
                            BCHL.FTBchName,
                            MER.FTMerCode,
                            MERL.FTMerName,
                            UGP.FTShpCode,
                            SHPL.FTShpName,
                            SHP.FTShpType,
                            SHP.FTWahCode   AS FTWahCode,
                            WAHL.FTWahName  AS FTWahName
                        FROM TCNTUsrGroup           UGP     WITH (NOLOCK)
                        LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode 
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
                        LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
                        LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
                        WHERE UGP.FTUsrCode = '$tUsrLogin' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Data In Doc DT Temp
    // Parameters: function parameters
    // Creator: 15/07/2021 Off
    // Last Modified: -
    // Return: Array Data Doc DT Temp
    // ReturnType: array
    public function FSaMDPSGetDocDTTempListPage($paDataWhere){
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');

        $aRowLen    = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);

        $tSQL       = " SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM (
                                SELECT
                                    DOCTMP.FTBchCode,
                                    DOCTMP.FTXthDocNo,
                                    DOCTMP.FNXtdSeqNo,
                                    DOCTMP.FTXthDocKey,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTXtdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FTXtdBarCode,
                                    DOCTMP.FTPunCode,
                                    DOCTMP.FCXtdFactor,
                                    DOCTMP.FCXtdQty,
                                    DOCTMP.FCXtdSetPrice,
                                    DOCTMP.FCXtdSalePrice,
                                    DOCTMP.FCXtdAmtB4DisChg,
                                    DOCTMP.FTXtdDisChgTxt,
                                    DOCTMP.FCXtdNet,
                                    DOCTMP.FCXtdNetAfHD,
                                    DOCTMP.FTXtdStaAlwDis,
                                    DOCTMP.FTTmpStatus,
                                    DOCTMP.FCXtdVatRate,
                                    DOCTMP.FTXtdVatType,
                                    DOCTMP.FDLastUpdOn,
                                    DOCTMP.FDCreateOn,
                                    DOCTMP.FTLastUpdBy,
                                    DOCTMP.FTCreateBy,
                                    DOCTMP.FTTmpRemark
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                -- LEFT JOIN TCNMImgPdt IMGPDT on DOCTMP.FTPdtCode = IMGPDT.FTImgRefID AND IMGPDT.FTImgTable='TCNMPdt'
                                WHERE 1 = 1
                                AND DOCTMP.FTXthDocNo  = '$tSODocNo'
                                AND DOCTMP.FTXthDocKey = '$tSODocKey'
                                AND DOCTMP.FTSessionID = '$tSOSesSessionID' ";
                                
        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "   AND (
                                DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%' )
                        ";
            
        }
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";


        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aFoundRow  = $this->FSaMDPSGetDocDTTempListPageAll($paDataWhere);
            $nFoundRow  = ($aFoundRow['rtCode'] == '1')? $aFoundRow['rtCountData'] : 0;
            $nPageAll   = ceil($nFoundRow/$paDataWhere['nRow']);
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }   

    // Functionality : Count All Documeny DT Temp
    // Parameters : function parameters
    // Creator : 15/07/2021 Off
    // Last Modified : -
    // Return : array Data Count All Data
    // Return Type : array
    public function FSaMDPSGetDocDTTempListPageAll($paDataWhere){
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');

        $tSQL   = " SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP
                    WHERE 1 = 1 ";
        
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tSODocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tSODocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tSOSesSessionID' ";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Functionality : Function Sum Amount DT Temp
    // Parameters : function parameters
    // Creator : 15/07/2021 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSSumDocDTTemp($paDataWhere){
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT
                                    SUM(FCXtdNetAfHD)       AS FCXtdSumNetAfHD,
                                    SUM(FCXtdAmtB4DisChg)   AS FCXtdSumAmtB4DisChg
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                WHERE 1 = 1 ";
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tSODocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tSODocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tSOSesSessionID' ";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
            $aDataReturn    =  array(
                'raDataSum' => $aResult,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Sum Empty',
            );
        }
        unset($oQuery);
        unset($aResult);
        return $aDataReturn;
    }

    // Functionality : Get Data Pdt
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSGetDataPdt($paDataPdtParams){
        $tPdtCode   = $paDataPdtParams['tPdtCode'];
        $FTPunCode  = $paDataPdtParams['tPunCode'];
        $FTBarCode  = $paDataPdtParams['tBarCode'];
        $nLngID     = $paDataPdtParams['nLngID'];
        $tSQL       = " SELECT
                            PDT.FTPdtCode,
                            PDT.FTPdtStkControl,
                            PDT.FTPdtGrpControl,
                            PDT.FTPdtForSystem,
                            PDT.FCPdtQtyOrdBuy,
                            PDT.FCPdtCostDef,
                            PDT.FCPdtCostOth,
                            PDT.FCPdtCostStd,
                            PDT.FCPdtMin,
                            PDT.FCPdtMax,
                            PDT.FTPdtPoint,
                            PDT.FCPdtPointTime,
                            PDT.FTPdtType,
                            PDT.FTPdtSaleType,
                            0 AS FTPdtSalePrice,
                            PDT.FTPdtSetOrSN,
                            PDT.FTPdtStaSetPri,
                            PDT.FTPdtStaSetShwDT,
                            PDT.FTPdtStaAlwDis,
                            PDT.FTPdtStaAlwReturn,
                            PDT.FTPdtStaVatBuy,
                            PDT.FTPdtStaVat,
                            PDT.FTPdtStaActive,
                            PDT.FTPdtStaAlwReCalOpt,
                            PDT.FTPdtStaCsm,
                            PDT.FTTcgCode,
                            PDT.FTPtyCode,
                            PDT.FTPbnCode,
                            PDT.FTPmoCode,
                            PDT.FTVatCode,
                            PDT.FDPdtSaleStart,
                            PDT.FDPdtSaleStop,
                            PDTL.FTPdtName,
                            PDTL.FTPdtNameOth,
                            PDTL.FTPdtNameABB,
                            PDTL.FTPdtRmk,
                            PKS.FTPunCode,
                            PKS.FCPdtUnitFact,
                            VAT.FCVatRate,
                            UNTL.FTPunName,
                            BAR.FTBarCode,
                            BAR.FTPlcCode,
                            PDTLOCL.FTPlcName,
                            PDTSRL.FTSrnCode,
                            PDT.FCPdtCostStd,
                            CAVG.FCPdtCostEx,
                            CAVG.FCPdtCostIn,
                            SPL.FCSplLastPrice
                        FROM TCNMPdt PDT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
                        LEFT JOIN (
                            SELECT DISTINCT
                                FTVatCode,
                                FCVatRate,
                                FDVatStart
                            FROM TCNMVatRate WITH (NOLOCK)
                            WHERE CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart ) VAT
                        ON PDT.FTVatCode = VAT.FTVatCode
                        LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
                        LEFT JOIN TCNMPdtSpl SPL        WITH (NOLOCK)   ON PDT.FTPdtCode    = SPL.FTPdtCode AND BAR.FTBarCode = SPL.FTBarCode
                        LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
                        WHERE 1 = 1 ";
    
        if(isset($tPdtCode) && !empty($tPdtCode)){
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }

        if(isset($FTBarCode) && !empty($FTBarCode)){
            $tSQL   .= " AND BAR.FTBarCode = '$FTBarCode'";
        }

        $tSQL   .= " ORDER BY FDVatStart DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $aResult    = array(
                'raItem'    => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aResult;
    }

    // Functionality : Insert Pdt To Doc DT Temp
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if($paDataPdtParams['tSOOptionAddPdt'] == 1){
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "   SELECT
                                FNXtdSeqNo, 
                                FCXtdQty
                            FROM TCNTDocDTTmp
                            WHERE 1=1 
                            AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                            AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                            AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                            AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                            AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo
                        ";
                        // echo $tSQL.'<br>';
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "   UPDATE TCNTDocDTTmp
                                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                                    WHERE 1=1
                                    AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                                    AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                                    AND FNXtdSeqNo      = '".$aResult["FNXtdSeqNo"]."'
                                    AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                                    AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                                    AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                                ";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                // เพิ่มรายการใหม่
                $aDataInsert    = array(
                    'FTBchCode'         => $paDataPdtParams['tBchCode'],
                    'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                    'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                    'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                    'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                    'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                    'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                    'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                    'FTPunName'         => $paPIDataPdt['FTPunName'],
                    'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                    // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                    'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                    'FTVatCode'         => $paPIDataPdt['FTVatCode'],
                    'FCXtdVatRate'      => $paPIDataPdt['FCVatRate'],
                    'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                    'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                    'FCXtdVat'          => $paDataPdtParams['FCXsdVat'],
                    'FCXtdVatable'      => $paDataPdtParams['FCXsdVatable'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                    'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                    'FTTmpStatus'          => $paDataPdtParams['FTTmpStatus'],
                    // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                    'FTLastUpdBy'       => $paDataPdtParams['tSOUsrCode'],
                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                    'FTCreateBy'        => $paDataPdtParams['tSOUsrCode'],
                );
                $this->db->insert('TCNTDocDTTmp',$aDataInsert);

                // $this->db->last_query();  
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode'    => '1',
                        'rtDesc'    => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode'    => '905',
                        'rtDesc'    => 'Error Cannot Add.',
                    );
                }
            }   
        }else{
            // เพิ่มแถวใหม่
            $aDataInsert    = array(
                'FTBchCode'         => $paDataPdtParams['tBchCode'],
                'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                'FTPunName'         => $paPIDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                'FTVatCode'         => $paPIDataPdt['FTVatCode'],
                'FCXtdVatRate'      => $paPIDataPdt['FCVatRate'],
                'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdVat'          => $paDataPdtParams['FCXsdVat'],
                'FCXtdVatable'      => $paDataPdtParams['FCXsdVatable'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                'FTTmpStatus'          => $paDataPdtParams['FTTmpStatus'],
                // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tSOUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tSOUsrCode'],
            );
            $this->db->insert('TCNTDocDTTmp',$aDataInsert);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality : Insert Pdt SO To Doc DT Temp
    // Parameters : function parameters
    // Creator : 15/07/2021 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSInsertPDTSOToTemp($paDataPdtParams){
            // Delete Doc DT Temp
            // $this->db->where('FTSessionID',$paDataPdtParams['tSessionID']);
            // $this->db->where('FTXtdPdtName',$paDataPdtParams['FTXtdPdtName']);
            // $this->db->delete('TCNTDocDTTmp');
            // เพิ่มแถวใหม่
            $aDataInsert    = array(
                'FTBchCode'         => $paDataPdtParams['tBchCode'],
                'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                'FTPdtCode'         => $paDataPdtParams['tPdtCode'],
                'FTXtdPdtName'      => $paDataPdtParams['FTXtdPdtName'],
                'FTXtdVatType'      => $paDataPdtParams['FTXtdVatType'],
                'FCXtdVatRate'      => $paDataPdtParams['FCXtdVatRate'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1,
                'FTXtdStaAlwDis'    => 1,
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                'FCXtdVat'          => $paDataPdtParams['FCXsdVat'],
                'FCXtdVatable'      => $paDataPdtParams['FCXsdVatable'],
                'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTTmpStatus'       => $paDataPdtParams['FTTmpStatus'],
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tSOUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tSOUsrCode'],
            );
            $this->db->insert('TCNTDocDTTmp',$aDataInsert);
            // $this->db->last_query();  
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        return $aStatus;
    }

    // Functionality : Update Document DT Temp by Seq
    // Parameters : function parameters
    // Creator : 05/07/2019 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
            // $this->db->set($paDataUpdateDT['tSOFieldName'], $paDataUpdateDT['tSOValue']);
            $this->db->set('FCXtdSetPrice', $paDataUpdateDT['FCXtdSetPrice']);
            $this->db->set('FCXtdNet', $paDataUpdateDT['FCXtdNet']);
            $this->db->set('FTXtdPdtName', $paDataUpdateDT['FTXtdPdtName']);
            $this->db->set('FTTmpRemark', $paDataUpdateDT['FTTmpRemark']);
            $this->db->set('FCXtdSalePrice', $paDataUpdateDT['FCXtdSalePrice']);
            $this->db->set('FCXtdVat', $paDataUpdateDT['FCXsdVat']);
            $this->db->set('FCXtdVatable', $paDataUpdateDT['FCXsdVatable']);

            $this->db->where('FTSessionID',$paDataWhere['tSOSessionID']);
            $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
            $this->db->where('FNXtdSeqNo',$paDataWhere['nSOSeqNo']);
            $this->db->where('FTXthDocNo',$paDataWhere['tSODocNo']);
            $this->db->where('FTBchCode',$paDataWhere['tSOBchCode']);
            $this->db->update('TCNTDocDTTmp');
            
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Update Success',
                );
            }else{
                $aStatus = array(
                    'rtCode'    => '903',
                    'rtDesc'    => 'Update Fail',
                );
            }
            return $aStatus;
    }

    // Functionality : Count Check Data Product In Doc DT Temp Before Save
    // Parameters : function parameters
    // Creator : 05/07/2021 Off
    // Last Modified : -
    // Return : Array Status Count
    // Return Type : array
    public function FSnMDPSChkPdtInDocDTTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        $tSODocKey      = $paDataWhere['FTXthDocKey'];
        $tSOSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocNo    = '$tSODocNo'
                            AND DocDT.FTXthDocKey   = '$tSODocKey'
                            AND DocDT.FTSessionID   = '$tSOSessionID' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // Functionality :  Delete Product Single Item In Doc DT Temp
    // Parameters : function parameters
    // Creator : 02/07/2021 Off
    // Last Modified : -
    // Return : Array Status Delete
    // Return Type : array
    public function FSnMDPSDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        // $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        return ;
    }

    // Functionality : Delete Product Multiple Items In Doc DT Temp
    // Parameters : function parameters
    // Creator : 02/07/2021 Off
    // Last Modified : -
    // Return : Array Status Delete
    // Return Type : array
    public function FSnMDPSDelMultiPdtInDTTmp($paDataWhere){
        $tSessionID = $this->session->userdata('tSesSessionID');
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['aDataSeqNo']);
        // $this->db->where_in('FTPdtCode',$paDataWhere['aDataPdtCode']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        return ;
    }


    // ============================================================================== Calcurate HD Document =============================================================================

    // Functionality : Function Get Cal From DT Temp
    // Parameters : function parameters
    // Creator : 05/07/2021 Off
    // Last Modified : -
    // Return : array
    // Return Type : array
    public function FSaMDPSCalInDTTemp($paParams){
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];

        $tSQL       = " SELECT
                            /* ยอดรวม ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXshTotal,

                            /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalNV,

                            /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalNoDis,

                            /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalB4DisChgV,

                            /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalB4DisChgNV,

                            /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXshTotalAfDisChgV,

                            /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXshTotalAfDisChgNV,

                            /* ยอดรวมเฉพาะภาษี ==============================================================*/
                            (
                                CASE 
                                    WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                                        (
                                            /* ยอดรวม */
                                            SUM(DTTMP.FCXtdNet)
                                            - 
                                            /* ยอดรวมสินค้าไม่มีภาษี */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                        -
                                        (
                                            /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                            -
                                            /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                    WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                                    
                                            (
                                                /* ยอดรวม */
                                                SUM(DTTMP.FCXtdNet)
                                                - 
                                                /* ยอดรวมสินค้าไม่มีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                            -
                                            (
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                                -
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN 
                                                            ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            ) 
                                            + 
                                            SUM(ISNULL(DTTMP.FCXtdVat, 0))
                                ELSE 0 END
                            ) AS FCXshAmtV,

                            /* ยอดรวมเฉพาะไม่มีภาษี ==============================================================*/
                            (
                                SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                                -
                                (
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                                    -
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                                )
                            ) AS FCXshAmtNV,

                            /* ยอดภาษี ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXshVat,

                            /* ยอดแยกภาษี ==============================================================*/
                            (
                                (
                                    CASE 
                                        WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                                            (
                                                /* ยอดรวม */
                                                SUM(DTTMP.FCXtdNet)
                                                - 
                                                /* ยอดรวมสินค้าไม่มีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                            -
                                            (
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                                -
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                        WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                                        
                                                (
                                                    /* ยอดรวม */
                                                    SUM(DTTMP.FCXtdNet)
                                                    - 
                                                    /* ยอดรวมสินค้าไม่มีภาษี */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                )
                                                -
                                                (
                                                    /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                    -
                                                    /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN 
                                                                ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                ) 
                                                + 
                                                SUM(ISNULL(DTTMP.FCXtdVat, 0))
                                    ELSE 0 END
                                    - 
                                    SUM(ISNULL(DTTMP.FCXtdVat, 0))
                                )
                                +
                                (
                                    SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                                    -
                                    (
                                        SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                                        -
                                        SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                                    )
                                )
                            ) AS FCXshVatable,

                            /* รหัสอัตราภาษี ณ ที่จ่าย ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                                FROM TCNTDocDTTmp DOCCONCAT
                                WHERE  1=1 
                                AND DOCCONCAT.FTBchCode = '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                                AND DOCCONCAT.FTSessionID = '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXshWpCode,

                            /* ภาษีหัก ณ ที่จ่าย ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdWhtAmt, 0)) AS FCXshWpTax

                        FROM TCNTDocDTTmp DTTMP
                        WHERE DTTMP.FTXthDocNo  = '$tDocNo' 
                        AND DTTMP.FTXthDocKey   = '$tDocKey' 
                        AND DTTMP.FTSessionID   = '$tSessionID'
                        AND DTTMP.FTBchCode     = '$tBchCode'
                        GROUP BY DTTMP.FTSessionID ";

                        // echo $tSQL;
                        // die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->result_array();
        }else{
            $aResult    = [];
        }
        return $aResult;
    }
    
    // ============================================================================= Add/Edit Event Document =============================================================================

    // Functionality : Add/Update Data HD
    // Parameters : function parameters
    // Creator : 05/07/2021 Off
    // Last Modified : -
    // Return : Array Status Add/Update Document HD
    // Return Type : array
    public function FSxMDPSAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        // Get Data PI HD
        $aDataGetDataHD     =   $this->FSaMDPSGetDataDocHD(array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->input->post("ohdSOLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['FDCreateOn'],
                'FTCreateBy'    => $aDataHDOld['FTCreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        // Delete PI HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert PI HD Dis
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);

        return;
    }


    // Functionality : Add/Update Data HD Cst
    // Parameters : function parameters
    // Creator : 07/07/2021 Off
    // Last Modified : -
    // Return : Array Status Add/Update Document HD Cst
    // Return Type : array
    public function FSxMDPSAddUpdateHDCst($paDataMaster,$paDataWhere,$paTableAddUpdate){
        // Get Data SO HD
        $aDataGetDataHD     =   $this->FSaMDPSGetDataDocHD(array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->input->post("ohdSOLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FTXshCardID'   => $paDataMaster['FTXshCardID'],
                'FTXshCstName'   => $paDataMaster['FTXshCstName'],
                'FTXshCstTel'   => $paDataMaster['FTXshCstTel'],
                'FTCstCode'     => $paDataMaster['FTCstCode'],
                // 'FNXshAddrTax'     => $paDataMaster['FNXshAddrTax']
            );
        }else{
            $aDataAddUpdateHD   = array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FTXshCardID'   => $paDataMaster['FTXshCardID'],
                'FTXshCstName'   => $paDataMaster['FTXshCstName'],
                'FTXshCstTel'   => $paDataMaster['FTXshCstTel'],
                'FTCstCode'     => $paDataMaster['FTCstCode'],
                // 'FNXshAddrTax'     => $paDataMaster['FNXshAddrTax']
            );
        }
        // Delete SO HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDCst']);

        // Insert SO HD Cst
        $this->db->insert($paTableAddUpdate['tTableHDCst'],$aDataAddUpdateHD);
        return;
    }

    // Functionality : Update DocNo In Doc Temp
    // Parameters : function parameters
    // Creator : 07/07/2021 Off
    // Last Modified : -
    // Return : Array Status Update DocNo In Doc Temp
    // Return Type : array
    public function FSxMDPSAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableHD']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        return;
    }

    // Functionality : Move Document DTTemp To Document DT
    // Parameters : function parameters
    // Creator : 07/07/2021 Off
    // Last Modified : -
    // Return : Array Status Insert Tempt To DT
    // Return Type : array
    public function FSaMDPSMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tSOBchCode     = $paDataWhere['FTBchCode'];
        $tSODocNo       = $paDataWhere['FTXshDocNo'];
        $tSODocKey      = $paTableAddUpdate['tTableHD'];
        $tSOSessionID   = $this->input->post('ohdSesSessionID');
        $tDPSVatCode    = $paDataWhere['FTVatCode'];
        $tDPSVatRate    = $paDataWhere['FTVatRate'];
        
        if(isset($tSODocNo) && !empty($tSODocNo)){
            $this->db->where_in('FTXshDocNo',$tSODocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTBchCode,FTXshDocNo,FNXsdSeqNo,FTXsdName,FCXsdTotal,FCXsdDeposit,FTXsdRmk,FTXsdVatType,FTVatCode,FTVatRate,FCXsdVat,FCXsdVatable,FTXsdSoRef ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTBchCode,
                            DOCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DOCTMP.FTXtdPdtName,
                            DOCTMP.FCXtdSalePrice,
                            DOCTMP.FCXtdNet,
                            DOCTMP.FTTmpRemark,
                            ISNULL(DOCTMP.FTXtdVatType,'1') AS FTXsdVatType,
                            '$tDPSVatCode' AS FTVatCode,
                            '$tDPSVatRate' AS FTVatRate,
                            DOCTMP.FCXtdVat,
                            DOCTMP.FCXtdVatable,
                            DOCTMP.FTTmpStatus
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tSOBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tSODocNo'
                        AND DOCTMP.FTXthDocKey  = '$tSODocKey'
                        AND DOCTMP.FTSessionID  = '$tSOSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // ============================================================================ Edit Page Query ============================================================================

    // Functionality : Get Data Document HD
    // Parameters : function parameters
    // Creator : 07/07/2021 Off
    // Last Modified : -
    // Return : Array Data Document HD
    // Return Type : array
    public function FSaMDPSGetDataDocHD($paDataWhere){
        $tSODocNo   = $paDataWhere['FTXthDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');

        $tSQL       = " SELECT
                            DOCHD.FTBchCode,
                            BCHL.FTBchName,
                            SHP.FTMerCode,
                            MERL.FTMerName,
                            SHP.FTShpType,
                            SHP.FTShpCode,
                            SHPL.FTShpName,
                            DOCHD.FTXshDocNo,
                            DOCHD.FNXshDocType,
                            DOCHD.FDXshDocDate,
                            DOCHD.FTXshCshOrCrd,
                            DOCHD.FTXshVATInOrEx,
                            DOCHD.FTDptCode,
                            DPTL.FTDptName,
                            DOCHD.FTUsrCode,
                            USRL.FTUsrName,
                            DOCHD.FTXshApvCode,
                            USRAPV.FTUsrName	AS FTXshApvName,
                            DOCHD.FTXshRefExt,
                            DOCHD.FDXshRefExtDate,
                            DOCHD.FTXshRefInt,
                            DOCHD.FDXshRefIntDate,
                            DOCHD.FNXshDocPrint,
                            DOCHD.FTRteCode,
                            RTL.FTRteName,
                            DOCHD.FCXshRteFac,
                            DOCHD.FTXshRmk,
                            DOCHD.FTXshStaRefund,
                            DOCHD.FTXshStaDoc,
                            DOCHD.FTXshStaApv,
                            DOCHD.FTXshStaPaid,
                            SPN.FTUsrName AS rtSpnName,
                            DOCHD.FNXshStaDocAct,
                            DOCHD.FNXshStaRef,
                            DOCHD.FTPosCode,
                            DOCHD.FTCstCode,
                            HDCST.FTXshCardID,
                            HDCST.FTXshCstName,
                            HDCST.FTXshCstTel,
                            (
                            SELECT
                            MAX (FNDatApvSeq)
                            FROM
                            TARTDocApvTxn
                            WHERE
                            TARTDocApvTxn.FTBchCode = DOCHD.FTBchCode
                            AND TARTDocApvTxn.FTDatRefCode = DOCHD.FTXshDocNo
                            AND TARTDocApvTxn.FTDatUsrApv IS NOT NULL
                            GROUP BY
                            TARTDocApvTxn.FTDatRefCode
                            ) AS LastSeq,
                            CST.FTCstDiscRet,
                            IMGOBJ.FTImgObj,
                            DOCHD.FDLastUpdOn,
                            DOCHD.FTLastUpdBy,
                            DOCHD.FDCreateOn,
                            DOCHD.FTCreateBy,
                            CSRAD.FTAddV2Desc1,
                            CONCAT(ADDL.FTAddV1No,' ', ADDL.FTAddV1Soi,' ', ADDL.FTAddV1Village,' ', ADDL.FTAddV1Road,' ',
                             SUBDL.FTSudName,' ', DISL.FTDstName,' ', PRO.FTPvnName,' ', ADDL.FTAddV2Desc2) AS FTAddV1Desc,
                             CLEV.FTPplCode
                        FROM TARTRcvDepositHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMShop          SHP     WITH (NOLOCK)   ON DOCHD.FTShpCode      = SHP.FTShpCode 
                        LEFT JOIN TCNMCstAddress_L  CSRAD   WITH (NOLOCK)   ON DOCHD.FTCstCode    = CSRAD.FTCstCode 
                        LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK)   ON DOCHD.FTShpCode      = SHPL.FTShpCode	AND SHPL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK)   ON SHP.FTMerCode        = MERL.FTMerCode	AND MERL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXshApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        SPN     WITH (NOLOCK)    ON DOCHD.FTSpnCode      = SPN.FTUsrCode	    AND SPN.FNLngID	    = $nLngID
                        LEFT JOIN TARTRcvDepositHDCst       HDCST   WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = HDCST.FTXshDocNo
                        LEFT JOIN TCNMCst           CST     WITH (NOLOCK)   ON DOCHD.FTCstCode      = CST.FTCstCode
                        LEFT JOIN TCNMCstLev        CLEV    WITH (NOLOCK)   ON CST.FTClvCode = CLEV.FTClvCode
                        LEFT JOIN TFNMRate_L        RTL     WITH (NOLOCK)   ON DOCHD.FTRteCode      = RTL.FTRteCode AND RTL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMImgObj        IMGOBJ  WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = IMGOBJ.FTImgRefID  AND IMGOBJ.FTImgTable='TARTSoHD'
                        LEFT JOIN TCNMCstAddress_L  ADDL    WITH (NOLOCK)   ON CST.FTCstCode = ADDL.FTCstCode AND ADDL.FTAddVersion = '$nAddressVersion'
                        LEFT JOIN TCNMProvince_L    PRO     WITH (NOLOCK)   ON ADDL.FTAddV1PvnCode = PRO.FTPvnCode AND PRO.FNLngID = $nLngID
                        LEFT JOIN TCNMDistrict_L    DISL    WITH (NOLOCK)   ON ADDL.FTAddV1DstCode = DISL.FTDstCode AND DISL.FNLngID = $nLngID
                        LEFT JOIN TCNMSubDistrict_L SUBDL   WITH (NOLOCK)   ON ADDL.FTAddV1SubDist = SUBDL.FTSudCode AND SUBDL.FNLngID = $nLngID
                        WHERE DOCHD.FTXshDocNo = '$tSODocNo' ";
                        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    // Functionality : Move Data DT To DTTemp
    // Parameters : function parameters
    // Creator : 15/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSxMDPSMoveDTToDTTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        $tSODocKey      = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTXtdPdtName,
                        FCXtdSalePrice,FCXtdSetPrice,
                        FCXtdNet,FTTmpRemark,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdVat,FCXtdVatable,FTXtdStaAlwDis,FCXtdQty,FTTmpStatus )
                    SELECT
                        DPDT.FTBchCode,
                        DPDT.FTXshDocNo,
                        DPDT.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$tSODocKey."') AS FTXthDocKey,
                        DPDT.FTXsdName,
                        DPDT.FCXsdTotal,
                        DPDT.FCXsdDeposit,
                        DPDT.FCXsdDeposit,
                        DPDT.FTXsdRmk,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                        DPDT.FTXsdVatType,
                        DPDT.FTVatCode,
                        DPDT.FTVatRate,
                        DPDT.FCXsdVat,
                        DPDT.FCXsdVatable,
                        1 AS FTXtdStaAlwDis,
                        1 AS FCXtdQty,
                        DPDT.FTXsdSoRef
                    FROM TARTRcvDepositDT AS DPDT WITH (NOLOCK)
                    WHERE 1=1 AND DPDT.FTXshDocNo = '$tSODocNo'
                    ORDER BY DPDT.FNXsdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        return;
    }
    
    // ============================================================================ Edit Page Query ============================================================================

    // Functionality : Cancel Document Data
    // Parameters : function parameters
    // Creator : 08/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSaMDPSCancelDocument($paDataUpdate){
        // TARTSoHD
        $this->db->trans_begin();
        $this->db->set('FTXshStaDoc' , '3');
        $this->db->where('FTXshDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TARTRcvDepositHD');

        $this->db->where('FTDatRefCode',$paDataUpdate['tDocNo'])->delete('TARTDocApvTxn');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Cancel Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Cancel Success."
            );
        }
        return $aDatRetrun;
    }

    // Functionality : Approve Document Data
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSaMDPSApproveDocument($paDataUpdate){
        // TARTSoHD
        $this->db->trans_begin();

        $tDocNo     = $paDataUpdate['tDocNo'];
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaApv',$paDataUpdate['nStaApv']);
        $this->db->set('FTXshApvCode',$paDataUpdate['tApvCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->update('TARTRcvDepositHD');

        //หลังจากอนุมัติ ต้องอัพเดท FCXshPaid , FCXshLeft
        $tSQL = "UPDATE HD
                SET 
                    HD.FCXshPaid = '0' ,
                    HD.FCXshLeft = RES.FCXshGrand 
                FROM TARTRcvDepositHD AS HD WITH(NOLOCK)
                INNER JOIN (
                    SELECT 
                        HDRes.FTXshDocNo , 
                        HDRes.FTBchCode ,
                        HDRes.FCXshGrand
                    FROM TARTRcvDepositHD HDRes WITH(NOLOCK)
                    WHERE HDRes.FTXshDocNo = '$tDocNo'
                ) RES 
                ON RES.FTXshDocNo = HD.FTXshDocNo
                AND RES.FTBchCode = HD.FTBchCode ";
        $this->db->query($tSQL);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Approve Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Approve Success."
            );
        }
        return $aDatRetrun;
    }

    // Functionality : Approve Paid Data
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSaMDPSApprovePaidDocument($paDataUpdate){
        // TARTSoHD
        $this->db->trans_begin();

        $tDocNo     = $paDataUpdate['tDocNo'];
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaPaid','4');
        $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->update('TARTRcvDepositHD');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Paid Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Paid Success."
            );
        }
        return $aDatRetrun;
    }

    // ================================================================== Search And Add Product In DT Temp ====================================================================

    // Functionality : Fet Doc Type
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSnMDPSGetDocType(){
        $tSql = "
        SELECT
            TSysDocType.FNSdtDocType
            FROM [dbo].[TSysDocType]
            WHERE 
            TSysDocType.FTSdtTblName='TARTSoHD'
        ";
        $oQuery = $this->db->query($tSql);
        return $oQuery->row_array();
    }

    // Functionality : Get Detail User Branch
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSaMDPSGetDetailUserBranch($paBchCode){
        if(!empty($paBchCode)){
        $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
        $aReulst['item'] = $aReustl;
        $aReulst['code'] = 1;
        $aReulst['msg'] = 'Success !';
        }else{
        $aReulst['code'] = 2;
        $aReulst['msg'] = 'Error !';
        }
        return $aReulst;
    }




    // Function: Get Data SO HD List // เอกสารการสั่งขาย
    public function FSoMDPSCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDPSRefIntBchCode        = $aAdvanceSearch['tDPSRefIntBchCode'];
        $tDPSRefIntDocNo          = $aAdvanceSearch['tDPSRefIntDocNo'];
        $tDPSRefIntDocDateFrm     = $aAdvanceSearch['tDPSRefIntDocDateFrm'];
        $tDPSRefIntDocDateTo      = $aAdvanceSearch['tDPSRefIntDocDateTo'];
        $tDPSRefIntStaDoc         = $aAdvanceSearch['tDPSRefIntStaDoc'];

        $tSQLMain = "   SELECT
                                SOHD.FTBchCode,
                                BCHL.FTBchName,
                                SOHD.FTXshDocNo,
                                CONVERT(CHAR(10),SOHD.FDXshDocDate,103) AS FDXshDocDate,
                                CONVERT(CHAR(5), SOHD.FDXshDocDate,108) AS FTXshDocTime,
                                SOHD.FTXshStaDoc,
                                SOHD.FTXshStaApv,
                                SOHD.FNXshStaRef,
                                SOHD.FTCreateBy,
                                SOHD.FDCreateOn,
                                SOHD.FNXshStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                SOHD.FTXshApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName,
                                SOHD.FTCstCode,
                                CSTL.FTCstName
                            FROM TARTSoHD           SOHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK)   ON SOHD.FTBchCode   = BCHL.FTBchCode    AND BCHL.FNLngID    = '$nLngID'
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK)   ON SOHD.FTCreateBy  = USRL.FTUsrCode    AND USRL.FNLngID    = '$nLngID'
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK)   ON SOHD.FTBchCode   = WAH_L.FTBchCode   AND SOHD.FTWahCode  = WAH_L.FTWahCode AND WAH_L.FNLngID	= '$nLngID'
                            LEFT JOIN TCNMCst_L     CSTL    WITH(NOLOCK)    ON SOHD.FTCstCode   = CSTL.FTCstCode    AND CSTL.FNLngID    = '$nLngID'
                            WHERE ISNULL(SOHD.FNXshStaRef,'') != 2 AND SOHD.FTXshStaDoc = 1 AND SOHD.FTXshStaApv = 1
                    ";
        if(isset($tDPSRefIntBchCode) && !empty($tDPSRefIntBchCode)){
            $tSQLMain .= " AND (SOHD.FTBchCode = '$tDPSRefIntBchCode')";
        }

        if(isset($tDPSRefIntDocNo) && !empty($tDPSRefIntDocNo)){
            $tSQLMain .= " AND (SOHD.FTXshDocNo LIKE '%$tDPSRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDPSRefIntDocDateFrm) && !empty($tDPSRefIntDocDateTo)){
            $tSQLMain .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateTo 23:59:59')) OR (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDPSRefIntStaDoc) && !empty($tDPSRefIntStaDoc)){
            if ($tDPSRefIntStaDoc == 3) {
                $tSQLMain .= " AND SOHD.FTXshStaDoc = '$tDPSRefIntStaDoc'";
            } elseif ($tDPSRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tDPSRefIntStaDoc == 1) {
                $tSQLMain .= " AND SOHD.FTXshStaApv = '$tDPSRefIntStaDoc'";
            }
        }

        $tSQL   =   "       SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
            
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Function: Get Data SO HD List // เอกสารใบเสนอราคา
    public function FSoMDPSCallRefIntDocDataTableQT($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tDPSRefIntBchCode        = $aAdvanceSearch['tDPSRefIntBchCode'];
        $tDPSRefIntDocNo          = $aAdvanceSearch['tDPSRefIntDocNo'];
        $tDPSRefIntDocDateFrm     = $aAdvanceSearch['tDPSRefIntDocDateFrm'];
        $tDPSRefIntDocDateTo      = $aAdvanceSearch['tDPSRefIntDocDateTo'];
        $tDPSRefIntStaDoc         = $aAdvanceSearch['tDPSRefIntStaDoc'];

        $tSQLMain = "   SELECT
                                TSDHD.FTBchCode,
                                BCHL.FTBchName,
                                TSDHD.FTXshDocNo,
                                CONVERT(CHAR(10),TSDHD.FDXshDocDate,103) AS FDXshDocDate,
                                CONVERT(CHAR(5), TSDHD.FDXshDocDate,108) AS FTXshDocTime,
                                TSDHD.FTXshStaDoc,
                                TSDHD.FTXshStaApv,
                                TSDHD.FNXshStaRef,
                                TSDHD.FTCreateBy,
                                TSDHD.FDCreateOn,
                                TSDHD.FNXshStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                TSDHD.FTXshApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName,
                                TSDHD.FTCstCode,
                                CSTL.FTCstName
                            FROM TARTSqHD           TSDHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON TSDHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = '$nLngID'
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON TSDHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = '$nLngID'
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON TSDHD.FTBchCode     = WAH_L.FTBchCode   AND TSDHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID = '$nLngID'
                            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON TSDHD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
                            WHERE ISNULL(TSDHD.FNXshStaRef,'') != 2 AND TSDHD.FTXshStaDoc = 1 AND TSDHD.FTXshStaApv = 1
                    ";
                    // echo $tSQLMain;
        if(isset($tDPSRefIntBchCode) && !empty($tDPSRefIntBchCode)){
            $tSQLMain .= " AND (TSDHD.FTBchCode = '$tDPSRefIntBchCode')";
        }

        if(isset($tDPSRefIntDocNo) && !empty($tDPSRefIntDocNo)){
            $tSQLMain .= " AND (TSDHD.FTXshDocNo LIKE '%$tDPSRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDPSRefIntDocDateFrm) && !empty($tDPSRefIntDocDateTo)){
            $tSQLMain .= " AND ((TSDHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateTo 23:59:59')) OR (TSDHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDPSRefIntStaDoc) && !empty($tDPSRefIntStaDoc)){
            if ($tDPSRefIntStaDoc == 3) {
                $tSQLMain .= " AND TSDHD.FTXshStaDoc = '$tDPSRefIntStaDoc'";
            } elseif ($tDPSRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(TSDHD.FTXshStaApv,'') = '' AND TSDHD.FTXshStaDoc != '3'";
            } elseif ($tDPSRefIntStaDoc == 1) {
                $tSQLMain .= " AND TSDHD.FTXshStaApv = '$tDPSRefIntStaDoc'";
            }
        }

        $tSQL   =   "       SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Function: Get Data SO HD List // เอกสารใบเคลมสินค้า
    public function FSoMDPSCallRefIntDocDataTableCLM($paDataCondition){
        $aRowLen        = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID         = $paDataCondition['FNLngID'];
        $aAdvanceSearch = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tDPSRefIntBchCode        = $aAdvanceSearch['tDPSRefIntBchCode'];
        $tDPSRefIntDocNo          = $aAdvanceSearch['tDPSRefIntDocNo'];
        $tDPSRefIntDocDateFrm     = $aAdvanceSearch['tDPSRefIntDocDateFrm'];
        $tDPSRefIntDocDateTo      = $aAdvanceSearch['tDPSRefIntDocDateTo'];
        $tDPSRefIntStaDoc         = $aAdvanceSearch['tDPSRefIntStaDoc'];

        $tSQLMain   = "
            SELECT
                HD.FTBchCode,
                BCHL.FTBchName,
                HD.FTPchDocNo AS FTXshDocNo,
                CONVERT(CHAR(10),HD.FDPchDocDate,103) AS FDXshDocDate,
                CONVERT(CHAR(5), HD.FDPchDocDate,108) AS FTXshDocTime,
                HD.FTPchStaDoc AS FTXshStaDoc,
                HD.FTPchStaApv AS FTXshStaApv,
                '' AS FNXshStaRef,
                HD.FTCreateBy,
                HD.FDCreateOn,
                HD.FTPchStaDocAct AS FNXshStaDocAct,
                USRL.FTUsrName 	AS FTCreateByName,
                HD.FTPchUsrApv 	AS FTXshApvCode,
                HD.FTCstCode,
                CSTL.FTCstName
            FROM TCNTPdtClaimHD HD WITH(NOLOCK)
            LEFT JOIN TCNMBranch_L  BCHL    WITH(NOLOCK)    ON HD.FTBchCode     = BCHL.FTBchCode 	AND BCHL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L    USRL    WITH(NOLOCK)    ON HD.FTCreateBy 	= USRL.FTUsrCode 	AND USRL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCst_L     CSTL    WITH(NOLOCK)	ON HD.FTCstCode	    = CSTL.FTCstCode    AND CSTL.FNLngID = '$nLngID'
            WHERE HD.FTPchStaDoc = '1' AND HD.FTPchStaPrcDoc >= 2
        ";

        // Check Branch Code
        if(isset($tDPSRefIntBchCode) && !empty($tDPSRefIntBchCode)){
            $tSQLMain .= " AND (HD.FTBchCode = '$tDPSRefIntBchCode')";
        }

        // Check Doc No
        if(isset($tDPSRefIntDocNo) && !empty($tDPSRefIntDocNo)){
            $tSQLMain .= " AND (HD.FTPchDocNo LIKE '%$tDPSRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDPSRefIntDocDateFrm) && !empty($tDPSRefIntDocDateTo)){
            $tSQLMain .= " AND ((HD.FDPchDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateTo 23:59:59')) OR (HD.FDPchDocDate BETWEEN CONVERT(datetime,'$tDPSRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDPSRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDPSRefIntStaDoc) && !empty($tDPSRefIntStaDoc)){
            if ($tDPSRefIntStaDoc == 3) {
                $tSQLMain .= " AND (HD.FTPchStaDoc = '$tDPSRefIntStaDoc')";
            } elseif ($tDPSRefIntStaDoc == 2) {
                $tSQLMain .= " AND (ISNULL(HD.FTPchStaApv,'') = '' AND HD.FTPchStaDoc != '3')";
            } elseif ($tDPSRefIntStaDoc == 1) {
                $tSQLMain .= " AND (HD.FTPchStaApv = '$tDPSRefIntStaDoc')";
            }
        }

        $tSQL   =   "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* 
                FROM (
                    ".$tSQLMain."
                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";
    
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }


    // Functionality: Get Document Ref DT LIST // เอกสารการสั่งขาย
    public function FSoMDPSCallRefIntDocDTDataTable($paData){

        $nLngID   =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];
        
        $tSQL= "SELECT
                    DT.FTBchCode,
                    DT.FTXshDocNo,
                    DT.FNXsdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdQtyAll,
                    DT.FTXsdRmk,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TARTSoDT DT WITH(NOLOCK)
            WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo'
            ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Document Ref DT LIST // เอกสารใบเสนอราคา
    public function FSoMDPSCallRefIntDocDTDataTableQT($paData){

        $nLngID   =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];
        
        $tSQL= "SELECT
                    DT.FTBchCode,
                    DT.FTXshDocNo,
                    DT.FNXsdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdQtyAll,
                    DT.FTXsdRmk,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TARTSqDT DT WITH(NOLOCK)
            WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo'
            ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Document Ref DT LIST // เอกสารใบเคลมสินค้า
    public function FSoMDPSCallRefIntDocDTDataTableCLM($paData){
        $nLngID     =  $paData['FNLngID'];
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
            SELECT 
                DT.FTBchCode,
                DT.FTPchDocNo 		AS FTXshDocNo,
                DT.FNPcdSeqNo 		AS FNXsdSeqNo,
                DT.FTPdtCode,
                DT.FTPcdPdtName 	AS FTXsdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCPcdFactor 	    AS FCXsdFactor,
                DT.FTPcdBarCode 	AS FTXsdBarCode,
                DT.FCPcdQty 		AS FCXsdQty,
                DT.FCPcdQtyAll		AS FCXsdQtyAll,
                DT.FTPcdRmk			AS FTXsdRmk,
                HD.FDLastUpdOn,
                HD.FTLastUpdBy,
                HD.FDCreateOn,
                HD.FTCreateBy
            FROM TCNTPdtClaimDT DT WITH(NOLOCK)
            LEFT JOIN TCNTPdtClaimHD HD WITH(NOLOCK) ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTBchCode = HD.FTBchCode AND DT.FTPchDocNo = HD.FTPchDocNo
            WHERE DT.FTBchCode = '$tBchCode' AND DT.FTPchDocNo = '$tDocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($nLngID);
        unset($tBchCode);
        unset($tDocNo);
        unset($tSQL);
        unset($oQuery);
        unset($oDataList);
        return $aResult;
    }


    // นำข้อมูลจาก Browse ลง DTTemp // เอกสารการสั่งขาย
    public function FSoMDPSCallRefIntDocInsertDTToTemp($paData){

        $tDPSDocNo        = $paData['tDPSDocNo'];
        $tDPSFrmBchCode   = $paData['tDPSFrmBchCode'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tDPSFrmBchCode);
        $this->db->where('FTXthDocNo',$tDPSDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $nFlag          = $paData['nFlag'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';
        if($nFlag == '1'){
        $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet )
                SELECT
                    '$tDPSFrmBchCode' as FTBchCode,
                    '$tDPSDocNo' as FTXthDocNo,
                    DT.FNXsdSeqNo,
                    'TARTRcvDepositHD' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdQtyAll,
                    '' as FTXsdRmk,   
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                    DT.FTXsdVatType,
                    DT.FTVatCode,
                    DT.FCXsdVatRate,
                    DT.FCXsdSetPrice,
                    DT.FCXsdSetPrice,
                    DT.FCXsdVat,
                    DT.FCXsdVatable,
                    DT.FCXsdNet
                FROM TARTSqDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo
                ";
        }else{
            $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet,FTTmpStatus )
                SELECT
                    '$tDPSFrmBchCode' as FTBchCode,
                    '$tDPSDocNo' as FTXthDocNo,
                    '1',
                    'TARTRcvDepositHD' AS FTXthDocKey,
                    TSYL.FTPdtCode,
                    TSYL.FTPdtName,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    '1',
                    '1',
                    '' as FTXsdRmk,   
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                    '1',
                    NULL,
                    7,
                    DT.FCXshTotal,
                    DT.FCXshTotal,
                    DT.FCXshVat,
                    DT.FCXshVatable,
                    DT.FCXshTotal,
                    '1'
                FROM TARTSqHD DT WITH (NOLOCK),TSysPdt TSY WITH (NOLOCK)
                LEFT JOIN TSysPdt_L TSYL WITH (NOLOCK) ON  TSY.FTPdtCode = TSYL.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND TSY.FTPdtSysTable = 'TARTRcvDepositHD'
                ";
        }
        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // นำข้อมูลจาก Browse ลง DTTemp // เอกสารใบเสนอราคา
    public function FSoMDPSCallRefIntDocInsertDTToTempSO($paData){

        $tDPSDocNo        = $paData['tDPSDocNo'];
        $tDPSFrmBchCode   = $paData['tDPSFrmBchCode'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tDPSFrmBchCode);
        $this->db->where('FTXthDocNo',$tDPSDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $nFlag          = $paData['nFlag'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';
        if($nFlag == '1'){
            $tSQL= "INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,
                        FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                        FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet
                    )
                    SELECT
                        '$tDPSFrmBchCode' as FTBchCode,
                        '$tDPSDocNo' as FTXthDocNo,
                        DT.FNXsdSeqNo,
                        'TARTRcvDepositHD' AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        DT.FCXsdQty,
                        DT.FCXsdQtyAll,
                        '' as FTXsdRmk,   
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                        DT.FTXsdVatType,
                        DT.FTVatCode,
                        DT.FCXsdVatRate,
                        DT.FCXsdSetPrice,
                        DT.FCXsdSetPrice,
                        DT.FCXsdVat,
                        DT.FCXsdVatable,
                        DT.FCXsdNet
                    FROM TARTSoDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo
            ";
        }else{
            $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet,FTTmpStatus )
                SELECT
                    '$tDPSFrmBchCode' as FTBchCode,
                    '$tDPSDocNo' as FTXthDocNo,
                    '1',
                    'TARTRcvDepositHD' AS FTXthDocKey,
                    TSYL.FTPdtCode,
                    TSYL.FTPdtName,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    '1',
                    '1',
                    '' as FTXsdRmk,   
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                    '1',
                    NULL,
                    7,
                    DT.FCXshTotal,
                    DT.FCXshTotal,
                    DT.FCXshVat,
                    DT.FCXshVatable,
                    DT.FCXshTotal,
                    '1'
                FROM
                    TARTSoHD DT WITH (NOLOCK),TSysPdt TSY WITH (NOLOCK)
                    LEFT JOIN TSysPdt_L TSYL WITH (NOLOCK) ON  TSY.FTPdtCode = TSYL.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND TSY.FTPdtSysTable = 'TARTRcvDepositHD'
            ";
        }
        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // นำข้อมูลจาก Browse ลง DTTemp // เอกสารใบเคลมสินค้า
    public function FSoMDPSCallRefIntDocInsertDTToTempCLM($paData){
        $tDPSDocNo      = $paData['tDPSDocNo'];
        $tDPSFrmBchCode = $paData['tDPSFrmBchCode'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tDPSFrmBchCode);
        $this->db->where('FTXthDocNo',$tDPSDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $nFlag          = $paData['nFlag'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

        // Check Flag
        if($nFlag == '1'){
            $tSQL   = "
                INSERT INTO TCNTDocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                    FCXtdQty,FCXtdQtyAll,
                    FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                    FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet
                )
                SELECT 
                    '".$tDPSFrmBchCode."'   AS FTBchCode,
                    '".$tDPSDocNo."'        AS FTXthDocNo,
                    DT.FNPcdSeqNo 			AS FNXsdSeqNo,
                    'TARTRcvDepositHD' 	    AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTPcdPdtName		    AS FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCPcdFactor			AS FCXsdFactor,
                    DT.FTPcdBarCode		    AS FTXsdBarCode,
                    DT.FCPcdQty				AS FCXsdQty,
                    DT.FCPcdQtyAll			AS FCXsdQtyAll,
                    '' 						AS FTXsdRmk,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')  AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                    PDT.FTPdtStaVatBuy 	    AS FTXsdVatType,
                    PDT.FTVatCode			AS FTVatCode,
                    VAT.FCVatRate			AS FCXsdVatRate,
                    PRI.FCPgdPriceRet		AS FCXsdSetPrice,
                    PRI.FCPgdPriceRet		AS FCXtdSalePrice,
                    NULL AS FCXsdVat,
                NULL AS FCXsdVatable,
                NULL AS FCXsdNet
                FROM TCNTPdtClaimDT DT WITH(NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                INNER JOIN (
                    SELECT A.* FROM(
                        SELECT  
                            ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                            FTVatCode , 
                            FCVatRate 
                    FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                    ) AS A WHERE A.RowNumber = 1 
                ) VAT ON PDT.FTVatCode = VAT.FTVatCode
                LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode 
                WHERE  DT.FTBchCode = '".$tRefIntBchCode."' AND  DT.FTPchDocNo ='".$tRefIntDocNo."' AND DT.FNPcdSeqNo IN $aSeqNo
            ";
        }else{
            $tSQL   = "
                INSERT INTO TCNTDocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                    FCXtdQty,FCXtdQtyAll,
                    FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSetPrice,
                    FCXtdSalePrice,FCXtdVat,FCXtdVatable,FCXtdNet,FTTmpStatus
                )
                SELECT
                    '".$tDPSFrmBchCode."'   AS FTBchCode,
                    '".$tDPSDocNo."'        AS FTXthDocNo,
                    '1',
                    'TARTRcvDepositHD' AS FTXthDocKey,
                    TSYL.FTPdtCode,
                    TSYL.FTPdtName,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    '1',
                    '1',
                    '' AS FTXsdRmk,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')    AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                    '1', NULL,
                    7,
                    NULL 	AS FCXshTotal,
                    NULL	AS FCXshTotal,
                    NULL	AS FCXshVat,
                    NULL	AS FCXshVatable,
                    NULL	AS FCXshTotal,
                    '1'
                FROM TCNTPdtClaimDT DT WITH (NOLOCK),TSysPdt TSY WITH (NOLOCK)
                LEFT JOIN TSysPdt_L TSYL WITH (NOLOCK) ON TSY.FTPdtCode = TSYL.FTPdtCode
                WHERE DT.FTBchCode = '".$tDPSFrmBchCode."' AND DT.FTPchDocNo = '".$tDPSDocNo."' AND TSY.FTPdtSysTable = 'TARTRcvDepositHD'
            ";
        }
        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }







    // Functionality : Delete Tmp Product When Add Page
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    //เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม where session
    public function FSaMDPSDeletePDTInTmp($paParams){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        return $aStatus;
    }

    // อัพเดทสถานะของใบ Ref
    public function FSaMDPSUpdateRefInStaRef($ptRefInDocNo, $pnStaRef, $ptTable, $ptDocWhere){
        $this->db->set($ptDocWhere['tStaTable'],$pnStaRef);
        $this->db->where($ptDocWhere['tDocWhere'],$ptRefInDocNo);
        $this->db->update($ptTable);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    // ลบข้อมูล Ref ใบเครมสินค้า ในตาราง TCNTPdtClaimHDDocRef
    public function FSaMDPSDelRefInStaRefCLMOld($aDataRefClm){
        // Delete Data Old ใบเครมสินค้า ใบเก่าเอกสารเดิมที่เคยลงไว้
        $this->db->where('FTAgnCode',$aDataRefClm['FTAgnCode']);
        $this->db->where('FTBchCode',$aDataRefClm['FTBchCode']);
        $this->db->where('FTPchDocNo',$aDataRefClm['FTPchDocNoOld']);
        $this->db->where('FTXshRefType',$aDataRefClm['FTXshRefType']);
        $this->db->where('FTXshRefDocNo',$aDataRefClm['FTXshRefDocNo']);
        $this->db->delete('TCNTPdtClaimHDDocRef');
    }

    // อัพเดทสถานะของใบ Ref ใบเครมสินค้า ในตาราง TCNTPdtClaimHDDocRef
    public function FSaMDPSUpdateRefInStaRefCLM($aDataRefClm){
        // Delete Data Old ใบเครมสินค้า
        $this->db->where('FTAgnCode',$aDataRefClm['FTAgnCode']);
        $this->db->where('FTBchCode',$aDataRefClm['FTBchCode']);
        $this->db->where('FTPchDocNo',$aDataRefClm['FTPchDocNo']);
        $this->db->where('FTXshRefType',$aDataRefClm['FTXshRefType']);
        $this->db->where('FTXshRefDocNo',$aDataRefClm['FTXshRefDocNo']);
        $this->db->delete('TCNTPdtClaimHDDocRef');

        // Insert Data เอกสารอ้างอิงใบซื้อสินค้า
        $this->db->insert('TCNTPdtClaimHDDocRef',array(
            'FTAgnCode'         => $aDataRefClm['FTAgnCode'],
            'FTBchCode'         => $aDataRefClm['FTBchCode'],
            'FTPchDocNo'        => $aDataRefClm['FTPchDocNo'],
            'FTXshRefType'      => $aDataRefClm['FTXshRefType'],
            'FTXshRefDocNo'     => $aDataRefClm['FTXshRefDocNo'],
            'FTXshRefKey'       => $aDataRefClm['FTXshRefKey'],
            'FDXshRefDocDate'   => $aDataRefClm['FDXshRefDocDate'],
        ));
    }



    //เครียร์สถานะ Ref 2 เอกสารการสั่งขาย / เอกสารใบเสนอราคา
    public function FSaMDPSClearRefDoc($ptRefInDocNo, $pnStaRef,$ptDataDocNo){

        $this->db->set('FNXshStaRef',$pnStaRef);
        $this->db->where('FTXshDocNo',$ptRefInDocNo);
        $this->db->update('TARTSqHD');

        $this->db->set('FNXshStaRef',$pnStaRef);
        $this->db->where('FTXshDocNo',$ptRefInDocNo);
        $this->db->update('TARTSoHD');

        // Delete Doc Ref ใบเครม
        $this->db->where('FTPchDocNo',$ptRefInDocNo);
        $this->db->where('FTXshRefDocNo',$ptDataDocNo);
        $this->db->where('FTXshRefType',2);
        $this->db->where('FTXshRefKey','DPS');
        $this->db->delete('TCNTPdtClaimHDDocRef');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    // Functionality : Cal Vat DT
    // Parameters : function parameters
    // Creator : 09/07/2021 Off
    // Last Modified : -
    // Return : -
    // Return Type : None
    public function FSaMDPSCalVatLastDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tBchCode       = $paData['tBchCode'];
        $tSessionID     = $paData['tSessionID'];
        $tDataVatInOrEx = $paData['tDataVatInOrEx'];

        $cSumFCXtdVat = " SELECT
            SUM (ISNULL(DOCTMP.FCXtdVat, 0)) AS FCXtdVat
            FROM
                TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE
                1 = 1
            AND DOCTMP.FTSessionID = '$tSessionID'
            AND DOCTMP.FTXthDocKey = 'TARTRcvDepositHD'
            AND DOCTMP.FTXthDocNo = '$tDocNo'
            AND DOCTMP.FCXtdVatRate > 0  ";

        
        $tSql ="UPDATE TCNTDocDTTmp
                SET FCXtdVat = (
                    ($cSumFCXtdVat) - (
                        SELECT
                            SUM (DTTMP.FCXtdVat) AS FCXtdVat
                        FROM
                            TCNTDocDTTmp DTTMP
                        WHERE
                            DTTMP.FTSessionID = '$tSessionID'
                        AND DTTMP.FTXthDocNo = '$tDocNo'
                        AND DTTMP.FTXtdVatType = 1
                        AND DTTMP.FNXtdSeqNo != (
                            SELECT
                                TOP 1 SUBDTTMP.FNXtdSeqNo
                            FROM
                                TCNTDocDTTmp SUBDTTMP
                            WHERE
                                SUBDTTMP.FTSessionID = '$tSessionID'
                            AND SUBDTTMP.FTXthDocNo = '$tDocNo'
                            AND SUBDTTMP.FTXtdVatType = 1
                            ORDER BY
                                SUBDTTMP.FNXtdSeqNo DESC
                        )
                    )
                ),
                FCXtdVatable = (
                    CASE
                        WHEN $tDataVatInOrEx  = 1 
                        THEN FCXtdNet - (
                            ($cSumFCXtdVat) - (
                                SELECT
                                    SUM (DTTMP.FCXtdVat) AS FCXtdVat
                                FROM
                                    TCNTDocDTTmp DTTMP
                                WHERE
                                    DTTMP.FTSessionID = '$tSessionID'
                                AND DTTMP.FTXthDocNo = '$tDocNo'
                                AND DTTMP.FTXtdVatType = 1
                                AND DTTMP.FNXtdSeqNo != (
                                    SELECT
                                        TOP 1 SUBDTTMP.FNXtdSeqNo
                                    FROM
                                        TCNTDocDTTmp SUBDTTMP
                                    WHERE
                                        SUBDTTMP.FTSessionID = '$tSessionID'
                                    AND SUBDTTMP.FTXthDocNo = '$tDocNo'
                                    AND SUBDTTMP.FTXtdVatType = 1
                                    ORDER BY
                                        SUBDTTMP.FNXtdSeqNo DESC
                                )
                            )
                        )
                        WHEN $tDataVatInOrEx  = 2 
                        THEN FCXtdNetAfHD
                    ELSE 0 END 
                )
                WHERE
                    FTSessionID = '$tSessionID'
                AND FTXthDocNo = '$tDocNo'
                AND FNXtdSeqNo = (
                    SELECT
                        TOP 1 FNXtdSeqNo
                    FROM
                        TCNTDocDTTmp WHDTTMP
                    WHERE
                        WHDTTMP.FTSessionID = '$tSessionID'
                    AND WHDTTMP.FTXthDocNo = '$tDocNo'
                    AND WHDTTMP.FTXtdVatType = 1
                    ORDER BY
                        WHDTTMP.FNXtdSeqNo DESC
                )";

        $nRSCounDT =  $this->db->where('FTSessionID',$tSessionID)->where('FTXthDocNo',$tDocNo)->where('FTXtdVatType','1')->get('TCNTDocDTTmp')->num_rows();
        
        if($nRSCounDT>1){
            $this->db->query($tSql);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'cannot Delete Item.',
                );
            }
        }else{
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }
        return $aStatus;
    }

    

    // ค้นหาข้อมูลรายละเอียดข้อมูลลูกค้า
    public function FSoMDPSFindCstBehideRefIn($paDataWhereCst){
        $nLngID     = $paDataWhereCst['FNLngID'];
        $tCstCode   = $paDataWhereCst['FTCstCode'];
        $tSQL       = "
            SELECT 
                TCNMCst.FTCstCode,
                TCNMCst_L.FTCstName,
                TCNMCst.FTCstCardID,
                TCNMCst.FTCstTaxNo,
                TCNMCst.FTCstTel,
                TCNMCst.FTCstDiscRet,
                TCNMCst.FTCstStaAlwPosCalSo,
                TCNMCstCard.FTCstCrdNo,
                TCNMCstAddress_L.FTAddV2Desc1,
                TCNMCst.FTCstEmail
            FROM TCNMCst WITH(NOLOCK)
            LEFT JOIN TCNMCst_L 	    WITH(NOLOCK) ON TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '$nLngID'
            LEFT JOIN TCNMCstCard       WITH(NOLOCK) ON TCNMCst.FTCstCode = TCNMCstCard.FTCstCode
            LEFT JOIN TCNMCstAddress_L  WITH(NOLOCK) ON TCNMCstAddress_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCstAddress_L.FNLngID = '$nLngID'
            WHERE TCNMCst.FTCstStaActive = '1' AND TCNMCst.FTCstCode = '$tCstCode'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->row_array();
            $aResult    = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'Success Finde Customer',
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'ไม่พบข้อมูลลูกค้า',
            );
        }
        return $aResult;
    }







}