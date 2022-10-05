<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Bluecradmonitor_model extends CI_Model {

   

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvMBCMCallDataTable($paData){
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);

        $aParameter = $paData['aParameter'];
        $tWhereCondition = '';
        if($aParameter['tBCMBchCode']!=''){
            $tBCMBchCode = $aParameter['tBCMBchCode'];
            $tWhereCondition .= " AND LMS.FTBchCode = '$tBCMBchCode' ";   
        }

        if($aParameter['tBCMPosCode']!=''){
            $tBCMPosCode = $aParameter['tBCMPosCode'];
            $tWhereCondition .= " AND LMS.FTPosCode = '$tBCMPosCode' ";   
        }

        if($aParameter['tBCMBatStaClosed']!=''){
            $tBCMBatStaClosed = $aParameter['tBCMBatStaClosed'];
            $tWhereCondition .= " AND BAT.FTBatStaClosed = '$tBCMBatStaClosed' ";   
        }

        if($aParameter['tBCMBatStaVerify']!=''){
            $tBCMBatStaVerify = $aParameter['tBCMBatStaVerify'];
            if($tBCMBatStaVerify=='3'){
                $tWhereCondition .= " AND ISNULL(BAT.FTBatStaVerify,'') = '' ";   
            }else{
                $tWhereCondition .= " AND BAT.FTBatStaVerify = '$tBCMBatStaVerify' ";   
            }
        }

        if($aParameter['tBCMBatStaInsBat']!=''){
            $tBCMBatStaInsBat = $aParameter['tBCMBatStaInsBat'];
            if($tBCMBatStaInsBat=='2'){
                $tWhereCondition .= " AND ISNULL(BAT.FTBatStaInsBat,'') = '' ";   
            }else{
                $tWhereCondition .= " AND BAT.FTBatStaInsBat = '$tBCMBatStaInsBat' ";   
            }
        }

        if($aParameter['tBCMSALDate']!=''){
            $tBCMSALDate = $aParameter['tBCMSALDate'];
            $tWhereCondition .= " AND CONVERT(VARCHAR(10),BAT.FDShfSaleDate,121) = '$tBCMSALDate' ";   
        }

        $tSQLSub="SELECT
                    ROW_NUMBER() OVER(ORDER BY BAT.FDShfSaleDate ASC) AS rtRowID,
                    BCHL.FTBchCode,
                    BCHL.FTBchName,
                    LMS.FTPosCode AS FTPosRefTID,
                    BAT.FTShfCode,
                    BAT.FTBatID,
                    BAT.FTBatStandFrm,
                    BAT.FTBatStandTo,
                    BAT.FCBatSumAmt,
                    BAT.FTBatStaClosed,
                    BAT.FTBatStaVerify,
                    BAT.FTBatStaInsBat
                    FROM TLKTShiftLMS BAT
                    LEFT JOIN TLKMLMSShop LMS WITH(NOLOCK) ON BAT.FTBchRefMID = LMS.FTLmsMID AND BAT.FTPosRefTID = LMS.FTLmsTID
                    LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON LMS.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLangEdit
                    WHERE 1=1 $tWhereCondition ";

        $tSQL = " SELECT c.*
                    FROM
                        (
                         $tSQLSub
                        ) AS c  WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1] ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $oQuerySub  = $this->db->query($tSQLSub);
            $nFoundRow  = $oQuerySub->num_rows();
            $this->session->set_userdata("tSesSqlForExport", $tSQLSub);
            $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aDataReturn = array(
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn = array(
                'raItems'       => array(),
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aDataReturn;
    }

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    function FSvMBCMCallDataBatchbyID($ptBatID){
        $nLangEdit = $this->session->userdata("tLangEdit");
        $tSQL="SELECT
                    BCHL.FTBchName,
                    LMS.FTPosCode AS FTPosRefTID,
                    BAT.FTShfCode,
                    BAT.FTBatID,
                    BAT.FTBatStandFrm,
                    BAT.FTBatStandTo,
                    BAT.FCBatSumAmt,
                    BAT.FTBatStaClosed,
                    BAT.FTBatStaVerify,
                    BAT.FTBatStaInsBat
                    FROM TLKTShiftLMS BAT
                    LEFT JOIN TLKMLMSShop LMS WITH(NOLOCK) ON BAT.FTBchRefMID = LMS.FTLmsMID AND BAT.FTPosRefTID = LMS.FTLmsTID
                    LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON LMS.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLangEdit
                    WHERE 1=1
                    AND  BAT.FTBatID = '$ptBatID' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataReturn = array(
                'raItems'       => $oQuery->row_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn = array(
                'raItems'       => array(),
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        return $aDataReturn;
    }


    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    function FSvMBCMCallStandDataTable($paData){

        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $aParameter  = $paData['aParameter'];
        $tBatID = $aParameter['tBatID'];
        $tWhereCondition = '';
        if($aParameter['tBCMBatTabStdType']!=''){
            $tBCMBatTabStdType = $aParameter['tBCMBatTabStdType'];
            $tWhereCondition .= " AND TXN.FTTxnType = '$tBCMBatTabStdType' ";   
        }

        $tSQLSub="SELECT
                    ROW_NUMBER() OVER(ORDER BY TXN.FTTxnStandID * 1 ASC) AS rtRowID,
                    TXN.FTXshDocNo,
                    TXN.FTTxnStandID,
                    TXN.FTTxnCrdCode,
                    TXN.FTTxnRefTranID,
                    TXN.FTTxnType,
                    TXN.FTTxnStaOnline,
                    TXN.FTTxnStaUpload,
                    TXN.FTTxnRmk,
                    TXN.FDCreateOn,
                    TXN.FCTxnPntB4Bill,
                    TXN.FCTxnPntBillQty,
                    TXN.FCTxnTotalPntToday
                    FROM
                        TLKTLmsTxnHD TXN
                    WHERE TXN.FTBatID = '$tBatID'
                    $tWhereCondition
                    ";

        $tSQL = " SELECT c.*
                    FROM
                        (
                         $tSQLSub
                        ) AS c  WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1] ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $oQuerySub  = $this->db->query($tSQLSub);
            $nFoundRow  = $oQuerySub->num_rows();
            $this->session->set_userdata("tSesSqlForExport", $tSQLSub);
            $nPageAll   = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aDataReturn = array(
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn = array(
                'raItems'       => array(),
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        return $aDataReturn;
    }


}