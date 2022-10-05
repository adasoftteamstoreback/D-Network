<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productpick_model extends CI_Model
{

    /**
     * Functionality : HD List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : HD List
     * Return Type : Array
     */
    public function FSaMHDList($paParams = [])
    {



        $aRowLen    = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        $nLngID     = $paParams['FNLngID'];

        $tSQL   =   "   SELECT 			
        A.* 
    FROM ( ";

        $tSQL       .= "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        HD.*,
                        AGNL.FTAgnName,
                        BCHL.FTBchName,
                        USRL.FTUsrName AS FTCreateByName,
                        USRLAPV.FTUsrName AS FTXthApvName,
                        COUNT(HDDocRef.FTXthDocNo) OVER(PARTITION BY HD.FTXthDocNo) AS PARTITIONBYDOC, 
                        HDDocRef.FTXthRefDocNo AS 'DOCREF', 
                        CONVERT(VARCHAR, HDDocRef.FDXthRefDocDate, 103) AS 'DATEREF'
                    FROM TCNTPdtPickHD HD WITH (NOLOCK)
                    LEFT JOIN TCNMAgency_L AGNL WITH (NOLOCK) ON AGNL.FTAgnCode = HD.FTAgnCode AND AGNL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON HD.FTXthApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNTPdtPickHDDocRef HDDocRef WITH(NOLOCK) ON HD.FTXthDocNo = HDDocRef.FTXthDocNo AND HDDocRef.FTXthRefType = 1
                    WHERE HD.FDCreateOn <> '' AND HD.FNXthDocType = '12'
        ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= "
                AND HD.FTBchCode IN ($tBchCode)
            ";
        }

        $aAdvanceSearch = $paParams['aAdvanceSearch'];
        $tSearchList = $aAdvanceSearch['tSearchAll'];
        if ($tSearchList != '') {
            $tSearchLists = $this->db->escape_like_str($tSearchList);
            $tSQL   .= "
                AND (
                    (HD.FTXthDocNo COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (BCHL.FTBchName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (USRL.FTUsrName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (USRLAPV.FTUsrName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (HDDocRef.FTXthRefDocNo COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                )
            ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= "
                AND (
                    (HD.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeFrom) . "  AND " . $this->db->escape($tSearchBchCodeTo) . ")
                    OR (HD.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeTo) . " AND " . $this->db->escape($tSearchBchCodeFrom) . ")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= "
                AND ((HD.FDXthDocDate BETWEEN CONVERT(datetime," . $this->db->escape($tSearchDocDateFrom . " 00:00:00") . ") AND CONVERT(datetime," . $this->db->escape($tSearchDocDateTo . " 23:59:59") . "))
                OR (HD.FDXthDocDate BETWEEN CONVERT(datetime," . $this->db->escape($tSearchDocDateTo . " 00:00:00") . ") AND CONVERT(datetime," . $this->db->escape($tSearchDocDateFrom . " 23:59:59") . ")))
            ";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND HD.FTXthStaDoc = " . $this->db->escape($tSearchStaDoc) . " ";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(HD.FTXthStaApv,'') = '' AND HD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND HD.FTXthStaApv = " . $this->db->escape($tSearchStaDoc) . " ";
            }
        }

        // ค้นหาสถานะประมวลผล
        // $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        // if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
        //     if ($tSearchStaPrcStk == 3) {
        //         $tSQL   .= " AND (HD.FTXthStaPrcStk = " . $this->db->escape($tSearchStaPrcStk) . " OR ISNULL(HD.FTXthStaPrcStk,'') = '') ";
        //     } else {
        //         $tSQL   .= " AND HD.FTXthStaPrcStk = " . $this->db->escape($tSearchStaPrcStk) . " ";
        //     }
        // }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL   .= " AND HD.FNXthStaDocAct = 1";
            } else {
                $tSQL   .= " AND HD.FNXthStaDocAct = 0";
            }
        }

        // $tSQL .= ") Base) AS c WHERE c.FNRowID > " . $this->db->escape($aRowLen[0]) . " AND c.FNRowID <= " . $this->db->escape($aRowLen[1]) . " ";

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > " . $this->db->escape($aRowLen[0]) . " AND c.FNRowID <= " . $this->db->escape($aRowLen[1]) . "";
        $tSQL .= " ) AS A ";
        $tSQL .= " ORDER BY A.FNRowID ASC ";



        // print_r($tSQL);
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aDataCountAllRow = $this->FSnMHDListGetPageAll($paParams);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1') ? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow / $paParams['nRow']);
            // $nFoundRow  = $this->FSnMHDListGetPageAll($paParams);
            // $nPageAll   = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // No Data
            $aResult    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paParams['nPage'],
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
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Count HD Row
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count Row
     * Return Type : Number
     */
    public function FSnMHDListGetPageAll($paParams = [])
    {
        $nLngID     = $paParams['FNLngID'];
        $tSQL = "
        SELECT COUNT (HD.FTXthDocNo) AS counts
                
            FROM TCNTPdtPickHD HD WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON HD.FTXthApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
            WHERE HD.FDCreateOn <> '' AND HD.FNXthDocType = '12'
        ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND HD.FTBchCode IN ($tBchCode)
            ";
        }

        $aAdvanceSearch = $paParams['aAdvanceSearch'];
        $tSearchList = $aAdvanceSearch['tSearchAll'];
        if ($tSearchList != '') {
            $tSearchLists = $this->db->escape_like_str($tSearchList);
            $tSQL   .= "
                AND (
                    (HD.FTXthDocNo COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (BCHL.FTBchName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (USRL.FTUsrName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                    OR (USRLAPV.FTUsrName COLLATE THAI_BIN LIKE '%" . $tSearchLists . "%')
                )
            ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= "
                AND (
                    (HD.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeFrom) . " AND " . $this->db->escape($tSearchBchCodeTo) . ")
                    OR (HD.FTBchCode BETWEEN " . $this->db->escape($tSearchBchCodeTo) . " AND " . $this->db->escape($tSearchBchCodeFrom) . ")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= "
                AND (
                    (HD.FDXthDocDate BETWEEN CONVERT(datetime," . $this->db->escape($tSearchDocDateFrom . " 00:00:00") . ") AND CONVERT(datetime," . $this->db->escape($tSearchDocDateTo . " 23:59:59") . ")
                ) OR
                    (HD.FDXthDocDate BETWEEN CONVERT(datetime," . $this->db->escape($tSearchDocDateTo . " 00:00:00") . ") AND CONVERT(datetime," . $this->db->escape($tSearchDocDateFrom . " 23:59:59") . "))
                )
            ";
        }

        // // สถานะเอกสาร
        // $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        // if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
        //     if ($tSearchStaDoc == 2) {
        //         $tSQL   .= " AND HD.FTXthStaDoc = " . $this->db->escape($tSearchStaDoc) . " OR HD.FTXthStaDoc = ''";
        //     } else {
        //         $tSQL   .= " AND HD.FTXthStaDoc = " . $this->db->escape($tSearchStaDoc) . " ";
        //     }
        // }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND HD.FTXthStaDoc = " . $this->db->escape($tSearchStaDoc) . " ";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(HD.FTXthStaApv,'') = '' AND HD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND HD.FTXthStaApv = " . $this->db->escape($tSearchStaDoc) . " ";
            }
        }

        // ค้นหาสถานะประมวลผล
        // $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        // if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
        //     if ($tSearchStaPrcStk == 3) {
        //         $tSQL   .= " AND (HD.FTXthStaPrcStk = " . $this->db->escape($tSearchStaPrcStk) . " OR ISNULL(HD.FTXthStaPrcStk,'') = '') ";
        //     } else {
        //         $tSQL   .= " AND HD.FTXthStaPrcStk = " . $this->db->escape($tSearchStaPrcStk) . " ";
        //     }
        // }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXthStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXthStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);
        // return $oQuery->num_rows();
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

    // ข้อมูลของริษัท
    public function FStTFWGetShpCodeForUsrLogin($paParams = [])
    {
        $nLngID     = $paParams['FNLngID'];
        $tUsrLogin  = $paParams['tUsrLogin'];

        $tSQL = "
            SELECT UGP.FTBchCode,
                BCHL.FTBchName,
                MCHL.FTMerCode,
                MCHL.FTMerName,
                UGP.FTShpCode,
                SHPL.FTShpName,
                SHP.FTShpType,
                SHP.FTWahCode AS FTWahCode,
                WAHL.FTWahName AS FTWahName
                /* BCH.FTWahCode AS FTWahCode_Bch,
                BWAHL.FTWahName AS FTWahName_Bch  */

            FROM TCNTUsrGroup UGP WITH (NOLOCK)
            LEFT JOIN TCNMBranch  BCH WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode
            LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode
            /* LEFT JOIN TCNMWaHouse_L BWAHL ON BCH.FTWahCode = BWAHL.FTWahCode */
            LEFT JOIN TCNMShop      SHP WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMMerchant_L  MCHL WITH (NOLOCK) ON SHP.FTMerCode = MCHL.FTMerCode AND  MCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop_L    SHPL WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHL WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
            WHERE FTUsrCode = " . $this->db->escape($tUsrLogin) . "
        ";

        $aResult = [];

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array();
        }

        return $aResult;
    }

    /**
     * Functionality : Get HD Detail
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : HD Detail
     * Return Type : Array
     */
    public function FSaMGetHD($paParams = []){
        $tDocNo = $paParams['tDocNo'];
        $nLngID = $paParams['nLngID'];
        $tSQL   = "
            SELECT
                ISNULL(STACANCEL.FTXthRefType,'1') AS FTXthRefType,
                HD.*, 
                AGNL.FTAgnName,
                BCHL.FTBchName, 
                CONVERT(CHAR(5), HD.FDXthDocDate, 108) AS FTXthDocTime, 
                USRAPV.FTUsrName AS FTXthApvName, 
                USRL.FTUsrName AS FTCreateByName, 
                HDREFDOC.FTXthRefDocNo AS FTXthRefInt, 
                HDREFDOCEX.FTXthRefDocNo AS FTXthRefExt, 
                HDREFDOC.FDXthRefDocDate AS FDXthRefIntDate, 
                HDREFDOCEX.FDXthRefDocDate AS FDXthRefExtDate, 
                CONVERT(CHAR(5), HDREFDOC.FDXthRefDocDate, 108) AS FDXthRefIntTime,
                BCHLF.FTBchName AS FTXthBchFrmName, 
                SHPLF.FTShpName AS FTXthShopFrmName, 
                WAHLF.FTWahName AS FTXthWhFrmName,
                BCHLT.FTBchName AS FTXthBchToName, 
                WAHLT.FTWahName AS FTXthWhToName,
                HD.FTUsrCode,
                USRPCK.FTUsrName  AS FTUsrName,
                RSNL.FTRsnName
            FROM TCNTPdtPickHD HD WITH (NOLOCK)
            LEFT JOIN TCNMAgency_L AGNL WITH (NOLOCK) ON AGNL.FTAgnCode = HD.FTAgnCode AND AGNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode  AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRL WITH(NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRAPV WITH(NOLOCK) ON USRAPV.FTUsrCode = HD.FTXthApvCode AND USRAPV.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRPCK WITH(NOLOCK) ON USRPCK.FTUsrCode = HD.FTUsrCode AND USRPCK.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtPickHDDocRef HDREFDOC WITH(NOLOCK) ON HDREFDOC.FTXthDocNo = HD.FTXthDocNo AND HDREFDOC.FTXthRefType = '1'
            LEFT JOIN TCNTPdtPickHDDocRef HDREFDOCEX WITH(NOLOCK) ON HDREFDOCEX.FTXthDocNo = HD.FTXthDocNo AND HDREFDOCEX.FTXthRefType = '3'
            LEFT JOIN TCNMBranch_L BCHLF WITH(NOLOCK) ON BCHLF.FTBchCode = HD.FTBchCode AND BCHLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop_L SHPLF WITH(NOLOCK) ON SHPLF.FTShpCode = HD.FTShpCode AND SHPLF.FTBchCode = HD.FTBchCode AND SHPLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHLF WITH(NOLOCK) ON WAHLF.FTWahCode = HD.FTWahCode AND WAHLF.FTBchCode = HD.FTBchCode AND WAHLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMBranch_L BCHLT WITH(NOLOCK) ON BCHLT.FTBchCode = HD.FTBchCode AND BCHLT.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHLT WITH(NOLOCK) ON WAHLT.FTWahCode = HD.FTWahCode AND WAHLT.FTBchCode = HD.FTBchCode AND WAHLT.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMRsn_L  RSNL WITH(NOLOCK)     ON HD.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN (
                SELECT TOP 1
                    DOCREF2.FTXthDocNo,
                    DOCREF2.FTXthRefType
                FROM TCNTPdtPickHDDocRef DOCREF2 WITH(NOLOCK)
                WHERE DOCREF2.FTXthRefType = '2'
                AND DOCREF2.FTXthDocNo  = ".$this->db->escape($tDocNo)."
            ) STACANCEL ON HD.FTXthDocNo = STACANCEL.FTXthDocNo
            WHERE HD.FDCreateOn <> ''
        ";
        if ($tDocNo != "") {
            $tSQL .= " AND HD.FTXthDocNo = '$tDocNo'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->row_array();
            $aResult = array(
                'raItems' => $oDetail,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            // Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Insert DT to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPCKDTToTemp($paParams = [])
    {
        $tDocNo = $paParams['tDocNo']; // เลขที่เอกสาร
        $tDocKey = $paParams['tDocKey']; // ชื่อตาราง HD
        $tBchCode = $paParams['tBchCode']; // สาขาที่ทำรายการ
        // $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID']; // User Session
        $nLngID = $paParams['nLngID'];

        // ทำการลบ ใน DT Temp ก่อนการย้าย DT ไป DT Temp
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTSessionID', $tUserSessionID);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL = "
            INSERT TCNTDocDTTmp
                (FTBchCode,
                FTXthDocNo,
                FNXtdSeqNo,
                FTPdtCode,
                FTXtdPdtName,
                FTPunCode,
                FTPunName,
                FCXtdFactor,
                FTXtdBarCode,
                FTXtdVatType,
                FTVatCode,
                FCXtdVatRate,
                FCXtdQty,
                FCXtdQtyAll,
                FCXtdSetPrice,
                FTXtdStaPrcStk,
                FTXtdRmk,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy,
                FTXthDocKey,
                FTSessionID,
                FCXtdQtyOrd,
                FTAgnCode)
        ";

        $tSQL .= "
            SELECT
                DT.FTBchCode,
                '$tDocNo' AS FTXthDocNo,
                DT.FNXtdSeqNo,
                DT.FTPdtCode,
                DT.FTXtdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXtdFactor,
                DT.FTXtdBarCode,
                DT.FTXtdVatType,
                DT.FTVatCode,
                DT.FCXtdVatRate,
                DT.FCXtdQty,
                DT.FCXtdQtyAll,
                DT.FCXtdSetPrice,
                DT.FTXtdStaPrcStk,
                DT.FTXtdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy,
                '$tDocKey' AS FTXthDocKey,
                '$tUserSessionID' AS FTSessionID,
                DT.FCXtdQtyOrd,
                DT.FTAgnCode
            FROM TCNTPdtPickDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode  = " . $this->db->escape($tBchCode) . "
            AND DT.FTXthDocNo   = " . $this->db->escape($tDocNo) . "
            ORDER BY DT.FNXtdSeqNo ASC
        ";



        $this->db->query($tSQL);
    }

    /**
     * Functionality : Insert Temp to DT
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMTempToDT($paParams = [])
    {
        $tDocNo = $paParams['tDocNo']; // เลขที่เอกสาร
        $tDocKey = $paParams['tDocKey']; // ชื่อตาราง HD
        $tBchCode = $paParams['tBchCode']; // สาขา
        // $tShpCode = $paParams['tShpCode']; // ร้านค้า
        // $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID']; // User Session
        $tUserLoginCode = $paParams['tUserLoginCode']; // User Login Code
        // $nLngID = $paParams['nLngID'];

        $tAgnCode =  $paParams['tAgnCode'];

        // ทำการลบ ใน DT Temp ก่อนการย้าย DT ไป DT Temp
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtPickDT');

        $tSQL = "
            INSERT TCNTPdtPickDT
                (FTAgnCode,
                    FTBchCode,
                FTXthDocNo,
                FNXtdSeqNo,
                FTPdtCode,
                FTXtdPdtName,
                FTPunCode,
                FTPunName,
                FCXtdFactor,
                FTXtdBarCode,
                FTXtdVatType,
                FTVatCode,
                FCXtdVatRate,
                FCXtdQty,
                FCXtdQtyAll,
                FCXtdSetPrice,
                FTXtdStaPrcStk,
                FTXtdRmk,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy,
                FCXtdQtyOrd)
        ";

        $tSQL .= "
            SELECT
                ISNULL(TMP.FTAgnCode,''),
                TMP.FTBchCode,
                TMP.FTXthDocNo,
                ROW_NUMBER() OVER(ORDER BY TMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                TMP.FTPdtCode,
                TMP.FTXtdPdtName,
                TMP.FTPunCode,
                TMP.FTPunName,
                TMP.FCXtdFactor,
                TMP.FTXtdBarCode,
                TMP.FTXtdVatType,
                TMP.FTVatCode,
                TMP.FCXtdVatRate,
                TMP.FCXtdQty,
                TMP.FCXtdQtyAll,
                TMP.FCXtdSetPrice,
                TMP.FTXtdStaPrcStk,
                TMP.FTXtdRmk,
                GETDATE() AS FDLastUpdOn,
                '$tUserLoginCode' AS FTLastUpdBy,
                GETDATE() AS FDCreateOn,
                '$tUserLoginCode' AS FTCreateBy,
                TMP.FCXtdQtyOrd
            FROM TCNTDocDTTmp TMP WITH(NOLOCK)
            WHERE TMP.FTBchCode = '$tBchCode'
            AND TMP.FTXthDocKey = '$tDocKey'
            AND TMP.FTSessionID = '$tUserSessionID'
            ORDER BY TMP.FNXtdSeqNo ASC
        ";
        $this->db->query($tSQL);

        // ทำการลบ ใน DT Temp หลังการย้าย DT Temp ไป DT
        $this->db->where('FTSessionID', $tUserSessionID);
        $this->db->delete('TCNTDocDTTmp');
    }

    /**
     * Functionality : ล้างข้อมูลในตาราง tmp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMClearInTmp($aParams = [])
    {
        $tUserSessionID = $aParams['tUserSessionID'];
        $tDocKey = $aParams['tDocKey'];

        $tSQL = "
            DELETE FROM TCNTDocDTTmp
            WHERE FTSessionID = '$tUserSessionID'
            AND FTXthDocKey = '$tDocKey'
        ";

        $this->db->query($tSQL);


        $tSQLRef = "
        DELETE FROM TCNTDocHDRefTmp
        WHERE FTSessionID = '$tUserSessionID'
        AND FTXthDocKey = '$tDocKey'
    ";

        $this->db->query($tSQLRef);
    }

    /**
     * Functionality : Check DocNo is Duplicate
     * Parameters : DocNo
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Boolean
     */
    public function FSbMCheckDuplicate($ptDocNo = '')
    {
        $tSQL = "
            SELECT
                FTXthDocNo
            FROM TCNTPdtPickHD
            WHERE FTXthDocNo = '$ptDocNo'
        ";

        $bStatus = false;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Add or Update HD
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMAddUpdateHD($paParams = [])
    {
        // Update Master
        $this->db->set('FTAgnCode',  $paParams['FTAgnCode']);
        $this->db->set('FTBchCode', $paParams['FTBchCode']);
        $this->db->set('FDXthDocDate', $paParams['FDXthDocDate']);
        $this->db->set('FTDptCode', $paParams['FTDptCode']);
        $this->db->set('FTUsrCode', $paParams['FTUsrCode']);
        $this->db->set('FTXthApvCode', $paParams['FTXthApvCode']);
        $this->db->set('FNXthDocPrint', $paParams['FNXthDocPrint']);
        $this->db->set('FTXthRmk', $paParams['FTXthRmk']);
        $this->db->set('FTXthStaDoc', $paParams['FTXthStaDoc']);
        $this->db->set('FTXthStaApv', $paParams['FTXthStaApv']);
        $this->db->set('FTXthStaDelMQ', $paParams['FTXthStaDelMQ']);
        $this->db->set('FNXthStaDocAct', $paParams['FNXthStaDocAct']);
        $this->db->set('FNXthStaRef', $paParams['FNXthStaRef']);
        $this->db->set('FDLastUpdOn', $paParams['FDLastUpdOn']);
        $this->db->set('FTLastUpdBy', $paParams['FTLastUpdBy']);
        $this->db->set('FNXthDocType', $paParams['FNXthDocType']);
        $this->db->set('FTRsnCode', $paParams['FTRsnCode']);
        $this->db->where('FTXthDocNo', $paParams['FTXthDocNo']);
        $this->db->update('TCNTPdtPickHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Master Success',
            );
        } else {
            // Add Master
            $this->db->set('FTAgnCode',  $paParams['FTAgnCode']);
            $this->db->set('FTBchCode', $paParams['FTBchCode']);
            $this->db->set('FTXthDocNo', $paParams['FTXthDocNo']);
            $this->db->set('FDXthDocDate', $paParams['FDXthDocDate']);
            $this->db->set('FTDptCode', $paParams['FTDptCode']);
            $this->db->set('FTUsrCode', $paParams['FTUsrCode']);
            $this->db->set('FTXthApvCode', $paParams['FTXthApvCode']);
            $this->db->set('FNXthDocPrint', $paParams['FNXthDocPrint']);
            $this->db->set('FTXthRmk', $paParams['FTXthRmk']);
            $this->db->set('FTXthStaDoc', $paParams['FTXthStaDoc']);
            $this->db->set('FTXthStaApv', $paParams['FTXthStaApv']);
            $this->db->set('FTXthStaDelMQ', $paParams['FTXthStaDelMQ']);
            $this->db->set('FNXthStaDocAct', $paParams['FNXthStaDocAct']);
            $this->db->set('FNXthStaRef', $paParams['FNXthStaRef']);
            $this->db->set('FDLastUpdOn', $paParams['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paParams['FTLastUpdBy']);
            $this->db->set('FDCreateOn', $paParams['FDCreateOn']);
            $this->db->set('FTCreateBy', $paParams['FTCreateBy']);
            $this->db->set('FNXthDocType', $paParams['FNXthDocType']);
            $this->db->set('FTRsnCode', $paParams['FTRsnCode']);
            $this->db->insert('TCNTPdtPickHD');
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
    }

    /**
     * Functionality : Add or Update HDRef
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMAddUpdateHDRef($paParams = [])
    {
        // Update TCNTPdtTboHDRef
        $this->db->set('FTBchCode', $paParams['FTBchCode']);
        $this->db->set('FTXthCtrName', $paParams['FTXthCtrName']);
        $this->db->set('FDXthTnfDate', $paParams['FDXthTnfDate']);
        $this->db->set('FTXthRefTnfID', $paParams['FTXthRefTnfID']);
        $this->db->set('FTXthRefVehID', $paParams['FTXthRefVehID']);
        $this->db->set('FTXthQtyAndTypeUnit', $paParams['FTXthQtyAndTypeUnit']);
        $this->db->set('FNXthShipAdd', $paParams['FNXthShipAdd']);
        $this->db->set('FTViaCode', $paParams['FTViaCode']);
        $this->db->where('FTXthDocNo', $paParams['FTXthDocNo']);
        $this->db->update('TCNTPdtTboHDRef');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update AddUpdateHDRef Success',
            );
        } else {
            // Add TCNTPdtTboHDRef
            $this->db->set('FTBchCode', $paParams['FTBchCode']);
            $this->db->set('FTXthDocNo', $paParams['FTXthDocNo']);
            $this->db->set('FTXthCtrName', $paParams['FTXthCtrName']);
            $this->db->set('FDXthTnfDate', $paParams['FDXthTnfDate']);
            $this->db->set('FTXthRefTnfID', $paParams['FTXthRefTnfID']);
            $this->db->set('FTXthRefVehID', $paParams['FTXthRefVehID']);
            $this->db->set('FTXthQtyAndTypeUnit', $paParams['FTXthQtyAndTypeUnit']);
            $this->db->set('FNXthShipAdd', $paParams['FNXthShipAdd']);
            $this->db->set('FTViaCode', $paParams['FTViaCode']);
            $this->db->insert('TCNTPdtTboHDRef');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add AddUpdateHDRef Success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit AddUpdateHDRef.',
                );
            }
        }
        return $aStatus;
    }

    /**
     * Functionality : Update DocNo in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMUpdateDocNoInTmp($paParams = [])
    {
        $this->db->set('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->update('TCNTDocDTTmp');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update DocNo Success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Update DocNo Fail',
            );
        }
        return $aStatus;
    }

    /**
     * Functionality : Cancel Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPCKDocCancel($paParams)
    {
        $this->db->trans_begin();

        // Update Status Doc In HD
        $this->db->set('FTXthStaDoc', '3');
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->update('TCNTPdtPickHD');

        // BS Ref
        // $this->db->where_in('FTXthDocNo', $paParams['tDocNo']);
        // $this->db->delete('TCNTPdtPickHDDocRef');

        // TR Ref
        // $this->db->where_in('FTXshRefDocNo', $paParams['tDocNo']);
        // $this->db->delete('TCNTPdtReqBchHDDocRef');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Cancel Fail',
            );
        } else {
            $this->db->trans_commit();
            $aStatus    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Cancel Success',
            );
        }
        return $aStatus;
    }

    /**
     * Functionality : Approve Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Array
     */
    public function FSaMPCKDocApprove($paParams = [])
    {

        $tDocNo = $paParams['tDocNo'];
        $this->db->set('FTXthStaApv', '1');
        $this->db->set('FTXthApvCode', $paParams['tApvCode']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->update('TCNTPdtPickHD');


        $tSQL2 = "SELECT FTXthRefDocNo FROM TCNTPdtPickHDDocRef WITH (NOLOCK) WHERE FTXthDocNo = '$tDocNo'  ";
        $oQuery2 = $this->db->query($tSQL2);
        if ($oQuery2->num_rows() > 0) {
            $oList2      = $oQuery2->result_array();
            $aResult2    = array(
                'rtDocRef'       => $oList2[0]['FTXthRefDocNo'],
            );
        } else {
            // No Data
            $aResult2    = array(
                'rtDocRef'       => 'No Data',
            );
        }
        $tDocNoRef = $aResult2['rtDocRef'];


        $tSQL4 = "SELECT STRING_AGG(FTXthDocNo, ''',''') AS  FTXthDocNo FROM  TCNTPdtPickHDDocRef WHERE FTXthRefDocNo = '$tDocNoRef' ";
        $oQuery4 = $this->db->query($tSQL4);
        if ($oQuery4->num_rows() > 0) {
            $oList4      = $oQuery4->result_array();
            $aResult4    = array(
                'rtDocRef'       => $oList4[0]['FTXthDocNo'],
            );
        } else {
            // No Data
            $aResult4    = array(
                'rtDocRef'       => 'No Data',
            );
        }


        $tDocRefWherein =   $aResult4['rtDocRef'];
        //     $tSQL1 = "SELECT
        //     DT.FTXthDocNo,
        //     SUM(FCXtdQtyOrd) AS SumA
        // FROM TCNTPdtPickDT DT WITH (NOLOCK)
        // LEFT JOIN TCNTPdtPickHD HD  WITH (NOLOCK) ON HD.FTXthDocNo = DT.FTXthDocNo 
        // WHERE DT.FTXthDocNo = '$tDocNo'
        // GROUP BY DT.FTXthDocNo ";
        $tSQL1 = "SELECT 
    SUM(D.SumA) AS SumU
    FROM (
       SELECT
                        DT.FTXthDocNo,
                        SUM(FCXtdQtyOrd) AS SumA
                    FROM TCNTPdtPickDT DT WITH (NOLOCK)
                    LEFT JOIN TCNTPdtPickHD HD  WITH (NOLOCK) ON HD.FTXthDocNo = DT.FTXthDocNo 
                    WHERE  DT.FTXthDocNo IN ('$tDocRefWherein') AND HD.FTXthStaApv = 1
                    GROUP BY DT.FTXthDocNo) D";
        $oQuery1 = $this->db->query($tSQL1);
        if ($oQuery1->num_rows() > 0) {
            $oList1      = $oQuery1->result_array();
            $aResult1    = array(
                'rtSum'       => $oList1[0]['SumU'],
            );
        } else {
            // No Data
            $aResult1    = array(
                'rtSum'       => 0
            );
        }
        //     $tSQL3 = "SELECT
        //     FTXshDocNo,
        //     SUM(FCXsdQtyAll) AS SumA
        // FROM TSVTJob2OrdDT WITH (NOLOCK)
        // WHERE FTXshDocNo = '$tDocNoRef'
        // GROUP BY FTXshDocNo ";
        // SELECT 

        $tSQL3 = " SELECT  SUM(D.SumA) AS SumU
FROM		(
SELECT
    FTXshDocNo,
    SUM(FCXsdQtyAll) AS SumA
FROM TSVTJob2OrdDT WITH (NOLOCK)
WHERE FTXshDocNo = '$tDocNoRef' AND FTPdtStaSet != 5
GROUP BY FTXshDocNo
UNION ALL
  SELECT
    FTXshDocNo,
    SUM(FCXsdQtySet) AS SumA
FROM TSVTJob2OrdDTSet WITH (NOLOCK)
WHERE FTXshDocNo = '$tDocNoRef'
GROUP BY FTXshDocNo) AS D ";

        $oQuery3 = $this->db->query($tSQL3);
        if ($oQuery3->num_rows() > 0) {
            $oList3      = $oQuery3->result_array();
            $aResult3    = array(
                'rtSum'       => $oList3[0]['SumU'],
                'rtDocNo'       => $oList3[0]['FTXthDocNo'],
            );
        } else {
            // No Data
            $aResult3    = array(
                'rtSum'       => 0,
                'rtDocNo'       => 'No Data',
            );
        }

        if ($aResult1['rtSum'] == $aResult3['rtSum']) {
            $this->db->set('FTXshStaRef', '2');
            $this->db->where('FTXshDocNo', $tDocNoRef);
            $this->db->update('TSVTJob2OrdHD');
        }



        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Approve Success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Approve Fail',
            );
        }
        return $aStatus;
    }

    /**
     * Functionality : Del Document by DocNo
     * Parameters : function parameters
     * Creator : 04/02/2020 Piya
     * Return : Status Delete
     * Return Type : array
     */
    public function FSaMDelMaster($paParams = [])
    {
        $tDocNo = $paParams['tDocNo'];

        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtPickHD');

        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtPickDT');

        // $this->db->where('FTXthDocNo', $tDocNo);
        // $this->db->delete('TCNTPdtTboHDRef');

        // BS Ref
        $this->db->where_in('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtPickHDDocRef');

        // TR Ref
        // $this->db->where_in('FTXshRefDocNo', $tDocNo);
        // $this->db->delete('TCNTPdtReqBchHDDocRef');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'DelMaster Success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'DelMaster Fail',
            );
        }
        return $aStatus;
    }



    //อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMHDUpdateRmk($paDataUpdate)
    {
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXthRmk', $paDataUpdate['FTXthRmk']);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo', $paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtPickHD');

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
        return $aStatus;
    }


    //อัพเดท เอกสาร TR ว่าห้ามใช้งานอีก
    public function FSxMBSUpdateRef($ptTableName, $paParam)
    {
        $nChkDataDocRef  = $this->FSaMBSChkRefDupicate($ptTableName, $paParam);
        $tTableRef       = $ptTableName;
        if (isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1) { //หากพบว่าซ้ำ
            //ลบ
            $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
            $this->db->where_in('FTXthDocNo', $paParam['FTXthDocNo']);
            $this->db->where_in('FTXthRefType', $paParam['FTXthRefType']);
            $this->db->where_in('FTXthRefKey', $paParam['FTXthRefKey']);

            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef, $paParam);
        } else { //หากพบว่าไม่ซ้ำ
            $this->db->insert($tTableRef, $paParam);
        }
        return;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMBSChkRefDupicate($ptTableName, $paParam)
    {
        try {
            $tAgnCode       = $paParam['FTAgnCode'];
            $tBchCode       = $paParam['FTBchCode'];
            $tDocNo         = $paParam['FTXthDocNo'];
            $tRefDocType    = $paParam['FTXthRefType'];
            $tRefDocNo      = $paParam['FTXthDocNo'];
            $tRefKey        = $paParam['FTXthRefKey'];

            $tSQL = "   SELECT
                            FTBchCode
                        FROM $ptTableName
                        WHERE 1=1
                        AND FTAgnCode     = '$tAgnCode'
                        AND FTBchCode     = '$tBchCode'
                        AND FTXthRefType  = '$tRefDocType' ";

            if ($tRefDocType == 1 || $tRefDocType == 3) {
                $tSQL .= " AND FTXthDocNo  = '$tDocNo' ";
            } else {
                $tSQL .= " AND FTXthDocNo  = '$tRefDocNo' ";
            }

            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0) {
                $aResult    = array(
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

    //หาว่าสาขานี้ คลัง default คืออะไร
    public function FSaMCheckWahouseInBCH($ptBCHCode)
    {
        try {
            $nLngID = $this->session->userdata("tLangEdit");
            $tSQL   = " SELECT
                        WAH.FTWahCode,
                        WAH.FTWahName
                    FROM TCNMBranch BCH
                    LEFT JOIN TCNMWahouse_L WAH ON BCH.FTBchCode = WAH.FTBchCode AND BCH.FTWahCode = WAH.FTWahCode AND WAH.FNLngID = $nLngID
                    WHERE 1=1
                    AND BCH.FTBchCode = '$ptBCHCode' ";

            $oQueryHD = $this->db->query($tSQL);
            return $oQueryHD->result();
        } catch (Exception $Error) {
            echo $Error;
        }
    }





    /**
     * Functionality : Get Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Data List Pdt
     * Return Type : Array
     */
    public function FSaMGetPdtInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tDocKey = $paParams['tDocKey'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTXthDocNo,
                        TMP.FNXtdSeqNo,
                        TMP.FTXthDocKey,
                        TMP.FTPdtCode,
                        TMP.FTXtdPdtName,
                        /*TMP.FTXtdStkCode,*/
                        TMP.FTPunCode,
                        TMP.FTPunName,
                        TMP.FCXtdFactor,
                        TMP.FTXtdBarCode,
                        TMP.FTXtdVatType,
                        TMP.FTVatCode,
                        TMP.FCXtdVatRate,
                        TMP.FCXtdQty,
                        TMP.FCXtdQtyAll,
                        TMP.FCXtdSetPrice,
                        TMP.FTXtdStaPrcStk,
                        TMP.FTXtdPdtStaSet,
                        TMP.FTXtdRmk,
                        TMP.FTSessionID,
                        TMP.FDLastUpdOn,
                        TMP.FDCreateOn,
                        TMP.FTLastUpdBy,
                        TMP.FTCreateBy,
                        TMP.FCXtdQtyOrd
                    FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTSessionID = '$tUserSessionID'
                    AND TMP.FTXthDocKey = '$tDocKey'
        ";

        $tSearchList = $paParams['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL .= " AND ((TMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPdtInTmpPageAll($paParams);
            $nPageAll = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Count Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count Pdt
     * Return Type : Number
     */
    public function FSnMTFWGetPdtInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tDocKey = $paParams['tDocKey'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TCNTDocDTTmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID' 
            AND TMP.FTXthDocKey = '$tDocKey'
        ";

        $tSearchList = $paParams['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL .= " AND ((TMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Update Pdt Value in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePdtInTmpBySeq($paParams = [])
    {
        $this->db->set($paParams['tFieldName'], $paParams['tValue']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->update('TCNTDocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete Pdt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePdtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
        $this->db->delete('TCNTDocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete More Pdt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeleteMorePdtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->where_in('FNXtdSeqNo', $paParams['aSeqNo']);
        $this->db->delete('TCNTDocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Clear Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbClearPdtInTmp($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTBddTypeForDeposit', '1');
        $this->db->delete('TCNTDocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Function Get Max Seq From Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Max Seq
     * Return Type : array
     */
    public function FSnMGetMaxSeqDTTemp($paParams = [])
    {
        $tDocNo = $paParams['tDocNo'];
        $tDocKey = $paParams['tDocKey'];
        $tUserSessionID = $paParams['tUserSessionID'];

        $tSQL = "
            SELECT 
                MAX(DOCTMP.FNXtdSeqNo) AS maxSeqNo
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE 1 = 1
        ";

        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";

        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

        $tSQL .= " AND DOCTMP.FTSessionID = '$tUserSessionID'";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result_array();
            $aResult = $oDetail[0]['maxSeqNo'];
        } else {
            $aResult = 0;
        }

        return empty($aResult) ? 0 : $aResult;
    }

    /**
     * Functionality : Get Pdt Data
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Pdt Data
     * Return Type : array
     */
    public function FSaMGetDataPdt($paParams = [])
    {

        $tPdtCode = $paParams['tPdtCode'];
        $FTPunCode = $paParams['tPunCode'];
        $FTBarCode = $paParams['tBarCode'];
        $nLngID = $paParams['nLngID'];

        $tSQL = "
            SELECT
                PDT.FTPdtCode,
                PDT.FTPdtStkControl,
                PDT.FTPdtGrpControl,
                PDT.FTPdtForSystem,
                PDT.FCPdtQtyOrdBuy,
                PDT.FCPdtCostDef,
                PDT.FCPdtCostOth,
                PDT.FCPdtCostStd,
                PDT.FCPdtMin,
                PDT.FCPdtMax,
                PDT.FTPdtPoint,
                PDT.FCPdtPointTime,
                PDT.FTPdtType,
                PDT.FTPdtSaleType,
                ISNULL(PRI4PDT.FCPgdPriceRet,0) AS FTPdtSalePrice,
                PDT.FTPdtSetOrSN,
                PDT.FTPdtStaSetPri,
                PDT.FTPdtStaSetShwDT,
                PDT.FTPdtStaAlwDis,
                PDT.FTPdtStaAlwReturn,
                PDT.FTPdtStaVatBuy,
                PDT.FTPdtStaVat,
                PDT.FTPdtStaActive,
                PDT.FTPdtStaAlwReCalOpt,
                PDT.FTPdtStaCsm,
                PDT.FTTcgCode,
                PDT.FTPtyCode,
                PDT.FTPbnCode,
                PDT.FTPmoCode,
                PDT.FTVatCode,
                PDT.FDPdtSaleStart,
                PDT.FDPdtSaleStop,
                PDTL.FTPdtName,
                PDTL.FTPdtNameOth,
                PDTL.FTPdtNameABB,
                PDTL.FTPdtRmk,
                PKS.FTPunCode,
                PKS.FCPdtUnitFact,
                VAT.FCVatRate,
                UNTL.FTPunName,
                BAR.FTBarCode,
                BAR.FTPlcCode,
                PDTLOCL.FTPlcName,
                PDTSRL.FTSrnCode,
                PDT.FCPdtCostStd,
                CAVG.FCPdtCostEx,
                CAVG.FCPdtCostIn,
                SPL.FCSplLastPrice
            FROM TCNMPdt PDT WITH (NOLOCK)
            LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
            LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
            LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
            LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
            LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
            LEFT JOIN (
                SELECT DISTINCT
                    FTVatCode,
                    FCVatRate,
                    FDVatStart
                FROM TCNMVatRate WITH (NOLOCK)
                WHERE CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart ) VAT
            ON PDT.FTVatCode = VAT.FTVatCode
            LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
            LEFT JOIN TCNMPdtSpl SPL        WITH (NOLOCK)   ON PDT.FTPdtCode    = SPL.FTPdtCode AND BAR.FTBarCode = SPL.FTBarCode
            LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
            LEFT JOIN (
                SELECT DISTINCT
                    P4PDT.FTPdtCode,
                    P4PDT.FTPunCode,
                    P4PDT.FDPghDStart,
                    P4PDT.FTPghTStart,
                    P4PDT.FCPgdPriceRet
                    /*,P4PDT.FCPgdPriceWhs
                    ,P4PDT.FCPgdPriceNet*/
                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                WHERE 1=1
                AND (CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121))
                AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121))
            ) AS PRI4PDT
            ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode
            WHERE 1 = 1
        ";

        if ($tPdtCode != "") {
            $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
        }

        if ($FTBarCode != "") {
            $tSQL .= "AND BAR.FTBarCode = '$FTBarCode'";
        }

        $tSQL .= " ORDER BY FDVatStart DESC";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItem'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $tResult = json_encode($aResult);
        $aResult = json_decode($tResult, true);
        return $aResult;
    }

    /**
     * Functionality : Insert DT to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : array
     */
    public function FSaMInsertPDTToTemp($paData = [], $paDataWhere = [])
    {

        $paData = $paData['raItem'];
        if ($paDataWhere['tOptionAddPdt'] == '1') {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL = "   
                SELECT 
                    FNXtdSeqNo, 
                    FCXtdQty 
                FROM TCNTDocDTTmp 
                WHERE FTBchCode = '" . $paDataWhere['tBchCode'] . "' 
                AND FTXthDocNo = '" . $paDataWhere['tDocNo'] . "'
                AND FTXthDocKey = '" . $paDataWhere['tDocKey'] . "'
                AND FTSessionID = '" . $paDataWhere['tUserSessionID'] . "'
                AND FTPdtCode = '" . $paData["FTPdtCode"] . "' 
                AND FTXtdBarCode = '" . $paData["FTBarCode"] . "'
                ORDER BY FNXtdSeqNo
            ";

            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0) { // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult = $oQuery->row_array();
                $tSQL = "
                    UPDATE TCNTDocDTTmp SET
                        FCXtdQty = '" . ($aResult["FCXtdQty"] + 1) . "'
                    WHERE 
                    FTBchCode = '" . $paDataWhere['tBchCode'] . "' AND
                    FTXthDocNo  = '" . $paDataWhere['tDocNo'] . "' AND
                    FNXtdSeqNo = '" . $aResult["FNXtdSeqNo"] . "' AND
                    FTXthDocKey = '" . $paDataWhere['tDocKey'] . "' AND
                    FTSessionID = '" . $paDataWhere['tUserSessionID'] . "' AND
                    FTPdtCode = '" . $paData["FTPdtCode"] . "' AND 
                    FTXtdBarCode = '" . $paData["FTBarCode"] . "'";

                $this->db->query($tSQL);

                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            } else {

                // เพิ่มรายการใหม่
                $this->db->set('FTPdtCode', $paData['FTPdtCode']);
                $this->db->set('FTXtdPdtName', $paData['FTPdtName']);
                $this->db->set('FCXtdFactor', $paData['FCPdtUnitFact']);
                $this->db->set('FCPdtUnitFact', $paData['FCPdtUnitFact']);
                $this->db->set('FTPunCode', $paData['FTPunCode']);
                $this->db->set('FTPunName', $paData['FTPunName']);
                $this->db->set('FTXtdVatType', $paData['FTPdtStaVatBuy']);
                $this->db->set('FTVatCode', $paData['FTVatCode']);
                $this->db->set('FCXtdVatRate', $paData['FCVatRate']);
                $this->db->set('FCXtdNet', $paData['FTPdtPoint'] * $paData['FCPdtCostStd']);
                $this->db->set('FTXtdStaAlwDis', $paData['FTPdtStaAlwDis']);
                $this->db->set('FCXtdQty', 1);  // เพิ่มสินค้าใหม่
                $this->db->set('FCXtdQtyAll', 1 * $paData['FCPdtUnitFact']); // จากสูตร qty * fector
                $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

                $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
                $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
                $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
                $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
                $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
                $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
                $this->db->set('FTSessionID', $paDataWhere['tUserSessionID']);
                $this->db->set('FDLastUpdOn', date('Y-m-d h:i:s'));
                $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
                $this->db->set('FDCreateOn', date('Y-m-d h:i:s'));
                $this->db->set('FTCreateBy', $this->session->userdata('tSesUsername'));

                $this->db->insert('TCNTDocDTTmp');

                $this->db->last_query();

                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success.',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add.',
                    );
                }
            }
        } else {
            // เพิ่มแถวใหม่
            $this->db->set('FTPdtCode', $paData['FTPdtCode']);
            $this->db->set('FTXtdPdtName', $paData['FTPdtName']);
            $this->db->set('FCXtdFactor', $paData['FCPdtUnitFact']);
            $this->db->set('FCPdtUnitFact', $paData['FCPdtUnitFact']);
            $this->db->set('FTPunCode', $paData['FTPunCode']);
            $this->db->set('FTPunName', $paData['FTPunName']);
            $this->db->set('FTXtdVatType', $paData['FTPdtStaVatBuy']);
            $this->db->set('FTVatCode', $paData['FTVatCode']);
            $this->db->set('FCXtdVatRate', $paData['FCVatRate']);
            $this->db->set('FCXtdNet', $paData['FTPdtPoint'] * $paData['FCPdtCostStd']);
            $this->db->set('FTXtdStaAlwDis', $paData['FTPdtStaAlwDis']);
            $this->db->set('FCXtdQty', 1);  // เพิ่มสินค้าใหม่
            $this->db->set('FCXtdQtyAll', 1 * $paData['FCPdtUnitFact']); // จากสูตร qty * fector
            $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

            $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
            $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
            $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
            $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
            $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
            $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
            $this->db->set('FTSessionID', $paDataWhere['tUserSessionID']);
            $this->db->set('FDLastUpdOn', date('Y-m-d h:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FDCreateOn', date('Y-m-d h:i:s'));
            $this->db->set('FTCreateBy', $this->session->userdata('tSesUsername'));

            $this->db->insert('TCNTDocDTTmp');

            $this->db->last_query();

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
        }

        return $aStatus;
    }

    /**
     * Functionality : คำนวณใน DT Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMCalInTmp($aParams = [])
    {
        $tUserSessionID = $aParams['tUserSessionID'];
        $tDocKey = $aParams['tDocKey'];

        $tSQL = "
            SELECT
                SUM(CASE 
                    WHEN TMP.FTBddTypeForDeposit = '1' THEN ISNULL(TMP.FCBddRefAmtForDeposit, 0)
                    ELSE 0
                END) AS FCBddRefAmtCashTotal,
                SUM(CASE 
                    WHEN TMP.FTBddTypeForDeposit = '2' THEN ISNULL(TMP.FCBddRefAmtForDeposit, 0)
                    ELSE 0
                END) AS FCBddRefAmtChequeTotal,
                SUM(ISNULL(TMP.FCBddRefAmtForDeposit, 0)) AS FCBddRefAmtTotal
            FROM TCNTDocDTTmp TMP WITH(NOLOCK)
            WHERE FTSessionID = '$tUserSessionID' AND FTXthDocKey = '$tDocKey'
            GROUP BY TMP.FTSessionID
        ";

        $oQuery = $this->db->query($tSQL);

        $aData = [
            'FCBddRefAmtPdtTotal' => 0,
            'FCBddRefAmtTotal' => 0
        ];

        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->row_array();
        }

        return $aData;
    }

    // Function: Get Data DO HD List
    public function FSoMPCKCallRefIntDocDataTable($paDataCondition)
    {
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tPCKRefIntBchCode        = $aAdvanceSearch['tPCKRefIntBchCode'];
        $tPCKRefIntDocNo          = $aAdvanceSearch['tPCKRefIntDocNo'];
        $tPCKRefIntDocDateFrm     = $aAdvanceSearch['tPCKRefIntDocDateFrm'];
        $tPCKRefIntDocDateTo      = $aAdvanceSearch['tPCKRefIntDocDateTo'];
        $tPCKRefIntStaDoc         = $aAdvanceSearch['tPCKRefIntStaDoc'];
        // $tCarCode                = $aAdvanceSearch['tCarCode'];
        // $tCstCode                = $aAdvanceSearch['tCstCode'];

        $tSQLMain = "SELECT DATAJOB1.*
                        FROM(
                            SELECT DOCJ1.*,DOCREF.FTXthRefDocNo
                            FROM (
                                SELECT  DISTINCT
                                    JOB1.FTAgnCode,
                                    JOB1.FTBchCode,
                                    BCHL.FTBchName,
                                    JOB1.FTXshDocNo,
                                    CONVERT(CHAR(10),JOB1.FDXshDocDate,103) AS FDXshDocDate,
                                    CONVERT(CHAR(5), JOB1.FDXshDocDate,108) AS FTXshDocTime,
                                    JOB1.FTXshStaDoc,
                                    JOB1.FTXshStaApv,
                                    CSTL.FTCstCode,
                                    CSTL.FTCstName,
                                    JOB1.FTCreateBy,
                                    JOB1.FDCreateOn,
                                    JOB1.FNXshStaDocAct,
                                    USRL.FTUsrName AS FTCreateByName,
                                    JOB1.FTXshApvCode,
                                    CST.FTCstTaxNo,
                                    CST.FTCstTel,
                                    CST.FTCstEmail,
                                    ADDL.FTAddV2Desc1,
                                    CAR.FTCarRegNo,
			                        CARIFL.FTCaiName AS FTBndName
                                FROM TSVTJob2OrdHD JOB1 WITH (NOLOCK)
                                LEFT JOIN TSVTJob2OrdHDCst HDCst WITH (NOLOCK) ON JOB1.FTXshDocNo = HDCst.FTXshDocNo AND JOB1.FTBchCode = HDCst.FTBchCode
                                LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON  CAR.FTCarCode = HDCst.FTCarCode
                                LEFT JOIN TSVMCarInfo CARIF  WITH(NOLOCK) ON CAR.FTCarBrand = CARIF.FTCaiCode AND CARIF.FTCaiType = 2
								LEFT JOIN TSVMCarInfo_L CARIFL  WITH(NOLOCK) ON CARIFL.FTCaiCode = CARIF.FTCaiCode AND CARIFL.FNLngID = 1
                                LEFT JOIN TCNMCst CST WITH (NOLOCK) ON JOB1.FTCstCode = CST.FTCstCode
                                LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON JOB1.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = 1
                                LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON JOB1.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = 1
                                LEFT JOIN TCNMCst_L CSTL WITH (NOLOCK) ON JOB1.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
                                LEFT JOIN TCNMCstAddress_L ADDL WITH (NOLOCK) ON JOB1.FTCstCode = ADDL.FTCstCode AND ADDL.FNLngID = 1
                                WHERE  JOB1.FTXshStaDoc = 1 
                                AND JOB1.FTXshStaApv = 1   
                                AND ( ISNULL( ADDL.FTAddRefNo, '' ) = '1' OR ISNULL( ADDL.FTAddRefNo, '' ) = '' ) 
                                AND   ISNULL(JOB1.FTXshStaRef,'') != 2
                                ";

        // if(isset($tCarCode) && !empty($tCarCode)){
        //     $tSQLMain .= " AND (HDCst.FTCarCode = '$tCarCode')";
        // }

        // if(isset($tCstCode) && !empty($tCstCode)){
        //     $tSQLMain .= " AND (JOB1.FTCstCode = '$tCstCode')";
        // }


        if (isset($tPCKRefIntBchCode) && !empty($tPCKRefIntBchCode)) {
            $tSQLMain .= " AND (JOB1.FTBchCode = '$tPCKRefIntBchCode')";
        }

        if (isset($tPCKRefIntDocNo) && !empty($tPCKRefIntDocNo)) {
            $tSQLMain .= " AND (JOB1.FTXshDocNo LIKE '%$tPCKRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tPCKRefIntDocDateFrm) && !empty($tPCKRefIntDocDateTo)) {
            $tSQLMain .= " AND ((JOB1.FDXshDocDate BETWEEN CONVERT(datetime,'$tPCKRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tPCKRefIntDocDateTo 23:59:59')) OR (JOB1.FDXshDocDate BETWEEN CONVERT(datetime,'$tPCKRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tPCKRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if (isset($tPCKRefIntStaDoc) && !empty($tPCKRefIntStaDoc)) {
            if ($tPCKRefIntStaDoc == 3) {
                $tSQLMain .= " AND JOB1.FTXshStaDoc = '$tPCKRefIntStaDoc'";
            } elseif ($tPCKRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(JOB1.FTXshStaApv,'') = '' AND JOB1.FTXshStaDoc != '3'";
            } elseif ($tPCKRefIntStaDoc == 1) {
                $tSQLMain .= " AND JOB1.FTXshStaApv = '$tPCKRefIntStaDoc'";
            }
        }

        $tSQLMain   .= "
                ) AS DOCJ1
                LEFT JOIN (
                    SELECT  DOCREF.FTAgnCode,DOCREF.FTBchCode,DOCREF.FTXthRefDocNo
                    FROM TCNTPdtPickHDDocRef DOCREF WITH(NOLOCK)
                    WHERE DOCREF.FTBchCode = '$tPCKRefIntBchCode' AND DOCREF.FTXthRefKey = 'Job2Ord'
                    GROUP BY DOCREF.FTAgnCode,DOCREF.FTBchCode,DOCREF.FTXthRefDocNo
                ) DOCREF ON DOCJ1.FTAgnCode = DOCREF.FTAgnCode AND DOCJ1.FTBchCode = DOCREF.FTBchCode AND DOCJ1.FTXshDocNo = DOCREF.FTXthRefDocNo
            ) AS DATAJOB1
          
        ";

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTBchCode ASC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                        (  $tSQLMain ";

        $tSQL .= ") Base) AS c ORDER BY c.FDCreateOn DESC";

        // ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
        // echo $tSQL;
        // exit();

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow / $paDataCondition['nRow']);
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

    // DT ของใบสั่งงาน
    public function FSoMPCKCallRefIntDocDTDataTable($paData)
    {

        $nLngID    =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];


        // $tSQL = "SELECT
        //          DT.FTPdtStaSet,
        //          DTSET.FTPsvType,
        //          DT.FTBchCode,
        //          DT.FTXshDocNo,
        //          DT.FNXsdSeqNo,
        //          DT.FTPdtCode,
        //          DT.FTXsdPdtName, 
        //          DT.FTPunCode,
        //          DT.FTPunName,
        //          DT.FCXsdFactor,
        //          DT.FTXsdBarCode,
        //          DT.FTXsdRmk,
        //          DT.FDLastUpdOn,
        //          DT.FTLastUpdBy,
        //          DT.FDCreateOn,
        //          DT.FTCreateBy,
        //          DT.FCXsdQty,
        //          DT.FCXsdQtyAll,
        //          CASE 
        //          WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQty
        //          ELSE 
        //          DT.FCXsdQty - DOC.QTYUse
        //          END AS FCXsdQtyL,
        //          CASE 
        //          WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQtyAll
        //          ELSE 
        //          DT.FCXsdQtyAll - DOC.QTYUse
        //          END AS FCXsdQtyAllL 
        //          FROM TSVTJob2OrdDT DT WITH(NOLOCK)
        //          LEFT JOIN (
        //          SELECT SUM(A.FCXtdQtyOrd) AS QTYUSE , A.* FROM (
        //          SELECT HD.FTXthDocNo , REF.FTXthRefDocNo AS REFJOB2 , DT.FTPdtCode , DT.FCXtdQtyOrd FROM TCNTPdtPickHD HD 
        //          LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
        //          LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo AND REF.FTXthRefKey = 'Job2Ord' AND REF.FTXthRefType = 1   
        //          WHERE HD.FTXthStaApv = 1 ) AS A GROUP BY A.FTXthDocNo , A.REFJOB2 , A.FTPdtCode , A.FCXtdQtyOrd
        //         ) DOC ON DOC.REFJOB2 = DT.FTXshDocNo AND DOC.FTPdtCode = DT.FTPdtCode
        //         LEFT JOIN  TSVTJob2OrdDTSet DTSET WITH(NOLOCK) ON  DTSET.FTXshDocNo = DT.FTXshDocNo AND DT.FNXsdSeqNo = DTSET.FNXsdSeqNo AND DTSET.FTPsvType != 2
        //          WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo' AND DT.FTXsdStaApvTask = 1 AND  (DT.FTPdtStaSet NOT IN ('2','5') OR DTSET.FTPsvType = 1  ) ";


        //         $tSQL = "    SELECT 
        //            DT.FTBchCode, 
        //            DT.FTXshDocNo, 
        //            DT.FNXsdSeqNo, 
        //            DT.FTPdtCode, 
        //            DT.FTXsdPdtName, 
        //            DT.FTPunCode, 
        //            DT.FTPunName, 
        //            DT.FCXsdFactor, 
        //            DT.FTXsdBarCode, 
        //            DT.FTXsdRmk, 
        //            DT.FCXsdQty,
        //            DT.FCXsdQtyAll,
        //            DT.FTPdtStaSet,
        //            CASE
        //                WHEN ISNULL(DOC.QTYUse, 0) = 0
        //                THEN DT.FCXsdQty
        //                ELSE DT.FCXsdQty - DOC.QTYUse
        //            END AS FCXsdQtyL,

        //            CASE
        //                WHEN ISNULL(DOC.QTYUse, 0) = 0
        //                THEN DT.FCXsdQtyAll
        //                ELSE DT.FCXsdQtyAll - DOC.QTYUse
        //            END AS FCXsdQtyAllL

        //     FROM TSVTJob2OrdDT DT WITH(NOLOCK)
        //          LEFT JOIN
        //     (
        //         SELECT
        //   SUM (A.FCXtdQtyOrd) AS QTYUSE,A.FTPdtCode , A.REFJOB2
        //  FROM
        //   (
        //    SELECT
        //     REF.FTXthRefDocNo AS REFJOB2,
        //     DT.FTPdtCode,
        //     DT.FCXtdQtyOrd
        //    FROM
        //     TCNTPdtPickHD HD
        //    LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
        //    LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
        //    AND REF.FTXthRefKey = 'Job2Ord'
        //    AND REF.FTXthRefType = 1
        //    WHERE
        //     HD.FTXthStaApv = 1
        //    AND REF.FTXthRefDocNo = '$tDocNo'
        //    AND HD.FNXthDocType = '12'
        //   ) AS A
        //  GROUP BY
        //   A.REFJOB2,
        //   A.FTPdtCode
        //     ) DOC ON DOC.REFJOB2 = DT.FTXshDocNo
        //              AND DOC.FTPdtCode = DT.FTPdtCode  
        //     WHERE DT.FTBchCode = '$tBchCode' AND DT.FTXshDocNo = '$tDocNo' AND DT.FTXsdStaApvTask = 1

        //     UNION ALL

        //        SELECT 
        //         DTSET.FTBchCode, 
        //            DTSET.FTXshDocNo, 
        //            DTSET.FNXsdSeqNo, 
        //            DTSET.FTPdtCode, 
        //            DTSET.FTXsdPdtName, 
        //            DTSET.FTPunCode, 
        //            UNIL.FTPunName, 
        //            PACK.FCPdtUnitFact AS FCXsdFactor, 
        //            '' AS FTXsdBarCode, 
        //            '' AS FTXsdRmk, 
        //             FCXsdQtySet AS FCXsdQty,
        // 	       FCXsdQtySet AS FCXsdQtyAll,
        //            '' AS FTPdtStaSet,
        //            CASE
        //            WHEN ISNULL(DOC.QTYUse, 0) = 0
        //            THEN DTSET.FCXsdQtySet
        //            ELSE DTSET.FCXsdQtySet - DOC.QTYUse
        //        END AS FCXsdQtyL,
        //        CASE
        //            WHEN ISNULL(DOC.QTYUse, 0) = 0
        //            THEN DTSET.FCXsdQtySet
        //            ELSE DTSET.FCXsdQtySet - DOC.QTYUse
        //        END AS FCXsdQtyAllL
        // FROM TSVTJob2OrdDTSet DTSET
        //      LEFT JOIN TCNMPdtUnit_L UNIL ON DTSET.FTPunCode = UNIL.FTPunCode
        //                                      AND UNIL.FNLngID = 1
        //      LEFT JOIN TCNMPdtPackSize PACK ON DTSET.FTPdtCode = PACK.FTPdtCode
        //                                        AND UNIL.FTPunCode = PACK.FTPunCode
        // 									    LEFT JOIN
        // (

        //     SELECT
        //   SUM (A.FCXtdQtyOrd) AS QTYUSE,A.FTPdtCode , A.REFJOB2
        //  FROM
        //   (
        //    SELECT
        //     REF.FTXthRefDocNo AS REFJOB2,
        //     DT.FTPdtCode,
        //     DT.FCXtdQtyOrd
        //    FROM
        //     TCNTPdtPickHD HD
        //    LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
        //    LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
        //    AND REF.FTXthRefKey = 'Job2Ord'
        //    AND REF.FTXthRefType = 1
        //    WHERE
        //     HD.FTXthStaApv = 1
        //    AND REF.FTXthRefDocNo = '$tDocNo'
        //    AND HD.FNXthDocType = '12'
        //   ) AS A
        //  GROUP BY
        //   A.REFJOB2,
        //   A.FTPdtCode

        // ) DOC ON DOC.REFJOB2 = DTSET.FTXshDocNo
        //          AND DOC.FTPdtCode = DTSET.FTPdtCode
        //     WHERE DTSET.FTBchCode = '$tBchCode' AND DTSET.FTXshDocNo = '$tDocNo' AND DTSET.FTPsvType != 2  ORDER BY  DT.FNXsdSeqNo ASC ";


        $tSQL = "SELECT DTJORD.*,ISNULL(DTPL.QTYUSE,0) AS QTYUSE,ISNULL(DTJORD.FCXsdQtyAll,0)-ISNULL(DTPL.QTYUSE,0) AS FCXtdQtyLeft  FROM (
    SELECT DT.FTBchCode, 
           DT.FTXshDocNo, 
           CONCAT(DT.FNXsdSeqNo,0) AS FNXsdSeqNo, 
           DT.FTPdtCode, 
           DT.FTXsdPdtName, 
           DT.FTPunCode, 
           DT.FTPunName, 
           DT.FTXsdBarCode,  
           DT.FCXsdQtyAll, 
           DT.FTPdtStaSet
    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
    WHERE DT.FTBchCode = '$tBchCode' AND DT.FTXshDocNo = '$tDocNo' AND DT.FTXsdStaApvTask = 1

    
    UNION ALL
    
    
    SELECT DT.FTBchCode, 
           DT.FTXshDocNo, 
           CONCAT(DT.FNXsdSeqNo,DT.FNPstSeqNo) AS FNXsdSeqNo,
           DT.FTPdtCode, 
           DT.FTXsdPdtName, 
           DT.FTPunCode, 
           '' AS FTPunName, 
           '' AS FTXsdBarCode,  
           FCXsdQtySet AS FCXsdQtyAll, 
           '' AS FTPdtStaSet
    FROM TSVTJob2OrdDTSET DT WITH(NOLOCK)
    WHERE FTXshDocNo = '$tDocNo' AND FTPsvType != 2   ) DTJORD
    LEFT JOIN (
              
                        SELECT REF.FTXthRefDocNo AS REFJOB2, 
                               DT.FTPdtCode, 
                               SUM(DT.FCXtdQtyOrd) AS QTYUSE
                        FROM TCNTPdtPickHD HD
                             LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
                             LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
                                                                  AND REF.FTXthRefKey = 'Job2Ord'
                                                                  AND REF.FTXthRefType = 1
                        WHERE HD.FTXthStaApv = 1
                              AND REF.FTXthRefDocNo = '$tDocNo'
                              AND HD.FNXthDocType = '12'
                         GROUP BY REF.FTXthRefDocNo, 
                                DT.FTPdtCode
    ) DTPL ON DTJORD.FTXshDocNo = DTPL.REFJOB2 AND DTJORD.FTPdtCode = DTPL.FTPdtCode 
    WHERE DTJORD.FTBchCode = '$tBchCode' AND DTJORD.FTXshDocNo = '$tDocNo'
    ORDER BY DTJORD.FNXsdSeqNo ";


        // print_r($tSQL); die();

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // Functionality: Get Data Purchase Order HD List
    public function FSoMPCKCallRefIntDocDTPCKDataTable($paData)
    {

        $nLngID    =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocRefNo    =  $paData['tDocNo'];


        $tSQL1 = "SELECT FTXthDocNo FROM TCNTPdtPickHDDocRef WHERE FTXthRefDocNo = '$tDocRefNo' ";
        $oQuery1 = $this->db->query($tSQL1);
        if ($oQuery1->num_rows() > 0) {
            $oDataList          = $oQuery1->result_array();
            $tDocNo =  $oDataList[0]['FTXthDocNo'];
        } else {
            $tDocNo = '';
        }

        $tSQL3 = "SELECT
                        DT.FTPdtCode
                        FROM TSVTJob2OrdDT DT WITH(NOLOCK)
                WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocRefNo' ";

        $oQuery3 = $this->db->query($tSQL3);
        $oDataList3          = $oQuery3->result_array();

        $tPdtCode = '';
        foreach ($oDataList3 as $aValue3) {
            $tPdtCode .= "'" . $aValue3['FTPdtCode'] . "',";
        }

        $tWherePdtCode = substr($tPdtCode, 0, -1);


        $tSQL2 = "SELECT FTPdtCode,FCXtdQtyOrd FROM TCNTPdtPickDT WHERE  FTXthDocNo = '$tDocNo' AND FTPdtCode IN ($tWherePdtCode) ";
        $oQuery2 = $this->db->query($tSQL2);



        if ($oQuery2->num_rows() > 0) {
            $oDataList2          = $oQuery2->result_array();
            $aResult = array(
                'raItems'       => $oDataList2,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    //     // นำข้อมูลจาก Browse ลง DTTemp
    //     public function FSoMPCKCallRefIntDocInsertDTToTemp($paData)
    //     {

    //         $tPCKDocNo        = $paData['tPCKDocNo'];
    //         $tPCKFrmBchCode   = $paData['tPCKFrmBchCode'];
    //         $tAgnCode = $paData['tPCKAgnCode'];
    //         $tSessionID                  = $this->session->userdata('tSesSessionID');
    //         $tRefIntDocNo   = $paData['tRefIntDocNo'];
    //         $tRefIntBchCode = $paData['tRefIntBchCode'];
    //         $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';
    //         $ptDocType = 'Job2Ord';
    //         $tPCKOptionAddPdt = $paData['tPCKOptAddPdt'];



    //         $oQueryCheckTempDocType = $this->FSnMPCKCheckTempDocType($paData);
    //         if ($oQueryCheckTempDocType['raItems'] == '') {

    //             //ลบรายการสินค้า
    //             $this->db->where('FTXthDocNo', $tPCKDocNo);
    //             $this->db->where('FTSessionID', $paData['tSessionID']);
    //             $this->db->delete('TCNTDocDTTmp');
    //         } elseif ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {

    //             //ลบรายการสินค้า
    //             $this->db->where('FTXthDocNo', $tPCKDocNo);
    //             $this->db->where('FTSessionID', $paData['tSessionID']);
    //             $this->db->delete('TCNTDocDTTmp');
    //         }


    //         if ($tPCKOptionAddPdt == 1) {
    //             $tSQLSelectDT   = "SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXsdBarCode  , DT.FNXsdSeqNo , DT.FCXsdQty
    //                                FROM TSVTJob2OrdDT DT WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";
    //             $oQuery         = $this->db->query($tSQLSelectDT);

    //             $tSQLGetSeqPDT = "SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
    //                                 FROM TCNTDocDTTmp WITH(NOLOCK)
    //                                 WHERE FTSessionID = " . $this->db->escape($tSessionID) . "
    //                                 AND FTXthDocKey = 'TCNTPdtPickHD'
    //                             ";
    //             $oQuerySeq = $this->db->query($tSQLGetSeqPDT);
    //             $aResultDTSeq = $oQuerySeq->row_array();

    //             if ($oQuery->num_rows() > 0) {
    //                 $aResultDT      = $oQuery->result_array();
    //                 $nCountResultDT = count($aResultDT);
    //                 if ($nCountResultDT >= 0) {
    //                     for ($j = 0; $j < $nCountResultDT; $j++) {
    //                         $tSQL   =   "   SELECT FNXtdSeqNo , FCXtdQty 
    //                                         FROM TCNTDocDTTmp
    //                                         WHERE FTXthDocNo            = '" . $tPCKDocNo . "'
    //                                         AND FTBchCode               = " . $this->db->escape($tPCKFrmBchCode) . "
    //                                         AND FTXthDocKey             = 'TCNTPdtPickHD'
    //                                         AND FTSessionID             = " . $this->db->escape($tSessionID) . "
    //                                         AND FTPdtCode               = " . $this->db->escape($aResultDT[$j]["FTPdtCode"]) . "
    //                                         AND FTPunCode               = " . $this->db->escape($aResultDT[$j]["FTPunCode"]) . " 
    //                                         AND ISNULL(FTXtdBarCode,'') = " . $this->db->escape($aResultDT[$j]["FTXsdBarCode"]) . " 
    //                                         ORDER BY FNXtdSeqNo ";


    //                         $oQuery = $this->db->query($tSQL);

    //                         if ($oQuery->num_rows() > 0) {

    //                             // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
    //                             $aResult    =   $oQuery->row_array();
    //                             $tSQL       =   "   UPDATE TCNTDocDTTmp
    //                                                 SET FCXtdQty = '" . ($aResult["FCXtdQty"] + $aResultDT[$j]["FCXsdQty"]) . "'
    //                                                 WHERE FTXthDocNo            = '" . $tPCKDocNo . "'
    //                                                 AND FTBchCode               = " . $this->db->escape($tPCKFrmBchCode) . "
    //                                                 AND FNXtdSeqNo              = " . $this->db->escape($aResult["FNXtdSeqNo"]) . "
    //                                                 AND FTXthDocKey             = 'TCNTPdtPickHD'
    //                                                 AND FTSessionID             = " . $this->db->escape($tSessionID) . "
    //                                                 AND FTPdtCode               = " . $this->db->escape($aResultDT[$j]["FTPdtCode"]) . "
    //                                                 AND FTPunCode               = " . $this->db->escape($aResultDT[$j]["FTPunCode"]) . " 
    //                                                 AND ISNULL(FTXtdBarCode,'') = " . $this->db->escape($aResultDT[$j]["FTXsdBarCode"]) . " ";
    //                             $this->db->query($tSQL);
    //                         } else {
    //                             $tSQL = "INSERT INTO TCNTDocDTTmp (
    //                                 FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
    //                                 FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
    //                                 FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
    //                                 FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
    //                                 FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
    //                                 FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //                                 FTXtdPdtStaSet,FTXtdRmk,
    //                                 FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTWahCode,
    //                                 FCXtdQtyOrd,FTAgnCode
    //                             )
    //                             SELECT
    //                                 '$tPCKFrmBchCode' as FTBchCode,
    //                                 '' as FTXshDocNo,
    //                                 -- ROW_NUMBER() OVER(ORDER BY DT.FNXsdSeqNo DESC ) AS FNXtdSeqNo,
    //                                 " . $aResultDTSeq['FNXtdSeqNo'] . " + DT.FNXsdSeqNo,
    //                                 'TCNTPdtPickHD' AS FTXthDocKey,
    //                                 DT.FTPdtCode,
    //                                 DT.FTXsdPdtName,
    //                                 DT.FTPunCode,
    //                                 DT.FTPunName,
    //                                 DT.FCXsdFactor,
    //                                 DT.FTXsdBarCode,
    //                                 '' AS FTSrnCode,
    //                                 PDT.FTPdtStaVatBuy,
    //                                 PDT.FTVatCode AS FTVatCode,
    //                                 DT.FCXsdVatRate,
    //                                 PDT.FTPdtSaleType AS FTXtdSaleType,
    //                                 PDT.FCPdtCostStd AS FCXtdSalePrice,
    //                                 CASE 
    //                                 WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQty
    //                                 ELSE 
    //                                  DT.FCXsdQty - DOC.QTYUse
    //                                END AS FCXsdQty ,
    //                                CASE 
    //                                 WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQtyAll
    //                                 ELSE 
    //                                  DT.FCXsdQtyAll - DOC.QTYUse
    //                                END AS FCXsdQtyAll,
    //                                 PDT.FCPdtCostStd * DT.FCXsdQty AS FCXtdSetPrice,
    //                                 0 AS FCXsdAmtB4DisChg,
    //                                 '' AS FTXsdDisChgTxt,
    //                                 0 as FCXsdQtyLef,
    //                                 0 as FCXsdQtyRfn,
    //                                 '' as FTXsdStaPrcStk,
    //                                 PDT.FTPdtStaAlwDis,
    //                                 0 as FNXsdPdtLevel,
    //                                 '' as FTXsdPdtParent,
    //                                 0 as FCXsdQtySet,
    //                                 '' as FTPdtStaSet,
    //                                 '' as FTXsdRmk,   
    //                                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
    //                                 CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
    //                                 CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
    //                                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
    //                                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
    //                                 DT.FTWahCode,
    //                                 CASE 
    //                                 WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQty
    //                                 ELSE 
    //                                  DT.FCXsdQty - DOC.QTYUse
    //                                END AS FCXtdQtyOrd ,
    //                                 '$tAgnCode' as FTAgnCode
    //                             FROM
    //                             TSVTJob2OrdDT DT WITH (NOLOCK)
    //                                 LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
    //                                 LEFT JOIN (
    //                                     SELECT SUM(A.FCXtdQtyOrd) AS QTYUSE , A.* FROM (
    //                                      SELECT HD.FTXthDocNo , REF.FTXthRefDocNo AS REFJOB2 , DT.FTPdtCode , DT.FCXtdQtyOrd FROM TCNTPdtPickHD HD 
    //                                      LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
    //                                      LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo AND REF.FTXthRefKey = 'Job2Ord' AND REF.FTXthRefType = 1   WHERE HD.FTXthStaApv = 1
    //                                     ) AS A GROUP BY A.FTXthDocNo , A.REFJOB2 , A.FTPdtCode , A.FCXtdQtyOrd
    //                                    ) DOC ON DOC.REFJOB2 = DT.FTXshDocNo AND DOC.FTPdtCode = DT.FTPdtCode
    //                                 WHERE  DT.FTBchCode = " . $this->db->escape($tRefIntBchCode) . " 
    //                                 AND  DT.FTXshDocNo = " . $this->db->escape($tRefIntDocNo) . " 
    //                                 AND DT.FNXsdSeqNo = " . $this->db->escape($aResultDT[$j]["FNXsdSeqNo"]) . " ";

    //                             $oQuery = $this->db->query($tSQL);
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             $tSQL = "INSERT INTO TCNTDocDTTmp (
    //                 FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
    //                 FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
    //                 FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
    //                 FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
    //                 FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
    //                 FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    //                 FTXtdPdtStaSet,FTXtdRmk,
    //                 FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTWahCode,
    //                 FCXtdQtyOrd,FTAgnCode
    //             )
    //             SELECT
    //                 '$tPCKFrmBchCode' as FTBchCode,
    //                 '' as FTXshDocNo,
    //                 ROW_NUMBER() OVER(ORDER BY DT.FNXsdSeqNo DESC ) AS FNXtdSeqNo,
    //                 'TCNTPdtPickHD' AS FTXthDocKey,
    //                 DT.FTPdtCode,
    //                 DT.FTXsdPdtName,
    //                 DT.FTPunCode,
    //                 DT.FTPunName,
    //                 DT.FCXsdFactor,
    //                 DT.FTXsdBarCode,
    //                 '' AS FTSrnCode,
    //                 PDT.FTPdtStaVatBuy,
    //                 PDT.FTVatCode AS FTVatCode,
    //                 DT.FCXsdVatRate,
    //                 PDT.FTPdtSaleType AS FTXtdSaleType,
    //                 PDT.FCPdtCostStd AS FCXtdSalePrice,
    //                 CASE 
    //     WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQty
    //     ELSE 
    //      DT.FCXsdQty - DOC.QTYUse
    //    END AS FCXsdQty ,
    //    CASE 
    //     WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQtyAll
    //     ELSE 
    //      DT.FCXsdQtyAll - DOC.QTYUse
    //    END AS FCXsdQtyAll,
    //                 PDT.FCPdtCostStd * DT.FCXsdQty AS FCXtdSetPrice,
    //                 0 AS FCXsdAmtB4DisChg,
    //                 '' AS FTXsdDisChgTxt,
    //                 0 as FCXsdQtyLef,
    //                 0 as FCXsdQtyRfn,
    //                 '' as FTXsdStaPrcStk,
    //                 PDT.FTPdtStaAlwDis,
    //                 0 as FNXsdPdtLevel,
    //                 '' as FTXsdPdtParent,
    //                 0 as FCXsdQtySet,
    //                 '' as FTPdtStaSet,
    //                 '' as FTXsdRmk,   
    //                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
    //                 CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
    //                 CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
    //                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
    //                 CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
    //                 DT.FTWahCode,
    //                 CASE 
    //                 WHEN ISNULL(DOC.QTYUse,0) = 0 THEN DT.FCXsdQty
    //                 ELSE 
    //                  DT.FCXsdQty - DOC.QTYUse
    //                END AS FCXtdQtyOrd ,
    //                 '$tAgnCode' as FTAgnCode
    //             FROM
    //             TSVTJob2OrdDT DT WITH (NOLOCK)
    //                 LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
    //                 LEFT JOIN (
    //                     SELECT SUM(A.FCXtdQtyOrd) AS QTYUSE , A.* FROM (
    //                      SELECT HD.FTXthDocNo , REF.FTXthRefDocNo AS REFJOB2 , DT.FTPdtCode , DT.FCXtdQtyOrd FROM TCNTPdtPickHD HD 
    //                      LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
    //                      LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo AND REF.FTXthRefKey = 'Job2Ord' AND REF.FTXthRefType = 1   WHERE HD.FTXthStaApv = 1
    //                     ) AS A GROUP BY A.FTXthDocNo , A.REFJOB2 , A.FTPdtCode , A.FCXtdQtyOrd
    //                    ) DOC ON DOC.REFJOB2 = DT.FTXshDocNo AND DOC.FTPdtCode = DT.FTPdtCode
    //                 WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";

    //             $oQuery = $this->db->query($tSQL);
    //         }
    //     }
    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMPCKCallRefIntDocInsertDTToTemp($paData)
    {

        $tPCKDocNo        = $paData['tPCKDocNo'];
        $tPCKFrmBchCode   = $paData['tPCKFrmBchCode'];
        $tAgnCode = $paData['tPCKAgnCode'];
        $tSessionID                  = $this->session->userdata('tSesSessionID');
        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';
        $ptDocType = 'Job2Ord';
        $tPCKOptionAddPdt = $paData['tPCKOptAddPdt'];

        //ลบรายการสินค้า
        $this->db->where('FTXthDocNo', $tPCKDocNo);
        $this->db->where('FTSessionID', $paData['tSessionID']);
        $this->db->delete('TCNTDocDTTmp');


        //         $tSQL = "INSERT INTO TCNTDocDTTmp (
        //              FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
        //              FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
        //              FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
        //              FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
        //              FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
        //              FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
        //              FTXtdPdtStaSet,FTXtdRmk,
        //              FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTWahCode,
        //              FCXtdQtyOrd,FTAgnCode
        //          )
        //          SELECT  '$tPCKFrmBchCode' AS FTBchCode, 
        //              '' AS FTXshDocNo, 
        //              ROW_NUMBER() OVER(
        //              ORDER BY DT.FNXsdSeqNo DESC) AS FNXtdSeqNo, 
        //              'TCNTPdtPickHD' AS FTXthDocKey, 
        //              DT.FTPdtCode, 
        //              DT.FTXsdPdtName, 
        //              DT.FTPunCode, 
        //              DT.FTPunName, 
        //              DT.FCXsdFactor, 
        //              DT.FTXsdBarCode, 
        //              '' AS FTSrnCode, 
        //              PDT.FTPdtStaVatBuy, 
        //              PDT.FTVatCode AS FTVatCode, 
        //              DT.FCXsdVatRate, 
        //              PDT.FTPdtSaleType AS FTXtdSaleType, 
        //              PDT.FCPdtCostStd AS FCXtdSalePrice,
        //              CASE
        //                  WHEN ISNULL(DOC.QTYUse, 0) = 0
        //                  THEN DT.FCXsdQty
        //                  ELSE DT.FCXsdQty - DOC.QTYUse
        //              END AS FCXsdQty,
        //              CASE
        //                  WHEN ISNULL(DOC.QTYUse, 0) = 0
        //                  THEN DT.FCXsdQtyAll
        //                  ELSE DT.FCXsdQtyAll - DOC.QTYUse
        //              END AS FCXsdQtyAll, 
        //              PDT.FCPdtCostStd * DT.FCXsdQty AS FCXtdSetPrice, 
        //              0 AS FCXsdAmtB4DisChg, 
        //              '' AS FTXsdDisChgTxt, 
        //              0 AS FCXsdQtyLef, 
        //              0 AS FCXsdQtyRfn, 
        //              '' AS FTXsdStaPrcStk, 
        //              PDT.FTPdtStaAlwDis, 
        //              0 AS FNXsdPdtLevel, 
        //              '' AS FTXsdPdtParent, 
        //              0 AS FCXsdQtySet, 
        //              '' AS FTPdtStaSet, 
        //              '' AS FTXsdRmk, 
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
        //             CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
        //             CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy, 
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy, 
        //              DT.FTWahCode,
        //              CASE
        //                  WHEN ISNULL(DOC.QTYUse, 0) = 0
        //                  THEN DT.FCXsdQty
        //                  ELSE DT.FCXsdQty - DOC.QTYUse
        //              END AS FCXtdQtyOrd, 
        //              '$tAgnCode' AS FTAgnCode
        //       FROM TSVTJob2OrdDT DT WITH(NOLOCK)
        //            LEFT JOIN TCNMPdt PDT WITH(NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
        //            LEFT JOIN
        //       (
        //         SELECT
        //   SUM (A.FCXtdQtyOrd) AS QTYUSE,A.FTPdtCode , A.REFJOB2
        //  FROM
        //   (
        //    SELECT
        //     REF.FTXthRefDocNo AS REFJOB2,
        //     DT.FTPdtCode,
        //     DT.FCXtdQtyOrd
        //    FROM
        //     TCNTPdtPickHD HD
        //    LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
        //    LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
        //    AND REF.FTXthRefKey = 'Job2Ord'
        //    AND REF.FTXthRefType = 1
        //    WHERE
        //     HD.FTXthStaApv = 1
        //    AND REF.FTXthRefDocNo = '$tRefIntDocNo'
        //    AND HD.FNXthDocType = '12'
        //   ) AS A
        //  GROUP BY
        //   A.REFJOB2,
        //   A.FTPdtCode
        //       ) DOC ON DOC.REFJOB2 = DT.FTXshDocNo
        //                AND DOC.FTPdtCode = DT.FTPdtCode
        //       WHERE DT.FTBchCode = '$tRefIntBchCode'
        //             AND DT.FTXshDocNo = '$tRefIntDocNo'
        //             AND DT.FNXsdSeqNo IN $aSeqNo
        //            AND DT.FTPdtStaSet NOT IN('2', '5')
        //       UNION ALL
        //       SELECT '$tPCKFrmBchCode' AS FTBchCode, 
        //              '' AS FTXshDocNo, 
        //              ROW_NUMBER() OVER(
        //              ORDER BY DTSET.FNXsdSeqNo DESC) AS FNXtdSeqNo, 
        //              'TCNTPdtPickHD' AS FTXthDocKey, 
        //              DTSET.FTPdtCode, 
        //              DTSET.FTXsdPdtName, 
        //              DTSET.FTPunCode, 
        //              UNIL.FTPunName, 
        //              PACK.FCPdtUnitFact AS FCXsdFactor, 
        //              '' AS FTXsdBarCode, 
        //              '' AS FTSrnCode, 
        //              PDT.FTPdtStaVatBuy, 
        //              PDT.FTVatCode AS FTVatCode, 
        //              0 AS FCXsdVatRate, 
        //              PDT.FTPdtSaleType AS FTXtdSaleType, 
        //              PDT.FCPdtCostStd AS FCXtdSalePrice, 
        //              CASE
        //              WHEN ISNULL(DOC.QTYUse, 0) = 0
        //              THEN DTSET.FCXsdQtySet
        //              ELSE DTSET.FCXsdQtySet - DOC.QTYUse
        //          END AS FCXsdQty,
        //          CASE
        //              WHEN ISNULL(DOC.QTYUse, 0) = 0
        //              THEN DTSET.FCXsdQtySet
        //              ELSE DTSET.FCXsdQtySet - DOC.QTYUse
        //          END AS FCXsdQtyAll,
        //              PDT.FCPdtCostStd * DTSET.FCXsdQtySet AS FCXtdSetPrice, 
        //              0 AS FCXsdAmtB4DisChg, 
        //              '' AS FTXsdDisChgTxt, 
        //              0 AS FCXsdQtyLef, 
        //              0 AS FCXsdQtyRfn, 
        //              '' AS FTXsdStaPrcStk, 
        //              PDT.FTPdtStaAlwDis, 
        //              0 AS FNXsdPdtLevel, 
        //              '' AS FTXsdPdtParent, 
        //              0 AS FCXsdQtySet, 
        //              '' AS FTPdtStaSet, 
        //              '' AS FTXsdRmk, 
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
        //              CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
        //              CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "')AS FTLastUpdBy, 
        //              CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy, 
        //              '' AS FTWahCode, 
        //              CASE
        //              WHEN ISNULL(DOC.QTYUse, 0) = 0
        //              THEN DTSET.FCXsdQtySet
        //              ELSE DTSET.FCXsdQtySet - DOC.QTYUse
        //          END AS FCXtdQtyOrd, 
        //              '$tAgnCode' AS FTAgnCode
        //       FROM TSVTJob2OrdDTSet DTSET
        //            LEFT JOIN TCNMPdtUnit_L UNIL ON DTSET.FTPunCode = UNIL.FTPunCode
        //                                            AND UNIL.FNLngID = 1
        //            LEFT JOIN TCNMPdtPackSize PACK ON DTSET.FTPdtCode = PACK.FTPdtCode
        //                                              AND UNIL.FTPunCode = PACK.FTPunCode
        //            LEFT JOIN TCNMPdt PDT WITH(NOLOCK) ON DTSET.FTPdtCode = PDT.FTPdtCode
        //            LEFT JOIN
        //            (

        //                         SELECT
        //                         SUM (A.FCXtdQtyOrd) AS QTYUSE,A.FTPdtCode , A.REFJOB2
        //                        FROM
        //                         (
        //                          SELECT
        //                           REF.FTXthRefDocNo AS REFJOB2,
        //                           DT.FTPdtCode,
        //                           DT.FCXtdQtyOrd
        //                          FROM
        //                           TCNTPdtPickHD HD
        //                          LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
        //                          LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
        //                          AND REF.FTXthRefKey = 'Job2Ord'
        //                          AND REF.FTXthRefType = 1
        //                          WHERE
        //                           HD.FTXthStaApv = 1
        //                          AND REF.FTXthRefDocNo = '$tRefIntDocNo'
        //                          AND HD.FNXthDocType = '12'
        //                         ) AS A
        //                        GROUP BY
        //                         A.REFJOB2,
        //                         A.FTPdtCode

        //            ) DOC ON DOC.REFJOB2 = DTSET.FTXshDocNo
        //                     AND DOC.FTPdtCode = DTSET.FTPdtCode
        //       WHERE DTSET.FTBchCode = '$tRefIntBchCode'
        //             AND DTSET.FTXshDocNo = '$tRefIntDocNo'
        //             AND DTSET.FTPsvType != 2
        //             AND DTSET.FNXsdSeqNo IN $aSeqNo";


        $tSQL = "INSERT INTO TCNTDocDTTmp (
    FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
    FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
    FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
    FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
    FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
    FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
    FTXtdPdtStaSet,FTXtdRmk,
    FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTWahCode,
    FCXtdQtyOrd,FTAgnCode
)
SELECT
'$tPCKFrmBchCode' AS FTBchCode, 
 '' AS FTXshDocNo, 
 ROW_NUMBER() OVER(
              ORDER BY DTJORD.FTPdtStaSet,DTJORD.FNXsdSeqNo DESC) AS FNXtdSeqNo, 
'TCNTPdtPickHD' AS FTXthDocKey,
 DTJORD.FTPdtCode, 
 DTJORD.FTXsdPdtName, 
 DTJORD.FTPunCode, 
 DTJORD.FTPunName, 
  DTJORD.FCXsdFactor, 
  DTJORD.FTXsdBarCode, 
  '' AS FTSrnCode, 
 '' AS FTXtdVatType, 
 '' AS FTVatCode, 
  0 AS FCXtdVatRate, 
  '' AS FTXtdSaleType, 
  0 AS FCXtdSalePrice, 
  CASE
                  WHEN ISNULL(DTPL.QTYUse, 0) = 0
                 THEN DTJORD.FCXsdQtyAll
                  ELSE DTJORD.FCXsdQtyAll - DTPL.QTYUse
                  END AS FCXsdQty, 
			                        CASE
                  WHEN ISNULL(DTPL.QTYUse, 0) = 0
                 THEN DTJORD.FCXsdQtyAll
                  ELSE DTJORD.FCXsdQtyAll - DTPL.QTYUse
                  END AS FCXsdQtyAll, 
 0 AS FCXtdSetPrice, 
 0 AS FCXtdAmtB4DisChg, 
 '' AS FTXtdDisChgTxt, 
 ISNULL(DTJORD.FCXsdQtyAll, 0) - ISNULL(DTPL.QTYUSE, 0) AS FCXtdQtyLeft,
   0 AS FCXsdQtyRfn, 
              '' AS FTXsdStaPrcStk, 
              '' AS FTPdtStaAlwDis, 
              0 AS FNXsdPdtLevel, 
              '' AS FTXsdPdtParent, 
              0 AS FCXsdQtySet, 
              '' AS FTPdtStaSet, 
              '' AS FTXsdRmk, 
                       CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
            CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
              CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
             CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "')AS FTLastUpdBy, 
             CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy, 
              '' AS FTWahCode,
                                CASE
              WHEN ISNULL(DTPL.QTYUse, 0) = 0
             THEN DTJORD.FCXsdQtyAll
              ELSE DTJORD.FCXsdQtyAll - DTPL.QTYUse
              END AS FCXtdQtyOrd, 
 '' AS FTAgnCode
FROM
(
    SELECT DT.FTBchCode, 
           DT.FTXshDocNo, 
           CONCAT(DT.FNXsdSeqNo,0) AS FNXsdSeqNo,
           DT.FTPdtCode, 
           DT.FTXsdPdtName, 
           DT.FTPunCode, 
           DT.FTPunName, 
           DT.FTXsdBarCode, 
           DT.FCXsdQtyAll, 
           DT.FTPdtStaSet,
		    DT.FCXsdFactor AS FCXsdFactor
    FROM TSVTJob2OrdDT DT WITH(NOLOCK)
    WHERE FTXshDocNo = '$tRefIntDocNo'
    UNION ALL
    SELECT DT.FTBchCode, 
           DT.FTXshDocNo, 
           CONCAT(DT.FNXsdSeqNo,DT.FNPstSeqNo) AS FNXsdSeqNo,
           DT.FTPdtCode, 
           DT.FTXsdPdtName, 
           DT.FTPunCode, 
           '' AS FTPunName, 
           '' AS FTXsdBarCode, 
           FCXsdQtySet AS FCXsdQtyAll, 
           '' AS FTPdtStaSet,
		    PACK.FCPdtUnitFact AS FCXsdFactor
    FROM TSVTJob2OrdDTSET DT WITH(NOLOCK)
	            LEFT JOIN TCNMPdtUnit_L UNIL ON DT.FTPunCode = UNIL.FTPunCode
                                            AND UNIL.FNLngID = 1
            LEFT JOIN TCNMPdtPackSize PACK ON DT.FTPdtCode = PACK.FTPdtCode
                                              AND UNIL.FTPunCode = PACK.FTPunCode
    WHERE FTXshDocNo = '$tRefIntDocNo'
) DTJORD
LEFT JOIN
(
    SELECT REF.FTXthRefDocNo AS REFJOB2, 
           DT.FTPdtCode, 
           SUM(DT.FCXtdQtyOrd) AS QTYUSE
    FROM TCNTPdtPickHD HD
         LEFT JOIN TCNTPdtPickDT DT ON HD.FTXthDocNo = DT.FTXthDocNo
         LEFT JOIN TCNTPdtPickHDDocRef REF ON HD.FTXthDocNo = REF.FTXthDocNo
                                              AND REF.FTXthRefKey = 'Job2Ord'
                                              AND REF.FTXthRefType = 1
    WHERE HD.FTXthStaApv = 1
          AND REF.FTXthRefDocNo = '$tRefIntDocNo'
          AND HD.FNXthDocType = '12'
    GROUP BY REF.FTXthRefDocNo, 
             DT.FTPdtCode
) DTPL ON DTJORD.FTXshDocNo = DTPL.REFJOB2
          AND DTJORD.FTPdtCode = DTPL.FTPdtCode
		  WHERE DTJORD.FTPdtStaSet!= 5 AND DTJORD.FNXsdSeqNo IN $aSeqNo
ORDER BY DTJORD.FTPdtStaSet

  ";

        $oQuery = $this->db->query($tSQL);
    }


    public function FSnMPCKCheckTempDocType($paData)
    {

        $tSQL   = "SELECT
                        Tmp.FTXthRefKey
                    FROM TCNTDocHDRefTmp Tmp WITH(NOLOCK)
                    WHERE Tmp.FTXthDocNo = '" . $paData['tPCKDocNo'] . "' 
                    AND Tmp.FTXthDocKey = " . $this->db->escape($paData['tPCKDocKey']) . "
                    AND Tmp.FTSessionID = " . $this->db->escape($paData['tSessionID']) . "
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'raItems'       => '',
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    public function FSoMPCKGetCstCode($paData)
    {
        $tRefIntDocNo = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $tSQL       = " SELECT
    FTCstCode FROM TSVTJob2OrdHD WHERE FTXshDocNo = '$tRefIntDocNo'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
            $tCstCode = $aItem[0]['FTCstCode'];
        } else {
            $aItem = array();
            $tCstCode = '';
        }
        // $jResult = json_encode($aItem);
        // $aResult = json_decode($jResult, true);
        return $tCstCode;
    }

    public function FSoMPCKGetAddressCustmer($paData)
    {
        $tRefIntDocNo = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $tSQL       = " 
        SELECT DISTINCT 
                        CSTL.FTCstCode, 
                        CSTL.FTCstName, 
                        CST.FTCstTaxNo, 
                        CST.FTCstTel, 
                        CST.FTCstEmail, 
                        ADDL.FTAddV2Desc1, 
                        CAR.FTCarRegNo, 
                        CAR.FTBndName
                 FROM TSVTJob2OrdHD JOB1 WITH(NOLOCK)
                      LEFT JOIN TSVTJob2OrdHDCst HDCst WITH(NOLOCK) ON JOB1.FTXshDocNo = HDCst.FTXshDocNo
                                                                       AND JOB1.FTBchCode = HDCst.FTBchCode
                      LEFT JOIN TLKMCar CAR WITH(NOLOCK) ON CAR.FTCarCode = HDCst.FTCarCode
                      LEFT JOIN TCNMCst CST WITH(NOLOCK) ON JOB1.FTCstCode = CST.FTCstCode
                      LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON JOB1.FTBchCode = BCHL.FTBchCode
                                                                  AND BCHL.FNLngID = 1
                      LEFT JOIN TCNMUser_L USRL WITH(NOLOCK) ON JOB1.FTCreateBy = USRL.FTUsrCode
                                                                AND USRL.FNLngID = 1
                      LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON JOB1.FTCstCode = CSTL.FTCstCode
                                                               AND CSTL.FNLngID = 1
                      LEFT JOIN TCNMCstAddress_L ADDL WITH(NOLOCK) ON JOB1.FTCstCode = ADDL.FTCstCode
                                                                      AND ADDL.FNLngID = 1
                      LEFT JOIN TCNTDocHDRefTmp HDREF WITH(NOLOCK) ON HDREF.FTXthRefDocNo = JOB1.FTXshDocNo
                 WHERE 
                        JOB1.FTXshDocNo = '$tRefIntDocNo'";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        // $jResult = json_encode($aItem);
        // $aResult = json_decode($jResult, true);
        return $aItem;
    }


    public function FSoMPCKGetCstName($ptCstCode)
    {
        $tSQL       = " 	SELECT FTCstName FROM TCNMCst_L WHERE FTCstCode = '$ptCstCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
            $tCstName = $aItem[0]['FTCstName'];
        } else {
            $aItem = array();
            $tCstName = '';
        }

        return $tCstName;
    }

    public function FSoMPCKGetDocRef($tDocNo)
    {

        $tSQL       = " SELECT
    FTXthRefDocNo AS tRefIntDocNo,FTBchCode AS tRefIntBchCode FROM TCNTPdtPickHDDocRef WHERE FTXthDocNo = '$tDocNo' ";



        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        // $jResult = json_encode($aItem);
        // $aResult = json_decode($jResult, true);
        return $aItem;
    }



    public function FSaMPCKGetPdtInTmpForSendToAPI($paData)
    {

        $tBchCode   = $paData['FTBchCode'];
        $tDocCode   = $paData['FTXshDocNo'];
        $tDocKey    = $paData['FTXthDocKey'];
        $tSessionID = $paData['FTSessionID'];

        $tSQL       = " SELECT
                            TMP.FTPdtCode           AS ptPdtCode,
                            TMP.FTBchCode           AS ptBchCode,
                            BCH.FTWahCode			AS ptWahCode,
                            TMP.FCXtdQty            AS pcQty
                        FROM TCNTPdtTboDT          TMP WITH(NOLOCK)
                        INNER JOIN TCNMPdt         PDT WITH(NOLOCK) ON TMP.FTPdtCode = PDT.FTPdtCode
                        LEFT JOIN TCNMBranch      BCH WITH(NOLOCK) ON TMP.FTBchCode = BCH.FTBchCode
                        WHERE TMP.FTXthDocNo = '$tDocCode'
                          AND TMP.FTBchCode = '$tBchCode'
                          AND PDT.FTPdtStkControl = '1'
                          AND ISNULL(TMP.FTXtdStaPrcStk,'') != '1'
                      ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    public function FSxMPCKChkConfig()
    {

        $tSQL       = " SELECT FTSysStaUsrValue from TSysConfig where FTSysCode = 'tDoc_ChkStkTranfer'";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItem = $oQuery->result_array();
        } else {
            $aItem = array();
        }
        $jResult = json_encode($aItem);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Get ข้อมูล API
    public function FSaMPCKGetConfigAPI()
    {
        $tSQL       = "SELECT TOP 1 * FROM TCNTUrlObject WHERE FTUrlKey = 'CHKSTK' AND FTUrlTable = 'TCNMComp' AND FTUrlRefID = 'CENTER' ORDER BY FNUrlSeq ASC";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => '',
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    public function FSxMTransferBchUpdatePdtStkPrc($paDataWhere, $paHavePdtInWah)
    {
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocCode   = $paDataWhere['FTXshDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];

        $this->db->set('FTXtdRmk', '1');
        $this->db->where_in('FTPdtCode', $paHavePdtInWah);
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXthDocNo', $tDocCode);
        $this->db->update('TCNTPdtTboDT');


        return $this->db->last_query();
    }

    public function FSxMPCKUpdatePdtStkPrcAll($paDataWhere, $ptStatus)
    {
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocCode   = $paDataWhere['FTXshDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];

        if ($ptStatus == '1') {
            $this->db->set('FTXtdRmk', $ptStatus);
        } else {
            $this->db->set('FTXtdRmk', '');
        }
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXthDocNo', $tDocCode);
        $this->db->update('TCNTPdtPickDT');

        return $this->db->last_query();
    }




    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMPCKGetDataHDRefTmp($paData)
    {

        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTXshDocKey    = $paData['FTXshDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
                    FROM $tTableTmpHDRef
                    WHERE FTXthDocNo  = '$FTXshDocNo'
                      AND FTXthDocKey = '$FTXshDocKey'
                      AND FTSessionID = '$FTSessionID' ";
        //   print_r($tSQL); die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'aItems'   => $oQuery->result_array(),
                'tCode'    => '1',
                'tDesc'    => 'found data',
            );
        } else {
            $aResult    = array(
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    // แท็บค่าอ้างอิงเอกสาร - เพิ่ม
    public function FSaMPCKAddEditHDRefTmp($paDataWhere, $paDataAddEdit)
    {



        $tRefDocNo  = (empty($paDataWhere['tPCKRefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tPCKRefDocNoOld']);
        $tSQL       = " SELECT FTXthRefDocNo FROM TCNTDocHDRefTmp
                        WHERE FTXthDocNo    = '" . $paDataWhere['FTXshDocNo'] . "'
                            AND FTXthDocKey   = '" . $paDataWhere['FTXshDocKey'] . "'
                            AND FTSessionID   = '" . $paDataWhere['FTSessionID'] . "'
                            AND FTXthRefDocNo = '" . $tRefDocNo . "' ";
        $oQuery     = $this->db->query($tSQL);
        $this->db->trans_begin();
        if ($oQuery->num_rows() > 0) {
            $this->db->where('FTXthRefDocNo', $tRefDocNo);
            $this->db->where('FTXthDocNo', $paDataWhere['FTXshDocNo']);
            $this->db->where('FTXthDocKey', $paDataWhere['FTXshDocKey']);
            $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
            $this->db->update('TCNTDocHDRefTmp', $paDataAddEdit);
        } else {
            $aDataAdd = array_merge($paDataAddEdit, array(
                'FTXthDocNo'  => $paDataWhere['FTXshDocNo'],
                'FTXthDocKey' => $paDataWhere['FTXshDocKey'],
                'FTSessionID' => $paDataWhere['FTSessionID'],
                'FDCreateOn'  => $paDataWhere['FDCreateOn'],
            ));
            $this->db->insert('TCNTDocHDRefTmp', $aDataAdd);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => 'Add/Edit HDDocRef Error'
            );
        } else {
            $this->db->trans_commit();
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Add/Edit HDDocRef Success'
            );
        }
        return $aResult;
    }

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMPCKDelHDDocRef($paData)
    {
        $tPCKDocNo       = $paData['FTXshDocNo'];
        $tPCKRefDocNo    = $paData['FTXshRefDocNo'];
        $tPCKDocKey      = $paData['FTXshDocKey'];
        $tPCKSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID', $tPCKSessionID);
        $this->db->where('FTXthDocKey', $tPCKDocKey);
        $this->db->where('FTXthRefDocNo', $tPCKRefDocNo);
        $this->db->where('FTXthDocNo', $tPCKDocNo);
        $this->db->delete('TCNTDocHDRefTmp');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => 'Delete HD Doc Ref Error'
            );
        } else {
            $this->db->trans_commit();
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Delete HD Doc Ref Success'
            );
        }
        return $aResult;
    }



    //ข้อมูล HDDocRef
    public function FSxMPCKMoveHDRefToHDRefTemp($paData)
    {

        $tDocNo         = $paData['FTXthDocNo'];
        $tSessionID     = $this->session->userdata('tSesSessionID');

        $tSQL = "   INSERT INTO TCNTDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                                FTXthDocNo,
                                FTXthRefDocNo,
                                FTXthRefType,
                                FTXthRefKey,
                                FDXthRefDocDate,
                                'TCNTPdtPickHD'      AS FTXthDocKey,
                                '$tSessionID'   AS FTSessionID,
                                CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn
                            FROM TCNTPdtPickHDDocRef WITH(NOLOCK)
                            WHERE FTXthDocNo = '$tDocNo' ";
        $this->db->query($tSQL);
    }


    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMPCKMoveHDRefTmpToHDRef($paDataWhere)
    {
        // print_r($paDataWhere); die();
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        $tTableHD     = $paDataWhere['tTableHD'];




        // [ใบหยิบสินค้า]
        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where('FTBchCode', $tBchCode);
            $this->db->where('FTXthDocNo', $tDocNo);
            $this->db->delete('TCNTPdtPickHDDocRef');
        }
        $tSQL   =   "   INSERT INTO TCNTPdtPickHDDocRef (FTAgnCode, FTBchCode, FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate) ";
        $tSQL   .=  "   SELECT
                                '$tAgnCode' AS FTAgnCode,
                                '$tBchCode' AS FTBchCode,
                                FTXthDocNo,
                                FTXthRefDocNo,
                                FTXthRefType,
                                FTXthRefKey,
                                FDXthRefDocDate
                            FROM TCNTDocHDRefTmp WITH (NOLOCK)
                            WHERE FTXthDocNo  = '$tDocNo'
                              AND FTXthDocKey = '$tTableHD'
                              AND FTSessionID = '$tSessionID' ";
        $this->db->query($tSQL);
        // print_r($tSQL);
        // die();

        //Insert ใบสั่งงาน [PO]
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXshRefDocNo', $tDocNo);
        $this->db->delete('TSVTJob2OrdHDDocRef');
        $tSQL   =   "   INSERT INTO TSVTJob2OrdHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                                '$tAgnCode' AS FTAgnCode,
                                '$tBchCode' AS FTBchCode,
                                FTXthRefDocNo AS FTXshDocNo,
                                FTXthDocNo AS FTXshRefDocNo,
                                2,
                                'PDTPICK',
                                FDXthRefDocDate
                            FROM TCNTDocHDRefTmp WITH (NOLOCK)
                            WHERE FTXthDocNo  = '$tDocNo'
                              AND FTXthDocKey = '$tTableHD'
                              AND FTSessionID = '$tSessionID'
                              AND FTXthRefKey = 'Job2Ord'  ";
        $this->db->query($tSQL);
    }


    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDRefTmp
    public function FSxMPCKAddUpdateDocNoToTemp($paDataWhere)
    {
        $tSessionID   = $this->session->userdata('tSesSessionID');
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', 'TCNTPdtPickHD');
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into TCNTDocHDRefTmp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTXthDocKey', 'TCNTPdtPickHD');
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->update('TCNTDocHDRefTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));
        return;
    }

    // Update Document DT Temp by Seq
    public function FSaMPCKUpdateInlineDTTemp($paDataUpdateDT, $paDataWhere)
    {
        $this->db->where_in('FTSessionID', $paDataWhere['tPCKSessionID']);
        $this->db->where_in('FTXthDocKey', $paDataWhere['tDocKey']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['nPCKSeqNo']);

        if ($paDataWhere['tPCKDocNo'] != '' && $paDataWhere['tPCKBchCode'] != '') {
            $this->db->where_in('FTXthDocNo', $paDataWhere['tPCKDocNo']);
            $this->db->where_in('FTBchCode', $paDataWhere['tPCKBchCode']);
        }

        $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Update Success',
            );
        } else {
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Update Fail',
            );
        }

        return $aStatus;
    }


    //Delete Product Single Item In Doc DT Temp
    public function FSnMPCKDelPdtInDTTmp($paDataWhere)
    {
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID', $paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo', $paDataWhere['tDODocNo']);
        $this->db->where_in('FTXthDocKey', $paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode', $paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMPCKDelMultiPdtInDTTmp($paDataWhere)
    {
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID', $paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo', $paDataWhere['tDODocNo']);
        $this->db->where_in('FTXthDocKey', $paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode', $paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return;
    }
}
