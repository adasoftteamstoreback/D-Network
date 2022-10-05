<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptpobybchbypdt_model extends CI_Model {

    // Call Stored
    public function FSnMExecStoreReport($paDataFilter){
        $tCallStore = "{ CALL SP_RPTxPoByBchByPdt(?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            // Systemp Parameter Report
            'pnLngID'       => $paDataFilter['nLangID'],
            'ptComName'     => $paDataFilter['tCompName'],
            'ptRptCode'     => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'     => ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']),
            'pdDocDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'   => $paDataFilter['tDocDateTo'],
            'ptPdtCodeFrm'  => $paDataFilter['tRptPdtCodeFrom'],
            'ptPdtCodeTo'   => $paDataFilter['tRptPdtCodeTo'],
            'pnResult'      => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere){
        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if($nPage == $nTotalPage) {
            $tJoinFoooter   = "
                SELECT
                    FTUsrSession        AS FTUsrSession_Footer,
                    SUM(FCXpdQty)       AS FCXpdQty_Footer
                FROM TRPTPoByBchByPdtTmp WITH(NOLOCK)
                WHERE FTUsrSession <> '' 
                AND FTComName       = '$tComName'
		        AND FTRptCode       = '$tRptCode'
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }else{
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXpdQty_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = "
            SELECT
                L.*,
                T.FCXpdQty_Footer
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode,FTPdtCode) AS RowID ,
                    A.*,
                    S.FNRptGroupMember,
                    S.FCXpdQty_SubTotal
                FROM TRPTPoByBchByPdtTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTBchCode           AS FTBchCode_SUM,
                        FTPdtCode           AS FTPdtCode_SUM,
                        COUNT(FTPdtCode)    AS FNRptGroupMember,
                        SUM(FCXpdQty)       AS FCXpdQty_SubTotal
                    FROM TRPTPoByBchByPdtTmp WITH ( NOLOCK )
                    WHERE FTUsrSession <> ''
                    AND FTComName       = '$tComName'
                    AND FTRptCode       = '$tRptCode'
                    AND FTUsrSession    = '$tUsrSession'
                    GROUP BY FTBchCode,FTPdtCode
                ) AS S ON A.FTPdtCode = S.FTPdtCode_SUM AND A.FTBchCode = S.FTBchCode_SUM
                WHERE FTUsrSession <> ''
                AND A.FTComName     = '$tComName'
                AND A.FTRptCode     = '$tRptCode'
                AND A.FTUsrSession  = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
                " . $tJoinFoooter . "
        ";
        // WHERE เงื่อนไข Page
        $tSQL   .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL   .= " ORDER BY L.FTBchCode ASC , L.FTPdtCode ASC , L.FNRowPartID ASC";

        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = NULL;
        }
        $aErrorList = array(
            "nErrInvalidPage"   => ""
        );
        $aResualt = array(
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Count จำนวน
    private function FMaMRPTPagination($paDataWhere){
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTUsrSession) AS rnCountPage
            FROM TRPTPoByBchByPdtTmp RPT WITH(NOLOCK)
            WHERE RPT.FTComName     = '$tComName'
            AND RPT.FTRptCode       = '$tRptCode'
            AND RPT.FTUsrSession    = '$tUsrSession'
        ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage - 1;
        $nNextPage      = $nPage + 1;
        $nRowIDStart    = (($nPerPage * $nPage) - $nPerPage); 
        if ($nRptAllRecord <= $nPerPage) {
            $nTotalPage = 1;
        } else if (($nRptAllRecord % $nPerPage) == 0) {
            $nTotalPage = ($nRptAllRecord / $nPerPage);
        } else {
            $nTotalPage = ($nRptAllRecord / $nPerPage) + 1;
            $nTotalPage = (int) $nTotalPage;
        }
        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if ($nRowIDEnd > $nRptAllRecord) {
            $nRowIDEnd = $nRptAllRecord;
        }
        $aRptMemberDet = array(
            "nTotalRecord"  => $nRptAllRecord,
            "nTotalPage"    => $nTotalPage,
            "nDisplayPage"  => $paDataWhere['nPage'],
            "nRowIDStart"   => $nRowIDStart,
            "nRowIDEnd"     => $nRowIDEnd,
            "nPrevPage"     => $nPrevPage,
            "nNextPage"     => $nNextPage,
            "nPerPage"      => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    // Set Priority Group
    private function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {
        $tSQL   = "
            UPDATE TRPTPoByBchByPdtTmp
            SET FNRowPartID = B.PartID
            FROM(
                SELECT
                    ROW_NUMBER() OVER(PARTITION BY FTBchCode , FTPdtCode ORDER BY FTBchCode ASC , FTPdtCode ASC , FDXphDocDate ASC) AS PartID, 
                    FTRptRowSeq
                FROM TRPTPoByBchByPdtTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName     = '$ptComName' 
                AND TMP.FTRptCode 		= '$ptRptCode'
                AND TMP.FTUsrSession 	= '$ptUsrSession'
            ) B
            WHERE TRPTPoByBchByPdtTmp.FTRptRowSeq   = B.FTRptRowSeq 
            AND TRPTPoByBchByPdtTmp.FTComName       = '$ptComName' 
            AND TRPTPoByBchByPdtTmp.FTRptCode       = '$ptRptCode'
            AND TRPTPoByBchByPdtTmp.FTUsrSession    = '$ptUsrSession'
        ";
        $this->db->query($tSQL);
    }


}