<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cSaleMachine extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('company/branch/mBranch');
        $this->load->model('pos/salemachine/mSaleMachine');
        date_default_timezone_set("Asia/Bangkok");
        $this->load->helper("security");
        if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
            echo "ERROR XSS Filter";
        }
    }

    public function index($nBrowseType,$tPosBrowseOption){
        $aBrowseType = explode("-",$nBrowseType);
		if(isset($aBrowseType[1])){
			$nPosBrowseType = $aBrowseType[0];
			$tRouteFromName = $aBrowseType[1];
		}else{
			$nPosBrowseType = $nBrowseType;
			$tRouteFromName = '';
        }
        $aDataConfigView = array(
            'tRouteFromName'    => $tRouteFromName,
            'nPosBrowseType'    => $nPosBrowseType,
            'tPosBrowseOption'  => $tPosBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('salemachine/0/0'), // Controle Event
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('salemachine/0/0'), // Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        );
        $this->load->view('pos/salemachine/wSaleMachine',$aDataConfigView);
    }

    //Function Call SaleMachine Page List
    public function FSvCPOSListPage(){ 
        $aAlwEvent          = FCNaHCheckAlwFunc('salemachine/0/0');
		$aNewData  		   = ['aAlwEvent' => $aAlwEvent];
        $this->load->view('pos/salemachine/wSaleMachineList',$aNewData);
    }

    //Function Call DataTables SaleMachine
    public function FSvCPOSDataList(){
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'tSearchAll'    => $tSearchAll,
                'FNLngID'   	=> $this->session->userdata("tLangEdit"),
            );
            $aPosDataList   = $this->mSaleMachine->FSaMPOSList($aData); 

            //เวลา Text ค้นหา จะไม่เจอ
            if($aPosDataList['rnAllRow'] == 0){
                $nPage              = $nPage - 1;
                $aData['nPage']     = $nPage;
                $aPosDataList       = $this->mSaleMachine->FSaMPOSList($aData); 
            }

            $aGenTable  = array(
                'aPosDataList'  => $aPosDataList,
                'nPage'         => $nPage,
                'tSearchAll'    => $tSearchAll
            );
            $this->load->view('pos/salemachine/wSaleMachineDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Function CallPage SaleMachine Add
    public function FSvCPOSAddPage(){
        $aCnfAddPanal       = $this->FSvCBCHGenViewAddress();
        $aCnfAddVersion     = FCNaHAddressFormat('TCNMPos');
        $nType              = $this->input->post('nRoutetype');

        //เช็ค type กรณี เรียกใช้งานแบบเบาร์จากหน้าร้านค้า>เครื่องจุดขาย
        if(isset($nType) && !empty($nType)){
           switch ($nType) { 
            case  "1": 
                $tPosOptionType = '<option value="1" >'. language('pos/salemachine/salemachine','tPOSSalePoint') .'</option>';               
                break;
            case  "2": 
                $tPosOptionType = '<option value="2" >'. language('pos/salemachine/salemachine','tPOSPrePaid') .'</option>';
            break;
            // case  "3": 
            //     $tPosOptionType = '<option value="3" >'. language('pos/salemachine/salemachine','tPOSCheckPoint') .'</option>';
            // break;
            case  "4": 
                $tPosOptionType = '<option value="4" >'. language('pos/salemachine/salemachine','tPOSVending') .'</option>';
                break;
            
            case  "5": 
                if( FCNbGetIsLockerEnabled() ){
                    $tPosOptionType = '<option value="5" >'. language('pos/salemachine/salemachine','tPOSSmartLoc') .'</option>';
                }
                break;
            // case "7":
            //     $tPosOptionType = '<option value="7" >'. language('pos/salemachine/salemachine','tPOSSCO') .'</option>';
            // break;
            // case  "6": 
            //     $tPosOptionType = '<option value="6" >'. language('pos/salemachine/salemachine','tPOsVansale') .'</option>';
            // break;
            default: 
                $tPosOptionType = "";
            }
        }else{
            $tPosOptionType  = '<option value="1" >'. language('pos/salemachine/salemachine','tPOSSalePoint') .'</option>';
            $tPosOptionType .= '<option value="2" >'. language('pos/salemachine/salemachine','tPOSPrePaid') .'</option>';
            $tPosOptionType .= '<option value="4" >'. language('pos/salemachine/salemachine','tPOSVending') .'</option>';
            if( FCNbGetIsLockerEnabled() ){
                $tPosOptionType .= '<option value="5" >'. language('pos/salemachine/salemachine','tPOSSmartLoc') .'</option>';
            }
        }

        try{
            //ตรวจสอบ Level ของ User
            $tSesUsrLevel =	$this->session->userdata("tSesUsrLevel");
            if($tSesUsrLevel == "HQ"){
                $tStaUsrLevel = "HQ";
                $tUsrBchCode = "";  
                $tUsrBchName = "";
            }else{
                $tStaUsrLevel = $this->session->userdata("tSesUsrLevel"); 
                $tUsrBchCode = $this->session->userdata("tSesUsrBchCode"); 
                $tUsrBchName = $this->session->userdata("tSesUsrBchName"); 
            }

            $aDataSaleMachine = array(
                'nStaAddOrEdit' => 99,
                'aCnfAddPanal' => $aCnfAddPanal,
                'nCnfAddVersion' => $aCnfAddVersion,
                'tPosOptionType' => $tPosOptionType,
                'tStaUsrLevel' => $tStaUsrLevel,
                'tUsrBchCode' => $tUsrBchCode,
                'tUsrBchName' => $tUsrBchName
            );
            $this->load->view('pos/salemachine/wSaleMachineAdd',$aDataSaleMachine);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Function CallPage SaleMachine Edits
    public function FSvCPOSEditPage(){
        try{
            $tBchCode  = $this->input->post('tBchCode');
            $tPosCode  = $this->input->post('tPosCode');
            $tPosType  = $this->input->post('tPosType');
      
            switch ($tPosType) {
                case "4":
                    // ประเภท ตู้ขายสินค้า
                    $aCodeBchShp = $this->mSaleMachine->FSaMSMGGetVDPosShopDataList($tPosCode);
                    break;
                case "5":
                        // ประเภท ตู้ฝากของ
                    $aCodeBchShp = $this->mSaleMachine->FSaMSMGGetSMPosShopDataList($tPosCode);
                    break;
                default:
                    // ไม่ใช่ ประเภท 4,5 ให้เป็นค่าว่าง
                    $aCodeBchShp = array('rtCode' => '99');
            }
            $tPosOptionType = '
                <option value="1" >'. language('pos/salemachine/salemachine','tPOSSalePoint') .'</option>
                <option value="2" >'. language('pos/salemachine/salemachine','tPOSPrePaid') .'</option>	
                <option value="4" >'. language('pos/salemachine/salemachine','tPOSVending') .'</option> ';	
           	
            if( FCNbGetIsLockerEnabled() ){
                $tPosOptionType .= '<option value="5" >'. language('pos/salemachine/salemachine','tPOSSmartLoc') .'</option>';
            }

            $aData  = array(
                'FTPosCode' => $tPosCode,
                'FTBchCode' => $tBchCode
            );
                                                
            $aPosData           = $this->mSaleMachine->FSaMPOSGetDataByID($aData);
            $aSlipMessage       = $this->mSaleMachine->FSaMSMGGetDataList($tPosCode);
            $aCnfAddVersion     = FCNaHAddressFormat('TCNMPos');
            $aCnfAddPanal       = $this->FSvCBCHGenViewAddress($aPosData,$aCnfAddVersion);
            $tSesUsrLevel =	$this->session->userdata("tSesUsrLevel");
            if($tSesUsrLevel == "HQ"){
                $tStaUsrLevel = "HQ";  
                $tUsrBchCode  = "";
                $tUsrBchName  = "";
            }else{
                $tStaUsrLevel = $this->session->userdata("tSesUsrLevel"); 
                $tUsrBchCode  = $this->session->userdata("tSesUsrBchCode"); 
                $tUsrBchName  = $this->session->userdata("tSesUsrBchName"); 
            }

            $aDataSaleMachine   = array(
                'nStaAddOrEdit'     => 1,
                'aCnfAddPanal' 		=> $aCnfAddPanal,
                'nCnfAddVersion' 	=> $aCnfAddVersion,
                'aPosData'          => $aPosData,
                'aSlipMessage'      => $aSlipMessage,
                'aCodeBchShp'       => $aCodeBchShp,
                'tPosCodeEvent'     => $tPosCode,
                'tPosOptionType'    => $tPosOptionType,
                'tStaUsrLevel'      => $tStaUsrLevel,
                'tUsrBchCode'       => $tUsrBchCode,
                'tUsrBchName'       => $tUsrBchName
            );

        $this->load->view('pos/salemachine/wSaleMachineAdd',$aDataSaleMachine);

        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Detail Address
    public function FSvCBCHGenViewAddress($paResList = '',$nCnfAddVersion = ''){
		$nLangResort = $this->session->userdata("tLangID");
		$nLangEdit	 = $this->session->userdata("tLangEdit");
		if(isset($paResList['raItems']['rtPosCode'])){
			$tPosCode = $paResList['raItems']['rtPosCode'];
			$aData = array(
				'FNLngID' 			=> $nLangEdit,
				'FTAddGrpType' 		=> '6',
				'FTAddVersion' 		=> $nCnfAddVersion,
				'FTAddRefCode' 		=> $tPosCode,
			);
			$aCnfAddEdit    = $this->mBranch->FSvMBCHGetAddress($aData);
		}else{
			$tPosCode       = '';
			$aCnfAddEdit    = '';
		}
		return $aCnfAddEdit;
	}

    //Event Add SaleMachine
    public function FSoCPOSAddEvent(){ 
        try{

            // ไม่ได้ใช้ เพราะเอาไปใช้ checkbox เปิดรอบอัตโนมัติ ถ้า 1 : Manual 2 :Auto
            if($this->input->post('ocbStaShif') !== NULL){
                $tStaShift = '2';
            }else{
                $tStaShift = '1';
            }

            //เช็ค type PosType
            // $tPosType = $this->input->post("ocmPosType");
            // if($tPosType == 4 || $tPosType == 5 ){
            //         $tStaShift = 2;
            //     }else{
            //         $tStaShift = 1;
            // }

            // ไม่ได้ใช้ เพราะเอาไปใช้ checkbox เปิดรอบอัตโนมัติ ถ้า 1 : Manual 2 :Auto
            $nAddVersion = $this->input->post("ohdAddVersion");
            $tPosType    = $this->input->post("ocmPosType");
            if($nAddVersion==1){
                $aDataPos   = array(
                    'tIsAutoGenCode'                => $this->input->post("ocbPosAutoGenCode"),
                    'oetPosCode'                    => $this->input->post("oetPosCode"),
                    'FTBchCode'                     => $this->input->post("oetPosBchCode"),
                    'FTPosStaShift'                 => $tStaShift,
                    'ocmPosType'                    => $this->input->post("ocmPosType"),
                    'oetBchWahCode'                 => $this->input->post("oetBchWahCode"),
                    'oetPosRegNo'                   => $this->input->post("oetPosRegNo"),
                    'oetPosIP'                      => $this->input->post("oetPosIP"),
                    'oetPosSmgCode'                 => $this->input->post("oetPosSmgCode"), 
                    'ocbPOSStaPrnEJ'                => $this->input->post("ocbPOSStaPrnEJ"),
                    'ocbPosStaVatSend'              => 1,
                    'ocbPosStaUse'                  => $this->input->post("ocbPosStaUse"),
                    'ocbPosStaSaleFC'               => $this->input->post("ocbPosStaSaleFC"),
                    'oetPosName'                    => $this->input->post("oetPosName"),
                    'ocmPosShwRow'                  => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwRow") : 0 ),
                    'ocmPosShwCol'                  => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwCol") : 0 ),
                    'ocbPOSStaSumProductBySacn'     => $this->input->post("ocbPOSStaSumProductBySacn"), 
                    'ocbPOSStaSumProductByPrint'    => $this->input->post("ocbPOSStaSumProductByPrint"),
                    'FTChnCode'                     => $this->input->post("oetPosChanelCode")
                );
            }else{
                $aDataPos   = array(
                    'tIsAutoGenCode'    => $this->input->post("ocbPosAutoGenCode"),
                    'oetPosCode'        => $this->input->post("oetPosCode"),
                    'FTBchCode'         => $this->input->post("oetPosBchCode"),
                    'FTPosStaShift'     => $tStaShift,
                    'ocmPosType'        => $this->input->post("ocmPosType"),
                    'oetBchWahCode'     => $this->input->post("oetBchWahCode"),
                    'oetPosRegNo'       => $this->input->post("oetPosRegNo"),
                    'oetPosIP'          => $this->input->post("oetPosIP"),
                    'oetPosSmgCode'     => $this->input->post("oetPosSmgCode"),   
                    'ocbPOSStaPrnEJ'    => $this->input->post("ocbPOSStaPrnEJ"),
                    'ocbPosStaVatSend'  => 1,
                    'ocbPosStaUse'      => $this->input->post("ocbPosStaUse"),
                    'ocbPosStaSaleFC'   => $this->input->post("ocbPosStaSaleFC"),
                    'oetPosName'        => $this->input->post("oetPosName"),
                    'ocmPosShwRow'      => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwRow") : 0 ),
                    'ocmPosShwCol'      => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwCol") : 0 ),
                    'ocbPOSStaSumProductBySacn'     => $this->input->post("ocbPOSStaSumProductBySacn"), 
                    'ocbPOSStaSumProductByPrint'    => $this->input->post("ocbPOSStaSumProductByPrint"),
                    'FTChnCode'                     => $this->input->post("oetPosChanelCode")
                );
            }

            if($aDataPos['tIsAutoGenCode'] == '1'){ 
                $aStoreParam = array(
                    "tTblName"    => 'TCNMPos',                           
                    "tDocType"    => 0,                                          
                    "tBchCode"    => $this->input->post("oetPosBchCode"),                                 
                    "tShpCode"    => "",                               
                    "tPosCode"    => "",                     
                    "dDocDate"    => date("Y-m-d")       
                );
                $aAutogen                    = FCNaHAUTGenDocNo($aStoreParam);
                $aDataPos['oetPosCode']      = $aAutogen[0]["FTXxhDocNo"];
            }
            $nPosCup = $this->mSaleMachine->FSnMPOSCheckDuplicate($aDataPos['oetPosCode'],$this->input->post("oetPosBchCode"));
            
            if($nPosCup==0){
                $this->db->trans_begin();
                $this->mSaleMachine->FSxMPOSInsertPos($aDataPos);
                $this->mSaleMachine->FSxMPOSInsertPosWaHouse($aDataPos);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add SaleMachine Group"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataPos['oetPosCode'],
                        'tBchCode'      => $aDataPos['FTBchCode'],
                        'tPosType'      => $aDataPos['ocmPosType'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add SaleMachine Group'
                    );
                }
        }else{
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess Add Data Duplicate"
            );
        }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Event Edit SaleMachine
    public function FSoCPOSEditEvent(){ 
        try{
            
            if($this->input->post('ocbStaShif') !== NULL){
                $tStaShift = '2';
            }else{
                $tStaShift = '1';
            }

            $nAddVersion = $this->input->post("ohdAddVersion");
            $tPosType    = $this->input->post("ocmPosType");
            if($nAddVersion==1){
                $aDataPos   = array(
                    'tIsAutoGenCode'        => $this->input->post("ocbPosAutoGenCode"),
                    'oetPosCode'            => $this->input->post("oetPosCode"),
                    'FTBchCode'             => $this->input->post("oetPosBchCode"),
                    'FTPosStaShift'         => $tStaShift,
                    'ocmPosType'            => $this->input->post("ocmPosType"),
                    'oetBchWahCode'         => $this->input->post("oetBchWahCode"),
                    'oetBchWahCodeOld'      => $this->input->post("oetBchWahCodeOld"),
                    'oetPosRegNo'           => $this->input->post("oetPosRegNo"),
                    'oetPosIP'              => $this->input->post("oetPosIP"),
                    'oetPosSmgCode'         => $this->input->post("oetPosSmgCode"), 
                    'ocbPOSStaPrnEJ'        => $this->input->post("ocbPOSStaPrnEJ"),
                    'ocbPosStaVatSend'      => 1,
                    'ocbPosStaUse'          => $this->input->post("ocbPosStaUse"),
                    'ocbPosStaSaleFC'       => $this->input->post("ocbPosStaSaleFC"),
                    'ocmPosShwRow'          => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwRow") : 0 ),
                    'ocmPosShwCol'          => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwCol") : 0 ),
                    'ocbPOSStaSumProductBySacn'     => $this->input->post("ocbPOSStaSumProductBySacn"), 
                    'ocbPOSStaSumProductByPrint'    => $this->input->post("ocbPOSStaSumProductByPrint"),
                    'FTChnCode'                     => $this->input->post("oetPosChanelCode")
                );
            }else{
                $aDataPos   = array(
                    'tIsAutoGenCode'        => $this->input->post("ocbPosAutoGenCode"),
                    'oetPosCode'            => $this->input->post("oetPosCode"),
                    'FTBchCode'             => $this->input->post("oetPosBchCode"),
                    'FTBchOldCode'          => $this->input->post("ohdBchCode"),
                    'FTPosStaShift'         => $tStaShift,
                    'ocmPosType'            => $this->input->post("ocmPosType"),
                    'oetBchWahCode'         => $this->input->post("oetBchWahCode"),
                    'oetBchWahCodeOld'      => $this->input->post("oetBchWahCodeOld"),
                    'oetPosRegNo'           => $this->input->post("oetPosRegNo"),
                    'oetPosIP'              => $this->input->post("oetPosIP"),
                    'oetPosSmgCode'         => $this->input->post("oetPosSmgCode"),  
                    'ocbPOSStaPrnEJ'        => $this->input->post("ocbPOSStaPrnEJ"),
                    'ocbPosStaVatSend'      => 1,
                    'ocbPosStaUse'          => $this->input->post("ocbPosStaUse"),
                    'ocbPosStaSaleFC'       => $this->input->post("ocbPosStaSaleFC"),
                    'FTPosName'             => $this->input->post("oetPosName"),
                    'ocmPosShwRow'          => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwRow") : 0 ),
                    'ocmPosShwCol'          => ($tPosType == '1' || $tPosType == '4' ? $this->input->post("ocmPosShwCol") : 0 ),
                    'ocbPOSStaSumProductBySacn'     => $this->input->post("ocbPOSStaSumProductBySacn"), 
                    'ocbPOSStaSumProductByPrint'    => $this->input->post("ocbPOSStaSumProductByPrint"),
                    'FTChnCode'                     => $this->input->post("oetPosChanelCode")
                );
            }

            $nPosCup = $this->mSaleMachine->FSnMPOSCheckDuplicate($aDataPos['oetPosCode'],$this->input->post("oetPosBchCode"));
                if($this->input->post("oetPosBchCode")==$this->input->post("oetPosBchCode")){
                    $nPosCup = 0;
                }

            if($nPosCup==0){
                $this->mSaleMachine->FSaMPOSAddUpdateLang($aDataPos);
                $this->mSaleMachine->FSxMPOSUpdatePos($aDataPos);
                $this->mSaleMachine->FSxMPOSUpdatePosWaHouse($aDataPos);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add SaleMachine Group"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataPos['oetPosCode'],
                        'tBchCode'      => $aDataPos['FTBchCode'],
                        'tPosType'      => $aDataPos['ocmPosType'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add SaleMachine Group'
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Data Duplicate"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Event Delete SaleMachine
    public function FSoCPOSDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTPosCode' => $tIDCode,
            'FTBchCode' => $this->input->post('tBchCode'),
        );
        $aResDel    = $this->mSaleMachine->FSaMPOSDelAll($aDataMaster);
        $nNumRowPos = $this->mSaleMachine->FSnMLOCGetAllNumRow();
        if($nNumRowPos!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'nNumRowPos' => $nNumRowPos
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }

    /*===== Begin Import By Excel ======================================================*/
    public function FStCImportGetDataInTmp(){
        $tTextSearch = $this->input->post('tSearch');
        $this->load->view('pos/salemachine/wSaleMachineImportDataTable', ['tSearch' => $tTextSearch]);
    }

    //Get Import Data in Temp (JSON)
    public function FSoCImportGetDataJsonInTmp(){
        $aGetImportDataInTmpParams = array(
			'nPageNumber'       => ($this->input->post('nPageNumber') == 0) ? 1 : $this->input->post('nPageNumber'),
			'nLangEdit'	        => $this->session->userdata("tLangEdit"),
			'tTableKey'	        => 'TCNMPos',
			'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
			'tTextSearch'       => $this->input->post('tSearch') 
		);
		$aDataInTemp = $this->mSaleMachine->FSaMImportGetDataInTmp($aGetImportDataInTmpParams);
        
        $aData['draw']              = ($this->input->post('nPageNumber') == 0) ? 1 : $this->input->post('nPageNumber');
        $aData['recordsTotal']      = $aDataInTemp['nNumrow'];
        $aData['recordsFiltered']   = $aDataInTemp['nNumrow'];
        $aData['data']              = $aDataInTemp;
        $aData['error']             = array();
        $aData['tTextSearch']       = $aGetImportDataInTmpParams['tTextSearch'];
        $this->output->set_content_type('application/json')->set_output(json_encode($aData));
    }

    //Delete in Temp by SeqNo
	public function FSoCImportDeleteInTempBySeqNo(){
        $aImportDataItem = json_decode($this->input->post('tPdtDataItem'), JSON_FORCE_OBJECT);
        $tUserSessionID = $this->session->userdata('tSesSessionID');

        $this->db->trans_begin();
        $aImportDeleteInTempBySeqParams = array(
            'tUserSessionID' => $tUserSessionID,
            'tTableKey' => 'TCNMPos',
            'aSeqNo' => $aImportDataItem['aSeqNo']
        );
        $aResDel = $this->mSaleMachine->FSaMImportDeleteInTempBySeq($aImportDeleteInTempBySeqParams);

        foreach($aImportDataItem['aItems'] as $aItem){
            // ตรวจสอบกรอกข้อมูลซ้ำ Temp
            if($aItem['tSta'] == "5"){ 
                $aParams = [
                    'tUserSessionID'    => $tUserSessionID, 
                    'aFieldName'        => [['FTBchCode', $aItem['tBchCode']], ['FTPosCode', $aItem['tPosCode']]]
                ];
                FCNnMasTmpChkInlineCodeMultiDupInTemp($aParams);
            }
        }

        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $aReturn = array(
                'nStaEvent' => '900',
                'tStaMessg' => "Unsucess Add SaleMachine Group"
            );
        }else{
            $this->db->trans_commit();
            $aReturn = array(
                'nStaEvent' => $aResDel['tCode'],
                'tStaMessg' => $aResDel['tDesc']
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    //Temp to Master
	public function FSoCImportTempToMaster(){
        $aDataMaster = array(
            'nLangEdit'	            => $this->session->userdata("tLangEdit"),
            'tTableKey'	            => 'TCNMPos',
            'tUserSessionID'        => $this->session->userdata("tSesSessionID"),
            'tCreatedOn'            => date('Y-m-d H:i:s'),
            'tCreatedBy'            => $this->session->userdata("tSesUsername"),
            'tTypeCaseDuplicate'    => $this->input->post('tTypeCaseDuplicate')
        );

        $this->db->trans_begin();

        $this->mSaleMachine->FSxMImportTempToMaster($aDataMaster);
        $this->mSaleMachine->FSxMBCHImportTempToMasterWithReplaceOrInsert($aDataMaster);
        $this->mSaleMachine->FSxMBCHImportDeleteAllInTemp($aDataMaster);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $aReturn = array(
                'tCode' => '99',
                'tDesc' => 'Insert Temp to Master Fail'
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'tCode' => '1',
                'tDesc' => 'Insert Temp to Master Success'
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    //Clear Temp
	public function FSoCImportClearInTemp(){
        $aDataMaster = array(
            'tTableKey'	=> 'TCNMPos',
            'tUserSessionID' => $this->session->userdata("tSesSessionID"),
        );

        $this->db->trans_begin();
        $this->mSaleMachine->FSxMBCHImportDeleteAllInTemp($aDataMaster);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $aReturn = array(
                'tCode' => '99',
                'tDesc' => 'Clear Temp Fail'
            );
        } else {
            $this->db->trans_commit();
            $aReturn = array(
                'tCode' => '1',
                'tDesc' => 'Clear Temp Success'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));
    }

    //Get Status in Temp
    public function FSoCSALImportGetStaInTemp(){
        $aData = [];

        $aGetStaInTempParams = array(
            'tTableKey'	=> 'TCNMPos',
            'tUserSessionID' => $this->session->userdata("tSesSessionID"),
        );
        $aGetStaInTemp = $this->mSaleMachine->FSaMSALGetStaInTemp($aGetStaInTempParams);
        
        $aData['nRecordTotal'] = isset($aGetStaInTemp[0]['nRecordTotal'])?$aGetStaInTemp[0]['nRecordTotal']:0;
        $aData['nStaSuccess'] = isset($aGetStaInTemp[0]['nStaSuccess'])?$aGetStaInTemp[0]['nStaSuccess']:0;
        $aData['nStaNewOrUpdate'] = isset($aGetStaInTemp[0]['nStaNewOrUpdate'])?$aGetStaInTemp[0]['nStaNewOrUpdate']:0;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($aData));
    }

}