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

class RptPricePdtGrp_controller extends MX_Controller{
    // ภาษา
    public $aText = [];
    // จำนวนต่อหน้าในรายงาน
    public $nPerPage = 100;
    // Page number
    public $nPage = 1;
    // จำนวนทศนิยม
    public $nOptDecimalShow = 2;
    // จำนวนข้อมูลใน Temp
    public $nRows = 0;
    // Computer Name
    public $tCompName;
    // User Login on Bch
    public $tBchCodeLogin;
    // Report Code
    public $tRptCode;
    // Report Group
    public $tRptGroup;
    // System Language
    public $nLngID;
    // User Session ID
    public $tUserSessionID;
    // Report route
    public $tRptRoute;
    // Report Export Type
    public $tRptExportType;
    // Filter for Report
    public $aRptFilter = [];
    // Company Info
    public $aCompanyInfo = [];
    // User Login Session
    public $tUserLoginCode;
    // Sys Bch Code
    public $tSysBchCode;

    public function __construct(){
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/RptPricePdtGrp_model');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init(){
        // Array Text Label
        $this->aText    = [
            'tTitleReport'          => language('report/report/report', 'tRptPricePdtGrpTitle'),
            'tDatePrint'            => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'            => language('report/report/report', 'tRptAdjStkVDTimePrint'),
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
            'tRPCTaxNo'             => language('report/report/report', 'tRPCTaxNo'),
            'tRptFaxNo'             => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'               => language('report/report/report', 'tRptTel'),
            'tRptBranch'            => language('report/report/report', 'tRptBranch'),
            'tRptTaxSalePosTaxId'   => language('report/report/report', 'tRptTaxSalePosTaxId'),
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
            'tRptNoData'            => language('report/report/report', 'tRptNoData'),
            // Filter
            'tRptDateFrom'          => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'            => language('report/report/report', 'tRptDateTo'),
            'tRptBchFrom'           => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report', 'tRptBchTo'),
            'tPdtCodeFrom'          => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'            => language('report/report/report', 'tPdtCodeTo'),
            // Title Report
            'tRptPoByBchByPdtPdtCode'       => language('report/report/report', 'tRptPoByBchByPdtPdtCode'),
            'tRptPoByBchByPdtPdtName'       => language('report/report/report', 'tRptPoByBchByPdtPdtName'),
            'tRptPoByBchByPdtDocDate'       => language('report/report/report', 'tRptPoByBchByPdtDocDate'),
            'tRptPoByBchByPdtDocNo'         => language('report/report/report', 'tRptPoByBchByPdtDocNo'),
            'tRptPoByBchByPdtBarCode'       => language('report/report/report', 'tRptPoByBchByPdtBarCode'),
            'tRptPoByBchByPdtPunName'       => language('report/report/report', 'tRptPoByBchByPdtPunName'),
            'tRptPoByBchByPdtUnit'          => language('report/report/report', 'tRptPoByBchByPdtUnit'),
            'tRptPoByBchByPdtPdtGrpSub'     => language('report/report/report', 'tRptPoByBchByPdtPdtGrpSub'),
            'tRptPoByBchByPdtBchGrpSub'     => language('report/report/report', 'tRptPoByBchByPdtBchGrpSub'),
            'tRptPoByBchByPdtBchGrpFooter'  => language('report/report/report', 'tRptPoByBchByPdtBchGrpFooter'),
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
        $this->aRptFilter   = [
            'tUserSession'                      => $this->tUserSessionID,
            'tCompName'                         => $tFullHost,
            'tRptCode'                          => $this->tRptCode,
            'nLangID'                           => $this->nLngID,
            'tTypeSelect'                       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter ตัวแทนขาย
            'tAgnCodeSelect'                    => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Filter BCH (สาขา)
            'tBchCodeFrom'                      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'                      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'                        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'                        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'                    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'                    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'                  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // รหัสสินค้า
            'tRptPdtPdtCodeFrom'                => !empty($this->input->post('oetRptPdtCodeFrom')) ? $this->input->post('oetRptPdtCodeFrom') : "",
            'tRptPdtPdtNameFrom'                => !empty($this->input->post('oetRptPdtNameFrom')) ? $this->input->post('oetRptPdtNameFrom') : "",
            'tRptPdtPdtCodeTo'                  => !empty($this->input->post('oetRptPdtCodeTo')) ? $this->input->post('oetRptPdtCodeTo') : "",
            'tRptPdtPdtNameTo'                  => !empty($this->input->post('oetRptPdtNameTo')) ? $this->input->post('oetRptPdtNameTo') : "",

            // กลุ่มราคาสินค้า
            'tRptEffectivePriceGroupCodeFrom'   => !empty($this->input->post('oetRptEffectivePriceGroupCodeFrom')) ? $this->input->post('oetRptEffectivePriceGroupCodeFrom') : "",
            'tRptEffectivePriceGroupNameFrom'   => !empty($this->input->post('oetRptEffectivePriceGroupNameFrom')) ? $this->input->post('oetRptEffectivePriceGroupNameFrom') : "",
            'tRptEffectivePriceGroupCodeTo'     => !empty($this->input->post('oetRptEffectivePriceGroupCodeTo')) ? $this->input->post('oetRptEffectivePriceGroupCodeTo') : "",
            'tRptEffectivePriceGroupNameTo'     => !empty($this->input->post('oetRptEffectivePriceGroupNameTo')) ? $this->input->post('oetRptEffectivePriceGroupNameTo') : "",

            // วันที่มีผล
            'tRptEffectiveDateFrom'             => !empty($this->input->post('oetRptEffectiveDateFrom')) ? $this->input->post('oetRptEffectiveDateFrom') : "",
            'tRptEffectiveNameFrom'             => !empty($this->input->post('oetRptEffectiveNameFrom')) ? $this->input->post('oetRptEffectiveNameFrom') : "",
            'tRptEffectiveDateTo'               => !empty($this->input->post('oetRptEffectiveDateTo')) ? $this->input->post('oetRptEffectiveDateTo') : "",
            'tRptEffectiveNameTo'               => !empty($this->input->post('oetRptEffectiveNameTo')) ? $this->input->post('oetRptEffectiveNameTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo     = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index(){
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {
            // Execute Stored Procedure
            $this->RptPricePdtGrp_model->FSnMExecStoreReport($this->aRptFilter);
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

    // ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (หน้า 1)
    public function FSvCCallRptViewBeforePrint(){
        $aDataWhere = [
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter'    => $this->aRptFilter,
        ];
        $aDataReport    = $this->RptPricePdtGrp_model->FSaMGetDataReport($aDataWhere);

        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $this->aRptFilter
        ];
        $tRptView   = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto','wRptPricePdtGrpHtml',$aDataViewRptParams);
        // Data Viewer Center Report
        $aDataViewerParams = [
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
                'rtDesc'        => 'success'
            ]
        ];
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
    }
    
    // ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (หน้า 2 3 4 5)
    public function FSvCCallRptViewBeforePrintClickPage(){
        $aDataFilter    = json_decode($this->input->post('ohdRptDataFilter'), true);
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport    = $this->RptPricePdtGrp_model->FSaMGetDataReport($aDataWhereRpt);
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $aDataFilter
        );
        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptPricePdtGrpHtml', $aDataViewRptParams);
        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport'      => $this->aText['tTitleReport'],
            'tRptTypeExport'    => $this->tRptExportType,
            'tRptCode'          => $this->tRptCode,
            'tRptRoute'         => $this->tRptRoute,
            'tViewRenderKool'   => $tRptView,
            'aDataFilter'       => $aDataFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nRowIDEnd'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
    }


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
            WriterEntityFactory::createCell(NULL)
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
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(NULL),
                WriterEntityFactory::createCell(language('report/report/report', 'tRptEffectiveDateFrom') . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tRptEffectiveNameFrom'])) . ' ' . language('report/report/report', 'tRptEffectiveDateTo') . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tRptEffectiveNameTo']))),
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
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

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
            WriterEntityFactory::createCell(language('report/report/report', 'tRowNumber')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptBarchCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRPC15TBBchName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptChangePriceDocNo')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptAPBGDStart')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTimeEffectF')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptTimeEffectT')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptAPBGPriceGroup')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtCode')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPdtName')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptUnit')),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptInventoriesByBchPrice'))
        ];

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
        $aDataReport = $this->RptPricePdtGrp_model->FSaMGetDataReport($aDataReportParams);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($nKey+1),
                    WriterEntityFactory::createCell($aValue['FTBchCode']),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell($aValue['FTXphDocNo']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXphDStart']))),
                    WriterEntityFactory::createCell($aValue['FTXphTStart']),
                    WriterEntityFactory::createCell($aValue['FTXphTStop']),
                    WriterEntityFactory::createCell($aValue['FTPplName']),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell($aValue['FTPunName']),
                    WriterEntityFactory::createCell(number_format($aValue['FCXpdPriceRet'], 2))
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();
    }

    public function FSoCCallRptRenderFooterExcel(){
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
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport']),
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

        //รหัสสินค้า
        if (!empty($this->aRptFilter['tRptPdtPdtCodeFrom']) && !empty($this->aRptFilter['tRptPdtPdtCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tPdtCodeFrom') . ' : ' . $this->aRptFilter['tRptPdtPdtNameFrom'] . '     ' . language('report/report/report', 'tPdtCodeTo') . ' : ' . $this->aRptFilter['tRptPdtPdtNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //กลุ่มราคาสินค้า
        if (!empty($this->aRptFilter['tRptEffectivePriceGroupCodeFrom']) && !empty($this->aRptFilter['tRptEffectivePriceGroupCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell(language('report/report/report', 'tRptEffectivePriceGroupFrom') . ' : ' . $this->aRptFilter['tRptEffectivePriceGroupNameFrom'] . '     ' . language('report/report/report', 'tRptEffectivePriceGroupTo') . ' : ' . $this->aRptFilter['tRptEffectivePriceGroupNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

}
