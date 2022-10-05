<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rptcstlostcont_model extends CI_Model {
    /**
     * Functionality: Call Store
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Status Return Call Stored Procedure
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter){
        $nLangID = $paDataFilter['nLangID'];
        $tUserSession = $paDataFilter['tUserSession'];
        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{CALL SP_RPTxCstLostCont(?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
            'ptUsrSession'      => $tUserSession,
            'pnLngID'           => $nLangID,
            'ptAgnCode'         => $paDataFilter['tAgnCode'],
            'ptBchL'            => $tBchCodeSelect,
            'pnLostContNum'     => $paDataFilter['tLostContNum'],
            'ptCstCodeFrm'      => $paDataFilter['tCstCodeFrom'],
            'ptCstCodeTo'       => $paDataFilter['tCstCodeTo'],
            'tRegCodeFrom'     => $paDataFilter['tRegCodeFrom'],
            'tRegCodeTo'        => $paDataFilter['tRegCodeTo'],
            'pnResult'          => 0,
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);

        if($oQuery !== FALSE){
            unset($oQuery);
            return 1;
        }else{
            unset($oQuery);
            return 0;
        }
    }

    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */
    public function FSnMCountRowInTemp($paParams){
        $tUsrSession = $paParams['tSessionID'];

        $tSQL = "
            SELECT
                TMP.FTUsrSession
            FROM TRPTSVCstLostContTmp TMP WITH(NOLOCK)
            WHERE TMP.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        return $oQuery->num_rows();
    }

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : status
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere){

        // Call Data Pagination
        $aPagination = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);


        $tJoinFoooter = "   
            SELECT
                FTUsrSession            AS FTUsrSession_Footer,
                COUNT(FNRptRowSeq)      AS Cst_Total
            FROM TRPTSVCstLostContTmp WITH(NOLOCK)
            WHERE 1=1
            AND FTUsrSession = '$tUsrSession'
            GROUP BY FTUsrSession 
        ) T ON C.FTUsrSession = T.FTUsrSession_Footer ";

        
        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "SELECT C.* ,  T.*
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(ORDER BY A.FDFlwLastDate ASC , A.FNRptRowSeq ASC ) AS rtRowID,
                        A.*
                    FROM(
                        SELECT
                            *
                        FROM TRPTSVCstLostContTmp
                        WHERE FTUsrSession    = '" . $tUsrSession . "'
                    ) AS A
                ) AS C ";
        $tSQL .= " LEFT JOIN (" . $tJoinFoooter . " ";
        $tSQL .= " WHERE C.rtRowID > $nRowIDStart AND C.rtRowID <= $nRowIDEnd" ;

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->result_array();
        }else{
            $aData = NULL;
        }

        $aErrorList = [
            "nErrInvalidPage" => ""
        ];

        $aResualt= [
            "aPagination" => $aPagination,
            "aRptData" => $aData,
            "aError" => $aErrorList
        ];
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    private function FMaMRPTPagination($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            SELECT
                TSPT.FTUsrSession
            FROM TRPTSVCstLostContTmp TSPT WITH(NOLOCK)
            WHERE TSPT.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->num_rows();
        $nPage = $paDataWhere['nPage'];

        $nPerPage = $paDataWhere['nPerPage'];

        $nPrevPage = $nPage-1;
        $nNextPage = $nPage+1;
        $nRowIDStart = (($nPerPage*$nPage)-$nPerPage); //RowId Start
        if($nRptAllRecord<=$nPerPage){
            $nTotalPage = 1;
        }else if(($nRptAllRecord % $nPerPage)==0){
            $nTotalPage = ($nRptAllRecord/$nPerPage) ;
        }else{
            $nTotalPage = ($nRptAllRecord/$nPerPage)+1;
            $nTotalPage = (int)$nTotalPage;
        }

        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if($nRowIDEnd > $nRptAllRecord){
            $nRowIDEnd = $nRptAllRecord;
        }

        $aRptMemberDet = array(
            "nTotalRecord" => $nRptAllRecord,
            "nTotalPage" => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart" => $nRowIDStart,
            "nRowIDEnd" => $nRowIDEnd,
            "nPrevPage" => $nPrevPage,
            "nNextPage" => $nNextPage,
            "nPerPage" => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "
            UPDATE TRPTSVCstLostContTmp
                SET TRPTSVCstLostContTmp.FNRptRowSeq = B.PartID
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(PARTITION BY convert(varchar, FDFlwLastDate, 23) ORDER BY FDFlwLastDate ASC) AS PartID ,
                        TMP.FNRptRowSeq
                    FROM TRPTSVCstLostContTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTUsrSession = '$tUsrSession' ";

        $tSQL .= "
            ) AS B
            WHERE 1=1
            AND TRPTSVCstLostContTmp.FNRptRowSeq = B.FNRptRowSeq
            AND TRPTSVCstLostContTmp.FTUsrSession = '$tUsrSession'
        ";

        $this->db->query($tSQL);
    }

}
