<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mTransferBchOut extends CI_Model
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
        $nLngID     = $paParams['FNLngID'];
        $tSQL       = "
                SELECT TOP ". get_cookie('nShowRecordInPageList')." c.* FROM(
                SELECT  --ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS FNRowID,
                * FROM
                    (SELECT DISTINCT
                        HD.*,
                        BCHL.FTBchName,
                        USRL.FTUsrName AS FTCreateByName,
                        USRLAPV.FTUsrName AS FTXthApvName
                    FROM TCNTPdtTboHD HD WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON HD.FTXthApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                    WHERE HD.FDCreateOn <> ''
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
                    (HD.FTXthDocNo COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (BCHL.FTBchName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (USRL.FTUsrName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (USRLAPV.FTUsrName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                )
            ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= "
                AND (
                    (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)."  AND ".$this->db->escape($tSearchBchCodeTo).")
                    OR (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= "
                AND ((HD.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59")."))
                OR (HD.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 23:59:59").")))
            ";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND HD.FTXthStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(HD.FTXthStaApv,'') = '' AND HD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND HD.FTXthStaApv = ".$this->db->escape($tSearchStaDoc)." ";
            }
        }

        // ค้นหาสถานะประมวลผล
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL   .= " AND (HD.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." OR ISNULL(HD.FTXthStaPrcStk,'') = '') ";
            } else {
                $tSQL   .= " AND HD.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." ";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL   .= " AND HD.FNXthStaDocAct = 1";
            } else {
                $tSQL   .= " AND HD.FNXthStaDocAct = 0";
            }
        }

        $tSQL .= ") Base) AS c ORDER BY c.FDCreateOn DESC ";
        // $tSQL .= ") Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])." ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $nFoundRow  = 0;
            $nPageAll   = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
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
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        unset($nLngID);
        unset($tSQL);
        unset($tBchCode);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom);
        unset($tSearchBchCodeTo);
        unset($tSearchDocDateFrom);
        unset($tSearchDocDateTo);
        unset($tSearchStaDoc);
        unset($tSearchStaPrcStk);
        unset($tSearchStaDocAct);
        unset($oQuery);
        unset($oList);
        unset($nFoundRow);
        unset($nPageAll);
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
            SELECT HD.FTXthDocNo
                
            FROM TCNTPdtTboHD HD WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON HD.FTXthApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
            WHERE HD.FDCreateOn <> ''
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
                    (HD.FTXthDocNo COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (BCHL.FTBchName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (USRL.FTUsrName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                    OR (USRLAPV.FTUsrName COLLATE THAI_BIN LIKE '%".$tSearchLists."%')
                )
            ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= "
                AND (
                    (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).")
                    OR (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= "
                AND (
                    (HD.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59").")
                ) OR
                    (HD.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 23:59:59")."))
                )
            ";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 2) {
                $tSQL   .= " AND HD.FTXthStaDoc = ".$this->db->escape($tSearchStaDoc)." OR HD.FTXthStaDoc = ''";
            } else {
                $tSQL   .= " AND HD.FTXthStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
            }
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND HD.FTXthStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(HD.FTXthStaApv,'') = '' AND HD.FTXthStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND HD.FTXthStaApv = ".$this->db->escape($tSearchStaDoc)." ";
            }
        }

        // ค้นหาสถานะประมวลผล
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL   .= " AND (HD.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." OR ISNULL(HD.FTXthStaPrcStk,'') = '') ";
            } else {
                $tSQL   .= " AND HD.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." ";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXthStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXthStaDocAct = 0";
            }
        }

        unset($nLngID);
        unset($tSQL);
        unset($tBchCode);
        unset($aAdvanceSearch);
        unset($tSearchList);
        unset($tSearchBchCodeFrom);
        unset($tSearchBchCodeTo);
        unset($tSearchDocDateFrom);
        unset($tSearchDocDateTo);
        unset($tSearchStaDoc);
        unset($tSearchStaPrcStk);
        unset($tSearchStaDocAct);

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
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
            WHERE FTUsrCode = ".$this->db->escape($tUsrLogin)."
        ";

        $aResult = [];

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array();
        }
        unset($nLngID);
        unset($tUsrLogin);
        unset($tSQL);
        unset($oQuery);
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
    public function FSaMGetHD($paParams = [])
    {
        $tDocNo = $paParams['tDocNo'];
        $nLngID = $paParams['nLngID'];

        $tSQL = "
            SELECT
                HD.*,
                HDREF.*,
                SHIPVIAL.FTViaName,
                BCHL.FTBchName,
                CONVERT(CHAR(5), HD.FDXthDocDate, 108)  AS FTXthDocTime,
                USRAPV.FTUsrName AS FTXthApvName,
                USRL.FTUsrName AS FTCreateByName,
                RSNL.FTRsnName,
                HDREFDOC.FTXshRefDocNo AS FTXthRefInt,
                HDREFDOCEX.FTXshRefDocNo AS FTXthRefExt,
                HDREFDOC.FDXshRefDocDate AS FDXthRefIntDate,
                HDREFDOCEX.FDXshRefDocDate AS FDXthRefExtDate,
                HDDOCREF.FTXshRefType,
                CONVERT(CHAR(5), HDREFDOC.FDXshRefDocDate,108) AS FDXthRefIntTime,
                /*===== From ===========*/
                BCHLF.FTBchName AS FTXthBchFrmName,
                MCHLF.FTMerName AS FTXthMerchantFrmName,
                SHPLF.FTShpName AS FTXthShopFrmName,
                WAHLF.FTWahName AS FTXthWhFrmName,
                /*===== To =============*/
                BCHLT.FTBchName AS FTXthBchToName,
                WAHLT.FTWahName AS FTXthWhToName
            FROM TCNTPdtTboHD HD WITH (NOLOCK)

            LEFT JOIN TCNTPdtTboHDRef HDREF WITH (NOLOCK) ON HDREF.FTXthDocNo = HD.FTXthDocNo AND HDREF.FTBchCode = HD.FTBchCode
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BCHL.FTBchCode = HD.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShipVia_L SHIPVIAL WITH (NOLOCK) ON SHIPVIAL.FTViaCode = HDREF.FTViaCode AND SHIPVIAL.FNLngID = ".$this->db->escape($nLngID)."

            LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON USRL.FTUsrCode = HD.FTCreateBy AND USRL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRAPV WITH (NOLOCK) ON USRAPV.FTUsrCode = HD.FTXthApvCode AND USRAPV.FNLngID = ".$this->db->escape($nLngID)."

            LEFT JOIN TCNMRsn_L RSNL WITH (NOLOCK) ON RSNL.FTRsnCode = HD.FTRsnCode AND RSNL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtTboHDDocRef    HDREFDOC WITH (NOLOCK) ON HDREFDOC.FTXshDocNo  = HD.FTXthDocNo AND HDREFDOC.FTXshRefType = '1'
            LEFT JOIN TCNTPdtReqBchHDDocRef HDDOCREF WITH (NOLOCK) ON HDDOCREF.FTXshRefDocNo  = HD.FTXthDocNo AND HDDOCREF.FTXshRefKey ='BS' AND HDDOCREF.FTXshRefType = '2'
            LEFT JOIN TCNTPdtTboHDDocRef    HDREFDOCEX WITH (NOLOCK) ON HDREFDOCEX.FTXshDocNo  = HD.FTXthDocNo AND HDREFDOCEX.FTXshRefType = '3'

            /*===== From =========================================*/
            LEFT JOIN TCNMBranch_L BCHLF WITH (NOLOCK) ON BCHLF.FTBchCode = HD.FTXthBchFrm AND BCHLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant_L MCHLF WITH (NOLOCK) ON MCHLF.FTMerCode = HD.FTXthMerchantFrm AND MCHLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop_L SHPLF WITH (NOLOCK) ON SHPLF.FTShpCode = HD.FTXthShopFrm AND SHPLF.FTBchCode = HD.FTXthBchFrm AND SHPLF.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHLF WITH (NOLOCK) ON  WAHLF.FTWahCode = HD.FTXthWhFrm AND WAHLF.FTBchCode = HD.FTXthBchFrm AND WAHLF.FNLngID = ".$this->db->escape($nLngID)."
            /*===== To ===========================================*/
            LEFT JOIN TCNMBranch_L BCHLT WITH (NOLOCK) ON BCHLT.FTBchCode = HD.FTXthBchTo AND BCHLT.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHLT WITH (NOLOCK) ON  WAHLT.FTWahCode = HD.FTXthWhTo AND WAHLT.FTBchCode = HD.FTXthBchTo AND WAHLT.FNLngID = ".$this->db->escape($nLngID)."
            WHERE 1=1
        ";

        if ($tDocNo != "") {
            $tSQL .= " AND HD.FTXthDocNo = '$tDocNo'";
        }

        // echo $tSQL;
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

        unset($tDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);

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
    public function FSaMDTToTemp($paParams = [])
    {
        $tDocNo = $paParams['tDocNo']; // เลขที่เอกสาร
        $tDocKey = $paParams['tDocKey']; // ชื่อตาราง HD
        $tBchCode = $paParams['tBchCode']; // สาขาที่ทำรายการ
        // $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID']; // User Session

        // ทำการลบ ใน DT Temp ก่อนการย้าย DT ไป DT Temp
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTSessionID', $tUserSessionID);
        $this->db->delete('TSVTTBODocDTTmp');

        $tSQL = "
            INSERT TSVTTBODocDTTmp
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
                FCXtdAmt,
                FCXtdVat,
                FCXtdVatable,
                FCXtdNet,
                FCXtdCostIn,
                FCXtdCostEx,
                FTXtdStaPrcStk,
                FNXtdPdtLevel,
                FTXtdPdtParent,
                FCXtdQtySet,
                FTXtdPdtStaSet,
                FTXtdRmk,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy,

                FTXthDocKey,
                FTSessionID)
        ";

        $tSQL .= "
            SELECT
                DT.FTBchCode,
                'TBODOCTEMP' AS FTXthDocNo,
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
                DT.FCXtdAmt,
                DT.FCXtdVat,
                DT.FCXtdVatable,
                DT.FCXtdNet,
                DT.FCXtdCostIn,
                DT.FCXtdCostEx,
                DT.FTXtdStaPrcStk,
                DT.FNXtdPdtLevel,
                DT.FTXtdPdtParent,
                DT.FCXtdQtySet,
                DT.FTXtdPdtStaSet,
                DT.FTXtdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy,

                '$tDocKey' AS FTXthDocKey,
                '$tUserSessionID' AS FTSessionID
            FROM TCNTPdtTboDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode  = ".$this->db->escape($tBchCode)."
            AND DT.FTXthDocNo   = ".$this->db->escape($tDocNo)."
            ORDER BY DT.FNXtdSeqNo ASC
        ";

        $this->db->query($tSQL);
        unset($tDocNo);
        unset($tDocKey);
        unset($tBchCode);
        unset($tUserSessionID);
        unset($tSQL);
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
        $tUserSessionID = $paParams['tUserSessionID']; // User Session
        $tUserLoginCode = $paParams['tUserLoginCode']; // User Login Code
        // $nLngID = $paParams['nLngID'];

        // ทำการลบ ใน DT Temp ก่อนการย้าย DT ไป DT Temp
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtTboDT');

        $tSQL = "
            INSERT TCNTPdtTboDT
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
                FCXtdAmt,
                FCXtdVat,
                FCXtdVatable,
                FCXtdNet,
                FCXtdCostIn,
                FCXtdCostEx,
                FTXtdStaPrcStk,
                FNXtdPdtLevel,
                FTXtdPdtParent,
                FCXtdQtySet,
                FTXtdPdtStaSet,
                FTXtdRmk,
                FDLastUpdOn,
                FTLastUpdBy,
                FDCreateOn,
                FTCreateBy)
        ";

        $tSQL .= "
            SELECT
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
                TMP.FCXtdAmt,
                TMP.FCXtdVat,
                TMP.FCXtdVatable,
                TMP.FCXtdNet,
                TMP.FCXtdCostIn,
                TMP.FCXtdCostEx,
                TMP.FTXtdStaPrcStk,
                TMP.FNXtdPdtLevel,
                TMP.FTXtdPdtParent,
                TMP.FCXtdQtySet,
                TMP.FTXtdPdtStaSet,
                TMP.FTXtdRmk,
                GETDATE() AS FDLastUpdOn,
                '$tUserLoginCode' AS FTLastUpdBy,
                GETDATE() AS FDCreateOn,
                '$tUserLoginCode' AS FTCreateBy
            FROM TSVTTBODocDTTmp TMP WITH(NOLOCK)
            WHERE TMP.FTBchCode = '$tBchCode'
            AND TMP.FTXthDocKey = '$tDocKey'
            AND TMP.FTSessionID = '$tUserSessionID'
            ORDER BY TMP.FNXtdSeqNo ASC
        ";

        $this->db->query($tSQL);

        // ทำการลบ ใน DT Temp หลังการย้าย DT Temp ไป DT
        $this->db->where('FTSessionID', $tUserSessionID);
        $this->db->delete('TSVTTBODocDTTmp');

        unset($tDocNo);
        unset($tDocKey);
        unset($tBchCode);
        unset($tUserSessionID);
        unset($tUserLoginCode);
        unset($tSQL);

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
            DELETE FROM TSVTTBODocDTTmp
            WHERE FTSessionID = '$tUserSessionID'
            AND FTXthDocKey = '$tDocKey'
        ";

        $this->db->query($tSQL);
        unset($tUserSessionID);
        unset($tDocKey);
        unset($tSQL);

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
            FROM TCNTPdtTboHD
            WHERE FTXthDocNo = '$ptDocNo'
        ";

        $bStatus = false;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $bStatus = true;
        }
        unset($tSQL);
        unset($oQuery);
        
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
        $this->db->set('FTBchCode', $paParams['FTBchCode']);
        $this->db->set('FDXthDocDate', $paParams['FDXthDocDate']);
        $this->db->set('FTXthVATInOrEx', $paParams['FTXthVATInOrEx']);
        $this->db->set('FTDptCode', $paParams['FTDptCode']);
        $this->db->set('FTXthBchFrm', $paParams['FTXthBchFrm']);
        $this->db->set('FTXthBchTo', $paParams['FTXthBchTo']);
        $this->db->set('FTXthMerchantFrm', $paParams['FTXthMerchantFrm']);
        $this->db->set('FTXthMerchantTo', $paParams['FTXthMerchantTo']);
        $this->db->set('FTXthShopFrm', $paParams['FTXthShopFrm']);
        $this->db->set('FTXthShopTo', $paParams['FTXthShopTo']);
        $this->db->set('FTXthWhFrm', $paParams['FTXthWhFrm']);
        $this->db->set('FTXthWhTo', $paParams['FTXthWhTo']);
        $this->db->set('FTUsrCode', $paParams['FTUsrCode']);
        $this->db->set('FTSpnCode', $paParams['FTSpnCode']);
        $this->db->set('FTXthApvCode', $paParams['FTXthApvCode']);
        $this->db->set('FNXthDocPrint', $paParams['FNXthDocPrint']);
        $this->db->set('FCXthTotal', $paParams['FCXthTotal']);
        $this->db->set('FCXthVat', $paParams['FCXthVat']);
        $this->db->set('FCXthVatable', $paParams['FCXthVatable']);
        $this->db->set('FTXthRmk', $paParams['FTXthRmk']);
        $this->db->set('FTXthStaDoc', $paParams['FTXthStaDoc']);
        $this->db->set('FTXthStaApv', $paParams['FTXthStaApv']);
        $this->db->set('FTXthStaPrcStk', $paParams['FTXthStaPrcStk']);
        $this->db->set('FTXthStaDelMQ', $paParams['FTXthStaDelMQ']);
        $this->db->set('FNXthStaDocAct', $paParams['FNXthStaDocAct']);
        $this->db->set('FNXthStaRef', $paParams['FNXthStaRef']);
        $this->db->set('FTRsnCode', $paParams['FTRsnCode']);
        $this->db->set('FDLastUpdOn', $paParams['FDLastUpdOn']);
        $this->db->set('FTLastUpdBy', $paParams['FTLastUpdBy']);
        $this->db->where('FTXthDocNo', $paParams['FTXthDocNo']);
        $this->db->update('TCNTPdtTboHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Master Success',
            );
        } else {
            // Add Master
            $this->db->set('FTBchCode', $paParams['FTBchCode']);
            $this->db->set('FTXthDocNo', $paParams['FTXthDocNo']);
            $this->db->set('FDXthDocDate', $paParams['FDXthDocDate']);
            $this->db->set('FTXthVATInOrEx', $paParams['FTXthVATInOrEx']);
            $this->db->set('FTDptCode', $paParams['FTDptCode']);
            $this->db->set('FTXthBchFrm', $paParams['FTXthBchFrm']);
            $this->db->set('FTXthBchTo', $paParams['FTXthBchTo']);
            $this->db->set('FTXthMerchantFrm', $paParams['FTXthMerchantFrm']);
            $this->db->set('FTXthMerchantTo', $paParams['FTXthMerchantTo']);
            $this->db->set('FTXthShopFrm', $paParams['FTXthShopFrm']);
            $this->db->set('FTXthShopTo', $paParams['FTXthShopTo']);
            $this->db->set('FTXthWhFrm', $paParams['FTXthWhFrm']);
            $this->db->set('FTXthWhTo', $paParams['FTXthWhTo']);
            $this->db->set('FTUsrCode', $paParams['FTUsrCode']);
            $this->db->set('FTSpnCode', $paParams['FTSpnCode']);
            $this->db->set('FTXthApvCode', $paParams['FTXthApvCode']);
            $this->db->set('FNXthDocPrint', $paParams['FNXthDocPrint']);
            $this->db->set('FCXthTotal', $paParams['FCXthTotal']);
            $this->db->set('FCXthVat', $paParams['FCXthVat']);
            $this->db->set('FCXthVatable', $paParams['FCXthVatable']);
            $this->db->set('FTXthRmk', $paParams['FTXthRmk']);
            $this->db->set('FTXthStaDoc', $paParams['FTXthStaDoc']);
            $this->db->set('FTXthStaApv', $paParams['FTXthStaApv']);
            $this->db->set('FTXthStaPrcStk', $paParams['FTXthStaPrcStk']);
            $this->db->set('FTXthStaDelMQ', $paParams['FTXthStaDelMQ']);
            $this->db->set('FNXthStaDocAct', $paParams['FNXthStaDocAct']);
            $this->db->set('FNXthStaRef', $paParams['FNXthStaRef']);
            $this->db->set('FTRsnCode', $paParams['FTRsnCode']);
            $this->db->set('FDLastUpdOn', $paParams['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paParams['FTLastUpdBy']);
            $this->db->set('FDCreateOn', $paParams['FDCreateOn']);
            $this->db->set('FTCreateBy', $paParams['FTCreateBy']);
            $this->db->insert('TCNTPdtTboHD');
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
        $this->db->where('FTXthDocNo', 'TBODOCTEMP');
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->update('TSVTTBODocDTTmp');

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
    public function FSaMDocCancel($paParams){
        $this->db->trans_begin();

        // Update Status Doc In HD
        $this->db->set('FTXthStaDoc', '3');
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->update('TCNTPdtTboHD');

        // BS Ref
        $this->db->where_in('FTXshDocNo',$paParams['tDocNo']);
        $this->db->delete('TCNTPdtTboHDDocRef');

        // TR Ref
        $this->db->where_in('FTXshRefDocNo',$paParams['tDocNo']);
        $this->db->delete('TCNTPdtReqBchHDDocRef');

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Cancel Fail',
            );
        }else{
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
    public function FSaMDocApprove($paParams = [])
    {
        $this->db->set('FTXthStaApv', '2');
        $this->db->set('FTXthApvCode', $paParams['tApvCode']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);

        $this->db->update('TCNTPdtTboHD');
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
        $this->db->delete('TCNTPdtTboHD');

        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtTboDT');

        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTPdtTboHDRef');

        // BS Ref
        $this->db->where_in('FTXshDocNo',$tDocNo);
        $this->db->delete('TCNTPdtTboHDDocRef');

        // TR Ref
        $this->db->where_in('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TCNTPdtReqBchHDDocRef');

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
     public function FSaMHDUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXthRmk',$paDataUpdate['FTXthRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtTboHD');

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
    public function FSxMBSUpdateRef($ptTableName , $paParam){
        $nChkDataDocRef  = $this->FSaMBSChkRefDupicate($ptTableName , $paParam);
        $tTableRef       = $ptTableName;
        if(isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1){ //หากพบว่าซ้ำ
            //ลบ
                $this->db->where_in('FTAgnCode',$paParam['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paParam['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paParam['FTXshDocNo']);
                $this->db->where_in('FTXshRefType',$paParam['FTXshRefType']);
                $this->db->where_in('FTXshRefKey',$paParam['FTXshRefKey']);

            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef,$paParam);
        }else{ //หากพบว่าไม่ซ้ำ
            $this->db->insert($tTableRef,$paParam);
        }
        return;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMBSChkRefDupicate($ptTableName , $paParam){
        try{
            $tAgnCode       = $paParam['FTAgnCode'];
            $tBchCode       = $paParam['FTBchCode'];
            $tDocNo         = $paParam['FTXshDocNo'];
            $tRefDocType    = $paParam['FTXshRefType'];
            $tRefDocNo      = $paParam['FTXshDocNo'];
            $tRefKey        = $paParam['FTXshRefKey'];

                $tSQL = "   SELECT
                            FTBchCode
                        FROM $ptTableName
                        WHERE 1=1
                        AND FTAgnCode     = '$tAgnCode'
                        AND FTBchCode     = '$tBchCode'
                        AND FTXshRefType  = '$tRefDocType' ";

                if($tRefDocType == 1 || $tRefDocType == 3){
                    $tSQL .= " AND FTXshDocNo  = '$tDocNo' " ;
                }else{
                    $tSQL .= " AND FTXshDocNo  = '$tRefDocNo' ";
                }

            $oQueryHD = $this->db->query($tSQL);
            if ($oQueryHD->num_rows() > 0){
                $aResult    = array(
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

    //หาว่าสาขานี้ คลัง default คืออะไร
    public function FSaMCheckWahouseInBCH($ptBCHCode){
        try{
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
        }catch (Exception $Error) {
            echo $Error;
        }
    }
}
