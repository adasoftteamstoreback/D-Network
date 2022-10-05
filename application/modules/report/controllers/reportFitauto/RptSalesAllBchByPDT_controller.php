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

class RptSalesAllBchByPDT_controller extends MX_Controller
{
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
        $this->load->model('report/reportFitauto/mRptSalesAllBchByPDT_model');
        $this->init();
        parent::__construct();
    }

    private function init(){
        // Array Text Label
        $this->aText    = [
            'tTitleReport'                  => language('report/report/report', 'รายงานยอดขายรวมทุกสาขา'),
            'tDatePrint'                    => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'                    => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            'tRptAddrBuilding'              => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'                  => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'                   => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict'           => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'              => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'              => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'                   => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'                   => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'                => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'                => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'                => language('report/report/report', 'tRptAddV2Desc2'),
            'tRPCTaxNo'                     => language('report/report/report', 'tRPCTaxNo'),
            'tRptFaxNo'                     => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'                       => language('report/report/report', 'tRptTel'),
            'tRptBranch'                    => language('report/report/report', 'tRptBranch'),
            'tRptTaxSalePosTaxId'           => language('report/report/report', 'tRptTaxSalePosTaxId'),
            'tRptConditionInReport'         => language('report/report/report', 'tRptConditionInReport'),
            'tRptNoData'                    => language('report/report/report', 'tRptNoData'),
            'tRptDateFrom'                  => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'                    => language('report/report/report', 'tRptDateTo'),
            'tRptBchFrom'                   => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'                     => language('report/report/report', 'tRptBchTo'),
            'tPdtCodeFrom'                  => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'                    => language('report/report/report', 'tPdtCodeTo'),
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
            'tUserSession'      => $this->tUserSessionID,
            'tCompName'         => $tFullHost,
            'tRptCode'          => $this->tRptCode,
            'nLangID'           => $this->nLngID,
            'tTypeSelect'       => !empty($this->input->post('ohdTypeDataCondition')) ? $this->input->post('ohdTypeDataCondition') : "",

            //Fillter ตัวแทนขาย
            'tAgnCodeSelect'    => !empty($this->input->post('oetSpcAgncyCode')) ? $this->input->post('oetSpcAgncyCode') : "",

            //Filter BCH (สาขา)
            'tBchCodeSelect'    => !empty($this->input->post('oetRptBchCodeSelect')) ? $this->input->post('oetRptBchCodeSelect') : "",
            'tBchNameSelect'    => !empty($this->input->post('oetRptBchNameSelect')) ? $this->input->post('oetRptBchNameSelect') : "",
            'bBchStaSelectAll'  => !empty($this->input->post('oetRptBchStaSelectAll')) && ($this->input->post('oetRptBchStaSelectAll') == 1) ? true : false, 

            //ปี
            'tYear'             => !empty($this->input->post('oetRptYear')) ? $this->input->post('oetRptYear') : '',

            // เดือน
            'tMonth'            => !empty($this->input->post('ocmRptMonth')) ? $this->input->post('ocmRptMonth') : "",
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
            
            switch ($this->tRptExportType) {
                case 'html':
                    //ไม่เอา preview
                    break;
                case 'excel':
                    $this->FSvCCallRptExportFile();
                    break;
            }
        }
    }

    // Excel : ส่วนกลาง
    public function FSvCCallRptExportFile(){
        $aDataReportParams  = [
            'nPerPage'      => 0,
            'nPage'         => 1,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
            'aRptFilter'    => $this->aRptFilter
        ];
        $aDataReport        = $this->mRptSalesAllBchByPDT_model->FSaMGetDataReport($aDataReportParams);
        $tFileName          = $this->aText['tTitleReport'] . '_' . date('YmdHis') . '.xlsx';
        $oWriter            = WriterEntityFactory::createXLSXWriter();
        $oWriter->openToBrowser($tFileName);

        // เรียกฟังชั่นสร้างส่วนหัวรายงาน
        $aMulltiRow = $this->FSoCCallRptRenderHedaerExcel();
        $oWriter->addRows($aMulltiRow);

        $oBorder            = (new BorderBuilder())->setBorderTop(Color::BLACK, Border::WIDTH_THIN)->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)->build();
        $oStyleColumsBCH    = (new StyleBuilder())
                                ->setBorder($oBorder)
                                ->setFontBold()
                                ->setShouldWrapText()
                                ->setCellAlignment(CellAlignment::CENTER)
                                ->build();

        $oStyleColums       = (new StyleBuilder())
                                ->setBorder($oBorder)
                                ->setFontBold()
                                ->build();

        //ตัวกรองสาขา
        $aExplodeFilterBCH        = '';
        if($this->aRptFilter['tBchCodeSelect'] != '' || $this->aRptFilter['tBchCodeSelect'] != null){
            $aExplodeFilterBCH    = explode(",",$this->aRptFilter['tBchCodeSelect']);
        }
        
        //สาขาทั้งหมด
        $aDataReportBCHAll  = $this->mRptSalesAllBchByPDT_model->FSaMGetDataReportBCHAll($aDataReportParams);
        $values             = [];
        if (isset($aDataReportBCHAll['aRptDataBCHAll']) && !empty($aDataReportBCHAll['aRptDataBCHAll'])) {
            $values = [
                WriterEntityFactory::createCell('รหัสหมวดหมู่สินค้าหลัก',$oStyleColums),
                WriterEntityFactory::createCell('ชื่อหมวดหมู่สินค้าหลัก',$oStyleColums),
                WriterEntityFactory::createCell('รหัสหมวดหมู่สินค้าย่อย',$oStyleColums),
                WriterEntityFactory::createCell('ชื่อหมวดหมู่สินค้าย่อย',$oStyleColums),
            ];
            foreach ($aDataReportBCHAll['aRptDataBCHAll'] as $nKey => $aValue) {
                if (!empty($this->aRptFilter['tBchCodeSelect'])) {
                    if (in_array($aValue['FTBchCode'], $aExplodeFilterBCH)){
                        array_push($values,WriterEntityFactory::createCell($aValue['FTBchName'],$oStyleColumsBCH));
                    }
                }else{
                    array_push($values,WriterEntityFactory::createCell($aValue['FTBchName'],$oStyleColumsBCH));
                } 
            }

            $aRow   = WriterEntityFactory::createRow($values);
            $oWriter->addRow($aRow);
        }

        // Check Data Report
        if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
            $aSUMFooter = [];
            foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell(($aValue['FTPdtCat1'] == '') ? '-' : $aValue['FTPdtCat1']),
                    WriterEntityFactory::createCell(($aValue['FTCatName1'] == '') ? '-' : $aValue['FTCatName1']),
                    WriterEntityFactory::createCell(($aValue['FTPdtCat2'] == '') ? '-' : $aValue['FTPdtCat2']),
                    WriterEntityFactory::createCell(($aValue['FTCatName2'] == '') ? '-' : $aValue['FTCatName2']),
                ];
                
                //Loop สาขา
                foreach ($aDataReportBCHAll['aRptDataBCHAll'] as $nKeys => $aValueBCH) {
                    if (!empty($this->aRptFilter['tBchCodeSelect'])) {
                        if (in_array($aValueBCH['FTBchCode'], $aExplodeFilterBCH)){
                            $nValueShow =  $this->numberPrecision($aValue[$aValueBCH['FTBchCode']],3); 
                            array_push($values,WriterEntityFactory::createCell(($aValue[$aValueBCH['FTBchCode']]) == '' ? 0 : (float)$nValueShow));

                            //ยอดรวมท้ายบิลแต่ละสาขา
                            if((array_search($aValueBCH['FTBchName'],array_column($aSUMFooter, 'tBCHName'))) !== false){
                                $tFindArray = array_search($aValueBCH['FTBchName'],array_column($aSUMFooter, 'tBCHName'));
                                $nNewValue  = ($aValue[$aValueBCH['FTBchCode']]) == '' ? '0' : $aValue[$aValueBCH['FTBchCode']];
                                $aSUMFooter[$tFindArray]['nSumValue'] =  (float)$aSUMFooter[$tFindArray]['nSumValue'] + (float)$nNewValue;
                            }else{
                                $aSUMFooterByBCH = array(
                                    'tBCHName'      => $aValueBCH['FTBchName'],
                                    'nSumValue'     => ($aValue[$aValueBCH['FTBchCode']]) == '' ? 0 : $aValue[$aValueBCH['FTBchCode']]
                                );
                                array_push($aSUMFooter,$aSUMFooterByBCH); 
                            }
                        }
                    }else{
                        if(isset($aValue[$aValueBCH['FTBchCode']])){
                            $nValueShow =  $this->numberPrecision($aValue[$aValueBCH['FTBchCode']],3); 
                        }else{
                            $nValueShow = 0;
                        }
                        array_push($values,WriterEntityFactory::createCell(($nValueShow) == '' ? 0 : (float)$nValueShow));
                        
                        //ยอดรวมท้ายบิลแต่ละสาขา
                        if((array_search($aValueBCH['FTBchName'],array_column($aSUMFooter, 'tBCHName'))) !== false){
                            $tFindArray = array_search($aValueBCH['FTBchName'],array_column($aSUMFooter, 'tBCHName'));
                            $nNewValue  = ($nValueShow) == '' ? '0' : $nValueShow;
                            $aSUMFooter[$tFindArray]['nSumValue'] =  (float)$aSUMFooter[$tFindArray]['nSumValue'] + (float)$nNewValue;
                        }else{
                            $aSUMFooterByBCH = array(
                                'tBCHName'      => $aValueBCH['FTBchName'],
                                'nSumValue'     => ($nValueShow) == '' ? 0 : $nValueShow
                            );
                            array_push($aSUMFooter,$aSUMFooterByBCH); 
                        }
                    }                
                }

                $aRow   = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);


                //ยอดรวมทั้งสิ้น
                if (($nKey + 1) == FCNnHSizeOf($aDataReport['aRptData'])) { 
                    $valuesSUM = [
                        WriterEntityFactory::createCell('รวมทั้งสิ้น'),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                        WriterEntityFactory::createCell(null),
                    ];

                    foreach ($aSUMFooter as $nKeys => $aValueSUM) {
                        array_push($valuesSUM,WriterEntityFactory::createCell(($aValueSUM['nSumValue']) == '' ? 0 : $aValueSUM['nSumValue']));
                    }

                    $aRowSUM = WriterEntityFactory::createRow($valuesSUM, $oStyleColums);
                    $oWriter->addRow($aRowSUM);
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
        $oStyle = (new StyleBuilder())->setFontBold()->setFontSize(12)->build();

        $aCells = [
            WriterEntityFactory::createCell($this->aText['tTitleReport'])
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyle);

        $aCells = [
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow[] = WriterEntityFactory::createRow($aCells, $oStyle);
        
        $aCells = [
            WriterEntityFactory::createCell($tFTCmpName),
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
            WriterEntityFactory::createCell(null)
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
            WriterEntityFactory::createCell($this->aText['tRptTel'] . ' ' . $tFTCmpTel . ' ' . $this->aText['tRptFaxNo'] . ' ' . $tRptFaxNo),
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

        // Fillter ปี
        if (!empty($this->aRptFilter['tYear']) && !empty($this->aRptFilter['tYear'])) {
            $aCells = [
                WriterEntityFactory::createCell('ปี' . ' : ' . $this->aRptFilter['tYear'])
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        // Fillter เดือน
        if (!empty($this->aRptFilter['tMonth']) && !empty($this->aRptFilter['tMonth'])) {
            $aCells = [
                WriterEntityFactory::createCell('เดือน' . ' : ' . $this->aRptFilter['tMonth'])
            ];
            $aMulltiRow[] = WriterEntityFactory::createRow($aCells);
        }

        return $aMulltiRow;
    }

    // ทศนิยม
    public function numberPrecision($number, $decimals = 0){
        $negation = ($number < 0) ? (-1) : 1;
        $coefficient = 10 ** $decimals;
        return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
    }
}