<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prerepairresult_model extends CI_Model
{
    //ชื่อกลุ่มเอกสาร
    public $tQaGrpCode = '00015';

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMPreGetDataTableList($paDataCondition)
    {
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
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

        $tSQL   =   " SELECT TOP ". get_cookie('nShowRecordInPageList')." c.* FROM(
                            SELECT  --ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,
                            * FROM
                                (   SELECT DISTINCT
                                        Job3HD.FTAgnCode,
                                        Job3HD.FTBchCode,
                                        BCHL.FTBchName,
                                        Job3HD.FTXshDocNo,
                                        CONVERT(CHAR(10),Job3HD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), Job3HD.FDXshDocDate,108) AS FTXshDocTime,
                                        DocRef.FTXshRefDocNo,
                                        CONVERT(CHAR(10),DocRef.FDXshRefDocDate,103) AS FDXshRefDocDate,
                                        CONVERT(CHAR(5), DocRef.FDXshRefDocDate,108) AS FDXshRefIntTime,
                                        Job3HD.FTXshStaDoc,
                                        Job3HD.FTXshStaApv,
                                        Job3HD.FTCreateBy,
                                        Job3HD.FDCreateOn,
                                        CREBY.FTUsrName as FTCreateByName
                                    FROM TSVTJob3ChkHD  Job3HD             WITH (NOLOCK)
                                    LEFT JOIN TSVTJob3ChkHDDocRef  DocRef  WITH (NOLOCK) ON DocRef.FTXshDocNo    = Job3HD.FTXshDocNo AND DocRef.FTXshRefType = '1'
                                    LEFT JOIN TCNMBranch_L  BCHL             WITH (NOLOCK) ON Job3HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L CREBY               WITH (NOLOCK) ON CREBY.FTUsrCode = Job3HD.FTCreateBy AND CREBY.FNLngID = $nLngID
                                WHERE Job3HD.FTXshDocNo != '' 
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND Job3HD.FTBchCode IN ($tBchCode)
            ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if (isset($tSearchList) && !empty($tSearchList)) {
            $tSQL .= " AND ((Job3HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job3HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
            $tSQL .= " AND ((Job3HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job3HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL .= " AND ((Job3HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job3HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if (isset($tSearchStaDoc) && !empty($tSearchStaDoc)) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND Job3HD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(Job3HD.FTXshStaApv,'') = '' AND Job3HD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND Job3HD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if (isset($tSearchStaApprove) && !empty($tSearchStaApprove)) {
            if ($tSearchStaApprove == 2) {
                $tSQL .= " AND Job3HD.FTXshStaApv = '$tSearchStaApprove' OR Job3HD.FTXshStaApv = '' ";
            } else {
                $tSQL .= " AND Job3HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job3HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job3HD.FNXshStaDocAct = 0";
            }
        }

        $tSQL   .=  ") Base) AS c ORDER BY c.FDCreateOn DESC";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            // $aDataCountAllRow   = $this->FSnMPreSvCountPageDocListAll($paDataCondition);
            // $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1') ? $aDataCountAllRow['rtCountData'] : 0;
            // $nPageAll           = ceil($nFoundRow / $paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                // 'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                // 'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
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
    public function FSnMPreSvCountPageDocListAll($paDataCondition)
    {
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

        $tSQL   =   "   SELECT COUNT (Job3HD.FTXshDocNo) AS counts
                        FROM TSVTJob3ChkHD  Job3HD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON Job3HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1
                    ";

        // Check User Login Branch
        if (isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])) {
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND Job3HD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if (isset($tSearchList) && !empty($tSearchList)) {
            $tSQL .= " AND ((Job3HD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),Job3HD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
            $tSQL .= " AND ((Job3HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (Job3HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL .= " AND ((Job3HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (Job3HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if (isset($tSearchStaApprove) && !empty($tSearchStaApprove)) {
            if ($tSearchStaApprove == 2) {
                $tSQL .= " AND Job3HD.FTXshStaApv = '$tSearchStaApprove' OR Job3HD.FTXshStaApv = '' ";
            } else {
                $tSQL .= " AND Job3HD.FTXshStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND Job3HD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND Job3HD.FNXshStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
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
    public function FSaMPreAddUpdateHD($paDataAddHD, $paDataPrimaryKey)
    {
        try {
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableHD'];

            $nChhkData = $this->FSaMPreChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if (isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1) {
                $this->db->where('FTAgnCode', $tAgnCode);
                $this->db->where('FTBchCode', $tBchCode);
                $this->db->where('FTXshDocNo', $tDocNo);
                $this->db->update('TSVTJob3ChkHD', $paDataAddHD);
            } else {
                //เพิ่มใหม่
                $this->db->insert('TSVTJob3ChkHD', $paDataAddHD);
            }

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert HD success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
    public function FSaMPreUpdateJOB2HD($paDataUpdateHD){

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
        $tSQL   = "SELECT TOP 1 JOB1.FTXshDocNo FROM TSVTJob1ReqHDDocRef JOB1 WHERE JOB1.FTXshRefDocNo = '$tDocRefNo' AND JOB1.FTXshRefKey = 'Job2Ord' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData      = $oQuery->result_array();
            $tDocJOB1   =  $aData[0]['FTXshDocNo'];
            $aJOB1_Update = array(
                'FTXshCarFuel'      => $paDataUpdateHD['FTXshCarfuel'],
                'FCXshCarMileage'   => $paDataUpdateHD['FCXshCarMileage']
            );
            $this->db->where('FTBchCode', $tBchRef);
            $this->db->where('FTXshDocNo', $tDocJOB1);
            $this->db->update('TSVTJob1ReqHD', $aJOB1_Update);
        }
    }

    // insert ข้อมูลลงตาราง  DT
    public function FSaMPreQaAddUpdateDT($paDataDT, $paDataPrimaryKey)
    {
        try {
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableDT'];

            $nChhkData = $this->FSaMPreChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if (isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1) {
                //ลบ
                $this->db->where_in('FTAgnCode', $tAgnCode);
                $this->db->where_in('FTBchCode', $tBchCode);
                $this->db->where_in('FTXshDocNo', $tDocNo);
                $this->db->delete('TSVTJob5ScoreDT');

                foreach ($paDataDT as $key => $tPreVal) {
                    $tPreQaVal = $tPreVal['atPreQue'];

                    $aData = array(
                        'FTAgnCode'         => $tPreVal['FTAgnCode'],
                        'FTBchCode'         => $tPreVal['FTBchCode'],
                        'FTXshDocNo'        => $tPreVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tPreVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tPreQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tPreQaVal['nSeqDt'],
                        'FTQahType'         => $tPreQaVal['nQueType'],
                        'FDLastUpdOn'       => $tPreVal['FDLastUpdOn'],
                        'FTLastUpdBy'       => $tPreVal['FTLastUpdBy'],
                        'FDCreateOn'        => $tPreVal['FDCreateOn'],
                        'FTCreateBy'        => $tPreVal['FTCreateBy']
                    );

                    //อัพเดท
                    $this->db->insert('TSVTJob5ScoreDT', $aData);
                }
                //หากพบว่าไม่ซ้ำ
            } else {
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tPreVal) {
                    $tPreQaVal = $tPreVal['atPreQue'];

                    $aData = array(
                        'FTAgnCode'         => $tPreVal['FTAgnCode'],
                        'FTBchCode'         => $tPreVal['FTBchCode'],
                        'FTXshDocNo'        => $tPreVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tPreVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tPreQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tPreQaVal['nSeqDt'],
                        'FTQahType'         => $tPreQaVal['nQueType'],
                        'FDLastUpdOn'       => $tPreVal['FDLastUpdOn'],
                        'FTLastUpdBy'       => $tPreVal['FTLastUpdBy'],
                        'FDCreateOn'        => $tPreVal['FDCreateOn'],
                        'FTCreateBy'        => $tPreVal['FTCreateBy']
                    );

                    $this->db->insert('TSVTJob5ScoreDT', $aData);
                }
            }
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DT success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    // insert ข้อมูลลงตาราง  ANSDT
    public function FSaMPreQaAddUpdateAnsDT($paDataDT, $paDataPrimaryKey)
    {
        try {
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tTable   = $paDataPrimaryKey['tTableAnsDT'];
            $FTSessionID   = $paDataPrimaryKey['FTSessionID'];

            $nChhkData = $this->FSaMPreChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if (isset($nChhkData['rtCode']) && $nChhkData['rtCode'] == 1) {
                //ลบ
                $this->db->where_in('FTAgnCode', $tAgnCode);
                $this->db->where_in('FTBchCode', $tBchCode);
                $this->db->where_in('FTXshDocNo', $tDocNo);
                $this->db->delete('TSVTJob5ScoreDTAns');

                //อัพเดท
                foreach ($paDataDT as $key => $tPreVal) {
                    $tPreQaVal = $tPreVal['atPreAns'];

                    $aData = array(
                        'FTAgnCode'         => $tPreVal['FTAgnCode'],
                        'FTBchCode'         => $tPreVal['FTBchCode'],
                        'FTXshDocNo'        => $tPreVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tPreVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tPreQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tPreQaVal['nSeqDt'],
                        'FNQasResSeq'       => $tPreQaVal['nSeqAs'],
                        'FTXsdStaAnsValue'  => $tPreQaVal['tResVal'],
                        'FTXsdAnsValue'     => $tPreQaVal['tResName']
                    );

                    $this->db->insert('TSVTJob5ScoreDTAns', $aData);
                }
                //หากพบว่าไม่ซ้ำ
            } else {
                //เพิ่มใหม่
                foreach ($paDataDT as $key => $tPreVal) {
                    $tPreQaVal = $tPreVal['atPreAns'];

                    $aData = array(
                        'FTAgnCode'         => $tPreVal['FTAgnCode'],
                        'FTBchCode'         => $tPreVal['FTBchCode'],
                        'FTXshDocNo'        => $tPreVal['FTXshDocNo'],
                        'FTXsdSeq'          => $tPreVal['FTXsdSeq'],
                        'FTQahDocNo'        => $tPreQaVal['tDocNo'],
                        'FNQadSeqNo'        => $tPreQaVal['nSeqDt'],
                        'FNQasResSeq'       => $tPreQaVal['nSeqAs'],
                        'FTXsdStaAnsValue'  => $tPreQaVal['tResVal'],
                        'FTXsdAnsValue'     => $tPreQaVal['tResName']
                    );

                    $this->db->insert('TSVTJob5ScoreDTAns', $aData);
                }
            }
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert AnsDT success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    public function FSaMPreAddUpdateRefDocHD($paDataJob3AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $paDataPrimaryKey)
    {
        try {
            $tTable     = $paDataPrimaryKey['tTableDocRef3'];
            $tTableRef  = $paDataPrimaryKey['tTableDocRef2'];

            $nChhkDataDocRef3  = $this->FSaMPreChkRefDupicate($paDataPrimaryKey, $tTable, $paDataJob3AddDocRef['FTXshRefDocNo'], $paDataJob3AddDocRef['FTXshRefType']);
            //หากพบว่าซ้ำ
            // if (isset($nChhkDataDocRef3['rtCode']) && $nChhkDataDocRef3['rtCode'] == 1) {
            //ลบ
            $this->db->where_in('FTAgnCode', $paDataJob3AddDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paDataJob3AddDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $paDataJob3AddDocRef['FTXshDocNo']);
            $this->db->where_in('FTXshRefType', $paDataJob3AddDocRef['FTXshRefType']);
            $this->db->delete($tTable);

            //เพิ่มใหม่
            $this->db->insert($tTable, $paDataJob3AddDocRef);
            //หากพบว่าไม่ซ้ำ
            // } else {
            //     $this->db->insert($tTable, $paDataJob3AddDocRef);
            // }

            $nChhkDataDocRef2  = $this->FSaMPreChkRefDupicate($paDataPrimaryKey, $tTableRef, $aDataJob2AddDocRef['FTXshRefDocNo'], $aDataJob2AddDocRef['FTXshRefType']);
            //หากพบว่าซ้ำ
            // if (isset($nChhkDataDocRef2['rtCode']) && $nChhkDataDocRef2['rtCode'] == 1) {
            //ลบ
            $this->db->where_in('FTAgnCode', $aDataJob2AddDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode', $aDataJob2AddDocRef['FTBchCode']);
            $this->db->where_in('FTXshRefType', $aDataJob2AddDocRef['FTXshRefType']);
            $this->db->where_in('FTXshRefDocNo', $aDataJob2AddDocRef['FTXshRefDocNo']);
            $this->db->where_in('FTXshRefKey', $aDataJob2AddDocRef['FTXshRefKey']);
            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef, $aDataJob2AddDocRef);
            //หากพบว่าไม่ซ้ำ
            // } else {
            //     $this->db->insert($tTableRef, $aDataJob2AddDocRef);
            // }

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

        return $aReturnData;
    }

    public function FSaMPreAddUpdateRefDocHDEX($paDataJob3AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $paDataPrimaryKey)
    {
        try {
            $tTable     = $paDataPrimaryKey['tTableDocRef3'];

            $this->db->where('FTAgnCode', $paDataJob3AddDocRef['FTAgnCode']);
            $this->db->where('FTBchCode', $paDataJob3AddDocRef['FTBchCode']);
            $this->db->where('FTXshDocNo', $paDataJob3AddDocRef['FTXshDocNo']);
            $this->db->where('FTXshRefType', $paDataJob3AddDocRef['FTXshRefType']);
            $this->db->delete($tTable);

            $this->db->insert($tTable, $paDataJob3AddDocRef);


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

        return $aReturnData;
    }

    // Functionality    : Update DocNo In Doc Temp
    // Parameters       : function parameters
    // Creator          : 03/07/2019 Wasin(Yoshi)
    // Last Modified    : 24/02/2021 supawat
    // Return           : Array Status Update DocNo In Doc Temp
    // Return Type      : array
    public function FSxMPreAddUpdateDocNoToTemp($paDataWhere, $paTableAddUpdate)
    {
        // Update DocNo Into DTTemp
        $this->db->set('FTXshDocNo', $paDataWhere['FTXphDocNo']);
        $this->db->where('FTXshDocNo', '');
        $this->db->where('FTUsrSess', $paDataWhere['FTSessionID']);
        $this->db->update('TSVTJob3ChkDTTmp');
        return;
    }

    // Functionality    : Move Document DTTemp To Document DT
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Off
    // Return           : Array Status Insert Tempt To DT
    // Return Type      : array
    public function FSaMPreMoveDtTmpToDt($paDataWhere, $paTableAddUpdate)
    {
        $tPREBchCode     = $paDataWhere['FTBchCode'];
        $tPREDocNo       = $paDataWhere['FTXphDocNo'];
        $FTAgnCode       = $paDataWhere['FTAgnCode'];
        $FDLastUpdOn       = $paDataWhere['FDLastUpdOn'];
        $FDCreateOn       = $paDataWhere['FDCreateOn'];
        $FTCreateBy       = $paDataWhere['FTCreateBy'];
        $FTLastUpdBy       = $paDataWhere['FTLastUpdBy'];
        $tPRESessionID   = $this->session->userdata('tSesSessionID');


        if (isset($tPREDocNo) && !empty($tPREDocNo)) {
            $this->db->where('FTXshDocNo', $tPREDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO " . $paTableAddUpdate['tTableDT'] . " (
                        FTAgnCode,FTBchCode,FTXshDocNo,FTXsdSeq,FTPdtCode,FTPdtCodeSub,FTQahType,
                        FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            '$FTAgnCode' AS FTAgnCode,
                            '$tPREBchCode' AS FTBchCode,
                            DOCTMP.FTXshDocNo,
                            DOCTMP.FNXsdSeqNo,
                            DOCTMP.FTPdtCode,
                            DOCTMP.FTPdtCodeSub,
                            DOCTMP.FTPdtChkType,
                            '$FDLastUpdOn' AS FDLastUpdOn,
                            '$FTLastUpdBy' AS FTLastUpdBy,
                            '$FDCreateOn' AS FDCreateOn,
                            '$FTCreateBy' AS FTCreateBy
                        FROM TSVTJob3ChkDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTXshDocNo   = '$tPREDocNo'
                        AND DOCTMP.FTUsrSess  = '$tPRESessionID'
                        ORDER BY DOCTMP.FNXsdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // Functionality    : Move Document DTTemp To Document DT
    // Parameters       : function parameters
    // Creator          : 14/10/2021 Off
    // Return           : Array Status Insert Tempt To DT
    // Return Type      : array
    public function FSaMPreMoveDtTmpToDtAns($paDataWhere, $paTableAddUpdate)
    {
        $tPREBchCode     = $paDataWhere['FTBchCode'];
        $tPREDocNo       = $paDataWhere['FTXphDocNo'];
        $FTAgnCode       = $paDataWhere['FTAgnCode'];
        $tPRESessionID   = $this->session->userdata('tSesSessionID');

        $tSQLGetAnwserDetail = " SELECT 
        FTXshDocNo,
        FTPdtCode,
        FNXsdSeqNo,
        FTXsdPdtName,
        FTPdtCodeSub,
        FTPsvStaSuggest,
        FTSetChkName,
        FTSetChkSeq,
        FTPdtChkType,
        FNPdtSrvSeq,
        FTXsdStaAnsValue,
        FTXsdAnsValue,
        FTUsrSess
        FROM TSVTJob3ChkDTTmp
        WHERE FTXshDocNo = '$tPREDocNo' AND FTUsrSess = '$tPRESessionID'";

        $oQueryGetAnwserDetail = $this->db->query($tSQLGetAnwserDetail);
        $raAnwserDetail = $oQueryGetAnwserDetail->result_array();

        if (isset($tPREDocNo) && !empty($tPREDocNo)) {
            $this->db->where('FTXshDocNo', $tPREDocNo);
            $this->db->delete($paTableAddUpdate['tTableAnsDT']);
        }

        foreach ($raAnwserDetail as $nKey => $aValue) {
            $tAnsCheck = explode(";", $aValue['FNPdtSrvSeq']);
            $aDataAnswer[$nKey] = array(
                'FTAgnCode'         => $FTAgnCode,
                'FTBchCode'         => $tPREBchCode,
                'FTXshDocNo'        => $aValue['FTXshDocNo'],
                'FTXsdSeq'          => $aValue['FNXsdSeqNo'],
                'FTPdtCode'         => $aValue['FTPdtCode'],
                'FTPdtCodeSub'      => $aValue['FTPdtCodeSub'],
                'FTXsdAnsValue'     => $aValue['FTXsdAnsValue'],
            );
            foreach ($tAnsCheck as $nKey2 => $aValue2) {
                $aDataAnswer[$nKey]['FNPdtSrvSeq'] = $aValue2;
                $aDataAnswer[$nKey]['FTXsdStaAnsValue'] = $aValue2;
                $this->db->insert('TSVTJob3ChkDTAns', $aDataAnswer[$nKey]);
            }
        }
        // $this->db->insert_batch('TSVTJob3ChkDTAns', $aDataAnswer); 

        $this->db->where('FTXshDocNo ', $tPREDocNo);
        $this->db->where('FTUsrSess ', $tPRESessionID);
        $this->db->delete('TSVTJob3ChkDTTmp');

        return;
    }


    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMPreChkDupicate($paDataPrimaryKey, $ptTable)
    {
        try {
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
            if ($oQueryHD->num_rows() > 0) {
                $aDetail = $oQueryHD->row_array();
                $aResult    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            } else {
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMPreChkRefDupicate($paDataPrimaryKey, $ptTable, $tRefDocNo, $tRefDocType)
    {
        try {
            if ($ptTable == 'TSVTJob3ChkHDDocRef') {
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
                            AND FTXshRefDocNo = '$tRefDocNo'
                        ";
            } else {
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
                            AND FTXshDocNo    = '$tRefDocNo'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tDocNo'
                        ";
            }
            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0) {
                $aDetail = $oQueryHD->row_array();
                $aResult    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            } else {
                $aResult    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //ดึงคำตอบมาแสดงแบบ Add
    public function FSaMPreQaViewAnswer()
    {
        try {
            $tSQL = "SELECT 
                            HD.FTQahDocNo , 
                            Grp.FTQsgCode , 
                            DT.FNQadSeqNo , 
                            Grp.FTQsgName , 
                            DT.FTQadName , 
                            DT.FTQadType ,
                            ANS.FNQasResuitSeq , 
                            ANS.FNQasResuitName
                        FROM TCNTQaHD HD
                        INNER JOIN TCNMQasSubGrp_L Grp ON Grp.FTQsgCode = HD.FTQsgCode AND Grp.FNLngID = 1
                        LEFT JOIN TCNTQaDT DT ON HD.FTQahDocNo = DT.FTQahDocNo
                        LEFT JOIN TCNTQaDTAns ANS ON DT.FTQahDocNo = ANS.FTQahDocNo AND DT.FNQadSeqNo = ANS.FNQadSeqNo
                        WHERE HD.FTQgpCode = '$this->tQaGrpCode'
                    ";
            $oQueryQA = $this->db->query($tSQL);
            $aList = $oQueryQA->result_array();
            $aResult = array(
                'raItems' => $aList
            );
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ลบข้อมูลใน Temp
    public function FSnMPreDelALLTmp($paData)
    {
        try {
            $this->db->trans_begin();

            $this->db->where('FTUsrSess', $paData['FTSessionID']);
            $this->db->delete('TSVTJob3ChkDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ข้อมูล HD
    public function FSaMPreGetDataDocHD($ptAgnCode, $ptBchCode, $ptDocNo){
        
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT
                            DOCHD.FTBchCode,
                            DOCHD.FTAgnCode,
                            DOCHD.FTXshDocNo,
                            DOCHD.FTXshStaDoc,
                            DOCHD.FTXshRmk,
                            DOCHD.FTXshCarfuel,
                            DOCHD.FTXshApvCode,
                            DOCHD.FTXshStaApv,
                            CREBY.FTUsrName as FTNameCreateBy,
                            DOCHD.FDCreateOn AS DateOn,
                            DOCHD.FTCreateBy AS FTCreateBy,
                            DOCHD.FDXshDocDate,
                            DOCREFIN.FDXshRefDocDate AS FDXshRefDocDate,
                            DOCREFIN.FTXshRefDocNo AS FTXshRefDocNo,
                            DOCREFEX.FDXshRefDocDate AS FDXshRefExDocDate,
                            DOCREFEX.FTXshRefDocNo AS FTXshRefExDocNo ,
                            BCHL.FTBchName
                        FROM TSVTJob3ChkHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L  BCHL          WITH (NOLOCK) ON DOCHD.FTBchCode  = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                        LEFT JOIN TCNMUser_L CREBY            WITH (NOLOCK) ON CREBY.FTUsrCode  = DOCHD.FTCreateBy  AND CREBY.FNLngID = '$nLngID'
                        LEFT JOIN TSVTJob3ChkHDDocRef        DOCREFIN   WITH (NOLOCK)   ON DOCHD.FTXshDocNo      = DOCREFIN.FTXshDocNo   
                        AND DOCREFIN.FTXshRefKey	= 'Job2Ord' 
                        AND DOCREFIN.FTXshRefType	= '1'
                        AND DOCREFIN.FTBchCode	= DOCHD.FTBchCode
                        AND DOCREFIN.FTAgnCode	= DOCHD.FTAgnCode
                        LEFT JOIN TSVTJob3ChkHDDocRef        DOCREFEX   WITH (NOLOCK)   ON DOCHD.FTXshDocNo      = DOCREFEX.FTXshDocNo   
                        AND DOCREFEX.FTXshRefKey	= 'Job3Chk' 
                        AND DOCREFEX.FTXshRefType	= '3'
                        AND DOCREFEX.FTBchCode	= DOCHD.FTBchCode
                        AND DOCREFEX.FTAgnCode	= DOCHD.FTAgnCode
                        WHERE DOCHD.FTAgnCode = '$ptAgnCode' AND DOCHD.FTBchCode = '$ptBchCode'  AND ( DOCHD.FTXshDocNo = '$ptDocNo' OR (DOCREFIN.FTXshRefDocNo = '$ptDocNo' AND DOCREFIN.FTXshRefType = '1' )) ";
        // WHERE 1=1 AND DOCHD.FTXshDocNo = '$tPreDocNo'
        // print_r($tSQL);
        // die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    //ข้อมูล JOB2 HD
    // public function FSaMPreGetDataJob2HD($paDataWhere)
    public function FSaMPreGetDataJob2HD($ptAgnCode, $ptBchCode, $ptDocNo)
    {
        // $nLngID     = $paDataWhere['FNLngID'];
        // $nRefDoc     = $paDataWhere['RefDocNo'];
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT
            TCNMBranch_L.FTBchName,
            TSVTJob2OrdHD.FTXshDocNo,
            TSVTJob2OrdHD.FDXshDocDate,
            TCNMCst_L.FTCstName,
            TSVMCar.FTCarRegNo,
            T1.FTCaiName AS FTCarBrand,
            T2.FTCaiName AS FTCarModel,
            TCNMCst.FTCstTel,
            TCNMCst.FTCstEmail,
            TCNMAgency_L.FTAgnName,
            TCNMAgency_L.FTAgnCode,
            TCNMBranch_L.FTBchCode,
            TSVTJob2OrdHD.FTCstCode,
            TSVMCar.FTCarCode,
            USRL.FTUsrName,
            TSVTJob2OrdHD.FDXshTimeStart,
            TSVTJob2OrdHD.FTUsrCode,
            TSVTJob2OrdHD.FCXshCarMileage,
            TSVTJob2OrdHD.FTXshRmk,
            TSVTJob2OrdHD.FTXshCarChkRmk1,
            TSVTJob2OrdHD.FTXshCarChkRmk2
        FROM
            TSVTJob2OrdHD
            LEFT JOIN TCNMCst ON TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode
            LEFT JOIN TCNMCst_L ON TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode 
            AND TCNMCst_L.FNLngID = '$nLngID'
            LEFT JOIN TCNMAgency_L ON TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode 
            AND TCNMAgency_L.FNLngID = '$nLngID'
            LEFT JOIN TSVTJob2OrdHDCst J2HDCst ON TSVTJob2OrdHD.FTXshDocNo = J2HDCst.FTXshDocNo
            LEFT JOIN TSVMCar ON J2HDCst.FTCarCode = TSVMCar.FTCarCode
            LEFT JOIN TSVMCarInfo_L T1 ON TSVMCar.FTCarBrand  = T1.FTCaiCode 
            AND T1.FNLngID = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T2 ON TSVMCar.FTCarModel  = T2.FTCaiCode 
            AND T2.FNLngID = '$nLngID'
            LEFT JOIN TCNMBranch_L ON TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode 
            AND TCNMBranch_L.FNLngID = '$nLngID'
            LEFT JOIN TSVTJob3ChkHDDocRef JOB3 ON JOB3.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo 
            AND JOB3.FTXshRefType = '$nLngID'
            LEFT JOIN TCNMUser_L USRL ON USRL.FTUsrCode = TSVTJob2OrdHD.FTUsrCode 
            AND USRL.FNLngID = '$nLngID' 
        WHERE
            TSVTJob2OrdHD.FTAgnCode = '$ptAgnCode' AND TSVTJob2OrdHD.FTBchCode = '$ptBchCode'  AND TSVTJob2OrdHD.FTXshDocNo = '$ptDocNo' ";
        // AND TSVTJob2OrdHD.FTXshDocNo = '$nRefDoc'

        // print_r($tSQL); die();

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    public function FSaMPreCountXsdSeq($paDataPrimaryKey)
    {
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
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['FTXsdSeq'];
        } else {
            $nResult    = 0;
        }
        return empty($nResult) ? 0 : $nResult;
    }

    public function FSaMPreGetDataHD($ptAgnCode, $ptBchCode, $ptDocNo)
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
                            CAR.FTCarRegNo, 
                            T1.FTCaiName as FTCarBrand, 
                            T2.FTCaiName as FTCarModel,
                            HD.FTUsrCode,
                            USR.FTUsrName as PreSvBy,
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
                        WHERE 1=1 AND HD.FTAgnCode = '$ptAgnCode' AND HD.FTBchCode = '$ptBchCode'  AND HD.FTXshDocNo = '$ptDocNo' 
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
        return $aReturnData;
    }

    public function FSaMPreGetDataDT($ptAgnCode, $ptBchCode, $ptDocNo)
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
                        A.FNQadSeqNo * 1 ASC
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
        return $aReturnData;
    }

    //อนุมัตเอกสาร
    public function FSaMPreApproveDocument($paDataUpdate)
    {
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');

            $this->db->set('FDLastUpdOn', $dLastUpdOn);
            $this->db->set('FTLastUpdBy', $tLastUpdBy);
            $this->db->set('FTXshStaApv', $paDataUpdate['FTXshStaApv']);
            $this->db->set('FTXshApvCode', $paDataUpdate['FTXshApvCode']);
            $this->db->where_in('FTAgnCode', $paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob3ChkHD');

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
    public function FSaMPreCancelDocument($paDataUpdate, $aDataWhereDocRef)
    {
        try {
            $this->db->set('FTXshStaDoc', 3);
            $this->db->where_in('FTAgnCode', $paDataUpdate['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paDataUpdate['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->update('TSVTJob3ChkHD');

            // DT
            $this->db->where_in('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->delete('TSVTJob3ChkDT');

            // DT Ans
            $this->db->where_in('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->delete('TSVTJob3ChkDTAns');

            //ลบ TSVTJob5ScoreHDDocRef
            $this->db->where_in('FTAgnCode', $aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode', $aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $aDataWhereDocRef['FTXshDocNo']);
            $this->db->where_in('FTXshRefType', $aDataWhereDocRef['FTXshRefType']);
            $this->db->where_in('FTXshRefDocNo', $aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->delete('TSVTJob3ChkHDDocRef');

            //ลบ TSVTJob2OrdHDDocRef
            $this->db->where_in('FTAgnCode', $aDataWhereDocRef['FTAgnCode']);
            $this->db->where_in('FTBchCode', $aDataWhereDocRef['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $aDataWhereDocRef['FTXshRefDocNo']);
            $this->db->where_in('FTXshRefType', 2);
            $this->db->where_in('FTXshRefDocNo', $aDataWhereDocRef['FTXshDocNo']);
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
    public function FSnMPreDelDocument($paDataDoc)
    {
        try {
            $tDataDocNo = $paDataDoc['tDataDocNo'];
            $this->db->trans_begin();

            // HD
            $this->db->where_in('FTXshDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob3ChkHD');

            // HD Doc Ref
            $this->db->where_in('FTXshDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob3ChkHDDocRef');

            // DT
            $this->db->where_in('FTXshDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob3ChkDT');

            // DT Ans
            $this->db->where_in('FTXshDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob3ChkDTAns');

            // Job2HD Doc Ref
            $this->db->where_in('FTXshRefDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob2OrdHDDocRef');

            // Job3DT Tmp
            $this->db->where_in('FTXshDocNo', $tDataDocNo);
            $this->db->delete('TSVTJob3ChkDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStaDelDoc     = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Cannot Delete Item.',
                );
            } else {
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

    public function FSaMPreSvGetDataWhereJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT 
                            JOB2HD.FTAgnCode,
                            JOB2HD.FTBchCode,
                            JOB3REF.FTXshDocNo
                        FROM TSVTJob5ScoreHDDocRef JOB3REF
                        INNER JOIN TSVTJob2OrdHD JOB2HD ON JOB2HD.FTXshDocNo = JOB3REF.FTXshRefDocNo
                        WHERE JOB3REF.FTXshRefDocNo = '$ptDocCode'
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aData = $oQuery->result_array();
                $aReturnData = array(
                    'rtCode'        => '1',
                    'rtDesc'        => 'Found',
                    'aRtData'       => $aData
                );
            } else {
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

    // Functionality : Get Anwser Datail
    // Parameters : Ajax and Function Parameter
    // Creator : 07/10/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSaMPreGetAnswerDetail($paDataWhere)
    {
        $FTXshDocNo = $paDataWhere['FTXshDocNo'];
        $FTUsrSess = $paDataWhere['FTUsrSess'];
        $tRefDoc = $paDataWhere['tRefDoc'];
        $nCondition = $paDataWhere['nCondition'];
        $tType = $paDataWhere['tType'];

        $this->db->where('FTXshDocNo ', $FTXshDocNo);
        $this->db->where('FTUsrSess ', $FTUsrSess);
        $this->db->delete('TSVTJob3ChkDTTmp');

        try {
        if($tType == '2'){
            if ($nCondition == '0') {
                $tSQLAnwserDetail = "INSERT INTO TSVTJob3ChkDTTmp
                SELECT '$FTXshDocNo' AS FTXshDocNo,
                T.*, 
                CHO.FTSetChkName, 
                CHO.FTSetChkSeq, 
                CHO.FTPdtChkType, 
                '0' AS FNPdtSrvSeq, 
                '0' AS FTXsdStaAnsValue, 
                ' ' AS FTXsdAnsValue,
                '$FTUsrSess' AS FTUsrSess";
            } else {
                $tSQLAnwserDetail = "INSERT INTO TSVTJob3ChkDTTmp
                SELECT '$FTXshDocNo' AS FTXshDocNo,
                T.*, 
                CHO.FTSetChkName, 
                CHO.FTSetChkSeq, 
                CHO.FTPdtChkType, 
                FNPdtSrvSeq, 
                FTXsdStaAnsValue, 
                FTXsdAnsValue,
                '$FTUsrSess' AS FTUsrSess";
            }
            $tSQLAnwserDetail .= "
     FROM
     (
         SELECT DT.FTPdtCode, 
                DT.FNXsdSeqNo, 
                DST.FTXsdPdtName, 
                DST.FTPdtCode AS FTPdtCodeSub, 
                PST.FTPsvStaSuggest
         FROM TSVTJob2OrdDTSet DST
              INNER JOIN TSVTJob2OrdDT DT ON DT.FTAgnCode = DST.FTAgnCode
                                             AND DT.FTBchCode = DST.FTBchCode
                                             AND DT.FTXshDocNo = DST.FTXshDocNo
                                             AND DT.FNXsdSeqNo = DST.FNXsdSeqNo
                                             AND DST.FTPsvType = 2
              INNER JOIN TSVTPdtSet PST ON DT.FTPdtCode = PST.FTPdtCode
                                           AND DST.FTPdtCode = PST.FTPdtCodeSub
         WHERE DST.FTXshDocNo = '$tRefDoc'
     ) T
     LEFT JOIN
     (
         SELECT SCK.FTPdtCode, 
                SCK.FTPdtCodeSub, 
                SCK.FTPdtChkType, 
                STUFF(
         (
             SELECT ';' + A1.FTPdtChkResult
             FROM TSVTPdtSetChk A1
             WHERE A1.FTPdtCode = SCK.FTPdtCode
                   AND A1.FTPdtCodeSub = SCK.FTPdtCodeSub
             ORDER BY FTPdtCodeSub FOR XML PATH('')
         ), 1, 1, '') [FTSetChkName], 
                STUFF(
         (
             SELECT ';' + CAST(A2.FNPdtSrvSeq AS VARCHAR)
             FROM TSVTPdtSetChk A2
             WHERE A2.FTPdtCode = SCK.FTPdtCode
                   AND A2.FTPdtCodeSub = SCK.FTPdtCodeSub
             ORDER BY FTPdtCodeSub FOR XML PATH('')
         ), 1, 1, '') [FTSetChkSeq]
         FROM TSVTPdtSetChk SCK
         GROUP BY SCK.FTPdtCode, 
                  SCK.FTPdtCodeSub, 
                  SCK.FTPdtChkType
     ) CHO ON T.FTPdtCode = CHO.FTPdtCode
              AND T.FTPdtCodeSub = CHO.FTPdtCodeSub
     LEFT JOIN
     (
         SELECT SCK.FTPdtCode, 
                SCK.FTPdtCodeSub, 
                SCK.FTXsdSeq, 
                STUFF(
         (
             SELECT ';' + CAST(A1.FNPdtSrvSeq AS VARCHAR)
             FROM TSVTJob3ChkDTAns A1
             WHERE A1.FTPdtCode = SCK.FTPdtCode
                   AND A1.FTPdtCodeSub = SCK.FTPdtCodeSub
                   AND A1.FTXshDocNo = '$FTXshDocNo'
             ORDER BY FTPdtCodeSub FOR XML PATH('')
         ), 1, 1, '') [FNPdtSrvSeq], 
                STUFF(
         (
             SELECT ';' + CAST(A2.FTXsdStaAnsValue AS VARCHAR)
             FROM TSVTJob3ChkDTAns A2
             WHERE A2.FTPdtCode = SCK.FTPdtCode
                   AND A2.FTPdtCodeSub = SCK.FTPdtCodeSub
                   AND A2.FTXshDocNo = '$FTXshDocNo'
             ORDER BY FTPdtCodeSub FOR XML PATH('')
         ), 1, 1, '') [FTXsdStaAnsValue], 
                ISNULL(FTXsdAnsValue, '') AS FTXsdAnsValue
         FROM TSVTJob3ChkDTAns SCK
         WHERE FTXshDocNo = '$FTXshDocNo'
         GROUP BY SCK.FTPdtCode, 
                  SCK.FTPdtCodeSub, 
                  SCK.FTXsdSeq, 
                  ISNULL(FTXsdAnsValue, '')
     ) ANS ON T.FTPdtCode = ANS.FTPdtCode
              AND T.FTPdtCodeSub = ANS.FTPdtCodeSub;";

            $this->db->query($tSQLAnwserDetail);

            $nCheckAffectedRow = $this->db->affected_rows();

            // $raAnwserDetail = $oQueryAnwserDetail->result_array();
            if ($nCheckAffectedRow > 0) {
                $tSQLGetAnwserDetail = " SELECT 
                FTXshDocNo,
                FTPdtCode,
                FNXsdSeqNo,
                FTXsdPdtName,
                FTPdtCodeSub,
                FTPsvStaSuggest,
                FTSetChkName,
                FTSetChkSeq,
                FTPdtChkType,
                FNPdtSrvSeq,
                FTXsdStaAnsValue,
                FTXsdAnsValue,
                FTUsrSess
                FROM TSVTJob3ChkDTTmp
                WHERE FTXshDocNo = '$FTXshDocNo' AND FTUsrSess = '$FTUsrSess'
                ORDER BY FTXsdPdtName ASC ";
    
                $oQueryGetAnwserDetail = $this->db->query($tSQLGetAnwserDetail);

                $raAnwserDetail = $oQueryGetAnwserDetail->result_array();
                $aResult = array(
                    'raAnwserDetail' => $raAnwserDetail,
                    'rtCode'        => '1',
                );
            } else {
                $aResult = array(
                    'rtCode'        => '0',
                );
            }

            // print_r($aResult);
        }else{
            $aResult = array(
                'rtCode'        => '0',
            );
        }
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ติ๊กปกติ
    public function FSaMPreEditNormal($paDataUpdate)
    {
        try {
            $this->db->set('FNPdtSrvSeq', '0');
            $this->db->set('FTXsdStaAnsValue', '0');
            $this->db->set('FTXsdAnsValue', '');
            $this->db->where_in('FTPdtCode', $paDataUpdate['FTPdtCode']);
            $this->db->where_in('FTPdtCodeSub', $paDataUpdate['FTPdtCodeSub']);
            $this->db->where_in('FTUsrSess', $paDataUpdate['FTUsrSess']);
            $this->db->update('TSVTJob3ChkDTTmp');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Updated Tmp Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update Tmp.',
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

    //ติ๊กแก้ไข
    public function FSaMPreEditInLine($paDataUpdate)
    {
        try {
            $this->db->set('FNPdtSrvSeq', $paDataUpdate['FNPdtSrvSeq']);
            $this->db->set('FTXsdStaAnsValue', $paDataUpdate['FNPdtSrvSeq']);
            $this->db->set('FTXsdAnsValue', $paDataUpdate['FTXsdAnsValue']);
            $this->db->where_in('FTPdtCode', $paDataUpdate['FTPdtCode']);
            $this->db->where_in('FTPdtCodeSub', $paDataUpdate['FTPdtCodeSub']);
            $this->db->where_in('FTUsrSess', $paDataUpdate['FTUsrSess']);
            $this->db->update('TSVTJob3ChkDTTmp');

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Updated Tmp Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update Tmp.',
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

    //Functionality : ค้นหาข้อมูลลูกค้า - ทีอยู่
    //Parameters : Jobrequeststep1_controller
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : Array Data List
    //Return Type : Array
    public function FSaMPreGetDataCustomerAddr($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCstCode   = $paDataCondition['tCstCode'];
        $tSQL       = "
            SELECT
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
            WHERE Addr.FTCstCode = '$tCstCode'
            AND Addr.FNLngID = '1'
            AND Addr.FTAddGrpType = '1'
            AND Addr.FTAddRefNo	= '1';
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
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
        unset($oQuery);
        unset($aDataList);
        return $aDataReturn;
    }

    //Functionality : ค้นหาข้อมูลรถของลูกค้า
    //Parameters : Jobrequeststep2_controller
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : Array Data List
    //Return Type : Array
    public function FSaMPreGetDataCarCustomer($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCarCst    = $paDataCondition['tCarCstCode'];
        $tSQL       = "
            SELECT
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
                CAR.FTCarStaRedLabel
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
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID = '1'
            WHERE CAR.FTCarCode = '$tCarCst'
        ";
        $oQuery             = $this->db->query($tSQL);
        $aDataList          = $oQuery->row_array();
        $tCarOwnerCode      = $aDataList['FTCarOwnerCode'];

        $tSQL2       = "
            SELECT
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
                CST.FTCstTel,
                CST.FTCstEmail,
                Addr.FTAddLatitude
            FROM TCNMCstAddress_L Addr WITH(NOLOCK)
            LEFT JOIN TCNMSubDistrict_L SUBL WITH(NOLOCK) ON Addr.FTAddV1SubDist = SUBL.FTSudCode AND SUBL.FNLngID = '$nLngID'
            LEFT JOIN TCNMDistrict_L DSTL WITH(NOLOCK) ON Addr.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON Addr.FTAddV1PvnCode	= PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
            LEFT JOIN TCNMCst CST WITH(NOLOCK) ON Addr.FTCstCode	= CST.FTCstCode
            WHERE Addr.FTCstCode = '$tCarOwnerCode'
            AND Addr.FNLngID = '1'
            AND Addr.FTAddGrpType = '1'
            AND Addr.FTAddRefNo	= '1';
        ";
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
        return $aDataReturn;
    }

    //Functionality : ค้นหาข้อมูลJob1
    //Parameters : Jobrequeststep2_controller
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : Array Data List
    //Return Type : Array
    public function FSaMPreGetDataCarJob1($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCstCode   = $paDataCondition['tDocNo'];
        $tSQL       = "SELECT 
            HD.ftxshcarfuel,
            ISNULL(HDREF.fdxshrefdocdate, '') AS fdxshrefdocdate,
            JOB2HD.FCXshCarMileage,
            HDREF.FTXshDocNo
            FROM   tsvtjob1reqhd HD WITH (NOLOCK)
        INNER JOIN (SELECT ftagncode,
                           ftbchcode,
                           ftxshdocno,
                           ftxshrefdocno,
                           fdxshrefdocdate
                    FROM   tsvtjob1reqhddocref WITH (NOLOCK)
                    WHERE  ftxshreftype = '2'
                           AND ftxshrefdocno = '$tCstCode'
                           AND ftxshrefkey = 'Job2Ord') HDREF
                ON HDREF.ftagncode = HD.ftagncode
                   AND HDREF.ftbchcode = HD.ftbchcode
                   AND HDREF.ftxshdocno = HD.ftxshdocno
        LEFT JOIN (SELECT ftagncode,
                          ftbchcode,
                          ftxshdocno
                   FROM   tsvtjob1reqhddocref WITH (NOLOCK)
                   WHERE  ftxshreftype = '2'
                          AND ftxshrefkey = 'TSVTBookHD') HDBOOK
               ON HDBOOK.ftagncode = HDREF.ftagncode
                  AND HDBOOK.ftbchcode = HDREF.ftbchcode
                  AND HDBOOK.ftxshdocno = HDREF.ftxshdocno 
        LEFT JOIN TSVTJob2OrdHD JOB2HD WITH (NOLOCK) ON HDREF.ftagncode = JOB2HD.ftagncode AND HDREF.ftbchcode = JOB2HD.ftbchcode AND HDREF.ftxshrefdocno = JOB2HD.ftxshdocno
        ";
        // print_r($tSQL);
        // die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->row_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
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
        unset($oQuery);
        unset($aDataList);
        return $aDataReturn;
    }


    //เช็คเอกสารจากการ Jump มาผ่าน Webview
    public function FSaMPreCheckDocNo($ptAgnCode, $ptBchCode, $ptDocNo)
    {
        $tSQL = "   SELECT 
                        HD.FTXshDocNo
                    FROM TSVTJob3ChkHD HD WITH (NOLOCK)
                    LEFT JOIN TSVTJob3ChkHDDocRef DOCRef  WITH (NOLOCK) ON DOCRef.FTXshDocNo = HD.FTXshDocNo
                    WHERE HD.FTAgnCode = '$ptAgnCode' AND 
                    HD.FTBchCode = '$ptBchCode'  AND 
                    ( HD.FTXshDocNo = '$ptDocNo' OR ( DOCRef.FTXshRefDocNo = '$ptDocNo' AND DOCRef.FTXshRefType = '1' ) ) ";
                    
        // print_r($tSQL); die();
        $oQueryHD = $this->db->query($tSQL);
        if ($oQueryHD->num_rows() > 0) {
            $aReturnData = array(
                'raItems'   => $oQueryHD->result_array(),
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } else {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => 'error'
            );
        }
        return $aReturnData;
    }

    public function FSaMPreGetDataWhereJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL = "   SELECT TOP 1
                            JOB2HD.FTAgnCode,
                            JOB2HD.FTBchCode,
                            JOB3REF.FTXshDocNo
                        FROM TSVTJob3ChkHDDocRef JOB3REF
                        INNER JOIN TSVTJob2OrdHD JOB2HD ON JOB2HD.FTXshDocNo = JOB3REF.FTXshRefDocNo
                        WHERE JOB3REF.FTXshRefDocNo = '$ptDocCode' AND JOB3REF.FTXshRefType = '1' AND JOB3REF.FTXshRefKey = 'Job2Ord'
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

    public function FSaMPreGetDataJob2($ptDocCode)
    {
        try {
            $tLang = $this->session->userdata("tLangEdit");
            $tSQL       = " SELECT
                                TCNMBranch_L.FTBchName,
                                TSVTJob2OrdHD.FTXshDocNo,
                                TSVTJob2OrdHD.FDXshDocDate,
                                TCNMCst.FTCstCode,
                                TCNMCst_L.FTCstName,
                                TSVMCar.FTCarRegNo,
                                T1.FTCaiName AS FTCarBrand,
                                T2.FTCaiName AS FTCarModel,
                                TCNMCst.FTCstTel,
                                TCNMCst.FTCstEmail,
                                TCNMAgency_L.FTAgnName,
                                TCNMAgency_L.FTAgnCode,
                                TCNMBranch_L.FTBchCode,
                                TSVTJob2OrdHD.FTCstCode,
                                TSVMCar.FTCarCode,
                                USRL.FTUsrName,
                                TSVTJob2OrdHD.FDXshTimeStart,
                                TSVTJob2OrdHD.FTUsrCode 
                            FROM
                                TSVTJob2OrdHD
                                LEFT JOIN TCNMCst ON TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode
                                LEFT JOIN TCNMCst_L ON TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode 
                                AND TCNMCst_L.FNLngID = '$tLang'
                                LEFT JOIN TCNMAgency_L ON TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode 
                                AND TCNMAgency_L.FNLngID = '$tLang'
                                LEFT JOIN TSVTJob2OrdHDCst J2HDCst ON TSVTJob2OrdHD.FTXshDocNo = J2HDCst.FTXshDocNo
                                LEFT JOIN TSVMCar ON J2HDCst.FTCarCode = TSVMCar.FTCarCode
                                LEFT JOIN TSVMCarInfo_L T1 ON TSVMCar.FTCarBrand  = T1.FTCaiCode 
                                AND T1.FNLngID = '$tLang'
                                LEFT JOIN TSVMCarInfo_L T2 ON TSVMCar.FTCarModel  = T2.FTCaiCode 
                                AND T2.FNLngID = '$tLang'
                                LEFT JOIN TCNMBranch_L ON TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode 
                                AND TCNMBranch_L.FNLngID = '$tLang'
                                LEFT JOIN TSVTJob3ChkHDDocRef JOB3 ON JOB3.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo 
                                AND JOB3.FTXshRefType = '$tLang'
                                LEFT JOIN TCNMUser_L USRL ON USRL.FTUsrCode = TSVTJob2OrdHD.FTUsrCode 
                                AND USRL.FNLngID = '$tLang' 
                            WHERE TSVTJob2OrdHD.FTXshDocNo = '$ptDocCode' ";

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
