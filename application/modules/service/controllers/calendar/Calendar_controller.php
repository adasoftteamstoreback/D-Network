<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Calendar_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('service/calendar/Calendar_model');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nCldBrowseType,$tCldBrowseOption){
        $nMsgResp = array('title'=>"Calendar");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('masCLDView/0/0'); 
        $aAlwEventCalendar	= FCNaHCheckAlwFunc('masCLDView/0/0');
        $this->load->view ( 'service/calendar/wCalendar', array (
            'nMsgResp'          =>$nMsgResp,
            'vBtnSave'          =>$vBtnSave,
            'nCldBrowseType'    =>$nCldBrowseType,
            'tCldBrowseOption'  =>$tCldBrowseOption,
            'aAlwEventCalendar'   =>$aAlwEventCalendar
        ));
    }

    //Functionality : Function Call Page Calendar List
    //Parameters : Ajax jCalendar()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCLDListPage(){
        $aAlwEventCalendar	= FCNaHCheckAlwFunc('masCLDView/0/0');
        $aNewData  		    = array( 'aAlwEventCalendar' => $aAlwEventCalendar);
        $this->load->view('service/calendar/wCalendarList',$aNewData);
    }

    //Functionality : Function Call DataTables Calendar List
    //Parameters : Ajax jCalendar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCLDDataList(){
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
        $aResList   = $this->Calendar_model->FSaMCLDList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent  = FCNaHCheckAlwFunc('masCLDView/0/0'); //Controle Event

        $aGenTable  = array(
            'aAlwEventCalendar' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('service/calendar/wCalendarDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCLDAddPage(){
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData          = array(
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataAdd = array(
            'aResult'   => array('rtCode'=>'99'),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        );

        $this->load->view('service/calendar/wCalendarAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCLDEditPage(){
        $tCldCode       = $this->input->post('tCldCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTCldCode' => $tCldCode,
            'FNLngID'   => $nLangEdit
        );


        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCldData       = $this->Calendar_model->FSaMCCLDSearchByID($tAPIReq,$tMethodReq,$aData);
        
        $aDataEdit = array(
            'aResult'   => $aCldData,
        );
        $this->load->view('service/calendar/wCalendarAdd',$aDataEdit);
    }

    //Functionality : Event Add Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCCLDAddEvent(){
        try{
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbCalendarAutoGenCode'),
                'FTSpsCode'             => $this->input->post('oetCldCode'),
                'FTSpsRefCode'          => $this->input->post('oetCldRef'),
                'FTSpsStaUse'            => $this->input->post('ocbCalendarStatus'),
                'FTSpsName'             => $this->input->post('oetCldName'),
                'FTSpsRmk'              => $this->input->post('otaCldRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetCldAgnCode'),
                'FTSpsApvCode'          => $this->input->post('oetCldAvgCode'),
                'FTBchCode'             => $this->input->post('oetCldBchCode'),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){ 
                $aStoreParam = array(
                    "tTblName"   => 'TSVMPos',                           
                    "tDocType"   => 0,                                          
                    "tBchCode"   => "",                                 
                    "tShpCode"   => "",                               
                    "tPosCode"   => "",                     
                    "dDocDate"   => date("Y-m-d")       
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTSpsCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            if(($this->input->post('ocbCalendarStatus') == '')){
                $aDataMaster['FTSpsStaUse']   = '2';
            }
            $oCountDup  = $this->Calendar_model->FSoMCLDCheckDuplicate($aDataMaster['FTSpsCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaCldMaster  = $this->Calendar_model->FSaMCLDAddUpdateMaster($aDataMaster);
                $aStaCldLang    = $this->Calendar_model->FSaMCLDAddUpdateLang($aDataMaster);
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
                        'tCodeReturn'	=> $aDataMaster['FTSpsCode'],
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
    public function FSaCCLDEditEvent(){
        try{
            $aDataMaster    = array(
                'FTSpsCode'             => $this->input->post('oetCldCode'),
                'FTSpsRefCode'          => $this->input->post('oetCldRef'),
                'FTSpsName'             => $this->input->post('oetCldName'),
                'FTSpsStaUse'           => $this->input->post('ocbCalendarStatus'),
                'FTSpsRmk'              => $this->input->post('otaCldRemark'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetCldAgnCode'),
                'FTSpsApvCode'          => $this->input->post('oetCldAvgCode'),
                'FTBchCode'             => $this->input->post('oetCldBchCode'),
            );
            if(($this->input->post('ocbCalendarStatus') == '')){
                $aDataMaster['FTSpsStaUse']   = '2';
            }
            $this->db->trans_begin();
            $aStaCldMaster  = $this->Calendar_model->FSaMCLDAddUpdateMaster($aDataMaster);
            $aStaCldLang    = $this->Calendar_model->FSaMCLDAddUpdateLang($aDataMaster);

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
                    'tCodeReturn'	=> $aDataMaster['FTSpsCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
        
    }

    //Functionality : Event Delete Calendar
    //Parameters : Ajax jCalendar()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCCLDDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        
        $aDataMaster = array(
            'FTSpsCode' => $tIDCode
        );
        $tAPIReq        = 'API/Calendar/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Calendar_model->FSnMCLDDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Calendar_model->FSnMCLDGetAllNumRow();

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


    //Functionality : Function Call DataTables UserCalendar
    //Parameters : Ajax Call View DataTable
    //Creator : 28/05/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCLDUserDataList(){
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nPosCode       = $this->input->post('nPosCode');
            $nLangEdit      = $this->session->userdata("tLangEdit");
            
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'tSearchAll'    => $tSearchAll,
                'nPosCode'      => $nPosCode,
                'FNLngID'       => $nLangEdit
            );
            $aCldUserDataList   = $this->Calendar_model->FSaMCLDUserList($aData); 
            $aGenTable  = array(
                'aCldUserDataList'  => $aCldUserDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll
            );
        $this->load->view('service/calendar/tabuser/wCalendarUserDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage UserCalendar Add
    //Parameters : Ajax Call View Add
    //Creator : 28/05/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCLDUserCalendarAddPage(){
        try{
            $tCldCode           = $this->input->post('tCldCode');
            $aCldUserList       = $this->Calendar_model->FSaMCLDGetCurrentUser($tCldCode); 
            $aDataUserCalendar = array(
                'tCldCode'          => $tCldCode,
                'nStaAddOrEdit'     => 99,
                'aCldUserList'      => $aCldUserList
            );
            $this->load->view('service/calendar/tabuser/wCalendarUserAdd',$aDataUserCalendar);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add UserCalendar
    //Parameters : Ajax Event
    //Creator : 28/05/2021 Off 
    //Update :
    //Return : Status Add Event
    //Return Type : String
    public function FSoCCLDUserCalendarAddEvent(){ 
        $tCldCode           = $this->input->post('ohdObjCalendarCode');
        $FTUsrCode          = $this->input->post('oetAddCodeUserCalendar');
        $FTUsrRemark        = $this->input->post('otaCldUserRemark');
        $FDSpsUsrStart     = $this->input->post('oetCldUserStart');
        $FDSpsUsrExpired    = $this->input->post('oetCldUserFinish');

        $nLastSeq   = $this->Calendar_model->FSaMCLDLastSeqByShwCode($tCldCode);
        $nLastSeq   = $nLastSeq+1;
        $aDataCalendarUser   = array(
                'FTUsrCode'            => $FTUsrCode,
                'FTSpsCode'            => $tCldCode,
                'FNOcuSeq'             => $nLastSeq,
                'FTSpuRemark'          => $FTUsrRemark,
                'FDSpsUsrStart'       => $FDSpsUsrStart,
                'FDSpsUsrExpired'      => $FDSpsUsrExpired
        );
       $nDupPli =  $this->Calendar_model->FSnMCLDCheckDuplicate($aDataCalendarUser['FTSpsCode'],$aDataCalendarUser['FTUsrCode']);

    if($nDupPli==0){
        $this->db->trans_begin();
        $this->Calendar_model->FSaMCLDAddUpdateCalendarUserMaster($aDataCalendarUser);
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess Add UserCalendar Group"
            );
        }else{
            $this->db->trans_commit();
            $aReturn = array(
                'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                'tCodeReturn'	=> $aDataCalendarUser['FTUsrCode'],
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success Add UserCalendar',
                'tCldCode'      => $tCldCode
            );
        }
    }else{
        $aReturn = array(
            'nStaEvent'    => '900',
            'tStaMessg'    => "Unsucess Add Data Duplicate"
        );
    }
        echo json_encode($aReturn);
    }

    //Functionality : Event Delete UserCalendar
    //Parameters : Ajax jUserCalendar()
    //Creator : 31/05/2021 Off
    //Update : 
    //Return : Status Delete Event
    //Return Type : String
    public function FSoCCLDDeleteCalendarUserEvent(){
        $tObjCode   = $this->input->post('ptObjCode');
        $tIDCode    = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTUsrCode' => $tIDCode,
            'FTSpsCode' => $tObjCode
        );
        $aResDel    = $this->Calendar_model->FSaMCLDDelUserCalendarAll($aDataMaster);
        $nNumRowCld = $this->Calendar_model->FSnMCLDGetAllUserCalendarNumRow($tObjCode);
        if($nNumRowCld!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'nNumRowCld' => $nNumRowCld
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }

    //Functionality : Function CallPage CalendarUser Edits
    //Parameters : Ajax Call View Edits
    //Creator : 31/05/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCLDUserCalendarEditPage(){
        try{
            $tUsrCode           = $this->input->post('tUsrCode'); 
            $tCldCode           = $this->input->post('tCldCode');
            $FDSpsUsrStart     = $this->input->post('oetCldUserStart');
            $FDSpsUsrExpired    = $this->input->post('oetCldUserFinish');
            $aCldUserList       = $this->Calendar_model->FSaMCLDGetCurrentUser($tCldCode); 

            $aData  = array(
                'tCldCode'              => $tCldCode,
                'tUsrCode'              => $tUsrCode,
                'FDSpsUsrStart'       => $FDSpsUsrStart,
                'FDSpsUsrExpired'      => $FDSpsUsrExpired
            );
            $aCldData       = $this->Calendar_model->FSaCCLDGetDataByID($aData);
            $aDataUserCalendar   = array(
                'nStaAddOrEdit' => 1,
                'tUsrCode'      => $tUsrCode,
                'aCldData'      => $aCldData,
                'aCldUserList'      => $aCldUserList
            );
            $this->load->view('service/calendar/tabuser/wCalendarUserAdd',$aDataUserCalendar);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit UserCalendar
    //Parameters : Ajax Event
    //Creator : 31/05/2021 Off
    //Update :
    //Return : Status Edit Event
    //Return Type : String
    public function FSoCCLDUserCalendarEditEvent(){ 
        $tUrsCode       = $this->input->post('oetAddCodeUserCalendar');
        $tCldRemark     = $this->input->post('otaCldUserRemark');
        $FTSpsCode      = $this->input->post('ohdObjCalendarCode');
        $FDSpsUsrStart     = $this->input->post('oetCldUserStart');
        $FDSpsUsrExpired    = $this->input->post('oetCldUserFinish');
        $tTmpCode       = $this->input->post('ohdTmpCode');

        $aDataUserCalendarDevice   = array(
                'FTUsrCode'         => $tUrsCode,
                'TTmpCode'          => $tTmpCode,
                'FTSpuRemark'       => $tCldRemark,
                'FTSpsCode'         => $FTSpsCode,
                'FDSpsUsrStart'       => $FDSpsUsrStart,
                'FDSpsUsrExpired'      => $FDSpsUsrExpired,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
        );
        $this->db->trans_begin();
        $this->Calendar_model->FSaMCLDAddUpdateCalendarUserMaster($aDataUserCalendarDevice);
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess UserCalendar Device Group"
            );
        }else{
            $this->db->trans_commit();
            $aReturn = array(
                'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                'tCodeReturn'	=> $aDataUserCalendarDevice['FTUsrCode'],
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success UserCalendar Device Group',
                'tCldCode'      => $FTSpsCode
            );
        }
        echo json_encode($aReturn);
    }

}
