<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Purchasereturn_model extends CI_Model {

    /**
     * Functionality : Data List Product Adjust Stock HD
     * Parameters : function parameters
     * Creator :  22/05/2019 Piya
     * Last Modified : -
     * Return : Data Array
     * Return Type : Array
     */
    public function FSaMPNList($paData = []){
        $aDataUserInfo  = $this->session->userdata("tSesUsrInfo");
        $aRowLen        = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTXphDocNo DESC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        CDN.FTBchCode,
                        BCHL.FTBchName,
                        CDN.FTXphDocNo,
                        CONVERT(CHAR(10), CDN.FDXphDocDate, 103) AS FDXphDocDate,
                        CONVERT(CHAR(5), CDN.FDXphDocDate, 108)  AS FTXphDocTime,
                        CDN.FTXphStaDoc,
                        CDN.FTXphStaApv,
                        CDN.FTXphRefInt,
                        CONVERT(CHAR(10), CDN.FDXphRefIntDate, 103) AS FDXphRefIntDate,
                        CDN.FTXphStaPrcStk,
                        CDN.FTCreateBy,
                        CDN.FDCreateOn,
                        USRL.FTUsrName AS FTCreateByName,
                        CDN.FTXphApvCode,
                        USRLAPV.FTUsrName AS FTXphApvName
                    FROM TAPTPnHD CDN WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON CDN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON CDN.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                    LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON CDN.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                    WHERE 1=1
        ";

        if($this->session->userdata('tSesUsrLevel') != "HQ"){ // ??????????????????????????????????????????????????? HQ ????????????????????????????????????????????? login
            $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND CDN.FTBchCode IN (".$tBchMulti.") ";
        }
        
        $aAdvanceSearch = $paData['aAdvanceSearch'];
        
        @$tSearchList = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND ((CDN.FTXphDocNo  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (BCHL.FTBchName  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (USRL.FTUsrName  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (USRLAPV.FTUsrName  COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }

        if($this->session->userdata("tSesUsrLevel") != "HQ"){
            $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND CDN.FTBchCode IN (".$tBchMulti.") ";
            if($this->session->userdata("tSesUsrLevel")=="SHP"){
                $tSQL .= " AND CDN.FTShpCode = '".$aDataUserInfo['FTShpCode']."'";
            }
        }

        // ????????????????????? - ?????????????????????
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)){
            $tSQL .= " AND ((CDN.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (CDN.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ??????????????????????????? - ???????????????????????????
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((CDN.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (CDN.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // ?????????????????????????????????
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND CDN.FTXphStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(CDN.FTXphStaApv,'') = '' AND CDN.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND CDN.FTXphStaApv = '$tSearchStaDoc'";
            }
        }

        // ??????????????????????????????????????????????????????
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL .= " AND (CDN.FTXphStaPrcStk = '$tSearchStaPrcStk' OR ISNULL(CDN.FTXphStaPrcStk,'') = '') ";
            } else {
                $tSQL .= " AND CDN.FTXphStaPrcStk = '$tSearchStaPrcStk'";
            }
        }
        // ????????????????????????????????????????????????????????????
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND CDN.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND CDN.FNXphStaDocAct = 0";
            }
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
       
        // echo $tSQL;
        // exit();
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPNGetPageAll($paData);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); // ?????? Page All ??????????????? Rec ????????? ????????????????????????????????????
            $aResult = array(
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
    
    /**
     * Functionality : All Page Of Product Adjust Stock HD
     * Parameters : function parameters
     * Creator :  12/06/2018 Piya
     * Last Modified : -
     * Return : Data Array
     * Return Type : Array
     */
    public function FSnMPNGetPageAll($paData = []){
        $aDataUserInfo  = $this->session->userdata("tSesUsrInfo");
        $nLngID         = $paData['FNLngID'];
        $tSQL           = " SELECT 
                                COUNT (CDN.FTXphDocNo) AS counts
                            FROM TAPTPnHD CDN WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON CDN.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON CDN.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON CDN.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                            WHERE 1=1
        ";
        
        if($this->session->userdata("tSesUsrLevel") != "HQ"){
            $tBchMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND  CDN.FTBchCode IN (".$tBchMulti.")  ";
            if($this->session->userdata("tSesUsrLevel")=="SHP"){
                $tSQL .= " AND CDN.FTShpCode = '".$aDataUserInfo['FTShpCode']."'";
            }
        }

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList = $aAdvanceSearch['tSearchAll'];
        
        if(@$tSearchList != ''){
            $tSQL .= " AND ((CDN.FTXphDocNo  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (BCHL.FTBchName  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (USRL.FTUsrName  COLLATE THAI_BIN LIKE '%$tSearchList%') OR (USRLAPV.FTUsrName  COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }

        

        // ????????????????????? - ?????????????????????
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)){
            $tSQL .= " AND ((CDN.FTBchCode BETWEEN $tSearchBchCodeFrom AND $tSearchBchCodeTo) OR (CDN.FTBchCode BETWEEN $tSearchBchCodeTo AND $tSearchBchCodeFrom))";
        }

        // ??????????????????????????? - ???????????????????????????
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((CDN.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (CDN.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // ?????????????????????????????????
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND CDN.FTXphStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(CDN.FTXphStaApv,'') = '' AND CDN.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND CDN.FTXphStaApv = '$tSearchStaDoc'";
            }
        }

        // ??????????????????????????????????????????????????????
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (isset($tSearchStaPrcStk) && !empty($tSearchStaPrcStk)) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL .= " AND (CDN.FTXphStaPrcStk = '$tSearchStaPrcStk' OR ISNULL(CDN.FTXphStaPrcStk,'') = '') ";
            } else {
                $tSQL .= " AND CDN.FTXphStaPrcStk = '$tSearchStaPrcStk'";
            }
        }
        // ????????????????????????????????????????????????????????????
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND CDN.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND CDN.FNXphStaDocAct = 0";
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
    
    /**
     * Functionality : Function Get Count From Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetCountDTTemp($paDataWhere = []){
        
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
    
    /**
     * Functionality : Function Get Max Seq From Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetMaxSeqDTTemp($paDataWhere){
        
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

    /**
     * Functionality : Function Add DT Temp To DT
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertTmpToDT($paDataWhere){
        $tDocNo = $paDataWhere['tDocNo'];
        $tDocKey = $paDataWhere['tDocKey'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? DT ????????????????????????????????? DT Temp ?????? DT
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPnDT');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPnDT 
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
 
    /**
     * Functionality : Function Add DT To DT Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertDTToTemp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tDocKey = $paDataWhere['tDocKey'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? DT Temp ????????????????????????????????? DT ?????? DT Temp
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

            FROM TAPTPnDT DT WITH (NOLOCK)
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

    /**
     * Functionality : Function Add DTDis Temp To DTDis
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertTmpToDTDis($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? DTDis ????????????????????????????????? DTDis Temp ?????? DTDis
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPnDTDis');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPnDTDis 
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
                'rtDesc' => 'Add TAPTPnDTDis Success.',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add TAPTPnDTDis',
            );
        }
        return $aStatus;
    }
    
    /**
     * Functionality : Function Add DTDis To DTDis Temp
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertDTDisToTmp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? DTDis Temp ????????????????????????????????? DTDis ?????? DTDis Temp
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

            FROM TAPTPnDTDis DTDIS WITH (NOLOCK)
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
    
    /**
     * Functionality : Function Add HDDis To HDDis Temp
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertHDDisToTmp($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? HDDis Temp ????????????????????????????????? HDDis ?????? HDDis Temp
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

            FROM TAPTPnHDDis HDDIS WITH (NOLOCK)
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
    
    /**
     * Functionality : Function Add HDDis Temp To HDDis
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMPNInsertTmpToHDDis($paDataWhere = []){
        $tDocNo = $paDataWhere['tDocNo'];
        $tSessionID = $paDataWhere['tSessionID']; 
        
        // ????????????????????? ?????? DTDis ????????????????????????????????? DTDis Temp ?????? DTDis
        $this->db->where('FTXphDocNo', $tDocNo);
        $this->db->delete('TAPTPnHDDis');
        
        $tWhereDocNo = '';
        if($paDataWhere['tIsUpdatePage'] == '1'){
            $tWhereDocNo = $tDocNo;
        }
        
        $tSQL = "   
            INSERT TAPTPnHDDis
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
    
    /**
     * Functionality : Function Get Pdt From Temp
     * Parameters : function parameters
     * Creator : 22/05/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetDTTempListPage($paData = []){

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
                $oFoundRow      = $this->FSoMPNGetDTTempListPageAll($paData);
                $nFoundRow      = $oFoundRow[0]->counts;
                $nPageAll       = ceil($nFoundRow/$paData['nRow']); //?????? Page All ??????????????? Rec ????????? ????????????????????????????????????
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
    
    /**
     * Functionality : All Page Of Product Size
     * Parameters : function parameters
     * Creator :  25/06/2019 Piya
     * Return : Object Count All Product Model
     * Return Type : Object
     */
    public function FSoMPNGetDTTempListPageAll($paData = []){
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

    /**
     * Functionality : Function Get Data Pdt
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetPunCodeByBarCode($paParams = []){
        
        $tBarCode = $paParams['tBarCode'];
        $tSplCode = $paParams['tSplCode'];
        // config tCN_Cost	1 ???????????????????????????????????? ,2 ??????????????????????????????????????? ,3 ??????????????????????????????????????? ,4 ?????????????????? FIFO	
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
        
        /*===== ?????????????????????????????? ????????????????????????????????? ============================================*/
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
    
    /**
     * Functionality : Function Get Data Pdt
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetDataPdt($paData = []){

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

    /**
     * Functionality : Update DT Temp by Seq
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    function FSaMPNUpdateInlineDTTemp($aDataUpd = [], $aDataWhere = []){
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

    /**
     * Functionality : Function insert DT to Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status insert
     * Return Type : array
     */
    public function FSaMPNInsertPDTToTemp($paData = [], $paDataWhere = []){
        
        $paData = $paData['raItem'];
        if($paDataWhere['nPNOptionAddPdt'] == 1){
            // ??????????????????????????????????????????????????????????????????????????????
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
            
            if($oQuery->num_rows() > 0){ // ????????????????????????????????????????????????????????????????????????????????????????????????
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

                // ?????????????????????????????????????????????
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
                $this->db->set('FCXtdQtyAll'    , $nQty * $paData['FCPdtUnitFact']); // ????????????????????? qty * fector
                $this->db->set('FCXtdSalePrice' , $paData['FTPdtSalePrice']);
                $this->db->set('FTBchCode'      , $paDataWhere['tBchCode']);
                $this->db->set('FTXthDocNo'     , $paDataWhere['tDocNo']);
                $this->db->set('FNXtdSeqNo'     , $paDataWhere['nMaxSeqNo']);
                $this->db->set('FTXthDocKey'    , $paDataWhere['tDocKey']);
                $this->db->set('FTXtdBarCode'   , $paDataWhere['tBarCode']);
                $this->db->set('FCXtdSetPrice'  , $paDataWhere['pcPrice'] * 1); // pcPrice ??????????????????????????????????????? modal ????????? (??????????????????????????????????????????????????????????????? * fector) ????????????????????????????????????  pcPrice * rate  (rate ?????????????????????????????????????????????????????????????????? company)
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
            // ????????????????????????????????????
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
            $this->db->set('FCXtdQty', 1);  // ?????????????????????????????????????????????
            $this->db->set('FCXtdQtyAll', 1*$paData['FCPdtUnitFact']); // ????????????????????? qty * fector
            $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

            $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
            $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
            $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
            $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
            $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
            $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice ??????????????????????????????????????? modal ????????? (??????????????????????????????????????????????????????????????? * fector) ????????????????????????????????????  pcPrice * rate  (rate ?????????????????????????????????????????????????????????????????? company)
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

    /**
     * Functionality : Update DocNo in DT Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status insert
     * Return Type : array
     */
    function FSaMPNAddUpdateDocNoInDocTemp($aDataWhere = []){

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
    
    /**
     * Functionality : Cancel Doc
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status update
     * Return Type : array
     */
    public function FSaMPNCancel($paDataUpdate = []){
        try{
            // TAPTPnHD
            $this->db->set('FTXphStaDoc' , '3');
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
            $this->db->update('TAPTPnHD');

            $this->db->where('FTXshDocNo',$paDataUpdate['tDocNo']);
            $this->db->delete('TAPTPnHDDocRef');

            $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
            $this->db->delete('TAPTDoHDDocRef');

            $this->db->where('FTXshRefDocNo',$paDataUpdate['tDocNo']);
            $this->db->delete('TAPTPiHDDocRef');


            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }

    /**
     * Functionality : Approve Doc
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status update
     * Return Type : array
     */
    public function FSaMPNHavePdtApprove($paDataUpdate = []){
        try{
            // TAPTPnHD
            $this->db->set('FTXphStaPrcStk' , '1');
            $this->db->set('FTXphStaApv' , '1');
            $this->db->set('FTXphApvCode' , $paDataUpdate['tApvCode']);
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);

            $this->db->update('TAPTPnHD');
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
    
    /**
     * Functionality : Approve Doc
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Status update
     * Return Type : array
     */
    public function FSaMPNNonePdtApprove($paDataUpdate = []){
        try{
            // TAPTPnHD
            $this->db->set('FTXphStaPrcStk' , '1');
            $this->db->set('FTXphStaApv' , '1');
            $this->db->set('FTXphApvCode' , $paDataUpdate['tApvCode']);
            $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);

            $this->db->update('TAPTPnHD');
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
    
    /**
     * Functionality : Function Get Sum From Temp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNSumDTTemp($paDataWhere = []){

        $tDocNo = $paDataWhere['tDocNo'];
        $tDocKey = $paDataWhere['tDocKey'];
        $tSesSessionID = $this->session->userdata('tSesSessionID');   

        $tSQL = "   SELECT 
                        SUM(FCXtdAmt) AS FCXtdAmt
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                ";
             
            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";

            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oResult = $oQuery->result_array();
            }else{
                $oResult = '';
            }


        return $oResult;

    }

    /**
     * Functionality : Function Get Cal From HDDis Temp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piyas
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNCalInHDDisTemp($paParams = []){

        $tDocNo = $paParams['tDocNo'];
        $tDocKey = $paParams['tDocKey'];
        $tBchCode = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID']; 
        
        $tSQL = "
                    SELECT
                        /* ???????????????????????????????????????????????????????????? ==============================================================*/
                        STUFF((
                            SELECT  ',' + DOCCONCAT.FTXtdDisChgTxt
                            FROM TCNTDocHDDisTmp DOCCONCAT
                            WHERE  1=1 
                            AND DOCCONCAT.FTBchCode 		= '$tBchCode'
                            AND DOCCONCAT.FTXthDocNo		= '$tDocNo'
                            AND DOCCONCAT.FTSessionID		= '$tSessionID'
                        FOR XML PATH('')), 1, 1, '') AS FTXphDisChgTxt,
                        
                        /* ????????????????????????????????????????????? ==============================================================*/
                        SUM( 
                            CASE 
                                WHEN HDDISTMP.FTXtdDisChgType = 1 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                WHEN HDDISTMP.FTXtdDisChgType = 2 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                ELSE 0 
                            END
                        ) AS FCXphDis,
                        
                        /* ?????????????????????????????????????????????????????? ==============================================================*/
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
    
    /**
     * Functionality : Function Get Cal From DT Temp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetSplVatCode($paParams = []){
        $tSplCode = $paParams['tSplCode'];
        
        $tSQL = "   SELECT SPL.* , SPLCR.FCSplCrLimit  from TCNMSpl SPL
                    LEFT JOIN TCNMSplCredit SPLCR ON SPL.FTSplCode = SPLCR.FTSplCode
                    WHERE SPL.FTSplCode = '$tSplCode' ";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $aResult = $oQuery->row_array();
            }else{
                $aResult = '';
            }


        return $aResult;
        
    }
    
    /**
     * Functionality : Function Get Cal From DT Temp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNGetHDSpl($paParams = []){
        $tDocNo = $paParams['tDocNo'];
        
        $tSQL = "   SELECT 
                        *
                    FROM TAPTPnHDSpl WITH (NOLOCK)
                    WHERE FTXphDocNo = '$tDocNo'
                ";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $aResult = $oQuery->row_array();
            }else{
                $aResult = '';
            }


        return $aResult;
        
    }
    
    /**
     * Functionality : Function Get Cal From DT Temp
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMPNCalInDTTemp($paParams = []){

        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID'];  
        $tDataVatInOrEx = $paParams['tDataVatInOrEx']; 

        $tSQL =  " SELECT
                    /* ?????????????????? ==============================================================*/
                    SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXphTotal,

                    /* ??????????????????????????????????????????????????????????????? ==============================================================*/
                    SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNV,

                    /* ?????????????????????????????????????????????????????? ==============================================================*/
                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNoDis,

                    /* ??????????????????????????????????????????????????? ??????????????????????????? ==============================================================*/
                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgV,

                    /* ??????????????????????????????????????????????????? ???????????????????????????????????? */
                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgNV,

                    /* ???????????????????????????????????? ??????????????????????????? ==============================================================*/
                    SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgV,

                    /* ???????????????????????????????????? ???????????????????????????????????? ==============================================================*/
                    SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgNV,

                    /* ????????????????????????????????????????????? ==============================================================*/
                    (
                        CASE 
                            WHEN $tDataVatInOrEx = 1 THEN --???????????????
                                (
                                    /* ?????????????????? */
                                    SUM(DTTMP.FCXtdNet)
                                    - 
                                    /* ??????????????????????????????????????????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                                -
                                (
                                    /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                    -
                                    /* ??????????????????????????????????????????????????? ??????????????????????????? FCXphTotalAfDisChgV */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                            WHEN $tDataVatInOrEx = 2 THEN --??????????????????
                            
                                    (
                                        /* ?????????????????? */
                                        SUM(DTTMP.FCXtdNet)
                                        - 
                                        /* ??????????????????????????????????????????????????????????????? */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                    )
                                    -
                                    (
                                        /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                        -
                                        /* ??????????????????????????????????????????????????? ??????????????????????????? FCXphTotalAfDisChgV */
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

                    /* ???????????????????????????????????????????????????????????? ==============================================================*/
                    (
                        SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                        -
                        (
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                            -
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                        )
                    ) AS FCXphAmtNV,

                    /* ????????????????????? ==============================================================*/
                    SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXphVat,

                    /* ?????????????????????????????? ==============================================================*/
                    (
                        (
                            CASE 
                                WHEN $tDataVatInOrEx = 1 THEN --???????????????
                                    (
                                        /* ?????????????????? */
                                        SUM(DTTMP.FCXtdNet)
                                        - 
                                        /* ??????????????????????????????????????????????????????????????? */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                    )
                                    -
                                    (
                                        /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                ELSE 0
                                            END
                                        )
                                        -
                                        /* ??????????????????????????????????????????????????? ??????????????????????????? FCXphTotalAfDisChgV */
                                        SUM(
                                            CASE
                                                WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                ELSE 0
                                            END
                                        )
                                    )
                                WHEN $tDataVatInOrEx = 2 THEN --??????????????????
                                
                                        (
                                            /* ?????????????????? */
                                            SUM(DTTMP.FCXtdNet)
                                            - 
                                            /* ??????????????????????????????????????????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                        -
                                        (
                                            /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                            -
                                            /* ??????????????????????????????????????????????????? ??????????????????????????? FCXphTotalAfDisChgV */
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

                    /* ??????????????????????????????????????? ??? ????????????????????? ==============================================================*/
                    STUFF((
                        SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                        FROM TCNTDocDTTmp DOCCONCAT
                        WHERE  1=1 
                        AND DOCCONCAT.FTBchCode = '$tBchCode'
                        AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                        AND DOCCONCAT.FTSessionID = '$tSessionID'
                    FOR XML PATH('')), 1, 1, '') AS FTXphWpCode,

                    /* ????????????????????? ??? ????????????????????? ==============================================================*/
                    SUM(ISNULL(DTTMP.FCXtdWhtAmt, 0)) AS FCXphWpTax

                FROM TCNTDocDTTmp DTTMP
                WHERE DTTMP.FTXthDocNo  = '$tDocNo' 
                AND DTTMP.FTXthDocKey   = '$tDocKey' 
                AND DTTMP.FTSessionID   = '$tSessionID'
                AND DTTMP.FTBchCode     = '$tBchCode'
                GROUP BY DTTMP.FTSessionID ";
            
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->result_array();
        }else{
            $aResult = [];
        }
        return $aResult;
    }

    /**
     * Functionality : Search PN By ID
     * Parameters : function parameters
     * Creator : 22/05/2019 Piya
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPNGetHD($paData = []){

        $tDocNo  = $paData['FTXphDocNo'];
        $nLngID     = $paData['FNLngID'];
        
        $tSQL = "SELECT
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
                    EXRED.FTXshRefDocNo AS FTXphRefExt,
                    EXRED.FDXshRefDocDate AS FDXphRefExtDate,
                    PNRED.FTXshRefDocNo AS FTXphRefInt,
                    PNRED.FDXshRefDocDate AS FDXphRefIntDate,
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
                    
                FROM [TAPTPnHD] HD

                LEFT JOIN TCNMBranch_L      BCHLDOC ON HD.FTBchCode = BCHLDOC.FTBchCode AND BCHLDOC.FNLngID = $nLngID
                /*LEFT JOIN TCNMBranch_L      BCHLTO ON HD.FTXphBchTo = BCHLTO.FTBchCode AND BCHLTO.FNLngID = $nLngID  
                LEFT JOIN TCNMMerchant_L    MCHLTO ON HD.FFXphMerchantTo = MCHLTO.FTMerCode AND MCHLTO.FNLngID = $nLngID    
                LEFT JOIN TCNMUser_L        USRLCREATE ON HD.FTCreateBy = USRLCREATE.FTUsrCode AND USRLCREATE.FNLngID = $nLngID
                LEFT JOIN TCNMUser_L        USRLKEY ON HD.FTUsrCode = USRLKEY.FTUsrCode AND USRLKEY.FNLngID = $nLngID
                LEFT JOIN TCNMUser_L        USRAPV ON HD.FTXphApvCode = USRAPV.FTUsrCode AND USRAPV.FNLngID = $nLngID*/
                LEFT JOIN TCNMSpl_L         SPLL ON HD.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = $nLngID    
                LEFT JOIN TCNMUsrDepart_L   DPTL ON HD.FTDptCode = DPTL.FTDptCode AND DPTL.FNLngID = $nLngID
                LEFT JOIN TCNMShop_L        SHPL ON HD.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = $nLngID
                LEFT JOIN TCNMWaHouse_L     WAHL ON HD.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = $nLngID
                LEFT JOIN TAPTPnHDDocRef    PNRED ON HD.FTBchCode = PNRED.FTBchCode AND HD.FTXphDocNo = PNRED.FTXshDocNo AND PNRED.FTXshRefType = '1'
                LEFT JOIN TAPTPnHDDocRef    EXRED ON HD.FTBchCode = EXRED.FTBchCode AND HD.FTXphDocNo = EXRED.FTXshDocNo AND EXRED.FTXshRefType = '3'
                /*LEFT JOIN TCNMPosLastNo     POSVDTO WITH (NOLOCK) ON HD.FTXphPosTo = POSVDTO.FTPosCode*/   
                 
                WHERE 1=1 ";
      
        if($tDocNo != ""){
            $tSQL .= "AND HD.FTXphDocNo = '$tDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();

            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
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
     * Functionality : Function Add/Update Master ??????????????????????????????????????????????????????????????????
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : Status Add/Update Master
     * Return Type : array
     */
    public function FSaMPNAddUpdateHDHavePdt($paData = []){
        
        try{
            // Update Master
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
            $this->db->update('TAPTPnHD');
            
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
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

                $this->db->insert('TAPTPnHD');
                    
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
    
    /**
     * Functionality : Function Add/Update Master ???????????????????????????????????????????????????????????????????????????
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : Status Add/Update Master
     * Return Type : array
     */
    public function FSaMPNAddUpdateHDNonePdt($paData = []){
        
        try{
            // Update Master
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
            $this->db->update('TAPTPnHD');
            
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
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
                $this->db->insert('TAPTPnHD');
                
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

    /**
     * Functionality : Function Add/Update Master ???????????????????????????????????????????????????????????????????????????
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : Status Add/Update Master
     * Return Type : array
     */
    public function FSaMPNAddUpdateDTNonePdt($paData = []){
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
            $this->db->update('TAPTPnDT');
            
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
                $this->db->insert('TAPTPnDT');
                
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

        /**
     * Functionality : Get User Login
     * Parameters : function parameters
     * Creator : 22/05/2019 Piya
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FStPNGetUsrByCode($paParams = []){

        $nLngID = $paParams['FNLngID'];
        $tUsrLogin = $paParams['FTUsrCode'];
        
        if($this->session->userdata('tSesUsrLevel') == "HQ"){
            $tBchCode = "'" . FCNtGetBchInComp() . "'";
        }else{
            $tBchCode = "UGP.FTBchCode";
        }
        $tSQL = "SELECT BCH.FTBchCode,
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
                        /*  BCH.FTWahCode AS FTWahCode_Bch,  */
                        /*  BWAHL.FTWahName AS FTWahName_Bch  */

                FROM TCNMUser USR
                LEFT JOIN TCNMUser_L USRL ON USRL.FTUsrCode = USR.FTUsrCode AND USRL.FNLngID = $nLngID
                LEFT JOIN TCNTUsrGroup UGP ON UGP.FTUsrCode = USR.FTUsrCode
                LEFT JOIN TCNMBranch BCH ON $tBchCode = BCH.FTBchCode 
                LEFT JOIN TCNMBranch_L BCHL ON $tBchCode = BCHL.FTBchCode 
                LEFT JOIN TCNMShop SHP ON UGP.FTShpCode = SHP.FTShpCode
                LEFT JOIN TCNMShop_L SHPL ON UGP.FTShpCode = SHPL.FTShpCode AND UGP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                LEFT JOIN TCNMWaHouse WAH ON ($tBchCode = WAH.FTWahRefCode OR SHP.FTShpCode = WAH.FTWahRefCode)
                LEFT JOIN TCNMWaHouse_L WAHL ON WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = $nLngID
                LEFT JOIN TCNMMerchant_L MCHL ON SHP.FTMerCode = MCHL.FTMerCode AND  MCHL.FNLngID = $nLngID  
                LEFT JOIN TCNMUsrDepart_L DPTL ON DPTL.FTDptCode = USR.FTDptCode AND DPTL.FNLngID = $nLngID    
                WHERE USR.FTUsrCode ='".$tUsrLogin."'";
        $oQuery = $this->db->query($tSQL);
       
        if ($oQuery->num_rows() > 0){
            $oRes  = $oQuery->row_array();
            $tDataShp = $oRes;
        }else{
            $tDataShp = '';
        }

        return $tDataShp;
    }

    /**
     * Functionality : Data DT ???????????????????????????????????????????????????????????????????????????
     * Parameters : function parameters
     * Creator :  22/06/2019 Piya
     * Last Modified : -
     * Return : Data Array
     * Return Type : Array
     */
    public function FSaMPNGetDTNonePdt($paParams = []){
        $tDocNo = $paParams['tDocNo'];
        $tSQL   = " SELECT PCDT.* FROM [TAPTPnDT] PCDT WHERE PCDT.FTXphDocNo = '$tDocNo' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){ 
            $aItems = $oQuery->row_array();
        }else{ 
            $aItems = [];
        }
        return $aItems;
    }
    
    /**
     * Functionality : Function Add/Update TAPTPnHDSpl
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : Status Add/Update Master
     * Return Type : array
     */
    public function FSaMPNAddUpdatePCHDSpl($paData = []){
        
        try{
            // Update TAPTPnHDSpl
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
            $this->db->update('TAPTPnHDSpl');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TAPTPnHDSpl Success',
                );
            }else{
                // Add TAPTPnHDSpl
                $this->db->insert('TAPTPnHDSpl',array(
                    
                    'FTBchCode' => $paData['FTBchCode'],
                    'FTXphDocNo' => $paData['FTXphDocNo'],
                    'FTXphDstPaid' => $paData['FTXphDstPaid'],
                    'FNXphCrTerm' => $paData['FNXphCrTerm'],
                    'FDXphDueDate' => $paData['FDXphDueDate'],
                    'FDXphBillDue' => $paData['FDXphBillDue'],
                    'FTXphCtrName' => $paData['FTXphCtrName'],
                    'FDXphTnfDate' => $paData['FDXphTnfDate'],
                    'FTXphRefTnfID' => $paData['FTXphRefTnfID'],
                    'FTXphRefVehID' => $paData['FTXphRefVehID'],
                    'FTXphRefInvNo' => $paData['FTXphRefInvNo'],
                    'FTXphQtyAndTypeUnit' => $paData['FTXphQtyAndTypeUnit'],
                    'FNXphShipAdd' => $paData['FNXphShipAdd'],
                    'FNXphTaxAdd' => $paData['FNXphTaxAdd']

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add TAPTPnHDSpl Success',
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

        /**
     * Functionality : Function Add/Update TAPTPnHDSpl
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : Status Add/Update Master
     * Return Type : array
     */
    public function FSaMPNaAddUpdateRefDocHD($aDataWhere, $aTableAddUpdate, $aDataWhereDocRefPN, $aDataWhereDocRefOther, $aDataWhereDocRefPNExt){
        try {
            $tTableRefPN     = $aTableAddUpdate['tTableRefPN'];
            $tTableRefOther  = $aTableAddUpdate['tTableRefOther'];

            if ($aDataWhereDocRefPN != '') {
                // $nChhkDataDocRefPN  = $this->FSaMPNChkRefDupicate($aDataWhere, $tTableRefPN, $aDataWhereDocRefPN);

                //?????????????????????????????????
                // if(isset($nChhkDataDocRefPN['rtCode']) && $nChhkDataDocRefPN['rtCode'] == 1){
                    //??????

                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefPN['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefPN['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$aDataWhereDocRefPN['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefPN['FTXshRefType']);
                    // $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefPN['FTXshRefDocNo']);
                    $this->db->delete($tTableRefPN);

                    $this->db->last_query();

                    //???????????????????????????
                    $this->db->insert($tTableRefPN,$aDataWhereDocRefPN);
                //??????????????????????????????????????????
                // }else{
                //     $this->db->insert($tTableRefPN,$aDataWhereDocRefPN);
                // }
            }

            if ($aDataWhereDocRefOther != '') {
                $nChhkDataDocRefPO  = $this->FSaMPNChkRefDupicate($aDataWhere, $tTableRefOther, $aDataWhereDocRefOther);

                //?????????????????????????????????
                // if(isset($nChhkDataDocRefPO['rtCode']) && $nChhkDataDocRefPO['rtCode'] == 1){
                    //??????
                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefOther['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefOther['FTBchCode']);
                    // $this->db->where_in('FTXshDocNo',$aDataWhereDocRefOther['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefOther['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefOther['FTXshRefDocNo']);
                    $this->db->delete('TAPTPiHDDocRef');


                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefOther['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefOther['FTBchCode']);
                    // $this->db->where_in('FTXshDocNo',$aDataWhereDocRefOther['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefOther['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$aDataWhereDocRefOther['FTXshRefDocNo']);
                    $this->db->delete('TAPTDoHDDocRef');


                    //???????????????????????????
                    $this->db->insert($tTableRefOther,$aDataWhereDocRefOther);
                //??????????????????????????????????????????
                // }else{
                //     $this->db->insert($tTableRefOther,$aDataWhereDocRefOther);
                // }
            }

            if ($aDataWhereDocRefPNExt != '') {
                // $nChhkDataDocRefExt  = $this->FSaMPNChkRefDupicate($aDataWhere, $tTableRefPN, $aDataWhereDocRefPNExt);

                //?????????????????????????????????
                // if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                    //??????
                    $this->db->where_in('FTAgnCode',$aDataWhereDocRefPNExt['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$aDataWhereDocRefPNExt['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$aDataWhereDocRefPNExt['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$aDataWhereDocRefPNExt['FTXshRefType']);
                    $this->db->delete($tTableRefPN);

                    //???????????????????????????
                    $this->db->insert($tTableRefPN,$aDataWhereDocRefPNExt);
                //??????????????????????????????????????????
                // }else{
                //     $this->db->insert($tTableRefPN,$aDataWhereDocRefPNExt);
                // }
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

    //?????????????????????????????? Insert ??????????????????????????????????????? ?????????????????????????????????????????????????????????????????????????????????
    public function FSaMPNChkRefDupicate($aDataWhere, $tTableRef, $aDataWhereDocRef){
        try{

            $tAgnCode = $aDataWhereDocRef['FTAgnCode'];
            $tBchCode = $aDataWhereDocRef['FTBchCode'];
            $tDocNo   = $aDataWhereDocRef['FTXshDocNo'];
            $tRefDocType   = $aDataWhereDocRef['FTXshRefType'];
            $tRefDocNo   = $aDataWhereDocRef['FTXshRefDocNo'];

            $tSQL = "   SELECT
                            FTAgnCode,
                            FTBchCode,
                            FTXshDocNo
                        FROM $tTableRef
                        WHERE 1=1
                        AND FTAgnCode     = '$tAgnCode'
                        AND FTBchCode     = '$tBchCode'
                        AND FTXshDocNo    = '$tDocNo'
                        AND FTXshRefType  = '$tRefDocType'
                        AND FTXshRefDocNo = '$tRefDocNo'
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
    
    /**
     * Functionality : Function Delete TCNTDocDTTmp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMClearPdtInTmp(){
        $tSQL = "DELETE FROM TCNTDocDTTmp WHERE FTSessionID = '" . $this->session->userdata('tSesSessionID') . "' AND FTXthDocKey = 'TAPTPnHD'";
        $this->db->query($tSQL);
    }
    
    /**
     * Functionality : Function Delete TCNTDocDTTmp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMClearDTDisTmp(){
        $tSQL = "DELETE FROM TCNTDocDTDisTmp WHERE FTSessionID = '" . $this->session->userdata('tSesSessionID') . "'";
        $this->db->query($tSQL);
    }
    
    /**
     * Functionality : Function Delete TCNTDocDTTmp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMClearHDDisTmp(){
        $tSQL = "DELETE FROM TCNTDocHDDisTmp WHERE FTSessionID = '" . $this->session->userdata('tSesSessionID') . "'";
        $this->db->query($tSQL);
    }

    /**
     * Functionality : Delete Inline From DT Temp
     * Parameters : function parameters
     * Creator : 25/06/2019 Piya
     * Last Modified : -
     * Return : Array Status Delete
     * Return Type : array
     */
    public function FSnMPNDelDTTmp($paData = []){
        try{
            $this->db->trans_begin();
            var_dump($paData);
            // $this->db->where_in('FTXthDocNo', $paData['tDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['nSeqNo']);
            // $this->db->where_in('FTPdtCode',  $paData['tPdtCode']);
            $this->db->where_in('FTSessionID', $paData['tSessionID']);
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

    /**
     * Functionality : Multi Pdt Del Temp
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Return : Status Delete
     * Return Type : array
     */
    public function FSaMPNPdtTmpMultiDel($paData = []){
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


    public function FSnMPNGetDocType($ptTableName){

        $tSQL = "   SELECT
                        FNSdtDocType 
                    FROM TSysDocType 
                    WHERE FTSdtTblName='$ptTableName'
                ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $nDetail = $oDetail[0]->FNSdtDocType;
          
        }else{
            $nDetail = '';
        }

        return $nDetail;
       
    }
    
    public function FSxMPNClearDocTemForChngCdt($pInforData){
        $tSQL = "   DELETE FROM TCNTDocDTTmp 
                    WHERE FTBchCode = '".$pInforData["tbrachCode"]."' AND
                    FTXthDocNo = '".$pInforData["tFTXthDocNo"]."' AND
                    FTXthDocKey = '".$pInforData["tDockey"]."' AND
                    FTSessionID = '".$pInforData["tSession"]."'
                ";
        $this->db->query($tSQL);
    }
    
    /**
     * Functionality : ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
     * Parameters : function parameters
     * Creator : 28/0???/2019 Piya
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSnMPNCheckDuplicate($ptCode){
        $tSQL = "   SELECT COUNT(FTXphDocNo)AS counts
                    FROM TAPTPnHD
                    WHERE FTXphDocNo = '$ptCode'
                ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    /**
     * Functionality : Del Document by DocNo
     * Parameters : function parameters
     * Creator : 22/06/2019 Piya
     * Return : Status Delete
     * Return Type : array
     */
    public function FSaMPNDelMaster($paParams){
        try{
            $tDocNo = $paParams['tDocNo'];

            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPnHD');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPnDT');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPnHDDis');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPnDTDis');
            
            $this->db->where('FTXphDocNo', $tDocNo);
            $this->db->delete('TAPTPnHDSpl');

            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TAPTPnHDDocRef');

            $this->db->where('FTXshRefDocNo',$tDocNo);
            $this->db->delete('TAPTDoHDDocRef');

            $this->db->where('FTXshRefDocNo',$tDocNo);
            $this->db->delete('TAPTPiHDDocRef');
            
        }catch(Exception $Error){
            return $Error;
        }
    }    

    //?????????????????????????????????????????????????????? SPL ????????????????????? ????????????????????????????????????????????? VAT ????????????
    public function FSaMCNChangeSPLAffectNewVAT($paData){
        $this->db->set('FTVatCode', $paData['FTVatCode']);
        $this->db->set('FCXtdVatRate', $paData['FCXtdVatRate']);
        $this->db->where('FTSessionID',$paData['tSessionID']);
        $this->db->where('FTXthDocKey',$paData['tDocKey']);
        $this->db->where('FTBchCode',$paData['tBCHCode']);
        $this->db->update('TCNTDocDTTmp');
    }



    

    

    // Functionality: Get Data Purchase Invoice HD List
    // Parameters: function parameters
    // Creator:  19/06/2018 wasin (Yoshi AKA: Mr.JW)
    // Last Modified: -
    // Return: Data Array
    // Return Type: Array
    public function FSoMPNCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tPNRefType             = $paDataCondition['tPNTypeRef'];

        // Advance Search
        $tPNRefIntBchCode        = $aAdvanceSearch['tPNRefIntBchCode'];
        $tPNRefIntDocNo          = $aAdvanceSearch['tPNRefIntDocNo'];
        $tPNRefIntDocDateFrm     = $aAdvanceSearch['tPNRefIntDocDateFrm'];
        $tPNRefIntDocDateTo      = $aAdvanceSearch['tPNRefIntDocDateTo'];
        $tPNRefIntStaDoc         = $aAdvanceSearch['tPNRefIntStaDoc'];

        // ,
        // TOBCH_L.FTBchCode AS FTBchCodeTo,
        // TOBCH_L.FTBchName AS FTBchNameTo
        // LEFT OUTER JOIN TCNMBranch_L BCH_L WITH(NOLOCK) ON PRS.FTBchCode = BCH_L.FTBchCode AND BCH_L.FNLngID =  $nLngID  
        // LEFT OUTER JOIN TCNMBranch_L TOBCH_L WITH(NOLOCK) ON PRS.FTXphShipTo = BCH_L.FTBchCode AND TOBCH_L.FNLngID =  $nLngID 
        if($tPNRefType == '0'){
        $tSQLMain = "SELECT
                            TBPI.FTBchCode , 
                            BCH_L.FTBchName,
                            TBPI.FTXphDocNo,
                            TBPI.FNXphDocType,
                            TBPI.FDXphDocDate,
                            TBPI.FTSplCode,
                            SPL_L.FTSplName,
                            TBPI.FTXphStaApv,
                            TBPI.FTXphStaDoc,
                            TBPI.FNXphStaRef,
                            SPL.FTVatCode,
                            SPL.FTSplStaVATInOrEx,
                            VAT.FCVatRate,
                            SPLCRT.FTSplTspPaid,
                            SPLCRT.FCSplCrLimit,
                            SPLCRT.FNSplCrTerm,
                            TBPI.FTBchCode AS FTXphBchTo
                        FROM
                            TAPTPiHD TBPI WITH(NOLOCK)
                        LEFT OUTER JOIN TCNMSpl SPL WITH(NOLOCK) ON TBPI.FTSplCode = SPL.FTSplCode 
                        LEFT OUTER JOIN VCN_VatActive VAT WITH (NOLOCK) ON  SPL.FTVatCode = VAT.FTVatCode
                        LEFT OUTER JOIN TCNMSplCredit SPLCRT WITH(NOLOCK) ON TBPI.FTSplCode = SPLCRT.FTSplCode 
                        LEFT OUTER JOIN TCNMSpl_L SPL_L WITH(NOLOCK) ON TBPI.FTSplCode = SPL_L.FTSplCode AND SPL_L.FNLngID =  $nLngID  
                        LEFT OUTER JOIN TCNMBranch_L BCH_L WITH(NOLOCK) ON TBPI.FTBchCode = BCH_L.FTBchCode AND BCH_L.FNLngID =  $nLngID 
                        WHERE TBPI.FNXphStaRef != 2 AND TBPI.FTXphStaDoc = 1 AND TBPI.FTXphStaApv = 1 
                        ";

        if(isset($tPNRefIntBchCode) && !empty($tPNRefIntBchCode)){
            $tSQLMain .= " AND (TBPI.FTBchCode = '$tPNRefIntBchCode')";
        }

        if(isset($tPNRefIntDocNo) && !empty($tPNRefIntDocNo)){
            $tSQLMain .= " AND (TBPI.FTXphDocNo LIKE '%$tPNRefIntDocNo%')";
        }

        // ?????????????????????????????????????????? - ???????????????????????????
        if(!empty($tPNRefIntDocDateFrm) && !empty($tPNRefIntDocDateTo)){
            $tSQLMain .= " AND ((TBPI.FDXphDocDate BETWEEN CONVERT(datetime,'$tPNRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tPNRefIntDocDateTo 23:59:59')) OR (TBPI.FDXphDocDate BETWEEN CONVERT(datetime,'$tPNRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tPNRefIntDocDateFrm 00:00:00')))";
        }

        // ????????????????????????????????????????????????
        if(isset($tPNRefIntStaDoc) && !empty($tPNRefIntStaDoc)){
            if ($tPNRefIntStaDoc == 3) {
                $tSQLMain .= " AND TBPI.FTXphStaDoc = '$tPNRefIntStaDoc'";
            } elseif ($tPNRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(TBPI.FTXphStaApv,'') = '' AND TBPI.FTXphStaDoc != '3'";
            } elseif ($tPNRefIntStaDoc == 1) {
                $tSQLMain .= " AND TBPI.FTXphStaApv = '$tPNRefIntStaDoc'";
            }
        }
        }else{
            $tSQLMain = "SELECT
                                TBDO.FTBchCode , 
                                BCH_L.FTBchName,
                                TBDO.FTXphDocNo,
                                TBDO.FNXphDocType,
                                TBDO.FDXphDocDate,
                                TBDO.FTSplCode,
                                SPL_L.FTSplName,
                                TBDO.FTXphStaApv,
                                TBDO.FTXphStaDoc,
                                TBDO.FNXphStaRef,
                                SPL.FTVatCode,
                                SPL.FTSplStaVATInOrEx,
                                VAT.FCVatRate,
                                SPLCRT.FTSplTspPaid,
                                SPLCRT.FCSplCrLimit,
                                SPLCRT.FNSplCrTerm,
                                TBDO.FTBchCode AS FTXphBchTo
                            FROM
                                TAPTDoHD TBDO WITH(NOLOCK)
                            LEFT OUTER JOIN TCNMSpl SPL WITH(NOLOCK) ON TBDO.FTSplCode = SPL.FTSplCode 
                            LEFT OUTER JOIN VCN_VatActive VAT WITH (NOLOCK) ON  SPL.FTVatCode = VAT.FTVatCode
                            LEFT OUTER JOIN TCNMSplCredit SPLCRT WITH(NOLOCK) ON TBDO.FTSplCode = SPLCRT.FTSplCode 
                            LEFT OUTER JOIN TCNMSpl_L SPL_L WITH(NOLOCK) ON TBDO.FTSplCode = SPL_L.FTSplCode AND SPL_L.FNLngID =  $nLngID  
                            LEFT OUTER JOIN TCNMBranch_L BCH_L WITH(NOLOCK) ON TBDO.FTBchCode = BCH_L.FTBchCode AND BCH_L.FNLngID =  $nLngID 
                            LEFT JOIN TAPTDoHDDocRef DO_R   WITH (NOLOCK) ON TBDO.FTXphDocNo    = DO_R.FTXshDocNo  
                            AND TBDO.FTBchCode = DO_R.FTBchCode
                            AND TBDO.FTXphStaDoc = 1 
                            AND TBDO.FTXphStaApv = 1
                            AND DO_R.FTXshRefKey = 'PN'
                            WHERE TBDO.FNXphStaRef != 2 AND TBDO.FTXphStaDoc = 1 AND TBDO.FTXphStaApv = 1  AND ISNULL(DO_R.FTXshRefType, '') = '' 
                            ";

            if(isset($tPNRefIntBchCode) && !empty($tPNRefIntBchCode)){
                $tSQLMain .= " AND (TBDO.FTBchCode = '$tPNRefIntBchCode')";
            }

            if(isset($tPNRefIntDocNo) && !empty($tPNRefIntDocNo)){
                $tSQLMain .= " AND (TBDO.FTXphDocNo LIKE '%$tPNRefIntDocNo%')";
            }

            // ?????????????????????????????????????????? - ???????????????????????????
            if(!empty($tPNRefIntDocDateFrm) && !empty($tPNRefIntDocDateTo)){
                $tSQLMain .= " AND ((TBDO.FDXphDocDate BETWEEN CONVERT(datetime,'$tPNRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tPNRefIntDocDateTo 23:59:59')) OR (TBDO.FDXphDocDate BETWEEN CONVERT(datetime,'$tPNRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tPNRefIntDocDateFrm 00:00:00')))";
            }

            // ????????????????????????????????????????????????
            if(isset($tPNRefIntStaDoc) && !empty($tPNRefIntStaDoc)){
                if ($tPNRefIntStaDoc == 3) {
                    $tSQLMain .= " AND TBDO.FTXphStaDoc = '$tPNRefIntStaDoc'";
                } elseif ($tPNRefIntStaDoc == 2) {
                    $tSQLMain .= " AND ISNULL(TBDO.FTXphStaApv,'') = '' AND TBDO.FTXphStaDoc != '3'";
                } elseif ($tPNRefIntStaDoc == 1) {
                    $tSQLMain .= " AND TBDO.FTXphStaApv = '$tPNRefIntStaDoc'";
                }
            }
        }

        $tSQL   =   "       SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";
    // echo $tSQLMain;
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

    // Functionality: Get Data Purchase Invoice HD List
    // Parameters: function parameters
    // Creator:  19/06/2018 wasin (Yoshi AKA: Mr.JW)
    // Last Modified: -
    // Return: Data Array
    // Return Type: Array
    public function FSoMPNCallRefIntDocDTDataTable($paData){

        $nLngID         =  $paData['FNLngID'];
        $tBchCode       =  $paData['tBchCode'];
        $tDocNo         =  $paData['tDocNo'];
        $tPNRefType     = $paData['tPNTypeRef'];

        if($tPNRefType == '0'){
          $tSQL= "SELECT
                        DT.FTBchCode,
                        --DT.FTAgnCode,
                        DT.FTXphDocNo,
                        DT.FNXpdSeqNo,
                        DT.FTPdtCode,
                        DT.FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXpdFactor,
                        DT.FTXpdBarCode,
                        DT.FCXpdQty,
                        DT.FCXpdQtyAll,
                        --DT.FCXpdQtyDone,
                        DT.FTXpdRmk,
                        PRI.FCPgdPriceRet,
                        (PRI.FCPgdPriceRet * DT.FCXpdQty) AS pcTotal,
                        DT.FDLastUpdOn,
                        DT.FTLastUpdBy,
                        DT.FDCreateOn,
                        DT.FTCreateBy
                        FROM TAPTPiDT DT WITH(NOLOCK)
                        LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode
                WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tDocNo'
                ";
        }else{
            $tSQL= "SELECT
                    DT.FTBchCode,
                    --DT.FTAgnCode,
                    DT.FTXphDocNo,
                    DT.FNXpdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    --DT.FCXpdQtyDone,
                    DT.FTXpdRmk,
                    PRI.FCPgdPriceRet,
                    (PRI.FCPgdPriceRet * DT.FCXpdQty) AS pcTotal,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TAPTDoDT DT WITH(NOLOCK)
                    LEFT JOIN VCN_Price4PdtActive PRI WITH(NOLOCK) ON DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode
            WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tDocNo'
            ";
        }
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
                        'rnAllRow'      => 0,
                        'rtCode'        => '800',
                        'rtDesc'        => 'data not found',
                    );
                }
                unset($oQuery);
                return $aResult;
    }



    public function FSoMPNCallRefIntDocInsertDTToTemp($paData){

        $tPNDocNo           = $paData['tPNDocNo'];
        $tPNFrmBchCode      = $paData['tPNFrmBchCode'];
        $tCheckRefBrowse    = $paData['nCheckRefBrowse'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tPNFrmBchCode);
        $this->db->where('FTXthDocNo',$tPNDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

        if($tCheckRefBrowse == '0'){
       $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdSaleType,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
                FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
                FTXtdWhtCode,FCXtdWhtRate,FCXtdCostIn,FCXtdCostEx,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,
                FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTXtdRmk,
                FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tPNFrmBchCode' as FTBchCode,
                    '$tPNDocNo' as FTXphDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXpdSeqNo,
                    'TAPTPnHD' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    PDT.FTPdtStaVatBuy,
                    PDT.FTVatCode,
                    VAT.FCVatRate,
                    PDT.FTPdtSaleType,
                    (PRI.FCPgdPriceRet * DT.FCXpdFactor) AS FCXpdSalePrice,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    (PRI.FCPgdPriceRet * DT.FCXpdFactor) AS FCXpdSetPrice,
                    0 as FCXpdAmtB4DisChg,
                    '' as FTXpdDisChgTxt,
                    0 as FCXpdDis ,
                    0 as FCXpdChg,
                    (PRI.FCPgdPriceRet * DT.FCXpdQty) as FCXpdNet , 
                    (PRI.FCPgdPriceRet * DT.FCXpdQty) as FCXpdNetAfHD,
                    0 AS FCXpdVat,
                    0 AS FCXpdVatable,
                    0 as FCXpdWhtAmt,
                    NULL as FTXpdWhtCode,
                    0 as FCXpdWhtRate,
                    0 as FCXpdCostIn,
                    0 as FCXpdCostEx,
                    0 as FCXpdQtyLef,
                    0 as FCXpdQtyRfn,
                    '' as FTXpdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXpdPdtLevel,
                    '' as FTXpdPdtParent,
                    0 as FCXpdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXpdRmk,   
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TAPTPiDT DT WITH (NOLOCK)
                    LEFT JOIN VCN_Price4PdtActive PRI WITH (NOLOCK) ON DT.FTPdtCode = PRI.FTPdtCode AND DT.FTPunCode = PRI.FTPunCode
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    LEFT JOIN VCN_VatActive VAT WITH (NOLOCK) ON  PDT.FTVatCode = VAT.FTVatCode
                WHERE   DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo
                ";
       }else{
        $tSQL= "INSERT INTO TCNTDocDTTmp (
            FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
            FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdSaleType,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
            FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
            FTXtdWhtCode,FCXtdWhtRate,FCXtdCostIn,FCXtdCostEx,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,
            FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTXtdRmk,
            FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
            SELECT
                '$tPNFrmBchCode' as FTBchCode,
                '$tPNDocNo' as FTXphDocNo,
                ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXpdSeqNo,
                'TAPTPnHD' AS FTXthDocKey,
                DT.FTPdtCode,
                DT.FTXpdPdtName,
                DT.FTPunCode,
                DT.FTPunName,
                DT.FCXpdFactor,
                DT.FTXpdBarCode,
                PDT.FTPdtStaVatBuy,
                PDT.FTVatCode,
                VAT.FCVatRate,
                PDT.FTPdtSaleType,
                (PRI.FCPdtCostStd * DT.FCXpdFactor) AS FCXpdSalePrice,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                (PRI.FCPdtCostStd * DT.FCXpdFactor) AS FCXpdSetPrice,
                0 as FCXpdAmtB4DisChg,
                '' as FTXpdDisChgTxt,
                0 as FCXpdDis ,
                0 as FCXpdChg,
                (PRI.FCPdtCostStd * DT.FCXpdQty) as FCXpdNet , 
                (PRI.FCPdtCostStd * DT.FCXpdQty) as FCXpdNetAfHD,
                0 AS FCXpdVat,
                0 AS FCXpdVatable,
                0 as FCXpdWhtAmt,
                NULL as FTXpdWhtCode,
                0 as FCXpdWhtRate,
                0 as FCXpdCostIn,
                0 as FCXpdCostEx,
                0 as FCXpdQtyLef,
                0 as FCXpdQtyRfn,
                '' as FTXpdStaPrcStk,
                PDT.FTPdtStaAlwDis,
                0 as FNXpdPdtLevel,
                '' as FTXpdPdtParent,
                0 as FCXpdQtySet,
                '' as FTPdtStaSet,
                '' as FTXpdRmk,   
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM
                TAPTDoDT DT WITH (NOLOCK)
                LEFT JOIN VCN_ProductCost PRI WITH (NOLOCK) ON DT.FTPdtCode = PRI.FTPdtCode
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                LEFT JOIN VCN_VatActive VAT WITH (NOLOCK) ON  PDT.FTVatCode = VAT.FTVatCode
            WHERE   DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo
            ";
       }
        // echo $tSQL;
    
        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;

    }

}




