<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mConnectionSetting extends CI_Model
{

    //Functionality : แสดงข้อมูล คลัง ข้างบน
    //Parameters : function parameters
    //Creator : 14/05/202020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataUP($paData)
    {

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllNotSet  = $paData['tSearchAllNotSet'];

        $tSQL  = " SELECT
                            ISNULL(AGN.FTAgnName,'N/A') AS FTAgnName
                            ,WH.FTBchCode
                            ,BCHL.FTBchName
                            ,WH.FTWahCode
                            ,WHL.FTWahName
                            ,FTWahStaType
                        FROM  TCNMWaHouse WH
                        INNER JOIN TCNMWaHouse_L WHL ON WH.FTBchCode = WHL.FTBchCode AND WH.FTWahCode = WHL.FTWahCode AND WHL.FNLngID = $nLngID
                        LEFT JOIN  TLKMWaHouse LKWH ON  WH.FTBchCode = LKWH.FTBchCode AND WH.FTWahCode = LKWH.FTWahCode
                        LEFT JOIN  TCNMBranch BCH   ON  WH.FTBchCode = BCH.FTBchCode AND WH.FTWahCode = BCH.FTWahCode
                        LEFT JOIN  TCNMBranch_L BCHL ON WH.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID = $nLngID
                        LEFT JOIN  TCNMAgency_L AGN ON BCH.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID = $nLngID
                        WHERE ((WH.FTWahStaType = 1 AND WH.FTWahCode = '00001') OR WH.FTWahStaType = 2)
                        AND ISNULL(LKWH.FTWahCode,'') = ''
                        -- AND WH.FTBchCode IN($tBchCodeMulti)
                ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND WH.FTBchCode IN ($tBchCode) ";
        }


        if (isset($tSearchAllNotSet) && !empty($tSearchAllNotSet)) {
            $tSQL .= " AND (WH.FTBchCode  LIKE '%$tSearchAllNotSet%'";
            $tSQL .= " OR BCHL.FTBchName LIKE '%$tSearchAllNotSet%'";
            $tSQL .= " OR WH.FTWahCode LIKE '%$tSearchAllNotSet%'";
            $tSQL .= " OR WHL.FTWahName LIKE '%$tSearchAllNotSet%')";
        }

        $tSQL .= "ORDER BY WH.FTBchCode , WH.FTWahCode ASC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : แสดงข้อมูล Mapping
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataMapping($paData)
    {

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllMapping  = $paData['tSearchAllMapping'];

        $tSQL  = " SELECT
                    MP.FNMapSeqNo
                    ,MP.FTMapName
                    ,MP.FTMapDefValue
                    ,MP.FTMapUsrValue
                    ,MP.FTMapCode
                    ,MP.FTMapType
                FROM  TLKMMapping MP Where 1=1
        ";

        if (isset($tSearchAllMapping) && !empty($tSearchAllMapping)) {
            $tSQL .= " AND (MP.FTMapName  LIKE '%$tSearchAllMapping%'";
            $tSQL .= " OR MP.FNMapSeqNo LIKE '%$tSearchAllMapping%'";
            $tSQL .= " OR MP.FTMapDefValue LIKE '%$tSearchAllMapping%'";
            $tSQL .= " OR MP.FTMapUsrValue LIKE '%$tSearchAllMapping%')";
        }

        $tSQL .= " ORDER BY MP.FTMapCode ASC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : แสดงข้อมูล Mapping
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataMSShop($paData)
    {

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllMSShop  = $paData['tSearchAllMSShop'];

        $tSQL  = " SELECT
                    MSS.FTBchCode
                    ,MSS.FTPosCode
                    ,MSS.FTLmsMID
                    ,MSS.FTLmsTID
                    ,MSS.FTApiToken
                    ,MSS.FTApiLoginUsr
                    ,MSS.FTApiLoginPwd
                    ,CASE WHEN FTStaBlueCode='1'
                    THEN 'ใช้งาน'
                    ELSE 'ไม่ใช้งาน' END AS FTStaBlueCode
                    ,BRNL.FTBchName
                    ,BRN.FTBchRefID
                FROM  TLKMLMSShop MSS
                LEFT JOIN  TCNMBranch_L BRNL ON  MSS.FTBchCode = BRNL.FTBchCode AND BRNL.FNLngID = $nLngID
                LEFT JOIN  TCNMBranch BRN ON  MSS.FTBchCode = BRN.FTBchCode
                Where 1=1
        ";

        if (isset($tSearchAllMSShop) && !empty($tSearchAllMSShop)) {
            $tSQL .= " AND (BRNL.FTBchName  LIKE '%$tSearchAllMSShop%'";
            $tSQL .= " OR BRN.FTBchRefID LIKE '%$tSearchAllMSShop%'";
            $tSQL .= " OR MSS.FTLmsMID LIKE '%$tSearchAllMSShop%'";
            $tSQL .= " OR MSS.FTApiToken LIKE '%$tSearchAllMSShop%'";
            $tSQL .= " OR MSS.FTApiLoginUsr LIKE '%$tSearchAllMSShop%'";
            $tSQL .= " OR MSS.FTLmsTID LIKE '%$tSearchAllMSShop%' )";
        }

        $tSQL .= " ORDER BY MSS.FTBchCode ASC";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : แสดงข้อมูล Mapping
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataErrMsg($paData)
    {

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllRespond  = $paData['tSearchAllRespond'];

        $tSQL  = " SELECT
                    EMS.FTErrCode
                    ,EMS.FTErrStaApp
                    ,EMS.FTErrDescription
                    ,CASE
                        WHEN EMS.FTErrStaApp  = '1' THEN 'LMS'
                    ELSE
                        '-'
                    END AS FTErrStaAppName
                FROM  TLKMErrMsg EMS
                Where 1=1
        ";

        if (isset($tSearchAllRespond) && !empty($tSearchAllRespond)) {
            $tSQL .= " AND (EMS.FTErrCode  LIKE '%$tSearchAllRespond%'";
            $tSQL .= " OR EMS.FTErrStaApp LIKE '%$tSearchAllRespond%'";
            $tSQL .= " OR EMS.FTErrDescription LIKE '%$tSearchAllRespond%')";
        }

        $tSQL .= " ORDER BY EMS.FTErrCode ASC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }


    //Functionality : แสดงข้อมูล Mapping
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataUsrShop($paData)
    {

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllUserShop  = $paData['tSearchAllUserShop'];

        $tSQL  = " SELECT
                    CSTS.FTCshCostCenter
                    ,CSTS.FTCshShipTo
                    ,CSTS.FTCshSoldTo
                    ,CSTS.FTBchCode
                    ,CSTS.FTCshWhTaxCode
                    ,BRN.FTBchRefID
                    ,BRNL.FTBchName
                FROM  TLKMCstShp CSTS
                LEFT JOIN  TCNMBranch BRN ON  BRN.FTBchCode = CSTS.FTBchCode
                LEFT JOIN  TCNMBranch_L BRNL ON  BRN.FTBchCode = BRNL.FTBchCode AND BRNL.FNLngID = $nLngID
                Where 1=1
        ";

        if (isset($tSearchAllUserShop) && !empty($tSearchAllUserShop)) {
            $tSQL .= " AND (BRNL.FTBchName  LIKE '%$tSearchAllUserShop%'";
            $tSQL .= " OR BRN.FTBchRefID LIKE '%$tSearchAllUserShop%'";
            $tSQL .= " OR CSTS.FTCshSoldTo LIKE '%$tSearchAllUserShop%'";
            $tSQL .= " OR CSTS.FTCshShipTo LIKE '%$tSearchAllUserShop%'";
            $tSQL .= " OR CSTS.FTCshCostCenter LIKE '%$tSearchAllUserShop%')";
        }

        $tSQL .= " ORDER BY CSTS.FTBchCode DESC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : แสดงข้อมูล Carinter
    //Parameters : function parameters
    //Creator : 22/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataCarInter($paData)
    {

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);

        $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        $nLngID    = $paData['FNLngID'];
        $tSearchAllCarInter  = $paData['tSearchAllCarInter'];

        $tSQL  = " SELECT c.* FROM(
                     SELECT  ROW_NUMBER() OVER(ORDER BY FTCarRegNo DESC) AS rtRowID,* FROM
                    (SELECT
                    CIT.FTCarRegNo
                    ,CIT.FTCbaIO
                    ,CIT.FTCbaCostCenter
                    ,CIT.FTCbaStaTax
                    ,CSTL.FTCstCode
                    ,CSTL.FTCstName
                    ,CASE
                        WHEN CIT.FTCbaStaTax  = '1' THEN 'No Output Tax'
                    ELSE
                        'Output Tax'
                    END AS FTCbaStaTaxName
                    ,CASE
                        WHEN CIT.FTCbaConSta  = '1' THEN 'ติดต่อ'
                    ELSE
                        'ไม่ติดต่อ'
                    END AS FTCbaConSta
                FROM  TLKMCarInterBA CIT
                LEFT JOIN  TSVMCar CAR       ON  CAR.FTCarRegNo = CIT.FTCarRegNo
                LEFT JOIN  TCNMCst_L CSTL    ON  CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                Where 1=1
        ";


        if (isset($tSearchAllCarInter) && !empty($tSearchAllCarInter)) {
            $tSQL .= " AND (CIT.FTCarRegNo  LIKE '%$tSearchAllCarInter%'";
            $tSQL .= " OR CSTL.FTCstName LIKE '%$tSearchAllCarInter%'";
            $tSQL .= " OR CIT.FTCbaIO LIKE '%$tSearchAllCarInter%'";
            $tSQL .= " OR CIT.FTCbaCostCenter LIKE '%$tSearchAllCarInter%'";
            $tSQL .= " OR CIT.FTCbaStaTax LIKE '%$tSearchAllCarInter%')";
        }

        // $tSQL .= " ORDER BY CIT.FTCarRegNo DESC";
        // $oQuery = $this->db->query($tSQL);

        // if ($oQuery->num_rows() > 0) {
        //     $oList = $oQuery->result();
        //     $aResult = array(
        //         'raItems'       => $oList,
        //         'rtCode'        => '1',
        //         'rtDesc'        => 'success',
        //     );
        // } else {
        //     //No Data
        //     $aResult = array(
        //         'rtCode' => '800',
        //         'rtDesc' => 'data not found',
        //     );
        // }
        // $jResult = json_encode($aResult);
        // $aResult = json_decode($jResult, true);
        // return $aResult;

    if(isset($tSearchAllCarInter) && !empty($tSearchAllCarInter)){
        $tSQL .= " AND (CIT.FTCarRegNo  LIKE '%$tSearchAllCarInter%'";
        $tSQL .= " OR CSTL.FTCstName LIKE '%$tSearchAllCarInter%'";
        $tSQL .= " OR CIT.FTCbaIO LIKE '%$tSearchAllCarInter%'";
        $tSQL .= " OR CIT.FTCbaCostCenter LIKE '%$tSearchAllCarInter%'";
        $tSQL .= " OR CIT.FTCbaStaTax LIKE '%$tSearchAllCarInter%')";
    }
    // $tSQL .= " ORDER BY CIT.FTCarRegNo DESC";

    $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
    $oQuery = $this->db->query($tSQL);

    if($oQuery->num_rows() > 0){
        $oList = $oQuery->result();
        $aFoundRow = $this->FSnMDataCarInterGetPageAll($tSearchAllCarInter,$nLngID);
        $nFoundRow = $aFoundRow[0]->counts;

        $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
        $aResult = array(
            'raItems'       => $oList,
            'rnAllRow' => $nFoundRow,
            'rnCurrentPage' => $paData['nPage'],
            "rnAllPage"=> $nPageAll,
            'rtCode'        => '1',
            'rtDesc'        => 'success',
        );
    }else{
        //No Data
        $aResult = array(
            'rnAllRow' => 0,
            'rnCurrentPage' => $paData['nPage'],
            "rnAllPage"=> 0,
            'rtCode' => '800',
            'rtDesc' => 'data not found',
        );
    }
    $jResult = json_encode($aResult);
    $aResult = json_decode($jResult, true);
    return $aResult;

    }

    //Functionality : All Page Of CarIntel
    //Parameters : function parameters
    //Creator :  15/11/2021 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMDataCarInterGetPageAll($ptSearchList,$ptLngID){

        $tSQL = "SELECT COUNT (CIT.FTCarRegNo) AS counts

                FROM  TLKMCarInterBA CIT
                LEFT JOIN  TSVMCar CAR       ON  CAR.FTCarRegNo = CIT.FTCarRegNo
                LEFT JOIN  TCNMCst_L CSTL    ON  CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = $ptLngID
                Where 1=1  ";

        if($ptSearchList != ''){
            $tSQL .= " AND (CIT.FTCarRegNo  LIKE '%$ptSearchList%'";
            $tSQL .= " OR CSTL.FTCstName LIKE '%$ptSearchList%'";
            $tSQL .= " OR CIT.FTCbaIO LIKE '%$ptSearchList%'";
            $tSQL .= " OR CIT.FTCbaCostCenter LIKE '%$ptSearchList%'";
            $tSQL .= " OR CIT.FTCbaStaTax LIKE '%$ptSearchList%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }


    //Functionality : แสดงข้อมูล คลังข้างล่าง
    //Parameters : function parameters
    //Creator : 14/05/202020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCSListDataDown($paData)
    {

        $tUsrAgnCode = $this->session->userdata("tSesUsrAgnCode");

        $tWhere = "";

        if (!empty($paData['tStaUsrLevel']) && $paData['tStaUsrLevel'] != "HQ") {
            if (!empty($paData['tUsrBchCode'])) {
                $tUsrBchCode = $paData['tUsrBchCode'];
                $tWhere .=  " AND TWH.FTBchCode IN ($tUsrBchCode) ";
            }
        }

        $nLngID = $paData['FNLngID'];
        $tSearchAllSetUp  = $paData['tSearchAllSetUp'];

        $tSQL       = " SELECT
                            TWH.FTAgnCode,
                            TWH.FTBchCode,
                            TWH.FTShpCode,
                            SHPL.FTShpName,
                            TWH.FTWahCode,
                            TWH.FTWahRefNo,
                            TWH.FTWahStaChannel,
                            AGNL.FTAgnName,
                            TBL.FTBchName,
                            TWHL.FTWahName
                    FROM TLKMWaHouse TWH
                    LEFT JOIN TCNMAgency_L AGNL ON TWH.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID =  $nLngID
                    LEFT JOIN TCNMBranch_L TBL ON TWH.FTBchCode = TBL.FTBchCode AND TBL.FNLngID      =  $nLngID
                    LEFT JOIN TCNMShop_L SHPL ON TWH.FTBchCode = SHPL.FTBchCode AND TWH.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID      =  $nLngID
                    LEFT JOIN TCNMWaHouse_L TWHL ON TWH.FTWahCode = TWHL.FTWahCode AND TWH.FTBchCode = TWHL.FTBchCode
                    AND TWHL.FNLngID  =  $nLngID
                    WHERE 1=1
                    $tWhere ";

        if (isset($tSearchAllSetUp) && !empty($tSearchAllSetUp)) {
            if ($tSearchAllSetUp == 'Counter') {
                $tSQL .= "AND (TWH.FTWahStaChannel = 1) ";
            } else if ($tSearchAllSetUp == 'Event') {
                $tSQL .= "AND (TWH.FTWahStaChannel = 2) ";
            } else if ($tSearchAllSetUp == 'Vansale') {
                $tSQL .= "AND (TWH.FTWahStaChannel = 3) ";
            } else {
                $tSQL .= " AND (AGNL.FTAgnName  LIKE '%$tSearchAllSetUp%'";
                $tSQL .= " OR TBL.FTBchName LIKE '%$tSearchAllSetUp%'";
                $tSQL .= " OR TWH.FTWahCode LIKE '%$tSearchAllSetUp%'";
                $tSQL .= " OR TWHL.FTWahName LIKE '%$tSearchAllSetUp%'";
                $tSQL .= " OR TWH.FTWahRefNo LIKE '%$tSearchAllSetUp%')";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            //No Data
            $aResult = array(
                'raItems' =>  '',
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }


    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateCstShpMaster($paData)
    {
        try {
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTCshSoldTo', $paData['FTCshSoldTo']);
            $this->db->set('FTCshShipTo', $paData['FTCshShipTo']);
            $this->db->set('FTCshCostCenter', $paData['FTCshCostCenter']);
            $this->db->set('FTCshWhTaxCode', $paData['FTCshWhTaxCode']);
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->update('TLKMCstShp');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TLKMCstShp', array(
                    'FTCshSoldTo'               => $paData['FTCshSoldTo'],
                    'FTBchCode'                 => $paData['FTBchCode'],
                    'FTCshShipTo'               => $paData['FTCshShipTo'],
                    'FTCshCostCenter'           => $paData['FTCshCostCenter'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 22/07/2021 Off
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateCarInterMaster($paData)
    {
        try {
            $this->db->set('FTCbaIO', $paData['FTCbaIO']);
            $this->db->set('FTCbaCostCenter', $paData['FTCbaCostCenter']);
            $this->db->set('FTCbaStaTax', $paData['FTCbaStaTax']);
            $this->db->where('FTCarRegNo', $paData['FTCarRegNo']);
            $this->db->update('TLKMCarInterBA');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TLKMCarInterBA', array(
                    'FTCbaIO'                   => $paData['FTCbaIO'],
                    'FTCbaCostCenter'           => $paData['FTCbaCostCenter'],
                    'FTCbaStaTax'               => $paData['FTCbaStaTax'],
                    'FTCarRegNo'                => $paData['FTCarRegNo'],
                    'FTCbaID'                        => $paData['FTCbaID'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 22/07/2021 Off
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateMSShopMaster($paData)
    {
        try {
            $this->db->set('FTStaBlueCode', $paData['FTStaBlueCode']);
            $this->db->set('FTApiLoginUsr', $paData['FTApiLoginUsr']);
            $this->db->set('FTApiLoginPwd', $paData['FTApiLoginPwd']);
            $this->db->set('FTApiToken', $paData['FTApiToken']);
            $this->db->set('FTLmsMID', $paData['FTLmsMID']);
            $this->db->set('FTLmsTID', $paData['FTLmsTID']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTPosCode', $paData['FTPosCode']);
            $this->db->update('TLKMLMSShop');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TLKMLMSShop', array(
                    'FTStaBlueCode'           => $paData['FTStaBlueCode'],
                    'FTApiLoginUsr'           => $paData['FTApiLoginUsr'],
                    'FTApiLoginPwd'           => $paData['FTApiLoginPwd'],
                    'FTApiToken'              => $paData['FTApiToken'],
                    'FTPosCode'               => $paData['FTPosCode'],
                    'FTBchCode'               => $paData['FTBchCode'],
                    'FTLmsTID'                => $paData['FTLmsTID'],
                    'FTLmsMID'                => $paData['FTLmsMID'],
                    'FDLastUpdOn'             => $paData['FDLastUpdOn'],
                    'FTLastUpdBy'             => $paData['FTLastUpdBy'],
                    'FDCreateOn'              => $paData['FDCreateOn'],
                    'FTCreateBy'              => $paData['FTCreateBy'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 22/07/2021 Off
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateErrMsgMaster($paData)
    {
        try {
            $this->db->set('FTErrStaApp', $paData['FTErrStaApp']);
            $this->db->set('FTErrDescription', $paData['FTErrDescription']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTErrCode', $paData['FTErrCode']);
            $this->db->update('TLKMErrMsg');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TLKMErrMsg', array(
                    'FTErrStaApp'               => $paData['FTErrStaApp'],
                    'FTErrDescription'          => $paData['FTErrDescription'],
                    'FTErrCode'                 => $paData['FTErrCode'],
                    'FDLastUpdOn'                 => $paData['FDLastUpdOn'],
                    'FTLastUpdBy'                 => $paData['FTLastUpdBy'],
                    'FDCreateOn'                 => $paData['FDCreateOn'],
                    'FTCreateBy'                 => $paData['FTCreateBy'],
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateMappingMaster($paData)
    {
        try {
            //Update Master
            $this->db->set('FTMapName', $paData['FTMapName']);
            $this->db->set('FTMapDefValue', $paData['FTMapDefValue']);
            $this->db->set('FTMapUsrValue', $paData['FTMapUsrValue']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTMapCode', $paData['FTMapCode']);
            $this->db->where('FNMapSeqNo', $paData['FNMapSeqNo']);
            $this->db->update('TLKMMapping');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }



    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataDown($paData)
    {
        $tMerCode   = $paData['FTAgnCode'];
        $tBchCode   = $paData['FTBchCode'];
        $tShpCode   = $paData['FTShpCode'];
        $tWahCode   = $paData['FTWahCode'];

        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            TWH.FTAgnCode,
                            TWH.FTBchCode,
                            TWH.FTShpCode,
                            SHPL.FTShpName,
                            TWH.FTWahCode,
                            TWH.FTWahRefNo,
                            TWH.FTWahStaChannel,
                            AGNL.FTAgnName,
                            TBL.FTBchName,
                            TWHL.FTWahName
                        FROM TLKMWaHouse TWH
                        LEFT JOIN TCNMAgency_L AGNL ON TWH.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID =  $nLngID
                        LEFT JOIN TCNMBranch_L TBL ON TWH.FTBchCode = TBL.FTBchCode AND TBL.FNLngID      =  $nLngID
                        LEFT JOIN TCNMShop_L SHPL ON TWH.FTBchCode = SHPL.FTBchCode AND TWH.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID      =  $nLngID
                        LEFT JOIN TCNMWaHouse_L TWHL ON TWH.FTWahCode = TWHL.FTWahCode AND TWH.FTBchCode = TWHL.FTBchCode
                        AND TWHL.FNLngID  =  $nLngID
                        WHERE 1=1 ";

        if ($tMerCode != "") {
            $tSQL .= "AND TWH.FTAgnCode = '$tMerCode'";
        }
        if ($tBchCode != "") {
            $tSQL .= "AND TWH.FTBchCode = '$tBchCode'";
        }
        if ($tShpCode != "") {
            $tSQL .= "AND TWH.FTShpCode = '$tShpCode'";
        }
        if ($tWahCode != "") {
            $tSQL .= "AND TWH.FTWahCode = '$tWahCode'";
        }
        $oQuery = $this->db->query($tSQL);


        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
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

    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataMapping($paData)
    {
        $tMapCode   = $paData['FTMapCode'];
        $tMapSeqNo   = $paData['FNMapSeqNo'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                         MP.FNMapSeqNo
                        ,MP.FTMapName
                        ,MP.FTMapDefValue
                        ,MP.FTMapUsrValue
                        ,MP.FTMapCode
                        ,MP.FTMapType
                        ,RCVL.FTRcvName
                        FROM  TLKMMapping MP
                        LEFT JOIN TFNMRcv_L RCVL ON MP.FTMapDefValue = RCVL.FTRcvCode AND RCVL.FNLngID =  $nLngID
                        WHERE 1=1 ";

        if ($tMapCode != "") {
            $tSQL .= "AND MP.FTMapCode = '$tMapCode' AND MP.FNMapSeqNo = '$tMapSeqNo'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataMSShop($paData)
    {
        $tPosCode   = $paData['FTPosCode'];
        $tBchCode   = $paData['FTBchCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                        MSS.FTBchCode
                        ,MSS.FTPosCode
                        ,MSS.FTStaBlueCode
                        ,MSS.FTLmsMID
                        ,MSS.FTLmsTID
                        ,MSS.FTStaBlueCode
                        ,MSS.FTApiToken
                        ,MSS.FTApiLoginUsr
                        ,MSS.FTApiLoginPwd
                        ,BRNL.FTBchName
                        ,BRN.FTBchRefID
                        ,POSL.FTPosName
                    FROM  TLKMLMSShop MSS
                    LEFT JOIN  TCNMBranch_L BRNL ON  MSS.FTBchCode = BRNL.FTBchCode AND BRNL.FNLngID = $nLngID
                    INNER JOIN  TCNMPos_L POSL ON  MSS.FTBchCode = POSL.FTBchCode AND POSL.FTPosCode = MSS.FTPosCode  AND BRNL.FNLngID = $nLngID
                    LEFT JOIN  TCNMBranch BRN ON  MSS.FTBchCode = BRN.FTBchCode
                        WHERE 1=1 ";

        if ($tBchCode != "") {
            $tSQL .= "AND MSS.FTBchCode = '$tBchCode' AND MSS.FTPosCode = '$tPosCode'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataErrMsg($paData)
    {
        $tErrCode   = $paData['FTErrCode'];

        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                        EMS.FTErrCode
                        ,EMS.FTErrStaApp
                        ,EMS.FTErrDescription
                    FROM  TLKMErrMsg EMS
                    WHERE 1=1 ";

        if ($tErrCode != "") {
            $tSQL .= "AND EMS.FTErrCode = '$tErrCode'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataCstShp($paData)
    {
        $tBchCode   = $paData['FTBchCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                        CstS.FTBchCode
                        ,CstS.FTCshSoldTo
                        ,CstS.FTCshShipTo
                        ,CstS.FTCshCostCenter
                        ,CstS.FTCshWhTaxCode
                        ,BCHL.FTBchName
                        ,BCH.FTBchRefID
                        FROM  TLKMCstShp CstS
                        LEFT JOIN TCNMBranch BCH ON CstS.FTBchCode = BCH.FTBchCode
                        LEFT JOIN TCNMBranch_L BCHL ON BCHL.FTBchCode = BCH.FTBchCode AND BCHL.FNLngID =  $nLngID
                        WHERE 1=1 ";

        if ($tBchCode != "") {
            $tSQL .= "AND CstS.FTBchCode = '$tBchCode'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search connectionsetting By ID
    //Parameters : function parameters
    //Creator : 22/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataCarInter($paData)
    {
        $tCarReq   = $paData['FTCarRegNo'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                        CIT.FTCarRegNo
                        ,CIT.FTCbaIO
                        ,CIT.FTCbaCostCenter
                        ,CIT.FTCbaStaTax
                        ,CSTL.FTCstCode
                        ,CSTL.FTCstName
                    FROM  TLKMCarInterBA CIT
                    LEFT JOIN  TSVMCar CAR       ON  CAR.FTCarRegNo = CIT.FTCarRegNo
                    LEFT JOIN  TCNMCst_L CSTL    ON  CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                    WHERE 1=1 ";

        if ($tCarReq != "") {
            $tSQL .= "AND CIT.FTCarRegNo = '$tCarReq'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search Used Branch
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataUsedBranch($paData)
    {
        // $tMapCode   = $paData['FTMapCode'];
        // $nLngID     = $paData['FNLngID'];
        $tTable = $paData;

        $tSQL       = " SELECT
                        MP.FTBchCode
                        FROM  $tTable MP
                        WHERE 1=1 ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    public function FSaMCCGetDataUsedMS()
    {

        $tSQL       = " SELECT
                        MP.FTBchCode,
                        MP.FTPosCode
                        FROM  TLKMLMSShop MP
                        WHERE 1=1 ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Search Used Car
    //Parameters : function parameters
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCGetDataUsedCar($paData)
    {
        $tTable = $paData;

        $tSQL       = " SELECT
                        MP.FTCarRegNo
                        FROM  $tTable MP
                        WHERE 1=1 ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }



    // function delete siggle
    // Create BY Witsarut 21/05/2020
    public function FSnMConnSetDel($paData)
    {

        $this->db->where('FTAgnCode', $paData['FTAgnCode']);
        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FTShpCode', $paData['FTShpCode']);
        $this->db->where('FTWahCode', $paData['FTWahCode']);
        $this->db->delete('TLKMWaHouse');

        if ($this->db->affected_rows() > 0) {
            $aStatus  = array(
                'rtCode' => 1,
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    // function delete siggle
    // Create BY Witsarut 21/05/2020
    public function FSnMConnSetDelMSShop($paData)
    {

        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FTPosCode', $paData['FTPosCode']);
        $this->db->delete('TLKMLMSShop');

        if ($this->db->affected_rows() > 0) {
            $aStatus  = array(
                'rtCode' => 1,
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    // function delete siggle CstShp
    // Create BY Witsarut 21/05/2020
    public function FSnMConnSetDelCstShp($paData)
    {

        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->delete('TLKMCstShp');

        if ($this->db->affected_rows() > 0) {
            $aStatus  = array(
                'rtCode' => 1,
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    // function delete siggle CstShp
    // Create BY Witsarut 21/05/2020
    public function FSnMConnSetDelErrMsg($paData)
    {

        $this->db->where('FTErrCode', $paData['FTErrCode']);
        $this->db->delete('TLKMErrMsg');

        if ($this->db->affected_rows() > 0) {
            $aStatus  = array(
                'rtCode' => 1,
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    // function delete siggle CarInter
    // Create BY Witsarut 21/05/2020
    public function FSnMConnSetDelCarInter($paData)
    {

        $this->db->where('FTCarRegNo', $paData['FTCarRegNo']);
        $this->db->delete('TLKMCarInterBA');

        if ($this->db->affected_rows() > 0) {
            $aStatus  = array(
                'rtCode' => 1,
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


    //Functionality : Delete  Ads Multiple
    //Parameters : Ajax ()
    //Creator : 20/08/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaMConnDeleteMultiple($paDataDelete)
    {

        $this->db->where_in('FTAgnCode', $paDataDelete['aDataAgnCode']);
        $this->db->where_in('FTBchCode', $paDataDelete['aDataBchCode']);
        $this->db->where_in('FTShpCode', $paDataDelete['aDataShpCode']);
        $this->db->where_in('FTWahCode', $paDataDelete['aDataWahCode']);
        $this->db->delete('TLKMWaHouse');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : Delete  Ads Multiple CstShp
    //Parameters : Ajax ()
    //Creator : 20/08/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaMConnDeleteMultipleCstShp($paDataDelete)
    {

        $this->db->where_in('FTBchCode', $paDataDelete['aDataBchCode']);
        $this->db->delete('TLKMCstShp');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : Delete  Ads Multiple CarInter
    //Parameters : Ajax ()
    //Creator : 22/07/2021 Off
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaMConnDeleteMultipleCarInter($paDataDelete)
    {

        $this->db->where_in('FTCarRegNo', $paDataDelete['aDataCarReq']);
        $this->db->delete('TLKMCarInterBA');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : Delete  Ads Multiple CarInter
    //Parameters : Ajax ()
    //Creator : 22/07/2021 Off
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaMConnDeleteMultipleErrMsg($paDataDelete)
    {

        $this->db->where_in('FTErrCode', $paDataDelete['aDataErrCode']);
        $this->db->delete('TLKMErrMsg');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : Delete  Ads Multiple CarInter
    //Parameters : Ajax ()
    //Creator : 22/07/2021 Off
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaMConnDeleteMultipleMSShop($paDataDelete)
    {

        $this->db->where_in('FTBchCode', $paDataDelete['aDataBchCode']);
        $this->db->where_in('FTPosCode', $paDataDelete['aDataPosCode']);
        $this->db->delete('TLKMLMSShop');

        if ($this->db->affected_rows() > 0) {
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


    //Functionality : Get all row
    //Parameters : -
    //Creator : 04/07/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSnMLOCGetAllNumRow($ptTable)
    {
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM $ptTable";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        } else {
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : Get API
    //Parameters : -
    //Creator : 04/07/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSaMCCGetToken()
    {
        $tSQL = "SELECT FTApiURL FROM TCNMTxnAPI WHERE FTApiCode = '00001'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->result();
        } else {
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : Get TestHost API
    //Parameters : -
    //Creator : 04/07/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSaMCCGetTestHost()
    {
        $tSQL = "SELECT FTApiURL FROM TCNMTxnAPI WHERE FTApiCode = '00002'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->result();
        } else {
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : Update&insert connectionsetting
    //Parameters : function parameters
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMCSSAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTWahRefNo' , $paData['FTWahRefNo']);
            // $this->db->set('FTWahStaChannel' , $paData['FTWahStaChannel']);
    
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FDCreateOn' , $paData['FDCreateOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->set('FTCreateBy' , $paData['FTCreateBy']);
            $this->db->where('FTAgnCode', $paData['FTAgnCode']);
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTShpCode', $paData['FTShpCode']);
            $this->db->where('FTWahCode', $paData['FTWahCode']);
            $this->db->update('TLKMWaHouse');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TLKMWaHouse',array(
                    'FTAgnCode'         => $paData['FTAgnCode'],
                    'FTBchCode'         => $paData['FTBchCode'],
                    'FTShpCode'         => $paData['FTShpCode'],
                    'FTWahCode'         => $paData['FTWahCode'],
                    'FTWahRefNo'        => $paData['FTWahRefNo'],
                    // 'FTWahStaChannel'   => $paData['FTWahStaChannel'],
                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FTCreateBy'        => $paData['FTCreateBy'],

                ));
                if($this->db->affected_rows() > 0){
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

}
