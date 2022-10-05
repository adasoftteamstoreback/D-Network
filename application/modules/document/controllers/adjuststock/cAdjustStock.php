<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cAdjustStock extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('company/company/mCompany');
        $this->load->model('payment/rate/mRate');
        $this->load->model('document/adjuststock/mAdjustStock');
    }

    public function index($nBrowseType,$tBrowseOption){
        //======================= เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie =======================
        $aCookieMenuCode    = array(
            'name'          => 'tMenuCode',
            'value'         => json_encode('AST001'),
            'expire'        => 0
        );
        $this->input->set_cookie($aCookieMenuCode);
        $aCookieMenuName    = array(
            'name'          => 'tMenuName',
            'value'         => json_encode('ใบตรวจนับ - ยืนยัน สินค้าคงคลัง'),
            'expire'        => 0
        );
        $this->input->set_cookie($aCookieMenuName);
        // ========================================================================================
        $aParams    = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        $aDataConfigView    = array(
            'nBrowseType'       => $nBrowseType,    // nBrowseType สถานะการเข้าเมนู 0 :เข้ามาจากการกด Menu / 1 : เข้ามาจากการเพิ่มข้อมูลจาก Modal Browse ข้อมูล
            'tBrowseOption'     => $tBrowseOption,  // 
            'aAlwEvent'         => FCNaHCheckAlwFunc('dcmAST/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('dcmAST/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'aParams'           => $aParams ,
        );
        $this->load->view('document/adjuststock/wAdjustStock',$aDataConfigView);
    }

    // หน้าหลัก
    public function FSvCASTFormSearchList(){
        $this->load->view('document/adjuststock/wAdjustStockFormSearchList');    
    }

    // หน้าหลัก Datatable
    public function FSoCASTDataTable(){
        try{
            $aAdvanceSearch     = $this->input->post('oAdvanceSearch');
            $nPage              = $this->input->post('nPageCurrent');
            // Controle Event
            $aAlwEvent          = FCNaHCheckAlwFunc('dcmAST/0/0');
            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            // Page Current 
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");
            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID'           => $nLangEdit,
                'nPage'             => $nPage,
                'nRow'              => 10,
                'aAdvanceSearch'    => $aAdvanceSearch
            );
            $aDataList  = $this->mAdjustStock->FSaMASTGetDataTable($aDataCondition);
            $aConfigView    = array(
                'nPage'             => $nPage,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aAlwEvent'         => $aAlwEvent,
                'aDataList'         => $aDataList,
            );

            $tASTViewDataTable  = $this->load->view('document/adjuststock/wAdjustStockDataTable',$aConfigView,true);
            $aReturnData        = array(
                'tViewDataTable'    => $tASTViewDataTable,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ลบข้อมูล
    public function FSoCASTDeleteEventDoc(){
        try{    
            $tASTDocNo  = $this->input->post('tASTDocNo');
            $aDataMaster = array(
                'tASTDocNo'     => $tASTDocNo
            );
            $aResDelDoc = $this->mAdjustStock->FSnMASTDelDocument($aDataMaster);
            if($aResDelDoc['rtCode'] == '1'){
                $aDataStaReturn  = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Delete Document Success',
                    'tLogType' => 'INFO',
                    'tDocNo'   => $tASTDocNo,
                    'tEventName' => 'ลบใบตรวจนับ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }else{
                $aDataStaReturn  = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => 'Delete Document Fail',
                    'tLogType' => 'ERROR',
                    'tDocNo'   => $tASTDocNo,
                    'tEventName' => 'ลบใบตรวจนับ',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        }catch(Exception $Error){
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo'   => $tASTDocNo,
                'tEventName' => 'ลบใบตรวจนับ',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
                
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aDataStaReturn);
        echo json_encode($aDataStaReturn);
    }

    // เข้าหน้าเพิ่มข้อมูล
    public function FSoCASTAddPage(){
        try{
            // Clear Product List IN Doc Temp
            $tTblSelectData = "TCNTPdtAdjStkHD";
            $this->mAdjustStock->FSxMASTClearPdtInTmp($tTblSelectData);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            // Get Option Doc Save
            $nOptDocSave        = FCNnHGetOptionDocSave();
            // Get Option Scan SKU
            $nOptScanSku        = FCNnHGetOptionScanSku();
            //Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");

            $aDataWhere = array('FNLngID' => $nLangEdit);

            $tAPIReq    = "";
            $tMethodReq = "GET";
            $aCompData  = $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhere);  

           
            $tCmpCode       = $aCompData['raItems']['rtCmpCode'];

            if($aCompData['rtCode'] == '1'){
                $tBchCode       = $aCompData['raItems']['rtCmpBchCode'];
                $tCmpRteCode    = $aCompData['raItems']['rtCmpRteCode'];
                $tVatCode       = $aCompData['raItems']['rtVatCodeUse'];
                $aVatRate       = FCNoHCallVatlist($tVatCode); 
                $cVatRate       = $aVatRate['FCVatRate'][0];
                $aDataRate      = array(
                    'FTRteCode' => $tCmpRteCode,
                    'FNLngID'   => $nLangEdit
                );
                $aResultRte     = $this->mRate->FSaMRTESearchByID($aDataRate);
                if($aResultRte['rtCode'] == 1){
                    $cXthRteFac = $aResultRte['raItems']['rcRteRate'];
                }else{
                    $cXthRteFac = "";
                }
            }else{
                $tBchCode       = FCNtGetBchInComp();
                $tCmpRteCode    = "";
                $tVatCode       = "";
                $cVatRate       = "";
                $cXthRteFac     = "";
            }

            // Get Department Code
            // $tUsrLogin  = $this->session->userdata('tSesUsername');
            // $tDptCode   = FCNnDOCGetDepartmentByUser($tUsrLogin);

            // Get ข้อมูลสาขา และ ร้านค้าของ User ที่ login
            // $aDataShp   = array(
            //     'FNLngID'   => $nLangEdit,
            //     'tUsrLogin' => $tUsrLogin
            // );
            // $this->mAdjustStock->FSaMASTGetShpCodeForUsrLogin($aDataShp);
            // $aDataUserGroup = $this->mAdjustStock->FSaMASTGetShpCodeForUsrLogin($aDataShp);
            // if(empty($aDataUserGroup)){
                
                // $tBchCode   = "";
                // $tBchName   = "";
                $tMerCode   = "";
                $tMerName   = "";
                $tShpType   = "";
                $tShpCode   = "";
                $tShpName   = "";
                $tWahCode   = "";
                $tWahName   = "";

                // if($this->session->userdata("tSesUsrLevel") == "HQ"){
                //     $tBchCode = $this->session->userdata("tSesUsrBchCom");
                //     $tBchName = $this->session->userdata("tSesUsrBchNameCom");
                // }else{
                //     $tBchCode = $this->session->userdata("tSesUsrBchCode");
                //     $tBchName = $this->session->userdata("tSesUsrBchName");
                //     if($this->session->userdata("tSesUsrLevel") == "SHP"){
                //         $tShpCode = $this->session->userdata("tSesUsrShpCode");
                //         $tShpName = $this->session->userdata("tSesUsrShpName");
                //         $tMerCode = $this->session->userdata("tSesUsrMerCode");
                //         $tMerName = $this->session->userdata("tSesUsrMerName");
                //     }
                // }

            // }else{
            //     $tBchCode   = "";
            //     $tBchName   = "";
            //     $tMerCode   = "";
            //     $tMerName   = "";
            //     $tShpType   = "";
            //     $tShpCode   = "";
            //     $tShpName   = "";
            //     $tWahCode   = "";
            //     $tWahName   = "";

                // เช็ค user ว่ามีการผูกสาขาไว้หรือไม่
                // if(isset($aDataUserGroup["FTBchCode"]) && !empty($aDataUserGroup["FTBchCode"])){
                //     $tBchCode   = $aDataUserGroup["FTBchCode"];
                //     $tBchName   = $aDataUserGroup["FTBchName"];
                // }

                // เช็ค user ว่ามีการผูกกลุ่มร้านค้าไว้หรือไม่
                // if(isset($aDataUserGroup["FTMerCode"]) && !empty($aDataUserGroup["FTMerCode"])){
                //     $tMerCode   = $aDataUserGroup["FTMerCode"];
                //     $tMerName   = $aDataUserGroup["FTMerName"];
                // }

                // เช็ค user ว่ามีการผูกร้านค้าไว้หรือไม่
                // $tShpType   = $aDataUserGroup["FTShpType"];
                // if(isset($aDataUserGroup["FTShpCode"]) && !empty($aDataUserGroup["FTShpCode"])){
                //     $tShpCode   = $aDataUserGroup["FTShpCode"];
                //     $tShpName   = $aDataUserGroup["FTShpName"];
                // }

                // if(isset($aDataUserGroup["FTWahCode"]) && !empty($aDataUserGroup["FTWahCode"])){
                //     $tWahCode   = $aDataUserGroup["FTWahCode"];
                //     $tWahName   = $aDataUserGroup["FTWahName"];
                // }
            // }

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'nOptDocSave'       => $nOptDocSave,
                'nOptScanSku'       => $nOptScanSku,
                'tCmpRteCode'       => $tCmpRteCode,
                'tVatCode'          => $tVatCode,
                'cVatRate'          => $cVatRate,
                'cXthRteFac'        => $cXthRteFac,
                // 'tDptCode'          => $tDptCode,
                // 'tBchCode'          => $tBchCode,
                // 'tBchName'          => $tBchName,
                // 'tMerCode'          => $tMerCode,
                // 'tMerName'          => $tMerName,
                // 'tShpType'          => $tShpType,
                // 'tShpCode'          => $tShpCode,
                // 'tShpName'          => $tShpName,
                // 'tWahCode'          => $tWahCode,
                // 'tWahName'          => $tWahName,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'tBchCompCode'      => FCNtGetBchInComp(),
                'tBchCompName'      => FCNtGetBchNameInComp(),
                'tCmpCode'          => $tCmpCode
            );

            $tASTViewPageAdd    = $this->load->view('document/adjuststock/wAdjustStockAdd',$aDataConfigViewAdd,true);
            $aReturnData        = array(
                'tASTViewPageAdd'   => $tASTViewPageAdd,
                'tUsrLevel'         => $this->session->userdata("tSesUsrLevel"),
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

    // โหลดสินค้าใน DT Temp
    public function FSoCASTPdtAdvTblLoadData(){
        try{
            $tSearchAll         = $this->input->post('ptSearchAll');
            $tASTDocNo          = $this->input->post('ptASTDocNo');
            $tASTStaApv         = $this->input->post('ptASTStaApv');
            $tASTAjhStaStkApv   = $this->input->post('ptASTAjhStaStkApv');
            $tASTStaDoc         = $this->input->post('ptASTStaDoc');
            $nASTApvSeqChk      = $this->input->post('pnASTApvSeqChk');
            $nASTPageCurrent    = $this->input->post('pnASTPageCurrent');
            $tPdtCode           = $this->input->post('ptPdtCode');
            $tPunCode           = $this->input->post('ptPunCode');

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aColumnShow = array(
                (object) array('FNShwSeq' => 1,  'FTShwFedShw' => 'FTPdtCode',          'FTShwNameUsr' => 'รหัสสินค้า',     'FTShwFedStaUsed' => '1','FNShwColWidth' => 0,'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 2,  'FTShwFedShw' => 'FTXtdPdtName',       'FTShwNameUsr' => 'ชื่อสินค้า',     'FTShwFedStaUsed' => '1','FNShwColWidth' => 0,'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 3,  'FTShwFedShw' => 'FTXtdBarCode',       'FTShwNameUsr' => 'รหัสบาร์โค้ด',  'FTShwFedStaUsed' => '1','FNShwColWidth' => 0,'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 4,  'FTShwFedShw' => 'FCPdtUnitFact',      'FTShwNameUsr' => 'อัตราส่วน',    'FTShwFedStaUsed' => '1','FNShwColWidth' => 0,'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 5,  'FTShwFedShw' => 'FTPunName',          'FTShwNameUsr' => 'หน่วย',       'FTShwFedStaUsed' => '1','FNShwColWidth' => 0,'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 6,  'FTShwFedShw' => 'FCAjdWahB4Adj',      'FTShwNameUsr' => 'ก่อนตรวจนับ',  'FTShwFedStaUsed' => '1','FNShwColWidth' => 0, 'FTShwStaAlwEdit' => 0),
                (object) array('FNShwSeq' => 7,  'FTShwFedShw' => 'FCAjdUnitQtyC1',     'FTShwNameUsr' => 'ตรวจนับ',     'FTShwFedStaUsed' => '1','FNShwColWidth' => 0, 'FTShwStaAlwEdit' => 1),
                (object) array('FNShwSeq' => 8,  'FTShwFedShw' => 'FCAfterCount',       'FTShwNameUsr' => 'หลังตรวจนับ',  'FTShwFedStaUsed' => '1','FNShwColWidth' => 0, 'FTShwStaAlwEdit' => 0)
            );

            $aDataWhere         = array(
                'tSearchAll'    => $tSearchAll,
                'FTXthDocNo'    => $tASTDocNo,
                'FTXthDocKey'   => "TCNTPdtAdjStkHD",
                'nPage'         => $nASTPageCurrent,
                'nRow'          => 10,
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            );
            
            if($tASTAjhStaStkApv == '1'){
                $aDataInsert = array(
                    'FTXthDocNo'    => $tASTDocNo,
                    'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                    'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                );
                $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactorDT($aDataInsert);
            }

            $aDataDTList        = $this->mAdjustStock->FSaMASTGetDTTempListPage($aDataWhere);
            $aDataView          = array(
                'tASTStaApv'        => $tASTStaApv,
                'tASTStaDoc'        => $tASTStaDoc,
                'nASTApvSeqChk'     => $nASTApvSeqChk,
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aColumnShow'       => $aColumnShow,
                'aDataDTList'       => $aDataDTList,
                // 'aDataDTSum'        => $aDataDTSum,
                'nPage'             => $nASTPageCurrent,
                'tPunCode'          => $tPunCode,
                'tPdtCode'          => $tPdtCode,
                'tASTAjhStaStkApv'  => $tASTAjhStaStkApv,
            );
            
            $tASTPdtAdvTableView    = $this->load->view('document/adjuststock/advancetable/wAdjustStockPdtAdvTableData',$aDataView,true);
            $aReturnData = array(
                'tASTPdtAdvTableView'   => $tASTPdtAdvTableView,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success View',
                'nASTPageCurrent'  => $nASTPageCurrent
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เลือกว่าโชว์คอลัมน์อะไรบ้างใน DT (ยกเลิกใช้ไปเเล้ว)
    public function FSoCASTAdvTblShowColList(){
        try{
            $tTableShowColums   = "TCNTPdtAdjStkDT";
            $aAvailableColumn   = FCNaDCLAvailableColumn($tTableShowColums);
            $aDataViewAdvTbl    = array(
                'aAvailableColumn'  => $aAvailableColumn
            );
            
            $tViewTableShowCollist  = $this->load->view('document/adjuststock/advancetable/wAdjustStockTableShowColList',$aDataViewAdvTbl,true);
            $aReturnData = array(
                'tViewTableShowCollist' => $tViewTableShowCollist,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เลือกว่าโชว์คอลัมน์อะไรบ้างใน DT (ยกเลิกใช้ไปเเล้ว)
    public function FSoCASTShowColSave(){
        try{
            $this->db->trans_begin();

            $aASTColShowSet         = $this->input->post('aASTColShowSet');
            $aASTColShowAllList     = $this->input->post('aASTColShowAllList');
            $aASTColumnLabelName    = $this->input->post('aASTColumnLabelName');
            $nASTStaSetDef          = $this->input->post('nASTStaSetDef');

            // Table Set Show Colums
            $tTableShowColums   = "TCNTPdtAdjStkDT";
            FCNaDCLSetShowCol($tTableShowColums,'','');
            if($nASTStaSetDef == '1'){
                FCNaDCLSetDefShowCol($tTableShowColums);
            }else{
                for($i = 0; $i < FCNnHSizeOf($aASTColShowSet); $i++){
                    FCNaDCLSetShowCol($tTableShowColums,1,$aASTColShowSet[$i]);
                }
            }

            // Reset Seq Advannce Table
            FCNaDCLUpdateSeq($tTableShowColums,'','','');
            $q  = 1;
            for($n = 0; $n<FCNnHSizeOf($aASTColShowAllList); $n++){
                FCNaDCLUpdateSeq($tTableShowColums,$aASTColShowAllList[$n],$q,$aASTColumnLabelName[$n]);
                $q++;
            }

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Eror Not Save Colums'
                );
            }else{
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            }
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    public function FSbCheckHaveProductForTransfer(){
        $tDocNo = $this->input->post("tDocNo");
        $nNumPdt = $this->mAdjustStock->FSnMTFWCheckPdtTempForTransfer($tDocNo);
        if($nNumPdt>0){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }

    }

    // Add Pdt ลง Dt (File)
    public function FSvCASTAddPdtIntoTableDT(){

        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCode   = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCode");
        $tAjhDocNo  = $this->input->post('ptAjhDocNo');
        $nAdjStkOptionAddPdt = $this->input->post('pnAdjStkSubOptionAddPdt');
        $pjPdtData  = $this->input->post('pjPdtData');
        $aPdtData   = json_decode($pjPdtData);



        $aDataWhere = array(
            'FTAjhDocNo'    => $tAjhDocNo,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
        );
        $nCounts  =  $this->mAdjustStock->FSaMAdjStkGetCountDTTemp($aDataWhere);

        // วนตามรายการสินค้าที่เพิ่มเข้ามา
        for ($nI=0;$nI<FCNnHSizeOf($aPdtData);$nI++){
                 
            $pnPdtCode  = $aPdtData[$nI]->pnPdtCode;
            $ptBarCode  = $aPdtData[$nI]->ptBarCode; 
            $ptPunCode  = $aPdtData[$nI]->ptPunCode;
            $pcPrice    = $aPdtData[$nI]->packData->Price;
            $ptPlcCode  = $aPdtData[$nI]->packData->LOCSEQ;
            $nCounts    = $nCounts+1;

            $aDataPdtWhere = array(
                'FTAjhDocNo'    => $tAjhDocNo,  
                'FTBchCode'     => $tBchCode,   // จากสาขาที่ทำรายการ
                'FTPdtCode'     => $pnPdtCode,  // จาก Browse Pdt
                'FTPunCode'     => $ptPunCode,  // จาก Browse Pdt
                'FTBarCode'     => $ptBarCode,  // จาก Browse Pdt
                'pcPrice'       => $pcPrice,    // ราคาสินค้าจาก Browse Pdt
                'FTPlcCode'     => $ptPlcCode,
                'nCounts'       => $nCounts,    //จำนวนล่าสุด Seq
                'FNLngID'       => $this->session->userdata("tLangID"), //รหัสภาษาที่ login
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                'nAdjStkSubOptionAddPdt' => $nAdjStkOptionAddPdt,
            );

            $aDataPdtMaster  = $this->mAdjustStock->FSaMAdjStkGetDataPdt($aDataPdtWhere); // Data Master Pdt
            $nStaInsPdtToTmp = $this->mAdjustStock->FSaMAdjStkInsertPDTToTemp($aDataPdtMaster, $aDataPdtWhere);
        }
    }

    // การบันทึกข้อมูลลงฐานข้อมูล
    public function FSaCASTAddEvent(){
        try{
            $aDataDocument = $this->input->post();
 

            $tAjhDocDate    = $this->input->post('oetASTDocDate')." ".$this->input->post('oetASTDocTime');
            $tAdjStkBch     = $this->input->post('oetASTBchCode');  // นับได้เฉพาะในสาขาที่เข้าใช้งานเท่านั้น (สาขาสร้าง = สาขาที่นับ)
            $tUserLogin     = $this->session->userdata('tSesUsername');

            if($this->input->post('oetASTCountType') == "1"){
                $FDAjhDateFrm = NULL;
                $FDAjhDateTo = NULL;
            }else{
                $FDAjhDateFrm = $this->input->post('oetASTDateFrm');
                $FDAjhDateTo = $this->input->post('oetASTDateTo');
            }
            
            $aDataMaster = array(
                'tIsAutoGenCode'  => $this->input->post('ocbASTStaAutoGenCode'), // ต้องการรัน DocNo อัตโนมัติหรือไม่ 
                'FTBchCode'       => $tAdjStkBch,
                'FTAjhDocNo'      => $this->input->post('oetASTDocNo'),
                'FNAjhDocType'    => 11, // ประเภทใบนับสต็อค
                'FTAjhDocType'    => '3', // ประเภทใบนับย่อย
                'FDAjhDocDate'    => $tAjhDocDate,
                'FTAjhBchTo'      => $tAdjStkBch,  //นับภายใต้สาขา
                'FTAjhMerchantTo' => $this->input->post('oetASTMerCode'), // นับภายใต้กลุ่มร้านค้า
                'FTAjhShopTo'     => $this->input->post('oetASTShopCode'), //นับภายใต้ร้านค้า
                'FTAjhPosTo'      => $this->input->post('oetASTPosCode'), // นับภายใต้เครื่องจุดขาย
                'FTAjhWhTo'       => $this->input->post('oetASTWahCode'), //นับภายใต้คลังสินค้า
                'FTAjhPlcCode'    => NULL, // เก็บข้อมูลของที่เก็บ
                'FTDptCode'       => $this->input->post('ohdASTDptCode'), //แผนกผู้ใช้ login
                'FTUsrCode'       => $tUserLogin, // User Login
                'FTRsnCode'       => $this->input->post('oetASTRsnCode'), // เหตุผลการตรวจนับ
                'FTAjhRmk'        => $this->input->post('otaASTRmk'),  // ข้อมูลหมายเหตุ
                'FNAjhDocPrint'   => '1',
                'FTAjhApvSeqChk'  => '1', //$this->input->post('ostASTApvSeqChk')  //ใช้การตรวจนับ 1:นับ 1  2:นับ2  3:กำหนดเอง
                'FTAjhApvCode'    => NULL,
                'FTAjhStaApv'     => NULL,
                'FTAjhStaPrcStk'  => NULL,
                'FNAjdLayRow'     => '',
                'FNAjdLayCol'     => '',
                'FTAjhStaDoc'     => 1, //สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                'FNAjhStaDocAct' => !empty($aDataDocument['ocbASTStaDocAct']) ? $aDataDocument['ocbASTStaDocAct'] : 0,
                'FTAjhDocRef'     => NULL,
                'FDLastUpdOn'     => date('Y-m-d H:i:s'),
                'FTLastUpdBy'     => $tUserLogin,
                'FDCreateOn'      => date('Y-m-d H:i:s'),
                'FTCreateBy'      => $tUserLogin,
                'FTAjhCountType'  => $this->input->post('oetASTCountType'),
                'FDAjhDateFrm'    => $FDAjhDateFrm,
                'FDAjhDateTo'     => $FDAjhDateTo,
            );
            //Setup Doc No
            if($aDataMaster['tIsAutoGenCode'] == '1'){
                //Auto Gen ADjustStock
                // $aGenCode = FCNaHGenCodeV5('TCNTPdtAdjStkHD','3');
                // if($aGenCode['rtCode'] == '1'){
                //     $aDataMaster['FTAjhDocNo'] = $aGenCode['rtAjhDocNo'];
                // }

                // Update new gencode
                // 18/05/2020 Napat(Jame)
                $aStoreParam = array(
                    "tTblName"    => 'TCNTPdtAdjStkHD',                           
                    "tDocType"    => '3',                                          
                    "tBchCode"    => $tAdjStkBch,                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d")       
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTAjhDocNo']  = $aAutogen[0]["FTXxhDocNo"];
            }

            $aDataWhere = array(
                'FTAjhDocNo'    => $aDataMaster['FTAjhDocNo'],
                'FTBchCode'     => $aDataMaster['FTBchCode'],  
                'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            );

            $this->db->trans_begin();

            $this->mAdjustStock->FSaMASTAddUpdateDocNoInDocTemp($aDataWhere);   // Update DocNo ในตาราง Doctemp
            $this->mAdjustStock->FSaMASTAddUpdateHD($aDataMaster);  // ยังไม่ได้ update
            $this->FSaMASTAddTmpToDT($aDataMaster['FTAjhDocNo']);  // Temp to DT and Clear Temp       

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Unsuccess Add Document",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataMaster['FTAjhDocNo'],
                    'tEventName' => 'บันทึกใบตรวจนับ',
                    'nLogCode' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTAjhDocNo'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Add Document',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataMaster['FTAjhDocNo'],
                    'tEventName' => 'บันทึกใบตรวจนับ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }

        }catch(Exception $Error){
            $aReturn = array(
                'nStaEvent'     => '900',
                'tStaMessg'     => "Unsuccess Add Document",
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataMaster['FTAjhDocNo'],
                'tEventName' => 'บันทึกใบตรวจนับ',
                'nLogCode' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
            echo $Error;
        }

        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    // การแก้ไขข้อมูลลงฐานข้อมูล
    public function FSaCASTEditEvent(){
        try{
            $aDataDocument  = $this->input->post();
            if($this->input->post('oetASTCountType') == "1"){
                $FDAjhDateFrm = NULL;
                $FDAjhDateTo = NULL;
            }else{
                $FDAjhDateFrm = $this->input->post('oetASTDateFrm');
                $FDAjhDateTo = $this->input->post('oetASTDateTo');
            }

            $tAjhDocDate    = $this->input->post('oetASTDocDate')." ".$this->input->post('oetASTDocTime');
            $tAdjStkBch     = $this->input->post('oetASTBchCode');  // นับได้เฉพาะในสาขาที่เข้าใช้งานเท่านั้น (สาขาสร้าง = สาขาที่นับ)
            $tUserLogin     = $this->session->userdata('tSesUsername');
            $aDataMaster = array(
                'FTBchCode'       => $tAdjStkBch,
                'FTAjhDocNo'      => $this->input->post('oetASTDocNo'),
                'FNAjhDocType'    => 11, // ประเภทใบนับสต็อค
                'FTAjhDocType'    => '3', // ประเภทใบนับย่อย
                'FDAjhDocDate'    => $tAjhDocDate,
                'FTAjhBchTo'      => $tAdjStkBch,  //นับภายใต้สาขา
                'FTAjhMerchantTo' => $this->input->post('oetASTMerCode'), // นับภายใต้กลุ่มร้านค้า
                'FTAjhShopTo'     => $this->input->post('oetASTShopCode'), //นับภายใต้ร้านค้า
                'FTAjhPosTo'      => $this->input->post('oetASTPosCode'), // นับภายใต้เครื่องจุดขาย
                'FTAjhWhTo'       => $this->input->post('oetASTWahCode'), //นับภายใต้คลังสินค้า
                'FTAjhPlcCode'    => NULL, // เก็บข้อมูลของที่เก็บ
                'FTDptCode'       => $this->input->post('ohdASTDptCode'), //แผนกผู้ใช้ login
                'FTUsrCode'       => $tUserLogin, // User Login
                'FTRsnCode'       => $this->input->post('oetASTRsnCode'), // เหตุผลการตรวจนับ
                'FTAjhRmk'        => $this->input->post('otaASTRmk'),  // ข้อมูลหมายเหตุ
                'FNAjhDocPrint'   => '1',
                'FTAjhApvSeqChk'  => $this->input->post('ostASTApvSeqChk'),  //ใช้การตรวจนับ 1:นับ 1  2:นับ2  3:กำหนดเอง
                'FTAjhApvCode'    => NULL,
                'FTAjhStaApv'     => NULL,
                'FTAjhStaPrcStk'  => NULL,
                'FNAjdLayRow'     => '',
                'FNAjdLayCol'     => '',
                'FTAjhStaDoc'     => 1, //สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                'FNAjhStaDocAct' => !empty($aDataDocument['ocbASTStaDocAct']) ? $aDataDocument['ocbASTStaDocAct'] : 0,
                'FTAjhDocRef'     => NULL,
                'FDLastUpdOn'     => date('Y-m-d H:i:s'),
                'FTLastUpdBy'     => $tUserLogin,
                'FDCreateOn'      => date('Y-m-d H:i:s'),
                'FTCreateBy'      => $tUserLogin,
                'FTAjhCountType'  => $this->input->post('oetASTCountType'),
                'FDAjhDateFrm'    => $FDAjhDateFrm,
                'FDAjhDateTo'     => $FDAjhDateTo
            );
            $aDataWhere = array(
                'FTAjhDocNo'    => $aDataMaster['FTAjhDocNo'],
                'FTBchCode'     => $aDataMaster['FTBchCode'],  
                'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            );
            $this->db->trans_begin();   

            $this->mAdjustStock->FSaMASTAddUpdateDocNoInDocTemp($aDataWhere);   // Update DocNo ในตาราง Doctemp 
            $this->mAdjustStock->FSaMASTAddUpdateHD($aDataMaster);  // ยังไม่ได้ update
            $this->FSaMASTAddTmpToDT($aDataMaster['FTAjhDocNo']);  // Temp to DT and Clear Temp       
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Unsuccess Edit Document",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataMaster['FTAjhDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบตรวจนับ',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTAjhDocNo'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Edit Document',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataMaster['FTAjhDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบตรวจนับ',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        }catch(Exception $Error){
            $aReturn = array(
                'nStaEvent'     => '900',
                'tStaMessg'     => "Unsuccess Edit Document",
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataMaster['FTAjhDocNo'],
                'tEventName' => 'แก้ไขและบันทึกใบตรวจนับ',
                'nLogCode' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
            echo $Error;
        }

        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    // เพิ่มสินค้าใน PDT DT
    public function FSaMASTAddTmpToDT($ptAjhDocNo = ''){
        $aDataWhere = array(
            'FTAjhDocNo'    => $ptAjhDocNo,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
        );

        // Insert Temp ลง DT
        $aResInsDT = $this->mAdjustStock->FSaMASTInsertTmpToDT($aDataWhere);
    }

    // แก้ไข PDT DT
    public function FSvCASTEditPdtIntoTableDT(){

        $tXthDocNo    = $this->input->post('ptXthDocNo');
        $tEditSeqNo   = $this->input->post('ptEditSeqNo');
        $aField       = $this->input->post('paField');
        $aValue       = $this->input->post('paValue');      
        

        $aDataWhere = array(
            'FTAjhDocNo'    => $tXthDocNo,
            'FNXtdSeqNo'    => $tEditSeqNo,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
        );
        
        $aDataUpdateDT  = array();

        foreach($aField as $key => $FieldName){
            $aDataUpdateDT[$FieldName] = $aValue[$key];   
        }

        //edit In line
        $aResUpdDTTmpInline = $this->mAdjustStock->FSnMASTUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
    }

    // ลบข้อมูล
    public function FSvCASTRemovePdtInDTTmp(){

        $ptRoute    = $this->input->post('ptRoute');
        $tDocNo     = $this->input->post('ptXthDocNo');
        
        if($ptRoute == 'dcmASTEventAdd'){
            $tDocNo = "";
        }

        $aDataWhere = array(
            'FTXthDocNo'    => $tDocNo,
           'FTPdtCode'      => $this->input->post('ptPdtCode'),
           'FNXtdSeqNo'     => $this->input->post('ptSeqno'),
           'FTXthDocKey'    => 'TCNTPdtAdjStkHD',
           'FTSessionID'    => $this->session->userdata('tSesSessionID'),
        );

        $aResDel = $this->mAdjustStock->FSnMASTDelDTTmp($aDataWhere);

        $aDataInsert = array(
            'FTXthDocNo'    => $tDocNo,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'tUser'         => $this->session->userdata('tSesUsername'),
            'nLangEdit'     => $this->session->userdata("tLangEdit")
        );

        $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactor($aDataInsert);
    }

    // ลบข้อมูลหลายตัว
    public function FSvCASTPdtMultiDeleteEvent(){
        $FTXthDocNo = $this->input->post('tDocNo');
        $FTPdtCode  = $this->input->post('tPdtCode');
        $FTPunCode  = $this->input->post('tPunCode');
        $aSeqCode   = $this->input->post('tSeqCode');
        $tSession   = $this->session->userdata('tSesSessionID');
        $nCount     = FCNnHSizeOf($aSeqCode);

        if($nCount > 1){
            for($i=0; $i<$nCount; $i++){

                $aDataMaster = array(
                    'FTXthDocNo'    => $FTXthDocNo,
                    'FNXtdSeqNo'    => $aSeqCode[$i],
                    'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                    'FTSessionID'   => $tSession
                );
                $aResDel = $this->mAdjustStock->FSaMASTPdtTmpMultiDel($aDataMaster);
            }
        }else{
                $aDataMaster = array(
                    'FTXthDocNo'    => $FTXthDocNo,
                    'FNXtdSeqNo'    => $aSeqCode[0],
                    'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                    'FTSessionID'   => $tSession
                );
            $aResDel = $this->mAdjustStock->FSaMASTPdtTmpMultiDel($aDataMaster);
        }

        $aDataInsert = array(
            'FTXthDocNo'    => $FTXthDocNo,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'tUser'         => $this->session->userdata('tSesUsername'),
            'nLangEdit'     => $this->session->userdata("tLangEdit")
        );

        $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactor($aDataInsert);

        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    // แก้ไขข้อมูลใน DT Temp
    public function FSoCASTUpdateDataInline(){
        // $tASTDocNo      = $this->input->post('tASTDocNo');
        // $tASTSeqNo      = $this->input->post('tASTSeqUpd');
        // $tASTBchCode    = $this->input->post('tASTBchCode');
        // $aASTDataUpdate = $this->input->post('aASTDataEditInLine');
        // $tASTSessionID  = $this->session->userdata('tSesSessionID');
        // $FDAjdDateTime  = date('Y-m-d H:i:s');

        // $this->db->trans_begin();

        $aDataUpdateInLine  = array(
            'tField'        => $this->input->post('ptField'),
            'tValue'        => $this->input->post('pnVal')
        );

        $aDataWhereUpdInLine    = array(
            'FTXthDocNo'    => $this->input->post('ptDocNo'),
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            'FNXtdSeqNo'    => $this->input->post('pnSeq'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        );
        $aResUpd = $this->mAdjustStock->FSaMUpdateDocDTInLine($aDataUpdateInLine,$aDataWhereUpdInLine);

        // if($this->db->trans_status() === FALSE){
        //     $this->db->trans_rollback();
        //     $aDataReturn = array(
        //         'tStaCode'      => '500',
        //         'tStaMsgErr'    => 'Error Not Update In Line'
        //     );
        // }else{
        //     $this->db->trans_commit();
        //     $aDataReturn = array(
        //         'tStaCode'      => '1',
        //         'tStaMsgErr'    => 'Update Inline Success'
        //     );
        // }
        echo json_encode($aResUpd);
    }

    // เรียกหน้า  Edit
    public function FSoCASTEditPage(){
        $tXthDocNo          = $this->input->post('ptXthDocNo');
        // Get Option Show Decimal
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        
        // Get Option Doc Save
        $nOptDocSave        = FCNnHGetOptionDocSave();

        // Get Option Scan SKU
        $nOptScanSku        = FCNnHGetOptionScanSku();

        //Lang ภาษา
        $nLangEdit          = $this->session->userdata("tLangEdit");

        $aDataWhere  = array(
            'FTAjhDocNo'    => $tXthDocNo,
            'FNLngID'       => $nLangEdit,
            'nRow'          => 10000,
            'nPage'         => 1,
            'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
        );
        $aResult    = $this->mAdjustStock->FSaMASTGetHD($aDataWhere); 

        $this->mAdjustStock->FSaMASTInsertDTToTemp($aDataWhere); //MoveDT to Temp
        
        $aDataEdit = array(
            'nOptDecimalShow'   => $nOptDecimalShow,
            'nOptDocSave'       => $nOptDocSave,
            'nOptScanSku'       => $nOptScanSku,
            'aDataDocHD'        => $aResult,
            'tBchCompCode'      => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'      => $this->session->userdata("tSesUsrBchNameDefault")
        );
        $this->load->view('document/adjuststock/wAdjustStockAdd',$aDataEdit);

        $aReturnData = array(
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Call Page Success',
            //เพิ่มใหม่
            'tLogType'      => 'INFO',
            'tDocNo'        => $tXthDocNo,
            'tEventName'    => 'เรียกดูเอกสารใบตรวจนับ',
            'nLogCode'      => '001',
            'nLogLevel'     => '',
            'FTXphUsrApv'   => $aResult['raItems']['FTAjhUsrCodeAppove']
        );
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
    }

    // ยกเลิกเอกสาร
    public function FSvCASTCancel(){
    
        $tXthDocNo      =  $this->input->post('tXthDocNo');
        $tReasonCancel  =  $this->input->post('tReasonCancel');
        
        $aDataUpdate    =  array(
            'FTAjhDocNo'  => $tXthDocNo,
            'FTRsnCode'   => $tReasonCancel,
        );

            $aStaApv = $this->mAdjustStock->FSVMASTCancel($aDataUpdate);

            if($aStaApv['rtCode'] == 1 ){
                $aApv = array(
                    'nStaEvent'     => 1,
                    'tStaMessg'     => "Cancel Document Success",
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tXthDocNo,
                    'tEventName'    => 'ยกเลิกใบตรวจนับ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            }else{
                $aApv = array(
                    'nStaEvent'     => 2,
                    'tStaMessg'     => 'Unsuccess Edit Document',
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tXthDocNo,
                    'tEventName'    => 'ยกเลิกใบตรวจนับ',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
            FSoCCallLogMQ($aApv);
            echo json_encode($aApv);
    }

    // อนุมัติเอกสาร (สำหรับ BCH)
    public function FSvCASTApprove(){
        try{
            $tXthDocNo          = $this->input->post('tXthDocNo');
            $tUsrBchCode        = $this->input->post('tBchCode');
            $tCountType        = $this->input->post('tASTCountType');

            $tApvCode = $this->session->userdata('tSesUsername');
            if (empty($tApvCode) && $tApvCode == '') {
                $tApvCode = get_cookie('tUsrCode');
            }
            
            // $tXthStaApv = $this->input->Post('tXthStaApv'); lnwza

            // $aReturnUpdDTBal = $this->mAdjustStock->FSaMUpdateDTBal($tXthDocNo);
            // if( $aReturnUpdDTBal['tCode'] == '1' ){
                $aDataUpdate = array(
                    'FTAjhDocNo'    => $tXthDocNo,
                    'FTAjhApvCode'  => $this->session->userdata('tSesUsername'),
                    'FTAjhCountType'  => $tCountType
                );
                $aASTStaApv = $this->mAdjustStock->FSvMASTApprove($aDataUpdate);

                if($aASTStaApv['rtCode'] == '1'){
                    $aMQParams = [
                        "queueName"  =>  "ADJUSTSTOCK",
                        "params"   => [
                            "ptBchCode"      => $tUsrBchCode,
                            "ptDocNo"        => $tXthDocNo,
                            "ptDocType"      => '3',
                            "ptUser"         => $this->session->userdata('tSesUsername')
                        ]
                    ];
                    $mqchk = FCNxCallRabbitMQ($aMQParams);
                    if ($mqchk['rtCode'] == 1) {
                        $aDataGetDataHD     =   $this->mAdjustStock->FSaMASTGetHD(array(
                            'FTAjhDocNo'    => $tXthDocNo,
                            'FNLngID'       => $this->session->userdata("tLangEdit")
                        ));
                        if($aDataGetDataHD['rtCode']=='1' && $aDataGetDataHD['raItems']['FTAjhCountType']=='1'){
                            $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTAjhDocNo']);
                            $aMQParamsNoti = [
                                "queueName" => "CN_SendToNoti",
                                "tVhostType" => "NOT",
                                "params"    => [
                                                "oaTCNTNoti" => array(
                                                                "FNNotID"       => $tNotiID,
                                                                "FTNotCode"     => '00005',
                                                                "FTNotKey"      => 'TCNTPdtAdjStkHD',
                                                                "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCodeLogin'],
                                                                "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                ),
                                                "oaTCNTNoti_L" => array(
                                                                    0 => array(
                                                                        "FNNotID"       => $tNotiID,
                                                                        "FNLngID"       => 1,
                                                                        "FTNotDesc1"    => 'เอกสารใบตรวจนับยืนยัน #'.$aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                        "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCodeLogin'].' ตรวจนับสต็อค รอ HQ ยืนยันสต็อค',
                                                                    ),
                                                                    1 => array(
                                                                        "FNNotID"       => $tNotiID,
                                                                        "FNLngID"       => 2,
                                                                        "FTNotDesc1"    => 'Adjust Stock #'.$aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                        "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCodeLogin'].' Adjust Stock. Wait HQ Confirm Stock.',
                                                                    )
                                                ),
                                                "oaTCNTNotiAct" => array(
                                                                    0 => array( 
                                                                            "FNNotID"       => $tNotiID,
                                                                            "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                                                            "FTNoaDesc"      => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCodeLogin'].' ตรวจนับสต็อค รอ HQ ยืนยันสต็อค',
                                                                            "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                            "FNNoaUrlType"   =>  1,
                                                                            "FTNoaUrlRef"    => 'dcmAST/2/0',
                                                                            ),
                                                    ), 
                                                "oaTCNTNotiSpc" => array(
                                                                    0 => array(
                                                                            "FNNotID"       => $tNotiID,
                                                                            "FTNotType"    => '1',
                                                                            "FTNotStaType" => '1',
                                                                            "FTAgnCode"    => '',
                                                                            "FTAgnName"    => '',
                                                                            "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCodeLogin'],
                                                                            "FTBchName"    => $aDataGetDataHD['raItems']['FTBchNameLogin'],
                                                                    ),
                                                                    1 => array(
                                                                            "FNNotID"       => $tNotiID,
                                                                            "FTNotType"    => '2',
                                                                            "FTNotStaType" => '1',
                                                                            "FTAgnCode"    => '',
                                                                            "FTAgnName"    => '',
                                                                            "FTBchCode"    => $this->session->userdata("tUsrBchHQCode"),
                                                                            "FTBchName"    => $this->session->userdata("tUsrBchHQName"),
                                                                    ),
                                                ),
                                    "ptUser"        => $this->session->userdata('tSesUsername'),
                                ]
                            ];
                            FCNxCallRabbitMQ($aMQParamsNoti);
                        }
                        $aReturn = array(
                            'nStaEvent'     => '1',
                            'tStaMessg'     => "Approve Document Success",
                            'tLogType'      => 'INFO',
                            'tDocNo'        => $tXthDocNo,
                            'tEventName'    => 'อนุมัติใบตรวจนับ',
                            'nLogCode'      => '001',
                            'nLogLevel'     => '',
                            'FTXphUsrApv'   => $tApvCode
                        );
                    }else{
                        $aReturn = array(
                            'nStaEvent' => '905',
                            'tStaMessg' => 'Connect Rabbit MQ Fail'.' '.$mqchk['rtDesc'],
                            'tLogType'      => 'EVENT',
                            'tDocNo'        => $tXthDocNo,
                            'tEventName'    => 'อนุมัติใบสั่งซื้อ',
                            'nLogCode'      => '905',
                            'nLogLevel'     => 'Critical',
                            'FTXphUsrApv'   => $aDataUpdate['FTAjhApvCode']
                        );
                    }
                }else{
                    $aReturn = array(
                        'nStaEvent'     => '99',
                        'tStaMessg'     => language('common/main/main', 'tApproveFail'),
                        'tLogType'      => 'ERROR',
                        'tDocNo'        => $tXthDocNo,
                        'tEventName'    => 'อนุมัติใบตรวจนับ',
                        'nLogCode'      => '500',
                        'nLogLevel'     => 'Critical',
                        'FTXphUsrApv'   => $tApvCode
                    );
                }

            //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
            FSoCCallLogMQ($aReturn); 
            echo json_encode($aReturn);
            
        }catch(\ErrorException $err){

            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => language('common/main/main', 'tApproveFail')
            );
            echo json_encode($aReturn);
            return;
        }
    }

    // อนุมัติเอกสาร (สำหรับ HQ)
    public function FSvCASTHQApprove(){
        try{
            $tXthDocNo          = $this->input->post('tXthDocNo');
            $tUsrBchCode        = $this->input->post('tBchCode');
            $tCountType        = $this->input->post('tASTCountType');

            $aReturnUpdDTBal = $this->mAdjustStock->FSaMUpdateDTBal($tXthDocNo);
            if( $aReturnUpdDTBal['tCode'] == '1' ){
                $aDataUpdate = array(
                    'FTAjhDocNo'    => $tXthDocNo,
                    'FTAjhApvCode'  => $this->session->userdata('tSesUsername'),
                    'FTAjhCountType'  => $tCountType
                );
                $aASTStaApv = $this->mAdjustStock->FSvMASTHQApprove($aDataUpdate);

                if($aASTStaApv['rtCode'] == '1'){
                    $aMQParams = [
                        "queueName"  =>  "ADJUSTSTOCK",
                        "params"   => [
                            "ptBchCode"      => $tUsrBchCode,
                            "ptDocNo"        => $tXthDocNo,
                            "ptDocType"      => '3',
                            "ptUser"         => $this->session->userdata('tSesUsername')
                        ]
                    ];
                    $mqchk = FCNxCallRabbitMQ($aMQParams);

                    $aDataGetDataHD     =   $this->mAdjustStock->FSaMASTGetHD(array(
                        'FTAjhDocNo'    => $tXthDocNo,
                        'FNLngID'       => $this->session->userdata("tLangEdit")
                    ));
                    if($aDataGetDataHD['rtCode']=='1' && $aDataGetDataHD['raItems']['FTAjhCountType']=='1'){
                        $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTAjhDocNo']);
                    $aMQParamsNoti = [
                        "queueName" => "CN_SendToNoti",
                        "tVhostType" => "NOT",
                        "params"    => [
                                         "oaTCNTNoti" => array(
                                                         "FNNotID"       => $tNotiID,
                                                         "FTNotCode"     => '00005',
                                                         "FTNotKey"      => 'TCNTPdtAdjStkHD',
                                                         "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCodeLogin'],
                                                         "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTAjhDocNo'],
                                         ),
                                         "oaTCNTNoti_L" => array(
                                                            0 => array(
                                                                "FNNotID"       => $tNotiID,
                                                                "FNLngID"       => 1,
                                                                "FTNotDesc1"    => 'เอกสารใบตรวจนับยืนยัน #'.$aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                "FTNotDesc2"    => 'HQ ยืนยันสต็อค',
                                                            ),
                                                            1 => array(
                                                                "FNNotID"       => $tNotiID,
                                                                "FNLngID"       => 2,
                                                                "FTNotDesc1"    => 'Adjust Stock #'.$aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                "FTNotDesc2"    => 'HQ Confirm Stock',
                                                            )
                                        ),
                                         "oaTCNTNotiAct" => array(
                                                            0 => array( 
                                                                    "FNNotID"       => $tNotiID,
                                                                    "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                                                    "FTNoaDesc"      => 'HQ ยืนยันสต็อค',
                                                                    "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTAjhDocNo'],
                                                                    "FNNoaUrlType"   =>  1,
                                                                    "FTNoaUrlRef"    => 'dcmAST/2/0',
                                                                ),
                                             ), 
                                         "oaTCNTNotiSpc" => array(
                                                            0 => array(
                                                                    "FNNotID"       => $tNotiID,
                                                                    "FTNotType"    => '1',
                                                                    "FTNotStaType" => '1',
                                                                    "FTAgnCode"    => '',
                                                                    "FTAgnName"    => '',
                                                                    "FTBchCode"    => $this->session->userdata("tUsrBchHQCode"),
                                                                    "FTBchName"    => $this->session->userdata("tUsrBchHQName"),
                                                            ),
                                                            1 => array(
                                                                    "FNNotID"       => $tNotiID,
                                                                    "FTNotType"    => '2',
                                                                    "FTNotStaType" => '1',
                                                                    "FTAgnCode"    => '',
                                                                    "FTAgnName"    => '',
                                                                    "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCodeLogin'],
                                                                    "FTBchName"    => $aDataGetDataHD['raItems']['FTBchNameLogin'],
                                                            ),
                                         ),
                            "ptUser"        => $this->session->userdata('tSesUsername'),
                        ]
                    ];
                    // echo '<pre>';
                    // print_r($aMQParamsNoti['params']);
                    // echo '</pre>';
                    // echo json_encode($aMQParamsNoti['params']);
                    // die();
                    FCNxCallRabbitMQ($aMQParamsNoti);
                    }

                    if ($mqchk['rtCode'] == 1) {
                        $aReturn = array(
                            'nStaEvent'    => '1',
                            'tStaMessg'     => "HQ. Approve Document Success",
                            'tLogType'      => 'INFO',
                            'tDocNo'        => $tXthDocNo,
                            'tEventName'    => 'อนุมัติใบตรวจนับ (HQ)',
                            'nLogCode'      => '001',
                            'nLogLevel'     => '',
                            'FTXphUsrApv'   => $aDataUpdate['FTAjhApvCode']
                        );
                    }else{
                        $aReturn = array(
                            'nStaEvent' => '905',
                            'tStaMessg' => 'Connect Rabbit MQ Fail'.' '.$mqchk['rtDesc'],
                            'tLogType'      => 'EVENT',
                            'tDocNo'        => $tXthDocNo,
                            'tEventName'    => 'อนุมัติใบสั่งซื้อ',
                            'nLogCode'      => '905',
                            'nLogLevel'     => 'Critical',
                            'FTXphUsrApv'   => $aDataUpdate['FTAjhApvCode']
                        );
                    }
                    
                }else{
                    $aReturn = array(
                        'nStaEvent'    => '99',
                        'tStaMessg'     => "HQ. Approve Document Unsuccess",
                        'tLogType'      => 'ERROR',
                        'tDocNo'        => $tXthDocNo,
                        'tEventName'    => 'อนุมัติใบตรวจนับ',
                        'nLogCode'      => '500',
                        'nLogLevel'     => 'Critical',
                        'FTXphUsrApv'   => $aDataUpdate['FTAjhApvCode']
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '99',
                    'tStaMessg'     => "HQ. Approve Document Unsuccess",
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tXthDocNo,
                    'tEventName'    => 'อนุมัติใบตรวจนับ',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => $this->session->userdata('tSesUsername')
                );
            }
            
        }catch(\ErrorException $err){

            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'     => "HQ. Approve Document Unsuccess",
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tXthDocNo,
                'tEventName'    => 'อนุมัติใบตรวจนับ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $this->session->userdata('tSesUsername')
            );
            
        }

        if ($aReturn['nStaEvent'] != 905) {
            //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
            FSoCCallLogMQ($aReturn); 
        }
        echo json_encode($aReturn);
        return;
    }

    // Get สินค้า ตาม Pdt BarCode
    public function FSvCASTGetPdtBarCode(){

        $tBarCode = $this->input->post('tBarCode');
        $tSplCode = $this->input->post('tSplCode');

        $aPdtBarCode  = FCNxHGetPdtBarCode($tBarCode,$tSplCode);

        if($aPdtBarCode != 0){
            $jPdtBarCode = json_encode($aPdtBarCode);
            $aData = array(
                'aData' => $jPdtBarCode,
                'tMsg' 	=> 'OK',
            );
        }else{
            $aData = array(
                'aData' => 0,
                'tMsg' 	=> language('document/browsepdt/browsepdt', 'tPdtNotFound'),
            );
        }

        $jData = json_encode($aData);
        echo $jData;
    }

    // เพิ่มสินค้า
    public function FSvCAdjStkEventAddProducts(){
        try{

            $this->db->trans_begin();

            // // Clear Temp Before Insert
            // $aDataClear = array(
            //     'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
            //     'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            //     'tDeleteType'   => '1'
            // );
            // $this->mAdjustStock->FSxMAdjStkClearDTTmp($aDataClear);

            // settings variable
            $aDataCondition = $this->input->post('paCondition');
            $aGetDataInsert = $this->input->post('paDataInsert');

            
            $aDataInsert = array(
                'FTBchCode'     => $aGetDataInsert['tBchCode'],
                'FTWahCode'     => $aGetDataInsert['tWahCode'],
                'FTXthDocNo'    => $aGetDataInsert['tDocNo'],
                'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'tUser'         => $this->session->userdata('tSesUsername'),
                'nLangEdit'     => $this->session->userdata("tLangEdit")
            );
            $aResultPDT = $this->mAdjustStock->FSaMAdjStkEventAddProducts($aDataCondition,$aDataInsert);
            

            $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactor($aDataInsert);

            // tCode = 1 insert สำเร็จ
            if($aResultPDT['tCode'] == '1'){
                $this->db->trans_commit();
            }else{
                $this->db->trans_rollback();
            }
            echo json_encode($aResultPDT);
            
        }catch(Exception $Error){
            echo $Error;
        }
    }

    // การเพิ่มสินค้า ระหว่าง บาร์โค๊ด - บาร์โค๊ด (จากการสแกนผ่าน input)
    public function FSvCAdjStkEventAddProductsByBarCode(){
        try{

            $this->db->trans_begin();

            $aGetDataInsert = $this->input->post('paDataInsert');
            $aDataInsert = array(
                'tPDTCode'      => $this->input->post('ptPDTCode'),
                'tBarCode'      => $this->input->post('ptBarCode'),
                'tWahCode'      => $aGetDataInsert['tWahCode'],
                'FTBchCode'     => $aGetDataInsert['tBchCode'],
                'FTXthDocNo'    => $aGetDataInsert['tDocNo'],
                'FTXthDocKey'   => 'TCNTPdtAdjStkHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'tUser'         => $this->session->userdata('tSesUsername'),
                'nLangEdit'     => $this->session->userdata("tLangEdit")
            );
            $aResultPDT = $this->mAdjustStock->FSaMAdjStkEventAddProductsByBarCode($aDataInsert);

            $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactor($aDataInsert);
            
            if($aResultPDT['tCode'] == '1'){
                $this->db->trans_commit();
            }else{
                $this->db->trans_rollback();
            }
            echo json_encode($aResultPDT);
            
        }catch(Exception $Error){
            echo $Error;
        }
    }

    // การเพิ่มสินค้า ระหว่าง บาร์โค๊ด - บาร์โค๊ด (จากการสแกนผ่าน input)
    public function FSvCAdjStkEventUpdateAdjust(){
        try{

            $this->db->trans_begin();

            $aDataInsert = array(
                'tWahCode'      => $this->input->post('tWahCode'),
                'FTBchCode'     => $this->input->post('tBchCode'),
                'FTXthDocNo'    => $this->input->post('tDocCode'),
                'FTXthDocKey'   => "TCNTPdtAdjStkHD",
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'tUser'         => $this->session->userdata('tSesUsername'),
                'nLangEdit'     => $this->session->userdata("tLangEdit")
            );
            $aResultPDT = $this->mAdjustStock->FSaMAdjStkEventUpdateStockAdjust($aDataInsert);

            $this->mAdjustStock->FSaMAdjStkEventChangeAdjFactor($aDataInsert);
            
            
            if($aResultPDT['tCode'] == '1'){
                $this->db->trans_commit();
            }else{
                $this->db->trans_rollback();
            }
            echo json_encode($aResultPDT);
            
        }catch(Exception $Error){
            echo $Error;
        }
    }

    // หาว่าเอกสาร ของสาขานั้นมีการค้างอนุมัติไหม
    // ใบรับเข้า (คลัง) , ใบเบิกออก (คลัง) , ใบจ่ายโอน (คลัง) , ใบรับโอน (คลัง) , ใบโอนสินค้าระหว่างคลัง , ใบจ่ายโอน (สาขา) , ใบรับโอน (สาขา) , ใบโอนสินค้าระหว่างสาขา 
    // ใบรับของ , ใบลดหนี้แบบมีสินค้า
    // ใบนัดหมาย , ใบรับรถ , ใบจอง
    public function FSxCASTCheckDocAllAproveINBCH(){
        $ptBCHCode  = $this->input->post('ptBCHCode');
        $aResultPDT = $this->mAdjustStock->FSaMCASTCheckDocAllAproveINBCH($ptBCHCode);
        if($aResultPDT == 0){
            //ไม่มีเอกสารค้างอนุมัติ
            $aReturnData = array(
                'nCode' => 1,
                'tMsg' 	=> 'ไม่มีเอกสารค้างอนุมัติ'
            );
        }else{
            //มีเอกสารค้างอนุมัติ
            $aReturnData = array(
                'nCode' => 400,
                'tMsg' 	=> 'มีเอกสารค้างอนุมัติ'
            );
        }
        echo json_encode($aReturnData);
    }

    //หาว่าเอกสารที่
    public function FSxCASTSendNotiForDocNotApv(){
        $ptBCHCode  = $this->input->post('ptBCHCode');
        $aResultPDT = $this->mAdjustStock->FSaMCASTCheckDocFindAproveINBCH($ptBCHCode);

        //ส่ง Noti
        if($aResultPDT['rtCode'] == 1){

            for($i=0; $i<count($aResultPDT['raItems']); $i++){
                $nCodeNoti      = $aResultPDT['raItems'][$i]['rnCodeNoti'];
                $tTableNoti     = $aResultPDT['raItems'][$i]['rtTableName'];
                $tBchCodeNoti   = $aResultPDT['raItems'][$i]['rtBchCode'];
                $tBchNameNoti   = $aResultPDT['raItems'][$i]['rtBchName']; 
                $tDocCodeNoti   = $aResultPDT['raItems'][$i]['rtDocNo'];
                $tDesc          = "กรุณาอนุมัติเอกสารดังกล่าว ก่อนทำเอกสารตรวจนับ - ยืนยันสินค้าคงคลัง";

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
                // print_r($aMQParamsNoti);
                FCNxCallRabbitMQ($aMQParamsNoti);
            }

        }
    }

}
