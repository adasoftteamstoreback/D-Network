<?php

use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

defined('BASEPATH') or exit('No direct script access allowed');

class Deposit_controller extends MX_Controller {

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('document/depositdoc/Deposit_Model');
        parent::__construct();
    }

    public function index($nDPSBrowseType, $tDPSBrowseOption) {

        //เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
        $aCookieMenuCode = array(
            'name'	=> 'tMenuCode',
            'value' => json_encode('AR0002'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuCode);

        $aCookieMenuName = array(
            'name'	=> 'tMenuName',
            'value' => json_encode('ใบมัดจำ'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuName);
        //end

        $aDataConfigView = array(
            'nDPSBrowseType'     => $nDPSBrowseType,
            'tDPSBrowseOption'   => $tDPSBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('dcmDPS/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('dcmDPS/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/depositdoc/wDeposit', $aDataConfigView);
    }

    // Functionality : Function Call Page From Search List
    // Parameters : Ajax and Function Parameter
    // Creator : 02/07/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCDPSFormSearchList() {
        $this->load->view('document/depositdoc/wDepositFormSearchList');
    }

    // Functionality : Function Call Page Data Table
    // Parameters : Ajax and Function Parameter
    // Creator : 02/07/2021 Off
    // LastUpdate: -
    // Return : Object View Data Table
    // Return Type : object
    public function FSoCDPSDataTable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $aAlwEvent      = FCNaHCheckAlwFunc('dcmDPS/0/0');

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
                'nRow'                  => 10,
                'aDatSessionUserLogIn'  => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->Deposit_Model->FSaMDPSGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage'                 => $nPage,
                'nOptDecimalShow'       => $nOptDecimalShow,
                'aAlwEvent'             => $aAlwEvent,
                'aDataList'             => $aDataList
            );
            $tDPSViewDataTableList = $this->load->view('document/depositdoc/wDepositDataTable', $aConfigView, true);
            $aReturnData = array(
                'tDPSViewDataTableList' => $tDPSViewDataTableList,
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

    // Functionality : Function Delete Document Purchase Invoice
    // Parameters : Ajax and Function Parameter
    // Creator : 09/07/2021 Off
    // LastUpdate: -
    // Return : Object View Data Table
    // Return Type : object
    public function FSoCDPSDeleteEventDoc() {
        $tDataDocNo     = $this->input->post('tDataDocNo');
        try {
            $tRefInDocNo    = $this->input->post('tDPSRefInCode');
            $aDataMaster    = array(
                'tDataDocNo' => $tDataDocNo
            );

            if (!empty($tRefInDocNo)) {
                $nStaRef = '0';
                $this->Deposit_Model->FSaMDPSClearRefDoc($tRefInDocNo,$nStaRef,$tDataDocNo);
            }

            $aResDelDoc = $this->Deposit_Model->FSnMDPSDelDocument($aDataMaster);

            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Delete Document Success',
                    'tLogType' => 'INFO',
                    'tDocNo' => $tDataDocNo,
                    'tEventName' => 'ลบใบมัดจำ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc'],
                    'tLogType' => 'ERROR',
                    'tDocNo' => $tDataDocNo,
                    'tEventName' => 'ลบใบมัดจำ',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo' => $tDataDocNo,
                'tEventName' => 'ลบใบมัดจำ',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aDataStaReturn);
        echo json_encode($aDataStaReturn);
    }

    // Functionality : Function Call Page Add Tranfer Out
    // Parameters : Ajax and Function Parameter
    // Creator : 02/07/2021 Off
    // LastUpdate: -
    // Return : Object View Page Add
    // Return Type : object
    public function FSoCDPSAddPage() {
        try {

            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => '',
                'FTXthDocKey' => 'TARTRcvDepositHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];

            $tUserBchCode = $this->session->userdata('tSesUsrBchCom');
            // echo $tUserBchCode;die();
            if(!empty($tUserBchCode)){
                $aDataBch = $this->Deposit_Model->FSaMDPSGetDetailUserBranch($tUserBchCode);
                $tSOPplCode = $aDataBch['item']['FTPplCode'];
            }else{
                $tSOPplCode = '';
            }

            $this->Deposit_Model->FSaMDPSDeletePDTInTmp($aWhereClearTemp);
            $this->Deposit_Model->FSxMDPSClearDataInDocTemp($aWhereClearTemp);

            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $nOptDocSave = FCNnHGetOptionDocSave();
            $nOptScanSku = FCNnHGetOptionScanSku();
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            $aCompData    = $this->mCompany->FSaMCMPList('','',array('FNLngID' => $nLangEdit));
            

            $aWhereHelperCalcDTTemp = array(
                'tDataDocEvnCall' => "",
                'tDataVatInOrEx' => 1,
                'tDataDocNo' => '',
                'tDataDocKey' => 'TARTRcvDepositHD',
                'tDataSeqNo' => ''
            );
            FCNbHCallCalcDocDTTemp($aWhereHelperCalcDTTemp);

            $aDataComp = FCNaGetCompanyForDocument();

            $tBchCode = $aDataComp['tBchCode'];
            $tCmpRteCode = $aDataComp['tCmpRteCode'];
            $tVatCode = $aDataComp['tVatCode'];
            $cVatRate = FCNoHVatActiveList($tVatCode);
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
            $aDataUserGroup = $this->Deposit_Model->FSaMDPSGetShpCodeForUsrLogin($aDataShp);
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
                'cVatRate' => $cVatRate->FCVatRate,
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
                'aRateDefault'  => $aCompData,
                'tBchCompCode' => FCNtGetBchInComp(),
                'tBchCompName' => FCNtGetBchNameInComp(),
                'aDataDocHD' => array('rtCode' => '800'),
                'aDataDocHDSpl' => array('rtCode' => '800'),
                'tCmpRetInOrEx' => $tCmpRetInOrEx,
                'tSOPplCode'  => $tSOPplCode
            );
            $tSOViewPageAdd = $this->load->view('document/depositdoc/wDepositAdd', $aDataConfigViewAdd, true);
            $aReturnData = array(
                'tSOViewPageAdd' => $tSOViewPageAdd,
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


    // Functionality : Function Call Page Edit Tranfer Out
    // Parameters : Ajax and Function Parameter
    // Creator : 15/07/2021 Off
    // LastUpdate: -
    // Return : Object View Page Add
    // Return Type : object
    public function FSoCDPSEditPage() {
        $tDPSDocNo = $this->input->post('ptDPSDocNo');
        try {

            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => $tDPSDocNo,
                'FTXthDocKey' => 'TARTRcvDepositHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->Deposit_Model->FSaMDPSDeletePDTInTmp($aWhereClearTemp);
            $this->Deposit_Model->FSxMDPSClearDataInDocTemp($aWhereClearTemp);

            $tUserBchCode = $this->session->userdata('tSesUsrBchCom');
            // echo $tUserBchCode;die();
            // if(!empty($tUserBchCode)){
            //     $aDataBch = $this->Deposit_Model->FSaMDPSGetDetailUserBranch($tUserBchCode);
            //     $tSOPplCode = $aDataBch['item']['FTPplCode'];
            // }else{
            //     $tSOPplCode = '';
            // }

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

            $aDataUserGroup = $this->Deposit_Model->FSaMDPSGetShpCodeForUsrLogin($aDataShp);
            if (isset($aDataUserGroup) && empty($aDataUserGroup)) {
                $tUsrBchCode = "";
                $tUsrBchName = "";
                $tUsrMerCode = "";
                $tUsrMerName = "";
                $tUsrShopType = "";
                $tUsrShopCode = "";
                $tUsrShopName = "";
                $tUsrWahCode = "";
                $tUsrWahName = "";
            } else {
                $tUsrBchCode = $aDataUserGroup["FTBchCode"];
                $tUsrBchName = $aDataUserGroup["FTBchName"];
                $tUsrMerCode = $aDataUserGroup["FTMerCode"];
                $tUsrMerName = $aDataUserGroup["FTMerName"];
                $tUsrShopType = $aDataUserGroup["FTShpType"];
                $tUsrShopCode = $aDataUserGroup["FTShpCode"];
                $tUsrShopName = $aDataUserGroup["FTShpName"];
                $tUsrWahCode = $aDataUserGroup["FTWahCode"];
                $tUsrWahName = $aDataUserGroup["FTWahName"];
            }

            // Data Table Document
            $aTableDocument = array(
                'tTableHD'      => 'TARTSoHD',
                'tTableHDCst'   => 'TARTSoHDCst',
                'tTableHDDis'   => 'TARTSoHDDis',
                'tTableDT'      => 'TARTSoDT',
                'tTableDTDis'   => 'TARTSoDTDis'
            );

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo' => $tDPSDocNo,
                'FTXthDocKey' => 'TARTRcvDepositHD',
                'FNLngID' => $nLangEdit,
                'nRow' => 10000,
                'nPage' => 1,
            );

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD = $this->Deposit_Model->FSaMDPSGetDataDocHD($aDataWhere);
            // Move Data DT TO DTTemp
            $this->Deposit_Model->FSxMDPSMoveDTToDTTemp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();
                // Prorate HD
                // FCNaHCalculateProrate('TARTSoHD', $tDPSDocNo);
                $tDPSVATInOrEx = ($aDataDocHD['rtCode'] == '1') ? $aDataDocHD['raItems']['FTXshVATInOrEx'] : 1;
                $aCalcDTTempParams = array(
                    'tDataDocEvnCall' => '1',
                    'tDataVatInOrEx' => $tDPSVATInOrEx,
                    'tDataDocNo' => $tDPSDocNo,
                    'tDataDocKey' => 'TARTSoHD',
                    'tDataSeqNo' => ""
                );
                // $tUserBchCode = $aDataDocHD['raItems']['FTBchCode'];
                // if(!empty($tUserBchCode)){
                //     $aDataBch = $this->Deposit_Model->FSaMDPSGetDetailUserBranch($tUserBchCode);
                //     $tSOPplCode = $aDataBch['item']['FTPplCode'];
                // }else{
                //     $tSOPplCode = '';
                // }

                $aDataWhere = array(
                    'FNLngID' => $nLangEdit
                );
    
                $aDataComp = FCNaGetCompanyForDocument();

                $tVatCode = $aDataComp['tVatCode'];
                $cVatRate = FCNoHVatActiveList($tVatCode);
                $tBchCode = $aDataComp['tBchCode'];
                $tCmpRteCode = $aDataComp['tCmpRteCode'];
                $cXthRteFac = $aDataComp['cXthRteFac'];
                $tCmpRetInOrEx = $aDataComp['tCmpRetInOrEx'];
                
                // $tStaCalDocDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTTempParams);
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
                    // 'tSOPplCode' => '',
                    'tCmpRetInOrEx' => $tCmpRetInOrEx,
                    'cVatRate' => $cVatRate->FCVatRate,
                    'tVatCode' => $tVatCode,
                );
                $tSOViewPageEdit = $this->load->view('document/depositdoc/wDepositAdd', $aDataConfigViewAdd, true);
                $aReturnData = array(
                    'tSOViewPageEdit' => $tSOViewPageEdit,
                    'nStaEvent' => '1',
                    'tStaMessg'         => 'Call Page Success',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $tDPSDocNo,
                    'tEventName' => 'เรียกดูเอกสารใบมัดจำ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXshApvCode']
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $tDPSDocNo,
                'tEventName' => 'เรียกดูเอกสารใบมัดจำ',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXshApvCode']
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    // Functionality : Call View Table Data Doc DT Temp
    // Parameters : Ajax and Function Parameter
    // Creator : 02/07/2021 Off
    // Return : Object  View Table Data Doc DT Temp
    // Return Type : object
    public function FSoCDPSPdtAdvTblLoadData() {
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
            $tSODocNo           = $this->input->post('ptSODocNo');
            $tDPSStaApv          = $this->input->post('ptDPSStaApv');
            $tDPSStaDoc          = $this->input->post('ptDPSStaDoc');
            $tDPSVATInOrEx       = $this->input->post('ptDPSVATInOrEx');
            $nSOPageCurrent     = $this->input->post('pnSOPageCurrent');
            $tSearchPdtAdvTable = $this->input->post('ptSearchPdtAdvTable');
            $tSOPdtCode         = $this->input->post('ptSOPdtCode');
            $tSOPunCode         = $this->input->post('ptSOPunCode');

            //Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Call Advance Table
            $tTableGetColumeShow    = 'TARTSoDT';
            // $aColumnShow            = FCNaDCLGetColumnShow($tTableGetColumeShow);
            
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tSODocNo,
                'FTXthDocKey'           => 'TARTRcvDepositHD',
                'nPage'                 => $nSOPageCurrent,
                'nRow'                  => 90000,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );

            // Calcurate Document DT Temp Array Parameter
            $aCalcDTParams = [
                'tDataDocEvnCall' => '1',
                'tDataVatInOrEx' => $tDPSVATInOrEx,
                'tDataDocNo' => $tSODocNo,
                'tDataDocKey' => 'TARTRcvDepositHD',
                'tDataSeqNo' => ''
            ];
            // FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aDataDocDTTemp     = $this->Deposit_Model->FSaMDPSGetDocDTTempListPage($aDataWhere);
            $aDataDocDTTempSum  = $this->Deposit_Model->FSaMDPSSumDocDTTemp($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tDPSStaApv'         => $tDPSStaApv,
                'tDPSStaDoc'         => $tDPSStaDoc,
                'tDPSVATInOrEx'      => $tDPSVATInOrEx,
                'tSOPdtCode'        => $tSOPdtCode,
                'tSOPunCode'        => $tSOPunCode,
                'nPage'             => $nSOPageCurrent,
                'aColumnShow'       => array(),
                'aDataDocDTTemp'    => $aDataDocDTTemp,
                'aDataDocDTTempSum' => $aDataDocDTTempSum,
            );

            $tSOPdtAdvTableHtml = $this->load->view('document/depositdoc/wDepositPdtAdvTableData', $aDataView, true);

            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'   => $tDPSVATInOrEx,
                'tDocNo'        => $tSODocNo,
                'tDocKey'       => 'TARTRcvDepositHD',
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
                'tSplVatType'   => $tDPSVATInOrEx
            );
            // print_r($aSOEndOfBill);
            // exit(); 

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
        echo json_encode($aReturnData);
    }

    // Function: Add สินค้า ลง Document DT Temp
    // Parameters: Document Type
    // Creator: 02/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Add Pdt To Doc DT Temp
    // ReturnType: Object
    public function FSoCDPSAddPdtIntoDocDTTemp() {
        try {

            $tSOUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tSODocNo           = $this->input->post('tSODocNo');
            $tDPSVATInOrEx       = $this->input->post('tSOVATInOrEx');
            $tSOBchCode         = $this->input->post('tSelectBCH');
            $tSOOptionAddPdt    = $this->input->post('tSOOptionAddPdt');
            $tSOPdtData         = $this->input->post('tSOPdtData');
            $aSOPdtData         = json_decode($tSOPdtData);
            $tSOPplCodeBch      = $this->input->post('tSOPplCodeBch');//กลุ่มราคาตามสาขา
            $tSOPplCodeCst      = $this->input->post('tSOPplCodeCst');//กลุ่มราคาตามลูกค้า
            $nVatinOrEx         = $this->input->post('nVatInOrEx');
            $nVatRate           = $this->input->post('nVatRate');

            $aDataWhere = array(
                'FTBchCode' => $tSOBchCode,
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTRcvDepositHD',
            );

            $this->db->trans_begin();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aSOPdtData); $nI++) {
                $tSOPdtCode = $aSOPdtData[$nI]->pnPdtCode;
                $tSOBarCode = $aSOPdtData[$nI]->ptBarCode;
                $tSOPunCode = $aSOPdtData[$nI]->ptPunCode;
                $aDataGetprice = array(
                    'tSOPplCodeCst' => $tSOPplCodeCst,
                    'tSOPplCodeBch' => $tSOPplCodeCst,
                    'tSOPdtCode'    => $tSOPdtCode,
                    'tSOBarCode'    => $tSOBarCode,
                    'tSOPunCode'    => $tSOPunCode
                );
                $cSOPrice       = $aSOPdtData[$nI]->packData->PriceRet;
                $nDPSPrice       = str_replace(",","",$cSOPrice);
                $aDataPdtParams = array(
                    'tDocNo'            => $tSODocNo,
                    'tBchCode'          => $tSOBchCode,
                    'tPdtCode'          => $tSOPdtCode,
                    'tBarCode'          => $tSOBarCode,
                    'tPunCode'          => $tSOPunCode,
                    'cPrice'            => str_replace(",","",$cSOPrice),
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdSOLangEdit"),
                    // 'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TARTRcvDepositHD',
                    'tSOOptionAddPdt'   => $tSOOptionAddPdt,
                    'tSOUsrCode'        => $this->input->post('ohdSOUsrCode'),
                    'FTTmpStatus'       => '2'
                );
                $aDataPdtMaster = $this->Deposit_Model->FSaMDPSGetDataPdt($aDataPdtParams);

                if($nVatinOrEx == '1'){
                    $nTotalVat = (($nDPSPrice*100)/(100+$nVatRate));
                }else{
                    $nTotalVat = (($nDPSPrice*(100+$nVatRate))/100)-$nDPSPrice;
                }
                $aDataPdtParams['FCXsdVat'] = $nTotalVat;
                $aDataPdtParams['FCXsdVatable'] = $nDPSPrice-$nTotalVat;

                $nStaInsPdtToTmp = $this->Deposit_Model->FSaMDPSInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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
                    'tDataVatInOrEx'    => $tDPSVATInOrEx,
                    'tDataDocNo'        => $tSODocNo,
                    'tDataDocKey'       => 'TARTRcvDepositHD',
                    'tDataSeqNo'        => ''
                ];
                // $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                $tStaCalcuRate = TRUE;
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

    // Function: Add SO ลง Document DT Temp
    // Parameters: Document Type
    // Creator: 06/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Add Pdt To Doc DT Temp
    // ReturnType: Object
    public function FSoCDPSAddPdtSOIntoDocDTTemp() {
        try {
            $tSOUserLevel       = $this->session->userdata('tSesUsrLevel');
            $tSODocNo           = $this->input->post('tSODocNo');
            $tDPSVATInOrEx       = $this->input->post('tSOVATInOrEx');
            $tSOBchCode         = $this->input->post('tSelectBCH');
            $tSOOptionAddPdt    = $this->input->post('tSOOptionAddPdt');
            $tSOPdtData         = $this->input->post('tSOPdtData');
            $aSOPdtData         = json_decode($tSOPdtData);
            $tSOPplCodeBch      = $this->input->post('tSOPplCodeBch');//กลุ่มราคาตามสาขา
            $tSOPplCodeCst      = $this->input->post('tSOPplCodeCst');//กลุ่มราคาตามลูกค้า
            $nVatinOrEx         = $this->input->post('nVatInOrEx');
            $nVatRate           = $this->input->post('nVatRate');
            $aDataWhere = array(
                'FTBchCode' => $tSOBchCode,
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTRcvDepositHD',
            );

            $this->db->trans_begin();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtParams = array(
                    'tDocNo'            => $tSODocNo,
                    'tBchCode'          => $tSOBchCode,
                    'nMaxSeqNo'         => $this->input->post('tSeqNo'),
                    'nLngID'            => $this->input->post("ohdSOLangEdit"),
                    'tPdtCode'          => $aSOPdtData[0],
                    'FTXtdPdtName'      => $aSOPdtData[0],
                    'cPrice'            => $aSOPdtData[1],
                    // 'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tSessionID'        => $this->input->post('ohdSesSessionID'),
                    'tDocKey'           => 'TARTRcvDepositHD',
                    'tSOOptionAddPdt'   => $tSOOptionAddPdt,
                    'tSOUsrCode'        => $this->input->post('ohdSOUsrCode'),
                    'FTXtdVatType'      => '1',
                    'FCXtdVatRate'      => $nVatRate,
                    'FTTmpStatus'       => '1'
                );

                if($nVatinOrEx == '1'){
                    $nTotalVat = (($aSOPdtData[1]*100)/(100+$nVatRate));
                }else{
                    $nTotalVat = (($aSOPdtData[1]*(100+$nVatRate))/100)-$aSOPdtData[1];
                }
                $aDataPdtParams['FCXsdVat'] = $nTotalVat;
                $aDataPdtParams['FCXsdVatable'] = $aSOPdtData[1]-$nTotalVat;

                $nStaInsPdtToTmp = $this->Deposit_Model->FSaMDPSInsertPDTSOToTemp($aDataPdtParams);
            

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
                    'tDataVatInOrEx'    => $tDPSVATInOrEx,
                    'tDataDocNo'        => $tSODocNo,
                    'tDataDocKey'       => 'TARTRcvDepositHD',
                    'tDataSeqNo'        => ''
                ];
                // $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                $tStaCalcuRate = TRUE;
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

    // Function: Edit Inline ลง Document DT Temp
    // Parameters: Document Type
    // Creator: 05/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Edit Pdt To Doc DT Temp
    // ReturnType: Object
    public function FSoCDPSEditPdtIntoDocDTTemp() {
        try {
            $tSOBchCode         = $this->input->post('tSOBchCode');
            $tSODocNo           = $this->input->post('tSODocNo');
            $nSOSeqNo           = $this->input->post('nSOSeqNo');
            $tSOSessionID       = $this->input->post('ohdSesSessionID');
            $nStaDelDis         = $this->input->post('nStaDelDis');
            $nVatinOrEx         = $this->input->post('nVatInOrEx');
            $nVatRate           = $this->input->post('nVatRate');

            $aDataWhere = array(
                'tSOBchCode'    => $tSOBchCode,
                'tSODocNo'      => $tSODocNo,
                'nSOSeqNo'      => $nSOSeqNo,
                'tSOSessionID'  => $this->input->post('ohdSesSessionID'),
                'tDocKey'       => 'TARTRcvDepositHD',
            );
            $aDataUpdateDT = array(
                'FCXtdQty'          => $this->input->post('nQty'),
                // 'FCXtdSetPrice'     => $this->input->post('cPrice'),
                'FCXtdSetPrice'     => $this->input->post('nGrand'),
                'FCXtdNet'          => $this->input->post('cNet'),
                'FTTmpRemark'       => $this->input->post('tRemark'),
                'FCXtdSalePrice'    => $this->input->post('cPrice'),
                // 'FCXtdSalePrice'    => $this->input->post('nGrand'),
                'FTXtdPdtName'      => $this->input->post('tName')
            );

            if($nVatinOrEx == '1'){
                $nTotalVat = (($this->input->post('nGrand')*100)/(100+$nVatRate));
            }else{
                $nTotalVat = (($this->input->post('nGrand')*(100+$nVatRate))/100)-$this->input->post('nGrand');
            }
            $aDataUpdateDT['FCXsdVat'] = $nTotalVat;
            $aDataUpdateDT['FCXsdVatable'] = $this->input->post('nGrand')-$nTotalVat;

            $this->db->trans_begin();
            $this->Deposit_Model->FSaMDPSUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
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

    // Function: Remove Product In Documeny Temp
    // Parameters: Document Type
    // Creator: 02/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Edit Pdt To Doc DT Temp
    // ReturnType: Object
    public function FSvCDPSRemovePdtInDTTmp() {
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

            $aStaDelPdtDocTemp = $this->Deposit_Model->FSnMDPSDelPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TARTRcvDepositHD',
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

    // Function: Remove Product In Documeny Temp Multiple
    // Parameters: Document Type
    // Creator: 15/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Event Delte
    // ReturnType: Object
    public function FSvCDPSRemovePdtInDTTmpMulti() {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tBchCode' => $this->input->post('ptDPSBchCode'),
                'tDocNo' => $this->input->post('ptDPSDocNo'),
                'tVatInOrEx' => $this->input->post('ptDPSVatInOrEx'),
                'aDataPdtCode' => $this->input->post('paDataPdtCode'),
                'aDataSeqNo' => $this->input->post('paDataSeqNo')
            );
            $aStaDelPdtDocTemp = $this->Deposit_Model->FSnMDPSDelMultiPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aCalcDTParams = [
                    'tDataDocEvnCall' => '',
                    'tDataVatInOrEx' => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo' => $aDataWhere['tDocNo'],
                    'tDataDocKey' => 'TARTRcvDepositHD',
                    'tDataSeqNo' => ''
                ];
                // FCNbHCallCalcDocDTTemp($aCalcDTParams);
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

    // =================================================================================== Add / Edit Document ===================================================================================
    // Function: Check Product Have In Temp For Document DT
    // Parameters: Ajex Event Before Save DT
    // Creator: 05/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Check Product DT Temp
    // ReturnType: Object
    public function FSoCDPSChkHavePdtForDocDTTemp() {
        try {
            $tSODocNo = $this->input->post("ptSODocNo");
            $tDPSSessionID = $this->input->post('tDPSSesSessionID');
            $aDataWhere = array(
                'FTXthDocNo' => $tSODocNo,
                'FTXthDocKey' => 'TARTRcvDepositHD',
                'FTSessionID' => $tDPSSessionID
            );
            $nCountPdtInDocDTTemp = $this->Deposit_Model->FSnMDPSChkPdtInDocDTTemp($aDataWhere);
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
        echo json_encode($aReturnData);
    }

    // Function: คำนวณค่าจาก DT Temp ให้ HD
    // Parameters: Ajex Event Add Document
    // Creator: 15/07/2021 Off
    // LastUpdate: -
    // Return: Array Data Calcurate DocDTTemp For HD
    // ReturnType: Array
    private function FSaCDPSCalDTTempForHD($paParams) {
        $aCalDTTemp = $this->Deposit_Model->FSaMDPSCalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems = $aCalDTTemp[0];
            // คำนวณหา ยอดปัดเศษ ให้ HD(FCXphRnd)
            $pCalRoundParams = [
                'FCXshAmtV' => $aCalDTTempItems['FCXshAmtV'],
                'FCXshAmtNV' => $aCalDTTempItems['FCXshAmtNV']
            ];

            // print_r($pCalRoundParams);
            // die();
            // // คำนวณหา ยอดรวม ให้ HD(FCXphGrand)
            // $nRound = $aRound['nRound'];
            // $cGrand = $aRound['cAfRound'];
            $nRound = 0;
            $cGrand = $aCalDTTempItems['FCXshAmtV'] + $aCalDTTempItems['FCXshAmtNV'];
            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXshRnd'] = $nRound;
            $aCalDTTempItems['FCXshGrand'] = $cGrand;
            $aCalDTTempItems['FTXshGndText'] = $tGndText;
            return $aCalDTTempItems;
        }
    }

    // Function: Add Document 
    // Parameters: Ajex Event Add Document
    // Creator: 15/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Add Document
    // ReturnType: Object
    public function FSoCDPSAddEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDPSAutoGenCode    = (isset($aDataDocument['ocbDPSStaAutoGenCode'])) ? 1 : 0;
            $tDPSDocNo          = (isset($aDataDocument['oetDPSDocNo'])) ? $aDataDocument['oetDPSDocNo'] : '';
            $tDPSDocDate        = $aDataDocument['oetSODocDate'] . " " . $aDataDocument['oetSODocTime'];
            $tDPSStaDocAct      = (isset($aDataDocument['ocbDPSFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tDPSVATInOrEx      = $aDataDocument['ocmVatInOrEx'];
            $tDPSStaRef         = $aDataDocument['ocmDPSFrmInfoOthRef'];
            $tDPSSessionID      = $this->input->post('ohdSesSessionID');
            $tFTXshRefExt       = $this->input->post('oetDPSRefExt');
            $tRefin             = $this->input->post("oetDPSRefInAllName");
            $tFDXshRefExtDate   = $this->input->post('oetDPSRefExtDate');
            $tFTXshRefInt       = $this->input->post('oetDPSRefInt');
            $tFDXshRefIntDate   = $this->input->post('oetDPSRefIntDate');

            //--------------------------------------------------------------------
            $aCalcDTParams      = [
                'tBchCode'          => $aDataDocument['ohdDPSBchCode'],
                'tDataDocEvnCall'   => '',
                'tDataVatInOrEx'    => $tDPSVATInOrEx,
                'tDataDocNo'        => $tDPSDocNo,
                'tDataDocKey'       => 'TARTRcvDepositHD',
                'tDataSeqNo'        => ''
            ];
            $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
            //-----------------------------------------------------------------------------

            $aCalDTTempParams   = [
                'tDocNo'            => $tDPSDocNo,
                'tBchCode'          => $aDataDocument['ohdDPSBchCode'],
                'tSessionID'        => $tDPSSessionID,
                'tDocKey'           => 'TARTRcvDepositHD',
                'tDataVatInOrEx'    => $tDPSVATInOrEx,
            ];

            $this->Deposit_Model->FSaMDPSCalVatLastDT($aCalDTTempParams);
            $aCalDTTempForHD = $this->FSaCDPSCalDTTempForHD($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TARTRcvDepositHD',
                'tTableHDCst'   => 'TARTRcvDepositHDCst',
                'tTableDT'      => 'TARTRcvDepositDT',
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'     => $aDataDocument['ohdDPSBchCode'],
                'FTXshDocNo'    => $tDPSDocNo,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->input->post('ohdSOUsrCode'),
                'FTLastUpdBy'   => $this->input->post('ohdSOUsrCode'),
                'FTSessionID'   => $this->input->post('ohdSesSessionID'),
                'FTSessionID'   => $this->input->post('ohdSesSessionID'),
                'FTVatCode'     => $this->input->post('ohdDPSVatCode'),
                'FTVatRate'     => $this->input->post('ohdDPSVatRate'),
            );

            //array Data Customer 
            $aDataCustomer = array(
                'FTXshCardID'   => $aDataDocument['oetPanel_CustomerTaxID'],
                'FTXshCstName'  => $aDataDocument['oetPanel_CustomerName'],
                'FTXshCstTel'   =>$aDataDocument['oetPanel_CustomerTelephone'],
                'FTCstCode'     => $aDataDocument['oetSOFrmCstCode'],
                'FNXshAddrTax'  => $aDataDocument['oetPanel_CustomerAddress'],
            );

            $nDocType   = $this->Deposit_Model->FSnMDPSGetDocType();
            // Array Data HD Master
            $aDataMaster    = array(
                'FTCstCode'         => $aDataDocument['oetSOFrmCstCode'],
                'FTXshRefExt'       => $tFTXshRefExt,
                'FTXshRefInt'       => $tRefin,
                'FTShfCode'         => '',
                'FTSpnCode'         => $aDataDocument['ohdSOUsrCode'],
                'FTXshRmk'          => $aDataDocument['oetDPSHdRemark'],
                'FNXshDocType'      => $nDocType['FNSdtDocType'] ,
                'FDXshDocDate'      => (!empty($tDPSDocDate)) ? $tDPSDocDate : NULL,
                'FTXshCshOrCrd'     => $aDataDocument['ocmTQPaymentType'],
                'FTXshVATInOrEx'    => $tDPSVATInOrEx,
                'FTDptCode'         => $aDataDocument['ohdSODptCode'],
                'FTUsrCode'         => $aDataDocument['ohdSOUsrCode'],
                'FDXshRefExtDate'   => (!empty($aDataDocument['oetDPSRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDPSRefExtDate'])) : NULL,
                'FDXshRefIntDate'   => (!empty($aDataDocument['oetDPSRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDPSRefIntDate'])) : NULL,
                'FTRteCode'         => $aDataDocument['ohdDPSRateCode'],
                'FCXshRteFac'       => $aDataDocument['ohdSORteFac'],
                'FCXshTotal'        => $aCalDTTempForHD['FCXshTotal'],
                'FCXshTotalNV'      => $aCalDTTempForHD['FCXshTotalNV'],
                'FCXshTotalNoDis'   => $aCalDTTempForHD['FCXshTotalNoDis'],
                'FCXshAmtV'         => $aCalDTTempForHD['FCXshAmtV'],
                'FCXshAmtNV'        => $aCalDTTempForHD['FCXshAmtNV'],
                'FCXshVat'          => $aCalDTTempForHD['FCXshVat'],
                'FCXshVatable'      => $aCalDTTempForHD['FCXshVatable'],
                'FCXshGrand'        => $aCalDTTempForHD['FCXshGrand'],
                'FCXshRnd'          => $aCalDTTempForHD['FCXshRnd'],
                'FTXshGndText'      => $aCalDTTempForHD['FTXshGndText'],
                'FTXshStaRefund'    => $aDataDocument['ohdSOStaRefund'],
                'FTXshStaDoc'       => $aDataDocument['ohdDPSStaDoc'],
                'FTXshStaApv'       => !empty($aDataDocument['ohdDPSStaApv']) ? $aDataDocument['ohdDPSStaApv'] : NULL,
                'FTXshStaPaid'      => $aDataDocument['ohdDPSStaPaid'],
                'FNXshStaDocAct'    => $tDPSStaDocAct,
                'FNXshStaRef'       => $tDPSStaRef,
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tDPSAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TARTRcvDepositHD',                           
                    "tDocType"    => '1',                                          
                    "tBchCode"    => $aDataDocument['ohdDPSBchCode'],                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d")       
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXshDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXshDocNo'] = $tDPSDocNo;
            }
            // Add Update Document HD
            $this->Deposit_Model->FSxMDPSAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // Add Update HD Cst
            $this->Deposit_Model->FSxMDPSAddUpdateHDCst($aDataCustomer,$aDataWhere, $aTableAddUpdate);

            // Update Doc No Into Doc Temp
            $this->Deposit_Model->FSxMDPSAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->Deposit_Model->FSaMDPSMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if($this->input->post('ocmDPSSelectBrowse') == '1'){
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSqHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }elseif($this->input->post('ocmDPSSelectBrowse') == '0'){
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSoHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }elseif($this->input->post('ocmDPSSelectBrowse') == '2'){
                // Insert ใบเครมสินค้า
                $aDataInsertRef = [
                    'FTAgnCode'         => '',
                    'FTBchCode'         => $aDataDocument['ohdDPSBchCode'],
                    'FTPchDocNo'        => $tRefin,
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataWhere['FTXshDocNo'],
                    'FTXshRefKey'       => 'DPS',
                    'FDXshRefDocDate'   => date('Y-m-d H:i:s'),
                ];
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRefCLM($aDataInsertRef);
            }

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'บันทึกใบมัดจำ',
                    'nLogCode' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXshDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Success Add Document.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'บันทึกใบมัดจำ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataWhere['FTXshDocNo'],
                'tEventName' => 'บันทึกใบมัดจำ',
                'nLogCode' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    // Function: Edit Document 
    // Parameters: Ajex Event Add Document
    // Creator: 15/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Add Document
    // ReturnType: Object
    public function FSoCDPSEditEventDoc() {
        try {
            $aDataDocument      = $this->input->post();
            $tDPSAutoGenCode    = (isset($aDataDocument['ocbDPSStaAutoGenCode'])) ? 1 : 0;
            $tDPSDocNo          = (isset($aDataDocument['oetDPSDocNo'])) ? $aDataDocument['oetDPSDocNo'] : '';
            $tSODocDate         = $aDataDocument['oetSODocDate'] . " " . $aDataDocument['oetSODocTime'];
            $tDPSStaDocAct      = (isset($aDataDocument['ocbDPSFrmInfoOthStaDocAct'])) ? 1 : 0;
            $tDPSVATInOrEx      = $aDataDocument['ocmVatInOrEx'];
            $tDPSStaRef         = $aDataDocument['ocmDPSFrmInfoOthRef'];
            $tDPSSessionID      = $this->input->post('ohdSesSessionID');
            $tFTXshRefExt       = $this->input->post('oetDPSRefExt');
            $tFDXshRefExtDate   = $this->input->post('oetDPSRefExtDate');
            $tFTXshRefInt       = $this->input->post('oetDPSRefInt');
            $tFDXshRefIntDate   = $this->input->post('oetDPSRefIntDate');
            $tRefin             = $this->input->post("oetDPSRefInAllName");
            $tRefinOld          = $this->input->post("oetDPSRefInt");

            //--------------------------------------------------------------------
            $aCalcDTParams      = [
                'tBchCode'          => $aDataDocument['ohdDPSBchCode'],
                'tDataDocEvnCall'   => '',
                'tDataVatInOrEx'    => $tDPSVATInOrEx,
                'tDataDocNo'        => $tDPSDocNo,
                'tDataDocKey'       => 'TARTRcvDepositHD',
                'tDataSeqNo'        => ''
            ];
            $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
            //-----------------------------------------------------------------------------

            $aCalcDTParams = [
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tDPSVATInOrEx,
                'tDataDocNo'        => $tDPSDocNo,
                'tDataDocKey'       => 'TARTRcvDepositHD',
                'tDataSeqNo'        => ''
            ];

            $aCalDTTempParams = [
                'tDocNo'            => $tDPSDocNo,
                'tBchCode'          => $aDataDocument['ohdDPSBchCode'],
                'tSessionID'        => $tDPSSessionID,
                'tDocKey'           => 'TARTRcvDepositHD',
                'tDataVatInOrEx'    => $tDPSVATInOrEx,
            ];

            $this->Deposit_Model->FSaMDPSCalVatLastDT($aCalDTTempParams);
            $aCalDTTempForHD = $this->FSaCDPSCalDTTempForHD($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TARTRcvDepositHD',
                'tTableHDCst'   => 'TARTRcvDepositHDCst',
                'tTableDT'      => 'TARTRcvDepositDT',
                'tTableStaGen'  => 1,
            );

            //array Data Customer 
            $aDataCustomer = array(
                'FTXshCardID'   => $aDataDocument['oetPanel_CustomerTaxID'],
                'FTXshCstName'  => $aDataDocument['oetPanel_CustomerName'],
                'FTXshCstTel'   =>$aDataDocument['oetPanel_CustomerTelephone'],
                'FTCstCode'     => $aDataDocument['oetSOFrmCstCode'],
                'FNXshAddrTax'  => $aDataDocument['oetPanel_CustomerAddress'],
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdDPSBchCode'],
                'FTXshDocNo'        => $tDPSDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->input->post('ohdSOUsrCode'),
                'FTLastUpdBy'       => $this->input->post('ohdSOUsrCode'),
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tDPSVATInOrEx,
                'FTVatCode'         => $this->input->post('ohdDPSVatCode'),
                'FTVatRate'         => $this->input->post('ohdDPSVatRate'),
            );

            $nDocType = $this->Deposit_Model->FSnMDPSGetDocType();
            // Array Data HD Master
            $aDataMaster = array(
                'FTCstCode' => $aDataDocument['oetSOFrmCstCode'],
                'FTXshRefExt' => $tFTXshRefExt,
                'FTXshRefInt' => $tRefin,
                'FTShfCode' => '',
                'FTSpnCode' => $aDataDocument['ohdSOUsrCode'],
                'FNXshDocType' => $nDocType['FNSdtDocType'] ,
                'FDXshDocDate' => (!empty($tSODocDate)) ? $tSODocDate : NULL,
                'FTXshCshOrCrd' => $aDataDocument['ocmTQPaymentType'],
                'FTXshVATInOrEx' => $tDPSVATInOrEx,
                'FTDptCode' => $aDataDocument['ohdSODptCode'],
                'FTUsrCode' => $aDataDocument['ohdSOUsrCode'],
                'FDXshRefExtDate' => (!empty($aDataDocument['oetDPSRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDPSRefExtDate'])) : NULL,
                'FDXshRefIntDate' => (!empty($aDataDocument['oetDPSRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetDPSRefIntDate'])) : NULL,
                'FTRteCode' => $aDataDocument['ohdDPSRateCode'],
                'FCXshRteFac' => $aDataDocument['ohdSORteFac'],
                'FCXshTotal' => $aCalDTTempForHD['FCXshTotal'],
                'FCXshTotalNV' => $aCalDTTempForHD['FCXshTotalNV'],
                'FCXshTotalNoDis' => $aCalDTTempForHD['FCXshTotalNoDis'],
                'FCXshAmtV' => $aCalDTTempForHD['FCXshAmtV'],
                'FCXshAmtNV' => $aCalDTTempForHD['FCXshAmtNV'],
                'FCXshVat' => $aCalDTTempForHD['FCXshVat'],
                'FCXshVatable' => $aCalDTTempForHD['FCXshVatable'],
                'FCXshGrand' => $aCalDTTempForHD['FCXshGrand'],
                'FCXshRnd' => $aCalDTTempForHD['FCXshRnd'],
                'FTXshGndText' => $aCalDTTempForHD['FTXshGndText'],
                'FTXshStaRefund' => $aDataDocument['ohdSOStaRefund'],
                'FTXshStaDoc' => $aDataDocument['ohdDPSStaDoc'],
                'FTXshStaApv' => !empty($aDataDocument['ohdDPSStaApv']) ? $aDataDocument['ohdDPSStaApv'] : NULL,
                'FTXshApvCode' => !empty($aDataDocument['ohdDPSApvCode']) ? $aDataDocument['ohdDPSApvCode'] : NULL,
                'FTXshStaPaid'  => $aDataDocument['ohdDPSStaPaid'],
                'FNXshStaDocAct' => $tDPSStaDocAct,
                'FTXshRmk'      => $aDataDocument['oetDPSHdRemark'],
                'FNXshStaRef' => $tDPSStaRef,
            );
            // Array Data HD Supplier date('Y-m-d H:i:s', $old_date_timestamp);

            $this->db->trans_begin();

            // Add Update Document HD
            $this->Deposit_Model->FSxMDPSAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // Add Update HD Cst
            $this->Deposit_Model->FSxMDPSAddUpdateHDCst($aDataCustomer,$aDataWhere, $aTableAddUpdate);

            // Update Doc No Into Doc Temp
            $this->Deposit_Model->FSxMDPSAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->Deposit_Model->FSaMDPSMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            if($tRefin == ""){
                $nStaRef = '0';
                $this->Deposit_Model->FSaMDPSClearRefDoc($tRefinOld, $nStaRef, $tDPSDocNo);
            }

            if($this->input->post('ocmDPSSelectBrowse') == '1' && $tRefin != $tRefinOld){
                $nStaRef = '0';
                $this->Deposit_Model->FSaMDPSClearRefDoc($tRefinOld, $nStaRef, $tDPSDocNo);
                
                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSqHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }

            if($this->input->post('ocmDPSSelectBrowse') == '0' && $tRefin != $tRefinOld){
                $nStaRef    = '0';
                $this->Deposit_Model->FSaMDPSClearRefDoc($tRefinOld, $nStaRef, $tDPSDocNo);

                $tRefInDocNo    = $tRefin;
                $tRefInTable    = 'TARTSoHD';
                $nStaRef = '2';
                $aDocWhere = array(
                    'tDocWhere' => 'FTXshDocNo',
                    'tStaTable' => 'FNXshStaRef',
                );
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRef($tRefInDocNo, $nStaRef, $tRefInTable, $aDocWhere);
            }

            if($this->input->post('ocmDPSSelectBrowse') == '2' && $tRefin != $tRefinOld){
                $aDataInsertRef = [
                    'FTAgnCode'         => '',
                    'FTBchCode'         => $aDataDocument['ohdDPSBchCode'],
                    'FTPchDocNoOld'     => $tRefinOld,
                    'FTPchDocNo'        => $tRefin,
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataWhere['FTXshDocNo'],
                    'FTXshRefKey'       => 'DPS',
                    'FDXshRefDocDate'   => date('Y-m-d H:i:s'),
                ];
                // Delet เอกสารใบเครมเก่าออกจากตาราง TCNTPdtClaimHDDocRef
                $this->Deposit_Model->FSaMDPSDelRefInStaRefCLMOld($aDataInsertRef);
                // ADD/Update ใบเครมสินค้า
                $this->Deposit_Model->FSaMDPSUpdateRefInStaRefCLM($aDataInsertRef);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบมัดจำ',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXshDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Success Edit Document.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบมัดจำ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataWhere['FTXshDocNo'],
                'tEventName' => 'แก้ไขและบันทึกใบมัดจำ',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 
        echo json_encode($aReturnData);
    }

    // =================================================================================== Cancel / Approve / Print  ===================================================================================
    // Function: Cancel Document
    // Parameters: Ajex Event Add Document
    // Creator: 08/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Cancel Document
    // ReturnType: Object
    public function FSvCDPSCancelDocument() {
        $tDPSDocNo = $this->input->post('ptDPSDocNo');
        try {
            $tRefInDocNo = $this->input->post('ptRefInDocNo');
            $aDataUpdate = array(
                'tDocNo' => $tDPSDocNo,
            );
            $nStaRef = '0';
            $this->Deposit_Model->FSaMDPSClearRefDoc($tRefInDocNo, $nStaRef,$tDPSDocNo);

            $aStaApv = $this->Deposit_Model->FSaMDPSCancelDocument($aDataUpdate);
            $aReturnData = $aStaApv;

            //success
            if ($aStaApv['nStaEvent'] == 1) {
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg'     => $aStaApv['tStaMessg'],
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDPSDocNo,
                    'tEventName'    => 'ยกเลิกใบมัดจำ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            }else{
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg'     => $aStaApv['tStaMessg'],
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tDPSDocNo,
                    'tEventName'    => 'ยกเลิกใบมัดจำ',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDPSDocNo,
                'tEventName'    => 'ยกเลิกใบมัดจำ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 
        echo json_encode($aReturnData);
    }


    // Function: Approve Document
    // Parameters: Ajex Event Add Document
    // Creator: 09/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Cancel Document
    // ReturnType: Object
    public function FSvCDPSApproveDocument() {
        $tDPSDocNo = $this->input->post('ptDPSDocNo');
        $tDPSBchCode = $this->input->post('ptDPSBchCode');
        $tDPSStaApv = $this->input->post('ptDPSStaApv');
        $tSOtiemNotApr = json_decode($this->input->post('thdSOtiemNotApr'), true);

        $aDataDelObj = array(
            'tSOtiemNotApr' => $tSOtiemNotApr,
            'tDocNo'        => $tDPSDocNo
        );

        $tApvCode = $this->session->userdata('tSesUsername');
        if (empty($tApvCode) && $tApvCode == '') {
            $tApvCode = get_cookie('tUsrCode');
        }

        try {
            $aDataUpdate = array(
                'tDocNo'      => $tDPSDocNo,
                'tApvCode'    => $this->session->userdata('tSesUsername'),
                'tTableDocHD' => 'TARTRcvDepositHD',
                'tBchCode'    => $tDPSBchCode
            );
                //do some this for normal aprove 
            $aDataUpdate['nStaApv']=1;//success จบเลย
            $aStaApv = $this->Deposit_Model->FSaMDPSApproveDocument($aDataUpdate);

            $aReturn = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Success Document Approve.',
                'tLogType'      => 'INFO',
                'tDocNo'        => $tDPSDocNo,
                'tEventName'    => 'อนุมัติใบมัดจำ',
                'nLogCode'      => '001',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => $tApvCode
            );
        } catch (ErrorException $err) {
            $aReturn = array(
                'nStaEvent' => '900',
                'tStaMessg' => language('common/main/main', 'tApproveFail'),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDPSDocNo,
                'tEventName'    => 'อนุมัติใบมัดจำ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $tApvCode
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn); 
        echo json_encode($aReturn);
        return;
    }

    // Function: Approve Document
    // Parameters: Ajex Event Add Document
    // Creator: 09/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Cancel Document
    // ReturnType: Object
    public function FSvCDPSApprovePaidDocument() {
        $tDPSDocNo = $this->input->post('ptDPSDocNo');
        $tDPSBchCode = $this->input->post('ptDPSBchCode');
        $tDPSStaApv = $this->input->post('ptDPSStaApv');
        $tSOtiemNotApr = json_decode($this->input->post('thdSOtiemNotApr'), true);

        $aDataDelObj = array(
            'tSOtiemNotApr' => $tSOtiemNotApr,
            'tDocNo'        => $tDPSDocNo
        );

        try {
            $aDataUpdate = array(
                'tDocNo'      => $tDPSDocNo,
                'tApvCode'    => $this->session->userdata('tSesUsername'),
                'tTableDocHD' => 'TARTRcvDepositHD',
                'tBchCode'    => $tDPSBchCode
            );
                //do some this for normal aprove 
         $aDataUpdate['nStaApv']=1;//success จบเลย
            $aStaApv = $this->Deposit_Model->FSaMDPSApprovePaidDocument($aDataUpdate);
            $aReturn = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Success Document Paid.',
                'tLogType'      => 'INFO',
                'tDocNo'        => $tDPSDocNo,
                'tEventName'    => 'คืนมัดจำ',
                'nLogCode'      => '001',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => ''
            );
        } catch (ErrorException $err) {
            // $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent' => '900',
                'tStaMessg' => language('common/main/main', 'tPaidFail'),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDPSDocNo,
                'tEventName'    => 'คืนมัดจำ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aReturn); 
        echo json_encode($aReturn);
        return;
    }

    // Function: Clear Data In DocTemp
    // Parameters: Ajex Event Add Document
    // Creator: 02/07/2021 Off
    // LastUpdate: -
    // Return: Object Status Clear Data In Document Temp
    // ReturnType: Object
    public function FSoCDPSClearDataInDocTemp() {
        try {
            $this->db->trans_begin();

            // Clear Data Product IN Doc Temp
            $aWhereClearTemp = [
                'FTXthDocNo' => $this->input->post('ptSODocNo'),
                'FTXthDocKey' => 'TARTSoHD',
                'FTSessionID' => $this->session->userdata('tSesSessionID')
            ];
            $this->Deposit_Model->FSxMDPSClearDataInDocTemp($aWhereClearTemp);

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

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDPSCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        $this->load->view('document/depositdoc/refintdocument/wDepositRefDoc', $aDataParam);
    }

    // แสดงตารางรายการเอกสารอ้างอิงภายใน
    public function FSoCDPSCallRefIntDocDataTable(){
        $nPage                  = $this->input->post('nDPSRefIntPageCurrent');
        $tDPSRefIntBchCode      = $this->input->post('tDPSRefIntBchCode');
        $tDPSRefIntDocNo        = $this->input->post('tDPSRefIntDocNo');
        $tDPSRefIntDocDateFrm   = $this->input->post('tDPSRefIntDocDateFrm');
        $tDPSRefIntDocDateTo    = $this->input->post('tDPSRefIntDocDateTo');
        $tDPSRefIntStaDoc       = $this->input->post('tDPSRefIntStaDoc');
        $tDPSTypeRef            = $this->input->post('tDPSTypeRef');
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nDPSRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tDPSRefIntBchCode'     => $tDPSRefIntBchCode,
            'tDPSRefIntDocNo'       => $tDPSRefIntDocNo,
            'tDPSRefIntDocDateFrm'  => $tDPSRefIntDocDateFrm,
            'tDPSRefIntDocDateTo'   => $tDPSRefIntDocDateTo,
            'tDPSRefIntStaDoc'      => $tDPSRefIntStaDoc,
        );
        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $aDataParamFilter
        );

        // Check Type Call Document Ref
        switch($tDPSTypeRef){
            case '0':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDataTable($aDataCondition);
            break;
            case '1':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDataTableQT($aDataCondition);
            break;
            case '2':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDataTableCLM($aDataCondition);
            break;
        }

        $aConfigView    = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );
        $this->load->view('document/depositdoc/refintdocument/wDepositRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCDPSCallRefIntDocDetailDataTable(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $tDPSTypeRef        = $this->input->post('tDPSTypeRef');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition     = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        // Check Type Call Document Ref
        switch($tDPSTypeRef){
            case '0':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDTDataTable($aDataCondition);
            break;
            case '1':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDTDataTableQT($aDataCondition);
            break;
            case '2':
                $aDataParam = $this->Deposit_Model->FSoMDPSCallRefIntDocDTDataTableCLM($aDataCondition);
            break;
        }

        $aConfigView = array(
            'aDataList' => $aDataParam,
            'nOptDecimalShow' => $nOptDecimalShow
            );
        $this->load->view('document/depositdoc/refintdocument/wDepositRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCDPSCallRefIntDocInsertDTToTemp(){
        $tDPSDocNo              =  $this->input->post('tDPSDocNo');
        $tDPSFrmBchCode         =  $this->input->post('tDPSFrmBchCode');
        $tRefIntDocNo           =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode         =  $this->input->post('tRefIntBchCode');
        $aSeqNo                 =  $this->input->post('aSeqNo');
        $nFlag                  =  $this->input->post('tnFlg');
        $nCheckRefBrowse        =  $this->input->post('nCheckRefBrowse');
        $aDataParam = array(
            'tDPSDocNo'       => $tDPSDocNo,
            'tDPSFrmBchCode'  => $tDPSFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
            'nFlag'          => $nFlag
        );
        // Check Type Ref [ เช็คเอกสารอ้างอิงภายใน ]
        switch($nCheckRefBrowse){
            case '0':
                $aDataResult    = $this->Deposit_Model->FSoMDPSCallRefIntDocInsertDTToTempSO($aDataParam);
            break;
            case '1':
                $aDataResult    = $this->Deposit_Model->FSoMDPSCallRefIntDocInsertDTToTemp($aDataParam);
            break;
            case '2':
                $aDataResult    = $this->Deposit_Model->FSoMDPSCallRefIntDocInsertDTToTempCLM($aDataParam);
                // Calcurate DT
                $aWhereHelperCalcDTTemp = array(
                    'tDataDocEvnCall'   => "",
                    'tDataVatInOrEx'    => 1,
                    'tDataDocNo'        => '',
                    'tDataDocKey'       => 'TARTRcvDepositHD',
                    'tDataSeqNo'        => ''
                );
                FCNbHCallCalcDocDTTemp($aWhereHelperCalcDTTemp);
            break;
        }
        return  $aDataResult;
    }




    // ค้นหาข้อมูลรายละเอียดข้อมูลลูกค้า
    public function FSoCDPSFindCstBehideRefIn(){
        $aDataWhereCst  = [
            'FNLngID'   => FCNaHGetLangEdit(),
            'FTCstCode' => $this->input->post('tcstcode')
        ];
        $aDataCstCode   = $this->Deposit_Model->FSoMDPSFindCstBehideRefIn($aDataWhereCst);
        echo json_encode($aDataCstCode);
    }




}



