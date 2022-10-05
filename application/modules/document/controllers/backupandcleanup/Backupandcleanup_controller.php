<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backupandcleanup_controller extends MX_Controller
{
    public function __construct()
    {
        $this->load->model('document/backupandcleanup/Backupandcleanup_model');
        parent::__construct();
    }

    //public $tRouteMenu  = 'product/0/0';
    public $tRouteMenu  = 'docBackupCleanup/0/0';

    public function index($ptRoute, $ptDocCode) //แสดงส่วนหัวหน้าการสำรองและล้างข้อมูล
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
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParams'           => $aParams
        ];

        $this->load->view('document/backupandcleanup/wBackupAndCleanup', $aDataConfigView);
    }

    public function FSvCBACUAddPage() //แสดงส่วนรายละเอียดการสำรองและล้างข้อมูล
    {
        try {
            $nReturntype = $this->input->post('pnType');
            if ($nReturntype == 1) {
                // $aDataQA = $this->Backupandcleanup_model->FSaMBACUGetData();
                $aDataFinal = array(
                    'tReturn' => 1
                );

                $aDataAll = array(
                    // 'aDataQA'           => $aDataQA,
                    'aDataGetDetail'    => $aDataFinal,
                    'tRoute'            => 'docBackupCleanupEventAdd'
                );
            }
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        $this->load->view('document/backupandcleanup/wBackupAndCleanupPageAdd', $aDataAll);
    }

    //Functionality : Function Call View Data SettingConperiod
    //Parameters : Ajax Call View DataTable
    //Creator : 06-09-2022 Off 
    //Return : String View
    //Return Type : View
    public function FSvBACGetAdvDataTable()
    {
        try {
            $aSearchAll = $this->input->post('aSearchAll');
            $nPage      = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit  = $this->session->userdata("tLangEdit");

            $aData  = array(
                'tSearchAll'    => $aSearchAll
            );

            $aDataList = $this->Backupandcleanup_model->FSaMBACUGetData($aData);
            $aAlwEventSettconpreiod = FCNaHCheckAlwFunc('BAC/0/0'); //Controle Event


            $aGenTable = array(
                'aSearchAll'    => $aSearchAll,
                'aDataList'     => $aDataList,
                'aAlwEvent'     => $aAlwEventSettconpreiod
            );

            $this->load->view('document/backupandcleanup/wBackupAndCleanupAdvDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function Call View Data HistoryPurge
    //Parameters : Ajax Call View DataTable
    //Creator : 06-14-2022 Off 
    //Return : String View
    //Return Type : View
    public function FSvBACGetHistoryAdvDataTable()
    {
        try {
            $aSearchAll = $this->input->post('aSearchAll');
            $nPage      = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit  = $this->session->userdata("tLangEdit");

            $aData  = array(
                'tSearchAll'        => $aSearchAll,
                'FNLngID'           => $this->session->userdata("tLangID")
            );

            $aDataList = $this->Backupandcleanup_model->FSaMBACUGetHistoryData($aData);
            $aAlwEventSettconpreiod = FCNaHCheckAlwFunc('BAC/0/0'); //Controle Event


            $aGenTable = array(
                'aDataList'     => $aDataList,
                'aAlwEvent'     => $aAlwEventSettconpreiod
            );

            $this->load->view('document/backupandcleanup/wBackupAndCleanupHistoryList', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //อนุมัติเอกสาร
    public function FSoCBACUPurgeEvent()
    {
        try {
            // print_r($this->input->post());
            $aPurgeData = $this->input->post('aNewPurge');
            $tAgnCode   = $this->input->post('tAgnCode');
            $tBACUType  = $this->input->post('tBACUType');
            $tPosCode   = $this->input->post('tPosCode');
            $tBchCode   = $this->input->post('tBchCode');
            
            $paoTaskPurge = [];
            $apaoCondPurge = [];
            foreach($aPurgeData as $nKey => $aValue){
                $paoTaskPurgeData   = [
                    "ptAgnCode"       => $tAgnCode,
                    "ptPrgTblHD"      => $aValue['ptPrgTblHD'],
                    "pnPrgDocType"    => (int)$aValue['pnPrgDocType'],
                ];

                $apaoCondPurgeData   = [
                    "ptAgnCode"         => $tAgnCode,
                    "ptBchCode"         => $tBchCode,
                    "ptPosCode"         => $tPosCode,
                    "ptCondPrgType"     => $tBACUType,
                ];
                array_push($paoTaskPurge,$paoTaskPurgeData);
                array_push($apaoCondPurge,$apaoCondPurgeData);
            }

            $aMQParams = [
                "tVhostType" => "P",
                "queueName" => "CN_QTask",
                "params"    => [
                    'ptFunction'        => 'PURGEANDBACKUP',
                    'ptSource'          => 'POSSERVER',
                    'ptDest'            => 'MQReceivePrc',
                    'ptFilter'          => '',
                    'ptData'            => json_encode([
                        "ptCondType"    => $tBACUType,
                        "paoCondPurge"       => $apaoCondPurge,
                        "paoTaskPurge"       => $paoTaskPurge,
                    ])
                ]
            ];
            FCNxCallRabbitMQ($aMQParams);

        
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Purge Document Success",
                'tLogType' => 'INFO',
                'tEventName' => 'Purge ข้อมูล',
                'nLogCode' => '001',
                'nLogLevel' => '0'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tEventName' => 'Purge ข้อมูล',
                'nLogCode' => '900',
                'nLogLevel' => '4'
            );
        }
        echo json_encode($aReturnData);
    }
}
