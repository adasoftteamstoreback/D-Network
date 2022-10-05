<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Qassubgroup_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('service/qassubgroup/Qassubgroup_model');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nQSGBrowseType,$tQSGBrowseOption){
        $nMsgResp = array('title'=>"QasSubGroup");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('masQSGView/0/0');
        $aAlwEventQasSubGroup	= FCNaHCheckAlwFunc('masQSGView/0/0');
        $this->load->view ( 'service/qassubgroup/wQasSubGroup', array (
            'nMsgResp'              =>$nMsgResp,
            'vBtnSave'              =>$vBtnSave,
            'nQSGBrowseType'        =>$nQSGBrowseType,
            'tQSGBrowseOption'      =>$tQSGBrowseOption,
            'aAlwEventQasSubGroup'   =>$aAlwEventQasSubGroup
        ));
    }

    //Functionality : Function Call Page QasSubGroup List
    //Parameters : Ajax jQasSubGroup()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQSGListPage(){
        $aAlwEventQasSubGroup	= FCNaHCheckAlwFunc('masQSGView/0/0');
        $aNewData  		        = array( 'aAlwEventQasSubGroup' => $aAlwEventQasSubGroup);
        $this->load->view('service/qassubgroup/wQasSubGroupList',$aNewData);
    }

    //Functionality : Function Call DataTables QasSubGroup List
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQSGDataList(){
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
        $aResList   = $this->Qassubgroup_model->FSaMQSGList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent = FCNaHCheckAlwFunc('masQSGView/0/0'); //Controle Event

        $aGenTable  = array(
            'aAlwEventQasSubGroup' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('service/qassubgroup/wQasSubGroupDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQSGAddPage(){
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

        $this->load->view('service/qassubgroup/wQasSubGroupAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQSGEditPage(){
        $tQSGCode       = $this->input->post('tQSGCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTQSGCode' => $tQSGCode,
            'FNLngID'   => $nLangEdit
        );


        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCldData       = $this->Qassubgroup_model->FSaMQSGSearchByID($tAPIReq,$tMethodReq,$aData);

        $aDataEdit = array(
            'aResult'   => $aCldData,
        );
        $this->load->view('service/qassubgroup/wQasSubGroupAdd',$aDataEdit);
    }

    //Functionality : Event Add QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCQSGAddEvent(){
        try{
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbQasSubGroupAutoGenCode'),
                'FTQsgCode'             => $this->input->post('oetQSGCode'),
                'FTQsgName'             => $this->input->post('oetQSGName'),
                'FTQsgRmk'              => $this->input->post('otaQSGRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetQSGAgnCode'),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){
                $aStoreParam = array(
                    "tTblName"   => 'TCNMQasSubGrp',
                    "tDocType"   => 0,
                    "tBchCode"   => "",
                    "tShpCode"   => "",
                    "tPosCode"   => "",
                    "dDocDate"   => date("Y-m-d")
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTQsgCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup  = $this->Qassubgroup_model->FSoMQSGCheckDuplicate($aDataMaster['FTQsgCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaQSGMaster  = $this->Qassubgroup_model->FSaMQSGAddUpdateMaster($aDataMaster);
                $aStaQSGLang    = $this->Qassubgroup_model->FSaMQSGAddUpdateLang($aDataMaster);
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
                        'tCodeReturn'	=> $aDataMaster['FTQsgCode'],
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
    public function FSaCQSGEditEvent(){
        try{
            $aDataMaster    = array(
                'FTQsgCode'             => $this->input->post('oetQSGCode'),
                'FTQsgName'             => $this->input->post('oetQSGName'),
                'FTQsgRmk'              => $this->input->post('otaQSGRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetQSGAgnCode'),
            );
            $this->db->trans_begin();
            $aStaQSGMaster  = $this->Qassubgroup_model->FSaMQSGAddUpdateMaster($aDataMaster);
            $aStaQSGLang    = $this->Qassubgroup_model->FSaMQSGAddUpdateLang($aDataMaster);

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
                    'tCodeReturn'	=> $aDataMaster['FTQsgCode'],
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
    public function FSaCQSGDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTQsgCode' => $tIDCode
        );
        $tAPIReq        = 'API/service/QasSubGroup/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Qassubgroup_model->FSnMQSGDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Qassubgroup_model->FSnMQSGGetAllNumRow();

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
