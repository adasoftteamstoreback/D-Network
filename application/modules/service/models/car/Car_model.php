<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Car_model extends CI_Model {
    
    //Functionality : Search Car By ID
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCARSearchByID($paData){
        $tCarCode   = $paData['FTCarCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT
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
                T1.FTCaiName                AS rtCarTypeName,
                T2.FTCaiName                AS rtCarBrandName,
                T3.FTCaiName                AS rtCarModelName,
                T4.FTCaiName                AS rtCarColorName,
                T5.FTCaiName                AS rtCarGearName,
                T6.FTCaiName                AS rtCarPowerTypeName,
                T7.FTCaiName                AS rtCarEngineSizeName,
                T8.FTCaiName                AS rtCarCategoryName,
                IMG.FTImgObj                AS rtImgObj,
                PRVL.FTPvnName              AS rtPvnName,
                REFBCH.FTCbrBchCode         AS rtRefBCHCode,
                REFBCH.FTCbrBchName         AS rtRefBCHName 
            FROM TSVMCar CAR WITH (NOLOCK)
            LEFT JOIN TCNMCst_L			CSTL	WITH (NOLOCK)	ON CAR.FTCarOwner   = CSTL.FTCstCode AND CSTL.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMProvince_L	PRVL	WITH (NOLOCK)	ON CAR.FTCarRegProvince	= PRVL.FTPvnCode AND PRVL.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TSVMCarInfo_L		T1		WITH (NOLOCK)	ON CAR.FTCarType	= T1.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T2		WITH (NOLOCK)	ON CAR.FTCarBrand	= T2.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T3		WITH (NOLOCK)	ON CAR.FTCarModel	= T3.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T4		WITH (NOLOCK)	ON CAR.FTCarColor	= T4.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T5		WITH (NOLOCK)	ON CAR.FTCarGear	= T5.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T6		WITH (NOLOCK)	ON CAR.FTCarPowerType	= T6.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T7		WITH (NOLOCK)	ON CAR.FTCarEngineSize	= T7.FTCaiCode
            LEFT JOIN TSVMCarInfo_L		T8		WITH (NOLOCK)	ON CAR.FTCarCategory	= T8.FTCaiCode
            LEFT JOIN TCNMCstBch		REFBCH	WITH (NOLOCK)	ON CAR.FTCbrBchCode = REFBCH.FTCbrBchCode AND CAR.FTCarOwner = REFBCH.FTCstCode
            LEFT JOIN TCNMImgObj		IMG		WITH (NOLOCK)	ON CAR.FTCarCode	= IMG.FTImgRefID AND FTImgTable = 'TSVMCar'
            WHERE CAR.FTCarCode = ".$this->db->escape($tCarCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
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
        unset($tCarCode,$nLngID,$tSQL,$oQuery,$aDetail,$paData);
        return $aResult;
    }
    
    //Functionality : list Car
    //Parameters : function parameters
    //Creator :  09/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCARList($paData){
        $nLngID         = $paData['FNLngID'];
        $tSesAgnCode    = $paData['tSesAgnCode'];
        $tSQLFilter     = '';
        // Check Agency 
        if($tSesAgnCode != ''){
            $tSQLFilter .= "AND CAR.FTAgnCode = '$tSesAgnCode' ";
        }
        // Chekc Filter Search
        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQLFilter .= " AND (CAR.FTCarCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQLFilter .= " OR CAR.FTCarRegNo  COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQLFilter .= " OR CSTL.FTCstName  COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }
        // Chekc Search Type
        $aSearchType    = $paData['aSearchType'];
        if(count($aSearchType) > 0 ){
            foreach($aSearchType as $tKey => $tValue){
                $tSQLFilter .=  " AND CAR.$tKey = '$tValue' ";
            }
        }
        $tSQL       = " 
            SELECT 
                DATACAR.*,
                ISNULL(JOB1.FNCarCount,0)		AS rtFTUsedCodeReqHD,
                ISNULL(BOOKING.FNCountBook,0)	AS rtFTUsedCodeBookHD
            FROM (
                SELECT TOP ". get_cookie('nShowRecordInPageList')."
                    CAR.FTCarOwner		AS rtCstCode,
                    (CASE WHEN CSTL.FTCstName IS NULL THEN '-' ELSE CSTL.FTCstName END) AS rtCstName,
                    CAR.FTCarCode		AS rtCarCode,
                    CAR.FTCarRegNo		AS rtCarRegNo,
                    CAR.FTCarEngineNo	AS rtCarEngineNo,
                    CAR.FTCarVIDRef		AS rtCarVIDRef,
                    CAR.FTCarCategory	AS rtCarCategory,
                    CAR.FTCarBrand		AS rtCarBrand,
                    (CASE WHEN CBD.FTCaiName IS NULL THEN '-' ELSE CBD.FTCaiName END) AS rtCarBrandName,
                    CAR.FTCarModel,
                    (CASE WHEN CMD.FTCaiName IS NULL THEN '-' ELSE CMD.FTCaiName END) AS rtCarModelName,
                    CAR.FTCarColor,
                    (CASE WHEN CCL.FTCaiName IS NULL THEN '-' ELSE CCL.FTCaiName END) AS rtCarColorName,
                    CAR.FDCreateOn	AS rtFDCreateOn,
                    IMG.FTImgObj	AS rtImgObj
                FROM TSVMCar CAR WITH(NOLOCK)
                LEFT JOIN TCNMCst_L		CSTL WITH(NOLOCK)	ON CAR.FTCarOwner	= CSTL.FTCstCode AND CSTL.FNLngID	= ".$this->db->escape($nLngID)."
                LEFT JOIN TSVMCarInfo_L CBD	WITH(NOLOCK)	ON CAR.FTCarBrand	= CBD.FTCaiCode	AND CBD.FNLngID		= ".$this->db->escape($nLngID)."
                LEFT JOIN TSVMCarInfo_L CMD	WITH(NOLOCK)	ON CAR.FTCarModel	= CMD.FTCaiCode	AND CMD.FNLngID		= ".$this->db->escape($nLngID)."
                LEFT JOIN TSVMCarInfo_L CCL	WITH(NOLOCK)	ON CAR.FTCarColor	= CCL.FTCaiCode	AND CCL.FNLngID		= ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMImgObj	IMG WITH(NOLOCK)	ON CAR.FTCarCode	= IMG.FTImgRefID AND FTImgTable		= 'TSVMCar'
                WHERE 1=1
                ".$tSQLFilter."
                ORDER BY CAR.FDCreateOn DESC
            ) AS DATACAR 
            LEFT JOIN (
                SELECT FTCarCode,COUNT(FTCarCode) AS FNCarCount FROM TSVTJob1ReqHDCst WITH(NOLOCK)	WHERE FTCarCode <> '' GROUP BY FTCarCode
            ) AS JOB1 ON DATACAR.rtCarCode = JOB1.FTCarCode
            LEFT JOIN (
                SELECT FTXshCstRef2,COUNT(FTXshCstRef2) AS FNCountBook FROM TSVTBookHD WITH(NOLOCK) WHERE FTXshCstRef2 <> '' GROUP BY FTXshCstRef2
            ) AS BOOKING ON DATACAR.rtCarCode = BOOKING.FTXshCstRef2
            ORDER BY DATACAR.rtFDCreateOn DESC
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems' => $aList,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        return $aResult;
    }
    
    //Functionality : Checkduplicate Primary
    //Parameters : function parameters
    //Creator : 10/06/2021 Off
    //Last Modified : -
    //Return : Data Count Duplicate
    //Return Type : Object
    public function FSoMCARCheckDuplicate($ptCarCode){
        $tSQL   = "
            SELECT COUNT(FTCarCode)AS counts
            FROM TSVMCar WITH (NOLOCK)
            WHERE FTCarCode = ".$this->db->escape($ptCarCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oReturn = $oQuery->result();
        }else{
            $oReturn = false;
        }
        unset($ptCarCode,$oQuery);
        return $oReturn;
    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 10/06/2021 Off
    //Last Modified : 
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMCARAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTCarOwner'   , $paData['FTCstCode']);
            $this->db->set('FTCarRegProvince'   , $paData['FTCarRegProvince']);
            $this->db->set('FTCarStaRedLabel'   , $paData['FTCarStaRedLabel']);
            $this->db->set('FTCarRegNo'  , $paData['FTCarRegNo']);
            $this->db->set('FTCarEngineNo' , $paData['FTCarEngineNo']);
            $this->db->set('FTCarType' , $paData['FTCarType']);
            $this->db->set('FTCarVIDRef' , $paData['FTCarVIDRef']);
            $this->db->set('FTCarBrand' , $paData['FTCarBrand']);
            $this->db->set('FTCarModel' , $paData['FTCarModel']);
            $this->db->set('FTCarColor' , $paData['FTCarColor']);
            $this->db->set('FTCarGear' , $paData['FTCarGear']);
            $this->db->set('FTCarPowerType' , $paData['FTCarPowerType']);
            $this->db->set('FTCarEngineSize' , $paData['FTCarEngineSize']);
            $this->db->set('FTCarCategory' , $paData['FTCarCategory']);
            $this->db->set('FDCarDOB' , $paData['FDCarDOB']);
            $this->db->set('FDCarOwnChg' , $paData['FDCarOwnChg']);
            $this->db->set('FTCbrBchCode', $paData['FTCbrBchCode']);
            $this->db->where('FTCarCode', $paData['FTCarCode']);
            $this->db->update('TSVMCar');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TSVMCar',array(
                    'FTCarCode'         => $paData['FTCarCode'],
                    'FTCarOwner'        => $paData['FTCstCode'],
                    'FTCarRegProvince'  => $paData['FTCarRegProvince'],
                    'FTCarStaRedLabel'  => $paData['FTCarStaRedLabel'],
                    'FTCarRegNo'        => $paData['FTCarRegNo'],
                    'FTCarEngineNo'     => $paData['FTCarEngineNo'],
                    'FTCarVIDRef'       => $paData['FTCarVIDRef'],
                    'FTCarType'         => $paData['FTCarType'],
                    'FTCarBrand'        => $paData['FTCarBrand'],
                    'FTCarModel'        => $paData['FTCarModel'],
                    'FTCarColor'        => $paData['FTCarColor'],
                    'FTCarGear'         => $paData['FTCarGear'],
                    'FTCarCategory'     => $paData['FTCarCategory'],
                    'FTCarPowerType'    => $paData['FTCarPowerType'],
                    'FTCarEngineSize'   => $paData['FTCarEngineSize'],
                    'FDCarDOB'          => $paData['FDCarDOB'],
                    'FDCarOwnChg'       => $paData['FDCarOwnChg'],
                    //เวลาบันทึกล่าสุด
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    //เวลาบันทึกครั้งแรก
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTCreateBy'        => $paData['FTCreateBy'],
                    'FTCbrBchCode'      => $paData['FTCbrBchCode']
                ));
                if($this->db->affected_rows() > 0 ){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Delete Calendar
    //Parameters : function parameters
    //Creator : 11/06/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMCARDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTCarCode', $paData['FTCarCode']);
            $this->db->delete('TSVMCar');
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : list Car History
    //Parameters : function parameters
    //Creator :  10/06/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMCARHistoryList($paData){
        try{
            $aAdvanceSearch = $paData['aAdvanceSearch'];
            // Advance Search
            $tStatusdoc     = $aAdvanceSearch['tStatus'];
            $dDocdate       = $aAdvanceSearch['dDocdate'];
            $dJointdate     = $aAdvanceSearch['dJointdate'];
            $tBchFrom       = $aAdvanceSearch['tBchFrom'];
            $tBchTo         = $aAdvanceSearch['tBchTo'];
            $tSearchList    = $paData['tSearchAll'];
            $tCarCode       = $paData['nPosCode'];
            $nLngID         = $paData['FNLngID'];
            $tSQL           = "
                SELECT TOP ". get_cookie('nShowRecordInPageList')."
                    CSTF.FTBchCode              AS rtBchCode,
                    BRL.FTBchName               AS rtBchName,
                    CSTF.FTCarCode              AS rtCarCode,
                    PDT_L.FTPdtCode             AS rtPdtCode,
                    PDT_L.FTPdtName             AS rtPdtName,
                    CSTF.FTFlwDocRef            AS rtFlwDocRef,
                    CASE WHEN OPDT_L.FTPdtCode IS NULL THEN '-' ELSE OPDT_L.FTPdtCode END AS rtOrgPdtCode,
                    CASE WHEN OPDT_L.FTPdtName IS NULL THEN '-' ELSE OPDT_L.FTPdtName END AS rtOrgPdtName,
                    CASE WHEN CSTF.FTFlwStaBook = '1' THEN 'รอนัดหมาย' WHEN CSTF.FTFlwStaBook = '2' THEN 'นัดหมายแล้ว' WHEN CSTF.FTFlwStaBook = '3' THEN 'ยืนยันแล้ว' ELSE 'จบรายการ' END AS rtFlwStaBook,
                    CONVERT(CHAR(10),CSTF.FDFlwDateForcast,103)     AS rtFlwDateForcast,
                    CONVERT(CHAR(10),CSTF.FDFlwLastDate,103)        AS rtFlwLastDate,
                    CONVERT(CHAR(10),JORD.FDXshDocDate,103)         AS rtXshDocDate
                FROM [TSVTCstFollow] CSTF WITH (NOLOCK)
                LEFT JOIN [TCNMBranch_L] BRL WITH (NOLOCK) ON CSTF.FTBchCode = BRL.FTBchCode AND BRL.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN [TCNMPdt_L] PDT_L WITH(NOLOCK) ON CSTF.FTPdtCode = PDT_L.FTPdtCode   AND PDT_L.FNLngID =  ".$this->db->escape($nLngID)."
                LEFT JOIN [TCNMPdt_L] OPDT_L WITH(NOLOCK) ON CSTF.FTPdtCodeOrg = OPDT_L.FTPdtCode   AND OPDT_L.FNLngID =  ".$this->db->escape($nLngID)."
                LEFT JOIN [TSVTJob2OrdHD] JORD WITH(NOLOCK) ON JORD.FTXshDocNo = CSTF.FTFlwDocRef
                WHERE CSTF.FTCarCode  = ".$this->db->escape($tCarCode)."
            ";
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL   .= " AND (BRL.FTBchName    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR PDT_L.FTPdtCode    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR PDT_L.FTPdtName    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR OPDT_L.FTPdtCode   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR OPDT_L.FTPdtName   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR CSTF.FTFlwDocRef   LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
            }
            // ค้นหาจากสาขา - ถึงสาขา
            if(!empty($tBchFrom) && !empty($tBchTo)){
                $tSQL   .= " AND ((CSTF.FTBchCode BETWEEN '$tBchFrom' AND '$tBchTo') OR (CSTF.FTBchCode BETWEEN '$tBchTo' AND '$tBchFrom'))";
            }
            // ค้นหาสถานะเอกสาร
            if (!empty($tStatusdoc) && ($tStatusdoc != "0")) {
                if ($tStatusdoc != 0) {
                    $tSQL .= " AND CSTF.FTFlwStaBook = '$tStatusdoc'";
                } else {
                    $tSQL .= "";
                }
            }
            // ค้นหาจากวันที่เข้ารับบริการ
            if(!empty($dJointdate)){
                $tSQL .= " AND CSTF.FDFlwLastDate = CONVERT(datetime,'$dJointdate 00:00:00')";
            }
            // ค้นหาจากวันที่เอกสาร
            if(!empty($dDocdate)){
                $tSQL .= " AND JORD.FDXshDocDate = CONVERT(datetime,'$dDocdate 00:00:00')";
            }
            $tSQL   .= "ORDER BY FTCarCode ASC ";
            $oQuery  = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
                $aResult    = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            unset($aAdvanceSearch,$tStatusdoc,$dDocdate,$dJointdate,$tBchFrom,$tBchTo,$tSearchList,$tCarCode,$nLngID);
            unset($tSQL,$oQuery,$aList);
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : list Car Order History
    //Parameters : function parameters
    //Creator :  10/06/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMCAROrderHistoryList($paData){
        try{
            $tSearchList    = $paData['tSearchAll'];
            $tCarCode       = $paData['nPosCode'];
            $nLngID         = $paData['FNLngID'];
            $tSQL       = "
                SELECT  TOP ". get_cookie('nShowRecordInPageList')."
                    JORD.FTBchCode              AS rtBchCode,
                    BRL.FTBchName               AS rtBchName,
                    AGN_L.FTAgnName             AS rtAgnName,
                    AGN_L.FTAgnCode             AS rtAgnCode,
                    JORD.FTXshDocNo             AS rtXshDocNo,
                    CST_L.FTCstName             AS rtCstName,
                    POS_L.FTSpsName             AS rtSpsName,
                    CAR.FTCarCode               AS rtCarCode,
                    CAR.FTCarRegNo              AS rtCarRegNo,
                    CASE WHEN JORD.FTXshStaClosed IS NULL THEN 'ยังไม่ปิด' ELSE 'ปิด Job' END AS rtXshStaClosed,
                    CONVERT(CHAR(10),JORD.FDXshDocDate,103)         AS rtXshDocDate
                FROM [TSVTJob2OrdHD] JORD WITH (NOLOCK)
                LEFT JOIN [TCNMBranch_L] BRL WITH (NOLOCK) ON JORD.FTBchCode = BRL.FTBchCode AND BRL.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN [TCNMAgency_L] AGN_L WITH(NOLOCK) ON JORD.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN [TCNMCst_L] CST_L WITH(NOLOCK) ON JORD.FTCstCode = CST_L.FTCstCode AND CST_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN [TSVMPos_L] POS_L WITH(NOLOCK) ON JORD.FTXshToPos = POS_L.FTSpsCode AND POS_L.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN [TSVTJob2OrdHDCst] HDCst WITH (NOLOCK) ON JORD.FTXshDocNo = HDCst.FTXshDocNo 
                LEFT JOIN [TSVMCar] CAR     WITH(NOLOCK) ON CAR.FTCarCode = HDCst.FTCarCode
                WHERE CAR.FTCarCode  = ".$this->db->escape($tCarCode)."
            ";
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND (BRL.FTBchName    LIKE '%$tSearchList%'";
                $tSQL .= " OR AGN_L.FTAgnName    LIKE '%$tSearchList%'";
                $tSQL .= " OR JORD.FTXshDocNo    LIKE '%$tSearchList%'";
                $tSQL .= " OR POS_L.FTSpsName   LIKE '%$tSearchList%'";
                $tSQL .= " OR CST_L.FTCstName   LIKE '%$tSearchList%'";
                $tSQL .= " OR CAR.FTCarCode    LIKE '%$tSearchList%')";
            }

            $tSQL .= "ORDER BY CAR.FTCarCode ASC";

            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
                $aResult    = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : All Page Of History
    //Parameters : function parameters
    //Creator :  28/05/2021 Off
    //Return : object Count All History
    //Return Type : Object
    public function FSoMCARGetOrderHistoryPageAll($ptSearchList,$pnLngID,$ptCarCode){
        try{
            $tSQL = "SELECT COUNT (JORD.FTXshDocNo) AS counts
                FROM [TSVTJob2OrdHD] JORD WITH (NOLOCK)
                LEFT JOIN [TCNMBranch_L] BRL WITH (NOLOCK) ON JORD.FTBchCode = BRL.FTBchCode AND BRL.FNLngID = $pnLngID
                LEFT JOIN [TCNMAgency_L] AGN_L WITH(NOLOCK) ON JORD.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = $pnLngID
                LEFT JOIN [TCNMCst_L] CST_L WITH(NOLOCK) ON JORD.FTCstCode = CST_L.FTCstCode AND CST_L.FNLngID = $pnLngID
                LEFT JOIN [TSVMPos_L] POS_L WITH(NOLOCK) ON JORD.FTXshToPos = POS_L.FTSpsCode AND POS_L.FNLngID = $pnLngID
                LEFT JOIN [TSVTJob2OrdHDCst] HDCst WITH (NOLOCK) ON JORD.FTXshDocNo = HDCst.FTXshDocNo 
                LEFT JOIN [TSVMCar] CAR     WITH(NOLOCK) ON CAR.FTCarCode = HDCst.FTCarCode
                WHERE CAR.FTCarCode  = '$ptCarCode' ";

            if($ptSearchList != ''){
                $tSQL .= " AND (BRL.FTBchName    LIKE '%$ptSearchList%'";
                $tSQL .= " OR AGN_L.FTAgnName    LIKE '%$ptSearchList%'";
                $tSQL .= " OR JORD.FTXshDocNo    LIKE '%$ptSearchList%'";
                $tSQL .= " OR POS_L.FTSpsName   LIKE '%$ptSearchList%'";
                $tSQL .= " OR CST_L.FTCstName   LIKE '%$ptSearchList%'";
                $tSQL .= " OR CAR.FTCarCode   LIKE '%$ptSearchList%')";
            }
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //ข้อมูลการขายตามรถ
    public function FSaMCARSaleHistoryList($paData){
        try{
            $nCarCode       = $paData['nCarCode'];
            $tSearchList    = trim($paData['tSearchAll']);
            $nLngID         = $this->session->userdata("tLangEdit");

            $tSQL           = "SELECT TOP ". get_cookie('nShowRecordInPageList')."
                                    BCHL.FTBchName ,
                                    SALDT.FTXshDocNo ,
                                    CONVERT(CHAR(10),SALHD.FDXshDocDate,103) AS FDXshDocDate,
                                    CONVERT(CHAR(20),SALHD.FDXshDocDate,8) AS rtTime,
                                    SALDT.FTPdtCode ,
                                    SALDT.FTPunName ,
                                    SALDT.FTXsdPdtName ,
                                    SALDT.FCXsdQty,
                                    SALDT.FCXsdNetAfHD
                                FROM TPSTSalDT          SALDT   WITH (NOLOCK)
                                LEFT JOIN TPSTSalHD     SALHD   WITH (NOLOCK) ON SALDT.FTXshDocNo = SALHD.FTXshDocNo
                                LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SALDT.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                                LEFT JOIN TPSTSalHDCst  SALCST  WITH (NOLOCK) ON SALDT.FTXshDocNo = SALCST.FTXshDocNo
                                WHERE SALHD.FNXshDocType = 1 AND SALCST.FTCarCode = ".$this->db->escape($nCarCode)." ";

            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL   .= " AND (BCHL.FTBchName    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR SALDT.FTPdtCode    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR SALDT.FTXsdPdtName    LIKE '%".$this->db->escape_like_str($tSearchList)."%' )";
            }

            $tSQL   .= " ORDER BY SALHD.FDXshDocDate DESC ";
            
            $oQuery  = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
                $aResult    = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            unset($tSQL,$oQuery,$aList);
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }
}
