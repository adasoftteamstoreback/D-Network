<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Withholdingtax_Model extends CI_Model {

    public function FSaMWhTaxGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        WTHD.FTBchCode,
                                        BCHL.FTBchName,
                                        WTHD.FTXshDocNo,
                                        CONVERT(CHAR(10),WTHD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), WTHD.FDXshDocDate,108) AS FTXshDocTime,
                                        WTHD.FTXshRefInt,
                                        CONVERT(CHAR(10),WTHD.FDXshRefIntDate,103) AS FDXshRefIntDate,
                                        CONVERT(CHAR(5), WTHD.FDXshRefIntDate,108) AS FTXshRefIntTime,
                                        CSTL.FTCstName,
                                        WTHD.FTXshStaDoc,
                                        WTHD.FDCreateOn,
                                        WTHD.FTXshStaApv,
                                        USRL.FTUsrName
                                    FROM TPSTWhTaxHD   WTHD  WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON WTHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON WTHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMCst_L     CSTL    WITH (NOLOCK) ON WTHD.FTCstCode     = CSTL.FTCstCode    AND CSTL.FNLngID = $nLngID
                                WHERE 1=1
        ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND WTHD.FTBchCode IN ($tBchCode)
            ";
        }
        
        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND WTHD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((WTHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),WTHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((WTHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (WTHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((WTHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (WTHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND WTHD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(WTHD.FTXshStaApv,'') = '' AND WTHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND WTHD.FTXshStaApv = '$tSearchStaDoc'";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

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

    public function FSnMSOCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];

        $tSQL   =   "   SELECT COUNT (WTHD.FTXshDocNo) AS counts
                        FROM TPSTWhTaxHD WTHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON WTHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1
                    ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND WTHD.FTBchCode IN ($tBchCode)
            ";
        }

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND WTHD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND WTHD.FTShpCode = '$tUserLoginShpCode' ";
        }
        
        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((WTHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),WTHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((WTHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (WTHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((WTHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (WTHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND WTHD.FTXshStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(WTHD.FTXshStaApv,'') = '' AND WTHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND WTHD.FTXshStaApv = '$tSearchStaDoc'";
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

    public function FSaMWhTaxGetDataView($paData)
    {
        $nLangID  = $this->session->userdata("tLangID");
        $tBchCode = $paData['aBchCode'];
        $tDocNo   = $paData['aDocNo'];

        $tSQL       = " SELECT
                            CSTL.FTCstName,
                            WTHD.FTXshDocNo,
                            WTHD.FDXshDocDate AS FDXshDocDate,
                            CONVERT(CHAR(5), WTHD.FDXshDocDate,108) AS FTXshDocTime,
                            WTHD.FTXshStaDoc,
                            WTHD.FDLastUpdOn AS FDLastUpdOn,
                            WTHD.FTXshStaApv,
                            USRL.FTUsrName,
                            WTHD.FTXshRefInt,
                            WTHD.FDXshRefIntDate AS FDXshRefIntDate,
                            WTHD.FTXshRefExt,
                            CONVERT(CHAR(10),WTHD.FDXshRefExtDate,103) AS FDXshRefExtDate,
                            WTHD.FTXshVATInOrEx,
                            WTHD.FTXshCshOrCrd,
                            WHCST.FNXshCrTerm,
                            CONVERT(CHAR(10),WHCST.FDXshDueDate,103) AS FDXshDueDate,
                            WHCST.FTXshCstName,
                            WTADL.FTAddTaxNo,
                            WTADL.FTAddStaHQ,
                            WTADL.FTAddTel,
                            WTADL.FTAddV2Desc1,
                            WTADL.FTAddV2Desc2,
                            WTADL.FTAddStaBusiness,
                            WTADL.FTAddStaBchCode,
                            WTADL.FTAddFax,
                            WTADL.FTAddVersion,
                            WTADL.FTAddV1No,
                            WTADL.FTAddV1Soi,
                            WTADL.FTAddV1Village,
                            WTADL.FTAddV1Road,
                            WTADL.FTAddV1SubDist,
                            WTADL.FTAddV1DstCode,
                            WTADL.FTAddV1PvnCode,
                            WTADL.FTAddV1PostCode,
                            WTHD.FCXshTotal,
                            WTHD.FCXshVat,
                            WTHD.FTXshRmk
                            
                        FROM TPSTWhTaxHD WTHD WITH (NOLOCK)
                        LEFT JOIN TCNMUser_L         USRL    WITH (NOLOCK)   ON WTHD.FTLastUpdBy    = USRL.FTUsrCode	AND USRL.FNLngID	    = $nLangID
                        LEFT JOIN TCNMCst_L          CSTL    WITH (NOLOCK)   ON WTHD.FTCstCode      = CSTL.FTCstCode    AND CSTL.FNLngID	    = $nLangID
                        LEFT JOIN TPSTWhTaxHDCst     WHCST   WITH (NOLOCK)   ON WTHD.FTXshDocNo     = WHCST.FTXshDocNo
                        LEFT JOIN TCNMTaxAddress_L   WTADL   WITH (NOLOCK)   ON WHCST.FTXshAddrTax  = WTADL.FTAddTaxNo   AND WTADL.FNLngID	    = $nLangID
                        WHERE 1=1 AND WTHD.FTBchCode = '$tBchCode' AND WTHD.FTXshDocNo = '$tDocNo'
                    ";      
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );

            $nAddVersion = $aResult['raItems']['FTAddVersion'];
            if ($nAddVersion = 1) {
                $tSQL2 = " SELECT Result.* FROM
                        (SELECT
                                TCNMTaxAddress_L.FTAddV1No,
                                TCNMTaxAddress_L.FTAddV1Soi,
                                TCNMTaxAddress_L.FTAddV1Village,
                                TCNMTaxAddress_L.FTAddV1Road,
                                TCNMSubDistrict_L.FTSudName,
                                TCNMDistrict_L.FTDstName,
                                TCNMProvince_L.FTPvnName,
                                TCNMTaxAddress_L.FTAddV1PostCode
                        FROM TCNMTaxAddress_L 
                        LEFT JOIN TCNMProvince_L On TCNMTaxAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = 1 
                        LEFT JOIN TCNMDistrict_L On TCNMTaxAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = 1 
                        LEFT JOIN TCNMSubDistrict_L On TCNMTaxAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = 1 
                        WHERE 1=1 AND TCNMTaxAddress_L.FTAddVersion = 1 AND TCNMTaxAddress_L.FNLngID = 1 ) AS Result
                ";
                $oQuery2 = $this->db->query($tSQL2);
                if ($oQuery2->num_rows() > 0){
                    $aDetail2 = $oQuery2->row_array();
                    $aResult    = array(
                        'raItems'   => $aDetail,
                        'raItems2'   => $aDetail2,
                        'rtCode'    => '1',
                        'rtDesc'    => 'success',
                    );
                }else{
                    $aResult    = array(
                        'raItems'   => $aDetail,
                        'rtCode'    => '1',
                        'rtDesc'    => 'success',
                    );
                }
            }
            
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }

    public function FSaMWhTaxGetDetailList($paData)
    {
        try {
            $nLangID  = $this->session->userdata("tLangID");
            $tBchCode = $paData['aBchCode'];
            $tDocNo   = $paData['aDocNo'];

            $tSQL       = " SELECT
                                WTDT.FTXsdPdtName,
                                WTDT.FTPdtCode,
                                WTDT.FCXsdWhtRate,
                                WTDT.FTXsdWhType,
                                WTDT.FCXsdNet,
                                WTDT.FTXsdRmk,
                                WTDT.FCXsdWhtAmt
                            
                            FROM TPSTWhTaxHD WTHD WITH (NOLOCK)
                            LEFT JOIN TPSTWhTaxDT WTDT WITH (NOLOCK)   ON WTDT.FTXshDocNo = WTHD.FTXshDocNo
                            WHERE 1=1 AND WTHD.FTBchCode = '$tBchCode' AND WTHD.FTXshDocNo = '$tDocNo'
                            
                        ";
            
            $oQuery = $this->db->query($tSQL);
            $aDetailList = $oQuery->result_array();

            $tSQL2       = " SELECT
                                SUM(WTDT.FCXsdWhtAmt) AS Total
                            FROM TPSTWhTaxDT WTDT WITH (NOLOCK)
                            WHERE 1=1 AND WTDT.FTBchCode = '$tBchCode' AND WTDT.FTXshDocNo = '$tDocNo'
                            
                        ";
            
            $oQuery2 = $this->db->query($tSQL2);
            $aDetailLastBill = $oQuery2->result_array();

            $aResult    = array(
                'raItems'       => $aDetailList,
                'raItemLast'    => $aDetailLastBill
            );
        return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    public function FSaMWhTaxGetRefFile($paData)
    {
        try {
            $nLangID  = $this->session->userdata("tLangID");
            $tBchCode = $paData['aBchCode'];
            $tDocNo   = $paData['aDocNo'];

            $tSQL       = " SELECT
                                FLO.FTFleObj,
                                FLO.FTFleName
                            
                            FROM TPSTWhTaxHD WTHD WITH (NOLOCK)
                            LEFT JOIN TCNMFleObj FLO WITH (NOLOCK)   ON WTHD.FTXshDocNo = FLO.FTFleRefID1
                            WHERE 1=1 AND WTHD.FTBchCode = '$tBchCode' AND WTHD.FTXshDocNo = '$tDocNo'
                            
                        ";
            $oQuery = $this->db->query($tSQL);
            $aData = $oQuery->result_array();

            $aRefFile  = array(
                'raItems' => $aData,
            );
        return $aRefFile;
        } catch (Exception $Error) {
            return $Error;
        }
    }

}

/* End of file ModelName.php */
