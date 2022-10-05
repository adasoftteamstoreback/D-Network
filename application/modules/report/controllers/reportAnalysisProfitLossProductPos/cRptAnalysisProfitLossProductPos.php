<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
date_default_timezone_set("Asia/Bangkok");

class cRptAnalysisProfitLossProductPos extends MX_Controller{
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

    public function __construct(){
        $this->load->helper('report');
        $this->load->model('report/reportAnalysisProfitLossProductPos/mRptAnalysisProfitLossProductPos');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init(){
        $this->aText = [
            // Title
            'tTitleReport'          => language('report/report/report', 'tRptAnalysisProfitLossProductPosTitle'),
            'tDatePrint'            => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tTimePrint'            => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            // Address Lang
            'tRptAddrBuilding'      => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'          => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'           => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'   => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'      => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'      => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'           => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'           => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'        => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'        => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'        => language('report/report/report', 'tRptAddV2Desc2'),
            'tRptFaxNo'             => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'               => language('report/report/report', 'tRptTel'),
            // Table Label
            'tRptTaxSalePosDocNo'               => language('report/report/report', 'tRptTaxSalePosDocNo'),
            'tRptTaxSalePosDocDate'             => language('report/report/report', 'tRptTaxSalePosDocDate'),
            'tRptTaxSalePosDateAndLocker'       => language('report/report/report', 'tRptTaxSalePosDateAndLocker'),
            'tRptTaxSalePosPayTypeAndDocRef'    => language('report/report/report', 'tRptTaxSalePosPayTypeAndDocRef'),
            'tRptTaxSalePosDocRef'              => language('report/report/report', 'tRptTaxSalePosDocRef'),
            'tRptTaxSalePosPayment'             => language('report/report/report', 'tRptTaxSalePosPayment'),
            'tRptTaxSalePosPaymentTotal'        => language('report/report/report', 'tRptTaxSalePosPaymentTotal'),
            'tRptTaxSalePosPosGrouping'         => language('report/report/report', 'tRptTaxSalePosPosGrouping'),
            // No Data Report
            'tRptTaxSalePosNoData'              => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSalePosTotalSub'            => language('report/report/report', 'tRptTaxSalePosTotalSub'),
            'tRptTaxSalePosTotalFooter'         => language('report/report/report', 'tRptTaxSalePosTotalFooter'),
            // Filter Text Label
            'tRptTaxSalePosFilterBchFrom'       => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
            'tRptTaxSalePosFilterBchTo'         => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
            'tRptTaxSalePosFilterShopFrom'      => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
            'tRptTaxSalePosFilterShopTo'        => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
            'tRptTaxSalePosFilterPosFrom'       => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo'         => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosFilterPayTypeFrom'   => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
            'tRptTaxSalePosFilterPayTypeTo'     => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
            'tRptTaxSalePosFilterDocDateFrom'   => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo'     => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            'tRptTaxSalePosTaxId'               => language('report/report/report', 'tRptTaxSalePosTaxId'),

            'tRptDateFrom'  => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'    => language('report/report/report', 'tRptDateTo'),
            'tRptBchFrom'   => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'     => language('report/report/report', 'tRptBchTo'),
            'tRptMerFrom'   => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'     => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'  => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'    => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'   => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'     => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeFrom'  => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'    => language('report/report/report', 'tPdtCodeTo'),
            'tPdtGrpFrom'   => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'     => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'  => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'    => language('report/report/report', 'tPdtTypeTo'),
            // Text Label
            'tRptPdtCode'               => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName'               => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp'                => language('report/report/report', 'tRptPdtGrp'),
            'tRptQtySale'               => language('report/report/report', 'tRptQtySale'),
            'tRptSaleQty'               => language('report/report/report', 'tRptAnalysisProfitLossProductPosSaleQty'),
            'tRptGrandSale'             => language('report/report/report', 'tRptGrandSale'),
            'tRptProfitloss'            => language('report/report/report', 'tRptProfitloss'),
            'tRptCost'                  => language('report/report/report', 'tRptCost'),
            'tRptSalesVending'          => language('report/report/report', 'tRptSalesVending'),
            'tRptCabinetCost'           => language('report/report/report', 'tRptCabinetCost'),
            'tRptTotalSale'             => language('report/report/report', 'tRptTotalSale'),
            'tRptProfitandLost'         => language('report/report/report', 'tRptProfitandLost'),
            'tRptGrandtotal'            => '%' . language('report/report/report', 'tRptGrandtotal'),
            'tRptCapital'               => '%' . language('report/report/report', 'tRptCapital'),
            'tRptTaxSalePosTel'         => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTaxSalePosFax'         => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptTaxSalePosDatePrint'   => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tRptTaxSalePosTimePrint'   => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptTaxSalePosBch'         => language('report/report/report', 'tRptTaxSalePosBch'),
            'tRptDataReportNotFound'    => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptRentAmtFolCourSumText' => language('report/report/report', 'tRptRentAmtFolCourSumText'),
            'tRptTotalSub'              => language('report/report/report', 'tRptTotalSub'),
            'tRptPosType'               => language('report/report/report', 'tRptPosType'),
            'tRptPosType1'              => language('report/report/report', 'tRptPosType1'),
            'tRptPosType2'              => language('report/report/report', 'tRptPosType2'),
            'tRptPosTypeName'           => language('report/report/report', 'tRptPosTypeName'),
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),

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
            'tRptTotalFooter' => language('report/report/report', 'tRptTotalFooter'),
            'tRptTaxSaleTaxNo' => language('report/report/report', 'tRptTaxSaleTaxNo'),
        ];
        $this->tSysBchCode      = SYS_BCH_CODE;
        $this->tBchCodeLogin    = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage         = 100;
        $this->nOptDecimalShow  = get_cookie('tOptDecimalShow');
        $tIP                    = $this->input->ip_address();
        $tFullHost              = gethostbyaddr($tIP);
        $this->tCompName        = $tFullHost;
        $this->nLngID           = $this->session->userdata("tLangID");
        $this->tRptCode         = $this->input->post('ohdRptCode');
        $this->tRptGroup        = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID   = $this->session->userdata('tSesSessionID');
        $this->tRptRoute        = $this->input->post('ohdRptRoute');
        $this->tRptExportType   = $this->input->post('ohdRptTypeExport');
        $this->nPage            = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode   = $this->session->userdata('tSesUsername');
        // Report Filter
        $this->aRptFilter = [
            'tUserSession'  => $this->tUserSessionID,
            'tCompName'     => $tFullHost,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,
            'tTypeSelect'   => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            // สาขา(Branch)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,
            // กลุ่มธุรกิจ
            'tRptMerCodeFrom'   => !empty($this->input->post('oetRptMerCodeFrom')) ? $this->input->post('oetRptMerCodeFrom') : "",
            'tRptMerNameFrom'   => !empty($this->input->post('oetRptMerNameFrom')) ? $this->input->post('oetRptMerNameFrom') : "",
            'tRptMerCodeTo'     => !empty($this->input->post('oetRptMerCodeTo')) ? $this->input->post('oetRptMerCodeTo') : "",
            'tRptMerNameTo'     => !empty($this->input->post('oetRptMerNameTo')) ? $this->input->post('oetRptMerNameTo') : "",
            'tMerCodeSelect'    => !empty($this->input->post('oetRptMerCodeSelect')) ? $this->input->post('oetRptMerCodeSelect') : "",
            'tMerNameSelect'    => !empty($this->input->post('oetRptMerNameSelect')) ? $this->input->post('oetRptMerNameSelect') : "",
            'bMerStaSelectAll'  => !empty($this->input->post('oetRptMerStaSelectAll')) && ($this->input->post('oetRptMerStaSelectAll') == 1) ? true : false,
            // ร้านค้า
            'tRptShpCodeFrom'   => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tRptShpNameFrom'   => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tRptShpCodeTo'     => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tRptShpNameTo'     => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            'tShpCodeSelect'    => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'    => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'  => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,
            // เครื่องจุดขาย
            'tRptPosCodeFrom'   => !empty($this->input->post('oetRptPosCodeFrom')) ? $this->input->post('oetRptPosCodeFrom') : "",
            'tRptPosNameFrom'   => !empty($this->input->post('oetRptPosNameFrom')) ? $this->input->post('oetRptPosNameFrom') : "",
            'tRptPosCodeTo'     => !empty($this->input->post('oetRptPosCodeTo')) ? $this->input->post('oetRptPosCodeTo') : "",
            'tRptPosNameTo'     => !empty($this->input->post('oetRptPosNameTo')) ? $this->input->post('oetRptPosNameTo') : "",
            'tPosCodeSelect'    => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect'    => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",
            'bPosStaSelectAll'  => !empty($this->input->post('oetRptPosStaSelectAll')) && ($this->input->post('oetRptPosStaSelectAll') == 1) ? true : false,
            // สินค้า
            'tRptPdtCodeFrom'   => !empty($this->input->post('oetRptPdtCodeFrom')) ? $this->input->post('oetRptPdtCodeFrom') : "",
            'tRptPdtNameFrom'   => !empty($this->input->post('oetRptPdtNameFrom')) ? $this->input->post('oetRptPdtNameFrom') : "",
            'tRptPdtCodeTo'     => !empty($this->input->post('oetRptPdtCodeTo')) ? $this->input->post('oetRptPdtCodeTo') : "",
            'tRptPdtNameTo'     => !empty($this->input->post('oetRptPdtNameTo')) ? $this->input->post('oetRptPdtNameTo') : "",
            // กลุ่มสินค้า
            'tRptPdtGrpCodeFrom'    => !empty($this->input->post('oetRptPdtGrpCodeFrom')) ? $this->input->post('oetRptPdtGrpCodeFrom') : "",
            'tRptPdtGrpNameFrom'    => !empty($this->input->post('oetRptPdtGrpNameFrom')) ? $this->input->post('oetRptPdtGrpNameFrom') : "",
            'tRptPdtGrpCodeTo'      => !empty($this->input->post('oetRptPdtGrpCodeTo')) ? $this->input->post('oetRptPdtGrpCodeTo') : "",
            'tRptPdtGrpNameTo'      => !empty($this->input->post('oetRptPdtGrpNameTo')) ? $this->input->post('oetRptPdtGrpNameTo') : "",
            // ประเภทสินค้า
            'tRptPdtTypeCodeFrom'   => !empty($this->input->post('oetRptPdtTypeCodeFrom')) ? $this->input->post('oetRptPdtTypeCodeFrom') : "",
            'tRptPdtTypeNameFrom'   => !empty($this->input->post('oetRptPdtTypeNameFrom')) ? $this->input->post('oetRptPdtTypeNameFrom') : "",
            'tRptPdtTypeCodeTo'     => !empty($this->input->post('oetRptPdtTypeCodeTo')) ? $this->input->post('oetRptPdtTypeCodeTo') : "",
            'tRptPdtTypeNameTo'     => !empty($this->input->post('oetRptPdtTypeNameTo')) ? $this->input->post('oetRptPdtTypeNameTo') : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
            //ประเภทเครื่องจุดขาย
            'tPosType'      => !empty($this->input->post('ocmPosType')) ? $this->input->post('ocmPosType') : "",
        ];
        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin,
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {
            // Execute Stored Procedure
            $this->mRptAnalysisProfitLossProductPos->FSnMExecStoreReport($this->aRptFilter);
            // Report Type
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

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Saharat(Golf)
     * LastUpdate: -
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint(){
        try {
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams  = [
                'nPerPage'      => $this->nPerPage,
                'nPage'         => $this->nPage,
                'tCompName'     => $this->tCompName,
                'tRptCode'      => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
                'aDataFilter'   => $this->aRptFilter,
            ];
            $aDataReport = $this->mRptAnalysisProfitLossProductPos->FSaMGetDataReport($aDataReportParams);
            /** =========== End Get Data ===================================== */
            /** =========== Begin Render View ================================ */
            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter,
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportAnalysisProfitLossProductPos', 'wRptAnalysisProfitLossProductPosHtml', $aDataViewRptParams);
            // Data Viewer Center Report
            $aDataViewerParams  = [
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
                    'rtDesc'        => 'success',

                ],
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
     * Creator: 15/10/2562 Napat(Jame)
     * LastUpdate: -
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage(){
        /** =========== End Init Variable ==================================== */
        $aDataWhere = array(
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $this->tCompName,
            'tUserCode'         => $this->tUserLoginCode,
            'tRptCode'          => $this->tRptCode,
            'nPage'             => $this->nPage,
            'nRow'              => $this->nPerPage,
            'nPerPage'          => $this->nPerPage,
            'tUsrSessionID'     => $this->tUserSessionID,
            'aDataFilter'       => $this->aRptFilter,
        );
        $aDataReport = $this->mRptAnalysisProfitLossProductPos->FSaMGetDataReport($aDataWhere);
        /** =========== End Get Data ===================================== */
        /** =========== Begin Render View ================================ */
        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $this->aRptFilter,
        ];
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportAnalysisProfitLossProductPos', 'wRptAnalysisProfitLossProductPosHtml', $aDataViewRptParams);
        // Data Viewer Center Report
        $aDataViewerParams  = [
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
                'rtDesc'        => 'success',

            ],
        ];
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
    }

    // Excel : ส่วนกลาง
    public function FSvCCallRptRenderExcel(){
        $aDataReportParams  = [
            'nPerPage'      => 0,
            'nPage'         => 1,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter'    => $this->aRptFilter,
        ];
        $aDataReport    = $this->mRptAnalysisProfitLossProductPos->FSaMGetDataReport($aDataReportParams);
        $tFileName      = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter        = WriterEntityFactory::createXLSXWriter();
        // stream data directly to the browser
        $oWriter->openToBrowser($tFileName);
        // เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $aMulltiRow     = $this->FSoCCallRptRenderHedaerExcel();
        $oWriter->addRows($aMulltiRow);
        $oBorder        = (new BorderBuilder())->setBorderTop(Color::BLACK, Border::WIDTH_THIN)->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)->build();
        $oBorder        = (new BorderBuilder())->setBorderTop(Color::BLACK, Border::WIDTH_THIN)->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)->build();
        $oStyleColums   = (new StyleBuilder())->setBorder($oBorder)->setFontBold()->build();
        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtGrp')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptQtySale')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCabinetCost')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptGrandSale')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptProfitloss')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCost')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSalesVending')),
            WriterEntityFactory::createCell(null),
        ];
        /** add a row at a time */
        $singleRow  = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);
        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())->setCellAlignment(CellAlignment::RIGHT)->build();
        // Check Data Report
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTChainName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdSaleQty'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCPdtCost'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXshGrand'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdProfit'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdProfitPercent'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdSalePercent'])),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow   = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
            }
            $values = [
                WriterEntityFactory::createCell('รวม'),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCXsdSaleQty_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCPdtCost_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCXshGrand_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCXsdProfit_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCXsdProfitPercent_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(number_format(floatval($aValue['FCXsdSalePercent_Footer']), $this->nOptDecimalShow)),
                WriterEntityFactory::createCell(null),
            ];
            $singleRow  = WriterEntityFactory::createRow($values, $oStyleColums);
            $oWriter->addRow($singleRow);
        }
        //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel();
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();
        unset($aDataReportParams,$aDataReport,$tFileName,$oWriter,$aMulltiRow,$oBorder,$aCells,$singleRow,$oStyle);
    }

    // Excel : ส่วนหัว
    public function FSoCCallRptRenderHedaerExcel(){
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo) > 0) {
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
        } else {
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
        $oStyle = (new StyleBuilder())->setFontBold()->setFontSize(12)->build();
        $aCells = [
            WriterEntityFactory::createCell($tFTCmpName),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($this->aText['tTitleReport'])
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
            WriterEntityFactory::createCell($this->aText['tRptTel'] . ' ' . $tFTCmpTel . ' ' . $this->aText['tRptFaxNo'] . ' ' . $tRptFaxNo),
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

        // Fillter DocDate (วันที่สร้างเอกสาร)
        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
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

    // Excel : ส่วนท้าย
    public function FSoCCallRptRenderFooterExcel(){
        $oStyleFilter   = (new StyleBuilder())->setFontBold()->build();
        $aCells         = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[]   = WriterEntityFactory::createRow($aCells);
        $aCells         = [
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport']),
        ];
        $aMulltiRow[]   = WriterEntityFactory::createRow($aCells, $oStyleFilter);
         // สาขา แบบเลือก
         if (!empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelectText = ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptTaxSalePosFilterBchFrom'] . ' ' . $tBchSelectText),
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
            $tShpSelectText = ($this->aRptFilter['bShpStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tShpNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $tShpSelectText),
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
        // กลุ่มธุระกิจ (Mar) แบบเลือก
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
        // Fillter Shop (ร้านค้า) แบบช่วง
        if (!empty($this->aRptFilter['tRptShpCodeFrom']) && !empty($this->aRptFilter['tRptShpCodeFrom'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptShopFrom'] . ' : ' . $this->aRptFilter['tRptShpNameFrom'] . '     ' . $this->aText['tRptShopTo'] . ' : ' . $this->aRptFilter['tRptShpNameTo']),
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
        // Fillter เครื่องจุดขาย แบบช่วง
        if (!empty($this->aRptFilter['tRptPosCodeFrom']) && !empty($this->aRptFilter['tRptPosCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'] . ' : ' . $this->aRptFilter['tRptPosNameFrom'] . '     ' . $this->aText['tRptPosTo'] . ' : ' . $this->aRptFilter['tRptPosNameTo']),
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
        // Fillter กลุ่มธุระกิจ แบบช่วง
        if (!empty($this->aRptFilter['tRptMerCodeFrom']) && !empty($this->aRptFilter['tRptMerCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'] . ' : ' . $this->aRptFilter['tRptMerNameFrom'] . '     ' . $this->aText['tRptMerTo'] . ' : ' . $this->aRptFilter['tRptMerNameTo']),
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
        // Fillter สินค้า แบบช่วง
        if (!empty($this->aRptFilter['tRptPdtCodeFrom']) && !empty($this->aRptFilter['tRptPdtCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tPdtCodeFrom'] . ' : ' . $this->aRptFilter['tRptPdtNameFrom'] . '     ' . $this->aText['tPdtCodeTo'] . ' : ' . $this->aRptFilter['tRptPdtNameTo']),
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
        // Fillter กลุ่มสินค้า แบบช่วง
        if (!empty($this->aRptFilter['tRptPdtGrpCodeFrom']) && !empty($this->aRptFilter['tRptPdtGrpCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tPdtCodeFrom'] . ' : ' . $this->aRptFilter['tRptPdtGrpNameFrom'] . '     ' . $this->aText['tPdtCodeTo'] . ' : ' . $this->aRptFilter['tRptPdtGrpNameTo']),
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
        // Fillter ประเภทสินค้า แบบช่วง
        if (!empty($this->aRptFilter['tRptPdtTypeCodeFrom']) && !empty($this->aRptFilter['tRptPdtTypeCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tPdtGrpFrom'] . ' : ' . $this->aRptFilter['tRptPdtTypeNameFrom'] . '     ' . $this->aText['tPdtGrpTo'] . ' : ' . $this->aRptFilter['tRptPdtTypeNameTo']),
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
        // Fillter Apptype (ประเภทเครื่องจุดขาย)
        if (isset($this->aRptFilter['tPosType'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosTypeName'] . ' : ' . $this->aText['tRptPosType' . $this->aRptFilter['tPosType']]),
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
