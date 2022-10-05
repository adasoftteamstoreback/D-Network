<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class logmonitor_controller extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('settingconfig/logmonitor/logmonitor_model');

        // Clean XSS Filtering Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE) {
            echo "ERROR XSS Filter";
        }
    }


    public function index($nLimBrowseType, $tLimBrowseOption)
    {

        $nMsgResp   = array('title' => "Settingconperid");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';

        if (!$isXHR) {
            $this->load->view('common/wHeader', $nMsgResp);
            $this->load->view('common/wTopBar', array('nMsgResp' => $nMsgResp));
            $this->load->view('common/wMenu', array('nMsgResp' => $nMsgResp));
        }

        $vBtnSave       = FCNaHBtnSaveActiveHTML('monLog/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventSettconpreiod  = FCNaHCheckAlwFunc('monLog/0/0');

        $this->load->view('settingconfig/logmonitor/wLogMonitor', array(
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nBrowseType'    => $nLimBrowseType,
            'tBrowseOption'  => $tLimBrowseOption,
            'aAlwEventSettconpreiod' => $aAlwEventSettconpreiod
        ));
    }

    //Functionality : Function Call Page SettingConperiod List
    //Parameters : Ajax and Function Parameter
    //Creator : 07-10-2020 Witsarut (Bell)
    //Return : String View
    //Return Type : View
    public function FSvLOGListPage()
    {
        $aAlwEventSettconpreiod  = FCNaHCheckAlwFunc('monLog/0/0');
        $aNewData     = array(
            'aAlwEventSettconpreiod' => $aAlwEventSettconpreiod
        );

        $this->load->view('settingconfig/logmonitor/wLogMonitorList', $aNewData);
    }


    //Functionality : Function Call View Data SettingConperiod
    //Parameters : Ajax Call View DataTable
    //Creator : 07-10-2020 witsarut 
    //Return : String View
    //Return Type : View
    public function FSvLOGDataList()
    {
        try {
            $aSearchAll = $this->input->post('aSearchAll');
            $nPage      = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit  = $this->session->userdata("tLangEdit");

            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $aSearchAll
            );

            $aDataList = $this->logmonitor_model->FSaMLOGList($aData);
            $aAlwEventSettconpreiod = FCNaHCheckAlwFunc('monLog/0/0'); //Controle Event


            $aGenTable = array(
                'aDataList'     => $aDataList,
                'nPage'         => $nPage,
                'aAlwEvent'     => $aAlwEventSettconpreiod

            );

            $this->load->view('settingconfig/logmonitor/wLogMonitorDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    public function FSvLOGDataListWebView()
    {
        // try {
        //     $aSearchAll = $this->input->post('aSearchAll');
        //     $nPage      = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
        //     $nLangEdit  = $this->session->userdata("tLangEdit");

        //     $aData  = array(
        //         'nPage'         => $nPage,
        //         'nRow'          => 10,
        //         'FNLngID'       => $nLangEdit,
        //         'tSearchAll'    => $aSearchAll
        //     );

        //     $aDataList = $this->logmonitor_model->FSaMLOGList($aData);
        //     $aAlwEventSettconpreiod = FCNaHCheckAlwFunc('monLog/0/0'); //Controle Event


        //     $aGenTable = array(
        //         'aDataList'     => $aDataList,
        //         'nPage'         => $nPage,
        //         'aAlwEvent'     => $aAlwEventSettconpreiod

        //     );
        //     $WebView = '';

        //     $this->load->view($WebView,$aGenTable);
        // } catch (Exception $Error) {
        //     echo $Error;
        // }
    }

    //ฟังก์ชั่นรวบรวม ERROR จากการทำงานของ USER ส่งเข้า MQ_LOG
    public function FSoCLOGPackDataToLogMQ()
    {
        $tDataCode = $this->input->post('tDataCode');
        $tLast = substr($tDataCode, 0, strlen($tDataCode) - 2);
        $tDataCodeWhere =   str_replace(" , ", "','", $tLast);



        $aDataLogClient = $this->logmonitor_model->FSaMLOGGetDataLogClient($tDataCodeWhere);
        // Update FTLogStaSync = 1 ก่อนส่ง (ไม่ต้องก็ได้)

        // print_r($aDataLogClient); die();

        for ($i = 0; $i < count($aDataLogClient['raItems']); $i++) {
            $aMQParams = [
                "queueName" => "CN_QSendToLog",
                "tVhostType" => "LOG",
                "params"    => [
                    'ptFunction'    => $aDataLogClient['raItems'][$i]['FTLogType'], //ประเภท Log INFO WARNING EVENT ERROR
                    'ptSource'      => 'StoreBack',
                    'ptDest'        => 'MQLog',
                    'ptData'        => json_encode([
                        'ptAgnCode' => $aDataLogClient['raItems'][$i]['FTAgnCode'], //ตัวแทนขาย
                        'ptBchCode' => $aDataLogClient['raItems'][$i]['FTBchCode'], //รหัสสาขา
                        'ptPosCode' => $aDataLogClient['raItems'][$i]['FTPosCode'], //รหัสเครื่องจุดขาย
                        'ptShfCode' => $aDataLogClient['raItems'][$i]['FTShfCode'], //รหัสรอบการขาย
                        'ptAppCode' => $aDataLogClient['raItems'][$i]['FTAppCode'], //ต้นทาง (Application) SB,PS,FC,VD,VS
                        'ptAppName' => 'SB', //ต้นทาง (Application) SB,PS,FC,VD,VS
                        'ptMnuCode' => $aDataLogClient['raItems'][$i]['FTMnuCodeRef'], //รหัสเมนู
                        'ptMnuName' => $aDataLogClient['raItems'][$i]['FTMnuName'], //ชื่อเมนู
                        'ptObjCode' => $aDataLogClient['raItems'][$i]['FTPrcCodeRef'], //รหัสหน้าจอ
                        'ptObjName' => $aDataLogClient['raItems'][$i]['FTPrcName'], //ชื่อหน้าจอ/ฟังก์ชั่น
                        'pnLogLevel' => $aDataLogClient['raItems'][$i]['FTLogLevel'], //ระดับ 0:Info 1:Low 2:Medium 3:High 4:Critical
                        'ptLogCode' => $aDataLogClient['raItems'][$i]['FNLogRefCode'], //รหัสอ้างอิง 001:Ok  800:Not Found  900:Fail ....
                        'ptLogDesc' => $aDataLogClient['raItems'][$i]['FTLogDescription'], //รายเอียด Log
                        'ptLogDate' => $aDataLogClient['raItems'][$i]['FDLogDate'], //วันที่ yyyy-MM-dd HH:mm:ss
                        'ptUsrCode' => $aDataLogClient['raItems'][$i]['FTUsrCode'], //รหัสผู้ใช้
                        'ptApvCode' => $aDataLogClient['raItems'][$i]['FTUsrApvCode'], //รหัสผู้อนุมัติ
                    ])
                ]
            ];
            $aStaReturn = FCNxCallRabbitMQ($aMQParams);

            //Update ทีละตัว  FTLogStaSync = '2'
            if ($aStaReturn['rtCode'] == 905) {
                $aReturn = array(
                    'rtCode' => '909',
                    'rtDesc' => 'Send Error'
                );
            } else {
                $tLogCodeWhere =  $aDataLogClient['raItems'][$i]['FNLogCode'];
                $tSQLUpdate = "UPDATE TCNSLogClient
                               SET FTLogStaSync = '2'
                               WHERE FNLogCode = '$tLogCodeWhere'";

                $oQuery = $this->db->query($tSQLUpdate);

                $aReturn = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Send Success'
                );
            }
        }
        echo json_encode($aReturn);
    }

    public function FSoCLOGRenderExcel()
    {
        $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
        $nRow           = 99999999; // กำหนด Row  ที่แสดง
        $nLangResort    = $this->session->userdata("tLangID");  //รับค่า tLangID
        $nLangEdit      = $this->session->userdata("tLangEdit"); //รับค่า tLangEdit
        $tDataSearch    = $this->session->userdata('tDataFilter');  //รับค่า tLangEdit

        $tSearchAll     = $tDataSearch; //รับค่า $tDataSearc

        //Get Data
        $aData = array(
            'nPage'        => $nPage,
            'nRow'         => $nRow,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'   => $tSearchAll,
        );
        if ($tSearchAll['tSearchTab'] == 1) {
            $aDataReport = $this->logmonitor_model->FSaMLOGListAdaLog($aData);

            $writer = WriterEntityFactory::createXLSXWriter(); // เรียกฟังชั่นสร้างไฟล์excel จาก Libraries Spout
            $fileName = language('movement\movement\movement', 'LogรอSync') . '_' . date('Y-m-d-H-i-s') . '.xlsx'; // กำหนดชื่อไฟล์
            $writer->openToBrowser($fileName); // สร้างไฟล์าำหรับ Download  โดยนำ ชื่อมาจากตัวแปร  $fileName



            $oStyle = (new StyleBuilder())  // กำหนดรูปแบบของตารางและฟอนต์
                ->setFontBold() //กำหนดรูปแบบตัวหนา
                ->setFontSize(18) //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน

            $oStyle2 = (new StyleBuilder()) // กำหนดรูปแบบของตารางและฟอนต์2
                ->setFontBold() //กำหนดรูปแบบตัวหนา
                ->setFontSize(12) //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน

            $aCells = [    // สร้างหัวตาราง บรรทัดที่1
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell('Log Monitor'), //รับค่าจาก  lang
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells, $oStyle);  // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyle
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow
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
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvDatePrint') . ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement', 'tMMTInvTimePrint') . ' ' . date('H:i:s')), //รับค่าจาก  lang
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells, $oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow
            $oBorder = (new BorderBuilder()) // สร้างคำสั่งกำหนดเส้นขอบตาราง
                ->setBorderTop(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางบน
                ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางล่าง
                ->build();  //สร้างตารางจากข้อมูลด้านบน

            $oStyleColums = (new StyleBuilder()) //สร้างคำสั่งกำหนดเส้นขอบตาราง
                ->setBorder($oBorder) //กำหนดรูปแบบตัวอังษร
                ->setFontBold() //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน

            if ($tSearchAll['tSearchGroupMonitor'] == 1) {
                $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ผู้ใช้')),
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อหน้าจอ/ฟังก์ชั่น/Event')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิงเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิง')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ประเภท')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ระดับ')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รายละเอียด')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'สาขา')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'จุดขาย')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสรอบ')), //รับค่าจาก  lang
                    //WriterEntityFactory::createCell(null),

                ];

                $aMulltiRow  = WriterEntityFactory::createRow($cells, $oStyleColums); //  รับข้อมูล Row จากข้อมูล $cells
                $writer->addRow($aMulltiRow); // สร้าง Row


                if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $values = [
                            WriterEntityFactory::createCell($aValue['FTUsrCode']),
                            WriterEntityFactory::createCell($aValue['FTPrcName']),
                            WriterEntityFactory::createCell($aValue['FTMnuName']),
                            WriterEntityFactory::createCell($aValue['FTMnuCodeRef']),
                            WriterEntityFactory::createCell($aValue['FNLogRefCode']),
                            WriterEntityFactory::createCell($aValue['FTLogType']),
                            WriterEntityFactory::createCell($aValue['FTLogLevel']),
                            WriterEntityFactory::createCell($aValue['FTLogDescription']),
                            WriterEntityFactory::createCell($aValue['FTBchCode']),
                            WriterEntityFactory::createCell($aValue['FTPosCode']),
                            WriterEntityFactory::createCell($aValue['FTShfCode']),
                            //WriterEntityFactory::createCell(null),
                        ];
                        $aMulltiRow  = WriterEntityFactory::createRow($values);
                        $writer->addRow($aMulltiRow);
                    }
                }
                $writer->close();
            } else if ($tSearchAll['tSearchGroupMonitor'] == 2) {
                $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'แอปพลิเคชั่น')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ผู้ใช้')),
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อหน้าจอ/ฟังก์ชั่น/Event')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิงเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิง')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ประเภท')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ระดับ')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รายละเอียด')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'สาขา')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'จุดขาย')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสรอบ')), //รับค่าจาก  lang
                    //WriterEntityFactory::createCell(null),

                ];

                $aMulltiRow  = WriterEntityFactory::createRow($cells, $oStyleColums); //  รับข้อมูล Row จากข้อมูล $cells
                $writer->addRow($aMulltiRow); // สร้าง Row


                if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $values = [
                            WriterEntityFactory::createCell($aValue['FTAppCode']),
                            WriterEntityFactory::createCell($aValue['FTUsrCode']),
                            WriterEntityFactory::createCell($aValue['FTPrcName']),
                            WriterEntityFactory::createCell($aValue['FTMnuName']),
                            WriterEntityFactory::createCell($aValue['FTMnuCodeRef']),
                            WriterEntityFactory::createCell($aValue['FNLogRefCode']),
                            WriterEntityFactory::createCell($aValue['FTLogType']),
                            WriterEntityFactory::createCell($aValue['FTLogLevel']),
                            WriterEntityFactory::createCell($aValue['FTLogDescription']),
                            WriterEntityFactory::createCell($aValue['FTBchCode']),
                            WriterEntityFactory::createCell($aValue['FTPosCode']),
                            WriterEntityFactory::createCell($aValue['FTShfCode']),
                            //WriterEntityFactory::createCell(null),
                        ];
                        $aMulltiRow  = WriterEntityFactory::createRow($values);
                        $writer->addRow($aMulltiRow);
                    }
                }
                $writer->close();
            } else if ($tSearchAll['tSearchGroupMonitor'] == 3) {
                $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'วันที่')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'แอปพลิเคชั่น')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ผู้ใช้')),
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อหน้าจอ/ฟังก์ชั่น/Event')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิงเมนู')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิง')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ประเภท')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'ระดับ')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รายละเอียด')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'สาขา')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'จุดขาย')), //รับค่าจาก  lang
                    WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสรอบ')), //รับค่าจาก  lang
                    //WriterEntityFactory::createCell(null),

                ];

                $aMulltiRow  = WriterEntityFactory::createRow($cells, $oStyleColums); //  รับข้อมูล Row จากข้อมูล $cells
                $writer->addRow($aMulltiRow); // สร้าง Row


                if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $values = [
                            WriterEntityFactory::createCell($aValue['FDLogDate']),
                            WriterEntityFactory::createCell($aValue['FTAppCode']),
                            WriterEntityFactory::createCell($aValue['FTUsrCode']),
                            WriterEntityFactory::createCell($aValue['FTPrcName']),
                            WriterEntityFactory::createCell($aValue['FTMnuName']),
                            WriterEntityFactory::createCell($aValue['FTMnuCodeRef']),
                            WriterEntityFactory::createCell($aValue['FNLogRefCode']),
                            WriterEntityFactory::createCell($aValue['FTLogType']),
                            WriterEntityFactory::createCell($aValue['FTLogLevel']),
                            WriterEntityFactory::createCell($aValue['FTLogDescription']),
                            WriterEntityFactory::createCell($aValue['FTBchCode']),
                            WriterEntityFactory::createCell($aValue['FTPosCode']),
                            WriterEntityFactory::createCell($aValue['FTShfCode']),
                            //WriterEntityFactory::createCell(null),
                        ];
                        $aMulltiRow  = WriterEntityFactory::createRow($values);
                        $writer->addRow($aMulltiRow);
                    }
                }
                $writer->close();
            }
        } else if ($tSearchAll['tSearchTab'] == 2) {
            $aDataReport = $this->logmonitor_model->FSaMLOGList($aData);

            $writer = WriterEntityFactory::createXLSXWriter(); // เรียกฟังชั่นสร้างไฟล์excel จาก Libraries Spout
            $fileName = language('movement\movement\movement', 'LogMonitor') . '_' . date('Y-m-d-H-i-s') . '.xlsx'; // กำหนดชื่อไฟล์
            $writer->openToBrowser($fileName); // สร้างไฟล์าำหรับ Download  โดยนำ ชื่อมาจากตัวแปร  $fileName



            $oStyle = (new StyleBuilder())  // กำหนดรูปแบบของตารางและฟอนต์
                ->setFontBold() //กำหนดรูปแบบตัวหนา
                ->setFontSize(18) //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน

            $oStyle2 = (new StyleBuilder()) // กำหนดรูปแบบของตารางและฟอนต์2
                ->setFontBold() //กำหนดรูปแบบตัวหนา
                ->setFontSize(12) //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน

            $aCells = [    // สร้างหัวตาราง บรรทัดที่1
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell('Log Monitor'), //รับค่าจาก  lang
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(null),
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells, $oStyle);  // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyle
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow
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
                WriterEntityFactory::createCell(null),
                WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvDatePrint') . ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement', 'tMMTInvTimePrint') . ' ' . date('H:i:s')), //รับค่าจาก  lang
            ];
            $aMulltiRow = WriterEntityFactory::createRow($aCells, $oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2
            $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow
            $oBorder = (new BorderBuilder()) // สร้างคำสั่งกำหนดเส้นขอบตาราง
                ->setBorderTop(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางบน
                ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางล่าง
                ->build();  //สร้างตารางจากข้อมูลด้านบน

            $oStyleColums = (new StyleBuilder()) //สร้างคำสั่งกำหนดเส้นขอบตาราง
                ->setBorder($oBorder) //กำหนดรูปแบบตัวอังษร
                ->setFontBold() //กำหนดขนาดตัวอักษร
                ->build(); //สร้างตารางจากข้อมูลด้านบน



            $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ตัวแทนขาย')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'สาขา')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'จุดขาย')),
                WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสรอบ')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'แอปพลิเคชั่น')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิงเมนู')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อเมนู')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิงหน้าจอ/ฟังก์ชั่น')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ชื่อหน้าจอ/ฟังก์ชั่น/Event')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ประเภท')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ระดับ')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'รหัสอ้างอิง')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'รายละเอียด')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'วันที่')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ผู้ใช้')), //รับค่าจาก  lang
                WriterEntityFactory::createCell(language('movement\movement\movement', 'ผู้อนุมัติ')), //รับค่าจาก  lang
                //WriterEntityFactory::createCell(null),

            ];
            $aMulltiRow  = WriterEntityFactory::createRow($cells, $oStyleColums); //  รับข้อมูล Row จากข้อมูล $cells
            $writer->addRow($aMulltiRow); // สร้าง Row


            if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                    $values = [
                        WriterEntityFactory::createCell($aValue['FTAgnCode']),
                        WriterEntityFactory::createCell($aValue['FTBchCode']),
                        WriterEntityFactory::createCell($aValue['FTPosCode']),
                        WriterEntityFactory::createCell($aValue['FTShfCode']),
                        WriterEntityFactory::createCell($aValue['FTAppCode']),
                        WriterEntityFactory::createCell($aValue['FTMnuCodeRef']),
                        WriterEntityFactory::createCell($aValue['FTMnuName']),
                        WriterEntityFactory::createCell($aValue['FTPrcCodeRef']),
                        WriterEntityFactory::createCell($aValue['FTPrcName']),
                        WriterEntityFactory::createCell($aValue['FTLogType']),
                        WriterEntityFactory::createCell($aValue['FTLogLevel']),
                        WriterEntityFactory::createCell($aValue['FNLogRefCode']),
                        WriterEntityFactory::createCell($aValue['FTLogDescription']),
                        WriterEntityFactory::createCell($aValue['FDLogDate']),
                        WriterEntityFactory::createCell($aValue['FTUsrCode']),
                        WriterEntityFactory::createCell($aValue['FTUsrApvCode']),
                        //WriterEntityFactory::createCell(null),
                    ];
                    $aMulltiRow  = WriterEntityFactory::createRow($values);
                    $writer->addRow($aMulltiRow);
                }
            }
            $writer->close();
        }

        // print_r($aDataReport);
        // die();

    }
}
