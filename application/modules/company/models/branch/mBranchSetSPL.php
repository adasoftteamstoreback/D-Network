<?php defined('BASEPATH') or exit('No direct script access allowed');

class mBranchSetSPL extends CI_Model {


    //ข้อมูล
    public function FSaMBranchSetsplDataList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $tBchCode   = $paData['tBchCode'];
        $tAgnCode   = $paData['tAgnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQLConcat = '';

        $tSQL   = " SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTSplCode DESC) AS rtRowID,* FROM ( ";
        $tSQLConcat   = " SELECT
                                SPLL.FTSplName , 
                                SPLL.FTSplCode ,
                                SPLSPC.FTStaUse ,
                                SPLSPC.FTBchCode 
                            FROM TCNMBchSplSpc SPLSPC WITH(NOLOCK)
                            LEFT JOIN TCNMSpl_L SPLL ON SPLSPC.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = '$nLngID'
                            WHERE SPLSPC.FTBchCode = '".$tBchCode."' AND SPLSPC.FTAgnCode = '".$tAgnCode."' ";

        $tSearchList    = $paData['tSearchAll'];
        if($tSearchList != ''){
            $tSQLConcat   .= " AND (SPLL.FTSplCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQLConcat   .= " OR SPLL.FTSplName  COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }

        $tSQL .= $tSQLConcat;
        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result();
            $nFoundRow  = $this->JSnMBranchSetsplPageAll($tSQLConcat);
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
    public function JSnMBranchSetsplPageAll($ptSQL) {
        $oQuery = $this->db->query($ptSQL);
        return $oQuery->num_rows();
    }

    //หาข้อมูลตามไอดี
    public function FSaMBranchSetsplGetDataByID($paData){
        $tBchCode   = $paData['tBchCode'];
        $tSPLCode   = $paData['tSplCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL       = " SELECT
                            SPLL.FTSplName , 
                            SPLL.FTSplCode ,
                            SPLSPC.FTStaUse ,
                            SPLSPC.FTBchCode 
                        FROM TCNMBchSplSpc SPLSPC WITH(NOLOCK)
                        LEFT JOIN TCNMSpl_L SPLL ON SPLSPC.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = '$nLngID'
                        WHERE SPLSPC.FTBchCode = '".$tBchCode."' AND SPLSPC.FTSplCode = '".$tSPLCode."' ";

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

    //เพิ่มข้อมูล
    public function FSaMBranchSetInsert($paData){
        $this->db->insert('TCNMBchSplSpc', array(
            'FTAgnCode'         => $paData['FTAgnCode'],
            'FTBchCode'         => $paData['FTBchCode'],
            'FTSplCode'         => $paData['FTSplCode'],
            'FTStaUse'          => $paData['FTStaUse']
        ));
    }

    //อัพเดทข้อมูล
    public function FSaMBranchSetSPLUpdate($paData){
        $this->db->set('FTStaUse',$paData['FTStaUse']);
        $this->db->where('FTSplCode', $paData['FTSplCode']);
        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FTAgnCode', $paData['FTAgnCode']);
        $this->db->update('TCNMBchSplSpc');
    }

    //เช็คข้อมูลซ้ำ
    public function FSaMBranchSetCheckSPLDup($paData){
        $tFTAgnCode         = $paData['FTAgnCode'];
        $tFTBchCode         = $paData['FTBchCode'];
        $tFTSplCode         = $paData['FTSplCode'];

        $tSQL = "SELECT COUNT(FTSplCode)AS counts
                FROM TCNMBchSplSpc
                WHERE FTBchCode = '$tFTBchCode' AND FTAgnCode = '$tFTAgnCode' AND FTSplCode = '$tFTSplCode' ";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    //ลบข้อมูลก่อน เพิ่มข้อมูล
    public function FSaMBranchSetDelete($paData,$ptSPLCodeOld){
        $this->db->where('FTSplCode', $ptSPLCodeOld);
        $this->db->where('FTBchCode', $paData['FTBchCode']);
        $this->db->where('FTAgnCode', $paData['FTAgnCode']);
        $this->db->delete('TCNMBchSplSpc');
    }

    //ลบข้อมูล รายการเดียว
    public function FSnMBranchSetsplDel($paData){
        $this->db->where('FTSplCode', $paData['tSplCode']);
        $this->db->where('FTBchCode', $paData['tBchCode']);
        $this->db->delete('TCNMBchSplSpc');
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }

    //ลบข้อมูล หลายรายการ
    public function FSnMBranchSetsplDelMulti($paData){
        $this->db->where('FTBchCode', $paData['tBchCode']);
        $this->db->where_in('FTSplCode', $paData['tDataDocNo']);
        $this->db->delete('TCNMBchSplSpc');
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }
}