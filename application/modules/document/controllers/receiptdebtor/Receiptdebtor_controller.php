<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');
require_once(APPPATH . 'config/rabbitmq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Receiptdebtor_controller extends MX_Controller {

    public $aPermission = [];

    public function __construct(){
        parent::__construct();
        $this->load->model('document/receiptdebtor/Receiptdebtor_model');
    }

    public function index($nBrowseType, $tBrowseOption){
        $aDataConfigView = array(
            'nBrowseType'       => $nBrowseType,
            'tBrowseOption'     => $tBrowseOption,
            'aPermission'       => FCNaHCheckAlwFunc('docRCB/0/0'),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('docRCB/0/0'),
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/receiptdebtor/wReceiptDebtor', $aDataConfigView);
    }

    // เรียกหน้า List

    public function FSvCRCBPageList(){
        $this->load->view('document/receiptdebtor/wReceiptDebtorList',array(
            'aGetChnDelivery' => ''/*FCNaGetChnDelivery()*/
        ));
    }

    // เรียกหน้า DataTable

    public function FSvCRCBPageDataTable(){
        $nPage              = $this->input->post('pnPageCurrent');
        $aSearchList        = $this->input->post('paSearchList');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $nLangEdit          = $this->session->userdata("tLangEdit");

        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        }

        $aDataSearch = array(
            'aSearchList'   => $aSearchList,
            'FNLngID'       => $nLangEdit,
            'nPage'         => $nPage,
            'nRow'          => 10
        );

        $aResList = $this->Receiptdebtor_model->FSaMRCBDataList($aDataSearch);

        $aGenTable = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docRCB/0/0'),
            'aDataList'         => $aResList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/receiptdebtor/wReceiptDebtorDataTable', $aGenTable);
        // $aReturnData = array(
        //     'tViewDataTable'    => $this->load->view('document/transferreceiptOut/wTransferreceiptOutDataTable', $aGenTable, true),
        //     'nStaEvent'         => '1',
        //     'tStaMessg'         => 'Success'
        // );
        // echo json_encode($aReturnData);
    }

    // เรียกหน้า Edit/View
    // Create By: Napat(Jame) 05/07/2021
    public function FSvCRCBPageEdit(){
        try {
            $tDocNo = $this->input->post('ptDocNo');

            // // Clear Data In Doc DT Temp
            // $aWhereTemp = [
            //     'tDocKey'       => 'TPSTSalHD',
            //     'FTSessionID'   => $this->session->userdata('tSesSessionID')
            // ];
            // $this->Receiptdebtor_model->FSaMRCBEventClearPdtSNTmp($aWhereTemp);

            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Array Data Where Get
            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nLngID'       => $nLangEdit
            );

            // Get Data Document HD
            $aDataDocHD = $this->Receiptdebtor_model->FSaMRCBEventGetDataDocHD($aDataWhere);
            if( $aDataDocHD['tCode'] == '1' ){
                $aDataCst = $this->Receiptdebtor_model->FSaMRCBEventGetDataCstHD($aDataWhere);
                $aDataDT = $this->Receiptdebtor_model->FSaMRCBEventGetDataDT($aDataWhere);
                $aDataHDDocRef = $this->Receiptdebtor_model->FSaMRCBEventGetHDDocRef($aDataWhere);
                $aDataDocRC = $this->Receiptdebtor_model->FSaMRCBEventGeDocRC($aDataWhere);
                if (isset($aDataCst['aItems'])) {
                  $aDataCst = $aDataCst['aItems'];
                }else {
                  $aDataCst =  array();
                }
                $aPackData = array(
                    'aDataDocHD'        => $aDataDocHD['aItems'],
                    'aDataCst'          => $aDataCst,
                    'aDataDT'           => $aDataDT,
                    'aDataDocRC'        => $aDataDocRC,
                    'aDataDocRef'       => $aDataHDDocRef,
                    'nOptDecimalShow'   => $nOptDecimalShow,
                );

                $aReturnData = array(
                    'tViewPageAdd'   => $this->load->view('document/receiptdebtor/wReceiptDebtorPageAdd', $aPackData, true),
                    // 'nXshDocType'    => $aDataDocHD['aItems']['FNXshDocType'],

                    // // ABB
                    // 'tXshStaPrcDoc'  => $aDataDocHD['aItems']['FTXshStaPrcDoc'],
                    // 'tXshRefTax'     => $aDataDocHD['aItems']['FTXshRefTax'],
                    // 'tXshStaETax'    => $aDataDocHD['aItems']['FTXshStaETax'],
                    // 'tXshETaxStatus' => $aDataDocHD['aItems']['FTXshETaxStatus'],

                    // // Full Tax
                    // 'tXshDocVatFull'        => $aDataDocHD['aItems']['FTXshDocVatFull'],
                    // 'tXshRefTaxFullTax'     => $aDataDocHD['aItems']['FTXshRefTaxFullTax'],
                    // 'tXshStaETaxFullTax'    => $aDataDocHD['aItems']['FTXshStaETaxFullTax'],
                    // 'tXshETaxStatusFullTax' => $aDataDocHD['aItems']['FTXshETaxStatusFullTax'],

                    'nStaEvent'      => '1',
                    'tStaMessg'      => 'Success'
                );

            }else{
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => $aDataDocHD['tDesc']
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
