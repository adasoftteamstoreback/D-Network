<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mSaleOrder extends CI_Model {

    // List ข้อมูล
    public function FSaMSOGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaApprove'];
        $tSearchStaSale         = $aAdvanceSearch['tSearchStaSale'];
        ///ORDER BY FDCreateOn DESC ,FTXshDocNo DESC  OLD
        
        $tSQL   =   "   SELECT 			
                            A.* , 
                            COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY A.FTXshDocNo)  AS PARTITIONBYDOC  , 
                            HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF' ,
                            CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF' 
                        FROM ( ";
        $tSQL   .=   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FTBchCode ASC, FTXshDocNo DESC,FDCreateOn DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        SOHD.FTBchCode,
                                        SOHD.FTXshStaPrcDoc,
                                        BCHL.FTBchName,
                                        SOHD.FTXshDocNo,
                                        CONVERT(CHAR(10),SOHD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), SOHD.FDXshDocDate,108) AS FTXshDocTime,
                                        SOHD.FTXshStaDoc,
                                        SOHD.FTXshStaApv,
                                        SOHD.FNXshStaRef,
                                        SOHD.FTCreateBy,
                                        SOHD.FDCreateOn,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        SOHD.FTXshApvCode,
                                        USRLAPV.FTUsrName   AS FTXshApvName,
                                        SALE.FTXshRefDocNo  AS 'SALEABB',
                                        CSTL.FTCstName
                                    FROM TARTSoHD               SOHD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON SOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON SOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRLAPV WITH (NOLOCK) ON SOHD.FTXshApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK) ON SOHD.FTCstCode     = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                                    LEFT JOIN TARTSoHDDocRef    SALE    WITH (NOLOCK) ON SOHD.FTXshDocNo    = SALE.FTXshDocNo   AND SALE.FTXshRefType = 2 AND SALE.FTXshRefKey = 'ABB' 
                                WHERE 1=1 ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND SOHD.FTBchCode IN ($tBchCode) ";
        }

        // Check User Login Branch
        // if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
        //     $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
        //     $tSQL   .= " AND SOHD.FTBchCode = '$tUserLoginBchCode' ";
        // }

        // // Check User Login Shop
        // if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
        //     $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
        //     $tSQL   .= " AND SOHD.FTShpCode = '$tUserLoginShpCode' ";
        // }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SOHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SOHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SOHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (SOHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDocAct ) && !empty($tSearchStaDocAct)){
            if ($tSearchStaDocAct == 3) {
                $tSQL .= " AND SOHD.FTXshStaDoc = '$tSearchStaDocAct'";
            } elseif ($tSearchStaDocAct == 2) {
                $tSQL .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaDocAct'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 0";
            }
        }

        // ค้นหาสถานะการขาย
        if (!empty($tSearchStaSale) && ($tSearchStaSale != "0")) {
            if ($tSearchStaSale == 2) {
                $tSQL .= " AND ISNULL(SALE.FTXshRefDocNo,'') <> '' ";
            }else{
                $tSQL .= " AND ISNULL(SALE.FTXshRefDocNo,'') = '' ";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL   .= " ) AS A LEFT JOIN TARTSoHDDocRef HDDocRef_in WITH (NOLOCK) ON A.FTXshDocNo = HDDocRef_in.FTXshDocNo AND (HDDocRef_in.FTXshRefType = 1 OR HDDocRef_in.FTXshRefType = 2)  ";
        $tSQL   .= " ORDER BY A.FNRowID ASC " ;

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMSOCountPageDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
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

    // จำนวน
    public function FSnMSOCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaApprove'];
        $tSearchStaSale         = $aAdvanceSearch['tSearchStaSale'];

        $tSQL   =   "   SELECT COUNT (SOHD.FTXshDocNo) AS counts
                        FROM TARTSoHD SOHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON SOHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TARTSoHDDocRef    SALE    WITH (NOLOCK) ON SOHD.FTXshDocNo = SALE.FTXshDocNo AND SALE.FTXshRefType = 2 AND SALE.FTXshRefKey = 'ABB' 
                        WHERE 1=1  ";

        // Check User Login Branch
        // if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
        //     $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
        //     $tSQL   .= " AND SOHD.FTBchCode = '$tUserLoginBchCode' ";
        // }

        // // Check User Login Shop
        // if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
        //     $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
        //     $tSQL   .= " AND SOHD.FTShpCode = '$tUserLoginShpCode' ";
        // }

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND SOHD.FTBchCode IN ($tBchCode) ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SOHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SOHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SOHD.FTBchCode BETWEEN $tSearchBchCodeFrom AND $tSearchBchCodeTo) OR (SOHD.FTBchCode BETWEEN $tSearchBchCodeTo AND $tSearchBchCodeFrom))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDocAct ) && !empty($tSearchStaDocAct)){
            if ($tSearchStaDocAct == 3) {
                $tSQL .= " AND SOHD.FTXshStaDoc = '$tSearchStaDocAct'";
            } elseif ($tSearchStaDocAct == 2) {
                $tSQL .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaDocAct'";
            }
        }

        // ค้นหาสถานะการขาย
        if (!empty($tSearchStaSale) && ($tSearchStaSale != "0")) {
            if ($tSearchStaSale == 2) {
                $tSQL .= " AND ISNULL(SALE.FTXshRefDocNo,'') <> '' ";
            }else{
                $tSQL .= " AND ISNULL(SALE.FTXshRefDocNo,'') = '' ";
            }
        }

        /// ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // ลบข้อมูล
    public function FSnMSODelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoHD');

        // Document HD Cst
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoHDCst');

        // Document HD Discount
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoHDDis');
        
        // Document DT
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoDT');

        // Document DT Discount
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoDTDis');

        $this->db->where_in('FTDatRefCode',$tDataDocNo);
        $this->db->delete('TARTDocApvTxn');

        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TARTSoHDDocRef');

        $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
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

    // Clear ข้อมูล
    public function FSxMSOClearDataInDocTemp($paWhereClearTemp){
        $tSODocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tSODocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tSOSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tSODocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tSODocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tSOSessionID' ";
        $this->db->query($tClearDocTemp);


        // Query Delete Doc HD Discount Temp
        $tClearDocHDDisTemp =   "   DELETE FROM TCNTDocHDDisTmp
                                    WHERE 1=1
                                    AND TCNTDocHDDisTmp.FTXthDocNo  = '$tSODocNo'
                                    AND TCNTDocHDDisTmp.FTSessionID = '$tSOSessionID' ";
        $this->db->query($tClearDocHDDisTemp);

        // Query Delete Doc DT Discount Temp
        $tClearDocDTDisTemp =   "   DELETE FROM TCNTDocDTDisTmp
                                    WHERE 1=1
                                    AND TCNTDocDTDisTmp.FTXthDocNo  = '$tSODocNo'
                                    AND TCNTDocDTDisTmp.FTSessionID = '$tSOSessionID' ";
        $this->db->query($tClearDocDTDisTemp);

        // Query Delete DocRef Temp
        $tClearDocDocRefTemp =   "   DELETE FROM TCNTDocHDRefTmp
                                    WHERE 1=1
                                    AND TCNTDocHDRefTmp.FTXthDocNo  = '$tSODocNo'
                                    AND TCNTDocHDRefTmp.FTSessionID = '$tSOSessionID' ";
        $this->db->query($tClearDocDocRefTemp);
    
    }

    // Get ShopCode From User Login
    public function FSaMSOGetShpCodeForUsrLogin($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tUsrLogin  = $paDataShp['tUsrLogin'];
        $tSQL       = " SELECT
                            UGP.FTBchCode,
                            BCHL.FTBchName,
                            MER.FTMerCode,
                            MERL.FTMerName,
                            UGP.FTShpCode,
                            SHPL.FTShpName,
                            SHP.FTShpType,
                            SHP.FTWahCode   AS FTWahCode,
                            WAHL.FTWahName  AS FTWahName
                        FROM TCNTUsrGroup           UGP     WITH (NOLOCK)
                        LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode 
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
                        LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
                        LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
                        WHERE UGP.FTUsrCode = '$tUsrLogin' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($oQuery);
        return $aResult;
    }

    // Get Data Config WareHouse TSysConfig
    public function FSaMSOGetDefOptionConfigWah($paConfigSys){
        $tSysCode       = $paConfigSys['FTSysCode'];
        $nSysSeq        = $paConfigSys['FTSysSeq'];
        $nLngID         = $paConfigSys['FNLngID'];
        $aDataReturn    = array();

        $tSQLUsrVal = " SELECT
                            SYSCON.FTSysStaUsrValue AS FTSysWahCode,
                            WAHL.FTWahName          AS FTSysWahName
                        FROM TSysConfig SYSCON          WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaUsrValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                        LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
                        WHERE 1=1
                        AND SYSCON.FTSysCode    = '$tSysCode'
                        AND SYSCON.FTSysSeq     = $nSysSeq
        ";
        $oQuery1    = $this->db->query($tSQLUsrVal);
        if($oQuery1->num_rows() > 0){
            $aDataReturn    = $oQuery1->row_array();
        }else{
            $tSQLUsrDef =   "   SELECT
                                    SYSCON.FTSysStaDefValue AS FTSysWahCode,
                                    WAHL.FTWahName          AS FTSysWahName
                        FROM TSysConfig SYSCON          WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaDefValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                        LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
                        WHERE 1=1
                        AND SYSCON.FTSysCode    = '$tSysCode'
                        AND SYSCON.FTSysSeq     = $nSysSeq
            ";
            $oQuery2    = $this->db->query($tSQLUsrDef);
            if($oQuery2->num_rows() > 0){
                $aDataReturn    = $oQuery2->row_array();
            }
        }
        unset($oQuery1);
        unset($oQuery2);
        return $aDataReturn;
    }

    // สินค้าใน DT Temp
    public function FSaMSOGetDocDTTempListPage($paDataWhere){
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');
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
                                            DOCTMP.FCXtdVatRate,
                                            DOCTMP.FTXtdVatType,
                                            DOCTMP.FDLastUpdOn,
                                            DOCTMP.FDCreateOn,
                                            DOCTMP.FTLastUpdBy,
                                            DOCTMP.FTCreateBy
                                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                        WHERE 1 = 1
                                        AND DOCTMP.FTXthDocNo  = '$tSODocNo'
                                        AND DOCTMP.FTXthDocKey = '$tSODocKey'
                                        AND DOCTMP.FTSessionID = '$tSOSesSessionID' 
                                ) Base) AS c ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aFoundRow  = 1;
            $nFoundRow  = 1;
            $nPageAll   = ceil($nFoundRow/$paDataWhere['nRow']);
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => 0,
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

    // ยอดรวม
    public function FSaMSOSumDocDTTemp($paDataWhere){
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT
                                    SUM(FCXtdNetAfHD)       AS FCXtdSumNetAfHD,
                                    SUM(FCXtdAmtB4DisChg)   AS FCXtdSumAmtB4DisChg
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                WHERE 1 = 1 ";
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tSODocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tSODocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tSOSesSessionID' ";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
            $aDataReturn    =  array(
                'raDataSum' => $aResult,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Sum Empty',
            );
        }
        unset($oQuery);
        unset($aResult);
        return $aDataReturn;
    }

    // หา Seq ล่าสุด
    public function FSaMSOGetMaxSeqDocDTTemp($paDataWhere){
        $tSOBchCode         = $paDataWhere['FTBchCode'];
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL   =   "   SELECT 
                            MAX(DOCTMP.FNXtdSeqNo) AS rnMaxSeqNo
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1 ";
        $tSQL   .= " AND DOCTMP.FTBchCode   = '$tSOBchCode'";
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tSODocNo'";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tSODocKey'";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tSOSesSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['rnMaxSeqNo'];
        }else{
            $nResult    = 0;
        }
        return empty($nResult)? 0 : $nResult;
    }

    // ข้อมูลสินค้า
    public function FSaMSOGetDataPdt($paDataPdtParams){
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

    // เพิ่มสินค้าใน Temp
    public function FSaMSOInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if($paDataPdtParams['tSOOptionAddPdt'] == 1){
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
                            AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo
                        ";
                        // echo $tSQL.'<br>';
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
                                    AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
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
                    'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                    'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                    'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                    'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                    'FTPunName'         => $paPIDataPdt['FTPunName'],
                    'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                    // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                    'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                    'FTVatCode'         => $paPIDataPdt['FTVatCode'],
                    'FCXtdVatRate'      => $paPIDataPdt['FCVatRate'],
                    'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                    'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                    'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                    // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                    'FTSessionID'       => $paDataPdtParams['tSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                    'FTLastUpdBy'       => $paDataPdtParams['tSOUsrCode'],
                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                    'FTCreateBy'        => $paDataPdtParams['tSOUsrCode'],
                );
                $this->db->insert('TCNTDocDTTmp',$aDataInsert);

                // $this->db->last_query();  
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
                'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                'FTPunName'         => $paPIDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                'FTVatCode'         => $paPIDataPdt['FTVatCode'],
                'FCXtdVatRate'      => $paPIDataPdt['FCVatRate'],
                'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paPIDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tSOUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tSOUsrCode'],
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

    // แก้ไขจำนวน
    public function FSaMSOUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->set('FCXtdQty', $paDataUpdateDT['FCXtdQty']);
        $this->db->set('FCXtdSetPrice', $paDataUpdateDT['FCXtdSetPrice']);
        $this->db->set('FCXtdNet', $paDataUpdateDT['FCXtdNet']);
        $this->db->where('FTSessionID',$paDataWhere['tSOSessionID']);
        $this->db->where('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where('FNXtdSeqNo',$paDataWhere['nSOSeqNo']);
        $this->db->where('FTXthDocNo',$paDataWhere['tSODocNo']);
        $this->db->where('FTBchCode',$paDataWhere['tSOBchCode']);
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

    // เช็คสินค้าใน Temp
    public function FSnMSOChkPdtInDocDTTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        $tSODocKey      = $paDataWhere['FTXthDocKey'];
        $tSOSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocNo    = '$tSODocNo'
                            AND DocDT.FTXthDocKey   = '$tSODocKey'
                            AND DocDT.FTSessionID   = '$tSOSessionID' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // ลบข้อมูลสินค้าตัวเดียว
    public function FSnMSODelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        // Delete Doc DT Temp
        $this->db->where_in('FNXtdStaDis',1);
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTDisTmp');
        return ;
    }

    // ลบข้อมูลสินค้าหลายตัว
    public function FSnMSODelMultiPdtInDTTmp($paDataWhere){
        $tSessionID = $this->session->userdata('tSesSessionID');

        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['aDataSeqNo']);
        $this->db->where_in('FTPdtCode',$paDataWhere['aDataPdtCode']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        // Delete Doc DT Temp
        $this->db->where_in('FNXtdStaDis',1);
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['aDataSeqNo']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTDisTmp');
        return ;
    }

    // คำนวณ DT
    public function FSaMSOCalInDTTemp($paParams){
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tDataVatInOrEx = $paParams['tDataVatInOrEx'];

        $tSQL       = " SELECT
                            /* ยอดรวม ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXshTotal,

                            /* ยอดรวมสินค้าไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalNV,

                            /* ยอดรวมสินค้าห้ามลด ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXshTotalNoDis,

                            /* ยอมรวมสินค้าลดได้ และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END) AS FCXshTotalB4DisChgV,

                            /* ยอมรวมสินค้าลดได้ และไม่มีภาษี */
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END) AS FCXshTotalB4DisChgNV,

                            /* ยอดรวมหลังลด และมีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXshTotalAfDisChgV,

                            /* ยอดรวมหลังลด และไม่มีภาษี ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXshTotalAfDisChgNV,

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
                            ) AS FCXshAmtV,

                            /* ยอดรวมเฉพาะไม่มีภาษี ==============================================================*/
                            (
                                SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END)
                                -
                                (
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END)
                                    -
                                    SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END)
                                )
                            ) AS FCXshAmtNV,

                            /* ยอดภาษี ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXshVat,

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
                            ) AS FCXshVatable,

                            /* รหัสอัตราภาษี ณ ที่จ่าย ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                                FROM TCNTDocDTTmp DOCCONCAT
                                WHERE  1=1 
                                AND DOCCONCAT.FTBchCode = '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                                AND DOCCONCAT.FTSessionID = '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXshWpCode,

                            /* ภาษีหัก ณ ที่จ่าย ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdWhtAmt, 0)) AS FCXshWpTax

                        FROM TCNTDocDTTmp DTTMP
                        WHERE DTTMP.FTXthDocNo  = '$tDocNo' 
                        AND DTTMP.FTXthDocKey   = '$tDocKey' 
                        AND DTTMP.FTSessionID   = '$tSessionID'
                        AND DTTMP.FTBchCode     = '$tBchCode'
                        GROUP BY DTTMP.FTSessionID ";

                        // echo $tSQL;
                        // die();
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->result_array();
        }else{
            $aResult    = [];
        }
        return $aResult;
    }

    // คำนวณส่วนลด HDDis
    public function FSaMSOCalInHDDisTemp($paParams){
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
                            FOR XML PATH('')), 1, 1, '') AS FTXshDisChgTxt,
                            /* มูลค่ารวมส่วนลด ==============================================================*/
                            SUM( 
                                CASE 
                                    WHEN HDDISTMP.FTXtdDisChgType = 1 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    WHEN HDDISTMP.FTXtdDisChgType = 2 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    ELSE 0 
                                END
                            ) AS FCXshDis,
                            /* มูลค่ารวมส่วนชาร์จ ==============================================================*/
                            SUM( 
                                CASE 
                                    WHEN HDDISTMP.FTXtdDisChgType = 3 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    WHEN HDDISTMP.FTXtdDisChgType = 4 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    ELSE 0 
                                END
                            ) AS FCXshChg
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
    
    // บันทึก - เพิ่มข้อมูล HD
    public function FSxMSOAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMSOGetDataDocHD(array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->input->post("ohdSOLangEdit")
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

        // ลบข้อมูลก่อน
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // เพิ่มข้อมูล
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);
        return;
    }

    // บันทึก - เพิ่มข้อมูล HDCst
    public function FSxMSOAddUpdateHDCst($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMSOGetDataDocHD(array(
            'FTXthDocNo'    => $paDataWhere['FTXshDocNo'],
            'FNLngID'       => $this->input->post("ohdSOLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataAddUpdateHD   = array(
                'FTBchCode'             => $paDataWhere['FTBchCode'],
                'FTXshDocNo'            => $paDataWhere['FTXshDocNo'],
                'FTXshCardID'           => $paDataMaster['FTXshCardID'],
                'FTXshCstName'          => $paDataMaster['FTXshCstName'],
                'FTXshCstTel'           => $paDataMaster['FTXshCstTel'],
                'FNXshCrTerm'           => $paDataMaster['FNXshCrTerm'], 
                'FDXshDueDate'          => $paDataMaster['FDXshDueDate'],
                'FNXshAddrShip'         => $paDataMaster['FNXshAddrShip'],
                'FTCarCode'             => $paDataMaster['FTCarCode'],
                'FTXshCstRef'           => $paDataMaster['FTXshCstRef'],
                'FTXshStaAlwPosCalSo'   => $paDataMaster['FTXshStaAlwPosCalSo']
            );
        }else{
            $aDataAddUpdateHD   = array(
                'FTBchCode'             => $paDataWhere['FTBchCode'],
                'FTXshDocNo'            => $paDataWhere['FTXshDocNo'],
                'FTXshCardID'           => $paDataMaster['FTXshCardID'],
                'FTXshCstName'          => $paDataMaster['FTXshCstName'],
                'FTXshCstTel'           => $paDataMaster['FTXshCstTel'],
                'FNXshCrTerm'           => $paDataMaster['FNXshCrTerm'], 
                'FDXshDueDate'          => $paDataMaster['FDXshDueDate'],
                'FNXshAddrShip'         => $paDataMaster['FNXshAddrShip'],
                'FTCarCode'             => $paDataMaster['FTCarCode'],
                'FTXshCstRef'           => $paDataMaster['FTXshCstRef'],
                'FTXshStaAlwPosCalSo'   => $paDataMaster['FTXshStaAlwPosCalSo']
            );
        }

        // Delete SO HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXshDocNo',$aDataAddUpdateHD['FTXshDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDCst']);

        // Insert SO HD Cst
        $this->db->insert($paTableAddUpdate['tTableHDCst'],$aDataAddUpdateHD);
        return;
    }

    // Update DocNo In Doc Temp
    public function FSxMSOAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableHD']);
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
         $this->db->where('FTXthDocKey','TARTSoHD');
         $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
         $this->db->update('TCNTDocHDRefTmp',array(
             'FTXthDocNo'    => $paDataWhere['FTXshDocNo']
         ));
        return;
    }

    // [Move] Data HDDisTemp To HDDis
    public function FSaMSOMoveHdDisTempToHdDis($paDataWhere,$paTableAddUpdate){
        $tSODocNo       = $paDataWhere['FTXshDocNo'];
        $tSOBchCode     = $paDataWhere['FTBchCode'];
        $tSOSessionID   = $this->input->post('ohdSesSessionID');
        if(isset($tSODocNo) && !empty($tSODocNo)){
            $this->db->where_in('FTXshDocNo',$tSODocNo);
            $this->db->where_in('FTBchCode',$tSOBchCode);
            $this->db->delete($paTableAddUpdate['tTableHDDis']);
        }
        $tSQL   =   "   INSERT INTO ".$paTableAddUpdate['tTableHDDis']." (
                            FTBchCode,FTXshDocNo,FDXhdDateIns,FTXhdDisChgTxt,FTXhdDisChgType,
                            FCXhdTotalAfDisChg,FCXhdDisChg,FCXhdAmt )
                    ";
        $tSQL   .=  "   SELECT
                            HDDISTEMP.FTBchCode,
                            HDDISTEMP.FTXthDocNo,
                            HDDISTEMP.FDXtdDateIns,
                            HDDISTEMP.FTXtdDisChgTxt,
                            HDDISTEMP.FTXtdDisChgType,
                            HDDISTEMP.FCXtdTotalAfDisChg,
                            HDDISTEMP.FCXtdDisChg,
                            HDDISTEMP.FCXtdAmt
                        FROM TCNTDocHDDisTmp AS HDDISTEMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND HDDISTEMP.FTBchCode     = '$tSOBchCode'
                        AND HDDISTEMP.FTXthDocNo    = '$tSODocNo'
                        AND HDDISTEMP.FTSessionID   = '$tSOSessionID'
                    ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // [Move] Data DTTemp To DT
    public function FSaMSOMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tSOBchCode     = $paDataWhere['FTBchCode'];
        $tSODocNo       = $paDataWhere['FTXshDocNo'];
        $tSODocKey      = $paTableAddUpdate['tTableHD'];
        $tSOSessionID   = $this->input->post('ohdSesSessionID');
        
        if(isset($tSODocNo) && !empty($tSODocNo)){
            $this->db->where_in('FTXshDocNo',$tSODocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTBchCode,FTXshDocNo,FNXsdSeqNo,FTPdtCode,FTXsdPdtName,FTPunCode,FTPunName,FCXsdFactor,FTXsdBarCode,FTXsdVatType,FTVatCode,FCXsdVatRate,
                        FTXsdSaleType,FCXsdSalePrice,FCXsdQty,FCXsdQtyAll,FCXsdSetPrice,FCXsdAmtB4DisChg,FTXsdDisChgTxt,FCXsdDis,FCXsdChg,FCXsdNet,FCXsdNetAfHD,
                        FCXsdVat,FCXsdVatable,FCXsdWhtAmt,FTXsdWhtCode,FCXsdWhtRate,FCXsdCostIn,FCXsdCostEx,FCXsdQtyLef,FCXsdQtyRfn,FTXsdStaPrcStk,FTXsdStaAlwDis,
                        FNXsdPdtLevel,FTXsdPdtParent,FCXsdQtySet,FTPdtStaSet,FTXsdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTBchCode,
                            DOCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DOCTMP.FTPdtCode,
                            DOCTMP.FTXtdPdtName,
                            DOCTMP.FTPunCode,
                            DOCTMP.FTPunName,
                            DOCTMP.FCXtdFactor,
                            DOCTMP.FTXtdBarCode,
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
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tSOBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tSODocNo'
                        AND DOCTMP.FTXthDocKey  = '$tSODocKey'
                        AND DOCTMP.FTSessionID  = '$tSOSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // [Move] Data DTDisTemp To DTDis
    public function FSaMSOMoveDtDisTempToDtDis($paDataWhere,$paTableAddUpdate){
        $tSOBchCode     = $paDataWhere['FTBchCode'];
        $tSODocNo       = $paDataWhere['FTXshDocNo'];
        $tSOSessionID   = $this->input->post('ohdSesSessionID');
        
        if(isset($tSODocNo) && !empty($tSODocNo)){
            $this->db->where_in('FTXshDocNo',$tSODocNo);
            $this->db->where_in('FTBchCode',$tSOBchCode);
            $this->db->delete($paTableAddUpdate['tTableDTDis']);
        }

        $tSQL   =   "   INSERT INTO ".$paTableAddUpdate['tTableDTDis']." (FTBchCode,FTXshDocNo,FNXsdSeqNo,FDXddDateIns,FNXddStaDis,FTXddDisChgTxt,FTXddDisChgType,FCXddNet,FCXddValue) ";
        $tSQL   .=  "   SELECT
                            DOCDISTMP.FTBchCode,
                            DOCDISTMP.FTXthDocNo,
                            DOCDISTMP.FNXtdSeqNo,
                            DOCDISTMP.FDXtdDateIns,
                            DOCDISTMP.FNXtdStaDis,
                            DOCDISTMP.FTXtdDisChgTxt,
                            DOCDISTMP.FTXtdDisChgType,
                            DOCDISTMP.FCXtdNet,
                            DOCDISTMP.FCXtdValue
                        FROM TCNTDocDTDisTmp DOCDISTMP WITH (NOLOCK)
                        WHERE 1=1
                        AND DOCDISTMP.FTBchCode     = '$tSOBchCode'
                        AND DOCDISTMP.FTXthDocNo    = '$tSODocNo'
                        AND DOCDISTMP.FTSessionID   = '$tSOSessionID' 
                        ORDER BY DOCDISTMP.FNXtdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // ข้อมูลของ HD
    public function FSaMSOGetDataDocHD($paDataWhere){
        $tSODocNo   = $paDataWhere['FTXthDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $nAddressVersion = FCNaHAddressFormat('TCNMCst');

        $tSQL       = " SELECT
                            DOCHD.FTBchCode,
                            BCHL.FTBchName,
                            SHP.FTMerCode,
                            MERL.FTMerName,
                            SHP.FTShpType,
                            SHP.FTShpCode,
                            SHPL.FTShpName,
                            POS.FTWahRefCode,
                            POSL.FTPosComName,
                            DOCHD.FTWahCode,
                            WAHL.FTWahName,
                            DOCHD.FTXshDocNo,
                            DOCHD.FNXshDocType,
                            DOCHD.FDXshDocDate,
                            DOCHD.FTXshCshOrCrd,
                            DOCHD.FTXshVATInOrEx,
                            DOCHD.FTDptCode,
                            DPTL.FTDptName,
                            DOCHD.FTUsrCode,
                            USRL.FTUsrName,
                            DOCHD.FTXshApvCode,
                            USRAPV.FTUsrName	AS FTXshApvName,
                            DOCHD.FTXshRefAE,
                            DOCHD.FNXshDocPrint,
                            DOCHD.FTRteCode,
                            DOCHD.FCXshRteFac,
                            DOCHD.FTXshRmk,
                            DOCHD.FTXshStaRefund,
                            DOCHD.FTXshStaDoc,
                            DOCHD.FTXshStaApv,
                            DOCHD.FTXshStaPaid,
                            SPN.FTUsrName AS rtSpnName,
                            DOCHD.FNXshStaDocAct,
                            DOCHD.FNXshStaRef,
                            DOCHD.FTPosCode,
                            DOCHD.FTCstCode,
                            HDCST.FTXshCardID,
                            HDCST.FTXshCstName,
                            HDCST.FTXshCstTel,
                            HDCST.FNXshCrTerm,
                            HDCST.FDXshDueDate,
                            ADDL.FTAddV2Desc1,
                            CAR.FTCarCode,
                            CAR.FTCarRegNo,
                            CONCAT(ADDL.FTAddV1No,' ', ADDL.FTAddV1Soi,' ', ADDL.FTAddV1Village,' ', ADDL.FTAddV1Road,' ',
                            SUBDL.FTSudName,' ', DISL.FTDstName,' ', PRO.FTPvnName,' ', ADDL.FTAddV2Desc2) AS FTAddV1Desc,
                            T1.FTCaiName AS FTCarBrand,
                            T2.FTCaiName AS FTCarModel,
                            (
                                SELECT MAX (FNDatApvSeq) FROM TARTDocApvTxn
                                WHERE TARTDocApvTxn.FTBchCode = DOCHD.FTBchCode
                                AND TARTDocApvTxn.FTDatRefCode = DOCHD.FTXshDocNo
                                AND TARTDocApvTxn.FTDatUsrApv IS NOT NULL
                                GROUP BY TARTDocApvTxn.FTDatRefCode
                            ) AS LastSeq,
                            CSTLEV.FTPplCode AS FTPplCodeRet,
                            CST.FTCstDiscRet,
                            HDCST.FTXshStaAlwPosCalSo,
                            IMGOBJ.FTImgObj,
                            DOCHD.FDLastUpdOn,
                            DOCHD.FTLastUpdBy,
                            DOCHD.FDCreateOn,
                            DOCHD.FTCreateBy,
                            DOCHD.FTXshStaPrcDoc,
                            HDCST.FTXshCstRef
                        FROM TARTSoHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMShop          SHP     WITH (NOLOCK)   ON DOCHD.FTShpCode      = SHP.FTShpCode 
                        LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK)   ON DOCHD.FTShpCode      = SHPL.FTShpCode	AND SHPL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK)   ON SHP.FTMerCode        = MERL.FTMerCode	AND MERL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK)   ON DOCHD.FTWahCode      = WAHL.FTWahCode    AND BCHL.FTBchCode = 	WAHL.FTBchCode AND WAHL.FNLngID	    = $nLngID
                        LEFT JOIN TCNMWaHouse       POS     WITH (NOLOCK)   ON DOCHD.FTWahCode      = POS.FTWahCode		AND BCHL.FTBchCode = 	POS.FTBchCode AND POS.FTWahStaType    = '6'
                        LEFT JOIN TCNMPosLastNo		POSL    WITH (NOLOCK)   ON POS.FTWahRefCode     = POSL.FTPosCode
                        LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXshApvCode	= USRAPV.FTUsrCode	AND USRAPV.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        SPN     WITH (NOLOCK)   ON DOCHD.FTSpnCode      = SPN.FTUsrCode	    AND SPN.FNLngID	    = $nLngID
                        LEFT JOIN TARTSoHDCst       HDCST   WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = HDCST.FTXshDocNo
                        LEFT JOIN TCNMCst           CST     WITH (NOLOCK)   ON DOCHD.FTCstCode      = CST.FTCstCode
                        LEFT JOIN TCNMCstLev        CSTLEV  WITH (NOLOCK)   ON CST.FTClvCode        = CSTLEV.FTClvCode
                        LEFT JOIN TCNMImgObj        IMGOBJ  WITH (NOLOCK)   ON DOCHD.FTXshDocNo     = IMGOBJ.FTImgRefID AND IMGOBJ.FTImgTable ='TARTSoHD'
                        LEFT JOIN TCNMCstAddress_L  ADDL    WITH (NOLOCK)   ON DOCHD.FTCstCode      = ADDL.FTCstCode    AND ADDL.FTAddGrpType = 1  AND ADDL.FTAddVersion = '$nAddressVersion'
                        LEFT JOIN TSVMCar           CAR      WITH (NOLOCK)  ON HDCST.FTCarCode      = CAR.FTCarCode     
                        LEFT JOIN TSVMCarInfo_L     T1       WITH (NOLOCK)  ON CAR.FTCarBrand       = T1.FTCaiCode      AND T1.FNLngID	    = $nLngID
                        LEFT JOIN TSVMCarInfo_L     T2       WITH (NOLOCK)  ON CAR.FTCarModel       = T2.FTCaiCode      AND T2.FNLngID	    = $nLngID
                        LEFT JOIN TCNMProvince_L    PRO     WITH (NOLOCK)   ON ADDL.FTAddV1PvnCode = PRO.FTPvnCode      AND PRO.FNLngID     = $nLngID
                        LEFT JOIN TCNMDistrict_L    DISL    WITH (NOLOCK)   ON ADDL.FTAddV1DstCode = DISL.FTDstCode     AND DISL.FNLngID    = $nLngID
                        LEFT JOIN TCNMSubDistrict_L SUBDL   WITH (NOLOCK)   ON ADDL.FTAddV1SubDist = SUBDL.FTSudCode    AND SUBDL.FNLngID   = $nLngID
                        WHERE DOCHD.FTXshDocNo = '$tSODocNo' ";
                        
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

    // ข้อมูลของ HDCst
    public function FSaMSOGetDataDocHDCstAddr($paDataWhere){
        try {
            $tSODocNo   = $paDataWhere['FTXthDocNo'];
            $nLngID     = $paDataWhere['FNLngID'];

            $tSQL       = " SELECT
                                ADDL.FNAddSeqNo,
                                ADDL.FTAddV1No,
                                ADDL.FTAddV1Soi,
                                ADDL.FTAddV1Village,
                                ADDL.FTAddV1Road,
                                SDTL.FTSudName,
                                DTL.FTDstName,
                                PVL.FTPvnName,
                                ADDL.FTAddV1PostCode,
                                ADDL.FTAddTel,
                                ADDL.FTAddFax,
                                DOCHD.FTXshCstRef
                        FROM TARTSoHDCst DOCHD WITH (NOLOCK)
                        LEFT JOIN TCNMCstAddress_L  ADDL    WITH (NOLOCK) ON DOCHD.FNXshAddrShip = ADDL.FNAddSeqNo  AND ADDL.FNLngID = $nLngID 
                        LEFT JOIN TCNMProvince_L PVL WITH (NOLOCK) ON ADDL.FTAddV1PvnCode = PVL.FTPvnCode AND PVL.FNLngID = $nLngID 
                        LEFT JOIN TCNMDistrict_L DTL WITH (NOLOCK) ON ADDL.FTAddV1DstCode = DTL.FTDstCode AND DTL.FNLngID = $nLngID 
                        LEFT JOIN TCNMSubDistrict_L SDTL WITH (NOLOCK) ON ADDL.FTAddV1SubDist = SDTL.FTSudCode AND SDTL.FNLngID = $nLngID 
                        WHERE DOCHD.FTXshDocNo = '$tSODocNo' ";
                        
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->row_array();
                $aReturnData    = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aReturnData    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found.',
                );
            }

        }  catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    } 

    // [Move] Data HDDisTemp To Temp
    public function FSxMSOMoveHDDisToTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        // Delect Document HD DisTemp By Doc No
        $this->db->where('FTXthDocNo',$tSODocNo);
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
                            SOHDDis.FTBchCode,
                            SOHDDis.FTXshDocNo,
                            SOHDDis.FDXhdDateIns,
                            SOHDDis.FTXhdDisChgTxt,
                            SOHDDis.FTXhdDisChgType,
                            SOHDDis.FCXhdTotalAfDisChg,
                            (ISNULL(NULL,0)) AS FCXtdTotalB4DisChg,
                            SOHDDis.FCXhdDisChg,
                            SOHDDis.FCXhdAmt,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')    AS FTSessionID,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                        FROM TARTSoHDDis SOHDDis WITH (NOLOCK)
                        WHERE 1=1 AND SOHDDis.FTXshDocNo = '$tSODocNo'
        ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery;
    }

    // [Move] Data DT To DTTemp
    public function FSxMSOMoveDTToDTTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        $tSODocKey      = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FTXtdVatType,FTVatCode,FCXtdVatRate,FTXtdSaleType,FCXtdSalePrice,FCXtdQty,FCXtdQtyAll,FCXtdSetPrice,
                        FCXtdAmtB4DisChg,FTXtdDisChgTxt,FCXtdDis,FCXtdChg,FCXtdNet,FCXtdNetAfHD,FCXtdVat,FCXtdVatable,FCXtdWhtAmt,
                        FTXtdWhtCode,FCXtdWhtRate,FCXtdCostIn,FCXtdCostEx,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,
                        FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,FTXtdPdtStaSet,FTXtdRmk,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                    SELECT
                        SODT.FTBchCode,
                        SODT.FTXshDocNo,
                        SODT.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$tSODocKey."') AS FTXthDocKey,
                        SODT.FTPdtCode,
                        SODT.FTXsdPdtName,
                        SODT.FTPunCode,
                        SODT.FTPunName,
                        SODT.FCXsdFactor,
                        SODT.FTXsdBarCode,
                        SODT.FTXsdVatType,
                        SODT.FTVatCode,
                        SODT.FCXsdVatRate,
                        SODT.FTXsdSaleType,
                        SODT.FCXsdSalePrice,
                        SODT.FCXsdQty,
                        SODT.FCXsdQtyAll,
                        SODT.FCXsdSetPrice,
                        SODT.FCXsdAmtB4DisChg,
                        SODT.FTXsdDisChgTxt,
                        SODT.FCXsdDis,
                        SODT.FCXsdChg,
                        SODT.FCXsdNet,
                        SODT.FCXsdNetAfHD,
                        SODT.FCXsdVat,
                        SODT.FCXsdVatable,
                        SODT.FCXsdWhtAmt,
                        SODT.FTXsdWhtCode,
                        SODT.FCXsdWhtRate,
                        SODT.FCXsdCostIn,
                        SODT.FCXsdCostEx,
                        SODT.FCXsdQtyLef,
                        SODT.FCXsdQtyRfn,
                        SODT.FTXsdStaPrcStk,
                        SODT.FTXsdStaAlwDis,
                        SODT.FNXsdPdtLevel,
                        SODT.FTXsdPdtParent,
                        SODT.FCXsdQtySet,
                        SODT.FTPdtStaSet,
                        SODT.FTXsdRmk,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TARTSoDT AS SODT WITH (NOLOCK)
                    WHERE 1=1 AND SODT.FTXshDocNo = '$tSODocNo'
                    ORDER BY SODT.FNXsdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);

        // echo $tSQL;
        return $oQuery;
    }

    // [Move] Data DTDisTemp To DTDis
    public function FSxMSOMoveDTDisToDTDisTemp($paDataWhere){
        $tSODocNo       = $paDataWhere['FTXthDocNo'];
        
        // Delect Document DTDisTemp By Doc No
        $this->db->where('FTXthDocNo',$tSODocNo);
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
                        SODTDis.FTBchCode,
                        SODTDis.FTXshDocNo,
                        SODTDis.FNXsdSeqNo,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."')    AS FTSessionID,
                        SODTDis.FDXddDateIns,
                        SODTDis.FNXddStaDis,
                        SODTDis.FTXddDisChgType,
                        SODTDis.FCXddNet,
                        SODTDis.FCXddValue,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
                        SODTDis.FTXddDisChgTxt
                    FROM TARTSoDTDis SODTDis
                    WHERE 1=1 AND SODTDis.FTXshDocNo = '$tSODocNo'
                    ORDER BY SODTDis.FNXsdSeqNo ASC
            ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery;
    }
    
    // ยกเลิกเอกสาร
    public function FSaMSOCancelDocument($paDataUpdate){
        $this->db->trans_begin();
        $this->db->set('FTXshStaDoc' , '3');
        $this->db->where('FTXshDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TARTSoHD');

        // $this->db->where('FTDatRefCode',$paDataUpdate['tDocNo'])->delete('TARTDocApvTxn');
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Cancel Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Cancel Success."
            );
        }
        return $aDatRetrun;
    }

    // อนุมัติเอกสาร
    public function FSaMSOApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaApv',$paDataUpdate['FTXshStaApv']);
        $this->db->set('FTXshApvCode',$paDataUpdate['FTXshUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTSoHD');

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

    // ปรับสถานะ
    public function FSaMSOStaprcDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshStaPrcDoc',$paDataUpdate['FTXshStaApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTSoHD');

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

    // ต้องวิ่งไปหา ว่าเอกสาร SO ใบนี้ถูกสร้างมาจาก ใบสั่งสินค้าจากสาขา - ลูกค้า หรือเปล่า 
    public function FSaMSOApproveDocumentINMGT($paDataUpdate){
        $tDocNo = $paDataUpdate['FTXshDocNo'];
        $tSQL   = " UPDATE TCNTPdtReqMgtHD SET FTXrhStaPrcDoc = 2 WHERE FTXphDocNo = '$tDocNo' AND FNXrhDocType = '5' ";
        $this->db->query($tSQL);
    }

    // Count Product Bar
    public function FSaCPICountPdtBarInTablePdtBar($paDataChkINDB){
        $tSODataSearchAndAdd    = $paDataChkINDB['tSODataSearchAndAdd'];
        $nLangEdit              = $paDataChkINDB['nLangEdit'];

        $tSQL   = " SELECT 
                        PDTBAR.FTPdtCode,
                        PDT_L.FTPdtName,
                        PDTBAR.FTBarCode,
                        PDTBAR.FTPunCode,
                        PUN_L.FTPunName
                    FROM TCNMPdtBar         PDTBAR  WITH(NOLOCK)
                    LEFT JOIN TCNMPdt		PDT     WITH(NOLOCK)	ON PDTBAR.FTPdtCode = PDT.FTPdtCode
                    LEFT JOIN TCNMPdt_L	    PDT_L   WITH(NOLOCK)	ON PDT.FTPdtCode	= PDT_L.FTPdtCode   AND PDT_L.FNLngID   = $nLangEdit
                    LEFT JOIN TCNMPdtUnit   PUN     WITH(NOLOCK)	ON PDTBAR.FTPunCode	= PUN.FTPunCode
                    LEFT JOIN TCNMPdtUnit_L	PUN_L   WITH(NOLOCK)	ON PUN.FTPunCode    = PUN_L.FTPunCode   AND PUN_L.FNLngID   = $nLangEdit
                    WHERE 1=1
                    AND PDTBAR.FTBarStaUse 	= 1
                    AND (PDTBAR.FTPdtCode = '$tSODataSearchAndAdd' OR PDTBAR.FTBarCode = '$tSODataSearchAndAdd')
        ";
        $oQuery         = $this->db->query($tSQL);
        $aDataReturn    = $oQuery->result_array();
        unset($oQuery);
        return $aDataReturn;
    }

    // Check Approve Document And Load Format User Aprove From Roles To Trns
    public function FSnMSOCheckLevelApr($paData){

        $tTableDocHD = $paData['tTableDocHD'];
        $tCreateBy   = $paData['tApvCode'];
        $tDocNo      = $paData['tDocNo'];
        $tBchCode    = $paData['tBchCode'];
        $dDocDate    = date('Y-m-d H:i:s');

        if(!empty($tTableDocHD)){

            $tSqlDocApr = "   SELECT
                            dbo.TCNMDocApvRole.FNDarApvSeq,
                            dbo.TCNMDocApvRole.FTDarUsrRole,
                            dbo.TCNMDocApvRole.FTDarRefType,
                            dbo.TSysDocApv.FTDapName,
                            dbo.TSysDocApv.FTDapNameOth
                        FROM
                            dbo.TCNMDocApvRole
                        INNER JOIN dbo.TSysDocApv ON dbo.TCNMDocApvRole.FNDarApvSeq = dbo.TSysDocApv.FNDapSeq
                        AND dbo.TCNMDocApvRole.FTDarTable = dbo.TSysDocApv.FTDapTable
                        WHERE
                            dbo.TCNMDocApvRole.FTDarTable = '$tTableDocHD'
                    ";

                   $oQuery = $this->db->query($tSqlDocApr);
                   $nNumrows = $oQuery->num_rows();

                if($nNumrows>0){

                    $aDataParam=array(
                        'tTableDocHD' => $tTableDocHD,
                        'tCreateBy'   => $tCreateBy,
                        'tDocNo'      => $tDocNo ,
                        'dDocDate'    => $dDocDate,
                        'tBchCode'    => $tBchCode
                    );

                    if(!empty($aDataParam)){

                      $aResult =  $this->FSnMSODMoveRoleToTrns($aDataParam);

                      if($aResult==1){

                        $aReturn['tReturnCode'] = '200';
                        $aReturn['tReturnMsg'] = 'Success Function Insert Level Apr';
                        return $aReturn;

                      }else{

                        $aReturn['tReturnCode'] = '500';
                        $aReturn['tReturnMsg'] = 'This function error!';
                        return $aReturn;

                      }

                    }else{

                        $aReturn['tReturnCode'] = '202';
                        $aReturn['tReturnMsg'] = 'Doc Approve Only User';
                        return $aReturn;

                    }

                }else{
                    $aReturn['tReturnCode'] = '202';
                    $aReturn['tReturnMsg'] = 'Doc Approve Only User';
                    return $aReturn;
                }

        }else{

            $aReturn['tReturnCode'] = '404';
            $aReturn['tReturnMsg'] = 'Table Is Empty !';
            return $aReturn;
        }
    }

    public function FSnMSODMoveRoleToTrns($paDataInsert){
        
        $tTableDocHD = $paDataInsert['tTableDocHD'];
        $tCreateBy   = $paDataInsert['tCreateBy'];
        $tDocNo      = $paDataInsert['tDocNo'];
        $dDocDate    = $paDataInsert['dDocDate'];
        $tBchCode    = $paDataInsert['tBchCode'];

         $nCountrow = $this->FSnMSONumRowTnxTable($paDataInsert);
        
        if($nCountrow<0){
            $tSql ="
                    INSERT INTO TARTDocApvTxn (
                        FTBchCode,
                        FTDatRefCode,
                        FTDatRefType,
                        FNDatApvSeq,
                        FDCreateOn,
                        FTCreateBy
                    ) SELECT
                        '$tBchCode' AS FTBchCode,
                        '$tDocNo' AS FTDatRefCode,
                        dbo.TCNMDocApvRole.FTDarRefType,
                        dbo.TCNMDocApvRole.FNDarApvSeq,
                        GETDATE() AS FDCreateOn,
                        '$tCreateBy' AS FTCreateBy
                    FROM
                        dbo.TCNMDocApvRole
                    WHERE
                        dbo.TCNMDocApvRole.FTDarTable = '$tTableDocHD'
            ";

            $oQuery = $this->db->query($tSql);
            
            if($oQuery){
                $nReustl = 1;
            }else{
                $nReustl = 2;
            }
        }else{
            $nReustl = 1;
        }
        return $nReustl;

    }

    public function FSnMSONumRowTnxTable($paDataInsert){

        $tTableDocHD = $paDataInsert['tTableDocHD'];
        $tCreateBy   = $paDataInsert['tCreateBy'];
        $tDocNo      = $paDataInsert['tDocNo'];
        $dDocDate    = $paDataInsert['dDocDate'];
        $tBchCode    = $paDataInsert['tBchCode'];

        $tSqlCount = "
            SELECT COUNT(*) AS nNums FROM [dbo].[TARTDocApvTxn]
             WHERE FTBchCode='$tBchCode'
             AND FTDatRefCode = '$tDocNo';
              ";
       
       $oQuery = $this->db->query($tSqlCount);
      $aRes = $oQuery->row_array();
       return $aRes['nNums'];

    }

    public function FSnMSOUpdateTableMutiAprve($paData){

        $tRoleCode   = $paData['tRoleCode'];
        $tDatRefCode = $paData['FTDatRefCode'];
        $tBchCode    = $paData['FTBchCode'];
        $tTableDocHD    = $paData['tTableDocHD'];
        
        $tSql="
                    SELECT
                        TOP 1
                        dbo.TARTDocApvTxn.FNDatApvSeq,
                        dbo.TARTDocApvTxn.FTDatRefType,
                        dbo.TARTDocApvTxn.FTDatRefCode,
                        dbo.TARTDocApvTxn.FTBchCode,
                        dbo.TARTDocApvTxn.FTDatUsrApv,
                        dbo.TARTDocApvTxn.FDDatDateApv,
                        dbo.TCNMDocApvRole.FTDarTable,
                        dbo.TCNMDocApvRole.FTDarUsrRole,
                        dbo.TCNMDocApvRole.FNDarApvSeq
                    FROM
                    dbo.TARTDocApvTxn
                    INNER JOIN dbo.TCNMDocApvRole ON dbo.TARTDocApvTxn.FNDatApvSeq = dbo.TCNMDocApvRole.FNDarApvSeq AND dbo.TCNMDocApvRole.FTDarTable='$tTableDocHD'
                    WHERE
                        dbo.TARTDocApvTxn.FTBchCode='$tBchCode'
                        AND dbo.TARTDocApvTxn.FTDatRefCode='$tDatRefCode'
                        AND dbo.TARTDocApvTxn.FDDatDateApv IS NULL
                        AND dbo.TARTDocApvTxn.FTDatUsrApv IS NULL
        ";

        $oQuery = $this->db->query($tSql);
        $aTnx = $oQuery->row_array();

        if(!empty($aTnx)){

            if($aTnx['FTDarUsrRole']=='' || $aTnx['FTDarUsrRole']==$tRoleCode){
                $aResult =  array(
                                    'nReturnCode' => 1 ,
                                    'FNDatApvSeq' => $aTnx['FNDatApvSeq']
                                    );
            }else{
                $aResult = array(
                    'nReturnCode' => 2,
                    'FNDatApvSeq' => ''
                    );
            }

        }else{
            $aResult = array(
                'nReturnCode' => 2 ,
                'FNDatApvSeq' => ''
                );
        }
        return $aResult;
    }

    public function FSnMSOAInsertForMultiAprve($paData){
        $nCheckPerAprv = $this->FSnMSOUpdateTableMutiAprve($paData);//ตรวจสอบลำดับที่จะอนุมัติ
        if($nCheckPerAprv['nReturnCode']==1){

            $this->db->trans_begin();
            $tRoleCode = $paData['tRoleCode'];
            $tDatRefCode = $paData['FTDatRefCode'];
            $tBchCode = $paData['FTBchCode'];
            $nDatApvSeq = $nCheckPerAprv['FNDatApvSeq'];

            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
            $tDatUsrApv = $paData['FTDatUsrApv'];
            $dDatDateApv = $paData['FDDatDateApv'];
            $tDatRmk = $paData['FTDatRmk'];

            $this->db->set('FDLastUpdOn',$dLastUpdOn);
            $this->db->set('FTLastUpdBy',$tLastUpdBy);
            $this->db->set('FTDatUsrApv',$tDatUsrApv);
            $this->db->set('FDDatDateApv',$dDatDateApv);
            $this->db->set('FTDatRmk',$tDatRmk);
            $this->db->where('FTDatRefCode',$tDatRefCode);
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FNDatApvSeq',$nDatApvSeq);
            $this->db->update('TARTDocApvTxn');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aDatRetrun = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Cannot Update Status Approve Document."
                );
            }else{
                $this->db->trans_commit();
                $aDatRetrun = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => "Update Status Document Approve Success."
                );
            }

        }else{
            $aDatRetrun = array(
                'nStaEvent' => '990',
                'tStaMessg' => "You don't have permission to approve document."
            );
        }
        return $aDatRetrun;
    }

    public function FSxMSONotAproveItem($paData){
        foreach($paData['tSOtiemNotApr'] as $nK => $aData){
                $aDataInserDelObj = array(
                    'FTBchCode'      => $paData['tBchCode'],
                    'FTXshDocNo'  => $paData['tDocNo'] ,
                    'FNXsdSeqNo' => strval($aData['nseq']),
                    'FTXsdRmk'    => strval($aData['reason']),
                    'FDCreateOn'      => date('Y-m-d H:i:s') ,
                    'FTCreateBy'      => $paData['tSesUsername'] ,
                ); 
                
        $this->db->insert('TVDTDTCN',$aDataInserDelObj);
        }
    }

    //หา DocType ของเอกสาร (ไม่ได้ใช้เเล้ว)
    public function FSnMSOGetDocType(){
        $tSql   = "SELECT FNSdtDocType FROM TSysDocType WHERE FTSdtTblName = 'TARTSoHD' ";
        $oQuery = $this->db->query($tSql);
        return $oQuery->row_array();
    }

    public function FSaMSOUpdateStrPrcLastUpdate($paData){
        $this->db->trans_begin();
        $this->db->set('FTDatStaPrc',2);
        $this->db->set('FDLastUpdOn',$paData['FDLastUpdOn']);
        $this->db->set('FTLastUpdBy',$paData['FTLastUpdBy']);
        $this->db->where('FTDatRefCode',$paData['FTDatRefCode']);
        $this->db->where('FTBchCode',$paData['tBchCode']);
        $this->db->where('FNDatApvSeq',$paData['FNDatApvSeq']);
        $this->db->update('TARTDocApvTxn');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Success."
            );
        
        }
        return $aDatRetrun;
    }

    public function FSnMSOCheckStrPrcLastUpdate($paData){
        $FTDatRefCode = $paData['FTDatRefCode'];
        $tBchCode = $paData['tBchCode'];
        $FNDatApvSeq = $paData['FNDatApvSeq'];
        $dDataNow = date('Y-m-d H:i:s');
            $tSql = " SELECT
            count(*) AS StrCheck
            FROM
                TARTDocApvTxn TXN
                LEFT OUTER JOIN TSysConfig TCF ON TCF.FTSysCode='tVD_DocApprove'
            WHERE
                TXN.FTDatRefCode = '$FTDatRefCode'
            AND TXN.FTBchCode = '$tBchCode'
            AND TXN.FNDatApvSeq = '$FNDatApvSeq'
            AND ( 
                ( TXN.FTDatStaPrc IS NULL AND TXN.FTDatUsrApv IS NULL ) 
                OR 
                ( TXN.FTDatStaPrc = 2 AND DATEADD(MINUTE,CONVERT(INT,TCF.FTSysStaUsrValue),TXN.FDLastUpdOn) <= '$dDataNow' )
            )
                    ";
            $oQuery = $this->db->query($tSql);
            $reustl =  $oQuery->row_array();

            return  $reustl['StrCheck'];
    }

    public function FSnMSOGetTimeCountDown($paData){

        $FTDatRefCode = $paData['FTDatRefCode'];
        $tBchCode = $paData['tBchCode'];
        $FNDatApvSeq = $paData['FNDatApvSeq'];
        $dDataNow = date('Y-m-d H:i:s');
        $tSql = " SELECT
                    TCF.FTSysStaUsrValue,
                    TXN.FDLastUpdOn,
                    DATEADD(MINUTE,CONVERT(INT,TCF.FTSysStaUsrValue),TXN.FDLastUpdOn) AS rDateExp,
                    GETDATE() AS dateget,
                    DATEDIFF(SECOND,'$dDataNow',DATEADD(MINUTE,CONVERT(INT,TCF.FTSysStaUsrValue),TXN.FDLastUpdOn)) AS rSecondTime
                    FROM
                        TARTDocApvTxn TXN
                        LEFT OUTER JOIN TSysConfig TCF ON TCF.FTSysCode='tVD_DocApprove'
                    WHERE
                        TXN.FTDatRefCode = '$FTDatRefCode'
                    AND TXN.FTBchCode = '$tBchCode'
                    AND TXN.FNDatApvSeq = '$FNDatApvSeq' ";
        $oQuery = $this->db->query($tSql);
        $aReustl =  $oQuery->row_array();
        return  $aReustl['rSecondTime'];
    }

    public function FSaMSOGetDetailUserBranch($paBchCode){
        if(!empty($paBchCode)){
        $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
        //   $oQuery = $this->db->query($oSql);
        //   $aReustl =  $oQuery->row_array();
        $aReulst['item'] = $aReustl;
        $aReulst['code'] = 1;
        $aReulst['msg'] = 'Success !';
        }else{
        $aReulst['code'] = 2;
        $aReulst['msg'] = 'Error !';
        }
        return $aReulst;
    }

    //หาราคาตามสินค้า
    public function FSaMSOGetPrice4Pdt($paData,$pNPrice){
        $tSOPplCodeBch = $paData['tSOPplCodeBch'];
        $tSOPplCodeCst = $paData['tSOPplCodeCst'];
        $tSOPdtCode    = $paData['tSOPdtCode'];
        $tSOPunCode    = $paData['tSOPunCode'];

        if($pNPrice==1){
            $tConditionSOPplCode=" AND TCNTPdtPrice4PDT.FTPplCode='$tSOPplCodeCst' ";
        }else if($pNPrice==2){
            $tConditionSOPplCode=" AND TCNTPdtPrice4PDT.FTPplCode='$tSOPplCodeBch' ";
        }else if($pNPrice==3){
            $tConditionSOPplCode =" AND ( TCNTPdtPrice4PDT.FTPplCode IS NULL OR  TCNTPdtPrice4PDT.FTPplCode ='' ) ";
        }

        $dDate  =   date('Y-m-d');
        $tTime  =   date('H:i:s');
        $tSql   =   "   SELECT TOP 1
                            TCNTPdtPrice4PDT.FTPplCode,
                            TCNTPdtPrice4PDT.FTPdtCode,
                            TCNTPdtPrice4PDT.FTPunCode,
                            TCNTPdtPrice4PDT.FDPghDStart,
                            TCNTPdtPrice4PDT.FTPghTStart,
                            TCNTPdtPrice4PDT.FDPghDStop,
                            TCNTPdtPrice4PDT.FTPghTStop,
                            TCNTPdtPrice4PDT.FCPgdPriceRet,
                            TCNTPdtPrice4PDT.FCPgdPriceNet,
                            TCNTPdtPrice4PDT.FCPgdPriceWhs
                        FROM TCNTPdtPrice4PDT
                            WHERE 1=1
                            $tConditionSOPplCode
                        AND TCNTPdtPrice4PDT.FTPdtCode='$tSOPdtCode'
                        AND TCNTPdtPrice4PDT.FTPunCode='$tSOPunCode'
                        AND TCNTPdtPrice4PDT.FDPghDStart<='$dDate' AND TCNTPdtPrice4PDT.FTPghTStart<='$tTime'
                        AND TCNTPdtPrice4PDT.FDPghDStop>='$dDate' AND TCNTPdtPrice4PDT.FTPghTStop>='$tTime' 
                        ORDER BY FTPghDocType DESC , FDPghDStart DESC ";

        $oQuery = $this->db->query($tSql);
        $nRows  = $oQuery->num_rows();
        if($nRows>0){
            $aDataPrice  = $oQuery->row_array();  
            $aResult['code'] = 1; 
            $aResult['price'] = $aDataPrice['FCPgdPriceRet']; 
        }else{
            $aResult['code'] = 2; 
            $aResult['price'] = 0;
        }
        return $aResult;
    }

    // หาราคาตามกลุ่ม
    public function FScMSOGetPricePdt4CstOrPdtBYPplCode($paData){
        $tSOPplCodeBch = $paData['tSOPplCodeBch'];
        $tSOPplCodeCst = $paData['tSOPplCodeCst'];
        $tSOPdtCode    = $paData['tSOPdtCode'];
        $tSOPunCode    = $paData['tSOPunCode'];
        //    FDPghDStart วันที่เริ่ม
        //    FTPghTStart เวลาเริ่ม
        //    FDPghDStop วันที่หมดอายุ
        //    FTPghTStop เวลาหมดอายุ
        //    FCPgdPriceRet ราคาขายปลีก
        $PriceReturn = 0;
        if(!empty($tSOPplCodeCst)){
            $aResultCst = $this->FSaMSOGetPrice4Pdt($paData,1);
            if($aResultCst['code']==1){
                $PriceReturn = $aResultCst['price'];
            }else{
                $aResultBch = $this->FSaMSOGetPrice4Pdt($paData,2);
                if($aResultBch['code']==1){
                    $PriceReturn = $aResultBch['price'];
                }else{
                    $aResultBch = $this->FSaMSOGetPrice4Pdt($paData,3);
                    $PriceReturn = $aResultBch['price'];
                }
            }
        }else{
            $aResultBch = $this->FSaMSOGetPrice4Pdt($paData,2);
            if($aResultBch['code']==1){
                $PriceReturn = $aResultBch['price'];
            }else{
                $aResultBch = $this->FSaMSOGetPrice4Pdt($paData,3);
                $PriceReturn = $aResultBch['price'];
            }
        }

        return $PriceReturn;
    }

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม where session
    public function FSaMCENDeletePDTInTmp($paParams){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');
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

    // VAT ตัวสุดท้าย
    public function FSaMSOCalVatLastDT($paData){

        $tDocNo = $paData['tDocNo'];
        $tBchCode = $paData['tBchCode'];
        $tSessionID = $paData['tSessionID'];
        // $cSumFCXtdVat = $paData['cSumFCXtdVat'];
        $tSumFCXtdVat = " SELECT
                                SUM (ISNULL(DOCTMP.FCXtdVat, 0)) AS FCXtdVat
                            FROM
                                TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE
                                1 = 1
                            AND DOCTMP.FTSessionID = '$tSessionID'
                            AND DOCTMP.FTXthDocKey = 'TARTSoHD'
                            AND DOCTMP.FTXthDocNo = '$tDocNo'
                            AND DOCTMP.FTXtdVatType = 1
                            AND DOCTMP.FCXtdVatRate > 0  ";


        $tSql ="
                    UPDATE TCNTDocDTTmp
                            SET FCXtdVat = (
                                ($tSumFCXtdVat) - (
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
                             FCXtdVatable = (FCXtdNet - (
                                ($tSumFCXtdVat) - (
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
                                    ))
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
                            ) ";
        $nRSCounDT =  $this->db->where('FTSessionID',$tSessionID)->where('FTXthDocNo',$tDocNo)->get('TCNTDocDTTmp')->num_rows();
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

    // อ้างอิงเอกสาร ใบเสนอราคา , ใบสั่งซื้อ HD
    public function FSoMSOCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $aRefType               = $paDataCondition['tRefType'];

        // Advance Search
        $tSORefIntBchCode        = $aAdvanceSearch['tSORefIntBchCode'];
        $tSORefIntDocNo          = $aAdvanceSearch['tSORefIntDocNo'];
        $tSORefIntDocDateFrm     = $aAdvanceSearch['tSORefIntDocDateFrm'];
        $tSORefIntDocDateTo      = $aAdvanceSearch['tSORefIntDocDateTo'];
        $tSORefIntStaDoc         = $aAdvanceSearch['tSORefIntStaDoc'];

        if($aRefType == '1'){ //ใบเสนอราคา
            $tSQLMain = "   SELECT DISTINCT
                                QT.FTBchCode,
                                BCHL.FTBchName,
                                QT.FTXshDocNo,
                                CONVERT(CHAR(10),QT.FDXshDocDate,103) AS FDXshDocDate,
                                CONVERT(CHAR(5), QT.FDXshDocDate,108) AS FTXshDocTime,
                                QT.FTXshStaDoc,
                                QT.FTXshStaApv,
                                QT.FNXshStaRef,
                                QT.FTXshVATInOrEx,
                                QT.FTXshCshOrCrd,
                                QT.FTCreateBy,
                                QT.FDCreateOn,
                                QT.FNXshStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                QT.FTXshApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName,
                                QT.FTCstCode,
                                CSTL.FTCstName,
                                CST.FTCstCardID,
                                CST.FTCstTel,
                                ADR.FTAddV2Desc1,
                                QTCST.FNXshCrTerm,
                                QTCST.FDXshDueDate,
                                NULL AS BCHCodeTo
                            FROM TARTSqHD           QT      WITH (NOLOCK)
                            INNER JOIN TARTSqHDCst  QTCST   WITH (NOLOCK) ON QT.FTXshDocNo    = QTCST.FTXshDocNo  AND QT.FTBchCode  =  QTCST.FTBchCode
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON QT.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID      = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON QT.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID      = $nLngID
                            INNER JOIN TCNMCst      CST     WITH (NOLOCK) ON QT.FTCstCode     = CST.FTCstCode
                            INNER JOIN TCNMCst_L    CSTL    WITH (NOLOCK) ON QT.FTCstCode     = CSTL.FTCstCode    AND CSTL.FNLngID      = $nLngID
                            LEFT JOIN  TCNMCstAddress_L ADR WITH (NOLOCK) ON CST.FTCstCode    = ADR.FTCstCode     AND ADR.FNLngID       = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON QT.FTBchCode     = WAH_L.FTBchCode   AND QT.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            LEFT JOIN TARTSoHDDocRef QT_R   WITH (NOLOCK) ON QT.FTXshDocNo    = QT_R.FTXshDocNo   AND QT.FTBchCode = QT_R.FTBchCode
                            WHERE QT.FTXshStaDoc = 1 AND QT.FTXshStaApv = 1 AND (ADR.FTAddGrpType = 1 OR ISNULL(ADR.FTAddGrpType,'') = '')
                            AND ISNULL(QT_R.FTXshRefDocNo, '') = '' ";

            if(isset($tSORefIntBchCode) && !empty($tSORefIntBchCode)){
                $tSQLMain .= " AND (QT.FTBchCode = '$tSORefIntBchCode')";
            }

            if(isset($tSORefIntDocNo) && !empty($tSORefIntDocNo)){
                $tSQLMain .= " AND (QT.FTXshDocNo LIKE '%$tSORefIntDocNo%')";
            }

            // ค้นหาจากวันที่ - ถึงวันที่
            if(!empty($tSORefIntDocDateFrm) && !empty($tSORefIntDocDateTo)){
                $tSQLMain .= " AND ((QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSORefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tSORefIntDocDateTo 23:59:59')) OR (QT.FDXshDocDate BETWEEN CONVERT(datetime,'$tSORefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tSORefIntDocDateFrm 00:00:00')))";
            }

            // ค้นหาสถานะเอกสาร
            if(isset($tSORefIntStaDoc) && !empty($tSORefIntStaDoc)){
                if ($tSORefIntStaDoc == 3) {
                    $tSQLMain .= " AND QT.FTXshStaDoc = '$tSORefIntStaDoc'";
                } elseif ($tSORefIntStaDoc == 2) {
                    $tSQLMain .= " AND ISNULL(QT.FTXshStaApv,'') = '' AND QT.FTXshStaDoc != '3'";
                } elseif ($tSORefIntStaDoc == 1) {
                    $tSQLMain .= " AND QT.FTXshStaApv = '$tSORefIntStaDoc'";
                }
            }
        
            $tSQL   =  "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                        (  $tSQLMain
                        ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
        }elseif($aRefType == '2'){ //ใบสั่งซื้อ
            $tSQLMain = "   SELECT DISTINCT
                            QT.FTBchCode,
                            BCHL.FTBchName,
                            QT.FTXphDocNo AS FTXshDocNo,
                            CONVERT(CHAR(10),QT.FDXphDocDate,103) AS FDXshDocDate,
                            CONVERT(CHAR(5), QT.FDXphDocDate,108) AS FTXshDocTime,
                            QT.FTXphStaDoc AS FTXshStaDoc,
                            QT.FTXphStaApv AS FTXshStaApv,
                            QT.FNXphStaRef,
                            QT.FTXphVATInOrEx,
                            QT.FTXphCshOrCrd,
                            QT.FTCreateBy,
                            QT.FDCreateOn,
                            AGN.FTAgnRefCst AS FTCstCode,
                            CSTL.FTCstName,
                            CST.FTCstCardID,
                            CST.FTCstTel,
                            QT.FNXphStaDocAct,
                            QT.FTXphApvCode,
                            QT.FTXphBchTo   AS BCHCodeTo
                        FROM TAPTPoHD           QT      WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON QT.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID      = $nLngID 
                        LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON QT.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID      = $nLngID
                        LEFT JOIN TCNMAgency    AGN     WITH (NOLOCK) ON QT.FTAgnCode     = AGN.FTAgnCode    
                        INNER JOIN TCNMCst      CST     WITH (NOLOCK) ON CST.FTCstCode     = AGN.FTAgnRefCst
                        INNER JOIN TCNMCst_L    CSTL    WITH (NOLOCK) ON AGN.FTAgnRefCst     = CSTL.FTCstCode    AND CSTL.FNLngID      = $nLngID
                        WHERE QT.FTXphStaDoc = 1 AND QT.FTXphStaApv = 1 AND QT.FTAgnCode != '' AND ISNULL(QT.FTXphStaApvPdt,'') != '' ";

            if(isset($tSORefIntBchCode) && !empty($tSORefIntBchCode)){
            $tSQLMain .= " AND (QT.FTBchCode = '$tSORefIntBchCode')";
            }

            if(isset($tSORefIntDocNo) && !empty($tSORefIntDocNo)){
            $tSQLMain .= " AND (QT.FTXphDocNo LIKE '%$tSORefIntDocNo%')";
            }

            // ค้นหาจากวันที่ - ถึงวันที่
            if(!empty($tSORefIntDocDateFrm) && !empty($tSORefIntDocDateTo)){
            $tSQLMain .= " AND ((QT.FDXphDocDate BETWEEN CONVERT(datetime,'$tSORefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tSORefIntDocDateTo 23:59:59')) OR (QT.FDXphDocDate BETWEEN CONVERT(datetime,'$tSORefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tSORefIntDocDateFrm 00:00:00')))";
            }

            // ค้นหาสถานะเอกสาร
            if(isset($tSORefIntStaDoc) && !empty($tSORefIntStaDoc)){
                if ($tSORefIntStaDoc == 3) {
                    $tSQLMain .= " AND QT.FTXphStaDoc = '$tSORefIntStaDoc'";
                } elseif ($tSORefIntStaDoc == 2) {
                    $tSQLMain .= " AND ISNULL(QT.FTXphStaApv,'') = '' AND QT.FTXphStaDoc != '3'";
                } elseif ($tSORefIntStaDoc == 1) {
                    $tSQLMain .= " AND QT.FTXphStaApv = '$tSORefIntStaDoc'";
                }
            }

            $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                        (  $tSQLMain
                        ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";
        }

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
    
    // อ้างอิงเกอสาร ใบเสนอราคา DT
    public function FSoMSOCallRefIntDocDTDataTable($paData){

        $tBchCode   =  $paData['tBchCode'];
        $tDocNo     =  $paData['tDocNo'];
        $tDocType   =  $paData['tDocType'];
        
        if($tDocType == '1'){ //ใบเสนอราคา
            $tSQL   =   "   SELECT 
                            DT.FTBchCode, DT.FTXshDocNo, DT.FNXsdSeqNo,
                            DT.FTPdtCode, DT.FTXsdPdtName, DT.FTPunCode,
                            DT.FTPunName, DT.FCXsdFactor, DT.FTXsdBarCode,
                            DT.FCXsdQty     AS QTY, 
                            DT.FCXsdQtyAll, 
                            DT.FTXsdRmk,
                            DT.FDLastUpdOn, DT.FTLastUpdBy, DT.FDCreateOn,
                            DT.FTCreateBy
                        FROM TARTSqDT DT WITH(NOLOCK)
                        WHERE DT.FTBchCode = '$tBchCode' AND  DT.FTXshDocNo ='$tDocNo' ";
        }else if($tDocType == '2'){  //ใบสั่งซื้อ
            $tSQL   =   "   SELECT
                            DT.FTBchCode, DT.FTXphDocNo AS FNXsdSeqNo,
                            DT.FNXpdSeqNo AS FNXsdSeqNo, DT.FTPdtCode,
                            DT.FTXpdPdtName AS FTXsdPdtName,
                            DT.FTPunCode, DT.FTPunName,
                            DT.FCXpdFactor AS FCXsdFactor,
                            DT.FTXpdBarCode AS FTXsdBarCode,
                            DT.FCXpdQty AS FCXsdQty,
                            DT.FCXpdQtyAll AS FCXsdQtyAll,
                            DT.FTXpdRmk AS FTXsdRmk,
                            DT.FDLastUpdOn , DT.FTLastUpdBy ,
                            DT.FDCreateOn , 
                            DT.FTCreateBy ,
                            ISNULL(DT.FCXpdQtyApv,0) AS FCXpdQtyApv,
                            ISNULL(DT.FCXpdQtySo,0) AS FCXpdQtySo, 
                            ISNULL(DT.FCXpdQtyApv,0) - ISNULL(DT.FCXpdQtySo,0) AS QTY
                        FROM TAPTPoDT DT WITH(NOLOCK)
                        WHERE DT.FTXphDocNo ='$tDocNo' ";
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
    
    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMSOCallRefIntDocInsertDTToTemp($paData){

        $tSODocNo        = $paData['tSODocNo'];
        $tSOFrmBchCode   = $paData['tSOFrmBchCode'];
        $tRefType        = $paData['tRefType'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tSOFrmBchCode);
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = "'" . implode ( "', '", $paData['aSeqNo'] ) . "'";
        
        if($tRefType == '1'){
            $tSQL   = "INSERT INTO TCNTDocDTTmp (
                        FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                        FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                        FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                        FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                        FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                        FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                        FTXtdPdtStaSet,FTXtdRmk,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
                    )
                    SELECT
                        '$tSOFrmBchCode' as FTBchCode,
                        '$tSODocNo' as FTXshDocNo,
                        ROW_NUMBER() OVER(ORDER BY DT.FNXsdSeqNo DESC ) AS FNXsdSeqNo,
                        'TARTSoHD' AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXsdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXsdFactor,
                        DT.FTXsdBarCode,
                        '' AS FTSrnCode,
                        PDT.FTPdtStaVatBuy,
                        PDT.FTVatCode AS FTVatCode,
                        VAT.FCVatRate,
                        PDT.FTPdtSaleType AS FTXsdSaleType,
                        PDT.FCPdtCostStd AS FCXsdSalePrice,
                        DT.FCXsdQty,
                        DT.FCXsdQtyAll,
                        DT.FCXsdSetPrice AS FCXsdNetAfHD,
                        0 AS FCXsdAmtB4DisChg,
                        '' AS FTXsdDisChgTxt,
                        0 as FCXsdQtyLef,
                        0 as FCXsdQtyRfn,
                        '' as FTXsdStaPrcStk,
                        PDT.FTPdtStaAlwDis,
                        0 as FNXsdPdtLevel,
                        '' as FTXsdPdtParent,
                        0 as FCXsdQtySet,
                        '' as FTPdtStaSet,
                        '' as FTXsdRmk,   
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM
                        TARTSqDT DT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt           PDT WITH (NOLOCK)   ON DT.FTPdtCode     = PDT.FTPdtCode
                        LEFT JOIN VCN_VatActive     VAT WITH (NOLOCK)   ON PDT.FTVatCode    = VAT.FTVatCode
                        WHERE DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXshDocNo ='$tRefIntDocNo' AND DT.FNXsdSeqNo IN ($aSeqNo) ";
        }elseif($tRefType == '2'){
            $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,
                FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
            )
            SELECT
                '$tSOFrmBchCode' as FTBchCode,
                '$tSODocNo' as FTXshDocNo,
                ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXsdSeqNo,
                'TARTSoHD' AS FTXthDocKey,
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
                PDT.FTPdtSaleType   AS FTXsdSaleType,
                PDT.FCPdtCostStd    AS FCXsdSalePrice,
                ISNULL(DT.FCXpdQtyApv,0) - ISNULL(DT.FCXpdQtySo,0)                      AS FCXpdQty,
                (ISNULL(DT.FCXpdQtyApv,0) - ISNULL(DT.FCXpdQtySo,0))  * FCXpdFactor     AS FCXpdQtyAll,
                DT.FCXpdSetPrice    AS FCXsdNetAfHD,
                0                   AS FCXsdAmtB4DisChg,
                ''                  AS FTXsdDisChgTxt,
                0 AS FCXsdQtyLef,
                0 AS FCXsdQtyRfn,
                '' as FTXsdStaPrcStk,
                PDT.FTPdtStaAlwDis,
                0 as FNXsdPdtLevel,
                '' as FTXsdPdtParent,
                0 as FCXsdQtySet,
                '' as FTPdtStaSet,
                '' as FTXsdRmk,   
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM
                TAPTPoDT DT WITH (NOLOCK)
                LEFT JOIN TCNMPdt           PDT WITH (NOLOCK)   ON DT.FTPdtCode     = PDT.FTPdtCode
                LEFT JOIN VCN_VatActive     VAT WITH (NOLOCK)   ON PDT.FTVatCode    = VAT.FTVatCode
                WHERE DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN ($aSeqNo) ";
        }
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

    //อัพเดทว่าเอกสารใช้เเล้ว
    public function FSoMSOAddUpdateHDDocRef($paDataDocRefSq, $paDataDocRefSo, $paDataDocRefSOExt){
        try {
            $tTableSq = 'TARTSoHDDocRef';
            $tTableSo = 'TARTSoHDDocRef';
            
            if (!empty($paDataDocRefSq) && $paDataDocRefSq != '') {
                $nChhkDataDocRefSq = $this->FSaMIASChkRefDupicate($paDataDocRefSq, $tTableSq);
                if(isset($nChhkDataDocRefSq['rtCode']) && $nChhkDataDocRefSq['rtCode'] == 1){
                    $this->db->where_in('FTAgnCode',$paDataDocRefSq['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$paDataDocRefSq['FTBchCode']);
                    $this->db->where_in('FTXshRefType',$paDataDocRefSq['FTXshRefType']);
                    $this->db->where_in('FTXshRefDocNo',$paDataDocRefSq['FTXshRefDocNo']);
                    $this->db->delete('TARTSoHDDocRef');
                    
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSq);
                }else{
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSq);
                }
            }

            if (!empty($paDataDocRefSo) && $paDataDocRefSo != '') {
                $nChhkDataDocRefSo = $this->FSaMIASChkRefDupicate($paDataDocRefSo, $tTableSo);

                if(isset($nChhkDataDocRefSo['rtCode']) && $nChhkDataDocRefSo['rtCode'] == 1){
                    $this->db->where_in('FTAgnCode',$paDataDocRefSo['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$paDataDocRefSo['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$paDataDocRefSo['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$paDataDocRefSo['FTXshRefType']);
                    $this->db->delete('TARTSoHDDocRef');
    
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSo);
                }else{
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSo);
                }
            }

            if (!empty($paDataDocRefSOExt) && $paDataDocRefSOExt != '') {
                $nChhkDataDocRefSoExt = $this->FSaMIASChkRefDupicate($paDataDocRefSOExt, $tTableSo);
                if(isset($nChhkDataDocRefSoExt['rtCode']) && $nChhkDataDocRefSoExt['rtCode'] == 1){
                    $this->db->where_in('FTAgnCode',$paDataDocRefSOExt['FTAgnCode']);
                    $this->db->where_in('FTBchCode',$paDataDocRefSOExt['FTBchCode']);
                    $this->db->where_in('FTXshDocNo',$paDataDocRefSOExt['FTXshDocNo']);
                    $this->db->where_in('FTXshRefType',$paDataDocRefSOExt['FTXshRefType']);
                    $this->db->delete('TARTSoHDDocRef');
                    
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSOExt);
                }else{
                    //เพิ่มใหม่
                    $this->db->insert('TARTSoHDDocRef',$paDataDocRefSOExt);
                }
            }

        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMIASChkRefDupicate($paDataPrimaryKey, $ptTable){
        try{
            if ($ptTable == 'TARTSoHDDocRef') {
                $tAgnCode       = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode       = $paDataPrimaryKey['FTBchCode'];
                $tDocNo         = $paDataPrimaryKey['FTXshDocNo'];
                $tRefDocType    = $paDataPrimaryKey['FTXshRefType'];

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable
                            WHERE 1=1
                            AND FTAgnCode     = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshDocNo    = '$tDocNo'
                            AND FTXshRefType  = '$tRefDocType'
                        ";
            }else{
                $tAgnCode       = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode       = $paDataPrimaryKey['FTBchCode'];
                $tDocNo         = $paDataPrimaryKey['FTXshDocNo'];
                $tRefDocType    = $paDataPrimaryKey['FTXshRefType'];
                $tRefDocNo      = $paDataPrimaryKey['FTXshRefDocNo'];

                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshRefDocNo as FTXshDocNo
                            FROM $ptTable
                            WHERE 1=1
                            AND FTAgnCode     = '$tAgnCode'
                            AND FTBchCode     = '$tBchCode'
                            AND FTXshRefType  = '$tRefDocType'
                            AND FTXshRefDocNo = '$tRefDocNo'
                        ";
            }
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

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMSoGetDataHDRefTmp($paData){
        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXthDocNo     = $paData['FTXthDocNo'];
        $FTXthDocKey    = $paData['FTXthDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT FTXthDocNo,SO.FTBchCode, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
                    FROM $tTableTmpHDRef 
                    LEFT JOIN TARTSoHD SO ON SO.FTXshDocNo = TCNTDocHDRefTmp.FTXthDocNo
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
    public function FSaMSOAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tRefDocNo = ( empty($paDataWhere['tSORefDocNoOld']) ? $paDataAddEdit['FTXthRefDocNo'] : $paDataWhere['tSORefDocNoOld'] );

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

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMSOMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXshDocNo'];
        $tSessionID   = $this->session->userdata('tSesSessionID');

        $tSQLCheckPOOld   =  "  SELECT FTXshRefDocNo AS FTXshDocNo FROM TARTSoHDDocRef WITH (NOLOCK)
                                WHERE FTXshDocNo  = '$tDocNo' AND FTXshRefKey = 'PO'  ";
        $oQueryOld = $this->db->query($tSQLCheckPOOld);
        $aCheckPOOld = $oQueryOld->result_array();

        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TARTSoHDDocRef');
        }

        //Update PO GENSO
        if ( $oQueryOld->num_rows() > 0 ){
            $aCheckPOOld = $oQueryOld->result_array();
            foreach($aCheckPOOld as $nKey => $aVal){
                $tPODocno = $aVal['FTXshDocNo'];
                $tSQLGetPO   =  " SELECT  MIN(FNStaGenPO) AS FNStaGenPO FROM (
                    SELECT P.FCXpdQty AS FCXpdQtyP, 
                           S.FCXsdQty AS FCXsdQtyS,
                           CASE
                               WHEN P.FCXpdQty > S.FCXsdQty
                               THEN 1 --สั่งแล้วบางส่วน
                               WHEN P.FCXpdQty <= S.FCXsdQty
                               THEN 2 --สั่งครบแล้ว
                               ELSE 0
                           END FNStaGenPO
                    FROM
                    (
                        SELECT HDR.FTXshRefDocNo, 
                               DT.FTPdtCode, 
                               SUM(DT.FCXsdQty) AS FCXsdQty
                        FROM TARTSoDT DT WITH(NOLOCK)
                             INNER JOIN TARTSoHDDocRef HDR WITH(NOLOCK) ON DT.FTXshDocNo = HDR.FTXshDocNo
                        WHERE HDR.FTXshRefType = '1'
                              AND HDR.FTXshRefKey = 'PO'
                              AND HDR.FTXshRefDocNo = '$tPODocno' --เลขที่อ้างอิง PO (Parameter)
                        GROUP BY HDR.FTXshRefDocNo, 
                                 DT.FTPdtCode
                    ) S
                    INNER JOIN
                    (
                        SELECT FTXphDocNo, 
                               FTPdtCode, 
                               SUM(FCXpdQty) AS FCXpdQty
                        FROM TAPTPoDT WITH(NOLOCK)
                        WHERE FTXphDocNo = '$tPODocno' --เลขที่อ้างอิง PO (Parameter)
                        GROUP BY FTXphDocNo, 
                                 FTPdtCode
                    ) P ON S.FTXshRefDocNo = P.FTXphDocNo
                           AND S.FTPdtCode = P.FTPdtCode
                    ) A 
                ";
                $oQueryPO = $this->db->query($tSQLGetPO);
                $aGetPO = $oQueryPO->result_array();
                $nUpdateGenSO = $aGetPO[0]['FNStaGenPO'];
                if($nUpdateGenSO == ''){
                    $this->db->set('FTXphStaGenSO','');
                }else{
                    $this->db->set('FTXphStaGenSO',$nUpdateGenSO);
                }
                $this->db->where('FTXphDocNo',$tPODocno);
                $this->db->update('TAPTPoHD');
            }
        }

        $tSQL   =   "   INSERT INTO TARTSoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
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

        //Insert ใบเสนอราคา
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TARTSqHDDocRef');
        $tSQL   =   "   INSERT INTO TARTSqHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'SO',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'QT'  ";
        $this->db->query($tSQL);

        //Insert ใบสั่งซื้อ
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TAPTPoHDDocRef');
        $tSQL   =   "   INSERT INTO TAPTPoHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'SO',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'PO'  ";
        $this->db->query($tSQL);

        $tSQLCheckPO   =  "   SELECT
                            '' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'SO',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '".$paTableAddUpdate['tTableHD']."'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'PO'  ";
        $oQuery = $this->db->query($tSQLCheckPO);
        if ( $oQuery->num_rows() > 0 ){
            $aCheckPO = $oQuery->result_array();
            foreach($aCheckPO as $nKey => $aVal){
                $tPODocno = $aVal['FTXshDocNo'];
                $tSQLGetPO   =  " SELECT  MIN(FNStaGenPO) AS FNStaGenPO FROM (
                    SELECT P.FCXpdQty AS FCXpdQtyP, 
                           S.FCXsdQty AS FCXsdQtyS,
                           CASE
                               WHEN P.FCXpdQty > S.FCXsdQty
                               THEN 1 --สั่งแล้วบางส่วน
                               WHEN P.FCXpdQty <= S.FCXsdQty
                               THEN 2 --สั่งครบแล้ว
                               ELSE 0
                           END FNStaGenPO
                    FROM
                    (
                        SELECT HDR.FTXshRefDocNo, 
                               DT.FTPdtCode, 
                               SUM(DT.FCXsdQty) AS FCXsdQty
                        FROM TARTSoDT DT WITH(NOLOCK)
                             INNER JOIN TARTSoHDDocRef HDR WITH(NOLOCK) ON DT.FTXshDocNo = HDR.FTXshDocNo
                        WHERE HDR.FTXshRefType = '1'
                              AND HDR.FTXshRefKey = 'PO'
                              AND HDR.FTXshRefDocNo = '$tPODocno' --เลขที่อ้างอิง PO (Parameter)
                        GROUP BY HDR.FTXshRefDocNo, 
                                 DT.FTPdtCode
                    ) S
                    INNER JOIN
                    (
                        SELECT FTXphDocNo, 
                               FTPdtCode, 
                               SUM(FCXpdQty) AS FCXpdQty
                        FROM TAPTPoDT WITH(NOLOCK)
                        WHERE FTXphDocNo = '$tPODocno' --เลขที่อ้างอิง PO (Parameter)
                        GROUP BY FTXphDocNo, 
                                 FTPdtCode
                    ) P ON S.FTXshRefDocNo = P.FTXphDocNo
                           AND S.FTPdtCode = P.FTPdtCode
                    ) A 
                ";
                $oQueryPO = $this->db->query($tSQLGetPO);
                $aGetPO = $oQueryPO->result_array();
                $nUpdateGenSO = $aGetPO[0]['FNStaGenPO'];
                $this->db->set('FTXphStaGenSO',$nUpdateGenSO);
                $this->db->where('FTXphDocNo',$tPODocno);
                $this->db->update('TAPTPoHD');
            }
        }
    }

    //ข้อมูล HDDocRef
    public function FSxMSOMoveHDRefToHDRefTemp($paData){

        $FTXshDocNo     = $paData['FTXthDocNo'];
        $FTSessionID    = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTSessionID',$FTSessionID);
        $this->db->delete('TCNTDocHDRefTmp');

        $tSQL = "   INSERT INTO TCNTDocHDRefTmp (FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate, FTXthDocKey, FTSessionID , FDCreateOn)";
        $tSQL .= "  SELECT
                        FTXshDocNo,
                        FTXshRefDocNo,
                        FTXshRefType,
                        FTXshRefKey,
                        FDXshRefDocDate,
                        'TARTSoHD' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                    FROM TARTSoHDDocRef
                    WHERE FTXshDocNo = '$FTXshDocNo' ";
        $this->db->query($tSQL);
    }

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMSODelHDDocRef($paData){
        $tSODocNo       = $paData['FTXthDocNo'];
        $tSORefDocNo    = $paData['FTXthRefDocNo'];
        $tSODocKey      = $paData['FTXthDocKey'];
        $tSOSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tSOSessionID);
        $this->db->where('FTXthDocKey',$tSODocKey);
        $this->db->where('FTXthRefDocNo',$tSORefDocNo);
        $this->db->where('FTXthDocNo',$tSODocNo);
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


    // หาว่าเอกสารนี้ใช้คลังอนุญาตใบจัดไหม
    public function FSaMSOFindWahouseToPCK($paData){
        $tDocNo = $paData['FTXthDocNo'];
        $tSQL   = " SELECT ISNULL(FTWahStaAlwPLFrmSO, '') AS FTWahStaAlwPLFrmSO 
                    FROM TARTSoHD SoHD WITH(NOLOCK) 
                    INNER JOIN TCNMWaHouse WAH WITH(NOLOCK) ON 
                    SoHD.FTWahCode = WAH.FTWahCode  AND SoHD.FTBchCode = WAH.FTBchCode
                    WHERE SoHD.FTXshDocNo = '$tDocNo' " ;

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

    //อัพเดทหมายเหตุถ้าเอกสารอนุมัติแล้ว
    public function FSaMSOUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXshRmk',$paDataUpdate['FTXshRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXshDocNo',$paDataUpdate['FTXshDocNo']);
        $this->db->update('TARTSoHD');

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

    // List ข้อมูล
    public function FSaMSOGetDataTableListGenPO($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaApprove'];
        $tSearchStaSale         = $aAdvanceSearch['tSearchStaSale'];
        $tSearchStaGenSO         = $aAdvanceSearch['tSearchStaGenSO'];

        $tSQL   =   "   SELECT TOP ". get_cookie('nShowRecordInPageList')." c.* FROM(
                            SELECT * FROM
                                (   SELECT
                                            HD.FTBchCode, 
                                            HD.FTAgnCode,
                                            AGNL.FTAgnName,
                                            BCHL.FTBchName,
                                            AGN.FTAgnRefCst,
                                            CST.FTCstName,
                                            HD.FTXphDocNo, 
                                            HD.FTXphStaDoc,
                                            HD.FTXphStaApv,
                                            HD.FTXphStaApvPdt,
                                            HD.FDCreateOn,
                                            CONVERT(CHAR(10),HD.FDXphDocDate,103) AS FDXphDocDate,
                                            CONVERT(CHAR(5), HD.FDXphDocDate,108) AS FTXphDocTime,
                                            HD.FTXphStaGenSO
                                    FROM
                                    (
                                            SELECT DISTINCT PRS.FTXshRefDocNo AS FTXshRefDocNo
                                            FROM TAPTPoHD HD WITH (NOLOCK)
                                            INNER JOIN TAPTPoHDDocRef REF WITH (NOLOCK) ON HD.FTXphDocNo = REF.FTXshDocNo
                                                                            AND HD.FTBchCode = REF.FTBchCode
                                                                            AND REF.FTXshRefKey = 'PRS'
                                                                            AND REF.FTXshRefType = '1'
                                            INNER JOIN TCNTPdtReqSplHDDocRef PRS WITH (NOLOCK) ON REF.FTXshRefDocNo = PRS.FTXshDocNo
                                                                            AND PRS.FTXshRefKey = 'PO'
                                                                            AND PRS.FTXshRefType = '1' ";
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " WHERE HD.FTXphBchTo IN ('$tSearchBchCodeFrom')";
        }

        $tSQL   .=   "  ) POF
                            INNER JOIN TAPTPoHD         HD      WITH (NOLOCK) ON HD.FTXphDocNo   = POF.FTXshRefDocNo
                            INNER JOIN TCNMAgency       AGN     WITH (NOLOCK) ON HD.FTAgnCode    = AGN.FTAgnCode
                            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON HD.FTBchCode    = BCHL.FTBchCode   AND BCHL.FNLngID    = $nLngID
                            LEFT JOIN TCNMAgency_L      AGNL    WITH (NOLOCK) ON HD.FTAgnCode    = AGNL.FTAgnCode   AND AGNL.FNLngID    = $nLngID
                            LEFT JOIN TCNMCst_L         CST     WITH (NOLOCK) ON AGN.FTAgnRefCst = CST.FTCstCode    AND CST.FNLngID     = $nLngID
                        WHERE 1=1 ";

        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND HD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((HD.FTXphDocNo LIKE '%$tSearchList%') OR (AGNL.FTAgnName LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDocAct ) && !empty($tSearchStaDocAct)){
            if ($tSearchStaDocAct == 3) {
                $tSQL .= " AND HD.FTXphStaDoc = '$tSearchStaDocAct'";
            } elseif ($tSearchStaDocAct == 2) {
                $tSQL .= " AND ISNULL(HD.FTXphStaApv,'') = '' AND HD.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaDocAct'";
            }
        }

        // ค้นหาสถานะใบสั่งขาย
        if(isset($tSearchStaGenSO ) && !empty($tSearchStaGenSO)){
            if ($tSearchStaGenSO == 4) {
                $tSQL .= " AND ( HD.FTXphStaGenSO = '1' OR ISNULL(HD.FTXphStaGenSO,'') = '' )";
            } elseif ($tSearchStaGenSO == 3) {
                $tSQL .= " AND ISNULL(HD.FTXphStaGenSO,'') = ''";
            } elseif ($tSearchStaGenSO == 0) {
                $tSQL .= "";
            }else{
                $tSQL .= " AND HD.FTXphStaGenSO  = '$tSearchStaGenSO'";
            }
        }

        /// ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) {
                $tSQL .= " AND HD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXphStaDocAct = 0";
            }
        }

        // ค้นหาสถานะการขาย
        if (!empty($tSearchStaSale) && ($tSearchStaSale != "0")) {
            if ($tSearchStaSale == 2) {
                $tSQL .= " AND ISNULL(SALE.FTXphRefDocNo,'') <> '' ";
            }else{
                $tSQL .= " AND ISNULL(SALE.FTXphRefDocNo,'') = '' ";
            }
        }

        $tSQL   .=  ") Base) AS c ORDER BY c.FDCreateOn DESC ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $nFoundRow          = 0;
            $nPageAll           = 0;
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

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMSODataTableGenPOGetCst($paData){
        $tCstCode        = $paData;
        $tSQL= "
                SELECT
                Result.* 
            FROM
                (
                SELECT TOP
                    1 TCNMCst.FTCstCode,
                    TCNMCst_L.FTCstName,
                    TCNMCst.FTCstCardID,
                    TCNMCst.FTCstTel,
                    TCNMCstLev.FTPplCode,
                    TCNMCst.FTCstDiscRet,
                    TCNMCst.FTCstStaAlwPosCalSo,
                    TCNMCstCard.FTCstCrdNo 
                FROM
                    TCNMCst
                    LEFT JOIN TCNMCst_L ON TCNMCst_L.FTCstCode = TCNMCst.FTCstCode 
                    AND TCNMCst_L.FNLngID = 1
                    LEFT JOIN TCNMCstCard ON TCNMCst.FTCstCode = TCNMCstCard.FTCstCode
                    LEFT JOIN TCNMCstLev ON TCNMCst.FTClvCode = TCNMCstLev.FTClvCode 
                WHERE
                    1 = 1 
                AND TCNMCst.FTCstStaActive = '1' 
                AND TCNMCst.FTCstCode = '$tCstCode'
                ) AS Result";
        
        $oQuery = $this->db->query($tSQL);
        $oDataList          = $oQuery->result_array();
        unset($oQuery,$tCstCode,$tSQL);
        return $oDataList;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMSOCallGenSORefIntDocInsertDTToTemp($paData){

        $tSODocNo        = $paData['tSODocNo'];
        $tSOFrmBchCode   = $paData['tSOFrmBchCode'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tSOFrmBchCode);
        $this->db->where('FTXthDocNo',$tSODocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        
            $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode, FTXthDocNo, FNXtdSeqNo, FTXthDocKey, FTPdtCode, FTXtdPdtName,
                FTPunCode, FTPunName, FCXtdFactor, FTXtdBarCode, FTSrnCode,
                FTXtdVatType, FTVatCode, FCXtdVatRate, FTXtdSaleType, FCXtdSalePrice,
                FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmtB4DisChg, FTXtdDisChgTxt,
                FCXtdQtyLef, FCXtdQtyRfn, FTXtdStaPrcStk, FTXtdStaAlwDis,
                FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,
                FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy 
            )
            SELECT
                '$tSOFrmBchCode' as FTBchCode,
                '$tSODocNo' as FTXshDocNo,
                ROW_NUMBER() OVER(ORDER BY DT.FNXpdSeqNo DESC ) AS FNXsdSeqNo,
                'TARTSoHD' AS FTXthDocKey,
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
                PDT.FTPdtSaleType AS FTXsdSaleType,
                PDT.FCPdtCostStd AS FCXsdSalePrice,
                DT.FCXpdQty,
                DT.FCXpdQtyAll,
                DT.FCXpdSetPrice AS FCXsdNetAfHD,
                0 AS FCXsdAmtB4DisChg,
                '' AS FTXsdDisChgTxt,
                0 as FCXsdQtyLef,
                0 as FCXsdQtyRfn,
                '' as FTXsdStaPrcStk,
                PDT.FTPdtStaAlwDis,
                0 as FNXsdPdtLevel,
                '' as FTXsdPdtParent,
                0 as FCXsdQtySet,
                '' as FTPdtStaSet,
                '' as FTXsdRmk,   
                CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
            FROM
                TAPTPoDT DT WITH (NOLOCK)
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
                WHERE DT.FTXphDocNo ='$tRefIntDocNo' ";
        
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

    // เช็คสต็อกก่อนส่ง MQ
    public function FSaMSODCheckStk($ptSODocNo){

        $tSQL = " SELECT
        DT.FTBchCode,
        DT.FTWahCode,
        DT.FTPdtCode,
        ISNULL( STK.FCStkQty, 0 ) AS FCStkQty,
        DT.FCDTQty,
        ( ISNULL( STK.FCStkQty, 0 ) - ISNULL( DT.FCDTQty, 0 ) ) AS FCQtyDiff,
        ( ISNULL( STK.FCStkQty, 0 ) - ISNULL( DT.FCDTQty, 0 ) ) * ( - 1 ) AS FCQtyDiff2,
        DT.FTPdtName
    FROM
        (
        SELECT
            HD.FTBchCode,
            HD.FTWahCode,
            DT.FTPdtCode,
            SUM ( DT.FCXsdQtyAll ) AS FCDTQty,
		    PDTL.FTPdtName
        FROM
            TARTSoDT DT WITH ( NOLOCK ) INNER JOIN TARTSoHD HD WITH ( NOLOCK ) ON HD.FTXshDocNo = DT.FTXshDocNo INNER JOIN TCNMPdt_L PDTL WITH(NOLOCK) ON DT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = 1
        WHERE
            DT.FTXshDocNo = '$ptSODocNo' 
        GROUP BY
            DT.FTPdtCode,HD.FTBchCode,HD.FTWahCode,PDTL.FTPdtName
        ) DT
        LEFT JOIN TCNTPdtStkBal STK WITH ( NOLOCK ) ON STK.FTPdtCode = DT.FTPdtCode 
        AND STK.FTBchCode = DT.FTBchCode 
        AND STK.FTWahCode = DT.FTWahCode 
    WHERE
        ( ISNULL( STK.FCStkQty, 0 ) - ISNULL( DT.FCDTQty, 0 ) ) < 0 
    ORDER BY
        DT.FTPdtCode ASC ";
        
        $oQuery = $this->db->query($tSQL);

        if ( $oQuery->num_rows() > 0 ){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'nStaItems' => $oDataList,
                'nStaEvent' => '800',
                'tStaMessg' => 'StkFull'
            );
        }else{
            $aResult = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Add/Edit HDDocRef Success'
            );
        }

        return $aResult;
    }
}