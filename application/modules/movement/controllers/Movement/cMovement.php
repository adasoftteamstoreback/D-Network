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
date_default_timezone_set("Asia/Bangkok");

class cMovement extends MX_Controller{
     /**
     * ภาษา
     * @var array
     */
    public $aText = [];

    /**
     * จำนวนต่อหน้าในรายงาน
     * @var int
     */
    public $nPerPage = 100;

    /**
     * Page number
     * @var int
     */
    public $nPage = 1;

    /**
     * จำนวนทศนิยม
     * @var int
     */
    public $nOptDecimalShow = 2;

    /**
     * จำนวนข้อมูลใน Temp
     * @var int
     */
    public $nRows = 0;

    /**
     * Computer Name
     * @var string
     */
    public $tCompName;

    /**
     * User Login on Bch
     * @var string
     */
    public $tBchCodeLogin;

    /**
     * Report Code
     * @var string
     */
    public $tRptCode;

    /**
     * Report Group
     * @var string
     */
    public $tRptGroup;

    /**
     * System Language
     * @var int
     */
    public $nLngID;

    /**
     * User Session ID
     * @var string
     */
    public $tUserSessionID;

    /**
     * Report route
     * @var string
     */
    public $tRptRoute;

    /**
     * Report Export Type
     * @var string
     */
    public $tRptExportType;

    /**
     * Filter for Report
     * @var array
     */
    public $aRptFilter = [];

    /**
     * Company Info
     * @var array
     */
    public $aCompanyInfo = [];

    /**
     * User Login Session
     * @var string
     */
    public $tUserLoginCode;

    /**
     * Sys Bch Code
     * @var string
     */
    public $tSysBchCode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Movement/Movement/mMovement');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nMovementType, $tMovementOption){
        $this->load->view('Movement/Movement/wMovement', array(
            'nMovementType'     => $nMovementType,
            'tMovementOption'   => $tMovementOption,
        ));
    }

    public function FSxMmtContentTab(){
        $this->load->view('Movement/Movement/wMmtContentTab');
    }

    //Functionality : Function Call Movement Page List
    //Parameters    : Ajax and Function Parameter
    //Creator       :  10/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return        : String View
    //Return Type   : View
    public function FSvCMovementListPage(){
        $tMemCrdStartMonth  = date('m');
        $tMemCrdYear        = date('Y');
        $aRrayMonth         = array(
            '01' => language('movement/movement/movement', 'tMMTJan') ,
            '02' => language('movement/movement/movement', 'tMMTFeb') ,
            '03' => language('movement/movement/movement', 'tMMTMar') ,
            '04' => language('movement/movement/movement', 'tMMTApr') ,
            '05' => language('movement/movement/movement', 'tMMTMay') ,
            '06' => language('movement/movement/movement', 'tMMTJune') ,
            '07' => language('movement/movement/movement', 'tMMTJuly') ,
            '08' => language('movement/movement/movement', 'tMMTAug') ,
            '09' => language('movement/movement/movement', 'tMMTSept') ,
            '10' => language('movement/movement/movement', 'tMMTOct') ,
            '11' => language('movement/movement/movement', 'tMMTNov') ,
            '12' => language('movement/movement/movement', 'tMMTDec') ,
        );
        $this->load->view('Movement/Movement/wMovementList', array(
            'aRrayMonth'        => $aRrayMonth,
            'tMemCrdStartMonth' => $tMemCrdStartMonth,
            'tMemCrdYear'       => $tMemCrdYear,
        ));
        unset($tMemCrdStartMonth,$tMemCrdYear,$aRrayMonth);
    }

    //Functionality : Function Call DataTables Movement
    //Parameters    : Ajax Call View DataTable
    //Creator       : 11/03/2020 Saharat(Golf)
    //Last Modified : 15/04/2020 surawat
    //Return        : String View
    //Return Type   : View
    public function FSvCMovementDataList(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $tDataSearch        = $this->input->post('tDataFilter');
        $tSearchAll         = json_decode($tDataSearch, true);
        $nOptDecimalShow    = get_cookie('tOptDecimalShow');
        $aData = array(
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll
        );
        $aMovementDataList  = $this->mMovement->FSaMMovementList($aData);
        $aGenTable  = array(
            'aDataList'         => $aMovementDataList,
            'nOptDecimalShow'   => $nOptDecimalShow
        );
        $this->load->view('Movement/Movement/wMovementDataTable', $aGenTable);
        unset($nLangEdit,$tDataSearch,$tSearchAll,$nOptDecimalShow,$aData,$aMovementDataList,$aGenTable);
    }


    // Functionality    : Render Excel Report
    // Parameters       :  Function Parameter
    // Creator          : 09/07/2021 Phaksaran(Golf)
    // LastUpdate       :
    // Return           : file
    // ReturnType       : file
    public function FSvCMovementRenderExcel(){
        $nLangEdit      = $this->session->userdata("tLangEdit"); //รับค่า tLangEdit
        $tDataSearch    = $this->session->userdata('tDataFilter');  //รับค่า tLangEdit
        $tSearchAll     = $tDataSearch; //รับค่า $tDataSearc
        $writer         = WriterEntityFactory::createXLSXWriter(); // เรียกฟังชั่นสร้างไฟล์excel จาก Libraries Spout
        $fileName       = language('movement\movement\movement', 'tMovementMain').'_'.date('Y-m-d-H-i-s').'.xlsx'; // กำหนดชื่อไฟล์
        $writer->openToBrowser($fileName); // สร้างไฟล์าำหรับ Download  โดยนำ ชื่อมาจากตัวแปร  $fileName
        $oStyle         = (new StyleBuilder())  // กำหนดรูปแบบของตารางและฟอนต์
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMovementMain')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle);  // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyle
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
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement','tMMTMovementDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTMovementTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang
        ];

        $aMulltiRow = WriterEntityFactory::createRow($aCells,$oStyle2); // เก็บข้อมูล และรูปแบบจากตัวแปร  $aCells $oStyleq2
        $writer->addRow($aMulltiRow); //เพิ่มข้อมูลลงในตาราง Excel จากตัวแปร $aMulltiRow
        $oBorder = (new BorderBuilder()) // สร้างคำสั่งกำหนดเส้นขอบตาราง
        ->setBorderTop(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางบน
        ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN) //กำหนดสีของเส้นขอบตารางล่าง
        ->build();  //สร้างตารางจากข้อมูลด้านบน

        $oStyleColums = (new StyleBuilder()) //สร้างคำสั่งกำหนดเส้นขอบตาราง
        ->setBorder($oBorder) //กำหนดรูปแบบตัวอังษร
        ->setFontBold() //กำหนดขนาดตัวอักษร
        ->build(); //สร้างตารางจากข้อมูลด้านบน
        $cells  = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementNum')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementId')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementName')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementDate')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementDocument')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementInv')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementToplift')),//รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementIn')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementOut')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementSell')),//รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementReturn')),//รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementAmend')), //รับค่าจาก  lang
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTMovementStayInv')), //รับค่าจาก  lang
        ];
        $aMulltiRow  = WriterEntityFactory::createRow($cells , $oStyleColums ); //  รับข้อมูล Row จากข้อมูล $cells
        $writer->addRow($aMulltiRow); // สร้าง Row

        //Get Data
        $aData  = array(
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,
        );
        $aDataReport = $this->mMovement->FSaMMovementList($aData);

        if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
            $nRowID     = 1;
            foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($nRowID),
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    WriterEntityFactory::createCell($aValue['FDStkDate']),
                    WriterEntityFactory::createCell($aValue['FTStkDocNo']),
                    WriterEntityFactory::createCell($aValue['FTWahName']),
                    WriterEntityFactory::createCell($aValue['FCStkMonthEnd']),
                    WriterEntityFactory::createCell($aValue['FCStkIN']),
                    WriterEntityFactory::createCell($aValue['FCStkOUT']),
                    WriterEntityFactory::createCell($aValue['FCStkSale']),
                    WriterEntityFactory::createCell($aValue['FCStkReturn']),
                    WriterEntityFactory::createCell($aValue['FCStkAdjust']),
                    WriterEntityFactory::createCell($aValue['FCStkQtyInWah']),
                ];
                $aMulltiRow  = WriterEntityFactory::createRow($values);
                $writer->addRow($aMulltiRow);
                $nRowID++;
            }
        }
        $writer->close();
        unset($nLangEdit,$tDataSearch,$tSearchAll,$writer,$fileName,$oStyle,$oStyle2,$aCells,$aMulltiRow,$oStyleColums,$cells,$aData,$nRowID);
    }








}
