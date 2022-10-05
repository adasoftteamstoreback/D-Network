<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Satisfactionsurvey_model extends CI_Model {
    //ชื่อกลุ่มเอกสาร
    public $tQaGrpCode = '00001';

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMSatSvGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        Job5HD.FTAgnCode,
                                        Job5HD.FTBchCode,
                                        BCHL.FTBchName,
                                        Job5HD.FTXshDocNo,
                                        CONVERT(CHAR(10),Job5HD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), Job5HD.FDXshDocDate,108) AS FTXshDocTime,
                                        DocRef.FTXshRefDocNo,
                                        CONVERT(CHAR(10),DocRef.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                        CONVERT(CHAR(5), DocRef.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                        Job5HD.FTXshStaDoc,
                                        Job5HD.FTXshStaApv,
                                        Job5HD.FNXshScoreValue,
                                        Job5HD.FTCreateBy,
                                        Job5HD.FDCreateOn,
                                        CREBY.FTUsrName as FTCreateByName
                                    FROM TSVTJob5ScoreHD  Job5HD             WITH (NOLOCK)
                                    LEFT JOIN TSVTJob5ScoreHDDocRef  DocRef  WITH (NOLOCK) ON DocRef.FTXshDocNo    = Job5HD.FTXshDocNo
                                    LEFT JOIN TCNMBranch_L  BCHL             WITH (NOLOCK) ON Job5HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L CREBY               WITH (NOLOCK) ON CREBY.FTUsrCode = Job5HD.FTCreateBy AND CREBY.FNLngID = $nLngID
                                WHERE 1=1
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND Job5HD.FTBchCode IN ($tBchCode)
            ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((Job5HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job5HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((Job5HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job5HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((Job5HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job5HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND Job5HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(Job5HD.FTXshStaApv,'') = '' AND Job5HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND Job5HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND Job5HD.FTXshStaApv = '$tSearchStaApprove' OR Job5HD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND Job5HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job5HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job5HD.FNXshStaDocAct = 0";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMSatSvCountPageDocListAll($paDataCondition);
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

    // Paginations
    public function FSnMSatSvCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];
    
        $tSQL   =   "   SELECT COUNT (Job5HD.FTXshDocNo) AS counts
                        FROM TSVTJob5ScoreHD  Job5HD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON Job5HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1
                    ";
    
        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND Job5HD.FTBchCode = '$tUserLoginBchCode' ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((Job5HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job5HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((Job5HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job5HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((Job5HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job5HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND Job5HD.FTXshStaApv = '$tSearchStaApprove' OR Job5HD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND Job5HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }
    
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job5HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job5HD.FNXshStaDocAct = 0";
            }
        }
        
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

    // insert ข้อมูลลงตาราง  HD
    public function FSaMSATQaAddUpdateHD($paDataAddHD, $paDataPrimaryKey)
    {
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableHD'];

            $nChhkData = $this->FSaMSATChkDupicate($paDataPrimaryKey, $tTable);
            
            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                // ลบ
                $this->db->where_in('FTAgnCode',$tAgnCode);
                $this->db->where_in('FTBchCode',$tBchCode);
                $this->db->where_in('FTXshDocNo',$tDocNo);
                $this->db->delete('TSVTJob5ScoreHD');

                //อัพเดท
                $this->db->insert('TSVTJob5ScoreHD',$paDataAddHD);
            //ไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                $this->db->insert('TSVTJob5ScoreHD',$paDataAddHD);
            } 

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert HD success'
            );
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    // insert ข้อมูลลงตาราง  DT
    public function FSaMSATQaAddUpdateDT($paDataDT, $paDataPrimaryKey)
    {
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableDT'];

            $nChhkData = $this->FSaMSATChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$tAgnCode);
                $this->db->where_in('FTBchCode',$tBchCode);
                $this->db->where_in('FTXshDocNo',$tDocNo);
                $this->db->delete('TSVTJob5ScoreDT');
                
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atSatQue'];
        
                    $aData = array(
                        'FTAgnCode'         => $tSatVal['FTAgnCode'],
                        'FTBchCode'         => $tSatVal['FTBchCode'],
                        'FTXshDocNo'        => $tSatVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tSatVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tSatQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tSatQaVal['nSeqDt'],
                        'FTQahType'         => $tSatQaVal['nQueType'],
                        'FDLastUpdOn'       => $tSatVal['FDLastUpdOn'],
                        'FTLastUpdBy'       => $tSatVal['FTLastUpdBy'],
                        'FDCreateOn'        => $tSatVal['FDCreateOn'],
                        'FTCreateBy'        => $tSatVal['FTCreateBy']
                    );

                    //อัพเดท
                    $this->db->insert('TSVTJob5ScoreDT',$aData);
                }
            //หากพบว่าไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atSatQue'];
        
                    $aData = array(
                        'FTAgnCode'         => $tSatVal['FTAgnCode'],
                        'FTBchCode'         => $tSatVal['FTBchCode'],
                        'FTXshDocNo'        => $tSatVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tSatVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tSatQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tSatQaVal['nSeqDt'],
                        'FTQahType'         => $tSatQaVal['nQueType'],
                        'FDLastUpdOn'       => $tSatVal['FDLastUpdOn'],
                        'FTLastUpdBy'       => $tSatVal['FTLastUpdBy'],
                        'FDCreateOn'        => $tSatVal['FDCreateOn'],
                        'FTCreateBy'        => $tSatVal['FTCreateBy']
                    );

                    $this->db->insert('TSVTJob5ScoreDT',$aData);
                }
            } 
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DT success'
            );
            

        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    // insert ข้อมูลลงตาราง  ANSDT
    public function FSaMSATQaAddUpdateAnsDT($paDataDT, $paDataPrimaryKey)
    {
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableAnsDT'];

            $nChhkData = $this->FSaMSATChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$tAgnCode);
                $this->db->where_in('FTBchCode',$tBchCode);
                $this->db->where_in('FTXshDocNo',$tDocNo);
                $this->db->delete('TSVTJob5ScoreDTAns');

                //อัพเดท
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atSatAns'];
        
                    $aData = array(
                        'FTAgnCode'         => $tSatVal['FTAgnCode'],
                        'FTBchCode'         => $tSatVal['FTBchCode'],
                        'FTXshDocNo'        => $tSatVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tSatVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tSatQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tSatQaVal['nSeqDt'],
                        'FNQasResSeq'       => $tSatQaVal['nSeqAs'],
                        'FTXsdStaAnsValue'  => $tSatQaVal['tResVal'],
                        'FTXsdAnsValue'     => $tSatQaVal['tResName']
                    );

                    $this->db->insert('TSVTJob5ScoreDTAns',$aData);
                }
            //หากพบว่าไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atSatAns'];
        
                    $aData = array(
                        'FTAgnCode'         => $tSatVal['FTAgnCode'],
                        'FTBchCode'         => $tSatVal['FTBchCode'],
                        'FTXshDocNo'        => $tSatVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tSatVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tSatQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tSatQaVal['nSeqDt'],
                        'FNQasResSeq'       => $tSatQaVal['nSeqAs'],
                        'FTXsdStaAnsValue'  => $tSatQaVal['tResVal'],
                        'FTXsdAnsValue'     => $tSatQaVal['tResName']
                    );

                    $this->db->insert('TSVTJob5ScoreDTAns',$aData);
                }
            } 
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert AnsDT success'
            );

        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    public function FSaMSATQaAddUpdateRefDocHD($paDataJob5AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $paDataPrimaryKey)
    {
        try {
            $tTable     = $paDataPrimaryKey['tTableDocRef5'];
            $tTableRef  = $paDataPrimaryKey['tTableDocRef2'];
            
            $nChhkDataDocRef5  = $this->FSaMIASChkRefDupicate($paDataPrimaryKey, $tTable, $paDataJob5AddDocRef['FTXshRefDocNo'], $paDataJob5AddDocRef['FTXshRefType']);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRef5['rtCode']) && $nChhkDataDocRef5['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataJob5AddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataJob5AddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataJob5AddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType',$paDataJob5AddDocRef['FTXshRefType']);
                $this->db->where_in('FTXshRefDocNo',$paDataJob5AddDocRef['FTXshRefDocNo']);
                $this->db->delete($tTable);

                //เพิ่มใหม่
                $this->db->insert($tTable,$paDataJob5AddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert($tTable,$paDataJob5AddDocRef);
            }

            $nChhkDataDocRef2  = $this->FSaMIASChkRefDupicate($paDataPrimaryKey, $tTableRef, $aDataJob2AddDocRef['FTXshRefDocNo'], $aDataJob2AddDocRef['FTXshRefType']);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRef2['rtCode']) && $nChhkDataDocRef2['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$aDataJob2AddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$aDataJob2AddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$aDataJob2AddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType',$aDataJob2AddDocRef['FTXshRefType']);
                $this->db->where_in('FTXshRefDocNo',$aDataJob2AddDocRef['FTXshRefDocNo']);
                $this->db->delete($tTableRef);

                //เพิ่มใหม่
                $this->db->insert($tTableRef,$aDataJob2AddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert($tTableRef,$aDataJob2AddDocRef);
            }

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
            
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMSATChkDupicate($paDataPrimaryKey, $ptTable)
    {
        try{
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];

            $tSQL = "   SELECT 
                            FTAgnCode,
                            FTBchCode,
                            FTXshDocNo
                        FROM $ptTable
                        WHERE 1=1
                        AND FTAgnCode  = '$tAgnCode'
                        AND FTBchCode  = '$tBchCode'
                        AND FTXshDocNo = '$tDocNo'
                    ";
            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aDetail = $oQueryHD->row_array();
                $aResult    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            return $aResult;
            
        }catch (Exception $Error) {
            echo $Error;
        }
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIASChkRefDupicate($paDataPrimaryKey, $ptTable, $tRefDocNo, $tRefDocType)
    {
        try{
            if ($ptTable == 'TSVTJob5ScoreHDDocRef') {
                $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode = $paDataPrimaryKey['FTBchCode'];
                $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable
                            WHERE 1=1
                            AND FTAgnCode     = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshDocNo    = '$tDocNo'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tRefDocNo'";

                            
            }else{
                $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode = $paDataPrimaryKey['FTBchCode'];
                $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable
                            WHERE 1=1
                            AND FTAgnCode     = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tDocNo' ";
            }

            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aDetail = $oQueryHD->row_array();
                $aResult    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            return $aResult;
            
        }catch (Exception $Error) {
            echo $Error;
        }
    }

    //ดึงคำตอบมาแสดงแบบ Add
    public function FSaMSATQaViewAnswer()
    {
        try {
            // $tSQL = "SELECT 
            //                 HD.FTQahDocNo , 
            //                 Grp.FTQsgCode , 
            //                 DT.FNQadSeqNo , 
            //                 Grp.FTQsgName , 
            //                 DT.FTQadName , 
            //                 DT.FTQadType ,
            //                 ANS.FNQasResuitSeq , 
            //                 ANS.FNQasResuitName
            //             FROM TCNTQaHD HD
            //             INNER JOIN TCNMQasSubGrp_L Grp ON Grp.FTQsgCode = HD.FTQsgCode AND Grp.FNLngID = 1
            //             LEFT JOIN TCNTQaDT DT ON HD.FTQahDocNo = DT.FTQahDocNo
            //             LEFT JOIN TCNTQaDTAns ANS ON DT.FTQahDocNo = ANS.FTQahDocNo AND DT.FNQadSeqNo = ANS.FNQadSeqNo
            //             WHERE HD.FTQgpCode = '$this->tQaGrpCode'
            //         ";
            $nLangEdit = $this->session->userdata("tLangEdit");
            $tSQL ="SELECT
                        HD.FTQahDocNo,
                        Grp.FTQsgCode,
                        DT.FNQadSeqNo,
                        Grp.FTQsgName,
                        DT.FTQadName,
                        DT.FTQadType,
                        ANS.FNQasResuitSeq,
                        ANS.FNQasResuitName
                    FROM
                        TCNTQaHD HD
                    INNER JOIN TCNMQasSubGrp_L Grp ON Grp.FTQsgCode = HD.FTQsgCode
                    AND Grp.FNLngID = $nLangEdit
                    LEFT JOIN TCNTQaDT DT ON HD.FTQahDocNo = DT.FTQahDocNo
                    LEFT JOIN TCNTQaDTAns ANS ON DT.FTQahDocNo = ANS.FTQahDocNo
                    AND DT.FNQadSeqNo = ANS.FNQadSeqNo
                    WHERE
                        HD.FTQahDocNo IN (
                            SELECT
                                QA.FTQahDocNo
                            FROM
                                (
                                    SELECT
                                        ROW_NUMBER () OVER (PARTITION BY HD.FTQsgCode ORDER BY HD.FDQahDateStart DESC,HD.FDQahDateStop DESC) AS FNRowPart,
                                        HD.FTQahDocNo
                                    FROM
                                        TCNTQaHD HD
                                    WHERE
                                        HD.FTQgpCode = '$this->tQaGrpCode'
                                    AND HD.FTQahStaActive = '1'
                                    AND (
                                        CONVERT (VARCHAR(10), GETDATE(), 121) >= HD.FDQahDateStart
                                        AND CONVERT (VARCHAR(10), GETDATE(), 121) <= HD.FDQahDateStop
                                    )
                                ) QA
                            WHERE
                                QA.FNRowPart = 1
                        )";
            $oQueryQA = $this->db->query($tSQL);
            // echo $this->db->last_query();
            // die();
            $aList = $oQueryQA->result_array();
            $aResult = array(
                'raItems' => $aList
            );
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }

    }

    public function FSaMSATCountXsdSeq($paDataPrimaryKey){
        $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
        $tBchCode = $paDataPrimaryKey['FTBchCode'];
        $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
        $tSQL   =   "   SELECT 
                            MAX(DT.FTXsdSeq) AS FTXsdSeq
                        FROM TSVTJob5ScoreDT DT WITH (NOLOCK)
                        WHERE 1=1 
                        AND DT.FTAgnCode = '$tAgnCode'
                        AND DT.FTBchCode = '$tBchCode'
                        AND DT.FTXshDocNo = '$tDocNo'
                    ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['FTXsdSeq'];
        }else{
            $nResult    = 0;
        }
        return empty($nResult)? 0 : $nResult;
    }


    //เช็คเอกสารจากการ Jump มาผ่าน Webview
    public function FSaMSATCheckDocNo($ptAgnCode,$ptBchCode,$ptDocNo){
        $tSQL = "   SELECT 
                        HD.FTXshDocNo
                    FROM TSVTJob5ScoreHD HD WITH (NOLOCK)
                    LEFT JOIN TSVTJob5ScoreHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo
                    WHERE HD.FTAgnCode = '$ptAgnCode' AND 
                    HD.FTBchCode = '$ptBchCode'  AND 
                    ( HD.FTXshDocNo = '$ptDocNo' OR ( DOCRef.FTXshRefDocNo = '$ptDocNo' AND DOCRef.FTXshRefType = '1' ) ) ";
        $oQueryHD = $this->db->query($tSQL);
        if ($oQueryHD->num_rows() > 0) {
            $aReturnData = array(
                'raItems'   => $oQueryHD->result_array(),
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        }else{
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => 'error'
            );
        }
        return $aReturnData;
    }

    public function FSaMSATGetDataHD($ptAgnCode,$ptBchCode,$ptDocNo){
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            HD.FTAgnCode,
                            AGN.FTAgnName,
                            HD.FTBchCode,
                            BCH.FTBchName,
                            HD.FTXshDocNo,
                            HD.FDXshDocDate,
                            CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXshDocTime,
                            Job2HD.FTCstCode,
                            CSTL.FTCstName,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            CAR.FTCarRegNo, 
                            T1.FTCaiName as FTCarBrand, 
                            T2.FTCaiName as FTCarModel,
                            HD.FTUsrCode,
                            USR.FTUsrName as SatSvBy,
                            HD.FTXshApvCode,
                            APVBY.FTUsrName as ApvBy,
                            HD.FTXshRmk,
                            HD.FTXshAdditional,
                            HD.FTXshStaDoc,
                            HD.FTXshStaApv,
                            HD.FNXshStaDocAct,
                            HD.FNXshScoreValue,
                            HD.FTCreateBy,
                            USR.FTUsrName as FTNameCreateBy,
                            HD.FDCreateOn,
                            DOCRef.FTXshRefType,
                            DOCRef.FTXshRefDocNo,
                            DOCRef.FDXshRefDocDate

                        FROM TSVTJob5ScoreHD HD WITH (NOLOCK)
                        LEFT JOIN TSVTJob5ScoreHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo
                        LEFT JOIN TSVTJob2OrdHD Job2HD          WITH (NOLOCK) ON Job2HD.FTXshDocNo = DOCRef.FTXshRefDocNo
                        LEFT JOIN TSVTJob2OrdHDCst Job2HDCST    WITH (NOLOCK) ON Job2HDCST.FTXshDocNo = Job2HD.FTXshDocNo
                        LEFT JOIN TCNMAgency_L AGN              WITH (NOLOCK) ON HD.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID ='$tLang'
                        LEFT JOIN TCNMBranch_L BCH              WITH (NOLOCK) ON Job2HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID ='$tLang'
                        LEFT JOIN TCNMCst CST                   WITH (NOLOCK) ON Job2HD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL                WITH (NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$tLang'
                        LEFT JOIN TSVMCar CAR                   WITH (NOLOCK) ON Job2HDCST.FTCarCode = CAR.FTCarCode
                        LEFT JOIN TSVMCarInfo_L T1              WITH (NOLOCK) ON CAR.FTCarBrand  = T1.FTCaiCode AND T1.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T2              WITH (NOLOCK) ON CAR.FTCarModel  = T2.FTCaiCode AND T2.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L USR                WITH (NOLOCK) ON USR.FTUsrCode = HD.FTUsrCode AND USR.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L CREBY              WITH (NOLOCK) ON CREBY.FTUsrCode = HD.FTCreateBy AND CREBY.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L APVBY              WITH (NOLOCK) ON APVBY.FTUsrCode = HD.FTXshApvCode AND APVBY.FNLngID = '$tLang'
                        WHERE HD.FTAgnCode = '$ptAgnCode' AND HD.FTBchCode = '$ptBchCode'  AND ( HD.FTXshDocNo = '$ptDocNo' OR (DOCRef.FTXshRefDocNo = '$ptDocNo' AND DOCRef.FTXshRefType = '1' )) ";
            $oQueryQA = $this->db->query($tSQL);

            $aReturnData = array(
                'raItems'   => $oQueryQA->result_array(),
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }
    
    public function FSaMSATGetDataDT($ptAgnCode,$ptBchCode,$ptDocNo){
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT A.* , ANS.FNQasResuitName, ANS.FNQasResuitSeq FROM ( 
                            SELECT
                            ROW_NUMBER() OVER ( PARTITION BY DT.FTQahDocNo , DT.FTQahType , DT.FNQadSeqNo ORDER BY DT.FTQahDocNo, DT.FTQahType * 1 ) row_num ,
                            DT.FTQahType,
                            DT.FTQahDocNo,
                            DT.FNQadSeqNo,
                            DTAns.FNQasResSeq,
                            Grp.FTQsgCode ,  
                            Grp.FTQsgName , 
                            QADT.FTQadName AS FTQadName, 
                            DT.FTQahType as FTQadType ,
                            CASE 
                                WHEN DT.FTQahType = '2' THEN (
                                    SELECT 
                                    ',' + FTXsdStaAnsValue ,
                                    '(' + DTAns.FTQahDocNo + ')' FROM TSVTJob5ScoreDTAns DTAns 
                                    INNER JOIN TCNTQaDT QDT ON DTAns.FTQahDocNo = QDT.FTQahDocNo AND DTAns.FNQadSeqNo = QDT.FNQadSeqNo 
                                    WHERE QDT.FTQadType = '2' AND DTAns.FTXshDocNo = '$ptDocNo'  
                                    FOR XML PATH (''))
                            ELSE
                                DTAns.FTXsdStaAnsValue 
                            END AS ANS_VALUE ,

                            CASE 
                                WHEN DT.FTQahType = '2' THEN (
                                    SELECT 
                                    ',' + FTXsdAnsValue ,
                                    '(' + DTAns.FTQahDocNo + ')' FROM TSVTJob5ScoreDTAns DTAns 
                                    INNER JOIN TCNTQaDT QDT ON DTAns.FTQahDocNo = QDT.FTQahDocNo AND DTAns.FNQadSeqNo = QDT.FNQadSeqNo 
                                    WHERE QDT.FTQadType = '2' AND DTAns.FTXshDocNo = '$ptDocNo'  
                                    FOR XML PATH (''))
                            ELSE
                                DTAns.FTXsdAnsValue 
                            END AS ANS_NAME

                    FROM TSVTJob5ScoreDT DT           
                    INNER JOIN TCNTQaHD QAHD ON DT.FTQahDocNo = QAHD.FTQahDocNo
                    INNER JOIN TCNTQaDT QADT ON DT.FTQahDocNo = QADT.FTQahDocNo AND DT.FNQadSeqNo = QADT.FNQadSeqNo
                    INNER JOIN TSVTJob5ScoreDTAns DTAns ON DTAns.FTXshDocNo = DT.FTXshDocNo AND DTAns.FTQahDocNo = DT.FTQahDocNo AND DTAns.FNQadSeqNo = DT.FNQadSeqNo
                    INNER JOIN TCNTQaDTAns ANS 	ON DT.FTQahDocNo = ANS.FTQahDocNo AND DTAns.FNQadSeqNo = ANS.FNQadSeqNo
                    INNER JOIN TCNMQasSubGrp_L Grp ON Grp.FTQsgCode = QAHD.FTQsgCode AND Grp.FNLngID = '$tLang'
                    WHERE 1=1 AND DT.FTAgnCode = '$ptAgnCode' AND DT.FTBchCode = '$ptBchCode'  AND DT.FTXshDocNo = '$ptDocNo' 
                    ) AS A
                    INNER JOIN TCNTQaDTAns ANS ON A.FTQahDocNo = ANS.FTQahDocNo AND A.FNQadSeqNo = ANS.FNQadSeqNo
                    WHERE A.row_num = 1
                    ORDER BY
                        A.FTQahDocNo,
                        A.FNQadSeqNo * 1 ASC ";
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aReturnData = array(
                'raItems' => $aList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    //อนุมัตเอกสาร
    public function FSaMSATApproveDocument($paDataUpdate){
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
    
            $this->db->set('FDLastUpdOn',$dLastUpdOn);
            $this->db->set('FTLastUpdBy',$tLastUpdBy);
            $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
            $this->db->set('FTXshApvCode',$paDataUpdate['FTXshApvCode']);
            $this->db->where_in('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob5ScoreHD');
    
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Updated Status Document Cancel Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update Status Document.',
                );
            }
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        return $aStatus;
    }

    //ยกเลิกเอกสาร
    public function FSaMSATCancelDocument($paDataUpdate, $aDataWhereDocRef){
        try {
            $this->db->set('FTXshStaDoc' , 3);
            $this->db->where_in('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob5ScoreHD');

            //ลบ TSVTJob5ScoreHDDocRef
            $this->db->where_in('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->where_in('FTXshRefType',$aDataWhereDocRef['FTXshRefType']);
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->delete('TSVTJob5ScoreHDDocRef');

            //ลบ TSVTJob2OrdHDDocRef
            $this->db->where_in('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo',$aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->where_in('FTXshRefType',2);
            $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob2OrdHDDocRef');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Updated Status Document Cancel Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update Status Document.',
                );
            }
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        return $aStatus;
    }

    //ลบข้อมูล
    public function FSnMSATDelDocument($paDataDoc){
        try {
            $tDataDocNo = $paDataDoc['tDataDocNo'];
            $this->db->trans_begin();

            // HD
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob5ScoreHD');

            // HD Doc Ref
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob5ScoreHDDocRef');
            
            // DT
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob5ScoreDT');

            // DT Ans
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob5ScoreDTAns');

            // Job2HD Doc Ref
            $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob2OrdHDDocRef');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStaDelDoc     = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStaDelDoc     = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Delete Complete.',
                );
            }
        } catch (Exception $Error) {
            $aStatus = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        return $aStaDelDoc;
    }
    
    public function FSaMSatSvGetDataWhereJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            JOB2HD.FTAgnCode,
                            JOB2HD.FTBchCode,
                            JOB5REF.FTXshDocNo
                        FROM TSVTJob5ScoreHDDocRef JOB5REF
                        INNER JOIN TSVTJob2OrdHD JOB2HD ON JOB2HD.FTXshDocNo = JOB5REF.FTXshRefDocNo
                        WHERE JOB5REF.FTXshRefDocNo = '$ptDocCode'
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aData = $oQuery->result_array();
                $aReturnData = array(
                    'rtCode'        => '1',
                    'rtDesc'        => 'Found',
                    'aRtData'       => $aData
                );
            }else{
                //Not Found
                $aReturnData = array(
                    'rtCode'        => '0',
                    'rtDesc'        => 'data not found',
                    'aRtData'       => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    public function FSaMSatSvGetDataJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            Job2HD.FTAgnCode,
                            AGN.FTAgnName,
                            Job2HD.FTBchCode,
                            BCH.FTBchName,
                            Job2HD.FTCstCode,
                            CSTL.FTCstName,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            Job2HD.FTXshDocNo,
                            Job2HD.FDXshDocDate,
                            T1.FTCaiName as FTCarBrand, 
                            T2.FTCaiName as FTCarModel,
                            CAR.FTCarRegNo
                        FROM TSVTJob2OrdHD Job2HD
                        LEFT JOIN TCNMAgency_L AGN ON Job2HD.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID ='$tLang'
                        LEFT JOIN TCNMBranch_L BCH ON Job2HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID ='$tLang'
                        LEFT JOIN TCNMCst CST ON Job2HD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$tLang'
                        LEFT JOIN TSVMCar CAR ON Job2HD.FTCstCode = CAR.FTCarOwner
                        LEFT JOIN TSVMCarInfo_L T1 ON CAR.FTCarBrand  = T1.FTCaiCode AND T1.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T2 ON CAR.FTCarModel  = T2.FTCaiCode AND T2.FNLngID = '$tLang'
                        WHERE Job2HD.FTXshDocNo = '$ptDocCode' ";

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aData = $oQuery->result_array();
                $aReturnData = array(
                    'rtCode'        => '1',
                    'rtDesc'        => 'Found',
                    'aRtData'       => $aData
                );
            }else{
                //Not Found
                $aReturnData = array(
                    'rtCode'        => '0',
                    'rtDesc'        => 'data not found',
                    'aRtData'       => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    } 
}



/* End of file ModelName.php */
