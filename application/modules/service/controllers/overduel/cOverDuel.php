<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cOverDuel extends MX_Controller {

    public $tRouteMenu  = 'masOdl/0/0';

    public function __construct(){
        parent::__construct ();
        $this->load->model('service/overduel/mOdl');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    // Functionality : CallPage ODL
    // Parameters : Ajax Call Page DataTable ODL
    // Creator : 9/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object
    public function index($nOdlBrowseType,$tOdlBrowseOption){ // เช็คหน้าว่าถูกเรียกผ่านการ Browse หรือผ่าน Route
        $nMsgResp   = array('title'=>"Product Odl");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave           = FCNaHBtnSaveActiveHTML($this->tRouteMenu); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventOdl	    = FCNaHCheckAlwFunc($this->tRouteMenu);

        $this->load->view('service/overduel/wOdl', array (
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nOdlBrowseType'    => $nOdlBrowseType,
            'tOdlBrowseOption'  => $tOdlBrowseOption,
            'aAlwEventOdl'      => $aAlwEventOdl
        ));
    }
    // Functionality : CallPage ODL
    // Parameters : -
    // Creator : 9/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : object View
    // Return Type : object

    public function FSvCOdlListPage(){ //load view มาแสดง
        $aAlwEventOdl      = FCNaHCheckAlwFunc($this->tRouteMenu);
        $this->load->view('service/overduel/wOdlList', array(
            'aAlwEventOdl'  => $aAlwEventOdl
        ));
    }

    // Functionality : Select Data ODL
    // Parameters : function parameters
    // Creator : 9/08/2021 Phaksaran(Golf)
    // Last Modified : -
    // Return : Array Data Query For Database
    // Return Type : Array

    public function FSvCOdlDataList(){ //ดึง database ออกมาแสดงที่หน้า view(wPdtOdlDataTable) ทั้งแบบไม่มีการค้นหาหรือมีการค้นหา
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $tSearchAllType = $this->input->post('tSearchAllType');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll,
                'tSearchAllType'=> $tSearchAllType
            );
            $aOdlDataList       = $this->mOdl->FSaMOdlList($aData);
            $aAlwEventOdl       = FCNaHCheckAlwFunc($this->tRouteMenu);
            $aGenTable  = array(
                'aOdlDataList'      => $aOdlDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll,
                'tSearchAllType'=> $tSearchAllType,
                'aAlwEventOdl'  => $aAlwEventOdl
            );
            $this->load->view('service/overduel/wOdlDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }
    //Functionality : Function CallPage ODL Add
    //Parameters : Ajax Call View Add
    // Creator : 9/08/2021 Phaksaran(Golf)
    //Return : String View
    //Return Type : View
    public function FSvCOdlAddPage(){ //load view หน้าเพิ่มข้อมูล
        try{
            $aDataOdl = array(
                'nStaAddOrEdit'   => 99,
                'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName")
            );
            $this->load->view('service/overduel/wOdlAdd', $aDataOdl);
        }catch(Exception $Error){
            echo $Error;
        }
    }
    //Functionality: Function Add and Edit ODL
    //Parameters:  Ajax Send Event Post
    //Creator: 9/08/2021 Phaksaran(Golf)
    //LastModified: -
    //Return: Return object Event Edit
    //ReturnType: object
    public function FSoCOdlAddEvent(){  // event insert ข้อมูล ลง database
        try{
            $tMaxDate = $this->input->post('oetOdlMax');
            if($tMaxDate  == ''){
                $tMaxDate  = 0;
            }
            $tOdlCode   = $this->input->post("ocbOdlAutoGenCode");
            $aDataOdl   = array(
                'tIsAutoGenCode' => $tOdlCode,
                'FTOdlCode'    => $this->input->post('oetOdlCode'),
                'FTAgnCode'    => $this->input->post('oetOdlAgnCode'),
                'FTOdlType'    => $this->input->post('ocmOdlTpye'),
                'FNOdlMin'     => $this->input->post('oetOdlMin'),
                'FNOdlMax'     => $tMaxDate,
                'FDCreateOn'   => date('Y-m-d H:i:s'),
                'FDLastUpdOn'  => date('Y-m-d H:i:s'),
                'FTCreateBy'   => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'  => $this->session->userdata('tSesUsername'),
                'FNLngID'      => $this->session->userdata("tLangEdit"),

            );

            if($aDataOdl["tIsAutoGenCode"] == '1'){ // Check Auto Gen Reason Code?
                $aStoreParam = array(
                    "tTblName"    => 'TCNMOverDueLev',
                    "tDocType"    => 0,
                    "tBchCode"    => "",
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d")
                );
                $aAutogen                 = FCNaHAUTGenDocNo($aStoreParam);
                $aDataOdl["FTOdlCode"]   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup      = $this->mOdl->FSnMOdlCheckDuplicate($aDataOdl['FTOdlCode']);
            $nStaDup        = $oCountDup['counts'];
            if($oCountDup !== FALSE && $nStaDup == 0){
                $this->db->trans_begin();



                $aStaOdlMaster  = $this->mOdl->FSaMOdlAddUpdateMaster($aDataOdl);

                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Product Odl"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'   => $aDataOdl['FTOdlCode'],
                        'nStaEvent'     => '1',
                        'tStaMessg'     => 'Success Add Odl'
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
    //Functionality: เพิ่มข้อมูล StockConditions
    //Parameters:  พารามิเตอร์ จาก jOverDuel
    //Creator : 9/08/2021 Golf
    //LastModified: -
    //Return: Return JSON
    //ReturnType: JSON
    public function FSoCOdlEditEvent(){  // event update ข้อมูลลง database
        try{
            $this->db->trans_begin();
            $aDataOdl   = array(
                'FTOdlCode'   => $this->input->post('oetOdlCode'),
                'FTAgnCode'   => $this->input->post('oetOdlAgnCode'),
                'FTOdlType'   => $this->input->post('ocmOdlTpye'),
                'FNOdlMin'    => $this->input->post('oetOdlMin'),
                'FNOdlMax'    => $this->input->post('oetOdlMax'),
                'FDCreateOn'  => date('Y-m-d H:i:s'),
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
                'FNLngID'     => $this->session->userdata("tLangEdit"),
            );
            $aStaOdlMaster  = $this->mOdl->FSaMOdlAddUpdateMaster($aDataOdl);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Product Odl"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataOdl['FTOdlCode'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Edit Odl'
                );
            }
            echo json_encode($aReturn);

        }catch(Exception $Error){
            echo $Error;
        }
    }
    //Functionality : Function CallPage ODL Edits
    //Parameters : Ajax Call View Add
    //Creator : 9/08/2021 Golf
    //Return : String View
    //Return Type : View
    public function FSvCOdlEditPage(){  //load หน้า edit ขึ้นมาแสดงผลที่หน้า view
        try{
            $tOdlCode       = $this->input->post('tOdlCode');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");

            $aData  = array(
                'FTOdlCode'   => $tOdlCode,
                'FNLngID'   => $nLangEdit
            );

            $aOdlData = $this->mOdl->FSaMOdlGetDataByID($aData);

            $aDataOdl  = array(
                'nStaAddOrEdit' => 1,
                'aOdlData'      => $aOdlData
            );
            $this->load->view('service/overduel/wOdlAdd',$aDataOdl);
        }catch(Exception $Error){
            echo $Error;
        }
    }
    //Functionality : Event Delete ODL
    //Parameters : Ajax
    //Creator : 9/08/2021 Golf
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : array
    public function FSoCOdlDeleteEvent(){ // event delete ข้อมูลทั้งได้ทั้ง single และ multi
        $aOdlCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTOdlCode' => $aOdlCode
        );
        $aResDel    = $this->mOdl->FSaMOdlDelAll($aDataMaster);
        $nNumRowOdl = $this->mOdl->FSnMOdlGetAllNumRow();
        if($nNumRowOdl!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['tCode'],
                'tStaMessg' => $aResDel['tDesc'],
                'nNumRowOdl' => $nNumRowOdl
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }


    public function FSnCOdlChkDupMinMax(){
        $aDataWhere = [
            'FTAgnCode' => $this->input->post('tOdlAgnCode'),
            'FTOdlType' => $this->input->post('tOdlTpye'),
            'FNOdlMin'  => $this->input->post('tOdlMin'),
            'FNOdlMax'  => $this->input->post('tOdlMax'),
        ];
        $nStaChk    = $this->mOdl->FSnMOdlChkDupMinMax($aDataWhere);
        echo $nStaChk;
    }





}
