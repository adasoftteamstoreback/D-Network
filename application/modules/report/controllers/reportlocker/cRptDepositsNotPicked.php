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

class cRptDepositsNotPicked extends MX_Controller {

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
     * Systems Branch Code
     * @var string 
    */
    public $tSysBchCode;
    
    public function __construct() {
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportlocker/mRptDepositsNotPicked');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init() {
        $this->aText = [
            // Address Language
            'tRptAddrBuilding' => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad' => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi' => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict' => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince' => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddV2Desc1' => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2' => language('report/report/report', 'tRptAddV2Desc2'),
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter' => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptFaxNo' => language('report/report/report', 'tRptFaxNo'),
            'tRptTel' => language('report/report/report', 'tRptTel'),
            // Title Report
            'tTitleReport'              => language('report/report/report', 'tRptDepositsNotPickedTitle'),
            'tRptAddrBranch'            => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddrTaxNo'             => language('report/report/report', 'tRptAddrTaxNo'),
            'tRptAddrTel'               => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'               => language('report/report/report', 'tRptAddrFax'),
            'tDatePrint'                => language('report/report/report', 'tRptDatePrint'),
            'tTimePrint'                => language('report/report/report', 'tRptTimePrint'),
            // Filter Text Label
            'tRptBchFrom'               => language('report/report/report', 'tRptBchFrom'),
            'tRPCBchFrom'               => language('report/report/report', 'tRPCBchFrom'),
            'tRPCBchTo'                 => language('report/report/report', 'tRPCBchTo'),
            'tRPCShopFrom'              => language('report/report/report', 'tRPCShopFrom'),
            'tRPCShopTo'                => language('report/report/report', 'tRPCShopTo'),
            'tRptPosFrom'               => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                 => language('report/report/report', 'tRptPosTo'),
            'tRptchannelForm'           => language('report/report/report', 'tRptchannelForm'),
            'tRptchannelTo'             => language('report/report/report', 'tRptchannelTo'),
            'tRptDateFrom'              => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'                => language('report/report/report', 'tRptDateTo'),
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),
            'tRptPosLockerFrom'         => language('report/report/report', 'tRptPosLockerFrom'),
            'tRptPosLockerTo'           => language('report/report/report', 'tRptPosLockerTo'),
            'tRptMerFrom'               => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'                 => language('report/report/report', 'tRptMerTo'),
            'tRptAll'                   => language('report/report/report', 'tRptAll'),

            // Table Label
            'tRptBarchName'             => language('report/report/report', 'tRptBarchName'),
            'tRptshop'                  => language('report/report/report', 'tRptshop'),
            'tRptXshDocNo'              => language('report/report/report', 'tRptXshDocNo'),
            'tRptDNPDepositDate'        => language('report/report/report', 'tRptDNPDepositDate'),
            'tRptDNPLockersID'          => language('report/report/report', 'tRptDNPLockersID'),
            'tRptTimePrint'             => language('report/report/report', 'tRptTimePrint'),
            'tRptDNPChannel'            => language('report/report/report', 'tRptDNPChannel'),
            'tRptSize'                  => language('report/report/report', 'tRptSize'),
            'tRptDNPDepositRates'       => language('report/report/report', 'tRptDNPDepositRates'),
            'tRptDNPDepositor'          => language('report/report/report', 'tRptDNPDepositor'),
            'tRptDNPTrackingNumber'     => language('report/report/report', 'tRptDNPTrackingNumber'),
            'tRptDNPRecipient'          => language('report/report/report', 'tRptDNPRecipient'),
            'tRptDNPPickUpStatus'       => language('report/report/report', 'tRptDNPPickUpStatus'),
            'tRptDNNotAccepted'         => language('report/report/report', 'tRptDNNotAccepted'),
            // No Data Report
            'tRptTaxSaleLockerNoData'   => language('common/main/main', 'tCMNNotFoundData'),
            // อัพเดทใหม่ 18/11/2019 Napat
            'tRptPosTypeName' => language('report/report/report', 'tRptPosTypeName'),
            'tRptPosType' => language('report/report/report', 'tRptPosType'),
            'tRptPosType1' => language('report/report/report', 'tRptPosType1'),
            'tRptPosType2' => language('report/report/report', 'tRptPosType2'),
            'tRptPdtCode' => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName' => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp' => language('report/report/report', 'tRptPdtGrp'),
            'tRptQty' => language('report/report/report', 'tRptQty'),
            'tRptUnit' => language('report/report/report', 'tRptUnit'),
            'tRptAveragePrice' => language('report/report/report', 'tRptAveragePrice'),
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
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom' => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo' => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom' => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo' => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom' => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo' => language('report/report/report', 'tRptAdjWahTo'),
            'tRptCstFrom' => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo' => language('report/report/report', 'tRptCstTo'),
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
            'tCompName'         => $this->tCompName,
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

            //กลุ่มธุรกิจ (MerChant)
            'tMerCodeFrom'      => !empty($this->input->post('oetRptMerCodeFrom')) ?  $this->input->post('oetRptMerCodeFrom') : "",
            'tMerNameFrom'      => !empty($this->input->post('oetRptMerNameFrom')) ?  $this->input->post('oetRptMerNameFrom') : "",
            'tMerCodeTo'        => !empty($this->input->post('oetRptMerCodeTo')) ?  $this->input->post('oetRptMerCodeTo') : "",
            'tMerNameTo'        => !empty($this->input->post('oetRptMerNameTo')) ?  $this->input->post('oetRptMerNameTo') : "",
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

            // เครื่องจุดขาย(Pos)
            'tPosCodeFrom'     => !empty($this->input->post('oetRptLockerCodeFrom')) ? $this->input->post('oetRptLockerCodeFrom') : "",
            'tPosNameFrom'     => !empty($this->input->post('oetRptLockerNameFrom')) ? $this->input->post('oetRptLockerNameFrom') : "",
            'tPosCodeTo'       => !empty($this->input->post('oetRptLockerCodeTo')) ? $this->input->post('oetRptLockerCodeTo') : "",
            'tPosNameTo'       => !empty($this->input->post('oetRptLockerNameTo')) ? $this->input->post('oetRptLockerNameTo') : "",
            'tPosCodeSelect'   => !empty($this->input->post('oetRptLockerCodeSelect')) ? $this->input->post('oetRptLockerCodeSelect') : "",
            'tPosNameSelect'   => !empty($this->input->post('oetRptLockerNameSelect')) ? $this->input->post('oetRptLockerNameSelect') : "",
            'bPosStaSelectAll' => !empty($this->input->post('oetRptLockerStaSelectAll')) && ($this->input->post('oetRptLockerStaSelectAll') == 1) ? true : false,

            // ช่อง(channel)
            'tLockerChanelFrom' => !empty($this->input->post('oetRptLockerChanelFrom')) ? $this->input->post('oetRptLockerChanelFrom') : "",
            'tLockerChanelTo'   => !empty($this->input->post('oetRptLockerChanelTo')) ? $this->input->post('oetRptLockerChanelTo') : "",
     
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'      => !empty($this->input->post('oetRptDocDateFrom')) ?  $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'        => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "", 
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {
            
            // Execute Stored Procedure
            $this->mRptDepositsNotPicked->FSnMExecStoreCReport($this->aRptFilter);

            $aDataMain = array(
                'ptRptRoute'      => $this->tRptRoute,
                'ptRptCode'       => $this->tRptCode,
                'ptRptTypeExport' => $this->tRptExportType,
                'paDataFilter'    => $this->aRptFilter
            );

            switch ($this->tRptExportType) {
                case 'html': 
                    $this->FSvCCallRptViewBeforePrint($aDataMain);
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
     * Creator: 02/12/2019 Saharat(Golf)
     * LastUpdate: -
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint($paDataMain) {
        try {

            /*===== Begin Get Data =====================================================*/
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'nPerPage'      => $this->nPerPage,
                'nPage'         => $this->nPage,
                'tCompName'     => $this->tCompName,
                'tRptCode'      => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
            ];

            /*=====  Get Data ==========================================================*/
            $aDataReport        = $this->mRptDepositsNotPicked->FSaMGetDataReport($aDataReportParams);
            /*===== End Get Data =======================================================*/
            
            /*===== Begin Render View ==================================================*/

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo'    => $this->aCompanyInfo,
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aDataReport'     => $aDataReport,
                'aDataTextRef'    => $this->aText,
                'aDataFilter'     => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportlocker/rptRptDepositsNotPicked', 'wRptDepositsNotPickedHtml', $aDataViewRptParams);
            
            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport'    => $this->aText['tTitleReport'],
                'tRptTypeExport'  => $this->tRptExportType,
                'tRptCode'        => $this->tRptCode,
                'tRptRoute'       => $this->tRptRoute,
                'tViewRenderKool' => $tRptView,
                'aDataFilter' => $this->aRptFilter,
                'aDataReport' => [
                    'raItems' => $aDataReport['aRptData'],
                    'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                    'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                    'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
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
     * Creator: 02/11/2019 Saharat(Golf)
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
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        ];

        $aDataReport        = $this->mRptDepositsNotPicked->FSaMGetDataReport($aDataReportParams);
        /*===== End Get Data ===========================================================*/
        
        /*===== Begin Render View ======================================================*/
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportlocker/rptRptDepositsNotPicked', 'wRptDepositsNotPickedHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport'    => $this->aText['tTitleReport'],
            'tRptTypeExport'  => $this->tRptExportType,
            'tRptCode'        => $this->tRptCode,
            'tRptRoute'       => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter'     => $aDataFilter,
            'aDataReport'     => array(
                'raItems'     => $aDataReport['aRptData'],
                'rnAllRow'    => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success'
            )
        );

        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /*===== End Render View ========================================================*/
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 02/12/2019 Saharat(GolF)
     * LastUpdate: -
     * Return: object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp($paDataMain) {

        try {
            $aDataCountData = [
                'tCompName'     => $paDataMain['paDataFilter']['tCompName'],
                'tRptCode'      => $paDataMain['paDataFilter']['tRptCode'],
                'tUserSession'  => $paDataMain['paDataFilter']['tUserSession'],
            ];

            $nDataCountPage = $this->mRptDepositsNotPicked->FSnMCountRowInTemp($aDataCountData);

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
     * Creator: 02/11/2019 Saharat(GolF)
     * LastUpdate: -
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile() {
        try {
            $tDateSendMQ    = date('Y-m-d');
            $tTimeSendMQ    = date('H:i:s');
            $tDateSubscribe = date('Ymd');
            $tTimeSubscribe = date('His');

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_'.$this->tSysBchCode.'_'.$this->tRptGroup.'_'.$this->tRptCode;

            $aDataSendMQ = [
                'tQueueName'    => $tRptQueueName,
                'aParams'       => [
                    'ptRptCode'         => $this->tRptCode,
                    'pnPerFile'         => 20000,
                    'ptUserCode'        => $this->tUserLoginCode,
                    'ptUserSessionID'   => $this->tUserSessionID,
                    'pnLngID'           => $this->nLngID,
                    'ptFilter'          => $this->aRptFilter,
                    'ptRptExpType'      => $this->tRptExportType,
                    'ptComName'         => $this->tCompName,
                    'ptDate'            => $tDateSendMQ,
                    'ptTime'            => $tTimeSendMQ,
                    'ptBchCode'         => (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'))
                ]
            ];
            FCNxReportCallRabbitMQ($aDataSendMQ);
            $aResponse = array(
                'nStaEvent' => 1,
                'tMessage'  => 'Success Send Rabbit MQ.',
                'aDataSubscribe'    => array(
                    'ptSysBchCode'      => $this->tSysBchCode,
                    'ptComName'         => $this->tCompName,
                    'ptRptCode'         => $this->tRptCode,
                    'ptUserCode'        => $this->tUserLoginCode,
                    'ptUserSessionID'   => $this->tUserSessionID,
                    'pdDateSubscribe'   => $tDateSubscribe,
                    'pdTimeSubscribe'   => $tTimeSubscribe,
                )
            );
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

     /**
     * Functionality: Render Excel Report
     * Parameters:  Function Parameter
     * Creator: 01/10/2020 Sooksanti
     * LastUpdate:
     * Return: file
     * ReturnType: file
     */
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptshop')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPLockersID')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptXshDocNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPDepositDate')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTimePrint')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPChannel')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptSize')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPDepositRates')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPDepositor')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPTrackingNumber')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptDNPRecipient')),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataWhereRpt = array(
            'nPerPage' => 999999999999,
            'nPage' => '1', // เริ่มทำงานหน้าแรก
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter' => $this->aRptFilter,
        );
        $aDataReport = $this->mRptDepositsNotPicked->FSaMGetDataReport($aDataWhereRpt);

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
            ->build();

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                if($aValue['FNXsdLayNo']==0 && empty($aValue['FTSizName']) ){
                    $tRateDeposit = '';
                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTShpName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPosName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(null),
                    // WriterEntityFactory::createCell(date('Y-m-d',strtotime($aValue['FDXshDatePick'])). ' ' .date('H:i:s',strtotime($aValue['FDXshDatePick']))),
                    // WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(substr($aValue['FDXshDocDate'],0,-13)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(substr($aValue['FDXshDocTime'],11,-4)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell('Virtual'),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell('-'),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshRefExt']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshFrmLoginName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshToLogin']),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
                }else{
                    $tRateDeposit = '';
                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTShpName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPosName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(null),
                    // WriterEntityFactory::createCell(date('Y-m-d',strtotime($aValue['FDXshDatePick'])). ' ' .date('H:i:s',strtotime($aValue['FDXshDatePick']))),
                    // WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(substr($aValue['FDXshDocDate'],0,-13)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(substr($aValue['FDXshDocTime'],11,-4)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FNXsdLayNo']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTSizName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshRefExt']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshFrmLoginName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTXshToLogin']),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow = WriterEntityFactory::createRow($values);
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

        // Fillter DocDate (วันที่สร้างเอกสาร)
        if ((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))) {
            $aCells = [
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
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
            $tBchSelectText = ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelectText),
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

        // Fillterฺ Pos (เครื่องจุดขาย)) แบบช่วง
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

        // if (isset($this->aRptFilter['tPosType'])) {
        //     $aCells = [
        //         WriterEntityFactory::createCell($this->aText['tRptPosTypeName'] . ' : ' . $this->aText['tRptPosType' . $this->aRptFilter['tPosType']]),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //         WriterEntityFactory::createCell(null),
        //     ];
        //     $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        // }

        // แคชเชียร์ แบบช่วง
        if (!empty($this->aRptFilter['tCashierCodeFrom']) && !empty($this->aRptFilter['tCashierCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCashierFrom'] . ' : ' . $this->aRptFilter['tCashierNameFrom'] . '     ' . $this->aText['tRptCashierTo'] . ' : ' . $this->aRptFilter['tCashierNameTo']),
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

         // Fillter ลูกค้า
         if (!empty($this->aRptFilter['tCstCodeFrom']) && !empty($this->aRptFilter['tCstCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $this->aRptFilter['tCstNameFrom'] . '     ' . $this->aText['tRptCstTo'] . ' : ' . $this->aRptFilter['tCstNameTo']),
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







































