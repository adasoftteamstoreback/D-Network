<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class CarDetail_model extends CI_Model {
    
    //Search Car By ID
    public function FSaMCarDetailSearchByID($tCarCode){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "SELECT
                    CAR.FTCarOwner              AS rtCstCode,
                    CSTL.FTCstName              AS rtCstName,
                    CAR.FTCarCode               AS rtCarCode,
                    CAR.FTCarRegNo              AS rtCarRegNo,
                    CAR.FTCarEngineNo           AS rtCarEngineNo,
                    CAR.FTCarRegProvince        AS rtCarRegProvince,
                    CAR.FTCarVIDRef             AS rtCarVIDRef,
                    CAR.FTCarType               AS rtCarType,
                    CAR.FTCarStaRedLabel        AS rtCarStaRedLabel,
                    CAR.FTCarBrand              AS rtCarBrand,
                    CAR.FTCarModel              AS rtCarModel,
                    CAR.FTCarColor              AS rtCarColor,
                    CAR.FTCarGear               AS rtCarGear,
                    CAR.FTCarPowerType          AS rtCarPowerType,
                    CAR.FTCarEngineSize         AS rtCarEngineSize,
                    CAR.FTCarCategory           AS rtCarCategory,
                    CAR.FDCreateOn              AS rtFDCreateOn,
                    CAR.FDCarDOB                AS rtCarDOB,
                    CAR.FDCarOwnChg             AS rtCarOwnChg,
                    IMG.FTImgObj                AS rtImgObj,
                    T1.FTCaiName                AS rtCarTypeName,
                    T2.FTCaiName                AS rtCarBrandName,
                    T3.FTCaiName                AS rtCarModelName,
                    T4.FTCaiName                AS rtCarColorName,
                    T5.FTCaiName                AS rtCarGearName,
                    T6.FTCaiName                AS rtCarPowerTypeName,
                    T7.FTCaiName                AS rtCarEngineSizeName,
                    T8.FTCaiName                AS rtCarCategoryName,
                    PRVL.FTPvnName              AS rtPvnName
                 FROM [TSVMCar] CAR WITH (NOLOCK)
                 LEFT JOIN [TCNMImgObj] IMG WITH (NOLOCK) ON CAR.FTCarCode = IMG.FTImgRefID AND FTImgTable = 'TSVMCar'
                 LEFT JOIN [TCNMCst_L] CSTL WITH (NOLOCK) ON CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
                 LEFT JOIN [TCNMProvince_L] PRVL WITH (NOLOCK) ON CAR.FTCarRegProvince = PRVL.FTPvnCode AND PRVL.FNLngID = '$nLngID'
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T1 ON CAR.FTCarType = T1.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T2 ON CAR.FTCarBrand = T2.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T3 ON CAR.FTCarModel = T3.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T4 ON CAR.FTCarColor = T4.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T5 ON CAR.FTCarGear  = T5.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T6 ON CAR.FTCarPowerType = T6.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T7 ON CAR.FTCarEngineSize = T7.FTCaiCode
                 LEFT JOIN (SELECT CIF.FTCaiName,CIF.FTCaiCode FROM TSVMCarInfo_L CIF WITH (nolock)) T8 ON CAR.FTCarCategory = T8.FTCaiCode
                 WHERE CAR.FTCarCode = '$tCarCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }
}
