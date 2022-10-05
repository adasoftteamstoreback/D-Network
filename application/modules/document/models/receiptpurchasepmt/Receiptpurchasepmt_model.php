<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Receiptpurchasepmt_model extends CI_Model {

    // Function : ตารางข้อมูล
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTXshDocNo DESC) AS FNRowID,* FROM (
        ";
        $tSQLMain   = "
                    SELECT
                        HD.FTAgnCode,
                        AGNL.FTAgnName,
                        HD.FTBchCode,
                        BCHL.FTBchName,
                        HD.FTXshDocNo,
                        CONVERT(CHAR(10),HD.FDXshDocDate,103) AS FDXshDocDate,
                        CONVERT(CHAR(10),HD.FDXshDueDate,103) AS FDXshDueDate,
                        HD.FTSplCode,
                        SPLL.FTSplName,
                        HD.FTXshStaPaid,
                        HD.FTXshStaApv,
                        HD.FTXshStaDoc,
                        HD.FTXshStaPrcDoc,
                        HD.FTXshRmk,
                        USRL.FTUsrName AS FTCreateByName,
                        HD.FDCreateOn
                    FROM TACTPpHD HD WITH(NOLOCK)
                    LEFT JOIN TACTPpHDSpl   HDSPL	WITH (NOLOCK)   ON HD.FTBchCode     = HDSPL.FTBchCode   AND HDSPL.FTXshDocNo = HD.FTXshDocNo
                    LEFT JOIN TCNMBranch_L	BCHL	WITH (NOLOCK)   ON HD.FTBchCode     = BCHL.FTBchCode 	AND BCHL.FNLngID    = ".$this->db->escape($nLngID)." 
                    LEFT JOIN TCNMUser_L    USRL	WITH (NOLOCK)   ON HD.FTCreateBy 	= USRL.FTUsrCode 	AND USRL.FNLngID 	= ".$this->db->escape($nLngID)." 
                    LEFT JOIN TCNMSPL_L     SPLL	WITH (NOLOCK)   ON HD.FTSplCode 	= SPLL.FTSplCode 	AND SPLL.FNLngID 	= ".$this->db->escape($nLngID)." 
                    LEFT JOIN TCNMAgency_L  AGNL 	WITH(NOLOCK)    ON HD.FTAgnCode 	= AGNL.FTAgnCode	AND AGNL.FNLngID 	= ".$this->db->escape($nLngID)." 
                    WHERE HD.FDCreateOn <> ''
        ";
        // Check Filter Serch Input
        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != '') {
            $tSQLMain   .= " 
                AND (
                    (HD.FTXshDocNo      LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (BCHL.FTBchName  LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (SPLL.FTSplName  LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (AGNL.FTAgnName  LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (CONVERT(CHAR(10),HD.FDXshDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                )
            ";
        }

        // Check Branch And Shop
        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH        = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLMain   .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        /* จากสาขา - ถึงสาขา */
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQLMain   .= "
                AND (
                    (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).")
                    OR (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        /* จากผู้จำหน่าย - ถึงผู้จำหน่าย */
        $tSearchSPLCodeFrom = $aAdvanceSearch['tSearchSPLCodeFrom'];
        $tSearchSPLCodeTo   = $aAdvanceSearch['tSearchSPLCodeTo'];
        if (!empty($tSearchSPLCodeFrom) && !empty($tSearchSPLCodeTo)) {
            $tSQLMain   .= "
                AND (
                    (HDSPL.FTSplCode BETWEEN ".$this->db->escape($tSearchSPLCodeFrom)." AND ".$this->db->escape($tSearchSPLCodeTo).")
                    OR (HDSPL.FTSplCode BETWEEN ".$this->db->escape($tSearchSPLCodeFrom)." AND ".$this->db->escape($tSearchSPLCodeTo).")
                )
            ";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQLMain   .= "
                AND (
                    (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59'))
                    OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00'))
                )
            ";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            $tSQLMain   .= " AND HD.FTXshStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
        }

        $tSQL   .= $tSQLMain ;

        $tSQL   .= ") Base ) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList              = $oQuery->result();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow / $paData['nRow']);
            $aResult            = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }

        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);

        return $aResult;
    }

    // Function : ล้างข้อมูลใน temp
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPDeletePDTInTmp($tDocno = ''){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTPpDT');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTPpDTStep2');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTPpDTStep3');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

        if ($this->db->affected_rows() > 0) {
            $aStatus    = [
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            ];
        }else{
            $aStatus    = [
                'rtCode'    => '905',
                'rtDesc'    => 'cannot Delete Item.',
            ];
        }
        return $aStatus;
    }

    // Function : Get ข้อมูลเอกสารที่จะชำระ ใน Temp  [ Step 1]
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPListStep1Point1($paDataWhere){
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nLngID         = $this->session->userdata("tLangEdit");
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        $tSQL           = "
            SELECT c.* FROM (
                SELECT  ROW_NUMBER() OVER(ORDER BY FTXthDocNo ASC) AS rtRowID,* FROM (
                    SELECT
                        DOCTMP.*,
                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE FTSessionID <> ''
                    AND ISNULL(DOCTMP.FTXthDocNo,'')    = ".$this->db->escape($tDocNo)."
                    AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                    AND DOCTMP.FTSessionID  = ".$this->db->escape($tSesSessionID)." 
                )
            Base ) AS c
        ";
        $oQuery = $this->db->query($tSQL);

        $tSQL2          = "
            SELECT c.* FROM (
                SELECT  ROW_NUMBER() OVER(ORDER BY FTPdtCode ASC) AS rtRowID,* FROM (
                    SELECT
                        DOCTMP.FTPdtCode
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE FTSessionID <> ''
                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                    AND DOCTMP.FTXthDocKey  = 'TACTPpDTStep2'
                    AND DOCTMP.FTSessionID  = ".$this->db->escape($tSesSessionID)."
                )
            Base) AS c 
        ";
        $oQuery2    = $this->db->query($tSQL2);

        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->result_array();
            $aDataList2     = $oQuery2->result_array();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'Step2Item' => $aDataList2,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        return $aDataReturn;
    }

    // Function : Get Spl Address
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPGetSplAddress($ptHDSplCode = '', $pnLangID = ''){
        $nAddressVersion = FCNaHAddressFormat('TCNMSpl');

        if ($ptHDSplCode == "") {
            $aDataReturn = array();
        } else {
            $tSQL   = "
                SELECT TOP 1
                    CAD.FTSplCode, 
                    CAD.FNAddSeqNo,
                    CAD.FTAddVersion, 
                    ISNULL(CAD.FTAddV1No,'') AS FTAddV1No,
                    ISNULL(CAD.FTAddV1Soi,'') AS  FTAddV1Soi,
                    ISNULL(CAD.FTAddV1Road,'') AS FTAddV1Road,
                    ISNULL(CAD.FTAddV1Village,'') AS FTAddV1Village,
                    ISNULL(SDT.FTSudName,'') AS FTSudName,
                    ISNULL(DTS.FTDstName,'') AS FTDstName,
                    ISNULL(PVN.FTPvnName,'') AS FTPvnName,
                    CAD.FTAddV1PostCode,
                    CAD.FTAddCountry, 
                    ISNULL(CAD.FTAddV2Desc1,'') AS FTAddV2Desc1,
                    ISNULL(CAD.FTAddV2Desc2,'') AS FTAddV2Desc2,
                    SPL.FTSplTel, 
                    SPL.FTSplEmail, 
                    CAD.FTAddFax
                FROM TCNMSpl SPL WITH(NOLOCK)
                LEFT JOIN TCNMSplAddress_L CAD WITH(NOLOCK) ON SPL.FTSplCode = CAD.FTSplCode AND CAD.FTAddVersion = ".$this->db->escape($nAddressVersion)."
                LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                WHERE SPL.FTSplCode = ".$this->db->escape($ptHDSplCode)."
            ";
            $oQuery = $this->db->query($tSQL);
            if (empty($oQuery->result_array())) {
                $aDataReturn = array();
            } else {
                $aDataReturn    = $oQuery->result_array();
            }
        }
        return $aDataReturn;
    }

    // Function : ค้นหาเอกสารที่ต้องชำระกับผู้จำหน่าย [ Step 1]
    // Creator  : 23/03/2022 Wasin
    public function FSnMRPPEventFindBill($paDataDoc){
        $tSqlWhere  = "";
        

        /* จากวันที่ครบชำระ - ถึงวันที่ครบชำระ */
        $tSearchDocDateFrom = $paDataDoc['FDXphDueDateFrm'];
        $tSearchDocDateTo   = $paDataDoc['FDXphDueDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSqlWhere  .= " 
                AND (
                    (SPL.FDXphDueDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom.' 00:00:00').")  AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo.' 23:59:59').")) 
                    OR (SPL.FDXphDueDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo.' 23:00:00').") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00")."))
                )
            ";
        }

        /* เลขที่เอกสาร */
        $tSearchDocNo   = $paDataDoc['FTSearchXphDocNo'];
        if (!empty($tSearchDocNo)) {
            $tSqlWhere  .= " AND DT.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchDocNo)."%'";
        }

        /* เอกสารอ้างอิง ใบวางบิล */
        $tSearchDocRef   = $paDataDoc['FTSearchBill'];
        if (!empty($tSearchDocRef)) {
            $tSqlWhere  .= " AND DTREF.FTXshRefDocNo LIKE '%".$this->db->escape_like_str($tSearchDocRef)."%'";
        }

        $tTypeIn            = $paDataDoc['tType'];
        if ($tTypeIn == '1') {
            $tSqlWhere  .= " AND ISNULL( PBDT.FTXpdRmk, '' ) = '' ";
        }else{
            $tSqlWhere  .= " AND ISNULL( PBDT.FTXpdRmk, '' ) != '' ";
        }

        $tDataDocNo     = $paDataDoc['FTXphDocNo'];
        $tDataSplCode   = $paDataDoc['FTSplCode'];
        $tSessionID     = $paDataDoc['tSessionID'];
        $tAgnCode       = $paDataDoc['FTAgnCode'];
        $tBchCode       = $paDataDoc['FTBchCode'];
        $tDocKey        = "TACTPpDT";

        $this->db->trans_begin();

        //ลบ ใน Temp
        $this->db->where_in('FTXthDocNo', $tDataDocNo);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');
    
        /* เช็คประเภทเอกสารที่จ่ายชำระ */
        $tDocType   = $paDataDoc['tDocType'];
        switch($tDocType){
            case '1':
                // Fillter เอกสาร - ใบซื้อ
                $tSQL   = "
                    INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
                    )
                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        DTREF.FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'IV'
                    FROM TAPTPiHD DT WITH(NOLOCK)
                    LEFT JOIN TAPTPiHDDocRef    DTREF   WITH(NOLOCK) ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPiHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                    LEFT JOIN TAPTDoHDDocRef    DOREF   WITH(NOLOCK) ON DOREF.FTXshRefDocNo = DT.FTXphDocNo 
                    LEFT JOIN TAPTDoHD          DOHD    WITH(NOLOCK) ON DOREF.FTXshDocNo = DOHD.FTXphDocNo 
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1'
                    AND DT.FTSplCode    = ".$this->db->escape($tDataSplCode)."
                    AND DOHD.FTBchCode  = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."
                ";
            break;
            case '2':
                // Fillter เอกสาร - ใบลดหนี้
                $tSQL   = "
                    INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
                    )
                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        DTREF.FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'PC'
                    FROM TAPTPcHD DT WITH ( NOLOCK )
                    LEFT JOIN TAPTPcHDDocRef    DTREF   WITH(NOLOCK) ON DT.FTXphDocNo   = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPcHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo   = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1' 
                    AND DT.FTSplCode    = ".$this->db->escape($tDataSplCode)."
                    AND DT.FTBchCode    = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."
                ";
            break;
            case '3':
                // Fillter เอกสาร - ใบเพิ่มหนี้
                $tSQL   = "
                    INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
                    )
                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        ''  AS FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'PD'
                    FROM TAPTPdHD DT WITH ( NOLOCK )
                    LEFT JOIN TAPTPdHDDocRef    DTREF   WITH ( NOLOCK ) ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPdHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo       = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo  = DT.FTXphDocNo
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1' 
                    AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
                    AND DT.FTBchCode = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."
                ";
            break;
            default:
                // Fillter เอกสารทั้งหมด
                $tSQL = "
                    INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
                    )
                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        DTREF.FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'IV'
                    FROM TAPTPiHD DT WITH(NOLOCK)
                    LEFT JOIN TAPTPiHDDocRef    DTREF   WITH(NOLOCK) ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPiHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                    LEFT JOIN TAPTDoHDDocRef    DOREF   WITH(NOLOCK) ON DOREF.FTXshRefDocNo = DT.FTXphDocNo 
                    LEFT JOIN TAPTDoHD          DOHD    WITH(NOLOCK) ON DOREF.FTXshDocNo = DOHD.FTXphDocNo 
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1'
                    AND DT.FTSplCode    = ".$this->db->escape($tDataSplCode)."
                    AND DOHD.FTBchCode  = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."

                    UNION ALL

                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        DTREF.FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'PC'
                    FROM TAPTPcHD DT WITH ( NOLOCK )
                    LEFT JOIN TAPTPcHDDocRef    DTREF   WITH(NOLOCK) ON DT.FTXphDocNo   = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPcHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo   = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1' 
                    AND DT.FTSplCode    = ".$this->db->escape($tDataSplCode)."
                    AND DT.FTBchCode    = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."

                    UNION ALL

                    SELECT DISTINCT
                        DT.FTBchCode,
                        ".$this->db->escape($tDataDocNo)."  AS FTXthDocNo,
                        ".$this->db->escape($tDocKey)."     AS FTXthDocKey,
                        DT.FTXphDocNo,
                        DT.FDXphDocDate,
                        ''  AS FTXshRefDocNo,
                        SPL.FDXphDueDate,
                        DT.FCXphAmtV,
                        DT.FCXphPaid,
                        DT.FCXphLeft,
                        ".$this->db->escape($tSessionID)."  AS FTSessionID,
                        DT.FDLastUpdOn,
                        DT.FDCreateOn,
                        DT.FTLastUpdBy,
                        DT.FTCreateBy,
                        'PD'
                    FROM TAPTPdHD DT WITH ( NOLOCK )
                    LEFT JOIN TAPTPdHDDocRef    DTREF   WITH ( NOLOCK ) ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                    LEFT JOIN TAPTPdHDSpl       SPL     WITH(NOLOCK) ON DT.FTXphDocNo       = SPL.FTXphDocNo
                    LEFT JOIN TACTPbDT          PBDT    WITH(NOLOCK) ON PBDT.FTXpdRefDocNo  = DT.FTXphDocNo
                    WHERE DT.FDCreateOn <> ''
                    AND ISNULL(FTXphStaPaid,'') != '3' 
                    AND ISNULL(DT.FTXphStaApv,'') = '1' 
                    AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
                    AND DT.FTBchCode = ".$this->db->escape($tBchCode)."
                    ".$tSqlWhere."
                ";
        }
        $this->db->query($tSQL);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStaDelDoc     = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Add Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStaDelDoc     = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Add Complete.',
            );
        }
        return $aStaDelDoc;
    }

    // Function : หาชื่อผู้ติดต่อมา Default Supplier 
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPFindContact($ptSPLCode){
        $tSQL   = "
            SELECT TOP 1 
                FNCtrSeq,
                FTCtrName,
                FTCtrTel
            FROM TCNMSplContact_L 
            WHERE FTSplCode = ".$this->db->escape($ptSPLCode)." ";
        $oQuery = $this->db->query($tSQL);
        $aFind  = $oQuery->result_array();
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'rtCode'    => '1',
                'rtResult'  => $aFind[0],
                'rtDesc'    => 'Find',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }




    
    // Function : ค้นหารายละเอียดเอกสารชำระ [ Step 2]
    // Creator  : 23/03/2022 Wasin
    public function FSaMRPPListStep1Point2($paDataWhere){
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $tPdtCode       = $paDataWhere['tPdtCode'];
        $nLngID         = $this->session->userdata("tLangEdit");
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tWhereINpdt    = "";
        foreach($tPdtCode AS $aValPdt){
            $tWhereINpdt    .= "'".trim($aValPdt)."',";
        }
        $tWhereINpdt    = substr($tWhereINpdt,0,-1);

        //ลบ ใน Temp Step2
        $this->db->where_in('FTXthDocNo', $tDocNo);
        $this->db->where_in('FTSessionID', $tSesSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');

        $tSQLInsert = "
            INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable,
                FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
            )
            SELECT 
                DT.FTBchCode,
                FTXthDocNo,
                ".$this->db->escape($tDocKey)." AS FTXthDocKey,
                DT.FTPdtCode,
                DT.FDAjdDateTimeC2,
                DT.FTXtdDocNoRef,
                DT.FDAjdDateTimeC1,
                DT.FCXtdAmt,
                DT.FCXtdSetPrice,
                DT.FCXtdVatable,
                DT.FTSessionID,
                DT.FDLastUpdOn,
                DT.FDCreateOn,
                DT.FTLastUpdBy,
                DT.FTCreateBy,
                DT.FTSrnCode
            FROM TCNTDocDTTmp DT WITH(NOLOCK)
            WHERE FTSessionID   = ".$this->db->escape($tSesSessionID)." 
            AND FTXthDocNo      = ".$this->db->escape($tDocNo)." 
            AND FTXthDocKey     = 'TACTPpDT'
            AND FTPdtCode IN ($tWhereINpdt);
        ";
        $this->db->query($tSQLInsert);
        $tSQL       = "
            SELECT c.* FROM (
                SELECT  ROW_NUMBER() OVER(ORDER BY FTXthDocNo ASC) AS rtRowID,* FROM (
                    SELECT
                        DOCTMP.*,
                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE DOCTMP.FTSessionID <> ''
                    AND ISNULL(DOCTMP.FTXthDocNo,'')    = ".$this->db->escape($tDocNo)."
                    AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                    AND DOCTMP.FTSessionID  = ".$this->db->escape($tSesSessionID)."
                )
            Base) AS c 
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->result_array();
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
        return $aDataReturn;
    }

    // Function : ดึงข้อมูลราคาส่วนลด ใบลดหนี้มาทำการคำนวณ [ Step 2 ]
    // Creator  : 08/04/2022
    public function FSaMGetDataDisDocPC($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];
        $tSQL       = "
            SELECT
                DT.FTBchCode,
                DT.FTXthDocNo,
                DT.FTXthDocKey,
                DT.FTsessionID,
                SUM(DT.FCXtdAmt) AS FCXtdTotalDisPC
            FROM TCNTDocDTTmp DT WITH(NOLOCK)
            WHERE DT.FDCreateOn <> ''
            AND DT.FTBchCode 	= ".$this->db->escape($tBchCode)."
            AND DT.FTXthDocNo 	= ".$this->db->escape($tDocNo)."
            AND DT.FTXthDocKey	= ".$this->db->escape($tDocKey)."
            AND DT.FTsessionID	= ".$this->db->escape($tSessionID)."
            AND DT.FTSrnCode    = 'PC'
            GROUP BY FTBchCode,FTXthDocNo,FTXthDocKey,FTsessionID
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataReturn    = $oQuery->row_array();
        }else{
            $aDataReturn    = "";
        }
        return $aDataReturn;
    }

    // Function : ดึงข้อมูลรายการเอกสารที่ทำการเลือกโดยไม่เอาใบลดหนี้ [ Step 2 ]
    // Creator  : 08/04/2022 Wasin
    public function FSaMGetDataStep1Pont2NotPC($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];
        $tSQL   = "
            SELECT
                DT.FTBchCode,
                DT.FTXthDocNo,
                DT.FTXthDocKey,
                DT.FTPdtCode,
                DT.FDAjdDateTimeC2,
                DT.FTXtdDocNoRef,
                DT.FDAjdDateTimeC1,
                DT.FCXtdAmt         AS FCXtdInvGrand,
                DT.FCXtdSetPrice    AS FCXtdInvPaid,
                DT.FCXtdVatable     AS FCXtdInvRem,
                DT.FCXtdNet         AS FCXtdInvPay,
                DT.FTSessionID,
                DT.FDLastUpdOn,
                DT.FDCreateOn,
                DT.FTLastUpdBy,
                DT.FTCreateBy,
                DT.FTSrnCode
            FROM TCNTDocDTTmp DT WITH(NOLOCK)
            WHERE DT.FDCreateOn <> ''
            AND DT.FTBchCode    = ".$this->db->escape($tBchCode)."
            AND DT.FTXthDocNo 	= ".$this->db->escape($tDocNo)."
            AND DT.FTXthDocKey	= ".$this->db->escape($tDocKey)."
            AND DT.FTsessionID	= ".$this->db->escape($tSessionID)."
            AND DT.FTSrnCode <> 'PC'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($tBchCode);
        unset($tSplCode);
        unset($tDocNo);
        unset($tDocKey);
        unset($tSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }

    // Function : อัพเดตรายการเอกสาร DT [ Step 2 ]
    // Creator  : 08/04/2022 Wasin
    public function FSaMUpdDocStep1Point2DT($paDataWhere,$paDataUpd){
        $this->db->where('FTBchCode',$paDataWhere['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataWhere['FTXthDocNo']);
        $this->db->where('FTXthDocKey',$paDataWhere['FTXthDocKey']);
        $this->db->where('FTPdtCode',$paDataWhere['FTPdtCode']);
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocDTTmp',$paDataUpd);
    }

    // Function : ดึงข้อมูลรายการเอกสาร DT [ Step 2 ]
    // Creator  : 08/04/2022 Wasin
    public function FSaMRPPGetDataListStep1Point2($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocNo     = $paDataWhere['FTXthDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];
        $tSQL       = "
            SELECT DATAALL.* 
            FROM ( 
                SELECT
                    DT.FTBchCode,
                    DT.FTXthDocNo,
                    DT.FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FDAjdDateTimeC2,
                    DT.FTXtdDocNoRef,
                    DT.FDAjdDateTimeC1,
                    DT.FCXtdAmt         AS FCXtdInvGrand,
                    DT.FCXtdSetPrice    AS FCXtdInvPaid,
                    DT.FCXtdVatable     AS FCXtdInvRem,
                    DT.FCXtdNet         AS FCXtdInvPay,
                    DT.FTSessionID,
                    DT.FDLastUpdOn,
                    DT.FDCreateOn,
                    DT.FTLastUpdBy,
                    DT.FTCreateBy,
                    DT.FTSrnCode,
                    CONVERT(CHAR(10),DT.FDAjdDateTimeC1,23) AS DateReq,
                    CONVERT(CHAR(10),DT.FDAjdDateTimeC2,23) AS DateSplGet
                FROM TCNTDocDTTmp DT WITH(NOLOCK)
                WHERE DT.FDCreateOn <> ''
                AND DT.FTBchCode 	= ".$this->db->escape($tBchCode)."
                AND DT.FTXthDocNo 	= ".$this->db->escape($tDocNo)."
                AND DT.FTXthDocKey	= ".$this->db->escape($tDocKey)."
                AND DT.FTsessionID	= ".$this->db->escape($tSessionID)."
            ) AS DATAALL   
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($tBchCode);
        unset($tDocNo);
        unset($tDocKey);
        unset($tSessionID);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }

    // Function : ดึงข้อมูลสรุปท้ายบิล DT [ Step 2 ]
    // Creator  : 08/04/2022 Wasin
    public function FSaMRPPGetDataListStep1Point2EndOfBill($paDataWhere){
        $nDecimal   = FCNxHGetOptionDecimalShow();
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocNo     = $paDataWhere['FTXthDocNo'];
        $tDocKey    = $paDataWhere['FTXthDocKey'];
        $tSessionID = $paDataWhere['FTSessionID'];
        $tSQL   = "
            SELECT 
                DTTMP.FTBchcode,
                DTTMP.FTXthDocNo,
                DTTMP.FTXthDocKey,
                SUM(DTTMP.FCXtdInvGrand)    AS FCXtdInvGrandSum,
                SUM(DTTMP.FCXtdInvPaid)     AS FCXtdInvPaidSum,
                SUM(DTTMP.FCXtdInvRem)	    AS FCXtdInvRemSum,
                SUM(DTTMP.FCXtdInvPay)	    AS FCXtdInvPaySum
            FROM (
                SELECT
                    DT.FTBchCode,
                    DT.FTXthDocNo,
                    DT.FTXthDocKey,
                    CASE WHEN DT.FTSrnCode = 'PC' THEN FCXtdAmt*-1 ELSE FCXtdAmt        END  AS FCXtdInvGrand,
                    CASE WHEN DT.FTSrnCode = 'PC' THEN FCXtdAmt*-1 ELSE FCXtdSetPrice   END  AS FCXtdInvPaid,
                    CASE WHEN DT.FTSrnCode = 'PC' THEN FCXtdAmt*-1 ELSE FCXtdNet        END  AS FCXtdInvPay,
                    ISNULL(DT.FCXtdVatable,0) AS FCXtdInvRem
                FROM TCNTDocDTTmp DT WITH ( NOLOCK ) 
                WHERE DT.FTBchcode 	= ".$this->db->escape($tBchCode)."
                AND DT.FTXthDocNo 	= ".$this->db->escape($tDocNo)."
                AND DT.FTXthDocKey 	= ".$this->db->escape($tDocKey)."
                AND DT.FTsessionID 	= ".$this->db->escape($tSessionID)."
            ) AS DTTMP
            GROUP BY DTTMP.FTBchCode,DTTMP.FTXthDocNo,DTTMP.FTXthDocKey
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = '';
        }
        unset($tBchCode);
        unset($tDocNo);
        unset($tDocKey);
        unset($tSessionID);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }




    // Function : ค้นหารายละเอียดเอกสารชำระ [ Step 3]
    // Creator  : 30/03/2022 Wasin
    public function FSaMRPPListStep1Point3($paDataWhere){
        $tBCHCode       = $paDataWhere['tBCHCode'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nLngID         = $this->session->userdata("tLangEdit");
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        $tSesUsername   = $this->session->userdata('tSesUsername');

        //ลบ ใน Temp Step 3
        $this->db->where_in('FTXthDocNo', $tDocNo);
        $this->db->where_in('FTSessionID', $tSesSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');

        
        // Insert Calulate Data In Temp Step 3
        $tSQLInsert = "
            INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FCXtdAmtB4DisChg,FCXtdChg,FCXtdNetAfHD,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTXtdDocNoRef
            )
            SELECT 
                DATAINS.FTBchCode,
                DATAINS.FTXthDocNo,
                ".$this->db->escape($tDocKey)." AS FTXthDocKey,
                DATAINS.FCTotalGrand	AS FCXtdAmtB4DisChg,
                0 AS FCXtdChg,
                DATAINS.FCTotalGrand	AS FCXtdNetAfHD,
                ".$this->db->escape($tSesSessionID)."   AS FTSessionID,
                '".date('Y-m-d h:i:s')."' AS FDLastUpdOn,
                '".date('Y-m-d h:i:s')."' AS FDCreateOn,
                ".$this->db->escape($tSesUsername)."    AS FTLastUpdBy,
                ".$this->db->escape($tSesUsername)."    AS FTCreateBy,
                NULL AS FTXtdDocNoRef
            FROM (
                SELECT
                    DATADT.FTBchCode,
                    DATADT.FTXthDocNo,
                    DATADT.FTXthDocKey,
                    SUM(CASE WHEN DATADT.FTSrnCode = 'PC' THEN (DATADT.FCXtdInvGrand *-1) WHEN DATADT.FTSrnCode = 'PD' THEN (DATADT.FCXtdInvGrand *1) ELSE DATADT.FCXtdInvGrand END ) AS FCTotalGrand
                FROM (
                    SELECT 
                        DTTMP.FTBchCode,
                        DTTMP.FTXthDocNo,
                        DTTMP.FTXthDocKey,
                        DTTMP.FCXtdAmt AS FCXtdInvGrand,
                        DTTMP.FTSrnCode 
                    FROM TCNTDocDTTmp DTTMP WITH(NOLOCK)
                    WHERE DTTMP.FDCreateOn <> ''
                    AND DTTMP.FTBchCode     = ".$this->db->escape($tBCHCode)."
                    AND DTTMP.FTXthDocNo    = ".$this->db->escape($tDocNo)."
                    AND DTTMP.FTXthDocKey   = 'TACTPpDTStep2'
                    AND DTTMP.FTSessionID   = ".$this->db->escape($tSesSessionID)."
                ) AS DATADT
                GROUP BY FTBchCode,FTXthDocNo,FTXthDocKey
            ) DATAINS
        ";
        $oQueryInsert   = $this->db->query($tSQLInsert);

        $tSQL   = "
            SELECT
                FTBchCode,
                FTXthDocNo,
                FTXthDocKey,
                FCXtdAmtB4DisChg,
                FCXtdChg,
                FCXtdNetAfHD,
                FTSessionID,
                FDLastUpdOn,
                FDCreateOn,
                FTLastUpdBy,
                FTCreateBy,
                FTXtdDocNoRef
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID <> ''
            AND ISNULL(DOCTMP.FTXthDocNo,'')    = ".$this->db->escape($tDocNo)."
            AND DOCTMP.FTXthDocKey              = ".$this->db->escape($tDocKey)."
            AND DOCTMP.FTSessionID              = ".$this->db->escape($tSesSessionID)."
        ";
        $oQuery   = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

    // Function : ดึงข้อมูลประเภทการชำระเงิน [ Step 3]
    // Creator  : 01/03/2022 Wasin
    public function FSaMRPPGetDataRCV($paData){
        $nLngID = $paData['nLangEdit'];
        $tSQL   = "
            SELECT RCV.FTRcvCode,RCVL.FTRcvName
            FROM TFNMRcv RCV WITH(NOLOCK)
            LEFT JOIN TFNMRcv_L RCVL WITH(NOLOCK) ON RCV.FTRcvCode = RCVL.FTRcvCode AND RCVL.FNLngID = ".$this->db->escape($nLngID)."
            WHERE RCV.FDCreateOn <> '' AND RCV.FTRcvStaUse = '1' AND RCV.FTRcvCode IN ('001','002','058','059','060')
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList          = $oQuery->result_array();
            $aResult        = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        } else {
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }

    // Function : อัพเดตและคำนวณ หักภาษี ณ ที่จ่าย [ Step 3]
    // Creator  : 07/04/2022 Wasin
    public function FSoMRPPEventUpdWhTaxHD($paDataWhere,$paDataUpd){
        $this->db->trans_begin();

        $tBCHCode       = $paDataWhere['tBCHCode'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $tSessionID     = $paDataWhere['tSessionID'];

        // Update Wht Amt And Document Ref Tax HD
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FTXthDocNo',$tDocNo);
        $this->db->where('FTBchCode',$tBCHCode);
        $this->db->update('TCNTDocDTTmp',$paDataUpd);

        // Sql Update And Calcurate FCXtdNetAfHD
        $tSQLUpd    = "
            UPDATE DTUPD
            SET 
                DTUPD.FCXtdNetAfHD = DATADT.FCXtdNetAfHD
            FROM TCNTDocDTTmp DTUPD WITH(NOLOCK)
            INNER JOIN (
                SELECT
                    DOCDT.FTBchCode,
                    DOCDT.FTXthDocNo,
                    DOCDT.FTXthDocKey,
                    DOCDT.FTSessionID,
                    ISNULL(DOCDT.FCXtdAmtB4DisChg,0)    AS FCXtdAmtB4DisChg,
                    ISNULL(DOCDT.FCXtdChg,0)            AS FCXtdChg,
                    ISNULL(DOCDT.FCXtdAmtB4DisChg - DOCDT.FCXtdChg,0) AS FCXtdNetAfHD,
                    DOCDT.FTXtdDocNoRef
                FROM TCNTDocDTTmp DOCDT WITH(NOLOCK)
                WHERE DOCDT.FDCreateOn <> ''
                AND DOCDT.FTBchCode     = ".$this->db->escape($tBCHCode)."
                AND DOCDT.FTXthDocNo    = ".$this->db->escape($tDocNo)."
                AND DOCDT.FTXthDocKey	= ".$this->db->escape($tDocKey)."
                AND DOCDT.FTSessionID	= ".$this->db->escape($tSessionID)."
            ) DATADT 
            ON 1=1
            AND DATADT.FTBchCode    = DTUPD.FTBchCode
            AND DATADT.FTXthDocNo 	= DTUPD.FTXthDocNo 
            AND DATADT.FTXthDocKey	= DTUPD.FTXthDocKey
            AND DATADT.FTSessionID  = DTUPD.FTSessionID
        ";
        $oQueryUpd  = $this->db->query($tSQLUpd);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStaUpdDoc     = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Update Item.',
            );
        }else{
            $this->db->trans_commit();
            $tSQL   = "
                SELECT
                    FTBchCode,
                    FTXthDocNo,
                    FTXthDocKey,
                    FCXtdAmtB4DisChg,
                    FCXtdChg,
                    FCXtdNetAfHD,
                    FTSessionID,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy,
                    FTXtdDocNoRef
                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                WHERE DOCTMP.FTSessionID <> ''
                AND ISNULL(DOCTMP.FTXthDocNo,'')    = ".$this->db->escape($tDocNo)."
                AND DOCTMP.FTXthDocKey              = ".$this->db->escape($tDocKey)."
                AND DOCTMP.FTSessionID              = ".$this->db->escape($tSessionID)."
            ";
            $oQuery   = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aList      = $oQuery->row_array();
                $aStaUpdDoc = array(
                    'raItems'   => $aList,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success'
                );
            } else {
                $aStaUpdDoc     = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found'
                );
            }
        }
        return $aStaUpdDoc;
    }






    // Function : เช็คข้อมูลในตาราง DT Temp
    // Creator  : 19/04/2022 Wasin
    public function FSnMRPPChkPdtInDocDTTemp($paDataWhere){
        $tRPPDocNo      = $paDataWhere['FTXphDocNo'];
        $tRPPDocKey     = $paDataWhere['FTXthDocKey'];
        $tRPPSessionID  = $paDataWhere['FTSessionID'];
        $tSQL           = "
            SELECT
                COUNT(FNXtdSeqNo) AS nCountPdt
            FROM TCNTDocDTTmp DocDT WITH(NOLOCK)
            

        ";







    }









}