<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Qassubgroup_model extends CI_Model {

    //Functionality : Search QasSubGroup By ID
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMQSGSearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tQSGCode   = $paData['FTQSGCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                        Qsg.FTQsgCode       AS rtQsgCode,
                        QsgL.FTQsgName      AS rtQsgName,
                        QsgL.FTQsgRmk       AS rtQsgRmk,
                        Qsg.FDCreateOn      AS rtFDCreateOn,
                        Qsg.FTAgnCode       AS rtAgnCode,
                        AGNL.FTAgnName  AS rtAgnName
                    FROM [TCNMQasSubGrp] Qsg with (NOLOCK)
                    LEFT JOIN [TCNMQasSubGrp_L] QsgL WITH (NOLOCK) ON Qsg.FTQsgCode = QsgL.FTQsgCode AND QsgL.FNLngID = $nLngID
                    LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON Qsg.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    WHERE 1=1
                    AND  Qsg.FTQsgCode = '$tQSGCode' ";

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


    //Functionality : list QasSubGroup
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMQSGList($ptAPIReq,$ptMethodReq,$paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);

        $nLngID = $paData['FNLngID'];

        $tSesAgnCode = $paData['tSesAgnCode'];

        $tSQL = "SELECT c.* FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtQsgCode DESC) AS rtRowID,* FROM
                (SELECT DISTINCT
                    Qsg.FTQsgCode       AS rtQsgCode,
                    QsgL.FTQsgName      AS rtQsgName,
                    QsgL.FTQsgRmk       AS rtQsgRmk,
                    Qsg.FDCreateOn      AS rtFDCreateOn,
                    ISNULL(t.nCountQuestion,0) 	AS rtQahCount
                 FROM [TCNMQasSubGrp] Qsg WITH (NOLOCK)
                 LEFT JOIN [TCNMQasSubGrp_L] QsgL WITH (NOLOCK) ON Qsg.FTQsgCode = QsgL.FTQsgCode AND QsgL.FNLngID = ".$this->db->escape($nLngID)."
                 LEFT JOIN (SELECT COUNT(QAH.FTQsgCode) as nCountQuestion,QAH.FTQsgCode FROM TCNTQaHD QAH WITH (nolock)GROUP BY QAH.FTQsgCode) t ON Qsg.FTQsgCode = t.FTQsgCode
                 WHERE 1=1 ";

        if($tSesAgnCode != ''){
            $tSQL .= "AND Qsg.FTAgnCode = '$tSesAgnCode' ";
        }

        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL .= " AND (Qsg.FTQsgCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR QsgL.FTQsgName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }

        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMQSGGetPageAll($tSearchList,$nLngID, $tSesAgnCode);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems' => $oList,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> $nPageAll,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        }else{
            //No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        }

        return $aResult;
    }

    //Functionality : All Page Of QasSubGroup
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMQSGGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode){

        $tSQL = "SELECT COUNT (Qsg.FTQsgCode) AS counts

                 FROM TCNMQasSubGrp Qsg WITH (NOLOCK)
                 LEFT JOIN [TCNMQasSubGrp_L] QsgL WITH (NOLOCK) ON Qsg.FTQsgCode = QsgL.FTQsgCode AND QsgL.FNLngID = $ptLngID
                 WHERE 1=1 ";

        if($ptSesAgnCode != ''){
            $tSQL  .= " AND Qsg.FTAgnCode = '$ptSesAgnCode' ";
        }

        if($ptSearchList != ''){
            $tSQL .= " AND (Qsg.FTQsgCode LIKE '%$ptSearchList%'";
            $tSQL .= " OR QsgL.FTQsgName LIKE '%$ptSearchList%')";
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
    public function FSoMQSGCheckDuplicate($ptQsgCode){
        $tSQL   = "SELECT COUNT(FTQsgCode)AS counts
                   FROM TCNMQasSubGrp WITH (NOLOCK)
                   WHERE FTQsgCode = '$ptQsgCode' ";
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
    public function FSaMQSGAddUpdateMaster($paData){

        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->where('FTQsgCode', $paData['FTQsgCode']);
            $this->db->update('TCNMQasSubGrp');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMQasSubGrp',array(
                    'FTQsgCode'     => $paData['FTQsgCode'],
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

    //Functionality : Function Add/Update Lang
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified :
    //Return : Status Add Update Lang
    //Return Type : Array
    public function FSaMQSGAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTQsgName', $paData['FTQsgName']);
            $this->db->set('FTQsgRmk', $paData['FTQsgRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTQsgCode', $paData['FTQsgCode']);
            $this->db->update('TCNMQasSubGrp_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TCNMQasSubGrp_L',array(
                    'FTQsgCode'     => $paData['FTQsgCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTQsgName'     => $paData['FTQsgName'],
                    'FTQsgRmk'      => $paData['FTQsgRmk']
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

    //Functionality : Delete QasSubGroup
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMQSGDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTQsgCode', $paData['FTQsgCode']);
            $this->db->delete('TCNMQasSubGrp');

            $this->db->where_in('FTQsgCode', $paData['FTQsgCode']);
            $this->db->delete('TCNMQasSubGrp_L');

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

    //Functionality : get all row data from QasSubGroup
    //Parameters : -
    //Creator : 19/05/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMQSGGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMQasSubGrp WITH (NOLOCK)";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }
}
