<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Joborder_controller extends MX_Controller {
    public function __construct(){
        $this->load->model('document/joborder/Joborder_model');
        $this->load->model('customer/customer/mCustomerAddress');
        parent::__construct();
    }

    public $tRouteMenu  = 'docJob/0/0';
    //public $tRouteMenu  = 'docJoborder/0/0';

    public function index($ptRoute, $ptDocCode) //แสดงส่วนหัวหน้าใบประเมินความพึงพอใจของลูกค้า ($ptRoute คือ param ของหน้าว่าเข้ามาแบบ insert หรือ view, $ptDocCode เลขที่เอกสารที่แนบมา
    {
        $aDataConfigView    = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'tCheckJump'    => $this->input->get('ptTypeJump'),
        ];

        $this->load->view('document/joborder/wJoborder',$aDataConfigView);

    }

    public function FSxCJOBPageList()
    {
        $this->load->view('document/joborder/wJoborderPageList');
    }

    // แสดงตารางในหน้า List
    public function FSxCJOBDatatable() {
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
            $aDataList = $this->Joborder_model->FSaMJOBGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/joborder/wJoborderDatable', $aConfigView, true);
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

    //หน้าจอแก้ไข
    public function FSvCJOBEditPage()
    {
        try {
            $nLangID = $this->session->userdata("tLangEdit");
            $tAgnCode = $this->input->post('ptAgnCode');
            $tBchCode = $this->input->post('ptBchCode');
            $tDocNo = $this->input->post('ptDocNo');
            $tCstCode = $this->input->post('ptCstCode');

            //ดึงข้อมูล HD
            $aDataHD = $this->Joborder_model->FSaMJOBGetDataHD($tAgnCode,$tBchCode,$tDocNo);

            //ดึงข้อมูล DT
            $aDataDT = $this->Joborder_model->FSaMJOBGetDataDT($tAgnCode,$tBchCode,$tDocNo);

            $aSumVat = $this->Joborder_model->FSaMJOBGetDataSumVat($tAgnCode,$tBchCode,$tDocNo);

            $aVatRate = $this->Joborder_model->FSaMJOBGetDataVatRate($tAgnCode,$tBchCode,$tDocNo);

            //ที่อยู่
            $aCSTAddress  = FCNtGetAddressCustmer('TSVTJob2OrdHDCst', $tCstCode, $nLangID,'FNXshAddrShip');

            //เอกสารอ้างอิงทั้งหมด
            $aAllDocRef = $this->Joborder_model->FSaMJOBGetAllDocRef($tAgnCode,$tBchCode,$tDocNo);

            // หาว่าเอกสารใบสั่งงานใบนี้ ถูกอ้างหรือยัง
            $aFindDocNoUse = $this->Joborder_model->FSxMJOBFindDocNoUse($tDocNo);
            if(empty($aFindDocNoUse)){
                $tStaFindDocNoUse = 0; //ยังไม่เคยอ้างอิงใบสั่งงาน : อนุมัติเเล้วก็ยกเลิกได้
            }else{
                $tStaFindDocNoUse = 1; //ถูกอ้างอิงเเล้ว : ยกเลิกไม่ได้
            }

            $aDataAll = array(
                'aDataDetail'               => $aDataDT,
                'aDataGetDetail'            => $aDataHD,
                'aCSTAddress'               => $aCSTAddress,
                'aSumVat'                   => $aSumVat,
                'aVatRate'                  => $aVatRate,
                'aAllDocRef'                => $aAllDocRef,
                'tRtCode'                   => 1,
                'nRsCodeReturn'             => 1,
                'nOptDecimalShow'           => FCNxHGetOptionDecimalShow(),
                'tRoute'                    => 'docJOBEventEdit',
                'tStaFindDocNoUse'          => $tStaFindDocNoUse
            );

            $this->load->view('document/joborder/wJoborderPageAdd', $aDataAll);


        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

    }

    public function FSxCJOBEditEvent() //เพิ่มใบประเมินลง database
    {
        try{
            $tJOBAgnCode = $this->input->post('tAgnCode');
            $tJOBBchCode = $this->input->post('tBchCode');
            $tJOBDocNo = $this->input->post('tDocNo');

            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tJOBAgnCode,
                'FTBchCode'         => $tJOBBchCode,
                'FTXshDocNo'        => $tJOBDocNo,
                'tTableHD'          =>'TSVTJob2OrdHD',
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTXshRmk'          => $this->input->post('tRmk')
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Joborder_model->FSaMJOBQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

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
                    'tAgnCode'      => $tJOBAgnCode,
                    'tBchCode'      => $tJOBBchCode,
                    'tDocNo'        => $tJOBDocNo,
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

    //เพิ่มเอกสารอ้างอิงจากภายนอก
    public function FSxCJOBAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tJOBAgnCode,$tJOBBchCode,$tJOBDocNo,$nStausEditDocRef)
    {
        try{
            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob4AddDocRef = array(
                'FTAgnCode'         => $tJOBAgnCode,
                'FTBchCode'         => $tJOBBchCode,
                'FTXshDocNo'        => $tJOBDocNo,
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'Job4Apv',
                'FTXshRefDocNo'     => $tDocRefExt,
                'FDXshRefDocDate'   => $tDocRefExtDate,
            );

            $this->Joborder_model->FSaMJOBQaAddUpdateRefDocExtHD($aDataJob4AddDocRef,$tJOBAgnCode,$tJOBBchCode,$tJOBDocNo,$nStausEditDocRef);
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
        return $aReturnData;
    }

    // ยกเลิกเอกสาร
    public function FSvCJOBCancelDocument() {
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');

            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo
            );

            $aDataWhereDocRef = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
            );

            $this->Joborder_model->FSaMJOBCancelDocument($aDataUpdate, $aDataWhereDocRef);

            $aMQParams = [
                "tVhostType" => "S",
                "queueName" => "SALEPOSJOB2ORD",
                "params"    => [
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptXihDocNo"    => $tDocNo
                    ])
                ]
            ];
            FCNxCallRabbitMQ($aMQParams);

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
    public function FSoCJOBEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tJOBAgnCode = $this->input->post('tJOBAgnCode');
            $tJOBBchCode = $this->input->post('tJOBBchCode');
            $JOBDocRefCode = $this->input->post('tJOBDocRefCode');

            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tJOBAgnCode' => $tJOBAgnCode,
                'tJOBBchCode' => $tJOBBchCode,
                'tJOBDocRefCode' => $JOBDocRefCode
            );

            $aResDelAll = $this->Joborder_model->FSnMJOBDelDocument($aDataMaster);

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

    public function FSoCJOBChkTypeAddOrUpdate()
    {
        $tDocNo = $this->input->post('ptDocumentJOBHD');
        $aDataWhereJob2 = $this->satisfactionsurvey_model->FSaMSatSvGetDataWhereJob2($tDocNo);
        $aDataJob2 = $aDataWhereJob2['rtCode'];
        if ($aDataJob2 == 1) {
            $aDataFinal = array(
                'tReturn'   => $aDataJob2,// type 1 คือเจอข้อมูลวิ่งไปหน้าแก้ไข
                'tAgnCode'  => $aDataWhereJob2['aRtData'][0]['FTAgnCode'],
                'tBchCode'  => $aDataWhereJob2['aRtData'][0]['FTBchCode'],
                'tDocNo'    => $aDataWhereJob2['aRtData'][0]['FTXshDocNo']
            );
        }else{
            $aDataGetJob2 = $this->satisfactionsurvey_model->FSaMSatSvGetDataJob2($tDocNo);

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
