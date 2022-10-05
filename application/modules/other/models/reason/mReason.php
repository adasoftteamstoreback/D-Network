<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mReason extends CI_Model {
    
    //Functionality : Search Reason By ID
    //Parameters : function parameters
    //Creator : 08/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMRSNSearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tRsnCode   = $paData['FTRsnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT
                RSN.FTRsnCode   AS rtRsnCode,
                RSN.FTRsgCode   AS rtRsgCode,
                RSNL.FTRsnName  AS rtRsnName,
                RSNL.FTRsnRmk   AS rtRsnRmk,
                RSN.FTAgnCode   AS rtAgnCode,
                AGNL.FTAgnName  AS rtAgnName,
                YRSL.FTRsgName  AS rtRsgName
            FROM [TCNMRsn] RSN
            LEFT JOIN [TCNMRsn_L] RSNL ON RSN.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN [TCNMAgency_L] AGNL ON RSN.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN [TSysRsnGrp_L] YRSL ON YRSL.FTRsgCode = RSN.FTRsgCode AND YRSL.FNLngID = ".$this->db->escape($nLngID)."
            WHERE RSN.FTRsnCode = ".$this->db->escape($tRsnCode)."
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
    
    //Functionality : list Reason
    //Parameters : function parameters
    //Creator :  08/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMRSNList($ptAPIReq,$ptMethodReq,$paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSesAgnCode    = $paData['tSesAgnCode'];
        $tSQL           = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtRsnCode DESC) AS rtRowID,* 
                FROM(
                    SELECT DISTINCT
                        RSN.FTRsnCode   AS rtRsnCode,
                        RSNL.FTRsnName  AS rtRsnName,
                        RSNG.FTRsgCode  AS rtRsgCode,
                        RSNG.FTRsgName  AS rtRsgName,
                        RSN.FDCreateOn  AS rtFDCreateOn,
                        RSN.FTAgnCode   AS rtAgnCode,
                        AGNL.FTAgnName  AS rtAgnName
                    FROM [TCNMRsn] RSN
                    LEFT JOIN [TCNMRsn_L] RSNL ON RSN.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID       = ".$this->db->escape($nLngID)."
                    LEFT JOIN [TSysRsnGrp_L] RSNG ON RSN.FTRsgCode = RSNG.FTRsgCode AND RSNG.FNLngID    = ".$this->db->escape($nLngID)."
                    LEFT JOIN [TCNMAgency_L] AGNL ON RSN.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID  = ".$this->db->escape($nLngID)."
                    WHERE RSN.FDCreateOn <> ''
        ";

        if($tSesAgnCode != ''){
            $tSQL .= "AND RSN.FTAgnCode = ".$this->db->escape($tSesAgnCode)."";
        }
        
        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL .= " AND (RSN.FTRsnCode COLLATE THAI_BIN  LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR RSNL.FTRsnName COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR RSNG.FTRsgName COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])."";
        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMRSNGetPageAll($tSearchList,$nLngID, $tSesAgnCode);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems' => $oList,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> $nPageAll, 
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
            $jResult    = json_encode($aResult);
            $aResult    = json_decode($jResult, true);
        }else{
            //No Data
            $aResult    = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
            $jResult    = json_encode($aResult);
            $aResult    = json_decode($jResult, true);
        }
        
        return $aResult;
    }

    //Functionality : All Page Of Reason
    //Parameters : function parameters
    //Creator :  08/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMRSNGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode){
        $tSQL   = "
            SELECT COUNT (RSN.FTRsnCode) AS counts
            FROM TCNMRsn RSN
            LEFT JOIN [TCNMRsn_L] RSNL ON RSN.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID       = ".$this->db->escape($ptLngID)."
            LEFT JOIN [TSysRsnGrp_L] RSNG ON RSN.FTRsgCode = RSNG.FTRsgCode AND RSNG.FNLngID    = ".$this->db->escape($ptLngID)."
            LEFT JOIN [TCNMAgency_L] AGNL ON RSN.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID  = ".$this->db->escape($ptLngID)."
            WHERE RSN.FDCreateOn <> ''
        ";
        if($ptSesAgnCode != ''){
            $tSQL   .= " AND RSN.FTAgnCode = ".$this->db->escape($ptSesAgnCode)."";
        }
        if($ptSearchList != ''){
            $tSQL   .= " AND (RSN.FTRsnCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR RSNL.FTRsnName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR RSNG.FTRsgName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    //Functionality : Select Data Reason Group
    //Parameters : function parameters
    //Creator :  09/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMRSNSysGroup($ptAPIReq,$ptMethodReq,$paData){
        $nLngID = $paData['FNLngID'];
        $tSQL   = "
            SELECT
                RSNG.FTRsgCode AS rtRsgCode,
                RSNG.FTRsgName AS rtRsgName,
                RSNG.FTRsgRmk  AS rtRsgRmk
            FROM [TSysRsnGrp_L] RSNG
            WHERE RSNG.FNLngID = ".$this->db->escape($nLngID)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'   => $oList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
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
    
    //Functionality : Checkduplicate
    //Parameters : function parameters
    //Creator : 10/05/2018 wasin
    //Last Modified : -
    //Return : Data Count Duplicate
    //Return Type : Object
    public function FSoMRSNCheckDuplicate($ptRsnCode){
        $tSQL   = "
            SELECT COUNT(FTRsnCode)AS counts
            FROM TCNMRsn
            WHERE FTRsnCode = ".$this->db->escape($ptRsnCode)."
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
    //Creator : 10/05/2018 wasin
    //Last Modified : 11/06/2018 wasin
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMRSNAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTRsgCode' , $paData['FTRsgCode']);
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTAgnCode', $paData['FTAgnCode']);
            $this->db->where('FTRsnCode', $paData['FTRsnCode']);
            $this->db->update('TCNMRsn');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMRsn',array(
                    'FTRsnCode'     => $paData['FTRsnCode'],
                    'FTRsgCode'     => $paData['FTRsgCode'],
                    'FTAgnCode'     => $paData['FTAgnCode'],
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

    //Functionality : Functio Add/Update Lang
    //Parameters : function parameters
    //Creator :  10/05/2018 Wasin
    //Last Modified : 11/06/2018 wasin
    //Return : Status Add Update Lang
    //Return Type : Array
    public function FSaMRSNAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTRsnName', $paData['FTRsnName']);
            $this->db->set('FTRsnRmk', $paData['FTRsnRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTRsnCode', $paData['FTRsnCode']);
            $this->db->update('TCNMRsn_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TCNMRsn_L',array(
                    'FTRsnCode'     => $paData['FTRsnCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTRsnName'     => $paData['FTRsnName'],
                    'FTRsnRmk'      => $paData['FTRsnRmk']
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

    //Functionality : Delete Reason
    //Parameters : function parameters
    //Creator : 10/05/2018 wasin
    //Return : response
    //Return Type : array
    public function FSnMRSNDel($ptAPIReq,$ptMethodReq,$paData){

        try{
            $this->db->where_in('FTRsnCode', $paData['FTRsnCode']);
            $this->db->delete('TCNMRsn');

            $this->db->where_in('FTRsnCode', $paData['FTRsnCode']);
            $this->db->delete('TCNMRsn_L');

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



    //Functionality : get all row data from pdt location
    //Parameters : -
    //Creator : 1/04/2019 Pap
    //Return : array result from db
    //Return Type : array

    public function FSnMLOCGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMRsn";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }





}