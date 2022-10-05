<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mCheckProductPrice extends CI_Model{

    public function FSaMCPPGetPriList($paData){
        $nLngID = $paData['FNLngID'];
        $tSQL   ="
            SELECT 
                P.FTPplCode, 
                PL.FTPplName
            FROM TCNMPdtPriList P
            LEFT JOIN TCNMPdtPriList_L PL ON P.FTPplCode = PL.FTPplCode
            WHERE PL.FNLngID    = ".$this->db->escape($nLngID)."
            ORDER BY P.FDCreateOn DESC
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList          = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        unset($paData);
        return $aResult;
    }

    // Functionality    : เรียกข้อมูลตรวจสอบราคาสินค้า
    // Parameters       : Function Parameter
    // Creator          : 20/08/2020 Sooksanti(Non)
    // Last Modified    :
    // Return           : array
    // Return Type      : array
    public function FSaMCPPGetListData($paData){
        $nLngID         = $paData['FNLngID'];
        $oAdvanceSearch = $paData['oAdvanceSearch'];
        $tWhere         = "";

        if( $paData['tDisplayType'] == '1' ){
            $tOrderBy1  = " B.FTPdtCode ASC, B.FTPunCode ASC, B.FDXphDStart DESC,B.FTXphDocNo  DESC";
        }else{
            $tOrderBy1  = " B.FTPplCode ASC, B.FTPdtCode ASC, B.FTPunCode ASC, B.FDXphDStart DESC ,B.FTXphDocNo  DESC";
        }
        
        @$tSearchList = $oAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tWhere .= " AND ((ADJP_DT.FTPdtCode LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (PDTL.FTPdtName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (PUNL.FTPunName LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (ADJP_HD.FTXphDocNo LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR (CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) LIKE '%".$this->db->escape_like_str($tSearchList)."%') OR '%".$this->db->escape_like_str($tSearchList)."%' BETWEEN CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) AND CONVERT(VARCHAR(10),ADJP_HD.FDXphDStop,121))";
        }

        // จากสินค้า - ถึงสินค้า
        $tPdtCodeFrom = $oAdvanceSearch['tPdtCodeFrom'];
        $tPdtCodeTo   = $oAdvanceSearch['tPdtCodeTo'];
        if(!empty($tPdtCodeFrom) && !empty($tPdtCodeTo)){
            $tWhere .= " AND ((ADJP_DT.FTPdtCode BETWEEN ".$this->db->escape($tPdtCodeFrom)." AND ".$this->db->escape($tPdtCodeTo).") OR (ADJP_DT.FTPdtCode BETWEEN ".$this->db->escape($tPdtCodeTo)." AND ".$this->db->escape($tPdtCodeFrom)."))";
        }

        // จากวันที่เอกสาร - ถึงวันที่เอกสาร
        $tSearchDocDateFrom = $oAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $oAdvanceSearch['tSearchDocDateTo'];
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tWhere .= " AND ((CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) BETWEEN ".$this->db->escape($tSearchDocDateFrom)." AND ".$this->db->escape($tSearchDocDateTo).") OR (CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) BETWEEN ".$this->db->escape($tSearchDocDateTo)." AND ".$this->db->escape($tSearchDocDateFrom)."))";
        }

        // หน่วยสินค้า
        $tPunCodeFrom   = $oAdvanceSearch['tPunCodeFrom'];
        $tPunCodeTo     = $oAdvanceSearch['tPunCodeTo'];
        if(!empty($tPunCodeFrom) && !empty($tPunCodeTo)){
            $tWhere .= " AND ((ADJP_DT.FTPunCode BETWEEN ".$this->db->escape($tPunCodeFrom)." AND ".$this->db->escape($tPunCodeTo).") OR (ADJP_DT.FTPunCode BETWEEN ".$this->db->escape($tPunCodeTo)." AND ".$this->db->escape($tPunCodeFrom)."))";
        }

        // // กลุ่มราคาที่มีผล
        $tPplCodeFrom   = $oAdvanceSearch['tPplCodeFrom'];
        $tPplCodeTo     = $oAdvanceSearch['tPplCodeTo'];
        if(!empty($tPplCodeFrom) && !empty($tPplCodeTo)){
            if( $tPplCodeFrom == 'NA' && $tPplCodeTo != 'NA' ){
                $tWhere .= " AND (ADJP_HD.FTPplCode     = '' OR ADJP_HD.FTPplCode IS NULL) ";
                $tWhere     .= " AND ADJP_HD.FTPplCode  = ".$this->db->escape($tPplCodeTo)." ";
            }

            if( $tPplCodeFrom != 'NA' && $tPplCodeTo == 'NA' ){
                $tWhere .= " AND (ADJP_HD.FTPplCode = '' OR ADJP_HD.FTPplCode IS NULL) ";
                $tWhere .= " AND ADJP_HD.FTPplCode  = ".$this->db->escape($tPplCodeFrom)." ";
            }

            if( $tPplCodeFrom != 'NA' && $tPplCodeTo != 'NA' ){
                $tWhere .= " AND ((ADJP_HD.FTPplCode BETWEEN ".$this->db->escape($tPplCodeFrom)." AND ".$this->db->escape($tPplCodeTo).") OR (ADJP_HD.FTPplCode BETWEEN ".$this->db->escape($tPplCodeTo)." AND ".$this->db->escape($tPplCodeFrom).")) ";
            }

            if( $tPplCodeFrom == 'NA' && $tPplCodeTo == 'NA' ){
                $tWhere .= " AND (ADJP_HD.FTPplCode = '' OR ADJP_HD.FTPplCode IS NULL) ";
            }
        }

        // if( $this->session->userdata("tSesUsrLevel") != "HQ" ){
        //     $tBchCodeMulti = $this->session->userdata("tSesUsrBchCodeMulti");
        //     $tWhere .= " AND ADJP_HD.FTBchCode IN ($tBchCodeMulti) ";

        //     //ถ้ามี Mer ต้อง Where Mer เพิ่ม
        //     if($this->session->userdata("tSesUsrMerCode") != ''){
        //         $tMerCode = $this->session->userdata("tSesUsrMerCode");
        //         $tWhere .= " AND PDTSPC.FTMerCode IN ($tMerCode) ";
        //     }
        // }

        $tSQLMain   = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
            (
                SELECT
                    A.FCxpdpriceret
                FROM
                    (
                    SELECT
                        ROW_NUMBER ( ) OVER ( PARTITION BY SDT.FTPdtCode ORDER BY SHD.FTPplCode DESC, CONCAT ( CONVERT ( VARCHAR ( 10 ), FDXphDocDate, 121 ), ' ', FTXphDocTime ) DESC) AS PARTTITIONBYDOC_COUNT,
                        SHD.FTXphDocNo,
                        SHD.FTXphStaAdj,
                        SHD.FTPplCode,
                        SDT.FTPdtCode,
                        SDT.FCxpdpriceret 
                    FROM
                        TCNTPdtAdjPriHD SHD
                        LEFT JOIN TCNTPdtAdjPriDT SDT ON SDT.FTXphDocNo = SHD.FTXphDocNo 
                        AND SDT.FTBchCode = SHD.FTBchCode 
                    WHERE
                        FTXphDocType = '1' 
                        AND CONCAT ( CONVERT ( VARCHAR ( 10 ), FDXphDocDate, 121 ), ' ', FTXphDocTime ) <= CONVERT ( VARCHAR ( 16 ), GETDATE( ), 121 ) 
                        AND SDT.FTPdtCode = ADJP_DT.FTPdtCode 
                        AND SDT.FTPunCode = ADJP_DT.FTPunCode
                        AND (SHD.FTPplCode = ADJP_HD.FTPplCode OR ISNULL(FTPPlCode,'') = '')
                    ) AS A 
                WHERE
                    A.PARTTITIONBYDOC_COUNT = 1 
                ) AS Price2,	
                ADJP_DT.FTPdtCode,
                ADJP_HD.FTXphStaAdj,
                PDTL.FTPdtName,
                ADJP_DT.FTPunCode,
                PUNL.FTPunName,
                CONVERT(VARCHAR(10),ADJP_HD.FDXphDStart,121) AS FDXphDStart,
                CASE WHEN ADJP_HD.FTXphDocType = '2' THEN CONVERT(VARCHAR(10),ADJP_HD.FDXphDStop,121) ELSE '-' END AS FDXphDStop,
                ADJP_HD.FTXphTStart,
                ADJP_HD.FTXphTStop,
                ADJP_DT.FCXpdPriceRet,
                ADJP_HD.FTPplCode,
                PL.FTPplName,
                ADJP_HD.FTXphDocNo,
                ADJP_HD.FTXphDocType,
                CONVERT(VARCHAR(10),ADJP_HD.FDXphDocDate,121) AS FDXphDocDate
        ";


        $tSQL1 = "  SELECT B.*,
        CASE
                WHEN B.FTXphStaAdj = '1' THEN
                B.FCXpdPriceRet 
                WHEN B.FTXphStaAdj = '2' THEN
                (B.Price2 - ((B.Price2/100*FCXpdPriceRet)))
                WHEN B.FTXphStaAdj = '3' THEN
                (B.Price2 - FCXpdPriceRet)
                WHEN B.FTXphStaAdj = '4' THEN
                (B.Price2 + ((B.Price2/100*FCXpdPriceRet)))
                WHEN B.FTXphStaAdj = '5' THEN
                (B.Price2 + FCXpdPriceRet)
                ELSE 0 
            END AS SumPrice FROM ( ";  
        $tSQL2 = "  FROM TCNTPdtAdjPriHD ADJP_HD WITH (NOLOCK)
                    INNER JOIN TCNTPdtAdjPriDT ADJP_DT WITH (NOLOCK) ON ADJP_DT.FTXphDocNo = ADJP_HD.FTXphDocNo AND ADJP_DT.FTBchCode = ADJP_HD.FTBchCode
                    LEFT JOIN TCNMPdtUnit_L PUNL WITH (NOLOCK)   ON ADJP_DT.FTPunCode = PUNL.FTPunCode AND PUNL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK) ON ADJP_DT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMPdtPriList_L PL WITH (NOLOCK) ON ADJP_HD.FTPplCode = PL.FTPplCode AND PL.FNLngID = ".$this->db->escape($nLngID)."
                    LEFT JOIN TCNMPdtSpcBch PDTSPC WITH (NOLOCK) ON PDTSPC.FTPdtCode = PDTL.FTPdtCode 
                    WHERE ADJP_HD.FDCreateOn <> ''
                    AND ADJP_DT.FTPdtCode != ''
                    AND ADJP_HD.FTXphStaApv = 1
                    $tWhere
                ";
        $tSQL3 = " ) B  
            ORDER BY ".$tOrderBy1."
        ";
        // Data
        $tFinalDataQuery    = $tSQL1.$tSQLMain.$tSQL2.$tSQL3;
        $oQueryData         = $this->db->query($tFinalDataQuery);
        if ($oQueryData->num_rows() > 0) {
            $aList      = $oQueryData->result_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        } else {
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($nLngID,$oAdvanceSearch,$tWhere,$tSQLMain,$tSQL1,$tSQL2,$tSQL3,$tFinalDataQuery,$oQueryData);
        unset($aList);
        return $aResult;
    }
}