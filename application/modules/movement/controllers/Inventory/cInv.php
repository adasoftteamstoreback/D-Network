<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

include APPPATH . 'libraries/spout-3.1.0/src/Spout/Autoloader/autoload.php';
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
date_default_timezone_set("Asia/Bangkok");
class cInv extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('Movement/Inventory/mInv');
        date_default_timezone_set("Asia/Bangkok");
    }


    public function index($nMovementType,$tMovementOption){
        $this->load->view ( 'Movement/Inventory/wInventory', array (
            'nMovementType'     =>$nMovementType,
            'tMovementOption'   =>$tMovementOption,
        ));
    }

    /**
     * Functionality : แสดงหน้า list รายการสินค้าคงคลัง
     * Parameters : -
     * Creator : 15/04/2020 surawat
     * Last Modified : -
     * Return : html ฟอร์มค้นหารายการ
     * Return Type : html
     */
    public function FSxCInvPageList(){
        $this->load->view('Movement/Inventory/wInvList');
    }

    /**
     * Functionality : แสดงหน้า list รายการสินค้าคงคลัง
     * Parameters : -
     * Creator : 15/04/2020 surawat
     * Last Modified : -
     * Return : html ฟอร์มค้นหารายการ
     * Return Type : html
     */
    public function FSxCInvDataTableList(){
        try{
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tDataSearch    = $this->input->post('tDataFilter');
            $tSearchAll     = json_decode($tDataSearch, true);
            $aData          = array(
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );
            $aInvDataList   = $this->mInv->FSaMInvList($aData);
            $aGenTable      = array(
                'aDataList'         => $aInvDataList,
                'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            );
            $this->load->view('Movement/Inventory/wInvDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    /**
     * Functionality : แสดงหน้า list รายการสินค้าคงคลัง
     * Parameters : -
     * Creator : 15/04/2020 surawat
     * Last Modified : -
     * Return : html ฟอร์มค้นหารายการ
     * Return Type : html
     */
    public function FSxCInvPdtFhnDataTableList(){
        try{
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nRow           = 10;
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tSearchAll     = $this->input->post('oDataFileter');
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => $nRow,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );
            $aPdtColorSizeActive    = $this->mInv->FSaMInvPdtFhnColorSizeActive($tSearchAll);
            $aDataPdtFhnByRefCode   = array();
            if(!empty($aPdtColorSizeActive)){
                foreach($aPdtColorSizeActive as $nKey => $aPdtClrSze){
                    $aDataPdtFhnByRefCode[$aPdtClrSze['FTFhnRefCode']][] = $aPdtClrSze;
                }
            }
            $aInvDataList   = $this->mInv->FSaMInvPdtFhnList($aData);
            $aGenTable      = array(
                'aDataList'                 => $aInvDataList,
                'aDataPdtFhnByRefCode'      => $aDataPdtFhnByRefCode,
                'nPage'                     => $nPage,
                'nRow'                      => $nRow,
                'tSearchAll'                => $tSearchAll,
                'nOptDecimalShow'           => FCNxHGetOptionDecimalShow()
            );
            $this->load->view('Movement/Inventory/wInvPdtFhnDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    /**
     * Functionality: Render Excel Report
     * Parameters:  Function Parameter
     * Creator: 04/06/2021 Phaksaran(Golf)
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSvCCallInvRenderExcel(){
        $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
        $nRow           = 99999999; // กำหนด Row  ที่แสดง
        $nLangEdit      = $this->session->userdata("tLangEdit"); //รับค่า tLangEdit
        $tDataSearch    = $this->session->userdata('tDataFilter');  //รับค่า tLangEdit
        $tSearchAll     = $tDataSearch; //รับค่า $tDataSearc
        $writer         = WriterEntityFactory::createXLSXWriter(); // เรียกฟังชั่นสร้างไฟล์excel จาก Libraries Spout
        $fileName       = language('movement\movement\movement', 'tMMTInvMain').'_'.date('Y-m-d-H-i-s').'.xlsx'; // กำหนดชื่อไฟล์
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
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvMain')), //รับค่าจาก  lang
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
            WriterEntityFactory::createCell(language('movement\movement\movement','tMMTInvDatePrint'). ' ' . date('d/m/Y') . ' ' . language('movement\movement\movement','tMMTInvTimePrint') . ' ' . date('H:i:s')),//รับค่าจาก  lang
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

        $cells = [ //กำหนดชื่อหัวตาราง แต่ละ คอลลัม
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvId')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvName')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            //WriterEntityFactory::createCell(null),
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvBranch')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvTreasury')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvInventory')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvAudAmount')), //รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
            WriterEntityFactory::createCell(language('movement\movement\movement', 'tMMTInvTotal')),//รับค่าจาก  lang
            //WriterEntityFactory::createCell(null),
        ];
        $aMulltiRow  = WriterEntityFactory::createRow($cells , $oStyleColums ); //  รับข้อมูล Row จากข้อมูล $cells
        $writer->addRow($aMulltiRow); // สร้าง Row

        //Get Data
        $aData = array(
            'nPage'        => $nPage,
            'nRow'         => $nRow,
            'FNLngID'       =>$nLangEdit,
            'tSearchAll'   => $tSearchAll,
        );
        $aDataReport = $this->mInv->FSaMInvList($aData);
        if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
            foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                $values = [
                    WriterEntityFactory::createCell($aValue['FTPdtCode']),
                    //WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTPdtName']),
                    // WriterEntityFactory::createCell(null),
                    // WriterEntityFactory::createCell(null),
                    // WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTBchName']),
                    //WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FTWahName']),
                    //WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FCStkQty']),
                    //WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FCXtdQtyInt']),
                    //WriterEntityFactory::createCell(null),
                    WriterEntityFactory::createCell($aValue['FCXtdQtyBal']),
                    //WriterEntityFactory::createCell(null),
                ];
                $aMulltiRow  = WriterEntityFactory::createRow($values);
                $writer->addRow($aMulltiRow);
            }
        }
        $writer->close();
    }


    /**
     * Functionality: ดึงรายละเอียดข้อมูล การจอง Stock Booking
     * Parameters:  Function Parameter
     * Creator: 04/04/2022 Wasin(Yoshi)
     * LastUpdate:
     * Return: file
     * ReturnType: file
    */
    public function FSvCInvStkBookDetail(){
        try{
            $aDataWhere = [
                'FTBchCode' => $this->input->post('tBchCode'),
                'FTWahCode' => $this->input->post('tWahCode'),
                'FTPdtCode' => $this->input->post('tPdtCode'),
                'FNLngID'   => $this->session->userdata("tLangEdit")
            ];
            $aDataListHD    = $this->mInv->FSaMInvStkBookDetailHD($aDataWhere);
            $aDataBook      = $this->mInv->FSaMInvStkBookDetailDT($aDataWhere);
            if(isset($aDataBook) && !empty($aDataBook) && $aDataBook['rtCode'] == '1'){
                $aDataDT    = [];
                foreach($aDataBook['raItems'] AS $key => $aValBook){
                    $tStkBklDocRef  = $aValBook['FTStbDocRef'];
                    $tStkBklQtyAll  = $aValBook['FCStbQtyAll'];
                    $tStkBklTbl     = $aValBook['FTStbRefKey'];
                    $tStkBklTblName = language('common/main/main', 'tTable'.$tStkBklTbl);
                    // Get Data Table Primery Key
                    $aStkBklTblData = $this->mInv->FSaMInvStkBookGetDataPkTbl($tStkBklTbl);
                    // Where Data Get Document
                    $aDataWhereDoc  = [
                        'tDocName'      => $tStkBklTblName,
                        'tDocNoRef'     => $tStkBklDocRef,
                        'tTblName'      => $tStkBklTbl,
                        'tTblPKField'   => $aStkBklTblData['FTTblPkName'],
                        'tTblRefKey'    => $aStkBklTblData['FTTblRefkey'],
                        'tStkBklQtyAll' => $tStkBklQtyAll
                    ];
                    $aDataDocument  = $this->mInv->FSaMInvStkBookGetDataDoc($aDataWhereDoc);
                    array_push($aDataDT,$aDataDocument);
                }
                $aDataListDT    = array(
                    'raItems'   => $aDataDT,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aDataListDT    = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found',
                );
            }
            $aGenTable  = [
                'aDataListHD'       => $aDataListHD,
                'aDataListDT'       => $aDataListDT,
                'nOptDecimalShow'   => get_cookie('tOptDecimalShow'),
            ];
            $this->load->view('Movement/Inventory/wInvStkBookDetail',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

}
