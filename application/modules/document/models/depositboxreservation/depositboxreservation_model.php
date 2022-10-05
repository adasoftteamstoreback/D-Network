<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depositboxreservation_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMDBRGetDataTableList($paDataCondition){
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
                COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY C.FTXshDocNo)  AS PARTITIONBYDBRC, 
                HDDocRef_in.FTXshRefDocNo   AS 'DBRCREF',
                CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)   AS 'DATEREF'
            FROM(
                SELECT DISTINCT
                    DBRHD.FTBchCode,
                    BCHL.FTBchName,
                    DBRHD.FTXshDocNo,
                    CONVERT(CHAR(10),DBRHD.FDXshDocDate,103) AS FDXshDocDate,
                    CONVERT(CHAR(5), DBRHD.FDXshDocDate,108) AS FTXshDocTime,
                    DBRHD.FTXshStaDoc,
                    DBRHD.FTXshStaApv,
                    DBRHD.FTCreateBy,
                    DBRHD.FDCreateOn,
                    DBRHD.FNXshStaDocAct,
                    USRL.FTUsrName      AS FTCreateByName,
                    USRLR.FTUsrName AS FTDepositByName,
                    CST.FTCstName,
                    DBRHD.FTXshApvCode,
                    USRLAPV.FTUsrName   AS FTXshApvName
                FROM TRTTBookHD DBRHD WITH (NOLOCK)
                LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON DBRHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON DBRHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRLR   WITH (NOLOCK) ON DBRHD.FTUsrCode    = USRLR.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)." 
                LEFT JOIN TCNMCst_L     CST     WITH (NOLOCK) ON DBRHD.FTCstCode  = CST.FTCstCode AND CST.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON DBRHD.FTXshApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                WHERE DBRHD.FDCreateOn <> ''
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= "
                AND DBRHD.FTBchCode IN ($tBchCode)
            ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode   = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL               .= " AND DBRHD.FTShpCode = ".$this->db->escape($tUserLoginShpCode)."";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((DBRHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),DBRHD.FDXshDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((DBRHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (DBRHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((DBRHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DBRHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DBRHD.FTXshStaDoc = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DBRHD.FTXshStaApv,'') = '' AND DBRHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaDoc)." AND DBRHD.FTXshStaDoc != '3'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)." OR DBRHD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)."";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DBRHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND DBRHD.FNXshStaDocAct = 0";
            }
        }

        $tSQL   .= " 
            ) AS C
            LEFT JOIN TRTTBookHDDocRef HDDocRef_in WITH (NOLOCK) ON C.FTXshDocNo = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1
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
    public function FSnMDBRCountPageDocListAll($paDataCondition){
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

        $tSQL   =   "   SELECT COUNT (DBRHD.FTXshDocNo) AS counts
                        FROM TRTTBookHD DBRHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON DBRHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TRTTBookHDDocRef DBRHD_REF WITH (NOLOCK) ON DBRHD.FTXshDocNo  = DBRHD_REF.FTXshDocNo AND DBRHD_REF.FTXshRefType = '1'
                        WHERE DBRHD.FDCreateOn <> ''
                    ";

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND DBRHD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND DBRHD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((DBRHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),DBRHD.FDXshDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((DBRHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (DBRHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((DBRHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (DBRHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        
        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND DBRHD.FTXshStaDoc = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(DBRHD.FTXshStaApv,'') = '' AND DBRHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaDoc)."";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)." OR DBRHD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND DBRHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)."";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND DBRHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND DBRHD.FNXshStaDocAct = 0";
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
    public function FSxMDBRFindSPLByConfig(){
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

    public function FSaMDBRGetDetailUserBranch($paBchCode){
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
        $this->db->delete('TRTTBookDocDTTmp');
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
    public function FSxMDBRClearDataInDocTemp($paWhereClearTemp){
        $tDBRDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tDBRDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tDBRSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "
            DELETE FROM TRTTBookDocDTTmp
            WHERE TRTTBookDocDTTmp.FTXthDocNo = ".$this->db->escape($tDBRDocNo)."
            AND TRTTBookDocDTTmp.FTXthDocKey  = ".$this->db->escape($tDBRDocKey)."
            AND TRTTBookDocDTTmp.FTSessionID  = ".$this->db->escape($tDBRSessionID)."
        ";
        $this->db->query($tClearDocTemp);

        // Query Delete DocRef Temp
        $tClearDocDocRefTemp    =  "
            DELETE FROM TRTTBookDocHDRefTmp
            WHERE TRTTBookDocHDRefTmp.FTXthDocNo  = ".$this->db->escape($tDBRDocNo)."
            AND TRTTBookDocHDRefTmp.FTSessionID   = ".$this->db->escape($tDBRSessionID)."
        ";
        $this->db->query($tClearDocDocRefTemp);
        unset($tDBRDocNo);
        unset($tDBRDocKey);
        unset($tDBRSessionID);
        unset($tClearDocTemp);
        unset($tClearDocDocRefTemp);
    }

    // Functionality : Delete Delivery Order Document
    public function FSxMDBRClearDataInDocTempForImp($paWhereClearTemp){
        $tDBRDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tDBRDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tDBRSessionID   = $paWhereClearTemp['FTSessionID'];
        // Query Delete DocTemp
        $tClearDocTemp  =   "
            DELETE FROM TRTTBookDocDTTmp 
            WHERE TRTTBookDocDTTmp.FTXthDocNo = ".$this->db->escape($tDBRDocNo)."
            AND TRTTBookDocDTTmp.FTXthDocKey  = ".$this->db->escape($tDBRDocKey)."
            AND TRTTBookDocDTTmp.FTSessionID  = ".$this->db->escape($tDBRSessionID)."
            AND TRTTBookDocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
        unset($tDBRDocNo);
        unset($tDBRDocKey);
        unset($tDBRSessionID);
    }

    // Function: Get ShopCode From User Login
    public function FSaMDBRGetShpCodeForUsrLogin($paDataShp){
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
    public function FSaMDBRGetDefOptionConfigWah($paConfigSys){
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
    public function FSaMDBRGetDocDTTempListPage($paDataWhere){
        $tDBRBchCode         = $paDataWhere['FTBchCode'];
        $tDBRDocNo           = $paDataWhere['FTXthDocNo'];
        $tDBRDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable  = $paDataWhere['tSearchPdtAdvTable'];
        $nLngID              = $paDataWhere['FNLngID'];
        $tDBRSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL               = " 
            SELECT DISTINCT
                SPOS.FTPshType,
                DTTMP.FTBchCode,
                DTTMP.FTXthDocNo,
                DTTMP.FNXtdSeqNo,
                DTTMP.FTXthDocKey,
                DTTMP.FTPdtCode,
                DTTMP.FTXtdPdtName,
                DTTMP.FTPunName,
                DTTMP.FTXtdBarCode,
                DTTMP.FTPunCode,
                DTTMP.FCXtdFactor,
                DTTMP.FCXtdQty,
                DTTMP.FCXtdSetPrice,
                DTTMP.FCXtdAmtB4DisChg,
                DTTMP.FTXtdDisChgTxt,
                DTTMP.FCXtdNet,
                DTTMP.FCXtdNetAfHD,
                DTTMP.FTXtdStaAlwDis,
                DTTMP.FTTmpRemark,
                DTTMP.FCXtdVatRate,
                DTTMP.FTXtdVatType,
                DTTMP.FTSrnCode,
                DTTMP.FTTmpStatus,
                DTTMP.FDLastUpdOn,
                DTTMP.FDCreateOn,
                DTTMP.FTLastUpdBy,
                DTTMP.FTCreateBy,
                DTTMP.FTPosCode,
                POSL.FTPosName,
                DTTMP.FTShpCode,
                DTTMP.FTLayNo,
                LAY.FTLayName,
                DTTMP.FTXtdRmkInRow
            FROM TRTTBookDocDTTmp DTTMP WITH (NOLOCK)
            LEFT JOIN TRTMShopPos SPOS WITH ( NOLOCK ) ON SPOS.FTBchCode = ".$this->db->escape($tDBRBchCode)." AND DTTMP.FTPosCode = SPOS.FTPosCode AND DTTMP.FTShpCode = SPOS.FTShpCode
            LEFT JOIN TCNMPos_L POSL WITH(NOLOCK) ON SPOS.FTBchCode =  POSL.FTBchCode AND DTTMP.FTPosCode = POSL.FTPosCode AND POSL.FNLngID	= ".$this->db->escape($nLngID)."
            LEFT JOIN TRTMShopLayout_L LAY WITH ( NOLOCK ) ON LAY.FTBchCode = ".$this->db->escape($tDBRBchCode)."  AND DTTMP.FTShpCode = LAY.FTShpCode AND DTTMP.FTLayNo = LAY.FNLayNo AND LAY.FNLngID	= ".$this->db->escape($nLngID)."
            WHERE DTTMP.FTXthDocKey = ".$this->db->escape($tDBRDocKey)."
            AND DTTMP.FTSessionID = ".$this->db->escape($tDBRSesSessionID)."
        ";

        
        if(isset($tDBRDocNo) && !empty($tDBRDocNo)){
            $tSQL   .=  "   AND ISNULL(DTTMP.FTXthDocNo,'')  = ".$this->db->escape($tDBRDocNo)." ";
        }
        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "   
                AND (
                    DTTMP.FTPdtCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DTTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DTTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
                    OR DTTMP.FTPunName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%' )
            ";
        }
        $tSQL   .= " ORDER BY DTTMP.FNXtdSeqNo ASC";
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
        unset($tDBRDocNo);
        unset($tDBRDocKey);
        unset($tSearchPdtAdvTable);
        unset($tDBRSesSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        unset($paDataWhere);
        return $aDataReturn;
    }

    //Get Data Pdt
    public function FSaMDBRGetDataPdt($paDataPdtParams){
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
    public function FSaMDBRInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tDBROptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "
                SELECT
                    FNXtdSeqNo,
                    FCXtdQty
                FROM TRTTBookDocDTTmp WITH (NOLOCK)
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
                    UPDATE TRTTBookDocDTTmp
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
                        'FTLastUpdBy'       => $paDataPdtParams['tDBRUsrCode'],
                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                        'FTCreateBy'        => $paDataPdtParams['tDBRUsrCode'],
                    );
                    $this->db->insert('TRTTBookDocDTTmp',$aDataInsert);
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
                'FTLastUpdBy'       => $paDataPdtParams['tDBRUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tDBRUsrCode'],
            );
            $this->db->insert('TRTTBookDocDTTmp',$aDataInsert);
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
    public function FSnMDBRDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where('FTXthDocNo',$paDataWhere['tDBRDocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TRTTBookDocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMDBRDelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where('FTXthDocNo',$paDataWhere['tDBRDocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TRTTBookDocDTTmp');
        return ;
    }

    // Update Document DT Temp by Seq
    public function FSaMDBRUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where('FTSessionID',$paDataWhere['tDBRSessionID']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nDBRSeqNo']);
        if ($paDataWhere['tDBRDocNo'] != '' && $paDataWhere['tDBRBchCode'] != '') {
            $this->db->where('FTXthDocNo',$paDataWhere['tDBRDocNo']);
            $this->db->where('FTBchCode',$paDataWhere['tDBRBchCode']);
        }
        $this->db->update('TRTTBookDocDTTmp', $paDataUpdateDT);

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
    public function FSnMDBRChkPdtInDocDTTemp($paDataWhere){
        $tDBRDocNo       = $paDataWhere['FTXthDocNo'];
        $tDBRDocKey      = $paDataWhere['FTXthDocKey'];
        $tDBRSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " 
            SELECT
                COUNT(FNXtdSeqNo) AS nCountPdt
            FROM TRTTBookDocDTTmp DocDT WITH (NOLOCK)
            WHERE DocDT.FTXthDocKey = ".$this->db->escape($tDBRDocKey)."
            AND DocDT.FTSessionID   = ".$this->db->escape($tDBRSessionID)."
        ";
        if(isset($tDBRDocNo) && !empty($tDBRDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'') = ".$this->db->escape($tDBRDocNo)."";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            unset($tDBRDocNo);
            unset($tDBRDocKey);
            unset($tDBRSessionID);
            unset($tSQL);
            unset($oQuery);
            return $aDataQuery['nCountPdt'];
        }else{
            unset($tDBRDocNo);
            unset($tDBRDocKey);
            unset($tDBRSessionID);
            unset($tSQL);
            unset($oQuery);
            return 0;
        }
    }

    // Function : Count Check DocRef Before Cancel
    public function FSaMDBRCheckIVRef($ptDocNo){
        $tDBRDocNo   = $ptDocNo;
        $tSQL       = "
            SELECT
                COUNT(FTXshRefDocNo) AS nCount
            FROM TAPTPiHDDocRef DocDT WITH (NOLOCK)
            WHERE DocDT.FTXshRefDocNo   = ".$this->db->escape($tDBRDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            unset($tDBRDocNo);
            unset($oQuery);
            return $aDataQuery['nCount'];
        }else{
            unset($tDBRDocNo);
            unset($oQuery);
            return 0;
        }
    }

    // อ้างอิงเอกสาร ใบสั่งขาย
    public function FSoMDBRCallRefSOIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDBRRefIntBchCode        = $aAdvanceSearch['tDBRRefIntBchCode'];
        $tDBRRefIntDocNo          = $aAdvanceSearch['tDBRRefIntDocNo'];
        $tDBRRefIntDocDateFrm     = $aAdvanceSearch['tDBRRefIntDocDateFrm'];
        $tDBRRefIntDocDateTo      = $aAdvanceSearch['tDBRRefIntDocDateTo'];
        $tDBRRefIntStaDoc         = $aAdvanceSearch['tDBRRefIntStaDoc'];
        $tDBRSplCode              = $aAdvanceSearch['tDBRSplCode'];

        $tSQLMain   = "
            SELECT DISTINCT 
                SOHD.FTBchCode,
                BCHL.FTBchName,
                SOHD.FTXshDocNo,
                CONVERT(CHAR(16),SOHD.FDXshDocDate,121) AS FDXshDocDate,
                CONVERT(CHAR(5), SOHD.FDXshDocDate,108) AS FTXshDocTime,
                SOHD.FTXshStaDoc,
                SOHD.FTXshStaApv,
                SOHD.FNXshStaRef,
                SOHD.FTXshVATInOrEx,
                SOHD.FTCreateBy,
                SOHD.FDCreateOn,
                SOHD.FNXshStaDocAct,
                USRL.FTUsrName      AS FTCreateByName,
                SOHD.FTXshApvCode,
                SOHD.FTCstCode,
                CSTL.FTCstName,
                CST.FTCstTel,
                CST.FTCstEmail
            FROM TARTSoHD           SOHD    WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON SOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMCst       CST    WITH (NOLOCK)  ON SOHD.FTCstCode     = CST.FTCstCode     
            LEFT JOIN TCNMCst_L     CSTL    WITH (NOLOCK) ON SOHD.FTCstCode     = CSTL.FTCstCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TARTSoHDDocRef SO_R   WITH (NOLOCK) ON SOHD.FTXshDocNo    = SO_R.FTXshDocNo   AND SOHD.FTBchCode = SO_R.FTBchCode
            WHERE ISNULL(SO_R.FTXshRefDocNo, '') = '' ";

        if(isset($tDBRRefIntBchCode) && !empty($tDBRRefIntBchCode)){
            $tSQLMain .= " AND (SOHD.FTBchCode = ".$this->db->escape($tDBRRefIntBchCode).")";
        }

        // if(isset($tDBRSplCode) && !empty($tDBRSplCode)){
        //     $tSQLMain .= " AND (SOHD.FTSplCode = ".$this->db->escape($tDBRSplCode).")";
        // }

        if(isset($tDBRRefIntDocNo) && !empty($tDBRRefIntDocNo)){
            $tSQLMain .= " AND (SOHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tDBRRefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDBRRefIntDocDateFrm) && !empty($tDBRRefIntDocDateTo)){
            $tSQLMain .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDBRRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDBRRefIntDocDateTo 23:59:59')) OR (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDBRRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDBRRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDBRRefIntStaDoc) && !empty($tDBRRefIntStaDoc)){
            if ($tDBRRefIntStaDoc == 3) {
                $tSQLMain .= " AND SOHD.FTXshStaDoc = ".$this->db->escape($tDBRRefIntStaDoc);
            } elseif ($tDBRRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != ".$this->db->escape(3);
            } elseif ($tDBRRefIntStaDoc == 1) {
                $tSQLMain .= " AND SOHD.FTXshStaApv = ".$this->db->escape($tDBRRefIntStaDoc)." AND SOHD.FTXshStaDoc != ".$this->db->escape(3); 
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
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

    public function FSnMDBRGetMainSpl($paDataCondition){
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
    public function FSoMDBRCallRefIntDocDTDataTable($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
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
                DT.FCXsdQtyLef AS FCXsdQty,
                DT.FCXsdQtyAll,
                DT.FTXsdRmk,
                /*CASE WHEN ISNULL(DT.FCXsdQtySo,0) = '0' THEN  DT.FCXsdQtyLef ELSE ISNULL(DT.FCXsdQtySo,0) END AS FCXsdQtySo,*/
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy
                FROM TARTSoDT DT WITH(NOLOCK)
                LEFT JOIN TARTSoHD HD WITH (NOLOCK) ON DT.FTXshDocNo  = HD.FTXshDocNo
            WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tDocNo)."
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
    public function FSoMDBRCallRefIntDocABBDTDataTable($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
            SELECT
                DT.FTBchCode,
                DT.FTXshDocNo AS FTXshDocNo,
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
    public function FSxMDBRAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMDBRGetDataDocHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->input->post("ohdDBRLangEdit")
        ));
        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDBRld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDBRld['DateOn'],
                'FTCreateBy'    => $aDataHDBRld['CreateBy']
            ));
            // update HD
            $this->db->where('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
            $this->db->where('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
            $this->db->update($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
            // Insert PI HD Dis
            $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        }
        unset($aDataGetDataHD);
        unset($aDataHDBRld);
        unset($aDataAddUpdateHD);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TRTTBookDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMDBRAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TRTTBookDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into TRTTBookDocHDRefTmp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTXthDocKey','TRTTBookHD');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TRTTBookDocHDRefTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo']
        ));

        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMDBRMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tDBRBchCode     = $paDataWhere['FTBchCode'];
        $tDBRDocNo       = $paDataWhere['FTXshDocNo'];
        $tDBRDocKey      = $paTableAddUpdate['tTableDT'];
        $tDBRSessionID   = $paDataWhere['FTSessionID'];

        if(isset($tDBRDocNo) && !empty($tDBRDocNo)){
            $this->db->where('FTXshDocNo',$tDBRDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FTPdtCode,FTXsdPdtName,FTPunCode,FTPunName,FCXsdFactor,FTXsdBarCode,
                        FCXsdQty,FCXsdQtyAll,FTXsdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            DBRCTMP.FTBchCode,
                            DBRCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DBRCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DBRCTMP.FTPdtCode,
                            DBRCTMP.FTXtdPdtName,
                            DBRCTMP.FTPunCode,
                            DBRCTMP.FTPunName,
                            DBRCTMP.FCXtdFactor,
                            DBRCTMP.FTXtdBarCode,
                            DBRCTMP.FCXtdQty,
                            DBRCTMP.FCXtdQty * DBRCTMP.FCXtdFactor AS FCXpdQtyAll,
                            DBRCTMP.FTXtdRmkInRow,
                            DBRCTMP.FDLastUpdOn,
                            DBRCTMP.FTLastUpdBy,
                            DBRCTMP.FDCreateOn,
                            DBRCTMP.FTCreateBy
                        FROM TRTTBookDocDTTmp DBRCTMP WITH (NOLOCK)
                        WHERE  DBRCTMP.FTXthDocNo    = ".$this->db->escape($tDBRDocNo)."
                        AND DBRCTMP.FTBchCode        = ".$this->db->escape($tDBRBchCode)."
                        AND DBRCTMP.FTXthDocKey      = ".$this->db->escape($tDBRDocKey)."
                        AND DBRCTMP.FTSessionID      = ".$this->db->escape($tDBRSessionID)."
                        ORDER BY DBRCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        unset($tDBRBchCode);
        unset($tDBRDocNo);
        unset($tDBRDocKey);
        unset($tDBRSessionID);
        unset($oQuery);
        return;
    }


    // Function Move Document DTTemp To Document DT
    public function FSaMDBRMoveSoCstToBook($paDataWhere,$paTableAddUpdate){
        $tDBRBchCode     = $paDataWhere['FTBchCode'];
        $tDBRDocNo       = $paDataWhere['FTXshDocNo'];
        $tDBRDocKey      = $paTableAddUpdate['tTableDT'];
        $tDBRSessionID   = $paDataWhere['FTSessionID'];

        

        $tSQL   = " SELECT
                        REF.FTXshRefDocNo 
                    FROM TRTTBookHDDocRef REF
                    WHERE  REF.FTXshRefKey = 'SO' AND REF.FTXshDocNo = '$tDBRDocNo' ";
        $oQuery = $this->db->query($tSQL);
        $aGetSO = $oQuery->result_array();

        $tSoDoc = $aGetSO[0]['FTXshRefDocNo'];


        $tSQL   = " INSERT INTO TRTTBookHDCst (
                        FTAgnCode,FTBchCode,FTXshDocNo,FTXshCardID,FTXshCstTel,FTXshCardNo,FNXshCrTerm,FDXshDueDate
                        ,FDXshBillDue,FTXshCtrName,
                        FDXshTnfDate,FTXshRefTnfID,FNXshAddrShip,FTXshAddrTax ) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            SOCST.FTBchCode,
                            '$tDBRDocNo' AS FTXshDocNo,
                            SOCST.FTXshCardID,
                            SOCST.FTXshCstTel,
                            SOCST.FTXshCardNo,
                            SOCST.FNXshCrTerm,
                            SOCST.FDXshDueDate,
                            SOCST.FDXshBillDue,
                            SOCST.FTXshCtrName,
                            SOCST.FDXshTnfDate,
                            SOCST.FTXshRefTnfID,
                            SOCST.FNXshAddrShip,
                            SOCST.FTXshAddrTax
                        FROM TARTSoHDCst SOCST WITH (NOLOCK)
                        WHERE  SOCST.FTXshDocNo    = ".$this->db->escape($tSoDoc)."
        ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // Function Move Document DTTemp To Document DTSL
    public function FSxMDBRMoveDTTempToDTSL($paDataWhere,$paTableAddUpdate){
        $tDBRBchCode     = $paDataWhere['FTBchCode'];
        $tDBRDocNo       = $paDataWhere['FTXshDocNo'];
        $tDBRDocKey      = $paTableAddUpdate['tTableDT'];
        $tDBRSessionID   = $paDataWhere['FTSessionID'];

        if(isset($tDBRDocNo) && !empty($tDBRDocNo)){
            $this->db->where('FTXshDocNo',$tDBRDocNo);
            $this->db->delete($paTableAddUpdate['tTableDTSL']);
        }

        $tSQL   = " INSERT INTO TRTTBookDTSL (FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FTShpCode,
        FTPosCode,FTPdtCode,FNXsdLayNo,FTXsdStaDrop) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            DBRCTMP.FTBchCode,
                            DBRCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DBRCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DBRCTMP.FTShpCode,
                            DBRCTMP.FTPosCode,
                            DBRCTMP.FTPdtCode,
                            DBRCTMP.FTLayNo,
                            '2' AS FTXsdStaDrop
                        FROM TRTTBookDocDTTmp DBRCTMP WITH (NOLOCK)
                        WHERE  DBRCTMP.FTXthDocNo    = ".$this->db->escape($tDBRDocNo)."
                        AND DBRCTMP.FTBchCode        = ".$this->db->escape($tDBRBchCode)."
                        AND DBRCTMP.FTXthDocKey      = ".$this->db->escape($tDBRDocKey)."
                        AND DBRCTMP.FTSessionID      = ".$this->db->escape($tDBRSessionID)."
                        ORDER BY DBRCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);

        $tSQL2   =  "   UPDATE T1
                        SET T1.FTPdtCode  = T2.FtPdtCode
                        FROM TRTTBookDTSL T1 LEFT JOIN (
                        
                        SELECT 
                            DSL.FTAgnCode,
                            DSL.FTBchCode,
                            DSL.FTShpCode,
                            PRT.FTPdtCode,
                            DSL.FNXsdSeqNo
                        FROM TRTTBookDTSL DSL 
                        LEFt JOIN TRTMPdtRental PRT ON DSL.FTShpCode = PRT.FTShpCode 
                        WHERE FTXshDocNo = ".$this->db->escape($tDBRDocNo)."
                        
                        ) T2 ON T1.FTAgnCode = T2.FTAgnCode
                        AND T1.FTBchCode = T2.FTBchCode
                        AND T1.FTShpCode = T2.FTShpCode
                        AND T1.FNXsdSeqNo = T2.FNXsdSeqNo 
                        WHERE FTXshDocNo = ".$this->db->escape($tDBRDocNo)." ";
        $oQuery = $this->db->query($tSQL2);

        
        unset($tDBRBchCode);
        unset($tDBRDocNo);
        unset($tDBRDocKey);
        unset($tDBRSessionID);
        unset($oQuery);
        return;
    }

    // Function Move Document DTTemp To Document DTSL
    public function FSxMDBRMoveDTTempToDTSLSugges($ptDocNo,$tBchCode){

        if(isset($ptDocNo) && !empty($ptDocNo)){
            $this->db->where('FTXshDocNo',$ptDocNo);
            $this->db->delete('TRTTBookDTSL');
        }

        $tSQL   = " INSERT INTO TRTTBookDTSL (FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FTShpCode,
        FTPosCode,FTPdtCode,FNXsdLayNo,FTXsdStaDrop) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            DBRCTMP.FTBchCode,
                            DBRCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DBRCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DBRCTMP.FTShpCode,
                            DBRCTMP.FTPosCode,
                            DBRCTMP.FTPdtCode,
                            DBRCTMP.FTLayNo,
                            '2' AS FTXsdStaDrop
                        FROM TRTTBookDocDTTmp DBRCTMP WITH (NOLOCK)
                        WHERE  DBRCTMP.FTXthDocNo    = ".$this->db->escape($ptDocNo)."
                        AND DBRCTMP.FTBchCode        = ".$this->db->escape($tBchCode)."
                        AND DBRCTMP.FTXthDocKey      = 'TRTTBookDT'
                        AND DBRCTMP.FTSessionID      = ".$this->db->escape($this->session->userdata('tSesSessionID'))."
                        ORDER BY DBRCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        
        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMDBRMoveDtTmpToDtSugges($ptDocNo,$tBchCode){

        if(isset($tDBRDocNo) && !empty($tDBRDocNo)){
            $this->db->where('FTXshDocNo',$tDBRDocNo);
            $this->db->delete('TRTTBookDT');
        }

        $tSQL   = " INSERT INTO TRTTBookDT (
                        FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FTPdtCode,FTXsdPdtName,FTPunCode,FTPunName,FCXsdFactor,FTXsdBarCode,
                        FCXsdQty,FCXsdQtyAll,FTXsdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            DBRCTMP.FTBchCode,
                            DBRCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DBRCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DBRCTMP.FTPdtCode,
                            DBRCTMP.FTXtdPdtName,
                            DBRCTMP.FTPunCode,
                            DBRCTMP.FTPunName,
                            DBRCTMP.FCXtdFactor,
                            DBRCTMP.FTXtdBarCode,
                            DBRCTMP.FCXtdQty,
                            DBRCTMP.FCXtdQty * DBRCTMP.FCXtdFactor AS FCXpdQtyAll,
                            DBRCTMP.FTXtdRmkInRow,
                            DBRCTMP.FDLastUpdOn,
                            DBRCTMP.FTLastUpdBy,
                            DBRCTMP.FDCreateOn,
                            DBRCTMP.FTCreateBy
                        FROM TRTTBookDocDTTmp DBRCTMP WITH (NOLOCK)
                        WHERE  DBRCTMP.FTXthDocNo    = ".$this->db->escape($ptDocNo)."
                        AND DBRCTMP.FTBchCode        = ".$this->db->escape($tBchCode)."
                        AND DBRCTMP.FTXthDocKey      = 'TRTTBookDT'
                        AND DBRCTMP.FTSessionID      = ".$this->db->escape($this->session->userdata('tSesSessionID'))."
                        ORDER BY DBRCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);

        unset($oQuery);
        return;
    }

    // ข้อมูล HD
    public function FSaMDBRGetDataDocHD($paDataWhere){
        $tDBRDocNo   = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        // $tSQL       = " 
        //     SELECT
        //         DBRCHD.FTXshDocNo,
        //         DBRCHD.FDXshDocDate,
        //         DBRCHD.FTXshStaDoc,
        //         DBRCHD.FTXshStaApv,
        //         DBRCHD.FTXshApvCode,
        //         DBRCHD.FTXshRefInt,
        //         DBRCHD.FDXshRefIntDate,
        //         DBRCHD.FTXshRefExt,
        //         DBRCHD.FDXshRefExtDate,
        //         DBRCHD.FNXshStaRef,
        //         DBRCHD.FTWahCode,
        //         DBRCHD.FNXshStaDocAct,
        //         DBRCHD.FNXshDocPrint,
        //         DBRCHD.FTXshRmk,
        //         DBRCHD.FNXshStaDocAct,
        //         DBRCHD.FDCreateOn AS DateOn,
        //         DBRCHD.FTCreateBy AS CreateBy,
        //         USRCRE.FTUsrName AS CreateByName,
        //         DBRCHD.FTBchCode,
        //         BCHL.FTBchName,
        //         DBRCHD.FTUsrCode    AS FTUsrDepositCode,
        //         USRL.FTUsrName      AS FTUsrDepositName,
        //         USRAPV.FTUsrName	AS FTXshApvName,
        //         AGN.FTAgnCode       AS rtAgnCode,
        //         AGN.FTAgnName       AS rtAgnName,
        //         WAH_L.FTWahCode     AS rtWahCode,
        //         WAH_L.FTWahName     AS rtWahName,
        //         DBRCHD.FTCstCode,
        //         CSTL.FTCstName,
        //         CST.FTCstTel,
        //         CST.FTCstEmail,
        //         REF.FTXshRefDocNo   AS DOCREF,
        //         SAL.FTXshDocNo      AS SALDOCNO,
        //         SAL.FTBchCode       AS SALBchNO,
        //         SAL.FTXshGndText    AS SALGRANDTXT,
        //         TAX.FTXshDocNo      AS TAXDOCNO,
        //         TAX.FTBchCode       AS TAXBchNO,
        //         TAX.FTXshGndText    AS TAXGRANDTXT,
        //         DBRCHD.FDXshBookDate
        //     FROM TRTTBookHD DBRCHD WITH (NOLOCK)
        //     INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DBRCHD.FTBchCode      = BCH.FTBchCode
        //     LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMAgency_L      AGN     WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DBRCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DBRCHD.FTXshApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMUser_L        USRCRE	WITH (NOLOCK)   ON DBRCHD.FTCreateBy	= USRCRE.FTUsrCode	AND USRCRE.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK)   ON DBRCHD.FTBchCode      = WAH_L.FTBchCode  AND DBRCHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TCNMCst           CST	    WITH (NOLOCK)   ON DBRCHD.FTCstCode	= CST.FTCstCode
        //     LEFT JOIN TCNMCst_L         CSTL	WITH (NOLOCK)   ON DBRCHD.FTCstCode	= CSTL.FTCstCode	AND CSTL.FNLngID	= ".$this->db->escape($nLngID)."
        //     LEFT JOIN TRTTBookHDDocRef  REF	    WITH (NOLOCK)   ON REF.FTXshDocNo	= DBRCHD.FTXshDocNo	AND REF.FTXshRefKey	= 'SO'
        //     LEFT JOIN TARTSoHDDocRef    SOREF	WITH (NOLOCK)   ON REF.FTXshRefDocNo	= SOREF.FTXshDocNo	AND SOREF.FTXshRefKey	= 'ABB'
        //     LEFT JOIN TPSTSalHD         SAL	    WITH (NOLOCK)   ON SAL.FTXshDocNo	= SOREF.FTXshRefDocNo
        //     LEFT JOIN TPSTSalHDDocRef   SALREF	WITH (NOLOCK)   ON SAL.FTXshDocNo	= SALREF.FTXshDocNo AND SALREF.FTXshRefKey	= 'ABBFULLTAX'
        //     LEFT JOIN TPSTTaxHD         TAX	    WITH (NOLOCK)   ON TAX.FTXshDocNo	= SALREF.FTXshRefDocNo
        //     WHERE DBRCHD.FTXshDocNo = ".$this->db->escape($tDBRDocNo)."
        // ";
        $tSQL       = " 
        SELECT
            DBRCHD.FTXshDocNo,
            DBRCHD.FDXshDocDate,
            DBRCHD.FTXshStaDoc,
            DBRCHD.FTXshStaApv,
            DBRCHD.FTXshApvCode,
            DBRCHD.FTXshRefInt,
            DBRCHD.FDXshRefIntDate,
            DBRCHD.FTXshRefExt,
            DBRCHD.FDXshRefExtDate,
            DBRCHD.FNXshStaRef,
            DBRCHD.FTWahCode,
            DBRCHD.FNXshStaDocAct,
            DBRCHD.FNXshDocPrint,
            DBRCHD.FTXshRmk,
            DBRCHD.FNXshStaDocAct,
            DBRCHD.FDCreateOn AS DateOn,
            DBRCHD.FTCreateBy AS CreateBy,
            USRCRE.FTUsrName AS CreateByName,
            DBRCHD.FTBchCode,
            BCHL.FTBchName,
            DBRCHD.FTUsrCode    AS FTUsrDepositCode,
            USRL.FTUsrName      AS FTUsrDepositName,
            USRAPV.FTUsrName	AS FTXshApvName,
            AGN.FTAgnCode       AS rtAgnCode,
            AGN.FTAgnName       AS rtAgnName,
            WAH_L.FTWahCode     AS rtWahCode,
            WAH_L.FTWahName     AS rtWahName,
            DBRCHD.FTCstCode,
            CSTL.FTCstName,
            CST.FTCstTel,
            CST.FTCstEmail,
            REF.FTXshRefDocNo   AS DOCREF,
            SAL.FTXshDocNo      AS SALDOCNO,
            SAL.FTBchCode       AS SALBchNO,
            SAL.FTXshGndText    AS SALGRANDTXT,
            TAX.FTXshDocNo      AS TAXDOCNO,
            TAX.FTBchCode       AS TAXBchNO,
            TAX.FTXshGndText    AS TAXGRANDTXT,
            DBRCHD.FDXshBookDate,
            DOCREF.FTXshRefKey
        FROM TRTTBookHD DBRCHD WITH (NOLOCK)
        INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DBRCHD.FTBchCode      = BCH.FTBchCode
        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMAgency_L      AGN     WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DBRCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DBRCHD.FTXshApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMUser_L        USRCRE	WITH (NOLOCK)   ON DBRCHD.FTCreateBy	= USRCRE.FTUsrCode	AND USRCRE.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK)   ON DBRCHD.FTBchCode      = WAH_L.FTBchCode  AND DBRCHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TCNMCst           CST	    WITH (NOLOCK)   ON DBRCHD.FTCstCode	= CST.FTCstCode
        LEFT JOIN TCNMCst_L         CSTL	WITH (NOLOCK)   ON DBRCHD.FTCstCode	= CSTL.FTCstCode	AND CSTL.FNLngID	= ".$this->db->escape($nLngID)."
        LEFT JOIN TRTTBookHDDocRef  REF	    WITH (NOLOCK)   ON REF.FTXshDocNo	= DBRCHD.FTXshDocNo	AND REF.FTXshRefKey	= 'SO'
        LEFT JOIN TARTSoHDDocRef    SOREF	WITH (NOLOCK)   ON REF.FTXshRefDocNo	= SOREF.FTXshDocNo	AND SOREF.FTXshRefKey	= 'ABB'
        LEFT JOIN TPSTSalHD         SAL	    WITH (NOLOCK)   ON SAL.FTXshDocNo	= SOREF.FTXshRefDocNo
        LEFT JOIN TPSTSalHDDocRef   SALREF	WITH (NOLOCK)   ON SAL.FTXshDocNo	= SALREF.FTXshDocNo AND SALREF.FTXshRefKey	= 'ABBFULLTAX'
        LEFT JOIN TPSTTaxHD         TAX	    WITH (NOLOCK)   ON TAX.FTXshDocNo	= SALREF.FTXshRefDocNo
        LEFT JOIN (
            SELECT 
                ROW_NUMBER() OVER(PARTITION BY DOCREF.FTXshDocNo,DOCREF.FTXshRefKey ORDER BY DOCREF.FTXshDocNo,DOCREF.FTXshRefKey ASC) AS RowID,
                DOCREF.FTXshDocNo,
                DOCREF.FTXshRefKey
                    FROM
                        TRTTBookHDDocRef DOCREF WITH(NOLOCK)
                    WHERE DOCREF.FTXshRefKey = 'RTSale'
            ) DOCREF ON DBRCHD.FTXshDocNo = DOCREF.FTXshDocNo AND DOCREF.RowID = 1
        WHERE DBRCHD.FTXshDocNo = ".$this->db->escape($tDBRDocNo)."
    ";
    
        // echo $tSQL;
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
        unset($tDBRDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($aDetail);
        unset($oQuery);
        return $aResult;
    }

    //ลบข้อมูลใน Temp
    public function FSnMDBRDelALLTmp($paData){
        try {
            $this->db->trans_begin();
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TRTTBookDocDTTmp');
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
    public function FSxMDBRMoveDTToDTTemp($paDataWhere){
        $tDBRDocNo       = $paDataWhere['FTXshDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tDBRDocNo);
        $this->db->where('FTSessionID',$this->session->userdata('tSesSessionID'));
        $this->db->delete('TRTTBookDocDTTmp');

        $tSQL   = " INSERT INTO TRTTBookDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,FTPosCode,FTShpCode,FTLayNo,FTXtdRmkInRow,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                    )
                    SELECT DISTINCT
                        DT.FTBchCode,
                        DT.FTXshDocNo,
                        DT.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        DT.FCXsdQty,
                        DT.FCXsdQtyAll,
                        DTSL.FTPosCode,
                        DTSL.FTShpCode,
                        DTSL.FNXsdLayNo,
                        DT.FTXsdRmk,
                        PDT.FTPdtType,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TRTTBookDT AS DT WITH (NOLOCK)
                    LEFT JOIN TRTTBookDTSL DTSL WITH (NOLOCK) ON DTSL.FTXshDocNo = DT.FTXshDocNo AND DTSL.FTBchCode = DT.FTBchCode AND DT.FNXsdSeqNo = DTSL.FNXsdSeqNo
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON  PDT.FTPdtCode = DT.FTPdtCode 
                    WHERE DT.FTXshDocNo = ".$this->db->escape($tDBRDocNo)."
                    ORDER BY DT.FNXsdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);

        unset($tDBRDocNo);
        unset($tDocKey);
        unset($tSQL);
        unset($oQuery);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDBRCallRefIntDocInsertDTToTemp($paData, $ptDocType){
        $tDBRDocNo           = $paData['tDBRDocNo'];
        $tDBRFrmBchCode      = $paData['tDBRFrmBchCode'];
        $tDBROptionAddPdt    = $paData['tDBROptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tSessionID         = $this->session->userdata('tSesSessionID');
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';

        if($tDBROptionAddPdt == 1){
            $tSQLSelectDT   = "
                SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXsdBarCode  , DT.FNXsdSeqNo , DT.FCXsdQty , DT.FCXsdFactor
                FROM TARTSoDT DT WITH(NOLOCK)
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";
            $oQuery = $this->db->query($tSQLSelectDT);
            
            $tSQLGetSeqPDT = "
                SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
                FROM TRTTBookDocDTTmp WITH(NOLOCK)
                WHERE FTSessionID = ".$this->db->escape($tSessionID)."
                AND FTXthDocKey = 'TRTTBookDT'
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
                            FROM TRTTBookDocDTTmp WITH(NOLOCK)
                            WHERE FTXthDocNo            = ".$this->db->escape($tDBRDocNo)."
                            AND FTBchCode               = ".$this->db->escape($tDBRFrmBchCode)."
                            AND FTXthDocKey             = 'TRTTBookDT'
                            AND FTSessionID             = ".$this->db->escape($tSessionID)."
                            AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                            AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                            AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXsdBarCode"])." 
                            ORDER BY FNXtdSeqNo ";
                        $oQuery = $this->db->query($tSQL);
                        if ($oQuery->num_rows() > 0) {

                            // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                            $aResult    =   $oQuery->row_array();
                            $tSQL       =   "
                                UPDATE TRTTBookDocDTTmp
                                SET FCXtdQty = ".($aResult["FCXtdQty"] + $aResultDT[$j]["FCXsdQty"] ).",
                                    FCXtdQtyAll = ".(($aResult["FCXtdQty"] + $aResultDT[$j]["FCXsdQty"]) * $aResultDT[$j]["FCXsdFactor"])."
                                WHERE FTXthDocNo            = ".$this->db->escape($tDBRDocNo)."
                                AND FTBchCode               = ".$this->db->escape($tDBRFrmBchCode)."
                                AND FNXtdSeqNo              = ".$this->db->escape($aResult["FNXtdSeqNo"])."
                                AND FTXthDocKey             = 'TRTTBookDT'
                                AND FTSessionID             = ".$this->db->escape($tSessionID)."
                                AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                                AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                                AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXsdBarCode"])." "; 
                            $this->db->query($tSQL);
                        }else{
                            // $tSQL= "INSERT INTO TRTTBookDocDTTmp (
                            //     FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                            //     FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                            //     FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                            //     SELECT
                            //         '$tDBRFrmBchCode' as FTBchCode,
                            //         '$tDBRDocNo' as FTXshDocNo,
                            //         ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXsdSeqNo,
                            //         'TRTTBookDT' AS FTXthDocKey,
                            //         DT.FTPdtCode,
                            //         DT.FTXsdPdtName,
                            //         DT.FTPunCode,
                            //         DT.FTPunName,
                            //         DT.FCXsdFactor,
                            //         DT.FTXsdBarCode,
                            //         DT.FCXsdQty AS FCXsdQty,
                            //         DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
                            //         0 as FCXsdQtyLef,
                            //         0 as FCXsdQtyRfn,
                            //         '' as FTXsdStaPrcStk,
                            //         PDT.FTPdtStaAlwDis,
                            //         0 as FNXsdPdtLevel,
                            //         '' as FTXsdPdtParent,
                            //         0 as FCXsdQtySet,
                            //         '' as FTPdtStaSet,
                            //         '' as FTXsdRmk,
                            //         PDT.FTPdtType,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                            //         CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                            //         CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                            //     FROM
                            //         TARTSoDT DT WITH (NOLOCK)
                            //         LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                            //     WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
                            //     AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." 
                            //     AND DT.FNXsdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXsdSeqNo"])."
                            //     ";
                            $tSQL= "INSERT INTO TRTTBookDocDTTmp (
                                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                                FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                                SELECT
                                    '$tDBRFrmBchCode' as FTBchCode,
                                    '$tDBRDocNo' as FTXshDocNo,
                                    ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXsdSeqNo,
                                    'TRTTBookDT' AS FTXthDocKey,
                                    DT.FTPdtCode,
                                    DT.FTXsdPdtName,
                                    DT.FTPunCode,
                                    DT.FTPunName,
                                    DT.FCXsdFactor,
                                    DT.FTXsdBarCode,
                                    CASE WHEN SUM(PICKDT.FCXtdQty) > DT.FCXsdQty
                                    THEN DT.FCXsdQty
                                    ELSE SUM(PICKDT.FCXtdQty)
                                    END AS FCXsdQty,
                                    DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
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
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                                FROM
                                    TARTSoDT DT WITH (NOLOCK)
                                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                                    LEFT JOIN TCNTPdtPickHDDocRef PICKREF WITH ( NOLOCK ) ON DT.FTXshDocNo = PICKREF.FTXthRefDocNo
	                                INNER JOIN TCNTPdtPickDT PICKDT WITH ( NOLOCK ) ON DT.FTPdtCode = PICKDT.FTPdtCode AND PICKDT.FTXthDocNo = PICKREF.FTXthDocNo
                                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
                                AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." 
                                AND DT.FNXsdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXsdSeqNo"])."
                                GROUP BY DT.FNXsdSeqNo,DT.FTPdtCode,DT.FTXsdPdtName,DT.FTPunCode,DT.FTPunName,DT.FCXsdFactor,DT.FTXsdBarCode,PDT.FTPdtType,PDT.FTPdtStaAlwDis,DT.FCXsdQty
                                ";
                            
                            $oQuery = $this->db->query($tSQL);
                            
                        }
                    }
                }
            }
        }else{
            $tSQL   = "
                INSERT INTO TRTTBookDocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                    FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                )
                SELECT
                    '$tDBRFrmBchCode' as FTBchCode,
                    '$tDBRDocNo' as FTXshDocNo,
                    DT.FNXsdSeqNo,
                    'TRTTBookDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty AS FCXtdQty,
                    DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
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
                FROM TARTSoDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXpdSeqNo IN $aSeqNo
                ";

            $oQuery = $this->db->query($tSQL);
        }
        unset($tDBRDocNo,$tDBRFrmBchCode,$tDBROptionAddPdt,$tRefIntDocNo,$tRefIntBchCode,$tSessionID,$aSeqNo,$oQueryCheckTempDocType,$tClearDocDocRefTemp);
        unset($tSQLSelectDT,$oQuery,$tSQLGetSeqPDT,$oQuerySeq,$aResultDTSeq,$aResultDT,$nCountResultDT,$tSQL);
        unset($oQuery);
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDBRCallRefIntDocInsertDTToTempByJump($paData, $ptDocType){
        $tDBRDocNo           = $paData['tDBRDocNo'];
        $tDBRFrmBchCode      = $paData['tDBRFrmBchCode'];
        $tDBROptionAddPdt    = $paData['tDBROptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tSessionID         = $this->session->userdata('tSesSessionID');

        if($tDBROptionAddPdt == 1){
            $tSQLSelectDT   = "
                SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXsdBarCode  , DT.FNXsdSeqNo , DT.FCXsdQty , DT.FCXsdFactor
                FROM TARTSoDT DT WITH(NOLOCK)
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo'  ";
            $oQuery = $this->db->query($tSQLSelectDT);
            
            $tSQLGetSeqPDT = "
                SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
                FROM TRTTBookDocDTTmp WITH(NOLOCK)
                WHERE FTSessionID = ".$this->db->escape($tSessionID)."
                AND FTXthDocKey = 'TRTTBookDT'
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
                            FROM TRTTBookDocDTTmp WITH(NOLOCK)
                            WHERE FTXthDocNo            = ".$this->db->escape($tDBRDocNo)."
                            AND FTBchCode               = ".$this->db->escape($tDBRFrmBchCode)."
                            AND FTXthDocKey             = 'TRTTBookDT'
                            AND FTSessionID             = ".$this->db->escape($tSessionID)."
                            AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                            AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                            AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXsdBarCode"])." 
                            ORDER BY FNXtdSeqNo ";
                        $oQuery = $this->db->query($tSQL);
                        if ($oQuery->num_rows() > 0) {

                            // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                            $aResult    =   $oQuery->row_array();
                            $tSQL       =   "
                                UPDATE TRTTBookDocDTTmp
                                SET FCXtdQty = ".($aResult["FCXtdQty"] + $aResultDT[$j]["FCXsdQty"] ).",
                                    FCXtdQtyAll = ".(($aResult["FCXtdQty"] + $aResultDT[$j]["FCXsdQty"]) * $aResultDT[$j]["FCXsdFactor"])."
                                WHERE FTXthDocNo            = ".$this->db->escape($tDBRDocNo)."
                                AND FTBchCode               = ".$this->db->escape($tDBRFrmBchCode)."
                                AND FNXtdSeqNo              = ".$this->db->escape($aResult["FNXtdSeqNo"])."
                                AND FTXthDocKey             = 'TRTTBookDT'
                                AND FTSessionID             = ".$this->db->escape($tSessionID)."
                                AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
                                AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
                                AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXsdBarCode"])." "; 
                            $this->db->query($tSQL);
                        }else{
                            // $tSQL= "INSERT INTO TRTTBookDocDTTmp (
                            //     FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                            //     FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                            //     FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                            //     SELECT
                            //         '$tDBRFrmBchCode' as FTBchCode,
                            //         '$tDBRDocNo' as FTXshDocNo,
                            //         ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXsdSeqNo,
                            //         'TRTTBookDT' AS FTXthDocKey,
                            //         DT.FTPdtCode,
                            //         DT.FTXsdPdtName,
                            //         DT.FTPunCode,
                            //         DT.FTPunName,
                            //         DT.FCXsdFactor,
                            //         DT.FTXsdBarCode,
                            //         DT.FCXsdQty AS FCXsdQty,
                            //         DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
                            //         0 as FCXsdQtyLef,
                            //         0 as FCXsdQtyRfn,
                            //         '' as FTXsdStaPrcStk,
                            //         PDT.FTPdtStaAlwDis,
                            //         0 as FNXsdPdtLevel,
                            //         '' as FTXsdPdtParent,
                            //         0 as FCXsdQtySet,
                            //         '' as FTPdtStaSet,
                            //         '' as FTXsdRmk,
                            //         PDT.FTPdtType,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                            //         CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                            //         CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                            //         CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                            //     FROM
                            //         TARTSoDT DT WITH (NOLOCK)
                            //         LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                            //     WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
                            //     AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." 
                            //     AND DT.FNXsdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXsdSeqNo"])."
                            //     ";
                            $tSQL= "INSERT INTO TRTTBookDocDTTmp (
                                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                                FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                                SELECT
                                    '$tDBRFrmBchCode' as FTBchCode,
                                    '$tDBRDocNo' as FTXshDocNo,
                                    ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXsdSeqNo,
                                    'TRTTBookDT' AS FTXthDocKey,
                                    DT.FTPdtCode,
                                    DT.FTXsdPdtName,
                                    DT.FTPunCode,
                                    DT.FTPunName,
                                    DT.FCXsdFactor,
                                    DT.FTXsdBarCode,
                                    CASE WHEN SUM(PICKDT.FCXtdQty) > DT.FCXsdQty
                                    THEN DT.FCXsdQty
                                    ELSE SUM(PICKDT.FCXtdQty)
                                    END AS FCXsdQty,
                                    DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
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
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                                FROM
                                    TARTSoDT DT WITH (NOLOCK)
                                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                                    LEFT JOIN TCNTPdtPickHDDocRef PICKREF WITH ( NOLOCK ) ON DT.FTXshDocNo = PICKREF.FTXthRefDocNo
	                                INNER JOIN TCNTPdtPickDT PICKDT WITH ( NOLOCK ) ON DT.FTPdtCode = PICKDT.FTPdtCode AND PICKDT.FTXthDocNo = PICKREF.FTXthDocNo
                                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
                                AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." 
                                AND DT.FNXsdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXsdSeqNo"])."
                                GROUP BY DT.FNXsdSeqNo,DT.FTPdtCode,DT.FTXsdPdtName,DT.FTPunCode,DT.FTPunName,DT.FCXsdFactor,DT.FTXsdBarCode,PDT.FTPdtType,PDT.FTPdtStaAlwDis,DT.FCXsdQty
                                ";
                            
                            $oQuery = $this->db->query($tSQL);
                            
                        }
                    }
                }
            }
        }else{
            $tSQL   = "
                INSERT INTO TRTTBookDocDTTmp (
                    FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                    FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                )
                SELECT
                    '$tDBRFrmBchCode' as FTBchCode,
                    '$tDBRDocNo' as FTXshDocNo,
                    DT.FNXsdSeqNo,
                    'TRTTBookDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty AS FCXtdQty,
                    DT.FCXsdQty*DT.FCXsdFactor AS FCXtdQtyAll,
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
                FROM TARTSoDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)."
                ";

            $oQuery = $this->db->query($tSQL);
        }

        $tSQLSelectHD   = "
            SELECT SOHD.FTCstCode,CSTL.FTCstName,CST.FTCstTel,CST.FTCstEmail
            FROM TARTSoHD SOHD WITH(NOLOCK)
            LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK) ON SOHD.FTCstCode     = CSTL.FTCstCode AND CSTL.FNLngID = 1
            LEFT JOIN TCNMCst           CST     WITH (NOLOCK) ON SOHD.FTCstCode     = CST.FTCstCode
            WHERE  SOHD.FTXshDocNo = '$tRefIntDocNo' AND  SOHD.FTBchCode ='$tRefIntBchCode'  ";
        $oQueryHD = $this->db->query($tSQLSelectHD);
        $oHDDataList      = $oQueryHD->result_array();

        $aResult    = array(
            'raItems'   => $oHDDataList,
            'rtCode'    => '1',
            'rtDesc'    => 'success',
        );

        unset($tDBRDocNo,$tDBRFrmBchCode,$tDBROptionAddPdt,$tRefIntDocNo,$tRefIntBchCode,$tSessionID,$oQueryCheckTempDocType,$tClearDocDocRefTemp);
        unset($tSQLSelectDT,$oQuery,$tSQLGetSeqPDT,$oQuerySeq,$aResultDTSeq,$aResultDT,$nCountResultDT,$tSQL);
        unset($oQuery);

        return $aResult;
    }


    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDBRGetSoData($paData){
        $tDBRDocNo           = $paData;
        $tSQL   = "
            SELECT
                HD.FTXshDocNo,
                CONVERT(VARCHAR(10),FDXshDocDate,121) AS FDXshdocDate
            FROM TARTSoHD HD WITH(NOLOCK)
            WHERE HD.FTXshDocNo    = ".$this->db->escape($tDBRDocNo)." 
        ";
        $oQuery = $this->db->query($tSQL);
        $oDataList      = $oQuery->result_array();

        $aResult    = array(
            'raItems'   => $oDataList,
            'rtCode'    => '1',
            'rtDesc'    => 'success',
        );
        return $aResult;
    }


    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDBRAddHDRefEx($paDataWhere,$paDataAddEdit){
        $tSoDocRef = $paDataAddEdit['FTXthRefDocNo'];
        
        $tSQL   = " INSERT INTO TRTTBookDocHDRefTmp (
            FTXthDocNo,FTXthRefDocNo,FTXthRefType,FTXthRefKey,FDXthRefDocDate,FTXthDocKey,FTSessionID,FDCreateOn
        )
        SELECT
            '' AS FTXthDocNo,
            DOCREF.FTXshRefDocNo AS FTXthRefDocNo,
            '3' AS FTXthRefType,
            'DNW' AS FTXthRefKey,
            DOCREF.FDXshRefDocDate AS FDXthRefDocDate,
            'TRTTBookHD' AS FTXthDocKey,
            '".$this->session->userdata('tSesSessionID')."' AS FTSessionID,
            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDCreateOn
        FROM TARTSoHDDocRef DOCREF WITH (NOLOCK)
        WHERE  DOCREF.FTXshDocNo = ".$this->db->escape($tSoDocRef)." AND DOCREF.FTXshRefType = '3' AND DOCREF.FTXshRefKey = 'DNW' ";
        $oQuery = $this->db->query($tSQL);
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMDBRCallRefIntABBDocInsertDTToTemp($paData, $ptDocType){
        $tDBRDocNo               = $paData['tDBRDocNo'];
        $tDBRFrmBchCode          = $paData['tDBRFrmBchCode'];
        $oQueryCheckTempDocType = $this->FSnMDBRCheckTempDocType($paData);
        $tRefIntDocNo           = $paData['tRefIntDocNo'];
        $tRefIntBchCode         = $paData['tRefIntBchCode'];
        $aSeqNo                 = '(' . implode(',', $paData['aSeqNo']) .')';
        $tDBROptionAddPdt        = $paData['tDBROptionAddPdt']; 
        $tSessionID             = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        if ($oQueryCheckTempDocType['raItems'] == '') {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDBRDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TRTTBookDocDTTmp');
        }elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tDBRDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TRTTBookDocDTTmp');

            //ลบรายการอ้างอิง
            $tClearDocDocRefTemp    = "
                DELETE FROM TRTTBookDocHDRefTmp
                WHERE  TRTTBookDocHDRefTmp.FTXthDocNo  = ".$this->db->escape($paData['tDBRDocNo'])."
                AND TRTTBookDocHDRefTmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
            ";
            $this->db->query($tClearDocDocRefTemp);
        }

        $tSQL   = " INSERT INTO TRTTBookDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                        FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
                    )
                    SELECT
                        '$tDBRFrmBchCode' as FTBchCode,
                        '$tDBRDocNo' as FTXshDocNo,
                        DT.FNXsdSeqNo,
                        'TRTTBookDT' AS FTXthDocKey,
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
        
        unset($tDBRDocNo,$tDBRFrmBchCode,$oQueryCheckTempDocType,$tRefIntDocNo,$tRefIntBchCode,$aSeqNo,$tDBROptionAddPdt,$tSessionID);
        unset($tSQLSelectDT,$tSQLGetSeqPDT,$aResultDTSeq,$aResultDT,$nCountResultDT);
        unset($oQuery);
    }

    public function FSnMDBRCheckTempDocType($paData){
        $tSQL   = "
            SELECT
                Tmp.FTXthRefKey
            FROM TRTTBookDocHDRefTmp Tmp WITH(NOLOCK)
            WHERE Tmp.FTXthDocNo    = ".$this->db->escape($paData['tDBRDocNo'])." 
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
    public function FSnMDBRDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode   = $paDataDoc['tBchCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TRTTBookHD');

        // Document DT
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TRTTBookDT');

        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TRTTBookHDDocRef');

        $this->db->where('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TARTSoHDDocRef');


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
    public function FSaMDBRCancelDocument($paDataUpdate){
        // TAPTPoHD
        $this->db->trans_begin();
        //$this->db->set('FTXshStaApv' , ' ');
        $this->db->set('FTXshStaDoc' , '3');
        $this->db->where('FTXshDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TRTTBookHD');

        $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TARTSoHDDocRef');

        $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TRTTBookHDDocRef');

        $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TPSTSalHDDocRef');

        $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TPSTTaxHDDocRef');

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
    public function FSaMDBRApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');
        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
        $this->db->set('FTXshApvCode',$paDataUpdate['FTXshUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TRTTBookHD');
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

    // public function FSaMDBRUpdatePOStaPrcDoc($ptRefInDocNo){
    //     $nStaPrcDoc = 1;
    //     $this->db->set('FTXshStaPrcDoc',$nStaPrcDoc);
    //     $this->db->where('FTXshDocNo',$ptRefInDocNo);
    //     $this->db->update('TAPTPoHD');
    //     if ($this->db->affected_rows() > 0) {
    //         $aStatus = array(
    //             'rtCode' => '1',
    //             'rtDesc' => 'Updated Status Document Success.',
    //         );
    //     } else {
    //         $aStatus = array(
    //             'rtCode' => '903',
    //             'rtDesc' => 'Not Update Status Document.',
    //         );
    //     }
    //     unset($nStaPrcDoc);
    //     return $aStatus;
    // }

    public function FSaMDBRUpdateSOStaRef($ptRefInDocNo, $pnStaRef){
        $this->db->set('FNXshStaRef',$pnStaRef);
        $this->db->where('FTXshDocNo',$ptRefInDocNo);
        $this->db->update('TARTSoHD');
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

    public function FSaMDBRQaAddUpdateRefDocHD($aDataWhere, $aTableAddUpdate, $aDataWhereDocRefDBR, $aDataWhereDocRefPO, $aDataWhereDocRefDBRExt){
        try {
            $tTableRefDBR    = $aTableAddUpdate['tTableRefDBR'];
            $tTableRefPO    = $aTableAddUpdate['tTableRefPO'];
            if ($aDataWhereDocRefDBR != '') {
                $nChhkDataDocRefDBR  = $this->FSaMDBRChkRefDupicate($aDataWhere, $tTableRefDBR, $aDataWhereDocRefDBR);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefDBR['rtCode']) && $nChhkDataDocRefDBR['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$aDataWhereDocRefDBR['FTAgnCode']);
                    $this->db->where('FTBchCode',$aDataWhereDocRefDBR['FTBchCode']);
                    $this->db->where('FTXshDocNo',$aDataWhereDocRefDBR['FTXshDocNo']);
                    $this->db->where('FTXshRefType',$aDataWhereDocRefDBR['FTXshRefType']);
                    $this->db->where('FTXshRefDocNo',$aDataWhereDocRefDBR['FTXshRefDocNo']);
                    $this->db->delete($tTableRefDBR);
                    $this->db->last_query();
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefDBR,$aDataWhereDocRefDBR);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefDBR,$aDataWhereDocRefDBR);
                }
            }
            if ($aDataWhereDocRefPO != '') {
                $nChhkDataDocRefPO  = $this->FSaMDBRChkRefDupicate($aDataWhere, $tTableRefPO, $aDataWhereDocRefPO);
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
            if ($aDataWhereDocRefDBRExt != '') {
                $nChhkDataDocRefExt  = $this->FSaMDBRChkRefDupicate($aDataWhere, $tTableRefDBR, $aDataWhereDocRefDBRExt);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$aDataWhereDocRefDBRExt['FTAgnCode']);
                    $this->db->where('FTBchCode',$aDataWhereDocRefDBRExt['FTBchCode']);
                    $this->db->where('FTXshDocNo',$aDataWhereDocRefDBRExt['FTXshDocNo']);
                    $this->db->where('FTXshRefType',$aDataWhereDocRefDBRExt['FTXshRefType']);
                    $this->db->where('FTXshRefDocNo',$aDataWhereDocRefDBRExt['FTXshRefDocNo']);
                    $this->db->delete($tTableRefDBR);
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefDBR,$aDataWhereDocRefDBRExt);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefDBR,$aDataWhereDocRefDBRExt);
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
        unset($tTableRefDBR);
        unset($tTableRefPO);
        unset($nChhkDataDocRefDBR);
        unset($nChhkDataDocRefPO);
        unset($nChhkDataDocRefExt);
        return $aReturnData;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMDBRChkRefDupicate($aDataWhere, $tTableRef, $aDataWhereDocRef){
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
   
    public function FSaMDBRUpdatePOFNStaRef($ptBchCode,$ptRefInDocNo){
        $tSQL   = "
            UPDATE POHD
            SET POHD.FNXshStaRef = PODT.FNXshStaRef
            FROM TAPTPoHD POHD
            INNER JOIN (
                SELECT
                    CHKDTPO.FTBchCode,
                    CHKDTPO.FTXshDocNo,
                    CASE WHEN CHKDTPO.FNSumQtyLef = '0' THEN '2' ELSE '1' END AS FNXshStaRef
                FROM (
                    SELECT
                        PODT.FTBchCode,
                        PODT.FTXshDocNo,
                        SUM(PODT.FCXpdQtyLef) AS FNSumQtyLef
                    FROM TAPTPoDT PODT WITH(NOLOCK)
                    WHERE PODT.FTBchCode    = ".$this->db->escape($ptBchCode)." AND PODT.FTXshDocNo = ".$this->db->escape($ptRefInDocNo)."
                    GROUP BY PODT.FTBchCode,PODT.FTXshDocNo
                ) CHKDTPO
            ) PODT ON POHD.FTBchCode = PODT.FTBchCode AND POHD.FTXshDocNo = PODT.FTXshDocNo
        ";
        $this->db->query($tSQL);
    }

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMDBRGetDataHDRefTmp($paData){
        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FTSessionID    = $paData['FTSessionID'];
        $tSQL           = "
            SELECT '$FTXthDocNo' AS BookDocno,FTXthDocNo ,BOOK.FTBchCode, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
            FROM $tTableTmpHDRef WITH(NOLOCK) 
            LEFT JOIN TRTTBookHD BOOK ON BOOK.FTXshDocNo = TRTTBookDocHDRefTmp.FTXthDocNo
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
    public function FSaMDBRAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tRefDocNo = ( empty($paDataWhere['tSORefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tSORefDocNoOld'] );
        // $tRefDocNo = $paDataAddEdit['FTXthRefDocNo'];

        $tSQL = " SELECT FTXthRefDocNo FROM TRTTBookDocHDRefTmp WITH(NOLOCK)
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
            $this->db->update('TRTTBookDocHDRefTmp',$paDataAddEdit);
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
            $this->db->insert('TRTTBookDocHDRefTmp',$aDataAdd);
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

    // แท็บค่าอ้างอิงเอกสาร - เพิ่ม
    public function FSaMDBRAddEditHDRefTmpABB($paDataWhere,$paDataAddEdit){

        $tRefDocNo = $paDataAddEdit['FTXthRefDocNo'];

        $tSQL = " SELECT * FROM TARTSoHDDocRef WITH(NOLOCK)
                    WHERE FTXshRefKey   = 'ABB'
                    AND FTXshDocNo = ".$this->db->escape($tRefDocNo)."
                ";
        $oQuery = $this->db->query($tSQL);
        
        $this->db->trans_begin();
        if ( $oQuery->num_rows() > 0 ){
            $aABBRef =  $oQuery->result_array();
           
            $aDataAdd = array(
                'FTXthRefDocNo'     => $aABBRef[0]['FTXshRefDocNo'],
                'FTXthRefType'      => '1',
                'FTXthRefKey'       => 'ABB',
                'FTXthDocNo'        => $paDataWhere['FTXthDocNo'],
                'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                'FTSessionID'       => $paDataWhere['FTSessionID'],
                'FDCreateOn'        => $paDataWhere['FDCreateOn'],
                'FDXthRefDocDate'   => $paDataWhere['FDCreateOn'],
            );
            $this->db->insert('TRTTBookDocHDRefTmp',$aDataAdd);
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Add/Edit HDDocRef Success'
            );
        }

        $tSQLTAX = " SELECT * FROM TPSTTaxHDDocRef WITH(NOLOCK)
                    WHERE FTXshRefKey   = 'ABB'
                    AND FTXshRefDocNo = ".$this->db->escape($aABBRef[0]['FTXshRefDocNo'])."
                ";
        $oQueryTAX = $this->db->query($tSQLTAX);
        
     
        if ( $oQueryTAX->num_rows() > 0 ){
            $aTAXRef =  $oQueryTAX->result_array();
           
            $aDataAdd = array(
                'FTXthRefDocNo'     => $aTAXRef[0]['FTXshDocNo'],
                'FTXthRefType'      => '1',
                'FTXthRefKey'       => 'ABBFULLTAX',
                'FTXthDocNo'        => $paDataWhere['FTXthDocNo'],
                'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                'FTSessionID'       => $paDataWhere['FTSessionID'],
                'FDCreateOn'        => $paDataWhere['FDCreateOn'],
                'FDXthRefDocDate'   => $paDataWhere['FDCreateOn'],
            );
            $this->db->insert('TRTTBookDocHDRefTmp',$aDataAdd);
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
    public function FSxMDBRMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate,$pnDocType){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        $this->db->where('FTXshDocNo',$tDocNo);
        $this->db->delete('TRTTBookHDDocRef');

        $tSQL   =   "   INSERT INTO TRTTBookHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthDocNo,
                            FTXthRefDocNo,
                            FTXthRefType,
                            FTXthRefKey,
                            FDXthRefDocDate
                        FROM TRTTBookDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)." 
                    ";
        
        $this->db->query($tSQL);

        if ($pnDocType == 1) {
            $tTableInsert = $paTableAddUpdate['tTableRefSO'];
            $tTableInsertABB = 'TPSTSalHDDocRef';
            $tTableInsertTAX = 'TPSTTaxHDDocRef';
            $tTableInsertField = 'FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
            $tTableInsertFieldABB = 'FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
        }
        
        //Insert PO or ABB
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete($tTableInsert);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete($tTableInsertABB);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete($tTableInsertTAX);

        $tSQL   =   "   INSERT INTO $tTableInsert ($tTableInsertField) ";
        if ($pnDocType == 1) {
            $tDocKey = 'SO';
            $tSQL   .=  "SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'RTBook',
                            FDXthRefDocDate
                        FROM TRTTBookDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)."
                            AND FTXthRefKey = ".$this->db->escape($tDocKey)."  
                        ";
        }
        $this->db->query($tSQL);

        $tSQL   =   "   INSERT INTO $tTableInsertABB ($tTableInsertFieldABB) ";
        if ($pnDocType == 1) {
            $tDocKeyABB = 'ABB';
            $tSQL   .=  "SELECT
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'RTBook',
                            FDXthRefDocDate
                        FROM TRTTBookDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)."
                            AND FTXthRefKey = ".$this->db->escape($tDocKeyABB)."  
                        ";
        }
        $this->db->query($tSQL);

        $tSQL   =   "   INSERT INTO $tTableInsertTAX ($tTableInsertFieldABB) ";
        if ($pnDocType == 1) {
            $tDocKeyABB = 'ABBFULLTAX';
            $tSQL   .=  "SELECT
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'RTBook',
                            FDXthRefDocDate
                        FROM TRTTBookDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
                            AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
                            AND FTSessionID = ".$this->db->escape($tSessionID)."
                            AND FTXthRefKey = ".$this->db->escape($tDocKeyABB)."  
                        ";
        }
        $this->db->query($tSQL);
        
    }

    //ข้อมูล HDDocRef
    public function FSxMDBRMoveHDRefToHDRefTemp($paData){

        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey','TRTTBookHD');
        $this->db->where('FTSessionID',$FTSessionID);
        $this->db->delete('TRTTBookDocHDRefTmp');

        $tSQL = "   INSERT INTO TRTTBookDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                        FTXshDocNo,
                        FTXshRefDocNo,
                        FTXshRefType,
                        FTXshRefKey,
                        FDXshRefDocDate,
                        'TRTTBookHD' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                    FROM TRTTBookHDDocRef WITH (NOLOCK)
                    WHERE FTXshDocNo = ".$this->db->escape($FTXshDocNo)." ";
        $this->db->query($tSQL);
    }

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMDBRDelHDDocRef($paData){
        $tSODocNo       = $paData['FTXthDocNo'];
        $tSORefDocNo    = $paData['FTXthRefDocNo'];
        $tSODocKey      = $paData['FTXthDocKey'];
        $tSOSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tSOSessionID);
        $this->db->where('FTXthDocKey',$tSODocKey);
        $this->db->where('FTXthRefDocNo',$tSORefDocNo);
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TRTTBookDocHDRefTmp');

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

    // ฺBrowse ตู้ฝาก
    public function FSoMDBRCallBoxDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDBRBrowseBoxBchCode           = $aAdvanceSearch['tDBRBrowseBoxBchCode'];
        $tDBRBrowseBoxPos               = $aAdvanceSearch['tDBRBrowseBoxPos'];
        $tDBRBrowseBoxNo                = $aAdvanceSearch['tDBRBrowseBoxNo'];

        $tSQLMain   = "SELECT
                        PSH.FTBchCode,
                        BCH.FTBchName,
                        PSH.FTShpCode,
                        SHP.FTShpName,
                        PSH.FTPosCode ,
                        POSL.FTPosName,
                        PSH.FTPshType
                        FROM TRTMShopPos PSH WITH(NOLOCK)
                    LEFT JOIN TCNMBranch_L BCH WITH ( NOLOCK ) ON PSH.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = 1
                    LEFT JOIN TCNMShop_L SHP WITH ( NOLOCK ) ON PSH.FTBchCode = SHP.FTBchCode AND PSH.FTShpCode = SHP.FTShpCode AND SHP.FNLngID = 1
	                LEFT JOIN TCNMPos_L POSL WITH ( NOLOCK ) ON PSH.FTBchCode = POSL.FTBchCode AND PSH.FTPosCode  = POSL.FTPosCode AND POSL.FNLngID = 1 
                    WHERE 1=1 
        ";

        if(isset($tDBRBrowseBoxBchCode) && !empty($tDBRBrowseBoxBchCode)){
            $tSQLMain .= " AND (PSH.FTBchCode = ".$this->db->escape($tDBRBrowseBoxBchCode).")";
        }

        if(isset($tDBRBrowseBoxPos) && !empty($tDBRBrowseBoxPos)){
            $tSQLMain .= " AND (PSH.FTPosCode = ".$this->db->escape($tDBRBrowseBoxPos).")";
        }

        if(isset($tDBRBrowseBoxNo) && !empty($tDBRBrowseBoxNo)){
            $tSQLMain .= " AND (PSH.FTPosCode LIKE '%".$this->db->escape_like_str($tDBRBrowseBoxNo)."%')";
            $tSQLMain .= " OR (POSL.FTPosName LIKE '%".$this->db->escape_like_str($tDBRBrowseBoxNo)."%')";
        }

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTPosCode DESC , FTPosCode DESC ) AS FNRowID,* FROM
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


    // ฺBrowse ตู้ฝาก
    public function FSoMDBRCheckDocref($ptDocNo){

        
        $tSQL   =   "SELECT REF.FTXshRefKey FROM TRTTBookHDDocRef REF WITH(NOLOCK)
                    WHERE REF.FTXshRefKey = 'RTSale' AND REF.FTXshDocNo = '$ptDocNo'";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = array(
                'rtCode'        => '500',
                'rtDesc'        => 'Has Rtsale ',
            );
        }else{
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'No RtSale',
            );
        }

        return $aResult;
    }

    // Browse ช่อง
    public function FSoMDBRCallSlotDocDataTable($paDataCondition, $ptDBRStaUse){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tDBRBrowseBoxBchCode           = $aAdvanceSearch['tDBRBrowseBoxBchCode'];
        $tDBRBrowseBoxPos               = $aAdvanceSearch['tDBRBrowseBoxPos'];
        $tDBRBrowseBoxShp               = $aAdvanceSearch['tDBRBrowseShp'];
        $tDBRBrowseBoxNo                = $aAdvanceSearch['tDBRBrowseBoxNo'];
        // $tDBRBrowsePOS                  = $aAdvanceSearch['tDBRPosCode'];

        $tSQLMain   = "SELECT BCH.FTBchCode, 
                            BCH.FTBchName, 
                            LST.FTPosCode, 
                            POS.FTPosName, 
                            LST.FNLayNo, 
                            LAY.FTLayName,
                            LAY.FTShpCode,
                            LST.FTLayStaUse
                    FROM TRTTLockerStatus LST WITH(NOLOCK)
                        LEFT JOIN TCNMBranch_L BCH WITH(NOLOCK) ON LST.FTBchCode = BCH.FTBchCode
                                                                    AND BCH.FNLngID = '1'
                        LEFT JOIN TCNMPos_L POS ON LST.FTPosCode = Pos.FTPosCode
                                                    AND POS.FNLngID = '1'
                                                    AND LST.FTBchCode = POS.FTBchCode
                    
                    LEFT JOIN TRTMShopLayout_L LAY ON  LST.FTBchCode = LAY.FTBchCode
                                                    AND LST.FTShpCode = LAY.FTShpCode
                                AND LST.FNLayNo= LAY.FNLayNo
                                AND LAY.FNLngID = 1
                    WHERE 1 = 1
        ";

        if(isset($tDBRBrowseBoxBchCode) && !empty($tDBRBrowseBoxBchCode)){
            $tSQLMain .= " AND (LST.FTBchCode = ".$this->db->escape($tDBRBrowseBoxBchCode).")";
        }

        if(isset($tDBRBrowseBoxPos) && !empty($tDBRBrowseBoxPos)){
            $tSQLMain .= " AND (LST.FTPosCode = ".$this->db->escape($tDBRBrowseBoxPos).")";
        }

        if(isset($tDBRBrowseBoxShp) && !empty($tDBRBrowseBoxShp)){
            $tSQLMain .= " AND (LST.FTShpCode = ".$this->db->escape($tDBRBrowseBoxShp).")";
        }

        if(isset($ptDBRStaUse) && !empty($ptDBRStaUse)){
            $tSQLMain .= " AND (LST.FTLayStaUse = ".$this->db->escape($ptDBRStaUse).")";
        }


        // if(isset($tDBRBrowsePOS) && !empty($tDBRBrowsePOS)){
        //     $tSQLMain .= " AND (SHP.FTPosCode = ".$this->db->escape($tDBRBrowsePOS).")";
        // }

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTShpCode DESC , FTShpCode DESC ) AS FNRowID,* FROM
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

    public function FSoMDBRUpdateLayDTTmp($paItem, $aDataWhere)
    {
        $tSQL = "UPDATE TRTTBookDocDTTmp
                SET FTPosCode = '".$paItem['rtPosCode']."',
                    FTShpCode = '".$paItem['rtShpCode']."',
                    FTLayNo   = '".$paItem['rnLayNo']."'
                WHERE FTXthDocNo        = ".$this->db->escape($aDataWhere['tDBRDocCode'])."
                    AND FNXtdSeqNo      = ".$this->db->escape($paItem["rnXsdSeqNo"])."
                    AND FTXthDocKey     = 'TRTTBookDT'
                    AND FTSessionID     = ".$this->db->escape($aDataWhere['FTSessionID'])."";
                    
        $this->db->query($tSQL);


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

    public function FSoMDBRUpdateLayEDITDTTmp($paItem, $aDataWhere)
    {
        if($paItem['rtStatus'] == '1'){
        $tSQL = "UPDATE TRTTBookDocDTTmp
                SET FTPosCode = '".$paItem['rtPosTo']."',
                    FTShpCode = '".$paItem['rtShpTo']."',
                    FTLayNo   = '".$paItem['rnLayNoTo']."'
                WHERE FTXthDocNo        = ".$this->db->escape($aDataWhere['tDBRDocCode'])."
                    AND FTBchCode       = ".$this->db->escape($aDataWhere['tDBRBchCode'])."
                    AND FNXtdSeqNo      = ".$this->db->escape($paItem["rnXsdSeqNo"])."
                    AND FTXthDocKey     = 'TRTTBookDT'
                    AND FTSessionID     = ".$this->db->escape($aDataWhere['FTSessionID'])."";
                    
        $this->db->query($tSQL);
              $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Cancel Success.',
                );
        }else{
            $tSQL = "UPDATE TRTTBookDocDTTmp
                SET FTPosCode = '".$paItem['rtPosFrm']."',
                    FTShpCode = '".$paItem['rtShpFrm']."',
                    FTLayNo   = '".$paItem['rnLayNoFrm']."'
                WHERE FTXthDocNo        = ".$this->db->escape($aDataWhere['tDBRDocCode'])."
                AND FTBchCode       = ".$this->db->escape($aDataWhere['tDBRBchCode'])."
                AND FNXtdSeqNo      = ".$this->db->escape($paItem["rnXsdSeqNo"])."
                AND FTXthDocKey     = 'TRTTBookDT'
                AND FTSessionID     = ".$this->db->escape($aDataWhere['FTSessionID'])."";
        
                $this->db->query($tSQL);

            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
                );

        }


        // if ($this->db->affected_rows() > 0) {
        //     $aStatus = array(
        //         'rtCode' => '1',
        //         'rtDesc' => 'Updated Status Document Cancel Success.',
        //     );
        // } else {
        //     $aStatus = array(
        //         'rtCode' => '903',
        //         'rtDesc' => 'Not Update Status Document.',
        //     );
        // }
        // unset($dLastUpdOn);
        // unset($tLastUpdBy);
        // return $aStatus;
    }


    public function FSoMDBRUpdatePrint($ptDocNo, $ptPrintCount)
    {
        $tSQLChk = "SELECT FNXshDocPrint FROM TRTTBookHD
                WHERE FTXshDocNo        = ".$this->db->escape($ptDocNo)." ";

        $oQueryChk = $this->db->query($tSQLChk);
        $oDataList  = $oQueryChk->result_array();

        if($oDataList[0]['FNXshDocPrint'] > $ptPrintCount){
            $ptPrintCount = $oDataList[0]['FNXshDocPrint'];
        }

        $tSQL = "UPDATE TRTTBookHD
                SET FNXshDocPrint = '".$ptPrintCount."'
                WHERE FTXshDocNo        = ".$this->db->escape($ptDocNo)." ";
                    
        $this->db->query($tSQL);

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
}
