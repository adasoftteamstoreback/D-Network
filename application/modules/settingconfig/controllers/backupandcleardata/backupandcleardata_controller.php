<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class backupandcleardata_controller extends MX_Controller {
    
    public function __construct(){
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('settingconfig/backupandcleardata/backupandcleardata_model');
    }


    public function index($nLimBrowseType, $tLimBrowseOption){

        $nMsgResp   = array('title' => "Settingconperid");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';

        if (!$isXHR) {
            $this->load->view('common/wHeader', $nMsgResp);
            $this->load->view('common/wTopBar', array('nMsgResp' => $nMsgResp));
            $this->load->view('common/wMenu', array('nMsgResp' => $nMsgResp));
        }

        $vBtnSave       = FCNaHBtnSaveActiveHTML('BAC/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventSettconpreiod  = FCNaHCheckAlwFunc('BAC/0/0');

        $this->load->view('settingconfig/backupandcleardata/wBackupAndClearData', array(
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nBrowseType'    => $nLimBrowseType,
            'tBrowseOption'  => $tLimBrowseOption,
            'aAlwEventSettconpreiod' => $aAlwEventSettconpreiod
        ));
    }

    //Functionality : Function Call Page SettingConperiod List
    //Parameters : Ajax and Function Parameter
    //Creator : 07-10-2020 Witsarut (Bell)
    //Return : String View
    //Return Type : View
    public function FSvBACListPage(){
        $aAlwEventSettconpreiod  = FCNaHCheckAlwFunc('BAC/0/0');
        $aNewData     = array('aAlwEventSettconpreiod' => $aAlwEventSettconpreiod);

        $this->load->view('settingconfig/backupandcleardata/wBackupAndClearDataList', $aNewData);
    }


    //Functionality : Function Call View Data SettingConperiod
    //Parameters : Ajax Call View DataTable
    //Creator : 07-10-2020 witsarut 
    //Return : String View
    //Return Type : View
    public function FSvBACDataList(){
        try{
            $aSearchAll = $this->input->post('aSearchAll');
            $nPage      = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit  = $this->session->userdata("tLangEdit");

            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $aSearchAll
            );

            $aDataList = $this->backupandcleardata_model->FSaMBACList($aData);
            $aAlwEventSettconpreiod = FCNaHCheckAlwFunc('BAC/0/0'); //Controle Event


            $aGenTable = array(
                'aDataList'     => $aDataList,
                'nPage'         => $nPage,
                'aAlwEvent'     => $aAlwEventSettconpreiod
              
            );

            $this->load->view('settingconfig/backupandcleardata/wBackupAndClearDataDataTable', $aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function Call EditPage
    //Parameters : Ajax Call View DataTable
    //Creator : 06-08-2022 Off 
    //Return : String View
    //Return Type : View
    public function FSvBACEditPage(){
        $tDocType                   = $this->input->post('tDocType');
        $tPrgKey                   = $this->input->post('tPrgKey');
        $nLangEdit                  = $this->session->userdata("tLangEdit");
        
        $aData  = array(
            'FNPrgDocType'  => $tDocType,
            'FTPrgKey'      => $tPrgKey,
            'FNLngID'       => $nLangEdit
        );

        $aAdvData       = $this->backupandcleardata_model->FSaBACSearchByID($aData);

        $aDataEdit      = array(
            'aResult'               => $aAdvData
        );
        $this->load->view('settingconfig/backupandcleardata/wBackupAndClearDataAdd', $aDataEdit);
    }

    //Functionality : Event Edit BAC
    //Parameters : Ajax jReason()
    //Creator : 06/08/2022 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSvBACEditEvent(){
        try{
            if($this->input->post('ocbUseStandart') !== NULL){
                $tPrgUseStd = '1';
            }else{
                $tPrgUseStd = '2';
            }
            if($this->input->post('ocbPrgStaUse') !== NULL){
                $tPrgStaUse = '1';
            }else{
                $tPrgStaUse = '2';
            }

            if($this->input->post('ocbPrgStaPrg') !== NULL){
                $tPrgStaPrg = '1';
            }else{
                $tPrgStaPrg = '2';
            }

            $aDataMaster    = array(
                'FTPrgKey'          => $this->input->post('ohdBacPrgKey'),
                'FNPrgDocType'      => $this->input->post('ohdBacPrgDocType'),
                'FTPrgStaUse'       => $tPrgStaUse,
                'FTPrgStaPrg'       => $tPrgStaPrg,
                'tPrgUseStd'        => $tPrgUseStd,
                'FNPrgKeep'         => $this->input->post('oetBacKeep'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FNLngID'           => $this->session->userdata("tLangEdit"),
            );
            $this->db->trans_begin();
            $aBacMaster  = $this->backupandcleardata_model->FSaMRSNAddUpdateBAC($aDataMaster);

            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Event"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'	=> $aDataMaster['FTRsnCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
        
    }
}
