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

class cRptSaleRecive extends MX_Controller {
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

    public function __construct() {
        $this->load->model('report/reportsale/mRptSaleRecive');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport'          => language('report/report/report', 'tRptTitleSaleRecive'),
            'tDatePrint'            => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'            => language('report/report/report', 'tRptAdjStkVDTimePrint'),
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
            // Table Report
            'tRptBarchCode'         => language('report/report/report', 'tRptBarchCode'),
            'tRptBarchName'         => language('report/report/report', 'tRptBarchName'),
            'tRptDocDate'           => language('report/report/report', 'tRptDocDate'),
            'tRptShopCode'          => language('report/report/report', 'tRptShopCode'),
            'tRptShopName'          => language('report/report/report', 'tRptShopName'),
            'tRptAmount'            => language('report/report/report', 'tRptAmount'),
            'tRptSale'              => language('report/report/report', 'tRptSale'),
            'tRptCancelSale'        => language('report/report/report', 'tRptCancelSale'),
            'tRptTotalSale'         => language('report/report/report', 'tRptTotalSale'),
            'tRptTotalAllSale'      => language('report/report/report', 'tRptTotalAllSale'),
            'tRptPayby'             => language('report/report/report', 'tRptPayby'),
            'tRptRcvDocumentCode'   => language('report/report/report', 'tRptRcvDocumentCode'),
            'tRptDate'              => language('report/report/report', 'tRptDate'),
            'tRptRcvTotal'          => language('report/report/report', 'tRptRcvTotal'),
            // Filter Heard Report
            'tRptRcvFrom'           => language('report/report/report', 'tRptRcvFrom'),
            'tRptRcvTo'             => language('report/report/report', 'tRptRcvTo'),
            'tRptBchFrom'           => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom'          => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'            => language('report/report/report', 'tRptShopTo'),
            'tPdtCodeFrom'          => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'            => language('report/report/report', 'tPdtCodeTo'),
            'tPdtGrpFrom'           => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'             => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'          => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'            => language('report/report/report', 'tPdtTypeTo'),
            'tRptDateFrom'          => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'            => language('report/report/report', 'tRptDateTo'),
            // Table Label
            'tRptBillNo'            => language('report/report/report', 'tRptBillNo'),
            'tRptDate'              => language('report/report/report', 'tRptDate'),
            'tRptProduct'           => language('report/report/report', 'tRptProduct'),
            'tRptCabinetnumber'     => language('report/report/report', 'tRptCabinetnumber'),
            'tRptPrice'             => language('report/report/report', 'tRptPrice'),
            'tRptSales'             => language('report/report/report', 'tSales'),
            'tRptDiscount'          => language('report/report/report', 'tDiscount'),
            'tRptTax'               => language('report/report/report', 'tRptTax'),
            'tRptGrand'             => language('report/report/report', 'tRptGrand'),
            'tRptTotalSub'          => language('report/report/report', 'tRptTotalSub2'),
            'tRptTotalFooter'       => language('report/report/report', 'tRptTotalFooter'),
            'tRptSRCCouponCardNumber' =>language('report/report/report','tRptSRCCouponCardNumber'),
            'tRptSRCNumber'         =>language('report/report/report','tRptSRCNumber'),
            'tRptSRCBank'           =>language('report/report/report','tRptSRCBank'),
            'tRptTotal'             =>language('report/report/report','tRptTotal'),
            'tRptSRCCash'           =>language('report/report/report','tRptSRCCash'),
            'tRptSRCCredit'         =>language('report/report/report','tRptSRCCredit'),
            'tRptSRCOther'          =>language('report/report/report','tRptSRCOther'),
            'tRptSRCPromtpay'       =>language('report/report/report','tRptSRCPromtpay'),
            'tRptSRCAlipay'         =>language('report/report/report','tRptSRCAlipay'),
            'tRptTaxSalePosTaxId'   => language('report/report/report', 'tRptTaxSalePosTaxId'),
            "tRptAdjStkNoData"      => language('report/report/report','tRptAdjStkNoData'),
            // No Data Report
            'tRptNoData'            => language('common/main/main', 'tCMNNotFoundData'),
            'tRptPosTypeName'       => language('common/main/main', 'tRptPosTypeName'),
            'tRptPosType'           => language('common/main/main', 'tRptPosType'),
            'tRptPosType1'          => language('common/main/main', 'tRptPosType1'),
            'tRptPosType2'          => language('common/main/main', 'tRptPosType2'),
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
            'tRptAdjMerChantFrom'   => language('report/report/report','tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'     => language('report/report/report','tRptAdjMerChantTo'),
            'tRptAdjShopFrom'       => language('report/report/report','tRptAdjShopFrom'),
            'tRptAdjShopTo'         => language('report/report/report','tRptAdjShopTo'),
            'tRptAdjPosFrom'        => language('report/report/report','tRptAdjPosFrom'),
            'tRptAdjPosTo'          => language('report/report/report','tRptAdjPosTo'),
            'tRptBranch'            => language('report/report/report', 'tRptBranch'),
            'tRptTotal'             => language('report/report/report', 'tRptTotal'),
            'tRPCTaxNo'             => language('report/report/report', 'tRPCTaxNo'),
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
            'tRptMerFrom'           => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'             => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'          => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'            => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'           => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'             => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeTo'            => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom'          => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom'           => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'             => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'          => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'            => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom'        => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'          => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAll'               => language('report/report/report', 'tRptAll'),
            'tRptDatePrint'         => language('report/report/report', 'tRptDatePrint'),
            'tRptTimePrint'         => language('report/report/report', 'tRptTimePrint'),
            'tRptTaxPointByCstDocDateFrom'  => language('report/report/report', 'tRptTaxPointByCstDocDateFrom'),
            'tRptTaxPointByCstDocDateTo'    => language('report/report/report', 'tRptTaxPointByCstDocDateTo'),
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
        $this->nPosType         = $this->input->post('ocmPosType');
        // Report Filter
        $this->aRptFilter = [
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $tFullHost,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            // สาขา(Branch)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,
            // ร้านค้า(Shop)
            'tShpCodeFrom'         => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tShpNameFrom'         => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tShpCodeTo'           => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tShpNameTo'           => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            'tShpCodeSelect'       => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'       => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'     => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,
            // Filter Merchant (กลุ่มธุรกิจ)
            'tMerCodeFrom'      => (empty($this->input->post('oetRptMerCodeFrom'))) ? '' : $this->input->post('oetRptMerCodeFrom'),
            'tMerNameFrom'      => (empty($this->input->post('oetRptMerNameFrom'))) ? '' : $this->input->post('oetRptMerNameFrom'),
            'tMerCodeTo'        => (empty($this->input->post('oetRptMerCodeTo'))) ? '' : $this->input->post('oetRptMerCodeTo'),
            'tMerNameTo'        => (empty($this->input->post('oetRptMerNameTo'))) ? '' : $this->input->post('oetRptMerNameTo'),
            'tMerCodeSelect'    => !empty($this->input->post('oetRptMerCodeSelect')) ? $this->input->post('oetRptMerCodeSelect') : "",
            'tMerNameSelect'    => !empty($this->input->post('oetRptMerNameSelect')) ? $this->input->post('oetRptMerNameSelect') : "",
            'bMerStaSelectAll'  => !empty($this->input->post('oetRptMerStaSelectAll')) && ($this->input->post('oetRptMerStaSelectAll') == 1) ? true : false,
            // Filter Pos (เครื่องจุดขาย)
            'tPosCodeFrom'      => (empty($this->input->post('oetRptPosCodeFrom'))) ? '' : $this->input->post('oetRptPosCodeFrom'),
            'tPosNameFrom'      => (empty($this->input->post('oetRptPosNameFrom'))) ? '' : $this->input->post('oetRptPosNameFrom'),
            'tPosCodeTo'        => (empty($this->input->post('oetRptPosCodeTo'))) ? '' : $this->input->post('oetRptPosCodeTo'),
            'tPosNameTo'        => (empty($this->input->post('oetRptPosNameTo'))) ? '' : $this->input->post('oetRptPosNameTo'),
            'tPosCodeSelect'    => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect'    => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",
            'bPosStaSelectAll'  => !empty($this->input->post('oetRptPosStaSelectAll')) && ($this->input->post('oetRptPosStaSelectAll') == 1) ? true : false,
            // ประเภทการชำระ
            'tRcvCodeFrom'      => !empty($this->input->post('oetRptRcvCodeFrom')) ? $this->input->post('oetRptRcvCodeFrom') : "",
            'tRcvNameFrom'      => !empty($this->input->post('oetRptRcvNameFrom')) ? $this->input->post('oetRptRcvNameFrom') : "",
            'tRcvCodeTo'        => !empty($this->input->post('oetRptRcvCodeTo')) ? $this->input->post('oetRptRcvCodeTo') : "",
            'tRcvNameTo'        => !empty($this->input->post('oetRptRcvNameTo')) ? $this->input->post('oetRptRcvNameTo') : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'      => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'        => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
            'nPosType'          => !empty($this->input->post('ocmPosType')) ? $this->input->post('ocmPosType') : ""
        ];
        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams    = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {
            // Execute Stored Procedure
            $this->mRptSaleRecive->FSnMExecStoreReport($this->aRptFilter);
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
                    $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * LastUpdate: 24/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
    */
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase) {
        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage'      => $this->nPerPage,
            'nPage'         => 1, // เริ่มรายงานหน้าแรก
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'paDataFilter'  =>$paDataSwitchCase['paDataFilter']
        );
        $aDataReport    = $this->mRptSaleRecive->FSaMGetDataReport($aDataWhereRpt);
        $aReportGroup           = array();
        $aReportGroupBnk        = array();
        $aReportGroupBnklist    = array();
        $nSumBnkArray           = array(); //รวมค่าตามธนาคาร
        $paFooterSumData        = array();
        if(!empty($aDataReport['aRptData'])){
            foreach($aDataReport['aRptData'] as $aValue){
                if(!empty($aValue["FTBnkCode"])){
                    $aReportGroupBnklist['credit'][$aValue["FTBnkCode"]][]  = array(
                        'tFTXshDocNo'=>$aValue['FTXshDocNo'],
                        'dFDXrcRefDate'=>$aValue['FDXrcRefDate'],
                        'nFCXrcNet'=>$aValue['FCXrcNet'],
                    );
                }
                $nSumFooterFCXrcNet = number_format($aValue["FCXrcNet_Footer"], 2);
                $paFooterSumData    = array($this->aText['tRptTotalSub'], 'N', 'N', 'N',  $nSumFooterFCXrcNet);
            }
            $nSumBnkArray = array(); //รวมค่าตามธนาคาร
            if(!empty($aReportGroupBnklist['credit'])){
                foreach($aReportGroupBnklist['credit'] as $tBnkCode => $aBnkVale){
                    if(!empty($aReportGroupBnklist['credit'][$tBnkCode])){
                        foreach($aReportGroupBnklist['credit'][$tBnkCode] as $aSubbnk){
                            @$nSumBnkArray[$tBnkCode]   = @$nSumBnkArray[$tBnkCode]+$aSubbnk['nFCXrcNet'];
                        }
                    }
                }
            }
        }
        // ข้อมูล Render Report
        $aDataReport['aReportGroup']        = $aReportGroup;
        $aDataReport['aReportGroupBnk']     = $aReportGroupBnk;
        $aDataReport['aReportGroupBnklist'] = $aReportGroupBnklist;
        $aDataReport['nSumBnkArray']        = $nSumBnkArray;
        $aDataViewPdt   = array(
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataFilter'       => $paDataSwitchCase['paDataFilter'],
            'paFooterSumData'   => $paFooterSumData
        );
        // Load View Advance Table
        $tRptView       = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleRecive', 'wRptSaleReciveHtml', $aDataViewPdt);
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

    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 19/07/2019 Wasin(Yoshi)
     * LastUpdate: 24/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {
        $aDataFilter    = json_decode($this->input->post('ohdRptDataFilter'), true);
        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt  = array(
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'paDataFilter'  => $aDataFilter
        );
        $aDataReport            = $this->mRptSaleRecive->FSaMGetDataReport($aDataWhereRpt);
        $aReportGroup           = array();
        $aReportGroupBnk        = array();
        $aReportGroupBnklist    = array();
        $aReportGroupBnkSum     = 0;
        $nOther = 0;
        $nFTBnkCode=0;
        $sumCash = 0;
        $sumBnk = 0;
        $sumOther = 0;
        $nSumBnkArray=array();///รวมค่าตามธนาคาร
        $paFooterSumData = array();
        if(!empty($aDataReport['aRptData'])){
            foreach($aDataReport['aRptData'] as $aValue){



                if(!empty($aValue["FTBnkCode"])){
                $aReportGroupBnklist['credit'][$aValue["FTBnkCode"]][] = array(
                    'tFTXshDocNo'=>$aValue['FTXshDocNo'],
                    'dFDXrcRefDate'=>$aValue['FDXrcRefDate'],
                    'nFCXrcNet'=>$aValue['FCXrcNet'],

                 );
                }

                $nSumFooterFCXrcNet = number_format($aValue["FCXrcNet_Footer"], 2);
                $paFooterSumData = array($this->aText['tRptTotalSub'], 'N', 'N', 'N',  $nSumFooterFCXrcNet);




            }



                     $nSumBnkArray=array();///รวมค่าตามธนาคาร

                     if(!empty($aReportGroupBnklist['credit'])){
                         foreach($aReportGroupBnklist['credit'] as $tBnkCode => $aBnkVale){
                                if(!empty($aReportGroupBnklist['credit'][$tBnkCode])){
                                        foreach($aReportGroupBnklist['credit'][$tBnkCode] as $aSubbnk){
                                                @$nSumBnkArray[$tBnkCode]  = @$nSumBnkArray[$tBnkCode]+$aSubbnk['nFCXrcNet'];
                                        }
                                }

                         }


                     }
                    }

        // ข้อมูล Render Report
        $aDataReport['aReportGroup']=$aReportGroup;
        $aDataReport['aReportGroupBnk']=$aReportGroupBnk;
        $aDataReport['aReportGroupBnklist']=$aReportGroupBnklist;
        $aDataReport['nSumBnkArray']=$nSumBnkArray;


        // ข้อมูล Render Report
        $aDataViewPdt = array(
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataFilter' =>  $aDataFilter,
            'paFooterSumData' =>$paFooterSumData
        );

        // Load View Advance Table

        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleRecive', 'wRptSaleReciveHtml', $aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter' =>   $aDataFilter,
            'aDataReport' => array(
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }


    /**
     * Functionality: Render Excel Report
     * Parameters:  Function Parameter
     * Creator: 25/09/2020 Sooksanti
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSvCCallRptRenderExcel($paDataSwitchCase){
        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();
        $oWriter->openToBrowser($tFileName); // stream data directly to the browser
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptRcvTypePaid')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPayby')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSRCCouponCardNumber')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptRcvDocumentCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBnkCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSRCBank')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDate')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptRcvTotal')),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aGetDataReportParams = array(
            'nPerPage' => 0,
            'nPage' => 1, // เริ่มรายงานหน้าแรก
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'paDataFilter' => $paDataSwitchCase['paDataFilter'],
        );

        $aDataReport = $this->mRptSaleRecive->FSaMGetDataReport($aGetDataReportParams);


        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($aValue['FTRcvCode']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTRcvName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXrcRefNo1']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTBnkCode']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTBnkName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(date('d/m/Y', strtotime($aValue["FDXrcRefDate"]))),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format(floatval($aValue['FCXrcNet']), $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $values = [
                        WriterEntityFactory::createCell($this->aText['tRptTotalFooter']),
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
                        WriterEntityFactory::createCell(number_format($aValue['FCXrcNet_Footer'], $this->nOptDecimalShow)),
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

    /**
     * Functionality: Render Excel Report Header
     * Parameters:  Function Parameter
     * Creator: 25/09/2020 Sooksanti
     * LastUpdate:
     * Return: oject
     * ReturnType: oject
     */
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
            $tFTAddV2Desc1      = "1"; $tFTAddV1Village = "";
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
            WriterEntityFactory::createCell(null),
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
                WriterEntityFactory::createCell($this->aText['tRptTaxPointByCstDocDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptTaxPointByCstDocDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
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

    /**
     * Functionality: Render Excel Report Footer
     * Parameters:  Function Parameter
     * Creator: 25/09/2020 Sooksanti
     * LastUpdate:
     * Return: oject
     * ReturnType: oject
    */
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

        if (isset($this->aRptFilter['tMerCodeSelect']) && !empty($this->aRptFilter['tMerCodeSelect'])) {
            $tMerSelect = ($this->aRptFilter['bMerStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tMerNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'] . ' : ' . $tMerSelect),
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

        if (isset($this->aRptFilter['tShpCodeSelect']) && !empty($this->aRptFilter['tShpCodeSelect'])) {
            $tShpSelect = ($this->aRptFilter['bShpStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tShpNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptAdjShopFrom'] . ' : ' . $tShpSelect),
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
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'].' : '.$tPosSelectText),
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

        // Fillterฺ Pos (เครื่องจุดขาย)) แบบช่วง
        if(!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])){
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'].' : '.$this->aRptFilter['tPosNameFrom'] . '     ' . $this->aText['tRptPosTo'].' : '.$this->aRptFilter['tPosNameTo']),
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

        if (!empty($this->aRptFilter['tRcvCodeFrom']) && !empty($this->aRptFilter['tRcvCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptRcvFrom'] . ' : ' . $this->aRptFilter['tRcvNameFrom'] . '     ' . $this->aText['tRptRcvTo'] . ' : ' . $this->aRptFilter['tRcvNameTo']),
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
        if (isset($this->aRptFilter['nPosType'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosTypeName'] . ' : ' . $this->aText['tRptPosType' . $this->aRptFilter['nPosType']]),
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
