<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Dailyworkorder_controller extends MX_Controller {
    public function __construct(){
        $this->load->model('document/dailyworkorder/Dailyworkorder_model');
        parent::__construct();
    }

    public $tRouteMenu  = 'docDWO/0/0';

    public function index($ptRoute, $ptDocCode)
    {   
        $aDataConfigView    = [
            'aAlwEvent'         => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        ];

        $this->load->view('document/dailyworkorder/wDailyWorkOrder',$aDataConfigView);
    }

    // Functionality : Function Call Search List
    // Parameters : Ajax and Function Parameter
    // Creator : 30/09/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCDWOSearchList() {
        $this->load->view('document/dailyworkorder/wDailyWorkOrderSearchMonitor');
    }

    // Functionality : แสดงตารางในหน้า List
    // Parameters : Ajax and Function Parameter
    // Creator : 30/09/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
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
            $aDataList = $this->Dailyworkorder_model->FSaMSatSvGetDataTableList($aDataCondition);
            
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

    // Functionality : แสดงตารางในหน้า ข้อมูลภายใน Bay
    // Parameters : Ajax and Function Parameter
    // Creator : 30/09/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCDWOPageMonitor()
    {   
        try {
            $nReturntype = $this->input->post('pnType');
            $dDateSerach = $this->input->post('dDate');
            if($this->input->post('dDate') == ''){
                $dDateSerach = date("Y-m-d");
            }
            
            $aDataWhere = array(
                'FDXshDocDate' => $dDateSerach,
                'FTBchCode' => $this->input->post('tBchCode'),
                'FTXshStaDoc' => $this->input->post('nStatus'),
                'FTDptCode' => $this->input->post('tDepart')
            );
           
            $aDataBAY = $this->Dailyworkorder_model->FSaMDWOGetBayDetail($aDataWhere);
            $aDataFinal = array(
                'tReturn' => 1
            );

            $aDataAll = array(
                'aDataBAY'           => $aDataBAY,
                'aDataGetDetail'    => $aDataFinal,
                'tRoute'            => 'docSatisfactionSurveyEventAdd'
            );
            
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        
        $this->load->view('document/dailyworkorder/wDailyWorkOrderMonitor', $aDataAll);
    } 
}