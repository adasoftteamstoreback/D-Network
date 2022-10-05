<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliveryorder_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMDOGetDataTableList($paDataCondition){
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

        $tSQL   = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                C.*,
                COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY C.FTXphDocNo)  AS PARTITIONBYDOC, 
                HDDocRef_in.FTXshRefDocNo   AS 'DOCREF',
                CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)   AS 'DATEREF'
            FROM(
                SELECT DISTINCT
                    DOHD.FTBchCode,
                    BCHL.FTBchName,
                    DOHD.FTXphDocNo,
                    CONVERT(CHAR(10),DOHD.FDXphDocDate,103) AS FDXphDocDate,
                    CONVERT(CHAR(5), DOHD.FDXphDocDate,108) AS FTXshDocTime,
                    DOHD.FTXphStaDoc,
                    DOHD.FTXphStaApv,
                    DOHD.FNXphStaRef,
                    SPL.FTSplName,
                    DOHD.FTCreateBy,
                    DOHD.FDCreateOn,
                    DOHD.FNXphStaDocAct,
                    USRL.FTUsrName      AS FTCreateByName,
                    DOHD.FTXphApvCode,
                    USRLAPV.FTUsrName   AS FTXshApvName
                FROM TAPTDoHD DOHD WITH (NOLOCK)
                LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON DOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON DOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON DOHD.FTXphApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                INNER JOIN TCNMSpl_L    SPL     WITH (NOLOCK) ON DOHD.FTSplCode     = SPL.FTSplCode     AND SPL.FNLngID     = ".$this->db->escape($nLngID)."
                WHERE DOHD.FDCreateOn <> ''
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= "
                AND DOHD.FTBchCode IN ($tBchCode)
            ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode   = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL               .= " AND DOHD.FTShpCode = ".$this->db->escape($tUserLoginShpCode)."";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((DOHD.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),DOHD.FDXphDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((DOHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (DOHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DOHD.FTXphStaDoc = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DOHD.FTXphStaApv,'') = '' AND DOHD.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaDoc)."";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaApprove)." OR DOHD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaApprove)."";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DOHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND DOHD.FNXphStaDocAct = 0";
            }
        }

        $tSQL   .= " 
            ) AS C
            LEFT JOIN TAPTDoHDDocRef HDDocRef_in WITH (NOLOCK) ON C.FTXphDocNo = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1
            ORDER BY C.FDCreateOn DESC
        ";
       
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult    = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($tSearchStaDocAct,$tSearchStaDoc,$tSearchDocDateTo,$tSearchDocDateFrom,$tSearchBchCodeTo,$tSearchBchCodeFrom,$tSearchList);
        unset($aRowLen,$nLngID,$aDatSessionUserLogIn,$aAdvanceSearch);
        return $aResult;
    }

    // Paginations
    public function FSnMDOCountPageDocListAll($paDataCondition){
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

        $tSQL   =   "   SELECT COUNT (DOHD.FTXphDocNo) AS counts
                        FROM TAPTDoHD DOHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON DOHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TAPTDoHDDocRef DOHD_REF WITH (NOLOCK) ON DOHD.FTXphDocNo  = DOHD_REF.FTXshDocNo AND DOHD_REF.FTXshRefType = '1'
                        WHERE DOHD.FDCreateOn <> ''
                    ";

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND DOHD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND DOHD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((DOHD.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),DOHD.FDXphDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((DOHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (DOHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        
        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DOHD.FTXphStaDoc = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DOHD.FTXphStaApv,'') = '' AND DOHD.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaDoc)."";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaApprove)." OR DOHD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND DOHD.FTXphStaApv = ".$this->db->escape($tSearchStaApprove)."";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DOHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND DOHD.FNXphStaDocAct = 0";
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
        unset($tSearchStaDocAct,$tSearchStaDoc,$tSearchDocDateTo,$tSearchDocDateFrom,$tSearchBchCodeTo,$tSearchBchCodeFrom,$tSearchList);
        unset($nLngID,$aDatSessionUserLogIn,$aAdvanceSearch);
        return $aDataReturn;
    }

    // หาว่า ถ้าเป็นแฟรนไซด์ จะต้องไปเอาผู้จำหน่ายใน config
    public function FSxMDOFindSPLByConfig(){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT
                            CON.FTSysStaUsrValue    AS rtSPLCode,
                            SPLL.FTSplName          AS rtSPLName
                        FROM TSysConfig             CON     WITH (NOLOCK)
                        LEFT JOIN TCNMSpl_L         SPLL    WITH (NOLOCK) ON CON.FTSysStaUsrValue = SPLL.FTSplCode  AND SPLL.FNLngID = '$nLngID'
                        WHERE CON.FTSysCode = 'tCN_FCSupplier' AND CON.FTSysApp = 'CN' AND CON.FTSysSeq = 1 ";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($oQuery);
        return $aResult;
    }

    public function FSaMDOGetDetailUserBranch($paBchCode){
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
        $this->db->delete('TSVTDODocDTTmp');
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
    public function FSxMDOClearDataInDocTemp($paWhereClearTemp){
        $tDODocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tDODocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tDOSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "
            DELETE FROM TSVTDODocDTTmp
            WHERE TSVTDODocDTTmp.FTXthDocNo = ".$this->db->escape($tDODocNo)."
            AND TSVTDODocDTTmp.FTXthDocKey  = ".$this->db->escape($tDODocKey)."
            AND TSVTDODocDTTmp.FTSessionID  = ".$this->db->escape($tDOSessionID)."
        ";
        $this->db->query($tClearDocTemp);

        // Query Delete DocRef Temp
        $tClearDocDocRefTemp    =  "
            DELETE FROM TSVTDODocHDRefTmp
            WHERE TSVTDODocHDRefTmp.FTXthDocNo  = ".$this->db->escape($tDODocNo)."
            AND TSVTDODocHDRefTmp.FTSessionID   = ".$this->db->escape($tDOSessionID)."
        ";
        $this->db->query($tClearDocDocRefTemp);
        unset($tDODocNo);
        unset($tDODocKey);
        unset($tDOSessionID);
        unset($tClearDocTemp);
        unset($tClearDocDocRefTemp);
    }

    // Functionality : Delete Delivery Order Document
    public function FSxMDOClearDataInDocTempForImp($paWhereClearTemp){
        $tDODocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tDODocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tDOSessionID   = $paWhereClearTemp['FTSessionID'];
        // Query Delete DocTemp
        $tClearDocTemp  =   "
            DELETE FROM TSVTDODocDTTmp 
            WHERE TSVTDODocDTTmp.FTXthDocNo = ".$this->db->escape($tDODocNo)."
            AND TSVTDODocDTTmp.FTXthDocKey  = ".$this->db->escape($tDODocKey)."
            AND TSVTDODocDTTmp.FTSessionID  = ".$this->db->escape($tDOSessionID)."
            AND TSVTDODocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
        unset($tDODocNo);
        unset($tDODocKey);
        unset($tDOSessionID);
    }

    // Function: Get ShopCode From User Login
    public function FSaMDOGetShpCodeForUsrLogin($paDataShp){
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
            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
            LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
            WHERE UGP.FTUsrCode = '$tUsrLogin'
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
    public function FSaMDOGetDefOptionConfigWah($paConfigSys){
        $tSysCode       = $paConfigSys['FTSysCode'];
        $nSysSeq        = $paConfigSys['FTSysSeq'];
        $nLngID         = $paConfigSys['FNLngID'];
        $aDataReturn    = array();
        $tSQLUsrVal     = "
            SELECT
                SYSCON.FTSysStaUsrValue AS FTSysWahCode,
                WAHL.FTWahName          AS FTSysWahName
            FROM TSysConfig SYSCON  WITH(NOLOCK)
            LEFT JOIN TCNMWaHouse   WAH  WITH(NOLOCK) ON SYSCON.FTSysStaUsrValue = WAH.FTWahCode AND WAH.FTWahStaType = 1
            LEFT JOIN TCNMWaHouse_L WAHL WITH(NOLOCK) ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = ".$this->db->escape($nLngID)."
            WHERE SYSCON.FTSysCode = ".$this->db->escape($tSysCode)."
            AND SYSCON.FTSysSeq = ".$this->db->escape($nSysSeq)."
        ";
        $oQuery1    = $this->db->query($tSQLUsrVal);
        if($oQuery1->num_rows() > 0){
            $aDataReturn    = $oQuery1->row_array();
        }else{
            $tSQLUsrDef =   "   SELECT
                                    SYSCON.FTSysStaDefValue AS FTSysWahCode,
                                    WAHL.FTWahName          AS FTSysWahName
                        FROM TSysConfig SYSCON          WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaDefValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                        LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = ".$this->db->escape($nLngID)."
                        WHERE SYSCON.FTSysCode    = ".$this->db->escape($tSysCode)."
                        AND SYSCON.FTSysSeq     = ".$this->db->escape($nSysSeq)."
            ";
            $oQuery2    = $this->db->query($tSQLUsrDef);
            if($oQuery2->num_rows() > 0){
                $aDataReturn    = $oQuery2->row_array();
            }
        }
        unset($oQuery1);
        unset($oQuery2);
        return $aDataReturn;
    }

    // Function : Get Data In Doc DT Temp
    public function FSaMDOGetDocDTTempListPage($paDataWhere){
        $tDODocNo           = $paDataWhere['FTXthDocNo'];
        $tDODocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tDOSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL               = " 
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
                DOCTMP.FTTmpStatus,
                DOCTMP.FDLastUpdOn,
                DOCTMP.FDCreateOn,
                DOCTMP.FTLastUpdBy,
                DOCTMP.FTCreateBy
            FROM TSVTDODocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTXthDocKey = ".$this->db->escape($tDODocKey)."
            AND DOCTMP.FTSessionID = ".$this->db->escape($tDOSesSessionID)."
        ";
        if(isset($tDODocNo) && !empty($tDODocNo)){
            $tSQL   .=  "   AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDODocNo)." ";
        }
        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "   
                AND (
                    DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%' )
            ";
        }
        $tSQL   .= " ORDER BY DOCTMP.FNXtdSeqNo ASC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($tDODocNo);
        unset($tDODocKey);
        unset($tSearchPdtAdvTable);
        unset($tDOSesSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        unset($paDataWhere);
        return $aDataReturn;
    }

    //Get Data Pdt
    public function FSaMDOGetDataPdt($paDataPdtParams){
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
            LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = ".$this->db->escape($FTPunCode)."
            LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = ".$this->db->escape($FTPunCode)."
            LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = ".$this->db->escape($nLngID)."
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
            $tSQL   .= " AND PDT.FTPdtCode   = ".$this->db->escape($tPdtCode)."";
        }
        if(isset($FTBarCode) && !empty($FTBarCode)){
            $tSQL   .= " AND BAR.FTBarCode = ".$this->db->escape($FTBarCode)."";
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
        unset($tPdtCode);
        unset($FTPunCode);
        unset($FTBarCode);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($aDetail);
        return $aResult;
    }

    // Functionality : Insert Pdt To Doc DT Temp
    public function FSaMDOInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tDOOptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "
                SELECT
                    FNXtdSeqNo,
                    FCXtdQty
                FROM TSVTDODocDTTmp WITH (NOLOCK)
                WHERE FTXthDocNo    = ".$this->db->escape($paDataPdtParams['tDocNo'])."
                AND FTBchCode       = ".$this->db->escape($paDataPdtParams['tBchCode'])."
                AND FTXthDocKey     = ".$this->db->escape($paDataPdtParams['tDocKey'])."
                AND FTSessionID     = ".$this->db->escape($paDataPdtParams['tSessionID'])."
                AND FTPdtCode       = ".$this->db->escape($paPIDataPdt["FTPdtCode"])."
                AND FTXtdBarCode    = ".$this->db->escape($paPIDataPdt["FTBarCode"])."
                ORDER BY FNXtdSeqNo
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "
                    UPDATE TSVTDODocDTTmp
                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                    WHERE FTXthDocNo    = ".$this->db->escape($paDataPdtParams['tDocNo'])."
                    AND FTBchCode       = ".$this->db->escape($paDataPdtParams['tBchCode'])."
                    AND FNXtdSeqNo      = ".$this->db->escape($aResult["FNXtdSeqNo"])."
                    AND FTXthDocKey     = ".$this->db->escape($paDataPdtParams['tDocKey'])."
                    AND FTSessionID     = ".$this->db->escape($paDataPdtParams['tSessionID'])."
                    AND FTPdtCode       = ".$this->db->escape($paPIDataPdt["FTPdtCode"])."
                    AND FTXtdBarCode    = ".$this->db->escape($paPIDataPdt["FTBarCode"])."
                ";
                $this->db->query($tSQL);
                $aStatus    = array(
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
                        'FTTmpStatus'       => $paPIDataPdt['FTPdtType'],
                        'FCXtdQty'          => 1,
                        'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                        'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                        'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                        'FTSessionID'       => $paDataPdtParams['tSessionID'],
                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                        'FTLastUpdBy'       => $paDataPdtParams['tDOUsrCode'],
                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                        'FTCreateBy'        => $paDataPdtParams['tDOUsrCode'],
                    );
                    $this->db->insert('TSVTDODocDTTmp',$aDataInsert);
                    if($this->db->affected_rows() > 0){
                        $aStatus    = array(
                            'rtCode'    => '1',
                            'rtDesc'    => 'Add Success.',
                        );
                    }else{
                        $aStatus    = array(
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
                'FTTmpStatus'       => $paPIDataPdt['FTPdtType'],
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
                'FTLastUpdBy'       => $paDataPdtParams['tDOUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tDOUsrCode'],
            );
            $this->db->insert('TSVTDODocDTTmp',$aDataInsert);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                $aStatus    = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        }
        unset($paPIDataPdt);
        unset($tSQL);
        unset($oQuery);
        return $aStatus;
    }

    //Delete Product Single Item In Doc DT Temp
    public function FSnMDODelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where('FTXthDocNo',$paDataWhere['tDODocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TSVTDODocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMDODelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where('FTXthDocNo',$paDataWhere['tDODocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TSVTDODocDTTmp');
        return ;
    }

    // Update Document DT Temp by Seq
    public function FSaMDOUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where('FTSessionID',$paDataWhere['tDOSessionID']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nDOSeqNo']);
        if ($paDataWhere['tDODocNo'] != '' && $paDataWhere['tDOBchCode'] != '') {
            $this->db->where('FTXthDocNo',$paDataWhere['tDODocNo']);
            $this->db->where('FTBchCode',$paDataWhere['tDOBchCode']);
        }
        $this->db->update('TSVTDODocDTTmp', $paDataUpdateDT);
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

    // Function : Count Check Data Product In Doc DT Temp Before Save
    public function FSnMDOChkPdtInDocDTTemp($paDataWhere){
        $tDODocNo       = $paDataWhere['FTXthDocNo'];
        $tDODocKey      = $paDataWhere['FTXthDocKey'];
        $tDOSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " 
            SELECT
                COUNT(FNXtdSeqNo) AS nCountPdt
            FROM TSVTDODocDTTmp DocDT WITH (NOLOCK)
            WHERE DocDT.FTXthDocKey = ".$this->db->escape($tDODocKey)."
            AND DocDT.FTSessionID   = ".$this->db->escape($tDOSessionID)."
        ";
        if(isset($tDODocNo) && !empty($tDODocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'') = ".$this->db->escape($tDODocNo)."";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            unset($tDODocNo);
            unset($tDODocKey);
            unset($tDOSessionID);
            unset($tSQL);
            unset($oQuery);
            return $aDataQuery['nCountPdt'];
        }else{
            unset($tDODocNo);
            unset($tDODocKey);
            unset($tDOSessionID);
            unset($tSQL);
            unset($oQuery);
            return 0;
        }
    }

    // Function : Count Check DocRef Before Cancel
    public function FSaMDOCheckIVRef($ptDocNo){
        $tDODocNo   = $ptDocNo;
        $tSQL       = "
            SELECT
                COUNT(FTXshRefDocNo) AS nCount
            FROM TAPTPiHDDocRef DocDT WITH (NOLOCK)
            WHERE DocDT.FTXshRefDocNo   = ".$this->db->escape($tDODocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            unset($tDODocNo);
            unset($oQuery);
            return $aDataQuery['nCount'];
        }else{
            unset($tDODocNo);
            unset($oQuery);
            return 0;
        }
    }

    // อ้างอิงเอกสาร ใบสั่งซื้อ
    public function FSoMDOCallRefPOIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDORefIntBchCode        = $aAdvanceSearch['tDORefIntBchCode'];
        $tDORefIntDocNo          = $aAdvanceSearch['tDORefIntDocNo'];
        $tDORefIntDocDateFrm     = $aAdvanceSearch['tDORefIntDocDateFrm'];
        $tDORefIntDocDateTo      = $aAdvanceSearch['tDORefIntDocDateTo'];
        $tDORefIntStaDoc         = $aAdvanceSearch['tDORefIntStaDoc'];
        $tDOSplCode              = $aAdvanceSearch['tDOSplCode'];

        $tSQLMain   = "
            SELECT DISTINCT 
                POHD.FTBchCode,
                BCHL.FTBchName,
                POHD.FTXphDocNo,
                CONVERT(CHAR(16),POHD.FDXphDocDate,121) AS FDXphDocDate,
                CONVERT(CHAR(5), POHD.FDXphDocDate,108) AS FTXshDocTime,
                POHD.FTXphStaDoc,
                POHD.FTXphStaApv,
                POHD.FNXphStaRef,
                POHD.FTSplCode,
                SPL_L.FTSplName,
                POHD.FTXphVATInOrEx,
                SPL.FNXphCrTerm,
                POHD.FTCreateBy,
                POHD.FDCreateOn,
                POHD.FNXphStaDocAct,
                USRL.FTUsrName      AS FTCreateByName,
                POHD.FTXphApvCode,
                WAH_L.FTWahCode,
                WAH_L.FTWahName,
                BCHLTO.FTBchName AS BCHNameTo ,
                A.SumA
            FROM TAPTPoHD           POHD    WITH (NOLOCK)
            LEFT JOIN   (
                SELECT
                    FTXphDocNo,
                    SUM(FCXpdQtyLef) AS SumA
                FROM TAPTPoDT WITH (NOLOCK)
                GROUP BY FTXphDocNo
            ) A ON A.FTXphDocNo = POHD.FTXphDocNo
            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON POHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L  BCHLTO  WITH (NOLOCK) ON POHD.FTXphBchTo    = BCHLTO.FTBchCode  AND BCHLTO.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON POHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON POHD.FTSplCode     = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON POHD.FTBchCode     = WAH_L.FTBchCode   AND POHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
            INNER JOIN TAPTPoHDSpl  SPL     WITH (NOLOCK) ON POHD.FTXphDocNo    = SPL.FTXphDocNo
            LEFT JOIN ( 
                SELECT DOCREFSPC.FTXshDocNo , HD.FTXphDocNo , HD.FTSPLCode FROM TAPTPoHD HD
                LEFT JOIN TAPTPoHDDocRef DOCREFSPC	WITH (NOLOCK) ON HD.FTXphDocNo = DOCREFSPC.FTXshDocNo 
                AND DOCREFSPC.FTXshRefKey = 'PO' AND DOCREFSPC.FTXshRefType = 2
            ) AS SUBFN ON POHD.FTXphDocNo = SUBFN.FTXshDocNo
            WHERE A.SumA != ".$this->db->escape(0)." ";

        if(isset($tDORefIntBchCode) && !empty($tDORefIntBchCode)){
            $tSQLMain .= " AND (POHD.FTBchCode = ".$this->db->escape($tDORefIntBchCode)." OR POHD.FTXphBchTo = ".$this->db->escape($tDORefIntBchCode).")";
        }

        if(isset($tDOSplCode) && !empty($tDOSplCode)){
            $tSQLMain .= " AND (POHD.FTSplCode = ".$this->db->escape($tDOSplCode).")";
        }

        if(isset($tDORefIntDocNo) && !empty($tDORefIntDocNo)){
            $tSQLMain .= " AND (POHD.FTXphDocNo LIKE '%".$this->db->escape_like_str($tDORefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDORefIntDocDateFrm) && !empty($tDORefIntDocDateTo)){
            $tSQLMain .= " AND ((POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tDORefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDORefIntDocDateTo 23:59:59')) OR (POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tDORefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDORefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDORefIntStaDoc) && !empty($tDORefIntStaDoc)){
            if ($tDORefIntStaDoc == 3) {
                $tSQLMain .= " AND POHD.FTXphStaDoc = ".$this->db->escape($tDORefIntStaDoc);
            } elseif ($tDORefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(POHD.FTXphStaApv,'') = '' AND POHD.FTXphStaDoc != ".$this->db->escape(3);
            } elseif ($tDORefIntStaDoc == 1) {
                $tSQLMain .= " AND POHD.FTXphStaApv = ".$this->db->escape($tDORefIntStaDoc);
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                        (  
                            $tSQLMain
                        ) Base) AS c 
                    WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1] )." ";

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

    // Function: Get Data DO HD List
    public function FSoMDOCallRefABBIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDORefIntBchCode        = $aAdvanceSearch['tDORefIntBchCode'];
        $tDORefIntDocNo          = $aAdvanceSearch['tDORefIntDocNo'];
        $tDORefIntDocDateFrm     = $aAdvanceSearch['tDORefIntDocDateFrm'];
        $tDORefIntDocDateTo      = $aAdvanceSearch['tDORefIntDocDateTo'];
        $tDORefIntStaDoc         = $aAdvanceSearch['tDORefIntStaDoc'];

        $tSQLMain = "   SELECT DISTINCT
                                HD.FTBchCode,
                                BCHL.FTBchName,
                                HD.FTXshDocNo       AS FTXphDocNo,
                                CONVERT(CHAR(16),HD.FDXshDocDate,121) AS FDXphDocDate,
                                CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXshDocTime,
                                HD.FTXshStaDoc      AS FTXphStaDoc,
                                HD.FTXshStaApv      AS FTXphStaApv,
                                HD.FNXshStaRef      AS FNXphStaRef,
                                HD.FTXshVATInOrEx   AS FTXphVATInOrEx,
                                CST_Crd.FNCstCrTerm AS FNXphCrTerm,
                                HD.FTCreateBy,
                                HD.FDCreateOn,
                                HD.FNXshStaDocAct   AS FNXphStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                HD.FTXshApvCode     AS FTXphApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName,
                                BCHLTO.FTBchName AS BCHNameTo ,
                                A.SumA
                            FROM TPSTSalHD    HD    WITH (NOLOCK)
                            LEFT JOIN   (   SELECT
                                                FTXshDocNo,
                                                SUM(FCXsdQtyLef) AS SumA
                                                FROM TPSTSalDT WITH (NOLOCK)
                                                GROUP BY FTXshDocNo
                                        ) A ON A.FTXshDocNo = HD.FTXshDocNo
                            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON HD.FTBchCode       = BCHL.FTBchCode        AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                            LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON HD.FTCreateBy      = USRL.FTUsrCode        AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                            LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK) ON HD.FTBchCode       = WAH_L.FTBchCode       AND HD.FTWahCode    = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
                            LEFT JOIN TCNMCstCredit     CST_Crd WITH (NOLOCK) ON HD.FTCstCode       = CST_Crd.FTCstCode
                            LEFT JOIN TPSTSalHDDocRef   HD_R    WITH (NOLOCK) ON HD.FTXshDocNo      = HD_R.FTXshDocNo       AND HD.FTBchCode    = HD_R.FTBchCode
                            LEFT JOIN TPSTSalHDCst      SALCST  WITH (NOLOCK) ON HD.FTXshDocNo      = SALCST.FTXshDocNo 
                            LEFT JOIN TCNMBranch_L      BCHLTO  WITH (NOLOCK) ON SALCST.FTXshCstRef = BCHLTO.FTBchCode      AND BCHLTO.FNLngID  = ".$this->db->escape($nLngID)."
                            WHERE A.SumA != ".$this->db->escape(0)."
                        ";

        if(isset($tDORefIntBchCode) && !empty($tDORefIntBchCode)){
            $tSQLMain .= " AND (
                            HD.FTBchCode = ".$this->db->escape($tDORefIntBchCode)." 
                            OR HD.FTBchCode = ".$this->db->escape($tDORefIntBchCode)."
                            OR SALCST.FTXshCstRef = ".$this->db->escape($tDORefIntBchCode)." 
                            )";
        }

        if(isset($tDORefIntDocNo) && !empty($tDORefIntDocNo)){
            $tSQLMain .= " AND (HD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tDORefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDORefIntDocDateFrm) && !empty($tDORefIntDocDateTo)){
            $tSQLMain .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDORefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDORefIntDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDORefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDORefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDORefIntStaDoc) && !empty($tDORefIntStaDoc)){
            if ($tDORefIntStaDoc == 3) {
                $tSQLMain .= " AND HD.FTXshStaDoc = ".$this->db->escape($tDORefIntStaDoc);
            } elseif ($tDORefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(HD.FTXshStaApv,'') = '' AND HD.FTXshStaDoc != ".$this->db->escape(3);
            } elseif ($tDORefIntStaDoc == 1) {
                $tSQLMain .= " AND HD.FTXshStaApv = ".$this->db->escape($tDORefIntStaDoc);
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                        (  
                            $tSQLMain
                        ) Base) AS c 
                     WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])." ";  
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oQueryMainSpl      = $this->FSnMDOGetMainSpl($paDataCondition);
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
                'raMainSpl'     => $oQueryMainSpl,
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

    public function FSnMDOGetMainSpl($paDataCondition){
        $tAgnCode = $this->session->userdata('tSesUsrAgnCode');
        $nLngID                 = $paDataCondition['FNLngID'];
        if ($tAgnCode != '') {
            $tSQL   = "SELECT
                            CF.FTCfgStaUsrValue AS FTSplCode,
                            SPL_L.FTSplName
                     FROM TCNTConfigSpc CF WITH(NOLOCK)
                     LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON CF.FTCfgStaUsrValue  = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
                     WHERE  CF.FTSysCode = 'tCN_FCSupplier' AND FTSysSeq = '1'
                    ";
        }else{
            $tSQL   = "SELECT
                            CF.FTSysStaUsrValue AS FTSplCode,
                            SPL_L.FTSplName
                     FROM TSysConfig CF WITH(NOLOCK)
                     LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON CF.FTSysStaUsrValue  = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
                     WHERE  CF.FTSysCode = 'tCN_FCSupplier' AND FTSysSeq = '1'
                    ";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult    = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($tAgnCode);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Data Purchase Order HD List
    public function FSoMDOCallRefIntDocDTDataTable($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
            SELECT
                DT.FTBchCode,
                DT.FTXphDocNo,
                DT.FNXpdSeqNo,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FCXpdQtyLef AS FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FTXpdRmk,
                ISNULL(HD.FTAgnCode,'') AS FTAgnCode,
                CASE WHEN ISNULL(DT.FCXpdQtySo,0) = '0' THEN  DT.FCXpdQtyLef ELSE ISNULL(DT.FCXpdQtySo,0) END AS FCXpdQtySo,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy
                FROM TAPTPoDT DT WITH(NOLOCK)
                LEFT JOIN TAPTPoHD HD WITH (NOLOCK) ON DT.FTXphDocNo  = HD.FTXphDocNo
            WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXphDocNo = ".$this->db->escape($tDocNo)."
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
        unset($tBchCode);
        unset($tDocNo);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Data Purchase Order HD List
    public function FSoMDOCallRefIntDocABBDTDataTable($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
            SELECT
                DT.FTBchCode,
                DT.FTXshDocNo AS FTXphDocNo,
                DT.FNXsdSeqNo AS FNXpdSeqNo,
                DT.FTPdtCode,
                DT.FTXsdPdtName AS FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXsdFactor AS FCXpdFactor,
                '' AS FTAgnCode,
                0  AS FCXpdQtySo,
                DT.FTXsdBarCode AS FTXpdBarCode,
                DT.FCXsdQty AS FCXpdQty,
                DT.FCXsdQtyAll AS FCXpdQtyAll,
                DT.FTXsdRmk AS FTXpdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy
                FROM TPSTSalDT DT WITH(NOLOCK)
                WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tDocNo)." ";
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
        unset($tBchCode);
        unset($tDocNo);
        unset($oQuery);
        return $aResult;
    }

    // Function : Add/Update Data HD
    public function FSxMDOAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMDOGetDataDocHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->input->post("ohdDOLangEdit")
        ));
        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['DateOn'],
                'FTCreateBy'    => $aDataHDOld['CreateBy']
            ));
            // update HD
            $this->db->where('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
            $this->db->where('FTXphDocNo',$aDataAddUpdateHD['FTXphDocNo']);
            $this->db->update($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
            // Insert PI HD Dis
            $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        }
        unset($aDataGetDataHD);
        unset($aDataHDOld);
        unset($aDataAddUpdateHD);
        return;
    }

    // Function : Add/Update Data HD Supplier
    public function FSxMDOAddUpdateHDSpl($paDataHDSpl,$paDataWhere,$paTableAddUpdate){
        // Get Data PI HD
        $aDataGetDataSpl    =   $this->FSaMDOGetDataDocHDSpl(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->input->post("ohdDOLangEdit")
        ));
        $aDataAddUpdateHDSpl    = array();
        if(isset($aDataGetDataSpl['rtCode']) && $aDataGetDataSpl['rtCode'] == 1){
            $aDataHDSplOld      = $aDataGetDataSpl['raItems'];
            $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
                'FTBchCode'     => $aDataHDSplOld['FTBchCode'],
                'FTXphDocNo'    => $aDataHDSplOld['FTXphDocNo'],
            ));
        }else{
            $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            ));
        }
        // Delete PI HD Spl
        $this->db->where('FTBchCode',$aDataAddUpdateHDSpl['FTBchCode']);
        $this->db->where('FTXphDocNo',$aDataAddUpdateHDSpl['FTXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDSpl']);
        // Insert PI HD Dis
        $this->db->insert($paTableAddUpdate['tTableHDSpl'],$aDataAddUpdateHDSpl);
        unset($aDataGetDataSpl);
        unset($aDataAddUpdateHDSpl);
        unset($aDataHDSplOld);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TSVTDODocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMDOAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TSVTDODocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into TSVTDODocHDRefTmp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTXthDocKey','TAPTDoHD');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TSVTDODocHDRefTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));

        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMDOMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tDOBchCode     = $paDataWhere['FTBchCode'];
        $tDODocNo       = $paDataWhere['FTXphDocNo'];
        $tDODocKey      = $paTableAddUpdate['tTableDT'];
        $tDOSessionID   = $paDataWhere['FTSessionID'];

        if(isset($tDODocNo) && !empty($tDODocNo)){
            $this->db->where('FTXphDocNo',$tDODocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTBchCode,FTXphDocNo,FNXpdSeqNo,FTPdtCode,FTXpdPdtName,FTPunCode,FTPunName,FCXpdFactor,FTXpdBarCode,
                        FCXpdQty,FCXpdQtyAll,FCXpdQtyLef,FCXpdQtyRfn,FTXpdStaPrcStk,FTXpdStaAlwDis,
                        FNXpdPdtLevel,FTXpdPdtParent,FCXpdQtySet,FTPdtStaSet,FTXpdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTBchCode,
                            DOCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DOCTMP.FTPdtCode,
                            DOCTMP.FTXtdPdtName,
                            DOCTMP.FTPunCode,
                            DOCTMP.FTPunName,
                            DOCTMP.FCXtdFactor,
                            DOCTMP.FTXtdBarCode,
                            DOCTMP.FCXtdQty,
                            DOCTMP.FCXtdQty * DOCTMP.FCXtdFactor AS FCXpdQtyAll,
                            DOCTMP.FCXtdQtyLef,
                            DOCTMP.FCXtdQtyRfn,
                            DOCTMP.FTXtdStaPrcStk,
                            DOCTMP.FTXtdStaAlwDis,
                            DOCTMP.FNXtdPdtLevel,
                            DOCTMP.FTXtdPdtParent,
                            DOCTMP.FCXtdQtySet,
                            DOCTMP.FTXtdPdtStaSet,
                            DOCTMP.FTXtdRmk,
                            DOCTMP.FDLastUpdOn,
                            DOCTMP.FTLastUpdBy,
                            DOCTMP.FDCreateOn,
                            DOCTMP.FTCreateBy
                        FROM TSVTDODocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE  DOCTMP.FTXthDocNo    = ".$this->db->escape($tDODocNo)."
                        AND DOCTMP.FTBchCode        = ".$this->db->escape($tDOBchCode)."
                        AND DOCTMP.FTXthDocKey      = ".$this->db->escape($tDODocKey)."
                        AND DOCTMP.FTSessionID      = ".$this->db->escape($tDOSessionID)."
                        ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        unset($tDOBchCode);
        unset($tDODocNo);
        unset($tDODocKey);
        unset($tDOSessionID);
        unset($oQuery);
        return;
    }

    // ข้อมูล HD
    public function FSaMDOGetDataDocHD($paDataWhere){
        $tDODocNo   = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " 
            SELECT
                DOCHD.FTXphDocNo,
                DOCHD.FDXphDocDate,
                DOCHD.FTXphStaDoc,
                DOCHD.FTXphStaApv,
                DOCHD.FTDptCode,
                DOCHD.FTXphApvCode,
                DOCHD.FTXphRefInt,
                DOCHD.FDXphRefIntDate,
                DOCHD.FTXphRefExt,
                DOCHD.FDXphRefExtDate,
                DOCHD.FNXphStaRef,
                DOCHD.FTWahCode,
                DOCHD.FNXphStaDocAct,
                DOCHD.FNXphDocPrint,
                DOCHD.FTXphRmk,
                DOCHD.FTRteCode,
                DOCHD.FTXphVATInOrEx,
                DOCHD.FTXphCshOrCrd,
                DOCHD.FNXphStaDocAct,
                DOCHD.FDCreateOn AS DateOn,
                DOCHD.FTCreateBy AS CreateBy,
                DOCHD.FTBchCode,
                BCHL.FTBchName,
                DPTL.FTDptName,
                USRL.FTUsrName,
                RTE_L.FTRteName,
                USRAPV.FTUsrName	AS FTXphApvName,
                DOSPL.FNXphCrTerm,
                DOSPL.FTXphCtrName,
                DOSPL.FDXphTnfDate,
                DOSPL.FTXphRefTnfID,
                DOSPL.FTXphRefVehID,
                DOSPL.FTXphRefInvNo,
                SPL.FTCreateBy,
                SPL.FTSplCode,
                SPL_L.FTSplName,
                POHD.FTBchCode AS rtPOBchCode,
                POBCHL.FTBchName AS rtPOBchName ,
                AGN.FTAgnCode       AS rtAgnCode,
                AGN.FTAgnName       AS rtAgnName,
                WAH_L.FTWahCode     AS rtWahCode,
                WAH_L.FTWahName     AS rtWahName,
                DOREF.FTXshRefDocNo AS rtPORef,
                PIREF.FTXshRefDocNo AS rtPIRef,
                SOREF.FTXshRefDocNo AS rtSORef
            FROM TAPTDoHD DOCHD WITH (NOLOCK)
            INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode
            LEFT JOIN TAPTDoHDSpl       DOSPL   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = DOSPL.FTXphDocNo
            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMAgency_L      AGN     WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXphApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSpl           SPL     WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL.FTSplCode
            LEFT JOIN TCNMSpl_L         SPL_L   WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL_L.FTSplCode   AND SPL_L.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TFNMRate_L        RTE_L   WITH (NOLOCK)   ON DOCHD.FTRteCode      = RTE_L.FTRteCode   AND RTE_L.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK)   ON DOCHD.FTBchCode      = WAH_L.FTBchCode   AND DOCHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TAPTPoHD          POHD    WITH (NOLOCK)   ON DOCHD.FTXphRefInt    = POHD.FTXphDocNo
            LEFT JOIN TCNMBranch_L      POBCHL  WITH (NOLOCK)   ON POHD.FTBchCode       = POBCHL.FTBchCode  AND POBCHL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TAPTDoHDDocRef    DOREF   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = DOREF.FTXshDocNo  AND DOREF.FTXshRefType = '1' AND DOREF.FTXshRefKey = 'PO'
            LEFT JOIN TAPTDoHDDocRef    PIREF   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = PIREF.FTXshDocNo  AND PIREF.FTXshRefType = '2' AND PIREF.FTXshRefKey = 'IV'
            LEFT JOIN TAPTDoHDDocRef    SOREF   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = SOREF.FTXshDocNo  AND SOREF.FTXshRefType = '2' AND SOREF.FTXshRefKey = 'SO'
            WHERE DOCHD.FTXphDocNo = ".$this->db->escape($tDODocNo)."
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
        unset($tDODocNo);
        unset($nLngID);
        unset($tSQL);
        unset($aDetail);
        unset($oQuery);
        return $aResult;
    }

    // Get Data Document HD Spl
    public function FSaMDOGetDataDocHDSpl($paDataWhere){
        $tDODocNo   = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " 
            SELECT
                HDSPL.FTBchCode,
                HDSPL.FTXphDocNo,
                HDSPL.FTXphDstPaid,
                HDSPL.FNXphCrTerm,
                HDSPL.FDXphDueDate,
                HDSPL.FDXphBillDue,
                HDSPL.FTXphCtrName,
                HDSPL.FDXphTnfDate,
                HDSPL.FTXphRefTnfID,
                HDSPL.FTXphRefVehID,
                HDSPL.FTXphRefInvNo,
                HDSPL.FTXphQtyAndTypeUnit,
                HDSPL.FNXphShipAdd,
                SHIP_Add.FTAddV1No              AS FTXphShipAddNo,
                SHIP_Add.FTAddV1Soi				AS FTXphShipAddPoi,
                SHIP_Add.FTAddV1Village         AS FTXphShipAddVillage,
                SHIP_Add.FTAddV1Road			AS FTXphShipAddRoad,
                SHIP_SUDIS.FTSudName			AS FTXphShipSubDistrict,
                SHIP_DIS.FTDstName				AS FTXphShipDistrict,
                SHIP_PVN.FTPvnName				AS FTXphShipProvince,
                SHIP_Add.FTAddV1PostCode	    AS FTXphShipPosCode,
                HDSPL.FNXphTaxAdd,
                TAX_Add.FTAddV1No               AS FTXphTaxAddNo,
                TAX_Add.FTAddV1Soi				AS FTXphTaxAddPoi,
                TAX_Add.FTAddV1Village		    AS FTXphTaxAddVillage,
                TAX_Add.FTAddV1Road				AS FTXphTaxAddRoad,
                TAX_SUDIS.FTSudName				AS FTXphTaxSubDistrict,
                TAX_DIS.FTDstName               AS FTXphTaxDistrict,
                TAX_PVN.FTPvnName               AS FTXphTaxProvince,
                TAX_Add.FTAddV1PostCode		    AS FTXphTaxPosCode
            FROM TAPTDoHDSpl HDSPL  WITH (NOLOCK)
            LEFT JOIN TCNMAddress_L			SHIP_Add    WITH (NOLOCK)   ON HDSPL.FNXphShipAdd       = SHIP_Add.FNAddSeqNo	AND SHIP_Add.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSubDistrict_L     SHIP_SUDIS 	WITH (NOLOCK)	ON SHIP_Add.FTAddV1SubDist	= SHIP_SUDIS.FTSudCode	AND SHIP_SUDIS.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMDistrict_L        SHIP_DIS    WITH (NOLOCK)	ON SHIP_Add.FTAddV1DstCode	= SHIP_DIS.FTDstCode    AND SHIP_DIS.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMProvince_L        SHIP_PVN    WITH (NOLOCK)	ON SHIP_Add.FTAddV1PvnCode	= SHIP_PVN.FTPvnCode    AND SHIP_PVN.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMAddress_L			TAX_Add     WITH (NOLOCK)   ON HDSPL.FNXphTaxAdd        = TAX_Add.FNAddSeqNo	AND TAX_Add.FNLngID		= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSubDistrict_L     TAX_SUDIS 	WITH (NOLOCK)	ON TAX_Add.FTAddV1SubDist   = TAX_SUDIS.FTSudCode	AND TAX_SUDIS.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMDistrict_L        TAX_DIS     WITH (NOLOCK)	ON TAX_Add.FTAddV1DstCode   = TAX_DIS.FTDstCode     AND TAX_DIS.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMProvince_L        TAX_PVN     WITH (NOLOCK)	ON TAX_Add.FTAddV1PvnCode   = TAX_PVN.FTPvnCode		AND TAX_PVN.FNLngID     = ".$this->db->escape($nLngID)."
            WHERE HDSPL.FTXphDocNo = ".$this->db->escape($tDODocNo)."
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
        unset($tDODocNo);
        unset($nLngID);
        unset($tSQL);
        unset($aDetail);
        unset($oQuery);
        return $aResult;

    }

    //ลบข้อมูลใน Temp
    public function FSnMDODelALLTmp($paData){
        try {
            $this->db->trans_begin();
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TSVTDODocDTTmp');
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
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ย้ายจาก DT To Temp
    public function FSxMDOMoveDTToDTTemp($paDataWhere){
        $tDODocNo       = $paDataWhere['FTXphDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tDODocNo);
        $this->db->where('FTSessionID',$this->session->userdata('tSesSessionID'));
        $this->db->delete('TSVTDODocDTTmp');
        $tSQL   = " 
            INSERT INTO TSVTDODocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
            )
            SELECT
                DT.FTBchCode,
                DT.FTXphDocNo,
                DT.FNXpdSeqNo,
                CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FCXpdQtyLef,
                DT.FCXpdQtyRfn,
                DT.FTXpdStaPrcStk,
                DT.FTXpdStaAlwDis,
                DT.FNXpdPdtLevel,
                DT.FTXpdPdtParent,
                DT.FCXpdQtySet,
                DT.FTPdtStaSet,
                DT.FTXpdRmk,
                PDT.FTPdtType,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM TAPTDoDT AS DT WITH (NOLOCK)
            LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON  PDT.FTPdtCode = DT.FTPdtCode
            WHERE DT.FTXphDocNo = ".$this->db->escape($tDODocNo)."
            ORDER BY DT.FNXpdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        unset($tDODocNo);
        unset($tDocKey);
        unset($tSQL);
        unset($oQuery);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDOCallRefIntDocInsertDTToTemp($paData, $ptDocType){
        $tDODocNo           = $paData['tDODocNo'];
        $tDOFrmBchCode      = $paData['tDOFrmBchCode'];
        $tDOOptionAddPdt    = $paData['tDOOptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tSessionID         = $this->session->userdata('tSesSessionID');
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';
        $oQueryCheckTempDocType = $this->FSnMDOCheckTempDocType($paData);
        if ($oQueryCheckTempDocType['raItems'] == '') {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDODocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TSVTDODocDTTmp');
        }elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDODocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TSVTDODocDTTmp');
            //ลบรายการอ้างอิง
            $tClearDocDocRefTemp =   "
                DELETE FROM TSVTDODocHDRefTmp
                WHERE  TSVTDODocHDRefTmp.FTXthDocNo  = ".$this->db->escape($paData['tDODocNo'])."
                AND TSVTDODocHDRefTmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
            ";
            $this->db->query($tClearDocDocRefTemp);
        }
        if($tDOOptionAddPdt == 1){
            $tSQLSelectDT   = "
                SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXpdBarCode  , DT.FNXpdSeqNo , DT.FCXpdQty
                FROM TAPTPoDT DT WITH(NOLOCK)
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";
            $oQuery = $this->db->query($tSQLSelectDT);
            
            $tSQLGetSeqPDT = "
                SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
                FROM TSVTDODocDTTmp WITH(NOLOCK)
                WHERE FTSessionID = ".$this->db->escape($tSessionID)."
                AND FTXthDocKey = 'TAPTDoDT'
            ";
            $oQuerySeq = $this->db->query($tSQLGetSeqPDT);
            $aResultDTSeq = $oQuerySeq->row_array();
            if ($oQuery->num_rows() > 0) {
                $aResultDT      = $oQuery->result_array();
                $nCountResultDT = count($aResultDT);
                if($nCountResultDT >= 0){
                    for($j=0; $j<$nCountResultDT; $j++){
                        $tSQL   =   "
                            SELECT FNXtdSeqNo , FCXtdQty 
                            FROM TSVTDODocDTTmp WITH(NOLOCK)
                            WHERE FTXthDocNo            = ".$this->db->escape($tDODocNo)."
                            AND FTBchCode               = ".$this->db->escape($tDOFrmBchCode)."
                            AND FTXthDocKey             = 'TAPTDoDT'
                            AND FTSessionID             = ".$this->db->escape($tSessionID)."
                            AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                            AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                            AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXpdBarCode"])." 
                            ORDER BY FNXtdSeqNo ";
                        $oQuery = $this->db->query($tSQL);
                        if ($oQuery->num_rows() > 0) {

                            // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                            $aResult    =   $oQuery->row_array();
                            $tSQL       =   "
                                UPDATE TSVTDODocDTTmp
                                SET FCXtdQty = '".($aResult["FCXtdQty"] + $aResultDT[$j]["FCXpdQty"] )."'
                                WHERE FTXthDocNo            = ".$this->db->escape($tDODocNo)."
                                AND FTBchCode               = ".$this->db->escape($tDOFrmBchCode)."
                                AND FNXtdSeqNo              = ".$this->db->escape($aResult["FNXtdSeqNo"])."
                                AND FTXthDocKey             = 'TAPTDoDT'
                                AND FTSessionID             = ".$this->db->escape($tSessionID)."
                                AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                                AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                                AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXpdBarCode"])." "; 
                            $this->db->query($tSQL);
                        }else{
                            $tSQL= "INSERT INTO TSVTDODocDTTmp (
                                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                                FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                                SELECT
                                    '$tDOFrmBchCode' as FTBchCode,
                                    '$tDODocNo' as FTXphDocNo,
                                    ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXpdSeqNo,
                                    'TAPTDoDT' AS FTXthDocKey,
                                    DT.FTPdtCode,
                                    DT.FTXpdPdtName,
                                    DT.FTPunCode,
                                    DT.FTPunName,
                                    DT.FCXpdFactor,
                                    DT.FTXpdBarCode,
                                    DT.FCXpdQtyLef AS FCXtdQty,
                                    DT.FCXpdQtyLef*DT.FCXpdFactor AS FCXtdQtyAll,
                                    0 as FCXpdQtyLef,
                                    0 as FCXpdQtyRfn,
                                    '' as FTXpdStaPrcStk,
                                    PDT.FTPdtStaAlwDis,
                                    0 as FNXpdPdtLevel,
                                    '' as FTXpdPdtParent,
                                    0 as FCXpdQtySet,
                                    '' as FTPdtStaSet,
                                    '' as FTXpdRmk,
                                    PDT.FTPdtType,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                                FROM
                                    TAPTPoDT DT WITH (NOLOCK)
                                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
                                AND  DT.FTXphDocNo = ".$this->db->escape($tRefIntDocNo)." 
                                AND DT.FNXpdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXpdSeqNo"])."
                                ";
                            
                            $oQuery = $this->db->query($tSQL);
                        }
                    }
                }
            }
        }else{
            $tSQL   = "
                INSERT INTO TSVTDODocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                    FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                )
                SELECT
                    '$tDOFrmBchCode' as FTBchCode,
                    '$tDODocNo' as FTXphDocNo,
                    DT.FNXpdSeqNo,
                    'TAPTDoDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    DT.FCXpdQtyLef AS FCXtdQty,
                    DT.FCXpdQtyLef AS FCXtdQtyAll,
                    0 as FCXpdQtyLef,
                    0 as FCXpdQtyRfn,
                    '' as FTXpdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXpdPdtLevel,
                    '' as FTXpdPdtParent,
                    0 as FCXpdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXpdRmk,
                    PDT.FTPdtType,
                    '".$this->session->userdata('tSesSessionID')."' AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDCreateOn,
                    '".$this->session->userdata('tSesUsername')."'  AS FTLastUpdBy,
                    '".$this->session->userdata('tSesUsername')."'  AS FTCreateBy
                FROM TAPTPoDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXphDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXpdSeqNo IN $aSeqNo
                ";

            $oQuery = $this->db->query($tSQL);
        }
        unset($tDODocNo,$tDOFrmBchCode,$tDOOptionAddPdt,$tRefIntDocNo,$tRefIntBchCode,$tSessionID,$aSeqNo,$oQueryCheckTempDocType,$tClearDocDocRefTemp);
        unset($tSQLSelectDT,$oQuery,$tSQLGetSeqPDT,$oQuerySeq,$aResultDTSeq,$aResultDT,$nCountResultDT,$tSQL);
        unset($oQuery);
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDOCallRefIntABBDocInsertDTToTemp($paData, $ptDocType){
        $tDODocNo               = $paData['tDODocNo'];
        $tDOFrmBchCode          = $paData['tDOFrmBchCode'];
        $oQueryCheckTempDocType = $this->FSnMDOCheckTempDocType($paData);
        $tRefIntDocNo           = $paData['tRefIntDocNo'];
        $tRefIntBchCode         = $paData['tRefIntBchCode'];
        $aSeqNo                 = '(' . implode(',', $paData['aSeqNo']) .')';
        $tDOOptionAddPdt        = $paData['tDOOptionAddPdt']; 
        $tSessionID             = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        if ($oQueryCheckTempDocType['raItems'] == '') {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDODocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TSVTDODocDTTmp');
        }elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDODocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TSVTDODocDTTmp');

            //ลบรายการอ้างอิง
            $tClearDocDocRefTemp    = "
                DELETE FROM TSVTDODocHDRefTmp
                WHERE  TSVTDODocHDRefTmp.FTXthDocNo  = ".$this->db->escape($paData['tDODocNo'])."
                AND TSVTDODocHDRefTmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
            ";
            $this->db->query($tClearDocDocRefTemp);
        }

        $tSQL   = " INSERT INTO TSVTDODocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                        FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                    )
                    SELECT
                        '$tDOFrmBchCode' as FTBchCode,
                        '$tDODocNo' as FTXshDocNo,
                        DT.FNXsdSeqNo,
                        'TAPTDoDT' AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        DT.FCXsdQtyLef AS FCXtdQty,
                        DT.FCXsdQtyLef AS FCXtdQtyAll,
                        0 as FCXsdQtyLef,
                        0 as FCXsdQtyRfn,
                        '' as FTXsdStaPrcStk,
                        PDT.FTPdtStaAlwDis,
                        0 as FNXsdPdtLevel,
                        '' as FTXsdPdtParent,
                        0 as FCXsdQtySet,
                        '' as FTPdtStaSet,
                        '' as FTXsdRmk,
                        PDT.FTPdtType,
                        '".$this->session->userdata('tSesSessionID')."' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDCreateOn,
                        '".$this->session->userdata('tSesUsername')."'  AS FTLastUpdBy,
                        '".$this->session->userdata('tSesUsername')."'  AS FTCreateBy
                    FROM TPSTSalDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXsdSeqNo IN $aSeqNo ";
        $oQuery = $this->db->query($tSQL);
        
        unset($tDODocNo,$tDOFrmBchCode,$oQueryCheckTempDocType,$tRefIntDocNo,$tRefIntBchCode,$aSeqNo,$tDOOptionAddPdt,$tSessionID);
        unset($tSQLSelectDT,$tSQLGetSeqPDT,$aResultDTSeq,$aResultDT,$nCountResultDT);
        unset($oQuery);
    }

    public function FSnMDOCheckTempDocType($paData){
        $tSQL   = "
            SELECT
                Tmp.FTXthRefKey
            FROM TSVTDODocHDRefTmp Tmp WITH(NOLOCK)
            WHERE Tmp.FTXthDocNo    = ".$this->db->escape($paData['tDODocNo'])." 
            AND Tmp.FTXthDocKey     = ".$this->db->escape($paData['tDocKey'])."
            AND Tmp.FTSessionID     = ".$this->db->escape($paData['tSessionID'])."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'raItems'   => '',
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($oQuery);
        unset($tSQL);
        unset($oDataList);
        return $aResult;
    }

    // Delete Purchase Invoice Document
    public function FSnMDODelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode   = $paDataDoc['tBchCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TAPTDoHD');

        // Document DT
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TAPTDoDT');

        // Document HD
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TAPTDoHDSpl');

        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TAPTDoHDDocRef');

        $this->db->where('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TAPTPoHDDocRef');

        $this->db->where('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TPSTSalHDDocRef');


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
        return $aStaDelDoc;
    }

    // Cancel Document Data
    public function FSaMDOCancelDocument($paDataUpdate){
        // TAPTPoHD
        $this->db->trans_begin();
        //$this->db->set('FTXphStaApv' , ' ');
        $this->db->set('FTXphStaDoc' , '3');
        $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TAPTDoHD');

        // (ย้ายไป ลบตอน หลังอนุมัติที่ C# แทน เพราะต้องหา QTY แบบย้อนกลับ)
        // $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo'])->delete('TAPTDoHDDocRef');
        // $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo'])->delete('TAPTPoHDDocRef');

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

    //อนุมัตเอกสาร
    public function FSaMDOApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');
        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXphStaApv',$paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode',$paDataUpdate['FTXphUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TAPTDoHD');
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

    public function FSaMDOUpdatePOStaPrcDoc($ptRefInDocNo){
        $nStaPrcDoc = 1;
        $this->db->set('FTXphStaPrcDoc',$nStaPrcDoc);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TAPTPoHD');
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
        return $aStatus;
    }

    public function FSaMDOUpdatePOStaRef($ptRefInDocNo, $pnStaRef){
        $this->db->set('FNXphStaRef',$pnStaRef);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TAPTPoHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Updated Status Document Success.',
            );
        } else {
            $aStatus    = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    public function FSaMDOQaAddUpdateRefDocHD($aDataWhere, $aTableAddUpdate, $aDataWhereDocRefDO, $aDataWhereDocRefPO, $aDataWhereDocRefDOExt){
        try {
            $tTableRefDO    = $aTableAddUpdate['tTableRefDO'];
            $tTableRefPO    = $aTableAddUpdate['tTableRefPO'];
            if ($aDataWhereDocRefDO != '') {
                $nChhkDataDocRefDO  = $this->FSaMDOChkRefDupicate($aDataWhere, $tTableRefDO, $aDataWhereDocRefDO);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefDO['rtCode']) && $nChhkDataDocRefDO['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$aDataWhereDocRefDO['FTAgnCode']);
                    $this->db->where('FTBchCode',$aDataWhereDocRefDO['FTBchCode']);
                    $this->db->where('FTXshDocNo',$aDataWhereDocRefDO['FTXshDocNo']);
                    $this->db->where('FTXshRefType',$aDataWhereDocRefDO['FTXshRefType']);
                    $this->db->where('FTXshRefDocNo',$aDataWhereDocRefDO['FTXshRefDocNo']);
                    $this->db->delete($tTableRefDO);
                    $this->db->last_query();
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefDO,$aDataWhereDocRefDO);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefDO,$aDataWhereDocRefDO);
                }
            }
            if ($aDataWhereDocRefPO != '') {
                $nChhkDataDocRefPO  = $this->FSaMDOChkRefDupicate($aDataWhere, $tTableRefPO, $aDataWhereDocRefPO);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefPO['rtCode']) && $nChhkDataDocRefPO['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$aDataWhereDocRefPO['FTAgnCode']);
                    $this->db->where('FTBchCode',$aDataWhereDocRefPO['FTBchCode']);
                    $this->db->where('FTXshDocNo',$aDataWhereDocRefPO['FTXshDocNo']);
                    $this->db->where('FTXshRefType',$aDataWhereDocRefPO['FTXshRefType']);
                    $this->db->where('FTXshRefDocNo',$aDataWhereDocRefPO['FTXshRefDocNo']);
                    $this->db->delete($tTableRefPO);
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefPO,$aDataWhereDocRefPO);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefPO,$aDataWhereDocRefPO);
                }
            }
            if ($aDataWhereDocRefDOExt != '') {
                $nChhkDataDocRefExt  = $this->FSaMDOChkRefDupicate($aDataWhere, $tTableRefDO, $aDataWhereDocRefDOExt);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$aDataWhereDocRefDOExt['FTAgnCode']);
                    $this->db->where('FTBchCode',$aDataWhereDocRefDOExt['FTBchCode']);
                    $this->db->where('FTXshDocNo',$aDataWhereDocRefDOExt['FTXshDocNo']);
                    $this->db->where('FTXshRefType',$aDataWhereDocRefDOExt['FTXshRefType']);
                    $this->db->where('FTXshRefDocNo',$aDataWhereDocRefDOExt['FTXshRefDocNo']);
                    $this->db->delete($tTableRefDO);
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefDO,$aDataWhereDocRefDOExt);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefDO,$aDataWhereDocRefDOExt);
                }
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
        unset($tTableRefDO);
        unset($tTableRefPO);
        unset($nChhkDataDocRefDO);
        unset($nChhkDataDocRefPO);
        unset($nChhkDataDocRefExt);
        return $aReturnData;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMDOChkRefDupicate($aDataWhere, $tTableRef, $aDataWhereDocRef){
        try{
            $tAgnCode       = $aDataWhereDocRef['FTAgnCode'];
            $tBchCode       = $aDataWhereDocRef['FTBchCode'];
            $tDocNo         = $aDataWhereDocRef['FTXshDocNo'];
            $tRefDocType    = $aDataWhereDocRef['FTXshRefType'];
            $tRefDocNo      = $aDataWhereDocRef['FTXshRefDocNo'];
            $tSQL           = "
                SELECT
                    FTAgnCode,
                    FTBchCode,
                    FTXshDocNo
                FROM $tTableRef WITH(NOLOCK)
                WHERE FTXshDocNo    = ".$this->db->escape($tDocNo)."
                AND FTAgnCode       = ".$this->db->escape($tAgnCode)."
                AND FTBchCode       = ".$this->db->escape($tBchCode)."
                AND FTXshRefType    = ".$this->db->escape($tRefDocType)."
                AND FTXshRefDocNo   = ".$this->db->escape($tRefDocNo)."
            ";
            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aDetail    = $oQueryHD->row_array();
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
            unset($tRefDocType);
            unset($tRefDocNo);
            unset($tRefDocNo);
            unset($tSQL);
            unset($oQueryHD);
            unset($aDetail);
            return $aResult;
        }catch (Exception $Error) {
            echo $Error;
        }
    }
   
    public function FSaMDOUpdatePOFNStaRef($ptBchCode,$ptRefInDocNo){
        $tSQL   = "
            UPDATE POHD
            SET POHD.FNXphStaRef = PODT.FNXphStaRef
            FROM TAPTPoHD POHD
            INNER JOIN (
                SELECT
                    CHKDTPO.FTBchCode,
                    CHKDTPO.FTXphDocNo,
                    CASE WHEN CHKDTPO.FNSumQtyLef = '0' THEN '2' ELSE '1' END AS FNXphStaRef
                FROM (
                    SELECT
                        PODT.FTBchCode,
                        PODT.FTXphDocNo,
                        SUM(PODT.FCXpdQtyLef) AS FNSumQtyLef
                    FROM TAPTPoDT PODT WITH(NOLOCK)
                    WHERE PODT.FTBchCode    = ".$this->db->escape($ptBchCode)." AND PODT.FTXphDocNo = ".$this->db->escape($ptRefInDocNo)."
                    GROUP BY PODT.FTBchCode,PODT.FTXphDocNo
                ) CHKDTPO
            ) PODT ON POHD.FTBchCode = PODT.FTBchCode AND POHD.FTXphDocNo = PODT.FTXphDocNo
        ";
        $this->db->query($tSQL);
    }

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMDOGetDataHDRefTmp($paData){
        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FTSessionID    = $paData['FTSessionID'];
        $tSQL           = "
            SELECT FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
            FROM $tTableTmpHDRef WITH(NOLOCK)
            WHERE FTXthDocNo  = ".$this->db->escape($FTXthDocNo)."
            AND FTXthDocKey = ".$this->db->escape($FTXthDocKey)."
            AND FTSessionID = ".$this->db->escape($FTSessionID)."
        ";

        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult    = array(
                'aItems'   => $oQuery->result_array(),
                'tCode'    => '1',
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        unset($oQuery);
        return $aResult;

    }

    // แท็บค่าอ้างอิงเอกสาร - เพิ่ม
    public function FSaMDOAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tRefDocNo = ( empty($paDataWhere['tSORefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tSORefDocNoOld'] );
        // $tRefDocNo = $paDataAddEdit['FTXthRefDocNo'];

        $tSQL = " SELECT FTXthRefDocNo FROM TSVTDODocHDRefTmp WITH(NOLOCK)
                    WHERE FTXthDocNo    = ".$this->db->escape($paDataWhere['FTXthDocNo'])."
                    AND FTXthDocKey   = ".$this->db->escape($paDataWhere['FTXthDocKey'])."
                    AND FTSessionID   = ".$this->db->escape($paDataWhere['FTSessionID'])."
                    AND FTXthRefDocNo = ".$this->db->escape($tRefDocNo)."
                ";
        $oQuery = $this->db->query($tSQL);
        
        $this->db->trans_begin();
        if ( $oQuery->num_rows() > 0 ){
            $this->db->where('FTXthRefDocNo',$paDataWhere['tSORefDocNoOld']);
            $this->db->where('FTXthDocNo',$paDataWhere['FTXthDocNo']);
            $this->db->where('FTXthDocKey',$paDataWhere['FTXthDocKey']);
            $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
            $this->db->update('TSVTDODocHDRefTmp',$paDataAddEdit);
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Update Doc Ref Success'
            );
        }else{
            $aDataAdd = array_merge($paDataAddEdit,array(
                'FTXthDocNo'  => $paDataWhere['FTXthDocNo'],
                'FTXthDocKey' => $paDataWhere['FTXthDocKey'],
                'FTSessionID' => $paDataWhere['FTSessionID'],
                'FDCreateOn'  => $paDataWhere['FDCreateOn'],
            ));
            $this->db->insert('TSVTDODocHDRefTmp',$aDataAdd);
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Add/Edit HDDocRef Success'
            );
        }

        if ( $this->db->trans_status() === FALSE ) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => 'Add/Edit HDDocRef Error'
            );
        } else {
            $this->db->trans_commit();
        }
        return $aResult;
    }

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMDOMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate,$pnDocType){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        $this->db->where('FTXshDocNo',$tDocNo);
        $this->db->delete('TAPTDoHDDocRef');

        $tSQL   =   "   INSERT INTO TAPTDoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthDocNo,
                            FTXthRefDocNo,
                            FTXthRefType,
                            FTXthRefKey,
                            FDXthRefDocDate
                        FROM TSVTDODocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)." 
                    ";
        
        $this->db->query($tSQL);

        if ($pnDocType == 1) {
            $tTableInsert = 'TAPTPoHDDocRef';
            $tTableInsertField = 'FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
        }else{
            $tTableInsert = 'TPSTSalHDDocRef';
            $tTableInsertField = 'FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
        }
        
        //Insert PO or ABB
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete($tTableInsert);

        $tSQL   =   "   INSERT INTO $tTableInsert ($tTableInsertField) ";
        if ($pnDocType == 1) {
            $tDocKey = 'PO';
            $tSQL   .=  "SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'DO',
                            FDXthRefDocDate
                        FROM TSVTDODocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)."
                            AND FTXthRefKey = ".$this->db->escape($tDocKey)."  
                        ";
        }else{
            $tDocKey = 'ABB';
            $tSQL   .=  "SELECT
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'DO',
                            FDXthRefDocDate
                        FROM TSVTDODocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)."
                            AND FTXthRefKey = ".$this->db->escape($tDocKey)."  
                        ";
        }
        
        $this->db->query($tSQL);
    }

    //ข้อมูล HDDocRef
    public function FSxMDOMoveHDRefToHDRefTemp($paData){

        $FTXshDocNo     = $paData['FTXphDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey','TAPTDoHD');
        $this->db->where('FTSessionID',$FTSessionID);
        $this->db->delete('TSVTDODocHDRefTmp');

        $tSQL = "   INSERT INTO TSVTDODocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                        FTXshDocNo,
                        FTXshRefDocNo,
                        FTXshRefType,
                        FTXshRefKey,
                        FDXshRefDocDate,
                        'TAPTDoHD' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                    FROM TAPTDoHDDocRef WITH (NOLOCK)
                    WHERE FTXshDocNo = ".$this->db->escape($FTXshDocNo)." ";
        $this->db->query($tSQL);
    }

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMDODelHDDocRef($paData){
        $tSODocNo       = $paData['FTXthDocNo'];
        $tSORefDocNo    = $paData['FTXthRefDocNo'];
        $tSODocKey      = $paData['FTXthDocKey'];
        $tSOSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tSOSessionID);
        $this->db->where('FTXthDocKey',$tSODocKey);
        $this->db->where('FTXthRefDocNo',$tSORefDocNo);
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TSVTDODocHDRefTmp');

        if ( $this->db->trans_status() === FALSE ) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => 'Delete HD Doc Ref Error'
            );
        } else {
            $this->db->trans_commit();
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Delete HD Doc Ref Success'
            );
        }
        return $aResult;
    }
}
