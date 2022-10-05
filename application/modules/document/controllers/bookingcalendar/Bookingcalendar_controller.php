<?php

defined('BASEPATH') or exit('No direct script access allowed');
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
class Bookingcalendar_controller extends MX_Controller{

    public function __construct(){
        $this->load->model('document/bookingcalendar/Bookingcalendar_model');
        parent::__construct();
    }

    public function index($nBKBrowseType, $tBKBrowseOption){
        $aData['nBrowseType']       = $nBKBrowseType;
        $aData['tBrowseOption']     = $tBKBrowseOption;
        $aData['aPermission']       = FCNaHCheckAlwFunc('docBookingCalendar/0/0');
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('docBookingCalendar/0/0');
        $this->load->view('document/bookingcalendar/wBookingcalendar', $aData);
    }

    //List
    public function FSvCBKFormList(){
        $this->Bookingcalendar_model->FSaMBKDeleteDTInTemp();
        $this->load->view('document/bookingcalendar/wBookingcalendarList');
    }

    //FormTable
    public function FSvCBKFormTable(){
        $tADCode   = $this->input->post('tADCode');
        $tBCHCode  = $this->input->post('tBCHCode');

        $aItem              = array(
            'FTAgnCode'     => $tADCode,
            'FTBchCode'     => $tBCHCode
        );

        $aBayService        = $this->Bookingcalendar_model->FSaMBKGetBayService($aItem);
        $aCalendarService   = $this->Bookingcalendar_model->FSaMBKGetCalendarService($aItem);
        $aReturnToView = array(
            'aBayService'       => $aBayService,
            'aCalendarService'  => $aCalendarService
        );

        $this->load->view('document/bookingcalendar/wCalendar', $aReturnToView);
    }

    //Page Add + Edit
    public function FSvCBKAddPage(){
        $nStartTime = $this->input->post('nStartTime');
        $nEndTime   = $this->input->post('nEndTime');
        $aColumn    = $this->input->post('aColumn');
        $tDocCode   = $this->input->post('tDocCode');

        if ($tDocCode == '' || $tDocCode == null) { //Insert
            $aItemBookingByID = '';

            //Delete InTemp 
            $this->Bookingcalendar_model->FSaMBKDeleteDTInTemp();
        } else { //Update
            $aItemBookingByID = $this->Bookingcalendar_model->FSaMBKGetBookingbyID($tDocCode);

            //Delete InTemp 
            $this->Bookingcalendar_model->FSaMBKDeleteDTInTemp();

            //Move DT To Temp
            $this->Bookingcalendar_model->FSaMBKMoveDTToTemp($tDocCode);
        }

        //ข้อมูลจุดให้บริการ
        if ($aColumn['id'] == '' || $aColumn['id'] == null) {
            //มาจากหน้า list history
            $aBayService = $this->Bookingcalendar_model->FSaMBKGetBayServiceByID('', $aColumn['adcode'], $aColumn['bchcode']);
            $aBranch     = $this->Bookingcalendar_model->FSaMBKGetBranchName($aColumn['bchcode']);
        } else {
            //มาจากหน้า booking ปกติ
            $aBayService = $this->Bookingcalendar_model->FSaMBKGetBayServiceByID($aColumn['id'], '', '');
            $aBranch     = '';
        }

        //ข้อมูลคลังในสาขา (ต้นทาง)
        $aWahouseFrm    = $this->Bookingcalendar_model->FSaMBKGetWahouseInBranchFrm($aColumn);

        //ข้อมูลคลังจอง (ปลายทาง)
        $aWahouseTo     = $this->Bookingcalendar_model->FSaMBKGetWahouseInBranchTo($aColumn);

        $aReturnToView = array(
            'nStartTime'        =>  $nStartTime,
            'nEndTime'          =>  $nEndTime,
            'aColumn'           =>  $aColumn,
            'aBranch'           =>  $aBranch,
            'aItemBookingByID'  =>  $aItemBookingByID,
            'aBayService'       =>  $aBayService,
            'aWahouseFrm'       =>  $aWahouseFrm,
            'aWahouseTo'        =>  $aWahouseTo
        );
        $this->load->view('document/bookingcalendar/wBookingcalendarAdd', $aReturnToView);
    }

    //โหลดสินค้าใน Temp
    public function FSvCBKLoadTableItemDT(){
        $tDocumentNumber  = $this->input->post('tDocumentNumber');
        $tDocumentNumber  = ($tDocumentNumber == '') ? 'DUMMY' : $tDocumentNumber;
        $tAGNCode         = $this->input->post('tAgnCode');
        $tBCHCode         = $this->input->post('tBchCode');
        $aItemGetData     = $this->Bookingcalendar_model->FSaMBKGetDTInTemp($tBCHCode, $tDocumentNumber);

        $aReturnToView = array(
            "tStaDoc"     => $this->input->post('tStaDoc'),
            "tStaPrcDoc"  => $this->input->post('tStaPrcDoc'),
            "aDataList"   => $aItemGetData
        );
        $this->load->view('document/bookingcalendar/wBookingcalendarTableTmp', $aReturnToView);
    }

    //เพิ่มข้อมูลลงใน Temp (สินค้าปกติ)
    public function FSxCBKEventInsertToDT(){
        $poItem                 = json_decode($this->input->post('poItem'));
        $tDocumentNumber        = $this->input->post('tDocumentNumber');
        $tAGNCode               = $this->input->post('tAgnCode');
        $tBCHCode               = $this->input->post('tBchCode');
        $tDocumentNumber        = ($tDocumentNumber == '') ? 'DUMMY' : $tDocumentNumber;
        $nCheckPDTSet           = 0; //เก็บไว้ว่ามีค่าสินค้าเซตไหม จะมีผลต่อหน้าจอ render
        $tWahFrm                = $this->input->post('tWahFrm');
        $tWahTo                 = $this->input->post('tWahTo');
        if (isset($poItem)) {
            for ($i = 0; $i < count($poItem); $i++) {

                $tPDTCode   = $poItem[$i]->packData->PDTCode;
                $tPunCode   = $poItem[$i]->packData->PUNCode;
                $tBarCode   = $poItem[$i]->packData->Barcode;
                $aDetailItem = $this->Bookingcalendar_model->FSaMBKGetDataPdt($tPDTCode, $tPunCode);
                $nSeqNo     = $i + 1;

                //ถ้าเป็นสินค้าปกติ
                $aInsertToTemp = array(
                    'FTBchCode'     => $tBCHCode,
                    'FTXshDocNo'    => $tDocumentNumber,
                    'FTXthDocKey'   => 'TSVTBookDT',
                    'FTPdtCode'     => $poItem[$i]->packData->PDTCode,
                    'FTBarCode'     => $tBarCode,
                    'FTPunName'     => $poItem[$i]->packData->PUNName,
                    'FTPunCode'     => $tPunCode,
                    'FTXcdPdtSeq'   => $nSeqNo,
                    'FCXtdFactor'   => $poItem[$i]->packData->PDTName,
                    'FTXcdPdtName'  => $poItem[$i]->packData->PDTName,
                    'FTPdtSetOrSN'  => $poItem[$i]->packData->SetOrSN, //1:สินค้าปกติ 2:สินค้าปกติชุด 3: สินค้าSerial 4:สินค้าSerial Set ,5: สินค้าชุดบริการ
                    'FTXtdStaPrcStk' => null, //null: รอยืนยัน ,  1: ยืนยันแล้ว
                    'FCXtdQty'      => 1,
                    'FTXtdVatType'  => $aDetailItem[0]['FTPdtStaVat'],
                    'FCXtdSetPrice' => ($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                    'FCXtdNet'      => ($aDetailItem[0]['FCPgdPriceRet'] == '') ? 0 : $aDetailItem[0]['FCPgdPriceRet'],
                    'FTVatCode'     => $aDetailItem[0]['FTVatCode'],
                    'FCXtdVatRate'  => $aDetailItem[0]['FCVatRate'],
                    'FTXtdStaAlwDis' => $aDetailItem[0]['FTPdtStaAlwDis'],
                    'FCXtdFactor'   => $aDetailItem[0]['FCPdtUnitFact'],
                    'tWahFrm'       => $tWahFrm,
                    'tWahTo'        => $tWahTo
                );
                $this->Bookingcalendar_model->FSaMBKInsertToTemp($aInsertToTemp);

                if ($poItem[$i]->packData->SetOrSN == 2 || $poItem[$i]->packData->SetOrSN == 5) {
                    $nCheckPDTSet = 1;
                }
            }
        }

        $aReturn = array(
            'nStatusRender' => $nCheckPDTSet
        );

        echo json_encode($aReturn);
    }

    //เพิ่มข้อมูลลงใน Temp (สินค้าเซต + สินค้าบำรุงรักษา)
    public function FSxCBKEventInsertToDTPDTSet(){
        $poItem                 = json_decode($this->input->post('poItem'));
        $tDocumentNumber        = $this->input->post('tDocumentNumber');
        $tAGNCode               = $this->input->post('tAgnCode');
        $tBCHCode               = $this->input->post('tBchCode');
        $tDocumentNumber        = ($tDocumentNumber == '') ? 'DUMMY' : $tDocumentNumber;

        if (!empty($poItem->aResult)) {
            $nCount = count($poItem->aResult[0]);
            $aItem  = $poItem->aResult[0];
            $nNum   = 1;
            for ($i = 0; $i < $nCount; $i++) {
                $tPDTSetType        = $aItem[$i]->FTPsvType;
                $tPDTSetPuncode     = $aItem[$i]->FTPunCode;
                $tPDTSetStaSuggest  = $aItem[$i]->FTPsvStaSuggest;
                $tPDTSetName        = $aItem[$i]->FTPdtName;
                $tPDTSetCode        = $aItem[$i]->FTPdtCodeSet;
                $tPDTMain           = $aItem[$i]->FTPdtCode;
                $nQTYSet            = $aItem[$i]->QTYSet;

                $aInsertToTemp = array(
                    'FTBchCode'     => $tBCHCode,
                    'FTXshDocNo'    => $tDocumentNumber,
                    'FTXthDocKey'   => 'TSVTBookDT',
                    'FTPdtCode'     => $tPDTSetCode,
                    'FTSrnCode'     => $tPDTMain,   //รหัสสินค้าแม่
                    'FTPunCode'     => $tPDTSetPuncode,
                    'FTXcdPdtSeq'   => 0,
                    'FCXtdQtySet'   => $nQTYSet,
                    'FTXcdPdtName'  => $tPDTSetName,
                    'FNXtdPdtLevel' => $tPDTSetType, //ประเภทรายการ 1 : เปลี่ยนคิดราคา , 2 : ตรวจสอบไม่คิดราคา
                    'FTXtdPdtStaSet' => 1,
                    'FTXtdPdtParent' => $tPDTSetStaSuggest //สถานะแนะนำ 1 : แนะนำ , 2 : ไม่ได้แนะนำ 
                );
                $this->Bookingcalendar_model->FSaMBKInsertPDTSetToTemp($aInsertToTemp);

                $nNum++;
            }
        }
    }

    //เพิ่มข้อมูล กดยืนยัน
    public function FSxCBKEventAdd(){
        $tEventClick        = $this->input->post('tEventClick');
        $tStaPrcDoc         = $this->input->post('tStaPrcDoc');
        $tStaApv            = $this->input->post('tStaApv');
        $tDocumentNumber    = $this->input->post('tDocuemntNumber');
        $tCusCode           = $this->input->post('tCusCode');
        $tCarCode           = $this->input->post('tCarCode');
        $tTelphone          = $this->input->post('tTelphone');
        $tEmail             = $this->input->post('tEmail');
        $nWaringDay         = $this->input->post('nWaringDay');
        $dDateBooking       = $this->input->post('dDateBooking');
        $nStartTime         = $this->input->post('nStartTime');
        $nEndTime           = $this->input->post('nEndTime');
        $tDateBooking       = $this->input->post('tDateBooking');

        //วันที่นัดหมาย
        $aNewDate           = explode("/", $tDateBooking);
        $dDateBooking       = $aNewDate[2] . '-' . $aNewDate[1] . '-' . $aNewDate[0];
        $aCoulumn           = json_decode($this->input->post('aCoulumn'));
        $tRemark            = $this->input->post('tRemark');
        $tBayCode           = $this->input->post('tBayCode');
        $tBCHCode           = $this->input->post('tBCHCode');
        $tAGNCode           = $this->input->post('tAGNCode');
        $tRSNCode           = $this->input->post('tRSNCode');

        $this->db->trans_begin();

        if ($tDocumentNumber == '' || $tDocumentNumber == null) { //ขา Insert
            //เลขที่เอกสาร
            $aStoreParam = array(
                "tTblName"    => 'TSVTBookHD',
                "tDocType"    => 1,
                "tBchCode"    => $tBCHCode,
                "tShpCode"    => "",
                "tPosCode"    => "",
                "dDocDate"    => date("Y-m-d")
            );
            $aAutogen        = FCNaHAUTGenDocNo($aStoreParam);
            $tDocumentNumber = $aAutogen[0]["FTXxhDocNo"];
            $tDummyDocument  = 'DUMMY';
        } else { //ขา Update
            $tDocumentNumber = $tDocumentNumber;
            $tDummyDocument  = $tDocumentNumber;

            //Delete : TSVTBookDT , TSVTBookHD , TSVTBookDTSet , TSVTBookHDDocRef
            $this->Bookingcalendar_model->FSaMBKDeleteB4Insert($tDocumentNumber);
        }

        $tRefBookKey     = (int)$tCusCode . (int)str_replace("-", "", $dDateBooking) . (int)str_replace(":", "", $nStartTime);

        //ถ้ากดบันทึกจะเปลี่ยนสถานะ
        if ($tEventClick == 'save') { //ถ้ากดบันทึก
            $FTXshStaApv            = $tStaApv;
            $FTXshStaPrcDoc         = $tStaPrcDoc;
            $tCallMQ                = 'DontCallMQ';
        } else if ($tEventClick == 'booking') { //ถ้ากดนัดหมาย
            $FTXshStaApv            = $tStaApv;
            $FTXshStaPrcDoc         = $tStaPrcDoc;
            $tCallMQ                = 'CallMQ';
        } else {
            $FTXshStaApv            = '';
            $FTXshStaPrcDoc         = '';
            $tCallMQ                = '';
        }

        //ส่วนข้อมูล HD -> รายละเอียด
        $aInsertHD      = array(
            'FTAgnCode'         => $tAGNCode,
            'FTBchCode'         => $tBCHCode,
            'FTXshDocNo'        => $tDocumentNumber,
            'FDXshDocDate'      => date('Y-m-d H:i:s'),
            'FNXshRefBookKey'   => (int)$tRefBookKey,
            'FTXshToAgn'        => $tAGNCode,
            'FTXshToBch'        => $tBCHCode,
            'FTXshToShp'        => '',
            'FTXshToPos'        => $tBayCode,
            'FTXshCstRef1'      => $tCusCode,
            'FTXshCstRef2'      => $tCarCode,
            'FDXshBookDate'     => $dDateBooking,
            'FDXshTimeStart'    => $dDateBooking . ' ' . $nStartTime,
            'FDXshTimeStop'     => $dDateBooking . ' ' . $nEndTime,
            'FNXshQtyNotiPrev'  => $nWaringDay,
            'FTXshTel'          => $tTelphone,
            'FTXshEmail'        => $tEmail,
            'FTXshRmk'          => $tRemark,
            'FTRsnCode'         => '',
            'FTXshStaDoc'       => 1,    //สถานะเอกสาร     => 1:สมบูรณ์ , 3:ยกเลิก
            'FTXshStaApv'       => $FTXshStaApv,    //สถานะอนุมัติ      => null:ยังไม่ยืนยัน , 1:ยืนยันแล้ว 
            'FTXshStaPrcDoc'    => $FTXshStaPrcDoc, //สถานะนัดหมาย    => null:รอนัดหมาย , 1:บันทึกเฉยๆ , 2:นัดหมาย 
            'FNXshStaDocAct'    => 1,   //สถานะเคลื่อนไหว
            'FTXshStaClosed'    => '',
            'FTXshAppVer'       => 'SB',
            'FTAppCode'         => '-',
            'FDLastUpdOn'       => date("Y-m-d H:i:s"),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            'FDCreateOn'        => date("Y-m-d H:i:s"),
            'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            'FTRsnCode'         => $tRSNCode
        );
        $this->Bookingcalendar_model->FSaMBKInsertBayService($aInsertHD, 'TSVTBookHD');

        //ส่วนของการคำนวณ DT 
        $aCalcDTParams = [
            'tBchCode'          => $tBCHCode,
            'tDataDocEvnCall'   => '',
            'tDataVatInOrEx'    => $this->input->post('tDocVat'),
            'tDataDocNo'        => $tDummyDocument,
            'tDataDocKey'       => 'TSVTBookDT',
            'tDataSeqNo'        => ''
        ];
        FCNbHCallCalcDocDTTemp($aCalcDTParams);

        //เพิ่มข้อมูล DT (สินค้าปกติ + สินค้าเซต + สินค้าบำรุงรักษา)
        $this->Bookingcalendar_model->FSaMBKMoveDTToTable($aInsertHD, $tDummyDocument);

        //ส่วนของการคำนวณ HD
        $aCalDTTempParams = [
            'tDocNo'            => $tDocumentNumber,
            'tBchCode'          => $tBCHCode,
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
            'tDocKey'           => 'TSVTBookDT',
            'tDataVatInOrEx'    => $this->input->post('tDocVat'),
        ];
        $aCalDTTempForHD = $this->FSaCBKCalDTTempForHD($aCalDTTempParams);

        //ส่วนข้อมูล HD -> รายละเอียดตัวเลข
        $aUpdateCalculate = array(
            'FTXshVATInOrEx'        => $this->input->post('tDocVat'),
            'FTUsrCode'             => $this->session->userdata('tSesUsername'),
            'FTXshApvCode'          => '',
            'FNXshDocPrint'         => 0,
            'FTRteCode'             => 0,
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
            'FCXshVat'              => $aCalDTTempForHD['FCXphVat'],
            'FCXshVatable'          => $aCalDTTempForHD['FCXphVatable'],
            'FCXshWpTax'            => $aCalDTTempForHD['FTXphWpCode'],
            'FCXshGrand'            => $aCalDTTempForHD['FCXphGrand'],
        );
        $this->Bookingcalendar_model->FSaMBKUpdateDTToTable($aCalDTTempParams, $aUpdateCalculate);

        // Check Status Transection DB
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $aReturnData = array(
                'tDocNo'        => '',
                'nStaEvent'     => '900',
                'tStaMessg'     => "Error Unsucess Add Document."
            );
        } else {
            $this->db->trans_commit();

            //ถ้าเป็น นัดหมาย & นัดหมาย และยืนยัน ต้องวิ่ง MQ
            if ($tCallMQ == 'CallMQ') {
                $aMQParams = [
                    "queueName" => "CN_QDocApprove",
                    "params"    => [
                        'ptFunction'    => "TSVTBookHD",
                        'ptSource'      => 'AdaStoreBack',
                        'ptDest'        => 'MQReceivePrc',
                        'ptFilter'      => $tBCHCode,
                        'ptData'        => json_encode([
                            "ptBchCode"     => $tBCHCode,
                            "ptDocNo"       => $tDocumentNumber,
                            "ptUser"        => $this->session->userdata("tSesUsername"),
                        ])
                    ]
                ];
                FCNxCallRabbitMQ($aMQParams);
            }

            //ถ้ากดนัดหมาย
            if ($tEventClick == 'booking') {
                //เช็คว่าสินค้าที่เลือก ไปเจอกับสินค้า CSTFollow ไหม 
                $this->Bookingcalendar_model->FSaMBKCheckPDTTempFoundINCstFollow($tDocumentNumber, $this->input->post('tIDEventClick'));
            }

            $aReturnData = array(
                'tDocNo'        => $tDocumentNumber,
                'nStaReturn'    => '1',
                'tStaMessg'     => 'Success Add Document.'
            );
        }

        echo json_encode($aReturnData);
    }

    //ตรวจสอบสต็อก
    public function FSxCBKCheckStockInTemp(){
        $tDocumentNumber    = $this->input->post('tDocuemntNumber');
        $nTimes             = $this->input->post('nTimes');
        $aResultCheck       = $this->Bookingcalendar_model->FSaMBKCheckSTKInTemp($tDocumentNumber);
        if ($aResultCheck['rtCode'] == 800) { //มีสินค้ายังไม่ผ่าน
            $aReturnData = array(
                'nStaReturn'    => 800,
                'nTimes'        => (int)$nTimes,
                'tDocNo'        => $aResultCheck['rtDoc'],
                'aResultCheck'  => $aResultCheck['rtItem']
            );
        } else { //สินค้าผ่านทุกตัว
            $this->Bookingcalendar_model->FSaMBKUpdateSTKPrcAndSTAAPVDocNo($tDocumentNumber);
            $aReturnData = array(
                'nStaReturn'     => 1,
                'nTimes'         => (int)$nTimes,
                'tDocNo'         => $aResultCheck['rtDoc']
            );
        }
        echo json_encode($aReturnData);
    }

    //อัพเดทสต็อกว่าไม่ผ่าน
    public function FSxCBKUpdateSTKFail(){
        $tDocumentNumber    = $this->input->post('tDocuemntNumber');
        $oItemFail          = $this->input->post('oItemFail');
        $tTextPDT           = '';
        for($i=0; $i<count($oItemFail['aResultCheck']); $i++){
            $tTextPDT .= "'".$oItemFail['aResultCheck'][$i]['FTPdtCode']."',";
        }
        $tTextPDT = rtrim($tTextPDT, ", ");
        $this->Bookingcalendar_model->FSaMBKUpdateSTKFail($tDocumentNumber,$tTextPDT);
    }

    //ยกเลิกเอกสาร 
    public function FSxCBKEventCancel(){
        $tDocumentNumber    = $this->input->post('tDocuemntNumber');
        $tBchCode           = $this->input->post('tBchCode');
        $tRSNCode           = $this->input->post('tRSNCode');
        $aWhere = array(
            'tDocumentNumber' => $tDocumentNumber,
            'tBchCode'        => $tBchCode,
            'tRSNCode'        => $tRSNCode
        );

        //ยกเลิกเอกสาร 
        $this->Bookingcalendar_model->FSaMBKEventCancelDocument($aWhere);

        //ไปอัพเดทสินค้าใน CstFollow ให้กลับมาติดตามได้ต่อ
        $this->Bookingcalendar_model->FSaMBKEventReCstFollow($aWhere);

        //ถ้ายกเลิกเอกสาร ต้องวิ่งไปเช็คว่าสินค้าใน DT มี Status จองยัง ถ้ามีแล้วต้องวิ่ง MQ / ถ้าไม่มีสถานะยังไม่จองไม่ต้องทำไร (รอทำ)
        $nItems = $this->Bookingcalendar_model->FSaMBKCheckStockProcessInTemp($aWhere);
        if ($nItems != 0) {
            $aMQParams = [
                "queueName" => "CN_QDocApprove",
                "params"    => [
                    'ptFunction'    => "TSVTBookHD",
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
        }
    }

    //ข้อมูลรถ
    public function FSxCBKGetInforCar(){
        $nCar   = $this->input->post('pnCar');
        $aItem  = $this->Bookingcalendar_model->FSaMBKGetCarbyID($nCar);
        echo json_encode($aItem);
    }

    //คำนวณจาก DT Temp ให้ HD
    private function FSaCBKCalDTTempForHD($paParams){
        $aCalDTTemp = $this->Bookingcalendar_model->FSaMBKCalInDTTemp($paParams);
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

    //ลบข้อมูลใน Temp
    public function FSxCBKDeletePDTInDB(){
        $nSeqNo             = $this->input->post('nSeqNo');
        $tDocumentNumber    = $this->input->post('tDocumentNumber');
        $tBCHCode           = $this->input->post('tBchCode');
        $tPdtCode           = $this->input->post('tPdtCode');
        $tDocumentNumber    = ($tDocumentNumber == '') ? 'DUMMY' : $tDocumentNumber;

        $aWhereDelete = array(
            'FTBchCode'     => $tBCHCode,
            'FTPdtCode'     => $tPdtCode,
            'FTXshDocNo'    => $tDocumentNumber,
            'FTXthDocKey'   => 'TSVTBookDT',
            'FTXcdPdtSeq'   => $nSeqNo
        );

        $this->Bookingcalendar_model->FSaMBKDeleteDTPDTInTemp($aWhereDelete);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //สำหรับหน้าค้นหาตามเงื่อนไขของแท็บสอง
    public function FSvCBKCusDatatable(){

        $nPage                  = $this->input->post('nPage');
        $tTypeCondition         = $this->input->post('tTypeCondition');
        $tFindCus               = $this->input->post('tFindCus');
        $tFindCusEmail          = $this->input->post('tFindCusEmail');
        $tFindCusTel            = $this->input->post('tFindCusTel');
        $tFindCusCarID          = $this->input->post('tFindCusCarID');

        $aDataCondition = array(
            'nPage'                 => $nPage,
            'nRow'                  => 20,
            'tTypeCondition'        => $tTypeCondition,
            'tFindDateFrom'         => $this->input->post('tFindDateFrom'),
            'tFindDateTo'           => $this->input->post('tFindDateTo'),
            'tFindCus'              => trim($tFindCus),
            'tFindCusEmail'         => trim($tFindCusEmail),
            'tFindCusTel'           => trim($tFindCusTel),
            'tFindCusCarID'         => trim($tFindCusCarID)
        );

        if ($tTypeCondition == "5") { //ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย
            $aDataList = $this->Bookingcalendar_model->FSaMBKFindDataByCustomerWaitItem($aDataCondition);
        } else {
            $aDataList = $this->Bookingcalendar_model->FSaMBKFindDataByCustomer($aDataCondition);
        }
        
        $aReturnToView = array(
            'nPage'             => $nPage,
            'aAlwEvent'         => FCNaHCheckAlwFunc('docBookingCalendar/0/0'),
            'aDataList'         => $aDataList,
            'tTypeCondition'    => $tTypeCondition,
        );

        $this->load->view('document/bookingcalendar/FindByCustomer/wFindByCustomerDataTable', $aReturnToView);
    }

    //ประวัติการเข้าใช้บริการ
    public function FSvCBKHistoryService(){
        $tCustomerCode      = $this->input->post('tCustomerCode');
        $tCarCode           = $this->input->post('tCarCode');
        $tStaDoc            = $this->input->post('tStaDoc');
        $tStaPrcDoc         = $this->input->post('tStaPrcDoc');
        $tDateForcateFrom   = $this->input->post('tDateForcateFrom');
        $tDateForcateTo     = $this->input->post('tDateForcateTo');

        $aDataCondition = array(
            'tCustomerCode'    => $tCustomerCode,
            'tCarCode'         => $tCarCode,
            'tDateForcateFrom' => $tDateForcateFrom,
            'tDateForcateTo'   => $tDateForcateTo
        );

        //select ข้อมูล
        $aItemFollow = $this->Bookingcalendar_model->FSaMBKCstFollow($aDataCondition);

        $aReturnToView = array(
            'aItemFollow'        => $aItemFollow,
            'tStaDoc'            => $tStaDoc,
            'tStaPrcDoc'         => $tStaPrcDoc
        );
        $this->load->view('document/bookingcalendar/wBookingcalendarItemFollow', $aReturnToView);
    }

    //เลิกติดตามสินค้าใน follow
    public function FSxCBKDeleteFollow(){
        $tPDTCode  = $this->input->post('tPDTCode');
        $tCarReg   = $this->input->post('tCarReg');
        $tReason   = $this->input->post('tReason');

        $aDataCondition = array(
            'tPDTCode'    => $tPDTCode,
            'tCarReg'     => $tCarReg,
            'nStaBook'    => 4, //เลิกติดตาม
            'tReason'     => $tReason,
            'tRemark'     => 'เลิกติดตามสินค้าตัวนี้'
        );

        //update ข้อมูล
        $this->Bookingcalendar_model->FSaMBKDeleteFollow($aDataCondition);
    }

    //ยืนยันสินค้าจาก follow
    public function FSxCBKConfirmFollow(){
        $tPDTCode  = $this->input->post('tPDTCode');
        $tCarReg   = $this->input->post('tCarReg');

        $aDataCondition = array(
            'tPDTCode'    => $tPDTCode,
            'tCarReg'     => $tCarReg,
            'nStaBook'    => 2 //นัดเเล้ว
        );

        //update ข้อมูล
        $this->Bookingcalendar_model->FSaMBKDeleteFollow($aDataCondition);
    }

    //รายละเอียด Booking
    public function FSaCBKGetDetailBooking(){
        $tBookingID  = $this->input->post('ptBookingID');
        $aReturn      = $this->Bookingcalendar_model->FSaMBKGetDetailBooking($tBookingID);
        echo json_encode($aReturn);
    }

    //กรณีเลื่อนวันนัด
    public function FSxCBKEventPostpone(){
        $tDocuemntNumber    = $this->input->post('tDocuemntNumber');
        $dDateBooking       = $this->input->post('tDateBooking');
        $dDateBooking       = str_replace('/', '-', $dDateBooking);
        $tTelphone          = $this->input->post('tTelphone');
        $tEmail             = $this->input->post('tEmail');
        $nStartTime         = $this->input->post('nStartTime');
        $nEndTime           = $this->input->post('nEndTime');
        $nWaringDay         = $this->input->post('nWaringDay');

        $aUpdate = array(
            'FTXshDocNo'        => $tDocuemntNumber,
            'FDXshBookDate'      => date_format(date_create($dDateBooking), 'Y-m-d'),
            'FDXshTimeStart'    => date('Y-m-d', strtotime($dDateBooking)) . ' ' . $nStartTime,
            'FDXshTimeStop'     => date('Y-m-d', strtotime($dDateBooking)) . ' ' . $nEndTime,
            'FTXshTel'          => $tTelphone,
            'FTXshEmail'        => $tEmail,
            'FNXshQtyNotiPrev'  => $nWaringDay,
            'FDLastUpdOn'       => date("Y-m-d H:i:s"),
            'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
        );
        $this->Bookingcalendar_model->FSaMBKUpdatePostPone($aUpdate);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //Call API เช็คสต็อก
    public function FSxCBKCheckStock(){
        $tDocumentNumber = $this->input->post('tDocuemntNumber');
        $tDocumentNumber  = ($tDocumentNumber == '') ? 'DUMMY' : $tDocumentNumber;
        $tBCHCode         = $this->input->post('tBchCode');

        //วิ่งเข้ามาหารายการสินค้า ออกมาเป็น array
        $aGetItem = $this->Bookingcalendar_model->FSaMBKGetPDTInTempToArray($tDocumentNumber, $tBCHCode);

        if (empty($aGetItem)) {
            $aReturnData = array(
                'tRetrunStatus' => 800,
                'tTitle'        => 'NULL'
            );
        } else {
            $aConfig = $this->Bookingcalendar_model->FSxMBKGetConfigAPI();
            if ($aConfig['rtCode'] == '800') {
                $aReturnData = array(
                    'tRetrunStatus' => 800,
                    'tTitle'        => 'APIFAIL'
                );
                echo '<script>FSvCMNSetMsgErrorDialog("เกิดข้อผิดพลาด ไม่พบ API ในการเชื่อมต่อ")</script>';
                exit;
            } else {
                $this->tPublicAPI = $aConfig['raItems'][0]['FTUrlAddress'];
            }

            //API CheckSTK
            $aToAPI     = $aGetItem;
            $tUrlApi    = $this->tPublicAPI . '/Stock/CheckStockPdts';
            // $tUrlApi    = 'https://202.44.55.94/StoreBackFitAuto/API2CNCheckOnline/Stock/CheckStockPdts'; 

            $aParam     = $aToAPI;
            $aAPIKey    = array(
                'tKey'      => 'X-API-KEY',
                'tValue'    => '12345678-1111-1111-1111-123456789410'
            );
            $aResult    = FCNaHCallAPIBasic($tUrlApi, 'POST', $aParam, $aAPIKey);

            if ($aResult['rtCode'] == '001') {
                $aItemCheck = array();
                $nCountItem = count($aResult['raItems']);

                for ($i = 0; $i < $nCountItem; $i++) {
                    if ($aResult['raItems'][$i]['rtStaPrcStock'] == 2) { //ไม่พอ
                        array_push($aItemCheck, $aResult['raItems'][$i]['rtPdtCode']);
                    }
                }

                $aReturnData = array(
                    'tRetrunStatus'     => 1,
                    'tTitle'            => 'SUCCESS',
                    'aItemStkFail'      => $aItemCheck,
                    'nCountItem'        => count($aItemCheck)
                );
            } else {
                $aReturnData = array(
                    'tRetrunStatus'     => 800,
                    'tTitle'            => 'NOTFOUND'
                );
            }
        }

        echo json_encode($aReturnData);
    }

    //โทรคอนเฟริมกับลูกค้าเเเล้ว
    public function FSxCBKEventConfirmByTelDone(){
        $ptBookingID    = $this->input->post('ptBookingID');
        $aUpdate = array(
            'FTXshDocNo'        => $ptBookingID
        );
        $this->Bookingcalendar_model->FSaMBKUpdateConfirmByTelDone($aUpdate);
    }

    //แก้ไข InLine จำนวนใน Temp (PDT)
    public function FSxCBKEventUpdateToDT(){

        $nQty       = $this->input->post('nQty');
        $nFactor    = $this->input->post('nFactor');
        $tDocNo     = $this->input->post('tDocNo');
        $nSeqNo     = $this->input->post('nSeqNo'); 
        $aInsertToTemp = array(
            'FCXtdQty'          => $nQty,
            'FCXtdQtyAll'       => $nQty  *  $nFactor,
            'tDocNo'            => $tDocNo,
            'nSeqNo'            => $nSeqNo,
        );

        $this->Bookingcalendar_model->FSaMBKUpdateToTemp($aInsertToTemp);
    }

    //Render Excel Reportc 
    function FSxCBKRenderExcel(){
        $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
        $ocmSearchTypeCondition = $this->input->get('ocmSearchTypeCondition');
        $oetDateConditionFrom = $this->input->get('oetDateConditionFrom');
        $oetDateConditionTo = $this->input->get('oetDateConditionTo');
        $oetFindCusCode = $this->input->get('oetFindCusCode');
        $oetFindCusEmail = $this->input->get('oetFindCusEmail');
        $oetFindCusTel = $this->input->get('oetFindCusTel');
        $oetFindCusCarIDCode = $this->input->get('oetFindCusCarIDCode');
        $writer = WriterEntityFactory::createXLSXWriter(); // เรียกฟังชั่นสร้างไฟล์excel จาก Libraries Spout
        if($ocmSearchTypeCondition == 4){
            $fileName =  'ค้นหาลูกค้าที่ไม่มาตามนัดยังไม่ถึงกำหนด'.'_'.date('Y-m-d-H-i-s').'.xlsx';
        }else{
            $fileName = language('document/bookingcalendar/bookingcalendar', 'tTypeCondition'.$ocmSearchTypeCondition).'_'.date('Y-m-d-H-i-s').'.xlsx'; // กำหนดชื่อไฟล์
        }
        $writer->openToBrowser($fileName); // สร้างไฟล์าำหรับ Download  โดยนำ ชื่อมาจากตัวแปร  $fileName

        $oStyle = (new StyleBuilder())  // กำหนดรูปแบบของตารางและฟอนต์
            ->setFontBold() //กำหนดรูปแบบตัวหนา
            ->setFontSize(18) //กำหนดขนาดตัวอักษร
            ->build(); //สร้างตารางจากข้อมูลด้านบน

        $oStyle2 = (new StyleBuilder()) // กำหนดรูปแบบของตารางและฟอนต์2
            ->setFontBold() //กำหนดรูปแบบตัวหนา
            ->setFontSize(12) //กำหนดขนาดตัวอักษร
            ->build(); //สร้างตารางจากข้อมูลด้านบน

        $oBorder = (new BorderBuilder()) // สร้างคำสั่งกำหนดเส้นขอบตาราง
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางบน
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางล่าง
            ->build();  //สร้างตารางจากข้อมูลด้านบน

        $oStyleColums = (new StyleBuilder()) //สร้างคำสั่งกำหนดเส้นขอบตาราง
            ->setBorder($oBorder) //กำหนดรูปแบบตัวอังษร
            ->setFontBold() //กำหนดขนาดตัวอักษร
            ->build(); //สร้างตารางจากข้อมูลด้านบน
            
        if($ocmSearchTypeCondition == 5){

            $aCells = [ // สร้างหัวตาราง บรรทัดที่2
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang 
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2 
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'tModalAdvNo')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('common/main/main', 'tCenterModalPDTBranch')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKBookingNumber')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKDateBook')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKTimeBook')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKCstCode')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKCustomer')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKTelephone')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('service/car/car', 'tCARProductNo')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('service/car/car', 'tCARProductName')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_Qty')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('common/main/main', 'tModalPriceUnit')),//รับค่าจาก  lang  
            ];
        }elseif($ocmSearchTypeCondition == 1){

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'สาขาที่ใช้')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'วันที่ใช้บริการ')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTName')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTTel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARRegNo')),//รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARBrand')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARModel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'ที่อยู่')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'อีเมล')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'BlueCard')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'บริการที่ใช้/สินค้าที่ซื้อ')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'จำนวนเงิน')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'Paymenttype')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'บริการครั้งที่แล้ว')),//รับค่าจาก  lang 
            ];
        }elseif($ocmSearchTypeCondition == 2){

            $aCells = [ // สร้างหัวตาราง บรรทัดที่2
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang 
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2 
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'tModalAdvNo')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTCode')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTName')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTTel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTEmail')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARBrand')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARRegNo')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tDateIN')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'วันที่ต้องเข้าใช้บริการครั้งถัดไป')),//รับค่าจาก  lang  
            ];
        }elseif($ocmSearchTypeCondition == 3){
            $aCells = [ // สร้างหัวตาราง บรรทัดที่2
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang 
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2 
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'tModalAdvNo')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTCode')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTName')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTTel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTEmail')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARBrand')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARRegNo')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKDatTimeBooking')),//รับค่าจาก  lang  
            ];
        }elseif($ocmSearchTypeCondition == 4){
            $aCells = [ // สร้างหัวตาราง บรรทัดที่2
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang 
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2 
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'tModalAdvNo')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTCode')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTName')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTTel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTEmail')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARBrand')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARRegNo')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'รหัสเอกสารการนัดหมาย')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBKDatTimeBooking')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'สถานะเอกสาร')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'สถานะสินค้า')),//รับค่าจาก  lang  
            ];
        }elseif($ocmSearchTypeCondition == 6){

            $aCells = [ // สร้างหัวตาราง บรรทัดที่2
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang 
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2 
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow

            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('common/main/main', 'tModalAdvNo')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTCode')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTName')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTTel')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('customer/customer/customer', 'tCSTEmail')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARBrand')), //รับค่าจาก  lang 
                WriterEntityFactory::createCell(language('service/car/car', 'tCARRegNo')),//รับค่าจาก  lang  
                WriterEntityFactory::createCell(language('document/bookingcalendar/bookingcalendar', 'tBookingDate')),//รับค่าจาก  lang  
            ];
        }
        
        $aMulltiRow  = WriterEntityFactory::createRow($cells , $oStyleColums ); //  รับข้อมูล Row จากข้อมูล $cells
        $writer->addRow($aMulltiRow); // สร้าง Row

        //Get Data
        $aDataCondition = array(
            'nPage'                 => $nPage,
            'nRow'                  => 99999999,
            'tTypeCondition'        => $ocmSearchTypeCondition,
            'tFindDateFrom'         => $oetDateConditionFrom,
            'tFindDateTo'           => $oetDateConditionTo,
            'tFindCus'              => trim($oetFindCusCode),
            'tFindCusEmail'         => trim($oetFindCusEmail),
            'tFindCusTel'           => trim($oetFindCusTel),
            'tFindCusCarID'         => trim($oetFindCusCarIDCode)
        );

        if ($ocmSearchTypeCondition == "5") { //ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย
            $aDataList = $this->Bookingcalendar_model->FSaMBKFindDataByCustomerWaitItem($aDataCondition);
        } else {
            $aDataList = $this->Bookingcalendar_model->FSaMBKFindDataByCustomer($aDataCondition);
        }

        if (isset($aDataList['raItems']) && !empty($aDataList['raItems'])) {
            if($ocmSearchTypeCondition == 5){
                $nSeq = 1;
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                $tDateFT = $aValue['TimeStart'] .'-'. $aValue['TimeEnd'];
                    $values = [
                        WriterEntityFactory::createCell($nSeq++),
                        WriterEntityFactory::createCell(($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']),
                        WriterEntityFactory::createCell(($aValue['FTXshDocNo'] == '') ? '-' : $aValue['FTXshDocNo']),
                        WriterEntityFactory::createCell(($aValue['DateBooking'] == '') ? '-' : $aValue['DateBooking']),
                        WriterEntityFactory::createCell($tDateFT),
                        WriterEntityFactory::createCell(($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTPdtCode'] == '') ? '-' : $aValue['FTPdtCode']),
                        WriterEntityFactory::createCell(($aValue['FTXsdPdtName'] == '') ? '-' : $aValue['FTXsdPdtName']),
                        WriterEntityFactory::createCell(($aValue['FCXsdQty'] == '') ? '-' : number_format($aValue['FCXsdQty'],2)),
                        WriterEntityFactory::createCell(($aValue['FTPunName'] == '') ? '-' : $aValue['FTPunName']),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }elseif($ocmSearchTypeCondition == 1){
                $nOptDecimalShow = get_cookie('tOptDecimalShow');
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                    $values = [
                        WriterEntityFactory::createCell($aValue['FTBchName']),
                        WriterEntityFactory::createCell($aValue['DateStart']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']),
                        WriterEntityFactory::createCell(($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']),
                        WriterEntityFactory::createCell(($aValue['FTCarModel'] == '') ? '-' : $aValue['FTCarModel']),
                        WriterEntityFactory::createCell(($aValue['FTCstAddress'] == '') ? '-' : $aValue['FTCstAddress']),
                        WriterEntityFactory::createCell(($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']),
                        WriterEntityFactory::createCell(($aValue['FTTxnCrdCode'] == '') ? '-' : $aValue['FTTxnCrdCode']),
                        WriterEntityFactory::createCell(($aValue['FTXshRefDocNo'] == '') ? '-' : $aValue['FTXshRefDocNo']),
                        WriterEntityFactory::createCell(($aValue['FCXshGrand'] == '') ? '-' : number_format($aValue['FCXshGrand'],$nOptDecimalShow)),
                        WriterEntityFactory::createCell(($aValue['FTRcvName'] == '') ? '-' : $aValue['FTRcvName']),
                        WriterEntityFactory::createCell(($aValue['LastService'] == '') ? '-' : $aValue['LastService']),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }
            elseif($ocmSearchTypeCondition == 2){
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                    if($aValue['DateForcate'] == '' || $aValue['DateForcate'] == null){
                        $dDateForcate = 'ไม่ได้ระบุข้อมูล';
                    }else{
                        $dDateForcate = $aValue['DateForcate'];
                    } 
                    $values = [
                        WriterEntityFactory::createCell($aValue['FNRowID']),
                        WriterEntityFactory::createCell(($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']),
                        WriterEntityFactory::createCell(($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']),
                        WriterEntityFactory::createCell(($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']),
                        WriterEntityFactory::createCell($aValue['DateStart']),
                        WriterEntityFactory::createCell($dDateForcate),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }elseif($ocmSearchTypeCondition == 3){
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                    $values = [
                        WriterEntityFactory::createCell($aValue['FNRowID']),
                        WriterEntityFactory::createCell(($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']),
                        WriterEntityFactory::createCell(($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']),
                        WriterEntityFactory::createCell(($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']),
                        WriterEntityFactory::createCell($aValue['DateStart']),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }elseif($ocmSearchTypeCondition == 4){
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                    if($aValue['FDXshBookDate'] == date('Y-m-d 00:00:00.000')){
                        $tTextStaDoc = 'กำลังดำเนินการ';
                    }else if($aValue['FDXshBookDate'] < date('Y-m-d H:i:s')){
                        $tTextStaDoc = 'เลยกำหนด';
                    }else{
                        $tTextStaDoc = 'ยังไม่ถึงกำหนด';
                    }
                    if($aValue['FTXshStaPrcDoc'] == 2){
                        $tTextStaPrcDoc = 'จองสต๊อกครบแล้ว';
                    }else if($aValue['FTXshStaPrcDoc'] == 1){
                        $tTextStaPrcDoc = 'จองบางส่วน';
                    }else{
                        $tTextStaPrcDoc = 'ไม่ต้องตรวจสอบสต๊อก';
                    }
                    $values = [
                        WriterEntityFactory::createCell($aValue['FNRowID']),
                        WriterEntityFactory::createCell(($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']),
                        WriterEntityFactory::createCell(($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']),
                        WriterEntityFactory::createCell(($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']),
                        WriterEntityFactory::createCell($aValue['BOOKID']),
                        WriterEntityFactory::createCell($aValue['DateStart']),
                        WriterEntityFactory::createCell($tTextStaDoc),
                        WriterEntityFactory::createCell($tTextStaPrcDoc),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }elseif($ocmSearchTypeCondition == 6){
                foreach ($aDataList['raItems'] as $nKey => $aValue) {  
                    $values = [
                        WriterEntityFactory::createCell($aValue['FNRowID']),
                        WriterEntityFactory::createCell(($aValue['FTCstCode'] == '') ? '-' : $aValue['FTCstCode']),
                        WriterEntityFactory::createCell(($aValue['FTCstName'] == '') ? '-' : $aValue['FTCstName']),
                        WriterEntityFactory::createCell(($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel']),
                        WriterEntityFactory::createCell(($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail']),
                        WriterEntityFactory::createCell(($aValue['CarBrand'] == '') ? '-' : $aValue['CarBrand']),
                        WriterEntityFactory::createCell(($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']),
                        WriterEntityFactory::createCell($aValue['DateStart']),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }
        }

        $writer->close();
    }

    //หาว่าลูกค้าคนนี้ มีรถอะไร เพื่อเอาค่าไป default
    function FSaCBKFindCar(){
        $tCstCode   = $this->input->post('tCstCode');
        $aResult    = $this->Bookingcalendar_model->FSaMBKFindCar($tCstCode);
        if(!empty($aResult)){
            $aReturnData = array(
                'tRetrunStatus' => 1,
                'aResult'       => $aResult
            );
        }else{
            $aReturnData = array(
                'tRetrunStatus' => 800,
                'aResult'       => ''
            );
        }
        echo json_encode($aReturnData);
    }

}