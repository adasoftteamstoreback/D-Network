<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RptWithholdingTax_model extends CI_Model {
    
    // Functionality: Call Stored Procedure SQL
    // Parameters:  Function Parameter
    // Creator: 21/04/2022 Wasin
    // LastUpdate:
    // Return: Status Stored Procedure
    // ReturnType: Boolen
    public function FSnMExecStoreReport($paDataFilter){
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $tCallStore     = "{ CALL SP_RPTxWithholdingtaxTmp(?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore     = [
            'ptRptCode'         => $paDataFilter['tRptCode'],
            'ptUsrSessionID'    => $paDataFilter['tSessionID'],
            'ptAgnCode'         => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'         => $tBchCodeSelect,
            'pdDocDateFrm'      => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'       => $paDataFilter['tDocDateTo'],
            'ptRcvCodeFrm'      => $paDataFilter['tRcvCodeFrom'],
            'ptRcvCodeTo '      => $paDataFilter['tRcvCodeTo'],
            'ptCstCodeFrm '     => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeTo '      => $paDataFilter['tCstCodeTo'],
            'ptLangID'          => $paDataFilter['nLangID'],
            'pnResult'          => 0
        ];
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        // echo $this->db->last_query();
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Report In Table Temp
    // Parameters:  Function Parameter
    // Creator: 21/04/2022 Wasin
    // LastUpdate:
    // Return: Array Data In Temp
    // ReturnType: Array
    public function FSaMGetDataReport ($paDataWhere){
        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "
                SELECT
                    FTUsrSession            AS FTUsrSession_Footer,
                    SUM(FCXshVat)           AS FCXshVat_Footer,
                    SUM(FCXshVatable)		AS FCXshVatable_Footer,
                    SUM(FCXshGrand)			AS FCXshGrand_Footer,
                    SUM(FCXrcNet)			AS FCXrcNet_Footer,
                    COUNT(FTUsrSession)     AS RowID_Footer
                FROM TRPTTaxWithholdingtaxTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSession    = ".$this->db->escape($tUsrSession)."
                GROUP BY FTUsrSession
            ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            
            ";
        }else{
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    ".$this->db->escape($tUsrSession)." AS FTUsrSession_Footer,
                    '0'                                 AS FCXshVat_Footer,
                    '0'                                 AS FCXshVatable_Footer,
                    '0'                                 AS FCXshGrand_Footer,
                    '0'                                 AS FCXrcNet_Footer,
                    '0'                                 AS RowID_Footer
                ) T ON  L.FTUsrSession  =   T.FTUsrSession_Footer
            ";
        }
        
        $tSQL   = "
            SELECT
                ROW_NUMBER ()       OVER (PARTITION BY L.FTBchCode ORDER BY L.FTBchCode ASC, L.FTCstCode ASC, L.FDXshDocDate DESC ) AS FNFmtPageRow,
                SUM (1)             OVER (PARTITION BY L.FTBchCode) AS FNFmtMaxPageRow,
                SUM (FCXshVat) 	    OVER (PARTITION BY FTBchCode)   AS FCXshVatSumBch,
                SUM (FCXshVatable)  OVER (PARTITION BY FTBchCode)   AS FCXshVatableSumBch,
                SUM (FCXshGrand) 	OVER (PARTITION BY FTBchCode)   AS FCXshGrandSumBch,
                SUM (FCXrcNet)  	OVER (PARTITION BY FTBchCode)   AS FCXrcNetSumBch,
                L.*,
                T.FCXshVat_Footer,
                T.FCXshVatable_Footer,
                T.FCXshGrand_Footer,
                T.FCXrcNet_Footer,
                T.RowID_Footer
            FROM (
                SELECT
                    ROW_NUMBER ()		OVER (ORDER BY  FTBchCode ASC,FTCstCode ASC,FTRcvName ASC,FDXshDocDate DESC) AS RowID,
                    ROW_NUMBER ()		OVER (PARTITION BY FTBchCode,FTCstCode ORDER BY FTBchCode ASC, FTCstCode ASC,FTRcvName ASC,FDXshDocDate DESC ) AS FNFmtAllRow,
                    SUM(1)				OVER (PARTITION BY FTBchCode)		AS FNFmtEndRow,
                    SUM(FCXshVat)		OVER (PARTITION BY FTBchCode,FTCstCode)		AS FCXshVatSum,
                    SUM(FCXshVatable)	OVER (PARTITION BY FTBchCode,FTCstCode)		AS FCXshVatableSum,
                    SUM(FCXshGrand)		OVER (PARTITION BY FTBchCode,FTCstCode)		AS FCXshGrandSum,
                    SUM(FCXrcNet)		OVER (PARTITION BY FTBchCode,FTCstCode)		AS FCXrcNetSum,
                    A.*
                FROM TRPTTaxWithholdingtaxTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession	= ".$this->db->escape($tUsrSession)."
            ) AS L 
            LEFT JOIN (
                " . $tJoinFoooter . "
        ";
        $tSQL   .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = null;
        }
        $aErrorList = array(
            "nErrInvalidPage" => "",
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

    // Functionality: Count Page Data All 
    // Parameters:  Function Parameter
    // Creator: 21/04/2022 Wasin
    // LastUpdate:
    // Return: Array Data In Temp
    // ReturnType: Array
    private function FMaMRPTPagination($paDataWhere){
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTBchCode) AS rnCountPage
            FROM TRPTTaxWithholdingtaxTmp RPT WITH(NOLOCK)
            WHERE RPT.FTUsrSession = '$tUsrSession'    
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










}