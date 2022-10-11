<?php

use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

defined('BASEPATH') or exit('No direct script access allowed');

class cSaleOrderData extends MX_Controller {

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('document/saleorderdata/mSaleOrderData');
        parent::__construct();
    }

    public function index($nSOBrowseType, $tSOBrowseOption) {

        $aDataConfigView = array(
            'nSOBrowseType'     => $nSOBrowseType,
            'tSOBrowseOption'   => $tSOBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('dcmSO/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('dcmSO/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/saleorderdata/wSaleOrderData', $aDataConfigView);
    }

    // ฟังก์ชั่นหลัก
    public function FSvCSODFormSearchList() {
        $this->load->view('document/saleorderdata/wSaleOrderDataFormSearchList');
    }

    // ตารางข้อมูล
    public function FSoCSODDataTable() {
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
            $aDataList = $this->mSaleOrderData->FSaMSOGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'nOptDecimalShow' => $nOptDecimalShow,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );

            $tSOViewDataTableList = $this->load->view('document/saleorderdata/wSaleOrderDataDataTable', $aConfigView, true);
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

    // เช็คข้อมูลใบ รับของลูกค้า
    public function FSoCSODChkCRV() {
        try {
            $tDocno     = $this->input->post('tDocno');
            $tUsrCode   = $this->input->post('tUsrCode');
            $ptType     = $this->input->post('ptType');
            $RealDocno = explode("|",$tDocno);
            $RealDocno = $RealDocno[0];
            $aDataList = $this->mSaleOrderData->FSnMSOGetCRVDocData($RealDocno,$tUsrCode,$ptType);
            if($aDataList['tCode'] == '1'){
                $aReturnData = array(
                    'aItems'    => $aDataList,
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            }else{
                $aReturnData = array(
                    'nStaEvent' => '800',
                    'tStaMessg' => 'Not Found'
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

    // เช็คว่า ต้องใส่รหัสไหม
    public function FSoCSODChkByPass() {
        try {
            $tUsrCode   = $this->input->post('tUsrCode');
            $aDataList = $this->mSaleOrderData->FSnMSOChkByPass($tUsrCode);
            if($aDataList['tCode'] == '1'){
                $aReturnData = array(
                    'aItems'    => $aDataList,
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            }else{
                $aReturnData = array(
                    'nStaEvent' => '800',
                    'tStaMessg' => 'Not Found'
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

    // เช็คข้อมูลใบ รับของลูกค้า
    public function FSoCSODCheckUpdateMQ() {
        try {
            $tCkiRdmCode = get_cookie('tCkiRdmCode');
            $aParamQ['tQname']          = 'Q_ORDUser'.$this->session->userdata("tSesUsrBchCodeDefault").$this->session->userdata("tSesUsername").$tCkiRdmCode;
            $aParamQ['nStaBindingEXC']  = '1';
            $aParamQ['tExchangeName']   = 'AR_XReciveOrder';
            $aParamQ['tVhostType']      = 'S';
            $tTaxJsonString             = FCNxRabbitMQGetMassage($aParamQ);
            if( $tTaxJsonString != 'false' ){
                $aGetData                   =  json_decode($tTaxJsonString, true);

                $aGetAllData = $this->mSaleOrderData->FSoMDBRGetAllData($aGetData);

                if($aGetAllData['tCode'] != '800'){
                    $aGetData['tCstName'] = $aGetAllData['aItems']['0']['FTCstName'];
                    $aGetData['rtDocRefDate'] = $aGetAllData['aItems']['0']['FDXshDocDate'];
                    $aReturnData = array(
                        'nStaEvent'    => '1',
                        'tMessage'     => 'มี Q',
                        'aItems'     => $aGetData
                    );
                }else{
                    $aReturnData = array(
                        'nStaEvent'    => '404',
                        'tMessage'     => 'ไม่พบข้อมูล',
                        'tDocno'       => $aGetAllData['tDocno']
                    );
                }                
            }else{
                $aReturnData = array(
                    'nStaEvent'    => '900',
                    'tMessage'     => 'ไม่มี Q Update'
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


     // เช็คข้อมูลใบ รับของลูกค้า
     public function FSoCSODChkUserNameLogin() {
       

        try {
            $tUserName = $this->input->post('tUserName');
            $tPassword = $this->input->post('tUserPass');
            $aDataList = $this->mSaleOrderData->FSnMSOChkAdmin($tUserName,$tPassword);
            if($aDataList['tCode'] == '1'){
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            }else{
                $aReturnData = array(
                    'nStaEvent' => '800',
                    'tStaMessg' => 'Not Found'
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

}



