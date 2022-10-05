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

class RptPdtCouponPromotion_controller extends MX_Controller
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
        $this->load->model('report/reportsale/RptPdtCouponPromotion_model');

        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init()
    {
        $this->aText = [
            'tTitleReport'          => language('report/report/report', 'tRptCouponProduct'),
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
            'tRptAdjStkVDTaxNo'     => language('report/report/report', 'tRptAdjStkVDTaxNo'),
            'tRptFaxNo'             => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'               => language('report/report/report', 'tRptTel'),
            'tRPCTaxNo'             => language('report/report/report', 'tRPCTaxNo'),
            // Table Label
            'tRptSaleProduct'       => language('report/report/report', 'tRptSaleProduct'),
            'tRptPromotion'         => language('report/report/report', 'tRptPromotion'),
            'tRpePmoBarCode'        => language('report/report/report', 'tRpePmoBarCode'),
            'tRptPmoProduct'        => language('report/report/report', 'tRptPmoProduct'),
            'tRptPmoSup'            => language('report/report/report', 'tRptPmoSup'),
            'tRptPmoModelPro'       => language('report/report/report', 'tRptPmoModelPro'),
            'tRptPmoScored'         => language('report/report/report', 'tRptPmoScored'),
            'tRptPmoQtyUnit'        => language('report/report/report', 'tRptPmoQtyUnit'),
            'tRptPmoPriceNormal'    => language('report/report/report', 'tRptPmoPriceNormal'),
            'tRptPmoDiscount'       => language('report/report/report', 'tRptPmoDiscount'),
            'tRptPricePromotion'    => language('report/report/report', 'ราคา'),
            'tRptPmoDocNo'          => language('report/report/report', 'tRptPmoDocNo'),
            'tRptPmoDate'           => language('report/report/report', 'tRptPmoDate'),
            'tRptPmoDisCountBath'   => language('report/report/report', 'tRptPmoDisCountBath'),
            'tRptPmoCoupon'         => language('report/report/report', 'tRptPmoCoupon'),
            'tRptPdtCode'           => language('report/report/report', 'tRptPdtCode'),
            
            // Fillter
            'tRptBchFrom'           => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report', 'tRptBchTo'),
            'tRptMerFrom'           => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'             => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'          => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'            => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'           => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'             => language('report/report/report', 'tRptPosTo'),
            'tRptDateFrom'          => language('report/report/report', 'tRptAdjDateFrom'),
            'tRptDateTo'            => language('report/report/report', 'tRptAdjDateTo'),
            'tRptAll'               => language('report/report/report', 'tRptAll'),
            'tRptPmoUnit'           => language('report/report/report', 'tRptPmoUnit'),
            'tRptSplFrom'           => language('report/report/report', 'tRptSplFrom'),
            'tRptSplTo'             => language('report/report/report', 'tRptSplTo'),

            // No Data Report
            'tRptAdjStkNoData'          => language('common/main/main', 'tCMNNotFoundData'),
            'tRptAdjStkVDTotalFooter'   => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            'tRptConditionInReport'     => language('report/report/report', 'tRptConditionInReport'),
            'tRptTaxSalePosTaxId'       => language('report/report/report', 'tRptTaxSalePosTaxId'),

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

        // Report Fillter.
        $this->aRptFilter  = [
            'tSessionID'                => $this->tUserSessionID,
            'tCompName'                 => $this->tCompName,
            'tRptCode'                  => $this->tRptCode,
            'nLangID'                   => $this->nLngID,
            'tTypeSelect'               => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            // ตัวแทนขาย
            'tAgnCodeFrom'              => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Filter BCH (สาขา)
            'tBchCodeFrom'              => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'              => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'                => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'                => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            'tBchCodeSelect'            => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'            => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'          => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false,

            // Filter Merchant (กลุ่มธุรกิจ)
            'tMerCodeFrom'              => (empty($this->input->post('oetRptMerCodeFrom'))) ? '' : $this->input->post('oetRptMerCodeFrom'),
            'tMerNameFrom'              => (empty($this->input->post('oetRptMerNameFrom'))) ? '' : $this->input->post('oetRptMerNameFrom'),
            'tMerCodeTo'                => (empty($this->input->post('oetRptMerCodeTo'))) ? '' : $this->input->post('oetRptMerCodeTo'),
            'tMerNameTo'                => (empty($this->input->post('oetRptMerNameTo'))) ? '' : $this->input->post('oetRptMerNameTo'),
            'tMerCodeSelect'            => !empty($this->input->post('oetRptMerCodeSelect')) ? $this->input->post('oetRptMerCodeSelect') : "",
            'tMerNameSelect'            => !empty($this->input->post('oetRptMerNameSelect')) ? $this->input->post('oetRptMerNameSelect') : "",
            'bMerStaSelectAll'          => !empty($this->input->post('oetRptMerStaSelectAll')) && ($this->input->post('oetRptMerStaSelectAll') == 1) ? true : false,

            // Filter Shop (ร้านค้า)
            'tShpCodeFrom'              => (empty($this->input->post('oetRptShpCodeFrom'))) ? '' : $this->input->post('oetRptShpCodeFrom'),
            'tShpNameFrom'              => (empty($this->input->post('oetRptShpNameFrom'))) ? '' : $this->input->post('oetRptShpNameFrom'),
            'tShpCodeTo'                => (empty($this->input->post('oetRptShpCodeTo'))) ? '' : $this->input->post('oetRptShpCodeTo'),
            'tShpNameTo'                => (empty($this->input->post('oetRptShpNameTo'))) ? '' : $this->input->post('oetRptShpNameTo'),
            'tShpCodeSelect'            => !empty($this->input->post('oetRptShpCodeSelect')) ? $this->input->post('oetRptShpCodeSelect') : "",
            'tShpNameSelect'            => !empty($this->input->post('oetRptShpNameSelect')) ? $this->input->post('oetRptShpNameSelect') : "",
            'bShpStaSelectAll'          => !empty($this->input->post('oetRptShpStaSelectAll')) && ($this->input->post('oetRptShpStaSelectAll') == 1) ? true : false,

            // Filter Pos (เครื่องจุดขาย)
            'tPosCodeFrom'              => (empty($this->input->post('oetRptPosCodeFrom'))) ? '' : $this->input->post('oetRptPosCodeFrom'),
            'tPosNameFrom'              => (empty($this->input->post('oetRptPosNameFrom'))) ? '' : $this->input->post('oetRptPosNameFrom'),
            'tPosCodeTo'                => (empty($this->input->post('oetRptPosCodeTo'))) ? '' : $this->input->post('oetRptPosCodeTo'),
            'tPosNameTo'                => (empty($this->input->post('oetRptPosNameTo'))) ? '' : $this->input->post('oetRptPosNameTo'),
            'tPosCodeSelect'            => !empty($this->input->post('oetRptPosCodeSelect')) ? $this->input->post('oetRptPosCodeSelect') : "",
            'tPosNameSelect'            => !empty($this->input->post('oetRptPosNameSelect')) ? $this->input->post('oetRptPosNameSelect') : "",
            'bPosStaSelectAll'          => !empty($this->input->post('oetRptPosStaSelectAll')) && ($this->input->post('oetRptPosStaSelectAll') == 1) ? true : false,

            //Filter TCNMSpl
            'tSupplierCodeFrom'         => (empty($this->input->post('oetRptSupplierCodeFrom'))) ? '' : $this->input->post('oetRptSupplierCodeFrom'),
            'tSupplierNameFrom'         => (empty($this->input->post('oetRptSupplierNameFrom'))) ? '' : $this->input->post('oetRptSupplierNameFrom'),
            'tSupplierCodeTo'           => (empty($this->input->post('oetRptSupplierCodeTo'))) ? '' : $this->input->post('oetRptSupplierCodeTo'),
            'tSupplierNameTo'           => (empty($this->input->post('oetRptSupplierNameTo'))) ? '' : $this->input->post('oetRptSupplierNameTo'),

            // Filter Document Date (วันที่สร้างเอกสาร)
            'tDocDateFrom'              => (empty($this->input->post('oetRptDocDateFrom'))) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo'                => (empty($this->input->post('oetRptDocDateTo'))) ? '' : $this->input->post('oetRptDocDateTo'),

            // Filter Document Promotion (เลขที่เอกสารโปรโมชั่น)
            'tDocPromotionCodeFrom'     => (empty($this->input->post('oetRptDocPromotionCodeFrom'))) ? '' : $this->input->post('oetRptDocPromotionCodeFrom'),
            'tDocPromotionNameFrom'     => (empty($this->input->post('oetRptDocPromotionNameFrom'))) ? '' : $this->input->post('oetRptDocPromotionNameFrom'),

            // Filter Coupon (คูปอง)
            'tCouponCodeFrom'           => (empty($this->input->post('oetRptCouponCodeFrom'))) ? '' : $this->input->post('oetRptCouponCodeFrom'),
            'tCouponNameFrom'           => (empty($this->input->post('oetRptCouponNameFrom'))) ? '' : $this->input->post('oetRptCouponNameFrom'),
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
            $this->RptPdtCouponPromotion_model->FSnMExecStoreReport($this->aRptFilter);

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
                    $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    break;
            }
        }
    }

    // ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    public function FSvCCallRptViewBeforePrint(){
        try {
            $aDataWhere  = array(
                'tUsrSessionID' => $this->tUserSessionID,
                'tCompName'     => $this->tCompName,
                'tUserCode'     => $this->tUserLoginCode,
                'tRptCode'      => $this->tRptCode,
                'nPage'         => 1, // เริ่มทำงานหน้าแรก
                'nPerPage'      => $this->nPerPage,
                'aDataFilter'   => $this->aRptFilter
            );

            $aDataReport    = $this->RptPdtCouponPromotion_model->FSaMGetDataReport($aDataWhere);

            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow'   => $this->nOptDecimalShow,
                'aCompanyInfo'      => $this->aCompanyInfo,
                'aDataReport'       => $aDataReport,
                'aDataTextRef'      => $this->aText,
                'aDataFilter'       => $this->aRptFilter
            ];

            // Load View Advance Table
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/rptPdtCouponPromotion', 'wRptPdtCouponPromotionHtml', $aDataViewRptParams);

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

    // Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
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
        $aDataReport    = $this->RptPdtCouponPromotion_model->FSaMGetDataReport($aDataWhereRpt);

        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/rptPdtCouponPromotion', 'wRptPdtCouponPromotionHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport'  => $this->aText['tTitleReport'],
            'tRptTypeExport'=> $this->tRptExportType,
            'tRptCode'      => $this->tRptCode,
            'tRptRoute'     => $this->tRptRoute,
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

    //Excel ส่วนกลาง
    public function FSvCCallRptRenderExcel(){

        $tFileName  = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); // stream data directly to the browser

        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();  //เรียกฟังชั่นสร้างส่วนหัวรายงาน
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
            WriterEntityFactory::createCell('สาขา'),
            WriterEntityFactory::createCell($this->aText['tRptPmoCoupon']),
            WriterEntityFactory::createCell($this->aText['tRptPmoDocNo']),
            WriterEntityFactory::createCell($this->aText['tRptPmoDate']),
            WriterEntityFactory::createCell($this->aText['tRptPdtCode']),
            WriterEntityFactory::createCell($this->aText['tRptPmoProduct']),
            WriterEntityFactory::createCell('หมวดหมู่สินค้าหลัก'),
            WriterEntityFactory::createCell('หมวดหมู่สินค้าย่อย'),
            WriterEntityFactory::createCell($this->aText['tRptPmoQtyUnit']),
            WriterEntityFactory::createCell($this->aText['tRptPmoUnit']),
            WriterEntityFactory::createCell($this->aText['tRptPmoPriceNormal']),
            WriterEntityFactory::createCell($this->aText['tRptPmoDiscount']),
            WriterEntityFactory::createCell($this->aText['tRptPricePromotion']),
            WriterEntityFactory::createCell('รหัสคูปอง'),
            WriterEntityFactory::createCell('ประเภทการชำระเงิน'),

   
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aDataReportParams = [
            'nPerPage'      => 0,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aDataFilter'   => $this->aRptFilter
        ];

        $aDataReport    = $this->RptPdtCouponPromotion_model->FSaMGetDataReport($aDataReportParams);
        
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {

                $tPmhDocNo      = $aValue['FTPmhDocNo'] . '  ' . $aValue['FTPmhName'];
                $tGetTypePmh    = $aValue['FTXpdGetType'];  //รูปแบบโปรโมชั่น 1:ลดบาท 2:ลด% 3:ปรับราคา 4:.ใช้กลุ่มราคา 5:แถม(Free) 6:ไม่กำหนด

                switch ($tGetTypePmh) {
                    case 1:
                        $tGetTypePmh  = 'ลดบาท';
                        break;
                    case 2:
                        $tGetTypePmh  = 'ลด%';
                        break;
                    case 3:
                        $tGetTypePmh  = 'ปรับราคา';
                        break;
                    case 4:
                        $tGetTypePmh  = 'ใช้กลุ่มราคา';
                        break;
                    case 5:
                        $tGetTypePmh  = 'แถม(Free)';
                        break;
                    case 6:
                        $tGetTypePmh  = 'ไม่กำหนด';
                        break;
                }

                $values = [
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    WriterEntityFactory::createCell($tPmhDocNo),
                    WriterEntityFactory::createCell($aValue['FTXshDocNo']),
                    WriterEntityFactory::createCell(date("d/m/Y", strtotime($aValue['FDXshDocDate']))),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell(($aValue['FTPdtCatName1'] == '') ? '-' : $aValue['FTPdtCatName1']),
                    WriterEntityFactory::createCell(($aValue['FTPdtCatName2'] == '') ? '-' : $aValue['FTPdtCatName2']),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdQty'])),
                    WriterEntityFactory::createCell($aValue['FTPunName']),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdNet'])),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXpdDis'])),
                    WriterEntityFactory::createCell(FCNnGetNumeric($aValue['FCXsdNetPmt'])),
                    WriterEntityFactory::createCell(($aValue['FTXddRefCode'] == '') ? '-' : $aValue['FTXddRefCode']),
                    WriterEntityFactory::createCell(($aValue['FTRcvName'] == '') ? '-' : $aValue['FTRcvName']),
       
                ];

                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);

                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { //SumFooter
                    $values = [
                        WriterEntityFactory::createCell($this->aText['tRptAdjStkVDTotalFooter']),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue["FCXsdQty_Footer"])),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue["FCXsdNet_Footer"])),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue["FCXpdDis_Footer"])),
                        WriterEntityFactory::createCell(FCNnGetNumeric($aValue["FCXsdNetPmt_Footer"])),
                        WriterEntityFactory::createCell(NULL),
                        WriterEntityFactory::createCell(NULL),
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

    //Excel ส่วนหัว
    public function FSoCCallRptRenderHedaerExcel(){
        if (isset($this->aCompanyInfo) && count($this->aCompanyInfo) > 0) {
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
        } else {
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
                WriterEntityFactory::createCell($this->aText['tRptDateFrom'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateFrom'])) . ' ' . $this->aText['tRptDateTo'] . ' ' . date('d/m/Y', strtotime($this->aRptFilter['tDocDateTo']))),
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

    //Excel ส่วนท้าย
    public function FSoCCallRptRenderFooterExcel(){
        $oStyleFilter = (new StyleBuilder())
            ->setFontBold()
            ->build();

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tRptConditionInReport'])
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyleFilter);

        if (isset($this->aRptFilter['tBchCodeSelect']) && !empty($this->aRptFilter['tBchCodeSelect'])) {
            $tBchSelect =  ($this->aRptFilter['bBchStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tBchNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptBchFrom'] . ' : ' . $tBchSelect)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // เครื่องจุดขาย (Pos) แบบเลือก
        if (!empty($this->aRptFilter['tPosCodeSelect'])) {
            $tPosSelectText = ($this->aRptFilter['bPosStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tPosNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPosFrom'] . ' : ' . $tPosSelectText)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //กลุ่มธุรกิจ
        if (isset($this->aRptFilter['tMerCodeSelect']) && !empty($this->aRptFilter['tMerCodeSelect'])) {
            $tMerSelect =  ($this->aRptFilter['bMerStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tMerNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptMerFrom'] . ' : ' . $tMerSelect)
            ];

            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        //ร้านค้า (Shop)
        if (isset($this->aRptFilter['tShpCodeSelect']) && !empty($this->aRptFilter['tShpCodeSelect'])) {
            $tShpSelect =  ($this->aRptFilter['bShpStaSelectAll']) ? $this->aText['tRptAll'] : $this->aRptFilter['tShpNameSelect'];
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptAdjShopFrom'] . ' : ' . $tShpSelect)
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // ผู้จำหน่าย
        if ((isset($this->aRptFilter['tSupplierCodeFrom']) && !empty($this->aRptFilter['tSupplierCodeFrom'])) && (isset($this->aRptFilter['tSupplierCodeTo']) && !empty($this->aRptFilter['tSupplierCodeTo']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptSplFrom'] . ' : ' . $this->aRptFilter['tSupplierNameFrom'] . ' ' . $this->aText['tRptSplTo'] . ' : ' . $this->aRptFilter['tSupplierNameTo'])
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // คูปอง
        if ((isset($this->aRptFilter['tCouponCodeFrom']) && !empty($this->aRptFilter['tCouponCodeFrom']))) {
            $aCells = [
                WriterEntityFactory::createCell($this->aText['tRptPmoCoupon'] . ' : ' . $this->aRptFilter['tCouponCodeFrom'])
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }
}
