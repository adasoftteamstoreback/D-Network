<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerreceived_controller extends MX_Controller {

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/customerreceived/customerreceived_model');
        parent::__construct();

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }
    
    public $tRouteMenu  = 'docCRV/0/0';
    
    public function index($nCRVBrowseType, $tCRVBrowseOption){
        $aParams    = [
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
            'tCheckJump'    => $this->input->post('ptTypeJump'),
        ];
        $aDataConfigView    = array(
            'nCRVBrowseType'     => $nCRVBrowseType,
            'tCRVBrowseOption'   => $tCRVBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParams'           => $aParams ,
        );
        $this->load->view('document/customerreceived/wCustomerReceived', $aDataConfigView);
        unset($aParams);
        unset($aDataConfigView);
        unset($nCRVBrowseType,$tCRVBrowseOption);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCCRVFormSearchList() {
        $this->load->view('document/customerreceived/wCustomerReceivedFormSearchList');
    }

    // แสดงตารางในหน้า List
    public function FSoCCRVDataTable() {
        try {
            $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
            $nPage              = $this->input->post('nPageCurrent');
            $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);
            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            // Data Conditon Get Data Document

            $aDataCondition = array(
                'FNLngID' => $nLangEdit,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList   = $this->customerreceived_model->FSaMCRVGetDataTableList($aDataCondition);
            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList
            );
            $tCRVViewDataTableList = $this->load->view('document/customerreceived/wCustomerReceivedDataTable', $aConfigView, true);
            $aReturnData = array(
                'tCRVViewDataTableList' => $tCRVViewDataTableList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        unset($aAdvanceSearch,$nPage,$aAlwEvent,$nOptDecimalShow,$nLangEdit,$aDataCondition,$aDataList,$aConfigView);
        unset($tCRVViewDataTableList);
        echo json_encode($aReturnData);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    // public function FSoCCRVCallRefIntDoc(){
    //     $tDocType   = $this->input->post('tDocType');
    //     $tBCHCode   = $this->input->post('tBCHCode');
    //     $tBCHName   = $this->input->post('tBCHName');
    //     $aDataParam = array(
    //         'tBCHCode' => $tBCHCode,
    //         'tBCHName' => $tBCHName,
    //         'tDocType' => $tDocType,
    //     );
    //     $this->load->view('document/customerreceived/refintdocument/wCustomerReceivedRefDoc', $aDataParam);
    // }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    // public function FSoCCRVCallRefIntDocDataTable(){
    //     $nPage                  = $this->input->post('nCRVRefIntPageCurrent');
    //     $tCRVRefIntBchCode       = $this->input->post('tCRVRefIntBchCode');
    //     $tCRVRefIntDocNo         = $this->input->post('tCRVRefIntDocNo');
    //     $tCRVRefIntDocDateFrm    = $this->input->post('tCRVRefIntDocDateFrm');
    //     $tCRVRefIntDocDateTo     = $this->input->post('tCRVRefIntDocDateTo');
    //     $tCRVRefIntStaDoc        = $this->input->post('tCRVRefIntStaDoc');
    //     $tCRVDocType             = $this->input->post('tCRVDocType');
    //     $tCRVSplCode             = $this->input->post('tCRVSplCode');
    //     // Page Current 
    //     if ($nPage == '' || $nPage == null) {
    //         $nPage = 1;
    //     } else {
    //         $nPage = $this->input->post('nCRVRefIntPageCurrent');
    //     }
    //     // Lang ภาษา
    //     $nLangEdit = $this->session->userdata("tLangEdit");
    //     $aDataParamFilter = array(
    //         'tCRVRefIntBchCode'      => $tCRVRefIntBchCode,
    //         'tCRVRefIntDocNo'        => $tCRVRefIntDocNo,
    //         'tCRVRefIntDocDateFrm'   => $tCRVRefIntDocDateFrm,
    //         'tCRVRefIntDocDateTo'    => $tCRVRefIntDocDateTo,
    //         'tCRVRefIntStaDoc'       => $tCRVRefIntStaDoc,
    //         'tCRVSplCode'            => $tCRVSplCode
    //     );
    //     // Data Conditon Get Data Document
    //     $aDataCondition = array(
    //         'FNLngID'   => $nLangEdit,
    //         'nPage'     => $nPage,
    //         'nRow'      => 10,
    //         'aAdvanceSearch' => $aDataParamFilter
    //     );
    //     if ($tCRVDocType == 1) {
    //         $aDataParam = $this->customerreceived_model->FSoMCRVCallRefPOIntDocDataTable($aDataCondition);
    //     }else{
    //         $aDataParam = $this->customerreceived_model->FSoMCRVCallRefABBIntDocDataTable($aDataCondition);
    //     }
    //     $aConfigView = array(
    //         'nPage'         => $nPage,
    //         'aDataList'     => $aDataParam,
    //         'tCRVDocType'    => $tCRVDocType
    //     );
    //     $this->load->view('document/customerreceived/refintdocument/wCustomerReceivedRefDocDataTable', $aConfigView);
    // }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    // public function FSoCCRVCallRefIntDocDetailDataTable(){
    //     $nLangEdit          = $this->session->userdata("tLangEdit");
    //     $tBchCode           = $this->input->post('ptBchCode');
    //     $tDocNo             = $this->input->post('ptDocNo');
    //     $tDocType           = $this->input->post('ptdoctype');
    //     $nOptDecimalShow    = get_cookie('tOptDecimalShow');
    //     $aDataCondition     = array(
    //         'FNLngID'   => $nLangEdit,
    //         'tBchCode'  => $tBchCode,
    //         'tDocNo'    => $tDocNo
    //     );
    //     if ($tDocType == 1) {
    //         $aDataParam = $this->customerreceived_model->FSoMCRVCallRefIntDocDTDataTable($aDataCondition);
    //     }else{
    //         $aDataParam = $this->customerreceived_model->FSoMCRVCallRefIntDocABBDTDataTable($aDataCondition);
    //     }
    //     $aConfigView    = array(
    //         'aDataList'         => $aDataParam,
    //         'nOptDecimalShow'   => $nOptDecimalShow
    //     );
    //     $this->load->view('document/customerreceived/refintdocument/wCustomerReceivedRefDocDetailDataTable', $aConfigView);
    // }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    // public function FSoCCRVCallRefIntDocInsertDTToTemp(){
    //     $tCRVDocNo           =  $this->input->post('tCRVDocNo');
    //     $tCRVFrmBchCode      =  $this->input->post('tCRVFrmBchCode');
    //     $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
    //     $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
    //     $aSeqNo             =  $this->input->post('aSeqNo');
    //     $tDoctype           =  $this->input->post('tDoctype');
    //     $tCRVOptionAddPdt    =  $this->input->post('tCRVOptionAddPdt');
    //     $aDataParam         = array(
    //         'tCRVDocNo'          => $tCRVDocNo,
    //         'tCRVFrmBchCode'     => $tCRVFrmBchCode,
    //         'tRefIntDocNo'      => $tRefIntDocNo,
    //         'tRefIntBchCode'    => $tRefIntBchCode,
    //         'aSeqNo'            => $aSeqNo,
    //         'tDocKey'           => 'TSVTBookHD',
    //         'tCRVOptionAddPdt'   => $tCRVOptionAddPdt,
    //         'tSessionID'        => $this->session->userdata('tSesSessionID'),
    //     );
    //     if ($tDoctype == 1) {
    //         $tDocType       = 'PO';
    //         $aDataResult    = $this->customerreceived_model->FSoMCRVCallRefIntDocInsertDTToTemp($aDataParam, $tDocType);
    //     }else{
    //         $tDocType       = 'ABB';
    //         $aDataResult    = $this->customerreceived_model->FSoMCRVCallRefIntABBDocInsertDTToTemp($aDataParam, $tDocType);
    //     }
    //     return  $aDataResult;
    // }

    // public function FSoCCRVClearTempWhenChangeData(){
    //     try {
    //         $tCRVDocNo           = $this->input->post('tCRVDocNo');
    //         $aWhereClearTemp    = [
    //             'FTXthDocNo'    => $tCRVDocNo,
    //             'FTXthDocKey'   => 'TSVTBookHD',
    //             'FTSessionID'   => $this->session->userdata('tSesSessionID')
    //         ];
    //         $this->customerreceived_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
    //         $this->customerreceived_model->FSxMCRVClearDataInDocTemp($aWhereClearTemp);
    //         $aReturnData = array(
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'Success'
    //         );
    //     } catch (\Throwable $e) {
    //         $aReturnData = array(
    //             'nStaEvent' => '999',
    //             'tStaMessg' => 'Success'    
    //         );
    //     }
    //     return  $aReturnData;
    // }

    // เรียกหน้าเพิ่มข้อมูล
    // public function FSoCCRVPageAdd() {
    //     try {
    //         // Clear Data Product IN Doc Temp
    //         $aWhereClearTemp    = [
    //             'FTXthDocNo'    => '',
    //             'FTXthDocKey'   => 'TSVTBookHD',
    //             'FTSessionID'   => $this->session->userdata('tSesSessionID')
    //         ];

    //         $this->customerreceived_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
    //         $this->customerreceived_model->FSxMCRVClearDataInDocTemp($aWhereClearTemp);
    //         $nOptDecimalShow    = get_cookie('tOptDecimalShow');
    //         $nOptDocSave        = get_cookie('tOptDocSave');
    //         $nOptScanSku        = get_cookie('tOptScanSku');

    //         //ถ้าเป็นแบบแฟรนไซด์
    //         if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){
    //             $aSPLConfig     = $this->customerreceived_model->FSxMCRVFindSPLByConfig();
    //         }else{
    //             $aSPLConfig     = '';
    //         }

    //         $aDataConfigViewAdd = array(
    //             'nOptDecimalShow'   => $nOptDecimalShow,
    //             'nOptDocSave'       => $nOptDocSave,
    //             'nOptScanSku'       => $nOptScanSku,
    //             'aSPLConfig'        => $aSPLConfig,
    //             'aDataDocHD'        => array('rtCode' => '800'),
    //         );
    //         $tCRVViewPageAdd = $this->load->view('document/customerreceived/wCustomerReceivedPageAdd', $aDataConfigViewAdd, true);
    //         $aReturnData    = array(
    //             'tCRVViewPageAdd'    => $tCRVViewPageAdd,
    //             'nStaEvent'         => '1',
    //             'tStaMessg'         => 'Success'
    //         );
    //     } catch (Exception $Error) {
    //         $aReturnData    = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // แสดงผลลัพธ์การค้นหาขั้นสูง
    public function FSoCCRVPdtAdvTblLoadData() {
        try {
            $bStaSession    =   $this->session->userdata('bSesLogIn');
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData = array(
                    'checksession' => 'expire'
                );
                echo json_encode($aReturnData);
            }
            $tCRVBchCode         = $this->input->post('ptCRVBchCode');
            $tCRVDocNo           = $this->input->post('ptCRVDocNo');
            // $tCRVStaApv          = $this->input->post('ptCRVStaApv');
            // $tCRVStaDoc          = $this->input->post('ptCRVStaDoc');
            // $nCRVPageCurrent     = $this->input->post('pnCRVPageCurrent');
            $tSearchPdtAdvTable  = $this->input->post('ptSearchPdtAdvTable');
            // $tCRVPdtCode         = $this->input->post('ptCRVPdtCode');
            // $tCRVPunCode         = $this->input->post('ptCRVPunCode');
            //Get Option Show Decimal
            $nOptDecimalShow     = get_cookie('tOptDecimalShow');

            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'tBchCode'              => $tCRVBchCode,
                'tDocNo'                => $tCRVDocNo
            );
            $aDataDocDTTemp     = $this->customerreceived_model->FSaMCRVGetDocDTTempListPage($aDataWhere);
            $aDataView          = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );
            $tCRVPdtAdvTableHtml = $this->load->view('document/customerreceived/wCustomerReceivedPdtAdvTableData', $aDataView, true);
            $aReturnData    = array(
                'tCRVPdtAdvTableHtml'    => $tCRVPdtAdvTableHtml,
                'nStaEvent'             => '1',
                'tStaMessg'             => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Add สินค้า ลง Document DT Temp
    // public function FSoCCRVAddPdtIntoDocDTTemp() {
    //     try {
    //         $tCRVDocNo           = $this->input->post('tCRVDocNo');
    //         $tCRVBchCode         = $this->input->post('tSelectBCH');
    //         $tCRVOptionAddPdt    = $this->input->post('tCRVOptionAddPdt');
    //         $tCRVPdtData         = $this->input->post('tCRVPdtData');
    //         $aCRVPdtData         = json_decode($tCRVPdtData);
    //         $nVatRate           = $this->input->post('nVatRate');
    //         $nVatCode           = $this->input->post('nVatCode');
    //         $this->db->trans_begin();
    //         // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
    //         for ($nI = 0; $nI < FCNnHSizeOf($aCRVPdtData); $nI++) {
    //             $tCRVPdtCode = $aCRVPdtData[$nI]->pnPdtCode;
    //             $tCRVBarCode = $aCRVPdtData[$nI]->ptBarCode;
    //             $tCRVPunCode = $aCRVPdtData[$nI]->ptPunCode;
    //             $cCRVPrice       = $aCRVPdtData[$nI]->packData->Price;
    //             $aDataPdtParams = array(
    //                 'tDocNo'            => $tCRVDocNo,
    //                 'tBchCode'          => $tCRVBchCode,
    //                 'tPdtCode'          => $tCRVPdtCode,
    //                 'tBarCode'          => $tCRVBarCode,
    //                 'tPunCode'          => $tCRVPunCode,
    //                 'cPrice'            => str_replace(",","",$cCRVPrice),
    //                 'nMaxSeqNo'         => $this->input->post('tSeqNo'),
    //                 'nLngID'            => $this->input->post("ohdCRVLangEdit"),
    //                 'tSessionID'        => $this->input->post('ohdSesSessionID'),
    //                 'tDocKey'           => 'TSVTBookDT',
    //                 'tCRVOptionAddPdt'   => $tCRVOptionAddPdt,
    //                 'tCRVUsrCode'        => $this->input->post('ohdCRVUsrCode'),
    //                 'nVatRate'          => $nVatRate,
    //                 'nVatCode'          => $nVatCode
    //             );
    //             // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
    //             $aDataPdtMaster = $this->customerreceived_model->FSaMCRVGetDataPdt($aDataPdtParams);
    //             // นำรายการสินค้าเข้า DT Temp
    //             $this->customerreceived_model->FSaMCRVInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
    //         }
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent' => '500',
    //                 'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //                 $aReturnData = array(
    //                     'nStaEvent' => '1',
    //                     'tStaMessg' => 'Success Add Product Into Document DT Temp.'
    //                 );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // Function: Remove Product In Documeny Temp
    // public function FSvCCRVRemovePdtInDTTmp() {
    //     try {
    //         $this->db->trans_begin();
    //         $aDataWhere = array(
    //             'tCRVDocNo' => $this->input->post('tDocNo'),
    //             'tBchCode' => $this->input->post('tBchCode'),
    //             'tPdtCode' => $this->input->post('tPdtCode'),
    //             'nSeqNo'   => $this->input->post('nSeqNo'),
    //             'tDocKey'  => 'TSVTBookDT',
    //             'tSessionID' => $this->session->userdata('tSesSessionID'),
    //         );
    //         $this->customerreceived_model->FSnMCRVDelPdtInDTTmp($aDataWhere);
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent' => '500',
    //                 'tStaMessg' => 'Cannot Delete Item.',
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $aReturnData = array(
    //                 'nStaEvent' => '1',
    //                 'tStaMessg' => 'Success Delete Product'
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    //Remove Product In Documeny Temp Multiple
    // public function FSvCCRVRemovePdtInDTTmpMulti() {
    //     try {
    //         $this->db->trans_begin();
    //         $aDataWhere = array(
    //             'tCRVDocNo' => $this->input->post('tDocNo'),
    //             'tBchCode' => $this->input->post('tBchCode'),
    //             'tPdtCode' => str_replace(",","','",$this->input->post('tPdtCode')),
    //             'nSeqNo'   => str_replace(",","','",$this->input->post('nSeqNo')),
    //             'tDocKey'  => 'TSVTBookDT',
    //             'tSessionID' => $this->session->userdata('tSesSessionID'),
    //         );

    //         $this->customerreceived_model->FSnMCRVDelMultiPdtInDTTmp($aDataWhere);
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent' => '500',
    //                 'tStaMessg' => 'Cannot Delete Item.',
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $aReturnData = array(
    //                 'nStaEvent' => '1',
    //                 'tStaMessg' => 'Success Delete Product'
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // Function: Edit Inline สินค้า ลง Document DT Temp
    // public function FSoCCRVEditPdtIntoDocDTTemp() {
    //     try {
    //         $tCRVBchCode         = $this->input->post('tCRVBchCode');
    //         $tCRVDocNo           = $this->input->post('tCRVDocNo');
    //         $nCRVSeqNo           = $this->input->post('nCRVSeqNo');
    //         $tCRVSessionID       = $this->session->userdata('tSesSessionID');
    //         $aDataWhere     = array(
    //             'tCRVBchCode'    => $tCRVBchCode,
    //             'tCRVDocNo'      => $tCRVDocNo,
    //             'nCRVSeqNo'      => $nCRVSeqNo,
    //             'tCRVSessionID'  => $tCRVSessionID,
    //             'tDocKey'       => 'TSVTBookDT',
    //         );
    //         $aDataUpdateDT  = array(
    //             'FCXtdQty'      => $this->input->post('nQty'),
    //             'FTXtdPdtName'  => $this->input->post('FTXtdPdtName'),
    //         );
    //         $this->db->trans_begin();
    //         $this->customerreceived_model->FSaMCRVUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
    //         if($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent' => '500',
    //                 'tStaMessg' => "Error Update Inline Into Document DT Temp."
    //             );
    //         }else{
    //             $this->db->trans_commit();
    //             $aReturnData = array(
    //                 'nStaEvent' => '1',
    //                 'tStaMessg' => "Update Inline Into Document DT Temp."
    //             );
    //         }
            
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // Function: Check Product Have In Temp For Document DT
    // public function FSoCCRVChkHavePdtForDocDTTemp() {
    //     try {
    //         $tCRVDocNo       = $this->input->post("ptCRVDocNo");
    //         $tCRVSessionID   = $this->input->post('tCRVSesSessionID');
    //         $aDataWhere     = array(
    //             'FTXthDocNo'    => $tCRVDocNo,
    //             'FTXthDocKey'   => 'TSVTBookDT',
    //             'FTSessionID'   => $tCRVSessionID
    //         );
    //         $nCountPdtInDocDTTemp   = $this->customerreceived_model->FSnMCRVChkPdtInDocDTTemp($aDataWhere);
    //         if ($nCountPdtInDocDTTemp > 0) {
    //             $aReturnData    = array(
    //                 'nStaReturn'    => '1',
    //                 'tStaMessg'     => 'Found Data In Doc DT.'
    //             );
    //         } else {
    //             $aReturnData    = array(
    //                 'nStaReturn'    => '800',
    //                 'tStaMessg'     => language('document/customerreceived/customerreceived', 'tCRVPleaseSeletedPDTIntoTable')
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData    = array(
    //             'nStaReturn'    => '500',
    //             'tStaMessg'     => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    //เพิ่มข้อมูล
    // public function FSoCCRVAddEventDoc() {
    //     try {
    //         $aDataDocument      = $this->input->post();
    //         $tCRVAutoGenCode     = (isset($aDataDocument['ocbCRVStaAutoGenCode'])) ? 1 : 0;
    //         $tCRVDocNo           = (isset($aDataDocument['oetCRVDocNo'])) ? $aDataDocument['oetCRVDocNo'] : '';
    //         $tCRVDocDate         = $aDataDocument['oetCRVDocDate'] . " " . $aDataDocument['oetCRVDocTime'];
    //         $tCRVStaDocAct       = (isset($aDataDocument['ocbCRVFrmInfoOthStaDocAct'])) ? 1 : 0;
    //         $tCRVVATInOrEx       = $aDataDocument['ocmCRVFrmSplInfoVatInOrEx'];
    //         $nCRVSubmitWithImp   = $aDataDocument['ohdCRVSubmitWithImp'];
    //         $nDocType           = $aDataDocument['ohdCRVDocType'];
    //         $aClearDTParams = [
    //             'FTXthDocNo'    => $tCRVDocNo,
    //             'FTXthDocKey'   => 'TSVTBookDT',
    //             'FTSessionID'   => $this->input->post('ohdSesSessionID'),
    //         ];
    //         if($nCRVSubmitWithImp==1){
    //             $this->customerreceived_model->FSxMCRVClearDataInDocTempForImp($aClearDTParams);
    //         }
    //         // Check Auto GenCode Document
    //         if ($tCRVAutoGenCode == '1') {
    //             $aStoreParam = array(
    //                 "tTblName"    => 'TSVTBookHD',                           
    //                 "tDocType"    => '11',                                          
    //                 "tBchCode"    => $aDataDocument['oetCRVFrmBchCode'],                                 
    //                 "tShpCode"    => "",                               
    //                 "tPosCode"    => "",                     
    //                 "dDocDate"    => date("Y-m-d H:i:s")       
    //             );
    //             $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
    //             $tCRVDocNo   = $aAutogen[0]["FTXxhDocNo"];
    //         } else {
    //             $tCRVDocNo = $tCRVDocNo;
    //         }
    //         // Array Data Table Document
    //         $aTableAddUpdate    = array(
    //             'tTableHD'      => 'TSVTBookHD',
    //             'tTableHDSpl'   => 'TSVTBookHDSpl',
    //             'tTableDT'      => 'TSVTBookDT',
    //             'tTableStaGen'  => 11,
    //             'tTableRefCRV'   => 'TSVTBookHDDocRef',
    //             'tTableRefPO'   => 'TAPTPoHDDocRef'
    //         );
    //         // Array Data Where Insert
    //         $aDataWhere = array(
    //             'FTAgnCode'         => $aDataDocument['oetCRVAgnCode'],
    //             'FTBchCode'         => $aDataDocument['oetCRVFrmBchCode'],
    //             'FTXphDocNo'        => $tCRVDocNo,
    //             'FDLastUpdOn'       => date('Y-m-d H:i:s'),
    //             'FDCreateOn'        => date('Y-m-d H:i:s'),
    //             'FTCreateBy'        => $this->input->post('ohdCRVUsrCode'),
    //             'FTLastUpdBy'       => $this->input->post('ohdCRVUsrCode'),
    //             'FTSessionID'       => $this->input->post('ohdSesSessionID'),
    //             'FTXthVATInOrEx'    => $tCRVVATInOrEx,
    //             'FTXphUsrApv'       => ''
    //         );
    //         // Array Data HD Master
    //         $aDataMaster    = array(
    //             'FNXphDocType'      => 11,
    //             'FDXphDocDate'      => (!empty($tCRVDocDate)) ? $tCRVDocDate : NULL,
    //             'FTXphCshOrCrd'     => $aDataDocument['ocmCRVTypePayment'],
    //             'FTXphVATInOrEx'    => $tCRVVATInOrEx,
    //             'FTDptCode'         => $aDataDocument['ohdCRVDptCode'],
    //             'FTWahCode'         => $aDataDocument['oetCRVFrmWahCode'],
    //             'FTUsrCode'         => $aDataDocument['ohdCRVUsrCode'],
    //             'FTSplCode'         => $aDataDocument['oetCRVFrmSplCode'],
    //             'FNXphDocPrint'     => $aDataDocument['ocmCRVFrmInfoOthDocPrint'],
    //             'FTXphRmk'          => $aDataDocument['otaCRVFrmInfoOthRmk'],
    //             'FTXphStaDoc'       => $aDataDocument['ohdCRVStaDoc'],
    //             'FTXphStaApv'       => !empty($aDataDocument['ohdCRVStaApv']) ? $aDataDocument['ohdCRVStaApv'] : NULL,
    //             'FTXphStaDelMQ'     => $aDataDocument['ohdCRVStaDelMQ'],
    //             'FTXphStaPrcStk'    => $aDataDocument['ohdCRVStaPrcStk'],
    //             'FNXphStaDocAct'    => $tCRVStaDocAct,
    //             'FNXphStaRef'       => $aDataDocument['ocmCRVFrmInfoOthRef']
    //         );
    //         // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);
    //         $aDataSpl   = array(
    //             'FNXphCrTerm'   => intval($aDataDocument['oetCRVFrmSplInfoCrTerm']),
    //             'FTXphCtrName'  => $aDataDocument['oetCRVFrmSplInfoCtrName'],
    //             'FDXphTnfDate'  => (!empty($aDataDocument['oetCRVFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCRVFrmSplInfoTnfDate'])) : NULL,
    //             'FTXphRefTnfID' => $aDataDocument['oetCRVFrmSplInfoRefTnfID'],
    //             'FTXphRefVehID' => $aDataDocument['oetCRVFrmSplInfoRefVehID'],
    //         );
    //         $this->db->trans_begin();
    //         // // Add Update Document HD
    //         $this->customerreceived_model->FSxMCRVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
    //         // // Add Update Document HD Spl
    //         $this->customerreceived_model->FSxMCRVAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);
    //         // [Update] DocNo -> Temp
    //         $this->customerreceived_model->FSxMCRVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
    //         // // Move Doc DTTemp To DT
    //         $this->customerreceived_model->FSaMCRVMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
    //         $this->customerreceived_model->FSxMCRVMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
    //         // Check Status Transection DB
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData    = array(
    //                 'nStaEvent'     => '900',
    //                 'tStaMessg'     => "Error Unsucess Add Document.",
    //                 //เพิ่มใหม่
    //                 'tLogType'      => 'ERROR',
    //                 'tEventName'    => 'บันทึกใบรับของ',
    //                 'nLogCode'      => '900',
    //                 'nLogLevel'     => '4'
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $aReturnData    = array(
    //                 'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
    //                 'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
    //                 'nStaReturn'    => '1',
    //                 'tStaMessg'     => 'Success Add Document.',
    //                 //เพิ่มใหม่
    //                 'tLogType'      => 'INFO',
    //                 'tEventName'    => 'บันทึกใบรับของ',
    //                 'nLogCode'      => '001',
    //                 'nLogLevel'     => '0'
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaReturn'    => '500',
    //             'tStaMessg'     => $Error->getMessage(),
    //             'tLogType'      => 'ERROR',
    //             'tEventName'    => 'บันทึกใบรับของ',
    //             'nLogCode'      => '900',
    //             'nLogLevel'     => '4'
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    //แก้ไขเอกสาร
    // public function FSoCCRVEditEventDoc() {
    //     try {
    //         $aDataDocument      = $this->input->post();
    //         $tCRVDocNo           = (isset($aDataDocument['oetCRVDocNo'])) ? $aDataDocument['oetCRVDocNo'] : '';
    //         $tCRVDocDate         = $aDataDocument['oetCRVDocDate'] . " " . $aDataDocument['oetCRVDocTime'];
    //         $tCRVStaDocAct       = (isset($aDataDocument['ocbCRVFrmInfoOthStaDocAct'])) ? 1 : 0;
    //         $tCRVVATInOrEx       = $aDataDocument['ocmCRVFrmSplInfoVatInOrEx'];
    //         $nCRVSubmitWithImp   = $aDataDocument['ohdCRVSubmitWithImp'];
    //         $nDocType           = $aDataDocument['ohdCRVDocType'];
    //         // Get Data Comp.
    //         $aClearDTParams = [
    //             'FTXthDocNo'     => $tCRVDocNo,
    //             'FTXthDocKey'    => 'TSVTBookDT',
    //             'FTSessionID'    => $this->input->post('ohdSesSessionID'),
    //         ];
    //         if($nCRVSubmitWithImp==1){
    //             $this->customerreceived_model->FSxMCRVClearDataInDocTempForImp($aClearDTParams);
    //         }
    //         if (!empty($aDataDocument['oetCRVRefDocIntName'])) {
    //             $tRefInDocNo    = $aDataDocument['oetCRVRefDocIntName'];
    //             $nStaRef        = '2';
    //             $this->customerreceived_model->FSaMCRVUpdatePOStaRef($tRefInDocNo, $nStaRef);
    //         }
    //         // Array Data Table Document
    //         $aTableAddUpdate = array(
    //             'tTableHD'      => 'TSVTBookHD',
    //             'tTableHDSpl'   => 'TSVTBookHDSpl',
    //             'tTableDT'      => 'TSVTBookDT',
    //             'tTableStaGen'  => 11,
    //             'tTableRefCRV'   => 'TSVTBookHDDocRef',
    //             'tTableRefPO'   => 'TAPTPoHDDocRef'
    //         );
    //         // Array Data Where Insert
    //         $aDataWhere = array(
    //             'FTAgnCode'         => $aDataDocument['oetCRVAgnCode'],
    //             'FTBchCode'         => $aDataDocument['oetCRVFrmBchCode'],
    //             'FTXphDocNo'        => $tCRVDocNo,
    //             'FDLastUpdOn'       => date('Y-m-d H:i:s'),
    //             'FDCreateOn'        => date('Y-m-d H:i:s'),
    //             'FTCreateBy'        => $this->input->post('ohdCRVUsrCode'),
    //             'FTLastUpdBy'       => $this->input->post('ohdCRVUsrCode'),
    //             'FTSessionID'       => $this->input->post('ohdSesSessionID'),
    //             'FTXthVATInOrEx'    => $tCRVVATInOrEx,
    //             'FTXphUsrApv'       => ''
    //         );
    //         // Array Data HD Master
    //         $aDataMaster = array(
    //             'FNXphDocType'      => 11,
    //             'FDXphDocDate'      => (!empty($tCRVDocDate)) ? $tCRVDocDate : NULL,
    //             'FTXphCshOrCrd'     => $aDataDocument['ocmCRVTypePayment'],
    //             'FTXphVATInOrEx'    => $tCRVVATInOrEx,
    //             'FTDptCode'         => $aDataDocument['ohdCRVDptCode'],
    //             'FTWahCode'         => $aDataDocument['oetCRVFrmWahCode'],
    //             'FTUsrCode'         => $aDataDocument['ohdCRVUsrCode'],
    //             'FTSplCode'         => $aDataDocument['oetCRVFrmSplCode'],
    //             'FNXphDocPrint'     => $aDataDocument['ocmCRVFrmInfoOthDocPrint'],
    //             'FTXphRmk'          => $aDataDocument['otaCRVFrmInfoOthRmk'],
    //             'FTXphStaDoc'       => $aDataDocument['ohdCRVStaDoc'],
    //             'FTXphStaApv'       => !empty($aDataDocument['ohdCRVStaApv']) ? $aDataDocument['ohdCRVStaApv'] : NULL,
    //             'FTXphStaDelMQ'     => $aDataDocument['ohdCRVStaDelMQ'],
    //             'FTXphStaPrcStk'    => $aDataDocument['ohdCRVStaPrcStk'],
    //             'FNXphStaDocAct'    => $tCRVStaDocAct,
    //             'FNXphStaRef'       => $aDataDocument['ocmCRVFrmInfoOthRef']
    //         );
    //         // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);
    //         $aDataSpl = array(
    //             'FNXphCrTerm'   => intval($aDataDocument['oetCRVFrmSplInfoCrTerm']),
    //             'FTXphCtrName'  => $aDataDocument['oetCRVFrmSplInfoCtrName'],
    //             'FDXphTnfDate'  => (!empty($aDataDocument['oetCRVFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCRVFrmSplInfoTnfDate'])) : NULL,
    //             'FTXphRefTnfID' => $aDataDocument['oetCRVFrmSplInfoRefTnfID'],
    //             'FTXphRefVehID' => $aDataDocument['oetCRVFrmSplInfoRefVehID'],
    //         );
    //         $this->db->trans_begin();
    //         // Add Update Document HD
    //         $this->customerreceived_model->FSxMCRVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
    //         // Add Update Document HD Spl
    //         $this->customerreceived_model->FSxMCRVAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);
    //         // [Update] DocNo -> Temp
    //         $this->customerreceived_model->FSxMCRVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
    //         // Move Doc DTTemp To DT
    //         $this->customerreceived_model->FSaMCRVMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
    //         // [Move] Doc TSVTCRVDocHDRefTmp To TARTDoHDDocRef
    //         $this->customerreceived_model->FSxMCRVMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
    //         // Check Status Transection DB
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent'     => '900',
    //                 'tStaMessg'     => "Error Unsucess Edit Document.",
    //                 //เพิ่มใหม่
    //                 'tLogType'      => 'ERROR',
    //                 'tEventName'    => 'บันทึกใบรับของ',
    //                 'nLogCode'      => '900',
    //                 'nLogLevel'     => '4'
    //             );
    //         } else {
    //             $this->db->trans_commit();
    //             $aReturnData = array(
    //                 'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
    //                 'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
    //                 'nStaReturn'    => '1',
    //                 'tStaMessg'     => 'Success Edit Document.',
    //                 'tLogType'      => 'INFO',
    //                 'tEventName'    => 'แก้ไขและบันทึกใบรับของ',
    //                 'nLogCode'      => '001',
    //                 'nLogLevel'     => '0'
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaReturn'    => '500',
    //             'tStaMessg'     => $Error->getMessage(),
    //             'tLogType'      => 'ERROR',
    //             'tEventName'    => 'แก้ไขและบันทึกใบรับของ',
    //             'nLogCode'      => '900',
    //             'nLogLevel'     => '4'
    //         );
    //     }
    //     $nStaApvOrSave = $aDataDocument['ohdCRVApvOrSave'];
    //     echo json_encode($aReturnData);
    // }

    //หน้าจอแก้ไข
    public function FSvCCRVEditPage(){
        try {
            $ptBchCode          = $this->input->post('ptCRVBchCode');
            $ptDocumentNumber   = $this->input->post('ptCRVDocNo');
            // Clear Data In Doc DT Temp
            // $aWhereClearTemp    = [
            //     'FTBchCode'     => $ptBchCode,
            //     'FTXthDocNo'    => $ptDocumentNumber,
            //     'FTXthDocKey'   => 'TSVTBookHD',
            //     'FTSessionID'   => $this->session->userdata('tSesSessionID')
            // ];
            // $this->customerreceived_model->FSnMCRVDelALLTmp($aWhereClearTemp);
            // $this->customerreceived_model->FSxMCRVClearDataInDocTemp($aWhereClearTemp);
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'tBchCode'  => $ptBchCode,
                'tDocNo'    => $ptDocumentNumber,
                'nLngID'    => $nLangEdit
            );
            // Get Autentication Route
            $aAlwEvent         = FCNaHCheckAlwFunc($this->tRouteMenu); // Controle Event
            // $vBtnSave          = FCNaHBtnSaveActiveHTML($this->tRouteMenu); // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            $nOptDecimalShow   = get_cookie('tOptDecimalShow');
            $nOptDecimalSave   = get_cookie('tOptDecimalSave');
            $nOptDocSave       = get_cookie('tOptDocSave');
            $this->db->trans_begin();
            // Get Data Document HD
            $aDataDocHD = $this->customerreceived_model->FSaMCRVGetDataDocHD($aDataWhere);
            // Move Data DT TO DTTemp
            // $this->customerreceived_model->FSxMCRVMoveDTToDTTemp($aDataWhere);
            // Move Data HDDocRef TO HDRefTemp
            // $this->customerreceived_model->FSxMCRVMoveHDRefToHDRefTemp($aDataWhere);
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
                    // 'vBtnSave'          => $vBtnSave,
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'nOptDecimalSave'   => $nOptDecimalSave,
                    'nOptDocSave'       => $nOptDocSave,
                    // 'aRateDefault'      => '',
                    'aDataDocHD'        => $aDataDocHD
                );
                $tViewPageEdit           = $this->load->view('document/customerreceived/wCustomerReceivedPageAdd',$aDataConfigViewEdit,true);
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

    //ลบเอกสาร
    // public function FSoCCRVDeleteEventDoc() {
    //     try {
    //         $tDataDocNo = $this->input->post('tDataDocNo');
    //         $tBchCode = $this->input->post('tBchCode');
    //         $tRefInDocNo = $this->input->post('tCRVRefInCode');
    //         if (!empty($tRefInDocNo)) {
    //             $nStaRef = '0';
    //             $this->customerreceived_model->FSaMCRVUpdatePOStaRef($tRefInDocNo, $nStaRef);
    //         }
    //         $aDataMaster = array(
    //             'tDataDocNo' => $tDataDocNo,
    //             'tBchCode' => $tBchCode
    //         );
    //         $aResDelDoc = $this->customerreceived_model->FSnMCRVDelDocument($aDataMaster);
    //         if ($aResDelDoc['rtCode'] == '1') {
    //             $aDataStaReturn = array(
    //                 'nStaEvent' => '1',
    //                 'tStaMessg' => 'Success'
    //             );
    //         } else {
    //             $aDataStaReturn = array(
    //                 'nStaEvent' => $aResDelDoc['rtCode'],
    //                 'tStaMessg' => $aResDelDoc['rtDesc']
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aDataStaReturn = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aDataStaReturn);
    // }

    //ยกเลิกเอกสาร
    // public function FSvCCRVCancelDocument() {
    //     try {
    //         $tCRVDocNo       = $this->input->post('ptCRVDocNo');
    //         $tRefInDocNo    = $this->input->post('ptRefInDocNo');
    //         $tStaApv        = $this->input->post('ptStaApv');
    //         $tBchCode       = $this->input->post('ptBchCode');

    //         if (!empty($tRefInDocNo)) {
    //             $nStaRef = '0';
    //             $this->customerreceived_model->FSaMCRVUpdatePOStaRef($tRefInDocNo, $nStaRef);
    //         }
            
    //         $aDataUpdate = array(
    //             'tDocNo' => $tCRVDocNo,
    //         );
    //         $aResult        = $this->customerreceived_model->FSaMCRVCancelDocument($aDataUpdate);
    //         $aReturnData    = $aResult;

    //         if($tStaApv == 1){
    //             //ถ้าอนุมัติเเล้ว แล้วกดยกเลิกต้องวิ่ง MQ อีกรอบ ให้เคลียร์ Stock
    //             $aMQParams = [
    //                 "queueName" => "AP_QDocApprove",
    //                 "params"    => [
    //                     'ptFunction'    => 'TSVTBookHD',
    //                     'ptSource'      => 'AdaStoreBack',
    //                     'ptDest'        => 'MQReceivePrc',
    //                     'ptFilter'      => '',
    //                     'ptData'        => json_encode([
    //                         "ptBchCode"     => $tBchCode,
    //                         "ptDocNo"       => $tCRVDocNo,
    //                         "ptDocType"     => 11,
    //                         "ptUser"        => $this->session->userdata("tSesUsername"),
    //                     ])
    //                 ]
    //             ];

    //             // เชื่อม Rabbit MQ
    //             FCNxCallRabbitMQ($aMQParams);
    //         }
            
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    //เช็ค Ref ก่อน Cancel
    // public function FSvCCRVCancelCheckRef() {
    //     try {
    //         $tCRVDocNo   = $this->input->post('ptCRVDocNo');
    //         $CountIVRef = $this->customerreceived_model->FSaMCRVCheckIVRef($tCRVDocNo);
    //         if($CountIVRef > 0 ){
    //             $aReturnData = array(
    //                 'nStaEvent' => '2',
    //                 'tStaMessg' => 'ไม่สามารถยกเลิกได้ ใบรับของถูกอ้างอิงไปแล้ว กรุณายกเลิกการอ้างอิงก่อนทำรายการ'
    //             );
    //         }else{
    //             $aReturnData = array(
    //                 'nStaEvent' => '1',
    //                 'tStaMessg' => 'Success'
    //             );
    //         }
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    //อนุมัติเอกสาร
    // public function FSoCCRVApproveEvent(){
    //     try{
    //         $tDocNo         = $this->input->post('tDocNo');
    //         $tAgnCode       = $this->input->post('tAgnCode');
    //         $tBchCode       = $this->input->post('tBchCode');
    //         $tRefInDocNo    = $this->input->post('tRefInDocNo');
    //         if (!empty($tRefInDocNo)) {
    //             $this->customerreceived_model->FSaMCRVUpdatePOStaPrcDoc($tRefInDocNo);
    //         }
    //         $aMQParams = [
    //             "queueName" => "AP_QDocApprove",
    //             "params"    => [
    //                 'ptFunction'        => 'TSVTBookHD',
    //                 'ptSource'          => 'AdaStoreBack',
    //                 'ptDest'            => 'MQReceivePrc',
    //                 'ptFilter'          => '',
    //                 'ptData'            => json_encode([
    //                     "ptBchCode"     => $tBchCode,
    //                     "ptDocNo"       => $tDocNo,
    //                     "ptDocType"     => 11,
    //                     "ptUser"        => $this->session->userdata("tSesUsername"),
    //                 ])
    //             ]
    //         ];
    //         // เชื่อม Rabbit MQ
    //         FCNxCallRabbitMQ($aMQParams);
    //         $aDataGetDataHD     =   $this->customerreceived_model->FSaMCRVGetDataDocHD(array(
    //             'FTXphDocNo'    => $tDocNo,
    //             'FNLngID'       => $this->session->userdata("tLangEdit")
    //         ));
    //         if($aDataGetDataHD['rtCode']=='1'){
    //         $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXphDocNo']);
    //         if($aDataGetDataHD['raItems']['FTXphRefInt']!=''){
    //             $tTxtRefDoc1 = 'อ้างอิงเลขที่ #'.$aDataGetDataHD['raItems']['FTXphRefInt'];
    //             $tTxtRefDoc2 = 'Ref #'.$aDataGetDataHD['raItems']['FTXphRefInt'];
    //         }else{
    //             $tTxtRefDoc1 = "";
    //             $tTxtRefDoc2 = "";
    //         }
    //         $aTCNTNotiSpc[] = array(
    //                                     "FNNotID"       => $tNotiID,
    //                                     "FTNotType"    => '1',
    //                                     "FTNotStaType" => '1',
    //                                     "FTAgnCode"    => '',
    //                                     "FTAgnName"    => '',
    //                                     "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
    //                                     "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
    //                                 );
    //         if($aDataGetDataHD['raItems']['rtPOBchCode']!=''){
    //             $aTCNTNotiSpc[] = array(
    //                                         "FNNotID"       => $tNotiID,
    //                                         "FTNotType"    => '2',
    //                                         "FTNotStaType" => '1',
    //                                         "FTAgnCode"    => '',
    //                                         "FTAgnName"    => '',
    //                                         "FTBchCode"    => $aDataGetDataHD['raItems']['rtPOBchCode'],
    //                                         "FTBchName"    => $aDataGetDataHD['raItems']['rtPOBchName'],
    //                                     );
    //         }
    //         $aMQParamsNoti = [
    //             "queueName" => "CN_SendToNoti",
    //             "tVhostType" => "NOT",
    //             "params"    => [
    //                              "oaTCNTNoti" => array(
    //                                              "FNNotID"       => $tNotiID,
    //                                              "FTNotCode"     => '00011',
    //                                              "FTNotKey"      => 'TSVTBookHD',
    //                                              "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
    //                                              "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXphDocNo'],
    //                              ),
    //                              "oaTCNTNoti_L" => array(
    //                                                 0 => array(
    //                                                     "FNNotID"       => $tNotiID,
    //                                                     "FNLngID"       => 1,
    //                                                     "FTNotDesc1"    => 'เอกสารใบรับของ #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
    //                                                     "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
    //                                                 ),
    //                                                 1 => array(
    //                                                     "FNNotID"       => $tNotiID,
    //                                                     "FNLngID"       => 2,
    //                                                     "FTNotDesc1"    => 'Purchase orders from branches #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
    //                                                     "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Recive Product '.$tTxtRefDoc1,
    //                                                 )
    //                             ),
    //                              "oaTCNTNotiAct" => array(
    //                                                  0 => array( 
    //                                                         "FNNotID"       => $tNotiID,
    //                                                         "FDNoaDateInsert" => date('Y-m-d H:i:s'),
    //                                                         "FTNoaDesc"          => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
    //                                                         "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXphDocNo'],
    //                                                         "FNNoaUrlType"   =>  1,
    //                                                         "FTNoaUrlRef"    => 'docCRV/2/0',
    //                                                         ),
    //                                  ), 
    //                              "oaTCNTNotiSpc" => $aTCNTNotiSpc,
    //                 "ptUser"        => $this->session->userdata('tSesUsername'),
    //             ]
    //         ];
    //         FCNxCallRabbitMQ($aMQParamsNoti);
    //     }
    //         $aDataUpdate = array(
    //             'FTBchCode'         => $tBchCode,
    //             'FTXphDocNo'        => $tDocNo,
    //             'FTXphStaApv'       => 1,
    //             'FTXphUsrApv'       => $this->session->userdata('tSesUsername')
    //         );
    //         $this->customerreceived_model->FSaMCRVApproveDocument($aDataUpdate);

    //         $aDataWhere = array(
    //             'FTAgnCode'    => $tAgnCode,
    //             'FTBchCode'    => $tBchCode,
    //             'FTXphUsrApv'  => $aDataUpdate['FTXphUsrApv']
    //         );
    //         $aReturnData = array(
    //             'nStaEvent'    => '1',
    //             'tStaMessg'    => "Approve Document Success",
    //             'tLogType' => 'INFO',
    //             'tEventName' => 'อนุมัติใบรับของ',
    //             'nLogCode' => '001',
    //             'nLogLevel' => '0'
    //         );
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage(),
    //             'tLogType' => 'ERROR',
    //             'tEventName' => 'อนุมัติใบรับของ',
    //             'nLogCode' => '900',
    //             'nLogLevel' => '4'
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCCRVPageHDDocRef(){
        try {
            $tDocNo = $this->input->post('ptDocNo');
            $aDataWhere = [
                // 'tTableHDDocRef'    => 'TRTTSalHD',
                // 'tTableTmpHDRef'    => 'TSVTBookDocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                // 'FTXthDocKey'       => 'TSVTBookHD',
                // 'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef = $this->customerreceived_model->FSaMCRVGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/customerreceived/wCustomerReceivedDocRef', $aDataConfig, true);
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
    // public function FSoCCRVEventAddEditHDDocRef(){
    //     try {
    //         $aDataWhere = [
    //             'FTXthDocNo'        => $this->input->post('ptDocNo'),
    //             'FTXthDocKey'       => 'TSVTBookHD',
    //             'FTSessionID'       => $this->session->userdata('tSesSessionID'),
    //             'tSORefDocNoOld'    => $this->input->post('ptRefDocNoOld'),
    //             'FDCreateOn'        => date('Y-m-d H:i:s'),
    //         ];
            
    //         $aDataAddEdit = [
    //             'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
    //             'FTXthRefType'      => $this->input->post('ptRefType'),
    //             'FTXthRefKey'       => $this->input->post('ptRefKey'),
    //             'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
    //             'FDCreateOn'        => date('Y-m-d H:i:s'),
    //         ];
    //         $aReturnData = $this->customerreceived_model->FSaMCRVAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
        
    //     echo json_encode($aReturnData);
    // }

    // ค่าอ้างอิงเอกสาร - ลบ
    // public function FSoCCRVEventDelHDDocRef(){
    //     try {
    //         $aData = [
    //             'FTXthDocNo'        => $this->input->post('ptDocNo'),
    //             'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
    //             'FTXthDocKey'       => 'TSVTBookHD',
    //             'FTSessionID'       => $this->session->userdata('tSesSessionID')
    //         ];

    //         $aReturnData = $this->customerreceived_model->FSaMCRVDelHDDocRef($aData);
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // กดปุ่มสร้างใบสั่งขาย
    // public function FSoCCRVEventGenSO(){
    //     $tDocNo     = $this->input->post('tDocNo');
    //     $tBchCode   = $this->input->post('tBchCode');

    //     $aMQParams = [
    //         "queueName" => "CN_QGenDoc",
    //         "params"    => [
    //             'ptFunction'    => "TSVTBookHD",
    //             'ptSource'      => 'AdaStoreBack',
    //             'ptDest'        => 'MQReceivePrc',
    //             'ptFilter'      => '',
    //             'ptData'        => json_encode([
    //                 "ptBchCode"     => $tBchCode,
    //                 "ptDocNo"       => $tDocNo,
    //                 "ptDocType"     => 11,
    //                 "ptUser"        => $this->session->userdata("tSesUsername"),
    //             ])
    //         ]
    //     ];
    //     // เชื่อม Rabbit MQ
    //     FCNxCallRabbitMQ($aMQParams);
    // }

    // Create By : Napat(Jame) 06/07/2022
    // ยืนยันรับของ
    public function FSoCCRVEventSave(){
        try {
            $aDataUpd = array(
                'tDocNo'    => $this->input->post('ptDocNo'),
                'tBchCode'  => $this->input->post('ptBchCode'),
                'aItems'    => $this->input->post('paItems'),
            );
            $aReturnData = $this->customerreceived_model->FSaMCRVEventSave($aDataUpd);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }


    

}
