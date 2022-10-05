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

class cRptRentAmountDetail extends MX_Controller {

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

    public function __construct() {
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportlocker/mRptRentAmountDetail');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport'  => language('report/report/report', 'tRptRentAmtForDetailTitle'),
            'tDatePrint'    => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'    => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            // Address Language
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
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter' => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptFaxNo' => language('report/report/report', 'tRptFaxNo'),
            'tRptTel' => language('report/report/report', 'tRptTel'),
            'tRPCTaxNo' => language('report/report/report', 'tRPCTaxNo'),
            // Address Lang
            'tRptAddrBuilding'      => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'          => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'           => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'   => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'      => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'      => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'           => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'           => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrTaxNo'         => language('report/report/report', 'tRptAddrTaxNo'),
            'tRptAddrBranch'        => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'        => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'        => language('report/report/report', 'tRptAddV2Desc2'),
            // Table Label
            'tRptRentAmtForDetailSerailPos' => language('report/report/report', 'tRptRentAmtForDetailSerailPos'),
            'tRptRentAmtForDetailUser' => language('report/report/report', 'tRptRentAmtForDetailUser'),
            'tRptRentAmtForDetailDocno' => language('report/report/report', 'tRptRentAmtForDetailDocno'),
            'tRptRentAmtForDetailDocDate' => language('report/report/report', 'tRptRentAmtForDetailDocDate'),
            'tRptRentAmtForDetailRack' => language('report/report/report', 'tRptRentAmtForDetailRack'),
            'tRptRentAmtForDetailSubRack' => language('report/report/report', 'tRptRentAmtForDetailSubRack'),
            'tRptRentAmtForDetailSize' => language('report/report/report', 'tRptRentAmtForDetailSize'),
            'tRptRentAmtForDetailDateGet' => language('report/report/report', 'tRptRentAmtForDetailDateGet'),
            'tRptRentAmtForDetailLoginTo' => language('report/report/report', 'tRptRentAmtForDetailLoginTo'),
            'tRptRentAmtForDetailStaPayment' => language('report/report/report', 'tRptRentAmtForDetailStaPayment'),
            'tRptRentAmtForDetailAmtPayment' => language('report/report/report', 'tRptRentAmtForDetailAmtPayment'),
            'tRptTaxSalePosTel' => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTaxSalePosFax' => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptTaxSalePosBch' => language('report/report/report', 'tRptTaxSalePosBch'),
            // No Data Report
            'tRptAdjStkNoData' => language('common/main/main', 'tCMNNotFoundData'),
            // in report
            'tRptRentAmtForDetailStaPaymentNoPay'       => language('report/report/report', 'tRptRentAmtForDetailStaPaymentNoPay'),
            'tRptRentAmtForDetailStaPaymentSome'        => language('report/report/report', 'tRptRentAmtForDetailStaPaymentSome'),
            'tRptRentAmtForDetailStaPaymentAlready'     => language('report/report/report', 'tRptRentAmtForDetailStaPaymentAlready'),
            'tRptRentAmtForDetailSumText'               => language('report/report/report', 'tRptRentAmtForDetailSumText'),
            'tRptRentAmtForDetailSumTextLast'           => language('report/report/report', 'tRptRentAmtForDetailSumTextLast'),
            // report filter
            'tRptBchFrom'   => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'     => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom'  => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'    => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'   => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'     => language('report/report/report', 'tRptPosTo'),
            'tRptDateFrom'  => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'    => language('report/report/report', 'tRptDateTo'),
            'tRptRackFrom'  => language('report/report/report', 'tRptRackFrom'),
            'tRptRackTo'    => language('report/report/report', 'tRptRackTo'),
            // Lang Condition Other Report
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
        ];

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
            'tUsrSessionID' => $this->tUserSessionID,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,

            // สาขา(Branch)
            'tBchCodeFrom' => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom' => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo' => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo' => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect' => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect' => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll' => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // ร้านค้า(Shop)
            'tShpCodeSelect' => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect' => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",

            // วันที่เอกสาร(DocNo)
            'tDocDateFrom' => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo' => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",

            // ตู้ฝากของ
            'tPosCodeSelect' => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect' => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",

            'tRackCodeFrom' => empty($this->input->post('oetSMLBrowseGroupCodeFrom')) ? '' : $this->input->post('oetSMLBrowseGroupCodeFrom'),
            'tRackNameFrom' => empty($this->input->post('oetSMLBrowseGroupNameFrom')) ? '' : $this->input->post('oetSMLBrowseGroupNameFrom'),
            'tRackCodeTo'   => empty($this->input->post('oetSMLBrowseGroupCodeTo')) ? '' : $this->input->post('oetSMLBrowseGroupCodeTo'),
            'tRackNameTo'   => empty($this->input->post('oetSMLBrowseGroupNameTo')) ? '' : $this->input->post('oetSMLBrowseGroupNameTo'),
            'tDocDateFrom'  => empty($this->input->post('oetRptDocDateFrom')) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'    => empty($this->input->post('oetRptDocDateTo')) ? '' : $this->input->post('oetRptDocDateTo'),
            'FNResult'      => 0
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {

            // Execute Stored Procedure
            $this->mRptRentAmountDetail->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID
            ];
            $this->nRows = $this->mRptRentAmountDetail->FSaMCountDataReportAll($aCountRowParams);

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
     * Creator: 22/07/2019 Piya
     * LastUpdate: 25/09/2019 Saharat(Golf)
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint() {
        try {
            /*===== Begin Get Data =====================================================*/
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
            ];
            $aDataReport = $this->mRptRentAmountDetail->FSaMGetDataReport($aDataReportParams);
            /*===== End Get Data =======================================================*/

            /*===== Begin Render View ==================================================*/
            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo' => $this->aCompanyInfo,
                'aDataReport' => $aDataReport,
                'aDataTextRef' => $this->aText,
                'aDataFilter' => $this->aRptFilter
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportRentAmountDetail', 'wRptRentAmountDetailHtml', $aDataViewRptParams);

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
                    'rtDesc' => 'success',
                ]
            ];
            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
            /*===== End Render View ====================================================*/
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {

        /*===== Begin Init Variable ====================================================*/
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /*===== End Init Variable ======================================================*/

        /*===== Begin Get Data =========================================================*/
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptRentAmountDetail->FSaMGetDataReport($aDataReportParams);
        // echo "<pre>";
        //     print_r ($aDataReport);
        //     echo "</pre>";
        //     exit;
        /*===== End Get Data ========================================================== */

        /*===== Begin Render View ======================================================*/
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $aDataFilter
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportRentAmountDetail', 'wRptRentAmountDetailHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter' => $aDataFilter,
            'aDataReport' => array(
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success'
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /*===== End Render View ========================================================*/
    }

    public function FSvCCallRptRenderExcel()
    {
        $tFileName = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';

        $oWriter = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); // stream data directly to the browser

        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel(); //เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $oWriter->addRows($aMulltiRow);

        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleColums = (new StyleBuilder())
            ->setBorder($oBorder)
            ->setFontBold()
            ->build();

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailSerailPos']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailUser']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailDocno']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailDocDate']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailRack']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailSubRack']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailLoginTo']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailStaPayment']),
            WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailAmtPayment']),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage' => 999999999999,
            'nPage' => 1,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptRentAmountDetail->FSaMGetDataReport($aDataReportParams);
        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData']['aData'] as $aDataValue) {

                if ($aDataValue["FTXshStaPaid"] == 1) {
                    $tStaPaid = $this->aText['tRptRentAmtForDetailStaPaymentNoPay'];
                }elseif ($aDataValue["FTXshStaPaid"] == 2) {
                    $tStaPaid = $this->aText['tRptRentAmtForDetailStaPaymentSome'];
                }elseif ($aDataValue["FTXshStaPaid"] == 3) {
                    $tStaPaid = $this->aText['tRptRentAmtForDetailStaPaymentAlready'];
                }

                if ($aDataValue["FCXshPrePaid"] == "") {
                    $tPrePaid =  number_format("0", $this->nOptDecimalShow);
                } else {
                    $tPrePaid =  number_format($aDataValue["FCXshPrePaid"], $this->nOptDecimalShow);
                }
                $values = [
                    WriterEntityFactory::createCell($aDataValue['FTPosCode']),
                    WriterEntityFactory::createCell($aDataValue['FTXshFrmLogin']),
                    WriterEntityFactory::createCell($aDataValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aDataValue["FDXshDocDate"]))),
                    WriterEntityFactory::createCell($aDataValue['FTRakName']),
                    WriterEntityFactory::createCell(date('d/m/Y', strtotime($aDataValue['FDXshDatePick']))),
                    WriterEntityFactory::createCell($aDataValue['FTXshToLogin']),
                    WriterEntityFactory::createCell($tStaPaid),
                    WriterEntityFactory::createCell($tPrePaid),
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if ($aDataValue["PARTITIONBYPOS"] == $aDataValue["COUNTBYPOS"]) {
                    foreach ($aDataReport["aRptData"]['aSumData'] as $aSumDataItems) {
                        if ($aSumDataItems["FNXshNumDoc"] == $aDataValue["COUNTBYPOS"]) {
                            $values = [
                                WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailSumText']),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(null),
                                WriterEntityFactory::createCell(number_format($aSumDataItems['FCXshSumPrePaid'], $this->nOptDecimalShow)),
                            ];
                            $aRow = WriterEntityFactory::createRow($values);
                            $oWriter->addRow($aRow);
                        }
                    }
                }
            }
            $aPagination    = $aDataReport["aPagination"];
            if ($aPagination["nTotalPage"] == $aPagination["nDisplayPage"]) {
                $values = [
                    WriterEntityFactory::createCell($this->aText['tRptRentAmtForDetailSumTextLast']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format($aDataReport["aRptData"]['aSumData'][0]['FCXshSumAllPrePaid'], $this->nOptDecimalShow)),
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
     * Creator: 01/10/2020 Sooksanti
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($this->aText['tTitleReport']),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptTel'] . ' ' . $tFTCmpTel . ' '.$this->aText['tRptFaxNo'] . ' ' . $tRptFaxNo),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptAddrBranch'] . ' ' . $tFTBchName),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRPCTaxNo'] . ' : ' . $tFTAddTaxNo),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        //Fillter DocDate (วันที่สร้างเอกสาร)
        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell($this->aText['tRptDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
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
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        return $aMulltiRow;

    }

    /**
     * Functionality: Render Excel Report Footer
     * Parameters:  Function Parameter
     * Creator: 01/10/2020 Sooksanti
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        // สาขา แบบเลือก
        if (!empty($this->aRptFilter['tBchCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $this->aRptFilter['tBchNameSelect']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ร้านค้า แบบเลือก
        if (!empty($this->aRptFilter['tShpCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $this->aRptFilter['tShpNameSelect']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // เครื่องจุดขาย (Pos) แบบเลือก
        if (!empty($this->aRptFilter['tPosCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report','tRptLockerBookingPosForm') . ' : ' . $this->aRptFilter['tPosNameSelect']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // สาขา แบบเลือก
        if (!empty($this->aRptFilter['tBchCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $this->aRptFilter['tBchNameSelect']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ร้านค้า แบบเลือก
        if (!empty($this->aRptFilter['tShpCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $this->aRptFilter['tShpNameSelect']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // กลุ่มช่อง F-T
        if ((isset($aDataFilter['tRackCodeFrom']) && !empty($aDataFilter['tRackCodeFrom'])) && (isset($aDataFilter['tRackCodeTo']) && !empty($aDataFilter['tRackCodeTo']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptRackFrom'] . ' : ' . $this->aRptFilter['tRackNameFrom']),
                WriterEntityFactory::createCell($this->aText['tRptRackTo'] . ' : ' . $this->aRptFilter['tRackNameTo']),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;

    }

}


