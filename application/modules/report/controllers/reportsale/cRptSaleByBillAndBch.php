<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
date_default_timezone_set("Asia/Bangkok");

class cRptSaleByBillAndBch extends MX_Controller
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

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportsale/mRptSaleByBillAndBch');
        $this->init();
        parent::__construct();
    }

    private function init(){
        $this->aText = [
            'tTitleReport'                      => language('report/report/report', 'รายงานยอดขายตามบิลตามสาขา'),
            'tRptTaxNo'                         => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint'                     => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport'                    => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint'                     => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml'                     => language('report/report/report', 'tRptPrintHtml'),
            'tRptBranch'                        => language('report/report/report', 'tRptAddrBranch'),
            'tRptFaxNo'                         => language('report/report/report', 'tRptAddrFax'),
            'tRptTel'                           => language('report/report/report', 'tRptAddrTel'),
            'tRptBchFrom'                       => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                         => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom'                      => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                        => language('report/report/report', 'tRptShopTo'),
            'tRptDateFrom'                      => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'                        => language('report/report/report', 'tRptDateTo'),
            'tRptCstFrom'                       => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'                         => language('report/report/report', 'tRptCstTo'),
            'tRptPosTypeName'                   => language('report/report/report', 'tRptPosTypeName'),
            'tRptPosType'                       => language('report/report/report', 'tRptPosType'),
            'tRptPosType1'                      => language('report/report/report', 'tRptPosType1'),
            'tRptPosType2'                      => language('report/report/report', 'tRptPosType2'),
            'tRptDocBill'                       => language('report/report/report', 'tRptDocBill'),
            'tRptDisChg'                        => language('report/report/report', 'tRptDisChg'),
            'tRptTax'                           => language('report/report/report', 'tRptTax'),
            'tRptGrand'                         => language('report/report/report', 'tRptGrand'),
            'tRptOverall'                       => language('report/report/report', 'tRptOverall'),
            'tRptBillNo'                        => language('report/report/report', 'tRptBillNo'),
            'tRptTaxSalePosDocRef'              => language('report/report/report', 'tRptTaxSalePosDocRef'),
            'tRptCst'                           => language('report/report/report', 'tRptCst'),
            'tRptDate'                          => language('report/report/report', 'tRptDate'),
            'tSeqPdtCode'                       => language('report/report/report', 'tSeqPdtCode'),
            'tRptPdtName'                       => language('report/report/report', 'tRptPdtName'),
            'tRptQty'                           => language('report/report/report', 'tRptQty'),
            'tRptPricePerUnit'                  => language('report/report/report', 'tRptPricePerUnit'),
            'tRptSales'                         => language('report/report/report', 'tRptSales'),
            'tRptDiscount'                      => language('report/report/report', 'tRptDiscount'),
            'tRptGrandSale'                     => language('report/report/report', 'tRptGrandSale'),
            'tRptTotalAllSale'                  => language('report/report/report', 'tRptTotalAllSale'),
            'tRptPdtHaveTaxPerTax'              => language('report/report/report', 'tRptPdtHaveTaxPerTax'),
            'tRptRndVal'                        => language('report/report/report', 'tRptRndVal'),
            'tRptCstNormal'                     => language('report/report/report', 'tRptCstNormal'),
            'tRptConditionInReport'             => language('report/report/report', 'tRptConditionInReport'),
            'tRptAll'                           => language('report/report/report', 'tRptAll'),
            'tRptNoData'                        => language('report/report/report', 'tRptNoData'),
            'tRptBarchCode'                     => language('report/report/report', 'tRptBarchCode'),
            'tRptBarchName'                     => language('report/report/report', 'tRptBarchName'),
            'tRptBchFrom'                       => language('report/report/report', 'tRptBchFrom'),
            'tRptAdjMerChantFrom'               => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'                 => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'                   => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'                     => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'                    => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'                      => language('report/report/report', 'tRptAdjPosTo'),
            'tRptBranch'                        => language('report/report/report', 'tRptBranch'),
            'tRptTotal'                         => language('report/report/report', 'tRptTotal'),
            'tRPCTaxNo'                         => language('report/report/report', 'tRPCTaxNo'),
            'tRptConditionInReport'             => language('report/report/report', 'tRptConditionInReport'),
            'tRptMerFrom'                       => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'                         => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'                      => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                        => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'                       => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                         => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeTo'                        => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom'                      => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom'                       => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'                         => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'                      => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'                        => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom'                    => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'                      => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAll'                           => language('report/report/report', 'tRptAll'),
            'tRptSalByBranchBchCode'            => language('report/report/report', 'tRptSalByBranchBchCode'),
            'tRptSalByBranchBchName'            => language('report/report/report', 'tRptSalByBranchBchName'),
            'tRptTaxSalePosTaxId'               => language('report/report/report', 'tRptTaxSalePosTaxId'),
            'tRptTaxPointByCstDocDateFrom'      => language('report/report/report', 'tRptTaxPointByCstDocDateFrom'),
            'tRptDocSale'                       => language('report/report/report', 'tRptDocSale'),
            'tRptDocReturn'                     => language('report/report/report', 'tRptDocReturn'),
            'tRptTaxPointByCstDocDateTo'        => language('report/report/report', 'tRptTaxPointByCstDocDateTo'),
        ];

        $this->tSysBchCode          = SYS_BCH_CODE;
        $this->tBchCodeLogin        = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage             = 100;
        $this->nOptDecimalShow      = FCNxHGetOptionDecimalShow();
        $tIP                        = $this->input->ip_address();
        $tFullHost                  = gethostbyaddr($tIP);
        $this->tCompName            = $tFullHost;
        $this->nLngID               = FCNaHGetLangEdit();
        $this->tRptCode             = $this->input->post('ohdRptCode');
        $this->tRptGroup            = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID       = $this->session->userdata('tSesSessionID');
        $this->tRptRoute            = $this->input->post('ohdRptRoute');
        $this->tRptExportType       = $this->input->post('ohdRptTypeExport');
        $this->nPage                = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode       = $this->session->userdata('tSesUsername');

        // Report Filter
        $this->aRptFilter = [
            'tUserSessionID'        => $this->tUserSessionID,
            'tCompName'             => $tFullHost,
            'tRptCode'              => $this->tRptCode,
            'nLangID'               => $this->nLngID,
            'tTypeSelect'           => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            // สาขา
            'tBchCodeFrom'          => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'          => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'            => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'            => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'        => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'        => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'      => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // เครื่องจุดขาย
            'tPosCodeFrom'          => (empty($this->input->post('oetRptPosCodeFrom'))) ? '' : $this->input->post('oetRptPosCodeFrom'),
            'tPosNameFrom'          => (empty($this->input->post('oetRptPosNameFrom'))) ? '' : $this->input->post('oetRptPosNameFrom'),
            'tPosCodeTo'            => (empty($this->input->post('oetRptPosCodeTo'))) ? '' : $this->input->post('oetRptPosCodeTo'),
            'tPosNameTo'            => (empty($this->input->post('oetRptPosNameTo'))) ? '' : $this->input->post('oetRptPosNameTo'),
            'tPosCodeSelect'        => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect'        => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",
            'bPosStaSelectAll'      => !empty($this->input->post('oetRptPosStaSelectAll')) && ($this->input->post('oetRptPosStaSelectAll') == 1) ? true : false,

            // ลูกค้า
            'tCstCodeFrom'          => !empty($this->input->post('oetRptCstCodeFrom')) ? $this->input->post('oetRptCstCodeFrom') : "",
            'tCstNameFrom'          => !empty($this->input->post('oetRptCstNameFrom')) ? $this->input->post('oetRptCstNameFrom') : "",
            'tCstCodeTo'            => !empty($this->input->post('oetRptCstCodeTo')) ? $this->input->post('oetRptCstCodeTo') : "",
            'tCstNameTo'            => !empty($this->input->post('oetRptCstNameTo')) ? $this->input->post('oetRptCstNameTo') : "",

            // วันที่เอกสาร
            'tDocDateFrom'          => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'            => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin,
        ];

        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){

        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            $this->mRptSaleByBillAndBch->FSnMExecStoreCReport($this->aRptFilter);
            $aDataSwitchCase = array(
                'ptRptRoute'        => $this->tRptRoute,
                'ptRptCode'         => $this->tRptCode,
                'ptRptTypeExport'   => $this->tRptExportType,
                'paDataFilter'      => $this->aRptFilter,
            );

            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    break;
            }
        }
    }

    //ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrint(){

        $aDataWhere = array(
            'tUserSessionID'    => $this->tUserSessionID,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'nPage'             => 1, // เริ่มทำงานหน้าแรก
            'nPerPage'          => $this->nPerPage
        );

        $aDataReport        = $this->mRptSaleByBillAndBch->FSaMGetDataReport($aDataWhere, $this->aRptFilter);

        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $this->aRptFilter,
            'mRptSaleByBillAndBch'   => $this->mRptSaleByBillAndBch
        ];

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleByBillAndBch', 'wRptSaleByBillAndBchHtml', $aDataViewRptParams);

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
    }

    //Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrintClickPage(){

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'          => $this->nPerPage,
            'nPage'             => $this->nPage,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'tUserSessionID'    => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptSaleByBillAndBch->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter,
            'mRptSaleByBillAndBch' => $this->mRptSaleByBillAndBch
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleByBillAndBch', 'wRptSaleByBillAndBchHtml', $aDataViewRptParams);

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
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase){
        try {
            $aDataCountData = [
                'tCompName'     => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode'      => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tSessionID'    => $paDataSwitchCase['paDataFilter']['tSessionID'],
            ];

            $nDataCountPage = $this->mRptSaleByBillAndBch->FSnMCountDataReportAll($aDataCountData);

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

    //excel ส่วนกลาง
    public function FSvCCallRptRenderExcel(){
        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSRCDate')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBillNo')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDocRef')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillRptType')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillDataType')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPosTypeName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCst')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCabinetnumber')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptUnit')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPricePerUnit')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSales')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDiscount')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillTaxSeparated')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillVAT')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillReduceEndOfBill')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSaleByBillTotalBillReduction')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptRndVal')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptGrandSale')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptXshGrand')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPaymentToTal')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPayby')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSRCBank')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptXrcRef')),
        ];

        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataWhere = array(
            'tUserSessionID'    => $this->tUserSessionID,
            'tCompName'         => $this->tCompName,
            'tUserCode'         => $this->tUserLoginCode,
            'tRptCode'          => $this->tRptCode,
            'nPage'             => $this->nPage,
            'nPerPage'          => 0
        );
        $aDataReport = $this->mRptSaleByBillAndBch->FSaMGetDataReport($aDataWhere, $this->aRptFilter);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $tPosType       = $aValue['FNAppType'];
                $tPosTypeText   = $this->aText['tRptPosType' . $tPosType];
                $tDocType       = $aValue['FNXshDocType'];
                $tDocTypeText   = '';

                if ($tDocType == '1') {
                    $tDocTypeText = $this->aText['tRptDocSale'];
                }
                if ($tDocType == '9') {
                    $tDocTypeText = $this->aText['tRptDocReturn'];
                }

                $tDataType = $aValue['FNType'];
                $tDataTypeText = '';
                if ($tDataType == '1') {
                    $tDataTypeText = 'Header';
                }
                if ($tDataType == '2') {
                    $tDataTypeText = 'Detail';
                }
                if ($tDataType == '3') {
                    $tDataTypeText = 'Recieve';
                }

                $tFDXshDocDate      = empty($aValue['FDXshDocDate']) ? '' : date('d/m/Y', strtotime($aValue['FDXshDocDate']));
                $nFCXsdQty          = empty($aValue['FCXsdQty']) ? 0 : $aValue['FCXsdQty'];
                $cFCXsdSetPrice     = empty($aValue['FCXsdSetPrice']) ? 0 : $aValue['FCXsdSetPrice'];
                $cFCXsdAmt          = empty($aValue['FCXsdAmt']) ? 0 : $aValue['FCXsdAmt'];
                $cFCXsdDis          = empty($aValue['FCXsdDis']) ? 0 : $aValue['FCXsdDis'];
                $cFCXshVatable      = empty($aValue['FCXshVatable']) ? 0 : $aValue['FCXshVatable'];
                $cFCXshVat          = empty($aValue['FCXshVat']) ? 0 : $aValue['FCXshVat'];
                $cFCXshDis          = empty($aValue['FCXshDis']) ? 0 : $aValue['FCXshDis'];
                $cFCXshTotalAfDis   = empty($aValue['FCXshTotalAfDis']) ? 0 : $aValue['FCXshTotalAfDis'];
                $cFCXshRnd          = empty($aValue['FCXshRnd']) ? 0 : $aValue['FCXshRnd'];
                $cFCXsdNet          = empty($aValue['FCXsdNet']) ? 0 : $aValue['FCXsdNet'];
                $cFCXshGrand        = empty($aValue['FCXshGrand']) ? 0 : $aValue['FCXshGrand'];
                $cFCXrcNet          = empty($aValue['FCXrcNet']) ? 0 : $aValue['FCXrcNet'];
                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchCode']),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell($tFDXshDocDate),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell($aValue['FTXshRefInt']),
                    WriterEntityFactory::createCell($tDocTypeText),
                    WriterEntityFactory::createCell($tDataTypeText),
                    WriterEntityFactory::createCell($tPosTypeText),
                    WriterEntityFactory::createCell($aValue['FTCstName']),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nFCXsdQty)),
                    WriterEntityFactory::createCell($aValue['FTPunName']),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdSetPrice)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdAmt)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdDis)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVatable)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVat)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshDis)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshTotalAfDis)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshRnd)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdNet)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshGrand)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($cFCXrcNet)),
                    WriterEntityFactory::createCell($aValue['FTRcvName']),
                    WriterEntityFactory::createCell($aValue['FTBnkName']),
                    WriterEntityFactory::createCell($aValue['FTXrcRefNo1']),
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if($aValue['PARTTITIONBYBCH'] == $aValue['PARTTITIONBYBCH_COUNT']){ 
                    $aGetHDParams   = [
                        'tBCHCode'  => $aValue['FTBchCode']
                    ];
                    $aSumFooterByBCH            = $this->mRptSaleByBillAndBch->FMaMRPTSumFooterAllByBCH($aGetHDParams);
                    $cFCXsdQty_SumFooter        = empty($aSumFooterByBCH['FCXsdQty_SumFooter']) ? 0 : $aSumFooterByBCH['FCXsdQty_SumFooter'];
                    $cFCXsdSetPrice_SumFooter   = empty($aSumFooterByBCH['FCXsdSetPrice_SumFooter']) ? 0 : $aSumFooterByBCH['FCXsdSetPrice_SumFooter'];
                    $cFCXsdAmt_SumFooter        = empty($aSumFooterByBCH['FCXsdAmt_SumFooter']) ? 0 : $aSumFooterByBCH['FCXsdAmt_SumFooter'];
                    $cFCXsdDis_SumFooter        = empty($aSumFooterByBCH['FCXsdDis_SumFooter']) ? 0 : $aSumFooterByBCH['FCXsdDis_SumFooter'];
                    $cFCXshVatable_SumFooter    = empty($aSumFooterByBCH['FCXshVatable_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshVatable_SumFooter'];
                    $cFCXshVat_SumFooter        = empty($aSumFooterByBCH['FCXshVat_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshVat_SumFooter'];
                    $cFCXshDis_SumFooter        = empty($aSumFooterByBCH['FCXshDis_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshDis_SumFooter'];
                    $cFCXshTotalAfDis_SumFooter = empty($aSumFooterByBCH['FCXshTotalAfDis_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshTotalAfDis_SumFooter'];
                    $cFCXshRnd_SumFooter        = empty($aSumFooterByBCH['FCXshRnd_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshRnd_SumFooter'];
                    $cFCXsdNet_SumFooter        = empty($aSumFooterByBCH['FCXsdNet_SumFooter']) ? 0 : $aSumFooterByBCH['FCXsdNet_SumFooter'];
                    $cFCXshGrand_SumFooter      = empty($aSumFooterByBCH['FCXshGrand_SumFooter']) ? 0 : $aSumFooterByBCH['FCXshGrand_SumFooter'];
                    $cFCXrcNet_SumFooter        = empty($aSumFooterByBCH['FCXrcNet_SumFooter']) ? 0 : $aSumFooterByBCH['FCXrcNet_SumFooter'];
                    $values = [
                        WriterEntityFactory::createCell('รวม'),
                        WriterEntityFactory::createCell($aValue['FTBchName']),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdQty_SumFooter)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdSetPrice_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdAmt_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVatable_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVat_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshTotalAfDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshRnd_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdNet_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshGrand_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXrcNet_SumFooter)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null)
                    ];
                    $aRow = WriterEntityFactory::createRow($values, $oStyleColums);
                    $oWriter->addRow($aRow);
                }

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { 
                    $aGetHDParams = [
                        'tDocNo'            => $aValue['FTXshDocNo'],
                        'tCompName'         => $this->tCompName,
                        'tRptCode'          => $this->tRptCode,
                        'tUserSessionID'    => $this->tUserSessionID
                    ];
                    $aSumFooter                 = $this->mRptSaleByBillAndBch->FMaMRPTSumFooterAll($aGetHDParams);

                    $cFCXsdQty_SumFooter        = empty($aSumFooter['FCXsdQty_SumFooter']) ? 0 : $aSumFooter['FCXsdQty_SumFooter'];
                    $cFCXsdSetPrice_SumFooter   = empty($aSumFooter['FCXsdSetPrice_SumFooter']) ? 0 : $aSumFooter['FCXsdSetPrice_SumFooter'];
                    $cFCXsdAmt_SumFooter        = empty($aSumFooter['FCXsdAmt_SumFooter']) ? 0 : $aSumFooter['FCXsdAmt_SumFooter'];
                    $cFCXsdDis_SumFooter        = empty($aSumFooter['FCXsdDis_SumFooter']) ? 0 : $aSumFooter['FCXsdDis_SumFooter'];
                    $cFCXshVatable_SumFooter    = empty($aSumFooter['FCXshVatable_SumFooter']) ? 0 : $aSumFooter['FCXshVatable_SumFooter'];
                    $cFCXshVat_SumFooter        = empty($aSumFooter['FCXshVat_SumFooter']) ? 0 : $aSumFooter['FCXshVat_SumFooter'];
                    $cFCXshDis_SumFooter        = empty($aSumFooter['FCXshDis_SumFooter']) ? 0 : $aSumFooter['FCXshDis_SumFooter'];
                    $cFCXshTotalAfDis_SumFooter = empty($aSumFooter['FCXshTotalAfDis_SumFooter']) ? 0 : $aSumFooter['FCXshTotalAfDis_SumFooter'];
                    $cFCXshRnd_SumFooter        = empty($aSumFooter['FCXshRnd_SumFooter']) ? 0 : $aSumFooter['FCXshRnd_SumFooter'];
                    $cFCXsdNet_SumFooter        = empty($aSumFooter['FCXsdNet_SumFooter']) ? 0 : $aSumFooter['FCXsdNet_SumFooter'];
                    $cFCXshGrand_SumFooter      = empty($aSumFooter['FCXshGrand_SumFooter']) ? 0 : $aSumFooter['FCXshGrand_SumFooter'];
                    $cFCXrcNet_SumFooter        = empty($aSumFooter['FCXrcNet_SumFooter']) ? 0 : $aSumFooter['FCXrcNet_SumFooter'];
                    $values = [
                        WriterEntityFactory::createCell($this->aText['tRptTotalAllSale']),
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
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdQty_SumFooter)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdSetPrice_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdAmt_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVatable_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVat_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshTotalAfDis_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshRnd_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXsdNet_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshGrand_SumFooter)),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXrcNet_SumFooter)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
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

    //excel ส่วนหัว
    public function FSoCCallRptRenderHedaerExcel(){
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo)>0) {
            $tFTAddV1Village        = $this->aCompanyInfo['FTAddV1Village'];
            $tFTCmpName             = $this->aCompanyInfo['FTCmpName'];
            $tFTAddV1No             = $this->aCompanyInfo['FTAddV1No'];
            $tFTAddV1Road           = $this->aCompanyInfo['FTAddV1Road'];
            $tFTAddV1Soi            = $this->aCompanyInfo['FTAddV1Soi'];
            $tFTSudName             = $this->aCompanyInfo['FTSudName'];
            $tFTDstName             = $this->aCompanyInfo['FTDstName'];
            $tFTPvnName             = $this->aCompanyInfo['FTPvnName'];
            $tFTAddV1PostCode       = $this->aCompanyInfo['FTAddV1PostCode'];
            $tFTAddV2Desc1          = $this->aCompanyInfo['FTAddV2Desc1'];
            $tFTAddV2Desc2          = $this->aCompanyInfo['FTAddV2Desc2'];
            $tFTAddVersion          = $this->aCompanyInfo['FTAddVersion'];
            $tFTBchName             = $this->aCompanyInfo['FTBchName'];
            $tFTAddTaxNo            = $this->aCompanyInfo['FTAddTaxNo'];
            $tFTCmpTel              = $this->aCompanyInfo['FTAddTel'];
            $tRptFaxNo              = $this->aCompanyInfo['FTAddFax'];
        }else {
            $tFTCmpTel              = "";
            $tFTCmpName             = "";
            $tFTAddV1No             = "";
            $tFTAddV1Road           = "";
            $tFTAddV1Soi            = "";
            $tFTSudName             = "";
            $tFTDstName             = "";
            $tFTPvnName             = "";
            $tFTAddV1PostCode       = "";
            $tFTAddV2Desc1          = "1"; 
            $tFTAddV1Village        = "";
            $tFTAddV2Desc2          = "2";
            $tFTAddVersion          = "";
            $tFTBchName             = "";
            $tFTAddTaxNo            = "";
            $tRptFaxNo              = "";
        }

        $oStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell($tFTCmpName),
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
            WriterEntityFactory::createCell($this->aText['tRptBranch'] . ' ' . $tFTBchName),
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
            WriterEntityFactory::createCell($this->aText['tRptTaxSalePosTaxId'] . ' : ' . $tFTAddTaxNo),
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
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
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell($this->aText['tRptTaxPointByCstDocDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptTaxPointByCstDocDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($this->aText['tRptDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tRptTimePrint'] . ' ' . date('H:i:s')),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    //excel ส่วนท้าย
    public function FSoCCallRptRenderFooterExcel(){

        $oStyleFilter = (new StyleBuilder())
            ->setFontBold()
            ->build();

        $aCells = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

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

        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect = ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
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

        if (isset($this->aRptFilter['tPosCodeSelect']) && !empty($this->aRptFilter['tPosCodeSelect'])) {
            $tPosSelect = ($this->aRptFilter['bPosStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tPosCodeSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'] . ' : ' . $tPosSelect),
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

        if ((isset($this->aRptFilter['tCstCodeFrom']) && !empty($this->aRptFilter['tCstCodeFrom'])) && (isset($this->aRptFilter['tCstCodeTo']) && !empty($this->aRptFilter['tCstCodeTo']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $this->aRptFilter['tCstCodeFrom'] . ' ' . $this->aText['tRptCstTo'] . ' : ' . $this->aRptFilter['tCstCodeTo']),
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

        return $aMulltiRow;

    }
}
