<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cSalePriceAdj extends MX_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('document/salepriceadj/mSalePriceAdj');
        $this->load->model('authen/login/mLogin');
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nSpaBrowseType, $tSpaBrowseOption){
        // ========================== เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie ==========================
            $aCookieMenuCode = array(
                'name'      => 'tMenuCode',
                'value'     => json_encode('SKU003'),
                'expire'    => 0
            );
            $this->input->set_cookie($aCookieMenuCode);
            $aCookieMenuName = array(
                'name'      => 'tMenuName',
                'value'     => json_encode('ใบปรับราคาขาย'),
                'expire'    => 0
            );
            $this->input->set_cookie($aCookieMenuName);
        // =============================================================================================
        $nMsgResp   = array('title' => "Sale Price Adj");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if (!$isXHR) {
            $this->load->view('common/wHeader', $nMsgResp);
            $this->load->view('common/wTopBar', array('nMsgResp' => $nMsgResp));
            $this->load->view('common/wMenu', array('nMsgResp' => $nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('dcmSPA/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventSalePriceAdj = FCNaHCheckAlwFunc('dcmSPA/0/0');
        $this->load->view('document/salepriceadj/wSalePriceAdj', array(
            'vBtnSave'              => $vBtnSave,
            'nSpaBrowseType'        => $nSpaBrowseType,
            'tSpaBrowseOption'      => $tSpaBrowseOption,
            'aAlwEventSalePriceAdj' => $aAlwEventSalePriceAdj
        ));
    }

    //Functionality : Function Call Product Size Page List
    //Parameters : Ajax and Function Parameter
    //Creator : 21/09/2018 Witsarut(Bell)
    //Return : String View
    //Return Type : View
    public function FSvCSPAMainPage(){
        $aAlwEventSalePriceAdj = FCNaHCheckAlwFunc('dcmSPA/0/0');
        $this->load->view('document/salepriceadj/wSalePriceAdjMain', array(
            'aAlwEventSalePriceAdj'  => $aAlwEventSalePriceAdj
        ));
    }


    //Functionality : Function Call DataTables Product Size
    //Parameters : Ajax Call View DataTable
    //Creator : 21/09/2018 Witsarut (Bell)
    //Return : String View
    //Return Type : View
    public function FSvCSPADataList(){
        try {
            $oAdvanceSearchData     = $this->input->post('oAdvanceSearchData');
            $nPage                  = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit              = $this->session->userdata("tLangEdit");
            $aData  = array(
                'nPage'                 => $nPage,
                'nRow'                  => 10,
                'FNLngID'               => $nLangEdit,
                'oAdvanceSearchData'    => $oAdvanceSearchData
            );

            $aSpaDataList           = $this->mSalePriceAdj->FSaMSPAList($aData);
            $aAlwEventSalePriceAdj  = FCNaHCheckAlwFunc('dcmSPA/0/0');
            $aGenTable  = array(
                'aSpaDataList'              => $aSpaDataList,
                'nPage'                     => $nPage,
                'oAdvanceSearchData'        => $oAdvanceSearchData,
                'aAlwEventSalePriceAdj'     => $aAlwEventSalePriceAdj
            );
            $this->load->view('document/salepriceadj/wSalePriceAdjDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function Call DataTables Product Price
    //Parameters : Ajax Call View DataTable
    //Creator : 18/02/2019 Napat(Jame)
    //Return : String View
    //Return Type : View
    public function FSvCSPAPdtPriDataList(){
        try {
            $tSearchAll     = $this->input->post('tSearchAll');
            $FTXphDocNo     = $this->input->post('FTXphDocNo');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aData          = array(
                'nStaAddOrEdit' => 99,
                'nPage'         => $nPage,
                'nRow'          => 20,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll,
                'FTXthDocKey'   => 'TCNTPdtAdjPriHD',
                'FTXphDocNo'    => $FTXphDocNo,
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
            );
            // Get Option Show Decimal
            $nOptDecimalShow        = get_cookie('tOptDecimalShow');
            // $aColumnShow            = $this->mSalePriceAdj->FCNaDCLGetColumnShow('TCNTPdtAdjPriDT');
            $aPdtPriDataList        = $this->mSalePriceAdj->FSaMSPAPdtPriList($aData);
            $aAlwEventSalePriceAdj  = FCNaHCheckAlwFunc('dcmSPA/0/0');
            $aGenTable              = array(
                'aPdtPriDataList'           => $aPdtPriDataList,
                'nPage'                     => $nPage,
                'tSearchAll'                => $tSearchAll,
                'aAlwEventSalePriceAdj'     => $aAlwEventSalePriceAdj,
                // 'aColumnShow'               => $aColumnShow,
                'nOptDecimalShow'           => $nOptDecimalShow
            );
            // print_r($aPdtPriDataList);
            $this->load->view('document/salepriceadj/wSalePriceAdjPdtPriDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function CallPage Sale Price Adj Add
    //Parameters : Ajax Call View Add
    //Creator : 15/02/2019 Napat (Jame)
    //Return : String View
    //Return Type : View
    public function FSvCSPAAddPage(){
        try {
            // ================== Create By Witsarut 27/08/2019 ===================
            // Lang ภาษา
            $nLangEdit  = $this->session->userdata("tLangEdit");
            $aDataWhere = array(
                'FNLngID'   => $nLangEdit
            );
            $tAPIReq    = "";
            $tMethodReq = "GET";
            $aResList   = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhere);
            // ================== Create By Witsarut 27/08/2019 ===================
            $aData = array(
                'nStaAddOrEdit'   => 99,
                'FTXthDocKey'     => 'TCNTPdtAdjPriHD',
                'FTSessionID'     => $this->session->userdata('tSesSessionID'),
                'aResList'        => $aResList,
                'tBchCompCode'    => $this->session->userdata("tSesUsrBchCodeDefault"),
                'tBchCompName'    => $this->session->userdata("tSesUsrBchNameDefault")
            );
            $this->mSalePriceAdj->FSaMSPADelPdtTmp($aData);
            $this->load->view('document/salepriceadj/wSalePriceAdjAdd', $aData);
        } catch (Exception $Error) {
            echo $Error;
        }
    }


    //Functionality : Function CallPage Sale Price Adj Edits
    //Parameters : Ajax Call View Add
    //Creator : 15/02/2019 Napat(Jame)
    //Return : String View
    //Return Type : View
    public function FSvCSPAEditPage(){
        try {
            $tXphDocNo  = $this->input->post('tXphDocNo');
            $nLangEdit  = $this->session->userdata("tLangEdit");
            $aDataDoc   = array(
                'FTXphDocNo'    => $tXphDocNo,
                'FTXthDocKey'   => 'TCNTPdtAdjPriHD',
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FDLastUpdOn'   => date('Y-m-d'),
                'FDCreateOn'    => date('Y-m-d'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            );
            $oDelTmp    = $this->mSalePriceAdj->FSaMSPADelPdtTmp($aDataDoc);
            $oDTtoTmp   = $this->mSalePriceAdj->FSoMSPADTtoTmp($aDataDoc);
            $aData      = array(
                'FTXphDocNo'    => $tXphDocNo,
                'FNLngID'       => $nLangEdit
            );
            $aSpaData    = $this->mSalePriceAdj->FSaMSPAGetDataByID($aData);
            // Lang ภาษา
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aDataWhere  = array(
                'FNLngID'   => $nLangEdit
            );
            $tAPIReq    = "";
            $tMethodReq = "GET";
            $aResList    = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhere);
            // ================== Create By Witsarut 27/08/2019 ===================
            $aDataSalePriceAdj  = array(
                'nStaAddOrEdit' => 1,
                'aSpaData'      => $aSpaData,
                'oDelTmp'       => $oDelTmp,
                'oDTtoTmp'      => $oDTtoTmp,
                'nLngID'        => $nLangEdit,
                'aResList'      => $aResList,
            );
            $this->load->view('document/salepriceadj/wSalePriceAdjAdd', $aDataSalePriceAdj);
            $aReturnData = array(
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Call Page Success',
                //เพิ่มใหม่
                'tLogType'          => 'INFO',
                'tDocNo'            => $tXphDocNo,
                'tEventName'        => 'เรียกดูเอกสารใบปรับราคาขาย',
                'nLogCode'          => '001',
                'nLogLevel'         => '',
                'FTXphUsrApv'       => $aSpaData['raItems']['FTXphUsrApv']
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
                //เพิ่มใหม่
                'tLogType' => 'ERROR',
                'tDocNo' => $tXphDocNo,
                'tEventName' => 'เรียกดูเอกสารใบปรับราคาขาย',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => $aSpaData['raItems']['FTXphUsrApv']
            );
            echo $Error;
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturnData);
    }


    //Functionality : Event Add Sale Price Adj
    //Parameters : Ajax Event
    //Creator : 21/02/2019 Napat(Jame)
    //Return : Status Add Event
    //Return Type : String
    public function FSoCSPAAddEvent(){
        //Check Auto gen Code
        $nStaAutoGenCode = $this->input->post('ocbStaAutoGenCode');
        if ($nStaAutoGenCode == 'on') {
            $aStoreParam = array(
                "tTblName"   => 'TCNTPdtAdjPriHD',                  //ชื่อตาราง (จำเป็นต้องมี)
                "tDocType"   => 9,                                  //ประเภทเอกสาร (จำเป็นต้องมี)
                "tBchCode"   => $this->input->post('oetBchCode'),   //สาขาที่สร้าง (จำเป็นต้องมี)
                "tShpCode"   => "",                                 //ร้านค้าที่สร้าง (ไม่มีให้ใส่ว่าง)
                "tPosCode"   => "",            //เครื่องจุดขาย (ไม่มีให้ใส่ว่าง)
                "dDocDate"   => date("Y-m-d")  //วันที่ปัจจุบัน (จำเป็นต้องมี)
            );
            $aXthDocNo  = FCNaHAUTGenDocNo($aStoreParam);
            $tXphDocNo  = $aXthDocNo[0]['FTXxhDocNo'];
        } else {
            $tXphDocNo  = $this->input->post('oetXphDocNo');
        }
        if ($tXphDocNo != '') {
            try {
                $dRefInfDate = $this->input->post('oetXphRefIntDate');
                if ($dRefInfDate == "") {
                    $dRefInfDate = NULL;
                }

                // ตรวจสอบ DocType 18/10/2019 Saharat(GolF)
                $CheckDate = $this->input->post('oetCheckDate');
                if ($CheckDate == 1) {
                    $dStart = $this->input->post('oetXphDStart');
                    $dStop = date('Y-m-d', strtotime('+5 year'));
                } else {
                    $dStart = $this->input->post('oetXphDStart');
                    $dStop = $this->input->post('oetXphDStop');
                }

                $aDataSpa = array(
                    'FTBchCode'         => $this->input->post('oetBchCode'),
                    'FTXphDocNo'        => $tXphDocNo,
                    'FTXphStaDoc'       => '1',
                    'FDXphDocDate'      => $this->input->post('oetXphDocDate'),
                    'FTXphDocTime'      => $this->input->post('oetXphDocTime'),
                    'FTCreateBy'        => $this->input->post('oetCreateBy'),

                    'FTXphDocType'      => $this->input->post('ocmXphDocType'),
                    'FTXphStaAdj'       => $this->input->post('ocmXphStaAdj'),
                    'FTXphZneTo'        => $this->input->post('oetZneChain'),
                    'FTXphBchTo'        => $this->input->post('oetXphBchTo'),
                    'FTXphShpTo'        => $this->input->post('oetXphShpTo'),
                    'FTPplCode'         => $this->input->post('oetPplCode'),
                    'FDXphDStart'       => $dStart,
                    'FDXphDStop'        => $dStop,
                    'FTXphTStart'       => $this->input->post('oetXphTStart'),
                    'FTXphTStop'        => $this->input->post('oetXphTStop'),

                    // เพิ่ม merchante 10/06/62 Jame(Napat)
                    'FTMerCode'         => $this->input->post('oetXphMerCode'),

                    'FTXphName'         => $this->input->post('oetXphName'),
                    'FTXphRefInt'       => $this->input->post('oetXphRefInt'),
                    'FDXphRefIntDate'   => $dRefInfDate,
                    'FTAggCode'         => $this->input->post('oetAggCode'),
                    'FTXphPriType'      => $this->input->post('ocmXphPriType'),
                    'FNXphStaDocAct'    => $this->input->post('ocbXphStaDocAct'),
                    'FTXphRmk'          => $this->input->post('otaXphRmk'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTUsrCode'         => $this->session->userdata('tSesUsername'),

                    // for DocTmpDT
                    'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                    'FTXthDocKey'       => 'TCNTPdtAdjPriHD',
                );
                $oCountDup = $this->mSalePriceAdj->FSnMSPACheckDuplicate($aDataSpa['FTXphDocNo']);

                if ($oCountDup !== FALSE && $oCountDup['counts'] == 0) {

                    $this->db->trans_begin();

                    $this->mSalePriceAdj->FSaMSPAAddUpdateDocNoInDocTemp($aDataSpa); // Update Docno in DocTemp
                    $this->mSalePriceAdj->FSaMSPAAddUpdateMaster($aDataSpa); // Update to HD

                    // Add Product into DocTmpDT
                    if ($this->input->post('nStaAction') == '1') {
                        $this->mSalePriceAdj->FSaMSPADelAllProductDT($aDataSpa); // Delete All Product by id from table DT
                        $this->mSalePriceAdj->FSoMSPATmptoDT($aDataSpa); // Move Doc temp to DT
                    }

                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $aReturn = array(
                            'nStaEvent' => '900',
                            'tStaMessg' => "Unsucess Add Sale Price Adj"
                        );
                    } else {
                        $this->db->trans_commit();
                        $aReturn = array(
                            'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                            'tCodeReturn' => $aDataSpa['FTXphDocNo'],
                            'tBchCode' => $aDataSpa['FTBchCode'],
                            'nStaEvent'    => '1',
                            'tStaMessg' => 'Success Add Sale Price Adj',
                            //เพิ่มใหม่
                            'tLogType' => 'INFO',
                            'tDocNo' => $aDataSpa['FTXphDocNo'],
                            'tEventName' => 'บันทึกใบปรับราคาขาย',
                            'nLogCode' => '001',
                            'nLogLevel' => '',
                            'FTXphUsrApv'   => ''
                        );
                    }

                } else {
                    $aReturn = array(
                        'nStaEvent' => '801',
                        'tStaMessg' => "เลขที่เอกสารมีอยู่แล้วในระบบ",
                        //เพิ่มใหม่
                        'tLogType' => 'ERROR',
                        'tDocNo' => $aDataSpa['FTXphDocNo'],
                        'tEventName' => 'Check Data Duplicate',
                        'nLogCode' => '900',
                        'nLogLevel' => 'Critical',
                        'FTXphUsrApv'   => ''
                    );
                }
            } catch (Exception $Error) {
                $aReturn = array(
                    'nStaReturn' => '500',
                    'tStaMessg' => $Error->getMessage(),
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataSpa['FTXphDocNo'],
                    'tEventName' => 'บันทึกใบปรับราคาขาย',
                    'nLogCode' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            }
        } else {
            $aReturn = array(
                'nStaEvent' => '801',
                'tStaMessg' => language('common/main/main', 'tCanNotAutoGenCode')
            );
        }
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }


    //Functionality : Event Edit Sale Price Adj
    //Parameters : Ajax Event
    //Creator : 22/02/2019 Napat(Jame)
    //Return : Status Edit Event
    //Return Type : String
    public function FSoCSPAEditEvent()
    {
        try {
            $dRefInfDate = $this->input->post('oetXphRefIntDate');

            if ($dRefInfDate == "") {
                $dRefInfDate = NULL;
            }

            // ตรวจสอบ DocType 18/10/2019 Saharat(GolF)
            $CheckDate = $this->input->post('oetCheckDate');
            if ($CheckDate == 1) {
                $dStart = $this->input->post('oetXphDStart');
                $dStop = date('Y-m-d', strtotime('+5 year'));
            } else {
                $dStart = $this->input->post('oetXphDStart');
                $dStop = $this->input->post('oetXphDStop');
            }

            $this->db->trans_begin();
            $aDataSpa = array(
                'FTBchCode'         => $this->input->post('oetBchCode'),
                'FTXphDocNo'        => $this->input->post('oetXphDocNo'),
                'FDXphDocDate'      => $this->input->post('oetXphDocDate'),
                'FTXphDocTime'      => $this->input->post('oetXphDocTime'),
                // 'FTCreateBy'        => $this->input->post('oetCreateBy'),

                'FTXphDocType'      => $this->input->post('ocmXphDocType'),
                'FTXphStaAdj'       => $this->input->post('ocmXphStaAdj'),
                'FTXphZneTo'        => $this->input->post('oetZneChain'),
                'FTXphBchTo'        => $this->input->post('oetXphBchTo'),
                'FTXphShpTo'        => $this->input->post('oetXphShpTo'),
                'FTPplCode'         => $this->input->post('oetPplCode'),
                'FDXphDStart'       => $dStart,
                'FDXphDStop'        => $dStop,
                'FTXphTStart'       => $this->input->post('oetXphTStart'),
                'FTXphTStop'        => $this->input->post('oetXphTStop'),

                'FTXphName'         => $this->input->post('oetXphName'),
                'FTXphRefInt'       => $this->input->post('oetXphRefInt'),
                'FDXphRefIntDate'   => $dRefInfDate,
                'FTAggCode'         => $this->input->post('oetAggCode'),
                'FTXphPriType'      => $this->input->post('ocmXphPriType'),
                'FNXphStaDocAct'    => $this->input->post('ocbXphStaDocAct'),
                'FTXphRmk'          => $this->input->post('otaXphRmk'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),

                // for DocTmpDT
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FTXthDocKey'       => 'TCNTPdtAdjPriHD',
                'FTMerCode'         => $this->input->post('oetXphMerCode')
            );
            // $aCountDupPdtPriHD = $this->mSalePriceAdj->FSnMSPACheckDuplicatePdtPriHD($aDataSpa);

            // if($aCountDupPdtPriHD !== FALSE && $aCountDupPdtPriHD['counts'] == 0){

            $this->mSalePriceAdj->FSaMSPAAddUpdateMaster($aDataSpa);
            $this->mSalePriceAdj->FSaMSPADelAllProductDT($aDataSpa);
            $this->mSalePriceAdj->FSoMSPATmptoDT($aDataSpa); // Move Doc temp to DT

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent' => '900',
                    'tStaMessg' => "Unsucess Edit Sale Price Adj",
                    //เพิ่มใหม่
                    'tLogType' => 'ERROR',
                    'tDocNo' => $aDataSpa['FTXphDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบปรับราคาขาย ',
                    'nLogLevel' => '500',
                    'nLogLevel' => 'Critical',
                    'FTXphUsrApv'   => ''
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn' => $aDataSpa['FTXphDocNo'],
                    'tBchCode' => $aDataSpa['FTBchCode'],
                    'nStaEvent'    => '1',
                    'tStaMessg' => 'Success Edit Sale Price Adj',
                    //เพิ่มใหม่
                    'tLogType' => 'INFO',
                    'tDocNo' => $aDataSpa['FTXphDocNo'],
                    'tEventName' => 'แก้ไขและบันทึกใบปรับราคาขาย ',
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
                'tDocNo' => $aDataSpa['FTXphDocNo'],
                'tEventName' => 'แก้ไขและบันทึกใบปรับราคาขาย',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn); 
        echo json_encode($aReturn);
    }


    //Functionality : Event Delete Sale Price Adj
    //Parameters : Ajax jReason()
    //Creator : 18/02/2019 Napat(Jame)
    //Return : Status Delete Event
    //Return Type : String
    public function FSoCSPADeleteEvent()
    {
        $FTXphDocNo = $this->input->post('tXphDocNo');
        $aDataMaster = array(
            'FTXphDocNo' => $FTXphDocNo
        );
        $aResDel = $this->mSalePriceAdj->FSaMSPADelAll($aDataMaster);
        if ($aResDel['rtCode'] == 1 ) {
            $aReturn = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'tLogType' => 'INFO',
                'tDocNo' => $FTXphDocNo,
                'tEventName' => 'ลบใบปรับราคาขาย ',
                'nLogCode' => '001',
                'nLogLevel' => '',
                'FTXphUsrApv'   => ''
            );
        }else{
            $aReturn = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'tLogType' => 'ERROR',
                'tDocNo' => $FTXphDocNo,
                'tEventName' => 'ลบใบปรับราคาขาย ',
                'nLogCode' => '500',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    //Functionality : Event Delete Product Price list
    //Parameters : Ajax jReason()
    //Creator : 25/02/2019 Napat(Jame)
    //Return : Status Delete Event
    //Return Type : String
    public function FSoCSPAPdtPriDeleteEvent()
    {
        $aPdtDataItem = json_decode($this->input->post('tPdtDataItem'), JSON_FORCE_OBJECT);
        $tDelType = $this->input->post('tDelType');
        $FTXphDocNo = $this->input->post('tDocNo');
        $FTPdtCode = $this->input->post('tPdtCode');
        $FTPunCode = $this->input->post('tPunCode');
        $tSta = $this->input->post('tSta');
        $tSeq = $this->input->post('tSeq');
        $tSession = $this->session->userdata('tSesSessionID');

        if ($tDelType == "M") { // Delete Multiple
            foreach ($aPdtDataItem as $aPdtData) {
                $aDataMaster = array(
                    'FNXtdSeqNo' => $aPdtData['tSeq'],
                    'FTPdtCode' => $aPdtData['tPdt'],
                    'FTPunCode' => $aPdtData['tPun'],
                    'FTSessionID' => $tSession,
                    'FTXthDocKey' => 'TCNTPdtAdjPriHD'
                );
                $aResDel = $this->mSalePriceAdj->FSaMSPAPdtTmpDelAll($aDataMaster);

                // ตรวจสอบกรอกข้อมูลซ้ำ Temp
                if ($aPdtData['tSta'] == "5") {
                    $aParams = [
                        'tUserSessionID' => $tSession,
                        'aFieldName' => [['FTPdtCode', $aPdtData['tPdt']], ['FTPunCode', $aPdtData['tPun']]]
                    ];
                    FCNnDocTmpChkInlineCodeMultiDupInTemp($aParams);
                }
            }
        } else { // Delete Single

            $aDataMaster = array(
                'FTXphDocNo' => $FTXphDocNo,
                'FTPdtCode' => $FTPdtCode,
                'FTPunCode' => $FTPunCode,
                'FTSessionID' => $tSession,
                'FNXtdSeqNo' => $tSeq,
                'FTXthDocKey' => 'TCNTPdtAdjPriHD',
            );
            $aResDel = $this->mSalePriceAdj->FSaMSPAPdtTmpDelAll($aDataMaster);

            // ตรวจสอบกรอกข้อมูลซ้ำ Temp
            if ($tSta == "5") {
                $aParams = [
                    'tUserSessionID' => $tSession,
                    'aFieldName' => [['FTPdtCode', $FTPdtCode], ['FTPunCode', $FTPunCode]]
                ];
                FCNnDocTmpChkInlineCodeMultiDupInTemp($aParams);
            }
        }
        if ($aResDel['rtCode'] == 1) {
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'tLogType' => 'INFO',
                'tDocNo' => $FTXphDocNo,
                'tEventName' => 'ลบใบปรับราคาขาย ',
                'nLogCode' => '001',
                'nLogLevel' => '',
                'FTXphUsrApv'   => ''
            );
        }else{
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'tLogType' => 'ERROR',
                'tDocNo' => $FTXphDocNo,
                'tEventName' => 'ลบใบปรับราคาขาย ',
                'nLogLevel' => '500',
                'nLogLevel' => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    public function FSoCSPAGetBchComp(){
        $aGetBch    = $this->mLogin->FSaMLOGGetBch();
        $aReturn    = array(
            'FTBchCode' => $aGetBch[0]['FTBchcode']
        );
        echo json_encode($aReturn);
    }

    public function FSvCSPAPdtPriAddTmpEvent(){
        try {
            $aDataPdt    = $this->input->post('aData');
            $tBchCode    = $this->input->post('tFTBchCode');
            $tDocNo      = $this->input->post('tFTXthDocNo');
            $tSession    = $this->session->userdata('tSesSessionID');
            $nNumData    = FCNnHSizeOf($aDataPdt);
            $aDataGetSeq = array(
                'FTXthDocNo'    => $tDocNo,
                'FTXthDocKey'   => 'TCNTPdtAdjPriHD',
                'FTSessionID'   => $tSession
            );
            $aGetSeq = $this->mSalePriceAdj->FSaMSPACheckDataSeq($aDataGetSeq);
            $nSeq    = $aGetSeq[0]['nSeq'];
            if ($aGetSeq !== FALSE) {
                for ($i = 0; $i < $nNumData; $i++) {
                    $nSeq = $nSeq + 1;
                    $aDataEdit = array(
                        'FNXtdSeqNo'            => $nSeq,
                        'FTBchCode'             => $tBchCode,
                        'FTXthDocNo'            => $tDocNo,
                        'FTXthDocKey'           => 'TCNTPdtAdjPriHD',
                        'FTXtdShpTo'            => $aDataPdt[$i]['packData']['SHP'],
                        'FTXtdBchTo'            => $aDataPdt[$i]['packData']['BCH'],
                        'FTPdtCode'             => $aDataPdt[$i]['pnPdtCode'],
                        'FTPunCode'             => $aDataPdt[$i]['ptPunCode'],
                        'FCXtdPriceRet'         => $aDataPdt[$i]['packData']['PriceRet'],
                        'FCXtdPriceWhs'         => $aDataPdt[$i]['packData']['PriceWhs'],
                        'FCXtdPriceNet'         => $aDataPdt[$i]['packData']['PriceNet'],
                        'FTSessionID'           => $tSession,
                        'FDLastUpdOn'           => date('Y-m-d'),
                        'FDCreateOn'            => date('Y-m-d'),
                        'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                        'FTCreateBy'            => $this->session->userdata('tSesUsername')
                    );
                    $aCheckTmpDup = $this->mSalePriceAdj->FSaMSPACheckDataTempDuplicate($aDataEdit); //check data duplicate
                    // insert data to table doctmp if not have items
                    if ($aCheckTmpDup == FALSE) {
                        $this->mSalePriceAdj->FSaMSPAAddPdtDocTmp($aDataEdit);
                    }
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Product to tmp"
                    );
                } else {
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'    => $this->input->post('tFTXthDocNo'),
                        'nStaEvent'        => '1',
                        'tStaMessg'        => 'Success Add Product to tmp'
                    );
                }
            } else {

                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Not found product from doc temp."
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    public function FSvCSPAPdtPriAddDTEvent(){
        try {
            $aData = array(
                'FTXphDocNo'    => $this->input->post('oetXphDocNo'),
                'FTSessionID'   => $this->session->userdata('tSesSessionID'),
                'FTXthDocKey'   => 'TCNTPdtAdjPriHD',
                'FDLastUpdOn'   => date('Y-m-d'),
                'FDCreateOn'    => date('Y-m-d'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            );

            $this->mSalePriceAdj->FSaMSPADelAllProductDT($aData);

            if ($this->input->post('nStaAction') == '1') {
                $this->mSalePriceAdj->FSoMSPADTtoTmp($aData);
                $this->mSalePriceAdj->FSoMSPATmptoDT($aData);
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Product to DT"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'    => $this->input->post('oetXphDocNo'),
                    'nStaEvent'        => '1',
                    'tStaMessg'        => 'Success Add Product to DT'
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {

            echo $Error;
        }
    }

    public function FSoCSPAProductDeleteAllEvent(){
        $FTXphDocNo     = $this->input->post('tDocNo');
        $FTSessionID    = $this->session->userdata('tSesSessionID');
        $aDataMaster    = array(
            'FTXthDocKey'   => 'TCNTPdtAdjPriHD',
            'FTXphDocNo'    => $FTXphDocNo,
            'FTSessionID'   => $FTSessionID
        );
        $aResDel    = $this->mSalePriceAdj->FSaMSPAPdtTmpDelAll($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    public function FSoCSPAUpdatePriceTemp(){
        $aDataMaster    = array(
            'FTXthDocNo'        => $this->input->post('FTXthDocNo'),
            'FTPdtCode'         => $this->input->post('FTPdtCode'),
            'FTPunCode'         => $this->input->post('FTPunCode'),
            'tPrice'            => $this->input->post('ptPrice'),
            'tSeq'              => $this->input->post('tSeq'),
            'tColValidate'      => $this->input->post('tColValidate'),
            'tValue'            => empty($this->input->post('ptValue')) ? 0 : $this->input->post('ptValue'),
            'FTSessionID'       => $this->session->userdata('tSesSessionID'),
            'tSearchSpaPdtPri'  => $this->session->userdata('tSearchSpaPdtPri')
        );
        $aResDel    = $this->mSalePriceAdj->FSaMSPAUpdatePriceTemp($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc'],
        );
        echo json_encode($aReturn);
    }

    public function FSoCSPAUpdatePunTemp(){
        $aDataMaster    = array(
            'FTXthDocNo'        => $this->input->post('FTXthDocNo'),
            'tSeq'              => $this->input->post('tSeq'),
            'tValue'            => $this->input->post('ptValue'),
            'FTSessionID'       => $this->session->userdata('tSesSessionID')
        );
        $aResDel    = $this->mSalePriceAdj->FSaMSPAUpdatePunTemp($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc'],
        );
        echo json_encode($aReturn);
    }

    //Function : Adv Table Show
    public function FSvCSPAAdvTblShowColList(){
        $aAvailableColumn   = FCNaDCLAvailableColumn('TCNTPdtAdjPriDT');
        $aData['aAvailableColumn'] = $aAvailableColumn;
        $this->load->view('document/salepriceadj/advancetable/wSalePriceAdjTableShowColList', $aData);
    }

    //Function : Adv Table Save
    public function FSvCSPAShowColSave(){
        FCNaDCLSetShowCol('TCNTPdtAdjPriDT', '', '');
        $aColShowSet        = $this->input->post('aColShowSet');
        $aColShowAllList    = $this->input->post('aColShowAllList');
        $aColumnLabelName   = $this->input->post('aColumnLabelName');
        $nStaSetDef         = $this->input->post('nStaSetDef');
        if ($nStaSetDef == 1) {
            FCNaDCLSetDefShowCol('TCNTPdtAdjPriDT');
        } else {
            for ($i = 0; $i < FCNnHSizeOf($aColShowSet); $i++) {
                FCNaDCLSetShowCol('TCNTPdtAdjPriDT', 1, $aColShowSet[$i]);
            }
        }
        //Reset Seq
        FCNaDCLUpdateSeq('TCNTPdtAdjPriDT', '', '', '');
        $q = 1;
        for ($n = 0; $n < FCNnHSizeOf($aColShowAllList); $n++) {
            FCNaDCLUpdateSeq('TCNTPdtAdjPriDT', $aColShowAllList[$n], $q, $aColumnLabelName[$n]);
            $q++;
        }
    }

    //Function : Display Original Price 4PDT
    public function FSoCSPAOriginalPrice(){
        $tFTPdtCode     = $this->input->post('ptFTPdtCode');
        $tFTPdtName     = $this->input->post('ptFTPdtName');
        $tFTPunCode     = $this->input->post('ptFTPunCode');
        $tTable         = $this->input->post('ptTable');
        $tField         = $this->input->post('ptField');
        $tPplCode       = $this->input->post('ptFTPplCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TCNMPdt_L');
        $nLangHave      = FCNnHSizeOf($aLangHave);
        $tPplBrowseCode = $this->input->post('tPplCode');
        $tPplBrowseName = $this->input->post('tPplName');
        if ($nLangHave > 1) {
            $nLangEdit  = ($nLangEdit != '') ? $nLangEdit : $nLangResort;
        } else {
            $nLangEdit  = (@$aLangHave[0]->nLangList == '') ? '1' : $aLangHave[0]->nLangList;
        }
        $aWherePriceList    = array(
            'pnLngID'       => $nLangEdit,
            'ptPdtCode'     => $tFTPdtCode,
            'ptPunCode'     => $tFTPunCode,
            'ptTable'       => $tTable,
            'ptField'       => $tField,
            'tPplCode'      => $tPplCode
        );
        $aPdtData4PDT   = $this->mSalePriceAdj->FSaMSPADataPrice4Pdt($aWherePriceList);
        $nOptDecimal    = get_cookie('tOptDecimalShow');
        $aGenTable      = array(
            'ptTable'         => $aWherePriceList,
            'aPdtData4PDT'    => $aPdtData4PDT['aItems'][0],
            'nOptDecimal'     => $nOptDecimal,
            'nStaEvent'       => $aPdtData4PDT['nStaEvent'],
            'tStaMessg'       => $aPdtData4PDT['tStaMessg'],
            'tSQL'            => $aPdtData4PDT['tSQL'],
            'tPplCode' =>  $tPplBrowseCode,
            'tPplName' =>  $tPplBrowseName,
            'tFTPdtCode' =>  $tFTPdtCode,
            'tFTPdtName' =>  $tFTPdtName
        );
        $this->load->view('document/salepriceadj/advancetable/wSalePriceAdjTableShowOriginalPrice', $aGenTable);
    }

    //Function : Adjust Product Price in DocTemp
    public function FSoCSPAPdtPriAdjustEvent(){
        $tDocNo       = $this->input->post('tDocNo');
        $tStaAdj      = $this->input->post('tStaAdj');
        $tChangePrice = $this->input->post('tChangePrice');
        $tValue       = $this->input->post('tValue');
        $aDataMaster = array(
            'FTXphDocNo'        => $this->input->post('tDocNo'),
            'ChangePriceType'   => $this->input->post('tChangePrice'),
            'FTSessionID'       => $this->session->userdata('tSesSessionID')
        );
        $aTempData      = $this->mSalePriceAdj->FSaMSPAGetDataFromTemp($aDataMaster);
        $nCountTempData = FCNnHSizeOf($aTempData);
        for ($i = 0; $i < $nCountTempData; $i++) {
            switch ($tStaAdj) {
                case "1": //New Price
                    switch ($tChangePrice) {
                        case "1": //ปรับทั้งหมด
                            $aTempData[$i]['FCXtdPriceRet'] = $tValue;
                            $aTempData[$i]['FCXtdPriceWhs'] = $tValue;
                            $aTempData[$i]['FCXtdPriceNet'] = $tValue;
                            $tPrice = "All";
                            break;
                        case "2": //ราคาปลีก
                            $aTempData[$i]['FCXtdPriceRet'] = $tValue;
                            $tPrice = "FCXtdPriceRet";
                            $tValue = $aTempData[$i]['FCXtdPriceRet'];
                            break;
                        case "3": //ราคาส่ง
                            $aTempData[$i]['FCXtdPriceWhs'] = $tValue;
                            $tPrice = "FCXtdPriceWhs";
                            $tValue = $aTempData[$i]['FCXtdPriceWhs'];
                            break;
                        case "4": //ราคาออนไลน์
                            $aTempData[$i]['FCXtdPriceNet'] = $tValue;
                            $tPrice = "FCXtdPriceNet";
                            $tValue = $aTempData[$i]['FCXtdPriceNet'];
                            break;
                    }
                    break;
                case "2": //ปรับลด %
                    switch ($tChangePrice) {
                        case "1": //ปรับทั้งหมด
                            $aTempData[$i]['FCXtdPriceRet'] = ($aTempData[$i]['FCXtdPriceRet'] * (100 - $tValue) / 100);
                            $aTempData[$i]['FCXtdPriceWhs'] = ($aTempData[$i]['FCXtdPriceWhs'] * (100 - $tValue) / 100);
                            $aTempData[$i]['FCXtdPriceNet'] = ($aTempData[$i]['FCXtdPriceNet'] * (100 - $tValue) / 100);
                            $tPrice = "All";
                            break;
                        case "2": //ราคาปลีก
                            $aTempData[$i]['FCXtdPriceRet'] = ($aTempData[$i]['FCXtdPriceRet'] * (100 - $tValue) / 100);
                            $tPrice = "FCXtdPriceRet";
                            $tValue = $aTempData[$i]['FCXtdPriceRet'];
                            break;
                        case "3": //ราคาส่ง
                            $aTempData[$i]['FCXtdPriceWhs'] = ($aTempData[$i]['FCXtdPriceWhs'] * (100 - $tValue) / 100);
                            $tPrice = "FCXtdPriceWhs";
                            $tValue = $aTempData[$i]['FCXtdPriceWhs'];
                            break;
                        case "4": //ราคาออนไลน์
                            $aTempData[$i]['FCXtdPriceNet'] = ($aTempData[$i]['FCXtdPriceNet'] * (100 - $tValue) / 100);
                            $tPrice = "FCXtdPriceNet";
                            $tValue = $aTempData[$i]['FCXtdPriceNet'];
                            break;
                    }
                    break;
                case "3": //ปรับลด มูลค่า
                    switch ($tChangePrice) {
                        case "1": //ปรับทั้งหมด
                            $aTempData[$i]['FCXtdPriceRet'] = $aTempData[$i]['FCXtdPriceRet'] - $tValue;
                            $aTempData[$i]['FCXtdPriceWhs'] = $aTempData[$i]['FCXtdPriceWhs'] - $tValue;
                            $aTempData[$i]['FCXtdPriceNet'] = $aTempData[$i]['FCXtdPriceNet'] - $tValue;
                            $tPrice = "All";
                            break;
                        case "2": //ราคาปลีก
                            $aTempData[$i]['FCXtdPriceRet'] = $aTempData[$i]['FCXtdPriceRet'] - $tValue;
                            $tPrice = "FCXtdPriceRet";
                            $tValue = $aTempData[$i]['FCXtdPriceRet'];
                            break;
                        case "3": //ราคาส่ง
                            $aTempData[$i]['FCXtdPriceWhs'] = $aTempData[$i]['FCXtdPriceWhs'] - $tValue;
                            $tPrice = "FCXtdPriceWhs";
                            $tValue = $aTempData[$i]['FCXtdPriceWhs'];
                            break;
                        case "4": //ราคาออนไลน์
                            $aTempData[$i]['FCXtdPriceNet'] = $aTempData[$i]['FCXtdPriceNet'] - $tValue;
                            $tPrice = "FCXtdPriceNet";
                            $tValue = $aTempData[$i]['FCXtdPriceNet'];
                            break;
                    }
                    break;
                case "4": //ปรับเพิ่ม %
                    switch ($tChangePrice) {
                        case "1": //ปรับทั้งหมด
                            $aTempData[$i]['FCXtdPriceRet'] = ($aTempData[$i]['FCXtdPriceRet'] * (100 + $tValue) / 100);
                            $aTempData[$i]['FCXtdPriceWhs'] = ($aTempData[$i]['FCXtdPriceWhs'] * (100 + $tValue) / 100);
                            $aTempData[$i]['FCXtdPriceNet'] = ($aTempData[$i]['FCXtdPriceNet'] * (100 + $tValue) / 100);
                            $tPrice = "All";
                            break;
                        case "2": //ราคาปลีก
                            $aTempData[$i]['FCXtdPriceRet'] = ($aTempData[$i]['FCXtdPriceRet'] * (100 + $tValue) / 100);
                            $tPrice = "FCXtdPriceRet";
                            $tValue = $aTempData[$i]['FCXtdPriceRet'];
                            break;
                        case "3": //ราคาส่ง
                            $aTempData[$i]['FCXtdPriceWhs'] = ($aTempData[$i]['FCXtdPriceWhs'] * (100 + $tValue) / 100);
                            $tPrice = "FCXtdPriceWhs";
                            $tValue = $aTempData[$i]['FCXtdPriceWhs'];
                            break;
                        case "4": //ราคาออนไลน์
                            $aTempData[$i]['FCXtdPriceNet'] = ($aTempData[$i]['FCXtdPriceNet'] * (100 + $tValue) / 100);
                            $tPrice = "FCXtdPriceNet";
                            $tValue = $aTempData[$i]['FCXtdPriceNet'];
                            break;
                    }
                    break;
                case "5": //ปรับเพิ่ม มูลค่า
                    switch ($tChangePrice) {
                        case "1": //ปรับทั้งหมด
                            $aTempData[$i]['FCXtdPriceRet'] = $aTempData[$i]['FCXtdPriceRet'] + $tValue;
                            $aTempData[$i]['FCXtdPriceWhs'] = $aTempData[$i]['FCXtdPriceWhs'] + $tValue;
                            $aTempData[$i]['FCXtdPriceNet'] = $aTempData[$i]['FCXtdPriceNet'] + $tValue;
                            $tPrice = "All";
                            break;
                        case "2": //ราคาปลีก
                            $aTempData[$i]['FCXtdPriceRet'] = $aTempData[$i]['FCXtdPriceRet'] + $tValue;
                            $tPrice = "FCXtdPriceRet";
                            $tValue = $aTempData[$i]['FCXtdPriceRet'];
                            break;
                        case "3": //ราคาส่ง
                            $aTempData[$i]['FCXtdPriceWhs'] = $aTempData[$i]['FCXtdPriceWhs'] + $tValue;
                            $tPrice = "FCXtdPriceWhs";
                            $tValue = $aTempData[$i]['FCXtdPriceWhs'];
                            break;
                        case "4": //ราคาออนไลน์
                            $aTempData[$i]['FCXtdPriceNet'] = $aTempData[$i]['FCXtdPriceNet'] + $tValue;
                            $tPrice = "FCXtdPriceNet";
                            $tValue = $aTempData[$i]['FCXtdPriceNet'];
                            break;
                    }
                    break;
            }

            $aDataChangePrice = array(
                'FTXthDocNo'        =>  $tDocNo,
                'FTPdtCode'         =>  $aTempData[$i]['FTPdtCode'],
                'FTPunCode'         =>  $aTempData[$i]['FTPunCode'],
                'FTSessionID'       =>  $this->session->userdata('tSesSessionID'),
                'tPrice'            =>  $tPrice,
                'tValue'            =>  $tValue,
                'FCXtdPriceRet'     =>  $aTempData[$i]['FCXtdPriceRet'],
                'FCXtdPriceWhs'     =>  $aTempData[$i]['FCXtdPriceWhs'],
                'FCXtdPriceNet'     =>  $aTempData[$i]['FCXtdPriceNet'],
                'tColValidate'      => '',
                'tSeq'              => 'N'
            );
            $aEditTempData  = $this->mSalePriceAdj->FSaMSPAUpdatePriceTemp($aDataChangePrice);
        }
        $aReturn    = array(
            'Data'  => $aEditTempData
        );
        echo json_encode($aReturn);
    }

    // Functionality : Event Approve Doc
    // Parameters : Ajax and Function Parameter
    // Creator : 25/03/2019 Napat(Jame)
    // Last Modified : -
    // Return : Status Add Event
    // Return Type : String
    public function FSoCSPAApproveEvent(){
        $tDocNo     = $this->input->post('tDocNo');
        try {

            $tBchCode   = $this->input->post('tBchCode');
            $tDocType   = $this->input->post('tDocType');

            $aDataChkDupDateTime = array(
                'FTXphDocNo'        => $tDocNo,
                'FTSessionID'       => $this->session->userdata('tSesSessionID'),
                'FTXthDocKey'       => 'TCNTPdtAdjPriHD',
                'FDXphDStart'       => $this->input->post('dDateStart'),
                'FTXphTStart'       => $this->input->post('tTimeStart'),
                'FTPplCode'         => $this->input->post('tPplCode')
            );
            if($tDocType == '3' || $tDocType == '4'){
                $aDataUpdate = array(
                    'FTXthDocNo'        => $tDocNo,
                    'FTXphStaPrcDoc'    => '1',
                    'FTXphStaApv'       => '1',
                    'FTXphUsrApv'       => $this->session->userdata('tSesUsername')
                );
                $this->mSalePriceAdj->FSaMSPAApproveStatus($aDataUpdate);

                $aReturn = array(
                    'nStaEvent'    => '1',
                    'tStaMessg'    => "Approve Success",
                    'tType'        => '1',
                    'tCodeReturn'  => $tDocNo,
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบปรับราคาขาย ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $aDataUpdate['FTXphUsrApv']
                );
            }else{
                $aDataUpdate = array(
                    'FTXthDocNo'        => $tDocNo,
                    'FTXphStaPrcDoc'    => '2', //กำลังประมวลผล
                    'FTXphUsrApv'       => $this->session->userdata('tSesUsername')
                );
                $this->mSalePriceAdj->FSaMSPAApprove($aDataUpdate);

                $aMQParams = [
                    "queueName" => "ADJUSTPRICE",
                    "params" => [
                        "ptBchCode"   => $tBchCode,
                        "ptDocNo"     => $tDocNo,
                        "ptDocType"   => '9',
                        "ptUser"      => $this->session->userdata('tSesUsername')
                    ]
                ];
                FCNxCallRabbitMQ($aMQParams);

                $aReturn = array(
                    'nStaEvent'    => '1',
                    'tStaMessg'    => "Success",
                    'tType'        => '2',
                    'tCodeReturn'  => $tDocNo,
                    'tLogType'      => 'INFO',
                    'tDocNo'        => $tDocNo,
                    'tEventName'    => 'อนุมัติใบปรับราคาขาย ',
                    'nLogCode'      => '001',
                    'nLogLevel'     => '',
                    'FTXphUsrApv'   => $aDataUpdate['FTXphUsrApv']
                );
            }

        } catch (\ErrorException $err) {
            // $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => language('common/main/main', 'tApproveFail'),
                'tLogType'      => 'ERROR',
                'tDocNo'        => $tDocNo,
                'tEventName'    => 'อนุมัติใบปรับราคาขาย ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => $aDataUpdate['FTXphUsrApv']
            );
        }
        //ถ้าทำงานเสร็จสิ้นแล้วจะรวบรวม Data เพื่อส่ง MQ_LOG
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }

    /**
     * Functionality : Update Status Doc to Cancel
     * Parameters : Ajax and Function Parameter
     * Creator : 26/03/2019 Napat(Jame)
     * Last Modified : -
     * Return : Status Edit Event
     * Return Type : String
     */
    public function FSoCSPAUpdateStaDocCancel(){
        $FTXphDocNo  = $this->input->post('tDocNo');
        $aDataUpdate = array(
            'FTXphDocNo'    => $FTXphDocNo,
            'FTXphStaDoc'   => '3',
            'FTXphStaApv'   => ''
        );
        $aStaDoc = $this->mSalePriceAdj->FSaMSPAUpdateStaDocCancel($aDataUpdate);
        if ($aStaDoc['rtCode'] == 1) {
            $aReturn    = array(
                'rtCode' => $aStaDoc['rtCode'],
                'rtDesc' => $aStaDoc['rtDesc'],
                'tLogType'      => 'INFO',
                'tDocNo'        => $FTXphDocNo,
                'tEventName'    => 'ยกเลิกใบปรับราคาขาย ',
                'nLogCode'      => '001',
                'nLogLevel'     => '',
                'FTXphUsrApv'   => ''
            );
        }else{
            $aReturn    = array(
                'rtCode' => $aStaDoc['rtCode'],
                'rtDesc' => $aStaDoc['rtDesc'],
                'tLogType'      => 'ERROR',
                'tDocNo'        => $FTXphDocNo,
                'tEventName'    => 'ยกเลิกใบปรับราคาขาย ',
                'nLogCode'      => '500',
                'nLogLevel'     => 'Critical',
                'FTXphUsrApv'   => ''
            );
        }
        FSoCCallLogMQ($aReturn);
        echo json_encode($aReturn);
    }


}
