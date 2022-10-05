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
class Productaliveatpurchase_controller extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('monitor/productaliveatpurchase/Productaliveatpurchase_model');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
      $this->load->view('monitor/productaliveatpurchase/wProductaliveatpurchase');
    }

    public function FSvCPAPListPage()
    {
      $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
      $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");
      $nDecimal       = FCNxHGetOptionDecimalShow();

      $aData  = array(
          'nPage'         => $nPage,
          'nRow'          => 10,
          'FNLngID'       => $nLangEdit,
          'aAdvanceSearch'    => $aAdvanceSearch
      );
      $aDataList = $this->Productaliveatpurchase_model->FSoMMONGetData($aData);

      $aGenTable  = array(
          'aPAPDataList'      => $aDataList,
          'nPage'             => $nPage,
          'nDecimal'          => $nDecimal,
          'FNLngID'           => $nLangEdit
      );

      $this->load->view('monitor/productaliveatpurchase/wProductaliveatpurchaseDataList',$aGenTable);
    }

    public function FSvCPAPGetSplBuyList()
    {
      $nLangEdit      = $this->session->userdata("tLangEdit");

      $aData  = array(
        'FNLngID'     => $nLangEdit,
        'tPdtCode'    => $this->input->post('ptPdtCode'),
        'aPdtCode'    => ''
      );
      $aDataList = $this->Productaliveatpurchase_model->FSoMMONGetSplBuyList($aData);
      $aDataAlwPo = $this->Productaliveatpurchase_model->FSoMMONGetDataAlwPo($aData);

      $aGenTable  = array(
          'aPAPSplDataList'      => $aDataList,
          'aPAPAlwPo'            => $aDataAlwPo
      );

      $this->load->view('monitor/productaliveatpurchase/wProductaliveatpurchaseSplBuyList',$aGenTable);
    }

    public function FSvCPAPExportExcel()
    {
      $aAdvanceSearch = array(
        'tAgnCode'          => $this->input->get('oetPAPAgnCode'),
        'tBchCode'          => $this->input->get('oetPAPBchCode'),
        'tWahCode'          => $this->input->get('oetPAPWahCode'),
        'tPdtCode'          => $this->input->get('oetPAPPdtCode'),
        'tGrpProductCode'   => $this->input->get('oetPAPGrpProductCode'),
        'tProducBrandCode'  => $this->input->get('oetPAPProducBrandCode'),
        'tProducModelCode'  => $this->input->get('oetPAPProducModelCode'),
        'tProductTypeCode'  => $this->input->get('oetPAPProductTypeCode'),
        'nStaOrder'         => $this->input->get('nStaOrder'),
      );

      $nPage          = ($this->input->get('nPageCurrent') == '' || null)? 1 : $this->input->get('nPageCurrent');   // Check Number Page
      $nLangResort    = $this->session->userdata("tLangID");
      $nLangEdit      = $this->session->userdata("tLangEdit");

      $aData  = array(
          'nPage'             => $nPage,
          'nRow'              => 10,
          'FNLngID'           => $nLangEdit,
          'aAdvanceSearch'    => $aAdvanceSearch,
      );


      $aDataList = $this->Productaliveatpurchase_model->FSoMMONGetData($aData);
      $aPdtCode = array();
      foreach ($aDataList['aItems'] as $item) {
        $aPdtCode[] = $item['FTPdtCode'];
      }

      $aDataSpl  = array(
        'FNLngID'     => $nLangEdit,
        'tPdtCode'    => '',
        'aPdtCode'    => "'" . implode ( "', '", $aPdtCode ) . "'"
      );

      $aDataSplList = $this->Productaliveatpurchase_model->FSoMMONGetSplBuyList($aDataSpl);

      $tTitleReport = "ตรวจสอบสินค้าถึงจุดสั่งซื้อ";
      $tFileName = $tTitleReport.'_'.date('YmdHis').'.xlsx';
      $oWriter = WriterEntityFactory::createXLSXWriter();

      $oWriter->openToBrowser($tFileName); // stream data directly to the browser

      // Sheet ที่ 1 ============================================================================
      $oSheet = $oWriter->getCurrentSheet();
      $oSheet->setName('ตรวจสอบสินค้าถึงจุดสั่งซื้อ');

      $oBorder = (new BorderBuilder())
      ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
      ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
      ->build();

      $oStyleColums = (new StyleBuilder())
      ->setFontBold()
      ->setBorder($oBorder)
      ->setBackgroundColor(Color::LIGHT_BLUE)
      ->build();

      $aCells = [

          WriterEntityFactory::createCell("ตรวจสอบสินค้าถึงจุดสั่งซื้อ"),
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
          WriterEntityFactory::createCell("สาขา"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("รหัสสินค้า"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("ชื่อสินค้า"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("กลุ่มสินค้า"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("ยี่ห้อ"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("รุ่น"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("ชื่อคลังสินค้า"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("จำนวนคงเหลือล่าสุด"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("จำนวนขายต่อวัน(เฉลี่ย)"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("จำนวนต่ำสุด"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("จำนวนแนะนำสั่งซื้อ"),
          //WriterEntityFactory::createCell(null),
          WriterEntityFactory::createCell("จำนวนสั่งซื้อเหมาะสม"),
          //WriterEntityFactory::createCell(null),
      ];

      /** add a row at a time */
      $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
      $oWriter->addRow($singleRow);

      if (isset($aDataList['aItems'])) {
        foreach ($aDataList['aItems'] as $nKey => $aValue) {
          $aCells = [
              WriterEntityFactory::createCell($aValue['FTBchName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTPdtCode']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTPdtName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTPgpName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTPbnName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTPmoName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FTWahName']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FCStkQty']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FCPdtDailyUseAvg']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FCPdtMin']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FCPdtQtyOrdBuy']),
              //WriterEntityFactory::createCell(null),
              WriterEntityFactory::createCell($aValue['FCPdtQtySugges']),
              //WriterEntityFactory::createCell(null),
          ];

          /** add a row at a time */
          $singleRow = WriterEntityFactory::createRow($aCells);
          $oWriter->addRow($singleRow);
        }
      }

      //Sheet ที่ 2 ========================================================================

      $onewSheet = $oWriter->addNewSheetAndMakeItCurrent();
      $onewSheet->setName('แหล่งซื้อ');
      $onewSheet = $oWriter->getCurrentSheet();

      $aCells = [

        WriterEntityFactory::createCell("แหล่งจัดซื้อ"),
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
        WriterEntityFactory::createCell("บาร์โค้ด"),
        //WriterEntityFactory::createCell(null),
        WriterEntityFactory::createCell("รหัสสินค้า"),
        //WriterEntityFactory::createCell(null),
        WriterEntityFactory::createCell("ชื่อสินค้า"),
        //WriterEntityFactory::createCell(null),
        WriterEntityFactory::createCell("ชื่อผู้จำหน่าย"),
        //WriterEntityFactory::createCell(null),
        WriterEntityFactory::createCell("ติดต่อ"),
        //WriterEntityFactory::createCell(null),
        WriterEntityFactory::createCell("เบอร์โทร"),
        //WriterEntityFactory::createCell(null),
    ];

    /** add a row at a time */
    $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
    $oWriter->addRow($singleRow);

    if (isset($aDataSplList['aItems'])) {
      foreach ($aDataSplList['aItems'] as $nKey => $aValue) {
        $aCells = [
            WriterEntityFactory::createCell($aValue['FTBarCode']),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($aValue['FTPdtCode']),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($aValue['FTPdtName']),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($aValue['FTSplName']),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($aValue['FTCtrName']),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($aValue['FTCtrTel']),
            //WriterEntityFactory::createCell(null),
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
