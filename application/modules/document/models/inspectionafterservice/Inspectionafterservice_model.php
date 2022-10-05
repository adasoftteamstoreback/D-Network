<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspectionafterservice_model extends CI_Model {

    //ชื่อกลุ่มเอกสาร
    public $tQaGrpCode = '00002';

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMIASGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "   SELECT TOP ". get_cookie('nShowRecordInPageList')." c.* FROM(
                            SELECT  --ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,
                            * FROM
                                (   SELECT DISTINCT
                                        Job4HD.FTAgnCode,
                                        Job4HD.FTBchCode,
                                        BCHL.FTBchName,
                                        Job4HD.FTXshDocNo,
                                        CONVERT(CHAR(10),Job4HD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), Job4HD.FDXshDocDate,108) AS FTXshDocTime,
                                        DocRef.FTXshRefDocNo,
                                        CONVERT(CHAR(10),DocRef.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                        CONVERT(CHAR(5), DocRef.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                        Job4HD.FTXshStaDoc,
                                        Job4HD.FTXshStaApv,
                                        Job4HD.FNXshScoreValue,
                                        USR.FTUsrName as FTCreateBy,
                                        Job4HD.FDCreateOn
                                    FROM TSVTJob4ApvHD  Job4HD             WITH (NOLOCK)
                                    LEFT JOIN TSVTJob4ApvHDDocRef  DocRef  WITH (NOLOCK) ON DocRef.FTXshDocNo    = Job4HD.FTXshDocNo AND DocRef.FTXshRefType = 1
                                    LEFT JOIN TCNMBranch_L  BCHL           WITH (NOLOCK) ON Job4HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    INNER JOIN TCNMUser_L USR              WITH (NOLOCK) ON Job4HD.FTCreateBy    = USR.FTUsrCode    AND BCHL.FNLngID    = $nLngID
                                WHERE ISNULL(Job4HD.FTXshDocNo,'') !=''  ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND Job4HD.FTBchCode IN ($tBchCode) ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((Job4HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job4HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((Job4HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job4HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((Job4HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job4HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND Job4HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(Job4HD.FTXshStaApv,'') = '' AND Job4HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaApprove' OR Job4HD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job4HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job4HD.FNXshStaDocAct = 0";
            }
        }

        // $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL   .=  ") Base) AS c ORDER BY c.FDCreateOn DESC";
      
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = 0;
            $nFoundRow          = 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
            unset($oDataList);
            unset($aDataCountAllRow);
            unset($nFoundRow);
            unset($nPageAll);
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($tSQL);
        unset($oQuery);
        unset($nLngID);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom);
        unset($tSearchBchCodeTo);
        unset($tSearchDocDateFrom);
        unset($tSearchDocDateTo);
        unset($tSearchStaDoc);
        unset($tSearchStaDocAct);
        unset($aRowLen);
        return $aResult;
    }

    // Paginations
    public function FSnMIASCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
    
        $tSQL   =   "   SELECT COUNT (Job4HD.FTXshDocNo) AS counts
                        FROM TSVTJob4ApvHD  Job4HD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON Job4HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE ISNULL(Job4HD.FTXshDocNo,'')!= '' ";
    
        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND  Job4HD.FTBchCode IN ($tBCH) ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((Job4HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job4HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((Job4HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job4HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((Job4HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job4HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaApprove' OR Job4HD.FTXshStaApv = '' ";
            }else{
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND Job4HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(Job4HD.FTXshStaApv,'') = '' AND Job4HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND Job4HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }
    
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job4HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job4HD.FNXshStaDocAct = 0";
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
            unset($aDetail);
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($tSQL);
        unset($oQuery);
        unset($nLngID);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom);
        unset($tSearchBchCodeTo);
        unset($tSearchDocDateFrom);
        unset($tSearchDocDateTo);
        unset($tSearchStaDoc);
        unset($tSearchStaDocAct);
        return $aDataReturn;
    }

    //ดึงคำตอบมาแสดงแบบ Add
    public function FSaMIASQaViewAnswer(){
        try {
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
                    TCNTQaHD HD WITH(NOLOCK)
                INNER JOIN TCNMQasSubGrp_L Grp WITH(NOLOCK) ON Grp.FTQsgCode = HD.FTQsgCode
                AND Grp.FNLngID = $nLangEdit
                LEFT JOIN TCNTQaDT DT WITH(NOLOCK) ON HD.FTQahDocNo = DT.FTQahDocNo
                LEFT JOIN TCNTQaDTAns ANS WITH(NOLOCK) ON DT.FTQahDocNo = ANS.FTQahDocNo
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
                                    TCNTQaHD HD WITH(NOLOCK)
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
            unset($aList);
            unset($oQueryQA);
            unset($tSQL);
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }

    }

    // insert ข้อมูลลงตาราง  HD
    public function FSaMIASQaAddUpdateHD($paDataAddHD, $paDataPrimaryKey){
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableHD'];

            $nChhkData = $this->FSaMIASChkDupicate($paDataPrimaryKey, $tTable);
            
            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                // ลบ
                $this->db->where('FTAgnCode',$tAgnCode);
                $this->db->where('FTBchCode',$tBchCode);
                $this->db->where('FTXshDocNo',$tDocNo);
                $this->db->delete($tTable);

                //อัพเดท
                $this->db->insert($tTable,$paDataAddHD);
            //ไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                $this->db->insert($tTable,$paDataAddHD);
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
        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tTable);
        unset($nChhkData);
        return $aReturnData;
    }

    // insert ข้อมูลลงตาราง  DT
    public function FSaMIASQaAddUpdateDT($paDataDT, $paDataPrimaryKey){
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableDT'];

            $nChhkData = $this->FSaMIASChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                //ลบ
                $this->db->where('FTAgnCode',$tAgnCode);
                $this->db->where('FTBchCode',$tBchCode);
                $this->db->where('FTXshDocNo',$tDocNo);
                $this->db->delete($tTable);
                
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atIASQue'];
        
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
                    $this->db->insert($tTable,$aData);
                }
            //หากพบว่าไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atIASQue'];
        
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

                    $this->db->insert($tTable,$aData);
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
        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($nChhkData);
        unset($tTable);
        unset($aData);
        return $aReturnData;
    }

    // insert ข้อมูลลงตาราง  ANSDT
    public function FSaMIASQaAddUpdateAnsDT($paDataDT, $paDataPrimaryKey)
    {
        try {   
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableAnsDT'];

            $nChhkData = $this->FSaMIASChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1){
                //ลบ
                $this->db->where('FTAgnCode',$tAgnCode);
                $this->db->where('FTBchCode',$tBchCode);
                $this->db->where('FTXshDocNo',$tDocNo);
                $this->db->delete($tTable);

                //อัพเดท
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atIASAns'];
        
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

                    $this->db->insert($tTable,$aData);
                }
            //หากพบว่าไม่ซ้ำ
            }else{
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tSatVal) {
                    $tSatQaVal = $tSatVal['atIASAns'];
        
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

                    $this->db->insert($tTable,$aData);
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
        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tTable);
        unset($nChhkData);
        return $aReturnData;
    }

    public function FSaMIASQaAddUpdateRefDocHD($paDataJob4AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $paDataPrimaryKey, $aDatawhereJob4DocRef)
    {
        try { 
            $tAgnCode   = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode   = $paDataPrimaryKey['FTBchCode'];
            $tDocNo     = $paDataPrimaryKey['FTXshDocNo'];
            $tTable     = $paDataPrimaryKey['tTableDocRef4'];
            $tTableRef  = $paDataPrimaryKey['tTableDocRef2'];
            $tRefDocNo   = $aDatawhereJob4DocRef['FTXshRefDocNo'];
            $tRefDocTypeJob4 = $aDatawhereJob4DocRef['FTXshRefType'];

            $nChhkDataDocRef4  = $this->FSaMIASChkRefDupicate($paDataPrimaryKey, $tTable, $tRefDocNo, $aDatawhereJob4DocRef['FTXshRefType']);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRef4['rtCode']) && $nChhkDataDocRef4['rtCode'] == 1){
                //ลบ
                $this->db->where('FTAgnCode',$aDatawhereJob4DocRef['FTAgnCode']);
                $this->db->where('FTBchCode',$aDatawhereJob4DocRef['FTBchCode']);
                $this->db->where('FTXshDocNo',$aDatawhereJob4DocRef['FTXshDocNo']);
                $this->db->where('FTXshRefType',$aDatawhereJob4DocRef['FTXshRefType']);
                $this->db->where('FTXshRefDocNo',$aDatawhereJob4DocRef['FTXshRefDocNo']);
                $this->db->delete($tTable);

                //เพิ่มใหม่
                $this->db->insert($tTable,$paDataJob4AddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert($tTable,$paDataJob4AddDocRef);
            }

            $nChhkDataDocRef2  = $this->FSaMIASChkRefDupicate($aDataJob2AddDocRef, $tTableRef, $tRefDocNo, $aDataJob2AddDocRef['FTXshRefType']);
            
            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRef2['rtCode']) && $nChhkDataDocRef2['rtCode'] == 1){
                //ลบ
                $this->db->where('FTAgnCode',$aDataJob2AddDocRef['FTAgnCode']);
                $this->db->where('FTBchCode',$aDataJob2AddDocRef['FTBchCode']);
                $this->db->where('FTXshDocNo',$aDataJob2AddDocRef['FTXshDocNo']);
                $this->db->where('FTXshRefType',$aDataJob2AddDocRef['FTXshRefType']);
                $this->db->where('FTXshRefDocNo',$aDataJob2AddDocRef['FTXshRefDocNo']);
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

        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tTable);
        unset($tTableRef);
        unset($tRefDocNo);
        unset($tRefDocTypeJob4);
        return $aReturnData;
    }

    public function FSaMIASQaAddUpdateRefDocExtHD($paDataJob4AddDocRef,$ptIASAgnCode,$ptIASBchCode,$ptIASDocNo,$pnStausEditDocRef)
    {
        try {
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $ptIASAgnCode,
                'FTBchCode'         => $ptIASBchCode,
                'FTXshDocNo'        => $ptIASDocNo
            );
            $tTable     = 'TSVTJob4ApvHDDocRef';
            $tRefDocNo   = $paDataJob4AddDocRef['FTXshRefDocNo'];
            $tRefDocType = 3;
            if ($pnStausEditDocRef == 1) {
                $nChhkDataDocRef4  = $this->FSaMIASChkRefDupicate($paDataPrimaryKey, $tTable, $tRefDocNo, $tRefDocType);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRef4['rtCode']) && $nChhkDataDocRef4['rtCode'] == 1){
                    //ลบ
                    $this->db->where('FTAgnCode',$ptIASAgnCode);
                    $this->db->where('FTBchCode',$ptIASBchCode);
                    $this->db->where('FTXshDocNo',$ptIASDocNo);
                    $this->db->where('FTXshRefDocNo',$tRefDocNo);
                    $this->db->where('FTXshRefType',$tRefDocType);
                    $this->db->delete($tTable);

                    //เพิ่มใหม่
                    $this->db->insert($tTable,$paDataJob4AddDocRef);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTable,$paDataJob4AddDocRef);
                }
            }else{
                $this->db->where('FTAgnCode',$ptIASAgnCode);
                $this->db->where('FTBchCode',$ptIASBchCode);
                $this->db->where('FTXshDocNo',$ptIASDocNo);
                $this->db->where('FTXshRefDocNo',$tRefDocNo);
                $this->db->where('FTXshRefType',$tRefDocType);
                $this->db->delete($tTable);
            }
        
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        $paDataPrimaryKey = array(
            'FTAgnCode'         => $ptIASAgnCode,
            'FTBchCode'         => $ptIASBchCode,
            'FTXshDocNo'        => $ptIASDocNo
        );
        unset($paDataPrimaryKey);
        unset($tTable);
        unset($tRefDocNo);
        unset($tRefDocType);
        return $aReturnData;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIASChkDupicate($paDataPrimaryKey, $ptTable)
    {
        try{
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];

            $tSQL = "   SELECT 
                            FTAgnCode,
                            FTBchCode,
                            FTXshDocNo
                        FROM $ptTable WITH(NOLOCK)
                        WHERE  FTAgnCode  = '$tAgnCode'
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
                unset($aDetail);
            }else{
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }

            unset($tAgnCode);
            unset($tBchCode);
            unset($tDocNo);
            unset($tSQL);
            return $aResult;
            
        }catch (Exception $Error) {
            echo $Error;
        }
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIASChkRefDupicate($paDataPrimaryKey, $ptTable, $tRefDocNo, $tRefDocType)
    {
        try{
            if ($ptTable == 'TSVTJob4ApvHDDocRef') {
                $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode = $paDataPrimaryKey['FTBchCode'];
                $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
                $tRefDocNo = $tRefDocNo;

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable WITH(NOLOCK)
                            WHERE FTAgnCode   = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshDocNo    = '$tDocNo'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tRefDocNo'
                        ";
            }else{
                $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode = $paDataPrimaryKey['FTBchCode'];
                $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
                $tRefDocNo = $paDataPrimaryKey['FTXshRefDocNo'];

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable WITH(NOLOCK)
                            WHERE FTAgnCode   = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshDocNo    = '$tDocNo'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tRefDocNo'
                        ";
            }
            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aDetail = $oQueryHD->row_array();
                $aResult    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
                unset($aDetail);
            }else{
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            unset($tAgnCode);
            unset($tBchCode);
            unset($tDocNo);
            unset($tSQL);
            unset($tRefDocNo);
            return $aResult;
            
        }catch (Exception $Error) {
            echo $Error;
        }
    }

    // นับ Seq
    public function FSaMIASCountXsdSeq($paDataPrimaryKey)
    {
        $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
        $tBchCode = $paDataPrimaryKey['FTBchCode'];
        $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
        $tTableWhere = $paDataPrimaryKey['tTableDT'];
        $tSQL   =   "   SELECT 
                            MAX(DT.FTXsdSeq) AS FTXsdSeq
                        FROM $tTableWhere DT WITH (NOLOCK)
                        WHERE  DT.FTAgnCode = '$tAgnCode'
                        AND DT.FTBchCode = '$tBchCode'
                        AND DT.FTXshDocNo = '$tDocNo'
                    ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['FTXsdSeq'];
            unset($aDetail);
        }else{
            $nResult    = 0;
        }

        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tSQL);
        unset($tTableWhere);
        unset($oQuery);
        return empty($nResult)? 0 : $nResult;
    }

    public function FSaMIASGetDataHD($ptAgnCode,$ptBchCode,$ptDocNo)
    {
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
                            CAR.FTCarCode,
                            CAR.FTCarRegNo, 
                            CAR.FTCarEngineNo,
                            CAR.FTCarVIDRef,
                            T1.FTCaiName as FTCarType, 
                            T2.FTCaiName as FTCarBrand,
                            T3.FTCaiName as FTCarModel,
                            T4.FTCaiName as FTCarColor,
                            T5.FTCaiName as FTCarGear,
                            T6.FTCaiName as FTCarPowerType,
                            T7.FTCaiName as FTCarEngineSize,
                            T8.FTCaiName as FTCarCategory,
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
                            DOCRef.FTXshRefType as FTXshRefType1,
                            DOCRef.FTXshRefDocNo as FTXshRefDocNo1,
                            DOCRef.FDXshRefDocDate as FDXshRefDocDate1,
                            DOCRef3.FTXshRefType as FTXshRefType3,
                            DOCRef3.FTXshRefDocNo as FTXshRefDocNo3,
                            DOCRef3.FDXshRefDocDate as FDXshRefDocDate3,
                            HD.FDXshStartChk,
                            CONVERT(CHAR(5), HD.FDXshStartChk,108) AS FDXshStartChkTime,
                            HD.FDXshFinishChk,
                            CONVERT(CHAR(5), HD.FDXshFinishChk,108) AS FDXshFinishChkTime,
                            Job2HD.FCXshCarMileage,
                            PVNL.FTPvnName,
                            POS.FTSpsName
                        FROM TSVTJob4ApvHD HD WITH (NOLOCK)
                        LEFT JOIN TSVTJob4ApvHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo AND DOCRef.FTXshRefType = 1
                        LEFT JOIN TSVTJob4ApvHDDocRef DOCRef3 WITH (NOLOCK) ON DOCRef3.FTXshDocNo = HD.FTXshDocNo AND DOCRef3.FTXshRefType = 3
                        LEFT JOIN TSVTJob2OrdHD Job2HD        WITH (NOLOCK) ON Job2HD.FTXshDocNo = DOCRef.FTXshRefDocNo
                        LEFT JOIN TSVTJob2OrdHDCst Job2HDCST  WITH (NOLOCK) ON Job2HDCST.FTXshDocNo = Job2HD.FTXshDocNo
                        LEFT JOIN TSVTJob1ReqHDDocRef Job1Ref WITH (NOLOCK) ON Job1Ref.FTXshRefDocNo = Job2HD.FTXshDocNo AND Job1Ref.FTXshRefType = 2
                        LEFT JOIN TSVTJob1ReqHD Job1HD        WITH (NOLOCK) ON Job1Ref.FTXshDocNo = Job1HD.FTXshDocNo
                        LEFT JOIN TCNMAgency_L AGN            WITH (NOLOCK) ON HD.FTAgnCode = AGN.FTAgnCode AND AGN.FNLngID ='$tLang'
                        LEFT JOIN TCNMBranch_L BCH            WITH (NOLOCK) ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID ='$tLang'
                        LEFT JOIN TCNMCst CST                 WITH (NOLOCK) ON Job2HD.FTCstCode = CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL              WITH (NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = '$tLang'
                        LEFT JOIN TSVMCar CAR                 WITH (NOLOCK) ON Job2HDCST.FTCarCode = CAR.FTCarCode
                        LEFT JOIN TSVMCarInfo_L T1            WITH (NOLOCK) ON CAR.FTCarType   = T1.FTCaiCode AND T1.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T2            WITH (NOLOCK) ON CAR.FTCarBrand  = T2.FTCaiCode AND T2.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T3            WITH (NOLOCK) ON CAR.FTCarModel  = T3.FTCaiCode AND T3.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T4            WITH (NOLOCK) ON CAR.FTCarColor  = T4.FTCaiCode AND T4.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T5            WITH (NOLOCK) ON CAR.FTCarGear  = T5.FTCaiCode AND T5.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T6            WITH (NOLOCK) ON CAR.FTCarPowerType  = T6.FTCaiCode AND T6.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T7            WITH (NOLOCK) ON CAR.FTCarEngineSize  = T7.FTCaiCode AND T7.FNLngID = '$tLang'
                        LEFT JOIN TSVMCarInfo_L T8            WITH (NOLOCK) ON CAR.FTCarCategory   = T8.FTCaiCode AND T8.FNLngID = '$tLang'
                        LEFT JOIN TCNMProvince_L PVNL         WITH (NOLOCK) ON PVNL.FTPvnCode   = CAR.FTCarRegProvince AND PVNL.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L USR              WITH (NOLOCK) ON USR.FTUsrCode = HD.FTUsrCode AND USR.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L CREBY            WITH (NOLOCK) ON CREBY.FTUsrCode = HD.FTCreateBy AND CREBY.FNLngID = '$tLang'
                        LEFT JOIN TCNMUser_L APVBY            WITH (NOLOCK) ON APVBY.FTUsrCode = HD.FTXshApvCode AND APVBY.FNLngID = '$tLang'
                        LEFT JOIN TSVMPos_L  POS              WITH (NOLOCK) ON POS.FTSpsCode = Job2HD.FTXshToPos AND POS.FNLngID = '$tLang'
                        WHERE  HD.FTAgnCode = '$ptAgnCode' AND HD.FTBchCode = '$ptBchCode' AND ( HD.FTXshDocNo = '$ptDocNo' OR ( DOCRef.FTXshRefDocNo = '$ptDocNo' AND DOCRef.FTXshRefType = '1' ) ) 
                    ";
            //echo $tSQL;
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
        unset($tLang);
        unset($tSQL);
        unset($oQueryQA);
        unset($aList);
        return $aReturnData;
    }
    
    public function FSaMIASGetDataDT($ptAgnCode,$ptBchCode,$ptDocNo)
    {
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
                                    '(' + DTAns.FTQahDocNo + ')' FROM TSVTJob4ApvDTAns DTAns WITH(NOLOCK)
                                    INNER JOIN TCNTQaDT QDT WITH(NOLOCK) ON DTAns.FTQahDocNo = QDT.FTQahDocNo AND DTAns.FNQadSeqNo = QDT.FNQadSeqNo 
                                    WHERE QDT.FTQadType = '2' AND DTAns.FTXshDocNo = '$ptDocNo'  
                                    FOR XML PATH (''))
                            ELSE
                                DTAns.FTXsdStaAnsValue 
                            END AS ANS_VALUE ,

                            CASE 
                                WHEN DT.FTQahType = '2' THEN (
                                    SELECT 
                                    ',' + FTXsdAnsValue ,
                                    '(' + DTAns.FTQahDocNo + ')' FROM TSVTJob4ApvDTAns DTAns WITH(NOLOCK)
                                    INNER JOIN TCNTQaDT QDT WITH(NOLOCK) ON DTAns.FTQahDocNo = QDT.FTQahDocNo AND DTAns.FNQadSeqNo = QDT.FNQadSeqNo 
                                    WHERE QDT.FTQadType = '2' AND DTAns.FTXshDocNo = '$ptDocNo'  
                                    FOR XML PATH (''))
                            ELSE
                                DTAns.FTXsdAnsValue 
                            END AS ANS_NAME

                    FROM TSVTJob4ApvDT DT WITH(NOLOCK)          
                    INNER JOIN TCNTQaHD QAHD WITH(NOLOCK) ON DT.FTQahDocNo = QAHD.FTQahDocNo
                    INNER JOIN TCNTQaDT QADT WITH(NOLOCK) ON DT.FTQahDocNo = QADT.FTQahDocNo AND DT.FNQadSeqNo = QADT.FNQadSeqNo
                    INNER JOIN TSVTJob4ApvDTAns DTAns WITH(NOLOCK) ON DTAns.FTXshDocNo = DT.FTXshDocNo AND DTAns.FTQahDocNo = DT.FTQahDocNo AND DTAns.FNQadSeqNo = DT.FNQadSeqNo
                    INNER JOIN TCNTQaDTAns ANS WITH(NOLOCK) 	ON DT.FTQahDocNo = ANS.FTQahDocNo AND DTAns.FNQadSeqNo = ANS.FNQadSeqNo
                    INNER JOIN TCNMQasSubGrp_L Grp WITH(NOLOCK) ON Grp.FTQsgCode = QAHD.FTQsgCode AND Grp.FNLngID = '$tLang'
                    WHERE  DT.FTAgnCode = '$ptAgnCode' AND DT.FTBchCode = '$ptBchCode'  AND DT.FTXshDocNo = '$ptDocNo' 
                    ) AS A
                    INNER JOIN TCNTQaDTAns ANS WITH(NOLOCK) ON A.FTQahDocNo = ANS.FTQahDocNo AND A.FNQadSeqNo = ANS.FNQadSeqNo
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
        unset($tLang);
        unset($tSQL);
        unset($oQueryQA);
        unset($aList);
        return $aReturnData;
    }

    //อนุมัตเอกสาร
    public function FSaMIASApproveDocument($paDataUpdate){
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
    
            $this->db->set('FDLastUpdOn',$dLastUpdOn);
            $this->db->set('FTLastUpdBy',$tLastUpdBy);
            $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
            $this->db->set('FTXshApvCode',$paDataUpdate['FTXshApvCode']);
            $this->db->where('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob4ApvHD');
    
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
        unset($dLastUpdOn);
        unset($tLastUpdBy);
        return $aStatus;
    }

    //ยกเลิกเอกสาร
    public function FSaMIASCancelDocument($paDataUpdate, $aDataWhereDocRef){
        try {
            $this->db->set('FTXshStaDoc' , 3);
            $this->db->where('FTAgnCode',$paDataUpdate['FTAgnCode']);
            $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
            $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob4ApvHD');

            //ลบ TSVTJob4ApvHDDocRef
            $this->db->where('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where('FTXshDocNo',$aDataWhereDocRef['FTXshDocNo']);
            $this->db->delete('TSVTJob4ApvHDDocRef');

            //ลบ TSVTJob2OrdHDDocRef
            $this->db->where('FTAgnCode',$aDataWhereDocRef['FTAgnCode']);
            $this->db->where('FTBchCode',$aDataWhereDocRef['FTBchCode']);
            $this->db->where('FTXshDocNo',$aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->where('FTXshRefType',2);
            $this->db->where('FTXshRefDocNo',$aDataWhereDocRef['FTXshDocNo']);
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
    public function FSnMIASDelDocument($paDataDoc){
        try {
            $tDataDocNo = $paDataDoc['tDataDocNo'];
            $tIASAgnCode = $paDataDoc['tIASAgnCode'];
            $IASBchCode = $paDataDoc['tIASBchCode'];
            $IASDocRefCode = $paDataDoc['tIASDocRefCode'];
            $nDelMulti = $paDataDoc['nDelMulti'];
            
            $nRefDocType1 = 1;
            $nRefDocType2 = 2;

            $this->db->trans_begin();
            if($nDelMulti==1){
            // HD
            $this->db->where_in('FTAgnCode',$tIASAgnCode);
            $this->db->where_in('FTBchCode',$IASBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvHD');

            // HD Doc Ref
           $this->db->where_in('FTAgnCode',$tIASAgnCode);
           $this->db->where_in('FTBchCode',$IASBchCode);
           $this->db->where_in('FTXshDocNo',$tDataDocNo);
           $this->db->where_in('FTXshRefType',$nRefDocType1);
           $this->db->where_in('FTXshRefDocNo',$IASDocRefCode);
           $this->db->delete('TSVTJob4ApvHDDocRef');

           // Job2HD Doc Ref
           $this->db->where_in('FTAgnCode',$tIASAgnCode);
           $this->db->where_in('FTBchCode',$IASBchCode);
           $this->db->where_in('FTXshDocNo',$IASDocRefCode);
           $this->db->where_in('FTXshRefType',$nRefDocType2);
           $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
           $this->db->delete('TSVTJob2OrdHDDocRef');
            
            // DT
            $this->db->where_in('FTAgnCode',$tIASAgnCode);
            $this->db->where_in('FTBchCode',$IASBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvDT');

            // DT Ans
            $this->db->where_in('FTAgnCode',$tIASAgnCode);
            $this->db->where_in('FTBchCode',$IASBchCode);
            $this->db->where_in('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvDTAns');

            }else{
            // HD
            $this->db->where('FTAgnCode',$tIASAgnCode);
            $this->db->where('FTBchCode',$IASBchCode);
            $this->db->where('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvHD');

            // HD Doc Ref
           $this->db->where('FTAgnCode',$tIASAgnCode);
           $this->db->where('FTBchCode',$IASBchCode);
           $this->db->where('FTXshDocNo',$tDataDocNo);
           $this->db->where('FTXshRefType',$nRefDocType1);
           $this->db->where('FTXshRefDocNo',$IASDocRefCode);
           $this->db->delete('TSVTJob4ApvHDDocRef');

           // Job2HD Doc Ref
           $this->db->where('FTAgnCode',$tIASAgnCode);
           $this->db->where('FTBchCode',$IASBchCode);
           $this->db->where('FTXshDocNo',$IASDocRefCode);
           $this->db->where('FTXshRefType',$nRefDocType2);
           $this->db->where('FTXshRefDocNo',$tDataDocNo);
           $this->db->delete('TSVTJob2OrdHDDocRef');
            
            // DT
            $this->db->where('FTAgnCode',$tIASAgnCode);
            $this->db->where('FTBchCode',$IASBchCode);
            $this->db->where('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvDT');

            // DT Ans
            $this->db->where('FTAgnCode',$tIASAgnCode);
            $this->db->where('FTBchCode',$IASBchCode);
            $this->db->where('FTXshDocNo',$tDataDocNo);
            $this->db->delete('TSVTJob4ApvDTAns');
            }

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

        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tIASAgnCode = $paDataDoc['tIASAgnCode'];
        $IASBchCode = $paDataDoc['tIASBchCode'];
        $IASDocRefCode = $paDataDoc['tIASDocRefCode'];
        $nDelMulti = $paDataDoc['nDelMulti'];
        
        $nRefDocType1 = 1;
        $nRefDocType2 = 2;

        unset($tDataDocNo);
        unset($tIASAgnCode);
        unset($IASBchCode);
        unset($IASDocRefCode);
        unset($nDelMulti);
        unset($nRefDocType1);
        unset($nRefDocType2);
        return $aStaDelDoc;
    }

    //เช็คเอกสารจากการ Jump มาผ่าน Webview
    public function FSaMIASCheckDocNo($ptAgnCode,$ptBchCode,$ptDocNo){
        $tSQL = "   SELECT 
                        HD.FTXshDocNo
                    FROM TSVTJob4ApvHD HD WITH (NOLOCK)
                    LEFT JOIN TSVTJob4ApvHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo
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
        unset($tSQL);
        unset($oQueryHD);
        return $aReturnData;
    }

    //ค้นหาข้อมูลรถของลูกค้า
    public function FSaMInsGetDataCarCustomer($paDataCondition){
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCarCst    = $paDataCondition['tCarCstCode'];

        //ข้อมูลรถ
        $tSQL       = "SELECT
                            CAR.FTCarCode,
                            CAR.FTCarRegNo,
                            CAR.FTCarEngineNo,
                            CAR.FTCarVIDRef,
                            CAR.FTCarType AS FTCarTypeCode,
                            T1.FTCaiName AS FTCarTypeName,
                            CAR.FTCarBrand AS FTCarBrandCode,
                            T2.FTCaiName	AS FTCarBrandName,
                            CAR.FTCarModel AS FTCarModelCode,
                            T3.FTCaiName AS FTCarModelName,
                            CAR.FTCarColor AS FTCarColorCode,
                            T4.FTCaiName AS FTCarColorName,
                            CAR.FTCarGear AS FTCarGearCode,
                            T5.FTCaiName AS FTCarGearName,
                            CAR.FTCarPowerType AS FTCarPowerTypeCode,
                            T6.FTCaiName AS FTCarPowerTypeName,
                            CAR.FTCarEngineSize AS FTCarEngineSizeCode,
                            T7.FTCaiName AS FTCarEngineSizeName,
                            CAR.FTCarCategory AS FTCarCategoryCode,
                            T8.FTCaiName AS FTCarCategoryName,
                            CAR.FDCarDOB,
                            CAR.FTCarOwner AS FTCarOwnerCode,
                            CSTL.FTCstName AS FTCarOwnerName,
                            CAR.FDCarOwnChg,
                            CAR.FTCarRegProvince AS FTCarRegPvnCode,
                            PVNL.FTPvnName AS FTCarRegPvnName,
                            CAR.FTCarStaRedLabel ,
                            CST.FTCstTel,
                            CST.FTCstEmail
                        FROM TSVMCar CAR WITH(NOLOCK)
                        LEFT JOIN TSVMCarInfo_L T1 WITH (NOLOCK) ON CAR.FTCarType = T1.FTCaiCode AND T1.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T2 WITH (NOLOCK) ON CAR.FTCarBrand = T2.FTCaiCode AND T2.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T3 WITH (NOLOCK) ON CAR.FTCarModel = T3.FTCaiCode AND T3.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T4 WITH (NOLOCK) ON CAR.FTCarColor = T4.FTCaiCode AND T4.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T5 WITH (NOLOCK) ON CAR.FTCarGear = T5.FTCaiCode AND T5.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T6 WITH (NOLOCK) ON CAR.FTCarPowerType = T6.FTCaiCode AND T6.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T7 WITH (NOLOCK) ON CAR.FTCarEngineSize = T7.FTCaiCode AND T7.FNLngID = '$nLngID'
                        LEFT JOIN TSVMCarInfo_L T8 WITH (NOLOCK) ON CAR.FTCarCategory = T8.FTCaiCode AND T8.FNLngID = '$nLngID'
                        LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON CAR.FTCarRegProvince = PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
                        LEFT JOIN TCNMCst   CST  WITH(NOLOCK) ON CAR.FTCarOwner	= CST.FTCstCode
                        LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = '$nLngID'
                        WHERE CAR.FTCarCode = '$tCarCst'";
        $oQuery             = $this->db->query($tSQL);
        $aDataList          = $oQuery->row_array();
        $tCarOwnerCode      = $aDataList['FTCarOwnerCode'];

        //ข้อมูลที่อยู่
        $tSQL2       = "SELECT
                            Addr.FTCstCode,
                            Addr.FNLngID,
                            Addr.FTAddGrpType,
                            Addr.FNAddSeqNo,
                            Addr.FTAddRefNo,
                            Addr.FTAddName,
                            Addr.FTAddRmk,
                            Addr.FTAddVersion,
                            Addr.FTAddV1No,
                            Addr.FTAddV1Soi,
                            Addr.FTAddV1Village,
                            Addr.FTAddV1Road,
                            Addr.FTAddV1SubDist AS FTAddV1SubDistCode,
                            SUBL.FTSudName AS FTAddV1SubDistName,
                            Addr.FTAddV1DstCode,
                            DSTL.FTDstName AS FTAddV1DstName,
                            Addr.FTAddV1PvnCode,
                            PVNL.FTPvnName	AS FTAddV1PvnName,
                            Addr.FTAddV1PostCode,
                            Addr.FTAddTel,
                            Addr.FTAddFax,
                            Addr.FTAddV2Desc1,
                            Addr.FTAddV2Desc2,
                            Addr.FTAddWebsite,
                            Addr.FTAddLongitude,
                            Addr.FTAddLatitude
                        FROM TCNMCstAddress_L Addr WITH(NOLOCK)
                        LEFT JOIN TCNMSubDistrict_L SUBL WITH(NOLOCK) ON Addr.FTAddV1SubDist = SUBL.FTSudCode AND SUBL.FNLngID = '$nLngID'
                        LEFT JOIN TCNMDistrict_L DSTL WITH(NOLOCK) ON Addr.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = '$nLngID'
                        LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON Addr.FTAddV1PvnCode	= PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
                        WHERE  Addr.FTCstCode = '$tCarOwnerCode'
                        AND Addr.FNLngID = '$nLngID'
                        AND Addr.FTAddGrpType = '1'
                        AND Addr.FTAddRefNo	= '1' ";
        $oQuery2            = $this->db->query($tSQL2);
        $aDataList2         = $oQuery2->row_array();

        if ($oQuery->num_rows() > 0) {
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'raItems2'  => $aDataList2,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID);
        unset($tCstCode);
        unset($tSQL);
        unset($tSQL2);
        unset($oQuery);
        unset($oQuery2);
        unset($aDataList);
        unset($aDataList2);
        unset($tCarOwnerCode);
        return $aDataReturn;
    }

    public function FSaMIASGetDataWhereJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            JOB2HD.FTAgnCode,
                            JOB2HD.FTBchCode,
                            JOB4REF.FTXshDocNo
                        FROM TSVTJob4ApvHDDocRef JOB4REF WITH(NOLOCK)
                        INNER JOIN TSVTJob2OrdHD JOB2HD WITH(NOLOCK) ON JOB2HD.FTXshDocNo = JOB4REF.FTXshRefDocNo
                        WHERE JOB4REF.FTXshRefDocNo = '$ptDocCode'
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aData = $oQuery->result_array();
                $aReturnData = array(
                    'rtCode'        => '1',
                    'rtDesc'        => 'Found',
                    'aRtData'       => $aData
                );
                unset($aData);
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
        unset($tLang);
        unset($tSQL);
        unset($oQuery);
        return $aReturnData;
    }

    //หาว่าเอกสาร Job นี้ใช้ลูกค้า หรือ รถอะไร
    public function FSaMIASDataWhereJobOrder($tBchCode,$tDocNo){
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            HD.FTAgnCode,
                            HD.FTBchCode,
                            HD.FTXshDocNo,
                            HD.FDXshDocDate,
                            HD.FCXshCarMileage,
                            HD.FTCstCode,
                            HDCst.FTXshCstName,
                            HDCst.FTCarCode,
                            CAR.FTCarRegNo,
                            BCHL.FTBchName
                        FROM TSVTJob2OrdHD HD WITH(NOLOCK)
                        LEFT JOIN TSVTJob2OrdHDCst HDCst WITH(NOLOCK) ON HD.FTXshDocNo = HDCst.FTXshDocNo
                        LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = '$tLang'
                        INNER JOIN TSVMCar CAR WITH(NOLOCK) ON HDCst.FTCarCode = CAR.FTCarCode
                        WHERE HD.FTXshDocNo = '$tDocNo' AND HD.FTBchCode = '$tBchCode' ";
            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0){
                $aData = $oQuery->result_array();
                $aReturnData = array(
                    'rtCode'        => '1',
                    'rtDesc'        => 'Found',
                    'rtData'        => $aData
                );
                unset($aData);
            }else{
                $aReturnData = array(
                    'rtCode'        => '0',
                    'rtDesc'        => 'data not found',
                    'rtData'        => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        unset($tLang);
        unset($tSQL);
        unset($oQuery);
        return $aReturnData;
    }

    // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
    public function FSaMIASQaUpdateJOB2HD($paDataUpdateHD){

        $tDocRefNo      = $paDataUpdateHD['tDocRefNo'];
        $tBchRef        = $paDataUpdateHD['tBchRef'];

        //อัพเดท ตารางใบสั่งงาน เลขไมล์
        $aJOB2_Update = array(
            'FCXshCarMileage' => $paDataUpdateHD['FCXshCarMileage']
        );
        $this->db->where('FTBchCode', $tBchRef);
        $this->db->where('FTXshDocNo', $tDocRefNo);
        $this->db->update('TSVTJob2OrdHD', $aJOB2_Update);

        //--------------
        //อัพเดท ตารางใบรับรถ เลขไมล์ + แกนน้ำมัน
        $tSQL   = "SELECT TOP 1 JOB1.FTXshDocNo FROM TSVTJob1ReqHDDocRef JOB1 WITH(NOLOCK) WHERE JOB1.FTXshRefDocNo = '$tDocRefNo' AND JOB1.FTXshRefKey = 'Job2Ord' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData      = $oQuery->result_array();
            $tDocJOB1   =  $aData[0]['FTXshDocNo'];
            $aJOB1_Update = array(
                'FCXshCarMileage'   => $paDataUpdateHD['FCXshCarMileage']
            );
            $this->db->where('FTBchCode', $tBchRef);
            $this->db->where('FTXshDocNo', $tDocJOB1);
            $this->db->update('TSVTJob1ReqHD', $aJOB1_Update);
            unset($aData);
            unset($tDocJOB1);
            unset($aJOB1_Update);
        }

        unset($tDocRefNo);
        unset($tBchRef);
        unset($aJOB2_Update);
        unset($tSQL);
        unset($oQuery);
    }

    // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
    public function FSaMIASCheckJOB3Approve($paDataUpdateHD){
        $tDocRefNo      = $paDataUpdateHD['tDocJOB2'];
        $tSQL           = "SELECT TOP 1 JOB3.FTXshDocNo FROM TSVTJob3ChkHDDocRef JOB3 WITH(NOLOCK) WHERE JOB3.FTXshRefDocNo = '$tDocRefNo' AND JOB3.FTXshRefKey = 'Job2Ord' ";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData      = $oQuery->result_array();
            $tDocNo     = $aData[0]['FTXshDocNo'];
            
            //Update ข้อมูล
            $this->db->set('FTXshStaApv',1);
            $this->db->where('FTXshDocNo', $tDocNo);
            $this->db->update('TSVTJob3ChkHD');
            unset($aData);
            unset($tDocNo);
        }
        unset($tDocRefNo);
        unset($tSQL);
        unset($oQuery);
    }

}
