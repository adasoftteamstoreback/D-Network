<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Invoicebill_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/Invoicebill/Invoicebill_model');

        // Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nIVBBrowseType,$tIVBBrowseOption){

        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        
        $aDataConfigView    = array(
            'nIVBBrowseType'        => $nIVBBrowseType,
            'tIVBBrowseOption'      => $tIVBBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('docInvoiceBill/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'aParams'               => $aParams
        );
        $this->load->view('document/Invoicebill/wInvoiceBill',$aDataConfigView);
    }

    //List
    public function FSvCIVBPageList(){
        $this->load->view('document/Invoicebill/wInvoiceBillSearchList');   
    }

    //ตารางข้อมูล
    public function FSvCIVBDatatable(){
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

        $aList      = $this->Invoicebill_model->FSaMIVBList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );

        $tViewDataTable = $this->load->view('document/Invoicebill/wInvoiceBillDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCIVBPageAdd(){
        try{

            //ล้างค่าใน Temp
            $this->Invoicebill_model->FSaMIVBDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'aDataDocCST'       => array('rtCode'=>'99'),    
            );
            $tViewPageAdd       = $this->load->view('document/Invoicebill/wInvoiceBillPageAdd',$aDataConfigViewAdd,true);
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
    public function FSvCIVBPageEdit(){
        try {
            $ptDocumentNumber = $this->input->post('ptIVBDocNo');

            // Clear Data In Doc DT Temp
            $this->Invoicebill_model->FSaMIVBDeletePDTInTmp($ptDocumentNumber);

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Array Data Where Get (HD,HDCst)
            $aDataWhere = array(
                'FTXphDocNo'    => $ptDocumentNumber,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->Invoicebill_model->FSaMIVBGetDataDocHD($aDataWhere);

            // Get Data Document CST HD
            $aDataDocSPLHD      = $this->Invoicebill_model->FSaMIVBGetDataDocSPLHD($aDataWhere);
            $aDataWhere['FTSplCode'] = $aDataDocSPLHD['raItems']['FTSplCode'];

            // Get Data Document Ref
            $aDataDocHDDocRef   = $this->Invoicebill_model->FSaMIVBGetDataDocHDDocRef($aDataWhere);

            

            // Move Data DT To DTTemp , DTSPL To DTTemp
            $this->Invoicebill_model->FSxMIVBMoveDTToDTTemp($aDataWhere);

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
                $tViewPageAdd           = $this->load->view('document/Invoicebill/wInvoiceBillPageAdd',$aDataConfigViewAdd,true);
                $aReturnData = array(
                    'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
                    'tViewPageAdd'      => $tViewPageAdd,
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
    public function FSxCIVBEventAdd(){
        try {
            $aDataDocument  = $this->input->post();
            // print_r($aDataDocument);
            // exit();
            $tIVBAutoGenCode = (isset($aDataDocument['ocbIVBStaAutoGenCode'])) ? 1 : 0;
            $tIVBDocNo       = (isset($aDataDocument['oetIVBDocNo'])) ? $aDataDocument['oetIVBDocNo'] : '';
            $tIVBDocDate     = $aDataDocument['oetIVBDocDate'] . " " . $aDataDocument['oetIVBDocTime'];

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TACTPbHD',                    //ข้อมูลเอกสาร
                'tTableHDRef'       => 'TCNTPdtInvoiceBillHDDocRef',  //อ้างอิงเอกสาร
                'tTableDT'          => 'TACTPbDT',                    //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TACTPbHDSpl',                 //ส่งสินค้าไปหา - ผู้จำหน่าย
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdIVBBchCode'],
                'FTSplCode'         => $aDataDocument['ohdIVBSPLCode'],
                'FTXphDocNo'        => $tIVBDocNo,
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
                'FTBchCode'                 => $aDataDocument['ohdIVBBchCode'],
                'FTXphDocNo'                => $tIVBDocNo,
                'FTXphDocType'              => '2',
                'FTSplCode'                 => $aDataDocument['ohdIVBSPLCode'],
                'FTCstCode'                 => null,
                'FTPrdCode'                 => $aDataDocument['oetIVBPrdCode'],
                'FDXphDueDate'              => $aDataDocument['oetIVBPaidDate'],
                'FTXphCond'                 => $aDataDocument['otaIVBCondition'],
                'FCXphTotal'                => $aDataDocument['ohdIVBGrand'],
                'FCXphGndXC'                => 0,
                'FCXphGndXN'                => 0,
                'FCXphGndXX'                => 0,
                'FCXphGrand'                => $aDataDocument['ohdIVBGrand'],
                'FTXphGrandText'            => $aDataDocument['ohdIVBGrandText'],
                'FTXphRmk'                  => $aDataDocument['otaIVBFrmInfoOthRmk'],
                'FTXphCtrName'              => null,
                'FTDptCode'                 => null,
                'FTXphStaDoc'               => 1,
                'FTXphStaPaid'              => null,
                'FNXphStaDocAct'            => 1,
                'FNXphStaRef'               => null,
                'FNXphDocPrint'             => 1,
                'FDXphDocDate'               => $tIVBDocDate,
            );

            // Array Data SPL
            $aDataSPL = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdIVBBchCode'],
                'FTXphCtrName'      => $aDataDocument['oetIVBCtrName'],
                'FTXphDstPaid'      => $aDataDocument['ocmIVDstPaid'],
                'FTXphCshOrCrd'     => $aDataDocument['ocmIVPaymentType'],
                'FNXphCrTerm'       => $aDataDocument['oetIVCreditTerm'],
                'FTXphDocNo'        => $tIVBDocNo
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tIVBAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"      => $aTableAddUpdate['tTableHD'],
                    "tDocType"      => 0,
                    "tBchCode"      => $aDataDocument['ohdIVBBchCode'],
                    "tShpCode"      => "",
                    "tPosCode"      => "",
                    "dDocDate"      => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo']   = $tIVBDocNo;
            }

            // print_r($aDataWhere['FTXphDocNo']);
            // exit();

            // [Add] Document HD
            $this->Invoicebill_model->FSxMIVBAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document SPL
            $this->Invoicebill_model->FSxMIVBAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Invoicebill_model->FSxMIVBAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Invoicebill_model->FSaMIVBMoveDTTmpToDT($aDataWhere, $aTableAddUpdate ,$aDataMaster);

            
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
    public function FSxCIVBEventEdit(){
        try {
            $aDataDocument   = $this->input->post();
            $tIVBDocNo       = (isset($aDataDocument['oetIVBDocNo'])) ? $aDataDocument['oetIVBDocNo'] : '';
            $tIVBDocDate     = $aDataDocument['oetIVBDocDate'] . " " . $aDataDocument['oetIVBDocTime'];
            
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TACTPbHD',                    //ข้อมูลเอกสาร
                'tTableHDRef'       => 'TCNTPdtInvoiceBillHDDocRef',  //อ้างอิงเอกสาร
                'tTableDT'          => 'TACTPbDT',                    //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TACTPbHDSpl',                 //ส่งสินค้าไปหา - ผู้จำหน่าย
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdIVBBchCode'],
                'FTSplCode'         => $aDataDocument['ohdIVBSPLCode'],
                'FTXphDocNo'        => $tIVBDocNo,
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
                'FTBchCode'                 => $aDataDocument['ohdIVBBchCode'],
                'FTXphDocNo'                => $tIVBDocNo,
                'FTXphDocType'              => '2',
                'FTSplCode'                 => $aDataDocument['ohdIVBSPLCode'],
                'FTCstCode'                 => null,
                'FTPrdCode'                 => $aDataDocument['oetIVBPrdCode'],
                'FDXphDueDate'              => $aDataDocument['oetIVBPaidDate'],
                'FTXphCond'                 => $aDataDocument['otaIVBCondition'],
                'FCXphTotal'                => $aDataDocument['ohdIVBGrand'],
                'FCXphGndXC'                => 0,
                'FCXphGndXN'                => 0,
                'FCXphGndXX'                => 0,
                'FCXphGrand'                => $aDataDocument['ohdIVBGrand'],
                'FTXphGrandText'            => $aDataDocument['ohdIVBGrandText'],
                'FTXphRmk'                  => $aDataDocument['otaIVBFrmInfoOthRmk'],
                'FTXphCtrName'              => null,
                'FTDptCode'                 => null,
                'FTXphStaDoc'               => 1,
                'FTXphStaPaid'              => null,
                'FNXphStaDocAct'            => 1,
                'FNXphStaRef'               => null,
                'FNXphDocPrint'             => 1,
                'FDXphDocDate'               => $tIVBDocDate,
            );

            // Array Data SPL
            $aDataSPL = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdIVBBchCode'],
                'FTXphCtrName'      => $aDataDocument['oetIVBCtrName'],
                'FTXphDstPaid'      => $aDataDocument['ocmIVDstPaid'],
                'FTXphCshOrCrd'     => $aDataDocument['ocmIVPaymentType'],
                'FNXphCrTerm'       => $aDataDocument['oetIVCreditTerm'],
                'FTXphDocNo'        => $tIVBDocNo
            );

            $this->db->trans_begin();



            // [Add] Document HD
            $this->Invoicebill_model->FSxMIVBAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document SPL
            $this->Invoicebill_model->FSxMIVBAddUpdateSPLHD($aDataSPL, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Invoicebill_model->FSxMIVBAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Invoicebill_model->FSaMIVBMoveDTTmpToDT($aDataWhere, $aTableAddUpdate ,$aDataMaster);

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

    //เช็คว่ามีคลังเคลมเปลี่ยน คลังเคลมรับ และสินค้าทุกตัวมี SPL หรือยัง
    public function FSaCIVBEventCheckWahAndSPL(){
        $tBCHCode     = $this->input->post('tBCHCode');
        $tIVBDocNo    = $this->input->post('ptIVBDocNo');

        $aDataWhere = array(
            'tBCHCode'  => $tBCHCode,
            'tIVBDocNo' => $tIVBDocNo
        );

        //หาคลังก่อน
        $aResultFindWahouse = $this->Invoicebill_model->FSaMIVBFindWahouseINBranch($aDataWhere);
        if($aResultFindWahouse['rtCode'] == '800'){
            $aReturn = array(
                'nStaReturn'        => '800',
                'nTypeReturn'       => '1', //ตรวจสอบระดับคลัง
                'tStaMessg'         => 'ไม่พบคลังเคลมเปลี่ยน หรือคลังเคลมรับ'
            );
        }else{
            //หาว่าสินค้าทุกตัวระบุ SPL ครบเเล้ว
            $aResultFindSPL = $this->Invoicebill_model->FSaMIVBFindSPLInTemp($aDataWhere);
            if($aResultFindSPL['rtCode'] == '800'){
                $aReturn = array(
                    'nStaReturn'        => '800',
                    'nTypeReturn'       => '2', //ตรวจสอบระดับสินค้า
                    'tStaMessg'         => 'ไม่พบผู้จำหน่าย ในสินค้าที่จะส่งเคลม'
                );
            }else{

                //หาว่าสินค้ามีในคลังไหม
                $aConfig = $this->Invoicebill_model->FSxMIVBGetConfigAPI();
                if ($aConfig['rtCode'] == '800') {
                    $aReturn = array(
                        'nStaReturn'        => '800',
                        'nTypeReturn'       => '3', //ไม่พบ config
                        'tStaMessg'         => 'ไม่พบ config'
                    );
                    echo '<script>FSvCMNSetMsgErrorDialog("เกิดข้อผิดพลาด ไม่พบ API ในการเชื่อมต่อ")</script>';
                    exit;
                } else {
                    $this->tPublicAPI = $aConfig['raItems'][0]['FTUrlAddress'];
                }

                //API CheckSTK
                //วิ่งเข้ามาหารายการสินค้า ออกมาเป็น array
                $aGetItem   = $this->Invoicebill_model->FSaMIVBGetPDTInTempToArray($tIVBDocNo, $tBCHCode);
                $aToAPI     = $aGetItem;
                $tUrlApi    = $this->tPublicAPI . '/Stock/CheckStockPdts';
                $aParam     = $aToAPI;
                $aAPIKey    = array(
                    'tKey'      => 'X-API-KEY',
                    'tValue'    => '12345678-1111-1111-1111-123456789410'
                );
                $aResult    = FCNaHCallAPIBasic($tUrlApi, 'POST', $aParam, $aAPIKey);
                if ($aResult['rtCode'] == '001') {
                    if($aResult['raItems'][0]['rtStaPrcStock'] == 2){
                        $aReturn = array(
                            'nStaReturn'        => '800',
                            'nTypeReturn'       => '5', 
                            'tStaMessg'         => 'สินค้าไม่พอ'
                        );
                    }else{
                        $aReturn = array(
                            'nStaReturn'        => '1',
                            'nTypeReturn'       => '2', 
                            'tStaMessg'         => 'ผ่าน'
                        );
                    }
                } else {
                    $aReturn = array(
                        'nStaReturn'        => '1',
                        'nTypeReturn'       => '2', 
                        'tStaMessg'         => 'ผ่าน'
                    );
                }
            }
        }

        echo json_encode($aReturn);
    }

    //ลบข้อมูลเอกสาร
    public function FSoCIVBEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Invoicebill_model->FSnMIVBDelDocument($aDataMaster);
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
    public function FSoCIVBInvoiceBillEventCancel(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Invoicebill_model->FSnMIVBInvoiceBillEventCancel($aDataMaster);
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
    public function FSvCIVBCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/Invoicebill/refintdocument/wInvoiceBillRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCIVBCallRefIntDocDataTable(){

        $nPage                   = $this->input->post('nIVBRefIntPageCurrent');
        $tIVBRefIntBchCode       = $this->input->post('tIVBRefIntBchCode');
        $tIVBRefIntDocNo         = $this->input->post('tIVBRefIntDocNo');
        $tIVBRefIntDocDateFrm    = $this->input->post('tIVBRefIntDocDateFrm');
        $tIVBRefIntDocDateTo     = $this->input->post('tIVBRefIntDocDateTo');
        $tIVBRefIntStaDoc        = $this->input->post('tIVBRefIntStaDoc');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nIVBRefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tIVBRefIntBchCode'      => $tIVBRefIntBchCode,
            'tIVBRefIntDocNo'        => $tIVBRefIntDocNo,
            'tIVBRefIntDocDateFrm'   => $tIVBRefIntDocDateFrm,
            'tIVBRefIntDocDateTo'    => $tIVBRefIntDocDateTo,
            'tIVBRefIntStaDoc'       => $tIVBRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        $aDataParam = $this->Invoicebill_model->FSoMIVBCallRefIntDocDataTable($aDataCondition);

        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );

         $this->load->view('document/Invoicebill/refintdocument/wInvoiceBillRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCIVBCallRefIntDocDetailDataTable(){

        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        $aDataParam = $this->Invoicebill_model->FSoMIVBCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
          );
        $this->load->view('document/Invoicebill/refintdocument/wInvoiceBillRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp DT
    public function FSoCIVBCallRefIntDocInsertDTToTemp(){
        $tIVBDocNo          =  $this->input->post('tIVBDocNo');
        $tIVBFrmBchCode     =  $this->input->post('tIVBFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
       
        //เอาสินค้าลง Temp
        $aDataParam = array(
            'tIVBDocNo'         => $tIVBDocNo,
            'tIVBFrmBchCode'    => $tIVBFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'InvoiceBillStep1Point1'
        );
        $this->Invoicebill_model->FSoMIVBCallRefIntDocInsertDTToTemp($aDataParam);

        //Get ลูกค้าจากใบอ้างอิง
        $aFindCustomer  = $this->Invoicebill_model->FSoMIVBCallRefIntDocFindCstAndCar($aDataParam);
        $aReturnData    = array(
            'aFindCustomer' => $aFindCustomer
        );  
        echo json_encode($aReturnData);
    }

    //--------------------------------------- STEP 1 - POINT 1 --------------------------------------------//
    
    //โหลดข้อมูลสินค้า
    public function FSvCIVBStep1Point1Datatable(){
        $tIVBDocNo      = $this->input->post('ptIVBDocNo');

        $aData          = array(
            'tDocNo'    => $tIVBDocNo,
            'tDocKey'   => 'TACTPbDT'
        );
        $aList          = $this->Invoicebill_model->FSaMIVBListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicebill/step_form/wStep1Point1Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }


    //--------------------------------------- STEP 1 - POINT 2 --------------------------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCIVBStep1Point2Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tIVBDocNo      = $this->input->post('ptIVBDocNo');
        $tIVBStaApv     = $this->input->post('ptIVBStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tIVBDocNo,
            'tDocKey'   => 'InvoiceBillStep1Point1'
        );
        $aList          = $this->Invoicebill_model->FSaMIVBListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicebill/step_form/wStep1Point2Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    // ค้นหาเอกสารหลังจากเลือกผู้จำหน่าย
    public function FSoCIVBFindinghBill(){
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
                'tSessionID'            => $this->session->userdata('tSesSessionID')
            );
            $aStatusDoc = $this->Invoicebill_model->FSnMIVBInvoiceBillEventFindBill($aDataWhere);

            //หาชื่อผู้ติดต่อมา default 
            $aContactSPL = $this->Invoicebill_model->FSaMIVBFindContact($this->input->post('tSPLCode'));

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
    public function FSoCIVBFindinghBillPoint2(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tIVBDocNo      = $this->input->post('ptIVBDocNo');
        $tIVBStaApv     = $this->input->post('ptIVBStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tIVBDocNo,
            'tDocKey'   => 'TACTPbDTStep2',
            'tPdtCode'  => $this->input->post('tPdtCode')
        );
        // print_r($aData);
        $aList          = $this->Invoicebill_model->FSaMIVBListPoint2($aData);
        // print_r($aList);
        // exit();

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docInvoiceBill/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/Invoicebill/step_form/wStep1Point2Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //ค้นหาที่อยู่ SPL
    public function FSoCIVBFindingSplAddress(){
        $aReturnData = $this->Invoicebill_model->FSaMIVBGetSplAddress($this->input->post('tSPLCode'),$this->session->userdata("tLangID"));
        echo json_encode($aReturnData);
    }

    //ค้นหาที่อยู่ SPL
    public function FSoCIVBApproveEvent(){
        try{

            $tDocNo       = $this->input->post('tDocNo');
            $tBchCode     = $this->input->post('tBchCode');

            $aDataUpdate = array(
                'FTBchCode'         => $tBchCode,
                'FTXphDocNo'        => $tDocNo,
                'FTXphStaApv'       => 1,
                'FTXphApvCode'       => $this->session->userdata('tSesUsername')
            );
            $this->Invoicebill_model->FSaMIVBApproveDocument($aDataUpdate);
            $this->Invoicebill_model->FSaMIVBChangeStatusDTDocument($aDataUpdate);

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
}