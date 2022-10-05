<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reimbursement_model extends CI_Model {

    //ชื่อกลุ่มเอกสาร
    public $tQaGrpCode = '00019';

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMRBMGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $nStaSite               = $paDataCondition['nStaSite'];
        // Advance Search

        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];
        //TSVTSalTwoHD
        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        SAL.FTAgnCode,
                                        SAL.FTBchCode,
                                        BCHL.FTBchName,
                                        SAL.FTXshDocNo,
                                        CONVERT(CHAR(10),SAL.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), SAL.FDXshDocDate,108) AS FTXshDocTime,
                                        DocRef.FTXshRefDocNo,
                                        CONVERT(CHAR(10),DocRef.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                        CONVERT(CHAR(5), DocRef.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                        SAL.FTXshStaDoc,
                                        SAL.FTXshStaApv,
                                        USR.FTUsrName as FTCreateBy,
                                        SAL.FDCreateOn,
                                        SAL.FTCstCode,
                                        CST.FTCstName,
                                        CAR.FTCarRegNo
                                    FROM TSVTSalTwoHD  SAL                WITH (NOLOCK)
                                    LEFT JOIN TSVTSalTwoHDDocRef  DocRef   WITH (NOLOCK) ON DocRef.FTXshDocNo  = SAL.FTXshDocNo   AND DocRef.FTXshRefType = 1
                                    LEFT JOIN TCNMBranch_L  BCHL            WITH (NOLOCK) ON SAL.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID        = $nLngID
                                    LEFT JOIN TCNMUser_L USR                WITH (NOLOCK) ON SAL.FTCreateBy    = USR.FTUsrCode     AND BCHL.FNLngID        = $nLngID
                                    LEFT JOIN TCNMCst_L CST                 WITH (NOLOCK) ON SAL.FTCstCode     = CST.FTCstCode     AND CST.FNLngID         = $nLngID
                                    LEFT JOIN TSVTSalTwoHDCst SALCST      WITH (NOLOCK) ON SAL.FTXshDocNo    = SALCST.FTXshDocNo
                                    LEFT JOIN TSVMCar CAR                  WITH (NOLOCK) ON SALCST.FTCarCode  = CAR.FTCarCode
                                WHERE SAL.FNXshDocType = $nStaSite
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND SAL.FTBchCode IN ($tBchCode)
            ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= "  AND ((SAL.FTXshDocNo LIKE '%$tSearchList%')
                        OR (BCHL.FTBchName LIKE '%$tSearchList%')
                        OR (CONVERT(CHAR(10),SAL.FDXshDocDate,103) LIKE '%$tSearchList%'))
                        OR (CST.FTCstName LIKE '%$tSearchList%')
                        OR (CAR.FTCarRegNo LIKE '%$tSearchList%')
                    ";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SAL.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (SAL.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((SAL.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (SAL.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND SAL.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(SAL.FTXshStaApv,'') = '' AND SAL.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND SAL.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND SAL.FTXshStaApv = '$tSearchStaApprove' OR SAL.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND SAL.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SAL.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SAL.FNXshStaDocAct = 0";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        //
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMRBMCountPageDocListAll($paDataCondition);
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
    public function FSnMRBMCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $nStaSite               = $paDataCondition['nStaSite'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "   SELECT COUNT (SAL.FTXshDocNo) AS counts
                        FROM TSVTSalTwoHD  SAL WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON SAL.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE SAL.FNXshDocType = $nStaSite
                    ";

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND SAL.FTBchCode = '$tUserLoginBchCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SAL.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SAL.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SAL.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (SAL.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((SAL.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (SAL.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND SAL.FTXshStaApv = '$tSearchStaApprove' OR SAL.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND SAL.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SAL.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SAL.FNXshStaDocAct = 0";
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

    //ดึงคำตอบมาแสดงแบบ Add
    public function FSaMRBMQaViewAnswer()
    {
        try {
            $tSQL = "SELECT
                            HD.FTQahDocNo ,
                            Grp.FTQsgCode ,
                            DT.FNQadSeqNo ,
                            Grp.FTQsgName ,
                            DT.FTQadName ,
                            DT.FTQadType ,
                            ANS.FNQasResuitSeq ,
                            ANS.FNQasResuitName
                        FROM TCNTQaHD HD
                        INNER JOIN TCNMQasSubGrp_L Grp ON Grp.FTQsgCode = HD.FTQsgCode AND Grp.FNLngID = 1
                        LEFT JOIN TCNTQaDT DT ON HD.FTQahDocNo = DT.FTQahDocNo
                        LEFT JOIN TCNTQaDTAns ANS ON DT.FTQahDocNo = ANS.FTQahDocNo AND DT.FNQadSeqNo = ANS.FNQadSeqNo
                        WHERE HD.FTQgpCode = '$this->tQaGrpCode'
                    ";
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aResult = array(
                'raItems' => $aList
            );
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }

    }

    // insert ข้อมูลลงตาราง  HD
    public function FSaMRBMQaAddUpdateHD($paDataAddHD, $paDataPrimaryKey)
    {
        try {
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableHD'];
            $tRmk     = $paDataAddHD['FTXshRmk'];
                // อัพเดท
                $this->db->set('FTXshRmk',$tRmk);
                $this->db->where_in('FTAgnCode',$tAgnCode);
                $this->db->where_in('FTBchCode',$tBchCode);
                $this->db->where_in('FTXshDocNo',$tDocNo);
                $this->db->update($tTable);

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert HD success'
            );
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMRBMGetDataHD($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT
                            HD.FTAgnCode,
                            AGN.FTAgnName,
                            HD.FTBchCode,
                            BCH.FTBchName,
                            HD.FTXshDocNo,
                            HD.FDXshDocDate,
                            CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXshDocTime,
                            HD.FTCstCode,
                            CSTL.FTCstName,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            CAR.FTCarRegNo,
                            CAR.FTCarEngineNo,
                            CAR.FTCarVIDRef,
                            CAR.FTCarCode,
                            T1.FTCaiName as FTCarType,
                            T2.FTCaiName as FTCarBrand,
                            T3.FTCaiName as FTCarModel,
                            T4.FTCaiName as FTCarColor,
                            T5.FTCaiName as FTCarGear,
                            T6.FTCaiName as FTCarPowerType,
                            T7.FTCaiName as FTCarEngineSize,
                            T8.FTCaiName as FTCarCategory,
                            HD.FTUsrCode,
                            USR.FTUsrName as SatSvBy,
                            HD.FTXshApvCode,
                            APVBY.FTUsrName as ApvBy,
                            HD.FTXshRmk,
                            HD.FTXshStaDoc,
                            HD.FTXshStaApv,
                            HD.FNXshStaDocAct,
                            HD.FTCreateBy,
                            USR.FTUsrName as FTNameCreateBy,
                            HD.FDCreateOn,
                            DOCRef.FTXshRefType as FTXshRefType1,
                            DOCRef.FTXshRefDocNo as FTXshRefDocNo1,
                            DOCRef.FDXshRefDocDate as FDXshRefDocDate1,
                            DOCRef3.FTXshRefType as FTXshRefType3,
                            DOCRef3.FTXshRefDocNo as FTXshRefDocNo3,
                            DOCRef3.FDXshRefDocDate as FDXshRefDocDate3,
                            HD.FDXshDocDate as FDXshStartChk,
                            CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FDXshStartChkTime,
                            HD.FDXshDocDate as FDXshFinishChk,
                            CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FDXshFinishChkTime,
                            Job1HD.FCXshCarMileage,
                            PVNL.FTPvnName,
                            POS.FTSpsName,
                            HD.FCXshGrand,
                            HD.FCXshTotal,
                            HD.FCXshDis-HD.FCXshChg as FCXshDis,
                            HD.FTXshDisChgTxt,
                            HD.FCXshTotalAfDisChgV,
                            HD.FCXshVat

                        FROM TSVTSalTwoHD HD WITH (NOLOCK)
                        LEFT JOIN TSVTSalTwoHDCst SALCST    WITH (NOLOCK) ON HD.FTXshDocNo    = SALCST.FTXshDocNo
                        LEFT JOIN TSVTSalTwoHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo AND DOCRef.FTXshRefType = 1
                        LEFT JOIN TSVTSalTwoHDDocRef DOCRef3 WITH (NOLOCK) ON DOCRef3.FTXshDocNo = HD.FTXshDocNo AND DOCRef3.FTXshRefType = 3
                        LEFT JOIN TSVTJob1ReqHDDocRef Job1Ref WITH (NOLOCK) ON Job1Ref.FTXshRefDocNo = HD.FTXshDocNo AND Job1Ref.FTXshRefType = 2
                        LEFT JOIN TSVTJob1ReqHD Job1HD        WITH (NOLOCK) ON Job1Ref.FTXshDocNo = Job1HD.FTXshDocNo
                        LEFT JOIN TCNMAgency_L AGN            WITH (NOLOCK) ON HD.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID ='$tLang'
                        LEFT JOIN TCNMBranch_L BCH            WITH (NOLOCK) ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID ='$tLang'
                        LEFT JOIN TCNMCst CST                 WITH (NOLOCK) ON HD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL              WITH (NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$tLang'
                        LEFT JOIN TSVMCar CAR                 WITH (NOLOCK) ON SALCST.FTCarCode = CAR.FTCarCode
                        LEFT JOIN TSVMCarInfo_L T1            WITH (NOLOCK) ON CAR.FTCarType   = T1.FTCaiCode AND T1.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T2            WITH (NOLOCK) ON CAR.FTCarBrand  = T2.FTCaiCode AND T2.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T3            WITH (NOLOCK) ON CAR.FTCarModel  = T3.FTCaiCode AND T3.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T4            WITH (NOLOCK) ON CAR.FTCarColor  = T4.FTCaiCode AND T4.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T5            WITH (NOLOCK) ON CAR.FTCarGear  = T5.FTCaiCode AND T5.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T6            WITH (NOLOCK) ON CAR.FTCarPowerType  = T6.FTCaiCode AND T6.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T7            WITH (NOLOCK) ON CAR.FTCarEngineSize  = T7.FTCaiCode AND T7.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T8            WITH (NOLOCK) ON CAR.FTCarCategory   = T8.FTCaiCode AND T8.FNLngID = '$tLang'
                        LEFT JOIN TCNMProvince_L PVNL         WITH (NOLOCK) ON PVNL.FTPvnCode   = CAR.FTCarRegProvince AND PVNL.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L USR              WITH (NOLOCK) ON USR.FTUsrCode = HD.FTUsrCode AND USR.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L CREBY            WITH (NOLOCK) ON CREBY.FTUsrCode = HD.FTCreateBy AND CREBY.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L APVBY            WITH (NOLOCK) ON APVBY.FTUsrCode = HD.FTXshApvCode AND APVBY.FNLngID = '$tLang'
                        LEFT JOIN TSVMPos_L  POS              WITH (NOLOCK) ON POS.FTSpsCode = HD.FTXshToPos AND POS.FNLngID = '$tLang'
                        WHERE HD.FTAgnCode = '$ptAgnCode' AND HD.FTBchCode = '$ptBchCode'  AND HD.FTXshDocNo = '$ptDocNo'
                    ";

            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMRBMGetDataSumVat($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT
                        SUM(DT.FCXsdVat) as FCXsdVat
                    FROM TSVTSalTwoDT DT
                    WHERE DT.FTXshDocNo = '$ptDocNo'";

            if ($ptAgnCode != '') {
                $tSQL .= "AND DT.FTAgnCode = '$ptAgnCode' ";
            }

            if ($ptBchCode != '') {
                $tSQL .= "AND DT.FTBchCode = '$ptBchCode' ";
            }
            //echo $tSQL;
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMRBMGetDataVatRate($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT
                        FCXsdVatRate,
                        SUM(FCXsdVat) as FCXsdVat
                    FROM TSVTSalTwoDT DT
                    WHERE DT.FTXshDocNo = '$ptDocNo'";

            if ($ptAgnCode != '') {
                $tSQL .= "AND DT.FTAgnCode = '$ptAgnCode' ";
            }

            if ($ptBchCode != '') {
                $tSQL .= "AND DT.FTBchCode = '$ptBchCode' ";
            }
            $tSQL .= "GROUP BY FCXsdVatRate";
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMRBMGetDataDT($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = " SELECT A.*,
                            COUNT(FNXsdSeqNo) OVER (PARTITION BY A.FNXsdSeqNo) AS PARTITIONBYDOC,
                            ROW_NUMBER() OVER (PARTITION BY A.FNXsdSeqNo ORDER BY A.FNXsdSeqNo ASC) AS FNPstSeqNo
                        FROM (
                        SELECT
                        DISTINCT
                        DT.FNXsdSeqNo ,
                        DT.FTPdtCode ,
                        DT.FTXsdPdtName ,
                        DT.FTPunName ,
                        DT.FCXsdQty,
                        DT.FCXsdSalePrice,
                        DT.FCXsdDis,
                        DT.FCXsdNetAfHD,
                        DT.FTPdtStaSet AS FTPdtSetOrSN,
                        0 as FTPsvType,
                        DT.FCXsdVatRate,
                        DT.FCXsdVat,
                        '1' AS PDTSetOrPDT
                        FROM TSVTSalTwoDT DT
                        WHERE 1=1
                        AND DT.FTXshDocNo = '$ptDocNo'
                    ";
                    if ($ptAgnCode != '') {
                        $tSQL .= "AND DT.FTAgnCode = '$ptAgnCode' ";
                    }

                    if ($ptBchCode != '') {
                        $tSQL .= "AND DT.FTBchCode = '$ptBchCode' ";
                    }
            $tSQL .= " UNION ALL ";
            $tSQL .= "SELECT
                            DTSet.FNXsdSeqNo ,
                            DTSet.FTPdtCode ,
                            DTSet.FTXsdPdtName ,
                            DTSet.FTPunCode as FTPunName,
                            NULL as FCXsdQty,
                            NULL as FCXsdSalePrice,
                            NULL as FCXsdDis,
                            NULL as FCXsdNetAfHD,
                            0 AS FTPdtSetOrSN,
                            DTSet.FTPsvType,
                            NULL as FCXsdVatRate,
                            NULL as FCXsdVat,
                            '0' AS PDTSetOrPDT
                        FROM TSVTSalTwoDTSet DTSet
                        WHERE 1=1
                        AND DTSet.FTXshDocNo = '$ptDocNo'
                    ";
                    if ($ptAgnCode != '') {
                        $tSQL .= "AND DTSet.FTAgnCode = '$ptAgnCode' ";
                    }

                    if ($ptBchCode != '') {
                        $tSQL .= "AND DTSet.FTBchCode = '$ptBchCode' ";
                    }

            $tSQL .= ") AS A ORDER BY A.FNXsdSeqNo ASC , A.PDTSetOrPDT DESC , A.FTPdtCode DESC";
            //TSVTSalTwoHD

            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMRBMGetAllDocRef($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT FTXshRefDocNo, FTXshRefKey, FTXshRefType, FDXshRefDocDate FROM TSVTSalTwoHDDocRef HDR WHERE HDR.FTXshDocNo = '$ptDocNo'";
            if ($ptAgnCode != '') {
                $tSQL .= "AND HDR.FTAgnCode = '$ptAgnCode' ";
            }

            if ($ptBchCode != '') {
                $tSQL .= "AND HDR.FTBchCode = '$ptBchCode' ";
            }
            $tSQL .= "ORDER BY FTXshRefType ASC";
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    //อนุมัตเอกสาร
    public function FSaMRBMApproveDocument($paDataUpdate){
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');

            $this->db->set('FDLastUpdOn',$dLastUpdOn);
            $this->db->set('FTLastUpdBy',$tLastUpdBy);
            $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
            $this->db->set('FTXshApvCode',$paDataUpdate['FTXshApvCode']);
            $this->db->where_in('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTSalTwoHD');

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
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aStatus;
    }

    //ยกเลิกเอกสาร
    public function FSaMRBMCancelDocument($paDataUpdate, $aDataWhereDocRef){
        try {
            $this->db->set('FTXshStaDoc' , 3);
            $this->db->where_in('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTSalTwoHD');

            //ลบ TSVTSalTwoHDDocRef
            $this->db->where_in('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTSalTwoHDDocRef');

            //ลบ TSVTJob1ReqHDDocRef
            $this->db->where_in('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->where_in('FTXshRefType',2);
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob1ReqHDDocRef');

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
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',-
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aStatus;
    }

    //ลบข้อมูล
    public function FSnMRBMDelDocument($paDataDoc){
        try {
            $tDataDocNo = $paDataDoc['tDataDocNo'];
            $tJOBAgnCode = $paDataDoc['tJOBAgnCode'];
            $JOBBchCode = $paDataDoc['tJOBBchCode'];
            $JOBDocRefCode = $paDataDoc['tJOBDocRefCode'];
            $nRefDocType1 = 1;
            $nRefDocType2 = 2;

            $this->db->trans_begin();

            // HD
            $this->db->where_in('FTAgnCode',$tJOBAgnCode);
            $this->db->where_in('FTBchCode',$JOBBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTSalTwoHD');

            // HD Doc Ref
           $this->db->where_in('FTAgnCode',$tJOBAgnCode);
           $this->db->where_in('FTBchCode',$JOBBchCode);
           $this->db->where_in('FTXshDocNo',$tDataDocNo);
           $this->db->where_in('FTXshRefType',$nRefDocType1);
           $this->db->where_in('FTXshRefDocNo',$JOBDocRefCode);
           $this->db->delete('TSVTSalTwoHDDocRef');

           // SALHD Doc Ref
           $this->db->where_in('FTAgnCode',$tJOBAgnCode);
           $this->db->where_in('FTBchCode',$JOBBchCode);
           $this->db->where_in('FTXshDocNo',$JOBDocRefCode);
           $this->db->where_in('FTXshRefType',$nRefDocType2);
           $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
           $this->db->delete('TSVTJob1ReqHDDocRef');

            // DT
            $this->db->where_in('FTAgnCode',$tJOBAgnCode);
            $this->db->where_in('FTBchCode',$JOBBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTSalTwoDT');

            // DT Ans
            $this->db->where_in('FTAgnCode',$tJOBAgnCode);
            $this->db->where_in('FTBchCode',$JOBBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTSalTwoDTSet');

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
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aStaDelDoc;
    }

}

/* End of file inspectionafterservice.php */
