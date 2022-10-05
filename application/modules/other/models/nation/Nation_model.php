<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nation_model extends CI_Model
{

    //Functionality : list Reason
    //Parameters : function parameters
    //Creator :  08/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMNATList($ptAPIReq, $ptMethodReq, $paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSesAgnCode    = $paData['tSesAgnCode'];
        $tSQL           = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtNatCode DESC) AS rtRowID,* FROM (
                    SELECT DISTINCT
                        NAT.FTNatCode   AS rtNatCode,
                        NATL.FTNatName  AS rtNatName,
                        NAT.FDCreateOn  AS rtFDCreateOn
                    FROM [TCNMNation] NAT
                    LEFT JOIN [TCNMNation_L] NATL ON NAT.FTNatCode = NATL.FTNatCode AND NATL.FNLngID = ".$this->db->escape($nLngID)."
                    WHERE NAT.FDCreateOn <> ''
        ";
        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL   .= " AND (NAT.FTNatCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL   .= " OR NATL.FTNatName COLLATE THAI_BIN  LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > ".$this->db->escape($aRowLen[0])." AND c.rtRowID <= ".$this->db->escape($aRowLen[1])."";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMNATGetPageAll($tSearchList, $nLngID, $tSesAgnCode);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
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
        } else {
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

    //Functionality : All Page Of Reason
    //Parameters : function parameters
    //Creator :  08/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMNATGetPageAll($ptSearchList, $ptLngID, $ptSesAgnCode){
        $tSQL   = "
            SELECT COUNT (NAT.FTNatCode) AS counts
            FROM TCNMNation NAT
            LEFT JOIN [TCNMNation_L] NATL ON NAT.FTNatCode = NATL.FTNatCode AND NATL.FNLngID = ".$this->db->escape($ptLngID)."
            WHERE NAT.FDCreateOn <> ''
        ";
        if ($ptSearchList != '') {
            $tSQL   .= " AND (NAT.FTNatCode LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
            $tSQL   .= " OR NATL.FTNatName  LIKE '%".$this->db->escape_like_str($ptSearchList)."%'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            //No Data
            return false;
        }
    }

}
