<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Invoicecustomerbill_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/invoicecustomerbill/Invoicecustomerbill_model');

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nIVCBrowseType,$tIVCBrowseOption){

        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        
        $aDataConfigView    = array(
            'nIVCBrowseType'        => $nIVCBrowseType,
            'tIVCBrowseOption'      => $tIVCBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('docInvoiceCustomerBill/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'aParams'               => $aParams
        );
        $this->load->view('document/Invoicecustomerbill/wInvoiceCustomerBill',$aDataConfigView);
    }

    //List
    public function FSvCIVCPageList(){
        $this->load->view('document/Invoicecustomerbill/wInvoiceCustomerBillSearchList');   
    }

    //ตารางข้อมูล
    public function FSvCIVCDatatable(){
        $tAdvanceSearchData     = $this->input->post('oAdvanceSearch');
        $nPage                  = $this->input->post('nPageCurrent');
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

        $aList      = $this->Invoicecustomerbill_model->FSaMIVCList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );

        $tViewDataTable = $this->load->view('document/Invoicecustomerbill/wInvoiceCustomerBillDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCIVCPageAdd(){
        try{

            //ล้างค่าใน Temp
            $this->Invoicecustomerbill_model->FSaMIVCDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'aDataDocCST'       => array('rtCode'=>'99'),    
            );
            $tViewPageAdd       = $this->load->view('document/Invoicecustomerbill/wInvoiceCustomerBillPageAdd',$aDataConfigViewAdd,true);
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
    public function FSvCIVCPageEdit(){
        try {
            $ptDocumentNumber = $this->input->post('ptIVCDocNo');

            // Clear Data In Doc DT Temp
            $this->Invoicecustomerbill_model->FSaMIVCDeletePDTInTmp($ptDocumentNumber);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Array Data Where Get (HD,HDCst)
            $aDataWhere = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->Invoicecustomerbill_model->FSaMIVCGetDataDocHD($aDataWhere);
            $aDataWhere['FTCstCode'] = $aDataDocHD['raItems']['FTCstCode'];
            $aDataWhere['FTBchCode'] = $aDataDocHD['raItems']['FTBchCode'];

            // Get Data Document CST HD
            $aDataDocSPLHD      = $this->Invoicecustomerbill_model->FSaMIVCGetDataDocCSTHD($aDataWhere);
            

            // Get Data Document Ref
            $aDataDocHDDocRef   = $this->Invoicecustomerbill_model->FSaMIVCGetDataDocHDDocRef($aDataWhere);
            $aChkData = $this->Invoicecustomerbill_model->FSaMIVCChkCstBch($aDataWhere['FTCstCode']);

            

            // Move Data DT To DTTemp , DTSPL To DTTemp
            $this->Invoicecustomerbill_model->FSxMIVCMoveDTToDTTemp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();

                $aDataConfigViewAdd = array(
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'aDataDocHD'        => $aDataDocHD,
                    'aDataDocSPL'       => $aDataDocSPLHD,
                    'aDataDocHDDocRef'  => $aDataDocHDDocRef
                );
                $tViewPageAdd           = $this->load->view('document/Invoicecustomerbill/wInvoiceCustomerBillPageAdd',$aDataConfigViewAdd,true);
                $aReturnData = array(
                    'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
                    'tViewPageAdd'      => $tViewPageAdd,
                    'aChkData'         => $aChkData,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Success'
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

    //--------------------------------------- บันทึก แก้ไข ลบ --------------------------------------------//

    //เพิ่มข้อมูล HD DT 
    public function FSxCIVCEventAdd(){
        try {
            $aDataDocument  = $this->input->post();
            
            // print_r($aDataDocument);
            // exit();
            $tIVCAutoGenCode = (isset($aDataDocument['ocbIVCStaAutoGenCode'])) ? 1 : 0;
            $tIVCDocNo       = (isset($aDataDocument['oetIVCDocNo'])) ? $aDataDocument['oetIVCDocNo'] : '';
            $tIVCDocDate     = $aDataDocument['oetIVCDocDate'] . " " . $aDataDocument['oetIVCDocTime'];

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TACTSBHD',                    //ข้อมูลเอกสาร
                'tTableHDRef'       => 'TACTSBHDDocRef',              //อ้างอิงเอกสาร
                'tTableDT'          => 'TACTSBDT',                    //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TACTSBHDCst',                 //ส่งสินค้าไปหา - ผู้จำหน่าย
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdIVCBchCode'],
                'FTCstCode'         => $aDataDocument['ohdIVCCSTCode'],
                'FTSplCode'         => $aDataDocument['ohdIVCCSTCode'],
                'FTXphDocNo'        => $tIVCDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
            );

            $nStaPrcDoc = 1;
            

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'                 => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'                 => $aDataDocument['ohdIVCBchCode'],
                'FTXphDocNo'                => $tIVCDocNo,
                'FTXphDocType'              => '1',
                'FTSplCode'                 => null,
                'FTCstCode'                 => $aDataDocument['ohdIVCCSTCode'],
                'FTPrdCode'                 => $aDataDocument['oetIVCPrdCode'],
                'FDXphDueDate'              => $aDataDocument['oetIVCPaidDate'],
                'FTXphCond'                 => $aDataDocument['otaIVCCondition'],
                'FCXphTotal'                => $aDataDocument['ohdIVCGrand'],
                'FCXphGndXC'                => 0,
                'FCXphGndXN'                => 0,
                'FCXphGndXX'                => 0,
                'FCXphGrand'                => $aDataDocument['ohdIVCGrand'],
                'FTXphGrandText'            => $aDataDocument['ohdIVCGrandText'],
                'FTXphRmk'                  => $aDataDocument['otaIVCFrmInfoOthRmk'],
                'FTXphCtrName'              => null,
                'FTDptCode'                 => null,
                'FTXphStaDoc'               => 1,
                'FTXphStaPaid'              => null,
                'FNXphStaDocAct'            => 1,
                'FNXphStaRef'               => null,
                'FNXphDocPrint'             => 1,
                'FDXphDocDate'               => $tIVCDocDate,
            );

            // Array Data SPL
            $aDataSPL = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdIVCBchCode'],
                'FTXphCtrName'      => $aDataDocument['oetIVCCtrName'],
                'FTXphDstPaid'      => $aDataDocument['ocmIVDstPaid'],
                'FTXphCshOrCrd'     => $aDataDocument['ocmIVPaymentType'],
                'FNXphCrTerm'       => $aDataDocument['oetIVCreditTerm'],
                'FTXphCstRef'       => $aDataDocument['oetIVCCstBchFrm'],
                'FTXphDocNo'        => $tIVCDocNo
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tIVCAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"      => $aTableAddUpdate['tTableHD'],
                    "tDocType"      => 0,
                    "tBchCode"      => $aDataDocument['ohdIVCBchCode'],
                    "tShpCode"      => "",
                    "tPosCode"      => "",
                    "dDocDate"      => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo']   = $tIVCDocNo;
            }

            // [Add] Document HD
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document Cst
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Invoicecustomerbill_model->FSaMIVCMoveDTTmpToDT($aDataWhere, $aTableAddUpdate ,$aDataMaster);

            
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
                    'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
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

    //แก้ไขข้อมูล
    public function FSxCIVCEventEdit(){
        try {
            $aDataDocument   = $this->input->post();
            $tIVCDocNo       = (isset($aDataDocument['oetIVCDocNo'])) ? $aDataDocument['oetIVCDocNo'] : '';
            $tIVCDocDate     = $aDataDocument['oetIVCDocDate'] . " " . $aDataDocument['oetIVCDocTime'];
            
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TACTSBHD',                    //ข้อมูลเอกสาร
                'tTableHDRef'       => 'TACTSBHDDocRef',              //อ้างอิงเอกสาร
                'tTableDT'          => 'TACTSBDT',                    //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TACTSBHDCst',                 //ส่งสินค้าไปหา - ผู้จำหน่าย
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdIVCBchCode'],
                'FTCstCode'         => $aDataDocument['ohdIVCCSTCode'],
                'FTSplCode'         => $aDataDocument['ohdIVCCSTCode'],
                'FTXphDocNo'        => $tIVCDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
            );
  
            $nStaPrcDoc = 1;
        
            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'                 => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'                 => $aDataDocument['ohdIVCBchCode'],
                'FTXphDocNo'                => $tIVCDocNo,
                'FTXphDocType'              => '1',
                'FTSplCode'                 => null,
                'FTCstCode'                 => $aDataDocument['ohdIVCCSTCode'],
                'FTPrdCode'                 => $aDataDocument['oetIVCPrdCode'],
                'FDXphDueDate'              => $aDataDocument['oetIVCPaidDate'],
                'FTXphCond'                 => $aDataDocument['otaIVCCondition'],
                'FCXphTotal'                => $aDataDocument['ohdIVCGrand'],
                'FCXphGndXC'                => 0,
                'FCXphGndXN'                => 0,
                'FCXphGndXX'                => 0,
                'FCXphGrand'                => $aDataDocument['ohdIVCGrand'],
                'FTXphGrandText'            => $aDataDocument['ohdIVCGrandText'],
                'FTXphRmk'                  => $aDataDocument['otaIVCFrmInfoOthRmk'],
                'FTXphCtrName'              => null,
                'FTDptCode'                 => null,
                'FTXphStaDoc'               => 1,
                'FTXphStaPaid'              => null,
                'FNXphStaDocAct'            => 1,
                'FNXphStaRef'               => null,
                'FNXphDocPrint'             => 1,
                'FDXphDocDate'               => $tIVCDocDate,
            );

            // Array Data SPL
            $aDataSPL = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdIVCBchCode'],
                'FTXphCtrName'      => $aDataDocument['oetIVCCtrName'],
                'FTXphDstPaid'      => $aDataDocument['ocmIVDstPaid'],
                'FTXphCshOrCrd'     => $aDataDocument['ocmIVPaymentType'],
                'FNXphCrTerm'       => $aDataDocument['oetIVCreditTerm'],
                'FTXphCstRef'       => $aDataDocument['oetIVCCstBchFrm'],
                'FTXphDocNo'        => $tIVCDocNo
            );

            $this->db->trans_begin();



            // [Add] Document HD
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document SPL
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Invoicecustomerbill_model->FSxMIVCAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Invoicecustomerbill_model->FSaMIVCMoveDTTmpToDT($aDataWhere, $aTableAddUpdate ,$aDataMaster);

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
                    'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
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

    //ลบข้อมูลเอกสาร
    public function FSoCIVCEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Invoicecustomerbill_model->FSnMIVCDelDocument($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc']
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

    //ยกเลิกเอกสาร
    public function FSoCIVCInvoiceBillEventCancel(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Invoicecustomerbill_model->FSnMIVCInvoiceCustomerBillEventCancel($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc']
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

    //--------------------------------------- อ้างอิงเอกสารภายใน --------------------------------------------//

    //อ้างอิงเอกสารภายใน (ref ใบขาย)
    public function FSvCIVCCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/Invoicecustomerbill/refintdocument/wInvoiceCustomerBillRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCIVCCallRefIntDocDataTable(){

        $nPage                   = $this->input->post('nIVCRefIntPageCurrent');
        $tIVCRefIntBchCode       = $this->input->post('tIVCRefIntBchCode');
        $tIVCRefIntDocNo         = $this->input->post('tIVCRefIntDocNo');
        $tIVCRefIntDocDateFrm    = $this->input->post('tIVCRefIntDocDateFrm');
        $tIVCRefIntDocDateTo     = $this->input->post('tIVCRefIntDocDateTo');
        $tIVCRefIntStaDoc        = $this->input->post('tIVCRefIntStaDoc');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nIVCRefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tIVCRefIntBchCode'      => $tIVCRefIntBchCode,
            'tIVCRefIntDocNo'        => $tIVCRefIntDocNo,
            'tIVCRefIntDocDateFrm'   => $tIVCRefIntDocDateFrm,
            'tIVCRefIntDocDateTo'    => $tIVCRefIntDocDateTo,
            'tIVCRefIntStaDoc'       => $tIVCRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        $aDataParam = $this->Invoicecustomerbill_model->FSoMIVCCallRefIntDocDataTable($aDataCondition);

        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );

         $this->load->view('document/Invoicecustomerbill/refintdocument/wInvoiceCustomerBillRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCIVCCallRefIntDocDetailDataTable(){

        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        $aDataParam = $this->Invoicecustomerbill_model->FSoMIVCCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
          );
        $this->load->view('document/Invoicecustomerbill/refintdocument/wInvoiceCustomerBillRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp DT
    public function FSoCIVCCallRefIntDocInsertDTToTemp(){
        $tIVCDocNo          =  $this->input->post('tIVCDocNo');
        $tIVCFrmBchCode     =  $this->input->post('tIVCFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
       
        //เอาสินค้าลง Temp
        $aDataParam = array(
            'tIVCDocNo'         => $tIVCDocNo,
            'tIVCFrmBchCode'    => $tIVCFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'InvoiceCustomerBillStep1Point1'
        );
        $this->Invoicecustomerbill_model->FSoMIVCCallRefIntDocInsertDTToTemp($aDataParam);

        //Get ลูกค้าจากใบอ้างอิง
        $aFindCustomer  = $this->Invoicecustomerbill_model->FSoMIVCCallRefIntDocFindCstAndCar($aDataParam);
        $aReturnData    = array(
            'aFindCustomer' => $aFindCustomer
        );  
        echo json_encode($aReturnData);
    }

    //--------------------------------------- STEP 1 - POINT 1 --------------------------------------------//
    
    //โหลดข้อมูลสินค้า
    public function FSvCIVCStep1Point1Datatable(){
        $tIVCDocNo      = $this->input->post('ptIVCDocNo');

        $aData          = array(
            'tDocNo'    => $tIVCDocNo,
            'tDocKey'   => 'TACTSBDT'
        );
        $aList          = $this->Invoicecustomerbill_model->FSaMIVCListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicecustomerbill/step_form/wStep1Point1Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }


    //--------------------------------------- STEP 1 - POINT 2 --------------------------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCIVCStep1Point2Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tIVCDocNo      = $this->input->post('ptIVCDocNo');
        $tIVCStaApv     = $this->input->post('ptIVCStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tIVCDocNo,
            'tDocKey'   => 'InvoiceCustomerBillStep1Point1'
        );
        $aList          = $this->Invoicecustomerbill_model->FSaMIVCListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicecustomerbill/step_form/wStep1Point2Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // ค้นหาเอกสารหลังจากเลือกผู้จำหน่าย
    public function FSoCIVCFindinghBill(){
        try {
            $aDataWhere = array(
                'FTXphDocNo'            => $this->input->post('tDocno'),
                'FTSplCode'             => $this->input->post('tSPLCode'),
                'tDocType'              => $this->input->post('tSearchDocType'),
                'FDXphDueDateFrm'       => $this->input->post('tSearchDateFrm'),
                'FDXphDueDateTo'        => $this->input->post('tSearchDateTo'),
                'FTSearchXphDocNo'      => $this->input->post('tSearchDocNo'),
                'FTSearchBill'          => $this->input->post('tSearchDocRef'),
                'FTBchCode'             => $this->input->post('tBchCode'),
                'tType'                 => $this->input->post('tTypeIn'),
                'FDXphBchFrm'           => $this->input->post('tSearchBchFrm'),
                'FDXphBchTo'            => $this->input->post('tSearchBchTo'),
                'tSessionID'            => $this->session->userdata('tSesSessionID'),
                'tCstBchCode'           => $this->input->post('tCstBchCode'),
            );
            $aStatusDoc = $this->Invoicecustomerbill_model->FSnMIVCInvoiceCustomerBillEventFindBill($aDataWhere);

            //หาชื่อผู้ติดต่อมา default 
            $aContactSPL = $this->Invoicecustomerbill_model->FSaMIVCFindContact($this->input->post('tSPLCode'));

            if ($aStatusDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success',
                    'aContactSPL'   => $aContactSPL
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent'     => $aStatusDoc['rtCode'],
                    'tStaMessg'     => $aStatusDoc['rtDesc'],
                    'aContactSPL'   => $aContactSPL
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

    //โหลดข้อมูลสินค้า
    public function FSoCIVCFindinghBillPoint2(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tIVCDocNo      = $this->input->post('ptIVCDocNo');
        $tIVCStaApv     = $this->input->post('ptIVCStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tIVCDocNo,
            'tDocKey'   => 'TACTSBDTStep2',
            'tPdtCode'  => $this->input->post('tPdtCode')
        );
        // print_r($aData);
        $aList          = $this->Invoicecustomerbill_model->FSaMIVCListPoint2($aData);
        // print_r($aList);
        // exit();

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceCustomerBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicecustomerbill/step_form/wStep1Point2Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //ค้นหาที่อยู่ SPL
    public function FSoCIVCFindingCstAddress(){
        
        $tCstCode = $this->input->post('tCSTCode');
        $tCstBchCode = $this->input->post('tCSTBchCode');
        $tLangID = $this->session->userdata("tLangID");
        
        $aReturnData = $this->Invoicecustomerbill_model->FSaMIVCGetCstAddress($tCstCode,$tCstBchCode,$tLangID);
        echo json_encode($aReturnData);
    }

    public function FSoCIVCFindingCstBch(){
        $tCstCode = $this->input->post('tCSTCode');
        $aChkData = $this->Invoicecustomerbill_model->FSaMIVCChkCstBch($tCstCode);
        $aReturn = array(
            'aChkData' => $aChkData
        );
        echo json_encode($aReturn);
    }

    //ค้นหาที่อยู่ SPL
    public function FSoCIVCApproveEvent(){
        try{

            $tDocNo       = $this->input->post('tDocNo');
            $tBchCode     = $this->input->post('tBchCode');

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXphDocNo'        => $tDocNo,
                'FTXphStaApv'       => 1,
                'FTXphApvCode'       => $this->session->userdata('tSesUsername')
            );
            $this->Invoicecustomerbill_model->FSaMIVCApproveDocument($aDataUpdate);
            $this->Invoicecustomerbill_model->FSaMIVCChangeStatusDTDocument($aDataUpdate);

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

    public function FSoCIVCClearTmp()
    {
        $tDocNo = $this->input->post('tDocNo');
        $tStaClearTmp = $this->Invoicecustomerbill_model->FSaMIVCDeletePDTInTmp($tDocNo);
        echo json_encode($tStaClearTmp);
    }
}