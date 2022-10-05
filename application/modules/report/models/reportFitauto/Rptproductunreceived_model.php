<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rptproductunreceived_model extends CI_Model {

    public function FSnMExecStoreReport($paDataFilter) {
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' :  FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxPurPoUnRcv(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLangID'               => $paDataFilter['nLangID'],
            'ptUsrSession'           => $paDataFilter['tSessionID'],
            'pnFilterType'           => 2,
            'ptAgnCode'              => $paDataFilter['tAgnCodeSelect'],
            'ptBchCode'              => $tBchCodeSelect,   
            'ptShpCode'              => '',
            'ptPdtSupplierCodeFrom'  => $paDataFilter['tPdtSupplierCodeFrom'], 
            'ptPdtSupplierCodeTo'    => $paDataFilter['tPdtSupplierCodeTo'], 
            'ptPdtSgpCodeFrom'       => $paDataFilter['tPdtSgpCodeFrom'], 
            'ptPdtSgpCodeTo'         => $paDataFilter['tPdtSgpCodeTo'], 
            'ptPdtStyCodeFrom'       => $paDataFilter['tPdtStyCodeFrom'], 
            'ptPdtStyCodeTo'         => $paDataFilter['tPdtStyCodeTo'], 
            'pdAmsDateFrm'           => $paDataFilter['tDocDateFrom'],
            'pdAmsDateTo'            => $paDataFilter['tDocDateTo'],
            'pnResult'               => 0,
        );
        
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery !== FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    public function FMaMRPTPagination($paDataWhere) {
        
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   
            SELECT
                COUNT(ADJSTK_TMP.FTRptRowSeq) AS rnCountPage
            FROM TRPTPurPoUnRcvTmp ADJSTK_TMP WITH(NOLOCK)
            WHERE 1=1
            AND ADJSTK_TMP.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        $nPrevPage = $nPage - 1;
        $nNextPage = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage); //RowId Start
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
            "nNextPage"     => $nNextPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    public function FSaMGetDataReport($paDataWhere) {
        
        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];

        $tUsrSession    = $paDataWhere['tUsrSessionID'];

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   = " SELECT
                        L.*,
                        T.*,
                        D.*,
                        (F.Grp_DocNo+L.MAX_DATE) AS ALLDocINDate
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY FDXphDocDate DESC) AS RowID ,
                            ROW_NUMBER ( ) OVER ( PARTITION BY FDXphDocDate ORDER BY FDXphDocDate DESC ) AS PARTITION_DATE,
                            SUM(1) OVER ( PARTITION BY FDXphDocDate ORDER BY FDXphDocDate DESC ) AS MAX_DATE,
                            ROW_NUMBER ( ) OVER ( PARTITION BY FTBchCode ORDER BY FTBchCode DESC ) AS PARTITION_BCH,
                            SUM(1) OVER ( PARTITION BY FTBchCode ORDER BY FTBchCode DESC ) AS MAX_BCH,
                            ROW_NUMBER ( ) OVER ( PARTITION BY FTXphDocNo ORDER BY FTXphDocNo DESC ) AS PARTITION_PO,
                            SUM(1) OVER ( PARTITION BY FTXphDocNo ORDER BY FTXphDocNo DESC ) AS MAX_PO,
                            ROW_NUMBER ( ) OVER ( PARTITION BY FTXphDocNo, FTXshRefDO ORDER BY FTXphDocNo DESC ) AS PARTITION_DO,
                            SUM(1) OVER ( PARTITION BY FTXphDocNo, FTXshRefDO ORDER BY FTXphDocNo DESC ) AS MAX_DO,
                            A.*
                        FROM TRPTPurPoUnRcvTmp A WITH(NOLOCK)
                        WHERE A.FTUsrSession    = '$tUsrSession'
                        /* End Calculate Misures */
                    ) AS L

                    /* คำนวน Footer ตามเลขที่เอกสาร */
                    LEFT JOIN (
                                SELECT
                                    FTUsrSession            AS FTUsrSession_Footer,
                                    FTXphDocNo              AS DocNo_Footer,
                                    SUM(FCXpdQty)           AS Qty_DocNo_Footer,
                                    SUM(FCXpdQtyRcv)        AS QtyRcv_DocNo_Footer,
                                    SUM(FCXpdQtyLef)        AS QtyLef_DocNo_Footer
                                FROM TRPTPurPoUnRcvTmp WITH(NOLOCK)
                                WHERE FTUsrSession    = '$tUsrSession'
                                GROUP BY FTXphDocNo, FTUsrSession
                            ) T ON L.FTUsrSession = T.FTUsrSession_Footer AND L.FTXphDocNo = T.DocNo_Footer

                    /* คำนวน Footer ตามวันที่ */
                    LEFT JOIN (
                                SELECT
                                    FTUsrSession            AS FTUsrSession_Footer,
                                    FDXphDocDate            AS DocDate_Footer,
                                    SUM(FCXpdQty)           AS Qty_DocDate_Footer,
                                    SUM(FCXpdQtyRcv)        AS QtyRcv_DocDate_Footer,
                                    SUM(FCXpdQtyLef)        AS QtyLef_DocDate_Footer
                                FROM TRPTPurPoUnRcvTmp WITH(NOLOCK)
                                WHERE FTUsrSession    = '$tUsrSession'
                                GROUP BY FDXphDocDate, FTUsrSession
                    ) D ON L.FTUsrSession = D.FTUsrSession_Footer AND L.FDXphDocDate = D.DocDate_Footer

                    /* คำนวน Footer ตามวันที่ */
                    LEFT JOIN (
                                SELECT
                                FTUsrSession            AS FTUsrSession_SUM,
                                FTXphDocNo              AS DocNo_SUM,
                                FDXphDocDate            AS DocDate_SUM,
                                SUM(1) OVER ( PARTITION BY FDXphDocDate ORDER BY FDXphDocDate DESC ) AS Grp_DocNo
                                FROM TRPTPurPoUnRcvTmp WITH(NOLOCK)
                                WHERE FTUsrSession    = '$tUsrSession'
                                GROUP BY FDXphDocDate, FTXphDocNo, FTUsrSession
                    ) F ON L.FTUsrSession = F.FTUsrSession_SUM AND L.FDXphDocDate = F.DocDate_SUM AND L.FTXphDocNo = F.DocNo_SUM
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        // echo $tSQL;
        // exit;
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
             $aData = NULL;
        }

        $aErrorList = array(
            "nErrInvalidPage" => ""
        );

        $aResualt = array(
            "aPagination" => $aPagination,
            "aRptData" => $aData,
            "aError" => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Functionality: Count Data Report All
    public function FSnMCountDataReportAll($paDataWhere) {
        $tSessionID = $paDataWhere['tSessionID'];

        $tSQL = "   
            SELECT 
                COUNT(DTTMP.FTRptCode) AS rnCountPage
            FROM TRPTPurPoUnRcvTmp AS DTTMP WITH(NOLOCK)
            WHERE 1 = 1
            AND FTUsrSession    = '$tSessionID'
        ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}














