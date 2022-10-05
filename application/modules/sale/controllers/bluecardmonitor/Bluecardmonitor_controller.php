<?php 
defined('BASEPATH') or exit('No direct script access allowed');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
require APPPATH .'libraries\rabbitapi\RabbitApi.php';

class Bluecardmonitor_controller extends MX_Controller {

    public function __construct() {
        $this->load->model('sale/bluecardmonitor/Bluecradmonitor_model');
        parent::__construct();
    }

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function index(){
        $this->load->view('sale/bluecardmonitor/wBlueCardMonitor');
    } 

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvCBCMCallPageBatch(){
        $this->load->view('sale/bluecardmonitor/batchmonitor/wBatchMonitor');
    }   

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvCBCMCallDataTable(){
        try {
  
            $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
   
            $aParameter = array(
                'tBCMBchCode' => $this->input->post('oetBCMBchCode'),
                'tBCMPosCode' => $this->input->post('oetBCMPosCode'),
                'tBCMSALDate' => $this->input->post('oetBCMSALDate'),
                'tBCMBatStaClosed' => $this->input->post('ocmBCMBatStaClosed'),
                'tBCMBatStaVerify' => $this->input->post('ocmBCMBatStaVerify'),
                'tBCMBatStaInsBat' => $this->input->post('ocmtBCMBatStaInsBat'),
            );
            $aData  = array(
                'aParameter'    => $aParameter,
                'nPage'         => $nPage,
                'nRow'          => 10,
            );
            $aListData = array(
                'paData'        => $this->Bluecradmonitor_model->FSvMBCMCallDataTable($aData),
                'nPage'         => $nPage,
            );
            $this->load->view('sale/bluecardmonitor/batchmonitor/wBatchMonitorDataTable',$aListData);

        } catch (Exception $e) {
            echo "The queue is not found";
        }
        


    }


    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvCBCMCallPageStand(){
        $tBatID = $this->input->post('tBatID');

        $aBatchData = $this->Bluecradmonitor_model->FSvMBCMCallDataBatchbyID($tBatID);
        $aDataBat = array(
            'tBatID' => $tBatID,
            'aBatchData' => $aBatchData
        );
        
        $this->load->view('sale/bluecardmonitor/standmonitor/wStandMonitor',$aDataBat);
    }   


    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvCBCMCallStandDataTable(){
        try {
            $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
   
            $aParameter = array(
                'tBatID' => $this->input->post('oetBatID'),
                'tBCMBatTabStdType' => $this->input->post('ocmBCMBatTabStdType'),
            );
            $aData  = array(
                'aParameter'    => $aParameter,
                'nPage'         => $nPage,
                'nRow'          => 10,
            );
            $aListData = array(
                'paData'        => $this->Bluecradmonitor_model->FSvMBCMCallStandDataTable($aData),
                'nPage'         => $nPage,
            );
            $this->load->view('sale/bluecardmonitor/standmonitor/wStandMonitorDataTable',$aListData);

        } catch (Exception $e) {
            echo "The queue is not found";
        }
    }

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    function FSvCBCMCExportBatch(){
        $aDataParam = array(
            'tName'         => 'BlueCard',
            'tFileName'     => 'Batch_'.date('Ymd'),
            'nOptionExcel'  => 2,
            'tTitleSheet'   => language('sale/salemonitor/salemonitor', 'tBCMTitle'),
            'tTitlePrint'   => language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s'),
            'aHeader'       => array(
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdNo'),
                language('ticket/agency/agency', 'tAgnBchCode'),
                language('ticket/agency/agency', 'tAgnBranch'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabPos'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabSht'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabShtBlue'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStandBlueFrm'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStandBlueTo'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabShtSalAmt'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaClose'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaVerti'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaRepiar')
            ),
            'aCoulmn'      => array(
                'rtRowID',
                'FTBchCode',
                'FTBchName',
                'FTPosRefTID',
                'FTShfCode',
                'FTBatID',
                'FTBatStandFrm',
                'FTBatStandTo',
                'FCBatSumAmt',
                'FTBatStaClosed',
                'FTBatStaVerify',
                'FTBatStaInsBat'
            ),
            'tQuery'    => $this->session->userdata('tSesSqlForExport'),
        );
        FCNxEXCExportByQuery($aDataParam);
    }
    
    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    function FSvCBCMCExportStand(){
        $tBatchID = $this->input->get('tBatchID');
        $tTabSht  = $this->input->get('tTabSht');
        
        $aDataParam = array(
            'tName'         => 'StandCard',
            'tFileName'     => 'Stand_'.$tBatchID.'_'.date('Ymd'),
            'nOptionExcel'  => 2,
            'tTitleSheet'   => language('sale/salemonitor/salemonitor', 'tBCMTitle') . ' (' . language('sale/salemonitor/salemonitor', 'tBCMBatTabSht'). ' : ' .$tTabSht .')',
            'tTitlePrint'   => language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s'),
            'aHeader'       => array(
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdNo'),
                language('sale/salemonitor/salemonitor', 'tLMSDocDate'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdDocNo'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdID'),
                language('sale/salemonitor/salemonitor', 'tLMSTxnPntB4Bill'),
                language('sale/salemonitor/salemonitor', 'tLMSTxnPntBillQty'),
                language('sale/salemonitor/salemonitor', 'tLMSTxnTotalPntToday'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdCrdCode'),
                language('sale/salemonitor/salemonitor', 'LMS Order ID'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdType'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdMode'),
                language('sale/salemonitor/salemonitor', 'tBCMBatTabStdUpd'),
                language('document/card/main', 'tExcelNewCardRemark'),
            ),
            'aCoulmn'      => array(
                'rtRowID',
                'FDCreateOn',
                'FTXshDocNo',
                'FTTxnStandID',
                'FCTxnPntB4Bill',
                'FCTxnPntBillQty',
                'FCTxnTotalPntToday',
                'FTTxnCrdCode',
                'FTTxnRefTranID',
                'FTTxnType',
                'FTTxnStaOnline',
                'FTTxnStaUpload',
                'FTTxnRmk'
            ),
            'tQuery'    => $this->session->userdata('tSesSqlForExport'),
        );
        
        FCNxEXCExportByQuery($aDataParam);
    }

    // Functionality : ฟังก์ชั่น MQ Request Sale Data
    // Parameters : Ajax and Function Parameter
    // Creator : 20/04/2020 Nale
    // Return : String View
    // Return Type : View
    public function FSvCBCMCallMQRequestSaleData(){
        try{
            $oListItem = $this->input->post('oListItem');
            // {
            //     "ptFunction" : "002",  //ฟังก์ชั่น Sale by Shift
            //     "ptSource" : "AdaStoreback",
            //     "ptDest" : "POS.PosTools",
            //     "ptFilter" : "000010002",
            //     "ptResQ" : "", //ไม่ต้องส่งก็ได้ เพราะไม่ได้ตอบกลับ
            //     "poData" : 
            //      {
            //          "ptFTShfCode" : "2008000010000200013" //ถ้าส่งเป็นว่างมา จะใช้ Shift ล่าสุด
            //      }
            // }

            if(!empty($oListItem)){
                foreach($oListItem as $aData){

                   $aParam = [
                       'ptFunction' => '002',
                       'ptSource' => 'StoreBackOffice',
                       'ptDest' => 'MQAdaLink',
                       'ptFilter' => "",
                       'ptResQ' => "",
                        'poData' => array(
                            'ptFTBchCode'  =>  $aData['tBchCode'],
                            'ptFTPosCode'  =>  $aData['tPosCode'],
                            'ptFTShfCode' => $aData['tShiftCode']
                        )
                   ]; 
                    $aMQParams = [
                        "exchangeName" => "",
                        "queueName" => "LK_XPepairingBlueCard",
                        "params"    => $aParam
                    ];
                 

             $this->FSxCBCMRabbitMQRequest($aMQParams);
                }
            }else{

                $aParam = [
                    'ptFunction' => '002',
                    'ptSource' => 'StoreBackOffice',
                    'ptDest' => 'MQAdaLink',
                    'ptFilter' => '',
                    'ptResQ' => "",
                     'poData' => array(
                        'ptFTBchCode'  =>  '',
                        'ptFTPosCode'  =>  '',
                        'ptFTShfCode' => ''
                     )
                ]; 
                 $aMQParams = [
                     "exchangeName" => "",
                     "queueName" => "LK_XPepairingBlueCard",
                     "params"    => $aParam
                 ];
        

                 $this->FSxCBCMRabbitMQRequest($aMQParams);

            }
        

            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => 'ok'
            );
            echo json_encode($aReturn);
        }catch(Exception $Error){
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => 'Error'
            );
            echo json_encode($aReturn);
            return;
        }

    }


    public function FSxCBCMRabbitMQRequest($paParams){
        $tQueueName = $paParams['queueName'];
        $aParams = $paParams['params'];
        $aParams['ptConnStr'] = DB_CONNECT;
        $tExchange = $paParams['exchangeName'];
        $oConnection = new AMQPStreamConnection(INTERFACE_HOST, INTERFACE_PORT, INTERFACE_USER, INTERFACE_PASS, INTERFACE_VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oMessage = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();
        return 1; /** Success */
    }





}