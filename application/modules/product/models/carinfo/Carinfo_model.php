<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Carinfo_model extends CI_Model {

    //Functionality : Search CarInfo By ID
    //Parameters : function parameters
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCAISearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tCaiCode   = $paData['FTCaiCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                        CAI.FTCaiCode       AS rtCaiCode,
                        CAI.FTCaiStaUse     AS rtCaiStaUse,
                        CAIL.FTCaiName      AS rtCaiName,
                        CAIL.FTCaiRmk       AS rtCaiRmk,
                        CAI.FDCreateOn      AS rtFDCreateOn,
                        CAI.FTAgnCode       AS rtAgnCode,
                        CBRN.FTCaiCode      AS rtBrandCode,
                        CBRN.FTCaiName      AS rtBrandName,
                        AGNL.FTAgnName      AS rtAgnName
                    FROM [TSVMCarInfo] CAI with (NOLOCK)
                    LEFT JOIN [TSVMCarInfo_L] CAIL WITH (NOLOCK) ON CAI.FTCaiCode = CAIL.FTCaiCode AND CAIL.FNLngID = $nLngID
                    LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON CAI.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    LEFT JOIN [TSVMCarInfo_L] CBRN WITH (NOLOCK) ON CAI.FTCarParent  =  CBRN.FTCaiCode AND CBRN.FNLngID = $nLngID
                    WHERE 1=1
                    AND  CAI.FTCaiCode = '$tCaiCode' ";

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


    //Functionality : list Carinfo
    //Parameters : function parameters
    //Creator :  19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCAIList($ptAPIReq,$ptMethodReq,$paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);

        $nLngID = $paData['FNLngID'];

        $nCaiType = $paData['nCarType'];

        $tSesAgnCode = $paData['tSesAgnCode'];

        switch ($nCaiType) {
            case "1":
                $tColump = 'FTCarType';
            break;
            case "2":
                $tColump = 'FTCarBrand';
            break;
            case "3":
                $tColump = 'FTCarModel';
            break;
            case "4":
                $tColump = 'FTCarColor';
            break;
            case "5":
                $tColump = 'FTCarGear';
            break;
            case "6":
                $tColump = 'FTCarPowerType';
            break;
            case "7":
                $tColump = 'FTCarEngineSize';
            break;
            case "8":
                $tColump = 'FTCarCategory';
            break;
        }

        $tSQL = "SELECT c.* FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtCaiCode DESC) AS rtRowID,* FROM
                (SELECT DISTINCT
                    CAI.FTCaiCode       AS rtCaiCode,
                    CAIL.FTCaiName      AS rtCaiName,
                    CAIL.FTCaiRmk       AS rtCaiRmk,
                    CBRN.FTCaiCode            AS rtBrandCode,
                    ISNULL(CBRN.FTCaiName,'-')      AS rtBrandName,
                    CAI.FDCreateOn      AS rtFDCreateOn,
                    ISNULL(t.nCount,0) 	AS rtFTUsedCode
                 FROM [TSVMCarInfo] CAI WITH (NOLOCK)
                 LEFT JOIN [TSVMCarInfo_L] CAIL WITH (NOLOCK) ON CAI.FTCaiCode = CAIL.FTCaiCode AND CAIL.FNLngID = ".$this->db->escape($nLngID)."
                 LEFT JOIN (SELECT COUNT(CAR.FTCarCode) as nCount,CAR.$tColump FROM TSVMCar CAR WITH (nolock)GROUP BY CAR.$tColump) t ON CAI.FTCaiCode = t.$tColump
                 LEFT JOIN [TSVMCarInfo_L] CBRN WITH (NOLOCK) ON CAI.FTCarParent  =  CBRN.FTCaiCode AND CBRN.FNLngID = ".$this->db->escape($nLngID)."
                 WHERE 1=1 AND CAI.FTCaiType = '$nCaiType'";

        if($tSesAgnCode != ''){
            $tSQL .= "AND CAI.FTAgnCode = '$tSesAgnCode' ";
        }

        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL .= " AND (CAI.FTCaiCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR CAIL.FTCaiName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }

        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);


        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMCAIGetPageAll($tSearchList,$nLngID, $tSesAgnCode, $nCaiType);
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

    //Functionality : All Page Of CarInfo
    //Parameters : function parameters
    //Creator :  02/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMCAIGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode ,$pnCaiType){

        $tSQL = "SELECT COUNT (CAI.FTCaiCode) AS counts

                 FROM TSVMCarInfo CAI WITH (NOLOCK)
                 LEFT JOIN [TSVMCarInfo_L] CAIL WITH (NOLOCK) ON CAI.FTCaiCode = CAIL.FTCaiCode AND CAIL.FNLngID = $ptLngID
                 LEFT JOIN [TSVMCarInfo_L] CBRN WITH (NOLOCK) ON CAI.FTCarParent  =  CBRN.FTCaiCode AND CBRN.FNLngID = $ptLngID
                 WHERE 1=1 AND CAI.FTCaiType = '$pnCaiType'";

        if($ptSesAgnCode != ''){
            $tSQL  .= " AND CAI.FTAgnCode = '$ptSesAgnCode' ";
        }

        if($ptSearchList != ''){
            $tSQL .= " AND (CAI.FTCaiCode COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR CAIL.FTCaiName COLLATE THAI_BIN LIKE '%$ptSearchList%')";
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
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : Data Count Duplicate
    //Return Type : Object
    public function FSoMCAICheckDuplicate($ptCaiCode){
        $tSQL   = "SELECT COUNT(FTCaiCode)AS counts
                   FROM TSVMCarInfo WITH (NOLOCK)
                   WHERE FTCaiCode = '$ptCaiCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Get Max Seq CarInfo
    //Parameters : phw code, pos code , shw code
    //Creator : -
    //Update : 02/06/2021 Off
    //Return : max seq
    //Return Type : number
    public function FSaMCAILastSeqByShwCode($ptTypeCode,$ptAgnCode){
        $tSQL = "SELECT TOP 1 FNCaiSeq FROM TSVMCarInfo
                WHERE FTCaiType = '".$ptTypeCode."'
                AND FTAgnCode = '".$ptAgnCode."'
                ORDER BY FNCaiSeq DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            return $oQuery->row_array()["FNCaiSeq"];
        }else{
            return 0;
        }
    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 02/06/2021 Off
    //Last Modified :
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMCAIAddUpdateMaster($paData){

        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->set('FTCaiStaUse' , $paData['FTCaiStaUse']);
            $this->db->set('FNCaiSeq' , $paData['FNCaiSeq']);
            $this->db->set('FTCarParent' , $paData['FTCarParent']);
            $this->db->where('FTCaiCode', $paData['FTCaiCode']);
            $this->db->update('TSVMCarInfo');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TSVMCarInfo',array(
                    'FTCaiCode'     => $paData['FTCaiCode'],
                    'FTAgnCode'     => $paData['FTAgnCode'],
                    'FTCaiType'     => $paData['FTCaiType'],
                    'FNCaiSeq'      => $paData['FNCaiSeq'],
                    'FTCarParent'   => $paData['FTCarParent'],
                    'FTCaiStaUse'   => $paData['FTCaiStaUse'],

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
    public function FSaMCAIAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTCaiName', $paData['FTCaiName']);
            $this->db->set('FTCaiRmk', $paData['FTCaiRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTCaiCode', $paData['FTCaiCode']);
            $this->db->update('TSVMCarInfo_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TSVMCarInfo_L',array(
                    'FTCaiCode'     => $paData['FTCaiCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTCaiName'     => $paData['FTCaiName'],
                    'FTCaiRmk'      => $paData['FTCaiRmk']
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

    //Functionality : Delete CarInfo
    //Parameters : function parameters
    //Creator : 02/06/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMCAIDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTCaiCode', $paData['FTCaiCode']);
            $this->db->delete('TSVMCarInfo');

            $this->db->where_in('FTCaiCode', $paData['FTCaiCode']);
            $this->db->delete('TSVMCarInfo_L');

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

    //Functionality : get all row data from CarInfo
    //Parameters : -
    //Creator : 02/06/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMCAIGetAllNumRow($pnCarType){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TSVMCarInfo WITH (NOLOCK) WHERE FTCaiType = '$pnCarType'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }
}
