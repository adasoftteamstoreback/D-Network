<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prerepairresult_controller extends MX_Controller
{
    public function __construct()
    {
        $this->load->model('document/Prerepairresult/Prerepairresult_model');
        parent::__construct();
    }

    public $tRouteMenu  = 'docPreRepairResult/0/0';

    public function index($ptRoute, $ptDocCode) {
        //แสดงส่วนหัวหน้าใบประเมินความพึงพอใจของลูกค้า ($ptRoute คือ param ของหน้าว่าเข้ามาแบบ insert หรือ view, $ptDocCode เลขที่เอกสารที่แนบมา
        //รองรับการเข้ามาแบบ Noti
        $aParams = array(
            'tDocNo'        => $this->input->post('tDocNo'),
            'tBchCode'      => $this->input->post('tBchCode'),
            'tAgnCode'      => $this->input->post('tAgnCode'),
            'tCheckJump'    => $this->input->get('ptTypeJump'),
        );
        $aDataConfigView    = [
            'aAlwEvent' => FCNaHCheckAlwFunc($this->tRouteMenu),
            'vBtnSave'  => FCNaHBtnSaveActiveHTML($this->tRouteMenu),
            'aParams'   => $aParams,
        ];
        $this->load->view('document/Prerepairresult/wPreRepairResult', $aDataConfigView);
    }

    public function FSxCPrePageList(){
        $this->load->view('document/Prerepairresult/wPreRepairResultPageList');
    }

    // Functionality : แสดงตารางในหน้า ใบบันทึกผลตรวจเช็คสภาพรถ
    // Parameters : Ajax and Function Parameter
    // Creator : 07/10/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCPreDetailDataTable(){
        try {
            $aDataWhere = array(
                'FTXshDocNo'    => $this->input->post('tDocNo'),
                'tRefDoc'       => $this->input->post('tRefDoc'),
                'FTUsrSess'     => $this->session->userdata("tSesSessionID"),
                'nCondition'    => $this->input->post("nCondition"),
                'tType'         => $this->input->post("Type")
            );
            $aDataAnwser    = $this->Prerepairresult_model->FSaMPreGetAnswerDetail($aDataWhere);
            $aDataFinal     = array(
                'tReturn' => 1
            );
            $aDataAll = array(
                'aDataAnwser'       => $aDataAnwser,
                'aDataGetDetail'    => $aDataFinal,
                'nStadoc'           => $this->input->post("nStadoc")
            );
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        $this->load->view('document/Prerepairresult/wPreRepairResultAdvTable', $aDataAll);
    }

    // Functionality : แก้ไขข้อมูล Tmp กรณีที่กด ปกติ
    // Parameters : Ajax and Function Parameter
    // Creator : 08/10/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCPreDetailEditDataTable()
    {
        try {
            $aDataWhere = array(
                'FTPdtCode' => $this->input->post('tPdtCode'),
                'FTPdtCodeSub' => $this->input->post('tPdtCodeSub'),
                'FTUsrSess' => $this->session->userdata("tSesSessionID")
            );

            $aDataAnwser = $this->Prerepairresult_model->FSaMPreEditNormal($aDataWhere);
            $aDataFinal = array(
                'tReturn' => 1
            );

            $aDataAll = array(
                'aDataAnwser'           => $aDataAnwser,
                'aDataGetDetail'    => $aDataFinal
            );
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
    }

    // Functionality : แก้ไขข้อมูล Tmp
    // Parameters : Ajax and Function Parameter
    // Creator : 08/10/2021 Off
    // LastUpdate: -
    // Return : String View
    // Return Type : View
    public function FSvCPreDetailEditDataTableInLine()
    {
        try {
            $aDataWhere = array(
                'FTPdtCode' => $this->input->post('tPdtCode'),
                'FTPdtCodeSub' => $this->input->post('tPdtCodeSub'),
                'FTUsrSess' => $this->session->userdata("tSesSessionID"),
                'FNPdtSrvSeq' => $this->input->post("FNPdtSrvSeq"),
                'FTXsdAnsValue' => $this->input->post("FTXsdAnsValue")
            );

            $aDataAnwser = $this->Prerepairresult_model->FSaMPreEditInLine($aDataWhere);
            $aDataFinal = array(
                'tReturn' => 1
            );

            $aDataAll = array(
                'aDataAnwser'       => $aDataAnwser,
                'aDataGetDetail'    => $aDataFinal
            );
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
    }

    // แสดงตารางในหน้า List
    public function FSxCPreDatatable()
    {
        try {
            $aAdvanceSearch = $this->input->post('oAdvanceSearch');
            $nPage = $this->input->post('nPageCurrent');
            $aAlwEvent = FCNaHCheckAlwFunc($this->tRouteMenu);

            // Page Current
            if ($nPage == '' || $nPage == null) {
                $nPage = 1;
            } else {
                $nPage = $this->input->post('nPageCurrent');
            }

            // Lang ภาษา
            $nLangEdit = $this->session->userdata("tLangEdit");

            // Data Conditon Get Data Document
            $aDataCondition = array(
                'FNLngID' => $nLangEdit,
                'nPage' => $nPage,
                'nRow' => 10,
                'aDatSessionUserLogIn' => $this->session->userdata("tSesUsrInfo"),
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aDataList = $this->Prerepairresult_model->FSaMPreGetDataTableList($aDataCondition);

            $aConfigView = array(
                'nPage' => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataList,
            );
            $tViewDataTable = $this->load->view('document/Prerepairresult/wPreRepairResultDatable', $aConfigView, true);
            $aReturnData = array(
                'tViewDataTable' => $tViewDataTable,
                'nStaEvent' => '1',
                'tStaMessg' => 'Success'
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    public function FSvCPreAddPage() //แสดงส่วนรายละเอียดภายในใบประเมินความพึงพอใจของลูกค้า
    {
        try {
            // Clear Data In Doc DT Temp
            $aWhereClearTemp = [
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            ];

            // Lang ภาษา
            // $nLangEdit = $this->session->userdata("tLangEdit");

            $this->Prerepairresult_model->FSnMPreDelALLTmp($aWhereClearTemp);

            $nReturntype = $this->input->post('pnType');
            if ($nReturntype == 1) {
                $aDataAll = array(
                    'tRoute'            => 'docPreRepairResultEventAdd'
                );
            }
        } catch (Exception $Error) {
            $aDataAll = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }

        $this->load->view('document/Prerepairresult/wPreRepairResultPageAdd', $aDataAll);
    }

    public function FSxCPreAddEvent() //เพิ่มใบประเมินลง database
    {
        try {
            $tPreAgnCode        = $this->input->post('ohdPreSvOldAgnCode');
            $tPreBchCode        = $this->input->post('ohdPreSvOldBchCode');
            $tPreDocNo          = $this->input->post('oetPreDocNo');
            $tPreAutoGenCode    = $this->input->post('ocbPreStaAutoGenCode') ? 1 : 0;
            $tStaDocAct         = $this->input->post('ocbPreStaDocAct') ? 1 : 0;
            $tPreDocDate        = $this->input->post('oetPreDocDate') . " " . $this->input->post('oetPreDocTime');
            $tPreDocRefCode     = $this->input->post('ohdPreDocRefCode');
            $tPreDocRefDate     = $this->input->post('oetJPreDocRefBookDate');

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'     => $tPreBchCode,
                'FTXphDocNo'    => $tPreDocNo,
                'FTAgnCode'     => $tPreAgnCode,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTSessionID'   => $this->session->userdata('tSesSessionID')
            );

            //App varsion
            // $tAppVer = FCNtGetAppVersion();
            $tAppVer = 'SB';

            // Check Auto GenCode Document
            if ($tPreAutoGenCode == '1') {
                $aStoreParam = array(
                    "tTblName"    => 'TSVTJob3ChkHD',
                    "tDocType"    => '1',
                    "tBchCode"    => $tPreBchCode,
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d H:i:s")
                );

                $aAutogen    = FCNaHAUTGenDocNo($aStoreParam);
                $aDataWhere['FTXphDocNo']   = $aAutogen[0]["FTXxhDocNo"];
                $tPreDocNo   = $aAutogen[0]["FTXxhDocNo"];
            } else {
                $aDataWhere['FTXphDocNo']   = $tPreDocNo;
                $tPreDocNo   = $tPreDocNo;
            }

            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'tTableHD'          => 'TSVTJob3ChkHD',
                'tTableDT'          => 'TSVTJob3ChkDT',
                'tTableAnsDT'       => 'TSVTJob3ChkDTAns',
                'tTableDocRef3'     => 'TSVTJob3ChkHDDocRef',
                'tTableDocRef2'     => 'TSVTJob2OrdHDDocRef'
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FDXshDocDate'      => $tPreDocDate,
                'FTUsrCode'         => $this->input->post('ohdPreTaskRefUsrCode'),
                'FTXshApvCode'      => '',
                'FTXshRmk'          => $this->input->post('oetPreRmk'),
                'FTXshCarfuel'      => $this->input->post('ohdPreFrmCarFuel'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => 1,
                'FTXshStaApv'       => '',
                'FNXshStaDocAct'    => $tStaDocAct,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata("tSesUsername")
            );

            //อัพเดทเลขไมล์ / น้ำมัน กลับไปที่ใบสั่งงาน 
            $aUpdateJOB2AndJOB1 = array(
                'FCXshCarMileage'   => floatval(str_replace(',','',$this->input->post('oetPreCarMiter'))),
                'FTXshCarfuel'      => $this->input->post('ohdPreFrmCarFuel'),
                'tDocRefNo'         => $tPreDocRefCode,
                'tBchRef'           => $tPreBchCode
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job3
            $aDataJob3AddDocRefEx = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'Job3Chk',
                'FTXshRefDocNo'     => $this->input->post('oetJPreDocRefExtDoc'),
                'FDXshRefDocDate'   => $this->input->post('oetJPreDocRefExtDocDate'),
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job3
            $aDataJob5AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tPreDocRefCode,
                'FDXshRefDocDate'   => $tPreDocRefDate,
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $aDatawhereJob2AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocRefCode,
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job3Chk',
                'FTXshRefDocNo'     => $tPreDocNo,
                'FDXshRefDocDate'   => $tPreDocDate,
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Prerepairresult_model->FSaMPreAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
            $this->Prerepairresult_model->FSaMPreUpdateJOB2HD($aUpdateJOB2AndJOB1);

            // Update Doc No Into Doc Temp
            $this->Prerepairresult_model->FSxMPreAddUpdateDocNoToTemp($aDataWhere, $aDataPrimaryKey);

            // Move Doc DTTemp To DT
            $this->Prerepairresult_model->FSaMPreMoveDtTmpToDt($aDataWhere, $aDataPrimaryKey);

            // Move Doc DTTemp To AnsDT
            $this->Prerepairresult_model->FSaMPreMoveDtTmpToDtAns($aDataWhere, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Prerepairresult_model->FSaMPreAddUpdateRefDocHD($aDataJob5AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef EX3
            if ($this->input->post('oetJPreDocRefExtDoc') != '') {
                $this->Prerepairresult_model->FSaMPreAddUpdateRefDocHDEX($aDataJob3AddDocRefEx, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => 0,
                    'nStaEvent'     => '1',
                    'tAgnCode'      => $tPreAgnCode,
                    'tBchCode'      => $tPreBchCode,
                    'tDocNo'        => $tPreDocNo,
                    'tStaMessg'     => 'Success Add Document.'
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

    //เข้าหน้าแก้ไข
    public function FSvCPreEditPage(){
        try {
            $tAgnCode = $this->input->post('ptAgnCode');
            $tBchCode = $this->input->post('ptBchCode');
            $tDocNo = $this->input->post('ptDocNo');

            //เช็คก่อนว่ามีเอกสารนัดหมายนี้จริงๆ หรือเปล่า
            $aCheckDocNo = $this->Prerepairresult_model->FSaMPreCheckDocNo($tAgnCode, $tBchCode, $tDocNo);
            if ($aCheckDocNo['nStaEvent'] == 500) { //ไม่เจอข้อมูลเอกสาร
                $aDataReturn = array(
                    'nStaEvent' => '500',
                    'tStaMessg' => 'ไม่เจอเอกสารใบประเมิณ'
                );
                echo json_encode($aDataReturn);
            } else {
                // Clear Data In Doc DT Temp
                $aWhereClearTemp = [
                    'FTSessionID'   => $this->session->userdata('tSesSessionID')
                ];

                // Lang ภาษา
                $nLangEdit = $this->session->userdata("tLangEdit");

                $this->Prerepairresult_model->FSnMPreDelALLTmp($aWhereClearTemp);

                // Array Data Where Get (HD,HDSpl,HDDis,DT,DTDis)

                $this->db->trans_begin();

                // Get Data Document HD
                $aDataDocHD         = $this->Prerepairresult_model->FSaMPreGetDataDocHD($tAgnCode, $tBchCode, $tDocNo);

                if (isset($aDataDocHD['raItems']['FTXshRefDocNo'])) {
                    $tDocNo             = $aDataDocHD['raItems']['FTXshRefDocNo'];
                    $aDataJob2          = $this->Prerepairresult_model->FSaMPreGetDataJob2HD($tAgnCode, $tBchCode, $tDocNo);

                    $aDataWhereAddress = [
                        'tCstCode'      => $aDataJob2['raItems']['FTCstCode'],
                        'tCarCstCode'   => $aDataJob2['raItems']['FTCarCode'],
                        'nLangEdit'     => $nLangEdit,
                        'tDocNo'        => $aDataDocHD['raItems']['FTXshRefDocNo'],
                    ];

                    $aDataCstAddr   = $this->Prerepairresult_model->FSaMPreGetDataCustomerAddr($aDataWhereAddress);
                    $aDataCarCst    = $this->Prerepairresult_model->FSaMPreGetDataCarCustomer($aDataWhereAddress);
                    $aDataJob1HD    = $this->Prerepairresult_model->FSaMPreGetDataCarJob1($aDataWhereAddress);
                } else {
                    $aDataJob2      = '';
                    $aDataCstAddr   = '';
                    $aDataCarCst    = '';
                    $aDataJob1HD    = '';
                }


                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $aReturnData = array(
                        'nStaEvent' => '200',
                        'tStaMessg' => 'Error Query Call Edit Page.'
                    );
                    echo json_encode($aReturnData);
                } else {
                    $this->db->trans_commit();
                    $aDataConfigViewAdd = array(
                        'aDataDocHD'        => $aDataDocHD,
                        'aDataJob2'         => $aDataJob2,
                        'aDataCstAddr'      => $aDataCstAddr,
                        'aDataCarCst'       => $aDataCarCst,
                        'aDataJob1HD'       => $aDataJob1HD,
                        'tRoute'            => 'docPreRepairResultEventEdit'
                    );
                    $tViewDataTableList = $this->load->view('document/Prerepairresult/wPreRepairResultPageAdd', $aDataConfigViewAdd, true);
                    $aDataReturn = array(
                        'tViewDataTableList'    => $tViewDataTableList,
                        'nStaEvent'             => '1',
                        'tStaMessg'             => 'Success'
                    );
                    echo json_encode($aDataReturn);
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
            echo json_encode($aReturnData);
        }
    }

    //ฟังก์ชั่นแก้ไขข้อมูล
    public function FSxCPreEditEvent(){
        try {
            $tPreAgnCode    = $this->input->post('ohdPreSvOldAgnCode');
            $tPreBchCode    = $this->input->post('ohdPreSvOldBchCode');
            $tPreDocNo      = $this->input->post('oetPreDocNo');
            $tStaDocAct     = $this->input->post('ocbPreStaDocAct') ? 1 : 0;
            $tPreDocDate    = $this->input->post('oetPreDocDate') . " " . $this->input->post('oetPreDocTime');
            $tPreDocRefCode = $this->input->post('ohdPreDocRefCode');
            $tPreDocRefDate = $this->input->post('oetJPreDocRefBookDate');

            // Array Data Where Insert
            $aDataWhere = array(
                'FTBchCode'         => $tPreBchCode,
                'FTXphDocNo'        => $tPreDocNo,
                'FTAgnCode'         => $tPreAgnCode,
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTSessionID'       => $this->session->userdata('tSesSessionID')
            );

            //App varsion
            // $tAppVer = FCNtGetAppVersion();
            $tAppVer = 'SB';
            
            $aDataPrimaryKey = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'tTableHD'          => 'TSVTJob3ChkHD',
                'tTableDT'          => 'TSVTJob3ChkDT',
                'tTableAnsDT'       => 'TSVTJob3ChkDTAns',
                'tTableDocRef3'     => 'TSVTJob3ChkHDDocRef',
                'tTableDocRef2'     => 'TSVTJob2OrdHDDocRef'
            );

            //อัพเดทเลขไมล์ / น้ำมัน กลับไปที่ใบสั่งงาน 
            $aUpdateJOB2AndJOB1 = array(
                'FCXshCarMileage'   => floatval(str_replace(',','',$this->input->post('oetPreCarMiter'))),
                'FTXshCarfuel'      => $this->input->post('ohdPreFrmCarFuel'),
                'tDocRefNo'         => $tPreDocRefCode,
                'tBchRef'           => $tPreBchCode
            );

            // ข้อมูล Insert ลงตาราง  HD
            $aDataAddHD = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FDXshDocDate'      => $tPreDocDate,
                'FTUsrCode'         => $this->input->post('ohdPreTaskRefUsrCode'),
                'FTXshApvCode'      => '',
                'FTXshRmk'          => $this->input->post('oetPreRmk'),
                'FTRsnCode'         => '',
                'FTXshStaDoc'       => 1,
                'FTXshCarfuel'      => $this->input->post('ohdPreFrmCarFuel'),
                'FNXshStaDocAct'    => $tStaDocAct,
                'FTXshAppVer'       => trim($tAppVer),
                'FTAppCode'         => 'SB',
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata("tSesUsername"),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata("tSesUsername")
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job3
            $aDataJob5AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefKey'       => 'Job2Ord',
                'FTXshRefDocNo'     => $tPreDocRefCode,
                'FDXshRefDocDate'   => $tPreDocRefDate,
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job3
            $aDataJob3AddDocRefEx = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocNo,
                'FTXshRefType'      => 3,
                'FTXshRefKey'       => 'Job3Chk',
                'FTXshRefDocNo'     => $this->input->post('oetJPreDocRefExtDoc'),
                'FDXshRefDocDate'   => $this->input->post('oetJPreDocRefExtDocDate'),
            );

            // ข้อมูล Insert ลงตาราง DocRef ของ Job2
            $aDatawhereJob2AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocRefCode,
            );

            $aDataJob2AddDocRef = array(
                'FTAgnCode'         => $tPreAgnCode,
                'FTBchCode'         => $tPreBchCode,
                'FTXshDocNo'        => $tPreDocRefCode,
                'FTXshRefType'      => 2,
                'FTXshRefKey'       => 'Job3Chk',
                'FTXshRefDocNo'     => $tPreDocNo,
                'FDXshRefDocDate'   => $tPreDocDate,
            );

            $this->db->trans_begin();

            // ข้อมูล Insert ลงตาราง  HD
            $this->Prerepairresult_model->FSaMPreAddUpdateHD($aDataAddHD, $aDataPrimaryKey);

            // update เลขไมล์ และ แกนน้ำมันกลับไปที่ใบสั่งงาน
            $this->Prerepairresult_model->FSaMPreUpdateJOB2HD($aUpdateJOB2AndJOB1);

            // Move Doc DTTemp To DT
            $this->Prerepairresult_model->FSaMPreMoveDtTmpToDt($aDataWhere, $aDataPrimaryKey);

            // Move Doc DTTemp To AnsDT
            $this->Prerepairresult_model->FSaMPreMoveDtTmpToDtAns($aDataWhere, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef
            $this->Prerepairresult_model->FSaMPreAddUpdateRefDocHD($aDataJob5AddDocRef, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);

            // ข้อมูล Insert ลงตาราง DocRef EX3
            if ($this->input->post('oetJPreDocRefExtDoc') != '') {
                $this->Prerepairresult_model->FSaMPreAddUpdateRefDocHDEX($aDataJob3AddDocRefEx, $aDataJob2AddDocRef, $aDatawhereJob2AddDocRef, $aDataPrimaryKey);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Error Unsucess Add Document."
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => 0,
                    'nStaEvent'     => '1',
                    'tAgnCode'      => $tPreAgnCode,
                    'tBchCode'      => $tPreBchCode,
                    'tDocNo'        => $tPreDocNo,
                    'tStaMessg'     => 'Success Add Document.'
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

    //อนุมัติเอกสาร
    public function FSoCPreApproveEvent(){
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');

            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'        => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshStaApv'       => 1,
                'FTXshApvCode'       => $this->session->userdata('tSesUsername')
            );

            $this->Prerepairresult_model->FSaMPreApproveDocument($aDataUpdate);

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

    // ยกเลิกเอกสาร
    public function FSvCPreCancelDocument()
    {
        try {
            $tAgnCode      = $this->input->post('tAgnCode');
            $tBchCode      = $this->input->post('tBchCode');
            $tDocNo        = $this->input->post('tDocNo');
            $tDocRef       = $this->input->post('oetPreDocRefCode');

            $aDataUpdate = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo
            );

            $aDataWhereDocRef = array(
                'FTAgnCode'         => $tAgnCode,
                'FTBchCode'         => $tBchCode,
                'FTXshDocNo'        => $tDocNo,
                'FTXshRefType'      => 1,
                'FTXshRefDocNo'     => $tDocRef
            );

            $this->Prerepairresult_model->FSaMPreCancelDocument($aDataUpdate, $aDataWhereDocRef);
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

    //ลบข้อมูลเอกสาร
    public function FSoCPreEventDelete()
    {
        try {
            $tDataDocNo = $this->input->post('tDataDocNo');
            $aDataMaster = array(
                'tDataDocNo' => $tDataDocNo
            );

            $aResDelAll = $this->Prerepairresult_model->FSnMPreDelDocument($aDataMaster);

            if ($aResDelAll['rtCode'] == '1') {
                $aDataStaReturn = array(
                    'nStaEvent' => '1',
                    'tStaMessg' => 'Success'
                );
            } else {
                $aDataStaReturn = array(
                    'nStaEvent' => $aResDelAll['rtCode'],
                    'tStaMessg' => $aResDelAll['rtDesc']
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

    //Functionality :  ค้นหาข้อมูลลูกค้า
    //Parameters : Ajax jJobRequestStep1()
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSaCPreFindCst()
    {
        $poItem     = json_decode($this->input->post('poItem'));
        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCstCode'  => $poItem[11],
            'tCarCstCode'  => $poItem[12],
            'nLangEdit' => $nLangEdit,
            'tDocNo'    => $this->input->post('tDocNo'),
        ];
        $aDataReturn    = [
            'aDataCstAddr'  => $this->Prerepairresult_model->FSaMPreGetDataCustomerAddr($aDataWhere),
            'aDataCarCst' => $this->Prerepairresult_model->FSaMPreGetDataCarCustomer($aDataWhere),
            'aDataJob1HD' => $this->Prerepairresult_model->FSaMPreGetDataCarJob1($aDataWhere),
        ];
        echo json_encode($aDataReturn);
    }

    //Functionality :  ค้นหาข้อมูลลูกค้า
    //Parameters : Ajax jJobRequestStep1()
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSaCPreFindCstAddress()
    {
        $poItem     = json_decode($this->input->post('poItem'));
        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCstCode'  => $poItem[0],
            'nLangEdit' => $nLangEdit,
        ];
        $aDataReturn    = [
            'aDataCstAddr'  => $this->Prerepairresult_model->FSaMPreGetDataCustomerAddr($aDataWhere),
        ];
        echo json_encode($aDataReturn);
    }

    //Functionality :  ค้นหาข้อมูลรถ
    //Parameters : Ajax jJobRequestStep1()
    //Creator : 12/10/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSaCPreFindCar()
    {
        $poItem     = json_decode($this->input->post('poItem'),true);
        
        // Lang ภาษา
        $nLangEdit  = $this->session->userdata("tLangEdit");
        // Codition Where
        $aDataWhere = [
            'tCarCstCode'  => $poItem[0],
            'nLangEdit' => $nLangEdit,
        ];
        $aDataReturn    = [
            'aDataCarCst' => $this->Prerepairresult_model->FSaMPreGetDataCarCustomer($aDataWhere),
        ];
        echo json_encode($aDataReturn);
    }

    //เช็คข้อมูลจาก Jump จากหน้า JobOrder
    public function FSoCPreChkTypeAddOrUpdate(){
        $tDocNo = $this->input->post('ptDocNo');
        $aDataWhereJob2 = $this->Prerepairresult_model->FSaMPreGetDataWhereJob2($tDocNo);
        $nStaFoundData = $aDataWhereJob2['rtCode'];
        $aDataJob2 = $aDataWhereJob2['aRtData'];

        $aDataFinal =  array(
            'tReturn' => $nStaFoundData,
            'aRtData' => $aDataJob2
        );
        echo json_encode($aDataFinal);
    }
}
