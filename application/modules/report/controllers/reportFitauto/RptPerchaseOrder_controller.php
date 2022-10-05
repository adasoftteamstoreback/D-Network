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


class RptPerchaseOrder_controller extends MX_Controller
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
        $this->load->model('report/reportFitauto/RptPerchaseOrder_model');

        // Init Report
        $this->init();
        parent::__construct();
    }


    private function init()
    {
        $this->aText = [
            'tTitleReport'          => language('report/report/report', 'tRptPerchaseOrderTitle'),
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
            'tRPCTaxNo' => language('report/report/report', 'tRPCTaxNo'),
            'tRptFaxNo' => language('report/report/report', 'tRptFaxNo'),
            'tRptTel' => language('report/report/report', 'tRptTel'),
            
            // Table Label
            'tRptSatificationList'         => language('report/report/report', 'tRptSatificationList'),
            'tRptSatificationList'       => language('report/report/report', 'tRptSatificationList'),
            'tRptSatification5score'       => language('report/report/report', 'tRptSatification5score'),
            'tRptSatification4score'       => language('report/report/report', 'tRptSatification4score'),
            'tRptSatification3score'       => language('report/report/report', 'tRptSatification3score'),
            'tRptSatification2score'        => language('report/report/report', 'tRptSatification2score'),
            'tRptSatification1score'        => language('report/report/report', 'tRptSatification1score'),
            'tRptSatificationAvg'      => language('report/report/report', 'tRptSatificationAvg'),
            'tRptSatificationStandard'       => language('report/report/report', 'tRptSatificationStandard'),
            'tRptAdjStkVDTotalSub'      => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter'   => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptUnit'                  => language('report/report/report', 'tRptUnit'),

            // Fillter AdjStock
            'tRptAdjMerChantFrom'       => language('report/report/report', 'tRptAdjMerChantFrom'),
            'tRptAdjMerChantTo'         => language('report/report/report', 'tRptAdjMerChantTo'),
            'tRptAdjShopFrom'           => language('report/report/report', 'tRptAdjShopFrom'),
            'tRptAdjShopTo'             => language('report/report/report', 'tRptAdjShopTo'),
            'tRptAdjPosFrom'            => language('report/report/report', 'tRptAdjPosFrom'),
            'tRptAdjPosTo'              => language('report/report/report', 'tRptAdjPosTo'),
            'tRptAdjWahFrom'            => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'              => language('report/report/report', 'tRptAdjWahTo'),
            'tRptAdjDateFrom'           => language('report/report/report', 'tRptAdjDateFrom'),
            'tRptAdjDateTo'             => language('report/report/report', 'tRptAdjDateTo'),
            'tRptBchFrom'               => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                 => language('report/report/report', 'tRptBchTo'),

            // No Data Report
            'tRptAdjStkNoData'          => language('common/main/main', 'tCMNNotFoundData'),

            // Update Text Wasin(18/11/2019)
            'tRptAjdQtyAllDiff'         => language('report/report/report', 'tRptAjdQtyAllDiff'),
            'tRptAdjStkVDTaxNo'         => language('report/report/report', 'tRptAdjStkVDTaxNo'),
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),

            'tRptAll'                   => language('report/report/report', 'tRptAll'),

            // Filter Text Label
            'tRptTaxSalePosFilterBchFrom' => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
            'tRptTaxSalePosFilterBchTo' => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
            'tRptTaxSalePosFilterShopFrom' => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
            'tRptTaxSalePosFilterShopTo' => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
            'tRptTaxSalePosFilterPosFrom' => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo' => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosFilterPayTypeFrom' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
            'tRptTaxSalePosFilterPayTypeTo' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
            'tRptTaxSalePosFilterDocDateFrom' => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo' => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            'tRptTaxSalePosTaxId' => language('report/report/report', 'tRptTaxSalePosTaxId'),

            'tRptCstFrom'           => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'             => language('report/report/report', 'tRptCstTo'),

            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),

            'tRptSplFrom' => language('report/report/report', 'tRptSplFrom'),
            'tRptSplTo' => language('report/report/report', 'tRptSplTo'),

            'tRptSplGrpForm' => language('report/report/report', 'tRptSplGrpForm'),
            'tRptSplGrpTo' => language('report/report/report', 'tRptSplGrpTo'),

            'tRptSplTypeForm' => language('report/report/report', 'tRptSplTypeForm'),
            'tRptSplTypeTo' => language('report/report/report', 'tRptSplTypeTo'),

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

        // Report Fillter
        $this->aRptFilter = [
            'tSessionID'  => $this->tUserSessionID,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,

            'tTypeSelect'          => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter Agency (ตัวแทนขาย)
            'tAgnCodeSelect' => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Fillter สาขา
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            //สถานะเอกสาร
            'tStaApv' => !empty($this->input->post('ocmRptPhStaApv')) ? $this->input->post('ocmRptPhStaApv') : "",

            //ผู้จำหน่าย
            'tPdtSupplierCodeFrom' => !empty($this->input->post('oetRptSupplierCodeFrom')) ? $this->input->post('oetRptSupplierCodeFrom') : "",
            'tPdtSupplierNameFrom' => !empty($this->input->post('oetRptSupplierNameFrom')) ? $this->input->post('oetRptSupplierNameFrom') : "",
            'tPdtSupplierNameTo' => !empty($this->input->post('oetRptSupplierNameTo')) ? $this->input->post('oetRptSupplierNameTo') : "",
            'tPdtSupplierCodeTo' => !empty($this->input->post('oetRptSupplierCodeTo')) ? $this->input->post('oetRptSupplierCodeTo') : "",

             //กลุ่มผู้จำหน่าย
            'tPdtSgpCodeFrom' => !empty($this->input->post('oetRptSgpCodeFrom')) ? $this->input->post('oetRptSgpCodeFrom') : "",
            'tPdtSgpNameFrom' => !empty($this->input->post('oetRptSgpNameFrom')) ? $this->input->post('oetRptSgpNameFrom') : "",
            'tPdtSgpNameTo' => !empty($this->input->post('oetRptSgpNameTo')) ? $this->input->post('oetRptSgpNameTo') : "",
            'tPdtSgpCodeTo' => !empty($this->input->post('oetRptSgpCodeTo')) ? $this->input->post('oetRptSgpCodeTo') : "",

            //ประเภทผู้จำหน่าย
            'tPdtStyCodeFrom' => !empty($this->input->post('oetRptStyCodeFrom')) ? $this->input->post('oetRptStyCodeFrom') : "",
            'tPdtStyNameFrom' => !empty($this->input->post('oetRptStyNameFrom')) ? $this->input->post('oetRptStyNameFrom') : "",
            'tPdtStyNameTo' => !empty($this->input->post('oetRptStyNameTo')) ? $this->input->post('oetRptStyNameTo') : "",
            'tPdtStyCodeTo' => !empty($this->input->post('oetRptStyCodeTo')) ? $this->input->post('oetRptStyCodeTo') : "",

            // Filter Document Date (วันที่สร้างเอกสาร)
            'tDocDateFrom'  => (empty($this->input->post('oetRptDocDateFrom'))) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'    => (empty($this->input->post('oetRptDocDateTo'))) ? '' : $this->input->post('oetRptDocDateTo'),
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];

        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index()
    {
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {
            // Execute Stored Procedure
            $this->RptPerchaseOrder_model->FSnMExecStoreReport($this->aRptFilter);

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

            $aDataReport = $this->RptPerchaseOrder_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptPerchaseOrderHtml', $aDataViewRptParams);

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
    public function FSvCCallRptViewBeforePrintClickPage()
    {
        /** =========== Begin Init Variable ================================== */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** =========== End Init Variable ==================================== */

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'  => $this->nPerPage,
            'nPage'     => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->RptPerchaseOrder_model->FSaMGetDataReport($aDataWhereRpt);
        
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wSaleQuantationHtml', $aDataViewRptParams);

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

    // Functionality: Click Page Report (Report Viewer)
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase)
    {
        
    }

    
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


        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(NULL),
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
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    public function FSvCCallRptExportFile()
    {
        $tFileName = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); // stream data directly to the browser

        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();  //เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $oWriter->addRows($aMulltiRow);

        $oBorderTop = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->build();
        
        $oBorderBottom = (new BorderBuilder())
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleColumsTop = (new StyleBuilder())
            ->setBorder($oBorderTop)
            ->build();

        $oStyleColumsBottom = (new StyleBuilder())
            ->setBorder($oBorderBottom)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'tRowNumber')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSRCBch')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),  // เลขที่เอกสาร
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPreOrderBchTitle')),
            WriterEntityFactory::createCell(null), //สถานะ
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null), // ผู้จำหน่าย
            WriterEntityFactory::createCell(null), 
            WriterEntityFactory::createCell(null), 
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPreOrderTitle')), 
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColumsTop);
        $oWriter->addRow($singleRow);

        $aCells = [
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptXshDocNo')),  // เลขที่เอกสาร
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc')), //สถานะ
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDocPdtTwiSrcTypeAgn')), // ผู้จำหน่าย
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptXshDocNo')), // เลขที่เอกสาร
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDateDocument')), // วันที่เอกสาร
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPhStaApvdate')),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColumsBottom);
        $oWriter->addRow($singleRow);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage'  => 99999999999999,
            'nPage'     => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->RptPerchaseOrder_model->FSaMGetDataReport($aDataReportParams);

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            $i = 1;
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $tStaApvPq = '';
                if ($aValue['FTXphStaApvPq'] == 1) {
                    $tStaApvPq = language('report/report/report', 'tRptPhStaApv0');
                }elseif ($aValue['FTXphStaApvPq'] == 2) {
                    $tStaApvPq = language('report/report/report', 'tRptPhStaApv1');
                }elseif ($aValue['FTXphStaApvPq'] == 3) {
                    $tStaApvPq = language('report/report/report', 'tRptStaCrd3');
                }else{
                    $tStaApvPq = '';
                }

                $tStaApv = '';
                if ($aValue['FTXphStaApv'] == 1) {
                    $tStaApv = language('report/report/report', 'tRptPhStaApv0');
                }elseif ($aValue['FTXphStaApv'] == 2) {
                    $tStaApv = language('report/report/report', 'tRptPhStaApv1');
                }elseif ($aValue['FTXphStaApv'] == 3) {
                    $tStaApv = language('report/report/report', 'tRptStaCrd3');
                }else{
                    $tStaApv = '';
                }
                
                $values = [
                    WriterEntityFactory::createCell($i),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null), 
                    WriterEntityFactory::createCell($aValue['FTXphRefInt']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tStaApvPq),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTSplName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXphDocNo']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXphDocDate']))),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tStaApv),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDDateApv']))),
                    WriterEntityFactory::createCell(null),
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
                $i++;
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);

        $oWriter->close();
    }

    public function FSoCCallRptRenderFooterExcel()
    {
        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleFilter = (new StyleBuilder())
            ->setBorder($oBorder)
            ->build();
            
        $aCells = [
            WriterEntityFactory::createCell(null),
        ];
    
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        $aCells = [
            WriterEntityFactory::createCell(" "),
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
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        // สาขา
        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
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

        // //กลุ่มผู้จำหน่าย
        if (!empty($this->aRptFilter['tPdtSgpCodeFrom']) || !empty($this->aRptFilter['tPdtSgpCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptSplGrpForm'] . ' : ' . $this->aRptFilter['tPdtSgpNameFrom'] . '     ' . $this->aText['tRptSplGrpTo'] . ' : ' . $this->aRptFilter['tPdtSgpNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // //ประเภทผู้จำหน่าย
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
            }elseif ($this->aRptFilter['tStaApv'] == 2) {
                $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
            }elseif ($this->aRptFilter['tStaApv'] == 3) {
                $tStaOdr = language('report/report/report', 'tRptStaCrd3');
            }else{
                $tStaOdr = language('report/report/report', 'tRptAll');
            }
        if (isset($this->aRptFilter['tStaApv']) && !empty($this->aRptFilter['tStaApv'])) {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc') . ' : ' . $tStaOdr),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }else{
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tRptOpenJobStaDoc') . ' : ' . $tStaOdr),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

}
