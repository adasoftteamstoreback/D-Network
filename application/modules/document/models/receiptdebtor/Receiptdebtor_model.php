<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Receiptdebtor_model extends CI_Model {

    // Data List
    public function FSaMRCBDataList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID = $paData['FNLngID'];

        $tSQLPage1  = " SELECT c.* FROM( SELECT ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS FNRowID,* FROM ( ";
        $tSQLSelect = " SELECT  
                                BCHL.FTBchCode,
                                BCHL.FTBchName,
                                HD.FTXshDocNo,CONVERT(VARCHAR(10),HD.FDXshDocDate,121) AS FDXshDocDate,
                                CSTL.FTCstCode,
                                CSTL.FTCstName,
                                HD.FDCreateOn,
                                HD.FNXshStaDocAct,
                                HD.FTXshStaDoc
                    ";
        $tSQLCount  = " SELECT COUNT(HD.FTXshDocNo) AS FNXshRowAll ";
        $tSQLFrom   = " FROM TARTSpHD          HD   WITH(NOLOCK) 
                        INNER JOIN TCNMBranch   BCH  WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                        LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        INNER JOIN TCNMCst_L     CSTL WITH(NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                    ";

        // Parameters Search
        $tAgnCode = $paData['aSearchList']['tAgnCode'];
        if( $tAgnCode != "" ){
            $tSQLFrom .= " AND BCH.FTAgnCode = '".$tAgnCode."' ";
        }

        $tBchCode = $paData['aSearchList']['tBchCode'];
        if( $tBchCode != "" ){
            $tSQLFrom .= " AND BCH.FTBchCode = '".$tBchCode."' ";
        }

        $tDocNo = $paData['aSearchList']['tDocNo'];
        if( $tDocNo != "" ){
            $tSQLFrom .= " AND HD.FTXshDocNo LIKE '%".$tDocNo."%' ";
        }

        $tDocType = intval($paData['aSearchList']['tDocType']);
        if( $tDocType != "" ){
            $tSQLFrom .= " AND HD.FTXshStaDoc = ".$tDocType." ";
        }

        $tSQLPage2  = " ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        $tSQLData = $tSQLPage1.$tSQLSelect.$tSQLFrom.$tSQLPage2;

        $oQuery = $this->db->query($tSQLData);
        //echo $this->db->last_query();
        if ( $oQuery->num_rows() > 0 ){
            $tSQLRowAll     = $tSQLCount.$tSQLFrom;
            $oQueryRowAll   = $this->db->query($tSQLRowAll);
            $nFoundRow      = $oQueryRowAll->result_array()[0]['FNXshRowAll'];
            $nPageAll       = ceil($nFoundRow / $paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'nAllRow'          => $nFoundRow,
                'nCurrentPage'     => $paData['nPage'],
                'nAllPage'         => $nPageAll,
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'nAllRow'           => 0,
                'nCurrentPage'      => $paData['nPage'],
                "nAllPage"          => 0,
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล HD เอกสารการขาย
    // Create By: Napat(Jame) 05/07/2021
    public function FSaMRCBEventGetDataDocHD($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT
                        /* HD INFO */
                        HD.FTXshDocNo,
                        HD.FNXshStaDocAct,
                        CONVERT(VARCHAR(10),HD.FDXshDocDate,121) AS FDXshDocDate,
                        CONVERT(VARCHAR(8),HD.FDXshDocDate,114) AS FTXshDocTime,
                        AGNL.FTAgnCode,
                        AGNL.FTAgnName,
                        BCH.FTBchCode,
                        BCHL.FTBchName,
                        HD.FTUsrCode        AS FTUsrCreateCode,
                        USRL.FTUsrName      AS FTUsrCreateName,
                        HD.FCXshTotal,
                        HD.FCXshWht,
                        HD.FCXshAfWht,
                        HD.FCXshInterest,
                        HD.FCXshDisc,
                        HD.FCXshAfDisc,
                        HD.FCXshAmt,
                        HD.FCXshPay,
                        HD.FCXshChgCredit,
                        HD.FCXshGnd,
                        HD.FTXshGndText,
                        HD.FTXshRmk,
                        HD.FTXshCond,
                        HD.FNXshStaRef,
                        HD.FNXshDocPrint

                    FROM TARTSpHD               HD   WITH(NOLOCK) 
                    INNER JOIN TCNMBranch       BCH  WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                    INNER JOIN TCNMBranch_L     BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                    LEFT JOIN TCNMAgency_L      AGNL WITH(NOLOCK) ON BCH.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    LEFT JOIN TCNMUser_L        USRL WITH(NOLOCK) ON HD.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                    WHERE HD.FTXshDocNo = '$tDocNo' ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->row_array(),
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล ที่อยู่ลูกค้า
    public function FSaMRCBEventGetDataCstHD($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT TOP(1)
                        CST.FTCstCode,
                        CSTL.FTCstName,
                        CST.FTCstTel,
                        CST.FTCstEmail,
                        ADDL.FTAddVersion,
                        ADDL.FTAddV1No,
                        ADDL.FTAddV1Soi,
                        ADDL.FTAddV1Village,
                        ADDL.FTAddV1Road,
                        SUD_L.FTSudName,
                        ADDL.FTAddV1SubDist,
                        DST_L.FTDstName,
                        ADDL.FTAddV1DstCode,
                        PVN_L.FTPvnName,
                        ADDL.FTAddTaxNo,
                        ADDL.FTAddV1PvnCode,
                        ADDL.FTAddV1PostCode,
                        ADDL.FTAddV2Desc1,
                        ADDL.FTAddV2Desc2,
                        ADDL.FTAddStaBusiness,
                        ADDL.FTAddStaHQ,
                        ADDL.FTAddStaBchCode
                        
                    FROM
                        TCNMTaxAddress_L ADDL
                    LEFT JOIN TCNMCst CST WITH(NOLOCK) ON ADDL.FTCstCode = CST.FTCstCode
                    LEFT JOIN TCNMCst_L CSTL WITH(NOLOCK) ON CST.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                    LEFT JOIN TCNMProvince_L    PVN_L WITH(NOLOCK) ON ADDL.FTAddV1PvnCode = PVN_L.FTPvnCode AND PVN_L.FNLngID = $nLngID
                    LEFT JOIN TCNMDistrict_L    DST_L WITH(NOLOCK) ON ADDL.FTAddV1DstCode = DST_L.FTDstCode AND DST_L.FNLngID = $nLngID
                    LEFT JOIN TCNMSubDistrict_L SUD_L WITH(NOLOCK) ON ADDL.FTAddV1SubDist = SUD_L.FTSudCode AND SUD_L.FNLngID = $nLngID
                    WHERE
                        FTAddTaxNo = ( SELECT FTXshAddrTax FROM TARTSpHDCst WHERE FTXshDocNo = '$tDocNo' )
                ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->row_array(),
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล รายการชำระ
    public function FSaMRCBEventGetDataDT($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT 
                        FNXsdSeqNo,
                        FTXsdRefExt,
                        FNXsdInvType,
                        FTXsdInvNo,
                        CONVERT(VARCHAR(10),FDXsdInvDate,121) AS FDXsdInvDate,
                        FCXsdInvGrand,
                        FCXsdInvPaid,
                        FCXsdInvRem,
                        FCXsdInvPay
                    FROM
                        TARTSpDT DT
                        WHERE DT.FTXshDocNo = '$tDocNo' 
                ";
        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query();
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล รายละเอียดรายการชำระ
    public function FSaMRCBEventGeDocRC($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT 
                        RC.FNXrcSeqNo,
                        RC.FTRcvCode,
                        RC.FTRcvName,
                        RC.FTXrcRefNo1,
                        RC.FTXrcRefNo2,
                        RC.FTBnkName,
                        RC.FTXrcBnkBch,
                        CONVERT(VARCHAR(10),RC.FDXrcRefDate,121) AS FDXrcRefDate,
                        RC.FCXrcChgCreditPer,
                        RC.FCXrcChgCreditAmt,
                        RC.FCXrcNet
                    FROM
                        TARTSpRC RC
                        WHERE FTXshDocNo = '$tDocNo' 
                ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล เอกสารอ้างอิง
    public function FSaMRCBEventGetHDDocRef($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT 
                        FTXshDocNo,
                        FTXshRefType,
                        FTXshRefDocNo,
                        CONVERT(VARCHAR(10),FDXshRefDocDate,121) AS FDXshRefDocDate,
                        FTXshRefKey
                    FROM
                        TARTSpHDDocRef
                        WHERE FTXshDocNo = '$tDocNo' 
                ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'tCode'            => '1',
                'tDesc'            => 'success'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

}