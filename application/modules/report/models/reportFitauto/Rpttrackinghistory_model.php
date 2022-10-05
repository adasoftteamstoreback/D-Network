<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rpttrackinghistory_model extends CI_Model
{
    //Call Stored
    public function FSnMExecStoreReport($paDataFilter)
    {

        // สาขา
        $tBchCodeSelect = ($paDataFilter['bBchStaSelectAll']) ? '' : FCNtAddSingleQuote($paDataFilter['tBchCodeSelect']);

        $tCallStore = "{ CALL SP_RPTxSalAvgByQtyByWeek(?,?,?,?,?,?) }";
        $aDataStore = array(

            'ptAgnCode'     => $paDataFilter['tAgnCodeSelect'],
            'ptUsrSessionID'    => $paDataFilter['tSessionID'],
            'ptLangID'          => $paDataFilter['nLangID'],
            'ptBchCode'     => $tBchCodeSelect,
            'ptDayOfWeek'      => '',
            'ptRptSubBy'       => $paDataFilter['tSubByCodeSelect'],
        );

        // 'pdDocDateFrm'      => '2021-09-21',
        // 'pdDocDateTo'       => '2021-10-01',

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

    //Get Data Report In Table Temp
    public function FSaMGetDataReport($paDataWhere)
    {
        $nPage          = $paDataWhere['nPage'];
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // $tUsrSession    = '0000220211105144828';



        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        // if ($nPage == $nTotalPage) {
        //     $tJoinFoooter = "   
        //         SELECT
        //         FTUsrSession            AS FTUsrSession_Footer,
        //         SUM(FCXrcNet)           AS FCXidQty_Footer,
        //         COUNT(FTUsrSession)     AS RowID_Footer
        //         FROM TRPTSalAvgByQtyByWeekTmp WITH(NOLOCK)
        //         WHERE 1=1
        //         AND FTUsrSession    = '$tUsrSession'
        //         GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
        //     ";
        // } else {
        //     // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
        //     $tJoinFoooter = "   
        //         SELECT
        //             '$tUsrSession'  AS FTUsrSession_Footer,
        //             '0'             AS FCXidQty_Footer,
        //             '0'            AS RowID_Footer
        //         ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
        //     ";
        // }
        // if ($nPage == $nTotalPage) {
        //     $tJoinFoooter = "   
        //         SELECT
        //         FTUsrSession            AS FTUsrSession_Footer
        //         FROM TRPTSalAvgByQtyByWeekTmp WITH(NOLOCK)
        //         WHERE 1=1
        //         AND FTUsrSession    = '$tUsrSession'
        //         GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
        //     ";
        // } else {
        //     // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
        //     $tJoinFoooter = "   
        //         SELECT
        //             '$tUsrSession'  AS FTUsrSession_Footer
        //         ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
        //     ";
        // }
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT
                FTUsrSession            AS FTUsrSession_Footer,
                SUM(FCXshQty)           AS FCXshQty_Footer,
                SUM(FCXshPercentByQty)           AS FCXshPercentByQty_Footer,
                SUM(FCXshTotal)           AS FCXshTotal_Footer,
                SUM(FCXshPercentByTotal)           AS FCXshPercentByTotal_Footer,
                SUM(FCXshDisChg)           AS FCXshDisChg_Footer,
                SUM(FCXshGrand)           AS FCXshGrand_Footer,
                SUM(FCXshPercentByGrand)           AS FCXshPercentByGrand_Footer,
                SUM(FCXshSalAvgByQty)           AS FCXshSalAvgByQty_Footer
                FROM TRPTSalAvgByQtyByWeekTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer,
                    '0'           AS FCXshQty_Footer,
                    '0'           AS FCXshPercentByQty_Footer,
                    '0'           AS FCXshTotal_Footer,
                    '0'          AS FCXshPercentByTotal_Footer,
                    '0'          AS FCXshDisChg_Footer,
                    '0'           AS FCXshGrand_Footer,
                    '0'           AS FCXshPercentByGrand_Footer,
                    '0'         AS FCXshSalAvgByQty_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }


        $tSQL = "
            SELECT
                ROW_NUMBER() OVER(PARTITION BY L.FTBchCode ORDER BY  L.FTBchCode ASC) AS FNFmtPageRow,
                SUM(1) OVER (PARTITION BY L.FTBchCode) AS FNFmtMaxPageRow,
                L.*,
                T.FCXshQty_Footer,
                T.FCXshPercentByQty_Footer,
                T.FCXshTotal_Footer,
                T.FCXshPercentByTotal_Footer,
                T.FCXshDisChg_Footer,
                T.FCXshGrand_Footer,
                T.FCXshPercentByGrand_Footer,
                T.FCXshSalAvgByQty_Footer,
                S.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTBchCode ASC, FTBchCode ASC) AS RowID,
                    ROW_NUMBER() OVER(PARTITION BY FTBchCode ORDER BY  FTBchCode ASC) AS FNFmtAllRow,
                    SUM(1) OVER (PARTITION BY FTBchCode) AS FNFmtEndRow,
                    ROW_NUMBER() OVER(PARTITION BY FTBchCode,FTSubByCode
                    ORDER BY FTBchCode ASC) AS SumSub ,
                      SUM(1) OVER(PARTITION BY FTBchCode,FTSubByCode) AS SumSubEndRow, 
                    A.*
                FROM TRPTSalAvgByQtyByWeekTmp A WITH(NOLOCK)
                WHERE A.FTUsrSession    = '$tUsrSession'
            ) AS L  
            LEFT JOIN (
                SELECT
                    FTUsrSession            AS FTUsrSession_Footer,
                    FTBchCode              AS BchCode_Footer,
                    FTSubByCode            AS SubByCode_Footer,
                    SUM(FCXshQty)           AS FCXshQty_DocNo_Footer,
                    SUM(FCXshPercentByQty)           AS FCXshPercentByQty_DocNo_Footer,
                    SUM(FCXshTotal)           AS FCXshTotal_DocNo_Footer,
                    SUM(FCXshPercentByTotal)           AS FCXshPercentByTotal_DocNo_Footer,
                    SUM(FCXshDisChg)           AS FCXshDisChg_DocNo_Footer,
                    SUM(FCXshGrand)           AS FCXshGrand_DocNo_Footer,
                    SUM(FCXshPercentByGrand)           AS FCXshPercentByGrand_DocNo_Footer,
                    SUM(FCXshSalAvgByQty)           AS FCXshSalAvgByQty_DocNo_Footer
                FROM TRPTSalAvgByQtyByWeekTmp WITH(NOLOCK)
                WHERE FTUsrSession    = '$tUsrSession'
                GROUP BY FTBchCode,FTSubByCode, FTUsrSession
            ) S ON L.FTUsrSession = S.FTUsrSession_Footer AND L.FTBchCode = S.BchCode_Footer AND L.FTSubByCode = S.SubByCode_Footer
            
            LEFT JOIN (
                " . $tJoinFoooter . "";

        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";



        $oQuery = $this->db->query($tSQL);
        // echo $this->db->last_query(); die();
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

    //Count จำนวน
    private function FMaMRPTPagination($paDataWhere)
    {
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        // $tUsrSession    = '0000220211105144828';
        $tSQL = "SELECT
                    COUNT(RPT.FTBchCode) AS rnCountPage
                 FROM TRPTSalAvgByQtyByWeekTmp RPT WITH(NOLOCK)
                 WHERE RPT.FTUsrSession = '$tUsrSession'";

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
