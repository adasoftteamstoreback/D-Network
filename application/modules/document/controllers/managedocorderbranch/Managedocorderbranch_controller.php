<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Managedocorderbranch_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/managedocorderbranch/Managedocorderbranch_model');
    }

    public $tRouteMenu          = 'docMngDocPreOrdB/0/0';   //docMngDocPreOrdB/0/0 : ใบสั่งสินค้าจากสาขา , docMngDocPreOrdB/0/2 : ใบสั่งสินค้าจากสาขา-ลูกค้า
    public $tMNGTypeDocument    = '1';                      //1 : ใบสั่งสินค้าจากสาขา  , 2 : ใบสั่งสินค้าจากสาขา-ลูกค้า

    public function index($nMNGBrowseType,$tMNGBrowseOption){

        if($tMNGBrowseOption == 0){ //ใบสั่งสินค้าจากสาขา
            $this->tRouteMenu       = 'docMngDocPreOrdB/0/0';
            $this->tMNGTypeDocument = '1';
        }else{//ใบสั่งสินค้าจากสาขา-ลูกค้า
            $this->tRouteMenu       =  'docMngDocPreOrdB/0/2';
            $this->tMNGTypeDocument = '2';
        }

        $aDataConfigView    = array(
            'nMNGBrowseType'        => $nMNGBrowseType,
            'tMNGBrowseOption'      => $tMNGBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'tRoute'                => $this->tRouteMenu,
            'tMNGTypeDocument'      => $this->tMNGTypeDocument
        );
        $this->load->view('document/managedocorderbranch/wManagedoc',$aDataConfigView);
    }

    //List
    public function FSvCMNGFormSearchList(){
        $aResult = array(
            'tMNGTypeDocument'  => $this->input->post('tMNGTypeDocument')
        );
        $this->load->view('document/managedocorderbranch/wManagedocSearchList',$aResult);
    }

    //Datatable
    public function FSvCMNGDataTable(){
        $tAdvanceSearchData     = $this->input->post('oAdvanceSearch');
        $nPage                  = $this->input->post('nPageCurrent');
        $aAlwEvent              = FCNaHCheckAlwFunc($this->tRouteMenu);
        $tMNGTypeDocument       = $this->input->post('tMNGTypeDocument');
        $nOptDecimalShow        = FCNxHGetOptionDecimalShow();

        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }

        $nLangEdit              = $this->session->userdata("tLangEdit");
        $aData = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $tAdvanceSearchData,
            'tMNGTypeDocument'  => $tMNGTypeDocument
        );

        $aList      = $this->Managedocorderbranch_model->FSaMMNGList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow,
            'tMNGTypeDocument'  => $tMNGTypeDocument
        );

        $tViewDataTable = $this->load->view('document/managedocorderbranch/wManagedocDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอจัดการ (HD)
    public function FSvCMNGManagePDT(){
        $tDocumentNumber     = $this->input->post('ptDocumentNumber');

        //ข้อมูลรายละเอียดในใบสั่งซื้อ
        $aGetDetailHD        = $this->Managedocorderbranch_model->FSaMMNGGetDetailHD($tDocumentNumber);

        $aGenTable  = array(
            'aGetDetailHD'      => $aGetDetailHD
        );

        $tViewDataTable = $this->load->view('document/managedocorderbranch/wManagedocCustomPDT', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอจัดการสินค้า (DT)
    public function FSvCMNGManageByPDT(){
        $tDocumentNumber    = $this->input->post('ptDocumentNumber');
        $tBchDocRef         = $this->input->post('ptBchDocRef');
        $tMNGTypeDocument   = $this->input->post('tMNGTypeDocument');

        //ลบข้อมูลใน Temp 
        $this->Managedocorderbranch_model->FSaMMNGDeleteInTemp();
        
        //ข้อมูลสินค้าในใบสั่งซื้อ
        $aGetDetailDT       = $this->Managedocorderbranch_model->FSaMMNGGetDetailDT($tDocumentNumber);
        if( $aGetDetailDT['rtCode'] == '800'){
            $tTypeDoc = 1;  // Default 1 : ใบสั่งสินค้าจากสาขา PRB , 2 : ใบสั่งซื้อ PO
        }else{
            $tTypeDoc = $aGetDetailDT['raItems'][0]['TYPE_MGT']; // 1 : ใบสั่งสินค้าจากสาขา PRB , 2 : ใบสั่งซื้อ PO
        }

        //เอาข้อมูล สินค้าลง Temp
        $this->Managedocorderbranch_model->FSaMMNGMoveDTToTemp($tDocumentNumber);

        //ถ้าเป็นแบบแฟรนไซด์
        if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){
            $aSPLConfig     = $this->Managedocorderbranch_model->FSxMMNGFindSPLByConfig();
        }else{
            $aSPLConfig     = '';
        }

        $aGenTable  = array(
            'tDocumentNumber'  => $tDocumentNumber,
            'aGetDetailDT'     => $aGetDetailDT,
            'tBchDocRef'       => $tBchDocRef,
            'aSPLConfig'       => $aSPLConfig,
            'tMNGTypeDocument' => $tMNGTypeDocument,
            'TYPE_MGT'         => $tTypeDoc 
        );

        $this->load->view('document/managedocorderbranch/wManagedocCustomByPDT', $aGenTable);
    }

    //เพิ่มข้อมูล ลงในตาราง
    public function FSxCMNGManageSavePDTInTable(){
        $tDocumentNumber    = $this->input->post('ptDocumentNumber');
        $tMNGTypeDocument   = $this->input->post('tMNGTypeDocument');  //1 : ใบสั่งสินค้าจากสาขา  , 2 : ใบสั่งสินค้าจากสาขา-ลูกค้า

        //ลบข้อมูลก่อน insert ทุกครั้ง
        $this->Managedocorderbranch_model->FSaMMNGDeleteHDAndDT($tDocumentNumber);

        //ข้อมูลรายละเอียดในใบสั่งซื้อ
        $aGetDetailHD       = $this->Managedocorderbranch_model->FSaMMNGGetDetailHD($tDocumentNumber);
        $tTypeDoc           = $aGetDetailHD[0]['TYPE_MGT']; // 1 : ใบสั่งสินค้าจากสาขา PRB , 2 : ใบสั่งซื้อ PO
        
        //Move ข้อมูลจาก Temp To HD
        $this->Managedocorderbranch_model->FSaMMNGMoveTempToHD($aGetDetailHD , $tDocumentNumber , $tTypeDoc , $tMNGTypeDocument);

        //Move ข้อมูลจาก Temp To DT
        $this->Managedocorderbranch_model->FSaMMNGMoveTempToDT($aGetDetailHD , $tDocumentNumber , $tTypeDoc , $tMNGTypeDocument);

        //Delete Temp
        $this->Managedocorderbranch_model->FSxMMNGDeleteTempWhereID();
    }

    //อัพเดทข้อมูล QTY All
    public function FSxCMNGUpdateQTYAll(){
        $nQTY           = $this->input->post('pnQTY');
        $tPDTCode       = $this->input->post('ptPDTCode');
        $tSPLCode       = $this->input->post('ptSPLCode');
        $nSEQ           = $this->input->post('nSEQ');

        $aWhere = array(
            'nQTY'          => str_replace(",","",$nQTY),
            'tPDTCode'      => $tPDTCode,
            'tSPLCode'      => $tSPLCode,
            'nSEQ'          => $nSEQ,
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        );
        $this->Managedocorderbranch_model->FSaMMNGUpdateQTYAllinTemp($aWhere);
    }

    //อัพเดทข้อมูล QTY
    public function FSxCMNGUpdateQTY(){
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tDataUpdate        = $this->input->post('tDataUpdate');
        $nQTY               = $this->input->post('nQTY');
        $tPDTCode           = $this->input->post('tPDTCode');
        $tRefTo             = $this->input->post('tRefTo');
        $nSeq               = $this->input->post('nSeq');
        $tSessionID         = $this->session->userdata('tSesSessionID');

        $aWhere = array(
            'FTXthDocNo'    => $tDocumentNumber,
            'FTPdtCode'     => $tPDTCode,
            'FTSessionID'   => $tSessionID,
            'FNXprSeqNo'    => $nSeq
        );

        if( $tRefTo != '' ||  $tRefTo != null){ //อัพเดทรหัสสาขา + รหัสผู้จำหน่าย
            $tValue = $tRefTo;
            switch ($tDataUpdate) {
                case "bchto": //รหัสสาขาที่ขอโอน
                    $tFieldUpdate = 'FTXrhBchTo';
                    break;
                case "splto": //รหัสผู้จำหน่ายที่ขอซื้อ
                    $tFieldUpdate = 'FTXrhRefFrm';
                    break;
                default:
            }
        }else{
            $tValue = $nQTY;
            switch ($tDataUpdate) {
                case "reqbuy": //ตัวเลขขอซื้อ 
                    $tFieldUpdate = 'FCXpdQtyPRS';
                    break; 
                case "reqtnf": //ตัวเลขขอโอน
                    $tFieldUpdate = 'FCXpdQtyTR';
                    break;
                case "notapv": //ตัวเลขไม่อนุมัติ
                    $tFieldUpdate = 'FCXpdQtyCancel';
                    break;
                default:
            }    
        }

        $aUpdate = array(
            'NameField'         => $tFieldUpdate,
            'Value'             => $tValue
        );

        $this->Managedocorderbranch_model->FSaMMNGUpdateQTYinTemp($aUpdate,$aWhere);
    }

    //สร้างเอกสาร
    public function FSvCMNGCreateDocRef(){
        $aItemDoc    = $this->input->post('aItemDoc');

        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }

        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPage'             => 1,
            'nRow'              => 1000000,
            'tTextDocRef'       => rtrim($tTextDocRef, ", ")
        );

        //ข้อมูลรายละเอียดในใบสั่งซื้อ
        $aGenTable  = array(
            'aDataList'         => $this->Managedocorderbranch_model->FSaMMNGListArray($aData),
            'tTextDocRef'       => rtrim($tTextDocRef, ", ")
        );

        $tViewDataTable = $this->load->view('document/managedocorderbranch/wManagedocCreateDocRef', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //อนุมัติเอกสาร
    public function FSvCMNGAproveDocRef(){
        $aItemDoc    = $this->input->post('aItemDoc');
        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }

        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPage'             => 1,
            'nRow'              => 1000000,
            'tTextDocRef'       => rtrim($tTextDocRef, ", ")
        );

        $aItemDoc = $this->Managedocorderbranch_model->FSaMMNGListArray($aData);

        if($aItemDoc['rtCode'] == 1){
            $aItemDocSendMQ = $aItemDoc['raItems'];

            $tDocRefKeyReqSPL = '';
            for($k=0; $k<count($aItemDocSendMQ); $k++){
                $tDocType   = $aItemDocSendMQ[$k]['MGTDocType'];
                $tDocStatus = $aItemDocSendMQ[$k]['MGTStaExport'];

                if($tDocType != 3 && $tDocStatus == 1){
                    $tDocBch  = $aItemDocSendMQ[$k]['FTBchCode'];
                    $tDocRef  = $aItemDocSendMQ[$k]['MGTDocRef'];

                    if($tDocType == 1){ //ขอโอน

                    }else if($tDocType == 2){ //ขอซื้อ
                        $tDocRefKeyReqSPL .= $tDocRef.',';
                    }
                }
            }
            if($tDocRefKeyReqSPL != '' || $tDocRefKeyReqSPL != null){
                $tDocRefKeyReqSPL = rtrim($tDocRefKeyReqSPL, ", ");
            }

            for($i=0; $i<count($aItemDocSendMQ); $i++){
                $tDocType   = $aItemDocSendMQ[$i]['MGTDocType'];
                $tDocStatus = $aItemDocSendMQ[$i]['MGTStaExport'];

                if(($tDocType == 2 || $tDocType == 4) && $tDocStatus == 1){ //จะส่งเอกสารขอซื้อไปอนุมัติอย่างเดียว
                    $tDocBch  = $aItemDocSendMQ[$i]['FTBchCode'];
                    $tDocRef  = $aItemDocSendMQ[$i]['MGTDocRef'];

                    if($tDocType == 1){ //ขอโอน
                        $tFunction = 'TCNTPdtReqBchHD';
                    }else if($tDocType == 2){ //ขอซื้อ
                        $tFunction = 'TCNTPdtReqSplHD';
                    }else if($tDocType == 4){ //ขอซื้อแฟรนไซส์
                        $tFunction = 'TCNTPdtReqSplHD';
                    }

                    $aMQParams = [
                        "queueName" => "CN_QDocApprove",
                        "params"    => [
                            'ptFunction'    => $tFunction,
                            'ptSource'      => 'AdaStoreBack',
                            'ptDest'        => 'MQReceivePrc',
                            'ptFilter'      => $tDocRefKeyReqSPL,
                            'ptData'        => json_encode([
                                "ptBchCode"     => $tDocBch,
                                "ptDocNo"       => $tDocRef,
                                "ptDocType"     => $tDocType,
                                "ptUser"        => $this->session->userdata("tSesUsername"),
                            ])
                        ]
                    ];
                    
                    // เชื่อม Rabbit MQ
                    FCNxCallRabbitMQ($aMQParams);
                }
            }
        }
    }

    //วิ่ง MQ (สร้างเอกสาร)
    public function FSxCMNGCallMQCreateDoc(){
        $tTextDocRef    = $this->input->post('tTextDocRef');
        $aGetDocRef     = $this->Managedocorderbranch_model->FSaMMNGGetDocRefCallMQ($tTextDocRef);

        if($aGetDocRef['rtCode'] == 1){
            for($i=0; $i<count($aGetDocRef['raItems']); $i++){
               
                $aMQParams = [
                    "queueName" => "CN_QGenDoc",
                    "params"    => [
                        'ptFunction'    => "TCNTPdtReqMgtHD",
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => '',
                        'ptData'        => json_encode([
                            "ptBchCode"     => $aGetDocRef['raItems'][$i]['FTBchCode'],
                            "ptDocNo"       => $aGetDocRef['raItems'][$i]['FTXphDocNo'],
                            "ptDocType"     => $aGetDocRef['raItems'][$i]['FNXrhDocType'],
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];

                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
            }
        }
    }

    //ส่งออก หรือ ส่งออกและดาวน์โหลด
    public function FSvCMNGExport(){
        $tTypeExport    = $this->input->post('tTypeExport'); 
        $aItemDoc       = $this->input->post('aItemDoc');
        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }

        $aData = array(
            'tTextDocRef'       => rtrim($tTextDocRef, ", ")
        );
        $aGetDocRef     = $this->Managedocorderbranch_model->FSaMMNGGetDocRefForExport($aData);

        if($tTypeExport == 'genfile'){ //สร้างไฟล์ excel (ซ่อมไฟล์)
            $tQueueName = 'CN_QGenExport';
        }else{ //ส่งอีเมล์
            $tQueueName = 'CN_QSendMail';
        }

        $aMQParams = [
            "queueName" => $tQueueName,
            "params"    => [
                'ptFunction'    => "TCNTPdtReqSplHD",
                'ptSource'      => 'AdaStoreBack',
                'ptDest'        => 'MQReceivePrc',
                'ptFilter'      => '', 
                'ptData'        => ''
            ]
        ];
        $aItemDocMutiExport = array();
        $tDocRefKeyReqSPL = '';  //เอาเฉพาะเอกสารที่ต้องส่ง ต่อ string กัน
        if($aGetDocRef['rtCode'] == 1){
            for($i=0; $i<count($aGetDocRef['raItems']); $i++){
                $aItemDocRef = [
                    "ptBchCode"     => $aGetDocRef['raItems'][$i]['FTBchCode'],
                    "ptDocNo"       => $aGetDocRef['raItems'][$i]['FTXphDocNo'],
                    "ptDocType"     => $aGetDocRef['raItems'][$i]['FNXrhDocType'],
                    "ptUser"        => $this->session->userdata("tSesUsername"),
                ];
                array_push($aItemDocMutiExport,$aItemDocRef);

                //ต่อ string
                $tDocRefKeyReqSPL .= $aGetDocRef['raItems'][$i]['FTXphDocNo'].',';
            }
            $tDocRefKeyReqSPL = rtrim($tDocRefKeyReqSPL, ", ");
            $aMQParams["params"]["ptFilter"]    = $tDocRefKeyReqSPL;
            $aMQParams["params"]["ptData"]      = json_encode($aItemDocMutiExport);

            // print_r($aMQParams);
            // exit;
            // เชื่อม Rabbit MQ
            FCNxCallRabbitMQ($aMQParams);
        }
    }

    // Create By :Napat(Jame) 17/11/2021
    public function FSvCMNGPageChkPdtStkBal(){
        
        // ดึงข้อมูลสินค้าคงคลัง
        $aGetPdtStkBal = $this->Managedocorderbranch_model->FSaMMNGGetPdtStkBal([
            'tBchCodeOrder' => $this->input->post('ptBchCodeOrder'),
            'tPdtCode'      => $this->input->post('ptPdtCode'),
            'tBchCode'      => $this->input->post('ptBchCode'),
            'tWahCode'      => $this->input->post('ptWahCode'),
            'nLangEdit'     => $this->session->userdata("tLangEdit")
        ]);
        
        $this->load->view('document/managedocorderbranch/wManagedocChkPdtStkBal', [
            'aGetPdtStkBal'     => $aGetPdtStkBal,
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
        ]);

    }

    //หาว่าเอกสารที่สร้างเเล้ว เอกสารขอโอนไหนบ้างที่ต้องส่ง Noti
    public function FSxCMNGNoti(){
        $tDocNo         = "'".$this->input->post('ptDocNo')."'";
        $aFindDocRef    = $this->Managedocorderbranch_model->FSaMMNGGetDocRefCallMQ($tDocNo);

        if($aFindDocRef['rtCode'] == 1){
            for($i=0; $i<count($aFindDocRef['raItems']); $i++){
               
                if($aFindDocRef['raItems'][$i]['FNXrhDocType'] == 1){ //เอกสารขอโอนต้องส่งแจ้งเตือนไปที่สาขา
                    //ส่ง Noti
                    $tNotiID        = FCNtHNotiGetNotiIDByDocRef($aFindDocRef['raItems'][$i]['FTXphDocNo']);
                    $aMQParamsNoti  = [
                        "queueName"     => "CN_SendToNoti",
                        "tVhostType"    => "NOT", 
                        "params"        => [
                            "oaTCNTNoti" => array( //ตาราง master NOTI Toppic อะไร 
                                "FNNotID"       => $tNotiID,
                                "FTNotCode"     => '00010',
                                "FTNotKey"      => 'TCNTPdtReqHqHD', //ชื่อตารางของเอกสารที่เราทำ 
                                "FTNotBchRef"   => $aFindDocRef['raItems'][$i]['FTBchCode'],
                                "FTNotDocRef"   => $aFindDocRef['raItems'][$i]['FTXphDocNo'],
                            ),
                            "oaTCNTNoti_L" => array(
                                0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 1,
                                    "FTNotDesc1"    => 'เอกสารใบขอโอน ' . $aFindDocRef['raItems'][$i]['FTXphDocNo'] ,
                                    "FTNotDesc2"    => 'รหัสสาขา '.$aFindDocRef['raItems'][$i]['FTBchCode'].' ขอโอนจาก '.$aFindDocRef['raItems'][$i]['rtBCHCodeTo'],
                                ),
                                1 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 2,
                                    "FTNotDesc1"    => 'Transfer Request #'.$aFindDocRef['raItems'][$i]['FTXphDocNo'],
                                    "FTNotDesc2"    => 'Branch code '.$aFindDocRef['raItems'][$i]['FTBchCode'].' To Branch code '.$aFindDocRef['raItems'][$i]['rtBCHCodeTo'],
                                )
                            ),
                            "oaTCNTNotiAct" => array( //ลำดับเหตุการณ์ (เหมือนประมาณใบเคลม กรณี มีเหตุการณ์ต่อท้าย)
                                0 => array( 
                                    "FNNotID"           => $tNotiID,
                                    "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                    "FTNoaDesc"         => 'รหัสสาขา '.$aFindDocRef['raItems'][$i]['FTBchCode'].' ขอโอนจาก '.$aFindDocRef['raItems'][$i]['rtBCHCodeTo'],
                                    "FTNoaDocRef"       => $aFindDocRef['raItems'][$i]['FTXphDocNo'],
                                    "FNNoaUrlType"      =>  1, //1 : ภายใน , 2 : ภายนอก
                                    "FTNoaUrlRef"       => 'docTRB/2/0', 
                                ),
                            ), 
                            "oaTCNTNotiSpc" => array(
                                0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FTNotType"    => '1', //ต้นทาง
                                    "FTNotStaType" => '1', //1 : เฉพาะสาขา , 2 : ยกเว้นสาขา
                                    "FTAgnCode"    => '',
                                    "FTAgnName"    => '',
                                    "FTBchCode"    => $aFindDocRef['raItems'][$i]['FTBchCode'],
                                    "FTBchName"    => $aFindDocRef['raItems'][$i]['FTBchName'],
                                ),
                                1 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FTNotType"    => '2',  //ปลายทาง [ที่จะต้องเห็นสาขาที่ 1]
                                    "FTNotStaType" => '1',  //1 : เฉพาะสาขา , 2 : ยกเว้นสาขา
                                    "FTAgnCode"    => '',
                                    "FTAgnName"    => '',
                                    "FTBchCode"    => $aFindDocRef['raItems'][$i]['rtBCHCodeTo'],
                                    "FTBchName"    => $aFindDocRef['raItems'][$i]['rtBCHNameTo'],
                                )
                            ),
                            "ptUser"        => $this->session->userdata('tSesUsername'),
                        ]
                    ];

                    FCNxCallRabbitMQ($aMQParamsNoti);
                }

            }
        }
    }
    
}           