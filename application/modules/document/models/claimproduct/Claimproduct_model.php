<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Claimproduct_model extends CI_Model {

    //Datatable
    public function FSaMCLMList($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];

        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC, FTPchDocNo DESC) AS FNRowID,* FROM
                            ( ";
        $tSQLMain   =         " SELECT 
                                DISTINCT 
                                BCHL.FTBchName,
                                HD.FTBchCode,
                                HD.FTPchDocNo,
                                CONVERT(CHAR(10),HD.FDPchDocDate,103) AS FDXphDocDate,
                                CSTL.FTCstName,
                                HD.FTPchStaPrcDoc,
                                HD.FTPchStaDoc,
                                HD.FTPchStaApv,
                                HD.FTPchRmk,
                                USRL.FTUsrName  AS FTCreateByName,
                                HD.FDCreateOn
                            FROM TCNTPdtClaimHD HD WITH (NOLOCK)
                            LEFT JOIN TCNTPdtClaimDTSpl HDSPL       WITH (NOLOCK) ON HD.FTBchCode = HDSPL.FTBchCode AND HDSPL.FTPchDocNo = HD.FTPchDocNo
                            LEFT JOIN TCNTPdtClaimHDCst HDCst       WITH (NOLOCK) ON HD.FTBchCode = HDCst.FTBchCode AND HDCst.FTPchDocNo = HD.FTPchDocNo
                            LEFT JOIN TCNMBranch_L      BCHL        WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK) ON HD.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                            LEFT JOIN TCNMCst_L         CSTL        WITH (NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID 
                            WHERE 1=1 ";

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQLMain .= " AND ((HD.FTPchDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDPchDocDate,103) LIKE '%$tSearchList%'))";
        }

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLMain .= " AND  HD.FTBchCode IN ($tBCH) ";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQLMain .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        /*จากสาขา - ถึงสาขา*/
        $tSearchSPLCodeFrom = $aAdvanceSearch['tSearchSPLCodeFrom'];
        $tSearchSPLCodeTo   = $aAdvanceSearch['tSearchSPLCodeTo'];
        if (!empty($tSearchSPLCodeFrom) && !empty($tSearchSPLCodeTo)) {
            $tSQLMain .= " AND ((HDSPL.FTSplCode BETWEEN '$tSearchSPLCodeFrom' AND '$tSearchSPLCodeTo') OR (HDSPL.FTSplCode BETWEEN '$tSearchSPLCodeFrom' AND '$tSearchSPLCodeTo'))";
        }

        /*จากวันที่ - ถึงวันที่*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQLMain .= " AND ((HD.FDPchDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDPchDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        /*ลูกค้า*/
        $tSearchCstCode = $aAdvanceSearch['tSearchCstCode'];
        if(!empty($tSearchCstCode)){
            $tSQLMain .= " AND (HD.FTCstCode = '$tSearchCstCode')";
        }

        /*รถ*/
        $tSearchCarCode = $aAdvanceSearch['tSearchCarCode'];
        if(!empty($tSearchCarCode)){
            $tSQLMain .= " AND (HDCST.FTCarCode = '$tSearchCarCode')";
        }

        /*สถานะเอกสาร*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if(!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")){
            $tSQLMain .= " AND HD.FTPchStaPrcDoc = '$tSearchStaDoc' ";
        }

        $tSQL .= $tSQLMain;
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList              = $oQuery->result();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow/$paData['nRow']);
            $aResult            = array(
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

    //ลบข้อมูล
    public function FSnMCLMDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimDT');
    
        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimDTRcv');

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimDTRet');
        
        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimDTSpl');

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimDTWrn');

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimHD');

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimHDCst');

        $this->db->where_in('FTPchDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtClaimHDDocRef');

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

    //ล้างข้อมูลใน temp
    public function FSaMCLMDeletePDTInTmp(){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTPdtClaimDTTmp');

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

    //รายละเอียดสินค้า และราคา ใน Master
    public function FSaMCLMGetDataPdt($paDataPdtParams){
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
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
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

    //ยกเลิกเอกสาร
    public function FSnMCLMClaimEventCancel($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        $this->db->set('FTPchStaDoc', 2);
        $this->db->where('FTPchDocNo',$tDataDocNo);
        $this->db->update('TCNTPdtClaimHD');
    
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

    //--------------------------------------- บันทึกข้อมูล --------------------------------------------//

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMCLMAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMCLMGetDataDocHD(array(
            'FTPchDocNo'    => $paDataWhere['FTPchDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTPchDocNo'    => $paDataWhere['FTPchDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['FDCreateOn'],
                'FTCreateBy'    => $aDataHDOld['FTCreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTPchDocNo'    => $paDataWhere['FTPchDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }

        // Delete HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTPchDocNo',$aDataAddUpdateHD['FTPchDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert HD 
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        return;
    }

    //ข้อมูล CST ลบและ เพิ่มใหม่
    public function FSxMCLMAddUpdateCSTHD($paDataCSTHD,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataSPLHD    =   $this->FSaMCLMGetDataDocCSTHD(array(
            'FTPchDocNo'    => $paDataWhere['FTPchDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateCSTHD    = array();
        if(isset($aDataGetDataSPLHD['rtCode']) && $aDataGetDataSPLHD['rtCode'] == 1){
            $aDataAddUpdateCSTHD    = array_merge($paDataCSTHD,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTPchDocNo'    => $paDataWhere['FTPchDocNo'],
            ));
        }else{
            $aDataAddUpdateCSTHD    = array_merge($paDataCSTHD,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTPchDocNo'    => $paDataWhere['FTPchDocNo']
            ));
        }

        // Delete SPL
        $this->db->where_in('FTBchCode',$aDataAddUpdateCSTHD['FTBchCode']);
        $this->db->where_in('FTPchDocNo',$aDataAddUpdateCSTHD['FTPchDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDCst']);

        // Insert SPL
        $this->db->insert($paTableAddUpdate['tTableHDCst'],$aDataAddUpdateCSTHD);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTPdtClaimDTTmp
    public function FSxMCLMAddUpdateDocNoToTemp($paDataWhere){

        // Update DocNo Into DTTemp
        $this->db->where('FTPchDocNo','DUMMY');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey','ClaimStep1Point1');
        $this->db->update('TCNTPdtClaimDTTmp',array(
            'FTPchDocNo'    => $paDataWhere['FTPchDocNo']
        ));
        return;
    }

    //ข้อมูล DT
    public function FSaMCLMMoveDTTmpToDT($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTPchDocNo'];
        $tDocKey      = 'ClaimStep1Point1';
        $tSessionID   = $this->session->userdata('tSesSessionID');
        $tADCode      = $this->session->userdata('tSesUsrAgnCode');

        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where_in('FTPchDocNo',$tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);

            $this->db->where_in('FTPchDocNo',$tDocNo);
            $this->db->delete($paTableAddUpdate['tTableDTSpl']);
        }

        //TCNTPdtClaimDT (รับสินค้าจาก - ลูกค้า)
        $tSQL   = "     INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                            FTPchDocNo , FNPcdSeqNo , FTPdtCode ,
                            FTPcdPdtName , FTPunCode , FTPunName ,
                            FCPcdFactor , FTPcdBarCode , FCPcdQty ,
                            FCPcdQtyAll , FTPcdRefDoc , FDPcdRefDocDate ,
                            FTWahCode , FCPsvWaDistance ,
                            FNPsvWaQtyDay , FTPsvWaCond , FCPcdLastDistance ,
                            FTPcdStaClaim , FTPcdUsrClaimApv , FTPcdRmk ,
                            FTBchCode , FTAgnCode ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTPchDocNo ,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNPcdSeqNo ASC) AS FNPcdSeqNo,
                            DOCTMP.FTPdtCode ,
                            DOCTMP.FTPcdPdtName ,
                            DOCTMP.FTPunCode ,
                            DOCTMP.FTPunName ,
                            DOCTMP.FCPcdFactor ,
                            DOCTMP.FTPcdBarCode ,
                            DOCTMP.FCPcdQty ,
                            DOCTMP.FCPcdQtyAll ,
                            DOCTMP.FTPcdRefDoc ,
                            DOCTMP.FDPcdRefDocDate ,
                            'WAIT' , --ต้องเป็นคลังเคลมรับ
                            DOCTMP.FCPsvWaDistance ,
                            DOCTMP.FNPsvWaQtyDay ,
                            DOCTMP.FTPsvWaCond ,
                            DOCTMP.FCPcdLastDistance ,
                            DOCTMP.FTPcdStaClaim ,
                            DOCTMP.FTPcdUsrClaimApv ,
                            DOCTMP.FTPcdRmk ,
                            '$tBchCode',
                            '$tADCode'
                        FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                        WHERE DOCTMP.FTPchDocNo   = '$tDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tDocKey'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        ORDER BY DOCTMP.FNPcdSeqNo ASC";
        $this->db->query($tSQL);

        //TCNTPdtClaimDTSpl (ส่งสินค้าไปหา - ผู้จำหน่าย)
        $tSQL   = "     INSERT INTO ".$paTableAddUpdate['tTableDTSpl']." (
                            FTPchDocNo , FNPcdSeqNo , FTSplCode ,
                            FDPcdDateReq , FTPcdPdtRmk ,  FTPcdStaPick ,
                            FTPcdPdtPick , FCPcdQtyPick , FTWahCode ,
                            FTBchCode , FTAgnCode ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTPchDocNo ,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNPcdSeqNo ASC) AS FNPcdSeqNo,
                            DOCTMP.FTSplCode ,
                            DOCTMP.FDPcdDateReq ,
                            DOCTMP.FTPcdRmk ,
                            DOCTMP.FTPcdStaPick ,
                            DOCTMP.FTPcdPdtPick ,
                            DOCTMP.FCPcdQtyPick ,
                            'WAIT' , --ต้องเป็นคลังเคลมเปลียน
                            '$tBchCode' ,
                            '$tADCode'
                        FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTPchDocNo   = '$tDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tDocKey'
                        AND DOCTMP.FTSessionID  = '$tSessionID'
                        ORDER BY DOCTMP.FNPcdSeqNo ASC";
        $this->db->query($tSQL);
        return;
    }

    //อัพเดทคลังเคลมเปลี่ยน คลังเคลมรับในตาราง
    public function FSaMCLMUpdateWahouseInTable($paDataWhere){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTPchDocNo'];

        //อัพเดทคลังเคลมรับ
        $tSQL = "   UPDATE TCNTPdtClaimDT 
                    SET FTWahCode = TCNMWaHouse.FTWahCode
                    FROM (
                            SELECT TOP 1 FTWahCode 
                            FROM TCNMWaHouse WHERE 
                            TCNMWaHouse.FTWahStaType ='8' AND 
                            TCNMWaHouse.FTBchCode = '$tBchCode' 
                            ORDER BY FTWahCode DESC 
                        ) AS TCNMWaHouse
                    WHERE 
                        TCNTPdtClaimDT.FTPchDocNo = '$tDocNo' AND
                        TCNTPdtClaimDT.FTBchCode = '$tBchCode' ";
        $this->db->query($tSQL);

        //อัพเดทคลังเคลมเปลี่ยน
        $tSQL = "   UPDATE TCNTPdtClaimDTSpl 
                    SET FTWahCode = TCNMWaHouse.FTWahCode
                    FROM (
                            SELECT TOP 1 FTWahCode 
                            FROM TCNMWaHouse WHERE 
                            TCNMWaHouse.FTWahStaType ='9' AND 
                            TCNMWaHouse.FTBchCode = '$tBchCode' 
                            ORDER BY FTWahCode DESC 
                        ) AS TCNMWaHouse
                    WHERE 
                        TCNTPdtClaimDTSpl.FTPchDocNo = '$tDocNo' AND
                        TCNTPdtClaimDTSpl.FTBchCode = '$tBchCode' ";
        $this->db->query($tSQL);
    }

    //เช็คว่ามีคลังเคลมเปลี่ยน คลังเคลมรับ
    public function FSaMCLMFindWahouseINBranch($paDataWhere){
        $tBCHCode   = $paDataWhere['tBCHCode'];
        //8 : เคลมรับ
        //9 : เคลมเปลี่ยน
        $tSQL   = "SELECT SUM(A.CountWah) AS CountWahouse FROM(
                        SELECT TOP 1 COUNT(FTWahCode) AS CountWah FROM TCNMWaHouse WHERE FTBchCode = '$tBCHCode' AND FTWahStaType ='8' 
                        UNION ALL
                        SELECT TOP 1 COUNT(FTWahCode) AS CountWah FROM TCNMWaHouse WHERE FTBchCode = '$tBCHCode' AND FTWahStaType ='9' 
                    ) AS A";
        $oQuery = $this->db->query($tSQL);
        $aFindWah = $oQuery->result_array();
        if ($aFindWah[0]['CountWahouse'] >= 2){ //ต้องเจอทั้งคลังเคลมรับ และ เคลมเปลี่ยน
            $aResult    = array(
                'rtCode'    => '1',
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

    //เช็คว่าสินค้าที่จะยืนยันนั้นได้ระบบ SPL มาเเล้ว
    public function FSaMCLMFindSPLInTemp($paDataWhere){
        $tCLMDocNo      = $paDataWhere['tCLMDocNo'];
        $tSessionID     = $this->session->userdata('tSesSessionID');
        
        $tSQL       = " SELECT FTSplCode FROM TCNTPdtClaimDTTmp WHERE 
                            FTPchDocNo = '$tCLMDocNo' AND 
                            FTSessionID = '$tSessionID' AND 
                            FTXthDocKey = 'ClaimStep1Point1' AND
                            ISNULL(FTSplCode,'') = '' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'PDT NOT HAVE SPL',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'PDT Have SPL',
            );
        }
        return $aResult;

    }

    //--------------------------------------- เข้าหน้าแก้ไข --------------------------------------------//

    //ข้อมูล HD
    public function FSaMCLMGetDataDocHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTPchDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            CALHD.*,
                            BCHL.FTBchName,
                            USRL.FTUsrName AS FTUsrName
                        FROM TCNTPdtClaimHD         CALHD  WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL   WITH (NOLOCK)   ON CALHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMUser_L        USRL   WITH (NOLOCK)   ON CALHD.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                        WHERE CALHD.FTPchDocNo = '$tDocNo' ";

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
    public function FSaMCLMGetDataDocCSTHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTPchDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            CSTHD.*,
                            CSTL.FTCstCode AS FTCstCode,
                            CSTL.FTCstName AS FTCstName,
                            CST.FTCstTel,
                            CST.FTCstEmail,
                            ADDL.FTAddV2Desc1,
                            CAR.FTCarRegNo
                        FROM TCNTPdtClaimHDCst CSTHD WITH (NOLOCK)
                        LEFT JOIN TCNTPdtClaimHD    HD     WITH (NOLOCK)    ON CSTHD.FTPchDocNo = HD.FTPchDocNo AND CSTHD.FTBchCode = HD.FTBchCode
                        LEFT JOIN TCNMCst           CST    WITH (NOLOCK)    ON HD.FTCstCode = CST.FTCstCode 
                        LEFT JOIN TCNMCst_L         CSTL   WITH (NOLOCK)    ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                        LEFT JOIN TCNMCstAddress_L  ADDL   WITH (NOLOCK)    ON HD.FTCstCode = ADDL.FTCstCode
                        LEFT JOIN TSVMCar           CAR    WITH (NOLOCK)    ON CSTHD.FTCarCode = CAR.FTCarCode
                        WHERE CSTHD.FTPchDocNo = '$tDocNo' ";
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

    //ช้อมูล HDDocRef
    public function FSaMCLMGetDataDocHDDocRef($paDataWhere){
        $tDocNo     = $paDataWhere['FTPchDocNo'];

        $tSQL       = " SELECT
                            TOP 2
                            CALHDDOC.FTXshRefType,
                            CALHDDOC.FTXshRefDocNo,
                            CALHDDOC.FDXshRefDocDate
                        FROM TCNTPdtClaimHDDocRef   CALHDDOC  WITH (NOLOCK)
                        WHERE CALHDDOC.FTPchDocNo = '$tDocNo'
                        AND CALHDDOC.FTXshRefKey IN ('OTHER','ABB') ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->result();
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

    //--------------------------------------- ย้ายข้อมูลจากจริงไป Temp --------------------------------------------//

    //ย้ายจาก DT To Temp
    public function FSxMCLMMoveDTToDTTemp($paDataWhere){
        $tDocNo         = $paDataWhere['FTPchDocNo'];
        $tDocKey        = 'ClaimStep1Point1';
        $tDocKey2       = 'ClaimStep3';

        // Delect Document DTTemp By Doc No
        $this->db->where('FTPchDocNo',$tDocNo);
        $this->db->delete('TCNTPdtClaimDTTmp');

        //TCNTPdtClaimDT (รับสินค้าจาก - ลูกค้า) , TCNTPdtClaimDTSpl (ส่งสินค้าไปหา - ผู้จำหน่าย)
        $tSQL   = " INSERT INTO TCNTPdtClaimDTTmp (
                        FTPchDocNo , FNPcdSeqNo , FTPdtCode ,
                        FTPcdPdtName , FTPunCode , FTPunName ,
                        FCPcdFactor , FTPcdBarCode , FCPcdQty ,
                        FCPcdQtyAll , FTPcdRefDoc , FDPcdRefDocDate ,
                        FCPsvWaDistance ,
                        FNPsvWaQtyDay , FTPsvWaCond , FCPcdLastDistance ,
                        FTPcdStaClaim , FTPcdUsrClaimApv , FTPcdRmk ,
                        FTSplCode ,
                        FDPcdDateReq , FTPcdPdtRmk , FTPcdStaPick ,
                        FTPcdPdtPick , FCPcdQtyPick , FTWahCode ,
                        FDPcdSplGetDate , FTPctSplStaff , FTPcdSplRmk ,
                        FTXthDocKey , FTSessionID , FDLastUpdOn , FDCreateOn , FTLastUpdBy , FTCreateBy )
                    SELECT
                        DT.FTPchDocNo,
                        DT.FNPcdSeqNo,
                        DT.FTPdtCode,
                        DT.FTPcdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCPcdFactor,
                        DT.FTPcdBarCode,
                        DT.FCPcdQty,
                        DT.FCPcdQtyAll,
                        DT.FTPcdRefDoc,
                        DT.FDPcdRefDocDate,
                        DT.FCPsvWaDistance,
                        DT.FNPsvWaQtyDay,
                        DT.FTPsvWaCond,
                        DT.FCPcdLastDistance,
                        DT.FTPcdStaClaim,
                        DT.FTPcdUsrClaimApv,
                        DT.FTPcdRmk,
                        DTSPL.FTSplCode,
                        DTSPL.FDPcdDateReq,
                        DTSPL.FTPcdPdtRmk,
                        DTSPL.FTPcdStaPick,
                        DTSPL.FTPcdPdtPick,
                        DTSPL.FCPcdQtyPick,
                        DTSPL.FTWahCode,
                        DTSPL.FDPcdSplGetDate, 
                        DTSPL.FTPctSplStaff, 
                        DTSPL.FTPcdSplRmk,
                        CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TCNTPdtClaimDT AS DT WITH (NOLOCK)
                    LEFT JOIN TCNTPdtClaimDTSpl DTSPL ON DT.FTPchDocNo = DTSPL.FTPchDocNo AND DT.FNPcdSeqNo = DTSPL.FNPcdSeqNo
                    WHERE DT.FTPchDocNo = '$tDocNo'
                    ORDER BY DT.FNPcdSeqNo ASC ";
        $this->db->query($tSQL);

        //TCNTPdtClaimDTWrn , TCNTPdtClaimDTRcv 
        $tSQL   = " INSERT INTO TCNTPdtClaimDTTmp (
                        FTPchDocNo , FNPcdSeqNo , FNWrnSeq , FNRcvSeq , FTPdtCode ,
                        FTSplCode , FTPcdRefTwo , FTWrnRefDoc ,
                        FCWrnPercent , FCWrnDNCNAmt , FTWrnPdtCode ,
                        FCWrnPdtQty , FTWrnRmk , FTWrnUsrCode , 
                        FDWrnDate , FTRcvPdtCode , FCRcvPdtQty ,FDRcvDate ,
                        FTRcvRefTwi , FDRcvRefDate ,
                        FTXthDocKey , FTSessionID , FDLastUpdOn , FDCreateOn , FTLastUpdBy , FTCreateBy )
                    SELECT
                        DISTINCT
                        DT.FTPchDocNo,
                        DT.FNPcdSeqNo,
                        DT.FNWrnSeq,
                        DTRcv.FNRcvSeq,
                        DT.FTWrnPdtCode,
                        DT.FTSplCode,
                        DT.FTPcdRefTwo,
                        DT.FTWrnRefDoc,
                        DT.FCWrnPercent,
                        DT.FCWrnDNCNAmt,
                        DT.FTWrnPdtCode,
                        DT.FCWrnPdtQty,
                        DT.FTWrnRmk,
                        DT.FTWrnUsrCode,
                        DT.FDWrnDate,
                        DTRcv.FTRcvPdtCode , 
                        DTRcv.FCRcvPdtQty ,
                        DTRcv.FDRcvDate ,
                        DTRcv.FTRcvRefTwi , 
                        DTRcv.FDRcvRefDate ,
                        CONVERT(VARCHAR,'".$tDocKey2."') AS FTXthDocKey,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TCNTPdtClaimDTWrn AS DT WITH (NOLOCK)
                    LEFT JOIN TCNTPdtClaimDTRcv DTRcv ON 
                            DT.FTPchDocNo = DTRcv.FTPchDocNo AND 
                            DT.FNPcdSeqNo = DTRcv.FNPcdSeqNo 
                    WHERE DT.FTPchDocNo = '$tDocNo'
                    ORDER BY DT.FNPcdSeqNo ASC ";
        $this->db->query($tSQL);

            
        //จำนวนลูกค้าที่รับแล้ว step4
        $tSQL = "UPDATE DOCTMP
                SET 
                    DOCTMP.FCRetPdtQty = RES.FCRetPdtQty ,
                    DOCTMP.FTRetRmk = RES.FTRetRmk ,
                    DOCTMP.FDRetDate = RES.FDRetDate , 
                    DOCTMP.FTRetStaGenCNDN = RES.FTRetStaGenCNDN 
                FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                LEFT JOIN (
                    SELECT 
                        DTRet.FTPchDocNo , 
                        DTRet.FNWrnSeq,
                        DTRet.FNRcvSeq ,
                        DTRet.FCRetPdtQty ,
                        DTRet.FTRetRmk ,
                        DTRet.FDRetDate ,
                        DTRet.FTRetStaGenCNDN 
                    FROM TCNTPdtClaimDTRet DTRet WITH(NOLOCK)
                    WHERE DTRet.FTPchDocNo = '$tDocNo'
                ) RES 
            ON RES.FTPchDocNo = DOCTMP.FTPchDocNo
            AND RES.FNWrnSeq = DOCTMP.FNWrnSeq
            AND RES.FNRcvSeq = DOCTMP.FNRcvSeq ";
        $this->db->query($tSQL);

        return;
    }

    //--------------------------------------- อ้างอิงเอกสารภายใน --------------------------------------------//

    // Function: Get Data ใบขาย HD List
    public function FSoMCLMCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tCLMRefIntBchCode        = $aAdvanceSearch['tCLMRefIntBchCode'];
        $tCLMRefIntDocNo          = $aAdvanceSearch['tCLMRefIntDocNo'];
        $tCLMRefIntDocDateFrm     = $aAdvanceSearch['tCLMRefIntDocDateFrm'];
        $tCLMRefIntDocDateTo      = $aAdvanceSearch['tCLMRefIntDocDateTo'];
        $tCLMRefIntStaDoc         = $aAdvanceSearch['tCLMRefIntStaDoc'];

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
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON SALHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID      = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON SALHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID      = $nLngID
                        WHERE 1=1 AND SALHD.FTXshStaDoc = 1
                    ";

        if(isset($tCLMRefIntBchCode) && !empty($tCLMRefIntBchCode)){
            $tSQLMain .= " AND (SALHD.FTBchCode = '$tCLMRefIntBchCode')";
        }

        if(isset($tCLMRefIntDocNo) && !empty($tCLMRefIntDocNo)){
            $tSQLMain .= " AND (SALHD.FTXshDocNo LIKE '%$tCLMRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tCLMRefIntDocDateFrm) && !empty($tCLMRefIntDocDateTo)){
            $tSQLMain .= " AND ((SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCLMRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tCLMRefIntDocDateTo 23:59:59')) OR (SALHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tCLMRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tCLMRefIntDocDateFrm 00:00:00')))";
        }

        $tSQL   =   "SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

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

    // Functionality: Get Data ใบขาย DT List
    public function FSoMCLMCallRefIntDocDTDataTable($paData){

        $nLngID    =  $paData['FNLngID'];
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

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMCLMCallRefIntDocInsertDTToTemp($paData){

        $tCLMDocNo        = $paData['tCLMDocNo'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocKey',$paData['tDocKey']);
        $this->db->where('FTPchDocNo',$tCLMDocNo);
        $this->db->delete('TCNTPdtClaimDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

        $tSQL= "INSERT INTO TCNTPdtClaimDTTmp (
                    FTPchDocNo,FNPcdSeqNo,FTPdtCode,FTPcdPdtName,FTPunCode,FTPunName,
                    FCPcdFactor,FTPcdBarCode,FCPcdQty,FCPcdQtyAll,FTPcdStaClaim,
                    FTPcdRmk,FTXthDocKey,FTSessionID,FCPsvWaDistance,
                    FNPsvWaQtyDay,FTPsvWaCond,FDLastUpdOn,FTLastUpdBy,
                    FDCreateOn,FTCreateBy
                )
                SELECT
                    '$tCLMDocNo' as FTPchDocNo,
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
                    '".$paData['tDocKey']."' AS FTXthDocKey,  
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    PDTCar.FCPsvWaDistance,
                    PDTCar.FNPsvWaQtyDay,
                    PDTCar.FTPsvWaCond,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TPSTSalDT DT WITH (NOLOCK)
                LEFT JOIN TSVMPdtCar PDTCar WITH (NOLOCK) ON DT.FTPdtCode = PDTCar.FTPdtCode
                WHERE DT.FTBchCode = '$tRefIntBchCode' AND DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";

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

    //เพิ่มข้อมูลว่าอ้างอิงเอกสาร 
    public function FSxMCLMUpdateRef($ptTableName , $paParam){
        $nChkDataDocRef  = $this->FSaMCLMChkRefDupicate($ptTableName , $paParam);
        $tTableRef       = $ptTableName;
        if(isset($nChkDataDocRef['rtCode']) && $nChkDataDocRef['rtCode'] == 1){ //หากพบว่าซ้ำ
            //ลบ

            if($ptTableName == 'TCNTPdtClaimHDDocRef'){
                $this->db->where_in('FTAgnCode',$paParam['FTAgnCode']);
                $this->db->where_in('FTPchDocNo',$paParam['FTPchDocNo']);
            }else{
                $this->db->where_in('FTXshDocNo',$paParam['FTXshDocNo']);
            }

            $this->db->where_in('FTBchCode',$paParam['FTBchCode']);
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
    public function FSaMCLMChkRefDupicate($ptTableName , $paParam){
        try{
            $tBchCode       = $paParam['FTBchCode'];
            $tRefDocType    = $paParam['FTXshRefType'];

            if($ptTableName == 'TCNTPdtClaimHDDocRef'){
                $tDocNo         = $paParam['FTPchDocNo'];
                $tRefDocNo      = $paParam['FTPchDocNo'];
            }else{
                $tDocNo         = $paParam['FTXshDocNo'];
                $tRefDocNo      = $paParam['FTXshDocNo'];
            }

            $tSQL = "   SELECT 
                            FTBchCode
                        FROM $ptTableName
                        WHERE 1=1
                        AND FTBchCode     = '$tBchCode'
                        AND FTXshRefType  = '$tRefDocType' ";

            if($tRefDocType == 1 || $tRefDocType == 3){
                $tSQL .= " AND FTPchDocNo  = '$tDocNo' " ;
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

    // หาว่าเอกสารอ้างอิง นี้ลูกค้าเป็นอะไร
    public function FSoMCLMCallRefIntDocFindCstAndCar($paData){

        $tRefIntDocNo    =  $paData['tRefIntDocNo'];
        $tRefIntBchCode  =  $paData['tRefIntBchCode'];

        $tSQL= "SELECT
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
                WHERE HD.FTBchCode = '$tRefIntBchCode' AND  HD.FTXshDocNo ='$tRefIntDocNo'
                AND ISNULL(HD.FTCstCode,'') <> '' ";
        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0){
            $aResult = array(
                'raItems'       => $oQuery->result_array(),
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

    //--------------------------------------- STEP 1 - POINT 1 --------------------------------------------//

    //Get ข้อมูลใน Temp
    public function FSaMCLMListStep1Point1($paDataWhere){
        $tDocNo             = $paDataWhere['tDocNo'];
        $tBCHCode           = $paDataWhere['tBCHCode'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nSeqPDT            = ( !isset($paDataWhere['nSeqPDT']) ) ? '' : $paDataWhere['nSeqPDT'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FNPcdSeqNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.*,
                                        CONVERT(CHAR(10),DOCTMP.FDPcdDateReq,23) AS DateReq,
                                        CONVERT(CHAR(10),DOCTMP.FDPcdSplGetDate,23) AS DateSplGet,
                                        SPLL.FTSplName,
                                        PDTL.FTPdtCode          AS Pick_PDTCode,
                                        PDTL.FTPdtName          AS Pick_PDTName,
                                        PDTLS3Wrn.FTPdtName     AS Step3_PDTName_Wrn,
                                        PDTLS3Rcv.FTPdtName     AS Step3_PDTName_Rcv
                                    FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                    LEFT JOIN TCNMSpl_L SPLL    ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = $nLngID
                                    LEFT JOIN TCNMPdt_L PDTL    ON DOCTMP.FTPcdPdtPick = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                                    LEFT JOIN TCNMPdt_L PDTLS3Wrn  ON DOCTMP.FTWrnPdtCode = PDTLS3Wrn.FTPdtCode AND PDTLS3Wrn.FNLngID = $nLngID
                                    LEFT JOIN TCNMPdt_L PDTLS3Rcv  ON DOCTMP.FTRcvPdtCode = PDTLS3Rcv.FTPdtCode AND PDTLS3Rcv.FNLngID = $nLngID
                                    WHERE 1 = 1
                                    AND ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                    AND DOCTMP.FTXthDocKey = '$tDocKey'
                                    AND DOCTMP.FTSessionID = '$tSesSessionID' ";
        //เอาไว้ดูประวัติใน step3
        if($nSeqPDT != ''){
            $tSQL           .= " AND DOCTMP.FNPcdSeqNo = '$nSeqPDT' "; 
        }
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
        return $aDataReturn;
    }

    //Get ข้อมูลใน Temp ว่า SPL อะไร
    public function FSaMCLMPDTFindBySPL($paDataWhere){
        $tDocNo             = $paDataWhere['tDocNo'];
        $tDocKey            = $paDataWhere['tDocKey'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');

        //Update SPL 
        if($tDocNo == 'DUMMY'){
            //หาก่อนว่า SPL มีไหม ถ้าไม่มีไม่ต้อง update
            $tSQLUpdate = "UPDATE DOCTMP
                            SET DOCTMP.FTSplCode = RES.SPL_Code 
                    FROM TCNTPdtClaimDTTmp AS DOCTMP WITH(NOLOCK)
                    LEFT JOIN (
                        SELECT
                            TOP 1
                            DOCTMP.FNPcdSeqNo , 
                            DOCTMP.FTPchDocNo ,
                            DOCTMP.FTPdtCode ,
                            DOCTMP.FTSessionID ,
                            DOCTMP.FTXthDocKey,       
                            CASE
                                WHEN ISNULL(DOCTMP.FTSPLCode,'') <> '' THEN DOCTMP.FTSPLCode
                                WHEN ISNULL(PIHD.FTSplCode,'') <> '' THEN PIHD.FTSplCode
                                WHEN ISNULL(PDTSPL.FTSplCode,'') <> '' THEN PDTSPL.FTSplCode
                            ELSE DOCTMP.FTSPLCode
                            END AS SPL_Code
                        FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                        LEFT JOIN (
                            SELECT DISTINCT HD.FTSplCode , DT.FTPDTCode , PDTSPLL.FTSplName FROM TAPTPiHD HD
                            LEFT JOIN TAPTPiDT DT           ON HD.FTXphDocNo = DT.FTXphDocNo
                            LEFT JOIN TCNMSpl_L PDTSPLL     ON HD.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = $nLngID
                        ) AS PIHD                           ON DOCTMP.FTPdtCode = PIHD.FTPdtCode
                        LEFT JOIN (
                            SELECT DISTINCT PDTSPL.FTSplCode , PDTSPL.FTPDTCode , PDTSPLL.FTSplName , PDTSPL.FTBarCode FROM TCNMPdtSpl PDTSPL
                            LEFT JOIN TCNMSpl_L PDTSPLL     ON PDTSPL.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = $nLngID
                        ) AS PDTSPL                         ON DOCTMP.FTPdtCode = PDTSPL.FTPdtCode AND DOCTMP.FTPcdBarCode = PDTSPL.FTBarCode
                        WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                        AND DOCTMP.FTXthDocKey = '$tDocKey'
                        AND DOCTMP.FTSessionID = '$tSesSessionID'
                    ) RES 
                    ON RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                    AND RES.FTPchDocNo = DOCTMP.FTPchDocNo 
                    AND RES.FTPdtCode = DOCTMP.FTPdtCode
                    AND RES.FTXthDocKey = DOCTMP.FTXthDocKey 
                    AND RES.FTSessionID = DOCTMP.FTSessionID 
                    WHERE RES.FTSessionID = '$tSesSessionID' ";
            $this->db->query($tSQLUpdate);
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
                    LEFT JOIN TCNMSpl_L PDTSPLL     ON HD.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = $nLngID
                ) AS PIHD                           ON DOCTMP.FTPdtCode = PIHD.FTPdtCode
                LEFT JOIN (
                    SELECT DISTINCT PDTSPL.FTSplCode , PDTSPL.FTPDTCode , PDTSPLL.FTSplName , PDTSPL.FTBarCode FROM TCNMPdtSpl PDTSPL
                    LEFT JOIN TCNMSpl_L PDTSPLL     ON PDTSPL.FTSPLCode = PDTSPLL.FTSPLCode AND PDTSPLL.FNLngID = $nLngID
                ) AS PDTSPL                         ON DOCTMP.FTPdtCode = PDTSPL.FTPdtCode AND DOCTMP.FTPcdBarCode = PDTSPL.FTBarCode
                WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                AND DOCTMP.FTXthDocKey = '$tDocKey'
                AND DOCTMP.FTSessionID = '$tSesSessionID' ";
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
        return $aDataReturn;
    }

    //Insert ข้อมูลใน Temp (step1)
    public function FSaMCLMInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paItemDataPdt    = $paDataPdtMaster['raItem'];

        // เพิ่มแถวใหม่
        $aDataInsert    = array(
            'FTPchDocNo'        => $paDataPdtParams['tDocNo'],
            'FNPcdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
            'FTPdtCode'         => $paItemDataPdt['FTPdtCode'],
            'FTPcdPdtName'      => $paItemDataPdt['FTPdtName'],
            'FTPunCode'         => $paItemDataPdt['FTPunCode'],
            'FTPunName'         => $paItemDataPdt['FTPunName'],
            'FCPcdFactor'       => $paItemDataPdt['FCPdtUnitFact'],
            'FTPcdBarCode'      => $paDataPdtParams['tBarCode'],
            'FCPcdQty'          => 1,
            'FTPcdStaClaim'     => 2, //default : ไม่อนุญาติเคลม
            'FCPcdQtyAll'       => 1*$paItemDataPdt['FCPdtUnitFact'],
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
        $this->db->insert('TCNTPdtClaimDTTmp',$aDataInsert);
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
        return $aStatus;
    }

    //ลบข้อมูลใน Temp 
    public function FSnMCLMDelDTTmp($paData){
        try {
            $this->db->trans_begin();

            if($paData['tDocKey'] == 'ClaimStep1Point1'){
                $this->db->where_in('FNPcdSeqNo', $paData['nMaxSeqNo']);
            }else if($paData['tDocKey'] == 'ClaimStep3'){
                $this->db->where_in('FNWrnSeq', $paData['nMaxSeqNo']);
            }

            $this->db->where_in('FTPchDocNo', $paData['tDocNo']);
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
    public function FSaMCLMUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $tSessionID     = $paDataWhere['tSessionID'];
        $tDocNo         = $paDataWhere['tDocNo'];
        $nSeq           = $paDataWhere['nSeq'];
        $tDocKey        = $paDataWhere['tDocKey'];

        $this->db->set('FCPcdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCPcdQtyAll', 1 * $paDataUpdateDT['FCXtdQty']);
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FNPcdSeqNo',$nSeq);
        $this->db->where('FTPchDocNo',$tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');
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

    //--------------------------------------- STEP 1 - POINT 2 --------------------------------------------//

    //อัพเดทสถานะเคลมภายใน + หมายเหตุ + ผู้จำหน่าย(point3) + วันที่แจ้ง(point3) + จำนวนยืม(point4) + สินค้าที่ยืม(point4)
    public function FSaMCLMUpdateInlineDTTempStaAndRmk($paDataUpdateDT,$paDataWhere ,$tTypeUpdate){
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
                if($paDataUpdateDT['FTPcdPdtPick'] == '' || $paDataUpdateDT['FTPcdPdtPick'] == null){
                    $this->db->set('FTPcdStaPick', null); //ไม่มีการยืม
                }else{
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
        
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FNPcdSeqNo',$nSeq);
        $this->db->where('FTPchDocNo',$tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //--------------------------------------- STEP 2 --------------------------------------------//

    //อัพเดทข้อมูล
    public function FSaMCLMStep2UpdatePrcDoc($paDataWhere){
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
                            UPD.FTPchDocNo, 
                            UPD.FNPcdSeqNo ,
                            UPD.FDPcdSplGetDate	,
                            UPD.FTPcdSplRmk ,
                            UPD.FTPctSplStaff
                        FROM TCNTPdtClaimDTTmp UPD WITH(NOLOCK)
                        WHERE UPD.FTPchDocNo = '$tDocNo'
                        AND UPD.FTSessionID = '$tSesSessionID'
                        AND UPD.FTXthDocKey = 'ClaimStep1Point1'
                    ) RES 
                ON RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                AND RES.FTPchDocNo = DOCTMP.FTPchDocNo ";
        $this->db->query($tSQL);
    }

    //--------------------------------------- STEP 3 --------------------------------------------//

    //ช้อมูลในตาราง
    public function FSaMCLMListTableStep3($paDataWhere){
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
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = $nLngID
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTPchDocNo = TMPWrn.FTPchDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = '$tDocKey2'
                                WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                AND DOCTMP.FTXthDocKey = '$tDocKey'
                                AND DOCTMP.FTSessionID = '$tSesSessionID'
                                GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        SPLL.FTSplName ";
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
        return $aDataReturn;
    }

    //รายละเอียดข้อมูลของสินค้าตัวที่กดดู หรือตัวที่กำลังจะกดบันทึก ตาม SEQ
    public function FSaMCLMGetItemClaimBySeq($paDataWhere){
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
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = $nLngID
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTPchDocNo = TMPWrn.FTPchDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = '$tDocKey2'
                                WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                AND DOCTMP.FTXthDocKey = '$tDocKey'
                                AND DOCTMP.FNPcdSeqNo = '$nSeqPDT'
                                AND DOCTMP.FTSessionID = '$tSesSessionID'
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
    public function FSaMCLMInsertPDTToTempStep3($paDataPdtParams){

        //หา Seq DNCN 
        $tDocNo     = $paDataPdtParams['tDocNo'];
        $tDocKey    = $paDataPdtParams['tDocKey'];
        $tSessionID = $paDataPdtParams['tSessionID'];
        $tPDTCode   = $paDataPdtParams['tPDTCode'];
        $tSQL               = " SELECT TOP 1 DOCTMP.FNWrnSeq FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                                WHERE 1 = 1
                                AND ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                AND DOCTMP.FTXthDocKey = '$tDocKey'
                                AND DOCTMP.FTSessionID = '$tSessionID'
                                AND DOCTMP.FTWrnPdtCode = '$tPDTCode'
                                ORDER BY FNWrnSeq DESC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult    = $oQuery->result_array();
            $nSeq       = $aResult[0]['FNWrnSeq'] + 1;
        }else{
            $nSeq       =  1;
        }

        // เพิ่มแถวใหม่
        $aDataInsert    = array(
            'FNWrnSeq'          => $nSeq,
            'FNPcdSeqNo'        => $paDataPdtParams['FNPcdSeqNo'],
            'FTPchDocNo'        => $tDocNo,
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
        $this->db->insert('TCNTPdtClaimDTTmp',$aDataInsert);
        
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
        return $aStatus;
    }

    //Get ข้อมูลใน Temp
    public function FSaMCLMListStep3($paDataWhere){
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
                                    LEFT JOIN TCNMPdt_L PDTL ON DOCTMP.FTWrnPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                                    WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                    AND DOCTMP.FNPcdSeqNo = '$nSeqPDT'
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
        return $aDataReturn;
    }

    //บันทึกข้อมูลในตารางจริง
    public function FSaMCLMMoveTempToDTInSaveStep3($paDataWhere){

        $tBCHCode     = $paDataWhere['tBCHCode'];
        $tADCode      = $this->session->userdata('tSesUsrAgnCode');
        $tDocKey      = $paDataWhere['tDocKey'];
        $tDocNo       = $paDataWhere['tDocNo'];
        $tSessionID   = $paDataWhere['tSessionID'];
        $tTypePage    = $paDataWhere['tTypePage'];
        $nSeqNo       = $paDataWhere['nSeqNo'];

        if($tTypePage == 'saveclaim'){

            //ลบข้อมูลก่อน
            $this->db->where_in('FNPcdSeqNo', $nSeqNo);
            $this->db->where_in('FTPchDocNo', $tDocNo);
            $this->db->delete('TCNTPdtClaimDTWrn');

            //TCNTPdtClaimDTWrn
            $tSQL   = "INSERT INTO TCNTPdtClaimDTWrn (
                FTPchDocNo , FNPcdSeqNo , FNWrnSeq ,
                FTSplCode , FTPcdRefTwo , FTWrnRefDoc ,
                FCWrnPercent , FCWrnDNCNAmt , FTWrnPdtCode ,
                FCWrnPdtQty , FTWrnRmk , FTWrnUsrCode ,
                FDWrnDate , FTAgnCode , FTBchCode
            ) ";
            $tSQL   .=  "SELECT
                DOCTMP.FTPchDocNo ,
                $nSeqNo,   --ลำดับรายการ
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
                '$tADCode',
                '$tBCHCode'
                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                WHERE DOCTMP.FNPcdSeqNo = '$nSeqNo'
                AND DOCTMP.FTPchDocNo   = '$tDocNo'
                AND DOCTMP.FTXthDocKey  = '$tDocKey'
                AND DOCTMP.FTSessionID  = '$tSessionID'
                ORDER BY DOCTMP.FNPcdSeqNo ASC";
            $this->db->query($tSQL);

        }else if($tTypePage == 'saveget'){

            //สามารถรับสินค้าได้ที่ละชิ้น
            //หา Seq DNCN 
            $tSPLCode   = $paDataWhere['tSPLCode'];

            $tSQL       = " SELECT DOCTMP.FNRcvSeq FROM TCNTPdtClaimDTRcv DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                            AND DOCTMP.FNPcdSeqNo = '$nSeqNo' ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aResult    = $oQuery->result_array();
                $nSeqRcv    = $aResult[0]['FNRcvSeq'] + 1;
            }else{
                $nSeqRcv    =  1;
            }

            //TCNTPdtClaimDTRcv เอาสินค้าเข้าระบบ
            $tSQL   = "INSERT INTO TCNTPdtClaimDTRcv (
                        FTPchDocNo , FNPcdSeqNo , FNWrnSeq ,
                        FNRcvSeq , FTSplCode , FTPcdRefTwo ,
                        FTRcvPdtCode , FCRcvPdtQty , FTRcvRmk ,
                        FDRcvDate , FTRcvUsrCode , FTRcvRefTwi ,
                        FDRcvRefDate , FTAgnCode , FTBchCode ) ";
            $tSQL   .=  "SELECT
                        DOCTMP.FTPchDocNo ,
                        DOCTMP.FNPcdSeqNo,   --ลำดับรายการ
                        DOCTMP.FNWrnSeq,    --ลำดับการบันทึกผลเคลม
                        '$nSeqRcv' AS FNRcvSeq,
                        '$tSPLCode' ,
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
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDRcvDate ,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTRcvUsrCode ,
                        '' AS FTRcvRefTwi ,
                        '' AS FDRcvRefDate ,
                        '$tADCode',
                        '$tBCHCode'
                    FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                    AND DOCTMP.FTPchDocNo   = '$tDocNo'
                    AND DOCTMP.FTXthDocKey  = '$tDocKey'
                    AND DOCTMP.FTSessionID  = '$tSessionID'
                    AND DOCTMP.FNPcdSeqNo   = '$nSeqNo'
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
                                UPD.FTPchDocNo, 
                                UPD.FNPcdSeqNo ,
                                UPD.FNRcvSeq ,
                                UPD.FNWrnSeq ,
                                UPD.FCRcvPdtQty ,
                                UPD.FTRcvPdtCode ,
                                UPD.FDRcvDate
                            FROM TCNTPdtClaimDTRcv UPD WITH(NOLOCK)
                            WHERE UPD.FTPchDocNo = '$tDocNo'
                        ) RES 
                        ON RES.FNWrnSeq = DOCTMP.FNWrnSeq
                        AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo
                        AND RES.FTPchDocNo = DOCTMP.FTPchDocNo ";
            $this->db->query($tSQL);
        }

    }   

    //อัพเดท สินค้าที่รับ(step3) , จำนวนรับ(step3)
    public function FSaMCLMUpdateInlineDTTempStep3($paDataUpdateDT,$paDataWhere ,$tTypeUpdate){
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
        
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FNPcdSeqNo',$nSeq);
        $this->db->where('FTPchDocNo',$tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //อัพเดท เลขที่เอกสาร ใบรับเข้า ลงใน Temp
    public function FSaMCLMUpdateDocTWIInTemp($paDataWhere){
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
                        FTPchDocNo ,
                        FNPcdSeqNo ,
                        FNWrnSeq ,
                        FNRcvSeq ,
                        FTRcvRefTwi ,
                        FDRcvRefDate
                    FROM TCNTPdtClaimDTRcv WITH(NOLOCK)
                    WHERE FTPchDocNo = '$tDocNo' AND
                    FTBchCode = '$tBCHCode'
                ) RES 
                ON RES.FTPchDocNo = DOCTMP.FTPchDocNo
                AND RES.FNWrnSeq = DOCTMP.FNWrnSeq
                AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo 
                AND RES.FNRcvSeq = DOCTMP.FNRcvSeq
                WHERE DOCTMP.FTXthDocKey = 'ClaimStep3' AND
                DOCTMP.FTSessionID = '$tSesSessionID' ";
        $this->db->query($tSQL);
    }

    //--------------------------------------- STEP 4 --------------------------------------------//

    //ช้อมูลในตาราง
    public function FSaMCLMListTableStep4($paDataWhere){
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
                                LEFT JOIN TCNMSpl_L SPLL ON DOCTMP.FTSPLCode = SPLL.FTSPLCode AND SPLL.FNLngID = $nLngID
                                LEFT JOIN TCNTPdtClaimDTTmp TMPWrn ON DOCTMP.FTPchDocNo = TMPWrn.FTPchDocNo AND DOCTMP.FNPcdSeqNo = TMPWrn.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPWrn.FTPdtCode AND TMPWrn.FTXthDocKey = '$tDocKey2'
                                WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                AND DOCTMP.FTXthDocKey = '$tDocKey'
                                AND DOCTMP.FTSessionID = '$tSesSessionID'
                                GROUP BY DOCTMP.FNPcdSeqNo ,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTPcdBarCode,
                                        DOCTMP.FCPcdQty,
                                        DOCTMP.FTPcdPdtName,
                                        DOCTMP.FTPunName,
                                        DOCTMP.FDPcdDateReq,
                                        SPLL.FTSplName ";

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
        return $aDataReturn;
    }

    //รายละเอียดข้อมูลของสินค้าตัวที่กดดู หรือตัวที่กำลังจะกดบันทึก ตาม SEQ
    public function FSaMCLMGetItemClaimBySeqStep4($paDataWhere){
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
                                LEFT JOIN TCNTPdtClaimDTTmp TMPRET ON DOCTMP.FTPchDocNo = TMPRET.FTPchDocNo AND DOCTMP.FNPcdSeqNo = TMPRET.FNPcdSeqNo AND DOCTMP.FTPdtCode = TMPRET.FTPdtCode AND TMPRET.FTXthDocKey = '$tDocKey2'
                                WHERE ISNULL(DOCTMP.FTPchDocNo,'')  = '$tDocNo'
                                AND DOCTMP.FTXthDocKey = '$tDocKey'
                                AND DOCTMP.FNPcdSeqNo = '$nSeqPDT'
                                AND DOCTMP.FTSessionID = '$tSesSessionID' ";
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
        return $aDataReturn;
    }

    //อัพเดท อัพเดทวันที่(step4) , อัพเดทหมายเหตุ(step4)
    public function FSaMCLMUpdateInlineDTTempStep4($paDataUpdateDT,$paDataWhere ,$tTypeUpdate){
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
        
        $this->db->where('FNRcvSeq',$nRcvSeq);
        $this->db->where('FNWrnSeq',$nWrnSeq);
        $this->db->where('FNPcdSeqNo',$nPcdSeq);
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FTPchDocNo',$tDocNo);
        $this->db->update('TCNTPdtClaimDTTmp');

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

    //บันทึกข้อมูลในตารางจริง
    public function FSaMCLMMoveTempToDTInSaveStep4($paDataWhere){

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
                    FTPchDocNo , FNPcdSeqNo , FNWrnSeq ,  FNRcvSeq ,
                    FNRetSeq , FTCstCode , FCRetPdtQty ,
                    FTRetRmk , FTRetUsrCode , FDRetDate ,
                    FTRetStaGenCNDN ,
                    FTAgnCode , FTBchCode ) ";
        $tSQL   .=  "SELECT
                    DOCTMP.FTPchDocNo ,
                    '$nSeq' AS FNPcdSeqNo ,
                    DOCTMP.FNWrnSeq ,
                    DOCTMP.FNRcvSeq ,
                    ROW_NUMBER() OVER(ORDER BY DOCTMP.FNRcvSeq ASC) AS FNRetSeq,
                    '$tCSTCode',
                    CASE
                        WHEN ISNULL(DOCTMP.FCRetPdtQty,0) = 0 THEN DOCTMP.FCWrnPdtQty
                        ELSE DOCTMP.FCRetPdtQty
                    END AS FCRetPdtQty ,
                    DOCTMP.FTRetRmk,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTRetUsrCode ,
                    DOCTMP.FDRetDate ,
                    '$nCreateCNDN',
                    '$tADCode',
                    '$tBCHCode'
                FROM TCNTPdtClaimDTTmp DOCTMP WITH (NOLOCK)
                WHERE 1 = 1
                AND DOCTMP.FTPchDocNo   = '$tDocNo'
                AND DOCTMP.FTXthDocKey  = '$tDocKey'
                AND DOCTMP.FNPcdSeqNo   = '$nSeq'
                AND DOCTMP.FTSessionID  = '$tSessionID'
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
                        DTRet.FTPchDocNo , 
                        DTRet.FNPcdSeqNo ,
                        DTRet.FNWrnSeq ,
                        DTRet.FNRcvSeq ,
                        DTRet.FCRetPdtQty ,
                        DTRet.FTRetRmk ,
                        DTRet.FDRetDate ,
                        DTRet.FTRetStaGenCNDN 
                    FROM TCNTPdtClaimDTRet DTRet WITH(NOLOCK)
                    WHERE DTRet.FTPchDocNo = '$tDocNo'
                ) RES 
            ON RES.FTPchDocNo = DOCTMP.FTPchDocNo
            AND RES.FNWrnSeq = DOCTMP.FNWrnSeq
            AND RES.FNPcdSeqNo = DOCTMP.FNPcdSeqNo 
            AND RES.FNRcvSeq = DOCTMP.FNRcvSeq ";
        $this->db->query($tSQL);
    } 

    //Get ข้อมูล API
    public function FSxMCLMGetConfigAPI(){
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
    public function FSaMCLMGetPDTInTempToArray($ptDocumentNumber, $ptBchCode){
        $tSessionID  = $this->session->userdata('tSesSessionID');

        //หาคลังขาย
        $tSQL       = " SELECT TOP 1 FTWahCode 
                        FROM TCNMWaHouse WHERE 
                        TCNMWaHouse.FTWahStaType ='1' AND 
                        TCNMWaHouse.FTBchCode = '$ptBchCode' 
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
                         WHERE TMP.FTPchDocNo = '$ptDocumentNumber' AND
                         TMP.FTPcdStaPick = 1 AND
                         TMP.FTSessionID = '$tSessionID'  ";
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

    //Functionality : ค้นหาข้อมูลลูกค้า - ทีอยู่
    //Parameters : Jobrequeststep1_controller
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : Array Data List
    //Return Type : Array
    public function FSaMCLMGetDataCustomerAddr($paDataCondition)
    {
        $nLngID     = $paDataCondition['nLangEdit'];
        $tCstCode   = $paDataCondition['tCstCode'];
        $tSQL       = "
            SELECT
                Addr.FTCstCode,
                Addr.FNLngID,
                Addr.FTAddGrpType,
                Addr.FNAddSeqNo,
                Addr.FTAddRefNo,
                Addr.FTAddName,
                Addr.FTAddRmk,
                Addr.FTAddVersion,
                Addr.FTAddV1No,
                Addr.FTAddV1Soi,
                Addr.FTAddV1Village,
                Addr.FTAddV1Road,
                Addr.FTAddV1SubDist AS FTAddV1SubDistCode,
                SUBL.FTSudName AS FTAddV1SubDistName,
                Addr.FTAddV1DstCode,
                DSTL.FTDstName AS FTAddV1DstName,
                Addr.FTAddV1PvnCode,
                PVNL.FTPvnName	AS FTAddV1PvnName,
                Addr.FTAddV1PostCode,
                Addr.FTAddTel,
                Addr.FTAddFax,
                Addr.FTAddV2Desc1,
                Addr.FTAddV2Desc2,
                Addr.FTAddWebsite,
                Addr.FTAddLongitude,
                Addr.FTAddLatitude
            FROM TCNMCstAddress_L Addr WITH(NOLOCK)
            LEFT JOIN TCNMSubDistrict_L SUBL WITH(NOLOCK) ON Addr.FTAddV1SubDist = SUBL.FTSudCode AND SUBL.FNLngID = '$nLngID'
            LEFT JOIN TCNMDistrict_L DSTL WITH(NOLOCK) ON Addr.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = '$nLngID'
            LEFT JOIN TCNMProvince_L PVNL WITH(NOLOCK) ON Addr.FTAddV1PvnCode	= PVNL.FTPvnCode AND PVNL.FNLngID = '$nLngID'
            WHERE 1=1
            AND Addr.FTCstCode = '$tCstCode'
            AND Addr.FNLngID = '1'
            AND Addr.FTAddGrpType = '1'
            AND Addr.FTAddRefNo	= '1';
        ";
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

}
