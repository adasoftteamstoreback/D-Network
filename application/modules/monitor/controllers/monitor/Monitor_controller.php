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
class Monitor_controller extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('monitor/monitor/Monitor_model');
        date_default_timezone_set("Asia/Bangkok");
    }
    public function index()
    {
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $aData  = array(
          'FNLngID'       => $nLangEdit,
      );

      $aDataList = $this->Monitor_model->FSoMMONGetDataOption($aData);
      $aGenDataOption  = array(
          'aDataList'      => $aDataList,
      );
      $this->load->view('monitor/monitor/wMonitor',$aGenDataOption);
    }
    public function FSvCMONListPage()
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
          'tConditionSupplierCode' => '',
          'tConditionCodeGroup' => '',
          'tConditionCodeType' => '',
          'tConditionCodeClass' => '',
      );
      $aDataList = $this->Monitor_model->FSoMMONGetData($aData);
      $aGenTable  = array(
          'aMONDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/monitor/wMonitorDataList',$aGenTable);
    }
    public function FSvCMONSearchData()
    {
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $tConditionClassData = " ";

      /*if ($this->input->post('ocmMONClass')!=="") {
        $aOption = explode("_",$this->input->post('ocmMONClass'));
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
      }*/

      $aOption  = explode("_",$this->input->post('ocmMONClass'));
      // print_r($aOption);
      // exit();
      if (isset($aOption[1])) {
        $nStart   = $aOption[1];
      }else {
        $nStart   = "";
      }
      if (isset($aOption[2])) {
        $nStop   = $aOption[2];
      }else {
        $nStop   = "";
      }
      //$nStop    = $aOption[2];
      if ($aOption[0] == '1') {
        //ก่อนกำหนด
        if ($nStart!='' && ($nStop!='' && $nStop !=0)) {
          $tConditionClassData = " AND SPLPI.FNXphCreditDay BETWEEN '$nStart' AND '$nStop' ";
        }else {
          $tConditionClassData = " AND SPLPI.FNXphCreditDay >= '$nStart'";
        }
      }else {
        //เลยกำหนด
        if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

          $tConditionClassData = " AND SPLPI.FNXphDueQtyLate BETWEEN '$nStart' AND '$nStop' ";
        }else {
          $tConditionClassData = " AND SPLPI.FNXphDueQtyLate >= '$nStart'";
        }
      }

      if ($this->input->post('oetMONRefBilDocDateFrom') !="" && $this->input->post('oetMONRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->post('oetMONRefBilDocDateFrom');
        $tDateBillTo   = $this->input->post('oetMONRefBilDocDateTo');
        $tConditionDocDate = " AND DOCREF.FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->post('oetMONRefDealDateFrom') !="" && $this->input->post('oetMONRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->post('oetMONRefDealDateFrom');
        $tDateDealTo   = $this->input->post('oetMONRefDealDateTo');
        $tConditionDealDate = " AND SPLD.FDXphDueDate BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->post('oetMONSupplierCodeFrom')!="" && $this->input->post('oetMONSupplierCodeTo') !="") {
        $tSupplierCodeFrom = $this->input->post('oetMONSupplierCodeFrom');
        $tSupplierCodeTo   = $this->input->post('oetMONSupplierCodeTo');
        $tConditionSupplierCode= " AND SPL.FTSplCode BETWEEN '$tSupplierCodeFrom' AND '$tSupplierCodeTo' ";
      }else {
        $tConditionSupplierCode= " ";
      }
      if ($this->input->post('oetMONSupplierCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->post('oetMONSupplierCodeGroup');
        $tConditionSupplierGroup= " AND SPL.FTSgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->post('oetMONSupplierCodeType')!="") {
        $tSupplierCodeType = $this->input->post('oetMONSupplierCodeType');
        $tConditionSupplierType= " AND SPL.FTStyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->post('oetMONSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->post('oetMONSupplierCodeClass');
        $tConditionSupplierClass= " AND SPL.FTSlvCode ='$tSupplierCodeClass' ";
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
          'tConditionSupplierCode' => $tConditionSupplierCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
      );
      $aDataList = $this->Monitor_model->FSoMMONGetData($aData);
      $aGenTable  = array(
          'aMONDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/monitor/wMonitorDataList',$aGenTable);
    }
    public function FSvCMONSearchDataSuplDetail()
    {
      $tSearchAll     = $this->input->post('tSearchAll');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $tSplCode      = $this->input->post('ptSplCode');
      $tConditionClassData = " ";
      if ($this->input->post('ocmMONClass')=="A") {


        $tConditionClassData = " ";


      }else {

        $aOption = explode("_",$this->input->post('ocmMONClass'));
        $nStart = $aOption[1];
        $nStop = $aOption[2];
        if ($aOption[0] == '1') {
          //ก่อนกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND TPI.FNXphCreditDay BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND TPI.FNXphCreditDay >= '$nStart'";
          }

        }else {
          //เลยกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND TPI.FNXphDueQtyLate BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND TPI.FNXphDueQtyLate >= '$nStart'";
          }
        }

      }
      if ($this->input->post('oetMONRefBilDocDateFrom') !="" && $this->input->post('oetMONRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->post('oetMONRefBilDocDateFrom');
        $tDateBillTo   = $this->input->post('oetMONRefBilDocDateTo');
        $tConditionDocDate = " AND FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->post('oetMONRefDealDateFrom') !="" && $this->input->post('oetMONRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->post('oetMONRefDealDateFrom');
        $tDateDealTo   = $this->input->post('oetMONRefDealDateTo');
        $tConditionDealDate = " AND TPI.FDXphDueDate BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->post('oetMONSupplierCodeFrom')!="" && $this->input->post('oetMONSupplierCodeTo') !="") {
        $tSupplierCodeFrom = $this->input->post('oetMONSupplierCodeFrom');
        $tSupplierCodeTo   = $this->input->post('oetMONSupplierCodeTo');
        $tConditionSupplierCode= " AND SPL.FTSplCode BETWEEN '$tSupplierCodeFrom' AND '$tSupplierCodeTo' ";
      }else {
        $tConditionSupplierCode= " ";
      }
      if ($this->input->post('oetMONSupplierCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->post('oetMONSupplierCodeGroup');
        $tConditionSupplierGroup= " AND SPL.FTSgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->post('oetMONSupplierCodeType')!="") {
        $tSupplierCodeType = $this->input->post('oetMONSupplierCodeType');
        $tConditionSupplierType= " AND SPL.FTStyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->post('oetMONSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->post('oetMONSupplierCodeClass');
        $tConditionSupplierClass= " AND SPL.FTSlvCode ='$tSupplierCodeClass' ";
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
          'tConditionSupplierCode' => $tConditionSupplierCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
          'tSplCode'  => $tSplCode
      );
      $aDataList = $this->Monitor_model->FSoMMONDTGetData($aData);
      $aGenTable  = array(
          'aMONDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );
      $this->load->view('monitor/monitor/wMonitorDataListSuplDetail',$aGenTable);
    }
    public function FSvCMONExportExcel()
    {
      $tTitleReport = "ตรวจสอบเจ้าหนี้ค้างจ่าย";
      $tFileName = $tTitleReport.'_'.date('YmdHis').'.xlsx';
      $oWriter = WriterEntityFactory::createXLSXWriter();

      $oWriter->openToBrowser($tFileName); // stream data directly to the browser

      // $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();  //เรียกฟังชั่นสร้างส่วนหัวรายงาน
      // $oWriter->addRows($aMulltiRow);

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
      if ($this->input->get('ocmMONClass')!=="") {
        $aOption = explode("_",$this->input->get('ocmMONClass'));
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
      if ($this->input->get('oetMONRefBilDocDateFrom') !="" && $this->input->get('oetMONRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->get('oetMONRefBilDocDateFrom');
        $tDateBillTo   = $this->input->get('oetMONRefBilDocDateTo');
        $tConditionDocDate = " AND DOCREF.FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->get('oetMONRefDealDateFrom') !="" && $this->input->get('oetMONRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->get('oetMONRefDealDateFrom');
        $tDateDealTo   = $this->input->get('oetMONRefDealDateTo');
        $tConditionDealDate = " AND SPLD.FDXphDueDate BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->get('ptSplCode')!="") {
        $tSupplierCodeFrom = $this->input->get('ptSplCode');
        $tConditionSupplierCode= " AND SPL.FTSplCode = '$tSupplierCodeFrom' ";
      }else {
        $tConditionSupplierCode= " ";
      }
      if ($this->input->get('oetMONSupplierCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->post('oetMONSupplierCodeGroup');
        $tConditionSupplierGroup= " AND SPL.FTSgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->get('oetMONSupplierCodeType')!="") {
        $tSupplierCodeType = $this->input->post('oetMONSupplierCodeType');
        $tConditionSupplierType= " AND SPL.FTStyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->get('oetMONSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->get('oetMONSupplierCodeClass');
        $tConditionSupplierClass= " AND SPL.FTSlvCode ='$tSupplierCodeClass' ";
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
          'tConditionSupplierCode' => $tConditionSupplierCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
      );
      $aDataList = $this->Monitor_model->FSoMMONGetData($aData);


      $aCells = [

          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("ตรวจสอบเจ้าหนี้ค้างจ่าย"),
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
          WriterEntityFactory::createCell("รหัสผู้จำหน่าย"),
          WriterEntityFactory::createCell("ชื่อผู้จำหน่าย"),
          WriterEntityFactory::createCell("ที่อยู่"),
          WriterEntityFactory::createCell("เบอร์โทร"),
          WriterEntityFactory::createCell("อีเมล์"),
          WriterEntityFactory::createCell("ยอดรวม"),
      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
      $oWriter->addRow($singleRow);
      $aSpl = array();
      if (isset($aDataList['raItems'])) {
        foreach ($aDataList['raItems'] as $nKey => $aValue) {
          $aCells = [
              WriterEntityFactory::createCell(($nKey+1)),
              WriterEntityFactory::createCell($aValue['FTSplCode']),
              WriterEntityFactory::createCell($aValue['FTSplName']),
              WriterEntityFactory::createCell($aValue['FTAddV2Desc1']),
              WriterEntityFactory::createCell($aValue['FTAddTaxNo']),
              WriterEntityFactory::createCell($aValue['FTSplEmail']),
              WriterEntityFactory::createCell($aValue['FCXphLeft']),

          ];
          $aSpl[$aValue['FTSplCode']] = $aValue['FTSplName'];
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
      $tSplCode      = $this->input->get('ptSplCode');
      $tConditionClassData = " ";
      if ($this->input->get('ocmMONClass')=="A") {


        $tConditionClassData = " ";


      }else {

        $aOption = explode("_",$this->input->get('ocmMONClass'));
        $nStart = $aOption[1];
        $nStop = $aOption[2];
        if ($aOption[0] == '1') {
          //ก่อนกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND TPI.FNXphCreditDay BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND TPI.FNXphCreditDay >= '$nStart'";
          }

        }else {
          //เลยกำหนด
          if ($nStart!='' && ($nStop!='' && $nStop !=0)) {

            $tConditionClassData = " AND TPI.FNXphDueQtyLate BETWEEN '$nStart' AND  '$nStop' ";
          }else {
            $tConditionClassData = " AND TPI.FNXphDueQtyLate >= '$nStart'";
          }
        }

      }
      if ($this->input->get('oetMONRefBilDocDateFrom') !="" && $this->input->get('oetMONRefBilDocDateTo') !="") {
        $tDateBillFrom = $this->input->get('oetMONRefBilDocDateFrom');
        $tDateBillTo   = $this->input->get('oetMONRefBilDocDateTo');
        $tConditionDocDate = " AND FDXshRefDocDate BETWEEN '$tDateBillFrom' AND '$tDateBillTo' ";
      }else {
        $tConditionDocDate = " ";
      }
      if ($this->input->get('oetMONRefDealDateFrom') !="" && $this->input->get('oetMONRefDealDateTo') !="") {
        $tDateDealFrom = $this->input->get('oetMONRefDealDateFrom');
        $tDateDealTo   = $this->input->get('oetMONRefDealDateTo');
        $tConditionDealDate = " AND TPI.FDXphDueDate BETWEEN '$tDateDealFrom' AND '$tDateDealTo' ";
      }else {
        $tConditionDealDate = " ";
      }
      if ($this->input->get('oetMONSupplierCodeFrom')!="" && $this->input->get('oetMONSupplierCodeTo') !="") {
        $tSupplierCodeFrom = $this->input->get('oetMONSupplierCodeFrom');
        $tSupplierCodeTo   = $this->input->get('oetMONSupplierCodeTo');
        $tConditionSupplierCode= " AND SPL.FTSplCode BETWEEN '$tSupplierCodeFrom' AND '$tSupplierCodeTo' ";
      }else {
        $tConditionSupplierCode= " ";
      }
      if ($this->input->get('oetMONSupplierCodeGroup')!="") {
        $tSupplierCodeGroup = $this->input->get('oetMONSupplierCodeGroup');
        $tConditionSupplierGroup= " AND SPL.FTSgpCode ='$tSupplierCodeGroup' ";
      }else {
        $tConditionSupplierGroup= " ";
      }

      if ($this->input->get('oetMONSupplierCodeType')!="") {
        $tSupplierCodeType = $this->input->get('oetMONSupplierCodeType');
        $tConditionSupplierType= " AND SPL.FTStyCode ='$tSupplierCodeType' ";
      }else {
        $tConditionSupplierType= " ";
      }

      if ($this->input->get('oetMONSupplierCodeClass')!="") {
        $tSupplierCodeClass = $this->input->get('oetMONSupplierCodeClass');
        $tConditionSupplierClass= " AND SPL.FTSlvCode ='$tSupplierCodeClass' ";
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
          'tConditionSupplierCode' => $tConditionSupplierCode,
          'tConditionCodeGroup' => $tConditionSupplierGroup,
          'tConditionCodeType' => $tConditionSupplierType,
          'tConditionCodeClass' => $tConditionSupplierClass,
          'tSplCode'  => $tSplCode
      );
      $aDataList = $this->Monitor_model->FSoMMONDTGetDataExcel($aData);
      $aGenTable  = array(
          'aMONDataList'      => $aDataList,
          'nPage'             => $nPage,
          'tSearchAll'        => $tSearchAll,
      );

      $aCells = [
          WriterEntityFactory::createCell("ลำดับ"),
          WriterEntityFactory::createCell("ชื่อผู้จำหน่าย"),
          WriterEntityFactory::createCell("สาขา"),
          WriterEntityFactory::createCell("เลขที่เอกสาร"),

          WriterEntityFactory::createCell("วันที่เอกสาร"),

          WriterEntityFactory::createCell("อ้างอิงเอกสารภายใน"),

          WriterEntityFactory::createCell("อ้างอิงเอกสารใบวางบิล"),

          WriterEntityFactory::createCell("วันที่ใบวางบิล"),

          WriterEntityFactory::createCell("ครบกำหนดชำระ"),

          WriterEntityFactory::createCell("จำนวนวันเลยกำหนดชำระ"),

          WriterEntityFactory::createCell("จำนวนเงิน"),

          WriterEntityFactory::createCell("ชำระแล้ว"),

          WriterEntityFactory::createCell("ค้างชำระ"),

      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
      $oWriter->addRow($singleRow);

      if (isset($aDataList['raItems'])) {
        foreach ($aDataList['raItems'] as $nKey => $aValue) {
          if (($aValue['FNXphDueQtyLate'])>0) {
            $tTextColor = "#FF0000";
          }else {
            $tTextColor = "#000000";
          }
          if (isset($aSpl[$aValue['FTSplCode']])) {
            $tSplName = $aSpl[$aValue['FTSplCode']];
          }else {
            $tSplName = $aValue['FTBchName'];
          }
          $aCells = [
              WriterEntityFactory::createCell(($nKey+1)),
              WriterEntityFactory::createCell($tSplName),
              WriterEntityFactory::createCell($aValue['FTBchName']),

              WriterEntityFactory::createCell($aValue['FTXphDocNo']),

              WriterEntityFactory::createCell($aValue['FDXphDocDate']),

              WriterEntityFactory::createCell($aValue['FTXshRefInt']),

              WriterEntityFactory::createCell($aValue['FTXshBillingNote']),

              WriterEntityFactory::createCell($aValue['FDXshBillingNoteDate']),

              WriterEntityFactory::createCell($aValue['FDXphDueDate']),

              WriterEntityFactory::createCell($aValue['FNXphDueQtyLate']),

              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXphGrand'])),

              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXphPaid'])),

              WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXphLeft'])),

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
