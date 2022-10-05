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

class cRptLockerStatus extends MX_Controller {

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

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportlocker/mRptLockerStatus');
        $this->load->model('report/report/mReport');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init() {

        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptLockerStatusTitle'),
            'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tRptAddrFax' => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrTel' => language('report/report/report', 'tRptAddrTel'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
            'tRptAddrBranch' => language('report/report/report', 'tRptAddrBranch'),
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
            // Filter Heard Report
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            'tRptRcvFrom' => language('report/report/report', 'tRptRcvFrom'),
            'tRptRcvTo' => language('report/report/report', 'tRptRcvTo'),
            // Table Report
            'tRptLockerStatusBranch' => language('report/report/report', 'tRptLockerStatusBranch'),
            'tRptLockerStatusShop' => language('report/report/report', 'tRptLockerStatusShop'),
            'tRptLockerStatusName' => language('report/report/report', 'tRptLockerStatusName'),
            'tRptLockerStatusNumber' => language('report/report/report', 'tRptLockerStatusNumber'),
            'tRptLockerStatus' => language('report/report/report', 'tRptLockerStatus'),
            'tRptLockerStatusRefSale' => language('report/report/report', 'tRptLockerStatusRefSale'),
            'tRptLockerStatusRefDoc' => language('report/report/report', 'tRptLockerStatusRefDoc'),
            'tRptBarchName' => language('report/report/report', 'tRptBarchName'),
            'tRptDocDate' => language('report/report/report', 'tRptDocDate'),
            'tRptShopCode' => language('report/report/report', 'tRptShopCode'),
            'tRptShopName' => language('report/report/report', 'tRptShopName'),
            'tRptSale' => language('report/report/report', 'tRptSale'),
            'tRptCancelSale' => language('report/report/report', 'tRptCancelSale'),
            'tRptTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tRptTotalAllSale' => language('report/report/report', 'tRptTotalAllSale'),
            'tRptRcvNameEmpty' => language('report/report/report', 'tRptRcvNameEmpty'),
            'tRptTaxSalePosTaxId' => language('report/report/report', 'tRptTaxSalePosTaxId'),
            'tRptLockerStatusNoData' => language('report/report/report', 'tRptLockerStatusNoData'),
            'tRptLockerStatusLayName' => language('report/report/report', 'tRptLockerStatusLayName'),
            'tRPCBchFrom' => language('report/report/report', 'tRPCBchFrom'),
            'tRptAll' => language('report/report/report', 'tRptAll'),
            // Report Text Condtion Other Report
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),
        ];

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


        // Report Filter
        $this->aRptFilter = [
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'nLangID' => $this->nLngID,

            // สาขา
            'tBchCodeFrom' => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : '',
            'tBchNameFrom' => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : '',

            // เครื่องจุดขาย
            'tPosCodeFrom' => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : '',
            'tPosNameFrom' => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : '',

            // ร้านค้า
            'tShpCodeFrom' => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : '',
            'tShpNameFrom' => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : '',

            // จากประเภทชำระเงิน
            'tRcvCodeFrom' => !empty($this->input->post('oetRptRcvCodeFrom')) ? $this->input->post('oetRptRcvCodeFrom') : '',
            'tRcvNameFrom' => !empty($this->input->post('oetRptRcvNameFrom')) ? $this->input->post('oetRptRcvNameFrom') : '',
            'tRcvCodeTo' => !empty($this->input->post('oetRptRcvCodeTo')) ? $this->input->post('oetRptRcvCodeTo') : '',
            'tRcvNameTo' => !empty($this->input->post('oetRptRcvNameTo')) ? $this->input->post('oetRptRcvNameTo') : '',

            // วันที่เอกสาร(DocNo)
            'tDocDateFrom' => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : '',
            'tDocDateTo' => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : '',

            //
            'tLockerStatus' => !empty($this->input->post('ocmRptStaBooking')) ? $this->input->post('ocmRptStaBooking') : '',         
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            // Execute Stored Procedure
            $Result = $this->mRptLockerStatus->FSnMExecStoreReport($this->aRptFilter);

            $aDataSwitchCase = array(
                'ptRptRoute' => $this->tRptRoute,
                'ptRptCode' => $this->tRptCode,
                'ptRptTypeExport' => $this->tRptExportType,
                'paDataFilter' => $this->aRptFilter
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

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Wasin(Yoshi)
     * LastUpdate: 17/10/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase) {

        $aDataWhere = array(
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => 1, // เริ่มทำงานหน้าแรก
            'nRow' => $this->nPerPage,
        );

        // Get Data Report
        $aDataReport = $this->mRptLockerStatus->FSaMGetDataReport($aDataWhere, $this->aRptFilter); //Get Data

        // $tViewRenderKool = $this->FCNvCRenderKoolReportHtml($aDataReport, $this->aRptFilter); // Draw Table
        $aDataViewPdt = array(
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataFilter' => $paDataSwitchCase['paDataFilter'],
            'aDataReturn'=>$aDataReport
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptLockerStatus', 'wRptLockerStatusHtml', $aDataViewPdt);

        $aDataView = array(
            'aCompanyInfo' => $this->aCompanyInfo,
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => $aDataReport
        );

        $this->load->view('report/report/wReportViewer', $aDataView);
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 19/04/2019 Wasin(Yoshi)
     * LastUpdate: 17/10/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {

        /*===== Begin Init Variable ====================================================*/
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /*===== End Init Variable ======================================================*/

        $aDataWhere = array(
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => $this->nPage,
            'nRow' => $this->nPerPage,
        );
        // Get Data Report  
        $aDataReport = $this->mRptLockerStatus->FSaMGetDataReport($aDataWhere, $aDataFilter);

        if (!empty($aDataReport['rtCode']) && $aDataReport['rtCode'] == 1) {
            $tViewRenderKool = $this->FCNvCRenderKoolReportHtml($aDataReport, $aDataFilter);
        } else {
            $tViewRenderKool = "";
        }

        $aDataView = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewRenderKool,
            'aDataFilter' => $aDataFilter,
            'aDataReport' => $aDataReport
        );
        $this->load->view('report/report/wReportViewer', $aDataView);
    }

    /**
     * Functionality: Call Rpt Table Kool Report
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Wasin(Yoshi)
     * LastUpdate: 17/10/2019 Piya
     * Return: View Kool Report
     * ReturnType: View
     */
    public function FCNvCRenderKoolReportHtml($paDataReport, $paDataFilter) {

        // Ref File Kool Report
        require_once APPPATH . 'modules\report\datasources\rptLocPayment\rRptLocPayMent.php';

        // Set Parameter To Report
        $oRptLocPayMentHtml = new rRptLocPayMent(array(
            'nCurrentPage' => $paDataReport['rnCurrentPage'],
            'nAllPage' => $paDataReport['rnAllPage'],
            'aCompanyInfo' => $this->aCompanyInfo,
            'aFilterReport' => $paDataFilter,
            'aDataTextRef' => $this->aText,
            'aDataReturn' => $paDataReport,
        ));


        $oRptLocPayMentHtml->run();
        $tHtmlViewReport = $oRptLocPayMentHtml->render('wRptLockerStatusHtml', true);
        return $tHtmlViewReport;
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 16/08/2019 Witsarut(Bell)
     * LastUpdate: 17/10/2019 Piya
     * Return: object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp($paData) {

        try {
            $aDataCountData = [
                'tCompName' => $paData['paDataFilter']['tCompName'],
                'tRptCode' => $paData['paDataFilter']['tRptCode'],
                'tUserSession' => $paData['paDataFilter']['tUserSession'],
            ];

            $nDataCountPage = $this->mRptLockerStatus->FSaMCountDataReportAll($aDataCountData);

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



   
   // Functionality: Function Render Report Excel
    // Parameters:  Function Parameter
    // Creator: 06/08/2019 Wasin(Yoshi)
    // LastUpdate: 15/10/2019 Witsarut (Bell)
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptExportFile(){
        $tFileName = $this->aText['tTitleReport'].'_'.date('YmdHis').'.xlsx';
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusBranch')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusShop')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusNumber')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusLayName')),
            WriterEntityFactory::createCell(null),  
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatus')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusRefSale')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptLockerStatusRefDoc')),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => $this->nPage,
            'nRow' => $this->nPerPage,
        ];

        $aDataReport = $this->mRptLockerStatus->FSaMGetDataReport($aDataReportParams);

    

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())
            ->setCellAlignment(CellAlignment::RIGHT)
                ->build();
                if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $values = [
                            WriterEntityFactory::createCell($aValue['FTBchName']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTShpName']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTPosName']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FNLayNo']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTLayName']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTLayStaUse']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTLksRefSale']),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTLksRefCstDoc']),
                            WriterEntityFactory::createCell(null),
                        ];
                   
                        $aRow = WriterEntityFactory::createRow($values);
                        $oWriter->addRow($aRow);       
                    }
                }

                $oRow = [
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
           
                $aRow = WriterEntityFactory::createRow($oRow);
                $oWriter->addRow($aRow);    

                $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
                $oWriter->addRows($aMulltiRow);

                $oWriter->close();
    }

    /**
     * Functionality: Render Excel Report Header
     * Parameters:  Function Parameter
     * Creator: 30/07/2020 Nattakit
     * LastUpdate: 
     * Return: oject
     * ReturnType: oject
     */
    public function FSoCCallRptRenderHedaerExcel(){
        $oStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell($this->aCompanyInfo['FTCmpName']),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell($this->aText['tTitleReport']),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells,$oStyle);

        $tAddress = '';
        if(isset($this->aCompanyInfo) && !empty($this->aCompanyInfo)) {
            if ($this->aCompanyInfo['FTAddVersion'] == '1') { 
                $tAddress = $this->aCompanyInfo['FTAddV1No'].' '.$this->aCompanyInfo['FTAddV1Road'].' '.$this->aCompanyInfo['FTAddV1Soi'].' '.$this->aCompanyInfo['FTSudName'].' '.$this->aCompanyInfo['FTDstName'].' '.$this->aCompanyInfo['FTPvnName'].' '.$this->aCompanyInfo['FTAddV1PostCode'];
            }
            if ($this->aCompanyInfo['FTAddVersion'] == '2') { 
                $tAddress =  $this->aCompanyInfo['FTAddV2Desc1'].' '.$this->aCompanyInfo['FTAddV2Desc2'];
            }
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
            WriterEntityFactory::createCell($this->aText['tRptAddrTel'].' '.$this->aCompanyInfo['FTCmpTel']),
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
            WriterEntityFactory::createCell($this->aText['tRptAddrBranch'] .' '. $this->aCompanyInfo['FTBchName']),
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
            WriterEntityFactory::createCell($this->aText['tRptTaxSalePosTaxId'] .' '. $this->aCompanyInfo['FTAddTaxNo']),
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


        if((isset($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateFrom'])) && (isset($this->aRptFilter['tDocDateTo']) && !empty($this->aRptFilter['tDocDateTo']))){
            $aCells = [
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell($this->aText['tRptDateFrom'].' '.date('d/m/Y',strtotime($this->aRptFilter['tDocDateFrom'])).' '.$this->aText['tRptDateTo'].' '.date('d/m/Y',strtotime($this->aRptFilter['tDocDateTo']))),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
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
            WriterEntityFactory::createCell($this->aText['tRptDatePrint'].' '.date('d/m/Y').' '.$this->aText['tRptTimePrint'].' '.date('H:i:s')),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
            WriterEntityFactory::createCell(NULL),
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;

    }


       /**
     * Functionality: Render Excel Report Footer
     * Parameters:  Function Parameter
     * Creator: 30/07/2020 Witsarut (bell)
     * LastUpdate: 
     * Return: oject
     * ReturnType: oject
     */
    public function FSoCCallRptRenderFooterExcel(){
        $oStyleFilter = (new StyleBuilder())
        ->setFontBold()
        ->build();

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

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells,$oStyleFilter);

        if (isset($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeFrom'])) {
            $tBchSelect =  $this->aRptFilter['tBchNameFrom'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'].' : '.$tBchSelect),
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

         //กลุ่มธุรกิจ
         if (isset($this->aRptFilter['tMerCodeSelect']) && !empty($this->aRptFilter['tMerCodeSelect'])) {
            $tMerSelect =  ($this->aRptFilter['bMerStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tMerNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'].' : '.$tMerSelect),
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

        //ร้านค้า (Shop)
        if (isset($this->aRptFilter['tShpCodeSelect']) && !empty($this->aRptFilter['tShpCodeSelect'])) {   
            $tShpSelect =  ($this->aRptFilter['bShpStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tShpNameSelect'];
                $aCells = [
                    WriterEntityFactory::createCell($this->aText['tRptShopFrom'].' : '.$tShpSelect),
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

        if ((isset($this->aRptFilter['tPdtCodeFrom']) && !empty($this->aRptFilter['tPdtCodeFrom'])) && (isset($this->aRptFilter['tPdtCodeTo']) && !empty($this->aRptFilter['tPdtCodeTo']))) {   
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tPdtCodeFrom'].' : '.$this->aRptFilter['tPdtNameFrom'].' '.$this->aText['tPdtCodeTo'].' : '.$this->aRptFilter['tPdtNameTo']),
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

        return $aMulltiRow;
    }
}

