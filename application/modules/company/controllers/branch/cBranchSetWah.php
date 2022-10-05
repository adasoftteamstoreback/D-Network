<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cBranchSetWah extends MX_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model('company/branch/mBranchSetWah');
		date_default_timezone_set("Asia/Bangkok");
	}

	//คลังสินค้าของสาขานั้นๆ
	public function index(){
        $aAlwEvent  = FCNaHCheckAlwFunc('branch/0/0');
        $tBchCode   = $this->input->post('tBchCode');
        $tAgnCode   = $this->input->post('tAgnCode');

        $aItem = array(
            'tBchCode'      => $tBchCode,
            'tAgnCode'      => $tAgnCode
        );

        $this->load->view('company/branchsetwah/wBranchsetwah',array(
            'aAlwEvent'  => $aAlwEvent,
            'aItem'      => $aItem
        ));
	}

    //ข้อมูลช่องค้นหา
    public function FSvCWAHList(){
        $aAlwEvent	= FCNaHCheckAlwFunc('branch/0/0');
		$aNewData   = array('aAlwEvent' => $aAlwEvent);
		$this->load->view('company/branchsetwah/wBranchsetwahList', $aNewData);
    }

	//ข้อมูลในตาราง
	public function FSvCWAHDataTable(){
        $tBchCode       = $this->input->post('tBchCode');
        $tAgnCode       = $this->input->post('tAgnCode');
        $tSearchAll     = $this->input->post('tSearchAll');
        $nPage          = $this->input->post('nPageCurrent');

        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage  = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}

        // สิทธิ
        $aAlwEvent      = FCNaHCheckAlwFunc('branch/0/0');

        $aData   = array(
            'tBchCode'     => $tBchCode,
            'tAgnCode'     => $tAgnCode,
            'nPage'        => $nPage,
            'nRow'         => 10,
            'FNLngID'      => $this->session->userdata("tLangEdit"),
            'tSearchAll'   => $tSearchAll
        );

        $aResList   = $this->mBranchSetWah->FSaMBranchSetWahLDataList($aData);
        $aGenTable  = array(
            'aDataList' 	            => $aResList,
            'nPage'     	            => $nPage,
            'aAlwEvent'                 => $aAlwEvent
        );
        $this->load->view('company/branchsetwah/wBranchsetwahDataTable',$aGenTable);
	}

	//หน้าเพิ่ม
	public function FSvCWAHPageAdd(){
        $aAlwEvent	= FCNaHCheckAlwFunc('branch/0/0');
        $tBchCode   = $this->input->post('tBchCode');
        $tAgnCode   = $this->input->post('tAgnCode');

        $aItem = array(
            'tBchCode'      => $tBchCode,
            'tAgnCode'      => $tAgnCode,
            'FNLngID'       => $this->session->userdata("tLangEdit")
        );

        //ข้อมูลชื่อสาขา + ชือตัวแทนขาย
        $aItemResultHD = $this->mBranchSetWah->FSaMBranchSetWahLGetDetailName($aItem);

        $aReturn   = array(
            'aAlwEvent'    => $aAlwEvent,
            'aItem'        => $aItem,
            'aItemResultHD'=> $aItemResultHD
        );
        $this->load->view('company/branchsetwah/wBranchsetwahAdd',$aReturn);
	}

    //หน้าแก้ไข
	public function FSvCWAHPageEdit(){
        $aAlwEvent	= FCNaHCheckAlwFunc('branch/0/0');
        $tBchCode   = $this->input->post('tBchCode');
        $tAgnCode   = $this->input->post('tAgnCode');
        $nSeq       = $this->input->post('nSeq');
        $tWah       = $this->input->post('tWah');

        $aItem = array(
            'tBchCode'      => $tBchCode,
            'tAgnCode'      => $tAgnCode,
            'nSeq'          => $nSeq,
            'tWah'          => $tWah,
            'FNLngID'       => $this->session->userdata("tLangEdit")
        );

        //ข้อมูลชื่อสาขา + ชือตัวแทนขาย
        $aItemResultHD  = $this->mBranchSetWah->FSaMBranchSetWahLGetDetailName($aItem);

        //ข้อมูล
        $aItemResult    = $this->mBranchSetWah->FSaMBranchSetWahLGetDataByID($aItem);

        $aReturn   = array(
            'aAlwEvent'    => $aAlwEvent,
            'aItem'        => $aItem,
            'aItemResultHD'=> $aItemResultHD,
            'aItemResult'  => $aItemResult
        );
        $this->load->view('company/branchsetwah/wBranchsetwahAdd',$aReturn);
	}

	//เพิ่มข้อมูล
	public function FSaCWAHAddEvent(){
        $tBchCode       = $this->input->post('tBchCode');
        $tAgnCode       = $this->input->post('tAgnCode');
        $tOptionCode    = $this->input->post('tOptionCode');
        $tWahCode       = $this->input->post('tWahCode');

        $aItem   = array(
            'tBchCode'     => $tBchCode,
            'tAgnCode'     => $tAgnCode
        );

        //หาลำดับล่าสุด
        $nCountSeq = $this->mBranchSetWah->FSaMBranchSetGetSeqlast($aItem);
        $nCountSeq = $nCountSeq+1;

        $aInsert = array(
            'FTAgnCode'         => $tAgnCode,
            'FTBchCode'         => $tBchCode,
            'FTObjCode'         => $tOptionCode,
            'FNBchOptSeqNo'     => $nCountSeq,
            'FTBchOptValue'     => $tWahCode
        );

        //เช็คข้อมูลว่าซ้ำไหม 
        $aCheckDup = $this->mBranchSetWah->FSaMBranchSetCheckDup($aInsert);
        if($aCheckDup[0]->counts == 0){
            $this->mBranchSetWah->FSaMBranchSetInsert($aInsert);
            $aReturn = array(
                'nStatus'       => '0',
                'tTextStatus'   => 'success'
            );
        }else{
            $aReturn = array(
                'nStatus'       => '1',
                'tTextStatus'   => 'fail'
            );
        }

        echo json_encode($aReturn);
	}

	//แก้ไขข้อมูล
	public function FSaCWAHEditEvent(){
        $tBchCode       = $this->input->post('tBchCode');
        $tAgnCode       = $this->input->post('tAgnCode');
        $tOptionCode    = $this->input->post('tOptionCode');
        $tWahCode       = $this->input->post('tWahCode');
        $nSeq           = $this->input->post('nSeq');
        $tOptionCodeOld = $this->input->post('tOptionCodeOld');
        $tWahCodeOld    = $this->input->post('tWahCodeOld');

        $aUpdate = array(
            'FTAgnCode'         => $tAgnCode,
            'FTBchCode'         => $tBchCode,
            'FTObjCode'         => $tOptionCode,
            'FNBchOptSeqNo'     => $nSeq,
            'FTBchOptValue'     => $tWahCode
        );

        if(($tOptionCodeOld == $tOptionCode) && ($tWahCode == $tWahCodeOld) ){
            $aReturn = array(
                'nStatus'       => '0',
                'tTextStatus'   => 'success'
            );
        }else{
            //เช็คข้อมูลว่าซ้ำไหม 
            $aCheckDup = $this->mBranchSetWah->FSaMBranchSetCheckDup($aUpdate);
            if($aCheckDup[0]->counts == 0){
                $this->mBranchSetWah->FSaMBranchSetUpdate($aUpdate);
                $aReturn = array(
                    'nStatus'       => '0',
                    'tTextStatus'   => 'success'
                );
            }else{
                $aReturn = array(
                    'nStatus'       => '1',
                    'tTextStatus'   => 'fail'
                );
            }
        }

        echo json_encode($aReturn);
	}

	//ลบข้อมูล
	public function FSaCWAHDeleteEvent(){
        $nSeq           = $this->input->post('nSeq');
        $tBchCode       = $this->input->post('tBchCode');
        $tWahCode       = $this->input->post('tWahCode'); 

        $aDataMaster = array(
            'FNBchOptSeqNo' => $nSeq,
            'FTBchCode'     => $tBchCode,
            'FTBchOptValue' => $tWahCode
        );

        $aResDel = $this->mBranchSetWah->FSnMBranchSetDel($aDataMaster);
        $aReturn = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
	}
	

}
