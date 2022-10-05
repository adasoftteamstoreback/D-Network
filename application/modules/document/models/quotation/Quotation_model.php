<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Quotation_model extends CI_Model {

    //Datatable
    public function FSaMQTList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];

        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTXshDocNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                BCHL.FTBchName,
                                QT.FTBchCode,
                                QT.FTXshDocNo,
                                CONVERT(CHAR(10),QT.FDXshDocDate,103) AS FDXshDocDate,
                                QT.FTXshStaDoc,
                                QT.FTXshStaApv,
                                QT.FTCreateBy,
                                QT.FDCreateOn,
                                QT.FTXshApvCode,
                                USRL.FTUsrName AS FTCreateByName,
                                USRLAPV.FTUsrName AS FTXshApvName
                            FROM [TARTSqHD] QT WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON QT.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                            LEFT JOIN TCNMUser_L    USRL WITH (NOLOCK) ON QT.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                            LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON QT.FTXshApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                            WHERE 1=1 ";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND ((QT.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),QT.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND  QT.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL .= " AND ((QT.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (QT.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if(!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")){
            if($tSearchStaDoc == 1){ //อนุมัติแล้ว
                $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc' AND ISNULL(QT.FTXshStaApv,'') <> '' ";
            }else if($tSearchStaDoc == 2){ //รออนุมัติ
                $tSQL .= " AND QT.FTXshStaDoc = '1' AND ISNULL(QT.FTXshStaApv,'') = '' ";
            }else if($tSearchStaDoc == 3){ //ยกเลิก
                $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc'";
            }else{
                $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc'";
            }
        }

        /*สถานะเคลื่อนไหว*/
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if(!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")){
            if($tSearchStaDocAct == 3){
                $tSQL .= " AND QT.FNXshStaDocAct = '$tSearchStaDocAct' OR QT.FNXshStaDocAct = '' ";
            }else{
                $tSQL .= " AND QT.FNXshStaDocAct = '$tSearchStaDocAct'";
            }
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMQTGetPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']);
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
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
    public function FSnMQTGetPageAll($paData){
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "SELECT COUNT (QT.FTXshDocNo) AS counts
                        FROM [TARTSqHD] QT WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON QT.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1 ";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND ((QT.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (QT.FDXshDocDate LIKE '%$tSearchList%'))";
        }

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL .= " AND  QT.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL .= " AND ((QT.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (QT.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

       /*สถานะเอกสาร*/
       $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
       if(!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")){
           if($tSearchStaDoc == 1){ //อนุมัติแล้ว
               $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc' AND ISNULL(QT.FTXshStaApv,'') <> '' ";
           }else if($tSearchStaDoc == 2){ //รออนุมัติ
               $tSQL .= " AND QT.FTXshStaDoc = '1' AND ISNULL(QT.FTXshStaApv,'') = '' ";
           }else if($tSearchStaDoc == 3){ //ยกเลิก
               $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc'";
           }else{
               $tSQL .= " AND QT.FTXshStaDoc = '$tSearchStaDoc'";
           }
       }

       /*สถานะเคลื่อนไหว*/
       $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
       if(!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")){
           if($tSearchStaDocAct == 3){
               $tSQL .= " AND QT.FNXshStaDocAct = '$tSearchStaDocAct' OR QT.FNXshStaDocAct = '' ";
           }else{
               $tSQL .= " AND QT.FNXshStaDocAct = '$tSearchStaDocAct'";
           }
       }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //สินค้าใน DT
    public function FSaMQTGetDocDTTempListPage($paDataWhere){
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
        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
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
    public function FSaMQTGetDataPdt($paDataPdtParams){
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
                            SPL.FCSplLastPrice ,
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
                        /*LEFT JOIN (
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
                        ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode*/
                        LEFT JOIN VCN_Price4PdtActive PRI4PDT WITH(NOLOCK) ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PKS.FTPunCode = PRI4PDT.FTPunCode
                        WHERE 1 = 1 ";

        if(isset($tPdtCode) && !empty($tPdtCode)){
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }

        if(isset($FTBarCode) && !empty($FTBarCode)){
            $tSQL   .= " AND BAR.FTBarCode = '$FTBarCode'";
        }

        $tSQL   .= " ORDER BY FDVatStart DESC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $aResult    = array(
                'raItem'    => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
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
    public function FSaMQTInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paItemDataPdt    = $paDataPdtMaster['raItem'];
        if($paDataPdtParams['tTQOptionAddPdt'] == 1){
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "   SELECT
                                FNXtdSeqNo,
                                FCXtdQty
                            FROM TCNTDocDTTmp
                            WHERE 1=1
                            AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                            AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                            AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                            AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                            AND FTPdtCode       = '".$paItemDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paItemDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "   UPDATE TCNTDocDTTmp
                                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                                    WHERE 1=1
                                    AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                                    AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                                    AND FNXtdSeqNo      = '".$aResult["FNXtdSeqNo"]."'
                                    AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                                    AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                                    AND FTPdtCode       = '".$paItemDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paItemDataPdt["FTBarCode"]."' ";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
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
                    'FCXtdSalePrice'    => $paDataPdtParams['FCXtdSetPrice'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$paItemDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paDataPdtParams['FCXtdSetPrice'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                );

                $this->db->insert('TCNTDocDTTmp',$aDataInsert);
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode'    => '1',
                        'rtDesc'    => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode'    => '905',
                        'rtDesc'    => 'Error Cannot Add.',
                    );
                }
            }
        }else{
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
                'FCXtdSalePrice'    => $paItemDataPdt['FTPdtSalePrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paItemDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paItemDataPdt['FCPgdPriceRet'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            );
            $this->db->insert('TCNTDocDTTmp',$aDataInsert);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        }
        return $aStatus;
    }

    //ลบข้อมูลใน Temp [รายการเดียว]
    public function FSnMQTDelDTTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTXthDocNo', $paData['FTXshDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXsdSeqNo']);
            $this->db->where_in('FTPdtCode',  $paData['FTPdtCode']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->where_in('FTXthDocKey', $paData['FTXthDocKey']);
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

    //ลบข้อมูลใน Temp [หลายรายการ]
    public function FSaMQTPdtTmpMultiDel($paData){
        try{
            $this->db->trans_begin();

            //Del DTTmp
            $this->db->where('FTBchCode', $paData['FTBchCode']);
            $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            //Del DTDisTmp
            // $this->db->where('FTBchCode', $paData['FTBchCode']);
            // $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            // $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            // $this->db->where('FTSessionID', $paData['FTSessionID']);
            // $this->db->delete('TCNTDocDTDisTmp');

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

    //เช็คว่ามีสินค้าใน DocDT Temp ไหม
    public function FSnMQTChkPdtInDocDTTemp($paDataWhere){
        $tQTDocNo       = $paDataWhere['FTXshDocNo'];
        $tQTDocKey      = $paDataWhere['FTXthDocKey'];
        $tQTSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocNo    = '$tQTDocNo'
                            AND DocDT.FTXthDocKey   = '$tQTDocKey'
                            AND DocDT.FTSessionID   = '$tQTSessionID' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    //หาว่า VAT ตัวสุดท้าย
    public function FSaMQTCalVatLastDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSessionID     = $paData['tSessionID'];
        $tDataVatInOrEx = $paData['tDataVatInOrEx'];
        $tDocKey        = $paData['tDocKey'];

        $cSumFCXtdVat = " SELECT
            SUM (ISNULL(DOCTMP.FCXtdVat, 0)) AS FCXtdVat
            FROM
                TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE
                1 = 1
            AND DOCTMP.FTSessionID = '$tSessionID'
            AND DOCTMP.FTXthDocKey = '$tDocKey'
            AND DOCTMP.FTXthDocNo = '$tDocNo'
            AND DOCTMP.FCXtdVatRate > 0  ";

        $tSql ="UPDATE TCNTDocDTTmp
                SET FCXtdVat = (
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
                WHERE
                    FTSessionID = '$tSessionID'
                AND FTXthDocNo = '$tDocNo'
                AND FNXtdSeqNo = (
                    SELECT
                        TOP 1 FNXtdSeqNo
                    FROM
                        TCNTDocDTTmp WHDTTMP
                    WHERE
                        WHDTTMP.FTSessionID = '$tSessionID'
                    AND WHDTTMP.FTXthDocNo = '$tDocNo'
                    AND WHDTTMP.FTXtdVatType = 1
                    ORDER BY
                        WHDTTMP.FNXtdSeqNo DESC
                )";

        $nRSCounDT =  $this->db->where('FTSessionID',$tSessionID)->where('FTXthDocNo',$tDocNo)->where('FTXtdVatType','1')->get('TCNTDocDTTmp')->num_rows();

        if($nRSCounDT>1){
            $this->db->query($tSql);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'cannot Delete Item.',
                );
            }
        }else{
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }
        return $aStatus;
    }

    //คำนวณ VAT
    public function FSaMQTCalInDTTemp($paParams){
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
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->result_array();
        }else{
            $aResult    = [];
        }
        return $aResult;
    }

    //คำนวณ HD Dis
    public function FSaMQTCalInHDDisTemp($paParams){
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
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
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = [];
        }
        return $aResult;
    }

    //เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม where session
    public function FSaMQTDeletePDTInTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocHDDisTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTDisTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocHDRefTmp');

        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        return $aStatus;
    }

    //อัพเดทส่วนลด
    public function FSaMQTUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tQTDocNo       = $paDataWhere['tQTDocNo'];
        $tQTBchCode     = $paDataWhere['tQTBchCode'];
        $nQTSeqNo       = $paDataWhere['nQTSeqNo'];
        $tDocKey        = $paDataWhere['tDocKey'];

        $tSQL ="SELECT
                    PKS.FCPdtUnitFact
                    FROM
                TCNTDocDTTmp DTTEMP
                LEFT OUTER JOIN TCNMPdtPackSize PKS WITH (NOLOCK) ON DTTEMP.FTPdtCode = PKS.FTPdtCode AND DTTEMP.FTPunCode = PKS.FTPunCode
                WHERE
                    FTSessionID = '$tSessionID'
                    AND FTBchCode = '$tQTBchCode'
                    AND FTXthDocNo = '$tQTDocNo'
                    AND FNXtdSeqNo = $nQTSeqNo ";
        $cPdtUnitFact = $this->db->query($tSQL)->row_array()['FCPdtUnitFact'];

        if($cPdtUnitFact>0){
            $cPdtUnitFact = $cPdtUnitFact;
        }else{
            $cPdtUnitFact = 1;
        }

        $this->db->set('FCXtdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCXtdSetPrice', $paDataUpdateDT['FCXtdSetPrice']);
        $this->db->set('FCXtdNet', $paDataUpdateDT['FCXtdNet']);
        $this->db->set('FCXtdQtyAll', $paDataUpdateDT['FCXtdQty']*$cPdtUnitFact);
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FNXtdSeqNo',$nQTSeqNo);
        $this->db->where('FTXthDocNo',$tQTDocNo);
        $this->db->where('FTBchCode',$tQTBchCode);
        $this->db->update('TCNTDocDTTmp');
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Update Success',
            );
        }else{
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Update Fail',
            );
        }
        return $aStatus;
    }

    //ล้างค่าส่วนลดรายการ อัพเดทส่วนลดรายการ
    public function FSaMQTDeleteDTDisTemp($paParams){
        $tQTDocNo       = $paParams['tQTDocNo'];
        $nQTSeqNo       = $paParams['nQTSeqNo'];
        $tQTBchCode     = $paParams['tQTBchCode'];
        $nStaDelDis     = $paParams['nStaDelDis'];
        $tSessionID     = $paParams['tSessionID'];
        $this->db->where_in('FTSessionID',$tSessionID);
        if(isset($nQTSeqNo) && !empty($nQTSeqNo)){
            $this->db->where_in('FNXtdSeqNo',$nQTSeqNo);
        }
        $this->db->where_in('FTBchCode',$tQTBchCode);
        $this->db->where_in('FTXthDocNo',$tQTDocNo);
        if(isset($nStaDelDis) && !empty($nStaDelDis)){
            $this->db->where_in('FNXtdStaDis',$nStaDelDis);
        }
        $this->db->delete('TCNTDocDTDisTmp');
        return;
    }

    //ล้างค่าส่วนใน ตาราง DT
    public function FSaMQTClearDisChgTxtDTTemp($paParams){
        $tQTDocNo       = $paParams['tQTDocNo'];
        $nQTSeqNo       = $paParams['nQTSeqNo'];
        $tQTBchCode     = $paParams['tQTBchCode'];
        $tSessionID     = $paParams['tSessionID'];

        //อัพเดทให้เป็นค่าว่าง ใน Temp
        $this->db->set('FTXtdDisChgTxt', '');
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$nQTSeqNo);
        $this->db->where_in('FTBchCode',$tQTBchCode);
        $this->db->where_in('FTXthDocNo',$tQTDocNo);
        $this->db->update('TCNTDocDTTmp');
        return;
    }

    ////////////////////////////////////////////// บันทึกข้อมูล //////////////////////////////////////////////

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMQTAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMQTGetDataDocHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['FDCreateOn'],
                'FTCreateBy'    => $aDataHDOld['FTCreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        // Delete HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert HD
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        return;
    }

    //ข้อมูล CST ลบและ เพิ่มใหม่
    public function FSxMQTAddUpdateCSTHD($paDataCstHD,$paDataWhere,$paTableAddUpdate){

        $aDataGetDataCstHD    =   $this->FSaMQTGetDataDocCstHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateCstHD    = array();
        if(isset($aDataGetDataCstHD['rtCode']) && $aDataGetDataCstHD['rtCode'] == 1){
            $aDataCSTHDOld          = $aDataGetDataCstHD['raItems'];
            $aDataAddUpdateCstHD    = array_merge($paDataCstHD,array(
                'FTCarCode'     => ''
            ));
        }else{
            $aDataAddUpdateCstHD    = array_merge($paDataCstHD,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FTCarCode'     => ''
            ));
        }

        //หาว่าลูกค้าคนนี้ทะเบียนรถอะไร
        $aGetCarCode    =   $this->FSaMQTGetCarCode(array(
            'FTCstCode'    => $paDataCstHD['FTCstCode']
        ));
        if(isset($aGetCarCode['rtCode']) && $aGetCarCode['rtCode'] == 1){
            $aDataAddUpdateCstHD['FTCarCode'] = $aGetCarCode['raItems']['FTCarCode'];
        }else{
            $aDataAddUpdateCstHD['FTCarCode'] = null;
        }

        // Delete CstHD
        $this->db->where_in('FTBchCode',$aDataAddUpdateCstHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateCstHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDCst']);

        // Insert CstHD
        $this->db->insert($paTableAddUpdate['tTableHDCst'],$aDataAddUpdateCstHD);
        return;
    }

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMQTMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TARTSqHDDocRef');
        }

        $tSQL   =   "   INSERT INTO TARTSqHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthDocNo,
                            FTXthRefDocNo,
                            FTXthRefType,
                            FTXthRefKey,
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID' ";
        $this->db->query($tSQL);

        //Insert ตารางใบสั่งงาน
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TSVTJob1ReqHDDocRef');
        $tSQL   =   "   INSERT INTO TSVTJob1ReqHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'QT',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'Job1Req'  ";
        $this->db->query($tSQL);

        //Insert ตารางใบรับรถ
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TSVTJob2OrdHDDocRef');
        $tSQL   =   "   INSERT INTO TSVTJob2OrdHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'QT',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'Job2Ord' ";
        $this->db->query($tSQL);
    }

    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp , TCNTDocHDRefTmp
    public function FSxMQTAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into HDDisTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDDisTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into DTDisTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocDTDisTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

         // Update DocNo Into TCNTDocHDRefTmp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTXthDocKey','TARTSqHD');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDRefTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo']
        ));
        return;
    }

    //ข้อมูล HDDis
    public function FSaMQTMoveHDDisTempToHDDis($paDataWhere,$paTableAddUpdate){
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where_in('FTXshDocNo',$tDocNo);
            $this->db->where_in('FTBchCode',$tBchCode);
            $this->db->delete($paTableAddUpdate['tTableHDDis']);
        }

        $tSQL   =   "   INSERT INTO ".$paTableAddUpdate['tTableHDDis']." (
                            FTBchCode,
                            FTXshDocNo,
                            FDXhdDateIns,
                            FTXhdDisChgTxt,
                            FTXhdDisChgType,
                            FCXhdTotalAfDisChg,
                            FCXhdDisChg,
                            FCXhdAmt
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
    public function FSaMQTMoveDTTmpToDT($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tDocKey      = $paTableAddUpdate['tTableDT'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where_in('FTXshDocNo',$tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = "     INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                            FTBchCode, FTXshDocNo, FNXsdSeqNo, FTPdtCode, FTXsdPdtName,
                            FTPunCode, FTPunName,  FCXsdFactor, FTXsdBarCode, FTSrnCode,
                            FTXsdVatType, FTVatCode, FCXsdVatRate, FTXsdSaleType, FCXsdSalePrice,
                            FCXsdQty, FCXsdQtyAll, FCXsdSetPrice, FCXsdAmtB4DisChg, FTXsdDisChgTxt,
                            FCXsdDis, FCXsdChg, FCXsdNet, FCXsdNetAfHD, FCXsdVat, FCXsdVatable,
                            FCXsdWhtAmt, FTXsdWhtCode, FCXsdWhtRate, FCXsdCostIn, FCXsdCostEx,
                            FTXsdStaPdt, FCXsdQtyLef, FCXsdQtyRfn, FTXsdStaPrcStk, FTXsdStaAlwDis,
                            FNXsdPdtLevel, FTXsdPdtParent, FCXsdQtySet, FTPdtStaSet, FTXsdRmk,
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
                            1                       AS FTXsdStaPdt,
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
    public function FSaMQTMoveDTDisTempToDTDis($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where_in('FTXshDocNo',$tDocNo);
            $this->db->where_in('FTBchCode',$tBchCode);
            $this->db->delete($paTableAddUpdate['tTableDTDis']);
        }

        $tSQL   =   "   INSERT INTO ".$paTableAddUpdate['tTableDTDis']." (
                            FTBchCode , FTXshDocNo , FNXsdSeqNo , FDXddDateIns ,
                            FNXddStaDis , FTXddDisChgTxt , FTXddDisChgType , FCXddNet , FCXddValue ) ";
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
    public function FSnMQTDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        // Document DT
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqDT');

        // Document DT Discount
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqDTDis');

        // Document HD
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqHD');

        // Document HD Cst
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqHDCst');

        // Document HD Discount
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqHDDis');

        // Document HD DocRef
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSqHDDocRef');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aStaDelDoc     = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        }else{
            $this->db->trans_commit();
            $aStaDelDoc     = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        return $aStaDelDoc;
    }

    //ลบข้อมูลใน Temp
    public function FSnMQTDelALLTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTDisTmp');

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

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
    public function FSaMQTGetDataDocHD($paDataWhere){
        $tQTDocNo   = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            DOCHD.*,
                            BCHL.FTBchCode,
                            BCHL.FTBchName,
                            DPTL.FTDptName,
                            USRL.FTUsrName,
                            RTE_L.FTRteName,
                            USRAPV.FTUsrName	AS FTXshApvName
                        FROM TARTSqHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXshApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMCst           CST     WITH (NOLOCK)   ON DOCHD.FTCstCode		= CST.FTCstCode
                        LEFT JOIN TCNMCst_L         CST_L   WITH (NOLOCK)   ON DOCHD.FTCstCode		= CST_L.FTCstCode   AND CST_L.FNLngID	= $nLngID
                        LEFT JOIN TFNMRate_L        RTE_L    WITH (NOLOCK)  ON DOCHD.FTRteCode      = RTE_L.FTRteCode   AND RTE_L.FNLngID	= $nLngID
                        WHERE 1=1 AND DOCHD.FTXshDocNo = '$tQTDocNo' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
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
    }

    //ข้อมูล CST
    public function FSaMQTGetDataDocCstHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');
        $tSQL       = " SELECT
                            CSTHD.FTBchCode     AS FTBchCode,
                            CSTHD.FTXshDocNo    AS FTXshDocNo,
                            CST.FTCstCardID     AS FTXshCardID,
                            CSTHD.FNXshCrTerm   AS FNXshCrTerm,
                            CSTHD.FDXshDueDate  AS FDXshDueDate,
                            CSTHD.FDXshBillDue  AS FDXshBillDue,
                            CSTHD.FTXshCtrName  AS FTXshCtrName,
                            CSTHD.FDXshTnfDate  AS FDXshTnfDate,
                            CSTHD.FTXshRefTnfID AS FTXshRefTnfID,
                            CSTHD.FNXshAddrShip AS FNXshAddrShip,
                            CSTHD.FTXshAddrTax  AS FTXshAddrTax,
                            CSTHD.FTCarCode     AS FTCarCode,
                            CST_L.FTCstName     AS FTCstName,
                            CST_L.FTCstCode     AS FTCstCode,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            CSTLEV.FTPplCode,
                            ADDL.FTAddV2Desc1,
                            CONCAT(ADDL.FTAddV1No,' ', ADDL.FTAddV1Soi,' ', ADDL.FTAddV1Village,' ', ADDL.FTAddV1Road,' ',
                             SUBDL.FTSudName,' ', DISL.FTDstName,' ', PRO.FTPvnName,' ', ADDL.FTAddV2Desc2) AS FTAddV1Desc
                        FROM TARTSqHDCst    CSTHD       WITH (NOLOCK)
                        LEFT JOIN TCNMCst	CST         WITH (NOLOCK)   ON CST.FTCstCode = CSTHD.FTCstCode
                        LEFT JOIN TCNMCst_L	CST_L       WITH (NOLOCK)   ON CST.FTCstCode = CST_L.FTCstCode AND CST_L.FNLngID	= $nLngID
                        LEFT JOIN TCNMCstAddress_L      ADDL    WITH (NOLOCK)   ON CST.FTCstCode = ADDL.FTCstCode AND ADDL.FTAddVersion = '$nAddressVersion'
                        LEFT JOIN TCNMProvince_L        PRO     WITH (NOLOCK)   ON ADDL.FTAddV1PvnCode = PRO.FTPvnCode AND PRO.FNLngID = $nLngID
                        LEFT JOIN TCNMDistrict_L        DISL    WITH (NOLOCK)   ON ADDL.FTAddV1DstCode = DISL.FTDstCode AND DISL.FNLngID = $nLngID
                        LEFT JOIN TCNMSubDistrict_L     SUBDL   WITH (NOLOCK)   ON ADDL.FTAddV1SubDist = SUBDL.FTSudCode AND SUBDL.FNLngID = $nLngID
                        LEFT JOIN TCNMCstLev            CSTLEV  WITH (NOLOCK)   ON CST.FTClvCode = CSTLEV.FTClvCode
                        WHERE 1=1 AND CSTHD.FTXshDocNo = '$tDocNo' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
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
    }

    //ข้อมูลรถของลูกค้า
    public function FSaMQTGetCarCode($paDataWhere){
        $tCstCode   = $paDataWhere['FTCstCode'];
        $tSQL       = " SELECT
                            CAR.FTCarCode
                        FROM TSVMCar CAR WITH (NOLOCK)
                        WHERE CAR.FTCarOwner = '$tCstCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
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

    }

    //ข้อมูล HDDocRef
    public function FSxMQTMoveHDRefToHDRefTemp($paData){

        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey','TARTSqHD');
        $this->db->where('FTSessionID',$FTSessionID);
        $this->db->delete('TCNTDocHDRefTmp');

        $tSQL = "   INSERT INTO TCNTDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                        FTXshDocNo,
                        FTXshRefDocNo,
                        FTXshRefType,
                        FTXshRefKey,
                        FDXshRefDocDate,
                        'TARTSqHD' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                    FROM TARTSqHDDocRef
                    WHERE FTXshDocNo = '$FTXshDocNo' ";
        $this->db->query($tSQL);
    }

    ///////////////////////////////////////// ย้ายข้อมูลจากจริงไป Temp /////////////////////////////////////////

    //ย้ายจาก HDDis To Temp
    public function FSxMQTMoveHDDisToTemp($paDataWhere){
        $tQTDocNo       = $paDataWhere['FTXshDocNo'];

        // Delect Document HD DisTemp By Doc No
        $this->db->where('FTXthDocNo',$tQTDocNo);
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
                            HDDis.FTXshDocNo,
                            HDDis.FDXhdDateIns,
                            HDDis.FTXhdDisChgTxt,
                            HDDis.FTXhdDisChgType,
                            HDDis.FCXhdTotalAfDisChg,
                            (ISNULL(NULL,0)) AS FCXtdTotalB4DisChg,
                            HDDis.FCXhdDisChg,
                            HDDis.FCXhdAmt,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')    AS FTSessionID,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                        FROM TARTSqHDDis HDDis WITH (NOLOCK)
                        WHERE 1=1 AND HDDis.FTXshDocNo = '$tQTDocNo' ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DT To Temp
    public function FSxMQTMoveDTToDTTemp($paDataWhere){
        $tQTDocNo       = $paDataWhere['FTXshDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tQTDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdSaleType,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
                        FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
                        FTXtdWhtCode,FCXtdWhtRate,FCXtdCostIn,FCXtdCostEx,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,
                        FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTXtdRmk,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                    SELECT
                        DT.FTBchCode,
                        DT.FTXshDocNo,
                        DT.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        DT.FTXsdVatType,
                        DT.FTVatCode,
                        DT.FCXsdVatRate,
                        DT.FTXsdSaleType,
                        DT.FCXsdSalePrice,
                        DT.FCXsdQty,
                        DT.FCXsdQtyAll,
                        DT.FCXsdSetPrice,
                        DT.FCXsdAmtB4DisChg,
                        DT.FTXsdDisChgTxt,
                        DT.FCXsdDis,
                        DT.FCXsdChg,
                        DT.FCXsdNet,
                        DT.FCXsdNetAfHD,
                        DT.FCXsdVat,
                        DT.FCXsdVatable,
                        DT.FCXsdWhtAmt,
                        DT.FTXsdWhtCode,
                        DT.FCXsdWhtRate,
                        DT.FCXsdCostIn,
                        DT.FCXsdCostEx,
                        DT.FCXsdQtyLef,
                        DT.FCXsdQtyRfn,
                        DT.FTXsdStaPrcStk,
                        DT.FTXsdStaAlwDis,
                        DT.FNXsdPdtLevel,
                        DT.FTXsdPdtParent,
                        DT.FCXsdQtySet,
                        DT.FTPdtStaSet,
                        DT.FTXsdRmk,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TARTSqDT AS DT WITH (NOLOCK)
                    WHERE 1=1 AND DT.FTXshDocNo = '$tQTDocNo'
                    ORDER BY DT.FNXsdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    //ย้ายจาก DTDis To Temp
    public function FSxMQTMoveDTDisToDTDisTemp($paDataWhere){
        $tQTDocNo       = $paDataWhere['FTXshDocNo'];

        // Delect Document DTDisTemp By Doc No
        $this->db->where('FTXthDocNo',$tQTDocNo);
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
                        DTDis.FTXshDocNo,
                        DTDis.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')    AS FTSessionID,
                        DTDis.FDXddDateIns,
                        DTDis.FNXddStaDis,
                        DTDis.FTXddDisChgType,
                        DTDis.FCXddNet,
                        DTDis.FCXddValue,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                        DTDis.FTXddDisChgTxt
                    FROM TARTSqDTDis DTDis
                    WHERE 1=1 AND DTDis.FTXshDocNo = '$tQTDocNo'
                    ORDER BY DTDis.FNXsdSeqNo ASC";
        $this->db->query($tSQL);
        return;
    }

    //ยกเลิกเอกสาร
    public function FSaMQTUpdateStaDocCancel($paDataUpdate) {
        try {
            $this->db->set('FDLastUpdOn', date('Y-m-d H:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FTXshStaDoc', $paDataUpdate['FTXshStaDoc']);
            $this->db->where('FTXshDocNo', $paDataUpdate['FTXshDocNo']);
            $this->db->update('TARTSqHD');
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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //อนุมัตเอกสาร
    public function FSaMQTApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
        $this->db->set('FTXshApvCode',$paDataUpdate['FTXshUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTSqHD');

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
    public function FSaMQTUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshRmk',$paDataUpdate['FTXshRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTSqHD');

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

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMQTGetDataHDRefTmp($paData){

        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
                    FROM $tTableTmpHDRef
                    WHERE FTXthDocNo  = '$FTXthDocNo'
                      AND FTXthDocKey = '$FTXthDocKey'
                      AND FTSessionID = '$FTSessionID'
                 ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult    = array(
                'aItems'   => $oQuery->result_array(),
                'tCode'    => '1',
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        return $aResult;

    }

    // แท็บค่าอ้างอิงเอกสาร - เพิ่ม
    public function FSaMQTAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tRefDocNo = ( empty($paDataWhere['tQTRefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tQTRefDocNoOld'] );

        $tSQL = " SELECT FTXthRefDocNo FROM TCNTDocHDRefTmp
                  WHERE FTXthDocNo    = '".$paDataWhere['FTXthDocNo']."'
                    AND FTXthDocKey   = '".$paDataWhere['FTXthDocKey']."'
                    AND FTSessionID   = '".$paDataWhere['FTSessionID']."'
                    AND FTXthRefDocNo = '".$tRefDocNo."' ";
        $oQuery = $this->db->query($tSQL);
        $this->db->trans_begin();
        if ( $oQuery->num_rows() > 0 ){
            $this->db->where('FTXthRefDocNo',$tRefDocNo);
            $this->db->where('FTXthDocNo',$paDataWhere['FTXthDocNo']);
            $this->db->where('FTXthDocKey',$paDataWhere['FTXthDocKey']);
            $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
            $this->db->update('TCNTDocHDRefTmp',$paDataAddEdit);
        }else{
            $aDataAdd = array_merge($paDataAddEdit,array(
                'FTXthDocNo'  => $paDataWhere['FTXthDocNo'],
                'FTXthDocKey' => $paDataWhere['FTXthDocKey'],
                'FTSessionID' => $paDataWhere['FTSessionID'],
                'FDCreateOn'  => $paDataWhere['FDCreateOn'],
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

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMQTDelHDDocRef($paData){
        $tQTDocNo       = $paData['FTXthDocNo'];
        $tQTRefDocNo    = $paData['FTXthRefDocNo'];
        $tQTDocKey      = $paData['FTXthDocKey'];
        $tQTSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tQTSessionID);
        $this->db->where('FTXthDocKey',$tQTDocKey);
        $this->db->where('FTXthRefDocNo',$tQTRefDocNo);
        $this->db->where('FTXthDocNo',$tQTDocNo);
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

     // เอกสารอ้างอิงใบรับรถ HD
     public function FSoMQTCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tQTRefIntBchCode        = $aAdvanceSearch['tQTRefIntBchCode'];
        $tQTRefIntDocNo          = $aAdvanceSearch['tQTRefIntDocNo'];
        $tQTRefIntDocDateFrm     = $aAdvanceSearch['tQTRefIntDocDateFrm'];
        $tQTRefIntDocDateTo      = $aAdvanceSearch['tQTRefIntDocDateTo'];
        $tQTRefIntStaDoc         = $aAdvanceSearch['tQTRefIntStaDoc'];
        $tCarCode                = $aAdvanceSearch['tCarCode'];
        $tCstCode                = $aAdvanceSearch['tCstCode'];

        $tSQLMain = "SELECT DATAJOB1.*
                        FROM(
                            SELECT DOCJ1.*,DOCREF.FTXshRefDocNo
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
                                    ADDL.FTAddV2Desc1
                                FROM TSVTJob1ReqHD JOB1 WITH (NOLOCK)
                                LEFT JOIN TSVTJob1ReqHDCst HDCst WITH (NOLOCK) ON JOB1.FTXshDocNo = HDCst.FTXshDocNo AND JOB1.FTBchCode = HDCst.FTBchCode
                                LEFT JOIN TCNMCst CST WITH (NOLOCK) ON JOB1.FTCstCode = CST.FTCstCode
                                LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON JOB1.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = 1
                                LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON JOB1.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = 1
                                LEFT JOIN TCNMCst_L CSTL WITH (NOLOCK) ON JOB1.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = 1
                                LEFT JOIN TCNMCstAddress_L ADDL WITH (NOLOCK) ON JOB1.FTCstCode = ADDL.FTCstCode AND ADDL.FNLngID = 1
                                WHERE JOB1.FTXshStaDoc = 1 AND JOB1.FTXshStaApv = 1 AND ( ISNULL( ADDL.FTAddRefNo, '' ) = '1' OR ISNULL( ADDL.FTAddRefNo, '' ) = '' ) ";

        if(isset($tCarCode) && !empty($tCarCode)){
            $tSQLMain .= " AND (HDCst.FTCarCode = '$tCarCode')";
        }

        if(isset($tCstCode) && !empty($tCstCode)){
            $tSQLMain .= " AND (JOB1.FTCstCode = '$tCstCode')";
        }


        if(isset($tQTRefIntBchCode) && !empty($tQTRefIntBchCode)){
            $tSQLMain .= " AND (JOB1.FTBchCode = '$tQTRefIntBchCode')";
        }

        if(isset($tQTRefIntDocNo) && !empty($tQTRefIntDocNo)){
            $tSQLMain .= " AND (JOB1.FTXshDocNo LIKE '%$tQTRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tQTRefIntDocDateFrm) && !empty($tQTRefIntDocDateTo)){
            $tSQLMain .= " AND ((JOB1.FDXshDocDate BETWEEN CONVERT(datetime,'$tQTRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tQTRefIntDocDateTo 23:59:59')) OR (JOB1.FDXshDocDate BETWEEN CONVERT(datetime,'$tQTRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tQTRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tQTRefIntStaDoc) && !empty($tQTRefIntStaDoc)){
            if ($tQTRefIntStaDoc == 3) {
                $tSQLMain .= " AND JOB1.FTXshStaDoc = '$tQTRefIntStaDoc'";
            } elseif ($tQTRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(JOB1.FTXshStaApv,'') = '' AND JOB1.FTXshStaDoc != '3'";
            } elseif ($tQTRefIntStaDoc == 1) {
                $tSQLMain .= " AND JOB1.FTXshStaApv = '$tQTRefIntStaDoc'";
            }
        }

        $tSQLMain   .= "
                ) AS DOCJ1
                LEFT JOIN (
                    SELECT  DOCREF.FTAgnCode,DOCREF.FTBchCode,DOCREF.FTXshRefDocNo
                    FROM TARTSqHDDocRef DOCREF WITH(NOLOCK)
                    WHERE DOCREF.FTBchCode = '$tQTRefIntBchCode' AND DOCREF.FTXshRefKey = 'Job1Req'
                    GROUP BY DOCREF.FTAgnCode,DOCREF.FTBchCode,DOCREF.FTXshRefDocNo
                ) DOCREF ON DOCJ1.FTAgnCode = DOCREF.FTAgnCode AND DOCJ1.FTBchCode = DOCREF.FTBchCode AND DOCJ1.FTXshDocNo = DOCREF.FTXshRefDocNo
            ) AS DATAJOB1
            WHERE DATAJOB1.FTXshRefDocNo IS NULL
        ";

        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTBchCode ASC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                        (  $tSQLMain
                        ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
                      //echo $tSQL;
                      //exit();

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

    // เอกสารอ้างอิงใบรับรถ DT
    public function FSoMQTCallRefIntDocDTDataTable($paData){

        $nLngID   =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];

        $tSQL= "SELECT
                    DT.FTBchCode,
                    DT.FTXshDocNo,
                    DT.FNXsdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdQtyAll,
                    DT.FTXsdRmk,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TSVTJob1ReqDT DT WITH(NOLOCK)
                    WHERE DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo' ";
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

    // นำข้อมูลจาก Browse ลง DTTemp (ใบรับรถ)
    public function FSoMQTCallRefIntDocInsertDTToTemp($paData){

        $tQTDocNo         = $paData['tQTDocNo'];
        $tQTFrmBchCode    = $paData['tFrmBchCode'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tQTFrmBchCode);
        $this->db->where('FTXthDocNo',$tQTDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

        $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,
                FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdStaAlwDis,FCXtdSalePrice,FCXtdSetPrice , FCXtdNet , FCXtdNetAfHD ,
                FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tQTFrmBchCode' as FTBchCode,
                    '$tQTDocNo',
                    DT.FNXsdSeqNo,
                    'TARTSqDT' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdQtyAll,
                    FTXsdVatType    AS FTXtdVatType,
                    FTVatCode       AS FTVatCode,
                    FCXsdVatRate    AS FCXtdVatRate,
                    FTXsdStaAlwDis  AS FTXtdStaAlwDis,
                    FCXsdSalePrice  AS FCXtdSalePrice,
                    FCXsdSetPrice   AS FCXtdSetPrice,
                    FCXsdNet        AS FCXtdNet,
                    FCXsdNetAfHD    AS FCXtdNetAfHD,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TSVTJob1ReqDT DT WITH (NOLOCK)
                    WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";

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

    //Functionality : ค้นหาข้อมูลรถของลูกค้า
    //Parameters :
    //Creator : 19/11/2021 มอส
    //Last Modified : -
    //Return : Array Data List
    //Return Type : Array
    public function FSaMPreGetDataCarCustomer($paDataCondition){
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
            WHERE 1=1 AND CAR.FTCarCode = '$tCarCst' ";
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

    // Get Data Car Info Job1Req (ใบรับรถ)
    public function FSaMQTGetDataCarInfoJOB1REQ($aDataWhere){
        $tAgnCode   = $aDataWhere['FTAgnCode'];
        $tBchCode   = $aDataWhere['FTBchCode'];
        $tXshDocNo  = $aDataWhere['FTXshDocNo'];
        $nLngID     = $aDataWhere['FNLngID'];
        $tSQL       = "
            SELECT
                J1CST.FTAgnCode,
                J1CST.FTBchCode,
                J1CST.FTXshDocNo,
                CAR.FTCarCode,
                CAR.FTCarRegNo,
                CAR.FTCarEngineNo,
                CAR.FTCarVIDRef,
                CAR.FTCarType       AS FTCarTypeCode,
                T1.FTCaiName        AS FTCarTypeName,
                CAR.FTCarBrand      AS FTCarBrandCode,
                T2.FTCaiName        AS FTCarBrandName,
                CAR.FTCarModel      AS FTCarModelCode,
                T3.FTCaiName        AS FTCarModelName,
                CAR.FTCarColor      AS FTCarColorCode,
                T4.FTCaiName        AS FTCarColorName,
                CAR.FTCarGear       AS FTCarGearCode,
                T5.FTCaiName        AS FTCarGearName,
                CAR.FTCarPowerType  AS FTCarPowerTypeCode,
                T6.FTCaiName        AS FTCarPowerTypeName,
                CAR.FTCarEngineSize AS FTCarEngineSizeCode,
                T7.FTCaiName        AS FTCarEngineSizeName,
                CAR.FTCarCategory   AS FTCarCategoryCode,
                T8.FTCaiName        AS FTCarCategoryName,
                CAR.FDCarDOB,
                CAR.FTCarOwner      AS FTCarOwnerCode,
                CSTL.FTCstName      AS FTCarOwnerName,
                CAR.FDCarOwnChg,
                CAR.FTCarRegProvince    AS FTCarRegPvnCode,
                PVNL.FTPvnName          AS FTCarRegPvnName,
                CAR.FTCarStaRedLabel,
                J1HD.FCXshCarMileage,
                J1HD.FTXshCarFuel
            FROM TSVTJob1ReqHDCst J1CST WITH(NOLOCK)
            LEFT JOIN TSVTJob1ReqHD J1HD WITH(NOLOCK) ON J1CST.FTAgnCode = J1HD.FTAgnCode AND J1CST.FTBchCode = J1HD.FTBchCode AND J1CST.FTXshDocNo = J1HD.FTXshDocNo
            LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON J1CST.FTCarCode = CAR.FTCarCode
            LEFT JOIN TSVMCarInfo_L T1 WITH (NOLOCK) ON CAR.FTCarType = T1.FTCaiCode AND T1.FNLngID     = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T2 WITH (NOLOCK) ON CAR.FTCarBrand = T2.FTCaiCode AND T2.FNLngID    = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T3 WITH (NOLOCK) ON CAR.FTCarModel = T3.FTCaiCode AND T3.FNLngID    = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T4 WITH (NOLOCK) ON CAR.FTCarColor = T4.FTCaiCode AND T4.FNLngID    = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T5 WITH (NOLOCK) ON CAR.FTCarGear = T5.FTCaiCode AND T5.FNLngID     = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T6 WITH (NOLOCK) ON CAR.FTCarPowerType = T6.FTCaiCode AND T6.FNLngID    = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T7 WITH (NOLOCK) ON CAR.FTCarEngineSize = T7.FTCaiCode AND T7.FNLngID   = '$nLngID'
            LEFT JOIN TSVMCarInfo_L T8 WITH (NOLOCK) ON CAR.FTCarCategory = T8.FTCaiCode AND T8.FNLngID     = '$nLngID'
            LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON CAR.FTCarRegProvince = PVNL.FTPvnCode AND PVNL.FNLngID    = '$nLngID'
            LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CAR.FTCarOwner = CSTL.FTCstCode AND CSTL.FNLngID   = '$nLngID'
            WHERE J1CST.FTAgnCode = '$tAgnCode' AND J1CST.FTBchCode = '$tBchCode' AND J1CST.FTXshDocNo  = '$tXshDocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList      = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($tAgnCode);
        unset($tBchCode);
        unset($tXshDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        return $aResult;
    }

    //หา config ของที่อยู่
    public function FSnMQTGetConfigShwAddress(){
        $tSQL = "   SELECT 
                        CASE WHEN ISNULL(FTSysStaUsrValue,'') = '' THEN FTSysStaDefValue ELSE FTSysStaUsrValue END nStaShwAddr
                    FROM TSysConfig WITH(NOLOCK) 
                    WHERE FTSysCode = 'tCN_AddressType' 
                    AND FTSysApp = 'CN' 
                    AND FTSysKey = 'TCNMComp' 
                ";
        $oQuery = $this->db->query($tSQL);
        if( $oQuery->num_rows() > 0 ){
            $aDataList = $oQuery->result_array();
            $nResult   = $aDataList[0]['nStaShwAddr'];
        }else{
            $nResult   = 1;
        }
        return $nResult;
    }





}
