<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Depositboxreservation_controller extends MX_Controller {

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/depositboxreservation/depositboxreservation_model');
        parent::__construct();

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }
    
    public $tRouteMenu  = 'docDBR/0/0';
    
    public function index($nDBRBrowseType, $tDBRBrowseOption){
        $aParams    = [
            'tDocNo'        => $this->input->post('tDocNo'),
            'tBchCode'      => $this->input->post('tBchCode'),
            'tAgnCode'      => $this->input->post('tAgnCode'),
            'tCheckJump'    => $this->input->post('ptTypeJump'),
            'tCheckType'    => $this->input->post('tType'),
            'tCheckBackTo'  => $this->input->post('tBackTo'),
        ];

        $aDataConfigView    = array(
            'nDBRBrowseType'     => $nDBRBrowseType,
            'tDBRBrowseOption'   => $tDBRBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParams'           => $aParams ,
        );
        $this->load->view('document/depositboxreservation/wDepositBoxReservation', $aDataConfigView);
        unset($aParams);
        unset($aDataConfigView);
        unset($nDBRBrowseType,$tDBRBrowseOption);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCDBRFormSearchList() {
        $this->load->view('document/depositboxreservation/wDepositBoxReservationFormSearchList');
    }

    // แสดงตารางในหน้า List
    public function FSoCDBRDataTable() {
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
            $aDataList   = $this->depositboxreservation_model->FSaMDBRGetDataTableList($aDataCondition);
            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tDBRViewDataTableList = $this->load->view('document/depositboxreservation/wDepositBoxReservationDataTable', $aConfigView, true);
            $aReturnData = array(
                'tDBRViewDataTableList' => $tDBRViewDataTableList,
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
        unset($tDBRViewDataTableList);
        echo json_encode($aReturnData);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDBRCallRefIntDoc(){
        $tDocType   = $this->input->post('tDocType');
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName,
            'tDocType' => $tDocType,
        );
        $this->load->view('document/depositboxreservation/refintdocument/wDepositBoxReservationRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCDBRCallRefIntDocDataTable(){
        $nPage                  = $this->input->post('nDBRRefIntPageCurrent');
        $tDBRRefIntBchCode       = $this->input->post('tDBRRefIntBchCode');
        $tDBRRefIntDocNo         = $this->input->post('tDBRRefIntDocNo');
        $tDBRRefIntDocDateFrm    = $this->input->post('tDBRRefIntDocDateFrm');
        $tDBRRefIntDocDateTo     = $this->input->post('tDBRRefIntDocDateTo');
        $tDBRRefIntStaDoc        = $this->input->post('tDBRRefIntStaDoc');
        $tDBRDocType             = $this->input->post('tDBRDocType');
        $tDBRSplCode             = $this->input->post('tDBRSplCode');
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nDBRRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tDBRRefIntBchCode'      => $tDBRRefIntBchCode,
            'tDBRRefIntDocNo'        => $tDBRRefIntDocNo,
            'tDBRRefIntDocDateFrm'   => $tDBRRefIntDocDateFrm,
            'tDBRRefIntDocDateTo'    => $tDBRRefIntDocDateTo,
            'tDBRRefIntStaDoc'       => $tDBRRefIntStaDoc,
            'tDBRSplCode'            => $tDBRSplCode
        );
        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'nPage'     => $nPage,
            'nRow'      => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        if ($tDBRDocType == 1) {
            $aDataParam = $this->depositboxreservation_model->FSoMDBRCallRefSOIntDocDataTable($aDataCondition);
        }
        $aConfigView = array(
            'nPage'         => $nPage,
            'aDataList'     => $aDataParam,
            'tDBRDocType'    => $tDBRDocType
        );
        $this->load->view('document/depositboxreservation/refintdocument/wDepositBoxReservationRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDBRCallRefIntDocDetailDataTable(){
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
            $aDataParam = $this->depositboxreservation_model->FSoMDBRCallRefIntDocDTDataTable($aDataCondition);
        }
        $aConfigView    = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/depositboxreservation/refintdocument/wDepositBoxReservationRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCDBRCallRefIntDocInsertDTToTemp(){
        $tDBRDocNo           =  $this->input->post('tDBRDocNo');
        $tDBRFrmBchCode      =  $this->input->post('tDBRFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
        $tDoctype           =  $this->input->post('tDoctype');
        $tDBROptionAddPdt    =  $this->input->post('tDBROptionAddPdt');
        $aDataParam         = array(
            'tDBRDocNo'          => $tDBRDocNo,
            'tDBRFrmBchCode'     => $tDBRFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'TRTTBookHD',
            'tDBROptionAddPdt'   => $tDBROptionAddPdt,
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
        );
        if ($tDoctype == 1) {
            $tDocType       = 'SO';
            $aDataResult    = $this->depositboxreservation_model->FSoMDBRCallRefIntDocInsertDTToTemp($aDataParam, $tDocType);
        }
        return  $aDataResult;
    }


    // เอารายการจากการ Jump ลง temp dt
    public function FSoCDBRCallRefIntDocInsertDTToTempByJump(){
        $tDBRDocNo           =  $this->input->post('tDBRDocNo');
        $tDBRFrmBchCode      =  $this->input->post('tDBRFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $tDoctype           =  $this->input->post('tDoctype');
        $tDBROptionAddPdt    =  $this->input->post('tDBROptionAddPdt');

        $aDataParam         = array(
            'tDBRDocNo'          => $tDBRDocNo,
            'tDBRFrmBchCode'     => $tDBRFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'tDocKey'           => 'TRTTBookHD',
            'tDBROptionAddPdt'   => $tDBROptionAddPdt,
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
        );
            $tDocType       = 'SO';
            $aItems = $this->depositboxreservation_model->FSoMDBRCallRefIntDocInsertDTToTempByJump($aDataParam, $tDocType);
        echo json_encode($aItems);
    }

    public function FSoCDBRClearTempWhenChangeData(){
        try {
            $tDBRDocNo           = $this->input->post('tDBRDocNo');
            $aWhereClearTemp    = [
                'FTXthDocNo'    => $tDBRDocNo,
                'FTXthDocKey'   => 'TRTTBookHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->depositboxreservation_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->depositboxreservation_model->FSxMDBRClearDataInDocTemp($aWhereClearTemp);
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
    public function FSoCDBRPageAdd() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp    = [
                'FTXthDocNo'    => '',
                'FTXthDocKey'   => 'TRTTBookHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];

            $this->depositboxreservation_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->depositboxreservation_model->FSxMDBRClearDataInDocTemp($aWhereClearTemp);
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $nOptDocSave        = get_cookie('tOptDocSave');
            $nOptScanSku        = get_cookie('tOptScanSku');

            //ถ้าเป็นแบบแฟรนไซด์
            if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){
                $aSPLConfig     = $this->depositboxreservation_model->FSxMDBRFindSPLByConfig();
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
            $tDBRViewPageAdd = $this->load->view('document/depositboxreservation/wDepositBoxReservationPageAdd', $aDataConfigViewAdd, true);
            $aReturnData    = array(
                'tDBRViewPageAdd'    => $tDBRViewPageAdd,
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
    public function FSoCDBRPdtAdvTblLoadData() {
        try {
            $bStaSession    =   $this->session->userdata('bSesLogIn');
            $nLangEdit = $this->session->userdata("tLangEdit");
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData = array(
                    'checksession' => 'expire'
                );
                echo json_encode($aReturnData);
            }
            $tDBRDocNo           = $this->input->post('ptDBRDocNo');
            $tDBRStaApv          = $this->input->post('ptDBRStaApv');
            $tDBRStaDoc          = $this->input->post('ptDBRStaDoc');
            $nDBRPageCurrent     = $this->input->post('pnDBRPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tDBRPdtCode         = $this->input->post('ptDBRPdtCode');
            $tDBRPunCode         = $this->input->post('ptDBRPunCode');
            //Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTBchCode'             => $this->input->post('tSelectBCH'),
                'FTXthDocNo'            => $tDBRDocNo,
                'FTXthDocKey'           => 'TRTTBookDT',
                'nPage'                 => $nDBRPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
                'FNLngID'               => $nLangEdit
            );
            $aDataDocDTTemp     = $this->depositboxreservation_model->FSaMDBRGetDocDTTempListPage($aDataWhere);
            // echo "<pre>";
            // print_r($aDataDocDTTemp);
            // echo "</pre>";

            $aDataView          = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tDBRStaApv'         => $tDBRStaApv,
                'tDBRStaDoc'         => $tDBRStaDoc,
                'tDBRPdtCode'        => $tDBRPdtCode,
                'tDBRPunCode'        => $tDBRPunCode,
                'nPage'             => $nDBRPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );
            $tDBRPdtAdvTableHtml = $this->load->view('document/depositboxreservation/wDepositBoxReservationPdtAdvTableData', $aDataView, true);
            $aReturnData    = array(
                'tDBRPdtAdvTableHtml'    => $tDBRPdtAdvTableHtml,
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
    public function FSoCDBRAddPdtIntoDocDTTemp() {
        try {
            $tDBRDocNo           = $this->input->post('tDBRDocNo');
            $tDBRBchCode         = $this->input->post('tSelectBCH');
            $tDBROptionAddPdt    = $this->input->post('tDBROptionAddPdt');
            $tDBRPdtData         = $this->input->post('tDBRPdtData');
            $aDBRPdtData         = json_decode($tDBRPdtData);
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aDBRPdtData); $nI++) {
                $tDBRPdtCode = $aDBRPdtData[$nI]->pnPdtCode;
                $tDBRBarCode = $aDBRPdtData[$nI]->ptBarCode;
                $tDBRPunCode = $aDBRPdtData[$nI]->ptPunCode;
                $cDBRPrice       = $aDBRPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tDBRDocNo,
                    'tBchCode'          => $tDBRBchCode,
                    'tPdtCode'          => $tDBRPdtCode,
                    'tBarCode'          => $tDBRBarCode,
                    'tPunCode'          => $tDBRPunCode,
                    'cPrice'            => str_replace(",","",$cDBRPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdDBRLangEdit"),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TRTTBookDT',
                    'tDBROptionAddPdt'   => $tDBROptionAddPdt,
                    'tDBRUsrCode'        => $this->input->post('ohdDBRUsrCode'),
                    'nVatRate'          => $nVatRate,
                    'nVatCode'          => $nVatCode
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->depositboxreservation_model->FSaMDBRGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $this->depositboxreservation_model->FSaMDBRInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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
    public function FSvCDBRRemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tDBRDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TRTTBookDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );
            $this->depositboxreservation_model->FSnMDBRDelPdtInDTTmp($aDataWhere);
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
    public function FSvCDBRRemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tDBRDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => str_replace(",","','",$this->input->post('tPdtCode')),
                'nSeqNo'   => str_replace(",","','",$this->input->post('nSeqNo')),
                'tDocKey'  => 'TRTTBookDT',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $this->depositboxreservation_model->FSnMDBRDelMultiPdtInDTTmp($aDataWhere);
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
    public function FSoCDBREditPdtIntoDocDTTemp() {
        try {
            $tDBRBchCode         = $this->input->post('tDBRBchCode');
            $tDBRDocNo           = $this->input->post('tDBRDocNo');
            $nDBRSeqNo           = $this->input->post('nDBRSeqNo');
            $tDBRSessionID       = $this->session->userdata('tSesSessionID');
            $nBrowseType         = $this->input->post('nBrowseType');
            
            $aDataWhere     = array(
                'tDBRBchCode'    => $tDBRBchCode,
                'tDBRDocNo'      => $tDBRDocNo,
                'nDBRSeqNo'      => $nDBRSeqNo,
                'tDBRSessionID'  => $tDBRSessionID,
                'tDocKey'       => 'TRTTBookDT',
            );

            if ($nBrowseType == 1) {
                $aDataUpdateDT  = array(
                    'FTPosCode'      => $this->input->post('nQty'),
                    'FTXtdPdtName'   => $this->input->post('FTXtdPdtName'),
                    'FTLayNo'        => '0',
                    'FTShpCode'      => $this->input->post('tShpCode'),
                );
            }elseif ($nBrowseType == 2) {
                $aDataUpdateDT  = array(
                    'FTPosCode'      => $this->input->post('tBox'),
                    'FTLayNo'      => $this->input->post('nQty'),
                    'FTShpCode'      => $this->input->post('tShpCode'),
                    'FTXtdPdtName'  => $this->input->post('FTXtdPdtName'),
                );
            }else{
                $aDataUpdateDT  = array(
                    'FCXtdQty'      => $this->input->post('nQty'),
                    'FTXtdRmkInRow' => $this->input->post('tRmk'),
                    'FTXtdPdtName'  => $this->input->post('FTXtdPdtName'),
                );
            }
            

            $this->db->trans_begin();
            $this->depositboxreservation_model->FSaMDBRUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
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
    public function FSoCDBRChkHavePdtForDocDTTemp() {
        try {
            $tDBRDocNo       = $this->input->post("ptDBRDocNo");
            $tDBRSessionID   = $this->input->post('tDBRSesSessionID');
            $aDataWhere     = array(
                'FTXthDocNo'    => $tDBRDocNo,
                'FTXthDocKey'   => 'TRTTBookDT',
                'FTSessionID'   => $tDBRSessionID
            );
            $nCountPdtInDocDTTemp   = $this->depositboxreservation_model->FSnMDBRChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData    = array(
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData    = array(
                    'nStaReturn'    => '800',
                    'tStaMessg'     => language('document/depositboxreservation/depositboxreservation', 'tDBRPleaseSeletedPDTIntoTable')
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
    public function FSoCDBRAddEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDBRAutoGenCode     = (isset($aDataDocument['ocbDBRStaAutoGenCode'])) ? 1 : 0;
            $tDBRDocNo           = (isset($aDataDocument['oetDBRDocNo'])) ? $aDataDocument['oetDBRDocNo'] : '';
            $tDBRDocDate         = $aDataDocument['oetDBRDocDate'] . " " . $aDataDocument['oetDBRDocTime'];
            $tDBRStaDocAct       = (isset($aDataDocument['ocbDBRFrmInfoOthStaDocAct'])) ? 1 : 0;
            $nDocType           = 1;
            $aClearDTParams = [
                'FTXthDocNo'    => $tDBRDocNo,
                'FTXthDocKey'   => 'TRTTBookDT',
                'FTSessionID'   => $this->input->post('ohdSesSessionID'),
            ];

            // Check Auto GenCode Document
            if ($tDBRAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TRTTBookHD',                           
                    "tDocType"    => '1',                                          
                    "tBchCode"    => $aDataDocument['oetDBRFrmBchCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
                $tDBRDocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $tDBRDocNo = $tDBRDocNo;
            }
            
            // Array Data Table Document
            $aTableAddUpdate    = array(
                'tTableHD'              => 'TRTTBookHD',
                'tTableDT'              => 'TRTTBookDT',
                'tTableDTSL'            => 'TRTTBookDTSL',
                'tTableStaGen'          => 11,
                'tTableRefDBR'          => 'TRTTBookHDDocRef',
                'tTableRefSO'           => 'TARTSoHDDocRef',
                'tTableRefABB'          => 'TPSTSalHDDocRef',
                'tTableRefFULLTAX'      => 'TPSTTaxHDDocRef'
            );
            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['oetDBRFrmBchCode'],
                'FTXshDocNo'        => $tDBRDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdDBRUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdDBRUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXshUsrApv'       => ''
            );
            // Array Data HD Master
            $aDataMaster    = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FNXshDocType'      => 1,
                'FDXshDocDate'      => (!empty($tDBRDocDate)) ? $tDBRDocDate : NULL,
                'FTUsrCode'         => $aDataDocument['oetDBRUsrCode'],
                'FDXshBookDate'     => $aDataDocument['oetDBRDepositDate'] . " " . $aDataDocument['oetDBRDepositTime'],
                'FTCstCode'         => $aDataDocument['oetDBRCstCode'],
                'FNXshDocPrint'     => $aDataDocument['ocmDBRFrmInfoOthDocPrint'],
                'FTXshRmk'          => $aDataDocument['otaDBRFrmInfoOthRmk'],
                'FTXshStaDoc'       => $aDataDocument['ohdDBRStaDoc'],
                'FTXshStaApv'       => !empty($aDataDocument['ohdDBRStaApv']) ? $aDataDocument['ohdDBRStaApv'] : NULL,
                'FTXshStaPrcStk'    => $aDataDocument['ohdDBRStaPrcStk'],
                'FNXshStaDocAct'    => $tDBRStaDocAct,
                'FNXshStaRef'       => $aDataDocument['ocmDBRFrmInfoOthRef'],
                'FTXshFrmLogin'     => '',
                'FTXshFrmLoginPwd'  => '',
                'FTXshToLogin'      => '',
                'FTXshToLoginPwd'   => '',
            );

            $this->db->trans_begin();
            // // Add Update Document HD
            $this->depositboxreservation_model->FSxMDBRAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // // [Update] DocNo -> Temp
            $this->depositboxreservation_model->FSxMDBRAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            // // // Move Doc DTTemp To DT
            $this->depositboxreservation_model->FSaMDBRMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            $this->depositboxreservation_model->FSxMDBRMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
            $this->depositboxreservation_model->FSxMDBRMoveDTTempToDTSL($aDataWhere, $aTableAddUpdate);

            // /// BookCst
            $this->depositboxreservation_model->FSaMDBRMoveSoCstToBook($aDataWhere, $aTableAddUpdate);
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tEventName'    => 'บันทึกใบจองช่องฝาก',
                    'nLogCode'      => '900',
                    'nLogLevel'     => '4'
                );
            } else {
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXshDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.',
                    //เพิ่มใหม่
                    'tLogType'      => 'INFO',
                    'tEventName'    => 'บันทึกใบจองช่องฝาก',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '0'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tEventName'    => 'บันทึกใบจองช่องฝาก',
                'nLogCode'      => '900',
                'nLogLevel'     => '4'
            );
        }
        echo json_encode($aReturnData);
    }

    //แก้ไขเอกสาร
    public function FSoCDBREditEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDBRDocNo           = (isset($aDataDocument['oetDBRDocNo'])) ? $aDataDocument['oetDBRDocNo'] : '';
            $tDBRDocDate         = $aDataDocument['oetDBRDocDate'] . " " . $aDataDocument['oetDBRDocTime'];
            $tDBRStaDocAct       = (isset($aDataDocument['ocbDBRFrmInfoOthStaDocAct'])) ? 1 : 0;
            $nDBRSubmitWithImp   = $aDataDocument['ohdDBRSubmitWithImp'];
            $nDocType           = 1;
            // Get Data Comp.
            $aClearDTParams = [
                'FTXthDocNo'     => $tDBRDocNo,
                'FTXthDocKey'    => 'TRTTBookDT',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nDBRSubmitWithImp==1){
                $this->depositboxreservation_model->FSxMDBRClearDataInDocTempForImp($aClearDTParams);
            }

            $aTableAddUpdate    = array(
                'tTableHD'      => 'TRTTBookHD',
                'tTableDT'      => 'TRTTBookDT',
                'tTableDTSL'      => 'TRTTBookDTSL',
                'tTableStaGen'  => 11,
                'tTableRefDBR'  => 'TRTTBookHDDocRef',
                'tTableRefSO'   => 'TARTSoHDDocRef'
            );
            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['oetDBRFrmBchCode'],
                'FTXshDocNo'        => $tDBRDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdDBRUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdDBRUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXshUsrApv'       => ''
            );
            // Array Data HD Master
            $aDataMaster    = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FNXshDocType'      => 1,
                'FDXshDocDate'      => (!empty($tDBRDocDate)) ? $tDBRDocDate : NULL,
                'FTUsrCode'         => $aDataDocument['oetDBRUsrCode'],
                'FDXshBookDate'     => $aDataDocument['oetDBRDepositDate'] . " " . $aDataDocument['oetDBRDepositTime'],
                'FTCstCode'         => $aDataDocument['oetDBRCstCode'],
                'FNXshDocPrint'     => $aDataDocument['ocmDBRFrmInfoOthDocPrint'],
                'FTXshRmk'          => $aDataDocument['otaDBRFrmInfoOthRmk'],
                'FTXshStaDoc'       => $aDataDocument['ohdDBRStaDoc'],
                'FTXshStaApv'       => !empty($aDataDocument['ohdDBRStaApv']) ? $aDataDocument['ohdDBRStaApv'] : NULL,
                'FTXshStaPrcStk'    => $aDataDocument['ohdDBRStaPrcStk'],
                'FNXshStaDocAct'    => $tDBRStaDocAct,
                'FNXshStaRef'       => $aDataDocument['ocmDBRFrmInfoOthRef'],
                'FTXshFrmLogin'     => '',
                'FTXshFrmLoginPwd'  => '',
                'FTXshToLogin'      => '',
                'FTXshToLoginPwd'   => '',
            );
            
            $this->db->trans_begin();
            // // Add Update Document HD
            $this->depositboxreservation_model->FSxMDBRAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // [Update] DocNo -> Temp
            $this->depositboxreservation_model->FSxMDBRAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            // // Move Doc DTTemp To DT
            $this->depositboxreservation_model->FSaMDBRMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            /// BookCst
            // $this->depositboxreservation_model->FSaMDBRMoveSoCstToBook($aDataWhere, $aTableAddUpdate);

            $this->depositboxreservation_model->FSxMDBRMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate, $nDocType);
            $this->depositboxreservation_model->FSxMDBRMoveDTTempToDTSL($aDataWhere, $aTableAddUpdate);
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Edit Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tEventName'    => 'บันทึกใบจองช่องฝาก',
                    'nLogCode'      => '900',
                    'nLogLevel'     => '4'
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXshDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Edit Document.',
                    'tLogType'      => 'INFO',
                    'tEventName'    => 'แก้ไขและบันทึกใบจองช่องฝาก',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '0'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tEventName'    => 'แก้ไขและบันทึกใบจองช่องฝาก',
                'nLogCode'      => '900',
                'nLogLevel'     => '4'
            );
        }
        echo json_encode($aReturnData);
    }

    //หน้าจอแก้ไข
    public function FSvCDBREditPage(){
        try {
            $ptDocumentNumber   = $this->input->post('ptDBRDocNo');
            // Clear Data In Doc DT Temp
            $aWhereClearTemp    = [
                'FTXthDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TRTTBookHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->depositboxreservation_model->FSnMDBRDelALLTmp($aWhereClearTemp);
            $this->depositboxreservation_model->FSxMDBRClearDataInDocTemp($aWhereClearTemp);
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXshDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TRTTBookDT',
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
            $aDataDocHD = $this->depositboxreservation_model->FSaMDBRGetDataDocHD($aDataWhere);
            // print_r($aDataDocHD);

            // Move Data DT TO DTTemp
            $this->depositboxreservation_model->FSxMDBRMoveDTToDTTemp($aDataWhere);
            // Move Data HDDocRef TO HDRefTemp
            $this->depositboxreservation_model->FSxMDBRMoveHDRefToHDRefTemp($aDataWhere);
            
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
                $tViewPageEdit           = $this->load->view('document/depositboxreservation/wDepositBoxReservationPageAdd',$aDataConfigViewEdit,true);
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
    public function FSoCDBRDeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $tRefInDocNo = $this->input->post('tDBRRefInCode');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->depositboxreservation_model->FSaMDBRUpdateSOStaRef($tRefInDocNo, $nStaRef);
            }

            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tBchCode' => $tBchCode
            );
            
            $aResDelDoc = $this->depositboxreservation_model->FSnMDBRDelDocument($aDataMaster);
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
    public function FSvCDBRCancelDocument() {
        try {
            $tDBRDocNo       = $this->input->post('ptDBRDocNo');
            $tRefInDocNo    = $this->input->post('ptRefInDocNo');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->depositboxreservation_model->FSaMDBRUpdateSOStaRef($tRefInDocNo, $nStaRef);
            }
            
            $aDataUpdate = array(
                'tDocNo' => $tDBRDocNo,
            );

            $aResult        = $this->depositboxreservation_model->FSaMDBRCancelDocument($aDataUpdate);
            $aReturnData    = $aResult;

            // if($tStaApv == 1){
            //     //ถ้าอนุมัติเเล้ว แล้วกดยกเลิกต้องวิ่ง MQ อีกรอบ ให้เคลียร์ Stock
            //     $aMQParams = [
            //         "queueName" => "AP_QDocApprove",
            //         "params"    => [
            //             'ptFunction'    => 'TRTTBookHD',
            //             'ptSource'      => 'AdaStoreBack',
            //             'ptDest'        => 'MQReceivePrc',
            //             'ptFilter'      => '',
            //             'ptData'        => json_encode([
            //                 "ptBchCode"     => $tBchCode,
            //                 "ptDocNo"       => $tDBRDocNo,
            //                 "ptDocType"     => 11,
            //                 "ptUser"        => $this->session->userdata("tSesUsername"),
            //             ])
            //         ]
            //     ];

            //     // เชื่อม Rabbit MQ
            //     FCNxCallRabbitMQ($aMQParams);
            // }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //เช็ค Ref ก่อน Cancel
    public function FSvCDBRCancelCheckRef() {
        try {
            $tDBRDocNo   = $this->input->post('ptDBRDocNo');
            $CountIVRef = $this->depositboxreservation_model->FSaMDBRCheckIVRef($tDBRDocNo);
            if($CountIVRef > 0 ){
                $aReturnData = array(
                    'nStaEvent' => '2',
                    'tStaMessg' => 'ไม่สามารถยกเลิกได้ ใบจองช่องฝากถูกอ้างอิงไปแล้ว กรุณายกเลิกการอ้างอิงก่อนทำรายการ'
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
    public function FSoCDBRApproveEvent(){
        try{
            $tDocNo         = $this->input->post('tDocNo');
            $tAgnCode       = $this->input->post('tAgnCode');
            $tBchCode       = $this->input->post('tBchCode');
            $tRefInDocNo    = $this->input->post('tRefInDocNo');

            if (!empty($tRefInDocNo)) {
                $nStaRef        = '2';
                $this->depositboxreservation_model->FSaMDBRUpdateSOStaRef($tRefInDocNo, $nStaRef);
            }

            // $aMQParams = [
            //     "queueName" => "AP_QDocApprove",
            //     "params"    => [
            //         'ptFunction'        => 'TRTTBookHD',
            //         'ptSource'          => 'AdaStoreBack',
            //         'ptDest'            => 'MQReceivePrc',
            //         'ptFilter'          => '',
            //         'ptData'            => json_encode([
            //             "ptBchCode"     => $tBchCode,
            //             "ptDocNo"       => $tDocNo,
            //             "ptDocType"     => 11,
            //             "ptUser"        => $this->session->userdata("tSesUsername"),
            //         ])
            //     ]
            // ];
            // // เชื่อม Rabbit MQ
            // FCNxCallRabbitMQ($aMQParams);

            // $aDataGetDataHD     =   $this->depositboxreservation_model->FSaMDBRGetDataDocHD(array(
            //     'FTXshDocNo'    => $tDocNo,
            //     'FNLngID'       => $this->session->userdata("tLangEdit")
            // ));

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername')
            );

            // if($aDataGetDataHD['rtCode']=='1'){
            //     $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXshDocNo']);
            //     if($aDataGetDataHD['raItems']['FTXshRefInt']!=''){
            //         $tTxtRefDoc1 = 'อ้างอิงเลขที่ #'.$aDataGetDataHD['raItems']['FTXshRefInt'];
            //         $tTxtRefDoc2 = 'Ref #'.$aDataGetDataHD['raItems']['FTXshRefInt'];
            //     }else{
            //         $tTxtRefDoc1 = "";
            //         $tTxtRefDoc2 = "";
            //     }
            //     $aTCNTNotiSpc[] = array(
            //                                 "FNNotID"       => $tNotiID,
            //                                 "FTNotType"    => '1',
            //                                 "FTNotStaType" => '1',
            //                                 "FTAgnCode"    => '',
            //                                 "FTAgnName"    => '',
            //                                 "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
            //                                 "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
            //                             );
            //     if($aDataGetDataHD['raItems']['FTBchCode']!=''){
            //         $aTCNTNotiSpc[] = array(
            //                                     "FNNotID"       => $tNotiID,
            //                                     "FTNotType"    => '2',
            //                                     "FTNotStaType" => '1',
            //                                     "FTAgnCode"    => '',
            //                                     "FTAgnName"    => '',
            //                                     "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
            //                                     "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
            //                                 );
            //     }
            //     $aMQParamsNoti = [
            //         "queueName" => "CN_SendToNoti",
            //         "tVhostType" => "NOT",
            //         "params"    => [
            //                         "oaTCNTNoti" => array(
            //                                         "FNNotID"       => $tNotiID,
            //                                         "FTNotCode"     => '00011',
            //                                         "FTNotKey"      => 'TRTTBookHD',
            //                                         "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
            //                                         "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXshDocNo'],
            //                         ),
            //                         "oaTCNTNoti_L" => array(
            //                                             0 => array(
            //                                                 "FNNotID"       => $tNotiID,
            //                                                 "FNLngID"       => 1,
            //                                                 "FTNotDesc1"    => 'เอกสารใบจองช่องฝาก #'.$aDataGetDataHD['raItems']['FTXshDocNo'],
            //                                                 "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
            //                                             ),
            //                                             1 => array(
            //                                                 "FNNotID"       => $tNotiID,
            //                                                 "FNLngID"       => 2,
            //                                                 "FTNotDesc1"    => 'Purchase orders from branches #'.$aDataGetDataHD['raItems']['FTXshDocNo'],
            //                                                 "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Recive Product '.$tTxtRefDoc1,
            //                                             )
            //                         ),
            //                         "oaTCNTNotiAct" => array(
            //                                             0 => array( 
            //                                                     "FNNotID"       => $tNotiID,
            //                                                     "FDNoaDateInsert" => date('Y-m-d H:i:s'),
            //                                                     "FTNoaDesc"          => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' รับของ '.$tTxtRefDoc1,
            //                                                     "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXshDocNo'],
            //                                                     "FNNoaUrlType"   =>  1,
            //                                                     "FTNoaUrlRef"    => 'docDBR/2/0',
            //                                                     ),
            //                             ), 
            //                         "oaTCNTNotiSpc" => $aTCNTNotiSpc,
            //             "ptUser"        => $this->session->userdata('tSesUsername'),
            //         ]
            //     ];
            //     FCNxCallRabbitMQ($aMQParamsNoti);
                
            // }

            // $this->depositboxreservation_model->FSaMDBRApproveDocument($aDataUpdate);

            $aMQParams = [
                "queueName" => "CN_QDocApprove",
                "params"    => [
                    'ptFunction'        => 'TRTTBookHD',
                    'ptSource'          => 'AdaStoreBack',
                    'ptDest'            => 'MQReceivePrc',
                    'ptFilter'          => '',
                    'ptData'            => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptDocType"     => 1,
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            // เชื่อม Rabbit MQ
            FCNxCallRabbitMQ($aMQParams);

            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Approve Document Success",
                'tLogType' => 'INFO',
                'tEventName' => 'อนุมัติใบจองช่องฝาก',
                'nLogCode' => '001',
                'nLogLevel' => '0'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tEventName' => 'อนุมัติใบจองช่องฝาก',
                'nLogCode' => '900',
                'nLogLevel' => '4'
            );
        }
        echo json_encode($aReturnData);
    }


    //อนุมัติเอกสาร
    public function FSoCDBRApproveAfterSuggesEvent(){
        try{
            $tDocNo         = $this->input->post('tDocNo');
            $tAgnCode       = $this->input->post('tAgnCode');
            $tBchCode       = $this->input->post('tBchCode');
            $tRefInDocNo    = $this->input->post('tRefInDocNo');


            // // Move Doc DTTemp To DT
            $this->depositboxreservation_model->FSaMDBRMoveDtTmpToDtSugges($tDocNo, $tBchCode);
            $this->depositboxreservation_model->FSxMDBRMoveDTTempToDTSLSugges($tDocNo, $tBchCode);


            if (!empty($tRefInDocNo)) {
                $nStaRef        = '2';
                $this->depositboxreservation_model->FSaMDBRUpdateSOStaRef($tRefInDocNo, $nStaRef);
            }

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername')
            );

            $this->depositboxreservation_model->FSaMDBRApproveDocument($aDataUpdate);

            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Approve Document Success",
                'tLogType' => 'INFO',
                'tEventName' => 'อนุมัติใบจองช่องฝาก',
                'nLogCode' => '001',
                'nLogLevel' => '0'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tEventName' => 'อนุมัติใบจองช่องฝาก',
                'nLogCode' => '900',
                'nLogLevel' => '4'
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCDBRPageHDDocRef(){
        try {
            $tDocNo = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TRTTBookHDDocRef',
                'tTableTmpHDRef'    => 'TRTTBookDocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TRTTBookHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef = $this->depositboxreservation_model->FSaMDBRGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/depositboxreservation/wDepositBoxReservationDocRef', $aDataConfig, true);
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
    public function FSoCDBREventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthDocKey'       => 'TRTTBookHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tSORefDocNoOld'    => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $dDocDateChk = $this->input->post('pdRefDocDate');
            $dDocNoChk = $this->input->post('ptRefDocNo');
            

            if(trim($dDocDateChk) == ''){
                $aDataResult    = $this->depositboxreservation_model->FSoMDBRGetSoData($dDocNoChk);
                $dDocDate       = $aDataResult['raItems']['0']['FDXshdocDate'];
            }else{
                $dDocDate = $this->input->post('pdRefDocDate');
            }
            
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $dDocDate,
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];

            if($aDataAddEdit['FTXthRefKey'] == 'SO'){
                $aReturnData = $this->depositboxreservation_model->FSaMDBRAddEditHDRefTmpABB($aDataWhere,$aDataAddEdit);
            }

            $aReturnData = $this->depositboxreservation_model->FSaMDBRAddEditHDRefTmp($aDataWhere,$aDataAddEdit);

            if($this->input->post('ptRefEx') != ''){
                $this->depositboxreservation_model->FSoMDBRAddHDRefEx($aDataWhere,$aDataAddEdit);
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCDBREventDelHDDocRef(){
        try {
            $aData = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthDocKey'       => 'TRTTBookHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aReturnData = $this->depositboxreservation_model->FSaMDBRDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // กดปุ่มสร้างใบสั่งขาย
    public function FSoCDBREventGenSO(){
        $tDocNo     = $this->input->post('tDocNo');
        $tBchCode   = $this->input->post('tBchCode');

        $aMQParams = [
            "queueName" => "CN_QGenDoc",
            "params"    => [
                'ptFunction'    => "TRTTBookHD",
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

    // แสดงตู้ฝาก browse
    public function FSoCDBRCallBrowseBox(){
        $tSeqNo             = $this->input->post('tSeqNo');
        $tBCHCode           = $this->input->post('tBCHCode');
        $tBCHName           = $this->input->post('tBCHName');
        $tPosCode           = $this->input->post('tPosCode');
        $tShpCode           = $this->input->post('tShpCode');
        $tPosName           = $this->input->post('tPosName');
        $nStaBrowseType     = $this->input->post('nStaBrowseType');
        

        $aDataParam = array(
            'tBCHCode'          => $tBCHCode,
            'tBCHName'          => $tBCHName,
            'tSeqNo'            => $tSeqNo,
            'tPosCode'          => $tPosCode,
            'tShpCode'          => $tShpCode,
            'tPosName'          => $tPosName,
            'nStaBrowseType'    => $nStaBrowseType,
        );
        if ($nStaBrowseType == 1) {
            $this->load->view('document/depositboxreservation/BrowseBoxAndSlot/wDepositBoxReservationBox', $aDataParam);
        }else{
            $this->load->view('document/depositboxreservation/BrowseBoxAndSlot/wDepositBoxReservationSlot', $aDataParam);
        }
        
    }

    // แสดงตู้ฝาก browse & Search
    public function FSoCDBRCallBrowseBoxDataTable(){
        $nPage                      = $this->input->post('nDBRPageCurrent');
        $tDBRBrowseBoxBchCode       = $this->input->post('tDBRBrowseBoxBchCode');
        $tDBRBrowseBoxPos           = $this->input->post('tDBRPosCode');
        $tDBRBrowseBoxNo            = $this->input->post('tDBRBrowseBoxNo');
        $tDBRBrowseType             = $this->input->post('tDBRBrowseType');
        $tDBRBrowseShp             = $this->input->post('tDBRShpCode');
        
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nDBRPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tDBRBrowseBoxBchCode'      => $tDBRBrowseBoxBchCode,
            'tDBRBrowseBoxPos'          => $tDBRBrowseBoxPos,
            'tDBRBrowseShp'             => $tDBRBrowseShp,
            'tDBRBrowseBoxNo'           => $tDBRBrowseBoxNo
        );
        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'nPage'     => $nPage,
            'nRow'      => 35,
            'aAdvanceSearch' => $aDataParamFilter
        );
        if ($tDBRBrowseType == 1) {
            $aDataParam = $this->depositboxreservation_model->FSoMDBRCallBoxDataTable($aDataCondition);
        }else{
            $tDBRStaUse = $this->input->post('tDBRStaUse');
            $aDataParam = $this->depositboxreservation_model->FSoMDBRCallSlotDocDataTable($aDataCondition,$tDBRStaUse);
        }
        
        $aConfigView = array(
            'nPage'          => $nPage,
            'aDataList'      => $aDataParam,
            'tDBRBrowseType' => $tDBRBrowseType
        );

        $this->load->view('document/depositboxreservation/BrowseBoxAndSlot/wDepositBoxReservationBoxDataTable', $aConfigView);
        
    }

    //แนะนำช่องฝาก
    public function FSoCDBRSuggestLay(){
        try{
            $tBchCode           = $this->input->post('tBchCode');
            $tDocNo             = $this->input->post('tRefInDocNo');

            $aMQParams = [
                "queueName" => "SL_QProcess",
                "params"    => [
                    'ptFunction'        => 'SuggestByDoc',
                    'ptSource'          => 'AdaStoreBack',
                    'ptDest'            => 'MQReceivePrc',
                    'ptFilter'          => '',
                    'ptData'            => json_encode([
                        "ptAgnCode"     => $this->session->userdata('tSesUsrAgnCode'),
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptUsrCode"     => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];

            // print_r($aMQParams);

            // เชื่อม Rabbit MQ
            $nStaSendMQ = FCNxCallRabbitMQ($aMQParams);

            if ($nStaSendMQ == 1) {
                $aReturnData = array(
                    'nStaEvent'    => '1',
                );
            }else{
                $aReturnData = array(
                    'nStaEvent'    => '900',
                );
            }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
            );
        }
        echo json_encode($aReturnData);
    }

    //แนะนำช่องฝาก
    public function FSoCDBRSuggestEdit(){
        try{
            $aData = $this->input->post('paData');

            $aDataSugges = [];
            foreach($aData as $nKey => $aValue){
                $aList   = [
                    "ptAgnCode"         => $aValue['ptAgnCode'],
                    "ptBchCode"         => $aValue['ptBchCode'],
                    "ptUsrCode"         => $aValue['ptUsrCode'],
                    "ptShpFrm"          => $aValue['ptShpFrm'],
                    "ptPosFrm"          => $aValue['ptPosFrm'],
                    "pnLayNoFrm"        => $aValue['pnLayNoFrm'],
                    "ptShpTo"           => $aValue['ptShpTo'],
                    "ptPosTo"           => $aValue['ptPosTo'],
                    "pnLayNoTo"         => $aValue['pnLayNoTo'],
                    "ptLayStaTo"        => $aValue['ptLayStaTo'],
                    "ptDocNo"           => $aValue['ptDocNo'],
                    "pnXsdSeqNo"        => $aValue['pnXsdSeqNo'],
                ];

                array_push($aDataSugges,$aList);
            }
            // echo "<pre>";
            // print_r($aList);
            // echo "</pre>";


            $aMQParams = [
                "queueName" => "SL_QProcess",
                "params"    => [
                    'ptFunction'        => 'EditBooking',
                    'ptSource'          => 'AdaStoreBack',
                    'ptDest'            => 'MQReceivePrc',
                    'ptFilter'          => '',
                    'ptData'            => json_encode(
                        $aDataSugges
                    )
                ]
            ];

            // print_r($aMQParams);

            // เชื่อม Rabbit MQ
            $nStaSendMQ = FCNxCallRabbitMQ($aMQParams);

            if ($nStaSendMQ == 1) {
                $aReturnData = array(
                    'nStaEvent'    => '1',
                );
            }else{
                $aReturnData = array(
                    'nStaEvent'    => '900',
                );
            }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
            );
        }
        echo json_encode($aReturnData);
    }

        //แนะนำช่องฝาก
        public function FSoCDBRCheckRtsaleDocref(){
            try{
                $tDocno = $this->input->post('tDocno');
                
                $aDataParam = $this->depositboxreservation_model->FSoMDBRCheckDocref($tDocno);
            } catch (Exception $Error) {
                $aReturnData = array(
                    'nStaEvent' => '500',
                );
            }
            echo json_encode($aDataParam);
        }

        // รับค่าแนะนำช่องฝาก asad
        public function FSoCDBRGetMsgSuggestEdit(){
            try{
                $tBookDocNo = $this->input->post('tRefInDocNo');
                $aData = $this->input->post('aItems');
                $tBchCode = $aData[0]['ptBchCode'];
                $aFailItems = [];
                $aParamQ['tQname']  = 'RESEDIT_'.$tBookDocNo.'_'.$this->session->userdata("tSesUsername");
                $aParamQ['nAutoDelete']  = '1';
                $tTaxJsonString     = FCNxRabbitMQGetMassage($aParamQ);
               
                
                if( $tTaxJsonString != 'false' ){
                    $aDataUpdate    =  json_decode($tTaxJsonString, true);
                    // print_r($tTaxJsonString);

                    // print_r($aDataUpdate);
                    $pdtdata = $aDataUpdate['rtData'];
    
                    $aDataUpdate2    =  json_decode($pdtdata, true);   
                    
                    
    
                    $aDataWhere = array(
                        'tDBRDocCode'   => $tBookDocNo,
                        'tDBRBchCode'   => $tBchCode,
                        'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                    );
                    if (isset($aDataUpdate['rtData']) && !empty($aDataUpdate['rtData'])) {
                        foreach ($aDataUpdate2 as $aItem) {
                            $this->depositboxreservation_model->FSoMDBRUpdateLayEDITDTTmp($aItem,$aDataWhere);
                            if($aItem['rtStatus'] != '1'){

                                $aList   = [
                                    "pdt"                => 'testpdt',
                                    "rtShpFrm"           => $aItem['rtShpFrm'],
                                    "rtPosFrm"           => $aItem['rtPosFrm'],
                                    "rnLayNoFrm"         => $aItem['rnLayNoFrm'],
                                    "rtShpTo"            => $aItem['rtShpTo'],
                                    "rtPosTo"            => $aItem['rtPosTo'],
                                    "rnLayNoTo"          => $aItem['rnLayNoTo'],
                                    "rtLayStaTo"         => $aItem['rtLayStaTo'],
                                    "rtStatus"           => $aItem['rtStatus'],
                                    "rnXsdSeqNo"         => $aItem['rnXsdSeqNo'],
                                    "ptDocNo"            => $tBookDocNo,
                                ];
                
                                array_push($aFailItems,$aList);
                            }
                        }
                        $aReturnData = array(
                            'aFailItem'    => $aFailItems,
                            'nStaEvent'    => '1',
                            'tMessage'     => 'แนะนำช่องฝากสำเสร็จ'
                        );
                    }else{
                        $aReturnData = array(
                            'nStaEvent'    => '800',
                            'tMessage'     => 'ไม่มีข้อมูลแนะนำช่องฝาก'
                        );
                    }
                }else{
                    $aReturnData = array(
                        'nStaEvent'    => '900',
                        'tMessage'     => 'ไม่มีข้อมูลแนะนำช่องฝาก'
                    );
                }
                
            } catch (Exception $Error) {
                $aReturnData = array(
                    'nStaEvent' => '500',
                );
            }
            echo json_encode($aReturnData);
        }

    // รับค่าแนะนำช่องฝาก
    public function FSoCDBRGetMsgSuggestLay(){
        try{
            $tSODocNo = $this->input->post('tRefInDocNo');
            $tDBRDocCode = $this->input->post('tDBRDocNo');
            $tDBRBchCode = $this->input->post('tDBRBchCode');

            $aParamQ['nAutoDelete']  = '1';
            $aParamQ['tQname']  = 'RESSUG_'.$tSODocNo.'_'.$this->session->userdata("tSesUsername");

            $tTaxJsonString     = FCNxRabbitMQGetMassage($aParamQ);



            if( $tTaxJsonString != 'false' ){
                $aDataUpdate    =  json_decode($tTaxJsonString, true);
                $pdtdata        = $aDataUpdate['rtData'];
                $aDataUpdate2   =  json_decode($pdtdata, true);

                $aDataWhere = array(
                    'tDBRDocCode'   => $tDBRDocCode,
                    'tDBRBchCode'   => $tDBRBchCode,
                    'tSODocNo'      => $tSODocNo,
                    'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                );
                if (isset($aDataUpdate2) && !empty($aDataUpdate2)) {
                    foreach ($aDataUpdate2 as $aItem) {
                        $this->depositboxreservation_model->FSoMDBRUpdateLayDTTmp($aItem,$aDataWhere);
                        $aReturnData = array(
                            'nStaEvent'    => '1',
                            'tMessage'     => 'แนะนำช่องฝากสำเสร็จ',
                            'aDataUpdate'  => $aDataUpdate,
                            'pdtdata'      => $pdtdata,
                            'aDataUpdate2' => $aDataUpdate2
                        );
                    }
                }else{
                    $aReturnData = array(
                        'nStaEvent'    => '800',
                        'tMessage'     => 'ไม่มีข้อมูลแนะนำช่องฝาก',
                        'aDataUpdate'  => $aDataUpdate,
                        'pdtdata'      => $pdtdata,
                        'aDataUpdate2' => $aDataUpdate2
                    );
                }
                
            }else{
                $aReturnData = array(
                    'nStaEvent'    => '900',
                    'tMessage'     => 'ไม่มีข้อมูลแนะนำช่องฝาก',
                    'QName'        => $aParamQ['tQname']
                );
            }
            
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
            );
        }
        echo json_encode($aReturnData);
    }



    // รับค่าแนะนำช่องฝาก
    public function FSoCDBRCountPrint(){
    
        $tDocNo = $this->input->post('tDBRDocNo');
        $tPrintCount = $this->input->post('tPrintCount');
        $this->depositboxreservation_model->FSoMDBRUpdatePrint($tDocNo,$tPrintCount);

    }

}
