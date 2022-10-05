<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplierpurchaserequisition_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตารางหน้า List [TAB : ใบขอซื้อผู้จำหน่าย]
    public function FSaMPRSGetDataTableList($paDataCondition){
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
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSearchStaPrcDoc       = $aAdvanceSearch['tSearchStaPrcDoc'];

        $tSQL   =   "   SELECT 			
                            A.* , 
                            COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY A.FTXphDocNo)  AS PARTITIONBYDOC  , 
                            HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF' ,
                            CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF' 
                         FROM ( ";
        $tSQL   .=   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        PRSHD.FTBchCode,
                                        BCHL.FTBchName,
                                        PRSHD.FTXphDocNo,
                                        CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) AS FDXphDocDate,
                                        CONVERT(CHAR(5), PRSHD.FDXphDocDate,108) AS FTXshDocTime,
                                        PRSHD.FTXphStaDoc,
                                        PRSHD.FTXphStaApv,
                                        PRSHD.FNXphStaRef,
                                        SPL.FTSplName,
                                        PRSHD.FTCreateBy,
                                        USRCREB.FTUsrName                               AS CreateByName,
                                        PRSHD.FDCreateOn,
                                        PRSHD.FNXphStaDocAct,
                                        AGN_L.FTAgnName,
                                        USRL.FTUsrName                                  AS FTCreateByName,
                                        USRLAPV.FTUsrName                               AS FTXshApvName,
                                        PRSHD.FTXphApvCode,
                                        PRSHD.FTXphStaPrcDoc            
                                    FROM TCNTPdtReqSplHD        PRSHD       WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L      BCHL        WITH (NOLOCK) ON PRSHD.FTBchCode    = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRL        WITH (NOLOCK) ON PRSHD.FTUsrCode    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRLAPV     WITH (NOLOCK) ON PRSHD.FTXphApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNMUser_L        USRCREB 	WITH (NOLOCK) ON PRSHD.FTCreateBy	= USRCREB.FTUsrCode	AND USRCREB.FNLngID	= $nLngID
                                    LEFT JOIN TCNTPdtReqSplHDDocRef  PRBREF WITH (NOLOCK) ON PRBREF.FTXshDocNo  = PRSHD.FTXphDocNo  AND PRBREF.FTXshRefType = 1
                                    INNER JOIN TCNMSpl_L        SPL         WITH (NOLOCK) ON PRSHD.FTSplCode    = SPL.FTSplCode     AND SPL.FNLngID     = $nLngID
                                    LEFT JOIN TCNMAgency_L      AGN_L       WITH (NOLOCK) ON PRSHD.FTAgnCode    = AGN_L.FTAgnCode   AND AGN_L.FNLngID   = $nLngID 
                                WHERE 1=1  "; //ต้องมองไม่เห็นเอกสารของแฟรนไซส์

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND PRSHD.FTBchCode IN ($tBchCode) ";
        }   

        //ถ้าเป็นการเข้าใช้งานแบบ AGN จะเห็นใบขอซื้อของตัวเองด้วย
        if($this->session->userdata("bIsHaveAgn") == true){

        }else{
            $tSQL .= " AND PRSHD.FNXphDocType != 12 ";
        }
        
        // หารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((PRSHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((PRSHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PRSHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND PRSHD.FTXphStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(PRSHD.FTXphStaApv,'') = '' AND PRSHD.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND PRSHD.FTXphStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND PRSHD.FTXphStaApv = '$tSearchStaApprove' OR PRSHD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND PRSHD.FTXphStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 0";
            }
        }

        $tSQL .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL .= " ) AS A LEFT JOIN TCNTPdtReqSplHDDocRef HDDocRef_in WITH (NOLOCK) ON A.FTXphDocNo = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1 ";
        $tSQL .= " ORDER BY A.FNRowID ASC " ;
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMPRSCountPageDocListAll($paDataCondition);
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

    // จำนวน [TAB : ใบขอซื้อผู้จำหน่าย]
    public function FSnMPRSCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSearchStaPrcDoc       = $aAdvanceSearch['tSearchStaPrcDoc'];
    
        $tSQL   =   "   SELECT COUNT (PRSHD.FTXphDocNo) AS counts
                        FROM TCNTPdtReqSplHD PRSHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON PRSHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1 "; //ต้องมองไม่เห็นเอกสารของแฟรนไซส์
    
        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND PRSHD.FTBchCode IN ($tBchCode) ";
        }

        //ถ้าเป็นการเข้าใช้งานแบบ AGN จะเห็นใบขอซื้อของตัวเองด้วย
        if($this->session->userdata("bIsHaveAgn") == true){

        }else{
            $tSQL .= " AND PRSHD.FNXphDocType != 12 ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((PRSHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((PRSHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PRSHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND PRSHD.FTXphStaApv = '$tSearchStaApprove' OR PRSHD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND PRSHD.FTXphStaApv = '$tSearchStaApprove'";
            }
        }
    
        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 0";
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

    //---------------------------------------------------------------------------------------//

    // ดึงข้อมูลมาแสดงบนตารางหน้า List [TAB : ใบขอซื้อจากแฟรนไชส์]
    public function FSaMPRSGetDataTableList_FN($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSearchStaPrcDoc       = $aAdvanceSearch['tSearchStaPrcDoc'];

        $tSQL   =   "   SELECT 			
                            A.* , 
                            COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY A.FTXphDocNo)  AS PARTITIONBYDOC  , 
                            HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF' ,
                            CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF' 
                         FROM ( ";
        $tSQL   .=   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        PRSHD.FTBchCode,
                                        BCHL.FTBchName,
                                        CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) AS FDXphDocDate,
                                        CONVERT(CHAR(5), PRSHD.FDXphDocDate,108) AS FTXshDocTime,
                                        PRSHD.FTXphDocNo,
                                        PRSHD.FTXphStaDoc,
                                        PRSHD.FTXphStaApv,
                                        PRSHD.FNXphStaRef,
                                        SPL.FTSplName,
                                        AGN_L.FTAgnName,
                                        PRSHD.FTCreateBy,
                                        USRCREB.FTUsrName                   AS CreateByName,
                                        PRSHD.FDCreateOn,
                                        PRSHD.FNXphStaDocAct,
                                        USRL.FTUsrName                      AS FTCreateByName,
                                        USRLAPV.FTUsrName                   AS FTXshApvName,
                                        PRSHD.FTXphApvCode,
                                        PRSHD.FTXphStaPrcDoc
                                    FROM TCNTPdtReqSplHD            PRSHD   WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L          BCHL    WITH (NOLOCK) ON PRSHD.FTBchCode     = BCHL.FTBchCode       AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L            USRL    WITH (NOLOCK) ON PRSHD.FTUsrCode     = USRL.FTUsrCode       AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L            USRLAPV WITH (NOLOCK) ON PRSHD.FTXphApvCode  = USRLAPV.FTUsrCode    AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNMUser_L            USRCREB	WITH (NOLOCK) ON PRSHD.FTCreateBy	 = USRCREB.FTUsrCode	AND USRCREB.FNLngID	= $nLngID
                                    INNER JOIN TCNMSpl_L            SPL     WITH (NOLOCK) ON PRSHD.FTSplCode     = SPL.FTSplCode        AND SPL.FNLngID     = $nLngID
                                    LEFT JOIN TCNMAgency_L          AGN_L   WITH (NOLOCK) ON PRSHD.FTAgnCode     = AGN_L.FTAgnCode      AND AGN_L.FNLngID   = $nLngID 
                                WHERE 1=1 AND PRSHD.FNXphDocType = 12 AND PRSHD.FTXphStaApv = 1 ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND PRSHD.FTBchCode IN ($tBchCode) ";
        }
        
        // หารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((PRSHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((PRSHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PRSHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // สถานะยืนยัน
        if(isset($tSearchStaPrcDoc) && !empty($tSearchStaPrcDoc)){
            if ($tSearchStaPrcDoc == 1) {
                $tSQL .= " AND ISNULL(PRSHD.FTXphStaPrcDoc,'') = '2' ";
            } elseif ($tSearchStaPrcDoc == 2) {
                $tSQL .= " AND PRSHD.FTXphStaPrcDoc = '3' ";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 0";
            }
        }

        $tSQL .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $tSQL .= " ) AS A LEFT JOIN TCNTPdtReqSplHDDocRef HDDocRef_in WITH (NOLOCK) ON A.FTXphDocNo = HDDocRef_in.FTXshDocNo AND HDDocRef_in.FTXshRefType = 1 ";
        $tSQL .= " ORDER BY A.FNRowID ASC " ;
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMPRSCountPageDocListAll_FN($paDataCondition);
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

    // จำนวน [TAB : ใบขอซื้อจากแฟรนไชส์]
    public function FSnMPRSCountPageDocListAll_FN($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaDocAct'];
        $tSearchStaPrcDoc       = $aAdvanceSearch['tSearchStaPrcDoc'];
    
        $tSQL   =   "   SELECT COUNT (PRSHD.FTXphDocNo) AS counts
                        FROM TCNTPdtReqSplHD PRSHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON PRSHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1 AND PRSHD.FNXphDocType = 12  AND PRSHD.FTXphStaApv = 1  ";
    
        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND PRSHD.FTBchCode = '$tUserLoginBchCode' ";
        }
    
        // หารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((PRSHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PRSHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((PRSHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PRSHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }
    
        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PRSHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // สถานะยืนยัน
        if(isset($tSearchStaPrcDoc) && !empty($tSearchStaPrcDoc)){
            if ($tSearchStaPrcDoc == 1) {
                $tSQL .= " AND ISNULL(PRSHD.FTXphStaPrcDoc,'') = '2' ";
            } elseif ($tSearchStaPrcDoc == 2) {
                $tSQL .= " AND PRSHD.FTXphStaPrcDoc = '3' ";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND PRSHD.FNXphStaDocAct = 0";
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

    //---------------------------------------------------------------------------------------//

    // Get User Branch Detail.
    public function FSaMPRSGetDetailUserBranch($paBchCode){
        if(!empty($paBchCode)){
            $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
            $aReulst['item'] = $aReustl;
            $aReulst['code'] = 1;
            $aReulst['msg'] = 'Success !';
        }else{
            $aReulst['code'] = 2;
            $aReulst['msg'] = 'Error !';
        }
        return $aReulst;
    }

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม ใน DTTemp โดย where session
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

    // Delete PRS Order Document
    public function FSxMPRSClearDataInDocTemp($paWhereClearTemp){
        $tPRSDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tPRSDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tPRSSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tPRSDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tPRSSessionID' ";
        $this->db->query($tClearDocTemp);

        // Query Delete Doc HD Discount Temp
        $tClearDocHDDisTemp =   "   DELETE FROM TCNTDocHDDisTmp
                                    WHERE 1=1
                                    AND TCNTDocHDDisTmp.FTSessionID = '$tPRSSessionID'
        ";
        $this->db->query($tClearDocHDDisTemp);

        // Query Delete Doc DT Discount Temp
        $tClearDocDTDisTemp =   "   DELETE FROM TCNTDocDTDisTmp
                                    WHERE 1=1
                                    AND TCNTDocDTDisTmp.FTSessionID = '$tPRSSessionID'
        ";
        $this->db->query($tClearDocDTDisTemp);

        // Query Delete Doc Ref Temp
        $tClearDocRefTemp =   "   DELETE FROM TCNTDocHDRefTmp
                                    WHERE 1=1
                                    AND TCNTDocHDRefTmp.FTSessionID = '$tPRSSessionID' ";
        $this->db->query($tClearDocRefTemp);
    }

    // หาว่า ถ้าเป็นแฟรนไซด์ จะต้องไปเอาผู้จำหน่ายใน config
    public function FSxMPRSFindSPLByConfig(){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT
                            CON.FTSysStaUsrValue    AS rtSPLCode,
                            SPLL.FTSplName          AS rtSPLName
                        FROM TSysConfig             CON     WITH (NOLOCK)
                        LEFT JOIN TCNMSpl_L         SPLL    WITH (NOLOCK) ON CON.FTSysStaUsrValue = SPLL.FTSplCode  AND SPLL.FNLngID = '$nLngID'
                        WHERE CON.FTSysCode = 'tCN_FCSupplier' AND CON.FTSysApp = 'CN' AND CON.FTSysSeq = 1 ";
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($oQuery);
        return $aResult;
    }

    //ย้ายข้อมูลจาก TempHDDocRef => ตารางจริง
    public function FSxMPRSMoveHDRefTmpToHDRef($paDataWhere,$paTableAddUpdate){
        $tBchCode     = $paDataWhere['FTBchCode'];
        $tDocNo       = $paDataWhere['FTXphDocNo'];
        $tAgnCode     = $paDataWhere['FTAgnCode'];
        $tSessionID   = $this->session->userdata('tSesSessionID');
        $tTableHD     = $paTableAddUpdate['tTableHD'];

        // [PRS]
        if(isset($tDocNo) && !empty($tDocNo)){
            $this->db->where('FTBchCode',$tBchCode);
            $this->db->where('FTXshDocNo',$tDocNo);
            $this->db->delete('TCNTPdtReqSplHDDocRef');
        }
        $tSQL   =   "   INSERT INTO TCNTPdtReqSplHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '$tAgnCode' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthDocNo,
                            FTXthRefDocNo,
                            FTXthRefType,
                            FTXthRefKey,
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '$tTableHD'
                          AND FTSessionID = '$tSessionID' ";
        $this->db->query($tSQL);

        //Insert ใบสั่งสินค้าสำนักงานใหญ่ [PRB]
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTXshRefDocNo',$tDocNo);
        $this->db->delete('TCNTPdtReqBchHDDocRef');
        $tSQL   =   "   INSERT INTO TCNTPdtReqBchHDDocRef (FTAgnCode, FTBchCode, FTXshDocNo, FTXshRefDocNo, FTXshRefType, FTXshRefKey, FDXshRefDocDate) ";
        $tSQL   .=  "   SELECT
                            '$tAgnCode' AS FTAgnCode,
                            '$tBchCode' AS FTBchCode,
                            FTXthRefDocNo AS FTXshDocNo,
                            FTXthDocNo AS FTXshRefDocNo,
                            2,
                            'PRS',
                            FDXthRefDocDate
                        FROM TCNTDocHDRefTmp WITH (NOLOCK)
                        WHERE FTXthDocNo  = '$tDocNo'
                          AND FTXthDocKey = '$tTableHD'
                          AND FTSessionID = '$tSessionID'
                          AND FTXthRefKey = 'PRHQ'  ";
        $this->db->query($tSQL);
    }

    // Delete PRS Order Document
    public function FSxMPRSClearDataInDocTempForImp($paWhereClearTemp){
        $tPRSDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tPRSDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tPRSSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp 
                                WHERE 1=1 
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tPRSDocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tPRSDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tPRSSessionID'
                                AND TCNTDocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
    }

    //ข้อมูล HDDocRef
    public function FSxMPRSMoveHDRefToHDRefTemp($paData){

        $FTXshDocNo     = $paData['FTXphDocNo'];
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
                        'TCNTPdtReqSplHD' AS FTXthDocKey,
                        '$FTSessionID' AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn
                    FROM TCNTPdtReqSplHDDocRef
                    WHERE FTXshDocNo = '$FTXshDocNo' ";
        $this->db->query($tSQL);
    }
    
    // Get Data In Doc DT Temp
    public function FSaMPRSGetDocDTTempListPage($paDataWhere){
        $tPRSDocNo           = $paDataWhere['FTXthDocNo'];
        $tPRSDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tPRSSesSessionID    = $this->session->userdata('tSesSessionID');

        $aRowLen    = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);

        $tSQL       = " SELECT c.* FROM(
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
                                    DOCTMP.FTTmpRemark,
                                    DOCTMP.FCXtdVatRate,
                                    DOCTMP.FTXtdVatType,
                                    DOCTMP.FTSrnCode,
                                    DOCTMP.FDLastUpdOn,
                                    DOCTMP.FDCreateOn,
                                    DOCTMP.FTLastUpdBy,
                                    DOCTMP.FTCreateBy,
                                    DOCTMP.FCXtdQtyOrd
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                -- LEFT JOIN TCNMImgPdt IMGPDT on DOCTMP.FTPdtCode = IMGPDT.FTImgRefID AND IMGPDT.FTImgTable='TCNMPdt'
                                WHERE 1 = 1
                                AND DOCTMP.FTXthDocKey = '$tPRSDocKey'
                                AND DOCTMP.FTSessionID = '$tPRSSesSessionID' ";
        if(isset($tPRSDocNo) && !empty($tPRSDocNo)){
            $tSQL   .=  " AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tPRSDocNo' ";
        }

        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "   AND (
                                DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%' )
                        ";
            
        }
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aFoundRow  = $this->FSaMPRSGetDocDTTempListPageAll($paDataWhere);
            $nFoundRow  = ($aFoundRow['rtCode'] == '1')? $aFoundRow['rtCountData'] : 0;
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

    // Count All Document DT Temp
    public function FSaMPRSGetDocDTTempListPageAll($paDataWhere){
        $tPRSDocNo           = $paDataWhere['FTXthDocNo'];
        $tPRSDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tPRSSesSessionID    = $this->session->userdata('tSesSessionID');

        $tSQL   = " SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP
                    WHERE 1 = 1 ";
        
        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tPRSDocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tPRSDocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tPRSSesSessionID' ";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Get Data Pdt
    public function FSaMPRSGetDataPdt($paDataPdtParams){
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
                        INNER JOIN (
                            SELECT A.* FROM(
                                SELECT  
                                    ROW_NUMBER() OVER (PARTITION BY FTVatCode ORDER BY FDVatStart DESC) AS RowNumber , 
                                    FTVatCode , 
                                    FCVatRate 
                                FROM TCNMVatRate where CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart 
                            ) AS A WHERE A.RowNumber = 1 
                        ) VAT ON PDT.FTVatCode = VAT.FTVatCode
                        LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
                        LEFT JOIN TCNMPdtSpl SPL        WITH (NOLOCK)   ON PDT.FTPdtCode    = SPL.FTPdtCode AND BAR.FTBarCode = SPL.FTBarCode
                        LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
                        WHERE 1 = 1 ";
    
        if(isset($tPdtCode) && !empty($tPdtCode)){
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }

        if(isset($FTPunCode) && !empty($FTPunCode)){
            $tSQL   .= " AND PKS.FTPunCode = '$FTPunCode'";
        }

        if(isset($FTBarCode) && !empty($FTBarCode)){
            $tSQL   .= " AND BAR.FTBarCode = '$FTBarCode'";
        }

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

    // Insert Pdt To Doc DT Temp
    public function FSaMPRSInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPRSDataPdt    = $paDataPdtMaster['raItem'];
        if ($paDataPdtParams['tPRSOptionAddPdt'] == 1) {

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
                            AND FTPdtCode       = '".$paPRSDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paPRSDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                
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
                                    AND FTPdtCode       = '".$paPRSDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paPRSDataPdt["FTBarCode"]."'
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
                        'FTPdtCode'         => $paPRSDataPdt['FTPdtCode'],
                        'FTXtdPdtName'      => $paPRSDataPdt['FTPdtName'],
                        'FCXtdFactor'       => $paPRSDataPdt['FCPdtUnitFact'],
                        'FTPunCode'         => $paPRSDataPdt['FTPunCode'],
                        'FTPunName'         => $paPRSDataPdt['FTPunName'],
                        'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                        'FTXtdVatType'      => $paPRSDataPdt['FTPdtStaVatBuy'],
                        // 'FTXtdVatType'      => $paPRSDataPdt['FTPdtStaVat'],
                        'FTVatCode'         => $paDataPdtParams['nVatCode'],
                        'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                        'FTXtdStaAlwDis'    => $paPRSDataPdt['FTPdtStaAlwDis'],
                        'FTXtdSaleType'     => $paPRSDataPdt['FTPdtSaleType'],
                        'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                        'FCXtdQty'          => 1,
                        'FCXtdQtyAll'       => 1*$paPRSDataPdt['FCPdtUnitFact'],
                        'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                        'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                        // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                        'FTSessionID'       => $paDataPdtParams['tSessionID'],
                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                        'FTLastUpdBy'       => $paDataPdtParams['tPRSUsrCode'],
                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                        'FTCreateBy'        => $paDataPdtParams['tPRSUsrCode'],
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
                'FTPdtCode'         => $paPRSDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paPRSDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paPRSDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paPRSDataPdt['FTPunCode'],
                'FTPunName'         => $paPRSDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                'FTXtdVatType'      => $paPRSDataPdt['FTPdtStaVatBuy'],
                // 'FTXtdVatType'      => $paPRSDataPdt['FTPdtStaVat'],
                'FTVatCode'         => $paDataPdtParams['nVatCode'],
                'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                'FTXtdStaAlwDis'    => $paPRSDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPRSDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                'FCXtdQty'          => 1,
                'FCXtdQtyAll'       => 1*$paPRSDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * 1,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * 1,
                // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tPRSUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tPRSUsrCode'],
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
            return $aStatus;
    }

    //Delete Product Single Item In Doc DT Temp
    public function FSnMPRSDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tPRSDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMPRSDelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tPRSDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    // อัพเดทจำนวน
    public function FSaMPRSUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where_in('FTSessionID',$paDataWhere['tPRSSessionID']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nPRSSeqNo']);

        if ($paDataWhere['tPRSDocNo'] != '' && $paDataWhere['tPRSBchCode'] != '') {
            $this->db->where_in('FTXthDocNo',$paDataWhere['tPRSDocNo']);
            $this->db->where_in('FTBchCode',$paDataWhere['tPRSBchCode']);
        }
        
        $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);
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

    // Function : Count Check Data Product In Doc DT Temp Before Save
    public function FSnMPRSChkPdtInDocDTTemp($paDataWhere){
        $tPRSDocNo       = $paDataWhere['FTXthDocNo'];
        $tPRSDocKey      = $paDataWhere['FTXthDocKey'];
        $tPRSSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocKey   = '$tPRSDocKey'
                            AND DocDT.FTSessionID   = '$tPRSSessionID' ";
        if(isset($tPRSDocNo) && !empty($tPRSDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'')  = '$tPRSDocNo' ";
        }

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // Function: Get Data PRB HD List
    public function FSoMPRSCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];

        // Advance Search
        $tPRSRefIntBchCode        = $aAdvanceSearch['tPRSRefIntBchCode'];
        $tPRSRefIntDocNo          = $aAdvanceSearch['tPRSRefIntDocNo'];
        $tPRSRefIntDocDateFrm     = $aAdvanceSearch['tPRSRefIntDocDateFrm'];
        $tPRSRefIntDocDateTo      = $aAdvanceSearch['tPRSRefIntDocDateTo'];
        $tPRSRefIntStaDoc         = $aAdvanceSearch['tPRSRefIntStaDoc'];

        $tSQLMain = "   SELECT
                                PRBHD.FTBchCode,
                                BCHL.FTBchName,
                                PRBHD.FTXphDocNo,
                                CONVERT(CHAR(10),PRBHD.FDXphDocDate,103) AS FDXphDocDate,
                                CONVERT(CHAR(5), PRBHD.FDXphDocDate,108) AS FTXshDocTime,
                                PRBHD.FTXphStaDoc,
                                PRBHD.FTXphStaApv,
                                PRBHD.FNXphStaRef,
                                PRBHD.FTCreateBy,
                                PRBHD.FDCreateOn,
                                PRBHD.FNXphStaDocAct,
                                USRL.FTUsrName      AS FTCreateByName,
                                PRBHD.FTXphApvCode,
                                WAH_L.FTWahCode,
                                WAH_L.FTWahName
                            FROM TCNTPdtReqHqHD           PRBHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON PRBHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON PRBHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAH_L   WITH (NOLOCK) ON PRBHD.FTBchCode     = WAH_L.FTBchCode   AND PRBHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                            LEFT JOIN TCNTPdtReqSplHDDocRef SPLDOC   WITH (NOLOCK) ON SPLDOC.FTXshRefDocNo     = PRBHD.FTXphDocNo   AND SPLDOC.FTXshRefType = 1
                            WHERE PRBHD.FNXphStaRef != 2 AND PRBHD.FTXphStaDoc = 1 AND PRBHD.FTXphStaApv = 1 AND ISNULL(SPLDOC.FTXshRefDocNo,'') = ''  ";
        if(isset($tPRSRefIntBchCode) && !empty($tPRSRefIntBchCode)){
            $tSQLMain .= " AND (PRBHD.FTBchCode = '$tPRSRefIntBchCode')";
        }

        if(isset($tPRSRefIntDocNo) && !empty($tPRSRefIntDocNo)){
            $tSQLMain .= " AND (PRBHD.FTXphDocNo LIKE '%$tPRSRefIntDocNo%')";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tPRSRefIntDocDateFrm) && !empty($tPRSRefIntDocDateTo)){
            $tSQLMain .= " AND ((PRBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tPRSRefIntDocDateFrm 00:00:00') AND CONVERT(datetime,'$tPRSRefIntDocDateTo 23:59:59')) OR (PRBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tPRSRefIntDocDateTo 23:00:00') AND CONVERT(datetime,'$tPRSRefIntDocDateFrm 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tPRSRefIntStaDoc) && !empty($tPRSRefIntStaDoc)){
            if ($tPRSRefIntStaDoc == 3) {
                $tSQLMain .= " AND PRBHD.FTXphStaDoc = '$tPRSRefIntStaDoc'";
            } elseif ($tPRSRefIntStaDoc == 2) {
                $tSQLMain .= " AND ISNULL(PRBHD.FTXphStaApv,'') = '' AND PRBHD.FTXphStaDoc != '3'";
            } elseif ($tPRSRefIntStaDoc == 1) {
                $tSQLMain .= " AND PRBHD.FTXphStaApv = '$tPRSRefIntStaDoc'";
            }
        }

        $tSQL   =   "       SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";

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
    public function FSoMPRSCallRefIntDocDTDataTable($paData){

        $nLngID   =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tDocNo    =  $paData['tDocNo'];
        
        $tSQL= "SELECT
                    DT.FTBchCode,
                    DT.FTXphDocNo,
                    DT.FNXpdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    DT.FTXpdRmk,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TCNTPdtReqHqDT DT WITH(NOLOCK)
            WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tDocNo'
            ";

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

    // Function : Add/Update Data HD
    public function FSxMPRSAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMPRSGetDataDocHD(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->input->post("ohdPRSLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'         => $paDataWhere['FTBchCode'],
                'FTXphDocNo'        => $paDataWhere['FTXphDocNo'],
                'FDLastUpdOn'       => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'       => $paDataWhere['FTLastUpdBy'],
                'FTXphStaPrcDoc'    => $aDataHDOld['FTXphStaPrcDoc'],
                'FDCreateOn'        => $aDataHDOld['DateOn'],
                'FTCreateBy'        => $aDataHDOld['CreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }
        
        // Delete PRS HD
        $this->db->where_in('FTBchCode',$paDataWhere['FTOldBchCode']);
        $this->db->where_in('FTXphDocNo',$paDataWhere['FTOldXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert PRS HD Dis
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);

        return;
    }

    // Function : Add/Update Data HD Supplier
    public function FSxMPRSAddUpdateHDSpl($paDataHDSpl,$paDataWhere,$paTableAddUpdate){
        // Get Data PRS HD
        $aDataGetDataSpl    =   $this->FSaMPRSGetDataDocHDSpl(array(
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->input->post("ohdPRSLangEdit")
        ));
        $aDataAddUpdateHDSpl    = array();
        if(isset($aDataGetDataSpl['rtCode']) && $aDataGetDataSpl['rtCode'] == 1){
            $aDataHDSplOld  = $aDataGetDataSpl['raItems'];
            $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
                'FTBchCode'     => $aDataHDSplOld['FTBchCode'],
                'FTXphDocNo'    => $aDataHDSplOld['FTXphDocNo'],
            ));
        }else{
            $aDataAddUpdateHDSpl    = array_merge($paDataHDSpl,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            ));
        }
        
        // Delete PRS HD Spl
        $this->db->where_in('FTBchCode',$paDataWhere['FTOldBchCode']);
        $this->db->where_in('FTXphDocNo',$paDataWhere['FTOldXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHDSpl']);

        // Insert PRS HD Dis
        $this->db->insert($paTableAddUpdate['tTableHDSpl'],$aDataAddUpdateHDSpl);

        return;
    }

    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDRefTmp
    public function FSxMPRSAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableDT']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        // Update DocNo Into TCNTDocHDRefTmp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableHD']);
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->update('TCNTDocHDRefTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo']
        ));
        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMPRSMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tPRSBchCode        = $paDataWhere['FTBchCode'];
        $tPRSOldBchCode     = $paDataWhere['FTOldBchCode'];
        $tPRSDocNo          = $paDataWhere['FTXphDocNo'];
        $tPRSOldDocNo       = $paDataWhere['FTOldXphDocNo'];
        $tPRSDocKey         = $paTableAddUpdate['tTableDT'];
        $tPRSSessionID      = $paDataWhere['FTSessionID'];
        $tAgnCode           = $paTableAddUpdate['FTAgnCode'];
        
        if(isset($tPRSOldDocNo) && !empty($tPRSOldDocNo)){
            $this->db->where_in('FTBchCode',$tPRSOldBchCode);
            $this->db->where_in('FTXphDocNo',$tPRSOldDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }
        // print_r($tPRSOldDocNo);

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTAgnCode,FTBchCode,FTXphDocNo,FNXpdSeqNo,FTPdtCode,FTXpdPdtName,FTPunCode,FTPunName,FCXpdFactor,FTXpdBarCode,
                        FCXpdQty,FCXpdQtyAll,FCXpdQtyDone,
                        FTXpdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy ) ";
        if($paDataWhere['tTypeVisit'] == 1){
            $tSQL   .=  "   SELECT
                                ISNULL('$tAgnCode',''),
                                DOCTMP.FTBchCode,
                                DOCTMP.FTXthDocNo,
                                ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                                DOCTMP.FTPdtCode,
                                DOCTMP.FTXtdPdtName,
                                DOCTMP.FTPunCode,
                                DOCTMP.FTPunName,
                                DOCTMP.FCXtdFactor,
                                DOCTMP.FTXtdBarCode,
                                DOCTMP.FCXtdQty,
                                (DOCTMP.FCXtdQty * DOCTMP.FCXtdFactor ) AS FCXtdQtyAll,
                                DOCTMP.FCXtdQtyOrd,
                                DOCTMP.FTXtdRmk,
                                DOCTMP.FDLastUpdOn,
                                DOCTMP.FTLastUpdBy,
                                DOCTMP.FDCreateOn,
                                DOCTMP.FTCreateBy
                            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND DOCTMP.FTBchCode    = '$tPRSBchCode'
                            AND DOCTMP.FTXthDocNo   = '$tPRSDocNo'
                            AND DOCTMP.FTXthDocKey  = '$tPRSDocKey'
                            AND DOCTMP.FTSessionID  = '$tPRSSessionID'
                            ORDER BY DOCTMP.FNXtdSeqNo ASC";
        }else{
            $tSQL   .=  "   SELECT
                                '$tAgnCode',
                                '$tPRSBchCode',
                                '$tPRSDocNo',
                                ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                                DOCTMP.FTPdtCode,
                                DOCTMP.FTXtdPdtName,
                                DOCTMP.FTPunCode,
                                DOCTMP.FTPunName,
                                DOCTMP.FCXtdFactor,
                                DOCTMP.FTXtdBarCode,
                                DOCTMP.FCXtdQty,
                                (DOCTMP.FCXtdQty * DOCTMP.FCXtdFactor ) AS FCXtdQtyAll,
                                DOCTMP.FCXtdQtyOrd,
                                DOCTMP.FTXtdRmk,
                                DOCTMP.FDLastUpdOn,
                                DOCTMP.FTLastUpdBy,
                                DOCTMP.FDCreateOn,
                                DOCTMP.FTCreateBy
                            FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                            WHERE 1 = 1
                            AND DOCTMP.FTBchCode    = '$tPRSOldBchCode'
                            AND DOCTMP.FTXthDocNo   = '$tPRSOldDocNo'
                            AND DOCTMP.FTXthDocKey  = '$tPRSDocKey'
                            AND DOCTMP.FTSessionID  = '$tPRSSessionID'
                            ORDER BY DOCTMP.FNXtdSeqNo ASC";
        }
        $this->db->query($tSQL);
        return;
    }

    //ข้อมูล HD
    public function FSaMPRSGetDataDocHD($paDataWhere){
        $tPRSDocNo      = $paDataWhere['FTXphDocNo'];
        $nLngID         = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            DOCHD.*,
                            DOCHD.FDCreateOn            AS DateOn,
                            DOCHD.FTCreateBy            AS CreateBy,
                            USRCREB.FTUsrName           AS CreateByName,
                            DOCHD.FTBchCode,
                            BCHL.FTBchName,
                            BCHLST.FTBchName            AS rtShipName,
                            DOCHD.FTXphBchTo            AS FTXphShipTo,
                            DPTL.FTDptName,
                            USRL.FTUsrName,
                            USRAPV.FTUsrName	        AS FTXphApvName,
                            DOSPL.*,
                            SPL.*,
                            SPL_L.FTSplName,
                            AGN.FTAgnCode               AS rtAgnCode,
                            AGN.FTAgnName               AS rtAgnName,
                            AGNTO.FTAgnCode             AS rtAgnCodeTo,
                            AGNTO.FTAgnName             AS rtAgnNameTo,
                            WAH_L.FTWahCode             AS rtWahCode,
                            WAH_L.FTWahName             AS rtWahName
                        FROM TCNTPdtReqSplHD DOCHD WITH (NOLOCK)
                        INNER JOIN TCNMBranch           BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode    
                        LEFT JOIN TCNTPdtReqSplHDSpl    DOSPL   WITH (NOLOCK)   ON DOCHD.FTXphDocNo     = DOSPL.FTXphDocNo
                        LEFT JOIN TCNMBranch_L          BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode        AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMBranch_L          BCHLST  WITH (NOLOCK)   ON DOCHD.FTXphBchTo     = BCHLST.FTBchCode      AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L          AGN     WITH (NOLOCK)   ON DOCHD.FTAgnCode      = AGN.FTAgnCode         AND AGN.FNLngID	    = $nLngID
                        LEFT JOIN TCNMAgency_L          AGNTO   WITH (NOLOCK)   ON DOCHD.FTXphAgnTo     = AGNTO.FTAgnCode       AND AGNTO.FNLngID	    = $nLngID
                        LEFT JOIN TCNMUsrDepart_L	    DPTL    WITH (NOLOCK)   ON DOCHD.FTDptCode      = DPTL.FTDptCode	    AND DPTL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L            USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	    AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L            USRAPV	WITH (NOLOCK)   ON DOCHD.FTXphApvCode	= USRL.FTUsrCode	    AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L            USRCREB	WITH (NOLOCK)   ON DOCHD.FTCreateBy	    = USRCREB.FTUsrCode	    AND USRCREB.FNLngID	= $nLngID
                        LEFT JOIN TCNMSpl               SPL     WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL.FTSplCode
                        LEFT JOIN TCNMSpl_L             SPL_L   WITH (NOLOCK)   ON DOCHD.FTSplCode		= SPL_L.FTSplCode       AND SPL_L.FNLngID	= $nLngID
                        LEFT JOIN TCNMWaHouse_L         WAH_L   WITH (NOLOCK)   ON DOCHD.FTBchCode      = WAH_L.FTBchCode       AND DOCHD.FTWahCode = WAH_L.FTWahCode AND WAH_L.FNLngID	= $nLngID
                        WHERE 1=1 AND DOCHD.FTXphDocNo = '$tPRSDocNo' ";
        $oQuery     = $this->db->query($tSQL);
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

    // Function : Get Data Document HD Spl
    public function FSaMPRSGetDataDocHDSpl($paDataWhere){
        $tPRSDocNo   = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            HDSPL.FTBchCode,
                            HDSPL.FTXphDocNo,
                            HDSPL.FTXphDstPaid,
                            HDSPL.FNXphCrTerm,
                            HDSPL.FDXphDueDate,
                            HDSPL.FDXphBillDue,
                            HDSPL.FTXphCtrName,
                            HDSPL.FDXphTnfDate,
                            HDSPL.FTXphRefTnfID,
                            HDSPL.FTXphRefVehID,
                            HDSPL.FTXphRefInvNo,
                            HDSPL.FTXphQtyAndTypeUnit,
                            HDSPL.FNXphShipAdd,
                            SHIP_Add.FTAddV1No              AS FTXphShipAddNo,
                            SHIP_Add.FTAddV1Soi				AS FTXphShipAddPoi,
                            SHIP_Add.FTAddV1Village         AS FTXphShipAddVillage,
                            SHIP_Add.FTAddV1Road			AS FTXphShipAddRoad,
                            SHIP_SUDIS.FTSudName			AS FTXphShipSubDistrict,
                            SHIP_DIS.FTDstName				AS FTXphShipDistrict,
                            SHIP_PVN.FTPvnName				AS FTXphShipProvince,
                            SHIP_Add.FTAddV1PostCode	    AS FTXphShipPosCode,
                            HDSPL.FNXphTaxAdd,
                            TAX_Add.FTAddV1No               AS FTXphTaxAddNo,
                            TAX_Add.FTAddV1Soi				AS FTXphTaxAddPoi,
                            TAX_Add.FTAddV1Village		    AS FTXphTaxAddVillage,
                            TAX_Add.FTAddV1Road				AS FTXphTaxAddRoad,
                            TAX_SUDIS.FTSudName				AS FTXphTaxSubDistrict,
                            TAX_DIS.FTDstName               AS FTXphTaxDistrict,
                            TAX_PVN.FTPvnName               AS FTXphTaxProvince,
                            TAX_Add.FTAddV1PostCode		    AS FTXphTaxPosCode
                        FROM TCNTPdtReqSplHDSpl HDSPL  WITH (NOLOCK)
                        LEFT JOIN TCNMAddress_L			SHIP_Add    WITH (NOLOCK)   ON HDSPL.FNXphShipAdd       = SHIP_Add.FNAddSeqNo	AND SHIP_Add.FNLngID    = $nLngID
                        LEFT JOIN TCNMSubDistrict_L     SHIP_SUDIS 	WITH (NOLOCK)	ON SHIP_Add.FTAddV1SubDist	= SHIP_SUDIS.FTSudCode	AND SHIP_SUDIS.FNLngID  = $nLngID
                        LEFT JOIN TCNMDistrict_L        SHIP_DIS    WITH (NOLOCK)	ON SHIP_Add.FTAddV1DstCode	= SHIP_DIS.FTDstCode    AND SHIP_DIS.FNLngID    = $nLngID
                        LEFT JOIN TCNMProvince_L        SHIP_PVN    WITH (NOLOCK)	ON SHIP_Add.FTAddV1PvnCode	= SHIP_PVN.FTPvnCode    AND SHIP_PVN.FNLngID    = $nLngID
                        LEFT JOIN TCNMAddress_L			TAX_Add     WITH (NOLOCK)   ON HDSPL.FNXphTaxAdd        = TAX_Add.FNAddSeqNo	AND TAX_Add.FNLngID		= $nLngID
                        LEFT JOIN TCNMSubDistrict_L     TAX_SUDIS 	WITH (NOLOCK)	ON TAX_Add.FTAddV1SubDist   = TAX_SUDIS.FTSudCode	AND TAX_SUDIS.FNLngID	= $nLngID
                        LEFT JOIN TCNMDistrict_L        TAX_DIS     WITH (NOLOCK)	ON TAX_Add.FTAddV1DstCode   = TAX_DIS.FTDstCode     AND TAX_DIS.FNLngID     = $nLngID
                        LEFT JOIN TCNMProvince_L        TAX_PVN     WITH (NOLOCK)	ON TAX_Add.FTAddV1PvnCode   = TAX_PVN.FTPvnCode		AND TAX_PVN.FNLngID     = $nLngID
                        WHERE 1=1 AND HDSPL.FTXphDocNo = '$tPRSDocNo'
        ";
        
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

    //ลบข้อมูลใน Temp
    public function FSnMPRSDelALLTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
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

    //ย้ายจาก DT To Temp
    public function FSxMPRSMoveDTToDTTemp($paDataWhere){
        $tPRSDocNo       = $paDataWhere['FTXphDocNo'];
        $tDocKey        = $paDataWhere['FTXthDocKey'];
        
        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tPRSDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,FCXtdQtyOrd,
                        FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                    SELECT
                        DT.FTBchCode,
                        DT.FTXphDocNo,
                        DT.FNXpdSeqNo,
                        CONVERT(VARCHAR,'".$tDocKey."') AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXpdFactor,
                        DT.FTXpdBarCode,
                        DT.FCXpdQty,
                        (DT.FCXpdQty * DT.FCXpdFactor) AS FCXpdQtyAll,
                        DT.FCXpdQtyDone,
                        DT.FTXpdRmk,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM TCNTPdtReqSplDT AS DT WITH (NOLOCK)
                    WHERE 1=1 AND DT.FTXphDocNo = '$tPRSDocNo'
                    ORDER BY DT.FNXpdSeqNo ASC ";
        $this->db->query($tSQL);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMPRSCallRefIntDocInsertDTToTemp($paData){

        $tPRSDocNo          = $paData['tPRSDocNo'];
        $tPRSFrmBchCode     = $paData['tPRSFrmBchCode'];
        $tPRSOptionAddPdt   = $paData['tPRSOptionAddPdt']; 
        $tRefIntDocNo       = $paData['tRefIntDocNo'];
        $tSessionID         = $this->session->userdata('tSesSessionID');
        $tRefIntBchCode     = $paData['tRefIntBchCode'];
        $aSeqNo             = '(' . implode(',', $paData['aSeqNo']) .')';

        // ลบก่อนเพิ่มใหม่
        // $this->db->where('FTBchCode',$tPRSFrmBchCode);
        // $this->db->where('FTXthDocNo',$tPRSDocNo);
        // $this->db->delete('TCNTDocDTTmp');

        if ($tPRSOptionAddPdt == 1) { //บวกจำนวนเดิมในรายการ

            $tSQLSelectDT   = "SELECT DT.FTPdtCode , DT.FTPunCode , DT.FTXpdBarCode  , DT.FNXpdSeqNo , DT.FCXpdQty
                               FROM TCNTPdtReqHqDT DT WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";
            $oQuery         = $this->db->query($tSQLSelectDT);

            $tSQLGetSeqPDT  = "  SELECT MAX(ISNULL(FNXtdSeqNo,0)) AS FNXtdSeqNo 
                                FROM TCNTDocDTTmp WITH(NOLOCK)
                                WHERE FTSessionID = ".$this->db->escape($tSessionID)."
                                AND FTXthDocKey = 'TCNTPdtReqSplDT'  ";
            $oQuerySeq      = $this->db->query($tSQLGetSeqPDT);
            $aResultDTSeq   = $oQuerySeq->row_array();

            if ($oQuery->num_rows() > 0) {
                $aResultDT      = $oQuery->result_array();
                $nCountResultDT = count($aResultDT);

                if($nCountResultDT >= 0){
                    for($j=0; $j<$nCountResultDT; $j++){

                        $tSQL   =   "   SELECT FNXtdSeqNo , FCXtdQty 
                                        FROM TCNTDocDTTmp
                                        WHERE 
                                        FTXthDocNo          = '".$tPRSDocNo."'
                                        AND FTBchCode       = '".$tPRSFrmBchCode."'
                                        AND FTXthDocKey     = 'TCNTPdtReqSplDT'
                                        AND FTSessionID     = '".$tSessionID."'
                                        AND FTPdtCode       = '".$aResultDT[$j]["FTPdtCode"]."'
                                        AND FTPunCode       = '".$aResultDT[$j]["FTPunCode"]."' 
                                        AND ISNULL(FTXtdBarCode,'') = '".$aResultDT[$j]["FTXpdBarCode"]."' 
                                        ORDER BY FNXtdSeqNo ";
                        $oQuery = $this->db->query($tSQL);
                        if ($oQuery->num_rows() > 0) {

                            // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                            $aResult    =   $oQuery->row_array();
                            $tSQL       =   "   UPDATE TCNTDocDTTmp
                                                SET FCXtdQty = '".($aResult["FCXtdQty"] + $aResultDT[$j]["FCXpdQty"] )."' 
                                                WHERE 
                                                FTXthDocNo          = '".$tPRSDocNo."'
                                                AND FTBchCode       = '".$tPRSFrmBchCode."'
                                                AND FNXtdSeqNo      = '".$aResult["FNXtdSeqNo"]."'
                                                AND FTXthDocKey     = 'TCNTPdtReqSplDT'
                                                AND FTSessionID     = '".$tSessionID."'
                                                AND FTPdtCode       = '".$aResultDT[$j]["FTPdtCode"]."'
                                                AND FTPunCode       = '".$aResultDT[$j]["FTPunCode"]."' 
                                                AND ISNULL(FTXtdBarCode,'') = '".$aResultDT[$j]["FTXpdBarCode"]."' "; 
                            $this->db->query($tSQL);
                        }else{
                            //เพิ่มเป็นรายการใหม่
                            $tSQL = "   INSERT INTO TCNTDocDTTmp (
                                            FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                                            FCXtdQty,FCXtdQtyAll,
                                            FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                                        SELECT
                                            '$tPRSFrmBchCode' as FTBchCode,
                                            '$tPRSDocNo' as FTXphDocNo,
                                            ".$aResultDTSeq['FNXtdSeqNo']." + DT.FNXpdSeqNo,
                                            'TCNTPdtReqSplDT' AS FTXthDocKey,
                                            DT.FTPdtCode,
                                            DT.FTXpdPdtName,
                                            DT.FTPunCode,
                                            DT.FTPunName,
                                            DT.FCXpdFactor,
                                            DT.FTXpdBarCode,
                                            DT.FCXpdQty,
                                            DT.FCXpdQtyAll,
                                            '' as FTXpdRmk,   
                                            CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                                        FROM
                                            TCNTPdtReqHqDT DT WITH (NOLOCK)
                                        LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                                        WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo = '".$aResultDT[$j]["FNXpdSeqNo"]."' ";
                            $this->db->query($tSQL);
                        }
                    }
                }
            }
        }else{ //เพิ่มเป็นรายการใหม่
            $tSQL = " INSERT INTO TCNTDocDTTmp (
                        FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                        FCXtdQty,FCXtdQtyAll,
                        FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                    SELECT
                        '$tPRSFrmBchCode' as FTBchCode,
                        '$tPRSDocNo' as FTXphDocNo,
                        DT.FNXpdSeqNo,
                        'TCNTPdtReqSplDT' AS FTXthDocKey,
                        DT.FTPdtCode,
                        DT.FTXpdPdtName,
                        DT.FTPunCode,
                        DT.FTPunName,
                        DT.FCXpdFactor,
                        DT.FTXpdBarCode,
                        DT.FCXpdQty,
                        DT.FCXpdQtyAll,
                        '' as FTXpdRmk,   
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                        CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                        CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                    FROM
                        TCNTPdtReqHqDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                    WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo ";
        
            $oQuery = $this->db->query($tSQL);
        }
    }

    // Function: Delete Document
    public function FSnMPRSDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode = $paDataDoc['tBchCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TCNTPdtReqSplHD');
        
        // Document DT
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TCNTPdtReqSplDT');
        
        // Document HD
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->delete('TCNTPdtReqSplHDSpl');

        // PRS Ref
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqSplHDDocRef');

        // PRB Ref
        $this->db->where_in('FTXshRefDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqHqHDDocRef');
        

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

    // Function : Cancel Document Data
    public function FSaMPRSCancelDocument($paDataUpdate){
        // TCNTPdtReqHqHD
        $this->db->trans_begin();
        $this->db->set('FTXphStaDoc' , '3');
        $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtReqSplHD');

        // PRS Ref
        $this->db->where_in('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqSplHDDocRef');

        // PRB Ref
        $this->db->where_in('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqHqHDDocRef');

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

    // Function : Cancel Document Data
    public function FSaMPRSUpdateBchCode($paDataUpdate){
        // TCNTPdtReqHqHD
        $this->db->trans_begin();
        $this->db->set('FTXphStaDoc' , '3');
        $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtReqSplHD');

        // PRS Ref
        $this->db->where_in('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqSplHDDocRef');

        // PRB Ref
        $this->db->where_in('FTXshRefDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqHqHDDocRef');

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

    //อนุมัตเอกสาร
    public function FSaMPRSApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        //ใบขอซื้อแบบแฟรนไชส์
        if($paDataUpdate['tAGNCode'] != '' || $paDataUpdate['tAGNCode'] != null){
            if($paDataUpdate['tPRSTypeDocument'] == 1){ //ใบขอซื้อ
                $this->db->set('FTXphStaPrcDoc',2); // 2 : แฟรนไซด์อนุมัติแล้ว รอสำนักงานใหญ่อนุมัติ
            }else{ //ใบขอซื้อแฟรนส์ไซด์ 
                $this->db->set('FTXphStaPrcDoc',3); // 3 : สำนักงานใหญ่ อนุมัติเเล้ว
            }
        }

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXphStaApv',$paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode',$paDataUpdate['FTXphUsrApv']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TCNTPdtReqSplHD');

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

    //อัพเดทสถานะของใบ PRB
    public function FSaMPRSUpdatePRBStaRef($ptRefInDocNo, $pnStaRef){
        $this->db->set('FNXphStaRef',$pnStaRef);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TCNTPdtReqHqHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    public function FSaMPRSUpdateRefDocHD($paDataPRSAddDocRef, $aDatawherePRBAddDocRef ,$aDataPRBAddDocRef){
        try {   
            $tTable     = "TCNTPdtReqSplHDDocRef";
            $tTableRef  = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataPRSAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataPRSAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataPRSAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '1'
            );

            $nChhkDataDocRefInt  = $this->FSaMPRSChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefInt['rtCode']) && $nChhkDataDocRefInt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataPRSAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataPRSAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataPRSAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','1');
                $this->db->delete('TCNTPdtReqSplHDDocRef');

                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqSplHDDocRef',$paDataPRSAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqSplHDDocRef',$paDataPRSAddDocRef);
            }

            $aDataWhere = array(
                'FTAgnCode'         => $aDatawherePRBAddDocRef['FTAgnCode'],
                'FTBchCode'         => $aDatawherePRBAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $aDatawherePRBAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '2'
            );
            $nChhkDataDocRefPRB  = $this->FSaMPRSChkDupicate($aDataWhere, $tTableRef);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefPRB['rtCode']) && $nChhkDataDocRefPRB['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$aDataWhere['FTAgnCode']);
                $this->db->where_in('FTBchCode',$aDataWhere['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$aDataWhere['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','2');
                $this->db->delete('TCNTPdtReqHqHDDocRef');

                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqHqHDDocRef',$aDataPRBAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqHqHDDocRef',$aDataPRBAddDocRef);
            }

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
            
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        return $aReturnData;
    }

    public function FSaMPRSUpdateRefExtDocHD($paDataPRSAddDocRef){
        try {   
            $tTable     = "TCNTPdtReqSplHDDocRef";
            $tTableRef  = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataPRSAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataPRSAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataPRSAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => '3'
            );

            $nChhkDataDocRefExt  = $this->FSaMPRSChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataPRSAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataPRSAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataPRSAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType','3');
                $this->db->delete('TCNTPdtReqSplHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqSplHDDocRef',$paDataPRSAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqSplHDDocRef',$paDataPRSAddDocRef);
            }

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
            
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

    //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
    public function FSaMPRSChkDupicate($paDataPrimaryKey, $ptTable){
        try{
            $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
            $tBchCode = $paDataPrimaryKey['FTBchCode'];
            $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
            $tRefType   = $paDataPrimaryKey['FTXshRefType'];

            $tSQL = "   SELECT 
                            FTAgnCode,
                            FTBchCode,
                            FTXshDocNo
                        FROM $ptTable
                        WHERE 1=1
                        AND FTAgnCode  = '$tAgnCode'
                        AND FTBchCode  = '$tBchCode'
                        AND FTXshDocNo = '$tDocNo'
                        AND FTXshRefType = '$tRefType'
                    ";
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

    //หาว่าเอกสารใบขอซื้อ ใบนี้ถูกสร้างมาจาก ใบจัดการสินค้าจากสาขาหรือเปล่า 
    public function FSaMPRSFindPRBInDatabase($paDataUpdate){
        $tPRSBchCode    = $paDataUpdate['FTBchCode'];
        $tPRSDocCode    = $paDataUpdate['FTXphDocNo'];
        $tSQL           = " SELECT FTXphDocNo FROM TCNTPdtReqMgtHD WHERE FTXrhDocPrBch IN (SELECT FTXrhDocPrBch FROM TCNTPdtReqMgtHD WHERE FTXphDocNo = '$tPRSDocCode' ) AND (FNXrhDocType = 2 OR FNXrhDocType = 4)";
        $oQuery         = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->result_array();
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

    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // แท็บค่าอ้างอิงเอกสาร - โหลด
    public function FSaMPRSGetDataHDRefTmp($paData){

        $tTableTmpHDRef = $paData['tTableTmpHDRef'];
        $FTXshDocNo     = $paData['FTXshDocNo'];
        $FTXshDocKey    = $paData['FTXshDocKey'];
        $FTSessionID    = $paData['FTSessionID'];

        $tSQL = "   SELECT FTXthDocNo, FTXthRefDocNo, FTXthRefType, FTXthRefKey, FDXthRefDocDate
                    FROM $tTableTmpHDRef
                    WHERE FTXthDocNo  = '$FTXshDocNo'
                      AND FTXthDocKey = '$FTXshDocKey'
                      AND FTSessionID = '$FTSessionID' ";
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
    public function FSaMPRSAddEditHDRefTmp($paDataWhere,$paDataAddEdit){

        $tSQL       = " SELECT FTXthRefDocNo FROM TCNTDocHDRefTmp
                        WHERE FTXthDocNo    = '".$paDataWhere['FTXshDocNo']."'
                            AND FTXthDocKey   = '".$paDataWhere['FTXshDocKey']."'
                            AND FTSessionID   = '".$paDataWhere['FTSessionID']."'
                            AND FTXthRefDocNo = '".$paDataAddEdit['FTXthRefDocNo']."' ";

        $oQuery     = $this->db->query($tSQL);
        $this->db->trans_begin();
        if ( $oQuery->num_rows() > 0 ){
            $this->db->where('FTXthRefDocNo',$paDataAddEdit['FTXthRefDocNo']);
            $this->db->where('FTXthDocNo',$paDataWhere['FTXshDocNo']);
            $this->db->where('FTXthDocKey',$paDataWhere['FTXshDocKey']);
            $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
            $this->db->update('TCNTDocHDRefTmp',$paDataAddEdit);
        }else{
            $aDataAdd = array_merge($paDataAddEdit,array(
                'FTXthDocNo'  => $paDataWhere['FTXshDocNo'],
                'FTXthDocKey' => $paDataWhere['FTXshDocKey'],
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

    // แท็บค่าอ้างอิงเอกสาร - ลบ
    public function FSaMPRSDelHDDocRef($paData){
        $tPRSDocNo       = $paData['FTXshDocNo'];
        $tPRSRefDocNo    = $paData['FTXshRefDocNo'];
        $tPRSDocKey      = $paData['FTXshDocKey'];
        $tPRSSessionID   = $paData['FTSessionID'];

        $this->db->where('FTSessionID',$tPRSSessionID);
        $this->db->where('FTXthDocKey',$tPRSDocKey);
        $this->db->where('FTXthRefDocNo',$tPRSRefDocNo);
        $this->db->where('FTXthDocNo',$tPRSDocNo);
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

}

