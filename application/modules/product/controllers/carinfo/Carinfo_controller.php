<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Carinfo_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('product/carinfo/Carinfo_model');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nCAIBrowseType,$tCAIBrowseOption,$nCAICarType){
        $nMsgResp = array('title'=>"Carinfo");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave               = FCNaHBtnSaveActiveHTML('masCAIView/0/0'.'/'.$nCAICarType);
        $aAlwEventCarInfo	    = FCNaHCheckAlwFunc('masCAIView/0/0'.'/'.$nCAICarType);
        $this->load->view ( 'product/carinfo/wCarInfo', array (
            'nMsgResp'              =>$nMsgResp,
            'vBtnSave'              =>$vBtnSave,
            'nCAIBrowseType'        =>$nCAIBrowseType,
            'tCAIBrowseOption'      =>$tCAIBrowseOption,
            'nCAICarType'           =>$nCAICarType,
            'aAlwEventCarInfo'      =>$aAlwEventCarInfo
        ));
    }

    //Functionality : Function Call Page CarInfo List
    //Parameters : Ajax jCarInfo()
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCAIListPage(){
        $nCarType = $this->input->post('nCarType');
        $aAlwEventCarInfo	    = FCNaHCheckAlwFunc('masCAIView/0/0'.'/'.$nCarType);
        $aNewData  		        = array( 'aAlwEventCarInfo' => $aAlwEventCarInfo);
        $this->load->view('product/carinfo/wCarInfoList',$aNewData);
    }

    //Functionality : Function Call DataTables CarInfo List
    //Parameters : Ajax jCarInfo()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCAIDataList(){
        $nPage  = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        $nCarType   = $this->input->post('nCarType');
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
            'nCarType'      => $nCarType
        );

        $tAPIReq    = "";
        $tMethodReq = "GET";
        $aResList   = $this->Carinfo_model->FSaMCAIList($tAPIReq,$tMethodReq,$aData);
        $aAlwEvent = FCNaHCheckAlwFunc('masCAIView/0/0'.'/'. $nCarType); //Controle Event
        $aGenTable  = array(
            'aAlwEventCarInfo' => $aAlwEvent,
            'aDataList'     => $aResList,
            'nPage'         => $nPage,
            'tSearchAll'    => $tSearchAll,
            'nCarType'      => $nCarType
        );

        $this->load->view('product/carinfo/wCarInfoDataTable',$aGenTable);
    }

    //Functionality : Function Call Add Page CarInfo
    //Parameters : Ajax jCarInfo()
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCAIAddPage(){
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $nCarType       = $this->input->post('nCarType');
        $aData  = array(
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";

        $aDataAdd = array(
            'aResult'       => array('rtCode'=>'99'),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
            'nCarType'      => $nCarType
        );

        $this->load->view('product/carinfo/wCarInfoAdd',$aDataAdd);
    }

    //Functionality : Function Call Edit Page QasSubGroup
    //Parameters : Ajax jQasSubGroup()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCAIEditPage(){
        $tCAICode       = $this->input->post('tCAICode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $nCarType       = $this->input->post('nCarType');
        $aData  = array(
            'FTCaiCode' => $tCAICode,
            'FNLngID'   => $nLangEdit
        );


        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCldData       = $this->Carinfo_model->FSaMCAISearchByID($tAPIReq,$tMethodReq,$aData);
        $aDataEdit = array(
            'aResult'   => $aCldData,
            'nCarType'  => $nCarType
        );
        $this->load->view('product/carinfo/wCarInfoAdd',$aDataEdit);
    }

    //Functionality : Event Add CarInfo
    //Parameters : Ajax jCarInfo()
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCCAIAddEvent(){
        try{
            $nCatType   = $this->input->post('ohdCaiType');
            $tAgnCode   = $this->input->post('oetCaiAgnCode');
            $nLastSeq   = $this->Carinfo_model->FSaMCAILastSeqByShwCode($nCatType,$tAgnCode);
            $nLastSeq   = $nLastSeq+1;
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbCarInfoAutoGenCode'),
                'FTCaiType'             => $nCatType,
                'FNCaiSeq'              => $nLastSeq,
                'FTCaiCode'             => $this->input->post('oetCAICode'),
                'FTCaiName'             => $this->input->post('oetCAIName'),
                'FTCaiRmk'              => $this->input->post('otaCAIRemark'),
                'FTCaiStaUse'           => $this->input->post('ocbCarInfoStatus'),
                'FTCarParent'           => $this->input->post('oetCaiBrandCode'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetCaiAgnCode'),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){
                $aStoreParam = array(
                    "tTblName"   => 'TSVMCarInfo',
                    "tDocType"   => 0,
                    "tBchCode"   => "",
                    "tShpCode"   => "",
                    "tPosCode"   => "",
                    "dDocDate"   => date("Y-m-d")
                );
                $aAutogen   				= FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTCaiCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            if(($this->input->post('ocbCarInfoStatus') == '')){
                $aDataMaster['FTCaiStaUse']   = '2';
            }
            $oCountDup  = $this->Carinfo_model->FSoMCAICheckDuplicate($aDataMaster['FTCaiCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaCAIMaster  = $this->Carinfo_model->FSaMCAIAddUpdateMaster($aDataMaster);
                $aStaCAILang    = $this->Carinfo_model->FSaMCAIAddUpdateLang($aDataMaster);
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
                        'tCodeReturn'	=> $aDataMaster['FTCaiCode'],
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

    //Functionality : Event Edit CarInfo
    //Parameters : Ajax jCarInfo()
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCCAIEditEvent(){
            $nCatType       = $this->input->post('ohdCaiType');
            $tAgnCode       = $this->input->post('oetCaiAgnCode');
            $tAgnCodeTmp    = $this->input->post('ohdCaiAgnCodeTmp');
            $nLastSeq       = $this->Carinfo_model->FSaMCAILastSeqByShwCode($nCatType,$tAgnCode);
            if($tAgnCode != $tAgnCodeTmp){
                $nLastSeq       = $nLastSeq+1;
            }
        try{
            $aDataMaster    = array(
                'FTCaiCode'             => $this->input->post('oetCAICode'),
                'FTCaiName'             => $this->input->post('oetCAIName'),
                'FNCaiSeq'              => $nLastSeq,
                'FTCaiRmk'              => $this->input->post('otaCAIRemark'),
                'FTCaiStaUse'           => $this->input->post('ocbCarInfoStatus'),
                'FTCarParent'           => $this->input->post('oetCaiBrandCode'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTAgnCode'             => $this->input->post('oetCaiAgnCode'),
            );
            if(($this->input->post('ocbCarInfoStatus') == '')){
                $aDataMaster['FTCaiStaUse']   = '2';
            }
            $this->db->trans_begin();
            $aStaCAIMaster  = $this->Carinfo_model->FSaMCAIAddUpdateMaster($aDataMaster);
            $aStaCAILang    = $this->Carinfo_model->FSaMCAIAddUpdateLang($aDataMaster);

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
                    'tCodeReturn'	=> $aDataMaster['FTCaiCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Functionality : Event Delete CarInfo
    //Parameters : Ajax jCarInfo()
    //Creator : 02/06/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCCAIDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $nCarType = $this->input->post('nCarType');
        $aDataMaster = array(
            'FTCaiCode' => $tIDCode
        );
        $tAPIReq        = 'API/service/CarInfo/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Carinfo_model->FSnMCAIDel($tAPIReq,$tMethodReq,$aDataMaster);
        $nNumRowCldLoc  = $this->Carinfo_model->FSnMCAIGetAllNumRow($nCarType);

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
