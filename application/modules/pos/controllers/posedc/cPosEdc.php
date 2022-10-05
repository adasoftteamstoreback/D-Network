<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cPosEdc extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('pos/posedc/mPosEdc');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nPosEdcBrowseType,$tPosEdcBrowseOption){
        $aDataConfigView    = [
            'nPosEdcBrowseType'     => $nPosEdcBrowseType,
            'tPosEdcBrowseOption'   => $tPosEdcBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('posEdc/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('posEdc/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave()
        ];
        $this->load->view('pos/posedc/wPosEdc',$aDataConfigView);
    }

    // Functionality : Call Page From Search List
    // Parameters : Ajax and Function Parameter
    // Creator : 30/08/2019 wasin (Yoshi)
    // LastUpdate: -
    // Return : View Page Html
    // Return Type : View
    public function FSvCPosEdcListPage(){
        $aDataConfigView    = ['aAlwEvent' => FCNaHCheckAlwFunc('posEdc/0/0')];
        $this->load->view('pos/posedc/wPosEdcList',$aDataConfigView);
    }

    // Functionality : Call DataTables Pos Edc List
    // Parameters : Ajax Function Call Page
    // Creator : 30/08/2019 wasin (Yoshi)
    // LastUpdate: -
    // Return : object Data Table
    // Return Type : object
    public function FSoCPosEdcDataTable(){
        try{
            $tSearchAll     = $this->input->post('ptSearchAll');
            $nPage          = ($this->input->post('pnPageCurrent') == '' || null)? 1 : $this->input->post('pnPageCurrent');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aDataWhere = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );
            $aDataPosEdcList    = $this->mPosEdc->FSaMGetDataPosEdcList($aDataWhere);
            $aAlwEvent          = FCNaHCheckAlwFunc('posEdc/0/0');
            $aConfigView    = array(
                'nPage'     => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataPosEdcList
            );
            $tPosEdcViewDataTableList   = $this->load->view('pos/posedc/wPosEdcDataTable',$aConfigView,true);
            $aReturnData = array(
                'tViewDataTableList'    => $tPosEdcViewDataTableList,
                'nStaEvent' => 1,
                'tStaMessg' => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality : Call Page Add Pos Edc
    // Parameters : Ajax Function Call Page
    // Creator : 02/09/2019 wasin(Yoshi)
    // LastUpdate: -
    // Return : object Data Page Add
    // Return Type : object
    public function FSoCPosEdcCallPageAdd(){
        try{
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aDataConfigViewForm    = array(
                'nStaCallView'      => 1, // 1 = Call View Add , 2 = Call View Edits
            );
            $tPosEdcViewPageForm    = $this->load->view('pos/posedc/wPosEdcAdd',$aDataConfigViewForm,true);
            $aReturnData    = array(
                'tPosEdcViewPageForm'   => $tPosEdcViewPageForm,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality : Call Page Edit Pos Edc
    // Parameters : Ajax Function Call Page
    // Creator : 02/09/2019 wasin(Yoshi)
    // LastUpdate: -
    // Return : object Data Page Add
    // Return Type : object
    public function FSoCPosEdcCallPageEdit(){
        try{
            $tPosEdcCode    = $this->input->post('ptPosEdcCode');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aDataWhere = [
                'FTEdcCode' => $tPosEdcCode,
                'FNLngID'   => $nLangEdit
            ];
            $aDataPosEdc    = $this->mPosEdc->FSaMPosEdcGetDataByID($aDataWhere);
            /** Option Config View Form
             * nStaCallView : การเข้าถึงฟอร์มว่ามาจาก การกด Add หรือ มาจากการกด Edit
             * aDataPosEdc  : ข้อมูล Pos EDC
            */
            $aDataConfigViewForm    = array(
                'nStaCallView'  => 2,
                'aDataPosEdc'   => $aDataPosEdc
            );
            $tPosEdcViewPageForm    = $this->load->view('pos/posedc/wPosEdcAdd',$aDataConfigViewForm,true);
            $aReturnData            = [
                'nStaEvent'             => 1,
                'tStaMessg'             => 'Success',
                'tPosEdcViewPageForm'   => $tPosEdcViewPageForm
            ];
        }catch(Exception $Error){
            $aReturnData    = [
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            ];
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Add Event PosEdc
    //Parameters : Ajax Route Parameter
    //Creator : 02/09/2019 wasin(Yoshi)
    //Last Modified : -
    //Return : Status CallBack Add Event
    //Return Type : object
    public function FSoCPosEdcAddEvent(){
        try{
            $this->db->trans_begin();

            $nPosEdcAutoGenCode = (!empty($this->input->post('ocbPosEdcAutoGenCode')))? 1 : 0;
            $tPosEdcCode    = "";
            if(isset($nPosEdcAutoGenCode) &&  $nPosEdcAutoGenCode == 1){
                // 15/05/2020 Nattakit(Nale)
                $aStoreParam = array(
                    "tTblName"    => 'TFNMEdc',
                    "tDocType"    => 0,
                    "tBchCode"    => "",
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );
                $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
                $tPosEdcCode = $aAutogen[0]["FTXxhDocNo"];
            }else{
                $tPosEdcCode  = $this->input->post('oetPosEdcCode');
            }
            // Master Add/Update Table
            $aDataMaster    = array(
                'FTEdcCode'     => $tPosEdcCode,
                'FTSedCode'     => $this->input->post('oetPosEdcSedCode'),
                'FTBnkCode'     => $this->input->post('oetPosEdcBnkCode'),
                'FTEdcShwFont'  => $this->input->post('oetPosEdcShwFont'),
                'FTEdcShwBkg'   => $this->input->post('oetPosEdcShwBkg'),
                'FTEdcOther'    => $this->input->post('oetPosEdcOther'),
                'FTEdcName'     => $this->input->post('oetPosEdcName'),
                'FTEdcRmk'      => $this->input->post('otaPosEdcRemark'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FTAgnCode'     => $this->input->post('oetEDCUsrAgnCode'),
            );
            $this->mPosEdc->FSxMPosEdcAddUpdateMain($aDataMaster);
            $this->mPosEdc->FSxMPosEdcAddUpdateLang($aDataMaster);
            // Check Trancetion Event Add Pos Edc
            if($this->db->trans_status() !== FALSE){
                $this->db->trans_commit();
                $tImgInputPosEdc           = $this->input->post("oetImgInputPosEdc");
                $tImgInputPosEdcOld        = $this->input->post("oetImgInputPosEdcOld");
                $aPackUplode = array(
                    'tModuleName'       => 'pos',
                    'tImgFolder'        => 'posedc',
                    'tImgRefID'         => $aDataMaster['FTEdcCode'],
                    'tImgTable'         => 'TFNMEdc',
                    'tTableInsert'      => 'TCNMImgObj',
                    'tImgKey'           => 'main',
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'nStaDelBeforeEdit' => 1,
                    'dDateTimeOn'       => date('Y-m-d H:i:s'),
                    'tWhoBy'            => $this->session->userdata('tSesUsername'),
                );
                if (isset($tImgInputPosEdc) && !empty($tImgInputPosEdc) && $tImgInputPosEdc != $tImgInputPosEdcOld) {
                    $aPackUplode['tImgObj'] = $tImgInputPosEdc;
                    $aImgReturn = FCNnHAddImgObj($aPackUplode);
                } else {
                    $tCheckedColor  = $this->input->post('orbChecked');
                    $tInputColor    = $this->input->post('oetImgColorPosEdc');

                    if ((isset($tCheckedColor) && !empty($tCheckedColor)) || (isset($tInputColor) && !empty($tInputColor))) {
                        $aPackUplode['tImgObj'] = (isset($tCheckedColor) && !empty($tCheckedColor) ? $tCheckedColor : $tInputColor);
                        FCNxHAddColorObj($aPackUplode);
                    }
                }
                $aReturnData = array(
                    'nStaEvent'     =>  1,
                    'tStaMessg'     => 'Add/Update Pos Edc Complete.',
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTEdcCode'],
                );
            }else{
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nCodeReturn'   => 500,
                    'tTextStaMessg' => 'Error Not Add/Update Data Pos Edc.',
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['nCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Edit Event PosEdc
    //Parameters : Ajax Route Parameter
    //Creator : 02/09/2019 wasin(Yoshi)
    //Last Modified : -
    //Return : Status CallBack Add Event
    //Return Type : object
    public function FSoCPosEdcEditEvent(){
        try{
            $this->db->trans_begin();
            // Master Add/Update Table
            $aDataMaster    = array(
                'FTEdcCode'     => $this->input->post('oetPosEdcCode'),
                'FTSedCode'     => $this->input->post('oetPosEdcSedCode'),
                'FTBnkCode'     => $this->input->post('oetPosEdcBnkCode'),
                'FTEdcShwFont'  => $this->input->post('oetPosEdcShwFont'),
                'FTEdcShwBkg'   => $this->input->post('oetPosEdcShwBkg'),
                'FTEdcOther'    => $this->input->post('oetPosEdcOther'),
                'FTEdcName'     => $this->input->post('oetPosEdcName'),
                'FTEdcRmk'      => $this->input->post('otaPosEdcRemark'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit"),
            );
            $this->mPosEdc->FSxMPosEdcAddUpdateMain($aDataMaster);
            $this->mPosEdc->FSxMPosEdcAddUpdateLang($aDataMaster);
            // Check Trancetion Event Add Pos Edc
            if($this->db->trans_status() !== FALSE){
                $this->db->trans_commit();
                $tImgInputPosEdc           = $this->input->post("oetImgInputPosEdc");
                $tImgInputPosEdcOld        = $this->input->post("oetImgInputPosEdcOld");
                $aPackUplode = array(
                    'tModuleName'       => 'pos',
                    'tImgFolder'        => 'posedc',
                    'tImgRefID'         => $aDataMaster['FTEdcCode'],
                    'tImgTable'         => 'TFNMEdc',
                    'tTableInsert'      => 'TCNMImgObj',
                    'tImgKey'           => 'main',
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'nStaDelBeforeEdit' => 1,
                    'dDateTimeOn'       => date('Y-m-d H:i:s'),
                    'tWhoBy'            => $this->session->userdata('tSesUsername'),
                );
                if (isset($tImgInputPosEdc) && !empty($tImgInputPosEdc)) {
                    $aPackUplode['tImgObj'] = $tImgInputPosEdc;
                    $aImgReturn = FCNnHAddImgObj($aPackUplode);
                } else {
                    $tCheckedColor  = $this->input->post('orbChecked');
                    $tInputColor    = $this->input->post('oetImgColorPosEdc');

                    if ((isset($tCheckedColor) && !empty($tCheckedColor)) || (isset($tInputColor) && !empty($tInputColor))) {
                        $aPackUplode['tImgObj'] = (isset($tCheckedColor) && !empty($tCheckedColor) ? $tCheckedColor : $tInputColor);
                        FCNxHAddColorObj($aPackUplode);
                    }
                }
                $aReturnData = array(
                    'nStaEvent'     =>  1,
                    'tStaMessg'     => 'Add/Update Pos Edc Complete.',
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTEdcCode'],
                );
            }else{
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nCodeReturn'   => 500,
                    'tTextStaMessg' => 'Error Not Add/Update Data Pos Edc.',
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['nCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Delete Event Pos Edc
    //Parameters : Ajax Route Parameter
    //Creator : 03/09/2019 wasin(Yoshi)
    //Last Modified : -
    //Return : Status CallBack Delete Event
    //Return Type : object
    public function FSoCPosEdcDeleteEvent(){
        try{
            $tDeleteIDCode  = $this->input->post('ptDataCode');
            $aDataWhere    = array(
                'FTEdcCode' => $tDeleteIDCode
            );
            $aStaDelRole        = $this->mPosEdc->FSaMPosEdcDeleteData($aDataWhere);
            $aDeleteImage           = array(
                'tModuleName'  => 'pos',
                'tImgFolder'   => 'posedc',
                'tImgRefID'    => $tDeleteIDCode,
                'tTableDel'    => 'TCNMImgObj',
                'tImgTable'    => 'TFNMEdc'
            );
            //ลบข้อมูลในตาราง
            $nStaDelImgInDB = FSnHDelectImageInDB($aDeleteImage);
            if ($nStaDelImgInDB == 1) {
                //ลบรูปในโฟลเดอ
                FSnHDeleteImageFiles($aDeleteImage);
            }

            if($aStaDelRole['rtCode'] == '1'){
                $nNumRowPosEdc  = $this->mPosEdc->FSnMCountDataPosEdc();
                $aReturnData    = array(
                    'nStaEvent'     => $aStaDelRole['rtCode'],
                    'tStaMessg'     => $aStaDelRole['rtDesc'],
                    'nNumRowPosEdc' => $nNumRowPosEdc
                );
            }else{
                $aReturnData    = array(
                    'tCodeReturn'   => $aStaDelRole['rtCode'],
                    'tTextStaMessg' => $aStaDelRole['rtDesc'],
                );
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['nCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }






}
