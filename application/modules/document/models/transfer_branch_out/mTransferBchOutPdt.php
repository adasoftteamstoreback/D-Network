<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mTransferBchOutPdt extends CI_Model
{
    /**
     * Functionality : Get Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Data List Pdt
     * Return Type : Array
     */
    public function FSaMGetPdtInTmp($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tDocKey = $paParams['tDocKey'];
        $aRowLen = FCNaHCallLenData($paParams['nRow'], $paParams['nPage']);

        $tSQL = "
            SELECT c.* FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS FNRowID,* FROM
                    (SELECT DISTINCT
                        TMP.FTBchCode,
                        TMP.FTXthDocNo,
                        TMP.FNXtdSeqNo,
                        TMP.FTXthDocKey,
                        TMP.FTPdtCode,
                        TMP.FTXtdPdtName,
                        /*TMP.FTXtdStkCode,*/
                        TMP.FTPunCode,
                        TMP.FTPunName,
                        TMP.FCXtdFactor,
                        TMP.FTXtdBarCode,
                        TMP.FTXtdVatType,
                        TMP.FTVatCode,
                        TMP.FCXtdVatRate,
                        TMP.FCXtdQty,
                        TMP.FCXtdQtyAll,
                        TMP.FCXtdSetPrice,
                        TMP.FCXtdAmt,
                        TMP.FCXtdVat,
                        TMP.FCXtdVatable,
                        TMP.FCXtdNet,
                        TMP.FCXtdCostIn,
                        TMP.FCXtdCostEx,
                        TMP.FTXtdStaPrcStk,
                        TMP.FNXtdPdtLevel,
                        TMP.FTXtdPdtParent,
                        TMP.FCXtdQtySet,
                        TMP.FTXtdPdtStaSet,
                        TMP.FTXtdRmk,
                        TMP.FTSessionID,

                        TMP.FDLastUpdOn,
                        TMP.FDCreateOn,
                        TMP.FTLastUpdBy,
                        TMP.FTCreateBy
                    FROM TSVTTBODocDTTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTSessionID = '$tUserSessionID'
                    AND TMP.FTXthDocKey = '$tDocKey'
        ";

        $tSearchList = $paParams['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL .= " AND ((TMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oList = $oQuery->result();
            $nFoundRow = $this->FSnMTFWGetPdtInTmpPageAll($paParams);
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
        unset($tUserSessionID);
        unset($tDocKey);
        unset($aRowLen);
        unset($tSQL);
        unset($tSearchList);
        unset($oQuery);
        return $aResult;
    }

    /**
     * Functionality : Count Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Count Pdt
     * Return Type : Number
     */
    public function FSnMTFWGetPdtInTmpPageAll($paParams = [])
    {
        $tUserSessionID = $paParams['tUserSessionID'];
        $tDocKey = $paParams['tDocKey'];

        $tSQL = "
            SELECT 
                FTSessionID
            FROM TSVTTBODocDTTmp TMP WITH(NOLOCK) 
            WHERE TMP.FTSessionID = '$tUserSessionID' 
            AND TMP.FTXthDocKey = '$tDocKey'
        ";

        $tSearchList = $paParams['tSearchAll'];
        if ($tSearchList != '') {
            $tSQL .= " AND ((TMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchList%') OR (TMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchList%'))";
        }
        
        $oQuery = $this->db->query($tSQL);
        unset($tUserSessionID);
        unset($tDocKey);
        unset($tSQL);
        unset($tSearchList);
        return $oQuery->num_rows();
    }

    /**
     * Functionality : Update Pdt Value in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Update
     * Return Type : Boolean
     */
    public function FSbUpdatePdtInTmpBySeq($paParams = [])
    {
        $this->db->set($paParams['tFieldName'], $paParams['tValue']);
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->update('TSVTTBODocDTTmp');

        $tSQL = "SELECT TMP.FCXtdFactor * TMP.FCXtdQty AS QTY FROM TSVTTBODocDTTmp TMP WITH(NOLOCK) 
                 WHERE TMP.FTXthDocNo = '".$paParams['tDocNo']."' AND TMP.FTSessionID = '".$paParams['tUserSessionID']."' AND TMP.FTXthDocKey = '".$paParams['tDocKey']."' AND TMP.FNXtdSeqNo = '".$paParams['nSeqNo']."' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList                  = $oQuery->result_array();
            $this->db->set('FCXtdQtyAll', $oDataList[0]['QTY']);
            $this->db->where('FTSessionID', $paParams['tUserSessionID']);
            $this->db->where('FTXthDocNo', $paParams['tDocNo']);
            $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
            $this->db->where('FTXthDocKey', $paParams['tDocKey']);
            $this->db->update('TSVTTBODocDTTmp');
        }

        $bStatus = false;
        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }
        unset($tSQL);
        unset($oQuery);

        return $bStatus;
    }

    /**
     * Functionality : Delete Pdt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeletePdtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->where('FNXtdSeqNo', $paParams['nSeqNo']);
        $this->db->delete('TSVTTBODocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Delete More Pdt in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbDeleteMorePdtInTmpBySeq($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTXthDocNo', $paParams['tDocNo']);
        $this->db->where('FTXthDocKey', $paParams['tDocKey']);
        $this->db->where_in('FNXtdSeqNo', $paParams['aSeqNo']);
        $this->db->delete('TSVTTBODocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Clear Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status Delete
     * Return Type : Boolean
     */
    public function FSbClearPdtInTmp($paParams = [])
    {
        $this->db->where('FTSessionID', $paParams['tUserSessionID']);
        $this->db->where('FTBddTypeForDeposit', '1');
        $this->db->delete('TSVTTBODocDTTmp');

        $bStatus = false;

        if ($this->db->affected_rows() > 0) {
            $bStatus = true;
        }

        return $bStatus;
    }

    /**
     * Functionality : Function Get Max Seq From Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Max Seq
     * Return Type : array
     */
    public function FSnMGetMaxSeqDTTemp($paParams = [])
    {
        $tDocNo = $paParams['tDocNo'];
        $tDocKey = $paParams['tDocKey'];
        $tUserSessionID = $paParams['tUserSessionID'];

        $tSQL = "
            SELECT 
                MAX(DOCTMP.FNXtdSeqNo) AS maxSeqNo
            FROM TSVTTBODocDTTmp DOCTMP WITH (NOLOCK)
            WHERE 1 = 1
        ";

        $tSQL .= " AND DOCTMP.FTXthDocNo = '$tDocNo'";

        $tSQL .= " AND DOCTMP.FTXthDocKey = '$tDocKey'";

        $tSQL .= " AND DOCTMP.FTSessionID = '$tUserSessionID'";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $oDetail = $oQuery->result_array();
            $aResult = $oDetail[0]['maxSeqNo'];
        } else {
            $aResult = 0;
        }

        unset($tDocNo);
        unset($tDocKey);
        unset($tUserSessionID);
        unset($tSQL);
        unset($oQuery);

        return empty($aResult) ? 0 : $aResult;
    }

    /**
     * Functionality : Get Pdt Data
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Pdt Data
     * Return Type : array
     */
    public function FSaMGetDataPdt($paParams = []){

        $tPdtCode = $paParams['tPdtCode'];
        $FTPunCode = $paParams['tPunCode'];
        $FTBarCode = $paParams['tBarCode'];
        $nLngID = $paParams['nLngID'];

        $tSQL = "
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
            LEFT JOIN (
                SELECT DISTINCT
                    P4PDT.FTPdtCode,
                    P4PDT.FTPunCode,
                    P4PDT.FDPghDStart,
                    P4PDT.FTPghTStart,
                    P4PDT.FCPgdPriceRet
                    /*,P4PDT.FCPgdPriceWhs
                    ,P4PDT.FCPgdPriceNet*/
                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                WHERE 1=1
                AND (CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121))
                AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121))
            ) AS PRI4PDT
            ON PDT.FTPdtCode = PRI4PDT.FTPdtCode AND PRI4PDT.FTPunCode = PKS.FTPunCode
            WHERE 1 = 1
        ";
        
        if($tPdtCode!= ""){
            $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
        }

        if($FTBarCode!= ""){
            $tSQL .= "AND BAR.FTBarCode = '$FTBarCode'";
        }
        
        $tSQL .= " ORDER BY FDVatStart DESC";
        
        $oQuery = $this->db->query($tSQL);
        
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItem'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $tResult = json_encode($aResult);
        $aResult = json_decode($tResult, true);

        unset($tPdtCode);
        unset($FTPunCode);
        unset($FTBarCode);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);

        return $aResult;
    }

    /**
     * Functionality : Insert DT to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : array
     */
    public function FSaMInsertPDTToTemp($paData = [], $paDataWhere = []){
        
        $paData = $paData['raItem'];
        if($paDataWhere['tOptionAddPdt'] == '1'){
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL = "   
                SELECT 
                    FNXtdSeqNo, 
                    FCXtdQty,
                    FCXtdFactor
                FROM TSVTTBODocDTTmp WITH(NOLOCK)
                WHERE FTBchCode = '".$paDataWhere['tBchCode']."' 
                AND FTXthDocNo = '".$paDataWhere['tDocNo']."'
                AND FTXthDocKey = '".$paDataWhere['tDocKey']."'
                AND FTSessionID = '".$paDataWhere['tUserSessionID']."'
                AND FTPdtCode = '".$paData["FTPdtCode"]."' 
                AND FTXtdBarCode = '".$paData["FTBarCode"]."'
                ORDER BY FNXtdSeqNo
            ";
            
            $oQuery = $this->db->query($tSQL);
            
            if($oQuery->num_rows() > 0){ // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult = $oQuery->row_array();
                $tSQL = "
                    UPDATE TSVTTBODocDTTmp SET
                        FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."',
                        FCXtdQtyAll = '".(($aResult["FCXtdQty"] + 1) * $aResult["FCXtdFactor"])."'
                    WHERE 
                    FTBchCode = '".$paDataWhere['tBchCode']."' AND
                    FTXthDocNo  = '".$paDataWhere['tDocNo']."' AND
                    FNXtdSeqNo = '".$aResult["FNXtdSeqNo"]."' AND
                    FTXthDocKey = '".$paDataWhere['tDocKey']."' AND
                    FTSessionID = '".$paDataWhere['tUserSessionID']."' AND
                    FTPdtCode = '".$paData["FTPdtCode"]."' AND 
                    FTXtdBarCode = '".$paData["FTBarCode"]."'";
                    
                $this->db->query($tSQL);
                
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                
                // เพิ่มรายการใหม่
                $this->db->set('FTPdtCode', $paData['FTPdtCode']);
                $this->db->set('FTXtdPdtName', $paData['FTPdtName']);
                $this->db->set('FCXtdFactor', $paData['FCPdtUnitFact']);
                $this->db->set('FCPdtUnitFact', $paData['FCPdtUnitFact']);
                $this->db->set('FTPunCode', $paData['FTPunCode']);
                $this->db->set('FTPunName', $paData['FTPunName']);
                $this->db->set('FTXtdVatType', $paData['FTPdtStaVatBuy']);
                $this->db->set('FTVatCode', $paData['FTVatCode']);
                $this->db->set('FCXtdVatRate', $paData['FCVatRate']);
                $this->db->set('FCXtdNet', $paData['FTPdtPoint'] * $paData['FCPdtCostStd']);
                $this->db->set('FTXtdStaAlwDis', $paData['FTPdtStaAlwDis']);
                $this->db->set('FCXtdQty', 1);  // เพิ่มสินค้าใหม่
                $this->db->set('FCXtdQtyAll', 1*$paData['FCPdtUnitFact']); // จากสูตร qty * fector
                $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

                $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
                $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
                $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
                $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
                $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
                $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
                $this->db->set('FTSessionID', $paDataWhere['tUserSessionID']);
                $this->db->set('FDLastUpdOn', date('Y-m-d h:i:s'));
                $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
                $this->db->set('FDCreateOn', date('Y-m-d h:i:s'));
                $this->db->set('FTCreateBy', $this->session->userdata('tSesUsername'));
                        
                $this->db->insert('TSVTTBODocDTTmp');

                $this->db->last_query();  

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add.',
                    );
                }
            }
        }else{
            // เพิ่มแถวใหม่
            $this->db->set('FTPdtCode', $paData['FTPdtCode']);
            $this->db->set('FTXtdPdtName', $paData['FTPdtName']);
            $this->db->set('FCXtdFactor', $paData['FCPdtUnitFact']);
            $this->db->set('FCPdtUnitFact', $paData['FCPdtUnitFact']);
            $this->db->set('FTPunCode', $paData['FTPunCode']);
            $this->db->set('FTPunName', $paData['FTPunName']);
            $this->db->set('FTXtdVatType', $paData['FTPdtStaVatBuy']);
            $this->db->set('FTVatCode', $paData['FTVatCode']);
            $this->db->set('FCXtdVatRate', $paData['FCVatRate']);
            $this->db->set('FCXtdNet', $paData['FTPdtPoint'] * $paData['FCPdtCostStd']);
            $this->db->set('FTXtdStaAlwDis', $paData['FTPdtStaAlwDis']);
            $this->db->set('FCXtdQty', 1);  // เพิ่มสินค้าใหม่
            $this->db->set('FCXtdQtyAll', 1*$paData['FCPdtUnitFact']); // จากสูตร qty * fector
            $this->db->set('FCXtdSalePrice', $paData['FTPdtSalePrice']);

            $this->db->set('FTBchCode', $paDataWhere['tBchCode']);
            $this->db->set('FTXthDocNo', $paDataWhere['tDocNo']);
            $this->db->set('FNXtdSeqNo', $paDataWhere['nMaxSeqNo']);
            $this->db->set('FTXthDocKey', $paDataWhere['tDocKey']);
            $this->db->set('FTXtdBarCode', $paDataWhere['tBarCode']);
            $this->db->set('FCXtdSetPrice', $paDataWhere['pcPrice'] * 1); // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
            $this->db->set('FTSessionID', $paDataWhere['tUserSessionID']);
            $this->db->set('FDLastUpdOn', date('Y-m-d h:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FDCreateOn', date('Y-m-d h:i:s'));
            $this->db->set('FTCreateBy', $this->session->userdata('tSesUsername'));

            $this->db->insert('TSVTTBODocDTTmp');

            $this->db->last_query();  

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
        }
        
        return $aStatus;
        
    }

    /**
     * Functionality : คำนวณใน DT Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMCalInTmp($aParams = [])
    {
        $tUserSessionID = $aParams['tUserSessionID'];
        $tDocKey = $aParams['tDocKey'];

        $tSQL = "
            SELECT
                SUM(CASE 
                    WHEN TMP.FTBddTypeForDeposit = '1' THEN ISNULL(TMP.FCBddRefAmtForDeposit, 0)
                    ELSE 0
                END) AS FCBddRefAmtCashTotal,
                SUM(CASE 
                    WHEN TMP.FTBddTypeForDeposit = '2' THEN ISNULL(TMP.FCBddRefAmtForDeposit, 0)
                    ELSE 0
                END) AS FCBddRefAmtChequeTotal,
                SUM(ISNULL(TMP.FCBddRefAmtForDeposit, 0)) AS FCBddRefAmtTotal
            FROM TSVTTBODocDTTmp TMP WITH(NOLOCK)
            WHERE FTSessionID = '$tUserSessionID' AND FTXthDocKey = '$tDocKey'
            GROUP BY TMP.FTSessionID
        ";

        $oQuery = $this->db->query($tSQL);

        $aData = [
            'FCBddRefAmtPdtTotal' => 0,
            'FCBddRefAmtTotal' => 0
        ];

        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->row_array();
        }

        return $aData;
    }

    // Function: Get Data DO HD List
    public function FSoMTransferBchOutCallRefIntDocDataTable($paDataCondition){
        $aRowLen                             = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                              = $paDataCondition['FNLngID'];
        $aAdvanceSearch                      = $paDataCondition['aAdvanceSearch'];
        $tTransferBchOutRefIntBchCode        = $aAdvanceSearch['tTransferBchOutRefIntBchCode'];
        $tTransferBchOutRefIntDocNo          = $aAdvanceSearch['tTransferBchOutRefIntDocNo'];
        $tTransferBchOutRefIntDocDateFrm     = $aAdvanceSearch['tTransferBchOutRefIntDocDateFrm'];
        $tTransferBchOutRefIntDocDateTo      = $aAdvanceSearch['tTransferBchOutRefIntDocDateTo'];
        $tTransferBchOutRefIntStaDoc         = $aAdvanceSearch['tTransferBchOutRefIntStaDoc'];

        $tSQLMain = "   SELECT
                            DISTINCT
                                RBHD.FTBchCode,
                                BCHL.FTBchName,
                                RBHD.FTXthDocNo,
                                CONVERT(CHAR(10),RBHD.FDXthDocDate,103) AS FDXthDocDate,
                                CONVERT(CHAR(5), RBHD.FDXthDocDate,108) AS FTXshDocTime,
                                RBHD.FTXthStaDoc,
                                RBHD.FTXthStaApv,
                                RBHD.FNXthStaRef,
                                RBHD.FTSplCode,
                                RBHD.FTXthVATInOrEx,
                                RBHD.FTCreateBy,
                                RBHD.FDCreateOn,
                                RBHD.FNXthStaDocAct,
                                SPL_L.FTSplName,
                                USRL.FTUsrName      AS FTCreateByName,
                                RBHD.FTXthApvCode ,
                                SPLCR.FCSplCrLimit ,
                                BCHLTo.FTBchName    AS FTBchNameTo ,
                                BCHLTo.FTBchCode    AS FTBchCodeTo ,
                                WAHLTo.FTWahCode    AS FTWahCodeTo ,
                                WAHLTo.FTWahName    AS FTWahNameTo ,
                                BCHLFrm.FTBchName   AS FTBchNameFrm ,
                                BCHLFrm.FTBchCode   AS FTBchCodeFrm 
                            FROM TCNTPdtReqBchHD    RBHD      WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL      WITH (NOLOCK) ON RBHD.FTBchCode     = BCHL.FTBchCode     AND BCHL.FNLngID      = $nLngID 
                            LEFT JOIN TCNMBranch_L  BCHLFrm   WITH (NOLOCK) ON RBHD.FTXthBchFrm   = BCHLFrm.FTBchCode  AND BCHLFrm.FNLngID   = $nLngID 
                            LEFT JOIN TCNMBranch_L  BCHLTo    WITH (NOLOCK) ON RBHD.FTXthBchTo    = BCHLTo.FTBchCode   AND BCHLTo.FNLngID    = $nLngID 
                            LEFT JOIN TCNMWahouse_L WAHLTo    WITH (NOLOCK) ON RBHD.FTXthWhTo     = WAHLTo.FTWahCode   AND WAHLTo.FNLngID    = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL      WITH (NOLOCK) ON RBHD.FTCreateBy    = USRL.FTUsrCode     AND USRL.FNLngID      = $nLngID
                            LEFT JOIN TCNMSpl       SPL       WITH (NOLOCK) ON RBHD.FTSplCode     = SPL.FTSplCode  
                            LEFT JOIN TCNMSplCredit SPLCR     WITH (NOLOCK) ON RBHD.FTSplCode     = SPLCR.FTSplCode  
                            LEFT JOIN TCNMSpl_L     SPL_L     WITH (NOLOCK) ON RBHD.FTSplCode     = SPL_L.FTSplCode    AND SPL_L.FNLngID     = $nLngID
                            LEFT JOIN TCNTPdtTboHDDocRef RB_R WITH (NOLOCK) ON RB_R.FTXshRefDocNo = RBHD.FTXthDocNo    AND RB_R.FTXshRefType = 1
                            AND RBHD.FTXthStaDoc = 1 AND RBHD.FTXthStaDoc = 1 AND RBHD.FTXthStaApv = 1
                        WHERE 1=1 ";

        if(isset($tTransferBchOutRefIntBchCode) && !empty($tTransferBchOutRefIntBchCode)){
            $tSQLMain .= " AND (RBHD.FTXthBchFrm = '$tTransferBchOutRefIntBchCode' )";
        }

        if(isset($tTransferBchOutRefIntDocNo) && !empty($tTransferBchOutRefIntDocNo)){
            $tSQLMain .= " AND (RBHD.FTXthDocNo LIKE '%$tTransferBchOutRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tTransferBchOutRefIntDocDateFrm) && !empty($tTransferBchOutRefIntDocDateTo)){
            $tSQLMain .= " AND ((RBHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tTransferBchOutRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tTransferBchOutRefIntDocDateTo 23:59:59')) OR (RBHD.FDXthDocDate BETWEEN CONVERT(datetime,'$tTransferBchOutRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tTransferBchOutRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tTransferBchOutRefIntStaDoc) && !empty($tTransferBchOutRefIntStaDoc)){
            if ($tTransferBchOutRefIntStaDoc == 3) {
                $tSQLMain .= " AND RBHD.FTXthStaDoc = '$tTransferBchOutRefIntStaDoc'";
            } elseif ($tTransferBchOutRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(RBHD.FTXthStaApv,'') = '' AND RBHD.FTXthStaDoc != '3'";
            } elseif ($tTransferBchOutRefIntStaDoc == 1) {
                $tSQLMain .= " AND RBHD.FTXthStaApv = '$tTransferBchOutRefIntStaDoc'";
            }
        }

        $tSQLMain .= " AND ISNULL(RB_R.FTXshRefType, '') = '' ";

        $tSQL   =   "SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FDXthDocDate DESC , FTXthDocNo DESC ) AS FNRowID,* FROM
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

    // Functionality: Get Data Purchase Order HD List
    public function FSoMTransferBchOutCallRefIntDocDTDataTable($paData){

        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];
        
        $tSQL= "SELECT
                        DT.FTBchCode,
                        DT.FTXthDocNo,
                        DT.FNXtdSeqNo,
                        DT.FTPdtCode,
                        DT.FTXtdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXtdFactor,
                        DT.FTXtdBarCode,
                        DT.FCXtdQty,
                        DT.FCXtdQtyAll,
                        DT.FTXtdRmk,
                        DT.FDLastUpdOn,
                        DT.FTLastUpdBy,
                        DT.FDCreateOn,
                        DT.FTCreateBy
                        FROM TCNTPdtReqBchDT DT WITH(NOLOCK)
                WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXthDocNo ='$tDocNo' ";

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
    public function FSoMTransferBchOutCallRefIntDocInsertDTToTemp($paData){

        $tTransferBchOutFrmBchCode   = $paData['tTransferBchOutFrmBchCode'];
        $tSessionID                  = $this->session->userdata('tSesSessionID');

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tTransferBchOutFrmBchCode);
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->delete('TSVTTBODocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        if($paData['aSeqNo'] != 'all'){
            $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';
        }
        
        $tSQL= "INSERT INTO TSVTTBODocDTTmp (
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
                    '$tTransferBchOutFrmBchCode' as FTBchCode,
                    'TBODOCTEMP' as FTXthDocNo,
                    ROW_NUMBER() OVER(ORDER BY DT.FNXtdSeqNo DESC ) AS FNXtdSeqNo,
                    'TCNTPdtTboHD' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXtdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXtdFactor,
                    DT.FTXtdBarCode,
                    '' AS FTSrnCode,
                    PDT.FTPdtStaVatBuy,
                    PDT.FTVatCode AS FTVatCode,
                    DT.FCXtdVatRate,
                    PDT.FTPdtSaleType AS FTXtdSaleType,
                    PDT.FCPdtCostStd AS FCXtdSalePrice,
                    DT.FCXtdQty,
                    DT.FCXtdQtyAll,
                    PDT.FCPdtCostStd * DT.FCXtdQty AS FCXtdSetPrice,
                    0 AS FCXtdAmtB4DisChg,
                    '' AS FTXtdDisChgTxt,
                    0 as FCXtdQtyLef,
                    0 as FCXtdQtyRfn,
                    '' as FTXtdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXtdPdtLevel,
                    '' as FTXtdPdtParent,
                    0 as FCXtdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXtdRmk,   
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                TCNTPdtReqBchDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    WHERE DT.FTXthDocNo ='$tRefIntDocNo' ";

        if($paData['aSeqNo'] == 'all'){
            $tSQL .= " ";
        }else{
            $tSQL .= " AND DT.FNXtdSeqNo IN $aSeqNo ";
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
    
    //เอาสินค้าส่งไปเช็ค Stock
    public function FSaMTransferBchOutGetPdtInTmpForSendToAPI($paData){

        $tDocCode   = $paData['FTXshDocNo'];
        $tWahCode   = $paData['FTWahCode'];

        $tSQL       = "SELECT
                            TMP.FTPdtCode               AS ptPdtCode,
                            TMP.FTBchCode               AS ptBchCode,
                            '$tWahCode'				    AS ptWahCode,
                            TMP.FCXtdQty                AS pcQty,
                            ''                          AS ptAgnCode,
                            TMP.FTPunCode               AS ptPunCode,
                            '$tDocCode'                 AS ptDocNo,
                            HDRef.FTXshRefDocNo         AS ptRefDocNo
                        FROM TCNTPdtTboDT               TMP     WITH(NOLOCK)
                        INNER JOIN TCNMPdt              PDT     WITH(NOLOCK) ON TMP.FTPdtCode = PDT.FTPdtCode
                        LEFT JOIN TCNTPdtTboHDDocRef    HDREF   WITH(NOLOCK) ON TMP.FTXthDocNo = HDREF.FTXshDocNo AND FTXshRefKey = 'TR' AND FTXshRefType = 1
                        WHERE TMP.FTXthDocNo = '$tDocCode'
                          AND PDT.FTPdtStkControl = '1'
                          AND ISNULL(TMP.FTXtdStaPrcStk,'') != '1' ";

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

    //เอาสินค้าตัวที่ไม่พอ มาหาชื่อ
    public function FSxMFindTextNamePDTNoStock($ptTextCodePDT){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = " SELECT FTPdtName , FTPdtCode from TCNMPdt_L where FNLngID = '$nLngID' AND FTPdtCode IN($ptTextCodePDT) ";

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

    public function FSxMTransferBchOutChkConfig(){

        $tSQL       = " SELECT FTSysStaUsrValue from TSysConfig where FTSysCode = 'tDoc_ChkStkTranfer'";

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

    //Get ข้อมูล API
    public function FSaMTransferBchOutGetConfigAPI(){
        $tSQL       = "SELECT TOP 1 * FROM TCNTUrlObject WITH(NOLOCK) WHERE FTUrlKey = 'CHKSTK' AND FTUrlTable = 'TCNMComp' AND FTUrlRefID = 'CENTER' ORDER BY FNUrlSeq ASC";
        $oQuery     = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
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

    public function FSxMTransferBchUpdatePdtStkPrc($paDataWhere,$paHavePdtInWah){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocCode   = $paDataWhere['FTXshDocNo'];


        $this->db->set('FTXtdRmk','1');
        $this->db->where_in('FTPdtCode',$paHavePdtInWah);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXthDocNo',$tDocCode);
        $this->db->update('TCNTPdtTboDT');


        return $this->db->last_query();

    }

    public function FSxMTransferBchOutUpdatePdtStkPrcAll($paDataWhere,$ptStatus){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tDocCode   = $paDataWhere['FTXshDocNo'];


        if($ptStatus == '1'){
            $this->db->set('FTXtdRmk',$ptStatus);
        }else{
            $this->db->set('FTXtdRmk','');
        }
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXthDocNo',$tDocCode);
        $this->db->update('TCNTPdtTboDT');

        return $this->db->last_query();
    }

}
