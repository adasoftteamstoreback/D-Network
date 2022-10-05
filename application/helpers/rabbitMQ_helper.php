<?php
require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');
require_once(APPPATH . 'config/rabbitmq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 *
 * @param array $paParams
 *
 * $paParams = [
        "queueName" => "",
        "params" => [
            "ptBchCode" => "", "ptDocNo" => "", "ptUsrCode" => ""
        ]
    ];
 */
function FCNxCallRabbitMQ($paParams)
{
    $tQueueName = (isset($paParams['queueName']))?$paParams['queueName']:'';
    $aParams = (isset($paParams['params']))?$paParams['params']:[];
    $bStaUseConnStr = (isset($paParams['bStaUseConnStr']) && is_bool($paParams['bStaUseConnStr']))?$paParams['bStaUseConnStr']:true;
    $tVhostType = (isset($paParams['tVhostType'])) ?$paParams['tVhostType']:'D';

    if ($bStaUseConnStr) {
        $aParams['ptConnStr'] = DB_CONNECT;
    }
    $tExchange = EXCHANGE; // This use default exchange

    switch($tVhostType){
        case 'W': {
            $oConnection = new AMQPStreamConnection(MQ_CRD_HOST, MQ_CRD_PORT, MQ_CRD_USER, MQ_CRD_PASS, MQ_CRD_VHOST);
            // $aParams['ptData']['ptConnStr'] = DB_CONNECT;
            // $bDurable = true;
            break;
        }
        case 'A': {
            $oConnection = new AMQPStreamConnection(MQ_AUD_HOST, MQ_AUD_PORT, MQ_AUD_USER, MQ_AUD_PASS, MQ_AUD_VHOST);
            // $aParams['ptData']['ptConnStr'] = DB_CONNECT;
            // $bDurable = true;
            break;
        }
        case 'NOT': {
            $oConnection = new AMQPStreamConnection(MQ_NOT_HOST, MQ_NOT_PORT, MQ_NOT_USER, MQ_NOT_PASS, MQ_NOT_VHOST);
            // $aParams['ptData']['ptConnStr'] = DB_CONNECT;
            // $bDurable = true;
            break;
        }
        case 'S': {
            $oConnection = new AMQPStreamConnection(MQ_Sale_HOST, MQ_Sale_PORT, MQ_Sale_USER, MQ_Sale_PASS, MQ_Sale_VHOST);
            break;
        }
        case 'P': {
            $oConnection = new AMQPStreamConnection(MQ_PURGE_HOST, MQ_PURGE_PORT, MQ_PURGE_USER, MQ_PURGE_PASS, MQ_PURGE_VHOST);
            break;
        }
        case 'I': {
            $oConnection = new AMQPStreamConnection(INTERFACE_HOST, INTERFACE_PORT, INTERFACE_USER, INTERFACE_PASS, INTERFACE_VHOST);
            break;
        }
        default : {
            $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
            // $bDurable = false;
        }
    }

    $oChannel = $oConnection->channel();
    $oChannel->queue_declare($tQueueName, false, true, false, false);
    $oMessage = new AMQPMessage(json_encode($aParams,JSON_UNESCAPED_UNICODE));
    $oChannel->basic_publish($oMessage, "", $tQueueName);
    $oChannel->close();
    $oConnection->close();
    return 1;
    /** Success */

    /*$tQueueName = $paParams['queueName'];
    $aParams = $paParams['params'];
    $aParams['ptConnStr'] = DB_CONNECT;
    $tExchange = EXCHANGE;

    $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
    $oChannel = $oConnection->channel();
    $oChannel->queue_declare($tQueueName, false, false, false, false);
    $oChannel->exchange_declare($tExchange, 'direct', false, false, false);
    $oChannel->queue_bind($tQueueName, $tExchange);
    $oMessage = new AMQPMessage(json_encode($aParams));
    $oChannel->basic_publish($oMessage, $tExchange);

    echo "[x] Sent $tQueueName Success";

    $oChannel->close();
    $oConnection->close();*/
}

/**
 *
 * @param array $paParams
 *
 * $paParams = [
        "prefixQueueName" => "",
        "params" => [
            "ptBchCode" => "", "ptDocNo" => "", "ptUsrCode" => ""
        ]
    ];
 */
function FCNxRabbitMQDeleteQName(array $paParams = [])
{
    $tPrefixQueueName = $paParams['prefixQueueName'];
    $aParams = $paParams['params'];
    $tVhostType = (isset($paParams['tVhostType']) && in_array($paParams['tVhostType'],['W','D']))?$paParams['tVhostType']:'D';

    $tQueueName = $tPrefixQueueName . '_' . $aParams['ptDocNo'] . '_' . $aParams['ptUsrCode'];
    // $oConnection = new AMQPStreamConnection('172.16.30.28', '5672', 'admin', '1234', 'Pandora_PPT1');

    switch($tVhostType){
        case 'W': {
            $oConnection = new AMQPStreamConnection(MQ_CRD_HOST, MQ_CRD_PORT, MQ_CRD_USER, MQ_CRD_PASS, MQ_CRD_VHOST);
            break;
        }
        default : {
            $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        }
    }

    $oChannel = $oConnection->channel();
    $oChannel->queue_delete($tQueueName);
    $oChannel->close();
    $oConnection->close();
    return 1;
    /** Success */
}

function FSaHRabbitMQUpdateStaDelQnameHD($paParams)
{
    try {
        $tDocTableName = $paParams['tDocTableName'];
        $tDocFieldDocNo = $paParams['tDocFieldDocNo'];
        $tDocFieldStaApv = $paParams['tDocFieldStaApv'];
        $tDocFieldStaDelMQ = $paParams['tDocFieldStaDelMQ'];
        $tDocStaDelMQ = $paParams['tDocStaDelMQ'];
        $tDocNo = $paParams['tDocNo'];

        $ci = &get_instance();
        $ci->load->database();

        // Update HD
        $ci->db->set($tDocFieldStaDelMQ, 1);
        $ci->db->where($tDocFieldDocNo, $tDocNo);
        $ci->db->update($tDocTableName);


        if ($ci->db->affected_rows() > 0) {
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Master Success',
            );
        } else {
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add/Edit Master.',
            );
        }
        return $aStatus;
    } catch (Exception $Error) {
        return $Error;
    }
}

function FCNxRabbitMQGetMassage(array $paParams = [])
{
    /*$tQname = $paParams['tQname'];
		$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
		$channel = $connection->channel();
        $channel->queue_declare($tQname, false, true, false, false);
        $message = $channel->basic_get($tQname);
        if(!empty($message)){
            $channel->basic_ack($message->delivery_info['delivery_tag']);
            $nProgress = intval($message->body);
        }else{
            $nProgress = 'false' ;
        }
        $channel->close();
        $connection->close();
        return $nProgress;*/

    try {
        /*===== Begin Set Config =======================================================*/
        $tQname = $paParams['tQname'];
        $tVhostType = (isset($paParams['tVhostType']) && in_array($paParams['tVhostType'],['W','D','A','S','NOT']))?$paParams['tVhostType']:'D';
        /*===== End Set Config =========================================================*/

        switch($tVhostType){
            case 'W': {
                $oConnection = new AMQPStreamConnection(MQ_CRD_HOST, MQ_CRD_PORT, MQ_CRD_USER, MQ_CRD_PASS, MQ_CRD_VHOST);
                $bAutoDelete = true;
                break;
            }
            case 'A': {
                $oConnection = new AMQPStreamConnection(MQ_AUD_HOST, MQ_AUD_PORT, MQ_AUD_USER, MQ_AUD_PASS, MQ_AUD_VHOST);
                $bAutoDelete = false;
                break;
            }
            case 'S': {
                $oConnection = new AMQPStreamConnection(MQ_Sale_HOST, MQ_Sale_PORT, MQ_Sale_USER, MQ_Sale_PASS, MQ_Sale_VHOST);
                $bAutoDelete = false;
                break;
            }
            case 'NOT': {
                $oConnection = new AMQPStreamConnection(MQ_NOT_HOST, MQ_NOT_PORT, MQ_NOT_USER, MQ_NOT_PASS, MQ_NOT_VHOST);
                $bAutoDelete = true;
                break;
            }
            default : {
                $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
                $bAutoDelete = false;
            }
        }
        $oChannel = $oConnection->channel();
        ///เช็ค
        if(isset($paParams['nAutoDelete']) && $paParams['nAutoDelete'] != '')
        {
            $bAutoDelete = true;
        }
        $oChannel->queue_declare($tQname, false, true, false, $bAutoDelete);
        
        /// Queue นี้ต้องทำการ Binding ไปยัง Exchange หรือไม่ 1 = ใช่
        if(isset($paParams['nStaBindingEXC'])){
            if($paParams['nStaBindingEXC'] == '1'){
                if(isset($paParams['tExchangeName']) && $paParams['tExchangeName'] != ''){
                    
                    $tExchangeName = $paParams['tExchangeName'];
                    $binding_key = '';
                    $oChannel->queue_bind($tQname, $tExchangeName, $binding_key);
                }
            }
        }
        $message = $oChannel->basic_get($tQname);

        if (!empty($message)) {
            if (!empty($message->body)) {
                $oChannel->basic_ack($message->delivery_info['delivery_tag']);
                $nProgress = $message->body;
            } else {
                $nProgress = 'end';
            }
        } else {
            $nProgress = 'false';
        }

        $oChannel->close();
        $oConnection->close();
        return $nProgress;
    } catch (Exception $Error) {
        return $Error;
    }
}

function FCNxRabbitMQCheckQueueMassage(array $paParams = []){
    try{
        /*===== Begin Set Config =======================================================*/
        $tQname = $paParams ['tQname'];
        $tVhostType = (isset($paParams ['tVhostType']) && in_array($paParams ['tVhostType'],['W','D']))?$paParams ['tVhostType']:'D';
        /*===== End Set Config =========================================================*/

        switch($tVhostType){
            case 'W': {
                $oConnection = new AMQPStreamConnection(MQ_CRD_HOST, MQ_CRD_PORT, MQ_CRD_USER, MQ_CRD_PASS, MQ_CRD_VHOST);
                $bAutoDelete = true;
                break;
            }
            default : {
                $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
                $bAutoDelete = false;
            }
        }

        $channel = $oConnection->channel();
        $channel->queue_declare($tQname, false, true, false, $bAutoDelete);
        $message = $channel->basic_get($tQname);

            if(!empty($message->body)){
                $nProgress = 'true' ;
            }else{
                $nProgress = 'false' ;
            }

        $channel->close();
        $oConnection->close();
        return $nProgress;
    }catch(Exception $Error){
        return $Error;
    }
}

//Functionality : Controller ในการ Get Last Massage
//Parameters    : Ajax input type post
//Creator       : 20/11/2020 (Nale)
//Return        : text
//Return Type   : srting
function FCNxRabbitMQGetLastQueueMassage($paData){
    try{
        $massgeBack = '';
        $nProgress='';
        do{
            $tQname     = $paData['tQname'];
            $tVhostType = (isset($paData['tVhostType']) && in_array($paData['tVhostType'],['W','D','A','NOT']))?$paData['tVhostType']:'D';
            /*===== End Set Config =========================================================*/
    
            switch($tVhostType){
                case 'W': {
                    $oConnection = new AMQPStreamConnection(MQ_CRD_HOST, MQ_CRD_PORT, MQ_CRD_USER, MQ_CRD_PASS, MQ_CRD_VHOST);
                    $bAutoDelete = true;
                    break;
                }
                case 'A': {
                    $oConnection = new AMQPStreamConnection(MQ_AUD_HOST, MQ_AUD_PORT, MQ_AUD_USER, MQ_AUD_PASS, MQ_AUD_VHOST);
                    $bAutoDelete = false;
                    break;
                }
                case 'NOT': {
                    $oConnection = new AMQPStreamConnection(MQ_NOT_HOST, MQ_NOT_PORT, MQ_NOT_USER, MQ_NOT_PASS, MQ_NOT_VHOST);
                    $bAutoDelete = true;
                    break;
                }
                default : {
                    $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
                    $bAutoDelete = false;
                }
            }
            $oChannel    = $oConnection->channel();
            $oChannel->queue_declare($tQname, false, true, false, $bAutoDelete);
            $message    = $oChannel->basic_get($tQname);
            if(!empty($message)){
                if(!empty($message->body)){
                    $oChannel->basic_ack($message->delivery_info['delivery_tag']);
                    $nProgress  = $message->body;
                    $massgeBack = $message->body;
                }else{
                    $nProgress = 'end';
                }
            }else{
                $nProgress = 'false';
            }
            $oChannel->close();
            $oConnection->close();
        }while($nProgress!='false');
        return $massgeBack;
    }catch(Exception $Error){
        return $Error;
    }
}

// Create By : Napat(Jame) 04/08/2021
function FCNaRabbitMQInterface($paParams){
    $ci = &get_instance();
    $ci->load->database();

    $tSQL = "   SELECT *
                FROM TLKMConfig WITH(NOLOCK)
                WHERE TLKMConfig.FTCfgKey = 'Noti'
                AND TLKMConfig.FTCfgSeq = '4' ";
    $oQuery = $ci->db->query($tSQL);
    if ( $oQuery->num_rows() > 0 ){
        $aConfigMQ      = $oQuery->result_array();
        $tHost          = $aConfigMQ[1]['FTCfgStaUsrValue'];
        $tPort          = $aConfigMQ[2]['FTCfgStaUsrValue'];
        $tPassword      = FCNtHAES128Decrypt($aConfigMQ[3]['FTCfgStaUsrValue']);
        $tQueueName     = $paParams['queueName']/*$aConfigMQ][4]['FTCfgStaUsrValue']*/;
        $tUser          = $aConfigMQ[5]['FTCfgStaUsrValue'];
        $tVHost         = $aConfigMQ[6]['FTCfgStaUsrValue'];
        $aParams        = $paParams['params'];

        $oConnection = new AMQPStreamConnection($tHost, $tPort, $tUser, $tPassword, $tVHost);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oMessage = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);

        $oChannel->close();
        $oConnection->close();

        // echo "<pre>";
        // print_r($aConfigMQ);
        // print_r($oConnection);
        // echo $tPassword;

        $aReturnData  = array(
            'nStaEvent'     => '1',
            'tStaMessg'     => 'Success',
        );

    }else{
        $aReturnData  = array(
            'nStaEvent'     => '800',
            'tStaMessg'     => 'ไม่พบการตั้งค่า MQ กรุณาติดต่อผู้ดูแลระบบ',
        );
    }
    return $aReturnData;
}

//function ที่จะรวบรวม Data เพื่อส่ง MQ_LOG
function FSoCCallLogMQ($paReturnData)
{
    $aDataCookie = array(
        'tAgnCode' => json_decode($_COOKIE['tAgnCode']),
        'tBchCode' => json_decode($_COOKIE['tBchCode']),
        'tMenuCode' => json_decode($_COOKIE['tMenuCode']),
        'tMenuName' => json_decode($_COOKIE['tMenuName']),
        'tUsrCode' => json_decode($_COOKIE['tUsrCode']),
    );

    $aPackData = array();
    $aMQParams = array(
        'ptFunction' => $paReturnData['tLogType'],
        'ptAgnCode' => $aDataCookie['tAgnCode'],//ตัวแทนขาย
        'ptBchCode' => $aDataCookie['tBchCode'],//รหัสสาขา
        'ptPosCode' => '',//รหัสเครื่องจุดขาย
        'ptShfCode' => '',//รหัสรอบการขาย
        'ptAppCode' => 'SB',//ต้นทาง (Application) SB,PS,FC,VD,VS
        'ptAppName' => 'AdaStoreBack',//ต้นทาง (Application) SB,PS,FC,VD,VS
        'ptMnuCode' => $aDataCookie['tMenuCode'],//รหัสเมนู
        'ptMnuName' => $aDataCookie['tMenuName'],//ชื่อเมนู
        'ptObjCode' => $paReturnData['tDocNo'],//รหัสเอกสาร
        'ptObjName' => $paReturnData['tEventName'],//Event การกระทำของ User
        'pnLogLevel' => $paReturnData['nLogLevel'],//ระดับ 0:Info 1:Low 2:Medium 3:High 4:Critical
        'ptLogCode' => $paReturnData['nLogCode'],//รหัสอ้างอิง 001:Ok  800:Not Found  900:Fail ....
        'ptLogDesc' => $paReturnData['tStaMessg'],//รายเอียด Log
        'ptLogDate' => date("Y-m-d H:i:s") ,//วันที่ yyyy-MM-dd HH:mm:ss
        'ptUsrCode' => $aDataCookie['tUsrCode'],//รหัสผู้ใช้
        'ptApvCode' => $paReturnData['FTXphUsrApv'],//รหัสผู้อนุมัติ
    );

    array_push($aPackData,$aMQParams);
    FCNaRabbitMQLog($aPackData);
}

//function ส่ง MQ เข้า Log Q.
function FCNaRabbitMQLog($paParams)
{    
    $aDataCookie = array(
        'tAgnCode' => json_decode($_COOKIE['tAgnCode']),
        'tBchCode' => json_decode($_COOKIE['tBchCode']),
        'tMenuCode' => json_decode($_COOKIE['tMenuCode']),
        'tMenuName' => json_decode($_COOKIE['tMenuName']),
        'tUsrCode' => json_decode($_COOKIE['tUsrCode']),
    );
    
    for ($i=0; $i < count($paParams); $i++) { 
        $aMQParams = [
            "queueName" => "CN_QSendToLog",
            "tVhostType" => "LOG",
            "params"    => [
                'ptFunction'    => $paParams[$i]['ptFunction'],//ประเภท Log INFO WARNING EVENT ERROR
                'ptSource'      => 'StoreBack',
                'ptDest'        => 'MQLog',
                'ptData'        => json_encode([
                    'ptAgnCode' => $aDataCookie['tAgnCode'],//ตัวแทนขาย
                    'ptBchCode' => $aDataCookie['tBchCode'],//รหัสสาขา
                    'ptPosCode' => $paParams[$i]['ptPosCode'],//รหัสเครื่องจุดขาย
                    'ptShfCode' => $paParams[$i]['ptShfCode'],//รหัสรอบการขาย
                    'ptAppCode' => $paParams[$i]['ptAppCode'],//ต้นทาง (Application) SB,PS,FC,VD,VS
                    'ptAppName' => 'AdaStoreBack',//ต้นทาง (Application) SB,PS,FC,VD,VS
                    'ptMnuCode' => $aDataCookie['tMenuCode'],//รหัสเมนู
                    'ptMnuName' => $aDataCookie['tMenuName'],//ชื่อเมนู
                    'ptObjCode' => $paParams[$i]['ptObjCode'],//รหัสหน้าจอ
                    'ptObjName' => $paParams[$i]['ptObjName'],//ชื่อหน้าจอ/ฟังก์ชั่น
                    'pnLogLevel' => $paParams[$i]['pnLogLevel'],//ระดับ 0:Info 1:Low 2:Medium 3:High 4:Critical
                    'ptLogCode' => $paParams[$i]['ptLogCode'],//รหัสอ้างอิง 001:Ok  800:Not Found  900:Fail ....
                    'ptLogDesc' => $paParams[$i]['ptLogDesc'],//รายเอียด Log
                    'ptLogDate' => $paParams[$i]['ptLogDate'],//วันที่ yyyy-MM-dd HH:mm:ss
                    'ptUsrCode' => $aDataCookie['tUsrCode'],//รหัสผู้ใช้
                    'ptApvCode' => $paParams[$i]['ptApvCode'],//รหัสผู้อนุมัติ
                ])
            ]
        ];
       
    }

    $aStaReturn = FCNxCallRabbitMQ($aMQParams);
    if ($aStaReturn['rtCode'] == 905) {
        $ci = &get_instance();
        $ci->load->database();
        
        for ($i=0; $i < count($paParams); $i++) { 
            $aDataLogClient = array(
                'FTAgnCode'         => $aDataCookie['tAgnCode'],
                'FTBchCode'         => $aDataCookie['tBchCode'],
                'FTPosCode'         => $paParams[$i]['ptPosCode'],
                'FTShfCode'         => $paParams[$i]['ptShfCode'],
                'FTAppCode'         => $paParams[$i]['ptAppCode'],
                // 'FTAppName'         => 'AdaStoreBack',
                'FTMnuCodeRef'      => $aDataCookie['tMenuCode'],
                'FTMnuName'         => $aDataCookie['tMenuName'],
                'FTPrcCodeRef'      => $paParams[$i]['ptObjCode'],
                'FTPrcName'         => $paParams[$i]['ptObjName'],
                'FTLogType'         => $paParams[$i]['ptFunction'],
                'FTLogLevel'        => $paParams[$i]['pnLogLevel'],
                'FNLogRefCode'      => $paParams[$i]['ptLogCode'],
                'FTLogDescription'  => $paParams[$i]['ptLogDesc'],
                'FDLogDate'         => $paParams[$i]['ptLogDate'],
                'FTUsrCode'         => $aDataCookie['tUsrCode'],
                'FTUsrApvCode'      => $paParams[$i]['ptApvCode'],
                'FTLogStaSync'      => '',
            );
        }
        $ci->db->insert('TCNSLogClient',$aDataLogClient);

        for ($i=0; $i < count($paParams); $i++) { 
            $aDataClient = array(
                'FTAgnCode'         => $aDataCookie['tAgnCode'],
                'FTBchCode'         => $aDataCookie['tBchCode'],
                'FTPosCode'         => '',
                'FTShfCode'         => '',
                'FTAppCode'         => 'SB',
                // 'FTAppName'         => 'AdaStoreBack',
                'FTMnuCodeRef'      => $aDataCookie['tMenuCode'],
                'FTMnuName'         => $aDataCookie['tMenuName'],
                'FTPrcCodeRef'      => $paParams[$i]['ptObjCode'],
                'FTPrcName'         => 'Connect Log MQ',
                'FTLogType'         => 'EVENT',
                'FTLogLevel'        => '',
                'FNLogRefCode'      => 905,
                'FTLogDescription'  => 'Connection MQ Server Fail.',
                'FDLogDate'         => date("Y-m-d H:i:s"),
                'FTUsrCode'         => $aDataCookie['tUsrCode'],
                'FTUsrApvCode'      => $aDataCookie['tUsrCode'],
                'FTLogStaSync'      => '',
            );
        }

        $ci->db->insert('TCNSLogClient',$aDataClient);

        $tStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'Insert Success'
        );
    }else{
        $tStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'Send Success'
        );
    }

    return $tStatus;
}

function FCNaInsertLogClient($paParams)
{
    $aDataCookie = array(
            'tAgnCode' => json_decode($_COOKIE['tAgnCode']),
            'tBchCode' => json_decode($_COOKIE['tBchCode']),
            'tMenuCode' => json_decode($_COOKIE['tMenuCode']),
            'tMenuName' => json_decode($_COOKIE['tMenuName']),
            'tUsrCode' => json_decode($_COOKIE['tUsrCode']),
        );

    $ci = &get_instance();
    $ci->load->database();
    for ($i=0; $i < count($paParams); $i++) { 
        $aDataClient = array(
            'FTAgnCode'         => $aDataCookie['tAgnCode'],
            'FTBchCode'         => $aDataCookie['tBchCode'],
            'FTPosCode'         => '',
            'FTShfCode'         => '',
            'FTAppCode'         => 'SB',
            // 'FTAppName'         => 'AdaStoreBack',
            'FTMnuCodeRef'      => $aDataCookie['tMenuCode'],
            'FTMnuName'         => $aDataCookie['tMenuName'],
            'FTPrcCodeRef'      => $paParams[$i]['ptObjCode'],
            'FTPrcName'         => 'อนุมัติเอกสาร',
            'FTLogType'         => 'EVENT',
            'FTLogLevel'        => '',
            'FNLogRefCode'      => 905,
            'FTLogDescription'  => $paParams[$i]['ptLogDesc'],
            'FDLogDate'         => date("Y-m-d H:i:s"),
            'FTUsrCode'         => $aDataCookie['tUsrCode'],
            'FTUsrApvCode'      => $aDataCookie['tUsrCode'],
            'FTLogStaSync'      => '',
        );
    }

    $ci->db->insert('TCNSLogClient',$aDataClient);
    return;
}
