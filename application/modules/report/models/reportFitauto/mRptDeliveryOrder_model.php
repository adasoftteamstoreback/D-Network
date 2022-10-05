<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptDeliveryOrder_model extends CI_Model{

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
            $tSQLWhere .= "AND HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tDateForm 00:00:00') AND CONVERT(datetime,'$tDateTo 23:59:59') ";
        }

        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tTableName     = 'TAPTDoHD';
        if($this->db->table_exists($tTableName)){
            $tSQL   =   "   SELECT 
                                HD.FTBchCode,
                                BCL.FTBchName,
                                HD.FTXphDocNo,
                                HD.FDXphDocDate,
                                HD.FTSplCode,
                                Spl.FTSplName,
                                HDRef.FTXshRefDocNo,
                                HDRef.FDXshRefDocDate,
                                DT.FTPdtCode,
                                DT.FTXpdPdtName,
                                DT.FCXpdQtyAll,
                                DT.FTPunName
                            FROM $tTableName HD WITH(NOLOCK) 
                            LEFT JOIN TAPTDoDT DT ON HD.FTXphDocNo = DT.FTXphDocNo
                            LEFT JOIN TCNMBranch_L BCL ON  HD.FTBchCode = BCL.FTBchCode
                            LEFT JOIN TCNMSpl_L Spl ON HD.FTSplCode =  Spl.FTSplCode
                            LEFT JOIN TAPTDoHDDocRef HDRef ON HD.FTXphDocNo = HDRef.FTXshDocNo
                            WHERE  HD.FTXphStaDoc = '1' $tSQLWhere 
                            ORDER BY BCL.FTBchName, HD.FTXphDocNo, DT.FNXpdSeqNo
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
