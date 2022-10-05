<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptcreditordis_model extends CI_Model
{

    // Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {
        $tCallStore = "{ CALL SP_RPTxCreditorDis(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        $aDataStore = array(
            // Systemp Parameter Report
            'pnLngID'       => $paDataFilter['nLangID'],
            'ptComName'     => $paDataFilter['tCompName'],
            'ptRptCode'     => $paDataFilter['tRptCode'],
            'ptUsrSession'  => $paDataFilter['tUserSession'],
            'pnFilterType'      => $paDataFilter['tTypeSelect'],
            'ptAgnCode'     =>  $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'     => $tBchCodeSelect,
            'ptShpCode'     => '',
            'ptStaPaid'      => $paDataFilter['tPdtRptPhStaPaid'],
            'ptSplF'      => $paDataFilter['tPdtSupplierCodeFrom'],
            'ptSplT'      => $paDataFilter['tPdtSupplierCodeTo'],
            'ptSgpF'      => $paDataFilter['tPdtSgpCodeFrom'],
            'ptSgpT'      => $paDataFilter['tPdtSgpCodeTo'],
            'ptStyF'      => $paDataFilter['tPdtStyCodeFrom'],
            'ptStyT'      => $paDataFilter['tPdtStyCodeTo'],
            'ptUsrF'      => $paDataFilter['tCashierCodeFrom'],
            'ptUsrT'      => $paDataFilter['tCashierCodeTo'],
            'pdDocDateFrm'  => $paDataFilter['tDocDateFrom'],
            'pdDocDateTo'   => $paDataFilter['tDocDateTo'],
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
    public function FSaMGetDataReport($paDataWhere)
    {
        $nPage          = $paDataWhere['nPage'];

        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $nTotalPage = 1;
            $aPagination = 0;
        }
        
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // Set Priority
        // $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter   = "
                SELECT
                    FTUsrSession        AS FTUsrSession_Footer,
                    SUM(FCXphGrand)       AS FCXphGrand_Footer,
                    SUM(FCXphPaid)     AS FCXphPaid_Footer,
                    SUM(FCXphRemain)  AS FCXphRemain_Footer,
                    SUM(FCXphVat)  AS FCXphVat_Footer
                FROM TRPTCreditorDisTmp WITH(NOLOCK)
                WHERE FTUsrSession <> '' 
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            $tJoinFoooter   = "
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXphGrand_Footer,
                    0    AS FCXphPaid_Footer,
                   0 AS FCXphRemain_Footer,
                   0 AS FCXphVat_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "SELECT L.*,
                            T.FCXphGrand_Footer,
                            T.FCXphPaid_Footer,
                            T.FCXphRemain_Footer,
                            T.FCXphVat_Footer
                        FROM ( ";

        $tSQL   .= "
                SELECT
                ROW_NUMBER() OVER(
                    ORDER BY FTSplCode ASC,FDXphDocDate ASC) AS RowID, 
                               ROW_NUMBER() OVER(PARTITION BY FTSplCode
                    ORDER BY FTSplCode ASC) AS FNFmtAllRow, 
                    SUM(1) OVER(PARTITION BY FTSplCode) AS FNFmtEndRow,
                    A.*, 
                    S.FNRptGroupMember,
                    S.FCXphGrand_SubTotal,
                    S.FCXphPaid_SubTotal,
                    S.FCXphRemain_SubTotal,
                    S.FCXphVat_SubTotal
                FROM TRPTCreditorDisTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTSplCode           AS FTSplCode_SUM,
                        COUNT(FTSplCode)    AS FNRptGroupMember,
                        SUM(FCXphGrand)       AS FCXphGrand_SubTotal,
                        SUM(FCXphPaid)     AS FCXphPaid_SubTotal,
                        SUM(FCXphRemain)  AS FCXphRemain_SubTotal,
                        SUM(FCXphVat)  AS FCXphVat_SubTotal
                    FROM TRPTCreditorDisTmp WITH ( NOLOCK )
                    WHERE FTUsrSession <> ''
                    AND FTUsrSession    = '$tUsrSession'
                    GROUP BY FTSplCode
                ) AS S ON A.FTSplCode = S.FTSplCode_SUM 
                WHERE FTUsrSession <> ''
                AND A.FTUsrSession  = '$tUsrSession'
        ";
        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "  ) AS L ";
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
            $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }else{
            $tSQL .= "  ) AS L ";
            $tSQL .= "  LEFT JOIN (" . $tJoinFoooter . "";
        }

        // print_r($tSQL);
        // die();

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
    private function FMaMRPTPagination($paDataWhere)
    {
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "
            SELECT
                COUNT(RPT.FTUsrSession) AS rnCountPage
            FROM TRPTCreditorDisTmp RPT WITH(NOLOCK)
            WHERE 
             RPT.FTUsrSession    = '$tUsrSession'
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

    // // Set Priority Group
    // private function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession)
    // {
    //     $tSQL   = "
    //         UPDATE TRPTPurCrOverDueTmp
    //         SET FNRowPartID = B.PartID
    //         FROM(
    //             SELECT
    //                 ROW_NUMBER() OVER(PARTITION BY FTSplCode ORDER BY FTSplCode ASC , FDXphDocDate ASC) AS PartID, 
    //                 FTRptRowSeq
    //             FROM TRPTPurCrOverDueTmp TMP WITH(NOLOCK)
    //             WHERE 
    //              TMP.FTUsrSession 	= '$ptUsrSession'
    //         ) B
    //         WHERE TRPTPurCrOverDueTmp.FTRptRowSeq   = B.FTRptRowSeq 
    //         AND TRPTPurCrOverDueTmp.FTUsrSession    = '$ptUsrSession'
    //     ";
    //     $this->db->query($tSQL);
    // }
}
