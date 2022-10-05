<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productaliveatpurchase_model extends CI_Model
{
    public function FSoMMONGetData($paData)
    {
        $nLngID             = $paData['FNLngID'];
        $aAdvanceSearch     = $paData['aAdvanceSearch'];
        $tAgnCode           = $aAdvanceSearch['tAgnCode'];
        $tBchCode           = $aAdvanceSearch['tBchCode'];
        $tWahCode           = $aAdvanceSearch['tWahCode'];
        $tPdtCode           = $aAdvanceSearch['tPdtCode'];
        $tGrpProductCode    = $aAdvanceSearch['tGrpProductCode'];
        $tProducBrandCode   = $aAdvanceSearch['tProducBrandCode'];
        $tProducModelCode   = $aAdvanceSearch['tProducModelCode'];
        $tProductTypeCode   = $aAdvanceSearch['tProductTypeCode'];
        $nStaOrder          = $aAdvanceSearch['nStaOrder'];

        try{
            $tSQL = "SELECT BCHL.FTBchName, 
                        SPW.FTPdtCode, 
                        PDTL.FTPdtName,
                        GRP.FTPgpName,
                        BDL.FTPbnName,
                        MOL.FTPmoName,
                        SPW.FTWahCode,
                        WH.FTWahName,
                        ISNULL(BAL.FCStkQty,0) AS FCStkQty,
                        SPW.FCPdtLeadTime,
                        SPW.FCPdtDailyUseAvg,
                        SPW.FCPdtMin,
                        SPW.FCPdtMax,
                        SPW.FCPdtQtyOrdBuy,
                        SPW.FCPdtQtySugges,
                        SPW.FTPdtStaOrder,
                        SPW.FCPdtPerSLA
                    FROM TCNMPdtSpcWah SPW
                            LEFT JOIN TCNMWaHouse_L WH ON SPW.FTBchCode = WH.FTBchCode AND SPW.FTWahCode = WH.FTWahCode AND WH.FNLngID = $nLngID
                            LEFT JOIN TCNMBranch BCH    ON SPW.FTBchCode = BCH.FTBchCode
                            LEFT JOIN TCNMBranch_L BCHL ON BCH.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                            LEFT JOIN TCNTPdtStkBal BAL ON SPW.FTWahCode = BAL.FTWahCode AND SPW.FTBchCode = BAL.FTBchCode AND SPW.FTPdtCode = BAL.FTPdtCode
                            INNER JOIN TCNMPdt PDT ON SPW.FTPdtCode = PDT.FTPdtCode
                            INNER JOIN TCNMPdt_L PDTL ON SPW.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtGrp_L GRP ON PDT.FTPgpChain = GRP.FTPgpChain AND GRP.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtBrand_L BDL ON PDT.FTPbnCode = BDL.FTPbnCode AND BDL.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtModel_L MOL ON PDT.FTPmoCode = MOL.FTPmoCode AND MOL.FNLngID = $nLngID
                    WHERE 1=1 ";
            // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            if ($this->session->userdata('tSesUsrLevel') != "HQ") { 
                $tSessBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
                $tSQL .= "
                    AND SPW.FTBchCode IN ($tSessBchCode)
                ";
            }

            //สั่งซื้อ
            if($nStaOrder == 0){

            }else if($nStaOrder == 1){
                $tSQL .= "AND SPW.FTPdtStaOrder = 1 ";
            }else if($nStaOrder == 2){
                $tSQL .= "AND SPW.FTPdtStaOrder != 1 ";
            }

            //ตัวแทนขาย
            if (!empty($tAgnCode) && $tAgnCode != '') {
                $tSQL .= "AND BCH.FTAgnCode = '$tAgnCode'";
            }

            //สาขา
            if (!empty($tBchCode) && $tBchCode != '') {
                $tSQL .= "AND SPW.FTBchCode = '$tBchCode'";
            }

            //คลังสินค้า
            if (!empty($tWahCode) && $tWahCode != '') {
                $tSQL .= "AND SPW.FTWahCode = '$tWahCode'";
            }

            //รหัสสินค้า
            if (!empty($tPdtCode) && $tPdtCode != '') {
                $tSQL .= "AND SPW.FTPdtCode = '$tPdtCode'";
            }

            //กลุ่มสินค้า
            if (!empty($tGrpProductCode) && $tGrpProductCode != '') {
                $tSQL .= "AND PDT.FTPgpChain = '$tGrpProductCode'";
            }

            //แบรนด์สินค้า
            if (!empty($tProducBrandCode) && $tProducBrandCode != '') {
                $tSQL .= "AND PDT.FTPbnCode = '$tProducBrandCode'";
            }

            //โมเดลสินค้า
            if (!empty($tProducModelCode) && $tProducModelCode != '') {
                $tSQL .= "AND PDT.FTPmoCode = '$tProducModelCode'";
            }

            //ประเภทสินค้า
            if (!empty($tProductTypeCode) && $tProductTypeCode != '') {
                $tSQL .= "AND PDT.FTPtyCode = '$tProductTypeCode'";
            }

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->result_array();
                $aResult = array(
                    'aItems'   => $aDetail,
                    'tCode'    => '1',
                    'tDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'tCode' => '800',
                    'tDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoMMONGetSplBuyList($paData)
    {
        $tPdtCode = $paData['tPdtCode'];
        $aPdtCode = $paData['aPdtCode'];

        if ($tPdtCode != '') {
            $tWhere = "WHERE PD.FTPdtCode = '$tPdtCode'";
        }elseif ($aPdtCode != '') {
            $tWhere = "WHERE PD.FTPdtCode IN ($aPdtCode)";
        }else{
            $tWhere = "";
        }

        $nLngID   = $paData['FNLngID'];
        try{
            $tSQL = "SELECT
                        PDT.FTPdtCode,
                        PDTL.FTPdtName,
                        SPL.FTBarCode,
                        PSPL.FTSplName,
                        SPLC.FTCtrName,
                        SPLC.FTCtrTel
                    FROM
                        (   
                            SELECT 
                                DISTINCT PD.FTPdtCode
                            FROM TCNMPdt PD
                            $tWhere
                        ) PDT
                        LEFT JOIN TCNMPdt_L PDTL ON PDT.FTPdtCode = PDTL.FTPdtCode
	                    AND PDTL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtSpl SPL ON PDT.FTPdtCode = SPL.FTPdtCode
                        LEFT JOIN TCNMSpl_L PSPL ON SPL.FTSplCode = PSPL.FTSplCode 
                        AND PSPL.FNLngID = $nLngID
                        LEFT JOIN TCNMSplContact_L SPLC ON PSPL.FTSplCode = SPLC.FTSplCode 
                        AND SPLC.FNLngID = $nLngID
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->result_array();
                $aResult = array(
                    'aItems'   => $aDetail,
                    'tCode'    => '1',
                    'tDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'tCode' => '800',
                    'tDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoMMONGetDataAlwPo($paData)
    {
        $tPdtCode = $paData['tPdtCode'];
        $aPdtCode = $paData['aPdtCode'];

        if ($tPdtCode != '') {
            $tWhere = "WHERE PD.FTPdtCode = '$tPdtCode'";
        }elseif ($aPdtCode != '') {
            $tWhere = "WHERE PD.FTPdtCode IN ($aPdtCode)";
        }else{
            $tWhere = "";
        }

        $nLngID   = $paData['FNLngID'];
        try{
            $tSQL = "SELECT
                        UNL.FTPunName,
                        Pack.FTPdtStaAlwPoHQ,
                        Pack.FTPdtStaAlwPoSPL
                    FROM
                        (   
                            SELECT 
                                DISTINCT PD.FTPdtCode
                            FROM TCNMPdt PD
                            $tWhere
                        ) PDT
                        LEFT JOIN TCNMPdtPackSize Pack ON PDT.FTPdtCode = Pack.FTPdtCode
                        LEFT JOIN TCNMPdtUnit_L UNL ON Pack.FTPunCode = UNL.FTPunCode AND UNL.FNLngID = $nLngID
                    ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->result_array();
                $aResult = array(
                    'aItems'   => $aDetail,
                    'tCode'    => '1',
                    'tDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'tCode' => '800',
                    'tDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

}