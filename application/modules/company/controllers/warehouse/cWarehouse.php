<?php
defined('BASEPATH') or exit('No direct script access allowed');
class cWarehouse extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('company/warehouse/mWarehouse');
		date_default_timezone_set("Asia/Bangkok");

		// Test XSS Load Helper Security
		$this->load->helper("security");
		if ($this->security->xss_clean($this->input->post(), TRUE) === FALSE){
			echo "ERROR XSS Filter";
		}

	}

	public function index($nBrowseType, $tBrowseOption){
		$aBrowseType = explode("-", $nBrowseType);
		if (isset($aBrowseType[1])) {
			$nBrowseType = $aBrowseType[0];
			$tRouteFromName = $aBrowseType[1];
		} else {
			$nBrowseType = $nBrowseType;
			$tRouteFromName = '';
		}

		$vBtnSave = FCNaHBtnSaveActiveHTML('warehouse/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
		$aAlwEventWarehouse = FCNaHCheckAlwFunc('warehouse/0/0');
		$this->load->view('company/warehouse/wWarehouse', array(
			'vBtnSave' => $vBtnSave,
			'nBrowseType' => $nBrowseType,
			'tRouteFromName' => $tRouteFromName,
			'tBrowseOption'	=> $tBrowseOption,
			'aAlwEventWarehouse' => $aAlwEventWarehouse
		));
	}

	//Functionality : Event Warehouse Edit
	//Parameters : Ajax jWarehouse()
	//Creator : 15/05/2018 Krit(Copter)
	//Last Modified : 05/09/2019 Saharat(Golf)
	//Return : Status ReasonEdit
	//Return Type : array
	public function FSaCWAHEditEvent(){
		try {
			$tWahCode 				= $this->input->post('oetWahCode');
			$tWahName 				= $this->input->post('oetWahName');
			$tWahStaType 			= $this->input->post('ocmWahStaType');
			$tWahStaChkStk 			= $this->input->post('ocmWahStaChkStk');
			$tWahStaPrcStk 			= $this->input->post('ocmWahStaPrcStk');
			$tBchCodeCreate 		= $this->input->post('oetWahBchCodeCreated');
			$tBchCodeRef 			= $this->input->post('oetWAHBchCode');
			$tSpnCodeRef 			= $this->input->post('oetWahSpnCode');
			$tPosCodeRef 			= $this->input->post('oetWahPosCode');
			
			$FTWahStaAlwCntStk 		= ($this->input->post('ocbWahCheckCntStk') == "1") ? '1' : '2'; // สถานะอนุญาต ตรวจสอบสต๊อกคงเหลือ(สำหรับขาย) 0: อนุญาติ  1: ไม่อนุญาติ
			$FTWahStaAlwCostAmt 	= ($this->input->post('ocbWahCheckCostAmt') == "1") ? '1' : '2'; // สถานะอนุญาต คำนวณมูลค่าสินค้า  0: อนุญาติ  1: ไม่อนุญาติ

			$FTWahStaAlwPLFrmTBO	= ($this->input->post('ocbWahCheckAlwPLFrmTBO')		== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบจ่ายโอน 1:อนุญาต 2:ไม่อนุญาต
			$FTWahStaAlwPLFrmSale	= ($this->input->post('ocbWahCheckAlwPLFrmSale') 	== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบขาย 1:อนุญาต 2:ไม่อนุญาต
			$FTWahStaAlwPLFrmSO		= ($this->input->post('ocbWahCheckAlwPLFrmSO')		== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบสั่งขาย 1:อนุญาต 2:ไม่อนุญาต

			// echo $tWahStaType;
			// die();
			$tWahRefCode = "";
			switch ($tWahStaType) {
				case '1':
					$tWahRefCode = $tBchCodeRef;
					break;
				case '2':
					// $tWahRefCode = $tBchCodeRef;
					$tWahRefCode = $tPosCodeRef;
					break;
					/* case '3':
					$tWahRefCode = $this->input->post('oetWAHBchCode');
				break; */
				case '4':
					$tWahRefCode = null;
					break;
				case '5':
					$tWahRefCode = $tSpnCodeRef;
					break;
				case '6':
					$tWahRefCode = $tPosCodeRef;
					break;

				default:
					$tWahRefCode = $this->input->post('oetWahRefCode');
			}

			$aDataMaster	= array(
				'FTWahCode' 			=> $tWahCode,
				'FTWahName' 			=> $tWahName,
				'FTWahStaType' 			=> $tWahStaType,
				'FTBchCode' 			=> $tBchCodeCreate,
				'FTBchCodeOld' 			=> $this->input->post('oetWAHBchCodeOld'),
				'FTWahRefCode' 			=> $tWahRefCode,
				'FNLngID' 				=> $this->session->userdata("tLangEdit"),
				'FDLastUpdOn' 			=> date('Y-m-d H:i:s'),
				'FTLastUpdBy' 			=> $this->session->userdata('tSesUsername'),
				'FTWahStaChkStk' 		=> $tWahStaChkStk,
				'FTWahStaPrcStk' 		=> $tWahStaPrcStk,
				'FTWahStaAlwCntStk' 	=> $FTWahStaAlwCntStk,
				'FTWahStaAlwCostAmt' 	=> $FTWahStaAlwCostAmt,
				// Check Status Allow
				'FTWahStaAlwPLFrmTBO'	=> $FTWahStaAlwPLFrmTBO,
				'FTWahStaAlwPLFrmSale'	=> $FTWahStaAlwPLFrmSale,
				'FTWahStaAlwPLFrmSO'	=> $FTWahStaAlwPLFrmSO,
				'FTWahStaAlwSNPL'		=> $FTWahStaAlwPLFrmSO,
			);

			$this->db->trans_begin();
			$aResAdd = $this->mWarehouse->FSaMWAHUpdate($aDataMaster);
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$aReturn = array(
					'nStaEvent' => '900',
					'tStaMessg' => "Unsucess Edit Event"
				);
			} else {
				$this->db->trans_commit();
				$aReturn = array(
					'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
					'tCodeReturn' => $aDataMaster['FTWahCode'],
					'nStaEvent'	=> '1',
					'tStaMessg' => 'Success Edit Event'
				);
			}
		} catch (Exception $Error) {
			$aReturn = array(
				'nStaEvent' => '500',
				'tStaMessg' => "Error 500 Func.Edit Event => " . $Error
			);
		}
		echo json_encode($aReturn);
	}

	//Functionality: Event Warehouse Add
	//Parameters: Ajax jReason()
	//Creator: 15/05/2018 Krit(Copter)
	//Last Modified: 26/03/2019 Wasin(Yoshi)
	//Return: Status Warehouse Add
	//ReturnType: array
	public function FSaCWAHAddEvent(){
		try {
			$tIsAutoGenCode		= $this->input->post('ocbWahAutoGenCode');
			$FTWahStaType 		=	$this->input->post('ocmWahStaType');
			$tWahStaChkStk 		= $this->input->post('ocmWahStaChkStk');
			$tWahStaPrcStk 		= $this->input->post('ocmWahStaPrcStk');
			$tBchCodeCreate 	= $this->input->post('oetWahBchCodeCreated');
			$tBchCodeRef 		= $this->input->post('oetWAHBchCode');
			$tSpnCodeRef 		= $this->input->post('oetWahSpnCode');
			$tPosCodeRef 		= $this->input->post('oetWahPosCode');

			$FTWahStaAlwCntStk	= ($this->input->post('ocbWahCheckCntStk') == "1") 	? '1' : '2'; // สถานะอนุญาต ตรวจสอบสต๊อกคงเหลือ(สำหรับขาย) 0: อนุญาติ  1: ไม่อนุญาติ
			$FTWahStaAlwCostAmt = ($this->input->post('ocbWahCheckCostAmt') == "1") ? '1' : '2'; // สถานะอนุญาต คำนวณมูลค่าสินค้า  0: อนุญาติ  1: ไม่อนุญาติ
			
			$FTWahStaAlwPLFrmTBO	= ($this->input->post('ocbWahCheckAlwPLFrmTBO')		== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบจ่ายโอน 1:อนุญาต 2:ไม่อนุญาต
			$FTWahStaAlwPLFrmSale	= ($this->input->post('ocbWahCheckAlwPLFrmSale') 	== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบขาย 1:อนุญาต 2:ไม่อนุญาต
			$FTWahStaAlwPLFrmSO		= ($this->input->post('ocbWahCheckAlwPLFrmSO')		== "1") ? '1' : '2'; // สถานะอนุญาต ใช้ใบจัดจากใบสั่งขาย 1:อนุญาต 2:ไม่อนุญาต

			// Setup Warehouse Code
			$tWahCode = "";
			if ($tIsAutoGenCode == '1') {
				// Call Auto Gencode Helper
				$aStoreParam = array(
					"tTblName" => 'TCNMWaHouse',
					"tDocType" => 0,
					"tBchCode" => $tBchCodeCreate,
					"tShpCode" => "",
					"tPosCode" => "",
					"dDocDate" => date("Y-m-d")
				);
				$aAutogen = FCNaHAUTGenDocNo($aStoreParam);
				$tWahCode = $aAutogen[0]["FTXxhDocNo"];
			} else {
				$tWahCode = $this->input->post('oetWahCode');
			}

			$tWahRefCode = "";
			switch ($FTWahStaType) {
				case '1':
					$tWahRefCode = $tBchCodeRef;
					break;
				case '2':
					// $tWahRefCode = $tBchCodeRef;
					$tWahRefCode = $tPosCodeRef;
					break;
					/* case '3':
					$tWahRefCode = $this->input->post('oetWAHBchCode');
				break; */
				case '4':
					$tWahRefCode = null;
					break;
				case '5':
					$tWahRefCode = $tSpnCodeRef;
					break;
				case '6':
					$tWahRefCode = $tPosCodeRef;
					break;

				default:
					$tWahRefCode = $this->input->post('oetWahRefCode');
			}

			$aDataMaster = array(
				'FTWahCode' 			=> $tWahCode,
				'FTWahStaType'			=> $FTWahStaType,
				'FTBchCode' 			=> $tBchCodeCreate,
				'FTWahRefCode' 			=> $tWahRefCode,
				'FDLastUpdOn' 			=> date('Y-m-d H:i:s'),
				'FTLastUpdBy' 			=> $this->session->userdata('tSesUsername'),
				'FDCreateOn' 			=> date('Y-m-d H:i:s'),
				'FTCreateBy' 			=> $this->session->userdata('tSesUsername'),
				'FNLngID' 				=> $this->session->userdata("tLangEdit"),
				'FTWahName'				=> $this->input->post('oetWahName'),
				'FTWahStaChkStk' 		=> $tWahStaChkStk,
				'FTWahStaPrcStk' 		=> $tWahStaPrcStk,
				'FTWahStaAlwCntStk' 	=> $FTWahStaAlwCntStk,
				'FTWahStaAlwCostAmt' 	=> $FTWahStaAlwCostAmt,
				// Check Status Allow
				'FTWahStaAlwPLFrmTBO'	=> $FTWahStaAlwPLFrmTBO,
				'FTWahStaAlwPLFrmSale'	=> $FTWahStaAlwPLFrmSale,
				'FTWahStaAlwPLFrmSO'	=> $FTWahStaAlwPLFrmSO,
				'FTWahStaAlwSNPL'		=> $FTWahStaAlwPLFrmSO,
			);
			$this->db->trans_begin();

			$this->mWarehouse->FSaMWAHAdd($aDataMaster);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$aReturn = array(
					'nStaEvent' => '900',
					'tStaMessg' => "Unsucess Add Event"
				);
			} else {
				$this->db->trans_commit();
				$aReturn = array(
					'nStaCallBack' => $this->session->userdata('tBtnSaveStaActive'),
					'tCodeReturn' => $aDataMaster['FTWahCode'],
					'nStaEvent'	=> '1',
					'tStaMessg'	=> 'Success Add Event'
				);
			}
		} catch (Exception $Error) {
			$aReturn = array(
				'nStaEvent' => '500',
				'tStaMessg' => "Error 500 Func.Add Event => " . $Error
			);
		}
		echo json_encode($aReturn);
	}

	//Call Page From (ขาแก้ไข)
	public function FSvCWAHEditPage($ptWahCode = '', $ptUserLevel = ''){
		// ส่ง BchCode มาจาก Function Check Level
		if (@$ptWahCode) {
			$tWahCode 	= $ptWahCode;
			$tUserLevel	= $ptUserLevel; // เก็บ User Level เพื่อใช้ในการ โชว์ปุ่ม Back
		} else {
			$tWahCode 	= $this->input->post('tWahCode');
			$tBchCode 	= $this->input->post('tBchCode');
			$tStaType 	= $this->input->post('tStaType'); /*ประเภทคลัง*/
			$tUserLevel	= ''; // ไม่ได้เข้ามาจาก Function Check Level จะมีค่า เป็น ว่าง
		}
		$nStaBrowse	= $this->input->post('nStaBrowse');
		$tTypePage 	= $this->input->post('tTypePage');      // สถานะ page : edit , add

		if ($nStaBrowse == '') {
			$nStaBrowse	= '99';
		}

		$aData = array(
			'FTWahCode'	=> $tWahCode,
			'FTBchCode' => $tBchCode,
			'FNLngID' 	=> $this->session->userdata("tLangEdit"),
		);
		
		$nGetStaWasteWAH	= FCNbIsGetRoleWasteWAH();
		$aResList 			= $this->mWarehouse->FSaMWAHSearchByID($aData);
		$aGetWahouse 		= $this->mWarehouse->FSaMWAHGetWahouseInConfig($nGetStaWasteWAH);

		//ตรวจสอบ Level ของ User
		$tSesUsrLevel =	$this->session->userdata("tSesUsrLevel");
		if ($tSesUsrLevel == "HQ") {
			$tStaUsrLevel = "HQ";
			$tUsrBchCode  = "";
		} else {
			$tStaUsrLevel = $this->session->userdata("tSesUsrLevel");
			$tUsrBchCode  = $this->session->userdata("tSesUsrBchCode");
		}

		$aDataEdit  = array(
			'nResult' 		=> $aResList,
			'nStaBrowse' 	=> $nStaBrowse,
			'tTypePage' 	=> $tTypePage,
			'tUserLevel'	=> $tUserLevel,
			'vWahStaType' 	=> '',
			'aGetWahouse' 	=> $aGetWahouse,
			'tStaUsrLevel' 	=> $tStaUsrLevel,
			'tUsrBchCode' 	=> $tUsrBchCode,
			'nStaWasteWAH'  => $nGetStaWasteWAH
		);
		$this->load->view('company/warehouse/wWarehouseAdd', $aDataEdit);
	}

	//Call Page From (ขาเพิ่ม)
	public function FSvCWAHAddPage()
	{
		
		$nGetStaWasteWAH = FCNbIsGetRoleWasteWAH();
		$aGetWahouse = $this->mWarehouse->FSaMWAHGetWahouseInConfig($nGetStaWasteWAH);

		// ตรวจสอบ Level ของ User
		$tSesUsrLevel =	$this->session->userdata("tSesUsrLevel");
		if ($tSesUsrLevel == "HQ") {
			$tStaUsrLevel = "HQ";
			$tUsrBchCode  = "";
		} else {
			$tStaUsrLevel = $this->session->userdata("tSesUsrLevel");
			$tUsrBchCode  = $this->session->userdata("tSesUsrBchCode");
		}
		$aDataEdit = array(
			'nResult' 		=> array('rtCode' => '99'),
			'vWahStaType' 	=> '',
			'aGetWahouse' 	=> $aGetWahouse,
			'tStaUsrLevel' 	=> $tStaUsrLevel,
			'tUsrBchCode' 	=> $tUsrBchCode,
			'nStaWasteWAH'  => $nGetStaWasteWAH
		);

		$this->load->view('company/warehouse/wWarehouseAdd', $aDataEdit);
	}

	//Event ลบคลังสินค้า
	public function FSaCWAHDeleteEvent()
	{
		$tIDCode = $this->input->post('tIDCode');
		$tBchCode = $this->input->post('tBchCode');
		if (is_array($tIDCode)) {
			if (!empty($tIDCode)) {
				foreach ($tIDCode as $nKey => $aData) {
					$aDataMaster = array(
						'FTWahCode' => $aData,
						'FTBchCode' => $tBchCode[$nKey]
					);
					$aResultDel = $this->mWarehouse->FSnMWAHDel($aDataMaster);
				}
			}
		} else {

			$aDataMaster = array(
				'FTWahCode' => $tIDCode,
				'FTBchCode' => $tBchCode
			);

			$aResultDel = $this->mWarehouse->FSnMWAHDel($aDataMaster);
		}

		$nNumRowWahLoc = $this->mWarehouse->FSnMLOCGetAllNumRow();
		if ($nNumRowWahLoc !== false) {
			$aReturn = array(
				'nStaEvent' => $aResultDel['rtCode'],
				'tStaMessg' => $aResultDel['rtDesc'],
				'nNumRowWahLoc' => $nNumRowWahLoc
			);
			echo json_encode($aReturn);
		} else {
			echo "database error!";
		}
	}

	//Check level ของผู้ใช้งาน
	public function FSvCWAHCheckUserLevel()
	{
		// Chk เปลี่ยนหน้าตาม Lv. ของผู้ใช้งาน
		$tUserLevel = $this->session->userdata("tSesUserLevel");
		$tUserBchCode = $this->session->userdata("tSesUserBchCode");

		if ($tUserLevel == '1') {
			$this->FSvCWAHListPage();
			// $this->load->view('pos5/branch/wBranchList',$aHTML);
		} else if ($tUserLevel == '2') {
			echo "Edit Page warehouse";
		} else if ($tUserLevel == '3') {
		}
	}

	//Call Page List
	public function FSvCWAHListPage()
	{
		$aAlwEventWarehouse	= FCNaHCheckAlwFunc('warehouse/0/0');
		$aNewData = array('aAlwEventWarehouse' => $aAlwEventWarehouse);
		$this->load->view('company/warehouse/wWarehouseList', $aNewData);
	}

	//Call Page Datatable
	public function FSvCWAHDataList()
	{
		$nPage = $this->input->post('nPageCurrent');
		$tSearchAll = $this->input->post('tSearchAll');

		if ($nPage == '' || $nPage == null) {
			$nPage = 1;
		} else {
			$nPage = $this->input->post('nPageCurrent');
		}

		// Lang ภาษา
		$nLangEdit = $this->session->userdata("tLangEdit");

		$aData  = array(
			'nPage' 		=> $nPage,
			'nRow' 			=> 10,
			'FNLngID' 		=> $nLangEdit,
			'tSearchAll' 	=> $tSearchAll
		);

		$aResList = $this->mWarehouse->FSnMWAHList($aData);
		$aAlwEventWarehouse	= FCNaHCheckAlwFunc('warehouse/0/0');
		$aGenTable  = array(
			'aDataList' 			=> $aResList,
			'nPage' 				=> $nPage,
			'tSearchAll' 			=> $tSearchAll,
			'aAlwEventWarehouse' 	=> $aAlwEventWarehouse
		);
		$this->load->view('company/warehouse/wWarehouseDataTable', $aGenTable);
	}
}
