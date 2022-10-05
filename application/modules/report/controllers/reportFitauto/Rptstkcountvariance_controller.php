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

class Rptstkcountvariance_controller extends MX_Controller{
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
        $this->load->model('report/reportFitauto/Rptstkcountvariance_model');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init(){
        // Array Text Label
        $this->aText    = [
            'tTitleReport'          => language('report/report/report', 'tRptStkcountvariance'),
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
            // Title Report
            'tRptStkcountvarianceNo'            => language('report/report/report', 'tRptStkcountvarianceNo'),
            'tRptStkcountvarianceDocNo'         => language('report/report/report', 'tRptStkcountvarianceDocNo'),
            'tRptStkcountvariancePdtCode'       => language('report/report/report', 'tRptStkcountvariancePdtCode'),
            'tRptStkcountvariancePdtName'       => language('report/report/report', 'tRptStkcountvariancePdtName'),
            'tRptStkcountvarianceAjdWahB4Adj'   => language('report/report/report', 'tRptStkcountvarianceAjdWahB4Adj'),
            'tRptStkcountvarianceAjdQtyAll'     => language('report/report/report', 'tRptStkcountvarianceAjdQtyAll'),
            'tRptStkcountvarianceAjdQtyAllDiff' => language('report/report/report', 'tRptStkcountvarianceAjdQtyAllDiff'),
            'tRptStkcountvarianceGrpWah'        => language('report/report/report', 'tRptStkcountvarianceGrpWah'),
            'tRptStkcountvarianceSubGrpWah'     => language('report/report/report', 'tRptStkcountvarianceSubGrpWah'),
            'tRptStkcountvarianceSumFooter'     => language('report/report/report', 'tRptStkcountvarianceSumFooter'),
        ];

        $this->tSysBchCode      = SYS_BCH_CODE;
        $this->tBchCodeLogin    = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage         = 100;
        $this->nOptDecimalShow  = FCNxHGetOptionDecimalShow();

        $tIP        = $this->input->ip_address();
        $tFullHost  = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

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
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $tFullHost,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            //Fillter ตัวแทนขาย
            'tAgnCodeSelect'    => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",
            //Filter BCH (สาขา)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,
            //Filter วันที่เอกสาร
            'tDocDateFrom'      => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'        => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
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
            $this->Rptstkcountvariance_model->FSnMExecStoreReport($this->aRptFilter);
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
        $aDataReport    = $this->Rptstkcountvariance_model->FSaMGetDataReport($aDataWhere);

        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $this->aRptFilter
        ];
        $tRptView   = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto','wRptStkCountVariance',$aDataViewRptParams);
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
        $aDataReport    = $this->Rptstkcountvariance_model->FSaMGetDataReport($aDataWhereRpt);
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow'   => $this->nOptDecimalShow,
            'aCompanyInfo'      => $this->aCompanyInfo,
            'aDataReport'       => $aDataReport,
            'aDataTextRef'      => $this->aText,
            'aDataFilter'       => $aDataFilter
        );
        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptStkCountVariance', $aDataViewRptParams);
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

    // ตรวจสอบก่อนพิมพ์ว่ามีจำนวนไหม
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase){
    }

    // Excel : ส่วนกลาง
    public function FSvCCallRptExportFile(){
        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        
        $oWriter    = WriterEntityFactory::createXLSXWriter();
        // stream data directly to the browser
        $oWriter->openToBrowser($tFileName);

        // เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();
        $oWriter->addRows($aMulltiRow);

        $oBorder        = (new BorderBuilder())->setBorderTop(Color::BLACK, Border::WIDTH_THIN)->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)->build();
        $oBorder        = (new BorderBuilder())->setBorderTop(Color::BLACK, Border::WIDTH_THIN)->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)->build();
        $oStyleColums   = (new StyleBuilder())->setBorder($oBorder)->setFontBold()->build();

        // Set Header Table Report
        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceDocNo')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvariancePdtCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvariancePdtName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPDTWahCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptPDTWahName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceAjdWahB4Adj')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceAjdQtyAll')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkBeforecount')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceAjdQtyAllDiff')),
            WriterEntityFactory::createCell(null),
        ];

        /** add a row at a time */
        $singleRow  = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams  = [
            'nPerPage'      => 999999999999,
            'nPage'         => 1,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter'    => $this->aRptFilter,
        ];
        $aDataReport    = $this->Rptstkcountvariance_model->FSaMGetDataReport($aDataReportParams);

        /** Create a style with the StyleBuilder */
        $oStyle = (new StyleBuilder())->setCellAlignment(CellAlignment::RIGHT)->build();

        // Check Data Report
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $nBeforeCount = $aValue["FCAjdWahB4Adj"] + ($aValue["FCAjdQtyAllDiff"]);
                
                $values = [
                    WriterEntityFactory::createCell($aValue['RowIDExcel']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTAjhDocNo']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTWahCode']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTWahName']),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdWahB4Adj']), $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdQtyAll']), $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format(floatval($nBeforeCount), $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdQtyAllDiff']), $this->nOptDecimalShow)),
                    WriterEntityFactory::createCell(null),
                ];
                $aRow   = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) {
                    $nBeforeCountFooter = $aValue["FCAjdWahB4Adj_Footer"] + ($aValue["FCAjdQtyAllDiff_Footer"]);
                    $values = [
                        WriterEntityFactory::createCell(language('report/report/report', 'tRptStkcountvarianceSubGrpWah')),
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
                        WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdWahB4Adj_Footer']), $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdQtyAll_Footer']), $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format(floatval($nBeforeCountFooter), $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format(floatval($aValue['FCAjdQtyAllDiff_Footer']), $this->nOptDecimalShow)),
                        WriterEntityFactory::createCell(null),
                    ];
                }
            }
        }

        //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); 
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();

        
    }

    // Excel : ส่วนหัว
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
        $oStyle = (new StyleBuilder())->setFontBold()->setFontSize(12)->build();
        $aCells = [
            WriterEntityFactory::createCell($tFTCmpName),
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
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }
    
    // Excel : ส่วนท้าย
    public function FSoCCallRptRenderFooterExcel(){
        $oStyleFilter   = (new StyleBuilder())->setFontBold()->build();
        $aCells         = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[]   = WriterEntityFactory::createRow($aCells);
        $aCells         = [
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport']),
        ];
        $aMulltiRow[]   = WriterEntityFactory::createRow($aCells, $oStyleFilter);
        // สาขา แบบเลือก
        if (!empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelectText = ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells         = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelectText),
            ];
            $aMulltiRow[]   = WriterEntityFactory::createRow($aCells);
        }
        return $aMulltiRow;
    }


}