<?php defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class cLogin extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( "session" );
		if(isset($_SESSION["tStaByPass"])){
			if( @$_SESSION["tStaByPass"] == '2' ){
				if(@$_SESSION['tSesUsername'] == true) {
					redirect( '', 'refresh' );
					exit();
				}
			}
		}
	  
		// Clean XSS Filtering Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
			echo "ERROR XSS Filter";
		}
	}

	public function index() {
		$this->load->view('authen/login/wLogin');

		delete_cookie("tAgnCode");
		delete_cookie("tBchCode");
		delete_cookie("tUsrCode");
		delete_cookie("tMenuCode");
		delete_cookie("tMenuName");
		delete_cookie("nShowRecordInPageList");
	}
    
	//Functionality: ตรวจสอบการเข้าใช้งานระบบ
	//Parameters:  รับค่าจากฟอร์ม type POST
	//Creator: 23/03/2018 Phisan(Arm)
	//Last Modified : 
	//Return : Error Code 
	//Return Type: Redirect
	public function FSaCLOGChkLogin(){
		try {
			$tUsername	 = $this->input->post('oetUsername'); //ชื่อผู้ใช้
			$tPassword	 = $this->input->post('oetPasswordhidden'); //รหัสผ่าน
			$tUsrCode	 = $this->input->post('tUsrCode');
			$tUsrLogType = $this->input->post('tUsrLogType'); //UsrLogType 1:รหัสผ่าน,2:PIN
			$tStaByPass  = ( $this->input->post('tStaByPass') != '' ? '1' : '2'); 
			if( $tStaByPass == '1' ){
				$this->session->set_userdata("tRouteByPass", $this->input->post('tRouteByPass'));
				$this->session->set_userdata("tStaByPass", '1');
				$this->session->set_userdata("tLangID", $this->input->post('nLanguage'));
				$this->session->set_userdata("tByPassAgnCode", $this->input->post('tParamAgnCode'));
				$this->session->set_userdata("tByPassBchCode", $this->input->post('tParamBchCode'));
				$this->session->set_userdata("tByPassDocNo", $this->input->post('tParamDocNo'));
			}else{
				$this->session->set_userdata("tStaByPass", '2');
			}

			$this->load->model('authen/login/mLogin');
			$this->load->model('authen/user/mUser');
			$aDataUsr = $this->mLogin->FSaMLOGChkLogin($tUsername,$tPassword,$tStaByPass);
			if(!empty($aDataUsr[0]) && $aDataUsr[0]['FTStaError'] == '0' ){

				$this->session->set_userdata("tSesUserLogin", $tUsername);
				if($aDataUsr[0]['FTUsrStaActive'] == '3'){
					$aReturn = array(
						'nStaReturn'		=> 3,
						'tMsgReturn'		=> 'Reset Password',
						'tUsrLogType'		=> $aDataUsr[0]['FTUsrLogType']
					);
				}else{
					$aDataUsrGroup  = $this->mLogin->FSaMLOGGetDataUserLoginGroup($aDataUsr[0]['FTUsrCode']);
					$aDataUsrRole   = $this->mLogin->FSaMLOGGetUserRole($aDataUsr[0]['FTUsrCode']);
					$aDataComp 	    = $this->mLogin->FSaMLOGGetBch();
					if( empty($aDataUsrGroup[0]['FTAgnCode']) && empty($aDataUsrGroup[0]['FTMerCode']) && empty($aDataUsrGroup[0]['FTBchCode']) && empty($aDataUsrGroup[0]['FTShpCode'])){
					
						$tUsrAgnCodeDefult  = '';
						$tUsrAgnNameDefult  = '';

						$tUsrMerCodeDefult  = '';
						$tUsrMerNameDefult  = '';

						$tUsrBchCodeDefult  = $aDataComp[0]['FTBchCode'];
						$tUsrBchNameDefult  = $aDataComp[0]['FTBchName'];
						$tUsrBchCodeMulti	= "'".$aDataComp[0]['FTBchCode']."'";
						$tUsrBchNameMulti	= "'".$aDataComp[0]['FTBchName']."'";
						$nUsrBchCount		= 0;

						$tUsrShpCodeDefult  = $aDataComp[0]['FTShpCode'];
						$tUsrShpNameDefult  = $aDataComp[0]['FTShpName'];
						$tUsrShpCodeMulti 	= "'".$aDataComp[0]['FTShpCode']."'";
						$tUsrShpNameMulti 	= "'".$aDataComp[0]['FTShpName']."'";
						$nUsrShpCount		= 0;

						$tUsrWahCodeDefult  = $aDataComp[0]['FTWahCode'];
						$tUsrWahNameDefult  = $aDataComp[0]['FTWahName'];
					}else{
						$tUsrAgnCodeDefult  = $aDataUsrGroup[0]['FTAgnCode'];
						$tUsrAgnNameDefult  = $aDataUsrGroup[0]['FTAgnName'];
						
						$tUsrMerCodeDefult  = $aDataUsrGroup[0]['FTMerCode'];
						$tUsrMerNameDefult  = $aDataUsrGroup[0]['FTMerName'];

						$tUsrBchCodeDefult  = $aDataUsrGroup[0]['FTBchCode'];
						$tUsrBchNameDefult  = $aDataUsrGroup[0]['FTBchName'];
						$tUsrBchCodeMulti 	= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTBchCode','value');
						if($tUsrBchCodeMulti == ''){
							$tUsrBchCodeMulti = "'x'";
						}
						$tUsrBchNameMulti 	= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTBchName','value');
						$nUsrBchCount		= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTBchCode','counts');

						$tUsrShpCodeDefult  = $aDataUsrGroup[0]['FTShpCode'];
						$tUsrShpNameDefult  = $aDataUsrGroup[0]['FTShpName'];
						$tUsrShpCodeMulti 	= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTShpCode','value');
						$tUsrShpNameMulti 	= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTShpName','value');
						$nUsrShpCount		= $this->mLogin->FStMLOGMakeArrayToString($aDataUsrGroup,'FTShpCode','counts');

						$tUsrWahCodeDefult  = $aDataUsrGroup[0]['FTWahCode'];
						$tUsrWahNameDefult  = $aDataUsrGroup[0]['FTWahName'];
					}
					$tUsrRoleMulti  = $this->mLogin->FStMLOGMakeArrayToString($aDataUsrRole,'FTRolCode','value');
					$nUsrRoleLevel  = $this->mLogin->FSaMLOGGetUserRoleLevel($tUsrRoleMulti);
					$tUsrBchHQCode  = $aDataComp[0]['FTBchCode'];
					$tUsrBchHQName  = $aDataComp[0]['FTBchName'];
					$this->session->set_userdata("tUsrBchHQCode", $tUsrBchHQCode);
					$this->session->set_userdata("tUsrBchHQName", $tUsrBchHQName);
					//Set Cookie Pin
                    if ($tUsrLogType == 2) {
						$aCookieUsername = array(
							'name'    => 'FTUsrLogin',
							'value'   => base64_encode($tUsername),
							'expire'  => 31556926,
						);
						$this->input->set_cookie($aCookieUsername);

						$aCookieUsrCode = array(
							'name'    => 'FTUsrCode',
							'value'   => base64_encode($tUsrCode),
							'expire'  => 31556926,
						);
						$this->input->set_cookie($aCookieUsrCode);
                    }

					// User Role
					$this->session->set_userdata("tSesUsrRoleCodeMulti", $tUsrRoleMulti);
					$this->session->set_userdata("nSesUsrRoleLevel", $nUsrRoleLevel);

					// Agency
					$this->session->set_userdata("tSesUsrAgnCode", $tUsrAgnCodeDefult);
					$this->session->set_userdata("tSesUsrAgnName", $tUsrAgnNameDefult);

					// Merchant
					$this->session->set_userdata("tSesUsrMerCode", $tUsrMerCodeDefult);
					$this->session->set_userdata("tSesUsrMerName", $tUsrMerNameDefult);

					// Branch
					$this->session->set_userdata("tSesUsrBchCodeDefault", $tUsrBchCodeDefult);
					$this->session->set_userdata("tSesUsrBchNameDefault", $tUsrBchNameDefult);
					$this->session->set_userdata("tSesUsrBchCodeMulti", $tUsrBchCodeMulti);
					$this->session->set_userdata("tSesUsrBchNameMulti", $tUsrBchNameMulti);
					$this->session->set_userdata("nSesUsrBchCount", $nUsrBchCount);

					// Shop
					$this->session->set_userdata("tSesUsrShpCodeDefault", $tUsrShpCodeDefult);
					$this->session->set_userdata("tSesUsrShpNameDefault", $tUsrShpNameDefult);
					$this->session->set_userdata("tSesUsrShpCodeMulti", $tUsrShpCodeMulti);
					$this->session->set_userdata("tSesUsrShpNameMulti", $tUsrShpNameMulti);
					$this->session->set_userdata("nSesUsrShpCount", $nUsrShpCount);

					// WaHouse
					$this->session->set_userdata("tSesUsrWahCode", $tUsrWahCodeDefult);
					$this->session->set_userdata("tSesUsrWahName", $tUsrWahNameDefult);

					// Login Level
					$this->session->set_userdata("tSesUsrLoginLevel", $aDataUsrGroup[0]['FTLoginLevel']);

					// Login Status Agency
					$this->session->set_userdata("tSesUsrLoginAgency", $aDataUsrGroup[0]['FTStaLoginAgn']);

					$this->session->set_userdata('bSesLogIn',TRUE);
					$this->session->set_userdata("tSesUserCode", $aDataUsr[0]['FTUsrCode']);
					$this->session->set_userdata("tSesUsername", $aDataUsr[0]['FTUsrCode']);			
					$this->session->set_userdata("tSesUsrDptName", $aDataUsr[0]['FTDptName']);
					$this->session->set_userdata("tSesUsrDptCode", $aDataUsr[0]['FTDptCode']);
					$this->session->set_userdata("tSesUsrUsername", $aDataUsr[0]['FTUsrName']);

					$this->session->set_userdata("tSesUsrImagePerson", $aDataUsr[0]['FTImgObj']);
					
					$this->session->set_userdata("tSesUsrInfo", $aDataUsr[0]);
					$this->session->set_userdata("tSesUsrGroup", $aDataUsrGroup);

					$tDateNow 			= date('Y-m-d H:i:s');
					$tSessionID 		= $aDataUsr[0]['FTUsrCode'].date('YmdHis', strtotime($tDateNow)); 
					$this->session->set_userdata("tSesSessionID", $tSessionID);
					$this->session->set_userdata("tSesSessionDate", $tDateNow);

					$nLangEdit = $this->session->userdata("tLangEdit");
					if($nLangEdit == ''){
						$this->session->set_userdata( "tLangEdit", $this->session->userdata("tLangID") );
					}

					// User Have Agen
					if(!empty($aDataUsrGroup[0]['FTAgnCode'])){
						$this->session->set_userdata("bIsHaveAgn", true);

						// ถ้าเป็นตัวแทนขาย ต้องเช็คว่าเแ็นตัวแทนขาย หรือ แฟรนด์ไซส์ 
						// จะมีผลต่อการมองเห็น และ การซื้อ
						// 1 : ตัวแทนขาย , 2 : แฟรนด์ไซส์ 
						$this->session->set_userdata("tAgnType", $aDataUsrGroup[0]['FTAgnType']);
					}else{
						$this->session->set_userdata("bIsHaveAgn", false);
						$this->session->set_userdata("tAgnType", 0);
					}
					
					// User level
					$this->session->set_userdata("tSesUsrLevel", "");
					if( empty($aDataUsrGroup[0]['FTAgnCode']) && empty($aDataUsrGroup[0]['FTBchCode']) && empty($aDataUsrGroup[0]['FTShpCode'])){ // HQ level
						$this->session->set_userdata("tSesUsrLevel", "HQ");
					}
					if(!empty($aDataUsrGroup[0]['FTBchCode']) && empty($aDataUsrGroup[0]['FTShpCode'])){ // BCH level
						$this->session->set_userdata("tSesUsrLevel", "BCH");
					}
					if(!empty($aDataUsrGroup[0]['FTBchCode']) && !empty($aDataUsrGroup[0]['FTShpCode'])){ // SHP level
						$this->session->set_userdata("tSesUsrLevel", "SHP");
					}
                    if(!empty($aDataUsrGroup[0]['FTBchCode']) && !empty($aDataUsrGroup[0]['FTMerCode'])){ // MER & SHP level
						$this->session->set_userdata("tSesUsrLevel", "SHP");
					}

					$tSesUsrRoleCodeMultiSpc 	= "";
					$aDataWhereChain = array(
						'tUsrRoleMulti'	=> $tUsrRoleMulti,
						'tLoginLevel' 	=> $aDataUsrGroup[0]['FTLoginLevel'],
						'tAgnCode'		=> $tUsrAgnCodeDefult,
						'tBchCodeMulti'	=> $tUsrBchCodeMulti
					);
					$aDataUsrRoleChain  		= $this->mLogin->FSaMLOGGetUserRoleChain($aDataWhereChain);

					if( $aDataUsrRoleChain['tCode'] == '1' ){
						$tSesUsrRoleCodeMultiSpc = $this->mLogin->FStMLOGMakeArrayToString($aDataUsrRoleChain['aItems'],'FTRolCode','value');
					}

					$this->session->set_userdata("tSesUsrRoleSpcCodeMulti", $tSesUsrRoleCodeMultiSpc);

					//ตั้งค่าเวลากดบันทึก จะเป็น => เป็นบันทึกและดูเสมอ
					$this->session->set_userdata ("tBtnSaveStaActive",1);

					// Set สิทธิในการมองเห็นตัวแทนขาย
					FCNbLoadConfigIsAgnEnabled();

					// Set สิทธิในการมองเห็น หมวดหมู่สินค้า
					FCNxLoadConfigIsCategoryEnabled();

					//ถ้าเข้ามาแบบเว็บปกติ ให้สนใจข้อมูลพวกนี้
					if( $this->session->userdata('tStaByPass') == 2){
						// Set สิทธิในการมองเห็นตัวแทนขาย
						FCNbLoadConfigIsAgnEnabled();
		
						// Set สิทธิในการมองเห็นร้านค้า
						FCNbLoadConfigIsShpEnabled();

						// Set สิทธิในการมองเห็น Locker
						FCNbLoadConfigIsLockerEnabled();

						// Set สิทธิในการมองเห็น สินค้าแฟชั่น
						FCNbLoadPdtFasionEnabled();

						// Set สิทธิในการมองเห็น หมวดหมู่สินค้า
						FCNxLoadConfigIsCategoryEnabled();

						// Delete Doc Temp
						$this->load->helper('document');
						// FCNoHDOCDeleteDocTmp();

						// Clear Report Temp
						$this->load->helper('report');
						// FCNoHDOCClearRptTmp();
						
						// Delete Temp Card
						$this->load->helper('card');
						// FCNoCARDataListDeleteAllTable();

						// ลบรูปภาพใน Temp Server ตาม User
						$aGetFile = glob("application/modules/common/assets/system/systemimage/".$this->session->userdata('tSesUserCode')."/*"); // get all file names
						foreach($aGetFile as $tPathFile){ 
							if( is_file($tPathFile) ){
								unlink($tPathFile); // delete file
							}
						}
					}

					//เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
					$aCookieAgn = array(
						'name'	=> 'tAgnCode',
						'value' => json_encode($this->session->userdata('tSesUsrAgnCode')),
						'expire' => 0
					);
					$this->input->set_cookie($aCookieAgn);

					$aCookieBch = array(
						'name'	=> 'tBchCode',
						'value' => json_encode($this->session->userdata('tSesUsrBchCodeDefault')),
						'expire' => 0
					);
					$this->input->set_cookie($aCookieBch);

					$aCookieUsr = array(
						'name'	=> 'tUsrCode',
						'value' => json_encode($this->session->userdata('tSesUserCode')),
						'expire' => 0
					);
					$this->input->set_cookie($aCookieUsr);

					$aCookieMenuCode = array(
						'name'	=> 'tMenuCode',
						'value' => json_encode('H00000'),
						'expire' => 0
					);
					$this->input->set_cookie($aCookieMenuCode);
			
					$aCookieMenuName = array(
						'name'	=> 'tMenuName',
						'value' => json_encode('Home Page'),
						'expire' => 0
					);
					$this->input->set_cookie($aCookieMenuName);

					//เก็บค่าทศนิยมในการแสดงในรูปของ Cookie ทุกครั้งที่มีการ Login เข้ามา เพื่อใช้แสดงในเอกสารและรายงาน
					// Parameters: Ajax and Function Parameter
					// Creator: 23/04/2022 Sittikorn(Off)
					$tOptCookie = get_cookie('tOptDecimalShow');
					if($tOptCookie != NULL || $tOptCookie != ''){
						$nOptDecimalShow = $tOptCookie;
					}else{
						$nOptDecimalShow = FCNxHGetOptionDecimalShow();
						$aCookieMenuCode = array(
							'name' => 'tOptDecimalShow',
							'value' => $nOptDecimalShow,
							'expire' => 0
						);
						$this->input->set_cookie($aCookieMenuCode);
					}
					//เก็บค่าทศนิยมในการแสดงในรูปของ Cookie ทุกครั้งที่มีการ Login เข้ามา เพื่อใช้แสดงในเอกสารและรายงาน
					// Creator: 04/03/2022 Sittikorn(Off)
					$tOptCookieSave = get_cookie('tOptDecimalSave');
					if($tOptCookieSave != NULL || $tOptCookieSave != ''){
						$nOptDecimalSave = $tOptCookieSave;
					}else{
						$nOptDecimalSave = FCNxHGetOptionDecimalSave();
						$aCookieMenuCodeSave = array(
							'name' => 'tOptDecimalSave',
							'value' => $nOptDecimalSave,
							'expire' => 0
						);
						set_cookie($aCookieMenuCodeSave);
					}
					//เก็บค่า DocSave ในการแสดงในรูปของ Cookie ทุกครั้งที่มีการ Login เข้ามา เพื่อใช้แสดงในเอกสารและรายงาน
					// Creator: 04/25/2022 Sittikorn(Off)
					$tOptCookieDocSave = get_cookie('tOptDocSave');
					if($tOptCookieDocSave != NULL || $tOptCookieDocSave != ''){
						$nOptDocSave = $tOptCookieDocSave;
					}else{
						$nOptDocSave = FCNnHGetOptionDocSave();
						$aCookieDocSave = array(
							'name' => 'tOptDocSave',
							'value' => $nOptDocSave,
							'expire' => 0
						);
						set_cookie($aCookieDocSave);
					}
					//เก็บค่า ScanSku ในการแสดงในรูปของ Cookie ทุกครั้งที่มีการ Login เข้ามา เพื่อใช้แสดงในเอกสารและรายงาน
					// Creator: 04/25/2022 Sittikorn(Off)
					$tOptCookieScanSku = get_cookie('tOptScanSku');
					if($tOptCookieScanSku != NULL || $tOptCookieScanSku != ''){
						$nOptScanSku = $tOptCookieScanSku;
					}else{
						$nOptScanSku = FCNnHGetOptionScanSku();
						$aCookieScanSku = array(
							'name' => 'tOptScanSku',
							'value' => $nOptScanSku,
							'expire' => 0
						);
						set_cookie($aCookieScanSku);
					}

					// จำนวนเเสดงข้อมูลรายการสูงสุดของหน้า List 
					// Parameters: Ajax and Function Parameter
					// Creator: 23/04/2022 Supawat
					// $tShowRecordInPageList = get_cookie('nShowRecordInPageList');
					// if($tShowRecordInPageList != NULL || $tShowRecordInPageList != ''){
					// 	$nShowRecordInPageList = $tShowRecordInPageList;
					// }else{
					$nShowRecordInPageList = $this->mLogin->FStMFindConfigShowRecord();
					$aPackData 	= array(
						'name' 		=> 'nShowRecordInPageList',
						'value' 	=> $nShowRecordInPageList,
						'expire' 	=> 0
					);
					$this->input->set_cookie($aPackData);
					// }

					$tCkiRdmCode = get_cookie('tCkiRdmCode');
					if($tCkiRdmCode != NULL || $tCkiRdmCode != ''){
						$tCkiRdmCode = $tCkiRdmCode;
					}else{
						$tCkiRdmCode = chr(mt_rand(97, 122)).substr(md5(time()), 1);
						$atCkiRdmCode = array(
							'name' => 'tCkiRdmCode',
							'value' => $tCkiRdmCode,
							'expire' => 0
						);
						set_cookie($atCkiRdmCode);
					}

					$aReturn = array(
						'aItems'		=> $aDataUsr,
						'nStaReturn'	=> 1,
						'tMsgReturn'	=> 'Found Data'
					);

				}
			}else{
				$aReturn = array(
					'aItems'		=> $aDataUsr,
					'nStaReturn'	=> 99,
					'tMsgReturn'	=> 'Not Fround Data'
				);
			}
		}catch(Exception $e) {
			$aReturn = array(
				'aItems'		=> array(),
				'nStaReturn'	=> 500,
				'tMsgReturn'	=> $e
			);
		}

		echo json_encode($aReturn);
	}

	//รันสคริปท์ temp
	public function FSaCLOGSetUpAdaStoreBack(){
		try {
			// Settings
			$this->load->model('authen/login/mLogin');
			$tDirScript     = "application/modules/authen/assets/SQLScript/*.sql";
			$nTotalScript   = count(glob($tDirScript));
			$nCount         = 0;
			$nSuccess       = 0;
			$nError         = 0;

			$tTimeStart = round(microtime(true) * 1000);
			echo "<div style='overflow-y:auto;height:70%;padding:15px;background-color:#efefef;border-radius:5px;'>";
			echo "<table>";
			if($nTotalScript > 0){
				$db_debug = $this->db->db_debug;
				$this->db->db_debug = FALSE;
				foreach (glob($tDirScript) as $tPathFile){
					echo "<tr>";
					$nCount++;
					$tFileName 			= basename($tPathFile,".sql");
					$tStatement  		= file_get_contents($tPathFile);
					$tTimeLoopStart 	= round(microtime(true) * 1000);
					$aStaExecute  		= $this->mLogin->FSaMLOGExecuteScript($tStatement);
					$tTimeLoopFinish 	= round(microtime(true) * 1000);
					$nDiffTimeProcess 	= $tTimeLoopFinish - $tTimeLoopStart;

					if( $aStaExecute['nStaQuery'] == 1 ){
						if( isset($aStaExecute['tStaMessage']) && $aStaExecute['tStaMessage']['code'] != '0000' ) {
							echo "<td>".$nCount.".</td>";
							echo "<td>".$tFileName."</td>";
							echo "<td><img src='application/modules/common/assets/images/icons/Not-Approve.png' width='18'></td>";
							echo "<td><span>$nDiffTimeProcess ms.</span> <span style='color:red;'>".$aStaExecute['tStaMessage']['message']."</span></td>";
							$nError++;
						}else{
							echo "<td>".$nCount.".</td>";
							echo "<td>".$tFileName."</td>";
							echo "<td><img src='application/modules/common/assets/images/icons/OK-Approve.png' width='18'></td>";
							echo "<td><span>$nDiffTimeProcess ms.</span></td>";
							$nSuccess++;
						}
					}else{
						print_r($aStaExecute['tStaMessage']);
					}
					echo "</tr>";
				}
				$this->db->db_debug = $db_debug;
			}else{
				echo "<tr><td align='center'>ไม่พบไฟล์สคริปท์ (".$tDirScript.")</td></tr>";
			}
			$tTimeFinish = round(microtime(true) * 1000);

			echo "</table>";
			echo "</div>";

			echo "<br>จำนวนทั้งหมด ".count(glob($tDirScript))." สคริปท์ <br>";
			echo "สำเร็จ ".$nSuccess." สคริปท์ <br>";
			echo "ล้มเหลว ".$nError." สคริปท์ <br>";

			$nDiffTimeProcess = ($tTimeFinish - $tTimeStart) / 1000;
			echo "<br>ใช้เวลา ".$nDiffTimeProcess." วินาที<br>";

		}catch(Exception $e) {
			print_r($e);
		}
	}

	public function FSaCLOGBrowseUserName(){
		try {
			$this->load->model('authen/login/mLogin');
			$tTextFilter	= $this->input->post('tTextFilter');

			$aData = $this->mLogin->FSaMLOGGetUserName($tTextFilter);
			foreach ($aData as $aDataList){
				$tDataList 	= '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">';
				$tDataList .= '<div class="wf-box1">';
				$tDataList .= '<div style="padding: 14px;" class="thumbnail">';
				$tDataList .= '<div class="form-group text-center xCNThumbnail">';
				$tDataList .= '<input type="hidden" id="oetUsrname'.$aDataList['FTUsrCode'].'" class="form-control" value="'.$aDataList['FTUsrName'].'">';
				$tDataList .= '<input type="hidden" id="oetUsrCodePin'.$aDataList['FTUsrCode'].'" class="form-control" value="'.$aDataList['FTUsrCode'].'">';
				if($aDataList['FTImgObj'] != ''){
					// $tImage = $aDataList['FTImgObj']; 
					// $tImage = explode("application/modules",$tImage);
					// $tPatchImg = base_url('application/modules/').$tImage[1];
					$tPatchImg = $aDataList['FTImgObj'];

					$tDataList .= '<input type="hidden" id="oetUsrimg'.$aDataList['FTUsrCode'].'" class="form-control" value="'.$tPatchImg.'">';
					$tDataList .= "<img src='".$tPatchImg."'>";
				}else{
					$tDataList .= '<input type="hidden" id="oetUsrimg'.$aDataList['FTUsrCode'].'" class="form-control" value="'.base_url('application/modules/common/assets/images/NoPhoto.png').'">';
					$tDataList .= "<img src=".base_url('application/modules/common/assets/images/NoPhoto.png').">";
				}
				$tDataList .= '<label style="font-weight: bold !important;; font-size: 20px !important;" class="xCNLabelFrm">'.$aDataList["FTUsrName"].'</label>';
				$tDataList .= '</div>';
				$tDataList .= '<div class="row text-center">';
				$tDataList .= '<div class="col-xs-12 col-md-12" align="center">';
				$tDataList .= '<button id="xCNIMGChooseImg"';
				$tDataList .= "";
				$tDataList .= 'class="btn xCNBTNPrimery" style="width:100%" onclick="JSxLOGSelectUsr('. "'" . $aDataList['FTUsrCode'] . "'" . ')">เลือก</button>';
				$tDataList .= '</div>';
				$tDataList .= '</div>';
				$tDataList .= '</div>';
				$tDataList .= '</div>';
				$tDataList .= '</div>';
				echo $tDataList;
			}
		}catch(Exception $e) {
			print_r($e);
		}
	}

	public function FSaCLOGGetUsrLoginPin(){
		try{
			$tUsrCode	= $this->input->post('oetUsrCode'); //ชื่อผู้ใช้
			$tPassword	= $this->input->post('oetPasswordhidden'); //รหัสผ่าน		
			$this->load->model('authen/login/mLogin');
			$aData 		= $this->mLogin->FSaMLOGGetUsrLogin($tUsrCode,$tPassword);
			if($aData['rtCode'] == 1){
				$aReturn = array(
					'aItems'		=> $aData,
					'nStaReturn'	=> 1,
					'tMsgReturn'	=> 'Found Data'
				);
			}else{
				$aReturn = array(
					'aItems'		=> $aData,
					'nStaReturn'	=> 99,
					'tMsgReturn'	=> 'Not Fround Data'
				);
			}
		}catch(Exception $e) {
			$aReturn = array(
				'aItems'		=> array(),
				'nStaReturn'	=> 500,
				'tMsgReturn'	=> $e
			);
		}

		echo json_encode($aReturn);
	}

	public function FSaCLOGGetUsrNameAndImg(){
		try{
			$tUsrCode	= $this->input->post('tUsrCode'); //ชื่อผู้ใช้
			$this->load->model('authen/login/mLogin');
			$aData 		= $this->mLogin->FSaMLOGGetUsrNameAndImg($tUsrCode);
			if($aData['rtCode'] == 1){
				$aReturn = array(
					'aItems'		=> $aData,
					'nStaReturn'	=> 1,
					'tMsgReturn'	=> 'Found Data'
				);
			}else{
				$aReturn = array(
					'aItems'		=> $aData,
					'nStaReturn'	=> 99,
					'tMsgReturn'	=> 'Not Fround Data'
				);
			}
		}catch(Exception $e) {
			$aReturn = array(
				'aItems'		=> array(),
				'nStaReturn'	=> 500,
				'tMsgReturn'	=> $e
			);
		}

		echo json_encode($aReturn);
	}

}








