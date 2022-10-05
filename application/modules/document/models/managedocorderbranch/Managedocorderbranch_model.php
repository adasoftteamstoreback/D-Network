<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Managedocorderbranch_model extends CI_Model {

    //ข้อมูล
    public function FSaMMNGList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchDocDateRef      = $aAdvanceSearch['tSearchDocDateRef'];
        $tSearchSplCodeFrom     = $aAdvanceSearch['tSearchSplCodeFrom'];
        $tSearchSplCodeTo       = $aAdvanceSearch['tSearchSplCodeTo'];
        $tSearchStaApv          = $aAdvanceSearch['tSearchStaApv'];
        $tSearchTypeDocument    = $aAdvanceSearch['tSearchTypeDocument'];
        $tMNGTypeDocument       = $paDataCondition['tMNGTypeDocument'];
        $tSQL                   = '';

        $tSQL = "SELECT 
                    COUNT(A.FTXphDocNo) OVER (PARTITION BY A.FTXphDocNo) AS PARTITIONBYDOC ,
                    COUNT(A.FTXphDocNo) OVER (PARTITION BY A.FTXphDocNo , MGTHD.FNXrhDocType) AS PARTITIONBYDOC_AND_TYPE ,
                    A.* ,
                    BCHL.FTBchName,
                    MGTHD.FTBchCode							 AS BCHHQ ,
                    MGTHD.FNXrhDocType                       AS MGTDocType,
                    MGTHD.FTXphDocNo                         AS MGTDocRef ,
                    CONVERT(varchar,MGTHD.FDXphDocDate, 103) AS MGTDate, 
                    MGTHD.FTXrhStaPrcDoc                     AS MGTStaExport ,
                    MGTHD.FTXrhStaDoc                        AS MGTStaDoc ,
                    MGTHD.FTXrhAgnTo                         AS MGTAgnTo ,
                    MGTHD.FTXrhAgnFrm                        AS MGTAgnFrm ,
                    MGTHD.FTXrhRefFrm                        AS MGTBchTo ,
                    TOSPL.FTSplName                          AS MGTSplName ,
                    TOBCH.FTBchName                          AS MGTBchName ,
                    USRL.FTUsrName                           AS FTCreateByName,
                    USRLAPV.FTUsrName                        AS FTXphApvName ,
                    CASE  
                        WHEN MGTHD.FNXrhDocType = 1 THEN 'DONTCHECK'    --ขอโอน
                        ELSE FILEOBJ.FTFleObj --ขอซื้อ
                    END AS FTFleObj
                FROM(
                    SELECT c.* FROM(
                        SELECT  
                            ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTXphDocNo DESC ) AS FNRowID  ,
                            * 
                        FROM (  
                            SELECT     
                                DISTINCT
                                PBHD.FTBchCode ,
                                PBHD.FTXphDocNo ,
                                PBHD.FDCreateOn ,
                                PBHD.FDXphDocDate ,
                                PBHD.FTXphStaDoc ,
                                PBHD.FTCreateBy ,
                                PBHD.FTXphApvCode ,
                                CONVERT(varchar,PBHD.FDXphDocDate, 103)  AS rtDocDate
                            FROM TCNTPdtReqHqHD         PBHD    WITH (NOLOCK)
                            LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON PBHD.FTBchCode         = BCH.FTBchCode     
                            LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch    = PBHD.FTXphDocNo  AND MGTHD.FNXrhDocType != 3
                            LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON PBHD.FTBchCode         = BCHL.FTBchCode     AND BCHL.FNLngID    = $nLngID
                            WHERE 1=1 AND PBHD.FTXphStaApv = 1 "; 

        if($tMNGTypeDocument == 1){ //ใบสั่งสินค้าจากสาขา

            //ต้องเห็นเอกสารเฉพาะ สาขาที่เป็นแฟรนไชด์
            $tSQL .= " AND BCH.FTBchType = 4 ";

            //ระบบค้นหา
                if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                    $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                    $tSQL .= " AND PBHD.FTBchCode IN ($tBchCode) ";
                }
                
                // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
                if(isset($tSearchList) && !empty($tSearchList)){
                    $tSQL .= " AND ((PBHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PBHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
                }
        
                // ค้นหาจากสาขา - ถึงสาขา
                if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
                    $tSQL .= " AND ( ((PBHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PBHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) ";
                    $tSQL .= " OR ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) )";
                }
        
                // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
                if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
                    $tSQL .= " AND ((MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom' ))";
                }
        
                // ค้นหาจากวันที่ - ถึงวันที่
                if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
                    $tSQL .= " AND ((PBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
                }
        
                // ค้นหาจากวันที่เอกสาร (ใบขอโอน , ใบขอซื้อ)
                if(!empty($tSearchDocDateRef)){
                    $tSQL .= " AND ((MGTHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateRef 00:00:00') AND CONVERT(datetime,'$tSearchDocDateRef 23:59:59')))";
                }

                // สถานะอนุมัติ (ใบขอโอน , ใบขอซื้อ)
                $tConditionSQLStatus = "";
                if(isset($tSearchStaApv) && !empty($tSearchStaApv)){
                    if ($tSearchStaApv == 0) { //ทั้งหมด
                        $tSQL .= "";
                    } elseif ($tSearchStaApv == 1) { //รอยืนยัน
                        $tSQL .= " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
                        $tConditionSQLStatus = " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' ";
                    } elseif ($tSearchStaApv == 2) { //ยืนยันแล้วรออนุมัติ
                        $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc = 1 AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                        $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc = 1 ";
                    } elseif ($tSearchStaApv == 3) { //อนุมัติแล้ว
                        $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc >= 2  AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                        $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc >= 2 ";
                    } elseif ($tSearchStaApv == 4) { //เอกสารรอดำเนินการ
                        $tSQL .= " AND ISNULL(MGTHD.FNXrhDocType,'') = '' ";
                    }
                }

        }else{ //ใบสั่งสินค้าจากแฟรนไซส์
            //จะต้องเป็น PRB ที่ไม่ใช่ของแฟรนไซส์
            $tSQL .="   AND BCH.FTBchType <> 4 ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL .= " AND PBHD.FTBchCode IN ($tBchCode) ";
            }
            
            // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND ((PBHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),PBHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
            }
    
            // ค้นหาจากสาขา - ถึงสาขา
            if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
                $tSQL .= " AND ( ((PBHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (PBHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) ";
                $tSQL .= " OR ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) )";
            }
    
            // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
            if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
                $tSQL .= " AND ((MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom' ))";
            }
    
            // ค้นหาจากวันที่ - ถึงวันที่
            if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
                $tSQL .= " AND ((PBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (PBHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
            }
    
            // ค้นหาจากวันที่เอกสาร (ใบขอโอน , ใบขอซื้อ)
            if(!empty($tSearchDocDateRef)){
                $tSQL .= " AND ((MGTHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateRef 00:00:00') AND CONVERT(datetime,'$tSearchDocDateRef 23:59:59')))";
            }

            // สถานะอนุมัติ (ใบขอโอน , ใบขอซื้อ)
            $tConditionSQLStatus = "";
            if(isset($tSearchStaApv) && !empty($tSearchStaApv)){
                if ($tSearchStaApv == 0) { //ทั้งหมด
                    $tSQL .= "";
                } elseif ($tSearchStaApv == 1) { //รอยืนยัน
                    $tSQL .= " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
                    $tConditionSQLStatus = " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' ";
                } elseif ($tSearchStaApv == 2) { //ยืนยันแล้วรออนุมัติ
                    $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc = 1 AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                    $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc = 1 ";
                } elseif ($tSearchStaApv == 3) { //อนุมัติแล้ว
                    $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc >= 2  AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                    $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc >= 2 ";
                } elseif ($tSearchStaApv == 4) { //เอกสารรอดำเนินการ
                    $tSQL .= " AND ISNULL(MGTHD.FNXrhDocType,'') = '' ";
                }
            }

            // ประเภทเอกสาร  (ใบสั่งซื้อ , ใบสั่งสินค้าสำนักงานใหญ่)
            if(isset($tSearchTypeDocument) && !empty($tSearchTypeDocument)){
                if ($tSearchTypeDocument == 0) { //ทั้งหมด
                    $tSQL .= "";
                } elseif ($tSearchTypeDocument == 1) { //จะเอาแต่ใบสั่งซื้อ
                    $tSQL .= " AND PBHD.FTXphStaDoc = 99 "; //ทำให้มันค้นหาใบสั่งสินค้าสำนักงานใหญ่ ไม่เจอเลยสักใบ
                } 
            }
            
            $tSQL .="   UNION ALL ";
            $tSQL .="   SELECT     
                                DISTINCT
                                POHD.FTBchCode ,
                                POHD.FTXphDocNo ,
                                POHD.FDCreateOn ,
                                POHD.FDXphDocDate ,
                                POHD.FTXphStaDoc ,
                                POHD.FTCreateBy ,
                                POHD.FTXphApvCode ,
                                CONVERT(varchar,POHD.FDXphDocDate, 103)  AS rtDocDate
                        FROM TAPTPoHD         		POHD    WITH (NOLOCK)
                        LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch 	= POHD.FTXphDocNo  	 AND MGTHD.FNXrhDocType != 3
                        LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON POHD.FTBchCode         = BCH.FTBchCode     
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON POHD.FTBchCode         = BCHL.FTBchCode     AND BCHL.FNLngID    = ".$this->db->escape($nLngID)." 
                        WHERE 1=1 AND POHD.FTXphStaApv = 1 ";

            //ระบบค้นหา
                if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                    $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                    $tSQL .= " AND POHD.FTBchCode IN ($tBchCode) ";
                }else{
                    //ใบขอซื้อ ที่เป็นของแฟรนไซส์เท่านั้น
                    $tSQL .= " AND BCH.FTBchType = 4 ";
                }

                // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
                if(isset($tSearchList) && !empty($tSearchList)){
                    $tSQL .= " AND ((POHD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),POHD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
                }
        
                // ค้นหาจากสาขา - ถึงสาขา
                if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
                    $tSQL .= " AND ( ((POHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (POHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) ";
                    $tSQL .= " OR ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) )";
                }
        
                // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
                if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
                    $tSQL .= " AND ((MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom' ))";
                }
        
                // ค้นหาจากวันที่ - ถึงวันที่
                if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
                    $tSQL .= " AND ((POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (POHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
                }
        
                // ค้นหาจากวันที่เอกสาร (ใบขอโอน , ใบขอซื้อ)
                if(!empty($tSearchDocDateRef)){
                    $tSQL .= " AND ((MGTHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateRef 00:00:00') AND CONVERT(datetime,'$tSearchDocDateRef 23:59:59')))";
                }

                // สถานะอนุมัติ (ใบขอโอน , ใบขอซื้อ)
                $tConditionSQLStatus = "";
                if(isset($tSearchStaApv) && !empty($tSearchStaApv)){
                    if ($tSearchStaApv == 0) { //ทั้งหมด
                        $tSQL .= "";
                    } elseif ($tSearchStaApv == 1) { //รอยืนยัน
                        $tSQL .= " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
                        $tConditionSQLStatus = " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' ";
                    } elseif ($tSearchStaApv == 2) { //ยืนยันแล้วรออนุมัติ
                        $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc = 1 AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                        $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc = 1 ";
                    } elseif ($tSearchStaApv == 3) { //อนุมัติแล้ว
                        $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc >= 2  AND ISNULL(MGTHD.FNXrhDocType,'') != ''";
                        $tConditionSQLStatus = " AND MGTHD.FTXrhStaPrcDoc >= 2 ";
                    } elseif ($tSearchStaApv == 4) { //เอกสารรอดำเนินการ
                        $tSQL .= " AND ISNULL(MGTHD.FNXrhDocType,'') = '' ";
                    }
                }

                // ประเภทเอกสาร  (ใบสั่งซื้อ , ใบสั่งสินค้าสำนักงานใหญ่)
                if(isset($tSearchTypeDocument) && !empty($tSearchTypeDocument)){
                    if ($tSearchTypeDocument == 0) { //ทั้งหมด
                        $tSQL .= "";
                    } elseif ($tSearchTypeDocument == 2) { //ใบสั่งสินค้าสำนักงานใหญ่
                        $tSQL .= " AND POHD.FTXphStaDoc = 99 "; //ทำให้มันค้นหาใบสั่งซื้อไม่เจอเลยสักใบ
                    } 
                }
        }
     
        
        $tSQL .=" ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
                ) AS A
                LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch 	= A.FTXphDocNo       AND MGTHD.FNXrhDocType != 3 $tConditionSQLStatus
                LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON A.FTBchCode      		= BCHL.FTBchCode     AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."  
                LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON A.FTCreateBy     		= USRL.FTUsrCode     AND USRL.FNLngID    = ".$this->db->escape($nLngID)."  
                LEFT JOIN TCNMUser_L        USRLAPV WITH (NOLOCK) ON A.FTXphApvCode   		= USRLAPV.FTUsrCode  AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)."  
                LEFT JOIN TCNMSpl_L         TOSPL   WITH (NOLOCK) ON MGTHD.FTXrhRefFrm	 	= TOSPL.FTSplCode	 AND (MGTHD.FNXrhDocType = 2 OR MGTHD.FNXrhDocType = 4) AND TOSPL.FNLngID = ".$this->db->escape($nLngID)."  
                LEFT JOIN TCNMBranch_L      TOBCH   WITH (NOLOCK) ON MGTHD.FTXrhBchTo	 	= TOBCH.FTBchCode	 AND (MGTHD.FNXrhDocType = 1 OR MGTHD.FNXrhDocType = 5) AND TOBCH.FNLngID = ".$this->db->escape($nLngID)."  
                LEFT JOIN TCNMFleObj 		FILEOBJ WITH (NOLOCK) ON FILEOBJ.FTFleRefID1    = MGTHD.FTXphDocNo   AND FILEOBJ.FTFleRefTable = 'TCNTPdtReqSplHD' AND FILEOBJ.FTFleType = 'xlsx' 
                WHERE 1=1 ";

        $tSQL .= "  ORDER BY A.FDCreateOn DESC , A.FTXphDocNo DESC , 
                    CASE 
                        WHEN MGTHD.FNXrhDocType = 5 THEN MGTHD.FNXrhDocType 
                        WHEN MGTHD.FNXrhDocType = 1 THEN MGTHD.FNXrhDocType
                    END ASC , 
                    MGTHD.FTXrhStaPrcDoc ASC ";
        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMMNGCountPageDocListMain($paDataCondition);
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

    //ข้อมูลแบบ IN (Doc Array) มาจากการยืนยันสร้างเอกสาร
    public function FSaMMNGListArray($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $tSQLConcat             = '';
        $tTextDocRef            = $paDataCondition['tTextDocRef'];
        $tSQL       =   "SELECT c.* FROM(
                            SELECT  
                                ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC , FTXphDocNo DESC ) AS FNRowID  ,
                                COUNT(FTXphDocNo) OVER (PARTITION BY FTXphDocNo) AS PARTITIONBYDOC ,
                                * 
                            FROM ( ";
        $tSQLConcat =       " SELECT     
                                    MGTHD.FTBchCode,
                                    BCHL.FTBchName,
                                    PBHD.FTXphDocNo,
                                    PBHD.FDCreateOn,
                                    CONVERT(varchar,PBHD.FDXphDocDate, 103)  AS FDXphDocDate,
                                    PBHD.FTXphStaDoc ,
                                    MGTHD.FNXrhDocType                       AS MGTDocType,
                                    MGTHD.FTXphDocNo                         AS MGTDocRef ,
                                    CONVERT(varchar,MGTHD.FDXphDocDate, 103) AS MGTDate, 
                                    MGTHD.FTXrhStaPrcDoc                     AS MGTStaExport ,
                                    MGTHD.FTXrhStaDoc                        AS MGTStaDoc ,
                                    TOSPL.FTSplName                          AS MGTSplName ,
                                    TOBCH.FTBchName                          AS MGTBchName 
                                FROM TCNTPdtReqHqHD         PBHD    WITH (NOLOCK)
                                LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch = PBHD.FTXphDocNo 
                                LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON PBHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."  
                                LEFT JOIN TCNMSpl_L         TOSPL   WITH (NOLOCK) ON MGTHD.FTXrhRefFrm	 = TOSPL.FTSplCode	 AND (MGTHD.FNXrhDocType = 2 OR MGTHD.FNXrhDocType = 4) AND TOSPL.FNLngID = ".$this->db->escape($nLngID)."  
                                LEFT JOIN TCNMBranch_L      TOBCH   WITH (NOLOCK) ON MGTHD.FTXrhBchTo	 = TOBCH.FTBchCode	 AND (MGTHD.FNXrhDocType = 1 OR MGTHD.FNXrhDocType = 5) AND TOBCH.FNLngID = ".$this->db->escape($nLngID)."  
                            WHERE 1=1 ";
        $tSQLConcat .=  "   AND PBHD.FTXphDocNo IN($tTextDocRef) ";
        $tSQLConcat .=  "   UNION ALL ";
        $tSQLConcat .=  "   SELECT     
                                    MGTHD.FTBchCode,
                                    BCHL.FTBchName,
                                    POHD.FTXphDocNo,
                                    POHD.FDCreateOn,
                                    CONVERT(varchar,POHD.FDXphDocDate, 103)  AS FDXphDocDate,
                                    POHD.FTXphStaDoc ,
                                    MGTHD.FNXrhDocType                       AS MGTDocType,
                                    MGTHD.FTXphDocNo                         AS MGTDocRef ,
                                    CONVERT(varchar,MGTHD.FDXphDocDate, 103) AS MGTDate, 
                                    MGTHD.FTXrhStaPrcDoc                     AS MGTStaExport ,
                                    MGTHD.FTXrhStaDoc                        AS MGTStaDoc ,
                                    TOSPL.FTSplName                          AS MGTSplName ,
                                    TOBCH.FTBchName                          AS MGTBchName 
                                    FROM TAPTPoHD         	POHD    WITH (NOLOCK)
                                LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch = POHD.FTXphDocNo 
                                LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON POHD.FTBchCode      = BCHL.FTBchCode    AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."  
                                LEFT JOIN TCNMSpl_L         TOSPL   WITH (NOLOCK) ON MGTHD.FTXrhRefFrm	 = TOSPL.FTSplCode	 AND (MGTHD.FNXrhDocType = 2 OR MGTHD.FNXrhDocType = 4) AND TOSPL.FNLngID = ".$this->db->escape($nLngID)."  
                                LEFT JOIN TCNMBranch_L      TOBCH   WITH (NOLOCK) ON MGTHD.FTXrhBchTo	 = TOBCH.FTBchCode	 AND (MGTHD.FNXrhDocType = 1 OR MGTHD.FNXrhDocType = 5) AND TOBCH.FNLngID = ".$this->db->escape($nLngID)."  
                            WHERE 1=1 ";
        $tSQLConcat .=  " AND POHD.FTXphDocNo IN($tTextDocRef) ";
        
        $tSQL       .= $tSQLConcat;
        $tSQL       .=  " ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMMNGCountPageDocListAll($tSQLConcat);
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

    //จำนวน (ทั้งแม่ และ ลูก)
    public function FSnMMNGCountPageDocListAll($tSQLConcat){
        $oQuery = $this->db->query($tSQLConcat);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //จำนวน (แม่อย่างเดียว)
    public function FSnMMNGCountPageDocListMain($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchDocDateRef      = $aAdvanceSearch['tSearchDocDateRef'];
        $tSearchSplCodeFrom     = $aAdvanceSearch['tSearchSplCodeFrom'];
        $tSearchSplCodeTo       = $aAdvanceSearch['tSearchSplCodeTo'];
        $tSearchStaApv          = $aAdvanceSearch['tSearchStaApv'];
        $tMNGTypeDocument       = $paDataCondition['tMNGTypeDocument'];
        $tSearchTypeDocument    = $aAdvanceSearch['tSearchTypeDocument'];

        $tSQL =     " SELECT DISTINCT A.FTXphDocNo FROM ( ";
        $tSQL .=    "   SELECT     
                            PBHD.FTXphDocNo,
                            PBHD.FTBchCode,
                            PBHD.FTCreateBy,
                            PBHD.FTXphApvCode,
                            PBHD.FTXphStaApv,
                            PBHD.FDXphDocDate
                        FROM TCNTPdtReqHqHD     PBHD    WITH (NOLOCK)
                        LEFT JOIN TCNMBranch    BCH     WITH (NOLOCK) ON PBHD.FTBchCode = BCH.FTBchCode
                        WHERE PBHD.FTXphStaApv = 1  ";

        if($tMNGTypeDocument == 1){ //ใบสั่งสินค้าจากสาขา
            $tSQL .="  AND BCH.FTBchType = 4 ";
            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tBchCode   = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL       .= " AND PBHD.FTBchCode IN ($tBchCode) ";
            }
        }else{ //ใบสั่งสินค้าจากแฟรนไซส์
            //จะต้องเป็น PRB ที่ไม่ใช่ของแฟรนไซส์
            $tSQL .="  AND BCH.FTBchType <> 4 ";

            // ประเภทเอกสาร  (ใบสั่งซื้อ , ใบสั่งสินค้าสำนักงานใหญ่)
            if(isset($tSearchTypeDocument) && !empty($tSearchTypeDocument)){
                if ($tSearchTypeDocument == 0) { //ทั้งหมด
                    $tSQL .= "";
                } elseif ($tSearchTypeDocument == 1) { //จะเอาแต่ใบสั่งซื้อ
                    $tSQL .= " AND PBHD.FTXphStaDoc = 99 "; //ทำให้มันค้นหาใบสั่งสินค้าสำนักงานใหญ่ ไม่เจอเลยสักใบ
                } 
            }
            
            $tSQL .="   UNION ALL ";
            $tSQL .="   SELECT     
                            POHD.FTXphDocNo,
                            POHD.FTBchCode,
                            POHD.FTCreateBy,
                            POHD.FTXphApvCode,
                            POHD.FTXphStaApv,
                            POHD.FDXphDocDate 
                        FROM TAPTPoHD         		POHD    WITH (NOLOCK)
                        LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON POHD.FTBchCode = BCH.FTBchCode     
                        WHERE POHD.FTXphStaApv = 1 ";

            if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
                $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL .= " AND POHD.FTBchCode IN ($tBchCode) ";
            }else{
                //ใบขอซื้อ ที่เป็นของแฟรนไซส์เท่านั้น
                $tSQL .= " AND BCH.FTBchType = 4 ";
            }

            // ประเภทเอกสาร  (ใบสั่งซื้อ , ใบสั่งสินค้าสำนักงานใหญ่)
            if(isset($tSearchTypeDocument) && !empty($tSearchTypeDocument)){
                if ($tSearchTypeDocument == 0) { //ทั้งหมด
                    $tSQL .= "";
                } elseif ($tSearchTypeDocument == 2) { //ใบสั่งสินค้าสำนักงานใหญ่
                    $tSQL .= " AND POHD.FTXphStaDoc = 99 "; //ทำให้มันค้นหาใบสั่งซื้อไม่เจอเลยสักใบ
                } 
            }
        }

           
        $tSQL .=    "   ) A ";
        $tSQL .=    "   LEFT JOIN TCNTPdtReqMgtHD   MGTHD   WITH (NOLOCK) ON MGTHD.FTXrhDocPrBch    = A.FTXphDocNo       AND MGTHD.FNXrhDocType != 3
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON A.FTBchCode            = BCHL.FTBchCode     AND BCHL.FNLngID    = ".$this->db->escape($nLngID)."  
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON A.FTCreateBy           = USRL.FTUsrCode     AND USRL.FNLngID    = ".$this->db->escape($nLngID)." 
                        LEFT JOIN TCNMUser_L        USRLAPV WITH (NOLOCK) ON A.FTXphApvCode         = USRLAPV.FTUsrCode  AND USRLAPV.FNLngID = ".$this->db->escape($nLngID)." 
                        LEFT JOIN TCNMSpl_L         TOSPL   WITH (NOLOCK) ON MGTHD.FTXrhRefFrm	    = TOSPL.FTSplCode	 AND (MGTHD.FNXrhDocType = 2 OR MGTHD.FNXrhDocType = 4) AND TOSPL.FNLngID = ".$this->db->escape($nLngID)." 
                        LEFT JOIN TCNMBranch_L      TOBCH   WITH (NOLOCK) ON MGTHD.FTXrhBchTo	    = TOBCH.FTBchCode	 AND (MGTHD.FNXrhDocType = 1 OR MGTHD.FNXrhDocType = 5) AND TOBCH.FNLngID = ".$this->db->escape($nLngID)." 
                        WHERE A.FTXphStaApv = 1 ";

        // รหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((A.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),A.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ( ((A.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (A.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
            $tSQL .= " OR ((MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (MGTHD.FTXrhBchTo BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom')) )";
        }

        // ค้นหาจากผู้จำหน่าย - ถึงผู้จำหน่าย
        if(!empty($tSearchSplCodeFrom) && !empty($tSearchSplCodeTo)){
            $tSQL .= " AND ((MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeFrom' AND '$tSearchSplCodeTo') OR (MGTHD.FTXrhRefFrm BETWEEN '$tSearchSplCodeTo' AND '$tSearchSplCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((A.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (A.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาจากวันที่เอกสาร (ใบขอโอน , ใบขอซื้อ)
        if(!empty($tSearchDocDateRef)){
            $tSQL .= " AND ((MGTHD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateRef 00:00:00') AND CONVERT(datetime,'$tSearchDocDateRef 23:59:59')))";
        }

        if(isset($tSearchStaApv) && !empty($tSearchStaApv)){
            if ($tSearchStaApv == 0) { //ทั้งหมด
                $tSQL .= "";
            } elseif ($tSearchStaApv == 1) { //รอยืนยัน
                $tSQL .= " AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FTXrhStaDoc,'') = '' AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
            } elseif ($tSearchStaApv == 2) { //ยืนยันแล้วรออนุมัติ
                $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc = 1 AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
            } elseif ($tSearchStaApv == 3) { //อนุมัติแล้ว
                $tSQL .= " AND MGTHD.FTXrhStaDoc = '1' AND MGTHD.FTXrhStaPrcDoc >= 2 AND ISNULL(MGTHD.FNXrhDocType,'') != '' ";
            } elseif ($tSearchStaApv == 4) { //เอกสารรอดำเนินการ
                $tSQL .= " AND ISNULL(MGTHD.FNXrhDocType,'') = '' ";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //ข้อมูลรายละเอียด HD
    public function FSaMMNGGetDetailHD($ptDocumentNumber){
        $tDocumentNumber    = $ptDocumentNumber;
        $nLngID             = $this->session->userdata("tLangEdit");

        $tSQL = "SELECT 
                    PBHD.FTAgnCode ,
                    PBHD.FTBchCode ,
                    PBHD.FTXphDocNo ,
                    PBHD.FDXphDocDate ,
                    BCHL.FTBchName ,
                    1 AS 'TYPE_MGT'
                FROM TCNTPdtReqHqHD     PBHD WITH (NOLOCK) 
                LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON PBHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                WHERE PBHD.FTXphDocNo = ".$this->db->escape($tDocumentNumber)." ";

        $tSQL .= " UNION ALL ";

        $tSQL .= "SELECT 
                    PO.FTAgnCode ,
                    PO.FTBchCode ,
                    PO.FTXphDocNo ,
                    PO.FDXphDocDate ,
                    BCHL.FTBchName ,
                    2 AS 'TYPE_MGT'
                FROM TAPTPoHD     		PO WITH (NOLOCK) 
                LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON PO.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLngID)."
                WHERE PO.FTXphDocNo = ".$this->db->escape($tDocumentNumber)." ";

        $oQuery     = $this->db->query($tSQL);
        $oDataList  = $oQuery->result_array();
        return $oDataList;
    }

    //ข้อมูลรายละเอียด DT
    public function FSaMMNGGetDetailDT($ptDocumentNumber){
        $tDocumentNumber    = $ptDocumentNumber;
        $nLngID             = $this->session->userdata("tLangEdit");
        $tSQL = "SELECT 
                    DT.*, 
                    STKBAL.FCStkQty ,
                    ISNULL(RTB.FCXpdQtyTR, 0) 			AS FCXpdQtyTR,
                    ISNULL(RSPL.FCXpdQtyPRS, 0) 		AS FCXpdQtyPRS,
                    ISNULL(RJ.FCXpdQtyCancel, 0) 		AS FCXpdQtyCancel,
                    TOBCH.FTBchCode                     AS MGTBchCode ,
                    TOBCH.FTBchName                     AS MGTBchName ,
                    TOSPL.FTSplCode						AS MGTSplCode ,
                    TOSPL.FTSplName                     AS MGTSplName ,
                    PDTSPL.FTSplCode                    AS PDTSPLCode ,
	                PDTSPL.FTSplName                    AS PDTSPLName 
                FROM (
                    SELECT  
                        DT.FTPdtCode ,
                        DT.FTXpdPdtName ,
                        DT.FTPunCode ,
                        DT.FTPunName ,
                        DT.FCXpdQty ,
                        DT.FCXpdFactor ,
                        DT.FTXpdBarCode ,
                        DT.FNXpdSeqNo ,
                        HD.FTBchCode ,
                        HD.FTWahCode ,
                        1 AS 'TYPE_MGT'
                        FROM TCNTPdtReqHqDT     DT WITH (NOLOCK) 
                    INNER JOIN TCNTPdtReqHqHD   HD ON DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode AND DT.FTAgnCode = HD.FTAgnCode
                    WHERE HD.FTXphDocNo = ".$this->db->escape($tDocumentNumber)."

                    UNION ALL

                    SELECT 
                        DT.FTPdtCode ,
                        DT.FTXpdPdtName ,
                        DT.FTPunCode ,
                        DT.FTPunName ,
                        DT.FCXpdQty ,
                        DT.FCXpdFactor ,
                        DT.FTXpdBarCode ,
                        DT.FNXpdSeqNo ,
                        HD.FTBchCode ,
                        HD.FTWahCode ,
                        2 AS 'TYPE_MGT'
                        FROM TAPTPoDT     	DT WITH (NOLOCK) 
                    INNER JOIN TAPTPoHD     HD ON DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode 
                    WHERE HD.FTXphDocNo = ".$this->db->escape($tDocumentNumber)."

                ) DT
                LEFT JOIN (
                    SELECT  DT.FNXprSeqNo,  
                            DT.FTPdtCode, 
                            DT.FCXpdQtyTR, 
                            HD.FTXphDocNo, 
                            HD.FTXrhAgnTo, 
                            HD.FTXrhBchTo
                    FROM TCNTPdtReqMgtDT DT
                    INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                    WHERE ( HD.FNXrhDocType = 1 OR HD.FNXrhDocType = 5 ) AND HD.FTXrhDocPrBch = '$tDocumentNumber'
                
                ) RTB ON DT.FTPdtCode = RTB.FTPdtCode AND DT.FNXpdSeqNo = RTB.FNXprSeqNo
                LEFT JOIN (
                    SELECT  DT.FNXprSeqNo,  
                            DT.FTPdtCode, 
                            DT.FCXpdQtyPRS, 
                            HD.FTXphDocNo, 
                            HD.FTXrhRefFrm
                    FROM TCNTPdtReqMgtDT DT
                    INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                    WHERE ( HD.FNXrhDocType = 2 OR HD.FNXrhDocType = 4 ) AND HD.FTXrhDocPrBch = '$tDocumentNumber'
                ) RSPL ON DT.FTPdtCode = RSPL.FTPdtCode AND DT.FNXpdSeqNo = RSPL.FNXprSeqNo
                LEFT JOIN (
                    SELECT  DT.FNXprSeqNo,  
                            DT.FTPdtCode, 
                            DT.FCXpdQtyCancel, 
                            HD.FTXphDocNo
                    FROM TCNTPdtReqMgtDT DT
                    INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                    WHERE HD.FNXrhDocType = 3 AND HD.FTXrhDocPrBch = '$tDocumentNumber'
                ) RJ ON DT.FTPdtCode = RJ.FTPdtCode  AND DT.FNXpdSeqNo = RJ.FNXprSeqNo
                LEFT JOIN TCNTPdtStkBal     STKBAL  WITH (NOLOCK) ON DT.FTBchCode = STKBAL.FTBchCode AND STKBAL.FTWahCode = DT.FTWahCode AND DT.FTPdtCode = STKBAL.FTPdtCode 
                LEFT JOIN TCNMSpl_L         TOSPL   WITH (NOLOCK) ON RSPL.FTXrhRefFrm = TOSPL.FTSplCode	AND TOSPL.FNLngID = '$nLngID'
                LEFT JOIN TCNMBranch_L      TOBCH   WITH (NOLOCK) ON RTB.FTXrhBchTo	= TOBCH.FTBchCode	AND TOBCH.FNLngID = '$nLngID' 
                LEFT JOIN (
                    SELECT A.* FROM (
                        SELECT  
                        ROW_NUMBER() OVER (PARTITION BY PDTSPL.FTPdtCode ORDER BY PDTSPL.FTSplCode DESC) AS PARTITIONBYSPL,
                        PDTSPL.FTPdtCode , 
                        PDTSPL.FTSplCode ,
                        PDTSPL_L.FTSplName
                        FROM TCNMPdtSpl PDTSPL
                        LEFT JOIN TCNMSpl_L PDTSPL_L WITH (NOLOCK) ON PDTSPL.FTSplCode = PDTSPL_L.FTSplCode
                    ) AS A WHERE A.PARTITIONBYSPL = 1
                ) PDTSPL ON DT.FTPdtCode = PDTSPL.FTPdtCode ";

        $oQuery = $this->db->query($tSQL);
                
        if($oQuery->num_rows() > 0){
            $oDataList  = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $oQuery->num_rows(),
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rnAllRow'      => 0,
                'rtDesc'        => 'data not found',
            );
        }

        return $aResult;
    }

    //เพิ่มข้อมูลใน DT
    public function FSaMMNGInsertMNGDT($paParam){
        $this->db->insert('TCNTPdtReqMgtDT',$paParam);
    }

    //เพิ่มข้อมูลใน HD
    public function FSaMMNGInsertMNGHD($paParam){
        $this->db->insert('TCNTPdtReqMgtHD',$paParam);
    }

    //ลบข้อมูล HD DT 
    public function FSaMMNGDeleteHDAndDT($ptDocumentNumber){
        $this->db->where_in('FTXrhDocPrBch',$ptDocumentNumber);
        $this->db->delete('TCNTPdtReqMgtHD');

        $this->db->like('FTXphDocNo', $ptDocumentNumber);
        $this->db->delete('TCNTPdtReqMgtDT');
    }

    //Move ข้อมูลจาก DT To Temp
    public function FSaMMNGMoveDTToTemp($ptDocumentNumber){
        $tSessionID  = $this->session->userdata('tSesSessionID');

        $this->db->where_in('FTXthDocNo',$ptDocumentNumber);
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->delete('TCNTPdtReqMgtTemp');

        $tSQL   = " INSERT INTO TCNTPdtReqMgtTemp (
                        FTPdtCode , FTXpdPdtName , FCXpdQtyTR ,
                        FTPunCode , FCXpdQtyPRS , FTPunName ,
                        FCXpdFactor , FCXpdQtyCancel , FTXpdBarCode ,
                        FCXpdQty , FCXpdQtyAll , FTXrhAgnFrm ,
                        FTXrhRefFrm , FTXrhAgnTo ,  FTXrhBchTo ,
                        FTSessionID , FTXthDocNo , FNXprSeqNo )
                    SELECT 
                        DT.FTPdtCode, 
                        DT.FTXpdPdtName, 
                        ISNULL(RTB.FCXpdQtyTR, 0) 			AS FCXpdQtyTR,
                        DT.FTPunCode ,
                        ISNULL(RSPL.FCXpdQtyPRS, 0) 		AS FCXpdQtyPRS,
                        DT.FTPunName ,
                        DT.FCXpdFactor ,
                        ISNULL(RJ.FCXpdQtyCancel, 0) 		AS FCXpdQtyCancel,
                        DT.FTXpdBarCode ,
                        DT.FCXpdQty ,
                        DT.FCXpdQtyAll ,
                        null                        AS FTXrhAgnFrm ,
                        RSPL.FTXrhRefFrm            AS FTXrhRefFrm , 
                        null                        AS FTXrhAgnTo ,  
                        RTB.FTXrhBchTo              AS FTXrhBchTo ,
                        '$tSessionID'               AS FTSessionID , 
                        '$ptDocumentNumber'         AS FTXthDocNo , 
                        DT.FNXpdSeqNo               AS FNXprSeqNo 
                    FROM (
                        SELECT  
                            DT.FTPdtCode ,
                            DT.FTXpdPdtName ,
                            DT.FTPunCode ,
                            DT.FTPunName ,
                            DT.FCXpdQty ,
                            DT.FCXpdQtyAll ,
                            DT.FCXpdFactor ,
                            DT.FTXpdBarCode ,
                            DT.FNXpdSeqNo ,
                            HD.FTBchCode ,
                            HD.FTWahCode ,
                            1 AS 'TYPE_MGT'
                            FROM TCNTPdtReqHqDT     DT WITH (NOLOCK) 
                        INNER JOIN TCNTPdtReqHqHD   HD ON DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode AND DT.FTAgnCode = HD.FTAgnCode
                        WHERE HD.FTXphDocNo = ".$this->db->escape($ptDocumentNumber)."

                        UNION ALL

                        SELECT 
                            DT.FTPdtCode ,
                            DT.FTXpdPdtName ,
                            DT.FTPunCode ,
                            DT.FTPunName ,
                            DT.FCXpdQty ,
                            DT.FCXpdQtyAll,
                            DT.FCXpdFactor ,
                            DT.FTXpdBarCode ,
                            DT.FNXpdSeqNo ,
                            HD.FTBchCode ,
                            HD.FTWahCode ,
                            2 AS 'TYPE_MGT'
                            FROM TAPTPoDT     	DT WITH (NOLOCK) 
                        INNER JOIN TAPTPoHD     HD ON DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode 
                        WHERE HD.FTXphDocNo = ".$this->db->escape($ptDocumentNumber)."

                    ) DT
                    LEFT JOIN (
                        SELECT  DT.FNXprSeqNo, 
                                DT.FTPdtCode, 
                                DT.FCXpdQtyTR, 
                                HD.FTXphDocNo, 
                                HD.FTXrhAgnTo, 
                                HD.FTXrhBchTo
                        FROM TCNTPdtReqMgtDT DT
                        INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                        WHERE (HD.FNXrhDocType = 1 OR HD.FNXrhDocType = 5) AND HD.FTXrhDocPrBch = '$ptDocumentNumber'
                    ) RTB ON DT.FTPdtCode = RTB.FTPdtCode AND DT.FNXpdSeqNo = RTB.FNXprSeqNo
                    LEFT JOIN (
                        SELECT  DT.FNXprSeqNo,
                                DT.FTPdtCode, 
                                DT.FCXpdQtyPRS, 
                                HD.FTXphDocNo, 
                                HD.FTXrhRefFrm
                        FROM TCNTPdtReqMgtDT DT
                        INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                        WHERE (HD.FNXrhDocType = 2 OR HD.FNXrhDocType = 4) AND HD.FTXrhDocPrBch = '$ptDocumentNumber'
                    ) RSPL ON DT.FTPdtCode = RSPL.FTPdtCode AND DT.FNXpdSeqNo = RSPL.FNXprSeqNo
                    LEFT JOIN (
                        SELECT  DT.FNXprSeqNo,
                                DT.FTPdtCode, 
                                DT.FCXpdQtyCancel, 
                                HD.FTXphDocNo
                        FROM TCNTPdtReqMgtDT DT
                        INNER JOIN TCNTPdtReqMgtHD HD ON DT.FTAgnCode = HD.FTAgnCode AND DT.FTXphDocNo = HD.FTXphDocNo AND DT.FTBchCode = HD.FTBchCode
                        WHERE HD.FNXrhDocType = 3 AND HD.FTXrhDocPrBch = '$ptDocumentNumber'
                    ) RJ ON DT.FTPdtCode = RJ.FTPdtCode AND DT.FNXpdSeqNo = RJ.FNXprSeqNo ";
        $this->db->query($tSQL);
    }

    //Update จำนวน
    public function FSaMMNGUpdateQTYinTemp($paUpdate,$paWhere){
        $this->db->set($paUpdate['NameField'], $paUpdate['Value']);
        $this->db->where('FTXthDocNo', $paWhere['FTXthDocNo']);
        $this->db->where('FNXprSeqNo', $paWhere['FNXprSeqNo']);
        $this->db->where('FTPdtCode', $paWhere['FTPdtCode']);
        $this->db->where('FTSessionID', $paWhere['FTSessionID']);
        $this->db->update('TCNTPdtReqMgtTemp');
    }

    //Update จำนวน ทั้งหมด จากปุ่ม "สั่งซื้อทั้งหมด"
    public function FSaMMNGUpdateQTYAllinTemp($paWhere){
        $this->db->set('FCXpdQtyCancel', 0);
        $this->db->set('FTXrhBchTo', null);
        $this->db->set('FCXpdQtyPRS', $paWhere['nQTY']);
        $this->db->set('FTXrhRefFrm', $paWhere['tSPLCode']);
        $this->db->where('FNXprSeqNo', $paWhere['nSEQ']);
        $this->db->where('FTPdtCode', $paWhere['tPDTCode']);
        $this->db->where('FTSessionID', $paWhere['FTSessionID']);
        $this->db->update('TCNTPdtReqMgtTemp');
    }

    //Move ข้อมูลจาก Temp To HD
    public function FSaMMNGMoveTempToHD($aGetDetailHD , $ptDocumentNumber , $ptTypeDoc , $ptMNGTypeDocument){
        $tSessionID  = $this->session->userdata('tSesSessionID');
        $tBCHFrom    = $aGetDetailHD[0]['FTBchCode'];
        $tAGNFrom    = $aGetDetailHD[0]['FTAgnCode'];

        //ถ้าเป็นเอกสารจากแฟรนไซส์ จะต้องมีสาขาเป็น HQFC ถ้าเป็นของ สาขาจะใช้ รหัส default
        if($ptMNGTypeDocument == 1){ //เป็นการเข้ามาแบบหน้า ใบสั่งสินค้าจากสาขา

            //ถ้าว่าเอกสารนี้ HQFC คืออะไร
            $tSQLSelectFindBchHqFc = "  SELECT TOP 1 B.FTBCHCode FROM TCNMBranch A 
                                        LEFT JOIN TCNMBranch B ON A.FTAgnCode = B.FTAgnCode
                                        WHERE A.FTBchCode = '$tBCHFrom' AND B.FTBchStaHQ = 1 ";
            $oQuery = $this->db->query($tSQLSelectFindBchHqFc);
            if($oQuery->num_rows() > 0){
                $oResultBCH   = $oQuery->result_array();
                $tBCHHQ      = $oResultBCH[0]['FTBCHCode'];
            }else{
                $tBCHHQ      = $this->session->userdata('tSesUsrBchCodeDefault');
            }
            $tAGNFrom    = $aGetDetailHD[0]['FTAgnCode'];
        }else{ //เป็นการเข้ามาแบบหน้า ใบสั่งสินค้าจากสาขา-ลูกค้า
            $tBCHHQ      = $this->session->userdata('tSesUsrBchCodeDefault');
            $tAGNFrom    = '';
        }

        if($ptTypeDoc == 1){ //เอกสารถูกสร้างมาจาก ใบสั่งสินค้าจากสาขา (PRB)
            //ใบขอโอน
            $tTypeDocTr         = 1;    
            $tRemarkTr          = 'Create Transfer BCH';
            $tPrefixTr          = 'TR';

            //ใบขอซื้อ
            $tTypeDocPrs        = 2;  
            $tRemarkPrs         = 'Create Purchare SPL';
            $tPrefixPrs         = 'PRS';
        }else{ //เอกสารถูกสร้างมาจาก ใบสั่งซื้อ (PO)
            //ใบสั่งขาย
            $tTypeDocTr         = 5;    
            $tRemarkTr          = 'Create Sale Order';
            $tPrefixTr          = 'SO';

            //ใบขอซื้อ
            $tTypeDocPrs        = 4;   
            $tRemarkPrs         = 'Create Purchare SPL (FC)';
            $tPrefixPrs         = 'PRS';
        }

        $tSQL   = " INSERT INTO TCNTPdtReqMgtHD (
                            FTAgnCode , FTBchCode , FTXphDocNo ,
                            FTXrhDocPrBch , FNXrhDocType ,
                            FTXrhAgnFrm , FTXrhRefFrm , FTXrhAgnTo , 
                            FTXrhBchTo , FTXrhStaDoc , FTXrhStaPrcDoc ,
                            FTXphRmk , FDXphDocDate , FDLastUpdOn , FTLastUpdBy , 
                            FDCreateOn , FTCreateBy )

                        SELECT 
                            '$tAGNFrom'         AS FTAgnCode , 
                            '$tBCHHQ'         AS FTBchCode , 
                            A.FTXphDocNo        AS FTXphDocNo ,
                            '$ptDocumentNumber' AS FTXrhDocPrBch ,
                            A.FNXrhDocType      AS FNXrhDocType ,
                            A.FTXrhAgnFrm       AS FTXrhAgnFrm ,     
                            A.FTXrhRefFrm       AS FTXrhRefFrm ,
                            A.FTXrhAgnTo        AS FTXrhAgnTo ,
                            A.FTXrhBchTo        AS FTXrhBchTo ,  
                            null                AS FTXrhStaDoc ,
                            null 				AS FTXrhStaPrcDoc ,
                            A.FTXphRmk          AS FTXphRmk ,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDXphDocDate,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy
                        FROM (

                            --ขอโอน
                            SELECT 
                                CONCAT(
                                    '$tPrefixTr',
                                    ROW_NUMBER() OVER(ORDER BY FTXrhBchTo DESC),
                                    '$ptDocumentNumber','-#####'
                                )                                                                    AS FTXphDocNo ,  
                                '$tTypeDocTr'  								                         AS FNXrhDocType , 
                                '$tAGNFrom'                                                          AS FTXrhAgnFrm ,
                                '$tBCHFrom' 								                         AS FTXrhRefFrm ,
                                null                                                                 AS FTXrhAgnTo ,
                                TEMP.FTXrhBchTo			                                             AS FTXrhBchTo ,
                                '$tRemarkTr'                                                         AS FTXphRmk 
                            FROM TCNTPdtReqMgtTemp TEMP
                            WHERE ISNULL(FTXrhBchTo,'') <> '' AND ISNULL(FCXpdQtyTR,0) > 0 AND FTSessionID = '$tSessionID'
                            GROUP BY FTXrhBchTo

                            UNION ALL

                            --ขอซื้อ
                            SELECT 
                                CONCAT(
                                    '$tPrefixPrs',
                                    ROW_NUMBER() OVER(ORDER BY FTXrhRefFrm DESC),
                                    '$ptDocumentNumber','-#####'
                                )                                                                    AS FTXphDocNo ,  
                                '$tTypeDocPrs' 							                             AS FNXrhDocType , 
                                null                                                                 AS FTXrhAgnFrm ,
                                TEMP.FTXrhRefFrm                                                     AS FTXrhRefFrm,
                                '$tAGNFrom'                                                          AS FTXrhAgnTo ,
                                '$tBCHFrom' 							                             AS FTXrhBchTo ,
                                '$tRemarkPrs'                                                        AS FTXphRmk 
                            FROM TCNTPdtReqMgtTemp TEMP
                            WHERE ISNULL(FTXrhRefFrm,'') <> '' AND ISNULL(FCXpdQtyPRS,0) > 0 AND FTSessionID = '$tSessionID'
                            GROUP BY FTXrhRefFrm

                            UNION ALL

                            --ไม่อนุมัติ
                            SELECT 
                                CONCAT(
                                    'RJ',
                                    ROW_NUMBER() OVER(ORDER BY FCXpdQtyCancel DESC),
                                    '$ptDocumentNumber','-#####'
                                )                                                                    AS FTXphDocNo ,
                                '3' 								                                 AS FNXrhDocType ,  
                                null                                                                 AS FTXrhAgnFrm ,
                                null                                                                 AS FTXrhRefFrm,
                                null                                                                 AS FTXrhAgnTo ,
                                null 							                                     AS FTXrhBchTo ,
                                'Reject Product'                                                     AS FTXphRmk 
                            FROM TCNTPdtReqMgtTemp TEMP
                            WHERE ISNULL(FCXpdQtyCancel,0) <> 0 AND FTSessionID = '$tSessionID' 
                            GROUP BY FCXpdQtyCancel 
                        ) AS A ";
        $this->db->query($tSQL);
    }

    //Move ข้อมูลจาก Temp To DT
    public function FSaMMNGMoveTempToDT($aGetDetailHD , $ptDocumentNumber , $ptTypeDoc , $ptMNGTypeDocument){
        $tSessionID  = $this->session->userdata('tSesSessionID');
        $tBCHFrom    = $aGetDetailHD[0]['FTBchCode'];

        //ถ้าเป็นเอกสารจากแฟรนไซส์ จะต้องมีสาขาเป็น HQFC ถ้าเป็นของ สาขาจะใช้ รหัส default
        if($ptMNGTypeDocument == 1){ //เป็นการเข้ามาแบบหน้า ใบสั่งสินค้าจากสาขา
            //ถ้าว่าเอกสารนี้ HQFC คืออะไร
            $tSQLSelectFindBchHqFc = "  SELECT TOP 1 B.FTBCHCode FROM TCNMBranch A 
                                        LEFT JOIN TCNMBranch B ON A.FTAgnCode = B.FTAgnCode
                                        WHERE A.FTBchCode = '$tBCHFrom' AND B.FTBchStaHQ = 1 ";
            $oQuery = $this->db->query($tSQLSelectFindBchHqFc);
            if($oQuery->num_rows() > 0){
                $oResultBCH   = $oQuery->result_array();
                $tBCHHQ      = $oResultBCH[0]['FTBCHCode'];
            }else{
                $tBCHHQ      = $this->session->userdata('tSesUsrBchCodeDefault');
            }
            $tAGNFrom    = $aGetDetailHD[0]['FTAgnCode'];
        }else{ //เป็นการเข้ามาแบบหน้า ใบสั่งสินค้าจากสาขา-ลูกค้า
            $tBCHHQ      = $this->session->userdata('tSesUsrBchCodeDefault');
            $tAGNFrom    = '';
        }

        if($ptTypeDoc == 1){ //ใบสั่งสินค้าจากสาขา PRB
            //ใบขอโอน
            $tTypeDocTr     = 1;  

            //ใบขอซื้อ
            $tTypeDocPrs    = 2;
        }else{ //ใบสั่งซื้อ PO
            //ใบสั่งขาย
            $tTypeDocTr     = 5;   

            //ใบขอซื้อ
            $tTypeDocPrs    = 4;   
        }

        $tSQL   = " INSERT INTO TCNTPdtReqMgtDT (
                            FTAgnCode , FTBchCode , FTXphDocNo ,
                            FNXpdSeqNo , FNXprSeqNo , FTPdtCode ,
                            FTXpdPdtName , FCXpdQtyTR , FTPunCode ,
                            FCXpdQtyPRS , FTPunName , FCXpdFactor ,
                            FCXpdQtyCancel , FTXpdBarCode , FCXpdQty ,
                            FCXpdQtyAll , FTXpdRmk , FDLastUpdOn , 
                            FTLastUpdBy , FDCreateOn , FTCreateBy )

                        --ขอโอน
                        SELECT 
                            '$tAGNFrom'         AS FTAgnCode , 
                            '$tBCHHQ'           AS FTBchCode , 
                            REFHD.FTXphDocNo    AS FTXphDocNo ,
		                    ROW_NUMBER() OVER( PARTITION BY FTXphDocNo ORDER BY FTXphDocNo DESC) AS FNXpdSeqNo , 
                            TEMP.FNXprSeqNo     AS FNXprSeqNo ,
                            TEMP.FTPdtCode      AS FTPdtCode ,
                            TEMP.FTXpdPdtName   AS FTXpdPdtName , 
                            TEMP.FCXpdQtyTR     AS FCXpdQtyTR ,
                            TEMP.FTPunCode      AS FTPunCode ,
                            '0'                 AS FCXpdQtyPRS ,
                            TEMP.FTPunName      AS FTPunName ,     
                            TEMP.FCXpdFactor    AS FCXpdFactor ,
                            '0'                 AS FCXpdQtyCancel ,
                            TEMP.FTXpdBarCode   AS FTXpdBarCode ,  
                            TEMP.FCXpdQty       AS FCXpdQty ,
                            TEMP.FCXpdQtyAll 	AS FCXpdQtyAll ,
                            null                AS FTXpdRmk ,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy
                        FROM TCNTPdtReqMgtTemp TEMP
                        LEFT JOIN TCNTPdtReqMgtHD REFHD  ON REFHD.FTXrhBchTo = TEMP.FTXrhBchTo AND REFHD.FNXrhDocType = '$tTypeDocTr'
                        WHERE ISNULL(TEMP.FTXrhBchTo,'') <> '' AND ISNULL(TEMP.FCXpdQtyTR,0) > 0  AND TEMP.FTSessionID = '$tSessionID' 
                        AND REFHD.FTXrhDocPrBch = '$ptDocumentNumber'
                        
                        UNION ALL 

                        --ขอซื้อ
                        SELECT 
                            '$tAGNFrom'         AS FTAgnCode , 
                            '$tBCHHQ'           AS FTBchCode , 
                            REFHD.FTXphDocNo    AS FTXphDocNo ,
		                    ROW_NUMBER() OVER(PARTITION BY FTXphDocNo ORDER BY FTXphDocNo DESC) AS FNXpdSeqNo , 
                            TEMP.FNXprSeqNo     AS FNXprSeqNo ,
                            TEMP.FTPdtCode      AS FTPdtCode ,
                            TEMP.FTXpdPdtName   AS FTXpdPdtName , 
                            '0'                 AS FCXpdQtyTR ,
                            TEMP.FTPunCode      AS FTPunCode ,
                            TEMP.FCXpdQtyPRS    AS FCXpdQtyPRS ,
                            TEMP.FTPunName      AS FTPunName ,     
                            TEMP.FCXpdFactor    AS FCXpdFactor ,
                            '0'                 AS FCXpdQtyCancel ,
                            TEMP.FTXpdBarCode   AS FTXpdBarCode ,  
                            TEMP.FCXpdQty       AS FCXpdQty ,
                            TEMP.FCXpdQtyAll 	AS FCXpdQtyAll ,
                            null                AS FTXpdRmk ,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy
                        FROM TCNTPdtReqMgtTemp TEMP
                        LEFT JOIN TCNTPdtReqMgtHD REFHD  ON REFHD.FTXrhRefFrm = TEMP.FTXrhRefFrm AND REFHD.FNXrhDocType = '$tTypeDocPrs'
                        WHERE ISNULL(TEMP.FTXrhRefFrm,'') <> '' AND ISNULL(TEMP.FCXpdQtyPRS,0) > 0 AND TEMP.FTSessionID = '$tSessionID' 
                        AND REFHD.FTXrhDocPrBch = '$ptDocumentNumber'

                        UNION ALL 

                        --ไม่อนุมัติ
                        SELECT 
                            '$tAGNFrom'         AS FTAgnCode , 
                            '$tBCHHQ'           AS FTBchCode , 
                            REFHD.FTXphDocNo    AS FTXphDocNo ,
		                    ROW_NUMBER() OVER(PARTITION BY FTXphDocNo ORDER BY FTXphDocNo DESC) AS FNXpdSeqNo , 
                            TEMP.FNXprSeqNo     AS FNXprSeqNo ,
                            TEMP.FTPdtCode      AS FTPdtCode ,
                            TEMP.FTXpdPdtName   AS FTXpdPdtName , 
                            '0'                 AS FCXpdQtyTR ,
                            TEMP.FTPunCode      AS FTPunCode ,
                            '0'                 AS FCXpdQtyPRS ,
                            TEMP.FTPunName      AS FTPunName ,     
                            TEMP.FCXpdFactor    AS FCXpdFactor ,
                            TEMP.FCXpdQtyCancel AS FCXpdQtyCancel ,
                            TEMP.FTXpdBarCode   AS FTXpdBarCode ,  
                            TEMP.FCXpdQty       AS FCXpdQty ,
                            TEMP.FCXpdQtyAll 	AS FCXpdQtyAll ,
                            null                AS FTXpdRmk ,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDLastUpdOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTLastUpdBy,
                            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."')                          AS FDCreateOn,
                            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."')      AS FTCreateBy
                        FROM TCNTPdtReqMgtTemp TEMP
                        LEFT JOIN TCNTPdtReqMgtHD REFHD ON REFHD.FNXrhDocType = 3 AND ISNULL(REFHD.FTXrhRefFrm,'') = '' AND ISNULL(REFHD.FTXrhBchTo,'') = '' 
                        WHERE ISNULL(TEMP.FCXpdQtyCancel,0) <> 0 AND TEMP.FTSessionID = '$tSessionID' 
                        AND REFHD.FTXrhDocPrBch = '$ptDocumentNumber' ";
        $this->db->query($tSQL);
    }

    //Delete Temp 
    public function FSxMMNGDeleteTempWhereID(){
        $tSessionID  = $this->session->userdata('tSesSessionID');

        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->delete('TCNTPdtReqMgtTemp');
    }

    //Get ข้อมูลเพื่อไปอนุมัติ
    public function FSaMMNGGetDocRefCallMQ($ptTextDocRef){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL = "SELECT 
                    HD.FTBchCode , 
                    BCH.FTBchName ,
                    HD.FTXphDocNo , 
                    HD.FNXrhDocType , 
                    BCHTO.FTBchCode AS rtBCHCodeTo , 
                    BCHTO.FTBchName AS rtBCHNameTo 
                    FROM TCNTPdtReqMgtHD HD 
                LEFT JOIN TCNMBranch_L BCH ON HD.FTBchCode = BCH.FTBchCode AND BCH.FNLngID = ".$this->db->escape($nLngID)." 
                LEFT JOIN TCNMBranch_L BCHTO ON  HD.FTXrhBchTo = BCHTO.FTBchCode AND BCHTO.FNLngID = ".$this->db->escape($nLngID)." 
                WHERE FTXrhDocPrBch IN ($ptTextDocRef) ";
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
    public function FSaMMNGGetDocRefForExport($ptTextDocRef){
        $tTextDocRef    = $ptTextDocRef['tTextDocRef'];
        $tSQL           = "SELECT HD.FTBchCode , HD.FTXphDocNo , HD.FNXrhDocType FROM TCNTPdtReqMgtHD HD WHERE FTXrhDocPrBch IN ($tTextDocRef) AND (FNXrhDocType = '2' OR FNXrhDocType = '4') ";
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

    //ลบข้อมูลใน Temp
    public function FSaMMNGDeleteInTemp(){
        $tSessionID  = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID',$tSessionID);
        $this->db->delete('TCNTPdtReqMgtTemp');
    }

    
    public function FSaMMNGGetPdtStkBal($paDataSearch){

        // ตั้งค่าตัวแปร
        $tBchCodeOrder  = $paDataSearch['tBchCodeOrder'];
        $tPdtCode       = $paDataSearch['tPdtCode'];
        $tBchCode       = $paDataSearch['tBchCode'];
        $tWahCode       = $paDataSearch['tWahCode'];
        $nLangEdit      = $paDataSearch['nLangEdit'];
        
        $tSQL           = " SELECT 
                                ROW_NUMBER() OVER( PARTITION BY PSB.FTBchCode ORDER BY PSB.FTBchCode ASC ) AS FNByBch,
                                COUNT(1) OVER( PARTITION BY PSB.FTBchCode ORDER BY PSB.FTBchCode ASC ) AS FNMaxBch,
                                PSB.FTBchCode,
                                ISNULL(BCHL.FTBchName,'N/A') AS FTBchName,
                                PSB.FTWahCode,
                                ISNULL(WAHL.FTWahName,'N/A') AS FTWahName,
                                PSB.FCStkQty
                            FROM TCNTPdtStkBal      PSB  WITH(NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL WITH(NOLOCK) ON PSB.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = ".$this->db->escape($nLangEdit)."
                            LEFT JOIN TCNMWaHouse WAH WITH(NOLOCK) ON PSB.FTBchCode = WAH.FTBchCode AND PSB.FTWahCode = WAH.FTWahCode
                            LEFT JOIN TCNMWaHouse_L WAHL WITH(NOLOCK) ON PSB.FTBchCode = WAHL.FTBchCode AND PSB.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID = ".$this->db->escape($nLangEdit)."
                            WHERE PSB.FTPdtCode = '$tPdtCode' AND PSB.FCStkQty <> 0 AND WAH.FTWahStaAlwCntStk = 1";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            $tBchCodeSession = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= " AND PSB.FTBchCode IN ($tBchCodeSession) ";
        }
           
        if( isset($tBchCode) && !empty($tBchCode) ){
            $tSQL .= " AND PSB.FTBchCode = '$tBchCode' ";
        }

        if( isset($tWahCode) && !empty($tWahCode) ){
            $tSQL .= " AND PSB.FTWahCode = ".$this->db->escape($tWahCode)." ";
        }

        if( isset($tBchCodeOrder) && !empty($tBchCodeOrder) ){
            $tSQL .= " AND PSB.FTBchCode != '$tBchCodeOrder' ";
        }

        $oQuery = $this->db->query($tSQL);
        if( $oQuery->num_rows() > 0 ){
            $oDataList  = $oQuery->result_array();
            $aResult = array(
                'aItems'       => $oDataList,
                'tCode'        => '1',
                'tDesc'        => 'found'
            );
        }else{
            $aResult = array(
                'tCode'        => '800',
                'tDesc'        => 'data not found',
            );
        }

        return $aResult;
    }

     // หาว่า ถ้าเป็นแฟรนไซด์ จะต้องไปเอาผู้จำหน่ายใน config
     public function FSxMMNGFindSPLByConfig(){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT
                            CON.FTSysStaUsrValue    AS rtSPLCode,
                            SPLL.FTSplName          AS rtSPLName
                        FROM TSysConfig             CON     WITH (NOLOCK)
                        LEFT JOIN TCNMSpl_L         SPLL    WITH (NOLOCK) ON CON.FTSysStaUsrValue = SPLL.FTSplCode  AND SPLL.FNLngID = ".$this->db->escape($nLngID)." 
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


}