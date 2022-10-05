<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question_model extends CI_Model
{

    //Functionality : Search Question By ID
    //Parameters : function parameters
    //Creator : 22/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCQAHSearchByID($ptAPIReq, $ptMethodReq, $paData)
    {
        $tQahCode   = $paData['FTQahDocNo'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                        QAH.FTQahDocNo               AS rtQahDocNo,
                        QAH.FTQahName                AS rtQahName,
                        QAH.FDQahDateStart           AS rtQahDateStart,
                        QAH.FDQahDateStop            AS rtQahDateStop,
                        QAH.FDCreateOn               AS rtFDCreateOn,
                        QAH.FTQgpCode                AS rtQgpCode,
                        QAH.FTQsgCode                AS rtQsgCode,
                        QGPL.FTQgpName               AS rtQgpName,
                        QSGL.FTQsgName               AS rtQsgName,
                        QAH.FTQahStaActive          AS rtQahStaActive
                    FROM [TCNTQaHD] QAH with (NOLOCK)
                    LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGPL.FTQgpCode = QAH.FTQgpCode AND QGPL.FNLngID = $nLngID
                    LEFT JOIN [TCNMQasSubGrp_L] QSGL WITH (NOLOCK) ON QSGL.FTQsgCode = QAH.FTQsgCode AND QSGL.FNLngID = $nLngID
                    WHERE 1=1
                    AND  QAH.FTQahDocNo = '$tQahCode' ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : list Question
    //Parameters : function parameters
    //Creator :  21/06/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMQAHList($ptAPIReq, $ptMethodReq, $paData)
    {

        $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);

        $nLngID = $paData['FNLngID'];

        $tSesAgnCode = $paData['tSesAgnCode'];

        $tSQL = "SELECT c.* FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY rtFDCreateOn DESC, rtQahDocNo DESC) AS rtRowID,* FROM
                (SELECT DISTINCT
                    QAH.FTQahDocNo               AS rtQahDocNo,
                    QAH.FTQahName                AS rtQahName,
                    QAH.FDQahDateStart           AS rtQahDateStart,
                    QAH.FDQahDateStop            AS rtQahDateStop,
                    QAH.FDCreateOn                AS rtFDCreateOn,
                    QGPL.FTQgpName               AS rtQgpName,
                    QSGL.FTQsgName               AS rtQsgName,
                    ISNULL(t.nCountQuestion,0) 	AS rtQadSeqNo
                 FROM [TCNTQaHD] QAH WITH (NOLOCK)
                 LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGPL.FTQgpCode = QAH.FTQgpCode AND QGPL.FNLngID =  ".$this->db->escape($nLngID)."
                 LEFT JOIN [TCNMQasSubGrp_L] QSGL WITH (NOLOCK) ON QSGL.FTQsgCode = QAH.FTQsgCode AND QSGL.FNLngID =  ".$this->db->escape($nLngID)."
                 LEFT JOIN (SELECT COUNT(QAD.FNQadSeqNo) as nCountQuestion,QAD.FTQahDocNo FROM TCNTQaDT QAD WITH (nolock)GROUP BY QAD.FTQahDocNo) t ON QAH.FTQahDocNo = t.FTQahDocNo
                 WHERE 1=1 ";

        if ($tSesAgnCode != '') {
            $tSQL .= "AND QAH.FTAgnCode = '$tSesAgnCode' ";
        }

        $tSearchList = $paData['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL .= " AND (QAH.FTQahDocNo COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR QAH.FTQahName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR QGPL.FTQgpName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%'";
            $tSQL .= " OR QSGL.FTQsgName COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')";
        }

        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMQAHGetPageAll($tSearchList, $nLngID, $tSesAgnCode);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems' => $oList,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage" => $nPageAll,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        } else {
            //No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
        }

        return $aResult;
    }

    //Functionality : All Page Of Question
    //Parameters : function parameters
    //Creator :  22/05/2021 Off
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMQAHGetPageAll($ptSearchList, $ptLngID, $ptSesAgnCode)
    {

        $tSQL = "SELECT COUNT (QAH.FTQahDocNo) AS counts

                 FROM TCNTQaHD QAH WITH (NOLOCK)
                 LEFT JOIN [TCNMQasGrp_L] QGPL WITH (NOLOCK) ON QGPL.FTQgpCode = QAH.FTQgpCode AND QGPL.FNLngID = $ptLngID
                 LEFT JOIN [TCNMQasSubGrp_L] QSGL WITH (NOLOCK) ON QSGL.FTQsgCode = QAH.FTQsgCode AND QSGL.FNLngID = $ptLngID
                 WHERE 1=1 ";

        if ($ptSesAgnCode != '') {
            $tSQL  .= " AND QAH.FTAgnCode = '$ptSesAgnCode' ";
        }

        if ($ptSearchList != '') {
            $tSQL .= " AND (QAH.FTQahDocNo COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR QAH.FTQahName COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR QGPL.FTQgpName COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR QSGL.FTQsgName COLLATE THAI_BIN LIKE '%$ptSearchList%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            //No Data
            return false;
        }
    }

    //Functionality : Checkduplicate Primary
    //Parameters : function parameters
    //Creator : 22/06/2021 Off
    //Last Modified : -
    //Return : Data Count Duplicate
    //Return Type : Object
    public function FSoMQAHCheckDuplicate($ptQahCode)
    {
        $tSQL   = "SELECT COUNT(FTQahDocNo)AS counts
                   FROM TCNTQaHD WITH (NOLOCK)
                   WHERE FTQahDocNo = '$ptQahCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 22/06/2021 Off
    //Last Modified :
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMQAHAddUpdateMaster($paData){
        try {
            //Update Master
            $this->db->set('FTQahName', $paData['FTQahName']);
            $this->db->set('FDQahDateStart', $paData['FDQahDateStart']);
            $this->db->set('FDQahDateStop', $paData['FDQahDateStop']);
            $this->db->set('FTQgpCode', $paData['FTQgpCode']);
            $this->db->set('FTQsgCode', $paData['FTQsgCode']);
            $this->db->set('FTQahStaActive', $paData['FTQahStaActive']);
            $this->db->set('FDLastUpdOn','GETDATE()', false);
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->where('FTQahDocNo', $paData['FTQahDocNo']);
            $this->db->update('TCNTQaHD');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            } else {
                //Add Master
                $this->db->insert('TCNTQaHD', array(
                    'FTQahDocNo'        => $paData['FTQahDocNo'],
                    'FTQahName'         => $paData['FTQahName'],
                    'FDQahDateStart'    => $paData['FDQahDateStart'],
                    'FDQahDateStop'     => $paData['FDQahDateStop'],
                    'FTQahStaActive'    => $paData['FTQahStaActive'],
                    'FTQgpCode'         => $paData['FTQgpCode'],
                    'FTQsgCode'         => $paData['FTQsgCode'],
                    'FDLastUpdOn'       => $paData['FDCreateOn'],
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Delete Question
    //Parameters : function parameters
    //Creator : 23/06/2021 Off
    //Return : response
    //Return Type : array
    public function FSnMQAHDel($ptAPIReq,$ptMethodReq,$paData){
        try{
            $this->db->where_in('FTQahDocNo', $paData['FTQahDocNo']);
            $this->db->delete('TCNTQaHD');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : get all row data from Question
    //Parameters : -
    //Creator : 23/06/2021 Off
    //Return : array result from db
    //Return Type : array

    public function FSnMQAHGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNTQaHD WITH (NOLOCK)";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : list QuestionDetail
    //Parameters : function parameters
    //Creator :  23/06/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMQAHDetailList($paData)
    {
        try {
            $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
            $tSearchList    = $paData['tSearchAll'];
            $nQahCode       = $paData['nQahCode'];
            $nLngID         = $paData['FNLngID'];
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY rtQahDocNo ASC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        QAD.FTQahDocNo      AS rtQahDocNo,
                                        QAD.FTQadName       AS rtQadName,
                                        QAD.FTQadStaUse     AS rtQadStaUse,
                                        QAD.FTQadType       AS rtQadType,
                                        QAD.FNQadSeqNo      AS rtQadSeqNo
                                    FROM [TCNTQaDT] QAD WITH (NOLOCK)
                                    WHERE 1=1 AND QAD.FTQahDocNo  = '$nQahCode' ";

            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);

            $tSQL2       = "SELECT DISTINCT
                        QAS.FNQadSeqNo          AS rtQadSeqNo,
                        QAS.FNQasResuitSeq      AS rtQasResuitSeq,
                        QAS.FNQasResuitName     AS rtQasResuitName,
                        QAS.FNQadSeqNo      AS rtQadSeqNo
                    FROM [TCNTQaDTAns] QAS WITH (NOLOCK)
                    WHERE 1=1 AND QAS.FTQahDocNo  = '$nQahCode' ";


            $oQuery2 = $this->db->query($tSQL2);

            if ($oQuery->num_rows() > 0) {
                $aList = $oQuery->result_array();
                $aList2 = $oQuery2->result_array();
                $oFoundRow = $this->FSoMQADGetDetailPageAll($tSearchList, $nQahCode, $nLngID);
                $nFoundRow = $oFoundRow[0]->counts;
                $nPageAll = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult = array(
                    'raItems'       => $aList,
                    'raItems2'       => $aList2,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            } else {
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage" => 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }


    //Functionality : list QuestionDetail
    //Parameters : function parameters
    //Creator :  23/06/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaMQAHDetailListPreview($paData)
    {
        try {
            $nQahCode       = $paData['FTQahDocNo'];
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY rtQahDocNo ASC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        QAD.FTQahDocNo      AS rtQahDocNo,
                                        QAD.FTQadName       AS rtQadName,
                                        QAD.FTQadStaUse     AS rtQadStaUse,
                                        QAD.FTQadType       AS rtQadType,
                                        QAD.FNQadSeqNo      AS rtQadSeqNo
                                    FROM [TCNTQaDT] QAD WITH (NOLOCK)
                                    WHERE 1=1 AND QAD.FTQahDocNo  = '$nQahCode' ";

            $tSQL .= ") Base) AS c";

            $oQuery = $this->db->query($tSQL);

            $tSQL2       = "SELECT DISTINCT
                        QAS.FNQadSeqNo          AS rtQadSeqNo,
                        QAS.FNQasResuitSeq      AS rtQasResuitSeq,
                        QAS.FNQasResuitName     AS rtQasResuitName,
                        QAS.FNQadSeqNo      AS rtQadSeqNo
                    FROM [TCNTQaDTAns] QAS WITH (NOLOCK)
                    WHERE 1=1 AND QAS.FTQahDocNo  = '$nQahCode' ";


            $oQuery2 = $this->db->query($tSQL2);

            if ($oQuery->num_rows() > 0) {
                $aList = $oQuery->result_array();
                $aList2 = $oQuery2->result_array();
                $aResult = array(
                    'raItems'       => $aList,
                    'raItems2'       => $aList2,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            } else {
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    "rnAllPage" => 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : All Page Of QuestionDetail
    //Parameters : function parameters
    //Creator :  28/05/2021 Off
    //Return : object Count All QuestionDetail
    //Return Type : Object
    public function FSoMQADGetDetailPageAll($ptSearchList, $pnQahCode, $pnLngID)
    {
        try {
            $tSQL = "SELECT COUNT (QAD.FNQadSeqNo) AS counts
                        FROM [TCNTQaDT] QAD
                    WHERE 1=1 AND QAD.FTQahDocNo  = '$pnQahCode' ";

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            } else {
                return false;
            }
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Get Max Seq QuestionDetail
    //Parameters : phw code, pos code , shw code
    //Creator : -
    //Update : 23/06/2021 pap
    //Return : max seq
    //Return Type : number
    public function FSaMQADLastSeqByShwCode($ptQahCode)
    {
        $tSQL = "SELECT TOP 1 FNQadSeqNo FROM TCNTQaDT
                WHERE FTQahDocNo = '" . $ptQahCode . "'
                ORDER BY FNQadSeqNo DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array()["FNQadSeqNo"];
        } else {
            return 0;
        }
    }


    //Functionality : Update Product QuestionDetail (TCNTQaDT)
    //Parameters : function parameters
    //Creator : 23/06/2021 Off
    //Update :
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMQAHAddUpdateQuestionDetail($paDataQuestionDetail)
    {
        try {
            // Update TCNTQaDT
            $this->db->where('FTQahDocNo', $paDataQuestionDetail['FTQahDocNo']);
            $this->db->where('FNQadSeqNo', $paDataQuestionDetail['FNQadSeqNo']);
            $this->db->update('TCNTQaDT', array(
                'FTQadStaUse'             => $paDataQuestionDetail['FTQadStaUse'],
                'FTQadType'             => $paDataQuestionDetail['FTQadType'],
                'FTQadName'               => $paDataQuestionDetail['FTQadName']
            ));
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update UserCalendar Success',
                );
            } else {
                //Add TCNTQaDT
                $this->db->insert('TCNTQaDT', array(
                    'FTQahDocNo'            => $paDataQuestionDetail['FTQahDocNo'],
                    'FNQadSeqNo'            => $paDataQuestionDetail['FNQadSeqNo'],
                    'FTQadStaUse'           => $paDataQuestionDetail['FTQadStaUse'],
                    'FTQadType'           => $paDataQuestionDetail['FTQadType'],
                    'FTQadName'             => $paDataQuestionDetail['FTQadName']
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add UserCalendar Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit UserCalendar.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function Clear Detail
    //Parameters : function parameters
    //Creator :  23/06/2021 Off
    //Last Modified :
    //Return : Status Add Update Detail
    //Return Type : Array
    public function FSnMQAHDelDT($paData){
        $this->db->where('FTQahDocNo', $paData['FTQahDocNo']);
        $this->db->where('FNQadSeqNo', $paData['FNQadSeqNo']);
        $this->db->delete('TCNTQaDTAns');
        return $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'success',
        );
    }

    //Functionality : Function Add/Update Detail
    //Parameters : function parameters
    //Creator :  23/06/2021 Off
    //Last Modified :
    //Return : Status Add Update Detail
    //Return Type : Array
    public function FSaMQAHAddUpdateDetail($paData)
    {
        try {
            // Add Detail
            $this->db->insert('TCNTQaDTAns', array(
                'FTQahDocNo'            => $paData['FTQahDocNo'],
                'FNQadSeqNo'            => $paData['FNQadSeqNo'],
                'FNQasResuitSeq'        => $paData['FNQasResuitSeq'],
                'FNQasResuitName'       => $paData['FNQasResuitName']
            ));

            // Set Response status
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Lang Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Lang.',
                );
            }

            // Response status
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Delete UserCalendar
    //Parameters : function parameters
    //Creator : 31/05/2021 Off
    //Update :
    //Return : Status Delete
    //Return Type : array
    public function FSaMQASDelQuestionDetailAll($paData)
    {

        $this->db->where('FTQahDocNo', $paData['FTQahDocNo']);
        $this->db->where_in('FNQadSeqNo', $paData['FNQadSeqNo']);
        $this->db->delete('TCNTQaDTAns');

        $this->db->where('FTQahDocNo', $paData['FTQahDocNo']);
        $this->db->where_in('FNQadSeqNo', $paData['FNQadSeqNo']);
        $this->db->delete('TCNTQaDT');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Success',
            );
        } else {
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //Functionality : get all row data from QuestionDetail
    //Parameters : -
    //Creator : 23/06/2021 Off
    //Return : array result from db
    //Return Type : array
    public function FSnMQASGetAllQuestionDetailNumRow($ptObjCode)
    {
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNTQaDT WITH (NOLOCK) WHERE FTQahDocNo = '" . $ptObjCode . "'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        } else {
            $aResult = false;
        }
        return $aResult;
    }

    //Functionality : Get Data QuestionDetail By ID
    //Parameters : function parameters
    //Creator : 23/06/2021 Off
    //Return : data
    //Return Type : Array
    public function FSaQADGetDataByID($paData)
    {
        try {


            $tQahCode   = $paData['tQahCode'];
            $tSeqCode   = $paData['tSeqCode'];
            $tSQL       = " SELECT DISTINCT
                                QAD.FTQahDocNo      AS rtQahDocNo,
                                QAD.FTQadName       AS rtQadName,
                                QAD.FTQadStaUse     AS rtQadStaUse,
                                QAD.FTQadType       AS rtQadType,
                                QAD.FNQadSeqNo      AS rtQadSeqNo
                                FROM [TCNTQaDT] QAD WITH (NOLOCK)
                                WHERE 1=1 AND QAD.FTQahDocNo  = '$tQahCode' AND QAD.FNQadSeqNo= '$tSeqCode' ";
            $oQuery = $this->db->query($tSQL);

            // Detail
            $tDTSQL =   "SELECT
                QAS.FNQasResuitSeq     AS rtQasResuitSeq,
                QAS.FNQasResuitName    AS rtQasResuitName
            FROM [TCNTQaDTAns] QAS WITH(NOLOCK)
            WHERE 1=1
            AND QAS.FTQahDocNo  = '$tQahCode' AND QAS.FNQadSeqNo= '$tSeqCode' ORDER BY QAS.FNQasResuitSeq";
            $oDTQuery = $this->db->query($tDTSQL);



            if ($oQuery->num_rows() > 0) {
                $aDetail = $oQuery->row_array();
                $aDTDetail  = $oDTQuery->result();

                $aDetailQuestion = [];
                foreach ($aDTDetail as $nIndex => $oItem) {
                    $aDetailQuestion[$nIndex]['Value']   = $oItem->rtQasResuitName;
                    $aDetailQuestion[$nIndex]['Seq']     = $oItem->rtQasResuitSeq;
                }
                $aResult = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'raDetailQuestion'   => $aDetailQuestion,
                    'rtDesc'    => 'success',
                );
            } else {
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
            }
            return $aResult;
        } catch (Exception $Error) {
            echo $Error;
        }
    }

}
