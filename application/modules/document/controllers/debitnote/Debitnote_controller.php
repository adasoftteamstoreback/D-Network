<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Debitnote_controller extends MX_Controller {

    // Paramiter Main Route Menu
    public $tRouteMenu  = 'docDBN/0/0';

    public function __construct(){
        $this->load->model('customer/customer/mCustomerAddress');
        $this->load->model('document/debitnote/Debitnote_modal');
        parent::__construct();
    }

    // แสดงส่วนหัวหน้าใบประเมินความพึงพอใจของลูกค้า ($ptRoute คือ param ของหน้าว่าเข้ามาแบบ insert หรือ view, $ptDocCode เลขที่เอกสารที่แนบมา
    public function index($ptRoute, $ptDocCode){
        $aDataConfigView = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        ];
        $this->load->view('document/debitnote/wDebitnote',$aDataConfigView);
    }

    // Cell View Debit Note List
    public function FSxCDBNPageList(){
        $this->load->view('document/debitnote/wDebitnotePageList');
    }

    // แสดงตารางในหน้า List
    public function FSxCDBNDatatable(){
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $aAlwEvent      = FCNaHCheckAlwFunc($this->tRouteMenu);
            // Check Page Current
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage  = $this->input->post('nPageCurrent');
            }
            // Lang ภาษา
            $nLangEdit  = $this->session->userdata("tLangEdit");
            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID'   => $nLangEdit,
                'nPage'     => $nPage,
                'nRow'      => 10,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList      = $this->Debitnote_modal->FSaMDBNGetDataTableList($aDataCondition);
            $aConfigView    = array(
                'nPage'     => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/debitnote/wDebitnoteDatable', $aConfigView, true);
            $aReturnData = array(
                'tViewDataTable'    => $tViewDataTable,
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
    
    // หน้าจอแก้ไข
    public function FSvCDBNEditPage(){
        $nLangID    = $this->session->userdata("tLangEdit");
        $tAgnCode   = $this->input->post('ptAgnCode');
        $tBchCode   = $this->input->post('ptBchCode');
        $tDocNo     = $this->input->post('ptDocNo');
        $tCstCode   = $this->input->post('ptCstCode');
        // ดึงข้อมูล HD
        $aDataDocHD     = $this->Debitnote_modal->FSaMDBNGetDataHD($tAgnCode,$tBchCode,$tDocNo);
        // ดึงข้อมูล HD DocRef
        $aDataDocHDRef  = $this->Debitnote_modal->FSaMDBNGetDataHDDocRef($tAgnCode,$tBchCode,$tDocNo);
        // ดึงข้อมูล HD Doc Cst
        $aDataDocHdCst  = $this->Debitnote_modal->FSaMDBNGetDataHDCst($tAgnCode,$tBchCode,$tDocNo);
        // ดึงข้อมูล DT
        $aDataDocDT     = $this->Debitnote_modal->FSaMDBNGetDataDT($tAgnCode,$tBchCode,$tDocNo);
        // ดึงข้อมูล SUM VAT
        $aDataDocSumVat = $this->Debitnote_modal->FSaMDBNGetDataSumVat($tAgnCode,$tBchCode,$tDocNo);
        // ดึงข้อมูล VAT
        $aDataDocVat    = $this->Debitnote_modal->FSaMDBNGetDataVat($tAgnCode,$tBchCode,$tDocNo);
        $aDataAll       = array(
            'aDataDocHD'        => $aDataDocHD,
            'aDataDocHDRef'     => $aDataDocHDRef,
            'aDataDocHdCst'     => $aDataDocHdCst,
            'aDataDocDT'        => $aDataDocDT,
            'aDataDocSumVat'    => $aDataDocSumVat,
            'aDataDocVat'       => $aDataDocVat,
            'tRtCode'           => 1,
            'nRsCodeReturn'     => 1,
            'tRoute'            => 'docDBNEventEdit'
        );
        $this->load->view('document/debitnote/wDebitnotePageForm', $aDataAll);




    }

    // Event แก้ไขเอกสาร
    public function FSxCDBNEditEvent(){
        $aDataUpdateDoc = [
            'FTXshRmk'          => $this->input->post('otaDBNXshRmk'),
            'FNXshStaDocAct'    => $this->input->post('ohdDBNStaDocAct')
        ];
        $aDataWhereDoc  = [
            'FTBchCode'     => $this->input->post('ohdDBNBchCode'),
            'FTXshDocNo'    => $this->input->post('oetDBNDocNo'),
        ];
        $this->db->trans_begin();
        // ข้อมูล Update ลงตาราง  HD
        $this->Debitnote_modal->FSaMDNBQaAddUpdateHD($aDataUpdateDoc,$aDataWhereDoc);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturnData    = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Error Unsucess Add Document."
            );
        }else{
            $this->db->trans_commit();
            $aReturnData    = array(
                'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                'tCodeReturn'   => 0,
                'nStaEvent'     => '1',
                'tAgnCode'      => '',
                'tBchCode'      => $aDataWhereDoc['FTBchCode'],
                'tDocNo'        => $aDataWhereDoc['FTXshDocNo'],
                'tStaMessg'     => 'Success Add Document.'
            );
        }
        echo json_encode($aReturnData);
    }





}