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
class Rptpreviewcustomer_controller extends MX_Controller
{

    //ภาษา
    public $aText = [];

    //จำนวนต่อหน้าในรายงาน
    public $nPerPage = 100;

    //Page number
    public $nPage = 1;

    //จำนวนทศนิยม
    public $nOptDecimalShow = 2;

    //จำนวนข้อมูลใน Temp
    public $nRows = 0;

    //User Login on Bch
    public $tBchCodeLogin;

    //Report Code
    public $tRptCode;

    //Report Group
    public $tRptGroup;

    //System Language
    public $nLngID;

    //User Session ID
    public $tUserSessionID;

    //Report route
    public $tRptRoute;

    //Report Export Type
    public $tRptExportType;

    //Filter for Report
    public $aRptFilter = [];

    //Company Info
    public $aCompanyInfo = [];

    //User Login Session
    public $tUserLoginCode;

    public function __construct(){
        parent::__construct();
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportFitauto/Rptpreviewcustomer_model');

        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init(){
        $this->aText = [
            'tTitleReport'          => language('report/report/report', 'tRptPreviewCustomer'),
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
            'tRptCstFrom'           => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'             => language('report/report/report', 'tRptCstTo'),
            'tRptGender'            => language('report/report/report', 'tRptGender'),
            'tRptCreateOn'          => language('report/report/report', 'tRptCreateOn'),
            'tRptCreateOnFrom'      => language('report/report/report', 'tRptCreateOnFrom'),
            'tRptCreateOnTo'        => language('report/report/report', 'tRptCreateOnTo'),
            'tRptLevelCst'          => language('report/report/report', 'tRptLevelCst'),
            'tRptLevelCstFrom'      => language('report/report/report', 'tRptLevelCstFrom'),
            'tRptLevelCstTo'        => language('report/report/report', 'tRptLevelCstTo'),
        ];

        $this->tBchCodeLogin    = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage         = 100;
        $this->nOptDecimalShow  = FCNxHGetOptionDecimalShow();
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
            'tSessionID'        => $this->tUserSessionID,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",
            //Fillter ตัวแทนขาย
            'tAgnCodeSelect'    => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",
            //Filter ลูกค้า
            'tCstCodeFrom'      => !empty($this->input->post('oetRptCstCodeFrom')) ? $this->input->post('oetRptCstCodeFrom') : "",
            'tCstCodeNameFrom'  => !empty($this->input->post('oetRptCstNameFrom')) ? $this->input->post('oetRptCstNameFrom') : "",
            'tCstCodeTo'        => !empty($this->input->post('oetRptCstCodeTo')) ? $this->input->post('oetRptCstCodeTo') : "",
            'tCstCodeNameTo'    => !empty($this->input->post('oetRptCstNameTo')) ? $this->input->post('oetRptCstNameTo') : "", 
            //Filter เพศ
            'tCstGender'        => !empty($this->input->post('ocmGenderCustomer')) ? $this->input->post('ocmGenderCustomer') : "",
            //Filter วันที่ลงทะเบียน
            'tCstCreateOnFrom'  => !empty($this->input->post('oetRptCreateOnDateFrom')) ? $this->input->post('oetRptCreateOnDateFrom') : "",
            'tCstCreateOnTo'    => !empty($this->input->post('oetRptCreateOnDateTo')) ? $this->input->post('oetRptCreateOnDateTo') : "",
            //Filter เลเวลลูกค้า
            'tCstLevelFrom'     => !empty($this->input->post('oetRptCstLevelCodeFrom')) ? $this->input->post('oetRptCstLevelCodeFrom') : "",
            'tCstLevelNameFrom' => !empty($this->input->post('oetRptCstLevelNameFrom')) ? $this->input->post('oetRptCstLevelNameFrom') : "",
            'tCstLevelTo'       => !empty($this->input->post('oetRptCstLevelCodeTo')) ? $this->input->post('oetRptCstLevelCodeTo') : "",
            'tCstLevelNameTo'   => !empty($this->input->post('oetRptCstLevelNameTo')) ? $this->input->post('oetRptCstLevelNameTo') : "", 
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
            $this->Rptpreviewcustomer_model->FSnMExecStoreReport($this->aRptFilter);

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
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase){
        try {
            $aDataWhere  = array(
                'tUsrSessionID' => $this->tUserSessionID,
                'tUserCode'     => $this->tUserLoginCode,
                'tRptCode'      => $this->tRptCode,
                'nPage'         => 1, // เริ่มทำงานหน้าแรก
                'nPerPage'      => $this->nPerPage
            );

            $aDataReport = $this->Rptpreviewcustomer_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptPreviewCustomerHtml', $aDataViewRptParams);

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
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (หน้า 2 3 4 5)
    public function FSvCCallRptViewBeforePrintClickPage(){

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'  => $this->nPerPage,
            'nPage'     => $this->nPage,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->Rptpreviewcustomer_model->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptPreviewCustomerHtml', $aDataViewRptParams);

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

    //ตรวจสอบก่อนพิมพ์ว่ามีจำนวนไหม
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase){}

    //Excel : ส่วนกลาง
    public function FSvCCallRptExportFile(){
        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();
        $oWriter->openToBrowser($tFileName); 

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
            WriterEntityFactory::createCell(language('common/main/main','tAgency')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report','tRptCustomerGroup')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report','tRptCustomerCode')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report','tRptCustomerName')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('customer/customer/customer','tCSTBirthday')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report','tRptGender')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('report/report/report','tRptAge')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('customer/customer/customer','tCSTContactTel')),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('customer/customer/customer','tCSTContactEmail')),
            WriterEntityFactory::createCell(null)
        ];
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage'      => 999999999999,
            'nPage'         => $this->nPage,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter'    => $this->aRptFilter,
        ];
        $aDataReport = $this->Rptpreviewcustomer_model->FSaMGetDataReport($aDataReportParams);
    
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {

                switch ($aValue['FTCstSex']) {
                    case 1:
                        $tGender = language('report/report/report','tRptGender1');
                        break;
                    case 2:
                        $tGender = language('report/report/report','tRptGender2');
                        break;
                    default:
                        $tGender = "ไม่ระบุ";
                        break;
                }

                $tDateBirthday = empty($aValue['FDCstDob']) ? '' : date("d/m/Y", strtotime($aValue['FDCstDob']));
                $values = [
                    WriterEntityFactory::createCell(($aValue['FTAgnName'] == "" ? "-" : $aValue['FTAgnName'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTPplName'] == "" ? "-" : $aValue['FTPplName'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstCode'] == "" ? "-" : $aValue['FTCstCode'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstName'] == "" ? "-" : $aValue['FTCstName'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tDateBirthday),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($tGender),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FDCstAge'] == "" ? "-" : $aValue['FDCstAge'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstTel'] == "" ? "-" : $aValue['FTCstTel'])),
                    WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell(($aValue['FTCstEmail'] == "" ? "-" : $aValue['FTCstEmail'])),
                    WriterEntityFactory::createCell(null)
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();
    }

    //Excel : ส่วนหัว
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
            WriterEntityFactory::createCell($this->aText['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $this->aText['tTimePrint'] . ' ' . date('H:i:s')),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
    }

    //Excel : ส่วนท้าย
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

        // เพศ
        if($this->aRptFilter['tCstGender'] == ''){
            $tGender = language('report/report/report','tRptAll');
        }else if($this->aRptFilter['tCstGender'] == '1'){
            $tGender = language('report/report/report','tRptGender1');
        }else if($this->aRptFilter['tCstGender'] == '2'){
            $tGender = language('report/report/report','tRptGender2');
        }
        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptGender'] . ' : ' . $tGender),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);

        // ลูกค้า
        if (!empty($this->aRptFilter['tCstCodeFrom']) && !empty($this->aRptFilter['tCstCodeTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $this->aRptFilter['tCstCodeNameFrom'] . '     ' . $this->aText['tRptCstTo'] . ' : ' . $this->aRptFilter['tCstCodeNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // วันที่ลงทะเบียน
        if (!empty($this->aRptFilter['tCstCreateOnFrom']) && !empty($this->aRptFilter['tCstCreateOnTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCreateOnFrom'] . ' : ' . $this->aRptFilter['tCstCreateOnFrom'] . '     ' . $this->aText['tRptCreateOnTo'] . ' : ' . $this->aRptFilter['tCstCreateOnTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ระดับ
        if (!empty($this->aRptFilter['tCstLevelFrom']) && !empty($this->aRptFilter['tCstLevelTo'])) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptLevelCstFrom'] . ' ' . $this->aRptFilter['tCstLevelNameFrom'] . '     ' . $this->aText['tRptLevelCstTo'] . ' ' . $this->aRptFilter['tCstLevelNameTo']),
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

}
