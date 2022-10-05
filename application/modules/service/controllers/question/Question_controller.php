<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Question_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('service/question/Question_model');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nQahBrowseType,$tQahBrowseOption){
        $nMsgResp = array('title'=>"Question");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('masQAHView/0/0');
        $aAlwEventQuestion	= FCNaHCheckAlwFunc('masQAHView/0/0');
        $this->load->view ( 'service/question/wQuestion', array (
            'nMsgResp'          =>$nMsgResp,
            'vBtnSave'          =>$vBtnSave,
            'nQahBrowseType'    =>$nQahBrowseType,
            'tQahBrowseOption'  =>$tQahBrowseOption,
            'aAlwEventQuestion'   =>$aAlwEventQuestion
        ));
    }

    //Functionality : Function Call Page Question List
    //Parameters : Ajax jQuestion()
    //Creator : 24/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQAHListPage(){
        $aAlwEventQuestion	= FCNaHCheckAlwFunc('masQAHView/0/0');
        $aNewData  		    = array( 'aAlwEventQuestion' => $aAlwEventQuestion);
        $this->load->view('service/question/wQuestionList',$aNewData);
    }

    //Functionality : Function Call DataTables Question List
    //Parameters : Ajax jQuestion()
    //Creator : 21/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQAHDataList(){
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
        $aResList   = $this->Question_model->FSaMQAHList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent = FCNaHCheckAlwFunc('masQAHView/0/0'); //Controle Event

        $aGenTable  = array(
            'aAlwEventQuestion' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('service/question/wQuestionDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page Questions
    //Parameters : Ajax jQuestions()
    //Creator : 22/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQAHAddPage(){
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

        $this->load->view('service/question/wQuestionAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page Question
    //Parameters : Ajax jQuestion()
    //Creator : 22/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQAHEditPage(){
        $tQahCode       = $this->input->post('tQahCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTQahDocNo' => $tQahCode,
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aQahData       = $this->Question_model->FSaMCQAHSearchByID($tAPIReq,$tMethodReq,$aData);
        $aDataEdit = array(
            'aResult'   => $aQahData,
        );
        $this->load->view('service/question/wQuestionAdd',$aDataEdit);
    }

    //Functionality : Function Call Preview Question
    //Parameters : Ajax jQuestion()
    //Creator : 22/06/2021 Off lnwza
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCQAHPreviewPage(){
        $tQahCode       = $this->input->post('tQahCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTQahDocNo' => $tQahCode,
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq                = "";
        $tMethodReq             = "GET";
        $aQahData               = $this->Question_model->FSaMCQAHSearchByID($tAPIReq,$tMethodReq,$aData);
        $aQagDetailDataList     = $this->Question_model->FSaMQAHDetailListPreview($aData);
        $aDataEdit = array(
            'aResult'              => $aQahData,
            'aQagDetailDataList'   => $aQagDetailDataList,
        );
        // print_r($aDataEdit);
        $this->load->view('service/question/wQuestionPreviewPage',$aDataEdit);
        // echo json_encode($aDataEdit);
    }

    //Functionality : Event Add Question
    //Parameters : Ajax jQuestion()
    //Creator : 22/06/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCQAHAddEvent(){
        try{

            if($this->input->post('ocbQahStaActive')!=''){
                $nQahStaActive = 1;
            }else{
                $nQahStaActive = 2;
            }
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbQuestionAutoGenCode'),
                'FTQahDocNo'            => $this->input->post('oetQahCode'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FDQahDateStart'        => $this->input->post('oetQahStart'),
                'FDQahDateStop'         => $this->input->post('oetQahFinish'),
                'FTQahName'             => $this->input->post('oetQahName'),
                'FTQgpCode'             => $this->input->post('oetQasGroupCode'),
                'FTQsgCode'             => $this->input->post('oetQasSubCode'),
                'FTQahStaActive'        => $nQahStaActive
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){
                $aStoreParam = array(
                    "tTblName"   => 'TCNTQaHD',
                    "tDocType"   => 0,
                    "tBchCode"   => "",
                    "tShpCode"   => "",
                    "tPosCode"   => "",
                    "dDocDate"   => date("Y-m-d")
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTQahDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup  = $this->Question_model->FSoMQAHCheckDuplicate($aDataMaster['FTQahDocNo']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaCldMaster  = $this->Question_model->FSaMQAHAddUpdateMaster($aDataMaster);
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
                        'tCodeReturn'	=> $aDataMaster['FTQahDocNo'],
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

    //Functionality : Event Edit Question
    //Parameters : Ajax jQuestion()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCQAHEditEvent(){
        try{
            if($this->input->post('ocbQahStaActive')!=''){
                $nQahStaActive = 1;
            }else{
                $nQahStaActive = 2;
            }
            $aDataMaster    = array(
                'FTQahDocNo'            => $this->input->post('oetQahCode'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FDQahDateStart'        => $this->input->post('oetQahStart'),
                'FDQahDateStop'         => $this->input->post('oetQahFinish'),
                'FTQahName'             => $this->input->post('oetQahName'),
                'FTQgpCode'             => $this->input->post('oetQasGroupCode'),
                'FTQsgCode'             => $this->input->post('oetQasSubCode'),
                'FTQahStaActive'        => $nQahStaActive
            );
            $this->db->trans_begin();
            $aStaCldMaster  = $this->Question_model->FSaMQAHAddUpdateMaster($aDataMaster);

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
                    'tCodeReturn'	=> $aDataMaster['FTQahDocNo'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Functionality : Event Delete Question
    //Parameters : Ajax jQuestion()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCQAHDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');

        $aDataMaster = array(
            'FTQahDocNo' => $tIDCode
        );
        $tAPIReq        = 'API/Question/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Question_model->FSnMQAHDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Question_model->FSnMQAHGetAllNumRow();

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


    //Functionality : Function Call DataTables QuestionDetail
    //Parameters : Ajax Call View DataTable
    //Creator : 23/06/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCQAHQurstionDetailDataList(){
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nQahCode       = $this->input->post('nQahCode');
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'tSearchAll'    => $tSearchAll,
                'nQahCode'      => $nQahCode,
                'FNLngID'       => $nLangEdit
            );
            $aQagDetailDataList   = $this->Question_model->FSaMQAHDetailList($aData);
            $aGenTable  = array(
                'aQagDetailDataList'  => $aQagDetailDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll
            );
        $this->load->view('service/question/tabdetail/wQuestionDetailDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage QuestionDetail Add
    //Parameters : Ajax Call View Add
    //Creator : 22/06/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCQAHQuestionDetailAddPage(){
        try{
            $tQahCode           = $this->input->post('tQahCode');
            $tQahType           = $this->input->post('tQahType');
            // $aCldUserList       = $this->Question_model->FSaMQAHGetCurrentUser($tQahCode);

            $aDataUserCalendar = array(
                'tQahCode'          => $tQahCode,
                'tQahType'          => $tQahType,
                'nStaAddOrEdit'     => 99,
            );
            $this->load->view('service/question/tabdetail/wQuestionDetailAdd',$aDataUserCalendar);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add QuestionDetail
    //Parameters : Ajax Event
    //Creator : 23/06/2021 Off
    //Update :
    //Return : Status Add Event
    //Return Type : String
    public function FSoCQAHQuestionDetailAddEvent(){

        $tQahCode           = $this->input->post('ohdCheckQahCode');

        $nLastSeq   = $this->Question_model->FSaMQADLastSeqByShwCode($tQahCode);
        $nLastSeq   = $nLastSeq+1;

        $aDataQuestionDetail   = array(
                'FTQahDocNo'            => $this->input->post('ohdCheckQahCode'),
                'FTQadName'             => $this->input->post('oetQadName'),
                'FTQadType'             => $this->input->post('ocmQadType'),
                'FNQadSeqNo'             => $nLastSeq,
                'FTQadStaUse'            => $this->input->post('ocbQuestionStatus')
        );
        if(($this->input->post('ocbQuestionStatus') == '')){
            $aDataQuestionDetail['FTQadStaUse']   = '2';
        }
        $this->db->trans_begin();
        $this->Question_model->FSaMQAHAddUpdateQuestionDetail($aDataQuestionDetail);
        if(!(FCNnHSizeOf($this->input->post('oetQadValue')) <= 0)){
            $nIndex = 1;
            foreach($this->input->post('oetQadValue') as $key => $value){
                $aDataQuestionDetail ['FNQasResuitSeq']    = $nIndex;
                $aDataQuestionDetail ['FNQasResuitName']   = $value;
                $aStaMsgDetail    = $this->Question_model->FSaMQAHAddUpdateDetail($aDataQuestionDetail);
                $nIndex++;
            }
        }
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
                'tCodeReturn'	=> $aDataQuestionDetail['FTQahDocNo'],
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success Add UserCalendar',
                'tQahCode'      => $tQahCode
            );
        }
        echo json_encode($aReturn);
    }

    //Functionality : Event Delete QuestionDetail
    //Parameters : Ajax jQuestionDetail()
    //Creator : 31/05/2021 Off
    //Update :
    //Return : Status Delete Event
    //Return Type : String
    public function FSoCQAHDeleteQuestionDetailEvent(){

        $ptSeqCode   = $this->input->post('ptSeqCode');
        $tIDCode    = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTQahDocNo' => $tIDCode,
            'FNQadSeqNo' => $ptSeqCode
        );
        $aResDel    = $this->Question_model->FSaMQASDelQuestionDetailAll($aDataMaster);
        $nNumRowCld = $this->Question_model->FSnMQASGetAllQuestionDetailNumRow($tIDCode);
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
    public function FSvCQAHQuestionDetailEditPage(){
        try{
            $tQahCode           = $this->input->post('tQahCode');
            $tSeqCode           = $this->input->post('tSeqCode');
            $tQahType           = $this->input->post('tQahType');
            $FDObjDutyStart     = $this->input->post('oetCldUserStart');
            $FDObjDutyFinish    = $this->input->post('oetCldUserFinish');
            // $aCldUserList       = $this->Question_model->FSaMQAHGetCurrentUser($tSeqCode);

            $aData  = array(
                'tSeqCode'              => $tSeqCode,
                'tQahCode'              => $tQahCode,
                'FDObjDutyStart'       => $FDObjDutyStart,
                'FDObjDutyFinish'      => $FDObjDutyFinish
            );
            $aQahData       = $this->Question_model->FSaQADGetDataByID($aData);
            $aDataQuestionDetail   = array(
                'nStaAddOrEdit' => 1,
                'tQahCode'      => $tQahCode,
                'tQahType'      => $tQahType,
                'aQahData'      => $aQahData
            );
            $this->load->view('service/question/tabdetail/wQuestionDetailAdd',$aDataQuestionDetail);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit QuestionDetail
    //Parameters : Ajax Event
    //Creator : 23/06/2021 Off
    //Update :
    //Return : Status Edit Event
    //Return Type : String
    public function FSoCQAHQuestionDetailEditEvent(){
        $tQahCode      = $this->input->post('ohdCheckQahCode');

        $aDataQuestionDetail   = array(
            'FTQahDocNo'            => $this->input->post('ohdCheckQahCode'),
            'FTQadName'             => $this->input->post('oetQadName'),
            'FTQadType'             => $this->input->post('ocmQadType'),
            'FNQadSeqNo'             => $this->input->post('ohdCheckSeqCode'),
            'FTQadStaUse'            => $this->input->post('ocbQuestionStatus')
        );

        if(($this->input->post('ocbQuestionStatus') == '')){
            $aDataQuestionDetail['FTQadStaUse']   = '2';
        }
        $this->db->trans_begin();
        $this->Question_model->FSaMQAHAddUpdateQuestionDetail($aDataQuestionDetail);
        $this->Question_model->FSnMQAHDelDT($aDataQuestionDetail);
        if(!(FCNnHSizeOf($this->input->post('oetQadValue')) <= 0)){
            $nIndex = 1;
            foreach($this->input->post('oetQadValue') as $key => $value){
                $aDataQuestionDetail ['FNQasResuitSeq']    = $nIndex;
                $aDataQuestionDetail ['FNQasResuitName']   = $value;
                $aStaQahDetail    = $this->Question_model->FSaMQAHAddUpdateDetail($aDataQuestionDetail);
                $nIndex++;
            }
        }
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
                'tCodeReturn'	=> $aDataQuestionDetail['FTQahDocNo'],
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success UserCalendar Device Group',
                'tQahCode'      => $tQahCode
            );
        }
        echo json_encode($aReturn);
    }

}
