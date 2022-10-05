<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSaleRecive extends CI_Model {

    /**
     * Functionality: Delete Temp Report
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * Last Modified :
     * Return : Call Store Proce
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter) {
        $nLangID        = $paDataFilter['nLangID'];
        $tComName       = $paDataFilter['tCompName'];
        $tRptCode       = $paDataFilter['tRptCode'];
        $tUserSession   = $paDataFilter['tUserSession'];
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']); 
        // ร้านค้า
        $tShpCodeSelect = ($paDataFilter['bShpStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tShpCodeSelect']);
        // กลุ่มธุรกิจ
        $tMerCodeSelect = ($paDataFilter['bMerStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tMerCodeSelect']);
        // ประเภทเครื่องจุดขาย
        $tPosCodeSelect = ($paDataFilter['bPosStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tPosCodeSelect']);
        $tCallStore     = "{ CALL SP_RPTxPaymentDET1001005(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore     = array(
            'pnLngID'       => $nLangID,
            'ptComName'     => $tComName,
            'ptRptCode'     => $tRptCode,
            'ptUserSession' => $tUserSession,
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
            'ptRcvF'        => $paDataFilter['tRcvCodeFrom'],
            'ptRcvT'        => $paDataFilter['tRcvCodeTo'],
            'ptDocDateF'    => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'    => $paDataFilter['tDocDateTo'],
            'FNResult'      => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery != FALSE) {
            $nStaReturn = 1;
        } else {
            $nStaReturn = 0;
        }
        unset($nLangID,$tComName,$tRptCode,$tUserSession,$tBchCodeSelect,$tShpCodeSelect,$tMerCodeSelect,$tPosCodeSelect);
        unset($tCallStore,$aDataStore,$oQuery);
        return $nStaReturn;
    }

    /**
     * Functionality: Get Data Report
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * Last Modified : 13/11/2019 Piya
     * Return : Get Data Rpt Temp
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere) {
		
        /// ค่า Apptype
        $nApptype       = $paDataWhere['paDataFilter']['nPosType'];
        $nPage          = $paDataWhere['nPage'];
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        if($paDataWhere['nPerPage'] != 0 ){
            $aPagination    = $this->FMaMRPTPagination($paDataWhere);
            $nRowIDStart    = $aPagination["nRowIDStart"];
            $nRowIDEnd      = $aPagination["nRowIDEnd"];
            $nTotalPage     = $aPagination["nTotalPage"];
        }else{
            $nTotalPage     = 1;
            $aPagination    = 0;
        }
        
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if($nPage == $nTotalPage){
            $tJoinFoooter = "   
                SELECT 
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM(FCXrcNet) AS FCXrcNet_Footer
                FROM TRPTSalRCTmp WITH(NOLOCK)
                WHERE FTComName     = '$tComName'
                AND FTRptCode       = '$tRptCode'
                AND FTUsrSession    = '$tUsrSession'";
                if(!empty($nApptype)){
                    $tJoinFoooter .= " AND FNAppType='".$nApptype."' ";
                }
                $tJoinFoooter .= "GROUP BY FTUsrSession ) T 
                ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }else{
            $tJoinFoooter   = "   
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    '0' AS FCXrcNet_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
            if(!empty($nApptype)){
                $tJoinFoooter .= " AND FNAppType='".$nApptype."' ";
            }
        }
        /**
         * L = List ข้อมูลทั้งหมด
         * A = Data Main
         * S = Sub Groupping
        */
        $tSQL   = "
            SELECT C.* FROM ( 
				SELECT 
					ROW_NUMBER () OVER (ORDER BY L.FTRcvCode ASC) AS RowID,
					L.*,
					T.FCXrcNet_Footer
				FROM (
					SELECT 
						ROW_NUMBER() OVER(PARTITION BY A.FNAppType,A.FTRcvCode,A.FTBnkCode ORDER BY A.FNAppType ASC,A.FTRcvCode ASC,A.FTBnkCode ASC,A.FDXrcRefDate DESC) AS FNFmtAllRow,
						SUM(1) OVER(PARTITION BY A.FNAppType,A.FTRcvCode,A.FTBnkCode) AS FNFmtEndRow,
						A.FNAppType,
						A.FNXshDocType,
						A.FTBchCode,
						A.FTXshDocNo,
						A.FNXrcSeqNo,
						A.FTFmtCode,
						A.FTRcvCode,
						A.FTRcvName,
						A.FTXrcRefNo1,
						A.FTXrcRefNo2,
						A.FDXrcRefDate,
						A.FTXrcRefDesc,
						A.FTBnkCode,
						A.FTBnkName,
						A.FCXrcNet,
						S.FCXrcNet_SubTotal,
						A.FTUsrSession,
						A.FTRptCode
					FROM TRPTSalRCTmp A WITH(NOLOCK)
					LEFT JOIN (
						SELECT
							FTRcvCode AS FTRcvCode_SUM,
							COUNT(FTRcvCode) AS FNRptGroupMember,
							SUM(FCXrcNet) AS FCXrcNet_SubTotal
						FROM TRPTSalRCTmp WITH(NOLOCK)
						WHERE FTComName     = ".$this->db->escape($tComName)."
						AND FTRptCode       = ".$this->db->escape($tRptCode)."
						AND FTUsrSession    = ".$this->db->escape($tUsrSession)."
						GROUP BY FTRcvCode
					) AS S ON A.FTRcvCode = S.FTRcvCode_SUM
				) AS L ";
        if($paDataWhere['nPerPage'] != 0 ){
            $tSQL   .= "  	LEFT JOIN (" . $tJoinFoooter . "";
            $tSQL   .= " 	WHERE L.FTRptCode = ".$this->db->escape($tRptCode)." AND L.FTUsrSession = ".$this->db->escape($tUsrSession)." ) ";
			$tSQL   .= " 	C WHERE C.RowID > $nRowIDStart AND C.RowID <= $nRowIDEnd ";
        }else{
            $tSQL   .= "  	LEFT JOIN (" . $tJoinFoooter . "";
			$tSQL   .= " 	WHERE L.FTRptCode = ".$this->db->escape($tRptCode)." AND L.FTUsrSession = ".$this->db->escape($tUsrSession)." ) ";
			$tSQL   .= " 	C ";
        }
		
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData  = $oQuery->result_array();
        } else {
            $aData  = NULL;
        }
        $aErrorList = array(
            "nErrInvalidPage"   => ""
        );
        $aResualt = array(
            "aPagination"   => $aPagination,
            "aRptData"      => $aData,
            "aError"        => $aErrorList
        );
        unset($nPage,$aPagination,$nRowIDStart,$nRowIDEnd,$nTotalPage,$tJoinFoooter);
        unset($tSQL,$oQuery,$aData,$aErrorList);
        return $aResualt;
    }
   
    /**
     * Functionality: Count Data Report All
     * Parameters: Function Parameter
     * Creator: 22/04/2019 Wasin(Yoshi)
     * Last Modified: 13/11/2019 Piya
     * Return: Data Report All
     * ReturnType: Array
    */
    public function FMaMRPTPagination($paDataWhere) {
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tSQL           = "   
            SELECT
                RCV.FTRcvCode
            FROM TRPTSalRCTmp RCV WITH(NOLOCK)
            WHERE RCV.FTComName = '$tComName'
            AND RCV.FTRptCode = '$tRptCode'
            AND RCV.FTUsrSession = '$tUsrSession'
        ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->num_rows();
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage - 1;
        $nNextPage      = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage); // RowId Start
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
		
        unset($tComName,$tRptCode,$tUsrSession,$tSQL,$oQuery,$nRptAllRecord,$nPage,$nPerPage,$nPrevPage,$nNextPage,$nRowIDStart,$nRowIDEnd);
        unset($nTotalPage);
        unset($paDataWhere);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Set Priority Group
     * Parameters: Function Parameter
     * Creator: 22/04/2019 Wasin(Yoshi)
     * Last Modified: 13/11/2019 Piya
     * Return: Data Report All
     * ReturnType: Array
    */
    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {
        $tSQL = "
            UPDATE TRPTSalRCTmp SET 
                FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTRcvCode ORDER BY FTRcvCode ASC) AS PartID, 
                    FTRptRowSeq  
                FROM TRPTSalRCTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName     = ".$this->db->escape($ptComName)."
                AND TMP.FTRptCode       = ".$this->db->escape($ptRptCode)."
                AND TMP.FTUsrSession    = ".$this->db->escape($ptUsrSession)."
            ) AS B
            WHERE TRPTSalRCTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTSalRCTmp.FTComName      = ".$this->db->escape($ptComName)."
            AND TRPTSalRCTmp.FTRptCode      = ".$this->db->escape($ptRptCode)."
            AND TRPTSalRCTmp.FTUsrSession   = ".$this->db->escape($ptUsrSession)."
        ";
        $this->db->query($tSQL);
        unset($tSQL);
        unset($ptComName,$ptRptCode,$ptUsrSession);
    }

}


