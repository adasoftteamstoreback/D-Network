<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep1PmtBrandDt extends CI_Model
{
    /**
     * Functionality : Get PmtBrandDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Data List PmtBrandDt
     * Return Type : Array
     */
    public function FSaMGetPmtBrandDtInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmp = $paParams['tPmtGroupNameTmp'];
        $tPmtGroupTypeTmp = $paParams['tPmtGroupTypeTmp'];
        $tPmtGroupListTypeTmp = $paParams['tPmtGroupListTypeTmp'];
        $aPdtCond = $paParams['aPdtCond'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);
        $nLngID = $paParams['FNLngID'];

        // Root Master
        $tTable = $aPdtCond['tTable'];
        $tTableL = $aPdtCond['tTableL'];

        if($tTable == "TCNMPdtSpl"){
            $tTableL = "TCNMSpl_L";
        }
        $tFieldCode = $aPdtCond['tFieldCode'];
        $tFieldName = $aPdtCond['tFieldName'];
        // $tTitle = $aPdtCond['tDropName'];
        // $tFieldCodeLabel = $aPdtCond['tFieldCodeLabel'];
        // $tFieldNameLabel = $aPdtCond['tFieldNameLabel'];
        $tSqlFieldName = "";
        $tSqlJoin = "";
        if (!empty($tTable)) {
            $tSqlFieldName = " REFL.$tFieldName AS FTPmdRefName,";
            $tSqlJoin = " LEFT JOIN $tTableL REFL WITH (NOLOCK) ON REFL.$tFieldCode = TMP.FTPmdRefCode AND REFL.FNLngID = $nLngID";
        }

        // Sub Master
        $tSubTable = $aPdtCond['tSubTable'];
        $tSubTableL = $aPdtCond['tSubTableL'];

        if($tSubTable == "TCNMPdtSpl"){
            $tSubTableL = "TCNMSpl_L";
        }
        $tSubFieldCode = $aPdtCond['tSubFieldCode'];
        $tSubFieldName = $aPdtCond['tSubFieldName'];
        // $tSubTitle = $aPdtCond['tSubRefNTitle'];
        // $tSubFieldCodeLabel = $aPdtCond['tSubFieldCodeLabel'];
        // $tSubFieldNameLabel = $aPdtCond['tSubFieldNameLabel'];
        $tSqlSubFieldName = "";
        $tSqlSubJoin = "";
        if (!empty($tSubTable)) {
            $tSqlSubFieldName = " SUBREF.$tSubFieldName AS FTPmdSubRefName,";
            $tSqlSubJoin = " LEFT JOIN $tSubTableL SUBREF WITH (NOLOCK) ON SUBREF.$tSubFieldCode = TMP.FTPmdSubRef AND SUBREF.FNLngID = $nLngID";
        }
        if($tTable == "TCNMPdtBrand"){
            $tWhereStuff = "PDTLOT.FTPbnCode";
        }elseif($tTable == "TCNMPdtModel"){
            $tWhereStuff = "PDTLOT.FTPmoCode";
        }

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
                        $tSqlFieldName
                        TMP.FTPmdSubRef,
                        TMP.FTXtdRmk,
                        $tSqlSubFieldName
                        TMP.FTPmdBarCode,
                        TMP.FTPmdStaListType,
                        ";
                        
            if($tTable == "TCNMPdtBrand" || $tTable == "TCNMPdtModel"){
                $tSQL .= " STUFF(
                    ( SELECT DISTINCT ' , ' + TLOT.FTLotBatchNo FROM TCNTPdtPmtDTLOT_Tmp AS LOT 
                    LEFT JOIN TCNMLot TLOT ON LOT.FTPmdLotNo = TLOT.FTLotNo
                    WHERE TMP.FNPmdSeq = LOT.FNPmdSeq AND LOT.FTSessionID = ".$this->db->escape($tUserSessionID)." FOR XML PATH ( '' ) 
                    ),
                    1,
                    1,
                    '' 
                ) AS LotNumber,
                STUFF(
                    ( SELECT DISTINCT ',' + PDTLOT.FTLotNo FROM TCNMPdtLot AS PDTLOT WHERE TMP.FTPmdRefCode = $tWhereStuff FOR XML PATH ( '' ) ),
                    1,
                    1,
                    '' 
                ) AS CheckLOT,";
            }else{
                $tSQL .= " 
                '' AS LotNumber,
                '' AS CheckLOT,
                ";
            }
        
            $tSQL .= " TMP.FTSessionID
                    FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK)
                    $tSqlJoin
			        $tSqlSubJoin
                    WHERE TMP.FTSessionID = ".$this->db->escape($tUserSessionID)."
                    AND TMP.FTPmdGrpName = ".$this->db->escape($tPmtGroupNameTmp)."
                    AND TMP.FTPmdStaType = ".$this->db->escape($tPmtGroupTypeTmp)."
                    AND TMP.FTPmdStaListType = ".$this->db->escape($tPmtGroupListTypeTmp)."
        ";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > ".$this->db->escape($aRowLen[0])." AND c.FNRowID <= ".$this->db->escape($aRowLen[1])."";
        // echo $tSQL;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPmtBrandDtInTmpPageAll($paParams);
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
     * Functionality : Count PmtBrandDt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count PmtBrandDt
     * Return Type : Number
     */
    public function FSnMTFWGetPmtBrandDtInTmpPageAll($paParams = [])
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
    public function FSaMGetPmtBrandDtInAllTmp($paParams = []){
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $nLngID = $paParams['FNLngID'];

        $tSQL = "
            SELECT 
                *
            FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID'
            AND TMP.FTBchCode = '$tBchCodeLogin'
            AND TMP.FTPmdRefCode IS NOT NULL
            AND TMP.FTPmdRefCode <> ''
            /* AND TMP.FTPmdGrpName = '$tPmtGroupNameTmpOld' */
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array();
    }

    /**
     * Functionality : Insert PmtBrandDt to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtBrandDtToTemp($paParams = [])
    {
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];
        $tBrandCode          = $paParams['tBrandCode'];
        $tTable          = $paParams['tTable'];

        $tSQLCheck = "
        SELECT 
        FNPmdSeq,
        FTPmdRefCode
        FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
        WHERE TMP.FTSessionID = '$tUserSessionID'
        AND TMP.FTPmdRefCode = '$tBrandCode'
        AND TMP.FTPmdGrpName = '$tPmtGroupNameTmpOld'
        ";
        $oQueryCheck = $this->db->query($tSQLCheck);
        $aResult = $oQueryCheck->result();

        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Already Brand/Model.',
            'nSeqno' => '',
        ];

        if(count($aResult) == 0){
        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0) + 1) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
        $this->db->set('FTPmdStaType', $paParams['tPmtGroupTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdGrpName', $tPmtGroupNameTmpOld); // ชื่อกลุ่มจัดรายการ
        $this->db->set('FTPmdRefCode', $paParams['tBrandCode']); // รหัสยี่ห้อ
        $this->db->set('FTPmdRefName', $paParams['tBrandName']); // ชื่อยี่ห้อ

        $this->db->set('FTPmdSubRef', isset($paParams['tModelCode'])?$paParams['tModelCode']:NULL); // รหัสรุ่น
        $this->db->set('FTPmdSubRefName', isset($paParams['tModelName'])?$paParams['tModelName']:NULL); // ชื่อรุ่น

        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->insert('TCNTPdtPmtDT_Tmp');


        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert PmtBrandDt Fail.',
        ];


        if ($this->db->affected_rows() > 0) {

            if($tTable == 'TCNMPdtBrand'){
                $tSQL = "   
                SELECT 
                FTPbnCode FROM TCNMPdtLot LOT  WITH(NOLOCK)
                WHERE LOT.FTPbnCode = ".$this->db->escape($paParams['tBrandCode'])."
                ";
            }elseif($tTable == 'TCNMPdtModel'){
                $tSQL = "   
                SELECT 
                FTPbnCode FROM TCNMPdtLot LOT  WITH(NOLOCK)
                WHERE LOT.FTPmoCode = ".$this->db->escape($paParams['tBrandCode'])."
                ";
            }else{
                $tSQL = "   
                SELECT 
                FTPbnCode FROM TCNMPdtLot LOT  WITH(NOLOCK)
                WHERE 1=2
                ";
            }
            
            $oQuery = $this->db->query($tSQL);
            // echo $tSQL;
            $aPdtLot = $oQuery->result();

            
            $tSQL2 = "
                SELECT 
                FNPmdSeq,
                FTPmdRefCode
                FROM TCNTPdtPmtDT_Tmp TMP WITH(NOLOCK) 
                WHERE TMP.FTSessionID = '$tUserSessionID'
                AND TMP.FTPmdRefCode = '$tBrandCode'
                AND TMP.FTPmdGrpName = '$tPmtGroupNameTmpOld'
                ";

                $oQuery = $this->db->query($tSQL2);
                $aPdtSeq = $oQuery->result();

            $aStatus['rtCode'] = '1';
            $aStatus['rtDesc'] = 'Insert PmtBrandDt Success';
            $aStatus['nSeqno'] = $aPdtSeq[0]->FNPmdSeq;
            if(count($aPdtLot) > 0 ){
                $aStatus['nStaLot'] = '1';  
            }else{
                $aStatus['nStaLot'] = '0';  
            }
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
    public function FSaMPmtBrandDtToTempErrorCase($paParams = [])
    {
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin = $paParams['tBchCodeLogin'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tUserSessionDate = $paParams['tUserSessionDate'];
        $tBrandCode          = $paParams['tBrandCode'];
        $tTable          = $paParams['tTable'];

        $tRemake = $this->FSaMPmtCheckLogBrandError($paParams);

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0) + 1) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
        $this->db->set('FTPmdStaType', $paParams['tPmtGroupTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdGrpName', $tPmtGroupNameTmpOld); // ชื่อกลุ่มจัดรายการ
        $this->db->set('FTPmdRefCode', $paParams['tBrandCode']); // รหัสยี่ห้อ
        $this->db->set('FTPmdSubRef', isset($paParams['tModelCode'])?$paParams['tModelCode']:NULL); // รหัสรุ่น
        $this->db->set('FTPmdSubRefName', isset($paParams['tModelName'])?$paParams['tModelName']:NULL); // ชื่อรุ่น
        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->set('FTXtdRmk', $tRemake);
        $this->db->insert('TCNTPdtPmtDT_Tmp');


        $aStatus = [
            'rtCode' => '905',
            'rtDesc' => 'Insert PmtPdtDt Fail.',
            'nSeqno' => '',
        ];
    }

        /**
     * Functionality : Insert PmtPdtDt to Temp With Error
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaMPmtModelDtToTempErrorCase($paParams = [])
    {
        $tPmtGroupNameTmpOld    = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin          = $paParams['tBchCodeLogin'];
        $tUserSessionID         = $paParams['tUserSessionID'];
        $tUserSessionDate       = $paParams['tUserSessionDate'];
        $tBrandCode             = $paParams['tBrandCode'];
        $tTable                 = $paParams['tTable'];
        $tGetPbnCode            = $paParams['tCheckBranCode'];

        $tRemake = $this->FSaMPmtCheckLogBrandError($paParams);

        $this->db->set('FTBchCode', $tBchCodeLogin);
        $this->db->set('FTPmhDocNo', $paParams['tDocNo']);
        $this->db->set('FNPmdSeq', "(SELECT (ISNULL(MAX(FNPmdSeq), 0) + 1) AS FNPmdSeq FROM TCNTPdtPmtDT_Tmp WITH(NOLOCK) WHERE FTSessionID = '$tUserSessionID' AND FTBchCode = '$tBchCodeLogin')", false);
        $this->db->set('FTPmdStaType', $paParams['tPmtGroupTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']); // ประเภทกลุ่ม 1:กลุ่มร่วมรายการ 2:กลุ่มยกเว้น
        $this->db->set('FTPmdGrpName', $tPmtGroupNameTmpOld); // ชื่อกลุ่มจัดรายการ
        $this->db->set('FTPmdRefCode', $paParams['tBrandCode']); // รหัสยี่ห้อ
        $this->db->set('FTPmdSubRef', isset($paParams['tModelCode'])?$paParams['tModelCode']:NULL); // รหัสรุ่น
        $this->db->set('FTPmdSubRefName', isset($paParams['tModelName'])?$paParams['tModelName']:NULL); // ชื่อรุ่น
        $this->db->set('FDCreateOn', $tUserSessionDate);
        $this->db->set('FTSessionID', $tUserSessionID);
        $this->db->set('FTXtdRmk', $tRemake);
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
    public function FSaMPmtCheckLogBrandError($paParams = [])
    {
        $tPmtGroupNameTmpOld    = $paParams['tPmtGroupNameTmpOld'];
        $tBchCodeLogin          = $paParams['tBchCodeLogin'];
        $tUserSessionID         = $paParams['tUserSessionID'];
        $tUserSessionDate       = $paParams['tUserSessionDate'];
        $tBrandCode             = $paParams['tBrandCode'];
        $tTable                 = $paParams['tTable'];
        $tGetPbnCode            = $paParams['tCheckBranCode'];

        if($tTable == 'TCNMPdtModel'){
            if($tGetPbnCode == ''){
                return 'ยี่ห้อสินค้าไม่ถูกต้อง';
            }

            $tSQLCheck = "
            SELECT 
            BLN.FTPbnCode
            FROM TCNMPdtBrand BLN WITH(NOLOCK) 
            WHERE BLN.FTPbnCode = ".$this->db->escape($tGetPbnCode)."
            ";
            $oQueryCheck = $this->db->query($tSQLCheck);
            $aResult = $oQueryCheck->result();
            if(count($aResult) == 0 ){
                return 'ยี่ห้อสินค้าไม่ถูกต้อง';
            }

            $tSQLCheck = "
            SELECT 
            PMO.FTPmoCode
            FROM TCNMPdtModel PMO WITH(NOLOCK) 
            WHERE PMO.FTPmoCode = ".$this->db->escape($tBrandCode)."
            ";
            $oQueryCheck = $this->db->query($tSQLCheck);
            $aResult = $oQueryCheck->result();
            if(count($aResult) == 0){
                return 'รุ่นสินค้าไม่ถูกต้อง';
            }

        }else{
            $tSQLCheck = "
            SELECT 
            BLN.FTPbnCode
            FROM TCNMPdtBrand BLN WITH(NOLOCK) 
            WHERE BLN.FTPbnCode = ".$this->db->escape($tBrandCode)."
            ";
            $oQueryCheck = $this->db->query($tSQLCheck);
            $aResult = $oQueryCheck->result();
            if(count($aResult) == 0 ){
                return 'ยี่ห้อสินค้าไม่ถูกต้อง';
            }
        }

    }

       /**
     * Functionality : Check Stalot
     * Parameters : DocNo
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Boolean
     */
    public function FSbMCheckStaLot($paParams = [])
    {
        $tSQL = "   
        SELECT 
        FTPbnCode FROM TCNMPdtLot LOT  WITH(NOLOCK)
        WHERE LOT.FTPbnCode = ".$this->db->escape($paParams['tBrandCode'])."
        ";

        $bStatus = false;
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Update PmtBrandDt Value in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePmtBrandDtInTmpBySeq($paParams = [])
    {
        $this->db->set('FTPmdSubRef', $paParams['tModelCode']);
        $this->db->set('FTPmdSubRefName', $paParams['tModelName']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FNPmdSeq', $paParams['nSeqNo']);
        $this->db->where('FTPmdStaType', $paParams['tPmtGroupTypeTmp']);
        $this->db->where('FTPmdStaListType', $paParams['tPmtGroupListTypeTmp']);

        $this->db->update('TCNTPdtPmtDT_Tmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Get Pdt Condition (ประเภทรายการ)
     * Parameters : -
     * Creator : 29/10/2020 piya
     * Last Modified : -
     * Return : Pdt Condition List
     * Return Type : Array
     */
    public function FSaMGetPmtPdtCondition($paParams = [])
    {
        $nLngID = $paParams['nLngID'];

        $tSQL = "
            SELECT
                PdtCond.FNPmtID,
                PdtCond.FTPmtRefCode,
                PdtCond.FTPmtRefPdt,
                PdtCond.FTPmtSubRef,
                PdtCond.FTPmtSubRefPdt,
                PdtCond.FTPmtStaUse,
                PdtCondL.FTDropName,
                PdtCondL.FTPmtRefN,
                PdtCondL.FTPmtSubRefN,
                PdtCondL.FTSubRefNTitle
            FROM TCNSPmtPdtCond PdtCond WITH (NOLOCK)
            LEFT JOIN TCNSPmtPdtCond_L PdtCondL WITH (NOLOCK) ON PdtCondL.FNPmtID = PdtCond.FNPmtID AND PdtCondL.FNLngID = $nLngID
        ";

        $oQuery = $this->db->query($tSQL);

        return $oQuery->result_array();
    }

    /**
     * Functionality : Show Lot Detail
     * Parameters : -
     * Creator : 03/11/2021 Off
     * Last Modified : -
     */
    public function FSaMPmtBrandDtGetLotDetail($paDataWhere){
        $tPdtCode       = $paDataWhere['tPdtCode'];
        $tTable         = $paDataWhere['tTable'];
        $tSubref        = $paDataWhere['tSubref'];
        $nLangEdit = $this->session->userdata("tLangEdit");

        if($tTable == 'TCNMPdtBrand'){
            $tWhere = "WHERE LOT.FTPbnCode = ".$this->db->escape($tPdtCode)."";
            if($tSubref != ''){
                $tWhere2 = "WHERE LOT.FTPbnCode = ".$this->db->escape($tPdtCode)." AND LOT.FTPmoCode = ".$this->db->escape($tSubref)."";
            }else{
                $tWhere2 = "WHERE LOT.FTPbnCode = ".$this->db->escape($tPdtCode)." ";
            }
            $tSQL           = "
            SELECT DISTINCT
                LOT.FTPbnCode AS FTPdtCode,
                LOT.FTLotNo,
                TLOT.FTLotBatchNo
            FROM TCNMPdtLot LOT WITH(NOLOCK)
            LEFT JOIN TCNMLot TLOT ON LOT.FTLotNo = TLOT.FTLotNo
            $tWhere2
            ";
            $oQuery = $this->db->query($tSQL);

            //กรณีเลือก โมเดลแล้วเหลือ 0
            if($oQuery->num_rows() <= 0){
                $tSQL           = "
                SELECT DISTINCT
                    LOT.FTPbnCode AS FTPdtCode,
                    LOT.FTLotNo,
                    TLOT.FTLotBatchNo
                FROM TCNMPdtLot LOT WITH(NOLOCK)
                LEFT JOIN TCNMLot TLOT ON LOT.FTLotNo = TLOT.FTLotNo
                $tWhere
                ";
                $oQuery = $this->db->query($tSQL);
            }

        }elseif($tTable == 'TCNMPdtModel'){
            $tWhere = "WHERE LOT.FTPmoCode = ".$this->db->escape($tPdtCode)."";
            $tSQL           = "
            SELECT
                LOT.FTPmoCode AS FTPdtCode,
                LOT.FTLotNo,
                TLOT.FTLotBatchNo
            FROM TCNMPdtLot LOT WITH(NOLOCK)
            LEFT JOIN TCNMLot TLOT ON LOT.FTLotNo = TLOT.FTLotNo
            $tWhere
            ";
            $oQuery = $this->db->query($tSQL);
        }


        if($tTable == 'TCNMPdtBrand'){
            $tSQL2           = "
            SELECT
                PBNL.FTPbnName AS FTPdtName
            FROM TCNMPdtBrand_L PBNL WITH(NOLOCK)
            WHERE PBNL.FTPbnCode = ".$this->db->escape($tPdtCode)."
            AND PBNL.FNLngID = ".$this->db->escape($nLangEdit)."
            ";
        }elseif($tTable == 'TCNMPdtModel'){
            $tSQL2           = "
            SELECT
                PMOL.FTPmoName AS FTPdtName
            FROM TCNMPdtModel_L PMOL WITH(NOLOCK)
            WHERE PMOL.FTPmoCode = ".$this->db->escape($tPdtCode)."
            AND PMOL.FNLngID = ".$this->db->escape($nLangEdit)."
            ";
        }
       
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
        $tPbnCode = $paParams['tBrandCode'];
        $tPmoCode = $paParams['tModelCode'];
        $table  = $paParams['tTable'];


        if($table == 'TCNMPdtBrand'){
            $tSQLCheckHaveLot = "SELECT DISTINCT
            LOT.FTLotNo
            FROM TCNMPdtLot LOT 
            LEFT JOIN TCNMLot LOTT ON LOT.FTLotNo = LOTT.FTLotNo
            WHERE LOT.FTPbnCode = ".$this->db->escape($tPbnCode)."
            AND LOT.FTLotNo != '' 
            ";
            if($tPmoCode != ''){
                $tSQLCheckHaveLot .= " AND LOT.FTPmoCode = ".$this->db->escape($tPmoCode)." ";
            }
        }elseif($table == 'TCNMPdtModel'){
            $tSQLCheckHaveLot = "SELECT DISTINCT
            LOT.FTLotNo
            FROM TCNMPdtLot LOT 
            LEFT JOIN TCNMLot LOTT ON LOT.FTLotNo = LOTT.FTLotNo
            WHERE LOT.FTPbnCode = ".$this->db->escape($paParams['tCheckBranCode'])."
            AND LOT.FTLotNo != '' 
            AND LOT.FTPmoCode = ".$this->db->escape($tPbnCode)."
            ";
        }
        if($paParams['tYear'] != ''){
            $tSQLCheckHaveLot .= " AND LOTT.FTLotYear = ".$this->db->escape($paParams['tYear'])." ";
        }

        $oQueryHaveLot = $this->db->query($tSQLCheckHaveLot);
        $aGetAllLot      = $oQueryHaveLot->result_array();

        foreach($aGetAllLot as $nKey => $aValue){
            if($aValue['FTLotNo'] == $paParams['tLotNo']){
                if($paParams['tLotNo'] != ''){ 
                    $this->db->set('FTBchCode', $paParams['tBchCode']);
                    $this->db->set('FTPmhDocNo', $paParams['tDocNoLot']);
                    $this->db->set('FTPmdRefCode', $paParams['tBrandCode']);
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
}
