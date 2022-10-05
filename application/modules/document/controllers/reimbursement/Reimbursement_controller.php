<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Reimbursement_controller extends MX_Controller {
    public function __construct(){
        $this->load->model('document/reimbursement/Reimbursement_model');
        $this->load->model('customer/customer/mCustomerAddress');
        parent::__construct();
    }

    public $tRouteMenu  = 'docTXOWithdraw/0/1';
    //public $tRouteMenu  = 'doCRBMorder/0/0';

    public function index($ptRoute, $ptDocCode) //แสดงส่วนหัวหน้าใบประเมินความพึงพอใจของลูกค้า ($ptRoute คือ param ของหน้าว่าเข้ามาแบบ insert หรือ view, $ptDocCode เลขที่เอกสารที่แนบมา
    {
        
        $aDataConfigView    = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave(),
            'nStaSite'          => $ptDocCode
        ];

        $this->load->view('document/reimbursement/wReimbursement',$aDataConfigView);

    }

    public function FSxCRBMPageList()
    {
        $this->load->view('document/reimbursement/wReimbursementPageList');
    }

    // แสดงตารางในหน้า List
    public function FSxCRBMDatatable() {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc($this->tRouteMenu);
            $nStaSite = $this->input->post('pnStaSite');

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
                'aAdvanceSearch' => $aAdvanceSearch,
                'nStaSite' => $nStaSite
            );
            $aDataList = $this->Reimbursement_model->FSaMRBMGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/reimbursement/wReimbursementDatable', $aConfigView, true);
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
    public function FSvCRBMEditPage()
    {
        try {
            $nLangID = $this->session->userdata("tLangEdit");
            $tAgnCode = $this->input->post('ptAgnCode');
            $tBchCode = $this->input->post('ptBchCode');
            $tDocNo = $this->input->post('ptDocNo');
            $tCstCode = $this->input->post('ptCstCode');

            //ดึงข้อมูล HD
            $aDataHD = $this->Reimbursement_model->FSaMRBMGetDataHD($tAgnCode,$tBchCode,$tDocNo);

            //ดึงข้อมูล DT
            $aDataDT = $this->Reimbursement_model->FSaMRBMGetDataDT($tAgnCode,$tBchCode,$tDocNo);

            $aSumVat = $this->Reimbursement_model->FSaMRBMGetDataSumVat($tAgnCode,$tBchCode,$tDocNo);

            $aVatRate = $this->Reimbursement_model->FSaMRBMGetDataVatRate($tAgnCode,$tBchCode,$tDocNo);

            //ที่อยู่
            $aCSTAddress  = FCNtGetAddressCustmer('TSVTSalTwoHDCst', $tCstCode, $nLangID,'FNXshAddrShip');

            //เอกสารอ้างอิงทั้งหมด
            $aAllDocRef = $this->Reimbursement_model->FSaMRBMGetAllDocRef($tAgnCode,$tBchCode,$tDocNo);

            $aDataAll = array(
                'aDataDetail'       => $aDataDT,
                'aDataGetDetail'    => $aDataHD,
                'aCSTAddress'       => $aCSTAddress,
                'aSumVat'           => $aSumVat,
                'aVatRate'          => $aVatRate,
                'aAllDocRef'        => $aAllDocRef,
                'tRtCode'           => 1,
                'nRsCodeReturn'     => 1,
                'tRoute'            => 'docTXOWithdrawEventEdit'
            );

            $this->load->view('document/reimbursement/wReimbursementPageAdd', $aDataAll);


        }catch(Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

    }

    public function FSxCRBMEditEvent() //เพิ่มใบประเมินลง database
    {
        try{
            $tSALAgnCode = $this->input->post('tAgnCode');
            $tSALBchCode = $this->input->post('tBchCode');
            $tSALDocNo = $this->input->post('tDocNo');

            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tSALAgnCode,
                'FTBchCode'         => $tSALBchCode,
                'FTXshDocNo'        => $tSALDocNo,
                'tTableHD'          =>'TSVTSalTwoHD',
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTXshRmk'          => $this->input->post('tRmk')
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Reimbursement_model->FSaMRBMQaAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

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
                    'tAgnCode'      => $tSALAgnCode,
                    'tBchCode'      => $tSALBchCode,
                    'tDocNo'        => $tSALDocNo,
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
    public function FSxCRBMAddEventDocExt($tDocRefExt,$tDocRefExtDate,$tSALAgnCode,$tSALBchCode,$tSALDocNo,$nStausEditDocRef)
    {
        try{
            // ข้อมูล Insert ลงตาราง DocRef ของ Job5
            $aDataJob4AddDocRef = array(
                'FTAgnCode'         => $tSALAgnCode,
                'FTBchCode'         => $tSALBchCode,
                'FTXshDocNo'        => $tSALDocNo,
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'Job4Apv',
                'FTXshRefDocNo'     => $tDocRefExt,
                'FDXshRefDocDate'   => $tDocRefExtDate,
            );

            $this->Reimbursement_model->FSaMRBMQaAddUpdateRefDocExtHD($aDataJob4AddDocRef,$tSALAgnCode,$tSALBchCode,$tSALDocNo,$nStausEditDocRef);
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
    public function FSvCRBMCancelDocument() {
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');
            $tDocRef       = $this->input->post('oetSALDocRefCode');

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

            $this->Reimbursement_model->FSaMRBMCancelDocument($aDataUpdate, $aDataWhereDocRef);
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
    public function FSoCRBMEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tSALAgnCode = $this->input->post('tSALAgnCode');
            $tSALBchCode = $this->input->post('tSALBchCode');
            $SALDocRefCode = $this->input->post('tSALDocRefCode');

            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo,
                'tSALAgnCode' => $tSALAgnCode,
                'tSALBchCode' => $tSALBchCode,
                'tSALDocRefCode' => $SALDocRefCode
            );

            $aResDelAll = $this->Reimbursement_model->FSnMRBMDelDocument($aDataMaster);

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

    public function FSoCRBMChkTypeAddOrUpdate()
    {
        $tDocNo = $this->input->post('ptDocumentSALHD');
        $aDataWhereJob2 = $this->Reimbursement_model->FSaMSatSvGetDataWhereJob2($tDocNo);
        $aDataJob2 = $aDataWhereJob2['rtCode'];
        if ($aDataJob2 == 1) {
            $aDataFinal = array(
                'tReturn'   => $aDataJob2,// type 1 คือเจอข้อมูลวิ่งไปหน้าแก้ไข
                'tAgnCode'  => $aDataWhereJob2['aRtData'][0]['FTAgnCode'],
                'tBchCode'  => $aDataWhereJob2['aRtData'][0]['FTBchCode'],
                'tDocNo'    => $aDataWhereJob2['aRtData'][0]['FTXshDocNo']
            );
        }else{
            $aDataGetJob2 = $this->Reimbursement_model->FSaMSatSvGetDataJob2($tDocNo);

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
