<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Message_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('service/message/Message_model');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nMsgBrowseType,$tMsgBrowseOption){
        $nMsgResp = array('title'=>"Message");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('masMSGView/0/0'); 
        $aAlwEventMessage	= FCNaHCheckAlwFunc('masMSGView/0/0');
        $this->load->view ( 'service/message/wMessage', array (
            'nMsgResp'          =>$nMsgResp,
            'vBtnSave'          =>$vBtnSave,
            'nMsgBrowseType'    =>$nMsgBrowseType,
            'tMsgBrowseOption'  =>$tMsgBrowseOption,
            'aAlwEventMessage'   =>$aAlwEventMessage
        ));
    }

    //Functionality : Function Call Page Message List
    //Parameters : Ajax jMessage()
    //Creator : 04/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCMSGListPage(){
        $aAlwEventMessage	= FCNaHCheckAlwFunc('masMSGView/0/0');
        $aNewData  		    = array( 'aAlwEventMessage' => $aAlwEventMessage);
        $this->load->view('service/message/wMessageList',$aNewData);
    }

    //Functionality : Function Call DataTables Message List
    //Parameters : Ajax jMessage()
    //Creator : 04/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCMSGDataList(){
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
        $aResList   = $this->Message_model->FSaMMSGList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent = FCNaHCheckAlwFunc('masMSGView/0/0'); //Controle Event

        $aGenTable  = array(
            'aAlwEventMessage' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('service/message/wMessageDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page Message
    //Parameters : Ajax jMessage()
    //Creator : 07/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCMSGAddPage(){
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
            'dGetDataNow'   => date('Y-m-d'),
            'dGetDataFuture' => date('Y-m-d', strtotime('+1 year'))
        );

        $this->load->view('service/message/wMessageAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCMSGEditPage(){
        $tMshCode       = $this->input->post('tMshCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTMshCode' => $tMshCode,
            'FNLngID'   => $nLangEdit
        );


        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCldData       = $this->Message_model->FSaMMSGSearchByID($tAPIReq,$tMethodReq,$aData);
        
        $aDataEdit = array(
            'aResult'   => $aCldData,
        );
        $this->load->view('service/message/wMessageAdd',$aDataEdit);
    }

    //Functionality : Event Add Message
    //Parameters : Ajax jMessage()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCMSGAddEvent(){
        // print_r($this->input->post());
        //     exit();
        try{
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbMessageAutoGenCode'),
                'FTMshCode'             => $this->input->post('oetMsgCode'),
                'FTMshStaActive'        => $this->input->post('ocbMessageStatus'),
                'FTMshName'             => $this->input->post('oetMsgName'),
                'FTMshRmk'              => $this->input->post('otaCldRemark'),
                'FDMshStart'            => $this->input->post('oetMsgStart'),
                'FDMshFinish'           => $this->input->post('oetMsgFinish'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetMsgAgnCode'),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){ 
                $aStoreParam = array(
                    "tTblName"   => 'TCNMMsgHD',                           
                    "tDocType"   => 0,                                          
                    "tBchCode"   => "",                                 
                    "tShpCode"   => "",                               
                    "tPosCode"   => "",                     
                    "dDocDate"   => date("Y-m-d")       
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTMshCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            if(($this->input->post('ocbMessageStatus') == '')){
                $aDataMaster['FTMshStaUse']   = '2';
            }

            $oCountDup  = $this->Message_model->FSoMMSGCheckDuplicate($aDataMaster['FTMshCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaCldMaster  = $this->Message_model->FSaMMSGAddUpdateMaster($aDataMaster);
                $aStaMsgLang    = $this->Message_model->FSaMMSGAddUpdateLang($aDataMaster);
                if(!(FCNnHSizeOf($this->input->post('oetMsgType')) <= 0)){
                    $nIndex = 1;
                    foreach($this->input->post('oetMsgType') as $key => $value){
                        $aDataMaster ['FTMsdType']   = $value;
                        $aDataMaster ['FNMsdSeq']    = $nIndex;
                        
                        if($value == 4){
                            $tBranchImageOld	= trim($this->input->post('oetImgInputPDTDEMOOld'.$key));
                            $tBranchImage		= trim($this->input->post('oetImgInputPDTDEMO'.$key));
                            if($tBranchImage != $tBranchImageOld){
                                $aImageUplode = array(
                                    'tModuleName'       => 'service',
                                    'tImgFolder'        => 'message',
                                    'tImgRefID'         => $aDataMaster['FTMshCode'],
                                    'tImgObj'           => $tBranchImage,
                                    'tImgTable'         => 'TCNMMsgDT',
                                    'tTableInsert'      => 'TCNMImgObj',
                                    'tImgKey'           => 'main',
                                    'dDateTimeOn'       => date('Y-m-d H:i:s'),
                                    'tWhoBy'            => $this->session->userdata('tSesUsername'),
                                    'nStaDelBeforeEdit' => 1
                                );
                                $aImgReturn = FCNnHAddImgObj($aImageUplode);
                                $aDataMaster ['FTMsdValue'] = $aImgReturn['oSuccessApiReturn']['rtData'];
                            }
                        }else{
                            $aDataMaster ['FTMsdValue']  = $this->input->post('oetMsgValue')[$key];
                        }    
                        $aStaMsgDetail    = $this->Message_model->FSaMMSGAddUpdateDetail($aDataMaster);
                        $nIndex++;   
                    }
                }
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
                        'tCodeReturn'	=> $aDataMaster['FTMshCode'],
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

    //Functionality : Event Edit Message
    //Parameters : Ajax jMessage()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCMSGEditEvent(){
        try{
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbMessageAutoGenCode'),
                'FTMshCode'             => $this->input->post('oetMsgCode'),
                'FTMshStaActive'        => $this->input->post('ocbMessageStatus'),
                'FTMshName'             => $this->input->post('oetMsgName'),
                'FTMshRmk'              => $this->input->post('otaCldRemark'),
                'FDMshStart'            => $this->input->post('oetMsgStart'),
                'FDMshFinish'           => $this->input->post('oetMsgFinish'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetMsgAgnCode'),
            );
            if(($this->input->post('ocbMessageStatus') == '')){
                $aDataMaster['FTMshStaActive']   = '2';
            }
            $this->db->trans_begin();
                $aStaCldMaster  = $this->Message_model->FSaMMSGAddUpdateMaster($aDataMaster);
                $aStaMsgLang    = $this->Message_model->FSaMMSGAddUpdateLang($aDataMaster);
                $this->Message_model->FSnMMSGDelDT($aDataMaster);
                if(!(FCNnHSizeOf($this->input->post('oetMsgType')) <= 0)){
                    $nIndex = 1;
                    foreach($this->input->post('oetMsgType') as $key => $value){
                        $aDataMaster ['FTMsdType'] = $value;
                        $aDataMaster ['FNMsdSeq']  = $nIndex;
                        if($value == 4){
                            $tBranchImageOld	= trim($this->input->post('oetImgInputPDTDEMOOld'.$key));
                            $tBranchImage		= trim($this->input->post('oetImgInputPDTDEMO'.$key));
                            if($tBranchImage != $tBranchImageOld){
                                $aImageUplode = array(
                                    'tModuleName'       => 'service',
                                    'tImgFolder'        => 'message',
                                    'tImgRefID'         => $aDataMaster['FTMshCode'],
                                    'tImgObj'           => $tBranchImage,
                                    'tImgTable'         => 'TCNMMsgDT',
                                    'tTableInsert'      => 'TCNMImgObj',
                                    'tImgKey'           => 'main',
                                    'dDateTimeOn'       => date('Y-m-d H:i:s'),
                                    'tWhoBy'            => $this->session->userdata('tSesUsername'),
                                    'nStaDelBeforeEdit' => 1
                                );
                                $aImgReturn = FCNnHAddImgObj($aImageUplode);
                                $aDataMaster ['FTMsdValue'] = $aImgReturn['oSuccessApiReturn']['rtData'];
                            }else{
                                $aDataMaster ['FTMsdValue'] = $this->input->post('oetImgInputPDTDEMOOld'.$key);
                            }
                        }else{
                            $aDataMaster ['FTMsdValue']  = $this->input->post('oetMsgValue')[$key]; 
                        }    
                        $aStaMsgDetail    = $this->Message_model->FSaMMSGAddUpdateDetail($aDataMaster);
                        $nIndex++;   
                    }
                }
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
                    'tCodeReturn'	=> $aDataMaster['FTMshCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
        
    }

    //Functionality : Event Delete Message
    //Parameters : Ajax jMessage()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCMSGDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        
        $aDataMaster = array(
            'FTMshCode' => $tIDCode
        );
        $tAPIReq        = 'API/Message/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Message_model->FSnMMSGDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Message_model->FSnMMSGGetAllNumRow();

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
