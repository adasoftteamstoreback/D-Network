<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH .'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;


class Purchasebranch_controller extends MX_Controller {

    public function __construct()
    {
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/purchasebranch/Purchasebranch_model');
        parent::__construct();
    }

    public $tRouteMenu  = 'docPreOrderb/0/0';

    public function index($nPRBBrowseType, $tPRBBrowseOption)
    {
        $aParams    =   array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        $aDataConfigView = array(
            'nPRBBrowseType'        => $nPRBBrowseType,
            'tPRBBrowseOption'      => $tPRBBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc($this->tRouteMenu), // Controle Event
            'vBtnSave'              => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'aParams'               => $aParams 
        );
        $this->load->view('document/purchasebranch/wPurchasebranch', $aDataConfigView);
    }

    // แสดง Form Search ข้อมูลในตารางหน้า List
    public function FSvCPRBFormSearchList() {
        $this->load->view('document/purchasebranch/wPurchasebranchFormSearchList');
    }

    // แสดงตารางในหน้า List
    public function FSoCPRBDataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $aAlwEvent      = FCNaHCheckAlwFunc($this->tRouteMenu);

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
            $aDataList = $this->Purchasebranch_model->FSaMPRBGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tPRBViewDataTableList = $this->load->view('document/purchasebranch/wPurchasebranchDataTable', $aConfigView, true);
            $aReturnData = array(
                'tPRBViewDataTableList' => $tPRBViewDataTableList,
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
    public function FSoCPRBCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );

        $this->load->view('document/purchasebranch/refintdocument/wPurchasebranchRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCPRBCallRefIntDocDataTable(){

        $nPage = $this->input->post('nPRBRefIntPageCurrent');
        $tPRBRefIntBchCode   = $this->input->post('tPRBRefIntBchCode');
        $tPRBRefIntWahCode   = $this->input->post('tPRBRefIntWahCode');
        $tPRBRefIntDocNo        = $this->input->post('tPRBRefIntDocNo');
        $oetPRBRefIntPDTCodeFrm   = $this->input->post('oetPRBRefIntPDTCodeFrm');
        $oetPRBRefIntPDTCodeTo   = $this->input->post('oetPRBRefIntPDTCodeTo');
        $tPRBRefIntStaDoc   = $this->input->post('tPRBRefIntStaDoc');
        // Page Current
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPRBRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");


        $aDataParamFilter = array(
            'tPRBRefIntBchCode' => $tPRBRefIntBchCode,
            'tPRBRefIntWahCode' => $tPRBRefIntWahCode,
            'tPRBRefIntDocNo' => $tPRBRefIntDocNo,
            'oetPRBRefIntPDTCodeFrm' => $oetPRBRefIntPDTCodeFrm,
            'oetPRBRefIntPDTCodeTo' => $oetPRBRefIntPDTCodeTo,
            'tPRBRefIntStaDoc' => $tPRBRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );

         $aDataParam = $this->Purchasebranch_model->FSoMPRBCallRefIntDocDataTable($aDataCondition);

         $aConfigView = array(
            'nPage' => $nPage,
            'aDataList' => $aDataParam,
          );

         $this->load->view('document/purchasebranch/refintdocument/wPurchasebranchRefDocDataTable', $aConfigView);
    }
    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCPRBCallRefIntDocInsertDTToTemp(){
        $tPRBDocNo       =  $this->input->post('tPRBDocNo');
        $tPRBFrmBchCode  =  $this->input->post('tPRBFrmBchCode');
        $tRefIntDocNo   =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode =  $this->input->post('tRefIntBchCode');
        $aSeqNo         =  $this->input->post('aSeqNo');

        $aDataParam = array(
            'tPRBDocNo'       => $tPRBDocNo,
            'tPRBFrmBchCode'  => $tPRBFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );

       $aDataResult = $this->Purchasebranch_model->FSoMPRBCallRefIntDocInsertDTToTemp($aDataParam);
       return  $aDataResult;
    }

    // เรียกหน้าเพิ่มข้อมูล
    public function FSoCPRBPageAdd() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => '',
                'FTXthDocKey' => 'TCNTPdtReqBchHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];

            $tUserBchCode = $this->session->userdata('tSesUsrBchCodeDefault');
            // echo $tUserBchCode;die();
            if(!empty($tUserBchCode)){
                $aDataBch = $this->Purchasebranch_model->FSaMPRBGetDetailUserBranch($tUserBchCode);
                $tPRBPplCode = $aDataBch['item']['FTPplCode'];
            }else{
                $tPRBPplCode = '';
            }


            $this->Purchasebranch_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->Purchasebranch_model->FSxMPRBClearDataInDocTemp($aWhereClearTemp);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            $aWhereHelperCalcDTTemp = array(
                'tDataDocEvnCall' => "",
                'tDataVatInOrEx' => 1,
                'tDataDocNo' => '',
                'tDataDocKey' => 'TCNTPdtReqHqHD',
                'tDataSeqNo' => ''
            );
            FCNbHCallCalcDocDTTemp($aWhereHelperCalcDTTemp);

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
            $aDataUserGroup = $this->Purchasebranch_model->FSaMPRBGetShpCodeForUsrLogin($aDataShp);
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

            $aDataBhcHQ         = $this->Purchasebranch_model->FSaMPRBGetBranchHQ();
            // ดึงข้อมูลที่อยู่คลัง Defult ในตาราง TSysConfig
            $aConfigSys = [
                'FTSysCode' => 'tPS_Warehouse',
                'FTSysSeq' => 3,
                'FNLngID' => $nLangEdit,
                'FTAgnCode' => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode' => $aDataUserGroup["FTBchCode"]
            ];
            $aConfigSysWareHouse = $this->Purchasebranch_model->FSaMPRBGetDefOptionConfigWah($aConfigSys);
            $aConfigWareHouse = $this->Purchasebranch_model->FSaMPRBGetDefOptionConfigWahouse($aConfigSys);

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
                'tBchCompCode' => FCNtGetBchInComp(),
                'tBchCompName' => FCNtGetBchNameInComp(),
                'aConfigSysWareHouse' => $aConfigSysWareHouse,
                'aConfigSysWareHouseTrue' => $aConfigWareHouse,
                'aDataDocHD' => array('rtCode' => '800'),
                'aDataDocHDSpl' => array('rtCode' => '800'),
                'tCmpRetInOrEx' => $tCmpRetInOrEx,
                'tPRBPplCode'  => $tPRBPplCode,
                'aBCHHQ'        => $aDataBhcHQ['raItem']
            );

            $tPRBViewPageAdd = $this->load->view('document/purchasebranch/wPurchasebranchPageAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tPRBViewPageAdd' => $tPRBViewPageAdd,
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
    public function FSoCPRBPdtAdvTblLoadData() {
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


            $tPRBDocNo           = $this->input->post('ptPRBDocNo');
            $tPRBStaApv          = $this->input->post('ptPRBStaApv');
            $tPRBStaDoc          = $this->input->post('ptPRBStaDoc');
            $tPRBVATInOrEx       = $this->input->post('ptPRBVATInOrEx');
            $nPRBPageCurrent     = $this->input->post('pnDOPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tPRBPdtCode         = $this->input->post('ptPRBPdtCode');
            $tPRBPunCode         = $this->input->post('ptPRBPunCode');

            //Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Call Advance Table
            $tTableGetColumeShow    = 'TCNTPdtReqHqHD';
            // $aColumnShow            = FCNaDCLGetColumnShow($tTableGetColumeShow);

            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tPRBDocNo,
                'FTXthDocKey'           => 'TCNTPdtReqHqHD',
                'nPage'                 => $nPRBPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );
            // FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp     = $this->Purchasebranch_model->FSaMPRBGetDocDTTempListPage($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tPRBStaApv'         => $tPRBStaApv,
                'tPRBStaDoc'         => $tPRBStaDoc,
                'tPRBPdtCode'        => $tPRBPdtCode,
                'tPRBPunCode'        => $tPRBPunCode,
                'nPage'             => $nPRBPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );

            $tPRBPdtAdvTableHtml = $this->load->view('document/purchasebranch/wPurchasebranchPdtAdvTableData', $aDataView, true);

            $aReturnData = array(
                'tPRBPdtAdvTableHtml' => $tPRBPdtAdvTableHtml,
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
    public function FSoCPRBAddPdtIntoDocDTTemp() {
        try {
            $tPRBUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tPRBDocNo           = $this->input->post('tPRBDocNo');
            $tPRBVATInOrEx       = $this->input->post('tPRBVATInOrEx');
            $tPRBBchCode         = $this->input->post('tSelectBCH');
            $tPRBOptionAddPdt    = $this->input->post('tPRBOptionAddPdt');
            $tPRBPdtData         = $this->input->post('tPRBPdtData');
            $aDOPdtData         = json_decode($tPRBPdtData);
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $tPRBSuggesType           = $this->input->post('tPRBSuggesType');

            $aDataWhere = array(
                'FTBchCode' => $tPRBBchCode,
                'FTXthDocNo' => $tPRBDocNo,
                'FTXthDocKey' => 'TCNTPdtReqHqHD',
            );
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aDOPdtData); $nI++) {
                $tPRBPdtCode = $aDOPdtData[$nI]->pnPdtCode;
                $tPRBBarCode = $aDOPdtData[$nI]->ptBarCode;
                $tPRBPunCode = $aDOPdtData[$nI]->ptPunCode;

                if(isset($aDOPdtData[$nI]->packData->Sugges2)){
                    $tPRBSuggest        = $aDOPdtData[$nI]->packData->Sugges2;
                }else{
                    $tPRBSuggest        = $aDOPdtData[$nI]->packData->Sugges;
                }


                $tFCStkQty          = $aDOPdtData[$nI]->packData->FCStkQty;
                $tPRBrowspan        = $aDOPdtData[$nI]->packData->rowspan;
                $tPRBFCPdtQtyOrdBuy = $aDOPdtData[$nI]->packData->FCPdtQtyOrdBuy;
                $tPRBAllSugges      = $aDOPdtData[$nI]->packData->FCXtdVatable;
                

                // $tPRBQTY     = $aDOPdtData[$nI]->ptQTY;
                $tPRBQTY     = NULL;


                $cPRBPrice       = $aDOPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tPRBDocNo,
                    'tBchCode'          => $tPRBBchCode,
                    'tPdtCode'          => $tPRBPdtCode,
                    'tBarCode'          => $tPRBBarCode,
                    'tPunCode'          => $tPRBPunCode,
                    'tQTY'              => $tPRBQTY,
                    'cPrice'            => str_replace(",","",$cPRBPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdPRBLangEdit"),
                    // 'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TCNTPdtReqHqHD',
                    'tPRBOptionAddPdt'   => $tPRBOptionAddPdt,
                    'tPRBUsrCode'        => $this->input->post('ohdPRBUsrCode'),
                    'nVatRate'           => $nVatRate,
                    'tPRBSuggest'        => $tPRBSuggest,
                    'tFCStkQty'          => $tFCStkQty,
                    'tPRBrowspan'        => $tPRBrowspan,
                    'tPRBFCPdtQtyOrdBuy' => $tPRBFCPdtQtyOrdBuy,
                    'nVatCode'           => $nVatCode,
                    'tPRBAllSugges'      => $tPRBAllSugges,
                    'tPRBSuggesType'      => $tPRBSuggesType
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Purchasebranch_model->FSaMPRBGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $nStaInsPdtToTmp = $this->Purchasebranch_model->FSaMPRBInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
                // แก้ไขSugges DT Temp
                $nSuggesChange = $this->Purchasebranch_model->FSaMPRBEditGroupProduct($aDataPdtParams);
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
                        'Item' => $nSuggesChange,
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
    public function FSvCPRBRemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tPRBDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtReqHqHD',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aDataPdtParams = array(
                'tPdtCode'          => $this->input->post('tPdtCode'),
                'tDocKey'           => 'TCNTPdtReqHqHD',
                'tPRBSuggesType'    => $this->input->post('tPRBSuggesType'),
            );

            $aStaDelPdtDocTemp = $this->Purchasebranch_model->FSnMPRBDelPdtInDTTmp($aDataWhere);

            // แก้ไขSugges DT Temp
            $nSuggesChange = $this->Purchasebranch_model->FSaMPRBEditGroupProduct($aDataPdtParams);
            // print_r($nSuggesChange);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'Item'      => $nSuggesChange,
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
    public function FSvCPRBRemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tPRBDocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtReqHqHD',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Purchasebranch_model->FSnMPRBDelMultiPdtInDTTmp($aDataWhere);

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
    public function FSoCPRBEditPdtIntoDocDTTemp() {
        try {

            $tPRBBchCode         = $this->input->post('tPRBBchCode');
            $tPRBDocNo           = $this->input->post('tPRBDocNo');
            $nPRBSeqNo           = $this->input->post('nPRBSeqNo');
            $tPRBSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tPRBBchCode'    => $tPRBBchCode,
                'tPRBDocNo'      => $tPRBDocNo,
                'nPRBSeqNo'      => $nPRBSeqNo,
                'tPRBSessionID'  => $tPRBSessionID,
                'tDocKey'       => 'TCNTPdtReqHqHD',
            );
            $aDataUpdateDT = array(
                'FCXtdQty'          => $this->input->post('nQty'),
            );
            $this->db->trans_begin();
            $this->Purchasebranch_model->FSaMPRBUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
            

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
    public function FSoCPRBChkHavePdtForDocDTTemp() {
        try {
            $tPRBBchCode = $this->input->post("ptBchCode");
            $tPRBAgnCode = $this->input->post("ptAgnCode");
            $tPRBDocNo   = $this->input->post("ptPRBDocNo");
            $tPRBSessionID = $this->input->post('tPRBSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tPRBDocNo,
                'FTXthDocKey' => 'TCNTPdtReqHqHD',
                'FTSessionID' => $tPRBSessionID
            );
            $nCountPdtInDocDTTemp = $this->Purchasebranch_model->FSnMPRBChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/purchasebranch/purchasebranch', 'tPRBPleaseSeletedPDTIntoTable')
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
    public function FSoCPRBAddEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tPRBAutoGenCode = (isset($aDataDocument['ocbPRBStaAutoGenCode'])) ? 1 : 0;
            $tPRBDocNo = (isset($aDataDocument['oetPRBDocNo'])) ? $aDataDocument['oetPRBDocNo'] : '';
            $tPRBDocDate = $aDataDocument['oetPRBDocDate'] . " " . $aDataDocument['oetPRBDocTime'];
            $tPRBStaDocAct = (isset($aDataDocument['ocbPRBFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tPRBVATInOrEx = $aDataDocument['ohdPRBFrmSplInfoVatInOrEx'];
            $tPRBSessionID = $this->input->post('ohdSesSessionID');
            $nPRBSubmitWithImp = $aDataDocument['ohdPRBSubmitWithImp'];

            // Get Data Comp.
            $nLangEdit = $this->input->post("ohdPRBLangEdit");
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $tADOReq = "";
            $tMethodReq = "GET";
            $aCompData = $this->mCompany->FSaMCMPList($tADOReq, $tMethodReq, $aDataWhereComp);
            $aClearDTParams = [
                'FTXthDocNo'     => $tPRBDocNo,
                'FTXthDocKey'    => 'TCNTPdtReqHqHD',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nPRBSubmitWithImp==1){
                $this->Purchasebranch_model->FSxMPRBClearDataInDocTempForImp($aClearDTParams);
            }

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD' => 'TCNTPdtReqHqHD',
                'tTableDT' => 'TCNTPdtReqHqDT',
                'tTableStaGen' => 11,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode' => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode' => $aDataDocument['oetPRBFrmBchCode'],
                'FTXphDocNo' => $tPRBDocNo,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->input->post('ohdPRBUsrCode'),
                'FTLastUpdBy' => $this->input->post('ohdPRBUsrCode'),
                'FTSessionID' => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx' => $tPRBVATInOrEx
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FDXphDocDate'  => (!empty($tPRBDocDate)) ? $tPRBDocDate : NULL,
                'FTUsrCode'     => $aDataDocument['ohdPRBUsrCode'],
                'FTWahCode'     => $aDataDocument['oetPRBFrmWahCodeShip'],
                'FTXphAgnFrm'   => $aDataDocument['oetPRBAgnCodeTo'],
                'FTXphAgnTo'    => $aDataDocument['oetPRBAgnCodeShip'],
                'FTXphBchFrm'   => $aDataDocument['oetPRBFrmBchCodeTo'],
                'FTXphBchTo'    => $aDataDocument['oetPRBFrmBchCodeShip'],
                'FNXphDocPrint' => $aDataDocument['ocmPRBFrmInfoOthDocPrint'],
                'FTXphRmk'      => $aDataDocument['otaPRBFrmInfoOthRmk'],
                'FTXphStaDoc'   => $aDataDocument['ohdPRBStaDoc'],
                'FTXphStaApv'   => !empty($aDataDocument['ohdPRBStaApv']) ? $aDataDocument['ohdPRBStaApv'] : NULL,
                'FNXphStaDocAct' => $tPRBStaDocAct,
                'FNXphStaRef'   => $aDataDocument['ocmPRBFrmInfoOthRef'],
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tPRBAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TCNTPdtReqHqHD',
                    "tDocType"    => '13',
                    "tBchCode"    => $aDataDocument['oetPRBFrmBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );

                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo'] = $tPRBDocNo;
            }

            // Add Update Document HD
            $this->Purchasebranch_model->FSxMPRBAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Purchasebranch_model->FSxMPRBAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            
            // Move Doc DTTemp To DT
            $this->Purchasebranch_model->FSaMPRBMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if (!empty($aDataDocument['oetPRBRefDocIntName'])) {
                $tRefExtDocNo    = $aDataDocument['oetPRBRefDocIntName'];
                $tRefExtDocDate  = $aDataDocument['oetPRBRefIntDocDate'];

                $aDataPRBAddDocRef = array(
                'FTAgnCode'         => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRBFrmBchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'PRB',
                'FTXshRefDocNo'     => $tRefExtDocNo,
                'FDXshRefDocDate'   => $tRefExtDocDate,
                );
                $this->Purchasebranch_model->FSaMPRBUpdateRefExtDocHD($aDataPRBAddDocRef);
            }


            if (!empty($aDataDocument['oetPRBSplRefDocExt'])) {
                $tRefExtDocNo    = $aDataDocument['oetPRBSplRefDocExt'];
                $tRefExtDocDate  = $aDataDocument['oetPRBRefDocExtDate'];

                $aDataPRBAddDocRef = array(
                'FTAgnCode'         => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRBFrmBchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'PRB',
                'FTXshRefDocNo'     => $tRefExtDocNo,
                'FDXshRefDocDate'   => $tRefExtDocDate,
                );
                $this->Purchasebranch_model->FSaMPRBUpdateRefExtDocHD($aDataPRBAddDocRef);
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

    // Function: Edit Document
    public function FSoCPRBEditEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tPRBAutoGenCode = (isset($aDataDocument['ocbPRBStaAutoGenCode'])) ? 1 : 0;
            $tPRBDocNo = (isset($aDataDocument['oetPRBDocNo'])) ? $aDataDocument['oetPRBDocNo'] : '';
            $tPRBDocDate = $aDataDocument['oetPRBDocDate'] . " " . $aDataDocument['oetPRBDocTime'];
            $tPRBStaDocAct = (isset($aDataDocument['ocbPRBFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tPRBVATInOrEx = $aDataDocument['ohdPRBFrmSplInfoVatInOrEx'];
            $tPRBSessionID = $this->input->post('ohdSesSessionID');
            $nPRBSubmitWithImp = $aDataDocument['ohdPRBSubmitWithImp'];

            // Get Data Comp.
            $nLangEdit = $this->input->post("ohdPRBLangEdit");
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $tADOReq = "";
            $tMethodReq = "GET";
            $aCompData = $this->mCompany->FSaMCMPList($tADOReq, $tMethodReq, $aDataWhereComp);
            $aClearDTParams = [
                'FTXphDocNo'     => $tPRBDocNo,
                'FTXthDocKey'    => 'TCNTPdtReqHqHD',
                'FTSessionID'    => $this->input->post('ohdSesSessionID'),
            ];
            if($nPRBSubmitWithImp==1){
                $this->Purchasebranch_model->FSxMPRBClearDataInDocTempForImp($aClearDTParams);
            }

            if (!empty($aDataDocument['oetPRBRefDocIntName'])) {
                $tRefInDocNo = $aDataDocument['oetPRBRefDocIntName'];
                $nStaRef = '2';
                $this->Purchasebranch_model->FSaMPRBUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD' => 'TCNTPdtReqHqHD',
                'tTableDT' => 'TCNTPdtReqHqDT',
                'tTableStaGen' => 11,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode' => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode' => $aDataDocument['oetPRBFrmBchCode'],
                'FTXphDocNo' => $tPRBDocNo,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->input->post('ohdPRBUsrCode'),
                'FTLastUpdBy' => $this->input->post('ohdPRBUsrCode'),
                'FTSessionID' => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx' => $tPRBVATInOrEx
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FDXphDocDate'  => (!empty($tPRBDocDate)) ? $tPRBDocDate : NULL,
                'FTUsrCode'     => $aDataDocument['ohdPRBUsrCode'],
                'FTXphAgnFrm'   => $aDataDocument['oetPRBAgnCodeTo'],
                'FTXphAgnTo'    => $aDataDocument['oetPRBAgnCodeShip'],
                'FTXphBchFrm'   => $aDataDocument['oetPRBFrmBchCodeTo'],
                'FTXphBchTo'    => $aDataDocument['oetPRBFrmBchCodeShip'],
                'FTWahCode'     => $aDataDocument['oetPRBFrmWahCodeShip'],
                'FNXphDocPrint' => $aDataDocument['ocmPRBFrmInfoOthDocPrint'],
                'FTXphRmk'      => $aDataDocument['otaPRBFrmInfoOthRmk'],
                'FTXphStaDoc'   => $aDataDocument['ohdPRBStaDoc'],
                'FTXphStaApv'   => !empty($aDataDocument['ohdPRBStaApv']) ? $aDataDocument['ohdPRBStaApv'] : NULL,
                'FNXphStaDocAct' => $tPRBStaDocAct,
                'FNXphStaRef'   => $aDataDocument['ocmPRBFrmInfoOthRef'],
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tPRBAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TCNTPdtReqHqHD',
                    "tDocType"    => '11',
                    "tBchCode"    => $aDataDocument['oetPRBFrmBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );

                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo'] = $tPRBDocNo;
            }

            // Add Update Document HD
            $this->Purchasebranch_model->FSxMPRBAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Purchasebranch_model->FSxMPRBAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->Purchasebranch_model->FSaMPRBMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if (!empty($aDataDocument['oetPRBRefDocIntName'])) {
                $tRefExtDocNo    = $aDataDocument['oetPRBRefDocIntName'];
                $tRefExtDocDate  = $aDataDocument['oetPRBRefIntDocDate'];

                $aDataPRBAddDocRef = array(
                'FTAgnCode'         => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRBFrmBchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'PRB',
                'FTXshRefDocNo'     => $tRefExtDocNo,
                'FDXshRefDocDate'   => $tRefExtDocDate,
                );
                $this->Purchasebranch_model->FSaMPRBUpdateRefExtDocHD($aDataPRBAddDocRef);
            }


            if (!empty($aDataDocument['oetPRBSplRefDocExt'])) {
                $tRefExtDocNo    = $aDataDocument['oetPRBSplRefDocExt'];
                $tRefExtDocDate  = $aDataDocument['oetPRBRefDocExtDate'];

                $aDataPRBAddDocRef = array(
                'FTAgnCode'         => $aDataDocument['oetPRBAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPRBFrmBchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'PRB',
                'FTXshRefDocNo'     => $tRefExtDocNo,
                'FDXshRefDocDate'   => $tRefExtDocDate,
                );
                $this->Purchasebranch_model->FSaMPRBUpdateRefExtDocHD($aDataPRBAddDocRef);
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

    //หน้าจอแก้ไข
    public function FSvCPRBEditPage(){
        try {
            $ptBchCode = $this->input->post('ptBchCode');
            $ptAgnCode = $this->input->post('ptAgnCode');
            $ptDocumentNumber = $this->input->post('ptPRBDocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Purchasebranch_model->FSnMPRBDelALLTmp($aWhereClearTemp);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTBchCode'     => $ptBchCode,
                'FTAgnCode'     => $ptAgnCode,
                'FTXphDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TCNTPdtReqHqHD',
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
            $aDataDocHD         = $this->Purchasebranch_model->FSaMPRBGetDataDocHD($aDataWhere);
            
            // Move Data DT TO DTTemp
            $this->Purchasebranch_model->FSxMPRBMoveDTToDTTemp($aDataWhere);
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

                $tViewPageEdit           = $this->load->view('document/purchasebranch/wPurchasebranchPageAdd',$aDataConfigViewEdit,true);
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
    public function FSoCPRBDeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $tAgnCode = $this->input->post('tAgnCode');
            $tRefInDocNo = $this->input->post('tDORefInCode');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->Purchasebranch_model->FSaMPRBUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }

            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tBchCode' => $tBchCode,
                'tAgnCode' => $tAgnCode,
            );

            $aResDelDoc = $this->Purchasebranch_model->FSnMPRBDelDocument($aDataMaster);
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
    public function FSvCPRBCancelDocument() {
        try {
            $tPRBDocNo = $this->input->post('ptPRBDocNo');
            $tRefInDocNo = $this->input->post('ptRefInDocNo');

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->Purchasebranch_model->FSaMPRBUpdatePOStaRef($tRefInDocNo, $nStaRef);
            }

            $aDataUpdate = array(
                'tDocNo' => $tPRBDocNo,
            );

            $aStaApv = $this->Purchasebranch_model->FSaMPRBCancelDocument($aDataUpdate);
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
    public function FSoCPRBApproveEvent(){
        try{
            $tPRBDocNo     = $this->input->post('tPRBDocNo');
            $tAgnCode   = $this->input->post('tAgnCode');
            $tBchCode   = $this->input->post('tBchCode');
            $tRefInDocNo   = $this->input->post('tRefInDocNo');

            if (!empty($tRefInDocNo)) {
                $this->Purchasebranch_model->FSaMPRBUpdatePOStaPrcPRBc($tRefInDocNo);
            }

            // $aMQParams = [
            //     "queueName" => "AP_QDocApprove",
            //     "params"    => [
            //         "ptBchCode"     => $tBchCode,
            //         "ptDocNo"       => $tPRBDocNo,
            //         "ptDocType"     => 13,
            //         "ptUser"        => $this->session->userdata('tSesUsername'),
            //     ]
            // ];
            $aDataGetDataHD     =   $this->Purchasebranch_model->FSaMPRBGetDataDocHD(array(
                'FTBchCode'     => $tBchCode,
                'FTAgnCode'     => $tAgnCode,
                'FTXphDocNo'    => $tPRBDocNo,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            ));
            if($aDataGetDataHD['rtCode']=='1'){
                $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['rtXthDocNo']);
            $aMQParamsNoti = [
                "queueName" => "CN_SendToNoti",
                "tVhostType" => "NOT",
                "params"    => [
                                 "oaTCNTNoti" => array(
                                                 "FNNotID"       => $tNotiID,
                                                 "FTNotCode"     => '00001',
                                                 "FTNotKey"      => 'TCNTPdtReqHqHD',
                                                 "FTNotBchRef"    => $aDataGetDataHD['raItems']['rtBchCode'],
                                                 "FTNotDocRef"   => $aDataGetDataHD['raItems']['rtXthDocNo'],
                                 ),
                                 "oaTCNTNoti_L" => array(
                                                    0 => array(
                                                        "FNNotID"       => $tNotiID,
                                                        "FNLngID"       => 1,
                                                        "FTNotDesc1"    => 'เอกสารใบสั่งซื้อสินค้าจากสาขา #'.$aDataGetDataHD['raItems']['rtXthDocNo'],
                                                        "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['rtBchCode'].' สั่งสินค้าไปยัง HQ',
                                                    ),
                                                    1 => array(
                                                        "FNNotID"       => $tNotiID,
                                                        "FNLngID"       => 2,
                                                        "FTNotDesc1"    => 'Purchase orders from branches #'.$aDataGetDataHD['raItems']['rtXthDocNo'],
                                                        "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['rtBchCode'].' Order Product To HQ',
                                                    )
                                ),
                                 "oaTCNTNotiAct" => array(
                                                     0 => array( 
                                                            "FNNotID"       => $tNotiID,
                                                            "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                                            "FTNoaDesc"          => 'รหัสสาขา '.$aDataGetDataHD['raItems']['rtBchCode'].' สั่งสินค้าไปยัง HQ',
                                                            "FTNoaDocRef"    => $aDataGetDataHD['raItems']['rtXthDocNo'],
                                                            "FNNoaUrlType"   =>  1,
                                                            "FTNoaUrlRef"    => 'docPreOrderb/2/0',
                                                            ),
                                     ), 
                                 "oaTCNTNotiSpc" => array(
                                                    0 => array(
                                                            "FNNotID"       => $tNotiID,
                                                            "FTNotType"    => '1',
                                                            "FTNotStaType" => '1',
                                                            "FTAgnCode"    => '',
                                                            "FTAgnName"    => '',
                                                            "FTBchCode"    => $aDataGetDataHD['raItems']['rtBchCode'],
                                                            "FTBchName"    => $aDataGetDataHD['raItems']['rtBchName'],
                                                    ),
                                                    1 => array(
                                                            "FNNotID"       => $tNotiID,
                                                            "FTNotType"    => '2',
                                                            "FTNotStaType" => '1',
                                                            "FTAgnCode"    => '',
                                                            "FTAgnName"    => '',
                                                            "FTBchCode"    => $aDataGetDataHD['raItems']['rtBchCodeShip'],
                                                            "FTBchName"    => $aDataGetDataHD['raItems']['rtBchNameShip'],
                                                    ),
                                                    2 => array(
                                                            "FNNotID"       => $tNotiID,
                                                            "FTNotType"    => '2',
                                                            "FTNotStaType" => '1',
                                                            "FTAgnCode"    => '',
                                                            "FTAgnName"    => '',
                                                            "FTBchCode"    => $aDataGetDataHD['raItems']['rtBchCodeFrm'],
                                                            "FTBchName"    => $aDataGetDataHD['raItems']['rtBchNameTo'],
                                                    ),
                                 ),
                    "ptUser"        => $this->session->userdata('tSesUsername'),
                ]
            ];
            // echo '<pre>';
            // print_r($aMQParamsNoti);
            // echo '</pre>';
            // die();
            FCNxCallRabbitMQ($aMQParamsNoti);
        }
            // FCNxRabbitMQGetMassage($aMQParamsNoti);
            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTAgnCode'         => $tAgnCode,
                'FTXphDocNo'        => $tPRBDocNo,
                'FTXphStaApv'       => 1,
                'FTXthUsrApv'       => $this->session->userdata('tSesUsername')
            );
            $this->Purchasebranch_model->FSaMPRBApproveDocument($aDataUpdate);

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
    public function FSoCPRBCheckPdtWah()
    {
      $nPdtCode = $this->input->post('nPdtCode');
      $aDataBch = $this->Purchasebranch_model->FSaMPRBGetCheckPdtWah($nPdtCode);
      echo json_encode($aDataBch);
    }
    public function FSoCPRBCheckPdtWahAuto()
    {
        $aWhereClearTemp = [
            'FTXthDocNo' => '',
            'FTXthDocKey' => 'TCNTPdtReqBchHD',
            'FTSessionID' => $this->session->userdata('tSesSessionID')
        ];
        $aWhere = [
            'tBchCode' => $this->input->post('tBchCode'),
            'tWahCode' => $this->input->post('tWahCode'),
            'tSuggesType' => $this->input->post('tSuggesType')
        ];
        $nLangEdit = $this->session->userdata("tLangEdit");
        $this->Purchasebranch_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
        $this->Purchasebranch_model->FSxMPRBClearDataInDocTemp($aWhereClearTemp);
        $aDataBch = $this->Purchasebranch_model->FSaMPRBGetCheckPdtWahAuto($nLangEdit,$aWhere);
        echo json_encode($aDataBch);
    }

        
    public function FSoCPRBCheckRentPdtWahAuto()
    {
        $aWhereClearTemp = [
            'FTXthDocNo' => '',
            'FTXthDocKey' => 'TCNTPdtReqBchHD',
            'FTSessionID' => $this->session->userdata('tSesSessionID')
        ];
        $aWhere = [
            'tBchCode' => $this->input->post('tBchCode'),
            'tWahCode' => $this->input->post('tWahCode'),
            'tSuggesType' => $this->input->post('tSuggesType')
        ];
        $nLangEdit = $this->session->userdata("tLangEdit");
        $this->Purchasebranch_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
        $this->Purchasebranch_model->FSxMPRBClearDataInDocTemp($aWhereClearTemp);
        $aDataBch = $this->Purchasebranch_model->FSaMPRBGetCheckRentPdtWahAuto($aWhere);
        echo json_encode($aDataBch);
    }

    public function FSoCPRBCheckPdtWahAutoPlus()
    {
        $aWhere = [
            'aProduct' => $this->input->post('aProduct'),
            'tBchCode' => $this->input->post('tBchCode'),
            'tWahCode' => $this->input->post('tWahCode'),
            'tSuggesType' => $this->input->post('tSuggesType')
        ];
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataBch = $this->Purchasebranch_model->FSaMPRBCheckSuggestProduct($nLangEdit,$aWhere);
        echo json_encode($aDataBch);
    }

    public function FSoCPRBEditGroupSugges()
    {
        $tPdtCode = $this->input->post('nPdtCode');
        $aDataBch = $this->Purchasebranch_model->FSaMPRBEditGroupProduct($tPdtCode);
        echo json_encode($aDataBch);
    }

    public function FSoCPRBCheckPdtWahAddButton()
    {
        $aWhere = [
            'aProduct' => $this->input->post('aProduct'),
            'tBchCode' => $this->input->post('tBchCode'),
            'tWahCode' => $this->input->post('tWahCode'),
            'tSuggesType' => $this->input->post('tSuggesType')
        ];
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataBch = $this->Purchasebranch_model->FSaMPRBCheckSuggestProductAddButton($nLangEdit,$aWhere);
        echo json_encode($aDataBch);
    }

    //Functionality : Call Export UserLogin Excel
    //Parameters : Call Export UserLogin Excel
    //Creator : 27/08/2021 Off
    //Last Modified : -
    //Return : Excel
    //Return Type : -
    public function FSoCPRBNoStockExcel(){
        $tPRBRefIntBchCode   = $this->input->post('oetPRBRefIntBchCode');
        $tPRBRefIntWahCode   = $this->input->post('oetPRBRefIntWahCode');
        $tPRBRefIntDocNo        = $this->input->post('tPRBRefIntDocNo');
        $oetPRBRefIntPDTCodeFrm   = $this->input->post('oetPRBRefIntPDTCodeFrm');
        $oetPRBRefIntPDTCodeTo   = $this->input->post('oetPRBRefIntPDTCodeTo');
        $tPRBRefIntStaDoc   = $this->input->post('tPRBRefIntStaDoc');
        $nLangEdit = $this->session->userdata("tLangEdit");


        $aDataParamFilter = array(
            'tPRBRefIntBchCode' => $tPRBRefIntBchCode,
            'tPRBRefIntWahCode' => $tPRBRefIntWahCode,
            'tPRBRefIntDocNo' => $tPRBRefIntDocNo,
            'oetPRBRefIntPDTCodeFrm' => $oetPRBRefIntPDTCodeFrm,
            'oetPRBRefIntPDTCodeTo' => $oetPRBRefIntPDTCodeTo,
            'tPRBRefIntStaDoc' => $tPRBRefIntStaDoc,
            'FNLngID' => $nLangEdit,
        );

        $aTestExcel  = $this->Purchasebranch_model->FSoMPRBCallExcelData($aDataParamFilter);
        $aDataReportParams2 = array();
        foreach($aTestExcel['raItems'] as $nkey => $avalue){
            $aDataReportParams2[$nkey]['FTPdtCode'] = $avalue['FTPdtCode'];
            $aDataReportParams2[$nkey]['FTPdtName'] = $avalue['FTPdtName'];
            $aDataReportParams2[$nkey]['FTBchName'] = $avalue['FTBchName'];
            $aDataReportParams2[$nkey]['FTWahName'] = $avalue['FTWahName'];
            $aDataReportParams2[$nkey]['FCStkQty'] = $avalue['FCStkQty'];
        }
        $tFileName = 'PDTNoStock_'.date('YmdHis').'.xlsx';
        $oWriter = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName); // stream data directly to the browser

        $oSheet = $oWriter->getCurrentSheet();
        
        $oSheet->setName('สินค้าหมดสต็อค');

    //  ============================= Sheet ที่1 ==============================================================

        $oStyleColums = (new StyleBuilder())
            ->setBackgroundColor(Color::LIGHT_GREEN)
            ->build();

        $aCells = [
            WriterEntityFactory::createCell('ลำดับ'),
            WriterEntityFactory::createCell('รหัสสินค้า'),
            WriterEntityFactory::createCell('ชื่อสินค้า'),
            WriterEntityFactory::createCell('สาขา'),
            WriterEntityFactory::createCell('คลังสินค้า'),
            WriterEntityFactory::createCell('จำนวนคงคลัง'),
        ];

        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRow($aCells,$oStyleColums);
        $oWriter->addRow($singleRow);

        $oStyle = (new StyleBuilder())
                ->setCellAlignment(CellAlignment::RIGHT)
                ->build();

        foreach ($aDataReportParams2 as $nKey => $aValue) {
        $values= [
                WriterEntityFactory::createCell($nKey+1),
                WriterEntityFactory::createCell($aValue['FTPdtCode']),
                WriterEntityFactory::createCell($aValue['FTPdtName']),
                WriterEntityFactory::createCell($aValue['FTBchName']),
                WriterEntityFactory::createCell($aValue['FTWahName']),
                WriterEntityFactory::createCell($aValue['FCStkQty']),
        ];
        $aRow = WriterEntityFactory::createRow($values);
        $oWriter->addRow($aRow);
    }
    $oWriter->close();
    }
}



/* End of file Controllername.php */
