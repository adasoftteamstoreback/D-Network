<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Abbsalerefund_model extends CI_Model {

    // Data List
    // Create By: Napat(Jame) 02/07/2021
    public function FSaMABBDataList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'], $paData['nPage']);
        $nLngID = $paData['FNLngID'];

        $tSQLPage1  = " SELECT c.* FROM( SELECT ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS FNRowID,* FROM ( ";
        $tSQLSelect = " SELECT BCHL.FTBchCode,BCHL.FTBchName,APPL.FTAppCode,APPL.FTAppName,HD.FTXshDocNo,CONVERT(VARCHAR(10),HD.FDXshDocDate,121) AS FDXshDocDate,
                               CSTL.FTCstCode,CSTL.FTCstName,CHNL.FTChnCode,CHNL.FTChnName,HD.FTXshStaApv,HD.FTXshApvCode,USRL.FTUsrName AS FTXshApvName,HD.FDCreateOn,
                               HD.FNXshStaDocAct, HD.FNXshDocType, HD.FTXshStaPrcDoc, HD.FTXshETaxStatus, HD.FTXshStaDoc ";
        $tSQLCount  = " SELECT COUNT(HD.FTXshDocNo) AS FNXshRowAll ";
        $tSQLFrom   = " FROM TPSTSalHD          HD   WITH(NOLOCK) 
                        INNER JOIN TCNMBranch   BCH  WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                        LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMCst_L     CSTL WITH(NOLOCK) ON HD.FTCstCode = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                        LEFT JOIN TSysApp_L     APPL WITH(NOLOCK) ON HD.FTAppCode = APPL.FTAppCode AND APPL.FNLngID = $nLngID
                        LEFT JOIN TCNMChannel_L CHNL WITH(NOLOCK) ON HD.FTChnCode = CHNL.FTChnCode AND CHNL.FNLngID = $nLngID
                        LEFT JOIN TCNMUser_L    USRL WITH(NOLOCK) ON HD.FTXshApvCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                        WHERE HD.FNXshDocType IN (1,9) ";

        // Parameters Search
        $tAgnCode = $paData['aSearchList']['tAgnCode'];
        if( $tAgnCode != "" ){
            $tSQLFrom .= " AND BCH.FTAgnCode = '".$tAgnCode."' ";
        }

        $tBchCode = $paData['aSearchList']['tBchCode'];
        if( $tBchCode != "" ){
            $tSQLFrom .= " AND BCH.FTBchCode = '".$tBchCode."' ";
        }

        $tUserLevel = $this->session->userdata("tSesUsrLevel");
        if (isset($tUserLevel) && !empty($tUserLevel) && $tUserLevel == "BCH") {
            // Check User Level BCH
            $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti");
            $tSQLFrom       .= " AND BCH.FTBchCode IN (" . $tUsrBchCode . ") ";
        }

        // $tStaDocAct = $paData['aSearchList']['tStaDocAct'];
        // switch($tStaDocAct){
        //     case "NULL":
        //         $tSQLFrom .= " AND ISNULL(HD.FNXshStaDocAct,'') = '' ";
        //         break;
        //     case "1":
        //         $tSQLFrom .= " AND HD.FNXshStaDocAct = '1' ";
        //         break;
        // }

        $tDocNo = $paData['aSearchList']['tDocNo'];
        if( $tDocNo != "" ){
            $tSQLFrom .= " AND HD.FTXshDocNo LIKE '%".$tDocNo."%' ";
        }

        // $dDocDate = $paData['aSearchList']['dDocDate'];
        // if( $dDocDate != "" ){
        //     $tSQLFrom .= " AND CONVERT(VARCHAR(10),HD.FDXshDocDate,121) = '".$dDocDate."' ";
        // }

        // $tChnCode = $paData['aSearchList']['tChnCode'];
        // if( $tChnCode != "" ){
        //     $tSQLFrom .= " AND CHNL.FTChnCode = '".$tChnCode."' ";
        // }

        // $tStaPrcDoc = $paData['aSearchList']['tStaPrcDoc'];
        // if( $tStaPrcDoc != "" ){
        //     if( $tStaPrcDoc != "5" ){
        //         $tSQLFrom .= " AND (HD.FTXshStaPrcDoc != '5' AND ISNULL(HD.FTXshStaPrcDoc,'') != '' ) ";
        //     }else{
        //         $tSQLFrom .= " AND HD.FTXshStaPrcDoc = '5' ";
        //     }
        // }

        $tDocType = intval($paData['aSearchList']['tDocType']);
        if( $tDocType != "" ){
            $tSQLFrom .= " AND HD.FNXshDocType = ".$tDocType." ";
        }

        $tSQLPage2  = " ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        $tSQLData = $tSQLPage1.$tSQLSelect.$tSQLFrom.$tSQLPage2;

        $oQuery = $this->db->query($tSQLData);
        // echo $this->db->last_query();
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
    public function FSaMABBEventGetDataDocHD($paData){
        $tDocNo = $paData['tDocNo'];
        $nLngID = $paData['nLngID'];

        $tSQL = "   SELECT
                        /* HD INFO */
                        HD.FTXshDocNo,
                        APPL.FTAppName,
                        HD.FNXshStaDocAct,
                        HD.FNXshDocType,
                        CONVERT(VARCHAR(10),HD.FDXshDocDate,121) AS FDXshDocDate,
                        CONVERT(VARCHAR(8),HD.FDXshDocDate,114) AS FTXshDocTime,
                        HD.FTXshStaApv,
                        HD.FTCreateBy,
                        APVL.FTUsrName      AS FTApvName,
                        AGNL.FTAgnCode,
                        AGNL.FTAgnName,
                        BCH.FTBchCode,
                        BCHL.FTBchName,
                        ISNULL(HD.FTXshDocVatFull,'') AS FTXshDocVatFull,
                        HD.FTXshVATInOrEx,
                        HD.FTXshStaPrcDoc,
                        HD.FTPosCode,
                        HD.FTChnCode,
                        CHNL.FTChnName,
                        HD.FTXshETaxStatus,
                        HD.FTUsrCode        AS FTUsrCreateCode,
                        USRL.FTUsrName      AS FTUsrCreateName,

                        /* CST INFO */
                        HD.FTCstCode,
	                    HCST.FTXshCstName   AS FTCstName,
                        /*CADR.FTAddVersion,CADR.FTAddV1No,CADR.FTAddV1Village,CADR.FTAddV1Road,CADR.FTAddV1Soi,CADR.FTAddV1PostCode,
                        PVN_L.FTPvnCode,
                        PVN_L.FTPvnName,
                        DST_L.FTDstName,
                        SUD_L.FTSudName,
                        CADR.FTAddV2Desc1,CADR.FTAddV2Desc2,
                        HCST.FTXshAddrTax   AS FTCstTaxNo,*/
                        HCST.FTXshCstTel    AS FTCstTel,
                        HCST.FTXshCstEmail  AS FTCstEmail,

                        /* DOC REF INFO */
                        HD.FTXshRefInt,
                        CONVERT(VARCHAR(10),HD.FDXshRefIntDate,121) AS FDXshRefIntDate,
                        HD.FTXshRefExt,
                        CONVERT(VARCHAR(10),HD.FDXshRefExtDate,121) AS FDXshRefExtDate,

                        HD.FTXshRmk,
                        ISNULL(HD.FTXshRefTax,'') AS FTXshRefTax,
                        ISNULL(HD.FTXshStaETax,'') AS FTXshStaETax,

                        /* TAX HD */
                        ISNULL(TAXHD.FTXshETaxStatus,'')    AS FTXshETaxStatusFullTax,
                        ISNULL(TAXHD.FTXshRefTax,'')        AS FTXshRefTaxFullTax,
                        ISNULL(TAXHD.FTXshStaETax,'')       AS FTXshStaETaxFullTax,
                        HD.FTXshStaDoc

                    FROM TPSTSalHD              HD   WITH(NOLOCK) 
                    INNER JOIN TCNMBranch       BCH  WITH(NOLOCK) ON HD.FTBchCode = BCH.FTBchCode
                    INNER JOIN TCNMBranch_L     BCHL WITH(NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                    LEFT JOIN TCNMAgency_L      AGNL WITH(NOLOCK) ON BCH.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = $nLngID
                    LEFT JOIN TPSTSalHDCst      HCST WITH(NOLOCK) ON HD.FTXshDocNo = HCST.FTXshDocNo
                    /*LEFT JOIN TCNMCstAddress_L  CADR WITH(NOLOCK) ON HD.FTCstCode = CADR.FTCstCode AND HCST.FNXshAddrShip = CADR.FNAddSeqNo
                    LEFT JOIN TCNMProvince_L    PVN_L WITH(NOLOCK) ON CADR.FTAddV1PvnCode = PVN_L.FTPvnCode AND PVN_L.FNLngID = $nLngID
                    LEFT JOIN TCNMDistrict_L    DST_L WITH(NOLOCK) ON CADR.FTAddV1DstCode = DST_L.FTDstCode AND DST_L.FNLngID = $nLngID
                    LEFT JOIN TCNMSubDistrict_L SUD_L WITH(NOLOCK) ON CADR.FTAddV1SubDist = SUD_L.FTSudCode AND SUD_L.FNLngID = $nLngID
                    LEFT JOIN TCNMTaxAddress_L  TADR WITH(NOLOCK) ON HD.FTCstCode = TADR.FTCstCode AND TADR.FTAddStaBusiness = '2' AND TADR.FNLngID = $nLngID
                    LEFT JOIN TCNMCst           CST  WITH(NOLOCK) ON HD.FTCstCode = CST.FTCstCode*/
                    LEFT JOIN TSysApp_L         APPL WITH(NOLOCK) ON HD.FTAppCode = APPL.FTAppCode AND APPL.FNLngID = $nLngID
                    LEFT JOIN TCNMUser_L        APVL WITH(NOLOCK) ON HD.FTXshApvCode = APVL.FTUsrCode AND APVL.FNLngID = $nLngID
                    LEFT JOIN TCNMUser_L        USRL WITH(NOLOCK) ON HD.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                    LEFT JOIN TCNMChannel_L     CHNL WITH(NOLOCK) ON HD.FTChnCode = CHNL.FTChnCode AND CHNL.FNLngID = $nLngID
                    LEFT JOIN TPSTTaxHD        TAXHD WITH(NOLOCK) ON HD.FTXshDocVatFull = TAXHD.FTXshDocNo
                    WHERE HD.FNXshDocType IN (1,9)
                        AND HD.FTXshDocNo = '".$tDocNo."' ";
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

    // ดึงข้อมูล RC เอกสารการขาย
    // Create By: Napat(Jame) 06/07/2021
    public function FSaMABBEventGetDataDocRC($paData){
        $tDocNo = $paData['tDocNo'];

        $tSQL = "   SELECT 
                        RC.FTRcvCode,
                        RC.FTRcvName,
                        RC.FTXrcRefNo1,
                        RC.FCXrcNet
                    FROM TPSTSalRC RC WITH(NOLOCK) 
                    WHERE RC.FTXshDocNo = '$tDocNo'
                    ORDER BY FNXrcSeqNo ASC ";

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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูลรายการสินค้า จากเอกสารการขาย
    // Create By: Napat(Jame) 05/07/2021
    public function FSaMABBEventGetDataDocDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSearch        = $paData['tSearch'];
        $nLngID         = $paData['nLngID'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT 
                        DT.FNXsdSeqNo,
                        DT.FTPdtCode,
                        PDTL.FTPdtName,
                        DT.FTXsdBarCode,
                        CASE WHEN ISNULL(TMP.FTSrnCode,'') = '' THEN DT.FTSrnCode ELSE TMP.FTSrnCode END AS FTSrnCode,
                        DT.FTPunCode,
                        PUNL.FTPunName,
                        DT.FCXsdQty,
                        DT.FCXsdSetPrice,
                        DT.FTXsdDisChgTxt,
                        DT.FCXsdNet
                    FROM TPSTSalDT                DT WITH(NOLOCK)
                    INNER JOIN TCNMPdt_L 	    PDTL WITH(NOLOCK) ON DT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                    INNER JOIN TCNMPdtUnit_L 	PUNL WITH(NOLOCK) ON DT.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = $nLngID 
                    LEFT JOIN TCNTDocDTTmp       TMP WITH(NOLOCK) ON DT.FTPdtCode = TMP.FTPdtCode AND TMP.FTXthDocKey = 'TPSTSalHD' AND TMP.FTSessionID = '$tSesSessionID' AND  DT.FTXshDocNo = TMP.FTXthDocNo
                    WHERE DT.FTXshDocNo = '$tDocNo' 
                    ";

        if( !empty($tSearch) && isset($tSearch) ){
            $tSQL .= " AND ( DT.FTPdtCode LIKE '%$tSearch%'
                          OR PDTL.FTPdtName LIKE '%$tSearch%' 
                          OR DT.FTXsdBarCode LIKE '%$tSearch%'
                          OR DT.FTPunCode LIKE '%$tSearch%' 
                          OR PUNL.FTPunName LIKE '%$tSearch%' 
                            )";
        }

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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูลรายการท้ายบิล
    // Create By: Napat(Jame) 06/07/2021
    public function FSaMABBEventGetDataEndBill($paData){
        $tDocNo = $paData['tDocNo'];

        $tSQL = "   SELECT 
                        HD.FCXshTotal,
                        HD.FTXshDisChgTxt,
                        HD.FCXshDis,
                        HD.FCXshChg,
                        (HD.FCXshToTalAfDisChgV + HD.FCXshToTalAfDisChgNV) AS FCXshNetAfHD,
                        HD.FCXshVat,
                        HD.FCXshGrand,
                        HD.FTXshGndText
                    FROM TPSTSalHD HD WITH(NOLOCK)
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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล vat ตามรายการสินค้า
    // Create By: Napat(Jame) 06/07/2021
    public function FSaMABBEventGetDataEndBillVat($paData){
        $tDocNo = $paData['tDocNo'];

        $tSQL = "   SELECT 
                        ISNULL(DT.FCXsdVatRate,0) AS FCXtdVatRate,
                        SUM(ISNULL(DT.FCXsdVat,0)) AS FCXtdVat
                    FROM TPSTSalDT DT WITH(NOLOCK) 
                    WHERE DT.FTXshDocNo = '$tDocNo'
                        AND DT.FTXsdVatType = '1' 
                        AND DT.FCXsdVatRate > 0
                    GROUP BY DT.FCXsdVatRate
                    ORDER BY DT.FCXsdVatRate ASC ";
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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // ตรวจสอบ Serial ที่ยังไม่ได้ระบุ
    // Create By: Napat(Jame) 06/07/2021
    public function FSnMABBEventCountSerial($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT 
                        COUNT(DT.FTPdtCode) AS FNSrnCount
                    FROM TPSTSalDT DT WITH(NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH(NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    LEFT JOIN TCNTDocDTTmp TMP WITH(NOLOCK) ON DT.FTPdtCode = TMP.FTPdtCode AND TMP.FTXthDocKey = 'TPSTSalHD' AND TMP.FTSessionID = '$tSesSessionID' AND DT.FTXshDocNo = TMP.FTXthDocNo
                    WHERE DT.FTXshDocNo = '$tDocNo'
                        AND ( TMP.FTSrnCode IS NULL AND DT.FTSrnCode = '' )
                        AND PDT.FTPdtSetOrSN IN ('3','4')";
        $oQuery = $this->db->query($tSQL);        
        return $oQuery->row_array()['FNSrnCount'];
    }

    // ดึงข้อมูลสินค้าที่ต้องระบุหมายเลขซีเรียล
    // Create By: Napat(Jame) 07/07/2021
    public function FSaMABBEventGetDataPdtSN($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        // $tSQL = "   SELECT 
        //                 DT.FTXsdBarCode,
        //                 DT.FTPdtCode,
        //                 DT.FTXsdPdtName
        //             FROM TPSTSalDT      DT WITH(NOLOCK)
        //             INNER JOIN TCNMPdt PDT WITH(NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
        //             LEFT JOIN TCNTDocDTTmp TMP WITH(NOLOCK) ON DT.FTPdtCode = TMP.FTPdtCode AND TMP.FTXthDocKey = 'TPSTSalHD' AND TMP.FTSessionID = '$tSesSessionID' AND DT.FTXshDocNo = TMP.FTXthDocNo
        //             WHERE DT.FTXshDocNo = '$tDocNo'
        //             AND ( TMP.FTSrnCode IS NULL AND DT.FTSrnCode = '' )
        //                 AND PDT.FTPdtSetOrSN IN ('3','4') ";
        $tSQL = "   SELECT
                        TMP.FNXtdSeqNo   AS FNXsdSeqNo,
                        TMP.FTPdtCode,
                        TMP.FTXtdPdtName AS FTXsdPdtName,
                        TMP.FTXtdBarCode AS FTXsdBarCode,
                        '' AS tOldPdtSN
                    FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTXthDocNo = '$tDocNo'
                    AND TMP.FTXthDocKey = 'TPSTSalHD' 
                    AND TMP.FTSessionID = '$tSesSessionID'
                    AND TMP.FTXtdStaPrcStk IN ('3','4')
                    AND ISNULL(TMP.FTSrnCode,'') = '' ";
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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // อัพเดทหมายเลขซีเรียล
    // Create By: Napat(Jame) 07/07/2021
    public function FSaMABBEventUpdatePdtSNTmp($paData){
        $this->db->delete('TCNTDocDTTmp', array(
            'FTBchCode'   => '',
            'FTXthDocKey' => $paData['tDocKey'],
            'FTSessionID' => $paData['FTSessionID'],
            'FTXthDocNo'  => $paData['tDocNo'],
            'FTPdtCode'   => $paData['tPdtCode']
        ));

        $this->db->insert('TCNTDocDTTmp', array(
            'FTBchCode'   => '',
            'FTXthDocKey' => $paData['tDocKey'],
            'FTSessionID' => $paData['FTSessionID'],
            'FTXthDocNo'  => $paData['tDocNo'],
            'FTPdtCode'   => $paData['tPdtCode'],
            'FTSrnCode'   => $paData['tSerialNo']
        ));
    }

    // เคลียร์ tmp
    // Create By: Napat(Jame) 07/07/2021
    public function FSaMABBEventClearPdtSNTmp($paData){
        $this->db->delete('TCNTDocDTTmp', array(
            'FTXthDocKey' => $paData['tDocKey'],
            'FTSessionID' => $paData['FTSessionID'],
        ));
    }

    // ย้ายจาก Temp ไปตารางจริง
    // Create By: Napat(Jame) 07/07/2021
    public function FSaMABBEventMoveTmpToDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $tDocVatFull    = $paData['tDocVatFull'];
        $tSesSessionID  = $paData['FTSessionID'];
        $tDocKey        = $paData['tDocKey'];

        $tSQL = "   UPDATE DT
                    SET DT.FTSrnCode = TMP.FTSrnCode
                    FROM TPSTSalDT DT
                    INNER JOIN TCNTDocDTTmp TMP WITH(NOLOCK) ON DT.FTXshDocNo = TMP.FTXthDocNo
                        AND DT.FTPdtCode = TMP.FTPdtCode
                        AND TMP.FTXthDocKey = '$tDocKey'
                        AND TMP.FTSessionID = '$tSesSessionID'
                    WHERE DT.FTXshDocNo = '$tDocNo' ";
        $this->db->query($tSQL);
        if ( $this->db->affected_rows() > 0 ) {
            if( !empty($tDocVatFull) ){
                $tSQL = "   UPDATE DT
                            SET DT.FTSrnCode = TMP.FTSrnCode
                            FROM TPSTTaxDT DT
                            INNER JOIN TCNTDocDTTmp TMP WITH(NOLOCK) ON TMP.FTXthDocNo = '$tDocNo'
                                AND DT.FTPdtCode = TMP.FTPdtCode
                                AND TMP.FTXthDocKey = '$tDocKey'
                                AND TMP.FTSessionID = '$tSesSessionID'
                            WHERE DT.FTXshDocNo = '$tDocVatFull' ";
                $this->db->query($tSQL);
                if ( $this->db->affected_rows() > 0 ) {
                    $aResult = array(
                        'tCode'            => '1',
                        'tDesc'            => 'Update TPSTSalDT and TPSTTaxDT Success'
                    );
                }else{
                    $aResult = array(
                        'tCode'            => '800',
                        'tDesc'            => 'Update TPSTSalDT and TPSTTaxDT Fail'
                    );
                }
            }else{
                $aResult = array(
                    'tCode'            => '1',
                    'tDesc'            => 'Update TPSTSalDT Success'
                );
            }
        }else{
            $aResult = array(
                'tCode'            => '800',
                'tDesc'            => 'Update TPSTSalDT Fail'
            );
        }
        return $aResult;
        
    }

    // ตรวจสอบ Config TSysFormatAPI_L ฟิวส์ FTApiFmtCode = '00006'
    // Create By: Napat(Jame) 12/07/2021
    public function FSaMABBEventChkFormatAPI(){
        $tSQL = " SELECT FTApiFmtCode FROM TSysFormatAPI_L WITH(NOLOCK) WHERE FTApiFmtCode = '00006' ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'tCode'            => '1',
                'tDesc'            => 'FOUND FORMAT API'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'NOT FOUND FORMAT API'
            );
        }
        return $aResult;
    }

    // ตรวจสอบก่อนว่า บิลขายมี FullTax ไหม (เลขที่ใบกำกับภาษีอย่างเต็ม)
    // Create By: Napat(Jame) 12/07/2021
    public function FSaMABBEventChkFullTax($paData){
        $tSQL = "   SELECT HD.FTXshDocVatFull FROM TPSTSalHD HD WITH(NOLOCK) 
                    WHERE HD.FTXshDocNo = '".$paData['tDocNo']."'
                      AND HD.FTXshDocVatFull IS NOT NULL ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'tDocVatFull'      => $oQuery->row_array()['FTXshDocVatFull'],
                'tCode'            => '1',
                'tDesc'            => 'found full tax'
            );
        } else {
            $aResult = array(
                'tCode'             => '800',
                'tDesc'             => 'not found full tax'
            );
        }
        return $aResult;
    }

    // ดึงข้อมูล MQ Config Interface
    // Create By: Napat(Jame) 12/07/2021
    public function FSaMABBEventGetConfigMQ(){
        $tSQL = "   SELECT *
                    FROM TLKMConfig WITH(NOLOCK)
                    WHERE TLKMConfig.FTCfgKey = 'Noti'
                    AND TLKMConfig.FTCfgSeq = '4' ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'tCode'            => '1',
                'tDesc'            => 'found full tax'
            );
        } else {
            $aResult = array(
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'not found full tax'
            );
        }
        return $aResult;
    }

    // ปรับสถานะเอกสารใบขาย
    // Create By: Napat(Jame) 12/07/2021
    public function FSaMABBEventApproved($paData){
        $this->db->set('FTXshStaApv',1);
        $this->db->where('FTXshDocNo',$paData['tDocNo']);
        $this->db->update('TPSTSalHD');
    }

    public function FSaMABBGetDataSNByPdt($paData){
        $tDocNo         = $paData['tTaxDocNo'];
        $nSeqNo         = $paData['nSeqNo'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT 
                        TMP.FNXtdSeqNo AS FNXsdSeqNo,
                        TMP.FTPdtCode,
                        TMP.FTXtdPdtName AS FTPdtName,
                        TMP.FTXtdBarCode AS FTXsdBarCode,
                        TMP.FTPunCode,
                        TMP.FTPunName,
                        TMP.FCXtdQty AS FCXsdQty,
                        TMP.FCXtdSetPrice AS FCXsdSetPrice,
                        TMP.FTXtdDisChgTxt AS FTXsdDisChgTxt,
                        TMP.FCXtdNet AS FCXsdNet,
                        TMP.FTSrnCode AS FTPdtSerial,
                        TMP.FTXtdRmk,
                        TMP.FTXtdDocNoRef AS FTPdtBatchID
                    FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTXthDocNo = '$tDocNo'
                        AND TMP.FTXthDocKey = 'TPSTSalHD'
                        AND TMP.FTSessionID = '$tSesSessionID'
                        AND TMP.FNXtdSeqNo = '$nSeqNo'
                        AND ISNULL(TMP.FTSrnCode,'') != ''
                    ORDER BY TMP.FTSrnCode ASC
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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMABBEventGetDataDocDTTmp($paData){
        $tDocNo         = $paData['tDocNo'];
        $tSearch        = $paData['tSearch'];
        // $nLngID         = $paData['nLngID'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT DISTINCT
                        TMP.FNXtdSeqNo AS FNXsdSeqNo,
                        TMP.FTPdtCode,
                        TMP.FTXtdPdtName AS FTPdtName,
                        TMP.FTXtdBarCode AS FTXsdBarCode,
                        TMP.FTPunCode,
                        TMP.FTPunName,
                        TMP.FCXtdQty AS FCXsdQty,
                        TMP.FCXtdSetPrice AS FCXsdSetPrice,
                        TMP.FTXtdDisChgTxt AS FTXsdDisChgTxt,
                        TMP.FCXtdNet AS FCXsdNet,
                        TMP.FTXtdStaPrcStk
                    FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTXthDocNo = '$tDocNo'
                        AND TMP.FTXthDocKey = 'TPSTSalHD'
                        AND TMP.FTSessionID = '$tSesSessionID' 
                ";

        if( !empty($tSearch) && isset($tSearch) ){
            $tSQL .= " AND ( TMP.FTPdtCode LIKE '%$tSearch%'
                          OR TMP.FTPdtName LIKE '%$tSearch%' 
                          OR TMP.FTXtdBarCode LIKE '%$tSearch%'
                          OR TMP.FTPunCode LIKE '%$tSearch%' 
                          OR TMP.FTPunName LIKE '%$tSearch%' 
                            )";
        }
        // echo $tSQL;

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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMABBEventUpdPdtSNTmp($paData,$paWhereTemp){
        $tDocNo         = $paData['tDocNo'];
        $tDocKey        = $paWhereTemp['tDocKey'];
        $tSessionID     = $paWhereTemp['FTSessionID'];

        $tSQL = "   UPDATE TMP
                    SET 
                        TMP.FTSrnCode = DTSN.FTPdtSerial,
                        TMP.FTXtdRmk  = DTSN.FTPdtSerial, /* เก็บ Old S/N */
                        TMP.FTXtdDocNoRef = DTSN.FTPdtBatchID
                    FROM TCNTDocDTTmp TMP
                    INNER JOIN (
                        SELECT 
                            ROW_NUMBER() OVER( PARTITION BY FNXsdSeqNo ORDER BY FNXsdSeqNo ASC ) AS FTPdtParent,
                            FTXshDocNo,
                            FNXsdSeqNo,
                            FTPdtSerial,
                            FTPdtBatchID
                        FROM TPSTSalDTSN WITH(NOLOCK) 
                        WHERE FTXshDocNo = '$tDocNo'
                    ) DTSN ON DTSN.FNXsdSeqNo = TMP.FNXtdSeqNo AND CONVERT(varchar, DTSN.FTPdtParent) = CONVERT(varchar, TMP.FTXtdPdtParent)
                    WHERE TMP.FTXthDocNo = '$tDocNo'
                      AND TMP.FTXthDocKey = '$tDocKey'
                      AND TMP.FTSessionID = '$tSessionID'
                ";
        $this->db->query($tSQL);
        // echo $this->db->last_query();exit;
    }

    public function FSaMABBEventInsertToTmp($aDataDTTmp){
        // $this->db->insert('TCNTDocDTTmp',$aDataDTTmp);
        $this->db->insert_batch('TCNTDocDTTmp', $aDataDTTmp);
    }

    public function FSaMABBEventGetDocDT($paData){
        $tDocNo         = $paData['tDocNo'];
        $nLngID         = $paData['nLngID'];
        $tSessionID     = $this->session->userdata('tSesSessionID');

        $tSQL = "   SELECT 
                        DT.FTBchCode,
                        DT.FTXshDocNo AS FTXthDocNo,
                        DT.FNXsdSeqNo AS FNXtdSeqNo,
                        'TPSTSalHD' AS FTXthDocKey,
                        DT.FTPdtCode,
                        PDTL.FTPdtName AS FTXtdPdtName,
                        DT.FTXsdBarCode AS FTXtdBarCode,
                        DT.FTPunCode,
                        PUNL.FTPunName,
                        DT.FCXsdQty AS FCXtdQty,
                        CONVERT(INT,DT.FCXsdQty) AS FTXtdPdtParent,
                        DT.FCXsdSetPrice AS FCXtdSetPrice,
                        '' AS FTXtdDisChgTxt,
                        DT.FCXsdNet AS FCXtdNet,
                        '$tSessionID' AS FTSessionID,
                        PDT.FTPdtSetOrSN AS FTXtdStaPrcStk
                    FROM TPSTSalDT                DT WITH(NOLOCK) 
                    INNER JOIN TCNMPdt 	         PDT WITH(NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    INNER JOIN TCNMPdt_L 	    PDTL WITH(NOLOCK) ON DT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                    INNER JOIN TCNMPdtUnit_L 	PUNL WITH(NOLOCK) ON DT.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = $nLngID
                    WHERE DT.FTXshDocNo = '$tDocNo' 
                    ORDER BY DT.FNXsdSeqNo ASC
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
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMABBEventGetDocDTDis($paData){
        $tDocNo         = $paData['tDocNo'];
        $nLngID         = $paData['nLngID'];
        $tSQL = "   SELECT 
                        FTXshDocNo,FNXsdSeqNo,
                        (CASE FTXddDisChgType
                            WHEN '1' THEN ISNULL(FCXddValue,0)*-1
                            WHEN '2' THEN ISNULL(FCXddValue,0)*-1
                            WHEN '3' THEN ISNULL(FCXddValue,0)
                            WHEN '4' THEN ISNULL(FCXddValue,0)
                            END
                        ) AS FCXddValue
                    FROM TPSTSalDTDis  WITH (NoLock) 
                    WHERE FTXshDocNo = '$tDocNo'
                    AND FNXddStaDis = 1
                    ORDER BY FDXddDateIns ASC ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult = array(
                'aItems'           => $oQuery->result_array(),
                'tCode'            => '1',
                'tDesc'            => 'found'
            );
        } else {
            $aResult = array(
                'aItems'            => array(),
                'tCode'             => '800',
                'tDesc'             => 'data not found'
            );
        }
        return $aResult;
    }

    // Create By: Napat(Jame) 2021-10-20
    // Fitauto ใช้อ้างอิงเอกสารแบบมัลติ
    public function FSaMABBEventGetHDDocRef($paData){
        $tDocNo = $paData['tDocNo'];
        $tSQL   = " SELECT 
                        FTXshRefDocNo,
                        FTXshRefType,
                        FDXshRefDocDate
                    FROM TPSTSalHDDocRef WITH(NOLOCK) 
                    WHERE FTXshDocNo = '$tDocNo'
                    ORDER BY FTXshRefType ASC, FDXshRefDocDate ASC
                  ";
        $oQuery = $this->db->query($tSQL);
        if( $oQuery->num_rows() > 0 ){
            $aResult    = array(
                'aItems'       => $oQuery->result_array(),
                'tCode'        => '1',
                'tDesc'        => 'found'
            );
        }else{
            $aResult = array(
                'aItems'       => array(),
                'tCode'        => '800',
                'tDesc'        => 'data not found'
            );
        }
        return $aResult;
    }

}