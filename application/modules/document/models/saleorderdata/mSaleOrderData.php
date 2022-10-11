<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mSaleOrderData extends CI_Model {

    // List ข้อมูล
    public function FSaMSOGetDataTableList($paDataCondition){
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
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaApprove'];
        $tSearchStaSale         = $aAdvanceSearch['tSearchStaSale'];
        $tSearchSONo            = $aAdvanceSearch['tSearchSO'];
        $tSearchSODate          = $aAdvanceSearch['tSearchSODate'];
        $tSearchName            = $aAdvanceSearch['tSearchName'];
        $tSearchRefDoc          = $aAdvanceSearch['tSearchRefDoc'];
        $bCheckSearch = false;

        $tSQLStatus = '';
        // ค้นหาสถานะการขาย
        if (!empty($tSearchStaSale) && ($tSearchStaSale != "0")) {
            if ($tSearchStaSale == 2) {
                $tSQLStatus .= " AND ISNULL(SALE.FTXshRefDocNo,'') <> '' ";
            }elseif($tSearchStaSale == 1){
                $tSQLStatus .= " AND A.DOCREF = '' AND A.FTXshStaApv = '1' ";
            }else{
                $tSQLStatus .= " AND A.DOCREF = '' AND A.FTXshStaApv = '1' ";
            }
        }
        

        $tSQL   =   "   SELECT TOP ". get_cookie('nShowRecordInPageList')."	
                        ROW_NUMBER() OVER(ORDER BY FTBchCode,FTXshDocNo DESC) AS FNRowID
                        ,A2.* 
                        FROM
                            (
                            SELECT
                            A.* , 
                            COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY A.FTXshDocNo)  AS PARTITIONBYDOC  , 
                            (
                            SELECT TOP 1
                                COUNT ( HDDocRef_in.FTXshDocNo ) OVER ( PARTITION BY HDDocRef_in.FTXshDocNo ) AS PARTITIONBYDOC2
                                from TARTSoHDDocRef HDDocRef_in
                                LEFT JOIN TCNTPdtPickHD PickHD WITH ( NOLOCK ) ON HDDocRef_in.FTXshRefDocNo = PickHD.FTXthDocNo 
                                AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' )
                                WHERE ( HDDocRef_in.FTXshRefType = 1 OR HDDocRef_in.FTXshRefType = 2 ) 
                                AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' ) AND HDDocRef_in.FTXshDocNo = A.FTXshDocNo AND PickHD.FTXthStaApv = 1
                            ) as PARTITIONBYDOC2,
                            HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF' ,
                            PickHD.FTXthStaApv                                              AS 'DOCREFStaApv' ,
                            CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF',
                            CASE WHEN ISNULL( HDDocRef_in.FTXshRefDocNo, '' ) = '' THEN '1' 
                            ELSE '2' END AS 'SODStatus'
                        FROM ( ";
        $tSQL   .=   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        SOHD.FTBchCode,
                                        SOHD.FTXshStaPrcDoc,
                                        BCHL.FTBchName,
                                        SOHD.FTXshDocNo,
                                        CONVERT(CHAR(10),SOHD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), SOHD.FDXshDocDate,108) AS FTXshDocTime,
                                        SOHD.FTXshStaDoc,
                                        SOHD.FTXshStaApv,
                                        SOHD.FNXshStaRef,
                                        SOHD.FTCreateBy,
                                        SOHD.FDCreateOn,
                                        SOHD.FTCstCode,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        SOHD.FTXshApvCode,
                                        USRLAPV.FTUsrName       AS FTXshApvName,
                                        SALE.FTXshRefDocNo      AS SALEABB,
                                        TAX.FTXshDocNo          AS SALETAX,
                                        BOOK.FTXshRefDocNo       AS SALEBOOK,
                                        ExRef.FTXshRefDocNo      AS ExRef,
                                        BOOKHD.FTXshStaApv      AS BookApv,
                                        SALHD.FTXshDocNo        AS SALDOC,
                                        SALHD.FTBchCode        AS SALBCH,
                                        CONVERT(CHAR(10),ExRef.FDXshRefDocDate,103) AS ExRefDate,
                                        CSTL.FTCstName
                                    FROM TARTSoHD               SOHD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON SOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON SOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRLAPV WITH (NOLOCK) ON SOHD.FTXshApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK) ON SOHD.FTCstCode     = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                                    LEFT JOIN TARTSoHDDocRef    SALE    WITH (NOLOCK) ON SOHD.FTXshDocNo    = SALE.FTXshDocNo   AND SALE.FTXshRefType = 2 AND SALE.FTXshRefKey = 'ABB' 
                                    LEFT JOIN TPSTTaxHDDocRef   TAX     WITH ( NOLOCK ) ON SALE.FTXshRefDocNo = TAX.FTXshRefDocNo
                                    LEFT JOIN TARTSoHDDocRef    BOOK    WITH (NOLOCK) ON SOHD.FTXshDocNo    = BOOK.FTXshDocNo   AND BOOK.FTXshRefType = 2 AND BOOK.FTXshRefKey = 'RTBook' 
                                    LEFT JOIN TARTSoHDDocRef    ExRef   WITH (NOLOCK) ON SOHD.FTXshDocNo    = ExRef.FTXshDocNo   AND ExRef.FTXshRefType = 3 AND ExRef.FTXshRefKey = 'DNW' 
                                    LEFT JOIN TRTTBookHD        BOOKHD   WITH (NOLOCK) ON BOOKHD.FTXshDocNo    = BOOK.FTXshRefDocNo  
                                    LEFT JOIN TRTTSalHD         SALHD   WITH (NOLOCK) ON BOOKHD.FTXshDocNo    = SALHD.FTXshRefInt  
                                WHERE 1=1 ";
                                if($tSearchStaSale == 1){
                                    $tSQL       .= " AND SOHD.FTXshStaApv = '1' AND SOHD.FTXshStaDoc != '3' AND SOHD.FTXshStaPrcDoc = '1' ";
                                }
                                if($tSearchStaSale == 2){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '3' ";
                                }if($tSearchStaSale == 3){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '6' ";
                                }if($tSearchStaSale == 4){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '4' AND ISNULL(BOOK.FTXshRefDocNo,'') = '' AND ISNULL(BOOKHD.FTXshStaApv,'') = '' ";
                                }if($tSearchStaSale == 5){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '4' AND ISNULL(BOOK.FTXshRefDocNo,'') != '' AND ISNULL(BOOKHD.FTXshStaApv,'') = '' ";
                                }if($tSearchStaSale == 6){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '5' ";
                                }if($tSearchStaSale == 7){
                                    $tSQL       .= " AND SOHD.FTXshStaDoc = '3' ";
                                }if($tSearchStaSale == 8){
                                    $tSQL       .= " AND SOHD.FTXshStaDoc != '3' AND SOHD.FTXshStaPrcDoc = '2' ";
                                }


        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND SOHD.FTBchCode IN ($tBchCode) ";
        }

        // ค้นหาจาก เลขที่ใบสั่งขาย
        if(isset($tSearchSONo) && !empty($tSearchSONo)){
            $tSQL   .= " AND SOHD.FTXshDocNo LIKE '%$tSearchSONo%' ";
            $bCheckSearch = true;
        }

        // ค้นหาจาก วันที่ใบสั่งขาย
        if(isset($tSearchSODate) && !empty($tSearchSODate)){
            $tSQL   .= " AND (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchSODate 00:00:00') AND CONVERT(datetime,'$tSearchSODate 23:59:59') ) ";
            $bCheckSearch = true;
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SOHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SOHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
            $bCheckSearch = true;
        }
        
        // ชื่อนามสกุล
        if(isset($tSearchName) && !empty($tSearchName)){
            $tSQL .= " AND CSTL.FTCstName LIKE '%$tSearchName%' ";
            $bCheckSearch = true;
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SOHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (SOHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
            $bCheckSearch = true;
        }

        // วันที่ใบสั่งขาย
        if(!empty($tSearchDocDateFrom)){
            $tSQL .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
            $bCheckSearch = true;
        }

        // วันที่อ้างอิง
        if(!empty($tSearchDocDateTo)){
            $tSQL .= " AND ((ExRef.FDXshRefDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')))";
            $bCheckSearch = true;
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDocAct ) && !empty($tSearchStaDocAct)){
            if ($tSearchStaDocAct == 3) {
                $tSQL .= " AND SOHD.FTXshStaDoc = '$tSearchStaDocAct'";
            } elseif ($tSearchStaDocAct == 2) {
                $tSQL .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaDocAct'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 0";
            }
            $bCheckSearch = true;
        }


        $tSQL   .=  ") Base) AS c ";
        $tSQL   .= " ) AS A LEFT JOIN TARTSoHDDocRef HDDocRef_in WITH (NOLOCK) ON A.FTXshDocNo = HDDocRef_in.FTXshDocNo AND (HDDocRef_in.FTXshRefType = 1 OR HDDocRef_in.FTXshRefType = 2) AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' )  ";
        $tSQL   .= " LEFT JOIN TCNTPdtPickHD PickHD WITH (NOLOCK) ON HDDocRef_in.FTXshRefDocNo = PickHD.FTXthDocNo AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' ) ) AS A2 WHERE 1=1";
        if($tSearchStaSale == 1){
            $tSQL       .= " AND ISNULL(A2.DOCREF,'') = '' ";
            $bCheckSearch = true;
        }if($tSearchStaSale == 8){
            $tSQL       .= " AND ISNULL(PARTITIONBYDOC2,'') = '' ";
            $bCheckSearch = true;
        }

        // ค้นหา Docref
        if(isset($tSearchRefDoc) && !empty($tSearchRefDoc)){
            $tSQL .= " AND ((A2.SALEABB LIKE '%$tSearchRefDoc%') OR (A2.SALETAX LIKE '%$tSearchRefDoc%') OR (A2.SALEBOOK LIKE '%$tSearchRefDoc%') OR (A2.DOCREF LIKE '%$tSearchRefDoc%') OR ( A2.ExREF LIKE '%$tSearchRefDoc%' ) )";
            $bCheckSearch = true;
        }

        
        if($bCheckSearch == false){
            $tSQL .= " AND A2.FNRowID > $aRowLen[0] AND A2.FNRowID <= $aRowLen[1]";
        }
        $tSQL   .= " ORDER BY A2.FNRowID ASC " ;
        // echo $tSQL;
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMSOCountPageDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
                'tSQL'          => $tSQL

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

    // จำนวน
    public function FSnMSOCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        $tSearchList            = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom     = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo       = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom     = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo       = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc          = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct       = $aAdvanceSearch['tSearchStaApprove'];
        $tSearchStaSale         = $aAdvanceSearch['tSearchStaSale'];
        $tSearchSONo            = $aAdvanceSearch['tSearchSO'];
        $tSearchSODate          = $aAdvanceSearch['tSearchSODate'];
        $tSearchName            = $aAdvanceSearch['tSearchName'];
        $tSearchRefDoc          = $aAdvanceSearch['tSearchRefDoc'];

        $tSQL   =   "   SELECT COUNT (SOHD.FTXshDocNo) AS counts
                        FROM TARTSoHD SOHD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON SOHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TARTSoHDDocRef    SALE    WITH (NOLOCK) ON SOHD.FTXshDocNo = SALE.FTXshDocNo AND SALE.FTXshRefType = 2 AND SALE.FTXshRefKey = 'ABB' 
                        WHERE 1=1  ";

        
        $tSQLStatus = '';
        // ค้นหาสถานะการขาย
        if (!empty($tSearchStaSale) && ($tSearchStaSale != "0")) {
            if ($tSearchStaSale == 2) {
                $tSQLStatus .= " AND ISNULL(SALE.FTXshRefDocNo,'') <> '' ";
            }elseif($tSearchStaSale == 1){
                $tSQLStatus .= " AND A.DOCREF = '' AND A.FTXshStaApv = '1' ";
            }else{
                $tSQLStatus .= " AND A.DOCREF = '' AND A.FTXshStaApv = '1' ";
            }
        }
        

        $tSQL   =   "   SELECT COUNT (FTXshDocNo) AS counts
                        FROM
                            (
                            SELECT
                            A.* , 
                            COUNT(HDDocRef_in.FTXshDocNo) OVER (PARTITION BY A.FTXshDocNo)  AS PARTITIONBYDOC  , 
                            (
                            SELECT TOP 1
                                COUNT ( HDDocRef_in.FTXshDocNo ) OVER ( PARTITION BY HDDocRef_in.FTXshDocNo ) AS PARTITIONBYDOC2
                                from TARTSoHDDocRef HDDocRef_in
                                LEFT JOIN TCNTPdtPickHD PickHD WITH ( NOLOCK ) ON HDDocRef_in.FTXshRefDocNo = PickHD.FTXthDocNo 
                                AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' )
                                WHERE ( HDDocRef_in.FTXshRefType = 1 OR HDDocRef_in.FTXshRefType = 2 ) 
                                AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' ) AND HDDocRef_in.FTXshDocNo = A.FTXshDocNo AND PickHD.FTXthStaApv = 1
                            ) as PARTITIONBYDOC2,
                            HDDocRef_in.FTXshRefDocNo                                       AS 'DOCREF' ,
                            PickHD.FTXthStaApv                                              AS 'DOCREFStaApv' ,
                            CONVERT(varchar,HDDocRef_in.FDXshRefDocDate, 103)               AS 'DATEREF',
                            CASE WHEN ISNULL( HDDocRef_in.FTXshRefDocNo, '' ) = '' THEN '1' 
                            ELSE '2' END AS 'SODStatus'
                        FROM ( ";
        $tSQL   .=   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXshDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        SOHD.FTBchCode,
                                        SOHD.FTXshStaPrcDoc,
                                        BCHL.FTBchName,
                                        SOHD.FTXshDocNo,
                                        CONVERT(CHAR(10),SOHD.FDXshDocDate,103) AS FDXshDocDate,
                                        CONVERT(CHAR(5), SOHD.FDXshDocDate,108) AS FTXshDocTime,
                                        SOHD.FTXshStaDoc,
                                        SOHD.FTXshStaApv,
                                        SOHD.FNXshStaRef,
                                        SOHD.FTCreateBy,
                                        SOHD.FDCreateOn,
                                        SOHD.FTCstCode,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        SOHD.FTXshApvCode,
                                        USRLAPV.FTUsrName       AS FTXshApvName,
                                        SALE.FTXshRefDocNo      AS SALEABB,
                                        TAX.FTXshDocNo          AS SALETAX,
                                        BOOK.FTXshRefDocNo       AS SALEBOOK,
                                        ExRef.FTXshRefDocNo      AS ExRef,
                                        BOOKHD.FTXshStaApv      AS BookApv,
                                        SALHD.FTXshDocNo        AS SALDOC,
                                        SALHD.FTBchCode        AS SALBCH,
                                        CONVERT(CHAR(10),ExRef.FDXshRefDocDate,103) AS ExRefDate,
                                        CSTL.FTCstName
                                    FROM TARTSoHD               SOHD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON SOHD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK) ON SOHD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L        USRLAPV WITH (NOLOCK) ON SOHD.FTXshApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK) ON SOHD.FTCstCode     = CSTL.FTCstCode AND CSTL.FNLngID = $nLngID
                                    LEFT JOIN TARTSoHDDocRef    SALE    WITH (NOLOCK) ON SOHD.FTXshDocNo    = SALE.FTXshDocNo   AND SALE.FTXshRefType = 2 AND SALE.FTXshRefKey = 'ABB' 
                                    LEFT JOIN TPSTTaxHDDocRef   TAX     WITH ( NOLOCK ) ON SALE.FTXshRefDocNo = TAX.FTXshRefDocNo
                                    LEFT JOIN TARTSoHDDocRef    BOOK    WITH (NOLOCK) ON SOHD.FTXshDocNo    = BOOK.FTXshDocNo   AND BOOK.FTXshRefType = 2 AND BOOK.FTXshRefKey = 'RTBook' 
                                    LEFT JOIN TARTSoHDDocRef    ExRef   WITH (NOLOCK) ON SOHD.FTXshDocNo    = ExRef.FTXshDocNo   AND ExRef.FTXshRefType = 3 AND ExRef.FTXshRefKey = 'DNW' 
                                    LEFT JOIN TRTTBookHD        BOOKHD   WITH (NOLOCK) ON BOOKHD.FTXshDocNo    = BOOK.FTXshRefDocNo  
                                    LEFT JOIN TRTTSalHD         SALHD   WITH (NOLOCK) ON BOOKHD.FTXshDocNo    = SALHD.FTXshRefInt  
                                WHERE 1=1 ";
                                if($tSearchStaSale == 1){
                                    $tSQL       .= " AND SOHD.FTXshStaApv = '1' AND SOHD.FTXshStaDoc != '3' AND SOHD.FTXshStaPrcDoc = '1' ";
                                }
                                if($tSearchStaSale == 2){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '3' ";
                                }if($tSearchStaSale == 3){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '6' ";
                                }if($tSearchStaSale == 4){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '4' AND ISNULL(BOOK.FTXshRefDocNo,'') = '' AND ISNULL(BOOKHD.FTXshStaApv,'') = '' ";
                                }if($tSearchStaSale == 5){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '4' AND ISNULL(BOOK.FTXshRefDocNo,'') != '' AND ISNULL(BOOKHD.FTXshStaApv,'') = '' ";
                                }if($tSearchStaSale == 6){
                                    $tSQL       .= " AND SOHD.FTXshStaPrcDoc = '5' ";
                                }if($tSearchStaSale == 7){
                                    $tSQL       .= " AND SOHD.FTXshStaDoc = '3' ";
                                }if($tSearchStaSale == 8){
                                    $tSQL       .= " AND SOHD.FTXshStaDoc != '3' AND SOHD.FTXshStaPrcDoc = '2' ";
                                }


        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode    = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL       .= " AND SOHD.FTBchCode IN ($tBchCode) ";
        }

        // ค้นหาจาก เลขที่ใบสั่งขาย
        if(isset($tSearchSONo) && !empty($tSearchSONo)){
            $tSQL   .= " AND SOHD.FTXshDocNo LIKE '%$tSearchSONo%' ";
        }

        // ค้นหาจาก วันที่ใบสั่งขาย
        if(isset($tSearchSODate) && !empty($tSearchSODate)){
            $tSQL   .= " AND (SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchSODate 00:00:00') AND CONVERT(datetime,'$tSearchSODate 23:59:59') ) ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((SOHD.FTXshDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),SOHD.FDXshDocDate,103) LIKE '%$tSearchList%'))";
        }
        
        // ชื่อนามสกุล
        if(isset($tSearchName) && !empty($tSearchName)){
            $tSQL .= " AND CSTL.FTCstName LIKE '%$tSearchName%' ";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((SOHD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (SOHD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // วันที่ใบสั่งขาย
        if(!empty($tSearchDocDateFrom)){
            $tSQL .= " AND ((SOHD.FDXshDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // วันที่อ้างอิง
        if(!empty($tSearchDocDateTo)){
            $tSQL .= " AND ((ExRef.FDXshRefDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDocAct ) && !empty($tSearchStaDocAct)){
            if ($tSearchStaDocAct == 3) {
                $tSQL .= " AND SOHD.FTXshStaDoc = '$tSearchStaDocAct'";
            } elseif ($tSearchStaDocAct == 2) {
                $tSQL .= " AND ISNULL(SOHD.FTXshStaApv,'') = '' AND SOHD.FTXshStaDoc != '3'";
            } elseif ($tSearchStaDocAct == 1) {
                $tSQL .= " AND SOHD.FTXshStaApv = '$tSearchStaDocAct'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        if (!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")) {
            if ($tSearchStaDoc == 1) {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 1";
            } else {
                $tSQL .= " AND SOHD.FNXshStaDocAct = 0";
            }
        }


        $tSQL   .=  ") Base) AS c ";
        $tSQL   .= " ) AS A LEFT JOIN TARTSoHDDocRef HDDocRef_in WITH (NOLOCK) ON A.FTXshDocNo = HDDocRef_in.FTXshDocNo AND (HDDocRef_in.FTXshRefType = 1 OR HDDocRef_in.FTXshRefType = 2) AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' )  ";
        $tSQL   .= " LEFT JOIN TCNTPdtPickHD PickHD WITH (NOLOCK) ON HDDocRef_in.FTXshRefDocNo = PickHD.FTXthDocNo AND ( HDDocRef_in.FTXshRefKey = 'PCK' OR HDDocRef_in.FTXshRefKey = 'PdtPick' OR HDDocRef_in.FTXshRefKey = 'CSTPICK' ) ) AS A2 WHERE 1=1";
        if($tSearchStaSale == 1){
            $tSQL       .= " AND ISNULL(A2.DOCREF,'') = '' ";
        }if($tSearchStaSale == 8){
            $tSQL       .= " AND ISNULL(PARTITIONBYDOC2,'') = '' ";
        }

        // ค้นหา Docref
        if(isset($tSearchRefDoc) && !empty($tSearchRefDoc)){
            $tSQL .= " AND ((A2.SALEABB LIKE '%$tSearchRefDoc%') OR (A2.SALETAX LIKE '%$tSearchRefDoc%') OR (A2.SALEBOOK LIKE '%$tSearchRefDoc%') OR (A2.DOCREF LIKE '%$tSearchRefDoc%') OR ( A2.ExREF LIKE '%$tSearchRefDoc%' ) )";
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

    // จำนวน
    public function FSnMSOGetCRVDocData($ptDocno,$ptUsrCode,$ptType){

        $tChkByPass = '0';

        $tSQL   =   "   SELECT
                        BOOKREF.*
                    FROM
                        TARTSoHD HD
                        LEFT JOIN TARTSoHDDocRef   SOREF ON SOREF.FTXshDocNo = HD.FTXshDocNo AND SOREF.FTXshRefKey = 'RTBook'
                        LEFT JOIN TRTTBookHDDocRef BOOKREF ON SOREF.FTXshRefDocNo = BOOKREF.FTXshDocNo AND BOOKREF.FTXshRefKey = 'RTSale'
                        LEFT JOIN TRTTSalHD CRVHD ON CRVHD.FTXshDocNo = BOOKREF.FTXshRefDocNo
                        LEFT JOIN (
                                SELECT 
                                        B.FTXshDocNo,
                                        CASE WHEN B.FTPdtPick = B.FTPdtMax THEN '1' 
                                                WHEN B.FTPdtPick > 0 THEN '2'        
                                                ELSE '3'                            
                                        END AS FTStaPdtPick
                                FROM (
                                        SELECT
                                                FTXshDocNo AS FTXshDocNo,
                                                MAX(FTPdtMax) AS FTPdtMax,
                                                SUM(FTPdtPick) AS FTPdtPick
                                        FROM (
                                                SELECT 
                                                        FTXshDocNo,
                                                        SUM(1) OVER(PARTITION BY FTXshDocNo) AS FTPdtMax,
                                                        CASE WHEN FDXshDatePick IS NULL THEN 0 ELSE 1 END AS FTPdtPick
                                                FROM TRTTSalDTSL WITH(NOLOCK)
                                        ) A
                                        GROUP BY A.FTXshDocNo
                                ) B
                        ) CRVDTSL ON CRVDTSL.FTXshDocNo = CRVHD.FTXshDocNo
                        WHERE HD.FTXshDocNo = '$ptDocno' AND BOOKREF.FTXshDocNo != '' ";
        if( $ptType == '1'){
            // $tSQL .= " AND CRVDTSL.FTStaPdtPick != '1'";
        }
        $oQuery = $this->db->query($tSQL);


        $tSQLChkUsr   =   "   SELECT TOP 1 LN.FTUsrCode 
                        FROM TCNMUsrLogin LN WITH (NOLOCK)
                        LEFT JOIN TCNMUsrActRole USRROLE WITH ( NOLOCK ) ON LN.FTUsrCode = USRROLE.FTUsrCode 
                        LEFT JOIN TCNTUsrMenu USRRMENU WITH ( NOLOCK ) ON USRROLE.FTRolCode = USRRMENU.FTRolCode AND USRRMENU.FTMnuCode = 'ARC004' 
                        WHERE LN.FTUsrCode = '$ptUsrCode'
                        AND (USRRMENU.FTAutStaFull = '1' OR USRRMENU.FTAutStaAppv = '1') ";
        $oQueryChkUsr = $this->db->query($tSQLChkUsr);

        if ( $oQuery->num_rows() > 0 ){
            if ( $oQueryChkUsr->num_rows() > 0 ){
                $tChkByPass = '1';
            }
            $aResult    = array(
                'aItems'   => $oQuery->result_array(),
                'tCode'    => '1',
                'tByPass'  => $tChkByPass,
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // จำนวน
    public function FSnMSOChkByPass($ptUsrCode){
        $tChkByPass = '0';
        $tSQLChkUsr   =   "   SELECT TOP 1 LN.FTUsrCode 
                        FROM TCNMUsrLogin LN WITH (NOLOCK)
                        LEFT JOIN TCNMUsrActRole USRROLE WITH ( NOLOCK ) ON LN.FTUsrCode = USRROLE.FTUsrCode 
                        LEFT JOIN TCNTUsrMenu USRRMENU WITH ( NOLOCK ) ON USRROLE.FTRolCode = USRRMENU.FTRolCode AND USRRMENU.FTMnuCode = 'ARC004' 
                        WHERE LN.FTUsrCode = '$ptUsrCode'
                        AND (USRRMENU.FTAutStaFull = '1' OR USRRMENU.FTAutStaAppv = '1') ";
        $oQueryChkUsr = $this->db->query($tSQLChkUsr);

        if ( $oQueryChkUsr->num_rows() > 0 ){
            if ( $oQueryChkUsr->num_rows() > 0 ){
                $tChkByPass = '1';
            }else{
                $tChkByPass = '0';
            }
            $aResult    = array(
                'tCode'    => '1',
                'tByPass'  => $tChkByPass,
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'    => '1',
                'tByPass'  => $tChkByPass,
                'tDesc'    => 'data not found.',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // รับข้อมูล SO
    public function FSoMDBRGetAllData($paDocData){
        $ptDocno = $paDocData['rtDocNo'];
        $tSQL   =   "   SELECT
                        CSTL.FTCstName,
                        CONVERT(CHAR(10),HD.FDXshDocDate,103) AS FDXshDocDate
                    FROM
                        TARTSoHD HD
                        LEFT JOIN TCNMCst_L         CSTL    WITH (NOLOCK) ON HD.FTCstCode     = CSTL.FTCstCode AND CSTL.FNLngID = '1'
                        WHERE HD.FTXshDocNo = '$ptDocno' ";
        $oQuery = $this->db->query($tSQL);
        if ( $oQuery->num_rows() > 0 ){
            $aResult    = array(
                'aItems'   => $oQuery->result_array(),
                'tCode'    => '1',
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tDocno'   => $ptDocno,
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // เช็คล็อกอิน
    public function FSnMSOChkAdmin($ptUserName,$ptPassword){

        $tSQL   =   "   SELECT LN.FTUsrCode
                        FROM TCNMUsrLogin LN WITH (NOLOCK)
                        LEFT JOIN TCNMUsrActRole USRROLE WITH ( NOLOCK ) ON LN.FTUsrCode = USRROLE.FTUsrCode 
                        LEFT JOIN TCNTUsrMenu USRRMENU WITH ( NOLOCK ) ON USRROLE.FTRolCode = USRRMENU.FTRolCode AND USRRMENU.FTMnuCode = 'ARC004' 
                        WHERE LN.FTUsrLogin = '$ptUserName' AND LN.FTUsrLoginPwd = '$ptPassword'  
                        AND (USRRMENU.FTAutStaFull = '1' OR USRRMENU.FTAutStaAppv = '1') ";
        $oQuery = $this->db->query($tSQL);

        if ( $oQuery->num_rows() > 0 ){
            $aResult    = array(
                'tCode'    => '1',
                'tDesc'    => 'found data',
            );
        }else{
            $aResult    = array(
                'tCode'    => '800',
                'tDesc'    => 'data not found.',
            );
        }
        unset($oQuery);
        return $aResult;
    }




}