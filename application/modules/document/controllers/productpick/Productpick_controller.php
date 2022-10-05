<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productpick_controller extends MX_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('document/productpick/Productpick_model');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('authen/login/mLogin');
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE) {
            echo "ERROR XSS Filter";
        }
    }

    public function index($nBrowseType, $tBrowseOption){
        $aParams    = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
        $aData['aAlwEvent']         = FCNaHCheckAlwFunc('docPCK/0/0');
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('docPCK/0/0');
        $aData['nOptDecimalShow']   = get_cookie('tOptDecimalShow');
        $aData['nOptDecimalSave']   = get_cookie('tOptDecimalSave');
        $aData['aParams']           = $aParams;
        $this->load->view('document/productpick/wProductPick', $aData);
    }

    /**
     * Functionality : Main Page List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : List Page
     * Return Type : View
    */
    public function FSxCPCKList(){
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aData      = array(
            'FTBchCode'     => $this->session->userdata("tSesUsrBchCode"),
            'FTShpCode'     => '',
            'nPage'         => 1,
            'nRow'          => 20,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => ''
        );
        $aBchData   = $this->mBranch->FSnMBCHList($aData);
        $aShpData   = $this->mShop->FSaMSHPList($aData);
        $aDataMaster = array(
            'aBchData'  => $aBchData,
            'aShpData'  => $aShpData
        );
        $this->load->view('document/productpick/wProductPickList', $aDataMaster);
    }

    /**
     * Functionality : Get HD Table List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : HD Table List
     * Return Type : View
    */
    public function FSxCPCKDataTable(){
        $tAdvanceSearchData = $this->input->post('oAdvanceSearch');
        $nPage              = $this->input->post('nPageCurrent');
        $aAlwEvent          = FCNaHCheckAlwFunc('docPCK/0/0');
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aData      = array(
            'FNLngID'   => $nLangEdit,
            'nPage'     => $nPage,
            'nRow'      => 10,
            'aAdvanceSearch'    => json_decode($tAdvanceSearchData, true)
        );
        $aResList   = $this->Productpick_model->FSaMHDList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aResList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/productpick/wProductPickDatatable', $aGenTable);
    }

    /**
     * Functionality : Add Page
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Add Page
     * Return Type : View
     */
    public function FSxCPCKAddPage(){
        $tUserSessionID     = $this->session->userdata('tSesSessionID');
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        $nOptDocSave        = get_cookie('tOptDecimalSave');
        $nOptScanSku        = get_cookie('tOptScanSku');
        $aClearInTmpParams  = [
            'tUserSessionID'    => $tUserSessionID,
            'tDocKey'           => 'TCNTPdtPickHD'
        ];
        $this->Productpick_model->FSxMClearInTmp($aClearInTmpParams);
        $aDataAdd = array(
            'aResult'           =>  array('rtCode' => '99'),
            'aResultOrdDT'      =>  array('rtCode' => '99'),
            'nOptDecimalShow'   =>  $nOptDecimalShow,
            'nOptScanSku'       =>  $nOptScanSku,
            'nOptDocSave'       =>  $nOptDocSave,
            'tBchCompCode'      =>  FCNtGetBchInComp(),
            'tBchCompName'      =>  FCNtGetBchNameInComp(),
            'nStaWasteWAH'      =>  FCNbIsGetRoleWasteWAH()
        );
        $this->load->view('document/productpick/wProductPickPageadd', $aDataAdd);
    }

    /**
     * Functionality : Add Event
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCPCKAddEvent(){
        try {
            $aDataDocument  = $this->input->post();
            $tUserSessionID = $this->session->userdata('tSesSessionID');
            $tUserLoginCode = $this->session->userdata('tSesUsername');
            $tDocDate       = $this->input->post('oetPCKDocDate') . " " . $this->input->post('oetPCKDocTime');
            $tBchCode       = $this->input->post('oetPCKBchCode');
            $aDataMaster = array(
                'tIsAutoGenCode'    => $this->input->post('ocbPCKAutoGenCode'),
                'FTAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                'FTBchCode'         => $tBchCode, // สาขาสร้าง
                'FTXthDocNo'        => $this->input->post('oetPCKDocNo'), // เลขที่เอกสาร  XXYYMM-1234567
                'FDXthDocDate'      => $tDocDate, // วันที่/เวลา เอกสาร dd/mm/yyyy H:mm:ss
                'FTXthVATInOrEx'    => $this->input->post(''), // ภาษีมูลค่าเพิ่ม 1:รวมใน, 2:แยกนอก
                'FTDptCode'         => $this->input->post(''), // แผนก
                'FTXthBchFrm'       => $this->input->post('oetPCKXthBchFrmCode'), // รหัสสาขาต้นทาง
                'FTXthBchTo'        => $this->input->post('oetPCKXthBchToCode'), // รหัสสาขาปลายทาง
                'FTXthMerchantFrm'  => $this->input->post('oetPCKXthMerchantFrmCode'), // รหัสตัวแทน/เจ้าของดำเนินการ(ต้นทาง)
                'FTXthMerchantTo'   => $this->input->post(''), // รหัสตัวแทน/เจ้าของดำเนินการ(ปลายทาง)
                'FTXthShopFrm'      => $this->input->post('oetPCKXthShopFrmCode'), // ร้านค้า(ต้นทาง)
                'FTXthShopTo'       => $this->input->post(''), // ร้านค้า(ปลายทาง)
                'FTXthWhFrm'        => $this->input->post('oetPCKXthWhFrmCode'), // รหัสคลัง(ต้นทาง)
                'FTXthWhTo'         => $this->input->post('oetPCKXthWhToCode'), // รหัสคลัง(ปลายทาง)
                'FTUsrCode'         =>  $this->input->post('oetPCKUsrCode'),
                'FTSpnCode'         => '', // พนักงานขาย
                'FTXthApvCode'      => '', // ผู้อนุมัติ
                'FNXthDocPrint'     => 0, // จำนวนครั้งที่พิมพ์
                'FTXthRmk'          => $this->input->post('otaPCKXthRmk'), // หมายเหตุ
                'FTXthStaDoc'       => '1', // สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                'FTXthStaApv'       => '', // สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
                'FTXthStaPrcStk'    => '', // สถานะ ประมวลผลสต็อค ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                'FTXthStaDelMQ'     => '', // สถานะลบ MQ ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                'FNXthStaDocAct'    => ($this->input->post('ocbPCKXthStaDocAct') == "1") ? 1 : 0, // สถานะ เคลื่อนไหว 0:NonActive, 1:Active
                'FNXthStaRef'       => intval($this->input->post('ostPCKXthStaRef')), // สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว
                'FTRsnCode'         => $this->input->post('oetPCKRsnCode'), // รหัสเหตุผล
                // การขนส่ง(TCNTPdtTboHDRef)
                'FTXthCtrName'          => $this->input->post('oetPCKXthCtrName'), // ชื่อผู้ตืดต่อ
                'FDXthTnfDate'          => empty($this->input->post('oetPCKXthTnfDate')) ? NULL : $this->input->post('oetPCKXthTnfDate'), // วันที่ส่งของ
                'FTXthRefTnfID'         => $this->input->post('oetPCKXthRefTnfID'), // อ้างอิง เลขที่ ใบขนส่ง
                'FTXthRefVehID'         => $this->input->post('oetPCKXthRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                'FTXthQtyAndTypeUnit'   => $this->input->post('oetPCKXthQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                'FNXthShipAdd'          => 0, // อ้างอิง ที่อยู่ ส่งของ null หรือ 0 ไม่กำหนด
                'FTViaCode'             => $this->input->post('oetPCKShipViaCode'), // รหัสการขนส่ง
                'FDLastUpdOn'           => date('Y-m-d H:i:s'), // วันที่ปรับปรุงรายการล่าสุด
                'FTLastUpdBy'           => $tUserLoginCode, // ผู้ปรับปรุงรายการล่าสุด
                'FDCreateOn'            => date('Y-m-d H:i:s'), // วันที่สร้างรายการ
                'FTCreateBy'            => $tUserLoginCode, // ผู้สร้างรายการ,
                'FNXthDocType'          => 12
            );
            $this->db->trans_begin();
            // Setup Doc No.
            if ($aDataMaster['tIsAutoGenCode'] == '1') { // Check Auto Gen Reason Code?
                // Call Auto Gencode Helper
                $aStoreParam = array(
                    "tTblName" => 'TCNTPdtPickHD',
                    "tDocType" => 12,
                    "tBchCode" => $aDataMaster["FTBchCode"],
                    "tShpCode" => "",
                    "tPosCode" => "",
                    "dDocDate" => date("Y-m-d")
                );
                $aAutogen = FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTXthDocNo'] = $aAutogen[0]["FTXxhDocNo"];
            }
            $this->Productpick_model->FSaMAddUpdateHD($aDataMaster);
            $aDataWhere = [
                'FTBchCode'  => $aDataMaster['FTBchCode'],
                'FTXphDocNo' => $aDataMaster['FTXthDocNo'],
                'FTAgnCode' => $aDataDocument['oetPCKAgnCode'],
                'tTableHD' => 'TCNTPdtPickHD',
            ];
            // [Update] DocNo -> Temp
            $this->Productpick_model->FSxMPCKAddUpdateDocNoToTemp($aDataWhere);
            $aUpdateDocNoInTmpParams = [
                'tAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tDocKey' => 'TCNTPdtPickHD',
                'tUserSessionID' => $tUserSessionID
            ];
            $this->Productpick_model->FSaMUpdateDocNoInTmp($aUpdateDocNoInTmpParams); // Update DocNo ในตาราง Doctemp
            $aTempToDTParams = [
                'tAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                'tDocNo' => $aDataMaster['FTXthDocNo'],
                'tBchCode' => $aDataMaster['FTBchCode'],
                'tDocKey' => 'TCNTPdtPickHD',
                'tUserSessionID' => $tUserSessionID,
                'tUserLoginCode' => $tUserLoginCode
            ];
            $this->Productpick_model->FSaMTempToDT($aTempToDTParams); // คัดลอกข้อมูลจาก Temp to DT
            // [Move] Doc TCNTDocHDRefTmp 
            $this->Productpick_model->FSxMPCKMoveHDRefTmpToHDRef($aDataWhere);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataMaster['FTXthDocNo'],
                    'nStaEvent'    => '1',
                    'tStaMessg' => 'Success Add'
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality : Edit Page
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Edit Page
     * Return Type : View
     */
    public function FSvCPCKEditPage(){
        $tDocNo             = $this->input->post('tDocNo');
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tUserSessionID     = $this->session->userdata("tSesSessionID");
        $aAlwEvent          = FCNaHCheckAlwFunc('docPCK/0/0'); // Access Control
        // Get Option Show Decimal
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        // Get Option Scan SKU
        $nOptDocSave        = get_cookie('tOptDecimalSave');
        //Get Option Scan SKU
        $nOptScanSku        = get_cookie('tOptScanSku');
        $aClearInTmpParams  = [
            'tUserSessionID'    => $tUserSessionID,
            'tDocKey'           => 'TCNTPdtPickHD'
        ];
        $this->Productpick_model->FSxMClearInTmp($aClearInTmpParams);
        // Get Data
        $aGetHDParams   = array(
            'tDocNo'    => $tDocNo,
            'nLngID'    => $nLangEdit,
            'tDocKey'   => 'TCNTPdtPickHD',
        );
        $aResult    = $this->Productpick_model->FSaMGetHD($aGetHDParams); // Data TCNTPdtPickHD
        $aDTToTempParams = [
            'tDocNo'            => $tDocNo,
            'tDocKey'           => 'TCNTPdtPickHD',
            'tBchCode'          => isset($aResult['raItems']['FTBchCode']) ? $aResult['raItems']['FTBchCode'] : '',
            'tUserSessionID'    => $tUserSessionID,
            'nLngID'            => $nLangEdit
        ];
        $this->Productpick_model->FSaMPCKDTToTemp($aDTToTempParams);
        // Array Data Where Get
        $aDataWhere = array(
            'FTXthDocNo'    => $tDocNo,
        );
        // Move Data HDDocRef TO HDRefTemp
        $this->Productpick_model->FSxMPCKMoveHDRefToHDRefTemp($aDataWhere);
        $aGetDocRefCode = $this->Productpick_model->FSoMPCKGetDocRef($tDocNo);
        $tCstCode = $this->Productpick_model->FSoMPCKGetCstCode(@$aGetDocRefCode[0]);
        //ที่อยู่
        // $aGETCSTAddress  = FCNtGetAddressCustmerDefVersion($tCstCode, $nLangResort);
        $aGETCSTAddress  = $this->Productpick_model->FSoMPCKGetAddressCustmer(@$aGetDocRefCode[0]);
        //ชื่อลูกค้า
        $tCstName = $this->Productpick_model->FSoMPCKGetCstName($tCstCode);
        $aDataEdit = array(
            'nOptDecimalShow'   => $nOptDecimalShow,
            'nOptDocSave'       => $nOptDocSave,
            'nOptScanSku'       => $nOptScanSku,
            'aResult'           => $aResult,
            'aAlwEvent'         => $aAlwEvent,
            'tBchCompCode'      => FCNtGetBchInComp(),
            'tBchCompName'      => FCNtGetBchNameInComp(),
            'nStaWasteWAH'      => FCNbIsGetRoleWasteWAH(),
            'aCSTAddress'       => $aGETCSTAddress,
            'tCstName'          => $tCstName
        );
        $this->load->view('document/productpick/wProductPickPageadd', $aDataEdit);
    }

    /**
     * Functionality : Edit Event
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCPCKEditEvent()
    {
        try {


            $aDataDocument          = $this->input->post();
            if ($aDataDocument['ohdPCKStaApv'] == 1) { //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว
                // Array Data update
                $tTWODocNo              = (isset($aDataDocument['oetPCKDocNo'])) ? $aDataDocument['oetPCKDocNo'] : '';
                $aDataMaster = array(
                    'FTBchCode'             => $aDataDocument['oetPCKBchCode'],
                    'FTXthDocNo'            => $tTWODocNo,
                    'FTXthRmk'              => $aDataDocument['otaPCKXthRmk'],
                );
                $this->db->trans_begin();
                // [Update] update หมายเหตุ
                $this->Productpick_model->FSaMHDUpdateRmk($aDataMaster);
            } else { //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ


                $tUserSessionID = $this->session->userdata('tSesSessionID');
                $tUserLoginCode = $this->session->userdata('tSesUsername');
                $tDocDate = $this->input->post('oetPCKDocDate') . " " . $this->input->post('oetPCKDocTime');
                $tUserLevel = $this->session->userdata('tSesUsrLevel');
                $tBchCode = $this->input->post('oetPCKBchCode');

                $aDataMaster = array(
                    'tIsAutoGenCode' => $this->input->post('ocbPCKAutoGenCode'),
                    'FTAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                    'FTBchCode' => $tBchCode, // สาขาสร้าง
                    'FTXthDocNo' => $this->input->post('oetPCKDocNo'), // เลขที่เอกสาร  XXYYMM-1234567
                    'FDXthDocDate' => $tDocDate, // วันที่/เวลา เอกสาร dd/mm/yyyy H:mm:ss
                    'FTXthVATInOrEx' => $this->input->post(''), // ภาษีมูลค่าเพิ่ม 1:รวมใน, 2:แยกนอก
                    'FTDptCode' => $this->input->post(''), // แผนก
                    'FTXthBchFrm' => $this->input->post('oetPCKXthBchFrmCode'), // รหัสสาขาต้นทาง
                    'FTXthBchTo' => $this->input->post('oetPCKXthBchToCode'), // รหัสสาขาปลายทาง
                    'FTXthMerchantFrm' => $this->input->post('oetPCKXthMerchantFrmCode'), // รหัสตัวแทน/เจ้าของดำเนินการ(ต้นทาง)
                    'FTXthMerchantTo' => $this->input->post(''), // รหัสตัวแทน/เจ้าของดำเนินการ(ปลายทาง)
                    'FTXthShopFrm' => $this->input->post('oetPCKXthShopFrmCode'), // ร้านค้า(ต้นทาง)
                    'FTXthShopTo' => $this->input->post(''), // ร้านค้า(ปลายทาง)
                    'FTXthWhFrm' => $this->input->post('oetPCKXthWhFrmCode'), // รหัสคลัง(ต้นทาง)
                    'FTXthWhTo' => $this->input->post('oetPCKXthWhToCode'), // รหัสคลัง(ปลายทาง)
                    // 'FTUsrCode' => $tUserLoginCode, // พนักงาน Key
                    'FTUsrCode' =>  $this->input->post('oetPCKUsrCode'),
                    'FTSpnCode' => '', // พนักงานขาย
                    'FTXthApvCode' => '', // ผู้อนุมัติ
                    // 'FTXthRefExt' => $this->input->post('oetPCKXthRefExt'),
                    // 'FDXthRefExtDate' => empty($this->input->post('oetPCKXthRefExtDate')) ? NULL : $this->input->post('oetPCKXthRefExtDate'),
                    // 'FTXthRefInt' => $this->input->post('oetPCKXthRefInt'), 
                    // 'FDXthRefIntDate' => empty($this->input->post('oetPCKXthRefIntDate')) ? NULL : $this->input->post('oetPCKXthRefIntDate'),
                    'FNXthDocPrint' => 0, // จำนวนครั้งที่พิมพ์
                    // 'FCXthTotal' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดรวมก่อนลด
                    // 'FCXthVat' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdVat'])), // ยอดภาษี
                    // 'FCXthVatable' => floatval(str_replace(',', '', $aEndOfBillCal['cSumFCXtdNet'])), // ยอดแยกภาษี
                    'FTXthRmk' => $this->input->post('otaPCKXthRmk'), // หมายเหตุ
                    'FTXthStaDoc' => '1', // สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                    'FTXthStaApv' => '', // สถานะ อนุมัติ เอกสาร ว่าง:ยังไม่ทำ, 1:อนุมัติแล้ว
                    'FTXthStaPrcStk' => '', // สถานะ ประมวลผลสต็อค ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                    'FTXthStaDelMQ' => '', // สถานะลบ MQ ว่าง หรือ Null:ยังไม่ทำ, 1:ทำแล้ว
                    'FNXthStaDocAct' => ($this->input->post('ocbPCKXthStaDocAct') == "1") ? 1 : 0, // สถานะ เคลื่อนไหว 0:NonActive, 1:Active
                    'FNXthStaRef' => intval($this->input->post('ostPCKXthStaRef')), // สถานะ อ้างอิง 0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว
                    'FTRsnCode' => $this->input->post('oetPCKRsnCode'), // รหัสเหตุผล
                    // การขนส่ง(TCNTPdtTboHDRef)
                    'FTXthCtrName' => $this->input->post('oetPCKXthCtrName'), // ชื่อผู้ตืดต่อ
                    'FDXthTnfDate' => empty($this->input->post('oetPCKXthTnfDate')) ? NULL : $this->input->post('oetPCKXthTnfDate'), // วันที่ส่งของ
                    'FTXthRefTnfID' => $this->input->post('oetPCKXthRefTnfID'), // อ้างอิง เลขที่ ใบขนส่ง
                    'FTXthRefVehID' => $this->input->post('oetPCKXthRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                    'FTXthQtyAndTypeUnit' => $this->input->post('oetPCKXthQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                    'FNXthShipAdd' => 0, // อ้างอิง ที่อยู่ ส่งของ null หรือ 0 ไม่กำหนด
                    'FTViaCode' => $this->input->post('oetPCKShipViaCode'), // รหัสการขนส่ง
                    'FDLastUpdOn' => date('Y-m-d H:i:s'), // วันที่ปรับปรุงรายการล่าสุด
                    'FTLastUpdBy' => $tUserLoginCode, // ผู้ปรับปรุงรายการล่าสุด
                    'FDCreateOn' => date('Y-m-d H:i:s'), // วันที่สร้างรายการ
                    'FTCreateBy' => $tUserLoginCode, // ผู้สร้างรายการ,
                    'FNXthDocType' => 12
                );

                // print_r($aDataDocument['oetPCKAgnCode']); die();

                $this->db->trans_begin();



                $this->Productpick_model->FSaMAddUpdateHD($aDataMaster);
                // $this->Productpick_model->FSaMAddUpdateHDRef($aDataMaster);



                $aDataWhere = [
                    'FTBchCode'  => $aDataMaster['FTBchCode'],
                    'FTXphDocNo' => $aDataMaster['FTXthDocNo'],
                    'FTAgnCode' => $aDataDocument['oetPCKAgnCode'],
                    'tTableHD' => 'TCNTPdtPickHD',
                ];

                // [Update] DocNo -> Temp
                $this->Productpick_model->FSxMPCKAddUpdateDocNoToTemp($aDataWhere);

                $aUpdateDocNoInTmpParams = [
                    'tAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tDocKey' => 'TCNTPdtPickHD',
                    'tUserSessionID' => $tUserSessionID
                ];
                $this->Productpick_model->FSaMUpdateDocNoInTmp($aUpdateDocNoInTmpParams); // Update DocNo ในตาราง Doctemp

                $aTempToDTParams = [
                    'tAgnCode'         => $aDataDocument['oetPCKAgnCode'],
                    'tDocNo' => $aDataMaster['FTXthDocNo'],
                    'tBchCode' => $aDataMaster['FTBchCode'],
                    'tDocKey' => 'TCNTPdtPickHD',
                    'tUserSessionID' => $tUserSessionID,
                    'tUserLoginCode' => $tUserLoginCode
                ];
                $this->Productpick_model->FSaMTempToDT($aTempToDTParams); // คัดลอกข้อมูลจาก Temp to DT



                // [Move] Doc TCNTDocHDRefTmp 
                $this->Productpick_model->FSxMPCKMoveHDRefTmpToHDRef($aDataWhere);
            }



            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataMaster['FTXthDocNo'],
                    'nStaEvent'    => '1',
                    'tStaMessg' => 'Success Add'
                );
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality : Check Doc No. Duplicate
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : String
     */
    public function FStCPCKUniqueValidate()
    {
        $aStatus = ['bStatus' => false];

        if ($this->input->is_ajax_request()) { // Request check
            $tPCKDocCode = $this->input->post('tPCKCode');
            $bIsDocNoDup = $this->Productpick_model->FSbMCheckDuplicate($tPCKDocCode);

            if ($bIsDocNoDup) { // If have record
                $aStatus['bStatus'] = true;
            }
        } else {
            echo 'Method Not Allowed';
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    /**
     * Functionality : Cancel Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : String
     */
    public function FStCPCKDocCancel()
    {
        $tDocNo     = $this->input->post('tDocNo');
        $aDocCancelParams   = array(
            'tDocNo'    => $tDocNo,
        );
        $aStaCancel = $this->Productpick_model->FSaMPCKDocCancel($aDocCancelParams);
        if ($aStaCancel['rtCode'] == 1) {
            $this->db->trans_commit();
            $aCancel = array(
                'nSta' => 1,
                'tMsg' => "Cancel Success",
            );
        } else {
            $this->db->trans_rollback();
            $aCancel = array(
                'nSta' => 2,
                'tMsg' => "Cancel Fail",
            );
        }
        echo json_encode($aCancel);
    }

    /**
     * Functionality : Approve Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : Napat(Jame) 31/07/2020
     * Return : Status
     * Return Type : String
     */
    public function FStCPCKDocApprove()
    {
        $tDocNo  = $this->input->post('tDocNo');
        $tUserLoginCode = $this->session->userdata('tSesUsername');


        $aDocApproveParams = array(
            'tDocNo' => $tDocNo,
            'tApvCode' => $tUserLoginCode
        );
        $this->Productpick_model->FSaMPCKDocApprove($aDocApproveParams);
    }

    /**
     * Functionality : Delete Document
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : String
     */
    public function FStPCKDeleteDoc()
    {

        $tDocNo = $this->input->post('tDocNo');

        $this->db->trans_begin();

        $aDelMasterParams = [
            'tDocNo' => trim($tDocNo)
        ];
        $this->Productpick_model->FSaMDelMaster($aDelMasterParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Cannot Delete Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Complete.',
            );
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    //Delete Multiple Document
    public function FStPCKDeleteMultiDoc()
    {
        $aDocNo = $this->input->post('aDocNo');

        $this->db->trans_begin();

        foreach ($aDocNo as $aItem) {
            $aDelMasterParams = [
                'tDocNo' => trim($aItem)
            ];
            $this->Productpick_model->FSaMDelMaster($aDelMasterParams);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Cannot Delete Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Complete.',
            );
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aStatus));
    }

    //หาว่าสาขานี้ คลัง default คืออะไร
    public function FSoCPCKCheckWahouseInBCH()
    {
        $tBCHCode   = $this->input->post('tBCHCode');
        $aItems     = $this->Productpick_model->FSaMCheckWahouseInBCH($tBCHCode);
        $aReturnData = array(
            'aItems' => $aItems
        );
        echo json_encode($aReturnData);
    }


























    /**
     * Functionality : Get Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSxCPCKGetPdtInTmp()
    {
        $tSearchAll = $this->input->post('tSearchAll');
        $tIsApvOrCancel = $this->input->post('tIsApvOrCancel');
        $nPage = $this->input->post('nPageCurrent');
        $aAlwEvent = FCNaHCheckAlwFunc('docPCK/0/0');
        $nOptDecimalShow = get_cookie('tOptDecimalShow');
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tDocNo = '';
        $tDocKey = 'TCNTPdtPickHD';

        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }
        $nLangEdit = $this->session->userdata("tLangEdit");

        $aColumnShow = FCNaDCLGetColumnShow('TCNTPdtPickHD');

        // Calcurate Document DT Temp
        $aCalcDTParams = [
            'tDataDocEvnCall'   => '',
            'tDataVatInOrEx'    => '2',
            'tDataDocNo'        => $tDocNo,
            'tDataDocKey'       => $tDocKey,
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        $aEndOfBillParams = [
            'tSplVatType' => '2',
            'tDocNo' => $tDocNo,
            'tDocKey' => $tDocKey,
            'nLngID' => FCNaHGetLangEdit(),
            'tSesSessionID' => $this->session->userdata('tSesSessionID'),
            'tBchCode' => $this->session->userdata('tSesUsrLevel') == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata('tSesUsrBchCode')
        ];
        $aEndOfBill['aEndOfBillVat'] = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
        $aEndOfBill['aEndOfBillCal'] = FCNaDOCEndOfBillCal($aEndOfBillParams);
        $aEndOfBill['tTextBath'] = FCNtNumberToTextBaht($aEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

        $aGetPdtInTmpParams  = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 20,
            'tSearchAll' => $tSearchAll,
            'tUserSessionID' => $tUserSessionID,
            'tDocKey' => $tDocKey
        );
        $aResList = $this->Productpick_model->FSaMGetPdtInTmp($aGetPdtInTmpParams);

        $aGenTable = array(
            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'bIsApvOrCancel' => ($tIsApvOrCancel == "1") ? true : false,
            'aColumnShow' => $aColumnShow,
            'nPage' => $nPage,
            'nOptDecimalShow' => $nOptDecimalShow
        );
        $tHtml = $this->load->view('document/productpick/advance_table/wProductPickPdtDatatable', $aGenTable, true);

        $aResponse = [
            'aEndOfBill' => $aEndOfBill,
            'html' => $tHtml
        ];

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aResponse));
    }

    /**
     * Functionality : Insert Pdt to Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Status
     * Return Type : Array
     */
    public function FSaCPCKInsertPdtToTmp()
    {

        $tPCKDocNo =  $this->input->post('tPCKDocNo');
        // $tDocNo             = '';
        $tDocNo              = (isset($tPCKDocNo)) ? $tPCKDocNo : '';

        $tDocKey            = 'TCNTPdtPickHD';
        $nLngID             = $this->session->userdata("tLangID");
        $tUserSessionID     = $this->session->userdata('tSesSessionID');
        $tUserLevel         = $this->session->userdata('tSesUsrLevel');
        $tBchCode           = $this->input->post('ptBchCode');

        $tPCKOptionAddPdt = $this->input->post('tPCKOptionAddPdt');
        $tIsByScanBarCode = $this->input->post('tIsByScanBarCode');
        $tBarCodeByScan = $this->input->post('tBarCodeByScan');
        $tPdtData = $this->input->post('tPdtData');
        $aPdtData = json_decode($tPdtData);

        $this->db->trans_begin();

        if ($tIsByScanBarCode != '1') { // ทำงานเมื่อไม่ใช่การแสกนบาร์โค้ดมา

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            foreach ($aPdtData as $nKey => $oItem) {
                $oPackData = $oItem->packData;

                $tPdtCode = $oPackData->PDTCode;
                $tBarCode = $oPackData->Barcode;
                $tPunCode = $oPackData->PUNCode;
                $cPrice = $oPackData->Price;

                $aGetMaxSeqDTTempParams = array(
                    'tDocNo' => $tDocNo,
                    'tDocKey' => $tDocKey,
                    'tUserSessionID' => $tUserSessionID
                );
                $nMaxSeqNo = $this->Productpick_model->FSnMGetMaxSeqDTTemp($aGetMaxSeqDTTempParams);

                $aDataPdtParams = array(
                    'tDocNo' => $tDocNo,
                    'tBchCode' => $tBchCode, // จากสาขาที่ทำรายการ
                    'tPdtCode' => $tPdtCode, // จาก Browse Pdt
                    'tPunCode' => $tPunCode, // จาก Browse Pdt
                    'tBarCode' => $tBarCode, // จาก Browse Pdt
                    'pcPrice' => $cPrice, // ราคาสินค้าจาก Browse Pdt
                    'nMaxSeqNo' => $nMaxSeqNo + 1, // จำนวนล่าสุด Seq
                    // 'nCounts' => $nCounts,
                    'nLngID' => $nLngID, // รหัสภาษาที่ login
                    'tUserSessionID' => $tUserSessionID,
                    'tDocKey' => $tDocKey,
                    'tOptionAddPdt' => $tPCKOptionAddPdt
                );
                $aDataPdtMaster = $this->Productpick_model->FSaMGetDataPdt($aDataPdtParams); // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา

                if ($aDataPdtMaster['rtCode'] == '1') {
                    $this->Productpick_model->FSaMInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams); // นำรายการสินค้าเข้า DT Temp
                }
            }
        }



        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess InsertPdtToTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success InsertPdtToTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Update Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPCKUpdatePdtInTmp()
    {
        $tFieldName = $this->input->post('tFieldName');
        $tValue = $this->input->post('tValue');
        $nSeqNo = $this->input->post('nSeqNo');
        $tDocNo = '';
        $tDocKey = 'TCNTPdtPickHD';
        $tBchCode = $this->input->post('tBchCode');
        $tUserSessionID = $this->session->userdata("tSesSessionID");
        $tUserLoginCode = $this->session->userdata("tSesUsername");

        $this->db->trans_begin();

        $aUpdatePdtInTmpBySeqParams = [
            'tFieldName' => $tFieldName,
            'tValue' => $tValue,
            'tUserSessionID' => $tUserSessionID,
            'tDocNo' => $tDocNo,
            'tDocKey' => $tDocKey,
            'nSeqNo' => $nSeqNo,
        ];
        $this->Productpick_model->FSbUpdatePdtInTmpBySeq($aUpdatePdtInTmpBySeqParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess UpdatePdtInTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success UpdatePdtInTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Delete Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPCKDeletePdtInTmp()
    {
        $nSeqNo = $this->input->post('nSeqNo');
        $tUserSessionID = $this->session->userdata("tSesSessionID");

        $this->db->trans_begin();

        $aDeleteInTmpBySeqParams = [
            'tUserSessionID' => $tUserSessionID,
            'tDocNo' => '',
            'tDocKey' => 'TCNTPdtPickHD',
            'nSeqNo' => $nSeqNo,
        ];
        $this->Productpick_model->FSbDeletePdtInTmpBySeq($aDeleteInTmpBySeqParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess DeletePdtInTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success DeletePdtInTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Delete More Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : Napat(Jame) 31/07/2020
     * Return : -
     * Return Type : -
     */
    public function FSxCPCKDeleteMorePdtInTmp()
    {
        // $tSeqNo = $this->input->post('paSeqNo');
        $tUserSessionID = $this->session->userdata("tSesSessionID");

        $this->db->trans_begin();

        $aDeleteInTmpBySeqParams = [
            'tUserSessionID' => $tUserSessionID,
            'tDocNo' => '',
            'tDocKey' => 'TCNTPdtPickHD',
            'aSeqNo' => $this->input->post('paSeqNo'),
        ];
        $this->Productpick_model->FSbDeleteMorePdtInTmpBySeq($aDeleteInTmpBySeqParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess DeleteMorePdtInTmp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success DeleteMorePdtInTmp'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    /**
     * Functionality : Clear Pdt in Temp
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxCPCKClearPdtInTmp()
    {
        $tUserSessionID = $this->session->userdata("tSesSessionID");

        $aClearPdtInTmpParams = [
            'tUserSessionID' => $tUserSessionID
        ];
        $this->Productpick_model->FSbClearPdtInTmp($aClearPdtInTmpParams);
    }

    /**
     * Functionality : Get Pdt Column List
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FStCPCKGetPdtColumnList()
    {

        $aAvailableColumn = FCNaDCLAvailableColumn('TCNTPdtTboDT');
        $aData['aAvailableColumn'] = $aAvailableColumn;
        $this->load->view('document/productpick/advance_table/wProductPickPdtColList', $aData);
    }

    /**
     * Functionality : Update Pdt Column
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FStCPCKUpdatePdtColumn()
    {

        $aColShowSet = $this->input->post('aColShowSet');
        $aColShowAllList = $this->input->post('aColShowAllList');
        $aColumnLabelName = $this->input->post('aColumnLabelName');
        $nStaSetDef = $this->input->post('nStaSetDef');

        $this->db->trans_begin();

        FCNaDCLSetShowCol('TCNTPdtTboDT', '', '');

        if ($nStaSetDef == 1) {
            FCNaDCLSetDefShowCol('TCNTPdtTboDT');
        } else {
            for ($i = 0; $i < FCNnHSizeOf($aColShowSet); $i++) {

                FCNaDCLSetShowCol('TCNTPdtTboDT', 1, $aColShowSet[$i]);
            }
        }

        // Reset Seq
        FCNaDCLUpdateSeq('TCNTPdtTboDT', '', '', '');
        $q = 1;
        for ($n = 0; $n < FCNnHSizeOf($aColShowAllList); $n++) {
            FCNaDCLUpdateSeq('TCNTPdtTboDT', $aColShowAllList[$n], $q, $aColumnLabelName[$n]);
            $q++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess UpdatePdtColumn"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg' => 'Success UpdatePdtColumn'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    //////////////////////////////////////////// อ้างอิงเอกสารภายใน //////////////////////////

    //อ้างอิงเอกสารภายใน
    public function FSoCPCKRefIntDoc()
    {
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );

        $this->load->view('document/productpick/refintdocument/wProductPickRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCPCKCallRefIntDocDataTable()
    {

        $nPage                              = $this->input->post('nPCKRefIntPageCurrent');
        $tPCKRefIntBchCode       = $this->input->post('tPCKRefIntBchCode');
        $tPCKRefIntDocNo         = $this->input->post('tPCKRefIntDocNo');
        $tPCKRefIntDocDateFrm    = $this->input->post('tPCKRefIntDocDateFrm');
        $tPCKRefIntDocDateTo     = $this->input->post('tPCKRefIntDocDateTo');
        $tPCKRefIntStaDoc        = $this->input->post('tPCKRefIntStaDoc');

        // Page Current
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPCKRefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");


        $aDataParamFilter = array(
            'tPCKRefIntBchCode'      => $tPCKRefIntBchCode,
            'tPCKRefIntDocNo'        => $tPCKRefIntDocNo,
            'tPCKRefIntDocDateFrm'   => $tPCKRefIntDocDateFrm,
            'tPCKRefIntDocDateTo'    => $tPCKRefIntDocDateTo,
            'tPCKRefIntStaDoc'       => $tPCKRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );

        $aDataParam = $this->Productpick_model->FSoMPCKCallRefIntDocDataTable($aDataCondition);

        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );

        $this->load->view('document/productpick/refintdocument/wProductPickRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCPCKCallRefIntDocDetailDataTable()
    {

        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        $aDataParam = $this->Productpick_model->FSoMPCKCallRefIntDocDTDataTable($aDataCondition);

        $aDataParamRefPCK = $this->Productpick_model->FSoMPCKCallRefIntDocDTPCKDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'aDataParamRefPCK' => $aDataParamRefPCK,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('document/productpick/refintdocument/wProductPickRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCPCKCallRefIntDocInsertDTToTemp()
    {
        $tPCKDocNo           =  $this->input->post('tPCKDocNo');
        $tPCKFrmBchCode      =  $this->input->post('tFrmBchCode');
        $tPCKOptAddPdt      =  $this->input->post('tOptAddPdt');

        $nLangID = $this->session->userdata("tLangEdit");
        // $tCstCode = $this->input->post('ptCstCode');

        $tPCKAgnCode      =  $this->input->post('tAgnCode');
        $tRefIntDocNo                   =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode                 =  $this->input->post('tRefIntBchCode');
        $aSeqNo                         =  $this->input->post('aSeqNo');
        $tSplStaVATInOrEx               = $this->input->post('tSplStaVATInOrEx');

        $aDataParam = array(
            'tPCKDocNo'       => $tPCKDocNo,
            'tPCKFrmBchCode'  => $tPCKFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
            'tPCKAgnCode' => $tPCKAgnCode,
            'tSessionID' => $this->session->userdata('tSesSessionID'),
            'tPCKOptAddPdt' => $tPCKOptAddPdt,
            'tPCKDocKey' => 'TCNTPdtPickHD'
        );

        $aDataResult = $this->Productpick_model->FSoMPCKCallRefIntDocInsertDTToTemp($aDataParam);


        // Calcurate Document DT Temp Array Parameter
        // $aCalcDTParams = [
        //     'tDataDocEvnCall'   => '12',
        //     'tDataVatInOrEx'    => $tSplStaVATInOrEx,
        //     'tDataDocNo'        => $tPCKDocNo,
        //     'tDataDocKey'       => 'TCNTPdtPickDT',
        //     'tDataSeqNo'        => ''
        // ];

        // $aCalcDTParams = [
        //     'aCSTAddress'   => $aCSTAddress,
        // ];



        return  $aDataResult;
        // return  $aCalcDTParams;
    }

    // ################################################################################################################################################################################

    // เช็คว่ามีของในคลังพอไหมอนุมัติ
    public function FSoCPCKEventCheckProductWahouse()
    {
        try {

            $tDocNo       = $this->input->post('tDocNo');
            $tBchCode     = $this->input->post('tBchCode');

            $aDataWhere = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TCNTPdtPickHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            );
            $this->Productpick_model->FSxMPCKUpdatePdtStkPrcAll($aDataWhere, '1');

            $aGetPdtInTmpForSendToAPI = $this->Productpick_model->FSaMPCKGetPdtInTmpForSendToAPI($aDataWhere);
            if (FCNnHSizeOf($aGetPdtInTmpForSendToAPI) > 0) {

                //API CheckSTK
                $aConfig = $this->Productpick_model->FSaMPCKGetConfigAPI();
                if ($aConfig['rtCode'] == '800') {
                    $aReturnData = array(
                        'nStaEvent' => 300,
                        'tStaMessg' => 'เกิดข้อผิดพลาด ไม่พบ API ในการเชื่อมต่อ'
                    );
                    $this->Productpick_model->FSxMPCKUpdatePdtStkPrcAll($aDataWhere, '0');
                    echo json_encode($aReturnData);
                    return false;
                } else {
                    $tUrlAddress = $aConfig['raItems'][0]['FTUrlAddress'];
                }

                $tUrlApi    = $tUrlAddress . '/Stock/CheckStockPdts';
                $aParam     = $aGetPdtInTmpForSendToAPI;
                $aAPIKey    = array(
                    'tKey'      => 'X-API-KEY',
                    'tValue'    => '12345678-1111-1111-1111-123456789410'
                );
                $aResult    = FCNaHCallAPIBasic($tUrlApi, 'POST', $aParam, $aAPIKey);
                // echo "<pre>"; print_r($aResult); echo "</pre>"; exit;
                if ($aResult['rtCode'] == '001') {
                    $aHaveItemInWah     = array();
                    $aNotFoundItemInWah = array();
                    $nCountItem         = FCNnHSizeOf($aResult['raItems']);

                    for ($i = 0; $i < $nCountItem; $i++) {
                        if ($aResult['raItems'][$i]['rcReqQty'] <= $aResult['raItems'][$i]['rcStkQty']) { // เอาเฉพาะสินค้าที่มีในคลังขาย
                            array_push($aHaveItemInWah, $aResult['raItems'][$i]['rtPdtCode']);
                        } else {
                            array_push($aNotFoundItemInWah, $aResult['raItems'][$i]['rtPdtCode']);
                        }
                    }

                    // ถ้าสินค้าไหนมีอยู่ในคลังขาย ก็ให้ปรับ PdtStkPrc = 1
                    if (FCNnHSizeOf($aHaveItemInWah) > 0) {
                        $tUpdatePdtStkPrc = $this->Productpick_model->FSxMTransferBchUpdatePdtStkPrc($aDataWhere, $aHaveItemInWah);
                    }
                    $tChkTsysConfig = $this->Productpick_model->FSxMPCKChkConfig($aDataWhere, $aHaveItemInWah);


                    if (FCNnHSizeOf($aNotFoundItemInWah) > 0) {
                        $aReturnData = array(
                            'nStaEvent'         => 600,
                            'tStaMessg'         => 'ไม่สามารถอนุมัติเอกสารได้เนื่องจากมีสินค้าบางรายการมีสต๊อกไม่เพียงพอ',
                            'tChkTsysConfig'    => $tChkTsysConfig[0]['FTSysStaUsrValue']
                        );
                        $this->Productpick_model->FSxMPCKUpdatePdtStkPrcAll($aDataWhere, '0');
                    } else {

                        // $aMQParams = [
                        //     "queueName" => "CN_QDocApprove",
                        //     "params"    => [
                        //         'ptFunction'    => "TSVTJob1ReqHD",
                        //         'ptSource'      => 'AdaStoreBack',
                        //         'ptDest'        => 'MQReceivePrc',
                        //         'ptFilter'      => $tBchCode,
                        //         'ptData'        => json_encode([
                        //             "ptBchCode"     => $tBchCode,
                        //             "ptDocNo"       => $tDocNo,
                        //             "ptDocType"     => '',
                        //             "ptUser"        => $this->session->userdata("tSesUsername"),
                        //         ])
                        //     ]
                        // ];
                        // FCNxCallRabbitMQ($aMQParams);

                        $aReturnData = array(
                            'nStaEvent'         => 1,
                            'tStaMessg'         => 'SUCCESS',
                            'tUpdatePdtStkPrc'  => $tUpdatePdtStkPrc,
                            'tChkTsysConfig'    => $tChkTsysConfig[0]['FTSysStaUsrValue'],
                            // 'aSendMQParams'     => $aMQParams
                        );
                    }
                } else {
                    $aReturnData = array(
                        'nStaEvent'     => 800,
                        'tStaMessg'     => 'API Error',
                        'aPdtSendAPI'   => $aGetPdtInTmpForSendToAPI,
                        'oAPIReturn'    => $aResult
                    );
                    $this->Productpick_model->FSxMPCKUpdatePdtStkPrcAll($aDataWhere, '0');
                }
            } else {
                $aReturnData = array(
                    'nStaEvent'     => 400,
                    'tStaMessg'     => 'สินค้าทั้งหมดในเอกสาร ยืนยันสต็อคหมดแล้ว'
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


    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCPCKPageHDDocRef()
    {
        try {
            $tDocNo     = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TCNTPdtPickHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXshDocNo'        => $tDocNo,
                'FTXshDocKey'       => 'TCNTPdtPickHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef = $this->Productpick_model->FSaMPCKGetDataHDRefTmp($aDataWhere);
            // print_r($aDataDocHDRef);
            // die();
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/productpick/wProductPickDocRef', $aDataConfig, true);
            $aReturnData = array(
                'tViewPageHDRef'    => $tViewPageHDRef,
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

    // ค่าอ้างอิงเอกสาร - เพิ่ม หรือ เเก้ไข
    public function FSoCPCKEventAddEditHDDocRef()
    {
        try {
            $aDataWhere = [
                'FTXshDocNo'        => $this->input->post('ptPCKDocNo'),
                'FTXshDocKey'       => 'TCNTPdtPickHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tPCKRefDocNoOld'   => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];



            $aReturnData = $this->Productpick_model->FSaMPCKAddEditHDRefTmp($aDataWhere, $aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCPCKEventDelHDDocRef()
    {
        try {
            $aData = [
                'FTXshDocNo'        => $this->input->post('ptDocNo'),
                'FTXshRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXshDocKey'       => 'TCNTPdtPickHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->Productpick_model->FSaMPCKDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function: Edit Inline สินค้า ลง Document DT Temp
    public function FSoCPCKEditPdtIntoDocDTTemp()
    {
        try {
            $tPCKBchCode         = $this->input->post('tPCKBchCode');
            $tPCKDocNo           = $this->input->post('tPCKDocNo');
            $nPCKSeqNo           = $this->input->post('nPCKSeqNo');
            $tPCKSessionID       = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tPCKBchCode'    => $tPCKBchCode,
                'tPCKDocNo'      => $tPCKDocNo,
                'nPCKSeqNo'      => $nPCKSeqNo,
                'tPCKSessionID'  => $tPCKSessionID,
                'tDocKey'       => 'TCNTPdtPickHD',
            );
            $aDataUpdateDT = array(
                'FCXtdQtyOrd'          => $this->input->post('nQty'),
            );
            $this->db->trans_begin();
            $this->Productpick_model->FSaMPCKUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

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

            echo "<pre>";
            print_r($aReturnData);
            echo "</pre>";
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }



    // Function: Remove Product In Documeny Temp
    public function FSvCPCKRemovePdtInDTTmp()
    {
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tDODocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtPickHD',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Productpick_model->FSnMPCKDelPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success Delete Product'
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

    //Remove Product In Documeny Temp Multiple
    public function FSvCPCKRemovePdtInDTTmpMulti()
    {
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tDODocNo' => $this->input->post('tDocNo'),
                'tBchCode' => $this->input->post('tBchCode'),
                'tPdtCode' => $this->input->post('tPdtCode'),
                'nSeqNo'   => $this->input->post('nSeqNo'),
                'tDocKey'  => 'TCNTPdtPickHD',
                'tSessionID' => $this->session->userdata('tSesSessionID'),
            );

            $aStaDelPdtDocTemp = $this->Productpick_model->FSnMPCKDelMultiPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success Delete Product'
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
}
