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


class RptStkOrderByDay_controller extends MX_Controller
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
        $this->load->model('report/reportFitauto/RptStkOrderByDay_model');

        // Init Report
        $this->init();
        parent::__construct();
    }


    private function init()
    {
        $this->aText = [
            'tTitleReport'                      => language('report/report/report', 'tRptPoFormTitle'),
            'tDatePrint'                        => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'                        => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            // Address Lang
            'tRptAddrBuilding'                  => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'                      => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'                       => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'               => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'                  => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'                  => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'                       => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'                       => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'                    => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'                    => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'                    => language('report/report/report', 'tRptAddV2Desc2'),
            'tRPCTaxNo'                         => language('report/report/report', 'tRPCTaxNo'),
            'tRptFaxNo'                         => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'                           => language('report/report/report', 'tRptTel'),
            
            // Table Label
            'tRptSatificationList'              => language('report/report/report', 'tRptSatificationList'),
            'tRptSatificationList'              => language('report/report/report', 'tRptSatificationList'),
            'tRptSatification5score'            => language('report/report/report', 'tRptSatification5score'),
            'tRptSatification4score'            => language('report/report/report', 'tRptSatification4score'),
            'tRptSatification3score'            => language('report/report/report', 'tRptSatification3score'),
            'tRptSatification2score'            => language('report/report/report', 'tRptSatification2score'),
            'tRptSatification1score'            => language('report/report/report', 'tRptSatification1score'),
            'tRptSatificationAvg'               => language('report/report/report', 'tRptSatificationAvg'),
            'tRptSatificationStandard'          => language('report/report/report', 'tRptSatificationStandard'),
            'tRptAdjStkVDTotalSub'              => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter'           => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptUnit'                          => language('report/report/report', 'tRptUnit'),

            // Fillter AdjStock
            'tRptAdjMerChantFrom'               => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'                 => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'                   => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'                     => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'                    => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'                      => language('report/report/report', 'tRptAdjPosTo'),
            'tRptAdjWahFrom'                    => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'                      => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAdjDateFrom'                   => language('report/report/report', 'tRptAdjDateFrom'),
            'tRptAdjDateTo'                     => language('report/report/report', 'tRptAdjDateTo'),
            'tRptAgnFrom'                       => language('report/report/report', 'tRptGrpAgencyF'),
            'tRptBchFrom'                       => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                         => language('report/report/report', 'tRptBchTo'),

            // No Data Report
            'tRptAdjStkNoData'                  => language('common/main/main', 'tCMNNotFoundData'),
            'tRptAjdQtyAllDiff'                 => language('report/report/report', 'tRptAjdQtyAllDiff'),
            'tRptAdjStkVDTaxNo'                 => language('report/report/report', 'tRptAdjStkVDTaxNo'),
            'tRptConditionInReport'             => language('report/report/report', 'tRptConditionInReport'),
            'tRptAll'                           => language('report/report/report', 'tRptAll'),
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
            'tRptCstFrom'                       => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'                         => language('report/report/report', 'tRptCstTo'),
            'tPdtCodeFrom'                      => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'                        => language('report/report/report', 'tPdtCodeTo'),
            'tRptCat1F'                         => language('report/report/report', 'tRptCat1F'),
            'tRptPosFrom'                       => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                         => language('report/report/report', 'tRptPosTo'),
            'tRptNoData'                        => language('report/report/report', 'tRptNoData')
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

        // Report Fillter
        $this->aRptFilter   = [
            'tUserSession'          => $this->tUserSessionID,
            'tCompName'             => $this->tCompName,
            'tRptCode'              => $this->tRptCode,
            'nLangID'               => $this->nLngID,
            'tTypeSelect'           => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter Agency (ตัวแทนขาย)
            'tAgnCodeSelect'        => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",
            'tAgnName'              => !empty($this->input->post('oetSpcAgncyName')) ? $this->input->post('oetSpcAgncyName') : "",

            //Fillter สาขา
            'tBchCodeSelect'        => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'        => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'      => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter หมวดหมู่
            'tCate1From'            => !empty($this->input->post('oetRptCate1CodeFrom')) ? $this->input->post('oetRptCate1CodeFrom') : "",
            'tCate1FromName'        => !empty($this->input->post('oetRptCate1NameFrom')) ? $this->input->post('oetRptCate1NameFrom') : "",
            'tCate2From'            => !empty($this->input->post('oetRptCate2CodeFrom')) ? $this->input->post('oetRptCate2CodeFrom') : "",
            'tCate2FromName'        => !empty($this->input->post('oetRptCate2NameFrom')) ? $this->input->post('oetRptCate2NameFrom') : "",

            // สินค้า
            'tRptPdtCodeFrom'       => !empty($this->input->post('oetRptPdtCodeFrom')) ? $this->input->post('oetRptPdtCodeFrom') : "",
            'tRptPdtNameFrom'       => !empty($this->input->post('oetRptPdtNameFrom')) ? $this->input->post('oetRptPdtNameFrom') : "",
            'tRptPdtCodeTo'         => !empty($this->input->post('oetRptPdtCodeTo')) ? $this->input->post('oetRptPdtCodeTo') : "",
            'tRptPdtNameTo'         => !empty($this->input->post('oetRptPdtNameTo')) ? $this->input->post('oetRptPdtNameTo') : "",

            // Filter Pos (คลังสินค้า)
            'tWahCodeFrom'          => (empty($this->input->post('oetRptWahCodeFrom'))) ? '' : $this->input->post('oetRptWahCodeFrom'),
            'tWahNameFrom'          => (empty($this->input->post('oetRptWahNameFrom'))) ? '' : $this->input->post('oetRptWahNameFrom'),
            'tWahCodeTo'            => (empty($this->input->post('oetRptWahCodeTo'))) ? '' : $this->input->post('oetRptWahCodeTo'),
            'tWahNameTo'            => (empty($this->input->post('oetRptWahNameTo'))) ? '' : $this->input->post('oetRptWahNameTo'),

            // Filter Document Date (วันที่สร้างเอกสาร)
            'tDocDateFrom'          => (empty($this->input->post('oetRptDocDateFrom'))) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'            => (empty($this->input->post('oetRptDocDateTo'))) ? '' : $this->input->post('oetRptDocDateTo'),

            'tPdtStaActive' => !empty($this->input->post('ocmRptPdtStaActive')) ? $this->input->post('ocmRptPdtStaActive') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {
            // Execute Stored Procedure
            $this->RptStkOrderByDay_model->FSnMExecStoreReport($this->aRptFilter);

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
            }
        }
    }

    // Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
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
            
            $aDataReport = $this->RptStkOrderByDay_model->FSaMGetDataReport($aDataWhere);
            
            // echo "<pre>";
            // print_r ($aDataReport);
            // echo "</pre>";
            // exit;
            

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptStkOrderByDay', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport'   => $this->aText['tTitleReport'],
                'tRptTypeExport' => $this->tRptExportType,
                'tRptCode'       => $this->tRptCode,
                'tRptRoute'      => $this->tRptRoute,
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
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    public function FSvCCallRptViewBeforePrintClickPage(){
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'  => $this->nPerPage,
            'nPage'     => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->RptStkOrderByDay_model->FSaMGetDataReport($aDataWhereRpt);
        
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );        

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptStkOrderByDay', $aDataViewRptParams);

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
                'rnAllRow' => $aDataReport['aPagination']['nRowIDEnd'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success'
            )
        );
        

        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /** =========== End Render View ====================================== */
    }

    //Excel ส่วนหัว
    public function FSoCCallRptRenderHedaerExcel(){
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


        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell($this->aText['tRptAdjDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptAdjDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
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
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    //Excel ส่วนกลาง
    public function FSvCCallRptExportFile(){
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBranchName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptProductCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptProductName')),
            WriterEntityFactory::createCell(language('report/report/report', 'หมวดหมู่สินค้าหลัก')),
            WriterEntityFactory::createCell(language('report/report/report', 'หมวดหมู่สินค้าย่อย')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อหมวดหมู่สินค้าย่อย')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBringF')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptIn')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptEx')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTrfReceived')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTrfPay')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSale')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkReturn')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptAdjudUp')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptAdjudDown')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptInven')),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage'      => 0,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->RptStkOrderByDay_model->FSaMGetDataReport($aDataReportParams);
        
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $nDayEnd = $aValue['FCStkQtyDayEnd'];
                $nMonthEnd = $aValue['FCStkQtyMonEnd'];
                $nAmountEnd = ($nDayEnd)+($nMonthEnd);
                $nStkQtyIn      = $aValue['FCStkQtyIn'];
                $nStkQtyOut     = $aValue['FCStkQtyOut'];
                $nStkQtyInfIn   = $aValue['FCStkQtyInfIn'];
                $nStkQtyInfOut  = $aValue['FCStkQtyInfOut'];
                $nStkQtySale    = $aValue['FCStkQtySale'];
                $nStkQtyCN      = $aValue['FCStkQtyCN'];
                $nStkQtyAdjUp   = $aValue['FCStkQtyAdjUp'];
                $nStkQtyAdjDown = $aValue['FCStkQtyAdjDown'];
                $nTotal         = ($nAmountEnd)+($nStkQtyIn)-($nStkQtyOut)+($nStkQtyInfIn)-($nStkQtyInfOut)-($nStkQtySale)+($nStkQtyCN)+($nStkQtyAdjUp)-($nStkQtyAdjDown);
                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchCode']),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell($aValue['FTPdtGrp']),
                    WriterEntityFactory::createCell($aValue['FTPdtDptCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtDptName']),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nAmountEnd)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyIn)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyOut)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyInfIn)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyInfOut)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtySale)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyCN)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyAdjUp)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nStkQtyAdjDown)),
                    WriterEntityFactory::createCell(FCNnGetNumeric($nTotal)),
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
            }
            
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);

        $oWriter->close();
    }

    //Excel ส่วนท้าย
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
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        // ตัวแทนขาย
        if (isset($this->aRptFilter['tAgnCodeSelect']) && !empty($this->aRptFilter['tAgnCodeSelect'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptAgnFrom'] . ' : ' . $this->aRptFilter['tAgnName']),
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

         //คลัง
         if (!empty($this->aRptFilter['tWahCodeFrom']) && !empty($this->aRptFilter['tWahCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptAdjWahFrom'] . ' : ' . $this->aRptFilter['tWahNameFrom'] . '     ' . $this->aText['tRptAdjWahTo'] . ' : ' . $this->aRptFilter['tWahNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //สินค้า
        if (!empty($this->aRptFilter['tRptPdtCodeFrom']) && !empty($this->aRptFilter['tRptPdtCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tPdtCodeFrom'] . ' : ' . $this->aRptFilter['tRptPdtNameFrom'] . '     ' . $this->aText['tPdtCodeTo'] . ' : ' . $this->aRptFilter['tRptPdtNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //หมวดหมู่สินค้าหลัก
        if (!empty($this->aRptFilter['tCate1From'])) {
            $aCells = [
                WriterEntityFactory::createCell('หมวดหมู่สินค้าหลัก : ' . $this->aRptFilter['tCate1FromName']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //หมวดหมู่สินค้าย่อย
        if (!empty($this->aRptFilter['tCate2From'])) {
            $aCells = [
                WriterEntityFactory::createCell('หมวดหมู่สินค้าย่อย : ' . $this->aRptFilter['tCate2FromName']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //หมวดหมู่สินค้าย่อย
        if (!empty($this->aRptFilter['tPdtStaActive'])) {
            $tStaActive = '';
            if ($this->aRptFilter['tPdtStaActive'] == 1) {
                $tStaActive = 'เคลื่อนไหว';
            }elseif ($this->aRptFilter['tPdtStaActive'] == 2) {
                $tStaActive = 'ไม่เคลื่อนไหว';
            }else{
                $tStaActive = 'ทั้งหมด';
            }
            $aCells = [
                WriterEntityFactory::createCell('สถานะเคลื่อนไหว : ' . $tStaActive),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

}
