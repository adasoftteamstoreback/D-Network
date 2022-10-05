<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class APDebitnote_model extends CI_Model {

    // Function : Data List Product Adjust Stock HD
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDList($paData = []){
        $aDataUserInfo  = $this->session->userdata("tSesUsrInfo");
        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTXphDocNo DESC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        CDN.FTAgnCode,
                        AGNL.FTAgnName,
                        CDN.FTBchCode,
                        BCHL.FTBchName,
                        CDN.FTXphDocNo,
                        CONVERT(CHAR(10), CDN.FDXphDocDate, 103) AS FDXphDocDate,
                        CONVERT(CHAR(5), CDN.FDXphDocDate, 108)  AS FTXphDocTime,
                        CDN.FTXphStaDoc,
                        CDN.FTXphStaApv,
                        CDN.FTXphStaPrcStk,
                        CDN.FTCreateBy,
                        CDN.FDCreateOn,
                        USRL.FTUsrName AS FTCreateByName,
                        CDN.FTXphApvCode,
                        USRLAPV.FTUsrName AS FTXphApvName,
                        CDN.FTSplCode,
                        SPL.FTSplName,
                        CDN.FNXphDocType
                    FROM TAPTPdHD CDN WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON CDN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID    = ".$this->db->escape($nLngID)." 
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON CDN.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID     = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON CDN.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID  = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMSpl_L SPL WITH(NOLOCK) ON CDN.FTSplCode = SPL.FTSplCode AND SPL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMAgency_L AGNL WITH(NOLOCK) ON CDN.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = ".$this->db->escape($nLngID)."
                    WHERE 1=1
        ";

        if($this->session->userdata('tSesUsrLevel') != "HQ"){ // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchMulti   = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL       .= " AND CDN.FTBchCode IN (".$tBchMulti.") ";
        }
        
        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL   .= " 
                AND (
                    (CDN.FTXphDocNo         COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%') 
                    OR (BCHL.FTBchName      COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (USRL.FTUsrName      COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (USRLAPV.FTUsrName   COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (SPL.FTSplName       COLLATE THAI_BIN    LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                )
            ";
        }

        if($this->session->userdata("tSesUsrLevel") != "HQ"){
            $tBchMulti   = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL       .= " AND CDN.FTBchCode IN (".$tBchMulti.") ";
            if($this->session->userdata("tSesUsrLevel")=="SHP"){
                $tSQL   .= " AND CDN.FTShpCode = ".$this->db->escape($aDataUserInfo['FTShpCode'])."";
            }
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL   .= " 
                AND (
                    (CDN.FTBchCode      BETWEEN ".$this->db->escape($tSearchBchCodeFrom)."  AND ".$this->db->escape($tSearchBchCodeTo).") 
                    OR (CDN.FTBchCode   BETWEEN ".$this->db->escape($tSearchBchCodeTo)."    AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        // จากผู้จำหน่าย - ถึงผู้จำหน่าย
        $tSearchSplCodeFrom = $aAdvanceSearch['tSearchSplCodeFrom'];
        $tSearchSplCodeTo   = $aAdvanceSearch['tSearchSplCodeTo'];
        if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
            $tSQL   .= " 
                AND (
                    (CDN.FTSplCode      BETWEEN ".$this->db->escape($tSearchSplCodeFrom)."  AND ".$this->db->escape($tSearchSplCodeTo).") 
                    OR (CDN.FTSplCode   BETWEEN ".$this->db->escape($tSearchSplCodeTo)."    AND ".$this->db->escape($tSearchSplCodeFrom).")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL   .= " 
                AND (
                    (CDN.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").")  AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59").")) 
                    OR (CDN.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 23:59:59")."))
                )
            ";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND CDN.FTXphStaDoc  = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(CDN.FTXphStaApv,'') = '' AND CDN.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND CDN.FTXphStaApv  = ".$this->db->escape($tSearchStaDoc)."";
            }
        }

        // ค้นหาสถานะประมวลผล
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL   .= " AND (CDN.FTXphStaPrcStk    = ".$this->db->escape($tSearchStaPrcStk)." OR ISNULL(CDN.FTXphStaPrcStk,'') = '') ";
            } else {
                $tSQL   .= " AND CDN.FTXphStaPrcStk     = ".$this->db->escape($tSearchStaPrcStk)."";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL   .= " AND CDN.FNXphStaDocAct = 1";
            } else {
                $tSQL   .= " AND CDN.FNXphStaDocAct = 0";
            }
        }

        // ค้นหาตามประเภทเอกสาร
        $tStaDocType    = $aAdvanceSearch['tSearchDocType'];
        if(!empty($tStaDocType) && ($tStaDocType != "0")){
            if ($tStaDocType == 1) {
                $tSQL   .= " AND CDN.FNXphDocType = 6 ";
            } else {
                $tSQL   .= " AND CDN.FNXphDocType = 7 ";
            }
        }


        $tSQL .= ") Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])." ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMAPDGetPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;

    }
    
    // Function : All Page Of Product Adjust Stock HD
    // Creator  : 03/03/2022 Wasin
    public function FSnMAPDGetPageAll($paData = []){
        $aDataUserInfo  = $this->session->userdata("tSesUsrInfo");
        $nLngID         = $paData['FNLngID'];
        $tSQL           = " 
            SELECT 
                COUNT (CDN.FTXphDocNo) AS counts
            FROM TAPTPdHD CDN WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON CDN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID    = ".$this->db->escape($nLngID)." 
            LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON CDN.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON CDN.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID  = ".$this->db->escape($nLngID)."
            WHERE CDN.FDCreateOn <> ''
        ";
        
        if($this->session->userdata("tSesUsrLevel") != "HQ"){
            $tBchMulti   = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL       .= " AND  CDN.FTBchCode IN (".$tBchMulti.")  ";
            if($this->session->userdata("tSesUsrLevel")=="SHP"){
                $tSQL   .= " AND CDN.FTShpCode = ".$this->db->escape($aDataUserInfo['FTShpCode'])."";
            }
        }

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL   .= "
                AND (
                    (CDN.FTXphDocNo  COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (BCHL.FTBchName  COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (USRL.FTUsrName  COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (USRLAPV.FTUsrName  COLLATE THAI_BIN LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                )
            ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)){
            $tSQL   .= "
                AND (
                    (CDN.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)."  AND ".$this->db->escape($tSearchBchCodeTo).") 
                    OR (CDN.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)."    AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL   .= " 
                AND (
                    (CDN.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").")  AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59").")) 
                    OR (CDN.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 23:59:59")."))
                )
            ";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND CDN.FTXphStaDoc = ".$this->db->escape($tSearchStaDoc)."";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(CDN.FTXphStaApv,'') = '' AND CDN.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL   .= " AND CDN.FTXphStaApv = ".$this->db->escape($tSearchStaDoc)."";
            }
        }

        // ค้นหาสถานะประมวลผล
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL   .= " AND (CDN.FTXphStaPrcStk    = ".$this->db->escape($tSearchStaPrcStk)." OR ISNULL(CDN.FTXphStaPrcStk,'') = '') ";
            } else {
                $tSQL   .= " AND CDN.FTXphStaPrcStk     = ".$this->db->escape($tSearchStaPrcStk)."";
            }
        }
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL   .= " AND CDN.FNXphStaDocAct = 1";
            } else {
                $tSQL   .= " AND CDN.FNXphStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            // No Data
            return false;
        }
    }

    // Function : Function Get Count From Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetCountDTTemp($paDataWhere = []){
        
            $tSQL = "
                SELECT 
                    COUNT(DOCTMP.FTXthDocNo) AS counts
                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                WHERE 1 = 1
            ";

            $tDocNo = $paDataWhere['tDocNo'];
            $tDocKey = $paDataWhere['tDocKey'];
            $tSesSessionID = $this->session->userdata('tSesSessionID');    

            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";

            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result_array();
                $aResult = $oDetail[0]['counts'];
            }else{
                $aResult = 0;
            }

        return $aResult;

    }
    
    // Function : Function Get Max Seq From Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetMaxSeqDTTemp($paDataWhere){
        
            $tSQL = "
                SELECT 
                    MAX(DOCTMP.FNXtdSeqNo) AS maxSeqNo
                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                WHERE 1 = 1
            ";

            $tDocNo = $paDataWhere['tDocNo'];
            $tDocKey = $paDataWhere['tDocKey'];
            $tSesSessionID = $this->session->userdata('tSesSessionID');    

            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";

            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result_array();
                $aResult = $oDetail[0]['maxSeqNo'];
            }else{
                $aResult = 0;
            }

        return empty($aResult) ? 0 : $aResult;

    }

    // Function : Function Add DT Temp To DT
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertTmpToDT($paDataWhere){
        $tDocNo = $paDataWhere['tDocNo'];
        $tDocKey = $paDataWhere['tDocKey'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน DT ก่อนการย้าย DT Temp ไป DT
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPdDT');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPdDT 
                (FTBchCode, FTXphDocNo, FNXpdSeqNo, FTPdtCode, FTXpdPdtName, FTPunCode, FTPunName, FCXpdFactor,
                FTXpdBarCode, FTSrnCode, FTXpdVatType, FTVatCode, FCXpdVatRate, FTXpdSaleType, FCXpdSalePrice,
                FCXpdQty, FCXpdQtyAll, FCXpdSetPrice, FCXpdAmtB4DisChg, FTXpdDisChgTxt, FCXpdDis, FCXpdChg,
                FCXpdNet, FCXpdNetAfHD, FCXpdVat, FCXpdVatable, FCXpdWhtAmt, FTXpdWhtCode, FCXpdWhtRate, FCXpdCostIn,
                FCXpdCostEx, FCXpdQtyLef, FCXpdQtyRfn, FTXpdStaPrcStk, FTXpdStaAlwDis, FNXpdPdtLevel, FTXpdPdtParent,
                FCXpdQtySet, FTPdtStaSet, FTXpdRmk, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy)
        ";

        $tSQL .= "  
            SELECT 
                DOCTMP.FTBchCode,
                '$tDocNo' AS FTXphDocNo,
                DOCTMP.FNXtdSeqNo AS FNXpdSeqNo,
                DOCTMP.FTPdtCode,
                DOCTMP.FTXtdPdtName,
                DOCTMP.FTPunCode,
                DOCTMP.FTPunName,
                DOCTMP.FCXtdFactor,
                DOCTMP.FTXtdBarCode,
                DOCTMP.FTSrnCode,
                DOCTMP.FTXtdVatType,
                DOCTMP.FTVatCode,
                DOCTMP.FCXtdVatRate,
                DOCTMP.FTXtdSaleType,
                DOCTMP.FCXtdSalePrice,
                DOCTMP.FCXtdQty,
                DOCTMP.FCXtdQtyAll,
                DOCTMP.FCXtdSetPrice,
                DOCTMP.FCXtdAmtB4DisChg,
                DOCTMP.FTXtdDisChgTxt,
                DOCTMP.FCXtdDis,
                DOCTMP.FCXtdChg,
                DOCTMP.FCXtdNet,
                DOCTMP.FCXtdNetAfHD,
                DOCTMP.FCXtdVat,
                DOCTMP.FCXtdVatable,
                DOCTMP.FCXtdWhtAmt,
                DOCTMP.FTXtdWhtCode,
                DOCTMP.FCXtdWhtRate,
                DOCTMP.FCXtdCostIn,
                DOCTMP.FCXtdCostEx,
                DOCTMP.FCXtdQtyLef,
                DOCTMP.FCXtdQtyRfn,
                DOCTMP.FTXtdStaPrcStk,
                DOCTMP.FTXtdStaAlwDis,
                DOCTMP.FNXtdPdtLevel,
                DOCTMP.FTXtdPdtParent,
                DOCTMP.FCXtdQtySet,
                DOCTMP.FTXtdPdtStaSet,
                DOCTMP.FTXtdRmk,
                DOCTMP.FDLastUpdOn,
                DOCTMP.FTLastUpdBy,
                DOCTMP.FDCreateOn,
                DOCTMP.FTCreateBy

            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID = '$tSessionID'
            AND DOCTMP.FTXthDocKey = '$tDocKey'
            AND DOCTMP.FTXthDocNo = '$tWhereDocNo'
            ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        
        //echo $tSQL;
        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add.',
            );
        }
        return $aStatus;
    }
 
    // Function : Function Add DT To DT Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertDTToTemp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tDocKey = $paDataWhere['tDocKey'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน DT Temp ก่อนการย้าย DT ไป DT Temp
        // $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL = "   
            INSERT TCNTDocDTTmp 
                (FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor,
                FTXtdBarCode, FTSrnCode, FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt, FCXtdDis, FCXtdChg,
                FCXtdNet, FCXtdNetAfHD, FCXtdVat, FCXtdVatable, FCXtdWhtAmt, FTXtdWhtCode, FCXtdWhtRate, FCXtdCostIn,
                FCXtdCostEx, FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis, FNXtdPdtLevel, FTXtdPdtParent,
                FCXtdQtySet, FTXtdPdtStaSet, FTXtdRmk, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy, FTXthDocKey, FTSessionID)
        ";

        $tSQL .= "  
            SELECT 
                DT.FTBchCode,
                DT.FTXphDocNo AS FTXthDocNo,
                DT.FNXpdSeqNo AS FNXtdSeqNo,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FTSrnCode,
                DT.FTXpdVatType,
                DT.FTVatCode,
                DT.FCXpdVatRate,
                DT.FTXpdSaleType,
                DT.FCXpdSalePrice,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FCXpdSetPrice,
                DT.FCXpdAmtB4DisChg,
                DT.FTXpdDisChgTxt,
                DT.FCXpdDis,
                DT.FCXpdChg,
                DT.FCXpdNet,
                DT.FCXpdNetAfHD,
                DT.FCXpdVat,
                DT.FCXpdVatable,
                DT.FCXpdWhtAmt,
                DT.FTXpdWhtCode,
                DT.FCXpdWhtRate,
                DT.FCXpdCostIn,
                DT.FCXpdCostEx,
                DT.FCXpdQtyLef,
                DT.FCXpdQtyRfn,
                DT.FTXpdStaPrcStk,
                DT.FTXpdStaAlwDis,
                DT.FNXpdPdtLevel,
                DT.FTXpdPdtParent,
                DT.FCXpdQtySet,
                DT.FTPdtStaSet AS FTXpdPdtStaSet,
                DT.FTXpdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy,
                '$tDocKey' AS FTXthDocKey,
                '$tSessionID' AS FTSessionID

            FROM TAPTPdDT DT WITH (NOLOCK)
            WHERE DT.FTXphDocNo = '$tDocNo'
            ORDER BY DT.FNXpdSeqNo ASC
        ";
       
        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add.',
            );
        }
        return $aStatus;
    }

    // Function : Function Add DTDis Temp To DTDis
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertTmpToDTDis($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน DTDis ก่อนการย้าย DTDis Temp ไป DTDis
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPdDTDis');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPdDTDis 
                (FTBchCode, FTXphDocNo, FNXpdSeqNo, FDXpdDateIns, FNXpdStaDis, FTXpdDisChgTxt, FTXpdDisChgType, FCXpdNet, FCXpdValue)
        ";

        $tSQL .= "  
            SELECT 
                DTDISTMP.FTBchCode,
                '$tDocNo' AS FTXphDocNo,
                DTDISTMP.FNXtdSeqNo AS FNXpdSeqNo,
                DTDISTMP.FDXtdDateIns,
                DTDISTMP.FNXtdStaDis,
                DTDISTMP.FTXtdDisChgTxt,
                DTDISTMP.FTXtdDisChgType,
                DTDISTMP.FCXtdNet,
                DTDISTMP.FCXtdValue

            FROM TCNTDocDTDisTmp DTDISTMP WITH (NOLOCK)
            WHERE DTDISTMP.FTSessionID = '$tSessionID'
            AND DTDISTMP.FTXthDocNo = '$tWhereDocNo'
            ORDER BY DTDISTMP.FNXtdSeqNo ASC
        ";

        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add TAPTPdDTDis Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add TAPTPdDTDis',
            );
        }
        return $aStatus;
    }
    
    // Function : Function Add DTDis To DTDis Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertDTDisToTmp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน DTDis Temp ก่อนการย้าย DTDis ไป DTDis Temp
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTDocDTDisTmp');
        
        $tSQL = "   
            INSERT TCNTDocDTDisTmp
                (FTBchCode, FTXthDocNo, FNXtdSeqNo, FDXtdDateIns, FNXtdStaDis, FTXtdDisChgTxt, FTXtdDisChgType, FCXtdNet, FCXtdValue, FTSessionID)
        ";

        $tSQL .= "  
            SELECT 
                DTDIS.FTBchCode,
                DTDIS.FTXphDocNo AS FTXthDocNo,
                DTDIS.FNXpdSeqNo AS FNXpdSeqNo,
                DTDIS.FDXpdDateIns,
                DTDIS.FNXpdStaDis,
                DTDIS.FTXpdDisChgTxt,
                DTDIS.FTXpdDisChgType,
                DTDIS.FCXpdNet,
                DTDIS.FCXpdValue,
                '$tSessionID' AS FTSessionID

            FROM TAPTPdDTDis DTDIS WITH (NOLOCK)
            WHERE DTDIS.FTXphDocNo = '$tDocNo'
            ORDER BY DTDIS.FNXpdSeqNo ASC
        ";

        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add TCNTDocDTDisTmp Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add TCNTDocDTDisTmp',
            );
        }
        return $aStatus;
    }
    
    // Function : Function Add HDDis To HDDis Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertHDDisToTmp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน HDDis Temp ก่อนการย้าย HDDis ไป HDDis Temp
        $this->db->where('FTSessionID', $tSessionID);
        // $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTDocHDDisTmp');

        $tSQL = "   
            INSERT TCNTDocHDDisTmp
                (FTBchCode, FTXthDocNo, FDXtdDateIns, FTXtdDisChgTxt, FTXtdDisChgType, FCXtdTotalAfDisChg, FCXtdTotalB4DisChg, 
                FCXtdDisChg, FCXtdAmt, FDLastUpdOn, FDCreateOn, FTLastUpdBy, FTCreateBy, FTSessionID)
        ";

        $tSQL .= "  
            SELECT 
                HDDIS.FTBchCode, 
                HDDIS.FTXphDocNo AS FTXthDocNo,
                HDDIS.FDXphDateIns AS FDXtdDateIns,
                HDDIS.FTXphDisChgTxt AS FTXtdDisChgTxt,
                HDDIS.FTXphDisChgType AS FTXtdDisChgType,
                HDDIS.FCXphTotalAfDisChg AS FCXtdTotalAfDisChg,
                0 AS FCXtdTotalB4DisChg,
                HDDIS.FCXphDisChg AS FCXtdDisChg,
                HDDIS.FCXphAmt AS FCXtdAmt,
                '' AS FDLastUpdOn,
                '' AS FDCreateOn,
                '' AS FTLastUpdBy,
                '' AS FTCreateBy,
                '$tSessionID' AS FTSessionID

            FROM TAPTPdHDDis HDDIS WITH (NOLOCK)
            WHERE HDDIS.FTXphDocNo = '$tDocNo'
            ORDER BY HDDIS.FDXphDateIns ASC
        ";

        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add TCNTDocHDDisTmp Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add TCNTDocHDDisTmp',
            );
        }
        return $aStatus;
    }
    
    // Function : Function Add HDDis Temp To HDDis
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertTmpToHDDis($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ทำการลบ ใน DTDis ก่อนการย้าย DTDis Temp ไป DTDis
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPdHDDis');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPdHDDis
                (FTBchCode, FTXphDocNo, FDXphDateIns, FTXphDisChgTxt, FTXphDisChgType, FCXphTotalAfDisChg, 
                FCXphDisChg, FCXphAmt)
        ";

        $tSQL .= "  
            SELECT 
                HDDISTMP.FTBchCode,
                '$tDocNo' AS FTXphDocNo,
                HDDISTMP.FDXtdDateIns AS FDXphDateIns,
                HDDISTMP.FTXtdDisChgTxt AS FTXphDisChgTxt,
                HDDISTMP.FTXtdDisChgType AS FTXphDisChgType,
                HDDISTMP.FCXtdTotalAfDisChg AS FCXphTotalAfDisChg,
                HDDISTMP.FCXtdDisChg AS FCXphDisChg,
                HDDISTMP.FCXtdAmt AS FCXphAmt

            FROM TCNTDocHDDisTmp HDDISTMP WITH (NOLOCK)
            WHERE HDDISTMP.FTXthDocNo = '$tWhereDocNo'
            ORDER BY HDDISTMP.FDXtdDateIns ASC
        ";

        $oQuery = $this->db->query($tSQL);

        if($oQuery > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add TCNTDocHDDisTmp Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add TCNTDocHDDisTmp',
            );
        }
        return $aStatus;
    }
    
    // Function : Function Get Pdt From Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetDTTempListPage($paData = []){

        try{
            $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
            $tSQL = "
                SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM
                        (SELECT DOCTMP.FTBchCode,
                                DOCTMP.FTXthDocNo,
                                /*ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,*/
                                DOCTMP.FNXtdSeqNo,
                                DOCTMP.FTXthDocKey,
                                DOCTMP.FTPdtCode,
                                DOCTMP.FTXtdPdtName,
                                DOCTMP.FTPunName,
                                DOCTMP.FTXtdBarCode,
                                DOCTMP.FTPunCode,
                                DOCTMP.FCXtdFactor,
                                DOCTMP.FCXtdQty,
                                DOCTMP.FCXtdSetPrice,
                                DOCTMP.FTXtdDisChgTxt,
                                DOCTMP.FCXtdNet,
                                DOCTMP.FTXtdStaAlwDis,
                                DOCTMP.FDLastUpdOn,
                                DOCTMP.FDCreateOn,
                                DOCTMP.FTLastUpdBy,
                                DOCTMP.FTCreateBy

                            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
            ";

            $tDocNo = $paData['tDocNo'];
            $tDocKey = $paData['tDocKey'];
            $tSesSessionID = $this->session->userdata('tSesSessionID');    
           
            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";
            
            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $tSearchList = $paData['tSearchAll'];
            
            if ($tSearchList != '') {
                $tSQL .= " AND ( DOCTMP.FTPdtCode LIKE '%$tSearchList%'";
                $tSQL .= " OR DOCTMP.FTXtdPdtName LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTXtdBarCode LIKE '%$tSearchList%' )";
            }
            
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);

            if($oQuery->num_rows() > 0){
                $aList          = $oQuery->result_array();
                $oFoundRow      = $this->FSoMAPDGetDTTempListPageAll($paData);
                $nFoundRow      = $oFoundRow[0]->counts;
                $nPageAll       = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult        = array(
                    'raItems'           => $aList,
                    'rnAllRow'          => $nFoundRow,
                    'rnCurrentPage'     => $paData['nPage'],
                    'rnAllPage'         => $nPageAll,
                    'rtCode'            => '1',
                    'rtDesc'            => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"=> 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }

            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }

    }
    
    // Function : All Page Of Product Size
    // Creator  : 03/03/2022 Wasin
    public function FSoMAPDGetDTTempListPageAll($paData = []){
        try{

            $tSQL = "
                SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP
                    WHERE 1 = 1
            ";

            $tDocNo = $paData['tDocNo'];
            $tDocKey = $paData['tDocKey'];
            $tSesSessionID = $this->session->userdata('tSesSessionID');    

            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";
            
            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $tSearchList = $paData['tSearchAll'];
            
            if ($tSearchList != '') {
                $tSQL .= " AND ( DOCTMP.FTPdtCode LIKE '%$tSearchList%'";
                $tSQL .= " OR DOCTMP.FTXtdPdtName LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTXtdBarCode LIKE '%$tSearchList%' )";
            }
            
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    // Function : Function Get Data Pdt Pun By Barcode
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetPunCodeByBarCode($paParams = []){
        
        $tBarCode = $paParams['tBarCode'];
        $tSplCode = $paParams['tSplCode'];
        // config tCN_Cost	1 ต้นทุนเฉลี่ย ,2 ต้นทุนสุดท้าย ,3 ต้นทุนมาตรฐาน ,4 ต้นทุน FIFO	
        // TCNMPdtCostAvg
        $aConfigParams = [
            "tSysCode" => "tCN_Cost",
            "tSysApp" => "ALL",
            "tSysKey" => "Company",
            "tSysSeq" => "1",
            "tGmnCode" => "COMP"
        ];
        $aSysConfig = FCNaGetSysConfig($aConfigParams);

        $tCN_Cost_Config = "1,2,3,4"; // Defualt Config

        if(!empty($aSysConfig['raItems'])) {
            $tUsrConfigValue = $aSysConfig['raItems']['FTSysStaUsrValue']; // Set by User
            $tDefConfigValue = $aSysConfig['raItems']['FTSysStaDefValue']; // Set by System
            $tCN_Cost_Config = !empty($tUsrConfigValue) ? $tUsrConfigValue : $tDefConfigValue; // Config by User or Default    
        }

        $aCN_Cost_Config = explode(',', $tCN_Cost_Config);
        
        $tCost = ''; $tComma = '';
        
        /*===== เรียงลำดับ การหาต้นทุน ============================================*/
        if(isset($aCN_Cost_Config) && FCNnHSizeOf($aCN_Cost_Config) > 0) {
            
            $tComma = ',';
            $tCost = " (CASE";

            foreach($aCN_Cost_Config as $key => $costConfig) {
                switch($costConfig) {
                    case '1' : {
                        $tCost .= ' WHEN COSTAVG.FCPdtCostAmt IS NOT NULL THEN COSTAVG.FCPdtCostAmt';
                        break;
                    }
                    case '2' : {
                        $tCost .= ' WHEN PDTSPL.FCSplLastPrice IS NOT NULL THEN PDTSPL.FCSplLastPrice';
                        break;
                    }
                    case '3' : {
                        $tCost .= ' WHEN PDT.FCPdtCostStd IS NOT NULL THEN PDT.FCPdtCostStd';
                        break;
                    }
                    case '4' : {
                        $tCost .= ' WHEN COSTFIFO.FCPdtCostAmt IS NOT NULL THEN COSTFIFO.FCPdtCostAmt';
                        break;
                    }
                }
            }
            $tCost .= " ELSE 0 END) AS cCost ";
        }
        
        $tSQL = "
                    SELECT
                        BAR.FTBarCode,
                        BAR.FTPdtCode,
                        BAR.FTPunCode,
                        PACKSIZE.FCPdtUnitFact$tComma
                        $tCost
                    FROM TCNMPdtBar BAR WITH (NOLOCK)
                    LEFT JOIN TCNMSpl SPL WITH (NOLOCK) ON SPL.FTSplCode = '$tSplCode'
                    LEFT JOIN TCNMPdtPackSize PACKSIZE WITH (NOLOCK) ON PACKSIZE.FTPdtCode = BAR.FTPdtCode AND PACKSIZE.FTPunCode = BAR.FTPunCode
                    LEFT JOIN TCNMPdtCostAvg COSTAVG WITH (NOLOCK) ON COSTAVG.FTPdtCode = BAR.FTPdtCode
                    LEFT JOIN TCNMPdtSpl PDTSPL WITH (NOLOCK) ON PDTSPL.FTPdtCode = BAR.FTPdtCode AND PDTSPL.FTBarCode = BAR.FTBarCode
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON PDT.FTPdtCode = BAR.FTPdtCode
                    LEFT JOIN TCNMPdtCostFIFO COSTFIFO WITH (NOLOCK) ON COSTFIFO.FTPdtCode = BAR.FTPdtCode
                    WHERE BAR.FTBarCode = '$tBarCode'
                    AND PDTSPL.FTSplCode = '$tSplCode'
        ";
        
        // echo $tSQL;
        
        $oQuery = $this->db->query($tSQL);
            
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->row_array();
            $aResult = array(
                'raItem'   => $aData,
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
    
    // Function : Function Get Data Pdt
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetDataPdt($paData = []){

        $tPdtCode = $paData['tPdtCode'];
        $FTPunCode = $paData['tPunCode'];
        $FTBarCode = $paData['tBarCode'];
        $FTSplCode = $paData['tSplCode'];
        $nLngID = $paData['nLngID'];

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
                    P4PDT.FCPgdPriceRet,
                    P4PDT.FCPgdPriceWhs,
                    P4PDT.FCPgdPriceNet
                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                WHERE 1=1
                AND (CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121))
                AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121))
            ) AS PRI4PDT
            ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode
            WHERE 1 = 1
        ";
        
            if($tPdtCode!= ""){
                $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
            }

            if($FTBarCode!= ""){
                $tSQL .= "AND BAR.FTBarCode = '$FTBarCode'";
            }
            
            $tSQL .= " ORDER BY FDVatStart DESC";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result();
                $aResult = array(
                    'raItem'   => $oDetail[0],
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

    // Function : Update DT Temp by Seq
    // Creator  : 03/03/2022 Wasin
    function FSaMAPDUpdateInlineDTTemp($aDataUpd = [], $aDataWhere = []){
        try{
            $this->db->set($aDataUpd['tFieldName'], $aDataUpd['tValue']);
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->where('FTXthDocNo', $aDataWhere['tDocNo']);
            $this->db->where('FNXtdSeqNo', $aDataWhere['nSeqNo']);
            $this->db->where('FTXthDocKey', $aDataWhere['tDocKey']);
            $this->db->update('TCNTDocDTTmp');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Update Fail',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }

    // Function : Function insert DT to Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDInsertPDTToTemp($paData = [], $paDataWhere = []){
        
        $paData = $paData['raItem'];
        if($paDataWhere['nAPDOptionAddPdt'] == 1){
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL = "   
                SELECT 
                    FNXtdSeqNo, 
                    FCXtdQty 
                FROM TCNTDocDTTmp 
                WHERE FTBchCode = '".$paDataWhere['tBchCode']."' 
                AND FTXthDocNo = '".$paDataWhere['tDocNo']."'
                AND FTXthDocKey = '".$paDataWhere['tDocKey']."'
                AND FTSessionID = '".$paDataWhere['tSessionID']."'
                AND FTPdtCode = '".$paData["FTPdtCode"]."' 
                AND FTXtdBarCode = '".$paData["FTBarCode"]."'
                ORDER BY FNXtdSeqNo
            ";
            
            $oQuery = $this->db->query($tSQL);
            
            if($oQuery->num_rows() > 0){ // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult = $oQuery->row_array();
                $tSQL = "
                    UPDATE TCNTDocDTTmp SET
                        FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                    WHERE FTBchCode = '".$paDataWhere['tBchCode']."' 
                    AND FTXthDocNo  = '".$paDataWhere['tDocNo']."' 
                    AND FNXtdSeqNo = '".$aResult["FNXtdSeqNo"]."' 
                    AND FTXthDocKey = '".$paDataWhere['tDocKey']."' 
                    AND FTSessionID = '".$paDataWhere['tSessionID']."' 
                    AND FTPdtCode = '".$paData["FTPdtCode"]."' 
                    AND FTXtdBarCode = '".$paData["FTBarCode"]."'";
                
                $this->db->query($tSQL);
                
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{

                $nQty = ($paDataWhere['nQty'] == '') ? 1 : $paDataWhere['nQty'];

                // เพิ่มรายการใหม่
                $this->db->set('FTPdtCode'      , $paData['FTPdtCode']);
                $this->db->set('FTXtdPdtName'   , $paData['FTPdtName']);
                $this->db->set('FCXtdFactor'    , $paData['FCPdtUnitFact']);
                $this->db->set('FCPdtUnitFact'  , $paData['FCPdtUnitFact']);
                $this->db->set('FTPunCode'      , $paData['FTPunCode']);
                $this->db->set('FTPunName'      , $paData['FTPunName']);
                $this->db->set('FTXtdVatType'   , $paData['FTPdtStaVatBuy']);
                $this->db->set('FTVatCode'      , ($paDataWhere['tVatcode'] == '') ? $paData['FTVatCode'] : $paDataWhere['tVatcode']);
                $this->db->set('FCXtdVatRate'   , ($paDataWhere['tVatrate'] == '') ? $paData['FCVatRate'] : $paDataWhere['tVatrate']);
                $this->db->set('FCXtdNet'       , $paData['FTPdtPoint'] * $paData['FCPdtCostStd']);
                $this->db->set('FTXtdStaAlwDis' , $paData['FTPdtStaAlwDis']);
                $this->db->set('FCXtdQty'       , $nQty ); 
                $this->db->set('FCXtdQtyAll'    , $nQty * $paData['FCPdtUnitFact']); // จากสูตร qty * fector
                $this->db->set('FCXtdSalePrice' , $paData['FTPdtSalePrice']);
                $this->db->set('FTBchCode'      , $paDataWhere['tBchCode']);
                $this->db->set('FTXthDocNo'     , $paDataWhere['tDocNo']);
                $this->db->set('FNXtdSeqNo'     , $paDataWhere['nMaxSeqNo']);
                $this->db->set('FTXthDocKey'    , $paDataWhere['tDocKey']);
                $this->db->set('FTXtdBarCode'   , $paDataWhere['tBarCode']);
                $this->db->set('FCXtdSetPrice'  , $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
                $this->db->set('FTSessionID'    , $paDataWhere['tSessionID']);
                $this->db->set('FDLastUpdOn'    , date('Y-m-d h:i:s'));
                $this->db->set('FTLastUpdBy'    , $this->session->userdata('tSesUsername'));
                $this->db->set('FDCreateOn'     , date('Y-m-d h:i:s'));
                $this->db->set('FTCreateBy'     , $this->session->userdata('tSesUsername'));
                $this->db->insert('TCNTDocDTTmp');

                $this->db->last_query();  

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add.',
                    );
                }
            }
        }else{
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
            $this->db->set('FCXtdQtyAll', 1*$paData['FCPdtUnitFact']); // จากสูตร qty * fector
            $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

            $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
            $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
            $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
            $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
            $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
            $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
            $this->db->set('FTSessionID', $paDataWhere['tSessionID']);
            $this->db->set('FDLastUpdOn', date('Y-m-d h:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FDCreateOn', date('Y-m-d h:i:s'));
            $this->db->set('FTCreateBy', $this->session->userdata('tSesUsername'));
                    
            $this->db->insert('TCNTDocDTTmp');

            $this->db->last_query();  

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
        }
        
        return $aStatus;
        
    }

    // Function : Update DocNo in DT Temp
    // Creator  : 03/03/2022 Wasin
    function FSaMAPDAddUpdateDocNoInDocTemp($aDataWhere = []){

        try{

            $this->db->set('FTXthDocNo' , $aDataWhere['tDocNo']);    
            $this->db->set('FTBchCode'  , $aDataWhere['tBchCode']);    
            $this->db->where('FTXthDocNo', '');
            $this->db->where('FTSessionID', $$aDataWhere['tSessionID']);
            $this->db->where('FTXthDocKey', $aDataWhere['tDocKey']);
            $this->db->update('TCNTDocDTTmp');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update DocNo Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Update DocNo Fail',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }
    
    
    // Function : Cancel Document
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDCancel($paDataUpdate = []){
        try{
            $this->db->trans_begin();

            // TAPTPdHD
            $this->db->set('FTXphStaDoc' , '3');
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
            $this->db->update('TAPTPdHD');

            $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo']);
            $this->db->delete('TAPTPdHDDocRef');

            $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
            $this->db->delete('TAPTPiHDDocRef');

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }
            
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Function : Approve Doc Have PDT
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDHavePdtApprove($paDataUpdate = []){
        try{
            // TAPTPdHD
            $this->db->set('FTXphStaPrcStk' , '2');
            $this->db->set('FTXphStaApv' , '2');
            $this->db->set('FTXphApvCode' , $paDataUpdate['tApvCode']);
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
            $this->db->update('TAPTPdHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Approve Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Approve Fail',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }
    
    // Function : Approve Doc None PDT
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDNonePdtApprove($paDataUpdate = []){
        try{
            // TAPTPdHD
            $this->db->set('FTXphStaPrcStk' , '1');
            $this->db->set('FTXphStaApv' , '1');
            $this->db->set('FTXphApvCode' , $paDataUpdate['tApvCode']);
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);

            $this->db->update('TAPTPdHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Approve Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Approve Fail',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }
    
    // Function :  Function Get Sum From Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDSumDTTemp($paDataWhere = []){
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');   
        $tSQL           = "
            SELECT 
                SUM(FCXtdAmt) AS FCXtdAmt
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FDCreateOn <> ''
        ";
        $tSQL .= " AND DOCTMP.FTXthDocNo    = ".$this->db->escape($tDocNo)." ";
        $tSQL .= " AND DOCTMP.FTXthDocKey   = ".$this->db->escape($tDocKey)." ";
        $tSQL .= " AND DOCTMP.FTSessionID   = ".$this->db->escape($tSesSessionID)." ";
        $oQuery = $this->db->query($tSQL);
            
        if ($oQuery->num_rows() > 0){
            $oResult = $oQuery->result_array();
        }else{
            $oResult = '';
        }
        return $oResult;
    }

    // Function : Function Get Cal From HDDis Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDCalInHDDisTemp($paParams = []){

        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID']; 
        $tSQL = "
                    SELECT
                        /* ข้อความมูลค่าลดชาร์จ ==============================================================*/
                        STUFF((
                            SELECT  ',' + DOCCONCAT.FTXtdDisChgTxt
                            FROM TCNTDocHDDisTmp DOCCONCAT
                            WHERE  1=1 
                            AND DOCCONCAT.FTBchCode 		= '$tBchCode'
                            AND DOCCONCAT.FTXthDocNo		= '$tDocNo'
                            AND DOCCONCAT.FTSessionID		= '$tSessionID'
                        FOR XML PATH('')), 1, 1, '') AS FTXphDisChgTxt,
                        
                        /* มูลค่ารวมส่วนลด ==============================================================*/
                        SUM( 
                            CASE 
                                WHEN HDDISTMP.FTXtdDisChgType = 1 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                WHEN HDDISTMP.FTXtdDisChgType = 2 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                ELSE 0 
                            END
                        ) AS FCXphDis,
                        
                        /* มูลค่ารวมส่วนชาร์จ ==============================================================*/
                        SUM( 
                            CASE 
                                WHEN HDDISTMP.FTXtdDisChgType = 3 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                WHEN HDDISTMP.FTXtdDisChgType = 4 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                ELSE 0 
                            END
                        ) AS FCXphChg
                        
                    FROM TCNTDocHDDisTmp HDDISTMP    
                    
                    WHERE HDDISTMP.FTXthDocNo   = '$tDocNo' 
                    AND HDDISTMP.FTSessionID    = '$tSessionID'
                    AND HDDISTMP.FTBchCode      = '$tBchCode'

                    GROUP BY HDDISTMP.FTSessionID
                ";
        
        $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $aResult = $oQuery->result_array()[0];
            }else{
                $aResult = [];
            }


        return $aResult;
    }
    
    // Function : Function Get Cal From SPL Vat Code
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetSplVatCode($paParams = []){
        $tSplCode = $paParams['tSplCode'];
        
        $tSQL = "   SELECT 
                        *
                    FROM TCNMSpl WITH (NOLOCK)
                    WHERE FTSplCode = '$tSplCode'
                ";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $aResult = $oQuery->row_array();
            }else{
                $aResult = '';
            }


        return $aResult;
        
    }
    
    // Function : Function Get HD Spl
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetHDSpl($paParams = []){
        $tDocNo = $paParams['tDocNo'];
        $tSQL   = "
            SELECT *
            FROM TAPTPdHDSpl WITH (NOLOCK)
            WHERE FTXphDocNo    = ".$this->db->escape($tDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array();
        }else{
            $aResult = '';
        }
        return $aResult;
    }
    
    // Function : Function Get Cal From DT Temp
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDCalInDTTemp($paParams = []){
        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID'];   
        $tDataVatInOrEx = $paParams['tSplVatType']; 
        $tSQL           = "
            SELECT 
                /* ยอดรวม ==============================================================*/
                SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXphTotal,

                /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalNV,

                /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalNoDis,

                /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalB4DisChgV,

                /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalB4DisChgNV,

                /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalAfDisChgV,

                /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                SUM(
                    CASE
                        WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                        ELSE 0
                    END
                ) AS FCXphTotalAfDisChgNV,

                /* ยอดรวมเฉพาะภาษี ==============================================================*/
                (
                    CASE 
                        WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                            (
                                /* ยอดรวม */
                                SUM(DTTMP.FCXtdNet)
                                - 
                                /* ยอดรวมสินค้าไม่มีภาษี */
                                SUM(
                                    CASE
                                        WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                        ELSE 0
                                    END
                                )
                            )
                            -
                            (
                                /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                SUM(
                                    CASE
                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                        ELSE 0
                                    END
                                )
                                -
                                /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                SUM(
                                    CASE
                                        WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                        ELSE 0
                                    END
                                )
                            )
                        WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                        
                                (
                                    /* ยอดรวม */
                                    SUM(DTTMP.FCXtdNet)
                                    - 
                                    /* ยอดรวมสินค้าไม่มีภาษี */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                                -
                                (
                                    /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                    -
                                    /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN 
                                                ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                            ELSE 0
                                        END
                                    )
                                ) 
                                + 
                                SUM(ISNULL(DTTMP.FCXtdVat, 0))
                    ELSE 0 END
                ) AS FCXphAmtV,

                /* ยอดรวมเฉพาะไม่มีภาษี ==============================================================*/
                (
                    SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                    -
                    (
                        SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                        -
                        SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                    )
                ) AS FCXphAmtNV,

                /* ยอดภาษี ==============================================================*/
                SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXphVat,

                /* ยอดแยกภาษี ==============================================================*/
                (
                    (
                        CASE 
                            WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                                (
                                    /* ยอดรวม */
                                    SUM(DTTMP.FCXtdNet)
                                    - 
                                    /* ยอดรวมสินค้าไม่มีภาษี */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                                -
                                (
                                    /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                    -
                                    /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                            WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                            
                                    (
                                        /* ยอดรวม */
                                        SUM(DTTMP.FCXtdNet)
                                        - 
                                        /* ยอดรวมสินค้าไม่มีภาษี */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                    )
                                    -
                                    (
                                        /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                        -
                                        /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN 
                                                    ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                ELSE 0
                                            END
                                        )
                                    ) 
                                    + 
                                    SUM(ISNULL(DTTMP.FCXtdVat, 0))
                        ELSE 0 END
                        - 
                        SUM(ISNULL(DTTMP.FCXtdVat, 0))
                    )
                    +
                    (
                        SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                        -
                        (
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                            -
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                        )
                    )
                ) AS FCXphVatable,

                /* รหัสอัตราภาษี ณ ที่จ่าย ==============================================================*/
                STUFF((
                    SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                    FROM TCNTDocDTTmp DOCCONCAT
                    WHERE  1=1 
                    AND DOCCONCAT.FTBchCode = '$tBchCode'
                    AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                    AND DOCCONCAT.FTSessionID = '$tSessionID'
                FOR XML PATH('')), 1, 1, '') AS FTXphWpCode,

                /* ภาษีหัก ณ ที่จ่าย ==============================================================*/
                SUM(ISNULL(DTTMP.FCXtdWhtAmt, 0)) AS FCXphWpTax

            FROM TCNTDocDTTmp DTTMP 
            WHERE DTTMP.FTXthDocNo  = ".$this->db->escape($tDocNo)." 
            AND DTTMP.FTXthDocKey   = ".$this->db->escape($tDocKey)." 
            AND DTTMP.FTSessionID   = ".$this->db->escape($tSessionID)."
            AND DTTMP.FTBchCode     = ".$this->db->escape($tBchCode)."
            GROUP BY DTTMP.FTSessionID
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->result_array();
        }else{
            $aResult = [];
        }
        return $aResult;
    }

    // Function : Get User Login By Code
    // Creator  : 03/03/2022 Wasin
    public function FStAPDGetUsrByCode($paParams = []){
        $nLngID     = $paParams['FNLngID'];
        $tUsrLogin  = $paParams['FTUsrCode'];
        if($this->session->userdata('tSesUsrLevel') == "HQ"){
            $tBchCode   = "'" . FCNtGetBchInComp() . "'";
        }else{
            $tBchCode   = "UGP.FTBchCode";
        }
        $tSQL   = "
            SELECT 
                BCH.FTBchCode,
                BCHL.FTBchName,
                MCHL.FTMerCode,
                MCHL.FTMerName,
                UGP.FTShpCode,
                SHPL.FTShpName,
                SHP.FTShpType,
                USR.FTUsrCode,
                USRL.FTUsrName,
                USR.FTDptCode,
                DPTL.FTDptName,
                WAH.FTWahCode AS FTWahCode,
			    WAHL.FTWahName AS FTWahName
            FROM TCNMUser USR
            LEFT JOIN TCNMUser_L USRL       ON USRL.FTUsrCode = USR.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTUsrGroup UGP      ON UGP.FTUsrCode = USR.FTUsrCode
            LEFT JOIN TCNMBranch BCH        ON $tBchCode = BCH.FTBchCode 
            LEFT JOIN TCNMBranch_L BCHL     ON $tBchCode = BCHL.FTBchCode 
            LEFT JOIN TCNMShop SHP          ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMShop_L SHPL       ON UGP.FTShpCode = SHPL.FTShpCode AND UGP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse WAH       ON ($tBchCode = WAH.FTWahRefCode OR SHP.FTShpCode = WAH.FTWahRefCode)
            LEFT JOIN TCNMWaHouse_L WAHL    ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant_L MCHL   ON SHP.FTMerCode = MCHL.FTMerCode AND  MCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUsrDepart_L DPTL  ON DPTL.FTDptCode = USR.FTDptCode AND DPTL.FNLngID  = ".$this->db->escape($nLngID)."
            WHERE USR.FTUsrCode = ".$this->db->escape($tUsrLogin)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oRes       = $oQuery->row_array();
            $tDataShp   = $oRes;
        }else{
            $tDataShp   = '';
        }
        return $tDataShp;
    }

    // Function : Search AP Debitnote By ID
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetHD($paData = []){
        $tDocNo = $paData['FTXphDocNo'];
        $nLngID = $paData['FNLngID'];
        $tSQL   = "
            SELECT
                HD.FTAgnCode,
                AGNL.FTAgnName,
                HD.FTBchCode,
                HD.FTXphDocNo,
                HD.FNXphDocType,
                CONVERT(CHAR(5), HD.FDXphDocDate, 108) AS FTXphDocTime,
                HD.FDXphDocDate,
                HD.FTShpCode,
                HD.FTXphCshOrCrd,
                HD.FTXphVATInOrEx,
                HD.FTDptCode,
                HD.FTWahCode,
                HD.FTUsrCode,
                HD.FTXphApvCode,
                HD.FTSplCode,
                HD.FTXphRefExt,
                HD.FDXphRefExtDate,
                HD.FTXphRefInt,
                HD.FDXphRefIntDate,
                HD.FTXphRefAE,
                HD.FNXphDocPrint,
                HD.FTRteCode,
                HD.FCXphRteFac,
                HD.FCXphTotal,
                HD.FCXphTotalNV,
                HD.FCXphTotalNoDis,
                HD.FTXphStaDelMQ,
                HD.FCXphTotalB4DisChgV,
                HD.FCXphTotalB4DisChgNV,
                HD.FTXphDisChgTxt,
                HD.FCXphDis,
                HD.FCXphChg,
                HD.FCXphTotalAfDisChgV,
                HD.FCXphTotalAfDisChgNV,
                HD.FCXphRefAEAmt,
                HD.FCXphAmtV,
                HD.FCXphAmtNV,
                HD.FCXphVat,
                HD.FCXphVatable,
                HD.FTXphWpCode,
                HD.FCXphWpTax,
                HD.FCXphGrand,
                HD.FCXphRnd,
                HD.FTXphGndText,
                HD.FCXphPaid,
                HD.FCXphLeft,
                HD.FTXphRmk,
                HD.FTXphStaRefund,
                HD.FTXphStaDoc,
                HD.FTXphStaApv,
                HD.FTXphStaPrcStk,
                HD.FTXphStaPaid,
                HD.FNXphStaDocAct,
                HD.FNXphStaRef,
                HD.FDCreateOn,
                HD.FTCreateBy,
                HD.FDLastUpdOn,
                HD.FTLastUpdBy,
                    
                    
                BCHLDOC.FTBchName,
                DPTL.FTDptName,
                SHPL.FTShpName,
                WAHL.FTWahName,
                SPLL.FTSplName
                /*USRLCREATE.FTUsrName AS FTCreateByName,
                USRLKEY.FTUsrName AS FTUsrName,
                USRAPV.FTUsrName AS FTXphStaApvName,
                SHPLTO.FTShpName AS FTXphShopNameTo,
                WAHLTO.FTWahName AS FTXphWhNameTo,
                POSVDTO.FTPosComName AS FTXphPosNameTo*/
                    
            FROM [TAPTPdHD] HD
            LEFT JOIN TCNMBranch_L      BCHLDOC ON HD.FTBchCode = BCHLDOC.FTBchCode AND BCHLDOC.FNLngID     = ".$this->db->escape($nLngID)."
            /*LEFT JOIN TCNMBranch_L      BCHLTO ON HD.FTXphBchTo = BCHLTO.FTBchCode AND BCHLTO.FNLngID     = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant_L    MCHLTO ON HD.FFXphMerchantTo = MCHLTO.FTMerCode AND MCHLTO.FNLngID  = ".$this->db->escape($nLngID)."  
            LEFT JOIN TCNMUser_L        USRLCREATE ON HD.FTCreateBy = USRLCREATE.FTUsrCode AND USRLCREATE.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRLKEY ON HD.FTUsrCode = USRLKEY.FTUsrCode AND USRLKEY.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRAPV ON HD.FTXphApvCode = USRAPV.FTUsrCode AND USRAPV.FNLngID = ".$this->db->escape($nLngID)." */
            LEFT JOIN TCNMSpl_L SPLL ON HD.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUsrDepart_L   DPTL ON HD.FTDptCode = DPTL.FTDptCode AND DPTL.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop_L        SHPL ON HD.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID  = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAHL ON HD.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID  = ".$this->db->escape($nLngID)."
            /*LEFT JOIN TCNMPosLastNo     POSVDTO WITH (NOLOCK) ON HD.FTXphPosTo = POSVDTO.FTPosCode*/
            LEFT JOIN TCNMAgency_L AGNL WITH(NOLOCK) ON HD.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = ".$this->db->escape($nLngID)."
            WHERE 1=1
        ";
        if($tDocNo != ""){
            $tSQL   .= "AND HD.FTXphDocNo = ".$this->db->escape($tDocNo)."";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $aResult    = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    // Function : Add/Update HD Master สำหรับใบเพื่มหนี้มีสินค้า
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDAddUpdateHDHavePdt($paData = []){
        try{
            // Update Master
            $this->db->set('FTAgnCode', $paData['FTAgnCode']);
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FDXphDocDate', $paData['FDXphDocDate']);
            $this->db->set('FTShpCode', $paData['FTShpCode']);
            $this->db->set('FTXphCshOrCrd', $paData['FTXphCshOrCrd']);
            $this->db->set('FTXphVATInOrEx', $paData['FTXphVATInOrEx']);
            $this->db->set('FTWahCode', $paData['FTWahCode']);
            $this->db->set('FTSplCode', $paData['FTSplCode']);
            $this->db->set('FTXphRefExt', $paData['FTXphRefExt']);
            $this->db->set('FDXphRefExtDate', $paData['FDXphRefExtDate']);
            $this->db->set('FTXphRefInt', $paData['FTXphRefInt']);
            $this->db->set('FDXphRefIntDate', $paData['FDXphRefIntDate']);
            $this->db->set('FCXphTotal', $paData['FCXphTotal']);
            $this->db->set('FCXphTotalNV', $paData['FCXphTotalNV']);
            $this->db->set('FCXphTotalNoDis', $paData['FCXphTotalNoDis']);
            $this->db->set('FCXphTotalB4DisChgV', $paData['FCXphTotalB4DisChgV']);
            $this->db->set('FCXphTotalB4DisChgNV', $paData['FCXphTotalB4DisChgNV']);
            $this->db->set('FTXphDisChgTxt', $paData['FTXphDisChgTxt']);
            $this->db->set('FCXphDis', $paData['FCXphDis']);
            $this->db->set('FCXphChg', $paData['FCXphChg']);
            $this->db->set('FCXphTotalAfDisChgV', $paData['FCXphTotalAfDisChgV']);
            $this->db->set('FCXphTotalAfDisChgNV', $paData['FCXphTotalAfDisChgNV']);
            $this->db->set('FCXphAmtV', $paData['FCXphAmtV']);
            $this->db->set('FCXphAmtNV', $paData['FCXphAmtNV']);
            $this->db->set('FCXphVat', $paData['FCXphVat']);
            $this->db->set('FCXphVatable', $paData['FCXphVatable']);
            $this->db->set('FTXphWpCode', $paData['FTXphWpCode']);
            $this->db->set('FCXphWpTax', $paData['FCXphWpTax']);
            $this->db->set('FCXphGrand', $paData['FCXphGrand']);
            $this->db->set('FCXphRnd', $paData['FCXphRnd']);
            $this->db->set('FTXphGndText', $paData['FTXphGndText']);
            $this->db->set('FTXphRmk', $paData['FTXphRmk']);
            $this->db->set('FNXphStaDocAct', $paData['FNXphStaDocAct']);
            $this->db->set('FNXphStaRef', $paData['FNXphStaRef']);
            $this->db->set('FDLastUpdOn', 'GETDATE()', false);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->update('TAPTPdHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
                $this->db->set('FTAgnCode', $paData['FTAgnCode']);
                $this->db->set('FTBchCode', $paData['FTBchCode']);
                $this->db->set('FTXphDocNo', $paData['FTXphDocNo']);
                $this->db->set('FNXphDocType', $paData['FNXphDocType']);
                $this->db->set('FDXphDocDate', $paData['FDXphDocDate']);
                $this->db->set('FTShpCode', $paData['FTShpCode']);
                $this->db->set('FTXphCshOrCrd', $paData['FTXphCshOrCrd']);
                $this->db->set('FTXphVATInOrEx', $paData['FTXphVATInOrEx']);
                $this->db->set('FTDptCode', $paData['FTDptCode']);
                $this->db->set('FTWahCode', $paData['FTWahCode']);
                $this->db->set('FTUsrCode', $paData['FTUsrCode']);
                $this->db->set('FTXphApvCode', $paData['FTXphApvCode']);
                $this->db->set('FTSplCode', $paData['FTSplCode']);
                $this->db->set('FTXphRefExt', $paData['FTXphRefExt']);
                $this->db->set('FDXphRefExtDate', $paData['FDXphRefExtDate']);
                $this->db->set('FTXphRefInt', $paData['FTXphRefInt']);
                $this->db->set('FDXphRefIntDate', $paData['FDXphRefIntDate']);
                $this->db->set('FTXphRefAE', $paData['FTXphRefAE']);
                $this->db->set('FNXphDocPrint', $paData['FNXphDocPrint']);
                $this->db->set('FTRteCode', $paData['FTRteCode']);
                $this->db->set('FCXphRteFac', $paData['FCXphRteFac']);
                $this->db->set('FCXphTotal', $paData['FCXphTotal']);
                $this->db->set('FCXphTotalNV', $paData['FCXphTotalNV']);
                $this->db->set('FCXphTotalNoDis', $paData['FCXphTotalNoDis']);
                $this->db->set('FCXphTotalB4DisChgV', $paData['FCXphTotalB4DisChgV']);
                $this->db->set('FCXphTotalB4DisChgNV', $paData['FCXphTotalB4DisChgNV']);
                $this->db->set('FTXphDisChgTxt', $paData['FTXphDisChgTxt']);
                $this->db->set('FCXphDis', $paData['FCXphDis']);
                $this->db->set('FCXphChg', $paData['FCXphChg']);
                $this->db->set('FCXphTotalAfDisChgV', $paData['FCXphTotalAfDisChgV']);
                $this->db->set('FCXphTotalAfDisChgNV', $paData['FCXphTotalAfDisChgNV']);
                $this->db->set('FCXphRefAEAmt', $paData['FCXphRefAEAmt']);
                $this->db->set('FCXphAmtV', $paData['FCXphAmtV']);
                $this->db->set('FCXphAmtNV', $paData['FCXphAmtNV']);
                $this->db->set('FCXphVat', $paData['FCXphVat']);
                $this->db->set('FCXphVatable', $paData['FCXphVatable']);
                $this->db->set('FTXphWpCode', $paData['FTXphWpCode']);
                $this->db->set('FCXphWpTax', $paData['FCXphWpTax']);
                $this->db->set('FCXphGrand', $paData['FCXphGrand']);
                $this->db->set('FCXphRnd', $paData['FCXphRnd']);
                $this->db->set('FTXphGndText', $paData['FTXphGndText']);
                $this->db->set('FCXphPaid', $paData['FCXphPaid']);
                $this->db->set('FCXphLeft', $paData['FCXphLeft']);
                $this->db->set('FTXphRmk', $paData['FTXphRmk']);
                $this->db->set('FTXphStaRefund', $paData['FTXphStaRefund']);
                $this->db->set('FTXphStaDoc', $paData['FTXphStaDoc']);
                $this->db->set('FTXphStaApv', $paData['FTXphStaApv']);
                $this->db->set('FTXphStaPrcStk', $paData['FTXphStaPrcStk']);
                $this->db->set('FTXphStaPaid', $paData['FTXphStaPaid']);
                $this->db->set('FNXphStaDocAct', $paData['FNXphStaDocAct']);
                $this->db->set('FNXphStaRef', $paData['FNXphStaRef']);

                $this->db->set('FDCreateOn', 'GETDATE()', false);
                $this->db->set('FTCreateBy', $paData['FTCreateBy']);
                $this->db->set('FDLastUpdOn', 'GETDATE()', false);
                $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);

                $this->db->insert('TAPTPdHD');
                    
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }
    
    // Function : Add/Update HD Master สำหรับใบเพื่มหนี้ไม่มีสินค้า
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDAddUpdateHDNonePdt($paData = []){
        try{
            // Update Master
            $this->db->set('FTAgnCode', $paData['FTAgnCode']);
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FDXphDocDate', $paData['FDXphDocDate']);
            $this->db->set('FTShpCode', $paData['FTShpCode']);
            $this->db->set('FTXphCshOrCrd', $paData['FTXphCshOrCrd']);
            $this->db->set('FTXphVATInOrEx', $paData['FTXphVATInOrEx']);
            $this->db->set('FTWahCode', $paData['FTWahCode']);
            $this->db->set('FTSplCode', $paData['FTSplCode']);
            $this->db->set('FTXphRefExt', $paData['FTXphRefExt']);
            $this->db->set('FDXphRefExtDate', $paData['FDXphRefExtDate']);
            $this->db->set('FTXphRefInt', $paData['FTXphRefInt']);
            $this->db->set('FDXphRefIntDate', $paData['FDXphRefIntDate']);
            $this->db->set('FCXphTotal', $paData['FCXphTotal']);
            $this->db->set('FCXphTotalNV', $paData['FCXphTotalNV']);
            $this->db->set('FCXphTotalNoDis', $paData['FCXphTotalNoDis']);
            $this->db->set('FCXphTotalB4DisChgV', $paData['FCXphTotalB4DisChgV']);
            $this->db->set('FCXphTotalB4DisChgNV', $paData['FCXphTotalB4DisChgNV']);
            $this->db->set('FTXphDisChgTxt', $paData['FTXphDisChgTxt']);
            $this->db->set('FCXphDis', $paData['FCXphDis']);
            $this->db->set('FCXphChg', $paData['FCXphChg']);
            $this->db->set('FCXphTotalAfDisChgV', $paData['FCXphTotalAfDisChgV']);
            $this->db->set('FCXphTotalAfDisChgNV', $paData['FCXphTotalAfDisChgNV']);
            $this->db->set('FCXphAmtV', $paData['FCXphAmtV']);
            $this->db->set('FCXphAmtNV', $paData['FCXphAmtNV']);
            $this->db->set('FCXphVat', $paData['FCXphVat']);
            $this->db->set('FCXphVatable', $paData['FCXphVatable']);
            $this->db->set('FTXphWpCode', $paData['FTXphWpCode']);
            $this->db->set('FCXphWpTax', $paData['FCXphWpTax']);
            $this->db->set('FCXphGrand', $paData['FCXphGrand']);
            $this->db->set('FCXphRnd', $paData['FCXphRnd']);
            $this->db->set('FTXphGndText', $paData['FTXphGndText']);
            $this->db->set('FTXphRmk', $paData['FTXphRmk']);
            $this->db->set('FNXphStaDocAct', $paData['FNXphStaDocAct']);
            $this->db->set('FNXphStaRef', $paData['FNXphStaRef']);
            $this->db->set('FDLastUpdOn', 'GETDATE()', false);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->update('TAPTPdHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
                $this->db->set('FTAgnCode', $paData['FTAgnCode']);
                $this->db->set('FTBchCode', $paData['FTBchCode']);
                $this->db->set('FTXphDocNo', $paData['FTXphDocNo']);
                $this->db->set('FNXphDocType', $paData['FNXphDocType']);
                $this->db->set('FDXphDocDate', $paData['FDXphDocDate']);
                $this->db->set('FTShpCode', $paData['FTShpCode']);
                $this->db->set('FTXphCshOrCrd', $paData['FTXphCshOrCrd']);
                $this->db->set('FTXphVATInOrEx', $paData['FTXphVATInOrEx']);
                $this->db->set('FTDptCode', $paData['FTDptCode']);
                $this->db->set('FTWahCode', $paData['FTWahCode']);
                $this->db->set('FTUsrCode', $paData['FTUsrCode']);
                $this->db->set('FTXphApvCode', $paData['FTXphApvCode']);
                $this->db->set('FTSplCode', $paData['FTSplCode']);
                $this->db->set('FTXphRefExt', $paData['FTXphRefExt']);
                $this->db->set('FDXphRefExtDate', $paData['FDXphRefExtDate']);
                $this->db->set('FTXphRefInt', $paData['FTXphRefInt']);
                $this->db->set('FDXphRefIntDate', $paData['FDXphRefIntDate']);
                $this->db->set('FTXphRefAE', $paData['FTXphRefAE']);
                $this->db->set('FNXphDocPrint', $paData['FNXphDocPrint']);
                $this->db->set('FTRteCode', $paData['FTRteCode']);
                $this->db->set('FCXphRteFac', $paData['FCXphRteFac']);
                $this->db->set('FCXphTotal', $paData['FCXphTotal']);
                $this->db->set('FCXphTotalNV', $paData['FCXphTotalNV']);
                $this->db->set('FCXphTotalNoDis', $paData['FCXphTotalNoDis']);
                $this->db->set('FCXphTotalB4DisChgV', $paData['FCXphTotalB4DisChgV']);
                $this->db->set('FCXphTotalB4DisChgNV', $paData['FCXphTotalB4DisChgNV']);
                $this->db->set('FTXphDisChgTxt', $paData['FTXphDisChgTxt']);
                $this->db->set('FCXphDis', $paData['FCXphDis']);
                $this->db->set('FCXphChg', $paData['FCXphChg']);
                $this->db->set('FCXphTotalAfDisChgV', $paData['FCXphTotalAfDisChgV']);
                $this->db->set('FCXphTotalAfDisChgNV', $paData['FCXphTotalAfDisChgNV']);
                $this->db->set('FCXphRefAEAmt', $paData['FCXphRefAEAmt']);
                $this->db->set('FCXphAmtV', $paData['FCXphAmtV']);
                $this->db->set('FCXphAmtNV', $paData['FCXphAmtNV']);
                $this->db->set('FCXphVat', $paData['FCXphVat']);
                $this->db->set('FCXphVatable', $paData['FCXphVatable']);
                $this->db->set('FTXphWpCode', $paData['FTXphWpCode']);
                $this->db->set('FCXphWpTax', $paData['FCXphWpTax']);
                $this->db->set('FCXphGrand', $paData['FCXphGrand']);
                $this->db->set('FCXphRnd', $paData['FCXphRnd']);
                $this->db->set('FTXphGndText', $paData['FTXphGndText']);
                $this->db->set('FCXphPaid', $paData['FCXphPaid']);
                $this->db->set('FCXphLeft', $paData['FCXphLeft']);
                $this->db->set('FTXphRmk', $paData['FTXphRmk']);
                $this->db->set('FTXphStaRefund', $paData['FTXphStaRefund']);
                $this->db->set('FTXphStaDoc', $paData['FTXphStaDoc']);
                $this->db->set('FTXphStaApv', $paData['FTXphStaApv']);
                $this->db->set('FTXphStaPrcStk', $paData['FTXphStaPrcStk']);
                $this->db->set('FTXphStaPaid', $paData['FTXphStaPaid']);
                $this->db->set('FNXphStaDocAct', $paData['FNXphStaDocAct']);
                $this->db->set('FNXphStaRef', $paData['FNXphStaRef']);
                $this->db->set('FDCreateOn', 'GETDATE()', false);
                $this->db->set('FTCreateBy', $paData['FTCreateBy']);
                $this->db->set('FDLastUpdOn', 'GETDATE()', false);
                $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
                $this->db->insert('TAPTPdHD');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Function : Add/Update DT Master สำหรับใบเพื่มหนี้ไม่มีสินค้า
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDAddUpdateDTNonePdt($paData = []){
        try{
            // Update Master
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTPdtCode', $paData['FTPdtCode']);
            $this->db->set('FTXpdPdtName', $paData['FTXpdPdtName']);
            $this->db->set('FTXpdVatType', $paData['FTXpdVatType']);
            $this->db->set('FTVatCode', $paData['FTVatCode']);
            $this->db->set('FCXpdVatRate', $paData['FCXpdVatRate']);
            $this->db->set('FCXpdSetPrice', $paData['FCXpdSetPrice']);
            $this->db->set('FCXpdAmtB4DisChg', $paData['FCXpdAmtB4DisChg']);
            $this->db->set('FCXpdNet', $paData['FCXpdNet']);
            $this->db->set('FCXpdNetAfHD', $paData['FCXpdNetAfHD']);
            $this->db->set('FCXpdVat', $paData['FCXpdVat']);
            $this->db->set('FCXpdVatable', $paData['FCXpdVatable']);
            $this->db->set('FCXpdCostIn', $paData['FCXpdCostIn']);
            $this->db->set('FCXpdCostEx', $paData['FCXpdCostEx']);
            $this->db->set('FDLastUpdOn', 'GETDATE()', false);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->update('TAPTPdDT');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
                $this->db->set('FTBchCode', $paData['FTBchCode']);
                $this->db->set('FTXphDocNo', $paData['FTXphDocNo']);
                $this->db->set('FNXpdSeqNo', $paData['FNXpdSeqNo']);
                $this->db->set('FTPdtCode', $paData['FTPdtCode']);
                $this->db->set('FTXpdPdtName', $paData['FTXpdPdtName']);
                $this->db->set('FCXpdFactor', $paData['FCXpdFactor']);
                $this->db->set('FTXpdVatType', $paData['FTXpdVatType']);
                $this->db->set('FTVatCode', $paData['FTVatCode']);
                $this->db->set('FCXpdVatRate', $paData['FCXpdVatRate']);
                $this->db->set('FCXpdQty', $paData['FCXpdQty']);
                $this->db->set('FCXpdQtyAll', $paData['FCXpdQtyAll']);
                $this->db->set('FCXpdSetPrice', $paData['FCXpdSetPrice']);
                $this->db->set('FCXpdAmtB4DisChg', $paData['FCXpdAmtB4DisChg']);
                $this->db->set('FCXpdNet', $paData['FCXpdNet']);
                $this->db->set('FCXpdNetAfHD', $paData['FCXpdNetAfHD']);
                $this->db->set('FCXpdVat', $paData['FCXpdVat']);
                $this->db->set('FCXpdVatable', $paData['FCXpdVatable']);
                $this->db->set('FCXpdCostIn', $paData['FCXpdCostIn']);
                $this->db->set('FCXpdCostEx', $paData['FCXpdCostEx']);
                $this->db->set('FTXpdRmk', $paData['FTXpdRmk']);
                $this->db->set('FDCreateOn', 'GETDATE()', false);
                $this->db->set('FTCreateBy', $paData['FTCreateBy']);
                $this->db->set('FDLastUpdOn', 'GETDATE()', false);
                $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
                $this->db->insert('TAPTPdDT');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add DT Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit DT.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }   
    }

    // Function : Data DT สำหรับใบเพิ่มหนี้ไม่มีสินค้า
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDGetDTNonePdt($paParams = []){
        $tDocNo = $paParams['tDocNo'];
        $tSQL   = " SELECT PCDT.* FROM TAPTPdDT PCDT WHERE PCDT.FTXphDocNo = ".$this->db->escape($tDocNo)." ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){ 
            $aItems = $oQuery->row_array();
        }else{ 
            $aItems = [];
        }
        return $aItems;
    }
    
    // Function : Add/Update TAPTPdHDSpl ผู้จำหน่าย
    // Creator  : 03/03/2022 Wasin
    public function FSaMAPDAddUpdatePCHDSpl($paData = []){
        try{
            // Update TAPTPdHDSpl
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTXphDstPaid', $paData['FTXphDstPaid']);
            $this->db->set('FNXphCrTerm', $paData['FNXphCrTerm']);
            $this->db->set('FDXphDueDate', $paData['FDXphDueDate']);
            $this->db->set('FDXphBillDue', $paData['FDXphBillDue']);
            $this->db->set('FTXphCtrName', $paData['FTXphCtrName']);
            $this->db->set('FDXphTnfDate', $paData['FDXphTnfDate']);
            $this->db->set('FTXphRefTnfID', $paData['FTXphRefTnfID']);
            $this->db->set('FTXphRefVehID', $paData['FTXphRefVehID']);
            $this->db->set('FTXphRefInvNo', $paData['FTXphRefInvNo']);
            $this->db->set('FTXphQtyAndTypeUnit', $paData['FTXphQtyAndTypeUnit']);
            $this->db->set('FNXphShipAdd', $paData['FNXphShipAdd']);
            $this->db->set('FNXphTaxAdd', $paData['FNXphTaxAdd']);
            $this->db->where('FTXphDocNo', $paData['FTXphDocNo']);
            $this->db->update('TAPTPdHDSpl');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TAPTPdHDSpl Success',
                );
            }else{
                // Add TAPTPdHDSpl
                $this->db->insert('TAPTPdHDSpl',array(
                    'FTBchCode'             => $paData['FTBchCode'],
                    'FTXphDocNo'            => $paData['FTXphDocNo'],
                    'FTXphDstPaid'          => $paData['FTXphDstPaid'],
                    'FNXphCrTerm'           => $paData['FNXphCrTerm'],
                    'FDXphDueDate'          => $paData['FDXphDueDate'],
                    'FDXphBillDue'          => $paData['FDXphBillDue'],
                    'FTXphCtrName'          => $paData['FTXphCtrName'],
                    'FDXphTnfDate'          => $paData['FDXphTnfDate'],
                    'FTXphRefTnfID'         => $paData['FTXphRefTnfID'],
                    'FTXphRefVehID'         => $paData['FTXphRefVehID'],
                    'FTXphRefInvNo'         => $paData['FTXphRefInvNo'],
                    'FTXphQtyAndTypeUnit'   => $paData['FTXphQtyAndTypeUnit'],
                    'FNXphShipAdd'          => $paData['FNXphShipAdd'],
                    'FNXphTaxAdd'           => $paData['FNXphTaxAdd']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add TAPTPdHDSpl Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }
    
    // Function : Delete TCNTDocDTTmp
    // Creator  : 03/03/2022 Wasin
    public function FSxMClearPdtInTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $tSQL       = " DELETE FROM TCNTDocDTTmp WHERE FTSessionID  = ".$this->db->escape($tSessionID)." AND FTXthDocKey = 'TAPTPdHD' ";
        $this->db->query($tSQL);
    }
    
    // Function : Delete TCNTDocDTTmp
    // Creator  : 03/03/2022 Wasin
    public function FSxMClearDTDisTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $tSQL       = " DELETE FROM TCNTDocDTDisTmp WHERE FTSessionID = ".$this->db->escape($tSessionID)."";
        $this->db->query($tSQL);
    }
    
    // Function : Delete TCNTDocDTTmp
    // Creator  : 03/03/2022 Wasin
    public function FSxMClearHDDisTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $tSQL       = " DELETE FROM TCNTDocHDDisTmp WHERE FTSessionID = ".$this->db->escape($tSessionID)."";
        $this->db->query($tSQL);
    }

    // Function : Delete Inline From DT Temp
    // Creator  : 03/03/2022 Wasin
    public function FSnMAPDDelDTTmp($paData = []){
        try{
            $this->db->trans_begin();
            $this->db->where_in('FNXtdSeqNo', $paData['nSeqNo']);
            $this->db->where_in('FTSessionID', $paData['tSessionID']);
            $this->db->delete('TCNTDocDTTmp');
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus    = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus    = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Function : Multi Pdt Del Temp
    // Creator  : 09/03/2022 Wasin
    public function FSaMAPDPdtTmpMultiDel($paData = []){
        try{
            $this->db->trans_begin();
            // Del DTTmp
            $this->db->where('FTXthDocNo', $paData['tDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['nSeqNo']);
            $this->db->where('FTXthDocKey', $paData['tDocKey']);
            $this->db->delete('TCNTDocDTTmp');  
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Function : Get Data Doc By Sys Document Type
    // Creator  : 09/03/2022 Wasin
    public function FSnMAPDGetDocType($ptTableName){
        $tSQL   = "
            SELECT FNSdtDocType 
            FROM TSysDocType 
            WHERE FTSdtTblName = ".$this->db->escape($ptTableName)."
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $nDetail    = $oDetail[0]->FNSdtDocType;
        }else{
            $nDetail    = '';
        }
        return $nDetail;
    }
    
    // Function : Clear Doc DT Temp Change CDT
    // Creator  : 09/03/2022 Wasin
    public function FSxMAPDClearDocTemForChngCdt($pInforData){
        $tSQL   = "
            DELETE FROM TCNTDocDTTmp 
            WHERE FTBchCode = ".$this->db->escape($pInforData["tbrachCode"])." 
            AND FTXthDocNo  = ".$this->db->escape($pInforData["tFTXthDocNo"])."
            AND FTXthDocKey = ".$this->db->escape($pInforData["tDockey"])."
            AND FTSessionID = ".$this->db->escape($pInforData["tSession"])."
        ";
        $this->db->query($tSQL);
    }
    
    // Function : ตรวจสอบเลขที่เอกสารว่ามีการใช้ไปแล้วหรือไม่
    // Creator  : 09/03/2022 Wasin
    public function FSnMAPDCheckDuplicate($ptCode){
        $tSQL   = " 
            SELECT COUNT(FTXphDocNo)AS counts
            FROM TAPTPdHD
            WHERE FTXphDocNo = ".$this->db->escape($ptCode)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    // Function : Delete Document by DocNo
    // Creator  : 09/03/2022 Wasin
    public function FSaMAPDDelMaster($paParams){
        try{
            $tDocNo = $paParams['tDocNo'];

            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPdHD');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPdDT');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPdHDDis');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPdDTDis');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPdHDSpl');

            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TAPTPdHDDocRef');

            $this->db->where('FTXshRefDocNo',$tDocNo);
            $this->db->delete('TAPTPiHDDocRef');
            
        }catch(Exception $Error){
            return $Error;
        }
    }    

    // Function : ทุกครั้งที่เปลี่ยน SPL จะส่งผล ให้เกิดการคำนวณ VAT ใหม่
    // Creator  : 09/03/2022 Wasin
    public function FSaMCNChangeSPLAffectNewVAT($paData){
        $this->db->set('FTVatCode', $paData['FTVatCode']);
        $this->db->set('FCXtdVatRate', $paData['FCXtdVatRate']);
        $this->db->where('FTSessionID',$paData['tSessionID']);
        $this->db->where('FTXthDocKey',$paData['tDocKey']);
        $this->db->where('FTBchCode',$paData['tBCHCode']);
        $this->db->update('TCNTDocDTTmp');
    }

    // Function : Call Ref Int Doc Data Table [ PI HD ]
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefPIIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tAPDRefIntBchCode      = $aAdvanceSearch['tAPDRefIntBchCode'];
        $tAPDRefIntDocNo        = $aAdvanceSearch['tAPDRefIntDocNo'];
        $tAPDRefIntDocDateFrm   = $aAdvanceSearch['tAPDRefIntDocDateFrm'];
        $tAPDRefIntDocDateTo    = $aAdvanceSearch['tAPDRefIntDocDateTo'];
        $tAPDRefIntStaDoc       = $aAdvanceSearch['tAPDRefIntStaDoc'];
        $tAPDSplCode            = $aAdvanceSearch['tAPDSplCode'];

        $tSQLMain   = "
            SELECT
                PIHD.FTBchCode,
                BCHL.FTBchName,
                PIHD.FTXphDocNo,
                CONVERT(CHAR(10),PIHD.FDXphDocDate,121) AS FDXphDocDate,
                CONVERT(CHAR(5), PIHD.FDXphDocDate,108) AS FTXshDocTime,
                PIHD.FTXphStaDoc,
                PIHD.FTXphStaApv,
                PIHD.FNXphStaRef,
                PIHD.FTSplCode,
                SPL_L.FTSplName,
                PIHD.FTXphVATInOrEx,
                PIHD.FTXphCshOrCrd,
                SPL.FNXphCrTerm,
                SPL.FTXphDstPaid,
                CONVERT(CHAR(10),SPL.FDXphDueDate,121) AS FDXphDueDate,
                SPL.FTXphCtrName,
                SPL.FTXphRefTnfID,
                CONVERT(CHAR(10),SPL.FDXphTnfDate,121) AS FDXphTnfDate,
                SPL.FTXphRefVehID,
                PIHD.FTCreateBy,
                PIHD.FDCreateOn,
                PIHD.FNXphStaDocAct,
                USRL.FTUsrName      AS FTCreateByName,
                PIHD.FTXphApvCode,
                WAH_L.FTWahCode,
                WAH_L.FTWahName,
                PIHD.FNXphDocType,
                PIHDREF.FTXshRefType,
                CONVERT(CHAR(10),PIHDREF.FDXshRefDocDate,121) AS FDXshRefDocDate
            FROM TAPTPiHD           PIHD    WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON PIHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON PIHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON PIHD.FTSplCode     = SPL_L.FTSplCode   AND SPL_L.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON PIHD.FTBchCode     = WAH_L.FTBchCode   AND PIHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TAPTPiHDSpl  SPL      WITH (NOLOCK) ON PIHD.FTXphDocNo    = SPL.FTXphDocNo 
            LEFT JOIN TAPTPiHDDocRef PIHDREF WITH (NOLOCK) ON PIHD.FTXphDocNo   = PIHDREF.FTXshDocNo
            WHERE PIHD.FNXphStaRef != 2 AND PIHD.FTXphStaDoc = 1 AND PIHD.FTXphStaApv = 1
        ";
        $tSQLSta = "";

        if(isset($tAPDRefIntBchCode) && !empty($tAPDRefIntBchCode)){
            $tSQLSta    .= " AND (c.FTBchCode = ".$this->db->escape($tAPDRefIntBchCode).")";
        }

        if(isset($tAPDRefIntDocNo) && !empty($tAPDRefIntDocNo)){
            $tSQLSta    .= " AND (c.FTXphDocNo LIKE '%".$this->db->escape_like_str($tAPDRefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tAPDRefIntDocDateFrm) && !empty($tAPDRefIntDocDateTo)){
            $tSQLSta    .= " 
                AND (
                    (c.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateFrm." 00:00:00").")   AND CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateTo." 23:59:59").")) 
                    OR (c.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateTo." 23:00:00").") AND CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateFrm." 00:00:00")."))
                )
            ";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tAPDRefIntStaDoc) && !empty($tAPDRefIntStaDoc)){
            if ($tAPDRefIntStaDoc == 3) {
                $tSQLSta    .= " AND c.FTXphStaDoc  = ".$this->db->escape($tAPDRefIntStaDoc)."";
            } elseif ($tAPDRefIntStaDoc == 2) {
                $tSQLSta    .= " AND ISNULL(c.FTXphStaApv,'') = '' AND c.FTXphStaDoc != '3'";
            } elseif ($tAPDRefIntStaDoc == 1) {
                $tSQLSta    .= " AND c.FTXphStaApv  = ".$this->db->escape($tAPDRefIntStaDoc)."";
            }
        }

        $tSQL   =   "   
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM (
                    $tSQLMain
                ) Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."
                $tSQLSta
        ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
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

    // Function : Call Ref Int Doc Data Table [ TXO HD ]
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefTXOIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tAPDRefIntBchCode      = $aAdvanceSearch['tAPDRefIntBchCode'];
        $tAPDRefIntDocNo        = $aAdvanceSearch['tAPDRefIntDocNo'];
        $tAPDRefIntDocDateFrm   = $aAdvanceSearch['tAPDRefIntDocDateFrm'];
        $tAPDRefIntDocDateTo    = $aAdvanceSearch['tAPDRefIntDocDateTo'];
        $tAPDRefIntStaDoc       = $aAdvanceSearch['tAPDRefIntStaDoc'];
        $tAPDSplCode            = $aAdvanceSearch['tAPDSplCode'];
        $tSQLMain   = "
            SELECT 
                TWIHD.FTBchCode,
                BCHL.FTBchName,
                TWIHD.FTXthDocNo,
                CONVERT(CHAR(10),TWIHD.FDXthDocDate,121) AS FDXphDocDate,
                CONVERT(CHAR(5), TWIHD.FDXthDocDate,108) AS FTXshDocTime,
                TWIHD.FTXthStaDoc,
                TWIHD.FTXthStaApv,
                TWIHD.FNXthStaRef,
                TWIHD.FTSplCode,
                SPL_L.FTSplName,
                TWIHD.FTXthVATInOrEx,
                1       AS FTXthCshOrCrd,
                0       AS FNXthCrTerm,
                NULL	AS FTXthDstPaid,
                NULL	AS FDXthDueDate,
                NULL    AS FTXthCtrName,
                NULL    AS FTXthRefTnfID,
                NULL    AS FDXthTnfDate,
                NULL    AS FTXphRefVehID,
                TWIHD.FTCreateBy,
                TWIHD.FDCreateOn,
                TWIHD.FNXthStaDocAct,
                USRL.FTUsrName	AS FTCreateByName,
                TWIHD.FTXthApvCode,
                WAH_L.FTWahCode,
                WAH_L.FTWahName,
                TWIHD.FNXthDocType,
                1 AS FTXshRefType,
                CONVERT(CHAR(10),TWIHDREF.FDXthTnfDate,121) AS FDXshRefDocDate
            FROM TCNTPdtTwiHD TWIHD WITH(NOLOCK)
            LEFT JOIN TCNMBranch_L      BCHL        WITH(NOLOCK)    ON TWIHD.FTBchCode  = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK)   ON TWIHD.FTCreateBy	= USRL.FTUsrCode 	AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMSpl_L         SPL_L       WITH (NOLOCK)   ON TWIHD.FTSplCode	= SPL_L.FTSplCode	AND SPL_L.FNLngID   = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L     WAH_L       WITH (NOLOCK)   ON TWIHD.FTBchCode	= WAH_L.FTBchCode	AND TWIHD.FTXthWhTo = WAH_L.FTWahCode AND WAH_L.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNTPdtTwiHDRef   TWIHDREF    WITH(NOLOCK)    ON TWIHD.FTXthDocNo = TWIHDREF.FTXthDocNo 
            WHERE TWIHD.FNXthStaRef != 2 AND TWIHD.FTXthStaDoc = 1 AND TWIHD.FTXthStaApv = 1
        ";
        $tSQLSta = "";
        if(isset($tAPDRefIntBchCode) && !empty($tAPDRefIntBchCode)){
            $tSQLSta    .= " AND (c.FTBchCode = ".$this->db->escape($tAPDRefIntBchCode).")";
        }

        if(isset($tAPDRefIntDocNo) && !empty($tAPDRefIntDocNo)){
            $tSQLSta    .= " AND (c.FTXthDocNo LIKE '%".$this->db->escape_like_str($tAPDRefIntDocNo)."%')";
        }   

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tAPDRefIntDocDateFrm) && !empty($tAPDRefIntDocDateTo)){
            $tSQLSta    .= " 
                AND (
                    (c.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateFrm." 00:00:00").")   AND CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateTo." 23:59:59").")) 
                    OR (c.FDXphDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateTo." 23:00:00").") AND CONVERT(datetime,".$this->db->escape($tAPDRefIntDocDateFrm." 00:00:00")."))
                )
            ";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tAPDRefIntStaDoc) && !empty($tAPDRefIntStaDoc)){
            if ($tAPDRefIntStaDoc == 3) {
                $tSQLSta    .= " AND c.FTXthStaDoc  = ".$this->db->escape($tAPDRefIntStaDoc)."";
            } elseif ($tAPDRefIntStaDoc == 2) {
                $tSQLSta    .= " AND ISNULL(c.FTXthStaApv,'') = '' AND c.FTXthStaDoc != '3'";
            } elseif ($tAPDRefIntStaDoc == 1) {
                $tSQLSta    .= " AND c.FTXthStaApv  = ".$this->db->escape($tAPDRefIntStaDoc)."";
            }
        }

        $tSQL   =   "   
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXthDocNo DESC ) AS FNRowID,* FROM (
                    $tSQLMain
                ) Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."
                $tSQLSta
        ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
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


    // Function : Get Data Purchase Order DT List
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefPIIntDocDTDataTable($paData){
        $nLngID     =  $paData['FNLngID'];
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = " 
            SELECT
                DT.FTBchCode,
                DT.FTXphDocNo,
                DT.FNXpdSeqNo,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FCXpdQty AS FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FTXpdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy
            FROM TAPTPiDT DT WITH(NOLOCK)
            WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXphDocNo = ".$this->db->escape($tDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult    = [
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            ];
        }else{
            $aResult    = [
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            ];
        }
        unset($oQuery);
        return $aResult;
    }

    // Function : Get Data Purchase Order DT List
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefTWOIntDocDTDataTable($paData){
        $nLngID     =  $paData['FNLngID'];
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = "
            SELECT
                DT.FTBchCode,
                DT.FTXthDocNo,
                DT.FNXtdSeqNo,
                DT.FTPdtCode,
                DT.FTXtdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXtdFactor,
                DT.FTXtdBarCode,
                DT.FCXtdQty AS FCXtdQty,
                DT.FCXtdQtyAll,
                DT.FTXtdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy 
            FROM TCNTPdtTwiDT DT WITH ( NOLOCK )
            WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXthDocNo = ".$this->db->escape($tDocNo)."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult    = [
                'raItems'   => $oDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            ];
        }else{
            $aResult    = [
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            ];
        }
        unset($oQuery);
        return $aResult;
    }










    // Function : นำข้อมูล PI จาก Browse ลง DTTemp
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefIntDocInsertDTToTemp($paData, $ptDocType){
        $this->db->trans_begin();

        $tAPDDocNo          = $paData['tAPDDocNo'];
        $tAPDFrmBchCode     = $paData['tAPDFrmBchCode'];
        $tAPDOptionAddPdt   = $paData['tAPDOptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tSessionID         = $this->session->userdata('tSesSessionID');
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';

        $oQueryCheckTempDocType = $this->FSnMAPDCheckTempDocType($paData);
        if ($oQueryCheckTempDocType['raItems'] == '') {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tAPDDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TCNTDocDTTmp');
        }else if ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tAPDDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TCNTDocDTTmp');
            //ลบรายการอ้างอิง
            $tClearDocDocRefTemp    =   "
                DELETE FROM TCNTDocHDRefTmp
                WHERE  TCNTDocHDRefTmp.FTXthDocNo   = ".$this->db->escape($paData['tAPDDocNo'])."
                AND TCNTDocHDRefTmp.FTSessionID     = ".$this->db->escape($paData['tSessionID'])."
            ";
            $this->db->query($tClearDocDocRefTemp);
        }

        $tSQL    = "  
            INSERT TCNTDocDTTmp (
                FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor,
                FTXtdBarCode, FTSrnCode, FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt, FCXtdDis, FCXtdChg,
                FCXtdNet, FCXtdNetAfHD, FCXtdVat, FCXtdVatable, FCXtdWhtAmt, FTXtdWhtCode, FCXtdWhtRate, FCXtdCostIn,
                FCXtdCostEx, FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis, FNXtdPdtLevel, FTXtdPdtParent,
                FCXtdQtySet, FTXtdPdtStaSet, FTXtdRmk, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy, FTXthDocKey, FTSessionID
            )
            SELECT
                '$tAPDFrmBchCode' AS FTBchCode,
                '$tAPDDocNo' AS FTXphDocNo,
                DT.FNXpdSeqNo,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                DT.FTSrnCode,
                DT.FTXpdVatType,
                DT.FTVatCode,
                DT.FCXpdVatRate,
                DT.FTXpdSaleType,
                DT.FCXpdSalePrice,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FCXpdSetPrice,
                DT.FCXpdAmtB4DisChg,
                DT.FTXpdDisChgTxt,
                DT.FCXpdDis,
                DT.FCXpdChg,
                DT.FCXpdNet,
                DT.FCXpdNetAfHD,
                DT.FCXpdVat,
                DT.FCXpdVatable,
                DT.FCXpdWhtAmt,
                DT.FTXpdWhtCode,
                DT.FCXpdWhtRate,
                DT.FCXpdCostIn,
                DT.FCXpdCostEx,
                DT.FCXpdQtyLef,
                DT.FCXpdQtyRfn,
                DT.FTXpdStaPrcStk,
                DT.FTXpdStaAlwDis,
                DT.FNXpdPdtLevel,
                DT.FTXpdPdtParent,
                DT.FCXpdQtySet,
                DT.FTPdtStaSet,
                DT.FTXpdRmk, 
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                'TAPTPdHD' AS FTXthDocKey,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
            FROM TAPTPiDT DT WITH (NOLOCK)
            LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
            WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo
        ";
        $oQuery = $this->db->query($tSQL);

        // ส่วนลดรายการ
        $tSQLDTDis = "   
            INSERT TCNTDocDTDisTmp (FTBchCode, FTXthDocNo, FNXtdSeqNo, FDXtdDateIns, FNXtdStaDis, FTXtdDisChgTxt, FTXtdDisChgType, FCXtdNet, FCXtdValue, FTSessionID)
        ";
        $tSQLDTDis .= "  
            SELECT 
                DTDIS.FTBchCode,
                '$tAPDDocNo' AS FTXthDocNo,
                DTDIS.FNXpdSeqNo AS FNXpdSeqNo,
                DTDIS.FDXpdDateIns,
                DTDIS.FNXpdStaDis,
                DTDIS.FTXpdDisChgTxt,
                DTDIS.FTXpdDisChgType,
                DTDIS.FCXpdNet,
                DTDIS.FCXpdValue,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
            FROM TAPTPiDTDis DTDIS WITH (NOLOCK)
            WHERE DTDIS.FTXphDocNo  = ".$this->db->escape($tRefIntDocNo)." AND DTDIS.FNXpdSeqNo IN $aSeqNo
            ORDER BY DTDIS.FNXpdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQLDTDis);

        // ส่วนลดท้ายบิล
        $tSQLHDDis = "   
            INSERT TCNTDocHDDisTmp (
                FTBchCode, FTXthDocNo, FDXtdDateIns, FTXtdDisChgTxt, FTXtdDisChgType, FCXtdTotalAfDisChg, FCXtdTotalB4DisChg, 
                FCXtdDisChg, FCXtdAmt, FDLastUpdOn, FDCreateOn, FTLastUpdBy, FTCreateBy, FTSessionID
            )
        ";
        $tSQLHDDis .= "  
            SELECT 
                HDDIS.FTBchCode, 
                '$tAPDDocNo' AS FTXthDocNo,
                HDDIS.FDXphDateIns AS FDXtdDateIns,
                HDDIS.FTXphDisChgTxt AS FTXtdDisChgTxt,
                HDDIS.FTXphDisChgType AS FTXtdDisChgType,
                HDDIS.FCXphTotalAfDisChg AS FCXtdTotalAfDisChg,
                0 AS FCXtdTotalB4DisChg,
                HDDIS.FCXphDisChg AS FCXtdDisChg,
                HDDIS.FCXphAmt AS FCXtdAmt,
                '' AS FDLastUpdOn,
                '' AS FDCreateOn,
                '' AS FTLastUpdBy,
                '' AS FTCreateBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
            FROM TAPTPiHDDis HDDIS WITH (NOLOCK)
            WHERE HDDIS.FTXphDocNo  = ".$this->db->escape($tRefIntDocNo)."
            ORDER BY HDDIS.FDXphDateIns ASC
        ";
        $oQuery = $this->db->query($tSQLHDDis);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aResult = array(
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }else{
            $this->db->trans_commit();
            $aResult    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }
        unset($oQuery);
        return $aResult;

    }

    // Function : นำข้อมูล TWO จาก Browse ลง DTTemp
    // Creator  : 09/03/2022 Wasin
    public function FSoMAPDCallRefIntTWODocInsertDTToTemp($paData, $ptDocType){
        $this->db->trans_begin();
        $tAPDDocNo          = $paData['tAPDDocNo'];
        $tAPDFrmBchCode     = $paData['tAPDFrmBchCode'];
        $tAPDOptionAddPdt   = $paData['tAPDOptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tSessionID         = $this->session->userdata('tSesSessionID');
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';

        $oQueryCheckTempDocType = $this->FSnMAPDCheckTempDocType($paData);
        if ($oQueryCheckTempDocType['raItems'] == '') {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tAPDDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TCNTDocDTTmp');
        }else if ($oQueryCheckTempDocType['raItems'][0]['FTXthRefKey'] != $ptDocType) {
            //ลบรายการสินค้า
            $this->db->where('FTXthDocNo',$tAPDDocNo);
            $this->db->where('FTSessionID',$paData['tSessionID']);
            $this->db->delete('TCNTDocDTTmp');
            //ลบรายการอ้างอิง
            $tClearDocDocRefTemp    =   "
                DELETE FROM TCNTDocHDRefTmp
                WHERE  TCNTDocHDRefTmp.FTXthDocNo   = ".$this->db->escape($paData['tAPDDocNo'])."
                AND TCNTDocHDRefTmp.FTSessionID     = ".$this->db->escape($paData['tSessionID'])."
            ";
            $this->db->query($tClearDocDocRefTemp);
        }

        $tSQL   = "
            INSERT TCNTDocDTTmp (
                FTBchCode, FTXthDocNo, FNXtdSeqNo, FTPdtCode, FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor,
                FTXtdBarCode, FTSrnCode, FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt, FCXtdDis, FCXtdChg,
                FCXtdNet, FCXtdNetAfHD, FCXtdVat, FCXtdVatable, FCXtdWhtAmt, FTXtdWhtCode, FCXtdWhtRate, FCXtdCostIn,
                FCXtdCostEx, FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis, FNXtdPdtLevel, FTXtdPdtParent,
                FCXtdQtySet, FTXtdPdtStaSet, FTXtdRmk, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy, FTXthDocKey, FTSessionID
            )
            SELECT 
                '$tAPDFrmBchCode'   AS FTBchCode,
                '$tAPDDocNo'	    AS FTXthDocNo,
                DT.FNXtdSeqNo,
                DT.FTPdtCode,
                DT.FTXtdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXtdFactor,
                DT.FTXtdBarCode,
                NULL AS FTSrnCode,
                DT.FTXtdVatType,
                DT.FTVatCode,
                DT.FCXtdVatRate,
                1 AS FTXtdSaleType,
                DT.FCXtdNet AS FCXtdSalePrice,
                DT.FCXtdQty,
                DT.FCXtdQtyAll,
                DT.FCXtdSetPrice,
                DT.FCXtdAmt AS FCXtdAmtB4DisChg,
                NULL AS FTXtdDisChgTxt,
                NULL AS FCXtdDis,
                NULL AS FCXtdChg,
                DT.FCXtdNet,
                DT.FCXtdNet AS FCXtdNetAfHD,
                DT.FCXtdVat,
                DT.FCXtdVatable,
                NULL AS FCXtdWhtAmt,
                NULL AS FTXtdWhtCode,
                NULL AS FCXtdWhtRate,
                DT.FCXtdCostIn,
                DT.FCXtdCostEx,
                NULL AS FCXtdQtyLef,
                NULL AS FCXtdQtyRfn,
                PDT.FTPdtStaSetPrcStk AS FTXtdStaPrcStk,
                PDT.FTPdtStaAlwDis,
                DT.FNXtdPdtLevel,
                DT.FTXtdPdtParent,
                DT.FCXtdQtySet,
                DT.FTXtdPdtStaSet,
                DT.FTXtdRmk,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                'TAPTPdHD' AS FTXthDocKey,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID
            FROM TCNTPdtTwiDT DT WITH ( NOLOCK )
            LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
            WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXthDocNo ='$tRefIntDocNo' AND DT.FNXtdSeqNo IN $aSeqNo
        ";
        $oQuery = $this->db->query($tSQL);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aResult = array(
                'rnAllRow'  => 0,
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }else{
            $this->db->trans_commit();
            $aResult    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }
        unset($oQuery);
        return $aResult;

    }





    public function FSnMAPDCheckTempDocType($paData){
        $tSQL   = " SELECT
                        Tmp.FTXthRefKey
                    FROM TCNTDocHDRefTmp Tmp WITH(NOLOCK)
                    WHERE Tmp.FTXthDocNo = ".$this->db->escape($paData['tAPDDocNo'])." 
                    AND Tmp.FTXthDocKey = ".$this->db->escape($paData['tDocKey'])."
                    AND Tmp.FTSessionID = ".$this->db->escape($paData['tSessionID'])."
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'raItems'       => '',
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }
    











    // Function : Add Update Ref Doc HD
    // Creator  : 09/03/2022 Wasin
    public function FSaMAPDAddUpdateRefDocHD($aDataWhereDocRefCDN, $aDataWhereDocRefIV, $aDataWhereDocRefCDNExt){
        try { 
            $tTableRefPi    = 'TAPTPiHDDocRef';
            $tTableRefPc    = 'TAPTPdHDDocRef';
            if ($aDataWhereDocRefCDN != '') {
                $nChhkDataDocRefCDN = $this->FSaMDOChkRefDupicate($tTableRefPc, $aDataWhereDocRefCDN);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefCDN['rtCode']) && $nChhkDataDocRefCDN['rtCode'] == 1){
                    //ลบ
                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefCDN['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefCDN['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$aDataWhereDocRefCDN['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefCDN['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefCDN['FTXshRefDocNo']);
                    $this->db->delete($tTableRefPc);
                    $this->db->last_query();
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefPc,$aDataWhereDocRefCDN);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefPc,$aDataWhereDocRefCDN);
                }    
            }

            if ($aDataWhereDocRefIV != '') {
                $nChhkDataDocRefPi  = $this->FSaMDOChkRefDupicate($tTableRefPi, $aDataWhereDocRefIV);
                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefPi['rtCode']) && $nChhkDataDocRefPi['rtCode'] == 1){
                    //ลบ
                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefIV['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefIV['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$aDataWhereDocRefIV['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefIV['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefIV['FTXshRefDocNo']);
                    $this->db->delete($tTableRefPi);
    
                    //เพิ่มใหม่
                    $this->db->insert($tTableRefPi,$aDataWhereDocRefIV);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefPi,$aDataWhereDocRefIV);
                }    
            }
           
            if ($aDataWhereDocRefCDNExt != '') {
                $nChhkDataDocRefExt  = $this->FSaMDOChkRefDupicate($tTableRefPc, $aDataWhereDocRefCDNExt);

                //หากพบว่าซ้ำ
                if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                    //ลบ
                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefCDNExt['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefCDNExt['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$aDataWhereDocRefCDNExt['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefCDNExt['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefCDNExt['FTXshRefDocNo']);
                    $this->db->delete($tTableRefPc);

                    //เพิ่มใหม่
                    $this->db->insert($tTableRefPc,$aDataWhereDocRefCDNExt);
                //หากพบว่าไม่ซ้ำ
                }else{
                    $this->db->insert($tTableRefPc,$aDataWhereDocRefCDNExt);
                }
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
    
    // Function : เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    // Creator  : 09/03/2022 Wasin
    public function FSaMDOChkRefDupicate($tTableRef, $aDataWhereDocRef){
        try{
            $tBchCode       = $aDataWhereDocRef['FTBchCode'];
            $tDocNo         = $aDataWhereDocRef['FTXshDocNo'];
            $tRefDocType    = $aDataWhereDocRef['FTXshRefType'];
            $tRefDocNo      = $aDataWhereDocRef['FTXshRefDocNo'];
            $tSQL           = "
                SELECT 
                    FTAgnCode,
                    FTBchCode,
                    FTXshDocNo
                FROM $tTableRef
                WHERE 1=1
                AND FTAgnCode     = ''
                AND FTBchCode     = ".$this->db->escape($tBchCode)."
                AND FTXshDocNo    = ".$this->db->escape($tDocNo)."
                AND FTXshRefType  = ".$this->db->escape($tRefDocType)."
                AND FTXshRefDocNo = ".$this->db->escape($tRefDocNo)."
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




    // ===============================================================================================

    // Function : แท็บค่าอ้างอิงเอกสาร - โหลด
    // Creator  : 10/03/2022 Wasin
    public function FSaMAPDGetDataHDRefTmp($paData){
        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTXshDocKey    = $paData['FTXshDocKey'];
        $FTSessionID    = $paData['FTSessionID'];
        $tSQL           = "
            SELECT 
                FTXthDocNo,
                FTXthRefDocNo,
                FTXthRefType,
                FTXthRefKey,
                FDXthRefDocDate
            FROM $tTableTmpHDRef
            WHERE FTXthDocNo    = ".$this->db->escape($FTXshDocNo)."
            AND FTXthDocKey     = ".$this->db->escape($FTXshDocKey)."
            AND FTSessionID     = ".$this->db->escape($FTSessionID)." 
        "; 
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0 ){
            $aResult    = array(
                'aItems'    => $oQuery->result_array(),
                'tCode'     => '1',
                'tDesc'     => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'     => '800',
                'tDesc'     => 'data not found.',
            );
        }
        return $aResult;
    }

    // Function : แท็บค่าอ้างอิงเอกสาร - เพิ่ม
    // Creator  : 10/03/2022 Wasin
    public function FSaMAPDAddEditHDRefTmp($paDataWhere,$paDataAddEdit){
        $tRefDocNo  = ( empty($paDataWhere['tAPDRefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tAPDRefDocNoOld'] );
        $tSQL       = " 
        SELECT FTXthRefDocNo 
        FROM TCNTDocHDRefTmp
        WHERE FTXthDocNo    = ".$this->db->escape($paDataWhere['FTXshDocNo'])."
        AND FTXthDocKey     = ".$this->db->escape($paDataWhere['FTXshDocKey'])."
        AND FTSessionID     = ".$this->db->escape($paDataWhere['FTSessionID'])."
        AND FTXthRefDocNo   = ".$this->db->escape($tRefDocNo)."
        ";
        $oQuery     = $this->db->query($tSQL);
        $this->db->trans_begin();
        if ( $oQuery->num_rows() > 0 ){
            $this->db->where('FTXthRefDocNo',$tRefDocNo);
            $this->db->where('FTXthDocNo',$paDataWhere['FTXshDocNo']);
            $this->db->where('FTXthDocKey',$paDataWhere['FTXshDocKey']);
            $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
            $this->db->update('TCNTDocHDRefTmp',$paDataAddEdit);
        }else{
            $aDataAdd   = array_merge($paDataAddEdit,array(
                'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
                'FTXthDocKey'   => $paDataWhere['FTXshDocKey'],
                'FTSessionID'   => $paDataWhere['FTSessionID'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
            ));
            $this->db->insert('TCNTDocHDRefTmp',$aDataAdd);
        }
        if ( $this->db->trans_status() === FALSE ) {
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

    // Function : แท็บค่าอ้างอิงเอกสาร - ลบ
    // Creator  : 10/03/2022 Wasin
    public function FSaMAPDDelHDDocRef($paData){
        $tPODocNo       = $paData['FTXshDocNo'];
        $tPORefDocNo    = $paData['FTXshRefDocNo'];
        $tPODocKey      = $paData['FTXshDocKey'];
        $tPOSessionID   = $paData['FTSessionID'];
        $this->db->where('FTSessionID',$tPOSessionID);
        $this->db->where('FTXthDocKey',$tPODocKey);
        $this->db->where('FTXthRefDocNo',$tPORefDocNo);
        $this->db->where('FTXthDocNo',$tPODocNo);
        $this->db->delete('TCNTDocHDRefTmp');
        if ( $this->db->trans_status() === FALSE ) {
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

    // Function : ย้ายข้อมูลจาก HDDOCREF => DOCHDREFTEMP
    // Creator  : 10/03/2022 Wasin
    public function FSxMAPDMoveHDRefToHDRefTemp($paData){
        $FTXshDocNo     = $paData['FTXthDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID',$FTSessionID);
        $this->db->delete('TCNTDocHDRefTmp');

        $tSQL    = "
            INSERT INTO TCNTDocHDRefTmp (
                FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn
            )
        ";
        $tSQL   .= "
            SELECT
                FTXshDocNo,
                FTXshRefDocNo,
                FTXshRefType,
                FTXshRefKey,
                FDXshRefDocDate,
                'TAPTPdHD'      AS FTXthDocKey,
                '$FTSessionID'  AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
            FROM TAPTPdHDDocRef
            WHERE FTXshDocNo    = ".$this->db->escape($FTXshDocNo)."
        ";
        $this->db->query($tSQL);
    }

    // Function : ย้ายข้อมูลจาก DOCHDREFTEMP => HDDOCREF
    // Creator  : 10/03/2022 Wasin
    public function FSxMAPDMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate){
        $tAgnCode       = $paDataWhere['FTAgnCode'];
        $tBchCode       = $paDataWhere['FTBchCode'];
        $tDocNo         = $paDataWhere['FTXphDocNo'];
        $tSessionID     = $this->session->userdata('tSesSessionID');
        $tTableHD       = $paTableAddUpdate['tTableHD'];


        // Update Doc Ref Doc No
        if(isset($tDocNo) && !empty($tDocNo)){
            // Delete Temp Old
            $this->db->where('FTXthDocNo',$tDocNo);
            $this->db->delete('TCNTDocHDRefTmp');

            // Update
            $this->db->where('FTSessionID',$tSessionID);
            $this->db->where('FTXthDocKey','TAPTPdHD');
            $this->db->update('TCNTDocHDRefTmp',array(
                'FTXthDocNo' => $tDocNo
            ));
        }
        
        // =============== [ MOVE DOCHDREFTMP TO TAPTPdHDDocRef ] ===============
        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where('FTAgnCode',$tAgnCode);
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TAPTPdHDDocRef');

            // ลบข้อมูลตาราง PI Doc Ref [ ใบซื้อสินค้า ]
            $this->db->where('FTXshRefDocNo',$tDocNo);
            $this->db->delete('TAPTPiHDDocRef');
            
            // ลบข้อมูลตาราง TXO Doc Ref [ ใบรับเข้า ]
            $this->db->where('FTXthRefTnfID',$tDocNo);
            $this->db->delete('TCNTPdtTwiHDRef');
            
        }

        $tSQLHDRef    =   "
            INSERT INTO TAPTPdHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate)
        ";
        $tSQLHDRef   .=  "
            SELECT
                '$tAgnCode' AS FTAgnCode,
                '$tBchCode' AS FTBchCode,
                FTXthDocNo,
                FTXthRefDocNo,
                FTXthRefType,
                FTXthRefKey,
                FDXthRefDocDate
            FROM TCNTDocHDRefTmp WITH (NOLOCK)
            WHERE 1=1
            AND FTXthDocKey     = 'TAPTPdHD'
            AND FTSessionID     = ".$this->db->escape($tSessionID)."
        ";
        if(isset($tDocNo) && !empty($tDocNo)){
            $tSQLHDRef  .= " AND FTXthDocNo = ".$this->db->escape($tDocNo)."";
        }
        $this->db->query($tSQLHDRef);

        // ######################################################## Move Insert HD ใบ PI ########################################################
        $tSQLHDPIRef    = "
            INSERT INTO TAPTPiHDDocRef ( FTAgnCode , FTBchCode , FTXshDocNo , FTXshRefDocNo , FTXshRefType , FTXshRefKey , FDXshRefDocDate )
        ";
        $tSQLHDPIRef    .= "
            SELECT 
                '$tAgnCode'     AS FTAgnCode,
                '$tBchCode'     AS FTBchCode,
                FTXthRefDocNo   AS FTXshDocNo,
                FTXthDocNo      AS FTXshRefDocNo,
                2               AS FTXshRefType,
                'PD'            AS FTXshRefKey,
                FDXthRefDocDate AS FDXshRefDocDate
            FROM TCNTDocHDRefTmp WITH(NOLOCK)
            WHERE 1=1
            AND FTXthDocKey = 'TAPTPdHD'
            AND FTSessionID = ".$this->db->escape($tSessionID)."
            AND FTXthRefKey = 'PI'
        ";
        if(isset($tDocNo) && !empty($tDocNo)){
            $tSQLHDPIRef    .= " AND FTXthDocNo = ".$this->db->escape($tDocNo)."";
        }
        $this->db->query($tSQLHDPIRef);
    }











}




