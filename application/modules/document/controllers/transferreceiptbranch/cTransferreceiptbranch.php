<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cTransferreceiptbranch extends MX_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('document/transferreceiptbranch/mTransferreceiptbranch');
        $this->load->model('company/company/mCompany');
        $this->load->model('payment/rate/mRate');
    }

    public function index($nBrowseType, $tBrowseOption, $nDocType){
        //เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
        $aCookieMenuCode = array(
            'name'	=> 'tMenuCode',
            'value' => json_encode('TXO008'),
            'expire' => 0
        );
        $this->input->set_cookie($aCookieMenuCode);
        $aCookieMenuName = array(
            'name'	=> 'tMenuName',
            'value' => json_encode('ใบรับโอน - สาขา'),
            'expire' => 0
        );

        $this->input->set_cookie($aCookieMenuName);
        //end
        $aParams=array(
            'tDocNo' => $this->input->post('tDocNo'),
            'tBchCode' => $this->input->post('tBchCode'),
            'tAgnCode' => $this->input->post('tAgnCode'),
        );
        $aDataConfigView    = array(
            'nBrowseType'       => $nBrowseType,
            'tBrowseOption'     => $tBrowseOption,
            'nDocType'          => $nDocType,
            'aPermission'       => FCNaHCheckAlwFunc('docTBI/0/0/' . $nDocType),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('docTBI/0/0/' . $nDocType),
            'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            'nOptDecimalSave'   => get_cookie('tOptDecimalSave'),
            'aParams'            => $aParams ,
        );
        $this->load->view('document/transferreceiptbranch/wTransferreceiptbranch', $aDataConfigView);
        unset($aCookieMenuCode);
        unset($aCookieMenuName);
        unset($aParams);
        unset($aDataConfigView);
    }

    //Page - List
    public function FSxCTBIPageList(){
        $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchSearchList');
    }

    //Page - DataTable
    public function FSxCTBIPageDataTable(){
        $tAdvanceSearchData     = $this->input->post('oAdvanceSearch');
        $nPage                  = $this->input->post('nPageCurrent');
        $nTBIDocType            = $this->input->post('nTBIDocType');
        $aAlwEvent              = FCNaHCheckAlwFunc('docTBI/0/0/' . $nTBIDocType);
        $nOptDecimalShow        = get_cookie('tOptDecimalShow');
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
            'nTBIDocType'       => $nTBIDocType,
            'aAdvanceSearch'    => $tAdvanceSearchData
        );
        $aResList   = $this->mTransferreceiptbranch->FSaMTBIList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => $aAlwEvent,
            'aDataList'         => $aResList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $tTBIViewDataTable = $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchDataTable', $aGenTable, true);
        $aReturnData = array(
            'tViewDataTable'    => $tTBIViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
        unset($tAdvanceSearchData);
        unset($nPage);
        unset($nTBIDocType);
        unset($aAlwEvent);
        unset($nOptDecimalShow);
        unset($nLangEdit);
        unset($aData);
        unset($aResList);
        unset($aGenTable);
        unset($tTBIViewDataTable);
        unset($aReturnData);
    }

    //Page - Add
    public function FSvCTBIPageAdd(){
        try {
            // Clear Product List IN Doc Temp
            $tTblSelectData = [
                'FTXthDocKey'   => 'TCNTPdtTBIHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->mTransferreceiptbranch->FSxMTBIClearPdtInTmp($tTblSelectData);
            // Get Option Show Decimal
            $nOptDecimalShow        = get_cookie('tOptDecimalShow');
            // Get Option Doc Save
            $nOptDocSave            = get_cookie('tOptDocSave');
            // Get Option Scan SKU
            $nOptScanSku            = get_cookie('tOptScanSku');
            $aDataConfigViewAdd     = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'nOptDocSave'       => $nOptDocSave,
                'nOptScanSku'       => $nOptScanSku,
                'tDptCode'          => $this->session->userdata("tSesUsrDptCode"),
                'aDataDocHD'        => array('rtCode' => '99'),
                'nStaWasteWAH'      => FCNbIsGetRoleWasteWAH()
            );
            $tViewPageAdd           = $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchPageAdd', $aDataConfigViewAdd, true);
            $aReturnData            = array(
                'tViewPageAdd'      => $tViewPageAdd,
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

    public function FSxCTBIEventClearTemp(){
        $aWhereClearTemp = [
            'FTXthDocKey'   => 'TCNTPdtTBIHD',
            'FTSessionID'   => $this->session->userdata('tSesSessionID')
        ];
        $this->mTransferreceiptbranch->FSxMTBIClearPdtInTmp($aWhereClearTemp);
    }

    //Page - Edit
    public function FSvCTBIPageEdit(){
        try {
            $tTBIDocNo      = $this->input->post('ptDocNumber');
            $tTBIDocType    = $this->input->post('ptTBIDocType');
            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTXthDocNo'    => $tTBIDocNo,
                'FTXthDocKey'   => 'TCNTPdtTBIHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->mTransferreceiptbranch->FSxMTBIClearPdtInTmp($aWhereClearTemp);
            $aAlwEvent          = FCNaHCheckAlwFunc('docTBI/0/0/' . $tTBIDocType);
            // Get Option Show Decimal
            $nOptDecimalShow    = get_cookie('tOptDecimalShow');
            // Get Option Doc Save
            $nOptDocSave        = get_cookie('tOptDocSave');
            // Get Option Scan SKU
            $nOptScanSku        = get_cookie('tOptScanSku');
            //Lang ภาษา
            $nLangEdit          = $this->session->userdata("tLangEdit");
            // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)
            $aDataWhere = array(
                'FTXthDocNo'    => $tTBIDocNo,
                'FTXthDocKey'   => 'TCNTPdtTBIHD',
                'FNLngID'       => $nLangEdit,
                'nRow'          => 10000,
                'nPage'         => 1,
            );
            $this->db->trans_begin();
            // Get Data Document HD
            $aDataDocHD = $this->mTransferreceiptbranch->FSaMTBIGetDataDocHD($aDataWhere);
            // Move Data DT TO DTTemp
            $this->mTransferreceiptbranch->FSxMTBIMoveDTToDTTemp($aDataWhere);
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
                    'nOptDocSave'       => $nOptDocSave,
                    'nOptScanSku'       => $nOptScanSku,
                    'aDataDocHD'        => $aDataDocHD,
                    'aAlwEvent'         => $aAlwEvent,
                    'nStaWasteWAH'      => FCNbIsGetRoleWasteWAH()
                );
                $tViewPageAdd   = $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchPageAdd', $aDataConfigViewAdd, true);
                $aReturnData    = array(
                    'tViewPageAdd'      => $tViewPageAdd,
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Success',
                    'tLogType'          => 'INFO',
                    'tDocNo'            => $tTBIDocNo,
                    'tEventName'        => 'เรียกดูเอกสารใบรับโอน - สาขา ',
                    'nLogCode'          => '001',
                    'nLogLevel'         => '',
                    'FTXphUsrApv'       => $aDataDocHD['raItems']['FTXthDocNo']
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent'     => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tTBIDocNo,
                'tEventName'    => 'เรียกดูเอกสารใบรับโอน - สาขา ',
                'nLogLevel'     => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $aDataDocHD['raItems']['FTXthDocNo']
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
        echo json_encode($aReturnData);
    }

    //Page - Product Table
    public function FSoCTBIPagePdtAdvTblLoadData(){
        try {
            $tTBIBchCode              = $this->input->post('ptTBIBchCode');
            $tTBIDocNo                = $this->input->post('ptTBIDocNo');
            $tTBIStaApv               = $this->input->post('ptTBIStaApv');
            $tTBIStaDoc               = $this->input->post('ptTBIStaDoc');
            $nTBIPageCurrent          = $this->input->post('pnTBIPageCurrent');
            $tSearchPdtAdvTable       = $this->input->post('ptSearchPdtAdvTable');
            $tVat                     = 1;
            // Edit in line
            $tTBIPdtCode              = '';
            $tTBIPunCode              = '';
            //Get Option Show Decimal
            $nOptDecimalShow            = get_cookie('tOptDecimalShow');
            // Call Advance Table
            $tTableGetColumeShow        = 'TCNTPdtTBIDT';
            $aDataWhere = array(
                'tSearchPdtAdvTable'    => $tSearchPdtAdvTable,
                'FTXthDocNo'            => $tTBIDocNo,
                'FTXthDocKey'           => 'TCNTPdtTbiHD',
                'nPage'                 => $nTBIPageCurrent,
                'nRow'                  => 10,
                'FTSessionID'           => $this->session->userdata('tSesSessionID'),
            );
            // Calcurate Document DT Temp Array Parameter
            $aCalcDTParams = [
                'tDataDocEvnCall'       => '1',
                'tDataVatInOrEx'        => $tVat,
                'tDataDocNo'            => $tTBIDocNo,
                'tDataDocKey'           => 'TCNTPdtTbiHD',
                'tDataSeqNo'            => ''
            ];
            $aDataDocDTTemp     = $this->mTransferreceiptbranch->FSaMTBIGetDocDTTempListPage($aDataWhere);
            $aDataDocDTTempSum  = $this->mTransferreceiptbranch->FSaMTBISumDocDTTemp($aDataWhere);
            $aDataView = array(
                'nOptDecimalShow'       => $nOptDecimalShow,
                'tTBIStaApv'            => $tTBIStaApv,
                'tTBIStaDoc'            => $tTBIStaDoc,
                'tTBIPdtCode'           => @$tTBIPdtCode,
                'tTBIPunCode'           => @$tTBIPunCode,
                'nPage'                 => $nTBIPageCurrent,
                // 'aColumnShow'           => $aColumnShow,
                'aDataDocDTTemp'        => $aDataDocDTTemp,
                'aDataDocDTTempSum'     => $aDataDocDTTempSum
            );
            $tTBIPdtAdvTableHtml = $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchPdtAdvTableData', $aDataView, true);
            // Call Footer Document
            $aEndOfBillParams = array(
                'tSplVatType'   => $tVat,
                'tDocNo'        => $tTBIDocNo,
                'tDocKey'       => 'TCNTPdtTBIHD',
                'nLngID'        => FCNaHGetLangEdit(),
                'tSesSessionID' => $this->session->userdata('tSesSessionID'),
                'tBchCode'      => $tTBIBchCode, //$this->session->userdata('tSesUsrLevel') == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata('tSesUsrBchCode')
            );
            $aReturnData = array(
                'tTBIPdtAdvTableHtml'   => $tTBIPdtAdvTableHtml,
                // 'aTBIEndOfBill'         => $aTBIEndOfBill,
                'nStaEvent'             => '1',
                'tStaMessg'             => "Fucntion Success Return View."
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //กดเลือกว่า หน้าต่างจะให้โชว์อะไรบ้าง 
    public function FSoCTBIPageAdvTblShowColList(){
        try {
            $tTableShowColums = 'TCNTPdtTbiDT';
            $aAvailableColumn = FCNaDCLAvailableColumn($tTableShowColums);
            $aDataViewAdvTbl = array(
                'aAvailableColumn' => $aAvailableColumn
            );
            $tViewTableShowCollist = $this->load->view('document/transferreceiptbranch/advancetable/wTransferrenceiptbranchTableShowColList', $aDataViewAdvTbl, true);
            $aReturnData = array(
                'tViewTableShowCollist' => $tViewTableShowCollist,
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //บันทึกข้อมูลหน้าจอที่ให้โชว์อะไรบ้าง
    public function FSoCTBIEventAdvTalShowColSave(){
        try {
            $this->db->trans_begin();

            $nTBIStaSetDef       = $this->input->post('pnTBIStaSetDef');
            $aTBIColShowSet      = $this->input->post('paTBIColShowSet');
            $aTBIColShowAllList  = $this->input->post('paTBIColShowAllList');
            $aTBIColumnLabelName = $this->input->post('paTBIColumnLabelName');

            $tTableShowColums    = "TCNTPdtTBIDT";
            FCNaDCLSetShowCol($tTableShowColums, '', '');
            if ($nTBIStaSetDef == '1') {
                FCNaDCLSetDefShowCol($tTableShowColums);
            } else {
                for ($i = 0; $i < FCNnHSizeOf($aTBIColShowSet); $i++) {
                    FCNaDCLSetShowCol($tTableShowColums, 1, $aTBIColShowSet[$i]);
                }
            }

            // Reset Seq Advannce Table
            FCNaDCLUpdateSeq($tTableShowColums, '', '', '');
            $q = 1;
            for ($n = 0; $n < FCNnHSizeOf($aTBIColShowAllList); $n++) {
                FCNaDCLUpdateSeq($tTableShowColums, $aTBIColShowAllList[$n], $q, $aTBIColumnLabelName[$n]);
                $q++;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Eror Not Save Colums'
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
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

    //เพิ่มสินค้าลงตาราง Tmp
    public function FSoCTBIEventAddPdtIntoDocDTTemp(){
        try {
            $tTBIDocNo      = $this->input->post('tTBIDocNo');
            $tTBIBchCode    = $this->input->post('tTBIBchCode'); //($tTBIUserLevel == 'HQ') ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCode");
            $tTBIPdtData    = $this->input->post('tTBIPdtData');
            $aTBIPdtData    = JSON_decode($tTBIPdtData);
            $tTBIVATInOrEx  = 1;
            $tTypeInsPDT    = $this->input->post('tType');
            $aDataWhere = array(
                'FTBchCode'     => $tTBIBchCode,
                'FTXthDocNo'    => $tTBIDocNo,
                'FTXthDocKey'   => 'TCNTPdtTbiHD',
            );
            $this->db->trans_begin();
            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aTBIPdtData); $nI++) {

                $aItem       = $aTBIPdtData[$nI];
                if ($tTypeInsPDT == 'CN') {
                    $tDocRefSO      = $aItem->tDocNo;
                    $tSeqItemSO     = $aItem->ptSeqItem;
                } else if ($tTypeInsPDT == 'PDT') {
                    $tDocRefSO      = '';
                    $tSeqItemSO     = '';
                }

                $tTBIPdtCode = $aItem->pnPdtCode;
                $tTBIBarCode = $aItem->ptBarCode;
                $tTBIPunCode = $aItem->ptPunCode;

                $cTBIPrice    = $this->mTransferreceiptbranch->FSaMTBIGetPriceBYPDT($tTBIPdtCode);
                if ($cTBIPrice[0]->PDTCostSTD == null) {
                    $nPrice = 0;
                } else {
                    $nPrice = $cTBIPrice[0]->PDTCostSTD;
                }

                $nTBIMaxSeqNo = $this->mTransferreceiptbranch->FSaMTBIGetMaxSeqDocDTTemp($aDataWhere);
                $aDataPdtParams = array(
                    'tDocNo'            => $tTBIDocNo,
                    'tBchCode'          => $tTBIBchCode,
                    'tPdtCode'          => $tTBIPdtCode,
                    'tBarCode'          => $tTBIBarCode,
                    'tPunCode'          => $tTBIPunCode,
                    'cPrice'            => $nPrice,
                    'nMaxSeqNo'         => $nTBIMaxSeqNo + 1,
                    'nLngID'            => $this->session->userdata("tLangID"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'TCNTPdtTBIHD',
                    'tDocRefSO'         => $tDocRefSO
                );

                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster     = $this->mTransferreceiptbranch->FSaMTBIGetDataPdt($aDataPdtParams);
                // นำรายการสินค้าเข้า DT Temp
                $nStaInsPdtToTmp    = $this->mTransferreceiptbranch->FSaMTBIInsertPDTToTemp($aDataPdtMaster, $aDataPdtParams);

                //ถ้าเลือกนำเข้าข้อมูล จะต้องไปทำให้สินค้าใน CN ว่าถูกใช้งานแล้ว
                if ($tTypeInsPDT == 'CN') {
                    //$this->mTransferreceiptbranch->FSaMTBIUpdatePDTInCN($tDocRefSO,$tSeqItemSO);
                }
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
                    'tDataVatInOrEx'    => $tTBIVATInOrEx,
                    'tDataDocNo'        => $tTBIDocNo,
                    'tDataDocKey'       => 'TCNTPdtTbiHD',
                    'tDataSeqNo'        => ''
                ];
                $aReturnData = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success Add Product Into Document DT Temp.'
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

    //ลบข้อมูลใน HD (DATATABLE) - ตัวเดียว
    public function FSoCTBIEventDelete(){
        $tTBIDocNo  = $this->input->post('tTBIDocNo');
        try {
            $aDataMaster = array(
                'tTBIDocNo'     => $tTBIDocNo
            );
            $aResDelDoc = $this->mTransferreceiptbranch->FSnMTBIDelDocument($aDataMaster);
            if ($aResDelDoc['rtCode'] == '1') {
                $aDataStaReturn  = array(
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Delete Document Success',
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'ลบใบรับโอน - สาขา ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $aDataStaReturn  = array(
                    'nStaEvent'     => $aResDelDoc['rtCode'],
                    'tStaMessg'     => $aResDelDoc['rtDesc'],
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'ลบใบรับโอน - สาขา ',
                    'nLogLevel'     => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aDataStaReturn = array(
                'nStaEvent'     => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tTBIDocNo,
                'tEventName'    => 'ลบใบรับโอน - สาขา ',
                'nLogLevel'     => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aDataStaReturn);
        echo json_encode($aDataStaReturn);
    }

    //ลบสินค้าใน Tmp (ตารางสินค้า) - ตัวเดียว
    public function FSvCTBIEventRemovePdtInDTTmp(){
        try {
            $this->db->trans_begin();

            $aDataWhere = array(
                'tBchCode'      => $this->input->post('tBchCode'), //$this->session->userdata('tSesUsrLevel') == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata('tSesUsrBchCode'),
                'tDocNo'        => $this->input->post('tDocNo'),
                'tPdtCode'      => $this->input->post('tPdtCode'),
                'nSeqNo'        => $this->input->post('nSeqNo'),
                'tVatInOrEx'    => $this->input->post('tVatInOrEx'),
                'tSessionID'    => $this->session->userdata('tSesSessionID')
            );
            $this->mTransferreceiptbranch->FSnMTBIDelPdtInDTTmp($aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo'        => $aDataWhere['tDocNo'],
                    'tDataDocKey'       => 'TCNTPdtTbiHD',
                    'tDataSeqNo'        => ''
                ];
                // FCNbHCallCalcDocDTTemp($aCalcDTParams);
                $aReturnData = array(
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Success Delete Product'
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

    //ลบสินค้าใน Tmp (ตารางสินค้า) - หลายตัว
    public function FSvCTBIEventRemovePdtInDTTmpMulti(){
        try {
            $this->db->trans_begin();
            $aDataWhere = array(
                'tBchCode'      => $this->input->post('ptTBIBchCode'),
                'tDocNo'        => $this->input->post('ptTBIDocNo'),
                'tVatInOrEx'    => $this->input->post('ptTBIVatInOrEx'),
                'aDataPdtCode'  => $this->input->post('paDataPdtCode'),
                'aDataPunCode'  => $this->input->post('paDataPunCode'),
                'aDataSeqNo'    => $this->input->post('paDataSeqNo')
            );
            $this->mTransferreceiptbranch->FSnMTBIDelMultiPdtInDTTmp($aDataWhere);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Cannot Delete Item.',
                );
            } else {
                $this->db->trans_commit();
                $aCalcDTParams = [
                    'tDataDocEvnCall'   => '',
                    'tDataVatInOrEx'    => $aDataWhere['tVatInOrEx'],
                    'tDataDocNo'        => $aDataWhere['tDocNo'],
                    'tDataDocKey'       => 'TCNTPdtTbiHD',
                    'tDataSeqNo'        => ''
                ];
                // FCNbHCallCalcDocDTTemp($aCalcDTParams);
                $aReturnData = array(
                    'nStaEvent'         => '1',
                    'tStaMessg'         => 'Success Delete Product'
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

    //Event เพิ่ม HD - DT
    public function FSoCTBIEventAdd(){
        try {
            $aDataDocument   = $this->input->post();
            $tTBIAutoGenCode = (isset($aDataDocument['ocbTBIStaAutoGenCode'])) ? 1 : 0;
            $tTBIDocDate     = $aDataDocument['oetTBIDocDate'] . " " . $aDataDocument['oetTBIDocTime'];
            $tTBIVATInOrEx   = 1;
            $tTBISessionID   = $this->session->userdata('tSesSessionID');
            $aCalDTTempParams = [
                'tDocNo'        => '',
                'tBchCode'      => $this->input->post('oetTBIBchCode'),
                'tSessionID'    => $tTBISessionID,
                'tDocKey'       => 'TCNTPdtTbiHD'
            ];
            $aCalDTTempForHD = $this->FSaCTBICalDTTempForHD($aCalDTTempParams);
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'      => 'TCNTPdtTbiHD',
                'tTableHDDis'   => '-',
                'tTableHDSpl'   => '-',
                'tTableDT'      => 'TCNTPdtTbiDT',
                'tTableDTDis'   => '-',
                'tTableStaGen'  => $aDataDocument['ohdTBIFrmDocType'],
                'tTableHDRef'   => 'TCNTPdtTbiHDRef'
            );
            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $this->input->post('oetTBIBchCode'),
                'FTXthDocNo'        => '',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FTXthVATInOrEx'    => $tTBIVATInOrEx,
                'FTXtdBchRef'       => $this->input->post('oetTBIBchCodeFrom')
            );
            if ($this->input->post('ohdTBIFrmDocType') == '1' && $this->input->post('ocmSelectTransTypeIN') == '3') {          // DocType = 1 and ผู้จำหน่าย
                $tRsnType   = $this->input->post('ocmSelectTransTypeIN');
                $tSplCode   = $this->input->post('oetTBISplCode');
                $tOther     = NULL;
            } else if ($this->input->post('ohdTBIFrmDocType') == '1' && $this->input->post('ocmSelectTransTypeIN') == '4') {     // DocType = 1 and แหล่งอื่น
                $tRsnType   = $this->input->post('ocmSelectTransTypeIN');
                $tSplCode   = NULL;
                $tOther     = $this->input->post('oetTBIINEtc');
            } else {                                                                                                            // DocType = 5 and คลัง
                $tRsnType   = '1';
                $tSplCode   = NULL;
                $tOther     = NULL;
            }
            // Array Data HD Master
            $aDataMaster = array(
                'FTBchCode'             => $this->input->post('oetTBIBchCode'),
                'FNXthDocType'          => $aDataDocument['ohdTBIFrmDocType'],
                'FDXthDocDate'          => (!empty($tTBIDocDate)) ? $tTBIDocDate : NULL,
                'FTXthVATInOrEx'        => $tTBIVATInOrEx,
                'FTDptCode'             => $this->session->userdata('tSesUsrDptCode') == '' ? null : $this->session->userdata('tSesUsrDptCode'),
                'FTXthBchFrm'           => $this->input->post('oetTBIBchCodeFrom'),
                'FTXthBchTo'            => $this->input->post('oetTBIBchCodeTo'),
                'FTXthWhTo'             => $this->input->post('oetTBIWahCodeTo'),
                'FTXthRsnType'          => $tRsnType,
                'FTSplCode'             => $tSplCode,
                'FTXthOther'            => $tOther,
                'FTUsrCode'             => $this->session->userdata('tSesUserCode'),
                'FTSpnCode'             => NULL,
                'FTXthApvCode'          => $this->session->userdata('tSesUsername'),
                'FTXthRefExt'           => $aDataDocument['oetTBIRefExtDoc'],
                'FDXthRefExtDate'       => $aDataDocument['oetTBIRefExtDocDate'] == '' ? NULL : $aDataDocument['oetTBIRefExtDocDate'],
                'FTXthRefInt'           => $aDataDocument['oetTBIRefIntDoc'],
                'FDXthRefIntDate'       => date('Y-m-d H:i:s'), 
                'FNXthDocPrint'         => 0,
                'FCXthTotal'            => $aCalDTTempForHD['FCXphTotal'],
                'FCXthVat'              => $aCalDTTempForHD['FCXphVat'],
                'FCXthVatable'          => $aCalDTTempForHD['FCXphVatable'],
                'FTXthRmk'              => $aDataDocument['otaTBIFrmInfoOthRmk'],
                'FTXthStaDoc'           => 1, //สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                'FTXthStaApv'           => !empty($aDataDocument['ohdTBIStaApv']) ? $aDataDocument['ohdTBIStaApv'] : NULL,
                'FTXthStaPrcStk'        => !empty($aDataDocument['ohdTBIStaPrcStk']) ? $$aDataDocument['ohdTBIStaPrcStk'] : NULL,
                'FTXthStaDelMQ'         => !empty($aDataDocument['ohdTBIStaDelMQ']) ? $aDataDocument['ohdTBIStaDelMQ'] : NULL,
                'FNXthStaDocAct'        => $aDataDocument['ocbTBIStaDocAct'] == '' ? 0 : 1,
                'FNXthStaRef'           => NULL,
                'FTRsnCode'             => $aDataDocument['oetTBIReasonCode'] == '' ? null : $aDataDocument['oetTBIReasonCode'],
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername')
            );

            $aDataHDRef = array(
                'FTXthCtrName'          => $this->input->post('oetTBITransportCtrName'),
                'FDXthTnfDate'          => $this->input->post('oetTBITransportTnfDate'),
                'FTXthRefTnfID'         => $this->input->post('oetTBITransportRefTnfID'),
                'FTXthRefVehID'         => $this->input->post('oetTBITransportRefVehID'),
                'FTXthQtyAndTypeUnit'   => $this->input->post('oetTBITransportQtyAndTypeUnit'),
                'FNXthShipAdd'          => $this->input->post('ohdTBIFrmShipAdd')
            );
            
            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tTBIAutoGenCode == '1') {
                // Update new gencode
                $aStoreParam = array(
                    "tTblName"    => $aTableAddUpdate['tTableHD'],
                    "tDocType"    => $aTableAddUpdate['tTableStaGen'],
                    "tBchCode"    => $aDataMaster['FTBchCode'],
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXthDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXthDocNo'] = $this->input->post('oetTBIDocNo');
            }

            // Add Doc HD
            $this->mTransferreceiptbranch->FSxMTBIAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

            // Add Doc Ref
            $this->mTransferreceiptbranch->FSaMTBIAddUpdateHDRef($aDataHDRef, $aDataWhere, $aTableAddUpdate);

            // Update Doc No Into Doc Temp
            $this->mTransferreceiptbranch->FSxMTBIAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

            // Move Doc DTTemp To DT
            $this->mTransferreceiptbranch->FSaMTBIMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document.",
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $aDataWhere['FTXthDocNo'],
                    'tEventName'    => 'บันทึกใบรับโอน - สาขา',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTXthDocNo'],
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Add Document.',
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $aDataWhere['FTXthDocNo'],
                    'tEventName'    => 'บันทึกใบรับโอน - สาขา',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $aDataWhere['FTXthDocNo'],
                'tEventName'    => 'บันทึกใบรับโอน - สาขา',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }

        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);

        echo json_encode($aReturnData);
    }

    //Event แก้ไข HD - DT
    public function FSoCTBIEventEdit(){
        try {
            $aDataDocument          = $this->input->post();
            $tTBIDocNo              = (isset($aDataDocument['oetTBIDocNo'])) ? $aDataDocument['oetTBIDocNo'] : '';
            $tTBIDocDate            = $aDataDocument['oetTBIDocDate'] . " " . $aDataDocument['oetTBIDocTime'];
            $tTBIStaDocAct          = (isset($aDataDocument['ocbTBIStaDocAct'])) ? 1 : 0;
            $tTBISessionID          = $this->session->userdata('tSesSessionID');

            $aCalDTTempParams = [
                'tDocNo'        => '',
                'tBchCode'      => $aDataDocument['ohdTBIBchCode'],
                'tSessionID'    => $tTBISessionID,
                'tDocKey'       => 'TCNTPdtTBIHD'
            ];
            $aCalDTTempForHD    = $this->FSaCTBICalDTTempForHD($aCalDTTempParams);

            if ($aDataDocument['ohdTBIStaApv'] == 1 || $aDataDocument['ohdTBIStaDoc'] == 3) { //ถ้าอนุมัติแล้ว อัพเดทแค่หมายเหตุได้อย่างเดียว
                $aDataMaster = array(
                    'FTBchCode'             => $aDataDocument['oetTBIBchCode'],
                    'FTXthDocNo'            => $tTBIDocNo,
                    'FTXthRmk'              => $aDataDocument['otaTBIFrmInfoOthRmk'],
                );
                $this->db->trans_begin();
                // [Update] update หมายเหตุ
                $this->mTransferreceiptbranch->FSaMTBIUpdateRmk($aDataMaster);
            } else { //ถ้ายังไม่อนุมัติ ก็อัพเดทข้อมูลปกติ

                // Array Data Table Document
                $aTableAddUpdate = array(
                    'tTableHD'      => 'TCNTPdtTBIHD',
                    'tTableHDDis'   => '-',
                    'tTableHDSpl'   => '-',
                    'tTableDT'      => 'TCNTPdtTBIDT',
                    'tTableDTDis'   => '-',
                    'tTableStaGen'  => 5,
                    'tTableHDRef'   => 'TCNTPdtTbiHDRef'
                );

                // Array Data Where Insert
                $aDataWhere = array(
                    'FTBchCode'         => $aDataDocument['ohdTBIBchCode'],
                    'FTXthDocNo'        => $tTBIDocNo,
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                    'FTXtdBchRef'       => $this->input->post('oetTBIBchCodeFrom')
                );

                if ($this->input->post('ocmSelectTransTypeIN') == '3') {          // DocType = 1 and ผู้จำหน่าย
                    $tRsnType   = $this->input->post('ocmSelectTransTypeIN');
                    $tSplCode   = $this->input->post('oetTBISplCode');
                    $tOther     = '';
                } else if ($this->input->post('ocmSelectTransTypeIN') == '4') {     // DocType = 1 and แหล่งอื่น
                    $tRsnType   = $this->input->post('ocmSelectTransTypeIN');
                    $tSplCode   = NULL;
                    $tOther     = $this->input->post('oetTBIINEtc');
                } else {                                                          // DocType = 5 and คลัง
                    $tRsnType   = '1';
                    $tSplCode   = NULL;
                    $tOther     = NULL;
                }

                // Array Data HD Master
                $aDataMaster = array(
                    'FTBchCode'             => $aDataDocument['ohdTBIBchCode'],
                    'FNXthDocType'          => $this->input->post('ohdTBIFrmDocType'),
                    'FDXthDocDate'          => (!empty($tTBIDocDate)) ? $tTBIDocDate : NULL,
                    'FTXthVATInOrEx'        => '1',
                    'FTDptCode'             => $this->session->userdata('tSesUsrDptCode') == '' ? null : $this->session->userdata('tSesUsrDptCode'),
                    'FTXthBchFrm'           => $this->input->post('oetTBIBchCodeFrom'),
                    'FTXthBchTo'            => $this->input->post('oetTBIBchCodeTo'),
                    'FTXthWhTo'             => $this->input->post('oetTBIWahCodeTo'),
                    'FTXthRsnType'          => $tRsnType,
                    'FTSplCode'             => $tSplCode,
                    'FTXthOther'            => $tOther,
                    'FTUsrCode'             => $this->session->userdata('tSesUserCode'),
                    'FTSpnCode'             => null,
                    'FTXthApvCode'          => $this->session->userdata('tSesUsername'),
                    'FTXthRefExt'           => $aDataDocument['oetTBIRefExtDoc'],
                    'FDXthRefExtDate'       => $aDataDocument['oetTBIRefExtDocDate'] == '' ? NULL : $aDataDocument['oetTBIRefExtDocDate'],
                    'FTXthRefInt'           => $aDataDocument['oetTBIRefIntDoc'],
                    'FDXthRefIntDate'       => date('Y-m-d H:i:s'), 
                    'FNXthDocPrint'         => 0,
                    'FCXthTotal'            => $aCalDTTempForHD['FCXphTotal'],
                    'FCXthVat'              => $aCalDTTempForHD['FCXphVat'],
                    'FCXthVatable'          => $aCalDTTempForHD['FCXphVatable'],
                    'FTXthRmk'              => $aDataDocument['otaTBIFrmInfoOthRmk'],
                    'FTXthStaDoc'           => 1, //สถานะ เอกสาร  1:สมบูรณ์, 2:ไม่สมบูรณ์, 3:ยกเลิก
                    'FTXthStaApv'           => !empty($aDataDocument['ohdTBIStaApv']) ? $aDataDocument['ohdTBIStaApv'] : NULL,
                    'FTXthStaPrcStk'        => !empty($aDataDocument['ohdTBIStaPrcStk']) ? $aDataDocument['ohdTBIStaPrcStk'] : NULL,
                    'FTXthStaDelMQ'         => !empty($aDataDocument['ohdTBIStaDelMQ']) ? $aDataDocument['ohdTBIStaDelMQ'] : NULL,
                    'FNXthStaDocAct'        =>  $tTBIStaDocAct,
                    'FNXthStaRef'           => null,
                    'FTRsnCode'             => $aDataDocument['oetTBIReasonCode'] == '' ? null : $aDataDocument['oetTBIReasonCode'],
                    'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                );

                $aDataHDRef = array(
                    'FTXthCtrName'          => $this->input->post('oetTBITransportCtrName'),
                    'FDXthTnfDate'          => $this->input->post('oetTBITransportTnfDate'),
                    'FTXthRefTnfID'         => $this->input->post('oetTBITransportRefTnfID'),
                    'FTXthRefVehID'         => $this->input->post('oetTBITransportRefVehID'),
                    'FTXthQtyAndTypeUnit'   => $this->input->post('oetTBITransportQtyAndTypeUnit'),
                    'FNXthShipAdd'          => $this->input->post('ohdTBIFrmShipAdd')
                );

                $this->db->trans_begin();

                // Add Update Document HD
                $this->mTransferreceiptbranch->FSxMTBIAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);

                // Add Update Document HD
                $this->mTransferreceiptbranch->FSaMTBIAddUpdateHDRef($aDataHDRef, $aDataWhere, $aTableAddUpdate);

                // Update Doc No Into Doc Temp
                $this->mTransferreceiptbranch->FSxMTBIAddUpdateDocNoToTemp($aDataWhere, $aTableAddUpdate);

                // Move Doc DTTemp To DT
                $this->mTransferreceiptbranch->FSaMTBIMoveDtTmpToDt($aDataWhere, $aTableAddUpdate);
                
            }

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document.",
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'แก้ไขและบันทึกใบรับโอน - สาขา',
                    'nLogLevel'     => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $tTBIDocNo,
                    'nStaReturn'    => '1',
                    'tStaMessg'     => 'Success Edit Document.',
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'แก้ไขและบันทึกใบรับโอน - สาขา',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => ''
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaReturn'    => '500',
                'tStaMessg'     => $Error->getMessage(),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tTBIDocNo,
                'tEventName'    => 'แก้ไขและบันทึกใบรับโอน - สาขา',
                'nLogLevel'     => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }

        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData); 

        echo json_encode($aReturnData);
    }

    //คำนวณค่าจาก DT Temp ให้ HD
    private function FSaCTBICalDTTempForHD($paParams){
        $aCalDTTemp = $this->mTransferreceiptbranch->FSaMTBICalInDTTemp($paParams);
        if (isset($aCalDTTemp) && !empty($aCalDTTemp)) {
            $aCalDTTempItems = $aCalDTTemp[0];
            // คำนวณหา ยอดปัดเศษ ให้ HD(FCXphRnd)
            $pCalRoundParams = [
                'FCXphAmtV'     => $aCalDTTempItems['FCXphAmtV'],
                'FCXphAmtNV'    => $aCalDTTempItems['FCXphAmtNV']
            ];
            $aRound = $this->FSaCTBICalRound($pCalRoundParams);
            // คำนวณหา ยอดรวม ให้ HD(FCXphGrand)
            $nRound = $aRound['nRound'];
            $cGrand = $aRound['cAfRound'];

            // จัดรูปแบบข้อความ จากตัวเลขเป็นข้อความ HD(FTXphGndText)
            $tGndText = FCNtNumberToTextBaht(number_format($cGrand, 2));
            $aCalDTTempItems['FCXphRnd']        = $nRound;
            $aCalDTTempItems['FCXphGrand']      = $cGrand;
            $aCalDTTempItems['FTXphGndText']    = $tGndText;
            return $aCalDTTempItems;
        }
    }

    //หาค่าปัดเศษ HD(FCXphRnd)
    private function FSaCTBICalRound($paParams){
        $tOptionRound = '1';  // ปัดขึ้น
        $cAmtV  = $paParams['FCXphAmtV'];
        $cAmtNV = $paParams['FCXphAmtNV'];
        $cBath  = $cAmtV + $cAmtNV;
        // ตัดเอาเฉพาะทศนิยม
        $nStang = explode('.', number_format($cBath, 2))[1];
        $nPoint = 0;
        $nRound = 0;
        /* ====================== ปัดขึ้น ================================ */
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
        /* ====================== ปัดขึ้น ================================ */

        /* ====================== ปัดลง ================================ */
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
        /* ====================== ปัดลง ================================ */
        $cAfRound = floatval($cBath) + floatval($nRound / 100);
        return [
            'tRoundType' => $tOptionRound,
            'cBath' => $cBath,
            'nPoint' => $nPoint,
            'nStang' => $nStang,
            'nRound' => $nRound,
            'cAfRound' => $cAfRound
        ];
    }

    //ยกเลิกเอกสาร
    public function FSoCTBIEventCancel(){
        $tTBIDocNo = $this->input->post('tTBIDocNo');

        $aDataUpdate = array(
            'FTXthDocNo' => $tTBIDocNo,
        );
        $tDocumentNumber    = $this->input->post('tTBIDocNo');
        $tBchCode           = $this->input->post('tBIBchCode');
        $aWhere = array(
            'tNewDocument'    => $tDocumentNumber . 'C',
            'tDocumentNumber' => $tDocumentNumber,
            'tBchCode'        => $tBchCode
        );
        //ยกเลิกเอกสาร 
        $aStaApv    =  $this->mTransferreceiptbranch->FSvMTBICancel($aDataUpdate);

        //ถ้ายกเลิกเอกสาร ต้องวิ่งไปเช็คว่าสินค้าใน DT มี Status จองยัง ถ้ามีแล้วต้องวิ่ง MQ / ถ้าไม่มีสถานะยังไม่จองไม่ต้องทำไร (รอทำ)
        $nItems = $this->mTransferreceiptbranch->FSaMTBICheckStatusDocProcess($aWhere);
        if ($nItems != 0) {
            
            $aMQParams = [
                "queueName" => "CN_QDocApprove",
                "params"    => [
                    'ptFunction'    => "TCNTPdtTbiHD",
                    'ptSource'      => 'AdaStoreBack',
                    'ptDest'        => 'MQReceivePrc',
                    'ptFilter'      => $tBchCode,
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tBchCode,
                        "ptDocNo"       => $tDocumentNumber,
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            FCNxCallRabbitMQ($aMQParams);

        } else {
      
            if ($aStaApv['rtCode'] == 1) {
                $aApv = array(
                    'nSta'          => 1,
                    'tStaMessg'     => "Cancel Document Success",
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'ยกเลิกใบรับโอน - สาขา',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '0',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $aApv = array(
                    'nSta'          => 2,
                    'tStaMessg'     => "Cancel Document Unsuccess",
                    'tLogType'      => 'ERROR',
                    'tDocNo'        => $tTBIDocNo,
                    'tEventName'    => 'ยกเลิกใบรับโอน - สาขา',
                    'nLogCode'      => '500',
                    'nLogLevel'     => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }

            FSoCCallLogMQ($aApv);

            echo json_encode($aApv);
        }
    }

    //อัพเดทข้อมูล เป็นเเถว
    public function FSoCTBIEventEditPdtIntoDocDTTemp(){
        try {
            $tTBIBchCode    = $this->input->post('tTBIBchCode');
            $tTBIDocNo      = $this->input->post('tTBIDocNo');
            $tTBIVATInOrEx  = $this->input->post('tTBIVATInOrEx');
            $nTBISeqNo      = $this->input->post('nTBISeqNo');
            $tTBIFieldName  = $this->input->post('tTBIFieldName');
            $tTBIValue      = $this->input->post('tTBIValue');
            $tTBISessionID  = $this->session->userdata('tSesSessionID');

            $aDataWhere = array(
                'tTBIBchCode'   => $tTBIBchCode,
                'tTBIDocNo'     => $tTBIDocNo,
                'nTBISeqNo'     => $nTBISeqNo,
                'tTBISessionID' => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'TCNTPdtTbiHD',
            );
            $aDataUpdateDT = array(
                'tTBIFieldName'  => $tTBIFieldName,
                'tTBIValue'      => $tTBIValue
            );

            // echo "<pre>";
            // print_r($aDataWhere);
            // print_r($aDataUpdateDT);
            // exit;

            $this->db->trans_begin();
            $this->mTransferreceiptbranch->FSaMTBIUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '500',
                    'tStaMessg'     => "Error Update Inline Into Document DT Temp."
                );
            } else {
                $this->db->trans_commit();

                $aCalcDTTempParams = array(
                    'tDataDocEvnCall'   => '1',
                    'tDataVatInOrEx'    => '1',
                    'tDataDocNo'        => $tTBIDocNo,
                    'tDataDocKey'       => 'TCNTPdtTbiHD',
                    'tDataSeqNo'        => $nTBISeqNo
                );
                // $tStaCalDocDTTemp = FCNbHCallCalcDocDTTemp($aCalcDTTempParams);
                // if ($tStaCalDocDTTemp === TRUE) {
                //     $aReturnData = array(
                //         'nStaEvent'     => '1',
                //         'tStaMessg'     => "Update And Calcurate Process Document DT Temp Success."
                //     );
                // } else {
                //     $aReturnData = array(
                //         'nStaEvent'     => '500',
                //         'tStaMessg'     => "Error Cannot Calcurate Document DT Temp."
                //     );
                // }
                $aReturnData = array(
                    'nStaEvent'     => '1',
                    'tStaMessg'     => "Update And Calcurate Process Document DT Temp Success."
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

    //เลือกสินค้าจากตาราง CN - ใบสั้งขาย
    public function FSoCTBIPageSelectPDTInCN(){
        $tBCHCode = $this->input->post('tBCHCode');
        $tSHPCode = $this->input->post('tSHPCode');
        $tWAHCode = $this->input->post('tWAHCode');
        $aWhere = array(
            'tBCHCode' => $tBCHCode,
            'tSHPCode' => $tSHPCode,
            'tWAHCode' => $tWAHCode,
            'FNLngID'  => $this->session->userdata("tLangEdit")
        );
        $aDataCN = $this->mTransferreceiptbranch->FSaMTBIGetPDTInCN($aWhere);
        $aDataViewCN = array(
            'aDataCN'       => $aDataCN
        );
        $tViewCN            = $this->load->view('document/transferreceiptbranch/wTransferreceiptbranchCN', $aDataViewCN, true);
        $aReturnData        = array(
            'tViewPageAdd'  => $tViewCN,
            'nStaEvent'     => '1',
            'tStaMessg'     => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //อนุมัติ
    public function FSoCTBIEventApproved(){
        $tXthDocNo      = $this->input->post('tXthDocNo');
        $tXthDocType    = $this->input->post('tXthDocType');
        $tXthBchCode    = $this->input->post('tXthBchCode');
        try {
            $aMQParams = [
                "queueName" => "TNFBRANCHIN",
                "params"    => [
                    "ptBchCode"     => $tXthBchCode,
                    "ptDocNo"       => $tXthDocNo,
                    "ptDocType"     => $tXthDocType,
                    "ptUser"        => $this->session->userdata('tSesUsername')
                ]
            ];
            $aStaReturn = FCNxCallRabbitMQ($aMQParams);
            if ($aStaReturn['rtCode'] == 1) {      
                $aDataUpdate = array(
                    'FTXthDocNo'    => $tXthDocNo,
                    'FTXthApvCode'  => $this->session->userdata('tSesUsername')
                );
                $this->mTransferreceiptbranch->FSvMTBIApprove($aDataUpdate);
                $aDataGetDataHD     =  $this->mTransferreceiptbranch->FSaMTBIGetDataDocHD(array(
                    'FTXthDocNo'    => $tXthDocNo,
                    'FNLngID'       => $this->session->userdata("tLangEdit")
                ));
                if($aDataGetDataHD['rtCode']=='1'){
                    $tNotiID        = FCNtHNotiGetNotiIDByDocRef($aDataGetDataHD['raItems']['FTXthDocNo']);
                    $aMQParamsNoti  = [
                        "queueName"     => "CN_SendToNoti",
                        "tVhostType"    => "NOT",
                        "params"        => [
                            "oaTCNTNoti" => array(
                                            "FNNotID"       => $tNotiID,
                                            "FTNotCode"     => '00009',
                                            "FTNotKey"      => 'TCNTPdtTbiHD',
                                            "FTNotBchRef"    => $aDataGetDataHD['raItems']['FTBchCode'],
                                            "FTNotDocRef"   => $aDataGetDataHD['raItems']['FTXthDocNo'],
                            ),
                            "oaTCNTNoti_L" => array(
                                0 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 1,
                                    "FTNotDesc1"    => 'เอกสารใบรับโอน #'.$aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FTNotDesc2"    => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' อนุมัติใบรับโอน เลขที่อ้างอิง #'.$aDataGetDataHD['raItems']['FTXthRefInt'],
                                ),
                                1 => array(
                                    "FNNotID"       => $tNotiID,
                                    "FNLngID"       => 2,
                                    "FTNotDesc1"    => 'Vendor purchase requisitions #'.$aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FTNotDesc2"    => 'Branch code '.$aDataGetDataHD['raItems']['FTBchCode'].' Approve document Ref #'.$aDataGetDataHD['raItems']['FTXthRefInt'],
                                )
                            ),
                            "oaTCNTNotiAct" => array(
                                0 => array(  
                                    "FNNotID"       => $tNotiID,
                                    "FDNoaDateInsert" => date('Y-m-d H:i:s'),
                                    "FTNoaDesc"      => 'รหัสสาขา '.$aDataGetDataHD['raItems']['FTBchCode'].' อนุมัติใบรับโอน เลขที่อ้างอิง #'.$aDataGetDataHD['raItems']['FTXthRefInt'],
                                    "FTNoaDocRef"    => $aDataGetDataHD['raItems']['FTXthDocNo'],
                                    "FNNoaUrlType"   =>  1,
                                    "FTNoaUrlRef"    => 'docTBI/2/0/5',
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
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCodeTo'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTBchNameTo'],
                                ),
                                2 => array(
                                        "FNNotID"       => $tNotiID,
                                        "FTNotType"    => '2',
                                        "FTNotStaType" => '1',
                                        "FTAgnCode"    => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"    => $aDataGetDataHD['raItems']['FTBchCodeFrom'],
                                        "FTBchName"    => $aDataGetDataHD['raItems']['FTBchNameFrom'],
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
                    'tEventName'    => 'อนุมัติใบรับโอน - สาขา',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $this->session->userdata('tSesUsername')
                );
            }else{
                $aReturn = array(
                    'nStaEvent'     => '905',
                    'tStaMessg'     => 'Connect Rabbit MQ Fail'.' '.$aStaReturn['rtDesc'],
                    'tLogType'      => 'EVENT',
                    'tDocNo'        => $tXthDocNo,
                    'tEventName'    => 'อนุมัติใบรับโอน - สาขา',
                    'nLogCode'      => '905',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $this->session->userdata('tSesUsername')
                );
            }
        } catch (Exception $Error) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'     => '900',
                'tStaMessg'     => language('common/main/main', 'tApproveFail'),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tXthDocNo,
                'tEventName'    => 'อนุมัติใบรับโอน - สาขา',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $this->session->userdata('tSesUsername')
            );
        }
        if ($aReturn['nStaEvent'] != 905) {
            //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
            FSoCCallLogMQ($aReturn); 
        }

        unset($tXthDocNo);
        unset($tXthDocType);
        unset($tXthBchCode);
        unset($aMQParams);
        unset($aDataUpdate);
        unset($aDataGetDataHD);
        unset($aMQParamsNoti);
        echo json_encode($aReturn);
    }

    public function FSoCTBIEventGetPdtIntDTBch(){
        try {
            $tTBODocNo      =  $this->input->post('tTBODocNo');
            $tTBIDocNo      =  $this->input->post('tTBIDocNo');
            $tTBIBchCodeTo  =  $this->input->post('tTBIBchCode');
            $tTblSelectData = [
                'FTXthDocKey'   => 'TCNTPdtTBIHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];
            $this->mTransferreceiptbranch->FSxMTBIClearPdtInTmp($tTblSelectData);

            $aDataTBIGetPdtIntBch = array(
                'tTBODocNo'         => $tTBODocNo,
                'tTBIDocNo'         => $tTBIDocNo,
                'tTBIBchCodeTo'     => $tTBIBchCodeTo,
                'tTBISesUsername'   => $this->session->userdata('tSesUsername'),
                'tTBISessionID'     => $this->session->userdata('tSesSessionID'),
            );
            $aPdtDataResult = $this->mTransferreceiptbranch->FSoMTBIEventGetPdtIntDTBch($aDataTBIGetPdtIntBch);

            $aDataWhere = array(
                'FTBchCode'     => $tTBIBchCodeTo,
                'FTXthDocNo'    => $tTBIDocNo,
                'FTXthDocKey'   => 'TCNTPdtTbiHD',
            );

            if (!empty($aPdtDataResult)) {
                foreach ($aPdtDataResult as $aData) {
                    $cTBIPrice      = $this->mTransferreceiptbranch->FSaMTBIGetPriceBYPDT($aData['FTPdtCode']);
                    if ($cTBIPrice[0]->PDTCostSTD == null) {
                        $nPrice     = 0;
                    } else {
                        $nPrice     = $cTBIPrice[0]->PDTCostSTD;
                    }

                    $aDataPdtParams = array(
                        'tPdtCode'          => $aData['FTPdtCode'],
                        'tBarCode'          => $aData['FTXtdBarCode'],
                        'tPunCode'          => $aData['FTPunCode'],
                        'FCXtdQty'          => $aData['FCXtdQty'],
                        'FCXtdQtyAll'       => $aData['FCXtdQtyAll'],
                        'nLngID'            => $this->session->userdata("tLangEdit")
                    );
                    $nTBIMaxSeqNo = $this->mTransferreceiptbranch->FSaMTBIGetMaxSeqDocDTTemp($aDataWhere);

                    $aDataPdtParams = array(
                        'tDocNo'            => $tTBIDocNo,
                        'tBchCode'          => $tTBIBchCodeTo,
                        'tPdtCode'          => $aData['FTPdtCode'],
                        'tBarCode'          => $aData['FTXtdBarCode'],
                        'tPunCode'          => $aData['FTPunCode'],
                        'FCXtdQty'          => $aData['FCXtdQty'],
                        'FCXtdQtyAll'       => $aData['FCXtdQtyAll'],
                        'cPrice'            => $nPrice,
                        'nMaxSeqNo'         => $nTBIMaxSeqNo + 1,
                        'nLngID'            => $this->session->userdata("tLangID"),
                        'tSessionID'        => $this->session->userdata('tSesSessionID'),
                        'tDocKey'           => 'TCNTPdtTbiHD',
                        'tDocRefSO'         => $tTBODocNo
                    );

                    // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                    $aDataPdtMaster  = $this->mTransferreceiptbranch->FSaMTBIGetDataPdt($aDataPdtParams);
                    $this->mTransferreceiptbranch->FSoMTBIEventInsertPdtIntDTBchToTemp($aDataPdtMaster, $aDataPdtParams);
                }
            }

            $aReturn = array(
                'nStaEvent'    => '1',
                'tStaMessg'    => 'ok'
            );

            unset($tTBODocNo);
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => 'Fail'
            );
            echo json_encode($aReturn);
            return;
        }
    }
}
