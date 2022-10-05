<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );


include APPPATH .'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
class Delaystatus_controller extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('monitor/delaystatus/Delaystatus_model');
        date_default_timezone_set("Asia/Bangkok");
    }
    public function index()
    {
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $aData  = array(
          'FNLngID'       => $nLangEdit,
      );

      $aDataList = $this->Delaystatus_model->FSoMDLSGetDataOption($aData);
      $aGenDataOption  = array(
          'aDataList'      => $aDataList,
      );
      $this->load->view('monitor/delaystatus/wDelaystatus',$aGenDataOption);
    }
    public function FSvCDLSListPage()
    {
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");

      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'tSearchAll'    => $tSearchAll,
          'tCondition'    =>'',
          'tConditionDocDate' =>'',
          'tConditionDealDate' => '',
          'tConditionCustomerCode' => '',
          'tConditionCodeGroup' => '',
          'tConditionCodeType' => '',
          'tConditionCodeClass' => '',
      );
      $aDataList = $this->Delaystatus_model->FSoMDLSGetData($aData);
      $aGenTable  = array(
          'aDLSDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/delaystatus/wDelaystatusDataList',$aGenTable);
    }
    public function FSvCDLSSearchData()
    {
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $tConditionClassData = " ";
      if ($this->input->post('ocmDLSClass')!=="") {
        $aOption = explode("_",$this->input->post('ocmDLSClass'));

        if ($aOption[0]=="2") {
          $nStart = $aOption[1];
          $nStop = $aOption[2];
          $nStart = $aOption[1];
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {
            if($nStart == '0'){
              $tConditionClassData = " AND HD.FNXphCreditDay > '-$nStop' ";
            }else{
              $tConditionClassData = " AND HD.FNXphCreditDay BETWEEN '-$nStop' AND  '-$nStart' ";
            }
          }else{
            $tConditionClassData = " AND HD.FNXphCreditDay < '-$nStart'";
          }
        }else if ($aOption[0]=="1") {
          $nStart = $aOption[1];
          $nStop = $aOption[2];
          $tConditionClassData = " AND HD.FNXphCreditDay BETWEEN '$nStart' AND  '$nStop' ";
        }else {
          $tConditionClassData = " ";
        }

      }else {
        $tConditionClassData = " ";
      }
      
      // echo $tConditionClassData;
      if ($this->input->post('oetDLSRefBilDocDateFrom') !="" && $this->input->post('oetDLSRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->post('oetDLSRefBilDocDateFrom');
        $tDateBillTo   = $this->input->post('oetDLSRefBilDocDateTo');
        $tConditionDocDate = " AND HD.FDXshDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->post('oetDLSRefDealDateFrom') !="" && $this->input->post('oetDLSRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->post('oetDLSRefDealDateFrom');
        $tDateDealTo   = $this->input->post('oetDLSRefDealDateTo');
        $tConditionDealDate = " AND CRD.FNCstCrTerm BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->post('oetDLSCustomerCodeFrom')!="" && $this->input->post('oetDLSCustomerCodeTo') !="") {
        $tCustomerCodeFrom = $this->input->post('oetDLSCustomerCodeFrom');
        $tCustomerCodeTo   = $this->input->post('oetDLSCustomerCodeTo');
        $tConditionCustomerCode= " AND HD.FTCstCode BETWEEN '$tCustomerCodeFrom' AND '$tCustomerCodeTo' ";
      }else {
        $tConditionCustomerCode= " ";
      }
      if ($this->input->post('oetDLSCustomerCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->post('oetDLSCustomerCodeGroup');
        $tConditionSupplierGroup= " AND CSTHD.FTCgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->post('oetDLSCustomerCodeType')!="") {
        $tSupplierCodeType = $this->input->post('oetDLSCustomerCodeType');
        $tConditionSupplierType= " AND CSTHD.FTCtyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->post('oetDLSSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->post('oetDLSSupplierCodeClass');
        $tConditionSupplierClass= " AND CSTHD.FTClvCode ='$tSupplierCodeClass' ";
      }else {
        $tConditionSupplierClass= " ";
      }
      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'tSearchAll'    => $tSearchAll,
          'tCondition'    => $tConditionClassData,
          'tConditionDocDate' => $tConditionDocDate,
          'tConditionDealDate' => $tConditionDealDate,
          'tConditionCustomerCode' => $tConditionCustomerCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
      );
      $aDataList = $this->Delaystatus_model->FSoMDLSGetData($aData);
      $aGenTable  = array(
          'aDLSDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/delaystatus/wDelaystatusDataList',$aGenTable);
    }

    public function FSvCDLSSearchDataSuplDetail()
    {
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $tCstCode      = $this->input->post('ptCstCode');
      $tConditionClassData = " ";
      if ($this->input->post('ocmDLSClass')=="A") {
        $tConditionClassData = " ";
      }else {

        $aOption = explode("_",$this->input->post('ocmDLSClass'));
        // print_r($aOption);
        $nStart = $aOption[1];
        $nStop = $aOption[2];
        if ($aOption[0] == '1') {
          //ก่อนกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND FNXphCreditDay BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND FNXphCreditDay >= '$nStart'";
          }
        }else {
          //เลยกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND FNXshDelayDate BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND FNXshDelayDate >= '$nStart'";
          }
        }
          // print_r($tConditionClassData);


      }
      if ($this->input->post('oetDLSRefBilDocDateFrom') !="" && $this->input->post('oetDLSRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->post('oetDLSRefBilDocDateFrom');
        $tDateBillTo   = $this->input->post('oetDLSRefBilDocDateTo');
        $tConditionDocDate = " AND FDXshDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
        $tConditionDocDate = " ";
      }
      if ($this->input->post('oetDLSRefDealDateFrom') !="" && $this->input->post('oetDLSRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->post('oetDLSRefDealDateFrom');
        $tDateDealTo   = $this->input->post('oetDLSRefDealDateTo');
        $tConditionDealDate = " AND HD.FDXshDocDate BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->post('oetDLSCustomerCodeFrom')!="" && $this->input->post('oetDLSCustomerCodeTo') !="") {
        $tCustomerCodeFrom = $this->input->post('oetDLSCustomerCodeFrom');
        $tCustomerCodeTo   = $this->input->post('oetDLSCustomerCodeTo');
        $tConditionCustomerCode= " AND HD.FTCstCode BETWEEN '$tCustomerCodeFrom' AND '$tCustomerCodeTo' ";
      }else {
        $tConditionCustomerCode= " ";
      }
      if ($this->input->post('oetDLSCustomerCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->post('oetDLSCustomerCodeGroup');
        $tConditionSupplierGroup= " AND CSTHD.FTCgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->post('oetDLSCustomerCodeType')!="") {
        $tSupplierCodeType = $this->input->post('oetDLSCustomerCodeType');
        $tConditionSupplierType= " AND CSTHD.FTCtyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->post('oetDLSSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->post('oetDLSSupplierCodeClass');
        $tConditionSupplierClass= " AND CSTHD.FTClvCode ='$tSupplierCodeClass' ";
      }else {
        $tConditionSupplierClass= " ";
      }
      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'tSearchAll'    => $tSearchAll,
          'tCondition'    => $tConditionClassData,
          'tConditionDocDate' => $tConditionDocDate,
          'tConditionDealDate' => $tConditionDealDate,
          'tConditionCustomerCode' => $tConditionCustomerCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
          'ptCstCode'  => $tCstCode
      );
      $aDataList = $this->Delaystatus_model->FSoMDLSDTGetData($aData);
      $aGenTable  = array(
          'aDLSDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/delaystatus/wDelayDataListSupDetail',$aGenTable);
    }
    public function FSvCDLSExportExcel()
    {
      $tTitleReport = "ตรวจสอบสถานะลูกหนี้ค้างชำระ";
      $tFileName = $tTitleReport.'_'.date('YmdHis').'.xlsx';
      $oWriter = WriterEntityFactory::createXLSXWriter();

      $oWriter->openToBrowser($tFileName); // stream data directly to the browser

      $oBorder = (new BorderBuilder())
      ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
      ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
      ->build();

      $oStyleColums = (new StyleBuilder())
      ->setFontBold()
      ->setBorder($oBorder)
      ->build();
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->get('nPageCurrent') == '' || null)? 1 : $this->input->get('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");


      $tConditionClassData = " ";
      if ($this->input->get('ocmDLSClass')!=="") {
        $aOption = explode("_",$this->input->get('ocmDLSClass'));
        if ($aOption[0]=="2") {
          $nStart = $aOption[1];
          $tConditionClassData = " AND SPLPI.FNXphDueQtyLate >'$nStart'";
        }else if ($aOption[0]=="1") {
          $nStart = $aOption[1];
          $nStop = $aOption[2];
          $tConditionClassData = " AND SPLPI.FNXphDueQtyLate BETWEEN '$nStart' AND  '$nStop' ";
        }else {
          $tConditionClassData = " ";
        }
      }else {
        $tConditionClassData = " ";
      }
      if ($this->input->get('oetDLSRefBilDocDateFrom') !="" && $this->input->get('oetDLSRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->get('oetDLSRefBilDocDateFrom');
        $tDateBillTo   = $this->input->get('oetDLSRefBilDocDateTo');
        $tConditionDocDate = " AND DOCREF.FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->get('oetDLSRefDealDateFrom') !="" && $this->input->get('oetDLSRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->get('oetDLSRefDealDateFrom');
        $tDateDealTo   = $this->input->get('oetDLSRefDealDateTo');
        $tConditionDealDate = " AND CRD.FNCstCrTerm BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->get('ptCstCode')!="") {
        $tCustomerCodeFrom = $this->input->get('ptCstCode');
        $tConditionCustomerCode= " AND HD.FTCstCode = '$tCustomerCodeFrom' ";
      }else {
        $tConditionCustomerCode= " ";
      }
      if ($this->input->get('oetDLSCustomerCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->get('oetDLSCustomerCodeGroup');
        $tConditionSupplierGroup= " AND CSTHD.FTCgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->get('oetDLSCustomerCodeType')!="") {
        $tSupplierCodeType = $this->input->get('oetDLSCustomerCodeType');
        $tConditionSupplierType= " AND CSTHD.FTCtyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->get('oetDLSSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->get('oetDLSSupplierCodeClass');
        $tConditionSupplierClass= " AND CSTHD.FTClvCode ='$tSupplierCodeClass' ";
      }else {
        $tConditionSupplierClass= " ";
      }
      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'tSearchAll'    => $tSearchAll,
          'tCondition'    => $tConditionClassData,
          'tConditionDocDate' => $tConditionDocDate,
          'tConditionDealDate' => $tConditionDealDate,
          'tConditionCustomerCode' => $tConditionCustomerCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
      );
      $aDataList = $this->Delaystatus_model->FSoMDLSGetData($aData);

      $aCells = [

          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("ตรวจสอบสถานะลูกหนี้ค้างชำระ"),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells);
      $oWriter->addRow($singleRow);
      $aCells = [

          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells);
      $oWriter->addRow($singleRow);


      $aCells = [
          WriterEntityFactory::createCell("ลำดับ"),
          
          WriterEntityFactory::createCell("รหัสลูกค้า"),
          
          WriterEntityFactory::createCell("ชื่อลูกค้า"),
          
          WriterEntityFactory::createCell("ที่อยู่"),
          
          WriterEntityFactory::createCell("เบอร์โทร"),
          
          WriterEntityFactory::createCell("อีเมล์"),
          
          WriterEntityFactory::createCell("ยอดรวม"),
          
      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
      $oWriter->addRow($singleRow);

      if (isset($aDataList['raItems'])) {
        foreach ($aDataList['raItems'] as $nKey => $aValue) {
          $aCells = [
              WriterEntityFactory::createCell(($nKey+1)),
              
              WriterEntityFactory::createCell($aValue['FTCstCode']),
              
              WriterEntityFactory::createCell($aValue['FTCStName']),
              
              WriterEntityFactory::createCell($aValue['FTCstAddress']),
              
              WriterEntityFactory::createCell($aValue['FTCstTel']),
              
              WriterEntityFactory::createCell($aValue['FTCstEmail']),
              
              WriterEntityFactory::createCell($aValue['FCXshGrand']),
              
          ];

          /** add a row at a time */
          $singleRow = WriterEntityFactory::createRow($aCells);
          $oWriter->addRow($singleRow);


        }
      }

      $aCells = [

          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
      ];

      /** add a row at a time */

      $singleRow = WriterEntityFactory::createRow($aCells);
      $oWriter->addRow($singleRow);
      $tSearchAll     = $this->input->get('tSearchAll');
      $nPage          = ($this->input->get('nPageCurrent') == '' || null)? 1 : $this->input->get('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $tCstCode       = $this->input->get('ptCstCode');
      $tConditionClassData = " ";
      if ($this->input->get('ocmDLSClass')=="A") {


        $tConditionClassData = " ";


      }else {

        $aOption = explode("_",$this->input->get('ocmDLSClass'));
        $nStart = $aOption[1];
        $nStop = $aOption[2];
        if ($aOption[0] == '1') {
          //ก่อนกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND FNXphCreditDay BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND FNXphCreditDay >= '$nStart'";
          }

        }else {
          //เลยกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND FNXshDelayDate BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND FNXshDelayDate >= '$nStart'";
          }
        }

      }
      if ($this->input->get('oetDLSRefBilDocDateFrom') !="" && $this->input->get('oetDLSRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->get('oetDLSRefBilDocDateFrom');
        $tDateBillTo   = $this->input->get('oetDLSRefBilDocDateTo');
        $tConditionDocDate = " AND FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->get('oetDLSRefDealDateFrom') !="" && $this->input->get('oetDLSRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->get('oetDLSRefDealDateFrom');
        $tDateDealTo   = $this->input->get('oetDLSRefDealDateTo');
        $tConditionDealDate = " AND CRD.FNCstCrTerm BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->get('oetDLSCustomerCodeFrom')!="" && $this->input->get('oetDLSCustomerCodeTo') !="") {
        $tCustomerCodeFrom = $this->input->get('oetDLSCustomerCodeFrom');
        $tCustomerCodeTo   = $this->input->get('oetDLSCustomerCodeTo');
        $tConditionCustomerCode= " AND HD.FTCstCode BETWEEN '$tCustomerCodeFrom' AND '$tCustomerCodeTo' ";
      }else {
        $tConditionCustomerCode= " ";
      }
      if ($this->input->get('oetDLSCustomerCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->get('oetDLSCustomerCodeGroup');
        $tConditionSupplierGroup= " AND CSTHD.FTCgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->get('oetDLSCustomerCodeType')!="") {
        $tSupplierCodeType = $this->input->get('oetDLSCustomerCodeType');
        $tConditionSupplierType= " AND CSTHD.FTCtyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->get('oetDLSSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->get('oetDLSSupplierCodeClass');
        $tConditionSupplierClass= " AND CSTHD.FTClvCode ='$tSupplierCodeClass' ";
      }else {
        $tConditionSupplierClass= " ";
      }
      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'tSearchAll'    => $tSearchAll,
          'tCondition'    => $tConditionClassData,
          'tConditionDocDate' => $tConditionDocDate,
          'tConditionDealDate' => $tConditionDocDate,
          'tConditionCustomerCode' => $tConditionCustomerCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
          'ptCstCode'  => $tCstCode
      );
      $aDataList = $this->Delaystatus_model->FSoMDLSDTGetData($aData);
      $aGenTable  = array(
          'aDLSDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );

      $aCells = [
          WriterEntityFactory::createCell("ลำดับ"),
          
          WriterEntityFactory::createCell("สาขา"),
          
          WriterEntityFactory::createCell("เลขที่เอกสาร"),
          
          WriterEntityFactory::createCell("วันที่เอกสาร"),
          
          WriterEntityFactory::createCell("อ้างอิงเอกสารใบวางบิล"),
          
          WriterEntityFactory::createCell("วันที่ใบวางบิล"),
          
          WriterEntityFactory::createCell("ครบกำหนดชำระ"),
          
          WriterEntityFactory::createCell("จำนวนวันเลยกำหนดชำระ"),
          
          WriterEntityFactory::createCell("จำนวนเงิน"),
          
          WriterEntityFactory::createCell("ชำระแล้ว"),
          
          WriterEntityFactory::createCell("ค้างชำระ"),
          
      ];

      $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
      $oWriter->addRow($singleRow);

      if (isset($aDataList['raItems'])) {
        foreach ($aDataList['raItems'] as $nKey => $aValue) {
          if (($aValue['FNXshDelayDate'])>0) {
            $tTextColor = "#FF0000";
          }else {
            $tTextColor = "#000000";
          }
          $aCells = [
              WriterEntityFactory::createCell(($nKey+1)),
              
              WriterEntityFactory::createCell($aValue['FTBchName']),
              
              WriterEntityFactory::createCell($aValue['FTXshDocNo']),
              
              WriterEntityFactory::createCell($aValue['FDXshDocDate']),
              
              WriterEntityFactory::createCell($aValue['FTXshBillingNO']),
              
              WriterEntityFactory::createCell($aValue['FDXshBillingDate']),
              
              WriterEntityFactory::createCell($aValue['FDXshDueDate']),
              
              WriterEntityFactory::createCell($aValue['FNXshDelayDate']),
              
              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXshGrand'])),
              
              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXshPaid'])),
              
              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXshLeft'])),
              
          ];

          /** add a row at a time */
          $singleRow = WriterEntityFactory::createRow($aCells);
          $oWriter->addRow($singleRow);
        }
      }
      $oWriter->close();

    }





}


?>
