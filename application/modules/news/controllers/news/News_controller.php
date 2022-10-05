<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class News_controller extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('news/news/News_model');
        date_default_timezone_set("Asia/Bangkok");
    }

    //ฟังก์ชั่นหลัก
    public function index($nNewBrowseType, $tNewBrowseOption){
        $nMsgResp = array('title' => "news");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if (!$isXHR) {
            $this->load->view('common/wHeader', $nMsgResp);
            $this->load->view('common/wTopBar', array('nMsgResp' => $nMsgResp));
            $this->load->view('common/wMenu', array('nMsgResp' => $nMsgResp));
        }
        $vBtnSave           = FCNaHBtnSaveActiveHTML('news/0/0');
        $this->load->view('news/news/wNews', array(
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nNewBrowseType'    => $nNewBrowseType,
            'tNewBrowseOption'  => $tNewBrowseOption,
            'aAlwEventNews'	    => FCNaHCheckAlwFunc('news/0/0')
        ));
    }

    public function FSvCNEWListPage(){
        $aAlwEventNews	    = FCNaHCheckAlwFunc('news/0/0');
        $this->load->view('news/news/wNewsList', array(
            'aAlwEventNews' => $aAlwEventNews
        ));
    }

    //Datatable
    public function FSvCNEWDataTable(){
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent'); 
            $nLangEdit      = $this->session->userdata("tLangEdit");

            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );

            $aNewDataList     = $this->News_model->FSaMNEWList($aData);

            $aAlwEventNews	    = FCNaHCheckAlwFunc('news/0/0');
            $aGenTable  = array(
                'aNewDataList'      => $aNewDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll,
                'aAlwEventNews'     => $aAlwEventNews
            );

            $this->load->view('news/news/wNewsDataTable', $aGenTable);

        }catch(Exception $Error){
            echo $Error;
        }
    }
   
    //หน้าจอเพิ่มข้อมูล
    public function FSvCNEWPageAdd(){
        $this->load->view('news/news/wNewsAdd');
    }

    //หน้าจอแก้ไขข้อมูล
    public function FSvCNEWEditPage(){
        try{
            $aData = array(
                'tNewCode'    => $this->input->post('tNewCode'),
                'nLangResort' => $this->session->userdata("tLangID"),
                'nLangEdit'   =>  $this->session->userdata("tLangEdit")
            );

            $aNewData   = $this->News_model->FSaMNEWGetDataByID($aData);
            $aNewBchIn   = $this->News_model->FSaMNewBchByID($aData,1);
            $aNewBchEx   = $this->News_model->FSaMNewBchByID($aData,2);
            $aData      = array(
                'nStaAddOrEdit' => 1,
                'aNewData'      => $aNewData,
                'aNewBchIn'     => $aNewBchIn,
                'aNewBchEx'     => $aNewBchEx,
            );
            $this->load->view('news/news/wNewsAdd', $aData);
        }catch(Exception $Error){
            echo $Error;
        }
    }

 

    //Event Add : Tab 1
    public function FSoCNEWAddEvent(){
        try{

            $tNewUsrCode = $this->input->post('oetNewUsrCode');
            $tNewUsrName = $this->input->post('oetNewUsrName');
            $nNewToType  = $this->input->post('ocmNewToType');
            $aBranchCodeIn  = explode(',',$this->input->post('oetBranchCodeIn'));
            $aBranchCodeEx  = explode(',',$this->input->post('oetBranchCodeEx'));
            $aAgencyCodeIn  = explode(',',$this->input->post('oetAgencyCodeIn'));
            $aAgencyCodeEx  = explode(',',$this->input->post('oetAgencyCodeEx'));

            $tNewDesc1  = $this->input->post('oetNewDesc1');
            $tNewDesc2  = $this->input->post('oetNewDesc2');
            $tNewRefUrl  = $this->input->post('oetNewRefUrl');
            
            $aDataMaster = array(
                'FTNewDesc1'    => $tNewDesc1,
                'FTNewDesc2'    => $tNewDesc2,
                'FTNewRefUrl'   => '',
                'FNNewToType'   => $nNewToType,
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $tNewUsrCode,
                'FTLastUpdBy'   => $tNewUsrCode,
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FTBchCode'     => $this->session->userdata('tSesUsrBchCodeDefault'),
            );
            
      
            $aStoreParam = array(
                "tTblName"   => 'TCNMNews',                           
                "tDocType"   => 0,                                          
                "tBchCode"   => "",                                 
                "tShpCode"   => "",                               
                "tPosCode"   => "",                     
                "dDocDate"   => date("Y-m-d H:i:s")       
            );
            $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
            $aDataMaster['FTNewCode']   = $aAutogen[0]["FTXxhDocNo"];

            $aDataNewBch = array();
            if($nNewToType==1){

                if(!empty($aBranchCodeIn)){
                        foreach($aBranchCodeIn as $tBchCode){
                            if($tBchCode==''){
                                continue;
                            }
                                $aDataNewBch[]=array(
                                    'FTNewCode' => $aDataMaster['FTNewCode'],
                                    'FTNewAgnTo' => '',
                                    'FTNewBchTo' => $tBchCode,
                                    'FTNewStaType' => 1
                                );
                        }
                }
                if(!empty($aBranchCodeEx)){
                        foreach($aBranchCodeEx as $tBchCode){
                            if($tBchCode==''){
                                continue;
                            }
                                $aDataNewBch[]=array(
                                    'FTNewCode' => $aDataMaster['FTNewCode'],
                                    'FTNewAgnTo' => '',
                                    'FTNewBchTo' => $tBchCode,
                                    'FTNewStaType' => 2
                                );
                        }
                }

            }else{
                if(!empty($aAgencyCodeIn)){
                    foreach($aAgencyCodeIn as $tAgnCode){
                        if($tAgnCode==''){
                            continue;
                        }
                            $aDataNewBch[]=array(
                                'FTNewCode' => $aDataMaster['FTNewCode'],
                                'FTNewAgnTo' => $tAgnCode,
                                'FTNewBchTo' => '',
                                'FTNewStaType' => 1
                            );
                    }
                }
                if(!empty($aAgencyCodeEx)){
                    foreach($aAgencyCodeEx as $tAgnCode){
                        if($tAgnCode==''){
                            continue;
                        }
                            $aDataNewBch[]=array(
                                'FTNewCode' => $aDataMaster['FTNewCode'],
                                'FTNewAgnTo' => $tAgnCode,
                                'FTNewBchTo' => '',
                                'FTNewStaType' => 2
                            );
                    }
            }
            }
            
                $this->db->trans_begin();
                $aStaEventMaster     = $this->News_model->FSaMNEWAddUpdateMaster($aDataMaster);
                $aStaEventLang       = $this->News_model->FSaMNEWAddUpdateLang($aDataMaster);
                $aStaEventMasterBch  = $this->News_model->FSaMNEWAddUpdateBch($aDataNewBch,$aDataMaster);
                
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event 1"
                    );
                }else{
                    $this->db->trans_commit();
        
                    $aReturn = array(
                        'aImgReturn'    => ( isset($aImgReturn) && !empty($aImgReturn) ? $aImgReturn : array("nStaEvent" => '1') ),
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTNewCode'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
    
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Event Edit : Tab 1
    public function FSoCNEWEditEvent(){
        try{
        

            $tNewCode  = $this->input->post('ohdNewCode');
            $tNewUsrCode = $this->input->post('oetNewUsrCode');
            $tNewUsrName = $this->input->post('oetNewUsrName');
            $nNewToType  = $this->input->post('ocmNewToType');
            $aBranchCodeIn  = explode(',',$this->input->post('oetBranchCodeIn'));
            $aBranchCodeEx  = explode(',',$this->input->post('oetBranchCodeEx'));
            $aAgencyCodeIn  = explode(',',$this->input->post('oetAgencyCodeIn'));
            $aAgencyCodeEx  = explode(',',$this->input->post('oetAgencyCodeEx'));

            $tNewDesc1  = $this->input->post('oetNewDesc1');
            $tNewDesc2  = $this->input->post('oetNewDesc2');
            $tNewRefUrl  = $this->input->post('oetNewRefUrl');
            
            $aDataMaster = array(
                'FTNewCode'     => $tNewCode,
                'FTNewDesc1'    => $tNewDesc1,
                'FTNewDesc2'    => $tNewDesc2,
                'FTNewRefUrl'   => '',
                'FNNewToType'   => $nNewToType,
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $tNewUsrCode,
                'FTLastUpdBy'   => $tNewUsrCode,
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FTBchCode'     => $this->session->userdata('tSesUsrBchCodeDefault'),
            );
            

            $aDataNewBch = array();
            if($nNewToType==1){

                if(!empty($aBranchCodeIn)){
                        foreach($aBranchCodeIn as $tBchCode){
                            if($tBchCode==''){
                                continue;
                            }
                                $aDataNewBch[]=array(
                                    'FTNewCode' => $aDataMaster['FTNewCode'],
                                    'FTNewAgnTo' => '',
                                    'FTNewBchTo' => $tBchCode,
                                    'FTNewStaType' => 1
                                );
                        }
                }
                if(!empty($aBranchCodeEx)){
                        foreach($aBranchCodeEx as $tBchCode){
                            if($tBchCode==''){
                                continue;
                            }
                                $aDataNewBch[]=array(
                                    'FTNewCode' => $aDataMaster['FTNewCode'],
                                    'FTNewAgnTo' => '',
                                    'FTNewBchTo' => $tBchCode,
                                    'FTNewStaType' => 2
                                );
                        }
                }

            }else{
                if(!empty($aAgencyCodeIn)){
                    foreach($aAgencyCodeIn as $tAgnCode){
                        if($tAgnCode==''){
                            continue;
                        }
                            $aDataNewBch[]=array(
                                'FTNewCode' => $aDataMaster['FTNewCode'],
                                'FTNewAgnTo' => $tAgnCode,
                                'FTNewBchTo' => '',
                                'FTNewStaType' => 1
                            );
                    }
                }
                if(!empty($aAgencyCodeEx)){
                    foreach($aAgencyCodeEx as $tAgnCode){
                        if($tAgnCode==''){
                            continue;
                        }
                            $aDataNewBch[]=array(
                                'FTNewCode' => $aDataMaster['FTNewCode'],
                                'FTNewAgnTo' => $tAgnCode,
                                'FTNewBchTo' => '',
                                'FTNewStaType' => 2
                            );
                    }
            }
            }
            
                $this->db->trans_begin();
                $aStaEventMaster     = $this->News_model->FSaMNEWAddUpdateMaster($aDataMaster);
                $aStaEventLang       = $this->News_model->FSaMNEWAddUpdateLang($aDataMaster);
               
                 $aStaEventMasterBch  = $this->News_model->FSaMNEWAddUpdateBch($aDataNewBch,$aDataMaster);
                
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event 1"
                    );
                }else{
                    $this->db->trans_commit();
        
                    $aReturn = array(
                        'aImgReturn'    => ( isset($aImgReturn) && !empty($aImgReturn) ? $aImgReturn : array("nStaEvent" => '1') ),
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTNewCode'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
    
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Event Delete 
    public function FSoCNEWDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTNewCode' => $tIDCode
        );
        $aResDel    = $this->News_model->FSaMNEWDelAll($aDataMaster);
        $nNumRowNEW = $this->News_model->FSnMNEWGetAllNumRow();

        if($nNumRowNEW!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'nNumRowNEW' => $nNumRowNEW
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }


    public function FSoCNEWEventSendNoti(){

        
        $nStaChkAll = $this->input->post('nStaChkAll');
        $aNewCode = $this->input->post('aNewCode');

        if(!empty($aNewCode)){
            foreach($aNewCode as $tNewCode){
                        $aData = array(
                            'tNewCode'    => $tNewCode,
                            'nLangResort' => $this->session->userdata("tLangID"),
                            'nLangEdit'   =>  $this->session->userdata("tLangEdit")
                        );
                        $aNewData   = $this->News_model->FSaMNEWGetDataByID($aData);
                        $aNewBchIn   = $this->News_model->FSaMNewBchByID($aData,1);
                        $aNewBchEx   = $this->News_model->FSaMNewBchByID($aData,2);
                        
                        if($aNewData['rtCode']=='1'){
                            
                            $oaTCNTNotiSpc[] =  array(
                                "FNNotID"      => '',
                                "FTNotType"    => '1',
                                "FTNotStaType" => '1',
                                "FTAgnCode"    => '',
                                "FTAgnName"    => '',
                                "FTBchCode"    => $aNewData['raItems']['rtBchCode'],
                                "FTBchName"    => $aNewData['raItems']['rtBchName'],
                            );
                            if($aNewBchIn['rtCode']=='1'){    
                                foreach($aNewBchIn['raItems'] as $nKey => $aValue){
                                    $oaTCNTNotiSpc[] =  array(
                                                        "FNNotID"      => '',
                                                        "FTNotType"    => '2',
                                                        "FTNotStaType" => 1,
                                                        "FTAgnCode"    => $aValue['FTNewAgnTo'],
                                                        "FTAgnName"    => $aValue['FTAgnName'],
                                                        "FTBchCode"    => $aValue['FTNewBchTo'],
                                                        "FTBchName"    => $aValue['FTBchName'],
                                    );
                                }
                            }

                            if($aNewBchEx['rtCode']=='1'){    
                                foreach($aNewBchEx['raItems'] as $nKey => $aValue){
                                    $oaTCNTNotiSpc[] =  array(
                                                        "FNNotID"      => '',
                                                        "FTNotType"    => '2',
                                                        "FTNotStaType" => 2,
                                                        "FTAgnCode"    => $aValue['FTNewAgnTo'],
                                                        "FTAgnName"    => $aValue['FTAgnName'],
                                                        "FTBchCode"    => $aValue['FTNewBchTo'],
                                                        "FTBchName"    => $aValue['FTBchName'],
                                    );
                                }
                            }
                            $aMQParamsNoti = [
                                "queueName" => "CN_SendToNoti",
                                "tVhostType" => "NOT",
                                "params"    => [
                                                "oaTCNTNoti" => array(
                                                                "FNNotID"       => '',
                                                                "FTNotCode"     => '00000',
                                                                "FTNotKey"      => 'NEWS',
                                                                "FTNotBchRef"   => $aNewData['raItems']['rtBchCode'],
                                                                "FTNotDocRef"   => $aNewData['raItems']['rtNewCode'],
                                                ),
                                                "oaTCNTNoti_L" => array(
                                                                    0 => array(
                                                                        "FNNotID"       => '',
                                                                        "FNLngID"       => $this->session->userdata("tLangEdit"),
                                                                        "FTNotDesc1"    => $aNewData['raItems']['rtNewName'],
                                                                        "FTNotDesc2"    => $aNewData['raItems']['rtUsrName'],
                                                                    ),
                                                ),
                                                "oaTCNTNotiAct" => array(
                                                                    0 => array( 
                                                                            "FNNotID"         => '',
                                                                            "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                                                            "FTNoaDesc"       => $aNewData['raItems']['rtNewName'],
                                                                            "FTNoaDocRef"     => $aNewData['raItems']['rtNewCode'],
                                                                            "FNNoaUrlType"    => 2,
                                                                            "FTNoaUrlRef"     => base_url('ReadNews/'.$aNewData['raItems']['rtNewCode'].'/'.$aData['nLangEdit']),
                                                                            ),
                                                    ), 
                                                "oaTCNTNotiSpc" => $oaTCNTNotiSpc,
                                    "ptUser"        => $this->session->userdata('tSesUsername'),
                                ]
                            ];
                            FCNxCallRabbitMQ($aMQParamsNoti);
                            $aReturn    = array(
                                'nStaEvent' => '1',
                                'tStaMessg' => 'Send Noti Success.',
                            );
                            }else{
                                $aReturn    = array(
                                    'nStaEvent' => '900',
                                    'tStaMessg' => 'Send Noti UnSuccess.',
                                );
                            }
                        }
            }else{
                $aReturn    = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => 'Send Noti UnSuccess.',
                );
            }

        echo json_encode($aReturn);

    }
    //////////////////////////////////////*************************///////////////////////////////////////
    public function FSoCNEWEventReadNews($ptNewCode,$pnLangEdit){
        try{
            if($pnLangEdit==''){
                $pnLangEdit= 1;
            }
            $aData = array(
                'tNewCode'    => $ptNewCode,
                'nLangEdit'   => $pnLangEdit
            );

            $aNewData   = $this->News_model->FSaMNEWGetDataByID($aData);
            $aNewBchIn   = $this->News_model->FSaMNewBchByID($aData,1);
            $aNewBchEx   = $this->News_model->FSaMNewBchByID($aData,2);
            $aNewBchFile = $this->News_model->FSaMNewGetFile($aData);
            $aData      = array(
                'aNewData'      => $aNewData,
                'aNewBchIn'     => $aNewBchIn,
                'aNewBchEx'     => $aNewBchEx,
                'aNewBchFile'   => $aNewBchFile,
            );
            $this->load->view('news/news/wNewsRead', $aData);
        }catch(Exception $Error){
            echo $Error;
        }
    }
}
