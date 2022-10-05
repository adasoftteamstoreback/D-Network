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

class Rptincomefromcreditsystem_controller extends MX_Controller
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
        $this->load->model('report/reportFitauto/Rptincomefromcreditsystem_model');
        
        // Init Report
        $this->init();
        parent::__construct();
    }


    private function init()
    {
        $this->aText = [
            'tTitleReport'          => language('report/report/report', 'tRptIncomeFromCreditSystem'),
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
            'tRptConditionInReport' => language('report/report/report', 'tRptConditionInReport'),
            'tRptNoData'            => language('report/report/report', 'tRptNoData'),
            'tRptAll'               => language('report/report/report', 'tRptAll'),
            'tRptTaxSalePosFilterDocDateFrom'           => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo'             => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            'tRptSaleByPaymentDetailFilterPayTypeFrom'  => language('report/report/report', 'tRptSaleByPaymentDetailFilterPayTypeFrom'),
            'tRptSaleByPaymentDetailFilterPayTypeTo'    => language('report/report/report', 'tRptSaleByPaymentDetailFilterPayTypeTo'),
            'tRptAdjDateFrom'       => language('report/report/report', 'tRptAdjDateFrom'),
            'tRptAdjDateTo'         => language('report/report/report', 'tRptAdjDateTo'),
            'tRptRcvFromFrom'       => language('report/report/report', 'tRptSalByPaymentRcvFrom'),
            'tRptRcvFromTo'         => language('report/report/report', 'tRptSalByPaymentRcvFromTo'),
            'tRptBchFrom'           => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report', 'tRptBchTo'),
            'tRptCstFrom'           => language('report/report/report', 'tRptCstFrom'),
            'tRptCstTo'             => language('report/report/report', 'tRptCstTo'),
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
            'tSessionID'        => $this->tUserSessionID,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter Agency (ตัวแทนขาย)
            'tAgnCodeSelect'    => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Filter BCH (สาขา)
            'tBchCodeFrom'      => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'      => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'        => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'        => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter Document Date (วันที่สร้างเอกสาร)
            'tDocDateFrom'      => (empty($this->input->post('oetRptDocDateFrom'))) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'        => (empty($this->input->post('oetRptDocDateTo'))) ? '' : $this->input->post('oetRptDocDateTo'),

            //Filter ลูกค้า
            'tCstCodeSelect'    => !empty($this->input->post('oetRptCstCreditCodeSelect')) ? $this->input->post('oetRptCstCreditCodeSelect') : "",
            'tCstNameSelect'    => !empty($this->input->post('oetRptCstCreditNameSelect')) ? $this->input->post('oetRptCstCreditNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index()
    {
        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            // Execute Stored Procedure
            $this->Rptincomefromcreditsystem_model->FSnMExecStoreReport($this->aRptFilter);

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
    // Creator: 25/08/2021 Supawat
    // LastUpdate: -
    // Return: View Report Viewersd
    // ReturnType: View
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

            $aDataReport = $this->Rptincomefromcreditsystem_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptIncomeFromCreditSystemHtml', $aDataViewRptParams);

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

    // Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 25/08/2021 Supawat
    // LastUpdate:
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptViewBeforePrintClickPage()
    {

        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);

        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataWhereRpt = [
            'nPerPage'  => $this->nPerPage,
            'nPage'     => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->Rptincomefromcreditsystem_model->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportFitauto', 'wRptIncomeFromCreditSystemHtml', $aDataViewRptParams);

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
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase)
    {
        try {
            $aDataCountData = [
                'tCompName'     => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode'      => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tSessionID'    => $paDataSwitchCase['paDataFilter']['tSessionID'],
            ];

            $nDataCountPage = $this->Rptincomefromcreditsystem_model->FSnMCountDataReportAll($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent'     => 1,
                'tMessage'      => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    //รายงานส่วนหัว
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

        $oStyleFontBold = (new StyleBuilder())
            ->setFontBold()
            ->build();

        $oStyleColumsAndBold = (new StyleBuilder())
            ->setBorder($oBorder)
            ->setFontBold()
            ->build();

        $aCells = [
            WriterEntityFactory::createCell(language('report/report/report', 'ลำดับ')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อบริษัท/ชื่อลูกค้า')),
            WriterEntityFactory::createCell(language('report/report/report', 'รหัสสาขา')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อสาขา')),
            WriterEntityFactory::createCell(language('report/report/report', 'เลขที่บิล')),
            WriterEntityFactory::createCell(language('report/report/report', 'เลขที่ใบกำกับภาษี')),
            WriterEntityFactory::createCell(language('report/report/report', 'วันที่')),
            WriterEntityFactory::createCell(language('report/report/report', 'รหัสสาขา(ลูกค้า)')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อสาขา(ลูกค้า)')),
            WriterEntityFactory::createCell(language('report/report/report', 'รหัสสินค้า')),
            WriterEntityFactory::createCell(language('report/report/report', 'ชื่อสินค้า')),
            WriterEntityFactory::createCell(language('report/report/report', 'จำนวน')),
            WriterEntityFactory::createCell(language('report/report/report', 'ราคา/หน่วย')),
            WriterEntityFactory::createCell(language('report/report/report', 'ยอดขาย')),
            WriterEntityFactory::createCell(language('report/report/report', 'ส่วนลดรวม')),
            WriterEntityFactory::createCell(language('report/report/report', 'ยอดขายสุทธิ')),
            WriterEntityFactory::createCell(language('report/report/report', 'ต้นทุน/หน่วย')),
            WriterEntityFactory::createCell(language('report/report/report', 'ต้นทุน/หน่วยรวมภาษี')),
            WriterEntityFactory::createCell(language('report/report/report', 'ต้นทุนรวม')),
            WriterEntityFactory::createCell(language('report/report/report', 'กำไร')),
            WriterEntityFactory::createCell(language('report/report/report', '%กำไรเทียบทุนรวม'))
        ];

        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColumsAndBold);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage'      => 0,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        ];

        $aDataReport = $this->Rptincomefromcreditsystem_model->FSaMGetDataReport($aDataReportParams);
        
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {

            $tKeepCstCode = ''; 
            $tKeepDocNo   = ''; 
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {

             
                if($aValue['PARTTITIONBYDOC'] >= 1) { 
                    if($tKeepDocNo != $aValue['FTXshDocNo']){
                        $values = [
                            WriterEntityFactory::createCell($aValue['NUMBERDOC']),
                            WriterEntityFactory::createCell(($aValue['FTCstCompName'] == '') ? '-' : $aValue['FTCstCompName']),
                            WriterEntityFactory::createCell(($aValue['FTBchCode'] == '') ? '-' : $aValue['FTBchCode']),
                            WriterEntityFactory::createCell(($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']),
                            WriterEntityFactory::createCell(($aValue['FTXshDocNo'] == '') ? '-' : $aValue['FTXshDocNo']),
                            WriterEntityFactory::createCell(($aValue['FTXshDocVatFull'] == '') ? '-' : $aValue['FTXshDocVatFull']),
                            WriterEntityFactory::createCell(date('d/m/Y', strtotime($aValue['FDXshDocDate']))),
                            WriterEntityFactory::createCell(($aValue['FTBchCodeCst'] == '') ? '-' : $aValue['FTBchCodeCst']),
                            WriterEntityFactory::createCell(($aValue['FTBchNameCst'] == '') ? '-' : $aValue['FTBchNameCst']),
                            WriterEntityFactory::createCell($aValue['FTPdtCode']),
                            WriterEntityFactory::createCell($aValue['FTPdtName']),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdQty'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdSetPrice'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdAmt'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdDis'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdNet'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCost'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCostIncludeVat'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCostTotal'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshProfit'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshProfitPercent'], 2)),
                        ];
                        $aRow = WriterEntityFactory::createRow($values);
                        $oWriter->addRow($aRow);
                    }else{
                        $values = [
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell(null),
                            WriterEntityFactory::createCell($aValue['FTPdtCode']),
                            WriterEntityFactory::createCell($aValue['FTPdtName']),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdQty'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdSetPrice'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdAmt'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdDis'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXsdNet'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCost'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCostIncludeVat'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshCostTotal'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshProfit'], 2)),
                            WriterEntityFactory::createCell(number_format($aValue['FCXshProfitPercent'], 2)),
                        ];
                        $aRow = WriterEntityFactory::createRow($values);
                        $oWriter->addRow($aRow);
                    }
                }

                //รวมยอดตามบิล
                if($aValue['PARTTITIONBYDOC_COUNT'] == $aValue['PARTTITIONBYDOC']){ 

                    //%กำไรเทียบทุน
                    if($aValue['FCXshCostTotal_Doc_Footer'] == 0){
                        $nProFit_Doc_Footer = '100';
                    }else{
                        $nProFit_Doc_Footer = ($aValue['FCXshProfit_Doc_Footer'] * 100) / $aValue['FCXshCostTotal_Doc_Footer'];
                    }
                    $values = [
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
                        WriterEntityFactory::createCell("รวมบิล"),
                        WriterEntityFactory::createCell(number_format($aValue['FCXsdQty_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXsdSetPrice_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXsdAmt_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshDis_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshGrand_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshCostTotal_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshProfit_Doc_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($nProFit_Doc_Footer, 2))
                    ];
                    $aRow = WriterEntityFactory::createRow($values,$oStyleFontBold);
                    $oWriter->addRow($aRow);
                } 

                //รวมยอดตามลูกค้า
                if($aValue['PARTTITIONBYCSTCODE'] == $aValue['PARTTITIONBYCST_COUNT']){ 

                    //%กำไรเทียบทุน
                    if($aValue['FCXshCostTotal_CST_Footer'] == 0){
                        $nProFit_CST_Footer = '100';
                    }else{
                        $nProFit_CST_Footer = ($aValue['FCXshProfit_CST_Footer'] * 100) / $aValue['FCXshCostTotal_CST_Footer'];
                    }
                    
                    $values = [
                        WriterEntityFactory::createCell(($aValue['FTCstName']  == '') ? '-' : 'รวม'.$aValue['FTCstName']),
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
                        WriterEntityFactory::createCell(number_format($aValue['FCXsdQty_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($aValue['FCXsdAmt_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshDis_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshGrand_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshCostTotal_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($aValue['FCXshProfit_CST_Footer'], 2)),
                        WriterEntityFactory::createCell(number_format($nProFit_CST_Footer, 2)),
                    ];
                    $aRow = WriterEntityFactory::createRow($values,$oStyleColumsAndBold);
                    $oWriter->addRow($aRow);
                } 

                $tKeepCstCode     = $aValue['FTCstCode'];           
                $tKeepDocNo       = $aValue['FTXshDocNo'];  

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $FCXsdQty_Footer            = FCNnGetNumeric($aValue["FCXsdQty_Footer"]);
                    $FCXsdSetPrice_Footer       = FCNnGetNumeric($aValue["FCXsdSetPrice_Footer"]);
                    $FCXsdAmt_Footer            = FCNnGetNumeric($aValue["FCXsdAmt_Footer"]);
                    $FCXshDis_Footer            = FCNnGetNumeric($aValue["FCXshDis_Footer"]);
                    $FCXshGrand_Footer          = FCNnGetNumeric($aValue["FCXshGrand_Footer"]);
                    $FCXshCost_Footer           = FCNnGetNumeric($aValue["FCXshCost_Footer"]);   
                    $FCXshCostIncludeVat_Footer = FCNnGetNumeric($aValue["FCXshCostIncludeVat_Footer"]);
                    $FCXshCostTotal_Footer      = FCNnGetNumeric($aValue["FCXshCostTotal_Footer"]);
                    $FCXshProfit_Footer         = FCNnGetNumeric($aValue["FCXshProfit_Footer"]);    
                    $FCXshProfitPercent_Footer  = FCNnGetNumeric($aValue["FCXshProfitPercent_Footer"]);

                    //%กำไรเทียบทุน
                    if($aValue['FCXshCostTotal_Footer'] == 0){
                        $nProFit_Footer = '100';
                    }else{
                        $nProFit_Footer = ($aValue['FCXshProfit_Footer'] * 100) / $aValue['FCXshCostTotal_Footer'];
                    }

                    $values = [
                        WriterEntityFactory::createCell(language('report/report/report', 'tRptAdjStkVDTotalFooter')),
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
                        WriterEntityFactory::createCell(number_format($FCXsdQty_Footer, 2)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($FCXsdAmt_Footer, 2)),
                        WriterEntityFactory::createCell(number_format($FCXshDis_Footer, 2)),
                        WriterEntityFactory::createCell(number_format($FCXshGrand_Footer, 2)),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(number_format($FCXshCostTotal_Footer, 2)),
                        WriterEntityFactory::createCell(number_format($FCXshProfit_Footer, 2)),
                        WriterEntityFactory::createCell(number_format($nProFit_Footer, 2)),
                    ];
                    $aRow = WriterEntityFactory::createRow($values, $oStyleColumsAndBold);
                    $oWriter->addRow($aRow);
                }
            }
        }

        $aMulltiRow = $this->FSoCCallRptRenderFooterExcel(); //เรียกฟังชั่นสร้างส่วนท้ายรายงาน
        $oWriter->addRows($aMulltiRow);
        $oWriter->close();
    }

    //Excel ส่วนหัว
    public function FSoCCallRptRenderHedaerExcel(){
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo) > 0) {
            $tFTAddV1Village        = $this->aCompanyInfo['FTAddV1Village'];
            $tFTCmpName             = $this->aCompanyInfo['FTCmpName'];
            $tFTAddV1No             = $this->aCompanyInfo['FTAddV1No'];
            $tFTAddV1Road           = $this->aCompanyInfo['FTAddV1Road'];
            $tFTAddV1Soi            = $this->aCompanyInfo['FTAddV1Soi'];
            $tFTSudName             = $this->aCompanyInfo['FTSudName'];
            $tFTDstName             = $this->aCompanyInfo['FTDstName'];
            $tFTPvnName             = $this->aCompanyInfo['FTPvnName'];
            $tFTAddV1PostCode       = $this->aCompanyInfo['FTAddV1PostCode'];
            $tFTAddV2Desc1          = $this->aCompanyInfo['FTAddV2Desc1'];
            $tFTAddV2Desc2          = $this->aCompanyInfo['FTAddV2Desc2'];
            $tFTAddVersion          = $this->aCompanyInfo['FTAddVersion'];
            $tFTBchName             = $this->aCompanyInfo['FTBchName'];
            $tFTAddTaxNo            = $this->aCompanyInfo['FTAddTaxNo'];
            $tFTCmpTel              = $this->aCompanyInfo['FTAddTel'];
            $tRptFaxNo              = $this->aCompanyInfo['FTAddFax'];
        } else {
            $tFTCmpTel              = "";
            $tFTCmpName             = "";
            $tFTAddV1No             = "";
            $tFTAddV1Road           = "";
            $tFTAddV1Soi            = "";
            $tFTSudName             = "";
            $tFTDstName             = "";
            $tFTPvnName             = "";
            $tFTAddV1PostCode       = "";
            $tFTAddV2Desc1          = "1";
            $tFTAddV1Village        = "";
            $tFTAddV2Desc2          = "2";
            $tFTAddVersion          = "";
            $tFTBchName             = "";
            $tFTAddTaxNo            = "";
            $tRptFaxNo              = "";
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
            WriterEntityFactory::createCell($this->aText['tTitleReport'])
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
        ];

        $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        return $aMulltiRow;
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
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }   

        if (isset($this->aRptFilter['tCstCodeSelect']) && !empty($this->aRptFilter['tCstCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['tCstCodeSelect']) ? $this->aRptFilter['tCstNameSelect'] : $this->aText['tRptAll'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptCstFrom'] . ' : ' . $tBchSelect)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        } 

        return $aMulltiRow;
    }
}
