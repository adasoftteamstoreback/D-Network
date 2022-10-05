<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerreceived_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMCRVGetDataTableList($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchShpCode         = $aAdvanceSearch['tSearchShpCode'];
        $tSearchPosCode         = $aAdvanceSearch['tSearchPosCode'];
        $tSearchPshCode         = $aAdvanceSearch['tSearchPshCode'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchDocIn           = $aAdvanceSearch['tSearcDocIn'];
        
        // $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                C.*
            FROM(
                SELECT DISTINCT
                    CRVHD.FTBchCode,
                    BCHL.FTBchName,
                    CRVHD.FTXshDocNo,
                    CONVERT(CHAR(10),CRVHD.FDXshDocDate,103) AS FDXshDocDate,
                    CONVERT(CHAR(5), CRVHD.FDXshDocDate,108) AS FTXshDocTime,
                    CRVHD.FTXshStaDoc,
                    CRVHD.FTXshStaApv,
                    CRVHD.FTCreateBy,
                    CRVHD.FDCreateOn,
                    CRVHD.FNXshStaDocAct,
                    USRL.FTUsrName      AS FTCreateByName,
                    CRVHD.FTXshApvCode,
                    REF.FTXshDocNoBK AS FTXshRefInt,
                    REF.FDXshRefDocDateBK AS FDXshRefIntDate,
                    CSTL.FTCstName,
                    CRVDTSL.FTStaPdtPick,
                    CRVDTSL.FTShpCode,
                    CRVDTSL.FTPosCode,
                    SPS.FTPshType,
                    CASE WHEN ISNULL(SPS.FTPshType,'') = 1 THEN 'ตู้ฝากของ' ELSE 'จุดบริการ' END FTPshTypeName ,
				    POS.FTPosName
                FROM TRTTSalHD              CRVHD   WITH (NOLOCK)
                LEFT JOIN (
                    SELECT 
                        B.FTXshDocNo,
                        B.FTBchCode,
                        B.FTShpCode,
                        B.FTPosCode,
                        CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' /*รับทั้งหมด*/
                            WHEN B.FTPdtPick > 0 THEN '2'           /*รับบางส่วน*/
                            ELSE '3'                                /*ยังไม่ได้รับ*/
                        END AS FTStaPdtPick
                    FROM (
                        SELECT
                            FTXshDocNo AS FTXshDocNo,
                            MAX(FTPdtMax) AS FTPdtMax,
                            FTBchCode,
                            FTPosCode,
                            FTShpCode,
                            SUM(FTPdtPick) AS FTPdtPick
                        FROM (
                            SELECT 
                                FTXshDocNo,
                                FTBchCode,
                                FTShpCode,
                                FTPosCode,
                                SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
                                CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
                            FROM TRTTSalDTSL WITH(NOLOCK)
                        ) A
                        GROUP BY A.FTXshDocNo,
                                A.FTBchCode,
                                A.FTShpCode,
                                A.FTPosCode
                    ) B
                ) CRVDTSL ON CRVDTSL.FTBchCode = CRVHD .FTBchCode AND CRVDTSL.FTXshDocNo = CRVHD.FTXshDocNo
                LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON CRVHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON CRVHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                INNER JOIN (
                    SELECT 
                        FTXshDocNo,
                        FTXshRefDocNo AS FTXshDocNoBK,
                        FDXshRefDocDate AS FDXshRefDocDateBK
                    FROM TRTTSalHDDocRef WITH(NOLOCK) 
                    WHERE FTXshRefType = '1' AND FTXshRefKey = 'RTBook'
                ) REF ON REF.FTXshDocNo = CRVHD.FTXshDocNo
                LEFT JOIN TCNMCst_L CSTL WITH (NOLOCK) ON CRVHD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = ".$this->db->escape($nLngID)."

                LEFT JOIN TRTMShopPos SPS WITH(NOLOCK) ON CRVDTSL.FTBchCode = SPS.FTBchCode
				AND CRVDTSL.FTShpCode = SPS.FTShpCode AND CRVDTSL.FTPosCode = SPS.FTPosCode

				LEFT JOIN TCNMPos_L POS WITH(NOLOCK) ON SPS.FTBchCode = POS.FTBchCode
				AND SPS.FTPosCode = POS.FTPosCode AND POS.FNLngID = ".$this->db->escape($nLngID)." 
                WHERE CRVHD.FDCreateOn <> ''
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND CRVHD.FTBchCode IN ($tBchCode) ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode   = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL               .= " AND CRVHD.FTShpCode = ".$this->db->escape($tUserLoginShpCode)."";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((CRVHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') 
                        OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') 
                        OR (CONVERT(CHAR(10),CRVHD.FDXshDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                        OR (REF.FTXshDocNoBK LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        // ค้นหาแบบ In
        if(isset($tSearchDocIn) && !empty($tSearchDocIn)){
            $tSQL .= " OR (CRVHD.FTXshDocNo IN ($tSearchDocIn)) ";
        }

        }

        // ค้นหาแบบ In
        if(isset($tSearchDocIn) && !empty($tSearchDocIn)){
            $tSQL .= " AND CRVHD.FTXshDocNo IN ($tSearchDocIn)";
        }

        // ค้นหาจากสาขา
        if(!empty($tSearchBchCodeFrom)){
            $tSQL .= " AND (CRVHD.FTBchCode = ".$this->db->escape($tSearchBchCodeFrom).")";
        }

        // ค้นหาจากสาขา
        if(!empty($tSearchShpCode)){
            $tSQL .= " AND (CRVDTSL.FTShpCode = ".$this->db->escape($tSearchShpCode).")";
        }

        // ค้นหาจากสาขา
        if(!empty($tSearchPosCode)){
            $tSQL .= " AND (CRVDTSL.FTPosCode = ".$this->db->escape($tSearchPosCode).")";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((CRVHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (CRVHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        // if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
        //     if ($tSearchStaDoc == 3) {
        //         $tSQL .= " AND CRVHD.FTXshStaDoc = ".$this->db->escape($tSearchStaDoc)."";
        //     } elseif ($tSearchStaDoc == 2) {
        //         $tSQL .= " AND ISNULL(CRVHD.FTXshStaApv,'') = '' AND CRVHD.FTXshStaDoc != '3'";
        //     } elseif ($tSearchStaDoc == 1) {
        //         $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaDoc)."";
        //     }
        // }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)." OR CRVHD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)."";
            }
        }

        // สถานะรับของ
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            $tSQL .= " AND CRVDTSL.FTStaPdtPick = '".$tSearchStaDoc."' ";
        }

        // ชนิดตู้ฝาก
        if (!empty($tSearchPshCode) && ($tSearchPshCode != "0")) {
            $tSQL .= " AND SPS.FTPshType = '".$tSearchPshCode."' ";
        }

        $tSQL   .= " 
            ) AS C
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
        unset($tSearchStaDocAct,$tSearchStaDoc,$tSearchDocDateTo,$tSearchDocDateFrom,$tSearchBchCodeFrom,$tSearchList);
        unset($aRowLen,$nLngID,$aDatSessionUserLogIn,$aAdvanceSearch);
        return $aResult;
    }

    // Paginations
    // public function FSnMCRVCountPageDocListAll($paDataCondition){
    //     $nLngID                 = $paDataCondition['FNLngID'];
    //     $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
    //     $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
    //     // Advance Search
    //     $tSearchList        = $aAdvanceSearch['tSearchAll'];
    //     $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
    //     $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
    //     $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
    //     $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
    //     $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
    //     $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

    //     $tSQL   =   "   SELECT COUNT (CRVHD.FTXshDocNo) AS counts
    //                     FROM TSVTBookHD CRVHD WITH (NOLOCK)
    //                     LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON CRVHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
    //                     LEFT JOIN TSVTBookHDDocRef CRVHD_REF WITH (NOLOCK) ON CRVHD.FTXshDocNo  = CRVHD_REF.FTXshDocNo AND CRVHD_REF.FTXshRefType = '1'
    //                     WHERE CRVHD.FDCreateOn <> ''
    //                 ";

    //     // Check User Login Branch
    //     if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
    //         $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
    //         $tSQL   .= " AND CRVHD.FTBchCode = '$tUserLoginBchCode' ";
    //     }

    //     // Check User Login Shop
    //     if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
    //         $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
    //         $tSQL   .= " AND CRVHD.FTShpCode = '$tUserLoginShpCode' ";
    //     }

    //     // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
    //     if(isset($tSearchList) && !empty($tSearchList)){
    //         $tSQL .= " AND ((CRVHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),CRVHD.FDXshDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
    //     }

    //     // ค้นหาจากสาขา - ถึงสาขา
    //     if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
    //         $tSQL .= " AND ((CRVHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (CRVHD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
    //     }

    //     // ค้นหาจากวันที่ - ถึงวันที่
    //     if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
    //         $tSQL .= " AND ((CRVHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (CRVHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
    //     }
        
    //     // ค้นหาสถานะเอกสาร
    //     if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
    //         if ($tSearchStaDoc == 3) {
    //             $tSQL .= " AND CRVHD.FTXshStaDoc = ".$this->db->escape($tSearchStaDoc)."";
    //         } elseif ($tSearchStaDoc == 2) {
    //             $tSQL .= " AND ISNULL(CRVHD.FTXshStaApv,'') = '' AND CRVHD.FTXshStaDoc != '3'";
    //         } elseif ($tSearchStaDoc == 1) {
    //             $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaDoc)."";
    //         }
    //     }

    //     // ค้นหาสถานะอนุมัติ
    //     if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
    //         if($tSearchStaApprove == 2){
    //             $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)." OR CRVHD.FTXshStaApv = '' ";
    //         }else{
    //             $tSQL .= " AND CRVHD.FTXshStaApv = ".$this->db->escape($tSearchStaApprove)."";
    //         }
    //     }

    //     // ค้นหาสถานะเคลื่อนไหว
    //     $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
    //     if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
    //         if ($tSearchStaDocAct == 1) {
    //             $tSQL .= " AND CRVHD.FNXshStaDocAct = 1";
    //         } else {
    //             $tSQL .= " AND CRVHD.FNXshStaDocAct = 0";
    //         }
    //     }
    //     $oQuery = $this->db->query($tSQL);

    //     if($oQuery->num_rows() > 0) {
    //         $aDetail        = $oQuery->row_array();
    //         $aDataReturn    =  array(
    //             'rtCountData'   => $aDetail['counts'],
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );
    //     }else{
    //         $aDataReturn    =  array(
    //             'rtCode'        => '800',
    //             'rtDesc'        => 'Data Not Found',
    //         );
    //     }
    //     unset($oQuery);
    //     unset($aDetail);
    //     unset($tSearchStaDocAct,$tSearchStaDoc,$tSearchDocDateTo,$tSearchDocDateFrom,$tSearchBchCodeTo,$tSearchBchCodeFrom,$tSearchList);
    //     unset($nLngID,$aDatSessionUserLogIn,$aAdvanceSearch);
    //     return $aDataReturn;
    // }

    // หาว่า ถ้าเป็นแฟรนไซด์ จะต้องไปเอาผู้จำหน่ายใน config
    // public function FSxMCRVFindSPLByConfig(){
    //     $nLngID     = $this->session->userdata("tLangEdit");
    //     $tSQL       = "SELECT
    //                         CON.FTSysStaUsrValue    AS rtSPLCode,
    //                         SPLL.FTSplName          AS rtSPLName
    //                     FROM TSysConfig             CON     WITH (NOLOCK)
    //                     LEFT JOIN TCNMSpl_L         SPLL    WITH (NOLOCK) ON CON.FTSysStaUsrValue = SPLL.FTSplCode  AND SPLL.FNLngID = '$nLngID'
    //                     WHERE CON.FTSysCode = 'tCN_FCSupplier' AND CON.FTSysApp = 'CN' AND CON.FTSysSeq = 1 ";
    //     $oQuery     = $this->db->query($tSQL);
    //     if ($oQuery->num_rows() > 0){
    //         $aResult    = $oQuery->row_array();
    //     }else{
    //         $aResult    = "";
    //     }
    //     unset($oQuery);
    //     return $aResult;
    // }

    // public function FSaMCRVGetDetailUserBranch($paBchCode){
    //     if(!empty($paBchCode)){
    //         $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
    //         $aReulst['item']    = $aReustl;
    //         $aReulst['code']    = 1;
    //         $aReulst['msg']     = 'Success !';
    //     }else{
    //         $aReulst['code']    = 2;
    //         $aReulst['msg']     = 'Error !';
    //     }
    //     return $aReulst;
    // }

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม ใน DTTemp โดย where session
    // public function FSaMCENDeletePDTInTmp($paParams){
    //     $tSessionID = $this->session->userdata('tSesSessionID');
    //     $this->db->where('FTSessionID', $tSessionID);
    //     $this->db->delete('TSVTBookDocDTTmp');
    //     if($this->db->affected_rows() > 0){
    //         $aStatus = array(
    //             'rtCode' => '1',
    //             'rtDesc' => 'success',
    //         );
    //     }else{
    //         $aStatus = array(
    //             'rtCode' => '905',
    //             'rtDesc' => 'cannot Delete Item.',
    //         );
    //     }
    //     unset($tSessionID);
    //     return $aStatus;
    // }

    // Delete Delivery Order Document
    // public function FSxMCRVClearDataInDocTemp($paWhereClearTemp){
    //     $tCRVDocNo       = $paWhereClearTemp['FTXthDocNo'];
    //     $tCRVDocKey      = $paWhereClearTemp['FTXthDocKey'];
    //     $tCRVSessionID   = $paWhereClearTemp['FTSessionID'];

    //     // Query Delete DocTemp
    //     $tClearDocTemp  =   "
    //         DELETE FROM TSVTBookDocDTTmp
    //         WHERE TSVTBookDocDTTmp.FTXthDocNo = ".$this->db->escape($tCRVDocNo)."
    //         AND TSVTBookDocDTTmp.FTXthDocKey  = ".$this->db->escape($tCRVDocKey)."
    //         AND TSVTBookDocDTTmp.FTSessionID  = ".$this->db->escape($tCRVSessionID)."
    //     ";
    //     $this->db->query($tClearDocTemp);

    //     // Query Delete DocRef Temp
    //     $tClearDocDocRefTemp    =  "
    //         DELETE FROM TSVTBookDocHDRefTmp
    //         WHERE TSVTBookDocHDRefTmp.FTXthDocNo  = ".$this->db->escape($tCRVDocNo)."
    //         AND TSVTBookDocHDRefTmp.FTSessionID   = ".$this->db->escape($tCRVSessionID)."
    //     ";
    //     $this->db->query($tClearDocDocRefTemp);
    //     unset($tCRVDocNo);
    //     unset($tCRVDocKey);
    //     unset($tCRVSessionID);
    //     unset($tClearDocTemp);
    //     unset($tClearDocDocRefTemp);
    // }

    // Functionality : Delete Delivery Order Document
    // public function FSxMCRVClearDataInDocTempForImp($paWhereClearTemp){
    //     $tCRVDocNo       = $paWhereClearTemp['FTXthDocNo'];
    //     $tCRVDocKey      = $paWhereClearTemp['FTXthDocKey'];
    //     $tCRVSessionID   = $paWhereClearTemp['FTSessionID'];
    //     // Query Delete DocTemp
    //     $tClearDocTemp  =   "
    //         DELETE FROM TSVTBookDocDTTmp 
    //         WHERE TSVTBookDocDTTmp.FTXthDocNo = ".$this->db->escape($tCRVDocNo)."
    //         AND TSVTBookDocDTTmp.FTXthDocKey  = ".$this->db->escape($tCRVDocKey)."
    //         AND TSVTBookDocDTTmp.FTSessionID  = ".$this->db->escape($tCRVSessionID)."
    //         AND TSVTBookDocDTTmp.FTSrnCode <> 1
    //     ";
    //     $this->db->query($tClearDocTemp);
    //     unset($tCRVDocNo);
    //     unset($tCRVDocKey);
    //     unset($tCRVSessionID);
    // }

    // Function: Get ShopCode From User Login
    // public function FSaMCRVGetShpCodeForUsrLogin($paDataShp){
    //     $nLngID     = $paDataShp['FNLngID'];
    //     $tUsrLogin  = $paDataShp['tUsrLogin'];
    //     $tSQL       = " 
    //         SELECT
    //             UGP.FTBchCode,
    //             BCHL.FTBchName,
    //             MER.FTMerCode,
    //             MERL.FTMerName,
    //             UGP.FTShpCode,
    //             SHPL.FTShpName,
    //             SHP.FTShpType,
    //             SHP.FTWahCode   AS FTWahCode,
    //             WAHL.FTWahName  AS FTWahName
    //         FROM TCNTUsrGroup           UGP     WITH (NOLOCK)
    //         LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode
    //         LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
    //         LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
    //         LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
    //         WHERE UGP.FTUsrCode = '$tUsrLogin'
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     if ($oQuery->num_rows() > 0){
    //         $aResult    = $oQuery->row_array();
    //     }else{
    //         $aResult    = "";
    //     }
    //     unset($nLngID);
    //     unset($tUsrLogin);
    //     unset($tSQL);
    //     unset($oQuery);
    //     return $aResult;
    // }

    // Get Data Config WareHouse TSysConfig
    // public function FSaMCRVGetDefOptionConfigWah($paConfigSys){
    //     $tSysCode       = $paConfigSys['FTSysCode'];
    //     $nSysSeq        = $paConfigSys['FTSysSeq'];
    //     $nLngID         = $paConfigSys['FNLngID'];
    //     $aDataReturn    = array();
    //     $tSQLUsrVal     = "
    //         SELECT
    //             SYSCON.FTSysStaUsrValue AS FTSysWahCode,
    //             WAHL.FTWahName          AS FTSysWahName
    //         FROM TSysConfig SYSCON  WITH(NOLOCK)
    //         LEFT JOIN TCNMWaHouse   WAH  WITH(NOLOCK) ON SYSCON.FTSysStaUsrValue = WAH.FTWahCode AND WAH.FTWahStaType = 1
    //         LEFT JOIN TCNMWaHouse_L WAHL WITH(NOLOCK) ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = ".$this->db->escape($nLngID)."
    //         WHERE SYSCON.FTSysCode = ".$this->db->escape($tSysCode)."
    //         AND SYSCON.FTSysSeq = ".$this->db->escape($nSysSeq)."
    //     ";
    //     $oQuery1    = $this->db->query($tSQLUsrVal);
    //     if($oQuery1->num_rows() > 0){
    //         $aDataReturn    = $oQuery1->row_array();
    //     }else{
    //         $tSQLUsrDef =   "   SELECT
    //                                 SYSCON.FTSysStaDefValue AS FTSysWahCode,
    //                                 WAHL.FTWahName          AS FTSysWahName
    //                     FROM TSysConfig SYSCON          WITH(NOLOCK)
    //                     LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaDefValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
    //                     LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = ".$this->db->escape($nLngID)."
    //                     WHERE SYSCON.FTSysCode    = ".$this->db->escape($tSysCode)."
    //                     AND SYSCON.FTSysSeq     = ".$this->db->escape($nSysSeq)."
    //         ";
    //         $oQuery2    = $this->db->query($tSQLUsrDef);
    //         if($oQuery2->num_rows() > 0){
    //             $aDataReturn    = $oQuery2->row_array();
    //         }
    //     }
    //     unset($oQuery1);
    //     unset($oQuery2);
    //     return $aDataReturn;
    // }

    // Function : Get Data In Doc DT Temp
    public function FSaMCRVGetDocDTTempListPage($paDataWhere){
        $tCRVBchCode            = $paDataWhere['tBchCode'];
        $tCRVDocNo              = $paDataWhere['tDocNo'];
        // $tCRVDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable     = $paDataWhere['tSearchPdtAdvTable'];
        // $tCRVSesSessionID    = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT 
                        ROW_NUMBER() OVER(PARTITION BY A.FTBchCode,A.FTShpCode,A.FTPosCode,A.FNXsdLayNo ORDER BY A.FTBchCode,A.FTShpCode,A.FTPosCode,A.FNXsdLayNo) AS FNSeqLayNo,
                        SUM(1) OVER(PARTITION BY A.FTBchCode,A.FTShpCode,A.FTPosCode,A.FNXsdLayNo) AS FNMaxSeqLayNo,
                        A.* 
                    FROM (
                        SELECT DISTINCT 
                            BDT.FNXsdSeqNo
                            ,POS.FTPosName
                            ,BDT.FTPdtCode
                            ,BDT.FTXsdPdtName
                            ,BDT.FTXsdBarCode
                            ,BDT.FTPunName
                            ,BDT.FCXsdQtyAll
                            ,SDTSL.FNXsdLayNo
                            ,LAY.FTLayName
                            ,CONVERT(VARCHAR(10),SDTSL.FDXshDatePick,121) AS FDXshDatePick
                            ,SDTSL.FTRsnCode
                            ,RSN.FTRsnName
                            ,BDTSL.FTPosCode
                            ,BDTSL.FTBchCode
                            ,BDTSL.FTShpCode
                        FROM TRTTSalHDDocRef SHDR WITH (NOLOCK)
                        INNER JOIN TRTTBookHD BHD WITH (NOLOCK) ON  SHDR.FTAgnCode = BHD.FTAgnCode AND SHDR.FTBchCode = BHD.FTBchCode AND SHDR.FTXshRefDocNo = BHD.FTXshDocNo
                        INNER JOIN TRTTBookDT BDT WITH (NOLOCK) ON  BHD.FTAgnCode = BDT.FTAgnCode AND BHD.FTBchCode = BDT.FTBchCode AND BHD.FTXshDocNo = BDT.FTXshDocNo
                        INNER JOIN TRTTBookDTSL BDTSL WITH (NOLOCK) ON BDT.FTAgnCode = BDTSL.FTAgnCode AND BDT.FTBchCode = BDTSL.FTBchCode AND BDT.FTXshDocNo = BDTSL.FTXshDocNo AND BDT.FNXsdSeqNo = BDTSL.FNXsdSeqNo
                        INNER JOIN TRTTSalDTSL SDTSL WITH (NOLOCK) ON SDTSL.FTAgnCode = BDTSL.FTAgnCode AND SDTSL.FTBchCode = BDTSL.FTBchCode AND SDTSL.FTShpCode = BDTSL.FTShpCode AND SDTSL.FTPosCode = BDTSL.FTPosCode AND SDTSL.FTXshDocNo = SHDR.FTXshDocNo AND SDTSL.FNXsdLayNo = BDTSL.FNXsdLayNo
                        LEFT JOIN  TCNMPos_L POS WITH (NOLOCK) ON  SDTSL.FTBchCode = POS.FTBchCode AND SDTSL.FTPosCode = POS.FTPosCode AND POS.FNLngID = 1
                        LEFT JOIN  TCNMRsn_L RSN WITH (NOLOCK) ON  SDTSL.FTRsnCode = RSN.FTRsnCode AND RSN.FNLngID = 1
                        LEFT JOIN  TRTMShopLayout_L LAY WITH (NOLOCK) ON SDTSL.FTBchCode = LAY.FTBchCode AND SDTSL.FTShpCode = LAY.FTShpCode AND SDTSL.FNXsdLayNo = LAY.FNLayNo AND LAY.FNLngID = 1
                        WHERE SHDR.FTXshRefType = '1' 
                        AND SHDR.FTXshRefKey ='RTBook' 
                        AND SHDR.FTXshDocNo = ".$this->db->escape($tCRVDocNo)."
                        AND SHDR.FTBchCode = ".$this->db->escape($tCRVBchCode)."
                    ) A ";
        // $tSQL = "   SELECT
        //                 DT.FNXsdSeqNo,
        //                 DT.FTPdtCode,
        //                 DT.FTXsdPdtName,
        //                 DT.FTXsdBarCode,
        //                 DT.FTPunName,
        //                 DT.FCXsdQtyAll,
        //                 POSL.FTPosName,
        //                 DTSL.FNXsdLayNo,
        //                 CONVERT(VARCHAR(10),DTSL.FDXshDatePick,121) AS FDXshDatePick,
        //                 DTSL.FTRsnCode,
        //                 RSNL.FTRsnName
        //             FROM TRTTSalDT DT WITH(NOLOCK)
        //             INNER JOIN TRTTSalDTSL DTSL WITH(NOLOCK) ON DTSL.FTXshDocNo = DT.FTXshDocNo AND DTSL.FTBchCode = DT.FTBchCode AND DTSL.FNXsdSeqNo = DT.FNXsdSeqNo
        //             LEFT JOIN TCNMPos_L    POSL WITH(NOLOCK) ON DTSL.FTBchCode = POSL.FTBchCode AND DTSL.FTPosCode = POSL.FTPosCode AND POSL.FNLngID = 1
        //             LEFT JOIN TCNMRsn_L    RSNL WITH(NOLOCK) ON RSNL.FTRsnCode = DTSL.FTRsnCode AND RSNL.FNLngID = 1
        //             WHERE DT.FTXshDocNo = ".$this->db->escape($tCRVDocNo)."
        //               AND DT.FTBchCode = ".$this->db->escape($tCRVBchCode);
        
        // if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
        //     $tSQL   .=  "   
        //         AND (
        //             DT.FTPdtCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
        //             OR DT.FTXsdPdtName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
        //             OR DT.FTXsdBarCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
        //             OR DT.FTPunName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
        //             OR POSL.FTPosName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%'
        //             OR DTSL.FNXsdLayNo COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchPdtAdvTable)."%' )
        //     ";
        // }

        // $tSQL   .= " ORDER BY A.FTPdtCode ASC ";
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
        unset($tCRVDocNo);
        unset($tCRVDocKey);
        unset($tSearchPdtAdvTable);
        unset($tCRVSesSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        unset($paDataWhere);
        return $aDataReturn;
    }

    //Get Data Pdt
    // public function FSaMCRVGetDataPdt($paDataPdtParams){
    //     $tPdtCode   = $paDataPdtParams['tPdtCode'];
    //     $FTPunCode  = $paDataPdtParams['tPunCode'];
    //     $FTBarCode  = $paDataPdtParams['tBarCode'];
    //     $nLngID     = $paDataPdtParams['nLngID'];
    //     $tSQL       = " 
    //         SELECT
    //             PDT.FTPdtCode,
    //             PDT.FTPdtStkControl,
    //             PDT.FTPdtGrpControl,
    //             PDT.FTPdtForSystem,
    //             PDT.FCPdtQtyOrdBuy,
    //             PDT.FCPdtCostDef,
    //             PDT.FCPdtCostOth,
    //             PDT.FCPdtCostStd,
    //             PDT.FCPdtMin,
    //             PDT.FCPdtMax,
    //             PDT.FTPdtPoint,
    //             PDT.FCPdtPointTime,
    //             PDT.FTPdtType,
    //             PDT.FTPdtSaleType,
    //             0 AS FTPdtSalePrice,
    //             PDT.FTPdtSetOrSN,
    //             PDT.FTPdtStaSetPri,
    //             PDT.FTPdtStaSetShwDT,
    //             PDT.FTPdtStaAlwDis,
    //             PDT.FTPdtStaAlwReturn,
    //             PDT.FTPdtStaVatBuy,
    //             PDT.FTPdtStaVat,
    //             PDT.FTPdtStaActive,
    //             PDT.FTPdtStaAlwReCalOpt,
    //             PDT.FTPdtStaCsm,
    //             PDT.FTTcgCode,
    //             PDT.FTPtyCode,
    //             PDT.FTPbnCode,
    //             PDT.FTPmoCode,
    //             PDT.FTVatCode,
    //             PDT.FDPdtSaleStart,
    //             PDT.FDPdtSaleStop,
    //             PDTL.FTPdtName,
    //             PDTL.FTPdtNameOth,
    //             PDTL.FTPdtNameABB,
    //             PDTL.FTPdtRmk,
    //             PKS.FTPunCode,
    //             PKS.FCPdtUnitFact,
    //             VAT.FCVatRate,
    //             UNTL.FTPunName,
    //             BAR.FTBarCode,
    //             BAR.FTPlcCode,
    //             PDTLOCL.FTPlcName,
    //             PDTSRL.FTSrnCode,
    //             PDT.FCPdtCostStd,
    //             CAVG.FCPdtCostEx,
    //             CAVG.FCPdtCostIn,
    //             SPL.FCSplLastPrice
    //         FROM TCNMPdt PDT WITH (NOLOCK)
    //         LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = ".$this->db->escape($FTPunCode)."
    //         LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = ".$this->db->escape($FTPunCode)."
    //         LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = ".$this->db->escape($nLngID)."
    //         LEFT JOIN (
    //             SELECT DISTINCT
    //                 FTVatCode,
    //                 FCVatRate,
    //                 FDVatStart
    //             FROM TCNMVatRate WITH (NOLOCK)
    //             WHERE CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart ) VAT
    //         ON PDT.FTVatCode = VAT.FTVatCode
    //         LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
    //         LEFT JOIN TCNMPdtSpl SPL        WITH (NOLOCK)   ON PDT.FTPdtCode    = SPL.FTPdtCode AND BAR.FTBarCode = SPL.FTBarCode
    //         LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
    //         WHERE PDT.FDCreateOn <> ''
    //     ";
    //     if(isset($tPdtCode) && !empty($tPdtCode)){
    //         $tSQL   .= " AND PDT.FTPdtCode   = ".$this->db->escape($tPdtCode)."";
    //     }
    //     if(isset($FTBarCode) && !empty($FTBarCode)){
    //         $tSQL   .= " AND BAR.FTBarCode = ".$this->db->escape($FTBarCode)."";
    //     }
    //     $tSQL   .= " ORDER BY FDVatStart DESC";
    //     $oQuery = $this->db->query($tSQL);
    //     if ($oQuery->num_rows() > 0){
    //         $aDetail    = $oQuery->row_array();
    //         $aResult    = array(
    //             'raItem'    => $aDetail,
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'success',
    //         );
    //     }else{
    //         $aResult = array(
    //             'rtCode' => '800',
    //             'rtDesc' => 'data not found.',
    //         );
    //     }
    //     unset($tPdtCode);
    //     unset($FTPunCode);
    //     unset($FTBarCode);
    //     unset($nLngID);
    //     unset($tSQL);
    //     unset($oQuery);
    //     unset($aDetail);
    //     return $aResult;
    // }

    // Functionality : Insert Pdt To Doc DT Temp
    // public function FSaMCRVInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
    //     $paPIDataPdt    = $paDataPdtMaster['raItem'];
    //     if ($paDataPdtParams['tCRVOptionAddPdt'] == 1) {
    //         // นำสินค้าเพิ่มจำนวนในแถวแรก
    //         $tSQL   =   "
    //             SELECT
    //                 FNXtdSeqNo,
    //                 FCXtdQty
    //             FROM TSVTBookDocDTTmp WITH (NOLOCK)
    //             WHERE FTXthDocNo    = ".$this->db->escape($paDataPdtParams['tDocNo'])."
    //             AND FTBchCode       = ".$this->db->escape($paDataPdtParams['tBchCode'])."
    //             AND FTXthDocKey     = ".$this->db->escape($paDataPdtParams['tDocKey'])."
    //             AND FTSessionID     = ".$this->db->escape($paDataPdtParams['tSessionID'])."
    //             AND FTPdtCode       = ".$this->db->escape($paPIDataPdt["FTPdtCode"])."
    //             AND FTXtdBarCode    = ".$this->db->escape($paPIDataPdt["FTBarCode"])."
    //             ORDER BY FNXtdSeqNo
    //         ";
    //         $oQuery = $this->db->query($tSQL);
    //         if ($oQuery->num_rows() > 0) {
    //             // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
    //             $aResult    = $oQuery->row_array();
    //             $tSQL       =   "
    //                 UPDATE TSVTBookDocDTTmp
    //                 SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
    //                 WHERE FTXthDocNo    = ".$this->db->escape($paDataPdtParams['tDocNo'])."
    //                 AND FTBchCode       = ".$this->db->escape($paDataPdtParams['tBchCode'])."
    //                 AND FNXtdSeqNo      = ".$this->db->escape($aResult["FNXtdSeqNo"])."
    //                 AND FTXthDocKey     = ".$this->db->escape($paDataPdtParams['tDocKey'])."
    //                 AND FTSessionID     = ".$this->db->escape($paDataPdtParams['tSessionID'])."
    //                 AND FTPdtCode       = ".$this->db->escape($paPIDataPdt["FTPdtCode"])."
    //                 AND FTXtdBarCode    = ".$this->db->escape($paPIDataPdt["FTBarCode"])."
    //             ";
    //             $this->db->query($tSQL);
    //             $aStatus    = array(
    //                 'rtCode'    => '1',
    //                 'rtDesc'    => 'Add Success.',
    //             );
    //         }else{
    //                 // เพิ่มรายการใหม่
    //                 $aDataInsert    = array(
    //                     'FTBchCode'         => $paDataPdtParams['tBchCode'],
    //                     'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
    //                     'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
    //                     'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
    //                     'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
    //                     'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
    //                     'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
    //                     'FTPunCode'         => $paPIDataPdt['FTPunCode'],
    //                     'FTPunName'         => $paPIDataPdt['FTPunName'],
    //                     'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
    //                     'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
    //                     'FTVatCode'         => $paDataPdtParams['nVatCode'],
    //                     'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
    //                     'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
    //                     'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
    //                     'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
    //                     'FTTmpStatus'       => $paPIDataPdt['FTPdtType'],
    //                     'FCXtdQty'          => 1,
    //                     'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
    //                     'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
    //                     'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
    //                     'FTSessionID'       => $paDataPdtParams['tSessionID'],
    //                     'FDLastUpdOn'       => date('Y-m-d h:i:s'),
    //                     'FTLastUpdBy'       => $paDataPdtParams['tCRVUsrCode'],
    //                     'FDCreateOn'        => date('Y-m-d h:i:s'),
    //                     'FTCreateBy'        => $paDataPdtParams['tCRVUsrCode'],
    //                 );
    //                 $this->db->insert('TSVTBookDocDTTmp',$aDataInsert);
    //                 if($this->db->affected_rows() > 0){
    //                     $aStatus    = array(
    //                         'rtCode'    => '1',
    //                         'rtDesc'    => 'Add Success.',
    //                     );
    //                 }else{
    //                     $aStatus    = array(
    //                         'rtCode'    => '905',
    //                         'rtDesc'    => 'Error Cannot Add.',
    //                     );
    //                 }
    //             }
    //     }else{
    //         // เพิ่มแถวใหม่
    //         $aDataInsert    = array(
    //             'FTBchCode'         => $paDataPdtParams['tBchCode'],
    //             'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
    //             'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
    //             'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
    //             'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
    //             'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
    //             'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
    //             'FTPunCode'         => $paPIDataPdt['FTPunCode'],
    //             'FTPunName'         => $paPIDataPdt['FTPunName'],
    //             'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
    //             'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
    //             'FTTmpStatus'       => $paPIDataPdt['FTPdtType'],
    //             'FTVatCode'         => $paDataPdtParams['nVatCode'],
    //             'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
    //             'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
    //             'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
    //             'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
    //             'FCXtdQty'          => 1,
    //             'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
    //             'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
    //             'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
    //             'FTSessionID'       => $paDataPdtParams['tSessionID'],
    //             'FDLastUpdOn'       => date('Y-m-d h:i:s'),
    //             'FTLastUpdBy'       => $paDataPdtParams['tCRVUsrCode'],
    //             'FDCreateOn'        => date('Y-m-d h:i:s'),
    //             'FTCreateBy'        => $paDataPdtParams['tCRVUsrCode'],
    //         );
    //         $this->db->insert('TSVTBookDocDTTmp',$aDataInsert);
    //         if($this->db->affected_rows() > 0){
    //             $aStatus    = array(
    //                 'rtCode'    => '1',
    //                 'rtDesc'    => 'Add Success.',
    //             );
    //         }else{
    //             $aStatus    = array(
    //                 'rtCode'    => '905',
    //                 'rtDesc'    => 'Error Cannot Add.',
    //             );
    //         }
    //     }
    //     unset($paPIDataPdt);
    //     unset($tSQL);
    //     unset($oQuery);
    //     return $aStatus;
    // }

    //Delete Product Single Item In Doc DT Temp
    // public function FSnMCRVDelPdtInDTTmp($paDataWhere){
    //     // Delete Doc DT Temp
    //     $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
    //     $this->db->where('FTXthDocNo',$paDataWhere['tCRVDocNo']);
    //     $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
    //     $this->db->where('FTPdtCode',$paDataWhere['tPdtCode']);
    //     $this->db->where('FNXtdSeqNo',$paDataWhere['nSeqNo']);
    //     $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
    //     $this->db->delete('TSVTBookDocDTTmp');
    //     return ;
    // }

    //Delete Product Multiple Items In Doc DT Temp
    // public function FSnMCRVDelMultiPdtInDTTmp($paDataWhere){
    //     // Delete Doc DT Temp
    //     $this->db->where('FTSessionID',$paDataWhere['tSessionID']);
    //     $this->db->where('FTXthDocNo',$paDataWhere['tCRVDocNo']);
    //     $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
    //     $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
    //     $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
    //     $this->db->where('FTBchCode',$paDataWhere['tBchCode']);
    //     $this->db->delete('TSVTBookDocDTTmp');
    //     return ;
    // }

    // Update Document DT Temp by Seq
    // public function FSaMCRVUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
    //     $this->db->where('FTSessionID',$paDataWhere['tCRVSessionID']);
    //     $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
    //     $this->db->where('FNXtdSeqNo',$paDataWhere['nCRVSeqNo']);
    //     if ($paDataWhere['tCRVDocNo'] != '' && $paDataWhere['tCRVBchCode'] != '') {
    //         $this->db->where('FTXthDocNo',$paDataWhere['tCRVDocNo']);
    //         $this->db->where('FTBchCode',$paDataWhere['tCRVBchCode']);
    //     }
    //     $this->db->update('TSVTBookDocDTTmp', $paDataUpdateDT);
    //     if($this->db->affected_rows() > 0){
    //         $aStatus = array(
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'Update Success',
    //         );
    //     }else{
    //         $aStatus = array(
    //             'rtCode'    => '903',
    //             'rtDesc'    => 'Update Fail',
    //         );
    //     }
    //     return $aStatus;
    // }

    // Function : Count Check Data Product In Doc DT Temp Before Save
    // public function FSnMCRVChkPdtInDocDTTemp($paDataWhere){
    //     $tCRVDocNo       = $paDataWhere['FTXthDocNo'];
    //     $tCRVDocKey      = $paDataWhere['FTXthDocKey'];
    //     $tCRVSessionID   = $paDataWhere['FTSessionID'];
    //     $tSQL           = " 
    //         SELECT
    //             COUNT(FNXtdSeqNo) AS nCountPdt
    //         FROM TSVTBookDocDTTmp DocDT WITH (NOLOCK)
    //         WHERE DocDT.FTXthDocKey = ".$this->db->escape($tCRVDocKey)."
    //         AND DocDT.FTSessionID   = ".$this->db->escape($tCRVSessionID)."
    //     ";
    //     if(isset($tCRVDocNo) && !empty($tCRVDocNo)){
    //         $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'') = ".$this->db->escape($tCRVDocNo)."";
    //     }
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $aDataQuery = $oQuery->row_array();
    //         unset($tCRVDocNo);
    //         unset($tCRVDocKey);
    //         unset($tCRVSessionID);
    //         unset($tSQL);
    //         unset($oQuery);
    //         return $aDataQuery['nCountPdt'];
    //     }else{
    //         unset($tCRVDocNo);
    //         unset($tCRVDocKey);
    //         unset($tCRVSessionID);
    //         unset($tSQL);
    //         unset($oQuery);
    //         return 0;
    //     }
    // }

    // Function : Count Check DocRef Before Cancel
    // public function FSaMCRVCheckIVRef($ptDocNo){
    //     $tCRVDocNo   = $ptDocNo;
    //     $tSQL       = "
    //         SELECT
    //             COUNT(FTXshRefDocNo) AS nCount
    //         FROM TAPTPiHDDocRef DocDT WITH (NOLOCK)
    //         WHERE DocDT.FTXshRefDocNo   = ".$this->db->escape($tCRVDocNo)."
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $aDataQuery = $oQuery->row_array();
    //         unset($tCRVDocNo);
    //         unset($oQuery);
    //         return $aDataQuery['nCount'];
    //     }else{
    //         unset($tCRVDocNo);
    //         unset($oQuery);
    //         return 0;
    //     }
    // }

    // อ้างอิงเอกสาร ใบสั่งซื้อ
    // public function FSoMCRVCallRefPOIntDocDataTable($paDataCondition){
    //     $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
    //     $nLngID                 = $paDataCondition['FNLngID'];
    //     $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
    //     // Advance Search
    //     $tCRVRefIntBchCode        = $aAdvanceSearch['tCRVRefIntBchCode'];
    //     $tCRVRefIntDocNo          = $aAdvanceSearch['tCRVRefIntDocNo'];
    //     $tCRVRefIntDocDateFrm     = $aAdvanceSearch['tCRVRefIntDocDateFrm'];
    //     $tCRVRefIntDocDateTo      = $aAdvanceSearch['tCRVRefIntDocDateTo'];
    //     $tCRVRefIntStaDoc         = $aAdvanceSearch['tCRVRefIntStaDoc'];
    //     $tCRVSplCode              = $aAdvanceSearch['tCRVSplCode'];

    //     $tSQLMain   = "
    //         SELECT DISTINCT 
    //             POHD.FTBchCode,
    //             BCHL.FTBchName,
    //             POHD.FTXshDocNo,
    //             CONVERT(CHAR(16),POHD.FDXshDocDate,121) AS FDXshDocDate,
    //             CONVERT(CHAR(5), POHD.FDXshDocDate,108) AS FTXshDocTime,
    //             POHD.FTXshStaDoc,
    //             POHD.FTXshStaApv,
    //             POHD.FNXshStaRef,
    //             POHD.FTSplCode,
    //             SPL_L.FTSplName,
    //             POHD.FTXshVATInOrEx,
    //             SPL.FNXshCrTerm,
    //             POHD.FTCreateBy,
    //             POHD.FDCreateOn,
    //             POHD.FNXshStaDocAct,
    //             USRL.FTUsrName      AS FTCreateByName,
    //             POHD.FTXshApvCode,
    //             WAH_L.FTWahCode,
    //             WAH_L.FTWahName,
    //             BCHLTO.FTBchName AS BCHNameTo ,
    //             A.SumA
    //         FROM TAPTPoHD           POHD    WITH (NOLOCK)
    //         LEFT JOIN   (
    //             SELECT
    //                 FTXshDocNo,
    //                 SUM(FCXpdQtyLef) AS SumA
    //             FROM TAPTPoDT WITH (NOLOCK)
    //             GROUP BY FTXshDocNo
    //         ) A ON A.FTXshDocNo = POHD.FTXshDocNo
    //         LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON POHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMBranch_L  BCHLTO  WITH (NOLOCK) ON POHD.FTXshBchTo    = BCHLTO.FTBchCode  AND BCHLTO.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON POHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON POHD.FTSplCode     = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON POHD.FTBchCode     = WAH_L.FTBchCode   AND POHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
    //         INNER JOIN TAPTPoHDSpl  SPL     WITH (NOLOCK) ON POHD.FTXshDocNo    = SPL.FTXshDocNo
    //         LEFT JOIN ( 
    //             SELECT CRVCREFSPC.FTXshDocNo , HD.FTXshDocNo , HD.FTSPLCode FROM TAPTPoHD HD
    //             LEFT JOIN TAPTPoHDDocRef CRVCREFSPC	WITH (NOLOCK) ON HD.FTXshDocNo = CRVCREFSPC.FTXshDocNo 
    //             AND CRVCREFSPC.FTXshRefKey = 'PO' AND CRVCREFSPC.FTXshRefType = 2
    //         ) AS SUBFN ON POHD.FTXshDocNo = SUBFN.FTXshDocNo
    //         WHERE A.SumA != ".$this->db->escape(0)." ";

    //     if(isset($tCRVRefIntBchCode) && !empty($tCRVRefIntBchCode)){
    //         $tSQLMain .= " AND (POHD.FTBchCode = ".$this->db->escape($tCRVRefIntBchCode)." OR POHD.FTXshBchTo = ".$this->db->escape($tCRVRefIntBchCode).")";
    //     }

    //     if(isset($tCRVSplCode) && !empty($tCRVSplCode)){
    //         $tSQLMain .= " AND (POHD.FTSplCode = ".$this->db->escape($tCRVSplCode).")";
    //     }

    //     if(isset($tCRVRefIntDocNo) && !empty($tCRVRefIntDocNo)){
    //         $tSQLMain .= " AND (POHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tCRVRefIntDocNo)."%')";
    //     }

    //     // ค้นหาจากวันที่ - ถึงวันที่
    //     if(!empty($tCRVRefIntDocDateFrm) && !empty($tCRVRefIntDocDateTo)){
    //         $tSQLMain .= " AND ((POHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCRVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tCRVRefIntDocDateTo 23:59:59')) OR (POHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCRVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tCRVRefIntDocDateFrm 00:00:00')))";
    //     }

    //     // ค้นหาสถานะเอกสาร
    //     if(isset($tCRVRefIntStaDoc) && !empty($tCRVRefIntStaDoc)){
    //         if ($tCRVRefIntStaDoc == 3) {
    //             $tSQLMain .= " AND POHD.FTXshStaDoc = ".$this->db->escape($tCRVRefIntStaDoc);
    //         } elseif ($tCRVRefIntStaDoc == 2) {
    //             $tSQLMain .= " AND ISNULL(POHD.FTXshStaApv,'') = '' AND POHD.FTXshStaDoc != ".$this->db->escape(3);
    //         } elseif ($tCRVRefIntStaDoc == 1) {
    //             $tSQLMain .= " AND POHD.FTXshStaApv = ".$this->db->escape($tCRVRefIntStaDoc);
    //         }
    //     }

    //     $tSQL   =   "SELECT c.* FROM(
    //                     SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
    //                     (  
    //                         $tSQLMain
    //                     ) Base) AS c 
    //                 WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1] )." ";

    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oDataList          = $oQuery->result_array();
    //         $oQueryMain         = $this->db->query($tSQLMain);
    //         $aDataCountAllRow   = $oQueryMain->num_rows();
    //         $nFoundRow          = $aDataCountAllRow;
    //         $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
    //         $aResult = array(
    //             'raItems'       => $oDataList,
    //             'rnAllRow'      => $nFoundRow,
    //             'rnCurrentPage' => $paDataCondition['nPage'],
    //             'rnAllPage'     => $nPageAll,
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );

    //     }else{
    //         $aResult = array(
    //             'rnAllRow'      => 0,
    //             'rnCurrentPage' => $paDataCondition['nPage'],
    //             "rnAllPage"     => 0,
    //             'rtCode'        => '800',
    //             'rtDesc'        => 'data not found',
    //         );
    //     }
    //     unset($oQuery);
    //     unset($oDataList);
    //     unset($aDataCountAllRow);
    //     unset($nFoundRow);
    //     unset($nPageAll);
    //     return $aResult;
    // }

    // Function: Get Data CRV HD List
    // public function FSoMCRVCallRefABBIntDocDataTable($paDataCondition){
    //     $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
    //     $nLngID                 = $paDataCondition['FNLngID'];
    //     $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
    //     // Advance Search
    //     $tCRVRefIntBchCode        = $aAdvanceSearch['tCRVRefIntBchCode'];
    //     $tCRVRefIntDocNo          = $aAdvanceSearch['tCRVRefIntDocNo'];
    //     $tCRVRefIntDocDateFrm     = $aAdvanceSearch['tCRVRefIntDocDateFrm'];
    //     $tCRVRefIntDocDateTo      = $aAdvanceSearch['tCRVRefIntDocDateTo'];
    //     $tCRVRefIntStaDoc         = $aAdvanceSearch['tCRVRefIntStaDoc'];

    //     $tSQLMain = "   SELECT DISTINCT
    //                             HD.FTBchCode,
    //                             BCHL.FTBchName,
    //                             HD.FTXshDocNo       AS FTXshDocNo,
    //                             CONVERT(CHAR(16),HD.FDXshDocDate,121) AS FDXshDocDate,
    //                             CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXshDocTime,
    //                             HD.FTXshStaDoc      AS FTXshStaDoc,
    //                             HD.FTXshStaApv      AS FTXshStaApv,
    //                             HD.FNXshStaRef      AS FNXshStaRef,
    //                             HD.FTXshVATInOrEx   AS FTXshVATInOrEx,
    //                             CST_Crd.FNCstCrTerm AS FNXshCrTerm,
    //                             HD.FTCreateBy,
    //                             HD.FDCreateOn,
    //                             HD.FNXshStaDocAct   AS FNXshStaDocAct,
    //                             USRL.FTUsrName      AS FTCreateByName,
    //                             HD.FTXshApvCode     AS FTXshApvCode,
    //                             WAH_L.FTWahCode,
    //                             WAH_L.FTWahName,
    //                             BCHLTO.FTBchName AS BCHNameTo ,
    //                             A.SumA
    //                         FROM TPSTSalHD    HD    WITH (NOLOCK)
    //                         LEFT JOIN   (   SELECT
    //                                             FTXshDocNo,
    //                                             SUM(FCXsdQtyLef) AS SumA
    //                                             FROM TPSTSalDT WITH (NOLOCK)
    //                                             GROUP BY FTXshDocNo
    //                                     ) A ON A.FTXshDocNo = HD.FTXshDocNo
    //                         LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON HD.FTBchCode       = BCHL.FTBchCode        AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
    //                         LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON HD.FTCreateBy      = USRL.FTUsrCode        AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
    //                         LEFT JOIN TCNMWaHouse_L     WAH_L   WITH (NOLOCK) ON HD.FTBchCode       = WAH_L.FTBchCode       AND HD.FTWahCode    = WAH_L.FTWahCode AND WAH_L.FNLngID	= ".$this->db->escape($nLngID)."
    //                         LEFT JOIN TCNMCstCredit     CST_Crd WITH (NOLOCK) ON HD.FTCstCode       = CST_Crd.FTCstCode
    //                         LEFT JOIN TPSTSalHDDocRef   HD_R    WITH (NOLOCK) ON HD.FTXshDocNo      = HD_R.FTXshDocNo       AND HD.FTBchCode    = HD_R.FTBchCode
    //                         LEFT JOIN TPSTSalHDCst      SALCST  WITH (NOLOCK) ON HD.FTXshDocNo      = SALCST.FTXshDocNo 
    //                         LEFT JOIN TCNMBranch_L      BCHLTO  WITH (NOLOCK) ON SALCST.FTXshCstRef = BCHLTO.FTBchCode      AND BCHLTO.FNLngID  = ".$this->db->escape($nLngID)."
    //                         WHERE A.SumA != ".$this->db->escape(0)."
    //                     ";

    //     if(isset($tCRVRefIntBchCode) && !empty($tCRVRefIntBchCode)){
    //         $tSQLMain .= " AND (
    //                         HD.FTBchCode = ".$this->db->escape($tCRVRefIntBchCode)." 
    //                         OR HD.FTBchCode = ".$this->db->escape($tCRVRefIntBchCode)."
    //                         OR SALCST.FTXshCstRef = ".$this->db->escape($tCRVRefIntBchCode)." 
    //                         )";
    //     }

    //     if(isset($tCRVRefIntDocNo) && !empty($tCRVRefIntDocNo)){
    //         $tSQLMain .= " AND (HD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tCRVRefIntDocNo)."%')";
    //     }

    //     // ค้นหาจากวันที่ - ถึงวันที่
    //     if(!empty($tCRVRefIntDocDateFrm) && !empty($tCRVRefIntDocDateTo)){
    //         $tSQLMain .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCRVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tCRVRefIntDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCRVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tCRVRefIntDocDateFrm 00:00:00')))";
    //     }

    //     // ค้นหาสถานะเอกสาร
    //     if(isset($tCRVRefIntStaDoc) && !empty($tCRVRefIntStaDoc)){
    //         if ($tCRVRefIntStaDoc == 3) {
    //             $tSQLMain .= " AND HD.FTXshStaDoc = ".$this->db->escape($tCRVRefIntStaDoc);
    //         } elseif ($tCRVRefIntStaDoc == 2) {
    //             $tSQLMain .= " AND ISNULL(HD.FTXshStaApv,'') = '' AND HD.FTXshStaDoc != ".$this->db->escape(3);
    //         } elseif ($tCRVRefIntStaDoc == 1) {
    //             $tSQLMain .= " AND HD.FTXshStaApv = ".$this->db->escape($tCRVRefIntStaDoc);
    //         }
    //     }

    //     $tSQL   =   "SELECT c.* FROM(
    //                     SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
    //                     (  
    //                         $tSQLMain
    //                     ) Base) AS c 
    //                  WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])." ";  
        
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oQueryMainSpl      = $this->FSnMCRVGetMainSpl($paDataCondition);
    //         $oDataList          = $oQuery->result_array();
    //         $oQueryMain         = $this->db->query($tSQLMain);
    //         $aDataCountAllRow   = $oQueryMain->num_rows();
    //         $nFoundRow          = $aDataCountAllRow;
    //         $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
    //         $aResult = array(
    //             'raItems'       => $oDataList,
    //             'rnAllRow'      => $nFoundRow,
    //             'rnCurrentPage' => $paDataCondition['nPage'],
    //             'rnAllPage'     => $nPageAll,
    //             'raMainSpl'     => $oQueryMainSpl,
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );

    //     }else{
    //         $aResult = array(
    //             'rnAllRow'      => 0,
    //             'rnCurrentPage' => $paDataCondition['nPage'],
    //             "rnAllPage"     => 0,
    //             'rtCode'        => '800',
    //             'rtDesc'        => 'data not found',
    //         );
    //     }
    //     unset($oQuery);
    //     unset($oDataList);
    //     unset($aDataCountAllRow);
    //     unset($nFoundRow);
    //     unset($nPageAll);
    //     return $aResult;
    // }

    // public function FSnMCRVGetMainSpl($paDataCondition){
    //     $tAgnCode = $this->session->userdata('tSesUsrAgnCode');
    //     $nLngID                 = $paDataCondition['FNLngID'];
    //     if ($tAgnCode != '') {
    //         $tSQL   = "SELECT
    //                         CF.FTCfgStaUsrValue AS FTSplCode,
    //                         SPL_L.FTSplName
    //                  FROM TCNTConfigSpc CF WITH(NOLOCK)
    //                  LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON CF.FTCfgStaUsrValue  = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
    //                  WHERE  CF.FTSysCode = 'tCN_FCSupplier' AND FTSysSeq = '1'
    //                 ";
    //     }else{
    //         $tSQL   = "SELECT
    //                         CF.FTSysStaUsrValue AS FTSplCode,
    //                         SPL_L.FTSplName
    //                  FROM TSysConfig CF WITH(NOLOCK)
    //                  LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON CF.FTSysStaUsrValue  = SPL_L.FTSplCode   AND SPL_L.FNLngID    = ".$this->db->escape($nLngID)."
    //                  WHERE  CF.FTSysCode = 'tCN_FCSupplier' AND FTSysSeq = '1'
    //                 ";
    //     }
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oDataList  = $oQuery->result_array();
    //         $aResult    = array(
    //             'raItems'       => $oDataList,
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );
    //     }else{
    //         $aResult    = array(
    //             'rnAllRow'  => 0,
    //             'rtCode'    => '800',
    //             'rtDesc'    => 'data not found',
    //         );
    //     }
    //     unset($tAgnCode);
    //     unset($nLngID);
    //     unset($tSQL);
    //     unset($oQuery);
    //     return $aResult;
    // }

    // Functionality: Get Data Purchase Order HD List
    // public function FSoMCRVCallRefIntDocDTDataTable($paData){
    //     $tBchCode   =  $paData['tBchCode'];
    //     $tDocNo     =  $paData['tDocNo'];
    //     $tSQL       = "
    //         SELECT
    //             DT.FTBchCode,
    //             DT.FTXshDocNo,
    //             DT.FNXpdSeqNo,
    //             DT.FTPdtCode,
    //             DT.FTXpdPdtName,
    //             DT.FTPunCode,
    //             DT.FTPunName,
    //             DT.FCXpdFactor,
    //             DT.FTXpdBarCode,
    //             DT.FCXpdQtyLef AS FCXpdQty,
    //             DT.FCXpdQtyAll,
    //             DT.FTXpdRmk,
    //             ISNULL(HD.FTAgnCode,'') AS FTAgnCode,
    //             CASE WHEN ISNULL(DT.FCXpdQtySo,0) = '0' THEN  DT.FCXpdQtyLef ELSE ISNULL(DT.FCXpdQtySo,0) END AS FCXpdQtySo,
    //             DT.FDLastUpdOn,
    //             DT.FTLastUpdBy,
    //             DT.FDCreateOn,
    //             DT.FTCreateBy
    //             FROM TAPTPoDT DT WITH(NOLOCK)
    //             LEFT JOIN TAPTPoHD HD WITH (NOLOCK) ON DT.FTXshDocNo  = HD.FTXshDocNo
    //         WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tDocNo)."
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oDataList          = $oQuery->result_array();
    //         $aResult = array(
    //             'raItems'       => $oDataList,
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );
    //     }else{
    //         $aResult = array(
    //             'rnAllRow'      => 0,
    //             'rtCode'        => '800',
    //             'rtDesc'        => 'data not found',
    //         );
    //     }
    //     unset($tBchCode);
    //     unset($tDocNo);
    //     unset($tSQL);
    //     unset($oQuery);
    //     return $aResult;
    // }

    // Functionality: Get Data Purchase Order HD List
    // public function FSoMCRVCallRefIntDocABBDTDataTable($paData){
    //     $tBchCode   =  $paData['tBchCode'];
    //     $tDocNo     =  $paData['tDocNo'];
    //     $tSQL       = "
    //         SELECT
    //             DT.FTBchCode,
    //             DT.FTXshDocNo AS FTXshDocNo,
    //             DT.FNXsdSeqNo AS FNXpdSeqNo,
    //             DT.FTPdtCode,
    //             DT.FTXsdPdtName AS FTXpdPdtName,
    //             DT.FTPunCode,
    //             DT.FTPunName,
    //             DT.FCXsdFactor AS FCXpdFactor,
    //             '' AS FTAgnCode,
    //             0  AS FCXpdQtySo,
    //             DT.FTXsdBarCode AS FTXpdBarCode,
    //             DT.FCXsdQty AS FCXpdQty,
    //             DT.FCXsdQtyAll AS FCXpdQtyAll,
    //             DT.FTXsdRmk AS FTXpdRmk,
    //             DT.FDLastUpdOn,
    //             DT.FTLastUpdBy,
    //             DT.FDCreateOn,
    //             DT.FTCreateBy
    //             FROM TPSTSalDT DT WITH(NOLOCK)
    //             WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tDocNo)." ";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oDataList          = $oQuery->result_array();
    //         $aResult = array(
    //             'raItems'       => $oDataList,
    //             'rtCode'        => '1',
    //             'rtDesc'        => 'success',
    //         );
    //     }else{
    //         $aResult = array(
    //             'rnAllRow'      => 0,
    //             'rtCode'        => '800',
    //             'rtDesc'        => 'data not found',
    //         );
    //     }
    //     unset($tBchCode);
    //     unset($tDocNo);
    //     unset($oQuery);
    //     return $aResult;
    // }

    // Function : Add/Update Data HD
    // public function FSxMCRVAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
    //     $aDataGetDataHD     =   $this->FSaMCRVGetDataDocHD(array(
    //         'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
    //         'FNLngID'       => $this->input->post("ohdCRVLangEdit")
    //     ));
    //     $aDataAddUpdateHD   = array();
    //     if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
    //         $aDataHCRVld         = $aDataGetDataHD['raItems'];
    //         $aDataAddUpdateHD   = array_merge($paDataMaster,array(
    //             'FTAgnCode'     => $paDataWhere['FTAgnCode'],
    //             'FTBchCode'     => $paDataWhere['FTBchCode'],
    //             'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
    //             'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
    //             'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
    //             'FDCreateOn'    => $aDataHCRVld['DateOn'],
    //             'FTCreateBy'    => $aDataHCRVld['CreateBy']
    //         ));
    //         // update HD
    //         $this->db->where('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
    //         $this->db->where('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
    //         $this->db->update($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
    //     }else{
    //         $aDataAddUpdateHD   = array_merge($paDataMaster,array(
    //             'FTAgnCode'     => $paDataWhere['FTAgnCode'],
    //             'FTBchCode'     => $paDataWhere['FTBchCode'],
    //             'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
    //             'FDCreateOn'    => $paDataWhere['FDCreateOn'],
    //             'FTCreateBy'    => $paDataWhere['FTCreateBy'],
    //         ));
    //         // Insert PI HD Dis
    //         $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
    //     }
    //     unset($aDataGetDataHD);
    //     unset($aDataHCRVld);
    //     unset($aDataAddUpdateHD);
    //     return;
    // }

    // Function : Add/Update Data HD Supplier
    // public function FSxMCRVAddUpdateHDSpl($paDataHDSpl,$paDataWhere,$paTableAddUpdate){
    //     // Get Data PI HD
    //     $aDataGetDataSpl    =   $this->FSaMCRVGetDataDocHDSpl(array(
    //         'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
    //         'FNLngID'       => $this->input->post("ohdCRVLangEdit")
    //     ));
    //     $aDataAddUpdateHDSpl    = array();
    //     if(isset($aDataGetDataSpl['rtCode']) && $aDataGetDataSpl['rtCode'] == 1){
    //         $aDataHDSplOld      = $aDataGetDataSpl['raItems'];
    //         $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
    //             'FTBchCode'     => $aDataHDSplOld['FTBchCode'],
    //             'FTXshDocNo'    => $aDataHDSplOld['FTXshDocNo'],
    //         ));
    //     }else{
    //         $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
    //             'FTBchCode'     => $paDataWhere['FTBchCode'],
    //             'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
    //         ));
    //     }
    //     // Delete PI HD Spl
    //     $this->db->where('FTBchCode',$aDataAddUpdateHDSpl['FTBchCode']);
    //     $this->db->where('FTXshDocNo',$aDataAddUpdateHDSpl['FTXshDocNo']);
    //     $this->db->delete($paTableAddUpdate['tTableHDSpl']);
    //     // Insert PI HD Dis
    //     $this->db->insert($paTableAddUpdate['tTableHDSpl'],$aDataAddUpdateHDSpl);
    //     unset($aDataGetDataSpl);
    //     unset($aDataAddUpdateHDSpl);
    //     unset($aDataHDSplOld);
    //     return;
    // }

    //อัพเดทเลขที่เอกสาร  TSVTBookDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    // public function FSxMCRVAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
    //     // Update DocNo Into DTTemp
    //     $this->db->where('FTXthDocNo','');
    //     $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
    //     $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
    //     $this->db->update('TSVTBookDocDTTmp',array(
    //         'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
    //         'FTBchCode'     => $paDataWhere['FTBchCode']
    //     ));

    //     // Update DocNo Into TSVTBookDocHDRefTmp
    //     $this->db->where('FTXthDocNo','');
    //     $this->db->where('FTXthDocKey','TSVTBookHD');
    //     $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
    //     $this->db->update('TSVTBookDocHDRefTmp',array(
    //         'FTXthDocNo'    => $paDataWhere['FTXshDocNo']
    //     ));

    //     return;
    // }

    // Function Move Document DTTemp To Document DT
    // public function FSaMCRVMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
    //     $tCRVBchCode     = $paDataWhere['FTBchCode'];
    //     $tCRVDocNo       = $paDataWhere['FTXshDocNo'];
    //     $tCRVDocKey      = $paTableAddUpdate['tTableDT'];
    //     $tCRVSessionID   = $paDataWhere['FTSessionID'];

    //     if(isset($tCRVDocNo) && !empty($tCRVDocNo)){
    //         $this->db->where('FTXshDocNo',$tCRVDocNo);
    //         $this->db->delete($paTableAddUpdate['tTableDT']);
    //     }

    //     $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
    //                     FTBchCode,FTXshDocNo,FNXpdSeqNo,FTPdtCode,FTXpdPdtName,FTPunCode,FTPunName,FCXpdFactor,FTXpdBarCode,
    //                     FCXpdQty,FCXpdQtyAll,FCXpdQtyLef,FCXpdQtyRfn,FTXpdStaPrcStk,FTXpdStaAlwDis,
    //                     FNXpdPdtLevel,FTXpdPdtParent,FCXpdQtySet,FTPdtStaSet,FTXpdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
    //     $tSQL   .=  "   SELECT
    //                         CRVCTMP.FTBchCode,
    //                         CRVCTMP.FTXthDocNo,
    //                         ROW_NUMBER() OVER(ORDER BY CRVCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
    //                         CRVCTMP.FTPdtCode,
    //                         CRVCTMP.FTXtdPdtName,
    //                         CRVCTMP.FTPunCode,
    //                         CRVCTMP.FTPunName,
    //                         CRVCTMP.FCXtdFactor,
    //                         CRVCTMP.FTXtdBarCode,
    //                         CRVCTMP.FCXtdQty,
    //                         CRVCTMP.FCXtdQty * CRVCTMP.FCXtdFactor AS FCXpdQtyAll,
    //                         CRVCTMP.FCXtdQtyLef,
    //                         CRVCTMP.FCXtdQtyRfn,
    //                         CRVCTMP.FTXtdStaPrcStk,
    //                         CRVCTMP.FTXtdStaAlwDis,
    //                         CRVCTMP.FNXtdPdtLevel,
    //                         CRVCTMP.FTXtdPdtParent,
    //                         CRVCTMP.FCXtdQtySet,
    //                         CRVCTMP.FTXtdPdtStaSet,
    //                         CRVCTMP.FTXtdRmk,
    //                         CRVCTMP.FDLastUpdOn,
    //                         CRVCTMP.FTLastUpdBy,
    //                         CRVCTMP.FDCreateOn,
    //                         CRVCTMP.FTCreateBy
    //                     FROM TSVTBookDocDTTmp CRVCTMP WITH (NOLOCK)
    //                     WHERE  CRVCTMP.FTXthDocNo    = ".$this->db->escape($tCRVDocNo)."
    //                     AND CRVCTMP.FTBchCode        = ".$this->db->escape($tCRVBchCode)."
    //                     AND CRVCTMP.FTXthDocKey      = ".$this->db->escape($tCRVDocKey)."
    //                     AND CRVCTMP.FTSessionID      = ".$this->db->escape($tCRVSessionID)."
    //                     ORDER BY CRVCTMP.FNXtdSeqNo ASC
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     unset($tCRVBchCode);
    //     unset($tCRVDocNo);
    //     unset($tCRVDocKey);
    //     unset($tCRVSessionID);
    //     unset($oQuery);
    //     return;
    // }

    // ข้อมูล HD
    public function FSaMCRVGetDataDocHD($paDataWhere){
        $tCRVBchCode    = $paDataWhere['tBchCode'];
        $tCRVDocNo      = $paDataWhere['tDocNo'];
        $nLngID         = $paDataWhere['nLngID'];

        // $tSQL       = " SELECT
        //                     HD.FTBchCode,
        //                     BCHL.FTBchName,
        //                     HD.FTXshDocNo,
        //                     HD.FTUsrCode,
        //                     USRL.FTUsrName,
        //                     HD.FDXshDocDate,
        //                     HD.FTCstCode,
        //                     CSTL.FTCstName,
        //                     CST.FTCstTel,
        //                     CST.FTCstEmail,
        //                     DTSL.FTStaPdtPick
        //                 FROM TRTTSalHD          HD   WITH(NOLOCK)
        //                 LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
        //                 LEFT JOIN TCNMUser_L    USRL WITH(NOLOCK) ON USRL.FTUsrCode = HD.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
        //                 LEFT JOIN (
        //                     SELECT 
        //                         B.FTXshDocNo,
        //                         CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' /*รับทั้งหมด*/
        //                             WHEN B.FTPdtPick > 0 THEN '2'           /*รับบางส่วน*/
        //                             ELSE '3'                                /*ยังไม่ได้รับ*/
        //                         END AS FTStaPdtPick
        //                     FROM (
        //                         SELECT
        //                             FTXshDocNo AS FTXshDocNo,
        //                             MAX(FTPdtMax) AS FTPdtMax,
        //                             SUM(FTPdtPick) AS FTPdtPick
        //                         FROM (
        //                             SELECT 
        //                                 FTXshDocNo,
        //                                 SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
        //                                 CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
        //                             FROM TRTTSalDTSL WITH(NOLOCK)
        //                         ) A
        //                         GROUP BY A.FTXshDocNo
        //                     ) B
        //                 ) DTSL ON DTSL.FTXshDocNo = HD.FTXshDocNo
        //                 LEFT JOIN (
        //                     SELECT 
        //                         FTXshDocNo,
        //                         FTXshRefDocNo AS FTXshDocNoBK,
        //                         FDXshRefDocDate AS FDXshRefDocDateBK
        //                     FROM TRTTSalHDDocRef WITH(NOLOCK) 
        //                     WHERE FTXshRefType = '1' AND FTXshRefKey = 'RTBook'
        //                 ) REF ON REF.FTXshDocNo = HD.FTXshDocNo
        //                 LEFT JOIN TRTTBookHDCst BHDCST WITH (NOLOCK) ON BHDCST.FTXshDocNo = REF.FTXshDocNoBK
        //                 LEFT JOIN TCNMCstCard CSTCRD WITH (NOLOCK) ON BHDCST.FTXshCardID = CSTCRD.FTCstCrdNo
        //                 LEFT JOIN TCNMCst CST WITH(NOLOCK) ON CST.FTCstCode = HD.FTCstCode
        //                 LEFT JOIN TCNMCst_L CSTL WITH (NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = ".$this->db->escape($nLngID)."
        //                 WHERE HD.FTXshDocNo = ".$this->db->escape($tCRVDocNo)." 
        //                   AND HD.FTBchCode = ".$this->db->escape($tCRVBchCode);


        $tSQL       = " SELECT
                            HD.FTBchCode,
                            BCHL.FTBchName,
                            HD.FTXshDocNo,
                            HD.FTUsrCode,
                            USRL.FTUsrName,
                            HD.FDXshDocDate,
                            HD.FTCstCode,
                            CSTL.FTCstName,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            DTSL.FTStaPdtPick,
                            SPS.FTPshType
                        FROM TRTTSalHD          HD   WITH(NOLOCK)
                        LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMUser_L    USRL WITH(NOLOCK) ON USRL.FTUsrCode = HD.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN (
                            SELECT 
                                B.FTXshDocNo,
                                CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' /*รับทั้งหมด*/
                                    WHEN B.FTPdtPick > 0 THEN '2'           /*รับบางส่วน*/
                                    ELSE '3'                                /*ยังไม่ได้รับ*/
                                END AS FTStaPdtPick
                            FROM (
                                SELECT
                                    FTXshDocNo AS FTXshDocNo,
                                    MAX(FTPdtMax) AS FTPdtMax,
                                    SUM(FTPdtPick) AS FTPdtPick
                                FROM (
                                    SELECT 
                                        FTXshDocNo,
                                        SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
                                        CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
                                    FROM TRTTSalDTSL WITH(NOLOCK)
                                ) A
                                GROUP BY A.FTXshDocNo
                            ) B
                        ) DTSL ON DTSL.FTXshDocNo = HD.FTXshDocNo
                        LEFT JOIN (
                            SELECT 
                                FTXshDocNo,
                                FTXshRefDocNo AS FTXshDocNoBK,
                                FDXshRefDocDate AS FDXshRefDocDateBK
                            FROM TRTTSalHDDocRef WITH(NOLOCK) 
                            WHERE FTXshRefType = '1' AND FTXshRefKey = 'RTBook'
                        ) REF ON REF.FTXshDocNo = HD.FTXshDocNo
                        LEFT JOIN TRTTBookHDCst BHDCST WITH (NOLOCK) ON BHDCST.FTXshDocNo = REF.FTXshDocNoBK
                        LEFT JOIN TCNMCstCard CSTCRD WITH (NOLOCK) ON BHDCST.FTXshCardID = CSTCRD.FTCstCrdNo
                        LEFT JOIN TCNMCst CST WITH(NOLOCK) ON CST.FTCstCode = HD.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL WITH (NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN (
                            SELECT 
                                B.FTXshDocNo,
                                B.FTBchCode,
                                B.FTShpCode,
                                B.FTPosCode,
                                CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' /*รับทั้งหมด*/
                                    WHEN B.FTPdtPick > 0 THEN '2'           /*รับบางส่วน*/
                                    ELSE '3'                                /*ยังไม่ได้รับ*/
                                END AS FTStaPdtPick
                            FROM (
                                SELECT
                                    FTXshDocNo AS FTXshDocNo,
                                    MAX(FTPdtMax) AS FTPdtMax,
                                    FTBchCode,
                                    FTPosCode,
                                    FTShpCode,
                                    SUM(FTPdtPick) AS FTPdtPick
                                FROM (
                                    SELECT 
                                        FTXshDocNo,
                                        FTBchCode,
                                        FTShpCode,
                                        FTPosCode,
                                        SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
                                        CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
                                    FROM TRTTSalDTSL WITH(NOLOCK)
                                ) A
                                GROUP BY A.FTXshDocNo,
                                        A.FTBchCode,
                                        A.FTShpCode,
                                        A.FTPosCode
                            ) B
                        ) CRVDTSL ON CRVDTSL.FTBchCode = HD.FTBchCode AND CRVDTSL.FTXshDocNo = HD.FTXshDocNo
                        LEFT JOIN TRTMShopPos SPS WITH(NOLOCK) ON CRVDTSL.FTBchCode = SPS.FTBchCode
                        AND CRVDTSL.FTShpCode = SPS.FTShpCode AND CRVDTSL.FTPosCode = SPS.FTPosCode
                        WHERE HD.FTXshDocNo = ".$this->db->escape($tCRVDocNo)." 
                        AND HD.FTBchCode = ".$this->db->escape($tCRVBchCode);
                        


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
        unset($tCRVDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($aDetail);
        unset($oQuery);
        return $aResult;
    }

    // Get Data Document HD Spl
    // public function FSaMCRVGetDataDocHDSpl($paDataWhere){
    //     $tCRVDocNo   = $paDataWhere['FTXshDocNo'];
    //     $nLngID     = $paDataWhere['FNLngID'];
    //     $tSQL       = " 
    //         SELECT
    //             HDSPL.FTBchCode,
    //             HDSPL.FTXshDocNo,
    //             HDSPL.FTXshDstPaid,
    //             HDSPL.FNXshCrTerm,
    //             HDSPL.FDXshDueDate,
    //             HDSPL.FDXshBillDue,
    //             HDSPL.FTXshCtrName,
    //             HDSPL.FDXshTnfDate,
    //             HDSPL.FTXshRefTnfID,
    //             HDSPL.FTXshRefVehID,
    //             HDSPL.FTXshRefInvNo,
    //             HDSPL.FTXshQtyAndTypeUnit,
    //             HDSPL.FNXshShipAdd,
    //             SHIP_Add.FTAddV1No              AS FTXshShipAddNo,
    //             SHIP_Add.FTAddV1Soi				AS FTXshShipAddPoi,
    //             SHIP_Add.FTAddV1Village         AS FTXshShipAddVillage,
    //             SHIP_Add.FTAddV1Road			AS FTXshShipAddRoad,
    //             SHIP_SUDIS.FTSudName			AS FTXshShipSubDistrict,
    //             SHIP_DIS.FTDstName				AS FTXshShipDistrict,
    //             SHIP_PVN.FTPvnName				AS FTXshShipProvince,
    //             SHIP_Add.FTAddV1PostCode	    AS FTXshShipPosCode,
    //             HDSPL.FNXshTaxAdd,
    //             TAX_Add.FTAddV1No               AS FTXshTaxAddNo,
    //             TAX_Add.FTAddV1Soi				AS FTXshTaxAddPoi,
    //             TAX_Add.FTAddV1Village		    AS FTXshTaxAddVillage,
    //             TAX_Add.FTAddV1Road				AS FTXshTaxAddRoad,
    //             TAX_SUDIS.FTSudName				AS FTXshTaxSubDistrict,
    //             TAX_DIS.FTDstName               AS FTXshTaxDistrict,
    //             TAX_PVN.FTPvnName               AS FTXshTaxProvince,
    //             TAX_Add.FTAddV1PostCode		    AS FTXshTaxPosCode
    //         FROM TSVTBookHDSpl HDSPL  WITH (NOLOCK)
    //         LEFT JOIN TCNMAddress_L			SHIP_Add    WITH (NOLOCK)   ON HDSPL.FNXshShipAdd       = SHIP_Add.FNAddSeqNo	AND SHIP_Add.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMSubDistrict_L     SHIP_SUDIS 	WITH (NOLOCK)	ON SHIP_Add.FTAddV1SubDist	= SHIP_SUDIS.FTSudCode	AND SHIP_SUDIS.FNLngID  = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMDistrict_L        SHIP_DIS    WITH (NOLOCK)	ON SHIP_Add.FTAddV1DstCode	= SHIP_DIS.FTDstCode    AND SHIP_DIS.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMProvince_L        SHIP_PVN    WITH (NOLOCK)	ON SHIP_Add.FTAddV1PvnCode	= SHIP_PVN.FTPvnCode    AND SHIP_PVN.FNLngID    = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMAddress_L			TAX_Add     WITH (NOLOCK)   ON HDSPL.FNXshTaxAdd        = TAX_Add.FNAddSeqNo	AND TAX_Add.FNLngID		= ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMSubDistrict_L     TAX_SUDIS 	WITH (NOLOCK)	ON TAX_Add.FTAddV1SubDist   = TAX_SUDIS.FTSudCode	AND TAX_SUDIS.FNLngID	= ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMDistrict_L        TAX_DIS     WITH (NOLOCK)	ON TAX_Add.FTAddV1DstCode   = TAX_DIS.FTDstCode     AND TAX_DIS.FNLngID     = ".$this->db->escape($nLngID)."
    //         LEFT JOIN TCNMProvince_L        TAX_PVN     WITH (NOLOCK)	ON TAX_Add.FTAddV1PvnCode   = TAX_PVN.FTPvnCode		AND TAX_PVN.FNLngID     = ".$this->db->escape($nLngID)."
    //         WHERE HDSPL.FTXshDocNo = ".$this->db->escape($tCRVDocNo)."
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     if ($oQuery->num_rows() > 0){
    //         $aDetail = $oQuery->row_array();
    //         $aResult    = array(
    //             'raItems'   => $aDetail,
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'success',
    //         );
    //     }else{
    //         $aResult    = array(
    //             'rtCode'    => '800',
    //             'rtDesc'    => 'data not found.',
    //         );
    //     }
    //     unset($tCRVDocNo);
    //     unset($nLngID);
    //     unset($tSQL);
    //     unset($aDetail);
    //     unset($oQuery);
    //     return $aResult;

    // }

    //ลบข้อมูลใน Temp
    // public function FSnMCRVDelALLTmp($paData){
    //     try {
    //         $this->db->trans_begin();
    //         $this->db->where('FTSessionID', $paData['FTSessionID']);
    //         $this->db->delete('TSVTBookDocDTTmp');
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aStatus = array(
    //                 'rtCode' => '905',
    //                 'rtDesc' => 'Cannot Delete Item.',
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $aStatus = array(
    //                 'rtCode' => '1',
    //                 'rtDesc' => 'Delete Complete.',
    //             );
    //         }
    //         return $aStatus;
    //     } catch (Exception $Error) {
    //         return $Error;
    //     }
    // }

    //ย้ายจาก DT To Temp
    // public function FSxMCRVMoveDTToDTTemp($paDataWhere){
    //     $tCRVDocNo       = $paDataWhere['FTXshDocNo'];
    //     $tDocKey        = $paDataWhere['FTXthDocKey'];
    //     // Delect Document DTTemp By Doc No
    //     $this->db->where('FTXthDocNo',$tCRVDocNo);
    //     $this->db->where('FTSessionID',$this->session->userdata('tSesSessionID'));
    //     $this->db->delete('TSVTBookDocDTTmp');
    //     $tSQL   = " 
    //         INSERT INTO TSVTBookDocDTTmp (
    //             FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
    //             FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //             FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
    //         )
    //         SELECT
    //             DT.FTBchCode,
    //             DT.FTXshDocNo,
    //             DT.FNXpdSeqNo,
    //             CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
    //             DT.FTPdtCode,
    //             DT.FTXpdPdtName,
    //             DT.FTPunCode,
    //             DT.FTPunName,
    //             DT.FCXpdFactor,
    //             DT.FTXpdBarCode,
    //             DT.FCXpdQty,
    //             DT.FCXpdQtyAll,
    //             DT.FCXpdQtyLef,
    //             DT.FCXpdQtyRfn,
    //             DT.FTXpdStaPrcStk,
    //             DT.FTXpdStaAlwDis,
    //             DT.FNXpdPdtLevel,
    //             DT.FTXpdPdtParent,
    //             DT.FCXpdQtySet,
    //             DT.FTPdtStaSet,
    //             DT.FTXpdRmk,
    //             PDT.FTPdtType,
    //             CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
    //             CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
    //             CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
    //             CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
    //             CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
    //         FROM TSVTBookDT AS DT WITH (NOLOCK)
    //         LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON  PDT.FTPdtCode = DT.FTPdtCode
    //         WHERE DT.FTXshDocNo = ".$this->db->escape($tCRVDocNo)."
    //         ORDER BY DT.FNXpdSeqNo ASC
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     unset($tCRVDocNo);
    //     unset($tDocKey);
    //     unset($tSQL);
    //     unset($oQuery);
    //     return;
    // }

    // นำข้อมูลจาก Browse ลง DTTemp
    // public function FSoMCRVCallRefIntDocInsertDTToTemp($paData, $ptDocType){
    //     $tCRVDocNo           = $paData['tCRVDocNo'];
    //     $tCRVFrmBchCode      = $paData['tCRVFrmBchCode'];
    //     $tCRVOptionAddPdt    = $paData['tCRVOptionAddPdt']; 
    //     $tRefIntDocNo       = $paData['tRefIntDocNo'];
    //     $tRefIntBchCode     = $paData['tRefIntBchCode'];
    //     $tSessionID         = $this->session->userdata('tSesSessionID');
    //     $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';
    //     $oQueryCheckTempDocType = $this->FSnMCRVCheckTempDocType($paData);
    //     if ($oQueryCheckTempDocType['raItems'] == '') {
    //         //ลบรายการสินค้า
    //         $this->db->where('FTXthDocNo',$tCRVDocNo);
    //         $this->db->where('FTSessionID',$paData['tSessionID']);
    //         $this->db->delete('TSVTBookDocDTTmp');
    //     }elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
    //         //ลบรายการสินค้า
    //         $this->db->where('FTXthDocNo',$tCRVDocNo);
    //         $this->db->where('FTSessionID',$paData['tSessionID']);
    //         $this->db->delete('TSVTBookDocDTTmp');
    //         //ลบรายการอ้างอิง
    //         $tClearDocDocRefTemp =   "
    //             DELETE FROM TSVTBookDocHDRefTmp
    //             WHERE  TSVTBookDocHDRefTmp.FTXthDocNo  = ".$this->db->escape($paData['tCRVDocNo'])."
    //             AND TSVTBookDocHDRefTmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
    //         ";
    //         $this->db->query($tClearDocDocRefTemp);
    //     }
    //     if($tCRVOptionAddPdt == 1){
    //         $tSQLSelectDT   = "
    //             SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXpdBarCode  , DT.FNXpdSeqNo , DT.FCXpdQty
    //             FROM TAPTPoDT DT WITH(NOLOCK)
    //             WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";
    //         $oQuery = $this->db->query($tSQLSelectDT);
            
    //         $tSQLGetSeqPDT = "
    //             SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
    //             FROM TSVTBookDocDTTmp WITH(NOLOCK)
    //             WHERE FTSessionID = ".$this->db->escape($tSessionID)."
    //             AND FTXthDocKey = 'TSVTBookDT'
    //         ";
    //         $oQuerySeq = $this->db->query($tSQLGetSeqPDT);
    //         $aResultDTSeq = $oQuerySeq->row_array();
    //         if ($oQuery->num_rows() > 0) {
    //             $aResultDT      = $oQuery->result_array();
    //             $nCountResultDT = count($aResultDT);
    //             if($nCountResultDT >= 0){
    //                 for($j=0; $j<$nCountResultDT; $j++){
    //                     $tSQL   =   "
    //                         SELECT FNXtdSeqNo , FCXtdQty 
    //                         FROM TSVTBookDocDTTmp WITH(NOLOCK)
    //                         WHERE FTXthDocNo            = ".$this->db->escape($tCRVDocNo)."
    //                         AND FTBchCode               = ".$this->db->escape($tCRVFrmBchCode)."
    //                         AND FTXthDocKey             = 'TSVTBookDT'
    //                         AND FTSessionID             = ".$this->db->escape($tSessionID)."
    //                         AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
    //                         AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
    //                         AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXpdBarCode"])." 
    //                         ORDER BY FNXtdSeqNo ";
    //                     $oQuery = $this->db->query($tSQL);
    //                     if ($oQuery->num_rows() > 0) {

    //                         // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
    //                         $aResult    =   $oQuery->row_array();
    //                         $tSQL       =   "
    //                             UPDATE TSVTBookDocDTTmp
    //                             SET FCXtdQty = '".($aResult["FCXtdQty"] + $aResultDT[$j]["FCXpdQty"] )."'
    //                             WHERE FTXthDocNo            = ".$this->db->escape($tCRVDocNo)."
    //                             AND FTBchCode               = ".$this->db->escape($tCRVFrmBchCode)."
    //                             AND FNXtdSeqNo              = ".$this->db->escape($aResult["FNXtdSeqNo"])."
    //                             AND FTXthDocKey             = 'TSVTBookDT'
    //                             AND FTSessionID             = ".$this->db->escape($tSessionID)."
    //                             AND FTPdtCode               = ".$this->db->escape($aResultDT[$j]["FTPdtCode"])."
    //                             AND FTPunCode               = ".$this->db->escape($aResultDT[$j]["FTPunCode"])." 
    //                             AND ISNULL(FTXtdBarCode,'') = ".$this->db->escape($aResultDT[$j]["FTXpdBarCode"])." "; 
    //                         $this->db->query($tSQL);
    //                     }else{
    //                         $tSQL= "INSERT INTO TSVTBookDocDTTmp (
    //                             FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
    //                             FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //                             FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
    //                             SELECT
    //                                 '$tCRVFrmBchCode' as FTBchCode,
    //                                 '$tCRVDocNo' as FTXshDocNo,
    //                                 ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXpdSeqNo,
    //                                 'TSVTBookDT' AS FTXthDocKey,
    //                                 DT.FTPdtCode,
    //                                 DT.FTXpdPdtName,
    //                                 DT.FTPunCode,
    //                                 DT.FTPunName,
    //                                 DT.FCXpdFactor,
    //                                 DT.FTXpdBarCode,
    //                                 DT.FCXpdQtyLef AS FCXtdQty,
    //                                 DT.FCXpdQtyLef*DT.FCXpdFactor AS FCXtdQtyAll,
    //                                 0 as FCXpdQtyLef,
    //                                 0 as FCXpdQtyRfn,
    //                                 '' as FTXpdStaPrcStk,
    //                                 PDT.FTPdtStaAlwDis,
    //                                 0 as FNXpdPdtLevel,
    //                                 '' as FTXpdPdtParent,
    //                                 0 as FCXpdQtySet,
    //                                 '' as FTPdtStaSet,
    //                                 '' as FTXpdRmk,
    //                                 PDT.FTPdtType,
    //                                 CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
    //                                 CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
    //                                 CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
    //                                 CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
    //                                 CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
    //                             FROM
    //                                 TAPTPoDT DT WITH (NOLOCK)
    //                                 LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
    //                             WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." 
    //                             AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." 
    //                             AND DT.FNXpdSeqNo = ".$this->db->escape($aResultDT[$j]["FNXpdSeqNo"])."
    //                             ";
                            
    //                         $oQuery = $this->db->query($tSQL);
    //                     }
    //                 }
    //             }
    //         }
    //     }else{
    //         $tSQL   = "
    //             INSERT INTO TSVTBookDocDTTmp (
    //                 FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
    //                 FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //                 FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
    //             )
    //             SELECT
    //                 '$tCRVFrmBchCode' as FTBchCode,
    //                 '$tCRVDocNo' as FTXshDocNo,
    //                 DT.FNXpdSeqNo,
    //                 'TSVTBookDT' AS FTXthDocKey,
    //                 DT.FTPdtCode,
    //                 DT.FTXpdPdtName,
    //                 DT.FTPunCode,
    //                 DT.FTPunName,
    //                 DT.FCXpdFactor,
    //                 DT.FTXpdBarCode,
    //                 DT.FCXpdQtyLef AS FCXtdQty,
    //                 DT.FCXpdQtyLef AS FCXtdQtyAll,
    //                 0 as FCXpdQtyLef,
    //                 0 as FCXpdQtyRfn,
    //                 '' as FTXpdStaPrcStk,
    //                 PDT.FTPdtStaAlwDis,
    //                 0 as FNXpdPdtLevel,
    //                 '' as FTXpdPdtParent,
    //                 0 as FCXpdQtySet,
    //                 '' as FTPdtStaSet,
    //                 '' as FTXpdRmk,
    //                 PDT.FTPdtType,
    //                 '".$this->session->userdata('tSesSessionID')."' AS FTSessionID,
    //                 CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDLastUpdOn,
    //                 CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDCreateOn,
    //                 '".$this->session->userdata('tSesUsername')."'  AS FTLastUpdBy,
    //                 '".$this->session->userdata('tSesUsername')."'  AS FTCreateBy
    //             FROM TAPTPoDT DT WITH (NOLOCK)
    //             LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
    //             WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXpdSeqNo IN $aSeqNo
    //             ";

    //         $oQuery = $this->db->query($tSQL);
    //     }
    //     unset($tCRVDocNo,$tCRVFrmBchCode,$tCRVOptionAddPdt,$tRefIntDocNo,$tRefIntBchCode,$tSessionID,$aSeqNo,$oQueryCheckTempDocType,$tClearDocDocRefTemp);
    //     unset($tSQLSelectDT,$oQuery,$tSQLGetSeqPDT,$oQuerySeq,$aResultDTSeq,$aResultDT,$nCountResultDT,$tSQL);
    //     unset($oQuery);
    // }

    // นำข้อมูลจาก Browse ลง DTTemp
    // public function FSoMCRVCallRefIntABBDocInsertDTToTemp($paData, $ptDocType){
    //     $tCRVDocNo               = $paData['tCRVDocNo'];
    //     $tCRVFrmBchCode          = $paData['tCRVFrmBchCode'];
    //     $oQueryCheckTempDocType = $this->FSnMCRVCheckTempDocType($paData);
    //     $tRefIntDocNo           = $paData['tRefIntDocNo'];
    //     $tRefIntBchCode         = $paData['tRefIntBchCode'];
    //     $aSeqNo                 = '(' . implode(',', $paData['aSeqNo']) .')';
    //     $tCRVOptionAddPdt        = $paData['tCRVOptionAddPdt']; 
    //     $tSessionID             = $this->session->userdata('tSesSessionID');

    //     // Delect Document DTTemp By Doc No
    //     if ($oQueryCheckTempDocType['raItems'] == '') {
    //         //ลบรายการสินค้า
    //         $this->db->where('FTXthDocNo',$tCRVDocNo);
    //         $this->db->where('FTSessionID',$paData['tSessionID']);
    //         $this->db->delete('TSVTBookDocDTTmp');
    //     }elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
    //         //ลบรายการสินค้า
    //         $this->db->where('FTXthDocNo',$tCRVDocNo);
    //         $this->db->where('FTSessionID',$paData['tSessionID']);
    //         $this->db->delete('TSVTBookDocDTTmp');

    //         //ลบรายการอ้างอิง
    //         $tClearDocDocRefTemp    = "
    //             DELETE FROM TSVTBookDocHDRefTmp
    //             WHERE  TSVTBookDocHDRefTmp.FTXthDocNo  = ".$this->db->escape($paData['tCRVDocNo'])."
    //             AND TSVTBookDocHDRefTmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
    //         ";
    //         $this->db->query($tClearDocDocRefTemp);
    //     }

    //     $tSQL   = " INSERT INTO TSVTBookDocDTTmp (
    //                     FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
    //                     FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //                     FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy
    //                 )
    //                 SELECT
    //                     '$tCRVFrmBchCode' as FTBchCode,
    //                     '$tCRVDocNo' as FTXshDocNo,
    //                     DT.FNXsdSeqNo,
    //                     'TSVTBookDT' AS FTXthDocKey,
    //                     DT.FTPdtCode,
    //                     DT.FTXsdPdtName,
    //                     DT.FTPunCode,
    //                     DT.FTPunName,
    //                     DT.FCXsdFactor,
    //                     DT.FTXsdBarCode,
    //                     DT.FCXsdQtyLef AS FCXtdQty,
    //                     DT.FCXsdQtyLef AS FCXtdQtyAll,
    //                     0 as FCXsdQtyLef,
    //                     0 as FCXsdQtyRfn,
    //                     '' as FTXsdStaPrcStk,
    //                     PDT.FTPdtStaAlwDis,
    //                     0 as FNXsdPdtLevel,
    //                     '' as FTXsdPdtParent,
    //                     0 as FCXsdQtySet,
    //                     '' as FTPdtStaSet,
    //                     '' as FTXsdRmk,
    //                     PDT.FTPdtType,
    //                     '".$this->session->userdata('tSesSessionID')."' AS FTSessionID,
    //                     CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDLastUpdOn,
    //                     CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')     AS FDCreateOn,
    //                     '".$this->session->userdata('tSesUsername')."'  AS FTLastUpdBy,
    //                     '".$this->session->userdata('tSesUsername')."'  AS FTCreateBy
    //                 FROM TPSTSalDT DT WITH (NOLOCK)
    //                 LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
    //                 WHERE  DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXsdSeqNo IN $aSeqNo ";
    //     $oQuery = $this->db->query($tSQL);
        
    //     unset($tCRVDocNo,$tCRVFrmBchCode,$oQueryCheckTempDocType,$tRefIntDocNo,$tRefIntBchCode,$aSeqNo,$tCRVOptionAddPdt,$tSessionID);
    //     unset($tSQLSelectDT,$tSQLGetSeqPDT,$aResultDTSeq,$aResultDT,$nCountResultDT);
    //     unset($oQuery);
    // }

    // public function FSnMCRVCheckTempDocType($paData){
    //     $tSQL   = "
    //         SELECT
    //             Tmp.FTXthRefKey
    //         FROM TSVTBookDocHDRefTmp Tmp WITH(NOLOCK)
    //         WHERE Tmp.FTXthDocNo    = ".$this->db->escape($paData['tCRVDocNo'])." 
    //         AND Tmp.FTXthDocKey     = ".$this->db->escape($paData['tDocKey'])."
    //         AND Tmp.FTSessionID     = ".$this->db->escape($paData['tSessionID'])."
    //     ";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         $oDataList          = $oQuery->result_array();
    //         $aResult    = array(
    //             'raItems'   => $oDataList,
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'success',
    //         );
    //     }else{
    //         $aResult    = array(
    //             'raItems'   => '',
    //             'rtCode'    => '800',
    //             'rtDesc'    => 'data not found',
    //         );
    //     }
    //     unset($oQuery);
    //     unset($tSQL);
    //     unset($oDataList);
    //     return $aResult;
    // }

    // Delete Purchase Invoice Document
    // public function FSnMCRVDelDocument($paDataDoc){
    //     $tDataDocNo = $paDataDoc['tDataDocNo'];
    //     $tBchCode   = $paDataDoc['tBchCode'];
    //     $this->db->trans_begin();

    //     // Document HD
    //     $this->db->where('FTXshDocNo',$tDataDocNo);
    //     $this->db->where('FTBchCode',$tBchCode);
    //     $this->db->delete('TSVTBookHD');

    //     // Document DT
    //     $this->db->where('FTXshDocNo',$tDataDocNo);
    //     $this->db->where('FTBchCode',$tBchCode);
    //     $this->db->delete('TSVTBookDT');

    //     // Document HD
    //     $this->db->where('FTXshDocNo',$tDataDocNo);
    //     $this->db->where('FTBchCode',$tBchCode);
    //     $this->db->delete('TSVTBookHDSpl');

    //     $this->db->where('FTXshDocNo',$tDataDocNo);
    //     $this->db->delete('TSVTBookHDDocRef');

    //     $this->db->where('FTXshRefDocNo',$tDataDocNo);
    //     $this->db->delete('TAPTPoHDDocRef');

    //     $this->db->where('FTXshRefDocNo',$tDataDocNo);
    //     $this->db->delete('TPSTSalHDDocRef');


    //     if($this->db->trans_status() === FALSE){
    //         $this->db->trans_rollback();
    //         $aStaDelDoc     = array(
    //             'rtCode'    => '905',
    //             'rtDesc'    => 'Cannot Delete Item.',
    //         );
    //     }else{
    //         $this->db->trans_commit();
    //         $aStaDelDoc     = array(
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'Delete Complete.',
    //         );
    //     }
    //     unset($tDataDocNo);
    //     unset($tBchCode);
    //     return $aStaDelDoc;
    // }

    // Cancel Document Data
    // public function FSaMCRVCancelDocument($paDataUpdate){
    //     // TAPTPoHD
    //     $this->db->trans_begin();
    //     //$this->db->set('FTXshStaApv' , ' ');
    //     $this->db->set('FTXshStaDoc' , '3');
    //     $this->db->where('FTXshDocNo', $paDataUpdate['tDocNo']);
    //     $this->db->update('TSVTBookHD');

    //     // (ย้ายไป ลบตอน หลังอนุมัติที่ C# แทน เพราะต้องหา QTY แบบย้อนกลับ)
    //     // $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo'])->delete('TSVTBookHDDocRef');
    //     // $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo'])->delete('TAPTPoHDDocRef');

    //     if($this->db->trans_status() === FALSE){
    //         $this->db->trans_rollback();
    //         $aDatRetrun = array(
    //             'nStaEvent' => '900',
    //             'tStaMessg' => "Error Cannot Update Status Cancel Document."
    //         );
    //     }else{
    //         $this->db->trans_commit();
    //         $aDatRetrun = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => "Update Status Document Cancel Success."
    //         );
    //     }
    //     return $aDatRetrun;
    // }

    //อนุมัตเอกสาร
    // public function FSaMCRVApproveDocument($paDataUpdate){
    //     $dLastUpdOn = date('Y-m-d H:i:s');
    //     $tLastUpdBy = $this->session->userdata('tSesUsername');
    //     $this->db->set('FDLastUpdOn',$dLastUpdOn);
    //     $this->db->set('FTLastUpdBy',$tLastUpdBy);
    //     $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
    //     $this->db->set('FTXshApvCode',$paDataUpdate['FTXshUsrApv']);
    //     $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
    //     $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
    //     $this->db->update('TSVTBookHD');
    //     if ($this->db->affected_rows() > 0) {
    //         $aStatus = array(
    //             'rtCode' => '1',
    //             'rtDesc' => 'Updated Status Document Cancel Success.',
    //         );
    //     } else {
    //         $aStatus = array(
    //             'rtCode' => '903',
    //             'rtDesc' => 'Not Update Status Document.',
    //         );
    //     }
    //     unset($dLastUpdOn);
    //     unset($tLastUpdBy);
    //     return $aStatus;
    // }

    // public function FSaMCRVUpdatePOStaPrcDoc($ptRefInDocNo){
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

    // public function FSaMCRVUpdatePOStaRef($ptRefInDocNo, $pnStaRef){
    //     $this->db->set('FNXshStaRef',$pnStaRef);
    //     $this->db->where('FTXshDocNo',$ptRefInDocNo);
    //     $this->db->update('TAPTPoHD');
    //     if ($this->db->affected_rows() > 0) {
    //         $aStatus    = array(
    //             'rtCode'    => '1',
    //             'rtDesc'    => 'Updated Status Document Success.',
    //         );
    //     } else {
    //         $aStatus    = array(
    //             'rtCode'    => '903',
    //             'rtDesc'    => 'Not Update Status Document.',
    //         );
    //     }
    //     return $aStatus;
    // }

    // public function FSaMCRVQaAddUpdateRefDocHD($aDataWhere, $aTableAddUpdate, $aDataWhereDocRefCRV, $aDataWhereDocRefPO, $aDataWhereDocRefCRVExt){
    //     try {
    //         $tTableRefCRV    = $aTableAddUpdate['tTableRefCRV'];
    //         $tTableRefPO    = $aTableAddUpdate['tTableRefPO'];
    //         if ($aDataWhereDocRefCRV != '') {
    //             $nChhkDataDocRefCRV  = $this->FSaMCRVChkRefDupicate($aDataWhere, $tTableRefCRV, $aDataWhereDocRefCRV);
    //             //หากพบว่าซ้ำ
    //             if(isset($nChhkDataDocRefCRV['rtCode']) && $nChhkDataDocRefCRV['rtCode'] == 1){
    //                 //ลบ
    //                 $this->db->where('FTAgnCode',$aDataWhereDocRefCRV['FTAgnCode']);
    //                 $this->db->where('FTBchCode',$aDataWhereDocRefCRV['FTBchCode']);
    //                 $this->db->where('FTXshDocNo',$aDataWhereDocRefCRV['FTXshDocNo']);
    //                 $this->db->where('FTXshRefType',$aDataWhereDocRefCRV['FTXshRefType']);
    //                 $this->db->where('FTXshRefDocNo',$aDataWhereDocRefCRV['FTXshRefDocNo']);
    //                 $this->db->delete($tTableRefCRV);
    //                 $this->db->last_query();
    //                 //เพิ่มใหม่
    //                 $this->db->insert($tTableRefCRV,$aDataWhereDocRefCRV);
    //             //หากพบว่าไม่ซ้ำ
    //             }else{
    //                 $this->db->insert($tTableRefCRV,$aDataWhereDocRefCRV);
    //             }
    //         }
    //         if ($aDataWhereDocRefPO != '') {
    //             $nChhkDataDocRefPO  = $this->FSaMCRVChkRefDupicate($aDataWhere, $tTableRefPO, $aDataWhereDocRefPO);
    //             //หากพบว่าซ้ำ
    //             if(isset($nChhkDataDocRefPO['rtCode']) && $nChhkDataDocRefPO['rtCode'] == 1){
    //                 //ลบ
    //                 $this->db->where('FTAgnCode',$aDataWhereDocRefPO['FTAgnCode']);
    //                 $this->db->where('FTBchCode',$aDataWhereDocRefPO['FTBchCode']);
    //                 $this->db->where('FTXshDocNo',$aDataWhereDocRefPO['FTXshDocNo']);
    //                 $this->db->where('FTXshRefType',$aDataWhereDocRefPO['FTXshRefType']);
    //                 $this->db->where('FTXshRefDocNo',$aDataWhereDocRefPO['FTXshRefDocNo']);
    //                 $this->db->delete($tTableRefPO);
    //                 //เพิ่มใหม่
    //                 $this->db->insert($tTableRefPO,$aDataWhereDocRefPO);
    //             //หากพบว่าไม่ซ้ำ
    //             }else{
    //                 $this->db->insert($tTableRefPO,$aDataWhereDocRefPO);
    //             }
    //         }
    //         if ($aDataWhereDocRefCRVExt != '') {
    //             $nChhkDataDocRefExt  = $this->FSaMCRVChkRefDupicate($aDataWhere, $tTableRefCRV, $aDataWhereDocRefCRVExt);
    //             //หากพบว่าซ้ำ
    //             if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
    //                 //ลบ
    //                 $this->db->where('FTAgnCode',$aDataWhereDocRefCRVExt['FTAgnCode']);
    //                 $this->db->where('FTBchCode',$aDataWhereDocRefCRVExt['FTBchCode']);
    //                 $this->db->where('FTXshDocNo',$aDataWhereDocRefCRVExt['FTXshDocNo']);
    //                 $this->db->where('FTXshRefType',$aDataWhereDocRefCRVExt['FTXshRefType']);
    //                 $this->db->where('FTXshRefDocNo',$aDataWhereDocRefCRVExt['FTXshRefDocNo']);
    //                 $this->db->delete($tTableRefCRV);
    //                 //เพิ่มใหม่
    //                 $this->db->insert($tTableRefCRV,$aDataWhereDocRefCRVExt);
    //             //หากพบว่าไม่ซ้ำ
    //             }else{
    //                 $this->db->insert($tTableRefCRV,$aDataWhereDocRefCRVExt);
    //             }
    //         }
    //         $aReturnData = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'insert DocRef success'
    //         );
    //     }catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     unset($tTableRefCRV);
    //     unset($tTableRefPO);
    //     unset($nChhkDataDocRefCRV);
    //     unset($nChhkDataDocRefPO);
    //     unset($nChhkDataDocRefExt);
    //     return $aReturnData;
    // }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    // public function FSaMCRVChkRefDupicate($aDataWhere, $tTableRef, $aDataWhereDocRef){
    //     try{
    //         $tAgnCode       = $aDataWhereDocRef['FTAgnCode'];
    //         $tBchCode       = $aDataWhereDocRef['FTBchCode'];
    //         $tDocNo         = $aDataWhereDocRef['FTXshDocNo'];
    //         $tRefDocType    = $aDataWhereDocRef['FTXshRefType'];
    //         $tRefDocNo      = $aDataWhereDocRef['FTXshRefDocNo'];
    //         $tSQL           = "
    //             SELECT
    //                 FTAgnCode,
    //                 FTBchCode,
    //                 FTXshDocNo
    //             FROM $tTableRef WITH(NOLOCK)
    //             WHERE FTXshDocNo    = ".$this->db->escape($tDocNo)."
    //             AND FTAgnCode       = ".$this->db->escape($tAgnCode)."
    //             AND FTBchCode       = ".$this->db->escape($tBchCode)."
    //             AND FTXshRefType    = ".$this->db->escape($tRefDocType)."
    //             AND FTXshRefDocNo   = ".$this->db->escape($tRefDocNo)."
    //         ";
    //         $oQueryHD = $this->db->query($tSQL);
    //         if ($oQueryHD->num_rows() > 0){
    //             $aDetail    = $oQueryHD->row_array();
    //             $aResult    = array(
    //                 'raItems'   => $aDetail,
    //                 'rtCode'    => '1',
    //                 'rtDesc'    => 'success',
    //             );
    //         }else{
    //             $aResult    = array(
    //                 'rtCode'    => '800',
    //                 'rtDesc'    => 'data not found.',
    //             );
    //         }
    //         unset($tAgnCode);
    //         unset($tBchCode);
    //         unset($tDocNo);
    //         unset($tRefDocType);
    //         unset($tRefDocNo);
    //         unset($tRefDocNo);
    //         unset($tSQL);
    //         unset($oQueryHD);
    //         unset($aDetail);
    //         return $aResult;
    //     }catch (Exception $Error) {
    //         echo $Error;
    //     }
    // }
   
    // public function FSaMCRVUpdatePOFNStaRef($ptBchCode,$ptRefInDocNo){
    //     $tSQL   = "
    //         UPDATE POHD
    //         SET POHD.FNXshStaRef = PODT.FNXshStaRef
    //         FROM TAPTPoHD POHD
    //         INNER JOIN (
    //             SELECT
    //                 CHKDTPO.FTBchCode,
    //                 CHKDTPO.FTXshDocNo,
    //                 CASE WHEN CHKDTPO.FNSumQtyLef = '0' THEN '2' ELSE '1' END AS FNXshStaRef
    //             FROM (
    //                 SELECT
    //                     PODT.FTBchCode,
    //                     PODT.FTXshDocNo,
    //                     SUM(PODT.FCXpdQtyLef) AS FNSumQtyLef
    //                 FROM TAPTPoDT PODT WITH(NOLOCK)
    //                 WHERE PODT.FTBchCode    = ".$this->db->escape($ptBchCode)." AND PODT.FTXshDocNo = ".$this->db->escape($ptRefInDocNo)."
    //                 GROUP BY PODT.FTBchCode,PODT.FTXshDocNo
    //             ) CHKDTPO
    //         ) PODT ON POHD.FTBchCode = PODT.FTBchCode AND POHD.FTXshDocNo = PODT.FTXshDocNo
    //     ";
    //     $this->db->query($tSQL);
    // }

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMCRVGetDataHDRefTmp($paData){
        // $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        // $FTXthDocKey    = $paData['FTXthDocKey'];
        // $FTSessionID    = $paData['FTSessionID'];

        // FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
        $tSQL = "   SELECT
                        FTXshDocNo AS FTXthDocNo, 
                        FTXshRefDocNo AS FTXthRefDocNo, 
                        FTXshRefType AS FTXthRefType, 
                        FTXshRefKey AS FTXthRefKey,
                        FDXshRefDocDate AS FDXthRefDocDate 
                    FROM TRTTSalHDDocRef WTIH (NOLOCK) 
                    WHERE FTXshDocNo  = ".$this->db->escape($FTXthDocNo);
        // $tSQL = "   SELECT 
        //                 FTXshDocNo AS FTXthDocNo, 
        //                 FTXshRefInt AS FTXthRefDocNo, 
        //                 '1' AS FTXthRefType,
        //                 'BK' AS FTXthRefKey,
        //                 FDXshRefIntDate AS FDXthRefDocDate
        //             FROM TRTTSalHD WITH(NOLOCK)
        //             WHERE FTXshDocNo  = ".$this->db->escape($FTXthDocNo)."

        //             UNION ALL 

        //             SELECT 
        //                 FTXshDocNo AS FTXthDocNo, 
        //                 FTXshRefExt AS FTXthRefDocNo, 
        //                 '3' AS FTXthRefType,
        //                 'N/A' AS FTXthRefKey,
        //                 FDXshRefExtDate AS FDXthRefDocDate
        //             FROM TRTTSalHD WITH(NOLOCK)
        //             WHERE FTXshDocNo  = ".$this->db->escape($FTXthDocNo)."
        // ";

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
    // public function FSaMCRVAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

    //     $tRefDocNo = ( empty($paDataWhere['tSORefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tSORefDocNoOld'] );
    //     // $tRefDocNo = $paDataAddEdit['FTXthRefDocNo'];

    //     $tSQL = " SELECT FTXthRefDocNo FROM TSVTBookDocHDRefTmp WITH(NOLOCK)
    //                 WHERE FTXthDocNo    = ".$this->db->escape($paDataWhere['FTXthDocNo'])."
    //                 AND FTXthDocKey   = ".$this->db->escape($paDataWhere['FTXthDocKey'])."
    //                 AND FTSessionID   = ".$this->db->escape($paDataWhere['FTSessionID'])."
    //                 AND FTXthRefDocNo = ".$this->db->escape($tRefDocNo)."
    //             ";
    //     $oQuery = $this->db->query($tSQL);
        
    //     $this->db->trans_begin();
    //     if ( $oQuery->num_rows() > 0 ){
    //         $this->db->where('FTXthRefDocNo',$paDataWhere['tSORefDocNoOld']);
    //         $this->db->where('FTXthDocNo',$paDataWhere['FTXthDocNo']);
    //         $this->db->where('FTXthDocKey',$paDataWhere['FTXthDocKey']);
    //         $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
    //         $this->db->update('TSVTBookDocHDRefTmp',$paDataAddEdit);
    //         $aResult = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'Update Doc Ref Success'
    //         );
    //     }else{
    //         $aDataAdd = array_merge($paDataAddEdit,array(
    //             'FTXthDocNo'  => $paDataWhere['FTXthDocNo'],
    //             'FTXthDocKey' => $paDataWhere['FTXthDocKey'],
    //             'FTSessionID' => $paDataWhere['FTSessionID'],
    //             'FDCreateOn'  => $paDataWhere['FDCreateOn'],
    //         ));
    //         $this->db->insert('TSVTBookDocHDRefTmp',$aDataAdd);
    //         $aResult = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'Add/Edit HDDocRef Success'
    //         );
    //     }

    //     if ( $this->db->trans_status() === FALSE ) {
    //         $this->db->trans_rollback();
    //         $aResult = array(
    //             'nStaEvent' => '800',
    //             'tStaMessg' => 'Add/Edit HDDocRef Error'
    //         );
    //     } else {
    //         $this->db->trans_commit();
    //     }
    //     return $aResult;
    // }

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    // public function FSxMCRVMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate,$pnDocType){
    //     $tBchCode     = $paDataWhere['FTBchCode'];
    //     $tDocNo       = $paDataWhere['FTXshDocNo'];
    //     $tSessionID   = $this->session->userdata('tSesSessionID');

    //     $this->db->where('FTXshDocNo',$tDocNo);
    //     $this->db->delete('TSVTBookHDDocRef');

    //     $tSQL   =   "   INSERT INTO TSVTBookHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
    //     $tSQL   .=  "   SELECT
    //                         '' AS FTAgnCode,
    //                         '$tBchCode' AS FTBchCode,
    //                         FTXthDocNo,
    //                         FTXthRefDocNo,
    //                         FTXthRefType,
    //                         FTXthRefKey,
    //                         FDXthRefDocDate
    //                     FROM TSVTBookDocHDRefTmp WITH (NOLOCK)
    //                     WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
    //                         AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
    //                         AND FTSessionID = ".$this->db->escape($tSessionID)." 
    //                 ";
        
    //     $this->db->query($tSQL);

    //     if ($pnDocType == 1) {
    //         $tTableInsert = 'TAPTPoHDDocRef';
    //         $tTableInsertField = 'FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
    //     }else{
    //         $tTableInsert = 'TPSTSalHDDocRef';
    //         $tTableInsertField = 'FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate';
    //     }
        
    //     //Insert PO or ABB
    //     $this->db->where('FTBchCode',$tBchCode);
    //     $this->db->where('FTXshRefDocNo',$tDocNo);
    //     $this->db->delete($tTableInsert);

    //     $tSQL   =   "   INSERT INTO $tTableInsert ($tTableInsertField) ";
    //     if ($pnDocType == 1) {
    //         $tDocKey = 'PO';
    //         $tSQL   .=  "SELECT
    //                         '' AS FTAgnCode,
    //                         '$tBchCode' AS FTBchCode,
    //                         FTXthRefDocNo AS FTXshDocNo,
    //                         FTXthDocNo AS FTXshRefDocNo,
    //                         2,
    //                         'CRV',
    //                         FDXthRefDocDate
    //                     FROM TSVTBookDocHDRefTmp WITH (NOLOCK)
    //                     WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
    //                         AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
    //                         AND FTSessionID = ".$this->db->escape($tSessionID)."
    //                         AND FTXthRefKey = ".$this->db->escape($tDocKey)."  
    //                     ";
    //     }else{
    //         $tDocKey = 'ABB';
    //         $tSQL   .=  "SELECT
    //                         '$tBchCode' AS FTBchCode,
    //                         FTXthRefDocNo AS FTXshDocNo,
    //                         FTXthDocNo AS FTXshRefDocNo,
    //                         2,
    //                         'CRV',
    //                         FDXthRefDocDate
    //                     FROM TSVTBookDocHDRefTmp WITH (NOLOCK)
    //                     WHERE FTXthDocNo  = ".$this->db->escape($tDocNo)."
    //                         AND FTXthDocKey = ".$this->db->escape($paTableAddUpdate['tTableHD'])."
    //                         AND FTSessionID = ".$this->db->escape($tSessionID)."
    //                         AND FTXthRefKey = ".$this->db->escape($tDocKey)."  
    //                     ";
    //     }
        
    //     $this->db->query($tSQL);
    // }

    //ข้อมูล HDDocRef
    // public function FSxMCRVMoveHDRefToHDRefTemp($paData){

    //     $FTXshDocNo     = $paData['FTXshDocNo'];
    //     $FTSessionID    = $this->session->userdata('tSesSessionID');

    //     // Delect Document DTTemp By Doc No
    //     $this->db->where('FTXthDocKey','TSVTBookHD');
    //     $this->db->where('FTSessionID',$FTSessionID);
    //     $this->db->delete('TSVTBookDocHDRefTmp');

    //     $tSQL = "   INSERT INTO TSVTBookDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
    //     $tSQL .= "  SELECT
    //                     FTXshDocNo,
    //                     FTXshRefDocNo,
    //                     FTXshRefType,
    //                     FTXshRefKey,
    //                     FDXshRefDocDate,
    //                     'TSVTBookHD' AS FTXthDocKey,
    //                     '$FTSessionID' AS FTSessionID,
    //                     CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
    //                 FROM TSVTBookHDDocRef WITH (NOLOCK)
    //                 WHERE FTXshDocNo = ".$this->db->escape($FTXshDocNo)." ";
    //     $this->db->query($tSQL);
    // }

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    // public function FSaMCRVDelHDDocRef($paData){
    //     $tSODocNo       = $paData['FTXthDocNo'];
    //     $tSORefDocNo    = $paData['FTXthRefDocNo'];
    //     $tSODocKey      = $paData['FTXthDocKey'];
    //     $tSOSessionID   = $paData['FTSessionID'];

    //     $this->db->where('FTSessionID',$tSOSessionID);
    //     $this->db->where('FTXthDocKey',$tSODocKey);
    //     $this->db->where('FTXthRefDocNo',$tSORefDocNo);
    //     $this->db->where('FTXthDocNo',$tSODocNo);
    //     $this->db->delete('TSVTBookDocHDRefTmp');

    //     if ( $this->db->trans_status() === FALSE ) {
    //         $this->db->trans_rollback();
    //         $aResult = array(
    //             'nStaEvent' => '800',
    //             'tStaMessg' => 'Delete HD Doc Ref Error'
    //         );
    //     } else {
    //         $this->db->trans_commit();
    //         $aResult = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'Delete HD Doc Ref Success'
    //         );
    //     }
    //     return $aResult;
    // }

    // Create By : Napat(Jame) 06/07/2022
    // ยืนยันรับของ
    public function FSaMCRVEventSave($paData){
        $this->db->trans_begin();

        // ยืนยันรับของ
        foreach($paData['aItems'] AS $aValue){
            $this->db->where('FTXshDocNo', $paData['tDocNo']);
            $this->db->where('FTBchCode', $aValue['FTBchCode']);
            $this->db->where('FTShpCode', $aValue['FTShpCode']);
            $this->db->where('FTPosCode', $aValue['FTPosCode']);
            $this->db->where('FNXsdLayNo', $aValue['FNXsdLayNo']);
            $this->db->set('FDXshDatePick', $aValue['FDXshDatePick']);
            $this->db->set('FTRsnCode', $aValue['FTRsnCode']);
            $this->db->update('TRTTSalDTSL');
        }
        // $this->db->where('FTXshDocNo', $paData['tDocNo']);
        // $this->db->where('FTBchCode', $paData['tBchCode']);
        // $this->db->update_batch('TRTTSalDTSL', $paData['aItems'], 'FNXsdSeqNo'); 

        // ถ้ายืนยันรับของครบทุกรายการแล้ว ให้ไปอัพเดทตารางใบสั่งขาย เป็นสถานะ ลูกค้ามารับของแล้ว (TARTSoHD.FTXshStaPrcDoc = '6')

        

        $tSQL2 = "   SELECT COUNT(FNXsdLayNo) AS FNXsdWRecive
        FROM TRTTSalDTSL
        WHERE FTXshDocNo IN
        (
            SELECT FTXshRefDocNo
            FROM TRTTBookHDDocRef
            WHERE FTXshDocNo =
            (
                SELECT FTXshRefDocNo
                FROM TRTTSalHDDocRef
                WHERE FTBchCode = ".$this->db->escape($paData['tBchCode'])."
                        AND FTXshDocNo = ".$this->db->escape($paData['tDocNo'])." 
                        AND FTXshRefKey = 'RTBook' 
            )
                    AND FTXshRefKey = 'RTSale'
                
        )
        AND ISNULL(FDXshDatePick, '') = '' ";

        $oQuery = $this->db->query($tSQL2);
        $aQuery = $oQuery->result_array();
        $nChkReceive = $aQuery[0]['FNXsdWRecive'];

        if($nChkReceive == '0'){
        $tSQL = "   UPDATE TARTSoHD 
                        SET TARTSoHD.FTXshStaPrcDoc = '6' 
                        WHERE TARTSoHD.FTXshDocNo IN (
                            SELECT TOP 1
                                BKREF.FTXshRefDocNo
                            FROM TRTTSalHDDocRef HD WITH(NOLOCK)
                            LEFT JOIN TRTTBookHDDocRef BKREF WITH(NOLOCK) ON BKREF.FTXshDocNo = HD.FTXshRefDocNo AND BKREF.FTXshRefType = '1' AND BKREF.FTXshRefKey = 'SO'
                            LEFT JOIN (
                                SELECT 
                                    B.FTXshDocNo,
                                    CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' /*รับทั้งหมด*/
                                        WHEN B.FTPdtPick > 0 THEN '2'           /*รับบางส่วน*/
                                        ELSE '3'                                /*ยังไม่ได้รับ*/
                                    END AS FTStaPdtPick
                                FROM (
                                    SELECT
                                        FTXshDocNo AS FTXshDocNo,
                                        MAX(FTPdtMax) AS FTPdtMax,
                                        SUM(FTPdtPick) AS FTPdtPick
                                    FROM (
                                        SELECT 
                                            FTXshDocNo,
                                            SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
                                            CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
                                        FROM TRTTSalDTSL WITH(NOLOCK)
                                    ) A
                                    GROUP BY A.FTXshDocNo
                                ) B
                            ) C ON C.FTXshDocNo = HD.FTXshDocNo
                            WHERE HD.FTXshDocNo = ".$this->db->escape($paData['tDocNo'])."
                                AND HD.FTBchCode = ".$this->db->escape($paData['tBchCode'])." 
                        AND HD.FTXshRefType = '1'
                        AND HD.FTXshRefKey = 'RTBook'
                                AND C.FTStaPdtPick = '1'
                        ) ";
                    
        $this->db->query($tSQL);
        }

        if ( $this->db->trans_status() === FALSE ) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => $this->db->error()['message']
            );
        } else {
            $this->db->trans_commit();
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Update Success.'
            );
        }
        return $aResult;

    }
}
