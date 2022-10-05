<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoicecustomerbill_model extends CI_Model
{

    //Datatable
    public function FSaMIVCList($paData)
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
                                SPLL.FTCstName,
                                HD.FTXphStaDoc,
                                HD.FTXphStaApv,
                                HD.FTXphRmk,
                                USRL.FTUsrName  AS FTCreateByName,
                                HD.FDCreateOn
                            FROM TACTSBHD HD WITH (NOLOCK)
                            LEFT JOIN TACTSBHDCst       HDSPL       WITH (NOLOCK) ON HD.FTBchCode = HDSPL.FTBchCode AND HDSPL.FTXphDocNo = HD.FTXphDocNo
                            LEFT JOIN TCNMBranch_L      BCHL        WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)." 
                            LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK) ON HD.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)." 
                            LEFT JOIN TCNMCST_L         SPLL        WITH (NOLOCK) ON HD.FTCstCode = SPLL.FTCstCode AND SPLL.FNLngID = ".$this->db->escape($nLngID)."
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
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if($tSearchStaDoc == '1'){
                $tSQLMain .= " AND HD.FTXphStaApv = ".$this->db->escape($tSearchStaDoc)." ";
            }elseif($tSearchStaDoc == '2'){
                $tSQLMain .= " AND HD.FTXphStaDoc = '1' AND ISNULL(HD.FTXphStaApv,'') = '' ";
            }else{
                $tSQLMain .= " AND HD.FTXphStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
            }
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

        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //ลบข้อมูล
    public function FSnMIVCDelDocument($paDataDoc)
    {
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTSBHD');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTSBHDCst');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTSBHDDocRef');

        $this->db->where_in('FTXphDocNo', $tDataDocNo);
        $this->db->delete('TACTSBDT');

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
    public function FSaMIVCDeletePDTInTmp($tDocno = '')
    {
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTSBDT');
        $this->db->where_in('FTXthDocNo', $tDocno);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', 'TACTSBDTStep2');
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
    public function FSaMIVCGetDataPdt($paDataPdtParams)
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
    public function FSnMIVCInvoiceCustomerBillEventCancel($paDataDoc)
    {
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->set('FTXphStaDoc', '3');
        $this->db->where('FTXphDocNo', $tDataDocNo);
        $this->db->update('TACTSBHD');

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
    public function FSxMIVCAddUpdateHD($paDataMaster, $paDataWhere, $paTableAddUpdate)
    {
        $aDataGetDataHD     =   $this->FSaMIVCGetDataDocHD(array(
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
    public function FSxMIVCAddUpdateSPLHD($paDataCSTHD, $paDataWhere, $paTableAddUpdate)
    {
        $aDataGetDataSPLHD    =   $this->FSaMIVCGetDataDocCSTHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode'],
            'FTCstCode'     => $paDataWhere['FTCstCode'],
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
    public function FSxMIVCAddUpdateDocNoToTemp($paDataWhere)
    {

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', 'TACTSBDT');
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', 'TACTSBDTStep2');
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));
        return;
    }

    //ข้อมูล DT
    public function FSaMIVCMoveDTTmpToDT($paDataWhere, $paTableAddUpdate ,$paDataMaster)
    {
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tSPLCode     = $paDataWhere['FTSplCode'];
        $tDocKey      = 'TACTSBDTStep2';
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
                            CASE
                                    WHEN DOCTMP.FCXtdVatable > 0
                                    THEN DOCTMP.FCXtdVatable
                                    ELSE DOCTMP.FCXtdAmt
                            END as FCXpdInvLeft ,
                            DOCTMP.FCXtdSetPrice AS FCXpdInvPaid ,
                            CASE
                                    WHEN DOCTMP.FCXtdVatable > 0
                                    THEN ( DOCTMP.FCXtdVatable - DOCTMP.FCXtdSetPrice )
                                    ELSE DOCTMP.FCXtdAmt
                            END as FCXpdInvRem ,
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

    //--------------------------------------- เข้าหน้าแก้ไข --------------------------------------------//

    //ข้อมูล HD
    public function FSaMIVCGetDataDocHD($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            PBHD.*,
                            BCHL.FTBchName,
                            USRL.FTUsrName,
                            APV.FTUsrName AS FTXphApvName,
                            SPL_L.FTSplName ,
                            HDCST.FTXphCstRef AS CstRef
                        FROM TACTSBHD         PBHD  WITH (NOLOCK)
                        LEFT JOIN TACTSBHDCst       HDCST  WITH (NOLOCK)   ON PBHD.FTXphDocNo = HDCST.FTXphDocNo AND PBHD.FTBchCode = HDCST.FTBchCode
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

    //ข้อมูล CST
    public function FSaMIVCGetDataDocCSTHD($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $tCstNo     = $paDataWhere['FTCstCode'];
        $tBchNo     = $paDataWhere['FTBchCode'];
        $pnLangID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');

        $tSQL       = " SELECT TOP 1 AFinal.*
                            FROM
                            (
                                SELECT A1.*
                                FROM
                                (
                                    SELECT  TOP 1 
                                            HDCST.FTAgnCode,
                                            HDCST.FTBchCode,
                                            HDCST.FTXphDocNo,
                                            HDCST.FTXphDstPaid,
                                            HDCST.FTXphCshOrCrd,
                                            HDCST.FNXphCrTerm,
                                            HDCST.FTXphCtrName,
                                            HDCST.FDXphTnfDate,
                                            HDCST.FTXphRefTnfID,
                                            HDCST.FTXphRefVehID,
                                            HDCST.FTXphRefInvNo,
                                            HDCST.FTXphQtyAndTypeUnit,
                                            HDCST.FNXphShipAdd,
                                            HDCST.FNXphTaxAdd,
                                            HDCST.FTXphCtrName AS CtrName,
                                            HDCST.FTXphCstRef,
                                            CSTB.FTCbrBchName,
                                            CAD.FTCstCode,
                                            Cst_L.FTCstName,
                                            CstCD.FNCstCrTerm,
                                            CstCD.FCCstCrLimit,
                                            CstCD.FTCstTspPaid,
                                            CAD.FTAddRefNo,
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
                                            Cst.FTCstTel, 
                                            Cst.FTCstEmail, 
                                            CAD.FTAddFax, 
                                            2 AS FNAddPriority
                                    FROM TCNMCstAddress_L CAD
                                    INNER JOIN TACTSBHD SBHD WITH(NOLOCK) ON CAD.FTCstCode = SBHD.FTCstCode AND SBHD.FTXphDocNo = ".$this->db->escape($tDocNo)."
                                    INNER JOIN TACTSBHDCst HDCST WITH(NOLOCK) ON HDCST.FTXphDocNo = SBHD.FTXphDocNo AND HDCST.FTXphDocNo = ".$this->db->escape($tDocNo)."
                                    LEFT JOIN TCNMCst Cst WITH(NOLOCK) ON Cst.FTCstCode = CAD.FTCstCode 
                                    LEFT JOIN TCNMCst_L Cst_L WITH(NOLOCK) ON Cst_L.FTCstCode = CAD.FTCstCode 
                                    LEFT JOIN TCNMCstBch CSTB WITH (NOLOCK) ON SBHD.FTCstCode = CSTB.FTCstCode AND HDCST.FTXphCstRef = CSTB.FTCbrBchCode
                                    LEFT JOIN TCNMCstCredit CstCD WITH(NOLOCK) ON CstCD.FTCstCode = CAD.FTCstCode
                                    LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                                    LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                                    LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                                    WHERE CAD.FTCstCode = ".$this->db->escape($tCstNo)."
                                        AND CAD.FTAddGrpType = '4'
                                        AND CAD.FTAddRefNo = ".$this->db->escape($tBchNo)."
                                    ORDER BY CAD.FNAddSeqNo DESC
                                ) A1
                                UNION ALL
                                SELECT  TOP 1 
                                        HDCST.FTAgnCode,
                                        HDCST.FTBchCode,
                                        HDCST.FTXphDocNo,
                                        HDCST.FTXphDstPaid,
                                        HDCST.FTXphCshOrCrd,
                                        HDCST.FNXphCrTerm,
                                        HDCST.FTXphCtrName,
                                        HDCST.FDXphTnfDate,
                                        HDCST.FTXphRefTnfID,
                                        HDCST.FTXphRefVehID,
                                        HDCST.FTXphRefInvNo,
                                        HDCST.FTXphQtyAndTypeUnit,
                                        HDCST.FNXphShipAdd,
                                        HDCST.FNXphTaxAdd,
                                        HDCST.FTXphCtrName AS CtrName,
                                        HDCST.FTXphCstRef,
                                        CSTB.FTCbrBchName,
                                        CAD.FTCstCode,
                                        Cst_L.FTCstName,
                                        CstCD.FNCstCrTerm,
                                        CstCD.FCCstCrLimit,
                                        CstCD.FTCstTspPaid,
                                        CAD.FTAddRefNo,
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
                                        Cst.FTCstTel, 
                                        Cst.FTCstEmail, 
                                        CAD.FTAddFax,
                                        1 AS FNAddPriority
                                FROM TCNMCstAddress_L CAD
                                INNER JOIN TACTSBHD SBHD WITH(NOLOCK) ON CAD.FTCstCode = SBHD.FTCstCode AND SBHD.FTXphDocNo = ".$this->db->escape($tDocNo)."
                                INNER JOIN TACTSBHDCst HDCST WITH(NOLOCK) ON HDCST.FTXphDocNo = SBHD.FTXphDocNo AND HDCST.FTXphDocNo = ".$this->db->escape($tDocNo)."
                                LEFT JOIN TCNMCst Cst WITH(NOLOCK) ON Cst.FTCstCode = CAD.FTCstCode 
                                LEFT JOIN TCNMCst_L Cst_L WITH(NOLOCK) ON Cst_L.FTCstCode = CAD.FTCstCode 
                                LEFT JOIN TCNMCstBch CSTB WITH (NOLOCK) ON SBHD.FTCstCode = CSTB.FTCstCode AND HDCST.FTXphCstRef = CSTB.FTCbrBchCode
                                LEFT JOIN TCNMCstCredit CstCD WITH(NOLOCK) ON CstCD.FTCstCode = CAD.FTCstCode
                                LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                                LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                                LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                                WHERE CAD.FTCstCode = ".$this->db->escape($tCstNo)."
                                    AND CAD.FTAddGrpType = 1
                                ORDER BY CAD.FNAddSeqNo DESC
                            ) AFinal
                            ORDER BY FNAddPriority DESC;";
        $oQuery = $this->db->query($tSQL);
        // echo $tSQL;
        // exit;

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
    public function FSaMIVCGetDataDocHDDocRef($paDataWhere)
    {
        $tDocNo     = $paDataWhere['FTXphDocNo'];

        $tSQL       = " SELECT
                            TOP 2
                            HDREF.FTXphRefType,
                            HDREF.FTXphDocNo,
                            HDREF.FDXphRefDocDate
                        FROM TACTSBHDDocRef   HDREF  WITH (NOLOCK)
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
    public function FSxMIVCMoveDTToDTTemp($paDataWhere)
    {
        $tDocNo         = $paDataWhere['FTXphDocNo'];
        $tSplCode       = $paDataWhere['FTCstCode'];
        $tDocKey        = 'TACTSBDT';
        $tDocKey2       = 'TACTSBDTStep2';

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
                        FROM TACTSBDT PBDT WITH (NOLOCK)
                        WHERE PBDT.FTXphDocNo   = ".$this->db->escape($tDocNo)."
                        ORDER BY PBDT.FTXphDocNo ASC";
        $this->db->query($tSQL);

        return;
    }

    //--------------------------------------- อ้างอิงเอกสารภายใน --------------------------------------------//

    // Function: Get Data ใบขาย HD List
    public function FSoMIVCCallRefIntDocDataTable($paDataCondition)
    {
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'], $paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tIVCRefIntBchCode        = $aAdvanceSearch['tIVCRefIntBchCode'];
        $tIVCRefIntDocNo          = $aAdvanceSearch['tIVCRefIntDocNo'];
        $tIVCRefIntDocDateFrm     = $aAdvanceSearch['tIVCRefIntDocDateFrm'];
        $tIVCRefIntDocDateTo      = $aAdvanceSearch['tIVCRefIntDocDateTo'];
        $tIVCRefIntStaDoc         = $aAdvanceSearch['tIVCRefIntStaDoc'];

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

        if (isset($tIVCRefIntBchCode) && !empty($tIVCRefIntBchCode)) {
            $tSQLMain .= " AND (SALHD.FTBchCode = '%".$this->db->escape_like_str($tIVCRefIntBchCode)."%')";
        }

        if (isset($tIVCRefIntDocNo) && !empty($tIVCRefIntDocNo)) {
            $tSQLMain .= " AND (SALHD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tIVCRefIntDocNo)."%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if (!empty($tIVCRefIntDocDateFrm) && !empty($tIVCRefIntDocDateTo)) {
            $tSQLMain .= " AND ((SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tIVCRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tIVCRefIntDocDateTo 23:59:59')) OR (SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tIVCRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tIVCRefIntDocDateFrm 00:00:00')))";
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
    public function FSoMIVCCallRefIntDocDTDataTable($paData)
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
    public function FSoMIVCCallRefIntDocInsertDTToTemp($paData)
    {

        $tIVCDocNo        = $paData['tIVCDocNo'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey', $paData['tDocKey']);
        $this->db->where('FTXthDocNo', $tIVCDocNo);
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
                    ".$this->db->escape($tIVCDocNo)." as FTXthDocNo,
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
    public function FSxMIVCUpdateRef($ptTableName, $paParam)
    {
        $nChkDataDocRef  = $this->FSaMIVCChkRefDupicate($ptTableName, $paParam);
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
    public function FSaMIVCChkRefDupicate($ptTableName, $paParam)
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
    public function FSoMIVCCallRefIntDocFindCstAndCar($paData)
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
                    LEFT JOIN TCNMCstAddress_L ADDL ON HD.FTCstCode = ADDL.FTCstCode AND CAD.FNLngID =  1
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
    public function FSaMIVCListStep1Point1($paDataWhere)
    {
        $tDocNo             = $paDataWhere['tDocNo'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FTXthDocNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet
                                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                    WHERE 1 = 1
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                                    AND DOCTMP.FTXthDocKey = ".$this->db->escape($tDocKey)."
                                    AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";

        $tSQL               .= ") Base) AS c ORDER BY c.rtRowID DESC";
        $oQuery = $this->db->query($tSQL);


        $tSQL2      = " SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FTPdtCode ASC) AS rtRowID,* FROM (
                        SELECT
                            DOCTMP.FTPdtCode
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND ISNULL(DOCTMP.FTXthDocNo,'')  = ".$this->db->escape($tDocNo)."
                        AND DOCTMP.FTXthDocKey = 'TACTSBDTStep2'
                        AND DOCTMP.FTSessionID = ".$this->db->escape($tSesSessionID)." ";

        $tSQL2               .= ") Base) AS c ORDER BY c.rtRowID DESC";
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
    public function FSaMIVCPDTFindBySPL($paDataWhere)
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
    public function FSaMIVCInsertPDTToTemp($paDataPdtMaster, $paDataPdtParams)
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

    //ยกเลิกเอกสาร
    public function FSnMIVCInvoiceCustomerBillEventFindBill($paDataDoc)
    {
        $tSqlWhere = "";
        $tSqlWhereCstBch = "";
        /*จากวันที่ครบชำระ - ถึงวันที่ครบชำระ*/
        $tSearchDocDateFrom = $paDataDoc['FDXphDueDateFrm'];
        $tSearchDocDateTo   = $paDataDoc['FDXphDueDateTo'];
        $tTypeIn            = $paDataDoc['tType'];
        $tDocType           = $paDataDoc['tDocType'];

        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSqlWhere .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        /*เลขที่เอกสาร*/
        $tSearchDocNo   = $paDataDoc['FTSearchXphDocNo'];
        $tSearchDocRef   = $paDataDoc['FTSearchBill'];
        if (!empty($tSearchDocNo)) {
            if(!empty($tSearchDocRef)){
                $tSqlWhere .= " AND (HD.FTXshDocNo BETWEEN ".$this->db->escape($tSearchDocNo)." AND ".$this->db->escape($tSearchDocRef)." ) OR (HD.FTXshDocNo BETWEEN ".$this->db->escape($tSearchDocRef)."  AND ".$this->db->escape($tSearchDocNo)." )";
            }else{
                $tSqlWhere .= " AND HD.FTXshDocNo LIKE '%".$this->db->escape_like_str($tSearchDocNo)."%'";
            }
        }

        /*สาขา*/
        $tSearchBchFrm   = $paDataDoc['FDXphBchFrm'];
        $tSearchBchTo   = $paDataDoc['FDXphBchTo'];
        if (!empty($tSearchBchFrm)) {
            if(!empty($tSearchBchTo)){
                $tSqlWhere .= " AND (HD.FTBchCode BETWEEN ".$this->db->escape($tSearchBchFrm)." AND ".$this->db->escape($tSearchBchTo)." )";
            }else{
                $tSqlWhere .= " AND HD.FTBchCode LIKE '%".$this->db->escape_like_str($tSearchBchFrm)."%'";
            }
        }
        // print_r($paDataDoc);

        /*เอกสารอ้างอิง*/
        // $tSearchDocRef   = $paDataDoc['FTSearchBill'];
        // if (!empty($tSearchDocRef)) {
        //     $tSqlWhere .= " AND DTREF.FTXshRefDocNo LIKE '%".$this->db->escape_like_str($tSearchDocRef)."%'";
        // }

        if ($tTypeIn == '1') {
            $tSqlWhere .= " AND ISNULL( PBDT.FTXpdRmk, '' ) = '' ";
        }else{
            $tSqlWhere .= " AND ISNULL( PBDT.FTXpdRmk, '' ) != '' ";
        }

        $tDataDocNo      = $paDataDoc['FTXphDocNo'];
        $tDataCstCode    = $paDataDoc['FTSplCode'];
        $tSessionID      = $paDataDoc['tSessionID'];
        $tBchCode        = $paDataDoc['FTBchCode'];
        $tCstBchCode     = $paDataDoc['tCstBchCode'];

        if (!empty($tCstBchCode) && $tCstBchCode != '') {
            $tSqlWhereCstBch = "AND CST.FTXshCstRef = ".$this->db->escape($tCstBchCode)."";
        }
        
        $tDocKey         = "TACTSBDT";
        $this->db->trans_begin();

        //ลบ ใน Temp
        $this->db->where_in('FTXthDocNo', $tDataDocNo);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FTXthDocKey', $tDocKey);
        $this->db->delete('TCNTDocDTTmp');

        // if($tDocType == '1' || $tDocType == '0'){
            //ใบขายสินค้า
            $tSQL = "   INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FTXthDocKey,FTPdtCode,FDAjdDateTimeC2,FTXtdDocNoRef,FDAjdDateTimeC1,FCXtdAmt,FCXtdSetPrice,FCXtdVatable
               ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FTPgpChain,FTBchName
           )
           SELECT 
               DISTINCT
                HD.FTBchCode
               ,".$this->db->escape($tDataDocNo)."      AS FTXthDocNo
               ,".$this->db->escape($tDocKey)."         AS FTXthDocKey
               ,HD.FTXshDocNo
               ,HD.FDXshDocDate
               ,HDREF.FTXshDocNo
               ,HDREF.FDXshRefDocDate
               ,HD.FCXshAmtV
               ,HD.FCXshPaid
               ,HD.FCXshLeft
               ,".$this->db->escape($tSessionID)."		AS FTSessionID
               ,HD.FDLastUpdOn
               ,HD.FDCreateOn
               ,HD.FTLastUpdBy
               ,HD.FTCreateBy
               ,'Sal',
               CBCH.FTCbrBchName,
               BCHL.FTBchName
               ";

            $tSQL .= "FROM TPSTSalHD HD WITH(NOLOCK)
            
            OUTER APPLY
            (SELECT TOP 1 *
            FROM TPSTTaxHDDocRef a
            WHERE a.FTXshRefDocNo = HD.FTXshDocNo AND a.FTXshRefType = '1' AND a.FTXshRefKey = 'ABB'
            ORDER BY a.FDXshRefDocDate DESC
            ) AS HDREF

            LEFT JOIN TCNMBranch_L BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode
            LEFT JOIN TPSTSalHDCst CST WITH(NOLOCK) ON HD.FTXshDocNo = CST.FTXshDocNo AND HD.FTBchCode = CST.FTBchCode
            LEFT JOIN TACTSBDT PBDT WITH(NOLOCK) ON PBDT.FTXpdRefDocNo = HD.FTXshDocNo
            LEFT JOIN TSVMCar CAR WITH(NOLOCK) ON CAR.FTCarCode = CST.FTCarCode 	
            LEFT JOIN TCNMCstBch CBCH WITH(NOLOCK) ON CBCH.FTCbrBchCode = CAR.FTCbrBchCode AND CBCH.FTCstCode = HD.FTCstCode
              ";
            $tSQL .= " WHERE ISNULL(FTXshStaPaid,'') != '3' 
            AND FTXshStaRefund = '1'
            AND FNxshDocType != '9'
            AND ISNULL(HD.FTXshStaApv,'') = '1' 
            AND HD.FTCSTCode = ".$this->db->escape($tDataCstCode)."
            $tSqlWhereCstBch 
            $tSqlWhere";

        
        $this->db->query($tSQL);
        // echo $tSQL;
        // exit;

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
    public function FSaMIVCListPoint2($paDataWhere)
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
            ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FTSrnCode,FTPgpChain,FTBchName
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
            ,DT.FTPgpChain
            ,DT.FTBchName
        FROM TCNTDocDTTmp DT WITH(NOLOCK)
        WHERE FTSessionID = ".$this->db->escape($tSesSessionID)." 
        AND FTXthDocNo = ".$this->db->escape($tDocNo)." 
        AND FTXthDocKey = 'TACTSBDT'
        AND FTPdtCode IN ($tWhereinpdt);
        ";
        $this->db->query($tSQLInsert);
        
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FTXthDocNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC1,23) AS DateReq,
                                        CONVERT(CHAR(10),DOCTMP.FDAjdDateTimeC2,23) AS DateSplGet
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
    public function FSaMIVCGetCstAddress($ptHDCstCode = '', $ptHDCstBchCode = '', $pnLangID = '')
    {
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');

        if ($ptHDCstCode == "" || $ptHDCstBchCode == '') {
            $tSQL   = "SELECT  TOP 1 
                            CAD.FTCstCode, 
                            CstCD.FNCstCrTerm,
                            CstCD.FCCstCrLimit,
                            CstCD.FTCstTspPaid,
                            CAD.FTAddRefNo,
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
                            Cst.FTCstTel, 
                            Cst.FTCstEmail, 
                            CAD.FTAddFax,
                            1 AS FNAddPriority
                        FROM TCNMCstAddress_L CAD
                        LEFT JOIN TCNMCst Cst WITH(NOLOCK) ON Cst.FTCstCode = CAD.FTCstCode 
                        LEFT JOIN TCNMCstCredit CstCD WITH(NOLOCK) ON CstCD.FTCstCode = CAD.FTCstCode
                        LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                        LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                        LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                        WHERE CAD.FTCstCode = ".$this->db->escape($ptHDCstCode)." AND CAD.FTAddGrpType = 1
                        ORDER BY CAD.FNAddSeqNo DESC;
                    ";
            $oQuery = $this->db->query($tSQL);

            if (empty($oQuery->result_array())) {
                $aDataReturn = array();
            } else {
                $aDataReturn = $oQuery->result_array();
            }
        } else {
            $tSQL   = "SELECT TOP 1 AFinal.*
                            FROM
                            (
                                SELECT A1.*
                                FROM
                                (
                                    SELECT  TOP 1 
                                            CAD.FTCstCode,
                                            CstCD.FNCstCrTerm,
                                            CstCD.FCCstCrLimit,
                                            CstCD.FTCstTspPaid,
                                            CAD.FTAddRefNo,
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
                                            Cst.FTCstTel, 
                                            Cst.FTCstEmail, 
                                            CAD.FTAddFax, 
                                            2 AS FNAddPriority
                                    FROM TCNMCstAddress_L CAD
                                    LEFT JOIN TCNMCst Cst WITH(NOLOCK) ON Cst.FTCstCode = CAD.FTCstCode 
                                    LEFT JOIN TCNMCstCredit CstCD WITH(NOLOCK) ON CstCD.FTCstCode = CAD.FTCstCode
                                    LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                                    LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                                    LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                                    WHERE CAD.FTCstCode = ".$this->db->escape($ptHDCstCode)."
                                        AND CAD.FTAddGrpType = '4'
                                        AND CAD.FTAddRefNo = ".$this->db->escape($ptHDCstBchCode)."
                                    ORDER BY CAD.FNAddSeqNo DESC
                                ) A1
                                UNION ALL
                                SELECT  TOP 1 
                                        CAD.FTCstCode, 
                                        CstCD.FNCstCrTerm,
                                        CstCD.FCCstCrLimit,
                                        CstCD.FTCstTspPaid,
                                        CAD.FTAddRefNo,
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
                                        Cst.FTCstTel, 
                                        Cst.FTCstEmail, 
                                        CAD.FTAddFax,
                                        1 AS FNAddPriority
                                FROM TCNMCstAddress_L CAD
                                LEFT JOIN TCNMCst Cst WITH(NOLOCK) ON Cst.FTCstCode = CAD.FTCstCode 
                                LEFT JOIN TCNMCstCredit CstCD WITH(NOLOCK) ON CstCD.FTCstCode = CAD.FTCstCode
                                LEFT JOIN TCNMSubDistrict_L SDT WITH(NOLOCK) ON CAD.FTAddV1SubDist = SDT.FTSudCode AND SDT.FNLngID = ".$this->db->escape($pnLangID)."
                                LEFT JOIN TCNMDistrict_L DTS WITH(NOLOCK) ON CAD.FTAddV1SubDist = DTS.FTDstCode AND DTS.FNLngID = ".$this->db->escape($pnLangID)."
                                LEFT JOIN TCNMProvince_L PVN WITH(NOLOCK) ON CAD.FTAddV1PvnCode = PVN.FTPvnCode AND PVN.FNLngID = ".$this->db->escape($pnLangID)."
                                WHERE CAD.FTCstCode = ".$this->db->escape($ptHDCstCode)."
                                    AND CAD.FTAddGrpType = 1
                                ORDER BY CAD.FNAddSeqNo DESC
                            ) AFinal
                            ORDER BY FNAddPriority DESC;
                    ";

            $oQuery = $this->db->query($tSQL);

            if (empty($oQuery->result_array())) {
                $aDataReturn = array(
                    'rtCode' => '999',
                    'rtDesc' => 'Not Found',
                );
            } else {
                $aDataReturn = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Found',
                    'aQuery' => $oQuery->result_array()
                );
            }
        }
        return $aDataReturn;
    }

    //AND CAD.FTAddVersion = ".$this->db->escape($nAddressVersion)."
    //อนุมัตเอกสาร
    public function FSaMIVCApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXphStaApv',$paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode',$paDataUpdate['FTXphApvCode']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TACTSBHD');

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
    public function FSaMIVCChangeStatusDTDocument($paDataUpdate){

        $this->db->set('FTXpdRmk','1');
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TACTSBDT');

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
    public function FSaMIVCFindContact($ptSPLCode){
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

    //หาชื่อผู้ติดต่อมา default
    public function FSaMIVCChkCstBch($ptCstCode){
        $tSQL   = "SELECT FTCbrBchCode, FTCbrBchName FROM TCNMCstBch WHERE FTCstCode = ".$this->db->escape($ptCstCode)."";
        $oQuery = $this->db->query($tSQL);
        $aFind  = $oQuery->result_array();
        if ($oQuery->num_rows() > 0) {
            $aResult    = array(
                'rtCode'        => '1',
                'rtResult'      => $oQuery->result_array(),
                'row'           => count($aFind),
                'rtDesc'        => 'Find',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'row'           => 0,
                'rtDesc'        => 'data not found.',
            );
        }
        return $aResult;
    }
}
