<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_controller extends MX_Controller {

    public $tRouteMenu = 'docInvoice/0/0';

    public function __construct() {
        $this->load->model('company/company/mCompany');
        $this->load->model('document/invoice/Invoice_model');
        parent::__construct();
    }

    public function index($nIVBrowseType, $tIVBrowseOption) {
        $aData['nBrowseType']       = $nIVBrowseType;
        $aData['tBrowseOption']     = $tIVBrowseOption;
		$aData['aPermission']       = FCNaHCheckAlwFunc($this->tRouteMenu);
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML($this->tRouteMenu);
        // ##################### เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie #####################
        $aCookieMenuCode    = array(
            'name'      => 'tMenuCode',
            'value'     => json_encode('AP0008'),
            'expire'    => 0
        );
        $this->input->set_cookie($aCookieMenuCode);
        $aCookieMenuName    = array(
            'name'	    => 'tMenuName',
            'value'     => json_encode('ใบซื้อสินค้า'),
            'expire'    => 0
        );
        $this->input->set_cookie($aCookieMenuName);
        // ####################################################################################
        $this->load->view('document/invoice/wInvoice',$aData);
    }

    //List
    public function FSvCIVFormSearchList(){
        $this->load->view('document/invoice/wInvoiceSearchList');
    }

    //ตารางข้อมูล (HD)
    public function FSvCIVDataTable(){
        $tAdvanceSearchData = $this->input->post('oAdvanceSearch');
        $nPage              = $this->input->post('nPageCurrent');
        $aAlwEvent          = FCNaHCheckAlwFunc($this->tRouteMenu);
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        if ($nPage == '' || $nPage == null) {
            $nPage  = 1;
        } else {
            $nPage  = $this->input->post('nPageCurrent');
        }
        $nLangEdit  = $this->session->userdata("tLangEdit");
        $aData      = array(
            'FNLngID'           => $nLangEdit,
            'nPage'             => $nPage,
            'nRow'              => 10,
            'aAdvanceSearch'    => $tAdvanceSearchData
        );
        $aList      = $this->Invoice_model->FSaMIVList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $tViewDataTable = $this->load->view('document/invoice/wInvoiceDataTable', $aGenTable ,true);
        $aReturnData    = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCIVAddPage(){
        try{
            //ล้างค่าใน Temp
            $this->Invoice_model->FSaMIVDeletePDTInTmp();
            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $nLangEdit          = $this->session->userdata("tLangEdit");
            $aCompData          = $this->mCompany->FSaMCMPList('','',array('FNLngID' => $nLangEdit));
            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aRateDefault'      => $aCompData,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'nStaShwAddress'    => $this->Invoice_model->FSnMIVGetConfigShwAddress()
            );
            $tViewPageAdd   = $this->load->view('document/invoice/wInvoicePageAdd',$aDataConfigViewAdd,true);
            $aReturnData    = array(
                'tViewPageAdd'  => $tViewPageAdd,
                'nStaEvent'     => '1',
                'tStaMessg'     => 'Success'
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
    public function FSvCIVTableDTTemp(){
        try {
            $bStaSession    = $this->session->userdata('bSesLogIn');
            if(isset($bStaSession) && $bStaSession === TRUE){
                //ยังมี Session อยู่
            }else{
                $aReturnData    = array(
                    'checksession'  => 'expire'
                );
                echo json_encode($aReturnData);
                exit;
            }
            $tIVDocNo           = $this->input->post('ptIVDocNo');
            $tIVStaApv          = $this->input->post('ptIVStaApv');
            $tIVStaDoc          = $this->input->post('ptIVStaDoc');
            $tIVVATInOrEx       = $this->input->post('ptIVVATInOrEx');
            $tBCHCode           = $this->input->post('tBCHCode');
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            $aDataWhere = array(
                'FTXthDocNo'    => $tIVDocNo,
                'FTXthDocKey'   => 'TAPTPiDT',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            );
            $aDataDocDTTemp = $this->Invoice_model->FSaMIVGetDocDTTempListPage($aDataWhere);
            $aDataView      = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'tIVStaApv'         => $tIVStaApv,
                'tIVStaDoc'         => $tIVStaDoc,
                'aDataDocDTTemp'    => $aDataDocDTTemp
            );

            $tIVPdtAdvTableHtml = $this->load->view('document/invoice/wInvoicePdtAdvTableData', $aDataView, true);
            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'   => $tIVVATInOrEx,
                'tDocNo'        => $tIVDocNo,
                'tDocKey'       => 'TAPTPiDT',
                'nLngID'        => FCNaHGetLangEdit(),
                'tSesSessionID' => $this->session->userdata('tSesSessionID'),
                'tBchCode'      => $tBCHCode
            );
            //คำนวณส่วนลดใหม่อีกครั้ง ถ้าหากมีส่วนลดท้ายบิล
            $aIVEndOfBill['aEndOfBillVat']  = FCNaDOCEndOfBillCalVat($aEndOfBillParams);
            $aIVEndOfBill['aEndOfBillCal']  = FCNaDOCEndOfBillCal($aEndOfBillParams);
            $aIVEndOfBill['tTextBath']      = FCNtNumberToTextBaht($aIVEndOfBill['aEndOfBillCal']['cCalFCXphGrand']);
            $aReturnData = array(
                'tIVPdtAdvTableHtml'    => $tIVPdtAdvTableHtml,
                'aIVEndOfBill'          => $aIVEndOfBill,
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

    //ล้างข้อมูลใน Temp
    public function FSoCIVClearDataTemp(){
        //ล้างค่าใน Temp
        $this->Invoice_model->FSaMIVDeletePDTInTmp();
    }

    //เพิ่มสินค้าลงใน Temp
    public function FSoCIVAddPdtInDTTmp() {
        try {
            $tIVDocNo           = $this->input->post('tIVDocNo');
            $tIVVATInOrEx       = $this->input->post('tIVVATInOrEx');
            $tBCHCode           = $this->input->post('tBCHCode');
            $tIVOptionAddPdt    = $this->input->post('tIVOptionAddPdt');
            $tSeqNo             = $this->input->post('tSeqNo');
            $aPdtData           = json_decode($this->input->post('oPdtData'));
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPdtData); $nI++) {
                $tItemPdtCode   = $aPdtData[$nI]->pnPdtCode;
                $tItemBarCode   = $aPdtData[$nI]->ptBarCode;
                $tItemPunCode   = $aPdtData[$nI]->ptPunCode;
                $cItemPrice     = $aPdtData[$nI]->packData->Price;
                $aDataPdtParams = array(
                    'tDocNo'            => $tIVDocNo,
                    'tBchCode'          => $tBCHCode,
                    'tPdtCode'          => $tItemPdtCode,
                    'tBarCode'          => $tItemBarCode,
                    'tPunCode'          => $tItemPunCode,
                    'cPrice'            => str_replace(',', '', $cItemPrice),
                    'nMaxSeqNo'         => $tSeqNo,
                    'nLngID'            => $this->session->userdata("tLangID"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TAPTPiDT',
                    'tVatCode'          => $aPdtData[$nI]->packData->tVatCode,
                    'nVatRate'          => $aPdtData[$nI]->packData->nVat,
                    'tIVOptionAddPdt'   => $tIVOptionAddPdt
                );
                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Invoice_model->FSaMIVGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $this->Invoice_model->FSaMIVInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);
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
                    'tDataDocEvnCall'   => '12',
                    'tDataVatInOrEx'    => $tIVVATInOrEx,
                    'tDataDocNo'        => $tIVDocNo,
                    'tDataDocKey'       => 'TAPTPiDT',
                    'tDataSeqNo'        => ''
                ];
                $tStaCalcuRate = FCNbHCallCalcDocDTTemp($aCalcDTParams);
                if ($tStaCalcuRate === TRUE) {
                    $this->FSxCalculateHDDisAgain($tIVDocNo,$tBCHCode);
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
    public function FSvCIVRemovePdtInDTTmp(){
        $aDataWhere = array(
            'FTXphDocNo'    => $this->input->post('ptXphDocNo'),
            'FTPdtCode'     => $this->input->post('ptPDTCode'),
            'FNXpdSeqNo'    => $this->input->post('pnSeqNo'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'FTXthDocKey'   => 'TAPTPiDT'
        );
        $aResDel = $this->Invoice_model->FSnMIVDelDTTmp($aDataWhere);
        echo json_encode($aResDel);
    }

    //ลบสินค้าในตาราง Temp [หลายรายการ]
    public function FSvCIVPdtMultiDeleteEvent(){
        $tBchCode       = $this->input->post('tBchCode');
        $tDocNo         = $this->input->post('tDocNo');
        $aSeqCode       = $this->input->post('tSeqCode');
        $tSession       = $this->session->userdata('tSesSessionID');
        $nCount         = FCNnHSizeOf($aSeqCode);
        $aResDel        = '';
        if($nCount > 1){
            for($i=0;$i<$nCount;$i++){
                $aDataMaster    = array(
                    'FTBchCode'     => $tBchCode,
                    'FTXthDocNo'    => $tDocNo,
                    'FNXtdSeqNo'    => $aSeqCode[$i],
                    'FTXthDocKey'   => 'TAPTPiDT',
                    'FTSessionID'   => $tSession
                );
                $aResDel    = $this->Invoice_model->FSaMIVPdtTmpMultiDel($aDataMaster);
            }
        }
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    //เช็คเลขที่เอกสารถ้าคีย์มา ว่าซ้ำไหม
    public function FSoCIVChkHavePdtForDocDTTemp() {
        try {
            $tIVDocNo       = $this->input->post("ptIVDocNo");
            $tIVSessionID   = $this->session->userdata('tSesSessionID');
            $aDataWhere     = array(
                'FTXphDocNo'    => $tIVDocNo,
                'FTXthDocKey'   => 'TAPTPiDT',
                'FTSessionID'   => $tIVSessionID
            );
            $nCountPdtInDocDTTemp = $this->Invoice_model->FSnMIVChkPdtInDocDTTemp($aDataWhere);
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
    public function FSxCIVEventAdd(){
        try {
            $aDataDocument  = $this->input->post();

            // Vat Check Edit Input Or Systems
            $tIVSumFCXtdVatKey  = floatval($aDataDocument['oetIVSumFCXtdVat']);   // Vat ที่มาจากการ Key จาก Input ด้วย User
            $tIVSumFCXtdVatCal  = floatval($aDataDocument['ohdIVSumFCXtdVat']);   // Vat ที่มาจากการคำนวณของระบบ

            $tIVAutoGenCode = (isset($aDataDocument['ocbIVStaAutoGenCode'])) ? 1 : 0;
            $tIVDocNo       = (isset($aDataDocument['oetIVDocNo'])) ? $aDataDocument['oetIVDocNo'] : '';
            $tIVDocDate     = $aDataDocument['oetIVDocDate'] . " " . $aDataDocument['oetIVDocTime'];
            $tIVVATInOrEx   = $aDataDocument['ocmIVfoVatInOrEx'];
            $tIVSessionID   = $this->session->userdata('tSesSessionID');


            FCNaHCalculateProrate('TAPTPiDT',$tIVDocNo);
            $aCalcDTParams  = [
                'tBchCode'          => $aDataDocument['ohdIVBchCode'],
                'tDataDocEvnCall'   => '12',
                'tDataVatInOrEx'    => $tIVVATInOrEx,
                'tDataDocNo'        => $tIVDocNo,
                'tDataDocKey'       => 'TAPTPiDT',
                'tDataSeqNo'        => ''
            ];
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            // Prorate HD
            FCNaHCalculateProrate('TAPTPiDT', $tIVDocNo);
            FCNbHCallCalcDocDTTemp($aCalcDTParams);

            $aCalDTTempParams   = [
                'tDocNo'            => $tIVDocNo,
                'tBchCode'          => $aDataDocument['ohdIVBchCode'],
                'tSessionID'        => $tIVSessionID,
                'tDocKey'           => 'TAPTPiDT',
                'tDataVatInOrEx'    => $tIVVATInOrEx,
            ];
            $this->Invoice_model->FSaMIVCalVatLastDT($aCalDTTempParams);

            $aCalDTTempForHD    = $this->FSaCIVCalDTTempForHD($aCalDTTempParams);
            $aCalInHDDisTemp    = $this->Invoice_model->FSaMIVCalInHDDisTemp($aCalDTTempParams);

            // Array Data Table Document
            $aTableAddUpdate    = array(
                'tTableHD'      => 'TAPTPiHD',
                'tTableHDDis'   => 'TAPTPiHDDis',
                'tTableHDSPL'   => 'TAPTPiHDSpl',
                'tTableDT'      => 'TAPTPiDT',
                'tTableDTDis'   => 'TAPTPiDTDis',
                'tTableStaGen'  => 12
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTAgnCode'         => $aDataDocument['ohdIVADCode'],
                'FTBchCode'         => $aDataDocument['ohdIVBchCode'],
                'FTXphDocNo'        => $tIVDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx'    => $tIVVATInOrEx
            );

            // Array Data HD Master
            $aDataMaster = array(
                'FTShpCode'             => '',
                'FNXphDocType'          => 12,
                'FDXphDocDate'          => (!empty($tIVDocDate)) ? $tIVDocDate : NULL,
                'FTXphCshOrCrd'         => $aDataDocument['ocmIVPaymentType'],
                'FTXphVATInOrEx'        => $tIVVATInOrEx,
                'FTDptCode'             => '',
                'FTSplCode'             => $aDataDocument['ohdIVSPLCode'],
                'FTUsrCode'             => $this->session->userdata('tSesUsername'),
                'FTAgnCode'             => $aDataDocument['ohdIVADCode'],
                'FCXphPaid'             => 0,
                'FCXphLeft'             => $aCalDTTempForHD['FCXphGrand'],
                'FTXphStaPaid'          => 1,
                'FTXphRefAE'            => '',
                'FTRteCode'             => $aDataDocument['ohdIVRateCode'],
                'FCXphRteFac'           => 0,
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
                'FCXphAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                'FCXphAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                'FCXphVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXphVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FTXphWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                'FCXphWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                'FCXphGrand'            => $aCalDTTempForHD['FCXphGrand'],
                'FCXphRnd'              => $aCalDTTempForHD['FCXphRnd'],
                'FTXphRmk'              => $aDataDocument['otaIVRemark'],
                'FTXphGndText'          => $aCalDTTempForHD['FTXphGndText'],
                'FTXphStaDoc'           => $aDataDocument['ohdIVStaDoc'],
                'FNXphStaDocAct'        => (isset($aDataDocument['ocbIVFrmInfoOthStaDocAct'])) ? 1 : 0,
                'FNXphStaRef'           => $aDataDocument['ocmIVFrmInfoOthRef'],

                /** Vat คำนวณด้วยระบบ */
                'FCXphVatCal'           => $aCalDTTempForHD['FCXphVat'],
            );

            // Array Data SPL
            $aDataSPL = array(
                'FTBchCode'             => $aDataDocument['ohdIVBchCode'],
                'FTXphDocNo'            => $tIVDocNo,
                'FTXphDstPaid'          => $aDataDocument['ocmIVDstPaid'],
                'FNXphCrTerm'           => ($aDataDocument['ocmIVPaymentType'] == 1) ? NULL : $aDataDocument['oetIVCreditTerm'],
                'FDXphDueDate'          => !empty($aDataDocument['oetIVEffectiveDate']) ? $aDataDocument['oetIVEffectiveDate']: NULL,
                'FTXphCtrName'          => $aDataDocument['oetIVCtrName'],
                'FDXphTnfDate'          => (!empty($aDataDocument['oetIVTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetIVTnfDate'])) : NULL,
                'FTXphRefTnfID'         => $aDataDocument['oetIVRefTnfID'],
                'FTXphRefVehID'         => $aDataDocument['oetIVVehID'],
                'FTXphRefInvNo'         => '',
                'FTXphQtyAndTypeUnit'   => '',
                'FNXphShipAdd'          => intval($aDataDocument['ohdIVFrmShipAdd']),
                'FNXphTaxAdd'           => intval($aDataDocument['ohdIVFrmTaxAdd']),
            );

            $this->db->trans_begin();
            // Check Auto GenCode Document
            if ($tIVAutoGenCode == '1') {
                $aStoreParam    = array(
                    "tTblName"  => $aTableAddUpdate['tTableHD'],
                    "tDocType"  => $aTableAddUpdate['tTableStaGen'],
                    "tBchCode"  => $aDataDocument['ohdIVBchCode'],
                    "tShpCode"  => "",
                    "tPosCode"  => "",
                    "dDocDate"  => date("Y-m-d")
                );
                $aAutogen   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo']   = $tIVDocNo;
            }
            $aTableAddUpdate['refType']     = $aDataDocument['ohdIVSPLStaLocal'];
            $aDataWhereDocRef_Type3 = array(
                'FTAgnCode'         => $aDataDocument['ohdIVADCode'],
                'FTBchCode'         => $aDataDocument['ohdIVBchCode'],
                'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                'FTXshRefType'      => 3,
                'FTXshRefDocNo'     => $aDataDocument['oetIVRefSBInt'],
                'FTXshRefKey'       => 'BillNote',
                'FDXshRefDocDate'   => (!empty($aDataDocument['oetIVRefSBIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetIVRefSBIntDate'])) : NULL
            );
            // [Add] Update Document HD
            $this->Invoice_model->FSxMIVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
            // [Add] Update Document SPL
            $this->Invoice_model->FSxMIVAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);
            // [Update] DocNo -> Temp
            $this->Invoice_model->FSxMIVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
            // [Add] Move Doc HDDisTemp -> HDDis
            $this->Invoice_model->FSaMIVMoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);
            // [Add] Doc DTTemp -> DT
            $this->Invoice_model->FSaMIVMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);
            // [Add] Doc DTDisTemp -> DTDis
            $this->Invoice_model->FSaMIVMoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);
            // [Move] Doc TCNTDocHDRefTmp 
            $this->Invoice_model->FSxMIVMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
            // อ้างอิง Ref EX (Type3)
            $this->Invoice_model->FSxMIVUpdateRef('TAPTPiHDDocRef',$aDataWhereDocRef_Type3);

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $aDataWhere['FTXphDocNo'],
                    'tEventName'    => 'บันทึกใบซื้อสินค้า',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXphDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.',
                    //เพิ่มใหม่
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $aDataWhere['FTXphDocNo'],
                    'tEventName'    => 'บันทึกใบซื้อสินค้า',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );

                // เช็ค Input Vat ว่ามีการเปลี่ยน Vat หรือไหม เพื่อไปคำนวณ HD Or DT ใหม่
                if($tIVSumFCXtdVatKey != $tIVSumFCXtdVatCal){
                    $this->FSxCIVEventCalcVatKeyInputUser($aDataDocument,$aDataWhere['FTXphDocNo']);
                }

            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType'      => 'ERROR',
                'tDocNo'        => $aDataWhere['FTXphDocNo'],
                'tEventName'    => 'บันทึกใบซื้อสินค้า',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    //แก้ไขข้อมูล HD DT
    public function FSxCIVEventEdit(){
        try {
            $aDataDocument      = $this->input->post();
            // ========================== Vat Check Edit Input Or Systems ==========================
            $tIVSumFCXtdVatKey  = floatval($aDataDocument['oetIVSumFCXtdVat']);   // Vat ที่มาจากการ Key จาก Input ด้วย User
            $tIVSumFCXtdVatCal  = floatval($aDataDocument['ohdIVVatCal']);   // Vat ที่มาจากการคำนวณของระบบ
            // =====================================================================================
            $tIVDocNo       = (isset($aDataDocument['oetIVDocNo'])) ? $aDataDocument['oetIVDocNo'] : '';
            $tIVDocDate     = $aDataDocument['oetIVDocDate'] . " " . $aDataDocument['oetIVDocTime'];
            $tIVVATInOrEx   = $aDataDocument['ocmIVfoVatInOrEx'];
            $tIVSessionID   = $this->session->userdata('tSesSessionID');
            if($aDataDocument['ohdIVStaApv'] == 1){ 
                //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว
                // Array Data update
                $aDataUpdate = array(
                    'FTBchCode'     => $aDataDocument['ohdIVBchCode'],
                    'FTXphDocNo'    => $tIVDocNo,
                    'FTXphRmk'      => $aDataDocument['otaIVRemark'],
                );
                $this->db->trans_begin();
                // [Update] update หมายเหตุ
                $this->Invoice_model->FSaMIVUpdateRmk($aDataUpdate);
            } else { 
                //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ
                FCNaHCalculateProrate('TAPTPiDT', $tIVDocNo);
                $aCalcDTParams = [
                    'tBchCode'          => $aDataDocument['ohdIVBchCode'],
                    'tDataDocEvnCall'   => '12',
                    'tDataVatInOrEx'    => $tIVVATInOrEx,
                    'tDataDocNo'        => $tIVDocNo,
                    'tDataDocKey'       => 'TAPTPiDT',
                    'tDataSeqNo'        => ''
                ];
                FCNbHCallCalcDocDTTemp($aCalcDTParams);
                $aCalDTTempParams = [
                    'tDocNo'            => $tIVDocNo,
                    'tBchCode'          => $aDataDocument['ohdIVBchCode'],
                    'tSessionID'        => $tIVSessionID,
                    'tDocKey'           => 'TAPTPiDT',
                    'tDataVatInOrEx'    => $tIVVATInOrEx,
                ];
                $this->Invoice_model->FSaMIVCalVatLastDT($aCalDTTempParams);
                $aCalDTTempForHD    = $this->FSaCIVCalDTTempForHD($aCalDTTempParams);
                $aCalInHDDisTemp    = $this->Invoice_model->FSaMIVCalInHDDisTemp($aCalDTTempParams);
                // Array Data Table Document
                $aTableAddUpdate    = array(
                    'tTableHD'      => 'TAPTPiHD',
                    'tTableHDDis'   => 'TAPTPiHDDis',
                    'tTableHDSPL'   => 'TAPTPiHDSpl',
                    'tTableDT'      => 'TAPTPiDT',
                    'tTableDTDis'   => 'TAPTPiDTDis',
                    'tTableStaGen'  => 1
                );
                // Array Data Where Insert
                $aDataWhere     = array(
                    'FTAgnCode'         => $aDataDocument['ohdIVADCode'],
                    'FTBchCode'         => $aDataDocument['ohdIVBchCode'],
                    'FTXphDocNo'        => $tIVDocNo,
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                    'FTXthVATInOrEx'    => $tIVVATInOrEx
                );
                $aDataMaster    = array(
                    'FTShpCode'             => '',
                    'FNXphDocType'          => 12,
                    'FDXphDocDate'          => (!empty($tIVDocDate)) ? $tIVDocDate : NULL,
                    'FTXphCshOrCrd'         => $aDataDocument['ocmIVPaymentType'],
                    'FTXphVATInOrEx'        => $tIVVATInOrEx,
                    'FTDptCode'             => '',
                    'FTSplCode'             => $aDataDocument['ohdIVSPLCode'],
                    'FTUsrCode'             => $this->session->userdata('tSesUsername'),
                    'FTXphRefAE'            => '',
                    'FTRteCode'             => $aDataDocument['ohdIVRateCode'],
                    'FCXphRteFac'           => 0,
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
                    'FCXphAmtV'             => $aCalDTTempForHD['FCXphAmtV'],
                    'FCXphAmtNV'            => $aCalDTTempForHD['FCXphAmtNV'],
                    'FCXphVat'              => $aCalDTTempForHD['FCXphVat'],
                    'FCXphVatable'          => $aCalDTTempForHD['FCXphVatable'],                    
                    'FTXphWpCode'           => $aCalDTTempForHD['FTXphWpCode'],
                    'FCXphWpTax'            => $aCalDTTempForHD['FCXphWpTax'],
                    'FCXphGrand'            => $aCalDTTempForHD['FCXphGrand'],
                    'FCXphRnd'              => $aCalDTTempForHD['FCXphRnd'],
                    'FTXphRmk'              => $aDataDocument['otaIVRemark'],
                    'FTXphGndText'          => $aCalDTTempForHD['FTXphGndText'],
                    'FTXphStaDoc'           => $aDataDocument['ohdIVStaDoc'],
                    'FNXphStaDocAct'        => (isset($aDataDocument['ocbIVFrmInfoOthStaDocAct'])) ? 1 : 0,
                    'FNXphStaRef'           => $aDataDocument['ocmIVFrmInfoOthRef'],

                    /** Vat คำนวณด้วยระบบ */
                    'FCXphVatCal'           => $tIVSumFCXtdVatCal,               
                );
                // Array Data SPL
                $aDataSPL = array(
                    'FTBchCode'             => $aDataDocument['ohdIVBchCode'],
                    'FTXphDocNo'            => $tIVDocNo,
                    'FTXphDstPaid'          => $aDataDocument['ocmIVDstPaid'],
                    'FNXphCrTerm'           => ($aDataDocument['ocmIVPaymentType'] == 1) ? NULL : $aDataDocument['oetIVCreditTerm'],
                    'FDXphDueDate'          => !empty($aDataDocument['oetIVEffectiveDate']) ? $aDataDocument['oetIVEffectiveDate']: NULL,
                    'FTXphCtrName'          => $aDataDocument['oetIVCtrName'],
                    'FDXphTnfDate'          => (!empty($aDataDocument['oetIVTnfDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetIVTnfDate'])) : NULL,
                    'FTXphRefTnfID'         => $aDataDocument['oetIVRefTnfID'],
                    'FTXphRefVehID'         => $aDataDocument['oetIVVehID'],
                    'FTXphRefInvNo'         => '',
                    'FTXphQtyAndTypeUnit'   => '',
                    'FNXphShipAdd'          => intval($aDataDocument['ohdIVFrmShipAdd']),
                    'FNXphTaxAdd'           => intval($aDataDocument['ohdIVFrmTaxAdd']),
                );
                $this->db->trans_begin();
                $aTableAddUpdate['refType'] = $aDataDocument['ohdIVSPLStaLocal'];
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => $aDataDocument['ohdIVADCode'],
                    'FTBchCode'         => $aDataDocument['ohdIVBchCode'],
                    'FTXshDocNo'        => $aDataWhere['FTXphDocNo'],
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetIVRefSBInt'],
                    'FTXshRefKey'       => 'BillNote',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetIVRefSBIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetIVRefSBIntDate'])) : NULL
                );

                // [Add] Update Document HD
                $this->Invoice_model->FSxMIVAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
                // [Add] Update Document HDSPL
                $this->Invoice_model->FSxMIVAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);
                // [Update] DocNo -> Temp
                $this->Invoice_model->FSxMIVAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);
                // [Add] Move Doc HDDisTemp -> HDDis
                $this->Invoice_model->FSaMIVMoveHDDisTempToHDDis($aDataWhere, $aTableAddUpdate);
                // [Add] Move DTTemp -> DT
                $this->Invoice_model->FSaMIVMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);
                // [Add] Move DTDisTemp -> DTDis
                $this->Invoice_model->FSaMIVMoveDTDisTempToDTDis($aDataWhere, $aTableAddUpdate);
                // [Move] Doc TCNTDocHDRefTmp 
                $this->Invoice_model->FSxMIVMoveHDRefTmpToHDRef($aDataWhere, $aTableAddUpdate);
                // อ้างอิง Ref EX (Type3)
                $this->Invoice_model->FSxMIVUpdateRef('TAPTPiHDDocRef',$aDataWhereDocRef_Type3);
            }
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Edit Document.",
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tIVDocNo,
                    'tEventName'    => 'แก้ไขและบันทึกใบซื้อสินค้า',
                    'nLogLevel'     => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $tIVDocNo,
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Edit Document.',
                    //เพิ่มใหม่
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tIVDocNo,
                    'tEventName'    => 'แก้ไขและบันทึกใบซื้อสินค้า',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );


                // เช็ค Input Vat ว่ามีการเปลี่ยน Vat หรือไหม เพื่อไปคำนวณ HD Or DT ใหม่
                if($tIVSumFCXtdVatKey != $tIVSumFCXtdVatCal){
                    $this->FSxCIVEventCalcVatKeyInputUser($aDataDocument,$tIVDocNo);
                }

            }
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tIVDocNo,
                'tEventName'    => 'แก้ไขและบันทึกใบซื้อสินค้า',
                'nLogLevel'     => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        $nStaApvOrSave = $aDataDocument['ohdIVApvOrSave'];
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 
        echo json_encode($aReturnData);
    }


    // เช็ค Input Vat ว่ามีการเปลี่ยน Vat หรือไหม เพื่อไปคำนวณ HD Or DT ใหม่
    public function FSxCIVEventCalcVatKeyInputUser($aDataDocument,$tDocNo){
        $tIVDocNo           = $tDocNo;
        $tIVVATInOrEx       = $aDataDocument['ocmIVfoVatInOrEx'];
        $tIVBchCode         = $aDataDocument['ohdIVBchCode'];
        $tIVVatUsrKey       = floatval($aDataDocument['oetIVSumFCXtdVat']);
        $aCalDTTempParams   = [
            'tBchCode'          => $tIVBchCode,
            'tDocNo'            => $tIVDocNo,
            'tDataVatInOrEx'    => $tIVVATInOrEx,
            'tInputVatUsrKey'   => $tIVVatUsrKey
        ];
        $this->Invoice_model->FSaMIVCalVatLastDTUsrKeyManual($aCalDTTempParams);
        $aCalDTTempForHD    = $this->FSaCIVCalDTForHDKeyInputUser($aCalDTTempParams);
        // Update Vat And Vat Table HD
        $aDataReturn = $this->Invoice_model->FSaMIVUpdHDKeyInputUser($aCalDTTempForHD,$aCalDTTempParams);
        return $aDataReturn;
    }


    // คำนวณจาก DT ให้ HD
    private function FSaCIVCalDTForHDKeyInputUser($paParams) {
        $aCalDTTemp = $this->Invoice_model->FSaMIVCalInDTKeyInputUser($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems    = $aCalDTTemp[0];
            $nRound             = 0;
            $cGrand             = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV'];
            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXphRnd']        = $nRound;
            $aCalDTTempItems['FCXphGrand']      = $cGrand;
            $aCalDTTempItems['FTXphGndText']    = $tGndText;
            return $aCalDTTempItems;
        }
    }

    //คำนวณจาก DT Temp ให้ HD
    private function FSaCIVCalDTTempForHD($paParams) {
        $aCalDTTemp = $this->Invoice_model->FSaMIVCalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems    = $aCalDTTemp[0];
            $nRound             = 0;
            $cGrand             = $aCalDTTempItems['FCXphAmtV'] + $aCalDTTempItems['FCXphAmtNV'];
            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXphRnd']        = $nRound;
            $aCalDTTempItems['FCXphGrand']      = $cGrand;
            $aCalDTTempItems['FTXphGndText']    = $tGndText;
            return $aCalDTTempItems;
        }
    }



    //Edit Inline สินค้า ลง Document DT Temp
    public function FSoCIVEditPdtIntoDocDTTemp() {
        try {
            $tIVBchCode = $this->input->post('tIVBchCode');
            $tIVDocNo   = $this->input->post('tIVDocNo');
            $nIVSeqNo   = $this->input->post('nIVSeqNo');
            $nStaDelDis = $this->input->post('nStaDelDis');
            $aDataWhere = array(
                'tIVBchCode'    => $tIVBchCode,
                'tIVDocNo'      => $tIVDocNo,
                'nIVSeqNo'      => $nIVSeqNo,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'TAPTPiDT',
                'nStaDelDis'    =>  $nStaDelDis
            );
            $aDataUpdateDT = array(
                'FCXtdQty'      => $this->input->post('nQty'),
                'FTXtdPdtName'  => $this->input->post('FTXtdPdtName'),
                'FCXtdSetPrice' => $this->input->post('cPrice'),
                'FCXtdNet'      => $this->input->post('cNet')
            );
            $this->db->trans_begin();
            $this->Invoice_model->FSaMIVUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);
            if ($nStaDelDis == '1') {
                // ยืนยันการลบ DTDis ส่วนลดรายการนี้
                $this->Invoice_model->FSaMIVDeleteDTDisTemp($aDataWhere);
                $this->Invoice_model->FSaMIVClearDisChgTxtDTTemp($aDataWhere);
            }
            //ให้มันคำนวณส่วนลดท้ายบิลใหม่อีกครั้ง
            $this->FSxCalculateHDDisAgain($tIVDocNo,$tIVBchCode);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => "Error Update Inline Into Document DT Temp."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData    = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => "Update Inline Into Document DT Temp."
                );
            }
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //ลบข้อมูลเอกสาร
    public function FSoCIVEventDelete(){
        try {
            $tDataDocNo     = $this->input->post('tDataDocNo');
            $aDataMaster    = array(
                'tDataDocNo'    => $tDataDocNo
            );
            $aResDelDoc = $this->Invoice_model->FSnMIVDelDocument($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Delete Document Success',
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDataDocNo,
                    'tEventName'    => 'ลบใบซื้อสินค้า',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent'     => $aResDelDoc['rtCode'],
                    'tStaMessg'     => $aResDelDoc['rtDesc'],
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tDataDocNo,
                    'tEventName'    => 'ลบใบซื้อสินค้า',
                    'nLogLevel'     => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                'tLogType' => 'ERROR',
                'tDocNo'        => $tDataDocNo,
                'tEventName' => 'ลบใบซื้อสินค้า',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aDataStaReturn);
        echo json_encode($aDataStaReturn);
    }

    //หน้าจอแก้ไข
    public function FSvCIVEditPage(){
        try {
            $ptDocumentNumber   = $this->input->post('ptIVDocNo');
            // Clear Data In Doc DT Temp
            $aWhereClearTemp    = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->Invoice_model->FSnMIVDelALLTmp($aWhereClearTemp);
            // Get Autentication Route
            FCNaHCheckAlwFunc($this->tRouteMenu);
            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            // Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");
            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere         = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FTXthDocKey'   => 'TAPTPiDT',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 90000,
                'nPage'         => 1,
            );
            $this->db->trans_begin();
            // Get Data Document HD
            $aDataDocHD         = $this->Invoice_model->FSaMIVGetDataDocHD($aDataWhere);
            // Get Data Document SPL HD
            $aDataDocSPLHD      = $this->Invoice_model->FSaMIVGetDataDocSPLHD($aDataWhere);
            // Get ที่อยู่สำหรับจัดส่ง / ที่อยู่สำหรับออกใบกำกับภาษี
            $aDataDocAddr       =  $this->Invoice_model->FSxMIVGetAddress($aDataWhere);
            // Move Data HD DIS To HD DIS Temp
            $this->Invoice_model->FSxMIVMoveHDDisToTemp($aDataWhere);
            // Move Data DT TO DTTemp
            $this->Invoice_model->FSxMIVMoveDTToDTTemp($aDataWhere);
            // Move Data DTDIS TO DTDISTemp
            $this->Invoice_model->FSxMIVMoveDTDisToDTDisTemp($aDataWhere);
            // Move Data HDDocRef To HDRefTemp
            $this->Invoice_model->FSxMIVMoveHDRefToHDRefTemp($aDataWhere);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData    = array(
                    'nStaEvent'     => '500',
                    'tStaMessg'     => 'Error Query Call Edit Page.',
                    //เพิ่มใหม่
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $ptDocumentNumber,
                    'tEventName'    => 'เรียกดูเอกสารใบซื้อสินค้า',
                    'nLogLevel'     => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXphApvCode']
                );
            } else {
                $this->db->trans_commit();
                $aDataConfigViewAdd     = array(
                    'nOptDecimalShow'   => $nOptDecimalShow,
                    'aRateDefault'      => '',
                    'aDataDocHD'        => $aDataDocHD,
                    'aDataDocSPLHD'     => $aDataDocSPLHD,
                    'aDataDocAddr'      => $aDataDocAddr,
                    'nStaShwAddress'    => $this->Invoice_model->FSnMIVGetConfigShwAddress()
                );
                $tViewPageAdd           = $this->load->view('document/invoice/wInvoicePageAdd',$aDataConfigViewAdd,true);
                $aReturnData = array(
                    'tViewPageAdd'      => $tViewPageAdd,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Call Page Success'
                );
                $aReturn = array(
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Call Page Success',
                    //เพิ่มใหม่
                    'tLogType'          => 'INFO',
                    'tDocNo'            => $ptDocumentNumber,
                    'tEventName'        => 'เรียกดูเอกสารใบซื้อสินค้า',
                    'nLogCode'          => '001',
                    'nLogLevel'         => '',
                    'FTXphUsrApv'       => $aDataDocHD['raItems']['FTXphApvCode']
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );

            $aReturn = array(
                'nStaEvent'     => '1',
                'tStaMessg'     => 'Call Page Success',
                //เพิ่มใหม่
                'tLogType'      => 'INFO',
                'tDocNo'        => $ptDocumentNumber,
                'tEventName'    => 'เรียกดูเอกสารใบซื้อสินค้า',
                'nLogCode'      => '001',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXphApvCode']
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturnData);
    }

    //ยกเลิกเอกสาร
    public function FSoCIVUpdateStaDocCancel(){
        $tDocNo     = $this->input->post('tDocNo');
        $tRefIntDoc = $this->input->post('tRefIntDoc');
        $aDataUpdate = array(
            'FTXphDocNo'    => $tDocNo,
            'FTXphStaDoc'   => '3',
            'tRefInt'       => $tRefIntDoc
        );
        $aStaDoc    = $this->Invoice_model->FSaMIVUpdateStaDocCancel($aDataUpdate);
        $aReturn    = array(
            'rtCode'        => $aStaDoc['rtCode'],
            'tStaMessg'     => $aStaDoc['rtDesc'],
            'tLogType'      => 'INFO',
            'tDocNo'        => $tDocNo,
            'tEventName'    => 'ยกเลิกใบซื้อสินค้า',
            'nLogCode'      => '001',
            'nLogLevel'     => '',
            'FTXphUsrApv'   => ''
        );
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    //อนุมัติเอกสาร
    public function FSoCIVApproveEvent(){
        $tApvCode = $this->session->userdata('tSesUsername');
        if (empty($tApvCode) && $tApvCode == '') {
            $tApvCode = get_cookie('tUsrCode');
        }
        $tDocNo       = $this->input->post('tDocNo');
        try{
            $tBchCode   = $this->input->post('tBchCode');
            $tRefIntDoc = $this->input->post('tRefIntDoc');
            $aMQParams  = [
                "queueName" => "PURCHASEINV",
                "params"    => [
                    "ptBchCode" => $tBchCode,
                    "ptDocNo"   => $tDocNo,
                    "ptDocType" => 12,
                    "ptUser"    => $this->session->userdata('tSesUsername'),
                ]
            ];
            $aStaReturn = FCNxCallRabbitMQ($aMQParams);
            if ($aStaReturn['rtCode'] == 1) {
                $aDataUpdate    = array(
                    'FTBchCode'     => $tBchCode,
                    'FTXphDocNo'    => $tDocNo,
                    'FTXphStaApv'   => 1,
                    'FTXphUsrApv'   => $this->session->userdata('tSesUsername'),
                    'tRefInt'       => $tRefIntDoc,
                );
                $this->Invoice_model->FSaMIVApproveDocument($aDataUpdate);
                $aReturnData    = array(
                    'nStaEvent'     => '1',
                    'tStaMessg'     => "Approve Document Success",
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบซื้อสินค้า',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $tApvCode
                );
            }else{
                $aReturnData    = array(
                    'nStaEvent'     => '905',
                    'tStaMessg'     => 'Connect Rabbit MQ Fail'.' '.$aStaReturn['rtDesc'],
                    'tLogType'      => 'EVENT',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบซื้อสินค้า',
                    'nLogCode'      => '905',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $tApvCode
                );
            }
        } catch (Exception $Error) {
            $aReturnData    = array(
                'nStaEvent'     => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDocNo,
                'tEventName'    => 'อนุมัติใบซื้อสินค้า',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $tApvCode
            );
        }
        if ($aReturnData['nStaEvent'] != 905) {
            //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
            FSoCCallLogMQ($aReturnData); 
        }
        echo json_encode($aReturnData);
    }

    //////////////////////////////////////////// อ้างอิงเอกสารภายใน //////////////////////////

    //อ้างอิงเอกสารภายใน (ref DO + PO)
    public function FSoCIVCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');
        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        $this->load->view('document/invoice/refintdocument/wInvoiceRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCIVCallRefIntDocDataTable(){
        $nPage                  = $this->input->post('nIVRefIntPageCurrent');
        $tIVRefIntBchCode       = $this->input->post('tIVRefIntBchCode');
        $tIVRefIntDocNo         = $this->input->post('tIVRefIntDocNo');
        $tIVRefIntDocDateFrm    = $this->input->post('tIVRefIntDocDateFrm');
        $tIVRefIntDocDateTo     = $this->input->post('tIVRefIntDocDateTo');
        $tIVRefIntStaDoc        = $this->input->post('tIVRefIntStaDoc');
        $tIVSPLCode             = $this->input->post('tIVSPLCode');
        $tIVSPLStaLocal         = $this->input->post('tIVSPLStaLocal');
        $tIVTypeRefDoc          = $this->input->post('tIVTypeRefDoc');

        // Page Current
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nIVRefIntPageCurrent');
        }
        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        $aDataParamFilter = array(
            'tIVRefIntBchCode'      => $tIVRefIntBchCode,
            'tIVRefIntDocNo'        => $tIVRefIntDocNo,
            'tIVRefIntDocDateFrm'   => $tIVRefIntDocDateFrm,
            'tIVRefIntDocDateTo'    => $tIVRefIntDocDateTo,
            'tIVRefIntStaDoc'       => $tIVRefIntStaDoc,
            'tIVSPLCode'            => $tIVSPLCode,
            'tIVSPLStaLocal'        => $tIVSPLStaLocal
        );
        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        if($tIVSPLStaLocal == 1){ //อ้างอิงเอกสารใบรับของ
            $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDataTable_DO($aDataCondition);
        }else{ 
            if($tIVTypeRefDoc == 2){ //อ้างอิงเอกสารใบซื้อสินค้า
                $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDataTable_PO($aDataCondition);
            }else{ //อ้างอิงเอกสารใบขาย
                $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDataTable_ABB($aDataCondition);
            }
        }
        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );
        $this->load->view('document/invoice/refintdocument/wInvoiceRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCIVCallRefIntDocDetailDataTable(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $tIVSPLStaLocal     = $this->input->post('ptSPLStaLocal');
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        $tIVTypeRefDoc      = $this->input->post('tIVTypeRefDoc');

        $aDataCondition     = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        if($tIVSPLStaLocal == 1){ //อ้างอิงเอกสารใบรับของ
            $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDTDataTable_DO($aDataCondition);
        }else{ //อ้างอิงเอกสารใบซื้อสินค้า
            if($tIVTypeRefDoc == 2){ //อ้างอิงเอกสารใบซื้อสินค้า
                $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDTDataTable_PO($aDataCondition);
            }else{ //อ้างอิงเอกสารใบขาย
                $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocDTDataTable_ABB($aDataCondition);
            }
        }
        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow,
            'tIVSPLStaLocal'    => $tIVSPLStaLocal,
            'tIVTypeRefDoc'     => $tIVTypeRefDoc
          );
        $this->load->view('document/invoice/refintdocument/wInvoiceRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp dt
    public function FSoCIVCallRefIntDocInsertDTToTemp(){
        $tIVDocNo           =  $this->input->post('tIVDocNo');
        $tIVFrmBchCode      =  $this->input->post('tIVFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
        $tSplStaVATInOrEx   =  $this->input->post('tSplStaVATInOrEx');
        $tIVSPLStaLocal     =  $this->input->post('tIVSPLStaLocal');
        $tIVTypeRefDoc      = $this->input->post('tIVTypeRefDoc');

        $aDataParam         = array(
            'tIVDocNo'       => $tIVDocNo,
            'tIVFrmBchCode'  => $tIVFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );
        $aDataParamUpdateSeq = array(
            'tIVDocNo'       => $tIVDocNo,
            'tIVFrmBchCode'  => $tIVFrmBchCode,
            'tRefIntDocNo'   => $tRefIntDocNo,
            'tRefIntBchCode' => $tRefIntBchCode,
            'aSeqNo'         => $aSeqNo,
        );

        if($tIVSPLStaLocal == 1){ //อ้างอิงเอกสารใบรับของ
            $aDataParam = $this->Invoice_model->FSoMIVCallRefIntDocInsertDTToTemp_DO($aDataParam);
            $this->Invoice_model->FSoMIVUpdateSeqAfterRef($aDataParamUpdateSeq);
        }else{ 
            if($tIVTypeRefDoc == 2){ //อ้างอิงเอกสารใบซื้อสินค้า
                $aDataResult = $this->Invoice_model->FSoMIVCallRefIntDocInsertDTToTemp_PO($aDataParam);
            }else{ //อ้างอิงเอกสารใบขาย
                $aDataResult = $this->Invoice_model->FSoMIVCallRefIntDocInsertDTToTemp_ABB($aDataParam);
            }

            $this->Invoice_model->FSoMIVUpdateSeqAfterRef($aDataParamUpdateSeq);
        }
        // Calcurate Document DT Temp Array Parameter
        $aCalcDTParams = [
            'tDataDocEvnCall'   => '12',
            'tDataVatInOrEx'    => $tSplStaVATInOrEx,
            'tDataDocNo'        => $tIVDocNo,
            'tDataDocKey'       => 'TAPTPiDT',
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);
        $this->FSxCalculateHDDisAgain($tIVDocNo,$tRefIntBchCode);
        return  $aDataResult;
    }
   //////////////////////////////////////////////////// อ้างอิงเอกสาร ////////////////////////////////////////////////////

    // ค่าอ้างอิงเอกสาร - โหลดข้อมูล
    public function FSoCIVPageHDDocRef(){
        try {
            $tDocNo     = $this->input->post('ptDocNo');
            $aDataWhere = [
                'tTableHDDocRef'    => 'TAPTPiHDDocRef',
                'tTableTmpHDRef'    => 'TCNTDocHDRefTmp',
                'FTXshDocNo'        => $tDocNo,
                'FTXshDocKey'       => 'TAPTPiDT',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aDataDocHDRef  = $this->Invoice_model->FSaMIVGetDataHDRefTmp($aDataWhere);
            $aDataConfig    = array(
                'aDataDocHDRef' => $aDataDocHDRef
            );
            $tViewPageHDRef = $this->load->view('document/invoice/wInvoiceDocRef', $aDataConfig, true);
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
    public function FSoCIVEventAddEditHDDocRef(){
        try {
            $aDataWhere = [
                'FTXshDocNo'        => $this->input->post('ptIVDocNo'),
                'FTXshDocKey'       => 'TAPTPiDT',
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'tIVRefDocNoOld'   => $this->input->post('ptRefDocNoOld'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aDataAddEdit = [
                'FTXthRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXthRefType'      => $this->input->post('ptRefType'),
                'FTXthRefKey'       => $this->input->post('ptRefKey'),
                'FDXthRefDocDate'   => date('Y-m-d h:i:s', strtotime($this->input->post('pdRefDocDate'))),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
            ];
            $aReturnData = $this->Invoice_model->FSaMIVAddEditHDRefTmp($aDataWhere,$aDataAddEdit);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // ค่าอ้างอิงเอกสาร - ลบ
    public function FSoCIVEventDelHDDocRef(){
        try {
            $aData = [
                'FTXshDocNo'        => $this->input->post('ptDocNo'),
                'FTXshRefDocNo'     => $this->input->post('ptRefDocNo'),
                'FTXshDocKey'       => 'TAPTPiDT',
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            ];
            $aReturnData = $this->Invoice_model->FSaMIVDelHDDocRef($aData);
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

}
