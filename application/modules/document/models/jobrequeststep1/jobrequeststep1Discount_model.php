<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class jobrequeststep1Discount_model extends CI_Model {

    // Functionality    : Get Data HD Dis List
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1GetDisChgHDList($paDataCondition){
        $aRowLen        = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $tDocNo         = $paDataCondition['tDocNo'];
        $tBchCode       = $paDataCondition['tBchCode'];
        $tSessionID     = $paDataCondition['tSessionID'];

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FTSessionID ASC) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        HDDISTMP.FTBchCode,
                                        HDDISTMP.FTXthDocNo,
                                        HDDISTMP.FDXtdDateIns,
                                        HDDISTMP.FTXtdDisChgTxt,
                                        HDDISTMP.FTXtdDisChgType,
                                        HDDISTMP.FCXtdTotalAfDisChg,
                                        HDDISTMP.FCXtdTotalB4DisChg,
                                        HDDISTMP.FCXtdDisChg,
                                        HDDISTMP.FCXtdAmt,
                                        HDDISTMP.FTSessionID,
                                        HDDISTMP.FTLastUpdBy,
                                        HDDISTMP.FTCreateBy,
                                        CONVERT(CHAR(5), HDDISTMP.FDLastUpdOn,108)  AS FDLastUpdOn,
                                        CONVERT(CHAR(5), HDDISTMP.FDCreateOn,108)   AS FDCreateOn
                                    FROM TSVTJRQHDDisTmp HDDISTMP WITH (NOLOCK)
                                    WHERE 1=1 
                                    AND HDDISTMP.FTSessionID    = '$tSessionID'
                                    AND HDDISTMP.FTBchCode      = '$tBchCode'
                                    AND HDDISTMP.FTXthDocNo     = '$tDocNo' " ;
        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMJR1DisChgCountPageHDDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Functionality    : Data Get Data HD Dis List All
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSnMJR1DisChgCountPageHDDocListAll($paDataCondition){
        $aRowLen        = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $tDocNo         = $paDataCondition['tDocNo'];
        $tBchCode       = $paDataCondition['tBchCode'];
        $tSessionID     = $paDataCondition['tSessionID'];

        $tSQL   =   "   SELECT
                            COUNT (HDDISTMP.FTXthDocNo) AS counts
                        FROM TSVTJRQHDDisTmp HDDISTMP WITH (NOLOCK)
                        WHERE 1=1 
                        AND HDDISTMP.FTSessionID    = '$tSessionID'
                        AND HDDISTMP.FTBchCode      = '$tBchCode'
                        AND HDDISTMP.FTXthDocNo     = '$tDocNo' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }
 
    // Functionality    : Get Data DT Dis List
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1GetDisChgDTList($paDataCondition){
        $aRowLen    = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $tDocNo     = $paDataCondition['tDocNo'];
        $nSeqNo     = $paDataCondition['nSeqNo'];
        $tBchCode   = $paDataCondition['tBchCode'];
        $tSessionID = $paDataCondition['tSessionID'];

        $tSQL = "   SELECT c.* FROM(
                        SELECT ROW_NUMBER() OVER(ORDER BY FTSessionID ASC) AS FNRowID,* 
                        FROM
                            (SELECT DISTINCT
                                DTDISTMP.FTBchCode,
                                DTDISTMP.FTXthDocNo,
                                DTDISTMP.FNXtdSeqNo,
                                DTDISTMP.FTSessionID,
                                DTDISTMP.FDXtdDateIns,
                                DTDISTMP.FNXtdStaDis,
                                DTDISTMP.FTXtdDisChgType,
                                DTDISTMP.FCXtdNet,
                                DTDISTMP.FCXtdValue,
                                DTDISTMP.FTLastUpdBy,
                                DTDISTMP.FTCreateBy,
                                DTDISTMP.FDLastUpdOn,
                                DTDISTMP.FDCreateOn,
                                DTDISTMP.FTXtdDisChgTxt
                            FROM TSVTJRQDTDisTmp DTDISTMP WITH (NOLOCK)
                            WHERE DTDISTMP.FNXtdStaDis = 1
                            AND DTDISTMP.FTSessionID    = '$tSessionID'
                            AND DTDISTMP.FNXtdSeqNo     = $nSeqNo    
                            AND DTDISTMP.FTBchCode      = '$tBchCode'
                            AND DTDISTMP.FTXthDocNo     = '$tDocNo'
                            )" ;
        $tSQL .=  " Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMJR1DisChgCountPageDTDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Functionality    : Data Get Data DT Dis Page All
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSnMJR1DisChgCountPageDTDocListAll($paDataCondition){
        $tDocNo     = $paDataCondition['tDocNo'];
        $tBchCode   = $paDataCondition['tBchCode'];
        $tSessionID = $paDataCondition['tSessionID'];

        $tSQL = "   SELECT COUNT (DTDISTMP.FTXthDocNo) AS counts
                        FROM TSVTJRQDTDisTmp DTDISTMP WITH (NOLOCK)
                        WHERE DTDISTMP.FTSessionID = '$tSessionID'
                        AND DTDISTMP.FTBchCode = '$tBchCode'
                        AND DTDISTMP.FTXthDocNo = '$tDocNo' ";
        
        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Functionality    : ลบก่อนเพิ่มใหม่
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1DeleteDTDisTemp($paParams){
        $tDocNo       = $paParams['tDocNo'];
        $nSeqNo       = $paParams['nSeqNo'];
        $tBchCode     = $paParams['tBchCode'];
        $nStaDis      = $paParams['nStaDis'];
        $tSessionID   = $paParams['tSessionID'];
        $this->db->where_in('FTSessionID',$tSessionID);
        if(isset($nSeqNo) && !empty($nSeqNo)){
            $this->db->where_in('FNXtdSeqNo',$nSeqNo);
        }
        $this->db->where_in('FTBchCode',$tBchCode);
        $this->db->where_in('FTXthDocNo',$tDocNo);
        if(isset($nStaDis) && !empty($nStaDis)){
            $this->db->where_in('FNXtdStaDis',$nStaDis);
        }
        $this->db->delete('TSVTJRQDTDisTmp');
        return;
    }

    // Functionality    : อัพเดท Text ส่วนลดก่อน ที่จะทำใหม่อีกครั้ง
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1ClearDisChgTxtDTTemp($paParams){
        $tDocNo       = $paParams['tDocNo'];
        $nSeqNo       = $paParams['nSeqNo'];
        $tBchCode     = $paParams['tBchCode'];
        $tSessionID   = $paParams['tSessionID'];
        
        // ลบ ใน Temp
        $this->db->set('FTXtdDisChgTxt', '');
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$nSeqNo);
        $this->db->where_in('FTBchCode',$tBchCode);
        $this->db->where_in('FTXthDocNo',$tDocNo);
        $this->db->update('TSVTJRQDocDTTmp');
        return;
    }

    // Functionality    : เพิ่มข้อมูลส่วนลดรายการ
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1AddEditDTDisTemp($paDataInsert){
        $this->db->insert_batch('TSVTJRQDTDisTmp',$paDataInsert);
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Not Insert DT Dis Temp.',
            );
        }
        return $aStatus;
    }

    // Functionality    : อัพเดท Text ส่วนลดให้
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1UpdateDTDisInTemp($paDataInsert){
        $tDocNo       = $paDataInsert[0]['FTXthDocNo'];
        $nSeqNo       = $paDataInsert[0]['FNXtdSeqNo'];
        $tBchCode     = $paDataInsert[0]['FTBchCode'];
        $tSessionID   = $paDataInsert[0]['FTSessionID'];
        $tDisChgTxt     = '';

        for($i=0; $i<FCNnHSizeOf($paDataInsert); $i++){
            $tDisChgTxt  .= $paDataInsert[$i]['FTXtdDisChgTxt'] . ',';

            //ถ้าเป็นตัวท้ายให้ลบ comma ออก
            if($i == FCNnHSizeOf($paDataInsert) - 1 ){
                $tDisChgTxt = substr($tDisChgTxt,0,-1);
            }
        }

        $this->db->set('FTXtdDisChgTxt', $tDisChgTxt);
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$nSeqNo);
        $this->db->where_in('FTBchCode',$tBchCode);
        $this->db->where_in('FTXthDocNo',$tDocNo);
        $this->db->update('TSVTJRQDocDTTmp');
        return;
    }
    
    // Functionality    : ลบข้อมูลส่วนลดท้ายบิล และส่วนลดรายการ
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1DeleteHDDisTemp($paParams){
        $tDocNo       = $paParams['tDocNo'];
        $tBchCode     = $paParams['tBchCode'];
        $tSessionID   = $paParams['tSessionID'];

        // ลบ ข้อมูล HD Dis Temp
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TSVTJRQHDDisTmp');

        // ลบข้อมูล DT Dis Temp
        $this->db->where('FNXtdStaDis',2);
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TSVTJRQDTDisTmp');
        return;
    }

    // Functionality    : เพิ่มข้อมูลส่วนลดท้ายบิล
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Wasin
    public function FSaMJR1AddEditHDDisTemp($paDataInsert){
        $this->db->insert_batch('TSVTJRQHDDisTmp',$paDataInsert);
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Not Insert HD Dis Temp.',
            );
        }
        return $aStatus;
    }



}