<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Claimproduct_controller extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('document/claimproduct/Claimproduct_model');
    }

    public function index($nCLMBrowseType,$tCLMBrowseOption){

        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'    => $this->input->post('tDocNo'),
            'tBchCode'  => $this->input->post('tBchCode'),
            'tAgnCode'  => $this->input->post('tAgnCode'),
        );
        
        $aDataConfigView    = array(
            'nCLMBrowseType'        => $nCLMBrowseType,
            'tCLMBrowseOption'      => $tCLMBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('docClaim/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('docClaim/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave(),
            'aParams'               => $aParams
        );
        $this->load->view('document/claimproduct/wClaim',$aDataConfigView);
    }

    //List
    public function FSvCCLMPageList(){
        $this->load->view('document/claimproduct/wClaimSearchList');   
    }

    //ตารางข้อมูล
    public function FSvCCLMDatatable(){
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

        $aList      = $this->Claimproduct_model->FSaMCLMList($aData);
        $aGenTable  = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
            'aDataList'         => $aList,
            'nPage'             => $nPage,
            'nOptDecimalShow'   => $nOptDecimalShow
        );

        $tViewDataTable = $this->load->view('document/claimproduct/wClaimDataTable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //หน้าจอเพิ่มข้อมูล
    public function FSvCCLMPageAdd(){
        try{

            //ล้างค่าใน Temp
            $this->Claimproduct_model->FSaMCLMDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            $aDataConfigViewAdd = array(
                'nOptDecimalShow'   => $nOptDecimalShow,
                'aDataDocHD'        => array('rtCode'=>'99'),
                'aDataDocCST'       => array('rtCode'=>'99'),    
            );
            $tViewPageAdd       = $this->load->view('document/claimproduct/wClaimPageAdd',$aDataConfigViewAdd,true);
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
    public function FSvCCLMPageEdit(){
        try {
            $ptDocumentNumber = $this->input->post('ptCLMDocNo');

            // Clear Data In Doc DT Temp
            $this->Claimproduct_model->FSaMCLMDeletePDTInTmp();

            // Get Option Show Decimal
            $nOptDecimalShow    = FCNxHGetOptionDecimalShow();

            // Array Data Where Get (HD,HDCst)
            $aDataWhere = array(
                'FTPchDocNo'    => $ptDocumentNumber,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
            $this->db->trans_begin();

            // Get Data Document HD
            $aDataDocHD         = $this->Claimproduct_model->FSaMCLMGetDataDocHD($aDataWhere);

            // Get Data Document CST HD
            $aDataDocCSTHD      = $this->Claimproduct_model->FSaMCLMGetDataDocCSTHD($aDataWhere);

            // Get Data Document Ref
            $aDataDocHDDocRef   = $this->Claimproduct_model->FSaMCLMGetDataDocHDDocRef($aDataWhere);

            // Move Data DT To DTTemp , DTSPL To DTTemp
            $this->Claimproduct_model->FSxMCLMMoveDTToDTTemp($aDataWhere);

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
                    'aDataDocCST'       => $aDataDocCSTHD,
                    'aDataDocHDDocRef'  => $aDataDocHDDocRef
                );
                $tViewPageAdd           = $this->load->view('document/claimproduct/wClaimPageAdd',$aDataConfigViewAdd,true);
                $aReturnData = array(
                    'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
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
    public function FSxCCLMEventAdd(){
        try {
            $aDataDocument  = $this->input->post();
            $tCLMAutoGenCode = (isset($aDataDocument['ocbCLMStaAutoGenCode'])) ? 1 : 0;
            $tCLMDocNo       = (isset($aDataDocument['oetCLMDocNo'])) ? $aDataDocument['oetCLMDocNo'] : '';
            $tCLMDocDate     = $aDataDocument['oetCLMDocDate'] . " " . $aDataDocument['oetCLMDocTime'];

            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TCNTPdtClaimHD',        //ข้อมูลเอกสาร
                'tTableHDCst'       => 'TCNTPdtClaimHDCst',     //ข้อมูลลูกค้า
                'tTableHDRef'       => 'TCNTPdtClaimHDDocRef',  //อ้างอิงเอกสาร
                'tTableDT'          => 'TCNTPdtClaimDT',        //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TCNTPdtClaimDTSpl',     //ส่งสินค้าไปหา - ผู้จำหน่าย
                'tTableDTWrn'       => 'TCNTPdtClaimDTWrn',     //ผลเคลมจาก - ผู้จำหน่าย
                'tTableDTRcv'       => 'TCNTPdtClaimDTRcv',     //รับสินค้าจาก - ผู้จำหน่าย
                'tTableDTRet'       => 'TCNTPdtClaimDTRet',     //ส่งคืนให้ - ลูกค้า
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
            );

            if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 1){ //บันทึกเฉยๆ
                $nStaPrcDoc = 1;
            }else if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                $nStaPrcDoc = 1;
            }

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FDPchDocDate'      => $tCLMDocDate,
                'FTCstCode'         => $aDataDocument['oetCLMFrmCstCode'],
                'FTUsrcode'         => $this->session->userdata('tSesUsername'),
                'FTPchUsrApv'       => null,
                'FNPchDocPrint'     => 1,
                'FCXshCarMileage'   => ($aDataDocument['oetCLMCarMile'] == '' ) ? 0 : $aDataDocument['oetCLMCarMile'],
                'FTXshCarFuel'      => null,
                'FTRsnCode'         => null,
                'FTPchStaDoc'       => 1,
                'FTPchStaPrcDoc'    => $nStaPrcDoc, //สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว
                'FTPchRmk'          => $aDataDocument['otaCLMFrmInfoOthRmk'],
                'FTPchStaApv'       => null,
                'FTPchStaDocAct'    => 1,
            );

            // Array Data CST
            $aDataCST = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FTCarCode'         => $aDataDocument['oetCLMFrmCarCode'],
            );

            $this->db->trans_begin();

            // Check Auto GenCode Document
            if ($tCLMAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"      => $aTableAddUpdate['tTableHD'],
                    "tDocType"      => 1,
                    "tBchCode"      => $aDataDocument['ohdCLMBchCode'],
                    "tShpCode"      => "",
                    "tPosCode"      => "",
                    "dDocDate"      => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTPchDocNo']   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTPchDocNo']   = $tCLMDocNo;
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
            if($aDataDocument['oetCLMRefInt'] != ''){

                //1: อ้างอิงถึง(ภายใน) => ใบเคลม
                $aDataWhereDocRef_Type1 = array(
                    'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTPchDocNo'        => $aDataWhere['FTPchDocNo'],
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $aDataDocument['oetCLMRefInt'],
                    'FTXshRefKey'       => 'ABB',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefIntDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TCNTPdtClaimHDDocRef',$aDataWhereDocRef_Type1);
    
                //2:ถูกอ้างอิง(ภายใน) => ใบขาย
                $aDataWhereDocRef_Type2 = array(
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTXshDocNo'        => $aDataDocument['oetCLMRefInt'],
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $aDataWhere['FTPchDocNo'],
                    'FTXshRefKey'       => 'CLAIM',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefIntDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TPSTSalHDDocRef',$aDataWhereDocRef_Type2);
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายนอก ต้องกลับไปอัพเดท
            if($aDataDocument['oetCLMRefExt'] != '' ){

                //3: อ้างอิง ภายนอก
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTPchDocNo'        => $aDataWhere['FTPchDocNo'],
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetCLMRefExt'],
                    'FTXshRefKey'       => 'OTHER',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefExtDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TCNTPdtClaimHDDocRef',$aDataWhereDocRef_Type3);
            }

            // [Add] Document HD
            $this->Claimproduct_model->FSxMCLMAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document CST
            $this->Claimproduct_model->FSxMCLMAddUpdateCSTHD($aDataCST, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Claimproduct_model->FSxMCLMAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Claimproduct_model->FSaMCLMMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

            //ถ้าเป็นการกดบันทึก และยืนยันการเคลมจะต้องไปเอาคลังไปอัพเดท
            if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                $this->Claimproduct_model->FSaMCLMUpdateWahouseInTable($aDataWhere);
            }
            
            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();

                //ใบรับเข้าสินค้าจากลูกค้า
                if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                    $aMQParams = [
                        "queueName" => "CN_QGenDoc",
                        "params"    => [
                            'ptFunction'    => "TCNTPdtClaimHD",
                            'ptSource'      => 'AdaStoreBack',
                            'ptDest'        => 'MQReceivePrc',
                            'ptFilter'      => '',
                            'ptData'        => json_encode([
                                "ptBchCode"     => $aDataDocument['ohdCLMBchCode'],
                                "ptDocNo"       => $aDataWhere['FTPchDocNo'],
                                "ptDocType"     => '',
                                "ptUser"        => $this->session->userdata("tSesUsername"),
                            ])
                        ]
                    ];
                    // เชื่อม Rabbit MQ
                    FCNxCallRabbitMQ($aMQParams);

                    //ส่ง Noti
                    $tNotiID       = FCNtHNotiGetNotiIDByDocRef($tCLMDocNo);
                    $aMQParamsNoti = [
                        "queueName"     => "CN_SendToNoti",
                        "tVhostType"    => "NOT",
                        "params"        => [
                                        "oaTCNTNoti" => array(
                                            "FNNotID"               => $tNotiID,
                                            "FTNotCode"             => '00004',
                                            "FTNotKey"              => 'TCNTPdtClaimHD',
                                            "FTNotBchRef"            => $aDataDocument['ohdCLMBchCode'],
                                            "FTNotDocRef"           => $aDataWhere['FTPchDocNo'],
                                        ),
                                        "oaTCNTNoti_L" => array(
                                            0 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FNLngID"           => 1,
                                                "FTNotDesc1"        => 'เอกสารใบเคลม #'.$aDataWhere['FTPchDocNo'],
                                                "FTNotDesc2"        => 'รหัสสาขา '.$aDataDocument['ohdCLMBchCode'].' ทำการอนุมัติเอกสาร',
                                            ),
                                            1 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FNLngID"           => 2,
                                                "FTNotDesc1"        => 'Document Claim #'.$aDataWhere['FTPchDocNo'],
                                                "FTNotDesc2"        => 'Branch code '.$aDataDocument['ohdCLMBchCode'].' Approve document',
                                            )
                                        ),
                                        "oaTCNTNotiAct" => array(
                                            0 => array(  
                                                "FNNotID"           => $tNotiID,
                                                "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                                "FTNoaDesc"         => 'รหัสสาขา '.$aDataDocument['ohdCLMBchCode'].' ทำการอนุมัติเอกสาร',
                                                "FTNoaDocRef"       => $aDataWhere['FTPchDocNo'],
                                                "FNNoaUrlType"      =>  1,
                                                "FTNoaUrlRef"       => 'docClaim/2/0'
                                                ),
                                        ), 
                                        "oaTCNTNotiSpc" => array(
                                            0 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FTNotType"         => '1', //ต้นทาง
                                                "FTNotStaType"      => '1',
                                                "FTAgnCode"         => '',
                                                "FTAgnName"    => '',
                                                "FTBchCode"         => $aDataDocument['ohdCLMBchCode'],
                                                "FTBchName"         => $aDataDocument['ohdCLMBchCode'],
                                            ),
                                            1 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FTNotType"         => '2', //ปลายทาง
                                                "FTNotStaType"      => '1',
                                                "FTAgnCode"         => '',
                                                "FTAgnName"    => '',
                                                "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                                "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                                            ),
                                        ),
                                        "ptUser"                    => $this->session->userdata('tSesUsername'),
                        ]
                    ];
                    FCNxCallRabbitMQ($aMQParamsNoti);
                }

                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTPchDocNo'],
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
    public function FSxCCLMEventEdit(){
        try {
            $aDataDocument   = $this->input->post();
            $tCLMDocNo       = (isset($aDataDocument['oetCLMDocNo'])) ? $aDataDocument['oetCLMDocNo'] : '';
            $tCLMDocDate     = $aDataDocument['oetCLMDocDate'] . " " . $aDataDocument['oetCLMDocTime'];
            
            // Array Data Table Document
            $aTableAddUpdate = array(
                'tTableHD'          => 'TCNTPdtClaimHD',        //ข้อมูลเอกสาร
                'tTableHDCst'       => 'TCNTPdtClaimHDCst',     //ข้อมูลลูกค้า
                'tTableHDRef'       => 'TCNTPdtClaimHDDocRef',  //อ้างอิงเอกสาร
                'tTableDT'          => 'TCNTPdtClaimDT',        //รับสินค้าจาก - ลูกค้า
                'tTableDTSpl'       => 'TCNTPdtClaimDTSpl',     //ส่งสินค้าไปหา - ผู้จำหน่าย
                'tTableDTWrn'       => 'TCNTPdtClaimDTWrn',     //ผลเคลมจาก - ผู้จำหน่าย
                'tTableDTRcv'       => 'TCNTPdtClaimDTRcv',     //รับสินค้าจาก - ผู้จำหน่าย
                'tTableDTRet'       => 'TCNTPdtClaimDTRet',     //ส่งคืนให้ - ลูกค้า
            );

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
            );

            if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 1){ //บันทึกเฉยๆ
                $nStaPrcDoc = 1;
            }else if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                $nStaPrcDoc = 1;
            }

            // Array Data HD Master
            $aDataMaster = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FDPchDocDate'      => $tCLMDocDate,
                'FTCstCode'         => $aDataDocument['oetCLMFrmCstCode'],
                'FTUsrcode'         => $this->session->userdata('tSesUsername'),
                'FTPchUsrApv'       => null,
                'FNPchDocPrint'     => 1,
                'FCXshCarMileage'   => ($aDataDocument['oetCLMCarMile'] == '' ) ? 0 : $aDataDocument['oetCLMCarMile'],
                'FTXshCarFuel'      => null,
                'FTRsnCode'         => null,
                'FTPchStaDoc'       => 1,
                'FTPchStaPrcDoc'    => $nStaPrcDoc, //สถานะการทำงาน  1 : รออนุมัติ , 2 : รอส่งสินค้าไปยังผู้จำหน่าย , 3 : รอรับสินค้าจากผู้จำหน่าย , 4 : รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว , 5 : รอส่งสินค้าให้ลูกค้า , 6 : ส่งสินค้าบางส่วนให้ลูกค้าแล้ว , 7 : ปิดงานแล้ว
                'FTPchRmk'          => $aDataDocument['otaCLMFrmInfoOthRmk'],
                'FTPchStaApv'       => null,
                'FTPchStaDocAct'    => 1,
            );

            // Array Data CST
            $aDataCST = array(
                'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                'FTPchDocNo'        => $tCLMDocNo,
                'FTCarCode'         => $aDataDocument['oetCLMFrmCarCode'],
            );

            $this->db->trans_begin();

            // [Update] ถ้ามีเอกสารอ้างอิงภายใน ต้องกลับไปอัพเดท
            if($aDataDocument['oetCLMRefInt'] != '' || $aDataDocument['oetCLMRefIntOld'] != ''){

                //1: อ้างอิงถึง(ภายใน) => ใบเคลม
                $aDataWhereDocRef_Type1 = array(
                    'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTPchDocNo'        => $tCLMDocNo,
                    'FTXshRefType'      => 1,
                    'FTXshRefDocNo'     => $aDataDocument['oetCLMRefInt'],
                    'FTXshRefKey'       => 'ABB',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefIntDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TCNTPdtClaimHDDocRef',$aDataWhereDocRef_Type1);
    
                //2:ถูกอ้างอิง(ภายใน) => ใบขาย
                $aDataWhereDocRef_Type2 = array(
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTXshDocNo'        => $aDataDocument['oetCLMRefInt'],
                    'FTXshRefType'      => 2,
                    'FTXshRefDocNo'     => $tCLMDocNo,
                    'FTXshRefKey'       => 'CLAIM',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefIntDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefIntDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TPSTSalHDDocRef',$aDataWhereDocRef_Type2);
            }

            // [Update] ถ้ามีเอกสารอ้างอิงภายนอก ต้องกลับไปอัพเดท
            if($aDataDocument['oetCLMRefExt'] != '' ){

                //3: อ้างอิง ภายนอก
                $aDataWhereDocRef_Type3 = array(
                    'FTAgnCode'         => $this->session->userdata('tSesUsrAgnCode'),
                    'FTBchCode'         => $aDataDocument['ohdCLMBchCode'],
                    'FTPchDocNo'        => $tCLMDocNo,
                    'FTXshRefType'      => 3,
                    'FTXshRefDocNo'     => $aDataDocument['oetCLMRefExt'],
                    'FTXshRefKey'       => 'OTHER',
                    'FDXshRefDocDate'   => (!empty($aDataDocument['oetCLMRefExtDate'])) ? date('Y-m-d H:i:s', strtotime($aDataDocument['oetCLMRefExtDate'])) : NULL
                );
                $this->Claimproduct_model->FSxMCLMUpdateRef('TCNTPdtClaimHDDocRef',$aDataWhereDocRef_Type3);
            }

            // [Add] Document HD
            $this->Claimproduct_model->FSxMCLMAddUpdateHD($aDataMaster, $aDataWhere, $aTableAddUpdate);
         
            // [Add] Document CST
            $this->Claimproduct_model->FSxMCLMAddUpdateCSTHD($aDataCST, $aDataWhere, $aTableAddUpdate);

            // [Update] DocNo -> Temp
            $this->Claimproduct_model->FSxMCLMAddUpdateDocNoToTemp($aDataWhere);

            // [Add] Doc DTTemp -> DT
            $this->Claimproduct_model->FSaMCLMMoveDTTmpToDT($aDataWhere, $aTableAddUpdate);

            //ถ้าเป็นการกดบันทึก และยืนยันการเคลมจะต้องไปเอาคลังไปอัพเดท
            if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                $this->Claimproduct_model->FSaMCLMUpdateWahouseInTable($aDataWhere);
            }

            // Check Status Transection DB
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent'     => '900',
                    'tStaMessg'     => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();

                //ใบรับเข้าสินค้าจากลูกค้า
                if($aDataDocument['ohdCLMStaSaveOrSaveClaim'] == 2){ //บันทึกเและยืนยันการเคลม
                    $aMQParams = [
                        "queueName" => "CN_QGenDoc",
                        "params"    => [
                            'ptFunction'    => "TCNTPdtClaimHD",
                            'ptSource'      => 'AdaStoreBack',
                            'ptDest'        => 'MQReceivePrc',
                            'ptFilter'      => '',
                            'ptData'        => json_encode([
                                "ptBchCode"     => $aDataDocument['ohdCLMBchCode'],
                                "ptDocNo"       => $aDataWhere['FTPchDocNo'],
                                "ptDocType"     => '',
                                "ptUser"        => $this->session->userdata("tSesUsername"),
                            ])
                        ]
                    ];
                    // เชื่อม Rabbit MQ
                    FCNxCallRabbitMQ($aMQParams);

                    //ส่ง Noti
                    $tNotiID       = FCNtHNotiGetNotiIDByDocRef($tCLMDocNo);
                    $aMQParamsNoti = [
                        "queueName"     => "CN_SendToNoti",
                        "tVhostType"    => "NOT",
                        "params"        => [
                                        "oaTCNTNoti" => array(
                                            "FNNotID"               => $tNotiID,
                                            "FTNotCode"             => '00004',
                                            "FTNotKey"              => 'TCNTPdtClaimHD',
                                            "FTNotBchRef"            => $aDataDocument['ohdCLMBchCode'],
                                            "FTNotDocRef"           => $aDataWhere['FTPchDocNo'],
                                        ),
                                        "oaTCNTNoti_L" => array(
                                            0 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FNLngID"           => 1,
                                                "FTNotDesc1"        => 'เอกสารใบเคลม #'.$aDataWhere['FTPchDocNo'],
                                                "FTNotDesc2"        => 'รหัสสาขา '.$aDataDocument['ohdCLMBchCode'].' ทำการอนุมัติเอกสาร',
                                            ),
                                            1 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FNLngID"           => 2,
                                                "FTNotDesc1"        => 'Document Claim #'.$aDataWhere['FTPchDocNo'],
                                                "FTNotDesc2"        => 'Branch code '.$aDataDocument['ohdCLMBchCode'].' Approve document',
                                            )
                                        ),
                                        "oaTCNTNotiAct" => array(
                                            0 => array(  
                                                "FNNotID"           => $tNotiID,
                                                "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                                "FTNoaDesc"         => 'รหัสสาขา '.$aDataDocument['ohdCLMBchCode'].' ทำการอนุมัติเอกสาร',
                                                "FTNoaDocRef"       => $aDataWhere['FTPchDocNo'],
                                                "FNNoaUrlType"      =>  1,
                                                "FTNoaUrlRef"       => 'docClaim/2/0'
                                            ),
                                        ), 
                                        "oaTCNTNotiSpc" => array(
                                            0 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FTNotType"         => '1', //ต้นทาง
                                                "FTNotStaType"      => '1',
                                                "FTAgnCode"         => '',
                                                "FTAgnName"    => '',
                                                "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                                "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                                            ),
                                            1 => array(
                                                "FNNotID"           => $tNotiID,
                                                "FTNotType"         => '2', //ปลายทาง
                                                "FTNotStaType"      => '1',
                                                "FTAgnCode"         => '',
                                                "FTAgnName"    => '',
                                                "FTBchCode"         => $aDataDocument['ohdCLMBchCode'],
                                                "FTBchName"         => $aDataDocument['oetCLMBchName']
                                            ),
                                        ),
                                        "ptUser"                    => $this->session->userdata('tSesUsername'),
                        ]
                    ];
                    FCNxCallRabbitMQ($aMQParamsNoti);
                }

                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataWhere['FTPchDocNo'],
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
    public function FSaCCLMEventCheckWahAndSPL(){
        $tBCHCode     = $this->input->post('tBCHCode');
        $tCLMDocNo    = $this->input->post('ptCLMDocNo');

        $aDataWhere = array(
            'tBCHCode'  => $tBCHCode,
            'tCLMDocNo' => $tCLMDocNo
        );

        //หาคลังก่อน
        $aResultFindWahouse = $this->Claimproduct_model->FSaMCLMFindWahouseINBranch($aDataWhere);
        if($aResultFindWahouse['rtCode'] == '800'){
            $aReturn = array(
                'nStaReturn'        => '800',
                'nTypeReturn'       => '1', //ตรวจสอบระดับคลัง
                'tStaMessg'         => 'ไม่พบคลังเคลมเปลี่ยน หรือคลังเคลมรับ'
            );
        }else{
            //หาว่าสินค้าทุกตัวระบุ SPL ครบเเล้ว
            $aResultFindSPL = $this->Claimproduct_model->FSaMCLMFindSPLInTemp($aDataWhere);
            if($aResultFindSPL['rtCode'] == '800'){
                $aReturn = array(
                    'nStaReturn'        => '800',
                    'nTypeReturn'       => '2', //ตรวจสอบระดับสินค้า
                    'tStaMessg'         => 'ไม่พบผู้จำหน่าย ในสินค้าที่จะส่งเคลม'
                );
            }else{

                //หาว่าสินค้ามีในคลังไหม
                $aConfig = $this->Claimproduct_model->FSxMCLMGetConfigAPI();
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
                $aGetItem   = $this->Claimproduct_model->FSaMCLMGetPDTInTempToArray($tCLMDocNo, $tBCHCode);
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
    public function FSoCCLMEventDelete(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Claimproduct_model->FSnMCLMDelDocument($aDataMaster);
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
    public function FSoCCLMClaimEventCancel(){
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );
            $aResDelDoc = $this->Claimproduct_model->FSnMCLMClaimEventCancel($aDataMaster);
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
    public function FSvCCLMCallRefIntDoc(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tBCHName   = $this->input->post('tBCHName');

        $aDataParam = array(
            'tBCHCode' => $tBCHCode,
            'tBCHName' => $tBCHName
        );
        
        $this->load->view('document/claimproduct/refintdocument/wClaimRefDoc', $aDataParam);
    }

    // เลขที่เอกสารอ้างอิงมาแสดงในตาราง browse & Search
    public function FSoCCLMCallRefIntDocDataTable(){

        $nPage                   = $this->input->post('nCLMRefIntPageCurrent');
        $tCLMRefIntBchCode       = $this->input->post('tCLMRefIntBchCode');
        $tCLMRefIntDocNo         = $this->input->post('tCLMRefIntDocNo');
        $tCLMRefIntDocDateFrm    = $this->input->post('tCLMRefIntDocDateFrm');
        $tCLMRefIntDocDateTo     = $this->input->post('tCLMRefIntDocDateTo');
        $tCLMRefIntStaDoc        = $this->input->post('tCLMRefIntStaDoc');
  
        // Page Current 
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nCLMRefIntPageCurrent');
        }

        // Lang ภาษา
        $nLangEdit = $this->session->userdata("tLangEdit");
        

        $aDataParamFilter = array(
            'tCLMRefIntBchCode'      => $tCLMRefIntBchCode,
            'tCLMRefIntDocNo'        => $tCLMRefIntDocNo,
            'tCLMRefIntDocDateFrm'   => $tCLMRefIntDocDateFrm,
            'tCLMRefIntDocDateTo'    => $tCLMRefIntDocDateTo,
            'tCLMRefIntStaDoc'       => $tCLMRefIntStaDoc,
        );

        // Data Conditon Get Data Document
        $aDataCondition = array(
            'FNLngID'        => $nLangEdit,
            'nPage'          => $nPage,
            'nRow'           => 10,
            'aAdvanceSearch' => $aDataParamFilter
        );
        $aDataParam = $this->Claimproduct_model->FSoMCLMCallRefIntDocDataTable($aDataCondition);

        $aConfigView = array(
            'nPage'     => $nPage,
            'aDataList' => $aDataParam,
        );

         $this->load->view('document/claimproduct/refintdocument/wClaimRefDocDataTable', $aConfigView);
    }

    // เอารายการจากเอกสารอ้างอิงมาแสดงในตาราง browse
    public function FSoCCLMCallRefIntDocDetailDataTable(){

        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tBchCode           = $this->input->post('ptBchCode');
        $tDocNo             = $this->input->post('ptDocNo');
        $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
        $aDataCondition = array(
            'FNLngID'   => $nLangEdit,
            'tBchCode'  => $tBchCode,
            'tDocNo'    => $tDocNo
        );
        $aDataParam = $this->Claimproduct_model->FSoMCLMCallRefIntDocDTDataTable($aDataCondition);

        $aConfigView = array(
            'aDataList'         => $aDataParam,
            'nOptDecimalShow'   => $nOptDecimalShow
          );
        $this->load->view('document/claimproduct/refintdocument/wClaimRefDocDetailDataTable', $aConfigView);
    }

    // เอารายการที่เลือกจากเอกสารอ้างอิงภายในลงตาราง temp DT
    public function FSoCCLMCallRefIntDocInsertDTToTemp(){
        $tCLMDocNo          =  $this->input->post('tCLMDocNo');
        $tCLMFrmBchCode     =  $this->input->post('tCLMFrmBchCode');
        $tRefIntDocNo       =  $this->input->post('tRefIntDocNo');
        $tRefIntBchCode     =  $this->input->post('tRefIntBchCode');
        $aSeqNo             =  $this->input->post('aSeqNo');
       
        //เอาสินค้าลง Temp
        $aDataParam = array(
            'tCLMDocNo'         => $tCLMDocNo,
            'tCLMFrmBchCode'    => $tCLMFrmBchCode,
            'tRefIntDocNo'      => $tRefIntDocNo,
            'tRefIntBchCode'    => $tRefIntBchCode,
            'aSeqNo'            => $aSeqNo,
            'tDocKey'           => 'ClaimStep1Point1'
        );
        $this->Claimproduct_model->FSoMCLMCallRefIntDocInsertDTToTemp($aDataParam);

        //Get ลูกค้าจากใบอ้างอิง
        $aFindCustomer  = $this->Claimproduct_model->FSoMCLMCallRefIntDocFindCstAndCar($aDataParam);
        $aReturnData    = array(
            'aFindCustomer' => $aFindCustomer
        );  
        echo json_encode($aReturnData);
    }

    //--------------------------------------- STEP 1 - POINT 1 --------------------------------------------//
    
    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep1Point1Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $tCLMStaApv     = $this->input->post('ptCLMStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep1Point1Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //เพิ่มสินค้าลง temp
    public function FSoCCLMAddPdtInDTTmp(){
        try {
            $tCLMDocNo          = $this->input->post('tCLMDocNo');
            $tBCHCode           = $this->input->post('tBCHCode');
            $tSeqNo             = $this->input->post('tSeqNo');
            $aPdtData           = json_decode($this->input->post('oPdtData'));

            $this->db->trans_begin();

            //ลบข้อมูลก่อนเสมอ [สินค้าจะมีได้แค่ตัวเดียว];
            $this->Claimproduct_model->FSaMCLMDeletePDTInTmp();

            // ทำทีรายการ ตามรายการสินค้าที่เพิ่มเข้ามา
            for ($nI = 0; $nI < FCNnHSizeOf($aPdtData); $nI++) {
                $tItemPdtCode     = $aPdtData[$nI]->pnPdtCode;
                $tItemBarCode     = $aPdtData[$nI]->ptBarCode;
                $tItemPunCode     = $aPdtData[$nI]->ptPunCode;

                $aDataPdtParams = array(
                    'tDocNo'            => $tCLMDocNo,
                    'tBchCode'          => $tBCHCode,
                    'tPdtCode'          => $tItemPdtCode,
                    'tBarCode'          => $tItemBarCode,
                    'tPunCode'          => $tItemPunCode,
                    'nMaxSeqNo'         => $tSeqNo,
                    'nLngID'            => $this->session->userdata("tLangID"),
                    'tSessionID'        => $this->session->userdata('tSesSessionID'),
                    'tDocKey'           => 'ClaimStep1Point1'
                );

                // Data Master Pdt ข้อมูลรายการสินค้าที่เพิ่มเข้ามา
                $aDataPdtMaster = $this->Claimproduct_model->FSaMCLMGetDataPdt($aDataPdtParams);

                // นำรายการสินค้าเข้า DT Temp
                $this->Claimproduct_model->FSaMCLMInsertPDTToTemp($aDataPdtMaster,$aDataPdtParams);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'Error Insert Product Error Please Contact Admin.'
                );
            } else {
                $this->db->trans_commit();
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

    //ลบสินค้าในตาราง Temp [รายการเดียว]
    public function FSvCCLMRemovePdtInDTTmp(){
        $aDataWhere = array(
            'tDocNo'        => $this->input->post('tCLMDocNo'),
            'tPDTCode'      => $this->input->post('tPDTCode'),
            'nMaxSeqNo'     => $this->input->post('nSeqNo'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'tDocKey'       => 'ClaimStep1Point1'
        );
        $aResDel = $this->Claimproduct_model->FSnMCLMDelDTTmp($aDataWhere);
        echo json_encode($aResDel);
    }

    //Edit Inline สินค้า (จำนวน)
    public function FSoCCLMUpdateQTYDTTemp() {
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep1Point1'
            );

            $aDataUpdateDT = array(
                'FCXtdQty'      => $this->input->post('nQty')
            );

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTemp($aDataUpdateDT, $aDataWhere);

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

    //--------------------------------------- STEP 1 - POINT 2 --------------------------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep1Point2Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $tCLMStaApv     = $this->input->post('ptCLMStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep1Point2Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //Edit Inline สินค้า (สถานะเคลม , หมายเหตุ)
    public function FSoCCLMUpdateStaAndRmk() {
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');
            $tTypeUpdate     = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep1Point1'
            );

            if($tTypeUpdate == 'StatusClaim'){
                $aDataUpdateDT = array(
                    'FTPcdStaClaim'      => $this->input->post('tValueUpdate')
                );
            }else{
                $aDataUpdateDT = array(
                    'FTPcdRmk'           => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStaAndRmk($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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
  
    //--------------------------------------- STEP 1 - POINT 3 --------------------------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep1Point3Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $tCLMStaApv     = $this->input->post('ptCLMStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);
        $aItemBySPL     = $this->Claimproduct_model->FSaMCLMPDTFindBySPL($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
            'aDataList'         => $aList,
            'aItemBySPL'        => $aItemBySPL
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep1Point3Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //Edit Inline สินค้า (หมายเหตุ , ผู้จำหน่าย , วันที่แจ้ง)
    public function FSoCCLMUpdateSPLAndDate(){
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');
            $tTypeUpdate     = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep1Point1'
            );

            if($tTypeUpdate == 'RmkClaim'){
                $aDataUpdateDT = array(
                    'FTPcdRmk'           => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'DateClaim'){
                $aDataUpdateDT = array(
                    'FDPcdDateReq'       => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'SPLClaim'){
                $aDataUpdateDT = array(
                    'FTSplCode'         => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStaAndRmk($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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

    //--------------------------------------- STEP 1 - POINT 4 --------------------------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep1Point4Datatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $tCLMStaApv     = $this->input->post('ptCLMStaApv');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        $aGenTable      = array(
            'aAlwEvent'         => FCNaHCheckAlwFunc('docClaim/0/0'),
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep1Point4Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //Edit Inline สินค้า (จำนวนที่ขอยืม , สินค้าที่ขอยืม)
    public function FSoCCLMUpdatePickPDT(){
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');
            $tTypeUpdate     = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep1Point1'
            );

            if($tTypeUpdate == 'PDTClaim'){
                $aDataUpdateDT = array(
                    'FTPcdPdtPick'        => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'QTYPICKClaim'){
                $aDataUpdateDT = array(
                    'FCPcdQtyPick'       => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStaAndRmk($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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

    //--------------------------------------- STEP 1 - Result Datatable (สรุป) -----------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep1ResultDatatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        $aGenTable      = array(
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep1ResultDatatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //--------------------------------------- STEP 2 - Result Datatable (สรุป) -----------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep2ResultDatatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1'
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        $aGenTable      = array(
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep2ResultDatatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //Edit Inline สินค้า (จำนวนที่ขอยืม , สินค้าที่ขอยืม)
    public function FSoCCLMStep2Update(){
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');
            $tTypeUpdate     = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep1Point1'
            );

            if($tTypeUpdate == 'DateGetClaim'){ //วันที่เข้ามารับของ
                $aDataUpdateDT = array(
                    'FDPcdSplGetDate'        => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'RmkGet'){ //หมายเหตุ
                $aDataUpdateDT = array(
                    'FTPcdSplRmk'           => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'UserGet'){ //ชื่อคนมารับของ
                $aDataUpdateDT = array(
                    'FTPctSplStaff'         => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStaAndRmk($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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

    //สร้างเอกสารใบเบิกออก
    public function FSoCCLMStep2CreateDoc(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');

        //เอาไว้ทดสอบเฉยๆ ของจริงต้องโยนเข้า MQ
        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
        );
        $this->Claimproduct_model->FSaMCLMStep2UpdatePrcDoc($aData);

        //ใบเบิกออกหาผู้จำหน่าย
        $aMQParams = [
            "queueName" => "CN_QGenDoc",
            "params"    => [
                'ptFunction'    => "TCNTPdtClaimHD",
                'ptSource'      => 'AdaStoreBack',
                'ptDest'        => 'MQReceivePrc',
                'ptFilter'      => '',
                'ptData'        => json_encode([
                    "ptBchCode"     => $tBCHCode,
                    "ptDocNo"       => $tCLMDocNo,
                    "ptDocType"     => '',
                    "ptUser"        => $this->session->userdata("tSesUsername"),
                ])
            ]
        ];
        // เชื่อม Rabbit MQ
        FCNxCallRabbitMQ($aMQParams);
    }

    //--------------------------------------- STEP 3 - Result Datatable (สรุป) -----------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep3ResultDatatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1',
            'tDocKey2'  => 'ClaimStep3',
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListTableStep3($aData);

        $aGenTable      = array(
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep3ResultDatatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //โหลดข้อมูล HD ของ step3
    public function FSvCCLMStep3SaveAndGet(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $nSeqPDT        = $this->input->post('nSeqPDT');
        $tTypePage      = $this->input->post('tTypePage');

        //ข้อมูลส่วนรายละเอียด
        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'nSeqPDT'   => $nSeqPDT,
            'tDocKey'   => 'ClaimStep1Point1',
            'tDocKey2'  => 'ClaimStep3',
        );

        //ถ้าเป็นการดูประวัติรับเข้า จะมีการไปเอาเลขที่เอกสารใบรับเข้าที่ gen มาไว้ใน Temp
        if($tTypePage == 'historyget'){
            $this->Claimproduct_model->FSaMCLMUpdateDocTWIInTemp($aData);
        }

        $aList          = $this->Claimproduct_model->FSaMCLMGetItemClaimBySeq($aData);

        //ข้อมูลส่วนรายละเอียดประวัติ
        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'nSeqPDT'   => $nSeqPDT,
            'tDocKey'   => 'ClaimStep3'
        );
        $aListDT        = $this->Claimproduct_model->FSaMCLMListStep1Point1($aData);

        //ส่งข้อมูลไปหน้า View
        $aGenTable      = array(
            'aDataList'     => $aListDT,
            'tTypePage'     => $tTypePage
        );

        switch ($tTypePage) {
            case 'historysave':     //ประวัติการบันทึก
            case 'historyget':      //ประวัติการรับเข้า
                $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep3ResultHistory', $aGenTable ,true);
                break;
            case 'saveget':         //รับสินค้าเข้าระบบ
                $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep3ResultGet', $aGenTable ,true);
                break;
            case 'saveclaim':       //บันทึกผลเคลม
                $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep3ResultSave', $aGenTable ,true);
                break;
            default:
                break;
        }

        $aReturnData = array(
            'aList'             => $aList,
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
        );
        echo json_encode($aReturnData);
    }

    //โหลดข้อมูล 
    public function FSvCCLMStep3Table(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $nSeqPDT        = $this->input->post('nSeqPDT');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep3',
            'nSeqPDT'   => $nSeqPDT
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListStep3($aData);

        $aGenTable      = array(
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep3ResultSaveDatatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //เพิ่มข้อมูล
    public function FSvCCLMStep3Save(){
        $tBCHCode           = $this->input->post('tBCHCode');
        $tCLMDocNo          = $this->input->post('tCLMDocNo');
        $nStep3Percent      = $this->input->post('nStep3Percent');
        $nStep3Value        = $this->input->post('nStep3Value');
        $nStep3Get          = $this->input->post('nStep3Get');
        $nStep3Remark       = $this->input->post('nStep3Remark');
        $tPDTCode           = $this->input->post('tPDTCode');
        $tDateSave          = $this->input->post('tDateSave');
        $tDocNoSendClaim    = $this->input->post('tDocNoSendClaim');
        $tDocNoExClaim      = $this->input->post('tDocNoExClaim');
        $nSeq               = $this->input->post('nSeqNo');
        $tSPLCode           = $this->input->post('tSPLCode');

        $aDataPdtParams = array(
            'tDocNo'            => $tCLMDocNo,
            'FNPcdSeqNo'        => $nSeq,
            'tBchCode'          => $tBCHCode,
            'tPDTCode'          => $tPDTCode,
            'FTSplCode'         => $tSPLCode,
            'FTPcdRefTwo'       => $tDocNoSendClaim,    //เลขที่ใบส่งเคลม (เบิกออก)
            'FTWrnRefDoc'       => $tDocNoExClaim,      //เลขที่อ้างอิงผลเคลม (ใบรับประกันจากผู้จำหน่าย) เอกสารภายนอก
            'FDWrnDate'         => $tDateSave,
            'FCWrnPercent'      => $nStep3Percent,
            'FCWrnDNCNAmt'      => ($nStep3Value == '' ) ? '0' : $nStep3Value,
            'FCWrnPdtQty'       => $nStep3Get,
            'FTWrnRmk'          => $nStep3Remark,
            'FTWrnUsrCode'      => $this->session->userdata('tSesUsername'),
            'nLngID'            => $this->session->userdata("tLangID"),
            'tSessionID'        => $this->session->userdata('tSesSessionID'),
            'tDocKey'           => 'ClaimStep3'
        );

        // นำรายการสินค้าเข้า DT Temp
        $this->Claimproduct_model->FSaMCLMInsertPDTToTempStep3($aDataPdtParams);
    }

    //ลบสินค้าในตาราง Temp [รายการเดียว]
    public function FSxCCLMRemovePdtInDTStep3Tmp(){
        $aDataWhere = array(
            'tDocNo'        => $this->input->post('tCLMDocNo'),
            'tPDTCode'      => $this->input->post('tPDTCode'),
            'nMaxSeqNo'     => $this->input->post('nSeqNo'),
            'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            'tDocKey'       => 'ClaimStep3'
        );
        $this->Claimproduct_model->FSnMCLMDelDTTmp($aDataWhere);
    }

    //อัพเดทข้อมูล (สินค้าที่รับ , จำนวน)
    public function FSxCCLMStep3Update(){
        try {
            $tDocNo          = $this->input->post('tCLMDocNo');
            $nSeq            = $this->input->post('nSeq');
            $tTypeUpdate     = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nSeq'          => $nSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep3'
            );

            if($tTypeUpdate == 'Step3PDT'){
                $aDataUpdateDT = array(
                    'FTRcvPdtCode'      => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'Step3QTY'){
                $aDataUpdateDT = array(
                    'FCRcvPdtQty'       => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStep3($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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

    //บันทึกข้อมูลลงใน DT
    public function FSxCCLMStep3SaveInDB(){
        $tBCHCode   = $this->input->post('tBCHCode');
        $tCLMDocNo  = $this->input->post('tCLMDocNo');
        $tTypePage  = $this->input->post('tTypePage');
        $nSeqNo     = $this->input->post('nSeqNo');
        $tSPLCode   = $this->input->post('tSPLCode');

        // เอาสินค้าที่ บันทึกผลเคลม move ไปตารางจริง
        $aDataWhere          = array(
            'tBCHCode'      => $tBCHCode,
            'tDocNo'        => $tCLMDocNo,
            'tDocKey'       => 'ClaimStep3',
            'tTypePage'     => $tTypePage,
            'nSeqNo'        => $nSeqNo,
            'tSPLCode'      => $tSPLCode,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );
        $this->Claimproduct_model->FSaMCLMMoveTempToDTInSaveStep3($aDataWhere);


        //ใบเบิกออกหาผู้จำหน่าย
        if($tTypePage == 'saveget'){
            $aMQParams = [
                "queueName" => "CN_QGenDoc",
                "params"    => [
                    'ptFunction'    => "TCNTPdtClaimHD",
                    'ptSource'      => 'AdaStoreBack',
                    'ptDest'        => 'MQReceivePrc',
                    'ptFilter'      => '',
                    'ptData'        => json_encode([
                        "ptBchCode"     => $tBCHCode,
                        "ptDocNo"       => $tCLMDocNo,
                        "ptDocType"     => '',
                        "ptUser"        => $this->session->userdata("tSesUsername"),
                    ])
                ]
            ];
            // เชื่อม Rabbit MQ
            FCNxCallRabbitMQ($aMQParams);

            $aDataGetDataHD     =   $this->Claimproduct_model->FSaMCLMGetDataDocHD(array(
                'FTPchDocNo'    => $tBCHCode,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            ));
            if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            //ส่ง Noti
            $tNotiID       = FCNtHNotiGetNotiIDByDocRef($tCLMDocNo);
            $aMQParamsNoti = [
                "queueName"     => "CN_SendToNoti",
                "tVhostType"    => "NOT",
                "params"        => [
                                "oaTCNTNoti" => array(
                                    "FNNotID"               => $tNotiID,
                                    "FTNotCode"             => '00004',
                                    "FTNotKey"              => 'TCNTPdtClaimHD',
                                    "FTNotBchRef"            => $tBCHCode,
                                    "FTNotDocRef"           => $tCLMDocNo,
                                ),
                                "oaTCNTNoti_L" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 1,
                                        "FTNotDesc1"        => 'เอกสารใบเคลม #'.$tCLMDocNo,
                                        "FTNotDesc2"        => 'รหัสสาขา '.$tBCHCode.' รับสินค้าจากผู้จำหน่าย',
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 2,
                                        "FTNotDesc1"        => 'Document Claim #'.$tCLMDocNo,
                                        "FTNotDesc2"        => 'Branch code '.$tBCHCode.' Recive product',
                                    )
                                ),
                                "oaTCNTNotiAct" => array(
                                    0 => array(  
                                        "FNNotID"           => $tNotiID,
                                        "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                        "FTNoaDesc"         => 'รหัสสาขา '.$tBCHCode.' รับสินค้าจากผู้จำหน่าย',
                                        "FTNoaDocRef"       => $tCLMDocNo,
                                        "FNNoaUrlType"      =>  1,
                                        "FTNoaUrlRef"       => 'docClaim/2/0',
                                    ),
                                ), 
                                "oaTCNTNotiSpc" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '1', //ต้นทาง
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"         => $aDataGetDataHD['raItems']['FTBchCode'],
                                        "FTBchName"         => $aDataGetDataHD['raItems']['FTBchName'],
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '2', //ปลายทาง
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                        "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                                    ),
                                ),
                                "ptUser"                    => $this->session->userdata('tSesUsername'),
                ]
            ];
            FCNxCallRabbitMQ($aMQParamsNoti);
          }
        }else{
            $aDataGetDataHD     =   $this->Claimproduct_model->FSaMCLMGetDataDocHD(array(
                'FTPchDocNo'    => $tBCHCode,
                'FNLngID'       => $this->session->userdata("tLangEdit")
            ));
            if(isset($aDataGetDataHD['rtCode']) && $aDataGetDataHD['rtCode'] == 1){
            //ส่ง Noti - บันทึกผลเคลม
            $tNotiID       = FCNtHNotiGetNotiIDByDocRef($tCLMDocNo);
            $aMQParamsNoti = [
                "queueName"     => "CN_SendToNoti",
                "tVhostType"    => "NOT",
                "params"        => [
                                "oaTCNTNoti" => array(
                                    "FNNotID"               => $tNotiID,
                                    "FTNotCode"             => '00004',
                                    "FTNotKey"              => 'TCNTPdtClaimHD',
                                    "FTNotBchRef"            => $tBCHCode,
                                    "FTNotDocRef"           => $tCLMDocNo,
                                ),
                                "oaTCNTNoti_L" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 1,
                                        "FTNotDesc1"        => 'เอกสารใบเคลม #'.$tCLMDocNo,
                                        "FTNotDesc2"        => 'รหัสสาขา '.$tBCHCode.' บันทึกผลเคลม',
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FNLngID"           => 2,
                                        "FTNotDesc1"        => 'Document Claim #'.$tCLMDocNo,
                                        "FTNotDesc2"        => 'Branch code '.$tBCHCode.' Save document',
                                    )
                                ),
                                "oaTCNTNotiAct" => array(
                                    0 => array(  
                                        "FNNotID"           => $tNotiID,
                                        "FDNoaDateInsert"   => date('Y-m-d H:i:s'),
                                        "FTNoaDesc"         => 'รหัสสาขา '.$tBCHCode.' บันทึกผลเคลม',
                                        "FTNoaDocRef"       => $tCLMDocNo,
                                        "FNNoaUrlType"      =>  1,
                                        "FTNoaUrlRef"       => 'docClaim/2/0',
                                    ),
                                ), 
                                "oaTCNTNotiSpc" => array(
                                    0 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '1', //ต้นทาง
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"         => $aDataGetDataHD['raItems']['FTBchCode'],
                                        "FTBchName"         => $aDataGetDataHD['raItems']['FTBchName'],
                                    ),
                                    1 => array(
                                        "FNNotID"           => $tNotiID,
                                        "FTNotType"         => '2', //ปลายทาง
                                        "FTNotStaType"      => '1',
                                        "FTAgnCode"         => '',
                                        "FTAgnName"    => '',
                                        "FTBchCode"         => $this->session->userdata("tUsrBchHQCode"),
                                        "FTBchName"         => $this->session->userdata("tUsrBchHQName"),
                                    ),
                                ),
                                "ptUser"                    => $this->session->userdata('tSesUsername'),
                ]
            ];
            FCNxCallRabbitMQ($aMQParamsNoti);
          }
        }
    }

    //--------------------------------------- STEP 4 - Result Datatable (สรุป) -----------------------------//

    //โหลดข้อมูลสินค้า
    public function FSvCCLMStep4ResultDatatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');

        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'tDocKey'   => 'ClaimStep1Point1',
            'tDocKey2'  => 'ClaimStep3',
        );
        $aList          = $this->Claimproduct_model->FSaMCLMListTableStep4($aData);

        $aGenTable      = array(
            'aDataList'         => $aList
        );
        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep4Datatable', $aGenTable ,true);
        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
            'tStaMessg'         => 'Success'
        );
        echo json_encode($aReturnData);
    }

    //โหลดข้อมูล การส่งสินค้าให้ลูกค้า
    public function FSxCCLMStep4ReturnDatatable(){
        $tBCHCode       = $this->input->post('tBCHCode');
        $tCLMDocNo      = $this->input->post('ptCLMDocNo');
        $nSeqPDT        = $this->input->post('nSeqPDT');
        $tTypePage      = $this->input->post('tTypePage');

        //ข้อมูลส่วนรายละเอียดประวัติ
        $aData          = array(
            'tBCHCode'  => $tBCHCode,
            'tDocNo'    => $tCLMDocNo,
            'nSeqPDT'   => $nSeqPDT,
            'tDocKey'   => 'ClaimStep1Point1',
            'tDocKey2'  => 'ClaimStep3',
        );
        $aListDT        = $this->Claimproduct_model->FSaMCLMGetItemClaimBySeqStep4($aData);

        //ส่งข้อมูลไปหน้า View
        $aGenTable      = array(
            'aDataList'     => $aListDT,
            'nSeqPDT'       => $nSeqPDT,
            'tTypePage'     => $tTypePage
        );

        $tViewDataTable = $this->load->view('document/claimproduct/step_form/wStep4SaveAndHistoryDatatable', $aGenTable ,true);

        $aReturnData = array(
            'tViewDataTable'    => $tViewDataTable,
            'nStaEvent'         => '1',
        );
        echo json_encode($aReturnData);
    }

    //อัพเดทข้อมูล (หมายเหตุ , วันที่ส่งคืนให้ลูกค้า)
    public function FSoCCLMStep4Update(){
        try {
            $tDocNo             = $this->input->post('tCLMDocNo');
            $nRcvSeq            = $this->input->post('pnRcvSeq');
            $nWrnSeq            = $this->input->post('pnWrnSeq');
            $nPcdSeq            = $this->input->post('pnPcdSeq');
            $tTypeUpdate        = $this->input->post('tTypeUpdate');

            $aDataWhere = array(
                'tDocNo'        => $tDocNo,
                'nRcvSeq'       => $nRcvSeq,
                'nWrnSeq'       => $nWrnSeq,
                'nPcdSeq'       => $nPcdSeq,
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tDocKey'       => 'ClaimStep3'
            );

            if($tTypeUpdate == 'DateStep4'){
                $aDataUpdateDT = array(
                    'FDRetDate'      => $this->input->post('tValueUpdate')
                );
            }else if($tTypeUpdate == 'RmkStep4'){
                $aDataUpdateDT = array(
                    'FTRetRmk'       => $this->input->post('tValueUpdate')
                );
            }

            $this->db->trans_begin();

            $this->Claimproduct_model->FSaMCLMUpdateInlineDTTempStep4($aDataUpdateDT, $aDataWhere, $tTypeUpdate);

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

    //บันทึกข้อมูล
    public function FSxCCLMStep4Save(){
        $tBCHCode           = $this->input->post('tBCHCode');             
        $tCLMDocNo          = $this->input->post('tCLMDocNo');         
        $nCreateCNDN        = $this->input->post('nCreateCNDN');
        $tCSTCode           = $this->input->post('tCSTCode');  
        $nSeq               = $this->input->post('nSeq'); 

        // เอาสินค้าที่ บันทึกผลเคลม move ไปตารางจริง
        $aDataWhere          = array(
            'tBCHCode'      => $tBCHCode,
            'tDocNo'        => $tCLMDocNo,
            'nSeq'          => $nSeq,
            'tDocKey'       => 'ClaimStep3',
            'tCSTCode'      => $tCSTCode,
            'nCreateCNDN'   => $nCreateCNDN,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );
        $this->Claimproduct_model->FSaMCLMMoveTempToDTInSaveStep4($aDataWhere);

        
        $aMQParams = [
            "queueName" => "CN_QGenDoc",
            "params"    => [
                'ptFunction'    => "TCNTPdtClaimHD",
                'ptSource'      => 'AdaStoreBack',
                'ptDest'        => 'MQReceivePrc',
                'ptFilter'      => '',
                'ptData'        => json_encode([
                    "ptBchCode"     => $tBCHCode,
                    "ptDocNo"       => $tCLMDocNo,
                    "ptDocType"     => '',
                    "ptUser"        => $this->session->userdata("tSesUsername"),
                ])
            ]
        ];
        // เชื่อม Rabbit MQ
        FCNxCallRabbitMQ($aMQParams);
    }

    //Functionality :  ค้นหาข้อมูลลูกค้า
    //Parameters : Ajax jJobRequestStep1()
    //Creator : 31/01/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSaCCLMFindCstAddress()
    {
        $poItem     = json_decode($this->input->post('poItem'));
        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCstCode'  => $poItem[5],
            'nLangEdit' => $nLangEdit,
        ];
        $aDataReturn    = [
            'aDataCstAddr'  => $this->Claimproduct_model->FSaMCLMGetDataCustomerAddr($aDataWhere),
        ];
        echo json_encode($aDataReturn);
    }


}