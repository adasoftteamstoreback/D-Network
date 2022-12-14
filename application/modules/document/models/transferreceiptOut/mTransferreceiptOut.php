<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mTransferreceiptOut extends CI_Model
{

    //Data List
    public function FSaMTRNList($paData){
        $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID = $paData['FNLngID'];
        $tSQL = "
            SELECT  c.* FROM(
                SELECT * FROM(
                    SELECT TOP ".get_cookie('nShowRecordInPageList')."
                        TWI.FTBchCode,
                        BCHL.FTBchName,
                        TWI.FTXthDocNo,
                        CONVERT(CHAR(10), TWI.FDXthDocDate,103) + ' '  + convert(VARCHAR(8), TWI.FDXthDocDate, 14) AS FDXthDocDate,
                        TWI.FTXthStaDoc,
                        TWI.FTXthStaApv,
                        TWI.FTXthStaPrcStk,
                        TWI.FTCreateBy,
                        TWI.FDCreateOn,
                        USRL.FTUsrName AS FTCreateByName,
                        TWI.FTXthApvCode,
                        USRLAPV.FTUsrName AS FTXthApvName
                    FROM [TCNTPdtTwiHD] TWI WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON TWI.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)." 
                    LEFT JOIN TCNMUser_L USRL WITH (NOLOCK) ON TWI.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMUser_L USRLAPV WITH (NOLOCK) ON TWI.FTXthApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."
                    WHERE TWI.FDCreateOn <> ''
                    AND FNXthDocType = 1 
        ";

        if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
            $tBCH    = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQL   .= " AND  TWI.FTBchCode IN ($tBCH) ";
        }

        $aAdvanceSearch = $paData['aAdvanceSearch'];
        @$tSearchList   = $aAdvanceSearch['tSearchAll'];
        if (@$tSearchList != '') {
            $tSQL   .= " 
                AND (
                    (TWI.FTXthDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (BCHL.FTBchName LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                    OR (CONVERT(CHAR(10),TWI.FDXthDocDate,103) LIKE '%".$this->db->escape_like_str($tSearchList)."%')
                )
            ";
        }

        /*????????????????????? - ?????????????????????*/
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
            $tSQL   .= " 
                AND (
                    (TWI.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeFrom)."   AND ".$this->db->escape($tSearchBchCodeTo).")
                    OR (TWI.FTBchCode BETWEEN ".$this->db->escape($tSearchBchCodeTo)."  AND ".$this->db->escape($tSearchBchCodeFrom).")
                )
            ";
        }

        /*??????????????????????????? - ???????????????????????????*/
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];

        if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
            $tSQL   .= " 
                AND ((TWI.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:59:59").")) 
                OR (TWI.FDXthDocDate BETWEEN CONVERT(datetime,".$this->db->escape($tSearchDocDateTo." 23:00:00").") AND CONVERT(datetime,".$this->db->escape($tSearchDocDateFrom." 00:00:00")."))
            )";
        }

        /*?????????????????????????????????*/
        $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 3) {
                $tSQL   .= " AND TWI.FTXthStaDoc = ".$this->db->escape($tSearchStaDoc)." ";
            } else if ($tSearchStaDoc == 2) {
                $tSQL   .= " AND ISNULL(TWI.FTXthStaApv,'') = '' AND TWI.FTXthStaDoc != '3'";
            } else if ($tSearchStaDoc == 1) {
                $tSQL   .= " AND TWI.FTXthStaApv = ".$this->db->escape($tSearchStaDoc)." ";
            }
        }

        /*???????????????????????????????????????*/
        $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
        if (!empty($tSearchStaPrcStk) && ($tSearchStaPrcStk != "0")) {
            if ($tSearchStaPrcStk == 3) {
                $tSQL   .= " AND (TWI.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." OR ISNULL(TWI.FTXthStaPrcStk,'') = '')   ";
            } else {
                $tSQL   .= " AND TWI.FTXthStaPrcStk = ".$this->db->escape($tSearchStaPrcStk)." ";
            }
        }

        /*??????????????????????????????????????????????????????*/
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 2) {
                $tSQL   .= " AND TWI.FNXthStaDocAct <> '1' OR ISNULL(TWI.FNXthStaDocAct,'') = '' ";
            } else if ($tSearchStaDocAct == 1) {
                $tSQL   .= " AND TWI.FNXthStaDocAct = ".$this->db->escape($tSearchStaDocAct)." ";
            }
        }
        $tSQL .= "ORDER BY TWI.FDCreateOn DESC  ) Base) AS c ORDER BY c.FDCreateOn DESC ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $nFoundRow  = 0;
            $nPageAll   = ceil($nFoundRow / $paData['nRow']); //?????? Page All ??????????????? Rec ????????? ????????????????????????????????????
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        } else {
            $aResult    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult    = json_encode($aResult);
        $aResult    = json_decode($jResult, true);
        return $aResult;
    }

       //??????????????????????????????????????????????????????????????????????????????????????????????????????
    public function FSaMTwiUpdateRmk($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXthRmk',$paDataUpdate['FTXthRmk']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXthDocNo',$paDataUpdate['FTXthDocNo']);
        $this->db->update('TCNTPdtTwiHD');

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

    //Count Page
    // public function FSnMTWOGetPageAll($paData)
    // {
    //     $nLngID = $paData['FNLngID'];

    //     $tSQL = "
    //         SELECT 
    //             COUNT (TWI.FTXthDocNo) AS counts
    //         FROM [TCNTPdtTwiHD] TWI WITH (NOLOCK) 
    //         LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON TWI.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
    //         WHERE 1=1 AND FNXthDocType = 1 
    //     ";

    //     if ($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP') {
    //         $tBCH = $this->session->userdata("tSesUsrBchCodeMulti");
    //         $tSQL .= " AND  TWI.FTBchCode IN ($tBCH) ";
    //     }

    //     $aAdvanceSearch = $paData['aAdvanceSearch'];
    //     @$tSearchList = $aAdvanceSearch['tSearchAll'];
    //     if (@$tSearchList != '') {
    //         $tSQL .= " AND ((TWI.FTXthDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (TWI.FDXthDocDate LIKE '%$tSearchList%'))";
    //     }

    //     /*????????????????????? - ?????????????????????*/
    //     $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
    //     $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
    //     if (!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)) {
    //         $tSQL .= " AND ((TWI.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (TWI.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
    //     }

    //     /*??????????????????????????? - ???????????????????????????*/
    //     $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
    //     $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
    //     if (!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)) {
    //         $tSQL .= " AND ((TWI.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (TWI.FDXthDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
    //     }

    //     /*?????????????????????????????????*/
    //     // $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
    //     // if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
    //     //     $tSQL .= " AND TWI.FTXthStaDoc = '$tSearchStaDoc'";
    //     // }

    //     // /*????????????????????????????????????*/
    //     // $tSearchStaApprove = $aAdvanceSearch['tSearchStaApprove'];
    //     // if (!empty($tSearchStaApprove) && ($tSearchStaApprove != "0")) {
    //     //     if ($tSearchStaApprove == 2) {
    //     //         $tSQL .= " AND TWI.FTXthStaApv = '$tSearchStaApprove' OR TWI.FTXthStaApv = '' ";
    //     //     } else {
    //     //         $tSQL .= " AND TWI.FTXthStaApv = '$tSearchStaApprove'";
    //     //     }
    //     // }

    //     /*?????????????????????????????????*/
    //     // print_r($aAdvanceSearch); die();
    //     $tSearchStaDoc = $aAdvanceSearch['tSearchStaDoc'];
    //     if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
    //         if ($tSearchStaDoc == 3) {
    //             $tSQL .= " AND TWI.FTXthStaDoc = '$tSearchStaDoc'";
    //         } else if ($tSearchStaDoc == 2) {
    //             $tSQL .= " AND ISNULL(TWI.FTXthStaApv,'') = '' AND TWI.FTXthStaDoc != '3'";
    //         } else if ($tSearchStaDoc == 1) {
    //             $tSQL .= " AND TWI.FTXthStaApv = '$tSearchStaDoc'";
    //         }
    //     }

    //     /*???????????????????????????????????????*/
    //     $tSearchStaPrcStk = $aAdvanceSearch['tSearchStaPrcStk'];
    //     if (!empty($tSearchStaPrcStk) && ($tSearchStaPrcStk != "0")) {
    //         if ($tSearchStaPrcStk == 3) {
    //             $tSQL .= " AND (TWI.FTXthStaPrcStk = '$tSearchStaPrcStk' OR ISNULL(TWI.FTXthStaPrcStk,'') = '')  ";
    //         } else {
    //             $tSQL .= " AND TWI.FTXthStaPrcStk = '$tSearchStaPrcStk'";
    //         }
    //     }

    //     /*??????????????????????????????????????????????????????*/
    //     $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
    //     if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
    //         if ($tSearchStaDocAct == 2) {
    //             $tSQL .= " AND TWI.FNXthStaDocAct <> '1' OR TWI.FNXthStaDocAct = NULL";
    //         } else if ($tSearchStaDocAct == 1) {
    //             $tSQL .= " AND TWI.FNXthStaDocAct = '$tSearchStaDocAct'";
    //         }
    //     }

    //     $oQuery = $this->db->query($tSQL);
    //     if ($oQuery->num_rows() > 0) {
    //         return $oQuery->result();
    //     } else { // No Data
    //         return false;
    //     }
    // }

    // Clear Tmp
    public function FSxMTWIClearPdtInTmp($ptTblSelectData)
    {
        $tXthDocKey = $ptTblSelectData;
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTXthDocKey', $tXthDocKey);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');

        $this->db->where_in('FTXthDocKey', $tXthDocKey);
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTFhnTmp');
        
    }

    // Get Shop Code From User Login
    public function FSaMASTGetShpCodeForUsrLogin($paDataShp)
    {
        $nLngID = $paDataShp['FNLngID'];
        $tUsrLogin = $paDataShp['tUsrLogin'];

        $tSQL = " 
            SELECT
                UGP.FTBchCode,
                BCHL.FTBchName,
                MCHL.FTMerCode,
                MCHL.FTMerName,
                UGP.FTShpCode,
                SHPL.FTShpName,
                SHP.FTShpType,
                SHP.FTWahCode AS FTWahCode,
                WAHL.FTWahName AS FTWahName
            FROM TCNTUsrGroup UGP WITH (NOLOCK)
            LEFT JOIN TCNMBranch BCH WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode 
            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
            LEFT JOIN TCNMShop SHP WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
            LEFT JOIN TCNMShop_L  SHPL WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
            LEFT JOIN TCNMMerchant_L MCHL WITH (NOLOCK) ON SHP.FTMerCode = MCHL.FTMerCode AND MCHL.FNLngID = $nLngID
            LEFT JOIN TCNMWaHouse_L WAHL WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
            WHERE FTUsrCode ='$tUsrLogin' 
        ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array();
        } else {
            $aResult = "";
        }
        unset($oQuery);
        return $aResult;
    }

    // Get Data In Doc DT Temp
    public function FSaMTWIGetDocDTTempListPage($paDataWhere)
    {
        $tTWIDocNo = $paDataWhere['FTXthDocNo'];
        $tTWIDocKey = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tTWISesSessionID = $this->session->userdata('tSesSessionID');

        //$aRowLen = FCNaHCallLenData($paDataWhere['nRow'], $paDataWhere['nPage']);
        $tSQL = " 
            SELECT 
                c.* 
            FROM(
                SELECT  
                    ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* 
                FROM (
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
                        DOCTMP.FTTmpStatus,
                        DOCTMP.FDLastUpdOn,
                        DOCTMP.FDCreateOn,
                        DOCTMP.FTLastUpdBy,
                        DOCTMP.FTCreateBy,
                        DOCTMP.FCXtdAmt
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                    AND DOCTMP.FTXthDocNo  = '$tTWIDocNo'
                    AND DOCTMP.FTXthDocKey = '$tTWIDocKey'
                    AND DOCTMP.FTSessionID = '$tTWISesSessionID'
                    AND DOCTMP.FTCabNameForTWXVD IS NULL 
        ";

        if (isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)) {
            $tSQL   .=  "   
                AND (
                    DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                    OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%' 
                )
            ";
        }
        $tSQL .= ") Base) AS c ";
        /*WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";*/

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDataList = $oQuery->result_array();
            $aFoundRow = 0; //$this->FSaMTWIGetDocDTTempListPageAll($paDataWhere);
            $nFoundRow = 0; //($aFoundRow['rtCode'] == '1') ? $aFoundRow['rtCountData'] : 0;
            $nPageAll = 0; //ceil($nFoundRow / $paDataWhere['nRow']);
            $aDataReturn = array(
                'raItems' => $aDataList,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage' => $nPageAll,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aDataReturn = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }

    //Count All Documeny DT Temp
    public function FSaMTWIGetDocDTTempListPageAll($paDataWhere)
    {
        $tTWIDocNo = $paDataWhere['FTXthDocNo'];
        $tTWIDocKey = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tTWISesSessionID = $this->session->userdata('tSesSessionID');

        $tSQL = " 
            SELECT 
                COUNT (DOCTMP.FTXthDocNo) AS counts
            FROM TCNTDocDTTmp DOCTMP
            WHERE 1 = 1 
        ";

        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tTWIDocNo' ";
        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tTWIDocKey' ";
        $tSQL .= " AND DOCTMP.FTSessionID = '$tTWISesSessionID' ";
        $tSQL .= " AND DOCTMP.FTCabNameForTWXVD IS NULL ";

        if (isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)) {
            $tSQL .= " AND ( DOCTMP.FTPdtCode LIKE '%$tSearchPdtAdvTable%' ";
            $tSQL .= " OR DOCTMP.FTXtdPdtName LIKE '%$tSearchPdtAdvTable%' ";
            $tSQL .= " OR DOCTMP.FTPunName LIKE '%$tSearchPdtAdvTable%' ";
            $tSQL .= " OR DOCTMP.FTXtdBarCode LIKE '%$tSearchPdtAdvTable%' )";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aDataReturn = array(
                'rtCountData' => $aDetail['counts'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aDataReturn = array(
                'rtCode' => '800',
                'rtDesc' => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Sum Amount DT Temp
    public function FSaMTWISumDocDTTemp($paDataWhere)
    {
        $tTWIDocNo = $paDataWhere['FTXthDocNo'];
        $tTWIDocKey = $paDataWhere['FTXthDocKey'];
        $tTWISesSessionID = $this->session->userdata('tSesSessionID');

        $tSQL = " 
            SELECT
                SUM(FCXtdNetAfHD) AS FCXtdSumNetAfHD,
                SUM(FCXtdAmtB4DisChg) AS FCXtdSumAmtB4DisChg
            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
            WHERE 1 = 1 
        ";
        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tTWIDocNo'";
        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tTWIDocKey'";
        $tSQL .= " AND DOCTMP.FTSessionID = '$tTWISesSessionID'";
        $tSQL .= " AND DOCTMP.FTCabNameForTWXVD IS NULL";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aResult = $oQuery->row_array();
            $aDataReturn =  array(
                'raDataSum' => $aResult,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aDataReturn = array(
                'rtCode' => '800',
                'rtDesc' => 'Data Sum Empty',
            );
        }
        unset($oQuery);
        unset($aResult);
        return $aDataReturn;
    }

    // ?????????????????? Price ???????????????????????????
    public function FSaMTWIGetPriceBYPDT($ptCode)
    {
        $tSQL           = "SELECT TOP 1 FTCmpWhsInOrEx FROM TCNMComp";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        $nVat           = $oList[0]['FTCmpWhsInOrEx'];

        $FNLngID        = $this->session->userdata("tLangEdit");
        $FTPdtCode      = $ptCode;
        $tVatInorEx     = $nVat;

        $tSQL =  "SELECT P.*,PUNL.FTPunName,";
        if ($tVatInorEx == 1) {
            $tSQL .= " ISNULL(CAVG.FCPdtCostEx    * ISNULL(P.FCPdtUnitFact,1),NULL)	as PDTCostAvgInorEX ,";
        } else if ($tVatInorEx == 2) {
            $tSQL .= " ISNULL(CAVG.FCPdtCostIn    * ISNULL(P.FCPdtUnitFact,1),NULL)	as PDTCostAvgInorEX ,";
        }
        $tSQL .= " ISNULL(PSPL.FCSplLastPrice * ISNULL(P.FCPdtUnitFact,1),NULL) as PDTCostLastPrice,
                ISNULL(PCFF.FCPdtCostEx       *	ISNULL(P.FCPdtUnitFact,1),NULL)	as PDTCostFiFo			
                FROM(
                SELECT 
                        PDT.FTPdtCode,	
                        PBCH.FTShpCode,
                        PDT_L.FTPdtName,
                        PBCH.FTBchCode,			
                        BAR.FTBarCode,
                        BAR.FTPunCode,
                        PPS.FCPdtUnitFact,
                        PDT.FCPdtCostStd *  ISNULL(PPS.FCPdtUnitFact,1)	as PDTCostSTD,
                        PDTIMG.FTImgObj,
                        LOGSEQ.FTPlcCode
                            
                FROM TCNMPdt PDT
                LEFT JOIN TCNMPdtSpcBch PBCH  ON PDT.FTPdtCode = PBCH.FTPdtCode  
                LEFT JOIN TCNMPdtBar BAR ON BAR.FTPdtCode = PDT.FTPdtCode
                LEFT JOIN  TCNTPdtLocSeq  LOGSEQ  ON BAR.FTBarCode   = LOGSEQ.FTBarCode
                LEFT JOIN TCNMPdt_L PDT_L ON PDT.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = '$FNLngID'
                INNER JOIN TCNMPdtPackSize PPS ON PDT.FTPdtCode = PPS.FTPdtCode AND BAR.FTPunCode = PPS.FTPunCode 
                LEFT JOIN TCNMImgPdt PDTIMG ON PDT.FTPdtCode = PDTIMG.FTImgRefID AND PDTIMG.FTImgTable = 'TCNMPdt' AND PDTIMG.FTImgKey = 'master' AND PDTIMG.FNImgSeq = '1'
                WHERE PDT.FTPdtCode = '$FTPdtCode'
                ) P
                LEFT JOIN TCNMPdtUnit_L PUNL ON P.FTPunCode = PUNL.FTPunCode  AND PUNL.FNLngID = '$FNLngID'
                LEFT JOIN  TCNMPdtCostAvg CAVG	ON CAVG.FTPdtCode = P.FTPdtCode
                LEFT JOIN  TCNMPdtSpl PSPL	ON PSPL.FTPdtCode = P.FTPdtCode AND PSPL.FTBarCode = P.FTBarCode
                LEFT JOIN  TCNMPdtCostFiFo PCFF	ON PCFF.FTPdtCode = P.FTPdtCode";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        } else {
            return false;
        }
    }

    // ?????? Max Seq From Doc DT Temp
    public function FSaMTWIGetMaxSeqDocDTTemp($paDataWhere)
    {
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
        $tSQL   .= " AND DOCTMP.FTCabNameForTWXVD IS NULL ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['rnMaxSeqNo'];
        } else {
            $nResult    = 0;
        }
        return empty($nResult) ? 0 : $nResult;
    }


    // ?????? Max Seq From Doc DT Temp
    public function FSaMTWIGetMaxSeqDocDTFhnTemp($paDataWhere)
    {
        $tSOBchCode         = $paDataWhere['FTBchCode'];
        $tSODocNo           = $paDataWhere['FTXthDocNo'];
        $tSODocKey          = $paDataWhere['FTXthDocKey'];
        $tSOSesSessionID    = $this->session->userdata('tSesSessionID');
        $tSQL   =   "   SELECT 
                            MAX(DOCTMP.FNXsdSeqNo) AS rnMaxSeqNo
                        FROM TCNTDocDTFhnTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1 ";
        $tSQL   .= " AND DOCTMP.FTBchCode   = '$tSOBchCode'";
        $tSQL   .= " AND DOCTMP.FTXshDocNo  = '$tSODocNo'";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tSODocKey'";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tSOSesSessionID'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $nResult    = $aDetail['rnMaxSeqNo'];
        } else {
            $nResult    = 0;
        }
        return empty($nResult) ? 0 : $nResult;
    }


    //???????????????????????? PDT
    public function FSaMTWIGetDataPdt($paDataPdtParams)
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
                            ISNULL(PRI4PDT.FCPgdPriceRet,0) AS FTPdtSalePrice,
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
                            ISNULL(PKS.FCPdtUnitFact,1) AS FCPdtUnitFact,
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
                        LEFT JOIN (
                            SELECT DISTINCT
                                P4PDT.FTPdtCode,
                                P4PDT.FTPunCode,
                                P4PDT.FDPghDStart,
                                P4PDT.FTPghTStart,
                                P4PDT.FCPgdPriceRet
                                --P4PDT.FCPgdPriceWhs,
                                --P4PDT.FCPgdPriceNet
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

    //??????????????????????????????????????? Tmp
    public function FSaMTWIInsertPDTToTemp($paDataPdtMaster, $paDataPdtParams)
    {
        $paPIDataPdt    = $paDataPdtMaster['raItem'];

        // ??????????????????????????????????????????????????????????????????????????????
        $tSQL   =   "   SELECT
                            FNXtdSeqNo, 
                            FCXtdQty
                        FROM TCNTDocDTTmp
                        WHERE 1=1 
                        AND FTXthDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                        AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                        AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                        AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                        AND FTPdtCode       = '" . $paPIDataPdt["FTPdtCode"] . "'
                        AND FTXtdBarCode    = '" . $paPIDataPdt["FTBarCode"] . "'
                        ORDER BY FNXtdSeqNo";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            // ????????????????????????????????????????????????????????????????????????????????????????????????
            $aResult    = $oQuery->row_array();
            $tSQL       =   "   UPDATE TCNTDocDTTmp
                                SET FCXtdQty = '" . ($aResult["FCXtdQty"] + 1) . "' ,
                                FTCabNameForTWXVD = null
                                WHERE 1=1
                                AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                                AND FTXthDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                                AND FNXtdSeqNo      = '" . $aResult["FNXtdSeqNo"] . "'
                                AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                                AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                                AND FTPdtCode       = '" . $paPIDataPdt["FTPdtCode"] . "'
                                AND FTXtdBarCode    = '" . $paPIDataPdt["FTBarCode"] . "' ";
            $this->db->query($tSQL);
            $aStatus = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Add Success.',
            );
        } else {
            // ?????????????????????????????????????????????
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
                'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                'FTVatCode'         => $paPIDataPdt['FTVatCode'],
                'FCXtdVatRate'      => $paPIDataPdt['FCVatRate'],
                'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paPIDataPdt['FTPdtSalePrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1 * $paPIDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FTXtdDocNoRef'     => $paDataPdtParams['tDocRefSO'],
                'FTTmpStatus'       => $paPIDataPdt['FTPdtForSystem'],
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            );
            $this->db->insert('TCNTDocDTTmp', $aDataInsert);

            $this->db->last_query();
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


       //??????????????????????????????????????? Tmp
       public function FSaMTWIInsertPDTFhnToTemp($paDataPdtParams)
       {
   
           // ??????????????????????????????????????????????????????????????????????????????
           $tSQL   =   "   SELECT
                               FNXsdSeqNo, 
                               FCXtdQty
                           FROM TCNTDocDTFhnTmp
                           WHERE 1=1 
                           AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                           AND FTXshDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                           AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                           AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                           AND FTPdtCode       = '" . $paDataPdtParams["tPdtCode"] . "'
                           AND FTFhnRefCode    = '" . $paDataPdtParams["tRefCode"] . "'
                           ORDER BY FNXsdSeqNo";
           $oQuery = $this->db->query($tSQL);
           if ($oQuery->num_rows() > 0) {
               // ????????????????????????????????????????????????????????????????????????????????????????????????
               $aResult    = $oQuery->row_array();
               $tSQL       =   "   UPDATE TCNTDocDTFhnTmp
                                   SET FCXtdQty = '" . ($paDataPdtParams["nTWInQty"]) . "' 
                                   WHERE 1=1
                                   AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                                   AND FTXshDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                                   AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                                   AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                                   AND FTPdtCode       = '" . $paDataPdtParams["tPdtCode"] . "'
                                   AND FTFhnRefCode    = '" . $paDataPdtParams["tRefCode"] . "' ";
               $this->db->query($tSQL);
               $aStatus = array(
                   'rtCode'    => '1',
                   'rtDesc'    => 'Add Success.',
               );
           } else {
               // ?????????????????????????????????????????????
               $aDataInsert    = array(
                   'FTBchCode'         => $paDataPdtParams['tBchCode'],
                   'FTXshDocNo'        => $paDataPdtParams['tDocNo'],
                   'FNXsdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                   'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                   'FTPdtCode'         => $paDataPdtParams['tPdtCode'],
                   'FTXtdPdtName'      => '',
                   'FCXtdQty'          => $paDataPdtParams['nTWInQty'],
                   'FTFhnRefCode'      => $paDataPdtParams['tRefCode'],
                   'FTSessionID'       => $paDataPdtParams['tSessionID'],
                   'FDCreateOn'        => date('Y-m-d H:i:s'),
                   'FTCreateBy'        => $this->session->userdata('tSesUsername')
               );
               $this->db->insert('TCNTDocDTFhnTmp', $aDataInsert);
   
               $this->db->last_query();
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


    public function FSaMTWIDeletePDTFhnToTemp($paDataPdtParams){

            $tSQL   =   "  DELETE
                    FROM TCNTDocDTTmp
                    WHERE 1=1 
                    AND FTXthDocNo      = '" . $paDataPdtParams['tDocNo'] . "'
                    AND FTBchCode       = '" . $paDataPdtParams['tBchCode'] . "'
                    AND FTXthDocKey     = '" . $paDataPdtParams['tDocKey'] . "'
                    AND FTSessionID     = '" . $paDataPdtParams['tSessionID'] . "'
                    AND FTPdtCode       = '" . $paDataPdtParams["tPdtCode"] . "'
                  ";
            $oQuery = $this->db->query($tSQL);


    }



    //Clear ???????????????????????????????????????????????? ??????????????????????????????????????????
    public function FSaMTWIClearDocDTFhnTemp($paDataPdtParams){
        $tSesSessionID = $this->session->userdata('tSesSessionID');
        $tSQL   =   "  DELETE
            FROM TCNTDocDTFhnTmp
            WHERE 1=1 
            AND FTXshDocNo      = '" . $paDataPdtParams['FTXthDocNo'] . "'
            AND FTBchCode       = '" . $paDataPdtParams['FTBchCode'] . "'
            AND FTXthDocKey     = '" . $paDataPdtParams['FTXthDocKey'] . "'
            AND FTSessionID     = '" . $tSesSessionID . "'
            AND FTPdtCode       = '" . $paDataPdtParams["FTPdtCode"] . "'
        ";
        $oQuery = $this->db->query($tSQL);
    }

    //???????????????????????? HD - ????????????????????????
    public function FSnMTWIDelDocument($paDataDoc)
    {
        $tTWIDocNo  = $paDataDoc['tTWIDocNo'];

        $this->db->trans_begin();

        //???????????????????????????????????????????????????????????? CN
        if (FCNnHSizeOf($tTWIDocNo) > 1) {
            foreach ($tTWIDocNo as $tData) {
                $aPackData = array('FTXthDocNo' => $tData);
                $this->FSvMCheckDocumentInCN('CANCEL', $aPackData);
            }
        } else {
            $aPackData = array('FTXthDocNo' => $tTWIDocNo);
            $this->FSvMCheckDocumentInCN('CANCEL', $aPackData);
        }

        // Document HD 
        $this->db->where_in('FTXthDocNo', $tTWIDocNo);
        $this->db->delete('TCNTPdtTwiHD');

        // Document DT
        $this->db->where_in('FTXthDocNo', $tTWIDocNo);
        $this->db->delete('TCNTPdtTwiDT');

        // Document Temp
        $this->db->where_in('FTXthDocNo', $tTWIDocNo);
        $this->db->where_in('FTXthDocKey', 'TCNTPdtTwiHD');
        $this->db->delete('TCNTDocDTTmp');

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
        return $aStaDeleteDoc;
    }

    //???????????????????????? DTTmp - ????????????????????????
    public function FSnMTWIDelPdtInDTTmp($paDataWhere)
    {
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID', $paDataWhere['tSessionID']);
        $this->db->where_in('FTPdtCode', $paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['nSeqNo']);
        $this->db->where_in('FTXthDocNo', $paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        // $this->db->set('FTCabNameForTWXVD', 'DELETE_TEMP');
        // $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        // $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        // $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        // $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        // $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        // $this->db->update('TCNTDocDTTmp');
        return;
    }

    //???????????????????????? DTTmp - ?????????????????????
    public function FSnMTWIDelMultiPdtInDTTmp($paDataWhere)
    {
        $tSessionID = $this->session->userdata('tSesSessionID');

        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->where_in('FNXtdSeqNo', $paDataWhere['aDataSeqNo']);
     //   $this->db->where_in('FTPunCode', $paDataWhere['aDataPunCode']);
        $this->db->where_in('FTPdtCode', $paDataWhere['aDataPdtCode']);
        $this->db->where_in('FTXthDocNo', $paDataWhere['tDocNo']);
        $this->db->where_in('FTBchCode', $paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');

        // $this->db->set('FTCabNameForTWXVD', 'DELETE_TEMP');
        // $this->db->where_in('FTSessionID',$tSessionID);
        // $this->db->where_in('FTPunCode',$paDataWhere['aDataPunCode']);
        // $this->db->where_in('FTPdtCode',$paDataWhere['aDataPdtCode']);
        // $this->db->where_in('FNXtdSeqNo',$paDataWhere['aDataSeqNo']);
        // $this->db->where_in('FTXthDocNo',$paDataWhere['tDocNo']);
        // $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        // $this->db->update('TCNTDocDTTmp');
        return;
    }

    //????????????????????????
    public function FSaMTWICalInDTTemp($paParams)
    {
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tSQL       = " SELECT
                            /* ?????????????????? ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdNet, 0)) AS FCXphTotal,

                            /* ??????????????????????????????????????????????????????????????? ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNV,

                            /* ?????????????????????????????????????????????????????? ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 2 THEN ISNULL(DTTMP.FCXtdNet, 0) ELSE 0 END) AS FCXphTotalNoDis,

                            /* ??????????????????????????????????????????????????? ??????????????????????????? ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END) AS FCXphTotalB4DisChgV,

                            /* ??????????????????????????????????????????????????? ???????????????????????????????????? */
                            SUM(CASE WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0) ELSE 0 END) AS FCXphTotalB4DisChgNV,

                            /* ???????????????????????????????????? ??????????????????????????? ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgV,

                            /* ???????????????????????????????????? ???????????????????????????????????? ==============================================================*/
                            SUM(CASE WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0) ELSE 0 END) AS FCXphTotalAfDisChgNV,

                            /* ????????????????????????????????????????????? ==============================================================*/
                            (
                                (
                                    /* ?????????????????? */
                                    SUM(DTTMP.FCXtdNet)
                                    - 
                                    /* ??????????????????????????????????????????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                                -
                                (
                                    /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                            ELSE 0
                                        END
                                    )
                                    -
                                    /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                            ) AS FCXphAmtV,

                            /* ???????????????????????????????????????????????????????????? ==============================================================*/
                            (
                                (
                                    /* ??????????????????????????????????????????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                                -
                                (
                                    /* ??????????????????????????????????????????????????? ???????????????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                            ELSE 0
                                        END
                                    )
                                    -
                                    /* ???????????????????????????????????? ???????????????????????????????????? */
                                    SUM(
                                        CASE
                                            WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                            ELSE 0
                                        END
                                    )
                                )
                            ) AS FCXphAmtNV,

                            /* ????????????????????? ==============================================================*/
                            SUM(ISNULL(DTTMP.FCXtdVat, 0)) AS FCXphVat,

                            /* ?????????????????????????????? ==============================================================*/
                            (
                                (
                                    /* ????????????????????????????????????????????? */
                                    (
                                        (
                                            /* ?????????????????? */
                                            SUM(DTTMP.FCXtdNet)
                                            - 
                                            /* ??????????????????????????????????????????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                        -
                                        (
                                            /* ??????????????????????????????????????????????????? ??????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                                    ELSE 0
                                                END
                                            )
                                            -
                                            /* ???????????????????????????????????? ??????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdVatType = 1 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                    )
                                    -
                                    /* ????????????????????? */
                                    SUM(ISNULL(DTTMP.FCXtdVat, 0))	
                                )
                                +
                                (
                                    /* ???????????????????????????????????????????????????????????? */
                                    (
                                        (
                                            /* ??????????????????????????????????????????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNet, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                        -
                                        (
                                            /* ??????????????????????????????????????????????????? ???????????????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdStaAlwDis = 1 AND DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdAmtB4DisChg, 0)
                                                    ELSE 0
                                                END
                                            )
                                            -
                                            /* ???????????????????????????????????? ???????????????????????????????????? */
                                            SUM(
                                                CASE
                                                    WHEN DTTMP.FTXtdVatType = 2 THEN ISNULL(DTTMP.FCXtdNetAfHD, 0)
                                                    ELSE 0
                                                END
                                            )
                                        )
                                    )
                                )
                            ) AS FCXphVatable,

                            /* ??????????????????????????????????????? ??? ????????????????????? ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdWhtCode
                                FROM TCNTDocDTTmp DOCCONCAT
                                WHERE  1=1 
                                AND DOCCONCAT.FTBchCode = '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo = '$tDocNo'
                                AND DOCCONCAT.FTSessionID = '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXphWpCode,

                            /* ????????????????????? ??? ????????????????????? ==============================================================*/
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

    //Get Cal From HDDis Temp
    public function FSaMTWICalInHDDisTemp($paParams)
    {
        $tDocNo     = $paParams['tDocNo'];
        $tDocKey    = $paParams['tDocKey'];
        $tBchCode   = $paParams['tBchCode'];
        $tSessionID = $paParams['tSessionID'];
        $tSQL       = " SELECT
                            /* ???????????????????????????????????????????????????????????? ==============================================================*/
                            STUFF((
                                SELECT  ',' + DOCCONCAT.FTXtdDisChgTxt
                                FROM TCNTDocHDDisTmp DOCCONCAT
                                WHERE  1=1 
                                AND DOCCONCAT.FTBchCode 		= '$tBchCode'
                                AND DOCCONCAT.FTXthDocNo		= '$tDocNo'
                                AND DOCCONCAT.FTSessionID		= '$tSessionID'
                            FOR XML PATH('')), 1, 1, '') AS FTXphDisChgTxt,
                            /* ????????????????????????????????????????????? ==============================================================*/
                            SUM( 
                                CASE 
                                    WHEN HDDISTMP.FTXtdDisChgType = 1 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    WHEN HDDISTMP.FTXtdDisChgType = 2 THEN ISNULL(HDDISTMP.FCXtdAmt, 0)
                                    ELSE 0 
                                END
                            ) AS FCXphDis,
                            /* ?????????????????????????????????????????????????????? ==============================================================*/
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

    //???????????????????????? HD
    public function FSxMTWIAddUpdateHD($paDataMaster, $paDataWhere, $paTableAddUpdate)
    {
        $aDataGetDataHD = $this->FSaMTWIGetDataDocHD(array(
            'FTXthDocNo' => $paDataWhere['FTXthDocNo'],
            'FNLngID' => $this->session->userdata("tLangEdit")
        ));

        $aDataAddUpdateHD = array();
        if (isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1) {
            $aDataHDOld = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD = array_merge($paDataMaster, array(
                'FTBchCode' => $paDataWhere['FTBchCode'],
                'FTXthDocNo' => $paDataWhere['FTXthDocNo'],
                'FDLastUpdOn' => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy' => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn' => $aDataHDOld['FDCreateOn'],
                'FTCreateBy' => $aDataHDOld['FTCreateBy']
            ));
        } else {
            $aDataAddUpdateHD = array_merge($paDataMaster, array(
                'FTBchCode' => $paDataWhere['FTBchCode'],
                'FTXthDocNo' => $paDataWhere['FTXthDocNo'],
                'FDCreateOn' => $paDataWhere['FDCreateOn'],
                'FTCreateBy' => $paDataWhere['FTCreateBy'],
            ));
        }

        // Delete HD
        $this->db->where_in('FTBchCode', $aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTXthDocNo', $aDataAddUpdateHD['FTXthDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert HD
        $this->db->insert($paTableAddUpdate['tTableHD'], $aDataAddUpdateHD);
    }

    public function FSxMTWIAddUpdateHDRef($paTableAddUpdate, $paDataWhere)
    {

        $tTWIDocNo       = $paDataWhere['FTXthDocNo'];
        $tBchCode       = $paDataWhere['FTBchCode'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo', $tTWIDocNo);
        $this->db->where('FTBchCode', $tBchCode);
        $this->db->delete('TCNTPdtTwiHDRef');

        $aDataInsert = array(
            'FTBchCode'            => $tBchCode,
            'FTXthDocNo'           => $tTWIDocNo,
            'FTXthCtrName'         => $paTableAddUpdate['FTXthCtrName'],
            'FDXthTnfDate'         => $paTableAddUpdate['FDXthTnfDate'],
            'FTXthRefTnfID'        => $paTableAddUpdate['FTXthRefTnfID'],
            'FTXthRefVehID'        => $paTableAddUpdate['FTXthRefVehID'],
            'FTXthQtyAndTypeUnit'  => $paTableAddUpdate['FTXthQtyAndTypeUnit'],
            // 'FNXthShipAdd'         => $paTableAddUpdate['FNXthShipAdd'],
            'FTViaCode'            => $paTableAddUpdate['FTViaCode'],
        );
        $this->db->insert('TCNTPdtTwiHDRef', $aDataInsert);
        
        return;
    }

    //??????????????????????????? HD
    public function FSaMTWIGetDataDocHD($paDataWhere)
    {
        $tTWIDocNo   = $paDataWhere['FTXthDocNo'];
        $nLngID      = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                        BCHL.FTBchName,
                        SHP.FTMerCode,
                        MERL.FTMerName,
                        SHP.FTShpType,
                        SHP.FTShpCode,
                        SHPL.FTShpName,
                        SHPL_T.FTShpName AS ShpNameTo,
                        POS.FTWahRefCode,
                        POSL.FTPosComName,
                        WAHL.FTWahName,
                        WAHL_T.FTWahName AS WahNameTo,
                        DPTL.FTDptName,
                        USRL.FTUsrName,
                        USRL.FTUsrName AS FTXphApvName,
                        SPLL.FTSplName,
                        Cus_L.FTCstName,
                        DOCHD.FTBchCode,
                        DOCHD.FTXthDocNo,
                        DOCHD.FNXthDocType,
                        DOCHD.FTXthRsnType,
                        DOCHD.FTXthTypRefFrm,
                        DOCHD.FDXthDocDate,
                        DOCHD.FTXthVATInOrEx,
                        DOCHD.FTDptCode,
                        DOCHD.FTXthMerCode,
                        DOCHD.FTXthShopFrm,
                        DOCHD.FTXthShopTo,
                        DOCHD.FTXthWhFrm,
                        DOCHD.FTXthWhTo,
                        DOCHD.FTXthPosFrm,
                        DOCHD.FTXthPosTo,
                        DOCHD.FTSplCode,
                        DOCHD.FTCstCode,
                        DOCHD.FTXthOther,
                        DOCHD.FTUsrCode,
                        DOCHD.FTSpnCode,
                        DOCHD.FTXthApvCode,
                        DOCHD.FTXthRefExt,
                        DOCHD.FDXthRefExtDate,
                        DOCHD.FTXthRefInt,
                        DOCHD.FDXthRefIntDate,
                        DOCHD.FNXthDocPrint,
                        DOCHD.FCXthTotal,
                        DOCHD.FCXthVat,
                        DOCHD.FCXthVatable,
                        DOCHD.FTXthRmk,
                        DOCHD.FTXthStaDoc,
                        DOCHD.FTXthStaApv,
                        DOCHD.FTXthStaPrcStk,
                        DOCHD.FTXthStaDelMQ,
                        DOCHD.FNXthStaDocAct,
                        DOCHD.FNXthStaRef,
                        DOCHD.FTRsnCode,
                        DOCHD.FDLastUpdOn,
                        DOCHD.FTLastUpdBy,
                        DOCHD.FDCreateOn,
                        DOCHD.FTCreateBy,
                        REA.FTRsnName
                    FROM TCNTPdtTwiHD DOCHD WITH (NOLOCK)
                    LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID	    = $nLngID
                    LEFT JOIN TCNMShop          SHP     WITH (NOLOCK)   ON DOCHD.FTXthShopFrm   = SHP.FTShpCode     AND DOCHD.FTBchCode     = SHP.FTBchCode
                    LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK)   ON DOCHD.FTXthShopFrm   = SHPL.FTShpCode	AND DOCHD.FTBchCode     = SHPL.FTBchCode  AND SHPL.FNLngID	    = $nLngID
                    LEFT JOIN TCNMShop_L        SHPL_T  WITH (NOLOCK)   ON DOCHD.FTXthShopTo    = SHPL_T.FTShpCode	AND DOCHD.FTBchCode     = SHPL_T.FTBchCode  AND SHPL_T.FNLngID	    = $nLngID
                    LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK)   ON SHP.FTMerCode        = MERL.FTMerCode	AND MERL.FNLngID	    = $nLngID
                    LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK)   ON DOCHD.FTXthWhFrm     = WAHL.FTWahCode	AND DOCHD.FTBchCode     = WAHL.FTBchCode AND WAHL.FNLngID	        = $nLngID
                    LEFT JOIN TCNMWaHouse_L     WAHL_T  WITH (NOLOCK)   ON DOCHD.FTXthWhTo      = WAHL_T.FTWahCode	AND DOCHD.FTBchCode     = WAHL_T.FTBchCode AND WAHL_T.FNLngID 	    = $nLngID
                    LEFT JOIN TCNMRsn_L         REA     WITH (NOLOCK)   ON DOCHD.FTRsnCode      = REA.FTRsnCode	    AND REA.FNLngID	        = $nLngID
                    LEFT JOIN TCNMWaHouse       POS     WITH (NOLOCK)   ON DOCHD.FTXthWhTo      = POS.FTWahCode		AND POS.FTWahStaType    = '6'
                    LEFT JOIN TCNMPosLastNo		POSL    WITH (NOLOCK)   ON POS.FTWahRefCode     = POSL.FTPosCode
                    LEFT JOIN TCNMUsrDepart_L	DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	AND DPTL.FNLngID	= $nLngID
                    LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                    LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXthApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                    LEFT JOIN TCNMSpl_L         SPLL    WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPLL.FTSplCode	AND SPLL.FNLngID	= $nLngID

                    LEFT JOIN TCNMCst           Cus     WITH (NOLOCK)   ON DOCHD.FTCstCode		= Cus.FTCstCode	
                    LEFT JOIN TCNMCst_L         Cus_L   WITH (NOLOCK)   ON Cus_L.FTCstCode		= Cus.FTCstCode	    AND Cus_L.FNLngID = $nLngID

                    
                    WHERE 1=1 AND DOCHD.FTXthDocNo = '$tTWIDocNo' ";

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

    //Update DocNo In Doc Temp
    public function FSxMTWIAddUpdateDocNoToTemp($paDataWhere, $paTableAddUpdate)
    {
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', $paTableAddUpdate['tTableHD']);
        $this->db->update('TCNTDocDTTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into DTTemp
        $this->db->where('FTXshDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey', $paTableAddUpdate['tTableHD']);
        $this->db->update('TCNTDocDTFhnTmp', array(
            'FTXshDocNo'    => $paDataWhere['FTXthDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into HDDisTemp
        $this->db->where('FTXthDocNo', '');
        $this->db->where('FTSessionID', $paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDDisTmp', array(
            'FTXthDocNo'    => $paDataWhere['FTXthDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));
        return;
    }

    //??????????????????????????????????????? DTTmp To DT
    public function FSaMTWIMoveDtTmpToDt($paDataWhere, $paTableAddUpdate)
    {
        $tTWIBchCode     = $paDataWhere['FTBchCode'];
        $tTWIDocNo       = $paDataWhere['FTXthDocNo'];
        $tTWIDocKey      = $paTableAddUpdate['tTableHD'];
        $tTWISessionID   = $this->session->userdata('tSesSessionID');

        if (isset($tTWIDocNo) && !empty($tTWIDocNo)) {
            $this->db->where_in('FTXthDocNo', $tTWIDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO " . $paTableAddUpdate['tTableDT'] . " (
                    FTBchCode , FTXthDocNo , FNXtdSeqNo , FTPdtCode , FTXtdPdtName ,
                    FTPunCode , FTPunName , FCXtdFactor , FTXtdBarCode , FTXtdVatType ,
                    FTVatCode , FCXtdVatRate , FCXtdQty , FCXtdQtyAll , FCXtdSetPrice ,
                    FCXtdAmt , FCXtdVat , FCXtdVatable , FCXtdNet , FCXtdCostIn ,
                    FCXtdCostEx , FTXtdStaPrcStk , FNXtdPdtLevel , FTXtdPdtParent ,FCXtdQtySet ,
                    FTXtdPdtStaSet , FTXtdRmk , FTXtdBchRef , FTXtdDocNoRef , FDLastUpdOn ,
                    FTLastUpdBy ,FDCreateOn , FTCreateBy ) ";
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
                            DOCTMP.FCXtdQty,
                            DOCTMP.FCXtdQtyAll,
                            DOCTMP.FCXtdSetPrice,
                            DOCTMP.FCXtdAmt,
                            DOCTMP.FCXtdVat,
                            DOCTMP.FCXtdVatable,
                            DOCTMP.FCXtdNet,
                            DOCTMP.FCXtdCostIn,
                            DOCTMP.FCXtdCostEx,
                            DOCTMP.FTXtdStaPrcStk,
                            DOCTMP.FNXtdPdtLevel,
                            DOCTMP.FTXtdPdtParent,
                            DOCTMP.FCXtdQtySet,
                            DOCTMP.FTXtdPdtStaSet,
                            DOCTMP.FTXtdRmk,
                            NULL AS FTXtdBchRef,
                            DOCTMP.FTXtdDocNoRef,
                            DOCTMP.FDLastUpdOn,
                            DOCTMP.FTLastUpdBy,
                            DOCTMP.FDCreateOn,
                            DOCTMP.FTCreateBy
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tTWIBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tTWIDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tTWIDocKey'
                        AND DOCTMP.FTSessionID  = '$tTWISessionID'
                        AND DOCTMP.FTCabNameForTWXVD IS NULL
                        ORDER BY DOCTMP.FNXtdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);


        //?????????????????????????????????????????????????????? ref CN ??????????????????????????????????????????????????????????????????????????????????????????????????????
        $tSQLUpdateCN     =  "SELECT DOCTMP.FTXtdDocNoRef , DOCTMP.FTBchCode,DOCTMP.FTXthDocNo,DOCTMP.FTPdtCode,DOCTMP.FTCabNameForTWXVD
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK) WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tTWIBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tTWIDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tTWIDocKey'
                        AND DOCTMP.FTSessionID  = '$tTWISessionID'
                        AND DOCTMP.FTXtdDocNoRef IS NOT NULL
                        ORDER BY DOCTMP.FNXtdSeqNo ASC ";
        $oQuery     = $this->db->query($tSQLUpdateCN);
        $aDetailCN    = $oQuery->result_array();
        if ($oQuery->num_rows() > 0) {
            for ($i = 0; $i < FCNnHSizeOf($aDetailCN); $i++) {
                $aPackData = array(
                    'tDocNo'    => $aDetailCN[$i]['FTXtdDocNoRef'],
                    'tPdtCode'  => $aDetailCN[$i]['FTPdtCode']
                );
                $this->FSaMTWIUpdatePDTInCN($aPackData);
            }
        }


        //???????????????????????????????????? CN ?????????????????????????????????????????????????????? ???????????????????????????????????????????????????????????????
        $tSQLCN     =  "SELECT DOCTMP.FTBchCode,DOCTMP.FTXthDocNo,DOCTMP.FTPdtCode,DOCTMP.FTCabNameForTWXVD
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK) WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tTWIBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tTWIDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tTWIDocKey'
                        AND DOCTMP.FTSessionID  = '$tTWISessionID'
                        AND DOCTMP.FTCabNameForTWXVD = 'DELETE_TEMP'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC ";
        $oQuery     = $this->db->query($tSQLCN);
        $aDetail    = $oQuery->result_array();
        if ($oQuery->num_rows() > 0) {
            for ($i = 0; $i < FCNnHSizeOf($aDetail); $i++) {
                $aPackData = array(
                    'tDocNo'    => $aDetail[$i]['FTXthDocNo'],
                    'tPdtCode'  => $aDetail[$i]['FTPdtCode']
                );
                $this->FSvMCheckDocumentInCN('DEL', $aPackData);
            }
        }
        return;
    }



        //??????????????????????????????????????? DTFhnTmp To DTFhn
        public function FSaMTWIMoveDtTmpToDtFhn($paDataWhere, $paTableAddUpdate)
        {
            $tTWIBchCode     = $paDataWhere['FTBchCode'];
            $tTWIDocNo       = $paDataWhere['FTXthDocNo'];
            $tTWIDocKey      = $paTableAddUpdate['tTableHD'];
            $tTWISessionID   = $this->session->userdata('tSesSessionID');
    
            if (isset($tTWIDocNo) && !empty($tTWIDocNo)) {
                $this->db->where_in('FTXthDocNo', $tTWIDocNo);
                $this->db->delete($paTableAddUpdate['tTableDTFhn']);
            }
    
            $tSQL   = " INSERT INTO " . $paTableAddUpdate['tTableDTFhn'] . " (
                        FTBchCode , FTXthDocNo , FNXtdSeqNo , FTPdtCode , FTFhnRefCode ,
                        FCXtdQty ) ";
            $tSQL   .=  "   SELECT
                                DOCTMP.FTBchCode,
                                DOCTMP.FTXshDocNo,
                                (SELECT FNXtdSeqNo FROM " . $paTableAddUpdate['tTableDT'] . " AS DT WHERE DT.FTXthDocNo = DOCTMP.FTXshDocNo AND DT.FTPdtCode = DOCTMP.FTPdtCode ) AS FNXtdSeqNo,
                                DOCTMP.FTPdtCode,
                                DOCTMP.FTFhnRefCode,
                                DOCTMP.FCXtdQty
                            FROM TCNTDocDTFhnTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND DOCTMP.FTBchCode    = '$tTWIBchCode'
                            AND DOCTMP.FTXshDocNo   = '$tTWIDocNo'
                            AND DOCTMP.FTXthDocKey  = '$tTWIDocKey'
                            AND DOCTMP.FTSessionID  = '$tTWISessionID'
                            ORDER BY DOCTMP.FNXsdSeqNo ASC ";
            $oQuery = $this->db->query($tSQL);
    
    
     
        }

        
    //??????????????????????????????????????? DT To DTTmp
    public function FSxMTWIMoveDTToDTTemp($paDataWhere)
    {
        $tTWIDocNo       = $paDataWhere['FTXthDocNo'];
        $tTWIDocKey      = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo', $tTWIDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode, FTXthDocNo, FNXtdSeqNo , FTXthDocKey , FTPdtCode, 
                        FTXtdPdtName, FTPunCode, FTPunName, FCXtdFactor,
                        FTXtdBarCode, FTXtdVatType, FTVatCode, FCXtdVatRate,
                        FCXtdQty, FCXtdQtyAll, FCXtdSetPrice, FCXtdAmt,
                        FCXtdVat, FCXtdVatable, FCXtdNet, FCXtdCostIn,
                        FCXtdCostEx, FTXtdStaPrcStk, FNXtdPdtLevel, FTXtdPdtParent,
                        FCXtdQtySet, FTXtdPdtStaSet, FTXtdRmk, FTXtdBchRef,
                        FTXtdDocNoRef,FTTmpStatus, FTSessionID , FDLastUpdOn, FDCreateOn , FTLastUpdBy , FTCreateBy )
                    SELECT
                        DT.FTBchCode , 
                        DT.FTXthDocNo , 
                        DT.FNXtdSeqNo , 
                        CONVERT(VARCHAR,'" . $tTWIDocKey . "') AS FTXthDocKey,
                        DT.FTPdtCode , 
                        DT.FTXtdPdtName ,
                        DT.FTPunCode , 
                        DT.FTPunName , 
                        DT.FCXtdFactor , 
                        DT.FTXtdBarCode , 
                        DT.FTXtdVatType ,
                        DT.FTVatCode , 
                        DT.FCXtdVatRate , 
                        DT.FCXtdQty , 
                        DT.FCXtdQtyAll , 
                        DT.FCXtdSetPrice ,
                        DT.FCXtdAmt , 
                        DT.FCXtdVat , 
                        DT.FCXtdVatable , 
                        DT.FCXtdNet , 
                        DT.FCXtdCostIn ,
                        DT.FCXtdCostEx , 
                        DT.FTXtdStaPrcStk , 
                        DT.FNXtdPdtLevel , 
                        DT.FTXtdPdtParent , 
                        DT.FCXtdQtySet ,
                        DT.FTXtdPdtStaSet , 
                        DT.FTXtdRmk , 
                        DT.FTXtdBchRef ,
                        DT.FTXtdDocNoRef ,
                        PDT.FTPdtForSystem,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID ,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDLastUpdOn ,
                        CONVERT(DATETIME,'" . date('Y-m-d H:i:s') . "') AS FDCreateOn ,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTLastUpdBy ,
                        CONVERT(VARCHAR,'" . $this->session->userdata('tSesUsername') . "') AS FTCreateBy
                    FROM TCNTPdtTwiDT AS DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt AS PDT WITH (NOLOCK) ON DT.FTPDtCOde = PDT.FTPdtCode 
                    WHERE 1=1 AND DT.FTXthDocNo = '$tTWIDocNo'
                    ORDER BY DT.FNXtdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        return;
    }



      //??????????????????????????????????????? DT To DTTmp
      public function FSxMTWIMoveDTToDTFhnTemp($paDataWhere)
      {
          $tTWIDocNo       = $paDataWhere['FTXthDocNo'];
          $tTWIDocKey      = $paDataWhere['FTXthDocKey'];
  
          // Delect Document DTTemp By Doc No
          $this->db->where('FTXshDocNo', $tTWIDocNo);
          $this->db->delete('TCNTDocDTFhnTmp');
  
          $tSQL   = " INSERT INTO TCNTDocDTFhnTmp (
                          FTBchCode, FTXshDocNo, FNXsdSeqNo , FTXthDocKey , FTPdtCode, FTFhnRefCode , FCXtdQty ,FTSessionID
                        )
                      SELECT
                          DT.FTBchCode , 
                          DT.FTXthDocNo , 
                          DT.FNXtdSeqNo , 
                          CONVERT(VARCHAR,'" . $tTWIDocKey . "') AS FTXthDocKey,
                          DT.FTPdtCode , 
                          DT.FTFhnRefCode ,
                          DT.FCXtdQty,
                          CONVERT(VARCHAR,'" . $this->session->userdata('tSesSessionID') . "') AS FTSessionID 
                      FROM TCNTPdtTwiDTFhn AS DT WITH (NOLOCK)
                      WHERE 1=1 AND DT.FTXthDocNo = '$tTWIDocNo'
                      ORDER BY DT.FNXtdSeqNo ASC ";
          $oQuery = $this->db->query($tSQL);
          return;
      }
  
    //????????????????????????????????????
    public function FSvMTWICancel($paDataUpdate)
    {
        try {
            $this->db->set('FTXthStaDoc', 3);
            $this->db->where('FTXthDocNo', $paDataUpdate['FTXthDocNo']);
            $this->db->update('TCNTPdtTwiHD');
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

    //?????????????????????????????????????????????????????????
    public function FSaMTWIUpdateInlineDTTemp($paDataUpdateDT, $paDataWhere)
    {
        $this->db->set($paDataUpdateDT['tTWIFieldName'], $paDataUpdateDT['tTWIValue']);
        $this->db->where('FTSessionID', $paDataWhere['tTWISessionID']);
        $this->db->where('FTXthDocKey', $paDataWhere['tDocKey']);
        $this->db->where('FNXtdSeqNo', $paDataWhere['nTWISeqNo']);
        $this->db->where('FTXthDocNo', $paDataWhere['tTWIDocNo']);
        //$this->db->where('FTBchCode',$paDataWhere['tTWIBchCode']);
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


    
    //????????????????????????????????????????????????????????? ?????????????????????????????????????????????????????????
    public function FSaMTWIUpdateInlineDTFhnTemp($paDataUpdateDT)
    {
        
        $this->db->set('FCXtdQty', $paDataUpdateDT['tTWIValue']);
        $this->db->set('FCXtdQtyAll', 1 * $paDataUpdateDT['tTWIValue']);
        $this->db->set('FTXtdBarCode', $paDataUpdateDT['tPdtBarCode']);
        $this->db->where('FTSessionID', $paDataUpdateDT['tTWISessionID']);
        $this->db->where('FTXthDocKey', $paDataUpdateDT['tDocKey']);
        $this->db->where('FTPdtCode', $paDataUpdateDT['tPdtCode']);
        $this->db->where('FTXthDocNo', $paDataUpdateDT['tTWIDocNo']);
        //$this->db->where('FTBchCode',$paDataUpdateDT['tTWIBchCode']);
        $this->db->update('TCNTDocDTTmp');

        // echo $this->db->last_query();
        // die();
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


    //????????????????????????????????? ???????????????????????? CN ??????????????????????????????
    public function FSaMTWIGetPDTInCN($paDataWhere)
    {
        $tBCHCode       = $paDataWhere['tBCHCode'];
        $tSHPCode       = $paDataWhere['tSHPCode'];
        $tWAHCode       = $paDataWhere['tWAHCode'];
        $nLngID         = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT 
                            row_number() over (order by CN.FTXshDocNo) as rtRowID,
                            CN.FTXshDocNo , 
                            CN.FNXsdSeqNo , 
                            CN.FTStaPrcStk ,
                            HD.FTBchCode ,
                            BACL.FTBchName ,
                            HD.FTShpCode ,
                            SHPL.FTShpName ,
                            HD.FTWahCode ,
                            WAHL.FTWahName , 
                            DT.FTPdtCode ,
                            DT.FTXsdPdtName ,
                            DT.FTPunCode ,
                            DT.FTPunName , 
                            BAR.FTBarCode
                        FROM TVDTDTCN CN 
                        LEFT JOIN TARTSoHD HD			ON HD.FTXshDocNo = CN.FTXshDocNo
                        LEFT JOIN TARTSoDT DT			ON HD.FTXshDocNo = DT.FTXshDocNo AND DT.FNXsdSeqNo = CN.FNXsdSeqNo
                        LEFT JOIN TCNMBranch_L BACL		ON HD.FTBchCode = BACL.FTBchCode AND BACL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop_L SHPL		ON HD.FTShpCode = SHPL.FTShpCode AND HD.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMWahouse_L WAHL	ON HD.FTWahCode = WAHL.FTWahCode AND HD.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtBar BAR		ON BAR.FTPdtCode = DT.FTPdtCode AND DT.FTPunCode = BAR.FTPunCode 
                        WHERE CN.FTStaPrcStk IS NULL";

        if ($tBCHCode != null || $tBCHCode != '') {
            $tSQL .= " AND HD.FTBchCode = '$tBCHCode' ";
        }

        if ($tSHPCode != null || $tSHPCode != '') {
            $tSQL .= " AND HD.FTShpCode = '$tSHPCode' ";
        }

        if ($tWAHCode != null || $tWAHCode != '') {
            $tSQL .= " AND HD.FTWahCode = '$tWAHCode' ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
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

    //????????????????????????????????????????????????????????? CN ????????????????????????????????????????????????
    public function FSaMTWIUpdatePDTInCN($ptPackData)
    {
        $tDocument = $ptPackData['tDocNo'];
        $tPDTCode  = $ptPackData['tPdtCode'];

        $tSQL = " SELECT 
                    DT.FTXtdDocNoRef , 
                    DT.FTXthDocNo , 
                    DT.FTPdtCode ,
                    SODT.FNXsdSeqNo
                FROM TCNTDocDTTmp DT
                LEFT JOIN TARTSoDT SODT ON  DT.FTXtdDocNoRef = SODT.FTXshDocNo AND DT.FTPdtCode = SODT.FTPdtCode
                WHERE 
                SODT.FTXsdStaPrcStk = 2 AND
                DT.FTXtdDocNoRef = '$tDocument' AND
                DT.FTXtdDocNoRef IS NOT NULL AND
                DT.FTPdtCode = '$tPDTCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            for ($i = 0; $i < FCNnHSizeOf($aDetail); $i++) {
                $dLastUpdOn = date('Y-m-d H:i:s');
                $tLastUpdBy = $this->session->userdata('tSesUsername');
                $this->db->set('FTStaPrcStk', 1);
                $this->db->set('FDLastUpdOn', $dLastUpdOn);
                $this->db->set('FTLastUpdBy', $tLastUpdBy);
                $this->db->where('FTXshDocNo', $aDetail[$i]['FTXtdDocNoRef']);
                $this->db->where('FNXsdSeqNo', $aDetail[$i]['FNXsdSeqNo']);
                $this->db->update('TVDTDTCN');
            }
        }
    }

    //???????????????????????????????????????
    public function FSvMTWIApprove($paDataUpdate)
    {
        try {
            $dLastUpdOn = date('Y-m-d H:i:s');
            $tLastUpdBy = $this->session->userdata('tSesUsername');
            $this->db->set('FDLastUpdOn', $dLastUpdOn);
            $this->db->set('FTLastUpdBy', $tLastUpdBy);
            $this->db->set('FTXthStaPrcStk', 2);
            $this->db->set('FTXthStaApv', 1);
            $this->db->set('FTXthApvCode', $paDataUpdate['FTXthApvCode']);
            $this->db->where('FTXthDocNo', $paDataUpdate['FTXthDocNo']);
            $this->db->update('TCNTPdtTwiHD');
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

    //??????????????????????????????????????????????????? CN ?????????????????? CN ????????????????????????????????????????????????????????????????????????????????????
    public function FSvMCheckDocumentInCN($ptType, $paData)
    {
        if ($ptType == 'CANCEL') {
            $tDocument = $paData['FTXthDocNo'];
            $tPDTCode  = '';
            
            $tSQL = " SELECT 
                    DT.FTXtdDocNoRef , 
                    DT.FTXthDocNo , 
                    DT.FTPdtCode ,
                    SODT.FNXsdSeqNo
                FROM TCNTPdtTwiDT DT
                LEFT JOIN TARTSoDT SODT ON  DT.FTXtdDocNoRef = SODT.FTXshDocNo AND DT.FTPdtCode = SODT.FTPdtCode
                WHERE 
                SODT.FTXsdStaPrcStk = 2 AND
                DT.FTXthDocNo = '$tDocument' ";
        } else if ($ptType == 'DEL') {
            $tDocument = $paData['tDocNo'];
            $tPDTCode  = $paData['tPdtCode'];

            $tSQL = " SELECT 
                    DT.FTXtdDocNoRef , 
                    DT.FTXthDocNo , 
                    DT.FTPdtCode ,
                    SODT.FNXsdSeqNo
                FROM TCNTDocDTTmp DT
                LEFT JOIN TARTSoDT SODT ON  DT.FTXtdDocNoRef = SODT.FTXshDocNo AND DT.FTPdtCode = SODT.FTPdtCode
                WHERE 
                SODT.FTXsdStaPrcStk = 2 AND
                DT.FTXthDocNo = '$tDocument' AND
                DT.FTXtdDocNoRef IS NOT NULL ";
        }

        if ($tPDTCode != '') {
            $tSQL .= " AND DT.FTPdtCode = '$tPDTCode' ";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->result_array();
            for ($i = 0; $i < FCNnHSizeOf($aDetail); $i++) {
                $dLastUpdOn = date('Y-m-d H:i:s');
                $tLastUpdBy = $this->session->userdata('tSesUsername');
                $this->db->set('FTStaPrcStk', null);
                $this->db->set('FDLastUpdOn', $dLastUpdOn);
                $this->db->set('FTLastUpdBy', $tLastUpdBy);
                $this->db->where('FTXshDocNo', $aDetail[$i]['FTXtdDocNoRef']);
                $this->db->where('FNXsdSeqNo', $aDetail[$i]['FNXsdSeqNo']);
                $this->db->update('TVDTDTCN');
            }
        }
    }

    //Functionality: Get available column for showing in grid table
    //Parameters:  Function Parameter
    //Creator: 26/02/2018 kitpipat P'???????????????
    //Last Modified : 20/1/2020 ???????????????    (?????????????????????????????????????????????????????????????????????????????? session)
    //Return : 
    //Return Type: Array
    function FSoMTWIlableColumn($ptTable)
    {
        $tLangActive = $_SESSION['tLangEdit'];
        $ci = &get_instance();
        $ci->load->database();
        $tSQL = " SELECT SDT.* ,SDTL.FTShwNameUsr 
                FROM  TSysShwDT SDT 
                LEFT JOIN  TSysShwDT_L SDTL ON SDT.FTShwTblDT = SDTL.FTShwTblDT 
                AND SDT.FTShwFedShw = SDTL.FTShwFedShw  
                AND SDTL.FNLngID = '$tLangActive'
                WHERE SDT.FTShwTblDT = '$ptTable'
                AND SDT.FTShwFedStaUsed = 1
                AND SDT.FTShwFedShw != 'FCXtdAmt'
	            AND SDT.FTShwFedShw != 'FCXtdSetPrice'
                ORDER BY SDT.FTShwTblDT , SDT.FNShwSeq";

        $oQuery = $ci->db->query($tSQL);
        $aDataResult = $oQuery->result();
        return $aDataResult;
    }


   //Functionality: Get available column for showing in grid table
    //Parameters:  Function Parameter
    //Creator: 26/02/2018 kitpipat P'???????????????
    //Last Modified : 20/1/2020 ???????????????    (?????????????????????????????????????????????????????????????????????????????? session)
    //Return : 
    //Return Type: Array
    public function FSaMTWIGetBarCodeByRefCodePdtFhn($ptDataPdtFhn){
        $tPdtCode =  $ptDataPdtFhn['FTPdtCode']; 
        $tFhnRefCode =  $ptDataPdtFhn['FTFhnRefCode'];
        $tBarCodeQuery = $this->db->where('FTPdtCode',$tPdtCode)->where('FTFhnRefCode',$tFhnRefCode)->order_by('FDCreateOn','ASC')->limit(1)->get('TCNMPdtBar');
        if($tBarCodeQuery->num_rows()>0){
            $tBarCode = $tBarCodeQuery->row_array()['FTBarCode'];
        }else{
            $tBarCode = '';
        }
        return $tBarCode;
    }

    //??????????????????????????? HD
    public function FSaMTWIGetDataDocHDRef($paDataWhere)
    {

        $tTWIDocNo    = $paDataWhere['FTXthDocNo'];
        $nLngID       = $paDataWhere['FNLngID'];

        $tSQL = "SELECT
                        DOCHDR.FNXthShipAdd,
                        ADSL.FTAddName,
                        ADSL.FTAddTaxNo,
                        ADSL.FTAddRmk,
                        ADSL.FTAddCountry,
                        ADSL.FTAddVersion,
                        ADSL.FTAddV1No,
                        ADSL.FTAddV1Soi,
                        ADSL.FTAddV1Village,
                        ADSL.FTAddV1Road,
                        ADSL.FTAddV1SubDist,
                        ADSL.FTAddV1DstCode,
                        ADSL.FTAddV1PvnCode,
                        ADSL.FTAddV1PostCode,
                        ADSL.FTAddV2Desc1,
                        ADSL.FTAddV2Desc2,
                        ADSL.FTAddWebsite,
                        ADSL.FTAddLongitude,
                        ADSL.FTAddLatitude,
                        DOCHDR.FTXthCtrName,
                        DOCHDR.FTXthRefTnfID,
                        DOCHDR.FDXthTnfDate,
                        DOCHDR.FTXthRefVehID,
                        DOCHDR.FTXthQtyAndTypeUnit,
                        DOCHDR.FTViaCode,
                        PRVL.FTPvnName,
                        DSTL.FTDstName,
                        SDSTL.FTSudName
                        FROM
                        TCNTPdtTwiHDRef AS DOCHDR WITH (NOLOCK)
                        LEFT OUTER JOIN TCNMAddress_L ADSL WITH (NOLOCK) ON DOCHDR.FNXthShipAdd = ADSL.FNAddSeqNo AND ADSL.FNLngID = '$nLngID'
                        LEFT OUTER JOIN TCNMProvince_L PRVL WITH (NOLOCK) ON ADSL.FTAddV1PvnCode = PRVL.FTPvnCode AND PRVL.FNLngID = '$nLngID'
                        LEFT OUTER JOIN TCNMDistrict_L DSTL WITH (NOLOCK) ON ADSL.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = '$nLngID'
                        LEFT OUTER JOIN TCNMSubDistrict_L SDSTL WITH (NOLOCK) ON ADSL.FTAddV1SubDist = SDSTL.FTSudCode AND SDSTL.FNLngID = '$nLngID'
                        WHERE  DOCHDR.FTXthDocNo='$tTWIDocNo'
                ";

        $oQuery = $this->db->query($tSQL);

        // echo $this->db->last_query();
        // die();
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'raItems'   => array(),
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }
}
