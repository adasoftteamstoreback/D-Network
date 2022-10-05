<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSalesSumQtyAndPrice_model extends CI_Model{

    public function FSaMGetDataReport($paDataWhere) {

        $tYear          = $paDataWhere['aRptFilter']['tYear'];
        $tMonth         = $paDataWhere['aRptFilter']['tMonth'];
        $tCate1         = $paDataWhere['aRptFilter']['tCate1From'];
        $tCate2         = $paDataWhere['aRptFilter']['tCate2From'];
        $tBchCodeSelect = $paDataWhere['aRptFilter']['tBchCodeSelect'];
        $tDayFrom       = $paDataWhere['aRptFilter']['tDayFrom'];
        $tDayTo         = $paDataWhere['aRptFilter']['tDayTo'];


        $tSQLWhere      = '';

        if (!empty($tBchCodeSelect) && $tBchCodeSelect != '') {
            $tBchCodeSelect = str_replace(",","','",$tBchCodeSelect);
            $tSQLWhere .= "AND A.FTBchCode IN ('$tBchCodeSelect')";
        }

        if( $tDayFrom == '' ||  $tDayTo == null){
            $tSQLWhere .= " ";
        }else{
            $tDayTo             = $tDayTo + 1;
            $tDayFrom           = (strlen($tDayFrom) == 1) ? '0'.$tDayFrom : $tDayFrom;
            $tDayTo             = (strlen($tDayTo) == 1) ? '0'.$tDayTo : $tDayTo;
            $tFormatDateFrom    =  $tYear . $tMonth . $tDayFrom;
            $tFormatDateTo      =  $tYear . $tMonth . $tDayTo; 
            $tSQLWhere .= "AND CONVERT(VARCHAR(10), A.FDXshDocDate, 112) BETWEEN '$tFormatDateFrom' AND '$tFormatDateTo' ";
        }
  
        if (!empty($tCate1) && $tCate1 != '') {
            $tCate1Replace = str_replace(",","','",$tCate1);
            $tSQLWhere .= "AND A.FTPdtCat1 IN ('$tCate1Replace')";
        }

        if (!empty($tCate2) && $tCate2 != '') {
            $tCate2Replace = str_replace(",","','",$tCate2);
            $tSQLWhere .= "AND A.FTPdtCat2 IN ('$tCate2Replace')";
        }

        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tTableName     = 'TRPTSaleAllBchByPdt'.$tYear.$tMonth;
        if($this->db->table_exists($tTableName)){
            $tSQL   =   "   SELECT 
                                A.FTBchCode ,
                                A.FTPdtCat1 , 
                                A.FTCatName1 , 
                                A.FTPdtCat2 , 
                                A.FTCatName2 , 
                                SUM(A.FCXsdQty) AS FCXsdQty , 
                                SUM(A.FCXsdNetAfHD) AS FCXsdNetAfHD ,
                                B.FTBchName ,
                                C.FCXsdQty_Footer,
                                C.FCXsdNetAfHD_Footer
                            FROM $tTableName A WITH(NOLOCK) 
                            LEFT JOIN TCNMBranch_L B  WITH(NOLOCK) ON A.FTBchCode = B.FTBchCode AND B.FNLngID = '$nLangEdit' 
                            CROSS JOIN (
                                    SELECT 
                                        SUM(A.FCXsdQty) AS FCXsdQty_Footer , 
                                        SUM(A.FCXsdNetAfHD) AS FCXsdNetAfHD_Footer 
                                    FROM $tTableName A
                                    WHERE 1=1
                                    $tSQLWhere
                                ) C
                            WHERE 1=1
                                $tSQLWhere
                                GROUP BY 
                            A.FTBchCode ,
                            A.FTPdtCat1 , 
                            A.FTCatName1 , 
                            A.FTPdtCat2 , 
                            A.FTCatName2 ,
                            B.FTBchName ,
                            C.FCXsdQty_Footer,
                            C.FCXsdNetAfHD_Footer
                            ORDER BY A.FTBchCode , A.FTPdtCat1 ASC ";
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
