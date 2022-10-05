<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPdtLot extends CI_Model {

	public function FSaMLotList($paData){ //ดึงข้อมูลมาแสดงหน้า List
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTLotNo DESC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        LOT.FTLotNo ,
                                        LOT.FTLotStaUse,
                                        LOT.FDCreateOn,
                                        LOT.FTLotBatchNo,
                                        LOT.FTAgnCode,
                                        LOT.FTLotYear,
			                            ISNULL(PLOT.FTLotNo,'') AS PLOT
                                    FROM [TCNMLot] LOT
                                    LEFT JOIN [TCNMPdtLot] PLOT ON  LOT.FTLotNo = PLOT.FTLotNo
                                    WHERE 1=1 ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                // $tSQL .= " AND (LOT.FTAgnCode IN ($tAgnCode) OR ISNULL(LOT.FTAgnCode,'') = '')";
            }

            if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                $tSQL .= " AND ((LOT.FTLotNo COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (LOT.FTLotBatchNo LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
            }

            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

            // echo $tSQL;
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMLotGetPageAll($tSearchList,$nLngID);
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

    public function FSoMLotGetPageAll($ptSearchList,$ptLngID){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List
        try{
            $tSQL = "SELECT COUNT (Lot.FTLotNo) AS counts
                     FROM [TCNMLot] LOT
                     WHERE 1=1 ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                // $tSQL .= " AND (LOT.FTAgnCode IN ($tAgnCode) OR ISNULL(LOT.FTAgnCode,'') = '')";
            }

            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND ((LOT.FTLotNo COLLATE THAI_BIN LIKE '%$ptSearchList%') OR (LOT.FTLotBatchNo LIKE '%$ptSearchList%'))";
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

    public function FSaMLOTAddUpdateMaster($paDataPdtLot){ // update และ insert ข้อมูลลง Database
        try{
            // Update TCNMPdtLot
            $this->db->where('FTLotNo', $paDataPdtLot['FTLotNo']);
            $this->db->update('TCNMLot',$paDataPdtLot);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'tCode' => '1',
                    'tDesc' => 'Update Lot Success',
                );
            }else{
                //Add TCNMPdtLot
                $this->db->insert('TCNMLot', $paDataPdtLot);
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'tCode' => '1',
                        'tDesc' => 'Add Lot Success',
                    );
                }else{
                    $aStatus = array(
                        'tCode' => '905',
                        'tDesc' => 'Error Cannot Add/Edit Lot',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSnMLOTCheckDuplicate($ptLotCode){ // เช็คข้อมูลว่าซ้ำหรือไม่
        $tSQL = "SELECT COUNT(LOT.FTLotNo) AS counts
                 FROM TCNMLot LOT
                 WHERE LOT.FTLotNo = '$ptLotCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return FALSE;
        }
    }

    public function FSaMLotGetDataByID($paData){ //ดึงข้อมูลมาแสดงในหน้า Form ขา Edit
        try{
            $tLotNo   = $paData['FTLotNo'];
            $nLngID     = $paData['FNLngID'];
            $tSQL       = " SELECT
                                Lot.FTLotNo ,
                                Lot.FTLotRemark ,
                                Lot.FTLotStaUse ,
                                Lot.FTLotBatchNo ,
                                Lot.FTLotYear ,
                                Lot.FTAgnCode   AS tAgnCode,
                                AGNL.FTAgnName  AS tAgnName
                            FROM TCNMLot Lot
                            LEFT JOIN TCNMAgency_L AGNL ON  Lot.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID  = $nLngID
                            WHERE 1=1 AND Lot.FTLotNo = '$tLotNo' ";
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

    public function FSaMLOTDelAll($paData){ //delete date mulyi and single
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTLotNo', $paData['FTLotNo']);
            $this->db->delete('TCNMLot');

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

    public function FSnMLOTGetAllNumRow(){ //นับข้อมูลหลังจากลบ
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMLot";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    public function FSaMDotBAMList($paData){ //ดึงข้อมูลมาแสดงหน้า List
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tDotNo         = $paData['tDotNo'];
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTLotNo DESC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        LOT.FDCreateOn,
                                        LOT.FTLotNo,
                                        LOT.FTPbnCode,
                                        PBN_L.FTPbnName,
                                        LOT.FTPmoCode,
                                        PMO_L.FTPmoName
                                    FROM [TCNMPdtLot] LOT
                                    LEFT JOIN [TCNMPdtBrand_L] PBN_L ON  LOT.FTPbnCode = PBN_L.FTPbnCode AND PBN_L.FNLngID = '$nLngID'
                                    LEFT JOIN [TCNMPdtModel_L] PMO_L ON  LOT.FTPmoCode = PMO_L.FTPmoCode AND PMO_L.FNLngID = '$nLngID'
                                    WHERE LOT.FTLotNo = '$tDotNo' ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                // $tSQL .= " AND (LOT.FTAgnCode IN ($tAgnCode) OR ISNULL(LOT.FTAgnCode,'') = '')";
            }

            if(isset($tSearchList) && !empty($tSearchList) || $tSearchList == 0){
                $tSQL .= " AND ((PBN_L.FTPbnName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (PMO_L.FTPmoName LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
            }

            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

            //echo $tSQL;
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMDotBAMGetPageAll($tDotNo,$tSearchList,$nLngID);
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

    public function FSoMDotBAMGetPageAll($ptDotNo,$ptSearchList,$ptLngID){ // นับข้อมูลทั้งหมดที่จะแสดงหน้า List
        try{
            $tSQL = "SELECT COUNT (Lot.FTLotNo) AS counts
                     FROM [TCNMPdtLot] LOT
                     LEFT JOIN [TCNMPdtBrand_L] PBN_L ON  LOT.FTPbnCode = PBN_L.FTPbnCode AND PBN_L.FNLngID = '$ptLngID'
                     LEFT JOIN [TCNMPdtModel_L] PMO_L ON  LOT.FTPmoCode = PMO_L.FTPmoCode AND PMO_L.FNLngID = '$ptLngID'
                     WHERE LOT.FTLotNo = '$ptDotNo' ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
                // $tSQL .= " AND (LOT.FTAgnCode IN ($tAgnCode) OR ISNULL(LOT.FTAgnCode,'') = '')";
            }

            if(isset($ptSearchList) && !empty($ptSearchList) || $ptSearchList == 0){
                $tSQL .= " AND ((PBN_L.FTPbnName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($ptSearchList)."%') OR (PMO_L.FTPmoName LIKE '%".$this->db->escape_like_str($ptSearchList)."%'))";
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

    public function FSaMLOTBAMDelAll($paData){ //delete date mulyi and single
        try{
            $this->db->trans_begin();
            $this->db->where_in('FTLotNo', $paData['FTLotNo']);
            $this->db->where_in('FTPbnCode', $paData['FTPbnCode']);
            $this->db->where_in('FTPmoCode', $paData['FTPmoCode']);
            $this->db->delete('TCNMPdtLot');
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

    public function FSnMLOTBAMGetAllNumRow(){ //นับข้อมูลหลังจากลบ
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMPdtLot";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    public function FSnMDotBAMCheckDuplicate($paDataMaster){ // เช็คข้อมูลว่าซ้ำหรือไม่
        $tDotNo = $paDataMaster['FTLotNo'];
        $tBrandNo = $paDataMaster['FTPbnCode'];
        $tModelNo = $paDataMaster['FTPmoCode'];

        $tSQL = "SELECT 
                    COUNT(LOT.FTLotNo) AS counts
                 FROM TCNMPdtLot LOT
                 WHERE LOT.FTLotNo = '$tDotNo' AND LOT.FTPbnCode = '$tBrandNo' AND LOT.FTPmoCode = '$tModelNo'
                 ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return FALSE;
        }
    }

    public function FSaMDotBAMAddUpdateMaster($paDataMaster){ // update และ insert ข้อมูลลง Database
        try{
            // Update TCNMPdtLot
            $this->db->where('FTLotNo', $paDataMaster['FTLotNo']);
            $this->db->where('FTPbnCode', $paDataMaster['FTPbnCode']);
            $this->db->where('FTPmoCode', $paDataMaster['FTPmoCode']);
            $this->db->update('TCNMPdtLot',$paDataMaster);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'tCode' => '1',
                    'tDesc' => 'Update Lot Success',
                );
            }else{
                //Add TCNMPdtLot
                $this->db->insert('TCNMPdtLot', $paDataMaster);
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'tCode' => '1',
                        'tDesc' => 'Add Lot Success',
                    );
                }else{
                    $aStatus = array(
                        'tCode' => '905',
                        'tDesc' => 'Error Cannot Add/Edit Lot',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSaMLotBAMGetDataByID($paData){ //ดึงข้อมูลมาแสดงในหน้า Form ขา Edit
        try{
            $tLotNo   = $paData['FTLotNo'];
            $tBrandNo   = $paData['FTPbnCode'];
            $tModelNo   = $paData['FTPmoCode'];
            $nLngID     = $paData['FNLngID'];

            $tSQL       = " SELECT
                                LOT.FTLotNo,
                                LOT.FTPbnCode,
                                PBN_L.FTPbnName,
                                LOT.FTPmoCode,
                                PMO_L.FTPmoName,
                                LOT.FTCreateBy
                            FROM TCNMPdtLot Lot
                            LEFT JOIN [TCNMPdtBrand_L] PBN_L ON  LOT.FTPbnCode = PBN_L.FTPbnCode AND PBN_L.FNLngID = '$nLngID'
                            LEFT JOIN [TCNMPdtModel_L] PMO_L ON  LOT.FTPmoCode = PMO_L.FTPmoCode AND PMO_L.FNLngID = '$nLngID'
                            WHERE Lot.FTLotNo = '$tLotNo' AND LOT.FTPbnCode = '$tBrandNo' AND LOT.FTPmoCode = '$tModelNo'
                        ";
            // echo $tSQL;
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

}

/* End of file mPdtLot.php */
/* Location: ./application/modules/product/models/pdtlot/mPdtLot.php */
