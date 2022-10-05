<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep1PmtPdtDt extends CI_Model
{
    /**
     * Functionality : Get PmtPdtDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Data List PmtPdtDt
     * Return Type : Array
     */
    public function FSaMGetPmtPdtDtInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmp = $paParams['tPmtGroupNameTmp'];
        $tPmtGroupTypeTmp = $paParams['tPmtGroupTypeTmp'];
        $tPmtGroupListTypeTmp = $paParams['tPmtGroupListTypeTmp'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FNPmdSeq ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTPmhDocNo,
                        TMP.FNPmdSeq,
                        TMP.FTPmdStaType,
                        TMP.FTPmdGrpName,
                        TMP.FTPmdRefCode,
                        TMP.FTPmdRefName,
                        TMP.FTPmdSubRef,
                        TMP.FTPmdSubRefName,
                        TMP.FTPmdBarCode,
                        TMP.FTXtdRmk,
                        TMP.FTPmdStaListType,
                        TMP.FTSessionID,
                        PDT.FTPdtStaLot,
                        STUFF(
                            ( SELECT DISTINCT ' , ' + TLOT.FTLotBatchNo FROM TCNTPdtPmtDTLOT_Tmp AS LOT 
                            LEFT JOIN TCNMLot TLOT ON LOT.FTPmdLotNo = TLOT.FTLotNo
                            WHERE TMP.FNPmdSeq = LOT.FNPmdSeq AND LOT.FTSessionID = '$tUserSessionID' FOR XML PATH ( '' ) 
                            ),
                            1,
                            1,
                            '' 
                        ) AS LotNumber,
                        STUFF(
                            ( SELECT DISTINCT ',' + PDTLOT.FTLotNo FROM TCNMPdtLot AS PDTLOT WHERE TMP.FTPmdRefCode = PDTLOT.FTPdtCode FOR XML PATH ( '' ) ),
                            1,
                            1,
                            '' 
                        ) AS CheckLOT 	
                    FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK)
                    LEFT JOIN TCNMPdt PDT ON PDT.FTPdtCode = TMP.FTPmdRefCode
                    WHERE TMP.FTSessionID = '$tUserSessionID'
                    AND TMP.FTPmdGrpName = '$tPmtGroupNameTmp'
                    AND TMP.FTPmdStaType = '$tPmtGroupTypeTmp'
                    AND TMP.FTPmdStaListType = '$tPmtGroupListTypeTmp'
        ";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        // echo $tSQL;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPmtPdtDtInTmpPageAll($paParams);
            $nPageAll = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

        /**
     * Functionality : Get PmtPdtDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Data List PmtPdtDt
     * Return Type : Array
     */
    public function FSaMGetPmtPriDtInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmp = $paParams['tPmtGroupNameTmp'];
        $tPmtGroupTypeTmp = $paParams['tPmtGroupTypeTmp'];
        $tPmtGroupListTypeTmp = $paParams['tPmtGroupListTypeTmp'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        // $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FNPmdSeq ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTPmhDocNo,
                        TMP.FNPmdSeq,
                        TMP.FTPmdStaType,
                        TMP.FTPmdGrpName,
                        TMP.FTPmdRefCode,
                        PRI.FDXphDocDate AS FTPmdRefName,
                        TMP.FTPmdSubRef,
                        TMP.FTPmdSubRefName,
                        TMP.FTPmdBarCode,
                        TMP.FTXtdRmk,
                        TMP.FTPmdStaListType,
                        TMP.FTSessionID,
                        PDT.FTPdtStaLot
                    FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK)
                    LEFT JOIN TCNMPdt PDT ON PDT.FTPdtCode = TMP.FTPmdRefCode
                    LEFT JOIN TCNTPdtAdjPriHD PRI ON PRI.FTXphDocNo = TMP.FTPmdRefCode
                    WHERE TMP.FTSessionID = '$tUserSessionID'
                    AND TMP.FTPmdGrpName = '$tPmtGroupNameTmp'
                    AND TMP.FTPmdStaType = '$tPmtGroupTypeTmp'
                    AND TMP.FTPmdStaListType = '$tPmtGroupListTypeTmp'
        ";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        // echo $tSQL;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPmtPdtDtInTmpPageAll($paParams);
            $nPageAll = ceil($nFoundRow / $paParams['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paParams['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paParams['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Count PmtPdtDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count PmtPdtDt
     * Return Type : Number
     */
    public function FSnMTFWGetPmtPdtDtInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmp = $paParams['tPmtGroupNameTmp'];
        $tPmtGroupTypeTmp = $paParams['tPmtGroupTypeTmp'];
        $tPmtGroupListTypeTmp = $paParams['tPmtGroupListTypeTmp'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
            AND TMP.FTPmdGrpName = '$tPmtGroupNameTmp'
            AND TMP.FTPmdStaType = '$tPmtGroupTypeTmp'
            AND TMP.FTPmdStaListType = '$tPmtGroupListTypeTmp'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Get All PmtPdtDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count PmtPdtDt
     * Return Type : Number
     */
    public function FSaMGetPmtPdtDtInAllTmp($paParams = []){
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                *
            FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
            AND TMP.FTPmdRefCode IS NOT NULL
            AND TMP.FTPmdRefCode <> ''
            /* AND TMP.FTPmdGrpName = '$tPmtGroupNameTmpOld' */
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array();
    }

    /**
     * Functionality : Insert PmtPdtDt to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtPdtDtToTemp($paParams = [])
    {
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];
        $tPdtCode = $paParams['tPdtCode'];
        $tPunCode = $paParams['tPunCode'];
        $tBarCode = $paParams['tBarCode'];


        $tSQLCheck = "
        SELECT 
        FNPmdSeq,
        FTPmdRefCode
        FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
        WHERE TMP.FTSessionID = '$tUserSessionID'
        AND TMP.FTPmdSubRef = '$tPunCode'
        AND TMP.FTPmdBarCode = '$tBarCode'
        ";
        $oQueryCheck = $this->db->query($tSQLCheck);
        $aResult = $oQueryCheck->result();

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Already product.',
            'nSeqno' => '',
        ];
        
        if(count($aResult) == 0){
            
            $this->db->set('FTBchCode', $tBchCodeLogin);
            $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
            $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0) + 1) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
            $this->db->set('FTPmdStaType', $paParams['tPmtGroupTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
            $this->db->set('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
            $this->db->set('FTPmdGrpName', $tPmtGroupNameTmpOld); // ชื่อกลุ่มจัดรายการ
            $this->db->set('FTPmdRefCode', $paParams['tPdtCode']); // รหัสสินค้า
            $this->db->set('FTPmdRefName', $paParams['tPdtName']); // ชื่อรหัสสินค้า
            $this->db->set('FTPmdSubRef', $paParams['tPunCode']); // รหัสหน่วย
            $this->db->set('FTPmdSubRefName', $paParams['tPunName']); // ชื่อรหัสหน่วย
            $this->db->set('FTPmdBarCode', $paParams['tBarCode']); // รหัสบาร์โค้ด ณ. บันทึก
            $this->db->set('FDCreateOn', $tUserSessionDate);
            $this->db->set('FTSessionID', $tUserSessionID);
            $this->db->insert('TCNTPdtPmtDT_Tmp');

            $aStatus = [
                'rtCode' => '905',
                'rtDesc' => 'Insert PmtPdtDt Fail.',
                'nSeqno' => '',
            ];

            if ($this->db->affected_rows() > 0) {
                $tSQL = "
                SELECT 
                FNPmdSeq,
                FTPmdRefCode
                FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
                WHERE TMP.FTSessionID = '$tUserSessionID'
                AND TMP.FTPmdRefCode = '$tPdtCode'
                AND TMP.FTPmdGrpName = '$tPmtGroupNameTmpOld'
                ORDER BY FNPmdSeq DESC
                ";

                $oQuery = $this->db->query($tSQL);
                $aPdtSeq = $oQuery->result();

                // print_r($aPdtSeq);

                $aStatus['rtCode'] = '1';
                $aStatus['rtDesc'] = 'Insert PmtPdtDt Success';
                $aStatus['nSeqno'] = $aPdtSeq[0]->FNPmdSeq;
            }
        }
        return $aStatus;
    }

        /**
     * Functionality : Insert PmtPdtDt to Temp With Error
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtPdtDtToTempErrorCase($paParams = [])
    {
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];
        $tPdtCode = $paParams['tPdtCode'];
        $tPunCode = $paParams['tPunCode'];
        $tBarCode = $paParams['tBarCode'];

        $tRemake = $this->FSaMPmtCheckLogError($paParams);

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0) + 1) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
        $this->db->set('FTPmdStaType', $paParams['tPmtGroupTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdGrpName', $tPmtGroupNameTmpOld); // ชื่อกลุ่มจัดรายการ
        $this->db->set('FTPmdRefCode', $paParams['tPdtCode']); // รหัสสินค้า
        $this->db->set('FTPmdSubRef', $paParams['tPunCode']); // รหัสหน่วย
        $this->db->set('FTPmdBarCode', $paParams['tBarCode']); // รหัสบาร์โค้ด ณ. บันทึก
        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTXtdRmk', $tRemake);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtDT_Tmp');

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert PmtPdtDt Fail.',
            'nSeqno' => '',
        ];
    }

    /**
     * Functionality : Check Error Code
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtCheckLogError($paParams = [])
    {
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];
        $tPdtCode = $paParams['tPdtCode'];
        $tPunCode = $paParams['tPunCode'];
        $tBarCode = $paParams['tBarCode'];

        $tSQLCheck = "
        SELECT 
        PDT.FTPdtCode
        FROM TCNMPdt PDT WITH(NOLOCK) 
        WHERE PDT.FTPdtCode = ".$this->db->escape($tPdtCode)."
        ";
        $oQueryCheck = $this->db->query($tSQLCheck);
        $aResult = $oQueryCheck->result();
        if(count($aResult) == 0){
            return 'รหัสสินค้าไม่ถูกต้อง';
        }

        $tSQLCheck = "
        SELECT 
        PAC.FTPdtCode
        FROM TCNMPdtPackSize PAC WITH(NOLOCK) 
        WHERE PAC.FTPdtCode = ".$this->db->escape($tPdtCode)."
        AND PAC.FTPunCode = ".$this->db->escape($tPunCode)."
        ";
        $oQueryCheck = $this->db->query($tSQLCheck);
        $aResult = $oQueryCheck->result();
        if(count($aResult) == 0){
            return 'หน่วยสินค้าไม่ถูกต้อง';
        }

        $tSQLCheck = "
        SELECT 
        BAR.FTPdtCode
        FROM TCNMPdtBar BAR WITH(NOLOCK) 
        WHERE BAR.FTPdtCode = ".$this->db->escape($tPdtCode)."
        AND BAR.FTPunCode = ".$this->db->escape($tPunCode)."
        AND BAR.FTBarCode = ".$this->db->escape($tBarCode)."
        ";
        $oQueryCheck = $this->db->query($tSQLCheck);
        $aResult = $oQueryCheck->result();
        if(count($aResult) == 0){
            return 'บาร์โค้ดไม่ถูกต้อง';
        }

    }


        /**
     * Functionality : Insert PmtPdtDt to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtInsertLotExcel($paParams = [])
    {
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];

        $tSQLGetPmo = "	SELECT
        PDT.FTPmoCode,
        PDT.FTPbnCode
        FROM
        TCNMPdt PDT
        WHERE
        PDT.FTpdtcode = ".$this->db->escape($paParams['tPdtCode'])." ";
        $oQueryPMO = $this->db->query($tSQLGetPmo);
        $aGetPMO      = $oQueryPMO->result_array();
        $tPmoCode     = $aGetPMO[0]['FTPmoCode'];
        $tPbnCode     = $aGetPMO[0]['FTPbnCode'];
        if($tPmoCode != ''){
            $tWhere = "AND LOT.FTPmoCode = ".$this->db->escape($tPmoCode)."";
            $tWhere2 = "AND PDT.FTPmoCode = LOT.FTPmoCode";
        }else{
            $tWhere = "";
            $tWhere2 = "";
        }


        $tSQLCheckHaveLot = "SELECT DISTINCT
        LOT.FTLotNo
        FROM TCNMPdt PDT 
        LEFT JOIN TCNMPdtLot LOT ON PDT.FTPbnCode = LOT.FTPbnCode $tWhere2
        LEFT JOIN TCNMLot LOTT ON LOT.FTLotNo = LOTT.FTLotNo
        WHERE LOT.FTPbnCode = ".$this->db->escape($tPbnCode)."
        AND LOT.FTLotNo != '' 
        AND LOTT.FTLotYear = ".$this->db->escape($paParams['tYear'])."
        AND PDT.FTpdtcode = ".$this->db->escape($paParams['tPdtCode'])." $tWhere";

        $oQueryHaveLot = $this->db->query($tSQLCheckHaveLot);
        $aGetAllLot      = $oQueryHaveLot->result_array();

        foreach($aGetAllLot as $nKey => $aValue){
            if($aValue['FTLotNo'] == $paParams['tLotNo']){
                if($paParams['tLotNo'] != ''){ 
                    $this->db->set('FTBchCode', $paParams['tBchCode']);
                    $this->db->set('FTPmhDocNo', $paParams['tDocNoLot']);
                    $this->db->set('FTPmdRefCode', $paParams['tPdtCode']);
                    $this->db->set('FTPmdLotNo', $paParams['tLotNo']);
                    $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0)) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
                    $this->db->set('FDCreateOn', $tUserSessionDate);
                    $this->db->set('FTSessionID', $tUserSessionID);
                    $this->db->insert('TCNTPdtPmtDTLot_Tmp');
                }
        
                $aStatus = [
                    'rtCode' => '905',
                    'rtDesc' => 'Insert PmtPdtDt Fail.',
                    'nSeqno' => '',
                ];
            }
        }
        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert PmtPdtDt Fail.',
            'nSeqno' => '',
        ];

        return $aStatus;
    }

    /**
     * Functionality : Show Lot Detail
     * Parameters : -
     * Creator : 03/11/2021 Off
     * Last Modified : -
     */
    public function FSaMPmtPdtDtGetLotDetail($paDataWhere){
        $tPdtCode       = $paDataWhere['tPdtCode'];
        $nLangEdit = $this->session->userdata("tLangEdit");

        $tSQLGetPmo = "	SELECT
        PDT.FTPmoCode,
        PDT.FTPbnCode
        FROM
        TCNMPdt PDT
        WHERE
        PDT.FTpdtcode = ".$this->db->escape($tPdtCode)." ";
        $oQueryPMO = $this->db->query($tSQLGetPmo);
        $aGetPMO      = $oQueryPMO->result_array();
        $tPmoCode     = $aGetPMO[0]['FTPmoCode'];
        $tPbnCode     = $aGetPMO[0]['FTPbnCode'];
        if($tPmoCode != ''){
            $tWhere = "AND LOT.FTPmoCode = ".$this->db->escape($tPmoCode)."";
            $tWhere2 = "AND PDT.FTPmoCode = LOT.FTPmoCode";
        }else{
            $tWhere = "";
            $tWhere2 = "";
        }

        $tSQL = "SELECT DISTINCT
            PDT.FTPdtCode,
            LOT.FTLotNo,
            PDTL.FTPdtName,
            TLOT.FTLotBatchNo
        FROM TCNMPdt PDT 
        LEFT JOIN TCNMPdtLot LOT ON PDT.FTPbnCode = LOT.FTPbnCode $tWhere2
        LEFT JOIN TCNMPdt_L PDTL ON PDT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = ".$this->db->escape($nLangEdit)."
        LEFT JOIN TCNMLot TLOT ON LOT.FTLotNo = TLOT.FTLotNo
        WHERE LOT.FTPbnCode = ".$this->db->escape($tPbnCode)."
        AND LOT.FTLotNo != '' 
        AND PDT.FTpdtcode = ".$this->db->escape($tPdtCode)."
        $tWhere";

        // $tSQL           = "
        //     SELECT
        //         LOT.FTPdtCode,
        //         LOT.FTLotNo,
        //         PDTL.FTPdtName,
        //         TLOT.FTLotBatchNo
        //     FROM TCNMPdtLot LOT WITH(NOLOCK)
        //     LEFT JOIN TCNMPdt_L PDTL ON LOT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = '".$nLangEdit."'
        //     LEFT JOIN TCNMLot TLOT ON LOT.FTLotNo = TLOT.FTLotNo
        //     WHERE LOT.FTPdtCode = '".$tPdtCode."'
        // ";
        $oQuery = $this->db->query($tSQL);

        $tSQL2           = "
            SELECT
                PDTL.FTPdtName
            FROM TCNMPdt_L PDTL WITH(NOLOCK)
            WHERE PDTL.FTPdtCode = ".$this->db->escape($tPdtCode)."
            AND PDTL.FNLngID = ".$this->db->escape($nLangEdit)."
        ";
        $oQuery2 = $this->db->query($tSQL2);
        $aDataList2      = $oQuery2->result();

        if($oQuery->num_rows() > 0){
            $aDataList      = $oQuery->result();
            $aDataReturn    = array(
                'raItems'   => $aDataList,
                'aPdtName'  => $aDataList2,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aDataReturn    = array(
                'rtCode'    => '800',
                'aPdtName'  => $aDataList2,
                'rtDesc'    => 'data not found',
            );
        }
        return $aDataReturn;
    }

    /**
     * Functionality : Update PmtPdtDt Value in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePmtPdtDtInTmpBySeq($paParams = [])
    {
        $this->db->set('FDBddRefDateForDeposit', $paParams['tPmtPdtDtDate']);
        $this->db->set('FCBddRefAmtForDeposit', $paParams['cPmtPdtDtValue']);
        $this->db->set('FDLastUpdOn', 'GETDATE()', false);
        $this->db->set('FTLastUpdBy', $paParams['tUserLoginCode']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
        $this->db->where('FTBddTypeForDeposit', '1');
        $this->db->update('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Update PmtPdtDt Value in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePmtPdtDtInTmp($paParams = [])
    {
        $this->db->set('FTPmdGrpName', $paParams['tPmtGroupNameTmp']);
        $this->db->where('FTPmdGrpName', $paParams['tPmtGroupNameTmpOld']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->update('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete PmtPdtDt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePmtPdtDtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FNPmdSeq', $paParams['nSeqNo']);
        $this->db->delete('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete PmtPdtDt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePmtPdtDtInTmpByGroupName($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTPmdGrpName', $paParams['tGroupName']);
        $this->db->delete('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete More PmtPdtDt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeleteMorePmtPdtDtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where_in('FNPmdSeq', $paParams['aSeqNo']);
        $this->db->delete('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Check Group Name is Duplicate
     * Parameters : 
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Boolean
     */
    public function FSbMCheckPmtPdtDtDuplicateGroupName($paParams = [])
    {
        $tPmtGroupNameTmp = $paParams['tPmtGroupNameTmp'];
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tPmtGroupTypeTmp = $paParams['tPmtGroupTypeTmp'];
        $tUserSessionID = $paParams['tUserSessionID'];

        $tSQL = "   
            SELECT 
                FTPmdGrpName
            FROM TCNTPdtPmtDT_Tmp
            WHERE FTPmdGrpName = '$tPmtGroupNameTmp'
            AND FTSessionID = '$tUserSessionID'
        ";

        $bStatus = false;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    //ลบข้อมูล DTLOT Set Temp
    public function FSaMJR1DeleteDTLOTToTemp($paPDTDetail){
        $this->db->where('FTBchCode',$paPDTDetail['FTBchCode']);
        $this->db->where('FTPmhDocNo',$paPDTDetail['FTPmhDocNo']);
        $this->db->where('FNPmdSeq',$paPDTDetail['FNPmdSeq']);
        $this->db->where('FTPmdRefCode',$paPDTDetail['FTPmdRefCode']);
        $this->db->where('FTSessionID',$paPDTDetail['FTSessionID']);
        $this->db->delete('TCNTPdtPmtDTLot_Tmp');
    }

     /**
     * Functionality : Insert PmtPdtLot to Temp
     * Parameters : -
     * Creator : 03/11/2021 Off
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtPdtDtLotToTemp($paParams = [])
    {
        $tUserSessionID = $paParams['FTSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];

        $this->db->set('FTBchCode', $paParams['FTBchCode']);
        $this->db->set('FTPmhDocNo', $paParams['FTPmhDocNo']);
        $this->db->set('FTPmdRefCode', $paParams['FTPmdRefCode']);
        $this->db->set('FTPmdLotNo', $paParams['FTPmdLotNo']);
        $this->db->set('FNPmdSeq', $paParams['FNPmdSeq']);
        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtDTLot_Tmp');

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert PmtPdtDt Fail.',
            'nSeqno' => '',
        ];
        return $aStatus;
    }


}
