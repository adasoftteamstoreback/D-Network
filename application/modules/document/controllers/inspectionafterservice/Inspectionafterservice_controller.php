<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Inspectionafterservice_controller extends MX_Controller {
    public function __construct(){
        $this->load->model('document/inspectionafterservice/Inspectionafterservice_model');
        parent::__construct();
    }

    public $tRouteMenu  = 'docIAS/0/0';

    public function index($ptRoute, $ptDocCode) 
    {   
        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
            'tCheckJump'  => $this->input->get('ptTypeJump'),
        );

        $aDataConfigView    = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParamsWeView'     => $aParams
        ];
        $this->load->view('document/inspectionafterservice/wInspectionafterservice',$aDataConfigView);
        unset($aDataConfigView);
        unset($aParams);
    }

    public function FSxCIASPageList(){
        $this->load->view('document/inspectionafterservice/wInspectionafterservicePageList');
    }

    // แสดงตารางในหน้า List
    public function FSxCIASDatatable() {
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
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'aAdvanceSearch'        => $aAdvanceSearch
            );
            $aDataList = $this->Inspectionafterservice_model->FSaMIASGetDataTableList($aDataCondition);
            
            $aConfigView = array(
                'nPage'             => $nPage,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/inspectionafterservice/wInspectionafterserviceDatable', $aConfigView, true);
            $aReturnData = array(
                'tViewDataTable'    => $tViewDataTable,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);

        unset($aAdvanceSearch);
        unset($nPage);
        unset($aAlwEvent);
        unset($nLangEdit);
        unset($aDataCondition);
        unset($aDataList);
        unset($aConfigView);
        unset($tViewDataTable);
    }

    public function FSvCIASAddPage() //แสดงส่วนรายละเอียดภายในใบประเมินความพึงพอใจของลูกค้า
    {   
        try {
            $nReturntype = $this->input->post('pnType');
            if ($nReturntype == 1) {
                $aDataQA = $this->Inspectionafterservice_model->FSaMIASQaViewAnswer();
                $aDataFinal = array(
                    'tReturn' => 1
                );

                $aDataAll = array(
                    'aDataQA'           => $aDataQA,
                    'aDataGetDetail'    => $aDataFinal,
                    'tRoute'            => 'docIASEventAdd'
                );
                unset($aDataQA);
                unset($aDataFinal);
            }
            
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        $this->load->view('document/inspectionafterservice/wInspectionafterservicePageAdd', $aDataAll);
        unset($nReturntype);
        unset($aDataAll);
    }

    //เพิ่มข้อมูล
    public function FSxCIASAddEvent(){
        try{
            $tIASAgnCode        = $this->input->post('oetIASAgnCode');
            $tIASBchCode        = $this->input->post('ohdIASBchCode');
            $tIASDocNo          = $this->input->post('oetIASDocNo');
            $tIASAutoGenCode    =  $this->input->post('ocbIASStaAutoGenCode') ? 1 : 0;
            $tStaDocAct         = $this->input->post('ocbIASStaDocAct') ? 1 : 0;
            $tIASDocDate        = $this->input->post('oetIASDocDate') . " " . $this->input->post('oetIASDocTime');
            $tIASStartChk       = $this->input->post('oetIASDocDateBegin') . " " . $this->input->post('oetIASDocTimeBegin');
            $tIASFinishChk      = $this->input->post('oetIASDocDateEnd') . " " . $this->input->post('oetIASDocTimeEnd');
            $atIASAns           = json_decode($this->input->post('aIASAns'), true);
            $atIASQue           = json_decode($this->input->post('aIASQue'), true);
            $tIASRate           = $this->input->post('orbIASSueveyRate');
            $tIASDocRefCode     = $this->input->post('ohdIASDocRefCode');
            $tIASDocRefDate     = $this->input->post('oetIASDateStaService');

            //App varsion
            // $tAppVer = FCNtGetAppVersion();
            $tAppVer = 'SB';
    
            // Check Auto GenCode Document
            if ($tIASAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TSVTJob4ApvHD',                           
                    "tDocType"    => '1',                                          
                    "tBchCode"    => $tIASBchCode,                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d H:i:s")       
                );
                
                $aAutogen    = FCNaHAUTGenDocNo($aStoreParam);
                $tIASDocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $tIASDocNo   = $tIASDocNo;
            }
    
            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'tTableHD'          =>'TSVTJob4ApvHD',
                'tTableDT'          =>'TSVTJob4ApvDT',
                'tTableAnsDT'       =>'TSVTJob4ApvDTAns',
                'tTableDocRef4'     =>'TSVTJob4ApvHDDocRef',
                'tTableDocRef2'     =>'TSVTJob2OrdHDDocRef'
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FDXshDocDate'      => $tIASDocDate,
                'FTUsrCode'         => $this->input->post('ohdIASTaskRefUsrCode'),
                'FTXshApvCode'      => '',
                'FTXshRmk'          => $this->input->post('otaIASFrmInfoOthRmk'),
                'FTXshAdditional'   => $this->input->post('otaIASRmk'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => 1,
                'FTXshStaApv'       => '',
                'FNXshStaDocAct'    => $tStaDocAct,
                'FNXshScoreValue'   => $tIASRate,
                'FDXshStartChk'     => $tIASStartChk,
                'FDXshFinishChk'    => $tIASFinishChk,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata("tSesUsername")
            );

            // ข้อมูล Insert ลงตาราง DT
            $nCountSeq = 0;
            for ($i=0; $i < count($atIASQue); $i++) { 
                if ($i == 0) {
                   $nCountSeq =  $this->Inspectionafterservice_model->FSaMIASCountXsdSeq($aDataPrimaryKey);
                   $nCountSeq = $nCountSeq+1;
                }else{
                    $nCountSeq++;
                }
                $aDataDT[$i]['FTAgnCode']        = $tIASAgnCode;
                $aDataDT[$i]['FTBchCode']        = $tIASBchCode;
                $aDataDT[$i]['FTXshDocNo']       = $tIASDocNo;
                $aDataDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataDT[$i]['atIASQue']         = $atIASQue[$i];
                $aDataDT[$i]['FDLastUpdOn']      = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTLastUpdBy']      = $this->session->userdata("tSesUsername");
                $aDataDT[$i]['FDCreateOn']       = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTCreateBy']       = $this->session->userdata("tSesUsername");
            }

            // ข้อมูล Insert ลงตาราง  AnsDT
            for ($i=0; $i < count($atIASAns); $i++) { 
                if ($i == 0) {
                    $nCountSeq =  $this->Inspectionafterservice_model->FSaMIASCountXsdSeq($aDataPrimaryKey);
                    $nCountSeq = $nCountSeq+1;
                 }else{
                     $nCountSeq++;
                 }
                $aDataAnsDT[$i]['FTAgnCode']        = $tIASAgnCode;
                $aDataAnsDT[$i]['FTBchCode']        = $tIASBchCode;
                $aDataAnsDT[$i]['FTXshDocNo']       = $tIASDocNo;
                $aDataAnsDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataAnsDT[$i]['atIASAns']         = $atIASAns[$i];
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob4AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tIASDocRefCode,
                'FDXshRefDocDate'   => $tIASDocRefDate,
            );

            $tDocRefExt         = $this->input->post('oetIASDocRefExtCode');
            $tDocRefExtDate     = $this->input->post('oetIASDocRefExtDate');
            $nStausEditDocRef   = '';
            
            if ($tDocRefExt != '') {
                $nStausEditDocRef = 1;
                $this->FSxCIASAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef);
            }else{
                $nStausEditDocRef = 0;
                $tDocRefExt = $this->input->post('ohdIASDocRefExtCode');
                $this->FSxCIASAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef);
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $aDatawhereJob2AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocRefCode,
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job4Apv',
                'FTXshRefDocNo'     => $tIASDocNo,
                'FDXshRefDocDate'   => $tIASDocDate,
            );

            $aDatawhereJob4DocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tIASDocRefCode,
                'FDXshRefDocDate'   => $tIASDocRefDate,
            );

            //อัพเดทเลขไมล์ กลับไปที่ใบสั่งงาน 
            $aUpdateJOB2AndJOB1 = array(
                'FCXshCarMileage'   => floatval(str_replace(',','',$this->input->post('oetIASCarMileAge'))),
                'tDocRefNo'         => $tIASDocRefCode,
                'tBchRef'           => $tIASBchCode
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
            $this->Inspectionafterservice_model->FSaMIASQaUpdateJOB2HD($aUpdateJOB2AndJOB1);

            // ข้อมูล Insert ลงตาราง DT
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateDT($aDataDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง  AnsDT
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateAnsDT($aDataAnsDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateRefDocHD($aDataJob4AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey, $aDatawhereJob4DocRef);           

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
                    'tAgnCode'      => $tIASAgnCode,
                    'tBchCode'      => $tIASBchCode,
                    'tDocNo'        => $tIASDocNo,
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

        unset($tIASAgnCode);
        unset($tIASBchCode);
        unset($tIASDocNo);
        unset($tIASAutoGenCode);
        unset($tStaDocAct);
        unset($tIASDocDate);
        unset($tIASStartChk);
        unset($tIASFinishChk);
        unset($atIASAns);
        unset($atIASQue);
        unset($tIASRate);
        unset($tIASDocRefCode);
        unset($tIASDocRefDate);
        unset($tAppVer);
        unset($aDataPrimaryKey);
        unset($aDataAddHD);
        unset($nCountSeq);
        unset($aDataDT);
        unset($aDataAnsDT);
        unset($aDataJob4AddDocRef);
        unset($tDocRefExt);
        unset($tDocRefExtDate);
        unset($nStausEditDocRef);
        unset($aDatawhereJob2AddDocRef);
        unset($aDataJob2AddDocRef);
        unset($aDatawhereJob4DocRef);
        unset($aUpdateJOB2AndJOB1);
  
    }

    //หน้าจอแก้ไข
    public function FSvCIASEditPage(){
        try {
            $tAgnCode = $this->input->post('ptAgnCode');
            $tBchCode = $this->input->post('ptBchCode');
            $tDocNo = $this->input->post('ptDocNo');

            //เช็คก่อนว่ามีเอกสารนัดหมายนี้จริงๆ หรือเปล่า
            $aCheckDocNo = $this->Inspectionafterservice_model->FSaMIASCheckDocNo($tAgnCode,$tBchCode,$tDocNo);
            
            if($aCheckDocNo['nStaEvent'] == 500){ //ไม่เจอข้อมูลเอกสาร
                $aDataReturn = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'ไม่เจอเอกสารใบประเมิณ'
                );
                echo json_encode($aDataReturn);
            }else{
                //ดึงข้อมูล HD
                $aDataHD = $this->Inspectionafterservice_model->FSaMIASGetDataHD($tAgnCode,$tBchCode,$tDocNo);

                //ดึงข้อมูล DT
                $tDocNo  = $aDataHD['raItems'][0]['FTXshDocNo'];
                $aDataDT = $this->Inspectionafterservice_model->FSaMIASGetDataDT($tAgnCode,$tBchCode,$tDocNo);

                $aDataAll = array(
                    'aDataQA'           => $aDataDT,
                    'aDataGetDetail'    => $aDataHD,
                    'tRtCode'           => 1,
                    'nRsCodeReturn'     => 1,
                    'tRoute'            => 'docIASEventEdit'
                );            

                $tViewDataTableList = $this->load->view('document/inspectionafterservice/wInspectionafterservicePageAdd', $aDataAll, true);

                $aDataReturn = array(
                    'tViewDataTableList'    => $tViewDataTableList,
                    'nStaEvent'             => '1',
                    'tStaMessg'             => 'Success'
                );
                echo json_encode($aDataReturn);
            }
        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($aCheckDocNo);
    }

    //แก้ไขใบประเมินลง database 
    public function FSxCIASEditEvent(){
        try{
            
            $tIASAgnCode    = $this->input->post('oetIASAgnCode');
            $tIASBchCode    = $this->input->post('ohdIASBchCode');
            $tIASDocNo      = $this->input->post('oetIASDocNo');
            $tStaDocAct     = $this->input->post('ocbIASStaDocAct') ? 1 : 0;
            $tIASDocDate    = $this->input->post('oetIASDocDate') . " " . $this->input->post('oetIASDocTime');
            $tIASStartChk   = $this->input->post('oetIASDocDateBegin') . " " . $this->input->post('oetIASDocTimeBegin');
            $tIASFinishChk  = $this->input->post('oetIASDocDateEnd') . " " . $this->input->post('oetIASDocTimeEnd');
            $atIASAns       = json_decode($this->input->post('aIASAns'), true);
            $atIASQue       = json_decode($this->input->post('aIASQue'), true);
            $tIASRate       = $this->input->post('orbIASSueveyRate');
            $tIASDocRefCode = $this->input->post('ohdIASDocRefCode');
            $tIASDocRefDate = $this->input->post('oetIASDateStaService');

            //App varsion
            // $tAppVer = FCNtGetAppVersion();
            $tAppVer = 'SB';
    
            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'tTableHD'          =>'TSVTJob4ApvHD',
                'tTableDT'          =>'TSVTJob4ApvDT',
                'tTableAnsDT'       =>'TSVTJob4ApvDTAns',
                'tTableDocRef4'     =>'TSVTJob4ApvHDDocRef',
                'tTableDocRef2'     =>'TSVTJob2OrdHDDocRef'
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FDXshDocDate'      => $tIASDocDate,
                'FTUsrCode'         => $this->input->post('ohdIASTaskRefUsrCode'),
                'FTXshApvCode'      => '',
                'FTXshRmk'          => $this->input->post('otaIASFrmInfoOthRmk'),
                'FTXshAdditional'   => $this->input->post('otaIASRmk'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => $this->input->post('ohdIASStaDoc'),
                'FTXshStaApv'       => $this->input->post('ohdIASStaApv'),
                'FNXshStaDocAct'    => $tStaDocAct,
                'FNXshScoreValue'   => $tIASRate,
                'FDXshStartChk'     => $tIASStartChk,
                'FDXshFinishChk'    => $tIASFinishChk,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => $this->input->post('ohdIASCreateOn'),
                'FTCreateBy'        => $this->input->post("ohdIASCreateBy")
            );
    
            // ข้อมูล Insert ลงตาราง DT
            $nCountSeq = 0;
            for ($i=0; $i < count($atIASQue); $i++) { 
                if ($i == 0) {
                   $nCountSeq =  $this->Inspectionafterservice_model->FSaMIASCountXsdSeq($aDataPrimaryKey);
                   $nCountSeq = $nCountSeq+1;
                }else{
                    $nCountSeq++;
                }
                $aDataDT[$i]['FTAgnCode']        = $tIASAgnCode;
                $aDataDT[$i]['FTBchCode']        = $tIASBchCode;
                $aDataDT[$i]['FTXshDocNo']       = $tIASDocNo;
                $aDataDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataDT[$i]['atIASQue']         = $atIASQue[$i];
                $aDataDT[$i]['FDLastUpdOn']      = date('Y-m-d H:i:s');
                $aDataDT[$i]['FTLastUpdBy']      = $this->session->userdata("tSesUsername");
                $aDataDT[$i]['FDCreateOn']       = $this->input->post('ohdIASCreateOn');
                $aDataDT[$i]['FTCreateBy']       = $this->input->post("ohdIASCreateBy");
            }

            // ข้อมูล Insert ลงตาราง  AnsDT
            for ($i=0; $i < count($atIASAns); $i++) { 
                if ($i == 0) {
                    $nCountSeq =  $this->Inspectionafterservice_model->FSaMIASCountXsdSeq($aDataPrimaryKey);
                    $nCountSeq = $nCountSeq+1;
                 }else{
                     $nCountSeq++;
                 }
                $aDataAnsDT[$i]['FTAgnCode']        = $tIASAgnCode;
                $aDataAnsDT[$i]['FTBchCode']        = $tIASBchCode;
                $aDataAnsDT[$i]['FTXshDocNo']       = $tIASDocNo;
                $aDataAnsDT[$i]['FTXsdSeq']         = $nCountSeq;
                $aDataAnsDT[$i]['atIASAns']         = $atIASAns[$i];
            }

            $tDocRefExt = $this->input->post('oetIASDocRefExtCode');
            $tDocRefExtDate = $this->input->post('oetIASDocRefExtDate');
            $nStausEditDocRef = '';
            
            if ($tDocRefExt != '') {
                $nStausEditDocRef = 1;
                $this->FSxCIASAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef);
            }else{
                $nStausEditDocRef = 0;
                $tDocRefExt = $this->input->post('ohdIASDocRefExtCode');
                $this->FSxCIASAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef);
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $tDocRefOld = $this->input->post('ohdIASOldDocRefCode');
            if ($tDocRefOld == $tIASDocRefCode || $tDocRefOld != $tIASDocRefCode  ) {
                $aDatawhereJob2AddDocRef = array(
                    'FTAgnCode'         => $tIASAgnCode,
                    'FTBchCode'         => $tIASBchCode,
                    'FTXshDocNo'        => $tDocRefOld,
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $tIASDocNo
                );

                $aDatawhereJob4DocRef = array(
                    'FTAgnCode'         => $tIASAgnCode,
                    'FTBchCode'         => $tIASBchCode,
                    'FTXshDocNo'        => $tIASDocNo,
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $tDocRefOld
                );
            }else{
                $aDatawhereJob2AddDocRef = array();
                $aDatawhereJob4DocRef = array();
            }

            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob4AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tIASDocRefCode,
                'FDXshRefDocDate'   => $tIASDocRefDate,
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job4Apv',
                'FTXshRefDocNo'     => $tIASDocNo,
                'FDXshRefDocDate'   => $tIASDocDate,
            );

            //อัพเดทเลขไมล์ กลับไปที่ใบสั่งงาน 
            $aUpdateJOB2AndJOB1 = array(
                'FCXshCarMileage'   => floatval(str_replace(',','',$this->input->post('oetIASCarMileAge'))),
                'tDocRefNo'         => $tIASDocRefCode,
                'tBchRef'           => $tIASBchCode
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
            $this->Inspectionafterservice_model->FSaMIASQaUpdateJOB2HD($aUpdateJOB2AndJOB1);

            // ข้อมูล Insert ลงตาราง DT
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateDT($aDataDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง  AnsDT
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateAnsDT($aDataAnsDT, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateRefDocHD($aDataJob4AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey, $aDatawhereJob4DocRef);           

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
                    'tAgnCode'      => $tIASAgnCode,
                    'tBchCode'      => $tIASBchCode,
                    'tDocNo'        => $tIASDocNo,
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
        unset($tIASAgnCode);
        unset($tIASBchCode);
        unset($tIASDocNo);
        unset($tStaDocAct);
        unset($tIASDocDate);
        unset($tIASStartChk);
        unset($tIASFinishChk);
        unset($atIASAns);
        unset($atIASQue);
        unset($tIASRate);
        unset($tIASDocRefCode);
        unset($tIASDocRefDate);
        unset($tAppVer);
        unset($aDataPrimaryKey);
        unset($aDataAddHD);
        unset($nCountSeq);
        unset($aDataDT);
        unset($aDataAnsDT);
        unset($aDataJob4AddDocRef);
        unset($tDocRefExt);
        unset($tDocRefExtDate);
        unset($nStausEditDocRef);
        unset($aDatawhereJob2AddDocRef);
        unset($aDataJob2AddDocRef);
        unset($aDatawhereJob4DocRef);
        unset($aUpdateJOB2AndJOB1);
    }

    //เพิ่มเอกสารอ้างอิงจากภายนอก
    public function FSxCIASAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef){
        try{  
            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob4AddDocRef = array(
                'FTAgnCode'         => $tIASAgnCode,
                'FTBchCode'         => $tIASBchCode,
                'FTXshDocNo'        => $tIASDocNo,
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'Job4Apv',
                'FTXshRefDocNo'     => $tDocRefExt,
                'FDXshRefDocDate'   => $tDocRefExtDate,
            );

            $this->Inspectionafterservice_model->FSaMIASQaAddUpdateRefDocExtHD($aDataJob4AddDocRef,$tIASAgnCode,$tIASBchCode,$tIASDocNo,$nStausEditDocRef);
            $aReturnData = array(
                'nStaEvent'     => '1',
                'tStaMessg'     => 'Success Add Document.'
            );
        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        unset($aDataJob4AddDocRef);
        return $aReturnData;
    }

    //อนุมัติเอกสาร
    public function FSoCIASApproveEvent(){
        try{
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');
            $tDocJOB2      = $this->input->post('tDocJOB2');

            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'        => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshApvCode'       => $this->session->userdata('tSesUsername'),
                'tDocJOB2'          => $tDocJOB2
            );
            //ต้องกลับไปหา ใบบันทึกผลตรวจเช็คสภาพรถ ว่าใช่ใบสั่งงานใบไหน
            $this->Inspectionafterservice_model->FSaMIASCheckJOB3Approve($aDataUpdate);

            $this->Inspectionafterservice_model->FSaMIASApproveDocument($aDataUpdate);
            
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
        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tDocJOB2);
        unset($aDataUpdate);
    }

    //ยกเลิกเอกสาร
    public function FSvCIASCancelDocument() {
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');
            $tDocRef       = $this->input->post('oetIASDocRefCode');
            
            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo
            );

            $aDataWhereDocRef = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshRefDocNo'     => $tDocRef
            );

            $this->Inspectionafterservice_model->FSaMIASCancelDocument($aDataUpdate, $aDataWhereDocRef);
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
        unset($tAgnCode);
        unset($tBchCode);
        unset($tDocNo);
        unset($tDocRef);
        unset($aDataUpdate);
        unset($aDataWhereDocRef);
    }

    //ลบข้อมูลเอกสาร
    public function FSoCIASEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tIASAgnCode = $this->input->post('tIASAgnCode');
            $tIASBchCode = $this->input->post('tIASBchCode');
            $IASDocRefCode = $this->input->post('tIASDocRefCode');
           
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tIASAgnCode' => $tIASAgnCode,
                'tIASBchCode' => $tIASBchCode,
                'tIASDocRefCode' => $IASDocRefCode,
                'nDelMulti' => is_array($tDataDocNo)
            );
            
            $aResDelAll = $this->Inspectionafterservice_model->FSnMIASDelDocument($aDataMaster);
            
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
        unset($tDataDocNo);
        unset($tIASAgnCode);
        unset($tIASBchCode);
        unset($IASDocRefCode);
        unset($aDataMaster);
        unset($aResDelAll);
    }

    //ค้นหาข้อมูลรถ
    public function FSaCIASFindCar(){
        $poItem     = json_decode($this->input->post('poItem'));
        $nLangEdit  = $this->session->userdata("tLangEdit");

        // Codition Where
        $aDataWhere = [
            'tCarCstCode'   => $poItem[0],
            'nLangEdit'     => $nLangEdit,
        ];
        $aDataReturn    = [
            'aDataCarCst' => $this->Inspectionafterservice_model->FSaMInsGetDataCarCustomer($aDataWhere),
        ];
        echo json_encode($aDataReturn);
        unset($poItem);
        unset($nLangEdit);
        unset($aDataWhere);
        unset($aDataReturn);
    }

    //เช็คข้อมูลจาาก Jump จากหน้า JobOrder
    public function FSoCIASChkTypeAddOrUpdate(){
        $tDocNo         = $this->input->post('ptDocNo');
        $aDataWhereJob2 = $this->Inspectionafterservice_model->FSaMIASGetDataWhereJob2($tDocNo);
        $nStaFoundData  = $aDataWhereJob2['rtCode'];
        $aDataJob2      = $aDataWhereJob2['aRtData'];
        $aDataFinal =  array(
            'tReturn'   => $nStaFoundData,
            'aRtData'   => $aDataJob2
        );
        echo json_encode($aDataFinal);
        unset($tDocNo);
        unset($aDataWhereJob2);
        unset($nStaFoundData);
        unset($aDataJob2);
        unset($aDataFinal);
    }

    //หาว่าเอกสารใบสั่งงาน ใช้รถ หรือลูกค้าคนไหน
    public function FSaCIASRefJobOrder(){
        $tBchCode   = $this->input->post('tBchCode');
        $tDocNo     = $this->input->post('tDocNo');
        $aResult    = $this->Inspectionafterservice_model->FSaMIASDataWhereJobOrder($tBchCode,$tDocNo);
        if($aResult['rtCode'] == 1){
            $aReturn =  array(
                'nStatus'       => '1',
                'aResultdata'   => $aResult['rtData'][0]
            );
        }else{
            $aReturn =  array(
                'nStatus'       => '0',
                'aResultdata'   => array()
            );
        }

        echo json_encode($aReturn);
        unset($tBchCode);
        unset($tDocNo);
        unset($aResult);
    }
}