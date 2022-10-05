<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Qasgroup_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('service/qasgroup/Qasgroup_model');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nQGPBrowseType,$tQGPBrowseOption){
        $nMsgResp = array('title'=>"QasSubGroup");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('masQGPView/0/0'); 
        $aAlwEventQasGroup	    = FCNaHCheckAlwFunc('masQGPView/0/0');
        $this->load->view ( 'service/qasgroup/wQasGroup', array (
            'nMsgResp'              =>$nMsgResp,
            'vBtnSave'              =>$vBtnSave,
            'nQGPBrowseType'        =>$nQGPBrowseType,
            'tQGPBrowseOption'      =>$tQGPBrowseOption,
            'aAlwEventQasGroup'     =>$aAlwEventQasGroup
        ));
    }

    //Functionality : Function Call Page QasSubGroup List
    //Parameters : Ajax jQasSubGroup()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQGPListPage(){
        $aAlwEventQasGroup	= FCNaHCheckAlwFunc('masQGPView/0/0');
        $aNewData  		        = array( 'aAlwEventQasGroup' => $aAlwEventQasGroup);
        $this->load->view('service/qasgroup/wQasGroupList',$aNewData);
    }

    //Functionality : Function Call DataTables QasSubGroup List
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQGPDataList(){
        $nPage  = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}

        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
	    $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
        );

        $tAPIReq    = "";
        $tMethodReq = "GET";
        $aResList   = $this->Qasgroup_model->FSaMQGPList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent = FCNaHCheckAlwFunc('masQGPView/0/0'); //Controle Event

        $aGenTable  = array(
            'aAlwEventQasGroup' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('service/qasgroup/wQasGroupDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQGPAddPage(){
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        

        $aDataAdd = array(
            'aResult'   => array('rtCode'=>'99'),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        );

        $this->load->view('service/qasgroup/wQasGroupAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQGPEditPage(){
        $tQGPCode       = $this->input->post('tQGPCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTQgpCode' => $tQGPCode,
            'FNLngID'   => $nLangEdit
        );


        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCldData       = $this->Qasgroup_model->FSaMGPGSearchByID($tAPIReq,$tMethodReq,$aData);
        $aDataEdit = array(
            'aResult'   => $aCldData,
        );
        $this->load->view('service/qasgroup/wQasGroupAdd',$aDataEdit);
    }

    //Functionality : Event Add QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCQGPAddEvent(){
        try{
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbQasGroupAutoGenCode'),
                'FTQgpCode'             => $this->input->post('oetQGPCode'),
                'FTQgpName'             => $this->input->post('oetQGPName'),
                'FTQgpRmk'              => $this->input->post('otaQGPRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetQGPAgnCode'),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){ 
                $aStoreParam = array(
                    "tTblName"   => 'TCNMQasGrp',                           
                    "tDocType"   => 0,                                          
                    "tBchCode"   => "",                                 
                    "tShpCode"   => "",                               
                    "tPosCode"   => "",                     
                    "dDocDate"   => date("Y-m-d")       
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTQgpCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup  = $this->Qasgroup_model->FSoMQGPCheckDuplicate($aDataMaster['FTQgpCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaQGPMaster  = $this->Qasgroup_model->FSaMQGPAddUpdateMaster($aDataMaster);
                $aStaQGPLang    = $this->Qasgroup_model->FSaMQGPAddUpdateLang($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTQgpCode'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCQGPEditEvent(){
        try{
            $aDataMaster    = array(
                'FTQgpCode'             => $this->input->post('oetQGPCode'),
                'FTQgpName'             => $this->input->post('oetQGPName'),
                'FTQgpRmk'              => $this->input->post('otaQGPRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetQGPAgnCode'),
            );
            $this->db->trans_begin();
            $aStaQGPMaster  = $this->Qasgroup_model->FSaMQGPAddUpdateMaster($aDataMaster);
            $aStaQGPLang    = $this->Qasgroup_model->FSaMQGPAddUpdateLang($aDataMaster);

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
                    'tCodeReturn'	=> $aDataMaster['FTQgpCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
        
    }

    //Functionality : Event Delete QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCQGPDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTQgpCode' => $tIDCode
        );
        $tAPIReq        = 'API/service/QasSubGroup/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Qasgroup_model->FSnMQGPDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Qasgroup_model->FSnMQGPGetAllNumRow();

        if($nNumRowCldLoc !== false){
            $aReturn    = array(
                'nStaEvent'     => $aCldDel['rtCode'],
                'tStaMessg'     => $aCldDel['rtDesc'],
                'nNumRowCldLoc' => $nNumRowCldLoc
            );
            echo json_encode($aReturn);
        }else{
            echo "database error";
        }
    }
}
