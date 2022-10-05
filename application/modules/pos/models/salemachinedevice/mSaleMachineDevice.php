<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mSaleMachineDevice extends CI_Model {

    //Functionality : list SaleMachine Device
    //Parameters : function parameters
    //Creator :  05/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMPHWList($paData){
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = $paData['tSearchAll'];
            $nPosCode       = $paData['nPosCode'];
            $nPosCode       = $paData['nPosCode'];
            $tBchCode       = $paData['tBchCode'];
            $tSQL           = "
                SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY rtPhwCode ASC) AS rtRowID,* 
                    FROM (
                        SELECT DISTINCT
                            PHW.FTBchCode  AS rtBchCode,
                            POS.FTPosCode  AS rtPosCode,
                            PHW.FTPhwCode  AS rtPhwCode,
                            POSHW.FTShwCode AS rtShwCode,
                            POSHW.FTShwName AS rtShwName,
                            PHW.FTPhwName AS rtPhwName,
                            PHW.FTPhwConnType AS rtConnType,
                            PHW.FTPhwConnRef AS rtConnRef,
                            PHW.FTPhwCodeRef AS rtCodeRef,
                            PHW.FNPhwSeq AS rtPhwSeq
                        FROM [TCNMPosHW] PHW
                        LEFT JOIN [TCNMPos] POS ON PHW.FTPhwCode = POS.FTPosCode AND PHW.FTBchCode = POS.FTBchCode
                        LEFT JOIN [TSysPosHW] POSHW ON PHW.FTShwCode  = POSHW.FTShwCode
                        WHERE PHW.FDCreateOn <> '' AND PHW.FTBchCode = ".$this->db->escape($tBchCode)."
            ";
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL   .= " AND (PHW.FTPhwCode LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
                $tSQL   .= " OR PHW.FTPhwName   LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
            }
            if(isset($nPosCode) && !empty($nPosCode)){
                $tSQL   .= " AND PHW.FTPosCode = ".$this->db->escape($nPosCode)." ";
            }
            $tSQL   .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])." ";
            $oQuery  = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
                $oFoundRow  = $this->FSoMPHWGetPageAll($tSearchList,$nPosCode,$tBchCode);
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

    //Functionality : list SaleMachine Device from TSysPosHW
    //Parameters : function parameters
    //Creator :  12/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMGetSysPosHW(){
        try{
            $tSQL  = "
                SELECT
                    TSysPosHW.FTShwCode AS rtShwCode,
                    TSysPosHW.FTShwName AS rtShwName,
                    TSysPosHW.FTShwNameEng  AS rtShwNameEng
                FROM TSysPosHW WITH(NOLOCK)
                WHERE TSysPosHW.FTShwSystem = 'AdaPos'
            ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $aResult = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    "rnAllPage"=> 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : list SaleMachine Device from TSysPrinter
    //Parameters : function parameters
    //Creator :  12/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMGetSysPrinter(){
        try{
            $tSQL   = "
                SELECT
                    TSysPortPrn.FTSppCode  AS rtSppCode,
                    TSysPortPrn.FTSppValue AS rtSppValue,
                    TSysPortPrn.FTSppRef   AS rtFTSppRef
                FROM TSysPortPrn WITH(NOLOCK)
                WHERE TSysPortPrn.FTSppType = 'PRN'
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                return $oQuery->result_array();
            }else{
                return FALSE;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : All Page Of SaleMachine Device
    //Parameters : function parameters
    //Creator :  05/11/2018 Witsarut
    //Return : object Count All SaleMachine Device
    //Return Type : Object
    public function FSoMPHWGetPageAll($ptSearchList,$pnPosCode,$ptBchCode){
        try{
            $tSQL   = "
                SELECT COUNT (PHW.FTPosCode) AS counts
                FROM [TCNMPosHW] PHW
                LEFT JOIN [TCNMPos] POS ON PHW.FTPhwCode = POS.FTPosCode AND PHW.FTBchCode = POS.FTBchCode
                LEFT JOIN [TSysPosHW] POSHW ON PHW.FTShwCode  = POSHW.FTShwCode
                WHERE PHW.FDCreateOn <> '' AND PHW.FTBchCode  = ".$this->db->escape($ptBchCode)."
            ";
            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL   .= " AND (PHW.FTPhwCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
                $tSQL   .= " OR PHW.FTPhwName   LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
            }
            if(isset($pnPosCode) && !empty($pnPosCode)){
                $tSQL   .= " AND PHW.FTPosCode = ".$this->db->escape($pnPosCode)." ";
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

    //Functionality : Get Data SaleMachine Device By ID
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMPHWGetDataByID($paData){
        try{
            $tPhwCode   = $paData['tPhwCode'];
            $nLngID     = $this->session->userdata("tLangEdit");
            $tSQL       = " 
                SELECT
                    PHW.FTPhwCode  AS rtPhwCode,
                    PHW.FTBchCode  AS rtBchCode,
                    PHW.FTPhwName AS rtPhwName,
                    PHW.FTPhwConnType AS rtConnType,
                    PHW.FTPhwConnRef AS rtConnRef,
                    PHW.FTPhwCodeRef AS rtCodeRef,
                    POS.FTPosCode  AS rtPosCode,
                    PHW.FNPhwSeq AS rtPhwSeq,
                    POSHW.FTShwCode AS rtShwCode,
                    POSHW.FTShwName AS rtShwName,
                    PRN.FTPrnName AS rtNamePrinter,
                    PHW.FTPhwCustom AS FTPhwCustom,
                    EDC.FTEdcName AS NameEDC
                FROM [TCNMPosHW] PHW
                LEFT JOIN [TCNMPos] POS ON PHW.FTPosCode = POS.FTPosCode
                LEFT JOIN [TSysPosHW] POSHW ON PHW.FTShwCode  = POSHW.FTShwCode
                LEFT JOIN [TCNMPrinter_L] PRN ON PHW.FTPhwCodeRef = PRN.FTPrnCode
                LEFT JOIN [TFNMEdc_L] EDC ON PHW.FTPhwCodeRef = EDC.FTEdcCode AND EDC.FNLngID = ".$this->db->escape($nLngID)."
                WHERE PHW.FDCreateOn <> ''
                AND PHW.FTPhwCode   = ".$this->db->escape($tPhwCode)."
                AND PHW.FTBchCode   = ".$this->db->escape($paData['FTBchCode'])."
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

    //Functionality : Get Max Seq TCNMPosHw
    //Parameters : phw code, pos code , shw code
    //Creator : -
    //Update : 03/04/2019 pap
    //Return : max seq
    //Return Type : number
    public function FSaMLastSeqByShwCode($ptPhwPosCode){
        $tSQL   = "
            SELECT TOP 1 FNPhwSeq FROM TCNMPosHW
            WHERE FTPosCode = ".$this->db->escape($ptPhwPosCode)."
            ORDER BY FNPhwSeq DESC
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            return $oQuery->row_array()["FNPhwSeq"];
        }else{
            return 0;
        }
    }



    //Functionality : Checkduplicate SaleMachine Device
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSnMPHWCheckDuplicate($ptPhwCode,$ptPosCode,$ptBchCode){
        $tSQL   = "
            SELECT COUNT(PHW.FTPhwCode) AS counts
            FROM TCNMPosHW PHW
            WHERE PHW.FTPhwCode = ".$this->db->escape($ptPhwCode)."
            AND PHW.FTBchCode   = ".$this->db->escape($ptBchCode)."
            AND PHW.FTPosCode   = ".$this->db->escape($ptPosCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()['counts'];
        }else{
            return FALSE;
        }
    }

    //Functionality : Update Product SaleMachine Device (TCNMPosHW)
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Update : 03/04/2019 Pap
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMPHWAddUpdateMaster($paDataSaleMachineDevice){
        try{
            // Update TCNMPosHW
            $this->db->where('FTBchCode', $paDataSaleMachineDevice['FTBchCode']);
            $this->db->where('FTPhwCode', $paDataSaleMachineDevice['FTPhwCode']);
            $this->db->where('FTPosCode', $paDataSaleMachineDevice['FTPosCode']);
            $this->db->update('TCNMPosHW',array(
                'FTBchCode'     => $paDataSaleMachineDevice['FTBchCode'],
                'FTPhwCode'     => $paDataSaleMachineDevice['FTPhwCode'],
                'FTShwCode'     => $paDataSaleMachineDevice['FTShwCode'],
                'FTPhwName'     => $paDataSaleMachineDevice['FTPhwName'],
                'FTPhwConnType' => $paDataSaleMachineDevice['FTPhwConnType'],
                'FTPhwConnRef'  => $paDataSaleMachineDevice['FTPhwConnRef'],
                'FTPhwCodeRef'  => $paDataSaleMachineDevice['FTPhwCodeRef'],
                'FTPhwCustom'   => $paDataSaleMachineDevice['FTPhwCustom'],
                'FDLastUpdOn'   => $paDataSaleMachineDevice['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataSaleMachineDevice['FTLastUpdBy']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update SaleMachine Success',
                );
            }else{
                //Add TCNMPosHW
                $this->db->insert('TCNMPosHW', array(
                    'FTBchCode'     => $paDataSaleMachineDevice['FTBchCode'],
                    'FTPhwCode'     => $paDataSaleMachineDevice['FTPhwCode'],
                    'FTPosCode'     => $paDataSaleMachineDevice['FTPosCode'],
                    'FTShwCode'     => $paDataSaleMachineDevice['FTShwCode'],
                    'FNPhwSeq'      => $paDataSaleMachineDevice['FNPhwSeq'],
                    'FTPhwName'     => $paDataSaleMachineDevice['FTPhwName'],
                    'FTPhwConnType' => $paDataSaleMachineDevice['FTPhwConnType'],
                    'FTPhwConnRef'  => $paDataSaleMachineDevice['FTPhwConnRef'],
                    'FTPhwCodeRef'  => $paDataSaleMachineDevice['FTPhwCodeRef'],
                    'FTPhwCustom'   => $paDataSaleMachineDevice['FTPhwCustom'],
                    'FDLastUpdOn'   => $paDataSaleMachineDevice['FDLastUpdOn'],
                    'FTLastUpdBy'   => $paDataSaleMachineDevice['FTLastUpdBy'],
                    'FDCreateOn'    => $paDataSaleMachineDevice['FDCreateOn'],
                    'FTCreateBy'    => $paDataSaleMachineDevice['FTCreateBy']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add SaleMachine Device Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit SaleMachine Device.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Update SaleMachine (TCNMPosHW)
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Return : Array Stutus Add Update
    //Return Type : array
    public function FSaMPHWAddUpdateLang($paDataSaleMachineDevice){
        try{
            //Update Sale Machine Device Lang
            $this->db->where('FTPhwCode', $paDataSaleMachineDevice['FTPhwCode']);
            $this->db->update('TCNMPosHW',array(
                'FTPhwName'     => $paDataSaleMachineDevice['FTPhwName'],
                'FTPosCode'     => $paDataSaleMachineDevice['FTPosCode']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update SaleMachine Device Lang Success.',
                );
            }else{
                //Add Sale Machine Device Lang
                $this->db->insert('TCNMPosHW', array(
                    'FTPhwCode'     => $paDataSaleMachineDevice['FTPhwCode'],
                    'FTPosCode'     => $paDataSaleMachineDevice['FTPosCode']

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add SaleMachine Device Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit SaleMachine Device Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Delete SaleMachine Device
    //Parameters : function parameters
    //Creator : 05/11/2018 Witsarut
    //Update : 03/04/2019 pap
    //Return : Status Delete
    //Return Type : array
    public function FSaMPHWDelAll($paData){
        $this->db->where_in('FTBchCode', $paData['FTBchCode']);
        $this->db->where_in('FTPhwCode', $paData['FTPhwCode']);
        $this->db->delete('TCNMPosHW');
        if($this->db->affected_rows() > 0){
            //Success
            if(FCNnHSizeOf($paData['FTBchCode']) > 0){
                for($i=0;$i<FCNnHSizeOf($paData['FTBchCode']);$i++){
                    $tSQL = "SELECT * FROM TCNMPosHW WHERE FTPosCode = '".$paData["FTPosCode"]."' AND FTBchCode = '".$paData["FTBchCode"][$i]."' ORDER BY FNPhwSeq";
                    $oQuery = $this->db->query($tSQL);
                    if($oQuery->num_rows() > 0){
                        $aResult = $oQuery->result_array();
                        for($nI=0;$nI<FCNnHSizeOf($aResult);$nI++){
                            $tSQL = "UPDATE TCNMPosHW SET
                                        FNPhwSeq = '".($nI+1)."'
                                    WHERE FTPhwCode = '".$aResult[$nI]["FTPhwCode"]."'
                                    AND FTBchCode = '$paData[FTBchCode][$i]'
                                    ";
                            $this->db->query($tSQL);
                        }
                    }
                }
            }else{
                $tSQL = "SELECT * FROM TCNMPosHW WHERE FTPosCode = '".$paData["FTPosCode"]."' AND FTBchCode = '$paData[FTBchCode]' ORDER BY FNPhwSeq";
                $oQuery = $this->db->query($tSQL);
                if($oQuery->num_rows() > 0){
                    $aResult = $oQuery->result_array();
                    for($nI=0;$nI<FCNnHSizeOf($aResult);$nI++){
                        $tSQL = "UPDATE TCNMPosHW SET
                                    FNPhwSeq = '".($nI+1)."'
                                WHERE FTPhwCode = '".$aResult[$nI]["FTPhwCode"]."'
                                AND FTBchCode = '".$paData["FTBchCode"]."'
                                ";
                        $this->db->query($tSQL);
                    }
                }
            }
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => $tSQL,
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

    //Functionality : get all row data from phw
    //Parameters : -
    //Creator : 1/04/2019 Pap
    //Return : array result from db
    //Return Type : array
    public function FSnMPHWGetAllNumRow($ptPosCode){
        $tSQL   = "
            SELECT COUNT(*) AS FNAllNumRow FROM TCNMPosHW WHERE FTPosCode   = ".$this->db->escape($ptPosCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }


    //Functionality : CheckInputGenCode
    //Parameters : - form cSaleMacinDevice
    //Creator : - 26/09/2019 Saharat(Golf)
    //Return : array
    //Return Type : array
    public function FSaMCheckInputGenCode($paData){
        $tPosCode = $paData['tPosCode'];
        $tPhwCode = $paData['tPhwCode'];

        $tSQL = "SELECT COUNT (FTPhwCode) AS nNum
        FROM TCNMPosHW
        WHERE 1=1
        AND FTPosCode = '$tPosCode'
        AND FTPhwCode = '$tPhwCode'
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            //No Data
            return false;
        }

    }

    /*
    //Functionality : get bch
    //Parameters : ptPosCode salemachine
    //Creator : - 19/2/2020  nonpawich
    //Return : object
    //Return Type : object
    */
    function FSaMGetBch($ptPosCode){
        $tPosCode   = $ptPosCode;
        $tSQL       = " SELECT FTBchCode FROM TCNMPosHW WHERE FDCreateOn <> '' AND FTPosCode = ".$this->db->escape($tPosCode)." ";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            //No Data
            return false;
        }
    }

    public function FSaMPHUpdateLastUpdate($ptPosCode,$ptBchCode){
        //Update Sale Machine Device Lang
        $this->db->where('FTPosCode', $ptPosCode);
        $this->db->where('FTBchCode', $ptBchCode);
        $this->db->update('TCNMPos',array(
            'FDLastUpdOn'     => date('Y-m-d H:i:s')
        ));
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update SaleMachine Device Lang Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add SaleMachine Device Lang Success',
            );
        }
        return $aStatus;
    }








}