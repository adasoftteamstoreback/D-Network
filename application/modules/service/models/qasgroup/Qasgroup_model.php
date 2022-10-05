<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Qasgroup_model extends CI_Model {
    
    //Functionality : Search QasGroup By ID
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMGPGSearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tQgpCode   = $paData['FTQgpCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                        QGP.FTQgpCode       AS rtQgpCode,
                        QGPL.FTQgpName      AS rtQgpName,
                        QGPL.FTQgpRmk       AS rtQgpRmk,
                        QGP.FDCreateOn      AS rtFDCreateOn,
                        QGP.FTAgnCode       AS rtAgnCode,
                        AGNL.FTAgnName  AS rtAgnName
                    FROM [TCNMQasGrp] QGP with (NOLOCK)
                    LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGP.FTQgpCode = QGPL.FTQgpCode AND QGPL.FNLngID = $nLngID
                    LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON QGP.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    WHERE 1=1 
                    AND  QGP.FTQgpCode = '$tQgpCode' ";
        
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
    
    
    //Functionality : list QasGroup
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMQGPList($ptAPIReq,$ptMethodReq,$paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        
        $nLngID = $paData['FNLngID'];

        $tSesAgnCode = $paData['tSesAgnCode'];

        $tSQL = "SELECT c.* FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtQgpCode DESC) AS rtRowID,* FROM
                (SELECT DISTINCT
                    QGP.FTQgpCode       AS rtQgpCode,
                    QGPL.FTQgpName      AS rtQgpName,
                    QGPL.FTQgpRmk       AS rtQgpRmk,
                    QGP.FDCreateOn      AS rtFDCreateOn,
                    ISNULL(t.nCountQuestion,0) 	AS rtQahCount
                 FROM [TCNMQasGrp] QGP WITH (NOLOCK)
                 LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGP.FTQgpCode = QGPL.FTQgpCode AND QGPL.FNLngID = $nLngID
                 LEFT JOIN (SELECT COUNT(QAH.FTQgpCode) as nCountQuestion,QAH.FTQgpCode FROM TCNTQaHD QAH WITH (nolock)GROUP BY QAH.FTQgpCode) t ON QGP.FTQgpCode = t.FTQgpCode 
                 WHERE 1=1 ";

        if($tSesAgnCode != ''){
            $tSQL .= "AND QGP.FTAgnCode = '$tSesAgnCode' ";
        }
        
        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL .= " AND (QGP.FTQgpCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= " OR QGPL.FTQgpName COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMQGPGetPageAll($tSearchList,$nLngID, $tSesAgnCode);
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

    //Functionality : All Page Of QasGroup
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMQGPGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode){
        
        $tSQL = "SELECT COUNT (QGP.FTQgpCode) AS counts

                 FROM TCNMQasGrp QGP WITH (NOLOCK)
                 LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGP.FTQgpCode = QGPL.FTQgpCode AND QGPL.FNLngID = $ptLngID
                 WHERE 1=1 ";
                 
        if($ptSesAgnCode != ''){
            $tSQL  .= " AND QGP.FTAgnCode = '$ptSesAgnCode' ";
        }
                 
        if($ptSearchList != ''){
            $tSQL .= " AND (QGP.FTQgpCode LIKE '%$ptSearchList%'";
            $tSQL .= " OR QGPL.FTQgpName LIKE '%$ptSearchList%')";
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
    public function FSoMQGPCheckDuplicate($ptQgpCode){
        $tSQL   = "SELECT COUNT(FTQgpCode)AS counts
                   FROM TCNMQasGrp WITH (NOLOCK)
                   WHERE FTQgpCode = '$ptQgpCode' ";
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
    public function FSaMQGPAddUpdateMaster($paData){

        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->where('FTQgpCode', $paData['FTQgpCode']);
            $this->db->update('TCNMQasGrp');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMQasGrp',array(
                    'FTQgpCode'     => $paData['FTQgpCode'],
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
    public function FSaMQGPAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTQgpName', $paData['FTQgpName']);
            $this->db->set('FTQgpRmk', $paData['FTQgpRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTQgpCode', $paData['FTQgpCode']);
            $this->db->update('TCNMQasGrp_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TCNMQasGrp_L',array(
                    'FTQgpCode'     => $paData['FTQgpCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTQgpName'     => $paData['FTQgpName'],
                    'FTQgpRmk'      => $paData['FTQgpRmk']
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

    //Functionality : Delete QasGroup
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMQGPDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTQgpCode', $paData['FTQgpCode']);
            $this->db->delete('TCNMQasGrp');

            $this->db->where_in('FTQgpCode', $paData['FTQgpCode']);
            $this->db->delete('TCNMQasGrp_L');

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

    //Functionality : get all row data from QasGroup
    //Parameters : -
    //Creator : 19/05/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMQGPGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMQasGrp WITH (NOLOCK)";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }
}