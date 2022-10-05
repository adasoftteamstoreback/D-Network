<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jobrequeststep1_model extends CI_Model
{

    //ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMJR1GetDataTableList($paDataCondition)
    {
        //$aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct']; /*FTBchCode DESC ,FTXshDocNo DESC,*/
        $tSQL   =   "SELECT TOP ".get_cookie('nShowRecordInPageList')." c.* FROM(
                        SELECT  --ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS FNRowID,
                            * FROM (
                            SELECT DISTINCT
                                HD.FTAgnCode,
                                AGNL.FTAgnName,
                                HD.FTBchCode,
                                BCHL.FTBchName,
                                HD.FTXshDocNo,
                                CONVERT(CHAR(10),HD.FDXshDocDate,103) AS FDXshDocDate,
                                CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXshDocTime,
                                DOCREF.FTXshRefDocNo,
                                CONVERT(CHAR(10),DOCREF.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                CONVERT(CHAR(5), DOCREF.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                HD.FTXshStaDoc,
                                HD.FTXshStaApv,
                                USRL.FTUsrName AS FTCreateBy,
                                HD.FDCreateOn ,
                                HDCst.FTCarCode ,
                                CAR.FTCarRegNo ,
                                HDCst.FTXshCstName,
                                HD.FCXshGrand
                            FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                            INNER JOIN TSVTJob1ReqHDCst      HDCst WITH(NOLOCK) ON HD.FTXshDocNo = HDCst.FTXshDocNo
                            LEFT JOIN TSVTJob1ReqHDDocRef   DOCREF WITH(NOLOCK) ON HD.FTXshDocNo = DOCREF.FTXshDocNo AND DOCREF.FTXshRefType = '2' AND DOCREF.FTXshRefKey = 'Job2Ord'
                            INNER JOIN TSVMCar               CAR  WITH(NOLOCK) ON HDCst.FTCarCode = CAR.FTCarCode 
                            LEFT JOIN TCNMAgency_L          AGNL WITH(NOLOCK) ON HD.FTAgnCode  = AGNL.FTAgnCode AND AGNL.FNLngID = '" . $nLngID . "'
                            LEFT JOIN TCNMBranch_L          BCHL WITH(NOLOCK) ON HD.FTBchCode  = BCHL.FTBchCode AND BCHL.FNLngID = '" . $nLngID . "'
                            LEFT JOIN TCNMUser_L            USRL WITH(NOLOCK) ON HD.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = '" . $nLngID . "'
                            WHERE 1=1 ";
        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND HD.FTBchCode IN ($tBchCode) ";
        }
        // ค้นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if (isset($tSearchList) && !empty($tSearchList)) {
            $tSQL .= " AND ((HD.FTXshDocNo LIKE '%$tSearchList%') OR (HDCst.FTXshCstName LIKE '%$tSearchList%') OR (CAR.FTCarRegNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        // ค้นหาจากสาขา - ถึงสาขา
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะเอกสาร
        if (isset($tSearchStaDoc) && !empty($tSearchStaDoc)) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(HD.FTXshStaApv,'') = '' AND HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXshStaDocAct = 0";
            }
        }
        $tSQL   .=  ") Base) AS c ORDER BY c.FDCreateOn DESC  ";

        // $tSQL   .=  " WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = 0; //$this->FSnMJR1CountPageDocListAll($paDataCondition);
            $nFoundRow          = 0; //($aDataCountAllRow['rtCode'] == '1') ? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = 0; //ceil($nFoundRow / $paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
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

    //Count Data All Pagination
    public function FSnMJR1CountPageDocListAll($paDataCondition)
    {
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSQL   =   "
            SELECT COUNT (HD.FTXshDocNo) AS counts
            FROM TSVTJob1ReqHD HD WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
            LEFT JOIN TSVTJob1ReqHDCst      HDCst WITH(NOLOCK) ON HD.FTXshDocNo = HDCst.FTXshDocNo
            LEFT JOIN TSVMCar               CAR  WITH(NOLOCK) ON HDCst.FTCarCode = CAR.FTCarCode 
            WHERE 1=1
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= "
                AND HD.FTBchCode IN ($tBchCode)
            ";
        }
        // ค้นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if (isset($tSearchList) && !empty($tSearchList)) {
            $tSQL .= " AND ((HD.FTXshDocNo LIKE '%$tSearchList%')  OR (HDCst.FTXshCstName LIKE '%$tSearchList%') OR (CAR.FTCarRegNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        // ค้นหาจากสาขา - ถึงสาขา
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะเอกสาร
        if (isset($tSearchStaDoc) && !empty($tSearchStaDoc)) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(HD.FTXshStaApv,'') = '' AND HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXshStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    //ค้นหาข้อมูลลูกค้า
    public function FSaMJR1GetDataCustomer($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCstCode   = $paDataCondition['tCstCode'];
        $tSQL       = " SELECT
                            CST.FTCstCode,
                            CST_L.FTCstName,
                            CST.FTAgnCode,
                            CST.FTCstCardID,
                            CST.FTCstTaxNo,
                            CST.FTCstTel,
                            CST.FTCstFax,
                            CST.FTCstEmail,
                            CGP_L.FTCgpCode,
                            CGP_L.FTCgpName,
                            CTY_L.FTCtyCode,
                            CTY_L.FTCtyName,
                            CLV_L.FTClvCode,
                            CLV_L.FTClvName,
                            USR_L.FTUsrCode,
                            USR_L.FTUsrName,
                            CST.FTCstDiscWhs,
                            CST.FTCstDiscRet,
                            CST.FTCstBusiness,
                            CST.FTCstBchHQ,
                            CST.FTCstBchCode,
                            BCH_L.FTBchName AS FTCstBchName,
                            CST.FTCstStaActive,
                            CST.FTCstStaAlwPosCalSo,
                            CST.FTCstStaOffline
                        FROM TCNMCst CST WITH(NOLOCK)
                        LEFT JOIN TCNMCst_L CST_L WITH(NOLOCK) ON CST.FTCstCode = CST_L.FTCstCode AND CST_L.FNLngID = '$nLngID'
                        LEFT JOIN TCNMCstGrp_L CGP_L WITH(NOLOCK) ON CST.FTCgpCode = CGP_L.FTCgpCode AND CGP_L.FNLngID = '$nLngID'
                        LEFT JOIN TCNMCstType_L CTY_L WITH(NOLOCK) ON CST.FTCtyCode = CTY_L.FTCtyCode AND CTY_L.FNLngID = '$nLngID'
                        LEFT JOIN TCNMCstLev_L CLV_L WITH(NOLOCK) ON CST.FTClvCode = CLV_L.FTClvCode AND CLV_L.FNLngID = '$nLngID'
                        LEFT JOIN TCNMUser_L USR_L WITH(NOLOCK) ON CST.FTUsrCode = USR_L.FTUsrCode AND USR_L.FNLngID = '$nLngID'
                        LEFT JOIN TCNMBranch_L BCH_L WITH(NOLOCK) ON CST.FTCstBchCode = BCH_L.FTBchCode AND BCH_L.FNLngID = '$nLngID'
                        WHERE CST.FTCstCode = '" . $tCstCode . "'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tCstCode);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        return $aDataReturn;
    }

    //ค้นหาข้อมูลลูกค้า - ทีอยู่
    public function FSaMJR1GetDataCustomerAddr($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCstCode   = $paDataCondition['tCstCode'];
        $tSQL       = "
            SELECT
                Addr.FTCstCode,
                Addr.FNLngID,
                Addr.FTAddGrpType,
                Addr.FNAddSeqNo,
                Addr.FTAddRefNo,
                Addr.FTAddName,
                Addr.FTAddRmk,
                Addr.FTAddVersion,
                Addr.FTAddV1No,
                Addr.FTAddV1Soi,
                Addr.FTAddV1Village,
                Addr.FTAddV1Road,
                Addr.FTAddV1SubDist AS FTAddV1SubDistCode,
                SUBL.FTSudName AS FTAddV1SubDistName,
                Addr.FTAddV1DstCode,
                DSTL.FTDstName AS FTAddV1DstName,
                Addr.FTAddV1PvnCode,
                PVNL.FTPvnName	AS FTAddV1PvnName,
                Addr.FTAddV1PostCode,
                Addr.FTAddTel,
                Addr.FTAddFax,
                Addr.FTAddV2Desc1,
                Addr.FTAddV2Desc2,
                Addr.FTAddWebsite,
                Addr.FTAddLongitude,
                Addr.FTAddLatitude
            FROM TCNMCstAddress_L Addr WITH(NOLOCK)
            LEFT JOIN TCNMSubDistrict_L SUBL WITH(NOLOCK) ON Addr.FTAddV1SubDist = SUBL.FTSudCode AND SUBL.FNLngID = '$nLngID'
            LEFT JOIN TCNMDistrict_L DSTL WITH(NOLOCK) ON Addr.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON Addr.FTAddV1PvnCode	= PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
            WHERE 1=1
            AND Addr.FTCstCode = '$tCstCode'
            AND Addr.FNLngID = '1'
            AND Addr.FTAddGrpType = '1'
            AND Addr.FTAddRefNo	= '1';
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tCstCode);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        return $aDataReturn;
    }

    //ค้นหาข้อมูลรถของลูกค้า
    public function FSaMJR1GetDataCarCustomer($paDataCondition, $ptTypeCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tSQL       = "
            SELECT
                CAR.FTCarCode,
                CAR.FTCarRegNo,
                CAR.FTCarEngineNo,
                CAR.FTCarVIDRef,
                CAR.FTCarType       AS FTCarTypeCode,
                T1.FTCaiName        AS FTCarTypeName,
                CAR.FTCarBrand      AS FTCarBrandCode,
                T2.FTCaiName	    AS FTCarBrandName,
                CAR.FTCarModel      AS FTCarModelCode,
                T3.FTCaiName        AS FTCarModelName,
                CAR.FTCarColor      AS FTCarColorCode,
                T4.FTCaiName        AS FTCarColorName,
                CAR.FTCarGear       AS FTCarGearCode,
                T5.FTCaiName        AS FTCarGearName,
                CAR.FTCarPowerType  AS FTCarPowerTypeCode,
                T6.FTCaiName        AS FTCarPowerTypeName,
                CAR.FTCarEngineSize AS FTCarEngineSizeCode,
                T7.FTCaiName        AS FTCarEngineSizeName,
                CAR.FTCarCategory   AS FTCarCategoryCode,
                T8.FTCaiName        AS FTCarCategoryName,
                CAR.FDCarDOB,
                CAR.FTCarOwner      AS FTCarOwnerCode,
                CSTL.FTCstName      AS FTCarOwnerName,
                CAR.FDCarOwnChg,
                CAR.FTCarRegProvince AS FTCarRegPvnCode,
                PVNL.FTPvnName       AS FTCarRegPvnName,
                CAR.FTCarStaRedLabel
            FROM TSVMCar CAR WITH(NOLOCK)
            LEFT JOIN TSVMCarInfo_L T1 WITH (NOLOCK) ON CAR.FTCarType = T1.FTCaiCode AND T1.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T2 WITH (NOLOCK) ON CAR.FTCarBrand = T2.FTCaiCode AND T2.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T3 WITH (NOLOCK) ON CAR.FTCarModel = T3.FTCaiCode AND T3.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T4 WITH (NOLOCK) ON CAR.FTCarColor = T4.FTCaiCode AND T4.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T5 WITH (NOLOCK) ON CAR.FTCarGear = T5.FTCaiCode AND T5.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T6 WITH (NOLOCK) ON CAR.FTCarPowerType = T6.FTCaiCode AND T6.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T7 WITH (NOLOCK) ON CAR.FTCarEngineSize = T7.FTCaiCode AND T7.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T8 WITH (NOLOCK) ON CAR.FTCarCategory = T8.FTCaiCode AND T8.FNLngID = '$nLngID'
            LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON CAR.FTCarRegProvince = PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = '1'
            WHERE
        ";

        switch ($ptTypeCondition) {
            case 'Owner':
                $tCstCode   = $paDataCondition['tCstCode'];
                $tSQL      .= " CAR.FTCarOwner = '$tCstCode' ";
                break;
            default:
                $tCarCode   = $paDataCondition['tCarCstCode'];
                $tSQL      .= " CAR.FTCarCode = '$tCarCode' ";
                break;
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {

            switch ($ptTypeCondition) {
                case 'Owner':
                    $aDataList = $oQuery->result_array();
                    break;
                default:
                    $aDataList = $oQuery->row_array();
                    break;
            }

            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tCstCode);
        unset($tSQL);
        unset($oQuery);
        unset($aDataList);
        return $aDataReturn;
    }

    //เพิ่มข้อมูลสินค้าใน DTTemp - Booking
    public function FSaMJR1GetBookingDT($ptItem, $ptPdtConvertIN)
    {

        $tBookingCode       = $ptItem['tBookingCode'];
        $tDocumentNumber    = $ptItem['tDocumentNumber'];
        $tAGNCode           = $ptItem['tAGNCode'];
        $tBCHCode           = $ptItem['tBCHCode'];
        $tSession           = $this->session->userdata('tSesSessionID');
        $dDate              = date('Y-m-d H:i:s');
        $tUserName          = $this->session->userdata('tSesUsername');

        $tSQL   = " INSERT INTO TSVTJRQDocDTTmp (
                        FTBchCode , FTXthDocNo , FNXtdSeqNo , FTXthDocKey ,
                        FTPdtCode , FTXtdPdtName , FTPunCode , FTPunName ,
                        FCXtdFactor , FTXtdBarCode , FTXtdVatType , FTVatCode ,
                        FCXtdVatRate , FCXtdQty , FCXtdSalePrice , FCXtdSetPrice ,
                        FTPdtSetOrSN , FTXtdPdtStaSet , FTXtdStaAlwDis , FTXtdStaPrcStk , FTXtdRmk , FTWahCode , FTSessionID ,
                        FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy
                    )
                    SELECT
                        '$tBCHCode',
                        '$tDocumentNumber'  AS FTXthDocNo,
                        ISNULL(TMP.FNXtdSeqNo,0) + DT.FTXsdSeq AS FNXtdSeqNo,
                        'TSVTJob1ReqDT'     AS FTXthDocKey,
                        DT.FTPdtCode        AS FTPdtCode,
                        DT.FTXsdPdtName     AS FTXtdPdtName,
                        DT.FTPunCode        AS FTPunCode,
                        DT.FTPunName        AS FTPunName,
                        DT.FCXsdFactor      AS FCXtdFactor,
                        DT.FTXsdBarCode     AS FTXtdBarCode,
                        DT.FTXsdVatType     AS FTXtdVatType,
                        DT.FTVatCode        AS FTVatCode,
                        DT.FCXsdVatRate     AS FCXtdVatRate,
                        DT.FCXsdQty         AS FCXtdQty,
                        DT.FCXsdSalePrice   AS FCXtdSalePrice,
                        DT.FCXsdSetPrice    AS FCXtdSetPrice,
                        DT.FTPdtStaSet      AS FTPdtSetOrSN,
                        DT.FTPdtStaSet      AS FTXtdPdtStaSet,
                        DT.FTXsdStaAlwDis   AS FTXtdStaAlwDis,
                        DT.FTXsdStaPrcStk   AS FTXtdStaPrcStk,
                        DT.FTXsdStaPrcStk   AS FTXtdRmk,
                        DT.FTWahCodeTo      AS FTWahCode,
                        '$tSession',
                        '$dDate',
                        '$tUserName',
                        '$dDate',
                        '$tUserName'
                    FROM TSVTBookDT DT WITH ( NOLOCK )
                    LEFT JOIN (
                        SELECT MAX(FNXtdSeqNo) AS FNXtdSeqNo, TMP.FTSessionID, TMP.FTXthDocKey
                        FROM TSVTJRQDocDTTmp TMP
                        WHERE TMP.FTSessionID = '$tSession'
                        AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'
                        GROUP BY TMP.FTSessionID,TMP.FTXthDocKey
                    ) TMP ON TMP.FTSessionID = '$tSession' AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'
                    WHERE  DT.FTXshDocNo = '" . @$tBookingCode . "'
                  ";

        // if( isset($ptPdtConvertIN) && !empty($ptPdtConvertIN) ){
        //     $tSQL   .= " AND DT.FTPdtCode NOT IN ($ptPdtConvertIN) ";
        // }

        $this->db->query($tSQL);
        // echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Insert Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Insert.',
            );
        }
        return $aStatus;
    }

    //เพิ่มข้อมูลสินค้าใน DTSetTemp - Booking
    public function FSaMJR1GetBookingDTSet($ptItem, $ptPdtConvertIN)
    {

        $tBookingCode       = $ptItem['tBookingCode'];
        $tDocumentNumber    = $ptItem['tDocumentNumber'];
        $tAGNCode           = $ptItem['tAGNCode'];
        $tBCHCode           = $ptItem['tBCHCode'];
        $tSession           = $this->session->userdata('tSesSessionID');
        $dDate              = date('Y-m-d H:i:s');
        $tUserName          = $this->session->userdata('tSesUsername');

        $tSQL = "   INSERT INTO TSVTJRQDTSetTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FNPstSeqNo,FTPdtCode,FTPsvType,FTXtdPdtName,FTPunCode,FCXtdQtySet,FCXtdSalePrice,
                        FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy,FTSessionID,FTPdtCodeOrg,FTSrnCode,FTXthDocKey
                    )
                    SELECT
                        '$tBCHCode',
                        '$tDocumentNumber'         AS FTXthDocNo,
                        ISNULL(TMP.FNXtdSeqNo,0)   AS FNXtdSeqNo,
                        DTSET.FNPstSeqNo           AS FNPstSeqNo,
                        DTSET.FTPdtCode            AS FTPdtCode,
                        DTSET.FTPsvType            AS FTPsvType,
                        DTSET.FTXsdPdtName         AS FTXtdPdtName,
                        DTSET.FTPunCode            AS FTPunCode,
                        DTSET.FCXsdQtySet          AS FCXtdQtySet,
                        DTSET.FCXsdSalePrice       AS FCXtdSalePrice,
                        '$dDate',
                        '$tUserName',
                        '$dDate',
                        '$tUserName',
                        '$tSession',
                        DTSET.FTPdtCode             AS FTPdtCodeOrg,
                        DT.FTPdtCode                AS FTSrnCode,
                        'TSVTJob1ReqDT'             AS FTXthDocKey
                    FROM TSVTBookDTSet DTSET WITH ( NOLOCK )
                    LEFT JOIN TSVTBookDT DT ON DTSET.FTXshDocNo = DT.FTXshDocNo AND DT.FTXsdSeq = DTSET.FNXsdSeqNo
                    LEFT JOIN (
                        SELECT
                            FNXtdSeqNo,FTPdtCode
                        FROM TSVTJRQDocDTTmp WITH(NOLOCK)
                        WHERE FTSessionID = '$tSession'
                          AND FTXthDocKey = 'TSVTJob1ReqDT'
                    ) TMP ON TMP.FTPdtCode = DT.FTPdtCode
                    /*LEFT JOIN (
                        SELECT MAX(FNXtdSeqNo) AS FNXtdSeqNo, TMP.FTSessionID, TMP.FTXthDocKey
                        FROM TSVTJRQDTSetTmp TMP
                        WHERE TMP.FTSessionID = '$tSession'
                        AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'
                        GROUP BY TMP.FTSessionID,TMP.FTXthDocKey
                    ) TMP ON TMP.FTSessionID = '$tSession' AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'*/
                    WHERE ( DTSET.FTXshDocNo = '" . @$tBookingCode . "')
            ";

        // if( isset($ptPdtConvertIN) && !empty($ptPdtConvertIN) ){
        //     $tSQL   .= " AND DT.FTPdtCode NOT IN ($ptPdtConvertIN) ";
        // }

        $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Insert Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Insert.',
            );
        }
        return $aStatus;
    }

    //ดึงข้อมูลสินค้าใน DT Temp List
    public function FSaMJR1GetDocDTTempListPage($paDataWhere){
        $tDocNo         = $paDataWhere['FTXthDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        $tBCHCode       = $paDataWhere['FTBchCode'];
        $tAgnCode       = $paDataWhere['FTAgnCode'];

        $tSQL   = "
            SELECT
                COUNT(DATADT.FNXtdSeqNo) OVER (PARTITION BY DATADT.FNXtdSeqNo) AS PARTITIONBYDOC,
                ROW_NUMBER() OVER (PARTITION BY DATADT.FNXtdSeqNo ORDER BY DATADT.FTBchCode ASC) AS FNPstSeqNo,
                DATADT.*
            FROM(
                /** DT TEMP */
                SELECT
                    DT.FTBchCode,
                    DT.FTXtdBarCode,
                    DT.FTXthDocNo,
                    DT.FNXtdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXtdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXtdFactor,
                    DT.FCXtdQty,
                    DT.FCXtdSalePrice,
                    DT.FCXtdSetPrice,
                    DT.FTXtdPdtStaSet AS FTPdtSetOrSN,
                    '0' AS FTPsvType,
                    DT.FTXtdStaAlwDis,
                    DT.FTXtdStaPrcStk,
                    1 AS FTXtdStaDTSub,
                    DT.FTXtdDisChgTxt,
                    DT.FCXtdNet,
                    DT.FCXtdNetAfHD,
                    DT.FTXtdVatType,
                    DT.FCXtdVatRate,
                    DT.FTXtdSaleType,
                    DT.FTXtdRmk
                FROM TSVTJRQDocDTTmp DT WITH (NOLOCK)
                WHERE (ISNULL(DT.FTAgnCode,'')  = " . $this->db->escape($tAgnCode) . ")
                AND (ISNULL(DT.FTBchCode,'')    = " . $this->db->escape($tBCHCode) . ")
                AND (ISNULL(DT.FTXthDocNo,'')   = " . $this->db->escape($tDocNo) . ")
                AND (ISNULL(DT.FTXthDocKey,'')  = " . $this->db->escape($tDocKey) . ")
                AND (ISNULL(DT.FTSessionID,'')  = " . $this->db->escape($tSesSessionID) . ")

                /**UNION

                DT SET TEMP 
                SELECT
                    DTS.FTBchCode,
                    DTS.FTXthDocNo,
                    DTS.FNXtdSeqNo,
                    DTS.FTPdtCode		AS FTPdtCode,
                    DTS.FTXtdPdtName	AS FTPdtName,
                    DTS.FTPunCode, ''	AS FTPunName,
                    0 AS FCXtdFactor,
                    DTS.FCXtdQtySet		AS FCXtdQty,
                    DTS.FCXtdSalePrice,
                    DTS.FCXtdSalePrice	AS FCXtdSetPrice,
                    0 AS FTPdtSetOrSN,
                    DTS.FTPsvType,
                    0 AS FTXtdStaAlwDis,
                    0 AS FTXtdStaPrcStk, 
                    2 AS FTXtdStaDTSub,
                    NULL AS FTXtdDisChgTxt,
                    0 AS FCXtdNet,
                    0 AS FCXtdNetAfHD,
                    0 AS FTXtdVatType,
                    0 AS FCXtdVatRate,
                    '' AS FTXtdSaleType,
                    '' AS FTXtdRmk
                FROM TSVTJRQDTSetTmp DTS WITH(NOLOCK)
                WHERE (ISNULL(DTS.FTAgnCode,'') = " . $this->db->escape($tAgnCode) . ")
                AND (ISNULL(DTS.FTBchCode,'')   = " . $this->db->escape($tBCHCode) . ")
                AND (ISNULL(DTS.FTXthDocNo,'')  = " . $this->db->escape($tDocNo) . ")
                AND (ISNULL(DTS.FTXthDocKey,'') = " . $this->db->escape($tDocKey) . ")
                AND (ISNULL(DTS.FTSessionID,'') = " . $this->db->escape($tSesSessionID) . ") */
            ) AS DATADT
            ORDER BY DATADT.FNXtdSeqNo ASC, DATADT.FTXtdStaDTSub ASC, DATADT.FTPsvType ASC
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }

    //ดึงข้อมูลสินค้าใน DT Temp List By ID
    public function FSaMJR1GetDocDTTempByID($paDataWhere){
        $tDocNo         = $paDataWhere['FTXthDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        $tBchCode       = $paDataWhere['FTBchCode'];
        $tPdtCode       = $paDataWhere['FTPdtCode'];
        $tCstCode       = $paDataWhere['FTCstCode'];
        $tCarCode       = $paDataWhere['FTCarCode'];
        $nSeqno         = $paDataWhere['FNXtdSeqNo'];
        $tSQL           = "
            SELECT
                DTTMP.FTBchCode,
                DTTMP.FTXthDocNo,
                DTTMP.FNXtdSeqNo,
                DTTMP.FTXthDocKey,
                DTTMP.FTPdtCode,
                DTTMP.FTXtdPdtName,
                DTTMP.FTPunCode,
                DTTMP.FTPunName,
                DTTMP.FCXtdFactor,
                DTTMP.FTXtdBarCode,
                DTTMP.FTXtdVatType,
                DTTMP.FTVatCode,
                DTTMP.FCXtdVatRate,
                DTTMP.FCXtdQty,
                DTTMP.FCXtdSalePrice,
                DTTMP.FCXtdSetPrice,
                DTTMP.FTXtdPdtStaSet AS FTPdtSetOrSN,
                DTTMP.FTXtdStaAlwDis,
                DTTMP.FTXtdStaPrcStk,
                DTTMP.FTSessionID,
                '" . $tCstCode . "' AS FTCstCode,
                '" . $tCarCode . "' AS FTCarCode
            FROM TSVTJRQDocDTTmp DTTMP WITH(NOLOCK)
            WHERE 1=1
            AND (DTTMP.FTPdtCode    = '" . $tPdtCode . "')
            AND (DTTMP.FTBchCode    = '" . $tBchCode . "')
            AND (DTTMP.FTXthDocNo   = '" . $tDocNo . "')
            AND (DTTMP.FTXthDocKey  = '" . $tDocKey . "')
            AND (DTTMP.FTSessionID  = '" . $tSesSessionID . "')
            AND (DTTMP.FNXtdSeqNo  = '" . $nSeqno . "')
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        return $aDataReturn;
    }

    //ดึงข้อมูลตารางรายการสินค้า
    public function  FSaMJR1GetDataPdt($ptPdtCode, $ptPunCode){
        $tPdtCode   = $ptPdtCode;
        $FTPunCode  = $ptPunCode;
        $nLngID     = $this->session->userdata("tLangEdit");
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
                SPL.FCSplLastPrice,
                PRI4PDT.FCPgdPriceRet
            FROM TCNMPdt PDT WITH (NOLOCK)
            LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = '$nLngID'
            LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
            LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = '$nLngID'
            LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
            LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = '$nLngID'
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
            LEFT JOIN (
                SELECT DISTINCT
                    P4PDT.FTPdtCode,
                    P4PDT.FTPunCode,
                    P4PDT.FDPghDStart,
                    P4PDT.FTPghTStart,
                    P4PDT.FCPgdPriceRet
                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                WHERE 1=1
                AND (CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121))
                AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121))
            ) AS PRI4PDT
            ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode
            WHERE 1 = 1
        ";
        if (isset($tPdtCode) && !empty($tPdtCode)) {
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }
        $tSQL .= " ORDER BY FDVatStart DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    // ลบข้อมูลลงตาราง DT Temp (TSVTJRQDocDTTmp) เพื่อไม่ให้เกิดการ Dup ของข้อมูล
    public function FSaMJR1DeleteDTToTemp($aItem){
        $tSession   = $this->session->userdata('tSesSessionID');

        //ลบข้อมูล DT
        // $this->db->where("ISNULL(FTAgnCode,'')",$aItem['FTAgnCode']);
        // $this->db->where('FTBchCode',$aItem['FTBchCode']);
        // $this->db->where('FTXthDocNo',$aItem['FTXshDocNo']);
        // $this->db->where('FTSessionID',$tSession);
        // $this->db->where('FTPdtCode',$aItem['FTPdtCode']);
        // $this->db->delete('TSVTJRQDocDTTmp');

        //ลบข้อมูล Set
        $this->db->where("ISNULL(FTAgnCode,'')", $aItem['FTAgnCode']);
        $this->db->where('FTBchCode', $aItem['FTBchCode']);
        $this->db->where('FTXthDocNo', $aItem['FTXshDocNo']);
        $this->db->where('FTSessionID', $tSession);
        $this->db->where('FTSrnCode', $aItem['FTPdtCode']);
        $this->db->delete('TSVTJRQDTSetTmp');
    }

    //เพิ่มข้อมูลลงตาราง DT Temp (TSVTJRQDocDTTmp)
    public function FSaMJR1InsertDTToTemp($aItem){
        $tSession   = $this->session->userdata('tSesSessionID');

        if ($aItem['tJR1Option'] == 1) {
        $tSQL       = "SELECT
                            TMP.FCXtdQty,
                            TMP.FNXtdSeqNo,
                            TMP.FCXtdSalePrice,
                            TMP.FCXtdSetPrice
                        FROM TSVTJRQDocDTTmp TMP WITH(NOLOCK)
                        WHERE TMP.FTPdtCode = '" . $aItem['FTPdtCode'] . "' AND
                        TMP.FTXtdPdtName = '" . $aItem['FTXtdPdtName'] . "' AND /*กรณีสินค้า Fast ถ้าชื่อไม่ตรงกัน ให้เพิ่มรายการ*/
                        TMP.FTXthDocNo = '" . $aItem['FTXshDocNo'] . "' AND
                        TMP.FTBchCode = '" . $aItem['FTBchCode'] . "' AND
                        TMP.FTXthDocKey = '" . $aItem['FTXthDocKey'] . "' AND
                        TMP.FTPunCode = '" . $aItem['FTPunCode'] . "' AND
                        TMP.FTSessionID = '" . $tSession . "' ";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aItemQTY   = $oQuery->result_array();
            $nQTY       = $aItemQTY[0]['FCXtdQty'] + 1;
            $nSEQ       = $aItemQTY[0]['FNXtdSeqNo'];
            $nSalePrice = $aItemQTY[0]['FCXtdSalePrice'];
            $nSetPrice  = $aItemQTY[0]['FCXtdSetPrice'];
            $nSetSeqNo  = $aItemQTY[0]['FNXtdSeqNo'];

            //ลบข้อมูลสินค้าก่อน
            $this->db->where_in('FTXtdPdtName', $aItem['FTXtdPdtName']); /*กรณีสินค้า Fast ถ้าชื่อนไม่ตรงกัน ให้เพิ่มรายการ*/
            $this->db->where_in('FTPdtCode', $aItem['FTPdtCode']);
            $this->db->where_in('FTXthDocNo', $aItem['FTXshDocNo']);
            $this->db->where_in('FTBchCode', $aItem['FTBchCode']);
            $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
            $this->db->where_in('FTSessionID', $tSession);
            $this->db->where('FNXtdSeqNo', $nSetSeqNo);
            $this->db->delete('TSVTJRQDocDTTmp');
        } else {
            $nQTY       = 1;
            $nSalePrice = $aItem['FCXtdSalePrice'];
            $nSetPrice  = $aItem['FCXtdSetPrice'];

            //หา Seq ล่าสุดของเอกสาร
            $tSQLFindSEQ    = " SELECT TMP.FNXtdSeqNo FROM TSVTJRQDocDTTmp TMP
                                WHERE TMP.FTXthDocNo = '" . $aItem['FTXshDocNo'] . "' AND
                                TMP.FTBchCode = '" . $aItem['FTBchCode'] . "' AND
                                TMP.FTXthDocKey = '" . $aItem['FTXthDocKey'] . "' AND
                                TMP.FTSessionID = '" . $tSession . "' ORDER BY FNXtdSeqNo DESC ";
            $oQuery     = $this->db->query($tSQLFindSEQ);
            if ($oQuery->num_rows() > 0) {
                $aItemSEQ   = $oQuery->result_array();
                $nSEQ       = $aItemSEQ[0]['FNXtdSeqNo'] + 1;
            } else {
                $nSEQ       = 1;
            }
        }

        $this->db->insert('TSVTJRQDocDTTmp', array(
            'FTAgnCode'         => $aItem['FTAgnCode'],
            'FTBchCode'         => $aItem['FTBchCode'],
            'FTXthDocNo'        => $aItem['FTXshDocNo'],
            'FNXtdSeqNo'        => $nSEQ,
            'FTXthDocKey'       => $aItem['FTXthDocKey'],
            'FTPdtCode'         => $aItem['FTPdtCode'],
            'FTXtdPdtName'      => $aItem['FTXtdPdtName'],
            'FTPunCode'         => $aItem['FTPunCode'],
            'FTPunName'         => $aItem['FTPunName'],
            'FCXtdFactor'       => $aItem['FCXtdFactor'],
            'FTXtdBarCode'      => $aItem['FTXtdBarCode'],
            'FTXtdVatType'      => $aItem['FTXtdVatType'],
            'FTVatCode'         => $aItem['FTVatCode'],
            'FCXtdVatRate'      => $aItem['FCXtdVatRate'],
            'FCXtdQty'          => $nQTY,
            'FCXtdSalePrice'    => $nSalePrice,
            'FCXtdSetPrice'     => $nSetPrice,
            'FTXtdPdtStaSet'    => $aItem['FTXtdPdtStaSet'],
            'FNXtdPdtLevel'     => 1,
            'FTPdtSetOrSN'      => $aItem['FTPdtSetOrSN'],
            'FTXtdStaAlwDis'    => $aItem['FTXtdStaAlwDis'],
            'FTXtdStaPrcStk'    => $aItem['FTXtdStaPrcStk'],
            'FTXtdRmk'          => $aItem['FTXtdStaPrcStk'],
            'FTSessionID'       => $tSession,
            'FDLastUpdOn'       => date('Y-m-d H:i:s'),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date('Y-m-d H:i:s'),
            'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            'FTXtdSaleType'     => $aItem['FTPdtType'],
            'FCXtdNet'          => ($nSetPrice * $nQTY)
        ));
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add.',
            );
        }
        }else{
            $nQTY       = 1;
            $nSalePrice = $aItem['FCXtdSalePrice'];
            $nSetPrice  = $aItem['FCXtdSetPrice'];

            //หา Seq ล่าสุดของเอกสาร
            $tSQLFindSEQ    = " SELECT TMP.FNXtdSeqNo FROM TSVTJRQDocDTTmp TMP
                                WHERE TMP.FTXthDocNo = '" . $aItem['FTXshDocNo'] . "' AND
                                TMP.FTBchCode = '" . $aItem['FTBchCode'] . "' AND
                                TMP.FTXthDocKey = '" . $aItem['FTXthDocKey'] . "' AND
                                TMP.FTSessionID = '" . $tSession . "' ORDER BY FNXtdSeqNo DESC ";
            $oQuery     = $this->db->query($tSQLFindSEQ);
            if ($oQuery->num_rows() > 0) {
                $aItemSEQ   = $oQuery->result_array();
                $nSEQ       = $aItemSEQ[0]['FNXtdSeqNo'] + 1;
            } else {
                $nSEQ       = 1;
            }

            $this->db->insert('TSVTJRQDocDTTmp', array(
                'FTAgnCode'         => $aItem['FTAgnCode'],
                'FTBchCode'         => $aItem['FTBchCode'],
                'FTXthDocNo'        => $aItem['FTXshDocNo'],
                'FNXtdSeqNo'        => $nSEQ,
                'FTXthDocKey'       => $aItem['FTXthDocKey'],
                'FTPdtCode'         => $aItem['FTPdtCode'],
                'FTXtdPdtName'      => $aItem['FTXtdPdtName'],
                'FTPunCode'         => $aItem['FTPunCode'],
                'FTPunName'         => $aItem['FTPunName'],
                'FCXtdFactor'       => $aItem['FCXtdFactor'],
                'FTXtdBarCode'      => $aItem['FTXtdBarCode'],
                'FTXtdVatType'      => $aItem['FTXtdVatType'],
                'FTVatCode'         => $aItem['FTVatCode'],
                'FCXtdVatRate'      => $aItem['FCXtdVatRate'],
                'FCXtdQty'          => $nQTY,
                'FCXtdSalePrice'    => $nSalePrice,
                'FCXtdSetPrice'     => $nSetPrice,
                'FTXtdPdtStaSet'    => $aItem['FTXtdPdtStaSet'],
                'FNXtdPdtLevel'     => 1,
                'FTPdtSetOrSN'      => $aItem['FTPdtSetOrSN'],
                'FTXtdStaAlwDis'    => $aItem['FTXtdStaAlwDis'],
                'FTXtdStaPrcStk'    => $aItem['FTXtdStaPrcStk'],
                'FTXtdRmk'          => $aItem['FTXtdStaPrcStk'],
                'FTSessionID'       => $tSession,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTXtdSaleType'     => $aItem['FTPdtType'],
                'FCXtdNet'          => ($nSetPrice * $nQTY)
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
        }
        return $aStatus;
    }

    //อัพเดตข้อมูลรายการ DT Temp Seq
    public function FSxMJR1UpdateSeqDTTemp($paDataWhere){
        $tSession = $this->session->userdata('tSesSessionID');
        $tSQL     = "
            UPDATE DOCTMP
            SET DOCTMP.FNXtdSeqNo = DTUPD.rtRowID
            FROM TSVTJRQDocDTTmp AS DOCTMP WITH(NOLOCK)
            RIGHT JOIN (
                SELECT
                    ROW_NUMBER() OVER ( ORDER BY UPD.FDCreateOn ASC) AS rtRowID,
                    UPD.FTAgnCode   AS rtAgnCode,
                    UPD.FTBchCode 	AS rtBchCode,
                    UPD.FTXthDocNo	AS rtXthDocNo,
                    UPD.FTXthDocKey AS rtXthDocKey,
                    UPD.FTPdtCode   AS rtPdtCode
                FROM TSVTJRQDocDTTmp UPD WITH(NOLOCK)
                WHERE 1=1
                AND UPD.FTAgnCode       = '" . $paDataWhere['FTAgnCode'] . "'
                AND UPD.FTBchCode       = '" . $paDataWhere['FTBchCode'] . "'
                AND UPD.FTXthDocNo      = '" . $paDataWhere['FTXshDocNo'] . "'
                AND UPD.FTXthDocKey     = '" . $paDataWhere['FTXthDocKey'] . "'
                AND UPD.FTSessionID     = '" . $tSession . "'
            ) DTUPD
            ON DTUPD.rtAgnCode      = DOCTMP.FTAgnCode
            AND DTUPD.rtBchCode     = DOCTMP.FTBchCode
            AND DTUPD.rtXthDocNo    = DOCTMP.FTXthDocNo
            AND DTUPD.rtXthDocKey   = DOCTMP.FTXthDocKey
            AND DTUPD.rtPdtCode     = DOCTMP.FTPdtCode
        ";
        $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Update.',
            );
        }
        return $aStatus;
    }

    //ลบข้อมูล DT Set Temp
    public function FSaMJR1DeletePdtDTSetToTemp($paPDTDetail){
        $this->db->where('FTAgnCode', $paPDTDetail['FTAgnCode']);
        $this->db->where('FTBchCode', $paPDTDetail['FTBchCode']);
        $this->db->where('FTXthDocNo', $paPDTDetail['FTXthDocNo']);
        $this->db->where('FTSessionID', $paPDTDetail['FTSessionID']);
        $this->db->where('FTSrnCode', $paPDTDetail['FTSrnCode']);
        $this->db->delete('TSVTJRQDTSetTmp');
    }

    //เพิ่มข้อมูลลงตาราง DT Set Temp (TSVTJRQDTSetTmp) Type 5
    public function FSaMJR1InsertDTSetToTemp($paPDTDetail){
        $tAgnCode           = $paPDTDetail['FTAgnCode'];
        $tBchCode           = $paPDTDetail['FTBchCode'];
        $tDocumentNumber    = $paPDTDetail['FTXthDocNo'];
        $tSession           = $paPDTDetail['FTSessionID'];
        $nLngID             = $paPDTDetail['FNLngID'];
        $tDocKey            = $paPDTDetail['FTXthDocKey'];
        $tPdtCode           = $paPDTDetail['FTPdtCode'];

        $tSQL               = "
            INSERT INTO TSVTJRQDTSetTmp (
                FTAgnCode,FTBchCode,FTXthDocNo,FNXtdSeqNo,FNPstSeqNo,FTPdtCode,FTPsvType,FTXtdPdtName,FTPunCode,
                FCXtdQtySet,FCXtdSalePrice,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy,FTSessionID,FTPdtCodeOrg,
                FTSrnCode,FTPsvStaSuggest,FTXthDocKey
            )
            SELECT
                DATADTSET.*
            FROM(
                SELECT
                    '" . $tAgnCode . "'         AS FTAgnCode,
                    '" . $tBchCode . "'         AS FTBchCode,
                    '" . $tDocumentNumber . "'  AS FTXthDocNo,
                    DT.FNXtdSeqNo               AS FNXtdSeqNo,
                    ROW_NUMBER() OVER ( ORDER BY A.FTPdtCodeSub ASC) AS FNPstSeqNo,
                    A.FTPdtCodeSub 		    AS FTPdtCode,
                    A.FTPsvType				AS FTPsvType,
                    PDTL.FTPdtName			AS FTXtdPdtName,
                    A.FTPunCode				AS FTPunCode,
                    A.FCPsvQty				AS FCXtdQtySet,
                    PRI.FCPgdPriceRet       AS FCXtdSalePrice,
                    '" . date('Y-m-d H:i:s') . "'                       AS FDLastUpdOn,
                    '" . $this->session->userdata('tSesUsername') . "'  AS FTLastUpdBy,
                    '" . date('Y-m-d H:i:s') . "'                       AS FDCreateOn,
                    '" . $this->session->userdata('tSesUsername') . "'  AS FTCreateBy,
                    '" . $tSession . "'                                 AS FTSessionID,
                    A.FTPdtCodeSub  AS FTPdtCodeOrg,
                    A.FTPdtCode     AS FTSrnCode,
                    A.FTPsvStaSuggest,
                    '" . $tDocKey . "' AS FTXthDocKey
                FROM TSVTJRQDocDTTmp DT WITH(NOLOCK)
                INNER JOIN TSVTPdtSet A WITH(NOLOCK) ON DT.FTPdtCode = A.FTPdtCode
                LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK)ON A.FTPdtCodeSub = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                LEFT JOIN TSVTJRQDTSetTmp DTSET WITH(NOLOCK)   ON DT.FTPdtCode = DTSET.FTSrnCode AND DT.FNXtdSeqNo = DTSET.FNXtdSeqNo AND DTSET.FTSessionID  = '$tSession'
                LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON A.FTPdtCodeSub = PRI.FTPdtCode AND A.FTPunCode = PRI.FTPunCode
                WHERE DT.FTPdtSetOrSN   = '5'
                AND DT.FTAgnCode    = '" . $tAgnCode . "'
                AND DT.FTBchCode    = '" . $tBchCode . "'
                AND DT.FTXthDocNo   = '" . $tDocumentNumber . "'
                AND DT.FTXthDocKey 	= '" . $tDocKey . "'
                AND DT.FTSessionID	= '" . $tSession . "'
                AND DT.FTPdtCode    = '" . $tPdtCode . "'
                AND ISNULL(DTSET.FNXtdSeqNo,'') = ''
            ) AS DATADTSET
            ORDER BY DATADTSET.FNXtdSeqNo ASC , DATADTSET.FNPstSeqNo ASC
        ";
        $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Insert Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Insert.',
            );
        }
        return $aStatus;
    }

    //เพิ่มข้อมูลลงตาราง DT Set Temp (TSVTJRQDTSetTmp) Type 2
    public function FSaMJR1InsertDTSetToTemp2($paPDTDetail){
        $tAgnCode           = $paPDTDetail['FTAgnCode'];
        $tBchCode           = $paPDTDetail['FTBchCode'];
        $tDocumentNumber    = $paPDTDetail['FTXthDocNo'];
        $tSession           = $paPDTDetail['FTSessionID'];
        $nLngID             = $paPDTDetail['FNLngID'];
        $tDocKey            = $paPDTDetail['FTXthDocKey'];
        $tSQL               = "
            INSERT INTO TSVTJRQDTSetTmp (
                FTAgnCode,FTBchCode,FTXthDocNo,FNXtdSeqNo,FNPstSeqNo,FTPdtCode,FTPsvType,FTXtdPdtName,FTPunCode,FCXtdQtySet,FCXtdSalePrice,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy,FTSessionID,
                FTPdtCodeOrg,FTSrnCode,FTPsvStaSuggest,FTXthDocKey
            )
            SELECT DATADTSET.*
            FROM(
                SELECT
                    '" . $tAgnCode . "'         AS FTAgnCode,
                    '" . $tBchCode . "'	        AS FTBchCode,
                    '" . $tDocumentNumber . "'  AS FTXthDocNo,
                    DT.FNXtdSeqNo AS FNXtdSeqNo,
                    ROW_NUMBER() OVER ( ORDER BY A.FTPDTCode ASC) AS FNPstSeqNo,
                    A.FTPdtCodeSet	AS FTPdtCode,
                    '0'	AS FTPsvType,
                    PDTL.FTPdtName  AS FTXtdPdtName,
                    A.FTPunCode     AS FTPunCode,
                    A.FCPstQty      AS FCXtdQtySet,
                    PRI.FCPgdPriceRet AS FCXtdSalePrice,
                    '" . date('Y-m-d H:i:s') . "' AS FDLastUpdOn,
                    '" . $this->session->userdata('tSesUsername') . "' AS FTLastUpdBy,
                    '" . date('Y-m-d H:i:s') . "'	AS FDCreateOn,
                    '" . $this->session->userdata('tSesUsername') . "' AS FTCreateBy,
                    '" . $tSession . "' AS FTSessionID,
                    A.FTPdtCodeSet  AS FTPdtCodeOrg,
                    A.FTPdtCode		AS FTSrnCode,
                    '1' AS FTPsvStaSuggest,
                    'TSVTJob1ReqDT' AS FTXthDocKey
                FROM TSVTJRQDocDTTmp DT WITH(NOLOCK)
                INNER JOIN TCNTPdtSet A WITH(NOLOCK) ON DT.FTPdtCode = A.FTPdtCode
                LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK) ON A.FTPdtCodeSet = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON A.FTPdtCodeSet = PRI.FTPdtCode AND A.FTPunCode = PRI.FTPunCode
                WHERE DT.FTPdtSetOrSN   = '2'
                AND DT.FTAgnCode    = '" . $tAgnCode . "'
                AND DT.FTBchCode    = '" . $tBchCode . "'
                AND DT.FTXthDocNo   = '" . $tDocumentNumber . "'
                AND DT.FTXthDocKey  = '" . $tDocKey . "'
                AND DT.FTSessionID  = '" . $tSession . "'
            ) AS DATADTSET
            ORDER BY DATADTSET.FNXtdSeqNo ASC , DATADTSET.FNPstSeqNo ASC
        ";

        $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Insert Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Insert.',
            );
        }
        return $aStatus;
    }

    //สินค้าใน DT Set
    public function FSaMJR1GetDocDTSetTempJoinCstFollow($paWherePdtSet){
        $tBchCode           = $paWherePdtSet['FTBchCode'];
        $FNXtdSeqNo         = $paWherePdtSet['FNXtdSeqNo'];
        $tDocumentNumber    = $paWherePdtSet['FTXthDocNo'];
        $tSession           = $paWherePdtSet['FTSessionID'];
        $tDocKey            = $paWherePdtSet['FTXthDocKey'];
        $tCarCode           = $paWherePdtSet['FTCarCode'];
        $tPdtCode           = $paWherePdtSet['FTPdtCode'];
        $nLngID             = $paWherePdtSet['FNLngID'];
        
        // $tSQL   = "SELECT
        //             DOCTMP.FTBchCode,
        //             DOCTMP.FTXthDocNo,
        //             DOCTMP.FNXtdSeqNo,
        //             DTSCFW.FNPstSeqNo,
        //             DOCTMP.FTPdtCode,
        //             DOCTMP.FTXtdPdtName,
        //             DTSCFW.FTPdtCode AS FTPdtCodeSet,
        //             DTSCFW.FTPdtCodeOrg AS FTPdtCodeSetOrg,
        //             DTSCFW.FTPsvType,
        //             DTSCFW.FTXtdPdtName AS FTXtdPdtNameSet,
        //             DTSCFW.FCXtdQtySet,
        //             DTSCFW.FCXtdSalePrice,
        //             DTSCFW.FTSrnCode,
        //             DTSCFW.FTPdtCodeCstFlw,
        //             DTSCFW.FTPdtNameCstFlw,
        //             DTSCFW.FTPdtCodeOrgCstFlw,
        //             DTSCFW.FTPdtNameOrgCstFlw,
        //             '".$tCarCode."' AS FTCarCode
        //         FROM TSVTJRQDocDTTmp DOCTMP WITH(NOLOCK)
        //         LEFT JOIN (
        //             SELECT
        //                 A.FTBchCode,
        //                 A.FTXthDocNo,
        //                 A.FNXtdSeqNo,
        //                 A.FNPstSeqNo,
        //                 A.FTPdtCode,
        //                 A.FTPdtCodeOrg,
        //                 A.FTPsvType,
        //                 A.FTXtdPdtName,
        //                 A.FTPunCode,
        //                 A.FCXtdQtySet,
        //                 A.FCXtdSalePrice,
        //                 A.FTSrnCode,
        //                 CSTF.FTPdtCode 		AS FTPdtCodeCstFlw,
        //                 PDTL1.FTPdtName     AS FTPdtNameCstFlw,
        //                 CSTF.FTPdtCodeOrg   AS FTPdtCodeOrgCstFlw,
        //                 PDTL2.FTPdtName		AS FTPdtNameOrgCstFlw
        //             FROM TSVTJRQDTSetTmp A WITH(NOLOCK)
        //             LEFT JOIN TSVTCstFollow CSTF WITH(NOLOCK) ON A.FTPdtCodeOrg = CSTF.FTPdtCodeOrg AND CSTF.FTCarCode = '".@$tCarCode."'
        //             LEFT JOIN TCNMPdt_L PDTL1 WITH(NOLOCK) ON CSTF.FTPdtCode    = PDTL1.FTPdtCode AND PDTL1.FNLngID = '$nLngID'
        //             LEFT JOIN TCNMPdt_L PDTL2 WITH(NOLOCK) ON CSTF.FTPdtCodeOrg = PDTL2.FTPdtCode AND PDTL2.FNLngID = '$nLngID'
        //             WHERE
        //             A.FTBchCode   = '".@$tBchCode."'
        //             AND (A.FTXthDocNo  = '".@$tDocumentNumber."')
        //             AND (A.FTXthDocKey = '".@$tDocKey."')
        //             AND (A.FTSessionID = '".@$tSession."')
        //         ) DTSCFW ON DTSCFW.FTSrnCode = DOCTMP.FTPdtCode
        //         WHERE (DOCTMP.FTPdtSetOrSN = '2' OR DOCTMP.FTPdtSetOrSN = '5')
        //         AND (DOCTMP.FTBchCode   = '".@$tBchCode."')
        //         AND (DOCTMP.FTXthDocNo  = '".@$tDocumentNumber."')
        //         AND (DOCTMP.FTXthDocKey = '".@$tDocKey."')
        //         AND (DOCTMP.FTSessionID = '".@$tSession."')
        //         AND (DoCTMP.FTPdtCode   = '".@$tPdtCode."')
        //         ORDER BY DTSCFW.FTPsvType ASC ";
        $tSQL   = " SELECT DTSET.*
                    FROM TSVTJRQDTSetTmp DTSET WITH(NOLOCK)
                    WHERE DTSET.FTSrnCode   = '" . @$tPdtCode . "'
                      AND DTSET.FTSessionID = '" . @$tSession . "'
                      AND DTSET.FTXthDocKey = '" . @$tDocKey . "'
                      AND DTSET.FTBchCode   = '" . @$tBchCode . "'
                      AND DTSET.FTXthDocNo  = '" . @$tDocumentNumber . "'
                      AND DTSET.FNXtdSeqNo  = '" . @$FNXtdSeqNo . "'
                    ORDER BY DTSET.FTPsvType , DTSET.FNXtdSeqNo DESC , DTSET.FNPstSeqNo ASC ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }

    //ลบข้อมูลในตาราง DT Set Temp (TSVTJRQDTSetTmp)
    public function FSaMJR1DeleteDTSetTempByID($paDataWhere){
        try {
            $this->db->trans_begin();

            $this->db->where('FTBchCode', $paDataWhere['FTBchCode']);
            $this->db->where('FTXthDocNo', $paDataWhere['FTXthDocNo']);
            $this->db->where('FTPdtCode', $paDataWhere['FTPdtCode']);
            $this->db->where('FTPdtCodeOrg', $paDataWhere['FTPdtCodeOrg']);
            $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
            $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
            $this->db->delete('TSVTJRQDTSetTmp');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //อัพเดตข้อมูลรายการ DT Set Temp Seq
    public function FSxMJR1UpdateSeqDTSetTemp($paDataWhere){
        $tSession   = $this->session->userdata('tSesSessionID');
        $tSQL   = "
            UPDATE DOCSTMP
            SET DOCSTMP.FNXtdSeqNo	= DTUPD.rnXtdSeqNo , DOCSTMP.FNPstSeqNo	= DTUPD.rnPstSeqNo
            FROM TSVTJRQDTSetTmp AS DOCSTMP WITH(NOLOCK)
            RIGHT JOIN (
                SELECT
                    DT.FNXtdSeqNo AS rnXtdSeqNo,
                    ROW_NUMBER() OVER (PARTITION BY DT.FNXtdSeqNo ORDER BY DTS.FDCreateOn ASC)	AS rnPstSeqNo,
                    DT.FTAgnCode		AS rtAgnCode,
                    DT.FTBchCode		AS rtBchCode,
                    DT.FTXthDocNo		AS rtXthDocNo,
                    DT.FTPdtCode		AS rtSrnCode,
                    DTS.FTPdtCode		AS rtPdtCode,
                    DTS.FTPunCode		AS rtPunCode,
                    DTS.FTPdtCodeOrg	AS rtPdtCodeOrg,
                    DT.FTSessionID		AS rtSessionID
                FROM TSVTJRQDocDTTmp DT WITH (NOLOCK)
                LEFT JOIN TSVTJRQDTSetTmp DTS WITH(NOLOCK) ON DT.FTAgnCode = DTS.FTAgnCode AND DT.FTBchCode = DTS.FTBchCode AND DT.FTXthDocNo = DTS.FTXthDocNo AND DT.FTPdtCode = DTS.FTSrnCode
                WHERE ISNULL(DT.FTAgnCode,'') = '" . $paDataWhere['FTAgnCode'] . "'
                AND DT.FTBchCode   	= '" . $paDataWhere['FTBchCode'] . "'
                AND DT.FTXthDocNo  	= '" . $paDataWhere['FTXthDocNo'] . "'
                AND DT.FTSessionID 	= '" . $paDataWhere['FTSessionID'] . "'
                AND DT.FTXthDocKey 	= '" . $paDataWhere['FTXthDocKey'] . "'
            ) DTUPD ON 1=1
            AND DTUPD.rtAgnCode		= DOCSTMP.FTAgnCode
            AND DTUPD.rtBchCode     = DOCSTMP.FTBchCode
            AND DTUPD.rtXthDocNo    = DOCSTMP.FTXthDocNo
            AND DTUPD.rtSrnCode		= DOCSTMP.FTSrnCode
            AND DTUPD.rtPdtCode     = DOCSTMP.FTPdtCode
            AND DTUPD.rtPdtCodeOrg	= DOCSTMP.FTPdtCodeOrg
            AND DTUPD.rtSessionID	= DOCSTMP.FTSessionID
        ";

        $this->db->query($tSQL);
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Update.',
            );
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp แบบมี where (ใน Modal)
    public function FSaMJR1RemovePdtInDTTmpCaseModal($paDataWhere){
        $this->db->trans_begin();
        // ================== Start Delete Data DT In Temp ==================
        $this->db->where('FTBchCode', $paDataWhere['FTBchCode']);
        $this->db->where('FTXthDocNo', $paDataWhere['FTXshDocNo']);
        $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
        $this->db->where('FTPdtCode', $paDataWhere['FTPdtCode']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDocDTTmp');

        // ================ Start Delete Data DT Set In Temp ================
        $this->db->where('FTBchCode', $paDataWhere['FTBchCode']);
        $this->db->where('FTXthDocNo', $paDataWhere['FTXshDocNo']);
        $this->db->where('FTSrnCode', $paDataWhere['FTPdtCode']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDTSetTmp');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Delete Unsuccess.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Success.',
            );
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp แบบมี where
    public function FSaMJR1RemovePdtInDTTmp($paDataWhere){

        $this->db->trans_begin();

        // ================== Start Delete Data DT In Temp ==================
        $this->db->where("ISNULL(FTAgnCode,'')", $paDataWhere['FTAgnCode']);
        $this->db->where("ISNULL(FTBchCode,'')", $paDataWhere['FTBchCode']);
        $this->db->where("ISNULL(FTXthDocNo,'')", $paDataWhere['FTXthDocNo']);
        $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
        $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
        $this->db->where('FTPdtCode', $paDataWhere['FTPdtCode']);
        $this->db->where('FTPunCode', $paDataWhere['FTPunCode']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDocDTTmp');

        // ================ Start Delete Data DT Set In Temp ================
        $this->db->where("ISNULL(FTAgnCode,'')", $paDataWhere['FTAgnCode']);
        $this->db->where("ISNULL(FTBchCode,'')", $paDataWhere['FTBchCode']);
        $this->db->where("ISNULL(FTXthDocNo,'')", $paDataWhere['FTXthDocNo']);
        $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
        $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
        $this->db->where('FTSrnCode', $paDataWhere['FTPdtCode']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDTSetTmp');

        // ================ Start Delete Data DT Dis In Temp ================
        $this->db->where("ISNULL(FTBchCode,'')", $paDataWhere['FTBchCode']);
        $this->db->where("ISNULL(FTXthDocNo,'')", $paDataWhere['FTXthDocNo']);
        $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDTDisTmp');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Delete Unsuccess.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Success.',
            );
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp แบบมี where (Muti)
    public function FSaMJR1RemovePdtInDTMutiTmp($paDataWhere){

        $this->db->trans_begin();
        $this->db->where('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->where('FTXthDocNo', $paDataWhere['tDocNo']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['tTextRemoveSeq']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDocDTTmp');

        $this->db->where('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->where('FTXthDocNo', $paDataWhere['tDocNo']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['tTextRemoveSeq']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDTDisTmp');
        
        $this->db->where('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->where('FTXthDocNo', $paDataWhere['tDocNo']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['tTextRemoveSeq']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->delete('TSVTJRQDTSetTmp');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Delete Unsuccess.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Success.',
            );
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp แบบไม่มี where
    public function FSaMJR1DeletePDTInTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTJRQDocDTTmp');

        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTJRQHDDisTmp');

        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTJRQDTDisTmp');

        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTJRQDTSetTmp');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        return $aStatus;
    }

    //ล้างค่าส่วนลดรายการ อัพเดทส่วนลดรายการ
    public function FSaMJR1DeleteDTDisTemp($paParams){
        $tJR1DocNo      = $paParams['tJR1DocNo'];
        $nJR1SeqNo      = $paParams['nJR1SeqNo'];
        $tJR1BchCode    = $paParams['tJR1BchCode'];
        $nStaDelDis     = $paParams['nStaDelDis'];
        $tSessionID     = $paParams['tSessionID'];

        $this->db->where_in('FTSessionID', $tSessionID);
        if (isset($nJR1SeqNo) && !empty($nJR1SeqNo)) {
            $this->db->where_in('FNXtdSeqNo', $nJR1SeqNo);
        }
        $this->db->where_in('FTBchCode', $tJR1BchCode);
        $this->db->where_in('FTXthDocNo', $tJR1DocNo);
        if (isset($nStaDelDis) && !empty($nStaDelDis)) {
            $this->db->where_in('FNXtdStaDis', $nStaDelDis);
        }
        $this->db->delete('TSVTJRQDTDisTmp');
        return;
    }

    //ล้างค่าส่วนใน ตาราง DT
    public function FSaMJR1ClearDisChgTxtDTTemp($paParams){
        $tJR1DocNo      = $paParams['tJR1DocNo'];
        $nJR1SeqNo      = $paParams['nJR1SeqNo'];
        $tJR1BchCode    = $paParams['tJR1BchCode'];
        $tSessionID     = $paParams['tSessionID'];
        //อัพเดทให้เป็นค่าว่าง ใน Temp
        $this->db->set('FTXtdDisChgTxt', '');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FNXtdSeqNo', $nJR1SeqNo);
        $this->db->where_in('FTBchCode', $tJR1BchCode);
        $this->db->where_in('FTXthDocNo', $tJR1DocNo);
        $this->db->update('TSVTJRQDocDTTmp');
        return;
    }

    //อัพเดทข้อมูล DT Temp
    public function FSaMJR1UpdateInlineDTTemp($paDataUpdateDT, $paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tJR1DocNo      = $paDataWhere['tJR1DocNo'];
        $tJR1BchCode    = $paDataWhere['tJR1BchCode'];
        $nJR1SeqNo      = $paDataWhere['nJR1SeqNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nAdjStaStk     = $paDataWhere['nAdjStaStk'];

        $tSQL   = "
            SELECT
                PKS.FCPdtUnitFact
            FROM TSVTJRQDocDTTmp DTTEMP WITH(NOLOCK)
            LEFT OUTER JOIN TCNMPdtPackSize PKS WITH (NOLOCK) ON DTTEMP.FTPdtCode = PKS.FTPdtCode AND DTTEMP.FTPunCode = PKS.FTPunCode
            WHERE DTTEMP.FTSessionID  = '" . $tSessionID . "'
            AND DTTEMP.FTBchCode    = '" . $tJR1BchCode . "'
            AND DTTEMP.FTXthDocNo   = '" . $tJR1DocNo . "'
            AND DTTEMP.FNXtdSeqNo   = '" . $nJR1SeqNo . "' ";

        $cPdtUnitFact   = $this->db->query($tSQL)->row_array()['FCPdtUnitFact'];
        if ($cPdtUnitFact > 0) {
            $cPdtUnitFact   = $cPdtUnitFact;
        } else {
            $cPdtUnitFact   = 1;
        }
        
        // ======================================== Update Doc DT Temp ========================================
        if( $nAdjStaStk == 'true' ){
            $this->db->set('FTXtdStaPrcStk', NULL);
        }
        $this->db->set('FTXtdPdtName', $paDataUpdateDT['FTXtdPdtName']);
        $this->db->set('FCXtdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCXtdSalePrice', $paDataUpdateDT['FCXtdSalePrice']);
        $this->db->set('FCXtdSetPrice', $paDataUpdateDT['FCXtdSetPrice']);
        $this->db->set('FCXtdNet', $paDataUpdateDT['FCXtdNet']);
        $this->db->set('FCXtdQtyAll', $paDataUpdateDT['FCXtdQty'] * $cPdtUnitFact);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FNXtdSeqNo', $nJR1SeqNo);
        $this->db->where('FTXthDocNo', $tJR1DocNo);
        $this->db->where('FTBchCode', $tJR1BchCode);
        $this->db->update('TSVTJRQDocDTTmp');
        // ====================================================================================================
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Update Success',
            );
        } else {
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Update Fail',
            );
        }
        return $aStatus;
    }

    //เช็คว่ามีสินค้าใน DocDT Temp ไหม
    public function FSnMJR1ChkPdtInDocDTTemp($paDataWhere){
        $tJR1DocNo       = $paDataWhere['FTXshDocNo'];
        $tJR1DocKey      = $paDataWhere['FTXthDocKey'];
        $tJR1SessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TSVTJRQDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocNo    = '$tJR1DocNo'
                            AND DocDT.FTXthDocKey   = '$tJR1DocKey'
                            AND DocDT.FTSessionID   = '$tJR1SessionID' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        } else {
            return 0;
        }
    }

    // หาว่า VAT ตัวสุดท้าย
    public function FSaMJR1CalVatLastDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSessionID     = $paData['tSessionID'];
        $tDataVatInOrEx = $paData['tDataVatInOrEx'];
        $tDocKey        = $paData['tDocKey'];
        $cSumFCXtdVat   = "
            SELECT
                SUM (ISNULL(DOCTMP.FCXtdVat, 0)) AS FCXtdVat
            FROM TSVTJRQDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE 1 = 1
            AND DOCTMP.FTSessionID  = '$tSessionID'
            AND DOCTMP.FTXthDocKey  = '$tDocKey'
            AND DOCTMP.FTXthDocNo   = '$tDocNo'
            AND DOCTMP.FCXtdVatRate > 0
        ";
        $tSql = "UPDATE TSVTJRQDocDTTmp
            SET FCXtdVat = (
                ($cSumFCXtdVat) - (
                    SELECT
                        SUM (DTTMP.FCXtdVat) AS FCXtdVat
                    FROM
                        TSVTJRQDocDTTmp DTTMP
                    WHERE
                        DTTMP.FTSessionID = '$tSessionID'
                    AND DTTMP.FTXthDocNo = '$tDocNo'
                    AND DTTMP.FTXtdVatType = 1
                    AND DTTMP.FNXtdSeqNo != (
                        SELECT
                            TOP 1 SUBDTTMP.FNXtdSeqNo
                        FROM
                            TSVTJRQDocDTTmp SUBDTTMP
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
                    WHEN $tDataVatInOrEx  = 1 --รวมใน
                    THEN FCXtdNet - (
                        ($cSumFCXtdVat) - (
                            SELECT
                                SUM (DTTMP.FCXtdVat) AS FCXtdVat
                            FROM
                                TSVTJRQDocDTTmp DTTMP
                            WHERE
                                DTTMP.FTSessionID = '$tSessionID'
                            AND DTTMP.FTXthDocNo = '$tDocNo'
                            AND DTTMP.FTXtdVatType = 1
                            AND DTTMP.FNXtdSeqNo != (
                                SELECT
                                    TOP 1 SUBDTTMP.FNXtdSeqNo
                                FROM
                                    TSVTJRQDocDTTmp SUBDTTMP
                                WHERE
                                    SUBDTTMP.FTSessionID = '$tSessionID'
                                AND SUBDTTMP.FTXthDocNo = '$tDocNo'
                                AND SUBDTTMP.FTXtdVatType = 1
                                ORDER BY
                                    SUBDTTMP.FNXtdSeqNo DESC
                            )
                        )
                    )
                    WHEN $tDataVatInOrEx  = 2 --แยกนอก
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
                    TSVTJRQDocDTTmp WHDTTMP
                WHERE
                    WHDTTMP.FTSessionID = '$tSessionID'
                AND WHDTTMP.FTXthDocNo = '$tDocNo'
                AND WHDTTMP.FTXtdVatType = 1
                ORDER BY
                    WHDTTMP.FNXtdSeqNo DESC
            )
        ";
        $nRSCounDT =  $this->db->where('FTSessionID', $tSessionID)->where('FTXthDocNo', $tDocNo)->where('FTXtdVatType', '1')->get('TSVTJRQDocDTTmp')->num_rows();
        if ($nRSCounDT > 1) {
            $this->db->query($tSql);
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'cannot Delete Item.',
                );
            }
        } else {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }
        return $aStatus;
    }

    //คำนวณ VAT
    public function FSaMJR1CalInDTTemp($paParams){
        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];

        $tSQL       = " SELECT
                            /* ยอดรวม ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXphTotal,

                            /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNV,

                            /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNoDis,

                            /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgV,

                            /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgNV,

                            /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgV,

                            /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgNV,

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
                            ) AS FCXphAmtV,

                            /* ยอดรวมเฉพาะไม่มีภาษี ==============================================================*/
                            (
                                SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                                -
                                (
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                                    -
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                                )
                            ) AS FCXphAmtNV,

                            /* ยอดภาษี ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXphVat,

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
                            ) AS FCXphVatable,

                            /* รหัสอัตราภาษี ณ ที่จ่าย ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                                FROM TSVTJRQDocDTTmp DOCCONCAT
                                WHERE  1=1
                                AND DOCCONCAT.FTBchCode = '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                                AND DOCCONCAT.FTSessionID = '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXphWpCode,

                            /* ภาษีหัก ณ ที่จ่าย ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdWhtAmt, 0)) AS FCXphWpTax

                        FROM TSVTJRQDocDTTmp DTTMP
                        WHERE DTTMP.FTXthDocNo  = '$tDocNo'
                        AND DTTMP.FTXthDocKey   = '$tDocKey'
                        AND DTTMP.FTSessionID   = '$tSessionID'
                        AND DTTMP.FTBchCode     = '$tBchCode'
                        GROUP BY DTTMP.FTSessionID ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->result_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    //คำนวณ HD Dis
    public function FSaMJR1CalInHDDisTemp($paParams){
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tSQL       = " SELECT
                            /* ข้อความมูลค่าลดชาร์จ ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdDisChgTxt
                                FROM TSVTJRQHDDisTmp DOCCONCAT
                                WHERE  1=1
                                AND DOCCONCAT.FTBchCode 		= '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo		= '$tDocNo'
                                AND DOCCONCAT.FTSessionID		= '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXphDisChgTxt,
                            /* มูลค่ารวมส่วนลด ==============================================================*/
                            SUM(
                                CASE
                                    WHEN HDDISTMP.FTXtdDisChgType = 1 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    WHEN HDDISTMP.FTXtdDisChgType = 2 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    ELSE 0
                                END
                            ) AS FCXphDis,
                            /* มูลค่ารวมส่วนชาร์จ ==============================================================*/
                            SUM(
                                CASE
                                    WHEN HDDISTMP.FTXtdDisChgType = 3 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    WHEN HDDISTMP.FTXtdDisChgType = 4 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    ELSE 0
                                END
                            ) AS FCXphChg
                        FROM TSVTJRQHDDisTmp HDDISTMP
                        WHERE 1=1
                        AND HDDISTMP.FTXthDocNo     = '$tDocNo'
                        AND HDDISTMP.FTSessionID    = '$tSessionID'
                        AND HDDISTMP.FTBchCode      = '$tBchCode'
                        GROUP BY HDDISTMP.FTSessionID ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    //หาว่าเอกสารนี้ โดนใบสั่งงานใช้หรือยัง
    public function FSxMJR1FindDocNoUse($paDataWhere){
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tSQL       = " SELECT
                           TOP 1 FTXshRefKey FROM TSVTJob1ReqHDDocRef WHERE FTXshRefKey = 'Job2Ord' 
                        AND FTXshDocNo      = '$tDocNo'
                        AND FTBchCode       = '$tBchCode' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    ////////////////////////////////////////////// บันทึกข้อมูล //////////////////////////////////////////////

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMJR1AddUpdateHD($paDataMaster, $paDataWhere, $paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMJR1GetDataDocHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));
        $aDataAddUpdateHD   = array();
        if (isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1) {
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster, array(
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        } else {
            $aDataAddUpdateHD   = array_merge($paDataMaster, array(
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }

        // Delete HD
        $this->db->where_in('FTAgnCode', $aDataAddUpdateHD['FTAgnCode']);
        $this->db->where_in('FTBchCode', $aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo', $aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert HD
        $this->db->insert($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
        return;
    }

    //ข้อมูล CST ลบและ เพิ่มใหม่
    public function FSxMJR1AddUpdateCSTHD($paDataCstHD, $paDataWhere, $paTableAddUpdate){
        $aDataGetDataCstHD    =   $this->FSaMJR1GetDataDocCstHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));
        $aDataAddUpdateCstHD    = array();
        if (isset($aDataAddUpdateCstHD['rtCode']) && $aDataAddUpdateCstHD['rtCode'] == 1) {
            $aDataAddUpdateCstHD    = array_merge($paDataCstHD, array(
                'FTAgnCode'     => $aDataGetDataCstHD['FTAgnCode'],
                'FTBchCode'     => $aDataGetDataCstHD['FTBchCode'],
                'FTXshDocNo'    => $aDataGetDataCstHD['FTXshDocNo'],
            ));
        } else {
            $aDataAddUpdateCstHD    = array_merge($paDataCstHD, array(
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            ));
        }
        // Delete CstHD
        $this->db->where_in('FTAgnCode', $aDataAddUpdateCstHD['FTAgnCode']);
        $this->db->where_in('FTBchCode', $aDataAddUpdateCstHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo', $aDataAddUpdateCstHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDCst']);
        // Insert CstHD
        $this->db->insert($paTableAddUpdate['tTableHDCst'], $aDataAddUpdateCstHD);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TSVTJRQDocDTTmp , TSVTJRQHDDisTmp , TSVTJRQDTDisTmp , TSVTJRQDTSetTmp
    public function FSxMJR1AddUpdateDocNoToTemp($paDataWhere, $paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', $paTableAddUpdate['tTableDT']);
        $this->db->update('TSVTJRQDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into HDDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TSVTJRQHDDisTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into DTDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TSVTJRQDTDisTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into TSVTJRQDTSetTmp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TSVTJRQDTSetTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));
        return;
    }

    ////////////////////////////////////////////// MOVE DTTMP TO DT //////////////////////////////////////////////

    //ข้อมูล DT
    public function FSaMJR1MoveDTTmpToDT($paDataWhere, $paTableAddUpdate){
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tDocKey      = $paTableAddUpdate['tTableDT'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXshDocNo', $tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = "     INSERT INTO " . $paTableAddUpdate['tTableDT'] . " (
                            FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo
                            ,FTPdtCode,FTXsdPdtName,FTWahCode,FTPunCode
                            ,FTPunName,FCXsdFactor,FTXsdBarCode,FTXsdVatType
                            ,FTVatCode,FCXsdVatRate,FTPplCode,FCXsdSalePrice
                            ,FCXsdQty,FCXsdQtyAll,FNXsdPdtLevel,FTXsdPdtParent
                            ,FCXsdQtySet,FCXsdSetPrice,FCXsdAmtB4DisChg,FTXsdDisChgTxt
                            ,FCXsdDis,FCXsdChg,FCXsdNet,FCXsdNetAfHD,FCXsdVat
                            ,FCXsdVatable,FCXsdWhtAmt,FTXsdWhtCode,FCXsdWhtRate
                            ,FTPdtStaSet,FTXsdStaPrcStk,FTXsdStaAlwDis,FTXsdRmk,FDLastUpdOn
                            ,FTLastUpdBy,FDCreateOn,FTCreateBy
                        ) ";
        $tSQL   .=  "   SELECT
                            '$tAgnCode'             AS FTAgnCode,
                            DOCTMP.FTBchCode        AS FTBchCode,
                            DOCTMP.FTXthDocNo       AS FTXshDocNo,
                            DOCTMP.FNXtdSeqNo       AS FNXsdSeqNo,
                            DOCTMP.FTPdtCode        AS FTPdtCode,
                            DOCTMP.FTXtdPdtName     AS FTXsdPdtName,
                            CASE WHEN ISNULL(DOCTMP.FTWahCode,'') = '' THEN BCH.FTWahCode ELSE DOCTMP.FTWahCode END AS FTWahCode,
                            DOCTMP.FTPunCode        AS FTPunCode,
                            DOCTMP.FTPunName        AS FTPunName,
                            DOCTMP.FCXtdFactor      AS FCXsdFactor,
                            DOCTMP.FTXtdBarCode     AS FTXsdBarCode,
                            DOCTMP.FTXtdVatType     AS FTXsdVatType,
                            DOCTMP.FTVatCode        AS FTVatCode,
                            DOCTMP.FCXtdVatRate     AS FCXsdVatRate,
                            ''                      AS FTPplCode,
                            DOCTMP.FCXtdSalePrice   AS FCXsdSalePrice,
                            DOCTMP.FCXtdQty         AS FCXsdQty,
                            DOCTMP.FCXtdQtyAll      AS FCXsdQtyAll,
                            1                       AS FNXsdPdtLevel,
                            DOCTMP.FTXtdPdtParent   AS FTXsdPdtParent,
                            DOCTMP.FCXtdQtySet      AS FCXsdQtySet,
                            DOCTMP.FCXtdSetPrice    AS FCXsdSetPrice,
                            DOCTMP.FCXtdAmtB4DisChg AS FCXsdAmtB4DisChg,
                            DOCTMP.FTXtdDisChgTxt   AS FTXsdDisChgTxt,
                            DOCTMP.FCXtdDis         AS FCXsdDis,
                            DOCTMP.FCXtdChg         AS FCXsdChg,
                            DOCTMP.FCXtdNet         AS FCXsdNet,
                            DOCTMP.FCXtdNetAfHD     AS FCXsdNetAfHD,
                            DOCTMP.FCXtdVat         AS FCXsdVat,
                            DOCTMP.FCXtdVatable     AS FCXsdVatable,
                            DOCTMP.FCXtdWhtAmt      AS FCXsdWhtAmt,
                            DOCTMP.FTXtdWhtCode     AS FTXsdWhtCode,
                            DOCTMP.FCXtdWhtRate     AS FCXsdWhtRate,
                            DOCTMP.FTXtdPdtStaSet   AS FTPdtStaSet,
                            DOCTMP.FTXtdStaPrcStk   AS FTXsdStaPrcStk,
                            DOCTMP.FTXtdStaAlwDis   AS FTXsdStaAlwDis,
                            DOCTMP.FTXtdRmk         AS FTXsdRmk,
                            DOCTMP.FDLastUpdOn      AS FDLastUpdOn,
                            DOCTMP.FTLastUpdBy      AS FTLastUpdBy,
                            DOCTMP.FDCreateOn       AS FDCreateOn,
                            DOCTMP.FTCreateBy       AS FTCreateBy
                        FROM TSVTJRQDocDTTmp    DOCTMP WITH (NOLOCK)
                        LEFT JOIN TCNMBranch BCH WITH(NOLOCK) ON DOCTMP.FTBchCode = BCH.FTBchCode
                        WHERE DOCTMP.FTBchCode    = '$tBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tDocKey'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC";
        $this->db->query($tSQL);
        return;
    }

    //ข้อมูล DTSet
    public function FSaMJR1MoveDTTmpSetToDTSet($paDataWhere, $paTableAddUpdate){
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXshDocNo', $tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDTSet']);
        }

        $tSQL   = "     INSERT INTO " . $paTableAddUpdate['tTableDTSet'] . " (
                            FTAgnCode,FTBchCode,FTXshDocNo,FNXsdSeqNo,FNPstSeqNo,
                            FTPdtCode,FTPsvType,FTXsdPdtName,FTPunCode,FCXsdQtySet,
                            FCXsdSalePrice,FTPdtCodeOrg,FTPsvStaSuggest
                        ) ";
        $tSQL   .=  "   SELECT
                            '$tAgnCode'             AS FTAgnCode,
                            DOCTMP.FTBchCode        AS FTBchCode ,
                            DOCTMP.FTXthDocNo       AS FTXshDocNo ,
                            DT.FNXsdSeqNo           AS FNXsdSeqNo ,
                            DOCTMP.FNPstSeqNo       AS FNPstSeqNo ,
                            DOCTMP.FTPdtCode        AS FTPdtCode ,
                            DOCTMP.FTPsvType        AS FTPsvType ,
                            DOCTMP.FTXtdPdtName     AS FTXsdPdtName ,
                            DOCTMP.FTPunCode        AS FTPunCode ,
                            DOCTMP.FCXtdQtySet      AS FCXsdQtySet ,
                            DOCTMP.FCXtdSalePrice   AS FCXsdSalePrice ,
                            DOCTMP.FTPdtCodeOrg     AS FTPdtCodeOrg ,
                            DOCTMP.FTPsvStaSuggest  AS FTPsvStaSuggest
                        FROM TSVTJRQDTSetTmp DOCTMP WITH (NOLOCK)
                        LEFT JOIN TSVTJob1ReqDT DT WITH (NOLOCK) ON DOCTMP.FTSrnCode = DT.FTPdtCode AND DT.FNXsdSeqNo = DOCTMP.FNXtdSeqNo
                        WHERE DOCTMP.FTBchCode    = '$tBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tDocNo'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        AND DT.FTXshDocNo = '$tDocNo'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    //ข้อมูล HDDis
    public function FSaMJR1MoveHDDisTempToHDDis($paDataWhere, $paTableAddUpdate){
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXshDocNo', $tDocNo);
            $this->db->where_in('FTBchCode', $tBchCode);
            $this->db->delete($paTableAddUpdate['tTableHDDis']);
        }

        $tSQL   =   "   INSERT INTO " . $paTableAddUpdate['tTableHDDis'] . " (
                            FTBchCode,
                            FTXshDocNo,
                            FDXshDateIns,
                            FTXshDisChgTxt,
                            FTXshDisChgType,
                            FCXshTotalAfDisChg,
                            FCXshDisChg,
                            FCXshAmt,
                            FTAgnCode
                        )";
        $tSQL   .=  "   SELECT
                            HDDISTEMP.FTBchCode             AS FTBchCode,
                            HDDISTEMP.FTXthDocNo            AS FTXshDocNo,
                            HDDISTEMP.FDXtdDateIns          AS FDXhdDateIns,
                            HDDISTEMP.FTXtdDisChgTxt        AS FTXhdDisChgTxt,
                            HDDISTEMP.FTXtdDisChgType       AS FTXhdDisChgType,
                            HDDISTEMP.FCXtdTotalAfDisChg    AS FCXhdTotalAfDisChg,
                            HDDISTEMP.FCXtdDisChg           AS FCXhdDisChg,
                            HDDISTEMP.FCXtdAmt              AS FCXhdAmt,
                            '$tAgnCode'                     AS FTAgnCode
                        FROM TSVTJRQHDDisTmp AS HDDISTEMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND HDDISTEMP.FTBchCode     = '$tBchCode'
                        AND HDDISTEMP.FTXthDocNo    = '$tDocNo'
                        AND HDDISTEMP.FTSessionID   = '$tSessionID'";
        $this->db->query($tSQL);
        return;
    }

    //ข้อมูล DTDis
    public function FSaMJR1MoveDTDisTempToDTDis($paDataWhere, $paTableAddUpdate){
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXshDocNo', $tDocNo);
            $this->db->where_in('FTBchCode', $tBchCode);
            $this->db->delete($paTableAddUpdate['tTableDTDis']);
        }

        $tSQL   =   "   INSERT INTO " . $paTableAddUpdate['tTableDTDis'] . " (
                            FTBchCode ,FTXshDocNo ,FNXsdSeqNo
                            ,FDXsdDateIns ,FNXsdStaDis ,FTXsdDisChgTxt
                            ,FTXsdDisChgType ,FCXsdNet ,FCXsdValue ,FTAgnCode
                        ) ";
        $tSQL   .=  "   SELECT
                            DOCDISTMP.FTBchCode         AS FTBchCode,
                            DOCDISTMP.FTXthDocNo        AS FTXshDocNo,
                            DOCDISTMP.FNXtdSeqNo        AS FNXsdSeqNo,
                            DOCDISTMP.FDXtdDateIns      AS FDXsdDateIns,
                            DOCDISTMP.FNXtdStaDis       AS FNXsdStaDis,
                            DOCDISTMP.FTXtdDisChgTxt    AS FTXsdDisChgTxt,
                            DOCDISTMP.FTXtdDisChgType   AS FTXsdDisChgType,
                            DOCDISTMP.FCXtdNet          AS FCXsdNet,
                            DOCDISTMP.FCXtdValue        AS FCXsdValue,
                            '$tAgnCode'                 AS FTAgnCode
                        FROM TSVTJRQDTDisTmp DOCDISTMP WITH (NOLOCK)
                        WHERE 1=1
                        AND DOCDISTMP.FTBchCode     = '$tBchCode'
                        AND DOCDISTMP.FTXthDocNo    = '$tDocNo'
                        AND DOCDISTMP.FTSessionID   = '$tSessionID'
                        ORDER BY DOCDISTMP.FNXtdSeqNo ASC ";
        $this->db->query($tSQL);
        // echo $this->db->last_query();exit;
        return;
    }

    ////////////////////////////////////////////// เข้าหน้าแก้ไข //////////////////////////////////////////////

    //ข้อมูล HD
    public function FSaMJR1GetDataDocHD($paDataWhere){
        $tJR1DocNo  = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            DOCHD.*,
                            BCHL.FTBchName,
                            USRL.FTUsrName,
                            RTE_L.FTRteName,
                            USRAPV.FTUsrName	AS FTXshApvName,
                            USRGETCar.FTUsrCode AS rtUserGetCarCode,
                            USRGETCar.FTUsrName AS rtUserGetCarName,
                            HDDocRef_in.FTXshRefDocNo   AS DocRefIn,
                            HDDocRef_in.FDXshRefDocDate AS DateRefIn,
                            HDDocRef_ex.FTXshRefDocNo   AS DocRefEx,
                            HDDocRef_ex.FDXshRefDocDate AS DateRefEx,
                            BAYL.FTSpsName AS FTXshToPosName,
                            AGNL.FTAgnName
                        FROM TSVTJob1ReqHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TSVTJob1ReqHDDocRef   HDDocRef_in    WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = HDDocRef_in.FTXshDocNo   AND HDDocRef_in.FTXshRefType = 1
                        LEFT JOIN TSVTJob1ReqHDDocRef   HDDocRef_ex    WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = HDDocRef_ex.FTXshDocNo   AND HDDocRef_ex.FTXshRefType = 3
                        LEFT JOIN TSVMPos_L BAYL WITH(NOLOCK) ON DOCHD.FTBchCode = BAYL.FTBchCode AND DOCHD.FTAgnCode = BAYL.FTAgnCode AND DOCHD.FTXshToPos = BAYL.FTSpsCode AND BAYL.FNLngID = '$nLngID'
                        LEFT JOIN TCNMBranch_L          BCHL        WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode        AND BCHL.FNLngID	    = '$nLngID'
                        LEFT JOIN TCNMUser_L            USRL        WITH (NOLOCK)   ON DOCHD.FTCreateBy     = USRL.FTUsrCode	    AND USRL.FNLngID	    = '$nLngID'
                        LEFT JOIN TCNMUser_L            USRGETCar	WITH (NOLOCK)   ON DOCHD.FTUsrCode	    = USRGETCar.FTUsrCode	AND USRGETCar.FNLngID	= '$nLngID'
                        LEFT JOIN TCNMUser_L            USRAPV	    WITH (NOLOCK)   ON DOCHD.FTXshApvCode	= USRAPV.FTUsrCode	    AND USRAPV.FNLngID	    = '$nLngID'
                        LEFT JOIN TCNMCst               CST         WITH (NOLOCK)   ON DOCHD.FTCstCode		= CST.FTCstCode
                        LEFT JOIN TCNMCst_L             CST_L       WITH (NOLOCK)   ON DOCHD.FTCstCode		= CST_L.FTCstCode       AND CST_L.FNLngID	    = '$nLngID'
                        LEFT JOIN TFNMRate_L            RTE_L       WITH (NOLOCK)   ON DOCHD.FTRteCode      = RTE_L.FTRteCode       AND RTE_L.FNLngID	    = '$nLngID'
                        LEFT JOIN TCNMAgency_L          AGNL        WITH(NOLOCK)    ON DOCHD.FTAgnCode      = AGNL.FTAgnCode        AND AGNL.FNLngID        = '$nLngID'
                        WHERE 1=1 AND DOCHD.FTXshDocNo = '$tJR1DocNo'
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    //ข้อมูล CST
    public function FSaMJR1GetDataDocCstHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            CSTHD.FTAgnCode,
                            CSTHD.FTBchCode     AS FTBchCode,
                            CSTHD.FTXshDocNo    AS FTXshDocNo,
                            CST.FTCstCardID     AS FTXshCardID,
                            CSTHD.FNXshCrTerm   AS FNXshCrTerm,
                            CSTHD.FDXshDueDate  AS FDXshDueDate,
                            CSTHD.FDXshBillDue  AS FDXshBillDue,
                            CSTHD.FTXshCtrName  AS FTXshCtrName,
                            CSTHD.FDXshTnfDate  AS FDXshTnfDate,
                            CSTHD.FTXshRefTnfID AS FTXshRefTnfID,
                            CSTHD.FNXshAddrShip AS FNXshAddrShip,
                            CSTHD.FTXshAddrTax  AS FTXshAddrTax,
                            CST_L.FTCstName     AS FTCstName,
                            CST_L.FTCstCode     AS FTCstCode,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            ADDL.FTAddV2Desc1,
                            CSTLEV.FTPplCode
                        FROM TSVTJob1ReqHDCst   CSTHD       WITH (NOLOCK)
                        LEFT JOIN TSVTJob1ReqHD HDDoc       WITH (NOLOCK)   ON CSTHD.FTXshDocNo = HDDoc.FTXshDocNo AND CSTHD.FTBchCode = HDDoc.FTBchCode
                        LEFT JOIN TCNMCst	    CST         WITH (NOLOCK)   ON CST.FTCstCode = HDDoc.FTCstCode
                        LEFT JOIN TCNMCst_L	    CST_L       WITH (NOLOCK)   ON CST.FTCstCode = CST_L.FTCstCode AND CST_L.FNLngID	= $nLngID
                        LEFT JOIN TCNMCstAddress_L ADDL     WITH (NOLOCK)   ON CST.FTCstCode = ADDL.FTCstCode
                        LEFT JOIN TCNMCstLev    CSTLEV      WITH (NOLOCK)   ON CST.FTClvCode = CSTLEV.FTClvCode
                        WHERE 1=1 AND CSTHD.FTXshDocNo = '$tDocNo' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    ///////////////////////////////////////// ย้ายข้อมูลจากจริงไป Temp /////////////////////////////////////////

    //ย้ายจาก HDDis To Temp
    public function FSxMJR1MoveHDDisToTemp($paDataWhere){
        $tSessionID = $paDataWhere['tSessionID'];
        // $tAgnCode   = $paDataWhere['FTAgnCode'];
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocNo     = $paDataWhere['FTXshDocNo'];

        // Delect Document HD DisTemp By Doc No
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->delete('TSVTJRQHDDisTmp');

        $tSQL = "
            INSERT INTO TSVTJRQHDDisTmp (
                FTAgnCode,
                FTBchCode,
                FTXthDocNo,
                FDXtdDateIns,
                FTXtdDisChgTxt,
                FTXtdDisChgType,
                FCXtdTotalAfDisChg,
                FCXtdTotalB4DisChg,
                FCXtdDisChg,
                FCXtdAmt,
                FTSessionID,
                FDLastUpdOn,
                FDCreateOn,
                FTLastUpdBy,
                FTCreateBy
            )
            SELECT
                HDDis.FTAgnCode,
                HDDis.FTBchCode,
                HDDis.FTXshDocNo,
                HDDis.FDXshDateIns,
                HDDis.FTXshDisChgTxt,
                HDDis.FTXshDisChgType,
                HDDis.FCXshTotalAfDisChg,
                (ISNULL(NULL,0)) AS FCXshTotalB4DisChg,
                HDDis.FCXshDisChg,
                HDDis.FCXshAmt,
                CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
            FROM TSVTJob1ReqHDDis HDDis WITH(NOLOCK)
            WHERE HDDis.FTBchCode = '$tBchCode'
            AND HDDis.FTXshDocNo = '$tDocNo'
        ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DT To Temp
    public function FSxMJR1MoveDTToDTTemp($paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['FTXshDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->delete('TSVTJRQDocDTTmp');

        $tSQL   = " INSERT INTO TSVTJRQDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FTXtdVatType,FTVatCode,FCXtdVatRate,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
                        FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
                        FCXtdWhtRate,FTXtdStaPrcStk,FTXtdStaAlwDis,
                        FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTPdtSetOrSN,FTXtdRmk,FTWahCode,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdSaleType )
                    SELECT
                        DT.FTBchCode,
                        DT.FTXshDocNo,
                        DT.FNXsdSeqNo,
                        '$tDocKey',
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        DT.FTXsdVatType,
                        DT.FTVatCode,
                        DT.FCXsdVatRate,
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
                        DT.FCXsdWhtRate,
                        DT.FTXsdStaPrcStk,
                        DT.FTXsdStaAlwDis,
                        DT.FNXsdPdtLevel,
                        DT.FTXsdPdtParent,
                        DT.FCXsdQtySet,
                        DT.FTPdtStaSet,
                        DT.FTPdtStaSet AS FTPdtSetOrSN,
                        DT.FTXsdRmk,
                        DT.FTWahCode,
                        CONVERT(VARCHAR,'" . $tSessionID . "') AS FTSessionID,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
                        PDT.FTPdtType
                    FROM TSVTJob1ReqDT AS DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON PDT.FTPdtCode = DT.FTPdtCode
                    WHERE DT.FTXshDocNo = '$tDocNo'
                    ORDER BY DT.FNXsdSeqNo ASC
        ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DTSet To TempSet
    public function FSxMJR1MoveDTSetToDTTempSet($paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['FTXshDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->delete('TSVTJRQDTSetTmp');

        $tSQL   = " INSERT INTO TSVTJRQDTSetTmp (
                        FTAgnCode,FTBchCode,FTXthDocNo,FNXtdSeqNo,FNPstSeqNo,FTPdtCode,FTPsvType,FTXtdPdtName,FTPunCode,FCXtdQtySet,FCXtdSalePrice,
                        FTPdtCodeOrg,FTPsvStaSuggest,FTSrnCode,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy,FTSessionID,FTXthDocKey
                    )
                    SELECT
                        DTSET.FTAgnCode,
                        DTSET.FTBchCode,
                        DTSET.FTXshDocNo,
                        DTSET.FNXsdSeqNo,
                        DTSET.FNPstSeqNo,
                        DTSET.FTPdtCode,
                        DTSET.FTPsvType,
                        DTSET.FTXsdPdtName,
                        DTSET.FTPunCode,
                        DTSET.FCXsdQtySet,
                        DTSET.FCXsdSalePrice,
                        DTSET.FTPdtCodeOrg,
                        DTSET.FTPsvStaSuggest,
                        DT.FTPdtCode,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                        'TSVTJob1ReqDT' AS FTXthDocKey
                    FROM TSVTJob1ReqDTSet AS DTSET WITH (NOLOCK)
                    LEFT JOIN TSVTJob1ReqDT AS DT ON DTSET.FTXshDocNo = DT.FTXshDocNo AND DT.FNXsdSeqNo = DTSET.FNXsdSeqNo
                    WHERE 1=1 AND DTSET.FTXshDocNo = '$tDocNo'
                    ORDER BY DTSET.FNXsdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DTDis To Temp
    public function FSxMJR1MoveDTDisToDTDisTemp($paDataWhere){
        $tSessionID   = $paDataWhere['tSessionID'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];

        // Delect Document DTDisTemp By Doc No
        // $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->delete('TSVTJRQDTDisTmp');

        $tSQL   = " INSERT INTO TSVTJRQDTDisTmp (
                        FTBchCode,
                        FTXthDocNo,
                        FNXtdSeqNo,
                        FTSessionID,
                        FDXtdDateIns,
                        FNXtdStaDis,
                        FTXtdDisChgType,
                        FCXtdNet,
                        FCXtdValue,
                        FDLastUpdOn,
                        FDCreateOn,
                        FTLastUpdBy,
                        FTCreateBy,
                        FTXtdDisChgTxt
                    )
                    SELECT
                        DTDis.FTBchCode,
                        DTDis.FTXshDocNo,
                        DTDis.FNXsdSeqNo,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "')    AS FTSessionID,
                        DTDis.FDXsdDateIns,
                        DTDis.FNXsdStaDis,
                        DTDis.FTXsdDisChgType,
                        DTDis.FCXsdNet,
                        DTDis.FCXsdValue,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
                        DTDis.FTXsdDisChgTxt
                    FROM TSVTJob1ReqDTDis DTDis WITH(NOLOCK)
                    WHERE DTDis.FTXshDocNo = '$tDocNo'
                    ORDER BY DTDis.FNXsdSeqNo ASC";
        $this->db->query($tSQL);
        return;
    }

    //ยกเลิกเอกสาร
    public function FSaMJR1UpdateStaDocCancel($paDataUpdate){
        try {
            $this->db->set('FDLastUpdOn', date('Y-m-d H:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FTXshStaDoc', $paDataUpdate['FTXshStaDoc']);
            $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
            $this->db->where('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob1ReqHD');

            //อัพเดทเอกสาร นัดหมาย ให้กลับมาใช้งานได้อีก
            if ($paDataUpdate['tRefInt'] != '' || $paDataUpdate['tRefInt'] != null) {
                $this->db->where('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
                $this->db->delete('TSVTJob1ReqHDDocRef');

                $this->db->where('FTXshRefDocNo', $paDataUpdate['FTXshDocNo']);
                $this->db->delete('TSVTBookHDDocRef');
            }

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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ตรวจสิบค้าก่อนอนุมัตเอกสาร
    public function FSaMJR1CheckApproveDocument($paDataUpdate){
        $tBchCode       = $paDataUpdate['FTBchCode'];
        $tXshDocNo      = $paDataUpdate['FTXshDocNo'];
        $tSQL           = "SELECT FTXsdStaPrcStk from TSVTJob1ReqDT WHERE FTXshDocNo = '$tXshDocNo' AND FTBchCode = '$tBchCode' AND ISNULL(FTXsdStaPrcStk,'') = '' ";
        $oQueryCheckSTK = $this->db->query($tSQL);
        $aItemCheckSTK  = $oQueryCheckSTK->result_array();
        if (!empty($aItemCheckSTK)) {
            $aResult = array(
                'rtCode'    => '800',
                'rtItem'    => $aItemCheckSTK,
                'rtDesc'    => 'ยังมีสินค้าที่ไม่สมบูรณ์'
            );
        } else {
            $aResult = array(
                'rtCode'    => '1',
                'rtItem'    => array(),
                'rtDesc'    => 'สมบูรณ์'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //อนุมัตเอกสาร
    public function FSaMJR1ApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXshStaApv', $paDataUpdate['FTXshStaApv']);
        $this->db->set('FTXshApvCode', $paDataUpdate['FTXshApvCode']);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
        $this->db->update('TSVTJob1ReqHD');

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

    // อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMJR1UpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXshRmk', $paDataUpdate['FTXshRmk']);

        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
        $this->db->update('TSVTJob1ReqHD');

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

    ///////////////////////////////////////// อ้างอิงเอกสารภายใน (ref) /////////////////////////////////////////

    //อัพเดท เอกสารอ้างอิง ภายใน ภายนอก
    public function FSxMJR1UpdateRef($ptTableName, $paParam){
        $nChkDataDocRef  = $this->FSaMJR1ChkRefDupicate($ptTableName, $paParam);
        $tTableRef       = $ptTableName;
        if (isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1) { //หากพบว่าซ้ำ
            //ลบ
            $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $paParam['FTXshDocNo']);
            $this->db->where_in('FTXshRefType', $paParam['FTXshRefType']);
            $this->db->where_in('FTXshRefKey', $paParam['FTXshRefKey']);
            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef, $paParam);
        } else { //หากพบว่าไม่ซ้ำ
            $this->db->insert($tTableRef, $paParam);
        }
        return;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMJR1ChkRefDupicate($ptTableName, $paParam){
        try {
            $tAgnCode       = $paParam['FTAgnCode'];
            $tBchCode       = $paParam['FTBchCode'];
            $tDocNo         = $paParam['FTXshDocNo'];
            $tRefDocType    = $paParam['FTXshRefType'];
            $tRefDocNo      = $paParam['FTXshDocNo'];

            $tSQL = "   SELECT
                            FTAgnCode
                        FROM $ptTableName
                        WHERE 1=1
                        AND FTAgnCode     = '$tAgnCode'
                        AND FTBchCode     = '$tBchCode'
                        AND FTXshRefType  = '$tRefDocType' ";

            if ($tRefDocType == 1 || $tRefDocType == 3) {
                $tSQL .= " AND FTXshDocNo  = '$tDocNo' ";
            } else {
                $tSQL .= " AND FTXshDocNo  = '$tRefDocNo' ";
            }

            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0) {
                $aResult    = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            } else {
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    ///////////////////////////////////////// แก้ไข Edit Inline Product /////////////////////////////////////
    
    // ดึงข้อมูลราคาสินค้า Product Active
    public function FSaMJR1GetDataPricePdt($ptPdtCode, $ptPunCode){
        $tSQL   = "
            SELECT
                PDT.FTPdtCode,
                PRI.FTPunCode,
                PRI.FDPghDStart,
                PRI.FCPgdPriceNet,
                PRI.FCPgdPriceRet,
                PRI.FCPgdPriceWhs
            FROM TCNMPdt PDT WITH(NOLOCK)
            LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON PDT.FTPdtCode = PRI.FTPdtCode AND PRI.FTPunCode = '$ptPunCode'
            WHERE PDT.FTPdtCode = '$ptPdtCode';
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        return $aDataReturn;
    }

    // ดึงข้อมูลรายการสินค้า Set
    public function FSaMJR1UpdPdtSetInline($paDataUpdate, $paDataWhere){
        $this->db->where_in('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where_in('FTSrnCode', $paDataWhere['FTSrnCode']);
        $this->db->where_in('FTPdtCodeOrg', $paDataWhere['FTPdtCodeOrg']);
        $this->db->where_in('FTBchCode', $paDataWhere['FTBchCode']);
        $this->db->update('TSVTJRQDTSetTmp', $paDataUpdate);
    }

    // ดึงข้อมูลราคาสินค้า Product Org
    public function FSaMJR1GetDataPdtOrg($paDataWhere){
        $tSQL   = "
            SELECT
                DTS.FTPdtCodeOrg 	AS FTPdtCodeOrg,
                PDTL.FTPdtName		AS FTPdtNameOrg,
                PDPS.FTPunCode		AS FTPunCodeOrg,
                ISNULL(PRI.FCPgdPriceRet,0) AS FCPgdPriceRetOrg
            FROM TSVTJRQDTSetTmp DTS WITH(NOLOCK)
            LEFT JOIN TCNMPdt_L	PDTL WITH(NOLOCK) ON DTS.FTPdtCodeOrg = PDTL.FTPdtCode AND PDTL.FNLngID = '1'
            LEFT JOIN TCNMPdtPackSize PDPS WITH(NOLOCK) ON DTS.FTPdtCodeOrg = PDPS.FTPdtCode AND DTS.FCXtdQtySet = PDPS.FCPdtUnitFact
            LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON DTS.FTPdtCodeOrg = PRI.FTPdtCode AND PDPS.FTPunCode = PRI.FTPunCode
            WHERE DTS.FTBchCode 	= '" . $paDataWhere['FTBchCode'] . "'
            AND DTS.FTXthDocNo	    = '" . $paDataWhere['FTXthDocNo'] . "'
            AND DTS.FTPdtCode		= '" . $paDataWhere['FTPdtCode'] . "'
            AND DTS.FTPdtCodeOrg	= '" . $paDataWhere['FTPdtCodeOrg'] . "'
            AND DTS.FTSessionID	    = '" . $paDataWhere['FTSessionID'] . "'
        ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();exit;
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        return $aDataReturn;
    }

    // ลบเอกสาร Document
    public function FSnMJR1DelDocument($paDataDoc){
        $tAgnCode   = $paDataDoc['FTAgnCode'];
        $tBchCode   = $paDataDoc['FTBchCode'];
        $tDataDocNo = $paDataDoc['FTXshDocNo'];
        $this->db->trans_begin();

        // Document DT
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqDT');

        // Document DT SET
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqDTSet');

        // Document DT Discount
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqDTDis');

        // Document HD
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqHD');

        // Document HD CST
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqHDCst');

        // Document HD Discount
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqHDDis');

        // Document HD Doc Ref
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TSVTJob1ReqHDDocRef');

        // Remove Doc Ref Booking
        $this->db->where_in('FTXshRefDocNo', $tDataDocNo);
        $this->db->delete('TSVTBookHDDocRef');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStaDelDoc = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStaDelDoc     = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        return $aStaDelDoc;
    }

    // Clear Pdt In Tmp
    public function FSxMJR1ClearPdtInTmp($ptPdtConvertIN) {

        $tSession = $this->session->userdata('tSesSessionID');

        if ($ptPdtConvertIN != "") {
            $tSQL = "   DELETE FROM TSVTJRQDTSetTmp WHERE FTSrnCode IN (
                            SELECT TMP.FTPdtCode
                            FROM TSVTJRQDocDTTmp TMP WITH(NOLOCK)
                            LEFT JOIN TSVTBookDT DT WITH(NOLOCK) ON TMP.FTPdtCode = DT.FTPdtCode AND DT.FTXshDocNo = 'BK0000121000008'
                            WHERE TMP.FTSessionID = '$tSession' AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'
                            AND ( DT.FTPdtCode IN ($ptPdtConvertIN) OR TMP.FTPdtCode NOT IN ($ptPdtConvertIN) )
                        )
                        AND FTSessionID = '$tSession'
                        AND FTXthDocKey = 'TSVTJob1ReqDT' ";
            $this->db->query($tSQL);

            $tSQL = "   DELETE FROM TSVTJRQDocDTTmp WHERE FTPdtCode IN (
                            SELECT TMP.FTPdtCode
                            FROM TSVTJRQDocDTTmp TMP WITH(NOLOCK)
                            LEFT JOIN TSVTBookDT DT WITH(NOLOCK) ON TMP.FTPdtCode = DT.FTPdtCode AND DT.FTXshDocNo = 'BK0000121000008'
                            WHERE TMP.FTSessionID = '$tSession' AND TMP.FTXthDocKey = 'TSVTJob1ReqDT'
                            AND ( DT.FTPdtCode IN ($ptPdtConvertIN) OR TMP.FTPdtCode NOT IN ($ptPdtConvertIN) )
                        )
                        AND FTSessionID = '$tSession'
                        AND FTXthDocKey = 'TSVTJob1ReqDT' ";
            $this->db->query($tSQL);
        } else {
            $this->db->where('FTSessionID', $tSession);
            $this->db->delete('TSVTJRQDTSetTmp');

            $this->db->where('FTSessionID', $tSession);
            $this->db->delete('TSVTJRQDTDisTmp');

            $this->db->where('FTSessionID', $tSession);
            $this->db->delete('TSVTJRQDocDTTmp');
        }
        // echo $this->db->last_query();

        // if( FCNnHSizeOf($paPdtDefConfig) > 0 ){
        //     $this->db->where_not_in('FTSrnCode',$paPdtDefConfig);
        // }
        // $this->db->where('FTSessionID',$tSession);
        // $this->db->delete('TSVTJRQDTSetTmp');
        // echo $this->db->last_query();

        // $this->db->where('FTSessionID',$tSession);
        // $this->db->delete('TSVTJRQDTDisTmp');

    }

    public function FSaMJR1GetPdtInTmpForSendToAPI($paData){
        $tBchCode   = $paData['FTBchCode'];
        $tDocCode   = $paData['FTXshDocNo'];
        $tDocKey    = $paData['FTXthDocKey'];
        $tSessionID = $paData['FTSessionID'];
        $tSQL       = " 
            SELECT
                TMP.FTPdtCode           AS ptPdtCode,
                TMP.FTBchCode           AS ptBchCode,
                BCH.FTWahCode			AS ptWahCode,
                TMP.FCXtdQty            AS pcQty
            FROM TSVTJRQDocDTTmp          TMP WITH(NOLOCK)
            INNER JOIN TCNMPdt         PDT WITH(NOLOCK) ON TMP.FTPdtCode = PDT.FTPdtCode
            LEFT JOIN TSVTJRQDocDTTmp PDTMAIN WITH(NOLOCK) ON TMP.FTSrnCode = PDTMAIN.FTPdtCode
            LEFT JOIN TCNMBranch      BCH WITH(NOLOCK) ON TMP.FTBchCode = BCH.FTBchCode
            WHERE TMP.FTXthDocNo    = " . $this->db->escape($tDocCode) . "
            AND TMP.FTBchCode       = " . $this->db->escape($tBchCode) . "
            AND TMP.FTXthDocKey     = " . $this->db->escape($tDocKey) . "
            AND TMP.FTSessionID     = " . $this->db->escape($tSessionID) . "
            AND PDT.FTPdtStkControl = '1'
            AND ISNULL(TMP.FTXtdStaPrcStk,'') != '1'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    public function FSxMJR1UpdatePdtStkPrc($paDataWhere, $paHavePdtInWah){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocCode   = $paDataWhere['FTXshDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];

        $this->db->set('FTXsdRmk', '1');
        $this->db->where_in('FTPdtCode', $paHavePdtInWah);
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXshDocNo', $tDocCode);
        $this->db->update('TSVTJob1ReqDT');

        return $this->db->last_query();
    }

    //Get ข้อมูล API
    public function FSaMJR1GetConfigAPI(){
        $tSQL       = "SELECT TOP 1 * FROM TCNTUrlObject WHERE FTUrlKey = 'CHKSTK' AND FTUrlTable = 'TCNMComp' AND FTUrlRefID = 'CENTER' ORDER BY FNUrlSeq ASC";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => '',
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    public function FSaMJR1UpdPdtCstFollow($paData){
        $tSessionID = $paData['tSessionID'];
        $tDocKey    = $paData['tDocKey'];
        $tBhcCode   = $paData['tBhcCode'];
        $tDocNo     = $paData['tDocNo'];
        $tCarCode   = $paData['tCarCode'];
        $nLngID     = $paData['nLngID'];

        $tSQL = "   UPDATE DTSET
                        SET DTSET.FTPdtCode = CSTF.FTPdtCode, DTSET.FTXtdPdtName = PDTL.FTPdtName
                    FROM TSVTJRQDTSetTmp DTSET WITH(NOLOCK)
                    INNER JOIN TSVTCstFollow CSTF WITH(NOLOCK) ON DTSET.FTPdtCodeOrg = CSTF.FTPdtCodeOrg AND CSTF.FTCarCode = '$tCarCode'
                    LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK) ON CSTF.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                    WHERE DTSET.FTSessionID = '$tSessionID'
                    AND DTSET.FTXthDocKey   = '$tDocKey'
                    AND DTSET.FTBchCode     = '$tBhcCode'
                    AND DTSET.FTXthDocNo    = '$tDocNo' ";
        $this->db->query($tSQL);
    }

    public function FStMJR1GetPdtDefConfig(){
        $tSQL = "   SELECT
                        CASE WHEN ISNULL(FTSysStaUsrValue,'') = '' THEN FTSysStaDefValue ELSE FTSysStaUsrValue END FTValue
                    FROM TSysConfig
                    WHERE FTSysCode = 'tDoc_PdtDefault'
                    AND FTSysApp = 'CN'
                    AND FTSysKey = 'ProductDefNewDoc'
                    AND FTSysSeq = 1
                    AND FTGmnCode = 'XDOC'
                ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $tReturn = $oQuery->row_array()['FTValue'];
        } else {
            $tReturn = '';
        }
        return $tReturn;
    }

    public function FSaMJR1GetSmallUnit($ptPdtCode){
        $tSQL = "   SELECT A.*
                    FROM (
                        SELECT ROW_NUMBER() OVER(PARTITION BY FTPdtCode ORDER BY FTPdtCode,FCPdtUnitFact ASC) AS FNSmallUnit,
                        FTPdtCode,FTPunCode,FCPdtUnitFact
                        FROM TCNMPdtPackSize WITH(NOLOCK)
                        WHERE FTPdtCode IN ($ptPdtCode)
                    ) A
                    WHERE A.FNSmallUnit = 1
                ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'aItems'       => $oQuery->result_array(),
                'tCode'        => '1',
                'tDesc'        => 'found data'
            );
        } else {
            $aResult    = array(
                'aItems'       => array(),
                'tCode'        => '800',
                'tDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

    //เช็คสต็อก
    public function FSaMJR1CheckWahouseCheckStock($ptDocNo){
        $tDocCode   = $ptDocNo;
        $tSQL       = " SELECT TOP 1 SUM(A.CHKSTK) AS CHKSTK FROM(
                            SELECT DT.FTWahCode , CASE WHEN WAH.FTWahStaChkStk = 1 THEN 0 ELSE 1 END AS CHKSTK FROM TSVTJob1ReqDT DT
                            LEFT JOIN TCNMWaHouse WAH ON DT.FTWahCode = WAH.FTWahCode AND DT.FTBchCode = WAH.FTBchCode
                            WHERE DT.FTXshDocNo = '$tDocCode'
                        ) AS A ";
        $oQuery     = $this->db->query($tSQL);
        $aDataReturn    =  array(
            'raItem'        => $oQuery->result_array(),
        );
        return $aDataReturn;
    }

    //อัพเดทสถานะเอกสาร
    public function FSaMJR1UpdateStaApvInHDAndDT($ptDocNo){
        // $this->db->set('FTXshStaApv', 1);
        // $this->db->where_in('FTXshDocNo', $ptDocNo);
        // $this->db->update('TSVTJob1ReqHD');

        // $this->db->set('FTXsdStaPrcStk', 1);
        // $this->db->where_in('FTXshDocNo', $ptDocNo);
        // $this->db->update('TSVTJob1ReqDT');
    }

    public function FSaMChkStockBehideApv($paData)
    {
        $tBchCode   = $paData['FTBchCode'];
        $tDocCode   = $paData['FTXshDocNo'];
        /*$tSQL       = "
            SELECT CHKSTK.*
            FROM (
                SELECT HD.FTBchCode,HD.FTXshDocNo,HD.FTXshStaApv,DT.FNXshCountStkNo
                FROM TSVTJob1ReqHD HD WITH(NOLOCK)
                LEFT JOIN (
                    SELECT DT.FTBchCode,DT.FTXshDocNo,SUM(CASE WHEN DT.FTXsdStaPrcStk = 2 THEN 1 ELSE 0 END) AS FNXshCountStkNo
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    WHERE DT.FTBchCode		= " . $this->db->escape($tBchCode) . "
                    AND DT.FTXshDocNo		= " . $this->db->escape($tDocCode) . "
                    GROUP BY DT.FTBchCode,DT.FTXshDocNo
                ) DT ON HD.FTBchCode = DT.FTBchCode AND HD.FTXshDocNo = DT.FTXshDocNo
                WHERE HD.FTBchCode  = " . $this->db->escape($tBchCode) . " 
                AND HD.FTXshDocNo   = " . $this->db->escape($tDocCode) . "
            ) CHKSTK
            WHERE CHKSTK.FTXshStaApv = 1 AND CHKSTK.FNXshCountStkNo = 0 ";*/

        $tSQL       = " SELECT
                            TMP.FTPdtCode                                       AS ptPdtCode,
                            " . $this->db->escape($tBchCode) . "                AS ptBchCode,
                            WAH.FTWahCode				                        AS ptWahCode,
                            TMP.FCXsdQty                                        AS pcQty,
                            ''                                                  AS ptAgnCode,
                            TMP.FTPunCode                                       AS ptPunCode,
                            " . $this->db->escape($tDocCode) . "                AS ptDocNo,
                            ''                                                  AS ptRefDocNo
                        FROM TSVTJob1ReqDT          TMP WITH(NOLOCK)
                        INNER JOIN TCNMPdt          PDT WITH(NOLOCK) ON TMP.FTPdtCode = PDT.FTPdtCode
                        LEFT JOIN TCNMBranch        WAH WITH(NOLOCK) ON WAH.FTBchCode = TMP.FTBchCode
                        WHERE TMP.FTXshDocNo = " . $this->db->escape($tDocCode) . "
                        AND TMP.FTBchCode = " . $this->db->escape($tBchCode) . "
                        AND ISNULL(TMP.FTXsdStaPrcStk,'') = '2' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาว่าเอกสารนี้ใช้รถ อะไร
    public function FSaMJR1FindCarInDocument($ptDocumentCode)
    {
        $tSQL   = "  SELECT FTCarCode FROM TSVTJob1ReqHDCst WHERE FTXshDocNo = '$ptDocumentCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'aItems'       => $oQuery->result_array(),
                'tCode'        => '1',
                'tDesc'        => 'found data'
            );
        } else {
            $aResult    = array(
                'aItems'       => array(),
                'tCode'        => '800',
                'tDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

    //หาสินค้าใน เพื่อยิงไปที่ API
    public function FSaMJR1GetPdtInTmpForSendToAPI_New($paData){
        $tBchCode   = $paData['FTBchCode'];
        $tDocCode   = $paData['FTXshDocNo'];

        $tSQL       = " SELECT
                            TMP.FTPdtCode               AS ptPdtCode,
                            '$tBchCode'                 AS ptBchCode,
                            WAH.FTWahCode				AS ptWahCode,
                            TMP.FCXsdQty                AS pcQty,
                            ''                          AS ptAgnCode,
                            TMP.FTPunCode               AS ptPunCode,
                            '$tDocCode'                 AS ptDocNo,
                            ''                          AS ptRefDocNo
                        FROM TSVTJob1ReqDT          TMP WITH(NOLOCK)
                        INNER JOIN TCNMPdt          PDT WITH(NOLOCK) ON TMP.FTPdtCode = PDT.FTPdtCode
                        LEFT JOIN TCNMBranch        WAH WITH(NOLOCK) ON WAH.FTBchCode = TMP.FTBchCode
                        WHERE TMP.FTXshDocNo = '$tDocCode'
                          AND TMP.FTBchCode = '$tBchCode'
                          AND PDT.FTPdtStkControl = '1'
                          AND ISNULL(TMP.FTXsdStaPrcStk,'') != '1' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //เอาสินค้าตัวที่ไม่พอ มาหาชื่อ
    public function FSxMJR1FindTextNamePDTNoStock($ptTextCodePDT){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT FTPdtName , FTPdtCode from TCNMPdt_L where FNLngID = '$nLngID' AND FTPdtCode IN($ptTextCodePDT) ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาว่าสินค้า มีสินค้าลูกอะไรบ้าง
    public function FSaMJR1FindDTSet($paData){
        $tDocNo     = $paData['tDocNo'];
        $tBchCode   = $paData['tBchCode'];
        $tPDTCode   = $paData['tPDTCode'];
        $nSeqno     = $paData['nSeqno'];
        $tSession   = $this->session->userdata('tSesSessionID');

        $tSQL       = "SELECT
                        DTS.FTPdtCode       AS FTPdtCode,
                        DTS.FTXtdPdtName	AS FTPdtName,
                        DTS.FTPsvType       AS FTPsvType
                    FROM TSVTJRQDTSetTmp DTS WITH(NOLOCK)
                    WHERE (ISNULL(DTS.FTBchCode,'')   = " . $this->db->escape($tBchCode) . ")
                    AND (ISNULL(DTS.FTXthDocNo,'')  = " . $this->db->escape($tDocNo) . ")
                    AND (ISNULL(DTS.FTXthDocKey,'') = 'TSVTJob1ReqDT')
                    AND (ISNULL(DTS.FTSessionID,'') = " . $this->db->escape($tSession) . ")
                    AND FNXtdSeqNo = '$nSeqno'
                    AND FTSrnCode = '$tPDTCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }
}
