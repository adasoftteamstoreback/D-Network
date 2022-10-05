<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Message_model extends CI_Model {

    //Functionality : Search Calendar By ID
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMMSGSearchByID($ptAPIReq,$ptMethodReq,$paData){
        $tMshCode   = $paData['FTMshCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                MSH.FTMshCode       AS rtMshCode,
                MSH.FDMshStart      AS rtMshStart,
                MSH.FDMshFinish     AS rtMshFinish,
                MSH.FTMshStaActive  AS rtMshStaActive,
                MSHL.FTMshName      AS rtMshName,
                MSHL.FTMshRmk       AS rtMshRmk,
                MSH.FDCreateOn      AS rtFDCreateOn,
                MSH.FTAgnCode       AS rtAgnCode,
                AGNL.FTAgnName      AS rtAgnName
            FROM [TCNMMsgHD] MSH with (NOLOCK)
            LEFT JOIN [TCNMMsgHD_L] MSHL WITH (NOLOCK) ON MSH.FTMshCode = MSHL.FTMshCode AND MSHL.FNLngID       = ".$this->db->escape($nLngID)."
            LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON MSH.FTAgnCode  =  AGNL.FTAgnCode AND AGNL.FNLngID    = ".$this->db->escape($nLngID)."
            WHERE MSH.FDCreateOn <> ''
            AND  MSH.FTMshCode  = ".$this->db->escape($tMshCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        // Detail
        $tDTSQL =   "
            SELECT
                MSD.FNMsdSeq     AS rtFNMsdSeq,
                MSD.FTMsdType    AS rtMsdType,
                MSD.FTMsdValue   AS rtMsdValue
            FROM [TCNMMsgDT] MSD WITH(NOLOCK)
            WHERE MSD.FTMshCode = ".$this->db->escape($tMshCode)."
            ORDER BY MSD.FNMsdSeq
        ";
        $oDTQuery   = $this->db->query($tDTSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $oDTDetail  = $oDTQuery->result();
            $aDetailMessage = [];
            foreach ($oDTDetail as $nIndex => $oItem){
                $aDetailMessage[$nIndex]['Value']   = $oItem->rtMsdValue;
                $aDetailMessage[$nIndex]['Type']    = $oItem->rtMsdType;
                $aDetailMessage[$nIndex]['Seq']     = $oItem->rtFNMsdSeq;
            }
            $aResult    = array(
                'raItems'           => $oDetail[0],
                'rtCode'            => '1',
                'raDetailMessage'   => $aDetailMessage,
                'rtDesc'            => 'success',
            );
        }else{
            //Not Found
            $aResult    = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : list Message
    //Parameters : function parameters
    //Creator :  04/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMMSGList($ptAPIReq,$ptMethodReq,$paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSesAgnCode    = $paData['tSesAgnCode'];
        $tSQL   = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtMshCode DESC) AS rtRowID,* FROM (
                    SELECT DISTINCT
                        MSHD.FTMshCode              AS rtMshCodeLeft,
                        MSG.FTMshCode               AS rtMshCode,
                        MSGL.FTMshName              AS rtMshName,
                        MSGL.FTMshRmk               AS rtMshRmk,
                        MSG.FDCreateOn              AS rtFDCreateOn
                    FROM [TCNMMsgHD] MSG WITH (NOLOCK)
                    LEFT JOIN [TCNMMsgHD_L] MSGL WITH (NOLOCK) ON MSG.FTMshCode = MSGL.FTMshCode AND MSGL.FNLngID   = ".$this->db->escape($nLngID)."
                    LEFT JOIN (SELECT DISTINCT FTMshCode FROM TCNMSlipMsgHD_L WITH(NOLOCK) ) MSHD ON MSG.FTMshCode  = MSHD.FTMshCode
                    LEFT JOIN [TCNMMsgDT] MSD WITH (NOLOCK) ON MSG.FTMshCode = MSD.FTMshCode AND MSGL.FNLngID       = ".$this->db->escape($nLngID)."
                    WHERE MSG.FDCreateOn <> ''
        ";
        if($tSesAgnCode != ''){
            $tSQL   .= "AND MSG.FTAgnCode = ".$this->db->escape($tSesAgnCode)." ";
        }

        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL   .= " AND (MSG.FTMshCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR MSGL.FTMshName COLLATE THAI_BIN  LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])." ";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMMSGGetPageAll($tSearchList,$nLngID, $tSesAgnCode);
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

    //Functionality : All Page Of Message
    //Parameters : function parameters
    //Creator :  08/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMMSGGetPageAll($ptSearchList,$ptLngID ,$ptSesAgnCode){
        $tSQL = "
            SELECT COUNT (MSG.FTMshCode) AS counts
            FROM [TCNMMsgHD] MSG WITH (NOLOCK)
            LEFT JOIN [TCNMMsgHD_L] MSGL WITH (NOLOCK) ON MSG.FTMshCode = MSGL.FTMshCode AND MSGL.FNLngID = ".$this->db->escape($ptLngID)."
            WHERE MSG.FDCreateOn <> ''
        ";

        if($ptSesAgnCode != ''){
            $tSQL   .= " AND MSG.FTAgnCode = ".$this->db->escape($ptSesAgnCode)." ";
        }

        if($ptSearchList != ''){
            $tSQL   .= " AND (MSGL.FTMshCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR MSGL.FTMshName   LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
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
    public function FSoMMSGCheckDuplicate($ptMsgCode){
        $tSQL   = "
            SELECT COUNT(FTMshCode)AS counts
            FROM TCNMMsgHD WITH (NOLOCK)
            WHERE FTMshCode = ".$this->db->escape($ptMsgCode)."
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
    public function FSaMMSGAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTMshStaActive' , $paData['FTMshStaActive']);
            $this->db->set('FDMshStart' , $paData['FDMshStart']);
            $this->db->set('FDMshFinish' , $paData['FDMshFinish']);
            $this->db->set('FTAgnCode' , $paData['FTAgnCode']);
            $this->db->where('FTMshCode', $paData['FTMshCode']);
            $this->db->update('TCNMMsgHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMMsgHD',array(
                    'FTMshCode'         => $paData['FTMshCode'],
                    'FTMshStaActive'    => $paData['FTMshStaActive'],
                    'FTAgnCode'         => $paData['FTAgnCode'],
                    'FDMshFinish'       => $paData['FDMshFinish'],
                    'FDMshStart'        => $paData['FDMshStart'],
                    'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                    'FDLastUpdOn'   => $paData['FDLastUpdOn'],
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
    public function FSaMMSGAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTMshName', $paData['FTMshName']);
            $this->db->set('FTMshRmk', $paData['FTMshRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTMshCode', $paData['FTMshCode']);
            $this->db->update('TCNMMsgHD_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                // Add Lang
                $this->db->insert('TCNMMsgHD_L',array(
                    'FTMshCode'     => $paData['FTMshCode'],
                    'FNLngID'       => $paData['FNLngID'],
                    'FTMshName'     => $paData['FTMshName'],
                    'FTMshRmk'      => $paData['FTMshRmk']
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

    //Functionality : Function Add/Update Detail
    //Parameters : function parameters
    //Creator :  07/06/2021 Off
    //Last Modified :
    //Return : Status Add Update Detail
    //Return Type : Array
    public function FSaMMSGAddUpdateDetail($paData){
        try{
            // Add Detail
            $this->db->insert('TCNMMsgDT', array(
                'FTMshCode'   => $paData['FTMshCode'],
                'FTMsdValue'  => $paData['FTMsdValue'],
                'FTMsdType'   => $paData['FTMsdType'],
                'FNMsdSeq'    => $paData['FNMsdSeq']
            ));

            // Set Response status
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

            // Response status
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Function Clear Detail
    //Parameters : function parameters
    //Creator :  09/06/2021 Off
    //Last Modified :
    //Return : Status Add Update Detail
    //Return Type : Array
    public function FSnMMSGDelDT($paData){
        $this->db->where('FTMshCode', $paData['FTMshCode']);
        $this->db->delete('TCNMMsgDT');
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }

    //Functionality : Delete Message
    //Parameters : function parameters
    //Creator : 19/05/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMMSGDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTMshCode', $paData['FTMshCode']);
            $this->db->delete('TCNMMsgHD');

            $this->db->where_in('FTMshCode', $paData['FTMshCode']);
            $this->db->delete('TCNMMsgHD_L');

            $this->db->where_in('FTMshCode', $paData['FTMshCode']);
            $this->db->delete('TCNMMsgDT');

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

    //Functionality : get all row data from Message
    //Parameters : -
    //Creator : 19/05/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMMSGGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMMsgHD WITH (NOLOCK)";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : Get Max Seq CalendarUser
    //Parameters : phw code, pos code , shw code
    //Creator : -
    //Update : 28/05/2021 pap
    //Return : max seq
    //Return Type : number
    public function FSaMMGSLastSeqByDetail(){
        $tSQL = "SELECT TOP 1 FNMsdSeq FROM TCNMMsgDT
                ORDER BY FNMsdSeq DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            return $oQuery->row_array()["FNMsdSeq"];
        }else{
            return 0;
        }
    }

}
