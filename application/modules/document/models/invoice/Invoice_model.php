<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_model extends CI_Model{

    //Datatable
    public function FSaMIVList($paData){
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
        SELECT TOP ". get_cookie('nShowRecordInPageList')." c.*
                                FROM(
                                SELECT DISTINCT
                                COUNT ( HDDocRef_in.FTXshDocNo ) OVER ( PARTITION BY HD.FTXphDocNo ) AS PARTITIONBYDOC,
                                BCHL.FTBchName,
                                HD.FTBchCode,
                                HD.FTXphDocNo,
                                CONVERT(CHAR(10),HD.FDXphDocDate,103) AS FDXphDocDate,
                                HD.FTXphRefInt,
                                CONVERT(CHAR(10),HD.FDXphRefIntDate,103) AS FDXphRefIntDate,
                                HD.FTXphStaDoc,
                                HD.FTAgnCode,
                                AGN_L.FTAgnName,
                                HD.FTXphStaApv,
                                HD.FTXphStaPrcDoc,
                                HD.FTCreateBy,
                                HD.FDCreateOn,
                                HD.FTXphApvCode,
                                USRL.FTUsrName                  AS FTCreateByName,
                                USRLAPV.FTUsrName               AS FTXphApvName,
                                SPL_L.FTSplName,
                                POHD.FTXphBchTo,
                                DOHD.FTBchCode AS FTXphBchToDO,
                                POBCHL.FTBchName AS BchNameTo,
                                DOBCHL.FTBchName AS BchNameToDO,
                                HDDocRef_in.FTXshRefDocNo                           AS DocRefIn, 
                                CONVERT(CHAR(10),HDDocRef_in.FDXshRefDocDate,103)   AS DateRefIn
                            FROM [TAPTPiHD] HD WITH (NOLOCK)
                            LEFT JOIN TAPTPiHDDocRef    HDDocRef_in WITH (NOLOCK) ON HD.FTXphDocNo  = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1
                            LEFT JOIN TAPTPoHD          POHD        WITH (NOLOCK) ON POHD.FTXphDocNo  = HDDocRef_in.FTXshRefDocNo
                            LEFT JOIN TAPTDoHD          DOHD        WITH ( NOLOCK ) ON DOHD.FTXphDocNo = HDDocRef_in.FTXshRefDocNo
                            LEFT JOIN TCNMBranch_L      BCHL        WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMBranch_L      POBCHL      WITH (NOLOCK) ON POHD.FTXphBchTo = POBCHL.FTBchCode AND POBCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMBranch_L      DOBCHL      WITH ( NOLOCK ) ON DOHD.FTBchCode = DOBCHL.FTBchCode AND DOBCHL.FNLngID = 1
                            LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK) ON HD.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L        USRLAPV     WITH (NOLOCK) ON HD.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID 
                            LEFT JOIN TCNMSpl_L         SPL_L       WITH (NOLOCK) ON HD.FTSplCode = SPL_L.FTSplCode AND SPL_L.FNLngID = $nLngID 
                            LEFT JOIN TCNMAgency_L      AGN_L       WITH (NOLOCK) ON HD.FTAgnCode = AGN_L.FTAgnCode AND AGN_L.FNLngID = $nLngID 
                            WHERE 1=1 AND HD.FNXphDocType = 12 ";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if (@$tSearchList != '') {
            $tSQL   .= " AND ((HD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }

        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH    = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL   .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) { //อนุมัติแล้ว
                $tSQL   .= " AND HD.FTXphStaDoc = '$tSearchStaDoc' AND ISNULL(HD.FTXphStaApv,'') <> '' ";
            } else if ($tSearchStaDoc == 2) { //รออนุมัติ
                $tSQL   .= " AND HD.FTXphStaDoc = '1' AND ISNULL(HD.FTXphStaApv,'') = '' ";
            } else if ($tSearchStaDoc == 3) { //ยกเลิก
                $tSQL   .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
            } else {
                $tSQL   .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
            }
        }

        /*สถานะเคลื่อนไหว*/
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 2) {
                $tSQL   .= " AND HD.FNXphStaDocAct = '0' OR HD.FNXphStaDocAct = '' ";
            } else {
                $tSQL   .= " AND HD.FNXphStaDocAct = '$tSearchStaDocAct'";
            }
        }

        $tSQL .= ") AS c ORDER BY C.FDCreateOn DESC";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $nFoundRow  = 0;
            $nPageAll   = ceil($nFoundRow / $paData['nRow']);
            $aResult    = array(
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

    //จำนวน
    public function FSnMIVGetPageAll($paData){
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "SELECT COUNT (HD.FTXphDocNo) AS counts
                        FROM [TAPTPiHD] HD WITH (NOLOCK) 
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                        WHERE 1=1 AND HD.FNXphDocType = 12 ";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if (@$tSearchList != '') {
            $tSQL .= " AND ((HD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }

        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) { //อนุมัติแล้ว
                $tSQL .= " AND HD.FTXphStaDoc = '$tSearchStaDoc' AND ISNULL(HD.FTXphStaApv,'') <> '' ";
            } else if ($tSearchStaDoc == 2) { //รออนุมัติ
                $tSQL .= " AND HD.FTXphStaDoc = '1' AND ISNULL(HD.FTXphStaApv,'') = '' ";
            } else if ($tSearchStaDoc == 3) { //ยกเลิก
                $tSQL .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
            } else {
                $tSQL .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
            }
        }

        /*สถานะเคลื่อนไหว*/
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 2) {
                $tSQL .= " AND HD.FNXphStaDocAct = '0' OR HD.FNXphStaDocAct = '' ";
            } else {
                $tSQL .= " AND HD.FNXphStaDocAct = '$tSearchStaDocAct'";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    //สินค้าใน DT
    public function FSaMIVGetDocDTTempListPage($paDataWhere){
        $tDocNo             = $paDataWhere['FTXthDocNo'];
        $tDocKey            = $paDataWhere['FTXthDocKey'];
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.FTBchCode,
                                        DOCTMP.FTXthDocNo,
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
                                        DOCTMP.FCXtdAmtB4DisChg,
                                        DOCTMP.FTXtdDisChgTxt,
                                        DOCTMP.FCXtdNet,
                                        DOCTMP.FCXtdNetAfHD,
                                        DOCTMP.FTXtdStaAlwDis,
                                        DOCTMP.FTTmpRemark,
                                        DOCTMP.FCXtdVatRate,
                                        DOCTMP.FTXtdVatType,
                                        DOCTMP.FTSrnCode,
                                        DOCTMP.FTTmpStatus,
                                        DOCTMP.FDLastUpdOn,
                                        DOCTMP.FDCreateOn,
                                        DOCTMP.FTLastUpdBy,
                                        DOCTMP.FTCreateBy
                                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                    WHERE 1 = 1
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tDocNo'
                                    AND DOCTMP.FTXthDocKey = '$tDocKey'
                                    AND DOCTMP.FTSessionID = '$tSesSessionID' ";
        $tSQL               .= ") Base) AS c ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }

    //รายละเอียดสินค้า และราคา ใน Master
    public function FSaMIVGetDataPdt($paDataPdtParams){
        $tPdtCode   = $paDataPdtParams['tPdtCode'];
        $FTPunCode  = $paDataPdtParams['tPunCode'];
        $FTBarCode  = $paDataPdtParams['tBarCode'];
        $nLngID     = $paDataPdtParams['nLngID'];
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
                            SPL.FCSplLastPrice
                        FROM TCNMPdt PDT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
                        INNER JOIN (
                            SELECT A.* FROM(
                                SELECT  
                                    ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                    FTVatCode , 
                                    FCVatRate 
                                FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                            ) AS A WHERE A.RowNumber = 1 
                        ) VAT ON PDT.FTVatCode = VAT.FTVatCode
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

        if (isset($FTBarCode) && !empty($FTBarCode)) {
            $tSQL   .= " AND BAR.FTBarCode = '$FTBarCode'";
        }

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $aResult    = array(
                'raItem'    => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aResult;
    }

    //เพิ่มข้อมูลใน Temp
    public function FSaMIVInsertPDTToTemp($paDataPdtMaster, $paDataPdtParams){
        $paItemDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tIVOptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "   SELECT
                                FNXtdSeqNo, 
                                FCXtdQty
                            FROM TCNTDocDTTmp
                            WHERE 1=1 
                            AND FTXthDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                            AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                            AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                            AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                            AND FTPdtCode       = '" . $paItemDataPdt["FTPdtCode"] . "'
                            AND FTXtdBarCode    = '" . $paItemDataPdt["FTBarCode"] . "'
                            ORDER BY FNXtdSeqNo ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "   UPDATE TCNTDocDTTmp
                                    SET FCXtdQty = '" . ($aResult["FCXtdQty"] + 1) . "'
                                    WHERE 1=1
                                    AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                                    AND FTXthDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                                    AND FNXtdSeqNo      = '" . $aResult["FNXtdSeqNo"] . "'
                                    AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                                    AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                                    AND FTPdtCode       = '" . $paItemDataPdt["FTPdtCode"] . "'
                                    AND FTXtdBarCode    = '" . $paItemDataPdt["FTBarCode"] . "' ";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            } else {
                // เพิ่มรายการใหม่
                $aDataInsert    = array(
                    'FTBchCode'         => $paDataPdtParams['tBchCode'],
                    'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                    'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                    'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                    'FTPdtCode'         => $paItemDataPdt['FTPdtCode'],
                    'FTXtdPdtName'      => $paItemDataPdt['FTPdtName'],
                    'FCXtdFactor'       => $paItemDataPdt['FCPdtUnitFact'],
                    'FTPunCode'         => $paItemDataPdt['FTPunCode'],
                    'FTPunName'         => $paItemDataPdt['FTPunName'],
                    'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                    'FTXtdVatType'      => $paItemDataPdt['FTPdtStaVatBuy'],
                    'FTVatCode'         => $paDataPdtParams['tVatCode'],
                    'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                    'FTXtdStaAlwDis'    => $paItemDataPdt['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $paItemDataPdt['FTPdtSaleType'],
                    'FCXtdSalePrice'    => $paItemDataPdt['FCPdtCostStd'],
                    'FTTmpStatus'       => $paItemDataPdt['FTPdtType'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1 * $paItemDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paItemDataPdt['FCPdtCostStd'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                );
                $this->db->insert('TCNTDocDTTmp', $aDataInsert);
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode'    => '1',
                        'rtDesc'    => 'Add Success.',
                    );
                } else {
                    $aStatus = array(
                        'rtCode'    => '905',
                        'rtDesc'    => 'Error Cannot Add.',
                    );
                }
            }
        } else {
            // เพิ่มแถวใหม่
            $aDataInsert    = array(
                'FTBchCode'         => $paDataPdtParams['tBchCode'],
                'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                'FTPdtCode'         => $paItemDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paItemDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paItemDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paItemDataPdt['FTPunCode'],
                'FTPunName'         => $paItemDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                'FTXtdVatType'      => $paItemDataPdt['FTPdtStaVatBuy'],
                'FTVatCode'         => $paDataPdtParams['tVatCode'],
                'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                'FTXtdStaAlwDis'    => $paItemDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paItemDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paItemDataPdt['FCPdtCostStd'],
                'FTTmpStatus'       => $paItemDataPdt['FTPdtType'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1 * $paItemDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paItemDataPdt['FCPdtCostStd'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            );
            $this->db->insert('TCNTDocDTTmp', $aDataInsert);
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            } else {
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp [รายการเดียว]
    public function FSnMIVDelDTTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTXthDocNo', $paData['FTXphDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXpdSeqNo']);
            $this->db->where_in('FTPdtCode',  $paData['FTPdtCode']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->where_in('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->delete('TCNTDocDTTmp');

            //Del DTDisTmp
            $this->db->where_in('FTXthDocNo', $paData['FTXphDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXpdSeqNo']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTDisTmp');

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

    //ลบข้อมูลใน Temp [หลายรายการ]
    public function FSaMIVPdtTmpMultiDel($paData){
        try {
            $this->db->trans_begin();

            //Del DTTmp
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            //Del DTDisTmp
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTDisTmp');

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

    //เช็คว่ามีสินค้าใน DocDT Temp ไหม
    public function FSnMIVChkPdtInDocDTTemp($paDataWhere){
        $tIVDocNo       = $paDataWhere['FTXphDocNo'];
        $tIVDocKey      = $paDataWhere['FTXthDocKey'];
        $tIVSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocNo    = '$tIVDocNo'
                            AND DocDT.FTXthDocKey   = '$tIVDocKey'
                            AND DocDT.FTSessionID   = '$tIVSessionID' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        } else {
            return 0;
        }
    }

    // หาว่า VAT ตัวสุดท้าย
    public function FSaMIVCalVatLastDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSessionID     = $paData['tSessionID'];
        $tDataVatInOrEx = $paData['tDataVatInOrEx'];
        $tDocKey        = $paData['tDocKey'];
        $cSumFCXtdVat   = " 
            SELECT
                SUM (ISNULL(DOCTMP.FCXtdVat, 0)) AS FCXtdVat
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID <> ''
            AND DOCTMP.FTSessionID  = '$tSessionID'
            AND DOCTMP.FTXthDocKey  = '$tDocKey'
            AND DOCTMP.FTXthDocNo   = '$tDocNo'
            AND DOCTMP.FCXtdVatRate > 0 
        ";
        $tSql   = "
            UPDATE TCNTDocDTTmp
            SET FCXtdVat = (
                    ($cSumFCXtdVat) - (
                        SELECT
                            SUM (DTTMP.FCXtdVat) AS FCXtdVat
                        FROM TCNTDocDTTmp DTTMP
                        WHERE DTTMP.FTSessionID = '$tSessionID'
                        AND DTTMP.FTXthDocNo = '$tDocNo'
                        AND DTTMP.FTXtdVatType = 1
                        AND DTTMP.FNXtdSeqNo != (
                            SELECT
                                TOP 1 SUBDTTMP.FNXtdSeqNo
                            FROM
                                TCNTDocDTTmp SUBDTTMP
                            WHERE
                                SUBDTTMP.FTSessionID = '$tSessionID'
                            AND SUBDTTMP.FTXthDocNo = '$tDocNo'
                            AND SUBDTTMP.FTXtdVatType = 1
                            ORDER BY
                                SUBDTTMP.FNXtdSeqNo DESC
                        )
                    )
                ),
                FCXtdVatable = (
                    CASE
                        WHEN $tDataVatInOrEx  = 1 --รวมใน 
                        THEN FCXtdNet - (
                            ($cSumFCXtdVat) - (
                                SELECT
                                    SUM (DTTMP.FCXtdVat) AS FCXtdVat
                                FROM
                                    TCNTDocDTTmp DTTMP
                                WHERE
                                    DTTMP.FTSessionID = '$tSessionID'
                                AND DTTMP.FTXthDocNo = '$tDocNo'
                                AND DTTMP.FTXtdVatType = 1
                                AND DTTMP.FNXtdSeqNo != (
                                    SELECT
                                        TOP 1 SUBDTTMP.FNXtdSeqNo
                                    FROM
                                        TCNTDocDTTmp SUBDTTMP
                                    WHERE
                                        SUBDTTMP.FTSessionID = '$tSessionID'
                                    AND SUBDTTMP.FTXthDocNo = '$tDocNo'
                                    AND SUBDTTMP.FTXtdVatType = 1
                                    ORDER BY
                                        SUBDTTMP.FNXtdSeqNo DESC
                                )
                            )
                        )
                        WHEN $tDataVatInOrEx  = 2 --แยกนอก
                        THEN FCXtdNetAfHD
                    ELSE 0 END 
                )
                WHERE FTSessionID = '$tSessionID'
                AND FTXthDocNo = '$tDocNo'
                AND FNXtdSeqNo = (
                    SELECT TOP 1 FNXtdSeqNo
                    FROM TCNTDocDTTmp WHDTTMP
                    WHERE WHDTTMP.FTSessionID = '$tSessionID'
                    AND WHDTTMP.FTXthDocNo = '$tDocNo'
                    AND WHDTTMP.FTXtdVatType = 1
                    ORDER BY WHDTTMP.FNXtdSeqNo DESC
                )
        ";

        $nRSCounDT  =  $this->db->where('FTSessionID', $tSessionID)->where('FTXthDocNo', $tDocNo)->where('FTXtdVatType', '1')->get('TCNTDocDTTmp')->num_rows();
        if ($nRSCounDT > 1) {
            $this->db->query($tSql);
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'cannot Delete Item.',
                );
            }
        } else {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }
        return $aStatus;
    }

    // คำนวณ VAT
    public function FSaMIVCalInDTTemp($paParams){
        $tDocNo         = $paParams['tDocNo'];
        $tDocKey        = $paParams['tDocKey'];
        $tBchCode       = $paParams['tBchCode'];
        $tSessionID     = $paParams['tSessionID'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];
        $tSQL   = " SELECT
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


    // ****************** Update แก้ไข การ คำนวณ Vat โดย User มีการ Key ข้อมูลเข้ามา ******************

    // หาว่า VAT ตัวสุดท้าย DT ในกรณีที่ User Key ข้อมูลเข้ามา
    public function FSaMIVCalVatLastDTUsrKeyManual($paData){
        $tBchCode           = $paData['tBchCode'];
        $tDocNo             = $paData['tDocNo'];
        $tDataVatInOrEx     = $paData['tDataVatInOrEx'];
        $tInputVatUsrKey    = $paData['tInputVatUsrKey'];
        $tSql   = "
            UPDATE TAPTPiDT 
            SET
                FCXpdVat = (    
                    (SELECT CONVERT(FLOAT,'$tInputVatUsrKey') AS FCXpdVatKey) - (	
                        SELECT
                            SUM(ISNULL(DT.FCXpdVat, 0)) AS FCXpdVat 
                        FROM TAPTPiDT AS DT WITH(NOLOCK)
                        WHERE DT.FTBchCode  = ".$this->db->escape($tBchCode)."
                        AND DT.FTXphDocNo   = ".$this->db->escape($tDocNo)."
                        AND DT.FTXpdVatType	= 1
                        AND DT.FCXpdVatRate	 > 0
                        AND DT.FNXpdSeqNo != (
                            SELECT TOP 1 FNXpdSeqNo
                            FROM TAPTPiDT WHDT
                            WHERE WHDT.FTBchCode	= ".$this->db->escape($tBchCode)."
                            AND WHDT.FTXphDocNo		= ".$this->db->escape($tDocNo)."
                            AND WHDT.FTXpdVatType	= 1
                            ORDER BY WHDT.FNXpdSeqNo DESC
                        )
                    )
                ),
                FCXpdVatable = (
                    CASE 
                    WHEN $tDataVatInOrEx = 1 -- รวมใน  
                        THEN
                            TAPTPiDT.FCXpdNet - (
                                (SELECT CONVERT(FLOAT,'$tInputVatUsrKey') AS FCXpdVatKey) - (
                                    SELECT SUM(ISNULL(DT.FCXpdVat, 0)) AS FCXpdVat 
                                    FROM TAPTPiDT AS DT WITH(NOLOCK)
                                    WHERE DT.FTBchCode	= ".$this->db->escape($tBchCode)."
                                    AND DT.FTXphDocNo	= ".$this->db->escape($tDocNo)."
                                    AND DT.FTXpdVatType	= 1
                                    AND DT.FCXpdVatRate	 > 0
                                    AND DT.FNXpdSeqNo != (
                                        SELECT TOP 1 FNXpdSeqNo
                                        FROM TAPTPiDT WHDT
                                        WHERE WHDT.FTBchCode	= ".$this->db->escape($tBchCode)."
                                        AND WHDT.FTXphDocNo		= ".$this->db->escape($tDocNo)."
                                        AND WHDT.FTXpdVatType	= 1
                                        ORDER BY WHDT.FNXpdSeqNo DESC
                                    )
                                )
                            )
                    WHEN $tDataVatInOrEx = 2 -- แยกนอก
                        THEN TAPTPiDT.FCXpdNetAfHD
                    ELSE 0 END 
                )
            WHERE TAPTPiDT.FTBchCode	= ".$this->db->escape($tBchCode)."
            AND TAPTPiDT.FTXphDocNo		= ".$this->db->escape($tDocNo)."
            AND TAPTPiDT.FNXpdSeqNo		= (
                SELECT TOP 1 FNXpdSeqNo
                FROM TAPTPiDT WHDT
                WHERE WHDT.FTBchCode	= ".$this->db->escape($tBchCode)."
                AND WHDT.FTXphDocNo		= ".$this->db->escape($tDocNo)."
                AND WHDT.FTXpdVatType	= 1
                ORDER BY WHDT.FNXpdSeqNo DESC
            )
        ";
        $nRSCounDT  =  $this->db->where('FTBchCode',$tBchCode)->where('FTXphDocNo',$tDocNo)->where('FTXpdVatType', '1')->get('TAPTPiDT')->num_rows();
        if ($nRSCounDT > 1) {
            $this->db->query($tSql);
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'cannot Delete Item.',
                );
            }
        } else {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }
        return $aStatus;
    }

    // คำนวณ VAT HD หาว่า VAT ตัวสุดท้าย DT ในกรณีที่ User Key ข้อมูลเข้ามา
    public function FSaMIVCalInDTKeyInputUser($paParams){
        $tBchCode       = $paParams['tBchCode'];
        $tDocNo         = $paParams['tDocNo'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];
        $tSQL   = " SELECT
                            /* ยอดรวม ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXpdNet, 0)) AS FCXphTotal,

                            /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END) AS FCXphTotalNV,

                            /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END) AS FCXphTotalNoDis,

                            /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgV,

                            /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                            SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END) AS FCXphTotalB4DisChgNV,

                            /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgV,

                            /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgNV,

                            /* ยอดรวมเฉพาะภาษี ==============================================================*/
                            (
                                CASE 
                                    WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                                        (
                                            /* ยอดรวม */
                                            SUM(DTTMP.FCXpdNet)
                                            - 
                                            /* ยอดรวมสินค้าไม่มีภาษี */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                        -
                                        (
                                            /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                            -
                                            /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                    WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                                    
                                            (
                                                /* ยอดรวม */
                                                SUM(DTTMP.FCXpdNet)
                                                - 
                                                /* ยอดรวมสินค้าไม่มีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                            -
                                            (
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                                -
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN 
                                                            ISNULL(DTTMP.FCXpdNetAfHD, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            ) 
                                            + 
                                            SUM(ISNULL(DTTMP.FCXpdVat, 0))
                                ELSE 0 END
                            ) AS FCXphAmtV,

                            /* ยอดรวมเฉพาะไม่มีภาษี ==============================================================*/
                            (
                                SUM(CASE WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END)
                                -
                                (
                                    SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdAmtB4DisChg, 0) ELSE 0 END)
                                    -
                                    SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0) ELSE 0 END)
                                )
                            ) AS FCXphAmtNV,

                            /* ยอดภาษี ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXpdVat, 0)) AS FCXphVat,

                            /* ยอดแยกภาษี ==============================================================*/
                            (
                                (
                                    CASE 
                                        WHEN $tDataVatInOrEx = 1 THEN --รวมใน
                                            (
                                                /* ยอดรวม */
                                                SUM(DTTMP.FCXpdNet)
                                                - 
                                                /* ยอดรวมสินค้าไม่มีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                            -
                                            (
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                        ELSE 0
                                                    END
                                                )
                                                -
                                                /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                SUM(
                                                    CASE
                                                        WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0)
                                                        ELSE 0
                                                    END
                                                )
                                            )
                                        WHEN $tDataVatInOrEx = 2 THEN --แยกนอก
                                        
                                                (
                                                    /* ยอดรวม */
                                                    SUM(DTTMP.FCXpdNet)
                                                    - 
                                                    /* ยอดรวมสินค้าไม่มีภาษี */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                )
                                                -
                                                (
                                                    /* ยอมรวมสินค้าลดได้ และมีภาษี */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN ISNULL(DTTMP.FCXpdNet, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                    -
                                                    /* ยอมรวมสินค้าลดได้ และมีภาษี FCXphTotalAfDisChgV */
                                                    SUM(
                                                        CASE
                                                            WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 1 THEN 
                                                                ISNULL(DTTMP.FCXpdNetAfHD, 0)
                                                            ELSE 0
                                                        END
                                                    )
                                                ) 
                                                + 
                                                SUM(ISNULL(DTTMP.FCXpdVat, 0))
                                    ELSE 0 END
                                    - 
                                    SUM(ISNULL(DTTMP.FCXpdVat, 0))
                                )
                                +
                                (
                                    SUM(CASE WHEN DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNet, 0) ELSE 0 END)
                                    -
                                    (
                                        SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdAmtB4DisChg, 0) ELSE 0 END)
                                        -
                                        SUM(CASE WHEN DTTMP.FTXpdStaAlwDis = 1 AND DTTMP.FTXpdVatType = 2 THEN ISNULL(DTTMP.FCXpdNetAfHD, 0) ELSE 0 END)
                                    )
                                )
                            ) AS FCXphVatable,

                            /* รหัสอัตราภาษี ณ ที่จ่าย ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXpdWhtCode
                                FROM TAPTPiDT DOCCONCAT
                                WHERE  1=1 
                                AND DOCCONCAT.FTBchCode     = '$tBchCode'
                                AND DOCCONCAT.FTXphDocNo    = '$tDocNo'
                            FOR XML PATH('')), 1, 1, '') AS FTXphWpCode,

                            /* ภาษีหัก ณ ที่จ่าย ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXpdWhtAmt, 0)) AS FCXphWpTax

                        FROM TAPTPiDT DTTMP
                        WHERE DTTMP.FTXphDocNo  = '$tDocNo' 
                        AND DTTMP.FTBchCode = '$tBchCode'
                        GROUP BY DTTMP.FTXphDocNo 
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->result_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    // Update ตาราง HD 
    public function FSaMIVUpdHDKeyInputUser($paDataCalc,$paParams){
        $this->db->trans_begin();
        // Update Vat และ Vat Table ตาราง HD
        $this->db->where('FTBchCode', $paParams['tBchCode']);
        $this->db->where('FTXphDocNo', $paParams['tDocNo']);
        $this->db->update('TAPTPiHD', array(
            'FCXphVat'      => $paDataCalc['FCXphVat'],
            'FCXphVatable'  => $paDataCalc['FCXphVatable']
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aResult = array(
                'nStaEvent' => '800',
                'tStaMessg' => 'Update Vat / VatTable HD Error'
            );
        } else {
            $this->db->trans_commit();
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Update Vat / VatTable HD Success'
            );
        }
        return $aResult;
    }

    // *******************************************************************************************










    //คำนวณ HD Dis
    public function FSaMIVCalInHDDisTemp($paParams){
        $tDocNo     = $paParams['tDocNo'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tSQL       = " SELECT
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
                        WHERE 1=1 
                        AND HDDISTMP.FTXthDocNo     = '$tDocNo' 
                        AND HDDISTMP.FTSessionID    = '$tSessionID'
                        AND HDDISTMP.FTBchCode      = '$tBchCode'
                        GROUP BY HDDISTMP.FTSessionID ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        } else {
            $aResult    = [];
        }
        return $aResult;
    }

    //เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม where session
    public function FSaMIVDeletePDTInTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocHDDisTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocHDRefTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTDisTmp');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        return $aStatus;
    }

    //อัพเดทส่วนลด
    public function FSaMIVUpdateInlineDTTemp($paDataUpdateDT, $paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tIVDocNo       = $paDataWhere['tIVDocNo'];
        $tIVBchCode     = $paDataWhere['tIVBchCode'];
        $nIVSeqNo       = $paDataWhere['nIVSeqNo'];
        $tDocKey        = $paDataWhere['tDocKey'];

        $tSQL = "SELECT
                    PKS.FCPdtUnitFact
                    FROM
                TCNTDocDTTmp DTTEMP
                LEFT OUTER JOIN TCNMPdtPackSize PKS WITH (NOLOCK) ON DTTEMP.FTPdtCode = PKS.FTPdtCode AND DTTEMP.FTPunCode = PKS.FTPunCode
                WHERE
                    FTSessionID = '$tSessionID'
                    AND FTBchCode = '$tIVBchCode'
                    AND FTXthDocNo = '$tIVDocNo'
                    AND FNXtdSeqNo = $nIVSeqNo ";
        $cPdtUnitFact = $this->db->query($tSQL)->row_array()['FCPdtUnitFact'];

        if ($cPdtUnitFact > 0) {
            $cPdtUnitFact = $cPdtUnitFact;
        } else {
            $cPdtUnitFact = 1;
        }

        $this->db->set('FCXtdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCXtdSetPrice', $paDataUpdateDT['FCXtdSetPrice']);
        $this->db->set('FCXtdNet', $paDataUpdateDT['FCXtdNet']);
        $this->db->set('FTXtdPdtName', $paDataUpdateDT['FTXtdPdtName']);
        $this->db->set('FCXtdQtyAll', $paDataUpdateDT['FCXtdQty'] * $cPdtUnitFact);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FNXtdSeqNo', $nIVSeqNo);
        $this->db->where('FTXthDocNo', $tIVDocNo);
        $this->db->where('FTBchCode', $tIVBchCode);
        $this->db->update('TCNTDocDTTmp');
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

    //ล้างค่าส่วนลดรายการ อัพเดทส่วนลดรายการ 
    public function FSaMIVDeleteDTDisTemp($paParams){
        $tIVDocNo       = $paParams['tIVDocNo'];
        $nIVSeqNo       = $paParams['nIVSeqNo'];
        $tIVBchCode     = $paParams['tIVBchCode'];
        $nStaDelDis     = $paParams['nStaDelDis'];
        $tSessionID     = $paParams['tSessionID'];
        $this->db->where_in('FTSessionID', $tSessionID);
        if (isset($nIVSeqNo) && !empty($nIVSeqNo)) {
            $this->db->where_in('FNXtdSeqNo', $nIVSeqNo);
        }
        $this->db->where_in('FTBchCode', $tIVBchCode);
        $this->db->where_in('FTXthDocNo', $tIVDocNo);
        if (isset($nStaDelDis) && !empty($nStaDelDis)) {
            $this->db->where_in('FNXtdStaDis', $nStaDelDis);
        }
        $this->db->delete('TCNTDocDTDisTmp');
        return;
    }

    //ล้างค่าส่วนใน ตาราง DT
    public function FSaMIVClearDisChgTxtDTTemp($paParams){
        $tIVDocNo       = $paParams['tIVDocNo'];
        $nIVSeqNo       = $paParams['nIVSeqNo'];
        $tIVBchCode     = $paParams['tIVBchCode'];
        $tSessionID     = $paParams['tSessionID'];

        //อัพเดทให้เป็นค่าว่าง ใน Temp
        $this->db->set('FTXtdDisChgTxt', '');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FNXtdSeqNo', $nIVSeqNo);
        $this->db->where_in('FTBchCode', $tIVBchCode);
        $this->db->where_in('FTXthDocNo', $tIVDocNo);
        $this->db->update('TCNTDocDTTmp');
        return;
    }

    ////////////////////////////////////////////// บันทึกข้อมูล //////////////////////////////////////////////

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMIVAddUpdateHD($paDataMaster, $paDataWhere, $paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMIVGetDataDocHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if (isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1) {
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['DateOn'],
                'FTCreateBy'    => $aDataHDOld['CreateBy']
            ));
        } else {
            $aDataAddUpdateHD   = array_merge($paDataMaster, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        // Delete HD
        $this->db->where_in('FTBchCode', $aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXphDocNo', $aDataAddUpdateHD['FTXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);
        // Insert HD 
        $this->db->insert($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
        return;
    }

    //ข้อมูล SPL ลบและ เพิ่มใหม่
    public function FSxMIVAddUpdateSPLHD($paDataSPLHD, $paDataWhere, $paTableAddUpdate){
        $aDataGetDataSPLHD  = $this->FSaMIVGetDataDocSPLHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));
        $aDataAddUpdateSPLHD    = array();
        if (isset($aDataGetDataSPLHD['rtCode']) && $aDataGetDataSPLHD['rtCode'] == 1) {
            $aDataAddUpdateSPLHD    = array_merge($paDataSPLHD, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            ));
        } else {
            $aDataAddUpdateSPLHD    = array_merge($paDataSPLHD, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo']
            ));
        }
        // Delete SPL
        $this->db->where_in('FTBchCode', $aDataAddUpdateSPLHD['FTBchCode']);
        $this->db->where_in('FTXphDocNo', $aDataAddUpdateSPLHD['FTXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDSPL']);
        // Insert SPL
        $this->db->insert($paTableAddUpdate['tTableHDSPL'], $aDataAddUpdateSPLHD);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMIVAddUpdateDocNoToTemp($paDataWhere, $paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', $paTableAddUpdate['tTableDT']);
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into HDDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDDisTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into DTDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocDTDisTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));


        // Update DocNo Into DTDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDRefTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));

        return;
    }

    //ข้อมูล HDDis
    public function FSaMIVMoveHDDisTempToHDDis($paDataWhere, $paTableAddUpdate){
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXphDocNo', $tDocNo);
            $this->db->where_in('FTBchCode', $tBchCode);
            $this->db->delete($paTableAddUpdate['tTableHDDis']);
        }

        $tSQL   =   "   INSERT INTO " . $paTableAddUpdate['tTableHDDis'] . " (
                            FTBchCode,
                            FTXphDocNo,
                            FDXphDateIns,
                            FTXphDisChgTxt,
                            FTXphDisChgType,
                            FCXphTotalAfDisChg,
                            FCXphDisChg,
                            FCXphAmt
                        )";
        $tSQL   .=  "   SELECT
                            HDDISTEMP.FTBchCode             AS FTBchCode,
                            HDDISTEMP.FTXthDocNo            AS FTXshDocNo,
                            HDDISTEMP.FDXtdDateIns          AS FDXhdDateIns,
                            HDDISTEMP.FTXtdDisChgTxt        AS FTXhdDisChgTxt,
                            HDDISTEMP.FTXtdDisChgType       AS FTXhdDisChgType,
                            HDDISTEMP.FCXtdTotalAfDisChg    AS FCXhdTotalAfDisChg,
                            HDDISTEMP.FCXtdDisChg           AS FCXhdDisChg,
                            HDDISTEMP.FCXtdAmt              AS FCXhdAmt
                        FROM TCNTDocHDDisTmp AS HDDISTEMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND HDDISTEMP.FTBchCode     = '$tBchCode'
                        AND HDDISTEMP.FTXthDocNo    = '$tDocNo'
                        AND HDDISTEMP.FTSessionID   = '$tSessionID'";
        $this->db->query($tSQL);
        return;
    }

    //ข้อมูล DT
    public function FSaMIVMoveDTTmpToDT($paDataWhere, $paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tDocKey      = $paTableAddUpdate['tTableDT'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXphDocNo', $tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = "     INSERT INTO " . $paTableAddUpdate['tTableDT'] . " (
                            FTBchCode, FTXphDocNo, FNXpdSeqNo, FTPdtCode, FTXpdPdtName,
                            FTPunCode, FTPunName,  FCXpdFactor, FTXpdBarCode, FTSrnCode,
                            FTXpdVatType, FTVatCode, FCXpdVatRate, FTXpdSaleType, FCXpdSalePrice,
                            FCXpdQty, FCXpdQtyAll, FCXpdSetPrice, FCXpdAmtB4DisChg, FTXpdDisChgTxt,
                            FCXpdDis, FCXpdChg, FCXpdNet, FCXpdNetAfHD, FCXpdVat, FCXpdVatable,
                            FCXpdWhtAmt, FTXpdWhtCode, FCXpdWhtRate, FCXpdCostIn, FCXpdCostEx,
                            FCXpdQtyLef, FCXpdQtyRfn, FTXpdStaPrcStk, FTXpdStaAlwDis,
                            FNXpdPdtLevel, FTXpdPdtParent, FCXpdQtySet, FTPdtStaSet, FTXpdRmk,
                            FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTBchCode        AS FTBchCode,
                            DOCTMP.FTXthDocNo       AS FTXshDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXsdSeqNo,
                            DOCTMP.FTPdtCode        AS FTPdtCode,
                            DOCTMP.FTXtdPdtName     AS FTXsdPdtName,
                            DOCTMP.FTPunCode        AS FTPunCode,
                            DOCTMP.FTPunName        AS FTPunName,
                            DOCTMP.FCXtdFactor      AS FCXsdFactor,
                            DOCTMP.FTXtdBarCode     AS FTXsdBarCode,
                            DOCTMP.FTSrnCode        AS FTSrnCode,
                            DOCTMP.FTXtdVatType     AS FTXsdVatType,
                            DOCTMP.FTVatCode        AS FTVatCode,
                            DOCTMP.FCXtdVatRate     AS FCXsdVatRate, 
                            DOCTMP.FTXtdSaleType    AS FTXsdSaleType,
                            DOCTMP.FCXtdSalePrice   AS FCXsdSalePrice,
                            DOCTMP.FCXtdQty         AS FCXsdQty,
                            DOCTMP.FCXtdQtyAll      AS FCXsdQtyAll,
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
                            DOCTMP.FCXtdCostIn      AS FCXsdCostIn,
                            DOCTMP.FCXtdCostEx      AS FCXsdCostEx,
                            DOCTMP.FCXtdQtyLef      AS FCXsdQtyLef,
                            DOCTMP.FCXtdQtyRfn      AS FCXsdQtyRfn,
                            DOCTMP.FTXtdStaPrcStk   AS FTXsdStaPrcStk,
                            DOCTMP.FTXtdStaAlwDis   AS FTXsdStaAlwDis,
                            DOCTMP.FNXtdPdtLevel    AS FNXsdPdtLevel,
                            DOCTMP.FTXtdPdtParent   AS FTXsdPdtParent,
                            DOCTMP.FCXtdQtySet      AS FCXsdQtySet,
                            DOCTMP.FTXtdPdtStaSet   AS FTPdtStaSet,
                            DOCTMP.FTXtdRmk         AS FTXsdRmk,
                            DOCTMP.FDLastUpdOn      AS FDLastUpdOn,
                            DOCTMP.FTLastUpdBy      AS FTLastUpdBy,
                            DOCTMP.FDCreateOn       AS FDCreateOn,
                            DOCTMP.FTCreateBy       AS FTCreateBy
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tDocKey'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC";
        $this->db->query($tSQL);

        return;
    }

    //ข้อมูล DTDis
    public function FSaMIVMoveDTDisTempToDTDis($paDataWhere, $paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXphDocNo', $tDocNo);
            $this->db->where_in('FTBchCode', $tBchCode);
            $this->db->delete($paTableAddUpdate['tTableDTDis']);
        }

        $tSQL   =   "   INSERT INTO " . $paTableAddUpdate['tTableDTDis'] . " (
                            FTBchCode , FTXphDocNo , FNXpdSeqNo , FDXpdDateIns ,
                            FNXpdStaDis , FTXpdDisChgTxt , FTXpdDisChgType , FCXpdNet , FCXpdValue ) ";
        $tSQL   .=  "   SELECT
                            DOCDISTMP.FTBchCode         AS FTBchCode,
                            DOCDISTMP.FTXthDocNo        AS FTXshDocNo,
                            DOCDISTMP.FNXtdSeqNo        AS FNXsdSeqNo,
                            DOCDISTMP.FDXtdDateIns      AS FDXddDateIns,
                            DOCDISTMP.FNXtdStaDis       AS FNXddStaDis,
                            DOCDISTMP.FTXtdDisChgTxt    AS FTXddDisChgTxt,
                            DOCDISTMP.FTXtdDisChgType   AS FTXddDisChgType,
                            DOCDISTMP.FCXtdNet          AS FCXddNet,
                            DOCDISTMP.FCXtdValue        AS FCXddValue
                        FROM TCNTDocDTDisTmp DOCDISTMP WITH (NOLOCK)
                        WHERE 1=1
                        AND DOCDISTMP.FTBchCode     = '$tBchCode'
                        AND DOCDISTMP.FTXthDocNo    = '$tDocNo'
                        AND DOCDISTMP.FTSessionID   = '$tSessionID' 
                        ORDER BY DOCDISTMP.FNXtdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    ////////////////////////////////////////////// ลบข้อมูล //////////////////////////////////////////////

    //ลบข้อมูล
    public function FSnMIVDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        //ถ้ามีเอกสารอ้างอิง ต้องอัพเดท DO กลับไปด้วย
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');
        $tSQL = "UPDATE TAPTPoHD SET 
                        FNXphStaRef = '0' , 
                        FDLastUpdOn = '$dLastUpdOn',
                        FTLastUpdBy = '$tLastUpdBy'
                    FROM (
                        SELECT 
                            HD.FTXphRefInt
                        FROM TAPTPiHD HD WITH (NOLOCK) 
                        WHERE HD.FTXphDocNo = '$tDataDocNo'
                    ) AS HD 
                    WHERE FTXphDocNo = HD.FTXphRefInt";
        $this->db->query($tSQL);

        // Document DT
        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiDT');

        // Document DT Discount
        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiDTDis');

        // Document HD 
        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiHD');

        // Document HD SPL
        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiHDSpl');

        // Document HD Discount
        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiHDDis');

        // PI Ref
        $this->db->where_in('FTXshDocNo', $tDataDocNo);
        $this->db->delete('TAPTPiHDDocRef');

        // PO Ref
        $this->db->where_in('FTXshRefDocNo', $tDataDocNo);
        $this->db->delete('TAPTPoHDDocRef');

        // PO Ref
        $this->db->where_in('FTXshRefDocNo', $tDataDocNo);
        $this->db->delete('TAPTDoHDDocRef');

        // ABB Ref
        $this->db->where_in('FTXshRefDocNo', $tDataDocNo);
        $this->db->delete('TPSTSalHDDocRef');

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
        return $aStaDelDoc;
    }

    //ลบข้อมูลใน Temp
    public function FSnMIVDelALLTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTDisTmp');

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocHDRefTmp');

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocHDDisTmp');

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

    ////////////////////////////////////////////// เข้าหน้าแก้ไข //////////////////////////////////////////////

    //ข้อมูล HD
    public function FSaMIVGetDataDocHD($paDataWhere){
        $tIVDocNo   = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            DOCHD.*,
                            DOCHD.FDCreateOn AS DateOn,
                            DOCHD.FTCreateBy AS CreateBy,
                            BCHL.FTBchCode,
                            BCHL.FTBchName,
                            DPTL.FTDptName,
                            USRL.FTUsrName,
                            RTE_L.FTRteName,
                            USRAPV.FTUsrName	AS FTXphApvName,
                            SPL.*,
                            SPL_L.FTSplName,
                            AGN.FTAgnCode       AS rtAgnCode,
                            AGN.FTAgnName       AS rtAgnName,
                            WAH_L.FTWahCode     AS rtWahCode,
                            WAH_L.FTWahName     AS rtWahName,
                            HDDocRef_in.FTXshRefDocNo       AS DocRefIn, 
                            HDDocRef_in.FDXshRefDocDate     AS DateRefIn, 
                            HDDocRef_exBill.FTXshRefDocNo   AS DocRefEx_Bill,
                            HDDocRef_exBill.FDXshRefDocDate AS DateRefEx_Bill
                        FROM TAPTPiHD DOCHD WITH (NOLOCK)
                        INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode    
                        LEFT JOIN TAPTPiHDDocRef    HDDocRef_in     WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = HDDocRef_in.FTXshDocNo        AND HDDocRef_in.FTXshRefType = 1
                        LEFT JOIN TAPTPiHDDocRef    HDDocRef_exBill WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = HDDocRef_exBill.FTXshDocNo    AND HDDocRef_exBill.FTXshRefType = 3 AND HDDocRef_exBill.FTXshRefKey = 'BillNote'
                        LEFT JOIN TCNMBranch_L      BCHL            WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGN             WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = $nLngID
                        LEFT JOIN TCNMUsrDepart_L	DPTL            WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL            WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	        WITH (NOLOCK)   ON DOCHD.FTXphApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMSpl           SPL             WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL.FTSplCode
                        LEFT JOIN TCNMSpl_L         SPL_L           WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL_L.FTSplCode   AND SPL_L.FNLngID	= $nLngID
                        LEFT JOIN TFNMRate_L        RTE_L           WITH (NOLOCK)   ON DOCHD.FTRteCode      = RTE_L.FTRteCode   AND RTE_L.FNLngID	= $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAH_L           WITH (NOLOCK)   ON DOCHD.FTBchCode      = WAH_L.FTBchCode   AND DOCHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                        WHERE 1=1 AND DOCHD.FTXphDocNo = '$tIVDocNo' ";

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

    //ข้อมูล SPL
    public function FSaMIVGetDataDocSPLHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            SPLHD.FTBchCode,
                            SPLHD.FTXphDocNo,
                            SPLHD.FTXphDstPaid,
                            SPLHD.FNXphCrTerm,
                            SPLHD.FDXphDueDate,
                            SPLHD.FDXphBillDue,
                            SPLHD.FTXphCtrName,
                            SPLHD.FDXphTnfDate,
                            SPLHD.FTXphRefTnfID,
                            SPLHD.FTXphRefVehID,
                            SPLHD.FTXphRefInvNo,
                            SPLHD.FTXphQtyAndTypeUnit,
                            SPLHD.FNXphShipAdd,
                            SPLHD.FNXphTaxAdd,
                            SPLHD.FTXphBillDoc,
                            SPLHD.FDXphBillDue,
                            SPL.FTSplStaLocal,
                            SPL_L.FTSplCode,
                            SPL_L.FTSplName,
                            SPL.FTSplTel,
                            SPL.FTSplEmail
                        FROM TAPTPiHDSpl            SPLHD       WITH (NOLOCK)
                        INNER JOIN TAPTPiHD         HD          WITH (NOLOCK)   ON SPLHD.FTXphDocNo     = HD.FTXphDocNo     AND SPLHD.FTBchCode = HD.FTBchCode 
                        LEFT JOIN TCNMSpl           SPL         WITH (NOLOCK)   ON HD.FTSplCode		    = SPL.FTSplCode
                        LEFT JOIN TCNMSpl_L         SPL_L       WITH (NOLOCK)   ON HD.FTSplCode		    = SPL_L.FTSplCode        AND SPL_L.FNLngID	      = $nLngID
                        WHERE 1=1 AND SPLHD.FTXphDocNo = '$tDocNo' ";
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

    //////////////////////////// ไปดึงข้อมูลที่อยู่สำหรับจัดส่ง / ที่อยู่สำหรับออกใบกำกับภาษี /////////////////////////////
    public function FSxMIVGetAddress($paDataWhere){
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            'TYPE_SHIP'                 AS TYPE_ADDR,  
                            ADD_L_SHIP.FNAddSeqNo       AS FNAddSeqNo ,   
                            ADD_L_SHIP.FTAddV1No        AS FTAddV1No ,
                            ADD_L_SHIP.FTAddV1Soi       AS FTAddV1Soi ,
                            ADD_L_SHIP.FTAddV1Village   AS FTAddV1Village ,
                            ADD_L_SHIP.FTAddV1Road      AS FTAddV1Road ,
                            SUBDIS_SHIP.FTSudName       AS FTSudName ,
                            DIS_SHIP.FTDstName          AS FTDstName ,
                            PRO_SHIP.FTPvnName          AS FTPvnName ,
                            ADD_L_SHIP.FTAddV1PostCode  AS FTAddV1PostCode ,
                            ADD_L_SHIP.FTAddTel         AS FTAddTel ,
                            ADD_L_SHIP.FTAddFax         AS FTAddFax ,
                            ADD_L_SHIP.FTAddTaxNo       AS FTAddTaxNo ,
                            ADD_L_SHIP.FTAddV2Desc1     AS FTAddV2Desc1,
                            ADD_L_SHIP.FTAddV2Desc2     AS FTAddV2Desc2,
                            ADD_L_SHIP.FTAddName        AS FTAddName
                        FROM TAPTPiHDSpl             SPLHD          WITH (NOLOCK)
                        LEFT JOIN TCNMAddress_L      ADD_L_SHIP     WITH (NOLOCK)   ON SPLHD.FNXphShipAdd           = ADD_L_SHIP.FNAddSeqNo     AND ADD_L_SHIP.FNLngID     = $nLngID
                        LEFT JOIN TCNMProvince_L     PRO_SHIP       WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1PvnCode    = PRO_SHIP.FTPvnCode        AND PRO_SHIP.FNLngID       = $nLngID
                        LEFT JOIN TCNMDistrict_L     DIS_SHIP       WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1DstCode    = DIS_SHIP.FTDstCode        AND DIS_SHIP.FNLngID       = $nLngID
                        LEFT JOIN TCNMSubDistrict_L  SUBDIS_SHIP    WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1SubDist    = SUBDIS_SHIP.FTSudCode     AND SUBDIS_SHIP.FNLngID    = $nLngID   
                        WHERE 1=1 AND SPLHD.FTXphDocNo = '$tDocNo'
                        
                        UNION 

                        SELECT
                            'TYPE_TAX'                  AS TYPE_ADDR,      
                            ADD_L_TAX.FNAddSeqNo        AS FNAddSeqNo ,   
                            ADD_L_TAX.FTAddV1No         AS FTAddV1No ,
                            ADD_L_TAX.FTAddV1Soi        AS FTAddV1Soi ,
                            ADD_L_TAX.FTAddV1Village    AS FTAddV1Village ,
                            ADD_L_TAX.FTAddV1Road       AS FTAddV1Road ,
                            SUBDIS_TAX.FTSudName        AS FTSudName ,
                            DIS_TAX.FTDstName           AS FTDstName ,
                            PRO_TAX.FTPvnName           AS FTPvnName ,
                            ADD_L_TAX.FTAddV1PostCode   AS FTAddV1PostCode ,
                            ADD_L_TAX.FTAddTel          AS FTAddTel ,
                            ADD_L_TAX.FTAddFax          AS FTAddFax ,
                            ADD_L_TAX.FTAddTaxNo        AS FTAddTaxNo ,
                            ADD_L_TAX.FTAddV2Desc1      AS FTAddV2Desc1,
                            ADD_L_TAX.FTAddV2Desc2      AS FTAddV2Desc2,
                            ADD_L_TAX.FTAddName         AS FTAddName
                        FROM TAPTPiHDSpl             SPLHD          WITH (NOLOCK)
                        LEFT JOIN TCNMAddress_L      ADD_L_TAX      WITH (NOLOCK)   ON SPLHD.FNXphTaxAdd   = ADD_L_TAX.FNAddSeqNo         AND ADD_L_TAX.FNLngID      = $nLngID
                        LEFT JOIN TCNMProvince_L     PRO_TAX        WITH (NOLOCK)   ON ADD_L_TAX.FTAddV1PvnCode   = PRO_TAX.FTPvnCode     AND PRO_TAX.FNLngID        = $nLngID
                        LEFT JOIN TCNMDistrict_L     DIS_TAX        WITH (NOLOCK)   ON ADD_L_TAX.FTAddV1DstCode   = DIS_TAX.FTDstCode     AND DIS_TAX.FNLngID        = $nLngID
                        LEFT JOIN TCNMSubDistrict_L  SUBDIS_TAX     WITH (NOLOCK)   ON ADD_L_TAX.FTAddV1SubDist   = SUBDIS_TAX.FTSudCode  AND SUBDIS_TAX.FNLngID     = $nLngID
                        WHERE 1=1 AND SPLHD.FTXphDocNo = '$tDocNo' ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result_array();
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

    ///////////////////////////////////////// ย้ายข้อมูลจากจริงไป Temp /////////////////////////////////////////

    //ย้ายจาก HDDis To Temp
    public function FSxMIVMoveHDDisToTemp($paDataWhere){
        $tIVDocNo   = $paDataWhere['FTXphDocNo'];
        // Delect Document HD DisTemp By Doc No
        $this->db->where('FTXthDocNo', $tIVDocNo);
        $this->db->delete('TCNTDocHDDisTmp');
        $tSQL       = " INSERT INTO TCNTDocHDDisTmp (
                            FTBchCode,
                            FTXthDocNo,
                            FDXtdDateIns,
                            FTXtdDisChgTxt,
                            FTXtdDisChgType,
                            FCXtdTotalAfDisChg,
                            FCXtdTotalB4DisChg,
                            FCXtdDisChg,
                            FCXtdAmt,
                            FTSessionID,
                            FDLastUpdOn,
                            FDCreateOn,
                            FTLastUpdBy,
                            FTCreateBy
                        )
                        SELECT 
                            HDDis.FTBchCode,
                            HDDis.FTXphDocNo,
                            HDDis.FDXphDateIns,
                            HDDis.FTXphDisChgTxt,
                            HDDis.FTXphDisChgType,
                            HDDis.FCXphTotalAfDisChg,
                            (ISNULL(NULL,0)) AS FCXphTotalB4DisChg,
                            HDDis.FCXphDisChg,
                            HDDis.FCXphAmt,
                            CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "')    AS FTSessionID,
                            CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                            CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                            CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                            CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                        FROM TAPTPiHDDis HDDis WITH (NOLOCK)
                        WHERE 1=1 AND HDDis.FTXphDocNo = '$tIVDocNo' ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DT To Temp
    public function FSxMIVMoveDTToDTTemp($paDataWhere){
        $tIVDocNo       = $paDataWhere['FTXphDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo', $tIVDocNo);
        $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
        $this->db->delete('TCNTDocDTTmp');
        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdSaleType,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
                        FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
                        FTXtdWhtCode,FCXtdWhtRate,FCXtdCostIn,FCXtdCostEx,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,
                        FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                    SELECT
                        DT.FTBchCode,
                        DT.FTXphDocNo,
                        DT.FNXpdSeqNo,
                        CONVERT(VARCHAR,'" . $tDocKey . "') AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXpdFactor,
                        DT.FTXpdBarCode,
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
                        PDT.FTPdtType,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                    FROM TAPTPiDT AS DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON  PDT.FTPdtCode = DT.FTPdtCode
                    WHERE 1=1 AND DT.FTXphDocNo = '$tIVDocNo'
                    ORDER BY DT.FNXpdSeqNo ASC ";
        $this->db->query($tSQL);
        // echo $tSQL;
        return;
    }

    //ย้ายจาก DTDis To Temp
    public function FSxMIVMoveDTDisToDTDisTemp($paDataWhere){
        $tIVDocNo   = $paDataWhere['FTXphDocNo'];
        // Delect Document DTDisTemp By Doc No
        $this->db->where('FTXthDocNo', $tIVDocNo);
        $this->db->delete('TCNTDocDTDisTmp');
        $tSQL   = " INSERT INTO TCNTDocDTDisTmp (
                        FTBchCode,
                        FTXthDocNo,
                        FNXtdSeqNo,
                        FTSessionID,
                        FDXtdDateIns,
                        FNXtdStaDis,
                        FTXtdDisChgType,
                        FCXtdNet,
                        FCXtdValue,
                        FDLastUpdOn,
                        FDCreateOn,
                        FTLastUpdBy,
                        FTCreateBy,
                        FTXtdDisChgTxt
                    )
                    SELECT
                        DTDis.FTBchCode,
                        DTDis.FTXphDocNo,
                        DTDis.FNXpdSeqNo,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "')    AS FTSessionID,
                        DTDis.FDXpdDateIns,
                        DTDis.FNXpdStaDis,
                        DTDis.FTXpdDisChgType,
                        DTDis.FCXpdNet,
                        DTDis.FCXpdValue,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy,
                        DTDis.FTXpdDisChgTxt
                    FROM TAPTPiDTDis DTDis
                    WHERE 1=1 AND DTDis.FTXphDocNo = '$tIVDocNo'
                    ORDER BY DTDis.FNXpdSeqNo ASC";
        $this->db->query($tSQL);
        return;
    }

    //ยกเลิกเอกสาร
    public function FSaMIVUpdateStaDocCancel($paDataUpdate){
        try {
            $this->db->set('FDLastUpdOn', date('Y-m-d H:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FTXphStaDoc', $paDataUpdate['FTXphStaDoc']);
            $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
            $this->db->update('TAPTPiHD');

            //อัพเดทเอกสาร DO ให้กลับมาใช้งานได้อีก
            if ($paDataUpdate['tRefInt'] != '' || $paDataUpdate['tRefInt'] != null) {
                $this->db->where('FTXshDocNo', $paDataUpdate['FTXphDocNo']);
                $this->db->delete('TAPTPiHDDocRef');

                $this->db->where('FTXshRefDocNo', $paDataUpdate['FTXphDocNo']);
                $this->db->delete('TAPTPoHDDocRef');

                $this->db->where('FTXshRefDocNo', $paDataUpdate['FTXphDocNo']);
                $this->db->delete('TAPTDoHDDocRef');
            }

            //ลบ TAPTPiHDDocRef
            $this->db->where('FTXshDocNo', $paDataUpdate['FTXphDocNo']);
            $this->db->delete('TAPTPiHDDocRef');

            $this->db->where('FTXshRefDocNo', $paDataUpdate['FTXphDocNo']);
            $this->db->delete('TPSTSalHDDocRef');

            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Cancel Success.',
            );
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //อัพเดทวันที่กำหนดชำระ
    public function FSaMIVUpdateDocDuelPayDocument($paDataUpdate, $ptDocDuelBill){
        $this->db->set('FDXphDueDate', $ptDocDuelBill);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
        $this->db->update('TAPTPiHDSpl');
    }

    //อนุมัตเอกสาร
    public function FSaMIVApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXphStaApv', $paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode', $paDataUpdate['FTXphUsrApv']);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
        $this->db->update('TAPTPiHD');

        //หลังจากอนุมัติ ต้องอัพเดท FCXphPaid , FCXphLeft
        $tDocNo = $paDataUpdate['FTXphDocNo'];
        $tSQL = "UPDATE HD
                SET 
                    HD.FCXphPaid = '0' ,
                    HD.FCXphLeft = RES.FCXphGrand 
                FROM TAPTPiHD AS HD WITH(NOLOCK)
                INNER JOIN (
                    SELECT 
                        HDRes.FTXphDocNo , 
                        HDRes.FTBchCode ,
                        HDRes.FCXphGrand
                    FROM TAPTPiHD HDRes WITH(NOLOCK)
                    WHERE HDRes.FTXphDocNo = '$tDocNo'
                ) RES 
                ON RES.FTXphDocNo = HD.FTXphDocNo
                AND RES.FTBchCode = HD.FTBchCode ";
        $this->db->query($tSQL);

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

    //อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMIVUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXphRmk', $paDataUpdate['FTXphRmk']);
        $this->db->where('FTBchCode', $paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
        $this->db->update('TAPTPiHD');

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

    ///////////////////////////////////////// อ้างอิงเอกสารภายใน (ref DO) /////////////////////////////////////////

    //อัพเดท เอกสาร DO ว่าห้ามใช้งานอีก
    public function FSxMIVUpdateRef($ptTableName, $paParam){
        $nChkDataDocRef  = $this->FSaMIVChkRefDupicate($ptTableName, $paParam);
        $tTableRef       = $ptTableName;
        if (isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1) { //หากพบว่าซ้ำ
            //ลบ
            $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
            $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
            $this->db->where_in('FTXshDocNo', $paParam['FTXshDocNo']);
            $this->db->where_in('FTXshRefType', $paParam['FTXshRefType']);
            // $this->db->where_in('FTXshRefKey',$paParam['FTXshRefKey']);
            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef, $paParam);
        } else { //หากพบว่าไม่ซ้ำ
            $this->db->insert($tTableRef, $paParam);
        }
        return;
    }

    //เครีย Ref Type2
    public function FSxMIVClearOldRef($paParam){

        $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
        $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
        $this->db->where_in('FTXshRefDocNo', $paParam['FTXshRefDocNo']);
        $this->db->where_in('FTXshRefType', $paParam['FTXshRefType']);
        $this->db->delete('TAPTPoHDDocRef');

        $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
        $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
        $this->db->where_in('FTXshRefDocNo', $paParam['FTXshRefDocNo']);
        $this->db->where_in('FTXshRefType', $paParam['FTXshRefType']);
        $this->db->delete('TAPTDoHDDocRef');

        return;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIVChkRefDupicate($ptTableName, $paParam){
        try {
            $tAgnCode       = $paParam['FTAgnCode'];
            $tBchCode       = $paParam['FTBchCode'];
            $tDocNo         = $paParam['FTXshDocNo'];
            $tRefDocType    = $paParam['FTXshRefType'];
            $tRefDocNo      = $paParam['FTXshDocNo'];
            $tRefKey        = $paParam['FTXshRefKey'];

            $tSQL = "   SELECT 
                            FTAgnCode
                        FROM $ptTableName
                        WHERE 1=1
                        AND FTAgnCode     = '$tAgnCode'
                        AND FTBchCode     = '$tBchCode'
                        AND FTXshRefType  = '$tRefDocType' ";

            if ($tRefDocType == 1 || $tRefDocType == 3) {
                $tSQL .= " AND FTXshDocNo  = '$tDocNo' ";
            } else {
                $tSQL .= " AND FTXshDocNo  = '$tRefDocNo' ";
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

    //หา config ของที่อยู่
    public function FSnMIVGetConfigShwAddress(){
        $tSQL = "   SELECT 
                        CASE WHEN ISNULL(FTSysStaUsrValue,'') = '' THEN FTSysStaDefValue ELSE FTSysStaUsrValue END nStaShwAddr
                    FROM TSysConfig WITH(NOLOCK) 
                    WHERE FTSysCode = 'tCN_AddressType' 
                    AND FTSysApp = 'CN' 
                    AND FTSysKey = 'TCNMComp' 
                ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();
        // print_r($oQuery->result_array());
        // exit;
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->result_array();
            $nResult   = $aDataList[0]['nStaShwAddr'];
        } else {
            $nResult   = 1;
        }
        return $nResult;
    }

    //----------------------------------------------------------------------------------------//

    //เอกสารอ้างอิงใบสั่งซื้อ (PO) [HD]
    public function FSoMIVCallRefIntDocDataTable_PO($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tIVRefIntBchCode        = $aAdvanceSearch['tIVRefIntBchCode'];
        $tIVRefIntDocNo          = $aAdvanceSearch['tIVRefIntDocNo'];
        $tIVRefIntDocDateFrm     = $aAdvanceSearch['tIVRefIntDocDateFrm'];
        $tIVRefIntDocDateTo      = $aAdvanceSearch['tIVRefIntDocDateTo'];
        $tIVRefIntStaDoc         = $aAdvanceSearch['tIVRefIntStaDoc'];
        $tIVSPLCode              = $aAdvanceSearch['tIVSPLCode'];
        $tSQLMain   = " SELECT 
                        HD.FTBchCode, 
                                    CHK.FNStaGenPO,
                                    BCH.FTBchName,
                                    HD.FTXphBchTo,
                                    BCHTO.FTBchName AS BCHNameTo, 
                                    HD.FTXphDocNo, 
                                    HD.FDXphDocDate, 
                                    HD.FTXphStaApv,
                                    HDREF.FTXshRefDocNo
                    FROM TAPTPOHD HD WITH(NOLOCK)
			        LEFT JOIN TAPTPOHDDocRef HDREF WITH(NOLOCK) ON HD.FTXphDocNo = HDREF.FTXshDocNo AND HD.FTBchCode = HDREF.FTBchCode AND HDREF.FTXshRefType = 3
                    /*INNER JOIN (
                        SELECT DISTINCT 
                            PODT.FTBchCode, 
                            PODT.FTXphDocNo
                        FROM TAPTPODT PODT
                        INNER JOIN  TAPTPOHD POHD  WITH(NOLOCK) ON POHD.FTBchCode =  PODT.FTBchCode
                        WHERE FCXPDQTYLEF = 0 */";

        /*if (isset($tIVRefIntBchCode) && !empty($tIVRefIntBchCode)) {
            $tSQLMain .= " AND (POHD.FTBchCode = '$tIVRefIntBchCode' OR POHD.FTXphBchTo = '$tIVRefIntBchCode')";
        }

        if (isset($tIVSPLCode) && !empty($tIVSPLCode)) {
            $tSQLMain .= " AND (POHD.FTSplCode = '$tIVSPLCode')";
        }

        if (isset($tIVRefIntDocNo) && !empty($tIVRefIntDocNo)) {
            $tSQLMain .= " AND (POHD.FTXphDocNo LIKE '%$tIVRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tIVRefIntDocDateFrm) && !empty($tIVRefIntDocDateTo)) {
            $tSQLMain .= " AND ((POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateTo 23:59:59')) OR (POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if (isset($tIVRefIntStaDoc) && !empty($tIVRefIntStaDoc)) {
            if ($tIVRefIntStaDoc == 3) {
                $tSQLMain .= " AND POHD.FTXphStaDoc = '$tIVRefIntStaDoc'";
            } elseif ($tIVRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(POHD.FTXphStaApv,'') = '' AND POHD.FTXphStaDoc != '3'";
            } elseif ($tIVRefIntStaDoc == 1) {
                $tSQLMain .= " AND POHD.FTXphStaApv = '$tIVRefIntStaDoc'";
            }
        }*/

        $tSQLMain .= "  /*) RCD ON HD.FTXphDocNo = RCD.FTXphDocNo AND HD.FTBchCode = RCD.FTBchCode*/
                LEFT JOIN (
                    SELECT MIN
                        ( FNStaGenPO ) AS FNStaGenPO,
                        A.FTXphDocNo
                    FROM (
                        SELECT
                            P.FTXphDocNo,
                            P.FCXpdQty AS FCXpdQtyP,
                            S.FCXpdQty AS FCXsdQtyS,
                            CASE
                                WHEN P.FCXpdQty > S.FCXpdQty THEN
                                1 --สั่งแล้วบางส่วน
                                
                                WHEN P.FCXpdQty <= S.FCXpdQty THEN
                                2 --สั่งครบแล้ว
                            ELSE 0 
                            END FNStaGenPO 
                        FROM (
                            SELECT
                                HDR.FTXshRefDocNo,
                                DT.FTPdtCode,
                                SUM ( DT.FCXpdQty ) AS FCXpdQty 
                            FROM
                                TAPTPiDT DT WITH ( NOLOCK )
                                INNER JOIN TAPTPiHDDocRef HDR WITH ( NOLOCK ) ON DT.FTXphDocNo = HDR.FTXshDocNo 
                            WHERE HDR.FTXshRefType = '1' AND HDR.FTXshRefKey = 'PO' 
                            GROUP BY HDR.FTXshRefDocNo, DT.FTPdtCode 
                        ) S
                        RIGHT JOIN (
                            SELECT
                                FTXphDocNo,
                                FTPdtCode,
                                SUM ( FCXpdQty ) AS FCXpdQty 
                            FROM
                                TAPTPoDT WITH ( NOLOCK ) 
                            GROUP BY FTXphDocNo,FTPdtCode 
                        ) P ON S.FTXshRefDocNo = P.FTXphDocNo 
                        AND S.FTPdtCode = P.FTPdtCode
                    ) A
                    GROUP BY A.FTXphDocNo
                ) AS CHK ON CHK.FTXphDocNo = HD.FTXphDocNo
                LEFT JOIN TCNMBranch_L BCH WITH (NOLOCK) ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = 1
                LEFT JOIN TCNMBranch_L BCHTO WITH (NOLOCK) ON HD.FTXphBchTo = BCHTO.FTBchCode AND BCHTO.FNLngID = 1
                WHERE HD.FTXphStaApv = 1 AND HD.FTXphStaDoc = 1 AND FNStaGenPO != '2' ";

        if (isset($tIVRefIntBchCode) && !empty($tIVRefIntBchCode)) {
            $tSQLMain .= " AND (HD.FTBchCode = '$tIVRefIntBchCode' OR HD.FTXphBchTo = '$tIVRefIntBchCode')";
        }

        if (isset($tIVSPLCode) && !empty($tIVSPLCode)) {
            $tSQLMain .= " AND (HD.FTSplCode = '$tIVSPLCode')";
        }

        if (isset($tIVRefIntDocNo) && !empty($tIVRefIntDocNo)) {
            $tSQLMain .= " AND (HD.FTXphDocNo LIKE '%$tIVRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tIVRefIntDocDateFrm) && !empty($tIVRefIntDocDateTo)) {
            $tSQLMain .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateTo 23:59:59')) OR (POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if (isset($tIVRefIntStaDoc) && !empty($tIVRefIntStaDoc)) {
            if ($tIVRefIntStaDoc == 3) {
                $tSQLMain .= " AND HD.FTXphStaDoc = '$tIVRefIntStaDoc'";
            } elseif ($tIVRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(POHD.FTXphStaApv,'') = '' AND HD.FTXphStaDoc != '3'";
            } elseif ($tIVRefIntStaDoc == 1) {
                $tSQLMain .= " AND HD.FTXphStaApv = '$tIVRefIntStaDoc'";
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow / $paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
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

    //เอกสารอ้างอิงใบรับของ (DO) [HD]
    public function FSoMIVCallRefIntDocDataTable_DO($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tIVRefIntBchCode        = $aAdvanceSearch['tIVRefIntBchCode'];
        $tIVRefIntDocNo          = $aAdvanceSearch['tIVRefIntDocNo'];
        $tIVRefIntDocDateFrm     = $aAdvanceSearch['tIVRefIntDocDateFrm'];
        $tIVRefIntDocDateTo      = $aAdvanceSearch['tIVRefIntDocDateTo'];
        $tIVRefIntStaDoc         = $aAdvanceSearch['tIVRefIntStaDoc'];
        $tIVSPLCode              = $aAdvanceSearch['tIVSPLCode'];
        $tSQLMain = "   SELECT
                            DISTINCT
                                DOHD.FTBchCode,
                                BCHL.FTBchName,
                                DOHD.FTXphDocNo,
                                DOHD.FDXphDocDate AS FDXphDocDate,
                                DOHD.FDXphDocDate AS FTXshDocTime,
                                DOHD.FTXphStaDoc,
                                DOHD.FTXphStaApv,
                                DOHD.FNXphStaRef,
                                DOHD.FTSplCode,
                                DOHD.FTXphVATInOrEx,
                                DOHD.FTCreateBy,
                                DOHD.FDCreateOn,
                                DOHD.FNXphStaDocAct,
                                SPL_L.FTSplName,
                                USRL.FTUsrName      AS FTCreateByName,
                                DOHD.FTXphApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName,
                                DOSPL.FNXphCrTerm,
                                DOSPL.FTXphDstPaid,
                                SPLCR.FCSplCrLimit,
                                BCHL.FTBchName AS BCHNameTo,
                                VAT.FTVatCode,
                                VAT.FCVatRate,
                                DOREF.FTXshRefDocNo
                            FROM TAPTDoHD           DOHD    WITH (NOLOCK)
                            LEFT JOIN TAPTDoHDSpl   DOSPL   WITH (NOLOCK) ON DOHD.FTBchCode     = DOSPL.FTBchCode   AND DOSPL.FTXphDocNo  = DOHD.FTXphDocNo
                            LEFT JOIN TAPTDoHDDocRef DOREF WITH ( NOLOCK ) ON DOHD.FTBchCode     = DOREF.FTBchCode AND DOREF.FTXshDocNo  = DOHD.FTXphDocNo AND DOREF.FTXshRefType = '3'
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON DOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID      = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON DOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID      = $nLngID
                            LEFT JOIN TCNMSpl       SPL     WITH (NOLOCK) ON DOHD.FTSplCode     = SPL.FTSplCode  
                            LEFT JOIN TCNMSplCredit SPLCR   WITH (NOLOCK) ON DOHD.FTSplCode     = SPLCR.FTSplCode  
                            LEFT JOIN TCNMSpl_L     SPL_L   WITH (NOLOCK) ON DOHD.FTSplCode     = SPL_L.FTSplCode   AND SPL_L.FNLngID     = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON DOHD.FTBchCode     = WAH_L.FTBchCode   AND DOHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            INNER JOIN (
                                SELECT A.* FROM(
                                        SELECT  
                                                ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                                FTVatCode , 
                                                FCVatRate 
                                        FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                                ) AS A WHERE A.RowNumber = 1 
                            ) VAT ON SPL.FTVatCode = VAT.FTVatCode
                            LEFT JOIN TAPTDoHDDocRef DO_R   WITH (NOLOCK) ON DOHD.FTXphDocNo    = DO_R.FTXshDocNo  
                            AND DOHD.FTXphStaDoc = 1 
                            AND DOHD.FTXphStaApv = 1
                            AND DO_R.FTXshRefKey = 'IV'
                        WHERE 1=1 
                        AND DOHD.FNXphStaRef != 2 
                        AND ISNULL(DO_R.FTXshRefType, '') = '' 
                        ";

        if (isset($tIVRefIntBchCode) && !empty($tIVRefIntBchCode)) {
            $tSQLMain .= " AND (DOHD.FTBchCode = '$tIVRefIntBchCode')";
        }

        if (isset($tIVSPLCode) && !empty($tIVSPLCode)) {
            $tSQLMain .= " AND (DOHD.FTSplCode = '$tIVSPLCode')";
        }

        if (isset($tIVRefIntDocNo) && !empty($tIVRefIntDocNo)) {
            $tSQLMain .= " AND (DOHD.FTXphDocNo LIKE '%$tIVRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tIVRefIntDocDateFrm) && !empty($tIVRefIntDocDateTo)) {
            $tSQLMain .= " AND ((DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateTo 23:59:59')) OR (DOHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if (isset($tIVRefIntStaDoc) && !empty($tIVRefIntStaDoc)) {
            if ($tIVRefIntStaDoc == 3) {
                $tSQLMain .= " AND DOHD.FTXphStaDoc = '$tIVRefIntStaDoc'";
            } elseif ($tIVRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(DOHD.FTXphStaApv,'') = '' AND DOHD.FTXphStaDoc != '3'";
            } elseif ($tIVRefIntStaDoc == 1) {
                $tSQLMain .= " AND DOHD.FTXphStaApv = '$tIVRefIntStaDoc' AND DOHD.FTXphStaDoc != '3' ";
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                    (  $tSQLMain
                    ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow / $paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
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

    //เอกสารอ้างอิงใบรับของ (ABB) [HD]
    public function FSoMIVCallRefIntDocDataTable_ABB($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tIVRefIntBchCode        = $aAdvanceSearch['tIVRefIntBchCode'];
        $tIVRefIntDocNo          = $aAdvanceSearch['tIVRefIntDocNo'];
        $tIVRefIntDocDateFrm     = $aAdvanceSearch['tIVRefIntDocDateFrm'];
        $tIVRefIntDocDateTo      = $aAdvanceSearch['tIVRefIntDocDateTo'];
        $tIVRefIntStaDoc         = $aAdvanceSearch['tIVRefIntStaDoc'];
        $tIVSPLCode              = $aAdvanceSearch['tIVSPLCode'];

        $tSQLMain = "   SELECT
                            SAHD.FTBchCode,
                            BCHL.FTBchName,
                            SAHD.FTXshDocNo AS FTXphDocNo,
                            CONVERT(CHAR(10),SAHD.FDXshDocDate,121) AS FDXphDocDate,
                            CONVERT(CHAR(5), SAHD.FDXshDocDate,108) AS FTXshDocTime,
                            SAHD.FTXshStaDoc    AS FTXphStaDoc ,
                            SAHD.FTXshStaApv    AS FTXphStaApv,
                            SAHD.FNXshStaRef    AS FNXphStaRef,
                            '0'                 AS FTXphVATInOrEx,
                            '0'                 AS FNXphCrTerm,
                            SAHD.FTCreateBy,
                            SAHD.FDCreateOn,
                            SAHD.FNXshStaDocAct AS FNXphStaDocAct,
                            USRL.FTUsrName      AS FTCreateByName,
                            SAHD.FTXshApvCode   AS FTXphApvCode,
                            WAH_L.FTWahCode,
                            WAH_L.FTWahName,
                            SABCHL.FTBchCode    AS BCHCodeTo ,
                            SABCHL.FTBchName    AS BCHNameTo ,
                            SAAGNL.FTAgnCode    AS AGNCodeTo ,
                            SAAGNL.FTAgnName    AS AGNNameTo 
                        FROM TPSTSalHD          SAHD    WITH (NOLOCK)
                        LEFT JOIN TPSTSalHDCst  SAHDCst WITH (NOLOCK) ON SAHD.FTXshDocNo        = SAHDCst.FTXshDocNo  
                        LEFT JOIN TCNMBranch    SABCH   WITH (NOLOCK) ON SAHDCst.FTXshCstRef    = SABCH.FTBchCode 
                        LEFT JOIN TCNMAgency_L  SAAGNL  WITH (NOLOCK) ON SABCH.FTAgnCode        = SAAGNL.FTAgnCode  AND SAAGNL.FNLngID  = $nLngID
                        LEFT JOIN TCNMBranch_L  SABCHL  WITH (NOLOCK) ON SAHDCst.FTXshCstRef    = SABCHL.FTBchCode  AND SABCHL.FNLngID  = $nLngID

                        LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SAHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID        = $nLngID
                        LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON SAHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID        = $nLngID
                        LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON SAHD.FTBchCode     = WAH_L.FTBchCode   AND SAHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                        LEFT JOIN TARTDoHDDocRef REFUSE WITH (NOLOCK) ON SAHD.FTXshDocNo    = REFUSE.FTXshRefDocNo  AND FTXshRefType = 1
                        WHERE 1 = 1 AND ISNULL(REFUSE.FTXshDocNo,'') = '' AND ISNULL(SAHD.FTCstCode,'') != '' ";
 
        if(isset($tIVRefIntBchCode) && !empty($tIVRefIntBchCode)){
            $tSQLMain .= " AND (SAHDCst.FTXshCstRef = '$tIVRefIntBchCode')";
        }else {
            if ($this->session->userdata("tSesUsrLevel") != 'HQ') {
                $tSesUsrBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
                $tSQLMain .= " AND SAHDCst.FTXshCstRef IN ($tSesUsrBchCodeMulti) ";
            }
        }
 
        if(isset($tIVRefIntDocNo) && !empty($tIVRefIntDocNo)){
            $tSQLMain .= " AND (SAHD.FTXshDocNo LIKE '%$tIVRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tIVRefIntDocDateFrm) && !empty($tIVRefIntDocDateTo)){
            $tSQLMain .= " AND ((SAHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateTo 23:59:59')) OR (SAHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tIVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tIVRefIntStaDoc) && !empty($tIVRefIntStaDoc)){
            if ($tIVRefIntStaDoc == 3) {
                $tSQLMain .= " AND SAHD.FTXshStaDoc = '$tIVRefIntStaDoc'";
            } elseif ($tIVRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(SAHD.FTXshStaApv,'') = '' AND SAHD.FTXshStaDoc != '3'";
            } elseif ($tIVRefIntStaDoc == 1) {
                $tSQLMain .= " AND SAHD.FTXshStaApv = '$tIVRefIntStaDoc'";
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                     SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                     (  $tSQLMain
                     ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
 
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
             $oDataList          = $oQuery->result_array();
             $oQueryMain         = $this->db->query($tSQLMain);
             $aDataCountAllRow   = $oQueryMain->num_rows();
             $nFoundRow          = $aDataCountAllRow;
             $nPageAll           = ceil($nFoundRow / $paDataCondition['nRow']);
             $aResult = array(
                 'raItems'       => $oDataList,
                 'rnAllRow'      => $nFoundRow,
                 'rnCurrentPage' => $paDataCondition['nPage'],
                 'rnAllPage'     => $nPageAll,
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

    //เอกสารอ้างอิงใบสั่งซื้อ (PO) [DT]
    public function FSoMIVCallRefIntDocDTDataTable_PO($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = " SELECT
                        ISNULL(CHKSUM.FCXpdQty,0) AS FCXpdQtySUM,
                        DT.FTBchCode,
                        DT.FTXphDocNo,
                        DT.FNXpdSeqNo,
                        DT.FTPdtCode,
                        DT.FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXpdFactor,
                        DT.FTXpdBarCode,
                        (DT.FCXpdQty - ISNULL(CHKSUM.FCXpdQty,0)) AS FCXpdQty,
                        DT.FCXpdQtyAll,
                        DT.FTXpdRmk,
                        DT.FDLastUpdOn,
                        DT.FTLastUpdBy,
                        DT.FDCreateOn,
                        DT.FTCreateBy
                        FROM TAPTPoDT DT WITH(NOLOCK)
                        LEFT JOIN (
                        SELECT
                            HDR.FTXshRefDocNo,
                            DT.FTPdtCode,
                            SUM ( DT.FCXpdQty ) AS FCXpdQty 
                        FROM
                            TAPTPiDT DT WITH ( NOLOCK )
                            INNER JOIN TAPTPiHDDocRef HDR WITH ( NOLOCK ) ON DT.FTXphDocNo = HDR.FTXshDocNo 
                        WHERE
                            HDR.FTXshRefType = '1' 
                            AND HDR.FTXshRefKey = 'PO' 
                            AND HDR.FTXshRefDocNo = '$tDocNo' --เลขที่อ้างอิง PO (Parameter)
                            
                        GROUP BY
                            HDR.FTXshRefDocNo,
                            DT.FTPdtCode 
                        ) CHKSUM ON DT.FTXphDocNo = CHKSUM.FTXshRefDocNo AND CHKSUM.FTPdtCode = DT.FTPdtCode
                WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tDocNo' AND (ISNULL(CHKSUM.FCXpdQty,0) < DT.FCXpdQty) ";

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

    //เอกสารอ้างอิงใบรับของ (DO) [DT]
    public function FSoMIVCallRefIntDocDTDataTable_DO($paData){
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];
        $tSQL = "SELECT
                DT.FTBchCode,
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
                DT.FTXpdRmk,
                DT.FDLastUpdOn,
                DT.FTLastUpdBy,
                DT.FDCreateOn,
                DT.FTCreateBy
                FROM TAPTDoDT DT WITH(NOLOCK)
                WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tDocNo' ";

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

    //เอกสารอ้างอิงใบรับของ (ABB) [DT]
    public function FSoMIVCallRefIntDocDTDataTable_ABB($paData){
        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tSQL       = " SELECT
                        DT.FTBchCode,
                        DT.FTXshDocNo   AS FTXphDocNo,
                        DT.FNXsdSeqNo   AS FNXpdSeqNo,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName AS FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor  AS FCXpdFactor,
                        DT.FTXsdBarCode AS FTXpdBarCode,
                        DT.FCXsdQty     AS FCXpdQty,
                        DT.FCXsdQtyAll  AS FCXpdQtyAll,
                        DT.FTXsdRmk     AS FTXpdRmk,
                        DT.FDLastUpdOn,
                        DT.FTLastUpdBy,
                        DT.FDCreateOn,
                        DT.FTCreateBy
                        FROM TPSTSalDT DT WITH(NOLOCK)
                    WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo' ";
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

    //นำข้อมูลจาก Browse ลง DTTemp (PO) [DT]
    public function FSoMIVCallRefIntDocInsertDTToTemp_PO($paData){
        $tIVDocNo       = $paData['tIVDocNo'];
        $tIVFrmBchCode  = $paData['tIVFrmBchCode'];
        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';
        $tSQL = "INSERT INTO TCNTDocDTTmp (
                    FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                    FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                    FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                    FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                    FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                    FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,
                    FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
                )
                SELECT
                    '$tIVFrmBchCode' as FTBchCode,
                    '$tIVDocNo' as FTXphDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXpdSeqNo,
                    'TAPTPiDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    '' AS FTSrnCode,
                    PDT.FTPdtStaVatBuy,
                    PDT.FTVatCode AS FTVatCode,
                    VAT.FCVatRate,
                    PDT.FTPdtSaleType AS FTXpdSaleType,
                    PDT.FCPdtCostStd AS FCXpdSalePrice,
                    (DT.FCXpdQty - ISNULL(CHKSUM.FCXpdQty,0)) AS FCXpdQty,
                    DT.FCXpdQtyAll,
                    PDT.FCPdtCostStd AS FCXpdSetPrice,
                    0 AS FCXpdAmtB4DisChg,
                    '' AS FTXpdDisChgTxt,
                    0 as FCXpdQtyLef,
                    0 as FCXpdQtyRfn,
                    '' as FTXpdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXpdPdtLevel,
                    '' as FTXpdPdtParent,
                    0 as FCXpdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXpdRmk,   
                    PDT.FTPdtType,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                FROM
                    TAPTPoDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    LEFT JOIN (
                        SELECT
                            HDR.FTXshRefDocNo,
                            DT.FTPdtCode,
                            SUM ( DT.FCXpdQty ) AS FCXpdQty 
                        FROM
                            TAPTPiDT DT WITH ( NOLOCK )
                            INNER JOIN TAPTPiHDDocRef HDR WITH ( NOLOCK ) ON DT.FTXphDocNo = HDR.FTXshDocNo 
                        WHERE
                            HDR.FTXshRefType = '1' 
                            AND HDR.FTXshRefKey = 'PO' 
                            
                        GROUP BY
                            HDR.FTXshRefDocNo,
                            DT.FTPdtCode 
                        ) CHKSUM ON DT.FTXphDocNo = CHKSUM.FTXshRefDocNo AND CHKSUM.FTPdtCode = DT.FTPdtCode
                    INNER JOIN (
                        SELECT A.* FROM(
                            SELECT  
                                ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                FTVatCode , 
                                FCVatRate 
                            FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                        ) AS A WHERE A.RowNumber = 1 
                    ) VAT ON PDT.FTVatCode = VAT.FTVatCode
                    WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";

        $oQuery = $this->db->query($tSQL);

        if ($this->db->affected_rows() > 0) {
            $aResult = array(
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

    //นำข้อมูลจาก Browse ลง DTTemp (DO) [DT]
    public function FSoMIVCallRefIntDocInsertDTToTemp_DO($paData){
        $tIVDocNo       = $paData['tIVDocNo'];
        $tIVFrmBchCode  = $paData['tIVFrmBchCode'];
        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';
        $tSQL = "INSERT INTO TCNTDocDTTmp (
                    FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                    FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                    FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                    FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                    FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                    FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,
                    FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
                )
                SELECT
                    '$tIVFrmBchCode' as FTBchCode,
                    '$tIVDocNo' as FTXphDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXpdSeqNo,
                    'TAPTPiDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    '' AS FTSrnCode,
                    PDT.FTPdtStaVatBuy,
                    PDT.FTVatCode AS FTVatCode,
                    VAT.FCVatRate,
                    PDT.FTPdtSaleType AS FTXpdSaleType,
                    PDT.FCPdtCostStd AS FCXpdSalePrice,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    PDT.FCPdtCostStd AS FCXpdSetPrice,
                    0 AS FCXpdAmtB4DisChg,
                    '' AS FTXpdDisChgTxt,
                    0 as FCXpdQtyLef,
                    0 as FCXpdQtyRfn,
                    '' as FTXpdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXpdPdtLevel,
                    '' as FTXpdPdtParent,
                    0 as FCXpdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXpdRmk,   
                    PDT.FTPdtType,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                FROM
                    TAPTDoDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    INNER JOIN (
                        SELECT A.* FROM(
                            SELECT  
                                ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                FTVatCode , 
                                FCVatRate 
                            FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                        ) AS A WHERE A.RowNumber = 1 
                    ) VAT ON PDT.FTVatCode = VAT.FTVatCode
                    WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";

        $oQuery = $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aResult = array(
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

    //นำข้อมูลจาก Browse ลง DTTemp (ABB) [DT]
    public function FSoMIVCallRefIntDocInsertDTToTemp_ABB($paData){
        $tIVDocNo       = $paData['tIVDocNo'];
        $tIVFrmBchCode  = $paData['tIVFrmBchCode'];
        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';
        $tSQL   = "INSERT INTO TCNTDocDTTmp (
                    FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                    FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                    FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                    FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                    FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                    FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                    FTXtdPdtStaSet,FTXtdRmk,FTTmpStatus,
                    FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
                )
                SELECT
                    '$tIVFrmBchCode'   AS FTBchCode,
                    '$tIVDocNo'        AS FTXphDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXsdSeqNo DESC ) AS FNXpdSeqNo,
                    'TAPTPiDT'          AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    ''                  AS FTSrnCode,
                    PDT.FTPdtStaVatBuy  AS FTXtdVatType,
                    DT.FTVatCode        AS FTVatCode,
                    DT.FCXsdVatRate     AS FCXtdVatRate,
                    PDT.FTPdtSaleType   AS FTXtdSaleType,
                    DT.FCXsdNetAfHD     AS FCXtdSalePrice,
                    DT.FCXsdQty         AS FCXtdQty,
                    DT.FCXsdQtyAll      AS FCXtdQtyAll,
                    DT.FCXsdNetAfHD     AS FCXtdSetPrice,
                    0                   AS FCXtdAmtB4DisChg,
                    ''                  AS FTXtdDisChgTxt,
                    0                   AS FCXtdQtyLef,
                    0                   AS FCXtdQtyRfn,
                    ''                  AS FTXtdStaPrcStk,
                    PDT.FTPdtStaAlwDis  AS FTXtdStaAlwDis,
                    0                   AS FNXtdPdtLevel,
                    ''                  AS FTXtdPdtParent,
                    0                   AS FCXtdQtySet,
                    ''                  AS FTXtdPdtStaSet,
                    ''                  AS FTXtdRmk,
                    ''                  AS FTTmpStatus,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TPSTSalDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";

        $oQuery = $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aResult = array(
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


    public function FSoMIVUpdateSeqAfterRef($paData){
        $tIVDocNo        = $paData['tIVDocNo'];
        $tIVFrmBchCode   = $paData['tIVFrmBchCode'];
        $tSession        = $this->session->userdata('tSesSessionID');

        //Update Seq
        $tSQL2 = " SELECT
                FNXtdSeqNo,
                FTPdtCode,
                FTPunCode,
                FTXtdBarCode
            FROM
                TCNTDocDTTmp DT WITH (NOLOCK)
                    WHERE  FTBchCode = '$tIVFrmBchCode' 
                    AND FTXthDocNo = '$tIVDocNo' 
                    AND FTSessionID = '$tSession' ";
        $oQuery2 = $this->db->query($tSQL2);
        $aDataList = $oQuery2->result_array();
        $count = 1 ;
        foreach($aDataList as $key => $aval){
            $this->db->set('FNXtdSeqNo', $count);
            $this->db->where('FTPdtCode', $aval['FTPdtCode']);
            $this->db->where('FTPunCode', $aval['FTPunCode']);
            $this->db->where('FTXtdBarCode', $aval['FTXtdBarCode']);
            $this->db->update('TCNTDocDTTmp');
            $count++;
        }
    }
    
    //อัพเดท เอกสาร PO ว่าใช้งานเเล้ว หลังจากอนุมัติ
    public function FSaMIVApproveDocumentDOHD($paDataUpdate){
        $tRefInt    = $paDataUpdate['tRefInt'];
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn', $dLastUpdOn);
        $this->db->set('FTLastUpdBy', $tLastUpdBy);
        $this->db->set('FTXphStaPrcDoc', '1');
        $this->db->where('FTXphDocNo', $tRefInt);
        $this->db->update('TAPTPoHD');
    }

    //----------------------------------------------------------------------------------------//


    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMIVGetDataHDRefTmp($paData){

        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTXshDocKey    = $paData['FTXshDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
                    FROM $tTableTmpHDRef
                    WHERE FTXthDocNo  = '$FTXshDocNo'
                      AND FTXthDocKey = '$FTXshDocKey'
                      AND FTSessionID = '$FTSessionID' 
                      AND FTXthRefType != '3'
                      ";
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
    public function FSaMIVAddEditHDRefTmp($paDataWhere, $paDataAddEdit){
        $tRefDocNo  = (empty($paDataWhere['tIVRefDocNoOld']) ? $paDataAddEdit['FTXshRefDocNo'] : $paDataWhere['tIVRefDocNoOld']);
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
    public function FSaMIVDelHDDocRef($paData){
        $tIVDocNo       = $paData['FTXshDocNo'];
        $tIVRefDocNo    = $paData['FTXshRefDocNo'];
        $tIVDocKey      = $paData['FTXshDocKey'];
        $tIVSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID', $tIVSessionID);
        $this->db->where('FTXthDocKey', $tIVDocKey);
        $this->db->where('FTXthRefDocNo', $tIVRefDocNo);
        $this->db->where('FTXthDocNo', $tIVDocNo);
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
    public function FSxMIVMoveHDRefToHDRefTemp($paData){
        $FTXshDocNo     = $paData['FTXphDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');
        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID', $FTSessionID);
        $this->db->delete('TCNTDocHDRefTmp');
        $tSQL = "   INSERT INTO TCNTDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                        FTXshDocNo,
                        FTXshRefDocNo,
                        FTXshRefType,
                        FTXshRefKey,
                        FDXshRefDocDate,
                        'TAPTPiDT' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn
                    FROM TAPTPiHDDocRef
                    WHERE FTXshDocNo = '$FTXshDocNo' ";
        $this->db->query($tSQL);
    }

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMIVMoveHDRefTmpToHDRef($paDataWhere, $paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        // [PI]
        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where('FTBchCode', $tBchCode);
            $this->db->where('FTXshDocNo', $tDocNo);
            $this->db->delete('TAPTPiHDDocRef');
        }
        $tSQL   =   "   INSERT INTO TAPTPiHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
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
                            AND FTXthDocKey = 'TAPTPiDT'
                            AND FTSessionID = '$tSessionID' ";
        $this->db->query($tSQL);

        if ($paTableAddUpdate['refType'] == 1) { //อ้างอิงเอกสารใบรับของ
            $tSqlBeforeInsert = "SELECT * from TAPTDoHDDocRef where FTXshRefDocNo = '$tDocNo'";
            $oResultA = $this->db->query($tSqlBeforeInsert);
            $oDataList  = $oResultA->result_array();
            foreach ($oDataList as $nkey => $aVal) {
                $this->db->set('FNXphStaRef', '0');
                $this->db->where('FTXphDocNo', $aVal['FTXshDocNo']);
                $this->db->update('TAPTDoHD');
            }

            $this->db->where('FTBchCode', $tBchCode);
            $this->db->where('FTXshRefDocNo', $tDocNo);
            $this->db->delete('TAPTDoHDDocRef');
            $tSQL   =   "   INSERT INTO TAPTDoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
            $tSQL   .=  "   SELECT
                                '$tAgnCode' AS FTAgnCode,
                                '$tBchCode' AS FTBchCode,
                                FTXthRefDocNo AS FTXshDocNo,
                                FTXthDocNo AS FTXshRefDocNo,
                                2,
                                'PI',
                                FDXthRefDocDate
                            FROM TCNTDocHDRefTmp WITH (NOLOCK)
                            WHERE FTXthDocNo  = '$tDocNo'
                                AND FTXthDocKey = 'TAPTPiDT'
                                AND FTSessionID = '$tSessionID'
                                AND FTXthRefKey = 'DO'  ";
            $this->db->query($tSQL);

            $tSqlBeforeInsert = "SELECT * from TAPTDoHDDocRef where FTXshRefDocNo = '$tDocNo'";
            $oResultA = $this->db->query($tSqlBeforeInsert);
            $oDataList  = $oResultA->result_array();
            foreach ($oDataList as $nkey => $aVal) {
                $this->db->set('FNXphStaRef', '2');
                $this->db->where('FTXphDocNo', $aVal['FTXshDocNo']);
                $this->db->update('TAPTDoHD');
            }
        } else { //อ้างอิงเอกสารใบสั่งซื้อ + ใบขาย

            $tSqlBeforeInsert = "SELECT * from TAPTPoHDDocRef where FTXshRefDocNo = '$tDocNo'";
            $oResultA = $this->db->query($tSqlBeforeInsert);
            $oDataList  = $oResultA->result_array();
            foreach ($oDataList as $nkey => $aVal) {
                $this->db->set('FNXphStaRef', '0');
                $this->db->where('FTXphDocNo', $aVal['FTXshDocNo']);
                $this->db->update('TAPTPoHD');
            }

            $this->db->where('FTBchCode', $tBchCode);
            $this->db->where('FTXshRefDocNo', $tDocNo);
            $this->db->delete('TAPTPoHDDocRef');
            $tSQL   =   "   INSERT INTO TAPTPoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
            $tSQL   .=  "   SELECT
                                '$tAgnCode' AS FTAgnCode,
                                '$tBchCode' AS FTBchCode,
                                FTXthRefDocNo AS FTXshDocNo,
                                FTXthDocNo AS FTXshRefDocNo,
                                2,
                                'PI',
                                FDXthRefDocDate
                            FROM TCNTDocHDRefTmp WITH (NOLOCK)
                            WHERE FTXthDocNo  = '$tDocNo'
                                AND FTXthDocKey = 'TAPTPiDT'
                                AND FTSessionID = '$tSessionID'
                                AND FTXthRefKey = 'PO'  ";
            $this->db->query($tSQL);

            $tSqlBeforeInsert = "SELECT * from TAPTPoHDDocRef where FTXshRefDocNo = '$tDocNo'";
            $oResultA = $this->db->query($tSqlBeforeInsert);
            $oDataList  = $oResultA->result_array();
            foreach ($oDataList as $nkey => $aVal) {
                $this->db->set('FNXphStaRef', '2');
                $this->db->where('FTXphDocNo', $aVal['FTXshDocNo']);
                $this->db->update('TAPTPoHD');
            }

            //Insert ใบขาย
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FTXshRefDocNo',$tDocNo);
            $this->db->delete('TPSTSalHDDocRef');
            $tSQL   =   "   INSERT INTO TPSTSalHDDocRef ( FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
            $tSQL   .=  "   SELECT
                                '$tBchCode' AS FTBchCode,
                                FTXthRefDocNo AS FTXshDocNo,
                                FTXthDocNo AS FTXshRefDocNo,
                                2,
                                'PI',
                                FDXthRefDocDate
                            FROM TCNTDocHDRefTmp WITH (NOLOCK)
                            WHERE FTXthDocNo  = '$tDocNo'
                            AND FTXthDocKey = 'TAPTPiDT'
                            AND FTSessionID = '$tSessionID'
                            AND FTXthRefKey = 'ABB'  ";
            $this->db->query($tSQL);
        }
    }








    
}
