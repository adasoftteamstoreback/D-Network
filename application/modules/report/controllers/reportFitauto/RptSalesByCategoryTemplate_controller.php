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

class RptSalesByCategoryTemplate_controller extends MX_Controller{

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
        parent::__construct();
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/RptSalesByCategoryTemplate_model');
        $this->init();
        parent::__construct();
    }

    private function init(){
        $this->aText = [
            'tTitleReport'                      => 'รายยอดขายตามหมวดสินค้า',
            'tDatePrint'                        => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'                        => language('report/report/report', 'tRptAdjStkVDTimePrint'),
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
            'tRptPosFrom'                       => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                         => language('report/report/report', 'tRptPosTo')
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
        $this->aRptFilter = [
            'tSessionID'            => $this->tUserSessionID,
            'tCompName'             => $this->tCompName,
            'tRptCode'              => $this->tRptCode,
            'nLangID'               => $this->nLngID,

            // Filter ตัวแทนขาย
            'tAgnCodeSelect'        => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            // Filter สาขา
            'tBchCodeSelect'        => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'        => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'      => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter วันที่เอกสาร (DocNo)
            'tDocDateFrom'          => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'            => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",

            // Filter หมวดหมู่
            'tCate1From'            => !empty($this->input->post('oetRptCate1CodeFrom')) ? $this->input->post('oetRptCate1CodeFrom') : "",
            'tCate1FromName'        => !empty($this->input->post('oetRptCate1NameFrom')) ? $this->input->post('oetRptCate1NameFrom') : "",
            'tCate2From'            => !empty($this->input->post('oetRptCate2CodeFrom')) ? $this->input->post('oetRptCate2CodeFrom') : "",
            'tCate2FromName'        => !empty($this->input->post('oetRptCate2NameFrom')) ? $this->input->post('oetRptCate2NameFrom') : "",
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
            $this->RptSalesByCategoryTemplate_model->FSnMExecStoreReport($this->aRptFilter);
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSvCCallRptExportFile();
                    break;
            }
        }
    }

    //ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrint(){
        try {
            $aDataWhere  = array(
                'tUsrSessionID' => $this->tUserSessionID,
                'tCompName'     => $this->tCompName,
                'tUserCode'     => $this->tUserLoginCode,
                'tRptCode'      => $this->tRptCode,
                'nPage'         => 1, // เริ่มทำงานหน้าแรก
                'nPerPage'      => $this->nPerPage
            );
            $aDataReport = $this->RptSalesByCategoryTemplate_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptSalesByCategoryTemplateHtml', $aDataViewRptParams);

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
                    'rtDesc'        => 'success',
                ]
            ];

            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrintClickPage(){
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->RptSalesByCategoryTemplate_model->FSaMGetDataReport($aDataWhereRpt);
        
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

    //Excel ส่วนหัว    
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
                WriterEntityFactory::createCell($this->aText['tRptAdjDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptAdjDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        $aCells = [
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
            WriterEntityFactory::createCell(language('report/report/report', 'tRowNumber')),
            WriterEntityFactory::createCell('สาขา'),
            WriterEntityFactory::createCell('Lube'),
            WriterEntityFactory::createCell('Tire'),
            WriterEntityFactory::createCell('Service'),
            WriterEntityFactory::createCell('Spare')
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

        $aDataReport = $this->RptSalesByCategoryTemplate_model->FSaMGetDataReport($aDataReportParams);

        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            $i = 1;
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                
                $values = [
                    WriterEntityFactory::createCell($i),
                    WriterEntityFactory::createCell(($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']),
                    WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupLube'], 2)),
                    WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupTire'], 2)),
                    WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupService'], 2)),
                    WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupOther'], 2)),
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
                $i++;

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $values = [
                        WriterEntityFactory::createCell('รวมทั้งสิ้น'),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupLube_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupTire_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupService_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FNPdtGroupOther_Footer'], 2))
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

    //Excel ส่วนท้าย
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
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        // สาขา
        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

}
