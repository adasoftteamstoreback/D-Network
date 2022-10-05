<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Managedocpurchaseorder_model extends CI_Model {

    //ฟังก์ชั่น List
    public function FSaMMNPImportList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        
        // Advance Search
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchSplCodeFrom     = $aAdvanceSearch['tSearchSplFrom'];
        $tSearchSplCodeTo       = $aAdvanceSearch['tSearchSplTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];

        $tSQL = "SELECT 
                    COUNT(A.FTXphDocNo) OVER (PARTITION BY A.FTXphDocNo) AS PARTITIONBYDOC ,
                    A.* ,
                    MGTHD.FTXpdDocPo,
                    MGTHD.FTXrhDocRqSpl ,
                    MGTHD.FTXrhAgnTo ,
                    MGTHD.FTXrhBchTo ,
                    MGTHD.FTXrhStaApv    AS MGTStaApv ,
                    MGTHD.FTXrhStaPrcDoc AS MGTStaPrcDoc ,
                    TOSPL.FTSplCode      AS MGTSplCode ,
                    TOSPL.FTSplName      AS MGTSplName ,
                    BCHL_Frm.FTBchName   AS MGTBchName_Frm ,
                    BCHL_To.FTBchName    AS MGTBchName_To ,
                    FILEOBJ.FTFleObj
                FROM(
                    SELECT c.* FROM(
                        SELECT  
                            ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTBchCode DESC , FTXphDocNo DESC ) AS FNRowID  ,
                            * 
                        FROM (  
                            SELECT
                                DISTINCT     
                                PO.FTAgnCode ,
                                PO.FTBchCode ,
                                PO.FTXphDocNo ,
                                PO.FTXPhRefFile ,
                                PO.FTSplCode ,
                                PO.FNXphQtyBch ,
                                PO.FCXphQtyPdt ,
                                PO.FTXrhStaDoc ,
                                PO.FTXrhStaPrcDoc ,
                                PO.FTCreateBy,
                                PO.FDCreateOn
                            FROM TAPTPoMgtHD PO WITH (NOLOCK)
                            LEFT JOIN TAPTPoMgtHDDoc  MGTHD  WITH (NOLOCK) ON MGTHD.FTXphDocNo = PO.FTXphDocNo       
                            WHERE 1=1 ";

        // สถานะอนุมัติ (ใบขอโอน , ใบขอซื้อ)
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 0) { //ทั้งหมด
                $tSQL .= "";
            } elseif ($tSearchStaDoc == 1) { //รอยืนยัน
                $tSQL .= " AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaApv,'') = '' ) ";
            } elseif ($tSearchStaDoc == 2) { //ยืนยันแล้วรออนุมัติ
                $tSQL .= " AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = 1 AND ISNULL(MGTHD.FTXrhStaApv,'') = '' ) ";
            } elseif ($tSearchStaDoc == 3) { //อนุมัติแล้ว
                $tSQL .= "  AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = 1 AND ISNULL(MGTHD.FTXrhStaApv,'') = 1 ) ";
            }
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
                    ) AS A
                LEFT JOIN TAPTPoMgtHDDoc    MGTHD    WITH (NOLOCK) ON MGTHD.FTXphDocNo 	    = A.FTXphDocNo       
                LEFT JOIN TCNMBranch_L      BCHL_Frm WITH (NOLOCK) ON A.FTBchCode      		= BCHL_Frm.FTBchCode  AND BCHL_Frm.FNLngID      = $nLngID
                LEFT JOIN TCNMBranch_L      BCHL_To  WITH (NOLOCK) ON MGTHD.FTXrhBchTo      = BCHL_To.FTBchCode   AND BCHL_To.FNLngID       = $nLngID
                LEFT JOIN TCNMSpl_L         TOSPL    WITH (NOLOCK) ON MGTHD.FTSplCode	 	= TOSPL.FTSplCode	  AND TOSPL.FNLngID         = $nLngID
                LEFT JOIN TCNMFleObj 		FILEOBJ  WITH (NOLOCK) ON FILEOBJ.FTFleRefID1    = MGTHD.FTXpdDocPo   AND FILEOBJ.FTFleRefTable = 'TAPTPoHD' AND FILEOBJ.FTFleRefID2 = MGTHD.FTXphDocNo
                WHERE 1=1 ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND A.FTBchCode IN ($tBchCode) ";
        }

        // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((A.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL_Frm.FTBchName LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
        if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
            $tSQL .= " AND ((TOSPL.FTSplCode BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (TOSPL.FTSplCode BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom' ))";
        }

        $tSQL .= " ORDER BY A.FDCreateOn DESC , A.FTXphDocNo DESC ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMMNPCountPageDocListAll($paDataCondition);
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

    //จำนวน
    public function FSnMMNPCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchSplCodeFrom     = $aAdvanceSearch['tSearchSplFrom'];
        $tSearchSplCodeTo       = $aAdvanceSearch['tSearchSplTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSQL = " SELECT 
                    DISTINCT   
                    PO.FTXphDocNo
                FROM TAPTPoMgtHD            PO      WITH (NOLOCK)
                LEFT JOIN TAPTPoMgtHDDoc    MGTHD    WITH (NOLOCK) ON MGTHD.FTXphDocNo 	    = PO.FTXphDocNo       
                LEFT JOIN TCNMBranch_L      BCHL_Frm WITH (NOLOCK) ON PO.FTBchCode      	= BCHL_Frm.FTBchCode  AND BCHL_Frm.FNLngID      = $nLngID
                LEFT JOIN TCNMBranch_L      BCHL_To  WITH (NOLOCK) ON MGTHD.FTXrhBchTo      = BCHL_To.FTBchCode   AND BCHL_To.FNLngID       = $nLngID
                LEFT JOIN TCNMSpl_L         TOSPL    WITH (NOLOCK) ON MGTHD.FTSplCode	 	= TOSPL.FTSplCode	  AND TOSPL.FNLngID         = $nLngID
                WHERE 1=1 ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND PO.FTBchCode IN ($tBchCode) ";
        }

        // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((PO.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL_Frm.FTBchName LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
        if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
            $tSQL .= " AND ((TOSPL.FTSplCode BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (TOSPL.FTSplCode BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom' ))";
        }

        // สถานะอนุมัติ (ใบขอโอน , ใบขอซื้อ)
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 0) { //ทั้งหมด
                $tSQL .= "";
            } elseif ($tSearchStaDoc == 1) { //รอยืนยัน
                $tSQL .= " AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaApv,'') = '' ) ";
            } elseif ($tSearchStaDoc == 2) { //ยืนยันแล้วรออนุมัติ
                $tSQL .= " AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = 1 AND ISNULL(MGTHD.FTXrhStaApv,'') = '' ) ";
            } elseif ($tSearchStaDoc == 3) { //อนุมัติแล้ว
                $tSQL .= "  AND ISNULL(PO.FTXrhStaDoc,'') = 1  AND ( ISNULL(PO.FTXrhStaPrcDoc,'') = 1 AND ISNULL(MGTHD.FTXrhStaApv,'') = 1 ) ";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //ในรายการสินค้า Temp
    public function FSaMMNPGetDocDTTempListPage($paDataWhere){
        $tDocNo             = $paDataWhere['FTXthDocNo'];
        $tDocKey            = $paDataWhere['FTXthDocKey'];
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSesSessionID      = $this->session->userdata('tSesSessionID');
        $tSQL               = " SELECT C.* , T.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM (
                                    SELECT
                                        DOCTMP.FTXthDocNo,
                                        DOCTMP.FNXtdSeqNo,
                                        DOCTMP.FTXthDocKey,
                                        DOCTMP.FTBchCode,
                                        BCHL.FTBchName,
                                        DOCTMP.FTPdtCode,
                                        DOCTMP.FTXtdPdtName,
                                        UNIT.FTPunCode,
                                        UNIT.FTPunName,
                                        DOCTMP.FTXtdBarCode,
                                        DOCTMP.FTXtdDocNoRef,
                                        DOCTMP.FCStkQty,
                                        DOCTMP.FCXtdQty,
                                        DOCTMP.FTTmpStatus,
                                        DOCTMP.FTTmpRemark AS 'rtTextError',
                                        DOCTMP.FTSessionID,
                                        DOCTMP.FDCreateOn,
                                        BCH.FTBchType,
                                        DOCTMP.FTXtdRmk
                                    FROM TCNTDocDTTmp       DOCTMP WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch    BCH WITH (NOLOCK)       ON DOCTMP.FTBchCode = BCH.FTBchCode
                                    LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK)      ON DOCTMP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                                    LEFT JOIN TCNMPdtBar    PDTBar WITH (NOLOCK)    ON DOCTMP.FTPdtCode = PDTBar.FTPdtCode AND  DOCTMP.FTXtdBarCode = PDTBar.FTBarCode
                                    LEFT JOIN TCNMPdtUnit_L UNIT WITH (NOLOCK)      ON PDTBar.FTPunCode = UNIT.FTPunCode AND UNIT.FNLngID = $nLngID
                                    WHERE 1 = 1
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tDocNo'
                                    AND DOCTMP.FTXthDocKey = '$tDocKey'
                                    AND DOCTMP.FTSessionID = '$tSesSessionID' ";
        $tSQL               .= ") Base) AS C ";
        $tSQL               .= "LEFT JOIN ( 
                                    SELECT
                                        FTSessionID           	    AS FTUsrSession_Footer,
                                        COUNT(DISTINCT FTBchCode) 	AS count_BCH,
                                        SUM(ISNULL(FCXtdQty,0))     AS count_PDT
                                    FROM TCNTDocDTTmp DOCTMP WITH(NOLOCK)
                                    WHERE 1=1
                                    AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tDocNo'
                                    AND DOCTMP.FTXthDocKey = '$tDocKey'
                                    AND DOCTMP.FTSessionID = '$tSesSessionID' AND
                                    (DOCTMP.FTTmpStatus   = '1' AND DOCTMP.FTTmpStatus != 'DUP')
                                    GROUP BY FTSessionID ) T ON C.FTSessionID = T.FTUsrSession_Footer ";

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

    //Insert ข้อมูลใน Temp
    public function FSaMMNPImportExcelToTmp($paWhereData,$paInsPackdata){

        $tDocNo     = $paInsPackdata[0]['FTXthDocNo'];
        $tSessionID = $paWhereData['tSessionID'];
        $tDocKey    = $paWhereData['tKey'];

        //ลบข้อมูลก่อน
        $this->db->where_in('FTXthDocKey'   , $tDocKey);
        $this->db->where_in('FTSessionID'   , $tSessionID);
        $this->db->delete($paWhereData['tTableRef']);

        //เพิ่มข้อมูล
        $this->db->insert_batch($paWhereData['tTableRef'], $paInsPackdata);

        //เช็คว่าเลขที่เอกสารใบขอซื้อซ้ำไหม
        $tSQL = "UPDATE TCNTDocDTTmp
                    SET FTTmpStatus = B.CheckfieldDUP ,
                        FTTmpRemark = '$&อ้างอิงเลขที่ใบขอซื้อผู้จำหน่ายซ้ำ'
                FROM(
                    SELECT
                        CASE  
                            WHEN ISNULL(HDDUP.FTXrhDocRqSpl,'') = '' THEN '1'
                            ELSE '2'
                        END AS CheckfieldDUP,
                HDDUP.FTXrhDocRqSpl
                FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                LEFT JOIN TAPTPoMgtHDDoc HDDUP ON TMP.FTXtdDocNoRef = HDDUP.FTXrhDocRqSpl
                WHERE TMP.FTXthDocNo   = '$tDocNo' 
                AND TMP.FTXthDocKey    = '$tDocKey'
                AND TMP.FTSessionID    = '$tSessionID'
            ) AS B
            WHERE TCNTDocDTTmp.FTXtdDocNoRef = B.FTXrhDocRqSpl
            AND B.CheckfieldDUP = '2'
            AND TCNTDocDTTmp.FTXthDocKey    = '$tDocKey'
            AND TCNTDocDTTmp.FTXthDocNo     = '$tDocNo'
            AND TCNTDocDTTmp.FTSessionID    = '$tSessionID'";
        $this->db->query($tSQL);

        //อัพเดท Factor กับ QTYALL
        $tSQL = "UPDATE HD
                 SET 
                    HD.FCXtdFactor = B.FCPdtUnitFact ,
                    HD.FCXtdQtyAll = B.QTYAll ,
                    HD.FTPunCode = B.FTPunCode ,
                    HD.FTPunName = B.FTPunName 
                 FROM TCNTDocDTTmp AS HD WITH(NOLOCK)
                 LEFT JOIN (
                    SELECT
                        TMP.FTXthDocNo ,
                        TMP.FNXtdSeqNo ,
                        PDTBar.FTPunCode ,
                        PACK.FCPdtUnitFact ,
                        UNIT.FTPunName ,
                        PACK.FCPdtUnitFact * TMP.FCXtdQty AS QTYAll ,
                        TMP.FTSessionID
                    FROM TCNTDocDTTmp TMP WITH(NOLOCK)
                    LEFT JOIN TCNMPdtBar PDTBar ON TMP.FTXtdBarCode = PDTBar.FTBarCode AND TMP.FTPdtCode = PDTBar.FTPdtCode
                    LEFT JOIN TCNMPdtPackSize PACK ON TMP.FTPdtCode = PACK.FTPdtCode AND PDTBar.FTPunCode = PACK.FTPunCode
                    LEFT JOIN TCNMPdtUnit_L UNIT ON PDTBar.FTPunCode = UNIT.FTPunCode 
                    WHERE TMP.FTXthDocNo   = '$tDocNo' 
                    AND TMP.FTXthDocKey    = '$tDocKey'
                    AND TMP.FTSessionID    = '$tSessionID'
                ) B 
                ON B.FTXthDocNo = HD.FTXthDocNo
                AND B.FTSessionID = HD.FTSessionID 
                AND B.FNXtdSeqNo = HD.FNXtdSeqNo 
                WHERE HD.FTXthDocNo       = '$tDocNo' 
                    AND HD.FTXthDocKey    = '$tDocKey'
                    AND HD.FTSessionID    = '$tSessionID' ";
        $this->db->query($tSQL);
    }

    //ลบข้อมูลใน Temp [หลายรายการ]
    public function FSaMMNPPdtTmpMultiDel($paData){
        try{
            $this->db->trans_begin();

            //Del DTTmp
            $this->db->where('FTXthDocNo', $paData['FTXthDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->where('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');
    
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //ลบข้อมูลใน Temp [รายการเดียว]
    public function FSaMMNPPdtTmpSingleDel($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTXthDocNo', $paData['FTXphDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXpdSeqNo']);
            $this->db->where_in('FTPdtCode',  $paData['FTPdtCode']);
            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->where_in('FTXthDocKey', $paData['FTXthDocKey']);
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

    //เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม where session
    public function FSaMMNPDeletePDTInTmp(){
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

    ////////////////////////////////////////////// เข้าหน้าแก้ไข //////////////////////////////////////////////

    //ข้อมูล HD
    public function FSaMMNPGetDataDocHD($paDataWhere){
        $tDocNo     = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            HDDoc.FTXrhDocRqSpl,
                            DOCHD.FTXphDocNo,
                            DOCHD.FTXPhRefFile,
                            DOCHD.FTXrhStaDoc,
                            DOCHD.FTXrhStaPrcDoc,
                            BCHL.FTBchCode,
                            BCHL.FTBchName,
                            SPL_L.FTSplCode,
                            SPL_L.FTSplName,
                            USRL.FTUsrName
                        FROM TAPTPoMgtHD DOCHD WITH (NOLOCK)
                        LEFT JOIN TAPTPoMgtHDDoc    HDDoc   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = HDDoc.FTXphDocNo
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTCreateBy     = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMSpl_L         SPL_L   WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL_L.FTSplCode   AND SPL_L.FNLngID	= $nLngID
                        WHERE 1=1 AND DOCHD.FTXphDocNo = '$tDocNo' ";

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

    //Move DT To Temp
    public function  FSaMMNPMoveDTToTemp($ptDocumentNumber){
        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,
                        FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,
                        FCXtdFactor,FTXtdBarCode,
                        FTTmpStatus,FCStkQty,FCXtdQty,FCXtdQtyAll,FTSessionID,
                        FDCreateOn,FTXtdDocNoRef,FTXtdRmk)
                    SELECT
                        HD.FTXrhBchTo  ,
                        '$ptDocumentNumber' , 
                        ROW_NUMBER() OVER(ORDER BY DT.FTXphDocNo DESC) AS FNXpdSeqNo ,   
                        'TAPTPoMgtDT' ,
                        DT.FTPdtCode , 
                        DT.FTXpdPdtName , 
                        DT.FTPunCode , 
                        DT.FTPunName , 
                        DT.FCXpdFactor , 
                        DT.FTXpdBarCode , 
                        1 ,
                        DT.FCXpdQtyPRS ,
                        DT.FCXpdQty , 
                        DT.FCXpdQtyAll , 
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        DT.FDCreateOn ,
                        HD.FTXrhDocRqSpl ,
                        DT.FTXpdRmk
                    FROM TAPTPoMgtHDDoc AS HD WITH (NOLOCK)
                    LEFT JOIN TAPTPoMgtDT AS DT ON HD.FTXphDocNo = DT.FTXphDocNo AND HD.FNXpdSeqNo = DT.FNXpdSeqNo
                    WHERE 1=1 AND HD.FTXphDocNo = '$ptDocumentNumber'
                    ORDER BY DT.FTXphDocNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    ////////////////////////////////////////////// บันทึกข้อมูล //////////////////////////////////////////////

    //ข้อมูล HD ลบและ เพิ่มใหม่
    public function FSxMMNPAddUpdateHD($paData){

        // Delete HD
        $this->db->where_in('FTBchCode',$paData['FTBchCode']);
        $this->db->where_in('FTXphDocNo',$paData['FTXphDocNo']);
        $this->db->delete('TAPTPoMgtHD');

        // Insert HD 
        $this->db->insert('TAPTPoMgtHD',$paData);
        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp 
    public function FSxMMNPAddUpdateDocNoToTemp($paData){
        $tSessionID  = $this->session->userdata('tSesSessionID');

        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','DUMMY');
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FTXthDocKey','TAPTPoMgtDT');
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paData['FTXphDocNo']
        ));
        return;
    }

    //Move Temp To TAPTPoMgtHDDoc
    public function FSaMMNPMoveDTTmpToHDDoc($paData){

        $tSessionID  = $this->session->userdata('tSesSessionID');
        $tBCHCode    = $paData['FTBchCode'];
        $tAGNCode    = $paData['FTAgnCode'];
        $tDocNo      = $paData['FTXphDocNo'];
        $tSPLCode    = $paData['FTSplCode'];

        // Delete HDDoc
        $this->db->where_in('FTBchCode',$tBCHCode);
        $this->db->where_in('FTXphDocNo',$tDocNo);
        $this->db->delete('TAPTPoMgtHDDoc');

        // Insert 
        $tSQL   = " INSERT INTO TAPTPoMgtHDDoc (
                        FTAgnCode , FTBchCode , FTXphDocNo ,
                        FTSplCode , FTXrhDocRqSpl , FTXrhAgnTo , FTXrhBchTo ,
                        FTXrhStaApv , FTXrhStaPrcDoc , FTXphRmk ,
                        FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy ,
                        FTXpdDocPo , FNXpdSeqNo )
                    
                    SELECT 
                        A.* ,
                        CONCAT('PO',ROW_NUMBER() OVER(ORDER BY A.FTBchCode DESC),A.FTXrhDocRqSpl,'-#####')  AS FTXpdDocPo , 
                        ROW_NUMBER() OVER(ORDER BY A.FTBchCode DESC) AS FNXpdSeqNo  
                    FROM(
                        SELECT 
                            DISTINCT
                            '$tAGNCode'         AS FTAgnCode , 
                            '$tBCHCode'         AS FTBchCode , 
                            TEMP.FTXthDocNo     AS FTXphDocNo ,
                            '$tSPLCode'         AS FTSplCode , 
                            TEMP.FTXtdDocNoRef  AS FTXrhDocRqSpl ,
                            ''                  AS FTXrhAgnTo ,
                            TEMP.FTBchCode      AS FTXrhBchTo ,  
                            null                AS FTXrhStaApv ,
                            null 				AS FTXrhStaPrcDoc ,
                            null                AS FTXphRmk ,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy
                        FROM TCNTDocDTTmp TEMP
                        WHERE 
                            FTXthDocKey = 'TAPTPoMgtDT' AND 
                            FTSessionID = '$tSessionID' AND
                            FTXthDocNo  = '$tDocNo' AND
                            (FTTmpStatus   = '1' AND FTTmpStatus != 'DUP')
                    ) AS A ";
        $this->db->query($tSQL);
    }

    //Move Temp To TAPTPoMgtDT
    public function FSaMMNPMoveDTTmpToDT($paData){
        $tSessionID  = $this->session->userdata('tSesSessionID');
        $nLngID      = $this->session->userdata("tLangEdit");
        $tBCHCode    = $paData['FTBchCode'];
        $tAGNCode    = $paData['FTAgnCode'];
        $tDocNo      = $paData['FTXphDocNo'];

        // Delete DT
        $this->db->where_in('FTBchCode',$tBCHCode);
        $this->db->where_in('FTXphDocNo',$tDocNo);
        $this->db->delete('TAPTPoMgtDT');

        //Insert
        $tSQL   = " INSERT INTO TAPTPoMgtDT (
                        FTXphDocNo , FTAgnCode, FTBchCode  ,
                        FTPdtCode , FTXpdPdtName , FCXpdQtyTR ,  
                        FTPunCode , FTPunName , FCXpdFactor , FCXpdQtyPRS , 
                        FTXpdBarCode , FCXpdQty , FCXpdQtyAll , FTXpdRmk , FDLastUpdOn ,
                        FTLastUpdBy , FDCreateOn , FTCreateBy , FNXpdSeqNo , FNXppSeqNo )
                    SELECT 
                        A.rtDocPo ,
                        A.FTAgnCode,
                        A.FTBchCode,
                        A.FTPdtCode,
                        A.FTXpdPdtName,
                        A.FCXpdQtyTR,
                        A.FTPunCode,
                        A.FTPunName,
                        A.FCXpdFactor,
                        A.FCXpdQtyPRS,
                        A.FTXpdBarCode,
                        A.FCXpdQty,
                        A.FCXpdQtyAll,
                        A.FTXtdRmk ,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy,
                        A.rnSeqNo AS FNXpdSeqNo ,
                        ROW_NUMBER() OVER (PARTITION BY FTXrhBchTo ORDER BY FTXrhBchTo )     AS FNXppSeqNo  
                    FROM(
                        SELECT 
                            HDDoc.FTXphDocNo	AS rtDocPo ,
                            HDDoc.FNXpdSeqNo	As rnSeqNo ,
                            TEMP.FTBchCode      AS FTXrhBchTo ,
                            '$tAGNCode'         AS FTAgnCode , 
                            '$tBCHCode'         AS FTBchCode , 
                            TEMP.FTXthDocNo     AS FTXphDocNo ,
                            TEMP.FTPdtCode      AS FTPdtCode , 
                            TEMP.FTXtdPdtName   AS FTXpdPdtName ,
                            null                AS FCXpdQtyTR ,
                            BAR.FTPunCode       AS FTPunCode,
                            UNITL.FTPunName     AS FTPunName,
                            TEMP.FCXtdFactor    AS FCXpdFactor,
                            TEMP.FCStkQty       AS FCXpdQtyPRS , --จำนวนจากใบขอซื้อ
                            TEMP.FTXtdBarCode   AS FTXpdBarCode , 
                            TEMP.FCXtdQty       AS FCXpdQty ,  --จำนวนที่ SPL ยืนยัน
                            TEMP.FCXtdQtyAll    AS FCXpdQtyAll ,
                            TEMP.FTXtdRmk       AS FTXtdRmk 
                        FROM TCNTDocDTTmp TEMP
                        LEFT JOIN TAPTPoMgtHDDoc HDDoc ON TEMP.FTBchCode = HDDoc.FTXrhBchTo AND HDDoc.FTXphDocNo = TEMP.FTXthDocNo AND HDDoc.FTXrhDocRqSpl = TEMP.FTXtdDocNoRef 
                        LEFT JOIN TCNMPdtBar     BAR ON TEMP.FTXtdBarCode  = BAR.FTBarCode AND TEMP.FTPdtCode = BAR.FTPdtCode
                        LEFT JOIN TCNMPdtUnit_L  UNITL ON BAR.FTPunCode = UNITL.FTPunCode AND UNITL.FNLngID = $nLngID
                        WHERE 
                            TEMP.FTXthDocKey = 'TAPTPoMgtDT' AND 
                            TEMP.FTSessionID = '$tSessionID' AND
                            TEMP.FTXthDocNo  = '$tDocNo' AND
                            HDDoc.FTXphDocNo = '$tDocNo' AND
                            (FTTmpStatus    = '1' AND FTTmpStatus != 'DUP')
                    ) AS A ";
        $this->db->query($tSQL);
    }

    //ยกเลิกเอกสาร
    public function FSaMMNPUpdateStaDocCancel($paDataUpdate){
        try {
            $this->db->set('FDLastUpdOn', date('Y-m-d H:i:s'));
            $this->db->set('FTLastUpdBy', $this->session->userdata('tSesUsername'));
            $this->db->set('FTXrhStaDoc', $paDataUpdate['FTXrhStaDoc']);
            $this->db->where('FTXphDocNo', $paDataUpdate['FTXphDocNo']);
            $this->db->update('TAPTPoMgtHD');

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
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //Get ข้อมูล เพื่อส่งไป MQ
    public function FSaMMNPGetDocRefCallMQ($ptTextDocRef){
        $tSQL = "SELECT HD.FTXpdDocPo , HD.FTBchCode FROM TAPTPoMgtHDDoc HD WHERE FTXphDocNo IN ($ptTextDocRef) ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }

        return $aResult;
    }

    //Get ข้อมูลเพื่อส่งออก
    public function FSaMMNPGetDocRefForExport($ptTextDocRef){
        $tTextDocRef    = $ptTextDocRef['tTextDocRef'];
        $tSQL           = "SELECT HD.FTBchCode , HD.FTXpdDocPo FROM TAPTPoMgtHDDoc HD WHERE FTXphDocNo IN ($tTextDocRef) ";
        $oQuery         = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }

        return $aResult;
    }

    //อัพเดทสาขาใหม่ ตาม seq
    public function FSaMMNPUpdateSeqInTemp($parem){
        $tBCHCode          = $parem['tBCHCode'];
        $nSeqNo            = $parem['nSeqNo'];

        $tSessionID  = $this->session->userdata('tSesSessionID');

        // Update DocNo Into DTTemp
        $this->db->where('FTSessionID',$tSessionID);
        $this->db->where('FNXtdSeqNo',$nSeqNo);
        $this->db->where('FTXthDocKey','TAPTPoMgtDT');
        $this->db->update('TCNTDocDTTmp',array(
            'FTBchCode'    => $tBCHCode,
            'FTXtdRmk'     => 'ChangeBCH'
        ));
        return;
    }

}