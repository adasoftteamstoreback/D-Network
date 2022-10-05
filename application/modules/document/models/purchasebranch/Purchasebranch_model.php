<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasebranch_model extends CI_Model {

    // ดึงข้อมูลมาแสดงบนตาราางหน้า List
    public function FSaMPRBGetDataTableList($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search

        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "   SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC ,FTXphDocNo DESC ) AS FNRowID,* FROM
                                (   SELECT DISTINCT
                                        HD.FTBchCode,
                                        HD.FTAgnCode,
                                        BCHL.FTBchName,
                                        HD.FTXphDocNo,
                                        CONVERT(CHAR(10),HD.FDXphDocDate,103) AS FDXphDocDate,
                                        CONVERT(CHAR(5), HD.FDXphDocDate,108) AS FTXshDocTime,
                                        PRBREF.FTXshRefDocNo AS FTXphRefInt,
                                        CONVERT(CHAR(10),PRBREF.FDXshRefDocDate,103) AS FDXphRefIntDate,
                                        CONVERT(CHAR(5), PRBREF.FDXshRefDocDate,108) AS FDXphRefIntTime,
                                        HD.FTXphStaDoc,
                                        HD.FTXphStaApv,
                                        HD.FNXphStaRef,
                                        HD.FTCreateBy,
                                        HD.FDCreateOn,
                                        HD.FNXphStaDocAct,
                                        USRL.FTUsrName      AS FTCreateByName,
                                        HD.FTXphApvCode,
                                        USRLAPV.FTUsrName   AS FTXshApvName
                                    FROM TCNTPdtReqHqHD    HD    WITH (NOLOCK)
                                    LEFT JOIN TCNMBranch_L  BCHL    WITH (NOLOCK) ON HD.FTBchCode     = BCHL.FTBchCode    AND BCHL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L    USRL    WITH (NOLOCK) ON HD.FTCreateBy    = USRL.FTUsrCode    AND USRL.FNLngID    = $nLngID
                                    LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON HD.FTXphApvCode  = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                                    LEFT JOIN TCNTPdtReqHqHDDocRef    PRBREF WITH (NOLOCK) ON PRBREF.FTXshDocNo  = HD.FTXphDocNo AND PRBREF.FTXshRefType = 1
                                WHERE 1=1
        ";

        // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND HD.FTBchCode IN ($tBchCode)
            ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND HD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((HD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }

        // ค้นหาสถานะเอกสาร
        if(isset($tSearchStaDoc) && !empty($tSearchStaDoc)){
            if ($tSearchStaDoc == 3) {
                $tSQL .= " AND HD.FTXphStaDoc = '$tSearchStaDoc'";
            } elseif ($tSearchStaDoc == 2) {
                $tSQL .= " AND ISNULL(HD.FTXphStaApv,'') = '' AND HD.FTXphStaDoc != '3'";
            } elseif ($tSearchStaDoc == 1) {
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaDoc'";
            }
        }

        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaApprove' OR HD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXphStaDocAct = 0";
            }
        }

        $tSQL   .=  ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aDataCountAllRow   = $this->FSnMPRBCountPageDocListAll($paDataCondition);
            $nFoundRow          = ($aDataCountAllRow['rtCode'] == '1')? $aDataCountAllRow['rtCountData'] : 0;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Paginations
    public function FSnMPRBCountPageDocListAll($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $aDatSessionUserLogIn   = $paDataCondition['aDatSessionUserLogIn'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tSearchList        = $aAdvanceSearch['tSearchAll'];
        $tSearchBchCodeFrom = $aAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $aAdvanceSearch['tSearchBchCodeTo'];
        $tSearchDocDateFrom = $aAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $aAdvanceSearch['tSearchDocDateTo'];
        $tSearchStaDoc      = $aAdvanceSearch['tSearchStaDoc'];
        $tSearchStaDocAct   = $aAdvanceSearch['tSearchStaDocAct'];

        $tSQL   =   "   SELECT COUNT (HD.FTXphDocNo) AS counts
                        FROM TCNTPdtReqHqHD HD WITH (NOLOCK)
                        LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON HD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        WHERE 1=1
                    ";

        if ($this->session->userdata('tSesUsrLevel') != "HQ") {
            $tBchCode = $this->session->userdata('tSesUsrBchCodeMulti');
            $tSQL .= "
                AND HD.FTBchCode IN ($tBchCode)
            ";
        }

        // Check User Login Branch
        if(isset($aDatSessionUserLogIn['FTBchCode']) && !empty($aDatSessionUserLogIn['FTBchCode'])){
            $tUserLoginBchCode  = $aDatSessionUserLogIn['FTBchCode'];
            $tSQL   .= " AND HD.FTBchCode = '$tUserLoginBchCode' ";
        }

        // Check User Login Shop
        if(isset($aDatSessionUserLogIn['FTShpCode']) && !empty($aDatSessionUserLogIn['FTShpCode'])){
            $tUserLoginShpCode  = $aDatSessionUserLogIn['FTShpCode'];
            $tSQL   .= " AND HD.FTShpCode = '$tUserLoginShpCode' ";
        }

        // นหารหัสเอกสาร,ชือสาขา,วันที่เอกสาร
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL .= " AND ((HD.FTXphDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),HD.FDXphDocDate,103) LIKE '%$tSearchList%'))";
        }

        // ค้นหาจากสาขา - ถึงสาขา
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeTo)){
            $tSQL .= " AND ((HD.FTBchCode BETWEEN '$tSearchBchCodeFrom' AND '$tSearchBchCodeTo') OR (HD.FTBchCode BETWEEN '$tSearchBchCodeTo' AND '$tSearchBchCodeFrom'))";
        }

        // ค้นหาจากวันที่ - ถึงวันที่
        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (HD.FDXphDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 23:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 00:00:00')))";
        }
        // ค้นหาสถานะอนุมัติ
        if(isset($tSearchStaApprove) && !empty($tSearchStaApprove)){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaApprove' OR HD.FTXphStaApv = '' ";
            }else{
                $tSQL .= " AND HD.FTXphStaApv = '$tSearchStaApprove'";
            }
        }

        // ค้นหาสถานะเคลื่อนไหว
        $tSearchStaDocAct = $aAdvanceSearch['tSearchStaDocAct'];
        if (!empty($tSearchStaDocAct) && ($tSearchStaDocAct != "0")) {
            if ($tSearchStaDocAct == 1) {
                $tSQL .= " AND HD.FNXphStaDocAct = 1";
            } else {
                $tSQL .= " AND HD.FNXphStaDocAct = 0";
            }
        }

        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    public function FSaMPRBGetDetailUserBranch($paBchCode){
        if(!empty($paBchCode)){
        $aReustl = $this->db->where('FTBchCode',$paBchCode)->get('TCNMBranch')->row_array();
        //   $oQuery = $this->db->query($oSql);
        //   $aReustl =  $oQuery->row_array();
        $aReulst['item'] = $aReustl;
        $aReulst['code'] = 1;
        $aReulst['msg'] = 'Success !';
        }else{
        $aReulst['code'] = 2;
        $aReulst['msg'] = 'Error !';
        }
    return $aReulst;
    }

    public function FSaMPRBGetCheckPdtWah($pPdtCode){
      $tSQL = "SELECT
      PSW.FCPdtQtyOrdBuy,
      PSW.FCPdtQtySugges,
      PSB.FCStkQty
      FROM TCNMPdtSpcWah PSW
      LEFT JOIN TCNTPdtStkBal PSB ON PSW.FTPdtCode = PSB.FTPdtCode  WHERE PSW.FTPdtCode='$pPdtCode'";
      $oQuery = $this->db->query($tSQL);
      if($oQuery->num_rows() > 0){
          $oDataList          = $oQuery->result_array();
          $aResult = array(
              'raItems'       => $oDataList,
              'rtCode'        => '1',
              'rtDesc'        => 'success',
          );
      }else{
          $aResult = array(
              'rtCode'        => '800',
              'rtDesc'        => 'Data Not Found',
          );
      }
      return $aResult;
    }
    
    public function FSaMPRBGetCheckPdtWahAuto($pnLangEdit,$paWhere)
    {
      $tBchCode = $paWhere['tBchCode'];
      $tWahCode = $paWhere['tWahCode'];
      $tSuggesType = $paWhere['tSuggesType'];

      $tSQL = "SELECT
      PSW.FTPdtCode,
      PSW.FTBchCode,
      PSW.FTWahCode,
      PSW.FCPdtQtyOrdBuy,
      PSW.FCPdtQtySugges,
      PSW.FTPdtStaOrder,
      PSB.FCStkQty,
      BAR.FTBarCode,
      UNTL.FTPunName,
      PDTL.FTPdtName,
      PKS.FCPdtUnitFact,
      PKS.FTPunCode
      FROM TCNMPdtSpcWah PSW
      LEFT OUTER JOIN TCNTPdtStkBal PSB ON PSW.FTPdtCode = PSB.FTPdtCode  AND PSW.FTBchCode= PSB.FTBchCode AND PSW.FTWahCode = PSB.FTWahCode
      LEFT JOIN TCNMPdtPackSize PKS ON PSW.FTPdtCode = PKS.FTPdtCode 
    --   AND PKS.FCPdtUnitFact = '1'
      LEFT JOIN TCNMPdtBar BAR ON PKS.FTPdtCode = BAR.FTPdtCode  AND BAR.FTPunCode = PKS.FTPunCode
      LEFT JOIN TCNMPdtUnit_L UNTL WITH (NOLOCK) ON UNTL.FTPunCode = PKS.FTPunCode AND UNTL.FNLngID = '$pnLangEdit'
      LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK) ON PSW.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = '$pnLangEdit'
    --   LEFT OUTER JOIN TCNMPdt PDT ON PSW.FTPdtCode = PDT.FTPdtCode  AND PSW.FTBchCode= PDT.FTBchCode AND PSW.FTWahCode = PDT.FTWahCode
    -- TCNMPdtBar
    -- TCNMPdtPackSize
      WHERE PSW.FTPdtStaOrder='1' 
      AND PSW.FTBchCode = '$tBchCode' 
      AND PSW.FTWahCode = '$tWahCode' 
      AND PKS.FTPdtStaAlwPoHQ = '1'
      ORDER BY PSW.FTPdtCode,PKS.FCPdtUnitFact DESC ";
      $oQuery = $this->db->query($tSQL);
      $oDatatest          = $oQuery->result_array();
      if($oQuery->num_rows() > 0){
          $oDataList          = $oQuery->result_array();

          $tOldPdtCode = '';
          $remail = '';

          foreach($oDataList as $nKeyt => $aValuet){
            if($aValuet['FTPdtCode'] != $tOldPdtCode){
                $rowspan = 1;
                $tSuggerT = $aValuet['FCPdtQtySugges'] / $aValuet['FCPdtUnitFact'];
                if($aValuet['FCPdtQtySugges'] % $aValuet['FCPdtUnitFact'] != 0){
                    $remail = $aValuet['FCPdtQtySugges'] % $aValuet['FCPdtUnitFact'];
                }else{
                    $remail = 0;
                }
            }else{
                $tSuggerT = $remail / $aValuet['FCPdtUnitFact'];
                if($remail % $aValuet['FCPdtUnitFact'] != 0 && $remail != 0){
                    $remail = $aValuet['FCPdtQtySugges'] % $aValuet['FCPdtUnitFact'];
                }else{
                    $remail = 0;
                }
            }
            // FCPdtQtyOrdBuy
            $oDataList[$nKeyt]['FCPdtQtySugges'] = $aValuet['FCPdtQtySugges'];
            if($tSuggesType == '1'){
                $oDataList[$nKeyt]['Sugges'] = ceil($tSuggerT);
                $remail = 0;
            }elseif($tSuggesType == '2'){
                $oDataList[$nKeyt]['Sugges'] = floor($tSuggerT);
            }else{
                if(round($tSuggerT) > $tSuggerT || $remail == 0){
                    $remail = 0;
                }
                $oDataList[$nKeyt]['Sugges'] = round($tSuggerT);
            }
            $oDataList[$nKeyt]['remail'] = $remail;
            $oDataList[$nKeyt]['rowspan'] = $rowspan++;
            $tOldPdtCode = $aValuet['FTPdtCode'];
          }

          ////////////////////////////////////////////////////////////////////
          foreach($oDataList as $nkey => $aValue){
          $aDetail = array(
            "SHP" => null,
            "BCH" => null,
            "PDTCode" => $aValue['FTPdtCode'],
            "PDTName" => $aValue['FTPdtName'],
            "PUNCode" => $aValue['FTPunCode'],
            "Barcode" => $aValue['FTBarCode'],
            "PUNName" => $aValue['FTPunName'],
            "IMAGE" => null,
            "Price" => 0,
            "nCostSTD" => ".0000",
            "LOCSEQ" => "",
            "AlwDis" => "1",
            "AlwVat" => "1",
            "tVatCode" => "00001",
            "nVat" => "7.0000",
            "NetAfHD" => 0,
            "PDTSpc" => "-",
            "nStaLot" => "2",
            "tTypePDT" => "2",
            "SetOrSN" => "1",
            "Sugges" =>$aValue['Sugges'],
            "FCStkQty" =>$aValue['FCStkQty'],
            "rowspan" =>$aValue['rowspan'],
            "FCPdtQtyOrdBuy" =>$aValue['FCPdtQtyOrdBuy'],
            "FCXtdVatable" =>$aValuet['FCPdtQtyOrdBuy'],
            "Factor" =>$aValue['FCPdtUnitFact'],
          );


          $aAutoPDT[] = array(
            "pnPdtCode" => $aValue['FTPdtCode'],
            "ptPunCode" => $aValue['FTPunCode'],
            "ptBarCode" => $aValue['FTBarCode'],
            "packData"  => $aDetail
          );
        }
        ///////////////////////////////////////////////////////////////////////////////////////

          $aResult = array(
              'raItems'       => $oDataList,
              'rtCode'        => '1',
              'rtDesc'        => 'success',
              'raAutoPDT'     => $aAutoPDT,
          );
      }else{
          $aResult = array(
              'rtCode'        => '800',
              'rtDesc'        => 'Data Not Found',
          );
      }
      return $aResult;
    }

    public function FSaMPRBGetCheckRentPdtWahAuto($paWhere)
    {
    $tBchCode = $paWhere['tBchCode'];
    $tWhereHQ = '';
    $tWhereBCH = '';
    if ($this->session->userdata('tSesUsrLevel') != "HQ") {
        $tWhereHQ .= "
            AND FTBchCode = '$tBchCode'
        ";
        $tWhereBCH .= "FTBchCode,";
        $tSQL = "SELECT SUM(QTY) AS QTY,FTPdtCode,Barcode,FTPunCode,FTPunName,PDTName,FTBchCode,SUM(QTYALL) AS QTYALL  FROM (";
    }else{
        $tSQL = "SELECT SUM(QTY) AS QTY,FTPdtCode,Barcode,FTPunCode,FTPunName,PDTName,SUM(QTYALL) AS QTYALL  FROM (";
    }


    $tSQL .= "SELECT SUM(FCXtdQty) AS QTY,FTPdtCode,FTXtdPdtName AS PDTName,FTXtdBarCode AS Barcode,FTPunCode,FTPunName,TDT.FTBchCode,SUM(TDT.FCXtdQtyAll) AS QTYALL 
        FROM TCNTPdtTwxHD THD
        LEFT JOIN TCNTPdtTwxDT TDT WITH (NOLOCK) ON TDT.FTXthDocNo = THD.FTXthDocNo 
        WHERE THD.FTXthStaDoc='1' AND ( TDT.FTXtdStaPrcStk = '' OR TDT.FTXtdStaPrcStk IS NULL)
        GROUP BY FTPdtCode,FTXtdPdtName,FTXtdBarCode,FTPunCode,FTPunName,TDT.FTBchCode
        
        UNION ALL 

        SELECT SUM(BDT.FCXsdQty) AS QTY,FTPdtCode,FTXsdPdtName AS PDTName,FTXsdBarCode AS Barcode,FTPunCode,FTPunName,BDT.FTBchCode,SUM(BDT.FCXsdQtyAll) AS QTYALL 
        FROM TSVTBookHD BHD 
        LEFT JOIN TSVTBookDT BDT WITH (NOLOCK) ON BDT.FTXshDocNo = BHD.FTXshDocNo 
        WHERE BHD.FTXshStaDoc='1' 
        AND ( BHD.FTXshStaPrcDoc = '1'  OR ISNULL(BHD.FTXshStaPrcDoc,'') = '' )
        AND ( BDT.FTXsdStaPrcStk = '' OR BDT.FTXsdStaPrcStk IS NULL OR BDT.FTXsdStaPrcStk = '2')
        GROUP BY FTPdtCode,FTXsdPdtName,FTXsdBarCode,FTPunCode,FTPunName,BDT.FTBchCode  

        UNION ALL
        SELECT SUM
            ( BDTS.FCXsdQtySet ) AS QTY,
            BDTS.FTPdtCode,
            FTXsdPdtName AS PDTName,
            BAR.FTBarCode AS Barcode,
            BDTS.FTPunCode,
            PUNL.FTPunName,
            BDTS.FTBchCode,
			SUM(BDTS.FCXsdQtySet) AS QTYALL 
        FROM
            TSVTBookHD BHD
            LEFT JOIN TSVTBookDTSet BDTS WITH ( NOLOCK ) ON BDTS.FTXshDocNo = BHD.FTXshDocNo 
            LEFT JOIN TCNMPdtUnit_L     PUNL    ON BDTS.FTPunCode 	= PUNL.FTPunCode	AND PUNL.FNLngID = '1'
            LEFT JOIN TCNMPdtPackSize   PACK    ON BDTS.FTPdtCode 	= PACK.FTPdtCode	AND PACK.FCPdtUnitFact = '1'
            LEFT JOIN TCNMPdtBar   			BAR    	ON BDTS.FTPdtCode 	= BAR.FTPdtCode	AND PACK.FTPunCode = BAR.FTPunCode
        WHERE
            BHD.FTXshStaDoc= '1' 
            AND ( BHD.FTXshStaPrcDoc = '1'   OR ISNULL(BHD.FTXshStaPrcDoc,'') = '' )
            AND ( BDTS.FTXsdStaPrcStk = '' OR BDTS.FTXsdStaPrcStk IS NULL OR BDTS.FTXsdStaPrcStk = '2' ) 
        GROUP BY
            BDTS.FTPdtCode,
            FTXsdPdtName,
            BAR.FTBarCode,
            BDTS.FTPunCode,
            PUNL.FTPunName,
            BDTS.FTBchCode

    ) AS A WHERE FTPdtCode <> '' $tWhereHQ GROUP BY FTPdtCode,Barcode,FTPunCode,FTPunName, $tWhereBCH PDTName";
    
      $oQuery = $this->db->query($tSQL);

      
      if($oQuery->num_rows() > 0){
          $oDataList          = $oQuery->result_array();
          ////////////////////////////////////////////////////////////////////Qty
          foreach($oDataList as $nkey => $aValue){
          $aDetail = array(
            "SHP" => null,
            "BCH" => null,
            "PDTCode" => $aValue['FTPdtCode'],
            "PDTName" => $aValue['PDTName'],
            "PUNCode" => $aValue['FTPunCode'],
            "Barcode" => $aValue['Barcode'],
            "PUNName" => $aValue['FTPunName'],
            "IMAGE" => null,
            "Price" => 0,
            "nCostSTD" => ".0000",
            "LOCSEQ" => "",
            "AlwDis" => "1",
            "tVatCode" => "00001",
            "nVat" => "7.0000",
            "NetAfHD" => 0,
            "NetAfHD" => 0,
            "PDTSpc" => "-",
            "nStaLot" => "2",
            "Sugges2" =>$aValue['QTYALL'],
            "tTypePDT" => "2",
            "SetOrSN" => "1"
          );


          $aAutoPDT[] = array(
            "pnPdtCode" => $aValue['FTPdtCode'],
            "ptPunCode" => $aValue['FTPunCode'],
            "ptBarCode" => $aValue['Barcode'],
            "ptQTY"     => $aValue['QTY'],
            "packData"  => $aDetail
          );
        }
        ///////////////////////////////////////////////////////////////////////////////////////

          $aResult = array(
              'raItems'       => $oDataList,
              'rtCode'        => '1',
              'rtDesc'        => 'success',
              'raAutoPDT'     => $aAutoPDT,
          );
      }else{
          $aResult = array(
              'rtCode'        => '800',
              'rtDesc'        => 'Data Not Found',
          );
      }
    //   print_r($aResult);
    //   exit();
      return $aResult;
    }


    public function FSaMPRBCheckSuggestProduct($pnLangEdit,$paWhere)
    {  
      $tPdoCode = $paWhere['aProduct'][0]['pnPdtCode'];
      $tBarCode = $paWhere['aProduct'][0]['ptBarCode'];
      $tProduct = $paWhere['aProduct'][0];
      $tBchCode = $paWhere['tBchCode'];
      $tWahCode = $paWhere['tWahCode'];
      $tSuggesType = $paWhere['tSuggesType'];
      $tSQL = "SELECT 
      PSW.FTPdtCode,
      PSW.FCPdtQtyOrdBuy,
      PSW.FCPdtQtySugges,
      PSW.FTPdtStaOrder,
      PKS.FCPdtUnitFact,
      PSB.FCStkQty,
      BAR.FTBarCode
    FROM TCNMPdtSpcWah PSW WITH(NOLOCK)
    LEFT JOIN TCNMPdtPackSize PKS ON PSW.FTPdtCode = PKS.FTPdtCode 
    LEFT OUTER JOIN TCNTPdtStkBal PSB ON PSW.FTPdtCode = PSB.FTPdtCode  AND PSW.FTBchCode= PSB.FTBchCode AND PSW.FTWahCode = PSB.FTWahCode
    LEFT JOIN TCNMPdtBar BAR ON PKS.FTPdtCode = BAR.FTPdtCode  AND BAR.FTPunCode = PKS.FTPunCode
      WHERE PSW.FTPdtCode ='$tPdoCode'
      AND PSW.FTBchCode = '$tBchCode' AND PSW.FTWahCode = '$tWahCode' AND BAR.FTBarCode = '$tBarCode'
      ORDER BY PSW.FTPdtCode,PKS.FCPdtUnitFact DESC ";
      $oQuery = $this->db->query($tSQL);
      $oDatatest          = $oQuery->result_array();
      if($oQuery->num_rows() > 0){
          $oDataList          = $oQuery->result_array();

          foreach($oDataList as $nKeyt => $aValuet){
            $rowspan = 1;
            $tSuggerT = $aValuet['FCPdtQtyOrdBuy'] / $aValuet['FCPdtUnitFact'];
            $tProduct['packData']['rowspan'] = $rowspan;
            $tProduct['packData']['FCStkQty'] = $aValuet['FCStkQty'];
            if($tSuggesType == '1'){
                $tProduct['packData']['Sugges'] = ceil($tSuggerT);
            }elseif($tSuggesType == '2'){
                $tProduct['packData']['Sugges'] = floor($tSuggerT);
            }else{
                $tProduct['packData']['Sugges'] = round($tSuggerT);
            }
            $tProduct['packData']['FCPdtQtyOrdBuy'] = $aValuet['FCPdtQtyOrdBuy'];
            $tProduct['packData']['FCPdtQtySugges'] = $aValuet['FCPdtQtySugges'];
            $tProduct['packData']['FCXtdVatable']   = $aValuet['FCPdtQtyOrdBuy'];
            $tProduct['packData']['Factor']         = $aValuet['FCPdtUnitFact'];
          }
      }else{
        $tSQLPSB = "SELECT       
        PSB.FCStkQty
        FROM TCNTPdtStkBal PSB
        LEFT JOIN TCNMPdtPackSize PKS ON PSB.FTPdtCode = PKS.FTPdtCode 
        WHERE PSB.FTPdtCode ='$tPdoCode' AND PSB.FTBchCode = '$tBchCode' AND PSB.FTWahCode = '$tWahCode'";
          $oQueryPSB = $this->db->query($tSQLPSB);
        if($oQueryPSB->num_rows() > 0){
            $aPSBSTK          = $oQueryPSB->result_array();
            $tProduct['packData']['FCStkQty'] = $aPSBSTK[0]['FCStkQty'];
        }else{
            $tProduct['packData']['FCStkQty'] = 0;
        }

        $tSQLPSBFAC = "SELECT  
        BAR.FTPdtCode,         
        PKS.FCPdtUnitFact
        FROM TCNMPdtBar BAR
        LEFT JOIN TCNMPdtPackSize PKS ON BAR.FTPdtCode = PKS.FTPdtCode 
        WHERE BAR.FTPdtCode ='$tPdoCode' AND BAR.FTBarCode = '$tBarCode'";
        $oQueryPSBFAC = $this->db->query($tSQLPSBFAC);
        if($oQueryPSBFAC->num_rows() > 0){
            $aPSBFAC          = $oQueryPSBFAC->result_array();
            $tProduct['packData']['Factor'] = $aPSBFAC[0]['FCPdtUnitFact'];
        }else{
            $tProduct['packData']['Factor'] = 0;
        }

        $tProduct['packData']['rowspan'] = 1;
        $tProduct['packData']['Sugges'] = 0;
        $tProduct['packData']['FCPdtQtyOrdBuy'] = 0;
        $tProduct['packData']['FCPdtQtySugges'] = 0;
        $tProduct['packData']['FCXtdVatable'] = 0;
      }

      $aResult = array(
        'raItems'       => $tProduct,
        'rtCode'        => '1',
        'rtDesc'        => 'success',
        );
      return $aResult;
    }

    public function FSaMPRBCheckSuggestProductAddButton($pnLangEdit,$paWhere)
    {  
      $tPdoCode = $paWhere['aProduct']['pnPdtCode'];
      $tBarCode = $paWhere['aProduct']['ptBarCode'];
      $tProduct = $paWhere['aProduct'];
      $tBchCode = $paWhere['tBchCode'];
      $tWahCode = $paWhere['tWahCode'];
      $tSuggesType = $paWhere['tSuggesType'];
      $tSQL = "SELECT 
      PSW.FTPdtCode,
      PSW.FCPdtQtyOrdBuy,
      PSW.FCPdtQtySugges,
      PSW.FTPdtStaOrder,
      PKS.FCPdtUnitFact,
      PSB.FCStkQty,
      BAR.FTBarCode
    FROM TCNMPdtSpcWah PSW WITH(NOLOCK)
    LEFT JOIN TCNMPdtPackSize PKS ON PSW.FTPdtCode = PKS.FTPdtCode 
    LEFT OUTER JOIN TCNTPdtStkBal PSB ON PSW.FTPdtCode = PSB.FTPdtCode  AND PSW.FTBchCode= PSB.FTBchCode AND PSW.FTWahCode = PSB.FTWahCode
    LEFT JOIN TCNMPdtBar BAR ON PKS.FTPdtCode = BAR.FTPdtCode  AND BAR.FTPunCode = PKS.FTPunCode
      WHERE PSW.FTPdtCode ='$tPdoCode'
      AND PSW.FTBchCode = '$tBchCode' AND PSW.FTWahCode = '$tWahCode' AND BAR.FTBarCode = '$tBarCode'
      ORDER BY PSW.FTPdtCode,PKS.FCPdtUnitFact DESC ";
      $oQuery = $this->db->query($tSQL);
      $oDatatest          = $oQuery->result_array();
      if($oQuery->num_rows() > 0){
          $oDataList          = $oQuery->result_array();

          foreach($oDataList as $nKeyt => $aValuet){
            $rowspan = 1;
            $tSuggerT = $aValuet['FCPdtQtySugges'] / $aValuet['FCPdtUnitFact'];
            $tProduct['packData']['rowspan'] = $rowspan;
            $tProduct['packData']['FCStkQty'] = $aValuet['FCStkQty'];
            if($tSuggesType == '1'){
                $tProduct['packData']['Sugges'] = ceil($tSuggerT);
            }elseif($tSuggesType == '2'){
                $tProduct['packData']['Sugges'] = floor($tSuggerT);
            }else{
                $tProduct['packData']['Sugges'] = round($tSuggerT);
            }
            $tProduct['packData']['FCPdtQtyOrdBuy'] = $aValuet['FCPdtQtyOrdBuy'];
            $tProduct['packData']['FCPdtQtySugges'] = $aValuet['FCPdtQtySugges'];
            $tProduct['packData']['FCXtdVatable']   = $aValuet['FCPdtQtyOrdBuy'];
            $tProduct['packData']['Factor']         = $aValuet['FCPdtUnitFact'];
          }
      }else{
        $tSQLPSB = "SELECT       
        PSB.FCStkQty
        FROM TCNTPdtStkBal PSB
        WHERE PSB.FTPdtCode ='$tPdoCode' AND PSB.FTBchCode = '$tBchCode' AND PSB.FTWahCode = '$tWahCode'";
          $oQueryPSB = $this->db->query($tSQLPSB);
        if($oQueryPSB->num_rows() > 0){
            $aPSBSTK          = $oQueryPSB->result_array();
            $tProduct['packData']['FCStkQty'] = $aPSBSTK[0]['FCStkQty'];
        }else{
            $tProduct['packData']['FCStkQty'] = 0;
        }

        $tSQLPSBFAC = "SELECT  
        BAR.FTPdtCode,         
        PKS.FCPdtUnitFact
        FROM TCNMPdtBar BAR
        LEFT JOIN TCNMPdtPackSize PKS ON BAR.FTPdtCode = PKS.FTPdtCode 
        WHERE BAR.FTPdtCode ='$tPdoCode' AND BAR.FTBarCode = '$tBarCode'";
        $oQueryPSBFAC = $this->db->query($tSQLPSBFAC);
        if($oQueryPSBFAC->num_rows() > 0){
            $aPSBFAC          = $oQueryPSBFAC->result_array();
            $tProduct['packData']['Factor'] = $aPSBFAC[0]['FCPdtUnitFact'];
        }else{
            $tProduct['packData']['Factor'] = 0;
        }

        $tProduct['packData']['rowspan'] = 1;
        $tProduct['packData']['Sugges'] = 0;
        $tProduct['packData']['FCPdtQtyOrdBuy'] = 0;
        $tProduct['packData']['FCPdtQtySugges'] = 0;
        $tProduct['packData']['FCXtdVatable'] = 0;
      }

      $aResult = array(
        'raItems'       => $tProduct,
        'rtCode'        => '1',
        'rtDesc'        => 'success',
        );
      return $aResult;
    }

    public function FSaMPRBEditGroupProduct($aDataPdtParams)
    {  
      $tPdtCode = $aDataPdtParams['tPdtCode'];
      $tDocKey = $aDataPdtParams['tDocKey'];
      $tSessionID = $this->session->userdata('tSesSessionID');
      $tSuggesType = $aDataPdtParams['tPRBSuggesType'];
      $bFlag = '1';
      $remail = '';
      $tSQL = "SELECT 
      DOC.FTPdtCode,
      DOC.FCXtdFactor,
      DOC.FTXtdBarCode,
      DOC.FCXtdVatable
        FROM TCNTDocDTTmp DOC WITH(NOLOCK)
      WHERE DOC.FTSessionID='$tSessionID' 
      AND DOC.FTPdtCode ='$tPdtCode'
      AND DOC.FTXthDocKey ='$tDocKey'
      ORDER BY DOC.FCXtdFactor DESC ";
      $oQuery = $this->db->query($tSQL);
      $oDatatest          = $oQuery->result_array();

      foreach($oDatatest as $key => $value){
        if($bFlag == '1'){
        if($value['FCXtdFactor'] == ''){
            $value['FCXtdFactor'] = 1;
        }
        $tSuggerT = $value['FCXtdVatable'] / $value['FCXtdFactor'];
        if($value['FCXtdVatable'] % $value['FCXtdFactor'] != 0){
            $remail = $value['FCXtdVatable'] % $value['FCXtdFactor'];
        }else{
            $remail = 0;
        }
        $bFlag = '2';
        }else{
            $tSuggerT = $remail / $value['FCXtdFactor'];
            if($remail % $value['FCXtdFactor'] != 0 && $remail != 0){
                $remail = $value['FCXtdVatable'] % $value['FCXtdFactor'];
            }else{
                $remail = 0;
            }
        }

        if($tSuggesType == '1'){
            $tSuggerT = ceil($tSuggerT);
            $remail = 0;
        }elseif($tSuggesType == '2'){
            $tSuggerT = floor($tSuggerT);
        }else{
            if(round($tSuggerT) > $tSuggerT || $remail == 0){
                $remail = 0;
            }
            $tSuggerT = round($tSuggerT);
        }
        $oDatatest[$key]['Sugges'] = $tSuggerT;

        $this->db->set('FCXtdSetPrice' , $tSuggerT);
        $this->db->where('FTPdtCode', $value['FTPdtCode']);
        $this->db->where('FTXtdBarCode', $value['FTXtdBarCode']);
        $this->db->where('FTXthDocKey', $tDocKey);
        $this->db->where('FTSessionID', $tSessionID);
        $this->db->update('TCNTDocDTTmp');
      }

      $aResult = array(
        'raItems'       => $oDatatest
        );
      return $aResult;
    }

    // เปิดมาหน้า ADD จะต้อง ลบสินค้าตัวเดิม ใน DTTemp โดย where session
    public function FSaMCENDeletePDTInTmp($paParams){
        $tSessionID = $this->session->userdata('tSesSessionID');
        $this->db->where_in('FTSessionID', $tSessionID);
        $this->db->delete('TCNTDocDTTmp');
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        return $aStatus;
    }

    // Delete Delivery Order Document
    public function FSxMPRBClearDataInDocTemp($paWhereClearTemp){
        $tPRBDocNo       = $paWhereClearTemp['FTXthDocNo'];
        $tPRBDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tPRBSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp
                                WHERE 1=1
                                AND TCNTDocDTTmp.FTXthDocNo     = '$tPRBDocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tPRBDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tPRBSessionID'
        ";
        $this->db->query($tClearDocTemp);


        // Query Delete Doc HD Discount Temp
        $tClearDocHDDisTemp =   "   DELETE FROM TCNTDocHDDisTmp
                                    WHERE 1=1
                                    AND TCNTDocHDDisTmp.FTXthDocNo  = '$tPRBDocNo'
                                    AND TCNTDocHDDisTmp.FTSessionID = '$tPRBSessionID'
        ";
        $this->db->query($tClearDocHDDisTemp);

        // Query Delete Doc DT Discount Temp
        $tClearDocDTDisTemp =   "   DELETE FROM TCNTDocDTDisTmp
                                    WHERE 1=1
                                    AND TCNTDocDTDisTmp.FTXthDocNo  = '$tPRBDocNo'
                                    AND TCNTDocDTDisTmp.FTSessionID = '$tPRBSessionID'
        ";
        $this->db->query($tClearDocDTDisTemp);

    }

    // Functionality : Delete Delivery Order Document
    public function FSxMPRBClearDataInDocTempForImp($paWhereClearTemp){
        $tPRBDocNo       = $paWhereClearTemp['FTXphDocNo'];
        $tPRBDocKey      = $paWhereClearTemp['FTXthDocKey'];
        $tPRBSessionID   = $paWhereClearTemp['FTSessionID'];

        // Query Delete DocTemp
        $tClearDocTemp  =   "   DELETE FROM TCNTDocDTTmp
                                WHERE 1=1
                                AND TCNTDocDTTmp.FTXphDocNo     = '$tPRBDocNo'
                                AND TCNTDocDTTmp.FTXthDocKey    = '$tPRBDocKey'
                                AND TCNTDocDTTmp.FTSessionID    = '$tPRBSessionID'
                                AND TCNTDocDTTmp.FTSrnCode <> 1
        ";
        $this->db->query($tClearDocTemp);
    }

    // Function: Get ShopCode From User Login
    public function FSaMPRBGetShpCodeForUsrLogin($paDataShp){
        $nLngID     = $paDataShp['FNLngID'];
        $tUsrLogin  = $paDataShp['tUsrLogin'];
        $tSQL       = " SELECT
                            UGP.FTBchCode,
                            BCHL.FTBchName,
                            MER.FTMerCode,
                            MERL.FTMerName,
                            UGP.FTShpCode,
                            SHPL.FTShpName,
                            SHP.FTShpType,
                            SHP.FTWahCode   AS FTWahCode,
                            WAHL.FTWahName  AS FTWahName
                        FROM TCNTUsrGroup           UGP     WITH (NOLOCK)
                        LEFT JOIN TCNMBranch        BCH     WITH (NOLOCK) ON UGP.FTBchCode = BCH.FTBchCode
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK) ON UGP.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop          SHP     WITH (NOLOCK) ON UGP.FTShpCode = SHP.FTShpCode
                        LEFT JOIN TCNMShop_L        SHPL    WITH (NOLOCK) ON SHP.FTShpCode = SHPL.FTShpCode AND SHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerchant		MER		WITH (NOLOCK)	ON SHP.FTMerCode	= MER.FTMerCode
                        LEFT JOIN TCNMMerchant_L    MERL    WITH (NOLOCK) ON SHP.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAHL    WITH (NOLOCK) ON SHP.FTWahCode = WAHL.FTWahCode
                        WHERE UGP.FTUsrCode = '$tUsrLogin' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult    = $oQuery->row_array();
        }else{
            $aResult    = "";
        }
        unset($oQuery);
        return $aResult;
    }

    // Get Data Config WareHouse TSysConfig
    public function FSaMPRBGetDefOptionConfigWah($paConfigSys){
        $tSysCode       = $paConfigSys['FTSysCode'];
        $nSysSeq        = $paConfigSys['FTSysSeq'];
        $nLngID         = $paConfigSys['FNLngID'];
        $aDataReturn    = array();

        $tSQLUsrVal = " SELECT
                            SYSCON.FTSysStaUsrValue AS FTSysWahCode,
                            WAHL.FTWahName          AS FTSysWahName
                        FROM TSysConfig SYSCON          WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaUsrValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                        LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
                        WHERE 1=1
                        AND SYSCON.FTSysCode    = '$tSysCode'
                        AND SYSCON.FTSysSeq     = $nSysSeq
        ";
        $oQuery1    = $this->db->query($tSQLUsrVal);
        if($oQuery1->num_rows() > 0){
            $aDataReturn    = $oQuery1->row_array();
        }else{
            $tSQLUsrDef =   "   SELECT
                                    SYSCON.FTSysStaDefValue AS FTSysWahCode,
                                    WAHL.FTWahName          AS FTSysWahName
                        FROM TSysConfig SYSCON          WITH(NOLOCK)
                        LEFT JOIN TCNMWaHouse   WAH     WITH(NOLOCK)    ON SYSCON.FTSysStaDefValue  = WAH.FTWahCode     AND WAH.FTWahStaType = 1
                        LEFT JOIN TCNMWaHouse_L WAHL    WITH(NOLOCK)    ON WAH.FTWahCode            = WAHL.FTWahCode    AND WAHL.FNLngID = $nLngID
                        WHERE 1=1
                        AND SYSCON.FTSysCode    = '$tSysCode'
                        AND SYSCON.FTSysSeq     = $nSysSeq
            ";
            $oQuery2    = $this->db->query($tSQLUsrDef);
            if($oQuery2->num_rows() > 0){
                $aDataReturn    = $oQuery2->row_array();
            }
        }
        unset($oQuery1);
        unset($oQuery2);
        return $aDataReturn;
    }

        // Get Data Config WareHouse TSysConfig 2 
        public function FSaMPRBGetDefOptionConfigWahouse($paConfigSys){
            $tSysCode       = $paConfigSys['FTSysCode'];
            $nSysSeq        = $paConfigSys['FTSysSeq'];
            $nLngID         = $paConfigSys['FNLngID'];
            $FTAgnCode         = $paConfigSys['FTAgnCode'];
            $FTBchCode         = $paConfigSys['FTBchCode'];
            $aDataReturn    = array();
    
            $tSQLUsrVal = " SELECT 
                        CASE WHEN  ISNULL(FTCfgStaUsrValue,'') <> '' THEN FTCfgStaUsrValue
                            WHEN  ISNULL(FTCNUsrVal,'') <> ''  THEN FTCNUsrVal
                        ELSE   FTCNDefVal
                        END AS FTWahCode
                        FROM
                        (
                            SELECT FTSysCode, 
                                FTSysApp, 
                                FTSysStaDefValue AS FTCNDefVal, -- P3
                                FTSysStaUsrValue AS FTCNUsrVal --P2
                            FROM TSysConfig
                            WHERE FTSysCode = 'tPS_Warehouse'
                                AND FTSysApp = 'AP'
                        ) CN
                        LEFT JOIN
                        (
                            --กรณี AD มีการ Set ค่าให้ใช้ค่าตาม AD
                            SELECT FTSysCode, 
                                FTSysApp, 
                                FTCfgStaUsrValue  --P1
                            FROM TCNTConfigSpc
                            WHERE FTSysCode = 'tPS_Warehouse'
                                AND FTSysApp = 'AP'
                                AND FTAgnCode = '$FTAgnCode'
                        ) CAD ON CN.FTSysCode = CAD.FTSysCode
                                AND CN.FTSysApp = CAD.FTSysApp
            ";
            $oQuery1    = $this->db->query($tSQLUsrVal);
            if($oQuery1->num_rows() > 0){
                $aDataReturn    = $oQuery1->row_array();
                $tWahlWhere = $aDataReturn['FTWahCode'];
                $tSQLWahCode = " SELECT FTWahCode, 
                                   FTWahName
                            FROM TCNMWaHouse_L WITH(NOLOCK)
                            WHERE FTWahCode = '$tWahlWhere'
                            AND FNLngID = '$nLngID'
                            -- AND FTBchCode = '$FTBchCode'
                            ";
                $oQuery2    = $this->db->query($tSQLWahCode);
                $aDataReturn    = $oQuery2->row_array();
            }
            unset($oQuery1);
            unset($oQuery2);
            return $aDataReturn;
        }

    // Function : Get Data In Doc DT Temp
    public function FSaMPRBGetDocDTTempListPage($paDataWhere){
        $tPRBDocNo           = $paDataWhere['FTXthDocNo'];
        $tPRBDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tPRBSesSessionID    = $this->session->userdata('tSesSessionID');

        $aRowLen    = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);

        $tSQL       = " SELECT c.* FROM(
                            SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM (
                                SELECT
                                    DOCTMP.FTBchCode,
                                    DOCTMP.FTXthDocNo,
                                    DOCTMP.FNXtdSeqNo,
                                    DOCTMP.FTXthDocKey,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTXtdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FTXtdBarCode,
                                    DOCTMP.FTPunCode,
                                    DOCTMP.FCXtdFactor,
                                    DOCTMP.FCXtdQty,
                                    DOCTMP.FCXtdSetPrice,
                                    DOCTMP.FCXtdAmtB4DisChg,
                                    DOCTMP.FTXtdDisChgTxt,
                                    DOCTMP.FCXtdNet,
                                    DOCTMP.FCXtdNetAfHD,
                                    DOCTMP.FTXtdStaAlwDis,
                                    DOCTMP.FTTmpRemark,
                                    DOCTMP.FCXtdVatRate,
                                    DOCTMP.FTXtdVatType,
                                    DOCTMP.FTSrnCode,
                                    DOCTMP.FDLastUpdOn,
                                    DOCTMP.FDCreateOn,
                                    DOCTMP.FTLastUpdBy,
                                    DOCTMP.FCXtdAmt AS FCStkQty,
                                    DOCTMP.FCXtdVat AS FCPdtQtyOrdBuy,
                                    DOCTMP.FCXtdSetPrice AS FCPdtQtySugges,
                                    -- ISNULL(PSB.FCStkQty,0) AS FCStkQty,
                                    -- ISNULL(PSW.FCPdtQtyOrdBuy,0) AS FCPdtQtyOrdBuy,
                                    -- ISNULL(PSW.FCPdtQtySugges,0) AS FCPdtQtySugges,
                                    DOCTMP.FTCreateBy
                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                -- LEFT JOIN TCNMPdtSpcWah PSW ON DOCTMP.FTPdtCode = PSW.FTPdtCode
                                -- LEFT JOIN TCNMImgPdt IMGPDT on DOCTMP.FTPdtCode = IMGPDT.FTImgRefID AND IMGPDT.FTImgTable='TCNMPdt'
                                -- LEFT JOIN TCNTPdtStkBal PSB ON PSW.FTPdtCode = PSB.FTPdtCode
                                -- OUTER APPLY (SELECT TOP 1 [FCStkQty],[FTPdtCode] FROM [TCNTPdtStkBal] B WHERE B.FTPdtCode = PSW.FTPdtCode) PSB
                                WHERE 1 = 1
                                AND DOCTMP.FTXthDocKey = '$tPRBDocKey'
                                AND DOCTMP.FTSessionID = '$tPRBSesSessionID' ";
        if(isset($tPRBDocNo) && !empty($tPRBDocNo)){
            $tSQL   .=  " AND ISNULL(DOCTMP.FTXthDocNo,'')  = '$tPRBDocNo' ";
        }

        if(isset($tSearchPdtAdvTable) && !empty($tSearchPdtAdvTable)){
            $tSQL   .=  "   AND (
                                DOCTMP.FTPdtCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdPdtName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTXtdBarCode COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%'
                                OR DOCTMP.FTPunName COLLATE THAI_BIN LIKE '%$tSearchPdtAdvTable%' )
                        ";

        }
        $tSQL   .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1] ORDER BY FTPdtCode,FCxtdFactor DESC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataList  = $oQuery->result_array();
            $aFoundRow  = $this->FSaMPRBGetDocDTTempListPageAll($paDataWhere);
            $nFoundRow  = ($aFoundRow['rtCode'] == '1')? $aFoundRow['rtCountData'] : 0;
            $nPageAll   = ceil($nFoundRow/$paDataWhere['nRow']);
            $aDataReturn    = array(
                'raItems'       => $aDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($aDataList);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aDataReturn;
    }

    // Function : Count All Document DT Temp
    public function FSaMPRBGetDocDTTempListPageAll($paDataWhere){
        $tPRBDocNo           = $paDataWhere['FTXthDocNo'];
        $tPRBDocKey          = $paDataWhere['FTXthDocKey'];
        $tSearchPdtAdvTable = $paDataWhere['tSearchPdtAdvTable'];
        $tPRBSesSessionID    = $this->session->userdata('tSesSessionID');

        $tSQL   = " SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP
                    WHERE 1 = 1 ";

        $tSQL   .= " AND DOCTMP.FTXthDocNo  = '$tPRBDocNo' ";
        $tSQL   .= " AND DOCTMP.FTXthDocKey = '$tPRBDocKey' ";
        $tSQL   .= " AND DOCTMP.FTSessionID = '$tPRBSesSessionID' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'    => '800',
                'rtDesc'    => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    //Get Data Pdt
    public function FSaMPRBGetDataPdt($paDataPdtParams){
        $tPdtCode   = $paDataPdtParams['tPdtCode'];
        $FTPunCode  = $paDataPdtParams['tPunCode'];
        $FTBarCode  = $paDataPdtParams['tBarCode'];
        $nLngID     = $paDataPdtParams['nLngID'];
        $tSQL       = " SELECT
                            PDT.FTPdtCode,
                            PDT.FTPdtStkControl,
                            PDT.FTPdtGrpControl,
                            PDT.FTPdtForSystem,
                            PDT.FCPdtQtyOrdBuy,
                            PDT.FCPdtCostDef,
                            PDT.FCPdtCostOth,
                            PDT.FCPdtCostStd,
                            PDT.FCPdtMin,
                            PDT.FCPdtMax,
                            PDT.FTPdtPoint,
                            PDT.FCPdtPointTime,
                            PDT.FTPdtType,
                            PDT.FTPdtSaleType,
                            0 AS FTPdtSalePrice,
                            PDT.FTPdtSetOrSN,
                            PDT.FTPdtStaSetPri,
                            PDT.FTPdtStaSetShwDT,
                            PDT.FTPdtStaAlwDis,
                            PDT.FTPdtStaAlwReturn,
                            PDT.FTPdtStaVatBuy,
                            PDT.FTPdtStaVat,
                            PDT.FTPdtStaActive,
                            PDT.FTPdtStaAlwReCalOpt,
                            PDT.FTPdtStaCsm,
                            PDT.FTTcgCode,
                            PDT.FTPtyCode,
                            PDT.FTPbnCode,
                            PDT.FTPmoCode,
                            PDT.FTVatCode,
                            PDT.FDPdtSaleStart,
                            PDT.FDPdtSaleStop,
                            PDTL.FTPdtName,
                            PDTL.FTPdtNameOth,
                            PDTL.FTPdtNameABB,
                            PDTL.FTPdtRmk,
                            PKS.FTPunCode,
                            PKS.FCPdtUnitFact,
                            VAT.FCVatRate,
                            UNTL.FTPunName,
                            BAR.FTBarCode,
                            BAR.FTPlcCode,
                            PDTLOCL.FTPlcName,
                            PDTSRL.FTSrnCode,
                            PDT.FCPdtCostStd,
                            CAVG.FCPdtCostEx,
                            CAVG.FCPdtCostIn,
                            SPL.FCSplLastPrice
                        FROM TCNMPdt PDT WITH (NOLOCK)
                        LEFT JOIN TCNMPdt_L PDTL        WITH (NOLOCK)   ON PDT.FTPdtCode      = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtPackSize  PKS  WITH (NOLOCK)   ON PDT.FTPdtCode      = PKS.FTPdtCode     AND PKS.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtUnit_L UNTL    WITH (NOLOCK)   ON UNTL.FTPunCode     = '$FTPunCode'      AND UNTL.FNLngID    = $nLngID
                        LEFT JOIN TCNMPdtBar BAR        WITH (NOLOCK)   ON PKS.FTPdtCode      = BAR.FTPdtCode     AND BAR.FTPunCode   = '$FTPunCode'
                        LEFT JOIN TCNMPdtLoc_L PDTLOCL  WITH (NOLOCK)   ON PDTLOCL.FTPlcCode  = BAR.FTPlcCode     AND PDTLOCL.FNLngID = $nLngID
                        LEFT JOIN (
                            SELECT DISTINCT
                                FTVatCode,
                                FCVatRate,
                                FDVatStart
                            FROM TCNMVatRate WITH (NOLOCK)
                            WHERE CONVERT(VARCHAR(19),GETDATE(),121) > FDVatStart ) VAT
                        ON PDT.FTVatCode = VAT.FTVatCode
                        LEFT JOIN TCNTPdtSerial PDTSRL  WITH (NOLOCK)   ON PDT.FTPdtCode    = PDTSRL.FTPdtCode
                        LEFT JOIN TCNMPdtSpl SPL        WITH (NOLOCK)   ON PDT.FTPdtCode    = SPL.FTPdtCode AND BAR.FTBarCode = SPL.FTBarCode
                        LEFT JOIN TCNMPdtCostAvg CAVG   WITH (NOLOCK)   ON PDT.FTPdtCode    = CAVG.FTPdtCode
                        WHERE 1 = 1 ";

        if(isset($tPdtCode) && !empty($tPdtCode)){
            $tSQL   .= " AND PDT.FTPdtCode   = '$tPdtCode'";
        }

        if(isset($FTBarCode) && !empty($FTBarCode)){
            $tSQL   .= " AND BAR.FTBarCode = '$FTBarCode'";
        }

        $tSQL   .= " ORDER BY FDVatStart DESC";
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $aResult    = array(
                'raItem'    => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aResult;
    }

    // Functionality : Insert Pdt To Doc DT Temp
    public function FSaMPRBInsertPDTToTemp($paDataPdtMaster,$paDataPdtParams){
        $paPIDataPdt    = $paDataPdtMaster['raItem'];
        if(isset($paDataPdtParams['tQTY'])){
            $GetQty = $paDataPdtParams['tQTY'];
        }else{
            $GetQty = 1;
        }
        if ($paDataPdtParams['tPRBOptionAddPdt'] == 1) {
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL   =   "   SELECT
                                FNXtdSeqNo,
                                FCXtdQty
                            FROM TCNTDocDTTmp
                            WHERE 1=1
                            AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                            AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                            AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                            AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                            AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                            AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                            ORDER BY FNXtdSeqNo
                        ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                // เพิ่มจำนวนให้รายการที่มีอยู่แล้ว
                $aResult    = $oQuery->row_array();
                $tSQL       =   "   UPDATE TCNTDocDTTmp
                                    SET FCXtdQty = '".($aResult["FCXtdQty"] + 1 )."'
                                    WHERE 1=1
                                    AND FTBchCode       = '".$paDataPdtParams['tBchCode']."'
                                    AND FTXthDocNo      = '".$paDataPdtParams['tDocNo']."'
                                    AND FNXtdSeqNo      = '".$aResult["FNXtdSeqNo"]."'
                                    AND FTXthDocKey     = '".$paDataPdtParams['tDocKey']."'
                                    AND FTSessionID     = '".$paDataPdtParams['tSessionID']."'
                                    AND FTPdtCode       = '".$paPIDataPdt["FTPdtCode"]."'
                                    AND FTXtdBarCode    = '".$paPIDataPdt["FTBarCode"]."'
                                ";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                    // เพิ่มรายการใหม่
                    $aDataInsert    = array(
                        'FTBchCode'         => $paDataPdtParams['tBchCode'],
                        'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                        'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                        'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                        'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                        'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                        'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                        'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                        'FTPunName'         => $paPIDataPdt['FTPunName'],
                        'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                        'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                        // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                        'FTVatCode'         => $paDataPdtParams['tPRBrowspan'],
                        'FCXtdVat'         => $paDataPdtParams['tPRBFCPdtQtyOrdBuy'],
                        // 'FTVatCode'         => $paDataPdtParams['nVatCode'],
                        'FCXtdAmt'         => $paDataPdtParams['tFCStkQty'],
                        'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                        'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                        'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                        'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                        // 'FCXtdQty'          => $GetQty,
                        'FCXtdQty'          => $paDataPdtParams['tPRBSuggest'],
                        'FCXtdQtyAll'       => $GetQty*$paPIDataPdt['FCPdtUnitFact'],
                        // 'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * $GetQty,
                        'FCXtdSetPrice'     => $paDataPdtParams['tPRBSuggest'],
                        'FCXtdNet'          => $paDataPdtParams['cPrice'] * $GetQty,
                        // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                        // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                        'FTSessionID'       => $paDataPdtParams['tSessionID'],
                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                        'FTLastUpdBy'       => $paDataPdtParams['tPRBUsrCode'],
                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                        'FTCreateBy'        => $paDataPdtParams['tPRBUsrCode'],
                        'FCXtdVatable'      => $paDataPdtParams['tPRBAllSugges'],
                    );
                    
                    $this->db->insert('TCNTDocDTTmp',$aDataInsert);
                    if($this->db->affected_rows() > 0){
                        $aStatus = array(
                            'rtCode'    => '1',
                            'rtDesc'    => 'Add Success.',
                        );
                    }else{
                        $aStatus = array(
                            'rtCode'    => '905',
                            'rtDesc'    => 'Error Cannot Add.',
                        );
                    }
                }
        }else{
            // เพิ่มแถวใหม่
            $aDataInsert    = array(
                'FTBchCode'         => $paDataPdtParams['tBchCode'],
                'FTXthDocNo'        => $paDataPdtParams['tDocNo'],
                'FNXtdSeqNo'        => $paDataPdtParams['nMaxSeqNo'],
                'FTXthDocKey'       => $paDataPdtParams['tDocKey'],
                'FTPdtCode'         => $paPIDataPdt['FTPdtCode'],
                'FTXtdPdtName'      => $paPIDataPdt['FTPdtName'],
                'FCXtdFactor'       => $paPIDataPdt['FCPdtUnitFact'],
                'FTPunCode'         => $paPIDataPdt['FTPunCode'],
                'FTPunName'         => $paPIDataPdt['FTPunName'],
                'FTXtdBarCode'      => $paDataPdtParams['tBarCode'],
                'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVatBuy'],
                // 'FTXtdVatType'      => $paPIDataPdt['FTPdtStaVat'],
                // 'FTVatCode'         => $paDataPdtParams['nVatCode'],
                'FCXtdVatRate'      => $paDataPdtParams['nVatRate'],
                'FTXtdStaAlwDis'    => $paPIDataPdt['FTPdtStaAlwDis'],
                'FTXtdSaleType'     => $paPIDataPdt['FTPdtSaleType'],
                'FCXtdSalePrice'    => $paDataPdtParams['cPrice'],
                // 'FCXtdQty'          => $GetQty,
                'FCXtdQty'          => $paDataPdtParams['tPRBSuggest'],
                'FCXtdQtyAll'       => $GetQty*$paPIDataPdt['FCPdtUnitFact'],
                'FCXtdSetPrice'     => $paDataPdtParams['tPRBSuggest'],
                'FCXtdAmt'         => $paDataPdtParams['tFCStkQty'],
                'FTVatCode'         => $paDataPdtParams['tPRBrowspan'],
                'FCXtdVat'         => $paDataPdtParams['tPRBFCPdtQtyOrdBuy'],
                // 'FCXtdSetPrice'     => $paDataPdtParams['cPrice'] * $GetQty,
                'FCXtdNet'          => $paDataPdtParams['cPrice'] * $GetQty,
                // 'FCXtdNetAfHD'      => $paDataPdtParams['cPrice'] * 1,
                'FTSessionID'       => $paDataPdtParams['tSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $paDataPdtParams['tPRBUsrCode'],
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTCreateBy'        => $paDataPdtParams['tPRBUsrCode'],
                'FCXtdVatable'      => $paDataPdtParams['tPRBAllSugges'],
            );
            $this->db->insert('TCNTDocDTTmp',$aDataInsert);
            // $this->db->last_query();
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode'    => '1',
                    'rtDesc'    => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode'    => '905',
                    'rtDesc'    => 'Error Cannot Add.',
                );
            }
        }
            return $aStatus;
    }

    //Delete Product Single Item In Doc DT Temp
    public function FSnMPRBDelPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tPRBDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    //Delete Product Multiple Items In Doc DT Temp
    public function FSnMPRBDelMultiPdtInDTTmp($paDataWhere){
        // Delete Doc DT Temp
        $this->db->where_in('FTSessionID',$paDataWhere['tSessionID']);
        $this->db->where_in('FTXthDocNo',$paDataWhere['tPRBDocNo']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FTPdtCode',$paDataWhere['tPdtCode']);
        // $this->db->where_in('FNXtdSeqNo',$paDataWhere['nSeqNo']);
        $this->db->where_in('FTBchCode',$paDataWhere['tBchCode']);
        $this->db->delete('TCNTDocDTTmp');
        return ;
    }

    // Update Document DT Temp by Seq
    public function FSaMPRBUpdateInlineDTTemp($paDataUpdateDT,$paDataWhere){
        $this->db->where_in('FTSessionID',$paDataWhere['tPRBSessionID']);
        $this->db->where_in('FTXthDocKey',$paDataWhere['tDocKey']);
        $this->db->where_in('FNXtdSeqNo',$paDataWhere['nPRBSeqNo']);

        if ($paDataWhere['tPRBDocNo'] != '' && $paDataWhere['tPRBBchCode'] != '') {
            $this->db->where_in('FTXthDocNo',$paDataWhere['tPRBDocNo']);
            $this->db->where_in('FTBchCode',$paDataWhere['tPRBBchCode']);
        }

        $this->db->update('TCNTDocDTTmp', $paDataUpdateDT);
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Update Success',
            );
        }else{
            $aStatus = array(
                'rtCode'    => '903',
                'rtDesc'    => 'Update Fail',
            );
        }

        return $aStatus;
    }

    // Function : Count Check Data Product In Doc DT Temp Before Save
    public function FSnMPRBChkPdtInDocDTTemp($paDataWhere){
        $tPRBDocNo       = $paDataWhere['FTXthDocNo'];
        $tPRBDocKey      = $paDataWhere['FTXthDocKey'];
        $tPRBSessionID   = $paDataWhere['FTSessionID'];
        $tSQL           = " SELECT
                                COUNT(FNXtdSeqNo) AS nCountPdt
                            FROM TCNTDocDTTmp DocDT
                            WHERE 1=1
                            AND DocDT.FTXthDocKey   = '$tPRBDocKey'
                            AND DocDT.FTSessionID   = '$tPRBSessionID' ";
        if(isset($tPRBDocNo) && !empty($tPRBDocNo)){
            $tSQL   .=  " AND ISNULL(DocDT.FTXthDocNo,'')  = '$tPRBDocNo' ";
        }
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            return $aDataQuery['nCountPdt'];
        }else{
            return 0;
        }
    }

    // Function: Get Data DO HD List
    public function FSoMPRBCallRefIntDocDataTable($paDataCondition){
        $aRowLen                = FCNaHCallLenData($paDataCondition['nRow'],$paDataCondition['nPage']);
        $nLngID                 = $paDataCondition['FNLngID'];
        $aAdvanceSearch         = $paDataCondition['aAdvanceSearch'];
        // Advance Search
        $tPRBRefIntBchCode        = $aAdvanceSearch['tPRBRefIntBchCode'];
        $tPRBRefIntWahCode        = $aAdvanceSearch['tPRBRefIntWahCode'];
        $tPRBRefIntDocNo          = $aAdvanceSearch['tPRBRefIntDocNo'];
        $oetPRBRefIntPDTCodeFrm     = $aAdvanceSearch['oetPRBRefIntPDTCodeFrm'];
        $oetPRBRefIntPDTCodeTo      = $aAdvanceSearch['oetPRBRefIntPDTCodeTo'];
        $tPRBRefIntStaDoc         = $aAdvanceSearch['tPRBRefIntStaDoc'];

        $tSQLMain = "   SELECT
                            BAL.FTPdtCode,
			                PDTL.FTPdtName,
			                BCHL.FTBchName,
			                WAHL.FTWahName  AS FTWahName,
			                BAL.FCStkQty
			                FROM TCNTPdtStkBal BAL WITH (NOLOCK)
                            LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK) ON BAL.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
			                LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BAL.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
			                LEFT JOIN TCNMWaHouse_L WAHL WITH (NOLOCK) ON BAL.FTBchCode = WAHL.FTBchCode AND BAL.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID	= $nLngID
                            WHERE BAL.FCStkQty <=0 
                    ";

        if(isset($tPRBRefIntBchCode) && !empty($tPRBRefIntBchCode)){
            $tSQLMain .= " AND (BAL.FTBchCode = '$tPRBRefIntBchCode')";
        }

        if(isset($tPRBRefIntWahCode) && !empty($tPRBRefIntWahCode)){
            $tSQLMain .= " AND (BAL.FTWahCode = '$tPRBRefIntWahCode')";
        }

        if(isset($tPRBRefIntDocNo) && !empty($tPRBRefIntDocNo)){
            $tSQLMain .= " AND (HD.FTXphDocNo LIKE '%$tPRBRefIntDocNo%')";
        }

        if(!empty($oetPRBRefIntPDTCodeFrm) && !empty($oetPRBRefIntPDTCodeTo)){
            $tSQLMain .= " AND ( BAL.FTPdtCode BETWEEN '$oetPRBRefIntPDTCodeFrm' AND '$oetPRBRefIntPDTCodeTo' ) OR (BAL.FTPdtCode BETWEEN '$oetPRBRefIntPDTCodeTo' AND '$oetPRBRefIntPDTCodeFrm')";
        }

        $tSQL   =   "       SELECT c.* FROM(
                              SELECT  ROW_NUMBER() OVER(ORDER BY FTPdtCode DESC ) AS FNRowID,* FROM
                                (  $tSQLMain
                                ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]
        ";
        // echo $tSQLMain;
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aDataCountAllRow   = $oQueryMain->num_rows();
            $nFoundRow          = $aDataCountAllRow;
            $nPageAll           = ceil($nFoundRow/$paDataCondition['nRow']);
            $aResult = array(
                'raItems'       => $oDataList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataCondition['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );

        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataCondition['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($oDataList);
        unset($aDataCountAllRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Function: Get Data DO HD List
    public function FSoMPRBCallExcelData($paDataCondition){
        $nLngID                 = $paDataCondition['FNLngID'];
        $tPRBRefIntBchCode        = $paDataCondition['tPRBRefIntBchCode'];
        $tPRBRefIntWahCode        = $paDataCondition['tPRBRefIntWahCode'];
        $tPRBRefIntDocNo          = $paDataCondition['tPRBRefIntDocNo'];
        $oetPRBRefIntPDTCodeFrm     = $paDataCondition['oetPRBRefIntPDTCodeFrm'];
        $oetPRBRefIntPDTCodeTo      = $paDataCondition['oetPRBRefIntPDTCodeTo'];
        $tPRBRefIntStaDoc         = $paDataCondition['tPRBRefIntStaDoc'];

        $tSQLMain = "   SELECT
                            BAL.FTPdtCode,
                            PDTL.FTPdtName,
                            BCHL.FTBchName,
                            WAHL.FTWahName  AS FTWahName,
                            BAL.FCStkQty
                            FROM TCNTPdtStkBal BAL WITH (NOLOCK)
                            LEFT JOIN TCNMPdt_L PDTL WITH (NOLOCK) ON BAL.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                            LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON BAL.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                            LEFT JOIN TCNMWaHouse_L WAHL WITH (NOLOCK) ON BAL.FTBchCode = WAHL.FTBchCode AND BAL.FTWahCode = WAHL.FTWahCode AND WAHL.FNLngID	= $nLngID
                            WHERE BAL.FCStkQty <=0 
                    ";

        if(isset($tPRBRefIntBchCode) && !empty($tPRBRefIntBchCode)){
            $tSQLMain .= " AND (BAL.FTBchCode = '$tPRBRefIntBchCode')";
        }

        if(isset($tPRBRefIntWahCode) && !empty($tPRBRefIntWahCode)){
            $tSQLMain .= " AND (BAL.FTWahCode = '$tPRBRefIntWahCode')";
        }

        if(isset($tPRBRefIntDocNo) && !empty($tPRBRefIntDocNo)){
            $tSQLMain .= " AND (HD.FTXphDocNo LIKE '%$tPRBRefIntDocNo%')";
        }

        if(!empty($oetPRBRefIntPDTCodeFrm) && !empty($oetPRBRefIntPDTCodeTo)){
            $tSQLMain .= " AND ( BAL.FTPdtCode BETWEEN '$oetPRBRefIntPDTCodeFrm' AND '$oetPRBRefIntPDTCodeTo' ) OR (BAL.FTPdtCode BETWEEN '$oetPRBRefIntPDTCodeTo' AND '$oetPRBRefIntPDTCodeFrm')";
        }

        // echo $tSQLMain;
        $oQuery = $this->db->query($tSQLMain);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $oQueryMain         = $this->db->query($tSQLMain);
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );

        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        return $aResult;
    }

    // Functionality: Get Data Purchase Order HD List
    public function FSoMPRBCallRefIntDocDTDataTable($paData){

        $nLngID   =  $paData['FNLngID'];
        $tBchCode  =  $paData['tBchCode'];
        $tPRBcNo    =  $paData['tDocNo'];

        $tSQL= "SELECT
                    DT.FTBchCode,
                    DT.FTXphDocNo,
                    DT.FNXpdSeqNo,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    DT.FTXpdRmk,
                    DT.FDLastUpdOn,
                    DT.FTLastUpdBy,
                    DT.FDCreateOn,
                    DT.FTCreateBy
                    FROM TCNTPdtReqHqDT DT WITH(NOLOCK)
            WHERE   DT.FTBchCode = '$tBchCode' AND  DT.FTXphDocNo ='$tPRBcNo'
            ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDataList          = $oQuery->result_array();
            $aResult = array(
                'raItems'       => $oDataList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;
    }

    // Function : Add/Update Data HD
    public function FSxMPRBAddUpdateHD($paDataMaster,$paDataWhere,$paTableAddUpdate){
        $aDataGetDataHD     =   $this->FSaMPRBGetDataDocHD(array(
            'FTBchCode'     => $paDataWhere['FTBchCode'],
            'FTAgnCode'     => $paDataWhere['FTAgnCode'],
            'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
            'FNLngID'       => $this->input->post("ohdPRBLangEdit")
        ));

        $aDataAddUpdateHD   = array();
        if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            $aDataHDOld         = $aDataGetDataHD['raItems'];
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDLastUpdOn'   => $paDataWhere['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataWhere['FTLastUpdBy'],
                'FDCreateOn'    => $aDataHDOld['rdDateOn'],
                'FTCreateBy'    => $aDataHDOld['rtCreateBy']
            ));
        }else{
            $aDataAddUpdateHD   = array_merge($paDataMaster,array(
                'FTBchCode'     => $paDataWhere['FTBchCode'],
                'FTAgnCode'     => $paDataWhere['FTAgnCode'],
                'FTXphDocNo'    => $paDataWhere['FTXphDocNo'],
                'FDCreateOn'    => $paDataWhere['FDCreateOn'],
                'FTCreateBy'    => $paDataWhere['FTCreateBy'],
            ));
        }

        // Delete PI HD
        $this->db->where_in('FTBchCode',$aDataAddUpdateHD['FTBchCode']);
        $this->db->where_in('FTAgnCode',$aDataAddUpdateHD['FTAgnCode']);
        $this->db->where_in('FTXphDocNo',$aDataAddUpdateHD['FTXphDocNo']);
        $this->db->delete($paTableAddUpdate['tTableHD']);

        // Insert PI HD Dis
        $this->db->insert($paTableAddUpdate['tTableHD'],$aDataAddUpdateHD);

        return;
    }



    //อัพเดทเลขที่เอกสาร  TCNTDocDTTmp , TCNTDocHDDisTmp , TCNTDocDTDisTmp
    public function FSxMPRBAddUpdateDocNoToTemp($paDataWhere,$paTableAddUpdate){
        // Update DocNo Into DTTemp
        $this->db->where('FTXthDocNo','');
        $this->db->where('FTSessionID',$paDataWhere['FTSessionID']);
        $this->db->where('FTXthDocKey',$paTableAddUpdate['tTableHD']);
        $this->db->update('TCNTDocDTTmp',array(
            'FTXthDocNo'    => $paDataWhere['FTXphDocNo'],
            'FTBchCode'     => $paDataWhere['FTBchCode']
        ));

        return;
    }

    // Function Move Document DTTemp To Document DT
    public function FSaMPRBMoveDtTmpToDt($paDataWhere,$paTableAddUpdate){
        $tPRBBchCode     = $paDataWhere['FTBchCode'];
        $tPRBAgnCode     = $paDataWhere['FTAgnCode'];
        $tPRBDocNo       = $paDataWhere['FTXphDocNo'];
        $tPRBDocKey      = $paTableAddUpdate['tTableHD'];
        $tPRBSessionID   = $paDataWhere['FTSessionID'];

        if(isset($tPRBDocNo) && !empty($tPRBDocNo)){
            $this->db->where_in('FTXphDocNo',$tPRBDocNo);
            $this->db->delete($paTableAddUpdate['tTableDT']);
        }

        $tSQL   = " INSERT INTO ".$paTableAddUpdate['tTableDT']." (
                        FTBchCode,FTAgnCode,FTXphDocNo,FNXpdSeqNo,FTPdtCode,FTXpdPdtName,FTPunCode,FTPunName,FCXpdFactor,FTXpdBarCode,
                        FCXpdQty,FCXpdQtyAll,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy,FCXpdQtyBal,FCXpdQtySugges,FTXpdRmk,FCXpdQtyOrdBuy,FCXpdSugges ) ";
        $tSQL   .=  "   SELECT
                            DOCTMP.FTBchCode,
                            '$tPRBAgnCode' AS FTAgnCode,
                            DOCTMP.FTXthDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                            DOCTMP.FTPdtCode,
                            DOCTMP.FTXtdPdtName,
                            DOCTMP.FTPunCode,
                            DOCTMP.FTPunName,
                            DOCTMP.FCXtdFactor,
                            DOCTMP.FTXtdBarCode,
                            DOCTMP.FCXtdQty,
                            DOCTMP.FCXtdQtyAll,
                            DOCTMP.FDLastUpdOn,
                            DOCTMP.FTLastUpdBy,
                            DOCTMP.FDCreateOn,
                            DOCTMP.FTCreateBy,
                            DOCTMP.FCXtdAmt,
                            DOCTMP.FCXtdSetPrice,
                            DOCTMP.FTVatCode,
                            DOCTMP.FCXtdVat,
                            DOCTMP.FCXtdVatable
                        FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                        WHERE 1 = 1
                        AND DOCTMP.FTBchCode    = '$tPRBBchCode'
                        AND DOCTMP.FTXthDocNo   = '$tPRBDocNo'
                        AND DOCTMP.FTXthDocKey  = '$tPRBDocKey'
                        AND DOCTMP.FTSessionID  = '$tPRBSessionID'
                        ORDER BY DOCTMP.FNXtdSeqNo ASC
        ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    //---------------------------------------------------------------------------------------

    //ข้อมูล HD
    public function FSaMPRBGetDataDocHD($paDataWhere){
        $tBchCode   = $paDataWhere['FTBchCode'];
        $tAgnCode   = $paDataWhere['FTAgnCode'];
        $tPRBDocNo   = $paDataWhere['FTXphDocNo'];
        $nLngID     = $paDataWhere['FNLngID'];

        $tSQL       = " SELECT
                            DOCHD.FTXphDocNo   AS rtXthDocNo,
                            DOCHD.FDXphDocDate AS rdXthDocDate,
                            DOCHD.FTXphStaDoc  AS rtXthStaDoc,
                            DOCHD.FTXphStaApv  AS rtXthStaApv,
                            PRBREF.FTXshRefDocNo AS rtXthRefInt,
                            PRBREFEX.FTXshRefDocNo AS rtXthRefExt,
                            PRBREF.FDXshRefDocDate AS rdXthRefIntDate,
                            PRBREFEX.FDXshRefDocDate AS rdXthRefExtDate,
                            DOCHD.FNXphStaRef  AS rnXthStaRef,
                            DOCHD.FNXphStaDocAct  AS rnXthStaDocAct,
                            DOCHD.FNXphDocPrint  AS rnXthDocPrint,
                            DOCHD.FTXphRmk     AS rtXthRmk,
                            DOCHD.FDCreateOn   AS rdDateOn,
                            DOCHD.FTCreateBy   AS rtCreateBy,
                            AGN.FTAgnCode       AS rtAgnCode,
                            AGN.FTAgnName       AS rtAgnName,
                            DOCHD.FTBchCode     AS rtBchCode,
                            BCHL.FTBchName      AS rtBchName,
                            USRL.FTUsrName      AS rtUsrName ,
                            DOCHD.FTXphApvCode  AS rtXthApvCode,
                            USRAPV.FTUsrName	AS rtXthApvName,
                            AGNTo.FTAgnCode     AS rtAgnCodeTo,
                            AGNTo.FTAgnName     AS rtAgnNameTo,
                            DOCHD.FTXphBchFrm    AS rtBchCodeFrm,
                            BCHLTo.FTBchName    AS rtBchNameTo,
                            WAHTo_L.FTWahCode   AS rtWahCodeTo,
                            WAHTo_L.FTWahName    AS rtWahNameTo,
                            AGNShip.FTAgnCode    AS rtAgnCodeShip,
                            AGNShip.FTAgnName    AS rtAgnNameShip,
                            DOCHD.FTXphBchTo    AS rtBchCodeShip,
                            BCHLShip.FTBchName    AS rtBchNameShip
                            -- WAHShipTo_L.FTWahCode  AS rtWahCodeShip,
                            -- WAHShipTo_L.FTWahName  AS rtWahNameShip,
                            -- DOCHD.FTRsnCode       AS rtRsnCode,
                            -- RSNL.FTRsnName        AS rtRsnName
                        FROM TCNTPdtReqHqHD DOCHD WITH (NOLOCK)
                        INNER JOIN TCNMBranch       BCH     WITH (NOLOCK)   ON DOCHD.FTBchCode      = BCH.FTBchCode
                        LEFT JOIN TCNMBranch_L      BCHL    WITH (NOLOCK)   ON BCH.FTBchCode        = BCHL.FTBchCode    AND BCHL.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGN     WITH (NOLOCK)   ON BCH.FTAgnCode        = AGN.FTAgnCode     AND AGN.FNLngID	    = $nLngID
                        LEFT JOIN TCNMUser_L        USRL    WITH (NOLOCK)   ON DOCHD.FTUsrCode      = USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMUser_L        USRAPV	WITH (NOLOCK)   ON DOCHD.FTXphApvCode	= USRL.FTUsrCode	AND USRL.FNLngID	= $nLngID
                        LEFT JOIN TCNMBranch        BCHTo    WITH (NOLOCK)  ON DOCHD.FTXphBchFrm     = BCHTo.FTBchCode
                        LEFT JOIN TCNMBranch_L      BCHLTo   WITH (NOLOCK)  ON DOCHD.FTXphBchFrm     = BCHLTo.FTBchCode  AND BCHLTo.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGNTo    WITH (NOLOCK)  ON BCHTo.FTAgnCode      = AGNTo.FTAgnCode   AND AGNTo.FNLngID   = $nLngID
                        LEFT JOIN TCNMBranch        BCHShip    WITH (NOLOCK)  ON DOCHD.FTXphBchTo     = BCHShip.FTBchCode
                        LEFT JOIN TCNMBranch_L      BCHLShip   WITH (NOLOCK)  ON DOCHD.FTXphBchTo     = BCHLShip.FTBchCode  AND BCHLShip.FNLngID	= $nLngID
                        LEFT JOIN TCNMAgency_L      AGNShip    WITH (NOLOCK)  ON BCHShip.FTAgnCode      = AGNShip.FTAgnCode   AND AGNShip.FNLngID   = $nLngID
                        LEFT JOIN TCNMWaHouse_L     WAHTo_L  WITH (NOLOCK) ON DOCHD.FTXphBchFrm   = WAHTo_L.FTBchCode AND DOCHD.FTWahCode = WAHTo_L.FTWahCode AND WAHTo_L.FNLngID	= $nLngID
                        -- LEFT JOIN TCNMWaHouse_L     WAHShipTo_L  WITH (NOLOCK)   ON DOCHD.FTXphBchTo  = WAHShipTo_L.FTBchCode   AND DOCHD.FTXthWhShipTo = WAHShipTo_L.FTWahCode AND WAHShipTo_L.FNLngID	= $nLngID
                        -- LEFT JOIN TCNMRsn_L         RSNL	WITH (NOLOCK)   ON DOCHD.FTRsnCode	= RSNL.FTRsnCode	AND RSNL.FNLngID	= $nLngID
                        LEFT JOIN TCNTPdtReqHqHDDocRef    PRBREF WITH (NOLOCK) ON PRBREF.FTXshDocNo  = DOCHD.FTXphDocNo AND PRBREF.FTXshRefType = '1'
                        LEFT JOIN TCNTPdtReqHqHDDocRef    PRBREFEX WITH (NOLOCK) ON PRBREFEX.FTXshDocNo  = DOCHD.FTXphDocNo AND PRBREFEX.FTXshRefType = '3'

                        WHERE 1=1
                        AND DOCHD.FTBchCode = '$tBchCode'
                        AND DOCHD.FTAgnCode = '$tAgnCode'
                        AND DOCHD.FTXphDocNo = '$tPRBDocNo' ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aDetail = $oQuery->row_array();
            $aResult    = array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found.',
            );
        }
        return $aResult;
    }


        //ข้อมูล HD
    public function FSaMPRBGetBranchHQ(){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tAgnCode   = $this->session->userdata("tSesUsrAgnCode");
        $tSQL   =   "   SELECT BCH.FTBchCode,BCHL.FTBchName,BCH.FTAgnCode,AGNL.FTAgnName 
        FROM TCNMBranch BCH 
        LEFT JOIN TCNMBranch_L BCHL ON BCH.FTBchCode = BCHL.FTBchCode 
        AND BCHL.FNLngID = '$nLngID'
        LEFT JOIN TCNMAgency_L AGNL ON BCH.FTAgnCode = AGNL.FTAgnCode 
        AND AGNL.FNLngID = '$nLngID' 
        WHERE BCH.FTBchStaHQ = '1' AND BCH.FTAgnCode = '$tAgnCode'
        ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {

            $aDetail = $oQuery->result();
        } else {
            //No Data
            $aDetail = '';
        }
        

        if (@$aDetail) {

            $aResult = array(
                'raItem' => $aDetail[0],
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            //Not Found
            $aResult = array(
                'raItem' => '',
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);

        return $aResult;
    }



    //ลบข้อมูลใน Temp
    public function FSnMPRBDelALLTmp($paData){
        try {
            $this->db->trans_begin();

            $this->db->where_in('FTSessionID', $paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    //ย้ายจาก DT To Temp
    public function FSxMPRBMoveDTToDTTemp($paDataWhere){
        $tBchCode       = $paDataWhere['FTBchCode'];
        $tAgnCode       = $paDataWhere['FTAgnCode'];
        $tPRBDocNo       = $paDataWhere['FTXphDocNo'];
        $tPRBcKey        = $paDataWhere['FTXthDocKey'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTXthDocNo',$tPRBDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tSQL   = " INSERT INTO TCNTDocDTTmp (
            FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
            FCXtdQty,FCXtdQtyAll,FTVatCode,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy,FCXtdSetPrice,FCXtdAmt,FCXtdVat,FCXtdVatable )
        SELECT
            DT.FTBchCode,
            DT.FTXphDocNo,
            DT.FNXpdSeqNo,
            CONVERT(VARCHAR,'".$tPRBcKey."') AS FTXthDocKey,
            DT.FTPdtCode,
            DT.FTXpdPdtName,
            DT.FTPunCode,
            DT.FTPunName,
            DT.FCXpdFactor,
            DT.FTXpdBarCode,
            DT.FCXpdQty,
            DT.FCXpdQtyAll,
            DT.FTXpdRmk,
            CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
            CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
            CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy,
            DT.FCXpdQtySugges,
            DT.FCXpdQtyBal,
            DT.FCXpdQtyOrdBuy,
            DT.FCXpdSugges
        FROM TCNTPdtReqHqDT AS DT WITH (NOLOCK)
        WHERE 1=1
        AND DT.FTBchCode = '$tBchCode'
        AND DT.FTAgnCode = '$tAgnCode'
        AND DT.FTXphDocNo = '$tPRBDocNo'
        ORDER BY DT.FNXpdSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        return;
    }

    // นำข้อมูลจาก Browse ลง DTTemp
    public function FSoMPRBCallRefIntDocInsertDTToTemp($paData){

        $tPRBDocNo        = $paData['tPRBDocNo'];
        $tPRBFrmBchCode   = $paData['tPRBFrmBchCode'];

        // Delect Document DTTemp By Doc No
        $this->db->where('FTBchCode',$tPRBFrmBchCode);
        $this->db->where('FTXthDocNo',$tPRBDocNo);
        $this->db->delete('TCNTDocDTTmp');

        $tRefIntDocNo   = $paData['tRefIntDocNo'];
        $tRefIntBchCode = $paData['tRefIntBchCode'];
        $aSeqNo         = '(' . implode(',', $paData['aSeqNo']) .')';

       $tSQL= "INSERT INTO TCNTDocDTTmp (
                FTBchCode,FTXthDocNo,FNXtdSeqNo,FTXthDocKey,FTPdtCode,FTXtdPdtName,FTPunCode,FTPunName,FCXtdFactor,FTXtdBarCode,
                FCXtdQty,FCXtdQtyAll,FCXtdQtyLef,FCXtdQtyRfn,FTXtdStaPrcStk,FTXtdStaAlwDis,FNXtdPdtLevel,FTXtdPdtParent,FCXtdQtySet,
                FTXtdPdtStaSet,FTXtdRmk,FTSessionID,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy )
                SELECT
                    '$tPRBFrmBchCode' as FTBchCode,
                    '$tPRBDocNo' as FTXphDocNo,
                    DT.FNXpdSeqNo,
                    'TCNTPdtReqHqHD' AS FTXthDocKey,
                    DT.FTPdtCode,
                    DT.FTXpdPdtName,
                    DT.FTPunCode,
                    DT.FTPunName,
                    DT.FCXpdFactor,
                    DT.FTXpdBarCode,
                    DT.FCXpdQty,
                    DT.FCXpdQtyAll,
                    0 as FCXpdQtyLef,
                    0 as FCXpdQtyRfn,
                    '' as FTXpdStaPrcStk,
                    PDT.FTPdtStaAlwDis,
                    0 as FNXpdPdtLevel,
                    '' as FTXpdPdtParent,
                    0 as FCXpdQtySet,
                    '' as FTPdtStaSet,
                    '' as FTXpdRmk,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesSessionID')."') AS FTSessionID,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDLastUpdOn,
                    CONVERT(DATETIME,'".date('Y-m-d H:i:s')."') AS FDCreateOn,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTLastUpdBy,
                    CONVERT(VARCHAR,'".$this->session->userdata('tSesUsername')."') AS FTCreateBy
                FROM
                    TCNTPdtReqHqDT DT WITH (NOLOCK)
                    LEFT JOIN TCNMPdt PDT WITH (NOLOCK) ON DT.FTPdtCode = PDT.FTPdtCode
                WHERE  DT.FTBchCode = '$tRefIntBchCode' AND  DT.FTXphDocNo ='$tRefIntDocNo' AND DT.FNXpdSeqNo IN $aSeqNo
                ";

        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        return $aResult;

    }

    // Function: Delete Purchase Invoice Document
    public function FSnMPRBDelDocument($paDataDoc){
        $tDataDocNo = $paDataDoc['tDataDocNo'];
        $tBchCode = $paDataDoc['tBchCode'];
        $tAgnCode = $paDataDoc['tAgnCode'];
        $this->db->trans_begin();

        // Document HD
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTAgnCode',$tAgnCode);
        $this->db->delete('TCNTPdtReqHqHD');

        // Document DT
        $this->db->where('FTXphDocNo',$tDataDocNo);
        $this->db->where('FTBchCode',$tBchCode);
        $this->db->where('FTAgnCode',$tAgnCode);
        $this->db->delete('TCNTPdtReqHqDT');

        // PRB Ref
        $this->db->where_in('FTXshDocNo',$tDataDocNo);
        $this->db->delete('TCNTPdtReqHqHDDocRef');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aStaDelDoc     = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        }else{
            $this->db->trans_commit();
            $aStaDelDoc     = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        return $aStaDelDoc;
    }

    // Function : Cancel Document Data
    public function FSaMPRBCancelDocument($paDataUpdate){
        // TCNTPdtReqHqHD
        $this->db->trans_begin();
        $this->db->set('FTXphStaDoc' , '3');
        $this->db->where('FTXphDocNo', $paDataUpdate['tDocNo']);
        $this->db->update('TCNTPdtReqHqHD');

        // PRB Ref
        $this->db->where_in('FTXshDocNo',$paDataUpdate['tDocNo']);
        $this->db->delete('TCNTPdtReqHqHDDocRef');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aDatRetrun = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Cannot Update Status Cancel Document."
            );
        }else{
            $this->db->trans_commit();
            $aDatRetrun = array(
                'nStaEvent' => '1',
                'tStaMessg' => "Update Status Document Cancel Success."
            );
        }
        return $aDatRetrun;
    }

    //อนุมัตเอกสาร
    public function FSaMPRBApproveDocument($paDataUpdate){
        $dLastUpdOn = date('Y-m-d H:i:s');
        $tLastUpdBy = $this->session->userdata('tSesUsername');

        $this->db->set('FDLastUpdOn',$dLastUpdOn);
        $this->db->set('FTLastUpdBy',$tLastUpdBy);
        $this->db->set('FTXphStaApv',$paDataUpdate['FTXphStaApv']);
        $this->db->set('FTXphApvCode',$paDataUpdate['FTXthUsrApv']);
        $this->db->where('FTAgnCode',$paDataUpdate['FTAgnCode']);
        $this->db->where('FTBchCode',$paDataUpdate['FTBchCode']);
        $this->db->where('FTXphDocNo',$paDataUpdate['FTXphDocNo']);
        $this->db->update('TCNTPdtReqHqHD');

        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Cancel Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    public function FSaMPRBUpdatePOStaPrcDoc($ptRefInDocNo)
    {
        $nStaPrcDoc = 1;
        $this->db->set('FTXthStaPrcDoc',$nStaPrcDoc);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TCNTPdtReqHqHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    public function FSaMPRBUpdatePOStaRef($ptRefInDocNo, $pnStaRef)
    {
        $this->db->set('FNXphStaRef',$pnStaRef);
        $this->db->where('FTXphDocNo',$ptRefInDocNo);
        $this->db->update('TCNTPdtReqHqHD');
        if ($this->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Updated Status Document Success.',
            );
        } else {
            $aStatus = array(
                'rtCode' => '903',
                'rtDesc' => 'Not Update Status Document.',
            );
        }
        return $aStatus;
    }

    public function FSaMPRBUpdateRefExtDocHD($paDataPRSAddDocRef)
    {
        try {   
            $tTable     = "TCNTPdtReqHqHDDocRef";
            $paDataPrimaryKey = array(
                'FTAgnCode'         => $paDataPRSAddDocRef['FTAgnCode'],
                'FTBchCode'         => $paDataPRSAddDocRef['FTBchCode'],
                'FTXshDocNo'        => $paDataPRSAddDocRef['FTXshDocNo'],
                'FTXshRefType'        => $paDataPRSAddDocRef['FTXshRefType']
            );

            $nChhkDataDocRefExt  = $this->FSaMPRBChkDupicate($paDataPrimaryKey, $tTable);

            //หากพบว่าซ้ำ
            if(isset($nChhkDataDocRefExt['rtCode']) && $nChhkDataDocRefExt['rtCode'] == 1){
                //ลบ
                $this->db->where_in('FTAgnCode',$paDataPRSAddDocRef['FTAgnCode']);
                $this->db->where_in('FTBchCode',$paDataPRSAddDocRef['FTBchCode']);
                $this->db->where_in('FTXshDocNo',$paDataPRSAddDocRef['FTXshDocNo']);
                $this->db->where_in('FTXshRefType',$paDataPRSAddDocRef['FTXshRefType']);
                $this->db->delete('TCNTPdtReqHqHDDocRef');
                //เพิ่มใหม่
                $this->db->insert('TCNTPdtReqHqHDDocRef',$paDataPRSAddDocRef);
            //หากพบว่าไม่ซ้ำ
            }else{
                $this->db->insert('TCNTPdtReqHqHDDocRef',$paDataPRSAddDocRef);
            }

            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'insert DocRef success'
            );
            
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        return $aReturnData;
    }

        //เช็คข้อมูล Insert ว่าซ้ำหรือไม่ ถ้าซ้ำให้ลบและค่อยเพิ่มใหม่
        public function FSaMPRBChkDupicate($paDataPrimaryKey, $ptTable)
        {
            try{
                $tAgnCode = $paDataPrimaryKey['FTAgnCode'];
                $tBchCode = $paDataPrimaryKey['FTBchCode'];
                $tDocNo   = $paDataPrimaryKey['FTXshDocNo'];
                $tRefType   = $paDataPrimaryKey['FTXshRefType'];
    
                $tSQL = "   SELECT 
                                FTAgnCode,
                                FTBchCode,
                                FTXshDocNo
                            FROM $ptTable
                            WHERE 1=1
                            AND FTAgnCode  = '$tAgnCode'
                            AND FTBchCode  = '$tBchCode'
                            AND FTXshDocNo = '$tDocNo'
                            AND FTXshRefType = '$tRefType'
                        ";
                $oQueryHD = $this->db->query($tSQL);
                if ($oQueryHD->num_rows() > 0){
                    $aDetail = $oQueryHD->row_array();
                    $aResult    = array(
                        'raItems'   => $aDetail,
                        'rtCode'    => '1',
                        'rtDesc'    => 'success',
                    );
                }else{
                    $aResult    = array(
                        'rtCode'    => '800',
                        'rtDesc'    => 'data not found.',
                    );
                }
                return $aResult;
                
            }catch (Exception $Error) {
                echo $Error;
            }
        }

    /* End of file deliveryorder_model.php */

}
