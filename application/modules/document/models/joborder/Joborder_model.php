<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Joborder_model extends CI_Model {

    //ชื่อกลุ่มเอกสาร
    public $tQaGrpCode = '00019';

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMJOBGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "SELECT TOP ". get_cookie('nShowRecordInPageList')." c.* FROM(
                            SELECT  --ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,
                            * FROM
                                (   SELECT DISTINCT
                                        JOB2.FTAgnCode,
                                        JOB2.FTBchCode,
                                        BCHL.FTBchName,
                                        JOB2.FTXshDocNo,
                                        CONVERT(CHAR(10),JOB2.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), JOB2.FDXshDocDate,108) AS FTXshDocTime,
                                        DocRef.FTXshRefDocNo,
                                        CONVERT(CHAR(10),DocRef.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                        CONVERT(CHAR(5), DocRef.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                        JOB2.FTXshStaDoc,
                                        JOB2.FTXshStaApv,
                                        USR.FTUsrName as FTCreateBy,
                                        JOB2.FDCreateOn,
                                        JOB2.FTCstCode,
                                        CST.FTCstName,
                                        CAR.FTCarRegNo
                                    FROM TSVTJob2OrdHD  JOB2                WITH (NOLOCK)
                                    LEFT JOIN TSVTJob2OrdHDDocRef  DocRef   WITH (NOLOCK) ON DocRef.FTXshDocNo  = JOB2.FTXshDocNo   AND DocRef.FTXshRefType = 1
                                    LEFT JOIN TCNMBranch_L  BCHL            WITH (NOLOCK) ON JOB2.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID        = $nLngID
                                    LEFT JOIN TCNMUser_L USR                WITH (NOLOCK) ON JOB2.FTCreateBy    = USR.FTUsrCode     AND USR.FNLngID         = $nLngID
                                    LEFT JOIN TCNMCst_L CST                 WITH (NOLOCK) ON JOB2.FTCstCode     = CST.FTCstCode     AND CST.FNLngID         = $nLngID
                                    LEFT JOIN TSVTJob2OrdHDCst JOB2CST      WITH (NOLOCK) ON JOB2.FTXshDocNo    = JOB2CST.FTXshDocNo
                                    LEFT JOIN TSVMCar CAR                  WITH (NOLOCK) ON JOB2CST.FTCarCode  = CAR.FTCarCode
                                WHERE JOB2.FTXshDocNo != ''
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND JOB2.FTBchCode IN ($tBchCode)
            ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= "  AND ((JOB2.FTXshDocNo LIKE '%$tSearchList%') 
                        OR (BCHL.FTBchName LIKE '%$tSearchList%') 
                        OR (CONVERT(CHAR(10),JOB2.FDXshDocDate,103) LIKE '%$tSearchList%'))
                        OR (CST.FTCstName LIKE '%$tSearchList%') 
                        OR (CAR.FTCarRegNo LIKE '%$tSearchList%')
                    ";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((JOB2.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (JOB2.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((JOB2.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (JOB2.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND JOB2.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(JOB2.FTXshStaApv,'') = '' AND JOB2.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND JOB2.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND JOB2.FTXshStaApv = '$tSearchStaApprove' OR JOB2.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND JOB2.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND JOB2.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND JOB2.FNXshStaDocAct = 0";
            }
        }

        // $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL   .=  ") Base) AS c ORDER BY c.FDCreateOn DESC ";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = 0;
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
        unset($aRowLen);
        unset($nLngID);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom);
        unset($tSearchBchCodeTo);
        unset($tSearchDocDateFrom);
        unset($tSearchDocDateTo);
        unset($tSearchStaDoc);
        unset($tSearchStaDocAct);
        unset($tSQL);
        return $aResult;
    }

    // Paginations
    public function FSnMJOBCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];
    
        $tSQL   =   "   SELECT COUNT (JOB2.FTXshDocNo) AS counts
                        FROM TSVTJob2OrdHD  JOB2 WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON JOB2.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE JOB2.FTXshDocNo != ''
                    ";
    
        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND JOB2.FTBchCode = '$tUserLoginBchCode' ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((JOB2.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),JOB2.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((JOB2.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (JOB2.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((JOB2.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (JOB2.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND JOB2.FTXshStaApv = '$tSearchStaApprove' OR JOB2.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND JOB2.FTXshStaApv = '$tSearchStaApprove'";
            }
        }
    
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND JOB2.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND JOB2.FNXshStaDocAct = 0";
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
    public function FSaMJOBQaViewAnswer()
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
    public function FSaMJOBQaAddUpdateHD($paDataAddHD, $paDataPrimaryKey)
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

    public function FSaMJOBGetDataHD($ptAgnCode,$ptBchCode,$ptDocNo)
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
                            CREBY.FTUsrName as FTNameCreateBy,
                            HD.FDCreateOn,
                            DOCRef.FTXshRefType as FTXshRefType1,
                            DOCRef.FTXshRefDocNo as FTXshRefDocNo1,
                            DOCRef.FDXshRefDocDate as FDXshRefDocDate1,
                            DOCRef3.FTXshRefType as FTXshRefType3,
                            DOCRef3.FTXshRefDocNo as FTXshRefDocNo3,
                            DOCRef3.FDXshRefDocDate as FDXshRefDocDate3,
                            HD.FDXshTimeStart as FDXshStartChk,
                            CONVERT(CHAR(5), HD.FDXshTimeStart,108) AS FDXshStartChkTime,
                            HD.FDXshTimeFinish as FDXshFinishChk,
                            CONVERT(CHAR(5), HD.FDXshTimeFinish,108) AS FDXshFinishChkTime,
                            HD.FCXshCarMileage,
                            PVNL.FTPvnName,
                            POS.FTSpsName,
                            HD.FCXshGrand,
                            HD.FCXshTotal,
                            HD.FCXshDis-HD.FCXshChg as FCXshDis,
                            HD.FTXshDisChgTxt,
                            HD.FCXshTotalAfDisChgV,
                            HD.FCXshVat,
                            HD.FTXshCarChkRmk1,
                            HD.FTXshCarChkRmk2

                        FROM TSVTJob2OrdHD HD WITH (NOLOCK)
                        LEFT JOIN TSVTJob2OrdHDCst JOB2CST    WITH (NOLOCK) ON HD.FTXshDocNo    = JOB2CST.FTXshDocNo
                        LEFT JOIN TSVTJob2OrdHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo AND DOCRef.FTXshRefType = '1'
                        LEFT JOIN TSVTJob2OrdHDDocRef DOCRef3 WITH (NOLOCK) ON DOCRef3.FTXshDocNo = HD.FTXshDocNo AND DOCRef3.FTXshRefType = '3'
                        LEFT JOIN TSVTJob1ReqHDDocRef Job1Ref WITH (NOLOCK) ON Job1Ref.FTXshRefDocNo = HD.FTXshDocNo AND Job1Ref.FTXshRefType = '2'
                        LEFT JOIN TSVTJob1ReqHD Job1HD        WITH (NOLOCK) ON Job1Ref.FTXshDocNo = Job1HD.FTXshDocNo
                        LEFT JOIN TCNMAgency_L AGN            WITH (NOLOCK) ON HD.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID ='$tLang'
                        LEFT JOIN TCNMBranch_L BCH            WITH (NOLOCK) ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID ='$tLang'
                        LEFT JOIN TCNMCst CST                 WITH (NOLOCK) ON HD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL              WITH (NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$tLang'
                        LEFT JOIN TSVMCar CAR                 WITH (NOLOCK) ON JOB2CST.FTCarCode = CAR.FTCarCode
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

    public function FSaMJOBGetDataSumVat($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT 
                        SUM(DT.FCXsdVat) as FCXsdVat
                    FROM TSVTJob2OrdDT DT 
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

    public function FSaMJOBGetDataVatRate($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT
                        FCXsdVatRate,
                        SUM(FCXsdVat) as FCXsdVat
                    FROM TSVTJob2OrdDT DT 
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
    
    public function FSaMJOBGetDataDT($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = " SELECT A.*,
                            ROW_NUMBER ( ) OVER ( PARTITION BY A.FNXsdSeqNo ORDER BY A.FNXsdSeqNo DESC ) AS ROW_ID,
                            COUNT(FNXsdSeqNo) OVER (PARTITION BY A.FNXsdSeqNo) AS PARTITIONBYDOC
                        FROM (
                        SELECT 
                        DISTINCT
                        DT.FNXsdSeqNo ,
                        DT.FTPdtCode ,
                        DT.FTXsdPdtName ,
                        DT.FTPunName , 
                        DT.FCXsdQty,
                        DT.FCXsdSetPrice AS FCXsdSalePrice,
                        DT.FCXsdDis,
                        DT.FCXsdNetAfHD,
                        DT.FTPdtStaSet as FTPsvType,
                        DT.FCXsdVatRate,
                        DT.FCXsdVat,
                        '1' AS PDTSetOrPDT,
                        DT.FTXsdStaApvTask
                        FROM TSVTJob2OrdDT DT
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
                            DTSet.FTPunCode AS FTPunName, 
                            NULL AS FCXsdQty,
                            NULL AS FCXsdSalePrice,
                            NULL AS FCXsdDis,
                            NULL AS FCXsdNetAfHD,
                            DTSet.FTPsvType,
                            NULL AS FCXsdVatRate,
                            NULL AS FCXsdVat,
                            '0' AS PDTSetOrPDT,
                            NULL AS FTXsdStaApvTask
                        FROM TSVTJob2OrdDTSet DTSet
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

    public function FSaMJOBGetAllDocRef($ptAgnCode,$ptBchCode,$ptDocNo)
    {
        try {
            $tSQL = "SELECT FTXshRefDocNo, FTXshRefKey, FDXshRefDocDate, FTXshRefType FROM TSVTJob2OrdHDDocRef HDR WHERE HDR.FTXshDocNo = '$ptDocNo'";
            if ($ptAgnCode != '') {
                $tSQL .= "AND HDR.FTAgnCode = '$ptAgnCode' ";
            }

            if ($ptBchCode != '') {
                $tSQL .= "AND HDR.FTBchCode = '$ptBchCode' ";
            }
            $tSQL .= "ORDER BY FTXshRefKey DESC";
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
    public function FSaMJOBApproveDocument($paDataUpdate){
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
            $this->db->update('TSVTJob2OrdHD');
    
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
    public function FSaMJOBCancelDocument($paDataUpdate, $aDataWhereDocRef){
        try {
            $this->db->set('FTXshStaDoc' , 3);
            $this->db->where_in('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob2OrdHD');

            //ลบ TSVTJob2OrdHDDocRef
            $this->db->where('FTXshDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob2OrdHDDocRef');

            //ลบ TSVTJob1ReqHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob1ReqHDDocRef');

            //ลบ TSVTJob3ChkHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob3ChkHDDocRef');

            //ลบ TSVTJob4ApvHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob4ApvHDDocRef');

            //ลบ TSVTJob5ScoreHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob5ScoreHDDocRef');

            //ลบ TARTSqHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TARTSqHDDocRef');

            //ลบ TSVTSalTwoHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTSalTwoHDDocRef');

            //ลบ TPSTSalHDDocRef
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TPSTSalHDDocRef');

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
    public function FSnMJOBDelDocument($paDataDoc){
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
            $this->db->delete('TSVTJob2OrdHD');

            // HD Doc Ref
           $this->db->where_in('FTAgnCode',$tJOBAgnCode);
           $this->db->where_in('FTBchCode',$JOBBchCode);
           $this->db->where_in('FTXshDocNo',$tDataDocNo);
           $this->db->where_in('FTXshRefType',$nRefDocType1);
           $this->db->where_in('FTXshRefDocNo',$JOBDocRefCode);
           $this->db->delete('TSVTJob2OrdHDDocRef');

           // Job2HD Doc Ref
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
            $this->db->delete('TSVTJob2OrdDT');

            // DT Ans
            $this->db->where_in('FTAgnCode',$tJOBAgnCode);
            $this->db->where_in('FTBchCode',$JOBBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob2OrdDTSet');

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

    //หาว่าเอกสารนี้ ใบขายไปหรือยัง
    public function FSxMJOBFindDocNoUse($ptDocNo){
        $tSQL       = " SELECT
                           TOP 1 FTXshRefKey FROM TSVTJob2OrdHDDocRef WHERE FTXshRefKey IN ('ABB','SalTwo')
                        AND FTXshDocNo      = '$ptDocNo' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }


}
