<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookingorder_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตารางหน้า List
    public function FSaMBKOGetDataTableList($paDataCondition){
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

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXthDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        TWXHD.FTBchCode,
                                        BCHL.FTBchName,
                                        TWXHD.FTXthDocNo,
                                        TWXHD.FTXthRefInt,
                                        TWXHD.FTXthRefExt,
                                        CONVERT(CHAR(10),TWXHD.FDXthDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), TWXHD.FDXthDocDate,108) AS FTXshDocTime,
                                        TWXHD.FTXthStaDoc,
                                        TWXHD.FTXthStaApv,
                                        TWXHD.FNXthStaRef,
                                        -- SPL.FTSplName,
                                        TWXHD.FTCreateBy,
                                        TWXHD.FDCreateOn,
                                        CSTL.FTCstName,
                                        TWXHD.FNXthStaDocAct,
                                        CONVERT(CHAR(10),TWXHD.FDXthRefIntDate,103) AS FDXphRefIntDate,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        TWXHD.FTXthApvCode,
                                        USRLAPV.FTUsrName   AS FTXshApvName
                                    FROM TCNTPdtTwxHD           TWXHD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON TWXHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                                    LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON TWXHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                                    LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON TWXHD.FTXthApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                                    LEFT JOIN TCNTPdtTwxHDRef    HDREF WITH (NOLOCK) ON TWXHD.FTXthDocNo  = HDREF.FTXthDocNo
                                    LEFT JOIN TCNMCst_L    CSTL WITH (NOLOCK) ON HDREF.FTXthRefTnfID  = CSTL.FTCstCode AND CSTL.FNLngID = ".$this->db->escape($nLngID)."
                                    -- INNER JOIN TCNMSpl_L    SPL     WITH (NOLOCK) ON TWXHD.FTSplCode     = SPL.FTSplCode     AND SPL.FNLngID     = ".$this->db->escape($nLngID)."
                                WHERE 1=1 AND TWXHD.FTXthDocType = 1
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND TWXHD.FTBchCode IN ($tBchCode)
            ";
        }
        
        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND TWXHD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((TWXHD.FTXthDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),TWXHD.FDXthDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((TWXHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (TWXHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((TWXHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (TWXHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND TWXHD.FTXthStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(TWXHD.FTXthStaApv,'') = '' AND TWXHD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND TWXHD.FTXthStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND TWXHD.FTXthStaApv = '$tSearchStaApprove' OR TWXHD.FTXthStaApv = '' ";
            }else{
                $tSQL .= " AND TWXHD.FTXthStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND TWXHD.FNXthStaDocAct = 1";
            } else {
                $tSQL .= " AND TWXHD.FNXthStaDocAct = 0";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMTWXCountPageDocListAll($paDataCondition);
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
    public function FSnMTWXCountPageDocListAll($paDataCondition){
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
    
        $tSQL   =   "   SELECT COUNT (TWXHD.FTXthDocNo) AS counts
                        FROM TCNTPdtTwxHD TWXHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON TWXHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1 AND TWXHD.FTXthDocType = 1
                    ";
    
        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND TWXHD.FTBchCode = '$tUserLoginBchCode' ";
        }
    
        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND TWXHD.FTShpCode = '$tUserLoginShpCode' ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((TWXHD.FTXthDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),TWXHD.FDXthDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((TWXHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (TWXHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((TWXHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (TWXHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND TWXHD.FTXthStaApv = '$tSearchStaApprove' OR TWXHD.FTXthStaApv = '' ";
            }else{
                $tSQL .= " AND TWXHD.FTXthStaApv = '$tSearchStaApprove'";
            }
        }
    
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND TWXHD.FNXthStaDocAct = 1";
            } else {
                $tSQL .= " AND TWXHD.FNXthStaDocAct = 0";
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

    // Get User Branch Detail.
    public function FSaMTWXGetDetailUserBranch($paBchCode){
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

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม ใน DTTemp โดย where session
    public function FSaMCENDeletePDTInTmp($paParams){
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

    // Delete TWX Order Document
    public function FSxMTWXClearDataInDocTemp($paWhereClearTemp){
        $tTWXDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tTWXDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tTWXSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tTWXDocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tTWXDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tTWXSessionID'
        ";
        $this->db->query($tClearDocTemp);


        // Query Delete Doc HD Discount Temp
        $tClearDocHDDisTemp =   "   DELETE FROM TCNTDocHDDisTmp
                                    WHERE 1=1
                                    AND TCNTDocHDDisTmp.FTXthDocNo  = '$tTWXDocNo'
                                    AND TCNTDocHDDisTmp.FTSessionID = '$tTWXSessionID'
        ";
        $this->db->query($tClearDocHDDisTemp);

        // Query Delete Doc DT Discount Temp
        $tClearDocDTDisTemp =   "   DELETE FROM TCNTDocDTDisTmp
                                    WHERE 1=1
                                    AND TCNTDocDTDisTmp.FTXthDocNo  = '$tTWXDocNo'
                                    AND TCNTDocDTDisTmp.FTSessionID = '$tTWXSessionID'
        ";
        $this->db->query($tClearDocDTDisTemp);
    
    }

    // Functionality : Delete TWX Order Document
    public function FSxMTWXClearDataInDocTempForImp($paWhereClearTemp){
        $tTWXDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tTWXDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tTWXSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tTWXDocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tTWXDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tTWXSessionID'
                                AND TCNTDocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
    }

    // Function: Get ShopCode From User Login
    public function FSaMTWXGetShpCodeForUsrLogin($paDataShp){
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

    // Function: Get WahCode From Bch
    public function FSaMTWXGetWahCodeForDoc($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tBchCode  = $paDataShp['tBchCode'];
        $tSQL       = " SELECT WahDef.*
                     FROM
                    (SELECT
                            WAH.FTWahCode   AS FTWahCode,
                            WAH.FTWahStaType,
                            WAHL.FTWahName  AS FTWahName,
	                        CASE WHEN ISNULL(BCH.FTWahCode,'') = WAH.FTWahCode THEN 1 ELSE 0 END AS FNWahStaDef 
                        FROM TCNMWaHouse           WAH     WITH (NOLOCK)
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FTBchCode = WAH.FTBchCode AND WAHL.FNLngID = '$nLngID'
                        INNER JOIN TCNMBranch BCH WITH ( NOLOCK ) ON BCH.FTBchCode = WAH.FTBchCode
                        WHERE WAH.FTBchCode = '$tBchCode' AND WAH.FTWahStaType != 7 
                        ) WahDef WHERE WahDef.FNWahStaDef = '1' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() == 1){
            $aResult    = $oQuery->result_array();
        }else{
            $aResult    = "";
        }

        $tSQL2       = " SELECT Top 1
                            WAH.FTWahCode   AS FTWahCode,
                            WAH.FTWahStaType,
                            WAHL.FTWahName  AS FTWahName
                        FROM TCNMWaHouse           WAH     WITH (NOLOCK)
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FTBchCode = WAH.FTBchCode AND WAHL.FNLngID = '$nLngID'
                        WHERE WAH.FTBchCode = '$tBchCode' AND WAH.FTWahStaType = 7 ";
        $oQuery2 = $this->db->query($tSQL2);
        if ($oQuery2->num_rows() == 1){
            $aResult2    = $oQuery2->result_array();
        }else{
            $aResult2    = "";
        }
        $aDataReturn    = array(
            'raItemsWah'          => $aResult,
            'raItemsWahBook'      => $aResult2,
        );
        unset($oQuery);
        unset($oQuery2);
        return $aDataReturn;
    }
    
    // Function : Get Data In Doc DT Temp
    public function FSaMTWXGetDocDTTempListPage($paDataWhere){
        $tTWXDocNo           = $paDataWhere['FTXthDocNo'];
        $tTWXDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tTWXSesSessionID    = $this->session->userdata('tSesSessionID');

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
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                -- LEFT JOIN TCNMImgPdt IMGPDT on DOCTMP.FTPdtCode = IMGPDT.FTImgRefID AND IMGPDT.FTImgTable='TCNMPdt'
                                WHERE 1 = 1
                                AND DOCTMP.FTXthDocKey = '$tTWXDocKey'
                                AND DOCTMP.FTSessionID = '$tTWXSesSessionID' ";
        if(isset($tTWXDocNo) && !empty($tTWXDocNo)){
            $tSQL   .=  " AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tTWXDocNo' ";
        }

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
            $aFoundRow  = $this->FSaMTWXGetDocDTTempListPageAll($paDataWhere);
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

    // Function : Count All Document DT Temp
    public function FSaMTWXGetDocDTTempListPageAll($paDataWhere){
        $tTWXDocNo           = $paDataWhere['FTXthDocNo'];
        $tTWXDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tTWXSesSessionID    = $this->session->userdata('tSesSessionID');

        $tSQL   = " SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP
                    WHERE 1 = 1 ";
        
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tTWXDocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tTWXDocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tTWXSesSessionID' ";
        
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

    //Get Data Pdt
    public function FSaMTWXGetDataPdt($paDataPdtParams){
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
                        INNER JOIN (
                            SELECT A.* FROM(
                                SELECT  
                                    ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                    FTVatCode , 
                                    FCVatRate 
                                FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                            ) AS A WHERE A.RowNumber = 1 
                        ) VAT ON PDT.FTVatCode = VAT.FTVatCode
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
    public function FSaMTWXInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paTWXDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tTWXOptionAddPdt'] == 1) {

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
                            AND FTPdtCode       = '".$paTWXDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paTWXDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo
                        ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                
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
                                    AND FTPdtCode       = '".$paTWXDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paTWXDataPdt["FTBarCode"]."'
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
                        'FTPdtCode'         => $paTWXDataPdt['FTPdtCode'],
                        'FTXtdPdtName'      => $paTWXDataPdt['FTPdtName'],
                        'FCXtdFactor'       => $paTWXDataPdt['FCPdtUnitFact'],
                        'FTPunCode'         => $paTWXDataPdt['FTPunCode'],
                        'FTPunName'         => $paTWXDataPdt['FTPunName'],
                        'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                        'FTXtdVatType'      => $paTWXDataPdt['FTPdtStaVatBuy'],
                        // 'FTXtdVatType'      => $paTWXDataPdt['FTPdtStaVat'],
                        'FTVatCode'         => $paDataPdtParams['nVatCode'],
                        'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                        'FTXtdStaAlwDis'    => $paTWXDataPdt['FTPdtStaAlwDis'],
                        'FTXtdSaleType'     => $paTWXDataPdt['FTPdtSaleType'],
                        'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                        'FCXtdQty'          => 1,
                        'FCXtdQtyAll'       => 1*$paTWXDataPdt['FCPdtUnitFact'],
                        'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                        'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                        // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                        'FTSessionID'       => $paDataPdtParams['tSessionID'],
                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                        'FTLastUpdBy'       => $paDataPdtParams['tTWXUsrCode'],
                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                        'FTCreateBy'        => $paDataPdtParams['tTWXUsrCode'],
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
                'FTPdtCode'         => $paTWXDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paTWXDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paTWXDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paTWXDataPdt['FTPunCode'],
                'FTPunName'         => $paTWXDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                'FTXtdVatType'      => $paTWXDataPdt['FTPdtStaVatBuy'],
                // 'FTXtdVatType'      => $paTWXDataPdt['FTPdtStaVat'],
                'FTVatCode'         => $paDataPdtParams['nVatCode'],
                'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                'FTXtdStaAlwDis'    => $paTWXDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paTWXDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paTWXDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tTWXUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tTWXUsrCode'],
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
            return $aStatus;
    }

    //Delete Product Single Item In Doc DT Temp
    public function FSnMTWXDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tTWXDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMTWXDelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tTWXDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMTWXGetCstAddress($paDataWhere){
        $tCstCode   = $paDataWhere['tCstCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = "
            SELECT TOP 1
                CST.FTCstCode,
                CSTL.FTCstName,
                CST.FTCstTel,
                CST.FTCstEmail,
                ADL.FTAddV2Desc1,
                ADL.FNAddSeqNo,
                CAR.FTCarCode,
                CAR.FTCarRegNo,
                CARL.FTCaiName
            FROM TCNMCst CST WITH(NOLOCK)
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON  CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCstAddress_L ADL WITH(NOLOCK) ON CST.FTCstCode = ADL.FTCstCode AND ADL.FNLngID = '$nLngID'
            LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON CAR.FTCarOwner = ADL.FTCstCode
            LEFT JOIN TSVMCarInfo_L CARL ON CARL.FTCaiCode = CAR.FTCarBrand AND CSTL.FNLngID = '$nLngID'
            WHERE CST.FTCstCode = '$tCstCode'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtData'        => $aDetail,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Not Found',
            );
        }
        return $aDataReturn;
    }

    // Update Document DT Temp by Seq
    public function FSaMTWXUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where_in('FTSessionID',$paDataWhere['tTWXSessionID']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nTWXSeqNo']);

        if ($paDataWhere['tTWXDocNo'] != '' && $paDataWhere['tTWXBchCode'] != '') {
            $this->db->where_in('FTXthDocNo',$paDataWhere['tTWXDocNo']);
            $this->db->where_in('FTBchCode',$paDataWhere['tTWXBchCode']);
        }
        
        $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);
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
    public function FSnMTWXChkPdtInDocDTTemp($paDataWhere){
        $tTWXDocNo       = $paDataWhere['FTXthDocNo'];
        $tTWXDocKey      = $paDataWhere['FTXthDocKey'];
        $tTWXSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocKey   = '$tTWXDocKey'
                            AND DocDT.FTSessionID   = '$tTWXSessionID' ";
        if(isset($tTWXDocNo) && !empty($tTWXDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'')  = '$tTWXDocNo' ";
        }

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // Function: Get Data SO HD List
    public function FSoMTWXCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tTWXRefIntBchCode        = $aAdvanceSearch['tTWXRefIntBchCode'];
        $tTWXRefIntDocNo          = $aAdvanceSearch['tTWXRefIntDocNo'];
        $tTWXRefIntDocDateFrm     = $aAdvanceSearch['tTWXRefIntDocDateFrm'];
        $tTWXRefIntDocDateTo      = $aAdvanceSearch['tTWXRefIntDocDateTo'];
        $tTWXRefIntStaDoc         = $aAdvanceSearch['tTWXRefIntStaDoc'];

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
                                WAH_L.FTWahName
                            FROM TARTSoHD           SOHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON SOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON SOHD.FTBchCode     = WAH_L.FTBchCode   AND SOHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            WHERE SOHD.FNXshStaRef != 2 AND SOHD.FTXshStaDoc = 1 AND SOHD.FTXshStaApv = 1
                    ";
        if(isset($tTWXRefIntBchCode) && !empty($tTWXRefIntBchCode)){
            $tSQLMain .= " AND (SOHD.FTBchCode = '$tTWXRefIntBchCode')";
        }

        if(isset($tTWXRefIntDocNo) && !empty($tTWXRefIntDocNo)){
            $tSQLMain .= " AND (SOHD.FTXshDocNo LIKE '%$tTWXRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tTWXRefIntDocDateFrm) && !empty($tTWXRefIntDocDateTo)){
            $tSQLMain .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tTWXRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tTWXRefIntDocDateTo 23:59:59')) OR (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tTWXRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tTWXRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tTWXRefIntStaDoc) && !empty($tTWXRefIntStaDoc)){
            if ($tTWXRefIntStaDoc == 3) {
                $tSQLMain .= " AND SOHD.FTXshStaDoc = '$tTWXRefIntStaDoc'";
            } elseif ($tTWXRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tTWXRefIntStaDoc == 1) {
                $tSQLMain .= " AND SOHD.FTXshStaApv = '$tTWXRefIntStaDoc'";
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

        // Function: Get Data SO HD List
    public function FSoMTWXCallRefIntDocDataTablePO($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tTWXRefIntBchCode        = $aAdvanceSearch['tTWXRefIntBchCode'];
        $tTWXRefIntDocNo          = $aAdvanceSearch['tTWXRefIntDocNo'];
        $tTWXRefIntDocDateFrm     = $aAdvanceSearch['tTWXRefIntDocDateFrm'];
        $tTWXRefIntDocDateTo      = $aAdvanceSearch['tTWXRefIntDocDateTo'];
        $tTWXRefIntStaDoc         = $aAdvanceSearch['tTWXRefIntStaDoc'];

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
                                WAH_L.FTWahName
                            FROM TARTSqHD           TSDHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON TSDHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON TSDHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON TSDHD.FTBchCode     = WAH_L.FTBchCode   AND TSDHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            WHERE ISNULL(TSDHD.FNXshStaRef,'') != 2 AND TSDHD.FTXshStaDoc = 1 AND TSDHD.FTXshStaApv = 1
                    ";
                    // echo $tSQLMain;
        if(isset($tTWXRefIntBchCode) && !empty($tTWXRefIntBchCode)){
            $tSQLMain .= " AND (TSDHD.FTBchCode = '$tTWXRefIntBchCode')";
        }

        if(isset($tTWXRefIntDocNo) && !empty($tTWXRefIntDocNo)){
            $tSQLMain .= " AND (TSDHD.FTXshDocNo LIKE '%$tTWXRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tTWXRefIntDocDateFrm) && !empty($tTWXRefIntDocDateTo)){
            $tSQLMain .= " AND ((TSDHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tTWXRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tTWXRefIntDocDateTo 23:59:59')) OR (TSDHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tTWXRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tTWXRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tTWXRefIntStaDoc) && !empty($tTWXRefIntStaDoc)){
            if ($tTWXRefIntStaDoc == 3) {
                $tSQLMain .= " AND TSDHD.FTXshStaDoc = '$tTWXRefIntStaDoc'";
            } elseif ($tTWXRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(TSDHD.FTXshStaApv,'') = '' AND TSDHD.FTXshStaDoc != '3'";
            } elseif ($tTWXRefIntStaDoc == 1) {
                $tSQLMain .= " AND TSDHD.FTXshStaApv = '$tTWXRefIntStaDoc'";
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

    // Functionality: Get Data Purchase Order HD List
    public function FSoMTWXCallRefIntDocDTDataTable($paData){

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

    // Functionality: Get Data Purchase Order HD List
    public function FSoMTWXCallRefIntDocDTDataTablePO($paData){

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

    // Function : Add/Update Data HD
    public function FSxMTWXAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMTWXGetDataDocHD(array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FNLngID'       => $this->input->post("ohdTWXLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['DateOn'],
                'FTCreateBy'    => $aDataHDOld['CreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        // Delete TWX HD
        $this->db->where_in('FTBchCode',$paDataWhere['FTOldBchCode']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['FTOldXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert TWX HD Dis
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);

        return;
    }

    // Function : Add/Update Data HD Supplier
    public function FSxMTWXAddUpdateHDRef($paDataHDRef,$paDataWhere,$paTableAddUpdate){
        // Get Data TWX HD
        $aDataGetDataRef    =  $this->FSaMTWXGetDataDocHDREF(array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FNLngID'       => $this->input->post("ohdTWXLangEdit")
        ));
        $aDataAddUpdateHDSpl    = array();
        if(isset($aDataGetDataRef['rtCode']) && $aDataGetDataRef['rtCode'] == 1){
            $aDataHDSplOld  = $aDataGetDataRef['raItems'];
            $aDataAddUpdateHDSpl    = array_merge($paDataHDRef,array(
                'FTBchCode'     => $aDataHDSplOld['FTBchCode'],
                'FTXthDocNo'    => $aDataHDSplOld['FTXthDocNo'],
            ));
        }else{
            $aDataAddUpdateHDSpl    = array_merge($paDataHDRef,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            ));
        }
        
        // Delete TWX HD REF
        $this->db->where_in('FTBchCode',$paDataWhere['FTOldBchCode']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['FTOldXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDRef']);

        // Insert TWX HD Dis
        $this->db->insert($paTableAddUpdate['tTableHDRef'],$aDataAddUpdateHDSpl);

        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMTWXAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMTWXMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tTWXBchCode        = $paDataWhere['FTBchCode'];
        $tTWXOldBchCode     = $paDataWhere['FTOldBchCode'];
        $tTWXDocNo          = $paDataWhere['FTXthDocNo'];
        $tTWXOldDocNo       = $paDataWhere['FTOldXphDocNo'];
        $tTWXDocKey         = $paTableAddUpdate['tTableDT'];
        $tTWXSessionID      = $paDataWhere['FTSessionID'];
        
        if(isset($tTWXOldDocNo) && !empty($tTWXOldDocNo)){
            $this->db->where_in('FTBchCode',$tTWXOldBchCode);
            $this->db->where_in('FTXthDocNo',$tTWXOldDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,
                        FTXtdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        if($paDataWhere['tTypeVisit'] == 1){
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
                            DOCTMP.FCXtdQtyAll,
                            DOCTMP.FTXtdRmk,
                            DOCTMP.FDLastUpdOn,
                            DOCTMP.FTLastUpdBy,
                            DOCTMP.FDCreateOn,
                            DOCTMP.FTCreateBy
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tTWXBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tTWXDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tTWXDocKey'
                        AND DOCTMP.FTSessionID  = '$tTWXSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";}else{
        $tSQL           .=  "   SELECT
                                '$tTWXBchCode',
                                '$tTWXDocNo',
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
                            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND DOCTMP.FTBchCode    = '$tTWXOldBchCode'
                            AND DOCTMP.FTXthDocNo   = '$tTWXOldDocNo'
                            AND DOCTMP.FTXthDocKey  = '$tTWXDocKey'
                            AND DOCTMP.FTSessionID  = '$tTWXSessionID'
                            ORDER BY DOCTMP.FNXtdSeqNo ASC
            ";}
        $oQuery = $this->db->query($tSQL);
        return;
    }

    //---------------------------------------------------------------------------------------

    //ข้อมูล HD
    public function FSaMTWXGetDataDocHD($paDataWhere){
        $tTWXDocNo   = $paDataWhere['FTXthDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');

        $tSQL       = " SELECT
                            DOCHD.*,
                            DOCHD.FDCreateOn AS DateOn,
                            DOCHD.FTCreateBy AS CreateBy,
                            DOCHD.FTBchCode,
                            BCHL.FTBchName,
                            DPTL.FTDptName,
                            USRL.FTUsrName,
                            USRAPV.FTUsrName	AS FTXphApvName,
                            -- PRBREF.FTXshRefDocNo AS FTXphRefInt,
                            -- PRBREFEX.FTXshRefDocNo AS FTXphRefExt,
                            -- PRBREF.FDXshRefDocDate AS FDXphRefIntDate,
                            -- PRBREFEX.FDXshRefDocDate AS FDXphRefExtDate,
                            -- CONVERT(CHAR(5), PRBREF.FDXshRefDocDate,108) AS FDXphRefIntTime,
                            -- DOSPL.*,
                            -- SPL.*,
                            -- SPL_L.FTSplName,
                            HDREF.FTXthCtrName  AS rtCstName,
                            HDREF.FTXthRefTnfID  AS rtCstCode,
                            CAR.FTCarRegNo  AS rtCarRegno,
                            HDREF.FNXthShipAdd   AS rtAddSeq,
                            WAH_L.FTWahCode     AS rtWahCode,
                            WAH_To.FTWahCode     AS rtWahCodeTo,
                            WAH_To.FTWahName     AS rtWahNameTo,
                            CAR.FTCarCode       AS rtCarCode,
                            CARL.FTCaiName       AS rtCarBrand,
                            CSRAL.FTAddV2Desc1  AS rtAddress,
                            CST.FTCstTel        AS rtCstTel,
                            CST.FTCstEmail        AS rtCstEmail,
                            CSTL.FTCstName        AS rtCstName,
                            WAH_L.FTWahName     AS rtWahName,
                            CONCAT(ADDL.FTAddV1No,' ', ADDL.FTAddV1Soi,' ', ADDL.FTAddV1Village,' ', ADDL.FTAddV1Road,' ',
                             SUBDL.FTSudName,' ', DISL.FTDstName,' ', PRO.FTPvnName,' ', ADDL.FTAddV2Desc2) AS FTAddV1Desc
                        FROM TCNTPdtTwxHD DOCHD WITH (NOLOCK)
                        INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode    
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXthApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNTPdtTwxHDRef   HDREF	WITH (NOLOCK)   ON DOCHD.FTXthDocNo	    = HDREF.FTXthDocNo
                        LEFT JOIN TSVMCar           CAR	    WITH (NOLOCK)   ON HDREF.FTCarCode	    = CAR.FTCarCode
                        LEFT JOIN TSVMCarInfo_L     CARL	WITH (NOLOCK)   ON CAR.FTCarBrand	    = CARL.FTCaiCode        AND CARL.FNLngID	= $nLngID
                        LEFT JOIN TCNMCstAddress_L  CSRAL	WITH (NOLOCK)   ON CSRAL.FNAddSeqNo	    = HDREF.FNXthShipAdd    AND CSRAL.FNLngID	= $nLngID
                        LEFT JOIN TCNMCst           CST	    WITH (NOLOCK)   ON CST.FTCstCode	    = HDREF.FTXthRefTnfID
                        LEFT JOIN TCNMCst_L         CSTL	WITH (NOLOCK)   ON CSTL.FTCstCode	    = HDREF.FTXthRefTnfID AND CSTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK)   ON DOCHD.FTBchCode      = WAH_L.FTBchCode   AND DOCHD.FTXthWhFrm = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAH_To   WITH (NOLOCK)  ON DOCHD.FTBchCode      = WAH_To.FTBchCode   AND DOCHD.FTXthWhTo = WAH_To.FTWahCode AND WAH_To.FNLngID	= $nLngID
                        LEFT JOIN TCNMCstAddress_L ADDL WITH (NOLOCK)   ON CST.FTCstCode = ADDL.FTCstCode AND ADDL.FTAddVersion = '$nAddressVersion'
                        LEFT JOIN TCNMProvince_L    PRO WITH (NOLOCK)   ON ADDL.FTAddV1PvnCode = PRO.FTPvnCode AND PRO.FNLngID = $nLngID
                        LEFT JOIN TCNMDistrict_L    DISL WITH (NOLOCK)  ON ADDL.FTAddV1DstCode = DISL.FTDstCode AND DISL.FNLngID = $nLngID
                        LEFT JOIN TCNMSubDistrict_L    SUBDL WITH (NOLOCK)   ON ADDL.FTAddV1SubDist = SUBDL.FTSudCode AND SUBDL.FNLngID = $nLngID
                        -- LEFT JOIN TCNTPdtTwxHDDocRef    PRBREF WITH (NOLOCK) ON PRBREF.FTXshDocNo  = DOCHD.FTXthDocNo AND PRBREF.FTXshRefType = '1'
                        -- LEFT JOIN TCNTPdtTwxHDDocRef    PRBREFEX WITH (NOLOCK) ON PRBREFEX.FTXshDocNo  = DOCHD.FTXthDocNo AND PRBREFEX.FTXshRefType = '3'
                        WHERE 1=1 AND DOCHD.FTXthDocNo = '$tTWXDocNo' ";

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

    // Function : Get Data Document HD REF
    public function FSaMTWXGetDataDocHDREF($paDataWhere){
        $tTWXDocNo   = $paDataWhere['FTXthDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            HDREF.FTBchCode,
                            HDREF.FTXthDocNo,
                            HDREF.FTXthCtrName,
                            HDREF.FTXthRefTnfID,
                            HDREF.FTCarCode,
                            HDREF.FNXthShipAdd
                        FROM TCNTPdtTwxHDREF HDREF  WITH (NOLOCK)
                        -- LEFT JOIN TCNMAddress_L			SHIP_Add    WITH (NOLOCK)   ON HDREF.FNXphShipAdd       = SHIP_Add.FNAddSeqNo	AND SHIP_Add.FNLngID    = $nLngID
                        WHERE 1=1 AND HDREF.FTXthDocNo = '$tTWXDocNo'
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
        return $aResult;

    }

    //ลบข้อมูลใน Temp
    public function FSnMTWXDelALLTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

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
    public function FSxMTWXMoveDTToDTTemp($paDataWhere){
        $tTWXDocNo       = $paDataWhere['FTXthDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        
        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tTWXDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
            FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
            FCXtdQty,FCXtdQtyAll,
            FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
        SELECT
            DT.FTBchCode,
            DT.FTXthDocNo,
            DT.FNXtdSeqNo,
            CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
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
        FROM TCNTPdtTwxDT AS DT WITH (NOLOCK)
        WHERE 1=1 AND DT.FTXthDocNo = '$tTWXDocNo'
        ORDER BY DT.FNXtdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMTWXCallRefIntDocInsertDTToTemp($paData){

        $tTWXDocNo        = $paData['tTWXDocNo'];
        $tTWXFrmBchCode   = $paData['tTWXFrmBchCode'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tTWXFrmBchCode);
        $this->db->where('FTXthDocNo',$tTWXDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

       $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tTWXFrmBchCode' as FTBchCode,
                    '$tTWXDocNo' as FTXthDocNo,
                    DT.FNXsdSeqNo,
                    'TCNTPdtTwxDT' AS FTXthDocKey,
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
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TARTSoDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo
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
        unset($oQuery);
        return $aResult;

    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMTWXCallRefIntDocInsertDTToTempPO($paData){

        $tTWXDocNo        = $paData['tTWXDocNo'];
        $tTWXFrmBchCode   = $paData['tTWXFrmBchCode'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tTWXFrmBchCode);
        $this->db->where('FTXthDocNo',$tTWXDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

        $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tTWXFrmBchCode' as FTBchCode,
                    '$tTWXDocNo' as FTXthDocNo,
                    DT.FNXsdSeqNo,
                    'TCNTPdtTwxDT' AS FTXthDocKey,
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
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TARTSqDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo
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
        unset($oQuery);
        return $aResult;

    }

    // Function: Delete Document
    public function FSnMTWXDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode = $paDataDoc['tBchCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXthDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TCNTPdtTwxHD');
        
        // Document DT
        $this->db->where('FTXthDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TCNTPdtTwxDT');
        
        // TWX Ref
        $this->db->where_in('FTXthDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtTwxHDRef');

        

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

    // Function : Cancel Document Data
    public function FSaMTWXCancelDocument($paDataUpdate){
        // TCNTPdtReqHqHD
        $this->db->trans_begin();
        $this->db->set('FTXthStaDoc' , '3');
        $this->db->set('FTXthRefInt' , '');
        $this->db->set('FDXthRefIntDate' , NULL);
        $this->db->set('FTXthRefExt' , '');
        $this->db->set('FDXthRefExtDate' , NULL);
        $this->db->where('FTXthDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtTwxHD');

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

    // Function : Cancel Document Data
    public function FSaMTWXUpdateBchCode($paDataUpdate){
        // TCNTPdtReqHqHD
        $this->db->trans_begin();
        $this->db->set('FTXphStaDoc' , '3');
        $this->db->where('FTXthDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtTwxHD');

        // TWX Ref
        $this->db->where_in('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtTwxHDDocRef');

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
        return $aDatRetrun;
    }

    //อนุมัตเอกสาร
    public function FSaMTWXApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXthStaApv',$paDataUpdate['FTXthStaApv']);
        $this->db->set('FTXthApvCode',$paDataUpdate['FTXphUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtTwxHD');

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
        return $aStatus;
    }

    //อัพเดทสถานะของใบ Ref
    public function FSaMTWXUpdatePRBStaRef($ptRefInDocNo, $pnStaRef, $ptTable, $ptDocWhere){
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

    //เครียร์สถานะ Ref 2 ใบ
    public function FSaMTWXClearRefDoc($ptRefInDocNo, $pnStaRef){

        $this->db->set('FNXshStaRef',$pnStaRef);
        $this->db->where('FTXshDocNo',$ptRefInDocNo);
        $this->db->update('TARTSqHD');

        $this->db->set('FNXshStaRef',$pnStaRef);
        $this->db->where('FTXshDocNo',$ptRefInDocNo);
        $this->db->update('TARTSoHD');

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

    public function FSaMTWXUpdateRefDocHD($paDataTWXAddDocRef, $aDatawherePRBAddDocRef ,$aDataPRBAddDocRef)
    {
        try {   
            $tTable     = "TCNTPdtTwxHDDocRef";
            $tTableRef  = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataTWXAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataTWXAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataTWXAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '1'
            );

            $nChhkDataDocRefInt  = $this->FSaMTWXChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefInt['rtCode']) && $nChhkDataDocRefInt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataTWXAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataTWXAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataTWXAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','1');
                $this->db->delete('TCNTPdtTwxHDDocRef');

                //เพิ่มใหม่
                $this->db->insert('TCNTPdtTwxHDDocRef',$paDataTWXAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtTwxHDDocRef',$paDataTWXAddDocRef);
            }

            $aDataWhere = array(
                'FTAgnCode'         => $aDatawherePRBAddDocRef['FTAgnCode'],
                'FTBchCode'         => $aDatawherePRBAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $aDatawherePRBAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '2'
            );
            $nChhkDataDocRefPRB  = $this->FSaMTWXChkDupicate($aDataWhere, $tTableRef);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefPRB['rtCode']) && $nChhkDataDocRefPRB['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$aDataWhere['FTAgnCode']);
                $this->db->where_in('FTBchCode',$aDataWhere['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$aDataWhere['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','2');
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

        return $aReturnData;
    }

    public function FSaMTWXUpdateRefExtDocHD($paDataTWXAddDocRef)
    {
        try {   
            $tTable     = "TCNTPdtTwxHDDocRef";
            $tTableRef  = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataTWXAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataTWXAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataTWXAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '3'
            );

            $nChhkDataDocRefExt  = $this->FSaMTWXChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataTWXAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataTWXAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataTWXAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','3');
                $this->db->delete('TCNTPdtTwxHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtTwxHDDocRef',$paDataTWXAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtTwxHDDocRef',$paDataTWXAddDocRef);
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
        return $aReturnData;
    }


    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMTWXChkDupicate($paDataPrimaryKey, $ptTable)
    {
        try{
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tRefType   = $paDataPrimaryKey['FTXshRefType'];

            $tSQL = "   SELECT 
                            FTAgnCode,
                            FTBchCode,
                            FTXshDocNo
                        FROM $ptTable
                        WHERE 1=1
                        AND FTAgnCode  = '$tAgnCode'
                        AND FTBchCode  = '$tBchCode'
                        AND FTXshDocNo = '$tDocNo'
                        AND FTXshRefType = '$tRefType'
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
            return $aResult;
            
        }catch (Exception $Error) {
            echo $Error;
        }
    }


    // ค้นหาลูกค้าใบสั่งขาย
    public function FSoMBKOFindCstDocRefInfoType0($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tXshDocNo  = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = "
            SELECT 
                HD.FTBchCode,
                HD.FTXshDocNo,
                HD.FTCstCode,
                HDCST.FTXshCstName AS FTCstName,
                ADL.FTAddV2Desc1,
                ADL.FNAddSeqNo,
                CST.FTCstTel,
                CST.FTCstEmail,
                HDCST.FTCarCode,
                CARL.FTCaiName AS FTCarName,
                CAR.FTCarRegNo
            FROM TARTSoHD HD WITH(NOLOCK)
            LEFT JOIN TARTSoHDCst HDCST WITH(NOLOCK) ON HD.FTBchCode = HDCST.FTBchCode AND HD.FTXshDocNo = HDCST.FTXshDocNo
            LEFT JOIN TCNMCst CST WITH(NOLOCK) ON HD.FTCstCode = CST.FTCstCode
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCstAddress_L ADL WITH(NOLOCK) ON HD.FTCstCode = ADL.FTCstCode AND ADL.FNLngID = '$nLngID'
            LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON HDCST.FTCarCode = CAR.FTCarCode
            LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON CARL.FTCaiCode = CAR.FTCarBrand AND CSTL.FNLngID = '$nLngID'
            WHERE  HD.FTBchCode = '$tBchCode' AND HD.FTXshDocNo = '$tXshDocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->row_array();
            $aResult    = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        return $aResult;
    }
    // ค้นหาลูกค้าใบเสนอราคา
    public function FSoMBKOFindCstDocRefInfoType1($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tXshDocNo  = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = "
            SELECT TOP 1
                HDCST.FTBchCode,
                HDCST.FTXshDocNo,
                HDCST.FTCstCode,
                CSTL.FTCstName,
                ADL.FTAddV2Desc1,
                ADL.FNAddSeqNo,
                CST.FTCstTel,
                CST.FTCstEmail,
                HDCST.FTCarCode,
                CARL.FTCaiName AS FTCarName,
                CAR.FTCarRegNo
            FROM TARTSqHDCst HDCST WITH(NOLOCK)
            LEFT JOIN TCNMCst CST 	WITH(NOLOCK) ON HDCST.FTCstCode = CST.FTCstCode
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode	AND CSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCstAddress_L ADL WITH(NOLOCK) ON CST.FTCstCode = ADL.FTCstCode AND ADL.FNLngID = '$nLngID'
            LEFT JOIN TSVMCar 	CAR WITH(NOLOCK) ON HDCST.FTCarCode = CAR.FTCarCode
            LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON  CARL.FTCaiCode = CAR.FTCarBrand AND CSTL.FNLngID = '$nLngID'
            WHERE HDCST.FTBchCode = '$tBchCode' AND HDCST.FTXshDocNo = '$tXshDocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->row_array();
            $aResult    = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        return $aResult;
    }

}

