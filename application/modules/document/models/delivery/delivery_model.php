<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class delivery_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMDLVGetDataTableList($paDataCondition){
        // $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrm'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSQL                   = '';

        $tSQL  = "  SELECT TOP ". get_cookie('nShowRecordInPageList')." 
                    c.*,
                    COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY C.FTXthDocNo)  AS PARTITIONBYDOC, 
                    HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF',
                    CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF'
                    FROM( SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ) AS FNRowID,* FROM ( ";
        $tSQL  .= "  SELECT
                        HD.FTBchCode,
                        BCHL.FTBchName,
                        HD.FTXshDocNo           AS FTXthDocNo,
                        CONVERT(CHAR(10),HD.FDXshDocDate,103) AS FDXthDocDate,
                        CONVERT(CHAR(5), HD.FDXshDocDate,108) AS FTXthDocTime,
                        HD.FTXshStaDoc          AS FTXthStaDoc,
                        HD.FTXshStaApv          AS FTXthStaApv,
                        HD.FTCreateBy,
                        HD.FDCreateOn,
                        HD.FNXshStaDocAct,
                        HD.FNXshDocType,
                        USRL.FTUsrName          AS FTCreateByName
                    FROM TARTDoHD	                HD      WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L          BCHL    WITH (NOLOCK) ON HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID          = $nLngID
                    LEFT JOIN TCNMUser_L            USRL    WITH (NOLOCK) ON HD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID          = $nLngID
                    WHERE 1=1 ";

        if ( $this->session->userdata('tSesUsrLevel') != "HQ" ) {
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND HD.FTBchCode IN ($tBchCode) ";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchFrmBchCode'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchToBchCode'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((HD.FTXshDocNo LIKE '%$tSearchList%')
                          OR (BCHL.FTBchName LIKE '%$tSearchList%')
                          OR (CONVERT(CHAR(10),HD.FDXshDocDate,103) LIKE '%$tSearchList%') ) ";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == '3') {
                $tSQL .= " AND HD.FTXshStaDoc = '3' ";
            } elseif ($tSearchStaDoc == '2') {
                $tSQL .= " AND ISNULL(HD.FTXshStaApv,'') = '' AND HD.FTXshStaDoc != '3' ";
            } elseif ($tSearchStaDoc == '1') {
                $tSQL .= " AND HD.FTXshStaApv = '1' ";
            }
        }

        $tSQL  .=  ") Base) AS c 
        LEFT JOIN TARTDoHDDocRef HDDocRef_in WITH (NOLOCK) ON C.FTXthDocNo = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1
        ORDER BY c.FDCreateOn DESC ";

        $oQueryMain = $this->db->query($tSQL);
        if( $oQueryMain->num_rows() > 0 ){
            $oDataList          = $oQueryMain->result_array();
            $tSQLPage           = 0;
            $oQueryPage         = 0; //$this->db->query($tSQLPage);
            $nFoundRow          = 0; //$oQueryPage->num_rows();
            $nPageAll           = 0; //ceil($nFoundRow/$paDataCondition['nRow']);
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
        unset($oQueryMain);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // [ลบข้อมูล] เอกสาร HD
    public function FSnMDLVDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode   = $paDataDoc['tBchCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TARTDoHD');

        // Document DT
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TARTDoDT');

        // Document HD Cst
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TARTDoHDCst');

        //เอกสารอ้างอิง
        $this->db->where('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTDoHDDocRef');

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

    // เปิดมาหน้า ADD จะต้อง ลบเอกสารอ้างอิง ใน Temp โดย where session
    public function FSxMDLVClearDataInDocTemp(){
        $tSessionID = $this->session->userdata('tSesSessionID');

        //ลบข้อมูล HDDocRef
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', 'TARTDoHD');
        $this->db->delete('TCNTDocHDRefTmp');

        //ลบข้อมูล DT
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where('FTXthDocKey', 'TARTDoDT');
        $this->db->delete('TCNTDocDTTmp');
    }

    // ข้อมูลสินค้า ใน Temp
    public function FSaMDLVGetDocDTTempListPage($paDataWhere){
        $tDLVDocNo           = $paDataWhere['FTXthDocNo'];
        $tDLVDocKey          = $paDataWhere['FTXthDocKey'];
        $tDLVSesSessionID    = $this->session->userdata('tSesSessionID');
    
        $tSQL       = " SELECT
                            DLVTMP.FTBchCode,
                            DLVTMP.FTXthDocNo,
                            DLVTMP.FNXtdSeqNo,
                            DLVTMP.FTXthDocKey,
                            DLVTMP.FTPdtCode,
                            DLVTMP.FTXtdPdtName,
                            DLVTMP.FTPunName,
                            DLVTMP.FTXtdBarCode,
                            DLVTMP.FTPunCode,
                            DLVTMP.FCXtdFactor,
                            DLVTMP.FCXtdQty,
                            DLVTMP.FCXtdSetPrice,
                            DLVTMP.FCXtdAmtB4DisChg,
                            DLVTMP.FTXtdDisChgTxt,
                            DLVTMP.FCXtdNet,
                            DLVTMP.FCXtdNetAfHD,
                            DLVTMP.FTXtdStaAlwDis,
                            DLVTMP.FTTmpRemark,
                            DLVTMP.FCXtdVatRate,
                            DLVTMP.FTXtdVatType,
                            DLVTMP.FTSrnCode,
                            DLVTMP.FDLastUpdOn,
                            DLVTMP.FDCreateOn,
                            DLVTMP.FTLastUpdBy,
                            DLVTMP.FTCreateBy,
                            DLVTMP.FTXtdPdtSetOrSN,
                            DLVTMP.FCXtdQtyOrd,
                            DLVTMP.FTXtdRmk
                        FROM TCNTDocDTTmp DLVTMP WITH (NOLOCK)
                        WHERE DLVTMP.FTXthDocKey = '$tDLVDocKey' AND DLVTMP.FTSessionID = '$tDLVSesSessionID' ";

        if(isset($tDLVDocNo) && !empty($tDLVDocNo)){
            $tSQL   .=  " AND ISNULL(DLVTMP.FTXthDocNo,'')  = '$tDLVDocNo' ";
        }

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

    // ข้อมูลสินค้า
    public function FSaMDLVGetDataPdt($paDataPdtParams){
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
                            CAVG.FCPdtCostIn
                        FROM TCNMPdt PDT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
                        LEFT OUTER JOIN VCN_VatActive VAT WITH (NOLOCK) ON  PDT.FTVatCode = VAT.FTVatCode
                        LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
                        LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
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

    // เพิ่มข้อมูลลง temp
    public function FSaMDLVInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tDLVOptionAddPdt'] == 1) {
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
                            AND FTPdtCode       = '".$paDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo
                        ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "   UPDATE TCNTDocDTTmp
                                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."' ,
                                    FCXtdQtyAll = '".($aResult["FCXtdQty"] + 1 ) * $paDataPdt['FCPdtUnitFact']."'
                                    WHERE 1=1
                                    AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                                    AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                                    AND FNXtdSeqNo      = '".$aResult["FNXtdSeqNo"]."'
                                    AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                                    AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                                    AND FTPdtCode       = '".$paDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paDataPdt["FTBarCode"]."'
                                ";
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
                    'FTPdtCode'         => $paDataPdt['FTPdtCode'],
                    'FTXtdPdtName'      => $paDataPdt['FTPdtName'],
                    'FCXtdFactor'       => $paDataPdt['FCPdtUnitFact'],
                    'FTPunCode'         => $paDataPdt['FTPunCode'],
                    'FTPunName'         => $paDataPdt['FTPunName'],
                    'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                    'FTXtdVatType'      => $paDataPdt['FTPdtStaVatBuy'],
                    'FTVatCode'         => $paDataPdt['FTVatCode'],
                    'FCXtdVatRate'      => $paDataPdt['FCVatRate'],
                    'FTXtdStaAlwDis'    => $paDataPdt['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $paDataPdt['FTPdtSaleType'],
                    'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$paDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                    'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                    'FTLastUpdBy'       => $paDataPdtParams['tDLVUsrCode'],
                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                    'FTCreateBy'        => $paDataPdtParams['tDLVUsrCode'],
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
                'FTPdtCode'         => $paDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paDataPdt['FTPunCode'],
                'FTPunName'         => $paDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                'FTXtdVatType'      => $paDataPdt['FTPdtStaVatBuy'],
                'FTVatCode'         => $paDataPdt['FTVatCode'],
                'FCXtdVatRate'      => $paDataPdt['FCVatRate'],
                'FTXtdStaAlwDis'    => $paDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tDLVUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tDLVUsrCode'],
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
    
    // แก้ไขข้อมูล ตาม Seq
    public function FSaMDLVUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where_in('FTSessionID',$paDataWhere['tDLVSessionID']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nDLVSeqNo']);

        if ($paDataWhere['tDLVDocNo'] != '' && $paDataWhere['tDLVBchCode'] != '') {
            $this->db->where_in('FTXthDocNo',$paDataWhere['tDLVDocNo']);
            $this->db->where_in('FTBchCode',$paDataWhere['tDLVBchCode']);
        }

        $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);
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

    // ลบข้อมูลใน Temp
    public function FSnMDLVDelPdtInDTTmp($paDataWhere){
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDLVDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    // เช็คว่ามีใน DT เเล้วหรือยัง
    public function FSnMDLVChkPdtInDocDTTemp($paDataWhere){
        $tPAMDocNo       = $paDataWhere['FTXthDocNo'];
        $tPAMDocKey      = $paDataWhere['FTXthDocKey'];
        $tPAMSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT WITH(NOLOCK)
                            WHERE DocDT.FTXthDocKey   = '$tPAMDocKey'
                            AND DocDT.FTSessionID   = '$tPAMSessionID' ";
        if(isset($tPAMDocNo) && !empty($tPAMDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'')  = '$tPAMDocNo' ";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMDLVMoveHDRefTmpToHDRef($paDataWhere){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        $tTableHD     = 'TARTDoHD';

        // [ใบส่งของ]
        if (isset($tDocNo) && !empty($tDocNo)) {
            $this->db->where('FTBchCode', $tBchCode);
            $this->db->where('FTXshDocNo', $tDocNo);
            $this->db->delete('TARTDoHDDocRef');
        }

        //Insert HDDocRef ในตารางใบจัดสินค้า
        $tSQL   =   "   INSERT INTO TARTDoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '".$this->session->userdata("tSesUsrAgnCode")."' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthDocNo,
                            FTXthRefDocNo,
                            FTXthRefType,
                            FTXthRefKey,
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$tTableHD."'
                          AND FTSessionID = '$tSessionID' ";
        $this->db->query($tSQL);

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
                            'DLV',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$tTableHD."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'ABB'  ";
        $this->db->query($tSQL);
    }

    // เพิ่ม - แก้ไขข้อมูล TARTDoHD 
    public function FSxMDLVAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMDLVGetDataDocHD(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
            ));

            // update HD
            $this->db->where('FTBchCode',$paDataWhere['FTBchCode']);
            $this->db->where('FTXshDocNo',$paDataWhere['FTXshDocNo']);
            $this->db->update($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
            // Insert HD
            $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        }
        return;
    }

    // เพิ่ม - แก้ไขข้อมูล TARTDoHDCst 
    public function FSxMDLVAddUpdateHDCST($paDataHDCST,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataCST    =   $this->FSaMDLVGetDataDocHDCST(array(
            'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ));
        $aDataAddUpdateHDCST    = array();
        if(isset($aDataGetDataCST['rtCode']) && $aDataGetDataCST['rtCode'] == 1){
            $aDataHDCSTOld      = $aDataGetDataCST['raItems'];
            $aDataAddUpdateHDCST    = array_merge($paDataHDCST,array(
                'FTBchCode'     => $aDataHDCSTOld['FTBchCode'],
                'FTXshDocNo'    => $aDataHDCSTOld['FTXshDocNo'],
            ));

            // update HD
            $this->db->where('FTBchCode',$paDataWhere['FTBchCode']);
            $this->db->where('FTXshDocNo',$paDataWhere['FTXshDocNo']);
            $this->db->update($paTableAddUpdate['tTableHDCst'], $aDataAddUpdateHDCST);
        }else{
            $aDataAddUpdateHDCST    = array_merge($paDataHDCST,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXshDocNo'    => $paDataWhere['FTXshDocNo'],
            ));

            // Insert HD
            $this->db->insert($paTableAddUpdate['tTableHDCst'],$aDataAddUpdateHDCST);
        }
        return;
    }

    // อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp => ตารางจริง
    public function FSxMDLVAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey','TARTDoHD');
        $this->db->update('TCNTDocHDRefTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo']
        ));
        return;
    }

    // เพิ่ม - แก้ไขข้อมูล TARTDoDT => ตารางจริง
    public function FSaMDLVMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tDLVBchCode     = $paDataWhere['FTBchCode'];
        $tDLVDocNo       = $paDataWhere['FTXshDocNo'];
        $tDLVDocKey      = $paTableAddUpdate['tTableDT'];
        $tDLVSessionID   = $paDataWhere['FTSessionID'];

        if(isset($tDLVDocNo) && !empty($tDLVDocNo)){
            $this->db->where_in('FTXshDocNo',$tDLVDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." ( FTAgnCode , FTBchCode , FTXshDocNo, FNXsdSeqNo
                        , FTPdtCode , FTXsdPdtName , FTPunCode , FTPunName , FCXsdFactor
                        , FTXsdBarCode , FCXsdQty , FCXsdQtyAll , FTXsdStaPrcStk , FTXsdStaAlwDis 
                        , FTPdtStaSet , FTXsdRmk , FDLastUpdOn
                        , FTLastUpdBy , FDCreateOn, FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            '".$this->session->userdata("tSesUsrAgnCode")."' AS FTAgnCode,
                            TMP.FTBchCode,
                            TMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY TMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            TMP.FTPdtCode,
                            TMP.FTXtdPdtName,
                            TMP.FTPunCode,
                            TMP.FTPunName,
                            TMP.FCXtdFactor,
                            TMP.FTXtdBarCode,
                            TMP.FCXtdQty,
                            TMP.FCXtdQtyAll,
                            TMP.FTXtdStaPrcStk,
                            TMP.FTXtdStaAlwDis,
                            TMP.FTXtdPdtStaSet,
                            TMP.FTXtdRmk,
                            TMP.FDLastUpdOn,
                            TMP.FTLastUpdBy,
                            TMP.FDCreateOn,
                            TMP.FTCreateBy
                        FROM TCNTDocDTTmp TMP WITH (NOLOCK)
                        WHERE TMP.FTBchCode    = '$tDLVBchCode'
                          AND TMP.FTXthDocNo   = '$tDLVDocNo'
                          AND TMP.FTXthDocKey  = '$tDLVDocKey'
                          AND TMP.FTSessionID  = '$tDLVSessionID'
                        ORDER BY TMP.FNXtdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    // ข้อมูล HD
    public function FSaMDLVGetDataDocHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT
                            HD.FTAgnCode,
                            HD.FTBchCode,
                            BCHL.FTBchName          AS rtBchName_Create,
                            HD.FTXshDocNo,
                            HD.FNXshDocType,
                            HD.FDXshDocDate,
                            HD.FTUsrCode,
                            USRL.FTUsrName          AS rtUserName_Delivery,
                            HD.FTXshApvCode,
                            USRAPV.FTUsrName	    AS rtApvName ,
                            HD.FTCstCode,
                            CSTL.FTCstName          AS rtCstName ,
                            HD.FNXshDocPrint,
                            HD.FTXshRmk,
                            HD.FTXshStaDoc,
                            HD.FTXshStaApv,
                            HD.FTXshStaDelMQ,
                            HD.FTXshStaPrcStk,
                            HD.FNXshStaDocAct,
                            HD.FNXshStaRef,
                            HD.FTXshAgnFrm,
                            AGNF.FTAgnName          AS rtAgnName_From,
                            HD.FTXshBchFrm,
                            BCHFL.FTBchName         AS rtBchName_From,
                            HD.FTXshAgnTo,
                            AGNT.FTAgnName          AS rtAgnName_To,
                            HD.FTXshBchTo,
                            BCHTL.FTBchName         AS rtBchName_To,
                            HD.FDXshDeliveryDate,
                            HD.FTXshShipVia,
                            HD.FDCreateOn           AS rtDateOn,
                            HD.FTCreateBy,           
                            USRCRA.FTUsrName        AS rtUserName_Create
                        FROM TARTDoHD               HD      WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMBranch_L      BCHFL   WITH (NOLOCK)   ON HD.FTXshBchFrm   = BCHFL.FTBchCode   AND BCHFL.FNLngID	= $nLngID
                        LEFT JOIN TCNMBranch_L      BCHTL   WITH (NOLOCK)   ON HD.FTXshBchTo    = BCHTL.FTBchCode   AND BCHTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGNF    WITH (NOLOCK)   ON HD.FTXshAgnFrm   = AGNF.FTAgnCode    AND AGNF.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGNT    WITH (NOLOCK)   ON HD.FTXshAgnTo    = AGNT.FTAgnCode    AND AGNT.FNLngID	= $nLngID
                        LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK)   ON HD.FTCstCode     = CSTL.FTCstCode	AND CSTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON HD.FTUsrCode     = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON HD.FTXshApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRCRA	WITH (NOLOCK)   ON HD.FTCreateBy	= USRCRA.FTUsrCode	AND USRCRA.FNLngID	= $nLngID
                        WHERE HD.FTXshDocNo = '$tDocNo' ";

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

    // ข้อมูล HDCST
    public function FSaMDLVGetDataDocHDCST($paDataWhere){
        $tDocNo     = $paDataWhere['FTXshDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            HDCST.FTAgnCode,
                            HDCST.FTBchCode,
                            HDCST.FTXshDocNo,
                            HDCST.FTXshCardID,
                            HDCST.FTXshCardNo,
                            HDCST.FNXshCrTerm,
                            HDCST.FDXshDueDate,
                            HDCST.FDXshBillDue,
                            HDCST.FTXshCtrName,
                            HDCST.FDXshTnfDate,
                            HDCST.FTXshRefTnfID,
                            HDCST.FNXshAddrShip,
                            HDCST.FTXshAddrTax,
                            HDCST.FTCstCode ,
                            CST.FTCstTel,	
                            CST.FTCstEmail 	
                        FROM TARTDoHDCst HDCST  WITH (NOLOCK)
                        LEFT JOIN TCNMCst CST WITH (NOLOCK) ON HDCST.FTCstCode = CST.FTCstCode
                        WHERE HDCST.FTXshDocNo = ".$this->db->escape($tDocNo)." ";
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
        unset($tDocNo);
        unset($nLngID);
        unset($tSQL);
        unset($aDetail);
        unset($oQuery);
        return $aResult;
    }

    // อนุมัตเอกสาร
    public function FSxMDLVApproveDocument($paDataUpdate){
        $this->db->set('FDLastUpdOn',$paDataUpdate['FDLastUpdOn']);
        $this->db->set('FTLastUpdBy',$paDataUpdate['FTXshUsrApv']);
        $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
        $this->db->set('FTXshApvCode',$paDataUpdate['FTXshUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTDoHD');
    }

    // [เอกสารอ้างอิง] อ้างอิงเอกสาร table
    public function FSaMDLVGetDataHDRefTmp($paData){
        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT TMP.FTXthDocNo, TMP.FTXthRefDocNo, TMP.FTXthRefType, TMP.FTXthRefKey, TMP.FDXthRefDocDate 
                    FROM $tTableTmpHDRef TMP WITH(NOLOCK)
                    WHERE TMP.FTXthDocNo  = '$FTXthDocNo'
                      AND TMP.FTXthDocKey = '$FTXthDocKey'
                      AND TMP.FTSessionID = '$FTSessionID'
                    ORDER BY TMP.FDCreateOn DESC ";
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

    // [เอกสารอ้างอิง] อ้างอิงเอกสารใบขาย HD
    public function FSoMDLVCallRefIntDoc_SALE_DataTable($paDataCondition){
        $aRowLen                  = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                   = $paDataCondition['FNLngID'];
        $aAdvanceSearch           = $paDataCondition['aAdvanceSearch'];
        $tDLVRefIntBchCode        = $aAdvanceSearch['tDLVRefIntBchCode'];
        $tDLVRefIntDocNo          = $aAdvanceSearch['tDLVRefIntDocNo'];
        $tDLVRefIntDocDateFrm     = $aAdvanceSearch['tDLVRefIntDocDateFrm'];
        $tDLVRefIntDocDateTo      = $aAdvanceSearch['tDLVRefIntDocDateTo'];
        $tDLVRefIntStaDoc         = $aAdvanceSearch['tDLVRefIntStaDoc'];
        $tCstCode                 = $aAdvanceSearch['tCstCode'];

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

        //บิลขายตามรหัสลูกค้า
        if(isset($tCstCode) && !empty($tCstCode)){
            $tSQLMain .= " AND (SAHD.FTCstCode = '$tCstCode')";
        }
           
        if(isset($tDLVRefIntBchCode) && !empty($tDLVRefIntBchCode)){
            $tSQLMain .= " AND (SAHD.FTBchCode = '$tDLVRefIntBchCode' OR SAHD.FTBchCode = '$tDLVRefIntBchCode')";
        }else {
          if ($this->session->userdata("tSesUsrLevel") != 'HQ') {
            $tSesUsrBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLMain .= " AND SAHD.FTBchCode IN ($tSesUsrBchCodeMulti) ";
          }
        }

        if(isset($tDLVRefIntDocNo) && !empty($tDLVRefIntDocNo)){
            $tSQLMain .= " AND (SAHD.FTXshDocNo LIKE '%$tDLVRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tDLVRefIntDocDateFrm) && !empty($tDLVRefIntDocDateTo)){
            $tSQLMain .= " AND ((SAHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tDLVRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tDLVRefIntDocDateTo 23:59:59')) OR (SAHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tDLVRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tDLVRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tDLVRefIntStaDoc) && !empty($tDLVRefIntStaDoc)){
            if ($tDLVRefIntStaDoc == 3) {
                $tSQLMain .= " AND SAHD.FTXshStaDoc = '$tDLVRefIntStaDoc'";
            } elseif ($tDLVRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(SAHD.FTXshStaApv,'') = '' AND SAHD.FTXshStaDoc != '3'";
            } elseif ($tDLVRefIntStaDoc == 1) {
                $tSQLMain .= " AND SAHD.FTXshStaApv = '$tDLVRefIntStaDoc'";
            }
        }

        $tSQL   =   "SELECT c.* FROM(
                     SELECT ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                     ( $tSQLMain ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

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

    // [เอกสารอ้างอิง] อ้างอิงเอกสารใบขาย DT
    public function FSoMDLVCallRefIntDocDT_SALE_DataTable($paData){

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
                        DT.FCXsdQtyLef  AS FCXpdQty,
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

    // [เอกสารอ้างอิง] นำข้อมูลจากใบขาย ลง DTTemp
    public function FSoMDLVCallRefIntDocInsert_SALE_DTToTemp($paData){

        $tDLVDocNo          = $paData['tDLVDocNo'];
        $tDLVFrmBchCode     = $paData['tDLVFrmBchCode'];
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $tInsertOrUpdateRow = $paData['tInsertOrUpdateRow'];
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';

        if($tInsertOrUpdateRow == 1){ //บวก QTY
            // $nQTY       = "DT.FCXsdQty";
            // $nQTYAll    = "DT.FCXsdQtyAll";
            // $nQTYOrd    = "DT.FCXsdQty";
        }else{ //ขึ้น row ใหม่
            // $nQTY       = "DT.FCXsdQty";
            // $nQTYAll    = "DT.FCXsdQtyAll";
            // $nQTYOrd    = "DT.FCXsdQty";
        }

        $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tDLVFrmBchCode'   AS FTBchCode,
                    '$tDLVDocNo'        AS FTXphDocNo,
                    DT.FNXsdSeqNo,
                    'TARTDoDT'          AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXsdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXsdFactor,
                    DT.FTXsdBarCode,
                    DT.FCXsdQty         AS FCXtdQty,
                    DT.FCXsdQtyAll      AS FCXtdQtyAll,
                    0                   AS FCXsdQtyLef,
                    0                   AS FCXpdQtyRfn,
                    ''                  AS FTXpdStaPrcStk,
                    1                   AS FTXtdStaAlwDis,
                    0                   AS FNXpdPdtLevel,
                    ''                  AS FTXpdPdtParent,
                    0                   AS FCXpdQtySet,
                    ''                  AS FTPdtStaSet,
                    ''                  AS FTXpdRmk,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TPSTSalDT DT WITH (NOLOCK)
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN $aSeqNo ";
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
    
    // [เอกสารอ้างอิง] เพิ่ม
    public function FSaMDLVAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tRefDocNo = $paDataAddEdit['FTXthRefDocNo'];
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

    // [เอกสารอ้างอิง] ลบ
    public function FSaMDLVDelHDDocRef($paData){
        $tDocNo       = $paData['FTXshDocNo'];
        $tRefDocNo    = $paData['FTXshRefDocNo'];
        $tDocKey      = $paData['FTXshDocKey'];
        $tSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey',$tDocKey);
        $this->db->where('FTXthRefDocNo',$tRefDocNo);
        $this->db->where('FTXthDocNo',$tDocNo);
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

    // ย้ายจาก DT To Temp
    public function FSxMDLVMoveDTToDTTemp($paDataWhere){
        $tDLVDocNo      = $paDataWhere['FTXshDocNo'];
        $tDocKey        = $paDataWhere['FTXshDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tDLVDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,FTXtdStaPrcStk,FTXtdStaAlwDis,
                        FTXtdPdtStaSet,FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                    SELECT
                        DT.FTBchCode , 
                        DT.FTXshDocNo, 
                        DT.FNXsdSeqNo, 
                        CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                        DT.FTPdtCode , 
                        DT.FTXsdPdtName , 
                        DT.FTPunCode , 
                        DT.FTPunName , 
                        DT.FCXsdFactor, 
                        DT.FTXsdBarCode , 
                        DT.FCXsdQty , 
                        DT.FCXsdQtyAll , 
                        DT.FTXsdStaPrcStk , 
                        DT.FTXsdStaAlwDis , 
                        DT.FTPdtStaSet , 
                        DT.FTXsdRmk , 
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TARTDoDT DT WITH(NOLOCK)
                    WHERE DT.FTXshDocNo = '$tDLVDocNo'
                    ORDER BY DT.FNXsdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    // ย้ายจาก HDDocRef To Temp
    public function FSxMDLVMoveHDRefToHDRefTemp($paData){

        $tDocNo     = $paData['FTXshDocNo'];
        $tSessionID = $this->session->userdata('tSesSessionID');
        $tSQL       = " INSERT INTO TCNTDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL      .= " SELECT
                          FTXshDocNo,
                          FTXshRefDocNo,
                          FTXshRefType,
                          FTXshRefKey,
                          FDXshRefDocDate,
                          'TARTDoHD' AS FTXthDocKey,
                          '$tSessionID'  AS FTSessionID,
                          CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                      FROM TARTDoHDDocRef WITH(NOLOCK)
                      WHERE FTXshDocNo = '$tDocNo' ";
        $this->db->query($tSQL);

    }
    
    // ยกเลิกเอกสาร
    public function FSxMDLVCancelDocument($paData){

        //อัพเดทให้ เป็นยกเลิก
        $this->db->set('FDLastUpdOn',$paData['FDLastUpdOn']);
        $this->db->set('FTLastUpdBy',$paData['FTLastUpdBy']);
        $this->db->set('FTXshStaDoc',3); //ยกเลิก
        $this->db->where('FTXshDocNo',$paData['tDocNo']);
        $this->db->update('TARTDoHD');

        //ลบการอ้างอิงออกจากใบขาย (ย้ายไป ลบตอน หลังอนุมัติที่ C# แทน เพราะต้องหา QTY แบบย้อนกลับ)
        // $this->db->where('FTXshRefDocNo',$paData['tDocNo']);
        // $this->db->delete('TPSTSalHDDocRef');

        //ลบการอ้างอิงออก TARTDoHDDocRef (ย้ายไป ลบตอน หลังอนุมัติที่ C# แทน เพราะต้องหา QTY แบบย้อนกลับ)
        // $this->db->where('FTXshDocNo',$paData['tDocNo']);
        // $this->db->delete('TARTDoHDDocRef');
    }

    // อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMDLVUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshRmk',$paDataUpdate['FTXshRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTDoHD');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update.',
            );
        }
        return $aStatus;
    }

    //////////////////////////// ไปดึงข้อมูลที่อยู่สำหรับจัดส่ง / ที่อยู่สำหรับออกใบกำกับภาษี /////////////////////////////

    // หา config ของที่อยู่
    public function FSnMDLVGetConfigShwAddress(){
        $tSQL = "   SELECT 
                        CASE WHEN ISNULL(FTSysStaUsrValue,'') = '' THEN FTSysStaDefValue ELSE FTSysStaUsrValue END nStaShwAddr
                    FROM TSysConfig WITH(NOLOCK) 
                    WHERE FTSysCode = 'tCN_AddressType' 
                    AND FTSysApp = 'CN' 
                    AND FTSysKey = 'TCNMBranch' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->result_array();
            $nResult   = $aDataList[0]['nStaShwAddr'];
        } else {
            $nResult   = 1;
        }
        return $nResult;
    }

    // ข้อมูลที่อยู่จัดส่ง
    public function FSaMDLVGetDataAddress($paDataWhere){
         $tDocNo     = $paDataWhere['FTXshDocNo'];
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
                         FROM TARTDoHDCst             HDCST          WITH (NOLOCK)
                         LEFT JOIN TCNMAddress_L      ADD_L_SHIP     WITH (NOLOCK)   ON HDCST.FNXshAddrShip          = ADD_L_SHIP.FNAddSeqNo     AND ADD_L_SHIP.FNLngID     = $nLngID
                         LEFT JOIN TCNMProvince_L     PRO_SHIP       WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1PvnCode    = PRO_SHIP.FTPvnCode        AND PRO_SHIP.FNLngID       = $nLngID
                         LEFT JOIN TCNMDistrict_L     DIS_SHIP       WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1DstCode    = DIS_SHIP.FTDstCode        AND DIS_SHIP.FNLngID       = $nLngID
                         LEFT JOIN TCNMSubDistrict_L  SUBDIS_SHIP    WITH (NOLOCK)   ON ADD_L_SHIP.FTAddV1SubDist    = SUBDIS_SHIP.FTSudCode     AND SUBDIS_SHIP.FNLngID    = $nLngID   
                         WHERE 1=1 AND HDCST.FTXshDocNo = '$tDocNo' ";
 
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
}
