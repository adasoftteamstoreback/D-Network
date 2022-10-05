<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class delivery_controller extends MX_Controller {

    public $tRouteMenu  = 'docDLV/0/0';

    public function __construct(){
        $this->load->model('document/delivery/delivery_model');
        parent::__construct();
    }

    public function index($nDLVBrowseType, $tDLVBrowseOption){
        $aDataConfigView = array(
            'nDLVBrowseType'     => $nDLVBrowseType,
            'tDLVBrowseOption'   => $tDLVBrowseOption,
            'aAlwEvent'          => FCNaHCheckAlwFunc($this->tRouteMenu), 
            'vBtnSave'           => FCNaHBtnSaveActiveHTML($this->tRouteMenu), 
            'nOptDecimalShow'    => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'    => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/delivery/wDelivery', $aDataConfigView);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCDLVFormSearchList() {
        $aDataConfigView = array(
            'aAlwEvent'          => FCNaHCheckAlwFunc($this->tRouteMenu)
        );
        $this->load->view('document/delivery/wDeliveryFormSearchList', $aDataConfigView);
    }

    // แสดงตารางในหน้า List
    public function FSoCDLVDataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');

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
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 20,
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->delivery_model->FSaMDLVGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
                'aDataList'         => $aDataList
            );
            $tDLVViewDataTableList = $this->load->view('document/delivery/wDeliveryDataTable', $aConfigView, true);
            $aReturnData = array(
                'tDLVViewDataTableList' => $tDLVViewDataTableList,
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

    // เรียกหน้าเพิ่มข้อมูล
    public function FSoCDLVPageAdd() {
        try {
            // Clear Data 
            $this->delivery_model->FSxMDLVClearDataInDocTemp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Get Option Doc Save
            $nOptDocSave        = FCNnHGetOptionDocSave();

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'           => $nOptDecimalShow,
                'nOptDocSave'               => $nOptDocSave,
                'nStaShwAddress'            => $this->delivery_model->FSnMDLVGetConfigShwAddress(),
                'aDataDocHD'                => array('rtCode' => '800'),
                'aDataDocHDCst'             => array('rtCode' => '800'),
                'aDataDocAddr'              => array('rtCode' => '800'),
            );

            $tDLVViewPageAdd = $this->load->view('document/delivery/wDeliveryPageAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tDLVViewPageAdd'   => $tDLVViewPageAdd,
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

    // ข้อมูลใน Temp
    public function FSoCDLVPdtAdvTblLoadData() {
        try {
            $tDLVDocNo           = $this->input->post('ptDLVDocNo');
            $aDataWhere = array(
                'FTXthDocNo'            => $tDLVDocNo,
                'FTXthDocKey'           => 'TARTDoDT',
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );
            $aDataDocDTTemp     = $this->delivery_model->FSaMDLVGetDocDTTempListPage($aDataWhere);

            $aDataView = array(
                'aDataDocDTTemp'    => $aDataDocDTTemp,
                'nOptDecimalShow'   => FCNxHGetOptionDecimalShow()
            );
            $tDLVPdtAdvTableHtml = $this->load->view('document/delivery/wDeliveryPdtAdvTableData', $aDataView, true);
            $aReturnData = array(
                'tDLVPdtAdvTableHtml'   => $tDLVPdtAdvTableHtml,
                'nStaEvent'             => '1',
                'tStaMessg'             => "Fucntion Success Return View."
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
    public function FSoCDLVAddPdtIntoDocDTTemp() {
        try {
            $tDLVDocNo           = $this->input->post('tDLVDocNo');
            $tDLVOptionAddPdt    = $this->input->post('tDLVOptionAddPdt');
            $tBCHCode            = $this->input->post('tSelectBCH');
            $tDLVPdtData         = $this->input->post('tDLVPdtData');
            $aDLVPdtData         = json_decode($tDLVPdtData);

            $this->db->trans_begin();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aDLVPdtData); $nI++) {
                $tDLVPdtCode = $aDLVPdtData[$nI]->pnPdtCode;
                $tDLVBarCode = $aDLVPdtData[$nI]->ptBarCode;
                $tDLVPunCode = $aDLVPdtData[$nI]->ptPunCode;
                
                $aDataPdtParams = array(
                    'tDocNo'            => $tDLVDocNo,
                    'tBchCode'          => $tBCHCode,
                    'tPdtCode'          => $tDLVPdtCode,
                    'tBarCode'          => $tDLVBarCode,
                    'tPunCode'          => $tDLVPunCode,
                    'cPrice'            => 0,
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->session->userdata("tLangEdit"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TARTDoDT',
                    'tDLVOptionAddPdt'  => $tDLVOptionAddPdt,
                    'tDLVUsrCode'       => $this->session->userdata('tSesUsername')
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->delivery_model->FSaMDLVGetDataPdt($aDataPdtParams);
                $this->delivery_model->FSaMDLVInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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

    // Edit Inline สินค้า ลง Document DT Temp
    public function FSoCDLVEditPdtIntoDocDTTemp() {
        try {
            $tDLVBchCode         = $this->input->post('tDLVBchCode');
            $tDLVDocNo           = $this->input->post('tDLVDocNo');
            $nDLVSeqNo           = $this->input->post('nDLVSeqNo');
            $tDLVType            = $this->input->post('tDLVType');
            $tDLVValue           = $this->input->post('tDLVValue');
            $cDLVFactor          = $this->input->post('cDLVFactor');
            $tDLVSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tDLVBchCode'    => $tDLVBchCode,
                'tDLVDocNo'      => $tDLVDocNo,
                'nDLVSeqNo'      => $nDLVSeqNo,
                'tDLVSessionID'  => $tDLVSessionID,
                'tDocKey'        => 'TARTDoDT',
            );

            if( $tDLVType == 'Qty' ){
                $aDataUpdateDT = array(
                    'FCXtdQty'          => floatval($tDLVValue),
                    'FCXtdQtyAll'       => floatval($tDLVValue) * floatval($cDLVFactor)
                );
            }else{
                $aDataUpdateDT = array(
                    'FTXtdRmk'          => strval($tDLVValue)
                );
            }

            $this->db->trans_begin();
            $this->delivery_model->FSaMDLVUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

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

    // ลบสินค้า Temp (ตัวเดียว)
    public function FSvCDLVRemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tDLVDocNo'         => $this->input->post('tDocNo'),
                'tBchCode'          => $this->input->post('tBchCode'),
                'tPdtCode'          => $this->input->post('tPdtCode'),
                'nSeqNo'            => $this->input->post('nSeqNo'),
                'tDocKey'           => 'TARTDoDT',
                'tSessionID'        => $this->session->userdata('tSesSessionID'),
            );
            $this->delivery_model->FSnMDLVDelPdtInDTTmp($aDataWhere);

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

    // [เอกสารอ้างอิง] ข้อมูลเอกสารอ้างอิง table
    public function FSoCDLVPageHDDocRefList(){
        try{
            $tDocNo = ( !empty($this->input->post('ptDocNo')) ? $this->input->post('ptDocNo') : '');

            $aDataWhere = [
                'tTableHDDocRef'    => 'TARTDoHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TARTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aDataDocHDRef = $this->delivery_model->FSaMDLVGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/delivery/refintdocument/wDeliveryRefDocList', $aDataConfig, true);
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

    // [เอกสารอ้างอิง] เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDLVCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $tRefDoc   = $this->input->post('tRefDoc');
        $aDataParam = array(
            'tBCHCode'  => $tBCHCode,
            'tBCHName'  => $tBCHName,
            'tRefDoc'   => $tRefDoc
        );

        $this->load->view('document/delivery/refintdocument/wDeliveryRefDoc', $aDataParam);
    }

    // [เอกสารอ้างอิง] เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCDLVCallRefIntDocDataTable(){
        $nPage                   = $this->input->post('nDLVRefIntPageCurrent');
        $tDLVRefIntBchCode       = $this->input->post('tDLVRefIntBchCode');
        $tDLVRefIntDocNo         = $this->input->post('tDLVRefIntDocNo');
        $tDLVRefIntDocDateFrm    = $this->input->post('tDLVRefIntDocDateFrm');
        $tDLVRefIntDocDateTo     = $this->input->post('tDLVRefIntDocDateTo');
        $tDLVRefIntStaDoc        = $this->input->post('tDLVRefIntStaDoc');
        $tDLVRefIntIntRefDoc     = $this->input->post('tDLVRefIntIntRefDoc');
        $tCstCode                = $this->input->post('tCstCode');

        if ($nPage == '' || $nPage == null || $nPage == "NaN") {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nDLVRefIntPageCurrent');
        }
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tDLVRefIntBchCode'      => $tDLVRefIntBchCode,
            'tDLVRefIntDocNo'        => $tDLVRefIntDocNo,
            'tDLVRefIntDocDateFrm'   => $tDLVRefIntDocDateFrm,
            'tDLVRefIntDocDateTo'    => $tDLVRefIntDocDateTo,
            'tDLVRefIntStaDoc'       => $tDLVRefIntStaDoc,
            'tDLVRefIntIntRefDoc'    => $tDLVRefIntIntRefDoc,
            'tCstCode'               => $tCstCode
        );

        $aDataCondition = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $aDataParamFilter
        );

        //ใบขาย
        $aDataParam = $this->delivery_model->FSoMDLVCallRefIntDoc_SALE_DataTable($aDataCondition);

        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );
        $this->load->view('document/delivery/refintdocument/wDeliveryRefDocDataTable', $aConfigView);
    }

    // [เอกสารอ้างอิง] เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDLVCallRefIntDocDetailDataTable(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition     = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );

        //ใบขาย
        $aDataParam = $this->delivery_model->FSoMDLVCallRefIntDocDT_SALE_DataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/delivery/refintdocument/wDeliveryRefDocDetailDataTable', $aConfigView);
    }

    // [เอกสารอ้างอิง] เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCDLVCallRefIntDocInsertDTToTemp(){
        $tDLVDocNo          =  $this->input->post('tDLVDocNo');
        $tDLVFrmBchCode     =  $this->input->post('tDLVFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
        $tInsertOrUpdateRow = $this->input->post('tInsertOrUpdateRow'); 

        $aDataParam = array(
            'tDLVDocNo'          => $tDLVDocNo,
            'tDLVFrmBchCode'     => $tDLVFrmBchCode,
            'tRefIntDocNo'       => $tRefIntDocNo,
            'tRefIntBchCode'     => $tRefIntBchCode,
            'aSeqNo'             => $aSeqNo,
            'tInsertOrUpdateRow' => $tInsertOrUpdateRow
        );

        //ใบขาย
        $aDataResult = $this->delivery_model->FSoMDLVCallRefIntDocInsert_SALE_DTToTemp($aDataParam);

        return $aDataResult;
    }

    // [เอกสารอ้างอิง] เพิ่ม หรือ เเก้ไข
    public function FSoCDLVEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthDocKey'       => 'TARTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData = $this->delivery_model->FSaMDLVAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // [เอกสารอ้างอิง] ลบ
    public function FSoCDLVEventDelHDDocRef(){
        try {
            $aData = [
                'FTXshDocNo'        => $this->input->post('ptDocNo'),
                'FTXshRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXshDocKey'       => 'TARTDoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->delivery_model->FSaMDLVDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ฟังก์ชั่นเช็คว่ามีสินค้าไหม
    public function FSoCDLVChkHavePdtForDocDTTemp() {
        try {
            $tDLVDocNo      = $this->input->post("ptDLVDocNo");
            $tDLVSessionID  = $this->input->post('tDLVSesSessionID');
            $aDataWhere     = array(
                'FTXthDocNo'    => $tDLVDocNo,
                'FTXthDocKey'   => 'TARTDoDT',
                'FTSessionID'   => $tDLVSessionID
            );
            $nCountPdtInDocDTTemp = $this->delivery_model->FSnMDLVChkPdtInDocDTTemp($aDataWhere);

            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/delivery/delivery', 'tDLVPleaseSeletedPDTIntoTable')
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

    // ฟังก์ชั่นเพิ่มข้อมูล ในฐานข้อมูล
    public function FSoCDLVAddEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDLVAutoGenCode    = (isset($aDataDocument['ocbDLVStaAutoGenCode'])) ? 1 : 0;
            $tDLVDocNo          = (isset($aDataDocument['oetDLVDocNo'])) ? $aDataDocument['oetDLVDocNo'] : '';
            $tDLVDocDate        = $aDataDocument['oetDLVDocDate'] . " " . $aDataDocument['oetDLVDocTime'];

            // Check Auto GenCode Document
            if ($tDLVAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TARTDoHD',
                    "tDocType"    => '1',
                    "tBchCode"    => $aDataDocument['oetDLVBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );

                $aAutogen    = FCNaHAUTGenDocNo($aStoreParam);
                $tDLVDocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $tDLVDocNo = $tDLVDocNo;
            }

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TARTDoHD',
                'tTableDT'          => 'TARTDoDT',
                'tTableHDCst'       => 'TARTDoHDCst',
                'tTableStaGen'      => '1'
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                'FTXshDocNo'        => $tDLVDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                'FNXshDocType'      => 1,
                'FDXshDocDate'      => (!empty($tDLVDocDate)) ? $tDLVDocDate : NULL,
                'FTUsrCode'         => $aDataDocument['oetDLVCstDeliverlyCode'],
                'FTCstCode'         => $aDataDocument['oetDLVCstCode'],
                'FTXshRmk'          => $aDataDocument['otaDLVFrmInfoOthRmk'],
                'FTXshStaDoc'       => $aDataDocument['ohdDLVStaDoc'],
                'FTXshStaApv'       => !empty($aDataDocument['ohdDLVStaApv']) ? $aDataDocument['ohdDLVStaApv'] : NULL,
                'FTXshStaDelMQ'     => NULL,
                'FTXshStaPrcStk'    => NULL,
                'FNXshStaDocAct'    => (isset($aDataDocument['ocbDLVFrmInfoOthStaDocAct'])) ? 1 : 0,
                'FNXshStaRef'       => $aDataDocument['ocmDLVFrmInfoOthRef'],
                'FTXshAgnFrm'       => $aDataDocument['oetDLVFrmAgnCode'],
                'FTXshBchFrm'       => $aDataDocument['oetDLVFrmBchCode'],
                'FTXshAgnTo'        => $aDataDocument['oetDLVToAgnCode'],
                'FTXshBchTo'        => $aDataDocument['oetDLVToBchCode'],
                'FDXshDeliveryDate' => $aDataDocument['oetDLVDateSent'],
                'FTXshShipVia'      => $aDataDocument['oetDLVNumberCar']
            );

            // Array Data HD CST
            $aDataMasterHDCST = array(
                'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                'FTCstCode'         => $aDataDocument['oetDLVCstCode'],
                'FTXshCtrName'      => $aDataDocument['oetDLVCstName'],
                'FNXshAddrShip'     => $aDataDocument['ohdDLVShipAddSeqNo'],
            );

            $this->db->trans_begin();

            // [Update] DocNo -> Temp
            $this->delivery_model->FSxMDLVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Move] HDDocRef -> HDDocRef
            $this->delivery_model->FSxMDLVMoveHDRefTmpToHDRef($aDataWhere);

            // [ADD] Document HD
            $this->delivery_model->FSxMDLVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [ADD] Document HD CST
            $this->delivery_model->FSxMDLVAddUpdateHDCST($aDataMasterHDCST, $aDataWhere, $aTableAddUpdate);

            // [Move] Doc DTTemp To DT
            $this->delivery_model->FSaMDLVMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXshDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.'
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
    public function FSvCDLVEditPage(){
        try {
            $ptDocumentNumber = $this->input->post('ptDLVDocNo');

            // Clear Data 
            $this->delivery_model->FSxMDLVClearDataInDocTemp();

            // Array Data Where Get
            $aDataWhere = array(
                'FTXshDocNo'    => $ptDocumentNumber,
                'FTXshDocKey'   => 'TARTDoDT',
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );

            $nOptDecimalShow   = FCNxHGetOptionDecimalShow();

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->delivery_model->FSaMDLVGetDataDocHD($aDataWhere);

            // Get Data Detail HDCst + Ship
            $aDataDocHDCst      = $this->delivery_model->FSaMDLVGetDataDocHDCST($aDataWhere);

            // Get ที่อยู่สำหรับจัดส่ง 
            $aDataDocAddr       =  $this->delivery_model->FSaMDLVGetDataAddress($aDataWhere);

            // [Move] Data DT To DTTemp
            $this->delivery_model->FSxMDLVMoveDTToDTTemp($aDataWhere);

            // [Move] Data HDDocRef To HDRefTemp
            $this->delivery_model->FSxMDLVMoveHDRefToHDRefTemp($aDataWhere);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );

            } else {
                $this->db->trans_commit();

                $aDataConfigViewEdit = array(
                    'nOptDecimalShow'           => $nOptDecimalShow,
                    'nOptDocSave'               => $nOptDecimalShow,
                    'nStaShwAddress'            => $this->delivery_model->FSnMDLVGetConfigShwAddress(),
                    'aDataDocHD'                => $aDataDocHD,
                    'aDataDocHDCst'             => $aDataDocHDCst,
                    'aDataDocAddr'              => $aDataDocAddr
                );
                $tViewPageEdit           = $this->load->view('document/delivery/wDeliveryPageAdd',$aDataConfigViewEdit,true);
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

    // ฟังก์ชั่นแก้ไขข้อมูล ในฐานข้อมูล
    public function FSoCDLVEditEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDLVDocNo          = (isset($aDataDocument['oetDLVDocNo'])) ? $aDataDocument['oetDLVDocNo'] : '';
            $tDLVDocDate        = $aDataDocument['oetDLVDocDate'] . " " . $aDataDocument['oetDLVDocTime'];

            if($aDataDocument['ohdDLVStaApv'] == 1 || $aDataDocument['ohdDLVStaDoc'] == 3 ){ //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว

                // Array Data update
                $aDataUpdate = array(
                    'FTBchCode'             => $aDataDocument['oetDLVBchCode'],
                    'FTXshDocNo'            => $tDLVDocNo,
                    'FTXshRmk'              => $aDataDocument['otaDLVFrmInfoOthRmk'],
                );

                $this->db->trans_begin();

                // [Update] update หมายเหตุ
                $this->delivery_model->FSaMDLVUpdateRmk($aDataUpdate);

            } else {

                // Array Data Table Document
                $aTableAddUpdate = array(
                    'tTableHD'          => 'TARTDoHD',
                    'tTableDT'          => 'TARTDoDT',
                    'tTableHDCst'       => 'TARTDoHDCst',
                );

                // Array Data Where Insert
                $aDataWhere = array(
                    'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                    'FTXshDocNo'        => $tDLVDocNo,
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTSessionID'       => $this->session->userdata('tSesSessionID')
                );

                // Array Data HD Master
                $aDataMaster = array(
                    'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                    'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                    'FNXshDocType'      => 1,
                    'FDXshDocDate'      => (!empty($tDLVDocDate)) ? $tDLVDocDate : NULL,
                    'FTUsrCode'         => $aDataDocument['oetDLVCstDeliverlyCode'],
                    'FTCstCode'         => $aDataDocument['oetDLVCstCode'],
                    'FTXshRmk'          => $aDataDocument['otaDLVFrmInfoOthRmk'],
                    'FTXshStaDoc'       => $aDataDocument['ohdDLVStaDoc'],
                    'FTXshStaApv'       => !empty($aDataDocument['ohdDLVStaApv']) ? $aDataDocument['ohdDLVStaApv'] : NULL,
                    'FNXshStaDocAct'    => (isset($aDataDocument['ocbDLVFrmInfoOthStaDocAct'])) ? 1 : 0,
                    'FNXshStaRef'       => $aDataDocument['ocmDLVFrmInfoOthRef'],
                    'FTXshAgnFrm'       => $aDataDocument['oetDLVFrmAgnCode'],
                    'FTXshBchFrm'       => $aDataDocument['oetDLVFrmBchCode'],
                    'FTXshAgnTo'        => $aDataDocument['oetDLVToAgnCode'],
                    'FTXshBchTo'        => $aDataDocument['oetDLVToBchCode'],
                    'FDXshDeliveryDate' => $aDataDocument['oetDLVDateSent'],
                    'FTXshShipVia'      => $aDataDocument['oetDLVNumberCar']
                );

                // Array Data HD CST
                $aDataMasterHDCST = array(
                    'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                    'FTBchCode'         => $aDataDocument['oetDLVBchCode'],
                    'FTCstCode'         => $aDataDocument['oetDLVCstCode'],
                    'FTXshCtrName'      => $aDataDocument['oetDLVCstName'],
                    'FNXshAddrShip'     => $aDataDocument['ohdDLVShipAddSeqNo'],
                );
                
                $this->db->trans_begin();

                // [Update] DocNo -> Temp
                $this->delivery_model->FSxMDLVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

                // [Move] HDDocRef -> HDDocRef
                $this->delivery_model->FSxMDLVMoveHDRefTmpToHDRef($aDataWhere);

                // [ADD] Document HD
                $this->delivery_model->FSxMDLVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

                // [ADD] Document HD CST
                $this->delivery_model->FSxMDLVAddUpdateHDCST($aDataMasterHDCST, $aDataWhere, $aTableAddUpdate);

                // [Move] Doc DTTemp To DT
                $this->delivery_model->FSaMDLVMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
            }

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Edit Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $tDLVDocNo,
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Edit Document.'
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

    // ยกเลิกเอกสาร
    public function FSvCDLVCancelDocument() {
        try {
            $this->db->trans_begin();

            $aDataUpdate = array(
                'tDocNo'        => $this->input->post('ptDLVDocNo'),
                'tDocType'      => 1,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
            $this->delivery_model->FSxMDLVCancelDocument($aDataUpdate);

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => $this->db->error()['message']
                );
            }else{
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => "Cancel Success."
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

    // [ลบข้อมูล] เอกสาร HD
    public function FSoCDLVDeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode   = $this->input->post('tBchCode');

            $aDataMaster = array(
                'tDataDocNo'    => $tDataDocNo,
                'tBchCode'      => $tBchCode
            );
            $aResDelDoc = $this->delivery_model->FSnMDLVDelDocument($aDataMaster);
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

    // อนุมัติเอกสาร
    public function FSoCDLVApproveEvent(){
        try{
            $aDataUpdate = array(
                'FTBchCode'         => $this->input->post('tBchCode'),
                'FTXshDocNo'        => $this->input->post('tDocNo'),
                'FTXshStaApv'       => 1,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s')
            );

            $this->db->trans_begin();
            $this->delivery_model->FSxMDLVApproveDocument($aDataUpdate);

            if( $this->db->trans_status() === FALSE ){
                $this->db->trans_rollback();
                $aReturnData     = array(
                    'nStaEvent'    => '905',
                    'tStaMessg'    => $this->db->error()['message'],
                );
            }else{
                $this->db->trans_commit();
                $aReturnData     = array(
                    'nStaEvent'    => '1',
                    'tStaMessg'    => 'Approve Success.',
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
}
