<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookingcalendar_model extends CI_Model
{

    //หาข้อมูลช่องทางบริการ
    public function FSaMBKGetBayService($aItem){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tAgnCode   = $aItem['FTAgnCode'];
        $tBchCode   = $aItem['FTBchCode'];
        $tSQL       = "SELECT
                            TOP 10 
                            BCAL.FTSpsName,
                            CAL.FTSpsCode , 
                            CAL.FTBchCode,
                            CAL.FTAgnCode
                        FROM [TSVMPos] CAL WITH (NOLOCK)
                        LEFT JOIN [TSVMPos_L] BCAL WITH (NOLOCK) ON CAL.FTSpsCode = BCAL.FTSpsCode AND CAL.FTBchCode = BCAL.FTBchCode AND BCAL.FNLngID = $nLngID 
                        WHERE 1=1 AND CAL.FTSpsStaUse = 1 AND CAL.FTAgnCode = '$tAgnCode'
                        AND CAL.FTBchCode = '$tBchCode' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'raItems'       => $oQuery->result_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'raItems'       => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาข้อมูลช่องทางบริการ ตาม ID
    public function FSaMBKGetBayServiceByID($nBayCode, $nAgnCode, $nBchCode){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT
                            TOP 1 
                            BCAL.FTSpsName,
                            CAL.FTSpsCode , 
                            CAL.FTBchCode,
                            CAL.FTAgnCode ,
                            BCHL.FTBchName,
                            AGNL.FTAgnName
                        FROM [TSVMPos] CAL WITH (NOLOCK)
                        LEFT JOIN [TSVMPos_L] BCAL WITH (NOLOCK) ON CAL.FTSpsCode = BCAL.FTSpsCode AND CAL.FTBchCode = BCAL.FTBchCode AND BCAL.FNLngID = $nLngID 
                        LEFT JOIN [TCNMBranch_L] BCHL WITH (NOLOCK) ON CAL.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                        LEFT JOIN [TCNMAgency_L] AGNL WITH (NOLOCK) ON CAL.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID ";

        if ($nBayCode == '' || $nBayCode == null) {
            $tSQL .= " WHERE 1=1 AND CAL.FTSpsStaUse = 1 AND CAL.FTBchCode = '$nBchCode' AND CAL.FTAgnCode = '$nAgnCode' ";
        } else {
            $tSQL .= " WHERE 1=1 AND CAL.FTSpsStaUse = 1 AND CAL.FTSpsCode = '$nBayCode' ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'raItems'       => $oQuery->result_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'raItems'       => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาข้อมูลชื่อสาขา ชื่อ AD
    public function FSaMBKGetBranchName($nBchCode){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT
                            TOP 1 
                            BCHL.FTBchCode,
                            BCHL.FTBchName
                        FROM [TCNMBranch_L] BCHL WITH (NOLOCK) 
                        WHERE BCHL.FTBchCode = '$nBchCode' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'raItems'       => $oQuery->result_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'raItems'       => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาข้อมูลเอาไปใส่ ในตาราง
    public function FSaMBKGetCalendarService($aItem){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tAgnCode   = $aItem['FTAgnCode'];
        $tBchCode   = $aItem['FTBchCode'];
        $tSQL       = "SELECT 
                        HD.* , 
                        BCAL.FTSpsName,
                        ISNULL(CST_L.FTCstName,'') AS FTCstName,
                        CAL.FTSpsCode,
                        BCAL.FTSpsName
                    FROM TSVTBookHD HD
                    LEFT JOIN TSVMPos CAL         WITH (NOLOCK) ON HD.FTBchCode = CAL.FTBchCode AND HD.FTAgnCode = CAL.FTAgnCode AND HD.FTXshToPos = CAL.FTSpsCode
                    LEFT JOIN TSVMPos_L BCAL      WITH (NOLOCK) ON CAL.FTSpsCode = BCAL.FTSpsCode AND BCAL.FNLngID = '$nLngID'
                    LEFT JOIN TCNMCst CST         WITH (NOLOCK) ON HD.FTXshCstRef1 = CST.FTCstCode 
                    LEFT JOIN TCNMCst_L CST_L     WITH (NOLOCK) ON CST_L.FTCstCode = CST.FTCstCode AND CST_L.FNLngID = '$nLngID'
                    WHERE 1=1 AND HD.FTAgnCode = '$tAgnCode' AND HD.FTBchCode = '$tBchCode' AND HD.FTXshStaDoc = 1 ";


        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'raItems'       => $oQuery->result_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'raItems'       => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //อัพเดทข้อมูล กรณี ลูกค้าเลือนนัด
    public function FSaMBKUpdatePostPone($aItem){
        $tDocumentNumber    = $aItem['FTXshDocNo'];
        $dDocDate           = $aItem['FDXshBookDate'];
        $tDocTel            = $aItem['FTXshTel'];
        $tDocEmail          = $aItem['FTXshEmail'];
        $tDocNotiPrev       = $aItem['FNXshQtyNotiPrev'];
        $tUpdOn             = $aItem['FDLastUpdOn'];
        $tUpdBy             = $aItem['FTLastUpdBy'];
        $tTimeStart         = $aItem['FDXshTimeStart'];
        $tTimeStop          = $aItem['FDXshTimeStop'];

        //Update เลขที่เอกสาร
        $this->db->set('FDXshBookDate', $dDocDate);
        $this->db->set('FTXshTel', $tDocTel);
        $this->db->set('FTXshEmail', $tDocEmail);
        $this->db->set('FNXshQtyNotiPrev', $tDocNotiPrev);
        $this->db->set('FDXshTimeStart', $tTimeStart);
        $this->db->set('FDXshTimeStop', $tTimeStop);
        $this->db->set('FDLastUpdOn', $tUpdOn);
        $this->db->set('FTLastUpdBy', $tUpdBy);
        $this->db->where_in('FTXshDocNo', $tDocumentNumber);
        $this->db->update('TSVTBookHD');
    }

    //เพิ่มข้อมูล HD (TABLE)
    public function FSaMBKInsertBayService($aItem, $tTableName){
        try {
            $this->db->insert($tTableName, $aItem);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //เพิ่มข้อมูล DT (Move จาก Temp ไป DT)
    public function FSaMBKMoveDTToTable($aItem, $tDummyDocument){
        try {

            $tDocumentNumber    = $aItem['FTXshDocNo'];
            $tAGNCode           = $aItem['FTAgnCode'];
            $tBCHCode           = $aItem['FTBchCode'];
            $tSessionID         = $this->session->userdata('tSesSessionID');

            //Update เลขที่เอกสาร
            $this->db->set('FTXthDocNo', $tDocumentNumber);
            $this->db->where_in('FTSessionID', $tSessionID);
            $this->db->where_in('FTXthDocKey', 'TSVTBookDT');
            $this->db->where_in('FTBchCode', $tBCHCode);
            if ($tDummyDocument == 'DUMMY') {
                $this->db->where_in('FTXthDocNo', $tDummyDocument);
            }
            $this->db->update('TCNTDocDTTmp');

            //เรียง Seq ใหม่
            $tSQL   = " UPDATE TCNTDocDTTmp WITH(ROWLOCK)
                            SET FNXtdSeqNo = x.NewSeq 
                        FROM TCNTDocDTTmp DT 
                        INNER JOIN (
                            SELECT 
                                ROW_NUMBER() OVER (ORDER BY FNXtdSeqNo) AS NewSeq,
                                FNXtdSeqNo AS FNXtdSeqNo_x ,
                                FTPdtCode AS FTPdtCode_x
                            FROM TCNTDocDTTmp AS y
                            WHERE FTBchCode = '$tBCHCode' AND FTXthDocKey = 'TSVTBookDT' AND FTSessionID = '$tSessionID' AND ISNULL(FTSrnCode,'') = ''
                        ) x ON DT.FNXtdSeqNo = x.FNXtdSeqNo_x AND DT.FTPdtCode = x.FTPdtCode_x
                        WHERE FTBchCode = '$tBCHCode' AND FTXthDocKey = 'TSVTBookDT' AND FTSessionID = '$tSessionID' AND ISNULL(FTSrnCode,'') = '' ";
            $this->db->query($tSQL);

            //Move To DT (สินค้าปกติ)
            $tSQL   = " INSERT INTO TSVTBookDT (
                            FTAgnCode , FTBchCode , FTXshDocNo , FTXsdSeq , FTPdtCode , 
                            FTXsdPdtName ,  FTPsvStaSuggest , FTWahCodeFrm , FTWahCodeTo ,
                            FTPunCode , FTPunName , FCXsdFactor , FTXsdBarCode , FTXsdVatType ,
                            FTVatCode , FCXsdVatRate , FTPplCode , FCXsdSalePrice , FCXsdQty ,
                            FCXsdQtyAll , FNXsdPdtLevel , FTXsdPdtParent , FCXsdSetPrice ,
                            FCXsdAmtB4DisChg , FTXsdDisChgTxt , FCXsdDis , FCXsdChg , FCXsdNet ,
                            FCXsdNetAfHD , FCXsdVat , FCXsdVatable , FCXsdWhtAmt , FTXsdWhtCode ,
                            FCXsdWhtRate , FTPdtStaSet , FTXsdStaPrcStk , FTXsdStaAlwDis ,FTXsdRmk ,
                            FDLastUpdOn ,FTLastUpdBy , FDCreateOn , FTCreateBy ) ";
            $tSQL   .=  "   SELECT
                            '$tAGNCode'             AS FTAgnCode,
                            DOCTMP.FTBchCode        AS FTBchCode,
                            DOCTMP.FTXthDocNo       AS FTXshDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXsdSeqNo,
                            DOCTMP.FTPdtCode        AS FTPdtCode,
                            DOCTMP.FTXtdPdtName     AS FTXsdPdtName,
                            ''                      AS FTPsvStaSuggest, --สถานะแนะนำ 1 : แนะนำ , 2 : ไม่ได้แนะนำ 
                            FTXthWhFrmForTWXVD      AS FTWahCodeFrm,
                            FTXthWhToForTWXVD       AS FTWahCodeTo,
                            DOCTMP.FTPunCode        AS FTPunCode,
                            DOCTMP.FTPunName        AS FTPunName,
                            DOCTMP.FCXtdFactor      AS FCXsdFactor,
                            DOCTMP.FTXtdBarCode     AS FTXsdBarCode,
                            DOCTMP.FTXtdVatType     AS FTXsdVatType,
                            DOCTMP.FTVatCode        AS FTVatCode,
                            DOCTMP.FCXtdVatRate     AS FCXsdVatRate, 
                            ''                      AS FTPplCode,
                            DOCTMP.FCXtdSalePrice   AS FCXsdSalePrice,
                            DOCTMP.FCXtdQty         AS FCXsdQty,
                            DOCTMP.FCXtdQtyAll      AS FCXsdQtyAll,
                            DOCTMP.FTPdtSetOrSN     AS FTPdtSetOrSN,
                            ''                      AS FTXsdPdtParent, 
                            DOCTMP.FCXtdSetPrice    AS FCXsdSetPrice,
                            DOCTMP.FCXtdAmtB4DisChg AS FCXsdAmtB4DisChg,
                            DOCTMP.FTXtdDisChgTxt   AS FTXsdDisChgTxt,
                            DOCTMP.FCXtdDis         AS FCXsdDis,
                            DOCTMP.FCXtdChg         AS FCXsdChg,
                            DOCTMP.FCXtdNet         AS FCXsdNet,
                            DOCTMP.FCXtdNetAfHD     AS FCXsdNetAfHD,
                            DOCTMP.FCXtdVat         AS FCXsdVat,
                            DOCTMP.FCXtdVatable     AS FCXsdVatable,
                            DOCTMP.FCXtdWhtAmt      AS FCXsdWhtAmt,
                            DOCTMP.FTXtdWhtCode     AS FTXsdWhtCode,
                            DOCTMP.FCXtdWhtRate     AS FCXsdWhtRate,
                            DOCTMP.FTPdtSetOrSN     AS FTPdtStaSet,
                            DOCTMP.FTXtdStaPrcStk   AS FTXsdStaPrcStk,
                            DOCTMP.FTXtdStaAlwDis   AS FTXsdStaAlwDis,
                            DOCTMP.FTXtdRmk         AS FTXsdRmk,
                            DOCTMP.FDLastUpdOn      AS FDLastUpdOn,
                            DOCTMP.FTLastUpdBy      AS FTLastUpdBy,
                            DOCTMP.FDCreateOn       AS FDCreateOn,
                            DOCTMP.FTCreateBy       AS FTCreateBy
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tBCHCode'
                        AND DOCTMP.FTXthDocNo   = '$tDocumentNumber'
                        AND DOCTMP.FTXthDocKey  = 'TSVTBookDT'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        AND ISNULL(DOCTMP.FTSrnCode,'') = '' -- ไม่เอาสินค้าเซต + สินค้าบำรุง
                        AND ISNULL(DOCTMP.FTPdtCode,'') != ''
                        ORDER BY DOCTMP.FNXtdSeqNo ASC";
            $this->db->query($tSQL);

            //Move To DTSet (สินค้าเซต + สินค้าบำรุงรักษา)
            $tSQLSet   = " INSERT INTO TSVTBookDTSet (
                            FNXsdSeqNo , FTAgnCode , FTBchCode , FTXshDocNo ,  
                            FNPstSeqNo , FTPdtCode , FTPsvType , FTXsdPdtName ,
                            FTPunCode , FCXsdQtySet  , FCXsdSalePrice , FTXsdStaPrcStk ) ";
            $tSQLSet   .=  "   SELECT 
                                B.FNXtdSeqNo , A.FTAgnCode , A.FTBchCode ,
                                A.FTXshDocNo , A.FNPstSeqNo , A.FTPdtCode ,
                                A.FTPsvType , A.FTXsdPdtName , A.FTPunCode ,
                                A.FCXsdQtySet , A.FCXsdSalePrice , A.FTXsdStaPrcStk
                            FROM ( 
                            SELECT
                                '$tAGNCode'             AS FTAgnCode,
                                DOCTMP.FTBchCode        AS FTBchCode,
                                DOCTMP.FTXthDocNo       AS FTXshDocNo,
                                ROW_NUMBER() OVER ( ORDER BY DOCTMP.FTPdtCode ASC)       AS FNPstSeqNo,
                                DOCTMP.FTPdtCode        AS FTPdtCode,
                                DOCTMP.FNXtdPdtLevel    AS FTPsvType,
                                DOCTMP.FTXtdPdtName     AS FTXsdPdtName,
                                DOCTMP.FTPunCode        AS FTPunCode,
                                DOCTMP.FCXtdQtySet      AS FCXsdQtySet,
                                '0'                     AS FCXsdSalePrice,
                                DOCTMP.FTSrnCode        AS FTSrnCode ,
                                DOCTMP.FTXtdStaPrcStk   AS FTXsdStaPrcStk
                            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND DOCTMP.FTBchCode    = '$tBCHCode'
                            AND DOCTMP.FTXthDocNo   = '$tDocumentNumber'
                            AND DOCTMP.FTXthDocKey  = 'TSVTBookDT'
                            AND DOCTMP.FTSessionID  = '$tSessionID'
                            AND ISNULL(DOCTMP.FTSrnCode,'') != '' -- ไม่เอาสินค้าปกติ
                        ) A
                        LEFT JOIN TCNTDocDTTmp B ON A.FTSrnCode = B.FTPdtCode
                        WHERE 1 = 1
                        AND B.FTBchCode    = '$tBCHCode'
                        AND B.FTXthDocNo   = '$tDocumentNumber'
                        AND B.FTXthDocKey  = 'TSVTBookDT'
                        AND B.FTSessionID  = '$tSessionID'
                        AND ISNULL(B.FTSrnCode,'') = '' -- เอาสินค้าปกติ (เอา seq ของแม่)
                        ORDER BY A.FNPstSeqNo ASC";
            $this->db->query($tSQLSet);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //เพิ่มข้อมูล DT (Move จาก DT ไป TEMP)
    public function FSaMBKMoveDTToTemp($tDocumentNumber){
        $tSessionID  = $this->session->userdata('tSesSessionID');

        //จาก DT ไป Temp
        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                            FTXthDocKey , FTBchCode , FTXthDocNo , FNXtdSeqNo , FTPdtCode , 
                            FTXtdPdtName , FTXtdPdtParent , FTXthWhFrmForTWXVD , FTXthWhToForTWXVD ,
                            FTPunCode , FTPunName , FCXtdFactor , FTXtdBarCode , FTXtdVatType ,
                            FTVatCode , FCXtdVatRate , FCXtdSalePrice , FCXtdQty ,
                            FCXtdQtyAll , FCXtdSetPrice ,
                            FCXtdAmtB4DisChg , FTXtdDisChgTxt , FCXtdDis , FCXtdChg , FCXtdNet ,
                            FCXtdNetAfHD , FCXtdVat , FCXtdVatable , FCXtdWhtAmt , FTXtdWhtCode ,
                            FCXtdWhtRate , FTPdtSetOrSN , FTXtdStaPrcStk , FTXtdStaAlwDis ,FTXtdRmk , 
                            FDLastUpdOn ,FTLastUpdBy , FDCreateOn , FTCreateBy , FTSessionID ) ";
        $tSQL   .=  "   SELECT
                            'TSVTBookDT'            AS FTXthDocKey,
                            DOCTMP.FTBchCode        AS FTBchCode,
                            DOCTMP.FTXshDocNo       AS FTXshDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FTXsdSeq ASC) AS FTXsdSeq,
                            DOCTMP.FTPdtCode        AS FTPdtCode,
                            DOCTMP.FTXsdPdtName     AS FTXsdPdtName,
                            DOCTMP.FTXsdPdtParent   AS FTPsvStaSuggest, --สถานะแนะนำ 1 : แนะนำ , 2 : ไม่ได้แนะนำ 
                            DOCTMP.FTWahCodeFrm     AS FTWahCodeFrm,
                            DOCTMP.FTWahCodeTo      AS FTWahCodeTo,
                            DOCTMP.FTPunCode        AS FTPunCode,
                            DOCTMP.FTPunName        AS FTPunName,
                            DOCTMP.FCXsdFactor      AS FCXtdFactor,
                            DOCTMP.FTXsdBarCode     AS FTXtdBarCode,
                            DOCTMP.FTXsdVatType     AS FTXtdVatType,
                            DOCTMP.FTVatCode        AS FTVatCode,
                            DOCTMP.FCXsdVatRate     AS FCXtdVatRate, 
                            DOCTMP.FCXsdSalePrice   AS FCXtdSalePrice,
                            DOCTMP.FCXsdQty         AS FCXtdQty,
                            DOCTMP.FCXsdQtyAll      AS FCXtdQtyAll,
                            DOCTMP.FCXsdSetPrice    AS FCXtdSetPrice,
                            DOCTMP.FCXsdAmtB4DisChg AS FCXtdAmtB4DisChg,
                            DOCTMP.FTXsdDisChgTxt   AS FTXtdDisChgTxt,
                            DOCTMP.FCXsdDis         AS FCXtdDis,
                            DOCTMP.FCXsdChg         AS FCXtdChg,
                            DOCTMP.FCXsdNet         AS FCXtdNet,
                            DOCTMP.FCXsdNetAfHD     AS FCXtdNetAfHD,
                            DOCTMP.FCXsdVat         AS FCXtdVat,
                            DOCTMP.FCXsdVatable     AS FCXtdVatable,
                            DOCTMP.FCXsdWhtAmt      AS FCXtdWhtAmt,
                            DOCTMP.FTXsdWhtCode     AS FTXtdWhtCode,
                            DOCTMP.FCXsdWhtRate     AS FCXtdWhtRate,
                            DOCTMP.FTPdtStaSet      AS FTPdtSetOrSN,
                            DOCTMP.FTXsdStaPrcStk   AS FTXtdStaPrcStk,
                            DOCTMP.FTXsdStaAlwDis   AS FTXtdStaAlwDis,
                            DOCTMP.FTXsdRmk         AS FTXtdRmk,
                            DOCTMP.FDLastUpdOn      AS FDLastUpdOn,
                            DOCTMP.FTLastUpdBy      AS FTLastUpdBy,
                            DOCTMP.FDCreateOn       AS FDCreateOn,
                            DOCTMP.FTCreateBy       AS FTCreateBy,
                            '$tSessionID'           AS FTSessionID
                        FROM TSVTBookDT DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTXshDocNo   = '$tDocumentNumber'
                        ORDER BY DOCTMP.FTXsdSeq ASC";
        $this->db->query($tSQL);

        //จาก DTSet ไป Temp
        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                    FTXthDocKey , FTBchCode , FTXthDocNo , FNXtdSeqNo , FTPdtCode , FTSrnCode ,
                    FTXtdPdtName , FTPunCode , FNXtdPdtLevel , FTPdtSetOrSN , FTXtdPdtStaSet ,
                    FTXtdPdtParent , FCXtdQtySet , FDLastUpdOn ,FTLastUpdBy , FTXtdStaPrcStk ,
                    FDCreateOn , FTCreateBy , FTSessionID ) ";
        $tSQL   .=  "   SELECT
                    'TSVTBookDT'            AS FTXthDocKey,
                    DTSET.FTBchCode         AS FTBchCode,
                    DTSET.FTXshDocNo        AS FTXthDocNo,
                    DTSET.FNXsdSeqNo        AS FNXsdSeqNo,
                    DTSET.FTPdtCode         AS FTPdtCode,
                    DT.FTPdtCode            AS FTSrnCode,
                    DTSET.FTXsdPdtName      AS FTXtdPdtName,
                    DTSET.FTPunCode         AS FTPunCode,
                    DTSET.FTPsvType         AS FNXtdPdtLevel,   --ประเภทรายการ 1:เปลี่ยนคิดราคา , 2:ตรวจสอบไม่คิดราคา
                    null                    AS FTPdtSetOrSN,   --ถ้าเป็นสินค้าลูกปล่อยเป็นว่าง
                    '1'                     AS FTXtdPdtStaSet,
                    ''                      AS FTXtdPdtParent,  --สถานะแนะนำ 1:แนะนำ , 2:ไม่ได้แนะนำ 
                    DTSET.FCXsdQtySet       AS FCXtdQtySet,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "')                          AS FDLastUpdOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "')      AS FTLastUpdBy,
                    DTSET.FTXsdStaPrcStk    AS FTXtdStaPrcStk,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "')                          AS FDCreateOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "')      AS FTCreateBy,
                    '$tSessionID'           AS FTSessionID
                    FROM TSVTBookDTSet DTSET WITH (NOLOCK)
                    LEFT JOIN TSVTBookDT DT ON DTSET.FNXsdSeqNo = DT.FTXsdSeq AND DTSET.FTXshDocNo = DT.FTXshDocNo
                    WHERE 1 = 1
                    AND DTSET.FTXshDocNo   = '$tDocumentNumber'
                    ORDER BY DTSET.FNXsdSeqNo ASC";
        $this->db->query($tSQL);
    }

    //หาข้อมูลตาม ID
    public function FSaMBKGetBookingbyID($tDocumentNumber){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "SELECT 
                    HD.* , 
                    BCAL.FTSpsName,
                    CAR.FTCarRegNo,
                    IMGCar.FTImgObj,
                    HD.FTBchCode                AS rtBchCode,
                    ISNULL(CST_L.FTCstName,'')  AS FTCstName,
                    CAR.FTCarEngineNo           AS rtCarEngineNo,
                    CAR.FTCarVIDRef             AS rtCarPowerNumber,
                    CAR.FDCarDOB                AS rtCarDateOutCar,
                    CAR.FDCarOwnChg             AS rtCarDate, 
                    T1.FTCaiName                AS rtCarTypeName,
                    T2.FTCaiName                AS rtCarBrandName,
                    T3.FTCaiName                AS rtCarModelName,
                    T4.FTCaiName                AS rtCarColorName,
                    T5.FTCaiName                AS rtCarGearName,
                    T6.FTCaiName                AS rtCarPowerTypeName,
                    T7.FTCaiName                AS rtCarEngineSizeName,
                    T8.FTCaiName                AS rtCarCategoryName ,
                    RSNL.FTRsnCode              AS rtRsnCode,
                    RSNL.FTRsnName              AS rtRsnName
                FROM TSVTBookHD HD
                LEFT JOIN TSVMPos_L BCAL      WITH (NOLOCK) ON HD.FTXshToPos = BCAL.FTSpsCode AND BCAL.FNLngID = '$nLngID'
                LEFT JOIN TCNMCst CST         WITH (NOLOCK) ON HD.FTXshCstRef1 = CST.FTCstCode 
                LEFT JOIN TCNMCst_L CST_L     WITH (NOLOCK) ON CST_L.FTCstCode = CST.FTCstCode AND CST_L.FNLngID = '$nLngID'
                LEFT JOIN TSVMCar CAR         WITH (NOLOCK) ON HD.FTXshCstRef2 = CAR.FTCarCode
                LEFT JOIN TCNMImgObj IMGCar   WITH (NOLOCK) ON CAR.FTCarCode = IMGCar.FTImgRefID AND IMGCar.FTImgTable = 'TSVMCar'
                LEFT JOIN TSVMCarInfo_L T1 	  WITH (NOLOCK) ON CAR.FTCarType = T1.FTCaiCode AND T1.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T2 	  WITH (NOLOCK) ON CAR.FTCarBrand = T2.FTCaiCode AND T2.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T3 	  WITH (NOLOCK) ON CAR.FTCarModel = T3.FTCaiCode AND T3.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T4 	  WITH (NOLOCK) ON CAR.FTCarColor = T4.FTCaiCode AND T4.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T5 	  WITH (NOLOCK) ON CAR.FTCarGear = T5.FTCaiCode AND T5.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T6 	  WITH (NOLOCK) ON CAR.FTCarPowerType = T6.FTCaiCode AND T6.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T7 	  WITH (NOLOCK) ON CAR.FTCarEngineSize = T7.FTCaiCode AND T7.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T8 	  WITH (NOLOCK) ON CAR.FTCarCategory = T8.FTCaiCode AND T8.FNLngID = '$nLngID'
                LEFT JOIN TCNMRsn_L     RSNL  WITH (NOLOCK) ON HD.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID = '$nLngID'
                WHERE 1=1 AND HD.FTXshDocNo = '$tDocumentNumber' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    //หาข้อมูลตามรถ
    public function FSaMBKGetCarbyID($tCar){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "SELECT 
                    CAR.FTCarRegNo                          AS rtCarRegNo,
                    CAR.FTCarEngineNo                       AS rtCarEngineNo,
                    CAR.FTCarVIDRef                         AS rtCarPowerNumber,
                    convert(varchar,CAR.FDCarDOB , 103)     AS rtCarDateOutCar,
                    convert(varchar,CAR.FDCarOwnChg , 103)  AS rtCarDate,
                    T1.FTCaiName                AS rtCarTypeName,
                    T2.FTCaiName                AS rtCarBrandName,
                    T3.FTCaiName                AS rtCarModelName,
                    T4.FTCaiName                AS rtCarColorName,
                    T5.FTCaiName                AS rtCarGearName,
                    T6.FTCaiName                AS rtCarPowerTypeName,
                    T7.FTCaiName                AS rtCarEngineSizeName,
                    T8.FTCaiName                AS rtCarCategoryName,
                    IMG.FTImgObj
                FROM TSVMCar CAR         
                LEFT JOIN TSVMCarInfo_L T1 	  WITH (NOLOCK) ON CAR.FTCarType = T1.FTCaiCode AND T1.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T2 	  WITH (NOLOCK) ON CAR.FTCarBrand = T2.FTCaiCode AND T2.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T3 	  WITH (NOLOCK) ON CAR.FTCarModel = T3.FTCaiCode AND T3.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T4 	  WITH (NOLOCK) ON CAR.FTCarColor = T4.FTCaiCode AND T4.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T5 	  WITH (NOLOCK) ON CAR.FTCarGear = T5.FTCaiCode AND T5.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T6 	  WITH (NOLOCK) ON CAR.FTCarPowerType = T6.FTCaiCode AND T6.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T7 	  WITH (NOLOCK) ON CAR.FTCarEngineSize = T7.FTCaiCode AND T7.FNLngID = '$nLngID'
                LEFT JOIN TSVMCarInfo_L T8 	  WITH (NOLOCK) ON CAR.FTCarCategory = T8.FTCaiCode AND T8.FNLngID = '$nLngID'
                LEFT JOIN TCNMImgObj IMG      WITH (NOLOCK) ON IMG.FTImgRefID = CAR.FTCarCode AND IMG.FTImgTable = 'TSVMCar'
                WHERE 1=1 AND CAR.FTCarCode = '$tCar' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    //Delete ข้อมูลใน Temp (ทั้งหมด)
    public function FSaMBKDeleteDTInTemp(){
        try {
            $this->db->trans_begin();
            $tSession   = $this->session->userdata('tSesSessionID');

            $this->db->where_in('FTXthDocKey', 'TSVTBookDT');
            $this->db->where_in('FTSessionID', $tSession);
            $this->db->delete('TCNTDocDTTmp');

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

    //Delete ข้อมูลใน Temp (ตามสินค้า)
    public function FSaMBKDeleteDTPDTInTemp($aItem){
        try {
            $this->db->trans_begin();
            $tSession   = $this->session->userdata('tSesSessionID');

            if ($aItem['FTXcdPdtSeq'] == 0) { //ลบกรณีเปลี่ยนสาขา
                $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
                $this->db->where_in('FTSessionID', $tSession);
                $this->db->delete('TCNTDocDTTmp');
            } else {
                //ลบตัวแม่
                $this->db->where_in('FTBchCode', $aItem['FTBchCode']);
                $this->db->where_in('FTXthDocNo', $aItem['FTXshDocNo']);
                $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
                $this->db->where_in('FTPdtCode', $aItem['FTPdtCode']); //รหัสสินค้า
                $this->db->where_in('FNXtdSeqNo', $aItem['FTXcdPdtSeq']); //ลำดับ
                $this->db->where_in('FTSessionID', $tSession);
                $this->db->delete('TCNTDocDTTmp');

                //ลบตัวลูก
                $this->db->where_in('FTBchCode', $aItem['FTBchCode']);
                $this->db->where_in('FTXthDocNo', $aItem['FTXshDocNo']);
                $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
                $this->db->where_in('FTSrnCode', $aItem['FTPdtCode']); //รหัสสินค้าชุด
                $this->db->where_in('FTSessionID', $tSession);
                $this->db->delete('TCNTDocDTTmp');
            }

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

    //get ข้อมูลราคา
    public function FSaMBKSeletePriceToTemp($ptPdtCode, $ptPunCode){
        $tSQL   = "SELECT FCPgdPriceRet FROM VCN_Price4PdtActive PRICE
                   WHERE 1=1 AND PRICE.FTPdtCode = '$ptPdtCode' AND PRICE.FTPunCode = '$ptPunCode' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    //get ข้อมูลใน Temp
    public function FSaMBKGetDTInTemp($tBCHCode, $tDocumentNumber){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSession   = $this->session->userdata('tSesSessionID');
        $tSQL       = " SELECT 
                            -- COUNT(A.rtStaSet) OVER (PARTITION BY A.rtStaSet , A.FTPdtCode) AS PARTITIONBYPDTSET ,
                            COUNT (A.FTSrnCode) OVER (PARTITION BY A.FTSrnCode) AS PARTITIONBYPDTSET,
                            A.* 
                        FROM (
                            SELECT 
                                TMP.FTBchCode ,
                                TMP.FTXthDocNo ,
                                TMP.FTXthDocKey ,
                                CASE 
                                    WHEN ISNULL(TMP.FTPdtSetOrSN,'') = '' 
                                    THEN TMP.FTSrnCode 
                                ELSE TMP.FTPdtCode 
                                END AS FTPdtCode , 
                                TMP.FNXtdSeqNo ,
                                TMP.FTXtdPdtName ,
                                TMP.FTPdtSetOrSN ,
                                TMP.FCXtdQty ,
                                TMP.FCXtdSetPrice ,
                                TMP.FTXtdStaPrcStk ,
                                TMP.FTXtdPdtStaSet ,
                                TMP.FNXtdPdtLevel ,
                                TMP.FTXtdPdtParent ,
                                TMP.FTVatCode,
                                TMP.FCXtdVatRate,
                                TMP.FTXtdVatType,
                                TMP.FCXtdNet ,
                                TMP.FTXtdStaAlwDis ,
                                CASE 
                                    WHEN ISNULL(TMP.FTSrnCode,'') = '' THEN TMP.FTPdtCode 
                                    ELSE TMP.FTSrnCode 
                                END AS FTSrnCode , 
                                TMP.FCXtdNetAfHD ,
                                TMP.FTPunCode,
		                        TMP.FTPunName,
                                TMP.FCXtdFactor,
                                TMP.FTXtdPdtStaSet   AS rtStaSet,
                                TMP.FNXtdPdtLevel    AS rtPsyType,
                                TMP.FTXtdPdtName     AS rtNamePDTSet ,
                                TMP.FTPdtCode        AS rtPDTCodeSet
                            FROM TCNTDocDTTmp TMP
                            /*LEFT JOIN TCNTDocDTTmp PDTSET ON TMP.FTPdtCode = PDTSET.FTSrnCode 
                            AND PDTSET.FTSessionID = '$tSession'
                            AND PDTSET.FTXthDocKey = 'TSVTBookDT'
                            AND PDTSET.FTXthDocNo = '$tDocumentNumber'*/
                            WHERE 1=1 
                            AND TMP.FTBchCode = '$tBCHCode' 
                            AND TMP.FTXthDocNo = '$tDocumentNumber'
                            AND TMP.FTXthDocKey = 'TSVTBookDT' 
                            AND TMP.FTSessionID = '$tSession'
                            /*AND ISNULL(TMP.FTXtdPdtStaSet,'') = ''*/
                        ) AS A ORDER BY A.FNXtdSeqNo * 1 ASC , A.FTPdtCode , A.rtPsyType";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'raItems'       => $oQuery->result_array(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'raItems'       => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //get ข้อมูลสินค้าบริการ
    public function FSaMBKSeleteItemService($ptPdtCode){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "SELECT 
                    PDTSET.FTPsvType , 
                    PDTSET.FCPsvQty , 
                    PDTSET.FTPunCode , 
                    PDTSET.FTPsvStaSuggest , 
                    PDTSET.FTPdtCodeSub ,
                    PDTL.FTPdtName 
                    FROM TSVTPdtSet PDTSET
                    LEFT JOIN TCNMPdt_L PDTL ON PDTSET.FTPdtCodeSub = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                    WHERE 1=1 AND PDTSET.FTPdtCode = '$ptPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    //get ข้อมูลสินค้า
    public function FSaMBKGetDataPdt($ptPdtCode, $ptPunCode){
        $tPdtCode   = $ptPdtCode;
        $FTPunCode  = $ptPunCode;
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT
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
                            0 AS FTPdtSalePrice,
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
                            SPL.FCSplLastPrice,
                            PRI4PDT.FCPgdPriceRet
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
                            FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                            WHERE 1=1
                            AND (CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121))
                            AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121))
                        ) AS PRI4PDT
                        ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode
                        WHERE 1 = 1 ";

        if (isset($tPdtCode) && !empty($tPdtCode)) {
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }

        $tSQL   .= " ORDER BY FDVatStart DESC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
        } else {
            $aDetail = false;
        }
        return $aDetail;
    }

    //get ข้อมูลคลังในสาขา (คลังต้นทาง) 
    public function FSaMBKGetWahouseInBranchFrm($paParams){
        $tBCHCode   = $paParams['bchcode'];
        $tADCode    = $paParams['adcode'];
        $nLngID     = $this->session->userdata("tLangEdit");

        $tSQL       = " SELECT
                            WAHL.FTWahCode,
                            WAHL.FTWahName
                        FROM TCNMWaHouse WAH WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse_L WAHL ON WAH.FTBchCode = WAHL.FTBchCode AND WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = '$nLngID'
                        WHERE 1=1
                        AND WAH.FTBchCode = '" . $tBCHCode . "'
                        AND WAH.FTWahStaType != '7' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //get ข้อมูลคลังในสาขา (คลังจอง)
    public function FSaMBKGetWahouseInBranchTo($paParams){
        $tBCHCode   = $paParams['bchcode'];
        $tADCode    = $paParams['adcode'];
        $nLngID     = $this->session->userdata("tLangEdit");

        $tSQL       = " SELECT
                            WAHL.FTWahCode,
                            WAHL.FTWahName
                        FROM TCNMWaHouse WAH WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse_L WAHL ON WAH.FTBchCode = WAHL.FTBchCode AND WAH.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = '$nLngID'
                        WHERE 1=1
                        AND WAH.FTBchCode = '" . $tBCHCode . "'
                        AND WAH.FTWahStaType = '7' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //คำนวณ VAT
    public function FSaMBKCalInDTTemp($paParams){
        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];

        $tSQL       = " SELECT
                            /* ยอดรวม ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXphTotal,

                            /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNV,

                            /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNoDis,

                            /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgV,

                            /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgNV,

                            /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgV,

                            /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgNV,

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
                        WHERE DTTMP.FTXthDocNo  = '$tDocNo' 
                        AND DTTMP.FTXthDocKey   = '$tDocKey' 
                        AND DTTMP.FTSessionID   = '$tSessionID'
                        AND DTTMP.FTBchCode     = '$tBchCode'
                        GROUP BY DTTMP.FTSessionID ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->result_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    //เพิ่มข้อมูลใน Temp (PDT)
    public function FSaMBKInsertToTemp($aItem){
        $tSession   = $this->session->userdata('tSesSessionID');

        //หา Seq ล่าสุด ใน Temp 
        $tSQL  = " SELECT TOP 1 TMP.FNXtdSeqNo FROM TCNTDocDTTmp TMP WITH (NOLOCK)
                WHERE  TMP.FTXthDocNo = '" . $aItem['FTXshDocNo'] . "' AND
                TMP.FTBchCode = '" . $aItem['FTBchCode'] . "' AND
                TMP.FTXthDocKey = '" . $aItem['FTXthDocKey'] . "' AND
                TMP.FTSessionID = '" . $tSession . "' AND
                ISNULL(TMP.FTSrnCode,'') = '' ORDER BY FNXtdSeqNo DESC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->result_array();
            $nSeq       = $aResult[0]['FNXtdSeqNo'] + 1;
        } else {
            $nSeq       =  1;
        }

        //ถ้าเป็นสินค้าตัวเดิม
        $tSQL       = "SELECT 
                            TMP.FCXtdQty
                        FROM TCNTDocDTTmp TMP
                        WHERE TMP.FTPdtCode = '" . $aItem['FTPdtCode'] . "' AND
                        TMP.FTXthDocNo = '" . $aItem['FTXshDocNo'] . "' AND
                        TMP.FTPunCode = '" . $aItem['FTPunCode'] . "' AND
                        TMP.FTBchCode = '" . $aItem['FTBchCode'] . "' AND
                        TMP.FTXthDocKey = '" . $aItem['FTXthDocKey'] . "' AND
                        TMP.FTSessionID = '" . $tSession . "' AND
                        ( ISNULL(TMP.FTXtdStaPrcStk,'') = '' OR TMP.FTXtdStaPrcStk != 1 ) AND 
                        ISNULL(TMP.FTSrnCode,'') = ''  ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aItemQTY   = $oQuery->result_array();
            $nQTY       = $aItemQTY[0]['FCXtdQty'] + 1;

            //ลบข้อมูลสินค้าก่อน สินค้าตัวเดียว
            $this->db->where_in('FTPdtCode', $aItem['FTPdtCode']);
            $this->db->where_in('FTXthDocNo', $aItem['FTXshDocNo']);
            $this->db->where_in('FTBchCode', $aItem['FTBchCode']);
            $this->db->where_in('FTPunCode', $aItem['FTPunCode']);
            $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
            $this->db->where_in('FTSessionID', $tSession);
            $this->db->where_in("ISNULL(FTSrnCode,'')", '');
            $this->db->where_in("ISNULL(FTXtdStaPrcStk,'')", '');
            $this->db->delete('TCNTDocDTTmp');

            //ลบข้อมูลสินค้าก่อน สินค้าตัวเดียว
            $this->db->where_in('FTXthDocNo', $aItem['FTXshDocNo']);
            $this->db->where_in('FTBchCode', $aItem['FTBchCode']);
            $this->db->where_in('FTXthDocKey', $aItem['FTXthDocKey']);
            $this->db->where_in('FTSrnCode', $aItem['FTPdtCode']);
            $this->db->where_in('FTSessionID', $tSession);
            $this->db->delete('TCNTDocDTTmp');
        } else {
            $nQTY       = 1;
        }

        $this->db->insert('TCNTDocDTTmp', array(
            'FTBchCode'         => $aItem['FTBchCode'],
            'FTXthDocNo'        => $aItem['FTXshDocNo'],
            'FNXtdSeqNo'        => $nSeq,
            'FTXthDocKey'       => $aItem['FTXthDocKey'],
            'FTPdtCode'         => $aItem['FTPdtCode'],
            'FTPunCode'         => $aItem['FTPunCode'],
            'FTPunName'         => $aItem['FTPunName'],
            'FTXtdPdtName'      => $aItem['FTXcdPdtName'],
            'FTPdtSetOrSN'      => $aItem['FTPdtSetOrSN'],
            'FCXtdQty'          => $nQTY,
            'FCXtdQtyAll'       => $nQTY * $aItem['FCXtdFactor'],
            'FCXtdFactor'       => $aItem['FCXtdFactor'],
            'FCXtdSalePrice'    => $aItem['FCXtdSetPrice'],
            'FCXtdSetPrice'     => $aItem['FCXtdSetPrice'],
            'FCXtdNet'          => $aItem['FCXtdNet'],
            'FCXtdNetAfHD'      => $aItem['FCXtdSetPrice'] * $nQTY,
            'FTXtdStaPrcStk'    => $aItem['FTXtdStaPrcStk'],
            'FTXtdVatType'      => $aItem['FTXtdVatType'],
            'FTXtdBarCode'      => $aItem['FTBarCode'],
            'FTSessionID'       => $tSession,
            'FDLastUpdOn'       => date('Y-m-d H:i:s'),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date('Y-m-d H:i:s'),
            'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            'FTVatCode'         => $aItem['FTVatCode'],
            'FCXtdVatRate'      => $aItem['FCXtdVatRate'],
            'FTXtdStaAlwDis'    => $aItem['FTXtdStaAlwDis'],
            'FTXthWhFrmForTWXVD' => $aItem['tWahFrm'],
            'FTXthWhToForTWXVD' => $aItem['tWahTo']
        ));

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
        return $aStatus;
    }

    //เพิ่มข้อมูลใน Temp (PDTSet)
    public function FSaMBKInsertPDTSetToTemp($aItem){
        $tSession   = $this->session->userdata('tSesSessionID');
        $this->db->insert('TCNTDocDTTmp', array(
            'FTBchCode'         => $aItem['FTBchCode'],
            'FTXthDocNo'        => $aItem['FTXshDocNo'],
            'FNXtdSeqNo'        => $aItem['FTXcdPdtSeq'],
            'FTXthDocKey'       => $aItem['FTXthDocKey'],
            'FTPdtCode'         => $aItem['FTPdtCode'],
            'FTSrnCode'         => $aItem['FTSrnCode'],
            'FTXtdPdtName'      => $aItem['FTXcdPdtName'],
            'FTPunCode'         => $aItem['FTPunCode'],
            'FNXtdPdtLevel'     => $aItem['FNXtdPdtLevel'],
            'FTXtdPdtStaSet'    => $aItem['FTXtdPdtStaSet'],
            'FTXtdPdtParent'    => $aItem['FTXtdPdtParent'],
            'FCXtdQtySet'       => $aItem['FCXtdQtySet'],
            'FTSessionID'       => $tSession,
            'FDLastUpdOn'       => date('Y-m-d H:i:s'),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date('Y-m-d H:i:s'),
            'FTCreateBy'        => $this->session->userdata('tSesUsername')
        ));

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
        return $aStatus;
    }

    //อัพเดทข้อมูลใน Table
    public function FSaMBKUpdateDTToTable($aWhere, $aItem){
        $this->db->set('FTXshVATInOrEx', $aItem['FTXshVATInOrEx']);
        $this->db->set('FTUsrCode', $aItem['FTUsrCode']);
        $this->db->set('FTXshApvCode', $aItem['FTXshApvCode']);
        $this->db->set('FNXshDocPrint', $aItem['FNXshDocPrint']);
        $this->db->set('FTRteCode', $aItem['FTRteCode']);
        $this->db->set('FCXshRteFac', $aItem['FCXshRteFac']);
        $this->db->set('FCXshTotal', $aItem['FCXshTotal']);
        $this->db->set('FCXshTotalNV', $aItem['FCXshTotalNV']);
        $this->db->set('FCXshTotalNoDis', $aItem['FCXshTotalNoDis']);
        $this->db->set('FCXshTotalB4DisChgV', $aItem['FCXshTotalB4DisChgV']);
        $this->db->set('FCXshTotalB4DisChgNV', $aItem['FCXshTotalB4DisChgNV']);
        $this->db->set('FTXshDisChgTxt', $aItem['FTXshDisChgTxt']);
        $this->db->set('FCXshDis', 0);
        $this->db->set('FCXshChg', 0);
        $this->db->set('FCXshTotalAfDisChgV', $aItem['FCXshTotalAfDisChgV']);
        $this->db->set('FCXshTotalAfDisChgNV', $aItem['FCXshTotalAfDisChgNV']);
        $this->db->set('FCXshAmtV', $aItem['FCXshAmtV']);
        $this->db->set('FCXshAmtNV', $aItem['FCXshAmtNV']);
        $this->db->set('FCXshVat', $aItem['FCXshVat']);
        $this->db->set('FCXshVatable', $aItem['FCXshVatable']);
        // $this->db->set('FCXshWpTax', $aItem['FCXphWpTax']);
        $this->db->set('FCXshGrand', $aItem['FCXshGrand']);
        $this->db->where('FTXshDocNo', $aWhere['tDocNo']);
        $this->db->where('FTBchCode', $aWhere['tBchCode']);
        $this->db->update('TSVTBookHD');
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

    //ลบข้อมูล
    public function FSaMBKDeleteB4Insert($ptDocumentNumber){
        $this->db->where_in('FTXshDocNo', $ptDocumentNumber);
        $this->db->delete('TSVTBookDT');

        $this->db->where_in('FTXshDocNo', $ptDocumentNumber);
        $this->db->delete('TSVTBookHD');

        $this->db->where_in('FTXshDocNo', $ptDocumentNumber);
        $this->db->delete('TSVTBookDTSet');

        $this->db->where_in('FTXshDocNo', $ptDocumentNumber);
        $this->db->delete('TSVTBookHDDocRef');
    }

    //ประวัติการเข้าใช้งาน
    public function FSaMBKCstFollow($aItem){
        try {
            $nLngID             = $this->session->userdata("tLangEdit");
            $tCustomerCode      = $aItem['tCustomerCode'];
            $tCarCode           = $aItem['tCarCode'];
            $tDateForcateFrom   = $aItem['tDateForcateFrom'];
            $tDateForcateTo     = $aItem['tDateForcateTo'];

            $tSQL       = "SELECT 
                                CAR.FTCarRegNo,
                                CAR.FTCarCode,
                                FLW.FTPdtCode,
                                FLW.FTFlwDocRef,
                                FLW.FTPdtCodeOrg,
                                FLW.FDFlwLastDate,
                                FLW.FCFlwCurMileAge,
                                FLW.FCPsvDistanc,
                                FLW.FNPsvQtyMonth,
                                FLW.FTFlwStaBook,
                                FLW.FDFlwDateForcast,
                                FLW.FTFlwRmk,
                                FLW.FTAgnCode,
                                FLW.FTBchCode,
                                PDTL.FTPdtName,
                                PDT.FTPdtSetOrSN,
                                PUNL.FTPunCode,
                                PUNL.FTPunName,
                                BAR.FTBarCode
                            FROM TSVTCstFollow FLW 
                            LEFT JOIN TCNMPdt PDT ON FLW.FTPdtCode = PDT.FTPdtCode
                            LEFT JOIN TCNMPdtPackSize PAC ON PDT.FTPdtCode = PAC.FTPdtCode AND PAC.FCPdtUnitFact = '1'
                            LEFT JOIN TCNMPdtUnit_L PUNL ON PAC.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = '$nLngID'
                            LEFT JOIN TCNMPdt_L PDTL ON FLW.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = '$nLngID'
                            LEFT JOIN TCNMPdtBar BAR ON FLW.FTPdtCode = BAR.FTPdtCode AND PUNL.FTPunCode = BAR.FTPunCode
                            LEFT JOIN TSVMCar CAR ON FLW.FTCarCode = CAR.FTCarCode 
                            WHERE CAR.FTCarCode = '$tCarCode'  AND FLW.FTFlwStaBook <> 2
                            AND (FLW.FDFlwDateForcast BETWEEN CONVERT(datetime,'$tDateForcateFrom 00:00:00') AND CONVERT(datetime,'$tDateForcateTo 23:59:59') ) ";

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aResult    = array(
                    'raItems'       => $oQuery->result_array(),
                    'rtCode'        => '1',
                    'rtDesc'        => 'success'
                );
            } else {
                $aResult    = array(
                    'raItems'       => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found'
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ยกเลิกเอกสาร
    public function FSaMBKEventCancelDocument($aItem){
        try {
            $tDocumentNumber    = $aItem['tDocumentNumber'];
            $tRSNCode           = $aItem['tRSNCode'];

            $this->db->set('FTRsnCode', $tRSNCode);
            $this->db->set('FTXshStaDoc', 3);
            $this->db->where('FTXshDocNo', $tDocumentNumber);
            $this->db->update('TSVTBookHD');

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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //อัพเดทสินค้าใน CstFollow ให้กลับมาติดตามได้ต่อ
    public function FSaMBKEventReCstFollow($aItem){
        try {
            $tDocumentNumber    = $aItem['tDocumentNumber'];

            $tSQL = "UPDATE CSTFLW
                     SET 
                        CSTFLW.FTFlwStaBook = 1
                        FROM TSVTCstFollow AS CSTFLW WITH(NOLOCK)
                        INNER JOIN (
                            SELECT 
                                HD.FTXshDocNo ,
                                HD.FTXshCstRef2 ,
                                DT.FTPdtCode
                            FROM TSVTBookHD HD WITH(NOLOCK)
                            LEFT JOIN TSVTBookDT DT ON HD.FTXshDocNo = DT.FTXshDocNo AND HD.FTBchCode = DT.FTBchCode
                            WHERE HD.FTXshDocNo = '$tDocumentNumber' 
                        ) RES 
                    ON RES.FTPdtCode = CSTFLW.FTPdtCode 
                    AND RES.FTXshCstRef2 = CSTFLW.FTCarCode ";
            $this->db->query($tSQL);
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //เช็คสต็อกก่อนยกเลิก ว่าสินค้าตัวนี้ประมวลหรือยัง ถ้าประมวลผลเเล้ว จะต้อง call MQ ให้ Move สินค้ากลับ
    public function FSaMBKCheckStockProcessInTemp($paParams){
        try {
            $tDocumentNumber = $paParams['tDocumentNumber'];
            $tBchCode        = $paParams['tBchCode'];

            $tSQL       = " SELECT 
                                DT.FTXsdStaPrcStk 
                            FROM TSVTBookDT DT 
                            WHERE DT.FTXshDocNo = '$tDocumentNumber' AND DT.FTXsdStaPrcStk = 1 AND DT.FTBchCode = '$tBchCode'
                            
                            UNION ALL
                            
                            SELECT 
                                DT.FTXsdStaPrcStk 
                            FROM TSVTBookDTSet DT 
                            WHERE DT.FTXshDocNo = '$tDocumentNumber' AND DT.FTXsdStaPrcStk = 1 AND DT.FTBchCode = '$tBchCode'  ";
            $oQuery = $this->db->query($tSQL);
            return $oQuery->num_rows();
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //เช็คสต็อกว่าสินค้าตัวนี้
    public function FSaMBKCheckSTKInTemp($ptDocNo){
        $tSQLCheckSTK = "   SELECT FTPdtCode , FTXsdStaPrcStk , 'MAIN' AS PDTSET
                            FROM TSVTBookDT DT WITH(NOLOCK)
                            WHERE DT.FTXshDocNo = '" . $ptDocNo . "'
                            AND ((ISNULL(FTXsdStaPrcStk,'') = '') OR FTXsdStaPrcStk = 2)
                        
                            UNION ALL 

                            SELECT FTPdtCode , FTXsdStaPrcStk , 'SET' AS PDTSET
                            FROM TSVTBookDTSET DT WITH(NOLOCK)
                            WHERE DT.FTXshDocNo = '" . $ptDocNo . "'
                            AND ((ISNULL(FTXsdStaPrcStk,'') = '') OR FTXsdStaPrcStk = 2) ";
        $oQueryCheckSTK = $this->db->query($tSQLCheckSTK);
        $aItemCheckSTK  = $oQueryCheckSTK->result_array();
        if (!empty($aItemCheckSTK)) {
            $aResult = array(
                'rtCode'    => '800',
                'rtItem'    => $aItemCheckSTK,
                'rtDesc'    => 'ยังมีสินค้าที่ไม่สมบูรณ์',
                'rtDoc'     => $ptDocNo
            );
        } else {
            $aResult = array(
                'rtCode'    => '1',
                'rtItem'    => array(),
                'rtDesc'    => 'สมบูรณ์',
                'rtDoc'     => $ptDocNo
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //อัพเดทเอกสาร ว่าผ่านเเล้ว
    public function FSaMBKUpdateSTKPrcAndSTAAPVDocNo($ptDocNo){
        try {
            $this->db->set('FTXshStaApv', 1);
            $this->db->set('FTXshStaPrcDoc', 2);
            $this->db->where('FTXshDocNo', $ptDocNo);
            $this->db->update('TSVTBookHD');
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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ยกเลิกการติดตามสินค้าตัวนี้
    public function FSaMBKDeleteFollow($aItem){
        try {
            $this->db->set('FTFlwStaBook', $aItem['nStaBook']);
            $this->db->set('FTFlwRmk', $aItem['tRemark']);
            $this->db->set('FTRsnCode', $aItem['tReason']);
            $this->db->where('FTPdtCode', $aItem['tPDTCode']);
            $this->db->where('FTCarCode', $aItem['tCarReg']);
            $this->db->update('TSVTCstFollow');
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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //สินค้าใน Temp ไปชนกับสินค้าใน CstFollow ไหม
    public function FSaMBKCheckPDTTempFoundINCstFollow($ptDocumentNumber, $ptType){

        // if($ptType == 1){ //นัดหมายเเล้ว
        //     $tTypeStaUpdate = '2';
        //     $tTextRmk       = "UPDATE ว่าสินค้านัดหมายเเล้ว : " . $ptDocumentNumber;
        // }else{ //นัดหมายและยืนยันเเล้ว
        //     $tTypeStaUpdate = '3';
        //     $tTextRmk       = "UPDATE ว่าสินค้านัดหมายเเล้วและยืนยันเเล้ว : " . $ptDocumentNumber;
        // }

        $tTypeStaUpdate = '2';
        $tTextRmk       = "UPDATE ว่าสินค้านัดหมายเเล้ว : " . $ptDocumentNumber;
        $tSQL           = " UPDATE CSTFLOW
                            SET 
                                CSTFLOW.FTFlwStaBook = '$tTypeStaUpdate' ,
                                CSTFLOW.FTFlwRmk = '$tTextRmk'
                            FROM TSVTBookDT DT 
                                LEFT JOIN TSVTBookHD HD ON DT.FTXshDocNo = HD.FTXshDocNo
                                LEFT JOIN TSVTCstFollow CSTFLOW ON CSTFLOW.FTCarCode = HD.FTXshCstRef2 AND DT.FTPdtCode = CSTFLOW.FTPdtCode
                            WHERE 
                                HD.FTXshDocNo = '$ptDocumentNumber' AND 
                                CSTFLOW.FTFlwStaBook = 1 ";
        $this->db->query($tSQL);
    }

    //รายละเอียดของ Booking
    public function FSaMBKGetDetailBooking($ptDocumentNumber){
        try {
            $nLngID     = $this->session->userdata("tLangEdit");
            $tSQL       = "SELECT 
                                TOP 1 
                                HD.FTXshDocNo,
                                HD.FTXshToPos,
                                POS_L.FTSpsName,
                                HD.FTAgnCode,
                                HD.FTBchCode,
                                convert(datetime, HD.FDXshTimeStart, 120) AS FDXshTimeStart,
                                convert(datetime, HD.FDXshTimeStop, 120) AS FDXshTimeStop
                            FROM TSVTBookHD HD
                            LEFT JOIN TSVMPos_L POS_L ON HD.FTXshToPos = POS_L.FTSpsCode AND HD.FTBchCode = POS_L.FTBchCode AND POS_L.FNLngID = '$nLngID' 
                            WHERE HD.FTXshDocNo = '$ptDocumentNumber' ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aResult    = array(
                    'raItems'       => $oQuery->result_array(),
                    'rtCode'        => '1',
                    'rtDesc'        => 'success'
                );
            } else {
                $aResult    = array(
                    'raItems'       => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found'
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Get ข้อมูล API
    public function FSxMBKGetConfigAPI(){
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

    //กดยืนยัน นัดหมาย -> จะเอาสินค้ากลับไปส่ง API
    public function FSaMBKGetPDTInTempToArray($ptDocumentNumber, $ptBchCode){
        $tSessionID  = $this->session->userdata('tSesSessionID');
        $tSQL       = "SELECT  
                            TMP.FTPdtCode           AS ptPdtCode,
                            TMP.FTBchCode           AS ptBchCode,
                            CASE
                                WHEN TMP.FTXtdPdtStaSet = 1 THEN PDTMAIN.FTXthWhFrmForTWXVD
                                ELSE TMP.FTXthWhFrmForTWXVD
                            END ptWahCode,
                            CASE
                                WHEN TMP.FTXtdPdtStaSet = 1 THEN TMP.FCXtdQtySet
                                ELSE TMP.FCXtdQty
                            END pcQty
                        FROM TCNTDocDTTmp TMP
                        INNER JOIN TCNMPdt PDT ON TMP.FTPdtCode = PDT.FTPdtCode
                        LEFT JOIN TCNTDocDTTmp PDTMAIN ON TMP.FTSrnCode = PDTMAIN.FTPdtCode
                        WHERE TMP.FTXthDocNo = '$ptDocumentNumber' AND
                        TMP.FTBchCode = '$ptBchCode' AND
                        TMP.FTXthDocKey = 'TSVTBookDT' AND
                        TMP.FTSessionID = '$tSessionID' AND
                        PDT.FTPdtStkControl = 1 AND
                        isnull(TMP.FTXtdStaPrcStk,'') != '1' ";
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

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //ลิสต์ข้อมูล : [1 - 4]  
    public function FSaMBKFindDataByCustomer($paParams){

        $aRowLen            = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        $nLngID             = $this->session->userdata("tLangID");
        $tTypeCondition     = $paParams['tTypeCondition'];
        $dCurrent           = date('Y-m-d');
        $tSQLConcat         = '';
        $tOrderBy           = "FTCstCode DESC";
        $tDateFrom          = $paParams['tFindDateFrom'];
        $tDateTo            = $paParams['tFindDateTo'];

        if ($tTypeCondition == '1') { //ค้นหาลูกค้าเพื่อทำแบบสอบถามความพึงพอใจ
            $tSQL = "SELECT c.*,ISNULL(convert(varchar, C.FDXsLastDocDate, 103),c.DateStart) AS LastService FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY $tOrderBy) AS FNRowID,* FROM
                    ( ";
        }else{
            $tSQL = "SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY $tOrderBy) AS FNRowID,* FROM
                    ( ";
        }

        switch ($tTypeCondition) {
            case "1":  //ค้นหาลูกค้าเพื่อทำแบบสอบถามความพึงพอใจ
                $tSQLConcat .= "SELECT A.*, MAX(LastJob.FDXshDocDate) AS FDXsLastDocDate  FROM (
                            SELECT  
                                JOB2.FTAgnCode, 
                                JOB2.FTBchCode, 
                                BL.FTBchName,
                                CST.FTCstCode, 
                                CSTL.FTCstName, 
                                CST.FTCstTel, 
                                CST.FTCstEmail, 
                                CARL.FTCaiName AS CarBrand, 
                                CARB.FTCaiName AS FTCarModel, 
                                REPLACE(REPLACE(CONCAT(cstA.FTAddV2Desc1, cstA.FTAddV2Desc2), CHAR(13), ''), CHAR(10), ' ') AS FTCstAddress, 
                                CONVERT(VARCHAR, JOB2.FDXshDocDate, 103) AS DateStart, 
                                JOB2.FTXshDocNo AS FTFlwDocRef, 
                                CAR.FTCarRegNo, 
                                CAR.FTCarCode, 
                                '' AS BOOKID, 
                                SCORE.FTXshDocNo AS SCOREPoint, 
                                HD.FCXshGrand, 
                                drf.FTXshRefDocNo, 
                                LMS.FTTxnCrdCode, 
                                RCData.FTRcvName,
                                JOB2.FTXshDocNo
                            FROM TSVTJob2OrdHD JOB2 WITH(NOLOCK)";
                $tSQLConcat .= " LEFT JOIN TSVTJob2OrdHDCst HDCst       WITH(NOLOCK) ON JOB2.FTXshDocNo = HDCst.FTXshDocNo ";
                $tSQLConcat .= " LEFT JOIN TSVTJob5ScoreHDDocRef SCORE  WITH(NOLOCK) ON JOB2.FTXshDocNo = SCORE.FTXshRefDocNo AND JOB2.FTBchCode = SCORE.FTBchCode ";
                $tSQLConcat .= " LEFT JOIN TSVMCar CAR                  WITH(NOLOCK) ON HDCst.FTCarCode = CAR.FTCarCode";
                $tSQLConcat .= " LEFT JOIN TCNMCst CST                  WITH(NOLOCK) ON CAR.FTCarOwner = CST.FTCstCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst_L CSTL               WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARL           WITH(NOLOCK) ON CAR.FTCarBrand = CARL.FTCaiCode AND CARL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARB WITH(NOLOCK) ON CAR.FTCarModel = CARB.FTCaiCode AND CARL.FNLngID = 1
                                 LEFT JOIN ( 
                                        SELECT
                                            CSTA.FTAddV2Desc1, 
                                            CSTA.FTAddV2Desc2, 
                                            CSTA.FTCstCode, 
                                            CSTA.FNLngID, 
                                            CSTA.FTAddGrpType 
                                        FROM TCNMCstAddress_L CSTA WITH(NOLOCK)
                                        WHERE CSTA.FNLngID = 1 AND CSTA.FTAddGrpType = 1) cstA ON CST.FTCstCode = cstA.FTCstCode
                                        INNER JOIN TSVTJob2OrdHDdocref drf WITH(NOLOCK) ON JOB2.FTXshDocNo = drf.FTXshDocNo AND drf.FTXshRefKey = 'ABB'
                                        INNER JOIN TPSTSalHD HD WITH(NOLOCK) ON drf.FTXshRefDocNo = HD.FTXshDocNo
                                        LEFT JOIN TLKTLmsTxnHD LMS WITH(NOLOCK) ON HD.FTXshDocNo = LMS.FTXshDocNo
                                        LEFT JOIN TCNMBranch_L BL WITH(NOLOCK) ON JOB2.FTBchCode = BL.FTBchCode
                                LEFT JOIN (
                                        SELECT 
                                            FTXshDocNo, 
                                            SUBSTRING(d.FTRcvName, 1, LEN(d.FTRcvName) - 1) FTRcvName
                                        FROM (
                                            SELECT DISTINCT FTXshDocNo FROM TPSTSalRC WITH(NOLOCK)
                                        ) A CROSS APPLY (
                                            SELECT RC.FTRcvName + ', '
                                            FROM TPSTSalRC AS RC WITH(NOLOCK)
                                            WHERE A.FTXshDocNo = RC.FTXshDocNo FOR XML PATH('')
                                        ) D(FTRcvName)
                                ) RCData ON HD.FTXshDocNo = RCData.FTXshDocNo ";
                $tSQLConcat     .= " WHERE 1 = 1 ";
                $tSQLConcat     .= " AND (JOB2.FDXshDocDate BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";

                //เห็นของสาขาตัวเอง
                if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
                    $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
                    $tSQLConcat .= " AND  JOB2.FTBchCode IN ($tBCH) ";
                }

                $tSQLConcat .= ") AS A ";
                $tSQLConcat .= " LEFT JOIN (
                                    SELECT TOP 1 HD.FTCstCode, 
                                                HDCst.FTCarCode, 
                                                HD.FTXshDocNo, 
                                                HD.FDXshDocDate
                                    FROM TSVTJob2OrdHD HD WITH(NOLOCK)
                                    INNER JOIN TSVTJob2OrdHDCst HDCst WITH(NOLOCK) ON HD.FTBchCode = HDCst.FTBchCode AND HD.FTXshDocNo = HDCst.FTXshDocNo
                                ) LastJob ON A.FTCstCode = LastJob.FTCstCode AND A.FTCarCode = LastJob.FTCarCode AND A.FTXshDocNo <> LastJob.FTXshDocNo ";
                $tSQLConcat .= " WHERE 1 = 1";
                break;
            case "2":  //ลูกค้าเพื่อนัดหมายเข้ารับบริการ
                $tSQLConcat .= "SELECT A.* , 
                                B.FTBchCode , 
                                B.FTAgnCode , 
                                CONCAT(convert(varchar,B.FDFlwLastDate, 103) , ' ( ' , convert(varchar,B.FDFlwLastDate, 8) , ' )' ) AS DateStart ,
                                convert(varchar,C.FDFlwDateForcast, 103) AS DateForcate FROM (
                            SELECT  
                            DISTINCT
                            CST.FTCstCode ,
                            CSTL.FTCstName ,
                            CST.FTCstTel ,
                            CST.FTCstEmail ,
                            CARL.FTCaiName AS CarBrand ,
                            CAR.FTCarCode,
                            CAR.FTCarRegNo ,
                            '' AS BOOKID
                        FROM TSVTCstFollow CSTFLW WITH(NOLOCK)";
                $tSQLConcat .= " LEFT JOIN TSVMCar CAR    WITH(NOLOCK) ON CSTFLW.FTCarCode = CAR.FTCarCode";
                $tSQLConcat .= " LEFT JOIN TCNMCst CST    WITH(NOLOCK) ON CAR.FTCarOwner = CST.FTCstCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON CAR.FTCarBrand = CARL.FTCaiCode AND CARL.FNLngID = $nLngID ";
                $tSQLConcat .= " WHERE 1 = 1 ";
                $tSQLConcat .= " AND ( CSTFLW.FTFlwStaBook = 1 ) AND ( CSTFLW.FDFlwDateForcast <= CONVERT(date, '$dCurrent') ";
                $tSQLConcat .= " OR (CSTFLW.FDFlwDateForcast BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";
                $tSQLConcat .= " AND (ISNULL(CSTFLW.FDFlwDateForcast,'') <> '') ) ";
                $tSQLConcat .= ") AS A 
                                INNER JOIN (
                                    SELECT A.* FROM 
                                        ( 
                                            SELECT  
                                                ROW_NUMBER() OVER (PARTITION BY A.FTCarCode ORDER BY A.FDFlwLastDate DESC) AS RowNumber , 
                                                A.FTCarCode , 
                                                A.FTBchCode,
				                                A.FTAgnCode,
                                                A.FDFlwLastDate 
                                            FROM TSVTCstFollow A WITH(NOLOCK)
                                        ) AS A 
                                    WHERE A.RowNumber = 1 
                                ) B ON A.FTCarCode = B.FTCarCode
                                INNER JOIN (
                                    SELECT A.* FROM 
                                        ( 
                                            SELECT  
                                                ROW_NUMBER() OVER (PARTITION BY A.FTCarCode ORDER BY A.FDFlwDateForcast ASC) AS RowNumber , 
                                                A.FTCarCode , 
                                                A.FTBchCode,
				                                A.FTAgnCode,
                                                A.FDFlwDateForcast
                                            FROM TSVTCstFollow A WITH(NOLOCK)
                                        ) AS A 
                                    WHERE A.RowNumber = 1 
                                ) C ON A.FTCarCode = C.FTCarCode ";
                break;
            case "3":  //ค้นหาลูกค้าเพื่อยืนยันนัดหมาย
                $tSQLConcat .= "SELECT A.* FROM (
                            SELECT  
                            CAL.FTAgnCode,
                            CAL.FTBchCode,
                            CST.FTCstCode ,
                            CSTL.FTCstName ,
                            CAL.FTXshTel AS FTCstTel ,
                            CAL.FTXshEmail AS FTCstEmail ,
                            CARL.FTCaiName AS CarBrand ,
                            CAR.FTCarRegNo ,
                            concat(convert(varchar,CAL.FDXshTimeStart , 103) , ' ( ' , convert(varchar,CAL.FDXshTimeStart , 8) , ' - ' , convert(varchar,CAL.FDXshTimeStop , 8) , ' ) ') AS DateStart ,
                            CAR.FTCarCode ,
                            CAL.FTXshDocNo AS BOOKID,
                            CAL.FDXshBookDate,
                            CAL.FNXshQtyNotiPrev
                        FROM TSVTBookHD CAL WITH(NOLOCK)";
                $tSQLConcat .= " LEFT JOIN TSVMCar CAR   WITH(NOLOCK) ON CAL.FTXshCstRef1 = CAR.FTCarOwner AND CAL.FTXshCstRef2 = CAR.FTCarCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst CST   WITH(NOLOCK) ON CAR.FTCarOwner = CST.FTCstCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON CAR.FTCarBrand = CARL.FTCaiCode AND CARL.FNLngID = $nLngID ";
                $tSQLConcat .= " WHERE ( ISNULL(CAL.FTXshStaApv,'') = '' OR CAL.FTXshStaApv = 0 ) AND FTXshStaDoc = 1 ";
                $tSQLConcat .= " AND CAL.FTXshDocNo NOT IN (
                                    SELECT DISTINCT FTXshDocNo FROM TSVTBookDT WITH(NOLOCK) WHERE ISNULL(FTXsdStaPrcStk,'') = '2' 
                                    UNION ALL 
                                    SELECT DISTINCT FTXshDocNo FROM TSVTBookDTSet WITH(NOLOCK) WHERE ISNULL(FTXsdStaPrcStk,'') = '2' 
                                ) ";
                /*$tSQLConcat .= " AND CAL.FTXshStaPrcDoc <> 3 ";*/
                $tSQLConcat .= " ) AS A  WHERE 1 = 1 ";

                //เห็นของสาขาตัวเอง
                if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
                    $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
                    $tSQLConcat .= " AND  A.FTBchCode IN ($tBCH) ";
                }
                $tSQLConcat     .= " AND (A.FDXshBookDate BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";
                break;
            case "4":  //ลูกค้าที่ไม่มาตามนัด / ยังไม่ถึงกำหนด
                $tSQLConcat .= "SELECT A.* FROM ( SELECT  
                            CAL.FTAgnCode,
                            CAL.FTBchCode,
                            CST.FTCstCode ,
                            CSTL.FTCstName ,
                            CAL.FTXshTel    AS FTCstTel ,
                            CAL.FTXshEmail  AS FTCstEmail ,
                            CARL.FTCaiName  AS CarBrand ,
                            CAR.FTCarRegNo ,
                            concat(convert(varchar,CAL.FDXshTimeStart , 103) , ' ( ' , convert(varchar,CAL.FDXshTimeStart , 8) , ' - ' , convert(varchar,CAL.FDXshTimeStop , 8) , ' ) ') AS DateStart ,
                            CAR.FTCarCode ,
                            CAL.FTXshDocNo  AS BOOKID ,
                            CAL.FTXshStaPrcDoc ,
                            CAL.FDXshBookDate ,
                            CAL.FNXshQtyNotiPrev
                        FROM TSVTBookHD CAL WITH(NOLOCK)";
                $tSQLConcat .= " LEFT JOIN TSVMCar CAR   WITH(NOLOCK) ON CAL.FTXshCstRef1 = CAR.FTCarOwner AND CAL.FTXshCstRef2 = CAR.FTCarCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst CST   WITH(NOLOCK) ON CAR.FTCarOwner = CST.FTCstCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON CAR.FTCarBrand = CARL.FTCaiCode AND CARL.FNLngID = $nLngID ";
                $tSQLConcat .= " WHERE ( CAL.FTXshStaApv = 1 ) AND FTXshStaDoc = 1 AND ISNULL(CAL.FTXshStaClosed,'') = '' ";

                //วันที่นัดน้อยกว่าปัจจุบัน
                $tSQLConcat .= " AND (CAL.FDXshBookDate BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";
                $tSQLConcat .= " ) AS A  WHERE 1 = 1 ";

                //เห็นของสาขาตัวเอง
                if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
                    $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
                    $tSQLConcat .= " AND  A.FTBchCode IN ($tBCH) ";
                }
                break;
            case "6":  //ค้นหาเอกสารแจ้งเตือนก่อนถึงวันนัด
                $tSQLConcat .= "SELECT A.* FROM (
                            SELECT  
                            CAL.FTAgnCode,
                            CAL.FTBchCode,
                            CST.FTCstCode ,
                            CSTL.FTCstName ,
                            CAL.FTXshTel AS FTCstTel ,
                            CAL.FTXshEmail AS FTCstEmail ,
                            CARL.FTCaiName AS CarBrand ,
                            CAR.FTCarRegNo ,
                            concat(convert(varchar,CAL.FDXshTimeStart , 103) , ' ( ' , convert(varchar,CAL.FDXshTimeStart , 8) , ' - ' , convert(varchar,CAL.FDXshTimeStop , 8) , ' ) ') AS DateStart ,
                            CAR.FTCarCode ,
                            CAL.FTXshDocNo AS BOOKID,
                            CAL.FDXshBookDate,
                            CAL.FNXshQtyNotiPrev
                        FROM TSVTBookHD CAL WITH(NOLOCK)";
                $tSQLConcat .= " LEFT JOIN TSVMCar CAR   WITH(NOLOCK) ON CAL.FTXshCstRef1 = CAR.FTCarOwner AND CAL.FTXshCstRef2 = CAR.FTCarCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst CST   WITH(NOLOCK) ON CAR.FTCarOwner = CST.FTCstCode ";
                $tSQLConcat .= " LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID ";
                $tSQLConcat .= " LEFT JOIN TSVMCarInfo_L CARL WITH(NOLOCK) ON CAR.FTCarBrand = CARL.FTCaiCode AND CARL.FNLngID = $nLngID ";
                $tSQLConcat .= " WHERE CAL.FTXshStaApv = 1 AND CAL.FTXshStaDoc = 1 AND ISNULL(CAL.FTXshStaClosed,'') = '' AND CAL.FNXshQtyNotiPrev > 0 ) AS A  WHERE 1 = 1 ";

                //เห็นของสาขาตัวเอง
                if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
                    $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
                    $tSQLConcat .= " AND  A.FTBchCode IN ($tBCH) ";
                }

                //แจ้งเตือนก่อนถึงวันนัด
                $tSQLConcat .= " AND (DATEADD(day, -A.FNXshQtyNotiPrev , A.FDXshBookDate) BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";
                break;
            default:
        }

        //ตัวแทนขาย
        $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
        if ($tAgnCode != "") {
            $tSQLConcat .= " AND ( ISNULL(CST.FTAgnCode,'') = '' OR CST.FTAgnCode = '$tAgnCode' ";
        }

        //ชื่อลูกค้า
        if ($paParams['tFindCus'] != '') {
            $tFindCus = $paParams['tFindCus'];
            $tSQLConcat .= " AND A.FTCstCode = '$tFindCus' ";
        }

        //อีเมลลูกค้า
        if ($paParams['tFindCusEmail'] != '') {
            $tFindCusEmail = $paParams['tFindCusEmail'];
            $tSQLConcat .= " AND A.FTCstEmail LIKE '%$tFindCusEmail%' ";
        }

        //เบอร์โทร
        if ($paParams['tFindCusTel'] != '') {
            $tFindCusTel = $paParams['tFindCusTel'];
            $tSQLConcat .= " AND A.FTCstTel LIKE '%$tFindCusTel%' ";
        }

        //ทะเบียนรถ
        if ($paParams['tFindCusCarID'] != '') {
            $tFindCusCarID = $paParams['tFindCusCarID'];
            $tSQLConcat .= " AND A.FTCarCode = '$tFindCusCarID' ";
        }

        //ค้นหาลูกค้าเพื่อทำแบบสอบถามความพึงพอใจ
        if ($tTypeCondition == '1') { 
            $tSQLConcat .= " GROUP BY A.FTAgnCode, 
                            A.FTBchCode, 
                            A.FTBchName,
                            A.FTCstCode, 
                            A.FTCstName, 
                            A.FTCstTel, 
                            A.FTCstEmail, 
                            A.CarBrand, 
                            A.FTCarModel, 
                            A.FTCstAddress, 
                            A.DateStart, 
                            A.FTFlwDocRef, 
                            A.FTCarRegNo, 
                            A.FTCarCode, 
                            A.BOOKID, 
                            A.SCOREPoint, 
                            A.FCXshGrand, 
                            A.FTXshRefDocNo, 
                            A.FTTxnCrdCode, 
                            A.FTRcvName, 
                            A.FTXshDocNo, 
                            LastJob.FTXshDocNo ";
        }
            

        $tSQL .= $tSQLConcat;            
        $tSQL .= " ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $nFoundRow  = $this->FSnMBKListGetPageAll($tSQLConcat);
            $nPageAll   = ceil($nFoundRow / $paParams['nRow']);
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //ลิสต์ข้อมูล : [5] ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย 
    public function FSaMBKFindDataByCustomerWaitItem($paParams){
        $aRowLen            = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        $nLngID             = $this->session->userdata("tLangID");
        $tSQLConcat         = '';
        $tDateFrom          = $paParams['tFindDateFrom'];
        $tDateTo            = $paParams['tFindDateTo'];

        $tSQL       = "SELECT 
                        ALLHD.* ,
                        DTSET.FTPdtCode AS CODESET ,
                        DT.FTPdtCode,
                        DTSET.FTXsdPdtName AS NAMESET ,
                        DT.FTXsdPdtName,
                        DTSET.FCXsdQtySet AS QTYSET ,
                        DT.FCXsdQty ,
                        PUN.FTPunName AS PUNSET ,
                        DT.FTPunName ,
                        ROW_NUMBER () OVER ( PARTITION BY ALLHD.FTXshDocNo ORDER BY ALLHD.FTXshDocNo ASC ) PARTITIONBYDOC ,
                        COUNT (DT.FTXshDocNo) OVER (PARTITION BY DT.FTXshDocNo) AS PARTITIONBYITEM,
                        COUNT (DT.FCXsdQty) OVER (PARTITION BY DT.FCXsdQty) AS COUNTITEMALL 
                        FROM ( SELECT c.* FROM ( ";
        $tSQLConcat .= "SELECT
                            ROW_NUMBER () OVER (ORDER BY HD.FTBchCode DESC , CONVERT (VARCHAR,HD.FDXshTimeStart,103 ) DESC ) AS FNRowID ,
                            HD.FTAgnCode,
                            HD.FTBchCode,
                            BCHL.FTBchName,
                            HD.FTXshDocNo,
                            CONVERT (VARCHAR, HD.FDXshTimeStart,103) AS DateBooking,
                            CONVERT (VARCHAR,HD.FDXshTimeStart,8 ) AS TimeStart,
                            CONVERT (VARCHAR, HD.FDXshTimeStop, 8) AS TimeEnd,
                            HD.FTXshCstRef1,
                            CSTL.FTCstCode,
                            CSTL.FTCstName,
                            HD.FTXshTel AS FTCstTel,
                            HD.FTXshEmail AS FTCstEmail
                        FROM
                            TSVTBookHD HD
                        LEFT JOIN TCNMBranch_L BCHL ON HD.FTBchCode = BCHL.FTBchCode
                        LEFT JOIN TCNMCst_L CSTL ON HD.FTXshCstRef1 = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                        WHERE HD.FTXshStaDoc = 1 AND HD.FTXshStaPrcDoc <> 2 ";

        $tSQLConcat .= " AND (HD.FDXshBookDate BETWEEN CONVERT(datetime,'$tDateFrom 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ) ";

        //เห็นของสาขาตัวเอง
        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLConcat .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        //ตัวแทนขาย
        $tAgnCode = $this->session->userdata("tSesUsrAgnCode");
        if ($tAgnCode != "") {
            $tSQLConcat .= " AND ( ISNULL(CST.FTAgnCode,'') = '' OR CST.FTAgnCode = '$tAgnCode' ";
        }

        //ชื่อลูกค้า
        if ($paParams['tFindCus'] != '') {
            $tFindCus = $paParams['tFindCus'];
            $tSQLConcat .= " AND CSTL.FTCstCode = '$tFindCus' ";
        }

        //อีเมลลูกค้า
        if ($paParams['tFindCusEmail'] != '') {
            $tFindCusEmail = $paParams['tFindCusEmail'];
            $tSQLConcat .= " AND HD.FTXshEmail LIKE '%$tFindCusEmail%' ";
        }

        //เบอร์โทร
        if ($paParams['tFindCusTel'] != '') {
            $tFindCusTel = $paParams['tFindCusTel'];
            $tSQLConcat .= " AND HD.FTXshTel LIKE '%$tFindCusTel%' ";
        }

        //ทะเบียนรถ
        if ($paParams['tFindCusCarID'] != '') {
            $tFindCusCarID = $paParams['tFindCusCarID'];
            $tSQLConcat .= " AND HD.FTXshCstRef2 = '$tFindCusCarID' ";
        }

        $tSQL .= $tSQLConcat;

        $tSQL .= " ) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL .= " ) AS ALLHD ";
        $tSQL .= " LEFT JOIN TSVTBookDT DT ON DT.FTXshDocNo = ALLHD.FTXshDocNo ";
        $tSQL .= " LEFT JOIN TSVTBookDTSET DTSET ON DTSET.FTXshDocNo = DT.FTXshDocNo AND DTSET.FNXsdSeqNo = DT.FTXsdSeq ";
        $tSQL .= " LEFT JOIN TCNMPdtUnit_L PUN ON DTSET.FTPunCode = PUN.FTPunCode AND PUN.FNLngID = $nLngID ";
        $tSQL .= " WHERE(
                            ISNULL(DT.FTXsdStaPrcStk, '') = '' OR ISNULL(DT.FTXsdStaPrcStk, '') = 2 AND DT.FTPdtCode <> '' 
                            AND
                            ISNULL(DTSET.FTXsdStaPrcStk, '') = '' OR ISNULL(DTSET.FTXsdStaPrcStk, '') = 2 AND DTSET.FTPdtCode <> ''
                        ) ";
        $tSQL .= " ORDER BY ALLHD.FTBchCode , ALLHD.DateBooking , ALLHD.FTXshDocNo , COUNT (DT.FTXshDocNo) OVER (PARTITION BY DT.FTXshDocNo) ASC , ";
        $tSQL .= " ROW_NUMBER () OVER (PARTITION BY ALLHD.FTXshDocNo ORDER BY ALLHD.FTXshDocNo ASC) ASC ";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $nFoundRow  = $this->FSnMBKListGetPageAll($tSQLConcat);
            $nPageAll   = ceil($nFoundRow / $paParams['nRow']);
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //จำนวน
    public function FSnMBKListGetPageAll($tSQL = []){
        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //โทรคอนเฟริมกับลูกค้าเเเล้ว
    public function FSaMBKUpdateConfirmByTelDone($paParams){
        $tDocNo = $paParams['FTXshDocNo'];

        //อัพเดทวันเเจ้งเตือนว่าโทรไปคอนเฟริมเเล้ว
        $tSQL = "UPDATE HD
                 SET 
                    HD.FTXshDocNo = RES.FTXshDocNo,
                    HD.FNXshQtyNotiPrev = RES.FNXshQtyNotiPrev
                 FROM TSVTBookHD AS HD WITH(NOLOCK)
                 LEFT JOIN (
                    SELECT 
                        FTXshDocNo ,
                        FNXshQtyNotiPrev * -1 AS FNXshQtyNotiPrev
                    FROM TSVTBookHD WITH(NOLOCK)
                    WHERE FTXshDocNo = '$tDocNo' 
                ) RES 
                ON RES.FTXshDocNo = HD.FTXshDocNo 
                WHERE RES.FTXshDocNo = '$tDocNo'  ";
        $this->db->query($tSQL);
    }

    //อัพเดทสินค้าใน CstFollow ให้กลับมาติดตามได้ต่อ
    public function FSaMBKUpdateToTemp($aItem){
        try {
            $tSessionID     = $this->session->userdata('tSesSessionID');
            $nFCXtdQty      = $aItem['FCXtdQty'];
            $nFCXtdQtyAll   = $aItem['FCXtdQtyAll'];
            $tDocNo         = $aItem['tDocNo'];     
            $nSeqNo         = $aItem['nSeqNo'];  

            $tSQL = "UPDATE DOCDT
                     SET 
                        DOCDT.FCXtdQty = '$nFCXtdQty' ,
                        DOCDT.FCXtdQtyAll = '$nFCXtdQtyAll' ,
                        DOCDT.FCXtdNet  =  $nFCXtdQty * RES.FCXtdSetPrice ,
                        DOCDT.FCXtdNetAfHD = $nFCXtdQty * RES.FCXtdSetPrice 
                        FROM TCNTDocDTTmp AS DOCDT WITH(NOLOCK)
                        INNER JOIN (
                            SELECT 
                                TMP.FTPdtCode ,
                                TMP.FNXtdSeqNo ,
                                TMP.FCXtdSetPrice 
                            FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                            WHERE TMP.FTXthDocNo = '$tDocNo' AND TMP.FTXthDocKey = 'TSVTBookDT' AND TMP.FTSessionID = '$tSessionID'
                            AND ISNULL(TMP.FTSrnCode,'') = ''
                            AND TMP.FNXtdSeqNo = '$nSeqNo'
                        ) RES 
                    ON DOCDT.FTPdtCode = RES.FTPdtCode 
                    AND DOCDT.FNXtdSeqNo = RES.FNXtdSeqNo
                    WHERE DOCDT.FTSessionID = '$tSessionID'
                    AND DOCDT.FTXthDocKey = 'TSVTBookDT' 
                    AND DOCDT.FTXthDocNo = '$tDocNo'
                    AND DOCDT.FNXtdSeqNo = '$nSeqNo' ";
            $this->db->query($tSQL);
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //อัพเดทสินค้าใน ว่าสต็อกไม่เพียงพอ
    public function FSaMBKUpdateSTKFail($ptDocNo , $ptTextPDT){
        try {

            $tSessionID  = $this->session->userdata('tSesSessionID');

            //อัพเดทสินค้าทุกตัวให้ผ่านก่อน สินค้าแม่
            $tSQL = "UPDATE TCNTDocDTTmp SET FTXtdStaPrcStk = 1 
                    WHERE FTXthDocNo   = '$ptDocNo'
                    AND FTXthDocKey  = 'TSVTBookDT'
                    AND FTSessionID  = '$tSessionID' ";
            $this->db->query($tSQL);

            $tSQL = "UPDATE TCNTDocDTTmp SET FTXtdStaPrcStk = 2 
                    WHERE FTPdtCode IN ($ptTextPDT)
                    AND FTXthDocNo   = '$ptDocNo'
                    AND FTXthDocKey  = 'TSVTBookDT'
                    AND FTSessionID  = '$tSessionID' ";
            $this->db->query($tSQL);

            ////////////////////////////////////////////////////////////

            //อัพเดทสินค้าทุกตัวให้ผ่านก่อน สินค้าลูก
            $tSQL = "UPDATE TCNTDocDTTmp SET FTXtdStaPrcStk = 1 
                    WHERE FTXthDocNo   = '$ptDocNo'
                    AND FTXthDocKey  = 'TSVTBookDT'
                    AND FTSessionID  = '$tSessionID'
                    AND ISNULL(FTSrnCode,'') != '' ";
            $this->db->query($tSQL);

            $tSQL = "UPDATE TCNTDocDTTmp SET FTXtdStaPrcStk = 2 
                    WHERE FTPdtCode IN ($ptTextPDT)
                    AND FTXthDocNo   = '$ptDocNo'
                    AND FTXthDocKey  = 'TSVTBookDT'
                    AND FTSessionID  = '$tSessionID'
                    AND ISNULL(FTSrnCode,'') != '' ";
            $this->db->query($tSQL);

        } catch (Exception $Error) {
            return $Error;
        }
    }

    //หาว่าลูกค้าคนนี้ มีรถอะไร เพื่อเอาค่าไป default
    public function FSaMBKFindCar($ptCstCode){
        $tSQL       = "SELECT TOP 1 TSVMCar.FTCarCode , TSVMCar.FTCarRegNo , TCNMImgObj.FTImgObj
                        FROM TSVMCar
                        LEFT JOIN TCNMImgObj ON TCNMImgObj.FTImgRefID = TSVMCar.FTCarCode AND TCNMImgObj.FTImgTable = 'TSVMCar'
                        WHERE TSVMCar.FTCarOwner = '$ptCstCode' ";
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
}
