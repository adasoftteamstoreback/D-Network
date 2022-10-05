<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cPdtLot extends MX_Controller {

    public $tRouteMenu  = 'maslot/0/0';

    public function __construct(){
        parent::__construct ();
        $this->load->model('service/pdtlot/mPdtLot');
        date_default_timezone_set("Asia/Bangkok");
        // Test XSS Load Helper Security
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nLotBrowseType,$tLotBrowseOption){ // เช็คหน้าว่าถูกเรียกผ่านการ Browse หรือผ่าน Route
        $nMsgResp   = array('title'=>"Product Lot");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave               = FCNaHBtnSaveActiveHTML($this->tRouteMenu); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventPdtLot	    = FCNaHCheckAlwFunc($this->tRouteMenu);

        $this->load->view('service/pdtlot/wPdtLot', array (
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nLotBrowseType'    => $nLotBrowseType,
            'tLotBrowseOption'  => $tLotBrowseOption,
            'aAlwEventPdtLot'  => $aAlwEventPdtLot
        ));
    }

    public function FSvCLotListPage(){ //load view มาแสดง
        $aAlwEventPdtLot       = FCNaHCheckAlwFunc($this->tRouteMenu);
        $this->load->view('service/pdtlot/wPdtlotList', array(
            'aAlwEventPdtLot'  => $aAlwEventPdtLot
        ));
    }

    public function FSvCLotDataList(){ //ดึง database ออกมาแสดงที่หน้า view(wPdtLotDataTable) ทั้งแบบไม่มีการค้นหาหรือมีการค้นหา
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll,
            );
            $aLotDataList           = $this->mPdtLot->FSaMLotList($aData);
            $aAlwEventPdtLot       = FCNaHCheckAlwFunc($this->tRouteMenu);
            // $aAlwEventPdtLot       = FCNaHCheckAlwFunc('reason/0/0');

            $aGenTable  = array(
                'aLotDataList'      => $aLotDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll,
                'aAlwEventPdtLot'  => $aAlwEventPdtLot
            );
            $this->load->view('service/pdtlot/wPdtLotDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSvCLotAddPage(){ //load view หน้าเพิ่มข้อมูล
        try{
            $aDataPdtLot = array(
                'nStaAddOrEdit'   => 99,
                'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName")
            );
            $this->load->view('service/pdtlot/wPdtLotAdd', $aDataPdtLot);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoCLotAddEvent(){  // event insert ข้อมูล ลง database
        try{
            $tStatus = $this->input->post('ocbLotStatus');
            $tLotNo   = $this->input->post("ocbLotAutoGenCode");
            if($tStatus == ''){
                $tStatus = '0';
            }
            $aDataPdtLot   = array(
                'FTLotNo' => $this->input->post('oetLotCode'),
                'FTLotStaUse'  => $tStatus,
                'FTLotYear'  => $this->input->post('oetLOTYear'),
                'FTLotRemark'  => $this->input->post('otaLotRmk'),
                'FDCreateOn'  => date('Y-m-d H:i:s'),
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
                'FTAgnCode'  => $this->input->post('oetLotAgnCode'),
                'FTLotBatchNo'  => $this->input->post('oetLotBatchNo')
            );
            if($tLotNo == '1'){ // Check Auto Gen Reason Code?
                // Update new gencode
                // 15/05/2020 Napat(Jame)
                $aStoreParam = array(
                    "tTblName"    => 'TCNMLot',
                    "tDocType"    => 0,
                    "tBchCode"    => "",
                    "tShpCode"    => "",
                    "tPosCode"    => "",
                    "dDocDate"    => date("Y-m-d")
                );
                $aAutogen                 = FCNaHAUTGenDocNo($aStoreParam);
                $aDataPdtLot["FTLotNo"]   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup      = $this->mPdtLot->FSnMLOTCheckDuplicate($aDataPdtLot['FTLotNo']);
            $nStaDup        = $oCountDup['counts'];
            if($oCountDup !== FALSE && $nStaDup == 0){
                $this->db->trans_begin();
                $aStaLotMaster  = $this->mPdtLot->FSaMLOTAddUpdateMaster($aDataPdtLot);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Product Lot"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'   => $aDataPdtLot['FTLotNo'],
                        'nStaEvent'     => '1',
                        'tStaMessg'     => 'Success Add Lot'
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoCLotEditEvent(){  // event update ข้อมูลลง database
        try{
            $tStatus = $this->input->post('ocbLotStatus');
            if($tStatus == ''){
                $tStatus = '0';
            }
            $this->db->trans_begin();
            $aDataPdtLot   = array(
                'FTLotNo' => $this->input->post('oetLotCode'),
                'FTLotStaUse'  => $tStatus,
                'FTLotYear'  => $this->input->post('oetLOTYear'),
                'FTLotRemark'  => $this->input->post('otaLotRmk'),
                'FDCreateOn'  => date('Y-m-d H:i:s'),
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
                'FTAgnCode'  => $this->input->post('oetLotAgnCode'),
                'FTLotBatchNo'  => $this->input->post('oetLotBatchNo')
            );
            $aStaLotMaster  = $this->mPdtLot->FSaMLOTAddUpdateMaster($aDataPdtLot);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Product Lot"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataPdtLot['FTLotNo'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Edit Lot'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSvCLotEditPage(){  //load หน้า edit ขึ้นมาแสดงผลที่หน้า view
        try{
            $tLotNo       = $this->input->post('tLotNo');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");

            $aData  = array(
                'FTLotNo'   => $tLotNo,
                'FNLngID'   => $nLangEdit
            );

            $aLotData       = $this->mPdtLot->FSaMLotGetDataByID($aData);
            $aDataPdtLot   = array(
                'nStaAddOrEdit' => 1,
                'aLotData'      => $aLotData
            );
            $this->load->view('service/pdtlot/wPdtLotAdd',$aDataPdtLot);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSoCLotDeleteEvent(){ // event delete ข้อมูลทั้งได้ทั้ง single และ multi
        $aLotNo = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTLotNo' => $aLotNo
        );
        $aResDel    = $this->mPdtLot->FSaMLOTDelAll($aDataMaster);
        $nNumRowLot = $this->mPdtLot->FSnMLOTGetAllNumRow();
        if($nNumRowLot!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['tCode'],
                'tStaMessg' => $aResDel['tDesc'],
                'nNumRowLot' => $nNumRowLot
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }

    public function FSvCLotBAMDataTable()
    {
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tDotNo = $this->input->post('tDotNo');

            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll,
                'tDotNo'        => $tDotNo
            );
            $aLotBAMDataList       = $this->mPdtLot->FSaMDotBAMList($aData);

            $aAlwEventPdtLot       = FCNaHCheckAlwFunc($this->tRouteMenu);

            $aGenTable  = array(
                'aLotDataList'      => $aLotBAMDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll,
                'aAlwEventPdtLot'   => $aAlwEventPdtLot,
                'tLotNo'            => $tDotNo
            );

            $this->load->view('service/pdtlot/pdtdottab2/wPdtDotBAMDatatable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
        
    }

    public function FSvCLotBAMDeleteEvent()
    {
        $tLotNo      = $this->input->post('tPdtLotCode');
        $tLotBrandNo = $this->input->post('tPdtLotBrandCode');
        $tLotModelNo = $this->input->post('tPdtLotModelCode');

        $aDataMaster = array(
            'FTLotNo' => $tLotNo,
            'FTPbnCode' => $tLotBrandNo,
            'FTPmoCode' => $tLotModelNo
        );

        $aResDel    = $this->mPdtLot->FSaMLOTBAMDelAll($aDataMaster);
        $nNumRowLot = $this->mPdtLot->FSnMLOTGetAllNumRow();
        if($nNumRowLot!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['tCode'],
                'tStaMessg' => $aResDel['tDesc'],
                'nNumRowLot' => $nNumRowLot,
                'tLotNo'   => $tLotNo
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
        
    }

    public function FSvCLotBAMAddPage(){ //load view หน้าเพิ่มข้อมูล
        try{
            $aDataPdtLotBAM = array(
                'nStaAddOrEdit'     => 99,
                'tDotCode'          => $this->input->post('tDotCode')
            );
            $this->load->view('service/pdtlot/pdtdottab2/wPdtDotBAMPageAdd', $aDataPdtLotBAM);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSvCLotBAMAddEvent()
    {
        try{
            $tDotNo = $this->input->post('oetLotCode');
            $tBrandNo = $this->input->post('oetLotBrandCode');
            $tModelNo = $this->input->post('oetLotModelCode');

            $aDataMaster = array(
                'FTLotNo'    => $tDotNo,
                'FTPbnCode'  => $tBrandNo,
                'FTPmoCode'  => $tModelNo,
                'FDCreateOn'  => date('Y-m-d H:i:s'),
                'FDLastUpdOn' => date('Y-m-d H:i:s'),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FTLastUpdBy' => $this->session->userdata('tSesUsername'),
            );

            $oCountDup      = $this->mPdtLot->FSnMDotBAMCheckDuplicate($aDataMaster);
            $nStaDup        = $oCountDup['counts'];
            if($oCountDup !== FALSE && $nStaDup == 0){
                $this->db->trans_begin();
                $aInsertDotBAM  = $this->mPdtLot->FSaMDotBAMAddUpdateMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Product Lot"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'   => $aDataMaster['FTLotNo'],
                        'nStaEvent'     => '1',
                        'tStaMessg'     => 'Success Add Lot'
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSvCLotBAMDeleteEventMulti()
    {
        $aIDCode = $this->input->post('tIDCode');

        for ($i=0; $i < count($aIDCode); $i++) { 
            $aGroupData = $aIDCode[$i];
            $aData = explode(' , ',$aGroupData);
            $tDotNo = $aData[0];
            $tBrandNo = $aData[1];
            $tModelNo = $aData[2];

            $aDataMaster = array(
                'FTLotNo'    => $tDotNo,
                'FTPbnCode'  => $tBrandNo,
                'FTPmoCode'  => ($tModelNo == 'Dummy')? '': $tModelNo
            ); 
            $aResDel = $this->mPdtLot->FSaMLOTBAMDelAll($aDataMaster);
        }

        $nNumRowLot = $this->mPdtLot->FSnMLOTGetAllNumRow();
        if($nNumRowLot!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['tCode'],
                'tStaMessg' => $aResDel['tDesc'],
                'nNumRowLot' => $nNumRowLot,
                'tLotNo'   => $aDataMaster['FTLotNo']
            );

            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }

    public function FSvCLotBAMEditPage(){  //load หน้า edit ขึ้นมาแสดงผลที่หน้า view
        try{
            $tLotNo         = $this->input->post('tLotNo');
            $tBrandNo       = $this->input->post('tBrandNo');
            $tModelNo       = $this->input->post('tModelNo');
            $nLangEdit      = $this->session->userdata("tLangEdit");

            $aData  = array(
                'FTLotNo'   => $tLotNo,
                'FTPbnCode'   => $tBrandNo,
                'FTPmoCode'   => $tModelNo,
                'FNLngID'   => $nLangEdit
            );

            $aLotData       = $this->mPdtLot->FSaMLotBAMGetDataByID($aData);
            $aDataPdtLot   = array(
                'nStaAddOrEdit' => 1,
                'aLotData'      => $aLotData
            );
            $this->load->view('service/pdtlot/pdtdottab2/wPdtDotBAMPageAdd',$aDataPdtLot);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    public function FSvCLotBAMEditEvent(){  // event update ข้อมูลลง database
        try{
            $tDotNo = $this->input->post('oetLotCode');
            $tBrandNo = $this->input->post('oetLotBrandCode');
            $tModelNo = $this->input->post('oetLotModelCode');

            $aDataMaster   = array(
                'FTLotNo'       => $tDotNo,
                'FTPbnCode'     => $tBrandNo,
                'FTPmoCode'     => $tModelNo,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
            );

            $this->db->trans_begin();
            $aStaLotMaster  = $this->mPdtLot->FSaMDotBAMAddUpdateMaster($aDataMaster);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Product Lot"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTLotNo'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Edit Lot'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }
}
