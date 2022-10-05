<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Calendar_model extends CI_Model {
    
    //Functionality : Search Calendar By ID
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCCLDSearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tCldCode   = $paData['FTCldCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT
                CLD.FTSpsCode       AS rtObjCode,
                CLD.FTSpsRefCode    AS rtObjRefCode,
                CLD.FTSpsStaUse     AS rtObjStaUse,
                CLDL.FTSpsName      AS rtObjName,
                CLDL.FTSpsRmk       AS rtObjRmk,
                CLD.FDCreateOn      AS rtFDCreateOn,
                CLD.FTAgnCode       AS rtAgnCode,
                CLD.FTBchCode       AS rtBchCode,
                BCHL.FTBchName      AS rtBchName,
                CLD.FTSpsApvCode    AS rtApvCode,
                USRL.FTUsrName      AS rtApvName,
                AGNL.FTAgnName      AS rtAgnName
            FROM [TSVMPos] CLD with (NOLOCK)
            LEFT JOIN [TSVMPos_L] CLDL WITH (NOLOCK) ON CLD.FTSpsCode = CLDL.FTSpsCode AND CLDL.FNLngID         = ".$this->db->escape($nLngID)."
            LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON CLD.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN [TCNMBranch_L] BCHL WITH (NOLOCK) ON CLD.FTBchCode  =  BCHL.FTBchCode AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN [TCNMUser_L] USRL WITH (NOLOCK) ON CLD.FTSpsApvCode  =  USRL.FTUsrCode AND USRL.FNLngID   = ".$this->db->escape($nLngID)."
            WHERE CLD.FDCreateOn <> ''
            AND  CLD.FTSpsCode  = ".$this->db->escape($tCldCode)."
        ";
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
    
    //Functionality : list Calendar
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCLDList($ptAPIReq,$ptMethodReq,$paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSesAgnCode    = $paData['tSesAgnCode'];
        $tSQL           = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtObjCode DESC) AS rtRowID,*
                FROM(
                    SELECT DISTINCT
                        CLD.FTBchCode	            AS rtBchCode,
                        BCHL.FTBchName              AS rtBchName,
                        CLD.FTSpsCode               AS rtObjCode,
                        CLD.FTSpsRefCode            AS rtObjRefCode,
                        CLD.FTSpsStaUse             AS rtObjStaUse,
                        CLDL.FTSpsName              AS rtObjName,
                        CLDL.FTSpsRmk               AS rtObjRmk,
                        CLD.FDCreateOn              AS rtFDCreateOn,
                        CLD.FTSpsApvCode            AS rtApvCode,
                        USRL.FTUsrName              AS rtApvName,
                        BOOKHD.FTXshToPos           AS rtUseInBook
                    FROM TSVMPos CLD WITH (NOLOCK)
                    LEFT JOIN TSVMPos_L CLDL WITH (NOLOCK)   ON CLD.FTSpsCode       = CLDL.FTSpsCode AND CLDL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK)  ON CLD.FTSpsApvCode    = USRL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON CLD.FTBchCode       = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN (SELECT DISTINCT FTXshToPos FROM TSVTBookHD) BOOKHD ON BOOKHD.FTXshToPos = CLD.FTSpsCode
                    WHERE CLD.FDCreateOn <> '' ";

        if($tSesAgnCode != ''){
            $tSQL   .= " AND CLD.FTAgnCode = ".$this->db->escape($tSesAgnCode)." ";
        }

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            if(isset($tBCH) && !empty($tBCH)){
                $tSQL   .= " AND  CLD.FTBchCode IN ($tBCH) ";
            }
        }

        $tSearchList = $paData['tSearchAll'];
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL   .= " AND (CLD.FTSpsCode COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR CLDL.FTSpsName COLLATE THAI_BIN     LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR CLD.FTSpsRefCode COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMCLDGetPageAll($tSearchList,$nLngID, $tSesAgnCode);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => $nPageAll, 
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
            $jResult    = json_encode($aResult);
            $aResult    = json_decode($jResult, true);
        }else{
            //No Data
            $aResult    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
            $jResult    = json_encode($aResult);
            $aResult    = json_decode($jResult, true);
        }
        
        return $aResult;
    }

    //Functionality : All Page Of Calendar
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMCLDGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode){
        $tSQL   = "
            SELECT COUNT (CLD.FTSpsCode) AS counts
            FROM TSVMPos CLD WITH (NOLOCK)
            LEFT JOIN [TSVMPos_L] CLDL WITH (NOLOCK) ON CLD.FTSpsCode = CLDL.FTSpsCode AND CLDL.FNLngID = ".$this->db->escape($ptLngID)."
            WHERE CLD.FDCreateOn <> ''
        ";
        if($ptSesAgnCode != ''){
            $tSQL   .= " AND CLD.FTAgnCode = ".$this->db->escape($ptSesAgnCode)." ";
        }
        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH   = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL  .= " AND  CLD.FTBchCode IN ($tBCH) ";
        }
        if($ptSearchList != ''){
            $tSQL   .= " AND (CLD.FTSpsCode  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR CLDL.FTSpsName   LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR CLD.FTSpsRefCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }
    
    //Functionality : Checkduplicate Primary
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Data Count Duplicate
    //Return Type : Object
    public function FSoMCLDCheckDuplicate($ptCldCode){
        $tSQL   = "
            SELECT COUNT(FTSpsCode)AS counts
            FROM TSVMPos WITH (NOLOCK)
            WHERE FTSpsCode = ".$this->db->escape($ptCldCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : 
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMCLDAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTSpsRefCode' , $paData['FTSpsRefCode']);
            $this->db->set('FTSpsStaUse' , $paData['FTSpsStaUse']);
            $this->db->set('FTSpsApvCode' , $paData['FTSpsApvCode']);
            $this->db->where('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->where('FTBchCode' , $paData['FTBchCode']);
            $this->db->where('FTSpsCode', $paData['FTSpsCode']);
            $this->db->update('TSVMPos');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TSVMPos',array(
                    'FTSpsCode'     => $paData['FTSpsCode'],
                    'FTSpsRefCode'  => $paData['FTSpsRefCode'],
                    'FTSpsStaUse'   => $paData['FTSpsStaUse'],
                    'FTAgnCode'     => $paData['FTAgnCode'],
                    'FTSpsApvCode'  => $paData['FTSpsApvCode'],
                    'FTBchCode'     => $paData['FTBchCode'],
                    //เวลาบันทึกล่าสุด
                    'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                    'FDLastUpdOn'   => $paData['FDLastUpdOn'],
                    //เวลาบันทึกครั้งแรก
                    'FDCreateOn'    => $paData['FDCreateOn'],
                    'FTCreateBy'    => $paData['FTCreateBy'],
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

    //Functionality : Function Add/Update Lang
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified :
    //Return : Status Add Update Lang
    //Return Type : Array
    public function FSaMCLDAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTSpsName', $paData['FTSpsName']);
            $this->db->set('FTSpsRmk', $paData['FTSpsRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTSpsCode', $paData['FTSpsCode']);
            $this->db->where('FTAgnCode', $paData['FTAgnCode']);
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->update('TSVMPos_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TSVMPos_L',array(
                    'FTBchCode'     => $paData['FTBchCode'],
                    'FTSpsCode'     => $paData['FTSpsCode'],
                    'FTAgnCode'     => $paData['FTAgnCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTSpsName'     => $paData['FTSpsName'],
                    'FTSpsRmk'      => $paData['FTSpsRmk']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
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
    //Creator : 19/05/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMCLDDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTSpsCode', $paData['FTSpsCode']);
            $this->db->delete('TSVMPos');
            $this->db->where_in('FTSpsCode', $paData['FTSpsCode']);
            $this->db->delete('TSVMPos_L');
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

    //Functionality : get all row data from Calendar
    //Parameters : -
    //Creator : 19/05/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMCLDGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TSVMPos WITH (NOLOCK)";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : list UserCalendar Device
    //Parameters : function parameters
    //Creator :  28/05/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMCLDUserList($paData){
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = $paData['tSearchAll'];
            $nPosCode       = $paData['nPosCode'];
            $nLngID         = $paData['FNLngID'];
            $tSQL           = "
                SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY rtUsrCode ASC) AS rtRowID,* FROM (
                        SELECT DISTINCT
                            UCL.FTUsrCode    AS rtUsrCode,
                            UCL.FTSpsCode    AS rtObjCode,
                            UCL.FNSpuSeq     AS rtUsrSeq,
                            UCL.FTSpuRemark  AS FTSpuRemark,
                            UCL.FDSpsUsrStart  AS rtObjDutyStart,
                            UCL.FDSpsUsrExpired  AS rtObjDutyFinish,
                            USRL.FTUsrName   AS FTUsrName
                        FROM [TSVMPosUser] UCL WITH (NOLOCK)
                        LEFT JOIN [TCNMUser_L] USRL WITH (NOLOCK) ON USRL.FTUsrCode = UCL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                        WHERE 1=1 AND UCL.FTSpsCode  = ".$this->db->escape($nPosCode)."
            ";
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND (UCL.FTUsrCode LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL .= " OR UCL.FTSpsCode   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL .= " OR USRL.FTUsrName  LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
            }
            $tSQL   .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])." ";
            $oQuery  = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
                $oFoundRow  = $this->FSoMCLDGetUserPageAll($tSearchList,$nPosCode,$nLngID);
                $nFoundRow  = $oFoundRow[0]->counts;
                $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : All Page Of UserCalendar
    //Parameters : function parameters
    //Creator :  28/05/2021 Off
    //Return : object Count All UserCalendar
    //Return Type : Object
    public function FSoMCLDGetUserPageAll($ptSearchList,$pnPosCode,$pnLngID){
        try{
            $tSQL   = "
                SELECT COUNT (UCL.FTSpsCode) AS counts
                FROM [TSVMPosUser] UCL
                LEFT JOIN [TCNMUser_L] USRL WITH (NOLOCK) ON USRL.FTUsrCode = UCL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($pnLngID)."
                WHERE 1=1 AND UCL.FTSpsCode  = ".$this->db->escape($pnPosCode)."
            ";
            if($ptSearchList != ''){
                $tSQL   .= " AND (UCL.FTUsrCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR UCL.FTSpsCode   LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR USRL.FTUsrName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
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

    //Functionality : Get Max Seq CalendarUser
    //Parameters : phw code, pos code , shw code
    //Creator : -
    //Update : 28/05/2021 pap
    //Return : max seq
    //Return Type : number
    public function FSaMCLDLastSeqByShwCode($ptPhwPosCode){
        $tSQL   = "
            SELECT TOP 1 FNSpuSeq FROM TSVMPosUser 
            WHERE FTSpsCode = ".$this->db->escape($ptPhwPosCode)."
            ORDER BY FNSpuSeq DESC
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            return $oQuery->row_array()["FNSpuSeq"];
        }else{
            return 0;
        }
    }

    //Functionality : Checkduplicate UserCalendar
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSnMCLDCheckDuplicate($ptPosCode,$ptUsrCode){
        $tSQL   = "
            SELECT COUNT(USR.FTUsrCode) AS counts
            FROM TSVMPosUser USR
            WHERE USR.FTSpsCode = ".$this->db->escape($ptPosCode)."
            AND USR.FTUsrCode   = ".$this->db->escape($ptUsrCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()['counts'];
        }else{
            return FALSE;
        }
    }

    //Functionality : Update Product UserCalendar (TSVMPosUser)
    //Parameters : function parameters
    //Creator : 28/05/2021 Off
    //Update :
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMCLDAddUpdateCalendarUserMaster($paDataCalendarUser){
        try{
            // Update TSVMPosUser
            $this->db->where('FTSpsCode', $paDataCalendarUser['FTSpsCode']);
            if(isset($paDataCalendarUser['TTmpCode'])){
                $this->db->where('FTUsrCode', $paDataCalendarUser['TTmpCode']);
            }else{
                $this->db->where('FTUsrCode', $paDataCalendarUser['FTUsrCode']);
            }
            $this->db->update('TSVMPosUser',array(
                'FTSpsCode'         => $paDataCalendarUser['FTSpsCode'],
                'FTUsrCode'         => $paDataCalendarUser['FTUsrCode'],
                'FDSpsUsrStart'     => $paDataCalendarUser['FDSpsUsrStart'],
                'FDSpsUsrExpired'   => $paDataCalendarUser['FDSpsUsrExpired'],
                'FTSpuRemark'       => $paDataCalendarUser['FTSpuRemark']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update UserCalendar Success',
                );
            }else{
                //Add TSVMPosUser
                $this->db->insert('TSVMPosUser', array(
                    'FTSpsCode'            => $paDataCalendarUser['FTSpsCode'],
                    'FTUsrCode'            => $paDataCalendarUser['FTUsrCode'],
                    'FNSpuSeq'             => $paDataCalendarUser['FNOcuSeq'],
                    'FDSpsUsrStart'        => $paDataCalendarUser['FDSpsUsrStart'],
                    'FDSpsUsrExpired'      => $paDataCalendarUser['FDSpsUsrExpired'],
                    'FTSpuRemark'          => $paDataCalendarUser['FTSpuRemark']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add UserCalendar Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit UserCalendar.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Delete UserCalendar
    //Parameters : function parameters
    //Creator : 31/05/2021 Off
    //Update :
    //Return : Status Delete
    //Return Type : array
    public function FSaMCLDDelUserCalendarAll($paData){
        $this->db->where('FTSpsCode', $paData['FTSpsCode']);
        $this->db->where_in('FTUsrCode', $paData['FTUsrCode']);
        $this->db->delete('TSVMPosUser');
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Success',
            );
        }else{
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

    //Functionality : get all row data from UserCalendar
    //Parameters : -
    //Creator : 31/05/2021 Off
    //Return : array result from db
    //Return Type : array
    public function FSnMCLDGetAllUserCalendarNumRow($ptObjCode){
        $tSQL   = "SELECT COUNT(*) AS FNAllNumRow FROM TSVMPosUser WITH (NOLOCK) WHERE FTSpsCode = ".$this->db->escape($ptObjCode)." ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

        //Functionality : Get Data UserCalendar By ID
    //Parameters : function parameters
    //Creator : 31/05/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaCCLDGetDataByID($paData){
        try{
            $tUsrCode   = $paData['tUsrCode'];
            $FTSpsCode  = $paData['tCldCode'];
            $tSQL       = " 
                SELECT 
                    UCL.FTUsrCode    AS rtUsrCode,
                    UCL.FTSpsCode    AS rtObjCode,
                    UCL.FNSpuSeq     AS rtUsrSeq,
                    UCL.FTSpuRemark  AS FTSpuRemark,
                    UCL.FDSpsUsrStart  AS rtObjDutyStart,
                    UCL.FDSpsUsrExpired  AS rtObjDutyFinish,
                    USRL.FTUsrName   AS FTUsrName
                FROM [TSVMPosUser] UCL WITH (NOLOCK)
                LEFT JOIN [TCNMUser_L] USRL WITH (NOLOCK) ON USRL.FTUsrCode = UCL.FTUsrCode
                WHERE UCL.FTSpsCode  = ".$this->db->escape($FTSpsCode)." AND UCL.FTUsrCode= ".$this->db->escape($tUsrCode)."
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->row_array();
                $aResult = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : list Current UserCalendar
    //Parameters : function parameters
    //Creator :  28/05/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMCLDGetCurrentUser($ptCldCode){
        try{
            $FTSpsCode  = $ptCldCode;
            $tSQL       = " 
                SELECT 
                    UCL.FTUsrCode    AS rtUsrCode
                FROM [TSVMPosUser] UCL WITH (NOLOCK)
                WHERE UCL.FTSpsCode  = ".$this->db->escape($FTSpsCode)." ";
            $oQuery = $this->db->query($tSQL);
            return $oQuery->result();
        }catch(Exception $Error){
            echo $Error;
        }
    }
}
