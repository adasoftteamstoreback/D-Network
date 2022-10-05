<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoicebill_model extends CI_Model
{

    //Datatable
    public function FSaMIVBList($paData)
    {
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID     = $paData['FNLngID'];

        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTXphDocNo DESC) AS FNRowID,* FROM
                            ( ";
        $tSQLMain   =         " SELECT 
                                DISTINCT 
                                BCHL.FTBchName,
                                HD.FTBchCode,
                                HD.FTXphDocNo,
                                CONVERT(CHAR(10),HD.FDXphDocDate,103) AS FDXphDocDate,
                                CONVERT(CHAR(10),HD.FDXphDueDate,103) AS FDXphDueDate,
                                SPLL.FTSplName,
                                HD.FTXphStaDoc,
                                HD.FTXphStaApv,
                                HD.FTXphRmk,
                                USRL.FTUsrName  AS FTCreateByName,
                                HD.FDCreateOn
                            FROM TACTPbHD HD WITH (NOLOCK)
                            LEFT JOIN TACTPbHDSpl       HDSPL             WITH (NOLOCK) ON HD.FTBchCode = HDSPL.FTBchCode AND HDSPL.FTXphDocNo = HD.FTXphDocNo
                            LEFT JOIN TCNMBranch_L      BCHL        WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)." 
                            LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK) ON HD.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)." 
                            LEFT JOIN TCNMSPL_L         SPLL        WITH (NOLOCK) ON HD.FTSplCode = SPLL.FTSplCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
                            WHERE 1=1";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if (@$tSearchList != '') {
            $tSQLMain .= " AND ((HD.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%'))";
        }

        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLMain .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQLMain .= " AND ((HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)." AND ".$this->db->escape($tSearchBchCodeTo).") OR (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)." AND ".$this->db->escape($tSearchBchCodeFrom)."))";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchSPLCodeFrom = $aAdvanceSearch['tSearchSPLCodeFrom'];
        $tSearchSPLCodeTo   = $aAdvanceSearch['tSearchSPLCodeTo'];
        if (!empty($tSearchSPLCodeFrom) && !empty($tSearchSPLCodeTo)) {
            $tSQLMain .= " AND ((HDSPL.FTSplCode BETWEEN ".$this->db->escape($tSearchSPLCodeFrom)." AND ".$this->db->escape($tSearchSPLCodeTo).") OR (HDSPL.FTSplCode BETWEEN ".$this->db->escape($tSearchSPLCodeFrom)." AND ".$this->db->escape($tSearchSPLCodeTo)."))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQLMain .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if ($tSearchStaDoc == 3) {
            $tSQLMain .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
        } elseif ($tSearchStaDoc == 2) {
            $tSQLMain .= " AND ISNULL(HD.FTXphStaApv,'') = '' AND HD.FTXphStaDoc != '3'";
        } elseif ($tSearchStaDoc == 1) {
            $tSQLMain .= " AND HD.FTXphStaApv = '$tSearchStaDoc'";
        }

        $tSQL .= $tSQLMain;
        $tSQL .= ") Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."";

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
        // echo $tSQLMain;
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //ลบข้อมูล
    public function FSnMIVBDelDocument($paDataDoc)
    {
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTPbHD');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTPbHDSpl');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTPbHDDocRef');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTPbDT');

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

    //ล้างข้อมูลใน temp
    public function FSaMIVBDeletePDTInTmp($tDocno = '')
    {
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTPbDT');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTPbDTStep2');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

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

    //รายละเอียดสินค้า และราคา ใน Master
    public function FSaMIVBGetDataPdt($paDataPdtParams)
    {
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
                            SPL.FCSplLastPrice,
                            PDTCar.FCPsvWaDistance,
                            PDTCar.FNPsvWaQtyDay,
                            PDTCar.FTPsvWaCond
                        FROM TCNMPdt PDT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = ".$this->db->escape($FTPunCode)."
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = ".$this->db->escape($FTPunCode)."
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TSVMPdtCar PDTCar     WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTCar.FTPdtCode
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
            $tSQL   .= " AND PDT.FTPdtCode   = ".$this->db->escape($tPdtCode)."";
        }

        if (isset($FTBarCode) && !empty($FTBarCode)) {
            $tSQL   .= " AND BAR.FTBarCode = ".$this->db->escape($FTBarCode)."";
        }

        $tSQL   .= " ORDER BY FDVatStart DESC";
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

    //ยกเลิกเอกสาร
    public function FSnMIVBInvoiceBillEventCancel($paDataDoc)
    {
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->set('FTXphStaDoc', '3');
        $this->db->where('FTXphDocNo', $tDataDocNo);
        $this->db->update('TACTPbHD');

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

    //--------------------------------------- บันทึกข้อมูล --------------------------------------------//

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMIVBAddUpdateHD($paDataMaster, $paDataWhere, $paTableAddUpdate)
    {
        $aDataGetDataHD     =   $this->FSaMIVBGetDataDocHD(array(
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
                'FDCreateOn'    => $aDataHDOld['FDCreateOn'],
                'FTCreateBy'    => $aDataHDOld['FTCreateBy'],
                'FTXphStaApv'    => $aDataHDOld['FTXphStaApv'],
                'FTXphApvCode'    => $aDataHDOld['FTXphApvCode'],
                'FTXphStaDoc'    => $aDataHDOld['FTXphStaDoc']
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

    //ข้อมูล CST ลบและ เพิ่มใหม่
    public function FSxMIVBAddUpdateSPLHD($paDataCSTHD, $paDataWhere, $paTableAddUpdate)
    {
        $aDataGetDataSPLHD    =   $this->FSaMIVBGetDataDocSPLHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateCSTHD    = array();
        if (isset($aDataGetDataSPLHD['rtCode']) && $aDataGetDataSPLHD['rtCode'] == 1) {
            $aDataAddUpdateCSTHD    = array_merge($paDataCSTHD, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            ));
        } else {
            $aDataAddUpdateCSTHD    = array_merge($paDataCSTHD, array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo']
            ));
        }

        // Delete SPL
        $this->db->where_in('FTBchCode', $aDataAddUpdateCSTHD['FTBchCode']);
        $this->db->where_in('FTXphDocNo', $aDataAddUpdateCSTHD['FTXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableDTSpl']);

        // Insert SPL
        $this->db->insert($paTableAddUpdate['tTableDTSpl'], $aDataAddUpdateCSTHD);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTPdtClaimDTTmp
    public function FSxMIVBAddUpdateDocNoToTemp($paDataWhere)
    {

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', 'TACTPbDT');
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', 'TACTPbDTStep2');
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));
        return;
    }

    //ข้อมูล DT
    public function FSaMIVBMoveDTTmpToDT($paDataWhere, $paTableAddUpdate ,$paDataMaster)
    {
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tSPLCode     = $paDataWhere['FTSplCode'];
        $tDocKey      = 'TACTPbDTStep2';
        $tSessionID   = $this->session->userdata('tSesSessionID');
        $tADCode      = $this->session->userdata('tSesUsrAgnCode');
        $docdate      = date("Y-m-d");

        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where_in('FTXphDocNo', $tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        //TCNTPdtPBDT 
        $tSQL   = "     INSERT INTO " . $paTableAddUpdate['tTableDT'] . " (
                            FTXphDocNo , FNXpdSeqNo , FTXpdRefDocNo , FTXpdRefDocType , FDXpdRefDocDate ,
                            FTSplCode , FCXpdInvLeft , FCXpdInvPaid , FCXpdInvRem , FDXpdDueDate , FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy ,
                            FTBchCode , FTAgnCode ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTXthDocNo AS FTXphDocNo ,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FTXthDocNo ASC) AS FNXpdSeqNo,
                            DOCTMP.FTPdtCode AS FTXpdRefDocNo ,
                            1 AS FTXpdRefDocType ,
                            ".$this->db->escape($docdate)." AS FDXpdRefDocDate ,
                            ".$this->db->escape($tSPLCode)." AS FTSplCode ,
                            ISNULL(DOCTMP.FCXtdVatable,DOCTMP.FCXtdAmt) AS FCXpdInvLeft,
                            DOCTMP.FCXtdSetPrice AS FCXpdInvPaid ,
                            ISNULL(( DOCTMP.FCXtdVatable - DOCTMP.FCXtdSetPrice ),DOCTMP.FCXtdAmt) AS FCXpdInvRem ,
                            DOCTMP.FDAjdDateTimeC1 AS FDXpdDueDate ,
                            DOCTMP.FDLastUpdOn ,
                            DOCTMP.FTLastUpdBy ,
                            DOCTMP.FDCreateOn ,
                            DOCTMP.FTCreateBy ,
                            ".$this->db->escape($tBchCode).",
                            ".$this->db->escape($tADCode)."
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE DOCTMP.FTXthDocNo   = ".$this->db->escape($tDocNo)."
                        AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                        AND DOCTMP.FTSessionID  = ".$this->db->escape($tSessionID)."
                        ORDER BY DOCTMP.FTXthDocNo ASC";
        $this->db->query($tSQL);

        return;
    }

    //อัพเดทคลังเคลมเปลี่ยน คลังเคลมรับในตาราง
    public function FSaMIVBUpdateWahouseInTable($paDataWhere)
    {
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXthDocNo'];

        //อัพเดทคลังเคลมรับ
        $tSQL = "   UPDATE TCNTPdtClaimDT 
                    SET FTWahCode = TCNMWaHouse.FTWahCode
                    FROM (
                            SELECT TOP 1 FTWahCode 
                            FROM TCNMWaHouse WHERE 
                            TCNMWaHouse.FTWahStaType ='8' AND 
                            TCNMWaHouse.FTBchCode = ".$this->db->escape($tBchCode)."
                            ORDER BY FTWahCode DESC 
                        ) AS TCNMWaHouse
                    WHERE 
                        TCNTPdtClaimDT.FTXthDocNo = ".$this->db->escape($tDocNo)." AND
                        TCNTPdtClaimDT.FTBchCode = ".$this->db->escape($tBchCode)." ";
        $this->db->query($tSQL);

        //อัพเดทคลังเคลมเปลี่ยน
        $tSQL = "   UPDATE TCNTPdtClaimDTSpl 
                    SET FTWahCode = TCNMWaHouse.FTWahCode
                    FROM (
                            SELECT TOP 1 FTWahCode 
                            FROM TCNMWaHouse WHERE 
                            TCNMWaHouse.FTWahStaType ='9' AND 
                            TCNMWaHouse.FTBchCode = ".$this->db->escape($tBchCode)." 
                            ORDER BY FTWahCode DESC 
                        ) AS TCNMWaHouse
                    WHERE 
                        TCNTPdtClaimDTSpl.FTXthDocNo = ".$this->db->escape($tDocNo)." AND
                        TCNTPdtClaimDTSpl.FTBchCode = ".$this->db->escape($tBchCode)." ";
        $this->db->query($tSQL);
    }

    //เช็คว่ามีคลังเคลมเปลี่ยน คลังเคลมรับ
    public function FSaMIVBFindWahouseINBranch($paDataWhere)
    {
        $tBCHCode   = $paDataWhere['tBCHCode'];
        //8 : เคลมรับ
        //9 : เคลมเปลี่ยน
        $tSQL   = "SELECT SUM(A.CountWah) AS CountWahouse FROM(
                        SELECT TOP 1 COUNT(FTWahCode) AS CountWah FROM TCNMWaHouse WHERE FTBchCode = ".$this->db->escape($tBCHCode)." AND FTWahStaType ='8' 
                        UNION ALL
                        SELECT TOP 1 COUNT(FTWahCode) AS CountWah FROM TCNMWaHouse WHERE FTBchCode = ".$this->db->escape($tBCHCode)." AND FTWahStaType ='9' 
                    ) AS A";
        $oQuery = $this->db->query($tSQL);
        $aFindWah = $oQuery->result_array();
        if ($aFindWah[0]['CountWahouse'] >= 2) { //ต้องเจอทั้งคลังเคลมรับ และ เคลมเปลี่ยน
            $aResult    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Find',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    //เช็คว่าสินค้าที่จะยืนยันนั้นได้ระบบ SPL มาเเล้ว
    public function FSaMIVBFindSPLInTemp($paDataWhere)
    {
        $tIVBDocNo      = $paDataWhere['tIVBDocNo'];
        $tSessionID     = $this->session->userdata('tSesSessionID');

        $tSQL       = " SELECT FTSplCode FROM TCNTPdtClaimDTTmp WHERE 
                            FTXthDocNo = ".$this->db->escape($tIVBDocNo)." AND 
                            FTSessionID = ".$this->db->escape($tSessionID)." AND 
                            FTXthDocKey = 'InvoiceBillStep1Point1' AND
                            ISNULL(FTSplCode,'') = '' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'PDT NOT HAVE SPL',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'PDT Have SPL',
            );
        }
        return $aResult;
    }

    //--------------------------------------- เข้าหน้าแก้ไข --------------------------------------------//

    //ข้อมูล HD
    public function FSaMIVBGetDataDocHD($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            PBHD.*,
                            BCHL.FTBchName,
                            USRL.FTUsrName,
                            APV.FTUsrName AS FTXphApvName,
                            SPL_L.FTSplName
                        FROM TACTPbHD         PBHD  WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL   WITH (NOLOCK)   ON PBHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMSpl_L         SPL_L  WITH (NOLOCK)   ON PBHD.FTSplCode = SPL_L.FTSplCode AND SPL_L.FNLngID = ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMUser_L        USRL   WITH (NOLOCK)   ON PBHD.FTCreateBy = USRL.FTUsrCode	AND USRL.FNLngID	= ".$this->db->escape($nLngID)."
                        LEFT JOIN TCNMUser_L        APV   WITH (NOLOCK)    ON PBHD.FTXphApvCode = APV.FTUsrCode	AND APV.FNLngID	= ".$this->db->escape($nLngID)."
                        WHERE PBHD.FTXphDocNo = ".$this->db->escape($tDocNo)." ";

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
    public function FSaMIVBGetDataDocSPLHD($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMSpl');

        $tSQL       = " SELECT
                        SPLHD.FTAgnCode,
                        SPLHD.FTBchCode,
                        SPLHD.FTXphDocNo,
                        SPLHD.FTXphDstPaid,
                        SPLHD.FTXphCshOrCrd,
                        SPLHD.FNXphCrTerm,
                        SPLHD.FTXphCtrName,
                        SPLHD.FDXphTnfDate,
                        SPLHD.FTXphRefTnfID,
                        SPLHD.FTXphRefVehID,
                        SPLHD.FTXphRefInvNo,
                        SPLHD.FTXphQtyAndTypeUnit,
                        SPLHD.FNXphShipAdd,
                        SPLHD.FNXphTaxAdd,
                        SPL_L.FTSplCode,
                        SPL_L.FTSplName,
                        SPLHD.FTXphCtrName AS CtrName,
                        SPL.FTSplTel,
                        ISNULL(CAD.FTAddV1No,'') AS FTAddV1No,
                        ISNULL(CAD.FTAddV1Soi,'') AS  FTAddV1Soi,
                        ISNULL(CAD.FTAddV1Road,'') AS FTAddV1Road,
                        ISNULL(CAD.FTAddV1Village,'') AS FTAddV1Village,
                        ISNULL(SDT.FTSudName,'') AS FTSudName,
                        ISNULL(DTS.FTDstName,'') AS FTDstName,
                        ISNULL(PVN.FTPvnName,'') AS FTPvnName,
                        ISNULL(CAD.FTAddV2Desc1,'') AS FTAddV2Desc1,
                        ISNULL(CAD.FTAddV2Desc2,'') AS FTAddV2Desc2,
                        SPL.FTSplEmail
                    FROM TACTPbHDSpl            SPLHD       WITH (NOLOCK)
                    INNER JOIN TACTPbHD         HD          WITH (NOLOCK)   ON SPLHD.FTXphDocNo     = HD.FTXphDocNo     AND SPLHD.FTBchCode = HD.FTBchCode 
                    LEFT JOIN TCNMSpl           SPL         WITH (NOLOCK)   ON HD.FTSplCode		    = SPL.FTSplCode
                    LEFT JOIN TCNMSpl_L         SPL_L       WITH (NOLOCK)   ON HD.FTSplCode		    = SPL_L.FTSplCode        AND SPL_L.FNLngID	      = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMSplAddress_L CAD WITH(NOLOCK) ON SPL.FTSplCode = CAD.FTSplCode AND CAD.FTAddVersion = ".$this->db->escape($nAddressVersion)."
                    LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($nLngID)."
                    WHERE 1=1 AND SPLHD.FTXphDocNo = ".$this->db->escape($tDocNo)." ";
        $oQuery = $this->db->query($tSQL);
        // echo $tSQL;

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

    //ช้อมูล HDDocRef
    public function FSaMIVBGetDataDocHDDocRef($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];

        $tSQL       = " SELECT
                            TOP 2
                            HDREF.FTXphRefType,
                            HDREF.FTXphDocNo,
                            HDREF.FDXphRefDocDate
                        FROM TACTPbHDDocRef   HDREF  WITH (NOLOCK)
                        WHERE HDREF.FTXphDocNo = ".$this->db->escape($tDocNo)."
                        AND HDREF.FTXphRefKey IN ('OTHER','ABB') ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->result();
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

    //--------------------------------------- ย้ายข้อมูลจากจริงไป Temp --------------------------------------------//

    //ย้ายจาก DT To Temp
    public function FSxMIVBMoveDTToDTTemp($paDataWhere)
    {
        $tDocNo         = $paDataWhere['FTXphDocNo'];
        $tSplCode       = $paDataWhere['FTSplCode'];
        $tDocKey        = 'TACTPbDT';
        $tDocKey2       = 'TACTPbDTStep2';

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->delete('TCNTDocDTTmp');


        //TCNTPdtPBDT Step 2
        $tSQL   = "     INSERT INTO TCNTDocDTTmp (
            FTBchCode , FTXthDocNo , FNXtdSeqNo , FTPdtCode , FTPunCode ,
            FCXtdVatable , FCXtdSetPrice , FDAjdDateTimeC1 , FDAjdDateTimeC2 , FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy , FTXthDocKey , FTSessionID) ";
        $tSQL   .=  "   SELECT
                            PBDT.FTBchCode , 
                            PBDT.FTXphDocNo AS FTXthDocNo ,
                            PBDT.FNXpdSeqNo AS FNXtdSeqNo,
                            PBDT.FTXpdRefDocNo AS FTPdtCode ,
                            PBDT.FTXpdRefDocType AS FTPunCode,
                            PBDT.FCXpdInvLeft AS FCXtdVatable ,
                            PBDT.FCXpdInvPaid AS FCXtdSetPrice ,
                            PBDT.FDXpdDueDate AS FDAjdDateTimeC1 ,
                            PBDT.FDXpdRefDocDate AS FDAjdDateTimeC2 ,
                            PBDT.FDLastUpdOn ,
                            PBDT.FTLastUpdBy ,
                            PBDT.FDCreateOn ,
                            PBDT.FTCreateBy ,
                            CONVERT(VARCHAR,".$this->db->escape($tDocKey2).") AS FTXthDocKey,
                            CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID
                        FROM TACTPbDT PBDT WITH (NOLOCK)
                        WHERE PBDT.FTXphDocNo   = ".$this->db->escape($tDocNo)."
                        ORDER BY PBDT.FTXphDocNo ASC";
        $this->db->query($tSQL);

        return;
    }

    //--------------------------------------- อ้างอิงเอกสารภายใน --------------------------------------------//

    // Function: Get Data ใบขาย HD List
    public function FSoMIVBCallRefIntDocDataTable($paDataCondition)
    {
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tIVBRefIntBchCode        = $aAdvanceSearch['tIVBRefIntBchCode'];
        $tIVBRefIntDocNo          = $aAdvanceSearch['tIVBRefIntDocNo'];
        $tIVBRefIntDocDateFrm     = $aAdvanceSearch['tIVBRefIntDocDateFrm'];
        $tIVBRefIntDocDateTo      = $aAdvanceSearch['tIVBRefIntDocDateTo'];
        $tIVBRefIntStaDoc         = $aAdvanceSearch['tIVBRefIntStaDoc'];

        $tSQLMain = "   SELECT
                            DISTINCT
                                SALHD.FTBchCode,
                                BCHL.FTBchName,
                                SALHD.FTXshDocNo,
                                CONVERT(CHAR(10),SALHD.FDXshDocDate,103) AS FDXshDocDate,
                                CONVERT(CHAR(5), SALHD.FDXshDocDate,108) AS FTXshDocTime,
                                SALHD.FTXshStaDoc,
                                SALHD.FTXshStaApv,
                                SALHD.FNXshStaRef,
                                SALHD.FTCreateBy,
                                SALHD.FDCreateOn,
                                SALHD.FNXshDocType,
                                USRL.FTUsrName      AS FTCreateByName
                            FROM TPSTSalHD          SALHD   WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SALHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID      = ".$this->db->escape($nLngID)." 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON SALHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID      = ".$this->db->escape($nLngID)." 
                        WHERE 1=1 AND SALHD.FTXshStaDoc = 1
                    ";

        if (isset($tIVBRefIntBchCode) && !empty($tIVBRefIntBchCode)) {
            $tSQLMain .= " AND (SALHD.FTBchCode = '%".$this->db->escape_like_str($tIVBRefIntBchCode)."%')";
        }

        if (isset($tIVBRefIntDocNo) && !empty($tIVBRefIntDocNo)) {
            $tSQLMain .= " AND (SALHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tIVBRefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tIVBRefIntDocDateFrm) && !empty($tIVBRefIntDocDateTo)) {
            $tSQLMain .= " AND ((SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tIVBRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVBRefIntDocDateTo 23:59:59')) OR (SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tIVBRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVBRefIntDocDateFrm 00:00:00')))";
        }

        $tSQL   =   "SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
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

    // Functionality: Get Data ใบขาย DT List
    public function FSoMIVBCallRefIntDocDTDataTable($paData)
    {

        $nLngID    =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];

        $tSQL = "SELECT
                        DT.FTBchCode,
                        DT.FTXshDocNo,
                        DT.FNXsdSeqNo,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FTXsdPdtName,
                        DT.FTXsdBarCode,
                        DT.FCXsdQty,
                        DT.FCXsdQtyAll,
                        DT.FTXsdRmk,
                        DT.FDLastUpdOn,
                        DT.FTLastUpdBy,
                        DT.FDCreateOn,
                        DT.FTCreateBy
                        FROM TPSTSalDT DT WITH(NOLOCK)
                WHERE DT.FTBchCode = ".$this->db->escape($tBchCode)." AND  DT.FTXshDocNo = ".$this->db->escape($tDocNo)." ";

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

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMIVBCallRefIntDocInsertDTToTemp($paData)
    {

        $tIVBDocNo        = $paData['tIVBDocNo'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey', $paData['tDocKey']);
        $this->db->where('FTXthDocNo', $tIVBDocNo);
        $this->db->delete('TCNTPdtClaimDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) . ')';

        $tSQL = "INSERT INTO TCNTPdtClaimDTTmp (
                    FTXthDocNo,FNPcdSeqNo,FTPdtCode,FTPcdPdtName,FTPunCode,FTPunName,
                    FCPcdFactor,FTPcdBarCode,FCPcdQty,FCPcdQtyAll,FTPcdStaClaim,
                    FTPcdRmk,FTXthDocKey,FTSessionID,FCPsvWaDistance,
                    FNPsvWaQtyDay,FTPsvWaCond,FDLastUpdOn,FTLastUpdBy,
                    FDCreateOn,FTCreateBy
                )
                SELECT
                    ".$this->db->escape($tIVBDocNo)." as FTXthDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXsdSeqNo DESC ) AS FNPcdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty,
                    DT.FCXsdFactor*DT.FCXsdQty,
                    2 AS FTPcdStaClaim,
                    null AS FTPcdRmk,
                    ".$this->db->escape($paData['tDocKey'])." AS FTXthDocKey,  
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID,
                    PDTCar.FCPsvWaDistance,
                    PDTCar.FNPsvWaQtyDay,
                    PDTCar.FTPsvWaCond,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy,
                    CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                FROM
                    TPSTSalDT DT WITH (NOLOCK)
                LEFT JOIN TSVMPdtCar PDTCar WITH (NOLOCK) ON DT.FTPdtCode = PDTCar.FTPdtCode
                WHERE DT.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND DT.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)." AND DT.FNXsdSeqNo IN $aSeqNo ";

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

    //เพิ่มข้อมูลว่าอ้างอิงเอกสาร 
    public function FSxMIVBUpdateRef($ptTableName, $paParam)
    {
        $nChkDataDocRef  = $this->FSaMIVBChkRefDupicate($ptTableName, $paParam);
        $tTableRef       = $ptTableName;
        if (isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1) { //หากพบว่าซ้ำ
            //ลบ

            if ($ptTableName == 'TCNTPdtClaimHDDocRef') {
                $this->db->where_in('FTAgnCode', $paParam['FTAgnCode']);
                $this->db->where_in('FTXthDocNo', $paParam['FTXthDocNo']);
            } else {
                $this->db->where_in('FTXshDocNo', $paParam['FTXshDocNo']);
            }

            $this->db->where_in('FTBchCode', $paParam['FTBchCode']);
            $this->db->where_in('FTXshRefType', $paParam['FTXshRefType']);
            $this->db->where_in('FTXshRefKey', $paParam['FTXshRefKey']);
            $this->db->delete($tTableRef);

            //เพิ่มใหม่
            $this->db->insert($tTableRef, $paParam);
        } else { //หากพบว่าไม่ซ้ำ
            $this->db->insert($tTableRef, $paParam);
        }
        return;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIVBChkRefDupicate($ptTableName, $paParam)
    {
        try {
            $tBchCode       = $paParam['FTBchCode'];
            $tRefDocType    = $paParam['FTXshRefType'];

            if ($ptTableName == 'TCNTPdtClaimHDDocRef') {
                $tDocNo         = $paParam['FTXthDocNo'];
                $tRefDocNo      = $paParam['FTXthDocNo'];
            } else {
                $tDocNo         = $paParam['FTXshDocNo'];
                $tRefDocNo      = $paParam['FTXshDocNo'];
            }

            $tSQL = "   SELECT 
                            FTBchCode
                        FROM $ptTableName
                        WHERE FTBchCode != ''
                        AND FTBchCode     = ".$this->db->escape($tBchCode)."
                        AND FTXshRefType  = ".$this->db->escape($tRefDocType)." ";

            if ($tRefDocType == 1 || $tRefDocType == 3) {
                $tSQL .= " AND FTXthDocNo  = ".$this->db->escape($tDocNo)." ";
            } else {
                $tSQL .= " AND FTXshDocNo  = ".$this->db->escape($tRefDocNo)." ";
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

    // หาว่าเอกสารอ้างอิง นี้ลูกค้าเป็นอะไร
    public function FSoMIVBCallRefIntDocFindCstAndCar($paData)
    {

        $tRefIntDocNo    =  $paData['tRefIntDocNo'];
        $tRefIntBchCode  =  $paData['tRefIntBchCode'];

        $tSQL = "SELECT
                    HD.FTCstCode ,
                    HDCst.FTCarCode ,
                    CAR.FTCarRegNo ,
                    HDCst.FTXshCstTel ,
                    HDCst.FTXshCstName ,
                    HDCst.FTXshCstEmail ,
                    ADDL.FTAddV2Desc1 
                    FROM TPSTSalHD HD WITH(NOLOCK)
                    LEFT JOIN TPSTSalHDCst HDCst ON HD.FTXshDocNo = HDCst.FTXshDocNo AND HD.FTBchCode = HDCst.FTBchCode
                    LEFT JOIN TCNMCstAddress_L ADDL ON HD.FTCstCode = ADDL.FTCstCode
                    LEFT JOIN TSVMCar CAR ON HDCst.FTCarCode = CAR.FTCarCode AND CAR.FTCarOwner = HD.FTCstCode
                WHERE HD.FTBchCode = ".$this->db->escape($tRefIntBchCode)." AND  HD.FTXshDocNo = ".$this->db->escape($tRefIntDocNo)."
                AND ISNULL(HD.FTCstCode,'') <> '' ";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aResult = array(
                'raItems'       => $oQuery->result_array(),
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

    //--------------------------------------- STEP 1 - POINT 1 --------------------------------------------//

    //Get ข้อมูลใน Temp
    public function FSaMIVBListStep1Point1($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FTXtdDocNoRef ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet,
                                        CONVERT(CHAR(10),DOCTMP.FCDateTimeInputForADJSTKVD,23) AS DateRefDoc
                                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                    WHERE 1 = 1
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                    AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                    AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";

        $tSQL               .= ") Base) AS c ";
        $oQuery = $this->db->query($tSQL);


        $tSQL2      = " SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FTPdtCode ASC) AS rtRowID,* FROM (
                        SELECT
                            DOCTMP.FTPdtCode
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                        AND DOCTMP.FTXthDocKey = 'TACTPbDTStep2'
                        AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";

        $tSQL2               .= ") Base) AS c ";
        $oQuery2 = $this->db->query($tSQL2);

        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aDataList2  = $oQuery2->result_array();
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'Step2Item'     => $aDataList2,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aDataReturn    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aDataReturn;
    }

    //Get ข้อมูลใน Temp ว่า SPL อะไร
    public function FSaMIVBPDTFindBySPL($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');

        //Update SPL 
        if ($tDocNo == 'DUMMY') {
            $tSQL = "UPDATE DOCTMP
                        SET 
                        DOCTMP.FTSplCode = RES.SPL_Code 
                        FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                    LEFT JOIN (
                        SELECT
                            TOP 1
                            DOCTMP.FNPcdSeqNo , 
                            DOCTMP.FTXthDocNo ,
                            DOCTMP.FTPdtCode ,
                            DOCTMP.FTSessionID ,
                            DOCTMP.FTXthDocKey,       
                            CASE
                                WHEN ISNULL(PIHD.FTSplCode,'') <> '' THEN PIHD.FTSplCode
                                WHEN ISNULL(PDTSPL.FTSplCode,'') <> '' THEN PDTSPL.FTSplCode
                            ELSE null
                            END AS SPL_Code
                        FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                        LEFT JOIN (
                            SELECT DISTINCT HD.FTSplCode , DT.FTPDTCode , PDTSPLL.FTSplName FROM TAPTPiHD HD
                            LEFT JOIN TAPTPiDT DT           ON HD.FTXphDocNo = DT.FTXphDocNo
                            LEFT JOIN TCNMSpl_L PDTSPLL     ON HD.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = ".$this->db->escape($nLngID)."
                        ) AS PIHD                           ON DOCTMP.FTPdtCode = PIHD.FTPdtCode
                        LEFT JOIN (
                            SELECT DISTINCT PDTSPL.FTSplCode , PDTSPL.FTPDTCode , PDTSPLL.FTSplName , PDTSPL.FTBarCode FROM TCNMPdtSpl PDTSPL
                            LEFT JOIN TCNMSpl_L PDTSPLL     ON PDTSPL.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = ".$this->db->escape($nLngID)."
                        ) AS PDTSPL                         ON DOCTMP.FTPdtCode = PDTSPL.FTPdtCode AND DOCTMP.FTPcdBarCode = PDTSPL.FTBarCode
                        WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                        AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                        AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)."
                    ) RES 
                    ON RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                    AND RES.FTXthDocNo = DOCTMP.FTXthDocNo 
                    AND RES.FTPdtCode = DOCTMP.FTPdtCode
                    AND RES.FTXthDocKey = DOCTMP.FTXthDocKey 
                    AND RES.FTSessionID = DOCTMP.FTSessionID 
                    WHERE RES.FTSessionID = ".$this->db->escape($tSesSessionID)." ";
            $this->db->query($tSQL);
        }

        //Select SPL
        $tSQL  = " SELECT
                    TOP 1
                    DOCTMP.FTPdtCode ,         
                    PIHD.FTSplCode          AS SPL_PI_Code,
                    PIHD.FTSplName          AS SPL_PI_Name,
                    PDTSPL.FTSplCode        AS SPL_PDT_Code,
                    PDTSPL.FTSplName        AS SPL_PDT_Name
                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                LEFT JOIN (
                    SELECT DISTINCT HD.FTSplCode , DT.FTPDTCode , PDTSPLL.FTSplName FROM TAPTPiHD HD
                    LEFT JOIN TAPTPiDT DT           ON HD.FTXphDocNo = DT.FTXphDocNo
                    LEFT JOIN TCNMSpl_L PDTSPLL     ON HD.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = ".$this->db->escape($nLngID)."
                ) AS PIHD                           ON DOCTMP.FTPdtCode = PIHD.FTPdtCode
                LEFT JOIN (
                    SELECT DISTINCT PDTSPL.FTSplCode , PDTSPL.FTPDTCode , PDTSPLL.FTSplName , PDTSPL.FTBarCode FROM TCNMPdtSpl PDTSPL
                    LEFT JOIN TCNMSpl_L PDTSPLL     ON PDTSPL.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = ".$this->db->escape($nLngID)."
                ) AS PDTSPL                         ON DOCTMP.FTPdtCode = PDTSPL.FTPdtCode AND DOCTMP.FTPcdBarCode = PDTSPL.FTBarCode
                WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";
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
        return $aDataReturn;
    }

    //Insert ข้อมูลใน Temp (step1)
    public function FSaMIVBInsertPDTToTemp($paDataPdtMaster, $paDataPdtParams)
    {
        $paItemDataPdt    = $paDataPdtMaster['raItem'];

        // เพิ่มแถวใหม่
        $aDataInsert    = array(
            'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
            'FNPcdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
            'FTPdtCode'         => $paItemDataPdt['FTPdtCode'],
            'FTPcdPdtName'      => $paItemDataPdt['FTPdtName'],
            'FTPunCode'         => $paItemDataPdt['FTPunCode'],
            'FTPunName'         => $paItemDataPdt['FTPunName'],
            'FCPcdFactor'       => $paItemDataPdt['FCPdtUnitFact'],
            'FTPcdBarCode'      => $paDataPdtParams['tBarCode'],
            'FCPcdQty'          => 1,
            'FTPcdStaClaim'     => 2, //default : ไม่อนุญาติเคลม
            'FCPcdQtyAll'       => 1 * $paItemDataPdt['FCPdtUnitFact'],
            'FTPcdRmk'          => '',
            'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
            'FTSessionID'       => $paDataPdtParams['tSessionID'],
            'FCPsvWaDistance'   => $paItemDataPdt['FCPsvWaDistance'],
            'FNPsvWaQtyDay'     => $paItemDataPdt['FNPsvWaQtyDay'],
            'FTPsvWaCond'       => $paItemDataPdt['FTPsvWaCond'],
            'FDLastUpdOn'       => date('Y-m-d h:i:s'),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date('Y-m-d h:i:s'),
            'FTCreateBy'        => $this->session->userdata('tSesUsername')
        );
        $this->db->insert('TCNTPdtClaimDTTmp', $aDataInsert);
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
        return $aStatus;
    }

    //ลบข้อมูลใน Temp 
    public function FSnMIVBDelDTTmp($paData)
    {
        try {
            $this->db->trans_begin();

            if ($paData['tDocKey'] == 'InvoiceBillStep1Point1') {
                $this->db->where_in('FNPcdSeqNo', $paData['nMaxSeqNo']);
            } else if ($paData['tDocKey'] == 'InvoiceBillStep3') {
                $this->db->where_in('FNWrnSeq', $paData['nMaxSeqNo']);
            }

            $this->db->where_in('FTXthDocNo', $paData['tDocNo']);
            $this->db->where_in('FTPdtCode',  $paData['tPDTCode']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->where_in('FTXthDocKey', $paData['tDocKey']);
            $this->db->delete('TCNTPdtClaimDTTmp');

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

    //อัพเดทจำนวน
    public function FSaMIVBUpdateInlineDTTemp($paDataUpdateDT, $paDataWhere)
    {
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $nSeq           = $paDataWhere['nSeq'];
        $tDocKey        = $paDataWhere['tDocKey'];

        $this->db->set('FCPcdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCPcdQtyAll', 1 * $paDataUpdateDT['FCXtdQty']);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FNPcdSeqNo', $nSeq);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');
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

    //--------------------------------------- STEP 1 - POINT 2 --------------------------------------------//

    //อัพเดทสถานะเคลมภายใน + หมายเหตุ + ผู้จำหน่าย(point3) + วันที่แจ้ง(point3) + จำนวนยืม(point4) + สินค้าที่ยืม(point4)
    public function FSaMIVBUpdateInlineDTTempStaAndRmk($paDataUpdateDT, $paDataWhere, $tTypeUpdate)
    {
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nSeq           = $paDataWhere['nSeq'];

        switch ($tTypeUpdate) {
            case 'StatusClaim':     //Step 1.2 สถานะเคลมภายใน
                $this->db->set('FTPcdStaClaim', $paDataUpdateDT['FTPcdStaClaim']);
                break;
            case 'RmkClaim':        //Step 1.3 หมายเหตุ
                $this->db->set('FTPcdRmk', $paDataUpdateDT['FTPcdRmk']);
                break;
            case 'DateClaim':       //Step 1.3 วันที่แจ้งเคลม
                $this->db->set('FDPcdDateReq', $paDataUpdateDT['FDPcdDateReq']);
                break;
            case 'SPLClaim':        //Step 1.3 แจ้งเคลมผู้จำหน่าย
                $this->db->set('FTSplCode', $paDataUpdateDT['FTSplCode']);
                break;
            case 'PDTClaim':        //Step 1.4 สินค้าที่ขอยืม
                if ($paDataUpdateDT['FTPcdPdtPick'] == '' || $paDataUpdateDT['FTPcdPdtPick'] == null) {
                    $this->db->set('FTPcdStaPick', null); //ไม่มีการยืม
                } else {
                    $this->db->set('FTPcdStaPick', 1); //มีการยืม
                }
                $this->db->set('FTPcdPdtPick', $paDataUpdateDT['FTPcdPdtPick']);
                break;
            case 'QTYPICKClaim':    //Step 1.4 จำนวนที่ยืม
                $this->db->set('FCPcdQtyPick', $paDataUpdateDT['FCPcdQtyPick']);
                break;
            case 'DateGetClaim':    //Step 2 - วันที่เข้ามารับของ
                $this->db->set('FDPcdSplGetDate', $paDataUpdateDT['FDPcdSplGetDate']);
                break;
            case 'RmkGet':          //Step 2 - หมายเหตุ
                $this->db->set('FTPcdSplRmk', $paDataUpdateDT['FTPcdSplRmk']);
                break;
            case 'UserGet':         //Step 2 - ชื่อคนมารับของ
                $this->db->set('FTPctSplStaff', $paDataUpdateDT['FTPctSplStaff']);
                break;
            default:
                break;
        }

        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FNPcdSeqNo', $nSeq);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //--------------------------------------- STEP 2 --------------------------------------------//

    //อัพเดทข้อมูล
    public function FSaMIVBStep2UpdatePrcDoc($paDataWhere)
    {
        $tBCHCode       = $paDataWhere['tBCHCode'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "UPDATE DOCTMP
                    SET 
                        DOCTMP.FDPcdSplGetDate = RES.FDPcdSplGetDate ,
                        DOCTMP.FTPcdSplRmk = RES.FTPcdSplRmk ,
                        DOCTMP.FTPctSplStaff = RES.FTPctSplStaff 
                    FROM TCNTPdtClaimDTSpl AS DOCTMP WITH(NOLOCK)
                    LEFT JOIN (
                        SELECT 
                            UPD.FTXthDocNo, 
                            UPD.FNPcdSeqNo ,
                            UPD.FDPcdSplGetDate	,
                            UPD.FTPcdSplRmk ,
                            UPD.FTPctSplStaff
                        FROM TCNTPdtClaimDTTmp UPD WITH(NOLOCK)
                        WHERE UPD.FTXthDocNo = ".$this->db->escape($tDocNo)."
                        AND UPD.FTSessionID = ".$this->db->escape($tSesSessionID)."
                        AND UPD.FTXthDocKey = 'InvoiceBillStep1Point1'
                    ) RES 
                ON RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                AND RES.FTXthDocNo = DOCTMP.FTXthDocNo ";
        $this->db->query($tSQL);
    }

    //--------------------------------------- STEP 3 --------------------------------------------//

    //ช้อมูลในตาราง
    public function FSaMIVBListTableStep3($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $tDocKey2           = $paDataWhere['tDocKey2'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT
                                    DOCTMP.FNPcdSeqNo,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTPcdBarCode,
                                    DOCTMP.FCPcdQty,
                                    DOCTMP.FTPcdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FDPcdDateReq,
                                    SPLL.FTSplName,
                                    SUM(TMPWrn.FCWrnPdtQty) AS SUMQTY,
                                    SUM(ISNULL(TMPWrn.FCRcvPdtQty,0)) AS SUMRCVQTY
                                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTXthDocNo = TMPWrn.FTXthDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = ".$this->db->escape($tDocKey2)."
                                WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)."
                                GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        SPLL.FTSplName ";
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
        return $aDataReturn;
    }

    //รายละเอียดข้อมูลของสินค้าตัวที่กดดู หรือตัวที่กำลังจะกดบันทึก ตาม SEQ
    public function FSaMIVBGetItemClaimBySeq($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $tDocKey2           = $paDataWhere['tDocKey2'];
        $nSeqPDT            = $paDataWhere['nSeqPDT'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');

        $tSQL               = " SELECT
                                    DOCTMP.FNPcdSeqNo,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTPcdBarCode,
                                    DOCTMP.FCPcdQty,
                                    DOCTMP.FTPcdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FDPcdDateReq,
                                    SPLL.FTSplName,
                                    SPLL.FTSplCode,
                                    SUM(TMPWrn.FCWrnPdtQty) AS SUMQTY,
                                    SUM(ISNULL(TMPWrn.FCRcvPdtQty,0)) AS SUMRCVQTY
                                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTXthDocNo = TMPWrn.FTXthDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = ".$this->db->escape($tDocKey2)."
                                WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                AND DOCTMP.FNPcdSeqNo = ".$this->db->escape($nSeqPDT)."
                                AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)."
                                GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        SPLL.FTSplName,
                                        SPLL.FTSplCode ";

        $oQuery     = $this->db->query($tSQL);
        $aDataList  = $oQuery->result_array();
        return $aDataList;
    }

    //Insert ข้อมูลใน Temp (step3)
    public function FSaMIVBInsertPDTToTempStep3($paDataPdtParams)
    {

        //หา Seq DNCN 
        $tDocNo     = $paDataPdtParams['tDocNo'];
        $tDocKey    = $paDataPdtParams['tDocKey'];
        $tSessionID = $paDataPdtParams['tSessionID'];
        $tPDTCode   = $paDataPdtParams['tPDTCode'];
        $tSQL               = " SELECT TOP 1 DOCTMP.FNWrnSeq FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                WHERE 1 = 1
                                AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                AND DOCTMP.FTSessionID = ".$this->db->escape($tSessionID)."
                                AND DOCTMP.FTWrnPdtCode = ".$this->db->escape($tPDTCode)."
                                ORDER BY FNWrnSeq DESC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->result_array();
            $nSeq       = $aResult[0]['FNWrnSeq'] + 1;
        } else {
            $nSeq       =  1;
        }

        // เพิ่มแถวใหม่
        $aDataInsert    = array(
            'FNWrnSeq'          => $nSeq,
            'FNPcdSeqNo'        => $paDataPdtParams['FNPcdSeqNo'],
            'FTXthDocNo'        => $tDocNo,
            'FTPdtCode'         => $tPDTCode,
            'FTWrnPdtCode'      => $tPDTCode,
            'FCWrnPercent'      => $paDataPdtParams['FCWrnPercent'],
            'FCWrnDNCNAmt'      => $paDataPdtParams['FCWrnDNCNAmt'],
            'FCWrnPdtQty'       => $paDataPdtParams['FCWrnPdtQty'],
            'FTWrnRmk'          => $paDataPdtParams['FTWrnRmk'],
            'FTSplCode'         => $paDataPdtParams['FTSplCode'],
            'FTXthDocKey'       => $tDocKey,
            'FTPcdRefTwo'       => $paDataPdtParams['FTPcdRefTwo'],
            'FTWrnRefDoc'       => $paDataPdtParams['FTWrnRefDoc'],
            'FTWrnUsrCode'      => $paDataPdtParams['FTWrnUsrCode'],
            'FDWrnDate'         => $paDataPdtParams['FDWrnDate'],
            'FTSessionID'       => $tSessionID,
            'FDLastUpdOn'       => date('Y-m-d h:i:s'),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date('Y-m-d h:i:s'),
            'FTCreateBy'        => $this->session->userdata('tSesUsername')
        );
        $this->db->insert('TCNTPdtClaimDTTmp', $aDataInsert);

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
        return $aStatus;
    }

    //Get ข้อมูลใน Temp
    public function FSaMIVBListStep3($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nSeqPDT            = $paDataWhere['nSeqPDT'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FNPcdSeqNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDWrnDate,23) AS ptFDWrnDate,
                                        PDTL.FTPdtCode AS PDTCode,
                                        PDTL.FTPdtName AS PDTName
                                    FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                    LEFT JOIN TCNMPdt_L PDTL ON DOCTMP.FTWrnPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = ".$this->db->escape($nLngID)."
                                    WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                    AND DOCTMP.FNPcdSeqNo = ".$this->db->escape($nSeqPDT)."
                                    AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                    AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";
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
        return $aDataReturn;
    }

    //บันทึกข้อมูลในตารางจริง
    public function FSaMIVBMoveTempToDTInSaveStep3($paDataWhere)
    {

        $tBCHCode     = $paDataWhere['tBCHCode'];
        $tADCode      = $this->session->userdata('tSesUsrAgnCode');
        $tDocKey      = $paDataWhere['tDocKey'];
        $tDocNo       = $paDataWhere['tDocNo'];
        $tSessionID   = $paDataWhere['tSessionID'];
        $tTypePage    = $paDataWhere['tTypePage'];
        $nSeqNo       = $paDataWhere['nSeqNo'];

        if ($tTypePage == 'saveclaim') {

            //ลบข้อมูลก่อน
            $this->db->where_in('FNPcdSeqNo', $nSeqNo);
            $this->db->where_in('FTXthDocNo', $tDocNo);
            $this->db->delete('TCNTPdtClaimDTWrn');

            //TCNTPdtClaimDTWrn
            $tSQL   = "INSERT INTO TCNTPdtClaimDTWrn (
                FTXthDocNo , FNPcdSeqNo , FNWrnSeq ,
                FTSplCode , FTPcdRefTwo , FTWrnRefDoc ,
                FCWrnPercent , FCWrnDNCNAmt , FTWrnPdtCode ,
                FCWrnPdtQty , FTWrnRmk , FTWrnUsrCode ,
                FDWrnDate , FTAgnCode , FTBchCode
            ) ";
            $tSQL   .=  "SELECT
                DOCTMP.FTXthDocNo ,
                ".$this->db->escape($nSeqNo).",   --ลำดับรายการ
                DOCTMP.FNWrnSeq,    --ลำดับการบันทึกผลเคลม
                DOCTMP.FTSplCode ,
                DOCTMP.FTPcdRefTwo ,
                DOCTMP.FTWrnRefDoc ,
                DOCTMP.FCWrnPercent ,
                DOCTMP.FCWrnDNCNAmt ,
                DOCTMP.FTWrnPdtCode ,
                DOCTMP.FCWrnPdtQty ,
                DOCTMP.FTWrnRmk ,
                DOCTMP.FTWrnUsrCode ,
                DOCTMP.FDWrnDate,
                ".$this->db->escape($tADCode).",
                ".$this->db->escape($tBCHCode)."
                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                WHERE DOCTMP.FNPcdSeqNo = ".$this->db->escape($nSeqNo)."
                AND DOCTMP.FTXthDocNo   = ".$this->db->escape($tDocNo)."
                AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                AND DOCTMP.FTSessionID  = ".$this->db->escape($tSessionID)."
                ORDER BY DOCTMP.FNPcdSeqNo ASC";
            $this->db->query($tSQL);
        } else if ($tTypePage == 'saveget') {

            //สามารถรับสินค้าได้ที่ละชิ้น
            //หา Seq DNCN 
            $tSPLCode   = $paDataWhere['tSPLCode'];

            $tSQL       = " SELECT DOCTMP.FNRcvSeq FROM TCNTPdtClaimDTRcv DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                            AND DOCTMP.FNPcdSeqNo = ".$this->db->escape($nSeqNo)." ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aResult    = $oQuery->result_array();
                $nSeqRcv    = $aResult[0]['FNRcvSeq'] + 1;
            } else {
                $nSeqRcv    =  1;
            }

            //TCNTPdtClaimDTRcv เอาสินค้าเข้าระบบ
            $tSQL   = "INSERT INTO TCNTPdtClaimDTRcv (
                        FTXthDocNo , FNPcdSeqNo , FNWrnSeq ,
                        FNRcvSeq , FTSplCode , FTPcdRefTwo ,
                        FTRcvPdtCode , FCRcvPdtQty , FTRcvRmk ,
                        FDRcvDate , FTRcvUsrCode , FTRcvRefTwi ,
                        FDRcvRefDate , FTAgnCode , FTBchCode ) ";
            $tSQL   .=  "SELECT
                        DOCTMP.FTXthDocNo ,
                        DOCTMP.FNPcdSeqNo,   --ลำดับรายการ
                        DOCTMP.FNWrnSeq,    --ลำดับการบันทึกผลเคลม
                        ".$this->db->escape($nSeqRcv)." AS FNRcvSeq,
                        ".$this->db->escape($tSPLCode)." ,
                        DOCTMP.FTPcdRefTwo ,
                        CASE
                            WHEN ISNULL(DOCTMP.FTRcvPdtCode,'') = '' THEN DOCTMP.FTWrnPdtCode
                            ELSE DOCTMP.FTRcvPdtCode
                        END AS FTRcvPdtCode ,
                        CASE
                            WHEN ISNULL(DOCTMP.FCRcvPdtQty,0) = 0 THEN DOCTMP.FCWrnPdtQty
                            ELSE DOCTMP.FCRcvPdtQty
                        END AS FCRcvPdtQty ,
                        '' AS FTRcvRmk ,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDRcvDate ,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTRcvUsrCode ,
                        '' AS FTRcvRefTwi ,
                        '' AS FDRcvRefDate ,
                        ".$this->db->escape($tADCode).",
                        ".$this->db->escape($tBCHCode)."
                    FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                    AND DOCTMP.FTXthDocNo   = ".$this->db->escape($tDocNo)."
                    AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                    AND DOCTMP.FTSessionID  = ".$this->db->escape($tSessionID)."
                    AND DOCTMP.FNPcdSeqNo   = ".$this->db->escape($nSeqNo)."
                    ORDER BY DOCTMP.FNPcdSeqNo ASC";
            $this->db->query($tSQL);

            //อัพเดทข้อมูล
            $tSQL = "   UPDATE DOCTMP
                        SET 
                            DOCTMP.FNRcvSeq = RES.FNRcvSeq ,
                            DOCTMP.FTRcvPdtCode = RES.FTRcvPdtCode ,
                            DOCTMP.FCRcvPdtQty = RES.FCRcvPdtQty ,
                            DOCTMP.FDRcvDate = RES.FDRcvDate 
                        FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                        LEFT JOIN (
                            SELECT 
                                UPD.FTXthDocNo, 
                                UPD.FNPcdSeqNo ,
                                UPD.FNRcvSeq ,
                                UPD.FNWrnSeq ,
                                UPD.FCRcvPdtQty ,
                                UPD.FTRcvPdtCode ,
                                UPD.FDRcvDate
                            FROM TCNTPdtClaimDTRcv UPD WITH(NOLOCK)
                            WHERE UPD.FTXthDocNo = ".$this->db->escape($tDocNo)."
                        ) RES 
                        ON RES.FNWrnSeq = DOCTMP.FNWrnSeq
                        AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                        AND RES.FTXthDocNo = DOCTMP.FTXthDocNo ";
            $this->db->query($tSQL);
        }
    }

    //อัพเดท สินค้าที่รับ(step3) , จำนวนรับ(step3)
    public function FSaMIVBUpdateInlineDTTempStep3($paDataUpdateDT, $paDataWhere, $tTypeUpdate)
    {
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nSeq           = $paDataWhere['nSeq'];

        switch ($tTypeUpdate) {
            case 'Step3PDT':     //อัพเดทสินค้า
                $this->db->set('FTRcvPdtCode', $paDataUpdateDT['FTRcvPdtCode']);
                break;
            case 'Step3QTY':     //อัพเดทจำนวน
                $this->db->set('FCRcvPdtQty', $paDataUpdateDT['FCRcvPdtQty']);
                break;
            default:
                break;
        }

        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FNPcdSeqNo', $nSeq);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //อัพเดท เลขที่เอกสาร ใบรับเข้า ลงใน Temp
    public function FSaMIVBUpdateDocTWIInTemp($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tSesSessionID      = $this->session->userdata('tSesSessionID');

        //จำนวนลูกค้าที่รับแล้ว
        $tSQL = "UPDATE DOCTMP
                SET 
                    DOCTMP.FTRcvPdtCode = RES.FTRcvPdtCode ,
                    DOCTMP.FTRcvRefTwi = RES.FTRcvRefTwi ,
                    DOCTMP.FDRcvRefDate = RES.FDRcvRefDate 
                FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                LEFT JOIN (
                    SELECT 
                        FTRcvPdtCode ,
                        FTXthDocNo ,
                        FNPcdSeqNo ,
                        FNWrnSeq ,
                        FNRcvSeq ,
                        FTRcvRefTwi ,
                        FDRcvRefDate
                    FROM TCNTPdtClaimDTRcv WITH(NOLOCK)
                    WHERE FTXthDocNo = ".$this->db->escape($tDocNo)." AND
                    FTBchCode = ".$this->db->escape($tBCHCode)."
                ) RES 
                ON RES.FTXthDocNo = DOCTMP.FTXthDocNo
                AND RES.FNWrnSeq = DOCTMP.FNWrnSeq
                AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo 
                AND RES.FNRcvSeq = DOCTMP.FNRcvSeq
                WHERE DOCTMP.FTXthDocKey = 'InvoiceBillStep3' AND
                DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";
        $this->db->query($tSQL);
    }

    //--------------------------------------- STEP 4 --------------------------------------------//

    //ช้อมูลในตาราง
    public function FSaMIVBListTableStep4($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $tDocKey2           = $paDataWhere['tDocKey2'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT
                                    DOCTMP.FNPcdSeqNo,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTPcdBarCode,
                                    DOCTMP.FCPcdQty,
                                    DOCTMP.FTPcdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FDPcdDateReq,
                                    SPLL.FTSplName,
                                    SUM(TMPWrn.FCRcvPdtQty) AS SUMQTY,
                                    SUM(ISNULL(TMPWrn.FCRetPdtQty,0)) AS SUMRET
                                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTXthDocNo = TMPWrn.FTXthDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = ".$this->db->escape($tDocKey2)."
                                WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)."
                                GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        SPLL.FTSplName ";

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
        return $aDataReturn;
    }

    //รายละเอียดข้อมูลของสินค้าตัวที่กดดู หรือตัวที่กำลังจะกดบันทึก ตาม SEQ
    public function FSaMIVBGetItemClaimBySeqStep4($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $tDocKey2           = $paDataWhere['tDocKey2'];
        $nSeqPDT            = $paDataWhere['nSeqPDT'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');

        $tSQL               = " SELECT
                                    DOCTMP.FNPcdSeqNo,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTPcdBarCode,
                                    DOCTMP.FCPcdQty,
                                    DOCTMP.FTPcdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FDPcdDateReq,
                                    TMPRET.FNRcvSeq,
                                    TMPRET.FNWrnSeq,
                                    TMPRET.FCWrnPercent,
                                    TMPRET.FCWrnDNCNAmt,
                                    TMPRET.FDRetDate,
                                    TMPRET.FTRetRmk,
                                    TMPRET.FCRcvPdtQty AS SUMQTY,
                                    TMPRET.FCRetPdtQty AS SUMRET
                                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                LEFT JOIN TCNTPdtClaimDTTmp TMPRET ON DOCTMP.FTXthDocNo = TMPRET.FTXthDocNo AND DOCTMP.FNPcdSeqNo = TMPRET.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPRET.FTPdtCode AND TMPRET.FTXthDocKey = ".$this->db->escape($tDocKey2)."
                                WHERE ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                AND DOCTMP.FNPcdSeqNo = ".$this->db->escape($nSeqPDT)."
                                AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";
        /*GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        DOCTMP.FCWrnDNCNAmt,
                                        DOCTMP.FCWrnPercent,
                                        TMPRET.FDRetDate,
                                        TMPRET.FTRetRmk ";*/
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
        return $aDataReturn;
    }

    //อัพเดท อัพเดทวันที่(step4) , อัพเดทหมายเหตุ(step4)
    public function FSaMIVBUpdateInlineDTTempStep4($paDataUpdateDT, $paDataWhere, $tTypeUpdate)
    {
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $tDocKey        = $paDataWhere['tDocKey'];
        $nRcvSeq        = $paDataWhere['nRcvSeq'];
        $nWrnSeq        = $paDataWhere['nWrnSeq'];
        $nPcdSeq        = $paDataWhere['nPcdSeq'];

        switch ($tTypeUpdate) {
            case 'DateStep4':     //อัพเดทวันที่
                $this->db->set('FDRetDate', $paDataUpdateDT['FDRetDate']);
                break;
            case 'RmkStep4':     //อัพเดทหมายเหตุ
                $this->db->set('FTRetRmk', $paDataUpdateDT['FTRetRmk']);
                break;
            default:
                break;
        }

        $this->db->where('FNRcvSeq', $nRcvSeq);
        $this->db->where('FNWrnSeq', $nWrnSeq);
        $this->db->where('FNPcdSeqNo', $nPcdSeq);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTXthDocNo', $tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //บันทึกข้อมูลในตารางจริง
    public function FSaMIVBMoveTempToDTInSaveStep4($paDataWhere)
    {

        $tBCHCode     = $paDataWhere['tBCHCode'];
        $tADCode      = $this->session->userdata('tSesUsrAgnCode');
        $tDocKey      = $paDataWhere['tDocKey'];
        $tDocNo       = $paDataWhere['tDocNo'];
        $tSessionID   = $paDataWhere['tSessionID'];
        $nCreateCNDN  = $paDataWhere['nCreateCNDN'];
        $tCSTCode     = $paDataWhere['tCSTCode'];
        $nSeq         = $paDataWhere['nSeq'];

        //TCNTPdtClaimDTRet เอาสินค้าเข้าระบบ
        $tSQL   = "INSERT INTO TCNTPdtClaimDTRet (
                    FTXthDocNo , FNPcdSeqNo , FNWrnSeq ,  FNRcvSeq ,
                    FNRetSeq , FTCstCode , FCRetPdtQty ,
                    FTRetRmk , FTRetUsrCode , FDRetDate ,
                    FTRetStaGenCNDN ,
                    FTAgnCode , FTBchCode ) ";
        $tSQL   .=  "SELECT
                    DOCTMP.FTXthDocNo ,
                    ".$this->db->escape($nSeq)." AS FNPcdSeqNo ,
                    DOCTMP.FNWrnSeq ,
                    DOCTMP.FNRcvSeq ,
                    ROW_NUMBER() OVER(ORDER BY DOCTMP.FNRcvSeq ASC) AS FNRetSeq,
                    ".$this->db->escape($tCSTCode).",
                    CASE
                        WHEN ISNULL(DOCTMP.FCRetPdtQty,0) = 0 THEN DOCTMP.FCWrnPdtQty
                        ELSE DOCTMP.FCRetPdtQty
                    END AS FCRetPdtQty ,
                    DOCTMP.FTRetRmk,
                    CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTRetUsrCode ,
                    DOCTMP.FDRetDate ,
                    ".$this->db->escape($nCreateCNDN).",
                    ".$this->db->escape($tADCode).",
                    ".$this->db->escape($tBCHCode)."
                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                WHERE 1 = 1
                AND DOCTMP.FTXthDocNo   = ".$this->db->escape($tDocNo)."
                AND DOCTMP.FTXthDocKey  = ".$this->db->escape($tDocKey)."
                AND DOCTMP.FNPcdSeqNo   = ".$this->db->escape($nSeq)."
                AND DOCTMP.FTSessionID  = ".$this->db->escape($tSessionID)."
                ORDER BY DOCTMP.FNPcdSeqNo ASC";
        $this->db->query($tSQL);

        //จำนวนลูกค้าที่รับแล้ว
        $tSQL = "UPDATE DOCTMP
                SET 
                    DOCTMP.FCRetPdtQty = RES.FCRetPdtQty ,
                    DOCTMP.FTRetRmk = RES.FTRetRmk ,
                    DOCTMP.FDRetDate = RES.FDRetDate , 
                    DOCTMP.FTRetStaGenCNDN = RES.FTRetStaGenCNDN 
                FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                LEFT JOIN (
                    SELECT 
                        DTRet.FTXthDocNo , 
                        DTRet.FNPcdSeqNo ,
                        DTRet.FNWrnSeq ,
                        DTRet.FNRcvSeq ,
                        DTRet.FCRetPdtQty ,
                        DTRet.FTRetRmk ,
                        DTRet.FDRetDate ,
                        DTRet.FTRetStaGenCNDN 
                    FROM TCNTPdtClaimDTRet DTRet WITH(NOLOCK)
                    WHERE DTRet.FTXthDocNo = ".$this->db->escape($tDocNo)."
                ) RES 
            ON RES.FTXthDocNo = DOCTMP.FTXthDocNo
            AND RES.FNWrnSeq = DOCTMP.FNWrnSeq
            AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo 
            AND RES.FNRcvSeq = DOCTMP.FNRcvSeq ";
        $this->db->query($tSQL);
    }

    //Get ข้อมูล API
    public function FSxMIVBGetConfigAPI()
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

    //กดยืนยัน จะเอาสินค้าไปเช็ค API
    public function FSaMIVBGetPDTInTempToArray($ptDocumentNumber, $ptBchCode)
    {
        $tSessionID  = $this->session->userdata('tSesSessionID');

        //หาคลังขาย
        $tSQL       = " SELECT TOP 1 FTWahCode 
                        FROM TCNMWaHouse WHERE 
                        TCNMWaHouse.FTWahStaType ='1' AND 
                        TCNMWaHouse.FTBchCode = ".$this->db->escape($ptBchCode)."
                        ORDER BY FTWahCode DESC ";
        $oQuery     = $this->db->query($tSQL);
        $aItemWah   = $oQuery->result_array();
        $tItemWah   = $aItemWah[0]['FTWahCode'];

        //หาขาสินค้า
        $tSQL       = "SELECT  
                            TMP.FTPcdPdtPick        AS ptPdtCode,
                            '$ptBchCode'            AS ptBchCode,
                            '$tItemWah'             AS ptWahCode,
                            TMP.FCPcdQtyPick        AS pcQty
                         FROM TCNTPdtClaimDTTmp TMP
                         WHERE TMP.FTXthDocNo = ".$this->db->escape($ptDocumentNumber)." AND
                         TMP.FTPcdStaPick = 1 AND
                         TMP.FTSessionID = ".$this->db->escape($tSessionID)." ";
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

    //ค้นหาเอกสารตาม SPL
    public function FSnMIVBInvoiceBillEventFindBill($paDataDoc)
    {
        $tSqlWhere = "";
        /*จากวันที่ครบชำระ - ถึงวันที่ครบชำระ*/
        $tSearchDocDateFrom = $paDataDoc['FDXphDueDateFrm'];
        $tSearchDocDateTo   = $paDataDoc['FDXphDueDateTo'];
        $tTypeIn            = $paDataDoc['tType'];
        $tDocType           = $paDataDoc['tDocType'];

        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSqlWhere .= " AND ((SPL.FDXphDueDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (SPL.FDXphDueDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        /*เลขที่เอกสาร*/
        $tSearchDocNo   = $paDataDoc['FTSearchXphDocNo'];
        if (!empty($tSearchDocNo)) {
            $tSqlWhere .= " AND DT.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchDocNo)."%'";
        }
        /*เอกสารอ้างอิง*/
        $tSearchDocRef   = $paDataDoc['FTSearchBill'];
        if (!empty($tSearchDocRef)) {
            $tSqlWhere .= " AND DTREF.FTXshRefDocNo LIKE '%".$this->db->escape_like_str($tSearchDocRef)."%'";
        }

        if ($tTypeIn == '1') {
            $tSqlWhere .= " AND ISNULL( PBDT.FTXpdRmk, '' ) = '' ";
        }else{
            $tSqlWhere .= " AND ISNULL( PBDT.FTXpdRmk, '' ) != '' ";
        }

        $tDataDocNo      = $paDataDoc['FTXphDocNo'];
        $tDataSplCode    = $paDataDoc['FTSplCode'];
        $tSessionID      = $paDataDoc['tSessionID'];
        $tBchCode        = $paDataDoc['FTBchCode'];
        $tDocKey         = "TACTPbDT";
        $this->db->trans_begin();

        //ลบ ใน Temp
        $this->db->where_in('FTXthDocNo', $tDataDocNo);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');

        if($tDocType == '1'){
            //ใบซื้อ
            $tSQL = "   INSERT INTO TCNTDocDTTmp (
                            FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
                            ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FCDateTimeInputForADJSTKVD
                        )
                        SELECT 
                            DISTINCT
                                DT.FTBchCode
                            ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
                            ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
                            ,DT.FTXphDocNo
                            ,DT.FDXphDocDate
                            ,DTREF.FTXshRefDocNo
                            ,SPL.FDXphDueDate
                            ,DT.FCXphAmtV
                            ,DT.FCXphPaid
                            ,DT.FCXphLeft
                            ,".$this->db->escape($tSessionID)."		AS FTSessionID
                            ,DT.FDLastUpdOn
                            ,DT.FDCreateOn
                            ,DT.FTLastUpdBy
                            ,DT.FTCreateBy
                            ,'IV'
                            ,DTREF.FDXshRefDocDate ";
            $tSQL .= "  FROM TAPTPiHD DT WITH(NOLOCK)
                        LEFT JOIN TAPTPiHDDocRef DTREF ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                        LEFT JOIN TAPTPiHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
                        LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                        LEFT JOIN TAPTDoHDDocRef DOREF ON DOREF.FTXshRefDocNo = DT.FTXphDocNo 
                        LEFT JOIN TAPTDoHD DOHD ON DOREF.FTXshDocNo = DOHD.FTXphDocNo ";
            $tSQL .= "  WHERE 1=1
                        AND ISNULL(FTXphStaPaid,'') != '3' 
                        AND ISNULL(DT.FTXphStaApv,'') = '1' 
                        AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
                        AND ( DOHD.FTBchCode = ".$this->db->escape($tBchCode)." OR DT.FTBchCode = ".$this->db->escape($tBchCode).")
            $tSqlWhere ";
        }elseif($tDocType == '2'){
            //ใบลดหนี้
            $tSQL = "   INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
               ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FCDateTimeInputForADJSTKVD
           )
           SELECT 
               DISTINCT
                DT.FTBchCode
               ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
               ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
               ,DT.FTXphDocNo
               ,DT.FDXphDocDate
               ,DTREF.FTXshRefDocNo
               ,SPL.FDXphDueDate
               ,DT.FCXphAmtV
               ,DT.FCXphPaid
               ,DT.FCXphLeft
               ,".$this->db->escape($tSessionID)."		AS FTSessionID
               ,DT.FDLastUpdOn
               ,DT.FDCreateOn
               ,DT.FTLastUpdBy
               ,DT.FTCreateBy
               ,'PC'
               ,DTREF.FDXshRefDocDate
               ";

            $tSQL .= "FROM TAPTPcHD DT WITH ( NOLOCK )
            LEFT JOIN TAPTPcHDDocRef DTREF ON DT.FTXphDocNo = DTREF.FTXshDocNo 
            AND DTREF.FTXshRefType = '3'
            LEFT JOIN TAPTPcHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
            LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo";
            $tSQL .= " WHERE 1=1
            AND ISNULL(FTXphStaPaid,'') != '3' 
            AND ISNULL(DT.FTXphStaApv,'') = '1' 
            AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
            $tSqlWhere";
        }elseif($tDocType == '3'){
            //ใบเพิ่มหนี้
            $tSQL = "   INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
               ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FCDateTimeInputForADJSTKVD
           )
           SELECT 
               DISTINCT
                DT.FTBchCode
               ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
               ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
               ,DT.FTXphDocNo
               ,DT.FDXphDocDate
               ,''                                      AS FTXshRefDocNo
               ,SPL.FDXphDueDate
               ,DT.FCXphAmtV
               ,DT.FCXphPaid
               ,DT.FCXphLeft
               ,".$this->db->escape($tSessionID)."		AS FTSessionID
               ,DT.FDLastUpdOn
               ,DT.FDCreateOn
               ,DT.FTLastUpdBy
               ,DT.FTCreateBy
               ,'PC'
               ,''
               ";
               
            $tSQL .= "FROM TAPTPdHD DT WITH ( NOLOCK )
            LEFT JOIN TAPTPdHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
            LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo";
            $tSQL .= " WHERE 1=1
            AND ISNULL(FTXphStaPaid,'') != '3' 
            AND ISNULL(DT.FTXphStaApv,'') = '1' 
            AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
            $tSqlWhere";
            
        }else{
            //ทั้งหมด
            $tSQL = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
                        ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FCDateTimeInputForADJSTKVD 
                     )

                    SELECT 
                        DISTINCT
                        DT.FTBchCode
                        ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
                        ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
                        ,DT.FTXphDocNo
                        ,DT.FDXphDocDate
                        ,DTREF.FTXshRefDocNo
                        ,SPL.FDXphDueDate
                        ,DT.FCXphAmtV
                        ,DT.FCXphPaid
                        ,DT.FCXphLeft
                        ,".$this->db->escape($tSessionID)."		AS FTSessionID
                        ,DT.FDLastUpdOn
                        ,DT.FDCreateOn
                        ,DT.FTLastUpdBy
                        ,DT.FTCreateBy
                        ,'IV'
                        ,DTREF.FDXshRefDocDate";
            $tSQL .= "  FROM TAPTPiHD DT WITH(NOLOCK)
                        LEFT JOIN TAPTPiHDDocRef DTREF ON DT.FTXphDocNo = DTREF.FTXshDocNo AND DTREF.FTXshRefType = '3'
                        LEFT JOIN TAPTPiHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
                        LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo
                        LEFT JOIN TAPTDoHDDocRef DOREF ON DOREF.FTXshRefDocNo = DT.FTXphDocNo 
                        LEFT JOIN TAPTDoHD DOHD ON DOREF.FTXshDocNo = DOHD.FTXphDocNo ";
            $tSQL .= "  WHERE 1=1
                        AND ISNULL(FTXphStaPaid,'') != '3' 
                        AND ISNULL(DT.FTXphStaApv,'') = '1' 
                        AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
                        AND (DOHD.FTBchCode = ".$this->db->escape($tBchCode)." OR DT.FTBchCode = ".$this->db->escape($tBchCode).")
                        $tSqlWhere ";

            $tSQL .= " UNION ALL " ;

            $tSQL .= " SELECT 
                        DISTINCT
                        DT.FTBchCode
                        ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
                        ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
                        ,DT.FTXphDocNo
                        ,DT.FDXphDocDate
                        ,DTREF.FTXshRefDocNo
                        ,SPL.FDXphDueDate
                        ,DT.FCXphAmtV
                        ,DT.FCXphPaid
                        ,DT.FCXphLeft
                        ,".$this->db->escape($tSessionID)."		AS FTSessionID
                        ,DT.FDLastUpdOn
                        ,DT.FDCreateOn
                        ,DT.FTLastUpdBy
                        ,DT.FTCreateBy
                        ,'PC'
                        ,DTREF.FDXshRefDocDate ";
            $tSQL .= "  FROM TAPTPcHD DT WITH ( NOLOCK )
                        LEFT JOIN TAPTPcHDDocRef DTREF ON DT.FTXphDocNo = DTREF.FTXshDocNo 
                        AND DTREF.FTXshRefType = '3'
                        LEFT JOIN TAPTPcHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
                        LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo";
            $tSQL .= "  WHERE 1=1
                        AND ISNULL(FTXphStaPaid,'') != '3' 
                        AND ISNULL(DT.FTXphStaApv,'') = '1' 
                        AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)."
                        $tSqlWhere ";

            $tSQL .= " UNION ALL " ;

            $tSQL .= "SELECT 
                        DISTINCT
                        DT.FTBchCode
                        ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
                        ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
                        ,DT.FTXphDocNo
                        ,DT.FDXphDocDate
                        ,''                                      AS FTXshRefDocNo
                        ,SPL.FDXphDueDate
                        ,DT.FCXphAmtV
                        ,DT.FCXphPaid
                        ,DT.FCXphLeft
                        ,".$this->db->escape($tSessionID)."		AS FTSessionID
                        ,DT.FDLastUpdOn
                        ,DT.FDCreateOn
                        ,DT.FTLastUpdBy
                        ,DT.FTCreateBy
                        ,'PD'
                        ,'' ";
            $tSQL .= "  FROM TAPTPdHD DT WITH ( NOLOCK )
                        LEFT JOIN TAPTPdHDSpl SPL ON DT.FTXphDocNo = SPL.FTXphDocNo
                        LEFT JOIN TACTPbDT PBDT ON PBDT.FTXpdRefDocNo = DT.FTXphDocNo";
            $tSQL .= "  WHERE 1=1
                        AND ISNULL(FTXphStaPaid,'') != '3' 
                        AND ISNULL(DT.FTXphStaApv,'') = '1' 
                        AND DT.FTSplCode = ".$this->db->escape($tDataSplCode)." 
                        $tSqlWhere ";

            $tSQL .= " ORDER BY DTREF.FTXshRefDocNo";
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

    //Get ข้อมูลใน Temp
    public function FSaMIVBListPoint2($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $tPdtCode           = $paDataWhere['tPdtCode'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        

        $tWhereinpdt = "";
        foreach($tPdtCode AS $aValPdt){
            $tWhereinpdt .= "'".trim($aValPdt)."',";
        }
        $tWhereinpdt   = substr($tWhereinpdt,0,-1);
        //ลบ ใน Temp Step2
        $this->db->where_in('FTXthDocNo', $tDocNo);
        $this->db->where_in('FTSessionID', $tSesSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');

        $tSQLInsert = "   INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
            ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode
        )
        SELECT 
            DT.FTBchCode
            ,FTXthDocNo
            ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
            ,DT.FTPdtCode
            ,DT.FDAjdDateTimeC2
            ,DT.FTXtdDocNoRef
            ,DT.FDAjdDateTimeC1
            ,DT.FCXtdAmt
            ,DT.FCXtdSetPrice
            ,DT.FCXtdVatable
            ,DT.FTSessionID
            ,DT.FDLastUpdOn
            ,DT.FDCreateOn
            ,DT.FTLastUpdBy
            ,DT.FTCreateBy
            ,DT.FTSrnCode
        FROM TCNTDocDTTmp DT WITH(NOLOCK)
        WHERE FTSessionID = ".$this->db->escape($tSesSessionID)." 
        AND FTXthDocNo = ".$this->db->escape($tDocNo)." 
        AND FTXthDocKey = 'TACTPbDT'
        AND FTPdtCode IN ($tWhereinpdt);
        ";
        $this->db->query($tSQLInsert);
        
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FTXtdDocNoRef ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet,
                                        CONVERT(CHAR(10),DOCTMP.FCDateTimeInputForADJSTKVD,23) AS DateRefDoc
                                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                    WHERE DOCTMP.FTSessionID != ''
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                    AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                    AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";

        $tSQL               .= ") Base) AS c ";
        // echo $tSQL;
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
        return $aDataReturn;
    }


    //Get Spl Address
    public function FSaMIVBGetSplAddress($ptHDSplCode = '', $pnLangID = '')
    {
        $nAddressVersion = FCNaHAddressFormat('TCNMSpl');

        if ($ptHDSplCode == "") {
            $aDataReturn = array();
        } else {
            $tSQL   = "SELECT TOP 1 CAD.FTSplCode, 
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
                $aDataReturn        = $oQuery->result_array();
            }
        }
        return $aDataReturn;
    }

    //อนุมัตเอกสาร
    public function FSaMIVBApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXphStaApv',$paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode',$paDataUpdate['FTXphApvCode']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TACTPbHD');

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

    //อนุมัตเอกสาร
    public function FSaMIVBChangeStatusDTDocument($paDataUpdate){

        $this->db->set('FTXpdRmk','1');
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TACTPbDT');

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

    //หาชื่อผู้ติดต่อมา default
    public function FSaMIVBFindContact($ptSPLCode){
        $tSQL   = "SELECT TOP 1 FNCtrSeq , FTCtrName FROM TCNMSplContact_L WHERE FTSplCode = ".$this->db->escape($ptSPLCode)." ";
        $oQuery = $this->db->query($tSQL);
        $aFind  = $oQuery->result_array();
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'rtCode'        => '1',
                'rtResult'      => $aFind[0],
                'rtDesc'        => 'Find',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found.',
            );
        }
        return $aResult;
    }
}
