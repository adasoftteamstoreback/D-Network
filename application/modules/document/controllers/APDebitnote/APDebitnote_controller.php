<?php
defined('BASEPATH') or exit('No direct script access allowed');

class APDebitnote_controller extends MX_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper("file");
        $this->load->model('authen/user/mUser');
        $this->load->model('company/company/mCompany');
        $this->load->model('company/branch/mBranch');
        $this->load->model('company/shop/mShop');
        $this->load->model('payment/rate/mRate');
        $this->load->model('company/vatrate/mVatRate');
        $this->load->model('document/apdebitnote/APDebitnote_model');
        $this->load->model('document/apdebitnote/APDebitnoteDisChgModal_model');
        
        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nBrowseType, $tBrowseOption){
        //รองรับการ Jump
        $aData['tDocNo']            = $this->input->post('tDocNo');
        $aData['tBchCode']          = $this->input->post('tBchCode');
        $aData['tAgnCode']          = $this->input->post('tAgnCode');
        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
        $aData['aAlwEvent']         = FCNaHCheckAlwFunc('creditNote/0/0'); // Controle Event
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('creditNote/0/0'); // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aData['nOptDecimalShow']   = FCNxHGetOptionDecimalShow();
        $aData['nOptDecimalSave']   = FCNxHGetOptionDecimalSave();
        $this->load->view('document/apdebitnote/wAPDebitnote', $aData);
    }

    // Function : get ร้านค้า ใน สาขา
    public function FSvCAPDGetShpByBch(){
        $tBchCode       = $this->input->post('ptBchCode');
        // Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TCNMShop_L');
        $nLangHave      = FCNnHSizeOf($aLangHave);
        if ($nLangHave > 1) {
            if ($nLangEdit != '') {
                $nLangEdit = $nLangEdit;
            } else {
                $nLangEdit = $nLangResort;
            }
        } else {
            if (@$aLangHave[0]->nLangList == '') {
                $nLangEdit = '1';
            } else {
                $nLangEdit = $aLangHave[0]->nLangList;
            }
        }
        $aData  = array(
            'FTBchCode'     => $tBchCode,
            'FTShpCode'     => '',
            'nPage'         => 1,
            'nRow'          => '9999',
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => ''
        );
        $aShpData = $this->mShop->FSaMSHPList($aData);
        echo json_encode($aShpData);
    }

    // Function : Get สินค้า ตาม Pdt BarCode
    public function FSvCAPDGetPdtBarCode(){
        $tBarCode       = $this->input->post('tBarCode');
        $tSplCode       = $this->input->post('tSplCode');
        $aPdtBarCode    =  FCNxHGetPdtBarCode($tBarCode, $tSplCode);
        if($aPdtBarCode != 0) {
            $jPdtBarCode = json_encode($aPdtBarCode);
            $aData  = array(
                'aData' => $jPdtBarCode,
                'tMsg'     => 'OK',
            );
        }else{
            $aData  = array(
                'aData' => 0,
                'tMsg'     => language('document/browsepdt/browsepdt', 'tPdtNotFound'),
            );
        }
        $jData  = json_encode($aData);
        echo $jData;
    }

    // Function : Add Temp to DT
    public function FSaMAPDAddTmpToDT($ptXphDocNo = ''){
        $aDataWhere = array(
            'FTXphDocNo'    => $ptXphDocNo,
            'FTXthDocKey'   => 'TAPTPdHD',
        );
        $aResInsDT  = $this->APDebitnote_model->FSaMAPDInsertTmpToDT($aDataWhere);
        if ($aResInsDT['rtCode'] == '1') {
            $this->APDebitnote_model->FSxMClearPdtInTmp();
        }
    }

    // Function : แก้ไข Pdt DT
    public function FSvCAPDEditPdtIntoTableDT(){
        $tUserLevel     = $this->session->userdata('tSesUsrLevel');
        $tBchCode       = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCodeDefault");
        $tDocNo         = $this->input->post('tDocNo');
        $tSplVatType    = $this->input->post('tSplVatType');
        $nSeqNo         = $this->input->post('tSeqNo');
        $tFieldName     = $this->input->post('tFieldName');
        $tValue         = $this->input->post('tValue');
        $tIsDelDTDis    = $this->input->post('tIsDelDTDis');
        $tSessionID     = $this->session->userdata('tSesSessionID');
        $aDataWhere     = array(
            'tBchCode'      => $tBchCode,
            'tDocNo'        => $tDocNo,
            'nSeqNo'        => $nSeqNo,
            'tSessionID'    => $tSessionID,
            'tDocKey'       => 'TAPTPdHD',
        );

        $aDataUpdateDT = [
            'tFieldName'    => $tFieldName,
            'tValue'        => $tValue
        ];

        $this->db->trans_begin();
        // แก้ไขรายการสินค้า
        $this->APDebitnote_model->FSaMAPDUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
        if ($tIsDelDTDis == '1') { // ยืนยันการลบ DTDis ส่วนลดรายการนี้
            $this->APDebitnoteDisChgModal_model->FSaMAPDDeleteDTDisTemp($aDataWhere);
            $this->APDebitnoteDisChgModal_model->FSaMAPDClearDisChgTxtDTTemp($aDataWhere);
        }
        // Prorat Call
        FCNaHCalculateProrate('TAPTPdHD', $tDocNo);
        $aCalcDTParams = [
            'tDataDocEvnCall'   => '1',
            'tDataVatInOrEx'    => $tSplVatType,
            'tDataDocNo'        => $tDocNo,
            'tDataDocKey'       => 'TAPTPdHD',
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn    = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Unsucess process"
            );
        } else {
            $this->db->trans_commit();
            $aReturn    = array(
                'nStaEvent' => '1',
                'tStaMessg' => 'Success process'
            );
        }
        echo json_encode($aReturn);
    }

    // Function : Add Pdt ลง Dt (File)
    public function FSvCAPDAddPdtIntoTableDT(){
        $tUserLevel         = $this->session->userdata('tSesUsrLevel');
        $tDocNo             = $this->input->post('tDocNo');
        $tIsRefPI           = $this->input->post('tIsRefPI');
        $tSplVatType        = $this->input->post('tSplVatType');
        $tSplCode           = $this->input->post('tSplCode');
        $tBchCode           = $this->input->post('tBchCode');
        $nAPDOptionAddPdt   = $this->input->post('tAPDOptionAddPdt');
        $tPdtData           = $this->input->post('tPdtData');
        $tIsByScanBarCode   = $this->input->post('tIsByScanBarCode');
        $tBarCodeByScan     = $this->input->post('tBarCodeByScan');
        $aPdtData           = json_decode($tPdtData);
        if ($tIsByScanBarCode != '1') { // ทำงานเมื่อไม่ใช่การแสกนบาร์โค้ดมา
            if ($tIsRefPI == '1') { // หากนำเข้าจากการอ้างอิงใบ PI ต้องลบรายการสินค้าเดิมก่อน
                $this->APDebitnote_model->FSxMClearPdtInTmp();
                $this->APDebitnote_model->FSxMClearDTDisTmp();
                $this->APDebitnote_model->FSxMClearHDDisTmp();
            }
            // $nCounts = $this->APDebitnote_model->FSaMAPDGetCountDTTemp($aDataWhere);

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPdtData); $nI++) {
                $pnPdtCode  = $aPdtData[$nI]->pnPdtCode;
                $ptBarCode  = $aPdtData[$nI]->ptBarCode;
                $ptPunCode  = $aPdtData[$nI]->ptPunCode;
                $pcPrice    = $aPdtData[$nI]->packData->Price;
                $pcQty      = $aPdtData[$nI]->packData->Qty;
                $tVatrate   = $aPdtData[$nI]->packData->Vatrate;
                $tVatcode   = $aPdtData[$nI]->packData->vatcode;
                $aDataWhere = array(
                    'tDocNo'    => $tDocNo,
                    'tDocKey'   => 'TAPTPdHD',
                );
                $nMaxSeqNo = $this->APDebitnote_model->FSaMAPDGetMaxSeqDTTemp($aDataWhere);

                $aDataPdtParams = array(
                    'tDocNo'                    => $tDocNo,
                    'tSplCode'                  => $tSplCode,
                    'tBchCode'                  => $tBchCode,   // จากสาขาที่ทำรายการ
                    'tPdtCode'                  => $pnPdtCode,  // จาก Browse Pdt
                    'tPunCode'                  => $ptPunCode,  // จาก Browse Pdt
                    'tBarCode'                  => $ptBarCode,  // จาก Browse Pdt
                    'pcPrice'                   => $pcPrice,    // ราคาสินค้าจาก Browse Pdt
                    'nMaxSeqNo'                 => $nMaxSeqNo + 1, // จำนวนล่าสุด Seq
                    'nLngID'                    => $this->session->userdata("tLangID"), // รหัสภาษาที่ login
                    'tSessionID'                => $this->session->userdata('tSesSessionID'),
                    'tDocKey'                   => 'TAPTPdHD',
                    'nQty'                      => $pcQty,
                    'nAPDOptionAddPdt'          => $nAPDOptionAddPdt,
                    'tVatrate'                  => $tVatrate,
                    'tVatcode'                  => $tVatcode
                );

                $aDataPdtMaster = $this->APDebitnote_model->FSaMAPDGetDataPdt($aDataPdtParams); // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                if ($aDataPdtMaster['rtCode'] == '1') {
                    $this->APDebitnote_model->FSaMAPDInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams); // นำรายการสินค้าเข้า DT Temp
                }
            }
        }
        // นำเข้ารายการสินค้าจากการแสกนบาร์โค้ด
        if ($tIsByScanBarCode == '1') {
            $aGetPunCodeByBarCodeParams = [
                'tBarCode' => $tBarCodeByScan,
                'tSplCode' => $tSplCode
            ];
            $aPdtData = $this->APDebitnote_model->FSaMAPDGetPunCodeByBarCode($aGetPunCodeByBarCodeParams);
            if ($aPdtData['rtCode'] == '1') {
                $aDataWhere = array(
                    'tDocNo'    => $tDocNo,
                    'tDocKey'   => 'TAPTPdHD',
                );
                $nMaxSeqNo = $this->APDebitnote_model->FSaMAPDGetMaxSeqDTTemp($aDataWhere);
                $aPdtItems = $aPdtData['raItem'];
                // Loop
                $aDataPdtParams = array(
                    'tDocNo'            => $tDocNo,
                    'tSplCode'          => $tSplCode,
                    'tBchCode'          => $tBchCode,   // จากสาขาที่ทำรายการ
                    'tPdtCode'          => $aPdtItems['FTPdtCode'],  // จาก Browse Pdt
                    'tPunCode'          => $aPdtItems['FTPunCode'],  // จาก Browse Pdt
                    'tBarCode'          => $aPdtItems['FTBarCode'],  // จาก Browse Pdt
                    'pcPrice'           => $aPdtItems['cCost'],
                    'nMaxSeqNo'         => $nMaxSeqNo + 1, // จำนวนล่าสุด Seq
                    'nLngID'            => $this->session->userdata("tLangID"), // รหัสภาษาที่ login
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TAPTPdHD',
                    'nAPDOptionAddPdt'  => $nAPDOptionAddPdt
                );
                $aDataPdtMaster = $this->APDebitnote_model->FSaMAPDGetDataPdt($aDataPdtParams); // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                if ($aDataPdtMaster['rtCode'] == '1') {
                    $nStaInsPdtToTmp    = $this->APDebitnote_model->FSaMAPDInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams); // นำรายการสินค้าเข้า DT Temp
                }
                // Loop
            } else {
                $aStatus = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aStatus));
            }
        }
        // Prorat Call
        FCNaHCalculateProrate('TAPTPdHD', $tDocNo);
        $aCalcDTParams = [
            'tDataDocEvnCall'   => '',
            'tDataVatInOrEx'    => $tSplVatType,
            'tDataDocNo'        => $tDocNo,
            'tDataDocKey'       => 'TAPTPdHD',
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);
        echo $this->session->userdata("tSesUsrBchCodeDefault");
    }

    // Function : Remove Master Pdt Intable (File)
    public function FSvCAPDRemovePdtInDTTmp(){
        $aDataWhere = array(
            'tDocNo'        => $this->input->post('tDocNo'),
            'tPdtCode'      => $this->input->post('tPdtCode'),
            'nSeqNo'        => $this->input->post('nSeqNo'),
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );

        $aResDel = $this->APDebitnote_model->FSnMAPDDelDTTmp($aDataWhere);

        //ถ้าลบสินค้า ต้องวิ่งไปเช็คด้วยว่า มีท้ายบิล ไหม ถ้าสินค้าที่เหลืออยู่ไม่อนุญาตลด ท้ายบิลก็ต้องลบทิ้งด้วย
        $aPackDataCalCulate = array(
            'tDocNo'        => $this->input->post('tDocNo'),
            'tBchCode'      => $this->input->post('tBchCode'),
            'nB4Dis'        => '',
            'tSplVatType'   => $this->input->post('tSplVatType')
        );
        FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
        
        $tDocNo = $this->input->post('tDocNo');
        FCNaHCalculateProrate('TAPTPdHD', $tDocNo);

    }

    // Function : เรียกหน้า  Add 
    public function FSxCAPDAddPage(){
        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
        $aParams = array(
            'nSeqNo'        => '',
            'nStaDis'       => '',
            'tDocNo'        => '',
            'tBchCode'      => $tBchCode,
            'tSessionID'    => $this->session->userdata('tSesSessionID')
        );

        // Clear in temp
        $this->APDebitnote_model->FSxMClearPdtInTmp();
        $this->APDebitnoteDisChgModal_model->FSaMCENDeletePDTInTmp($aParams);
        $this->APDebitnoteDisChgModal_model->FSaMAPDDeleteHDDisTemp($aParams);
        $this->APDebitnoteDisChgModal_model->FSaMAPDDeleteDTDisTemp($aParams);
        // Get Option Show Decimal  
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        // Get Option Scan SKU
        $nOptDocSave        = FCNnHGetOptionDocSave();
        // Get Option Scan SKU
        $nOptScanSku        = FCNnHGetOptionScanSku();
        // Lang ภาษา
        $nLangId    = $this->session->userdata("tLangID");
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aLangHave  = FCNaHGetAllLangByTable('TFNMRate_L');
        $nLangHave  = FCNnHSizeOf($aLangHave);

        if ($nLangHave > 1) {
            if ($nLangEdit != '') {
                $nLangEdit  = $nLangEdit;
            } else {
                $nLangEdit  = $nLangId;
            }
        } else {
            if (@$aLangHave[0]->nLangList == '') {
                $nLangEdit  = '1';
            } else {
                $nLangEdit  = $aLangHave[0]->nLangList;
            }
        }

        $aData  = array(
            'FNLngID'   => $nLangEdit,
            'FTUsrCode' => $this->session->userdata('tSesUsername')
        );

        $aPermission    = FCNaHCheckAlwFunc("creditNote/0/0");

        $aDataUserLogin = $this->APDebitnote_model->FStAPDGetUsrByCode($aData);
        $nDocType       = $this->input->post('nDocType');

        $aDataAdd       = array(
            'nOptDecimalShow'   => $nOptDecimalShow,
            'nOptDocSave'       => $nOptDocSave,
            'nOptScanSku'       => $nOptScanSku,
            'aResult'           => array('rtCode' => '99'),
            'aUserCreated'      => ['rtCode' => '99'],
            'aUserApv'          => ['rtCode' => '99'],
            'aPermission'       => $aPermission,
            'tUserCode'         => $aDataUserLogin['FTUsrCode'],
            'tUserName'         => $aDataUserLogin['FTUsrName'],
            'tUserMchCode'      => $aDataUserLogin['FTMerCode'],
            'tUserMchCode'      => $aDataUserLogin['FTMerCode'],
            'tUserMchName'      => $aDataUserLogin['FTMerName'],
            'tUserShpCode'      => $aDataUserLogin['FTShpCode'],
            'tUserShpName'      => $aDataUserLogin['FTShpName'],
            'tUserWahCode'      => $aDataUserLogin['FTWahCode'],
            'tUserWahName'      => $aDataUserLogin['FTWahName'],
            'tUserBchCode'      => $aDataUserLogin['FTBchCode'],
            'tUserBchName'      => $aDataUserLogin['FTBchName'],
            'tUserDptCode'      => $aDataUserLogin['FTDptCode'],
            'tUserDptName'      => $aDataUserLogin['FTDptName'],
            'nDocType'          => $nDocType
        );
        $this->load->view('document/apdebitnote/wAPDebitnoteAdd', $aDataAdd);
    }

    // Functionality : Event Add Master
    public function FSaCAPDAddEvent(){
        try {
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $tDocDate           = $this->input->post('oetAPDXphDocDate') . " " . $this->input->post('oetAPDDocTime');
            $tUserLevel         = $this->session->userdata('tSesUsrLevel');
            $tLoginDpt          = $this->session->userdata('tSesUsrDptCode');
            $tBchCode           = $this->input->post("oetAPDBchCode");
            $tAgnCode           = $this->input->post("oetAPDAgnCode");
            $tLoginUser         = $this->session->userdata('tSesUsername');
            $tSessionID         = $this->session->userdata('tSesSessionID');
            $tSplVatType        = $this->input->post('ocmAPDXphVATInOrEx');
            

            $aCalDTTempParams = [
                'tDocNo'        => '',
                'tBchCode'      => $tBchCode,
                'tSessionID'    => $tSessionID,
                'tDocKey'       => 'TAPTPdHD',
                'tSplVatType'   => $tSplVatType
            ];
            $aCalDTTempForHD = $this->FSaCAPDCalDTTempForHD($aCalDTTempParams);
            $aCalInHDDisTemp = $this->APDebitnote_model->FSaMAPDCalInHDDisTemp($aCalDTTempParams);

            $aDataMaster = array(
                'tIsAutoGenCode'        => $this->input->post('ocbAPDAutoGenCode'), // ต้องการรัน DocNo อัตโนมัติหรือไม่
                'FTAgnCode'             => $tAgnCode,
                'FTBchCode'             => $tBchCode, // รหัสสาขาที่สร้างเอกสาร
                'FTXphDocNo'            => $this->input->post('oetAPDDocNo'), // เลขที่เอกสาร
                'FNXphDocType'          => $this->input->post('ohdAPDDocType'), // ประเภทเอกสาร (ใบลดหนี้มีสินค้า หรือ ไม่มีสินค้า)
                'FDXphDocDate'          => $tDocDate, // วันที่สร้างเอกสาร
                'FTShpCode'             => $this->input->post('oetAPDShpCode'), // รหัสร้านค้า
                'FTXphCshOrCrd'         => $this->input->post('ocmAPDXphCshOrCrd'), // ประเภทการชำระเงิน (เงินสด หรือ เชื่อ)
                'FTXphVATInOrEx'        => $tSplVatType, // ประเภทภาษี (แยกนอก หรือ รวมใน)
                'FTDptCode'             => $tLoginDpt, // รหัสแผนกผู้ที่สร้างเอกสาร
                'FTWahCode'             => $this->input->post('oetAPDWahCode'), // รหัสคลังสินค้า
                'FTUsrCode'             => $tLoginUser, // รหัสผู้สร้างเอกสาร
                'FTXphApvCode'          => NULL, // รหัสผู้อนุมัติ
                'FTSplCode'             => $this->input->post('oetAPDSplCode'), // รหัสผู้จำหน่าย
                'FTXphRefExt'           => $this->input->post('oetAPDXphRefExt'), // อ้างอิง เลขที่เอกสาร ภายนอก
                'FDXphRefExtDate'       => empty($this->input->post('oetAPDXphRefExtDate')) ? NULL : $this->input->post('oetAPDXphRefExtDate'), // อ้างอิง วันที่เอกสาร ภายนอก
                'FTXphRefInt'           => $this->input->post('oetAPDRefPICode'), // อ้างอิง เลขที่เอกสาร ภายใน
                'FDXphRefIntDate'       => empty($this->input->post('oetAPDXphRefIntDate')) ? NULL : $this->input->post('oetAPDXphRefIntDate'), // อ้างอิง วันที่เอกสาร ภายใน
                'FTXphRefAE'            => NULL,
                'FNXphDocPrint'         => NULL,
                'FTRteCode'             => NULL,
                'FCXphRteFac'           => NULL,
                'FCXphTotal'            => $aCalDTTempForHD['FCXphTotal'],
                'FCXphTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
                'FCXphTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
                'FCXphTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                'FCXphTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                'FTXphDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                'FCXphDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                'FCXphChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                'FCXphTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                'FCXphTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                'FCXphRefAEAmt'         => NULL,
                'FCXphAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                'FCXphAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXphVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXphVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FTXphWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                'FCXphWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                'FCXphGrand'            => $aCalDTTempForHD['FCXphGrand'],
                'FCXphRnd'              => NULL, // $aCalDTTempForHD['FCXphRnd'], ใบลดหนี้ไม่มีการปัดเศษ เนื่องจากเป็นการขายส่งเท่านั้น
                'FTXphGndText'          => $aCalDTTempForHD['FTXphGndText'],
                'FCXphPaid'             => NULL,
                'FCXphLeft'             => NULL,
                'FTXphRmk'              => $this->input->post('otaAPDXphRmk'),
                'FTXphStaRefund'        => NULL,
                'FTXphStaDoc'           => '1', // สถานะเอกสาร (1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก)
                'FTXphStaApv'           => NULL,
                'FTXphStaPrcStk'        => NULL,
                'FTXphStaPaid'          => NULL,
                'FNXphStaDocAct'        => $this->input->post('ocbAPDXphStaDocAct'),
                'FNXphStaRef'           => $this->input->post('ocmAPDXphStaRef'), // สถานะอ้างอิง (0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว)
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTCreateBy'            => $tLoginUser,
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $tLoginUser
            );

            if ($aDataMaster['tIsAutoGenCode'] == '1') { 
                $aStoreParam = array(
                    "tTblName"  => 'TAPTPdHD',
                    "tDocType"  => $aDataMaster['FNXphDocType'],
                    "tBchCode"  => $aDataMaster["FTBchCode"],
                    "tShpCode"  => "",
                    "tPosCode"  => "",
                    "dDocDate"  => date("Y-m-d")
                );
                $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTXphDocNo'] = $aAutogen[0]["FTXxhDocNo"];
            }

            // TAPTPdHDSpl
            $aDataSpl = array(
                'FTBchCode'             => $tBchCode,
                'FTXphDocNo'            => $aDataMaster['FTXphDocNo'],
                'FTXphDstPaid'          => $this->input->post('ocmAPDHDPcSplXphDstPaid'), // 1:ชำระต้นทาง, 2:ชำระปลายทาง
                'FNXphCrTerm'           => $this->input->post('oetAPDHDPcSplXphCrTerm'), // ระยะเครดิต
                'FDXphDueDate'          => empty($this->input->post('oetAPDHDPcSplXphDueDate')) ? NULL : $this->input->post('oetAPDHDPcSplXphDueDate'), // วันที่ครบกำหนด
                'FDXphBillDue'          => empty($this->input->post('oetAPDHDPcSplXphBillDue')) ? NULL : $this->input->post('oetAPDHDPcSplXphBillDue'), // วันที่จะรับ/วางบิล
                'FTXphCtrName'          => $this->input->post('oetAPDHDPcSplXphCtrName'), // ชื่อผู้ตืดต่อ
                'FDXphTnfDate'          => empty($this->input->post('oetAPDHDPcSplXphTnfDate')) ? NULL : $this->input->post('oetAPDHDPcSplXphTnfDate'), // วันที่ส่งของ
                'FTXphRefTnfID'         => $this->input->post('oetAPDHDPcSplXphRefTnfID'), // เลขที่ ใบขนส่ง
                'FTXphRefVehID'         => $this->input->post('oetAPDHDPcSplXphRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                'FTXphRefInvNo'         => $this->input->post('oetAPDHDPcSplXphRefInvNo'), // เลขที่บัญชีราคาสินค้า
                'FTXphQtyAndTypeUnit'   => $this->input->post('oetAPDHDPcSplXphQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                'FNXphShipAdd'          => NULL, // อ้างอิง ที่อยู่ ส่งของ
                'FNXphTaxAdd'           => NULL, // อ้างอิง ที่อยู่ออกใบกำกับภาษี
            );

            // เตรียมข้อมูลสำหรับ HD ใบลดหนี้ไม่มีสินค้า
            $tPdtCode               = $this->input->post('tPdtCode');
            $tPdtName               = $this->input->post('tPdtName');
            $tCalEndOfBillNonePdt   = $this->input->post('tCalEndOfBillNonePdt');
            $aCalEndOfBillNonePdt   = json_decode($tCalEndOfBillNonePdt, true);

            if ($aDataMaster['FNXphDocType'] == '7') { 
                // ใบลดหนี้ไม่มีสินค้า
                $aDataMaster['FCXphTotal'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวม
                $aDataMaster['FCXphTotalNV'] = NULL; // ยอดรวมสินค้าไม่มีภาษี
                $aDataMaster['FCXphTotalNoDis'] = NULL; // ยอดรวมสินค้าห้ามลด
                $aDataMaster['FCXphTotalB4DisChgV'] = NULL; // ยอมรวมสินค้าลดได้ และมีภาษี
                $aDataMaster['FCXphTotalB4DisChgNV'] = NULL; // ยอมรวมสินค้าลดได้ และไม่มีภาษี
                $aDataMaster['FTXphDisChgTxt'] = NULL; // ข้อความมูลค่าลดชาร์จ
                $aDataMaster['FCXphDis'] = NULL; // มูลค่ารวมส่วนลด
                $aDataMaster['FCXphChg'] = NULL; // มูลค่ารวมส่วนชาร์จ
                $aDataMaster['FCXphTotalAfDisChgV'] = NULL; // ยอดรวมหลังลด และมีภาษี
                $aDataMaster['FCXphTotalAfDisChgNV'] = NULL; // ยอดรวมหลังลด และไม่มีภาษี
                $aDataMaster['FCXphAmtV'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวมเฉพาะภาษี
                $aDataMaster['FCXphAmtNV'] = NULL; // ยอดรวมเฉพาะไม่มีภาษี
                $aDataMaster['FCXphVat'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']); // ยอดภาษี
                $aDataMaster['FCXphVatable'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']); // ยอดแยกภาษี
                $aDataMaster['FTXphWpCode'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tVatCode']); // รหัสอัตราภาษี ณ ที่จ่าย
                $aDataMaster['FCXphWpTax'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']); // ภาษีหัก ณ ที่จ่าย
                $aDataMaster['FCXphGrand'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวม
                $aDataMaster['FCXphRnd'] = NULL; // ยอดปัดเศษ
                $aDataMaster['FTXphGndText'] = number_format($aDataMaster['FCXphGrand'], $nOptDecimalShow); // ข้อความ ยอดรวมสุทธิ

                // เตรียมข้อมูลสำหรับ DT ใบลดหนี้ไม่มีสินค้า
                $aDataDT = [
                    'FTBchCode'     => $aDataMaster['FTBchCode'], // สาขาสร้าง
                    'FTXphDocNo'    => $aDataMaster['FTXphDocNo'], // เลขที่เอกสาร
                    'FNXpdSeqNo'    => 1, // ลำดับ
                    'FTPdtCode'     => $tPdtCode, // รหัสสินค้า
                    'FTXpdPdtName'  => $tPdtName, // ชื่อสินค้า
                    'FCXpdFactor'   => 1, // อัตราส่วนต่อหน่วย
                    'FTXpdVatType'  => $tSplVatType, // ประเภทภาษี 1:มีภาษี, 2:ไม่มีภาษี
                    'FTVatCode'     => $aCalEndOfBillNonePdt['tVatCode'], // รหัสภาษี ณ. ซื้อ
                    'FCXpdVatRate'  => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['nVatrate']), // อัตราภาษี ณ. ซื้อ
                    'FCXpdQty'      => 1, // จำนวนชื้น ตาม หน่วย
                    'FCXpdQtyAll'   => 1, // จำนวนรวมหน่วยเล็กสุด
                    'FCXpdSetPrice' => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // ราคาซื้อ ตาม หน่วย * อัตราแลกเปลี่ยน
                    'FCXpdAmtB4DisChg' => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // มูลค่ารวมก่อนลด
                    'FCXpdNet'      => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // มูลค่าสุทธิก่อนท้ายบิล
                    'FCXpdNetAfHD'  => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']), // มูลค่าสุทธิหลังท้ายบิล
                    'FCXpdVat'      => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']), // มูลค่าภาษี
                    'FCXpdVatable'  => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']), // มูลค่าแยกภาษี
                    'FCXpdCostIn'   => floatval(FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat'])) + floatval(FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable'])), // ต้นทุนรวมใน 
                    'FCXpdCostEx'   => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']), // ต้นทุนแยกนอก
                    'FTXpdRmk'      => 'ใบลดหนี้ไม่มีสินค้า', 
                    'FDCreateOn'    => date('Y-m-d H:i:s'),
                    'FTCreateBy'    => $tLoginUser,
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'   => $tLoginUser
                ];
            }

            $aParams = array(
                'tSessionID'    => $tSessionID,
                'tDocNo'        => $aDataMaster['FTXphDocNo'],
                'tBchCode'      => $tBchCode,
                'tDocKey'       => 'TAPTPdHD',
                'tIsUpdatePage' => '0'
            );

            $this->db->trans_begin();

            /*======================= Begin Data Process =====================*/

            if ($aDataMaster['FNXphDocType'] == '6') {
                // ใบลดหนี้แบบมีสินค้า
                $this->APDebitnote_model->FSaMAPDAddUpdateHDHavePdt($aDataMaster);
                $this->APDebitnote_model->FSaMAPDInsertTmpToDT($aParams);
                $this->APDebitnote_model->FSaMAPDInsertTmpToDTDis($aParams);
                $this->APDebitnote_model->FSaMAPDInsertTmpToHDDis($aParams);
            }

            if ($aDataMaster['FNXphDocType'] == '7') {
                // ใบลดหนี้แบบไม่มีสินค้า
                $this->APDebitnote_model->FSaMAPDAddUpdateHDNonePdt($aDataMaster);
                $this->APDebitnote_model->FSaMAPDAddUpdateDTNonePdt($aDataDT);
            }

            $this->APDebitnote_model->FSaMAPDAddUpdatePCHDSpl($aDataSpl);

            /*========================= End Data Process =====================*/

            /*======================= Begin Data Process Doc Ref =====================*/
            $aDataWhereDocRef   = array(
                'FTBchCode'         => $tBchCode,
                'FTAgnCode'         => $tAgnCode,
                'FTXphDocNo'        => $aDataMaster['FTXphDocNo'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $aDataMaster['FTCreateBy'],
                'FTLastUpdBy'       => $aDataMaster['FTLastUpdBy'],
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tSplVatType
            );

            // Array Data Table Document
            $aTableAddUpdate    = array(
                'tTableHD'      => 'TAPTPdHD',
                'tTableHDDis'   => 'TAPTPdHDDis',
                'tTableHDSpl'   => 'TAPTPdHDSpl',
                'tTableDT'      => 'TAPTPdDT',
                'tTableDTDis'   => 'TAPTPdDTDis',
                'tTableStaGen'  => 2,
            );
            $this->APDebitnote_model->FSxMAPDMoveHDRefTmpToHDRef($aDataWhereDocRef, $aTableAddUpdate);


            /*======================= End Data Process Doc Ref =====================*/

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'    => $aDataMaster['FTXphDocNo'],
                    'nStaEvent'        => '1',
                    'tStaMessg'        => 'Success Add'
                );
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aReturn));
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // Function : คำนวณค่าจาก DT Temp ให้ HD
    // Creator  : 09/03/2022 Wasin
    private function FSaCAPDCalDTTempForHD($paParams){
        $aCalDTTemp = $this->APDebitnote_model->FSaMAPDCalInDTTemp($paParams);
        if (!empty($aCalDTTemp)) {
            $aCalDTTempItems = $aCalDTTemp[0];
            // คำนวณหา ยอดปัดเศษ ให้ HD(FCXphRnd)
            /*$pCalRoundParams = [
                'FCXphAmtV' => $aCalDTTempItems['FCXphAmtV'],
                'FCXphAmtNV' => $aCalDTTempItems['FCXphAmtNV']
            ];
            $aRound = $this->FSaCAPDCalRound($pCalRoundParams);*/

            // คำนวณหา ยอดรวม ให้ HD(FCXphGrand)
            $nRound = NULL; // $aRound['nRound'];
            $cGrand = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV']; // $aRound['cAfRound'];

            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));

            $aCalDTTempItems['FCXphRnd'] = $nRound;
            $aCalDTTempItems['FCXphGrand'] = $cGrand;
            $aCalDTTempItems['FTXphGndText'] = $tGndText;

            /*echo $tGndText;
            
            echo '<pre>';
            var_dump($aCalDTTempItems);
            echo '</pre>';*/

            return $aCalDTTempItems;
        }
    }

    // Function : หาค่าปัดเศษ HD(FCXphRnd)
    // Creator  : 09/03/2022 Wasin
    private function FSaCAPDCalRound($paParams){

        $tOptionRound = '1'; // ปัดขึ้น

        $cAmtV = $paParams['FCXphAmtV'];
        $cAmtNV = $paParams['FCXphAmtNV'];

        $cBath = $cAmtV + $cAmtNV;

        // ตัดเอาเฉพาะทศนิยม
        $nStang = explode('.', number_format($cBath, 2))[1];


        $nPoint = 0;
        $nRound = 0;

        /*====================== ปัดขึ้น ================================*/
        if ($tOptionRound == '1') {
            if ($nStang >= 1 and $nStang < 25) {
                $nPoint = 25;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 25 and $nStang < 50) {
                $nPoint = 50;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 50 and $nStang < 75) {
                $nPoint = 75;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 75 and $nStang < 100) {
                $nPoint = 100;
                $nRound = $nPoint - $nStang;
            }
        }
        /*====================== ปัดขึ้น ================================*/

        /*====================== ปัดลง ================================*/
        if ($tOptionRound != '1') {
            if ($nStang >= 1 and $nStang < 25) {
                $nPoint = 1;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 25 and $nStang < 50) {
                $nPoint = 25;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 50 and $nStang < 75) {
                $nPoint = 50;
                $nRound = $nPoint - $nStang;
            }
            if ($nStang > 75 and $nStang < 100) {
                $nPoint = 75;
                $nRound = $nPoint - $nStang;
            }
        }
        /*====================== ปัดลง ================================*/
        $cAfRound = floatval($cBath) + floatval($nRound / 100);

        /*echo 'Bath: '; echo $cBath; echo '<br>';
        echo 'Point: '; echo $nPoint; echo '<br>';
        echo 'Stang: '; echo $nStang; echo '<br>';
        echo 'Round: '; echo $nRound; echo '<br>';
        echo 'After Round: '; echo $cAfRound;*/

        return [
            'tRoundType' => $tOptionRound,
            'cBath' => $cBath,
            'nPoint' => $nPoint,
            'nStang' => $nStang,
            'nRound' => $nRound,
            'cAfRound' => $cAfRound
        ];
    }

    // Function : เรียกหน้า  Edit
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDEditPage(){
        // Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TFNMRate_L');
        $nLangHave      = FCNnHSizeOf($aLangHave);
        if ($nLangHave > 1) {
            if ($nLangEdit != '') {
                $nLangEdit  = $nLangEdit;
            } else {
                $nLangEdit  = $nLangResort;
            }
        } else {
            if (@$aLangHave[0]->nLangList == '') {
                $nLangEdit  = '1';
            } else {
                $nLangEdit  = $aLangHave[0]->nLangList;
            }
        }
        $tUserLevel = $this->session->userdata('tSesUsrLevel');

        $tDocNo     = $this->input->post('tDocNo');
        $tBchCode   = $this->session->userdata("tSesUsrBchCodeDefault");
        $tSessionID = $this->session->userdata('tSesSessionID');

        $aData = array(
            'FTXphDocNo'    => $tDocNo,
            'FNLngID'       => $nLangEdit,
            'FTUsrCode'     => $this->session->userdata('tSesUsername')
        );

        $aPermission        = FCNaHCheckAlwFunc("docAPDebitnote/0/0");
        // Get Option Show Decimal
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        // Get Option Scan SKU
        $nOptDocSave        = FCNnHGetOptionDocSave();
        // Get Option Scan SKU
        $nOptScanSku        = FCNnHGetOptionScanSku();

        $aUser          = $this->mUser->FSaMUSRByID($aData);
        $aDataUserLogin = $this->APDebitnote_model->FStAPDGetUsrByCode($aData); // Get ข้อมูลสาขา และร้านค้าของ User ที่ login
        $aAPDHD         = $this->APDebitnote_model->FSaMAPDGetHD($aData); // Data TAPTPdHD
        
        $aHDSplParams = [
            'tDocNo' => $aAPDHD['raItems']['FTXphDocNo']
        ];
        $aHDSpl = $this->APDebitnote_model->FSaMAPDGetHDSpl($aHDSplParams); // ข้อมูลผู้จำหน่าย TAPTPdHDSpl

        $aSplParams = [
            'tSplCode' => $aAPDHD['raItems']['FTSplCode']
        ];
        $aSpl = $this->APDebitnote_model->FSaMAPDGetSplVatCode($aSplParams); // ข้อมูลผู้จำหน่าย TCNMSpl

        $aData['FTUsrCode'] = $aAPDHD['raItems']['FTUsrCode'];
        $aUserCreated = $this->mUser->FSaMUSRByID($aData);

        $aData['FTUsrCode'] = $aAPDHD['raItems']['FTXphApvCode'];
        $aUserApv = $this->mUser->FSaMUSRByID($aData);

        $aData['FTBchCode'] = $aAPDHD['raItems']['FTBchCode'];

        $aData['nRow'] = 10000;
        $aData['nPage'] = 1;
        $aData['FTXthDocKey'] = 'TAPTPdHD';

        // Get Data
        if ($aAPDHD['raItems']['FNXphDocType'] == '6') { // ใบลดหนี้มีสินค้า
            $aInsertTmpParams = [
                'tDocNo' => $tDocNo,
                'tBchCode' => $tBchCode,
                'tSessionID' => $tSessionID,
                'tDocKey' => 'TAPTPdHD'
            ];
            $this->APDebitnote_model->FSaMAPDInsertDTToTemp($aInsertTmpParams);
            $this->APDebitnote_model->FSaMAPDInsertDTDisToTmp($aInsertTmpParams);
            $this->APDebitnote_model->FSaMAPDInsertHDDisToTmp($aInsertTmpParams);
        }

        // Move Data HDDocRef To HDRefTemp
        $aDataWhereDocRef   = array(
            'FTXthDocNo'    => $tDocNo,
            'FTXthDocKey'   => 'TAPTPdHD',
            'FNLngID'       => $nLangEdit,
            'nRow'          => 10000,
            'nPage'         => 1,
        );
        $this->APDebitnote_model->FSxMAPDMoveHDRefToHDRefTemp($aDataWhereDocRef);

        $aDataEdit = array(
            'nOptDecimalShow'   => $nOptDecimalShow,
            'nOptDocSave'       => $nOptDocSave,
            'nOptScanSku'       => $nOptScanSku,
            'aResult'           => $aAPDHD,
            'aPermission'       => $aPermission,
            'aUser'             => $aUser,
            'aUserCreated'      => $aUserCreated,
            'aUserApv'          => $aUserApv,
            'aHDSpl'            => $aHDSpl,
            'aSpl'              => $aSpl,
            'tUserCode'         => $aDataUserLogin['FTUsrCode'],
            'tUserName'         => $aDataUserLogin['FTUsrName'],
            'tUserMchCode'      => $aDataUserLogin['FTMerCode'],
            'tUserMchCode'      => $aDataUserLogin['FTMerCode'],
            'tUserMchName'      => $aDataUserLogin['FTMerName'],
            'tUserShpCode'      => $aDataUserLogin['FTShpCode'],
            'tUserShpName'      => $aDataUserLogin['FTShpName'],
            'tUserWahCode'      => $aDataUserLogin['FTWahCode'],
            'tUserWahName'      => $aDataUserLogin['FTWahName'],
            'tUserBchCode'      => $aDataUserLogin['FTBchCode'],
            'tUserBchName'      => $aDataUserLogin['FTBchName'],
            'tUserDptCode'      => $aDataUserLogin['FTDptCode'],
            'tUserDptName'      => $aDataUserLogin['FTDptName']
        );
        $this->load->view('document/apdebitnote/wAPDebitnoteAdd', $aDataEdit);
    }

    // Function : Event Edit Master
    // Creator  : 09/03/2022 Wasin
    public function FSaCAPDEditEvent(){
        try {
            // Get Option Show Decimal  
            $nOptDecimalShow = FCNxHGetOptionDecimalShow();
            $tDocNo         = $this->input->post('oetAPDDocNo');
            $tDocType       = $this->input->post('ohdAPDDocType');
            $tDocDate       = $this->input->post('oetAPDXphDocDate') . " " . $this->input->post('oetAPDDocTime');
            $tUserLevel     = $this->session->userdata('tSesUsrLevel');
            $tLoginDpt      = $this->session->userdata('tSesUsrDptCode');
            $tBchCode       = $this->input->post("oetAPDBchCode"); // ทำรายการได้เฉพาะในสาขาที่เข้าใช้งานเท่านั้น
            $tAgnCode       = $this->input->post("oetAPDAgnCode");
            $tLoginUser     = $this->session->userdata('tSesUsername');
            $tSessionID     = $this->session->userdata('tSesSessionID');
            $tSplVatType    = $this->input->post('ocmAPDXphVATInOrEx');

            $aCalDTTempParams   = [
                'tDocNo'        => $tDocNo,
                'tBchCode'      => $tBchCode,
                'tSessionID'    => $tSessionID,
                'tDocKey'       => 'TAPTPdHD',
                'tSplVatType'   => $tSplVatType
            ];
            $aCalDTTempForHD    = $this->FSaCAPDCalDTTempForHD($aCalDTTempParams);

            $aCalInHDDisTemp    = $this->APDebitnote_model->FSaMAPDCalInHDDisTemp($aCalDTTempParams);

            // TAPTPdHD
            $aDataMaster        = array(
                'tIsAutoGenCode'        => $this->input->post('ocbAPDAutoGenCode'), // ต้องการรัน DocNo อัตโนมัติหรือไม่
                'FTAgnCode'             => $tAgnCode, // รหัสตัวแทนขาย / แฟรนไชส์
                'FTBchCode'             => $tBchCode, // รหัสสาขาที่สร้างเอกสาร
                'FTXphDocNo'            => $tDocNo, // เลขที่เอกสาร
                'FNXphDocType'          => $tDocType, // ประเภทเอกสาร (ใบลดหนี้มีสินค้า หรือ ไม่มีสินค้า)
                'FDXphDocDate'          => $tDocDate, // วันที่สร้างเอกสาร
                'FTShpCode'             => $this->input->post('oetAPDShpCode'), // รหัสร้านค้า
                'FTXphCshOrCrd'         => $this->input->post('ocmAPDXphCshOrCrd'), // ประเภทการชำระเงิน (เงินสด หรือ เชื่อ)
                'FTXphVATInOrEx'        => $tSplVatType, // ประเภทภาษี (แยกนอก หรือ รวมใน)
                'FTDptCode'             => $tLoginDpt, // รหัสแผนกผู้ที่สร้างเอกสาร
                'FTWahCode'             => $this->input->post('oetAPDWahCode'), // รหัสคลังสินค้า
                'FTUsrCode'             => $tLoginUser, // รหัสผู้สร้างเอกสาร
                'FTXphApvCode'          => NULL, // รหัสผู้อนุมัติ
                'FTSplCode'             => $this->input->post('oetAPDSplCode'), // รหัสผู้จำหน่าย
                'FTXphRefExt'           => $this->input->post('oetAPDXphRefExt'), // อ้างอิง เลขที่เอกสาร ภายนอก
                'FDXphRefExtDate'       => empty($this->input->post('oetAPDXphRefExtDate')) ? NULL : $this->input->post('oetAPDXphRefExtDate'), // อ้างอิง วันที่เอกสาร ภายนอก
                'FTXphRefInt'           => $this->input->post('oetAPDRefPICode'), // อ้างอิง เลขที่เอกสาร ภายใน
                'FDXphRefIntDate'       => empty($this->input->post('oetAPDXphRefIntDate')) ? NULL : $this->input->post('oetAPDXphRefIntDate'), // อ้างอิง วันที่เอกสาร ภายใน
                'FTXphRefAE'            => NULL,
                'FNXphDocPrint'         => NULL,
                'FTRteCode'             => NULL,
                'FCXphRteFac'           => NULL,
                'FCXphTotal'            => $aCalDTTempForHD['FCXphTotal'],
                'FCXphTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
                'FCXphTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
                'FCXphTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                'FCXphTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                'FTXphDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                'FCXphDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                'FCXphChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                'FCXphTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                'FCXphTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                'FCXphRefAEAmt'         => NULL,
                'FCXphAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                'FCXphAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXphVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXphVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FTXphWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                'FCXphWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                'FCXphGrand'            => $aCalDTTempForHD['FCXphGrand'],
                'FCXphRnd'              => $aCalDTTempForHD['FCXphRnd'],
                'FTXphGndText'          => $aCalDTTempForHD['FTXphGndText'],
                'FCXphPaid'             => NULL,
                'FCXphLeft'             => NULL,
                'FTXphRmk'              => $this->input->post('otaAPDXphRmk'),
                'FTXphStaRefund'        => NULL,
                'FTXphStaDoc'           => '1', // สถานะเอกสาร (1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก)
                'FTXphStaApv'           => NULL,
                'FTXphStaPrcStk'        => NULL,
                'FTXphStaPaid'          => NULL,
                'FNXphStaDocAct'        => $this->input->post('ocbAPDXphStaDocAct'),
                'FNXphStaRef'           => $this->input->post('ocmAPDXphStaRef'), // สถานะอ้างอิง (0:ไม่เคยอ้างอิง, 1:อ้างอิงบางส่วน, 2:อ้างอิงหมดแล้ว)
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTCreateBy'            => $tLoginUser,
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $tLoginUser
            );

            // TAPTPdHDSpl
            $aDataSpl = array(
                'FTBchCode' => $tBchCode,
                'FTXphDocNo' => $aDataMaster['FTXphDocNo'],
                'FTXphDstPaid' => $this->input->post('ocmAPDHDPcSplXphDstPaid'), // 1:ชำระต้นทาง, 2:ชำระปลายทาง
                'FNXphCrTerm' => $this->input->post('oetAPDHDPcSplXphCrTerm'), // ระยะเครดิต
                'FDXphDueDate' => empty($this->input->post('oetAPDHDPcSplXphDueDate')) ? NULL : $this->input->post('oetAPDHDPcSplXphDueDate'), // วันที่ครบกำหนด
                'FDXphBillDue' => empty($this->input->post('oetAPDHDPcSplXphBillDue')) ? NULL : $this->input->post('oetAPDHDPcSplXphBillDue'), // วันที่จะรับ/วางบิล
                'FTXphCtrName' => $this->input->post('oetAPDHDPcSplXphCtrName'), // ชื่อผู้ตืดต่อ
                'FDXphTnfDate' => empty($this->input->post('oetAPDHDPcSplXphTnfDate')) ? NULL : $this->input->post('oetAPDHDPcSplXphTnfDate'), // วันที่ส่งของ
                'FTXphRefTnfID' => $this->input->post('oetAPDHDPcSplXphRefTnfID'), // เลขที่ ใบขนส่ง
                'FTXphRefVehID' => $this->input->post('oetAPDHDPcSplXphRefVehID'), // อ้างอิง เลขที่ ยานพาหนะ ขนส่ง
                'FTXphRefInvNo' => $this->input->post('oetAPDHDPcSplXphRefInvNo'), // เลขที่บัญชีราคาสินค้า
                'FTXphQtyAndTypeUnit' => $this->input->post('oetAPDHDPcSplXphQtyAndTypeUnit'), // จำนวนและลักษณะหีบห่อ
                'FNXphShipAdd' => NULL, // อ้างอิง ที่อยู่ ส่งของ
                'FNXphTaxAdd' => NULL, // อ้างอิง ที่อยู่ออกใบกำกับภาษี
            );

            // เตรียมข้อมูลสำหรับ HD ใบลดหนี้ไม่มีสินค้า
            $tPdtCode = $this->input->post('tPdtCode');
            $tPdtName = $this->input->post('tPdtName');
            $tCalEndOfBillNonePdt = $this->input->post('tCalEndOfBillNonePdt');
            $aCalEndOfBillNonePdt = json_decode($tCalEndOfBillNonePdt, true);

            if ($aDataMaster['FNXphDocType'] == '7') { // ใบลดหนี้ไม่มีสินค้า
                $aDataMaster['FCXphTotal'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวม
                $aDataMaster['FCXphTotalNV'] = NULL; // ยอดรวมสินค้าไม่มีภาษี
                $aDataMaster['FCXphTotalNoDis'] = NULL; // ยอดรวมสินค้าห้ามลด
                $aDataMaster['FCXphTotalB4DisChgV'] = NULL; // ยอมรวมสินค้าลดได้ และมีภาษี
                $aDataMaster['FCXphTotalB4DisChgNV'] = NULL; // ยอมรวมสินค้าลดได้ และไม่มีภาษี
                $aDataMaster['FTXphDisChgTxt'] = NULL; // ข้อความมูลค่าลดชาร์จ
                $aDataMaster['FCXphDis'] = NULL; // มูลค่ารวมส่วนลด
                $aDataMaster['FCXphChg'] = NULL; // มูลค่ารวมส่วนชาร์จ
                $aDataMaster['FCXphTotalAfDisChgV'] = NULL; // ยอดรวมหลังลด และมีภาษี
                $aDataMaster['FCXphTotalAfDisChgNV'] = NULL; // ยอดรวมหลังลด และไม่มีภาษี
                $aDataMaster['FCXphAmtV'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวมเฉพาะภาษี
                $aDataMaster['FCXphAmtNV'] = NULL; // ยอดรวมเฉพาะไม่มีภาษี
                $aDataMaster['FCXphVat'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']); // ยอดภาษี
                $aDataMaster['FCXphVatable'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']); // ยอดแยกภาษี
                $aDataMaster['FTXphWpCode'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tVatCode']); // รหัสอัตราภาษี ณ ที่จ่าย
                $aDataMaster['FCXphWpTax'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']); // ภาษีหัก ณ ที่จ่าย

                $aDataMaster['FCXphGrand'] = FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']); // ยอดรวม
                $aDataMaster['FCXphRnd'] = NULL; // ยอดปัดเศษ
                $aDataMaster['FTXphGndText'] = number_format($aDataMaster['FCXphGrand'], $nOptDecimalShow); // ข้อความ ยอดรวมสุทธิ

                // เตรียมข้อมูลสำหรับ DT ใบลดหนี้ไม่มีสินค้า
                $aDataDT = [
                    'FTBchCode'         => $aDataMaster['FTBchCode'], // สาขาสร้าง
                    'FTXphDocNo'        => $aDataMaster['FTXphDocNo'], // เลขที่เอกสาร
                    'FNXpdSeqNo'        => 1, // ลำดับ
                    'FTPdtCode'         => $tPdtCode, // รหัสสินค้า
                    'FTXpdPdtName'      => $tPdtName, // ชื่อสินค้า
                    'FCXpdFactor'       => 1, // อัตราส่วนต่อหน่วย
                    'FTXpdVatType'      => $tSplVatType, // ประเภทภาษี 1:มีภาษี, 2:ไม่มีภาษี
                    'FTVatCode'         => $aCalEndOfBillNonePdt['tVatCode'], // รหัสภาษี ณ. ซื้อ
                    'FCXpdVatRate'      => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['nVatrate']), // อัตราภาษี ณ. ซื้อ
                    'FCXpdQty'          => 1, // จำนวนชื้น ตาม หน่วย
                    'FCXpdQtyAll'       => 1, // จำนวนรวมหน่วยเล็กสุด
                    'FCXpdSetPrice'     => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // ราคาซื้อ ตาม หน่วย * อัตราแลกเปลี่ยน
                    'FCXpdAmtB4DisChg'  => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // มูลค่ารวมก่อนลด
                    'FCXpdNet'          => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['tValue']), // มูลค่าสุทธิก่อนท้ายบิล
                    'FCXpdNetAfHD'      => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cTotalValue']), // มูลค่าสุทธิหลังท้ายบิล
                    'FCXpdVat'          => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat']), // มูลค่าภาษี
                    'FCXpdVatable'      => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']), // มูลค่าแยกภาษี
                    'FCXpdCostIn'       => floatval(FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVat'])) + floatval(FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable'])), // ต้นทุนรวมใน 
                    'FCXpdCostEx'       => FCNcUnFormatMoneyToFloat($aCalEndOfBillNonePdt['cVatable']), // ต้นทุนแยกนอก
                    'FTXpdRmk'          => 'ใบลดหนี้ไม่มีสินค้า', // หมายเหตุรายการ
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $tLoginUser,
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $tLoginUser
                ];
            }

            $aParams = array(
                'tSessionID'    => $tSessionID,
                'tDocNo'        => $aDataMaster['FTXphDocNo'],
                'tBchCode'      => $tBchCode,
                'tDocKey'       => 'TAPTPdHD',
                'tIsUpdatePage' => '1'
            );

            $this->db->trans_begin();

            /*======================= Begin Data Process =====================*/

            if ($tDocType == '6') { // ใบลดหนี้แบบมีสินค้า
                $this->APDebitnote_model->FSaMAPDAddUpdateHDHavePdt($aDataMaster);
                $this->APDebitnote_model->FSaMAPDInsertTmpToDT($aParams);
                $this->APDebitnote_model->FSaMAPDInsertTmpToDTDis($aParams);
                $this->APDebitnote_model->FSaMAPDInsertTmpToHDDis($aParams);
            }

            if ($tDocType == '7') { // ใบลดหนี้แบบไม่มีสินค้า
                $this->APDebitnote_model->FSaMAPDAddUpdateHDNonePdt($aDataMaster);
                $this->APDebitnote_model->FSaMAPDAddUpdateDTNonePdt($aDataDT);
            }

            $this->APDebitnote_model->FSaMAPDAddUpdatePCHDSpl($aDataSpl);


            /*========================= End Data Process =====================*/
            
            /*======================= Begin Data Process Doc Ref =====================*/
            $aDataWhereDocRef   = array(
                'FTBchCode'         => $tBchCode,
                'FTAgnCode'         => $tAgnCode,
                'FTXphDocNo'        => $aDataMaster['FTXphDocNo'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $aDataMaster['FTCreateBy'],
                'FTLastUpdBy'       => $aDataMaster['FTLastUpdBy'],
                'FTSessionID'       => $this->input->post('ohdSesSessionID'),
                'FTXthVATInOrEx'    => $tSplVatType
            );
            // Array Data Table Document
            $aTableAddUpdate    = array(
                'tTableHD'      => 'TAPTPdHD',
                'tTableHDDis'   => 'TAPTPdHDDis',
                'tTableHDSpl'   => 'TAPTPdHDSpl',
                'tTableDT'      => 'TAPTPdDT',
                'tTableDTDis'   => 'TAPTPdDTDis',
                'tTableStaGen'  => 2,
            );
            $this->APDebitnote_model->FSxMAPDMoveHDRefTmpToHDRef($aDataWhereDocRef, $aTableAddUpdate);
            /*======================= End Data Process Doc Ref =====================*/


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'    => $aDataMaster['FTXphDocNo'],
                    'nStaEvent'        => '1',
                    'tStaMessg'        => 'Success Add'
                );
            }

            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aReturn));
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    // Function : Function Call DataTables List Master
    // Creator  : 09/03/2022 Wasin
    public function FSxCAPDDataTable(){
        $tAdvanceSearch = $this->input->post('tAdvanceSearch');
        $nPage = $this->input->post('nPageCurrent');
        // Controle Event
        $aAlwEvent = FCNaHCheckAlwFunc('creditNote/0/0');
        // Get Option Show Decimal
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }
        // Lang ภาษา
        $nLangResort = $this->session->userdata("tLangID");
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FNLngID' => $nLangEdit,
            'nPage' => $nPage,
            'nRow' => 10,
            'aAdvanceSearch' => json_decode($tAdvanceSearch, true)
        );
        $aResList = $this->APDebitnote_model->FSaMAPDList($aData);

        $aGenTable = array(
            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'nOptDecimalShow' => $nOptDecimalShow
        );
        $this->load->view('document/apdebitnote/wAPDebitnoteDataTable', $aGenTable);
    }

    // Function : Adv Table Load Data
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDPdtAdvTblLoadData(){
        $tSearchAll     = $this->input->post('tSearchAll');
        $tDocNo         = $this->input->post('tDocNo');
        $tStaApv        = $this->input->post('tStaApv');
        $tStaDoc        = $this->input->post('tStaDoc');
        $tSplVatType    = $this->input->post('tSplVatType');
        $nPage          = $this->input->post('nPageCurrent');
        $aDataWhere = array(
            'tSearchAll'    => $tSearchAll,
            'tDocNo'        => $tDocNo,
            'tDocKey'       => 'TAPTPdHD',
            'nPage'         => $nPage,
            'nRow'          => 10,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );

        // Edit in line
        $tPdtCode   = $this->input->post('ptPdtCode');
        $tPunCode   = $this->input->post('ptPunCode');

        // Get Option Show Decimal
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aColumnShow        = FCNaDCLGetColumnShow('TAPTPdDT');

        // Calcurate Document DT Temp Array Parameter
        $aCalcDTParams = [
            'tDataDocEvnCall'   => '1',
            'tDataVatInOrEx'    => $tSplVatType,
            'tDataDocNo'        => $tDocNo,
            'tDataDocKey'       => 'TAPTPdHD',
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        $aEndOfBillParams = [
            'tSplVatType'   => $tSplVatType,
            'tDocNo'        => $tDocNo,
            'tDocKey'       => 'TAPTPdHD',
            'nLngID'        => FCNaHGetLangEdit(),
            'tSesSessionID' => $this->session->userdata('tSesSessionID'),
            'tBchCode'      => $this->input->post('tBchCode')
        ];

        // คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล supawat 03-04-2020 
        $aEndOfBill['aEndOfBillVat']    = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
        $aEndOfBill['aEndOfBillCal']    = FCNaDOCEndOfBillCal($aEndOfBillParams);
        $aEndOfBill['tTextBath']        = FCNtNumberToTextBaht($aEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

        $aPackDataCalCulate = array(
            'tDocNo'        => $tDocNo,
            'tBchCode'      => '',
            'nB4Dis'        => $aEndOfBill['aEndOfBillCal']['cSumFCXtdNet'],
            'tSplVatType'   => $tSplVatType
        );
        $tCalculateAgain = FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);

        if ($tCalculateAgain == 'CHANGE') {
            $aCalcDTParams = [
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tSplVatType,
                'tDataDocNo'        => $tDocNo,
                'tDataDocKey'       => 'TAPTPdHD',
                'tDataSeqNo'        => ''
            ];
            $aStaCalcDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTParams);
            if ($aStaCalcDTTemp === TRUE) {
                FCNaHCalculateProrate('TAPTPdHD', $aPackDataCalCulate['tDocNo']);
                FCNbHCallCalcDocDTTemp($aCalcDTParams);
            }
            $aEndOfBill['aEndOfBillVat']    = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aEndOfBill['aEndOfBillCal']    = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aEndOfBill['tTextBath']        = FCNtNumberToTextBaht($aEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
        }

        $aDataDT    = $this->APDebitnote_model->FSaMAPDGetDTTempListPage($aDataWhere);
        $aDataDTSum = $this->APDebitnote_model->FSaMAPDSumDTTemp($aDataWhere);
        
        $aData['nOptDecimalShow']   = $nOptDecimalShow;
        $aData['aColumnShow']       = $aColumnShow;
        $aData['tPdtCode']          = $tPdtCode;
        $aData['tPunCode']          = $tPunCode;
        $aData['aDataDT']           = $aDataDT;
        $aData['aDataDTSum']        = $aDataDTSum;
        $aData['tStaApv']           = $tStaApv;
        $aData['tStaDoc']           = $tStaDoc;
        $aData['nPage']             = $nPage;

        $tTableHtml = $this->load->view('document/apdebitnote/advancetable/wAPDebitnotePdtAdvTableData', $aData, true);

        $aResult['tTalbleHtml'] = $tTableHtml;
        $aResult['aEndOfBill'] = $aEndOfBill;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aResult));
    }

    // Function : Adv Table Load Data
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDNonePdtAdvTblLoadData(){
        $tSearchAll     = $this->input->post('tSearchAll');
        $tDocNo         = $this->input->post('tDocNo');
        $tSplVatType    = $this->input->post('tSplVatType');
        $tStaApv        = $this->input->post('tStaApv');
        $tStaDoc        = $this->input->post('tStaDoc');
        $nPage          = $this->input->post('nPageCurrent');
        $aDataWhere = array(
            'tSearchAll'    => $tSearchAll,
            'tDocNo'        => $tDocNo,
            'tDocKey'       => 'TAPTPdHD',
            'nPage'         => $nPage,
            'nRow'          => 10,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );

        // Edit in line
        $tPdtCode   = $this->input->post('ptPdtCode');
        $tPunCode   = $this->input->post('ptPunCode');

        // Get Option Show Decimal
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

        $aColumnShow        = FCNaDCLGetColumnShow('TAPTPdDT');
        $aTSysPdtParams     = [
            'tPdtCode'      => 'Debit Note',
            'tPdtSysTable'  => 'TAPTPdHD',
            'nLngID'        => FCNaHGetLangEdit()
        ];
        $aDataDT        = FCNoHDOCGetTSysPdt($aTSysPdtParams);
        $aDataDTSum     = $this->APDebitnote_model->FSaMAPDSumDTTemp($aDataWhere);

        $aData['nOptDecimalShow']   = $nOptDecimalShow;
        $aData['aColumnShow']       = $aColumnShow;
        $aData['tPdtCode']          = $tPdtCode;
        $aData['tPunCode']          = $tPunCode;
        $aData['oDataDT']           = $aDataDT;
        $aData['aDataDTSum']        = $aDataDTSum;
        $aData['tStaApv']           = $tStaApv;
        $aData['tStaDoc']           = $tStaDoc;
        $aData['nPage']             = $nPage;

        $aDTNontPdtParams = [
            'tDocNo'    => $tDocNo
        ];
        $aDataDTNonePdt             = $this->APDebitnote_model->FSaMAPDGetDTNonePdt($aDTNontPdtParams); // Data TAPTPdDT
        $aData['aDataDTNonePdt']    = $aDataDTNonePdt;

        $tTableHtml = $this->load->view('document/apdebitnote/advancetable/wAPDebitnoteNonePdtAdvTableData', $aData, true);

        $aEndOfBillParams = [
            'tDocNo'        => $tDocNo,
            'tSplVatType'   => $tSplVatType,
            'tDocKey'       => 'TAPTPdHD',
            'nLngID'        => FCNaHGetLangEdit(),
            'tSesSessionID' => $this->session->userdata('tSesSessionID'),
            'tBchCode'      => $this->session->userdata('tSesUsrBchCodeDefault')
        ];
        $aEndOfBill['aEndOfBillVat']    = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
        $aEndOfBill['aEndOfBillCal']    = FCNaDOCEndOfBillCal($aEndOfBillParams);
        $aEndOfBill['tTextBath']        = FCNtNumberToTextBaht($aEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
        $aResult['tTalbleHtml']         = $tTableHtml;
        $aResult['aEndOfBill']          = $aEndOfBill;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aResult));
    }

    // Function : Adv Table Save
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDShowColSave(){

        FCNaDCLSetShowCol('TAPTPdDT', '', '');

        $aColShowSet = $this->input->post('aColShowSet');
        $aColShowAllList = $this->input->post('aColShowAllList');
        $aColumnLabelName = $this->input->post('aColumnLabelName');
        $nStaSetDef = $this->input->post('nStaSetDef');

        if ($nStaSetDef == 1) {
            FCNaDCLSetDefShowCol('TAPTPdDT');
        } else {
            for ($i = 0; $i < FCNnHSizeOf($aColShowSet); $i++) {
                FCNaDCLSetShowCol('TAPTPdDT', 1, $aColShowSet[$i]);
            }
        }

        // Reset Seq
        FCNaDCLUpdateSeq('TAPTPdDT', '', '', '');
        $q = 1;
        for ($n = 0; $n < FCNnHSizeOf($aColShowAllList); $n++) {
            FCNaDCLUpdateSeq('TAPTPdDT', $aColShowAllList[$n], $q, $aColumnLabelName[$n]);
            $q++;
        }
    }

    // Function : Adv Table Show
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDAdvTblShowColList(){
        $aAvailableColumn = FCNaDCLAvailableColumn('TAPTPdDT');
        $aData['aAvailableColumn'] = $aAvailableColumn;
        $this->load->view('document/apdebitnote/advancetable/wPurchaseTableShowColList', $aData);
    }

    // Function : ค้นหา รายการ
    public function FSxCAPDFormSearchList(){

        // Lang ภาษา
        $nLangResort = $this->session->userdata("tLangID");
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aLangHave = FCNaHGetAllLangByTable('TCNMBranch_L');
        $nLangHave = FCNnHSizeOf($aLangHave);

        if ($nLangHave > 1) {
            if ($nLangEdit != '') {
                $nLangEdit = $nLangEdit;
            } else {
                $nLangEdit = $nLangResort;
            }
        } else {
            if (@$aLangHave[0]->nLangList == '') {
                $nLangEdit = '1';
            } else {
                $nLangEdit = $aLangHave[0]->nLangList;
            }
        }

        $aData  = array(
            'FTBchCode' => $this->session->userdata("tSesUsrBchCodeDefault"),
            'FTShpCode'    => '',
            'nPage' => 1,
            'nRow' => 9999,
            'FNLngID' => $nLangEdit,
            'tSearchAll' => ''
        );

        $aBchData = $this->mBranch->FSnMBCHList($aData);
        $aShpData = $this->mShop->FSaMSHPList($aData);

        $aDataMaster = array(
            'aBchData' => $aBchData,
            'aShpData' => $aShpData
        );

        $this->load->view('document/apdebitnote/wAPDebitnoteFormSearchList', $aDataMaster);
    }

    
    // Function : Multi Pdt Delete In Temp
    // Creator  : 09/03/2022 Wasin
    public function FSvCAPDPdtMultiDeleteEvent(){
        $tDocNo     = $this->input->post('tDocNo');
        $tPdtCode   = $this->input->post('tPdtCode');
        $tPunCode   = $this->input->post('tPunCode');
        $aSeqCode   = $this->input->post('tSeqCode');
        $tSession   = $this->session->userdata('tSesSessionID');
        $nCount     = FCNnHSizeOf($aSeqCode);
        if ($nCount > 1) {
            for ($i = 0; $i < $nCount; $i++) {
                $aDataMaster    = array(
                    'tDocNo'        => $tDocNo,
                    'nSeqNo'        => $aSeqCode[$i],
                    'tDocKey'       => 'TAPTPdHD',
                    'tSessionID'    => $tSession
                );
                $aResDel    = $this->APDebitnote_model->FSaMAPDPdtTmpMultiDel($aDataMaster);
            }
        } else {
            $aDataMaster    = array(
                'tDocNo'        => $tDocNo,
                'nSeqNo'        => $aSeqCode[0],
                'tDocKey'       => 'TAPTPdHD',
                'tSessionID'    => $tSession
            );
            $aResDel    = $this->APDebitnote_model->FSaMAPDPdtTmpMultiDel($aDataMaster);
        }
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    
    // Function : Approve Doc
    // Creator  : 18/03/2022 Wasin
    public function FSvCAPDApprove(){
        $tDocNo         = $this->input->post('tDocNo');
        $tStaApv        = $this->input->post('tStaApv');
        $tDocType       = $this->input->post('tDocType');
        $tBchCode       = $this->input->post('tBchCode');
        $aDataUpdate    = array(
            'tDocNo'    => $tDocNo,
            'tApvCode'  => $this->session->userdata('tSesUsername')
        );
        if ($tDocType == '6') { // ใบลดหนี้มีสินค้า
            $this->db->trans_begin();
            $aStaApv     = $this->APDebitnote_model->FSaMAPDHavePdtApprove($aDataUpdate);
            $tUsrBchCode    = $tBchCode;
            try {
                $aMQParams = [
                    "queueName" => "AP_QDocApprove",
                    "params"    => [
                        'ptFunction'    => 'TAPTPdHD',
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => '',
                        'ptData'        => json_encode([
                            "ptBchCode"     => $tUsrBchCode,
                            "ptDocNo"       => $tDocNo,
                            "ptDocType"     => 2,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];
                // เชื่อม Rabbit MQ
                FCNxCallRabbitMQ($aMQParams);
                $this->db->trans_commit();
            } catch (\ErrorException $err) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => language('common/main/main', 'tApproveFail')
                );
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aReturn));
                return;
            }
        }
        if ($tDocType == '7') { // ใบลดหนี้ไม่มีสินค้า
            $aStaApv    = $this->APDebitnote_model->FSaMAPDNonePdtApprove($aDataUpdate);
        }
    }

    // Function : Cancel Doc
    // Creator  : 18/03/2022 Wasin
    public function FSvCAPDCancel(){
        $tDocNo         = $this->input->post('tDocNo');
        $aDataUpdate    = array(
            'tDocNo' => $tDocNo,
        );
        $aStaApv    = $this->APDebitnote_model->FSaMAPDCancel($aDataUpdate);
        if ($aStaApv['rtCode'] == 1) {
            $aApv = array(
                'nSta'  => 1,
                'tMsg'  => "Cancel done.",
            );
        } else {
            $aApv = array(
                'nSta'  => 2,
                'tMsg'  => "Not Cancel.",
            );
        }
        echo json_encode($aApv);
    }

    // Function : Doc credit note code unique check
    // Creator  : 18/03/2022 Wasin
    public function FStCAPDUniqueValidate($tSelect = ''){
        if ($this->input->is_ajax_request()) { // Request check
            if ($tSelect == 'docAPDCode') {
                $tAPDDocCode    = $this->input->post('tAPDCode');
                $oAPDDoc        = $this->APDebitnote_model->FSnMAPDCheckDuplicate($tAPDDocCode);
                $tStatus        = 'false';
                if ($oAPDDoc[0]->counts > 0) { // If have record
                    $tStatus = 'true';
                }
                echo $tStatus;
            }
            echo 'Param not match.';
        } else {
            echo 'Method Not Allowed';
        }
    }

    // Function : Cal End Of Bill ใบลดหนี้ไม่สินค้า
    // Creator  : 18/03/2022 Wasin
    public function FSoCAPDCalEndOfBillNonePdt(){
        // Get Option Show Decimal  
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $tSplVatType        = $this->input->post('tSplVatType');
        $tVatCode           = $this->input->post('tVatCode');
        $tValue             = empty($this->input->post('tValue')) ? 0 : $this->input->post('tValue');
        $oVatActive         = FCNoHVatActiveList($tVatCode);
        $aCalEndOfBill      = [];
        if (empty($oVatActive)) {
            return;
        }
        try {

            /** ======================== Begin Process ==========================*/
            $cVatrate = $oVatActive->FCVatRate; // อัตราภาษี

            if ($tSplVatType == '1') { // ภาษีรวมใน
                $cCalVat = floatval($tValue) - ((floatval($tValue) * 100) / (100 + floatval($cVatrate))); // คำนวณภาษี
            }

            if ($tSplVatType == '2') { // ภาษีแยกนอก
                $cCalVat = ((floatval($tValue) * (100 + floatval($cVatrate)) / 100)) - floatval($tValue); // คำนวณภาษี
            }

            $tVatrateText = number_format($cVatrate) . '%'; // อัตราภาษี
            $cVat = floatval($cCalVat); // ภาษี
            $cValue = floatval($tValue); // มูลค่า
            $cVatable = floatval($tValue) - floatval($cCalVat);

            $cCostIn = floatval($cCalVat) + floatval($cVatable);
            $cCostEx = floatval($cVatable);

            $cTotalValueVatEx = floatval($cValue) + floatval($cCalVat); // มูลค่าหลังรวมภาษี ภาษีแยกนอก
            $cTotalValueVatIn = floatval($cValue); // มูลค่าหลังรวมภาษี ภาษีรวมใน

            $cTotalValue = 0.00;
            if ($tSplVatType == '1') { // ภาษีรวมใน
                $cTotalValue = $cTotalValueVatIn;
            }

            if ($tSplVatType == '2') { // ภาษีแยกนอก
                $cTotalValue = $cTotalValueVatEx;
            }

            $tTotalValueText = FCNtNumberToTextBaht(number_format($cTotalValue, $nOptDecimalShow));

            $aCalEndOfBill['tSplVatType']       = $tSplVatType; // ประเภทภาษี
            $aCalEndOfBill['tVatCode']          = $tVatCode; // รหัสภาษี
            $aCalEndOfBill['cVat']              = number_format($cVat, $nOptDecimalShow);
            $aCalEndOfBill['nVatrate']          = number_format($cVatrate); // อัตราภาษี
            $aCalEndOfBill['tVatrateText']      = $tVatrateText;
            $aCalEndOfBill['cTotalValue']       = number_format($cTotalValue, $nOptDecimalShow);
            $aCalEndOfBill['tValue']            = number_format($cValue, $nOptDecimalShow); // มูลค่า
            $aCalEndOfBill['cVatable']          = number_format($cVatable, $nOptDecimalShow); // มูลค่าแยกภาษี
            $aCalEndOfBill['tTotalValueText']   = $tTotalValueText;
            $aCalEndOfBill['cCostIn']           = number_format($cCostIn, $nOptDecimalShow); // ต้นทุนรวมใน
            $aCalEndOfBill['cCostEx']           = number_format($cCostEx, $nOptDecimalShow); // ต้นทุนแยกนอก
            /** ======================== End Process ============================*/
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aCalEndOfBill));
        } catch (\ErrorException $err) {
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode('{}'));
        }
    }

    // Function : Multi Delete Doc
    // Creator  : 18/03/2022 Wasin
    public function FSoCAPDDeleteMultiDoc(){
        $aDocNo = $this->input->post('aDocNo');
        $this->db->trans_begin();
        foreach ($aDocNo as $aItem) {
            $aDelMasterParams = [
                'tDocNo' => trim($aItem)
            ];
            $this->APDebitnote_model->FSaMAPDDelMaster($aDelMasterParams);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aStatus    = array(
                'rtCode'    => '905',
                'rtDesc'    => 'Cannot Delete Item.',
            );
        } else {
            $this->db->trans_commit();
            $aStatus    = array(
                'rtCode'    => '1',
                'rtDesc'    => 'Delete Complete.',
            );
        }
        return json_encode($aStatus);
    }


    // Function : Delete Doc
    // Creator  : 18/03/2022 Wasin
    public function FSoCAPDDeleteDoc(){
        $tDocNo = $this->input->post('tDocNo');
        $this->db->trans_begin();
        $aDelMasterParams = [
            'tDocNo'    => trim($tDocNo)
        ];
        $this->APDebitnote_model->FSaMAPDDelMaster($aDelMasterParams);
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
        return json_encode($aStatus);
    }

    // Function : Clear Temp
    // Creator  : 18/03/2022 Wasin
    public function FSaCAPDClearTemp(){
        $this->db->trans_begin();
        /*======================= Begin Data Process =====================*/
        $this->APDebitnote_model->FSxMClearPdtInTmp();
        $this->APDebitnote_model->FSxMClearDTDisTmp();
        $this->APDebitnote_model->FSxMClearHDDisTmp();
        /*========================= End Data Process =====================*/
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Unsucess Clear Temp"
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                'nStaEvent' => '1',
                'tStaMessg' => 'Success Clear Temp'
            );
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    // Function : SPL จะส่งผล ให้เกิดการคำนวณ VAT ใหม่
    // Creator  : 18/03/2022 Wasin
    public function FSoCAPDChangeSPLAffectNewVAT(){
        $tCNDocNo       = $this->input->post('tCNDocNo');
        $tBCHCode       = $this->input->post('tBCHCode');
        $tVatCode       = $this->input->post('tVatCode');
        $tVatRate       = $this->input->post('tVatRate');
        $aItem = [
            'tCNDocNo'      => $tCNDocNo,
            'tBCHCode'      => $tBCHCode,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
            'tDocKey'       => 'TAPTPdHD',
            'FTVatCode'     => $tVatCode,
            'FCXtdVatRate'  => $tVatRate
        ];
        $this->APDebitnote_model->FSaMCNChangeSPLAffectNewVAT($aItem);
    }


    //===============================================================================================










    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCAPDCallRefIntDoc(){
        $tDocType   = $this->input->post('tDocType');
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName,
            'tDocType' => $tDocType,
        );
        $this->load->view('document/apdebitnote/refintdocument/wAPDebitnoteRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCAPDCallRefIntDocDataTable(){
        $nPage                  = $this->input->post('nAPDRefIntPageCurrent');
        $tAPDRefIntBchCode      = $this->input->post('tAPDRefIntBchCode');
        $tAPDRefIntDocNo        = $this->input->post('tAPDRefIntDocNo');
        $tAPDRefIntDocDateFrm   = $this->input->post('tAPDRefIntDocDateFrm');
        $tAPDRefIntDocDateTo    = $this->input->post('tAPDRefIntDocDateTo');
        $tAPDRefIntStaDoc       = $this->input->post('tAPDRefIntStaDoc');
        $tAPDDocType            = $this->input->post('tAPDDocType');
        $tAPDSplCode            = $this->input->post('tAPDSplCode');

        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage  = 1;
        } else {
            $nPage  = $this->input->post('nAPDRefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aDataParamFilter   = array(
            'tAPDRefIntBchCode'     => $tAPDRefIntBchCode,
            'tAPDRefIntDocNo'       => $tAPDRefIntDocNo,
            'tAPDRefIntDocDateFrm'  => $tAPDRefIntDocDateFrm,
            'tAPDRefIntDocDateTo'   => $tAPDRefIntDocDateTo,
            'tAPDRefIntStaDoc'      => $tAPDRefIntStaDoc,
            'tAPDSplCode'           => $tAPDSplCode
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $aDataParamFilter
        );

        if($tAPDDocType == 1) {
            // ใบซื้อสินค้า INVOICE
            $aDataParam = $this->APDebitnote_model->FSoMAPDCallRefPIIntDocDataTable($aDataCondition);
        }else{
            // ใบรับของ TXO 
            $aDataParam = $this->APDebitnote_model->FSoMAPDCallRefTXOIntDocDataTable($aDataCondition);
        }

        $aConfigView = array(
            'nPage'         => $nPage,
            'aDataList'     => $aDataParam,
            'tAPDDocType'   => $tAPDDocType
        );

        $this->load->view('document/apdebitnote/refintdocument/wAPDebitnoteRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCAPDCallRefIntDocDetailDataTable(){
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $tBchCode   = $this->input->post('ptBchCode');
        $tDocNo     = $this->input->post('ptDocNo');
        $tDocType   = $this->input->post('ptdoctype');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition     = array(
            'FNLngID' => $nLangEdit,
            'tBchCode' => $tBchCode,
            'tDocNo' => $tDocNo
        );
        if ($tDocType == 1) {
            // ใบซื้อสินค้า INVOICE
            $aDataParam = $this->APDebitnote_model->FSoMAPDCallRefPIIntDocDTDataTable($aDataCondition);
        }else{
            // ใบรับของ TXO
            $aDataParam = $this->APDebitnote_model->FSoMAPDCallRefTWOIntDocDTDataTable($aDataCondition);
        }
        $aConfigView    = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow,
            'tAPDDocType'       => $tDocType
        );

        $this->load->view('document/apdebitnote/refintdocument/wAPDebitnoteRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCAPDCallRefIntDocInsertDTToTemp(){
        $tAPDDocNo          = $this->input->post('tAPDDocNo');
        $tAPDFrmBchCode     = $this->input->post('tAPDFrmBchCode');
        $tRefIntDocNo       = $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     = $this->input->post('tRefIntBchCode');
        $aSeqNo             = $this->input->post('aSeqNo');
        $tDoctype           =  $this->input->post('tDoctype');
        $tAPDOptionAddPdt   =  $this->input->post('tAPDOptionAddPdt');

        $aDataParam = array(
            'tAPDDocNo'         => $tAPDDocNo,
            'tAPDFrmBchCode'    => $tAPDFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'TAPTPdHD',
            'tAPDOptionAddPdt'  => $tAPDOptionAddPdt,
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
        );

        if ($tDoctype == 1) {
            $tDocType       = 'PI';
            $aDataResult    = $this->APDebitnote_model->FSoMAPDCallRefIntDocInsertDTToTemp($aDataParam, $tDocType);
        }else{
            $tDocType       = 'TWO';
            $aDataResult    = $this->APDebitnote_model->FSoMAPDCallRefIntTWODocInsertDTToTemp($aDataParam, $tDocType);
        }
        return  $aDataResult;
    }

    // ===============================================================================================
    
    // Function : อ้างอิงเอกสาร - โหลดข้อมูล ( Ver ใหม่ )
    // Creator  : 10/03/2022 Wasin
    public function FSoCAPDPageHDDocRef(){
        try {
            $tDocNo     = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TAPTPdHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXshDocNo'        => $tDocNo,
                'FTXshDocKey'       => 'TAPTPdHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef  = $this->APDebitnote_model->FSaMAPDGetDataHDRefTmp($aDataWhere);
            $aDataConfig    = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/apdebitnote/wAPDebitnoteDocRef', $aDataConfig, true);
            $aReturnData = array(
                'tViewPageHDRef'    => $tViewPageHDRef,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Function : ค่าอ้างอิงเอกสาร - เพิ่ม หรือ เเก้ไข
    // Creator  : 10/03/2022 Wasin
    public function FSoCAPDEventAddEditHDDocRef(){
        try {
            $nRefInOrOutType    = $this->input->post('ptRefType');
            $FTXthRefKey        = ($nRefInOrOutType == '1')? $this->input->post('tDocRefTypeIn') : $this->input->post('ptRefKey');
            $aDataWhere         = [
                'FTXshDocNo'        => $this->input->post('ptAPDDocNo'),
                'FTXshDocKey'       => 'TAPTPdHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tAPDRefDocNoOld'   => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit   = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $FTXthRefKey,
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData    = $this->APDebitnote_model->FSaMAPDAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
    }

    // Function : ค่าอ้างอิงเอกสาร - ลบ
    // Creator  : 10/03/2022 Wasin
    public function FSoCAPDEventDelHDDocRef(){
        try {
            $aData = [
                'FTXshDocNo'        => $this->input->post('ptDocNo'),
                'FTXshRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXshDocKey'       => 'TAPTPdHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->APDebitnote_model->FSaMAPDDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

}
