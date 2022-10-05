<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

date_default_timezone_set("Asia/Bangkok");

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;


class Rptcstlostcont_controller extends MX_Controller
{
    /**
     * ภาษา
     * @var array
     */
    public $aText = [];

    /**
     * จำนวนต่อหน้าในรายงาน
     * @var int
     */
    public $nPerPage = 100;

    /**
     * Page number
     * @var int
     */
    public $nPage = 1;

    /**
     * จำนวนทศนิยม
     * @var int
     */
    public $nOptDecimalShow = 2;

    /**
     * จำนวนข้อมูลใน Temp
     * @var int
     */
    public $nRows = 0;

    /**
     * Computer Name
     * @var string
     */
    public $tCompName;

    /**
     * User Login on Bch
     * @var string
     */
    public $tBchCodeLogin;

    /**
     * Report Code
     * @var string
     */
    public $tRptCode;

    /**
     * Report Group
     * @var string
     */
    public $tRptGroup;

    /**
     * System Language
     * @var int
     */
    public $nLngID;

    /**
     * User Session ID
     * @var string
     */
    public $tUserSessionID;

    /**
     * Report route
     * @var string
     */
    public $tRptRoute;

    /**
     * Report Export Type
     * @var string
     */
    public $tRptExportType;

    /**
     * Filter for Report
     * @var array
     */
    public $aRptFilter = [];

    /**
     * Company Info
     * @var array
     */
    public $aCompanyInfo = [];

    /**
     * User Login Session
     * @var string
     */
    public $tUserLoginCode;

    /**
     * User Login Session
     * @var string
     */
    public $tSysBchCode;

    public function __construct()
    {
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/Rptcstlostcont_model');
        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init()
    {
        $this->aText = [
            // Title

              'tTitleReport' => language('report/report/report', 'tRptCstLostContTitle'),
              'tDatePrint' => language('report/report/report', 'tRptTaxSalePosDatePrint'),
              'tTimePrint' => language('report/report/report', 'tRptTaxSalePosTimePrint'),

              'tRptTaxSalePosNoData' =>"ไม่มีข้อมูล",
              'tPdtnu'=> language('report/report/report', 'tPdtnu'),
              'tPdtCode'=> language('report/report/report', 'tPdtCode'),
              'tPdtName'=> language('report/report/report', 'tPdtName'),
              'tPgpChainName'=> language('report/report/report', 'tPgpChainName'),
              'tPtyName'=>language('report/report/report', 'tPtyName'),
              'tPdtSaleType'=> language('report/report/report', 'tPdtSaleType'),
              'tBarCode'=> language('report/report/report', 'tBarCode'),
              'tPunCode'=> language('report/report/report', 'tPunCode'),
              'tPunName'=> language('report/report/report', 'tPunName'),
              'tPdtUnitFact'=> language('report/report/report', 'tPdtUnitFact'),
              'tPdtPriceRET'=> language('report/report/report', 'tPdtPriceRET'),
              'tPdtCostInPerUnit'=> language('report/report/report', 'tPdtCostInPerUnit'),
              'tPdtCostInTotal'=> language('report/report/report', 'tPdtCostInTotal'),
              'tPgdPriceRetTotal'=> language('report/report/report', 'tPgdPriceRetTotal'),
              // Filter Heard Report
              'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
              'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
              'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
              'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
              'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
              'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
              'tRptCstFrom'           => language('report/report/report', 'tRptCstFrom'),
              'tRptCstTo'             => language('report/report/report', 'tRptCstTo'),

              'tRptRegCodeFrom'             => language('report/report/report', 'tRptCstLostContRegCodeFrom'),
              'tRptRegCodeTo'             => language('report/report/report', 'tRptCstLostContRegCodeTo'),
              'tRptLostCon'             => language('report/report/report', 'tRptCstLostContLostCon'),

              

            // Address Lang
            'tRptAddrBuilding' => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad' => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi' => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict' => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince' => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel' => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax' => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch' => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1' => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2' => language('report/report/report', 'tRptAddV2Desc2'),
            'tRptTel' => language('report/report/report', 'tRptAddrTel'),
            'tRptFaxNo' => language('report/report/report', 'tRptAddrFax'),
            // Table Label

            'tRptAdjMerChantFrom' => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo' => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom' => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo' => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom' => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo' => language('report/report/report', 'tRptAdjPosTo'),
            'tRptBranch' => language('report/report/report', 'tRptBranch'),
            'tRptTotal' => language('report/report/report', 'tRptTotal'),
            'tRPCTaxNo' => language('report/report/report', 'tRPCTaxNo'),
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
            'tRptMerFrom' => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo' => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom' => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo' => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom' => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo' => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom' => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo' => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAll' => language('report/report/report', 'tRptAll'),
            'tRptPdtType1' => language('report/report/report', 'tRptPdtType1'),
            'tRptPdtType2' => language('report/report/report', 'tRptPdtType2'),
            'tRptPdtType3' => language('report/report/report', 'tRptPdtType3'),
            'tRptPdtType4' => language('report/report/report', 'tRptPdtType4'),
            'tRptPdtType6' => language('report/report/report', 'tRptPdtType6'),
            'tRptBrandFrom'      => language('report/report/report', 'tRptBrandFrom'),
            'tRptBrandTo'        => language('report/report/report', 'tRptBrandTo'),
            'tRptModelFrom'      => language('report/report/report', 'tRptModelFrom'),
            'tRptModelTo'        => language('report/report/report', 'tRptModelTo'),

            'tRptPdtMoving1'        => language('report/report/report', 'tRptPdtMoving1'),
            'tRptPdtMoving2'        => language('report/report/report', 'tRptPdtMoving2'),
            'tRptTitlePdtMoving'        => language('report/report/report', 'tRptTitlePdtMoving'),
            'tRptStaVat'        => language('report/report/report', 'tRptStaVat'),
            'tRptStaVa1'        => language('report/report/report', 'tRptStaVa1'),
            'tRptStaVa2'        => language('report/report/report', 'tRptStaVa2'),
            'tRptNoData'            => language('report/report/report', 'tRptNoData'),






        ];

        $this->tSysBchCode = SYS_BCH_CODE;
        $this->tBchCodeLogin = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage = 100;
        $this->nOptDecimalShow = FCNxHGetOptionDecimalShow();

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        $this->nLngID = FCNaHGetLangEdit();
        $this->tRptCode = $this->input->post('ohdRptCode');
        $this->tRptGroup = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID = $this->session->userdata('tSesSessionID');
        $this->tRptRoute = $this->input->post('ohdRptRoute');
        $this->tRptExportType = $this->input->post('ohdRptTypeExport');
        $this->nPage = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode = $this->session->userdata('tSesUsername');

        // Report Filter
        $this->aRptFilter = [
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $tFullHost,
            'tRptCode' => $this->tRptCode,
            'nLangID' => $this->nLngID,

            'tTypeSelect' => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            'tAgnCode'          => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : '',
            // สาขา
            'tBchCodeFrom' => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom' => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo' => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo' => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect' => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect' => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll' => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            //ลูกค้าขาดการติดต่อ
            'tLostContNum'      => !empty($this->input->post('oetRptLostContNum')) ? $this->input->post('oetRptLostContNum') : "",

            //Filter ลูกค้า
            'tCstCodeFrom'      => !empty($this->input->post('oetRptCstCodeFrom')) ? $this->input->post('oetRptCstCodeFrom') : "",
            'tCstCodeNameFrom'  => !empty($this->input->post('oetRptCstNameFrom')) ? $this->input->post('oetRptCstNameFrom') : "",
            'tCstCodeTo'        => !empty($this->input->post('oetRptCstCodeTo')) ? $this->input->post('oetRptCstCodeTo') : "",
            'tCstCodeNameTo'    => !empty($this->input->post('oetRptCstNameTo')) ? $this->input->post('oetRptCstNameTo') : "", 

            //Filter ทะเบียนรถ
            'tRegCodeFrom'      => !empty($this->input->post('oetRptCarRegNoFrom')) ? $this->input->post('oetRptCarRegNoFrom') : "",
            'tRegCodeNameFrom'  => !empty($this->input->post('oetRptCarRegNoNameFrom')) ? $this->input->post('oetRptCarRegNoNameFrom') : "",
            'tRegCodeTo'        => !empty($this->input->post('oetRptCarRegNoTo')) ? $this->input->post('oetRptCarRegNoTo') : "",
            'tRegCodeNameTo'    => !empty($this->input->post('oetRptCarRegNoNameTo')) ? $this->input->post('oetRptCarRegNoNameTo') : "", 

        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index()
    {

        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {

            // Execute Stored Procedure
            $this->Rptcstlostcont_model->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
                'aDataFilter' => $this->aRptFilter
            ];
            $this->nRows = $this->Rptcstlostcont_model->FSnMCountRowInTemp($aCountRowParams);

            // Report Type
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptRenderExcel();
                    break;
                case 'pdf':
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * LastUpdate: -
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint()
    {
        try {
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
                'aDataFilter' => $this->aRptFilter
            ];
            $aDataReport = $this->Rptcstlostcont_model->FSaMGetDataReport($aDataReportParams);

            /** =========== End Get Data ===================================== */
            /** =========== Begin Render View ================================ */
            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo' => $this->aCompanyInfo,
                'aDataReport' => $aDataReport,
                'aDataTextRef' => $this->aText,
                'aDataFilter' => $this->aRptFilter
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptCstLostContHtml', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport' => $this->aText['tTitleReport'],
                'tRptTypeExport' => $this->tRptExportType,
                'tRptCode' => $this->tRptCode,
                'tRptRoute' => $this->tRptRoute,
                'tViewRenderKool' => $tRptView,
                'aDataFilter' => $this->aRptFilter,
                'aDataReport' => [
                    'raItems' => $aDataReport['aRptData'],
                    'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                    'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                    'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                    'rtCode' => '1',
                    'rtDesc' => 'success'
                ]
            ];
            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
            /** =========== End Render View ================================== */
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * LastUpdate: -
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage()
    {

        /** =========== Begin Init Variable ================================== */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** =========== End Init Variable ==================================== */
        $aDataWhere = array(
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => $this->nPage,
            'nRow' => $this->nPerPage,
            'nPerPage' => $this->nPerPage,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter' => $this->aRptFilter
        );

        // Get Data ReportFSaMGetDataReport
        $aDataReport = $this->Rptcstlostcont_model->FSaMGetDataReport($aDataWhere, $aDataFilter);

        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $this->aRptFilter
        ];
        $tViewRenderKool = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptCstLostContHtml', $aDataViewRptParams);


        // Data Viewer Center Report
        $aDataView = [
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewRenderKool,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => [
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            ]
        ];

        $this->load->view('report/report/wReportViewer', $aDataView);
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * LastUpdate: -
     * Return: Object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp()
    {
    }

    /**
     * Functionality: Send Rabbit MQ Report
     * Parameters:  Function Parameter
     * Creator: 15/09/2020 Piya
     * LastUpdate: -
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile()
    {
    }

       /**
     * Functionality: Render Excel Report
     * Parameters:  Function Parameter
     * Creator: 30/07/2020 Nattakit
     * LastUpdate:
     * Return: file
     * ReturnType: file
     */
    public function  FSvCCallRptRenderExcel()
    {
        $tFileName = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';

        $oWriter = WriterEntityFactory::createXLSXWriter();
        $oWriter->openToBrowser($tFileName); // stream data directly to the browser
        
        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();  //เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $oWriter->addRows($aMulltiRow);

        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleColums = (new StyleBuilder())
            ->setBorder($oBorder)
            ->setFontBold()
            ->build();

        $aCells = [
            WriterEntityFactory::createCell('วันที่เข้าบริการล่าสุด'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('ชื่อ'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('นามสกุล'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('เลขที่บิลที่ใช้บริการล่าสุด'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('ทะเบียนรถ'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('ระดับความพึงพอใจล่าสุด'),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell('ข้อมูลเบอร์ติดต่อ'),
            WriterEntityFactory::createCell(NULL),
        ];


        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        //  ===========================================================================================



        $aDataReportParams = [
            'nPerPage'      => 999999999999,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        ];

        //Get Data
        $aDataReport = $this->Rptcstlostcont_model->FSaMGetDataReport($aDataReportParams);

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {

                $tDocDate       = date("d/m/Y", strtotime($aValue['FDFlwLastDate']));

              $values = [
                  WriterEntityFactory::createCell($tDocDate),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FTCstName']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FTCstLastName']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FTFlwDocRef']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FTCarRegNo']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FNXshScoreValue']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell($aValue['FTCstTel']),
                  WriterEntityFactory::createCell(NULL),
                  WriterEntityFactory::createCell(NULL)
              ];
              $aRow = WriterEntityFactory::createRow($values);
              $oWriter->addRow($aRow);
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);

        $oWriter->close();
    }

    /**
     * Functionality: Render Excel Report Header
     * Parameters:  Function Parameter
     * Creator: 30/07/2020 Nattakit
     * LastUpdate:
     * Return: oject
     * ReturnType: oject
     */
    public function FSoCCallRptRenderHedaerExcel()
    {
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo)>0) {
            $tFTAddV1Village = $this->aCompanyInfo['FTAddV1Village'];
            $tFTCmpName = $this->aCompanyInfo['FTCmpName'];
            $tFTAddV1No = $this->aCompanyInfo['FTAddV1No'];
            $tFTAddV1Road = $this->aCompanyInfo['FTAddV1Road'];
            $tFTAddV1Soi = $this->aCompanyInfo['FTAddV1Soi'];
            $tFTSudName = $this->aCompanyInfo['FTSudName'];
            $tFTDstName = $this->aCompanyInfo['FTDstName'];
            $tFTPvnName = $this->aCompanyInfo['FTPvnName'];
            $tFTAddV1PostCode = $this->aCompanyInfo['FTAddV1PostCode'];
            $tFTAddV2Desc1 = $this->aCompanyInfo['FTAddV2Desc1'];
            $tFTAddV2Desc2 = $this->aCompanyInfo['FTAddV2Desc2'];
            $tFTAddVersion = $this->aCompanyInfo['FTAddVersion'];
            $tFTBchName = $this->aCompanyInfo['FTBchName'];
            $tFTAddTaxNo = $this->aCompanyInfo['FTAddTaxNo'];
            $tFTCmpTel = $this->aCompanyInfo['FTAddTel'];
            $tRptFaxNo = $this->aCompanyInfo['FTAddFax'];
        }else {
            $tFTCmpTel = "";
            $tFTCmpName = "";
            $tFTAddV1No = "";
            $tFTAddV1Road = "";
            $tFTAddV1Soi = "";
            $tFTSudName = "";
            $tFTDstName = "";
            $tFTPvnName = "";
            $tFTAddV1PostCode = "";
            $tFTAddV2Desc1 = "1"; $tFTAddV1Village = "";
            $tFTAddV2Desc2 = "2";
            $tFTAddVersion = "";
            $tFTBchName = "";
            $tFTAddTaxNo = "";
            $tRptFaxNo = "";
        }
        $oStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell($tFTCmpName),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell($this->aText['tTitleReport']),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyle);

        $tAddress = '';
        if ($tFTAddVersion == '1') {
            $tAddress = $tFTAddV1No . ' ' .$tFTAddV1Village. ' '.$tFTAddV1Road.' ' . $tFTAddV1Soi . ' ' . $tFTSudName . ' ' . $tFTDstName . ' ' . $tFTPvnName . ' ' . $tFTAddV1PostCode;
        }
        if ($tFTAddVersion == '2') {
            $tAddress = $tFTAddV2Desc1 . ' ' . $tFTAddV2Desc2;
        }

        $aCells = [
            WriterEntityFactory::createCell($tAddress),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptTel'] . ' ' . $tFTCmpTel . ' '.$this->aText['tRptFaxNo'] . ' ' . $tRptFaxNo),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[]  = WriterEntityFactory::createRow($aCells);



        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptAddrBranch'] . ' ' . $tFTBchName),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);


        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRPCTaxNo'] . ' : ' . $tFTAddTaxNo),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);


        $aCells = [
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        return $aMulltiRow;
    }

    /**
     * Functionality: Render Excel Report Footer
     * Parameters:  Function Parameter
     * Creator: 30/07/2020 Nattakit
     * LastUpdate:
     * Return: oject
     * ReturnType: oject
     */
    public function FSoCCallRptRenderFooterExcel()
    {

        $oStyleFilter = (new StyleBuilder())
            ->setFontBold()
            ->build();
        $aCells = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport']),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        // สาขา
        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ลูกค้า
        if (!empty($this->aRptFilter['tCstCodeFrom']) && !empty($this->aRptFilter['tCstCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $this->aRptFilter['tCstCodeNameFrom'] . '     ' . $this->aText['tRptCstTo'] . ' : ' . $this->aRptFilter['tCstCodeNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ฟิวเตอร์ข้อมูล ทะเบียนรถ =========================================== -->
        if ((isset($this->aRptFilter['tRegCodeFrom']) && !empty($this->aRptFilter['tRegCodeFrom'])) && (isset($this->aRptFilter['tRegCodeTo']) && !empty($this->aRptFilter['tRegCodeTo']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptRegCodeFrom'] . ' : ' . $this->aRptFilter['tRegCodeNameFrom'] . ' ' . $this->aText['tRptRegCodeTo'] . ' : ' . $this->aRptFilter['tRegCodeNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ระดับวันครบ =========================================== -->
        if ((isset($this->aRptFilter['tLostContNum']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptLostCon'] . ' : ' . $this->aRptFilter['tLostContNum']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }
//tLostContNum
        return $aMulltiRow;
    }
}
