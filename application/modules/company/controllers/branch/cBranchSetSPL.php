<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cBranchSetSPL extends MX_Controller {

	public function __construct() {
		parent::__construct ();
		$this->load->model('company/branch/mBranchSetSPL');
		date_default_timezone_set("Asia/Bangkok");
	}

	//หน้าหลัก
	public function index(){
        $aAlwEvent  = FCNaHCheckAlwFunc('branch/0/0');
        $tBchCode   = $this->input->post('tBchCode');
        $tAgnCode   = $this->input->post('tAgnCode');

        $aItem = array(
            'tBchCode'      => $tBchCode,
            'tAgnCode'      => $tAgnCode
        );

        $this->load->view('company/branchsetSPL/wBranchsetspl',array(
            'aAlwEvent'  => $aAlwEvent,
            'aItem'      => $aItem
        ));
	}

    //ข้อมูลช่องค้นหา
    public function FSvCSPLList(){
        $aAlwEvent	= FCNaHCheckAlwFunc('branch/0/0');
		$aNewData   = array('aAlwEvent' => $aAlwEvent);
		$this->load->view('company/branchsetSPL/wBranchsetsplList', $aNewData);
    }

	//ข้อมูลในตาราง
	public function FSvCSPLDataTable(){
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

        $aResList   = $this->mBranchSetSPL->FSaMBranchSetsplDataList($aData);
        $aGenTable  = array(
            'tBchCode'      => $tBchCode,
            'tAgnCode'      => $tAgnCode,
            'aDataList'     => $aResList,
            'nPage'     	=> $nPage,
            'aAlwEvent'     => $aAlwEvent
        );
        $this->load->view('company/branchsetSPL/wBranchsetsplDataTable',$aGenTable);
	}

	//เพิ่มข้อมูล
	public function FSaCSPLAddEvent(){
        $tBchCode       = $this->input->post('tBchCode');
        $tAgnCode       = $this->input->post('tAgnCode');
        $tSPLCode       = $this->input->post('tSPLCode');
        $tSPLCodeOld    = $this->input->post('tSPLCodeOld');
        $tSPLUse        = $this->input->post('tSPLUse');

        $aInsert = array(
            'FTAgnCode'         => $tAgnCode,
            'FTBchCode'         => $tBchCode,
            'FTSplCode'         => $tSPLCode,
            'FTStaUse'          => $tSPLUse
        );

        if($tSPLCode == $tSPLCodeOld){
            //Update Flag อย่างเดียว
            $this->mBranchSetSPL->FSaMBranchSetSPLUpdate($aInsert);
            $aReturn = array(
                'nStatus'       => '0',
                'tTextStatus'   => 'success'
            );
        }else{
            //เช็คข้อมูลว่าซ้ำไหม 
            $aCheckDup = $this->mBranchSetSPL->FSaMBranchSetCheckSPLDup($aInsert);
            if($aCheckDup[0]->counts == 0){

                if($tSPLCodeOld != '' || $tSPLCodeOld != null){
                    $this->mBranchSetSPL->FSaMBranchSetDelete($aInsert,$tSPLCodeOld);
                }

                $this->mBranchSetSPL->FSaMBranchSetInsert($aInsert);
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

    //หน้าจอแก้ไข (เอามาแต่ข้อมูล)
    public function FSvCSPLPageEdit(){
        $tBchCode       = $this->input->post('tBchCode');
        $tSplCode       = $this->input->post('tSplCode');

        $aWhere = array(
            'tBchCode'         => $tBchCode,
            'tSplCode'         => $tSplCode,
            'FNLngID'          => $this->session->userdata("tLangEdit"),
        );
        $aResult = $this->mBranchSetSPL->FSaMBranchSetsplGetDataByID($aWhere);
        echo json_encode($aResult);
    }

	//แก้ไขข้อมูล
	public function FSaCSPLEditEvent(){
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
            $aCheckDup = $this->mBranchSetSPL->FSaMBranchSetCheckDup($aUpdate);
            if($aCheckDup[0]->counts == 0){
                $this->mBranchSetSPL->FSaMBranchSetUpdate($aUpdate);
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

	//ลบข้อมูลรายการเดียว
	public function FSaCSPLDeleteEvent(){
        $tSplCode       = $this->input->post('tSplCode');
        $tBchCode       = $this->input->post('tBchCode');

        $aDataMaster = array(
            'tSplCode'      => $tSplCode,
            'tBchCode'      => $tBchCode
        );
        $aResDel = $this->mBranchSetSPL->FSnMBranchSetsplDel($aDataMaster);
        $aReturn = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
	}

    //ลบข้อมูลแบบหลายตัว
    public function FSaCSPLDeleteEventMulti(){
        $tDataDocNo       = $this->input->post('tDataDocNo');
        $tBchCode         = $this->input->post('tBchCode');
        $aDataMaster = array(
            'tDataDocNo'        => $tDataDocNo,
            'tBchCode'          => $tBchCode
        );
        $aResDel = $this->mBranchSetSPL->FSnMBranchSetsplDelMulti($aDataMaster);
        $aReturn = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

}
