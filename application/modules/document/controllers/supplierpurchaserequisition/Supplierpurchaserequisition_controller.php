<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplierpurchaserequisition_controller extends MX_Controller {

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/supplierpurchaserequisition/Supplierpurchaserequisition_model');
        parent::__construct();
    }
    
    public $tRouteMenu          = 'docPrs/0/0'; //docPrs/0/0 : ใบขอซื้อ , docPrs/0/2 : ใบขอซื้อแฟรนไชส์
    public $tPRSTypeDocument    = '1';          //1 : ใบขอซื้อ  , 2 : ใบขอซื้อแฟรนไชส์
    
    // Index
    public function index($nPrsBrowseType, $tPrsBrowseOption){
        $aParams=array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );

        if($tPrsBrowseOption == 0){ //การซื้อ -> ใบขอซื้อ 
            $this->tRouteMenu       = 'docPrs/0/0';
            $this->tPRSTypeDocument = '1';
        }else{//การขาย -> ใบขอซื้อแฟรนไชส์
            $this->tRouteMenu       =  'docPrs/0/2';
            $this->tPRSTypeDocument = '2';
        }

        $aDataConfigView = array(
            'nPrsBrowseType'    => $nPrsBrowseType,
            'tPrsBrowseOption'  => $tPrsBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave(),
            'aParams'           => $aParams ,
            'tRoute'            => $this->tRouteMenu,
            'tPRSTypeDocument'  => $this->tPRSTypeDocument
        );
        $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisition', $aDataConfigView);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCPRSFormSearchList() {
        $aResult = array(
            'tPRSTypeDocument'  => $this->input->post('tPRSTypeDocument')
        );
        $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionFormSearchList',$aResult);
    }

    // แสดงตารางในหน้า List [TAB : ใบขอซื้อผู้จำหน่าย]
    public function FSoCPRSDataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc($this->tRouteMenu);

            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();

            // Page Current 
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage = $this->input->post('nPageCurrent');
            }
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'aDatSessionUserLogIn'  => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->Supplierpurchaserequisition_model->FSaMPRSGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tPRSViewDataTableList = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionDataTable', $aConfigView, true);
            $aReturnData = array(
                'tPRSViewDataTableList' => $tPRSViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // แสดงตารางในหน้า List [TAB : ใบขอซื้อจากแฟรนไชส์]
    public function FSoCPRSDataTable_FN() {
        try {
            $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
            $nPage              = $this->input->post('nPageCurrent');
            $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $nLangEdit          = $this->session->userdata("tLangEdit");

            // Page Current 
            if ($nPage == '' || $nPage == null) {
                $nPage  = 1;
            } else {
                $nPage  = $this->input->post('nPageCurrent');
            }

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'aDatSessionUserLogIn'  => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->Supplierpurchaserequisition_model->FSaMPRSGetDataTableList_FN($aDataCondition);

            $aConfigView = array(
                'nPage'                 => $nPage,
                'nOptDecimalShow'       => $nOptDecimalShow,
                'aAlwEvent'             => $aAlwEvent,
                'aDataList'             => $aDataList
            );
            $tPRSViewDataTableList = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionDataTable_FN', $aConfigView, true);
            $aReturnData = array(
                'tPRSViewDataTableList' => $tPRSViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCPRSCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/supplierpurchaserequisition/refintdocument/wSupplierPurchaseRequisitionRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCPRSCallRefIntDocDataTable(){
    
        $nPage                  = $this->input->post('nPRSRefIntPageCurrent');
        $tPRSRefIntBchCode      = $this->input->post('tPRSRefIntBchCode');
        $tPRSRefIntDocNo        = $this->input->post('tPRSRefIntDocNo');
        $tPRSRefIntDocDateFrm   = $this->input->post('tPRSRefIntDocDateFrm');
        $tPRSRefIntDocDateTo    = $this->input->post('tPRSRefIntDocDateTo');
        $tPRSRefIntStaDoc       = $this->input->post('tPRSRefIntStaDoc');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPRSRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tPRSRefIntBchCode' => $tPRSRefIntBchCode,
            'tPRSRefIntDocNo' => $tPRSRefIntDocNo,
            'tPRSRefIntDocDateFrm' => $tPRSRefIntDocDateFrm,
            'tPRSRefIntDocDateTo' => $tPRSRefIntDocDateTo,
            'tPRSRefIntStaDoc' => $tPRSRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );

         $aDataParam = $this->Supplierpurchaserequisition_model->FSoMPRSCallRefIntDocDataTable($aDataCondition);

         $aConfigView = array(
            'nPage' => $nPage,
            'aDataList' => $aDataParam,
          );

         $this->load->view('document/supplierpurchaserequisition/refintdocument/wSupplierPurchaseRequisitionRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCPRSCallRefIntDocDetailDataTable(){

        $nLangEdit = $this->session->userdata("tLangEdit");
        $tBchCode = $this->input->post('ptBchCode');
        $tDocNo = $this->input->post('ptDocNo');
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo
        );

        $aDataParam = $this->Supplierpurchaserequisition_model->FSoMPRSCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList' => $aDataParam,
            'nOptDecimalShow' => $nOptDecimalShow
          );
        $this->load->view('document/supplierpurchaserequisition/refintdocument/wSupplierPurchaseRequisitionRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCPRSCallRefIntDocInsertDTToTemp(){
        $tPRSDocNo              =  $this->input->post('tPRSDocNo');
        $tPRSFrmBchCode         =  $this->input->post('tPRSFrmBchCode');
        $tRefIntDocNo           =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode         =  $this->input->post('tRefIntBchCode');
        $aSeqNo                 =  $this->input->post('aSeqNo');
        $tPRSOptionAddPdt       =  $this->input->post('tPRSOptionAddPdt');

        $aDataParam = array(
            'tPRSDocNo'         => $tPRSDocNo,
            'tPRSFrmBchCode'    => $tPRSFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tPRSOptionAddPdt'  => $tPRSOptionAddPdt
        );
        
        $this->Supplierpurchaserequisition_model->FSoMPRSCallRefIntDocInsertDTToTemp($aDataParam);
    }

    // เรียกหน้าเพิ่มข้อมูล
    public function FSoCPRSPageAdd() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo'    => '',
                'FTXthDocKey'   => 'TCNTPdtReqSplHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Supplierpurchaserequisition_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->Supplierpurchaserequisition_model->FSxMPRSClearDataInDocTemp($aWhereClearTemp);

            //ถ้าเป็นแบบแฟรนไซด์
            if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){
                $aSPLConfig     = $this->Supplierpurchaserequisition_model->FSxMPRSFindSPLByConfig();
            }else{
                $aSPLConfig     = '';
            }

            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $nOptDocSave        = FCNnHGetOptionDocSave();
            $tUsrLogin          = $this->session->userdata('tSesUsername');
            $tDptCode           = FCNnDOCGetDepartmentByUser($tUsrLogin);
            $aDataConfigViewAdd = array(
                'tDptCode'          => $tDptCode,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'nOptDocSave'       => $nOptDocSave,
                'aSPLConfig'        => $aSPLConfig,
                'aDataDocHD'        => array('rtCode' => '800'),
                'aDataDocHDSpl'     => array('rtCode' => '800'),
                'tPRSTypeDocument'  => $this->input->post('tPRSTypeDocument')
            );
            $tPRSViewPageAdd = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionPageAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tPRSViewPageAdd'   => $tPRSViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ตารางสินค้า
    public function FSoCPRSPdtAdvTblLoadData() {
        try {
            $bStaSession    =   $this->session->userdata('bSesLogIn');
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData = array(
                    'checksession' => 'expire'
                );
                echo json_encode($aReturnData);
                exit;
            }

            $tPRSDocNo           = $this->input->post('ptPRSDocNo');
            $tPRSStaApv          = $this->input->post('ptPRSStaApv');
            $tPRSStaDoc          = $this->input->post('ptPRSStaDoc');
            $tPRSVATInOrEx       = $this->input->post('ptPRSVATInOrEx');
            $nPRSPageCurrent     = $this->input->post('pnPRSPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tPRSPdtCode         = $this->input->post('ptPRSPdtCode');
            $tPRSPunCode         = $this->input->post('ptPRSPunCode');
            
            //Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tPRSDocNo,
                'FTXthDocKey'           => 'TCNTPdtReqSplDT',
                'nPage'                 => $nPRSPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );

            $aDataDocDTTemp     = $this->Supplierpurchaserequisition_model->FSaMPRSGetDocDTTempListPage($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'       => $nOptDecimalShow,
                'tPRSStaApv'            => $tPRSStaApv,
                'tPRSStaDoc'            => $tPRSStaDoc,
                'tPRSPdtCode'           => $tPRSPdtCode,
                'tPRSPunCode'           => $tPRSPunCode,
                'nPage'                 => $nPRSPageCurrent,
                'aColumnShow'           => array(),
                'aDataDocDTTemp'        => $aDataDocDTTemp,
                'tPRSTypeDocument'      => $this->input->post('tPRSTypeDocument')
            );
            $tPRSPdtAdvTableHtml = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionPdtAdvTableData', $aDataView, true);

            $aReturnData = array(
                'tPRSPdtAdvTableHtml' => $tPRSPdtAdvTableHtml,
                'nStaEvent' => '1',
                'tStaMessg' => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เพิ่มสินค้า ลง Document DT Temp
    public function FSoCPRSAddPdtIntoDocDTTemp() {
        
        try {
            $tPRSUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tPRSDocNo           = $this->input->post('tPRSDocNo');
            $tPRSVATInOrEx       = $this->input->post('tPRSVATInOrEx');
            $tPRSBchCode         = $this->input->post('tSelectBCH');
            $tPRSOptionAddPdt    = $this->input->post('tPRSOptionAddPdt');
            $tPRSPdtData         = $this->input->post('tPRSPdtData');
            $aPRSPdtData         = json_decode($tPRSPdtData);
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $aDataWhere = array(
                'FTBchCode' => $tPRSBchCode,
                'FTXthDocNo' => $tPRSDocNo,
                'FTXthDocKey' => 'TCNTPdtReqSplDT',
            );
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPRSPdtData); $nI++) {
                $tPRSPdtCode = $aPRSPdtData[$nI]->pnPdtCode;
                $tPRSBarCode = $aPRSPdtData[$nI]->ptBarCode;
                $tPRSPunCode = $aPRSPdtData[$nI]->ptPunCode;
  
     
                $cPRSPrice       = $aPRSPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tPRSDocNo,
                    'tBchCode'          => $tPRSBchCode,
                    'tPdtCode'          => $tPRSPdtCode,
                    'tBarCode'          => $tPRSBarCode,
                    'tPunCode'          => $tPRSPunCode,
                    'cPrice'            => str_replace(",","",$cPRSPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdPRSLangEdit"),
                    // 'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TCNTPdtReqSplDT',
                    'tPRSOptionAddPdt'   => $tPRSOptionAddPdt,
                    'tPRSUsrCode'        => $this->input->post('ohdPRSUsrCode'),
                    'nVatRate'          => $nVatRate,
                    'nVatCode'          => $nVatCode
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Supplierpurchaserequisition_model->FSaMPRSGetDataPdt($aDataPdtParams);
                // print_r($aDataPdtMaster);
                // print_r($aDataPdtMaster);
                // นำรายการสินค้าเข้า DT Temp
                $nStaInsPdtToTmp = $this->Supplierpurchaserequisition_model->FSaMPRSInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Add Product Into Document DT Temp.'
                    );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Remove Product In Documeny Temp
    public function FSvCPRSRemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tPRSDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtReqSplDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Supplierpurchaserequisition_model->FSnMPRSDelPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success Delete Product'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Remove Product In Documeny Temp Multiple
    public function FSvCPRSRemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tPRSDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtReqSplDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Supplierpurchaserequisition_model->FSnMPRSDelMultiPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success Delete Product'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Edit Inline สินค้า ลง Document DT Temp
    public function FSoCPRSEditPdtIntoDocDTTemp() {
        try {
            $tPRSBchCode         = $this->input->post('tPRSBchCode');
            $tPRSDocNo           = $this->input->post('tPRSDocNo');
            $nPRSSeqNo           = $this->input->post('nPRSSeqNo');
            $tPRSTypeDocument    = $this->input->post('tPRSTypeDocument');
            $tPRSSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tPRSBchCode'    => $tPRSBchCode,
                'tPRSDocNo'      => $tPRSDocNo,
                'nPRSSeqNo'      => $nPRSSeqNo,
                'tPRSSessionID'  => $tPRSSessionID,
                'tDocKey'       => 'TCNTPdtReqSplDT'
            );

            if($tPRSTypeDocument == 1){ //การซื้อ -> ใบขอซื้อสำนักงานใหญ่
                $aDataUpdateDT = array(
                    'FCXtdQty'          => $this->input->post('nQty')
                );
            }else{ //การขาย -> ใบขอซื้อแฟรนไชส์ 
                $aDataUpdateDT = array(
                    'FCXtdQtyOrd'        => $this->input->post('nQty')
                );
            }

            $this->db->trans_begin();

            $this->Supplierpurchaserequisition_model->FSaMPRSUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => "Error Update Inline Into Document DT Temp."
                );
            }else{
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => "Update Inline Into Document DT Temp."
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Check Product Have In Temp For Document DT
    public function FSoCPRSChkHavePdtForDocDTTemp() {
        try {
            $tPRSDocNo = $this->input->post("ptPRSDocNo");
            $tPRSSessionID = $this->input->post('tPRSSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tPRSDocNo,
                'FTXthDocKey' => 'TCNTPdtReqSplDT',
                'FTSessionID' => $tPRSSessionID
            );
            $nCountPdtInDocDTTemp = $this->Supplierpurchaserequisition_model->FSnMPRSChkPdtInDocDTTemp($aDataWhere);
            
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSPleaseSeletedPDTIntoTable')
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ฟังก์ชั่นเพิ่มข้อมูล
    public function FSoCPRSAddEventDoc() {
        try {
            
            $aDataDocument      = $this->input->post();
            $tPRSAutoGenCode    = (isset($aDataDocument['ocbPRSStaAutoGenCode'])) ? 1 : 0;
            $tPRSDocNo          = (isset($aDataDocument['oetPRSDocNo'])) ? $aDataDocument['oetPRSDocNo'] : '';
            $tPRSDocDate        = $aDataDocument['oetPRSDocDate'] . " " . $aDataDocument['oetPRSDocTime'];
            $tPRSStaDocAct      = (isset($aDataDocument['ocbPRSFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tPRSVATInOrEx      = $aDataDocument['ocmPRSFrmSplInfoVatInOrEx'];
            $nPRSSubmitWithImp  = $aDataDocument['ohdPRSSubmitWithImp'];
            
            //ตัวแทนขาย หรือแฟรนไซด์
            if($aDataDocument['oetPRSAgnCode'] == ''){
                $nDocType = 11; //ใบขอซื้อ - แฟรนไชส์
            }else{
                $nDocType = 12; //ใบขอซื้อ - ตัวแทนขาย
            }

            //ล้างข้อมูล
            $aClearDTParams = [
                'FTXthDocNo'     => $tPRSDocNo,
                'FTXthDocKey'    => 'TCNTPdtReqSplDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            
            if($nPRSSubmitWithImp == 1){
                $this->Supplierpurchaserequisition_model->FSxMPRSClearDataInDocTempForImp($aClearDTParams);
            }

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TCNTPdtReqSplHD',
                'tTableHDSpl'       => 'TCNTPdtReqSplHDSpl',
                'tTableDT'          => 'TCNTPdtReqSplDT',
                'tTableStaGen'      => 11,
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRSFrmBchCode'],
                'FTXphDocNo'        => $tPRSDocNo,
                'FTOldBchCode'      => $aDataDocument['ohdPRSBchCode'],
                'FTOldXphDocNo'     => $tPRSDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdPRSUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdPRSUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tPRSVATInOrEx,
                'tTypeVisit'        => 1
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
                'FNXphDocType'      => $nDocType,
                'FDXphDocDate'      => (!empty($tPRSDocDate)) ? $tPRSDocDate : NULL,
                'FTUsrCode'         => $aDataDocument['ohdPRSUsrCode'],
                'FTSplCode'         => $aDataDocument['oetPRSFrmSplCode'],
                'FTXphAgnTo'        => $aDataDocument['oetPRSAgnCodeTo'],
                'FTXphBchTo'        => $aDataDocument['oetPRSToBchCode'],
                'FNXphDocPrint'     => $aDataDocument['ocmPRSFrmInfoOthDocPrint'],
                'FTXphRmk'          => $aDataDocument['otaPRSFrmInfoOthRmk'],
                'FTXphStaDoc'       => $aDataDocument['ohdPRSStaDoc'],
                'FTXphStaApv'       => !empty($aDataDocument['ohdPRSStaApv']) ? $aDataDocument['ohdPRSStaApv'] : NULL,
                'FNXphStaDocAct'    => $tPRSStaDocAct,
                'FTXphCshOrCrd'     => $aDataDocument['ocmPRSTypePayment'],
                'FTXphVATInOrEx'    => $tPRSVATInOrEx,
                'FNXphStaRef'       => $aDataDocument['ocmPRSFrmInfoOthRef'],
                'FTXphApvCode'      => '',
            );
            
            // Array Data HD Supplier
            $aDataSpl = array(
                'FNXphCrTerm'       => intval($aDataDocument['oetPRSFrmSplInfoCrTerm']),
                'FTXphCtrName'      => $aDataDocument['oetPRSFrmSplInfoCtrName'],
                'FDXphTnfDate'      => (!empty($aDataDocument['oetPRSFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPRSFrmSplInfoTnfDate'])) : NULL,
                'FTXphRefTnfID'     => $aDataDocument['oetPRSFrmSplInfoRefTnfID'],
                'FTXphRefVehID'     => $aDataDocument['oetPRSFrmSplInfoRefVehID']
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tPRSAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TCNTPdtReqSplHD',                           
                    "tDocType"    => $nDocType,                                          
                    "tBchCode"    => $aDataDocument['oetPRSFrmBchCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo'] = $tPRSDocNo;
            }

            // [Add] Document HD
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Add] Document HD Spl
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Move] Doc DTTemp To DT
            $this->Supplierpurchaserequisition_model->FSaMPRSMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
            
            // [Move] Doc TCNTDocHDRefTmp 
            $this->Supplierpurchaserequisition_model->FSxMPRSMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXphDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ฟังก์ชั่นแก้ไขข้อมูล
    public function FSoCPRSEditEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tPRSDocNo          = (isset($aDataDocument['oetPRSDocNo'])) ? $aDataDocument['oetPRSDocNo'] : '';
            $tPRSDocDate        = $aDataDocument['oetPRSDocDate'] . " " . $aDataDocument['oetPRSDocTime'];
            $tPRSStaDocAct      = (isset($aDataDocument['ocbPRSFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tPRSVATInOrEx      = $aDataDocument['ocmPRSFrmSplInfoVatInOrEx'];
            $nPRSSubmitWithImp  = $aDataDocument['ohdPRSSubmitWithImp'];

            $aClearDTParams = [
                'FTXthDocNo'     => $tPRSDocNo,
                'FTXthDocKey'    => 'TCNTPdtReqSplDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nPRSSubmitWithImp == 1){
                $this->Supplierpurchaserequisition_model->FSxMPRSClearDataInDocTempForImp($aClearDTParams);
            }

            //เป็นเอกสาร ตัวแทนขาย หรือแฟรนไซด์
            if($aDataDocument['oetPRSAgnCode'] == ''){
                $nDocType = 11; //ใบขอซื้อ - แฟรนไชส์
            }else{
                $nDocType = 12; //ใบขอซื้อ - ตัวแทนขาย
            }
            
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TCNTPdtReqSplHD',
                'tTableHDSpl'       => 'TCNTPdtReqSplHDSpl',
                'tTableDT'          => 'TCNTPdtReqSplDT',
                'tTableStaGen'      => $nDocType,
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRSFrmBchCode'],
                'FTOldBchCode'      => $aDataDocument['ohdPRSBchCode'],
                'FTOldXphDocNo'     => $tPRSDocNo,
                'FTXphDocNo'        => $tPRSDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdPRSCreateBy'),
                'FTLastUpdBy'       => $this->input->post('ohdPRSUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tPRSVATInOrEx,
                'tTypeVisit'        => 2
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'         => $aDataDocument['oetPRSAgnCode'],
                'FNXphDocType'      => $nDocType,
                'FDXphDocDate'      => (!empty($tPRSDocDate)) ? $tPRSDocDate : NULL,
                'FTUsrCode'         => $aDataDocument['ohdPRSUsrCode'],
                'FTSplCode'         => $aDataDocument['oetPRSFrmSplCode'],
                'FTXphAgnTo'        => $aDataDocument['oetPRSAgnCodeTo'],
                'FTXphBchTo'        => $aDataDocument['oetPRSToBchCode'],
                'FNXphDocPrint'     => $aDataDocument['ocmPRSFrmInfoOthDocPrint'],
                'FTXphRmk'          => $aDataDocument['otaPRSFrmInfoOthRmk'],
                'FTXphStaDoc'       => $aDataDocument['ohdPRSStaDoc'],
                'FTXphStaApv'       => !empty($aDataDocument['ohdPRSStaApv']) ? $aDataDocument['ohdPRSStaApv'] : NULL,
                'FNXphStaDocAct'    => $tPRSStaDocAct,
                'FTXphCshOrCrd'     => $aDataDocument['ocmPRSTypePayment'],
                'FTXphVATInOrEx'    => $tPRSVATInOrEx,
                'FNXphStaRef'       => $aDataDocument['ocmPRSFrmInfoOthRef'],
                'FTXphApvCode'      => $aDataDocument['ohdPRSApvCode'],
            );

            // Array Data HD Supplier 
            $aDataSpl = array(
                'FNXphCrTerm'       => intval($aDataDocument['oetPRSFrmSplInfoCrTerm']),
                'FTXphCtrName'      => $aDataDocument['oetPRSFrmSplInfoCtrName'],
                'FDXphTnfDate'      => (!empty($aDataDocument['oetPRSFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPRSFrmSplInfoTnfDate'])) : NULL,
                'FTXphRefTnfID'     => $aDataDocument['oetPRSFrmSplInfoRefTnfID'],
                'FTXphRefVehID'     => $aDataDocument['oetPRSFrmSplInfoRefVehID'],
            );

            $this->db->trans_begin();

            // [Add] Document HD
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Add] Document HD Spl
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Supplierpurchaserequisition_model->FSxMPRSAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Move] Doc DTTemp To DT
            $this->Supplierpurchaserequisition_model->FSaMPRSMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            // [Move] Doc TCNTDocHDRefTmp 
            $this->Supplierpurchaserequisition_model->FSxMPRSMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXphDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // หน้าจอแก้ไข
    public function FSvCPRSEditPage(){
        try {
            $ptDocumentNumber = $this->input->post('ptPRSDocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Supplierpurchaserequisition_model->FSnMPRSDelALLTmp($aWhereClearTemp);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TCNTPdtReqSplDT',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 90000,
                'nPage'         => 1,
            );

            // Get Autentication Route
                $aAlwEvent         = FCNaHCheckAlwFunc($this->tRouteMenu); // Controle Event
                $vBtnSave          = FCNaHBtnSaveActiveHTML($this->tRouteMenu); // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
                $nOptDecimalShow   = FCNxHGetOptionDecimalShow();
                $nOptDecimalSave   = FCNxHGetOptionDecimalSave();
                $nOptDocSave       = FCNnHGetOptionDocSave();

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->Supplierpurchaserequisition_model->FSaMPRSGetDataDocHD($aDataWhere);

            // Move Data DT To DTTemp
            $this->Supplierpurchaserequisition_model->FSxMPRSMoveDTToDTTemp($aDataWhere);

            // Move Data HDDocRef To HDRefTemp
            $this->Supplierpurchaserequisition_model->FSxMPRSMoveHDRefToHDRefTemp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
                
            } else {
                $this->db->trans_commit();

                $aDataConfigViewEdit = array(
                    'aAlwEvent'         => $aAlwEvent,
                    'vBtnSave'          => $vBtnSave,
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'nOptDecimalSave'   => $nOptDecimalSave,
                    'nOptDocSave'       => $nOptDocSave,
                    'aRateDefault'      => '',
                    'aDataDocHD'        => $aDataDocHD,
                    'tPRSTypeDocument'  => $this->input->post('tPRSTypeDocument')
                );

                $tViewPageEdit           = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionPageAdd',$aDataConfigViewEdit,true);
                $aReturnData = array(
                    'tViewPageEdit'      => $tViewPageEdit,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Success'
                );
            }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function Delete Document
    public function FSoCPRSDeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $tRefInDocNo = $this->input->post('tPRSRefInCode');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->Supplierpurchaserequisition_model->FSaMPRSUpdatePRBStaRef($tRefInDocNo, $nStaRef);
            }
            
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tBchCode' => $tBchCode
            );
            
            $aResDelDoc = $this->Supplierpurchaserequisition_model->FSnMPRSDelDocument($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc']
                );
            }
        } catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aDataStaReturn);
    }

    // Cancel Status Document
    public function FSvCPRSCancelDocument() {
        try {
            $tPRSDocNo = $this->input->post('ptPRSDocNo');
            $tRefInDocNo = $this->input->post('ptRefInDocNo');
            
            $aDataUpdate = array(
                'tDocNo' => $tPRSDocNo,
            );

            $aStaApv = $this->Supplierpurchaserequisition_model->FSaMPRSCancelDocument($aDataUpdate);
            $aReturnData = $aStaApv;
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // อนุมัติเอกสาร
    public function FSoCPRSApproveEvent(){
        try{
            $tDocNo             = $this->input->post('tDocNo');
            $tBchCode           = $this->input->post('tBchCode');
            $tAGNCode           = $this->input->post('tAGNCode');
            $tPRSTypeDocument   = $this->input->post('tPRSTypeDocument');

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXphDocNo'        => $tDocNo,
                'FTXphStaApv'       => 1,
                'FTXphUsrApv'       => $this->session->userdata('tSesUsername'),
                'tAGNCode'          => $tAGNCode,
                'tPRSTypeDocument'  => $this->input->post('tPRSTypeDocument')
            );
            $this->Supplierpurchaserequisition_model->FSaMPRSApproveDocument($aDataUpdate);

            //หาว่าเอกสารใบขอซื้อ ใบนี้ถูกสร้างมาจาก ใบจัดการสินค้าจากสาขาหรือเปล่า 
            $aItemDoc = $this->Supplierpurchaserequisition_model->FSaMPRSFindPRBInDatabase($aDataUpdate);
            if($aItemDoc['rtCode'] == 1){ //พบข้อมูล
                $aItemDocSendMQ     = $aItemDoc['raItems'];
                $tDocRefKeyReqSPL   = '';
                for($k=0; $k<count($aItemDocSendMQ); $k++){
                    $tDocRef            = $aItemDocSendMQ[$k]['FTXphDocNo'];
                    $tDocRefKeyReqSPL   .= $tDocRef.',';
                }
                if($tDocRefKeyReqSPL != '' || $tDocRefKeyReqSPL != null){
                    $tDocRefKeyReqSPL = rtrim($tDocRefKeyReqSPL, ", ");
                }

                $aMQParams = [
                    "queueName" => "CN_QDocApprove",
                    "params"    => [
                        'ptFunction'    => 'TCNTPdtReqSplHD',
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => $tDocRefKeyReqSPL,
                        'ptData'        => json_encode([
                            "ptBchCode"     => $tBchCode,
                            "ptDocNo"       => $tDocNo,
                            "ptDocType"     => 2,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];

                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
            }

            $aDataGetDataHD     =   $this->Supplierpurchaserequisition_model->FSaMPRSGetDataDocHD(array(
                'FTXphDocNo'    => $tDocNo,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            ));

            if($aDataGetDataHD['rtCode']=='1'){

                //ส่ง Noti
                $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXphDocNo']);
                $aMQParamsNoti = [
                    "queueName"     => "CN_SendToNoti",
                    "tVhostType"    => "NOT",
                    "params"        => [
                        "oaTCNTNoti" => array(
                            "FNNotID"       => $tNotiID,
                            "FTNotCode"     => '00002',
                            "FTNotKey"      => 'TCNTPdtReqSplHD',
                            "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
                            "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXphDocNo'],
                        ),
                        "oaTCNTNoti_L" => array(
                            0 => array(
                                "FNNotID"       => $tNotiID,
                                "FNLngID"       => 1,
                                "FTNotDesc1"    => 'เอกสารใบขอซื้อผู้จำหน่าย #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
                                "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' ทำการอนุมัติเอกสาร',
                            ),
                            1 => array(
                                "FNNotID"       => $tNotiID,
                                "FNLngID"       => 2,
                                "FTNotDesc1"    => 'Vendor purchase requisitions #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
                                "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Approve document',
                            )
                        ),
                        "oaTCNTNotiAct" => array(
                            0 => array(  
                                "FNNotID"       => $tNotiID,
                                "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                "FTNoaDesc"      => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' ทำการอนุมัติเอกสาร',
                                "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXphDocNo'],
                                "FNNoaUrlType"   =>  1,
                                "FTNoaUrlRef"    => 'docPrs/2/0',
                            ),
                        ), 
                        "oaTCNTNotiSpc" => array(
                            0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FTNotType"    => '1',
                                    "FTNotStaType" => '1',
                                    "FTAgnCode"    => '',
                                    "FTAgnName"    => '',
                                    "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                    "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
                            ),
                            1 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FTNotType"    => '2',
                                    "FTNotStaType" => '1',
                                    "FTAgnCode"    => '',
                                    "FTAgnName"    => '',
                                    "FTBchCode"    => $aDataGetDataHD['raItems']['FTXphShipTo'],
                                    "FTBchName"    => $aDataGetDataHD['raItems']['rtShipName'],
                            ),
                        ),
                        "ptUser"        => $this->session->userdata('tSesUsername'),
                    ]
                ];
                FCNxCallRabbitMQ($aMQParamsNoti);
            }

            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Success"
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCPRSPageHDDocRef(){
        try {
            $tDocNo     = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TCNTPdtReqSplHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXshDocNo'        => $tDocNo,
                'FTXshDocKey'       => 'TCNTPdtReqSplHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef = $this->Supplierpurchaserequisition_model->FSaMPRSGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/supplierpurchaserequisition/wSupplierPurchaseRequisitionDocRef', $aDataConfig, true);
            $aReturnData = array(
                'tViewPageHDRef'    => $tViewPageHDRef,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - เพิ่ม หรือ เเก้ไข
    public function FSoCPRSEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXshDocNo'        => $this->input->post('ptPRSDocNo'),
                'FTXshDocKey'       => 'TCNTPdtReqSplHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tPRSRefDocNoOld'   => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData = $this->Supplierpurchaserequisition_model->FSaMPRSAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent'         => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCPRSEventDelHDDocRef(){
        try {
            $aData = [
                'FTXshDocNo'        => $this->input->post('ptDocNo'),
                'FTXshRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXshDocKey'       => 'TCNTPdtReqSplHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->Supplierpurchaserequisition_model->FSaMPRSDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }
}
