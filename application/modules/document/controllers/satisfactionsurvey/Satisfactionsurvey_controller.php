<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Satisfactionsurvey_controller extends MX_Controller {
    public function __construct(){
        $this->load->model('document/satisfactionsurvey/Satisfactionsurvey_model');
        parent::__construct();
    }

    //public $tRouteMenu  = 'product/0/0';
    public $tRouteMenu  = 'docSatisfactionSurvey/0/0';

    public function index($ptRoute, $ptDocCode) //แสดงส่วนหัวหน้าใบประเมินความพึงพอใจของลูกค้า ($ptRoute คือ param ของหน้าว่าเข้ามาแบบ insert หรือ view, $ptDocCode เลขที่เอกสารที่แนบมา
    {   

        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );

        $aDataConfigView    = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave(),
            'aParams'           => $aParams
        ];

        $this->load->view('document/satisfactionsurvey/wSatisfactionSurvey',$aDataConfigView);
        
    }

    public function FSxCSATPageList(){
        $this->load->view('document/satisfactionsurvey/wSatisfactionSurveyPageList');
    }

    // แสดงตารางในหน้า List
    public function FSxCSATDatatable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc($this->tRouteMenu);

            // Page Current 
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage = $this->input->post('nPageCurrent');
            }

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID' => $nLangEdit,
                'nPage' => $nPage,
                'nRow' => 10,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList = $this->Satisfactionsurvey_model->FSaMSatSvGetDataTableList($aDataCondition);
            
            $aConfigView = array(
                'nPage' => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/satisfactionsurvey/wSatisfactionSurveyDatable', $aConfigView, true);
            $aReturnData = array(
                'tViewDataTable' => $tViewDataTable,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    public function FSvCSATAddPage() //แสดงส่วนรายละเอียดภายในใบประเมินความพึงพอใจของลูกค้า
    {   
        try {
            $nReturntype = $this->input->post('pnType');
            if ($nReturntype == 1) {
                $aDataQA = $this->Satisfactionsurvey_model->FSaMSATQaViewAnswer();
                $aDataFinal = array(
                    'tReturn' => 1
                );

                $aDataAll = array(
                    'aDataQA'           => $aDataQA,
                    'aDataGetDetail'    => $aDataFinal,
                    'tRoute'            => 'docSatisfactionSurveyEventAdd'
                );
            }else{
                $aDataFinal = $this->input->post('paDataFinal');
                
                // echo "<pre>";
                // print_r ($aDataFinal);
                // echo "</pre>";
                
                $aDataQA = $this->Satisfactionsurvey_model->FSaMSATQaViewAnswer();

                $aDataAll = array(
                    'aDataQA'           => $aDataQA,
                    'aDataGetDetail'    => $aDataFinal,
                    'tRoute'            => 'docSatisfactionSurveyEventAdd'
                );
            }
            
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        $this->load->view('document/satisfactionsurvey/wSatisfactionSurveyPageAdd', $aDataAll);
    } 

    //เพิ่มใบประเมินลง database 
    public function FSxCSATAddEvent() {
        try{
            $tSatAgnCode = $this->input->post('oetSatAgnCode');
            $tSatBchCode = $this->input->post('ohdSatBchCode');
            $tSatDocNo = $this->input->post('oetSatDocNo');

            $tSatAutoGenCode =  $this->input->post('ocbSatStaAutoGenCode') ? 1 : 0;
            $tStaDocAct = $this->input->post('ocbSatStaDocAct') ? 1 : 0;
            $tSatDocDate = $this->input->post('oetSatDocDate') . " " . $this->input->post('oetSatDocTime');
            $atSatAns = json_decode($this->input->post('aSatAns'), true);
            $atSatQue = json_decode($this->input->post('aSatQue'), true);
            
            $tSatRate = $this->input->post('orbSatSueveyRate');
            $tSatDocRefCode = $this->input->post('ohdSatDocRefCode');
            $tSatDocRefDate = $this->input->post('oetSatSurveyDateStaService');

            //App varsion
            $tAppVer = FCNtGetAppVersion();
    
            // Check Auto GenCode Document
            if ($tSatAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TSVTJob5ScoreHD',                           
                    "tDocType"    => '1',                                          
                    "tBchCode"    => $tSatBchCode,                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                
                $aAutogen    = FCNaHAUTGenDocNo($aStoreParam);
                $tSatDocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $tSatDocNo   = $tSatDocNo;
            }
            
            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'tTableHD'          =>'TSVTJob5ScoreHD',
                'tTableDT'          =>'TSVTJob5ScoreDT',
                'tTableAnsDT'       =>'TSVTJob5ScoreDTAns',
                'tTableDocRef5'     =>'TSVTJob5ScoreHDDocRef',
                'tTableDocRef2'     =>'TSVTJob2OrdHDDocRef'
            );
    
            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'FDXshDocDate'      => $tSatDocDate,
                'FTUsrCode'         => $this->input->post('ohdSatTaskRefUsrCode'),
                'FTXshApvCode'      => '',
                'FTXshRmk'          => $this->input->post('otaSatFrmInfoOthRmk'),
                'FTXshAdditional'   => $this->input->post('otaSatRmk'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => 1,
                'FTXshStaApv'       => '',
                'FNXshStaDocAct'    => $tStaDocAct,
                'FNXshScoreValue'   => $tSatRate,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata("tSesUsername")
            );
    
            // ข้อมูล Insert ลงตาราง DT
            $nCountSeq = 0;
            for ($i=0; $i < count($atSatQue); $i++) { 
                if ($i == 0) {
                   $nCountSeq =  $this->Satisfactionsurvey_model->FSaMSATCountXsdSeq($aDataPrimaryKey);
                   $nCountSeq = $nCountSeq+1;
                }else{
                    $nCountSeq++;
                }
                $aDataDT[$i]['FTAgnCode']        = $tSatAgnCode;
                $aDataDT[$i]['FTBchCode']        = $tSatBchCode;
                $aDataDT[$i]['FTXshDocNo']       = $tSatDocNo;
                $aDataDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataDT[$i]['atSatQue']         = $atSatQue[$i];
                $aDataDT[$i]['FDLastUpdOn']      = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTLastUpdBy']      = $this->session->userdata("tSesUsername");
                $aDataDT[$i]['FDCreateOn']       = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTCreateBy']       = $this->session->userdata("tSesUsername");
            }

            // ข้อมูล Insert ลงตาราง  AnsDT
            for ($i=0; $i < count($atSatAns); $i++) { 
                if ($i == 0) {
                    $nCountSeq =  $this->Satisfactionsurvey_model->FSaMSATCountXsdSeq($aDataPrimaryKey);
                    $nCountSeq = $nCountSeq+1;
                 }else{
                     $nCountSeq++;
                 }
                $aDataAnsDT[$i]['FTAgnCode']        = $tSatAgnCode;
                $aDataAnsDT[$i]['FTBchCode']        = $tSatBchCode;
                $aDataAnsDT[$i]['FTXshDocNo']       = $tSatDocNo;
                $aDataAnsDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataAnsDT[$i]['atSatAns']         = $atSatAns[$i];
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob5AddDocRef = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tSatDocRefCode,
                'FDXshRefDocDate'   => $tSatDocRefDate,
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $aDatawhereJob2AddDocRef = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocRefCode,
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job5Score',
                'FTXshRefDocNo'     => $tSatDocNo,
                'FDXshRefDocDate'   => $tSatDocDate,
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DT
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateDT($aDataDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง  AnsDT
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateAnsDT($aDataAnsDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateRefDocHD($aDataJob5AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);           

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => 0,
                    'nStaEvent'     => '1',
                    'tAgnCode'      => $tSatAgnCode,
                    'tBchCode'      => $tSatBchCode,
                    'tDocNo'        => $tSatDocNo,
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //เข้าหน้าจอแก้ไข
    public function FSvCSATEditPage(){
        $tAgnCode   = $this->input->post('ptAgnCode');
        $tBchCode   = $this->input->post('ptBchCode');
        $tDocNo     = $this->input->post('ptDocNo');

        //เช็คก่อนว่ามีเอกสารนัดหมายนี้จริงๆ หรือเปล่า
        $aCheckDocNo = $this->Satisfactionsurvey_model->FSaMSATCheckDocNo($tAgnCode,$tBchCode,$tDocNo);
        if($aCheckDocNo['nStaEvent'] == 500){ //ไม่เจอข้อมูลเอกสาร
            $aDataReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => 'ไม่เจอเอกสารใบประเมิณ'
            );
            echo json_encode($aDataReturn);
        }else{
            //ดึงข้อมูล HD
            $aDataHD = $this->Satisfactionsurvey_model->FSaMSATGetDataHD($tAgnCode,$tBchCode,$tDocNo);

            //ดึงข้อมูล DT
            $tDocNo  = $aDataHD['raItems'][0]['FTXshDocNo'];
            $aDataDT = $this->Satisfactionsurvey_model->FSaMSATGetDataDT($tAgnCode,$tBchCode,$tDocNo);
                            
            $aDataAll = array(
                'aDataQA'           => $aDataDT,
                'aDataGetDetail'    => $aDataHD,
                'tRtCode'           => 1,
                'nRsCodeReturn'     => 1,
                'tRoute'            => 'docSatisfactionSurveyEventEdit'
            );
            $tViewDataTableList = $this->load->view('document/satisfactionsurvey/wSatisfactionSurveyPageAdd', $aDataAll , true);
            $aDataReturn = array(
                'tViewDataTableList'    => $tViewDataTableList,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
            echo json_encode($aDataReturn);
        }
    }

    //แก้ไขใบประเมินลง database 
    public function FSxCSATEditEvent(){
        try{
            $tSatAgnCode = $this->input->post('oetSatAgnCode');
            $tSatBchCode = $this->input->post('ohdSatBchCode');
            $tSatDocNo = $this->input->post('oetSatDocNo');

            $tStaDocAct = $this->input->post('ocbSatStaDocAct') ? 1 : 0;
            $tSatDocDate = $this->input->post('oetSatDocDate') . " " . $this->input->post('oetSatDocTime');
            $atSatAns = json_decode($this->input->post('aSatAns'), true);
            $atSatQue = json_decode($this->input->post('aSatQue'), true);
            
            $tSatRate = $this->input->post('orbSatSueveyRate');
            $tSatDocRefCode = $this->input->post('ohdSatDocRefCode');
            $tSatDocRefDate = $this->input->post('oetSatSurveyDateStaService');

            //App varsion
            $tAppVer = FCNtGetAppVersion();
            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'tTableHD'          =>'TSVTJob5ScoreHD',
                'tTableDT'          =>'TSVTJob5ScoreDT',
                'tTableAnsDT'       =>'TSVTJob5ScoreDTAns',
                'tTableDocRef5'     =>'TSVTJob5ScoreHDDocRef',
                'tTableDocRef2'     =>'TSVTJob2OrdHDDocRef'
            );
    
            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'FDXshDocDate'      => $tSatDocDate,
                'FTUsrCode'         => $this->input->post('ohdSatTaskRefUsrCode'),
                'FTXshApvCode'      => $this->input->post('ohdSatStaApvCode'),
                'FTXshRmk'          => $this->input->post('otaSatFrmInfoOthRmk'),
                'FTXshAdditional'   => $this->input->post('otaSatRmk'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => $this->input->post('ohdSatStaDoc'),
                'FTXshStaApv'       => $this->input->post('ohdSatStaApv'),
                'FNXshStaDocAct'    => $tStaDocAct,
                'FNXshScoreValue'   => $tSatRate,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => $this->input->post('ohdDateCreate'),
                'FTCreateBy'        => $this->input->post('ohdSatCreateBy')
            );

            // ข้อมูล Insert ลงตาราง DT
            $nCountSeq = 0;
            for ($i=0; $i < count($atSatQue); $i++) { 
                if ($i == 0) {
                   $nCountSeq =  $this->Satisfactionsurvey_model->FSaMSATCountXsdSeq($aDataPrimaryKey);
                   $nCountSeq = $nCountSeq+1;
                }else{
                    $nCountSeq++;
                }
                $aDataDT[$i]['FTAgnCode']        = $tSatAgnCode;
                $aDataDT[$i]['FTBchCode']        = $tSatBchCode;
                $aDataDT[$i]['FTXshDocNo']       = $tSatDocNo;
                $aDataDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataDT[$i]['atSatQue']         = $atSatQue[$i];
                $aDataDT[$i]['FDLastUpdOn']      = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTLastUpdBy']      = $this->session->userdata("tSesUsername");
                $aDataDT[$i]['FDCreateOn']       = $this->input->post('ohdDateCreate');
                $aDataDT[$i]['FTCreateBy']       = $this->input->post('ohdSatCreateBy');
            }

            // ข้อมูล Insert ลงตาราง  AnsDT
            for ($i=0; $i < count($atSatAns); $i++) { 
                if ($i == 0) {
                    $nCountSeq =  $this->Satisfactionsurvey_model->FSaMSATCountXsdSeq($aDataPrimaryKey);
                    $nCountSeq = $nCountSeq+1;
                 }else{
                     $nCountSeq++;
                 }
                $aDataAnsDT[$i]['FTAgnCode']        = $tSatAgnCode;
                $aDataAnsDT[$i]['FTBchCode']        = $tSatBchCode;
                $aDataAnsDT[$i]['FTXshDocNo']       = $tSatDocNo;
                $aDataAnsDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataAnsDT[$i]['atSatAns']         = $atSatAns[$i];
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob5AddDocRef = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tSatDocRefCode,
                'FDXshRefDocDate'   => $tSatDocRefDate,
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $aDatawhereJob2AddDocRef = array(
                'FTAgnCode'         => $this->input->post('ohdSatSvOldAgnCode'),
                'FTBchCode'         => $this->input->post('ohdSatSvOldBchCode'),
                'FTXshDocNo'        => $this->input->post('ohdSatSvOldDecRefNo')
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tSatAgnCode,
                'FTBchCode'         => $tSatBchCode,
                'FTXshDocNo'        => $tSatDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job5Score',
                'FTXshRefDocNo'     => $tSatDocNo,
                'FDXshRefDocDate'   => $tSatDocDate,
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DT
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateDT($aDataDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง  AnsDT
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateAnsDT($aDataAnsDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Satisfactionsurvey_model->FSaMSATQaAddUpdateRefDocHD($aDataJob5AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);           

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => 0,
                    'nStaEvent'     => '1',
                    'tAgnCode'      => $tSatAgnCode,
                    'tBchCode'      => $tSatBchCode,
                    'tDocNo'        => $tSatDocNo,
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //อนุมัติเอกสาร
    public function FSoCSATApproveEvent(){
        try{
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');

            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'        => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshApvCode'       => $this->session->userdata('tSesUsername')
            );

            $this->Satisfactionsurvey_model->FSaMSATApproveDocument($aDataUpdate);
            
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Success"
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ยกเลิกเอกสาร
    public function FSvCSATCancelDocument() {
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');
            $tDocRef       = $this->input->post('oetSatDocRefCode');
            
            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo
            );

            $aDataWhereDocRef = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefDocNo'     => $tDocRef
            );

            $this->Satisfactionsurvey_model->FSaMSATCancelDocument($aDataUpdate, $aDataWhereDocRef);
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Success"
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //ลบข้อมูลเอกสาร
    public function FSoCSATEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            
            $aResDelAll = $this->Satisfactionsurvey_model->FSnMSATDelDocument($aDataMaster);
            
            if ($aResDelAll['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelAll['rtCode'],
                    'tStaMessg' => $aResDelAll['rtDesc']
                );
            }
        } catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aDataStaReturn);
    }

    public function FSoCSATChkTypeAddOrUpdate(){
        $tDocNo = $this->input->post('ptDocumentJOBHD');
        $aDataWhereJob2 = $this->Satisfactionsurvey_model->FSaMSatSvGetDataWhereJob2($tDocNo);
        $aDataJob2 = $aDataWhereJob2['rtCode'];
        if ($aDataJob2 == 1) {
            $aDataFinal = array(
                'tReturn'   => $aDataJob2,// type 1 คือเจอข้อมูลวิ่งไปหน้าแก้ไข
                'tAgnCode'  => $aDataWhereJob2['aRtData'][0]['FTAgnCode'],
                'tBchCode'  => $aDataWhereJob2['aRtData'][0]['FTBchCode'],
                'tDocNo'    => $aDataWhereJob2['aRtData'][0]['FTXshDocNo']
            );
        }else{
            $aDataGetJob2 = $this->Satisfactionsurvey_model->FSaMSatSvGetDataJob2($tDocNo);
            
            $aDataFinal =  array(
                'tReturn'       => $aDataJob2, // type 2 คือไม่เจอข้อมูลวิ่งไปหน้าเพิ่ม
                'tAgnCode'      => $aDataGetJob2['aRtData'][0]['FTAgnCode'],
                'tAgnName'      => $aDataGetJob2['aRtData'][0]['FTAgnName'],
                'tBchCode'      => $aDataGetJob2['aRtData'][0]['FTBchCode'],
                'tBchName'      => $aDataGetJob2['aRtData'][0]['FTBchName'],
                'tCstCode'      => $aDataGetJob2['aRtData'][0]['FTCstCode'],
                'tCstName'      => $aDataGetJob2['aRtData'][0]['FTCstName'],
                'tCstTel'       => $aDataGetJob2['aRtData'][0]['FTCstTel'],
                'tCstEmail'     => $aDataGetJob2['aRtData'][0]['FTCstEmail'],
                'tDocNo'        => $aDataGetJob2['aRtData'][0]['FTXshDocNo'],
                'dDocDate'      => $aDataGetJob2['aRtData'][0]['FDXshDocDate'],
                'tCarBrand'     => $aDataGetJob2['aRtData'][0]['FTCarBrand'],
                'tCarModel'     => $aDataGetJob2['aRtData'][0]['FTCarModel'],
                'tCarRegNo'     => $aDataGetJob2['aRtData'][0]['FTCarRegNo'],
            );
        }
        echo json_encode($aDataFinal);
    }
}