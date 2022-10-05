<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cTransferBchOut extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('document/transfer_branch_out/mTransferBchOut');
        $this->load->model('document/transfer_branch_out/mTransferBchOutPdt');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('authen/login/mLogin');

        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nBrowseType, $tBrowseOption)
    {
        //เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
        $aCookieMenuCode = array(
            'name'	=> 'tMenuCode',
            'value' => json_encode('TXO007'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuCode);

        $aCookieMenuName = array(
            'name'	=> 'tMenuName',
            'value' => json_encode('ใบจ่ายโอน - สาขา'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuName);
        //end

        $aParams=array(
            'tDocNo' => $this->input->post('tDocNo'),
            'tBchCode' => $this->input->post('tBchCode'),
            'tAgnCode' => $this->input->post('tAgnCode'),
        );
        $aData['nBrowseType'] = $nBrowseType;
        $aData['tBrowseOption'] = $tBrowseOption;
        $aData['aAlwEvent'] = FCNaHCheckAlwFunc('docTransferBchOut/0/0');
        $aData['vBtnSave'] = FCNaHBtnSaveActiveHTML('docTransferBchOut/0/0');
        $aData['nOptDecimalShow'] = get_cookie('tOptDecimalShow');
        $aData['nOptDecimalSave'] = get_cookie('tOptDecimalSave');
        $aData['aParams'] = $aParams;
        $this->load->view('document/transfer_branch_out/wTransferBchOut', $aData);
    }

    /**
     * Functionality : Main Page List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : List Page
     * Return Type : View
     */
    public function FSxCTransferBchOutList()
    {
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aData = array(
            'FTBchCode'    => $this->session->userdata("tSesUsrBchCode"),
            'FTShpCode'    => '',
            'nPage' => 1,
            'nRow' => 20,
            'FNLngID' => $nLangEdit,
            'tSearchAll' => ''
        );

        $aBchData = $this->mBranch->FSnMBCHList($aData);
        $aShpData = $this->mShop->FSaMSHPList($aData);
        $aDataMaster = array(
            'aBchData' => $aBchData,
            'aShpData' => $aShpData
        );

        $this->load->view('document/transfer_branch_out/wTransferBchOutList', $aDataMaster);
    }

    /**
     * Functionality : Get HD Table List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : HD Table List
     * Return Type : View
     */
    public function FSxCTransferBchOutDataTable()
    {
        $tAdvanceSearchData = $this->input->post('oAdvanceSearch');
        $nPage = $this->input->post('nPageCurrent');
        $aAlwEvent = FCNaHCheckAlwFunc('docTransferBchOut/0/0');
        $nOptDecimalShow = get_cookie('tOptDecimalShow');



        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }

        $nLangEdit = $this->session->userdata("tLangEdit");
        $aData = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => json_decode($tAdvanceSearchData, true)
        );

        $aResList = $this->mTransferBchOut->FSaMHDList($aData);
        $aGenTable = array(
            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'nOptDecimalShow' => $nOptDecimalShow
        );

        $this->load->view('document/transfer_branch_out/wTransferBchOutDatatable', $aGenTable);
    }

    /**
     * Functionality : Add Page
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Add Page
     * Return Type : View
     */
    public function FSxCTransferBchOutAddPage()
    {
        $tUserSessionID = $this->session->userdata('tSesSessionID');
        $nOptDecimalShow = get_cookie('tOptDecimalShow');
        $nOptDocSave = get_cookie('tOptDocSave');
        $nOptScanSku = get_cookie('tOptScanSku');

        $aClearInTmpParams = [
            'tUserSessionID' => $tUserSessionID,
            'tDocKey' => 'TCNTPdtTboHD'
        ];
        $this->mTransferBchOut->FSxMClearInTmp($aClearInTmpParams);

        $aDataAdd = array(
            'aResult'           =>  array('rtCode' => '99'),
            'aResultOrdDT'      =>  array('rtCode' => '99'),
            'nOptDecimalShow'   =>  $nOptDecimalShow,
            'nOptScanSku'       =>  $nOptScanSku,
            'nOptDocSave'       =>  $nOptDocSave,
            // 'tBchCompCode'      =>  FCNtGetBchInComp(),
            // 'tBchCompName'      =>  FCNtGetBchNameInComp(),
            'nStaWasteWAH'      => FCNbIsGetRoleWasteWAH()
        );
        $this->load->view('document/transfer_branch_out/wTransferBchOutPageadd', $aDataAdd);
    }

    /**
     * Functionality : Add Event
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCTransferBchOutAddEvent()
    {
        try {
            $aDataDocument  = $this->input->post();
            $tUserSessionID = $this->session->userdata('tSesSessionID');
            $tUserLoginCode = $this->session->userdata('tSesUsername');
            $tDocDate = $this->input->post('oetTransferBchOutDocDate') . " " . $this->input->post('oetTransferBchOutDocTime');
            $tBchCode = $this->input->post('oetTransferBchOutBchCode');

            $aEndOfBillParams = [
                'tSplVatType' => '2', // ภาษีรวมใน
                'tDocNo' => 'TBODOCTEMP',
                'tDocKey' => 'TCNTPdtTboHD',
                'nLngID' => FCNaHGetLangEdit(),
                'tSesSessionID' => $tUserSessionID,
                'tBchCode' => $tBchCode
            ];
            $aEndOfBillCal = FCNaDOCEndOfBillCal($aEndOfBillParams);

            $aDataMaster = array(
                'tIsAutoGenCode' => $this->input->post('ocbTransferBchOutAutoGenCode'),
                'FTBchCode' => $tBchCode, // สาขาสร้าง
                'FTXthDocNo' => $this->input->post('oetTransferBchOutDocNo'), // เลขที่เอกสาร  XXYYMM-1234567
                'FDXthDocDate' => $tDocDate, // วันที่/เวลา เอกสาร dd/mm/yyyy H:mm:ss
                'FTXthVATInOrEx' => $this->input->post(''), // ภาษีมูลค่าเพิ่ม 1:รวมใน, 2:แยกนอก
                'FTDptCode' => $this->input->post(''), // แผนก
                'FTXthBchFrm' => $this->input->post('oetTransferBchOutXthBchFrmCode'), // รหัสสาขาต้นทาง
                'FTXthBchTo' => $this->input->post('oetTransferBchOutXthBchToCode'), // รหัสสาขาปลายทาง
                'FTXthMerchantFrm' => $this->input->post('oetTransferBchOutXthMerchantFrmCode'), // รหัสตัวแทน/เจ้าของดำเนินการ(ต้นทาง)
                'FTXthMerchantTo' => $this->input->post(''), // รหัสตัวแทน/เจ้าของดำเนินการ(ปลายทาง)
                'FTXthShopFrm' => $this->input->post('oetTransferBchOutXthShopFrmCode'), // ร้านค้า(ต้นทาง)
                'FTXthShopTo' => $this->input->post(''), // ร้านค้า(ปลายทาง)
                'FTXthWhFrm' => $this->input->post('oetTransferBchOutXthWhFrmCode'), // รหัสคลัง(ต้นทาง)
                'FTXthWhTo' => $this->input->post('oetTransferBchOutXthWhToCode'), // รหัสคลัง(ปลายทาง)
                'FTUsrCode' => $tUserLoginCode, // พนักงาน Key
                'FTSpnCode' => '', // พนักงานขาย
                'FTXthApvCode' => '', // ผู้อนุมัติ
                'FNXthDocPrint' => 0, // จำนวนครั้งที่พิมพ์
                'FCXthTotal' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดรวมก่อนลด
                'FCXthVat' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdVat'])), // ยอดภาษี
                'FCXthVatable' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดแยกภาษี
                'FTXthRmk' => $this->input->post('otaTransferBchOutXthRmk'), // หมายเหตุ
                'FTXthStaDoc' => '1', // สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                'FTXthStaApv' => '', // สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
                'FTXthStaPrcStk' => '', // สถานะ ประมวลผลสต็อค ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                'FTXthStaDelMQ' => '', // สถานะลบ MQ ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                'FNXthStaDocAct' => ($this->input->post('ocbTransferBchOutXthStaDocAct') == "1") ? 1 : 0, // สถานะ เคลื่อนไหว 0:NonActive, 1:Active
                'FNXthStaRef' => intval($this->input->post('ostTransferBchOutXthStaRef')), // สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว
                'FTRsnCode' => $this->input->post('oetTransferBchOutRsnCode'), // รหัสเหตุผล
                // การขนส่ง(TCNTPdtTboHDRef)
                'FTXthCtrName' => $this->input->post('oetTransferBchOutXthCtrName'), // ชื่อผู้ตืดต่อ
                'FDXthTnfDate' => empty($this->input->post('oetTransferBchOutXthTnfDate')) ? NULL : $this->input->post('oetTransferBchOutXthTnfDate'), // วันที่ส่งของ
                'FTXthRefTnfID' => $this->input->post('oetTransferBchOutXthRefTnfID'), // อ้างอิง เลขที่ ใบขนส่ง
                'FTXthRefVehID' => $this->input->post('oetTransferBchOutXthRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                'FTXthQtyAndTypeUnit' => $this->input->post('oetTransferBchOutXthQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                'FNXthShipAdd' => 0, // อ้างอิง ที่อยู่ ส่งของ null หรือ 0 ไม่กำหนด
                'FTViaCode' => $this->input->post('oetTransferBchOutShipViaCode'), // รหัสการขนส่ง
                'FDLastUpdOn' => date('Y-m-d H:i:s'), // วันที่ปรับปรุงรายการล่าสุด
                'FTLastUpdBy' => $tUserLoginCode, // ผู้ปรับปรุงรายการล่าสุด
                'FDCreateOn' => date('Y-m-d H:i:s'), // วันที่สร้างรายการ
                'FTCreateBy' => $tUserLoginCode, // ผู้สร้างรายการ
            );

            $this->db->trans_begin();

            // Setup Doc No.
            if ($aDataMaster['tIsAutoGenCode'] == '1') { // Check Auto Gen Reason Code?
                // Call Auto Gencode Helper
                $aStoreParam = array(
                    "tTblName" => 'TCNTPdtTboHD',
                    "tDocType" => 6,
                    "tBchCode" => $aDataMaster["FTBchCode"],
                    "tShpCode" => "",
                    "tPosCode" => "",
                    "dDocDate" => date("Y-m-d")
                );
                $aAutogen = FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTXthDocNo'] = $aAutogen[0]["FTXxhDocNo"];

            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
            if($aDataDocument['oetTransferBchOutXthRefInt'] != '' || $aDataDocument['oetTransferBchOutXthRefIntOld'] != ''){

                //1: อ้างอิงถึง(ภายใน) => ใบรับของ
                $aDataWhereDocRef_Type1 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataMaster['FTXthDocNo'],
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $aDataDocument['oetTransferBchOutXthRefInt'],
                    'FTXshRefKey'       => 'TR',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefIntDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtTboHDDocRef',$aDataWhereDocRef_Type1);

                //2:ถูกอ้างอิง(ภายใน) => ใบจ่ายโอน - สาขา
                $aDataWhereDocRef_Type2 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataDocument['oetTransferBchOutXthRefInt'],
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataMaster['FTXthDocNo'],
                    'FTXshRefKey'       => 'BS',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefIntDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtReqBchHDDocRef',$aDataWhereDocRef_Type2);
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายนอก ต้องกลับไปอัพเดท
            if($aDataDocument['oetTransferBchOutXthRefExt'] != '' ){

                //3: อ้างอิง ภายนอก => ใบวางบิล
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataMaster['FTXthDocNo'],
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetTransferBchOutXthRefExt'],
                    'FTXshRefKey'       => 'BillNote',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefExtDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtTboHDDocRef',$aDataWhereDocRef_Type3);
            }


            $this->mTransferBchOut->FSaMAddUpdateHD($aDataMaster);
            $this->mTransferBchOut->FSaMAddUpdateHDRef($aDataMaster);

            $aUpdateDocNoInTmpParams = [
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tDocKey' => 'TCNTPdtTboHD',
                'tUserSessionID' => $tUserSessionID
            ];
            $this->mTransferBchOut->FSaMUpdateDocNoInTmp($aUpdateDocNoInTmpParams); // Update DocNo ในตาราง Doctemp

            $aTempToDTParams = [
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tBchCode' => $aDataMaster['FTBchCode'],
                'tDocKey' => 'TCNTPdtTboHD',
                'tUserSessionID' => $tUserSessionID,
                'tUserLoginCode' => $tUserLoginCode
            ];
            $this->mTransferBchOut->FSaMTempToDT($aTempToDTParams); // คัดลอกข้อมูลจาก Temp to DT

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg' => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tEventName' => 'บันทึกใบจ่ายโอน - สาขา',
                    'nLogCode' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => '',
                    'FTXthStaDoc'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataMaster['FTXthDocNo'],
                    'nStaEvent'    => '1',
                    'tStaMessg' => 'Success Add Document.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tEventName' => 'บันทึกใบจ่ายโอน - สาขา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => '',
                    'FTXthStaDoc'   => ''
                );
            }
            
        } catch (Exception $Error) {
            $aReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tEventName' => 'บันทึกใบจ่ายโอน - สาขา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => '',
                'FTXthStaDoc'   => ''
            );
        }

        FSoCCallLogMQ($aReturn);
        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Edit Page
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Edit Page
     * Return Type : View
     */
    public function FSvCTransferBchOutEditPage(){
        $tDocNo             = $this->input->post('tDocNo');
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tUserSessionID     = $this->session->userdata("tSesSessionID");
        $aAlwEvent          = FCNaHCheckAlwFunc('docTransferBchOut/0/0'); // Access Control
        // Get Option Show Decimal
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        // Get Option Scan SKU
        $nOptDocSave        = get_cookie('tOptDocSave');
        //Get Option Scan SKU
        $nOptScanSku        = get_cookie('tOptScanSku');
        $aClearInTmpParams = [
            'tUserSessionID' => $tUserSessionID,
            'tDocKey' => 'TCNTPdtTboHD'
        ];
        $this->mTransferBchOut->FSxMClearInTmp($aClearInTmpParams);
        // Get Data
        $aGetHDParams = array(
            'tDocNo' => $tDocNo,
            'nLngID' => $nLangEdit,
            'tDocKey' => 'TCNTPdtTboHD',
        );
        $aResult = $this->mTransferBchOut->FSaMGetHD($aGetHDParams); // Data TCNTPdtTboHD
        $aDTToTempParams = [
            'tDocNo' => $tDocNo,
            'tDocKey' => 'TCNTPdtTboHD',
            'tBchCode' => isset($aResult['raItems']['FTBchCode']) ? $aResult['raItems']['FTBchCode'] : '',
            'tUserSessionID' => $tUserSessionID,
            'nLngID' => $nLangEdit
        ];
        $this->mTransferBchOut->FSaMDTToTemp($aDTToTempParams);
        $aDataEdit = array(
            'nOptDecimalShow'   => $nOptDecimalShow,
            'nOptDocSave'       => $nOptDocSave,
            'nOptScanSku'       => $nOptScanSku,
            'aResult'           => $aResult,
            'aAlwEvent'         => $aAlwEvent,
            'nStaWasteWAH'      => FCNbIsGetRoleWasteWAH()
        );
        $tViewPageEdit = $this->load->view('document/transfer_branch_out/wTransferBchOutPageadd', $aDataEdit,true);
        $aReturnData = array(
            'tViewPageEdit'      => $tViewPageEdit,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Call Page Success',
            //เพิ่มใหม่
            'tLogType'          => 'INFO',
            'tDocNo'            => $tDocNo,
            'tEventName'        => 'เรียกดูเอกสารใบจ่ายโอน - สาขา',
            'nLogCode'          => '001',
            'nLogLevel'         => '',
            'FTXphUsrApv'       => $aResult['raItems']['FTXthApvCode'],
            'FTXthStaApv'       => $aResult['raItems']['FTXthStaApv'],
            'FTXthStaDoc'       => $aResult['raItems']['FTXthStaDoc'],
            'nStaDocRef'        => $aResult['raItems']['FTXshRefType'],
        );
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    /**
     * Functionality : Edit Event
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCTransferBchOutEditEvent()
    {
        try {


            $aDataDocument          = $this->input->post();
            if ($aDataDocument['ohdTransferBchOutStaApv'] == 1 || $aDataDocument['ohdTransferBchOutStaDoc'] == 3) { //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว
                // Array Data update
                $tTWODocNo              = (isset($aDataDocument['oetTransferBchOutDocNo'])) ? $aDataDocument['oetTransferBchOutDocNo'] : '';
                $aDataMaster = array(
                    'FTBchCode'             => $aDataDocument['oetTransferBchOutBchCode'],
                    'FTXthDocNo'            => $tTWODocNo,
                    'FTXthRmk'              => $aDataDocument['otaTransferBchOutXthRmk'],
                );
                $this->db->trans_begin();
                // [Update] update หมายเหตุ
                $this->mTransferBchOut->FSaMHDUpdateRmk($aDataMaster);
            } else { //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ


                $tUserSessionID = $this->session->userdata('tSesSessionID');
                $tUserLoginCode = $this->session->userdata('tSesUsername');
                $tDocDate = $this->input->post('oetTransferBchOutDocDate') . " " . $this->input->post('oetTransferBchOutDocTime');
                $tUserLevel = $this->session->userdata('tSesUsrLevel');
                $tBchCode = $this->input->post('oetTransferBchOutBchCode');

                $aEndOfBillParams = [
                    'tSplVatType' => '2', // ภาษีรวมใน
                    'tDocNo' => 'TBODOCTEMP',
                    'tDocKey' => 'TCNTPdtTboHD',
                    'nLngID' => FCNaHGetLangEdit(),
                    'tSesSessionID' => $tUserSessionID,
                    'tBchCode' => $tBchCode
                ];
                $aEndOfBillCal = FCNaDOCEndOfBillCal($aEndOfBillParams);

                $aDataMaster = array(
                    'tIsAutoGenCode' => $this->input->post('ocbTransferBchOutAutoGenCode'),
                    'FTBchCode' => $tBchCode, // สาขาสร้าง
                    'FTXthDocNo' => $this->input->post('oetTransferBchOutDocNo'), // เลขที่เอกสาร  XXYYMM-1234567
                    'FDXthDocDate' => $tDocDate, // วันที่/เวลา เอกสาร dd/mm/yyyy H:mm:ss
                    'FTXthVATInOrEx' => $this->input->post(''), // ภาษีมูลค่าเพิ่ม 1:รวมใน, 2:แยกนอก
                    'FTDptCode' => $this->input->post(''), // แผนก
                    'FTXthBchFrm' => $this->input->post('oetTransferBchOutXthBchFrmCode'), // รหัสสาขาต้นทาง
                    'FTXthBchTo' => $this->input->post('oetTransferBchOutXthBchToCode'), // รหัสสาขาปลายทาง
                    'FTXthMerchantFrm' => $this->input->post('oetTransferBchOutXthMerchantFrmCode'), // รหัสตัวแทน/เจ้าของดำเนินการ(ต้นทาง)
                    'FTXthMerchantTo' => $this->input->post(''), // รหัสตัวแทน/เจ้าของดำเนินการ(ปลายทาง)
                    'FTXthShopFrm' => $this->input->post('oetTransferBchOutXthShopFrmCode'), // ร้านค้า(ต้นทาง)
                    'FTXthShopTo' => $this->input->post(''), // ร้านค้า(ปลายทาง)
                    'FTXthWhFrm' => $this->input->post('oetTransferBchOutXthWhFrmCode'), // รหัสคลัง(ต้นทาง)
                    'FTXthWhTo' => $this->input->post('oetTransferBchOutXthWhToCode'), // รหัสคลัง(ปลายทาง)
                    'FTUsrCode' => $tUserLoginCode, // พนักงาน Key
                    'FTSpnCode' => '', // พนักงานขาย
                    'FTXthApvCode' => '', // ผู้อนุมัติ
                    'FNXthDocPrint' => 0, // จำนวนครั้งที่พิมพ์
                    'FCXthTotal' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดรวมก่อนลด
                    'FCXthVat' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdVat'])), // ยอดภาษี
                    'FCXthVatable' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดแยกภาษี
                    'FTXthRmk' => $this->input->post('otaTransferBchOutXthRmk'), // หมายเหตุ
                    'FTXthStaDoc' => '1', // สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                    'FTXthStaApv' => '', // สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
                    'FTXthStaPrcStk' => '', // สถานะ ประมวลผลสต็อค ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                    'FTXthStaDelMQ' => '', // สถานะลบ MQ ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                    'FNXthStaDocAct' => ($this->input->post('ocbTransferBchOutXthStaDocAct') == "1") ? 1 : 0, // สถานะ เคลื่อนไหว 0:NonActive, 1:Active
                    'FNXthStaRef' => intval($this->input->post('ostTransferBchOutXthStaRef')), // สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว
                    'FTRsnCode' => $this->input->post('oetTransferBchOutRsnCode'), // รหัสเหตุผล

                    // การขนส่ง(TCNTPdtTboHDRef)
                    'FTXthCtrName' => $this->input->post('oetTransferBchOutXthCtrName'), // ชื่อผู้ตืดต่อ
                    'FDXthTnfDate' => empty($this->input->post('oetTransferBchOutXthTnfDate')) ? NULL : $this->input->post('oetTransferBchOutXthTnfDate'), // วันที่ส่งของ
                    'FTXthRefTnfID' => $this->input->post('oetTransferBchOutXthRefTnfID'), // อ้างอิง เลขที่ ใบขนส่ง
                    'FTXthRefVehID' => $this->input->post('oetTransferBchOutXthRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                    'FTXthQtyAndTypeUnit' => $this->input->post('oetTransferBchOutXthQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                    'FNXthShipAdd' => 0, // อ้างอิง ที่อยู่ ส่งของ null หรือ 0 ไม่กำหนด
                    'FTViaCode' => $this->input->post('oetTransferBchOutShipViaCode'), // รหัสการขนส่ง

                    'FDLastUpdOn' => date('Y-m-d H:i:s'), // วันที่ปรับปรุงรายการล่าสุด
                    'FTLastUpdBy' => $tUserLoginCode, // ผู้ปรับปรุงรายการล่าสุด
                    'FDCreateOn' => date('Y-m-d H:i:s'), // วันที่สร้างรายการ
                    'FTCreateBy' => $tUserLoginCode, // ผู้สร้างรายการ
                );

                $this->db->trans_begin();

                 // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
            if($aDataDocument['oetTransferBchOutXthRefInt'] != '' || $aDataDocument['oetTransferBchOutXthRefIntOld'] != ''){

                //1: อ้างอิงถึง(ภายใน) => ใบรับของ
                $aDataWhereDocRef_Type1 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataMaster['FTXthDocNo'],
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $aDataDocument['oetTransferBchOutXthRefInt'],
                    'FTXshRefKey'       => 'TR',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefIntDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtTboHDDocRef',$aDataWhereDocRef_Type1);

                //2:ถูกอ้างอิง(ภายใน) => ใบจ่ายโอน - สาขา
                $aDataWhereDocRef_Type2 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataDocument['oetTransferBchOutXthRefInt'],
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataMaster['FTXthDocNo'],
                    'FTXshRefKey'       => 'BS',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefIntDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtReqBchHDDocRef',$aDataWhereDocRef_Type2);
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายนอก ต้องกลับไปอัพเดท
            if($aDataDocument['oetTransferBchOutXthRefExt'] != '' ){

                //3: อ้างอิง ภายนอก => ใบวางบิล
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => ' ',
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $aDataMaster['FTXthDocNo'],
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetTransferBchOutXthRefExt'],
                    'FTXshRefKey'       => 'BillNote',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetTransferBchOutXthRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetTransferBchOutXthRefExtDate'])) : NULL
                );
                $this->mTransferBchOut->FSxMBSUpdateRef('TCNTPdtTboHDDocRef',$aDataWhereDocRef_Type3);
            }

                $this->mTransferBchOut->FSaMAddUpdateHD($aDataMaster);
                $this->mTransferBchOut->FSaMAddUpdateHDRef($aDataMaster);

                $aUpdateDocNoInTmpParams = [
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tDocKey' => 'TCNTPdtTboHD',
                    'tUserSessionID' => $tUserSessionID
                ];
                $this->mTransferBchOut->FSaMUpdateDocNoInTmp($aUpdateDocNoInTmpParams); // Update DocNo ในตาราง Doctemp

                $aTempToDTParams = [
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tBchCode' => $aDataMaster['FTBchCode'],
                    'tDocKey' => 'TCNTPdtTboHD',
                    'tUserSessionID' => $tUserSessionID,
                    'tUserLoginCode' => $tUserLoginCode
                ];
                $this->mTransferBchOut->FSaMTempToDT($aTempToDTParams); // คัดลอกข้อมูลจาก Temp to DT

            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg' => "Error Unsucess Edit Document.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบจ่ายโอน - สาขา',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataMaster['FTXthDocNo'],
                    'nStaEvent'    => '1',
                    'tStaMessg' => 'Success Edit Document.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบจ่ายโอน - สาขา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tEventName' => 'แก้ไขและบันทึกใบจ่ายโอน - สาขา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aReturn);
        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Check Doc No. Duplicate
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : String
     */
    public function FStCTransferBchOutUniqueValidate()
    {
        $aStatus = ['bStatus' => false];

        if ($this->input->is_ajax_request()) { // Request check
            $tTransferBchOutDocCode = $this->input->post('tTransferBchOutCode');
            $bIsDocNoDup = $this->mTransferBchOut->FSbMCheckDuplicate($tTransferBchOutDocCode);

            if ($bIsDocNoDup) { // If have record
                $aStatus['bStatus'] = true;
            }
        } else {
            echo 'Method Not Allowed';
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    //Cancel Document
    public function FStCTransferBchOutDocCancel()
    {
        $tDocNo     = $this->input->post('tDocNo');
        $tUserLoginCode = $this->session->userdata('tSesUsername');
        $tBchCode = $this->input->post('tBchCode');
        $tStaApv = $this->input->post('tStaApv');
        
        $aDocCancelParams   = array(
            'tDocNo'    => $tDocNo,
        );

        $aStaCancel = $this->mTransferBchOut->FSaMDocCancel($aDocCancelParams);
       
        if ($aStaCancel['rtCode'] == 1) {
            $this->db->trans_commit();

            if($tStaApv == 1){
                $aMQParams = [
                    "queueName" => "TNFBRANCHOUT",
                    "params" => [
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocNo,
                        "ptDocType"     => "6",
                        "ptUser"        => $tUserLoginCode,
                    ]
                ];
        
                $aStaReturn = FCNxCallRabbitMQ($aMQParams);
            }

            $aCancel = array(
                'nSta' => 1,
                'tStaMessg' => "Update Status Document Cancel Success.",
                'tLogType'      => 'INFO',
                'tDocNo'        => $tDocNo,
                'tEventName'    => 'ยกเลิกใบจ่ายโอน - สาขา',
                'nLogCode'      => '001',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => ''
            );
        } else {
            $this->db->trans_rollback();
            $aCancel = array(
                'nSta' => 2,
                'tStaMessg' => "Error Cannot Update Status Cancel Document.",
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDocNo,
                'tEventName'    => 'ยกเลิกใบจ่ายโอน - สาขา',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }

        FSoCCallLogMQ($aCancel);
        echo json_encode($aCancel);
    }

    //Approve Document
    public function FStCTransferBchOutDocApprove()
    {
        $tDocNo         = $this->input->post('tDocNo');
        $tUserLoginCode = $this->session->userdata('tSesUsername');
        $tBchCode       = $this->input->post('tBchCode');
        $this->db->trans_begin();

        try {
            $aMQParams = [
                "queueName" => "TNFBRANCHOUT",
                "params" => [
                    "ptBchCode"     => $tBchCode,
                    "ptDocNo"       => $tDocNo,
                    "ptDocType"     => "6",
                    "ptUser"        => $tUserLoginCode,
                ]
            ];
            $aStaReturn = FCNxCallRabbitMQ($aMQParams);

            if ($aStaReturn['rtCode'] == 1) {
                $aDocApproveParams = array(
                    'tDocNo' => $tDocNo,
                    'tApvCode' => $tUserLoginCode
                );
                $this->mTransferBchOut->FSaMDocApprove($aDocApproveParams);
        
                $aDataWhere = array(
                    'FTBchCode'         => $tBchCode,
                    'FTXshDocNo'        => $tDocNo,
                    'FTXthDocKey'       => 'TCNTPdtTboHD',
                    'FTSessionID'       => $this->session->userdata('tSesSessionID')
                );
                $this->mTransferBchOutPdt->FSxMTransferBchOutUpdatePdtStkPrcAll($aDataWhere,'1');

                $aDataGetDataHD     =   $this->mTransferBchOut->FSaMGetHD(array(
                    'tDocNo'       => $tDocNo,
                    'nLngID'       => $this->session->userdata("tLangEdit"),
                    'tDocKey'      => 'TCNTPdtTboHD',
                ));

                if($aDataGetDataHD['rtCode']=='1'){
                    $tNotiID = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXthDocNo']);
                    $aMQParamsNoti = [
                        "queueName"     => "CN_SendToNoti",
                        "tVhostType"    => "NOT",
                        "params"        => [
                            "oaTCNTNoti" => array(
                                "FNNotID"       => $tNotiID,
                                "FTNotCode"     => '00008',
                                "FTNotKey"      => 'TCNTPdtTboHD',
                                "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXthDocNo'],
                            ),
                            "oaTCNTNoti_L" => array(
                                0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 1,
                                    "FTNotDesc1"    => 'เอกสารใบจ่ายโอน #'.$aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' อนุมัติใบจ่ายโอนไปยังสาขา '.$aDataGetDataHD['raItems']['FTXthBchTo'],
                                ),
                                1 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 2,
                                    "FTNotDesc1"    => 'Transfer Branch Out #'.$aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Approve document To Branch Code '.$aDataGetDataHD['raItems']['FTXthBchTo'],
                                )
                            ),
                            "oaTCNTNotiAct" => array(
                                0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                    "FTNoaDesc"      => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' อนุมัติใบจ่ายโอนไปยังสาขา '.$aDataGetDataHD['raItems']['FTXthBchTo'],
                                    "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FNNoaUrlType"   =>  1,
                                    "FTNoaUrlRef"    => 'docTransferBchOut/2/0',
                                ),
                            ),
                            "oaTCNTNotiSpc" => array(
                                0 => array(
                                        "FNNotID"       => $tNotiID,
                                        "FTNotType"    => '1',
                                        "FTNotStaType" => '1',
                                        "FTAgnCode"    => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTBchName'],
                                ),
                                1 => array(
                                        "FNNotID"       => $tNotiID,
                                        "FTNotType"    => '2',
                                        "FTNotStaType" => '1',
                                        "FTAgnCode"    => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTXthBchFrm'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTXthBchFrmName'],
                                ),
                                2 => array(
                                        "FNNotID"       => $tNotiID,
                                        "FTNotType"    => '2',
                                        "FTNotStaType" => '1',
                                        "FTAgnCode"    => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTXthBchTo'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTXthBchToName'],
                                ),
                            ),
                            "ptUser"        => $this->session->userdata('tSesUsername'),
                        ]
                    ];
                    FCNxCallRabbitMQ($aMQParamsNoti);
                }
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg'     => "Approve Document Success",
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบจ่ายโอน - สาขา',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $tUserLoginCode
                );
            }else{
                $aReturn = array(
                    'nStaEvent' => '905',
                    'tStaMessg' => 'Connect Rabbit MQ Fail'.' '.$aStaReturn['rtDesc'],
                    'tLogType'      => 'EVENT',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบจ่ายโอน - สาขา',
                    'nLogCode'      => '905',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => $tUserLoginCode
                );
            }
        } catch (\ErrorException $err) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent' => '900',
                'tStaMessg' => language('common/main/main', 'tApproveFail'),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDocNo,
                'tEventName'    => 'อนุมัติใบจ่ายโอน - สาขา',
                'nLogCode'      => '500',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => $tUserLoginCode
            );
        }

        if ($aReturn['nStaEvent'] != 905) {
            //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
            FSoCCallLogMQ($aReturn); 
        }
        
        echo json_encode($aReturn);
        // $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Delete Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : String
     */
    public function FStTransferBchOutDeleteDoc()
    {

        $tDocNo = $this->input->post('tDocNo');

        $this->db->trans_begin();

        $aDelMasterParams = [
            'tDocNo' => trim($tDocNo)
        ];
        $this->mTransferBchOut->FSaMDelMaster($aDelMasterParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode' => '905',
                'tStaMessg' => 'Cannot Delete Item.',
                'tLogType' => 'ERROR',
                'tDocNo' => $tDocNo,
                'tEventName' => 'ลบใบจ่ายโอน - สาขา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'tStaMessg' => 'Delete Document Success',
                'tLogType' => 'INFO',
                'tDocNo' => $tDocNo,
                'tEventName' => 'ลบใบจ่ายโอน - สาขา',
                'nLogCode' => '001',
                'nLogLevel' => '',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aStatus);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    //Delete Multiple Document
    public function FStTransferBchOutDeleteMultiDoc(){
        $aDocNo = $this->input->post('aDocNo');

        $this->db->trans_begin();

        foreach ($aDocNo as $aItem) {
            $aDelMasterParams = [
                'tDocNo' => trim($aItem)
            ];
            $this->mTransferBchOut->FSaMDelMaster($aDelMasterParams);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode' => '905',
                'tStaMessg' => 'Cannot Delete Item.',
                'tLogType' => 'ERROR',
                'tDocNo' => $aDocNo,
                'tEventName' => 'ลบใบจ่ายโอน - สาขา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'tStaMessg' => 'Delete Document Success',
                'tLogType' => 'INFO',
                'tDocNo' => $aDocNo,
                'tEventName' => 'ลบใบจ่ายโอน - สาขา',
                'nLogCode' => '001',
                'nLogLevel' => '',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aStatus);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    //หาว่าสาขานี้ คลัง default คืออะไร
    public function FSoCTransferBchOutCheckWahouseInBCH(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $aItems     = $this->mTransferBchOut->FSaMCheckWahouseInBCH($tBCHCode);
        $aReturnData = array(
            'aItems' => $aItems
        );
        echo json_encode($aReturnData);
    }
}
