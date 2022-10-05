<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jobrequeststep1_controller extends MX_Controller {

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('document/jobrequeststep1/Jobrequeststep1_model');
        parent::__construct();
    }
    
    // Set Route Menu Default
    public $tRouteMenu  = 'docJR1/0/0';

    public function index($nJR1BrowseType, $tJR1BrowseOption) {
        
        //รองรับการ Jump
        $aParams    =   array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );

        $aData  = [
            'nBrowseType'   => $nJR1BrowseType,
            'tBrowseOption' => $tJR1BrowseOption,
            'aPermission'   => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'      => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'aParams'       => $aParams 
        ];

        //เก็บค่า Cookie Pdt Default ที่ใช้ในหน้าเพิ่มใบรับรถ ทุกครั้งที่มีการกดเข้าหน้า ใบรับรถ
        // Parameters: Ajax and Function Parameter
        // Creator: 04/03/2022 Sittikorn(Off)
        $tPdtDefConfig = get_cookie('tPdtDefConfig');
        if($tPdtDefConfig != NULL || $tPdtDefConfig != ''){
            $tPdtDefConfig = $tPdtDefConfig;
        }else{
            $tPdtDefConfig = $this->Jobrequeststep1_model->FStMJR1GetPdtDefConfig();;
            $aCookiePdtDef = array(
                'name' => 'tPdtDefConfig',
                'value' => $tPdtDefConfig,
                'expire' => 0
            );
            set_cookie($aCookiePdtDef);
        }

        $this->load->view('document/jobrequeststep1/wJobRequestStep1',$aData);
    }

    //Call Page Job Request List
    public function FSxCJR1PageList(){
        $this->load->view('document/jobrequeststep1/wJobRequestStep1PageList');
    }

    //Call Page Job Request Table
    public function FSxCJR1Datatable() {
        try {
            
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage          = $this->input->post('nPageCurrent');
            $aAlwEvent      = FCNaHCheckAlwFunc($this->tRouteMenu);
            // Page Current
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage  = $this->input->post('nPageCurrent');
            }
            // Lang ภาษา
            $nLangEdit  = $this->session->userdata("tLangEdit");
            // Data Conditon Get Data Document
            $aDataCondition = [
                'FNLngID'               => $nLangEdit,
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'aDatSessionUserLogIn'  => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch'        => $aAdvanceSearch
            ];
            $aDataList  = $this->Jobrequeststep1_model->FSaMJR1GetDataTableList($aDataCondition);
            $aConfigView = array(
                'nPage'     => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/jobrequeststep1/wJobRequestStep1Datable', $aConfigView, true);
            $aReturnData = array(
                'tViewDataTable'    => $tViewDataTable,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //เข้าหน้าเพิ่มข้อมูล
    public function FSvCJR1AddPage(){
        try {
            //ล้างค่าใน Temp
            $this->Jobrequeststep1_model->FSaMJR1DeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');

            //Cookie : สำหรับสินค้า default
            $tPdtDefConfig = get_cookie('tPdtDefConfig');
            if( isset($tPdtDefConfig) && !empty($tPdtDefConfig) ){ // ถ้าพบ config ให้ insert สินค้าลง temp

                $tPdtConvertIN = "'".str_replace(",","','",$tPdtDefConfig)."'";

                //เช็คค่าหน่วยเล็กสุดของ PdtDef หากเข้าครั้งที่ 2 จะให้ใช้ค่าที่เก็บมาจาก Cookie แทน
                $tGetcookiePdtList = get_cookie('tPdtDefList');
                if(isset($tGetcookiePdtList)  && !empty($tGetcookiePdtList)){
                    get_cookie('tPdtDefList');
                    $aSmallUnit = json_decode($tGetcookiePdtList);
                    $aSmallUnit = json_decode(json_encode($aSmallUnit), true);
                }else{
                    $aDefPdtList = $this->Jobrequeststep1_model->FSaMJR1GetSmallUnit($tPdtConvertIN);
                    $aCookiePdtDefList = array(
                        'name'      => 'tPdtDefList',
                        'value'     => json_encode($aDefPdtList),
                        'expire'    => 0
                    );
                    set_cookie($aCookiePdtDefList);
                    $aSmallUnit = $aDefPdtList;
                }

                $tAGNCode = $this->session->userdata('tSesUsrAgnCode');
                $tBCHCode = $this->session->userdata('tSesUsrBchCodeDefault');
                foreach($aSmallUnit['aItems'] AS $nKey => $aValue){
                    $tPDTCode = $aValue['FTPdtCode'];
                    $tPunCode = $aValue['FTPunCode'];

                    // ดึงข้อมูลตารางรายการสินค้า
                    $aDetailItem    = $this->Jobrequeststep1_model->FSaMJR1GetDataPdt($tPDTCode,$tPunCode);
                    $nSeqNo         = $nKey+1;

                    // ถ้าเป็นสินค้าปกติ หรือ สินค้าตัวแม่
                    $aInsertDTToTemp = [
                        'FTAgnCode'         => (!empty($tAGNCode)? $tAGNCode : ''),
                        'FTBchCode'         => (!empty($tBCHCode)? $tBCHCode : ''),
                        'FTXshDocNo'        => '',
                        'FTXcdPdtSeq'       => $nSeqNo,
                        'FTXthDocKey'       => 'TSVTJob1ReqDT',
                        'FTPdtCode'         => $aDetailItem[0]['FTPdtCode'],
                        'FTXtdPdtName'      => $aDetailItem[0]['FTPdtName'],
                        'FCXtdFactor'       => $aDetailItem[0]['FCPdtUnitFact'],
                        'FTPunCode'         => $tPunCode,
                        'FTPunName'         => $aDetailItem[0]['FTPunName'],
                        'FTXtdBarCode'      => $aDetailItem[0]['FTBarCode'],
                        'FTXtdVatType'      => $aDetailItem[0]['FTPdtStaVatBuy'],
                        'FTVatCode'         => $aDetailItem[0]['FTVatCode'],
                        'FCXtdVatRate'      => $aDetailItem[0]['FCVatRate'],
                        'FTXtdStaAlwDis'    => $aDetailItem[0]['FTPdtStaAlwDis'],
                        'FTXtdSaleType'     => $aDetailItem[0]['FTPdtSaleType'],
                        'FCXtdSalePrice'    => ($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                        'FCXtdQty'          => 1,
                        'FCXtdQtyAll'       => 1*$aDetailItem[0]['FCPdtUnitFact'],
                        'FCXtdSetPrice'     => ($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                        'FTXtdPdtStaSet'    => $aDetailItem[0]['FTPdtSetOrSN'], //1:สินค้าปกติ 2:สินค้าปกติชุด 3: สินค้าSerial 4:สินค้า Serial Set ,5: สินค้าชุดบริการ
                        'FTPdtSetOrSN'      => $aDetailItem[0]['FTPdtSetOrSN'], //1:สินค้าปกติ 2:สินค้าปกติชุด 3: สินค้าSerial 4:สินค้า Serial Set ,5: สินค้าชุดบริการ
                        'FTXtdStaPrcStk'    => null, //null: รอยืนยัน ,  1: ยืนยันแล้ว
                        'FTPdtType'         => $aDetailItem[0]['FTPdtType'],
                        'tJR1Option'        => 1,
                    ];

                    // Insert DT TAMP
                    $this->Jobrequeststep1_model->FSaMJR1InsertDTToTemp($aInsertDTToTemp);

                    $aWhereData     = [
                        'FTAgnCode'     => (!empty($tAGNCode)? $tAGNCode : ''),
                        'FTBchCode'     => (!empty($tBCHCode)? $tBCHCode : ''),
                        'FTXthDocNo'    => '',
                        'FTSrnCode'     => $tPDTCode,
                        'FTPdtCode'     => $tPDTCode,
                        'FNLngID'       => $this->session->userdata("tLangEdit"),
                        'FTXthDocKey'   => 'TSVTJob1ReqDT',
                        'FTSessionID'   => $this->session->userdata('tSesSessionID')
                    ];

                    // Loop Insert DT Set temp
                    $this->Jobrequeststep1_model->FSaMJR1InsertDTSetToTemp($aWhereData);

                    // Update Seq DT SET
                    // ถ้าเป็นขา add ไม่ต้องเรียง seq ใหม่
                    //$this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTSetTemp($aWhereData);
                }

                // Calcurate Document DT Temp Array Parameter
                /*$aCalcDTParams  = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => 1,
                    'tDataDocNo'        => '',
                    'tDataDocKey'       => 'TSVTJob1ReqDT',
                    'tDataSeqNo'        => '',
                    'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                    'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain('',$tBCHCode);
                }*/
            }

            // เข้ามาจากหน้า Table List
            $aDataAll   = array(
                'tRoute'                    => 'docJR1EventAdd',
                'nOptDecimalShow'           => $nOptDecimalShow,
                'aDataDocCar'               => array('rtCode'=>'99'),
                'aDataDocCSTHD'             => array('rtCode'=>'99'),
                'aDataDocHD'                => array('rtCode'=>'99'),
                'tStaFindDocNoUseInJOB2'    => 0 //ยังไม่เคยอ้างอิงใบสั่งงาน : อนุมัติเเล้วก็ยกเลิกได้
            );
        }catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        $this->load->view('document/jobrequeststep1/wJobRequestStep1PageAdd', $aDataAll);
    }

    //เข้าหน้าเเก้ไขข้อมูล
    public function FSvCJR1EditPage(){
        try {
            //ล้างค่าใน Temp
            $this->Jobrequeststep1_model->FSaMJR1DeletePDTInTmp();

            $tAgnCode           = $this->input->post('ptAgnCode');
            $tBchCode           = $this->input->post('ptBchCode');
            $tDocumentNumber    = $this->input->post('ptDocumentNumber');
            $tCarCode           = $this->input->post('ptCarCode');

            //ถ้าเกิดรถ ไม่ส่งมา หาว่าเอกสารนี้ใช้รถ อะไร
            if($tCarCode == '' || $tCarCode == null){
                $aResultFindCar = $this->Jobrequeststep1_model->FSaMJR1FindCarInDocument($tDocumentNumber);
                $tCarCode       = $aResultFindCar['aItems'][0]['FTCarCode'];
            }

            $aDataWhere = array(
                'FTAgnCode'     => $tAgnCode,
                'FTBchCode'     => $tBchCode,
                'FTXshDocNo'    => $tDocumentNumber,
                'tDocKey'       => 'TSVTJob1ReqDT',
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'nLangEdit'     => $this->session->userdata("tLangEdit"),
                'tCarCstCode'   => $tCarCode,
                'tSessionID'    => $this->session->userdata('tSesSessionID')
            );

            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');

            // Get Data Document HD
            $aDataDocHD         = $this->Jobrequeststep1_model->FSaMJR1GetDataDocHD($aDataWhere);

            // Get Data Document SPL HD
            $aDataDocCSTHD      = $this->Jobrequeststep1_model->FSaMJR1GetDataDocCstHD($aDataWhere);

            // Get Data ข้อมูลรถ
            $aDataDocCar        = $this->Jobrequeststep1_model->FSaMJR1GetDataCarCustomer($aDataWhere,'Car');

            // Move Data HD DIS To HD DIS Temp
            $this->Jobrequeststep1_model->FSxMJR1MoveHDDisToTemp($aDataWhere);

            // Move Data DT TO DTTemp
            $this->Jobrequeststep1_model->FSxMJR1MoveDTToDTTemp($aDataWhere);

            // Move Data DTSet TO DTSetTemp
            $this->Jobrequeststep1_model->FSxMJR1MoveDTSetToDTTempSet($aDataWhere);

            // Move Data DTDIS TO DTDISTemp
            $this->Jobrequeststep1_model->FSxMJR1MoveDTDisToDTDisTemp($aDataWhere);

            // หาว่าเอกสารใบรับรถนี้ ถูกอ้างอิงใบสั่งงานหรือยัง
            $aFindDocNoUse = $this->Jobrequeststep1_model->FSxMJR1FindDocNoUse($aDataWhere);
            if(empty($aFindDocNoUse)){
                $tStaFindDocNoUse = 0; //ยังไม่เคยอ้างอิงใบสั่งงาน : อนุมัติเเล้วก็ยกเลิกได้
            }else{
                $tStaFindDocNoUse = 1; //ถูกอ้างอิงเเล้ว : ยกเลิกไม่ได้
            }

            // เข้ามาจากหน้า Table List
            $aDataAll   = array(
                'tRoute'                    => 'docJR1EventEdit',
                'nOptDecimalShow'           => $nOptDecimalShow,
                'aDataDocCar'               => $aDataDocCar,
                'aDataDocCSTHD'             => $aDataDocCSTHD,
                'aDataDocHD'                => $aDataDocHD,
                'tStaFindDocNoUseInJOB2'    => $tStaFindDocNoUse
            );
        }catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        $this->load->view('document/jobrequeststep1/wJobRequestStep1PageAdd', $aDataAll);
    }

    //ค้นหาข้อมูลลูกค้า
    public function FSaCJR1FindCst(){
        $poItem     = json_decode($this->input->post('poItem'));

        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCstCode'      => $poItem[0],
            'nLangEdit'     => $nLangEdit
        ];
        $aDataReturn    = [
            'aDataCst'      => $this->Jobrequeststep1_model->FSaMJR1GetDataCustomer($aDataWhere),
            'aDataCstAddr'  => $this->Jobrequeststep1_model->FSaMJR1GetDataCustomerAddr($aDataWhere),
            'aDataCarCst'   => $this->Jobrequeststep1_model->FSaMJR1GetDataCarCustomer($aDataWhere,'Owner')
        ];
        echo json_encode($aDataReturn);
    }

    //ค้นหาข้อมูลรถของลูกค้า
    public function FSaCJR1FindCstCar(){
        $poItem     = json_decode($this->input->post('poItem'));
        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCarCstCode'   => $poItem[0],
            'nLangEdit'     => $nLangEdit
        ];
        $aDataReturn    = [
            'aDataCarCst' => $this->Jobrequeststep1_model->FSaMJR1GetDataCarCustomer($aDataWhere,'Car')
        ];
        echo json_encode($aDataReturn);
    }

    //Call Page ตารางสินค้าใน DT Temp
    public function FSvCJR1TableDTTemp(){
        try {
            $tJR1DocNo          = $this->input->post('ptJR1DocNo');
            $tJR1StaApv         = $this->input->post('ptJR1StaApv');
            $tJR1StaDoc         = $this->input->post('ptJR1StaDoc');
            $tJR1AgnCode        = $this->input->post('ptJR1AgnCode');
            $tJR1BCHCode        = $this->input->post('ptJR1BCHCode');
            $tJR1VATInOrEx      = '1';
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');

            // Get Data DT Temp
            $aDataWhereDT       = array(
                'FTXthDocNo'    => $tJR1DocNo,
                'FTXthDocKey'   => 'TSVTJob1ReqDT',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTBchCode'     => $tJR1BCHCode,
                'FTAgnCode'     => $tJR1AgnCode,
            );
            $aDataDocDTTemp = $this->Jobrequeststep1_model->FSaMJR1GetDocDTTempListPage($aDataWhereDT);
            
            // Load View Table List PDT DT
            $aDataView  = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tJR1StaApv'        => $tJR1StaApv,
                'tJR1StaDoc'        => $tJR1StaDoc,
                'aDataDocDTTemp'    => $aDataDocDTTemp,
            );
            $tJR1PdtAdvTableHtml    = $this->load->view('document/jobrequeststep1/wJobRequestStep1PdtAdvTableData', $aDataView, true);

            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'       => $tJR1VATInOrEx,
                'tDocNo'            => $tJR1DocNo,
                'tDocKey'           => 'TSVTJob1ReqDT',
                'nLngID'            => FCNaHGetLangEdit(),
                'tSesSessionID'     => $this->session->userdata('tSesSessionID'),
                'tBchCode'          => $tJR1BCHCode,
                'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                'tTableHDDisTmp'    => 'TSVTJRQHDDisTmp'
            );
            //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล
            $aJR1EndOfBill['aEndOfBillVat'] = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aJR1EndOfBill['aEndOfBillCal'] = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aJR1EndOfBill['tTextBath']     = FCNtNumberToTextBaht($aJR1EndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

            $aReturnData = array(
                'tJR1PdtAdvTableHtml'   => $tJR1PdtAdvTableHtml,
                'aJR1EndOfBill'         => $aJR1EndOfBill,
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

    //Get Data DT Set
    public function FSxCJR1EventInsertToDT(){
        $poItem             = json_decode($this->input->post('poItem'));
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tAGNCode           = $this->input->post('tAgnCode');
        $tBCHCode           = $this->input->post('tBchCode');
        $tJR1Option         = $this->input->post('tJR1OptionAddPdt');
        $nCheckPDTSet       = 0; //เก็บไว้ว่ามีค่าสินค้าเซตไหม จะมีผลต่อหน้าจอ render
        if(isset($poItem) && !empty($poItem)){
            // Loop ข้อมูลตระกร้าสินค้า
            for($i=0; $i<count($poItem); $i++){
                $tPDTCode = $poItem[$i]->packData->PDTCode;
                $tPunCode = $poItem[$i]->packData->PUNCode;
                $tBarCode = $poItem[$i]->packData->Barcode;
                // ดึงข้อมูลตารางรายการสินค้า
                $aDetailItem    = $this->Jobrequeststep1_model->FSaMJR1GetDataPdt($tPDTCode,$tPunCode);
                
                $nSeqNo         = $i+1;
                // ถ้าเป็นสินค้าปกติ หรือ สินค้าตัวแม่
                $aInsertDTToTemp = [
                    'FTAgnCode'         => (!empty($tAGNCode)? $tAGNCode : ''),
                    'FTBchCode'         => (!empty($tBCHCode)? $tBCHCode : ''),
                    'FTXshDocNo'        => $tDocumentNumber,
                    'FTXcdPdtSeq'       => $nSeqNo,
                    'FTXthDocKey'       => 'TSVTJob1ReqDT',
                    'FTPdtCode'         => $poItem[$i]->packData->PDTCode,
                    'FTXtdPdtName'      => $poItem[$i]->packData->PDTName,
                    'FCXtdFactor'       => $aDetailItem[0]['FCPdtUnitFact'],
                    'FTPunCode'         => $tPunCode,
                    'FTPunName'         => $poItem[$i]->packData->PUNName,
                    'FTXtdBarCode'      => $tBarCode,
                    'FTXtdVatType'      => $aDetailItem[0]['FTPdtStaVatBuy'],
                    'FTVatCode'         => $aDetailItem[0]['FTVatCode'],
                    'FCXtdVatRate'      => $aDetailItem[0]['FCVatRate'],
                    'FTXtdStaAlwDis'    => $aDetailItem[0]['FTPdtStaAlwDis'],
                    'FTXtdSaleType'     => $aDetailItem[0]['FTPdtSaleType'],
                    'FCXtdSalePrice'    => str_replace(",","",$poItem[$i]->packData->NetAfHD), //($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                    'FCXtdQty'          => 1,
                    'FCXtdQtyAll'       => 1*$aDetailItem[0]['FCPdtUnitFact'],
                    'FCXtdSetPrice'     => str_replace(",","",$poItem[$i]->packData->NetAfHD), //($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                    'FTXtdPdtStaSet'    => $poItem[$i]->packData->SetOrSN, //1:สินค้าปกติ 2:สินค้าปกติชุด 3: สินค้าSerial 4:สินค้า Serial Set ,5: สินค้าชุดบริการ
                    'FTPdtSetOrSN'      => $poItem[$i]->packData->SetOrSN, //1:สินค้าปกติ 2:สินค้าปกติชุด 3: สินค้าSerial 4:สินค้า Serial Set ,5: สินค้าชุดบริการ
                    'FTXtdStaPrcStk'    => null, //null: รอยืนยัน ,  1: ยืนยันแล้ว
                    'FTPdtType'         => $aDetailItem[0]['FTPdtType'],
                    'tJR1Option'        => $tJR1Option,
                ];
                // if($tJR1Option == 1){
                //     // Delete DT TAMP
                //     $this->Jobrequeststep1_model->FSaMJR1DeleteDTToTemp($aInsertDTToTemp);
                // }

                // Insert DT TAMP
                $this->Jobrequeststep1_model->FSaMJR1InsertDTToTemp($aInsertDTToTemp);

                // Run Seq Doc Temp
                // $this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTTemp($aInsertDTToTemp);

                // Calcurate Document DT Temp Array Parameter
                $aCalcDTParams  = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => 1,
                    'tDataDocNo'        => $tDocumentNumber,
                    'tDataDocKey'       => 'TSVTJob1ReqDT',
                    'tDataSeqNo'        => '',
                    'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                    'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain($tDocumentNumber,$tBCHCode);
                }
                // เช็ค Set Or SN ว่าเป็นสินค้าชุดหรือไม่ถ้าเป็นให้แสดงหน้ารายละเอียด DT Set
                $nCheckPDTSet = $poItem[$i]->packData->SetOrSN;
            }
        }
        $aReturn = array('nStatusRenderDTSet' => $nCheckPDTSet);
        echo json_encode($aReturn);
    }

    //เอาสินค้าใน Booking มา Insert
    public function FSxCJR1EventInsertToDTCaseDTBooking(){
        $poItem             = json_decode($this->input->post('poItem'));
        // print_r($poItem);exit;
        $tBookingCode       = $poItem[0];
        $tCarCode           = $poItem[1];
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tAGNCode           = $this->input->post('tAgnCode');
        $tBCHCode           = $this->input->post('tBchCode');

        $aItemInsert = [
            'tBookingCode'      => $tBookingCode,
            'tDocumentNumber'   => $tDocumentNumber,
            'tAGNCode'          => $tAGNCode,
            'tBCHCode'          => $tBCHCode
        ];

        // ตรวจสอบ Config สินค้า Default
        $tPdtDefConfig = $this->Jobrequeststep1_model->FStMJR1GetPdtDefConfig();
        if( isset($tPdtDefConfig) && !empty($tPdtDefConfig) ){
            $tPdtConvertIN = "'".str_replace(",","','",$tPdtDefConfig)."'";
            $aPdtDefConfig = explode(",",$tPdtDefConfig);
        }else{
            $tPdtConvertIN = "";
            $aPdtDefConfig = array();
        }

        $this->Jobrequeststep1_model->FSxMJR1ClearPdtInTmp($tPdtConvertIN);

        //Getข้อมูลในตารางนัดหมาย
        $this->Jobrequeststep1_model->FSaMJR1GetBookingDT($aItemInsert,$tPdtConvertIN);

        //Getข้อมูลในตารางนัดหมาย
        $this->Jobrequeststep1_model->FSaMJR1GetBookingDTSet($aItemInsert,$tPdtConvertIN);

        $aDataUpdPdtCstFollow = [
            'tSessionID' => $this->session->userdata('tSesSessionID'),
            'tDocKey'    => 'TSVTJob1ReqDT',
            'tBhcCode'   => $tBCHCode,
            'tDocNo'     => $tDocumentNumber,
            'tCarCode'   => $tCarCode,
            'nLngID'     => $this->session->userdata("tLangEdit")
        ];
        // print_r($aDataUpdPdtCstFollow);exit;
        $this->Jobrequeststep1_model->FSaMJR1UpdPdtCstFollow($aDataUpdPdtCstFollow);

        //คำนวณราคา
        $aCalcDTParams  = [
            'tDataDocEvnCall'   => '',
            'tDataVatInOrEx'    => 1,
            'tDataDocNo'        => $tDocumentNumber,
            'tDataDocKey'       => 'TSVTJob1ReqDT',
            'tDataSeqNo'        => '',
            'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
            'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
        ];
        $tStaCalcuRate  = FCNbHCallCalcDocDTTemp($aCalcDTParams);
        if ($tStaCalcuRate === TRUE) {
            $this->FSxCalculateHDDisAgain($tDocumentNumber,$tBCHCode);
        }
    }

    //คำนวณส่วนลดท้ายบิลใหม่อีกครั้ง กรณีมีการเพิ่มสินค้า , แก้ไขจำนวน , แก้ไขราคา , ลบสินค้า , ลดรายการ , ลดท้ายบิล
    public function FSxCalculateHDDisAgain($ptDocumentNumber,$ptBCHCode){
        $aPackDataCalCulate = array(
            'tDocNo'            => $ptDocumentNumber,
            'tBchCode'          => $ptBCHCode,
            'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
            'tTableHDDisTmp'    => 'TSVTJRQHDDisTmp'
        );
        FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
    }

    //Call View Pdt Set Join Cst Follow
    public function FSxCJR1PdtSetBehindSltPage(){
        $poItem             = json_decode($this->input->post('poItem'));
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tAGNCode           = $this->input->post('tAgnCode');
        $tBCHCode           = $this->input->post('tBchCode');
        $tCstCode           = $this->input->post('tCstCode');
        $tCarCode           = $this->input->post('tCarCode');
        $tTypeAction        = $this->input->post('ptTypeAction');
        $tOptionCondition   = $this->input->post('tJR1OptionAddPdt');

        // Loop ข้อมูลตระกร้าสินค้า
        for($i=0; $i<count($poItem); $i++){
            $tPDTCode       = $poItem[$i]->packData->PDTCode;
            if($tTypeAction == 'edit'){
                $nSeqno          = $poItem[$i]->nSeqno;
            }else{
                $nSeqno          = '';
            }

            // $tPunCode       = $poItem[$i]->packData->PUNCode;
            // $tBarCode       = $poItem[$i]->packData->Barcode;
            $aWhereData     = [
                'FTAgnCode'     => (!empty($tAGNCode)? $tAGNCode : ''),
                'FTBchCode'     => (!empty($tBCHCode)? $tBCHCode : ''),
                'FTXthDocNo'    => $tDocumentNumber,
                'FTSrnCode'     => $tPDTCode,
                'FTPdtCode'     => $tPDTCode,
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FTXthDocKey'   => 'TSVTJob1ReqDT',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTCstCode'     => $tCstCode,
                'FTCarCode'     => $tCarCode,
                'FNXtdSeqNo'    => $nSeqno,
            ];
            
            if( $tTypeAction == 'add' ){
                // Loop Insert DT Set temp
                // if($tOptionCondition == 1 ){
                //     $this->Jobrequeststep1_model->FSaMJR1DeletePdtDTSetToTemp($aWhereData);
                // }
                $this->Jobrequeststep1_model->FSaMJR1InsertDTSetToTemp($aWhereData);

                // Update Seq DT SET
                // $this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTSetTemp($aWhereData);

                $aDataUpdPdtCstFollow = [
                    'tSessionID' => $this->session->userdata('tSesSessionID'),
                    'tDocKey'    => 'TSVTJob1ReqDT',
                    'tBhcCode'   => (!empty($tBCHCode)? $tBCHCode : ''),
                    'tDocNo'     => $tDocumentNumber,
                    'tCarCode'   => $tCarCode,
                    'nLngID'     => $this->session->userdata("tLangEdit")
                ];
                $this->Jobrequeststep1_model->FSaMJR1UpdPdtCstFollow($aDataUpdPdtCstFollow);
            }

            if($tTypeAction == 'edit'){
                $aDataView  = [
                    'aDataDTTmp'  => $this->Jobrequeststep1_model->FSaMJR1GetDocDTTempByID($aWhereData),
                    'tTypeAction' => $tTypeAction
                ];
                $this->load->view('document/jobrequeststep1/modal/wJobRequestStep1MDPdtSet',$aDataView);
            }
        }
    }

    //โหลดข้อมูลตารางรายการสินค้าชุดเปรียบเทียบข้อมูล CST Follow
    public function FSxCJR1EventLoadTblDTPDTSetCstFlw(){
        $aWhereData     = [
            'FTBchCode'     => $this->input->post('tBchCode'),
            'FTXthDocNo'    => $this->input->post('tDocNo'),
            'FTXthDocKey'   => $this->input->post('tDocKey'),
            'FTCarCode'     => $this->input->post('tCarCode'),
            'FTPdtCode'     => $this->input->post('tPdtCode'),
            'FNXtdSeqNo'    => $this->input->post('tSeqCode'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FNLngID'       => $this->session->userdata("tLangEdit"),
        ];
        $aDataView  = [
            'aDataDTSTmp'   => $this->Jobrequeststep1_model->FSaMJR1GetDocDTSetTempJoinCstFollow($aWhereData)
        ];
        $this->load->view('document/jobrequeststep1/modal/wJobRequestStep1MDPdtSetTblCstflw',$aDataView);
    }

    // Insert ข้อมูล DTSet Type 2
    public function FSoCJR1EventInsDTSetPdtSetTyp2(){
        $poItem             = json_decode($this->input->post('poItem'));
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tAGNCode           = $this->input->post('tAgnCode');
        $tBCHCode           = $this->input->post('tBchCode');
        $tCstCode           = $this->input->post('tCstCode');
        $tCarCode           = $this->input->post('tCarCode');
        $this->db->trans_begin();
        // Loop ข้อมูลตระกร้าสินค้า
        for($i=0; $i<count($poItem); $i++){
            $tPDTCode       = $poItem[$i]->packData->PDTCode;
            $tPunCode       = $poItem[$i]->packData->PUNCode;
            $tBarCode       = $poItem[$i]->packData->Barcode;
            $aWhereData     = [
                'FTAgnCode'     => (!empty($tAGNCode)? $tAGNCode : ''),
                'FTBchCode'     => (!empty($tBCHCode)? $tBCHCode : ''),
                'FTXthDocNo'    => $tDocumentNumber,
                'FTSrnCode'     => $tPDTCode,
                'FTPdtCode'     => $tPDTCode,
                'FNLngID'       => $this->session->userdata("tLangEdit"),
                'FTXthDocKey'   => 'TSVTJob1ReqDT',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTCstCode'     => $tCstCode,
                'FTCarCode'     => $tCarCode,
            ];
            // $this->Jobrequeststep1_model->FSaMJR1DeletePdtDTSetToTemp($aWhereData);
            $this->Jobrequeststep1_model->FSaMJR1InsertDTSetToTemp2($aWhereData);
        }
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => 'Error Cannot Insert DT Set Type 2'
            );
        }else {
            $this->db->trans_commit();
            $aReturnData = array(
                'tAgnCode'  => $tAGNCode,
                'tBchCode'  => $tBCHCode,
                'tDocNo'    => $tDocumentNumber,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success Insert DT Set Type 2'
            );
        }
        echo json_encode($aReturnData);
    }

    //Delete ข้อมูลสินค้ากรณี ไม่เลือกเเล้ว
    public function FSxCJR1EventDeleteDTSetAndDT(){
        $aWhereData  = [
            'FTBchCode'     => $this->input->post('tBchCode'),
            'FTXshDocNo'    => $this->input->post('tDocNo'),
            'FTXthDocKey'   => 'TSVTJob1ReqDT',
            'FTPdtCode'     => $this->input->post('tPdtCode'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ];
        $this->Jobrequeststep1_model->FSaMJR1RemovePdtInDTTmpCaseModal($aWhereData);
    }

    //Delete ข้อมูลรายการรายละเอียด สินค้า DT (Single)
    public function FSvCJR1RemovePdtInDTTmp(){
        $aWhereData  = [
            'FTAgnCode'     => $this->input->post('tJR1AgnCode'),
            'FTBchCode'     => $this->input->post('tJR1BchCode'),
            'FTXthDocNo'    => $this->input->post('tJR1DocNo'),
            'FTXshDocNo'    => $this->input->post('tJR1DocNo'),
            'FNXtdSeqNo'    => $this->input->post('tJR1SeqNo'),
            'FTXthDocKey'   => 'TSVTJob1ReqDT',
            'FTPdtCode'     => $this->input->post('tJR1PdtCode'),
            'FTPunCode'     => $this->input->post('tJR1PunCode'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ];
        $aReturnData    = $this->Jobrequeststep1_model->FSaMJR1RemovePdtInDTTmp($aWhereData);
        
        // Check Update Seq Number
        if($aReturnData['rtCode'] == '1'){
            // Update Seq DT Temp
            /*$this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTTemp($aWhereData);

            // Update Seq DT Set Temp
            $this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTSetTemp($aWhereData);

            // Calcurate Document DT Temp Array Parameter
            $aCalcDTParams  = [
                'tDataDocEvnCall'   => '',
                'tDataVatInOrEx'    => 1,
                'tDataDocNo'        => $aWhereData['FTXthDocNo'],
                'tDataDocKey'       => 'TSVTJob1ReqDT',
                'tDataSeqNo'        => '',
                'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
            ];
            $tStaCalcuRate  = FCNbHCallCalcDocDTTemp($aCalcDTParams);
            if ($tStaCalcuRate === TRUE) {
                $this->FSxCalculateHDDisAgain($aWhereData['FTXthDocNo'],$aWhereData['FTBchCode']);
            }*/

            $aReturnData = array(
                'nStaEvent' => $aReturnData['rtCode'],
                'tStaMessg' => $aReturnData['rtDesc']
            );

        }else{
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $aReturnData['rtDesc']
            );
        }
        echo json_encode($aReturnData);
    }

    //Delete ข้อมูลรายการรายละเอียด สินค้า DT (Muti)
    public function FSvCJR1RemovePdtInDTMutiTmp(){

        $aItemSeq       = $this->input->post('aDataSeqNo');

        $aWhereData  = [
            'tDocNo'            => $this->input->post('tDocNo'),
            'tBchCode'          => $this->input->post('tBchCode'),
            'tTextRemoveSeq'    => $aItemSeq,
            'FTSessionID'       => $this->session->userdata('tSesSessionID')
        ];

        $aReturnData = $this->Jobrequeststep1_model->FSaMJR1RemovePdtInDTMutiTmp($aWhereData);
        if($aReturnData['rtCode'] == '1'){
            $aReturnData = array(
                'nStaEvent' => $aReturnData['rtCode'],
                'tStaMessg' => $aReturnData['rtDesc']
            );
        }else{
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $aReturnData['rtDesc']
            );
        }
        echo json_encode($aReturnData);
    }

    //Delete ข้อมูลรายการรายละเอียด สินค้า DT Set
    public function FSxCJR1EventDeleteToDTPDTSet(){
        // Parameter In Table DT Set Temp
        $aWhereDelete = [
            'FTAgnCode'     => $this->input->post('tAgnCode'),
            'FTBchCode'     => $this->input->post('tBchCode'),
            'FTXthDocNo'    => $this->input->post('tDocNo'),
            'FTPdtCode'     => $this->input->post('tPdtCode'),
            'FTPdtCodeOrg'  => $this->input->post('tPdtCodeOrg'),
            'FTXthDocKey'   => 'TSVTJob1ReqDT',
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FNXtdSeqNo'   => $this->input->post('tSeqCode'),
        ];
        // ลบข้อมูลตารางรายการ DTSet
        $aStaDelete = $this->Jobrequeststep1_model->FSaMJR1DeleteDTSetTempByID($aWhereDelete);
        if($aStaDelete['rtCode'] == '1'){
            // อัพเดตหมายเลข Seq DT Set
            $this->Jobrequeststep1_model->FSxMJR1UpdateSeqDTSetTemp($aWhereDelete);
        }
        echo json_encode($aStaDelete);
    }

    //Edit Inline สินค้า ลง Document DT Temp
    public function FSoCJR1EditPdtIntoDocDTTemp() {
        try{
            $tJR1BchCode            = $this->input->post('tJR1BchCode');
            $tJR1DocNo              = $this->input->post('tJR1DocNo');
            $nJR1SeqNo              = $this->input->post('nJR1SeqNo');
            $nStaDelDis             = $this->input->post('nStaDelDis');
            $nAdjStaStk             = $this->input->post('nAdjStaStk');
            $aDataWhere             = array(
                'tJR1BchCode'       => $tJR1BchCode,
                'tJR1DocNo'         => $tJR1DocNo,
                'nJR1SeqNo'         => $nJR1SeqNo,
                'tSessionID'        => $this->session->userdata('tSesSessionID'),
                'tDocKey'           => 'TSVTJob1ReqDT',
                'nStaDelDis'        => $nStaDelDis,
                'nAdjStaStk'        => $nAdjStaStk
            );
            $aDataUpdateDT          = array(
                'FCXtdQty'          => $this->input->post('nQty'),
                'FCXtdSalePrice'    => $this->input->post('cPrice'),
                'FCXtdSetPrice'     => $this->input->post('cPrice'),
                'FTXtdPdtName'      => $this->input->post('tPdtName'),
                'FCXtdNet'          => $this->input->post('cNet')
            );

            $this->db->trans_begin();
            $this->Jobrequeststep1_model->FSaMJR1UpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

            if ($nStaDelDis == '1') {
                // ยืนยันการลบ DTDis ส่วนลดรายการนี้
                $this->Jobrequeststep1_model->FSaMJR1DeleteDTDisTemp($aDataWhere);
                $this->Jobrequeststep1_model->FSaMJR1ClearDisChgTxtDTTemp($aDataWhere);
            }

            //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง
            $this->FSxCalculateHDDisAgain($tJR1DocNo,$tJR1BchCode);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => "Error Update Inline Into Document DT Temp."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => "Update Inline Into Document DT Temp."
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เช็คเลขที่เอกสารถ้าคีย์มา ว่าซ้ำไหม
    public function FSoCJR1ChkHavePdtForDocDTTemp(){
        $tJR1DocNo      = $this->input->post("tJR1DocNo");
        $tJR1SessionID  = $this->session->userdata('tSesSessionID');
        $aDataWhere     = array(
            'FTXshDocNo'    => $tJR1DocNo,
            'FTXthDocKey'   => 'TSVTJob1ReqDT',
            'FTSessionID'   => $tJR1SessionID
        );
        $nCountPdtInDocDTTemp   = $this->Jobrequeststep1_model->FSnMJR1ChkPdtInDocDTTemp($aDataWhere);
        if($nCountPdtInDocDTTemp > 0) {
            $aReturnData = array(
                'nStaReturn'    => '1',
                'tStaMessg'     => 'Found Data In Doc DT.'
            );
        }else {
            $aReturnData = array(
                'nStaReturn'    => '800',
                'tStaMessg'     => 'กรุณาเลือกสินค้าก่อนทำรายการ'
            );
        }
        echo json_encode($aReturnData);
    }

    // เพิ่มข้อมูล HD DT
    public function FSxCJR1AddEvent(){
        try{
            $aDataDocument      = $this->input->post();
            $tJR1AutoGenCode    = (isset($aDataDocument['ocbJR1StaAutoGenCode'])) ? 1 : 0;
            $tJR1DocNo          = (isset($aDataDocument['oetJR1DocNo'])) ? $aDataDocument['oetJR1DocNo'] : '';
            $tJR1DocDate        = $aDataDocument['oetJR1DocDate'] . " " . $aDataDocument['oetJR1DocTime'];
            $tJR1VATInOrEx      = 1;
            $tJR1SessionID      = $this->session->userdata('tSesSessionID');

            $aDataTableTmp = array(
                'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp',
                'tTableHDDisTmp'    => 'TSVTJRQHDDisTmp'
            );
            FCNaHCalculateProrate('TSVTJob1ReqDT',$tJR1DocNo,$aDataTableTmp);

            $aCalcDTParams      = [
                'tDataDocEvnCall'   => '',
                'tBchCode'          => $aDataDocument['ohdJR1BchCode'],
                'tDataVatInOrEx'    => $tJR1VATInOrEx,
                'tDataDocNo'        => $tJR1DocNo,
                'tDataDocKey'       => 'TSVTJob1ReqDT',
                'tDataSeqNo'        => '',
                'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
                'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            // Prorate HD
            //FCNaHCalculateProrate('TSVTJob1ReqDT', $tJR1DocNo,$aDataTableTmp);

            //FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aCalDTTempParams = [
                'tDocNo'            => $tJR1DocNo,
                'tBchCode'          => $aDataDocument['ohdJR1BchCode'],
                'tSessionID'        => $tJR1SessionID,
                'tDocKey'           => 'TSVTJob1ReqDT',
                'tDataVatInOrEx'    => $tJR1VATInOrEx,
            ];
            $this->Jobrequeststep1_model->FSaMJR1CalVatLastDT($aCalDTTempParams);

            // Get Data Total HD
            $aCalDTTempForHD    = $this->FSaCJR1CalDTTempForHD($aCalDTTempParams);

            // Get Data HD Dis
            $aCalInHDDisTemp    = $this->Jobrequeststep1_model->FSaMJR1CalInHDDisTemp($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate    = [
                'tTableHD'      => 'TSVTJob1ReqHD',
                'tTableHDDis'   => 'TSVTJob1ReqHDDis',
                'tTableDT'      => 'TSVTJob1ReqDT',
                'tTableDTSet'   => 'TSVTJob1ReqDTSet',
                'tTableDTDis'   => 'TSVTJob1ReqDTDis',
                'tTableHDCst'   => 'TSVTJob1ReqHDCst',
                'tTableStaGen'  => 1
            ];

            // Array Data Where Insert
            $aDataWhere         = [
                'FTAgnCode'     => $aDataDocument['ohdJR1ADCode'],
                'FTBchCode'     => $aDataDocument['ohdJR1BchCode'],
                'FTAgnCode'     => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                'FTXshDocNo'    => $tJR1DocNo,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx'=> $tJR1VATInOrEx
            ];

            // Array Data HD Master
            $aDataMasterHD      = [
                'FDXshDocDate'          => (!empty($tJR1DocDate)) ? $tJR1DocDate : NULL,
                'FTXshToShp'            => '',
                'FTXshToPos'            => !empty($aDataDocument['oetJR1BayCode'])? $aDataDocument['oetJR1BayCode'] : '',
                'FDXshTimeStart'        => $aDataDocument['oetJR1BookDate'] .' '. $aDataDocument['oetJR1TimeBook'],
                'FDXshTimeStop'         => null,
                'FTCstCode'             => $aDataDocument['oetJR1FrmCstCode'],
                'FCXshCarMileage'       => floatval(str_replace(',','',$aDataDocument['oetJR1CarMiter'])),
                'FTXshCarFuel'          => $aDataDocument['ohdJR1FrmCarFuel'],
                'FTXshStaBook'          => (!empty($aDataDocument['oetJR1BookUse'])) ? 1 : 2,
                'FTXshCarChkRmk1'       => $aDataDocument['oetJR1FrmCarChkRmk1'],
                'FTXshCarChkRmk2'       => null,
                'FTUsrCode'             => $aDataDocument['oetJR1UsrValetCode'],
                'FTRteCode'             => null,
                'FCXshRteFac'           => null,
                'FCXshTotal'            => $aCalDTTempForHD['FCXphTotal'],
                'FCXshTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
                'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
                'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : null,
                'FCXshDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : null,
                'FCXshChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : null,
                'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                'FCXshAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                'FCXshAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXshVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXshVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FCXshWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                'FCXshGrand'            => $aCalDTTempForHD['FCXphGrand'],
                'FTXshRmk'              => $aDataDocument['otaJR1Remark'],
                'FTRsnCode'             => null,
                'FTXshStaDoc'           => $aDataDocument['ohdJR1StaDoc'],
                'FNXshStaDocAct'        => 1,
                'FDXshVchRecDate'       => $aDataDocument['oetJR1PickInDate'] .' '. $aDataDocument['oetJR1PickInTime'],
            ];

            // Array Data HDCST
            $aDataCst          = [
                'FTAgnCode'     => $aDataDocument['ohdJR1ADCode'],
                'FTBchCode'     => $aDataDocument['ohdJR1BchCode'],
                'FTCarCode'     => $aDataDocument['oetJR1CarRegCode'],
                'FTXshDocNo'    => $tJR1DocNo,
                'FTXshCardID'   => $aDataDocument['oetJR1FrmCstCode'],
                'FTXshCstTel'   => $aDataDocument['oetJR1FrmCstTel'],
                'FTXshCstName'  => $aDataDocument['oetJR1FrmCstName']
            ];

            // $this->db->trans_begin();

            //App varsion
            // $tAppVer    = FCNtGetAppVersion();
            $tAppVer = 'SB';

            // Check Auto GenCode Document
            if ($tJR1AutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TSVTJob1ReqHD',
                    "tDocType"    => '1',
                    "tBchCode"    => $aDataDocument['ohdJR1BchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );
                $aAutogen = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXshDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXshDocNo']   = $tJR1DocNo;
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
            if($aDataDocument['oetJR1DocRefBookCode'] != ''){
                //1: อ้างอิงถึง(ภายใน) => ใบนัดหมาย
                $aDataWhereDocRef_Type1 = array(
                    'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                    'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                    'FTXshDocNo'        => $aDataWhere['FTXshDocNo'],
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $aDataDocument['oetJR1DocRefBookCode'],
                    'FTXshRefKey'       => 'BOOK',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefBookDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefBookDate'])) : NULL
                );
                $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTJob1ReqHDDocRef',$aDataWhereDocRef_Type1);
                //2: ถูกอ้างอิง(ภายใน) => ใบรับรถ
                $aDataWhereDocRef_Type2 = array(
                    'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                    'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                    'FTXshDocNo'        => $aDataDocument['oetJR1DocRefBookCode'],
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataWhere['FTXshDocNo'],
                    'FTXshRefKey'       => 'Job1Req',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefBookDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefBookDate'])) : NULL
                );
                $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTBookHDDocRef',$aDataWhereDocRef_Type2);
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายนอก
            if($aDataDocument['oetJR1DocRefExtDoc'] != '' ){
                //3: อ้างอิง ภายนอก
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                    'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                    'FTXshDocNo'        => $aDataWhere['FTXshDocNo'],
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetJR1DocRefExtDoc'],
                    'FTXshRefKey'       => 'BillNote',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefExtDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefExtDocDate'])) : NULL
                );
                $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTJob1ReqHDDocRef',$aDataWhereDocRef_Type3);
            }

            // [Add] Update Document HD
            $this->Jobrequeststep1_model->FSxMJR1AddUpdateHD($aDataMasterHD, $aDataWhere, $aTableAddUpdate);

            // [Add] Update Document Cst
            $this->Jobrequeststep1_model->FSxMJR1AddUpdateCSTHD($aDataCst, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Jobrequeststep1_model->FSxMJR1AddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Add] Doc DTTemp -> DT
            $this->Jobrequeststep1_model->FSaMJR1MoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

            // [Add] Doc DTTempSet -> DTSet
            $this->Jobrequeststep1_model->FSaMJR1MoveDTTmpSetToDTSet($aDataWhere, $aTableAddUpdate);

            // [Add] Move Doc HDDisTemp -> HDDis
            $this->Jobrequeststep1_model->FSaMJR1MoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);

            // [Add] Doc DTDisTemp -> DTDis
            $this->Jobrequeststep1_model->FSaMJR1MoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);

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
                    'tAgnCode'      => $aDataWhere['FTAgnCode'],
                    'tBchCode'      => $aDataWhere['FTBchCode'],
                    'tCarCode'      => $aDataCst['FTCarCode'],
                    'tCodeReturn'   => $aDataWhere['FTXshDocNo'],
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

    // แก้ไขข้อมูล HD DT
    public function FSxCJR1EditEvent(){
        $aDataDocument      = $this->input->post();
        $tJR1DocNo          = (isset($aDataDocument['oetJR1DocNo'])) ? $aDataDocument['oetJR1DocNo'] : '';
        $tJR1DocDate        = $aDataDocument['oetJR1DocDate'] . " " . $aDataDocument['oetJR1DocTime'];
        $tJR1VATInOrEx      = 1;
        $tJR1SessionID      = $this->session->userdata('tSesSessionID');

        $aDataTableTmp = array(
            'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
            'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp',
            'tTableHDDisTmp'    => 'TSVTJRQHDDisTmp'
        );
        FCNaHCalculateProrate('TSVTJob1ReqDT',$tJR1DocNo,$aDataTableTmp);

        $aCalcDTParams      = [
            'tDataDocEvnCall'   => '',
            'tBchCode'          => $aDataDocument['ohdJR1BchCode'],
            'tDataVatInOrEx'    => $tJR1VATInOrEx,
            'tDataDocNo'        => $tJR1DocNo,
            'tDataDocKey'       => 'TSVTJob1ReqDT',
            'tDataSeqNo'        => '',
            'tTableDTTmp'       => 'TSVTJRQDocDTTmp',
            'tTableDTDisTmp'    => 'TSVTJRQDTDisTmp'
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        // Prorate HD
        FCNaHCalculateProrate('TSVTJob1ReqDT', $tJR1DocNo,$aDataTableTmp);

        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        $aCalDTTempParams = [
            'tDocNo'            => $tJR1DocNo,
            'tBchCode'          => $aDataDocument['ohdJR1BchCode'],
            'tSessionID'        => $tJR1SessionID,
            'tDocKey'           => 'TSVTJob1ReqDT',
            'tDataVatInOrEx'    => $tJR1VATInOrEx,
        ];
        $this->Jobrequeststep1_model->FSaMJR1CalVatLastDT($aCalDTTempParams);

        // Get Data Total HD
        $aCalDTTempForHD    = $this->FSaCJR1CalDTTempForHD($aCalDTTempParams);

        // Get Data HD Dis
        $aCalInHDDisTemp    = $this->Jobrequeststep1_model->FSaMJR1CalInHDDisTemp($aCalDTTempParams);

        // Array Data Table Document
        $aTableAddUpdate    = [
            'tTableHD'      => 'TSVTJob1ReqHD',
            'tTableHDDis'   => 'TSVTJob1ReqHDDis',
            'tTableDT'      => 'TSVTJob1ReqDT',
            'tTableDTSet'   => 'TSVTJob1ReqDTSet',
            'tTableDTDis'   => 'TSVTJob1ReqDTDis',
            'tTableHDCst'   => 'TSVTJob1ReqHDCst',
            'tTableStaGen'  => 1
        ];

        // Array Data Where Insert
        $aDataWhere         = [
            'FTAgnCode'     => $aDataDocument['ohdJR1ADCode'],
            'FTBchCode'     => $aDataDocument['ohdJR1BchCode'],
            'FTXshDocNo'    => $tJR1DocNo,
            'FDLastUpdOn'   => date('Y-m-d H:i:s'),
            'FDCreateOn'    => date('Y-m-d H:i:s'),
            'FTCreateBy'    => $this->session->userdata('tSesUsername'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FTXthVATInOrEx'=> $tJR1VATInOrEx
        ];

        // Array Data HD Master
        $aDataMasterHD      = [
            'FDXshDocDate'          => (!empty($tJR1DocDate)) ? $tJR1DocDate : NULL,
            'FTXshToShp'            => '',
            'FTXshToPos'            => (!empty($aDataDocument['oetJR1BayCode']))? $aDataDocument['oetJR1BayCode'] : '',
            'FDXshTimeStart'        => @$aDataDocument['oetJR1BookDate'] .' '. $aDataDocument['oetJR1TimeBook'],
            'FDXshTimeStop'         => null,
            'FTCstCode'             => $aDataDocument['oetJR1FrmCstCode'],
            'FCXshCarMileage'       => floatval(str_replace(',','',$aDataDocument['oetJR1CarMiter'])),
            'FTXshCarFuel'          => $aDataDocument['ohdJR1FrmCarFuel'],
            'FTXshStaBook'          => (!empty($aDataDocument['oetJR1BookUse'])) ? 1 : 2,
            'FTXshCarChkRmk1'       => $aDataDocument['oetJR1FrmCarChkRmk1'],
            'FTXshCarChkRmk2'       => null,
            'FTUsrCode'             => $aDataDocument['oetJR1UsrValetCode'],
            'FTRteCode'             => null,
            'FCXshRteFac'           => null,
            'FCXshTotal'            => $aCalDTTempForHD['FCXphTotal'],
            'FCXshTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
            'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
            'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
            'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
            'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : null,
            'FCXshDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : null,
            'FCXshChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : null,
            'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
            'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
            'FCXshAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
            'FCXshAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
            'FCXshVat'              => $aCalDTTempForHD['FCXphVat'],
            'FCXshVatable'          => $aCalDTTempForHD['FCXphVatable'],
            'FCXshWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
            'FCXshGrand'            => $aCalDTTempForHD['FCXphGrand'],
            'FTXshRmk'              => $aDataDocument['otaJR1Remark'],
            'FTRsnCode'             => null,
            'FTXshApvCode'          => $aDataDocument['ohdJR1StaApvCode'],
            'FTXshStaApv'           => $aDataDocument['ohdJR1StaApv'],
            'FTXshStaDoc'           => $aDataDocument['ohdJR1StaDoc'],
            'FNXshStaDocAct'        => 1,
            'FDXshVchRecDate'       => @$aDataDocument['oetJR1PickInDate'] .' '. $aDataDocument['oetJR1PickInTime'],
        ];

        // Array Data HDCST
        $aDataCst          = [
            'FTAgnCode'     => $aDataDocument['ohdJR1ADCode'],
            'FTBchCode'     => $aDataDocument['ohdJR1BchCode'],
            'FTCarCode'     => $aDataDocument['oetJR1CarRegCode'],
            'FTXshDocNo'    => $tJR1DocNo,
            'FTXshCardID'   => $aDataDocument['oetJR1FrmCstCode'],
            'FTXshCstTel'   => $aDataDocument['oetJR1FrmCstTel'],
            'FTXshCstName'  => $aDataDocument['oetJR1FrmCstName']
        ];

        $this->db->trans_begin();

        // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
        if($aDataDocument['oetJR1DocRefBookCode'] != ''){
            //1: อ้างอิงถึง(ภายใน) => ใบนัดหมาย
            $aDataWhereDocRef_Type1 = array(
                'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXshDocNo'],
                'FTXshRefType'      => 1,
                'FTXshRefDocNo'     => $aDataDocument['oetJR1DocRefBookCode'],
                'FTXshRefKey'       => 'BOOK',
                'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefBookDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefBookDate'])) : NULL
            );
            $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTJob1ReqHDDocRef',$aDataWhereDocRef_Type1);
            //2: ถูกอ้างอิง(ภายใน) => ใบรับรถ
            $aDataWhereDocRef_Type2 = array(
                'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                'FTXshDocNo'        => $aDataDocument['oetJR1DocRefBookCode'],
                'FTXshRefType'      => 2,
                'FTXshRefDocNo'     => $aDataWhere['FTXshDocNo'],
                'FTXshRefKey'       => 'Job1Req',
                'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefBookDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefBookDate'])) : NULL
            );
            $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTBookHDDocRef',$aDataWhereDocRef_Type2);
        }

        // [Update] ถ้ามีเอกสารอ้างอิงภายนอก
        if($aDataDocument['oetJR1DocRefExtDoc'] != '' ){
            //3: อ้างอิง ภายนอก
            $aDataWhereDocRef_Type3 = array(
                'FTAgnCode'         => ($aDataDocument['ohdJR1ADCode'] = '') ? ' ' : $aDataDocument['ohdJR1ADCode'],
                'FTBchCode'         => $aDataDocument['ohdJR1BchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXshDocNo'],
                'FTXshRefType'      => 3,
                'FTXshRefDocNo'     => $aDataDocument['oetJR1DocRefExtDoc'],
                'FTXshRefKey'       => 'BillNote',
                'FDXshRefDocDate'   => (!empty($aDataDocument['oetJR1DocRefExtDocDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetJR1DocRefExtDocDate'])) : NULL
            );
            $this->Jobrequeststep1_model->FSxMJR1UpdateRef('TSVTJob1ReqHDDocRef',$aDataWhereDocRef_Type3);
        }

        // [Add] Update Document HD
        $this->Jobrequeststep1_model->FSxMJR1AddUpdateHD($aDataMasterHD, $aDataWhere, $aTableAddUpdate);

        // [Add] Update Document Cst
        $this->Jobrequeststep1_model->FSxMJR1AddUpdateCSTHD($aDataCst, $aDataWhere, $aTableAddUpdate);

        // [Update] DocNo -> Temp
        $this->Jobrequeststep1_model->FSxMJR1AddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

        // [Add] Doc DTTemp -> DT
        $this->Jobrequeststep1_model->FSaMJR1MoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

        // [Add] Doc DTTempSet -> DTSet
        $this->Jobrequeststep1_model->FSaMJR1MoveDTTmpSetToDTSet($aDataWhere, $aTableAddUpdate);

        // [Add] Move Doc HDDisTemp -> HDDis
        $this->Jobrequeststep1_model->FSaMJR1MoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);

        // [Add] Doc DTDisTemp -> DTDis
        $this->Jobrequeststep1_model->FSaMJR1MoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);

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
                'tAgnCode'      => $aDataWhere['FTAgnCode'],
                'tBchCode'      => $aDataWhere['FTBchCode'],
                'tCarCode'      => $aDataCst['FTCarCode'],
                'tCodeReturn'   => $aDataWhere['FTXshDocNo'],
                'nStaReturn'    => '1',
                'tStaMessg'     => 'Success Add Document.'
            );
        }
        echo json_encode($aReturnData);
    }

    //คำนวณจาก DT Temp ให้ HD
    private function FSaCJR1CalDTTempForHD($paParams) {
        $aCalDTTemp = $this->Jobrequeststep1_model->FSaMJR1CalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems    = $aCalDTTemp[0];
            //$nRound             = 0;
            $cGrand             = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV'];
            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            //$aCalDTTempItems['FCXphRnd'] = $nRound;
            $aCalDTTempItems['FCXphGrand'] = $cGrand;
            $aCalDTTempItems['FTXphGndText'] = $tGndText;
            return $aCalDTTempItems;
        }
    }

    //Event Delete Document
    public function FSoCJR1EventDelete(){
        try {
            $aDataWhere = [
                'FTAgnCode'     => $this->input->post('tJR1AgnCode'),
                'FTBchCode'     => $this->input->post('tJR1BchCode'),
                'FTXshDocNo'    => $this->input->post('tDataDocNo'),
            ];
            $aResDelDoc = $this->Jobrequeststep1_model->FSnMJR1DelDocument($aDataWhere);

            // ################ Delete File Document ################
            $aDataDeleteFile = array(
                'tDocNo'    => $aDataWhere['FTXshDocNo'],
                'tBchCode'  => $aDataWhere['FTBchCode'],
                'tDocKey'   => 'TSVTJob1ReqHD'
            );
            FCNaUPFDelDocFileEvent($aDataDeleteFile);

            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'tAgnCode'  => $aDataWhere['FTAgnCode'],
                    'tBchCode'  => $aDataWhere['FTBchCode'],
                    'tXshDocNo' => $aDataWhere['FTXshDocNo'],
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc']
                );
            }
        }catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aDataStaReturn);
    }

    //Event ยกเลิกเอกสาร
    public function FSvCJR1CancelDocument(){
        $tBchCode   = $this->input->post('tBchCode');
        $tDocNo     = $this->input->post('tDocNo');
        $tRefIntDoc = $this->input->post('tRefIntDoc');

        $aDataUpdate = array(
            'FTBchCode'         => $tBchCode,
            'FTXshDocNo'        => $tDocNo,
            'FTXshStaDoc'       => '3',
            'tRefInt'           => $tRefIntDoc
        );
        $aStaDoc = $this->Jobrequeststep1_model->FSaMJR1UpdateStaDocCancel($aDataUpdate);

        if( $aStaDoc['rtCode'] == '1' ){
            $aMQParams = [
                "queueName" => "CN_QDocApprove",
                "params"    => [
                    'ptFunction'    => "TSVTJob1ReqHD",
                    'ptSource'      => 'AdaStoreBack',
                    'ptDest'        => 'MQReceivePrc',
                    'ptFilter'      => $tBchCode,
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptDocType"     => '',
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            FCNxCallRabbitMQ($aMQParams);
        }

        $aReturn    = array(
            'rtCode' => $aStaDoc['rtCode'],
            'rtDesc' => $aStaDoc['rtDesc']
        );
        echo json_encode($aReturn);
    }

    //ตรวจสินค้าก่อนอนุมัติเอกสาร
    public function FSoCJR1CheckApproveEvent(){
        try{
            $tDocNo                 = $this->input->post('tDocNo');
            $tBchCode               = $this->input->post('tBchCode');
            $aData = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshApvCode'      => $this->session->userdata('tSesUsername'),
            );
            $aResultCheck = $this->Jobrequeststep1_model->FSaMJR1CheckApproveDocument($aData);
            if ($aResultCheck['rtCode'] == 800) { //มีสินค้ายังไม่ผ่าน
                $aReturnData = array(
                    'rtCode'    => 800
                );
            } else { //สินค้าผ่านทุกตัว
                $aReturnData = array(
                    'rtCode'     => 1
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'rtCode'         => '500',
                'tStaMessg'         => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }
    
    //อนุมัติเอกสาร
    public function FSoCJR1ApproveEvent(){
        try{
            $tDocNo     = $this->input->post('tDocNo');
            $tBchCode   = $this->input->post('tBchCode');
            $aDataWhere = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TSVTJob1ReqDT',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            );
            // Send Appove Code
            $aMQParams = [
                "queueName" => "CN_QDocApprove",
                "params"    => [
                    'ptFunction'    => "TSVTJob1ReqHD",
                    'ptSource'      => 'AdaStoreBack',
                    'ptDest'        => 'MQReceivePrc',
                    'ptFilter'      => $tBchCode,
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptDocType"     => '',
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            // Call Rabbit MQ อนุมัติ และ Check Stock
            FCNxCallRabbitMQ($aMQParams);

            //สถานะเช็คคลัง
            // $aCheckSTK   = $this->Jobrequeststep1_model->FSaMJR1CheckWahouseCheckStock($tDocNo);
            // if($aCheckSTK['raItem'][0]['CHKSTK'] == 0){ //0: คือไม่ต้องเช็คคลัง , มากกว่า 0 คือต้องเช็ค
            //     //อัพเดทสถานะเอกสาร
            //     $this->Jobrequeststep1_model->FSaMJR1UpdateStaApvInHDAndDT($tDocNo);
            // }

            $aReturnData = array(
                'nStaEvent'         => 1,
                'tStaMessg'         => 'SUCCESS',
                'nLangEdit'         => $this->session->userdata("tLangEdit"),
                'tBchCode'          => $tBchCode,
                'tDocNo'            => $tDocNo,
                'tUsrApv'           => $this->session->userdata("tSesUsername"),
                // 'tStaChkWah'        => $aCheckSTK['raItem'][0]['CHKSTK'] //0: คือไม่ต้องเช็คคลัง , มากกว่า 0 คือต้องเช็ค
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ######################################################################### EDIT INLINE PRODUCT SET DATA #########################################################################
    
    // อัพเดตข้อมูลสินค้าจากสินค้าเดิม
    public function FSxCHR1EventUpdInlinePdtSet(){
        try{
            // Data Send Form Ajax
            $poItem         = $this->input->post('poItemPdt');
            $poItemWhere    = $this->input->post('poInlinePdt');
            if(isset($poItem) && !empty($poItem)){
                $this->db->trans_begin();
                // Loop ข้อมูลตระกร้าสินค้า
                for($i=0; $i<count($poItem); $i++){
                    $tPDTCode   = $poItem[$i]['packData']['PDTCode'];
                    $tPDTName   = $poItem[$i]['packData']['PDTName'];
                    $tPunCode   = $poItem[$i]['packData']['PUNCode'];
                    $tBarCode   = $poItem[$i]['packData']['Barcode'];
                    // Check Get Data Price
                    $aDataPri   = $this->Jobrequeststep1_model->FSaMJR1GetDataPricePdt($tPDTCode,$tPunCode);
                    if($aDataPri['rtCode'] == '1'){
                        $tPdtPriceRet   = (!empty($aDataPri['raItems']['FCPgdPriceRet']))? $aDataPri['raItems']['FCPgdPriceRet'] : 0;
                    }else{
                        $tPdtPriceRet   = 0;
                    }
                    // Array Data Update
                    $aDataUpdate    = [
                        'FTPdtCode'         => $tPDTCode,
                        'FTXtdPdtName'      => $tPDTName,
                        'FTPunCode'         => $tPunCode,
                        'FCXtdSalePrice'    => $tPdtPriceRet,
                        'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                        'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    ];
                    // Array Data Where
                    $aDataWhere     = [
                        'FTBchCode'     => $poItemWhere['tJR1BchCode_Inline'],
                        'FTPdtCodeOrg'  => $poItemWhere['tJR1PdtCodeOrg_Inlne'],
                        'FTSrnCode'     => $poItemWhere['tJR1SrnCode_Inlne'],
                        'FTSessionID'   => $this->session->userdata('tSesSessionID')
                    ];
                    $this->Jobrequeststep1_model->FSaMJR1UpdPdtSetInline($aDataUpdate,$aDataWhere);
                }
                // Check Transection DB
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => "Error Update Inline Into Document DT SET Temp."
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => "Update Inline Into Document DT SET Temp."
                    );
                }
            }
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // คืนค่าข้อมูลสินค้าเดิมจากระบบที่จัดชุด
    public function FSxCHR1EventRejInlinePdtSet(){
        try{
            // Data Send Form Ajax
            $poItem = $this->input->post('poItemPdtSet');
            if(isset($poItem) && !empty($poItem)){
                // Array Data Where
                $aDataWhere = [
                    'FTBchCode'     => $poItem['tJR1BchCode_Inline'],
                    'FTXthDocNo'    => $poItem['tJR1Docno_Inline'],
                    'FTPdtCode'     => $poItem['tJR1PdtCode_Inline'],
                    'FTPdtCodeOrg'  => $poItem['tJR1PdtCodeOrg_Inlne'],
                    'FTSrnCode'     => $poItem['tJR1SrnCode_Inlne'],
                    'FTSessionID'   => $this->session->userdata('tSesSessionID')
                ];
                $aDataPdtOrgOld = $this->Jobrequeststep1_model->FSaMJR1GetDataPdtOrg($aDataWhere);
                if($aDataPdtOrgOld['rtCode'] == '1'){
                    $aDataUpdate    = [
                        'FTPdtCode'         => $aDataPdtOrgOld['raItems']['FTPdtCodeOrg'],
                        'FTXtdPdtName'      => $aDataPdtOrgOld['raItems']['FTPdtNameOrg'],
                        'FTPunCode'         => $aDataPdtOrgOld['raItems']['FTPunCodeOrg'],
                        'FCXtdSalePrice'    => $aDataPdtOrgOld['raItems']['FCPgdPriceRetOrg'],
                        'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                        'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    ];
                    $this->db->trans_begin();
                    $this->Jobrequeststep1_model->FSaMJR1UpdPdtSetInline($aDataUpdate,$aDataWhere);
                    // Check Transection DB
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $aReturnData = array(
                            'nStaEvent' => '500',
                            'tStaMessg' => "Error Update Inline Into Document DT SET Temp."
                        );
                    }else{
                        $this->db->trans_commit();
                        $aReturnData = array(
                            'nStaEvent' => '1',
                            'tStaMessg' => "Update Inline Into Document DT SET Temp."
                        );
                    }
                }else{
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => "Error Update Inline Into Document DT SET Temp."
                    );
                }
            }
        }catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค้นหาข้อมูล Stock Error In DB
    public function FSoCJR1EventCheckProductWahouse(){
        $tDocNo     = $this->input->post('tDocNo');
        $tBchCode   = $this->input->post('tBchCode');
        $aDataWhere = array(
            'FTBchCode'     => $tBchCode,
            'FTXshDocNo'    => $tDocNo
        );
        $aChkStkBehideApv = $this->Jobrequeststep1_model->FSaMChkStockBehideApv($aDataWhere);
        if( FCNnHSizeOf($aChkStkBehideApv) == 0 ){
            // เอกสารอนุมัติสำเร็จ
            $aReturnData    = array(
                'nStaEvent' => 1,
                'tStaMessg' => 'SUCCESS',
            );
        }else{

            //วิ่ง STK 
            $aNotFoundItemInWah = '';

            //Cookie : สำหรับ config API Check Stock
            $tGetConfigAPI = get_cookie('tAPICheckSTK');
            if(isset($tGetConfigAPI)  && !empty($tGetConfigAPI)){
                get_cookie('tAPICheckSTK');
                $aConfig = json_decode($tGetConfigAPI);
                $aConfig = json_decode(json_encode($aConfig), true);
            }else{
                $aAPIModalCheckSTK = $this->Jobrequeststep1_model->FSaMJR1GetConfigAPI();
                $aCookieAPICheckSTK = array(
                    'name'      => 'tAPICheckSTK',
                    'value'     => json_encode($aAPIModalCheckSTK),
                    'expire'    => 0
                );
                set_cookie($aCookieAPICheckSTK);
                $aConfig = $aAPIModalCheckSTK;
            }

            if($aConfig['rtCode'] == '800'){
                $aReturnData = array(
                    'nStaEvent' => 600,
                    'tStaMessg' => 'เกิดข้อผิดพลาด ไม่พบ API ในการเชื่อมต่อ'
                );
                echo json_encode($aReturnData);
                return false;
            }else{
                $tUrlAddress = $aConfig['raItems'][0]['FTUrlAddress'];
            }

            $tUrlApi    = $tUrlAddress.'/Stock/CheckStockPdts';
            $aParam     = $aChkStkBehideApv;
            $aAPIKey    = array(
                'tKey'      => 'X-API-KEY',
                'tValue'    => '12345678-1111-1111-1111-123456789410'
            );
            $aResult    = FCNaHCallAPIBasic($tUrlApi,'POST',$aParam,$aAPIKey);
            if( $aResult['rtCode'] == '001' ){
                $aHaveItemInWah     = array();
                $aNotFoundItemInWah = array();
                $nCountItem         = FCNnHSizeOf($aResult['raItems']);

                for($i=0; $i<$nCountItem; $i++){
                    if($aResult['raItems'][$i]['rtStaPrcStock'] == 2 ){ //stock ไม่พอ
                        //สินค้า , จำนวนร้องขอ , จำนวนคงเหลือ
                        $aFindTextNamePDTNoStock    = $this->Jobrequeststep1_model->FSxMJR1FindTextNamePDTNoStock("'".$aResult['raItems'][$i]['rtPdtCode']."'");
                        $tPdtName                   = $aFindTextNamePDTNoStock[0]['FTPdtName'];
                        array_push($aNotFoundItemInWah,array($aResult['raItems'][$i]['rtPdtCode'],$tPdtName,$aResult['raItems'][$i]['rcReqQty'],$aResult['raItems'][$i]['rcStkQty']));
                    }else{
                        array_push($aHaveItemInWah,$aResult['raItems'][$i]['rtPdtCode']);
                    }
                }
            }else{
                $aReturnData = array(
                    'nStaEvent'     => 600,
                    'tStaMessg'     => 'API Error',
                    'aPdtSendAPI'   => $aChkStkBehideApv,
                    'oAPIReturn'    => $aResult
                );
            }

            // ไม่สามารถ Appove เอกสารได้เนื่องจาก Stock ไม่เพียงพอ
            $aReturnData    = array(
                'nStaEvent'         => 600,
                'tStaMessg'         => 'ไม่สามารถอนุมัติเอกสารได้เนื่องจากมีสินค้าบางรายการมีสต๊อกไม่เพียงพอ',
                'aItemFail'         => $aNotFoundItemInWah
            );
        }
        echo json_encode($aReturnData);
    }

    // Move จาก DT To DTTemp อีกครั้ง
    public function FSoCJR1EventMoveDTToTemp(){
        //ล้างค่าใน Temp
        $this->Jobrequeststep1_model->FSaMJR1DeletePDTInTmp();

        $aDataWhere = array(
            'FTBchCode'     => $this->input->post('tBchCode'),
            'FTXshDocNo'    => $this->input->post('tDocNo'),
            'tDocKey'       => 'TSVTJob1ReqDT',
            'tSessionID'    => $this->session->userdata('tSesSessionID')
        );

        // Move Data HD DIS To HD DIS Temp
        $this->Jobrequeststep1_model->FSxMJR1MoveHDDisToTemp($aDataWhere);

        // Move Data DT TO DTTemp
        $this->Jobrequeststep1_model->FSxMJR1MoveDTToDTTemp($aDataWhere);

        // Move Data DTSet TO DTSetTemp
        $this->Jobrequeststep1_model->FSxMJR1MoveDTSetToDTTempSet($aDataWhere);

        // Move Data DTDIS TO DTDISTemp
        $this->Jobrequeststep1_model->FSxMJR1MoveDTDisToDTDisTemp($aDataWhere);
    }

    //หาว่าสินค้าตัวนี้ มีสินค้าเซตอะไรบ้าง
    public function FSaCJR1FindDTSet(){
        $tDocNo     = $this->input->post('tDocNo');
        $tBchCode   = $this->input->post('tBchCode');
        $tPDTCode   = $this->input->post('tPDTCode');
        $nSeqno     = $this->input->post('nSeqno');

        $aPackData    = array(
            'tDocNo'        => $tDocNo,
            'tBchCode'      => $tBchCode,
            'tPDTCode'      => $tPDTCode,
            'nSeqno'        => $nSeqno,
        );

        $aItemset   = $this->Jobrequeststep1_model->FSaMJR1FindDTSet($aPackData);
        echo json_encode($aItemset);
    }

}
