<?php defined('BASEPATH') or exit('No direct script access allowed');

class mBranchSetWah extends CI_Model {


    //ข้อมูล
    public function FSaMBranchSetWahLDataList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $tBchCode   = $paData['tBchCode'];
        $tAgnCode   = $paData['tAgnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQLConcat = '';

        $tSQL   = " SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FNBchOptSeqNo DESC) AS rtRowID,* FROM ( ";
        $tSQLConcat   = " SELECT
                                BCHOPT.FTBchCode ,
                                BCHOPT.FTObjCode ,
                                BCHOPT.FNBchOptSeqNo ,
                                BCHOPT.FTBchOptValue ,
                                WAHL.FTWahCode ,
                                WAHL.FTWahName ,
                                LOBJ.FTObjName
                            FROM TCNMBranchOptions BCHOPT WITH(NOLOCK)
                            LEFT JOIN TCNMWaHouse_L WAHL ON BCHOPT.FTBchOptValue = WAHL.FTWahCode AND BCHOPT.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = '$nLngID'
                            LEFT JOIN TCNSListObj_L LOBJ ON BCHOPT.FTObjCode = LOBJ.FTObjCode AND LOBJ.FNLngID = '$nLngID'
                            WHERE 1=1
                            AND BCHOPT.FTBchCode = '".$tBchCode."' AND BCHOPT.FTAgnCode = '".$tAgnCode."' ";

        $tSearchList    = $paData['tSearchAll'];
        if($tSearchList != ''){
            $tSQLConcat   .= " AND (LOBJ.FTObjName COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQLConcat   .= " OR WAHL.FTWahName  COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }

        $tSQL .= $tSQLConcat;
        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result();
            $nFoundRow  = $this->JSnMBranchSetWahPageAll($tSQLConcat);
            $nPageAll   = ceil($nFoundRow / $paData['nRow']); 
            $aResult    = array(
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        }
        return $aResult;
    }

    //หาจำนวน
    public function JSnMBranchSetWahPageAll($ptSQL) {
        $oQuery = $this->db->query($ptSQL);
        return $oQuery->num_rows();
    }

    //ข้อมูลชื่อสาขา + ชื่อตัวแทนขาย
    public function FSaMBranchSetWahLGetDetailName($paData){
        $tBchCode       = $paData['tBchCode'];
        $tAgnCode       = $paData['tAgnCode'];
        $nLngID         = $paData['FNLngID'];
        $tSQL = "SELECT
                    BCHL.FTBchName,
                    AGNL.FTAgnName
                FROM
                    TCNMBranch_L BCHL
                LEFT JOIN TCNMBranch BCH ON BCHL.FTBchCode = BCH.FTBchCode
                LEFT JOIN TCNMAgency_L AGNL ON BCH.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = '$nLngID' ";
        $tSQL .= " WHERE BCH.FTBchCode = '$tBchCode' AND BCHL.FNLngID = '$nLngID' ";

        if($tAgnCode != ''){
            $tSQL .= " AND BCH.FTAgnCode = '$tAgnCode' ";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    //หาข้อมูลตามไอดี
    public function FSaMBranchSetWahLGetDataByID($paData){
        $tBchCode   = $paData['tBchCode'];
        $tAgnCode   = $paData['tAgnCode'];
        $nSeq       = $paData['nSeq'];
        $tWah       = $paData['tWah'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                            BCHOPT.FTBchCode ,
                            BCHOPT.FTObjCode ,
                            BCHOPT.FNBchOptSeqNo ,
                            BCHOPT.FTBchOptValue ,
                            WAHL.FTWahCode ,
                            WAHL.FTWahName ,
                            LOBJ.FTObjName
                        FROM TCNMBranchOptions BCHOPT WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse_L WAHL ON BCHOPT.FTBchOptValue = WAHL.FTWahCode AND BCHOPT.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = '$nLngID'
                        LEFT JOIN TCNSListObj_L LOBJ ON BCHOPT.FTObjCode = LOBJ.FTObjCode AND LOBJ.FNLngID = '$nLngID'
                        WHERE 1=1
                        AND BCHOPT.FTBchCode = '".$tBchCode."' AND BCHOPT.FTAgnCode = '".$tAgnCode."' AND BCHOPT.FNBchOptSeqNo= '".$nSeq."' AND BCHOPT.FTBchOptValue= '".$tWah."' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาลำดับล่าสุด
    public function FSaMBranchSetGetSeqlast($paData){
        $tAgnCode   = $paData['tAgnCode'];
        $tBchCode   = $paData['tBchCode'];
        $tSQL       = " SELECT 
                            MAX(DT.FNBchOptSeqNo) AS FNBchOptSeqNo
                        FROM TCNMBranchOptions DT WITH (NOLOCK)
                        WHERE 1=1 
                        AND DT.FTAgnCode = '$tAgnCode'
                        AND DT.FTBchCode = '$tBchCode'
                    ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['FNBchOptSeqNo'];
        }else{
            $nResult    = 0;
        }
        return empty($nResult)? 0 : $nResult;
    }

    //เพิ่มข้อมูล
    public function FSaMBranchSetInsert($paData){
        $this->db->insert('TCNMBranchOptions', array(
            'FTAgnCode'         => $paData['FTAgnCode'],
            'FTBchCode'         => $paData['FTBchCode'],
            'FTObjCode'         => $paData['FTObjCode'],
            'FNBchOptSeqNo'     => $paData['FNBchOptSeqNo'],
            'FTBchOptValue'     => $paData['FTBchOptValue']
        ));
    }

    //อัพเดทข้อมูล
    public function FSaMBranchSetUpdate($paData){
        $this->db->set('FTObjCode',$paData['FTObjCode']);
        $this->db->set('FTBchOptValue',$paData['FTBchOptValue']);
        $this->db->where('FNBchOptSeqNo', $paData['FNBchOptSeqNo']);
        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FTAgnCode', $paData['FTAgnCode']);
        $this->db->update('TCNMBranchOptions');
    }

    //เช็คข้อมูลซ้ำ
    public function FSaMBranchSetCheckDup($paData){
        $tFTAgnCode         = $paData['FTAgnCode'];
        $tFTBchCode         = $paData['FTBchCode'];
        $tFTBchOptValue     = $paData['FTBchOptValue'];
        $tFTObjCode         = $paData['FTObjCode'];

        $tSQL = "SELECT COUNT(FTBchCode)AS counts
                FROM TCNMBranchOptions
                WHERE FTBchCode = '$tFTBchCode' AND FTAgnCode = '$tFTAgnCode' AND FTBchOptValue = '$tFTBchOptValue' AND FTObjCode = '$tFTObjCode'  ";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    //ลบข้อมูล
    public function FSnMBranchSetDel($paData){
        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FNBchOptSeqNo', $paData['FNBchOptSeqNo']);
        $this->db->where('FTBchOptValue', $paData['FTBchOptValue']);
        $this->db->delete('TCNMBranchOptions');
        
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }
}