<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptTranferIn_model extends CI_Model{

    public function FSaMGetDataReport($paDataWhere) {

        $tBchCodeSelect = $paDataWhere['aRptFilter']['tBchCodeSelect'];
        $tSplCodeSelect = $paDataWhere['aRptFilter']['tSplCodeSelect'];
        $tDateForm      = $paDataWhere['aRptFilter']['tDocDateFrom'];
        $tDateTo        = $paDataWhere['aRptFilter']['tDocDateTo'];

        if (empty($tDateTo) && $tDateTo == '') {
            $tDateTo = $tDateForm;
        }

        $tSQLWhere      = '';

        if (!empty($tBchCodeSelect) && $tBchCodeSelect != '') {
            $tBchCodeSelect = str_replace(",","','",$tBchCodeSelect);
            $tSQLWhere .= "AND HD.FTBchCode IN ('$tBchCodeSelect')";
        }

        if (!empty($tSplCodeSelect) && $tSplCodeSelect != '') {
            $tSplCodeReplace = str_replace(",","','",$tSplCodeSelect);
            $tSQLWhere .= "AND HD.FTSplCode IN ('$tSplCodeReplace')";
        }

        if (!empty($tDateForm) && $tDateForm != '') {
            $tSQLWhere .= "AND HD.FDXthDocDate BETWEEN CONVERT(datetime,'$tDateForm 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ";
        }

        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tTableName     = 'TCNTPdtTwiHD';
        if($this->db->table_exists($tTableName)){
            $tSQL   =   "SELECT 
                            BCL.FTBchName,
                            HD.FTXthDocNo,
                            convert(Date,HD.FDXthDocDate,121) AS FDXthDocDate,
                            HD.FTSplCode,
                            spl.FTSplName,
                            HD.FTXthRefExt,
                            HD.FDXthRefExtDate,
                            DWI.FTPdtCode,
                            DWI.FTXtdPdtName,
                            DWI.FCXtdQtyAll,
                            DWI.FTPunName,
                            HD.FTXthRmk
                        from $tTableName HD WITH(NOLOCK) 
                        LEFT JOIN [dbo].TCNTPdtTwiDT DWI WITH(NOLOCK)  ON HD.FTXthDocNo = DWI.FTXthDocNo
                        LEFT JOIN TCNMBranch_L BCL WITH(NOLOCK)  ON  HD.FTBchCode = BCL.FTBchCode
                        LEFT JOIN TCNMSpl_L Spl WITH(NOLOCK)  ON HD.FTSplCode =  Spl.FTSplCode
                        WHERE  HD.FTXthStaDoc = '1' 
                        AND HD.FTXthStaApv = '1' 
                        $tSQLWhere 
                        ORDER BY BCL.FTBchName,HD.FTXthDocNo,DWI.FNXtdSeqNo
                            ";
            // echo $tSQL;
            // exit;
            $oQuery = $this->db->query($tSQL);
    
            if ($oQuery->num_rows() > 0) {
                $aData = $oQuery->result_array();
            } else {
                $aData = NULL;
            } 
        }else{
            $aData = NULL;
        }

        $aResualt = array(
            "aRptData"  => $aData
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }
}
