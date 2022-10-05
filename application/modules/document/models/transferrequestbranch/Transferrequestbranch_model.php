<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transferrequestbranch_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMTRBGetDataTableList($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "  

            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                HD.FTBchCode,
                HD.FTAgnCode,
                BCHL.FTBchName,
                HD.FTXthDocNo,
                CONVERT(CHAR(10),HD.FDXthDocDate,103) AS FDXthDocDate,
                CONVERT(CHAR(5), HD.FDXthDocDate,108) AS FTXshDocTime,
                HD.FTXthStaDoc,
                HD.FTXthStaApv,
                HD.FNXthStaRef,
                HD.FTCreateBy,
                HD.FDCreateOn,
                HD.FNXthStaDocAct,
                HDREF.FTXshRefDocNo AS FTXthRefInt,
                CONVERT(CHAR(10),HDREF.FDXshRefDocDate,103) AS FDXthRefIntDate,
                USRL.FTUsrName      AS FTCreateByName,
                HD.FTXthApvCode,
                USRLAPV.FTUsrName   AS FTXshApvName,
                BCHLFrm.FTBchName   AS BchNameFrm ,
                BCHLTo.FTBchName    AS BchNameTo
            FROM TCNTPdtReqBchHD    HD          WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L  BCHL        WITH (NOLOCK) ON HD.FTBchCode     = BCHL.FTBchCode      AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L  BCHLFrm     WITH (NOLOCK) ON HD.FTXthBchFrm   = BCHLFrm.FTBchCode   AND BCHLFrm.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L  BCHLTo      WITH (NOLOCK) ON HD.FTXthBchTo    = BCHLTo.FTBchCode    AND BCHLTo.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L    USRL        WITH (NOLOCK) ON HD.FTUsrCode     = USRL.FTUsrCode      AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L    USRLAPV     WITH (NOLOCK) ON HD.FTXthApvCode  = USRLAPV.FTUsrCode   AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtReqBchHDDocRef    HDREF WITH (NOLOCK) ON HDREF.FTXshDocNo  = HD.FTXthDocNo AND HDREF.FTBchCode = HD.FTBchCode AND HDREF.FTXshRefType = 1 AND (HDREF.FTXshRefKey = 'TRB' OR HDREF.FTXshRefKey = 'PRHQ')
            WHERE HD.FDCreateOn <> ''
                                
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND ( HD.FTXthBchFrm IN ($tBchCode) OR HD.FTBchCode IN ($tBchCode) OR HD.FTXthBchTo IN ($tBchCode) ) ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND HD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((HD.FTXthDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXthDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " OR (HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((HD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND HD.FTXthStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(HD.FTXthStaApv,'') = '' AND HD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND HD.FTXthStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND HD.FTXthStaApv = '$tSearchStaApprove' OR HD.FTXthStaApv = '' ";
            }else{
                $tSQL .= " AND HD.FTXthStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXthStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXthStaDocAct = 0";
            }
        }

        $tSQL   .=  " ORDER BY FDCreateOn DESC ,FTXthDocNo DESC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
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
        unset($nLngID);
        unset($aDatSessionUserLogIn);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom,$tSearchBchCodeTo);
        unset($tSearchDocDateFrom,$tSearchDocDateTo);
        unset($tSearchStaDoc,$tSearchStaDocAct);
        unset($tBchCode);
        unset($tSQL);
        unset($oQuery);
        unset($oDataList);
        return $aResult;
    }

    public function FSaMTRBGetDetailUserBranch($paBchCode){
        if(!empty($paBchCode)){
            $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
            $aReulst['item']    = $aReustl;
            $aReulst['code']    = 1;
            $aReulst['msg']     = 'Success !';
        }else{
            $aReulst['code']    = 2;
            $aReulst['msg']     = 'Error !';
        }
        return $aReulst;
    }

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม ใน DTTemp โดย where session
    public function FSaMCENDeletePDTInTmp($paParams){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTTRBDocDTTmp');
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
        unset($tSessionID);
        return $aStatus;
    }

    // Delete Delivery Order Document
    public function FSxMTRBClearDataInDocTemp($paWhereClearTemp){
        $tTRBDocNo      = $paWhereClearTemp['FTXthDocNo'];
        $tTRBDocKey     = $paWhereClearTemp['FTXthDocKey'];
        $tTRBSessionID  = $paWhereClearTemp['FTSessionID'];
        // Query Delete DocTemp
        $tClearDocTemp  =   "   
            DELETE FROM TSVTTRBDocDTTmp
            WHERE TSVTTRBDocDTTmp.FTSessionID <> ''
            AND TSVTTRBDocDTTmp.FTXthDocNo     = '$tTRBDocNo'
            AND TSVTTRBDocDTTmp.FTXthDocKey    = '$tTRBDocKey'
            AND TSVTTRBDocDTTmp.FTSessionID    = '$tTRBSessionID'
        ";
        $this->db->query($tClearDocTemp);
        unset($tTRBDocNo);
        unset($tTRBDocKey);
        unset($tTRBSessionID);
        unset($tClearDocTemp);
    }

    // Functionality : Delete Delivery Order Document
    public function FSxMTRBClearDataInDocTempForImp($paWhereClearTemp){
        $tTRBDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tTRBDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tTRBSessionID   = $paWhereClearTemp['FTSessionID'];
        // Query Delete DocTemp
        $tClearDocTemp  =   "
            DELETE FROM TSVTTRBDocDTTmp
            WHERE TSVTTRBDocDTTmp.FTSessionID <> ''
            AND TSVTTRBDocDTTmp.FTXthDocNo     = '$tTRBDocNo'
            AND TSVTTRBDocDTTmp.FTXthDocKey    = '$tTRBDocKey'
            AND TSVTTRBDocDTTmp.FTSessionID    = '$tTRBSessionID'
            AND TSVTTRBDocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
        unset($tTRBDocNo);
        unset($tTRBDocKey);
        unset($tTRBSessionID);
    }

    // Function: Get ShopCode From User Login
    public function FSaMTRBGetShpCodeForUsrLogin($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tUsrLogin  = $paDataShp['tUsrLogin'];
        $tSQL       = "
            SELECT
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
            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode   = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
            LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
            WHERE UGP.FTUsrCode = ".$this->db->escape($tUsrLogin)." 
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($nLngID);
        unset($tUsrLogin);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }

    // Get Data Config WareHouse TSysConfig
    public function FSaMTRBGetDefOptionConfigWah($paConfigSys){
        $tSysCode       = $paConfigSys['FTSysCode'];
        $nSysSeq        = $paConfigSys['FTSysSeq'];
        $nLngID         = $paConfigSys['FNLngID'];
        $aDataReturn    = array();
        $tSQLUsrVal = " 
            SELECT
                SYSCON.FTSysStaUsrValue AS FTSysWahCode,
                WAHL.FTWahName          AS FTSysWahName
            FROM TSysConfig SYSCON          WITH(NOLOCK)
            LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK) ON SYSCON.FTSysStaUsrValue = WAH.FTWahCode AND WAH.FTWahStaType = 1
            LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK) ON WAH.FTWahCode   = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
            WHERE SYSCON.FTSysCode <> ''
            AND SYSCON.FTSysCode    = '$tSysCode'
            AND SYSCON.FTSysSeq     = $nSysSeq
        ";
        $oQuery1    = $this->db->query($tSQLUsrVal);
        if($oQuery1->num_rows() > 0){
            $aDataReturn    = $oQuery1->row_array();
        }else{
            $tSQLUsrDef =   "
                SELECT
                    SYSCON.FTSysStaDefValue AS FTSysWahCode,
                    WAHL.FTWahName          AS FTSysWahName
                FROM TSysConfig SYSCON          WITH(NOLOCK)
                LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaDefValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
                WHERE SYSCON.FTSysCode <> ''
                AND SYSCON.FTSysCode    = '$tSysCode'
                AND SYSCON.FTSysSeq     = $nSysSeq
            ";
            $oQuery2    = $this->db->query($tSQLUsrDef);
            if($oQuery2->num_rows() > 0){
                $aDataReturn    = $oQuery2->row_array();
            }
        }
        unset($tSysCode);
        unset($nSysSeq);
        unset($nLngID);
        unset($oQuery1);
        unset($oQuery2);
        return $aDataReturn;
    }

    // Function : Get Data In Doc DT Temp
    public function FSaMTRBGetDocDTTempListPage($paDataWhere){
        $tTRBDocNo              = $paDataWhere['FTXthDocNo'];
        $tTRBDocKey             = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable     = $paDataWhere['tSearchPdtAdvTable'];
        $tTRBSesSessionID       = $this->session->userdata('tSesSessionID');
        $tSQL       = " 
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
                DOCTMP.FCXtdAmtB4DisChg,
                DOCTMP.FTXtdDisChgTxt,
                DOCTMP.FCXtdNet,
                DOCTMP.FCXtdNetAfHD,
                DOCTMP.FTXtdStaAlwDis,
                DOCTMP.FTTmpRemark,
                DOCTMP.FCXtdVatRate,
                DOCTMP.FTXtdVatType,
                DOCTMP.FTSrnCode,
                DOCTMP.FDLastUpdOn,
                DOCTMP.FDCreateOn,
                DOCTMP.FTLastUpdBy,
                DOCTMP.FTCreateBy
            FROM TSVTTRBDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID <> ''
            AND DOCTMP.FTXthDocKey  = '$tTRBDocKey'
            AND DOCTMP.FTSessionID  = '$tTRBSesSessionID'
        ";

        if(isset($tTRBDocNo) && !empty($tTRBDocNo)){
            $tSQL   .=  " AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tTRBDocNo' ";
        }

        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "
                AND (
                    DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                )
            ";
        }

        $tSQL   .= " ORDER BY FNXtdSeqNo ASC"; 

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataList      = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rnCurrentPage' => $paDataWhere['nPage'],
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

    //Get Data Pdt
    public function FSaMTRBGetDataPdt($paDataPdtParams){
        $tPdtCode   = $paDataPdtParams['tPdtCode'];
        $FTPunCode  = $paDataPdtParams['tPunCode'];
        $FTBarCode  = $paDataPdtParams['tBarCode'];
        $nLngID     = $paDataPdtParams['nLngID'];
        $tSQL       = " 
            SELECT
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
            WHERE PDT.FDCreateOn <> '' 
        ";
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
    public function FSaMTRBInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tTRBOptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "
                SELECT
                    FNXtdSeqNo,
                    FCXtdQty
                FROM TSVTTRBDocDTTmp
                WHERE FTSessionID <> ''
                AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                ORDER BY FNXtdSeqNo
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "
                    UPDATE TSVTTRBDocDTTmp
                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                    WHERE FTSessionID <> ''
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
                    'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                    'FTVatCode'         => $paDataPdtParams['nVatCode'],
                    'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                    'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                    'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                    'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                    'FTLastUpdBy'       => $paDataPdtParams['tTRBUsrCode'],
                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                    'FTCreateBy'        => $paDataPdtParams['tTRBUsrCode'],
                );
                $this->db->insert('TSVTTRBDocDTTmp',$aDataInsert);
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
                'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                'FTVatCode'         => $paDataPdtParams['nVatCode'],
                'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tTRBUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tTRBUsrCode'],
            );
            $this->db->insert('TSVTTRBDocDTTmp',$aDataInsert);
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
        unset($paDataPdtMaster,$paDataPdtParams);
        unset($tSQL);
        unset($oQuery);
        unset($aResult);
        unset($aDataInsert);
        return $aStatus;
    }

    //Delete Product Single Item In Doc DT Temp
    public function FSnMTRBDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where('FTXthDocNo',$paDataWhere['tTRBDocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TSVTTRBDocDTTmp');
        unset($paDataWhere);
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMTRBDelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tTRBDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TSVTTRBDocDTTmp');
        unset($paDataWhere);
        return ;
    }

    // Update Document DT Temp by Seq
    public function FSaMTRBUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where('FTSessionID',$paDataWhere['tTRBSessionID']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nTRBSeqNo']);
        if ($paDataWhere['tTRBDocNo'] != '' && $paDataWhere['tTRBBchCode'] != '') {
            $this->db->where('FTXthDocNo',$paDataWhere['tTRBDocNo']);
            $this->db->where('FTBchCode',$paDataWhere['tTRBBchCode']);
        }
        $this->db->update('TSVTTRBDocDTTmp', $paDataUpdateDT);
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
        unset($paDataUpdateDT,$paDataWhere);
        return $aStatus;
    }

    // Function : Count Check Data Product In Doc DT Temp Before Save
    public function FSnMTRBChkPdtInDocDTTemp($paDataWhere){
        $tTRBDocNo      = $paDataWhere['FTXthDocNo'];
        $tTRBDocKey     = $paDataWhere['FTXthDocKey'];
        $tTRBSessionID  = $paDataWhere['FTSessionID'];
        $tSQL           = " 
            SELECT
                COUNT(FNXtdSeqNo) AS nCountPdt
            FROM TSVTTRBDocDTTmp DocDT
            WHERE DocDT.FTSessionID <> ''
            AND DocDT.FTXthDocKey   = '$tTRBDocKey'
            AND DocDT.FTSessionID   = '$tTRBSessionID' ";
        if(isset($tTRBDocNo) && !empty($tTRBDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'')  = '$tTRBDocNo' ";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            unset($tTRBDocNo);
            unset($tTRBDocKey);
            unset($tTRBSessionID);
            unset($tSQL);
            unset($oQuery);
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
        
    }

    // Function: Get Data HD List
    public function FSoMTRBCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tTRBRefIntBchCode        = $aAdvanceSearch['tTRBRefIntBchCode'];
        $tTRBRefIntDocNo          = $aAdvanceSearch['tTRBRefIntDocNo'];
        $tTRBRefIntDocDateFrm     = $aAdvanceSearch['tTRBRefIntDocDateFrm'];
        $tTRBRefIntDocDateTo      = $aAdvanceSearch['tTRBRefIntDocDateTo'];
        $tTRBRefIntStaDoc         = $aAdvanceSearch['tTRBRefIntStaDoc'];

        $tSQLMain = "   SELECT
                                HD.FTBchCode,
                                BCHL.FTBchName,
                                HD.FTXphDocNo,
                                CONVERT(CHAR(10),HD.FDXphDocDate,103) AS FDXphDocDate,
                                CONVERT(CHAR(5), HD.FDXphDocDate,108) AS FTXphDocTime,
                                HD.FTXphStaDoc,
                                HD.FTXphStaApv,
                                HD.FNXphStaRef,
                                HD.FTCreateBy,
                                HD.FDCreateOn,
                                HD.FNXphStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                HD.FTXphApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName
                            FROM TCNTPdtReqHqHD     HD              WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL            WITH (NOLOCK) ON HD.FTBchCode           = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                            LEFT JOIN TCNMUser_L    USRL            WITH (NOLOCK) ON HD.FTCreateBy          = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L           WITH (NOLOCK) ON HD.FTBchCode           = WAH_L.FTBchCode   AND HD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            LEFT JOIN TCNTPdtReqBchHDDocRef TRBDOC  WITH (NOLOCK) ON TRBDOC.FTXshRefDocNo   = HD.FTXphDocNo     AND TRBDOC.FTXshRefType = 1
                            WHERE HD.FNXphStaRef != 2 AND HD.FTXphStaDoc = 1 AND HD.FTXphStaApv = 1
                            AND ISNULL(TRBDOC.FTXshRefType, '') = '' ";

        if(isset($tTRBRefIntBchCode) && !empty($tTRBRefIntBchCode)){
            $tSQLMain .= " AND (HD.FTBchCode = '$tTRBRefIntBchCode' OR HD.FTXphBchTo = '$tTRBRefIntBchCode' )";
        }

        if(isset($tTRBRefIntDocNo) && !empty($tTRBRefIntDocNo)){
            $tSQLMain .= " AND (HD.FTXphDocNo LIKE '%$tTRBRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tTRBRefIntDocDateFrm) && !empty($tTRBRefIntDocDateTo)){
            $tSQLMain .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tTRBRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tTRBRefIntDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tTRBRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tTRBRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tTRBRefIntStaDoc) && !empty($tTRBRefIntStaDoc)){
            if ($tTRBRefIntStaDoc == 3) {
                $tSQLMain .= " AND HD.FTXphStaDoc = '$tTRBRefIntStaDoc'";
            } elseif ($tTRBRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(HD.FTXphStaApv,'') = '' AND HD.FTXphStaDoc != '3'";
            } elseif ($tTRBRefIntStaDoc == 1) {
                $tSQLMain .= " AND HD.FTXphStaApv = '$tTRBRefIntStaDoc'";
            }
        }

        $tSQL   =  "SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                    (  $tSQLMain
                    ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
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

    // Functionality: Get Data Purchase Order HD List
    public function FSoMTRBCallRefIntDocDTDataTable($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tTRBcNo    =  $paData['tDocNo'];
        $tSQL       = "
            SELECT 
                DT.FTBchCode,DT.FTXphDocNo,DT.FNXpdSeqNo,DT.FTPdtCode,DT.FTXpdPdtName,
                DT.FTPunCode,DT.FTPunName,DT.FCXpdFactor,DT.FTXpdBarCode,DT.FCXpdQty,DT.FCXpdQtyAll,
                DT.FTXpdRmk,DT.FDLastUpdOn,DT.FTLastUpdBy,DT.FDCreateOn,DT.FTCreateBy
            FROM TCNTPdtReqHqDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tTRBcNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($tBchCode);
        unset($tTRBcNo);
        unset($tSQL);
        unset($oQuery);
        unset($oDataList);
        return $aResult;
    }

    // Function : Add/Update Data HD
    public function FSxMTRBAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMTRBGetDataDocHD(array(
            'FTBchCode'     => $paDataWhere['FTBchCode'],
            'FTAgnCode'     => $paDataWhere['FTAgnCode'],
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FNLngID'       => $this->input->post("ohdTRBLangEdit")
        ));
        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['rdDateOn'],
                'FTCreateBy'    => $aDataHDOld['rtCreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        // Delete PI HD
        $this->db->where('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where('FTAgnCode',$aDataAddUpdateHD['FTAgnCode']);
        $this->db->where('FTXthDocNo',$aDataAddUpdateHD['FTXthDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);
        // Insert PI HD Dis
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        unset($aDataGetDataHD);
        unset($aDataAddUpdateHD);
        unset($paDataMaster);
        unset($paDataWhere);
        unset($paTableAddUpdate);
        return;
    }



    //อัพเดทเลขที่เอกสาร  TSVTTRBDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMTRBAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableHD']);
        $this->db->update('TSVTTRBDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));
        unset($paDataWhere,$paTableAddUpdate);
        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMTRBMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tTRBBchCode    = $paDataWhere['FTBchCode'];
        $tTRBAgnCode    = $paDataWhere['FTAgnCode'];
        $tTRBDocNo      = $paDataWhere['FTXthDocNo'];
        $tTRBDocKey     = $paTableAddUpdate['tTableHD'];
        $tTRBSessionID  = $paDataWhere['FTSessionID'];
        if(isset($tTRBDocNo) && !empty($tTRBDocNo)){
            $this->db->where('FTXthDocNo',$tTRBDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }
        $tSQL   = " 
            INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                FTBchCode,FTAgnCode,FTXthDocNo,FNXtdSeqNo,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FTXtdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy
            ) 
        ";
        $tSQL   .=  "   
            SELECT
                DOCTMP.FTBchCode,
                '$tTRBAgnCode' AS FTAgnCode,
                DOCTMP.FTXthDocNo,
                ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                DOCTMP.FTPdtCode,
                DOCTMP.FTXtdPdtName,
                DOCTMP.FTPunCode,
                DOCTMP.FTPunName,
                DOCTMP.FCXtdFactor,
                DOCTMP.FTXtdBarCode,
                DOCTMP.FCXtdQty,
                DOCTMP.FCXtdQtyAll,
                DOCTMP.FTXtdRmk,
                DOCTMP.FDLastUpdOn,
                DOCTMP.FTLastUpdBy,
                DOCTMP.FDCreateOn,
                DOCTMP.FTCreateBy
            FROM TSVTTRBDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID <> ''
            AND DOCTMP.FTBchCode    = '$tTRBBchCode'
            AND DOCTMP.FTXthDocNo   = '$tTRBDocNo'
            AND DOCTMP.FTXthDocKey  = '$tTRBDocKey'
            AND DOCTMP.FTSessionID  = '$tTRBSessionID'
            ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        unset($tTRBBchCode);
        unset($tTRBAgnCode);
        unset($tTRBDocNo);
        unset($tTRBDocKey);
        unset($tTRBSessionID);
        unset($tSQL);
        unset($oQuery);
        return;
    }

    //---------------------------------------------------------------------------------------

    //ข้อมูล HD
    public function FSaMTRBGetDataDocHD($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tAgnCode   = $paDataWhere['FTAgnCode'];
        $tTRBDocNo  = $paDataWhere['FTXthDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL = "SELECT
                    STACANCEL.FTXshRefType,
                    DOCHD.FTXthDocNo    AS rtXthDocNo,
                    DOCHD.FDXthDocDate  AS rdXthDocDate,
                    DOCHD.FTXthStaDoc   AS rtXthStaDoc,
                    DOCHD.FTXthStaApv   AS rtXthStaApv,
                    DOCHD.FNXthStaRef  AS rnXthStaRef,
                    DOCHD.FNXthStaDocAct  AS rnXthStaDocAct,
                    DOCHD.FNXthDocPrint  AS rnXthDocPrint,
                    TRBREF.FTXshRefDocNo AS rtXthRefInt,
                    TRBREFEX.FTXshRefDocNo AS rtXthRefExt,
                    TRBREF.FDXshRefDocDate AS rdXthRefIntDate,
                    TRBREFEX.FDXshRefDocDate AS rdXthRefExtDate,
                    DOCHD.FTXthRmk     AS rtXthRmk,
                    DOCHD.FDCreateOn   AS rdDateOn,
                    DOCHD.FTCreateBy   AS rtCreateBy,
                    AGN.FTAgnCode       AS rtAgnCode,
                    AGN.FTAgnName       AS rtAgnName,
                    DOCHD.FTBchCode     AS rtBchCode,
                    BCHL.FTBchName      AS rtBchName,
                    USRL.FTUsrName      AS rtUsrName ,
                    DOCHD.FTXthApvCode  AS rtXthApvCode,
                    USRAPV.FTUsrName	AS rtXthApvName,
                    AGNTo.FTAgnCode     AS rtAgnCodeTo,
                    AGNTo.FTAgnName     AS rtAgnNameTo,
                    DOCHD.FTXthBchFrm    AS rtBchCodeTo,
                    BCHLTo.FTBchName    AS rtBchNameTo,
                    WAHTo_L.FTWahCode   AS rtWahCodeTo,
                    WAHTo_L.FTWahName    AS rtWahNameTo,
                    AGNShip.FTAgnCode    AS rtAgnCodeShip,
                    AGNShip.FTAgnName    AS rtAgnNameShip,
                    DOCHD.FTXthBchTo    AS rtBchCodeShip,
                    BCHLShip.FTBchName    AS rtBchNameShip,
                    WAHShipTo_L.FTWahCode  AS rtWahCodeShip,
                    WAHShipTo_L.FTWahName  AS rtWahNameShip,
                    DOCHD.FTRsnCode       AS rtRsnCode,
                    RSNL.FTRsnName        AS rtRsnName,
                    TROREF.FTXshRefType   AS rtStaDocRef
            FROM TCNTPdtReqBchHD DOCHD WITH (NOLOCK)
            INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode
            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMAgency_L      AGN     WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXthApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch        BCHTo    WITH (NOLOCK)  ON DOCHD.FTXthBchFrm     = BCHTo.FTBchCode
            LEFT JOIN TCNMBranch_L      BCHLTo   WITH (NOLOCK)  ON DOCHD.FTXthBchFrm     = BCHLTo.FTBchCode  AND BCHLTo.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMAgency_L      AGNTo    WITH (NOLOCK)  ON BCHTo.FTAgnCode      = AGNTo.FTAgnCode   AND AGNTo.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch        BCHShip    WITH (NOLOCK)  ON DOCHD.FTXthBchTo     = BCHShip.FTBchCode
            LEFT JOIN TCNMBranch_L      BCHLShip   WITH (NOLOCK)  ON DOCHD.FTXthBchTo     = BCHLShip.FTBchCode  AND BCHLShip.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMAgency_L      AGNShip    WITH (NOLOCK)  ON BCHShip.FTAgnCode      = AGNShip.FTAgnCode   AND AGNShip.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAHTo_L  WITH (NOLOCK) ON DOCHD.FTXthBchFrm   = WAHTo_L.FTBchCode AND DOCHD.FTXthWhFrm = WAHTo_L.FTWahCode AND WAHTo_L.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAHShipTo_L  WITH (NOLOCK)   ON DOCHD.FTXthBchTo  = WAHShipTo_L.FTBchCode   AND DOCHD.FTXthWhTo = WAHShipTo_L.FTWahCode AND WAHShipTo_L.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMRsn_L         RSNL	WITH (NOLOCK)   ON DOCHD.FTRsnCode	= RSNL.FTRsnCode	AND RSNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtReqBchHDDocRef    TRBREF WITH (NOLOCK) ON TRBREF.FTXshDocNo  = DOCHD.FTXthDocNo AND TRBREF.FTXshRefType    = '1'
            LEFT JOIN TCNTPdtReqBchHDDocRef    TRBREFEX WITH (NOLOCK) ON TRBREFEX.FTXshDocNo  = DOCHD.FTXthDocNo AND TRBREFEX.FTXshRefType  = '3'
            LEFT JOIN 
                (SELECT 
                    TOP 1 DOCREF2.FTXshDocNo,
                    DOCREF2.FTXshRefType
                FROM TCNTPdtReqBchHDDocRef DOCREF2 
                WHERE DOCREF2.FTXshDocNo = '$tTRBDocNo'  
                AND DOCREF2.FTXshRefType = '2'
                ) STACANCEL ON STACANCEL.FTXshDocNo = DOCHD.FTXthDocNo
            LEFT JOIN TCNTPdtTboHDDocRef    TROREF WITH (NOLOCK) ON TROREF.FTXshRefDocNo  = DOCHD.FTXthDocNo AND TROREF.FTXshRefType    = '1'
            WHERE DOCHD.FDCreateOn <> ''
            AND DOCHD.FTBchCode = '$tBchCode'
            AND DOCHD.FTAgnCode = '$tAgnCode'
            AND DOCHD.FTXthDocNo = '$tTRBDocNo'
        ";
        
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
        unset($tBchCode);
        unset($tAgnCode);
        unset($tTRBDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }

    //อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMTRBUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');
        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXthRmk',$paDataUpdate['FTXthRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtReqBchHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Cancel Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        unset($dLastUpdOn);
        unset($tLastUpdBy);
        return $aStatus;
    }

    //ลบข้อมูลใน Temp
    public function FSnMTRBDelALLTmp($paData){
        try {
            $this->db->trans_begin();
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TSVTTRBDocDTTmp');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            unset($paData);
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ย้ายจาก DT To Temp
    public function FSxMTRBMoveDTToDTTemp($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tAgnCode   = $paDataWhere['FTAgnCode'];
        $tTRBDocNo  = $paDataWhere['FTXthDocNo'];
        $tTRBcKey   = $paDataWhere['FTXthDocKey'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tTRBDocNo);
        $this->db->delete('TSVTTRBDocDTTmp');
        $tSQL   = "
            INSERT INTO TSVTTRBDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
            )
            SELECT
                DT.FTBchCode,
                DT.FTXthDocNo,
                DT.FNXtdSeqNo,
                CONVERT(VARCHAR,'".$tTRBcKey."') AS FTXthDocKey,
                DT.FTPdtCode,
                DT.FTXtdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXtdFactor,
                DT.FTXtdBarCode,
                DT.FCXtdQty,
                DT.FCXtdQtyAll,
                DT.FTXtdRmk,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM TCNTPdtReqBchDT AS DT WITH (NOLOCK)
            WHERE DT.FDCreateOn <> ''
            AND DT.FTBchCode = '$tBchCode'
            AND DT.FTAgnCode = '$tAgnCode'
            AND DT.FTXthDocNo = '$tTRBDocNo'
            ORDER BY DT.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        unset($tBchCode);
        unset($tAgnCode);
        unset($tTRBDocNo);
        unset($tTRBcKey);
        unset($tSQL);
        unset($oQuery);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMTRBCallRefIntDocInsertDTToTemp($paData){
        $tTRBDocNo      = $paData['tTRBDocNo'];
        $tTRBFrmBchCode = $paData['tTRBFrmBchCode'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID',$tSesSessionID);
        $this->db->where('FTXthDocKey','TCNTPdtReqBchHD');
        $this->db->delete('TSVTTRBDocDTTmp');
        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        if(!empty($paData['aSeqNo'])){
            $tWhereSeqNo       = 'AND DT.FNXpdSeqNo IN (' . implode(',', $paData['aSeqNo']) .')';
        }else{
            $tWhereSeqNo  = 'AND DT.FNXpdSeqNo IN (0)';
        }
        $tSQL   = "
            INSERT INTO TSVTTRBDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
            )
            SELECT
                '$tTRBFrmBchCode' as FTBchCode,
                '$tTRBDocNo' as FTXthDocNo,
                DT.FNXpdSeqNo,
                'TCNTPdtReqBchHD' AS FTXthDocKey,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                0 as FCXpdQtyLef,
                0 as FCXpdQtyRfn,
                '' as FTXpdStaPrcStk,
                PDT.FTPdtStaAlwDis,
                0 as FNXpdPdtLevel,
                '' as FTXpdPdtParent,
                0 as FCXpdQtySet,
                '' as FTPdtStaSet,
                '' as FTXpdRmk,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM TCNTPdtReqHqDT DT WITH (NOLOCK)
            LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
            WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' $tWhereSeqNo
        ";
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
        unset($tTRBDocNo);
        unset($tTRBFrmBchCode);
        unset($tSesSessionID);
        unset($tRefIntDocNo);
        unset($tRefIntBchCode);
        unset($tWhereSeqNo);
        unset($tSQL);
        unset($oQuery);
        return $aResult;

    }

    // Function: Delete Purchase Invoice Document
    public function FSnMTRBDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode   = $paDataDoc['tBchCode'];
        $tAgnCode   = $paDataDoc['tAgnCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXthDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTAgnCode',$tAgnCode);
        $this->db->delete('TCNTPdtReqBchHD');

        // Document DT
        $this->db->where('FTXthDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTAgnCode',$tAgnCode);
        $this->db->delete('TCNTPdtReqBchDT');

        // TRB Ref
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqBchHDDocRef');

        // PRB Ref
        $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqHqHDDocRef');

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
        unset($tDataDocNo);
        unset($tBchCode);
        unset($tAgnCode);
        unset($paDataDoc);
        return $aStaDelDoc;
    }

    // Function: Delete Purchase Invoice Document
    public function FSnMTRBDelRef($paDataDoc){
        $tDataDocNo = $paDataDoc;
        $this->db->trans_begin();
        // TRB Ref
        $this->db->where('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqBchHDDocRef');
        // PRB Ref
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTXshRefType','1');
        $this->db->where('FTXshRefKey','TRB');
        $this->db->delete('TCNTPdtReqHqHDDocRef');
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
        unset($tDataDocNo);
        unset($paDataDoc);
        return $aStaDelDoc;
    }

    // Function : Cancel Document Data
    public function FSaMTRBCancelDocument($paDataUpdate){
        // TCNTPdtReqBchHD
        $this->db->trans_begin();
        $this->db->set('FTXthStaDoc' , '3');
        $this->db->set('FTXthStaApv' , '');
        $this->db->where('FTXthDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtReqBchHD');
        // TRB Ref
        $this->db->where_in('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqBchHDDocRef');
        // PRB Ref
        $this->db->where_in('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqHqHDDocRef');
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
        unset($paDataUpdate);
        return $aDatRetrun;
    }

    //อนุมัตเอกสาร
    public function FSaMTRBApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');
        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXthStaApv',$paDataUpdate['FTXthStaApv']);
        $this->db->set('FTXthApvCode',$paDataUpdate['FTXthUsrApv']);
        $this->db->where('FTAgnCode',$paDataUpdate['FTAgnCode']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtReqBchHD');
        //อัพเดท ที่ตารางจัดการใบสั้งสินค้าจากสาขาด้วย
        $this->db->set('FTXrhStaPrcDoc',2);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FNXrhDocType', 1); //ใบขอโอน
        $this->db->update('TCNTPdtReqMgtHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Cancel Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        unset($dLastUpdOn);
        unset($tLastUpdBy);
        unset($paDataUpdate);
        return $aStatus;
    }

    public function FSaMTRBUpdatePOStaPrcDoc($ptRefInDocNo){
        $nStaPrcDoc = 1;
        $this->db->set('FTXphStaPrcDoc',$nStaPrcDoc);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TCNTPdtReqHqHD');
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
        unset($nStaPrcDoc);
        unset($ptRefInDocNo);
        return $aStatus;
    }

    public function FSaMTRBUpdatePOStaRef($ptRefInDocNo, $pnStaRef){
        $this->db->set('FNXphStaRef',$pnStaRef);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TCNTPdtReqHqHD');
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
        unset($ptRefInDocNo, $pnStaRef);
        return $aStatus;
    }

    public function FSaMTRBUpdateRefDocHD($paDataTRBAddDocRef, $aDatawherePRBAddDocRef ,$aDataPRBAddDocRef){
        try {
            $tTable     = "TCNTPdtReqBchHDDocRef";
            $tTableRef  = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataTRBAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataTRBAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataTRBAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '1',
                'FTXshRefDocNo'     => $paDataTRBAddDocRef['FTXshRefDocNo']
            );
            $nChhkDataDocRefInt  = $this->FSaMTRBChkDupicate($paDataPrimaryKey, $tTable);
            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefInt['rtCode']) && $nChhkDataDocRefInt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataPrimaryKey['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataPrimaryKey['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataPrimaryKey['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','1');
                $this->db->where_in('FTXshRefDocNo',$paDataPrimaryKey['FTXshRefDocNo']);
                $this->db->delete('TCNTPdtReqBchHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqBchHDDocRef',$paDataTRBAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqBchHDDocRef',$paDataTRBAddDocRef);
            }
            $aDataWhere = array(
                'FTAgnCode'         => $aDataPRBAddDocRef['FTAgnCode'],
                'FTBchCode'         => $aDataPRBAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $aDataPRBAddDocRef['FTXshDocNo'],
                'FTXshRefType'      => '2',
                'FTXshRefDocNo'     => $aDataPRBAddDocRef['FTXshRefDocNo']
            );
            $nChhkDataDocRefPRB  = $this->FSaMTRBChkDupicate($aDataWhere, $tTableRef);
            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefPRB['rtCode']) && $nChhkDataDocRefPRB['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$aDataWhere['FTAgnCode']);
                $this->db->where_in('FTBchCode',$aDataWhere['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$aDataWhere['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','2');
                $this->db->where_in('FTXshRefDocNo',$aDataWhere['FTXshRefDocNo']);
                $this->db->delete('TCNTPdtReqHqHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqHqHDDocRef',$aDataPRBAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqHqHDDocRef',$aDataPRBAddDocRef);
            }
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        unset($tTable);
        unset($tTableRef);
        unset($paDataPrimaryKey);
        unset($nChhkDataDocRefInt);
        unset($aDataWhere);
        unset($nChhkDataDocRefPRB);
        unset($paDataTRBAddDocRef,$aDatawherePRBAddDocRef,$aDataPRBAddDocRef);
        return $aReturnData;
    }


    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMTRBChkDupicate($paDataPrimaryKey, $ptTable){
        try{
            $tAgnCode   = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode   = $paDataPrimaryKey['FTBchCode'];
            $tDocNo     = $paDataPrimaryKey['FTXshDocNo'];
            $tRefType   = $paDataPrimaryKey['FTXshRefType'];
            $tRefDocNo  = $paDataPrimaryKey['FTXshRefDocNo'];
            $tSQL = "
                SELECT
                    FTAgnCode,
                    FTBchCode,
                    FTXshDocNo
                FROM $ptTable
                WHERE FTBchCode <> ''
                AND FTAgnCode       = '$tAgnCode'
                AND FTBchCode       = '$tBchCode'
                AND FTXshDocNo      = '$tDocNo'
                AND FTXshRefType    = '$tRefType'
                AND FTXshRefDocNo   = '$tRefDocNo'
            ";
            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aDetail = $oQueryHD->row_array();
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
            unset($tAgnCode);
            unset($tBchCode);
            unset($tDocNo);
            unset($tRefType);
            unset($tRefDocNo);
            unset($tSQL);
            unset($oQueryHD);
            unset($paDataPrimaryKey,$ptTable);
            return $aResult;
        }catch (Exception $Error) {
            echo $Error;
        }
    }

    public function FSaMTRBUpdateRefExtDocHD($paDataPRSAddDocRef){
        try {
            $tTable = "TCNTPdtReqBchHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataPRSAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataPRSAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataPRSAddDocRef['FTXshDocNo'],
                'FTXshRefType'      => '3',
                'FTXshRefDocNo'     => $paDataPRSAddDocRef['FTXshRefDocNo']
            );
            $nChhkDataDocRefExt  = $this->FSaMTRBChkDupicate($paDataPrimaryKey, $tTable);
            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataPRSAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataPRSAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataPRSAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','3');
                $this->db->where_in('FTXshRefDocNo',$paDataPRSAddDocRef['FTXshRefDocNo']);
                $this->db->delete('TCNTPdtReqBchHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqBchHDDocRef',$paDataPRSAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqBchHDDocRef',$paDataPRSAddDocRef);
            }
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        unset($tTable);
        unset($paDataPrimaryKey);
        unset($nChhkDataDocRefExt);
        unset($paDataPRSAddDocRef);
        return $aReturnData;
    }

    /* End of file deliveryorder_model.php */




    // Get Data Doc DT Temp 
    public function FSaMTRBGetDataDocTempInLine($paDataWhere){
        $tTRBBchCode    = $paDataWhere['tTRBBchCode'];
        $tTRBDocNo      = $paDataWhere['tTRBDocNo'];
        $nTRBSeqNo      = $paDataWhere['nTRBSeqNo'];
        $tTRBSessionID  = $paDataWhere['tTRBSessionID'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $tSQL   = "
            SELECT DTTMP.FCXtdFactor
            FROM TSVTTRBDocDTTmp DTTMP WITH(NOLOCK)
            WHERE DTTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
            AND DTTMP.FTBchCode     = ".$this->db->escape($tTRBBchCode)."
            AND DTTMP.FTXthDocNo    = ".$this->db->escape($tTRBDocNo)."
            AND DTTMP.FNXtdSeqNo    = ".$this->db->escape($nTRBSeqNo)."
            AND DTTMP.FTSessionID   = ".$this->db->escape($tTRBSessionID)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'raItems'       => $aDetail,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($tTRBBchCode);
        unset($tTRBDocNo);
        unset($nTRBSeqNo);
        unset($tTRBSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aDetail);
        unset($paDataWhere);
        return $aDataReturn;
    }




}
