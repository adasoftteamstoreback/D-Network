<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Quotation_controller extends MX_Controller {

    public $tRouteMenu = 'docQuotation/0/0';

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('document/quotation/Quotation_model');
        parent::__construct();
    }

    public function index($nQTBrowseType, $tQTBrowseOption) {
        $aData['nBrowseType']       = $nQTBrowseType;
        $aData['tBrowseOption']     = $tQTBrowseOption;
		$aData['aPermission']       = FCNaHCheckAlwFunc($this->tRouteMenu);
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML($this->tRouteMenu);

        //เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
        $aCookieMenuCode = array(
            'name'	=> 'tMenuCode',
            'value' => json_encode('AR0003'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuCode);

        $aCookieMenuName = array(
            'name'	=> 'tMenuName',
            'value' => json_encode('ใบเสนอราคา'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuName);
        //end

        

        $this->load->view('document/quotation/wQuotation',$aData);
    }

    //List
    public function FSvCQTFormSearchList(){
        $this->load->view('document/quotation/wQuotationSearchList');
    }

    //ตารางข้อมูล (HD)
    public function FSvCQTDataTable(){
        $tAdvanceSearchData     = $this->input->post('oAdvanceSearch');
        $nPage                  = $this->input->post('nPageCurrent');
        $aAlwEvent              = FCNaHCheckAlwFunc($this->tRouteMenu);
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

        $aList      = $this->Quotation_model->FSaMQTList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );

        $tViewDataTable = $this->load->view('document/quotation/wQuotationDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCQTAddPage(){
        try{

            //ล้างค่าใน Temp
            $this->Quotation_model->FSaMQTDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
            $nLangEdit          = $this->session->userdata("tLangEdit");
            $aCompData          = $this->mCompany->FSaMCMPList('','',array('FNLngID' => $nLangEdit));

            $aDataConfigViewAdd = array(
                'nStaShwAddress'    => $this->Quotation_model->FSnMQTGetConfigShwAddress(),
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aRateDefault'      => $aCompData,
                'aDataCarCst'       => '',
                'aDataDocHD'        => array('rtCode'=>'99')
            );

            $tViewPageAdd       = $this->load->view('document/quotation/wQuotationPageAdd',$aDataConfigViewAdd,true);
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

    //ตารางสินค้าใน DT Temp
    public function FSvCQTTableDTTemp(){
        try {

            $bStaSession    =   $this->session->userdata('bSesLogIn');
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData = array(
                    'checksession' => 'expire'
                );
                echo json_encode($aReturnData);
                exit;
            }

            $tQTDocNo               = $this->input->post('ptQTDocNo');
            $tTQStaApv              = $this->input->post('ptTQStaApv');
            $tTQStaDoc              = $this->input->post('ptTQStaDoc');
            $tQTVATInOrEx           = $this->input->post('ptTQVATInOrEx');
            $tBCHCode               = $this->input->post('tBCHCode');
            $nOptDecimalShow        = FCNxHGetOptionDecimalShow();

            $aDataWhere = array(
                'FTXthDocNo'            => $tQTDocNo,
                'FTXthDocKey'           => 'TARTSqDT',
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );

            $aDataDocDTTemp         = $this->Quotation_model->FSaMQTGetDocDTTempListPage($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tTQStaApv'         => $tTQStaApv,
                'tTQStaDoc'         => $tTQStaDoc,
                'aDataDocDTTemp'    => $aDataDocDTTemp
            );

            $tTQPdtAdvTableHtml = $this->load->view('document/quotation/wQuotationPdtAdvTableData', $aDataView, true);

            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'   => $tQTVATInOrEx,
                'tDocNo'        => $tQTDocNo,
                'tDocKey'       => 'TARTSqDT',
                'nLngID'        => FCNaHGetLangEdit(),
                'tSesSessionID' => $this->session->userdata('tSesSessionID'),
                'tBchCode'      => $tBCHCode
            );

            //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล
            $aTQEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aTQEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aTQEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aTQEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);

            $aReturnData = array(
                'tTQPdtAdvTableHtml'    => $tTQPdtAdvTableHtml,
                'aTQEndOfBill'          => $aTQEndOfBill,
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

    //เพิ่มสินค้าลงใน Temp
    public function FSoCQTAddPdtInDTTmp() {
        try {
            $tTQDocNo           = $this->input->post('tTQDocNo');
            $tTQVATInOrEx       = $this->input->post('tTQVATInOrEx');
            $tBCHCode           = $this->input->post('tBCHCode');
            $tTQOptionAddPdt    = $this->input->post('tTQOptionAddPdt');
            $tSeqNo             = $this->input->post('tSeqNo');
            $aPdtData           = json_decode($this->input->post('oPdtData'));

            $this->db->trans_begin();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPdtData); $nI++) {
                $tItemPdtCode     = $aPdtData[$nI]->pnPdtCode;
                $tItemBarCode     = $aPdtData[$nI]->ptBarCode;
                $tItemPunCode     = $aPdtData[$nI]->ptPunCode;

                $aDataPdtParams = array(
                    'tDocNo'            => $tTQDocNo,
                    'tBchCode'          => $tBCHCode,
                    'tPdtCode'          => $tItemPdtCode,
                    'tBarCode'          => $tItemBarCode,
                    'tPunCode'          => $tItemPunCode,
                    'nMaxSeqNo'         => $tSeqNo,
                    'nLngID'            => $this->session->userdata("tLangID"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TARTSqDT',
                    'tVatCode'          => $aPdtData[$nI]->packData->tVatCode,
                    'nVatRate'          => $aPdtData[$nI]->packData->nVat,
                    'tTQOptionAddPdt'   => $tTQOptionAddPdt,
                    'FCXtdSetPrice'     => str_replace(",","",$aPdtData[$nI]->packData->NetAfHD),
                );

                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Quotation_model->FSaMQTGetDataPdt($aDataPdtParams);

                // นำรายการสินค้าเข้า DT Temp
                $this->Quotation_model->FSaMQTInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();

                // Calcurate Document DT Temp Array Parameter
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tTQVATInOrEx,
                    'tDataDocNo'        => $tTQDocNo,
                    'tDataDocKey'       => 'TARTSqDT',
                    'tDataSeqNo'        => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);

                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain($tTQDocNo,$tBCHCode);
                    $aReturnData = array(
                        'nStaEvent' => '1',
                        'tStaMessg' => 'Success Add Product Into Document DT Temp.'
                    );
                } else {
                    $aReturnData = array(
                        'nStaEvent' => '500',
                        'tStaMessg' => 'Error Calcurate Document DT Temp Please Contact Admin.'
                    );
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //คำนวณส่วนลดท้ายบิลใหม่อีกครั้ง กรณีมีการเพิ่มสินค้า , แก้ไขจำนวน , แก้ไขราคา , ลบสินค้า , ลดรายการ , ลดท้ายบิล
    public function FSxCalculateHDDisAgain($ptDocumentNumber , $ptBCHCode){
        $aPackDataCalCulate = array(
            'tDocNo'        => $ptDocumentNumber,
            'tBchCode'      => $ptBCHCode
        );
        FSaCCNDocumentUpdateHDDisAgain($aPackDataCalCulate);
    }

    //ลบสินค้าในตาราง Temp [รายการเดียว]
    public function FSvCQTRemovePdtInDTTmp(){
        $aDataWhere = array(
            'FTXshDocNo'    => $this->input->post('ptXshDocNo'),
            'FTPdtCode'     => $this->input->post('ptPDTCode'),
            'FNXsdSeqNo'    => $this->input->post('pnSeqNo'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FTXthDocKey'   => 'TARTSqDT'
        );
        $aResDel = $this->Quotation_model->FSnMQTDelDTTmp($aDataWhere);
        echo json_encode($aResDel);
    }

    //ลบสินค้าในตาราง Temp [หลายรายการ]
    public function FSvCQTPdtMultiDeleteEvent(){

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
                    'FTXthDocKey'   => 'TARTSqDT',
                    'FTSessionID'   => $tSession
                );
                $aResDel = $this->Quotation_model->FSaMQTPdtTmpMultiDel($aDataMaster);
            }
        }

        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    //เช็คเลขที่เอกสารถ้าคีย์มา ว่าซ้ำไหม
    public function FSoCQTChkHavePdtForDocDTTemp() {
        try {
            $tTQDocNo       = $this->input->post("ptQTDocNo");
            $tPISessionID   = $this->session->userdata('tSesSessionID');
            $aDataWhere     = array(
                'FTXshDocNo'    => $tTQDocNo,
                'FTXthDocKey'   => 'TARTSqDT',
                'FTSessionID'   => $tPISessionID
            );
            $nCountPdtInDocDTTemp = $this->Quotation_model->FSnMQTChkPdtInDocDTTemp($aDataWhere);
            if ($nCountPdtInDocDTTemp > 0) {
                $aReturnData = array(
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Found Data In Doc DT.'
                );
            } else {
                $aReturnData = array(
                    'nStaReturn'    => '800',
                    'tStaMessg'     => 'กรุณาเลือกสินค้าก่อนทำรายการ'
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

    //เพิ่มข้อมูล HD DT
    public function FSxCQTEventAdd(){
        try {
            $aDataDocument  = $this->input->post();
            $tQTAutoGenCode = (isset($aDataDocument['ocbTQStaAutoGenCode'])) ? 1 : 0;
            $tQTDocNo       = (isset($aDataDocument['oetTQDocNo'])) ? $aDataDocument['oetTQDocNo'] : '';
            $tQTDocDate     = $aDataDocument['oetTQDocDate'] . " " . $aDataDocument['oetTQDocTime'];
            $tQTVATInOrEx   = $aDataDocument['ocmTQfoVatInOrEx'];
            $tQTSessionID   = $this->session->userdata('tSesSessionID');

            FCNaHCalculateProrate('TARTSqDT',$tQTDocNo);

            $aCalcDTParams = [
                'tBchCode'          => $aDataDocument['ohdTQBchCode'],
                'tDataDocEvnCall'   => '1',
                'tDataVatInOrEx'    => $tQTVATInOrEx,
                'tDataDocNo'        => $tQTDocNo,
                'tDataDocKey'       => 'TARTSqDT',
                'tDataSeqNo'        => ''
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            // Prorate HD
            FCNaHCalculateProrate('TARTSqDT', $tQTDocNo);
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aCalDTTempParams = [
                'tDocNo'            => $tQTDocNo,
                'tBchCode'          => $aDataDocument['ohdTQBchCode'],
                'tSessionID'        => $tQTSessionID,
                'tDocKey'           => 'TARTSqDT',
                'tDataVatInOrEx'    => $tQTVATInOrEx,
            ];
            $this->Quotation_model->FSaMQTCalVatLastDT($aCalDTTempParams);

            $aCalDTTempForHD = $this->FSaCQTCalDTTempForHD($aCalDTTempParams);

            $aCalInHDDisTemp = $this->Quotation_model->FSaMQTCalInHDDisTemp($aCalDTTempParams);

            // [Update] ต้นทุน (ขาขาย)
            $aDataWhereCost = array(
                'tDataDocKey'       => 'TARTSqDT',
                'tDataDocNo'        => $tQTDocNo,
            );
            FCNaHGetCostInAndCostEx(5,'',$aDataWhereCost);

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TARTSqHD',
                'tTableHDDis'   => 'TARTSqHDDis',
                'tTableHDCst'   => 'TARTSqHDCst',
                'tTableDT'      => 'TARTSqDT',
                'tTableDTDis'   => 'TARTSqDTDis',
                'tTableStaGen'  => 1
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'     => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'     => $aDataDocument['ohdTQBchCode'],
                'FTXshDocNo'    => $tQTDocNo,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx'=> $tQTVATInOrEx,
                'FTXphUsrApv'   => ''
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTShpCode'             => '',
                'FNXshDocType'          => 1,
                'FDXshDocDate'          => (!empty($tQTDocDate)) ? $tQTDocDate : NULL,
                'FTXshCshOrCrd'         => $aDataDocument['ocmTQPaymentType'],
                'FTXshVATInOrEx'        => $tQTVATInOrEx,
                'FTDptCode'             => '',
                'FTWahCode'             => '',
                'FTPosCode'             => '',
                'FTShfCode'             => '',
                'FTUsrCode'             => $this->session->userdata('tSesUsername'),
                'FNSdtSeqNo'            => 0,
                'FTSpnCode'             => '',
                'FTXshApvCode'          => '',
                'FTCstCode'             => $aDataDocument['ohdTQCustomerCode'],
                'FTXshDocVatFull'       => '',
                'FTXshRefAE'            => '',
                'FTRteCode'             => $aDataDocument['ohdQTRateCode'],
                'FCXshRteFac'           => 0,
                'FCXshTotal'            => $aCalDTTempForHD['FCXphTotal'],
                'FCXshTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
                'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
                'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                'FCXshDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                'FCXshChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                'FCXshAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                'FCXshAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXshRefAEAmt'         => 0,
                'FCXshVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXshVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FTXshWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                'FCXshWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                'FCXshGrand'            => $aCalDTTempForHD['FCXphGrand'],
                'FCXshRnd'              => $aCalDTTempForHD['FCXphRnd'],
                'FTXshGndText'          => $aCalDTTempForHD['FTXphGndText'],
                'FTXshStaDoc'           => $aDataDocument['ohdTQStaDoc'],
                'FNXshStaDocAct'        => (isset($aDataDocument['ocbQTFrmInfoOthStaDocAct'])) ? 1 : 0,
                'FTXshRmk'              => $aDataDocument['otaQTRemark'],
            );

            $aDataCst = array(
                'FTBchCode'             => $aDataDocument['ohdTQBchCode'],
                'FTXshDocNo'            => $tQTDocNo,
                'FTCstCode'             => $aDataDocument['ohdTQCustomerCode'],
                'FTCarCode'             => $aDataDocument['oetPreCarRegCode'],
                'FNXshCrTerm'           => ($aDataDocument['ocmTQPaymentType'] == 1) ? NULL : $aDataDocument['oetQTCreditTerm'],
                'FDXshDueDate'          => $aDataDocument['oetTQEffectiveDate']
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tQTAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => $aTableAddUpdate['tTableHD'],
                    "tDocType"    => $aTableAddUpdate['tTableStaGen'],
                    "tBchCode"    => $aDataDocument['ohdTQBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXshDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXshDocNo']   = $tQTDocNo;
            }

            // [Add] Update Document HD
            $this->Quotation_model->FSxMQTAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // [Add] Update Document HDCst
            $this->Quotation_model->FSxMQTAddUpdateCSTHD($aDataCst, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Quotation_model->FSxMQTAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // [Add] Move Doc HDDisTemp -> HDDis
            $this->Quotation_model->FSaMQTMoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);

            // [Add] Doc DTTemp -> DT
            $this->Quotation_model->FSaMQTMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

            // [Add] Doc DTDisTemp -> DTDis
            $this->Quotation_model->FSaMQTMoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);

            // [Move] Doc TCNTDocHDRefTmp To TARTSqHDDocRef
            $this->Quotation_model->FSxMQTMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                //ถ้าทำงานผิดพลาด รวบรวม Data เพื่อส่ง MQ_LOG
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Add Quotation Document Unsucess.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'บันทึกใบเสนอราคา',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                //ถ้าทำงานสมบูรณ์ รวบรวม Data เพื่อส่ง MQ_LOG
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXshDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Add Quotation Document Success.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'บันทึกใบเสนอราคา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            //ถ้าทำงานผิดพลาด รวบรวม Data เพื่อส่ง MQ_LOG
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataWhere['FTXshDocNo'],
                'tEventName' => 'บันทึกใบเสนอราคา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);   
        echo json_encode($aReturnData);
    }

    //แก้ไขข้อมูล HD DT
    public function FSxCQTEventEdit(){
        try {            
            $aDataDocument  = $this->input->post();
            $tQTDocNo       = (isset($aDataDocument['oetTQDocNo'])) ? $aDataDocument['oetTQDocNo'] : '';
            $tQTDocDate     = $aDataDocument['oetTQDocDate'] . " " . $aDataDocument['oetTQDocTime'];
            $tQTVATInOrEx   = $aDataDocument['ocmTQfoVatInOrEx'];
            $tQTSessionID   = $this->session->userdata('tSesSessionID');

            if($aDataDocument['ohdTQStaApv'] == 1){ //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว

                // Array Data update
                $aDataUpdate = array(
                    'FTBchCode'             => $aDataDocument['ohdTQBchCode'],
                    'FTXshDocNo'            => $tQTDocNo,
                    'FTXshRmk'              => $aDataDocument['otaQTRemark'],
                );

                $this->db->trans_begin();

                // [Update] update หมายเหตุ
                $this->Quotation_model->FSaMQTUpdateRmk($aDataUpdate);

            } else { //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ
                FCNaHCalculateProrate('TARTSqDT', $tQTDocNo);

                $aCalcDTParams = [
                    'tBchCode'          => $aDataDocument['ohdTQBchCode'],
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => $tQTVATInOrEx,
                    'tDataDocNo'        => $tQTDocNo,
                    'tDataDocKey'       => 'TARTSqDT',
                    'tDataSeqNo'        => ''
                ];
                FCNbHCallCalcDocDTTemp($aCalcDTParams);

                $aCalDTTempParams = [
                    'tDocNo'            => $tQTDocNo,
                    'tBchCode'          => $aDataDocument['ohdTQBchCode'],
                    'tSessionID'        => $tQTSessionID,
                    'tDocKey'           => 'TARTSqDT',
                    'tDataVatInOrEx'    => $tQTVATInOrEx,
                ];
                $this->Quotation_model->FSaMQTCalVatLastDT($aCalDTTempParams);

                $aCalDTTempForHD = $this->FSaCQTCalDTTempForHD($aCalDTTempParams);

                $aCalInHDDisTemp = $this->Quotation_model->FSaMQTCalInHDDisTemp($aCalDTTempParams);

                // [Update] ต้นทุน (ขาขาย)
                $aDataWhereCost = array(
                    'tDataDocKey'       => 'TARTSqDT',
                    'tDataDocNo'        => $tQTDocNo,
                );
                FCNaHGetCostInAndCostEx(5,'',$aDataWhereCost);

                // Array Data Table Document
                $aTableAddUpdate = array(
                    'tTableHD'      => 'TARTSqHD',
                    'tTableHDDis'   => 'TARTSqHDDis',
                    'tTableHDCst'   => 'TARTSqHDCst',
                    'tTableDT'      => 'TARTSqDT',
                    'tTableDTDis'   => 'TARTSqDTDis',
                    'tTableStaGen'  => 1
                );

                // Array Data Where Insert
                $aDataWhere = array(
                    'FTAgnCode'     => $this->session->userdata('tSesUsrAgnCode'),
                    'FTBchCode'     => $aDataDocument['ohdTQBchCode'],
                    'FTXshDocNo'    => $tQTDocNo,
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FDCreateOn'    => date('Y-m-d H:i:s'),
                    'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                    'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                    'FTXthVATInOrEx'=> $tQTVATInOrEx,
                    'FTXphUsrApv'   => ''
                );

                // Array Data HD Master
                $aDataMaster = array(
                    'FTShpCode'             => '',
                    'FNXshDocType'          => 1,
                    'FDXshDocDate'          => (!empty($tQTDocDate)) ? $tQTDocDate : NULL,
                    'FTXshCshOrCrd'         => $aDataDocument['ocmTQPaymentType'],
                    'FTXshVATInOrEx'        => $tQTVATInOrEx,
                    'FTDptCode'             => '',
                    'FTWahCode'             => '',
                    'FTPosCode'             => '',
                    'FTShfCode'             => '',
                    'FTUsrCode'             => $this->session->userdata('tSesUsername'),
                    'FNSdtSeqNo'            => 0,
                    'FTSpnCode'             => '',
                    'FTXshApvCode'          => '',
                    'FTCstCode'             => $aDataDocument['ohdTQCustomerCode'],
                    'FTXshDocVatFull'       => '',
                    'FTXshRefAE'            => '',
                    'FTRteCode'             => $aDataDocument['ohdQTRateCode'],
                    'FCXshRteFac'           => 0,
                    'FCXshTotal'            => $aCalDTTempForHD['FCXphTotal'],
                    'FCXshTotalNV'          => $aCalDTTempForHD['FCXphTotalNV'],
                    'FCXshTotalNoDis'       => $aCalDTTempForHD['FCXphTotalNoDis'],
                    'FCXshTotalB4DisChgV'   => $aCalDTTempForHD['FCXphTotalB4DisChgV'],
                    'FCXshTotalB4DisChgNV'  => $aCalDTTempForHD['FCXphTotalB4DisChgNV'],
                    'FTXshDisChgTxt'        => isset($aCalInHDDisTemp['FTXphDisChgTxt']) ? $aCalInHDDisTemp['FTXphDisChgTxt'] : '',
                    'FCXshDis'              => isset($aCalInHDDisTemp['FCXphDis']) ? $aCalInHDDisTemp['FCXphDis'] : NULL,
                    'FCXshChg'              => isset($aCalInHDDisTemp['FCXphChg']) ? $aCalInHDDisTemp['FCXphChg'] : NULL,
                    'FCXshTotalAfDisChgV'   => $aCalDTTempForHD['FCXphTotalAfDisChgV'],
                    'FCXshTotalAfDisChgNV'  => $aCalDTTempForHD['FCXphTotalAfDisChgNV'],
                    'FCXshAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                    'FCXshAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                    'FCXshRefAEAmt'         => 0,
                    'FCXshVat'              => $aCalDTTempForHD['FCXphVat'],
                    'FCXshVatable'          => $aCalDTTempForHD['FCXphVatable'],
                    'FTXshWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                    'FCXshWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                    'FCXshGrand'            => $aCalDTTempForHD['FCXphGrand'],
                    'FCXshRnd'              => $aCalDTTempForHD['FCXphRnd'],
                    'FTXshGndText'          => $aCalDTTempForHD['FTXphGndText'],
                    'FTXshStaDoc'           => $aDataDocument['ohdTQStaDoc'],
                    'FNXshStaDocAct'        => (isset($aDataDocument['ocbQTFrmInfoOthStaDocAct'])) ? 1 : 0,
                    'FTXshRmk'              => $aDataDocument['otaQTRemark'],
                );

                // Array Data Customer
                $aDataCst = array(
                    'FTBchCode'             => $aDataDocument['ohdTQBchCode'],
                    'FTXshDocNo'            => $tQTDocNo,
                    'FTCstCode'             => $aDataDocument['ohdTQCustomerCode'],
                    'FNXshCrTerm'           => ($aDataDocument['ocmTQPaymentType'] == 1) ? NULL : $aDataDocument['oetQTCreditTerm'],
                    'FDXshDueDate'          => $aDataDocument['oetTQEffectiveDate']
                );

                $this->db->trans_begin();

                // [Add] Update Document HD
                $this->Quotation_model->FSxMQTAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

                // [Add] Update Document HDCst
                $this->Quotation_model->FSxMQTAddUpdateCSTHD($aDataCst, $aDataWhere, $aTableAddUpdate);

                // [Update] DocNo -> Temp
                $this->Quotation_model->FSxMQTAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

                // [Add] Move Doc HDDisTemp -> HDDis
                $this->Quotation_model->FSaMQTMoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);

                // [Add] Doc DTTemp -> DT
                $this->Quotation_model->FSaMQTMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

                // [Add] Doc DTDisTemp -> DTDis
                $this->Quotation_model->FSaMQTMoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);

                // [Move] Doc TCNTDocHDRefTmp To TARTSqHDDocRef
                $this->Quotation_model->FSxMQTMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                //ถ้าทำงานผิดพลาด รวบรวม Data เพื่อส่ง MQ_LOG
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Edit Quotation Document Unsucess.",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบเสนอราคา',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                //ถ้าทำงานสมบูรณ์ รวบรวม Data เพื่อส่ง MQ_LOG
                $aReturnData = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataWhere['FTXshDocNo'],
                    'nStaReturn' => '1',
                    'tStaMessg' => 'Edit Quotation Document Success.',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataWhere['FTXshDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบเสนอราคา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            //ถ้าทำงานผิดพลาด รวบรวม Data เพื่อส่ง MQ_LOG
            $aReturnData = array(
                'nStaReturn' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo' => $aDataWhere['FTXshDocNo'],
                'tEventName' => 'แก้ไขและบันทึกใบเสนอราคา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        $nStaApvOrSave = $aDataDocument['ohdTQApvOrSave'];
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 
        echo json_encode($aReturnData);
    }

    //คำนวณจาก DT Temp ให้ HD
    private function FSaCQTCalDTTempForHD($paParams) {
        $aCalDTTemp = $this->Quotation_model->FSaMQTCalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems    = $aCalDTTemp[0];
            $nRound             = 0;
            $cGrand             = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV'];

            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXphRnd'] = $nRound;
            $aCalDTTempItems['FCXphGrand'] = $cGrand;
            $aCalDTTempItems['FTXphGndText'] = $tGndText;
            return $aCalDTTempItems;
        }
    }

    //Edit Inline สินค้า ลง Document DT Temp
    public function FSoCQTEditPdtIntoDocDTTemp() {
        try {
            $tQTBchCode         = $this->input->post('tQTBchCode');
            $tQTDocNo           = $this->input->post('tQTDocNo');
            $nQTSeqNo           = $this->input->post('nQTSeqNo');
            $nStaDelDis         = $this->input->post('nStaDelDis');

            $aDataWhere = array(
                'tQTBchCode'    => $tQTBchCode,
                'tQTDocNo'      => $tQTDocNo,
                'nQTSeqNo'      => $nQTSeqNo,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'TARTSqDT',
                'nStaDelDis'    =>  $nStaDelDis
            );

            $aDataUpdateDT = array(
                'FCXtdQty'      => $this->input->post('nQty'),
                'FCXtdSetPrice' => $this->input->post('cPrice'),
                'FCXtdNet'      => $this->input->post('cNet')
            );

            $this->db->trans_begin();

            $this->Quotation_model->FSaMQTUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

            if ($nStaDelDis == '1') {
                // ยืนยันการลบ DTDis ส่วนลดรายการนี้
                $this->Quotation_model->FSaMQTDeleteDTDisTemp($aDataWhere);
                $this->Quotation_model->FSaMQTClearDisChgTxtDTTemp($aDataWhere);
            }

            //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง
            $this->FSxCalculateHDDisAgain($tQTDocNo,$tQTBchCode);

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

    //ลบข้อมูลเอกสาร
    public function FSoCQTEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Quotation_model->FSnMQTDelDocument($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );

                $aReturnData = array(
                    'nStaEvent'    => '1',
                    'tStaMessg'    => "Delete Quotation Document Success",
                    'tLogType' => 'INFO',
                    'tDocNo' => $tDataDocNo,
                    'tEventName' => 'ลบใบเสนอราคา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelDoc['rtCode'],
                    'tStaMessg' => $aResDelDoc['rtDesc']
                );

                $aReturnData = array(
                    'nStaEvent'    => $aResDelDoc['rtCode'],
                    'tStaMessg'    => $aResDelDoc['rtDesc'],
                    'tLogType' => 'ERROR',
                    'tDocNo' => $tDataDocNo,
                    'tEventName' => 'ลบใบเสนอราคา',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo' => $tDataDocNo,
                'tEventName' => 'ลบใบเสนอราคา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aDataStaReturn);
    }

    //หน้าจอแก้ไข
    public function FSvCQTEditPage(){
        try {
            $ptDocumentNumber = $this->input->post('ptQTDocNo');
            
            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Quotation_model->FSnMQTDelALLTmp($aWhereClearTemp);

            // Get Autentication Route
            $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXshDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TARTSqDT',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 90000,
                'nPage'         => 1,
            );

            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->Quotation_model->FSaMQTGetDataDocHD($aDataWhere);

            // Get Data Document Cst HD
            $aDataDocCstHD      = $this->Quotation_model->FSaMQTGetDataDocCstHD($aDataWhere);

            // Move Data HD DIS To HD DIS Temp
            $this->Quotation_model->FSxMQTMoveHDDisToTemp($aDataWhere);

            // Move Data DT TO DTTemp
            $this->Quotation_model->FSxMQTMoveDTToDTTemp($aDataWhere);

            // Move Data DTDIS TO DTDISTemp
            $this->Quotation_model->FSxMQTMoveDTDisToDTDisTemp($aDataWhere);

            // Move Data HDDocRef TO HDRefTemp
            $this->Quotation_model->FSxMQTMoveHDRefToHDRefTemp($aDataWhere);


            // [Update] ต้นทุน (ขาขาย)
            $aDataWhereCost = array(
                'tDataDocKey'       => 'TARTSqDT',
                'tDataDocNo'        => $ptDocumentNumber,
            );
            FCNaHGetCostInAndCostEx(5,'',$aDataWhereCost);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Query Call Edit Page.'
                );
            } else {
                $this->db->trans_commit();
                $tVATInOrEx = ($aDataDocHD['rtCode'] == '1') ? $aDataDocHD['raItems']['FTXshVATInOrEx'] : 1;


                $aDataWhare = array('tCarCstCode' => $aDataDocCstHD['raItems']['FTCarCode'], 'nLangEdit' => $nLangEdit);
                $aDataCarCst   =  $this->Quotation_model->FSaMPreGetDataCarCustomer($aDataWhare);

                $aDataConfigViewAdd = array(
                    'nStaShwAddress'    => $this->Quotation_model->FSnMQTGetConfigShwAddress(),
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'aRateDefault'      => '',
                    'aDataCarCstItem'   => @$aDataCarCst['raItems'],
                    'aDataDocHD'        => $aDataDocHD,
                    'aDataDocCstHD'     => $aDataDocCstHD
                );

                $tViewPageAdd           = $this->load->view('document/quotation/wQuotationPageAdd',$aDataConfigViewAdd,true);
                $aReturnData = array(
                    'tViewPageAdd'      => $tViewPageAdd,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Call Page Success',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $ptDocumentNumber,
                    'tEventName' => 'เรียกดูเอกสารใบเสนอราคา',
                    'nLogCode' => '001',
                    'nLogLevel' => '',
                    'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXshApvCode']
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $ptDocumentNumber,
                'tEventName' => 'เรียกดูเอกสารใบเสนอราคา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXshApvCode']
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    //ยกเลิกเอกสาร
    public function FSoCQTUpdateStaDocCancel(){
        try {
            $tDocNo  = $this->input->post('tDocNo');
            $tBchCode = $this->input->post('tBchCode');
            $aDataUpdate = array(
                'FTXshDocNo'    => $tDocNo,
                'FTXshStaDoc'   => '3'
            );

            $aStaDoc = $this->Quotation_model->FSaMQTUpdateStaDocCancel($aDataUpdate);
            $aReturn    = array(
                'rtCode' => $aStaDoc['rtCode'],
                'rtDesc' => $aStaDoc['rtDesc']
            );
            
            $aReturnData = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => "Cancel Quotation Document Success",
                'tLogType' => 'INFO',
                'tDocNo' => $tDocNo,
                'tEventName' => 'ยกเลิกใบเสนอราคา',
                'nLogCode' => '001',
                'nLogLevel' => '',
                'FTXphUsrApv'   => ''
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo' => $tDocNo,
                'tEventName' => 'ยกเลิกใบเสนอราคา',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturn);
    }

    //อนุมัติเอกสาร
    public function FSoCQTApproveEvent(){
        $tApvCode = $this->session->userdata('tSesUsername');
        if (empty($tApvCode) && $tApvCode == '') {
            $tApvCode = get_cookie('tUsrCode');
        }
        try{

            $tDocNo     = $this->input->post('tDocNo');
            $tBchCode   = $this->input->post('tBchCode');

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshUsrApv'       => $this->session->userdata('tSesUsername')
            );
            $this->Quotation_model->FSaMQTApproveDocument($aDataUpdate);
            
            $aReturnData = array(
                'nStaEvent'     => '1',
                'tStaMessg'     => "Approve Quotation Document Success",
                'tLogType'      => 'INFO',
                'tDocNo' => $tDocNo,
                'tEventName'    => 'อนุมัติใบเสนอราคา',
                'nLogCode'      => '001',
                'nLogLevel'     => '0',
                'FTXphUsrApv'   => $tApvCode
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent'     => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo' => $tDocNo,
                'tEventName'    => 'อนุมัติใบเสนอราคา',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $tApvCode
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 
        echo json_encode($aReturnData);

    }

    //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCQTPageHDDocRef(){
        try {
            $tDocNo = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TARTSqHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXthDocNo'        => $tDocNo,
                'FTXthDocKey'       => 'TARTSqHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];

            $aDataDocHDRef = $this->Quotation_model->FSaMQTGetDataHDRefTmp($aDataWhere);
            $aDataConfig = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/quotation/wQuotationDocRef', $aDataConfig, true);
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
    public function FSoCQTEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXthDocNo'        => $this->input->post('ptQTDocNo'),
                'FTXthDocKey'       => 'TARTSqHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tQTRefDocNoOld'    => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => $this->input->post('pdRefDocDate'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData = $this->Quotation_model->FSaMQTAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCQTEventDelHDDocRef(){
        try {
            $aData = [
                'FTXthDocNo'        => $this->input->post('ptDocNo'),
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthDocKey'       => 'TARTSqHD',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->Quotation_model->FSaMQTDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse (ใบรับรถ)
    public function FSoCQTCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        $this->load->view('document/quotation/refintdocument/wQuotationRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search (ใบรับรถ)
    public function FSoCQTCallRefIntDocDataTable(){

        $nPage                  = $this->input->post('nQTRefIntPageCurrent');
        $tQTRefIntBchCode       = $this->input->post('tQTRefIntBchCode');
        $tQTRefIntDocNo         = trim($this->input->post('tQTRefIntDocNo'), " ");
        $tQTRefIntDocDateFrm    = $this->input->post('tQTRefIntDocDateFrm');
        $tQTRefIntDocDateTo     = $this->input->post('tQTRefIntDocDateTo');
        $tQTRefIntStaDoc        = $this->input->post('tQTRefIntStaDoc');
        $tCarCode               = $this->input->post('tCarCode');
        $tCstCode               = $this->input->post('tCstCode');

        // Page Current
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nQTRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");

        $aDataParamFilter = array(
            'tQTRefIntBchCode'      => $tQTRefIntBchCode,
            'tQTRefIntDocNo'        => $tQTRefIntDocNo,
            'tQTRefIntDocDateFrm'   => $tQTRefIntDocDateFrm,
            'tQTRefIntDocDateTo'    => $tQTRefIntDocDateTo,
            'tQTRefIntStaDoc'       => $tQTRefIntStaDoc,
            'tCarCode'              => $tCarCode,
            'tCstCode'              => $tCstCode
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $aDataParamFilter
        );

         $aDataParam = $this->Quotation_model->FSoMQTCallRefIntDocDataTable($aDataCondition);

         $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
          );

         $this->load->view('document/quotation/refintdocument/wQuotationRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse (ใบรับรถ)
    public function FSoCQTCallRefIntDocDetailDataTable(){

        $nLangEdit       = $this->session->userdata("tLangEdit");
        $tBchCode        = $this->input->post('ptBchCode');
        $tDocNo          = $this->input->post('ptDocNo');
        $nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $aDataCondition  = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );

        $aDataParam = $this->Quotation_model->FSoMQTCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
          );
        $this->load->view('document/quotation/refintdocument/wQuotationRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt (ใบรับรถ)
    public function FSoCQTCallRefIntDocInsertDTToTemp(){
        $tQTDocNo       =  $this->input->post('tQTDocNo');
        $tFrmBchCode    =  $this->input->post('tFrmBchCode');
        $tRefIntDocNo   =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode =  $this->input->post('tRefIntBchCode');
        $aSeqNo         =  $this->input->post('aSeqNo');

        $aDataParam = array(
            'tQTDocNo'       => $tQTDocNo,
            'tFrmBchCode'    => $tFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );

        $aDataResult = $this->Quotation_model->FSoMQTCallRefIntDocInsertDTToTemp($aDataParam);
        return  $aDataResult;
    }


    function FSoCQTCallRefIntDocFindDocCarInfo(){
        $tQTRefType     = $this->input->post('ptQTRefType');
        $tRefIntDocNo   = $this->input->post('ptRefIntDocNo');
        $tRefIntBchCode = $this->input->post('ptRefIntBchCode');
        if($tQTRefType == '1'){
            // ใบรับรถ Job 1 Get Data Car Info
            $aDataWhere = [
                'FTAgnCode'     => '',
                'FTBchCode'     => $tRefIntBchCode,
                'FTXshDocNo'    => $tRefIntDocNo,
                'FNLngID'       => $this->session->userdata("tLangEdit"),
            ];
            $aDataGetCarInfo    = $this->Quotation_model->FSaMQTGetDataCarInfoJOB1REQ($aDataWhere);
        }


        echo json_encode($aDataGetCarInfo);
    }

    //หาข้อมูลรถ
    function FSoCQTReturnCarInfo(){
        $aDataWhare     = array(
            'tCarCstCode'   => $this->input->post('tCarCode'), 
            'nLangEdit'     => $this->session->userdata("tLangEdit")
        );
        $aDataCarCst        =  $this->Quotation_model->FSaMPreGetDataCarCustomer($aDataWhare);
        echo json_encode($aDataCarCst);
    }


}
