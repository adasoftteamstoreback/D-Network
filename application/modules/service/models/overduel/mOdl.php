<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mOdl extends CI_Model {

	public function FSaMOdlList($paData){ //ดึงข้อมูลมาแสดงหน้า List
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tSearchType    = $paData['tSearchAllType'];
            $tSQL       = "
                SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTOdlCode DESC) AS rtRowID,* FROM
                        (SELECT DISTINCT
                            ODL.FTOdlCode,
                            ODL.FTAgnCode,
                            ODL.FTOdlType,
                            ODL.FNOdlMin,
                            ODL.FNOdlMax,
                            ODL.FDCreateOn,
                            ODL.FTCreateBy
                        FROM [TCNMOverDueLev] ODL
                        WHERE 1=1
            ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                if(isset($tAgnCode) && !empty($tAgnCode)){
                    $tSQL .= " AND (ODL.FTAgnCode IN ($tAgnCode) OR ISNULL(ODL.FTAgnCode,'') = '')";
                }
            }

            if($tSearchType == 0){
                $tSQL .= "";
            }else{
                $tSQL .= " AND FTOdlType = '$tSearchType' ";
            }
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND ((ODL.FTOdlCode COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')OR (ODL.FNOdlMin LIKE '%".$this->db->escape_like_str($tSearchList)."%') or (ODL.FNOdlMax LIKE '%".$this->db->escape_like_str($tSearchList)."%') )";
            }


            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMOdlGetPageAll($tSearchList ,$tSearchType,$nLngID);
                $nFoundRow = $oFoundRow[0]->counts;
                $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult = array(
                    'aItems'       => $aList,
                    'nAllRow'      => $nFoundRow,
                    'nCurrentPage' => $paData['nPage'],
                    'nAllPage'     => $nPageAll,
                    'tCode'        => '1',
                    'tDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'nAllRowType' => 0,
                    'nAllRow' => 0,
                    'nCurrentPage' => $paData['nPage'],
                    "nAllPage"=> 0,
                    'tCode' => '800',
                    'tDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoMOdlGetPageAll($ptSearchList, $tSearchType ,$ptLngID){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List
        try{
            $tSQL = "
                SELECT COUNT (ODL.FTOdlCode) AS counts
                FROM [TCNMOverDueLev] ODL
                WHERE 1=1
            ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                if(isset($tAgnCode) && !empty($tAgnCode)){
                    $tSQL .= " AND (ODL.FTAgnCode IN ($tAgnCode) OR ISNULL(ODL.FTAgnCode,'') = '')";
                }
            }

            if($tSearchType == 0){
                $tSQL .= "";
            }else{
                $tSQL .= " AND FTOdlType = '$tSearchType' ";
            }

            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND ((ODL.FTOdlCode COLLATE THAI_BIN LIKE '%$ptSearchList%')OR (ODL.FNOdlMin LIKE '%$ptSearchList%') or (ODL.FNOdlMax LIKE '%$ptSearchList%') )";
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

    public function FSaMOdlAddUpdateMaster($paDataOdl){ // update และ insert ข้อมูลลง Database
        try{
            // Update TCNMOverDueLev
            $this->db->where('FTOdlCode', $paDataOdl['FTOdlCode']);
            $this->db->update('TCNMOverDueLev',array(

                'FTAgnCode'       => $paDataOdl['FTAgnCode'],
                'FTOdlType'       => $paDataOdl['FTOdlType'],
                'FNOdlMin'        => $paDataOdl['FNOdlMin'],
                'FNOdlMax'        => $paDataOdl['FNOdlMax'],
                'FDLastUpdOn'     => $paDataOdl['FDLastUpdOn'],
                'FTLastUpdBy'     => $paDataOdl['FTLastUpdBy'],
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'tCode' => '1',
                    'tDesc' => 'Update Odl Success',
                );
            }else{
                //Add TCNMOverDueLev
                $this->db->insert('TCNMOverDueLev', array(
                    'FTOdlCode'         => $paDataOdl['FTOdlCode'],
                    'FTAgnCode'         => $paDataOdl['FTAgnCode'],
                    'FTOdlType'         => $paDataOdl['FTOdlType'],
                    'FNOdlMin'          => $paDataOdl['FNOdlMin'],
                    'FNOdlMax'          => $paDataOdl['FNOdlMax'],
                    'FDLastUpdOn'       => $paDataOdl['FDLastUpdOn'],
                    'FTLastUpdBy'       => $paDataOdl['FTLastUpdBy'],
                    'FDCreateOn'        => $paDataOdl['FDCreateOn'],
                    'FTCreateBy'        => $paDataOdl['FTCreateBy'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'tCode' => '1',
                        'tDesc' => 'Add ODL Success',
                    );
                }else{
                    $aStatus = array(
                        'tCode' => '905',
                        'tDesc' => 'Error Cannot Add/Edit ODL',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSnMOdlCheckDuplicate($ptOdlCode){ // เช็คข้อมูลว่าซ้ำหรือไม่
        $tSQL = "SELECT COUNT(ODL.FTOdlCode) AS counts
                 FROM TCNMOverDueLev ODL
                 WHERE ODL.FTOdlCode = '$ptOdlCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return FALSE;
        }
    }

    public function FSaMOdlGetDataByID($paData){ //ดึงข้อมูลมาแสดงในหน้า Form ขา Edit
        try{
            $tOdlCode  = $paData['FTOdlCode'];
            $nLngID     = $paData['FNLngID'];
            $tSQL       = " SELECT
                                ODL.FTOdlCode ,
                                ODL.FTOdlType ,
                                ODL.FNOdlMin ,
                                ODL.FNOdlMax ,
                                ODL.FTAgnCode   AS tAgnCode,
                                AGNL.FTAgnName  AS tAgnName
                            FROM TCNMOverDueLev ODL
                            LEFT JOIN TCNMAgency_L AGNL ON  ODL.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID  = $nLngID
                            WHERE 1=1 AND ODL.FTOdlCode = '$tOdlCode ' ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->row_array();
                $aResult = array(
                    'aItems'   => $aDetail,
                    'tCode'    => '1',
                    'tDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'tCode' => '800',
                    'tDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMODLDelAll($paData){ //delete date mulyi and single
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTOdlCode', $paData['FTOdlCode']);
            $this->db->delete('TCNMOverDueLev');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'tCode' => '905',
                    'tDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'tCode' => '1',
                    'tDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSnMOdlGetAllNumRow(){ //นับข้อมูลหลังจากลบ
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMOverDueLev";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    public function FSnMOdlChkDupMinMax($paDataWhere){ // เช็คข้อมูล Max - Min กรอกซ้ำในระบบ
        $tAgnCode   = $paDataWhere['FTAgnCode'];
        $tOdlType   = $paDataWhere['FTOdlType'];
        $nOdlMin    = $paDataWhere['FNOdlMin'];
        $nOdlMax    = $paDataWhere['FNOdlMax'];
        $tSQL   = "
            SELECT COUNT(ODL.FTOdlCode) AS rtChkDupOdl
            FROM TCNMOverDueLev ODL WITH(NOLOCK)
            WHERE ODL.FTOdlType = '$tOdlType' AND ODL.FNOdlMin = '$nOdlMin' AND ODL.FNOdlMax = '$nOdlMax'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["rtChkDupOdl"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

}
