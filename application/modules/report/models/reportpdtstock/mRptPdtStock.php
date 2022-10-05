<?php defined('BASEPATH') or exit('No direct script access allowed');

class mRptPdtStock extends CI_Model{

    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 16/08/2019 Saharat(Golf)
    //Last Modified :
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreReport($paDataFilter){
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);
        // ร้านค้า
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        // กลุ่มธุรกิจ
        $tMerCodeSelect = ($paDataFilter['bMerStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tMerCodeSelect']);
        $tCallStore     = "{ CALL SP_RPTxStockCard(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore     = array(
            'pnLngID'           => $paDataFilter['nLangID'],
            'pnComName'         => $paDataFilter['tCompName'],
            'ptRptCode'         => $paDataFilter['tRptCode'],
            'ptUsrSession'      => $paDataFilter['tUserSession'],
            'pnFilterType'      => $paDataFilter['tTypeSelect'],
            'ptBchL'            => $tBchCodeSelect,
            'ptMerL'            => $tMerCodeSelect,
            'ptShpL'            => $tShpCodeSelect,
            'ptPdtF'            => $paDataFilter['tPdtCodeFrom'],
            'ptPdtT'            => $paDataFilter['tPdtCodeTo'],
            'ptWahF'            => $paDataFilter['tWahCodeFrom'],
            'ptWahT'            => $paDataFilter['tWahCodeTo'],
            'ptMonth'           => ltrim($paDataFilter['tMonth'],0),
            'ptYear'            => $paDataFilter['tYear'],
            'ptPdtStaActive'    => $paDataFilter['tPdtStaActive'],
            'FNResult'          => 0,
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery !== FALSE) {
            $nStaReturn = 1;
        } else {
            $nStaReturn = 0;
        }
        unset($tBchCodeSelect,$tShpCodeSelect,$tMerCodeSelect,$tCallStore,$aDataStore,$oQuery);
        return $nStaReturn;
    }

    // Functionality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 10/07/2019 Saharat(Golf)
    // Last Modified : 19/11/2019 wasin(Yoshi)
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere){
        $nPage          = $paDataWhere['nPage'];
        // Call Data Pagination 
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tSession       = $paDataWhere['tUsrSessionID'];
        //Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tSession);
        $this->FMxMRPTAjdStkBal($tComName, $tRptCode, $tSession);
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter   =   "
                    SELECT
                        FTUsrSession        AS FTUsrSession_Footer,
                        SUM(FCStkQtyMonEnd) AS FCStkQtyMonEnd_Footer,
                        SUM(FCStkQtyIn)     AS FCStkQtyIn_Footer,
                        SUM(FCStkQtyOut)    AS FCStkQtyOut_Footer,
                        SUM(FCStkQtySaleDN - FCStkQtyCN) AS FCStkQtySale_Footer,
                        SUM(FCStkQtyAdj)    AS FCStkQtyAdj_Footer,
                        SUM(FCStkQtyBal)    AS FCStkQtyBal_Footer
                    FROM TRPTPdtStkCrdSumTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName       = '$tComName'
                    AND FTRptCode       = '$tRptCode'
                    AND FTUsrSession    = '$tSession'
                    GROUP BY FTUsrSession
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter   =   "
                    SELECT
                        '$tSession' AS FTUsrSession_Footer,
                        '0'           AS FCStkQtyMonEnd_Footer,
                        '0'           AS FCStkQtyIn_Footer,
                        '0'           AS FCStkQtyOut_Footer,
                        '0'           AS FCStkQtySale_Footer,
                        '0'           AS FCStkQtyAdj_Footer,
                        '0'           AS FCStkQtyBal_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }
        $tSQL   = "
            SELECT L.*,T.*
            FROM (
                SELECT DISTINCT
                    ROW_NUMBER() OVER(ORDER BY DATASTKCRD.FTPdtCode ASC, DATASTKCRD.FTRptRowSeq ASC) AS RowID,
                    DATASTKCRD.*,
                    DATASUBPDT.*
                FROM TRPTPdtStkCrdSumTmp DATASTKCRD WITH(NOLOCK)
                LEFT JOIN (
                    SELECT DISTINCT
                        FTPdtCode           AS FTPdtCode_SUBPDT,
                        COUNT(FTPdtCode)    AS FNRptGroupMember_SUBPDT,
                        SUM(FCStkQtyMonEnd) AS FCStkQtyMonEnd_SUBPDT,
                        SUM(FCStkQtyIn)     AS FCStkQtyIn_SUBPDT,
                        SUM(FCStkQtyOut)    AS FCStkQtyOut_SUBPDT,
                        SUM(FCStkQtySaleDN - FCStkQtyCN) AS FCStkQtySale_SUBPDT,
                        SUM(FCStkQtyAdj)    AS FCStkQtyAdj_SUBPDT,
                        SUM(FCStkQtyBal)    AS FCStkQtyBal_SUBPDT
                    FROM TRPTPdtStkCrdSumTmp WITH(NOLOCK)
                    WHERE FTUsrSession <> ''
                    AND FTComName       = ".$this->db->escape($tComName)."
                    AND FTRptCode       = ".$this->db->escape($tRptCode)."
                    AND FTUsrSession    = ".$this->db->escape($tSession)."
                    GROUP BY FTPdtCode
                ) DATASUBPDT ON DATASTKCRD.FTPdtCode = DATASUBPDT.FTPdtCode_SUBPDT
                WHERE FTUsrSession <> ''
                AND DATASTKCRD.FTComName    = ".$this->db->escape($tComName)."
                AND DATASTKCRD.FTRptCode    = ".$this->db->escape($tRptCode)."
                AND DATASTKCRD.FTUsrSession = ".$this->db->escape($tSession)."
            ) L
            LEFT JOIN (
                " . $tJoinFoooter . "
        ";
        // WHERE เงื่อนไข Page
        $tSQL   .=  "   WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL   .=  "   ORDER BY L.FTPdtCode,L.FTRptRowSeq,L.FNRowPartID";
        $oQuery  = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData  = $oQuery->result_array();
        } else {
            $aData  = NULL;
        }
        $aErrorList =   array(
            "nErrInvalidPage"   =>  ""
        );
        $aResualt = array(
            "aPagination"   =>  $aPagination,
            "aRptData"      =>  $aData,
            "aError"        =>  $aErrorList
        );
        unset($nPage,$aPagination,$nRowIDStart,$nRowIDEnd,$nTotalPage,$tComName,$tRptCode,$tSession);
        unset($tJoinFoooter,$tSQL,$oQuery,$aData,$aErrorList);
        return  $aResualt;
    }



    public function FMaMRPTPagination($paDataWhere){
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           =   "
            SELECT
                COUNT(STK.FTPdtCode) AS rnCountPage
            FROM TRPTPdtStkCrdSumTmp STK WITH(NOLOCK)
            WHERE FTUsrSession <> ''
            AND STK.FTComName       = ".$this->db->escape($tComName)."
            AND STK.FTRptCode       = ".$this->db->escape($tRptCode)."
            AND STK.FTUsrSession    = ".$this->db->escape($tUsrSession)."
        ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage - 1;
        $nNextPage      = $nPage + 1;
        $nRowIDStart    = (($nPerPage * $nPage) - $nPerPage); //RowId Start
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
            "nTotalRecord"  =>  $nRptAllRecord,
            "nTotalPage"    =>  $nTotalPage,
            "nDisplayPage"  =>  $paDataWhere['nPage'],
            "nRowIDStart"   =>  $nRowIDStart,
            "nRowIDEnd"     =>  $nRowIDEnd,
            "nPrevPage"     =>  $nPrevPage,
            "nNextPage"     =>  $nNextPage
        );
        unset($tComName,$tRptCode,$tUsrSession,$tSQL,$oQuery,$nRptAllRecord,$nPage,$nPerPage,$nPrevPage,$nNextPage,$nRowIDStart,$nTotalPage);
        unset($nRowIDEnd,$paDataWhere);
        return $aRptMemberDet;
    }

    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession){
        $tSQLUPD    = "
            UPDATE DATAUPD
            SET DATAUPD.FNRowPartID     = DATASLT.PartIDPdt
            FROM TRPTPdtStkCrdSumTmp DATAUPD
            RIGHT JOIN (
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTPdtCode ORDER BY FTPdtCode ASC,FTPdtCode ASC,FTRptRowSeq ASC) AS PartIDPdt,
                    FTRptRowSeq,
                    FTPdtCode,
                    FTComName,
                    FTRptCode,
                    FTUsrSession
                FROM TRPTPdtStkCrdSumTmp WITH(NOLOCK)
                WHERE FTUsrSession <> ''
                AND FTComName       = ".$this->db->escape($ptComName)."
                AND FTRptCode       = ".$this->db->escape($ptRptCode)."
                AND FTUsrSession    = ".$this->db->escape($ptUsrSession)."
            ) DATASLT ON DATASLT.FTRptRowSeq    = DATAUPD.FTRptRowSeq
            AND DATASLT.FTPdtCode       = DATAUPD.FTPdtCode
            AND DATASLT.FTComName       = DATAUPD.FTComName
            AND DATASLT.FTRptCode       = DATAUPD.FTRptCode
            AND DATASLT.FTUsrSession    = DATAUPD.FTUsrSession
        ";
        $this->db->query($tSQLUPD);
        unset($tSQLUPD,$ptComName,$ptRptCode,$ptUsrSession);
    }

    //set AjdStkBal
    private function FMxMRPTAjdStkBal($ptComName, $ptRptCode, $ptUsrSession){
        $tSQL   =   "
            UPDATE STK
                SET STK.FCStkQtyBal =  STKAJB.FCStkBal
            FROM TRPTPdtStkCrdSumTmp STK 
            LEFT JOIN (
                SELECT 
                    STKB.* , 
                    SUM(STKB.FCStkSumTrans) OVER ( PARTITION  BY STKB.FTPdtCode ORDER BY STKB.FTRptRowSeq) AS FCStkBal
                FROM (
                    SELECT
                        FTRptRowSeq,FTPdtCode,
                        ROW_NUMBER() OVER(PARTITION by FTPdtCode ORDER BY FTPdtCode) AS FNStkRowGroupNo,
                        SUM(FCStkQtyMonEnd + FCStkQtyIn - FCStkQtyOut + FCStkQtyAdj - (FCStkQtySaleDN - FCStkQtyCN) ) AS FCStkSumTrans
                    FROM TRPTPdtStkCrdSumTmp
                    WHERE FTUsrSession <> ''
                    AND FTComName       = ".$this->db->escape($ptComName)." 
                    AND FTRptCode       = ".$this->db->escape($ptRptCode)."
                    AND FTUsrSession    = ".$this->db->escape($ptUsrSession)."
                    GROUP BY FTRptRowSeq,FTPdtCode
                ) STKB 
            ) STKAJB ON STK.FTPdtCode = STKAJB.FTPdtCode
        ";
        $this->db->query($tSQL);
        unset($tSQL);
        unset($ptComName,$ptRptCode,$ptUsrSession);
    }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 21/08/2019 Saharat(Golf)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountDataReportAll($paDataWhere){
        $tUserSession   = $paDataWhere['tUserSession'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tSQL           = "   
            SELECT DTTMP.FTRptCode
            FROM TRPTPdtStkCrdSumTmp AS DTTMP WITH(NOLOCK)
            WHERE FTUsrSession = '$tUserSession'
            AND FTComName = '$tCompName'
            AND FTRptCode = '$tRptCode'
        ";
        $oQuery     = $this->db->query($tSQL);
        $nStaReurn  = $oQuery->num_rows();
        unset($tUserSession,$tCompName,$tRptCode,$tSQL,$oQuery);
        return $nStaReurn;
    }
}
