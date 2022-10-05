<?php

use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

defined('BASEPATH') or exit('No direct script access allowed');

class cSaleOrder extends MX_Controller {

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('document/saleorder/mSaleOrder');
        $this->load->model('document/saleorder/mSaleOrderDisChgModal');
        parent::__construct();
    }

    public function index($nSOBrowseType, $tSOBrowseOption) {

        $aParams = array(
            'tDocNo'        => $this->input->get('tDocNo'),
            'tCheckJump'    => $this->input->get('ptTypeJump'),
            'tCheckType'    => $this->input->get('tType'),
            'tCheckBackTo'  => $this->input->get('tBackTo'),
        );

        $aDataConfigView = array(
            'nSOBrowseType'     => $nSOBrowseType,
            'tSOBrowseOption'   => $tSOBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('dcmSO/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('dcmSO/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave(),
            'aParams'   => $aParams,
        );
        $this->load->view('document/saleorder/wSaleOrder', $aDataConfigView);
    }

    // ฟังก์ชั่นหลัก
    public function FSvCSOFormSearchList() {
        $this->load->view('document/saleorder/wSaleOrderFormSearchList');
    }

    // ตารางข้อมูล
    public function FSoCSODataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc('dcmSO/0/0');

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
            $aDataList = $this->mSaleOrder->FSaMSOGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );

            $tSOViewDataTableList = $this->load->view('document/saleorder/wSaleOrderDataTable', $aConfigView, true);
            $aReturnData = array(
                'tSOViewDataTableList' => $tSOViewDataTableList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // ตารางข้อมูล
    public function FSoCSODataTableGenPO() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc('dcmSO/0/0');

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
            $aDataList = $this->mSaleOrder->FSaMSOGetDataTableListGenPO($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );

            $tSOViewDataTableList = $this->load->view('document/saleorder/wSaleOrderGenPODataTable', $aConfigView, true);
            $aReturnData = array(
                'tSOViewDataTableList' => $tSOViewDataTableList,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // ลบข้อมูล
    public function FSoCSODeleteEventDoc() {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->mSaleOrder->FSnMSODelDocument($aDataMaster);
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
        echo jSOn_encode($aDataStaReturn);
    }

    // หน้าจอเพิ่ม
    public function FSoCSOAddPage() {
        try {

            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => '',
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];

            $tUserBchCode = $this->session->userdata('tSesUsrBchCom');
            // echo $tUserBchCode;die();
            if(!empty($tUserBchCode)){
                $aDataBch = $this->mSaleOrder->FSaMSOGetDetailUserBranch($tUserBchCode);
                $tSOPplCode = $aDataBch['item']['FTPplCode'];
            }else{
                $tSOPplCode = '';
            }
     
            $this->mSaleOrder->FSaMCENDeletePDTInTmp($aWhereClearTemp);
            $this->mSaleOrder->FSxMSOClearDataInDocTemp($aWhereClearTemp);

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
                'tDataDocKey' => 'TARTSoHD',
                'tDataSeqNo' => ''
            );
            FCNbHCallCalcDocDTTemp($aWhereHelperCalcDTTemp);

            $aDataWhere = array(
                'FNLngID' => $nLangEdit
            );

            $tASOReq = "";
            $tMethodReq = "GET";
            $aCompData = $this->mCompany->FSaMCMPList($tASOReq, $tMethodReq, $aDataWhere);

            if (isset($aCompData) && $aCompData['rtCode'] == '1') {
                $tCmpRteCode    = $aCompData['raItems']['rtCmpRteCode'];
                $tVatCode       = $aCompData['raItems']['rtVatCodeUse'];
                $tCmpRetInOrEx  = $aCompData['raItems']['rtCmpRetInOrEx'];

                //ประเภทภาษี
                $aVatRate = FCNoHCallVatlist($tVatCode);
                if (isset($aVatRate) && !empty($aVatRate)) {
                    $cVatRate = $aVatRate['FCVatRate'][0];
                } else {
                    $cVatRate = "";
                }

                //อัตราเเลกเปลี่ยน
                $aDataRate  = array('FTRteCode' => $tCmpRteCode,'FNLngID' => $nLangEdit );
                $aResultRte = $this->mRate->FSaMRTESearchByID($aDataRate);
                if (isset($aResultRte) && $aResultRte['rtCode']) {
                    $cXthRteFac = $aResultRte['raItems']['rcRteRate'];
                } else {
                    $cXthRteFac = "";
                }
            } else {
                $tCmpRteCode    = "";
                $tVatCode       = "";
                $cVatRate       = "";
                $cXthRteFac     = "";
                $tCmpRetInOrEx  ="1";
            }

            // Get Department Code
            $tUsrLogin  = $this->session->userdata('tSesUsername');
            $tDptCode   = FCNnDOCGetDepartmentByUser($tUsrLogin);

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            $aDataShp = array('FNLngID' => $nLangEdit,'tUsrLogin' => $tUsrLogin );
            $aDataUserGroup = $this->mSaleOrder->FSaMSOGetShpCodeForUsrLogin($aDataShp);
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

            // ดึงข้อมูลที่อยู่คลัง Defult ในตาราง TSysConfig
            $aConfigSys = [
                'FTSysCode' => 'tPS_Warehouse',
                'FTSysSeq'  => 3,
                'FNLngID'   => $nLangEdit
            ];
            $aConfigSysWareHouse = $this->mSaleOrder->FSaMSOGetDefOptionConfigWah($aConfigSys);

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'nOptDocSave'       => $nOptDocSave,
                'nOptScanSku'       => $nOptScanSku,
                'tCmpRteCode'       => $tCmpRteCode,
                'tVatCode'          => $tVatCode,
                'cVatRate'          => $cVatRate,
                'cXthRteFac'        => $cXthRteFac,
                'tDptCode'          => $tDptCode,
                'tBchCode'          => $tBchCode,
                'tBchName'          => $tBchName,
                'tMerCode'          => $tMerCode,
                'tMerName'          => $tMerName,
                'tShopType'         => $tShopType,
                'tShopCode'         => $tShopCode,
                'tShopName'         => $tShopName,
                'tWahCode'          => $tWahCode,
                'tWahName'          => $tWahName,
                'tBchCompCode'      => FCNtGetBchInComp(),
                'tBchCompName'      => FCNtGetBchNameInComp(),
                'aConfigSysWareHouse' => $aConfigSysWareHouse,
                'aDataDocHD'        => array('rtCode' => '800'),
                'aDataDocHDSpl'     => array('rtCode' => '800'),
                'tCmpRetInOrEx'     => $tCmpRetInOrEx,
                'tSOPplCode'        => $tSOPplCode,
                'nAlwFindWahPCK'    => 0 //ไม่อนุญาตให้สร้างใบจัด
            );
            
            $tSOViewPageAdd = $this->load->view('document/saleorder/wSaleOrderAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tSOViewPageAdd'    => $tSOViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // หน้าจอแก้ไข
    public function FSoCSOEditPage() {
        try {
            $tSODocNo = $this->input->post('ptSODocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTXthDocNo'    => $tSODocNo,
                'FTXthDocKey'   => 'TARTSoHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->mSaleOrder->FSxMSOClearDataInDocTemp($aWhereClearTemp);

            $tUserBchCode = $this->session->userdata('tSesUsrBchCom');
            if(!empty($tUserBchCode)){
                $aDataBch = $this->mSaleOrder->FSaMSOGetDetailUserBranch($tUserBchCode);
                $tSOPplCode = $aDataBch['item']['FTPplCode'];
            }else{
                $tSOPplCode = '';
            }

            // Get Autentication Route
            $aAlwEvent          = FCNaHCheckAlwFunc('dcmSO/0/0');
            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave        = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku        = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");
            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            $tUsrLogin          = $this->session->userdata('tSesUsername');

            //หาว่า คลัง อนุญาต สร้างใบจัดไหม
            $aFindWahAlwPCK     = $this->mSaleOrder->FSaMSOFindWahouseToPCK($aWhereClearTemp);
            if($aFindWahAlwPCK['rtCode'] == 1){
                if($aFindWahAlwPCK['raItems']['FTWahStaAlwPLFrmSO'] == '' || $aFindWahAlwPCK['raItems']['FTWahStaAlwPLFrmSO'] == null ){
                    $nAlwFindWahPCK = 0; //ไม่อนุญาต
                }else{
                    $nAlwFindWahPCK = 1; //อนุญาต
                }
            }else{
                $nAlwFindWahPCK = 0; //ไม่อนุญาต
            }

            $aDataShp           = array(
                'FNLngID'   => $nLangEdit,
                'tUsrLogin' => $tUsrLogin
            );
            $aDataUserGroup = $this->mSaleOrder->FSaMSOGetShpCodeForUsrLogin($aDataShp);
            if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
                $tUsrBchCode    = "";
                $tUsrBchName    = "";
                $tUsrMerCode    = "";
                $tUsrMerName    = "";
                $tUsrShopType   = "";
                $tUsrShopCode   = "";
                $tUsrShopName   = "";
            } else {
                $tUsrBchCode    = $aDataUserGroup["FTBchCode"];
                $tUsrBchName    = $aDataUserGroup["FTBchName"];
                $tUsrMerCode    = $aDataUserGroup["FTMerCode"];
                $tUsrMerName    = $aDataUserGroup["FTMerName"];
                $tUsrShopType   = $aDataUserGroup["FTShpType"];
                $tUsrShopCode   = $aDataUserGroup["FTShpCode"];
                $tUsrShopName   = $aDataUserGroup["FTShpName"];
            }

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo'    => $tSODocNo,
                'FTXthDocKey'   => 'TARTSoHD',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 10000,
                'nPage'         => 1,
            );

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD = $this->mSaleOrder->FSaMSOGetDataDocHD($aDataWhere);

            // Get Data Document HD
            $aDataDocHDCstAddr = $this->mSaleOrder->FSaMSOGetDataDocHDCstAddr($aDataWhere);

            // [Move] Data HD DIS To HD DIS Temp
            $this->mSaleOrder->FSxMSOMoveHDDisToTemp($aDataWhere);

            // [Move] Data DT TO DTTemp
            $this->mSaleOrder->FSxMSOMoveDTToDTTemp($aDataWhere);

            // [Move] Data DTDIS TO DTDISTemp
            $this->mSaleOrder->FSxMSOMoveDTDisToDTDisTemp($aDataWhere);
            
            // [Move] Data HDDocRef TO HDRefTemp
            $this->mSaleOrder->FSxMSOMoveHDRefToHDRefTemp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();
                $tUserBchCode = $aDataDocHD['raItems']['FTBchCode'];
                if(!empty($tUserBchCode)){
                    $aDataBch   = $this->mSaleOrder->FSaMSOGetDetailUserBranch($tUserBchCode);
                    $tSOPplCode = $aDataBch['item']['FTPplCode'];
                }else{
                    $tSOPplCode = '';
                }

                $aDataWhere = array(
                    'FNLngID' => $nLangEdit
                );
    
                $aCompData = $this->mCompany->FSaMCMPList("", "GET", $aDataWhere);

                if (isset($aCompData) && $aCompData['rtCode'] == '1') {
                    $tCmpRteCode    = $aCompData['raItems']['rtCmpRteCode'];
                    $tVatCode       = $aCompData['raItems']['rtVatCodeUse'];
                    $tCmpRetInOrEx  = $aCompData['raItems']['rtCmpRetInOrEx'];

                    //ประเภทภาษี
                    $aVatRate       = FCNoHCallVatlist($tVatCode);
                    if (isset($aVatRate) && !empty($aVatRate)) {
                        $cVatRate = $aVatRate['FCVatRate'][0];
                    } else {
                        $cVatRate = "";
                    }

                    //อัตราเเลกเปลี่ยน
                    $aDataRate  = array('FTRteCode' => $tCmpRteCode,'FNLngID' => $nLangEdit);
                    $aResultRte = $this->mRate->FSaMRTESearchByID($aDataRate);
                    if (isset($aResultRte) && $aResultRte['rtCode']) {
                        $cXthRteFac = $aResultRte['raItems']['rcRteRate'];
                    } else {
                        $cXthRteFac = "";
                    }
                } else {
                    $tCmpRteCode    = "";
                    $tVatCode       = "";
                    $cVatRate       = "";
                    $cXthRteFac     = "";
                    $tCmpRetInOrEx  = "1";
                }
                
                $nLangID        = $this->session->userdata("tLangEdit");
                $tCstCode       = $this->input->post('ptCstCode');
                $aCSTAddress    = FCNtGetAddressCustmer('TARTSoHDCst', $tCstCode, $nLangID,'FNXshAddrShip');
                $aDataConfigViewAdd = array(
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'nOptDocSave'       => $nOptDocSave,
                    'nOptScanSku'       => $nOptScanSku,
                    'cXthRteFac'        => $cXthRteFac,
                    'tUserBchCode'      => $tUsrBchCode,
                    'tUserBchName'      => $tUsrBchName,
                    'tUsrMerCode'       => $tUsrMerCode,
                    'tUsrMerName'       => $tUsrMerName,
                    'tUsrShopType'      => $tUsrShopType,
                    'tUsrShopCode'      => $tUsrShopCode,
                    'tUsrShopName'      => $tUsrShopName,
                    'tBchCompCode'      => FCNtGetBchInComp(),
                    'tBchCompName'      => FCNtGetBchNameInComp(),
                    'aDataDocHD'        => $aDataDocHD,
                    'aDataDocHDCstAddr' => $aDataDocHDCstAddr,
                    'aAlwEvent'         => $aAlwEvent,
                    'tSOPplCode'        => $tSOPplCode,
                    'tCmpRetInOrEx'     => $tCmpRetInOrEx,
                    'cVatRate'          => $cVatRate,
                    'aCSTAddress'       => $aCSTAddress,
                    'nAlwFindWahPCK'    => $nAlwFindWahPCK
                );

                $tSOViewPageEdit    = $this->load->view('document/saleorder/wSaleOrderAdd', $aDataConfigViewAdd, true);
                $aReturnData        = array(
                    'tSOViewPageEdit'   => $tSOViewPageEdit,
                    'tCshOrCrd'         => $aDataDocHD['raItems']['FTXshCshOrCrd'],
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
        echo jSOn_encode($aReturnData);
    }
    
    // หน้าแก้ไข (Monitor - Statdose)
    public function FSoCSOEditPageMonitor() {
        try {
            $tSODocNo = $this->input->post('ptSODocNo');

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->mSaleOrder->FSxMSOClearDataInDocTemp($aWhereClearTemp);

            // Get Autentication Route
            $aAlwEvent = FCNaHCheckAlwFunc('dcmSO/0/0');
            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            $tUsrLogin = $this->session->userdata('tSesUsername');
            $aDataShp = array(
                'FNLngID' => $nLangEdit,
                'tUsrLogin' => $tUsrLogin
            );

            $aDataUserGroup = $this->mSaleOrder->FSaMSOGetShpCodeForUsrLogin($aDataShp);
            if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
                $tUsrBchCode = "";
                $tUsrBchName = "";
                $tUsrMerCode = "";
                $tUsrMerName = "";
                $tUsrShopType = "";
                $tUsrShopCode = "";
                $tUsrShopName = "";
            } else {
                $tUsrBchCode = $aDataUserGroup["FTBchCode"];
                $tUsrBchName = $aDataUserGroup["FTBchName"];
                $tUsrMerCode = $aDataUserGroup["FTMerCode"];
                $tUsrMerName = $aDataUserGroup["FTMerName"];
                $tUsrShopType = $aDataUserGroup["FTShpType"];
                $tUsrShopCode = $aDataUserGroup["FTShpCode"];
                $tUsrShopName = $aDataUserGroup["FTShpName"];
            }

            // Data Table Document
            $aTableDocument = array(
                'tTableHD' => 'TARTSoHD',
                'tTableHDCst' => 'TARTSoHDCst',
                'tTableHDDis' => 'TARTSoHDDis',
                'tTableDT' => 'TARTSoDT',
                'tTableDTDis' => 'TARTSoDTDis'
            );

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTSoHD',
                'FNLngID' => $nLangEdit,
                'nRow' => 10000,
                'nPage' => 1,
            );

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD = $this->mSaleOrder->FSaMSOGetDataDocHD($aDataWhere);

            $nNextSeq = $aDataDocHD['raItems']['LastSeq']+1; //หาลำดับต่อไป

            $aDataSetStrPrc = array(
                'FTDatRefCode' => $tSODocNo,
                'tBchCode' => $aDataDocHD['raItems']['FTBchCode'],
                'FNDatApvSeq' => $nNextSeq,
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
                'FDLastUpdOn' => date('Y-m-d H:i:s')
            );
          $nCheckNumBook =  $this->mSaleOrder->FSnMSOCheckStrPrcLastUpdate($aDataSetStrPrc);
         
           if($nCheckNumBook>0){//ตรวจสอบว่าในขณะนี้มีผู้จองเอกสารใช้อยู่หรือไม่ 0 = มีผู้จองใช้อยู่ , >0 = เอกสารว่างในขณะนี้

            $this->mSaleOrder->FSaMSOUpdateStrPrcLastUpdate($aDataSetStrPrc);

                    // echo '<pre>';
                    // print_r($aDataWhere);
                    // echo '</pre>';
                    // die();
            // Move Data HD DIS To HD DIS Temp
            $this->mSaleOrder->FSxMSOMoveHDDisToTemp($aDataWhere);

            // Move Data DT TO DTTemp
            $this->mSaleOrder->FSxMSOMoveDTToDTTemp($aDataWhere);

            // Move Data DTDIS TO DTDISTemp
            $this->mSaleOrder->FSxMSOMoveDTDisToDTDisTemp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();
                // Prorate HD
                FCNaHCalculateProrate('TARTSoHD', $tSODocNo);
                $tSOVATInOrEx = ($aDataDocHD['rtCode'] == '1') ? $aDataDocHD['raItems']['FTXshVATInOrEx'] : 1;
                $aCalcDTTempParams = array(
                    'tDataDocEvnCall' => '1',
                    'tDataVatInOrEx' => $tSOVATInOrEx,
                    'tDataDocNo' => $tSODocNo,
                    'tDataDocKey' => 'TARTSoHD',
                    'tDataSeqNo' => ""
                );
                $tStaCalDocDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTTempParams);

                $aDataCalTnx = array(
                    'tDocNo' => $tSODocNo,
                    'tApvCode'=> "",
                    'tTableDocHD' => 'TARTSoHD',
                    'tBchCode' =>$aDataDocHD['raItems']['FTBchCode']
    
                );
               $aDataSOTnx = FNaDOHNCheckSeqAprve($aDataCalTnx);//หาประวัติการบันทึกอนุมัติก่อน
                

              $nSecondTimeCountDonw = $this->mSaleOrder->FSnMSOGetTimeCountDown($aDataSetStrPrc);
                
                $aDataConfigViewAdd = array(
                    'nOptDecimalShow' => $nOptDecimalShow,
                    'nOptDocSave' => $nOptDocSave,
                    'nOptScanSku' => $nOptScanSku,
                    'tUserBchCode' => $tUsrBchCode,
                    'tUserBchName' => $tUsrBchName,
                    'tUsrMerCode' => $tUsrMerCode,
                    'tUsrMerName' => $tUsrMerName,
                    'tUsrShopType' => $tUsrShopType,
                    'tUsrShopCode' => $tUsrShopCode,
                    'tUsrShopName' => $tUsrShopName,
                    'tBchCompCode' => FCNtGetBchInComp(),
                    'tBchCompName' => FCNtGetBchNameInComp(),
                    'aDataDocHD' => $aDataDocHD,
                    'aAlwEvent' => $aAlwEvent,
                    'aDataSOTnx' => $aDataSOTnx,
                    'nSecondTimeCountDonw' => ($nSecondTimeCountDonw*1000)
                );
                $tSOViewPageEdit = $this->load->view('document/saleorder/wSaleOrderAddMonitor', $aDataConfigViewAdd, true);
                $aReturnData = array(
                    'tSOViewPageEdit' => $tSOViewPageEdit,
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            }
        }else{
            $aReturnData = array(
                'nStaEvent' => '3',
                'tStaMessg' => 'This document is in use.'
            );
        }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // โหลดหน้าจอ DT
    public function FSoCSOPdtAdvTblLoadData() {
        try {
            $bStaSession    =   $this->session->userdata('bSesLogIn');
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData = array(
                    'checksession' => 'expire'
                );
                echo jSOn_encode($aReturnData);
                exit;
            }

            $tSODocNo           = $this->input->post('ptSODocNo');
            $tSOStaApv          = $this->input->post('ptSOStaApv');
            $tSOStaDoc          = $this->input->post('ptSOStaDoc');
            $tSOVATInOrEx       = $this->input->post('ptSOVATInOrEx');
            $nSOPageCurrent     = $this->input->post('pnSOPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tSOPdtCode         = $this->input->post('ptSOPdtCode');
            $tSOPunCode         = $this->input->post('ptSOPunCode');

            //Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tSODocNo,
                'FTXthDocKey'           => 'TARTSoHD',
                'nPage'                 => $nSOPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );

            // FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp     = $this->mSaleOrder->FSaMSOGetDocDTTempListPage($aDataWhere);
            $aDataDocDTTempSum  = $this->mSaleOrder->FSaMSOSumDocDTTemp($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tSOStaApv'         => $tSOStaApv,
                'tSOStaDoc'         => $tSOStaDoc,
                'tSOPdtCode'        => $tSOPdtCode,
                'tSOPunCode'        => $tSOPunCode,
                'nPage'             => $nSOPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
                'aDataDocDTTempSum' => $aDataDocDTTempSum,
            );

            $tSOPdtAdvTableHtml = $this->load->view('document/saleorder/wSaleOrderPdtAdvTableData', $aDataView, true);

            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'   => $tSOVATInOrEx,
                'tDocNo'        => $tSODocNo,
                'tDocKey'       => 'TARTSoHD',
                'nLngID'        => FCNaHGetLangEdit(),
                'tSesSessionID' => $this->session->userdata('tSesSessionID'),
                'tBchCode'      => $this->input->post('tSelectBCH')
            );

            //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล supawat 03-04-2020       
            $aSOEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aSOEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aSOEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aSOEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

            $aPackDataCalCulate = array(
                'tDocNo'        => $tSODocNo,
                'tBchCode'      => $this->input->post('tSelectBCH'),
                'nB4Dis'        => $aSOEndOfBill['aEndOfBillCal']['cSumFCXtdNet'],
                'tSplVatType'   => $tSOVATInOrEx
            );
            // $tCalculateAgain = FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
            // if($tCalculateAgain == 'CHANGE'){
                // $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                // if($aStaCalcDTTemp === TRUE){
                //     FCNaHCalculateProrate('TARTSoHD',$aPackDataCalCulate['tDocNo']);
                    // FCNbHCallCalcDocDTTemp($aCalcDTParams);
                // }
            //     $aSOEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            //     $aSOEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
            //     $aSOEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aSOEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
            // }

            $aReturnData = array(
                'tSOPdtAdvTableHtml' => $tSOPdtAdvTableHtml,
                'aSOEndOfBill' => $aSOEndOfBill,
                'nStaEvent' => '1',
                'tStaMessg' => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // โหลดหน้าจอ DT (Monitor - Statdose)
    public function FSoCSOPdtAdvTblLoadDataMonitor() {
        try {
            $tSODocNo = $this->input->post('ptSODocNo');
            $tSOStaApv = $this->input->post('ptSOStaApv');
            $tSOStaDoc = $this->input->post('ptSOStaDoc');
            $tSOVATInOrEx = $this->input->post('ptSOVATInOrEx');
            $nSOPageCurrent = $this->input->post('pnSOPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            // Edit in line
            $tSOPdtCode = $this->input->post('ptSOPdtCode');
            $tSOPunCode = $this->input->post('ptSOPunCode');
            $nSOLastSeq = $this->input->post('nSOLastSeq');
            

            //Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();

            // Call Advance Table
            $tTableGetColumeShow = 'TARTSoDT';
            $aColumnShow = FCNaDCLGetColumnShow($tTableGetColumeShow);
            
            $aDataWhere = array(
                'tSearchPdtAdvTable' => $tSearchPdtAdvTable,
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTSoHD',
                'nPage' => $nSOPageCurrent,
                'nRow' => 10,
                'FTSessionID' => $this->session->userdata('tSesSessionID'),
            );

            // Calcurate Document DT Temp Array Parameter
            $aCalcDTParams = [
                'tDataDocEvnCall' => '1',
                'tDataVatInOrEx' => $tSOVATInOrEx,
                'tDataDocNo' => $tSODocNo,
                'tDataDocKey' => 'TARTSoDT',
                'tDataSeqNo' => ''
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp = $this->mSaleOrder->FSaMSOGetDocDTTempListPage($aDataWhere);
            $aDataDocDTTempSum = $this->mSaleOrder->FSaMSOSumDocDTTemp($aDataWhere);
      
            $aDataView = array(
                'nOptDecimalShow' => $nOptDecimalShow,
                'tSOStaApv' => $tSOStaApv,
                'tSOStaDoc' => $tSOStaDoc,
                'tSOPdtCode' => $tSOPdtCode,
                'tSOPunCode' => $tSOPunCode,
                'nPage' => $nSOPageCurrent,
                'aColumnShow' => $aColumnShow,
                'aDataDocDTTemp' => $aDataDocDTTemp,
                'aDataDocDTTempSum' => $aDataDocDTTempSum,
                'nSOLastSeq' => $nSOLastSeq
            );
            if($nSOLastSeq!=4){
              $tSOPdtAdvTableHtml = $this->load->view('document/saleorder/wSaleOrderPdtAdvTableDataMonitor', $aDataView, true);
            }else{
              $tSOPdtAdvTableHtml = $this->load->view('document/saleorder/wSaleOrderPdtAdvTableDataMonitorIMG', $aDataView, true);
            }
            
            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType' => $tSOVATInOrEx,
                'tDocNo' => $tSODocNo,
                'tDocKey' => 'TARTSoHD',
                'nLngID' => FCNaHGetLangEdit(),
                'tSesSessionID' => $this->session->userdata('tSesSessionID'),
                'tBchCode' => $this->input->post('tSelectBCH')
            );

            $aSOEndOfBill['aEndOfBillVat'] = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aSOEndOfBill['aEndOfBillCal'] = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aSOEndOfBill['tTextBath'] = FCNtNumberToTextBaht($aSOEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
            $aReturnData = array(
                'tSOPdtAdvTableHtml' => $tSOPdtAdvTableHtml,
                'aSOEndOfBill' => $aSOEndOfBill,
                'nStaEvent' => '1',
                'tStaMessg' => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // โชว์ Column
    public function FSoCSOAdvTblShowColList() {
        try {
            $tTableShowColums = 'TARTSoDT';
            $aAvailableColumn = FCNaDCLAvailableColumn($tTableShowColums);

            // print_r($aAvailableColumn);
            // die();
            $aDataViewAdvTbl = array(
                'aAvailableColumn' => $aAvailableColumn
            );
            $tViewTableShowCollist = $this->load->view('document/saleorder/advancetable/wSaleOrderTableShowColList', $aDataViewAdvTbl, true);
            $aReturnData = array(
                'tViewTableShowCollist' => $tViewTableShowCollist,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // โชว์ Column
    public function FSoCSOAdvTalShowColSave() {
        try {
            $this->db->trans_begin();

            $nSOStaSetDef = $this->input->post('pnSOStaSetDef');
            $aSOColShowSet = $this->input->post('paSOColShowSet');
            $aSOColShowAllList = $this->input->post('paSOColShowAllList');
            $aSOColumnLabelName = $this->input->post('paSOColumnLabelName');
            // Table Set Show Colums
            $tTableShowColums = "TARTSoDT";
            FCNaDCLSetShowCol($tTableShowColums, '', '');
            if ($nSOStaSetDef == '1') {
                FCNaDCLSetDefShowCol($tTableShowColums);
            } else {
                for ($i = 0; $i < count($aSOColShowSet); $i++) {
                    FCNaDCLSetShowCol($tTableShowColums, 1, $aSOColShowSet[$i]);
                }
            }
            // Reset Seq Advannce Table
            FCNaDCLUpdateSeq($tTableShowColums, '', '', '');
            $q = 1;
            for ($n = 0; $n < count($aSOColShowAllList); $n++) {
                FCNaDCLUpdateSeq($tTableShowColums, $aSOColShowAllList[$n], $q, $aSOColumnLabelName[$n]);
                $q++;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Eror Not Save Colums'
                );
            } else {
                $this->db->trans_commit();
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
        echo jSOn_encode($aReturnData);
    }

    // Add สินค้า ลง Document DT Temp
    public function FSoCSOAddPdtIntoDocDTTemp() {
        try {
            $tSOUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tSODocNo           = $this->input->post('tSODocNo');
            $tSOVATInOrEx       = $this->input->post('tSOVATInOrEx');
            $tSOBchCode         = $this->input->post('tSelectBCH');
            $tSOOptionAddPdt    = $this->input->post('tSOOptionAddPdt');
            $tSOPdtData         = $this->input->post('tSOPdtData');
            $aSOPdtData         = jSOn_decode($tSOPdtData);
            $tSOPplCodeBch      = $this->input->post('tSOPplCodeBch');//กลุ่มราคาตามสาขา
            $tSOPplCodeCst      = $this->input->post('tSOPplCodeCst');//กลุ่มราคาตามลูกค้า

            $this->db->trans_begin();

            // $nSOMaxSeqNo    = $this->mSaleOrder->FSaMSOGetMaxSeqDocDTTemp($aDataWhere);
            // $nSOMaxSeqNo   += 1;

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < count($aSOPdtData); $nI++) {
                $tSOPdtCode = $aSOPdtData[$nI]->pnPdtCode;
                $tSOBarCode = $aSOPdtData[$nI]->ptBarCode;
                $tSOPunCode = $aSOPdtData[$nI]->ptPunCode;
                // $aDataGetprice = array(
                //     'tSOPplCodeCst' => $tSOPplCodeCst,
                //     'tSOPplCodeBch' => $tSOPplCodeCst,
                //     'tSOPdtCode'    => $tSOPdtCode,
                //     'tSOBarCode'    => $tSOBarCode,
                //     'tSOPunCode'    => $tSOPunCode
                // );
                // $cSOPrice = $this->mSaleOrder->FScMSOGetPricePdt4CstOrPdtBYPplCode($aDataGetprice);

                $cSOPrice        = $aSOPdtData[$nI]->packData->PriceRet;
                
                $aDataPdtParams = array(
                    'tDocNo'            => $tSODocNo,
                    'tBchCode'          => $tSOBchCode,
                    'tPdtCode'          => $tSOPdtCode,
                    'tBarCode'          => $tSOBarCode,
                    'tPunCode'          => $tSOPunCode,
                    'cPrice'            => str_replace(",","",$cSOPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdSOLangEdit"),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TARTSoHD',
                    'tSOOptionAddPdt'   => $tSOOptionAddPdt,
                    'tSOUsrCode'        => $this->input->post('ohdSOUsrCode'),
                );

                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->mSaleOrder->FSaMSOGetDataPdt($aDataPdtParams);

                // นำรายการสินค้าเข้า DT Temp
                $this->mSaleOrder->FSaMSOInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();

                $tStaCalcuRate = TRUE;
                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain($tSODocNo,$tSOBchCode); 
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
        echo jSOn_encode($aReturnData);
    }

    // Edit Inline สินค้า ลง Document DT Temp
    public function FSoCSOEditPdtIntoDocDTTemp() {
        try {
            $tSOBchCode         = $this->input->post('tSOBchCode');
            $tSODocNo           = $this->input->post('tSODocNo');
            $nSOSeqNo           = $this->input->post('nSOSeqNo');
            $nStaDelDis         = $this->input->post('nStaDelDis');

            $aDataWhere = array(
                'tSOBchCode'    => $tSOBchCode,
                'tSODocNo'      => $tSODocNo,
                'nSOSeqNo'      => $nSOSeqNo,
                'tSOSessionID'  => $this->input->post('ohdSesSessionID'),
                'tDocKey'       => 'TARTSoHD',
            );
            $aDataUpdateDT = array(
                'FCXtdQty'          => $this->input->post('nQty'),
                'FCXtdSetPrice'     => $this->input->post('cPrice'),
                'FCXtdNet'          => $this->input->post('cNet')
            );

            $this->db->trans_begin();
            $this->mSaleOrder->FSaMSOUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
            if($nStaDelDis == 1){
                // ยืนยันการลบ DTDis ส่วนลดรายการนี้
                $this->mSaleOrderDisChgModal->FSaMSODeleteDTDisTemp($aDataWhere);
                $this->mSaleOrderDisChgModal->FSaMSOClearDisChgTxtDTTemp($aDataWhere);
            }

            //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง CR:Supawat
            /*****************************************************************/
            /**/    $this->FSxCalculateHDDisAgain($tSODocNo,$tSOBchCode);  /**/
            /*****************************************************************/

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
                // // Prorate HD
                // FCNaHCalculateProrate('TARTSoHD', $tSODocNo);

                // $aCalcDTTempParams = array(
                //     'tDataDocEvnCall' => '1',
                //     'tDataVatInOrEx' => $tSOVATInOrEx,
                //     'tDataDocNo' => $tSODocNo,
                //     'tDataDocKey' => 'TARTSoHD',
                //     'tDataSeqNo' => $nSOSeqNo
                // );
                // $tStaCalDocDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTTempParams);
                // if ($tStaCalDocDTTemp === TRUE) {
                //     $aReturnData = array(
                //         'nStaEvent' => '1',
                //         'tStaMessg' => "Update And Calcurate Process Document DT Temp Success."
                //     );
                // } else {
                //     $aReturnData = array(
                //         'nStaEvent' => '500',
                //         'tStaMessg' => "Error Cannot Calcurate Document DT Temp."
                //     );
                // }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // Remove Product In Documeny Temp
    public function FSvCSORemovePdtInDTTmp() {
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

            $aStaDelPdtDocTemp = $this->mSaleOrder->FSnMSODelPdtInDTTmp($aDataWhere);

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
                /**/    $tSODocNo   = $this->input->post('tDocNo');            /**/ 
                /**/    $tSOBchCode = $this->input->post('tBchCode');          /**/ 
                /**/    $this->FSxCalculateHDDisAgain($tSODocNo,$tSOBchCode);  /**/
                /*****************************************************************/

                // Prorate HD
                // FCNaHCalculateProrate('TARTSoHD', $aDataWhere['tDocNo']);
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TARTSoHD',
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
        echo jSOn_encode($aReturnData);
    }

    // Remove Product In Documeny Temp Multiple
    public function FSvCSORemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tBchCode' => $this->input->post('ptSOBchCode'),
                'tDocNo' => $this->input->post('ptSODocNo'),
                'tVatInOrEx' => $this->input->post('ptSOVatInOrEx'),
                'aDataPdtCode' => $this->input->post('paDataPdtCode'),
                // 'aDataPunCode' => $this->input->post('paDataPunCode'),
                'aDataSeqNo' => $this->input->post('paDataSeqNo')
            );

            $aStaDelPdtDocTemp = $this->mSaleOrder->FSnMSODelMultiPdtInDTTmp($aDataWhere);

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
                /**/    $tSODocNo   = $this->input->post('tDocNo');            /**/ 
                /**/    $tSOBchCode = $this->input->post('tBchCode');          /**/ 
                /**/    $this->FSxCalculateHDDisAgain($tSODocNo,$tSOBchCode);  /**/
                /*****************************************************************/
                
                // Prorate HD
                FCNaHCalculateProrate('TARTSoHD', $aDataWhere['tDocNo']);
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TARTSoHD',
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
        echo jSOn_encode($aReturnData);
    }

    // Check Product Have In Temp For Document DT
    public function FSoCSOChkHavePdtForDocDTTemp() {
        try {
            $tSODocNo = $this->input->post("ptSODocNo");
            $tSOSessionID = $this->input->post('tSOSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $tSOSessionID
            );
            $nCountPdtInDocDTTemp = $this->mSaleOrder->FSnMSOChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn' => '800',
                    'tStaMessg' => language('document/saleorder/saleorder', 'tSOPleaseSeletedPDTIntoTable')
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // คำนวณค่าจาก DT Temp ให้ HD
    private function FSaCSOCalDTTempForHD($paParams) {
        $aCalDTTemp = $this->mSaleOrder->FSaMSOCalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems = $aCalDTTemp[0];
            // คำนวณหา ยอดปัดเศษ ให้ HD(FCXphRnd)
            $pCalRoundParams = [
                'FCXshAmtV' => $aCalDTTempItems['FCXshAmtV'],
                'FCXshAmtNV' => $aCalDTTempItems['FCXshAmtNV']
            ];

            // print_r($pCalRoundParams);
            // die();
            $aRound = $this->FSaCSOCalRound($pCalRoundParams);
            // คำนวณหา ยอดรวม ให้ HD(FCXphGrand)
            $nRound = $aRound['nRound'];
            $cGrand = $aRound['cAfRound'];

            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXshRnd'] = $nRound;
            $aCalDTTempItems['FCXshGrand'] = $cGrand;
            $aCalDTTempItems['FTXshGndText'] = $tGndText;
            return $aCalDTTempItems;
        }
    }

    // หาค่าปัดเศษ HD(FCXphRnd)
    private function FSaCSOCalRound($paParams) {
        $tOptionRound = '1';  // ปัดขึ้น
        $cAmtV = $paParams['FCXshAmtV'];
        $cAmtNV = $paParams['FCXshAmtNV'];
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
            'nRound' => floatval($nRound / 100),
            'cAfRound' => $cAfRound
        ];
    }

    // เพิ่มเอกสาร
    public function FSoCSOAddEventDoc() {
        try {
            $aDataDocument  = $this->input->post();
            $tSOAutoGenCode = (isset($aDataDocument['ocbSOStaAutoGenCode'])) ? 1 : 0;
            $tSODocNo       = (isset($aDataDocument['oetSODocNo'])) ? $aDataDocument['oetSODocNo'] : '';
            $tSODocDate     = $aDataDocument['oetSODocDate'] . " " . $aDataDocument['oetSODocTime'];
            $tSOStaDocAct   = (isset($aDataDocument['ocbSOFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tSOSessionID   = $this->input->post('ohdSesSessionID');
            $tSOVATInOrEx   = 1;
            
            $aCalcDTParams = [
                'tBchCode'          => $aDataDocument['oetSOFrmBchCode'],
                'tDataDocEvnCall'   => '',
                'tDataVatInOrEx'    => $tSOVATInOrEx,
                'tDataDocNo'        => $tSODocNo,
                'tDataDocKey'       => 'TARTSoHD',
                'tDataSeqNo'        => ''
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            // Prorate HD
            FCNaHCalculateProrate('TARTSoHD', $tSODocNo);
                       
            $aCalDTTempParams = [
                'tDocNo'            => $tSODocNo,
                'tBchCode'          => $aDataDocument['oetSOFrmBchCode'],
                'tSessionID'        => $tSOSessionID,
                'tDocKey'           => 'TARTSoHD',
                'cSumFCXtdVat'      => $aDataDocument['ohdSumFCXtdVat'],
                'tDataVatInOrEx'    => $tSOVATInOrEx
            ];
            $this->mSaleOrder->FSaMSOCalVatLastDT($aCalDTTempParams);

            $aCalDTTempForHD = $this->FSaCSOCalDTTempForHD($aCalDTTempParams);
            $aCalInHDDisTemp = $this->mSaleOrder->FSaMSOCalInHDDisTemp($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TARTSoHD',
                'tTableHDDis'       => 'TARTSoHDDis',
                'tTableHDCst'       => 'TARTSoHDCst',
                'tTableDT'          => 'TARTSoDT',
                'tTableDTDis'       => 'TARTSoDTDis',
                'tTableStaGen'      => 1,
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['oetSOFrmBchCode'],
                'FTXshDocNo'        => $tSODocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdSOUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdSOUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tSOVATInOrEx
            );

            // Check Auto GenCode Document
            if ($tSOAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TARTSoHD',                           
                    "tDocType"    => '1',                                          
                    "tBchCode"    => $aDataDocument['oetSOFrmBchCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d")       
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXshDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXshDocNo']   = $tSODocNo;
            }

            //array Data Customer 
            $aDataCustomer = array(
                'FTXshCardID'           => $aDataDocument['oetSOFrmCstCtzID'],
                'FTXshCstName'          => $aDataDocument['oetSOFrmCustomerName'],
                'FTXshCstTel'           => $aDataDocument['oetSOFrmCstTel'],
                'FTXshStaAlwPosCalSo'   => empty($aDataDocument['ocbSOStaAlwPosCalSo']) ? 2 : $aDataDocument['ocbSOStaAlwPosCalSo'],
                'FNXshCrTerm'           => $aDataDocument['oetSOCreditTerm'],
                'FDXshDueDate'          => (!empty($aDataDocument['oetSOEffectiveDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetSOEffectiveDate'])) : NULL,
                'FTCarCode'             => !empty($aDataDocument['oetSOFrmCarCode']) ? $aDataDocument['oetSOFrmCarCode'] : NULL,
                'FNXshAddrShip'         => $aDataDocument['ohdSOFrmShipAdd'],
                'FTXshCstRef'           => $aDataDocument['oetSOHDXshCstRef']
            );
           
            // Array Data HD Master
            $aDataMaster = array(
                'FTCstCode'             => $aDataDocument['oetSOFrmCstHNNumber'],
                'FTShfCode'             => '',
                'FTSpnCode'             => $aDataDocument['ohdSOUsrCode'],
                'FNXshDocType'          => 1,
                'FDXshDocDate'          => (!empty($tSODocDate)) ? $tSODocDate : NULL,
                'FTXshCshOrCrd'         => $aDataDocument['ocmSOFrmSplInfoPaymentType'],
                'FTXshVATInOrEx'        => $tSOVATInOrEx,
                'FTDptCode'             => $aDataDocument['ohdSODptCode'],
                'FTWahCode'             => $aDataDocument['oetSOFrmWahCode'],
                'FTUsrCode'             => $aDataDocument['ohdSOUsrCode'],
                'FNXshDocPrint'         => $aDataDocument['ocmSOFrmInfoOthDocPrint'],
                'FTRteCode'             => $aDataDocument['ohdSOCmpRteCode'],
                'FCXshRteFac'           => $aDataDocument['ohdSORteFac'],
                'FCXshTotal'            => $aCalDTTempForHD['FCXshTotal'],
                'FCXshTotalNV'          => $aCalDTTempForHD['FCXshTotalNV'],
                'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXshTotalNoDis'],
                'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXshTotalB4DisChgV'],
                'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXshTotalB4DisChgNV'],
                'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXshDisChgTxt']) ? $aCalInHDDisTemp['FTXshDisChgTxt'] : '',
                'FCXshDis'              => isset($aCalInHDDisTemp['FCXshDis']) ? $aCalInHDDisTemp['FCXshDis'] : NULL,
                'FCXshChg'              => isset($aCalInHDDisTemp['FCXshChg']) ? $aCalInHDDisTemp['FCXshChg'] : NULL,
                'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXshTotalAfDisChgV'],
                'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXshTotalAfDisChgNV'],
                'FCXshAmtV'             => $aCalDTTempForHD['FCXshAmtV'],
                'FCXshAmtNV'            => $aCalDTTempForHD['FCXshAmtNV'],
                'FCXshVat'              => $aCalDTTempForHD['FCXshVat'],
                'FCXshVatable'          => $aCalDTTempForHD['FCXshVatable'],
                'FTXshWpCode'           => $aCalDTTempForHD['FTXshWpCode'],
                'FCXshWpTax'            => $aCalDTTempForHD['FCXshWpTax'],
                'FCXshGrand'            => $aCalDTTempForHD['FCXshGrand'],
                'FCXshRnd'              => $aCalDTTempForHD['FCXshRnd'],
                'FTXshGndText'          => $aCalDTTempForHD['FTXshGndText'],
                'FTXshRmk'              => $aDataDocument['otaSOFrmInfoOthRmk'],
                'FTXshStaRefund'        => $aDataDocument['ohdSOStaRefund'],
                'FTXshStaDoc'           => $aDataDocument['ohdSOStaDoc'],
                'FTXshStaApv'           => !empty($aDataDocument['ohdSOStaApv']) ? $aDataDocument['ohdSOStaApv'] : NULL,
                'FTXshStaPaid'          => $aDataDocument['ohdSOStaPaid'],
                'FNXshStaDocAct'        => $tSOStaDocAct,
                'FNXshStaRef'           => $aDataDocument['ocmSOFrmInfoOthRef']
            );

            $this->db->trans_begin();

            // [Add] Document HD
            $this->mSaleOrder->FSxMSOAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Add] Document HD Cst
            $this->mSaleOrder->FSxMSOAddUpdateHDCst($aDataCustomer,$aDataWhere, $aTableAddUpdate);

            // [Update] Doc No Into Doc Temp
            $this->mSaleOrder->FSxMSOAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Move] Doc HDDisTemp To HDDis
            $this->mSaleOrder->FSaMSOMoveHdDisTempToHdDis($aDataWhere, $aTableAddUpdate);

            // [Move] Doc DTTemp To DT
            $this->mSaleOrder->FSaMSOMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            // [Move] Doc DTDisTemp To DTTemp
            $this->mSaleOrder->FSaMSOMoveDtDisTempToDtDis($aDataWhere, $aTableAddUpdate);

            // [Move] Doc HDRefTmp To TARTSoHDDocRef
            $this->mSaleOrder->FSxMSOMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            
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
                    'tCstCode'      => $aDataMaster['FTCstCode'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'        => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // แก้ไขเอกสาร
    public function FSoCSOEditEventDoc() {
        try {
            $aDataDocument  = $this->input->post();
            $tSOAutoGenCode = (isset($aDataDocument['ocbSOStaAutoGenCode'])) ? 1 : 0;
            $tSODocNo       = (isset($aDataDocument['oetSODocNo'])) ? $aDataDocument['oetSODocNo'] : '';
            $tSODocDate     = $aDataDocument['oetSODocDate'] . " " . $aDataDocument['oetSODocTime'];
            $tSOStaDocAct   = (isset($aDataDocument['ocbSOFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tSOSessionID   = $this->input->post('ohdSesSessionID');
            $nLangEdit      = $this->input->post("ohdSOLangEdit");
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $aCompData      = $this->mCompany->FSaMCMPList("", "GET", $aDataWhereComp);
            $tSOVATInOrEx   = $aCompData['raItems']['rtCmpRetInOrEx'];//ภาษีขายปลีก ดูตามบริษัท


            if($aDataDocument['ohdSOStaApv'] == 1){ //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว

                // Array Data update
                $aDataUpdate = array(
                    'FTBchCode'             => $aDataDocument['oetSOFrmBchCode'],
                    'FTXshDocNo'            => $aDataDocument['oetSODocNo'],
                    'FTXshRmk'              => $aDataDocument['otaSOFrmInfoOthRmk'],
                );

                 // Array Data HD Master
                 $aDataMaster = array(
                    'FTXshCshOrCrd'         => $aDataDocument['ocmSOFrmSplInfoPaymentType'],
                    'FTCstCode'             => $aDataDocument['oetSOFrmCstHNNumber']
                );

                $this->db->trans_begin();

                // [Update] update หมายเหตุ
                $this->mSaleOrder->FSaMSOUpdateRmk($aDataUpdate);

            } else { //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ

                $aCalcDTParams = [
                    'tBchCode'          => $aDataDocument['oetSOFrmBchCode'],
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => $tSOVATInOrEx,
                    'tDataDocNo'        => $tSODocNo,
                    'tDataDocKey'       => 'TARTSoHD',
                    'tDataSeqNo'        => ''
                ];
                FCNbHCallCalcDocDTTemp($aCalcDTParams);
                
                FCNaHCalculateProrate('TARTSoHD', $tSODocNo);

                $aCalDTTempParams = [
                    'tDocNo'            => $tSODocNo,
                    'tBchCode'          => $aDataDocument['oetSOFrmBchCode'],
                    'tSessionID'        => $tSOSessionID,
                    'tDocKey'           => 'TARTSoHD',
                    'cSumFCXtdVat'      => $aDataDocument['ohdSumFCXtdVat'],
                    'tDataVatInOrEx'    => $tSOVATInOrEx
                ];
                $this->mSaleOrder->FSaMSOCalVatLastDT($aCalDTTempParams);
                
                $aCalDTTempForHD = $this->FSaCSOCalDTTempForHD($aCalDTTempParams);
                $aCalInHDDisTemp = $this->mSaleOrder->FSaMSOCalInHDDisTemp($aCalDTTempParams);

                // Array Data Table Document
                $aTableAddUpdate = array(
                    'tTableHD'      => 'TARTSoHD',
                    'tTableHDDis'   => 'TARTSoHDDis',
                    'tTableHDCst'   => 'TARTSoHDCst',
                    'tTableDT'      => 'TARTSoDT',
                    'tTableDTDis'   => 'TARTSoDTDis',
                    'tTableStaGen'  => 1,
                );

                //array Data Customer 
                $aDataCustomer = array(
                    'FTXshCardID'           => $aDataDocument['oetSOFrmCstCtzID'],
                    'FTXshCstName'          => $aDataDocument['oetSOFrmCustomerName'],
                    'FTXshCstTel'           => $aDataDocument['oetSOFrmCstTel'],
                    'FTXshStaAlwPosCalSo'   => empty($aDataDocument['ocbSOStaAlwPosCalSo']) ? 2 : $aDataDocument['ocbSOStaAlwPosCalSo'],
                    'FNXshCrTerm'           => $aDataDocument['oetSOCreditTerm'],
                    'FDXshDueDate'          => (!empty($aDataDocument['oetSOEffectiveDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetSOEffectiveDate'])) : NULL,
                    'FTCarCode'             => !empty($aDataDocument['oetSOFrmCarCode']) ? $aDataDocument['oetSOFrmCarCode'] : NULL,
                    'FNXshAddrShip'         => $aDataDocument['ohdSOFrmShipAdd'],
                    'FTXshCstRef'           => $aDataDocument['oetSOHDXshCstRef']
                );

                // Array Data Where Insert
                $aDataWhere = array(
                    'FTBchCode'             => $aDataDocument['oetSOFrmBchCode'],
                    'FTXshDocNo'            => $tSODocNo,
                    'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                    'FDCreateOn'            => date('Y-m-d H:i:s'),
                    'FTCreateBy'            => $this->input->post('ohdSOUsrCode'),
                    'FTLastUpdBy'           => $this->input->post('ohdSOUsrCode'),
                    'FTSessionID'           => $this->input->post('ohdSesSessionID'),
                    'FTXthVATInOrEx'        => $tSOVATInOrEx
                );

                // Array Data HD Master
                $aDataMaster = array(
                    'FNXshDocType'          => 1,
                    'FDXshDocDate'          => (!empty($tSODocDate)) ? $tSODocDate : NULL,
                    'FTXshCshOrCrd'         => $aDataDocument['ocmSOFrmSplInfoPaymentType'],
                    'FTXshVATInOrEx'        => $tSOVATInOrEx,
                    'FTDptCode'             => $aDataDocument['ohdSODptCode'],
                    'FTWahCode'             => $aDataDocument['oetSOFrmWahCode'],
                    'FTUsrCode'             => $aDataDocument['ohdSOUsrCode'],
                    'FTCstCode'             => $aDataDocument['oetSOFrmCstHNNumber'],
                    'FTShfCode'             => '',
                    'FTSpnCode'             => $aDataDocument['ohdSOUsrCode'],
                    'FNXshDocPrint'         => $aDataDocument['ocmSOFrmInfoOthDocPrint'],
                    'FTRteCode'             => $aDataDocument['ohdSOCmpRteCode'],
                    'FCXshRteFac'           => $aDataDocument['ohdSORteFac'],
                    'FCXshTotal'            => $aCalDTTempForHD['FCXshTotal'],
                    'FCXshTotalNV'          => $aCalDTTempForHD['FCXshTotalNV'],
                    'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXshTotalNoDis'],
                    'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXshTotalB4DisChgV'],
                    'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXshTotalB4DisChgNV'],
                    'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXshDisChgTxt']) ? $aCalInHDDisTemp['FTXshDisChgTxt'] : '',
                    'FCXshDis'              => isset($aCalInHDDisTemp['FCXshDis']) ? $aCalInHDDisTemp['FCXshDis'] : NULL,
                    'FCXshChg'              => isset($aCalInHDDisTemp['FCXshChg']) ? $aCalInHDDisTemp['FCXshChg'] : NULL,
                    'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXshTotalAfDisChgV'],
                    'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXshTotalAfDisChgNV'],
                    'FCXshAmtV'             => $aCalDTTempForHD['FCXshAmtV'],
                    'FCXshAmtNV'            => $aCalDTTempForHD['FCXshAmtNV'],
                    'FCXshVat'              => $aCalDTTempForHD['FCXshVat'],
                    'FCXshVatable'          => $aCalDTTempForHD['FCXshVatable'],
                    'FTXshWpCode'           => $aCalDTTempForHD['FTXshWpCode'],
                    'FCXshWpTax'            => $aCalDTTempForHD['FCXshWpTax'],
                    'FCXshGrand'            => $aCalDTTempForHD['FCXshGrand'],
                    'FCXshRnd'              => $aCalDTTempForHD['FCXshRnd'],
                    'FTXshGndText'          => $aCalDTTempForHD['FTXshGndText'],
                    'FTXshRmk'              => $aDataDocument['otaSOFrmInfoOthRmk'],
                    'FTXshStaRefund'        => $aDataDocument['ohdSOStaRefund'],
                    'FTXshStaDoc'           => !empty($aDataDocument['ohdSOStaDoc']) ? $aDataDocument['ohdSOStaDoc'] : NULL,
                    'FTXshStaApv'           => !empty($aDataDocument['ohdSOStaApv']) ? $aDataDocument['ohdSOStaApv'] : NULL,
                    'FTXshStaPaid'          => $aDataDocument['ohdSOStaPaid'],
                    'FNXshStaDocAct'        => $tSOStaDocAct,
                    'FNXshStaRef'           => $aDataDocument['ocmSOFrmInfoOthRef']
                );

                $this->db->trans_begin();

                // Check Auto GenCode Document
                if ($tSOAutoGenCode == '1') {
                    $aStoreParam = array(
                        "tTblName"    => 'TARTSoHD',                           
                        "tDocType"    => '1',                                          
                        "tBchCode"    => $aDataDocument['oetSOFrmBchCode'],                                 
                        "tShpCode"    => "",                               
                        "tPosCode"    => "",                     
                        "dDocDate"    => date("Y-m-d")       
                    );
                    $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                    $aDataWhere['FTXshDocNo']   = $aAutogen[0]["FTXxhDocNo"];
                } else {
                    $aDataWhere['FTXshDocNo'] = $tSODocNo;
                }
        
                // [Add] Document HD
                $this->mSaleOrder->FSxMSOAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

                // [Add] HD Cst
                $this->mSaleOrder->FSxMSOAddUpdateHDCst($aDataCustomer,$aDataWhere, $aTableAddUpdate);

                // [Update] Doc No Into Doc Temp
                $this->mSaleOrder->FSxMSOAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

                // [Move] Doc HD Dis Temp To HDDis
                $this->mSaleOrder->FSaMSOMoveHdDisTempToHdDis($aDataWhere, $aTableAddUpdate);

                // [Move] Doc DTTemp To DT
                $this->mSaleOrder->FSaMSOMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

                // [Move] Doc DTDisTemp To DTTemp
                $this->mSaleOrder->FSaMSOMoveDtDisTempToDtDis($aDataWhere, $aTableAddUpdate);

                // [Move] Doc TCNTDocHDRefTmp To TARTSoHDDocRef
                $this->mSaleOrder->FSxMSOMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            }

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
                    'tCodeReturn'   => $aDataDocument['oetSODocNo'],
                    'tCstCode'      => $aDataMaster['FTCstCode'],
                    'tCshOrCrd'     => $aDataMaster['FTXshCshOrCrd'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent'         => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // ยกเลิกเอกสาร
    public function FSvCSOCancelDocument() {
        try {
            $tSODocNo       = $this->input->post('ptSODocNo');
            $tSOBCHCode     = $this->input->post('ptSOBCHCode');
            $aDataUpdate    = array(
                'tDocNo'    => $tSODocNo,
            );
            $aStaApv = $this->mSaleOrder->FSaMSOCancelDocument($aDataUpdate);

            // เชื่อม Rabbit MQ
            $aMQParams = [
                "queueName" => "AR_QDocApprove",
                "params"    => [
                    'ptFunction'    => 'TARTSoHD',
                    'ptSource'      => 'AdaStoreBack',
                    'ptDest'        => 'MQReceivePrc',
                    'ptFilter'      => '',
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tSOBCHCode,
                        "ptDocNo"       => $tSODocNo,
                        "ptDocType"     => 1,
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            FCNxCallRabbitMQ($aMQParams);

            $aReturnData = $aStaApv;
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // อนุมัติเอกสาร
    public function FSvCSOApproveDocument() {
        $tSODocNo   = $this->input->post('ptSODocNo');
        $tSOBchCode = $this->input->post('ptSOBchCode');

        try {
            $aDataUpdate = array(
                'FTBchCode'         => $tSOBchCode,
                'FTXshDocNo'        => $tSODocNo,
                'FTXshStaApv'       => 1,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername')
            );
            $this->mSaleOrder->FSaMSOApproveDocument($aDataUpdate);

            //หาว่าเอกสาร SO ใบนี้ ถูกสร้างมาจากหน้าจอ ใบสั่งสินค้าจากสาขา - ลูกค้า
            // $this->mSaleOrder->FSaMSOApproveDocumentINMGT($aDataUpdate);

            // MQ จะเป็นคนอนุมัติให้ หาว่าเอกสาร SO ใบนี้ ถูกสร้างมาจากหน้าจอ ใบสั่งสินค้าจากสาขา - ลูกค้า
            // $aMQParams = [
            //     "queueName" => "AR_QDocApprove",
            //     "params"    => [
            //         'ptFunction'    => 'TARTSoHD',
            //         'ptSource'      => 'AdaStoreBack',
            //         'ptDest'        => 'MQReceivePrc',
            //         'ptFilter'      => '',
            //         'ptData'        => json_encode([
            //             "ptBchCode"     => $tSOBchCode,
            //             "ptDocNo"       => $tSODocNo,
            //             "ptDocType"     => 1,
            //             "ptUser"        => $this->session->userdata("tSesUsername"),
            //         ])
            //     ]
            // ];
            // FCNxCallRabbitMQ($aMQParams);
            
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Success"
            );
        } catch (ErrorException $err) {
            $aReturnData = array(
                'nStaEvent' => '900',
                'tStaMessg' => language('common/main/main', 'tApproveFail')
            );
        }
        echo json_encode($aReturnData); ;
    }

    // Function Searh And Add Pdt In Tabel Temp
    public function FSoCSOSearchAndAddPdtIntoTbl() {
        try {
            $tSOBchCode = $this->input->post('ptSOBchCode');
            $tSODocNo = $this->input->post('ptSODocNo');
            $tSODataSearchAndAdd = $this->input->post('ptSODataSearchAndAdd');
            $tSOStaReAddPdt = $this->input->post('ptSOStaReAddPdt');
            $tSOSessionID = $this->session->userdata('tSesSessionID');
            $nLangEdit = $this->session->userdata("tLangID");
            // เช็คข้อมูลในฐานข้อมูล
            $aDataChkINDB = array(
                'FTBchCode' => $tSOBchCode,
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $tSOSessionID,
                'tSODataSearchAndAdd' => trim($tSODataSearchAndAdd),
                'tSOStaReAddPdt' => $tSOStaReAddPdt,
                'nLangEdit' => $nLangEdit
            );

            $aCountDataChkInDTTemp = $this->mSaleOrder->FSaCSOCountPdtBarInTablePdtBar($aDataChkINDB);
            $nCountDataChkInDTTemp = isset($aCountDataChkInDTTemp) && !empty($aCountDataChkInDTTemp) ? count($aCountDataChkInDTTemp) : 0;
            if ($nCountDataChkInDTTemp == 1) {
                // สินค้าหรือ BarCode ทีกรอกมี 1 ตัวให้เอาลง หรือ เช็ค สถานะ Appove ได้เลย
            } else if ($nCountDataChkInDTTemp > 1) {
                // มี Bar Code มากกว่า 1 ให้แสดง Modal
            } else {
                // ไม่พบข้อมูลบาร์โค๊ดกับรหัสสินค้าในระบบ 
                $aReturnData = array(
                    'nStaEvent' => 800,
                    'tStaMessg' => language('document/saleorder/saleorder', 'tSONotFoundPdtCodeAndBarcode')
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo jSOn_encode($aReturnData);
    }

    // Clear Data In DocTemp
    public function FSoCSOClearDataInDocTemp() {
        try {
            $this->db->trans_begin();

            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => $this->input->post('ptSODocNo'),
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->mSaleOrder->FSxMSOClearDataInDocTemp($aWhereClearTemp);

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
        echo jSOn_encode($aReturnData);
    }
    
    //คำนวณส่วนลดท้ายบิลใหม่อีกครั้ง กรณีมีการเพิ่มสินค้า , แก้ไขจำนวน , แก้ไขราคา , ลบสินค้า , ลดรายการ , ลดท้ายบิล 
    public function FSxCalculateHDDisAgain($ptDocumentNumber , $ptBCHCode){
        $aPackDataCalCulate = array(
            'tDocNo'        => $ptDocumentNumber,
            'tBchCode'      => $ptBCHCode
        );
        FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
    }

    //////////////////////////////////////////// อ้างอิงเอกสารภายใน //////////////////////////

    //อ้างอิงเอกสารภายใน (ref ใบเสนอราคา , ใบสั่งขาย)
    public function FSoCSOCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $tRefType   = $this->input->post('tRefType');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName,
            'tRefType' => $tRefType
        );

        $this->load->view('document/saleorder/refintdocument/wSaleOrderRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCSOCallRefIntDocDataTable(){

        $nPage                  = $this->input->post('nSORefIntPageCurrent');
        $tSORefIntBchCode       = $this->input->post('tSORefIntBchCode');
        $tSORefIntDocNo         = $this->input->post('tSORefIntDocNo');
        $tSORefIntDocDateFrm    = $this->input->post('tSORefIntDocDateFrm');
        $tSORefIntDocDateTo     = $this->input->post('tSORefIntDocDateTo');
        $tSORefIntStaDoc        = $this->input->post('tSORefIntStaDoc');
        $tSORefIntDocType       = $this->input->post('tSORefIntDocType');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nSORefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        
        $aDataParamFilter = array(
            'tSORefIntBchCode'      => $tSORefIntBchCode,
            'tSORefIntDocNo'        => $tSORefIntDocNo,
            'tSORefIntDocDateFrm'   => $tSORefIntDocDateFrm,
            'tSORefIntDocDateTo'    => $tSORefIntDocDateTo,
            'tSORefIntStaDoc'       => $tSORefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'tRefType'       => $tSORefIntDocType,
            'aAdvanceSearch' => $aDataParamFilter
        );
        $aDataParam = $this->mSaleOrder->FSoMSOCallRefIntDocDataTable($aDataCondition);

        $aConfigView = array(
            'nPage'         => $nPage,
            'aDataList'     => $aDataParam,
            'tRefType'      => $tSORefIntDocType
        );

         $this->load->view('document/saleorder/refintdocument/wSaleOrderRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCSOCallRefIntDocDetailDataTable(){

        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $tDocType           = $this->input->post('tDocType');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocType'  => $tDocType,
            'tDocNo'    => $tDocNo
        );
        $aDataParam     = $this->mSaleOrder->FSoMSOCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView    = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow,
            'tDocType'          => $tDocType
        );
        $this->load->view('document/saleorder/refintdocument/wSaleOrderRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCSOCallRefIntDocInsertDTToTemp(){
        $tSODocNo           =  $this->input->post('tSODocNo');
        $tSOFrmBchCode      =  $this->input->post('tSOFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
        $tSplStaVATInOrEx   = $this->input->post('tSplStaVATInOrEx');
        $tRefType           = $this->input->post('tRefType');
        
        $aDataParam = array(
            'tSODocNo'       => $tSODocNo,
            'tSOFrmBchCode'  => $tSOFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
            'tRefType'        => $tRefType,
        );
        
       $aDataResult = $this->mSaleOrder->FSoMSOCallRefIntDocInsertDTToTemp($aDataParam);

        // Calcurate Document DT Temp Array Parameter
        $aCalcDTParams = [
            'tDataDocEvnCall' => "",
            'tDataVatInOrEx' => 1,
            'tDataDocNo' => '',
            'tDataDocKey' => 'TARTSoHD',
            'tDataSeqNo' => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);
        $this->FSxCalculateHDDisAgain($tSODocNo,$tRefIntBchCode);
            
        return  $aDataResult;
    }

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCSOPageHDDocRef(){
        try {
            $tDocNo = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TARTSoHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TARTSoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            
            $aDataDocHDRef = $this->mSaleOrder->FSaMSoGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/saleorder/wSaleOrderDocRef', $aDataConfig, true);
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
    public function FSoCSOEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptSODocNo'),
                'FTXthDocKey'       => 'TARTSoHD',
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
            $aReturnData = $this->mSaleOrder->FSaMSOAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCSOEventDelHDDocRef(){
        try {
            $aData = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthDocKey'       => 'TARTSoHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aReturnData = $this->mSaleOrder->FSaMSODelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // สร้างเอกสารใบจัด
    public function FSoCSOEventGenPCK(){

        $tSOBchCode = $this->input->post('ptSOBCHCode');
        $tSODocNo   = $this->input->post('ptSODocNo');

        $tChkWah    = $this->input->post('ptChkWah');
        $tChkCat1   = $this->input->post('ptChkCat1');
        $tChkCat2   = $this->input->post('ptChkCat2');
        $tChkCat3   = $this->input->post('ptChkCat3');
        $tChkCat4   = $this->input->post('ptChkCat4');
        $tChkCat5   = $this->input->post('ptChkCat5');
        $ptSplite = [];

        if($tChkWah == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '0',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        if($tChkCat1 == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '1',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        if($tChkCat2 == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '2',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        if($tChkCat3 == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '3',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        if($tChkCat4 == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '4',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        if($tChkCat5 == '1'){
            $ptSpliteData   = [
                "ptSplit"         => '5',
            ];
        array_push($ptSplite,$ptSpliteData);
        }
        

        /// Check Stock
            $aChkStock = $this->mSaleOrder->FSaMSODCheckStk($tSODocNo);

        if($aChkStock['nStaEvent'] == 1){
        $aMQParams = [
            "queueName" => "CN_QReqGenDoc",
            "params"    => [
                'ptFunction'    => 'TCNTPdtPickHD',
                'ptSource'      => 'AdaStoreBack',
                'ptDest'        => 'MQReceivePrc-Doc',
                'ptData'        => json_encode([
                    "ptAgnCode"     => '',
                    "ptBchCode"     => $tSOBchCode,
                    "ptDocNo"       => $tSODocNo,
                    "ptUserCode"    => $this->session->userdata('tSesUserCode'),
                    "ptPickType"    => '3',
                    'paCondition'   => $ptSplite,
                ])
            ]
        ];
        // เชื่อม Rabbit MQ
        FCNxCallRabbitMQ($aMQParams);
        }else{
            $aDataUpdate = array(
                'FTBchCode'         => $tSOBchCode,
                'FTXshDocNo'        => $tSODocNo,
                'FTXshStaApv'       => 3,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername')
            );
            $this->mSaleOrder->FSaMSOStaprcDocument($aDataUpdate);
        }
        echo  json_encode($aChkStock);
    }

    // ตารางข้อมูล
    public function FSoCSODataTableGenPOGetCst() {
       
        $tCstCode = $this->input->post('tCSTCode');
        $aDataResult = $this->mSaleOrder->FSoMSODataTableGenPOGetCst($tCstCode);
        $aReturnData = array(
            'nStaEvent' => '500',
        );
        echo  json_encode($aDataResult[0]);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCSODataTableGenPOGetProduct(){
        $tSODocNo           =  $this->input->post('tSODocNo');
        $tSOFrmBchCode      =  $this->input->post('tSOFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        
        $aDataParam = array(
            'tSODocNo'       => $tSODocNo,
            'tSOFrmBchCode'  => $tSOFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
        );
        
        $aDataResult = $this->mSaleOrder->FSoMSOCallGenSORefIntDocInsertDTToTemp($aDataParam);

        // Calcurate Document DT Temp Array Parameter
        $aCalcDTParams = [
            'tDataDocEvnCall' => "",
            'tDataVatInOrEx' => 1,
            'tDataDocNo' => '',
            'tDataDocKey' => 'TARTSoHD',
            'tDataSeqNo' => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);
        $this->FSxCalculateHDDisAgain($tSODocNo,$tRefIntBchCode);
            
        return  $aDataResult;
    }
}



