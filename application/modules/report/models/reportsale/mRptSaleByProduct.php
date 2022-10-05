<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSaleByProduct extends CI_Model {

    // Call Stored Procedure
    public function FSnMExecStoreReport($paDataFilter) {
        $nLangID      = $paDataFilter['nLangID'];
        $tComName     = $paDataFilter['tCompName'];
        $tRptCode     = $paDataFilter['tRptCode'];
        $tUserSession = $paDataFilter['tUserSession'];

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']); 
        // ร้านค้า
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        // กลุ่มธุรกิจ
        $tMerCodeSelect = ($paDataFilter['bMerStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tMerCodeSelect']);
        // ประเภทเครื่องจุดขาย
        $tPosCodeSelect = ($paDataFilter['bPosStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tPosCodeSelect']);
 
        $tCallStore = "{CALL SP_RPTxDailySaleByPdt1001002(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
            'pnLngID'       => $nLangID,
            'pnComName'     => $tComName,
            'ptRptCode'     => $tRptCode,
            'ptUsrSession'  => $tUserSession,
            'pnFilterType'  => $paDataFilter['tTypeSelect'],
            'ptBchL'        => $tBchCodeSelect,
            'ptBchF'        => $paDataFilter['tBchCodeFrom'],
            'ptBchT'        => $paDataFilter['tBchCodeTo'],
            'ptMerL'        => $tMerCodeSelect,
            'ptMerF'        => $paDataFilter['tMerCodeFrom'],
            'ptMerT'        => $paDataFilter['tMerCodeTo'],
            'ptShpL'        => $tShpCodeSelect,
            'ptShpF'        => $paDataFilter['tShpCodeFrom'],
            'ptShpT'        => $paDataFilter['tShpCodeTo'],
            'ptPosL'        => $tPosCodeSelect,
            'ptPosF'        => $paDataFilter['tPosCodeFrom'],
            'ptPosT'        => $paDataFilter['tPosCodeTo'],
            'ptPdtCodeF'    => $paDataFilter['tPdtCodeFrom'],
            'ptPdtCodeT'    => $paDataFilter['tPdtCodeTo'],
            'ptPdtChanF'    => $paDataFilter['tPdtGrpCodeFrom'],
            'ptPdtChanT'    => $paDataFilter['tPdtGrpCodeTo'],
            'ptPdtTypeF'    => $paDataFilter['tPdtTypeCodeFrom'],
            'ptPdtTypeT'    => $paDataFilter['tPdtTypeCodeTo'],
            'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'    => $paDataFilter['tDocDateTo'],
            'ptCate1From'   => FCNtAddSingleQuote($paDataFilter['tCate1From']),
            'ptCate2From'   => FCNtAddSingleQuote($paDataFilter['tCate2From']),
            'FNResult'      => 0
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

    // Get Data Page
    public function FMaMRPTPagination($paDataWhere) {
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tAppType       = $paDataWhere['aDataFilter']['tPosType'];
        $tSQL = "   SELECT
                        COUNT(DTTMP.FTRptCode) AS rnCountPage
                    FROM TRPTSalDTTmp AS DTTMP WITH(NOLOCK)
                    WHERE 1=1
                    AND DTTMP.FTComName    = '$tComName'
                    AND DTTMP.FTRptCode    = '$tRptCode'
                    AND DTTMP.FTUsrSession = '$tUsrSession'";
        if($tAppType != ""){
            $tSQL .= " AND DTTMP.FNAppType = '$tAppType'";
        }
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        $nPrevPage = $nPage - 1;
        $nNextPage = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage);
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
            "nTotalRecord" => $nRptAllRecord,
            "nTotalPage" => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart" => $nRowIDStart,
            "nRowIDEnd" => $nRowIDEnd,
            "nPrevPage" => $nPrevPage,
            "nNextPage" => $nNextPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    // Priority Group
    public function FMxMRPTSetPriorityGroup($paDataWhere) {
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tAppType       = $paDataWhere['aDataFilter']['tPosType'];

        $tSQL = " 
            UPDATE DATAUPD SET 
                DATAUPD.FNRowPartID = B.PartID
            FROM TRPTSalDTTmp AS DATAUPD WITH(NOLOCK)
            INNER JOIN(
                SELECT
                    ROW_NUMBER() OVER(PARTITION BY FTBchCode ORDER BY FTBchCode DESC , FTPdtCode ASC) AS PartID,
                    FTRptRowSeq
                FROM TRPTSalDTTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName     = '$tComName'
                AND TMP.FTRptCode       = '$tRptCode'
                AND TMP.FTUsrSession    = '$tUsrSession'
                AND TMP.FCXsdQty > 0 
                ";
        if($tAppType != ""){
            $tSQL .= " AND TMP.FNAppType = '$tAppType'";
        }

        $tSQL .= "
            ) AS B
            ON DATAUPD.FTRptRowSeq = B.FTRptRowSeq
            AND DATAUPD.FTComName       = '$tComName'
            AND DATAUPD.FTRptCode       = '$tRptCode'
            AND DATAUPD.FTUsrSession    = '$tUsrSession'";
        if($tAppType != ""){
            $tSQL .= " AND DATAUPD.FNAppType = '$tAppType'";
        }
        // print_r($tSQL);
        // exit;
        $this->db->query($tSQL);
    }

    // Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere) {
        $nPage          = $paDataWhere['nPage'];

        if( $paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $aPagination    = '';
            $nTotalPage     = 1;
        }

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tAppType       = $paDataWhere['aDataFilter']['tPosType'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   SELECT
                                        FTUsrSession            AS FTUsrSession_Footer,
                                        SUM(FCXsdQty)           AS FCXsdQty_Footer,
                                        SUM(FCXsdAmtB4DisChg)   AS FCXsdAmtB4DisChg_Footer,
                                        SUM(FCXsdDis)           AS FCXsdDis_Footer,
                                        SUM(FCXsdSetPrice)      AS FCXsdSetPrice_Footer,
                                        SUM(FCXsdNetAfHD)       AS FCXsdNetAfHD_Footer
                                    FROM TRPTSalDTTmp WITH(NOLOCK)
                                    WHERE 1=1
                                    AND FTComName       = '$tComName'
                                    AND FTRptCode       = '$tRptCode'
                                    AND FTUsrSession    = '$tUsrSession'
                                    AND FCXsdQty > 0 ";
            if($tAppType != ""){
                $tJoinFoooter .= "  AND FNAppType       = '$tAppType'";
            }
            $tJoinFoooter .= " 
                                    GROUP BY FTUsrSession
                                    ) T ON L.FTUsrSession = T.FTUsrSession_Footer";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   SELECT
                                        '$tUsrSession'  AS FTUsrSession_Footer,
                                        0   AS FCXsdQty_Footer,
                                        0   AS FCXsdAmtB4DisChg_Footer,
                                        0   AS FCXsdDis_Footer,
                                        0   AS FCXsdSetPrice_Footer,
                                        0   AS FCXsdNetAfHD_Footer
                                    ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        $tSQL = "   SELECT
                            L.*,
                            T.*
                        FROM (
                            SELECT
                                ROW_NUMBER() OVER(ORDER BY FTBchCode ASC) AS RowID ,
                                A.*,
                                S.FNRptGroupMember,
                                S.FCXsdQty_SubTotal,
                                S.FCXsdAmtB4DisChg_SubTotal,
                                S.FCXsdDis_SubTotal,
                                S.FCXsdSetPrice_SubTotal,
                                S.FCXsdNetAfHD_SubTotal
                            FROM TRPTSalDTTmp A WITH(NOLOCK)
                            LEFT JOIN (
                                SELECT
                                    FTBchCode               AS FTBchCode_SUM,
                                    COUNT(FTBchCode)        AS FNRptGroupMember,
                                    SUM(FCXsdQty)           AS FCXsdQty_SubTotal,
                                    SUM(FCXsdAmtB4DisChg)   AS FCXsdAmtB4DisChg_SubTotal,
                                    SUM(FCXsdDis)           AS FCXsdDis_SubTotal,
                                    SUM(FCXsdSetPrice)      AS FCXsdSetPrice_SubTotal,
                                    SUM(FCXsdNetAfHD)       AS FCXsdNetAfHD_SubTotal
                                FROM TRPTSalDTTmp WITH(NOLOCK)
                                WHERE 1=1
                                AND FTComName       = '$tComName'
                                AND FTRptCode       = '$tRptCode'
                                AND FTUsrSession    = '$tUsrSession'
                                AND FCXsdQty > 0 ";

        if($tAppType != ""){
            $tSQL .= "          AND FNAppType       = '$tAppType'";
        }
        
        $tSQL .= "
                                GROUP BY FTBchCode
                            ) AS S ON A.FTBchCode = S.FTBchCode_SUM
                            WHERE 1=1
                            AND A.FTComName     = '$tComName'
                            AND A.FTRptCode     = '$tRptCode'
                            AND A.FTUsrSession  = '$tUsrSession' 
                            AND A.FCXsdQty > 0 ";
        if($tAppType != ""){
            $tSQL .= "      AND A.FNAppType       = '$tAppType'";
        }

        $tSQL .= "      ) AS L
                        LEFT JOIN (
                            " . $tJoinFoooter . " ";

        if( $paDataWhere['nPerPage'] != 0 ){
            $tSQL .= "   WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        }

        if($tAppType != ""){
            $tSQL .= " AND L.FNAppType = '$tAppType'";
        }

        $tSQL .= "   ORDER BY L.FTBchCode ASC , L.FTPdtCode ASC , FNRowPartID ASC";

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
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Count Data Report All
    public function FSnMCountRowInTemp($paDataWhere) {
        $tUserSession   = $paDataWhere['tUserSession'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tPosType       = $paDataWhere['aDataFilter']['tPosType'];
        $tSQL = "   SELECT 
                             COUNT(DTTMP.FTRptCode) AS rnCountPage
                         FROM TRPTSalDTTmp AS DTTMP WITH(NOLOCK)
                         WHERE 1 = 1
                         AND FTUsrSession    = '$tUserSession'
                         AND FTComName       = '$tCompName'
                         AND FTRptCode       = '$tRptCode'";
        if($tPosType != ''){
            $tSQL .= "   AND FNAppType       = '$tPosType'";
        }

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}


