<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
require_once(APPPATH.'libraries/rabbitmq/vendor/autoload.php');
require_once(APPPATH.'config/rabbitmq.php');
require_once('././config_deploy.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
class cInterfaceExport extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('interface/interfaceexport/mInterfaceExport');
        
    }

    public function index($nBrowseType,$tBrowseOption){
        $tLangEdit = $this->session->userdata("tLangEdit");
        $aPackData = array(
            'nBrowseType'                   => $nBrowseType,
            'tBrowseOption'                 => $tBrowseOption,
            'aAlwEventInterfaceExport'      => FCNaHCheckAlwFunc('interfaceexport/0/0'), //Controle Event
            'vBtnSave'                      => FCNaHBtnSaveActiveHTML('interfaceexport/0/0'), //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'aDataMasterExport'             => $this->mInterfaceExport->FSaMIFXGetHD($tLangEdit)
        );
        $this->load->view('interface/interfaceexport/wInterfaceExport',$aPackData);
    }

    //กดปุ่มยืนยัน หา password ก่อน
    public function FSxCIFXCallRabitMQ(){
        $tTypeEvent     = $this->input->post('ptTypeEvent');
        if($tTypeEvent == 'getpassword'){
            $aResult = $this->mInterfaceExport->FSaMINMGetDataConfig();
            $aConnect = array(
                'tHost'      => $aResult[1]['FTCfgStaUsrValue'],
                'tPort'      => $aResult[2]['FTCfgStaUsrValue'],
                'tPassword'  => $aResult[3]['FTCfgStaUsrValue'],
                'tUser'      => $aResult[5]['FTCfgStaUsrValue'],
                'tVHost'     => $aResult[6]['FTCfgStaUsrValue']
            );
            echo json_encode($aConnect);
        }else{
            $aIFXExport     = $this->input->post('ocmIFXExport');
            $tPassword      = $this->input->post('tPassword');

            if(!empty($aIFXExport)){

                $aPackData     = array();
                $aKey          = array();
                $aValue        = array();

                foreach($aIFXExport as $nKey => $nValue){

                    switch ($nValue) {
                        case "00003": //ข้อมูลรายการขาย (BIGDATA)
                            // $aFilterSearch = [1,2];
                            $tMQName       = 'EX_StatusSO';
                            break;
                        case "00004": //ข้อมูลรายการขาย (BIGDATA)
                            // $aFilterSearch = [1,2];
                            $tMQName       = 'EX_StockOnHand';
                            break;
                        default:
                    }

                    //$aFilterSearch = '1' : สาขา ถึง สาขา
                    //$aFilterSearch = '2' : วันที่ ถึง วันที่
                    //$aFilterSearch = '3' : เลขที่บิล ถึง เลขที่บิล
                    //$aFilterSearch = '4' : เจ้าหนี้ ถึง เจ้าหนี้
                    //$aFilterSearch = '5' : เดือน
                    //$aFilterSearch = '6' : ปี
                    //$aFilterSearch = '7' : กลุ่มสินค้า ถึง กลุ่มสินค้า
                    //$aFilterSearch = '8' : หมวดสินค้า ถึง หมวดสินค้า
                    //$aFilterSearch = '9' : สินค้า ถึง สินค้า
                    //$aFilterSearch = '10' : หน่วยนับ ถึง หน่วยนับ
                    //$aFilterSearch = '11' : ประเภทการชำระเงิน ถึง ประเภทการชำระเงิน
                    //$aFilterSearch = '12' : เลขที่คูปอง ถึง เลขที่คูปอง
                    //$aFilterSearch = '13' : รหัสคูปอง ถึง รหัสคูปอง
                    //$aFilterSearch = '14' : รหัสโปรโมชั่น ถึง รหัสโปรโมชั่น
                    //$aFilterSearch = '15' : เลขที่ใบวางบิล ถึง เลขที่ใบวางบิล
                    //$aFilterSearch = '16' : เลขที่ใบซื้อ
                    // foreach($aFilterSearch AS $n => $nInforFilter){
                    //     switch ($nInforFilter) {
                    //         case "1": 
                    //             $tBchCodeFrm    = $this->input->post('oetExBchCodeFrm' . $nValue);
                    //             $tBchCodeTo     = $this->input->post('oetExBchCodeTo' . $nValue);
                    //             array_push($aKey,'ptBchCodeFrm','ptBchCodeTo');
                    //             array_push($aValue,$tBchCodeFrm,$tBchCodeTo);
                    //             break;
                    //         case "2": 
                    //             $dDateFrm       = $this->input->post('oetExDateFrm' . $nValue);
                    //             $dDateTo        = $this->input->post('oetExDateFrm' . $nValue); //วันที่ถึง ไม่ได้ใช้เเล้ว 
                    //             array_push($aKey,'pdDateFrm','pdDateTo');
                    //             array_push($aValue,$dDateFrm,$dDateTo);
                    //             break;
                    //         case "3": 
                    //             $tBillFrm       = $this->input->post('oetExDocSaleCodeFrm' . $nValue);
                    //             $tBillTo        = $this->input->post('oetExDocSaleCodeTo' . $nValue);
                    //             array_push($aKey,'ptBillFrm','ptBillTo');
                    //             array_push($aValue,$tBillFrm,$tBillTo);
                    //             break;
                    //         case "4": 
                    //             $tSplFrm        = $this->input->post('oetExCreditCodeFrm' . $nValue);
                    //             if($tSplFrm != ''){
                    //                 array_push($aKey,'ptListSplCode');
                    //                 array_push($aValue,$tSplFrm);
                    //             }
                    //             break;
                    //         case "5": 
                    //             $tMonth         = $this->input->post('ocmExMonth' . $nValue);
                    //             array_push($aKey,'ptMonth');
                    //             array_push($aValue,$tMonth);
                    //             break;
                    //         case "6": 
                    //             $tYears         = $this->input->post('ocmExYear' . $nValue);
                    //             array_push($aKey,'ptYears');
                    //             array_push($aValue,$tYears);
                    //             break;
                    //         case "7": 
                    //             $tGrpFrm        = $this->input->post('oetExGrpPDTCodeFrm' . $nValue);
                    //             $tGrpTo         = $this->input->post('oetExGrpPDTCodeTo' . $nValue);
                    //             array_push($aKey,'ptGrpFrm','ptGrpTo');
                    //             array_push($aValue,$tGrpFrm,$tGrpTo);
                    //             break;
                    //         case "8": 
                    //             $tCateFrm       = $this->input->post('oetExCatePDTCodeFrm' . $nValue);
                    //             $tCateTo        = $this->input->post('oetExCatePDTCodeTo' . $nValue);
                    //             array_push($aKey,'ptCateFrm','ptCateTo');
                    //             array_push($aValue,$tCateFrm,$tCateTo);
                    //             break;
                    //         case "9": 
                    //             $tPDTFrm        = $this->input->post('oetExPDTCodeFrm' . $nValue);
                    //             $tPDTTo         = $this->input->post('oetExPDTCodeTo' . $nValue);
                    //             array_push($aKey,'ptPDTFrm','ptPDTTo');
                    //             array_push($aValue,$tPDTFrm,$tPDTTo);
                    //             break;
                    //         case "10": 
                    //             $tUnitFrm       = $this->input->post('oetExUnitPDTCodeFrm' . $nValue);
                    //             $tUnitTo        = $this->input->post('oetExUnitPDTCodeTo' . $nValue);
                    //             array_push($aKey,'ptUnitFrm','ptUnitTo');
                    //             array_push($aValue,$tUnitFrm,$tUnitTo);
                    //             break;
                    //         case "11": 
                    //             $tTypePayFrm    = $this->input->post('oetExTypePayCodeFrm' . $nValue);
                    //             $tTypePayTo     = $this->input->post('oetExTypePayCodeTo' . $nValue);
                    //             array_push($aKey,'ptTypePayFrm','ptTypePayTo');
                    //             array_push($aValue,$tTypePayFrm,$tTypePayTo);
                    //             break;
                    //         case "12": 
                    //             $tDocCouponFrm  = $this->input->post('oetExDocCouponCodeFrm' . $nValue);
                    //             $tDocCouponTo   = $this->input->post('oetExDocCouponCodeTo' . $nValue);
                    //             array_push($aKey,'ptDocCouponFrm','ptDocCouponTo');
                    //             array_push($aValue,$tDocCouponFrm,$tDocCouponTo);
                    //             break;
                    //         case "13": 
                    //             $tCouponFrm      = $this->input->post('oetExCouponCodeFrm' . $nValue);
                    //             $tCouponTo       = $this->input->post('oetExCouponCodeTo' . $nValue);
                    //             array_push($aKey,'ptCouponFrm','ptCouponTo');
                    //             array_push($aValue,$tCouponFrm,$tCouponTo);
                    //             break;
                    //         case "14": 
                    //             $tPromotionFrm   = $this->input->post('oetExPromotionCodeFrm' . $nValue);
                    //             $tPromotionTo    = $this->input->post('oetExPromotionCodeTo' . $nValue);
                    //             array_push($aKey,'ptPromotionFrm','ptPromotionTo');
                    //             array_push($aValue,$tPromotionFrm,$tPromotionTo);
                    //             break;
                    //         case "15": 
                    //             $tDocBillFrm     = $this->input->post('oetExDocBillCodeFrm' . $nValue);
                    //             if($tDocBillFrm != ''){
                    //                 array_push($aKey,'ptListDocPB');
                    //                 array_push($aValue,$tDocBillFrm);
                    //             }
                    //             break;
                    //         case "16": 
                    //             $tDocBillIV     = $this->input->post('oetExDocIVCode' . $nValue);
                    //             if($tDocBillIV != ''){
                    //                 array_push($aKey,'ptListDocPI');
                    //                 array_push($aValue,$tDocBillIV);
                    //             }
                    //             break;
                    //         default:
                    //     }

                    //     //รวม array
                    //     $aPackData = array_combine($aKey,$aValue);
                    // }

                    $this->FSaCIFXGetFormatParam($nValue,json_encode($aPackData),$tMQName,$tPassword);
                    $aPackData      = array();
                    $aKey           = array();
                    $aValue         = array();
                }
            }
            return;
        }
    }

    //ส่งค่าไป MQ
    public function FCNxCallRabbitMQSale($paParams,$pbStaUse = true,$ptPasswordMQ) {
        $aVal       = $this->mInterfaceExport->FSaMINMGetDataConfig();
        $tHost      = $aVal[1]['FTCfgStaUsrValue'];
        $tPort      = $aVal[2]['FTCfgStaUsrValue'];
        $tUser      = $aVal[5]['FTCfgStaUsrValue'];
        $tVHost     = $aVal[6]['FTCfgStaUsrValue'];
        $tQueueName = $paParams['queueName'];
        $aParams    = $paParams['params'];
        if($pbStaUse == true){
            $aParams['ptConnStr']   = DB_CONNECT;
        }

        //ถ้ามีการเซตแบบ SSL ต้องวิ่งอีกแบบ AMQPSSLConnection
        $tRabbitHelper = '';
        if(defined('RABBITSSL')){
            if(RABBITSSL == true || RABBITSSL == 1){ //กรณีต้องการ connect MQ แบบ SSL
                $tRabbitHelper = 'rabbitMQSSL';
            }else{
                $tRabbitHelper = 'rabbitMQ';
            }
        }else{
            $tRabbitHelper = 'rabbitMQ';
        }   

        if($tRabbitHelper == 'rabbitMQSSL'){
            $aSsl_options = array(
                'allow_self_signed' => false,
                'verify_peer' 		=> false,
                'verify_peer_name' 	=> false
            );
            $oConnection    = new AMQPSSLConnection($tHost, $tPort,  $tUser, $ptPasswordMQ, $tVHost , $aSsl_options);
        }else{
            $oConnection    = new AMQPStreamConnection($tHost, $tPort,  $tUser, $ptPasswordMQ, $tVHost);
        }
        
        $oChannel       = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oMessage       = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();
        return 1; 
    }

    //จัด data เตรียมส่งเข้า MQ
    public function FSaCIFXGetFormatParam($pnValue,$paPackData,$ptMQName,$ptPassword){

        if($ptMQName == 'EX_StatusSO'){ //ข้อมูลรายการ Master (BIGDATA)
            $ptFunctionName = 'ReqExportStatusSO';
        }else if($ptMQName == 'EX_StockOnHand'){ //ข้อมูลรายการขาย (BIGDATA)
            $ptFunctionName = 'ReqExportStockOnHand';
        }

        $aMQParams = [
            "queueName"     => $ptMQName,
            "exchangname"   => "",
            "params"        => [
                "ptFunction"    =>  $ptFunctionName,    //ชื่อ Function
                "ptSource"      =>  "AdaTaskLink",     //ต้นทาง
                "ptDest"        =>  "MQAdaLink",        //ปลายทาง
                "ptFilter"        =>  "",        //ปลายทาง
                "ptData"        =>  date('Y-m-d H:i:s')
            ]
        ];

        $this->FCNxCallRabbitMQSale($aMQParams,false,$ptPassword);
    }

    //เลือก บิล แต่จะต้องเอาบิลมาใส่ใน Temp ก่อน
    public function FSnCIFXFillterBill(){
        $aDataParam = [
            "tType"         => $this->input->post('tType'),
            "dDateFrm"		=> $this->input->post('dDateFrm'),
            "dDateTo"		=> $this->input->post('dDateTo'),
            "tBCHCodeFrm"	=> $this->input->post('tBCHCodeFrm'),
            "tBCHCodeTo"	=> $this->input->post('tBCHCodeTo'),
            "tSPLCodeFrm"	=> $this->input->post('tSPLCodeFrm'),
        ];

        $this->mInterfaceExport->FSxMIFXFillterBill($aDataParam);
        return 1;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //ส่งคิวตามรายการบิล เฉพาะการติ๊กว่า ส่งไม่สำเร็จ
    public function FSxCINFCallPreapairExport($ptPasswordMQ){
        $aDocNoPrepair= $this->mInterfaceExport->FSaMINMGetLogHisError();
        
            if(!empty($aDocNoPrepair)){
                foreach($aDocNoPrepair as $aValue){
                    $aMQParams = [
                        "queueName"     => "LK_QSale2Vender",
                        "exchangname"   => "",
                        "params"        => [
                            "ptFunction"    =>  "SalePos",//ชื่อ Function
                            "ptSource"      =>  "AdaStoreBack", //ต้นทาง
                            "ptDest"        =>  "MQAdaLink",  //ปลายทาง
                            "ptData"        =>  json_encode([
                                "ptFilter"      => $aValue['FTBchCode'],
                                "ptDateFrm"     => '',
                                "ptDateTo"      => '',
                                "ptDocNoFrm"    => $aValue['FTLogTaskRef'],
                                "ptDocNoTo"     => $aValue['FTLogTaskRef'],
                                "ptWaHouse"     => '',
                                "ptPosCode"     => '',
                                "ptRound"       => '1'
                            ])
                        ]
                    ];

            
                    $this->FCNxCallRabbitMQSale($aMQParams,false,$ptPasswordMQ);
                }
            }

    }

    //เช็คว่าเงื่อนไขที่ส่งมามีในเอกสารตรวจนับเเล้วหรือยัง
    public function FSxCIFXCheckDocumentADJ(){
        $tBCHCodeFrm	= $this->input->post('tBCHCodeFrm');
        $tBCHCodeTo		= $this->input->post('tBCHCodeTo');
        $tMonth	        = $this->input->post('tMonth');
        $tYear	        = $this->input->post('tYear');
        $tDay	        = $this->input->post('tDay');
        $tBillFrm	    = $this->input->post('tBillFrm');
        $tBillTo	    = $this->input->post('tBillTo');
        $tAPICode	    = $this->input->post('tAPICode');

        $aWhere = array(
            'tBCHCodeFrm'   => $tBCHCodeFrm,
            'tBCHCodeTo'    => $tBCHCodeTo,
            'tMonth'        => $tMonth,
            'tYear'         => $tYear,
            'tDay'	        => $tDay,
            'tBillFrm'	    => $tBillFrm,
            'tBillTo'	    => $tBillTo
        );

        if($tAPICode == '00030'){ //ต้นทุน FIT Auto (SAP + BIGDATA) จะต้องวิ่งเข้าไปหาเอกสารตรวจนับก่อน
            $nCount = $this->mInterfaceExport->FSaMIFXCheckDocAllAproveINBCH($aWhere);
            if($nCount > 0){
                $aResultPDT = $this->mInterfaceExport->FSaMIFXCheckDocFindAproveINBCH($aWhere);
    
                //ส่ง Noti
                if($aResultPDT['rtCode'] == 1){
        
                    for($i=0; $i<count($aResultPDT['raItems']); $i++){
                        $nCodeNoti      = $aResultPDT['raItems'][$i]['rnCodeNoti'];
                        $tTableNoti     = $aResultPDT['raItems'][$i]['rtTableName'];
                        $tBchCodeNoti   = $aResultPDT['raItems'][$i]['rtBchCode'];
                        $tBchNameNoti   = $aResultPDT['raItems'][$i]['rtBchName']; 
                        $tDocCodeNoti   = $aResultPDT['raItems'][$i]['rtDocNo'];
                        $tDesc          = "กรุณาอนุมัติเอกสารดังกล่าว ก่อนทำการส่งออก";
        
                        switch ($nCodeNoti) {
                            case '00013': //ใบรับเข้า (คลัง)
                                $tMsgDesc1_thai     = 'เอกสารใบรับเข้า (คลัง) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Receipt (wahouse) #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TXOOut/2/0'; 
                                break;
                            case '00014': //ใบเบิกออก (คลัง)
                                $tMsgDesc1_thai     = 'เอกสารใบเบิกออก (คลัง) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Out #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TWO/2/0/2'; 
                                break;
                            case '00015': //ใบจ่ายโอน (คลัง)
                                $tMsgDesc1_thai     = 'เอกสารใบจ่ายโอน (คลัง) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Warehouse Out #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TWO/2/0/4'; 
                                break;
                            case '00016': //ใบรับโอน (คลัง)
                                $tMsgDesc1_thai     = 'เอกสารใบรับโอน (คลัง) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Warehouse Out #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TWI/2/0'; 
                                break;
                            case '00017': //ใบโอนสินค้าระหว่างคลัง
                                $tMsgDesc1_thai     = 'เอกสารใบโอนสินค้าระหว่างคลัง #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer between Warehouse #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TFW/2/0'; 
                                break;
                            case '00008': //ใบจ่ายโอน (สาขา) [มีแล้ว]
                                $tMsgDesc1_thai     = 'เอกสารใบจ่ายโอน (สาขา) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Branch Out #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docTransferBchOut/2/0'; 
                                break;
                            case '00009': //ใบรับโอน (สาขา) [มีแล้ว]
                                $tMsgDesc1_thai     = 'เอกสารใบรับโอน (สาขา) #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Vendor purchase requisitions #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docTBI/2/0/5'; 
                                break;
                            case '00012': //ใบโอนสินค้าระหว่างสาขา [มีแล้ว]
                                $tMsgDesc1_thai     = 'เอกสารใบโอนสินค้าระหว่างสาขา #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Transfer Product Branch #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'TBX/2/0'; 
                                break;
                            case '00011': //ใบรับของ [มีแล้ว]
                                $tMsgDesc1_thai     = 'เอกสารใบรับของ #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Deliveryorder #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docDO/2/0'; 
                                break;
                            case '00018': //ใบลดหนี้ (แบบมีสินค้า)
                                $tMsgDesc1_thai     = 'เอกสารใบลดหนี้ #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt CreditNote #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'creditNote/2/0'; 
                                break;
                            case '00019': //ใบนัดหมาย
                                $tMsgDesc1_thai     = 'เอกสารใบนัดหมาย #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Booking #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docBookingCalendar/0/0'; 
                                break;
                            case '00020': //ใบจองสินค้า
                                $tMsgDesc1_thai     = 'เอกสารใบจองสินค้า #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Booking Product #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docBKO/2/0'; 
                                break;
                            case '00021': //ใบรับรถ
                                $tMsgDesc1_thai     = 'เอกสารใบรับรถ #'.$tDocCodeNoti;
                                $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                                $tMsgDesc1_eng      = 'Docuemnt Job Require #'.$tDocCodeNoti;
                                $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                                $tRoute             = 'docJR1/2/0'; 
                                break;
                        }
        
                        $tNotiID        = '';
                        $aMQParamsNoti  = [
                            "queueName"     => "CN_SendToNoti",
                            "tVhostType"    => "NOT",
                            "params"        => [
                                "oaTCNTNoti" => array(
                                    "FNNotID"               => $tNotiID,
                                    "FTNotCode"             => $nCodeNoti,
                                    "FTNotKey"              => $tTableNoti,
                                    "FTNotBchRef"           => $tBchCodeNoti,
                                    "FTNotDocRef"           => $tDocCodeNoti,
                                    "FNNotType"             => '1' //เอกสารค้างอนุมัติ
                                ),
                                "oaTCNTNoti_L" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 1,
                                        "FTNotDesc1"        => $tMsgDesc1_thai,
                                        "FTNotDesc2"        => $tMsgDesc2_thai,
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 2,
                                        "FTNotDesc1"        => $tMsgDesc1_eng,
                                        "FTNotDesc2"        => $tMsgDesc2_eng,
                                    )
                                ),
                                "oaTCNTNotiAct" => array(
                                    0 => array(  
                                        "FNNotID"           => $tNotiID,
                                        "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                        "FTNoaDesc"         => $tDesc,
                                        "FTNoaDocRef"       => $tDocCodeNoti,
                                        "FNNoaUrlType"      =>  1,
                                        "FTNoaUrlRef"       => $tRoute,
                                    ),
                                ), 
                                "oaTCNTNotiSpc" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '1',
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"         => '',
                                        "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                        "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '2',
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"         => '',
                                        "FTBchCode"         => $tBchCodeNoti,
                                        "FTBchName"         => $tBchNameNoti
                                    ),
                                ),
                                "ptUser"    => $this->session->userdata('tSesUsername'),
                            ]
                        ];
                        FCNxCallRabbitMQ($aMQParamsNoti);
                    }
        
                }
            }else{
                $aResultPDT = array();
            }
        }else if($tAPICode == '00028'){ //ส่งออกรายได้ขายแยกตามประเภทการชำระเงิน(SAP)
            $nCount = $this->mInterfaceExport->FSaMIFXCheckDocSaleUploadSuccess($aWhere);
            $aResultPDT = array();
        }

        $aResult = array(
            'nCount' => $nCount,
            'aItems' => $aResultPDT
        );

        echo json_encode($aResult);
    }

    //ส่ง Noti
    public function FSxCIFXSendNotiForDocNotApv(){
        $tBCHCodeFrm	= $this->input->post('tBCHCodeFrm');
        $tBCHCodeTo		= $this->input->post('tBCHCodeTo');
        $tMonth	        = $this->input->post('tMonth');
        $tYear	        = $this->input->post('tYear');

        $aWhere = array(
            'tBCHCodeFrm'   => $tBCHCodeFrm,
            'tBCHCodeTo'    => $tBCHCodeTo,
            'tMonth'        => $tMonth,
            'tYear'         => $tYear
        );
        $aResultPDT = $this->mInterfaceExport->FSaMIFXCheckDocFindAproveINBCH($aWhere);

        //ส่ง Noti
        if($aResultPDT['rtCode'] == 1){

            for($i=0; $i<count($aResultPDT['raItems']); $i++){
                $nCodeNoti      = $aResultPDT['raItems'][$i]['rnCodeNoti'];
                $tTableNoti     = $aResultPDT['raItems'][$i]['rtTableName'];
                $tBchCodeNoti   = $aResultPDT['raItems'][$i]['rtBchCode'];
                $tBchNameNoti   = $aResultPDT['raItems'][$i]['rtBchName']; 
                $tDocCodeNoti   = $aResultPDT['raItems'][$i]['rtDocNo'];
                $tDesc          = "กรุณาอนุมัติเอกสารดังกล่าว ก่อนทำการส่งออก";

                switch ($nCodeNoti) {
                    case '00013': //ใบรับเข้า (คลัง)
                        $tMsgDesc1_thai     = 'เอกสารใบรับเข้า (คลัง) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Receipt (wahouse) #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TXOOut/2/0'; 
                        break;
                    case '00014': //ใบเบิกออก (คลัง)
                        $tMsgDesc1_thai     = 'เอกสารใบเบิกออก (คลัง) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Out #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TWO/2/0/2'; 
                        break;
                    case '00015': //ใบจ่ายโอน (คลัง)
                        $tMsgDesc1_thai     = 'เอกสารใบจ่ายโอน (คลัง) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Warehouse Out #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TWO/2/0/4'; 
                        break;
                    case '00016': //ใบรับโอน (คลัง)
                        $tMsgDesc1_thai     = 'เอกสารใบรับโอน (คลัง) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Warehouse Out #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TWI/2/0'; 
                        break;
                    case '00017': //ใบโอนสินค้าระหว่างคลัง
                        $tMsgDesc1_thai     = 'เอกสารใบโอนสินค้าระหว่างคลัง #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer between Warehouse #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TFW/2/0'; 
                        break;
                    case '00008': //ใบจ่ายโอน (สาขา) [มีแล้ว]
                        $tMsgDesc1_thai     = 'เอกสารใบจ่ายโอน (สาขา) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Branch Out #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docTransferBchOut/2/0'; 
                        break;
                    case '00009': //ใบรับโอน (สาขา) [มีแล้ว]
                        $tMsgDesc1_thai     = 'เอกสารใบรับโอน (สาขา) #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Vendor purchase requisitions #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docTBI/2/0/5'; 
                        break;
                    case '00012': //ใบโอนสินค้าระหว่างสาขา [มีแล้ว]
                        $tMsgDesc1_thai     = 'เอกสารใบโอนสินค้าระหว่างสาขา #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Transfer Product Branch #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'TBX/2/0'; 
                        break;
                    case '00011': //ใบรับของ [มีแล้ว]
                        $tMsgDesc1_thai     = 'เอกสารใบรับของ #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Deliveryorder #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docDO/2/0'; 
                        break;
                    case '00018': //ใบลดหนี้ (แบบมีสินค้า)
                        $tMsgDesc1_thai     = 'เอกสารใบลดหนี้ #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt CreditNote #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'creditNote/2/0'; 
                        break;
                    case '00019': //ใบนัดหมาย
                        $tMsgDesc1_thai     = 'เอกสารใบนัดหมาย #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Booking #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docBookingCalendar/0/0'; 
                        break;
                    case '00020': //ใบจองสินค้า
                        $tMsgDesc1_thai     = 'เอกสารใบจองสินค้า #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Booking Product #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docBKO/2/0'; 
                        break;
                    case '00021': //ใบรับรถ
                        $tMsgDesc1_thai     = 'เอกสารใบรับรถ #'.$tDocCodeNoti;
                        $tMsgDesc2_thai     = 'รหัสสาขา '.$tBchCodeNoti. ' ค้างอนุมัติ';
                        $tMsgDesc1_eng      = 'Docuemnt Job Require #'.$tDocCodeNoti;
                        $tMsgDesc2_eng      = 'Branch code '.$tBchCodeNoti. ' want to approve';
                        $tRoute             = 'docJR1/2/0'; 
                        break;
                }

                $tNotiID        = '';
                $aMQParamsNoti  = [
                    "queueName"     => "CN_SendToNoti",
                    "tVhostType"    => "NOT",
                    "params"        => [
                        "oaTCNTNoti" => array(
                            "FNNotID"               => $tNotiID,
                            "FTNotCode"             => $nCodeNoti,
                            "FTNotKey"              => $tTableNoti,
                            "FTNotBchRef"           => $tBchCodeNoti,
                            "FTNotDocRef"           => $tDocCodeNoti,
                            "FNNotType"             => '1' //เอกสารค้างอนุมัติ
                        ),
                        "oaTCNTNoti_L" => array(
                            0 => array(
                                "FNNotID"           => $tNotiID,
                                "FNLngID"           => 1,
                                "FTNotDesc1"        => $tMsgDesc1_thai,
                                "FTNotDesc2"        => $tMsgDesc2_thai,
                            ),
                            1 => array(
                                "FNNotID"           => $tNotiID,
                                "FNLngID"           => 2,
                                "FTNotDesc1"        => $tMsgDesc1_eng,
                                "FTNotDesc2"        => $tMsgDesc2_eng,
                            )
                        ),
                        "oaTCNTNotiAct" => array(
                            0 => array(  
                                "FNNotID"           => $tNotiID,
                                "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                "FTNoaDesc"         => $tDesc,
                                "FTNoaDocRef"       => $tDocCodeNoti,
                                "FNNoaUrlType"      =>  1,
                                "FTNoaUrlRef"       => $tRoute,
                            ),
                        ), 
                        "oaTCNTNotiSpc" => array(
                            0 => array(
                                "FNNotID"           => $tNotiID,
                                "FTNotType"         => '1',
                                "FTNotStaType"      => '1',
                                "FTAgnCode"         => '',
                                "FTAgnName"         => '',
                                "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                            ),
                            1 => array(
                                "FNNotID"           => $tNotiID,
                                "FTNotType"         => '2',
                                "FTNotStaType"      => '1',
                                "FTAgnCode"         => '',
                                "FTAgnName"         => '',
                                "FTBchCode"         => $tBchCodeNoti,
                                "FTBchName"         => $tBchNameNoti
                            ),
                        ),
                        "ptUser"    => $this->session->userdata('tSesUsername'),
                    ]
                ];
                FCNxCallRabbitMQ($aMQParamsNoti);
            }

        }

        echo json_encode($aResultPDT);
    }

    //Export Excel
    public function FSxCIFXExportForDocNotApv(){
        $tFileName  = 'เอกสารค้างอนุมัติ' . '_' . date('YmdHis') . '.xlsx';
        $oWriter    = WriterEntityFactory::createXLSXWriter();

        $oWriter->openToBrowser($tFileName);

        $oBorder = (new BorderBuilder())
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
            ->build();

        $oStyleColums = (new StyleBuilder())
            ->setBorder($oBorder)
            ->setFontBold() //กำหนดรูปแบบตัวหนา
            ->setFontSize(12) //กำหนดขนาดตัวอักษร
            ->build();

        $oStyle2 = (new StyleBuilder()) // กำหนดรูปแบบของตารางและฟอนต์2
            ->setFontBold() //กำหนดรูปแบบตัวหนา
            ->setFontSize(12) //กำหนดขนาดตัวอักษร
            ->build(); //สร้างตารางจากข้อมูลด้านบน

        $aCells = [
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell('เอกสารค้างอนุมัติ')
        ];
        $singleRow = WriterEntityFactory::createRow($aCells,$oStyle2);
        $oWriter->addRow($singleRow);

        $aCells = [
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell('วันที่พิมพ์ '. ' ' . date('d/m/Y') . ' ' . ' เวลา ' . ' ' . date('H:i:s')),
        ];
        $singleRow = WriterEntityFactory::createRow($aCells,$oStyle2);
        $oWriter->addRow($singleRow);

        $aCells = [
            WriterEntityFactory::createCell('ลำดับ'),
            WriterEntityFactory::createCell('ชื่อสาขา'),
            WriterEntityFactory::createCell('เอกสาร'),
            WriterEntityFactory::createCell('เลขที่เอกสาร'),
            WriterEntityFactory::createCell('วันที่เอกสาร')
        ];
        $singleRow = WriterEntityFactory::createRow($aCells, $oStyleColums);
        $oWriter->addRow($singleRow);

        $aResult = $this->mInterfaceExport->FSaMIFXGetDataInTempDocNoApv();

        if(isset($aResult['raItems']) && !empty($aResult['raItems'])) {
            foreach ($aResult['raItems'] as $nKey => $aValue) { 

                $nCode      = $aResult['raItems'][$nKey]['FTXthNotiCode'];
                $tBchName   = $aResult['raItems'][$nKey]['FTBchName']; 
                $tDocCode   = $aResult['raItems'][$nKey]['FTXthDocNo'];

                switch ($nCode) {
                    case '00013': //ใบรับเข้า (คลัง)
                        $tMsgDesc     = 'เอกสารใบรับเข้า (คลัง)';
                        break;
                    case '00014': //ใบเบิกออก (คลัง)
                        $tMsgDesc     = 'เอกสารใบเบิกออก (คลัง)';
                        break;
                    case '00015': //ใบจ่ายโอน (คลัง)
                        $tMsgDesc     = 'เอกสารใบจ่ายโอน (คลัง)';
                        break;
                    case '00016': //ใบรับโอน (คลัง)
                        $tMsgDesc     = 'เอกสารใบรับโอน (คลัง)';
                        break;
                    case '00017': //ใบโอนสินค้าระหว่างคลัง
                        $tMsgDesc     = 'เอกสารใบโอนสินค้าระหว่างคลัง';
                        break;
                    case '00008': //ใบจ่ายโอน (สาขา) [มีแล้ว]
                        $tMsgDesc     = 'เอกสารใบจ่ายโอน (สาขา)';
                        break;
                    case '00009': //ใบรับโอน (สาขา) [มีแล้ว]
                        $tMsgDesc     = 'เอกสารใบรับโอน (สาขา)';
                        break;
                    case '00012': //ใบโอนสินค้าระหว่างสาขา [มีแล้ว]
                        $tMsgDesc     = 'เอกสารใบโอนสินค้าระหว่างสาขา';
                        break;
                    case '00011': //ใบรับของ [มีแล้ว]
                        $tMsgDesc     = 'เอกสารใบรับของ';
                        break;
                    case '00018': //ใบลดหนี้ (แบบมีสินค้า)
                        $tMsgDesc     = 'เอกสารใบลดหนี้';
                        break;
                    case '00019': //ใบนัดหมาย
                        $tMsgDesc     = 'เอกสารใบนัดหมาย';
                        break;
                    case '00020': //ใบจองสินค้า
                        $tMsgDesc     = 'เอกสารใบจองสินค้า';
                        break;
                    case '00021': //ใบรับรถ
                        $tMsgDesc     = 'เอกสารใบรับรถ';
                        break;
                }

                $nNumber = $nKey+1;

                $values = [
                    WriterEntityFactory::createCell($nNumber),
                    WriterEntityFactory::createCell($tBchName),
                    WriterEntityFactory::createCell($tMsgDesc),
                    WriterEntityFactory::createCell($tDocCode),
                    WriterEntityFactory::createCell(date("d/m/Y H:i:s", strtotime($aResult['raItems'][$nKey]['FDXthDocDate'])))
                ];
                $aRow = WriterEntityFactory::createRow($values);
                $oWriter->addRow($aRow);
            }
        }

        $oWriter->close();
    }

}    
?>

