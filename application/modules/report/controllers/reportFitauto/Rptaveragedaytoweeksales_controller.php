<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';
include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Rptaveragedaytoweeksales_controller extends MX_Controller
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
     * Sys Bch Code
     * @var string
     */
    public $tSysBchCode;


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/Rptaveragedaytoweeksales_model');

        // Init Report
        $this->init();
        parent::__construct();
    }


    private function init()
    {
        $this->aText = [
            'tTitleReport'              => language('report/report/report', 'tRptaveragedaytoweeksales'),
            'tDatePrint'                => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'                => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            'tRptAddrBuilding'          => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'              => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'               => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'       => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'          => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'          => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'               => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'               => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'            => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'            => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'            => language('report/report/report', 'tRptAddV2Desc2'),
            'tRPCTaxNo'                 => language('report/report/report', 'tRPCTaxNo'),
            'tRptFaxNo'                 => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'                   => language('report/report/report', 'tRptTel'),
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),
            'tRptNoData'                => language('report/report/report', 'tRptNoData'),
            'tRptTaxSalePosFilterDocDateFrom'           => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo'             => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            'tRptSaleByPaymentDetailFilterPayTypeFrom'  => language('report/report/report', 'tRptSaleByPaymentDetailFilterPayTypeFrom'),
            'tRptSaleByPaymentDetailFilterPayTypeTo'    => language('report/report/report', 'tRptSaleByPaymentDetailFilterPayTypeTo'),
            'tRptAdjDateFrom'           => language('report/report/report', 'tRptAdjDateFrom'),
            'tRptAdjDateTo'             => language('report/report/report', 'tRptAdjDateTo'),
            'tRptRcvFromFrom'           => language('report/report/report', 'tRptSalByPaymentRcvFrom'),
            'tRptRcvFromTo'             => language('report/report/report', 'tRptSalByPaymentRcvFromTo'),
            'tRptBchFrom'               => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                 => language('report/report/report', 'tRptBchTo'),
            'tRptDate'                  => language('report/report/report', 'tRptDate'),
            'tRptDetailProduct'         => language('report/report/report', 'tRptDetailProduct')
        ];

        $this->tSysBchCode      = SYS_BCH_CODE;
        $this->tBchCodeLogin    = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage         = 100;
        $this->nOptDecimalShow  = FCNxHGetOptionDecimalShow();
        $tIP                    = $this->input->ip_address();
        $tFullHost              = gethostbyaddr($tIP);
        $this->tCompName        = $tFullHost;
        $this->nLngID           = FCNaHGetLangEdit();
        $this->tRptCode         = $this->input->post('ohdRptCode');
        $this->tRptGroup        = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID   = $this->session->userdata('tSesSessionID');
        $this->tRptRoute        = $this->input->post('ohdRptRoute');
        $this->tRptExportType   = $this->input->post('ohdRptTypeExport');
        $this->nPage            = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode   = $this->session->userdata('tSesUsername');

        // Report Fillter
        $this->aRptFilter = [
            'tSessionID'    => $this->tUserSessionID,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,
            'tTypeSelect'   => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter Agency (ตัวแทนขาย)
            'tAgnCodeSelect' => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Filter BCH (สาขา)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter Document Date (วันที่สร้างเอกสาร)
            'tDocDateFrom'      => (empty($this->input->post('oetRptDocDateFrom'))) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'        => (empty($this->input->post('oetRptDocDateTo'))) ? '' : $this->input->post('oetRptDocDateTo'),

            // จากประเภทชำระเงิน
            'tRcvCodeFrom'      => !empty($this->input->post('oetRptRcvCodeFrom')) ? $this->input->post('oetRptRcvCodeFrom') : '',
            'tRcvNameFrom'      => !empty($this->input->post('oetRptRcvNameFrom')) ? $this->input->post('oetRptRcvNameFrom') : '',
            'tRcvCodeTo'        => !empty($this->input->post('oetRptRcvCodeTo')) ? $this->input->post('oetRptRcvCodeTo') : '',
            'tRcvNameTo'        => !empty($this->input->post('oetRptRcvNameTo')) ? $this->input->post('oetRptRcvNameTo') : '',

            //Fillter ข้อมูลสินค้า
            'tSubByCodeSelect'  => !empty($this->input->post('ocmRptSubBy')) ? $this->input->post('ocmRptSubBy') : "",

            //วันจันทร์ - อาทิตย์
            'tRptDayOfWeekFrm'  => !empty($this->input->post('ocmRptDayOfWeekFrm')) ? $this->input->post('ocmRptDayOfWeekFrm') : "",
            'tRptDayOfWeekTo'   => !empty($this->input->post('ocmRptDayOfWeekTo')) ? $this->input->post('ocmRptDayOfWeekTo') : "",
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
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            // Execute Stored Procedure
            $this->Rptaveragedaytoweeksales_model->FSnMExecStoreReport($this->aRptFilter);

            $aDataSwitchCase = array(
                'ptRptRoute'        => $this->tRptRoute,
                'ptRptCode'         => $this->tRptCode,
                'ptRptTypeExport'   => $this->tRptExportType,
                'paDataFilter'      => $this->aRptFilter
            );

            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint($aDataSwitchCase);
                    break;
                case 'excel':
                    $this->FSvCCallRptExportFile($aDataSwitchCase);
                    break;
                    // case 'pdf':
                    //     $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    //     break;
            }
        }
    }

    // Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 25/08/2021 Supawat
    // LastUpdate: -
    // Return: View Report Viewersd
    // ReturnType: View
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase)
    {
        try {
            $aDataWhere  = array(
                'tUsrSessionID' => $this->tUserSessionID,
                'tCompName'     => $this->tCompName,
                'tUserCode'     => $this->tUserLoginCode,
                'tRptCode'      => $this->tRptCode,
                'nPage'         => 1, // เริ่มทำงานหน้าแรก
                'nPerPage'      => $this->nPerPage
            );

            $aDataReport = $this->Rptaveragedaytoweeksales_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptaveragedaytoweeksalesHtml', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport'      => $this->aText['tTitleReport'],
                'tRptTypeExport'    => $this->tRptExportType,
                'tRptCode'          => $this->tRptCode,
                'tRptRoute'         => $this->tRptRoute,
                'tViewRenderKool'   => $tRptView,
                'aDataFilter'       => $this->aRptFilter,
                'aDataReport'       => [
                    'raItems'       => $aDataReport['aRptData'],
                    'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                    'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                    'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                    'rtCode'        => '1',
                    'rtDesc'        => 'success'
                ]
            ];

            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 25/08/2021 Supawat
    // LastUpdate:
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptViewBeforePrintClickPage()
    {

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'  => $this->nPerPage,
            'nPage'     => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->Rptaveragedaytoweeksales_model->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptaveragedaytoweeksalesHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport'      => $this->aText['tTitleReport'],
            'tRptTypeExport'    => $this->tRptExportType,
            'tRptCode'          => $this->tRptCode,
            'tRptRoute'         => $this->tRptRoute,
            'tViewRenderKool'   => $tRptView,
            'aDataFilter'       => $aDataFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nRowIDEnd'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            )
        );

        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
    }


    //ตรวจสอบก่อนพิมพ์ว่ามีจำนวนไหม
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase)
    {
        try {
            $aDataCountData = [
                'tCompName'     => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode'      => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tSessionID'    => $paDataSwitchCase['paDataFilter']['tSessionID'],
            ];

            $nDataCountPage = $this->Rptaveragedaytoweeksales_model->FSnMCountDataReportAll($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent'     => 1,
                'tMessage'      => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    public function FSvCCallRptExportFile()
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
            ->build();

        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenSysAdminBchCode')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenSysAdminBchName')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRowNumber')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptaveragedaytoweeksalesDayWeek')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptInventoriesByBchPrice')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptaveragedaytoweeksalesQTYSales')),
            WriterEntityFactory::createCell('% / ' . language('report/report/report', 'tRptaveragedaytoweeksalesQTYSales')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptaveragedaytoweeksalesGoleSale')),
            WriterEntityFactory::createCell('% / ' . language('report/report/report', 'tRptaveragedaytoweeksalesGoleSale')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDisChg')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptaveragedaytoweeksalesGoleSaleTotal')),
            WriterEntityFactory::createCell('% / ' . language('report/report/report', 'tRptaveragedaytoweeksalesGoleSaleTotal')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptaveragedaytoweeksalesAvgrageSalesPerPrice')),
            WriterEntityFactory::createCell(NULL),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage'      => 999999999999,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        ];

        $aDataReport = $this->Rptaveragedaytoweeksales_model->FSaMGetDataReport($aDataReportParams);

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {

            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $tDay = '';
                if ($aValue['FTXshDayOfWeek'] == 1) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
                } else if ($aValue['FTXshDayOfWeek'] == 2) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
                } else if ($aValue['FTXshDayOfWeek'] == 3) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
                } else if ($aValue['FTXshDayOfWeek'] == 4) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
                } else if ($aValue['FTXshDayOfWeek'] == 5) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
                } else if ($aValue['FTXshDayOfWeek'] == 6) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
                } else if ($aValue['FTXshDayOfWeek'] == 7) {
                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
                }

                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchCode']),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell($aValue['SumSub']),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell($tDay),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshUnitPrice'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshQty'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByQty'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshTotal'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByTotal'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshDisChg'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshGrand'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByGrand'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(NULL),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshSalAvgByQty'], $this->nOptDecimalShow) ),
                    WriterEntityFactory::createCell(NULL),

                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter

                    $values = [
                        WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceSumFooter')),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshQty_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByQty_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshTotal_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByTotal_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshDisChg_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshGrand_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshPercentByGrand_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshSalAvgByQty_Footer'], $this->nOptDecimalShow) ),
                        WriterEntityFactory::createCell(NULL),
    
                    ];
                    $aRow = WriterEntityFactory::createRow($values, $oStyleColums);
                    $oWriter->addRow($aRow);
                }
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);

        $oWriter->close();
    }

    public function FSoCCallRptRenderHedaerExcel()
    {
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo) > 0) {
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
        } else {
            $tFTCmpTel = "";
            $tFTCmpName = "";
            $tFTAddV1No = "";
            $tFTAddV1Road = "";
            $tFTAddV1Soi = "";
            $tFTSudName = "";
            $tFTDstName = "";
            $tFTPvnName = "";
            $tFTAddV1PostCode = "";
            $tFTAddV2Desc1 = "1";
            $tFTAddV1Village = "";
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
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell($this->aText['tTitleReport']),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyle);

        $tAddress = '';
        if ($tFTAddVersion == '1') {
            $tAddress = $tFTAddV1No . ' ' . $tFTAddV1Village . ' ' . $tFTAddV1Road . ' ' . $tFTAddV1Soi . ' ' . $tFTSudName . ' ' . $tFTDstName . ' ' . $tFTPvnName . ' ' . $tFTAddV1PostCode;
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
            WriterEntityFactory::createCell($this->aText['tRptTel'] . ' ' . $tFTCmpTel . ' ' . $this->aText['tRptFaxNo'] . ' ' . $tRptFaxNo),
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


        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell($this->aText['tRptAdjDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptAdjDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

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

        // ข้อมูลสินค้า
        if (!empty($this->aRptFilter['tSubByCodeSelect']) && !empty($this->aRptFilter['tSubByCodeSelect'])) {
            if($this->aRptFilter['tSubByCodeSelect'] == 'PdtType'){
                $tSubBy = language('report/report/report', 'tRptGrpPdtType');
            }else if($this->aRptFilter['tSubByCodeSelect'] == 'PdtBrand'){
                $tSubBy = language('report/report/report', 'tRptGrpPdtBrand');
            }else if($this->aRptFilter['tSubByCodeSelect'] == 'PdtModel'){
                $tSubBy = language('report/report/report', 'tRptGrpPdtModel');
            }else if($this->aRptFilter['tSubByCodeSelect'] == 'PdtChain'){
                $tSubBy = language('report/report/report', 'tRptGrpPdtGroup');
            }
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptDetailProduct'] . ' : ' . $tSubBy),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // วัน
        if (!empty($this->aRptFilter['tRptDayOfWeekFrm']) && !empty($this->aRptFilter['tRptDayOfWeekFrm'])) {
            if ($this->aRptFilter['tRptDayOfWeekFrm'] == 1) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 2) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 3) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 4) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 5) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 6) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
            } else if ($this->aRptFilter['tRptDayOfWeekFrm'] == 7) {
                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
            }

            if ($this->aRptFilter['tRptDayOfWeekTo'] == 1) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 2) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 3) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 4) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 5) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 6) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
            } else if ($this->aRptFilter['tRptDayOfWeekTo'] == 7) {
                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
            }
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptDate'] . ' : ' . $tDayFrm . ' - ' . $tDayTo),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }


        return $aMulltiRow;
    }
}
