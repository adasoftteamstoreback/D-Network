<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliveryorder_controller extends MX_Controller {

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/deliveryorder/deliveryorder_model');
        parent::__construct();

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }
    
    public $tRouteMenu  = 'docDO/0/0';
    
    public function index($nDOBrowseType, $tDOBrowseOption){
        $aParams    = [
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        ];
        $aDataConfigView    = array(
            'nDOBrowseType'     => $nDOBrowseType,
            'tDOBrowseOption'   => $tDOBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParams'           => $aParams ,
        );
        $this->load->view('document/deliveryorder/wDeliveryOrder', $aDataConfigView);
        unset($aParams);
        unset($aDataConfigView);
        unset($nDOBrowseType,$tDOBrowseOption);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCDOFormSearchList() {
        $this->load->view('document/deliveryorder/wDeliveryOrderFormSearchList');
    }

    // แสดงตารางในหน้า List
    public function FSoCDODataTable() {
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
            $aDataList   = $this->deliveryorder_model->FSaMDOGetDataTableList($aDataCondition);
            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tDOViewDataTableList = $this->load->view('document/deliveryorder/wDeliveryOrderDataTable', $aConfigView, true);
            $aReturnData = array(
                'tDOViewDataTableList' => $tDOViewDataTableList,
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
        unset($tDOViewDataTableList);
        echo json_encode($aReturnData);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDOCallRefIntDoc(){
        $tDocType   = $this->input->post('tDocType');
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName,
            'tDocType' => $tDocType,
        );
        $this->load->view('document/deliveryorder/refintdocument/wDeliveryOrderRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCDOCallRefIntDocDataTable(){
        $nPage                  = $this->input->post('nDORefIntPageCurrent');
        $tDORefIntBchCode       = $this->input->post('tDORefIntBchCode');
        $tDORefIntDocNo         = $this->input->post('tDORefIntDocNo');
        $tDORefIntDocDateFrm    = $this->input->post('tDORefIntDocDateFrm');
        $tDORefIntDocDateTo     = $this->input->post('tDORefIntDocDateTo');
        $tDORefIntStaDoc        = $this->input->post('tDORefIntStaDoc');
        $tDODocType             = $this->input->post('tDODocType');
        $tDOSplCode             = $this->input->post('tDOSplCode');
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nDORefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tDORefIntBchCode'      => $tDORefIntBchCode,
            'tDORefIntDocNo'        => $tDORefIntDocNo,
            'tDORefIntDocDateFrm'   => $tDORefIntDocDateFrm,
            'tDORefIntDocDateTo'    => $tDORefIntDocDateTo,
            'tDORefIntStaDoc'       => $tDORefIntStaDoc,
            'tDOSplCode'            => $tDOSplCode
        );
        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'nPage'     => $nPage,
            'nRow'      => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        if ($tDODocType == 1) {
            $aDataParam = $this->deliveryorder_model->FSoMDOCallRefPOIntDocDataTable($aDataCondition);
        }else{
            $aDataParam = $this->deliveryorder_model->FSoMDOCallRefABBIntDocDataTable($aDataCondition);
        }
        $aConfigView = array(
            'nPage'         => $nPage,
            'aDataList'     => $aDataParam,
            'tDODocType'    => $tDODocType
        );
        $this->load->view('document/deliveryorder/refintdocument/wDeliveryOrderRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDOCallRefIntDocDetailDataTable(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $tDocType           = $this->input->post('ptdoctype');
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        $aDataCondition     = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        if ($tDocType == 1) {
            $aDataParam = $this->deliveryorder_model->FSoMDOCallRefIntDocDTDataTable($aDataCondition);
        }else{
            $aDataParam = $this->deliveryorder_model->FSoMDOCallRefIntDocABBDTDataTable($aDataCondition);
        }
        $aConfigView    = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/deliveryorder/refintdocument/wDeliveryOrderRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCDOCallRefIntDocInsertDTToTemp(){
        $tDODocNo           =  $this->input->post('tDODocNo');
        $tDOFrmBchCode      =  $this->input->post('tDOFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
        $tDoctype           =  $this->input->post('tDoctype');
        $tDOOptionAddPdt    =  $this->input->post('tDOOptionAddPdt');
        $aDataParam         = array(
            'tDODocNo'          => $tDODocNo,
            'tDOFrmBchCode'     => $tDOFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'TAPTDoHD',
            'tDOOptionAddPdt'   => $tDOOptionAddPdt,
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
        );
        if ($tDoctype == 1) {
            $tDocType       = 'PO';
            $aDataResult    = $this->deliveryorder_model->FSoMDOCallRefIntDocInsertDTToTemp($aDataParam, $tDocType);
        }else{
            $tDocType       = 'ABB';
            $aDataResult    = $this->deliveryorder_model->FSoMDOCallRefIntABBDocInsertDTToTemp($aDataParam, $tDocType);
        }
        return  $aDataResult;
    }

    public function FSoCDOClearTempWhenChangeData(){
        try {
            $tDODocNo           = $this->input->post('tDODocNo');
            $aWhereClearTemp    = [
                'FTXthDocNo'    => $tDODocNo,
                'FTXthDocKey'   => 'TAPTDoHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->deliveryorder_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->deliveryorder_model->FSxMDOClearDataInDocTemp($aWhereClearTemp);
            $aReturnData = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (\Throwable $e) {
            $aReturnData = array(
                'nStaEvent' => '999',
                'tStaMessg' => 'Success'    
            );
        }
        return  $aReturnData;
    }

    // เรียกหน้าเพิ่มข้อมูล
    public function FSoCDOPageAdd() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp    = [
                'FTXthDocNo'    => '',
                'FTXthDocKey'   => 'TAPTDoHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];

            $this->deliveryorder_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->deliveryorder_model->FSxMDOClearDataInDocTemp($aWhereClearTemp);
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $nOptDocSave        = get_cookie('tOptDocSave');
            $nOptScanSku        = get_cookie('tOptScanSku');

            //ถ้าเป็นแบบแฟรนไซด์
            if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){
                $aSPLConfig     = $this->deliveryorder_model->FSxMDOFindSPLByConfig();
            }else{
                $aSPLConfig     = '';
            }

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'nOptDocSave'       => $nOptDocSave,
                'nOptScanSku'       => $nOptScanSku,
                'aSPLConfig'        => $aSPLConfig,
                'aDataDocHD'        => array('rtCode' => '800'),
            );
            $tDOViewPageAdd = $this->load->view('document/deliveryorder/wDeliveryOrderPageAdd', $aDataConfigViewAdd, true);
            $aReturnData    = array(
                'tDOViewPageAdd'    => $tDOViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // แสดงผลลัพธ์การค้นหาขั้นสูง
    public function FSoCDOPdtAdvTblLoadData() {
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
            $tDODocNo           = $this->input->post('ptDODocNo');
            $tDOStaApv          = $this->input->post('ptDOStaApv');
            $tDOStaDoc          = $this->input->post('ptDOStaDoc');
            $nDOPageCurrent     = $this->input->post('pnDOPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tDOPdtCode         = $this->input->post('ptDOPdtCode');
            $tDOPunCode         = $this->input->post('ptDOPunCode');
            //Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tDODocNo,
                'FTXthDocKey'           => 'TAPTDoDT',
                'nPage'                 => $nDOPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );
            $aDataDocDTTemp     = $this->deliveryorder_model->FSaMDOGetDocDTTempListPage($aDataWhere);
            $aDataView          = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tDOStaApv'         => $tDOStaApv,
                'tDOStaDoc'         => $tDOStaDoc,
                'tDOPdtCode'        => $tDOPdtCode,
                'tDOPunCode'        => $tDOPunCode,
                'nPage'             => $nDOPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );
            $tDOPdtAdvTableHtml = $this->load->view('document/deliveryorder/wDeliveryOrderPdtAdvTableData', $aDataView, true);
            $aReturnData    = array(
                'tDOPdtAdvTableHtml'    => $tDOPdtAdvTableHtml,
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
    public function FSoCDOAddPdtIntoDocDTTemp() {
        try {
            $tDODocNo           = $this->input->post('tDODocNo');
            $tDOBchCode         = $this->input->post('tSelectBCH');
            $tDOOptionAddPdt    = $this->input->post('tDOOptionAddPdt');
            $tDOPdtData         = $this->input->post('tDOPdtData');
            $aDOPdtData         = json_decode($tDOPdtData);
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aDOPdtData); $nI++) {
                $tDOPdtCode = $aDOPdtData[$nI]->pnPdtCode;
                $tDOBarCode = $aDOPdtData[$nI]->ptBarCode;
                $tDOPunCode = $aDOPdtData[$nI]->ptPunCode;
                $cDOPrice       = $aDOPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tDODocNo,
                    'tBchCode'          => $tDOBchCode,
                    'tPdtCode'          => $tDOPdtCode,
                    'tBarCode'          => $tDOBarCode,
                    'tPunCode'          => $tDOPunCode,
                    'cPrice'            => str_replace(",","",$cDOPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdDOLangEdit"),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TAPTDoDT',
                    'tDOOptionAddPdt'   => $tDOOptionAddPdt,
                    'tDOUsrCode'        => $this->input->post('ohdDOUsrCode'),
                    'nVatRate'          => $nVatRate,
                    'nVatCode'          => $nVatCode
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->deliveryorder_model->FSaMDOGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $this->deliveryorder_model->FSaMDOInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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
    public function FSvCDORemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tDODocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TAPTDoDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );
            $this->deliveryorder_model->FSnMDODelPdtInDTTmp($aDataWhere);
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
    public function FSvCDORemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tDODocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => str_replace(",","','",$this->input->post('tPdtCode')),
                'nSeqNo'   => str_replace(",","','",$this->input->post('nSeqNo')),
                'tDocKey'  => 'TAPTDoDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $this->deliveryorder_model->FSnMDODelMultiPdtInDTTmp($aDataWhere);
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

    // Function: Edit Inline สินค้า ลง Document DT Temp
    public function FSoCDOEditPdtIntoDocDTTemp() {
        try {
            $tDOBchCode         = $this->input->post('tDOBchCode');
            $tDODocNo           = $this->input->post('tDODocNo');
            $nDOSeqNo           = $this->input->post('nDOSeqNo');
            $tDOSessionID       = $this->session->userdata('tSesSessionID');
            $aDataWhere     = array(
                'tDOBchCode'    => $tDOBchCode,
                'tDODocNo'      => $tDODocNo,
                'nDOSeqNo'      => $nDOSeqNo,
                'tDOSessionID'  => $tDOSessionID,
                'tDocKey'       => 'TAPTDoDT',
            );
            $aDataUpdateDT  = array(
                'FCXtdQty'      => $this->input->post('nQty'),
                'FTXtdPdtName'  => $this->input->post('FTXtdPdtName'),
            );
            $this->db->trans_begin();
            $this->deliveryorder_model->FSaMDOUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
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

    // Function: Check Product Have In Temp For Document DT
    public function FSoCDOChkHavePdtForDocDTTemp() {
        try {
            $tDODocNo       = $this->input->post("ptDODocNo");
            $tDOSessionID   = $this->input->post('tDOSesSessionID');
            $aDataWhere     = array(
                'FTXthDocNo'    => $tDODocNo,
                'FTXthDocKey'   => 'TAPTDoDT',
                'FTSessionID'   => $tDOSessionID
            );
            $nCountPdtInDocDTTemp   = $this->deliveryorder_model->FSnMDOChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData    = array(
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData    = array(
                    'nStaReturn'    => '800',
                    'tStaMessg'     => language('document/deliveryorder/deliveryorder', 'tDOPleaseSeletedPDTIntoTable')
                );
            }
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //เพิ่มข้อมูล
    public function FSoCDOAddEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDOAutoGenCode     = (isset($aDataDocument['ocbDOStaAutoGenCode'])) ? 1 : 0;
            $tDODocNo           = (isset($aDataDocument['oetDODocNo'])) ? $aDataDocument['oetDODocNo'] : '';
            $tDODocDate         = $aDataDocument['oetDODocDate'] . " " . $aDataDocument['oetDODocTime'];
            $tDOStaDocAct       = (isset($aDataDocument['ocbDOFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tDOVATInOrEx       = $aDataDocument['ocmDOFrmSplInfoVatInOrEx'];
            $nDOSubmitWithImp   = $aDataDocument['ohdDOSubmitWithImp'];
            $nDocType           = $aDataDocument['ohdDODocType'];
            $aClearDTParams = [
                'FTXthDocNo'    => $tDODocNo,
                'FTXthDocKey'   => 'TAPTDoDT',
                'FTSessionID'   => $this->input->post('ohdSesSessionID'),
            ];
            if($nDOSubmitWithImp==1){
                $this->deliveryorder_model->FSxMDOClearDataInDocTempForImp($aClearDTParams);
            }
            // Check Auto GenCode Document
            if ($tDOAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TAPTDoHD',                           
                    "tDocType"    => '11',                                          
                    "tBchCode"    => $aDataDocument['oetDOFrmBchCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
                $tDODocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $tDODocNo = $tDODocNo;
            }
            // Array Data Table Document
            $aTableAddUpdate    = array(
                'tTableHD'      => 'TAPTDoHD',
                'tTableHDSpl'   => 'TAPTDoHDSpl',
                'tTableDT'      => 'TAPTDoDT',
                'tTableStaGen'  => 11,
                'tTableRefDO'   => 'TAPTDoHDDocRef',
                'tTableRefPO'   => 'TAPTPoHDDocRef'
            );
            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['oetDOAgnCode'],
                'FTBchCode'         => $aDataDocument['oetDOFrmBchCode'],
                'FTXphDocNo'        => $tDODocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdDOUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdDOUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tDOVATInOrEx,
                'FTXphUsrApv'       => ''
            );
            // Array Data HD Master
            $aDataMaster    = array(
                'FNXphDocType'      => 11,
                'FDXphDocDate'      => (!empty($tDODocDate)) ? $tDODocDate : NULL,
                'FTXphCshOrCrd'     => $aDataDocument['ocmDOTypePayment'],
                'FTXphVATInOrEx'    => $tDOVATInOrEx,
                'FTDptCode'         => $aDataDocument['ohdDODptCode'],
                'FTWahCode'         => $aDataDocument['oetDOFrmWahCode'],
                'FTUsrCode'         => $aDataDocument['ohdDOUsrCode'],
                'FTSplCode'         => $aDataDocument['oetDOFrmSplCode'],
                'FNXphDocPrint'     => $aDataDocument['ocmDOFrmInfoOthDocPrint'],
                'FTXphRmk'          => $aDataDocument['otaDOFrmInfoOthRmk'],
                'FTXphStaDoc'       => $aDataDocument['ohdDOStaDoc'],
                'FTXphStaApv'       => !empty($aDataDocument['ohdDOStaApv']) ? $aDataDocument['ohdDOStaApv'] : NULL,
                'FTXphStaDelMQ'     => $aDataDocument['ohdDOStaDelMQ'],
                'FTXphStaPrcStk'    => $aDataDocument['ohdDOStaPrcStk'],
                'FNXphStaDocAct'    => $tDOStaDocAct,
                'FNXphStaRef'       => $aDataDocument['ocmDOFrmInfoOthRef']
            );
            // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);
            $aDataSpl   = array(
                'FNXphCrTerm'   => intval($aDataDocument['oetDOFrmSplInfoCrTerm']),
                'FTXphCtrName'  => $aDataDocument['oetDOFrmSplInfoCtrName'],
                'FDXphTnfDate'  => (!empty($aDataDocument['oetDOFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDOFrmSplInfoTnfDate'])) : NULL,
                'FTXphRefTnfID' => $aDataDocument['oetDOFrmSplInfoRefTnfID'],
                'FTXphRefVehID' => $aDataDocument['oetDOFrmSplInfoRefVehID'],
            );
            $this->db->trans_begin();
            // // Add Update Document HD
            $this->deliveryorder_model->FSxMDOAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // // Add Update Document HD Spl
            $this->deliveryorder_model->FSxMDOAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);
            // [Update] DocNo -> Temp
            $this->deliveryorder_model->FSxMDOAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            // // Move Doc DTTemp To DT
            $this->deliveryorder_model->FSaMDOMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
            $this->deliveryorder_model->FSxMDOMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tEventName'    => 'บันทึกใบรับของ',
                    'nLogCode'      => '900',
                    'nLogLevel'     => '4'
                );
            } else {
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.',
                    //เพิ่มใหม่
                    'tLogType'      => 'INFO',
                    'tEventName'    => 'บันทึกใบรับของ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '0'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tEventName'    => 'บันทึกใบรับของ',
                'nLogCode'      => '900',
                'nLogLevel'     => '4'
            );
        }
        echo json_encode($aReturnData);
    }

    //แก้ไขเอกสาร
    public function FSoCDOEditEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDODocNo           = (isset($aDataDocument['oetDODocNo'])) ? $aDataDocument['oetDODocNo'] : '';
            $tDODocDate         = $aDataDocument['oetDODocDate'] . " " . $aDataDocument['oetDODocTime'];
            $tDOStaDocAct       = (isset($aDataDocument['ocbDOFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tDOVATInOrEx       = $aDataDocument['ocmDOFrmSplInfoVatInOrEx'];
            $nDOSubmitWithImp   = $aDataDocument['ohdDOSubmitWithImp'];
            $nDocType           = $aDataDocument['ohdDODocType'];
            // Get Data Comp.
            $aClearDTParams = [
                'FTXthDocNo'     => $tDODocNo,
                'FTXthDocKey'    => 'TAPTDoDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nDOSubmitWithImp==1){
                $this->deliveryorder_model->FSxMDOClearDataInDocTempForImp($aClearDTParams);
            }
            if (!empty($aDataDocument['oetDORefDocIntName'])) {
                $tRefInDocNo    = $aDataDocument['oetDORefDocIntName'];
                $nStaRef        = '2';
                $this->deliveryorder_model->FSaMDOUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TAPTDoHD',
                'tTableHDSpl'   => 'TAPTDoHDSpl',
                'tTableDT'      => 'TAPTDoDT',
                'tTableStaGen'  => 11,
                'tTableRefDO'   => 'TAPTDoHDDocRef',
                'tTableRefPO'   => 'TAPTPoHDDocRef'
            );
            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['oetDOAgnCode'],
                'FTBchCode'         => $aDataDocument['oetDOFrmBchCode'],
                'FTXphDocNo'        => $tDODocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdDOUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdDOUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tDOVATInOrEx,
                'FTXphUsrApv'       => ''
            );
            // Array Data HD Master
            $aDataMaster = array(
                'FNXphDocType'      => 11,
                'FDXphDocDate'      => (!empty($tDODocDate)) ? $tDODocDate : NULL,
                'FTXphCshOrCrd'     => $aDataDocument['ocmDOTypePayment'],
                'FTXphVATInOrEx'    => $tDOVATInOrEx,
                'FTDptCode'         => $aDataDocument['ohdDODptCode'],
                'FTWahCode'         => $aDataDocument['oetDOFrmWahCode'],
                'FTUsrCode'         => $aDataDocument['ohdDOUsrCode'],
                'FTSplCode'         => $aDataDocument['oetDOFrmSplCode'],
                'FNXphDocPrint'     => $aDataDocument['ocmDOFrmInfoOthDocPrint'],
                'FTXphRmk'          => $aDataDocument['otaDOFrmInfoOthRmk'],
                'FTXphStaDoc'       => $aDataDocument['ohdDOStaDoc'],
                'FTXphStaApv'       => !empty($aDataDocument['ohdDOStaApv']) ? $aDataDocument['ohdDOStaApv'] : NULL,
                'FTXphStaDelMQ'     => $aDataDocument['ohdDOStaDelMQ'],
                'FTXphStaPrcStk'    => $aDataDocument['ohdDOStaPrcStk'],
                'FNXphStaDocAct'    => $tDOStaDocAct,
                'FNXphStaRef'       => $aDataDocument['ocmDOFrmInfoOthRef']
            );
            // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);
            $aDataSpl = array(
                'FNXphCrTerm'   => intval($aDataDocument['oetDOFrmSplInfoCrTerm']),
                'FTXphCtrName'  => $aDataDocument['oetDOFrmSplInfoCtrName'],
                'FDXphTnfDate'  => (!empty($aDataDocument['oetDOFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDOFrmSplInfoTnfDate'])) : NULL,
                'FTXphRefTnfID' => $aDataDocument['oetDOFrmSplInfoRefTnfID'],
                'FTXphRefVehID' => $aDataDocument['oetDOFrmSplInfoRefVehID'],
            );
            $this->db->trans_begin();
            // Add Update Document HD
            $this->deliveryorder_model->FSxMDOAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // Add Update Document HD Spl
            $this->deliveryorder_model->FSxMDOAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);
            // [Update] DocNo -> Temp
            $this->deliveryorder_model->FSxMDOAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            // Move Doc DTTemp To DT
            $this->deliveryorder_model->FSaMDOMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
            // [Move] Doc TSVTDODocHDRefTmp To TARTDoHDDocRef
            $this->deliveryorder_model->FSxMDOMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Edit Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tEventName'    => 'บันทึกใบรับของ',
                    'nLogCode'      => '900',
                    'nLogLevel'     => '4'
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Edit Document.',
                    'tLogType'      => 'INFO',
                    'tEventName'    => 'แก้ไขและบันทึกใบรับของ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '0'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tEventName'    => 'แก้ไขและบันทึกใบรับของ',
                'nLogCode'      => '900',
                'nLogLevel'     => '4'
            );
        }
        $nStaApvOrSave = $aDataDocument['ohdDOApvOrSave'];
        echo json_encode($aReturnData);
    }

    //หน้าจอแก้ไข
    public function FSvCDOEditPage(){
        try {
            $ptDocumentNumber   = $this->input->post('ptDODocNo');
            // Clear Data In Doc DT Temp
            $aWhereClearTemp    = [
                'FTXthDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TAPTDoHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->deliveryorder_model->FSnMDODelALLTmp($aWhereClearTemp);
            $this->deliveryorder_model->FSxMDOClearDataInDocTemp($aWhereClearTemp);
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TAPTDoDT',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 90000,
                'nPage'         => 1,
            );
            // Get Autentication Route
            $aAlwEvent         = FCNaHCheckAlwFunc($this->tRouteMenu); // Controle Event
            $vBtnSave          = FCNaHBtnSaveActiveHTML($this->tRouteMenu); // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            $nOptDecimalShow   = get_cookie('tOptDecimalShow');
            $nOptDecimalSave   = get_cookie('tOptDecimalSave');
            $nOptDocSave       = get_cookie('tOptDocSave');
            $this->db->trans_begin();
            // Get Data Document HD
            $aDataDocHD = $this->deliveryorder_model->FSaMDOGetDataDocHD($aDataWhere);
            // Move Data DT TO DTTemp
            $this->deliveryorder_model->FSxMDOMoveDTToDTTemp($aDataWhere);
            // Move Data HDDocRef TO HDRefTemp
            $this->deliveryorder_model->FSxMDOMoveHDRefToHDRefTemp($aDataWhere);
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
                $tViewPageEdit           = $this->load->view('document/deliveryorder/wDeliveryOrderPageAdd',$aDataConfigViewEdit,true);
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
    public function FSoCDODeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $tRefInDocNo = $this->input->post('tDORefInCode');
            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->deliveryorder_model->FSaMDOUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tBchCode' => $tBchCode
            );
            $aResDelDoc = $this->deliveryorder_model->FSnMDODelDocument($aDataMaster);
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

    //ยกเลิกเอกสาร
    public function FSvCDOCancelDocument() {
        try {
            $tDODocNo       = $this->input->post('ptDODocNo');
            $tRefInDocNo    = $this->input->post('ptRefInDocNo');
            $tStaApv        = $this->input->post('ptStaApv');
            $tBchCode       = $this->input->post('ptBchCode');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->deliveryorder_model->FSaMDOUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }
            
            $aDataUpdate = array(
                'tDocNo' => $tDODocNo,
            );
            $aResult        = $this->deliveryorder_model->FSaMDOCancelDocument($aDataUpdate);
            $aReturnData    = $aResult;

            if($tStaApv == 1){
                //ถ้าอนุมัติเเล้ว แล้วกดยกเลิกต้องวิ่ง MQ อีกรอบ ให้เคลียร์ Stock
                $aMQParams = [
                    "queueName" => "AP_QDocApprove",
                    "params"    => [
                        'ptFunction'    => 'TAPTDoHD',
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => '',
                        'ptData'        => json_encode([
                            "ptBchCode"     => $tBchCode,
                            "ptDocNo"       => $tDODocNo,
                            "ptDocType"     => 11,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];

                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
            }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //เช็ค Ref ก่อน Cancel
    public function FSvCDOCancelCheckRef() {
        try {
            $tDODocNo   = $this->input->post('ptDODocNo');
            $CountIVRef = $this->deliveryorder_model->FSaMDOCheckIVRef($tDODocNo);
            if($CountIVRef > 0 ){
                $aReturnData = array(
                    'nStaEvent' => '2',
                    'tStaMessg' => 'ไม่สามารถยกเลิกได้ ใบรับของถูกอ้างอิงไปแล้ว กรุณายกเลิกการอ้างอิงก่อนทำรายการ'
                );
            }else{
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
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

    //อนุมัติเอกสาร
    public function FSoCDOApproveEvent(){
        try{
            $tDocNo         = $this->input->post('tDocNo');
            $tAgnCode       = $this->input->post('tAgnCode');
            $tBchCode       = $this->input->post('tBchCode');
            $tRefInDocNo    = $this->input->post('tRefInDocNo');
            if (!empty($tRefInDocNo)) {
                $this->deliveryorder_model->FSaMDOUpdatePOStaPrcDoc($tRefInDocNo);
            }
            $aMQParams = [
                "queueName" => "AP_QDocApprove",
                "params"    => [
                    'ptFunction'        => 'TAPTDoHD',
                    'ptSource'          => 'AdaStoreBack',
                    'ptDest'            => 'MQReceivePrc',
                    'ptFilter'          => '',
                    'ptData'            => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptDocType"     => 11,
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            // เชื่อม Rabbit MQ
            FCNxCallRabbitMQ($aMQParams);
            $aDataGetDataHD     =   $this->deliveryorder_model->FSaMDOGetDataDocHD(array(
                'FTXphDocNo'    => $tDocNo,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            ));
            if($aDataGetDataHD['rtCode']=='1'){
            $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXphDocNo']);
            if($aDataGetDataHD['raItems']['FTXphRefInt']!=''){
                $tTxtRefDoc1 = 'อ้างอิงเลขที่ #'.$aDataGetDataHD['raItems']['FTXphRefInt'];
                $tTxtRefDoc2 = 'Ref #'.$aDataGetDataHD['raItems']['FTXphRefInt'];
            }else{
                $tTxtRefDoc1 = "";
                $tTxtRefDoc2 = "";
            }
            $aTCNTNotiSpc[] = array(
                                        "FNNotID"       => $tNotiID,
                                        "FTNotType"    => '1',
                                        "FTNotStaType" => '1',
                                        "FTAgnCode"    => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
                                    );
            if($aDataGetDataHD['raItems']['rtPOBchCode']!=''){
                $aTCNTNotiSpc[] = array(
                                            "FNNotID"       => $tNotiID,
                                            "FTNotType"    => '2',
                                            "FTNotStaType" => '1',
                                            "FTAgnCode"    => '',
                                            "FTAgnName"    => '',
                                            "FTBchCode"    => $aDataGetDataHD['raItems']['rtPOBchCode'],
                                            "FTBchName"    => $aDataGetDataHD['raItems']['rtPOBchName'],
                                        );
            }
            $aMQParamsNoti = [
                "queueName" => "CN_SendToNoti",
                "tVhostType" => "NOT",
                "params"    => [
                                 "oaTCNTNoti" => array(
                                                 "FNNotID"       => $tNotiID,
                                                 "FTNotCode"     => '00011',
                                                 "FTNotKey"      => 'TAPTDoHD',
                                                 "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                                 "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXphDocNo'],
                                 ),
                                 "oaTCNTNoti_L" => array(
                                                    0 => array(
                                                        "FNNotID"       => $tNotiID,
                                                        "FNLngID"       => 1,
                                                        "FTNotDesc1"    => 'เอกสารใบรับของ #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
                                                        "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
                                                    ),
                                                    1 => array(
                                                        "FNNotID"       => $tNotiID,
                                                        "FNLngID"       => 2,
                                                        "FTNotDesc1"    => 'Purchase orders from branches #'.$aDataGetDataHD['raItems']['FTXphDocNo'],
                                                        "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Recive Product '.$tTxtRefDoc1,
                                                    )
                                ),
                                 "oaTCNTNotiAct" => array(
                                                     0 => array( 
                                                            "FNNotID"       => $tNotiID,
                                                            "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                                            "FTNoaDesc"          => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
                                                            "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXphDocNo'],
                                                            "FNNoaUrlType"   =>  1,
                                                            "FTNoaUrlRef"    => 'docDO/2/0',
                                                            ),
                                     ), 
                                 "oaTCNTNotiSpc" => $aTCNTNotiSpc,
                    "ptUser"        => $this->session->userdata('tSesUsername'),
                ]
            ];
            FCNxCallRabbitMQ($aMQParamsNoti);
        }
            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXphDocNo'        => $tDocNo,
                'FTXphStaApv'       => 1,
                'FTXphUsrApv'       => $this->session->userdata('tSesUsername')
            );
            $this->deliveryorder_model->FSaMDOApproveDocument($aDataUpdate);

            $aDataWhere = array(
                'FTAgnCode'    => $tAgnCode,
                'FTBchCode'    => $tBchCode,
                'FTXphUsrApv'  => $aDataUpdate['FTXphUsrApv']
            );
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Approve Document Success",
                'tLogType' => 'INFO',
                'tEventName' => 'อนุมัติใบรับของ',
                'nLogCode' => '001',
                'nLogLevel' => '0'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tEventName' => 'อนุมัติใบรับของ',
                'nLogCode' => '900',
                'nLogLevel' => '4'
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCDOPageHDDocRef(){
        try {
            $tDocNo = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TAPTDoHDDocRef',
                'tTableTmpHDRef'    => 'TSVTDODocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TAPTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef = $this->deliveryorder_model->FSaMDOGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/deliveryorder/wDeliveryOrderDocRef', $aDataConfig, true);
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
    public function FSoCDOEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthDocKey'       => 'TAPTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tSORefDocNoOld'    => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData = $this->deliveryorder_model->FSaMDOAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCDOEventDelHDDocRef(){
        try {
            $aData = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthDocKey'       => 'TAPTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aReturnData = $this->deliveryorder_model->FSaMDODelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // กดปุ่มสร้างใบสั่งขาย
    public function FSoCDOEventGenSO(){
        $tDocNo     = $this->input->post('tDocNo');
        $tBchCode   = $this->input->post('tBchCode');

        $aMQParams = [
            "queueName" => "CN_QGenDoc",
            "params"    => [
                'ptFunction'    => "TAPTDoHD",
                'ptSource'      => 'AdaStoreBack',
                'ptDest'        => 'MQReceivePrc',
                'ptFilter'      => '',
                'ptData'        => json_encode([
                    "ptBchCode"     => $tBchCode,
                    "ptDocNo"       => $tDocNo,
                    "ptDocType"     => 11,
                    "ptUser"        => $this->session->userdata("tSesUsername"),
                ])
            ]
        ];
        // เชื่อม Rabbit MQ
        FCNxCallRabbitMQ($aMQParams);
    }

}
