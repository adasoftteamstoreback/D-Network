<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

// include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
// include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
// include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;


class Rptcredebtor_controller extends MX_Controller
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
        parent::__construct();
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/Rptcredebtor_model');

        // Init Report
        $this->init();
        parent::__construct();
    }


    private function init()
    {
        $this->aText = [
            'tTitleReport'                      => language('report/report/report', 'tRptCreditdebtorTitle'),
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
            'tRptBchFrom'                       => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                         => language('report/report/report', 'tRptBchTo'),
            'tRptAdjStkNoData'                  => language('common/main/main', 'tCMNNotFoundData'),

            // Update Text 
            'tRptAjdQtyAllDiff'                 => language('report/report/report', 'tRptAjdQtyAllDiff'),
            'tRptAdjStkVDTaxNo'                 => language('report/report/report', 'tRptAdjStkVDTaxNo'),
            'tRptConditionInReport'             => language('report/report/report', 'tRptConditionInReport'),
            'tRptAll'                           => language('report/report/report', 'tRptAll'),

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
            'tRptEffectiveDateFrom'             => language('report/report/report', 'tRptEffectiveDateFrom'),
            'tRptEffectiveDateTo'               => language('report/report/report', 'tRptEffectiveDateTo'),
            'tRptCstFrom'                       => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'                         => language('report/report/report', 'tRptCstTo')
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
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $tFullHost,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,

            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            'tAgnCode'          => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : '',

            // สาขา(Branch)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // ลูกค้า
            'tCstCodeFrom'      => !empty($this->input->post('oetRptCstCodeFrom')) ? $this->input->post('oetRptCstCodeFrom') : "",
            'tCstNameFrom'      => !empty($this->input->post('oetRptCstNameFrom')) ? $this->input->post('oetRptCstNameFrom') : "",
            'tCstCodeTo'        => !empty($this->input->post('oetRptCstCodeTo')) ? $this->input->post('oetRptCstCodeTo') : "",
            'tCstNameTo'        => !empty($this->input->post('oetRptCstNameTo')) ? $this->input->post('oetRptCstNameTo') : "",
            'tCstCodeSelect'    => !empty($this->input->post('oetRptCstCodeSelect')) ? $this->input->post('oetRptCstCodeSelect') : "",
            'tCstNameSelect'    => !empty($this->input->post('oetRptCstNameSelect')) ? $this->input->post('oetRptCstNameSelect') : "",
            'bCstStaSelectAll'  => !empty($this->input->post('oetRptCstStaSelectAll')) && ($this->input->post('oetRptCstStaSelectAll') == 1) ? true : false,
           
            // วันที่เอกสาร
            'tDocDateFrom'      => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'        => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",

            // วันที่มีผล
            'tDueDateFrm'       => !empty($this->input->post('oetRptEffectiveDateFrom')) ? $this->input->post('oetRptEffectiveDateFrom') : "",
            'tDueDateTo'        => !empty($this->input->post('oetRptEffectiveDateTo')) ? $this->input->post('oetRptEffectiveDateTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];

        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {
            // Execute Stored Procedure
            $this->Rptcredebtor_model->FSnMExecStoreReport($this->aRptFilter);

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

            $aDataReport = $this->Rptcredebtor_model->FSaMGetDataReport($aDataWhere);
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptcredebtor', $aDataViewRptParams);

            $aDataViewerParams = [
                'tTitleReport'   => $this->aText['tTitleReport'],
                'tRptTypeExport' => $this->tRptExportType,
                'tRptCode'       => $this->tRptCode,
                'tRptRoute'      => $this->tRptRoute,
                'tViewRenderKool' => $tRptView,
                'aDataFilter' => $this->aRptFilter,
                'aDataReport' => [
                    'raItems'       => $aDataReport['aRptData'],
                    'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                    'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                    'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
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

        $aDataReport = $this->Rptcredebtor_model->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptcredebtor', $aDataViewRptParams);

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

    //ส่วนหัวของ excel
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    public function FSvCCallRptExportFile()
    {
        $tFileName = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); 
        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel(); 
        $oWriter->addRows($aMulltiRow);
        
        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleColums = (new StyleBuilder())
            ->setBorder($oBorder)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'รหัสลูกค้า')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อลูกค้า')),
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

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage'      => 999999999999,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->Rptcredebtor_model->FSaMGetDataReport($aDataReportParams);

        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                
                $values = [
                    WriterEntityFactory::createCell($aValue['FTCstCode']),
                    WriterEntityFactory::createCell($aValue['FTCstName']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXshDueDate']))),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(($aValue['FTXshRefInt'] == '') ? '-' : $aValue['FTXshRefInt']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXshDocDate']))),
                    WriterEntityFactory::createCell($aValue['FNCstCrTerm']),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue60U'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue31T60'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue0T30'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue1'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue2T7'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue8T15'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue16T30'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue31T60'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue61T90'], $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue90U'], $this->nOptDecimalShow)),
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
                        WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue60U_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue31T60_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshBFDue0T30_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue1_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue2T7_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue8T15_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue16T30_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue31T60_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue61T90_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FDXshPastDue90U_Footer'], $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshLeft_Footer'], $this->nOptDecimalShow))
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

    //ส่วนท้าย
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

        //สาขา
        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // วันที่มีผล
        if (!empty($this->aRptFilter['tDueDateTo']) && !empty($this->aRptFilter['tDueDateFrm'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptEffectiveDateFrom'] . ' : ' . date("d/m/Y", strtotime($this->aRptFilter['tDueDateTo'])) . '     ' . $this->aText['tRptEffectiveDateTo'] . ' : ' . date("d/m/Y", strtotime($this->aRptFilter['tDueDateFrm'])) ),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ลูกค้า
        if (!empty($this->aRptFilter['tCstCodeFrom']) && !empty($this->aRptFilter['tCstCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $this->aRptFilter['tCstCodeFrom'] . '     ' . $this->aText['tRptCstTo'] . ' : ' . $this->aRptFilter['tCstCodeTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }
        
        return $aMulltiRow;
    }

}
