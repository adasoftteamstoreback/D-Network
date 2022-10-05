<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Managedocpurchaseorder_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/Managedocpurchaseorder/Managedocpurchaseorder_model');
    }

    public function index($nMNPBrowseType,$tMNPBrowseOption){
        $aDataConfigView    = array(
            'nMNPBrowseType'        => $nMNPBrowseType,
            'tMNPBrowseOption'      => $tMNPBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('docMnpDocPO/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('docMnpDocPO/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('document/managedocpurchaseorder/wManagedocPO',$aDataConfigView);
    }

    //List
    public function FSvCMNPFormSearchList(){
        $this->load->view('document/managedocpurchaseorder/wManagedocPOSearchList');   
    }

    //Table
    public function FSvCMNPTableImport(){
        $tAdvanceSearchData     = $this->input->post('oAdvanceSearch');
        $nPage                  = $this->input->post('nPageCurrent');
        $aAlwEvent              = FCNaHCheckAlwFunc('docMnpDocPO/0/0');
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
            'aAdvanceSearch'    => $tAdvanceSearchData
        );

        $aList      = $this->Managedocpurchaseorder_model->FSaMMNPImportList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );

        $tViewDataTable = $this->load->view('document/managedocpurchaseorder/wMNPImportDocDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCMNPPageAdd(){
        try{

            //ล้างค่าใน Temp
            $this->Managedocpurchaseorder_model->FSaMMNPDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => array('rtCode'=>'99')
            );

            $tViewPageAdd       = $this->load->view('document/managedocpurchaseorder/wManagedocPOPageAdd',$aDataConfigViewAdd,true);
            $aReturnData        = array(
                'tViewPageAdd'      => $tViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //หน้าจอแก้ไข
    public function FSvCMNPPageEdit(){
        try{
            $ptDocumentNumber = $this->input->post('ptDocumentNumber');

            //ล้างค่าใน Temp
            $this->Managedocpurchaseorder_model->FSaMMNPDeletePDTInTmp();

            //Move DT To Temp
            $this->Managedocpurchaseorder_model->FSaMMNPMoveDTToTemp($ptDocumentNumber);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataWhere = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );

            // Get Data Document HD
            $aDataDocHD         = $this->Managedocpurchaseorder_model->FSaMMNPGetDataDocHD($aDataWhere);

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => $aDataDocHD
            );

            $tViewPageAdd       = $this->load->view('document/managedocpurchaseorder/wManagedocPOPageAdd',$aDataConfigViewAdd,true);
            $aReturnData        = array(
                'tViewPageAdd'      => $tViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //สินค้าใน Temp
    public function FSvCMNPPOTableDTTemp(){
        try {

            $tMNPDocNo    = $this->input->post('ptMNPDocNo');
            if($tMNPDocNo == '' || $tMNPDocNo == null){
                $tMNPDocNo = 'DUMMY';
            }

            $aDataWhere = array(
                'FTXthDocNo'        => $tMNPDocNo,
                'FTXthDocKey'       => 'TAPTPoMgtDT'
            );
            $aDataDocDTTemp         = $this->Managedocpurchaseorder_model->FSaMMNPGetDocDTTempListPage($aDataWhere);

            $aDataView = array(
                'aDataDocDTTemp'    => $aDataDocDTTemp
            );
            $tMNPPdtAdvTableHtml = $this->load->view('document/managedocpurchaseorder/wManageAdvTableData', $aDataView, true);

            $aReturnData = array(
                'tMNPPdtAdvTableHtml'   => $tMNPPdtAdvTableHtml,
                'nStaEvent'             => '1',
                'tStaMessg'             => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent'             => '500',
                'tStaMessg'             => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //อัพโหลดจากไฟล์ To Temp
    public function FSvCMNPPOImportFile(){
        $aPackData      = $this->input->post('aPackdata');
        $tDocNo         = $this->input->post('tDocNo');
        $nPackData      = FCNnHSizeOf($aPackData);
        $aInsPackdata   = array();

        $aWhereData = array(
            'tKey'              => 'TAPTPoMgtDT',
            'tTableRef'         => 'TCNTDocDTTmp',
            'tSessionID'        => $this->session->userdata("tSesSessionID")
        );

        if($nPackData > 1){
            for($i=1; $i<$nPackData; $i++){
                $aObject = array(
                    'FTXthDocNo'        => $tDocNo,
                    'FNXtdSeqNo'        => $i,
                    'FTXthDocKey'       => 'TAPTPoMgtDT',
                    'FTBchCode'         => $aPackData[$i][0],
                    'FTPdtCode'         => (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1],
                    'FTXtdPdtName'      => (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2],
                    'FTXtdDocNoRef'     => (isset($aPackData[$i][3]) == '') ? '' : trim($aPackData[$i][3]),
                    'FTXtdBarCode'      => (isset($aPackData[$i][5]) == '') ? '' : $aPackData[$i][5],
                    'FCStkQty'          => (isset($aPackData[$i][6]) == '') ? '' : $aPackData[$i][6], //จำนวนจากใบขอซื้อ
                    'FCXtdQty'          => (isset($aPackData[$i][7]) == '') ? '' : $aPackData[$i][7], //จำนวนที่ SPL ยืนยัน
                    'FTTmpStatus'       => (isset($aPackData[$i][8]) == '') ? '' : $aPackData[$i][8],
                    'FTTmpRemark'       => '', //ถ้า rmk มากกว่า 1 จะมีว่า error อะไร
                    'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                    'FDCreateOn'        => date('Y-m-d')
                );
                array_push($aInsPackdata,$aObject);
            }   

            $this->Managedocpurchaseorder_model->FSaMMNPImportExcelToTmp($aWhereData,$aInsPackdata);

            //เช็คว่ารหัสสินค้ามีอยู่จริงไหม
            $aValidateData = array(
                'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                'tFieldName'        => 'FTPdtCode',
                'tTableName'        => 'TCNMPDT',
                'tErrMsg'           => '$&ไม่พบสินค้าในระบบ'
            );
            FCNnDocTmpChkCodeInDB($aValidateData);
        }
    }

    //ลบสินค้าในตาราง Temp [หลายรายการ]
    public function FSxCMNPPODeleteEventMuti(){
        $tBchCode       = $this->input->post('tBchCode');
        $tDocNo         = $this->input->post('tDocNo');
        $aSeqCode       = $this->input->post('tSeqCode');
        $tSession       = $this->session->userdata('tSesSessionID');
        $nCount         = FCNnHSizeOf($aSeqCode);
        $aResDel        = '';
        
        if($nCount > 1){
            for($i=0;$i<$nCount;$i++){
                $aDataMaster = array(
                    'FTBchCode'     => $tBchCode,
                    'FTXthDocNo'    => $tDocNo,
                    'FNXtdSeqNo'    => $aSeqCode[$i],
                    'FTXthDocKey'   => 'TAPTPoMgtDT',
                    'FTSessionID'   => $tSession
                );
                $aResDel = $this->Managedocpurchaseorder_model->FSaMMNPPdtTmpMultiDel($aDataMaster);
            }
        }
        
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    //ลบสินค้าในตาราง Temp [รายการเดียว]
    public function FSxCMNPPODeleteEventSingle(){
        $aDataWhere = array(
            'FTXphDocNo'    => $this->input->post('ptDocNo'),
            'FTPdtCode'     => $this->input->post('ptPDTCode'),
            'FNXpdSeqNo'    => $this->input->post('pnSeqNo'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FTXthDocKey'   => 'TAPTPoMgtDT'
        );
        $aResDel = $this->Managedocpurchaseorder_model->FSaMMNPPdtTmpSingleDel($aDataWhere);
        echo json_encode($aResDel);
    }

    //Event เพิ่มข้อมูล (HD DT)
    public function FSxCMNPPOEventAdd(){
        try {
            $aDataDocument  = $this->input->post();
            $tDocNo         = (isset($aDataDocument['oetMGTPODocNo'])) ? $aDataDocument['oetMGTPODocNo'] : 'DUMMY';
            $nQTYBch        = $this->input->post('pnQTYBch');
            $nQTYPdt        = $this->input->post('pnQTYPdt');

            // Array Data HD
            $aDataHD = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $this->session->userdata('tSesUsrBchCodeDefault'),
                'FTXphDocNo'        => '',
                'FTXPhRefFile'      => $aDataDocument['oetMNPFileNameImport'],
                'FTSplCode'         => $aDataDocument['oetMGTSPLCodeTo'],
                'FNXphQtyBch'       => $nQTYBch,
                'FCXphQtyPdt'       => $nQTYPdt,
                'FTXrhStaDoc'       => 1,
                'FTXrhStaPrcDoc'    => null,
                'FTXphRmk'          => '',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            $aStoreParam = array(
                "tTblName"    => 'TAPTPoMgtHD',
                "tDocType"    => '1',
                "tBchCode"    => $this->session->userdata('tSesUsrBchCodeDefault'),
                "tShpCode"    => "",
                "tPosCode"    => "",
                "dDocDate"    => date("Y-m-d")
            );
            $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
            $aDataHD['FTXphDocNo']      = $aAutogen[0]["FTXxhDocNo"];
          
            // [Add] Update Document HD
            $this->Managedocpurchaseorder_model->FSxMMNPAddUpdateHD($aDataHD);
         
            // [Update] DocNo -> Temp
            $this->Managedocpurchaseorder_model->FSxMMNPAddUpdateDocNoToTemp($aDataHD);

            // [Add] Doc DTTemp -> HDDoc
            $this->Managedocpurchaseorder_model->FSaMMNPMoveDTTmpToHDDoc($aDataHD);

            // [Add] Doc DTTemp -> DT
            $this->Managedocpurchaseorder_model->FSaMMNPMoveDTTmpToDT($aDataHD);

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataHD['FTXphDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'        => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //Event เเก้ไขข้อมูล (HD DT)
    public function FSxCMNPPOEventEdit(){
        try {
            $aDataDocument  = $this->input->post();
            $tDocNo         = $aDataDocument['oetMGTPODocNo'];
            $nQTYBch        = $this->input->post('pnQTYBch');
            $nQTYPdt        = $this->input->post('pnQTYPdt');

            // Array Data HD
            $aDataHD = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $this->session->userdata('tSesUsrBchCodeDefault'),
                'FTXphDocNo'        => $tDocNo,
                'FTXPhRefFile'      => $aDataDocument['oetMNPFileNameImport'],
                'FTSplCode'         => $aDataDocument['oetMGTSPLCodeTo'],
                'FNXphQtyBch'       => $nQTYBch,
                'FCXphQtyPdt'       => $nQTYPdt,
                'FTXrhStaDoc'       => 1,
                'FTXrhStaPrcDoc'    => null,
                'FTXphRmk'          => '',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            );

            $this->db->trans_begin();
          
            // [Add] Update Document HD
            $this->Managedocpurchaseorder_model->FSxMMNPAddUpdateHD($aDataHD);
         
            // [Update] DocNo -> Temp
            $this->Managedocpurchaseorder_model->FSxMMNPAddUpdateDocNoToTemp($aDataHD);

            // [Add] Doc DTTemp -> HDDoc
            $this->Managedocpurchaseorder_model->FSaMMNPMoveDTTmpToHDDoc($aDataHD);

            // // [Add] Doc DTTemp -> DT
            $this->Managedocpurchaseorder_model->FSaMMNPMoveDTTmpToDT($aDataHD);

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataHD['FTXphDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.'
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'        => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //Event ยกเลิกเอกสาร
    public function FSoCMNPUpdateStaDocCancel(){
        $tDocNo     = $this->input->post('tDocNo');
        $aDataUpdate = array(
            'FTXphDocNo'        => $tDocNo,
            'FTXrhStaDoc'       => '3'
        );

        $aStaDoc    = $this->Managedocpurchaseorder_model->FSaMMNPUpdateStaDocCancel($aDataUpdate); 
        $aReturn    = array(
            'rtCode' => $aStaDoc['rtCode'],
            'rtDesc' => $aStaDoc['rtDesc']
        );
        echo json_encode($aReturn);
    }

    //Event สร้างเอกสาร
    public function FSoCMNPCreateDoc(){
        $aItemDoc       = $this->input->post('aItemDoc');
        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }

        $tTextDocRef    = rtrim($tTextDocRef, ", ");
        $aGetDocRef     = $this->Managedocpurchaseorder_model->FSaMMNPGetDocRefCallMQ($tTextDocRef);
        if($aGetDocRef['rtCode'] == 1){
            for($i=0; $i<count($aGetDocRef['raItems']); $i++){
               
                $aMQParams = [
                    "queueName" => "CN_QGenDoc",
                    "params"    => [
                        'ptFunction'    => "TAPTPoMgtHD",
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => '',
                        'ptData'        => json_encode([
                            "ptBchCode"     => $aGetDocRef['raItems'][$i]['FTBchCode'],
                            "ptDocNo"       => $aGetDocRef['raItems'][$i]['FTXpdDocPo'],
                            "ptDocType"     => 2,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];

                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
            }
        }
    }

    //Event อนุมัติเอกสาร
    public function FSoCMNPAproveDoc(){
        $aItemDoc    = $this->input->post('aItemDoc');
        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }
        $tTextDocRef    = rtrim($tTextDocRef, ", ");
        $aItemDoc       = $this->Managedocpurchaseorder_model->FSaMMNPGetDocRefCallMQ($tTextDocRef);

        if($aItemDoc['rtCode'] == 1){
            $aItemDocSendMQ = $aItemDoc['raItems'];

            //เอกสารใบสั่งซื้อ ต่อ string 
            $tDocRefKeyReqSPL = '';
            for($k=0; $k<count($aItemDocSendMQ); $k++){
                $tDocRef            = $aItemDocSendMQ[$k]['FTXpdDocPo'];
                $tDocRefKeyReqSPL   .= $tDocRef.',';
            }
            if($tDocRefKeyReqSPL != '' || $tDocRefKeyReqSPL != null){
                $tDocRefKeyReqSPL = rtrim($tDocRefKeyReqSPL, ", ");
            }

            for($i=0; $i<count($aItemDocSendMQ); $i++){

                $aMQParams = [
                    "queueName" => "AP_QDocApprove",
                    "params"    => [
                        'ptFunction'    => 'TAPTPoHD',
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => $tDocRefKeyReqSPL,
                        'ptData'        => json_encode([
                            "ptBchCode"     => $aItemDocSendMQ[$i]['FTBchCode'],
                            "ptDocNo"       => $aItemDocSendMQ[$i]['FTXpdDocPo'],
                            "ptDocType"     => 2,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];

                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
            }
        }
    }

    //Event ส่งเมล หรือ สร้างเอกสาร excel
    public function FSoCMNPGenFileAndSendMail(){
        $tTypeExport    = $this->input->post('tTypeExport'); 
        $aItemDoc       = $this->input->post('aItemDoc');
        if(count($aItemDoc) > 0){
            $tTextDocRef = "";
            for($i=0; $i<count($aItemDoc); $i++){
                $tTextDocRef .= " '$aItemDoc[$i]',";
            }
        }

        if($tTypeExport == 'genfile'){ //สร้างไฟล์ excel (ซ่อมไฟล์)
            $tQueueName = 'CN_QGenExport';
        }else{ //ส่งอีเมล์
            $tQueueName = 'CN_QSendMail';
        }

        $aData = array(
            'tTextDocRef'       => rtrim($tTextDocRef, ", ")
        );
        $aGetDocRef     = $this->Managedocpurchaseorder_model->FSaMMNPGetDocRefForExport($aData);

        $aMQParams = [
            "queueName" => $tQueueName,
            "params"    => [
                'ptFunction'    => "TAPTPoHD",
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
                    "ptDocNo"       => $aGetDocRef['raItems'][$i]['FTXpdDocPo'],
                    "ptDocType"     => 2,
                    "ptUser"        => $this->session->userdata("tSesUsername"),
                ];
                array_push($aItemDocMutiExport,$aItemDocRef);

                //ต่อ string
                $tDocRefKeyReqSPL .= $aGetDocRef['raItems'][$i]['FTXpdDocPo'].',';
            }
            $tDocRefKeyReqSPL = rtrim($tDocRefKeyReqSPL, ", ");
            $aMQParams["params"]["ptFilter"]    = $tDocRefKeyReqSPL;
            $aMQParams["params"]["ptData"]      = json_encode($aItemDocMutiExport);

            // เชื่อม Rabbit MQ
            FCNxCallRabbitMQ($aMQParams);
        }
    }

    //อัพเดทสาขาใหม่ ตาม seq
    public function FSxCMNPUpdateSeqInTemp(){
        $ptBCHCode      = $this->input->post('ptBCHCode'); 
        $pnSeqNo        = $this->input->post('pnSeqNo');

        $aData = array(
            'tBCHCode'          => $ptBCHCode,
            'nSeqNo'            => $pnSeqNo
        );
        $this->Managedocpurchaseorder_model->FSaMMNPUpdateSeqInTemp($aData);


    }
  
}