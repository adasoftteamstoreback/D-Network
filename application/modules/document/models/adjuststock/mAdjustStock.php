<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mAdjustStock extends CI_Model{

    // Functionality: Data List HD Adjust Stock
    // Parameters: function parameters
    // Creator:  06/06/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Array
    // Return Type: Array
    public function FSaMASTGetDataTable($paDataCondition){
        $nLngID         = $paDataCondition['FNLngID'];
        $aAdvanceSearch = $paDataCondition['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        // Advance Search
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaPrcStk   = $aAdvanceSearch['tSearchStaPrcStk'];
        $tUsrBchCode        = $this->session->userdata("tSesUsrBchCodeMulti");
        
        /** ค้นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร */
        $tWhereSearchAll    = "";
        if (@$tSearchList != '') {
            $tWhereSearchAll = " AND ((AST.FTAjhDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),AST.FDAjhDocDate,103) LIKE '%$tSearchList%'))";
        }

        // Check User Level Branch HQ OR Bch Or Shop
        $tUserLevel = $this->session->userdata("tSesUsrLevel");
        $tWhereBch  = "";
        $tWhereShp  = "";
        if (isset($tUserLevel) && !empty($tUserLevel) && $tUserLevel == "BCH") {
            // Check User Level BCH
            $tWhereBch  =   " AND AST.FTBchCode IN (" . $tUsrBchCode . ") ";
        }
        if (isset($tUserLevel) && !empty($tUserLevel) && $tUserLevel == "SHP") {
            // Check User Level SHP
            $tSHP = $this->session->userdata('tSesUsrShpCodeDefault');
            $tWhereShp  =   " AND AST.FTAjhShopTo = '$tSHP' ";
        }
        $tWhereBchFrmTo     = "";
        if ($this->session->userdata("tSesUsrLevel") == "HQ") {
            /* ค้นหาจากสาขา - ถึงสาขา */
            $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
            $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];

            if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)) {
                $tWhereBchFrmTo = " AND ((AST.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (AST.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
            }
        } else {
            $tWhereBchFrmTo .= " AND AST.FTBchCode IN (" . $tUsrBchCode . ")";
        }
        /** ค้นหาจากวันที่ - ถึงวันที่ */
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tWhereDateFrmTo    = "";
        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tWhereDateFrmTo = " AND ((AST.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (AST.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        $tWhereStaDoc   = "";
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tWhereStaDoc .= " AND AST.FTAjhStaDoc = '$tSearchStaDoc'";
            } else if ($tSearchStaDoc == 2) {
                $tWhereStaDoc .= " AND ISNULL(AST.FTAjhStaApv,'') = '' AND AST.FTAjhStaDoc != '3'";
            } else if ($tSearchStaDoc == 1) {
                $tWhereStaDoc .= " AND AST.FTAjhStaApv = '$tSearchStaDoc'";
            }
        }
        /* ค้นหาสถานะประมวลผล */
        $tSearchStaPrcStk   = $aAdvanceSearch['tSearchStaPrcStk'];
        $tWhereStaPrcStk    = "";
        if (!empty($tSearchStaPrcStk) && ($tSearchStaPrcStk != "0")) {
            if ($tSearchStaPrcStk == 3) {
                $tWhereStaPrcStk = " AND (AST.FTAjhStaPrcStk = '$tSearchStaPrcStk' OR ISNULL(AST.FTAjhStaPrcStk,'') = '') ";
            } else {
                $tWhereStaPrcStk = " AND AST.FTAjhStaPrcStk = '$tSearchStaPrcStk'";
            }
        }
        $tSQL   = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')." HD.* 
            FROM (  
                SELECT  DISTINCT 
                    AST.FTBchCode, 
                    BCHL.FTBchName, 
                    AST.FTAjhDocNo, 
                    CONVERT(CHAR(10),AST.FDAjhDocDate,103)   AS FDAjhDocDate,
                    CONVERT(CHAR(5), AST.FDAjhDocDate, 108)  AS FDAjhDocTime,
                    AST.FTAjhStaDoc,
                    AST.FTAjhStaApv,
                    AST.FTAjhStaPrcStk,
                    AST.FTCreateBy,
                    AST.FDCreateOn,
                    AST.FTAjhStkApvCode,
                    AST.FTAjhCountType,
                    USRL.FTUsrName  AS FTCreateByName,
                    AST.FTAjhApvCode,
                    USRLAPV.FTUsrName   AS FTAjhApvName,
                    HQAPV.FTUsrName   AS FTAjhStkApvName
                FROM [TCNTPdtAdjStkHD] AST WITH (NOLOCK) 
                LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON AST.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON AST.FTCreateBy     = USRL.FTUsrCode    AND USRL.FNLngID    = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON AST.FTAjhApvCode   = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                LEFT JOIN TCNMUser_L    HQAPV   WITH (NOLOCK) ON AST.FTAjhStkApvCode   = HQAPV.FTUsrCode AND HQAPV.FNLngID  = ".$this->db->escape($nLngID)."
                WHERE 1=1 AND AST.FTAjhDocType = '3' 
                " . $tWhereSearchAll . "
                " . $tWhereBch . "
                " . $tWhereShp . "
                " . $tWhereBchFrmTo . "
                " . $tWhereDateFrmTo . "
                " . $tWhereStaDoc . "
                " . $tWhereStaPrcStk . "
            ) HD
            INNER JOIN (
                SELECT DISTINCT FTAjhDocNo  FROM TCNTPdtAdjStkDT WITH(NOLOCK) WHERE FNAjdLayRow = 0 AND FNAjdLayCol = 0 
            )DT ON HD .FTAjhDocNo = DT.FTAjhDocNo
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aDataList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }

        unset($nLngID,$aAdvanceSearch,$tSearchList);
        unset($tSearchBchCodeFrom,$tSearchBchCodeTo,$tSearchDocDateFrom,$tSearchDocDateTo,$tSearchStaDoc,$tSearchStaPrcStk,$tUsrBchCode);
        unset($tWhereSearchAll,$tWhereBch,$tWhereShp,$tWhereBchFrmTo,$tWhereDateFrmTo,$tWhereStaDoc,$tWhereStaPrcStk);
        unset($tSQL,$oQuery,$aDataList);
        unset($paDataCondition);
        return $aResult;
    }

    // Functionality : Delete HD/DT Document Adjust Stock
    // Parameters : function parameters
    // Creator : 07/06/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Array Status Delete
    // Return Type : array
    public function FSnMASTDelDocument($paDataDoc){
        $tASTDocNo  = $paDataDoc['tASTDocNo'];
        $this->db->trans_begin();
        // Document HD 
        $this->db->where_in('FTAjhDocNo', $tASTDocNo);
        $this->db->delete('TCNTPdtAdjStkHD');
        // Document DT
        $this->db->where_in('FTAjhDocNo', $tASTDocNo);
        $this->db->delete('TCNTPdtAdjStkDT');
        // Document Temp
        $this->db->where_in('FTXthDocNo', $tASTDocNo);
        $this->db->where_in('FTXthDocKey', 'TCNTPdtAdjStkHD');
        $this->db->delete('TCNTDocDTTmp');
        //Document Temp
        $this->db->where_in('FTXthDocNo', $tASTDocNo);
        $this->db->delete('TCNTDocHDDisTmp');
        //Document Temp
        $this->db->where_in('FTXthDocNo', $tASTDocNo);
        $this->db->delete('TCNTDocDTDisTmp');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStaDeleteDoc  = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStaDeleteDoc  = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        unset($tASTDocNo,$paDataDoc);
        return $aStaDeleteDoc;
    }

    // Functionality: Get Shop Code From User Login
    // Parameters: function parameters
    // Creator: 07/06/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Array Status Delete
    // ReturnType: array
    public function FSaMASTGetShpCodeForUsrLogin($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tUsrLogin  = $paDataShp['tUsrLogin'];
        $tSQL       = "
            SELECT
                UGP.FTBchCode,
                BCHL.FTBchName,
                MCHL.FTMerCode,
                MCHL.FTMerName,
                UGP.FTShpCode,
                SHPL.FTShpName,
                SHP.FTShpType,
                SHP.FTWahCode   AS FTWahCode,
                WAHL.FTWahName  AS FTWahName
            FROM TCNTUsrGroup UGP           WITH (NOLOCK)
            LEFT JOIN TCNMBranch BCH        WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode 
            LEFT JOIN TCNMBranch_L BCHL     WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMShop SHP          WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMShop_L  SHPL      WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMMerchant_L MCHL   WITH (NOLOCK) ON SHP.FTMerCode = MCHL.FTMerCode AND MCHL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode AND UGP.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = ".$this->db->escape($nLngID)."
            WHERE FTUsrCode = ".$this->db->escape($tUsrLogin)." 
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult    = $oQuery->row_array();
        } else {
            $aResult    = "";
        }
        unset($nLngID,$tUsrLogin,$tSQL,$oQuery);
        unset($paDataShp);
        return $aResult;
    }

    // Functionality : Clear Product In DTTemp
    // Parameters : function parameters
    // Creator : 12/06/2019 Wasin(Yoshi)
    // LastModified : -
    // Return : array
    // Return Type : array
    public function FSxMASTClearPdtInTmp($ptTblSelectData){
        $tXthDocKey = $ptTblSelectData;
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTXthDocKey', $tXthDocKey);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');
        unset($tXthDocKey,$tSessionID,$ptTblSelectData);
    }

    //Functionality : Function Get Pdt From Temp List Page
    //Parameters : function parameters
    //Creator : 10/06/2019 Wasin(Yoshi)
    //Last Modified : -
    //Return : array Data Doc DT Temp
    //Return Type : array
    public function FSaMASTGetDTTempListPage($paDataWhere){
        $tASTXthDocNo       = $paDataWhere['FTXthDocNo'];
        $tASTXthDocKey      = $paDataWhere['FTXthDocKey'];
        $tASTSesSessionID   = $paDataWhere['FTSessionID'];
        $tSQL               = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                DOCTMP.FTBchCode,
                DOCTMP.FTXthDocNo,
                ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,DOCTMP.FNXtdSeqNo AS FNXtdSeqNoDel,
                DOCTMP.FTXthDocKey,DOCTMP.FTPdtCode,DOCTMP.FTXtdPdtName,DOCTMP.FTPunCode,DOCTMP.FTPunName,DOCTMP.FCXtdFactor,
                DOCTMP.FTXtdBarCode,DOCTMP.FTXtdVatType,DOCTMP.FTVatCode,DOCTMP.FCXtdVatRate,DOCTMP.FCXtdQty,DOCTMP.FCXtdQtyAll,
                DOCTMP.FCXtdSetPrice,DOCTMP.FCXtdAmt,DOCTMP.FCXtdVat,DOCTMP.FCXtdVatable,DOCTMP.FCXtdNet,DOCTMP.FCXtdCostIn,
                DOCTMP.FCXtdCostEx,DOCTMP.FTXtdStaPrcStk,DOCTMP.FNXtdPdtLevel,DOCTMP.FTXtdPdtParent,DOCTMP.FCXtdQtySet,
                DOCTMP.FTXtdPdtStaSet,DOCTMP.FTXtdRmk,DOCTMP.FTXtdBchRef,DOCTMP.FTXtdDocNoRef,DOCTMP.FCXtdPriceRet,DOCTMP.FCXtdPriceWhs,
                DOCTMP.FCXtdPriceNet,DOCTMP.FTXtdShpTo,DOCTMP.FTXtdBchTo,DOCTMP.FTSrnCode,DOCTMP.FTXtdSaleType,DOCTMP.FCXtdSalePrice,
                DOCTMP.FCXtdAmtB4DisChg,DOCTMP.FTXtdDisChgTxt,DOCTMP.FCXtdDis,DOCTMP.FCXtdChg,DOCTMP.FCXtdNetAfHD,DOCTMP.FCXtdWhtAmt,
                DOCTMP.FTXtdWhtCode,DOCTMP.FCXtdWhtRate,DOCTMP.FCXtdQtyLef,DOCTMP.FCXtdQtyRfn,DOCTMP.FTXtdStaAlwDis,DOCTMP.FTSessionID,
                DOCTMP.FTPdtName,DOCTMP.FCPdtUnitFact,DOCTMP.FCAjdWahB4Adj,DOCTMP.FNAjdLayCol,DOCTMP.FNAjdLayRow,
                DOCTMP.FCAjdSaleB4AdjC1,DOCTMP.FDAjdDateTimeC1,DOCTMP.FCAjdUnitQtyC1,DOCTMP.FCAjdQtyAllC1,DOCTMP.FCAjdSaleB4AdjC2,DOCTMP.FDAjdDateTimeC2,
                DOCTMP.FCAjdUnitQtyC2,DOCTMP.FCAjdQtyAllC2,DOCTMP.FCAjdUnitQty,DOCTMP.FDAjdDateTime,DOCTMP.FCAjdQtyAll,DOCTMP.FCAjdQtyAllDiff,
                DOCTMP.FTAjdPlcCode,DOCTMP.FTPgpChain,DOCTMP.FDLastUpdOn,DOCTMP.FDCreateOn,DOCTMP.FTLastUpdBy,DOCTMP.FTCreateBy,
                (DOCTMP.FCPdtUnitFact * (DOCTMP.FCAjdUnitQtyC1)) AS FCAfterCount
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            LEFT JOIN (
                SELECT TOP 1
                    FCAjdWahB4Adj,
                    FTAjhDocNo 
                FROM TCNTPdtAdjStkDT WITH(NOLOCK)
                WHERE FTAjhDocNo = '$tASTXthDocNo'
                ORDER BY FCAjdWahB4Adj DESC
            ) ODT ON ODT.FTAjhDocNo = DOCTMP.FTXthDocNo
            WHERE  DOCTMP.FTSessionID <> ''
        ";
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tASTXthDocNo'";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tASTXthDocKey'";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tASTSesSessionID'";
        $tSQL   .= " ORDER BY DOCTMP.FNXtdSeqNo ASC";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList      = $oQuery->result_array();
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
        unset($tASTXthDocNo,$tASTXthDocKey,$tASTSesSessionID);
        unset($tSQL,$oQuery,$aDataList);
        unset($paDataWhere);
        return $aDataReturn;
    }


    public function FSnMTFWCheckPdtTempForTransfer($tDocNo){
        $tSQL   = "
            SELECT COUNT(FNXtdSeqNo) AS nSeqNo 
            FROM TCNTDocDTTmp WITH (NOLOCK) 
            WHERE FTXthDocKey = 'TCNTPdtAdjStkHD' 
            AND FTXthDocNo  = '" . $tDocNo . "' 
            AND FTSessionID = '" . $this->session->userdata('tSesSessionID') . "'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $nDataReturn    = $oQuery->row_array()["nSeqNo"];
        } else {
            $nDataReturn    = 0;
        }
        unset($tSQL,$oQuery,$tDocNo);
        return $nDataReturn;
    }

    //Functionality : Function Get Count From Temp
    //Parameters : function parameters
    //Creator : 21/06/2019 Bell
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkGetCountDTTemp($paDataWhere){
        $tSQL   = "
            SELECT 
                COUNT(DOCTMP.FTXthDocNo) AS counts
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE DOCTMP.FTSessionID <> ''
        ";
        $tAjhDocNo      = $paDataWhere['FTAjhDocNo'];
        $tXthDocKey     = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";
        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";
        $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result_array();
            $aResult = $oDetail[0]['counts'];
        } else {
            $aResult = 0;
        }
        unset($tSQL,$tAjhDocNo,$tXthDocKey,$tSesSessionID,$tSQL,$oQuery,$oDetail);
        unset($paDataWhere);
        return $aResult;
    }

    //Functionality : Function Get Data Pdt
    //Parameters : function parameters
    //Creator : 21/06/2019 Witsarut(Bell)
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkGetDataPdt($paData){
        $tPdtCode   = $paData['FTPdtCode'];
        $FTPunCode  = $paData['FTPunCode'];
        $FTBarCode  = $paData['FTBarCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "
            SELECT
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
            FROM TCNMPdt PDT WITH(NOLOCK)
            LEFT JOIN TCNMPdt_L PDTL WITH(NOLOCK) ON PDT.FTPdtCode = PDTL.FTPdtCode   AND PDTL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMPdtPackSize PKS  WITH(NOLOCK) ON PDT.FTPdtCode = PKS.FTPdtCode AND PKS.FTPunCode = ".$this->db->escape($FTPunCode)."
            LEFT JOIN TCNMPdtUnit_L UNTL  WITH(NOLOCK)  ON UNTL.FTPunCode = ".$this->db->escape($FTPunCode)." AND UNTL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMPdtBar BAR  WITH(NOLOCK)  ON PKS.FTPdtCode = BAR.FTPdtCode AND BAR.FTPunCode = ".$this->db->escape($FTPunCode)." 
            LEFT JOIN TCNMPdtLoc_L PDTLOCL WITH(NOLOCK) ON PDTLOCL.FTPlcCode = BAR.FTPlcCode AND PDTLOCL.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN (
                SELECT FTVatCode, FCVatRate, FDVatStart   
                FROM TCNMVatRate WITH(NOLOCK)
                WHERE GETdate()> FDVatStart
            ) VAT ON PDT.FTVatCode=VAT.FTVatCode 
            LEFT JOIN TCNTPdtSerial PDTSRL          ON PDT.FTPdtCode = PDTSRL.FTPdtCode
            LEFT JOIN TCNMPdtSpl SPL                ON PDT.FTPdtCode = SPL.FTPdtCode  AND BAR.FTBarCode = SPL.FTBarCode
            LEFT JOIN TCNMPdtCostAvg CAVG           ON PDT.FTPdtCode = CAVG.FTPdtCode
            WHERE 1 = 1";

        if ($tPdtCode != "") {
            $tSQL   .= "AND PDT.FTPdtCode = '$tPdtCode'";
        }

        if ($FTBarCode != "") {
            $tSQL   .= "AND BAR.FTBarCode = '$FTBarCode'";
        }
        $tSQL   .= " ORDER BY FDVatStart DESC";

        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItem'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        unset($tPdtCode,$FTPunCode,$FTBarCode,$nLngID,$tSQL,$oQuery,$oDetail,$jResult);
        unset($paData);
        return $aResult;
    }

    //Functionality : Function Add DT To Temp
    //Parameters : function parameters
    //Creator : 21/01/2019 Bell
    //Last Modified : -
    //Return : Status Add
    //Return Type : array
    public function FSaMAdjStkInsertPDTToTemp($paData, $paDataWhere){
        if ($paDataWhere['nAdjStkSubOptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   = "
                SELECT FNXtdSeqNo, FCXtdQty,FCXtdFactor 
                FROM TCNTDocDTTmp WITH(NOLOCK)
                WHERE FTBchCode  = '" . $paDataWhere['FTBchCode'] . "' 
                AND FTXthDocNo   = '" . $paDataWhere['FTAjhDocNo'] . "'
                AND FTAjdPlcCode = '" . $paDataWhere['FTPlcCode'] . "'
                AND FTXthDocKey  = '" . $paDataWhere['FTXthDocKey'] . "'
                AND FTSessionID  = '" . $paDataWhere['FTSessionID'] . "'
                AND FTPdtCode    = '" . $paData["raItem"]["FTPdtCode"] . "' 
                AND FTXtdBarCode = '" . $paData["raItem"]["FTBarCode"] . "' ORDER BY FNXtdSeqNo
            ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $aResult = $oQuery->row_array();
                $tSQL   = "
                    UPDATE TCNTDocDTTmp SET
                    FCXtdQty = '" . ($aResult["FCXtdQty"] + 1) . "',
                    FCXtdQtyAll = '" . (($aResult["FCXtdQty"] + 1) * $aResult["FCXtdFactor"]) . "'
                    WHERE 
                    FTBchCode    = '" . $paDataWhere['FTBchCode'] . "' AND
                    FTXthDocNo   = '" . $paDataWhere['FTAjhDocNo'] . "' AND
                    FTAjdPlcCode   = '" . $paDataWhere['FTPlcCode'] . "' AND
                    FNXtdSeqNo   = '" . $aResult["FNXtdSeqNo"] . "' AND
                    FTXthDocKey  = '" . $paDataWhere['FTXthDocKey'] . "' AND
                    FTSessionID  = '" . $paDataWhere['FTSessionID'] . "' AND
                    FTPdtCode    = '" . $paData["raItem"]["FTPdtCode"] . "' AND 
                    FTXtdBarCode = '" . $paData["raItem"]["FTBarCode"] . "'";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            } else {
                $paData = $paData['raItem'];
                //เพิ่ม
                $this->db->insert('TCNTDocDTTmp', array(
                    'FTBchCode'         => $paDataWhere['FTBchCode'],
                    'FTXthDocNo'        => $paDataWhere['FTAjhDocNo'],
                    'FNXthSeqNo'        => $paDataWhere['nCounts'],
                    'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                    'FTPdtCode'         => $paData['FTPdtCode'],
                    'FTXtdPdtName'      => $paData['FTPdtName'],
                    'FTAjdPlcCode'      => $paDataWhere['FTPlcCode'],
                    'FCXtdFactor'       => $paData['FCPdtUnitFact'],
                    'FTPunCode'         => $paData['FTPunCode'],
                    'FTPunName'         => $paData['FTPunName'],
                    'FTXtdBarCode'      => $paDataWhere['FTBarCode'],
                    'FTSessionID'       => $paDataWhere['FTSessionID'],
                    'FCXtdQtyAll'       => 0,
                    'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d h:i:sa'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                ));
                $this->db->last_query();
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
            }
        } else {
            // เพิ่มแถวใหม่
            $paData = $paData['raItem'];
            // เพิ่ม
            $this->db->insert('TCNTDocDTTmp', array(

                'FTBchCode'         => $paDataWhere['FTBchCode'],
                'FTXthDocNo'        => $paDataWhere['FTAjhDocNo'],
                'FNXtdSeqNo'        => $paDataWhere['nCounts'],
                'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                'FTPdtCode'         => $paData['FTPdtCode'],
                'FTXtdPdtName'      => $paData['FTPdtName'],
                'FTPunCode'         => $paData['FTPunCode'],
                'FTPunName'         => $paData['FTPunName'],
                'FCXtdFactor'       => $paData['FCPdtUnitFact'],
                'FTXtdBarCode'      => $paDataWhere['FTBarCode'],
                'FTAjdPlcCode'      => $paDataWhere['FTPlcCode'],
                'FTSessionID'       => $paDataWhere['FTSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d h:i:sa'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            ));
            $this->db->last_query();
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
        }
    }

    //Functionality : Function Update Doc No Into Temp 
    //Parameters : function parameters
    //Creator : 22/06/2019 Bell
    //Last Modified : -
    //Return : Status update
    //Return Type : array
    public function FSaMASTAddUpdateDocNoInDocTemp($paDataWhere){
        try {
            $this->db->set('FTXthDocNo', $paDataWhere['FTAjhDocNo']);
            $this->db->set('FTBchCode', $paDataWhere['FTBchCode']);
            $this->db->where('FTXthDocNo', '');
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
            $this->db->update('TCNTDocDTTmp');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Function Clear PDT IntoTmp 
    //Parameters : -
    //Creator : 22/06/2019 Bell
    //Last Modified : -
    //Return : Status update
    //Return Type : -
    public function FSxMClearPdtInTmp(){
        $tSQL = "DELETE FROM TCNTDocDTTmp WHERE FTSessionID = '" . $this->session->userdata('tSesSessionID') . "' AND FTXthDocKey = 'TCNTPdtAdjStkHD'";
        $this->db->query();
    }

    //Functionality : Function Insert Temp Into DT data 
    //Parameters : function parameters
    //Creator : 22/06/2019 Bell
    //Last Modified : -
    //Return : Status Add
    //Return Type : array
    public function FSaMASTInsertTmpToDT($paDataWhere){
        // ตัวแปร
        $tAjhDocNo      = $paDataWhere['FTAjhDocNo'];
        $tXthDocKey     = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');
        // ทำการลบ ใน DT ก่อนการย้าย Tmp ไป DT
        if ($paDataWhere['FTAjhDocNo'] != '') {
            $this->db->where_in('FTAjhDocNo', $paDataWhere['FTAjhDocNo']);
            $this->db->delete('TCNTPdtAdjStkDT');
        }
        $tSQL = "   INSERT TCNTPdtAdjStkDT(
                        FTBchCode, 
                        FTAjhDocNo, 
                        FNAjdSeqNo, 
                        FTPdtCode, 
                        FTPdtName, 
                        FTPunName, 
                        FTAjdBarcode, 
                        FTPunCode,
                        FCPdtUnitFact, 
                        FTAjdPlcCode,
                        FCAjdUnitQtyC1,
                        FCAjdQtyAllC1,
                        FNAjdLayRow,
                        FNAjdLayCol,
                        FDLastUpdOn, 
                        FTLastUpdBy, 
                        FDCreateOn, 
                        FTCreateBy,
                        FCAjdWahB4Adj
                    )
                ";
        $tSQL .= "  SELECT 
                        DOCTMP.FTBchCode,
                        DOCTMP.FTXthDocNo AS FTAjhDocNo,
                        ROW_NUMBER() OVER(ORDER BY DOCTMP.FTPdtCode ASC , DOCTMP.FCPdtUnitFact DESC) AS FNAjdSeqNo,
                        DOCTMP.FTPdtCode,
                        DOCTMP.FTXtdPdtName,
                        DOCTMP.FTPunName,
                        DOCTMP.FTXtdBarCode,
                        DOCTMP.FTPunCode,
                        DOCTMP.FCPdtUnitFact,
                        DOCTMP.FTAjdPlcCode,
                        DOCTMP.FCAjdUnitQtyC1,
                        DOCTMP.FCAjdQtyAllC1,
                        0,
                        0,
                        DOCTMP.FDLastUpdOn,
                        DOCTMP.FTLastUpdBy,
                        DOCTMP.FDCreateOn,
                        DOCTMP.FTCreateBy,
                        FCAjdWahB4Adj
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1=1
                ";

        $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";
        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";
        $tSQL .= " ORDER BY DOCTMP.FNXtdSeqNo ASC";

        $this->db->query($tSQL);
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode'  => '1',
                'rtDesc'  => 'Add Success',
            );
        } else {
            $aStatus = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Error Cannot Add',
            );
        }
        return $aStatus;
    }

    //Functionality : Function Update InlineDT Temp 
    //Parameters : function parameters
    //Creator : 23/06/2019 Bell
    //Last Modified : -
    //Return : Status Update inline
    //Return Type : array
    public function FSnMASTUpdateInlineDTTemp($paDataUpdateDT, $paDataWhere)
    {
        try {

            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->where('FTXthDocNo', $paDataWhere['FTAjhDocNo']);
            $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
            $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);

            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Upate',
                );
            }

            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Function Summary DT Temp 
    //Parameters : function parameters
    //Creator : 23/06/2019 Bell
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMASTSumDTTemp($paDataWhere)
    {

        $tAjhDocNo      = $paDataWhere['FTXthDocNo'];
        $tXthDocKey     = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "SELECT SUM(FCXtdAmt) AS FCXtdAmt
                 FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                 WHERE 1 = 1
            ";

        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";

        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

        $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oResult = $oQuery->result_array();
        } else {
            $oResult = '';
        }

        return $oResult;
    }

    //Functionality : Delete AdjustStock
    //Parameters : function parameters
    //Creator : 04/04/2019 Witsarut(bell)
    //Last Modified : -
    //Return : Array Status Delete
    //Return Type : array
    public  function FSnMASTDelDTTmp($paData){
        try {
            $this->db->trans_begin();
            $this->db->where_in('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where_in('FTPdtCode',  $paData['FTPdtCode']);
            $this->db->where_in('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus    = array(
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

    //Functionality : Multi Pdt Del Temp
    //Parameters : function parameters
    //Creator : 25/03/2019 Krit(Copter)
    //Return : Status Delete
    //Return Type : array
    public function FSaMASTPdtTmpMultiDel($paData){
        try {
            $this->db->trans_begin();
            //Del DTTmp
            $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
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

    //Functionality : Update Doc DT Temp
    //Parameters : function parameters
    //Creator : 26/06/2019 Witsarut(Bell)
    //Last Update : 30/07/2020 Napat(Jame)
    //Return : Status Delete
    //Return Type : array
    public function FSaMUpdateDocDTInLine($paDataUpdInline, $paDataWhere){
        $this->db->set('FCAjdUnitQtyC1', $paDataUpdInline['tValue'], FALSE);
        $this->db->set('FCAjdQtyAllC1', 'FCPdtUnitFact * ' . $paDataUpdInline['tValue'], FALSE);
        $this->db->where('FTXthDocNo', $paDataWhere['FTXthDocNo']);
        $this->db->where('FTXthDocKey', $paDataWhere['FTXthDocKey']);
        $this->db->where('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocDTTmp');
        if ($this->db->trans_status() === FALSE) {
            $aDataReturn = array(
                'nStaQuery' => 905,
                'tStaMeg'   => $this->db->error(),
            );
        } else {
            $aDataReturn = array(
                'nStaQuery' => 1,
                'tStaMeg'   => 'Update Success.',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 12/06/2018 Witsarut
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMASTAddUpdateHD($paData){
        try {
            // Update Master
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FTAjhBchTo', $paData['FTAjhBchTo']);
            $this->db->set('FTAjhRmk', $paData['FTAjhRmk']);
            $this->db->set('FTAjhMerchantTo', $paData['FTAjhMerchantTo']);
            $this->db->set('FTAjhShopTo', $paData['FTAjhShopTo']);
            $this->db->set('FTAjhPosTo', $paData['FTAjhPosTo']);
            $this->db->set('FTAjhWhTo', $paData['FTAjhWhTo']);
            $this->db->set('FTRsnCode', $paData['FTRsnCode']);
            $this->db->set('FDAjhDocDate', $paData['FDAjhDocDate']);
            $this->db->set('FNAjhStaDocAct', $paData['FNAjhStaDocAct']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);
            $this->db->set('FTAjhCountType', $paData['FTAjhCountType']);
            $this->db->set('FDAjhDateFrm', $paData['FDAjhDateFrm']);
            $this->db->set('FDAjhDateTo', $paData['FDAjhDateTo']);
            $this->db->where('FTAjhDocNo', $paData['FTAjhDocNo']);
            $this->db->update('TCNTPdtAdjStkHD');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'update Master Success',
                );
            } else {
                // Add Master
                $this->db->insert('TCNTPdtAdjStkHD', array(
                    'FTBchCode'         => $paData['FTBchCode'],
                    'FTAjhDocNo'        => $paData['FTAjhDocNo'],
                    'FNAjhDocType'      => $paData['FNAjhDocType'],
                    'FTAjhDocType'      => $paData['FTAjhDocType'],
                    'FDAjhDocDate'      => $paData['FDAjhDocDate'],
                    'FTAjhBchTo'        => $paData['FTAjhBchTo'],
                    'FTAjhMerchantTo'   => $paData['FTAjhMerchantTo'],
                    'FTAjhShopTo'       => $paData['FTAjhShopTo'],
                    'FTAjhPosTo'        => $paData['FTAjhPosTo'],
                    'FTAjhWhTo'         => $paData['FTAjhWhTo'],
                    'FTAjhPlcCode'      => $paData['FTAjhPlcCode'],
                    'FTDptCode'         => $paData['FTDptCode'],
                    'FTUsrCode'         => $paData['FTUsrCode'],
                    'FTAjhStaDoc'       => $paData['FTAjhStaDoc'],
                    'FTRsnCode'         => $paData['FTRsnCode'],
                    'FTAjhRmk'          => $paData['FTAjhRmk'],
                    'FTAjhApvSeqChk'    => $paData['FTAjhApvSeqChk'],
                    'FNAjhStaDocAct'    => $paData['FNAjhStaDocAct'],
                    'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                    'FDCreateOn'        => $paData['FDCreateOn'],
                    'FTCreateBy'        => $paData['FTCreateBy'],
                    'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                    'FTAjhCountType'    => $paData['FTAjhCountType'],
                    'FDAjhDateFrm'      => $paData['FDAjhDateFrm'],
                    'FDAjhDateTo'       => $paData['FDAjhDateTo']
                ));
                if ($this->db->affected_rows() > 0) {
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                } else {
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Search AdjStkSub By ID
    //Parameters : function parameters
    //Creator : 27/06/2019 Witsarut(Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMASTGetHD($paData){
        $tAjhDocNo  = $paData['FTAjhDocNo'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " 
            SELECT
                ADJSTK.FTBchCode    AS FTBchCodeLogin,
                BCHLDOC.FTBchName   AS FTBchNameLogin,
                ADJSTK.FTAjhDocNo,
                ADJSTK.FNAjhDocType,
                ADJSTK.FDAjhDocDate,
                CONVERT(CHAR(5),ADJSTK.FDAjhDocDate,108) AS FDAjhDocTime,
                ADJSTK.FTAjhBchTo       AS FTAjhBchCodeFilter,
                BCHLTO.FTBchName        AS FTAjhBchNameFilter,
                ADJSTK.FTAjhMerchantTo  AS FTAjhMerCodeFilter,
                MCHLTO.FTMerName        AS FTAjhMerNameFilter,
                ADJSTK.FTAjhShopTo      AS FTAjhShopCodeFilter,
                SHPLTO.FTShpName        AS FTAjhShopNameFilter,
                ADJSTK.FTAjhPosTo       AS FTAjhPosCodeFilter,
                POSL.FTPosName          AS FTAjhPosNameFilter,
                ADJSTK.FTAjhWhTo        AS FTAjhWahCodeFilter,
                WAHLTO.FTWahName        AS FTAjhWahNameFilter,
                ADJSTK.FTAjhPlcCode     AS FTAjhPlcCode,
                PLCL.FTPlcName          AS FTAjhPlcName,
                ADJSTK.FTDptCode        AS FTAjhDptCode,
                DPTL.FTDptName          AS FTAjhDptName,
                ADJSTK.FTUsrCode        AS FTAjhUsrCode,
                USRLKEY.FTUsrName       AS FTAjhUsrName,
                USRHQ.FTUsrName         AS FTAjhUsrHQName,
                ADJSTK.FTRsnCode        AS FTAjhRsnCode,
                RSNL.FTRsnName          AS FTAjhRsnName,
                ADJSTK.FTAjhRmk         AS FTAjhRmk,
                ADJSTK.FNAjhDocPrint,
                ADJSTK.FTAjhApvSeqChk,
                ADJSTK.FTAjhStaApv,
                ADJSTK.FTAjhStaPrcStk,
                ADJSTK.FTAjhStaDoc,
                ADJSTK.FNAjhStaDocAct,
                ADJSTK.FTAjhDocRef,
                ADJSTK.FTAjhCountType,
                ADJSTK.FDAjhDateFrm,
                ADJSTK.FDAjhDateTo,
                ADJSTK.FTAjhStaPrcStk,
                ADJSTK.FTAjhStkApvCode,
                AGN.FTAgnCode       AS rtAgnCode,
                AGN.FTAgnName       AS rtAgnName,
                SHP.FTShpType,
                ADJSTK.FTCreateBy       AS FTAjhUsrCodeCreateBy,
                USRLCREATE.FTUsrName    AS FTAjhUsrNameCreateBy,
                ADJSTK.FTAjhApvCode     AS FTAjhUsrCodeAppove,
                USRAPV.FTUsrName        AS FTAjsUsrNameAppove
            FROM [TCNTPdtAdjStkHD]      ADJSTK      WITH (NOLOCK)
            LEFT JOIN TCNMBranch_L      BCHLDOC     WITH (NOLOCK) ON ADJSTK.FTBchCode = BCHLDOC.FTBchCode AND BCHLDOC.FNLngID = $nLngID
            LEFT JOIN TCNMBranch_L      BCHLTO      WITH (NOLOCK) ON ADJSTK.FTAjhBchTo = BCHLTO.FTBchCode AND BCHLTO.FNLngID = $nLngID
            LEFT JOIN TCNMBranch        BCH         ON ADJSTK.FTBchCode = BCH.FTBchCode
            LEFT JOIN TCNMAgency_L      AGN         WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = $nLngID
            LEFT JOIN TCNMMerchant_L    MCHLTO      WITH (NOLOCK) ON ADJSTK.FTAjhMerchantTo = MCHLTO.FTMerCode AND MCHLTO.FNLngID = $nLngID
            LEFT JOIN TCNMUser_L        USRLKEY     WITH (NOLOCK) ON ADJSTK.FTUsrCode = USRLKEY.FTUsrCode AND USRLKEY.FNLngID = $nLngID
            LEFT JOIN TCNMUser_L        USRLCREATE  WITH (NOLOCK) ON ADJSTK.FTCreateBy = USRLCREATE.FTUsrCode AND USRLCREATE.FNLngID = $nLngID
            LEFT JOIN TCNMUser_L        USRAPV      WITH (NOLOCK) ON ADJSTK.FTAjhApvCode = USRAPV.FTUsrCode AND USRAPV.FNLngID = $nLngID
            LEFT JOIN TCNMUser_L        USRHQ       WITH (NOLOCK) ON ADJSTK.FTAjhStkApvCode = USRHQ.FTUsrCode AND USRHQ.FNLngID = $nLngID
            LEFT JOIN TCNMUsrDepart_L   DPTL        WITH (NOLOCK) ON ADJSTK.FTDptCode = DPTL.FTDptCode AND DPTL.FNLngID = $nLngID
            LEFT JOIN TCNMShop          SHP         WITH (NOLOCK) ON SHP.FTShpCode =  ADJSTK.FTAjhShopTo AND SHP. FTBchCode = ADJSTK.FTBchCode
            LEFT JOIN TCNMShop_L        SHPLTO      WITH (NOLOCK) ON ADJSTK.FTAjhShopTo = SHPLTO.FTShpCode AND SHPLTO.FNLngID = $nLngID
            LEFT JOIN TCNMWaHouse_L     WAHLTO      WITH (NOLOCK) ON ADJSTK.FTAjhWhTo = WAHLTO.FTWahCode AND ADJSTK.FTBchCode = WAHLTO.FTBchCode AND WAHLTO.FNLngID = $nLngID
            /*LEFT JOIN TCNMPosLastNo     POSVDTO     WITH (NOLOCK) ON ADJSTK.FTAjhPosTo = POSVDTO.FTPosCode*/
            LEFT JOIN TCNMPos_L         POSL        WITH (NOLOCK) ON POSL.FTPosCode   = ADJSTK.FTAjhPosTo AND POSL.FTBchCode = ADJSTK.FTBchCode AND POSL.FNLngID = $nLngID
            LEFT JOIN TCNMRsn_L         RSNL        WITH (NOLOCK) ON ADJSTK.FTRsnCode = RSNL.FTRsnCode AND RSNL.FNLngID = $nLngID
            LEFT JOIN TCNMPdtLoc_L      PLCL        WITH (NOLOCK) ON ADJSTK.FTAjhPlcCode = PLCL.FTPlcCode AND PLCL.FNLngID = $nLngID
            WHERE 1=1 AND ADJSTK.FTAjhDocType = '3'
        ";
        if ($tAjhDocNo != "") {
            $tSQL .= "AND ADJSTK.FTAjhDocNo = '$tAjhDocNo'";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();

            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            // Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Data List Subdistrict
    //Parameters : function parameters
    //Creator :  27/06/2019 Witsarut
    //Last Modified : -
    //Return : Data Array
    //Return Type : Array
    public function FSaMASTGetDT($paData){
        $tXthDocNo  = $paData['FTAjhDocNo'];
        $aRowLen    = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $tSQL   = "SELECT c.* FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY FTAjhDocNo ASC) AS FNRowID,* FROM
                (SELECT DISTINCT
                        ADJSTK.FTBchCode,
                        ADJSTK.FTAjhDocNo,
                        ADJSTK.FNAjdSeqNo,
                        ADJSTK.FTPdtCode,
                        ADJSTK.FTPdtName,
                        ADJSTK.FTPunName,
                        ADJSTK.FTAjdBarcode,
                        ADJSTK. FTPunCode,
                        ADJSTK. FCPdtUnitFact,
                        ADJSTK. FTPgpChain,
                        ADJSTK. FTAjdPlcCode,
                        ADJSTK. FNAjdLayRow,
                        ADJSTK. FNAjdLayCol,
                        ADJSTK. FCAjdWahB4Adj,
                        ADJSTK. FCAjdSaleB4AdjC1,
                        ADJSTK. FDAjdDateTimeC1,
                        ADJSTK. FCAjdUnitQtyC1,
                        ADJSTK. FCAjdQtyAllC1,
                        ADJSTK. FCAjdSaleB4AdjC2,
                        ADJSTK. FDAjdDateTimeC2,
                        ADJSTK. FCAjdUnitQtyC2,
                        ADJSTK. FCAjdQtyAllC2,
                        ADJSTK. FCAjdUnitQty,
                        ADJSTK. FDAjdDateTime,
                        ADJSTK. FCAjdQtyAll,
                        ADJSTK.FCAjdQtyAllDiff,
                        ADJSTK. FDLastUpdOn,
                        ADJSTK. FTLastUpdBy,
                        ADJSTK.FDCreateOn,
                        ADJSTK.FTCreateBy

                FROM [TCNTPdtAdjStkDT] ADJSTK
                ";
        if (@$tXthDocNo != '') {
            $tSQL .= " WHERE (ADJSTK.FTAjhDocNo = '$tXthDocNo')";
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result();
            $aResult = array(
                'rtCode' => '1',
                'raItems'   => $oDetail,
            );
        } else {
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    // Last Update : Napat(Jame) 09/09/2020 เพิ่ม ISNULL ในกรณีที่ใน PdtStkBal ไม่มีข้อมูล
    public function FSaMUpdateDTBal($ptDocNo){
        $tSQL = "   UPDATE TCNTPdtAdjStkDT
                    SET TCNTPdtAdjStkDT.FCAjdWahB4Adj = ISNULL(C.FCStkQty,0)
                    FROM TCNTPdtAdjStkHD A WITH(NOLOCK)
                    INNER JOIN TCNTPdtAdjStkDT B ON A.FTAjhDocNo = B.FTAjhDocNo
                    LEFT JOIN TCNTPdtStkBal C ON A.FTAjhWhTo = C.FTWahCode AND A.FTBchCode = C.FTBchCode AND B.FTPdtCode = C.FTPdtCode
                    WHERE A.FTAjhDocNo = '$ptDocNo'
                ";
        $this->db->query($tSQL);
        // print_r($tSQL);
        // exit();
        if ($this->db->trans_status() === FALSE) {
            $aStatus = array(
                'tCode' => '905',
                'tDesc' => $this->db->error()
            );
        } else {
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Update Success.'
            );
        }
        return $aStatus;
    }

    // เปลี่ยนค่า ก่อนตรวจนับ 
    public function FSaMAdjStkEventChangeAdjFactorDT($paDataInsert){
        $tDocNo         = $paDataInsert['FTXthDocNo'];
        $tDocKey        = $paDataInsert['FTXthDocKey'];
        $tSession       = $paDataInsert['FTSessionID'];
        $tSQL           = "SELECT 
                            DOC.FTPdtCode,
                            DOC.FCPdtUnitFact,
                            DOC.FTXtdBarCode,
                            DOC.FCAjdWahB4Adj,
                            ODT.FCAjdWahB4Adj AS FCXtdQtyAll
                            FROM TCNTDocDTTmp DOC WITH(NOLOCK)
                                LEFT JOIN (SELECT TOP 1
                                                    FCAjdWahB4Adj,
                                                    FTAjhDocNo 
                                                FROM
                                                    TCNTPdtAdjStkDT 
                                                    WHERE
                                                        FTAjhDocNo = '$tDocNo'
                                                ORDER BY FCAjdWahB4Adj DESC) ODT ON ODT.FTAjhDocNo = DOC.FTXthDocNo
                            WHERE DOC.FTSessionID='$tSession' 
                            AND DOC.FTXthDocNo ='$tDocNo'
                            AND DOC.FTXthDocKey ='$tDocKey'
                            ORDER BY DOC.FTPdtCode ASC,DOC.FCPdtUnitFact DESC ";
        $oQuery         = $this->db->query($tSQL);
        $oDataQuery     = $oQuery->result_array();

        $tOldPdtCode    = '';
        $tOldBarCode    = '';
        $tOldSugges     = '';
        $remail         = '';
        foreach($oDataQuery as $nKey => $aValuet){
            if($aValuet['FTPdtCode'] != $tOldPdtCode){
                $tSuggerT = $aValuet['FCXtdQtyAll'] / $aValuet['FCPdtUnitFact'];
                if($aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'] != 0){
                    $remail = $aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'];
                }else{
                    $remail = 0;
                }
                $tOldSugges = floor($tSuggerT);
            }else{
                ///อัพเดทหน่วยสินค้าที่ใหญ่กว่า
                $this->db->set('FCAjdWahB4Adj' , $tOldSugges);
                $this->db->where('FTPdtCode', $aValuet['FTPdtCode']);
                $this->db->where('FTXtdBarCode', $tOldBarCode);
                $this->db->where('FTXthDocKey', $tDocKey);
                $this->db->where('FTSessionID', $tSession);
                $this->db->update('TCNTDocDTTmp');

                $tSuggerT = $remail / $aValuet['FCPdtUnitFact'];
                if($remail % $aValuet['FCPdtUnitFact'] != 0 && $remail != 0){
                    $remail = $aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'];
                }else{
                    $remail = 0;
                }
                if($tSuggerT > 1){
                    $tOldSugges = floor($tSuggerT);
                }else{
                    $tOldSugges = $tSuggerT;
                }
            }

            // $this->db->set('FCAjdWahB4Adj' , $tSuggerT);
            // $this->db->where('FTPdtCode', $aValuet['FTPdtCode']);
            // $this->db->where('FTXtdBarCode', $aValuet['FTXtdBarCode']);
            // $this->db->where('FTXthDocKey', $tDocKey);
            // $this->db->where('FTSessionID', $tSession);
            // $this->db->update('TCNTDocDTTmp');

            $tOldPdtCode = $aValuet['FTPdtCode'];
            $tOldBarCode = $aValuet['FTXtdBarCode'];
        }
    }

    // Last Update : Napat(Jame) 09/09/2020 เพิ่ม ISNULL ในกรณีที่ใน PdtStkBal ไม่มีข้อมูล lnwza
    public function FSaMUpdateDTBalTmp($ptDocNo)
    {
        $tSQL = "   UPDATE TCNTPdtAdjStkDT
                    SET TCNTPdtAdjStkDT.FCAjdWahB4Adj = ISNULL(C.FCStkQty,0)
                    FROM TCNTPdtAdjStkHD A WITH(NOLOCK)
                    INNER JOIN TCNTPdtAdjStkDT B ON A.FTAjhDocNo = B.FTAjhDocNo
                    LEFT JOIN TCNTPdtStkBal C ON A.FTAjhWhTo = C.FTWahCode AND A.FTBchCode = C.FTBchCode AND B.FTPdtCode = C.FTPdtCode
                    WHERE A.FTAjhDocNo = '$ptDocNo'
                ";
        $this->db->query($tSQL);
        if ($this->db->trans_status() === FALSE) {
            $aStatus = array(
                'tCode' => '905',
                'tDesc' => $this->db->error()
            );
        } else {
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Update Success.'
            );
        }
        return $aStatus;
    }

    //Functionality : Function Add DT To Temp
    //Parameters : function parameters
    //Creator : 7/06/2019 Witsarut(Bell)
    //Last Modified : -
    //Return : Status Add
    //Return Type : array
    public function FSaMASTInsertDTToTemp($paDataWhere)
    {

        $tDocNo         = $paDataWhere['FTAjhDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        $tSessionID     = $this->session->userdata('tSesSessionID');

        //ลบ ใน Temp
        $this->db->where_in('FTXthDocNo', $tDocNo);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL = "   INSERT INTO TCNTDocDTTmp (
                         FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode
                        ,FTXtdPdtName,FTPunCode,FTPunName,FTXtdBarCode
                        ,FCPdtUnitFact,FTPgpChain,FTAjdPlcCode
                        ,FCAjdUnitQtyC1,FCAjdQtyAllC1,FCAjdQtyAllDiff,FCAjdWahB4Adj
                        ,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FCXtdQtyAll
                    )
                    SELECT 
                         DT.FTBchCode
                        ,DT.FTAjhDocNo
                        ,DT.FNAjdSeqNo
                        ,'$tDocKey'         AS FTXthDocKey
                        ,DT.FTPdtCode
                        ,DT.FTPdtName
                        ,DT.FTPunCode
                        ,DT.FTPunName
                        ,DT.FTAjdBarcode
                        ,DT.FCPdtUnitFact
                        ,DT.FTPgpChain
                        ,DT.FTAjdPlcCode
                        ,DT.FCAjdUnitQtyC1
                        ,DT.FCAjdQtyAllC1
                        ,DT.FCAjdQtyAllDiff
                        ,DT.FCAjdWahB4Adj
                        ,'$tSessionID'		AS FTSessionID
                        ,DT.FDLastUpdOn
                        ,DT.FDCreateOn
                        ,DT.FTLastUpdBy
                        ,DT.FTCreateBy
                        ,ISNULL(STKBAL.FCStkQty,0)
                    FROM TCNTPdtAdjStkDT DT WITH(NOLOCK)
                    INNER JOIN TCNTPdtAdjStkHD HD ON DT.FTAjhDocNo = HD.FTAjhDocNo
                    LEFT JOIN TCNTPdtStkBal STKBAL ON DT.FTPdtCode = STKBAL.FTPdtCode AND STKBAL.FTWahCode = HD.FTAjhWhTo AND STKBAL.FTBchCode = DT.FTBchCode
                    WHERE 1=1
                    AND DT.FTAjhDocNo = '$tDocNo'
        ";

        // echo $tSQL;
        $this->db->query($tSQL);
        if ($this->db->trans_status() === FALSE) {
            $aStatus = array(
                'tCode' => '905',
                'tDesc' => $this->db->error()
            );
        } else {
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Insert Success.'
            );
        }
        return $aStatus;

        // if($paData['rtCode'] == 1){
        //     $paData = $paData['raItems'];

        //     //ลบ ใน Temp
        //     if($paData[0]['FTAjhDocNo'] != ''){
        //         $this->db->where_in('FTXthDocNo', $paData[0]['FTAjhDocNo']);
        //         $this->db->where_in('FTSessionID', $this->session->userdata('tSesSessionID'));
        //         $this->db->delete('TCNTDocDTTmp');
        //     }

        //     foreach($paData as $key=>$val){

        //         $this->db->insert('TCNTDocDTTmp',array(

        //             'FTBchCode'         => $val['FTBchCode'],
        //             'FTXthDocNo'        => $val['FTAjhDocNo'], 
        //             'FNXtdSeqNo'        => $val['FNAjdSeqNo'],
        //             'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
        //             'FTPdtCode'         => $val['FTPdtCode'],
        //             'FTXtdPdtName'      => $val['FTPdtName'],
        //             'FTPunCode'         => $val['FTPunCode'],
        //             'FTPunName'         => $val['FTPunName'],
        //             'FTXtdBarCode'      => $val['FTAjdBarcode'],
        //             'FCXtdFactor'       => $val['FCPdtUnitFact'],
        //             'FTPgpChain'        => $val['FTPgpChain'],
        //             'FNAjdLayRow'       => $val['FNAjdLayRow'],
        //             'FNAjdLayCol'       => $val['FNAjdLayCol'],
        //             'FCAjdWahB4Adj'     => $val['FCAjdWahB4Adj'],
        //             'FCAjdSaleB4AdjC1'  => $val['FCAjdSaleB4AdjC1'],
        //             'FDAjdDateTimeC1'   => $val['FDAjdDateTimeC1'],
        //             'FCAjdUnitQtyC1'    => $val['FCAjdUnitQtyC1'],
        //             'FCAjdQtyAllC1'     => $val['FCAjdQtyAllC1'],
        //             'FCAjdSaleB4AdjC2'  => $val['FCAjdSaleB4AdjC2'],
        //             'FDAjdDateTimeC2'   => $val['FDAjdDateTimeC2'],
        //             'FCAjdUnitQtyC2'    => $val['FCAjdUnitQtyC2'],
        //             'FCAjdQtyAllC2'     => $val['FCAjdQtyAllC2'],
        //             'FCAjdUnitQty'      => $val['FCAjdUnitQty'],
        //             'FCAjdUnitQty'      => $val['FCAjdUnitQty'],
        //             'FDAjdDateTime'     => $val['FDAjdDateTime'],
        //             'FCAjdQtyAll'       => $val['FCAjdQtyAll'],
        //             'FCAjdQtyAllDiff'   => $val['FCAjdQtyAllDiff'],
        //             'FTAjdPlcCode'      => $val['FTAjdPlcCode'],

        //             'FTSessionID'       => $this->session->userdata('tSesSessionID'),
        //             'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
        //             'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
        //             'FDCreateOn'        => date('Y-m-d h:i:sa'),
        //             'FTCreateBy'        => $this->session->userdata('tSesUsername')
        //         ));

        //         if($this->db->affected_rows() > 0){
        //             $aStatus = array(
        //                 'rtCode' => '1',
        //                 'rtDesc' => 'Add Success.',
        //             );
        //         }else{
        //             $aStatus = array(
        //                 'rtCode' => '905',
        //                 'rtDesc' => 'Error Cannot Add.',
        //             );
        //         }
        //     }
        // }
    }

    //Functionality : Function Cancel Doc
    //Parameters : function parameters
    //Creator : 29/06/2019 Witsarut(Bell)
    //Last Modified : -
    //Return : Status Cancel
    //Return Type : array
    public function FSVMASTCancel($paDataUpdate)
    {
        try {
            $this->db->set('FTAjhStaDoc', 3);
            $this->db->set('FTRsnCode', $paDataUpdate['FTRsnCode']);
            $this->db->where('FTAjhDocNo', $paDataUpdate['FTAjhDocNo']);
            $this->db->update('TCNTPdtAdjStkHD');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }

            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Function Approve Doc
    //Parameters : function parameters
    //Creator : 29/06/2019 Witsarut(Bell)
    //Last Modified : 30/07/2019 Wasin(Yoshi)
    //Return : Status Approve
    //Return Type : array
    public function FSvMASTApprove($paDataUpdate)
    {
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
            $this->db->set('FDLastUpdOn', $dLastUpdOn);
            $this->db->set('FTLastUpdBy', $tLastUpdBy);
            $this->db->set('FTAjhStaApv', 1);
            $this->db->set('FTAjhApvCode', $paDataUpdate['FTAjhApvCode']);
            $this->db->where('FTAjhDocNo', $paDataUpdate['FTAjhDocNo']);

            $this->db->update('TCNTPdtAdjStkHD');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode'  => '1',
                    'rtDesc'  => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode'  => '903',
                    'rtDesc'  => 'Not Approve',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Functionality : Function Approve Doc
    //Parameters : function parameters
    //Creator : 29/06/2019 Witsarut(Bell)
    //Last Modified : 30/07/2019 Wasin(Yoshi)
    //Return : Status Approve
    //Return Type : array
    public function FSvMASTHQApprove($paDataUpdate)
    {
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
            $this->db->set('FDLastUpdOn', $dLastUpdOn);
            $this->db->set('FTLastUpdBy', $tLastUpdBy);
            
            $this->db->set('FTAjhStkApvCode', $paDataUpdate['FTAjhApvCode']);
            $this->db->where('FTAjhDocNo', $paDataUpdate['FTAjhDocNo']);

            $this->db->update('TCNTPdtAdjStkHD');
            if ($this->db->affected_rows() > 0) {
                $aStatus = array(
                    'rtCode'  => '1',
                    'rtDesc'  => 'OK',
                );
            } else {
                $aStatus = array(
                    'rtCode'  => '903',
                    'rtDesc'  => 'Not Approve',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    // Create By Napat(Jame) 2020/07/29
    // Parameter : SessionLogin, DocKey, DocNo , 
    //             TypeDelete 1 = clear temp , 2 delete by id
    public function FSxMAdjStkClearDTTmp($paDataWhere)
    {

        $this->db->where_in('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where_in('FTXthDocKey', $paDataWhere['FTXthDocKey']);

        // กรณีเคลียร์ temp ให้ลบทุกเลขที่เอกสาร
        // กรณีลบบางรายการให้ where ด้วยเลขที่เอกสาร และ Seq
        if ($paDataWhere['tDeleteType'] != '1') {
            $this->db->where_in('FTXthDocNo', $paDataWhere['FTAjhDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paDataWhere['FNXtdSeqNo']);
        }

        $this->db->delete('TCNTDocDTTmp');
    }

    // Create By : Napat(Jame) 2020/07/29
    public function FSaMAdjStkEventAddProducts($paDataCondition, $paDataInsert)
    {

        // Get Parameters
        $tBchCode   = $paDataInsert['FTBchCode'];
        $tWahCode   = $paDataInsert['FTWahCode'];
        $tDocNo     = $paDataInsert['FTXthDocNo'];
        $tDocKey    = $paDataInsert['FTXthDocKey'];
        $tSession   = $paDataInsert['FTSessionID'];
        $tUser      = $paDataInsert['tUser'];
        $tSesUsrAgnCode     = $this->session->userdata('tSesUsrAgnCode');
        $nLangEdit  = $paDataInsert['nLangEdit'];
        $tWhereDel  = "";
        // $tPdtLoc    = $paDataInsert['FTAjdPlcCode'];

        if (isset($paDataCondition['oetASTFilterPdtCodeFrom']) && $paDataCondition['oetASTFilterPdtCodeFrom'] != "" && isset($paDataCondition['oetASTFilterPdtCodeTo']) && $paDataCondition['oetASTFilterPdtCodeTo'] != "") {
            $tWhereDel  = "AND FTPdtCode BETWEEN '" . $paDataCondition['oetASTFilterPdtCodeFrom'] . "' AND '" . $paDataCondition['oetASTFilterPdtCodeTo'] . "' ";
        }

        $tSQLDel   = "     DELETE FROM TCNTDocDTTmp 
                WHERE   FTBchCode   = '$tBchCode'
                    AND FTXthDocNo  = '$tDocNo'
                    AND FTXthDocKey = '$tDocKey'
                    AND FTSessionID = '$tSession'
                    $tWhereDel
            ";
        $oQueryDel = $this->db->query($tSQLDel);

        // Get Last Seq
        $tSQL   = "     SELECT TOP 1
                            COUNT(FNXtdSeqNo) AS FNXtdLastSeq 
                        FROM TCNTDocDTTmp WITH(NOLOCK)
                        WHERE   FTBchCode   = '$tBchCode'
                            AND FTXthDocNo  = '$tDocNo'
                            AND FTXthDocKey = '$tDocKey'
                            AND FTSessionID = '$tSession'
                  ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $nLastSeq = $oQuery->result_array()[0]['FNXtdLastSeq'];
        } else {
            $nLastSeq = 0;
        }

        // Setings
        $tCondition         = "";
        $tQueryJoin         = "";

        if(!empty($tSesUsrAgnCode)){
            $tCondition      .= " AND (ISNULL(SpcBch.FTAgnCode, '') = '$tSesUsrAgnCode' OR ISNULL(SpcBch.FTAgnCode,'')='')";
            }
        // Condition Product
        if (isset($paDataCondition['oetASTFilterPdtCodeFrom']) && $paDataCondition['oetASTFilterPdtCodeFrom'] != "" && isset($paDataCondition['oetASTFilterPdtCodeTo']) && $paDataCondition['oetASTFilterPdtCodeTo'] != "") {
            $tCondition .= " AND PDT.FTPdtCode BETWEEN '" . $paDataCondition['oetASTFilterPdtCodeFrom'] . "' AND '" . $paDataCondition['oetASTFilterPdtCodeTo'] . "' ";
        }

        // Condition Spuplier
        if (isset($paDataCondition['oetASTFilterSplCodeFrom']) && $paDataCondition['oetASTFilterSplCodeFrom'] != "" && isset($paDataCondition['oetASTFilterSplCodeTo']) && $paDataCondition['oetASTFilterSplCodeTo'] != "") {
            $tQueryJoin  .= " INNER JOIN TCNMPdtSpl PDLSPL WITH(NOLOCK) ON PDLSPL.FTPdtCode = PDT.FTPdtCode ";
            $tCondition .= " AND PDLSPL.FTSplCode BETWEEN '" . $paDataCondition['oetASTFilterSplCodeFrom'] . "' AND '" . $paDataCondition['oetASTFilterSplCodeTo'] . "' ";
        }

        // Condition Product Group
        if (isset($paDataCondition['oetASTFilterPgpCode']) && !empty($paDataCondition['oetASTFilterPgpCode'])) {
            $tQueryJoin  .= " INNER JOIN TCNMPdtGrp GRP WITH(NOLOCK) ON GRP.FTPgpChain = PDT.FTPgpChain ";
            $tCondition .= " AND GRP.FTPgpChain = '" . $paDataCondition['oetASTFilterPgpCode'] . "' ";
        }

        // Condition Product Location
        if (isset($paDataCondition['oetASTFilterPlcCode']) && !empty($paDataCondition['oetASTFilterPlcCode'])) {
            $tQueryJoin  .= " INNER JOIN TCNMPdtBar BAR WITH(NOLOCK) ON BAR.FTPdtCode = PDT.FTPdtCode ";
            $tCondition .= " AND BAR.FTPlcCode = '" . $paDataCondition['oetASTFilterPlcCode'] . "' ";

            if (isset($paDataCondition['ocbASTPdtLocChkSeq']) && !empty($paDataCondition['ocbASTPdtLocChkSeq'])) {
                $tQueryJoin  .= " INNER JOIN TCNTPdtLocSeq LOC WITH(NOLOCK) ON BAR.FTPlcCode = LOC.FTPlcCode ";
            }

            // $tPdtLoc = $paDataCondition['oetASTFilterPlcCode'];
        }

        // Condition Product Stock Card
        if (isset($paDataCondition['ocbASTUsePdtStkCard']) && !empty($paDataCondition['ocbASTUsePdtStkCard'])) {
            if (isset($paDataCondition['orbASTPdtStkCard']) && !empty($paDataCondition['orbASTPdtStkCard'])) {
                if ($paDataCondition['orbASTPdtStkCard'] == '1') {
                    $tQueryJoin .= " LEFT JOIN TCNTPdtStkCrd PSK WITH(NOLOCK) ON PDT.FTPdtCode = PSK.FTPdtCode AND PSK.FTBchCode = '$tBchCode' ";
                    $tCondition .= " AND PSK.FDStkDate IS NULL ";
                } else {
                    if (isset($paDataCondition['onbASTPdtStkCardBack']) && !empty($paDataCondition['onbASTPdtStkCardBack'])) {
                        $nStkBack = intval($paDataCondition['onbASTPdtStkCardBack']);
                        $tQueryJoin .= " INNER JOIN (
                                            SELECT 
                                                FTBchCode,
                                                FTPdtCode 
                                            FROM TCNTPdtStkCrd WITH(NOLOCK)
                                            WHERE CONVERT(VARCHAR(10),FDStkDate,121) BETWEEN CONVERT(VARCHAR(10),DATEADD(MONTH, -$nStkBack, GETDATE()),121) AND CONVERT(VARCHAR(10),GETDATE(),121) 
                                            GROUP BY FTBchCode,FTPdtCode
                                         ) PSK ON PSK.FTPdtCode = PDT.FTPdtCode AND PSK.FTBchCode = '$tBchCode' 
                                       ";
                    }
                }
            }
        }

        // Condition Have Stock
        if (isset($paDataCondition['orbASTPdtStkCondition']) && !empty($paDataCondition['orbASTPdtStkCondition']) && $paDataCondition['orbASTPdtStkCondition'] != '1') {
            // $tQueryJoin  .= " INNER JOIN TCNTPdtStkBal BAL WITH(NOLOCK) ON BAL.FTPdtCode = PDT.FTPdtCode ";
            if($paDataCondition['orbASTPdtStkCondition'] == '2'){
                $tCondition .= " AND STKBAL.FCStkQty > 0 ";
            }elseif($paDataCondition['orbASTPdtStkCondition'] == '3'){
                $tCondition .= " AND STKBAL.FCStkQty <= 0 ";
            }
        }

        // Insert Production
        $tSQL = "   INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,
                        FTXtdPdtName,FTPunCode,FTPunName,FTXtdBarCode,FCPdtUnitFact,
                        FTAjdPlcCode,FCAjdUnitQtyC1,FCAjdQtyAllC1,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FCAjdWahB4Adj,FCXtdQtyAll
                    )
                ";
        $tSQL .= "  SELECT 
                        '$tBchCode'		    AS FTBchCode,
                        '$tDocNo'			AS FTXthDocNo,
                        ROW_NUMBER() OVER(ORDER BY PDT.FTPdtCode ASC , PPS.FCPdtUnitFact DESC) + $nLastSeq AS FNRowID,
                        '$tDocKey'	        AS FTXthDocKey,
                        PDT.FTPdtCode,
                        PDT_L.FTPdtName,
                        PPS.FTPunCode,
                        PUN_L.FTPunName,
                        PBAR.FTBarCode,
                        PPS.FCPdtUnitFact,
                        PBAR.FTPlcCode      AS FTAjdPlcCode,
                        0                   AS FCAjdUnitQtyC1,
                        0                   AS FCAjdQtyAllC1,
                        '$tSession'			AS FTSessionID,
                        GETDATE()			AS FDLastUpdOn,
                        GETDATE()			AS FDCreateOn,
                        '$tUser'			AS FTLastUpdBy,
                        '$tUser'			AS FTCreateBy,
                        ISNULL(STKBAL.FCStkQty,0),
                        ISNULL(STKBAL.FCStkQty,0)
                    FROM TCNMPdt PDT WITH(NOLOCK)
                    LEFT JOIN TCNMPdt_L PDT_L WITH(NOLOCK) ON PDT.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = $nLangEdit
                    LEFT JOIN TCNMPdtPackSize PPS WITH(NOLOCK) ON PDT.FTPdtCode = PPS.FTPdtCode
                    LEFT JOIN TCNMPdtUnit_L PUN_L WITH(NOLOCK) ON PPS.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $nLangEdit
                    LEFT JOIN TCNMPdtBar PBAR WITH(NOLOCK) ON PDT.FTPdtCode = PBAR.FTPdtCode AND PPS.FTPunCode = PBAR.FTPunCode AND PBAR.FTBarStaUse = $nLangEdit
                    LEFT JOIN TCNMPdtSpcBch SpcBch ON PDT.FTPdtCode = SpcBch.FTPdtCode
                    LEFT JOIN TCNTPdtStkBal STKBAL ON PDT.FTPdtCode = STKBAL.FTPdtCode AND STKBAL.FTWahCode = '" . $tWahCode . "' AND STKBAL.FTBchCode = '$tBchCode'
                    $tQueryJoin
                    WHERE PDT.FTPdtCode != ''  
                      AND PDT.FTPdtStaActive = '1'  
                      AND (  ( PDT.FTPdtType = '1' AND PDT.FTPdtSetOrSN = '1' )  OR ( PDT.FTPdtType = '3' AND PDT.FTPdtSetOrSN = '1' )  OR ( PDT.FTPdtType = '4' AND PDT.FTPdtSetOrSN = '1' )  OR ( PDT.FTPdtType = '5' AND PDT.FTPdtSetOrSN = '1' )  OR ( PDT.FTPdtType = '6' AND PDT.FTPdtSetOrSN = '1' )  OR ( PDT.FTPdtType = '1' AND PDT.FTPdtSetOrSN = '2' )  OR ( PDT.FTPdtType = '1' AND PDT.FTPdtSetOrSN = '3' )  OR ( PDT.FTPdtType = '1' AND PDT.FTPdtSetOrSN = '4' )  )
                    $tCondition
                 ";

        $this->db->query($tSQL);
        // echo $tSQL;
        if ($this->db->trans_status() === FALSE) {
            $aReturn = array(
                'tSQL'      => $tSQL,
                'tCode'     => '99',
                'tDesc'     => $this->db->error()
            );
        } else {
            if ($this->db->affected_rows() > 0) {
                $aReturn = array(
                    'tSQL'      => $tSQL,
                    'tCode'     => '1',
                    'tDesc'     => 'Success'
                );
            } else {
                $aReturn = array(
                    'tSQL'      => $tSQL,
                    'tCode'     => '905',
                    'tDesc'     => 'Not Found Data'
                );
            }
        }
        return $aReturn;
    }

    // Create By : Sittikorn(Off) 2021/12/09
    public function FSaMAdjStkEventChangeAdjFactor($paDataInsert)
    {

        // Get Parameters
        $tDocNo     = $paDataInsert['FTXthDocNo'];
        $tDocKey    = $paDataInsert['FTXthDocKey'];
        $tSession   = $paDataInsert['FTSessionID'];

        $bFlag = '1';
        $remail = '';
        $tSQL = "SELECT 
        DOC.FTPdtCode,
        DOC.FCPdtUnitFact,
        DOC.FTXtdBarCode,
        DOC.FCAjdWahB4Adj,
        DOC.FCXtdQtyAll
            FROM TCNTDocDTTmp DOC WITH(NOLOCK)
        WHERE DOC.FTSessionID='$tSession' 
        AND DOC.FTXthDocNo ='$tDocNo'
        AND DOC.FTXthDocKey ='$tDocKey'
        ORDER BY DOC.FTPdtCode ASC,DOC.FCPdtUnitFact DESC ";

        $oQuery     = $this->db->query($tSQL);
        $oDataQuery = $oQuery->result_array();

        // print_r($oDataQuery);
        $tOldPdtCode = '';
        $tOldBarCode = '';
        $tOldSugges = '';
        $remail = '';

        foreach($oDataQuery as $nKeyt => $aValuet){
        if($aValuet['FTPdtCode'] != $tOldPdtCode){
            $tSuggerT = $aValuet['FCXtdQtyAll'] / $aValuet['FCPdtUnitFact'];
            if($aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'] != 0){
                $remail = $aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'];
            }else{
                $remail = 0;
            }
            $tOldSugges = floor($tSuggerT);
        }else{
            ///อัพเดทหน่วยสินค้าที่ใหญ่กว่า
            $this->db->set('FCAjdWahB4Adj' , $tOldSugges);
            $this->db->where('FTPdtCode', $aValuet['FTPdtCode']);
            $this->db->where('FTXtdBarCode', $tOldBarCode);
            $this->db->where('FTXthDocKey', $tDocKey);
            $this->db->where('FTSessionID', $tSession);
            $this->db->update('TCNTDocDTTmp');


            $tSuggerT = $remail / $aValuet['FCPdtUnitFact'];
            if($remail % $aValuet['FCPdtUnitFact'] != 0 && $remail != 0){
                $remail = $aValuet['FCXtdQtyAll'] % $aValuet['FCPdtUnitFact'];
            }else{
                $remail = 0;
            }
            if($tSuggerT > 1){
                $tOldSugges = floor($tSuggerT);
            }else{
                $tOldSugges = $tSuggerT;
            }
        }

        // $tSuggerT = floor($tSuggerT);

        $this->db->set('FCAjdWahB4Adj' , $tSuggerT);
        $this->db->where('FTPdtCode', $aValuet['FTPdtCode']);
        $this->db->where('FTXtdBarCode', $aValuet['FTXtdBarCode']);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTSessionID', $tSession);
        $this->db->update('TCNTDocDTTmp');

        $tOldPdtCode = $aValuet['FTPdtCode'];
        $tOldBarCode = $aValuet['FTXtdBarCode'];
        }
    }
    
    // การเพิ่มสินค้า ระหว่าง บาร์โค๊ด - บาร์โค๊ด (จากการสแกนผ่าน input)
    public function FSaMAdjStkEventAddProductsByBarCode($paDataInsert){

        // Get Parameters
        $tPDTCode           = $paDataInsert['tPDTCode'];
        $tBarCode           = $paDataInsert['tBarCode'];
        $tWahCode           = $paDataInsert['tWahCode'];
        $tBchCode           = $paDataInsert['FTBchCode'];
        $tDocNo             = $paDataInsert['FTXthDocNo'];
        $tDocKey            = $paDataInsert['FTXthDocKey'];
        $tSession           = $paDataInsert['FTSessionID'];
        $tUser              = $paDataInsert['tUser'];
        $tSesUsrAgnCode     = $this->session->userdata('tSesUsrAgnCode');
        $nLangEdit          = $paDataInsert['nLangEdit'];
        $tWhereDel  = "";

        // print_r($paDataInsert);
        if (isset($paDataInsert['tPDTCode'])) {
            $tWhereDel  = "AND FTPdtCode = '" . $paDataInsert['tPDTCode']."' ";
        }

        $tSQLDel   = "     DELETE FROM TCNTDocDTTmp 
                WHERE   FTBchCode       = '$tBchCode'
                    AND FTXthDocNo      = '$tDocNo'
                    AND FTXtdBarCode    = '$tBarCode'
                    AND FTXthDocKey     = '$tDocKey'
                    AND FTSessionID     = '$tSession'
                    $tWhereDel
            ";
        $oQueryDel = $this->db->query($tSQLDel);

        // Get Last Seq
        $tSQL   = "     SELECT TOP 1
                            COUNT(FNXtdSeqNo) AS FNXtdLastSeq 
                        FROM TCNTDocDTTmp WITH(NOLOCK)
                        WHERE   FTBchCode   = '$tBchCode'
                            AND FTXthDocNo  = '$tDocNo'
                            AND FTXthDocKey = '$tDocKey'
                            AND FTSessionID = '$tSession' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $nLastSeq = $oQuery->result_array()[0]['FNXtdLastSeq'];
        } else {
            $nLastSeq = 0;
        }

        // Setings
        $tCondition          = "";
        if(!empty($tSesUsrAgnCode)){
            $tCondition      .= " AND (ISNULL(SpcBch.FTAgnCode, '') = '$tSesUsrAgnCode' OR ISNULL(SpcBch.FTAgnCode,'')='')";
        }

        $tCondition     .= " AND PDT.FTPdtCode = '" . $tPDTCode . "' ";

        if($tBarCode == '' || $tBarCode == null){
            $tCondition     .= " ";
        }else{
            $tCondition     .= " AND PBAR.FTBarCode = '" . $tBarCode . "' ";
        }
        
        // Insert Production
        $tSQL = "   INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,
                        FTXtdPdtName,FTPunCode,FTPunName,FTXtdBarCode,FCPdtUnitFact,
                        FTAjdPlcCode,FCAjdUnitQtyC1,FCAjdQtyAllC1,
                        FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FCAjdWahB4Adj,FCXtdQtyAll
                    ) ";
        $tSQL .= "  SELECT 
                        '$tBchCode'		    AS FTBchCode,
                        '$tDocNo'			AS FTXthDocNo,
                        ROW_NUMBER() OVER(ORDER BY PDT.FTPdtCode ASC) + $nLastSeq AS FNRowID,
                        '$tDocKey'	        AS FTXthDocKey,
                        PDT.FTPdtCode,
                        PDT_L.FTPdtName,
                        PPS.FTPunCode,
                        PUN_L.FTPunName,
                        PBAR.FTBarCode,
                        PPS.FCPdtUnitFact,
                        PBAR.FTPlcCode      AS FTAjdPlcCode,
                        0                   AS FCAjdUnitQtyC1,
                        0                   AS FCAjdQtyAllC1,
                        '$tSession'			AS FTSessionID,
                        GETDATE()			AS FDLastUpdOn,
                        GETDATE()			AS FDCreateOn,
                        '$tUser'			AS FTLastUpdBy,
                        '$tUser'			AS FTCreateBy,
                        ISNULL(STKBAL.FCStkQty,0),
                        ISNULL(STKBAL.FCStkQty,0)
                    FROM TCNMPdt PDT WITH(NOLOCK)
                    LEFT JOIN TCNMPdt_L PDT_L WITH(NOLOCK) ON PDT.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = $nLangEdit
                    LEFT JOIN TCNMPdtPackSize PPS WITH(NOLOCK) ON PDT.FTPdtCode = PPS.FTPdtCode
                    LEFT JOIN TCNMPdtUnit_L PUN_L WITH(NOLOCK) ON PPS.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $nLangEdit
                    LEFT JOIN TCNMPdtBar PBAR WITH(NOLOCK) ON PDT.FTPdtCode = PBAR.FTPdtCode AND PPS.FTPunCode = PBAR.FTPunCode AND PBAR.FTBarStaUse = $nLangEdit
                    LEFT JOIN TCNMPdtSpcBch SpcBch ON PDT.FTPdtCode = SpcBch.FTPdtCode
                    LEFT JOIN TCNTPdtStkBal STKBAL ON PDT.FTPdtCode = STKBAL.FTPdtCode AND STKBAL.FTWahCode = '$tWahCode' AND STKBAL.FTBchCode = '$tBchCode'
                    WHERE 1 = 1
                    $tCondition ";

        $this->db->query($tSQL);
        if ($this->db->trans_status() === FALSE) {
            $aReturn = array(
                'tSQL'      => $tSQL,
                'tCode'     => '99',
                'tDesc'     => $this->db->error()
            );
        } else {
            if ($this->db->affected_rows() > 0) {
                $aReturn = array(
                    'tSQL'      => $tSQL,
                    'tCode'     => '1',
                    'tDesc'     => 'Success'
                );
            } else {
                $aReturn = array(
                    'tSQL'      => $tSQL,
                    'tCode'     => '905',
                    'tDesc'     => 'Not Found Data'
                );
            }
        }
        return $aReturn;
    }

    // การเพิ่มสินค้า ระหว่าง บาร์โค๊ด - บาร์โค๊ด (จากการสแกนผ่าน input)
    public function FSaMAdjStkEventUpdateStockAdjust($paDataInsert){

        // Get Parameters
        $tWahCode           = $paDataInsert['tWahCode'];
        $tBchCode           = $paDataInsert['FTBchCode'];
        $tDocno             = $paDataInsert['FTXthDocNo'];
        $tDocKey            = $paDataInsert['FTXthDocKey'];
        $tSession           = $paDataInsert['FTSessionID'];
        $tUser              = $paDataInsert['tUser'];
        $tSesUsrAgnCode     = $this->session->userdata('tSesUsrAgnCode');

        $tSQL = "   UPDATE TCNTDocDTTmp
                    SET TCNTDocDTTmp.FCAjdWahB4Adj = ISNULL(STKBAL.FCStkQty,0),TCNTDocDTTmp.FCXtdQtyAll = ISNULL(STKBAL.FCStkQty,0)
                    FROM TCNTDocDTTmp
                    LEFT JOIN TCNTPdtStkBal STKBAL ON TCNTDocDTTmp.FTPdtCode = STKBAL.FTPdtCode AND STKBAL.FTBchCode = '$tBchCode' AND STKBAL.FTWahCode = '$tWahCode'
                    WHERE TCNTDocDTTmp.FTXthDocKey = '$tDocKey' AND TCNTDocDTTmp.FTSessionID = '$tSession' AND TCNTDocDTTmp.FTXthDocNo = '$tDocno'
                ";

        $this->db->query($tSQL);
        if ($this->db->trans_status() === FALSE) {
            $aStatus = array(
                'tCode' => '905',
                'tDesc' => $this->db->error()
            );
        } else {
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Update Success.'
            );
        }

        return $aStatus;
    } 

    // หาว่าเอกสาร ของสาขานั้นมีการค้างอนุมัติไหม
    public function FSaMCASTCheckDocAllAproveINBCH($ptBCHCode){
        //ใบรับเข้า (คลัง)         = TCNTPdtTwiHD FNXthDocType = 1
        //ใบเบิกออก (คลัง)        = TCNTPdtTwoHD FNXthDocType = 2
        //ใบจ่ายโอน (คลัง)        = TCNTPdtTwoHD FNXthDocType = 4
        //ใบรับโอน (คลัง)         = TCNTPdtTwiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างคลัง    = TCNTPdtTwxHD FTXthDocType = ''
        //ใบจ่ายโอน (สาขา)       = TCNTPdtTboHD
        //ใบรับโอน (สาขา)        = TCNTPdtTbiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างสาขา   = TCNTPdtTbxHD
        //ใบรับของ               = TAPTDoHD
        //ใบลดหนี้แบบมีสินค้า       = TAPTPcHD FNXphDocType = 6
        //ใบนัดหมาย              = TSVTBookHD
        //ใบจอง                 = TCNTPdtTwxHD FTXthDocType = 1
        //ใบรับรถ                = TSVTJob1ReqHD

        $tSQL = "SELECT COUNT(A.FTBchCode) AS rnCount FROM(

                    SELECT FTBchCode from TCNTPdtTwiHD WHERE FNXthDocType = 1 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwoHD WHERE FNXthDocType = 2 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwoHD WHERE FNXthDocType = 4 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwiHD WHERE FNXthDocType = 5 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwxHD WHERE ISNULL(FTXthDocType,'') = '' AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTboHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTbiHD WHERE FNXthDocType = 5 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTbxHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TAPTDoHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                    
                    UNION ALL
                    
                    SELECT FTBchCode FROM TAPTPcHD WHERE FNXphDocType = 6 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1

                    UNION ALL
                    
                    SELECT FTBchCode FROM TSVTBookHD WHERE FTBchCode = '$ptBCHCode' AND FTXshStaPrcDoc = 2 AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1

                    UNION ALL
                    
                    SELECT FTBchCode FROM TCNTPdtTwxHD WHERE FTXthDocType = 1 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1

                    UNION ALL

                    SELECT FTBchCode FROM TSVTJob1ReqHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1

                ) AS A ";

        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->row_array();
        return $aResult['rnCount'];
    }

    // หาว่าเอกสาร ที่ยังไม่อนุมัติ มีอะไรบ้าง
    public function FSaMCASTCheckDocFindAproveINBCH($ptBCHCode){
        //ใบรับเข้า (คลัง)         = TCNTPdtTwiHD FNXthDocType = 1
        //ใบเบิกออก (คลัง)        = TCNTPdtTwoHD FNXthDocType = 2
        //ใบจ่ายโอน (คลัง)        = TCNTPdtTwoHD FNXthDocType = 4
        //ใบรับโอน (คลัง)         = TCNTPdtTwiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างคลัง    = TCNTPdtTwxHD FTXthDocType = ''
        //ใบจ่ายโอน (สาขา)       = TCNTPdtTboHD
        //ใบรับโอน (สาขา)        = TCNTPdtTbiHD FNXthDocType = 5
        //ใบโอนสินค้าระหว่างสาขา   = TCNTPdtTbxHD
        //ใบรับของ               = TAPTDoHD
        //ใบลดหนี้แบบมีสินค้า       = TAPTPcHD FNXphDocType = 6
        //ใบนัดหมาย              = TSVTBookHD
        //ใบจอง                 = TCNTPdtTwxHD FTXthDocType = 1
        //ใบรับรถ                = TSVTJob1ReqHD

        $tSQL = "   SELECT A.* , BCHL.FTBchName AS rtBchName FROM (	
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwiHD' AS rtTableName  , '00013' AS rnCodeNoti
                        FROM TCNTPdtTwiHD WHERE FNXthDocType = 1 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwoHD' AS rtTableName , '00014' AS rnCodeNoti
                        FROM TCNTPdtTwoHD WHERE FNXthDocType = 2 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwoHD' AS rtTableName , '00015' AS rnCodeNoti
                        FROM TCNTPdtTwoHD WHERE FNXthDocType = 4 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwiHD' AS rtTableName , '00016' AS rnCodeNoti
                        FROM TCNTPdtTwiHD WHERE FNXthDocType = 5 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwxHD' AS rtTableName , '00017' AS rnCodeNoti
                        FROM TCNTPdtTwxHD WHERE ISNULL(FTXthDocType,'') = '' AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTboHD' AS rtTableName  , '00008' AS rnCodeNoti
                        FROM TCNTPdtTboHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTbiHD' AS rtTableName , '00009' AS rnCodeNoti
                        FROM TCNTPdtTbiHD WHERE FNXthDocType = 5 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTbxHD' AS rtTableName , '00012' AS rnCodeNoti
                        FROM TCNTPdtTbxHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXphDocNo AS rtDocNo , 'TAPTDoHD' AS rtTableName , '00011' AS rnCodeNoti
                        FROM TAPTDoHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXphDocNo AS rtDocNo , 'TAPTPcHD' AS rtTableName , '00018' AS rnCodeNoti
                        FROM TAPTPcHD WHERE FNXphDocType = 6 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXphStaApv,'') = '' AND FTXphStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXshDocNo AS rtDocNo , 'TSVTBookHD' AS rtTableName  , '00019' AS rnCodeNoti
                        FROM TSVTBookHD WHERE FTBchCode = '$ptBCHCode' AND FTXshStaPrcDoc = 2 AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXthDocNo AS rtDocNo , 'TCNTPdtTwxHD' AS rtTableName , '00020' AS rnCodeNoti
                        FROM TCNTPdtTwxHD WHERE FTXthDocType = 1 AND FTBchCode = '$ptBCHCode' AND ISNULL(FTXthStaApv,'') = '' AND FTXthStaDoc = 1
                    UNION ALL
                        SELECT FTBchCode AS rtBchCode , FTXshDocNo AS rtDocNo , 'TSVTJob1ReqHD' AS rtTableName , '00021' AS rnCodeNoti
                        FROM TSVTJob1ReqHD WHERE FTBchCode = '$ptBCHCode' AND ISNULL(FTXshStaApv,'') = '' AND FTXshStaDoc = 1 
                    ) AS A 
                    LEFT JOIN TCNMBranch_L BCHL ON A.rtBchCode = BCHL.FTBchCode AND FNLngID = '" . $this->session->userdata ( "tLangEdit" ) . "'
                    ORDER BY A.rtTableName ";

        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $aDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'raItems'       => array(),
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aResult;
    }
}
