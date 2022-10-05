<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debitnote_modal extends CI_Model {
    
    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMDBNGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];
        // TPSTTaxHD
        $tSQL   =   "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM (
                    SELECT DISTINCT
                        DBN.FTBchCode,
                        BCHL.FTBchName,
                        DBN.FTXshDocNo,
                        CONVERT(CHAR(10),DBN.FDXshDocDate,103) AS FDXshDocDate,
                        CONVERT(CHAR(5), DBN.FDXshDocDate,108) AS FTXshDocTime,
                        DBDR.FTXshRefDocNo,
                        CONVERT(CHAR(10),DBDR.FDXshRefDocDate,103) AS FDXshRefDocDate,
                        CONVERT(CHAR(5), DBDR.FDXshRefDocDate,108) AS FDXshRefIntTime,
                        DBN.FTXshStaDoc,
                        DBN.FTXshStaApv,
                        USR.FTUsrName AS FTCreateBy,
                        DBN.FDCreateOn,
                        DBN.FTCstCode,
                        CST.FTCstName
                    FROM TPSTTaxHD DBN WITH(NOLOCK)
                    LEFT JOIN TPSTTaxHDDocRef DBDR WITH(NOLOCK) ON DBN.FTBchCode = DBDR.FTBchCode AND DBN.FTXshDocNo = DBDR.FTXshDocNo
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON DBN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = '$nLngID'
                    LEFT JOIN TCNMUser_L USR	WITH (NOLOCK) ON DBN.FTCreateBy = USR.FTUsrCode AND BCHL.FNLngID = '$nLngID'
                    LEFT JOIN TCNMCst_L CST	WITH (NOLOCK) ON DBN.FTCstCode = CST.FTCstCode AND CST.FNLngID = '$nLngID'
                    WHERE DBN.FNXshDocType = '10'
        ";
        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode   = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL   .= "
                AND DBN.FTBchCode IN ($tBchCode)
            ";
        }
        // ค้นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL   .= "
                AND (
                    (DBN.FTXshDocNo LIKE '%$tSearchList%') 
                    OR (BCHL.FTBchName LIKE '%$tSearchList%') 
                    OR (CONVERT(CHAR(10),DBN.FDXshDocDate,103) LIKE '%$tSearchList%') 
                    OR (CST.FTCstName LIKE '%$tSearchList%')
                )
            ";
        }
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL   .= " AND ((DBN.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (DBN.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL   .= " AND ((DBN.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DBN.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DBN.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DBN.FTXshStaApv,'') = '' AND DBN.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaDoc'";
            }
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaApprove' OR DBN.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaApprove'";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DBN.FNXshStaDocAct = 1";
            } else {
            $tSQL .= " AND DBN.FNXshStaDocAct = 0";
            }
        }
        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $oQuery  = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMDBNCountPageDocListAll($paDataCondition);
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

    // Paginations
    public function FSnMDBNCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSQL   =   "
            SELECT COUNT (DBN.FTXshDocNo) AS counts
            FROM TPSTTaxHD DBN WITH (NOLOCK)
            LEFT JOIN TPSTTaxHDDocRef DBDR WITH(NOLOCK) ON DBN.FTBchCode = DBDR.FTBchCode AND DBN.FTXshDocNo = DBDR.FTXshDocNo
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON DBN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L USR WITH (NOLOCK) ON DBN.FTCreateBy = USR.FTUsrCode AND BCHL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCst_L CST	WITH (NOLOCK) ON DBN.FTCstCode = CST.FTCstCode AND CST.FNLngID = '$nLngID'
            WHERE DBN.FNXshDocType = '10'
        ";
        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND DBN.FTBchCode = '$tUserLoginBchCode' ";
        }
        // ค้นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL   .= "
                AND (
                    (DBN.FTXshDocNo LIKE '%$tSearchList%') 
                    OR (BCHL.FTBchName LIKE '%$tSearchList%') 
                    OR (CONVERT(CHAR(10),DBN.FDXshDocDate,103) LIKE '%$tSearchList%')
                    OR (CST.FTCstName LIKE '%$tSearchList%')
                )
            ";
        }
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL   .= " AND ((DBN.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (DBN.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL   .= " AND ((DBN.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DBN.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DBN.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DBN.FTXshStaApv,'') = '' AND DBN.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaDoc'";
            }
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaApprove' OR DBN.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND DBN.FTXshStaApv = '$tSearchStaApprove'";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DBN.FNXshStaDocAct = 1";
            } else {
            $tSQL .= " AND DBN.FNXshStaDocAct = 0";
            }
        }
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

    // ดึงข้อมูลตารางเอกสาร HD
    public function FSaMDBNGetDataHD($ptAgnCode,$ptBchCode,$ptDocNo){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "
            SELECT
                HD.FTBchCode,BCHL.FTBchName,
                HD.FTXshDocNo,
                HD.FTShpCode,SHPL.FTShpName,
                HD.FNXshDocType,
                HD.FDXshDocDate,
                CONVERT(VARCHAR(10),HD.FDXshDocDate,108) AS FTXshDocTime,
                HD.FTXshCshOrCrd,
                HD.FTXshVATInOrEx,
                HD.FTWahCode,WAHL.FTWahName,
                HD.FTPosCode,POSL.FTPosName,
                HD.FTShfCode,
                HD.FNSdtSeqNo,
                HD.FTUsrCode,USRL.FTUsrName,
                HD.FTSpnCode,
                HD.FTXshApvCode,UAPL.FTUsrName AS FTXshApvName,
                HD.FTCstCode,CSTL.FTCstName,
                HD.FTXshDocVatFull,
                HD.FTXshRefExt,
                HD.FDXshRefExtDate,
                HD.FTXshRefAE,
                HD.FNXshDocPrint,
                HD.FTRteCode,
                RTEL.FTRteName,
                HD.FCXshRteFac,
                HD.FCXshTotal,
                HD.FCXshTotalNV,
                HD.FCXshTotalNoDis,
                HD.FCXshTotalB4DisChgV,
                HD.FCXshTotalB4DisChgNV,
                HD.FTXshDisChgTxt,
                HD.FCXshDis,
                HD.FCXshChg,
                HD.FCXshTotalAfDisChgV,
                HD.FCXshTotalAfDisChgNV,
                HD.FCXshRefAEAmt,
                HD.FCXshAmtV,
                HD.FCXshAmtNV,
                HD.FCXshVat,
                HD.FCXshVatable,
                HD.FTXshWpCode,
                HD.FCXshWpTax,
                HD.FCXshGrand,
                HD.FCXshPaid,
                HD.FCXshLeft,
                HD.FTXshRmk,
                HD.FTXshStaRefund,
                HD.FTXshStaDoc,
                HD.FTXshStaApv,
                HD.FTXshStaPrcStk,
                HD.FTXshStaPaid,
                HD.FNXshStaDocAct,
                HD.FNXshStaRef,
                HD.FTXshRefTax,
                HD.FDLastUpdOn,
                HD.FTLastUpdBy,
                UPDL.FTUsrName AS FTLastUpdByName,
                HD.FDCreateOn,
                HD.FTCreateBy,
                UCRL.FTUsrName AS FTCreateByName,
                HD.FTXshStaETax,
                HD.FTRsnCode,
                HD.FCXshLeftCN,
                HD.FCXshLeftDN
            FROM TPSTTaxHD HD WITH(NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL 	WITH(NOLOCK) ON HD.FTBchCode 		= BCHL.FTBchCode AND BCHL.FNLngID = '$nLngID'
            LEFT JOIN TCNMShop_L SHPL 		WITH(NOLOCK) ON HD.FTBchCode 		= SHPL.FTBchCode AND HD.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = '$nLngID'
            LEFT JOIN TCNMWaHouse_L WAHL 	WITH(NOLOCK) ON HD.FTBchCode 		= WAHL.FTBchCode AND HD.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = '$nLngID'
            LEFT JOIN TCNMPos_L POSL 		WITH(NOLOCK) ON HD.FTBchCode 		= POSL.FTBchCode AND HD.FTPosCode = POSL.FTPosCode AND POSL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L USRL 		WITH(NOLOCK) ON HD.FTUsrCode 		= USRL.FTUsrCode AND USRL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L UAPL 		WITH(NOLOCK) ON HD.FTXshApvCode 	= UAPL.FTUsrCode AND USRL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCst_L CSTL 		WITH(NOLOCK) ON HD.FTCstCode 		= CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
            LEFT JOIN TFNMRate_L RTEL 		WITH(NOLOCK) ON HD.FTRteCode 		= RTEL.FTRteCode AND RTEL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L UPDL 		WITH(NOLOCK) ON HD.FTLastUpdBy 	    = UPDL.FTUsrCode AND UPDL.FNLngID = '$nLngID'
            LEFT JOIN TCNMUser_L UCRL		WITH(NOLOCK) ON HD.FTCreateBy 	    = UCRL.FTUsrCode AND UCRL.FNLngID = '$nLngID'
            WHERE HD.FNXshDocType = '10'
        ";
        // Check Agency Code
        if(isset($ptAgnCode) && !empty($ptAgnCode)){
            $tSQL   .= " AND HD.FTAgnCode = '$ptAgnCode'";
        }
        // Check Branch Code
        if(isset($ptBchCode) && !empty($ptBchCode)){
            $tSQL   .= " AND HD.FTBchCode = '$ptBchCode'";
        }
        // Check Document Number
        if(isset($ptDocNo) && !empty($ptDocNo)){
            $tSQL   .= " AND HD.FTXshDocNo = '$ptDocNo'";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList      = $oQuery->row_array();
            $aReturnData    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'Success'
            );
        }else{
            $aReturnData    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aReturnData;
    }

    // ดึงข้อมูลตารางเอกสาร HD Doc Ref
    public function FSaMDBNGetDataHDDocRef($ptAgnCode,$ptBchCode,$ptDocNo){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "
            SELECT 
                DOCREF.FTBchCode,
                BCHL.FTBchName,
                DOCREF.FTXshDocNo,
                DOCREF.FTXshRefDocNo,
                DOCREF.FTXshRefType,
                DOCREF.FTXshRefKey,
                DOCREF.FDXshRefDocDate
            FROM TPSTTaxHDDocRef DOCREF WITH(NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON DOCREF.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = '$nLngID'
            WHERE DOCREF.FDXshRefDocDate <> ''
        ";
        // Check Branch Code
        if(isset($ptBchCode) && !empty($ptBchCode)){
            $tSQL   .= " AND (DOCREF.FTBchCode = '$ptBchCode')";
        }
        // Check Document No
        if(isset($ptDocNo) && !empty($ptDocNo)){
            $tSQL   .= " AND (DOCREF.FTXshDocNo = '$ptDocNo')";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList      = $oQuery->result_array();
            $aReturnData    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'Success'
            );
        }else{
            $aReturnData    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aReturnData;
    }

    // ดึงข้อมูลตารางเอกสาร HD Doc CST
    public function FSaMDBNGetDataHDCst($ptAgnCode,$ptBchCode,$ptDocNo){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "
            SELECT
                HDCST.FTBchCode,
                HDCST.FTXshDocNo,
                HDCST.FTXshCardID,
                HDCST.FTXshCstTel,
                HDCST.FTXshCstName,
                HDCST.FTXshCardNo,
                HDCST.FNXshCrTerm,
                HDCST.FDXshDueDate,
                HDCST.FDXshBillDue,
                HDCST.FTXshCtrName,
                HDCST.FDXshTnfDate,
                HDCST.FTXshRefTnfID,
                HDCST.FNXshAddrShip,
                HDCST.FTXshAddrTax,
                HDCST.FTXshCourier,
                HDCST.FTXshCourseID,
                HDCST.FTXshCstRef,
                HDCST.FTXshCstEmail
            FROM TPSTTaxHDCst HDCST WITH(NOLOCK)
            WHERE HDCST.FTBchCode = '$ptBchCode' AND HDCST.FTXshDocNo = '$ptDocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList      = $oQuery->row_array();
            $aReturnData    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'Success'
            );
        }else{
            $aReturnData    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aReturnData;
    }

    // ดึงข้อมูลตารางเอกสาร DT
    public function FSaMDBNGetDataDT($ptAgnCode,$ptBchCode,$ptDocNo){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "
            SELECT
                DT.FTBchCode,
                DT.FTXshDocNo,
                DT.FNXsdSeqNo,
                DT.FTPdtCode,
                DT.FTXsdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXsdFactor,
                DT.FTXsdBarCode,
                DT.FTSrnCode,
                DT.FTXsdVatType,
                DT.FTVatCode,
                DT.FCXsdVatRate,
                DT.FTXsdSaleType,
                DT.FCXsdSalePrice,
                DT.FCXsdQty,
                DT.FCXsdQtyAll,
                DT.FCXsdSetPrice,
                DT.FCXsdAmtB4DisChg,
                DT.FTXsdDisChgTxt,
                DT.FCXsdDis,
                DT.FCXsdChg,
                DT.FCXsdNet,
                DT.FCXsdNetAfHD,
                DT.FCXsdVat,
                DT.FCXsdVatable,
                DT.FCXsdWhtAmt,
                DT.FTXsdWhtCode,
                DT.FCXsdWhtRate,
                DT.FCXsdCostIn,
                DT.FCXsdCostEx,
                DT.FTXsdStaPdt,
                DT.FCXsdQtyLef,
                DT.FCXsdQtyRfn,
                DT.FTXsdStaPrcStk,
                DT.FTXsdStaAlwDis,
                DT.FNXsdPdtLevel,
                DT.FTXsdPdtParent,
                DT.FCXsdQtySet,
                DT.FTPdtStaSet,
                DT.FTXsdRmk,
                DT.FTPplCode
            FROM TPSTTaxDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = '$ptBchCode' AND DT.FTXshDocNo = '$ptDocNo'
            ORDER BY DT.FNXsdSeqNo
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList      = $oQuery->result_array();
            $aReturnData    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'Success'
            );
        }else{
            $aReturnData    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        return $aReturnData;
    }

    // ดึงข้อมูลตารางเอกสาร DT SUM Vat
    public function FSaMDBNGetDataSumVat($ptAgnCode,$ptBchCode,$ptDocNo){
        $tSQL   = "
            SELECT
                SUM(DTVAT.FCXsdVat) AS FCXsdVat
            FROM TPSTTaxDT DTVAT WITH(NOLOCK)
            WHERE DTVAT.FTBchCode = '$ptBchCode' AND DTVAT.FTXshDocNo = '$ptDocNo'
            GROUP BY DTVAT.FCXsdVatRate
        ";
        $oQuery = $this->db->query($tSQL);
        $oDataList      = $oQuery->result_array();
        $aReturnData    = array(
            'raItems'   => $oDataList,
            'rtCode'    => '1',
            'rtDesc'    => 'Success'
        );
        return $aReturnData;
    }

    // ดึงข้อมูลตารางเอกสาร DT Vat rate
    public function FSaMDBNGetDataVat($ptAgnCode,$ptBchCode,$ptDocNo){
        $tSQL   = "
            SELECT
                DTVAT.FCXsdVatRate,
                SUM(DTVAT.FCXsdVat) AS FCXsdVat	
            FROM TPSTTaxDT DTVAT WITH(NOLOCK)
            WHERE DTVAT.FTBchCode = '$ptBchCode' AND DTVAT.FTXshDocNo = '$ptDocNo'
            GROUP BY DTVAT.FCXsdVatRate
        ";
        $oQuery = $this->db->query($tSQL);
        $oDataList      = $oQuery->result_array();
        $aReturnData    = array(
            'raItems'   => $oDataList,
            'rtCode'    => '1',
            'rtDesc'    => 'Success'
        );
        return $aReturnData;
    }

    // Update ข้อมูลตาราง HD
    public function FSaMDNBQaAddUpdateHD($paUpdateDoc,$paWhereDoc){
        $this->db->set('FTXshRmk',$paUpdateDoc['FTXshRmk']);
        $this->db->set('FNXshStaDocAct',$paUpdateDoc['FNXshStaDocAct']);
        $this->db->where_in('FTBchCode',$paWhereDoc['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$paWhereDoc['FTXshDocNo']);
        $this->db->update('TPSTTaxHD');
        $aReturnData = array(
            'nStaEvent' => '1',
            'tStaMessg' => 'Update HD success'
        );
        return $aReturnData;
    }






}