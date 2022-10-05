<?php
defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

date_default_timezone_set("Asia/Bangkok");

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;


class Rptcreditaging_controller extends MX_Controller
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
        $this->load->model('report/reportFitauto/Rptcreditaging_model');
        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init()
    {
        $this->aText = [
            'tTitleReport'      => language('report/report/report', 'tRptCreditAgingTitle'),
            'tDatePrint'        => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tTimePrint'        => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptAdjStkNoData'  => language('report/report/report', 'tRptAdjStkNoData'),
            'tPdtnu'            => language('report/report/report', 'tPdtnu'),
            'tPdtCode'          => language('report/report/report', 'tPdtCode'),
            'tPdtName'          => language('report/report/report', 'tPdtName'),
            'tPgpChainName'     => language('report/report/report', 'tPgpChainName'),
            'tPtyName'          => language('report/report/report', 'tPtyName'),
            'tPdtSaleType'      => language('report/report/report', 'tPdtSaleType'),
            'tBarCode'          => language('report/report/report', 'tBarCode'),
            'tPunCode'          => language('report/report/report', 'tPunCode'),
            'tPunName'          => language('report/report/report', 'tPunName'),
            'tPdtUnitFact'      => language('report/report/report', 'tPdtUnitFact'),
            'tPdtPriceRET'      => language('report/report/report', 'tPdtPriceRET'),
            'tPdtCostInPerUnit'         => language('report/report/report', 'tPdtCostInPerUnit'),
            'tPdtCostInTotal'           => language('report/report/report', 'tPdtCostInTotal'),
            'tPgdPriceRetTotal'         => language('report/report/report', 'tPgdPriceRetTotal'),
            'tRptBchFrom'           => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom'          => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'            => language('report/report/report', 'tRptShopTo'),
            'tRptDateFrom'          => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'            => language('report/report/report', 'tRptDateTo'),
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
            'tRptTel'               => language('report/report/report', 'tRptAddrTel'),
            'tRptFaxNo'             => language('report/report/report', 'tRptAddrFax'),
            'tRptAdjMerChantFrom'   => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'     => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'       => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'         => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'        => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'          => language('report/report/report', 'tRptAdjPosTo'),
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
            'tRptPdtType1'          => language('report/report/report', 'tRptPdtType1'),
            'tRptPdtType2'          => language('report/report/report', 'tRptPdtType2'),
            'tRptPdtType3'          => language('report/report/report', 'tRptPdtType3'),
            'tRptPdtType4'          => language('report/report/report', 'tRptPdtType4'),
            'tRptPdtType6'          => language('report/report/report', 'tRptPdtType6'),
            'tRptBrandFrom'         => language('report/report/report', 'tRptBrandFrom'),
            'tRptBrandTo'           => language('report/report/report', 'tRptBrandTo'),
            'tRptModelFrom'         => language('report/report/report', 'tRptModelFrom'),
            'tRptModelTo'           => language('report/report/report', 'tRptModelTo'),
            'tRptPdtMoving1'        => language('report/report/report', 'tRptPdtMoving1'),
            'tRptPdtMoving2'        => language('report/report/report', 'tRptPdtMoving2'),
            'tRptTitlePdtMoving'    => language('report/report/report', 'tRptTitlePdtMoving'),
            'tRptStaVat'            => language('report/report/report', 'tRptStaVat'),
            'tRptStaVa1'            => language('report/report/report', 'tRptStaVa1'),
            'tRptStaVa2'            => language('report/report/report', 'tRptStaVa2'),
            'tRptSplFrom'           => language('report/report/report', 'tRptSplFrom'),
            'tRptSplTo'             => language('report/report/report', 'tRptSplTo'),
            'tRptSplGrpForm'        => language('report/report/report', 'tRptSplGrpForm'),
            'tRptSplGrpTo'          => language('report/report/report', 'tRptSplGrpTo'),
            'tRptSplTypeForm'       => language('report/report/report', 'tRptSplTypeForm'),
            'tRptSplTypeTo'         => language('report/report/report', 'tRptSplTypeTo'),
            'tRptPhStaPaid1'        => language('report/report/report', 'tRptPhStaPaid1'),
            'tRptPhStaPaid2'        => language('report/report/report', 'tRptPhStaPaid2'),
            'tRptPhStaPaid3'        => language('report/report/report', 'tRptPhStaPaid3'),
            'tStaPaid'              => language('report/report/report', 'tStapaid')
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

        $this->aRptFilter = [
            'tUserSession'          => $this->tUserSessionID,
            'tCompName'             => $tFullHost,
            'tRptCode'              => $this->tRptCode,
            'nLangID'               => $this->nLngID,
            'tTypeSelect'           => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            'tAgnCode'              => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : '',

            // สาขา
            'tBchCodeFrom'          => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'          => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'            => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'            => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'        => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'        => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'      => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter Shop (ร้านค้า)
            'tShpCodeFrom'          => (empty($this->input->post('oetRptShpCodeFrom'))) ? '' : $this->input->post('oetRptShpCodeFrom'),
            'tShpNameFrom'          => (empty($this->input->post('oetRptShpNameFrom'))) ? '' : $this->input->post('oetRptShpNameFrom'),
            'tShpCodeTo'            => (empty($this->input->post('oetRptShpCodeTo'))) ? '' : $this->input->post('oetRptShpCodeTo'),
            'tShpNameTo'            => (empty($this->input->post('oetRptShpNameTo'))) ? '' : $this->input->post('oetRptShpNameTo'),
            'tShpCodeSelect'        => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'        => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'      => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,

            //วันที่เอกสาร
            'tRptDocDateFrom'       => (!empty($this->input->post('oetRptDocDateFrom'))) ? $this->input->post('oetRptDocDateFrom') : "",
            'tRptDocDateTo'         => (!empty($this->input->post('oetRptDocDateTo'))) ? $this->input->post('oetRptDocDateTo') : "",

            //วันที่เอกสาร
            'tPdtDateFrom'          => !empty($this->input->post('oetRptOneDateFrom')) ? $this->input->post('oetRptOneDateFrom') : "",
            
            //ผู้จำหน่าย
            'tPdtSupplierCodeFrom'  => !empty($this->input->post('oetRptSupplierCodeFrom')) ? $this->input->post('oetRptSupplierCodeFrom') : "",
            'tPdtSupplierCodeTo'    => !empty($this->input->post('oetRptSupplierCodeTo')) ? $this->input->post('oetRptSupplierCodeTo') : "",
            'tPdtSupplierNameFrom'  => !empty($this->input->post('oetRptSupplierNameFrom')) ? $this->input->post('oetRptSupplierNameFrom') : "",
            'tPdtSupplierNameTo'    => !empty($this->input->post('oetRptSupplierNameTo')) ? $this->input->post('oetRptSupplierNameTo') : "",

            //กลุ่มผู้จำหน่าย
            'tPdtSgpCodeFrom'       => !empty($this->input->post('oetRptSgpCodeFrom')) ? $this->input->post('oetRptSgpCodeFrom') : "",
            'tPdtSgpNameFrom'       => !empty($this->input->post('oetRptSgpNameFrom')) ? $this->input->post('oetRptSgpNameFrom') : "",
            'tPdtSgpNameTo'         => !empty($this->input->post('oetRptSgpNameTo')) ? $this->input->post('oetRptSgpNameTo') : "",
            'tPdtSgpCodeTo'         => !empty($this->input->post('oetRptSgpCodeTo')) ? $this->input->post('oetRptSgpCodeTo') : "",

            //ประเภทผู้จำหน่าย
            'tPdtStyCodeFrom'       => !empty($this->input->post('oetRptStyCodeFrom')) ? $this->input->post('oetRptStyCodeFrom') : "",
            'tPdtStyNameFrom'       => !empty($this->input->post('oetRptStyNameFrom')) ? $this->input->post('oetRptStyNameFrom') : "",
            'tPdtStyNameTo'         => !empty($this->input->post('oetRptStyNameTo')) ? $this->input->post('oetRptStyNameTo') : "",
            'tPdtStyCodeTo'         => !empty($this->input->post('oetRptStyCodeTo')) ? $this->input->post('oetRptStyCodeTo') : "",
            'tPdtRptPdtType'        => !empty($this->input->post('ocmRptPdtType')) ? $this->input->post('ocmRptPdtType') : "",

            //สถานะเอกสาร
            'tStaApv'               => !empty($this->input->post('ocmRptPhStaApv')) ? $this->input->post('ocmRptPhStaApv') : "",

            //สถานะ รับ/จ่ายเงิน
            'tPdtRptPhStaPaid'      => !empty($this->input->post('ocmRptPhStaPaid')) ? $this->input->post('ocmRptPhStaPaid') : ""
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {
            $this->Rptcreditaging_model->FSnMExecStoreReport($this->aRptFilter);
            $aCountRowParams = [
                'tCompName'     => $this->tCompName,
                'tRptCode'      => $this->tRptCode,
                'tSessionID'    => $this->tUserSessionID,
                'aDataFilter'   => $this->aRptFilter
            ];
            $this->nRows = $this->Rptcreditaging_model->FSnMCountRowInTemp($aCountRowParams);

            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptRenderExcel($this->aRptFilter);
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
            $aDataReportParams = [
                'nPerPage'      => $this->nPerPage,
                'nPage'         => $this->nPage,
                'tCompName'     => $this->tCompName,
                'tRptCode'      => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
                'aDataFilter'   => $this->aRptFilter
            ];
            $aDataReport = $this->Rptcreditaging_model->FSaMGetDataReport($aDataReportParams);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto/', 'wRptcreditaging', $aDataViewRptParams);

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
        $aDataReport = $this->Rptcreditaging_model->FSaMGetDataReport($aDataWhere, $aDataFilter);
        // print_r($aDataReport);
        // exit;

        // GetDataSumFootReport
        // $aDataSumFoot = $this->Rptcreditaging_model->FSaMGetDataSumFootReport($aDataWhere, $aDataFilter);


        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $this->aRptFilter
        ];
        $tViewRenderKool = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto/', 'wRptcreditaging', $aDataViewRptParams);


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
        try {
            $aDataCountData = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
                'aDataFilter' => $this->aRptFilter,
            ];

            $nDataCountPage = $this->Rptcreditaging_model->FSnMCountRowInTemp($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent' => 1,
                'tMessage' => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
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

    //ส่วนกลาง excel
    public function  FSvCCallRptRenderExcel(){
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
            WriterEntityFactory::createCell(language('report/report/report', 'รหัสผู้จำหน่าย')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อผู้จำหน่าย')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol5')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol6')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol7')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol8')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol9')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol10')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol11')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol12')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol13')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol14')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol15')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol16')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol17')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol18')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol19')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptCreditCol4'))
        ];

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
        $aDataReport = $this->Rptcreditaging_model->FSaMGetDataReport($aDataReportParams);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($aValue['FTSplCode']),
                    WriterEntityFactory::createCell($aValue['FTSplName']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXphDueDate']))),
                    WriterEntityFactory::createCell($aValue['FTXphDocNo']),
                    WriterEntityFactory::createCell(($aValue['FTXphRefInt'] == '') ? '-' : $aValue['FTXphRefInt']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXphDocDate']))),
                    WriterEntityFactory::createCell($aValue['FNXphCrTerm']),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue60'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue31And60'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue0And30'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue1'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue2And7'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue8And15'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue16And30'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue31And60'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue61And90'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue90'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FCXshLeft'] , $this->nOptDecimalShow))
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $values = [
                        WriterEntityFactory::createCell("รวม"),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue60_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue31And60_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphBFDue0And30_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue1_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue2And7_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue8And15_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue16And30_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue31And60_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue61And90_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXphOVDue90_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshLeft_Footer'], $this->nOptDecimalShow))
                    ];
                    $aRow = WriterEntityFactory::createRow($values, $oStyleColums);
                    $oWriter->addRow($aRow);
                }
            }
        }
        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); 
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();
    }

    //ส่วนหัว excel
    public function FSoCCallRptRenderHedaerExcel(){
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
            WriterEntityFactory::createCell($this->aText['tTitleReport']),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
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
            WriterEntityFactory::createCell(NULL),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport']),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);


        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
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
        }

        // สาขา
        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // วันที่มีผล
        if (!empty($this->aRptFilter['tRptDocDateFrom']) && !empty($this->aRptFilter['tRptDocDateTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptDateFrom'] . ' : ' . date("d/m/Y", strtotime($this->aRptFilter['tRptDocDateFrom'])) . '     ' . $this->aText['tRptDateTo'] . ' : ' . date("d/m/Y", strtotime($this->aRptFilter['tRptDocDateTo'])) ),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ผู้จำหน่าย
        if (!empty($this->aRptFilter['tPdtSupplierCodeFrom']) && !empty($this->aRptFilter['tPdtSupplierCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptSplFrom'] . ' : ' . $this->aRptFilter['tPdtSupplierNameFrom'] . '     ' . $this->aText['tRptSplTo'] . ' : ' . $this->aRptFilter['tPdtSupplierNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //กลุ่มผู้จำหน่าย
        if (!empty($this->aRptFilter['tPdtSgpCodeFrom']) || !empty($this->aRptFilter['tPdtSgpCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptSplGrpForm'] . ' : ' . $this->aRptFilter['tPdtSgpNameFrom'] . '     ' . $this->aText['tRptSplGrpTo'] . ' : ' . $this->aRptFilter['tPdtSgpNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ประเภทผู้จำหน่าย
        if (!empty($this->aRptFilter['tPdtStyCodeFrom']) || !empty($this->aRptFilter['tPdtStyCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptSplTypeForm'] . ' : ' . $this->aRptFilter['tPdtStyNameFrom'] . '     ' . $this->aText['tRptSplTypeTo'] . ' : ' . $this->aRptFilter['tPdtStyNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ฟิวเตอร์ข้อมูล สถานะเอกสาร 
        $tStaOdr = '';
        if ($this->aRptFilter['tStaApv'] == 1) {
            $tStaOdr = language('report/report/report', 'tRptPhStaApv0');
        } elseif ($this->aRptFilter['tStaApv'] == 2) {
            $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
        } elseif ($this->aRptFilter['tStaApv'] == 3) {
            $tStaOdr = language('report/report/report', 'tRptStaCrd3');
        } else {
            $tStaOdr = language('report/report/report', 'tRptAll');
        }
        if (isset($this->aRptFilter['tStaApv']) && !empty($this->aRptFilter['tStaApv'])) {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc') . ' : ' . $tStaOdr),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        } else {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc') . ' : ' . $tStaOdr),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // สถานะ รับ/จ่ายเงิน
        $tStaPaid = '';
        if($this->aRptFilter['tPdtRptPhStaPaid'] == '1'){
            $tStaPaid = language('report/report/report','tRptPhStaPaid1');
        }else if($this->aRptFilter['tPdtRptPhStaPaid'] == '2'){
            $tStaPaid = language('report/report/report','tRptPhStaPaid2');
        }else if($this->aRptFilter['tPdtRptPhStaPaid'] == '3'){
            $tStaPaid = language('report/report/report','tRptPhStaPaid3');
        }
        $aCells = [
            WriterEntityFactory::createCell($this->aText['tStaPaid'] . ' : ' . $tStaPaid),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        return $aMulltiRow;
    }
}
