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

class cRptTaxSalePos extends MX_Controller
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
        $this->load->model('report/reportsale/mRptTaxSalePos');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init()
    {
        $this->aText = [
            //Title
            'tTitleReport'                                  => language('report/report/report', 'tRptTaxSalePosTitle'),
            'tDatePrint'                                    => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tTimePrint'                                    => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptAddrBuilding'                              => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'                                  => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'                                   => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'                           => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'                              => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'                              => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'                                   => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'                                   => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'                                => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'                                => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'                                => language('report/report/report', 'tRptAddV2Desc2'),
            'tRptTaxSalePosDistrict'                        => language('report/report/report', 'tRptTaxSalePosDistrict'),
            'tRptFaxNo'                                     => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'                                       => language('report/report/report', 'tRptTel'),
            'tRptTaxSalePosDocNo'                           => language('report/report/report', 'tRptTaxSalePosDocNo'),
            'tRptTaxSalePosDocDate'                         => language('report/report/report', 'tRptTaxSalePosDocDate'),
            'tRptTaxSalePosDateAndLocker'                   => language('report/report/report', 'tRptTaxSalePosDateAndLocker'),
            'tRptTaxSalePosPayTypeAndDocRef'                => language('report/report/report', 'tRptTaxSalePosPayTypeAndDocRef'),
            'tRptTaxSalePosDocRef'                          => language('report/report/report', 'tRptTaxSalePosDocRef'),
            'tRptTaxSalePosPayment'                         => language('report/report/report', 'tRptTaxSalePosPayment'),
            'tRptTaxSalePosPaymentTotal'                    => language('report/report/report', 'tRptTaxSalePosPaymentTotal'),
            'tRptTaxSalePosPosGrouping'                     => language('report/report/report', 'tRptTaxSalePosPosGrouping'),
            'tRptTaxSalePosNoData'                          => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSalePosTotalSub'                        => language('report/report/report', 'tRptTaxSalePosTotalSub'),
            'tRptTaxSalePosTotalFooter'                     => language('report/report/report', 'tRptTaxSalePosTotalFooter'),
            'tRptTaxSalePosFilterBchFrom'                   => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
            'tRptTaxSalePosFilterBchTo'                     => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
            'tRptTaxSalePosFilterShopFrom'                  => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
            'tRptTaxSalePosFilterShopTo'                    => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
            'tRptTaxSalePosFilterPosFrom'                   => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo'                     => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosFilterPayTypeFrom'               => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
            'tRptTaxSalePosFilterPayTypeTo'                 => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
            'tRptTaxSalePosFilterDocDateFrom'               => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo'                 => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            'tRptTaxSalePosFilterPosFrom'                   => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo'                     => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosTaxId'                           => language('report/report/report', 'tRptTaxSalePosTaxId'),
            'tRptTaxSalePosByDateTotalSub'                  => language('report/report/report', 'tRptTaxSalePosByDateTotalSub'),
            'tRptTaxSalePosTaxMonth'                        => language('report/report/report', 'tRptTaxSalePosTaxMonth'),
            'tRptTaxSalePosYear'                            => language('report/report/report', 'tRptTaxSalePosYear'),
            'tRptTaxSaleFrom'                               => language('report/report/report', 'tRptTaxSaleFrom'),
            'tRptTaxSaleFromTo'                             => language('report/report/report', 'tRptTaxSaleFromTo'),
            'tRptTaxSalePosType'                            => language('report/report/report', 'tRptTaxSalePosType'),
            'tRptTaxSaleDateTo'                             => language('report/report/report', 'tRptTaxSaleDateTo'),
            'tRptTaxSalePosTel'                             => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTaxSalePosFax'                             => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptTaxSalePosDatePrint'                       => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tRptTaxSalePosTimePrint'                       => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptTaxSalePosBch'                             => language('report/report/report', 'tRptTaxSalePosBch'),
            'tRptDataReportNotFound'                        => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptTaxSalePosSeq'                             => language('report/report/report', 'tRptTaxSalePosSeq'),
            'tRptTaxSalePosCst'                             => language('report/report/report', 'tRptTaxSalePosCst'),
            'tRptTaxSalePosTaxID'                           => language('report/report/report', 'tRptTaxSalePosTaxID'),
            'tRptTaxSalePosComp'                            => language('report/report/report', 'tRptTaxSalePosComp'),
            'tRptTaxSalePosAmt'                             => language('report/report/report', 'tRptTaxSalePosAmt'),
            'tRptTaxSalePosAmtV'                            => language('report/report/report', 'tRptTaxSalePosAmtV'),
            'tRptTaxSalePosAmtNV'                           => language('report/report/report', 'tRptTaxSalePosAmtNV'),
            'tRptTaxSalePosTotal'                           => language('report/report/report', 'tRptTaxSalePosTotal'),
            'tRptTaxSalePosDoc'                             => language('report/report/report', 'tRptTaxSalePosDoc'),
            'tRptTaxSalePosSale'                            => language('report/report/report', 'tRptTaxSalePosSale'),
            'tRptTaxSaleBanch'                              => language('report/report/report', 'tRptTaxSaleBanch'),
            'tRptPosTypeName'                               => language('report/report/report', 'tRptPosTypeName'),
            'tRptPosType'                                   => language('report/report/report', 'tRptPosType'),
            'tRptPosType1'                                  => language('report/report/report', 'tRptPosType1'),
            'tRptPosType2'                                  => language('report/report/report', 'tRptPosType2'),
            'tRptConditionInReport'                         => language('report/report/report', 'tRptConditionInReport'),
            'tRptBchFrom'                                   => language('report/report/report', 'tRptBchFrom'),
            'tRptAdjMerChantFrom'                           => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'                             => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'                               => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'                                 => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'                                => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'                                  => language('report/report/report', 'tRptAdjPosTo'),
            'tRptBranch'                                    => language('report/report/report', 'tRptBranch'),
            'tRptTotal'                                     => language('report/report/report', 'tRptTotal'),
            'tRPCTaxNo'                                     => language('report/report/report', 'tRPCTaxNo'),
            'tRptConditionInReport'                         => language('report/report/report', 'tRptConditionInReport'),
            'tRptMerFrom'                                   => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'                                     => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'                                  => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                                    => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'                                   => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                                     => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeTo'                                    => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom'                                  => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom'                                   => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'                                     => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'                                  => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'                                    => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom'                                => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'                                  => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAll'                                       => language('report/report/report', 'tRptAll'),
            'tRptValue'                                     => language('report/report/report', 'tRptValue'),
            'tRptTaxSaleHeadQuarTers'                       => language('report/report/report', 'tRptTaxSaleHeadQuarTers'),
            'tRptBarchName'                                 => language('report/report/report', 'tRptBarchName'),
            'tRptCstBusiness1'                              => language('report/report/report', 'tRptCstBusiness1'),
            'tRptCstBusiness2'                              => language('report/report/report', 'tRptCstBusiness2'),
            'tRptDatePrint'                                 => language('report/report/report', 'tRptDatePrint'),
            'tRptTimePrint'                                 => language('report/report/report', 'tRptTimePrint'),
            'tRptTotalFooter'                               => language('report/report/report', 'tRptTotalFooter'),
            'tRptTaxPointByCstDocDateFrom'                  => language('report/report/report', 'tRptTaxPointByCstDocDateFrom'),
            'tRptTaxPointByCstDocDateTo'                    => language('report/report/report', 'tRptTaxPointByCstDocDateTo'),
            'tRptSaleByCashierAndPosFilterDocDateFrom'      => language('report/report/report', 'tRptSaleByCashierAndPosFilterDocDateFrom'),
            'tRptSaleByCashierAndPosFilterDocDateTo'        => language('report/report/report', 'tRptSaleByCashierAndPosFilterDocDateTo'),
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
            'tUserSession'          => $this->tUserSessionID,
            'tCompName'             => $tFullHost,
            'tRptCode'              => $this->tRptCode,
            'nLangID'               => $this->nLngID,
            'tTypeSelect'           => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            // สาขา(Branch)
            'tBchCodeFrom'          => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'          => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'            => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'            => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'        => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'        => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'      => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // ร้านค้า(Shop)
            'tShpCodeFrom'          => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tShpNameFrom'          => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tShpCodeTo'            => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tShpNameTo'            => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            'tShpCodeSelect'        => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'        => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'      => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,

            // Filter Merchant (กลุ่มธุรกิจ)
            'tMerCodeFrom'          => (empty($this->input->post('oetRptMerCodeFrom'))) ? '' : $this->input->post('oetRptMerCodeFrom'),
            'tMerNameFrom'          => (empty($this->input->post('oetRptMerNameFrom'))) ? '' : $this->input->post('oetRptMerNameFrom'),
            'tMerCodeTo'            => (empty($this->input->post('oetRptMerCodeTo'))) ? '' : $this->input->post('oetRptMerCodeTo'),
            'tMerNameTo'            => (empty($this->input->post('oetRptMerNameTo'))) ? '' : $this->input->post('oetRptMerNameTo'),
            'tMerCodeSelect'        => !empty($this->input->post('oetRptMerCodeSelect')) ? $this->input->post('oetRptMerCodeSelect') : "",
            'tMerNameSelect'        => !empty($this->input->post('oetRptMerNameSelect')) ? $this->input->post('oetRptMerNameSelect') : "",
            'bMerStaSelectAll'      => !empty($this->input->post('oetRptMerStaSelectAll')) && ($this->input->post('oetRptMerStaSelectAll') == 1) ? true : false,

            // Filter Pos (เครื่องจุดขาย)
            'tPosCodeFrom'          => (empty($this->input->post('oetRptPosCodeFrom'))) ? '' : $this->input->post('oetRptPosCodeFrom'),
            'tPosNameFrom'          => (empty($this->input->post('oetRptPosNameFrom'))) ? '' : $this->input->post('oetRptPosNameFrom'),
            'tPosCodeTo'            => (empty($this->input->post('oetRptPosCodeTo'))) ? '' : $this->input->post('oetRptPosCodeTo'),
            'tPosNameTo'            => (empty($this->input->post('oetRptPosNameTo'))) ? '' : $this->input->post('oetRptPosNameTo'),
            'tPosCodeSelect'        => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect'        => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",
            'bPosStaSelectAll'      => !empty($this->input->post('oetRptPosStaSelectAll')) && ($this->input->post('oetRptPosStaSelectAll') == 1) ? true : false,

            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'          => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'            => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",

            // ชื่อสาขา
            'tGetCompanyInfo'       => $this->tBchCodeLogin,

            // ประเภทเครื่องจุดขาย(TypePos)
            'tPosType'              => !empty($this->input->post('ocmPosType')) ? $this->input->post('ocmPosType') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin,
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){

        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {

            // Execute Stored Procedure
            $this->mRptTaxSalePos->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
            ];
            $this->nRows = $this->mRptTaxSalePos->FSnMCountRowInTemp($aCountRowParams);

            // Report Type
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptExportFile();
                    break;
                case 'pdf':
                    break;
            }
        }
    }

    // ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrint(){
        try {
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
                'aRptFilter' => $this->aRptFilter,
            ];
            $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aDataReportParams);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo' => $this->aCompanyInfo,
                'aDataReport' => $aDataReport,
                'aDataTextRef' => $this->aText,
                'aDataFilter' => $this->aRptFilter,
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/RptTaxSalePos', 'wRptTaxSalePosHtml', $aDataViewRptParams);

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
                ],
            ];
            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrintClickPage(){

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter' => $aDataFilter,
        ];
        $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aDataReportParams);
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $aDataFilter,
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/RptTaxSalePos', 'wRptTaxSalePosHtml', $aDataViewRptParams);

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
                'rtDesc' => 'success',
            ),
        );
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /** =========== End Render View ====================================== */
    }

    // Functionality: Click Page Report (Report Viewer)
    public function FSoCChkDataReportInTableTemp(){
        try {
            $aDataCountData = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
            ];

            $nDataCountPage = $this->mRptTaxSalePos->FSnMCountRowInTemp($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent' => 1,
                'tMessage' => 'Success Count Data All',
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage(),
            );
        }
        echo json_encode($aResponse);
    }

    // Excel ส่วนกลาง
    public function FSvCCallRptExportFile(){
        $tFileName = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';

        $oWriter = WriterEntityFactory::createXLSXWriter();

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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPosCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPosTypeName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPosRegNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosDocDate')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosDocNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosDocRef')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCustCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCustName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRPCTaxNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSaleFullPosComp')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCstBchCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCstBusiness')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosAmt2')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosAmtV')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosAmtNV')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTaxSalePosTotal')),
            WriterEntityFactory::createCell(null),
        ];

        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage'          => 0,
            'nPage'             => $this->nPage,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'tUsrSessionID'     => $this->tUserSessionID,
            'aRptFilter'        => $this->aRptFilter,
        ];
        $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aDataReportParams);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                switch ($aValue['FNAppType']) {
                    case 1:
                        $tAppType = "Pos";
                        break;
                    case 2:
                        $tAppType = "Vending";
                        break;
                    default:
                        $tAppType = "-";
                        break;
                }

                if ($aValue['FTCstBchCode'] == '1') {
                    $tCstBchCode = $this->aText['tRptTaxSaleHeadQuarTers'];
                } else {
                    $tCstBchCode = $this->aText['tRptBarchName'];
                }

                if ($aValue['FTCstBusiness'] == '1') {
                    $tCstBusiness = $this->aText['tRptCstBusiness1'];
                } else if ($aValue['FTCstBusiness'] == '2') {
                    $tCstBusiness = $this->aText['tRptCstBusiness2'];
                } else {
                    $tCstBusiness = '-';
                }

                $tFDXshDocDate              = empty($aValue['FDXshDocDate']) ? '' : date("d/m/Y", strtotime($aValue['FDXshDocDate']));
                $cFCXshAmt                  = FCNnGetNumeric(empty($aValue['FCXshAmt']) ? 0 : $aValue['FCXshAmt']);
                $cFCXshVat                  = FCNnGetNumeric(empty($aValue['FCXshVat']) ? 0 : $aValue['FCXshVat']);
                $cFCXshAmtNV                = FCNnGetNumeric(empty($aValue['FCXshAmtNV']) ? 0 : $aValue['FCXshAmtNV']);
                $cFCXshGrandTotal           = FCNnGetNumeric(empty($aValue['FCXshGrandTotal']) ? 0 : $aValue['FCXshGrandTotal']);
                $cFCXshAmt_Footer           = empty($aValue['FCXshAmt_Footer']) ? 0 : $aValue['FCXshAmt_Footer'];
                $cFCXshVat_Footer           = empty($aValue['FCXshVat_Footer']) ? 0 : $aValue['FCXshVat_Footer'];
                $cFCXshAmtNV_Footer         = empty($aValue['FCXshAmtNV_Footer']) ? 0 : $aValue['FCXshAmtNV_Footer'];
                $cFCXshGrandTotal_Footer    = empty($aValue['FCXshGrandTotal_Footer']) ? 0 : $aValue['FCXshGrandTotal_Footer'];

                $values = [
                    WriterEntityFactory::createCell(($aValue['FTBchCode'] == "" ? "-" : $aValue['FTBchCode'])),
                    WriterEntityFactory::createCell(($aValue['FTBchName'] == "" ? "-" : $aValue['FTBchName'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTPosCode'] == "" ? "-" : $aValue['FTPosCode'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tAppType),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTPosRegNo'] == "" ? "-" : $aValue['FTPosRegNo'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tFDXshDocDate),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTXshDocNo'] == "" ? "-" : $aValue['FTXshDocNo'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTXshDocRef'] == "" ? "-" : $aValue['FTXshDocRef'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstCode'] == "" ? "-" : $aValue['FTCstCode'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstName'] == "" ? "-" : $aValue['FTCstName'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstTaxNo'] == "" ? "-" : $aValue['FTCstTaxNo'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTEstablishment'] == "" ? "-" : $aValue['FTEstablishment'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tCstBchCode),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tCstBusiness),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($cFCXshAmt),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($cFCXshVat),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($cFCXshAmtNV),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($cFCXshGrandTotal),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);


                if($aValue['PARTTITIONBYBCH'] == $aValue['PARTTITIONBYBCH_COUNT']){
                    $cFCXshAmt_SUMBCH           = empty($aValue['FCXshAmt_SUMBCH']) ? 0 : $aValue['FCXshAmt_SUMBCH'];
                    $cFCXshVat_SUMBCH           = empty($aValue['FCXshVat_SUMBCH']) ? 0 : $aValue['FCXshVat_SUMBCH'];
                    $cFCXshAmtNV_SUMBCH         = empty($aValue['FCXshAmtNV_SUMBCH']) ? 0 : $aValue['FCXshAmtNV_SUMBCH'];
                    $cFCXshGrandTotal_SUMBCH    = empty($aValue['FCXshGrandTotal_SUMBCH']) ? 0 : $aValue['FCXshGrandTotal_SUMBCH'];

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
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshAmt_SUMBCH)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVat_SUMBCH)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshAmtNV_SUMBCH)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshGrandTotal_SUMBCH)),
                        WriterEntityFactory::createCell(null),
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
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshAmt_Footer)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshVat_Footer)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshAmtNV_Footer)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(FCNnGetNumeric($cFCXshGrandTotal_Footer)),
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

    // Excel ส่วนหัว
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

    // Excel ส่วนท้าย
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
            $tBchSelectText = ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptTaxSalePosFilterBchFrom'] . ' : ' . $tBchSelectText),
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

        // เครื่องจุดขาย (Pos) แบบเลือก
        if (!empty($this->aRptFilter['tPosCodeSelect'])) {
            $tPosSelectText = ($this->aRptFilter['bPosStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tPosNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptTaxSalePosFilterPosFrom'] . ' : ' . $tPosSelectText),
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
                WriterEntityFactory::createCell($this->aText['tRptTaxSalePosFilterPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'] . '     ' . $this->aText['tRptTaxSalePosFilterPosTo'] . ' ' . $this->aRptFilter['tPosNameTo']),
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

        // ฟิวเตอร์ข้อมูล ประเภทจุดขาย
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
