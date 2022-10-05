<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSalesAllBchByUNIT_model extends CI_Model{

    public function FSaMGetDataReport($paDataWhere) {

        $tYear          = $paDataWhere['aRptFilter']['tYear'];
        $tMonth         = $paDataWhere['aRptFilter']['tMonth'];

        $tTableName     = 'TRPTSalQtyByBch'.$tYear.$tMonth;
        if($this->db->table_exists($tTableName)){
            $tSQL   =   "   SELECT A.* FROM $tTableName A WITH(NOLOCK) ORDER BY A.FTPdtCat1 ASC ";
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

    //หาสาขาทั้งหมด
    public function FSaMGetDataReportBCHAll(){
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $tSQL   =   "   SELECT
                            BCHL.FTBchCode,
                            BCHL.FTBchName
                        FROM TCNMBranch_L BCHL WITH(NOLOCK) WHERE FNLngID = '$nLangEdit' ORDER BY BCHL.FTBchCode ASC ";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = NULL;
        }

        $aResualt = array(
            "aRptDataBCHAll"  => $aData
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }
}
