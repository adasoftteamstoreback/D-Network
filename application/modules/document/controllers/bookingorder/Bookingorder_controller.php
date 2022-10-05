<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookingorder_controller extends MX_Controller {

    public function __construct()
    {
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/bookingorder/Bookingorder_model');
        parent::__construct();
    }
    
    public $tRouteMenu  = 'docBKO/0/0';
    
    // Index
    public function index($nBKOBrowseType, $tBKOBrowseOption)
    {
        //รองรับการ Jump
        $aParams    =   array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );

        $aDataConfigView = array(
            'nBKOBrowseType'        => $nBKOBrowseType,
            'tBKOBrowseOption'      => $tBKOBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'              => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'aParams'               => $aParams 
        );
        
        $this->load->view('document/bookingorder/wBookingOrder', $aDataConfigView);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCBKOFormSearchList() {
        $this->load->view('document/bookingorder/wBookingOrderFormSearchList');
    }

    // แสดงตารางในหน้า List
    public function FSoCBKODataTable() {
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
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID' => $nLangEdit,
                'nPage' => $nPage,
                'nRow' => 10,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList = $this->Bookingorder_model->FSaMBKOGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tTWXViewDataTableList = $this->load->view('document/bookingorder/wBookingOrderDataTable', $aConfigView, true);
            $aReturnData = array(
                'tTWXViewDataTableList' => $tTWXViewDataTableList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
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
    public function FSoCBKOCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCBKOCallRefIntDocPO(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDocPO', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCBKOCallRefIntDocDataTable(){
    
        $nPage = $this->input->post('nTWXRefIntPageCurrent');
        $tTWXRefIntBchCode   = $this->input->post('tTWXRefIntBchCode');
        $tTWXRefIntDocNo   = $this->input->post('tTWXRefIntDocNo');
        $tTWXRefIntDocDateFrm   = $this->input->post('tTWXRefIntDocDateFrm');
        $tTWXRefIntDocDateTo   = $this->input->post('tTWXRefIntDocDateTo');
        $tTWXRefIntStaDoc   = $this->input->post('tTWXRefIntStaDoc');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nTWXRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tTWXRefIntBchCode' => $tTWXRefIntBchCode,
            'tTWXRefIntDocNo' => $tTWXRefIntDocNo,
            'tTWXRefIntDocDateFrm' => $tTWXRefIntDocDateFrm,
            'tTWXRefIntDocDateTo' => $tTWXRefIntDocDateTo,
            'tTWXRefIntStaDoc' => $tTWXRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );

         $aDataParam = $this->Bookingorder_model->FSoMTWXCallRefIntDocDataTable($aDataCondition);
         
         $aConfigView = array(
            'nPage' => $nPage,
            'aDataList' => $aDataParam,
          );

         $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDocDataTable', $aConfigView);
    }

        // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
        public function FSoCBKOCallRefIntDocDataTablePO(){

        $nPage = $this->input->post('nTWXRefIntPageCurrent');
        $tTWXRefIntBchCode   = $this->input->post('tTWXRefIntBchCode');
        $tTWXRefIntDocNo   = $this->input->post('tTWXRefIntDocNo');
        $tTWXRefIntDocDateFrm   = $this->input->post('tTWXRefIntDocDateFrm');
        $tTWXRefIntDocDateTo   = $this->input->post('tTWXRefIntDocDateTo');
        $tTWXRefIntStaDoc   = $this->input->post('tTWXRefIntStaDoc');
    
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nTWXRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tTWXRefIntBchCode' => $tTWXRefIntBchCode,
            'tTWXRefIntDocNo' => $tTWXRefIntDocNo,
            'tTWXRefIntDocDateFrm' => $tTWXRefIntDocDateFrm,
            'tTWXRefIntDocDateTo' => $tTWXRefIntDocDateTo,
            'tTWXRefIntStaDoc' => $tTWXRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );

            $aDataParam = $this->Bookingorder_model->FSoMTWXCallRefIntDocDataTablePO($aDataCondition);
            
            $aConfigView = array(
            'nPage' => $nPage,
            'aDataList' => $aDataParam,
            );

            $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDocDataTablePO', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCBKOCallRefIntDocDetailDataTable(){

        $nLangEdit = $this->session->userdata("tLangEdit");
        $tBchCode = $this->input->post('ptBchCode');
        $tDocNo = $this->input->post('ptDocNo');
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo
        );

        $aDataParam = $this->Bookingorder_model->FSoMTWXCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList' => $aDataParam,
            'nOptDecimalShow' => $nOptDecimalShow
          );
        $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCBKOCallRefIntDocDetailDataTablePO(){

        $nLangEdit = $this->session->userdata("tLangEdit");
        $tBchCode = $this->input->post('ptBchCode');
        $tDocNo = $this->input->post('ptDocNo');
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo
        );

        $aDataParam = $this->Bookingorder_model->FSoMTWXCallRefIntDocDTDataTablePO($aDataCondition);

        $aConfigView = array(
            'aDataList' => $aDataParam,
            'nOptDecimalShow' => $nOptDecimalShow
            );
        $this->load->view('document/bookingorder/refintdocument/wBookingOrderRefDocDetailDataTablePO', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCBKOCallRefIntDocInsertDTToTemp(){
        $tTWXDocNo       =  $this->input->post('tTWXDocNo');
        $tTWXFrmBchCode  =  $this->input->post('tTWXFrmBchCode');
        $tRefIntDocNo   =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode =  $this->input->post('tRefIntBchCode');
        $aSeqNo         =  $this->input->post('aSeqNo');
        $aDataParam = array(
            'tTWXDocNo'       => $tTWXDocNo,
            'tTWXFrmBchCode'  => $tTWXFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );
        
       $aDataResult = $this->Bookingorder_model->FSoMTWXCallRefIntDocInsertDTToTemp($aDataParam);
       return  $aDataResult;
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCBKOCallRefIntDocInsertDTToTempPO(){
        $tTWXDocNo       =  $this->input->post('tTWXDocNo');
        $tTWXFrmBchCode  =  $this->input->post('tTWXFrmBchCode');
        $tRefIntDocNo   =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode =  $this->input->post('tRefIntBchCode');
        $aSeqNo         =  $this->input->post('aSeqNo');
        $aDataParam = array(
            'tTWXDocNo'       => $tTWXDocNo,
            'tTWXFrmBchCode'  => $tTWXFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );
        
        $aDataResult = $this->Bookingorder_model->FSoMTWXCallRefIntDocInsertDTToTempPO($aDataParam);
        return  $aDataResult;
    }

    // เรียกหน้าเพิ่มข้อมูล
    public function FSoCBKOPageAdd() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => '',
                'FTXthDocKey' => 'TCNTPdtTwxHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];

            $tUserBchCode = $this->session->userdata('tSesUsrBchCodeDefault');
            if(!empty($tUserBchCode)){
                $aDataBch = $this->Bookingorder_model->FSaMTWXGetDetailUserBranch($tUserBchCode);
                $tTWXPplCode = $aDataBch['item']['FTPplCode'];
            }else{
                $tTWXPplCode = '';
            }
     

            $this->Bookingorder_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->Bookingorder_model->FSxMTWXClearDataInDocTemp($aWhereClearTemp);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            $aDataComp = FCNaGetCompanyForDocument();

            $tBchCode = $aDataComp['tBchCode'];
            $tCmpRteCode = $aDataComp['tCmpRteCode'];
            $tVatCode = $aDataComp['tVatCode'];
            $cVatRate = $aDataComp['cVatRate'];
            $cXthRteFac = $aDataComp['cXthRteFac'];
            $tCmpRetInOrEx = $aDataComp['tCmpRetInOrEx'];
            
            // Get Department Code
            $tUsrLogin = $this->session->userdata('tSesUsername');
            $tDptCode = FCNnDOCGetDepartmentByUser($tUsrLogin);

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            $aDataShp = array(
                'FNLngID' => $nLangEdit,
                'tUsrLogin' => $tUsrLogin
            );
            $aDataWah = array(
                'FNLngID' => $nLangEdit,
                'tBchCode' => $tBchCode
            );
            $aDataUserGroup = $this->Bookingorder_model->FSaMTWXGetShpCodeForUsrLogin($aDataShp);
            $aDataWah       = $this->Bookingorder_model->FSaMTWXGetWahCodeForDoc($aDataWah);
            if(isset($aDataWah['raItemsWah']) && empty($aDataWah['raItemsWah'])){
                $tWahCodeFrm = "";
                $tWahNameFrm = "";
            }else{
                $tWahCodeFrm = $aDataWah['raItemsWah'][0]['FTWahCode'];
                $tWahNameFrm = $aDataWah['raItemsWah'][0]['FTWahName'];
            }
            if(isset($aDataWah['raItemsWahBook']) && empty($aDataWah['raItemsWahBook']) ){
                $tWahCodeTo = "";
                $tWahNameTo = "";
            }else{
                $tWahCodeTo = $aDataWah['raItemsWahBook'][0]['FTWahCode'];
                $tWahNameTo = $aDataWah['raItemsWahBook'][0]['FTWahName'];
            }
            
            if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
                $tBchCode = "";
                $tBchName = "";
                $tMerCode = "";
                $tMerName = "";
                $tShopType = "";
                $tShopCode = "";
                $tShopName = "";
                $tWahCode = "";
                $tWahName = "";
            } else {
                $tBchCode = $aDataUserGroup["FTBchCode"];
                $tBchName = $aDataUserGroup["FTBchName"];
                $tMerCode = $aDataUserGroup["FTMerCode"];
                $tMerName = $aDataUserGroup["FTMerName"];
                $tShopType = $aDataUserGroup["FTShpType"];
                $tShopCode = $aDataUserGroup["FTShpCode"];
                $tShopName = $aDataUserGroup["FTShpName"];
                $tWahCode = $aDataUserGroup["FTWahCode"];
                $tWahName = $aDataUserGroup["FTWahName"];
            }

            
            $aDataConfigViewAdd = array(
                'nOptDecimalShow' => $nOptDecimalShow,
                'nOptDocSave' => $nOptDocSave,
                'nOptScanSku' => $nOptScanSku,
                'tCmpRteCode' => $tCmpRteCode,
                'tVatCode' => $tVatCode,
                'cVatRate' => $cVatRate,
                'cXthRteFac' => $cXthRteFac,
                'tDptCode' => $tDptCode,
                'tBchCode' => $tBchCode,
                'tBchName' => $tBchName,
                'tMerCode' => $tMerCode,
                'tMerName' => $tMerName,
                'tShopType' => $tShopType,
                'tShopCode' => $tShopCode,
                'tShopName' => $tShopName,
                'tWahCode' => $tWahCode,
                'tWahName' => $tWahName,
                'tWahCodeFrm' => $tWahCodeFrm,
                'tWahCodeTo' => $tWahCodeTo,
                'tWahNameFrm' => $tWahNameFrm,
                'tWahNameTo' => $tWahNameTo,
                'tBchCompCode' => FCNtGetBchInComp(),
                'tBchCompName' => FCNtGetBchNameInComp(),
                'aDataDocHD' => array('rtCode' => '800'),
                'aDataDocHDSpl' => array('rtCode' => '800'),
                'tCmpRetInOrEx' => $tCmpRetInOrEx,
                'tTWXPplCode'  => $tTWXPplCode
            );
            
            $tTWXViewPageAdd = $this->load->view('document/bookingorder/wBookingOrderPageAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tTWXViewPageAdd' => $tTWXViewPageAdd,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // แสดงผลลัพธ์การค้นหาขั้นสูง
    public function FSoCBKOPdtAdvTblLoadData() {
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

            $tTWXDocNo           = $this->input->post('ptTWXDocNo');
            $tTWXStaApv          = $this->input->post('ptTWXStaApv');
            $tTWXStaDoc          = $this->input->post('ptTWXStaDoc');
            $tTWXVATInOrEx       = $this->input->post('ptTWXVATInOrEx');
            $nTWXPageCurrent     = $this->input->post('pnTWXPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tTWXPdtCode         = $this->input->post('ptTWXPdtCode');
            $tTWXPunCode         = $this->input->post('ptTWXPunCode');
            
            //Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Call Advance Table
            $tTableGetColumeShow    = 'TCNTPdtTwxDT';
            // $aColumnShow            = FCNaDCLGetColumnShow($tTableGetColumeShow);
            
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tTWXDocNo,
                'FTXthDocKey'           => 'TCNTPdtTwxDT',
                'nPage'                 => $nTWXPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );
            // FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp     = $this->Bookingorder_model->FSaMTWXGetDocDTTempListPage($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tTWXStaApv'         => $tTWXStaApv,
                'tTWXStaDoc'         => $tTWXStaDoc,
                'tTWXPdtCode'        => $tTWXPdtCode,
                'tTWXPunCode'        => $tTWXPunCode,
                'nPage'             => $nTWXPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );
            $tTWXPdtAdvTableHtml = $this->load->view('document/bookingorder/wBookingOrderPdtAdvTableData', $aDataView, true);

            $aReturnData = array(
                'tTWXPdtAdvTableHtml' => $tTWXPdtAdvTableHtml,
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

    // Add สินค้า ลง Document DT Temp
    public function FSoCBKOAddPdtIntoDocDTTemp() {
        
        try {
            $tTWXUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tTWXDocNo           = $this->input->post('tTWXDocNo');
            $tTWXBchCode         = $this->input->post('tSelectBCH');
            $tTWXOptionAddPdt    = $this->input->post('tTWXOptionAddPdt');
            $tTWXPdtData         = $this->input->post('tTWXPdtData');
            $aTWXPdtData         = json_decode($tTWXPdtData);
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $aDataWhere = array(
                'FTBchCode' => $tTWXBchCode,
                'FTXthDocNo' => $tTWXDocNo,
                'FTXthDocKey' => 'TCNTPdtTwxDT',
            );
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aTWXPdtData); $nI++) {
                $tTWXPdtCode = $aTWXPdtData[$nI]->pnPdtCode;
                $tTWXBarCode = $aTWXPdtData[$nI]->ptBarCode;
                $tTWXPunCode = $aTWXPdtData[$nI]->ptPunCode;
  
     
                $cTWXPrice       = $aTWXPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tTWXDocNo,
                    'tBchCode'          => $tTWXBchCode,
                    'tPdtCode'          => $tTWXPdtCode,
                    'tBarCode'          => $tTWXBarCode,
                    'tPunCode'          => $tTWXPunCode,
                    'cPrice'            => str_replace(",","",$cTWXPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdTWXLangEdit"),
                    // 'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TCNTPdtTwxDT',
                    'tTWXOptionAddPdt'   => $tTWXOptionAddPdt,
                    'tTWXUsrCode'        => $this->input->post('ohdTWXUsrCode'),
                    'nVatRate'          => $nVatRate,
                    'nVatCode'          => $nVatCode
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Bookingorder_model->FSaMTWXGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $nStaInsPdtToTmp = $this->Bookingorder_model->FSaMTWXInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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

    // Function: Remove Product In Documeny Temp
    public function FSvCBKORemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tTWXDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtTwxDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Bookingorder_model->FSnMTWXDelPdtInDTTmp($aDataWhere);

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

    //Remove Product In Documeny Temp Multiple
    public function FSvCBKORemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tTWXDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtTwxDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Bookingorder_model->FSnMTWXDelMultiPdtInDTTmp($aDataWhere);

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

    //Remove Product In Documeny Temp Multiple
    public function FSoCBKOGetAddress() {
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataWhere = array(
            'tCstCode'      => $this->input->post('tCstCode'),
            'tCstName'      => $this->input->post('tCstName'),
            'FNLngID'       => $nLangEdit,
        );
        $aCstAddress = $this->Bookingorder_model->FSnMTWXGetCstAddress($aDataWhere);
        echo json_encode($aCstAddress);
    }

    // Function: Edit Inline สินค้า ลง Document DT Temp
    public function FSoCBKOEditPdtIntoDocDTTemp() {
        try {
            $tTWXBchCode         = $this->input->post('tTWXBchCode');
            $tTWXDocNo           = $this->input->post('tTWXDocNo');
            $nTWXSeqNo           = $this->input->post('nTWXSeqNo');
            $tTWXSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tTWXBchCode'    => $tTWXBchCode,
                'tTWXDocNo'      => $tTWXDocNo,
                'nTWXSeqNo'      => $nTWXSeqNo,
                'tTWXSessionID'  => $tTWXSessionID,
                'tDocKey'       => 'TCNTPdtTwxDT',
            );
            $aDataUpdateDT = array(
                'FCXtdQty'          => $this->input->post('nQty'),
            );
            $this->db->trans_begin();
            $this->Bookingorder_model->FSaMTWXUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

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
            
            echo "<pre>";
            print_r ($aReturnData);
            echo "</pre>";
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function: Check Product Have In Temp For Document DT
    public function FSoCBKOChkHavePdtForDocDTTemp() {
        try {
            $tTWXDocNo = $this->input->post("ptTWXDocNo");
            $tTWXSessionID = $this->input->post('tTWXSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tTWXDocNo,
                'FTXthDocKey' => 'TCNTPdtTwxDT',
                'FTSessionID' => $tTWXSessionID
            );
            $nCountPdtInDocDTTemp = $this->Bookingorder_model->FSnMTWXChkPdtInDocDTTemp($aDataWhere);
            
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/bookingorder/bookingorder', 'tTWXPleaseSeletedPDTIntoTable')
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

    // Function: Add Document 
    public function FSoCBKOAddEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tTWXAutoGenCode = (isset($aDataDocument['ocbTWXStaAutoGenCode'])) ? 1 : 0;
            $tTWXDocNo = (isset($aDataDocument['oetTWXDocNo'])) ? $aDataDocument['oetTWXDocNo'] : '';
            $tTWXDocDate = $aDataDocument['oetTWXDocDate'] . " " . $aDataDocument['oetTWXDocTime'];
            $tTWXStaDocAct = (isset($aDataDocument['ocbTWXFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tTWXSessionID = $this->input->post('ohdSesSessionID');
            $nLangEdit = $this->input->post("ohdTWXLangEdit");
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $tATWXReq = "";
            $tRefin = $this->input->post("oetTWXRefInAllName");
            $tRefinOld = $this->input->post("ohdTWXRefIntDoc");
            $tMethodReq = "GET";
            $aCompData = $this->mCompany->FSaMCMPList($tATWXReq, $tMethodReq, $aDataWhereComp);
            $aClearDTParams = [
                'FTXthDocNo'     => $tTWXDocNo,
                'FTXthDocKey'    => 'TCNTPdtTwxDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];

//-----------------------------------------------------------------------------
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD' => 'TCNTPdtTwxHD',
                'tTableHDRef' => 'TCNTPdtTwxHDRef',
                'tTableDT' => 'TCNTPdtTwxDT',
                'tTableStaGen' => 11,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode' => $aDataDocument['oetTWXWahBrcCode'],
                'FTXthDocNo' => $tTWXDocNo,
                'FTOldBchCode' => $aDataDocument['ohdTWXBchCode'],
                'FTOldXphDocNo' => $tTWXDocNo,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->input->post('ohdTWXUsrCode'),
                'FTLastUpdBy' => $this->input->post('ohdTWXUsrCode'),
                'FTSessionID' => $this->input->post('ohdSesSessionID'),
                'tTypeVisit'    => 1
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTXthDocType' => 1,
                'FDXthDocDate' => (!empty($tTWXDocDate)) ? $tTWXDocDate : NULL,
                'FTDptCode' => $aDataDocument['ohdTWXDptCode'],
                'FTUsrCode' => $aDataDocument['ohdTWXUsrCode'],
                'FNXthDocPrint' => $aDataDocument['ocmTWXFrmInfoOthDocPrint'],
                'FTXthRmk' => $aDataDocument['otaTWXFrmInfoOthRmk'],
                'FTXthStaDoc' => $aDataDocument['ohdTWXStaDoc'],
                'FTXthStaApv' => !empty($aDataDocument['ohdTWXStaApv']) ? $aDataDocument['ohdTWXStaApv'] : NULL,
                'FNXthStaDocAct' => $tTWXStaDocAct,
                'FTXthRefInt' => $tRefin,
                'FDXthRefIntDate' => (!empty($aDataDocument['oetTWXRefIntDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTWXRefIntDocDate'])) : NULL,
                'FTXthWhFrm'    => $aDataDocument['oetTWXWahFrmCode'],
                'FTXthWhTo'     => $aDataDocument['oetTWXWahBookCode'],
                'FTXthRefExt'         => $aDataDocument['oetTWXRefDocExt'],
                'FDXthRefExtDate'     => (!empty($aDataDocument['oetTWXRefDocExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTWXRefDocExtDate'])) : NULL,
                'FNXthStaRef' => $aDataDocument['ocmTWXFrmInfoOthRef']
            );

            $aDataRef = array(
                'FTBchCode'     => $aDataDocument['oetTWXWahBrcCode'],
                'FTXthCtrName'  => $aDataDocument['oetTWXFrmCstName'],
                'FTXthRefTnfID' => $aDataDocument['oetTWXFrmCstCode'],
                'FTCarCode'     => $aDataDocument['oetTWXCrscarCode'],
                'FNXthShipAdd'  => $aDataDocument['oetTWXPanel_ADDSeq'],
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tTWXAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TCNTPdtTwxHD',                           
                    "tDocType"    => '3',                                          
                    "tBchCode"    => $aDataDocument['oetTWXWahBrcCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXthDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXthDocNo'] = $tTWXDocNo;
            }
            // Add Update Document HD
            $this->Bookingorder_model->FSxMTWXAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // Add Update Document HD Ref
            $this->Bookingorder_model->FSxMTWXAddUpdateHDRef($aDataRef, $aDataWhere, $aTableAddUpdate);
            // [Update] DocNo -> Temp
            $this->Bookingorder_model->FSxMTWXAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            
            // Move Doc DTTemp To DT
            $this->Bookingorder_model->FSaMTWXMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if($this->input->post('ocmTWXSelectBrowse') == '1'){
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSqHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Bookingorder_model->FSaMTWXUpdatePRBStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }elseif($this->input->post('ocmTWXSelectBrowse') == '0'){
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSoHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Bookingorder_model->FSaMTWXUpdatePRBStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }
            
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
                    'tCodeReturn' => $aDataWhere['FTXthDocNo'],
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

    // Function: Add Document 
    public function FSoCBKOEditEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tTWXDocNo = (isset($aDataDocument['oetTWXDocNo'])) ? $aDataDocument['oetTWXDocNo'] : '';
            $tTWXDocDate = $aDataDocument['oetTWXDocDate'] . " " . $aDataDocument['oetTWXDocTime'];
            $tTWXStaDocAct = (isset($aDataDocument['ocbTWXFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tTWXSessionID = $this->input->post('ohdSesSessionID');
            $nTWXSubmitWithImp = $aDataDocument['ohdTWXSubmitWithImp'];
            $tRefin = $this->input->post("oetTWXRefInAllName");
            $tRefinOld = $this->input->post("ohdTWXRefIntDoc");
            // Get Data Comp.
            $nLangEdit = $this->input->post("ohdTWXLangEdit");
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $tATWXReq = "";
            $tMethodReq = "GET";
            $aCompData = $this->mCompany->FSaMCMPList($tATWXReq, $tMethodReq, $aDataWhereComp);
            $aClearDTParams = [
                'FTXthDocNo'     => $tTWXDocNo,
                'FTXthDocKey'    => 'TCNTPdtTwxDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nTWXSubmitWithImp==1){
                $this->Bookingorder_model->FSxMTWXClearDataInDocTempForImp($aClearDTParams);
            }

//-----------------------------------------------------------------------------
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD' => 'TCNTPdtTwxHD',
                'tTableHDRef' => 'TCNTPdtTwxHDRef',
                'tTableDT' => 'TCNTPdtTwxDT',
                'tTableStaGen' => 11,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode' => $aDataDocument['oetTWXWahBrcCode'],
                'FTXthDocNo' => $tTWXDocNo,
                'FTOldBchCode' => $aDataDocument['ohdTWXBchCode'],
                'FTOldXphDocNo' => $tTWXDocNo,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->input->post('ohdTWXUsrCode'),
                'FTLastUpdBy' => $this->input->post('ohdTWXUsrCode'),
                'FTSessionID' => $this->input->post('ohdSesSessionID'),
                'tTypeVisit'    => 1
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTXthDocType' => 1,
                'FDXthDocDate' => (!empty($tTWXDocDate)) ? $tTWXDocDate : NULL,
                'FTDptCode' => $aDataDocument['ohdTWXDptCode'],
                'FTUsrCode' => $aDataDocument['ohdTWXUsrCode'],
                'FNXthDocPrint' => $aDataDocument['ocmTWXFrmInfoOthDocPrint'],
                'FTXthRmk' => $aDataDocument['otaTWXFrmInfoOthRmk'],
                'FTXthStaDoc' => $aDataDocument['ohdTWXStaDoc'],
                'FTXthStaApv' => !empty($aDataDocument['ohdTWXStaApv']) ? $aDataDocument['ohdTWXStaApv'] : NULL,
                'FNXthStaDocAct' => $tTWXStaDocAct,
                'FTXthRefInt' => $tRefin,
                'FDXthRefIntDate' => (!empty($aDataDocument['oetTWXRefIntDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTWXRefIntDocDate'])) : NULL,
                'FTXthWhFrm'    => $aDataDocument['oetTWXWahFrmCode'],
                'FTXthWhTo'     => $aDataDocument['oetTWXWahBookCode'],
                'FTXthRefExt'         => $aDataDocument['oetTWXRefDocExt'],
                'FDXthRefExtDate'     => (!empty($aDataDocument['oetTWXRefDocExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTWXRefDocExtDate'])) : NULL,
                'FNXthStaRef' => $aDataDocument['ocmTWXFrmInfoOthRef']
            );

            $aDataRef = array(
                'FTBchCode'     => $aDataDocument['oetTWXWahBrcCode'],
                'FTXthCtrName'  => $aDataDocument['oetTWXFrmCstName'],
                'FTXthRefTnfID' => $aDataDocument['oetTWXFrmCstCode'],
                'FTCarCode' => $aDataDocument['oetTWXCrscarCode'],
                'FNXthShipAdd'  => $aDataDocument['oetTWXPanel_ADDSeq'],
            );

            $this->db->trans_begin();

             // Check Auto GenCode Document
            //  if ($aDataDocument['oetTWXWahBrcCode'] != $aDataDocument['ohdTWXBchCode']) {
            //     $aStoreParam = array(
            //         "tTblName"    => 'TCNTPdtReqSplHD',                           
            //         "tDocType"    => '11',                                          
            //         "tBchCode"    => $aDataDocument['oetTWXWahBrcCode'],                                 
            //         "tShpCode"    => "",                               
            //         "tPosCode"    => "",                     
            //         "dDocDate"    => date("Y-m-d H:i:s")       
            //     );
                
            //     $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
            //     $aDataWhere['FTXthDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            // } else {
            //     $aDataWhere['FTXthDocNo'] = $tTWXDocNo;
            // }

            // Add Update Document HD
            $this->Bookingorder_model->FSxMTWXAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // Add Update Document HD Ref
            $this->Bookingorder_model->FSxMTWXAddUpdateHDRef($aDataRef, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Bookingorder_model->FSxMTWXAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->Bookingorder_model->FSaMTWXMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if($this->input->post('ocmTWXSelectBrowse') == '1' && $tRefin != $tRefinOld){
                $nStaRef = '0';
                $this->Bookingorder_model->FSaMTWXClearRefDoc($tRefinOld, $nStaRef);
                
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSqHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Bookingorder_model->FSaMTWXUpdatePRBStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }
            if($this->input->post('ocmTWXSelectBrowse') == '0' && $tRefin != $tRefinOld){
                $nStaRef = '0';
                $this->Bookingorder_model->FSaMTWXClearRefDoc($tRefinOld, $nStaRef);

                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSoHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Bookingorder_model->FSaMTWXUpdatePRBStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }
            
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
                    'tCodeReturn' => $aDataWhere['FTXthDocNo'],
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

    //หน้าจอแก้ไข
    public function FSvCBKOEditPage(){
        try {
            $ptDocumentNumber = $this->input->post('ptTWXDocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Bookingorder_model->FSnMTWXDelALLTmp($aWhereClearTemp);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TCNTPdtTwxDT',
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
            $aDataDocHD         = $this->Bookingorder_model->FSaMTWXGetDataDocHD($aDataWhere);

            // Move Data DT TO DTTemp
            $this->Bookingorder_model->FSxMTWXMoveDTToDTTemp($aDataWhere);

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
                    'aDataDocHD'        => $aDataDocHD
                );
                $tViewPageEdit           = $this->load->view('document/bookingorder/wBookingOrderPageAdd',$aDataConfigViewEdit,true);
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
    public function FSoCBKODeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $tRefInDocNo = $this->input->post('tTWXRefInCode');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->Bookingorder_model->FSaMTWXClearRefDoc($tRefInDocNo, $nStaRef);
            }
            
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tBchCode' => $tBchCode
            );
            
            $aResDelDoc = $this->Bookingorder_model->FSnMTWXDelDocument($aDataMaster);
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

    // Function: Cancel Status Document
    public function FSvCBKOCancelDocument() {
        try {
            $tTWXDocNo = $this->input->post('ptTWXDocNo');
            $tRefInDocNo = $this->input->post('ptRefInDocNo');
            
            $aDataUpdate = array(
                'tDocNo' => $tTWXDocNo,
            );
            $nStaRef = '0';
            $this->Bookingorder_model->FSaMTWXClearRefDoc($tRefInDocNo, $nStaRef);

            $aStaApv = $this->Bookingorder_model->FSaMTWXCancelDocument($aDataUpdate);
            $aReturnData = $aStaApv;
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }


    //อนุมัติเอกสาร
    public function FSoCBKOApproveEvent(){
        try{
            $tDocNo         = $this->input->post('tDocNo');
            $tBchCode       = $this->input->post('tBchCode');
            $tRefInDocNo    = $this->input->post('tRefInDocNo');

            if (!empty($tRefInDocNo)) {
            }

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXthDocNo'        => $tDocNo,
                'FTXthStaApv'       => 1,
                'FTXphUsrApv'       => $this->session->userdata('tSesUsername')
            );

            $this->Bookingorder_model->FSaMTWXApproveDocument($aDataUpdate);
            
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



    // ค้นหาข้อมูล ลูกค้าจากเอกสารอ้างอิง Wasin 24112021
    public function FSoCBKOFindCstDocRefInfo(){
        $nCheckRefBrowse    = $this->input->post('nCheckRefBrowse');
        $aDataDocRef        = [
            'FTXshDocNo'    => $this->input->post('tRefIntDocNo'),
            'FTBchCode'     => $this->input->post('tRefIntBchCode'),
            'FNLngID'       => $this->session->userdata("tLangEdit")
        ];
        if($nCheckRefBrowse == '0'){ 
            // ใบสั่งขาย
            $aDataCstInfo   = $this->Bookingorder_model->FSoMBKOFindCstDocRefInfoType0($aDataDocRef);
        }else if($nCheckRefBrowse == '1'){ 
            // ใบเสนอราคา
            $aDataCstInfo   = $this->Bookingorder_model->FSoMBKOFindCstDocRefInfoType1($aDataDocRef);
        }
        echo json_encode($aDataCstInfo);
    }



    

}



/* End of file Controllername.php */
