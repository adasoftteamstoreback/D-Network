<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class CustomerBranch_model extends CI_Model {

    /**
     * Functionality : List department
    */
    public function FSaMCstBchList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tCstCode   = $paData['tCstCode'];
        $tWhereCode = '';
        if(!empty($tCstCode)){
            $tWhereCode .=  " AND CLB.FTCstCode = ".$this->db->escape($tCstCode)."";
        }
        $tSQL   = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rnCbrSeq ASC) AS rtRowID,*
                FROM (
                    SELECT DISTINCT
                        CLB.FTCstCode,
                        CLB.FNCbrSeq as rnCbrSeq,
                        CLB.FTCbrBchCode,
                        CLB.FTCbrBchName,
                        CLB.FTCbrRegNo,
                        CLB.FTCbrShipTo,
                        CLB.FTCbrSoldTo,
                        CLB.FTCbrStatus
                    FROM [TCNMCstBch] CLB WITH (NOLOCK)
                    WHERE CLB.FDCreateOn <> '' $tWhereCode
        ";
        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL   .= " AND (CLB.FTCstCode COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%'";  
            $tSQL   .= " OR CLB.FTCbrBchCode COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrBchName COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrRegNo COLLATE THAI_BIN     LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrShipTo COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        $tSQL .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])."";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMCstBchGetPageAll($tWhereCode, $tSearchList, $nLngID);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            //No Data
            $aResult    = array(
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
    
    /**
     * Functionality : All Page Of Customer
     */
    public function FSnMCstBchGetPageAll($ptWhereCode, $ptSearchList, $ptLngID){
        $tSQL = "
            SELECT COUNT (CLB.FTCstCode) AS counts
            FROM [TCNMCstBch] CLB
            WHERE CLB.FDCreateOn <> '' $ptWhereCode
        ";
        if($ptSearchList != ''){
            $tSQL   .= " AND (CLB.FTCstCode COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";  
            $tSQL   .= " OR CLB.FTCbrBchCode COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrBchName COLLATE THAI_BIN   LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrRegNo COLLATE THAI_BIN     LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR CLB.FTCbrShipTo COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($ptSearchList)."%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    /**
     * Functionality : Search CstBch By ID
    */
    public function FSaMCstBchSearchByID($paData){
        $tCstCode   = $paData['FTCstCode'];
        $nCbrSeq    = $paData['FNCbrSeq'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT 
                CLB.FTCstCode,
                CLB.FNCbrSeq as rnCbrSeq,
                CLB.FTCbrBchCode,
                CLB.FTCbrBchName,
                CLB.FTCbrRegNo,
                CLB.FTCbrShipTo,
                CLB.FTCbrSoldTo,
                CLB.FTCbrStatus,
                CLB.FDCreateOn  
            FROM [TCNMCstBch] CLB WITH (NOLOCK)
            WHERE  CLB.FTCstCode = ".$this->db->escape($tCstCode)." AND CLB.FNCbrSeq = ".$this->db->escape($nCbrSeq)."
        ";
        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->row_array();
            $aResult = array(
                'raItems'       => @$oDetail,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }

        return $aResult;
    }

    /**
     * Functionality : All Page Of Customer
     */
    public function FSaMGetLastSeqCstBch($ptCstCode){
        $rnCbrLastSeq =  $this->db->where('FTCstCode',$ptCstCode)->order_by('FNCbrSeq','DESC')->limit(1)->get('TCNMCstBch')->row_array()['FNCbrSeq'];
        if(!empty($rnCbrLastSeq)){
            return  $rnCbrLastSeq;
        }else{
            return  0;
        }
    }

    /**
     * Functionality : All Page Of Customer
     */
    public function FSaCstBchInsertUpdate($paData){
        try{
            $nNumrowsCstBch =  $this->db->where('FTCstCode',$paData['FTCstCode'])->where('FNCbrSeq',$paData['FNCbrSeq'])->get('TCNMCstBch')->num_rows();
            if($nNumrowsCstBch>0){
                $this->db->where('FTCstCode',$paData['FTCstCode'])->where('FNCbrSeq',$paData['FNCbrSeq'])->update('TCNMCstBch',$paData);
            }else{
                $this->db->insert('TCNMCstBch',$paData);
            }
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '01',
                    'rtDesc' => 'Add Master Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }

    }

    /**
     * Functionality : All Page Of Customer
     */
    public function FSnMCstBchDel($paData){
        try{
            $rtCstCode  =  $paData['FTCstCode'];
            $rnCbrSeq   =  $paData['FNCbrSeq'];
            $this->db->where('FTCstCode',$rtCstCode)->where('FNCbrSeq',$rnCbrSeq)->delete('TCNMCstBch');
        }catch(Exception $Error){
            echo $Error;
        }
    }

    /**
     * Functionality : All Page Of Customer
     */
    public function FSnMCstBchDelBchAddr($paData){
        try{
            $FTCstCode      =  $paData['FTCstCode'];
            $FTAddRefNo     =  $paData['FTAddRefNo'];
            $FTAddGrpType   =  $paData['FTAddGrpType'];
            $this->db->where('FTCstCode',$FTCstCode)->where('FTAddRefNo',$FTAddRefNo)->where('FTAddGrpType',$FTAddGrpType)->delete('TCNMCstAddress_L');
        }catch(Exception $Error){
            echo $Error;
        }
    }  

}
