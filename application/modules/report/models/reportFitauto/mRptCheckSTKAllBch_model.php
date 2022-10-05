<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mrptcheckstkallbch_model extends CI_Model{

    public function FSaMGetDataReport($paDataWhere) {
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        $tYear          = $paDataWhere['aRptFilter']['tYear'];
        $tMonth         = $paDataWhere['aRptFilter']['tMonth'];
        $tDay           = $paDataWhere['aRptFilter']['tDay'];
        $tCate1         = $paDataWhere['aRptFilter']['tCate1From'];
        $tCate2         = $paDataWhere['aRptFilter']['tCate2From'];
        $tWhereCate1    = "";
        $tWhereCate2    = "";

        //ถ้าไม่ได้ระบุวันที่มา ต้องเป็นวันที่ ปัจจุบัน
        if( $tDay == '' ||  $tDay == null){
            $tDay = date("d");
        }

        //ถ้าเดือนที่ระบุ ไม่ตรงกับ เดือนปัจจุบันต้องเอาวันที่วันสุดท้ายของเดือนนั้นๆ
        if($tMonth != date("m")){
            //ถ้าไมไ่ด้ระบุวันที่
            if( $tDay == '' ||  $tDay == null){
                $tFormatCheckLast   = $tYear.'-'.$tMonth.'-'.'01';
                $tDay               = date('t',strtotime($tFormatCheckLast));
            }else{
                $tDay               = $tDay;
            }
        }

        if (!empty($tCate1) && $tCate1 != '') {
            $tCate1Replace = str_replace(",","','",$tCate1);
            $tWhereCate1 = "AND A.FTCatCode1 IN ('$tCate1Replace')";
        }

        if (!empty($tCate2) && $tCate2 != '') {
            $tCate2Replace = str_replace(",","','",$tCate2);
            $tWhereCate2 = "AND A.FTCatCode2 IN ('$tCate2Replace')";
        }

        $tTableName     = 'TRPTStkBch'.$tYear.$tMonth.$tDay;
        if($this->db->table_exists($tTableName)){
            $tSQL   =   "SELECT 
                            A.* 
                        FROM $tTableName A WITH(NOLOCK) 
                        WHERE 1=1
                        $tWhereCate1
                        $tWhereCate2
                        ORDER BY A.FTPdtCode DESC ";
            $oQuery = $this->db->query($tSQL);
            // echo $this->db->last_query();
            // exit;
            
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
