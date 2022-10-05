<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');
require_once(APPPATH . 'config/rabbitmq.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Receiptpurchasepmt_controller extends MX_Controller {

    public $aPermission = [];
    public $tRouteMenu  = 'docRPP/0/0';
    public function __construct(){
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('document/receiptpurchasepmt/Receiptpurchasepmt_model');

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nRPPBrowseType, $tRPPBrowseOption){
        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        $this->aPermission  = FCNaHCheckAlwFunc($this->tRouteMenu);
        $aDataConfigView    = array(
            'nRPPBrowseType'    => $nRPPBrowseType,
            'tRPPBrowseOption'  => $tRPPBrowseOption,
            'aAlwEvent'         => $this->aPermission, // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML($this->tRouteMenu), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave(),
            'tRoute'            => $this->tRouteMenu,
            'aParams'           => $aParams
        );
        $this->load->view('document/receiptpurchasepmt/wReceiptPurchasepmt', $aDataConfigView);
    }

    // Function : แสดง Form Search ข้อมูลในตารางหน้า List
    // Creator  : 23/03/2022 Wasin
    public function FSvCRPPPageList(){
        $this->load->view('document/receiptpurchasepmt/wReceiptPurchasepmtList');
    }

    // Function : ตารางข้อมูล
    // Creator  : 23/03/2022 Wasin
    public function FSvCRPPPageDataTable(){
        $tAdvanceSearchData = $this->input->post('oAdvanceSearch');
        $nPage              = $this->input->post('nPageCurrent');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        if($nPage == '' || $nPage == null) {
            $nPage = 1;
        }else{
            $nPage = $this->input->post('nPageCurrent');
        }
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aData      = [
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $tAdvanceSearchData
        ];
        $aList      = $this->Receiptpurchasepmt_model->FSaMRPPList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docRPP/0/0'),
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $tViewDataTable = $this->load->view('document/receiptpurchasepmt/wReceiptPurchasepmtDataTable', $aGenTable ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // Function : หน้าจอเพิ่มข้อมูล
    // Creator  : 23/03/2022 Wasin
    public function FSvCRPPPageAdd(){
        try{
            //ล้างค่าใน Temp
            $this->Receiptpurchasepmt_model->FSaMRPPDeletePDTInTmp();
            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $aDataConfigViewAdd = [
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'aDataDocCST'       => array('rtCode'=>'99'),    
            ];
            $tViewPageAdd   = $this->load->view('document/receiptpurchasepmt/wReceiptPurchasepmtPageAdd',$aDataConfigViewAdd,true);
            $aReturnData    = [
                'tViewPageAdd'      => $tViewPageAdd,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            ];
        }catch(Exception $Error){
            $aReturnData    = [
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            ];
        }
        echo json_encode($aReturnData);
    }

    // Function : ค้นหาที่อยู่ SPL
    // Creator  : 28/03/2022 Wasin
    public function FSoCRPPFindingSplAddress(){
        $aReturnData    = $this->Receiptpurchasepmt_model->FSaMRPPGetSplAddress($this->input->post('tSPLCode'),$this->session->userdata("tLangID"));
        echo json_encode($aReturnData);
    }

    // Function : ค้นหาเอกสารหลังจากเลือกผู้จำหน่าย [ Step 1]
    // Creator  : 28/03/2022 Wasin
    public function FSoCRPPFindinghBillPoint1(){
        try {
            $aDataWhere = [
                'FTAgnCode'         => $this->input->post('tAgnCode'),
                'FTBchCode'         => $this->input->post('tBchCode'),
                'FTXphDocNo'        => $this->input->post('tDocno'),
                'FTSplCode'         => $this->input->post('tSPLCode'),
                'tDocType'          => $this->input->post('tSearchDocType'),
                'FDXphDueDateFrm'   => $this->input->post('tSearchDateFrm'),
                'FDXphDueDateTo'    => $this->input->post('tSearchDateTo'),
                'FTSearchXphDocNo'  => $this->input->post('tSearchDocno'),
                'FTSearchBill'      => $this->input->post('tSearchDocRef'),
                'tType'             => $this->input->post('tTypeIn'),
                'tSessionID'        => $this->session->userdata('tSesSessionID')
            ];
            $aStatusDoc     = $this->Receiptpurchasepmt_model->FSnMRPPEventFindBill($aDataWhere);
            
            // หาชื่อผู้ติดต่อมา Default Supplier 
            $aContactSPL    = $this->Receiptpurchasepmt_model->FSaMRPPFindContact($this->input->post('tSPLCode'));
            
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

    // Function : โหลดตารางเอกสารที่จะนำมาชำระ [ Step 1]
    // Creator  : 28/03/2022 Wasin
    public function FSvCRPPStep1Point1Datatable(){
        $tRPPAgnCode    = $this->input->post('tAGNCode');
        $tRPPBchCode    = $this->input->post('tBCHCode');
        $tRPPDocNo      = $this->input->post('tRPPDocNo');
        $aData  = [
            'tAgnCode'  => $tRPPAgnCode,
            'tBchCode'  => $tRPPBchCode,
            'tDocNo'    => $tRPPDocNo,
            'tDocKey'   => 'TACTPpDT'
        ];
        $aList      = $this->Receiptpurchasepmt_model->FSaMRPPListStep1Point1($aData);
        $aGenTable  = array(
            'aAlwEvent' => FCNaHCheckAlwFunc('docRPP/0/0'),
            'aDataList' => $aList
        );
        $tViewDataTable = $this->load->view('document/receiptpurchasepmt/step_form/wRPPStep1Point1Datatable', $aGenTable ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // Function : ค้นหารายละเอียดเอกสาร [ Step 2]
    // Creator  : 29/03/2022 Wasin
    public function FSoCRPPFindinghBillPoint2(){
        $tAGNCode   = $this->input->post('tAGNCode');
        $tBCHCode   = $this->input->post('tBCHCode');
        $tRPPDocNo  = $this->input->post('tRPPDocNo');
        $tRPPStaApv = $this->input->post('tRPPStaApv');
        // Data Where Document
        $aData      = [
            'tAGNCode'  => $tAGNCode,
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tRPPDocNo,
            'tDocKey'   => 'TACTPpDTStep2',
            'tPdtCode'  => $this->input->post('tPdtCode')
        ];
        $aList      = $this->Receiptpurchasepmt_model->FSaMRPPListStep1Point2($aData);
        $aGenTable  = array(
            'aAlwEvent' => FCNaHCheckAlwFunc('docRPP/0/0'),
            'aDataList' => $aList
        );
        $tViewDataTable = $this->load->view('document/receiptpurchasepmt/step_form/wRPPStep1Point2Datatable', $aGenTable ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // Function : ทำการนำยอดเงินที่กรอกมา Prorate ลงไปใน DT [ Step 2 ]
    // Creator  : 04/04/2022 Wasin
    public function FSoCRPPEventProRatePayment(){
        $nDecimal       = FCNxHGetOptionDecimalShow();
        $tAGNCode       = $this->input->post('tAGNCode');
        $tBCHCode       = $this->input->post('tBCHCode');
        $tSplCode       = $this->input->post('tSplCode');
        $tRPPDocNo      = $this->input->post('tRPPDocNo');
        $cInvPayment    = $this->input->post('cInvPayment');
        $tPdtCode       = $this->input->post('tPdtCode');
        $nLangEdit      = $this->session->userdata("tLangEdit");
        
        // Data Where Document
        $aDataWhere     = [
            'FTAgnCode'     => $tAGNCode,
            'FTBchCode'     => $tBCHCode,
            'FTSplCode'     => $tSplCode,
            'FTXshDocNo'    => $tRPPDocNo,
            'FNLngID'       => $nLangEdit,
            'FTXthDocKey'   => 'TACTPpDTStep2',
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ];

        // ############################# Data Call Default Document #############################
        $aDataDefault   = [
            'tAGNCode'  => $tAGNCode,
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tRPPDocNo,
            'tDocKey'   => 'TACTPpDTStep2',
            'tPdtCode'  => $tPdtCode
        ];
        $this->Receiptpurchasepmt_model->FSaMRPPListStep1Point2($aDataDefault);
        // ######################################################################################

        // Get ข้อมูล ราคารวม เอกสารใบลดหนี้ของลูกค้า
        $aDataDisPC = $this->Receiptpurchasepmt_model->FSaMGetDataDisDocPC($aDataWhere);
        if(isset($aDataDisPC) && !empty($aDataDisPC)){
            $tRPPTotalDisPC = $aDataDisPC['FCXtdTotalDisPC'];
        }else{
            $tRPPTotalDisPC = 0;
        }
        // เอายอดที่คีย์มาจาก Input มาบวกยอดที่รวมจาก เอกสารใบลดหนี้
        $tRPPPaymentKey         = floatval($cInvPayment+$tRPPTotalDisPC);

        // ดึงรายการเอกสารที่จะนำมา กระจายลงในแต่ละเอกสารโดยไม่เอาใบลดหนี้มาทำ
        $aRPPStep1Point2Data    = $this->Receiptpurchasepmt_model->FSaMGetDataStep1Pont2NotPC($aDataWhere);
        if($aRPPStep1Point2Data['rtCode'] == "1"){
            $this->db->trans_begin();
            foreach($aRPPStep1Point2Data['raItems'] AS $nKey => $aValue){
                $cInvGrand  = floatval($aValue['FCXtdInvGrand']);   // ยอดเอกสาร (D-Inv-field)
                $cInvPaid   = floatval($aValue['FCXtdInvPaid']);    // ยอดที่เคยชำระ
                $cInvRem    = floatval($aValue['FCXtdInvRem']);     // ยอดคงเหลือ (ค้างชำระครั้งต่อไป)
                $cInvPay    = floatval($aValue['FCXtdInvPay']);     // ยอดจ่าย /รับชำระครั้งปัจจุบัน
                if($cInvRem < $tRPPPaymentKey || $cInvRem == $tRPPPaymentKey){
                    // เช็คว่า จำนวนที่คงเหลือจ่าย มีค่าน้อยกว่า ยอดที่ Key + ใบลดหนี้     => จ่ายเต็มจำนวน
                    $aDataUpd   = array(
                        'FCXtdAmt'      => floatval($aValue['FCXtdInvGrand']),  // FCXtdInvGrand
                        'FCXtdSetPrice' => floatval($aValue['FCXtdInvGrand']),  // FCXtdInvPaid
                        'FCXtdVatable'  => floatval(0),                         // FCXtdInvRem
                        'FCXtdNet'      => floatval($aValue['FCXtdInvGrand']),  // FCXtdInvPay
                    );
                }else{
                    // เช็คว่า จำนวนที่คงเหลือจ่าย มากกว่า ยอดที่ Key + ใบลดหนี้        => จ่ายไปบ่างส่วนต้องคำนวณยอดค้างชำระ
                    $aDataUpd   = array(
                        'FCXtdAmt'      => $aValue['FCXtdInvGrand'],    
                        'FCXtdSetPrice' => floatval($tRPPPaymentKey),
                        'FCXtdVatable'  => floatval(($tRPPPaymentKey-$cInvRem)*-1),
                        'FCXtdNet'      => floatval($tRPPPaymentKey),
                    );
                }
                $tRPPPaymentKey -= $cInvRem;
                // Data Where Codition
                $aDataWhere = [
                    'FTBchCode'     => $aValue['FTBchCode'],
                    'FTXthDocNo'    => $aValue['FTXthDocNo'],
                    'FTXthDocKey'   => $aValue['FTXthDocKey'],
                    'FTPdtCode'     => $aValue['FTPdtCode'],
                    'FTSessionID'   => $aValue['FTSessionID'],
                ];
                $this->Receiptpurchasepmt_model->FSaMUpdDocStep1Point2DT($aDataWhere,$aDataUpd);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent' => '905',
                    'tStaMessg' => 'ไม่สามารถทำการคำนวณยอดเงินได้'
                );
            }else{
                $this->db->trans_commit();
                $aList      = $this->Receiptpurchasepmt_model->FSaMRPPGetDataListStep1Point2($aDataWhere);
                $aGenTable  = array(
                    'aAlwEvent' => FCNaHCheckAlwFunc('docRPP/0/0'),
                    'aDataList' => $aList
                );
                $tTableHtml     = $this->load->view('document/receiptpurchasepmt/step_form/wRPPStep1Point2DatatableList',$aGenTable, true);
                // Get Data All End Of Bill
                $aDataEndOfBill = [];
                $aEndOfBill     = $this->Receiptpurchasepmt_model->FSaMRPPGetDataListStep1Point2EndOfBill($aDataWhere);
                if(isset($aEndOfBill) && !empty($aEndOfBill)){
                    $aDataEndOfBill = [
                        'cInvGrandSum'  => number_format($aEndOfBill['FCXtdInvGrandSum'],$nDecimal),
                        'cInvPaidSum'   => number_format($aEndOfBill['FCXtdInvPaidSum'],$nDecimal),
                        'cInvRemSum'    => number_format($aEndOfBill['FCXtdInvRemSum'],$nDecimal),
                        'cInvPaySum'    => number_format($aEndOfBill['FCXtdInvPaySum'],$nDecimal),
                    ];
                }
                $aReturnData    = array(
                    'tTalbleHtml'       => $tTableHtml,
                    'aDataEndOfBill'    => $aDataEndOfBill,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'success'
                );
            }
        }else{
            $aReturnData    = array(
                'nStaEvent' => '905',
                'tStaMessg' => 'ไม่สามารถทำการคำนวณยอดเงินได้'
            );
        }
        echo json_encode($aReturnData);
    }


    // Function : ค้นหารายละเอียดเอกสาร [ Step 3]
    // Creator  : 29/03/2022 Wasin
    public function FSoCRPPFindinghBillPoint3(){
        $tAGNCode   = $this->input->post('tAGNCode');
        $tBCHCode   = $this->input->post('tBCHCode');
        $tRPPDocNo  = $this->input->post('tRPPDocNo');
        $tRPPStaApv = $this->input->post('tRPPStaApv');
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Data Where Document
        $aData      = [
            'nLangEdit'     => $nLangEdit,
            'tAGNCode'      => $tAGNCode,
            'tBCHCode'      => $tBCHCode,
            'tDocNo'        => $tRPPDocNo,
            'tDocKey'       => 'TACTPpDTStep3',
            'tPdtCode'      => $this->input->post('tPdtCode'),
            'tSessionID'    => $this->session->userdata('tSesSessionID')
        ];
        $aDataRCV   = $this->Receiptpurchasepmt_model->FSaMRPPGetDataRCV($aData);
        $aList      = $this->Receiptpurchasepmt_model->FSaMRPPListStep1Point3($aData);
        $aGenTable  = array(
            'aAlwEvent' => FCNaHCheckAlwFunc('docRPP/0/0'),
            'aDataRCV'  => $aDataRCV,
            'aDataList' => $aList
        );
        $tViewDataTable = $this->load->view('document/receiptpurchasepmt/step_form/wRPPStep1Point3Datatable', $aGenTable ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // Function : อัพเดตและคำนวณ หักภาษี ณ ที่จ่าย [ Step 3]
    // Creator  : 07/04/2022 Wasin
    public function FSoCRPPEventUpdWhTaxHD(){
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataWhere         = [
            'tAGNCode'      => $this->input->post('tAGNCode'),
            'tBCHCode'      => $this->input->post('tBCHCode'),
            'tDocNo'        => $this->input->post('tRPPDocNo'),
            'tDocKey'       => 'TACTPpDTStep3',
            'tSessionID'    => $this->session->userdata('tSesSessionID')
        ];
        $aDataUpd           = [
            'FTXtdDocNoRef' => $this->input->post('tWhtDocNo'),
            'FCXtdChg'      => $this->input->post('tWhtTotal'),
        ];
        $aDataUpdWhTaxHD    = $this->Receiptpurchasepmt_model->FSoMRPPEventUpdWhTaxHD($aDataWhere,$aDataUpd);
        if($aDataUpdWhTaxHD['rtCode'] == '1'){
            $aReturnData    = [
                'cAmtB4DisChg'  => number_format($aDataUpdWhTaxHD['raItems']['FCXtdAmtB4DisChg'],$nOptDecimalShow),
                'cChgWhtaxHD'   => number_format($aDataUpdWhTaxHD['raItems']['FCXtdChg'],$nOptDecimalShow),
                'cNetAfHD'      => number_format($aDataUpdWhTaxHD['raItems']['FCXtdNetAfHD'],$nOptDecimalShow),
                'nStaEvent'     => '1',
                'tStaMessg'     => 'Success'
            ];
        }else{
            $aReturnData    = [
                'nStaEvent' => '905',
                'tStaMessg' => 'Error Update Tax HD'
            ];
        }
        echo json_encode($aReturnData);
    }

    // Function : เพิ่ม Input ประเภทการชำระเงิน
    // Creator  : 29/03/2022 Wasin
    public function FSvCRPPStep1Point3InputADDRCV(){
        $aDataWhere = [
            'tAgnCode'      => $this->input->post('tAGNCode'),
            'tBchCode'      => $this->input->post('tBCHCode'),
            'tRPPDocNo'     => $this->input->post('tRPPDocNo'),
            'tRPPRcvCode'   => $this->input->post('tRPPRcvCode'),
            'tRPPRcvName'   => $this->input->post('tRPPRcvName'),
            'tRcvSeq'       => $this->input->post('tRcvSeq'),
        ];
        $tViewDataTable = $this->load->view('document/receiptpurchasepmt/input/wRPPInputRcv', $aDataWhere ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }



    // =============================================================== Event  Save And Appove Document ===============================================================

    // Function : เช็คข้อมูลใน DT Temp
    // Creator  : 29/03/2022 Wasin
    public function FSoCRPPChkHavePdtForDocDTTemp(){
        try {
            $tRPPDocNo      = $this->input->post("ptRPPDocNo");
            $tRPPSessionID  = $this->session->userdata('tSesSessionID');
            $aDataWhere     = [
                'FTXphDocNo'    => $tRPPDocNo,
                'FTXthDocKey'   => 'TAPTPiDT',
                'FTSessionID'   => $tRPPSessionID
            ];
            print_r($aDataWhere);
            // $nCountPdtInDocDTTemp   = $this->Receiptpurchasepmt_model->FSnMRPPChkPdtInDocDTTemp($aDataWhere);


        } catch (Exception $Error) {
            $aReturnData    = [
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage()
            ];
        }
    }















}
