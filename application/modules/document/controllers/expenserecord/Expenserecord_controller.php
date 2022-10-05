<?php

use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

defined('BASEPATH') or exit('No direct script access allowed');

class Expenserecord_controller extends MX_Controller {

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('document/expenserecord/Expenserecord_model');
        // $this->load->model('document/expenserecord/Expenserecord_modelDisChgModal');
        parent::__construct();
    }

    public function index($nPXBrowseType, $tPXBrowseOption) {
        $aDataConfigView = array(
            'nPXBrowseType'     => $nPXBrowseType,
            'tPXBrowseOption'   => $tPXBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('docPX/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('docPX/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/expenserecord/wExpenseRecord', $aDataConfigView);
    }

    // Functionality    : Function Call Page From Search List
    // Parameters       : Ajax and Function Parameter
    // Creator          : 17/06/2019 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : String View
    // Return Type      : View
    public function FSvCPXFormSearchList() {
        $this->load->view('document/expenserecord/wExpenseRecordFormSearchList');
    }

    // Functionality    : Function Call Page Data Table
    // Parameters       : Ajax and Function Parameter
    // Creator          : 19/06/2018 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object View Data Table
    // Return Type      : object
    public function FSoCPXDataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $aAlwEvent      = FCNaHCheckAlwFunc('docPX/0/0');

            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();

            // Page Current
            if ($nPage == '' || $nPage == null) {
                $nPage      = 1;
            } else {
                $nPage      = $this->input->post('nPageCurrent');
            }

            // Lang ภาษา
            $nLangEdit      = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'aDatSessionUserLogIn'  => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->Expenserecord_model->FSaMPXGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList
            );
            $tPXViewDataTableList = $this->load->view('document/expenserecord/wExpenseRecordDataTable', $aConfigView, true);
            $aReturnData = array(
                'tPXViewDataTableList'  => $tPXViewDataTableList,
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

    // Functionality    : Function Delete Document Purchase Invoice
    // Parameters       : Ajax and Function Parameter
    // Creator          : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object View Data Table
    // Return Type      : object
    public function FSoCPXDeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Expenserecord_model->FSnMPXDelDocument($aDataMaster);
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

    // Functionality    : Function Call Page Add Tranfer Out
    // Parameters       : Ajax and Function Parameter
    // Creator          : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object View Page Add
    // Return Type      : object
    public function FSoCPXAddPage() {
        try {
            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => '',
                'FTXthDocKey' => 'TAPTPxHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];

            $this->Expenserecord_model->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->Expenserecord_model->FSxMPXClearDataInDocTemp($aWhereClearTemp);

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
                'tDataDocKey' => 'TAPTPxHD',
                'tDataSeqNo' => ''
            );
            FCNbHCallCalcDocDTTemp($aWhereHelperCalcDTTemp);

            $aDataWhere = array(
                'FNLngID' => $nLangEdit
            );

            $tAPIReq        = "";
            $tMethodReq     = "GET";
            $aCompData      = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhere);

            if (isset($aCompData) && $aCompData['rtCode'] == '1') {
                $tBchCode       = $aCompData['raItems']['rtCmpBchCode'];
                $tCmpRteCode    = $aCompData['raItems']['rtCmpRteCode'];
                $tVatCode       = $aCompData['raItems']['rtVatCodeUse'];
                $tCmpRetInOrEx  = $aCompData['raItems']['rtCmpRetInOrEx'];
                $aVatRate       = FCNoHCallVatlist($tVatCode);
                if (isset($aVatRate) && !empty($aVatRate)) {
                    $cVatRate   = $aVatRate['FCVatRate'][0];
                } else {
                    $cVatRate   = "";
                }
                $aDataRate = array(
                    'FTRteCode' => $tCmpRteCode,
                    'FNLngID'   => $nLangEdit
                );
                $aResultRte = $this->mRate->FSaMRTESearchByID($aDataRate);
                if (isset($aResultRte) && $aResultRte['rtCode']) {
                    $cXthRteFac = $aResultRte['raItems']['rcRteRate'];
                } else {
                    $cXthRteFac = "";
                }
            } else {
                $tBchCode       = FCNtGetBchInComp();
                $tCmpRteCode    = "";
                $tVatCode       = "";
                $cVatRate       = "";
                $cXthRteFac     = "";
                $tCmpRetInOrEx  = "1";
            }

            // Get Department Code
            $tUsrLogin = $this->session->userdata('tSesUsername');
            $tDptCode = FCNnDOCGetDepartmentByUser($tUsrLogin);

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            // $aDataShp = array(
            //     'FNLngID'       => $nLangEdit,
            //     'tUsrLogin'     => $tUsrLogin
            // );
            // $aDataUserGroup = $this->Expenserecord_model->FSaPXGetShpCodeForUsrLogin($aDataShp);
            // if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
            //     $tBchCode = "";
            //     $tBchName = "";
            //     $tMerCode = "";
            //     $tMerName = "";
            //     $tShopType = "";
            //     $tShopCode = "";
            //     $tShopName = "";
            //     $tWahCode = "";
            //     $tWahName = "";
            // } else {
            //     $tBchCode = $aDataUserGroup["FTBchCode"];
            //     $tBchName = $aDataUserGroup["FTBchName"];
            //     $tMerCode = $aDataUserGroup["FTMerCode"];
            //     $tMerName = $aDataUserGroup["FTMerName"];
            //     $tShopType = $aDataUserGroup["FTShpType"];
            //     $tShopCode = $aDataUserGroup["FTShpCode"];
            //     $tShopName = $aDataUserGroup["FTShpName"];
            //     $tWahCode = $aDataUserGroup["FTWahCode"];
            //     $tWahName = $aDataUserGroup["FTWahName"];
            // }

            // ดึงข้อมูลที่อยู่คลัง Defult ในตาราง TSysConfig
            // $aConfigSys = [
            //     'FTSysCode' => 'tPS_Warehouse',
            //     'FTSysSeq' => 3,
            //     'FNLngID' => $nLangEdit
            // ];
            // $aConfigSysWareHouse = $this->Expenserecord_model->FSaMPXGetDefOptionConfigWah($aConfigSys);

            $aDataConfigViewAdd = array(
                'nOptDecimalShow' => $nOptDecimalShow,
                'nOptDocSave' => $nOptDocSave,
                'nOptScanSku' => $nOptScanSku,
                'tCmpRteCode' => $tCmpRteCode,
                'tVatCode' => $tVatCode,
                'cVatRate' => $cVatRate,
                'cXthRteFac' => $cXthRteFac,
                'tDptCode' => $tDptCode,
                // 'tBchCode' => $tBchCode,
                // 'tBchName' => $tBchName,
                // 'tMerCode' => $tMerCode,
                // 'tMerName' => $tMerName,
                // 'tShopType' => $tShopType,
                // 'tShopCode' => $tShopCode,
                // 'tShopName' => $tShopName,
                // 'tWahCode' => $tWahCode,
                // 'tWahName' => $tWahName,
                // 'tBchCompCode' => FCNtGetBchInComp(),
                // 'tBchCompName' => FCNtGetBchNameInComp(),
                // 'aConfigSysWareHouse' => $aConfigSysWareHouse,
                'aDataDocHD' => array('rtCode' => '800'),
                // 'aDataDocHDRef' => array('tCode' => '800'),
                'aDataDocHDSpl' => array('rtCode' => '800'),
                'tCmpRetInOrEx' => $tCmpRetInOrEx,
            );
            $tPXViewPageAdd = $this->load->view('document/expenserecord/wExpenseRecordAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tPXViewPageAdd' => $tPXViewPageAdd,
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

    // Functionality    : Function Call Page Edit Tranfer Out
    // Parameters       : Ajax and Function Parameter
    // Creator          : 19/06/2019 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object View Page Add
    // Return Type      : object
    public function FSoCPXEditPage() {
        try {
            $tPXDocNo = $this->input->post('ptPXDocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTXthDocNo'  => $tPXDocNo,
                'FTXthDocKey' => 'TAPTPxHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->Expenserecord_model->FSxMPXClearDataInDocTemp($aWhereClearTemp);

            // Get Autentication Route
            $aAlwEvent          = FCNaHCheckAlwFunc('docPX/0/0');
            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave        = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku        = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            // $tUsrLogin = $this->session->userdata('tSesUsername');
            // $aDataShp = array(
            //     'FNLngID'       => $nLangEdit,
            //     'tUsrLogin'     => $tUsrLogin
            // );

            // $aDataUserGroup = $this->Expenserecord_model->FSaPXGetShpCodeForUsrLogin($aDataShp);
            // if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
            //     $tUsrBchCode    = "";
            //     $tUsrBchName    = "";
            //     $tUsrMerCode    = "";
            //     $tUsrMerName    = "";
            //     $tUsrShopType   = "";
            //     $tUsrShopCode   = "";
            //     $tUsrShopName   = "";
            // } else {
            //     $tUsrBchCode    = $aDataUserGroup["FTBchCode"];
            //     $tUsrBchName    = $aDataUserGroup["FTBchName"];
            //     $tUsrMerCode    = $aDataUserGroup["FTMerCode"];
            //     $tUsrMerName    = $aDataUserGroup["FTMerName"];
            //     $tUsrShopType   = $aDataUserGroup["FTShpType"];
            //     $tUsrShopCode   = $aDataUserGroup["FTShpCode"];
            //     $tUsrShopName   = $aDataUserGroup["FTShpName"];
            // }

            // Data Table Document
            // $aTableDocument = array(
            //     'tTableHD'      => 'TAPTPxHD',
            //     'tTableHDSpl'   => 'TAPTPxHDSpl',
            //     'tTableHDDis'   => 'TAPTPxHDDis',
            //     'tTableDT'      => 'TAPTPxDT',
            //     'tTableDTDis'   => 'TAPTPxDTDis'
            // );

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo'    => $tPXDocNo,
                'FTXthDocKey'   => 'TAPTPxHD',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 10000,
                'nPage'         => 1,
                'FTSessionID'   =>  $this->session->userdata('tSesSessionID')
                // 'tTableDTFhn'   => 'TAPTPxDTFhn'
            );

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD = $this->Expenserecord_model->FSaMPXGetDataDocHD($aDataWhere);

            // Get Data Document HD Doc Ref
            // $aDataDocHDRef = $this->Expenserecord_model->FSaMPXGetDataDocHDRef($aDataWhere);

            // Get Data Document HD Spl
            $aDataDocHDSpl = $this->Expenserecord_model->FSaMPXGetDataDocHDSpl($aDataWhere);

            // Move Data HD DIS To HD DIS Temp
            $this->Expenserecord_model->FSxMPXMoveHDDisToTemp($aDataWhere);

            // Move Data DT TO DTTemp
            $this->Expenserecord_model->FSxMPXMoveDTToDTTemp($aDataWhere);

            // Move Data DTDIS TO DTDISTemp
            $this->Expenserecord_model->FSxMPXMoveDTDisToDTDisTemp($aDataWhere);

            // Move Data HDDocRef TO HDRefTemp
            $this->Expenserecord_model->FSxMPXMoveHDRefToHDRefTemp($aDataWhere);

            // $this->Expenserecord_model->FCNxMPIMoveDTToDTFhnTemp($aDataWhere); // Move DT To DT Temp Fashion

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();
                // Prorate HD
                FCNaHCalculateProrate('TAPTPxHD', $tPXDocNo);
                $tPXVATInOrEx = ($aDataDocHD['rtCode'] == '1') ? $aDataDocHD['raItems']['FTXphVATInOrEx'] : 1;
                $aCalcDTTempParams = array(
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tPXVATInOrEx,
                    'tDataDocNo'        => $tPXDocNo,
                    'tDataDocKey'       => 'TAPTPxHD',
                    'tDataSeqNo'        => ""
                );
                $tStaCalDocDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTTempParams);

                $tAPIReq    = "";
                $tMethodReq = "GET";
                $aCompData  = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhere);

                //หาว่าผู้จำหน่ายของเอกสารนี้ใช้ VAT อะไร
                $aDetailSPL = $this->Expenserecord_model->FSxMPXFindDetailSPL($aDataWhere);

                if (isset($aCompData) && $aCompData['rtCode'] == '1') {
                    $tVatCode = $aCompData['raItems']['rtVatCodeUse'];
                    $tCmpRetInOrEx = $aCompData['raItems']['rtCmpRetInOrEx'];
                    $aVatRate = FCNoHCallVatlist($tVatCode);
                    if (isset($aVatRate) && !empty($aVatRate)) {
                        $cVatRate = $aVatRate['FCVatRate'][0];
                    } else {
                        $cVatRate = "";
                    }
                } else {
                    $tVatCode       = "";
                    $cVatRate       = "";
                    $tCmpRetInOrEx  = "1";
                }

                $aDataConfigViewAdd = array(
                    'nOptDecimalShow' => $nOptDecimalShow,
                    'nOptDocSave'   => $nOptDocSave,
                    'nOptScanSku'   => $nOptScanSku,
                    // 'tUserBchCode'  => $tUsrBchCode,
                    // 'tUserBchName'  => $tUsrBchName,
                    // 'tUsrMerCode'   => $tUsrMerCode,
                    // 'tUsrMerName'   => $tUsrMerName,
                    // 'tUsrShopType'  => $tUsrShopType,
                    // 'tUsrShopCode'  => $tUsrShopCode,
                    // 'tUsrShopName'  => $tUsrShopName,
                    // 'tBchCompCode'  => FCNtGetBchInComp(),
                    // 'tBchCompName'  => FCNtGetBchNameInComp(),
                    'aDataDocHD'    => $aDataDocHD,
                    // 'aDataDocHDRef' => $aDataDocHDRef,
                    'aDataDocHDSpl' => $aDataDocHDSpl,
                    'aAlwEvent'     => $aAlwEvent,
                    'tCmpRetInOrEx' => $tCmpRetInOrEx,
                    'cVatRate'      => $cVatRate,
                    'aDetailSPL'    => $aDetailSPL
                );
                $tPXViewPageEdit = $this->load->view('document/expenserecord/wExpenseRecordAdd', $aDataConfigViewAdd, true);
                $aReturnData = array(
                    'tPXViewPageEdit' => $tPXViewPageEdit,
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

    // Functionality    : Call View Table Data Doc DT Temp
    // Parameters       : Ajax and Function Parameter
    // Creator          : 28/06/2018 wasin(Yoshi AKA: Mr.JW)
    // Return           : Object  View Table Data Doc DT Temp
    // Return Type      : object
    public function FSoCPXPdtAdvTblLoadData() {
        try {
            $tPXDocNo               = $this->input->post('ptPXDocNo');
            $tPXStaApv              = $this->input->post('ptPXStaApv');
            $tPXStaDoc              = $this->input->post('ptPXStaDoc');
            $tPXVATInOrEx           = $this->input->post('ptPXVATInOrEx');
            $nPXPageCurrent         = $this->input->post('pnPXPageCurrent');
            $tSearchPdtAdvTable     = $this->input->post('ptSearchPdtAdvTable');
            // Edit in line
            $tPXPdtCode             = $this->input->post('ptPXPdtCode');
            $tPXPunCode             = $this->input->post('ptPXPunCode');

            //Get Option Show Decimal
            $nOptDecimalShow        = FCNxHGetOptionDecimalShow();

            // Call Advance Table
            $tTableGetColumeShow    = 'TAPTPxDT';
            $aColumnShow            = FCNaDCLGetColumnShow($tTableGetColumeShow);

            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tPXDocNo,
                'FTXthDocKey'           => 'TAPTPxHD',
                'nPage'                 => $nPXPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );

            // Calcurate Document DT Temp Array Parameter
            $aCalcDTParams = [
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tPXVATInOrEx,
                'tDataDocNo'        => $tPXDocNo,
                'tDataDocKey'       => 'TAPTPxHD',
                'tDataSeqNo'        => ''
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp     = $this->Expenserecord_model->FSaMPXGetDocDTTempListPage($aDataWhere);

            $aDataDocDTTempSum  = $this->Expenserecord_model->FSaMPXSumDocDTTemp($aDataWhere);

            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tPXStaApv'         => $tPXStaApv,
                'tPXStaDoc'         => $tPXStaDoc,
                'tPXPdtCode'        => $tPXPdtCode,
                'tPXPunCode'        => $tPXPunCode,
                'nPage'             => $nPXPageCurrent,
                'aColumnShow'       => $aColumnShow,
                'aDataDocDTTemp'    => $aDataDocDTTemp,
                'aDataDocDTTempSum' => $aDataDocDTTempSum,
            );

            $tPXPdtAdvTableHtml = $this->load->view('document/expenserecord/wExpenseRecordPdtAdvTableData', $aDataView, true);

            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'       => $tPXVATInOrEx,
                'tDocNo'            => $tPXDocNo,
                'tDocKey'           => 'TAPTPxHD',
                'nLngID'            => FCNaHGetLangEdit(),
                'tSesSessionID'     => $this->session->userdata('tSesSessionID'),
                'tBchCode'          => $this->input->post('tBCHCode')
            );

            //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล supawat 03-04-2020
            $aPXEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aPXEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aPXEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aPXEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

            $aPackDataCalCulate = array(
                'tDocNo'        => $tPXDocNo,
                'tBchCode'      => '',
                'nB4Dis'        => $aPXEndOfBill['aEndOfBillCal']['cSumFCXtdNet'],
                'tSplVatType'   => $tPXVATInOrEx
            );
            $tCalculateAgain = FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
            if($tCalculateAgain == 'CHANGE'){
                $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);

                if($aStaCalcDTTemp === TRUE){
                    FCNaHCalculateProrate('TAPTPxHD',$aPackDataCalCulate['tDocNo']);
                    FCNbHCallCalcDocDTTemp($aCalcDTParams);
                }

                $aPXEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
                $aPXEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
                $aPXEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aPXEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
            }

            $aReturnData = array(
                'tPXPdtAdvTableHtml'    => $tPXPdtAdvTableHtml,
                'aPXEndOfBill'          => $aPXEndOfBill,
                'nStaEvent'             => '1',
                'tStaMessg'             => "Fucntion Success Return View.",
                'tCalculateAgain'       => ''
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : Call View Table Manage Advance Table
    // Parameters       : Document Type
    // Creator          : 01/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object View Advance Table
    // ReturnType       : Object
    // public function FSoCPXAdvTblShowColList() {
    //     try {
    //         $tTableShowColums = 'TAPTPxDT';
    //         $aAvailableColumn = FCNaDCLAvailableColumn($tTableShowColums);
    //         $aDataViewAdvTbl = array(
    //             'aAvailableColumn' => $aAvailableColumn
    //         );
    //         $tViewTableShowCollist = $this->load->view('document/expenserecord/advancetable/wExpenseRecordTableShowColList', $aDataViewAdvTbl, true);
    //         $aReturnData = array(
    //             'tViewTableShowCollist' => $tViewTableShowCollist,
    //             'nStaEvent' => '1',
    //             'tStaMessg' => 'Success'
    //         );
    //     } catch (Exception $Error) {
    //         $aReturnData = array(
    //             'nStaEvent' => '500',
    //             'tStaMessg' => $Error->getMessage()
    //         );
    //     }
    //     echo json_encode($aReturnData);
    // }

    // Function         : Save Columns Advance Table
    // Parameters       : Data Save Colums Advance Table
    // Creator          : 01/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object Sta Save Advance Table
    // ReturnType       : Object
    // public function FSoCPXAdvTalShowColSave() {
    //     try {
    //         $this->db->trans_begin();

    //         $nPXStaSetDef = $this->input->post('pnPXStaSetDef');
    //         $aPXColShowSet = $this->input->post('paPXColShowSet');
    //         $aPXColShowAllList = $this->input->post('paPXColShowAllList');
    //         $aPXColumnLabelName = $this->input->post('paPXColumnLabelName');
    //         // Table Set Show Colums
    //         $tTableShowColums = "TAPTPxDT";
    //         FCNaDCLSetShowCol($tTableShowColums, '', '');
    //         if ($nPXStaSetDef == '1') {
    //             FCNaDCLSetDefShowCol($tTableShowColums);
    //         } else {
    //             for ($i = 0; $i < FCNnHSizeOf($aPXColShowSet); $i++) {
    //                 FCNaDCLSetShowCol($tTableShowColums, 1, $aPXColShowSet[$i]);
    //             }
    //         }
    //         // Reset Seq Advannce Table
    //         FCNaDCLUpdateSeq($tTableShowColums, '', '', '');
    //         $q = 1;
    //         for ($n = 0; $n < FCNnHSizeOf($aPXColShowAllList); $n++) {
    //             FCNaDCLUpdateSeq($tTableShowColums, $aPXColShowAllList[$n], $q, $aPXColumnLabelName[$n]);
    //             $q++;
    //         }

    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $aReturnData = array(
    //                 'nStaEvent' => '500',
    //                 'tStaMessg' => 'Eror Not Save Colums'
    //             );
    //         } else {
    //             $this->db->trans_commit();
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

    // Function         : Add สินค้า ลง Document DT Temp
    // Parameters       : Document Type
    // Creator          : 02/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object Status Add Pdt To Doc DT Temp
    // ReturnType       : Object
    public function FSoCPXAddPdtIntoDocDTTemp() {
        try {
            $tPXUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tPXDocNo           = $this->input->post('tPXDocNo');
            $tPXVATInOrEx       = $this->input->post('tPXVATInOrEx');
            $tPXBchCode         = $this->input->post('tBCHCode');
            $tPXOptionAddPdt    = $this->input->post('tPXOptionAddPdt');
            $tPXPdtData         = $this->input->post('tPXPdtData');
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');
            $nPriceWaitScan     = $this->input->post('nPriceWaitScan');
            
            $aPXPdtData         = json_decode($tPXPdtData);

            $aDataWhere = array(
                'FTBchCode'     => $tPXBchCode,
                'FTXthDocNo'    => $tPXDocNo,
                'FTXthDocKey'   => 'TAPTPxHD'
            );

            $this->db->trans_begin();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPXPdtData); $nI++) {
                $tPXPdtCode     = $aPXPdtData[$nI]->pnPdtCode;
                $tPXBarCode     = $aPXPdtData[$nI]->ptBarCode;
                $tPXPunCode     = $aPXPdtData[$nI]->ptPunCode;
                if($nPriceWaitScan!=''){
                    $CPXPrice       = $nPriceWaitScan;
                }else{
                    $CPXPrice       = $aPXPdtData[$nI]->packData->Price;
                }

                // $nPXMaxSeqNo = $this->Expenserecord_model->FSaMPXGetMaxSeqDocDTTemp($aDataWhere);
                $aDataPdtParams = array(
                    'tDocNo'            => $tPXDocNo,
                    'tBchCode'          => $tPXBchCode,
                    'tPdtCode'          => $tPXPdtCode,
                    'tBarCode'          => $tPXBarCode,
                    'tPunCode'          => $tPXPunCode,
                    'cPrice'            => str_replace(',', '', $CPXPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->session->userdata("tLangID"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TAPTPxHD',
                    'tPXOptionAddPdt'   => $tPXOptionAddPdt,
                    'nVatRate'          => $nVatRate,
                    'nVatCode'          => $nVatCode
                );

                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Expenserecord_model->FSaMPXGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $nStaInsPdtToTmp = $this->Expenserecord_model->FSaMPXInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();
                // Calcurate Document DT Temp Array Parameter
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '1',
                    'tDataVatInOrEx' => $tPXVATInOrEx,
                    'tDataDocNo' => $tPXDocNo,
                    'tDataDocKey' => 'TAPTPxHD',
                    'tDataSeqNo' => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
                    // Prorate HD
                    // FCNaHCalculateProrate('TAPTPxHD', $tPXDocNo);
                    // FCNbHCallCalcDocDTTemp($aCalcDTParams);

                    $this->FSxCalculateHDDisAgain($tPXDocNo,$tPXBchCode);
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Add Product Into Document DT Temp.'
                    );
                } else {
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Calcurate Document DT Temp Please Contact Admin.'
                    );
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : Edit Inline สินค้า ลง Document DT Temp
    // Parameters       : Document Type
    // Creator          : 02/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object Status Edit Pdt To Doc DT Temp
    // ReturnType       : Object
    public function FSoCPXEditPdtIntoDocDTTemp() {
        try {
            $tPXBchCode         = $this->input->post('tPXBchCode');
            $tPXDocNo           = $this->input->post('tPXDocNo');
            $tPXVATInOrEx       = $this->input->post('tPXVATInOrEx');
            $nPXSeqNo           = $this->input->post('nPXSeqNo');
            $nPXIsDelDTDis      = $this->input->post('nStaDelDis');
            $tPXSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tPXBchCode'    => $tPXBchCode,
                'tPXDocNo'      => $tPXDocNo,
                'nPXSeqNo'      => $nPXSeqNo,
                'tPXSessionID'  => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'TAPTPxHD',
                'nPXStaDis'     =>  $nPXIsDelDTDis 
            );
            $aDataUpdateDT = array(
                // 'tPXFieldName'  => $tPXFieldName,
                // 'tPXValue'      => $tPXValue,
                'FCXtdQty'      => $this->input->post('nQty'),
                'FCXtdSetPrice' => $this->input->post('cPrice'),
                'FCXtdNet'      => $this->input->post('cNet')
            );

            $this->db->trans_begin();

            $this->Expenserecord_model->FSaMPXUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

            if ($nPXIsDelDTDis == '1') {
                // ยืนยันการลบ DTDis ส่วนลดรายการนี้
                $this->Expenserecord_modelDisChgModal->FSaMPXDeleteDTDisTemp($aDataWhere);
                $this->Expenserecord_modelDisChgModal->FSaMPXClearDisChgTxtDTTemp($aDataWhere);
            }

            //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง CR:Supawat
            /*****************************************************************/
            /**/    $this->FSxCalculateHDDisAgain($tPXDocNo,$tPXBchCode);  /**/
            /*****************************************************************/

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => "Error Update Inline Into Document DT Temp."
                );
            } else {
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

    // Function         : Remove Product In Documeny Temp
    // Parameters       : Document Type
    // Creator          : 14/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object Status Edit Pdt To Doc DT Temp
    // ReturnType       : Object
    public function FSvCPXRemovePdtInDTTmp() {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tBchCode' => $this->input->post('tBchCode'),
                'tDocNo' => $this->input->post('tDocNo'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo' => $this->input->post('nSeqNo'),
                'tVatInOrEx' => $this->input->post('tVatInOrEx'),
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Expenserecord_model->FSnMPXDelPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();

                //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง CR:Supawat
                /*****************************************************************/
                /**/    $tPXDocNo   = $this->input->post('tDocNo');            /**/
                /**/    $tPXBchCode = $this->input->post('tBchCode');          /**/
                /**/    $this->FSxCalculateHDDisAgain($tPXDocNo,$tPXBchCode);  /**/
                /*****************************************************************/

                // ถ้าลบสินค้า ต้องวิ่งไปเช็คด้วยว่า มีท้ายบิล ไหม ถ้าสินค้าที่เหลืออยู่ไม่อนุญาตลด ท้ายบิลก็ต้องลบทิ้งด้วย
                // $aPackDataCalCulate = array(
                //     'tDocNo'        => $this->input->post('tDocNo'),
                //     'tBchCode'      => $this->input->post('tBchCode'),
                //     'nB4Dis'        => '',
                //     'tSplVatType'   => $this->input->post('tVatInOrEx')
                // );
                // FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);

                // Prorate HD
                // FCNaHCalculateProrate('TAPTPxHD', $aDataWhere['tDocNo']);
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TAPTPxHD',
                    'tDataSeqNo' => ''
                ];
                FCNbHCallCalcDocDTTemp($aCalcDTParams);
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

    // Function         : Remove Product In Documeny Temp Multiple
    // Parameters       : Document Type
    // Creator          : 26/07/2019 wasin(Yoshi AKA: Mr.JW)
    // LastUpdate       : -
    // Return           : Object Status Event Delte
    // ReturnType       : Object
    public function FSvCPXRemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tBchCode' => $this->input->post('ptPXBchCode'),
                'tDocNo' => $this->input->post('ptPXDocNo'),
                'tVatInOrEx' => $this->input->post('ptPXVatInOrEx'),
                'aDataPdtCode' => $this->input->post('paDataPdtCode'),
                'aDataPunCode' => $this->input->post('paDataPunCode'),
                'aDataSeqNo' => $this->input->post('paDataSeqNo')
            );

            $aStaDelPdtDocTemp = $this->Expenserecord_model->FSnMPXDelMultiPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();

                //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง CR:Supawat
                /*****************************************************************/
                /**/    $tPXDocNo   = $this->input->post('ptPXDocNo');         /**/
                /**/    $tPXBchCode = $this->input->post('ptPXBchCode');       /**/
                /**/    $this->FSxCalculateHDDisAgain($tPXDocNo,$tPXBchCode);  /**/
                /*****************************************************************/

                //ถ้าลบสินค้า ต้องวิ่งไปเช็คด้วยว่า มีท้ายบิล ไหม ถ้าสินค้าที่เหลืออยู่ไม่อนุญาตลด ท้ายบิลก็ต้องลบทิ้งด้วย
                // $aPackDataCalCulate = array(
                //     'tDocNo'        => $this->input->post('ptPXDocNo'),
                //     'tBchCode'      => $this->input->post('ptPXBchCode'),
                //     'nB4Dis'        => '',
                //     'tSplVatType'   => $this->input->post('ptPXVatInOrEx')
                // );
                // FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);

                // Prorate HD
                FCNaHCalculateProrate('TAPTPxHD', $aDataWhere['tDocNo']);
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TAPTPxHD',
                    'tDataSeqNo' => ''
                ];
                FCNbHCallCalcDocDTTemp($aCalcDTParams);
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

    // Function         : Check Product Have In Temp For Document DT
    // Parameters       : Ajex Event Before Save DT
    // Creator          : 03/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Check Product DT Temp
    // ReturnType       : Object
    public function FSoCPXChkHavePdtForDocDTTemp() {
        try {
            $tPXDocNo = $this->input->post("ptPXDocNo");
            $tPXSessionID = $this->session->userdata('tSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tPXDocNo,
                'FTXthDocKey' => 'TAPTPxHD',
                'FTSessionID' => $tPXSessionID
            );
            $nCountPdtInDocDTTemp = $this->Expenserecord_model->FSnMPXChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/expenserecord/expenserecord', 'tPXPleaseSeletedPDTIntoTable')
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

    // Function         : คำนวณค่าจาก DT Temp ให้ HD
    // Parameters       : Ajex Event Add Document
    // Creator          : 04/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Array Data Calcurate DocDTTemp For HD
    // ReturnType       : Array
    private function FSaCPXCalDTTempForHD($paParams) {
        $aCalDTTemp = $this->Expenserecord_model->FSaMPXCalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems = $aCalDTTemp[0];

            // จะใช้งานได้เมื่อเอกสารขาขาย และชำระเงินแบบเงินสด
            // คำนวณหา ยอดปัดเศษ ให้ HD(FCXphRnd)
            // $pCalRoundParams = [
            //     'FCXphAmtV' => $aCalDTTempItems['FCXphAmtV'],
            //     'FCXphAmtNV' => $aCalDTTempItems['FCXphAmtNV']
            // ];
            // $aRound = $this->FSaCPXCalRound($pCalRoundParams);
            // // คำนวณหา ยอดรวม ให้ HD(FCXphGrand)
            // $nRound = $aRound['nRound'];
            // $cGrand = $aRound['cAfRound'];

            $nRound = 0;
            $cGrand = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV'];

            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXphRnd'] = $nRound;
            $aCalDTTempItems['FCXphGrand'] = $cGrand;
            $aCalDTTempItems['FTXphGndText'] = $tGndText;
            return $aCalDTTempItems;
        }
    }

    // Function         : หาค่าปัดเศษ HD(FCXphRnd)
    // Parameters       : Ajex Event Add Document
    // Creator          : 04/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Array ค่าปักเศษ
    // ReturnType       : Array
    private function FSaCPXCalRound($paParams) {
        $tOptionRound = '1';  // ปัดขึ้น
        $cAmtV = $paParams['FCXphAmtV'];
        $cAmtNV = $paParams['FCXphAmtNV'];
        $cBath = $cAmtV + $cAmtNV;
        // ตัดเอาเฉพาะทศนิยม
        $nStang = explode('.', number_format($cBath, 2))[1];
        $nPoint = 0;
        $nRound = 0;
        /* ====================== ปัดขึ้น ================================ */
        if ($tOptionRound == '1') {
            if ($nStang >= 1 and $nStang < 25) {
                $nPoint = 25;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 25 and $nStang < 50) {
                $nPoint = 50;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 50 and $nStang < 75) {
                $nPoint = 75;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 75 and $nStang < 100) {
                $nPoint = 100;
                $nRound = $nPoint - $nStang;
            }
        }
        /* ====================== ปัดขึ้น ================================ */

        /* ====================== ปัดลง ================================ */
        if ($tOptionRound != '1') {
            if ($nStang >= 1 and $nStang < 25) {
                $nPoint = 1;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 25 and $nStang < 50) {
                $nPoint = 25;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 50 and $nStang < 75) {
                $nPoint = 50;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 75 and $nStang < 100) {
                $nPoint = 75;
                $nRound = $nPoint - $nStang;
            }
        }
        /* ====================== ปัดลง ================================ */
        $cAfRound = floatval($cBath) + floatval($nRound / 100);
        return [
            'tRoundType' => $tOptionRound,
            'cBath' => $cBath,
            'nPoint' => $nPoint,
            'nStang' => $nStang,
            'nRound' => $nRound,
            'cAfRound' => $cAfRound
        ];
    }

    // Function         : Add Document
    // Parameters       : Ajex Event Add Document
    // Creator          : 03/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Add Document
    // ReturnType       : Object
    public function FSoCPXAddEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tPXAutoGenCode = (isset($aDataDocument['ocbPXStaAutoGenCode'])) ? 1 : 0;
            $tPXDocNo = (isset($aDataDocument['oetPXDocNo'])) ? $aDataDocument['oetPXDocNo'] : '';
            $tPXDocDate = $aDataDocument['oetPXDocDate'] . " " . $aDataDocument['oetPXDocTime'];
            // $tPXStaDocAct = (isset($aDataDocument['ocbPXFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tPXStaDocAct = $aDataDocument['ocbPXFrmInfoOthStaDocAct'];
            $tPXVATInOrEx = $aDataDocument['ocmPXFrmSplInfoVatInOrEx'];
            $tPXSessionID = $this->session->userdata('tSesSessionID');

            // Get Data Comp.
            // $nLangEdit = $this->session->userdata("tLangEdit");
            // $aDataWhereComp = array('FNLngID' => $nLangEdit);
            // $tAPIReq = "";
            // $tMethodReq = "GET";
            // $aCompData = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);

            $aResProrat = FCNaHCalculateProrate('TAPTPxHD',$tPXDocNo);

            $aCalcDTParams = [
                'tBchCode'          => $aDataDocument['oetPXFrmBchCode'],
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tPXVATInOrEx,
                'tDataDocNo'        => $tPXDocNo,
                'tDataDocKey'       => 'TAPTPxHD',
                'tDataSeqNo'        => ''
            ];

            $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);

            // Prorate HD
            FCNaHCalculateProrate('TAPTPxHD', $tPXDocNo);

            $aCalcDTParams = [
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tPXVATInOrEx,
                'tDataDocNo'        => $tPXDocNo,
                'tDataDocKey'       => 'TAPTPxHD',
                'tDataSeqNo'        => ''
            ];

            $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aCalDTTempParams = [
                'tDocNo'            => $tPXDocNo,
                'tBchCode'          => $aDataDocument['oetPXFrmBchCode'],
                'tSessionID'        => $tPXSessionID,
                'tDocKey'           => 'TAPTPxHD',
                'tDataVatInOrEx'    => $tPXVATInOrEx,
            ];

            $this->Expenserecord_model->FSaMPXCalVatLastDT($aCalDTTempParams);

            $aCalDTTempForHD = $this->FSaCPXCalDTTempForHD($aCalDTTempParams);

            $aCalInHDDisTemp = $this->Expenserecord_model->FSaMPXCalInHDDisTemp($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TAPTPxHD',
                'tTableHDDis'   => 'TAPTPxHDDis',
                'tTableHDSpl'   => 'TAPTPxHDSpl',
                'tTableDT'      => 'TAPTPxDT',
                'tTableDTDis'   => 'TAPTPxDTDis',
                'tTableHDRef'   => 'TAPTPxHDDocRef',
                'tTableStaGen'  => 14,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode' => $aDataDocument['oetPXAgnCode'],
                'FTBchCode' => $aDataDocument['oetPXFrmBchCode'],
                'FTXphDocNo' => $tPXDocNo,
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FDCreateOn' => date('Y-m-d H:i:s'),
                'FTCreateBy' => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
                'FTSessionID' => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx' => $tPXVATInOrEx
            );

            // Array Data HD Master
            $aDataMaster = array(
                // 'FTShpCode' => $aDataDocument['oetPXFrmShpCode'],
                'FNXphDocType' => 14,
                'FDXphDocDate' => (!empty($tPXDocDate)) ? $tPXDocDate : NULL,
                'FTXphCshOrCrd' => $aDataDocument['ocmPXFrmSplInfoPaymentType'],
                'FTXphVATInOrEx' => $tPXVATInOrEx,
                'FTDptCode' => $aDataDocument['ohdPXDptCode'],
                // 'FTWahCode' => $aDataDocument['oetPXFrmWahCode'],
                'FTUsrCode' => $aDataDocument['ohdPXUsrCode'],
                'FTSplCode' => $aDataDocument['oetPXFrmSplCode'],
                // 'FTXphRefExt' => $aDataDocument['oetPXRefExtDoc'],
                // 'FDXphRefExtDate' => (!empty($aDataDocument['oetPXRefExtDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXRefExtDocDate'])) : NULL,
                // 'FTXphRefInt' => $aDataDocument['oetPXRefIntDoc'],
                // 'FDXphRefIntDate' => (!empty($aDataDocument['oetPXRefIntDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXRefIntDocDate'])) : NULL,
                'FNXphDocPrint' => $aDataDocument['ocmPXFrmInfoOthDocPrint'],
                'FTRteCode' => $aDataDocument['ohdPXCmpRteCode'],
                'FCXphRteFac' => $aDataDocument['ohdPXRteFac'],
                'FCXphTotal' => $aCalDTTempForHD['FCXphTotal'],
                'FCXphTotalNV' => $aCalDTTempForHD['FCXphTotalNV'],
                'FCXphTotalNoDis' => $aCalDTTempForHD['FCXphTotalNoDis'],
                'FCXphTotalB4DisChgV' => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                'FCXphTotalB4DisChgNV' => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                'FTXphDisChgTxt' => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                'FCXphDis' => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                'FCXphChg' => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                'FCXphTotalAfDisChgV' => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                'FCXphTotalAfDisChgNV' => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                'FCXphAmtV' => $aCalDTTempForHD['FCXphAmtV'],
                'FCXphAmtNV' => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXphVat' => $aCalDTTempForHD['FCXphVat'],
                'FCXphVatable' => $aCalDTTempForHD['FCXphVatable'],
                'FTXphWpCode' => $aCalDTTempForHD['FTXphWpCode'],
                'FCXphWpTax' => $aCalDTTempForHD['FCXphWpTax'],
                'FCXphGrand' => $aCalDTTempForHD['FCXphGrand'],
                'FCXphRnd' => $aCalDTTempForHD['FCXphRnd'],
                'FTXphGndText' => $aCalDTTempForHD['FTXphGndText'],
                'FTXphRmk' => $aDataDocument['otaPXFrmInfoOthRmk'],
                'FTXphStaRefund' => $aDataDocument['ohdPXStaRefund'],
                'FTXphStaDoc' => $aDataDocument['ohdPXStaDoc'],
                'FTXphStaApv' => !empty($aDataDocument['ohdPXStaApv']) ? $aDataDocument['ohdPXStaApv'] : NULL,
                // 'FTXphStaDelMQ' => !empty($aDataDocument['ohdPXStaDelMQ']) ? $aDataDocument['ohdPXStaDelMQ'] : NULL,
                // 'FTXphStaPrcStk' => !empty($aDataDocument['ohdPXStaPrcStk']) ? $$aDataDocument['ohdPXStaPrcStk'] : NULL,
                'FTXphStaPaid' => $aDataDocument['ohdPXStaPaid'],
                'FNXphStaDocAct' => $tPXStaDocAct,
                // 'FNXphStaRef' => $aDataDocument['ocmPXFrmInfoOthRef']
            );

            $aDataSpl = array(
                'FTXphDstPaid' => $aDataDocument['ocmPXFrmSplInfoDstPaid'],
                'FNXphCrTerm' => intval($aDataDocument['oetPXFrmSplInfoCrTerm']),
                'FDXphDueDate' => (!empty($aDataDocument['oetPXFrmSplInfoDueDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoDueDate'])) : NULL,
                'FDXphBillDue' => (!empty($aDataDocument['oetPXFrmSplInfoBillDue'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoBillDue'])) : NULL,
                'FTXphCtrName' => $aDataDocument['oetPXFrmSplInfoCtrName'],
                'FDXphTnfDate' => (!empty($aDataDocument['oetPXFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoTnfDate'])) : NULL,
                'FTXphRefTnfID' => $aDataDocument['oetPXFrmSplInfoRefTnfID'],
                'FTXphRefVehID' => $aDataDocument['oetPXFrmSplInfoRefVehID'],
                'FTXphRefInvNo' => $aDataDocument['oetPXFrmSplInfoRefInvNo'],
                'FTXphQtyAndTypeUnit' => $aDataDocument['oetPXFrmSplInfoQtyAndTypeUnit'],
                'FNXphShipAdd' => intval($aDataDocument['ohdPXFrmShipAdd']),
                'FNXphTaxAdd' => intval($aDataDocument['ohdPXFrmTaxAdd']),
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tPXAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => $aTableAddUpdate['tTableHD'],
                    "tDocType"    => $aTableAddUpdate['tTableStaGen'],
                    "tBchCode"    => $aDataDocument['oetPXFrmBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo'] = $tPXDocNo;
            }

            // Add Update Document HD
            $this->Expenserecord_model->FSxMPXAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // Add Update Document HD Spl
            $this->Expenserecord_model->FSxMPXAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);

            // Update Doc No Into Doc Temp
            $this->Expenserecord_model->FSxMPXAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc HD Dis Temp To HDDis
            $this->Expenserecord_model->FSaMPXMoveHdDisTempToHdDis($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->Expenserecord_model->FSaMPXMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            // Move Doc DTDisTemp To DTTemp
            $this->Expenserecord_model->FSaMPXMoveDtDisTempToDtDis($aDataWhere, $aTableAddUpdate);

            // Move Doc TCNTDocHDRefTmp To TAPTPxHDDocRef
            $this->Expenserecord_model->FSxMPXMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);

            // Add RefInt to HDDocRef
            // $tRefIntDoc = $aDataDocument['oetPXRefIntDoc'];
            // if( isset($tRefIntDoc) && !empty($tRefIntDoc) ){
            //     $dRefIntDoc = $aDataDocument['oetPXRefIntDocDate'];
            //     $aDataDocHDRef = array(
            //         'FTAgnCode'         => $aDataDocument['oetPXAgnCode'],
            //         'FTBchCode'         => $aDataDocument['oetPXFrmBchCode'],
            //         'FTXphDocNo'        => $aDataWhere['FTXphDocNo'],
            //         'FTXphRefDocNo'     => $tRefIntDoc,
            //         'FTXphRefType'      => '1',
            //         'FTXphRefKey'       => 'WHTAX',
            //         'FDXphRefDocDate'   => ( !empty($dRefIntDoc) ? date('Y-m-d H:i:s', strtotime($dRefIntDoc)) : $tPXDocDate )
            //     );
            //     $this->Expenserecord_model->FSaMPXAddHDDocRef($aDataDocHDRef);
            // }

            // Add RefExt to HDDocRef
            // $tRefExtDoc = $aDataDocument['oetPXRefExtDoc'];
            // if( isset($tRefExtDoc) && !empty($tRefExtDoc) ){
            //     $dRefExtDoc = $aDataDocument['oetPXRefExtDocDate'];
            //     $aDataDocHDRef = array(
            //         'FTAgnCode'         => $aDataDocument['oetPXAgnCode'],
            //         'FTBchCode'         => $aDataDocument['oetPXFrmBchCode'],
            //         'FTXphDocNo'        => $aDataWhere['FTXphDocNo'],
            //         'FTXphRefDocNo'     => $tRefExtDoc,
            //         'FTXphRefType'      => '2',
            //         'FTXphRefKey'       => '',
            //         'FDXphRefDocDate'   => ( !empty($dRefExtDoc) ? date('Y-m-d H:i:s', strtotime($dRefExtDoc)) : $tPXDocDate )
            //     );
            //     $this->Expenserecord_model->FSaMPXAddHDDocRef($aDataDocHDRef);
            // }


            // $aTableAddUpdate = array(
            //     'tTableHD'      => 'TAPTPxHD',
            //     'tTableDT'      => 'TAPTPxDT',
            //     'tTableDTFhn'   => 'TAPTPxDTFhn',
            // );
            // $this->Expenserecord_model->FCNxMPXMoveDTTmpToDTFhn($aDataWhere, $aTableAddUpdate);

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

    // Function         : Edit Document
    // Parameters       : Ajex Event Add Document
    // Creator          : 03/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Add Document
    // ReturnType       : Object
    public function FSoCPXEditEventDoc() {
        try {
            $aDataDocument = $this->input->post();
            $tPXDocNo       = (isset($aDataDocument['oetPXDocNo'])) ? $aDataDocument['oetPXDocNo'] : '';
            $tPXStaDocAct   = $aDataDocument['ocbPXFrmInfoOthStaDocAct'];

            $tPXDocDate     = $aDataDocument['oetPXDocDate'] . " " . $aDataDocument['oetPXDocTime'];
            $tPXVATInOrEx   = (isset($aDataDocument['ocmPXFrmSplInfoVatInOrEx'])) ? $aDataDocument['ocmPXFrmSplInfoVatInOrEx'] : '';
            $tPXSessionID   = $this->session->userdata('tSesSessionID');

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TAPTPxHD',
                'tTableHDDis'       => 'TAPTPxHDDis',
                'tTableHDSpl'       => 'TAPTPxHDSpl',
                'tTableDT'          => 'TAPTPxDT',
                'tTableDTDis'       => 'TAPTPxDTDis',
                'tTableHDRef'       => 'TAPTPxHDDocRef'
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['oetPXAgnCode'],
                'FTBchCode'         => $aDataDocument['oetPXFrmBchCode'],
                'FTXphDocNo'        => $tPXDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx'    => $tPXVATInOrEx
            );

            $tPXStaApv = $aDataDocument['ohdPXStaApv'];
            // Update หมายเหตุ + ความเคลื่อนไหว หลังจากอนุมัติเอกสาร
            if( isset($tPXStaApv) && !empty($tPXStaApv) && $tPXStaApv == '1' ){
                // Array Data HD Master
                $aDataMaster = array(
                    'FTXphRmk'          => $aDataDocument['otaPXFrmInfoOthRmk'],
                    'FNXphStaDocAct'    => $tPXStaDocAct,
                );

                $this->db->trans_begin();
                // Update Document HD
                $this->Expenserecord_model->FSxMPXAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
                if( $this->db->trans_status() === FALSE ){
                    $this->db->trans_rollback();
                    $aReturnData = array(
                        'nStaReturn'    => '900',
                        'tStaMessg'     => "Error Unsucess Update Document."
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturnData = array(
                        'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
                        'nStaReturn'    => '1',
                        'tStaMessg'     => 'Success Update Document.'
                    );
                }
            }else{
                // Event Update Default

                //--------------------------------------------------------------------
                $aResProrat = FCNaHCalculateProrate('TAPTPxHD',$tPXDocNo);
                $aCalcDTParams = [
                    'tBchCode'          => $aDataDocument['oetPXFrmBchCode'],
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tPXVATInOrEx,
                    'tDataDocNo'        => $tPXDocNo,
                    'tDataDocKey'       => 'TAPTPxHD',
                    'tDataSeqNo'        => ''
                ];
                $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                //--------------------------------------------------------------------

                // Prorate HD
                FCNaHCalculateProrate('TAPTPxHD', $tPXDocNo);

                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tPXVATInOrEx,
                    'tDataDocNo'        => $tPXDocNo,
                    'tDataDocKey'       => 'TAPTPxHD',
                    'tDataSeqNo'        => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);

                $aCalDTTempParams = [
                    'tDocNo'            => $tPXDocNo,
                    'tBchCode'          => $aDataDocument['oetPXFrmBchCode'],
                    'tSessionID'        => $tPXSessionID,
                    'tDocKey'           => 'TAPTPxHD',
                    'cSumFCXtdVat'      => $aDataDocument['ohdSumFCXtdVat'],
                    'tDataVatInOrEx'    => $tPXVATInOrEx,
                ];
                $this->Expenserecord_model->FSaMPXCalVatLastDT($aCalDTTempParams);

                $aCalDTTempForHD = $this->FSaCPXCalDTTempForHD($aCalDTTempParams);
                $aCalInHDDisTemp = $this->Expenserecord_model->FSaMPXCalInHDDisTemp($aCalDTTempParams);

                // Array Data HD Master
                $aDataMaster = array(
                    // 'FTShpCode' => $aDataDocument['oetPXFrmShpCode'],
                    'FNXphDocType' => 14,
                    'FDXphDocDate' => (!empty($tPXDocDate)) ? $tPXDocDate : NULL,
                    'FTXphCshOrCrd' => $aDataDocument['ocmPXFrmSplInfoPaymentType'],
                    'FTXphVATInOrEx' => $tPXVATInOrEx,
                    'FTDptCode' => $aDataDocument['ohdPXDptCode'],
                    // 'FTWahCode' => $aDataDocument['oetPXFrmWahCode'],
                    'FTUsrCode' => $aDataDocument['ohdPXUsrCode'],
                    'FTSplCode' => $aDataDocument['oetPXFrmSplCode'],
                    // 'FTXphRefExt' => $aDataDocument['oetPXRefExtDoc'],
                    // 'FDXphRefExtDate' => (!empty($aDataDocument['oetPXRefExtDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXRefExtDocDate'])) : NULL,
                    // 'FTXphRefInt' => $aDataDocument['oetPXRefIntDoc'],
                    // 'FDXphRefIntDate' => (!empty($aDataDocument['oetPXRefIntDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXRefIntDocDate'])) : NULL,
                    'FNXphDocPrint' => $aDataDocument['ocmPXFrmInfoOthDocPrint'],
                    'FTRteCode' => $aDataDocument['ohdPXCmpRteCode'],
                    'FCXphRteFac' => $aDataDocument['ohdPXRteFac'],
                    'FCXphTotal' => $aCalDTTempForHD['FCXphTotal'],
                    'FCXphTotalNV' => $aCalDTTempForHD['FCXphTotalNV'],
                    'FCXphTotalNoDis' => $aCalDTTempForHD['FCXphTotalNoDis'],
                    'FCXphTotalB4DisChgV' => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                    'FCXphTotalB4DisChgNV' => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                    'FTXphDisChgTxt' => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                    'FCXphDis' => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                    'FCXphChg' => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                    'FCXphTotalAfDisChgV' => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                    'FCXphTotalAfDisChgNV' => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                    'FCXphAmtV' => $aCalDTTempForHD['FCXphAmtV'],
                    'FCXphAmtNV' => $aCalDTTempForHD['FCXphAmtNV'],
                    'FCXphVat' => $aCalDTTempForHD['FCXphVat'],
                    'FCXphVatable' => $aCalDTTempForHD['FCXphVatable'],
                    'FTXphWpCode' => $aCalDTTempForHD['FTXphWpCode'],
                    'FCXphWpTax' => $aCalDTTempForHD['FCXphWpTax'],
                    'FCXphGrand' => $aCalDTTempForHD['FCXphGrand'],
                    'FCXphRnd' => $aCalDTTempForHD['FCXphRnd'],
                    'FTXphGndText' => $aCalDTTempForHD['FTXphGndText'],
                    'FTXphRmk' => $aDataDocument['otaPXFrmInfoOthRmk'],
                    'FTXphStaRefund' => $aDataDocument['ohdPXStaRefund'],
                    // 'FTXphStaDoc' => !empty($aDataDocument['ohdPXStaDoc']) ? $aDataDocument['ohdPXStaDoc'] : NULL,
                    // 'FTXphStaApv' => !empty($aDataDocument['ohdPXStaApv']) ? $aDataDocument['ohdPXStaApv'] : NULL,
                    // 'FTXphStaDelMQ' => !empty($aDataDocument['ohdPXStaDelMQ']) ? $aDataDocument['ohdPXStaDelMQ'] : NULL,
                    // 'FTXphStaPrcStk' => !empty($aDataDocument['ohdPXStaPrcStk']) ? $$aDataDocument['ohdPXStaPrcStk'] : NULL,
                    'FTXphStaPaid' => $aDataDocument['ohdPXStaPaid'],
                    'FNXphStaDocAct' => $tPXStaDocAct,
                    // 'FNXphStaRef' => $aDataDocument['ocmPXFrmInfoOthRef']
                );

                // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);
                $aDataSpl = array(
                    'FTXphDstPaid' => $aDataDocument['ocmPXFrmSplInfoDstPaid'],
                    'FNXphCrTerm' => intval($aDataDocument['oetPXFrmSplInfoCrTerm']),
                    'FDXphDueDate' => (!empty($aDataDocument['oetPXFrmSplInfoDueDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoDueDate'])) : NULL,
                    'FDXphBillDue' => (!empty($aDataDocument['oetPXFrmSplInfoBillDue'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoBillDue'])) : NULL,
                    'FTXphCtrName' => $aDataDocument['oetPXFrmSplInfoCtrName'],
                    'FDXphTnfDate' => (!empty($aDataDocument['oetPXFrmSplInfoTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetPXFrmSplInfoTnfDate'])) : NULL,
                    'FTXphRefTnfID' => $aDataDocument['oetPXFrmSplInfoRefTnfID'],
                    'FTXphRefVehID' => $aDataDocument['oetPXFrmSplInfoRefVehID'],
                    'FTXphRefInvNo' => $aDataDocument['oetPXFrmSplInfoRefInvNo'],
                    'FTXphQtyAndTypeUnit' => $aDataDocument['oetPXFrmSplInfoQtyAndTypeUnit'],
                    'FNXphShipAdd' => intval($aDataDocument['ohdPXFrmShipAdd']),
                    'FNXphTaxAdd' => intval($aDataDocument['ohdPXFrmTaxAdd']),
                );

                $this->db->trans_begin();

                // Check Auto GenCode Document
                // if ($tPXAutoGenCode == '1') {
                //     $aStoreParam = array(
                //         "tTblName"    => $aTableAddUpdate['tTableHD'],
                //         "tDocType"    => $aTableAddUpdate['tTableStaGen'],
                //         "tBchCode"    => $aDataDocument['oetPXFrmBchCode'],
                //         "tShpCode"    => "",
                //         "tPosCode"    => "",
                //         "dDocDate"    => date("Y-m-d")
                //     );
                //     $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                //     $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
                // } else {
                //     $aDataWhere['FTXphDocNo'] = $tPXDocNo;
                // }

                // Add Update Document HD
                $this->Expenserecord_model->FSxMPXAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

                // Add Update Document HD Spl
                $this->Expenserecord_model->FSxMPXAddUpdateHDSpl($aDataSpl, $aDataWhere, $aTableAddUpdate);

                // Update Doc No Into Doc Temp
                $this->Expenserecord_model->FSxMPXAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

                // Move Doc HD Dis Temp To HDDis
                $this->Expenserecord_model->FSaMPXMoveHdDisTempToHdDis($aDataWhere, $aTableAddUpdate);

                // Move Doc DTTemp To DT
                $this->Expenserecord_model->FSaMPXMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

                // Move Doc DTDisTemp To DTTemp
                $this->Expenserecord_model->FSaMPXMoveDtDisTempToDtDis($aDataWhere, $aTableAddUpdate);

                // Move Doc TCNTDocHDRefTmp To TAPTPxHDDocRef
                $this->Expenserecord_model->FSxMPXMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);

                // $aTableAddUpdate = array(
                //     'tTableHD'      => 'TAPTPxHD',
                //     'tTableDT'      => 'TAPTPxDT',
                //     'tTableDTFhn'   => 'TAPTPxDTFhn',
                // );
                // $this->Expenserecord_model->FCNxMPXMoveDTTmpToDTFhn($aDataWhere, $aTableAddUpdate);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $aReturnData = array(
                        'nStaReturn'    => '900',
                        'tStaMessg'     => "Error Unsucess Add Document."
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
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : Cancel Document
    // Parameters       : Ajex Event Add Document
    // Creator          : 09/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Cancel Document
    // ReturnType       : Object
    public function FSvCPXCancelDocument() {
        try {
            $tPXDocNo = $this->input->post('ptPXDocNo');
            $aDataUpdate = array(
                'tDocNo' => $tPXDocNo,
            );
            $aReturnData = $this->Expenserecord_model->FSaMPXCancelDocument($aDataUpdate);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : Approve Document
    // Parameters       : Ajex Event Add Document
    // Creator          : 09/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Cancel Document
    // ReturnType       : Object
    public function FSvCPXApproveDocument() {
        $tPXDocNo = $this->input->post('ptPXDocNo');
        $tPXBchCode = $this->input->post('ptPXBchCode');
        $tPXStaApv = $this->input->post('ptPXStaApv');
        $tPXSplPaymentType = $this->input->post('ptPXSplPaymentType');

        $aDataUpdate = array(
            'tDocNo' => $tPXDocNo,
            'tApvCode' => $this->session->userdata('tSesUsername')
        );

        $aStaApv = $this->Expenserecord_model->FSaMPXApproveDocument($aDataUpdate);
        echo json_encode($aStaApv);

        // $tUsrBchCode = FCNtGetBchInComp();
        // $tPXDocType = intval($tPXSplPaymentType == 1 ? 4 : 5);
        // $this->db->trans_begin();
        // try {
        //     $aMQParams = [
        //         "queueName" => "PURCHASEINV",
        //         "params" => [
        //             "ptBchCode" => $tPXBchCode,
        //             "ptDocNo" => $tPXDocNo,
        //             "ptDocType" => $tPXDocType,
        //             "ptUser" => $this->session->userdata('tSesUsername'),
        //         ]
        //     ];
        //     FCNxCallRabbitMQ($aMQParams);
        // } catch (ErrorException $err) {
        //     $this->db->trans_rollback();
        //     $aReturn = array(
        //         'nStaEvent' => '900',
        //         'tStaMessg' => language('common/main/main', 'tApproveFail')
        //     );
        //     echo json_encode($aReturn);
        //     return;
        // }
    }

    // Function         : Function Searh And Add Pdt In Tabel Temp
    // Parameters       : Ajex Event Add Document
    // Creator          : 30/07/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Searh And Add Pdt In Tabel Temp
    // ReturnType       : Object
    public function FSoCPXSearchAndAddPdtIntoTbl() {
        try {
            $tPXBchCode = $this->input->post('ptPXBchCode');
            $tPXDocNo = $this->input->post('ptPXDocNo');
            $tPXDataSearchAndAdd = $this->input->post('ptPXDataSearchAndAdd');
            $tPXStaReAddPdt = $this->input->post('ptPXStaReAddPdt');
            $tPXSessionID = $this->session->userdata('tSesSessionID');
            $nLangEdit = $this->session->userdata("tLangID");
            // เช็คข้อมูลในฐานข้อมูล
            $aDataChkINDB = array(
                'FTBchCode' => $tPXBchCode,
                'FTXthDocNo' => $tPXDocNo,
                'FTXthDocKey' => 'TAPTPxHD',
                'FTSessionID' => $tPXSessionID,
                'tPXDataSearchAndAdd' => trim($tPXDataSearchAndAdd),
                'tPXStaReAddPdt' => $tPXStaReAddPdt,
                'nLangEdit' => $nLangEdit
            );

            $aCountDataChkInDTTemp = $this->Expenserecord_model->FSaCPXCountPdtBarInTablePdtBar($aDataChkINDB);
            $nCountDataChkInDTTemp = isset($aCountDataChkInDTTemp) && !empty($aCountDataChkInDTTemp) ? FCNnHSizeOf($aCountDataChkInDTTemp) : 0;
            if ($nCountDataChkInDTTemp == 1) {
                // สินค้าหรือ BarCode ทีกรอกมี 1 ตัวให้เอาลง หรือ เช็ค สถานะ Appove ได้เลย
            } else if ($nCountDataChkInDTTemp > 1) {
                // มี Bar Code มากกว่า 1 ให้แสดง Modal
            } else {
                // ไม่พบข้อมูลบาร์โค๊ดกับรหัสสินค้าในระบบ
                $aReturnData = array(
                    'nStaEvent' => 800,
                    'tStaMessg' => language('document/expenserecord/expenserecord', 'tPXNotFoundPdtCodeAndBarcode')
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

    // Function         : Clear Data In DocTemp
    // Parameters       : Ajex Event Add Document
    // Creator          : 13/08/2019 wasin(Yoshi)
    // LastUpdate       : -
    // Return           : Object Status Clear Data In Document Temp
    // ReturnType       : Object
    public function FSoCPXClearDataInDocTemp() {
        try {
            $this->db->trans_begin();

            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => $this->input->post('ptPXDocNo'),
                'FTXthDocKey' => 'TAPTPxHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->Expenserecord_model->FSxMPXClearDataInDocTemp($aWhereClearTemp);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaReturn' => 900,
                    'tStaMessg' => "Error Not Delete Document Temp."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaReturn' => 1,
                    'tStaMessg' => 'Success Delete Document Temp.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => 500,
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function         : Print Document
    // Parameters       : Ajax Event Add Document
    // Creator          : 27/08/2019 Piya
    // LastUpdate       : -
    // Return           : Object Status Print Document
    // ReturnType       : Object
    public function FSoCPXPrintDoc() {}

    // Function         : คำนวณส่วนลดท้ายบิลใหม่อีกครั้ง กรณีมีการเพิ่มสินค้า , แก้ไขจำนวน , แก้ไขราคา , ลบสินค้า , ลดรายการ , ลดท้ายบิล
    // Parameters       : -
    // Creator          : 24/02/2021 Supawat
    // LastUpdate       : -
    // Return           : -
    // ReturnType       : -
    public function FSxCalculateHDDisAgain($ptDocumentNumber , $ptBCHCode){
        $aPackDataCalCulate = array(
            'tDocNo'        => $ptDocumentNumber,
            'tBchCode'      => $ptBCHCode
        );
        FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
    }

    // Function         : -
    // Parameters       : -
    // Creator          : 24/02/2021 Supawat
    // LastUpdate       : -
    // Return           : -
    // ReturnType       : -
    public function FSaPXCallEndOfBillOnChaheVat(){

        $tPXVATInOrEx   = $this->input->post('ptPXVATInOrEx');
        $tPXDocNo       = $this->input->post('ptPXDocNo');
        $tPXFrmBchCode  = $this->input->post('tSelectBCH');


        //--------------------------------------------------------------------
        $aResProrat     = FCNaHCalculateProrate('TAPTPxHD',$tPXDocNo);
        $aCalcDTParams = [
            'tBchCode'          => $tPXFrmBchCode,
            'tDataDocEvnCall'   => '',
            'tDataVatInOrEx'    => $tPXVATInOrEx,
            'tDataDocNo'        => $tPXDocNo,
            'tDataDocKey'       => 'TAPTPxHD',
            'tDataSeqNo'        => ''
        ];
        $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
        //-----------------------------------------------------------------------------

        // Prorate HD
        FCNaHCalculateProrate('TAPTPxHD', $tPXDocNo);

        $aCalcDTParams = [
            'tDataDocEvnCall'   => '1',
            'tDataVatInOrEx'    => $tPXVATInOrEx,
            'tDataDocNo'        => $tPXDocNo,
            'tDataDocKey'       => 'TAPTPxHD',
            'tDataSeqNo'        => ''
        ];
        $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);

        $aCalDTTempParams = [
            'tDocNo' => $tPXDocNo,
            'tBchCode' => $tPXFrmBchCode,
            'tSessionID' => $this->session->userdata('tSesSessionID'),
            'tDocKey' => 'TAPTPxHD',
            'tDataVatInOrEx'    => $tPXVATInOrEx,
        ];
        $this->Expenserecord_model->FSaMPXCalVatLastDT($aCalDTTempParams);

        // Call Footer Document
        $aEndOfBillParams = array(
            'tSplVatType'   => $tPXVATInOrEx,
            'tDocNo'        => $tPXDocNo,
            'tDocKey'       => 'TAPTPxHD',
            'nLngID'        => FCNaHGetLangEdit(),
            'tSesSessionID' => $this->session->userdata('tSesSessionID'),
            'tBchCode'      => $this->input->post('tSelectBCH')
        );

        //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล supawat 03-04-2020
        $aPXEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
        $aPXEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
        $aPXEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aPXEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

        if(!empty($aPXEndOfBill['aEndOfBillVat'])){
            $aReturnData = array(
                'aPXEndOfBill' => $aPXEndOfBill,
                'nStaEvent' => '1',
                'tStaMessg' => "Fucntion Success Return View."
            );
        }else{
            $aReturnData  = array(
                'nStaEvent' => '99',
                'tStaMessg' => "Fucntion Error Return View."
            );
        }
        echo json_encode($aReturnData);

    }

    //ทุกครั้งที่เปลี่ยน SPL จะส่งผล ให้เกิดการคำนวณ VAT ใหม่
    public function FSoCPXChangeSPLAffectNewVAT(){
        $tPXDocNo       = $this->input->post('tPXDocNo');
        $tBCHCode       = $this->input->post('tBCHCode');
        $tVatCode       = $this->input->post('tVatCode');
        $tVatRate       = $this->input->post('tVatRate');

        $aItem = [
            'tPXDocNo'      => $tPXDocNo,
            'tBCHCode'      => $tBCHCode,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
            'tDocKey'       => 'TAPTPxHD',
            'FTVatCode'     => $tVatCode,
            'FCXtdVatRate'  => $tVatRate
        ];
        $this->Expenserecord_model->FSaMPXChangeSPLAffectNewVAT($aItem);
    }

    // Create By : Napat(Jame) 05/04/2021
    // หลังจากเลือก Ref IN PO Move รายการสินค้าจาก PO ไปยัง Tmp PX
    public function FSoCPXMovePODTToDocTmp() {
        try {
            $tPODocNo           = $this->input->post('tPODocNo');
            $tPXUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tPXDocNo           = $this->input->post('tPXDocNo');
            $tPXVATInOrEx       = $this->input->post('tPXVATInOrEx');
            $tPXBchCode         = $this->input->post('tBCHCode');
            $tPXOptionAddPdt    = $this->input->post('tPXOptionAddPdt');
            $nVatRate           = $this->input->post('nVatRate');
            $nVatCode           = $this->input->post('nVatCode');

            $aDataWhere = array(
                'FTBchCode'     => $tPXBchCode,
                'FTXthDocNo'    => $tPXDocNo,
                'FTXthDocKey'   => 'TAPTPxHD'
            );

            $this->db->trans_begin();

            $aDataPdtParams = array(
                'tPODocNo'          => $tPODocNo,
                'tDocNo'            => $tPXDocNo,
                'tBchCode'          => $tPXBchCode,
                'nLngID'            => $this->session->userdata("tLangID"),
                'tSessionID'        => $this->session->userdata('tSesSessionID'),
                'tDocKey'           => 'TAPTPxHD',
                'tPXOptionAddPdt'   => $tPXOptionAddPdt,
                'nVatRate'          => $nVatRate,
                'nVatCode'          => $nVatCode
            );
            
            // นำรายการสินค้า จากใบ PO DT เข้า DT Temp
            $this->Expenserecord_model->FSaMPXMovePODTToDocTmp($aDataPdtParams);
            

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();
                // Calcurate Document DT Temp Array Parameter
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tPXVATInOrEx,
                    'tDataDocNo'        => $tPXDocNo,
                    'tDataDocKey'       => 'TAPTPxHD',
                    'tDataSeqNo'        => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain($tPXDocNo,$tPXBchCode);
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Add Product Into Document DT Temp.'
                    );
                } else {
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Calcurate Document DT Temp Please Contact Admin.'
                    );
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }


    //เพิ่มสินค้าลงตาราง Tmp
    public function FSoCPXEventAddPdtIntoDTFhnTemp(){
        try {
            $tTWOUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tPXDocNo            = $this->input->post('tPXDocNo');
            $tPXBchCode         = $this->input->post('tPXWBCH');
            $tPXPdtDataFhn       = $this->input->post('tPXPdtDataFhn');
            $aPXPdtData         = JSON_decode($tPXPdtDataFhn);
            $nPXVATInOrEx       = $this->input->post('nPXVATInOrEx');
            $tTypeInsPDT         = $this->input->post('tPXType');
            $nEvent         = $this->input->post('nEvent');
            $tPXOptionAddPdt    = $this->input->post('tPXOptionAddPdt');
           
            $aDataWhere = array(
                'tBchCode'  => $tPXBchCode,
                'tDocNo'    => $tPXDocNo,
                'tDocKey'   => 'TAPTPxHD',
            );
            $this->db->trans_begin();
            if($aPXPdtData->tType=='confirm'){
                // $aDataWhere['tPdtCode'] = $aPXPdtData->aResult[0]->tPDTCode;
                // FCNxClearDTFhnTmp($aDataWhere);
                // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
                $nPdtParentQty = 0;
                for ($nI = 0; $nI < FCNnHSizeOf($aPXPdtData->aResult); $nI++) {

                    $aItem          = $aPXPdtData->aResult[$nI];
                    $tPXPdtCode    = $aItem->tPDTCode;
                    $tPXtRefCode   = $aItem->tRefCode;
                    $tPXtBarCode   = $aItem->tBarCode;
                    $tPXtPunCode   = $aItem->tPunCode;


                    $nPXnQty       = $aItem->nQty;
                    $nPdtParentQty  = $nPdtParentQty + $nPXnQty;
                    
                    $aDataWhere['tPdtCode'] = $tPXPdtCode;
                    $aDataWhere['tBarCode'] = $tPXtBarCode;
                    $aDataWhere['tPunCode'] = $tPXtPunCode;
                    if($nEvent==1){
                        $nPXSeqNo = FCNnGetMaxSeqDTFhnTmp($aDataWhere);
                    }else{
                        $nDTSeq   = $aItem->nDTSeq;
                        $nPXSeqNo =  $nDTSeq;
                    }

                    $aDataPdtParams = array(
                        'tDocNo'            => $tPXDocNo,
                        'tBchCode'          => $tPXBchCode,
                        'tPdtCode'          => $tPXPdtCode,
                        'tRefCode'          => $tPXtRefCode,
                        'nMaxSeqNo'         => $nPXSeqNo,
                        'nQty'              => $nPXnQty,
                        'tOptionAddPdt'     => $tPXOptionAddPdt,
                        'nLngID'            => $this->session->userdata("tLangID"),
                        'tSessionID'        => $this->session->userdata('tSesSessionID'),
                        'tDocKey'           => 'TAPTPxHD',
                    );
                    // นำรายการสินค้าเข้า DT Temp
                    if($nEvent==1){
                    $nStaInsPdtToTmp    = FCNaInsertPDTFhnToTemp($aDataPdtParams);
                    }else{
                    $nStaInsPdtToTmp    = FCNaUpdatePDTFhnToTemp($aDataPdtParams);
                    }

                }

                $aDataUpdateQtyParent = array(
                    'tDocNo'        => $tPXDocNo,
                    'nXtdSeq'       => $nPXSeqNo,
                    'tSessionID'    => $this->session->userdata('tSesSessionID'),
                    'tDocKey'       => 'TAPTPxHD',
                    'tValue'        => $nPdtParentQty
                );
                FCNaUpdateInlineDTTmp($aDataUpdateQtyParent);
            }else{
                $tPXPdtCode = $aPXPdtData->aResult->tPDTCode;
                $aDataPdtParams = array(
                    'tDocNo'            => $tPXDocNo,
                    'tBchCode'          => $tPXBchCode,
                    'tPdtCode'          => $tPXPdtCode,
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TAPTPxHD',
                );
                $nStaInsPdtToTmp    = FCNxDeletePDTInTmp($aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();
                // Calcurate Document DT Temp Array Parameter
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $nPXVATInOrEx,
                    'tDataDocNo'        => $tPXDocNo,
                    'tDataDocKey'       => 'TAPTPxHD',
                    'tDataSeqNo'        => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
    
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Add Product Into Document DT Temp.'
                    );
                } else {
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Calcurate Document DT Temp Please Contact Admin.'
                    );
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Create By: Napat(Jame) 02/11/2021
    public function FSoCPXPageHDDocRef(){
        try {
            $tPXDocNo = $this->input->post('ptPXDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TAPTPxHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXthDocNo'        => $tPXDocNo,
                'FTXthDocKey'       => 'TAPTPxHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aDataDocHDRef = $this->Expenserecord_model->FSaMPXGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tPXViewPageHDRef = $this->load->view('document/expenserecord/wExpenseRecordDocRef', $aDataConfig, true);
            $aReturnData = array(
                'tPXViewPageHDRef'  => $tPXViewPageHDRef,
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

    // Create By: Napat(Jame) 02/11/2021
    public function FSoCPXEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptPXDocNo'),
                'FTXthDocKey'       => 'TAPTPxHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tPXRefDocNoOld'    => $this->input->post('ptRefDocNoOld'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate')
            ];
            $aReturnData = $this->Expenserecord_model->FSaMPXAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Create By: Napat(Jame) 03/11/2021
    public function FSoCPXEventDelHDDocRef(){
        try {
            $aData = [
                'FTXthDocNo'        => $this->input->post('ptPXDocNo'),
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthDocKey'       => 'TAPTPxHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->Expenserecord_model->FSaMPXDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

}