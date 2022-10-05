<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Purchasereturnrefpimodel_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('document/purchasereturn/Purchasereturnrefpimodal_model');
    }

    /**
     * Functionality : Function Call Data From PI HD
     * Parameters : Ajax and Function Parameter
     * Creator : 21/06/2019 Piya
     * LastUpdate: -
     * Return : Object View Data Table
     * Return Type : object
     */
    public function FSoCPNRefPIHDList(){
        try{
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $tBranch        = $this->input->post('tBranch');
            $aAlwEvent      = FCNaHCheckAlwFunc('dcmPI/0/0');
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
            $nLangEdit        = $this->session->userdata("tLangEdit");
            
            // Data Conditon Get Data Document
            $aDataCondition  = array(
                'FNLngID'           => $nLangEdit,
                'nPage'             => $nPage,
                'nRow'              => 10,
                'aAdvanceSearch'    => $aAdvanceSearch,
                'tStaApv'           => $this->input->post('ocmStaApv'),
                'tStaRef'           => $this->input->post('ocmStaRef'),
                'tStaDocAct'        => $this->input->post('ocmStaDocAct'),
                'tStaDoc'           => $this->input->post('ocmStaDoc'),
                'tBranch'           => $tBranch
            );
            $aDataList = $this->Purchasereturnrefpimodal_model->FSaMPNGetPIHDList($aDataCondition);
            
            $aConfigView    = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tPIViewDataTableList   = $this->load->view('document/purchasereturn/ref_pi/wPurchasereturnPIHDList', $aConfigView, true);
            
            $aReturnData = array(
                'tPIViewDataTableList'  => $tPIViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }
  
    /**
     * Functionality : Function Call Data From PI DT
     * Parameters : Ajax and Function Parameter
     * Creator : 21/06/2019 Piya
     * LastUpdate: -
     * Return : Object View Data Table
     * Return Type : object
     */
    public function FSoCPNRefPIDTList(){
        try{
            $tDocNo = $this->input->post('tDocNo');
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc('dcmPI/0/0');
            // Get Option Show Decimal
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();
            // Page Current 
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");
            
            // Data Conditon Get Data Document
            $aDataCondition  = array(
                'tDocNo'            => $tDocNo,
                'FNLngID'           => $nLangEdit,
                'nPage'             => $nPage,
                'nRow'              => 1000,
                'aAdvanceSearch'    => $aAdvanceSearch
            );
            $aDataList  = $this->Purchasereturnrefpimodal_model->FSaMPNGetPIDTList($aDataCondition);
            
            $aConfigView    = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tPIViewDataTableList   = $this->load->view('document/purchasereturn/ref_pi/wPurchasereturnPIDTList', $aConfigView, true);
            
            $aReturnData = array(
                'tPIViewDataTableList'  => $tPIViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }
    
}





















































































































































































































































