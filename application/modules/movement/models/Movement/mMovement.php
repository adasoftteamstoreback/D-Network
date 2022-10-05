<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mMovement extends CI_Model{

    //Functionality : list Data Movement
    //Parameters : function parameters
    //Creator :  10/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return : data
    //Return Type : Array
    public function FSaMMovementList($paData){
        $tWhereBch  = "";
        $tWherePdt  = "";
        $tWhereWah  = "";
        $SqlWhere   = "";
        $tWhereProductActive    = "";
        $nLngID         = $paData['FNLngID'];
        $tBchCode       = $paData['tSearchAll']['tBchCode'];
        $tWahCode       = $paData['tSearchAll']['tWahCode'];
        $tPdtCode       = $paData['tSearchAll']['tPdtCode'];
        $tMmtPdtActive  = $paData['tSearchAll']['nPdtActive'];
        $tMmtMonth      = $paData['tSearchAll']['tMmtMonth'];
        $tMmtYear       = $paData['tSearchAll']['tMmtYear'];
        $this->session->set_userdata('tDataFilter',$paData['tSearchAll']);

        if ($tBchCode != "") {
            $tBchCodeText = str_replace(",", "','", $tBchCode);
            $tWhereBch = " AND StkCrd.FTBchCode IN ('$tBchCodeText')";
        }

        if ($tPdtCode != "") {
            $tPdtCodeText = str_replace(",", "','", $tPdtCode);
            $tWherePdt = " AND StkCrd.FTPdtCode IN ('$tPdtCodeText')";
        }

        if ($tWahCode != "") {
            $tWahCodeText = str_replace(",", "','", $tWahCode);
            $tWhereWah = " AND StkCrd.FTWahCode IN ('$tWahCodeText')";
        }

        if ($tMmtPdtActive == "1") {
            $tWhereProductActive = " AND PDTM.FTPdtStaActive = '1' ";
        }

        if($tMmtMonth!=""){
            $tWhereMonth = " AND FORMAT(StkCrd.FDStkDate, 'MM') = FORMAT($tMmtMonth,'00') ";
        }else {
          $tWhereMonth = " ";
        }

        if($tMmtYear!=""){
            $tWhereYear = " AND FORMAT(StkCrd.FDStkDate, 'yyyy') = FORMAT($tMmtYear,'0000') ";
        }else {
          $tWhereYear = "";
        }

        $SqlWhere   = $tWhereBch . ' ' . $tWherePdt . ' ' . $tWhereWah. ' ' . $tWhereProductActive. ' ' . $tWhereMonth. ' ' . $tWhereYear ;
        $tSQL       = "
            SELECT TOP ". get_cookie('nShowRecordInPageList')."
                StkCrd.FTBchCode,
                StkCrd.FTPdtCode,
                PDT.FTPdtName,
                StkCrd.FTStkDocNo,
                StkCrd.FDStkDate,
                StkCrd.FCStkQty,
                StkCrd.FTWahCode,
                WAH.FTWahName,
                StkCrd.FTStkType,
                CASE WHEN StkCrd.FTStkType  = '0' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkMonthEnd,
                CASE WHEN StkCrd.FTStkType  = '1' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkIN,
                CASE WHEN StkCrd.FTStkType  = '2' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkOUT,
                CASE WHEN StkCrd.FTStkType  = '3' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkSale,
                CASE WHEN StkCrd.FTStkType  = '4' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkReturn,
                CASE WHEN StkCrd.FTStkType  = '5' THEN StkCrd.FCStkQty  ELSE 0  END AS FCStkAdjust,
                SUM(
                    CASE
                        WHEN StkCrd.FTStkType = '0' THEN StkCrd.FCStkQty * 1
                        WHEN StkCrd.FTStkType = '1' THEN StkCrd.FCStkQty * 1
                        WHEN StkCrd.FTStkType = '2' THEN StkCrd.FCStkQty * -1
                        WHEN StkCrd.FTStkType = '3' THEN StkCrd.FCStkQty * -1
                        WHEN StkCrd.FTStkType = '4' THEN StkCrd.FCStkQty
                        WHEN StkCrd.FTStkType = '5' THEN StkCrd.FCStkQty * 1
                        ELSE 0
                    END
                )
                OVER(PARTITION BY StkCrd.FTPdtCode,StkCrd.FTWahCode,CONVERT (VARCHAR (7),StkCrd.FDStkDate,121) ORDER BY StkCrd.FTBchCode,
                StkCrd.FTPdtCode, StkCrd.FTWahCode,StkCrd.FDStkDate) AS FCStkQtyInWah
            FROM (
                SELECT
                    STK.FNStkCrdID,
                    STK.FTBchCode,
                    STK.FDStkDate,
                    STK.FTStkDocNo,
                    STK.FTWahCode,
                    STK.FTPdtCode,
                    STK.FTStkType,
                    STK.FCStkQty,
                    STK.FCStkSetPrice,
                    STK.FCStkCostIn,
                    STK.FCStkCostEx,
                    STK.FDCreateOn,
                    STK.FTCreateBy,
                    STK.FTPdtParent
                FROM TCNTPdtStkCrd STK WITH (NOLOCK)
                UNION ALL
                SELECT
                    STKME.FNStkCrdID,
                    STKME.FTBchCode,
                    STKME.FDStkDate,
                    STKME.FTStkDocNo,
                    STKME.FTWahCode,
                    STKME.FTPdtCode,
                    STKME.FTStkType,
                    STKME.FCStkQty,
                    STKME.FCStkSetPrice,
                    STKME.FCStkCostIn,
                    STKME.FCStkCostEx,
                    STKME.FDCreateOn,
                    STKME.FTCreateBy,
                    '' AS FTPdtParent
                FROM TCNTPdtStkCrdME STKME WITH (NOLOCK)
            ) StkCrd
            LEFT JOIN TCNMPdt   PDTM WITH(NOLOCK) ON StkCrd.FTPdtCode = PDTM.FTPdtCode
            LEFT JOIN TCNMPdt_L PDT WITH(NOLOCK) ON StkCrd.FTPdtCode = PDT.FTPdtCode AND PDT.FNLngID = ".$this->db->escape($nLngID)."
            LEFT JOIN TCNMWaHouse_L WAH WITH(NOLOCK) ON StkCrd.FTBchCode = WAH.FTBchCode AND StkCrd.FTWahCode = WAH.FTWahCode AND WAH.FNLngID = ".$this->db->escape($nLngID)."
            WHERE StkCrd.FTBchCode <> ''
        ";

        $tSQL .= $SqlWhere;

        $tSQL .= " ORDER BY StkCrd.FTPdtCode ASC, PDT.FTPdtName, StkCrd.FDStkDate, StkCrd.FTStkDocNo , StkCrd.FTWahCode";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'       => $aList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        } else {
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($tWhereBch,$tWherePdt,$tWhereWah,$SqlWhere,$tWhereProductActive,$nLngID,$tBchCode,$tWahCode,$tPdtCode,$tMmtPdtActive,$tMmtMonth,$tMmtYear);
        unset($tSQL,$oQuery,$aList);
        unset($paData);
        return $aResult;
    }






}
