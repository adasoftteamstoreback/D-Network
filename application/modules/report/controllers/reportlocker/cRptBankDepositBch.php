<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

date_default_timezone_set("Asia/Bangkok");
class cRptBankDepositBch extends MX_Controller
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
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportlocker/mRptBankDepositBch');

        $this->init();
        parent::__construct();
    }

    private function init()
    {
        $this->aText = [

            // TitleReport
            'tTitleReport'                  => language('report/report/report', 'tRptBnkdplTitle'),
            'tDatePrint'                    => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'                    => language('report/report/report', 'tRptAdjStkVDTimePrint'),

            // Filter Heard Report
            'tRptBchFrom'                   => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                     => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom'                  => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                    => language('report/report/report', 'tRptShopTo'),
            'tPdtCodeFrom'                  => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'                    => language('report/report/report', 'tPdtCodeTo'),
            'tPdtGrpFrom'                   => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'                     => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'                  => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'                    => language('report/report/report', 'tPdtTypeTo'),
            'tRptDateFrom'                  => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'                    => language('report/report/report', 'tRptDateTo'),
            'tRptSaleTaxByMonthlyTotal'     => language('report/report/report', 'tRptSaleTaxByMonthlyTotal'),

            // Address Language
            'tRptAddrBuilding'              => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'                  => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'                   => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'           => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'              => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'              => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'                   => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'                   => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'                => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'                => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'                => language('report/report/report', 'tRptAddV2Desc2'),
            'tRptAdjStkVDTotalSub'          => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter'       => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptFaxNo'                     => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'                       => language('report/report/report', 'tRptTel'),

            // Table Label
            'tRptBillNo'                    => language('report/report/report', 'tRptBillNo'),
            'tRptDate'                      => language('report/report/report', 'tRptDate'),
            'tRptProduct'                   => language('report/report/report', 'tRptProduct'),
            'tRptCabinetnumber'             => language('report/report/report', 'tRptCabinetnumber'),
            'tRptPrice'                     => language('report/report/report', 'tRptPrice'),
            'tRptSales'                     => language('report/report/report', 'tSales'),
            'tRptDiscount'                  => language('report/report/report', 'tDiscount'),
            'tRptTax'                       => language('report/report/report', 'tRptTax'),
            'tRptGrand'                     => language('report/report/report', 'tRptGrand'),
            'tRptTotalSub'                  => language('report/report/report', 'tRptTotalSub'),
            'tRptTotalFooter'               => language('report/report/report', 'tRptTotalFooter'),
            'tRptNoData'                    => language('common/main/main', 'tCMNNotFoundData'),
            'tRptPosTypeName'               => language('report/report/report', 'tRptPosTypeName'),
            'tRptPosType'                   => language('report/report/report', 'tRptPosType'),
            'tRptPosType1'                  => language('report/report/report', 'tRptPosType1'),
            'tRptPosType2'                  => language('report/report/report', 'tRptPosType2'),
            'tRptPdtCode'                   => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName'                   => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp'                    => language('report/report/report', 'tRptPdtGrp'),
            'tRptQty'                       => language('report/report/report', 'tRptQty'),
            'tRptUnit'                      => language('report/report/report', 'tRptUnit'),
            'tRptAveragePrice'              => language('report/report/report', 'tRptAveragePrice'),
            'tRptAdjMerChantFrom'           => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'             => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'               => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'                 => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'                => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'                  => language('report/report/report', 'tRptAdjPosTo'),
            'tRptBranch'                    => language('report/report/report', 'tRptBranch'),
            'tRptTotal'                     => language('report/report/report', 'tRptTotal'),
            'tRPCTaxNo'                     => language('report/report/report', 'tRPCTaxNo'),
            'tRptConditionInReport'         => language('report/report/report', 'tRptConditionInReport'),
            'tRptMerFrom'                   => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'                     => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'                  => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                    => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'                   => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                     => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeTo'                    => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom'                  => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom'                   => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'                     => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'                  => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'                    => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom'                => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'                  => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAll'                       => language('report/report/report', 'tRptAll'),
            'tRptAddrTaxNo'                 => language('report/report/report', 'tRptAddrTaxNo'),
            'tRptLockerDetailDepositAmountDocNo'    => language('report/report/report', 'tRptLockerDetailDepositAmountDocNo'),
            'tRptBnkdplDate'                        => language('report/report/report', 'tRptBnkdplDate'),
            'tRptBnkdplDocno'                       => language('report/report/report', 'tRptBnkdplDocno'),
            'tRptBnkdplBnkAccno'                    => language('report/report/report', 'tRptBnkdplBnkAccno'),
            'tRptBnkdplBnkAccType'                  => language('report/report/report', 'tRptBnkdplBnkAccType'),
            'tRptBnkdplBnkBddType'                  => language('report/report/report', 'tRptBnkdplBnkBddType'),
            'tRptBnkdplBnkExtDate'                  => language('report/report/report', 'tRptBnkdplBnkExtDate'),
            'tRptBnkdplRefAmt'                      => language('report/report/report', 'tRptBnkdplRefAmt'),
            'tRptBnkdplBnkAccToTolsum'              => language('report/report/report', 'tRptBnkdplBnkAccToTolsum'),
            'tRptBnkdplBnkAccFrom'                  => language('report/report/report', 'tRptBnkdplBnkAccFrom'),
            'tRptBnkdplBnkAccTo'                    => language('report/report/report', 'tRptBnkdplBnkAccTo'),
            'tRptTotalFooter'                       => language('report/report/report', 'tRptTotalFooter'),
            'tRptBbkFrom'                           => language('report/report/report','tRptBbkFrom'),
            'tRptBbkTo'                             => language('report/report/report','tRptBbkTo'),
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

        // Report Filter
        $this->aRptFilter = [
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $tFullHost,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'nFilterType'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter Merchant (กลุ่มธุรกิจ)
            'tMerCodeFrom'      => (empty($this->input->post('oetRptMerCodeFrom'))) ? '' : $this->input->post('oetRptMerCodeFrom'),
            'tMerNameFrom'      => (empty($this->input->post('oetRptMerNameFrom'))) ? '' : $this->input->post('oetRptMerNameFrom'),
            'tMerCodeTo'        => (empty($this->input->post('oetRptMerCodeTo'))) ? '' : $this->input->post('oetRptMerCodeTo'),
            'tMerNameTo'        => (empty($this->input->post('oetRptMerNameTo'))) ? '' : $this->input->post('oetRptMerNameTo'),
            'tMerCodeSelect'    => !empty($this->input->post('oetRptMerCodeSelect')) ? $this->input->post('oetRptMerCodeSelect') : "",
            'tMerNameSelect'    => !empty($this->input->post('oetRptMerNameSelect')) ? $this->input->post('oetRptMerNameSelect') : "",
            'bMerStaSelectAll'  => !empty($this->input->post('oetRptMerStaSelectAll')) && ($this->input->post('oetRptMerStaSelectAll') == 1) ? true : false,

            // ร้านค้า(Shop)
            'tShpCodeFrom'      => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tShpNameFrom'      => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tShpCodeTo'        => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tShpNameTo'        => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            'tShpCodeSelect'    => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'    => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'  => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,

            // เลขที่บัญชี(BnkAccNo)
            'tAccNoFrom'        => !empty($this->input->post('oetRptBbkAccNoFrom')) ? $this->input->post('oetRptBbkAccNoFrom') : "",
            'tAccNameFrom'      => !empty($this->input->post('oetRptBbkAccNameFrom')) ? $this->input->post('oetRptBbkAccNameFrom') : "",
            'tAccNoTo'          => !empty($this->input->post('oetRptBbkAccNoTo')) ? $this->input->post('oetRptBbkAccNoTo') : "",
            'tAccNameTo'        => !empty($this->input->post('oetRptBbkAccNameTo')) ? $this->input->post('oetRptBbkAccNameTo') : "",

            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'      => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'        => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
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

            $this->mRptBankDepositBch->FSnMExecStoreReport($this->aRptFilter);
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptRenderExcel();
                    break;
            }
        }
    }

    //ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrint(){
        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage'          => $this->nPerPage,
            'nPage'             => '1', // เริ่มทำงานหน้าแรก
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'tUsrSessionID'     => $this->tUserSessionID,
            'aDataFilter'       => $this->aRptFilter,
        );
        $aDataReport = $this->mRptBankDepositBch->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewRpt = array(
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aDataFilter'       => $this->aRptFilter,
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportlocker/reportBankDepositBch', 'wRptBankDepositBchHtml', $aDataViewRpt);

        $aDataViewer = array(
            'tTitleReport'      => $this->aText['tTitleReport'],
            'tRptTypeExport'    => $this->tRptExportType,
            'tRptCode'          => $this->tRptCode,
            'tRptRoute'         => $this->tRptRoute,
            'tViewRenderKool'   => $tRptView,
            'aDataFilter'       => $this->aRptFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            ),
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    //Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrintClickPage(){

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage'          => $this->nPerPage,
            'nPage'             => $this->nPage,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'tUsrSessionID'     => $this->tUserSessionID,
            'aDataFilter'       => $this->aRptFilter
        );
        $aDataReport = $this->mRptBankDepositBch->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewRpt = array(
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aDataFilter'       => $aDataFilter,
        );

        // Load View Advance Tablew
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportlocker/reportBankDepositBch', 'wRptBankDepositBchHtml', $aDataViewRpt);

        // Data Viewer Center Report
        $aDataViewer = array(
            'tTitleReport'      => $this->aText['tTitleReport'],
            'tRptTypeExport'    => $this->tRptExportType,
            'tRptCode'          => $this->tRptCode,
            'tRptRoute'         => $this->tRptRoute,
            'tViewRenderKool'   => $tRptView,
            'aDataFilter'       => $aDataFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            ),
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    //ส่วนกลาง excel
    public function FSvCCallRptRenderExcel(){
        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); 

        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel(); //เรียกฟังชั่นสร้างส่วนหัวรายงาน
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
            WriterEntityFactory::createCell(language('report/report/report', 'สาขา')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplDate')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplDocno')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplBnkAccno')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplBnkAccType')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplBnkBddType')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplBnkExtDate')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkdplRefAmt'))
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataWhereRpt = array(
            'nPerPage'      => 0,
            'nPage'         => '1', // เริ่มทำงานหน้าแรก
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        );
        $aDataReport = $this->mRptBankDepositBch->FSaMGetDataReport($aDataWhereRpt);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $tFDBdhDate = empty($aValue['FDBdhDate']) ? '' : date("d/m/Y", strtotime($aValue['FDBdhDate']));
                $tFDBdhRefExtDate = empty($aValue['FDBdhRefExtDate']) ? '' : date("d/m/Y", strtotime($aValue['FDBdhRefExtDate']));
                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell($tFDBdhDate),
                    WriterEntityFactory::createCell($aValue['FTBdhDocNo']),
                    WriterEntityFactory::createCell($aValue['FTBbkAccNo']),
                    WriterEntityFactory::createCell($aValue['FTBbkType']),
                    WriterEntityFactory::createCell($aValue['FTBddType']),
                    WriterEntityFactory::createCell($tFDBdhRefExtDate),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCBddRefAmt'])),

                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if($aValue['PARTTITIONBYBCH'] == $aValue['PARTTITIONBYBCH_COUNT']){
                    $values = [
                        WriterEntityFactory::createCell('รวม' . $aValue['FTBchName']),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXrcNet_SubByBch'])),
                    ];
                    $aRow = WriterEntityFactory::createRow($values, $oStyleColums);
                    $oWriter->addRow($aRow);
                }

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $values = [
                        WriterEntityFactory::createCell($this->aText['tRptTotalFooter']),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXrcNet_Footer'])),
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

    //ส่วนหัว excel
    public function FSoCCallRptRenderHedaerExcel(){
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo)>0) {
            $tFTAddV1Village    = $this->aCompanyInfo['FTAddV1Village']; 
            $tFTCmpName         = $this->aCompanyInfo['FTCmpName'];
            $tFTAddV1No         = $this->aCompanyInfo['FTAddV1No'];
            $tFTAddV1Road       = $this->aCompanyInfo['FTAddV1Road'];
            $tFTAddV1Soi        = $this->aCompanyInfo['FTAddV1Soi'];
            $tFTSudName         = $this->aCompanyInfo['FTSudName'];
            $tFTDstName         = $this->aCompanyInfo['FTDstName'];
            $tFTPvnName         = $this->aCompanyInfo['FTPvnName'];
            $tFTAddV1PostCode   = $this->aCompanyInfo['FTAddV1PostCode'];
            $tFTAddV2Desc1      = $this->aCompanyInfo['FTAddV2Desc1'];
            $tFTAddV2Desc2      = $this->aCompanyInfo['FTAddV2Desc2'];
            $tFTAddVersion      = $this->aCompanyInfo['FTAddVersion'];
            $tFTBchName         = $this->aCompanyInfo['FTBchName'];
            $tFTAddTaxNo        = $this->aCompanyInfo['FTAddTaxNo'];
            $tFTCmpTel          = $this->aCompanyInfo['FTAddTel'];
            $tRptFaxNo          = $this->aCompanyInfo['FTAddFax'];
        }else {
            $tFTCmpTel          = "";
            $tFTCmpName         = "";
            $tFTAddV1No         = "";
            $tFTAddV1Road       = "";
            $tFTAddV1Soi        = "";
            $tFTSudName         = "";
            $tFTDstName         = "";
            $tFTPvnName         = "";
            $tFTAddV1PostCode   = "";
            $tFTAddV2Desc1      = "1"; 
            $tFTAddV1Village    = "";
            $tFTAddV2Desc2      = "2";
            $tFTAddVersion      = "";
            $tFTBchName         = "";
            $tFTAddTaxNo        = "";
            $tRptFaxNo          = "";
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
            WriterEntityFactory::createCell($this->aText['tTitleReport'])
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

        $aCells = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell($this->aText['tRptDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
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
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    //ส่วนท้าย excel
    public function FSoCCallRptRenderFooterExcel(){

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

        // ร้านค้า แบบเลือก
        if (!empty($this->aRptFilter['tShpCodeSelect'])) {
            $tMerSelect = ($this->aRptFilter['bShpStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tShpNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $tMerSelect),
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
        // กลุ่มธุรกิจ แบบเลือก
        if (!empty($this->aRptFilter['tMerCodeSelect'])) {
            $tMerSelectText = ($this->aRptFilter['bMerStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tMerNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'] . ' : ' . $tMerSelectText),
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

        // เครื่องจุดขาย แบบเลือก
        if (!empty($this->aRptFilter['tPosCodeSelect'])) {
            $tPosSelectText = ($this->aRptFilter['bPosStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tPosNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'] . ' : ' . $tPosSelectText),
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

        // Fillter Shop (ร้านค้า)  แบบช่วง
        if (!empty($this->aRptFilter['tShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $this->aRptFilter['tShpNameFrom'] . '     ' . $this->aText['tRptShopTo'] . ' : ' . $this->aRptFilter['tShpNameTo']),
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

        // Fillterฺ Mar (กลุ่มธุรกิจ) แบบช่วง
        if (!empty($this->aRptFilter['tMerCodeFrom']) && !empty($this->aRptFilter['tMerCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'] . ' : ' . $this->aRptFilter['tMerNameFrom'] . '     ' . $this->aText['tRptMerTo'] . ' : ' . $this->aRptFilter['tMerNameTo']),
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

        // เครื่องจุดขาย แบบช่วง
        if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'] . ' : ' . $this->aRptFilter['tPosNameFrom'] . '     ' . $this->aText['tRptPosTo'] . ' : ' . $this->aRptFilter['tPosNameTo']),
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

        //เลขที่บัญชี
        if (!empty($this->aRptFilter['tAccNoFrom']) && !empty($this->aRptFilter['tAccNoTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBbkFrom'] . ' : ' . $this->aRptFilter['tAccNameFrom'] . '     ' . $this->aText['tRptBbkTo'] . ' : ' . $this->aRptFilter['tAccNameTo']),
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
