<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cHome extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->helper('url');
        $this->load->library ( "session" );

        $bStaWindowsLogin = ( $this->input->get('oParams') != '' ? true : false );
        if( !$bStaWindowsLogin ){
            if (isset($_SESSION['tSesUsername'])) {
                if(@$_SESSION['tSesUsername'] == false) {
                    redirect ( 'login', 'refresh' );
                    exit ();
                }
            }
        }

        $this->load->model('common/mMenu');
        $this->load->model('favorite/favorite/mFavorites');
        $this->load->model('common/mNotification');
        $this->load->model('document/purchaseorder/mPurchaseOrder');
        $this->load->model('document/purchaseinvoice/mPurchaseInvoice');
        $this->load->model('document/transferreceiptNew/mTransferreceiptNew');
    }

    public function index($nMsgResp = ''){
        $nMsgResp       =  array('title' => "Home");
        $tUsrID         =  $this->session->userdata("tSesUsername");
        $nLngID         =  $this->session->userdata("tLangID");
        $nOwner         =  $this->session->userdata('tSesUserCode');

        $aAlwNoti       = $this->mNotification->FSaMMENUChkAlwNoti();

        //เก็บ Session ที่จำเป็นในการส่ง Log ไว้ใน Cookie
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

        if( $aAlwNoti['tCode'] == '1' ){
            foreach($aAlwNoti['aItems'] as $aValue){

                if( $aValue['FTAlwActive'] == 'Y' ){
                    $bAlwActive = true;
                }else{
                    $bAlwActive = false;
                }

                switch ($aValue['FTGrpNoti']) {
                    case 'NEWS':
                        $this->session->set_userdata("bSesAlwNews", $bAlwActive);
                        break;
                    default:
                        $this->session->set_userdata("bSesAlwNoti", $bAlwActive);
                        break;
                }
            }
        }

        // echo "bSesAlwNews: "; var_dump($this->session->userdata("bSesAlwNews"));
        // echo "bSesAlwNoti: "; var_dump($this->session->userdata("bSesAlwNoti"));
        // exit;

        $this->load->view ('common/wHeader',$nMsgResp);
		$this->load->view ('common/wTopBar',array('nMsgResp'=> $nMsgResp));

        if(isset($nLngID) && !empty($nLngID)){
            $nLngID = $this->session->userdata("tLangID");
        }else{
            $nLngID = 1;
        }

        $tBranchHQ      = $this->mMenu->FStMMENUGetBranchHQ(); // Get HQBch from Table Agency or Company
        $aChkLic        = $this->mMenu->FSaMMENUCheckLicense($tBranchHQ);
        

        //set seesion HQBch
        $this->session->set_userdata("tSesHQBchCode", $tBranchHQ);

        // echo "<pre>";
        // print_r($tBranchHQ);
        // print_r($aChkLic);
        // echo $this->session->userdata("tSesSessionID");
        // exit;
        

        //$aChkLic['tCode'] = 1;
        // ตรวจสอบ TRGTLicKey
        if( $aChkLic['tCode'] == '1' ){
            // สมัครสมาชิก + อนมัติแล้ว
            $tCstKey = $aChkLic['aItems'][0]['FTCstKey'];
            $this->session->set_userdata("bSesRegStaLicense", true);
            //$tCstKey = '7a05f6eb2e9e';
            
            $this->session->set_userdata("tSesCstKey", $tCstKey);

            $aMenuFav       = $this->mFavorites->FSaFavGetdataList($nOwner,$nLngID);
            $oGrpModules    = $this->mMenu->FSaMMENUGetMenuGrpModulesName($tUsrID,$nLngID);
            $oMenuList 	    = $this->mMenu->FSoMMENUGetMenuList($tUsrID,$nLngID,$tCstKey);
            $aChkBuyPackage = $this->mMenu->FSaMMENUCheckBuyPackage($tCstKey);

            if( $aChkBuyPackage['tCode'] == '1' ){
                $this->session->set_userdata("bSesRegStaBuyPackage", true);
            }else{
                $this->session->set_userdata("bSesRegStaBuyPackage", false);
            }

        }else{
            // สมัครสมาชิก + ยังไม่อนุมัติ หรือ ยังไม่ได้สมัครสมาชิก
            $this->session->set_userdata("bSesRegStaLicense", false);
            $this->session->set_userdata("bSesRegStaBuyPackage", false);
            $this->session->set_userdata("tSesCstKey", '');

            $aMenuFav       = false;
            $oGrpModules    = false;
            $oMenuList 	    = false;
            // $aAlwMnuLic     = array();
        }

        $this->load->view ('common/wMenu',array(
            'aMenuFav'	    => $aMenuFav,
            'nMsgResp'	    => $nMsgResp,
            'oGrpModules'   => $oGrpModules,
            'oMenuList'     => $oMenuList,
            'tUsrID'        => $tUsrID
        ));
        
        
        $this->load->view('common/wWellcome', $nMsgResp);
        $this->load->view('common/wFooter',array('nMsgResp' => $nMsgResp));
    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Insdata Insert ลง ตาราง Notification
    public function FSxAddDataNoti(){
        try{
            
            $aResData =  $this->input->post('tDataNoti');

            foreach($aResData['ptData']['paContents'] AS $nKey => $aValue){
               $tSubTopic  =  $aValue['ptFTSubTopic'];
               $tMsg       =  $aValue['ptFTMsg'];
            } 

            $aData = array(
                'FTMsgID'       => $aResData['ptFunction'],
                'FTBchCode'     => $this->session->userdata('tSesUsrBchCom'),
                'FDNtiSendDate' => $aResData['ptData']['ptFDSendDate'],
                'FTNtiID'       => $aResData['ptData']['ptFTNotiId'],
                'FTNtiTopic'    => $aResData['ptData']['ptFTTopic'],
                'FTNtiContents' => json_encode($aResData['ptData']['paContents']),
                'FTNtiUsrRole'  => $aResData['ptData']['ptFTUsrRole'],
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'tSource'       => $aResData['ptSource'],
                'tDest'         => $aResData['ptDest'],
                'tFilter'       => $aResData['ptFilter']
            );


            $this->db->trans_begin();

            // Check ข้อมูลซ้ำ TCNTNoti (FTMsgID)
            // เงื่อนไข : ถ้า Check แล้วเกิดข้อมูลซ้ำจะไม่ Insert TCNTNoti
            $aChkDupNotiMsgID   = $this->mNotification->FSaMCheckNotiMsgID($aData);

            if($aChkDupNotiMsgID['rtCode'] == 1){
                $aReturn = array(
                    'nStaEvent'    => '905',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                $aResult = $this->mNotification->FSaMAddNotification($aData);
                
                if($this->db->trans_status() == false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Success Add Data"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Data',
                    );
                }
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Getdata Insert ลง ตาราง Notification
    public function FSxGetDataNoti(){

        $aData = $this->mNotification->FSaMGetNotification();
        if($aData['rtCode'] == 900){
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess Success Add Data"
            );
        }else{
            $aReturn = array(
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success Add Data',
                'aData'         => $aData['raItems']
            );
        }
        echo json_encode($aReturn);
    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Getdata Read 
    public function FSxGetDataNotiRead(){
        $this->mNotification->FSaMMoveDataTableNotiToTableRead();
    }

    //Create by supawat 03/07/2020
    public function FSxImpImportFileExcel(){
        $this->load->model('common/mCommon');
        $aPackData      = $this->input->post('aPackdata');
        $tNameModule    = $this->input->post('tNameModule');
        $tTypeModule    = $this->input->post('tTypeModule');
        $tFlagClearTmp  = $this->input->post('tFlagClearTmp');
        $tImportDocumentNo  = $this->input->post('tImportDocumentNo');
        $tImportFrmBchCode  = $this->input->post('tImportFrmBchCode');
        $tImportSplVatRate  = $this->input->post('tImportSplVatRate');
        $tImportSplVatCode  = $this->input->post('tImportSplVatCode');
        $nPackData      = FCNnHSizeOf($aPackData);

        //เลือกใช้ตาราง
        if($tTypeModule == 'document'){
             //ถ้าเป็นเอกสารจะใช้ตาราง TCNTDocDTTmp
            $tTableName = 'TCNTDocDTTmp';
        }else if($tTypeModule == 'master'){
             //ถ้าเป็นมาส์เตอร์จะใช้ตาราง TCNTImpMasTmp
            $tTableName = 'TCNTImpMasTmp';
        }

        //เงื่อนไข ที่มีผลต่อตาราง
        switch ($tNameModule) {
            case "branch":
                $aTableRefPK = ['TCNMBranch'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "adjprice":
                $aTableRefPK = ['TCNTPdtAdjPriHD'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "user":
                $aTableRefPK = ['TCNMUser'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "pos":
                $aTableRefPK = ['TCNMPos'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "product":
                $aTableRefPK = ['TCNMPDT','TCNMPdtUnit','TCNMPdtBrand','TCNMPdtTouchGrp','TCNMPdtSpcBch'];
                $tTableRefPK = $aTableRefPK;
            break;
            case "purchaseorder":
                $aTableRefPK = ['TAPTPoHD'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "purchaseinvoice":
                $aTableRefPK = ['TAPTPiHD'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "adjcost":
                $aTableRefPK = ['TCNTPdtAdjCostHD'];
                $tTableRefPK = $aTableRefPK[0];
            break;
            case "transferreceipt":
                $aTableRefPK = ['TCNTPdtTwiHD'];
                $tTableRefPK = $aTableRefPK[0];
            break;
        }

        //เงื่อนไข ที่มีผลต่อตาราง
        $aWhereData = array(
            'tTableRefPK'       => $tTableRefPK,
            'tTableNameTmp'	    => $tTableName,
            'tFlagClearTmp'     => $tFlagClearTmp,
            'tTypeModule'       => $tTypeModule,
            'tNameModule'		=> $tNameModule,
            'tSessionID'        => $this->session->userdata("tSesSessionID")
        );


        //ถ้าเป็นการนำเข้าจากหน้าจอสินค้า จะพิเศษกว่าอันอื่น
        if ($tNameModule == 'product') {

            $this->mCommon->FCNaMCMMDeleteTmpExcelCasePDT($aWhereData);

            //เพิ่ม ทัสกลุ่ม
            $aSumSheetTGroup = array();
            if (isset($aPackData[7])) {
                for ($tTGROUP = 0; $tTGROUP < FCNnHSizeOf($aPackData[7]); $tTGROUP++) {
                    $aTGroup = array(
                        'FTTmpTableKey'     => 'TCNMPdtTouchGrp',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tTGROUP + 1,
                        'FTTcgCode'         => $aPackData[7][$tTGROUP][0],
                        'FTTcgName'         => $aPackData[7][$tTGROUP][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[7][$tTGROUP][2]) == '') ? '' : $aPackData[7][$tTGROUP][2],
                        'FTTmpRemark'       => (isset($aPackData[7][$tTGROUP][3]) == '') ? '' : $aPackData[7][$tTGROUP][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetTGroup, $aTGroup);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetTGroup);

                //validate ข้อมูลซ้ำในตาราง Tmp _ ทัสกลุ่ม
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTTcgCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ ทัสกลุ่ม
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTTcgCode',
                    'tTableName'        => 'TCNMPdtTouchGrp'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม กลุ่มสินค้า
            $aSumSheetPdtGroup = array();
            if (isset($aPackData[6])) {
                for ($tPdtGroup = 0; $tPdtGroup < FCNnHSizeOf($aPackData[6]); $tPdtGroup++) {
                    $aPdtGroup = array(
                        'FTTmpTableKey'     => 'TCNMPdtGrp',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tPdtGroup + 1,
                        'FTPgpChain'        => $aPackData[6][$tPdtGroup][0],
                        'FTPgpName'         => $aPackData[6][$tPdtGroup][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[6][$tPdtGroup][2]) == '') ? '' : $aPackData[6][$tPdtGroup][2],
                        'FTTmpRemark'       => (isset($aPackData[6][$tPdtGroup][3]) == '') ? '' : $aPackData[6][$tPdtGroup][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetPdtGroup, $aPdtGroup);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetPdtGroup);

                //validate ข้อมูลซ้ำในตาราง Tmp _ กลุ่มสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPgpChain'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ กลุ่มสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPgpChain',
                    'tTableName'        => 'TCNMPdtGrp'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม รุ่นสินค้า
            $aSumSheetPdtModel = array();
            if (isset($aPackData[5])) {
                for ($tPdtModel = 0; $tPdtModel < FCNnHSizeOf($aPackData[5]); $tPdtModel++) {
                    $aPdtModel = array(
                        'FTTmpTableKey'     => 'TCNMPdtModel',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tPdtModel + 1,
                        'FTPmoCode'         => $aPackData[5][$tPdtModel][0],
                        'FTPmoName'         => $aPackData[5][$tPdtModel][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[5][$tPdtModel][2]) == '') ? '' : $aPackData[5][$tPdtModel][2],
                        'FTTmpRemark'       => (isset($aPackData[5][$tPdtModel][3]) == '') ? '' : $aPackData[5][$tPdtModel][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetPdtModel, $aPdtModel);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetPdtModel);

                //validate ข้อมูลซ้ำในตาราง Tmp _ รุ่นสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPmoCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ รุ่นสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPmoCode',
                    'tTableName'        => 'TCNMPdtModel'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม ประเภทสินค้า
            $aSumSheetPdtType = array();
            if (isset($aPackData[4])) {
                for ($tPdtType = 0; $tPdtType < FCNnHSizeOf($aPackData[4]); $tPdtType++) {
                    $aPdtType = array(
                        'FTTmpTableKey'     => 'TCNMPdtType',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tPdtType + 1,
                        'FTPtyCode'         => $aPackData[4][$tPdtType][0],
                        'FTPtyName'         => $aPackData[4][$tPdtType][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[4][$tPdtType][2]) == '') ? '' : $aPackData[4][$tPdtType][2],
                        'FTTmpRemark'       => (isset($aPackData[4][$tPdtType][3]) == '') ? '' : $aPackData[4][$tPdtType][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetPdtType, $aPdtType);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetPdtType);

                //validate ข้อมูลซ้ำในตาราง Tmp _ ประเภทสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPtyCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ ประเภทสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPtyCode',
                    'tTableName'        => 'TCNMPdtType'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม แบรนด์
            $aSumSheetBrand = array();
            if (isset($aPackData[3])) {
                for ($tBrand = 0; $tBrand < FCNnHSizeOf($aPackData[3]); $tBrand++) {
                    $aBrand = array(
                        'FTTmpTableKey'     => 'TCNMPdtBrand',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tBrand + 1,
                        'FTPbnCode'         => $aPackData[3][$tBrand][0],
                        'FTPbnName'         => $aPackData[3][$tBrand][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[3][$tBrand][2]) == '') ? '' : $aPackData[3][$tBrand][2],
                        'FTTmpRemark'       => (isset($aPackData[3][$tBrand][3]) == '') ? '' : $aPackData[3][$tBrand][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetBrand, $aBrand);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetBrand);

                //validate ข้อมูลซ้ำในตาราง Tmp _ แบรนด์
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPbnCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ แบรนด์
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPbnCode',
                    'tTableName'        => 'TCNMPdtBrand'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม หน่วยสินค้า
            $aSumSheetUnit = array();
            if (isset($aPackData[2])) {
                for ($tUnit = 0; $tUnit < FCNnHSizeOf($aPackData[2]); $tUnit++) {
                    $aUnit = array(
                        'FTTmpTableKey'     => 'TCNMPdtUnit',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tUnit + 1,
                        'FTPunCode'         => $aPackData[2][$tUnit][0],
                        'FTPunName'         => $aPackData[2][$tUnit][1],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[2][$tUnit][2]) == '') ? '' : $aPackData[2][$tUnit][2],
                        'FTTmpRemark'       => (isset($aPackData[2][$tUnit][3]) == '') ? '' : $aPackData[2][$tUnit][3],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetUnit, $aUnit);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetUnit);

                //validate ข้อมูลซ้ำในตาราง Tmp _ หน่วยสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ หน่วยสินค้า
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode',
                    'tTableName'        => 'TCNMPdtUnit'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);
            }

            //เพิ่ม ข้อมูลสินค้า
            $aSumSheetPDT   = array();
            $aPackPdtSpcBch = array();
            $aPdtCallBackFunction = array();
            $aPdtDupBarCallBackFunction = array();
            if (isset($aPackData[1])) {
                for ($tPDT = 0; $tPDT < FCNnHSizeOf($aPackData[1]); $tPDT++) {
                    $aPDT = array(
                        'FTTmpTableKey'     => 'TCNMPdt',
                        'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                        'FNTmpSeq'          => $tPDT + 1,
                        'FTPdtCode'         => (isset($aPackData[1][$tPDT][0]) == '') ? '' : trim($aPackData[1][$tPDT][0]),
                        'FTPdtName'         => (isset($aPackData[1][$tPDT][1]) == '') ? '' : $aPackData[1][$tPDT][1],
                        'FTPdtNameABB'      => (isset($aPackData[1][$tPDT][2]) == '') ? '' : $aPackData[1][$tPDT][2],
                        'FTPunCode'         => (isset($aPackData[1][$tPDT][3]) == '') ? '' : $aPackData[1][$tPDT][3],
                        'FCPdtUnitFact'     => (isset($aPackData[1][$tPDT][4]) == '') ? '' : $aPackData[1][$tPDT][4],
                        'FTBarCode'         => (isset($aPackData[1][$tPDT][5]) == '') ? '' : $aPackData[1][$tPDT][5],
                        'FTPbnCode'         => (isset($aPackData[1][$tPDT][6]) == '') ? '' : $aPackData[1][$tPDT][6],
                        'FTPdtStaVat'       => (isset($aPackData[1][$tPDT][7]) == '') ? '' : $aPackData[1][$tPDT][7],
                        'FTPtyCode'         => (isset($aPackData[1][$tPDT][8]) == '') ? '' : $aPackData[1][$tPDT][8],
                        'FTPmoCode'         => (isset($aPackData[1][$tPDT][9]) == '') ? '' : $aPackData[1][$tPDT][9],
                        'FTPgpChain'        => (isset($aPackData[1][$tPDT][10]) == '') ? '' : $aPackData[1][$tPDT][10],
                        'FTTcgCode'         => (isset($aPackData[1][$tPDT][11]) == '') ? '' : $aPackData[1][$tPDT][11],
                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                        'FTTmpStatus'       => (isset($aPackData[1][$tPDT][12]) == '') ? '' : $aPackData[1][$tPDT][12],
                        'FTTmpRemark'       => (isset($aPackData[1][$tPDT][13]) == '') ? '' : $aPackData[1][$tPDT][13],
                        'FDCreateOn'        => date('Y-m-d')
                    );
                    array_push($aSumSheetPDT, $aPDT);

                    if ($this->session->userdata("bIsHaveAgn")) {
                        $aPdtSpcBch = array(
                            'FTTmpTableKey'     => 'TCNMPdtSpcBch',
                            'FTPdtCode'         => (isset($aPackData[1][$tPDT][0]) == '') ? '' : trim($aPackData[1][$tPDT][0]),
                            'FTTmpStatus'       => (isset($aPackData[1][$tPDT][12]) == '') ? '' : $aPackData[1][$tPDT][12],
                            'FTAgnCode'         => $this->session->userdata("tSesUsrAgnCode"),
                            'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                            'FDCreateOn'        => date('Y-m-d')
                        );
                        array_push($aPackPdtSpcBch, $aPdtSpcBch);
                    }
                }



                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aSumSheetPDT);

                //เพิ่มข้อมูล SpcBCH
                if (FCNnHSizeOf($aPackPdtSpcBch) > 0) {
                    $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData, $aPackPdtSpcBch);
                }

                //validate ข้อมูลซ้ำในตาราง Tmp - บาร์โค๊ดห้ามซ้ำกัน ใน PDT เดียวกัน 
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'aFieldName'        => ['FTPdtCode','FTBarCode']
                // );
                // FCNnDocTmpChkCodeMultiDupInTemp($aValidateData,$aWhereData);

                //validate มีข้อมูลอยู่เเล้วในตารางห้ามซ้ำกับ AD อื่น
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableCheck'       => 'TCNMPdt'
                );
                FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                //validate รหัสสินค้าซ้ำใน AD ตัวเอง
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableCheck'       => 'TCNMPdt_AD'
                );
                FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                // Check รหัสหน่วยย่อยซ้ำ Temp ก่อนเเล้วค่อยเช็คจาก master (เพิ่มมาใหม่)
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTPdtCode',
                //     'tTableCheck'       => 'TCNMPdtPackSize'
                // );
                // FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                // Check บาร์โค๊ดก่อนว่าซ้ำไหม Temp ก่อนเเล้วค่อยเช็คจาก master (เพิ่มมาใหม่)
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTPdtCode',
                //     'tTableCheck'       => 'TCNMPdtBar'
                // );
                // FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                //Check หน่วยสินค้าจาก Temp ก่อนเเล้วค่อยเช็คจาก master (เช็คว่าหน่วยนิมีจริงไหม)
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode',
                    'tTableCheck'       => 'TCNMPdtUnit'
                );
                FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                // Check รหัสกลุ่มสินค้าด่วน Temp ก่อนเเล้วค่อยเช็คจาก master
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTTcgCode',
                //     'tTableCheck'       => 'TCNMPdtTouchGrp'
                // );
                // FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);

                //Check รหัสยี่ห้อ Temp ก่อนเเล้วค่อยเช็คจาก master
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTPbnCode',
                //     'tTableCheck'       => 'TCNMPdtBrand'
                // );
                // FCNnMasTmpChkCodeDupInDBSpecial($aValidateData);
            }
        }else{
            $aInsPackdata = array();
            if($nPackData > 1){

                $tDefAgnCode = $this->session->userdata("tSesUsrAgnCode");
                $tStaUsrAgn  = $this->session->userdata("tSesUsrLoginAgency");

                for($i=1; $i<$nPackData; $i++){
                    switch ($tNameModule) {
                        case "branch":

                            // Create By : 17/11/2020 Napat(jame)
                            // ถ้า Login ภายใต้ AD ให้นำ Session AD มา insert auto เพื่อป้องกันการ insert ให้ AD อื่น
                            if( $tStaUsrAgn == '1' ){
                                $tAgnCode = $tDefAgnCode;
                            }else{
                                $tAgnCode = $aPackData[$i][2];
                            }

                            $aObject = array(
                                'FTTmpTableKey'     => $tTableRefPK,
                                'FNTmpSeq'          => $i,
                                'FTBchCode'         => $aPackData[$i][0],
                                'FTBchName'         => (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1],
                                'FTAgnCode'         => $tAgnCode,
                                'FTPplCode'         => (isset($aPackData[$i][3]) == '') ? '' : $aPackData[$i][3],
                                'FTTmpStatus'       => (isset($aPackData[$i][4]) == '') ? '' : $aPackData[$i][4],
                                'FTTmpRemark'       => (isset($aPackData[$i][5]) == '') ? '' : $aPackData[$i][5],
                                'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                'FDCreateOn'        => date('Y-m-d')
                            );
                            // print_r($aObject);
                            // die();
                        break;
                        case "adjprice":
                            $aObject = array(
                                'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                'FTXthDocKey'       => $tTableRefPK,
                                'FNXtdSeqNo'        => $i,
                                'FTPdtCode'         => $aPackData[$i][0],
                                'FTPunCode'         => (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1],
                                'FCXtdPriceRet'     => (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2],
                                'FTTmpStatus'       => (isset($aPackData[$i][3]) == '') ? '' : $aPackData[$i][3],
                                'FTTmpRemark'       => (isset($aPackData[$i][4]) == '') ? '' : $aPackData[$i][4],
                                'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                'FDCreateOn'        => date('Y-m-d')
                            );
                        break;
                        case "user":
                            
                            // Create By : 17/11/2020 Napat(jame)
                            // ถ้า Login ภายใต้ AD ให้นำ Session AD มา insert auto เพื่อป้องกันการ insert ให้ AD อื่น

                            if( $tStaUsrAgn == '1' ){
                                $tAgnCode = $tDefAgnCode;
                            }else{
                                $tAgnCode = $aPackData[$i][4];
                            }

                            $aObject = array(
                                'FTTmpTableKey'     => $tTableRefPK,
                                'FNTmpSeq'          => $i,
                                'FTUsrCode'         => $aPackData[$i][0],
                                'FTUsrName'         => (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1],
                                'FTBchCode'         => (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2],
                                'FTRolCode'         => (isset($aPackData[$i][3]) == '') ? '' : $aPackData[$i][3],
                                'FTAgnCode'         => $tAgnCode,
                                'FTMerCode'         => (isset($aPackData[$i][5]) == '') ? '' : $aPackData[$i][5],
                                'FTShpCode'         => (isset($aPackData[$i][6]) == '') ? '' : $aPackData[$i][6],
                                'FTDptCode'         => (isset($aPackData[$i][7]) == '') ? '' : $aPackData[$i][7],
                                'FTUsrTel'          => (isset($aPackData[$i][8]) == '') ? '' : $aPackData[$i][8],
                                'FTUsrEmail'        => (isset($aPackData[$i][9]) == '') ? '' : $aPackData[$i][9],
                                'FTTmpStatus'       => (isset($aPackData[$i][10]) == '') ? '' : $aPackData[$i][10],
                                'FTTmpRemark'       => (isset($aPackData[$i][11]) == '') ? '' : $aPackData[$i][11],
                                'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                'FDCreateOn'        => date('Y-m-d')
                            );
                        break;
                        case "pos":
                            $aObject = array(
                                'FTTmpTableKey'     => $tTableRefPK,
                                'FNTmpSeq'          => $i,
                                'FTBchCode'         => $aPackData[$i][0],
                                'FTPosCode'         => $aPackData[$i][1],
                                'FTPosName'         => (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2],
                                'FTPosType'         => (isset($aPackData[$i][3]) == '') ? '' : $aPackData[$i][3],
                                'FTPosRegNo'        => (isset($aPackData[$i][4]) == '') ? '' : $aPackData[$i][4],
                                'FTTmpStatus'       => (isset($aPackData[$i][5]) == '') ? '' : $aPackData[$i][5],
                                'FTTmpRemark'       => (isset($aPackData[$i][6]) == '') ? '' : $aPackData[$i][6],
                                'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                'FDCreateOn'        => date('Y-m-d')
                            );
                        break;
                        case "purchaseorder":

                                $tPdtCode = (isset($aPackData[$i][0]) == '') ? '' : $aPackData[$i][0];
                                $tPunCode = (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1];
                                $tBarCode = (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2];
                                $nSeqNo   = $i;
                                $cQty     = (isset($aPackData[$i][3]) == '') ? 0 : $aPackData[$i][3];
                                $cPrice   = (isset($aPackData[$i][4]) == '') ? 0 : $aPackData[$i][4];
                                $tErrType   = (isset($aPackData[$i][5]) == '') ? 0 : $aPackData[$i][5];
                                $tErrDes   = (isset($aPackData[$i][6]) == '') ? 0 : $aPackData[$i][6];
                                if($tErrType=='1'){
                                    $nSrnCode = '1';
                                }else{
                                    $nSrnCode = '0';
                                }
                                $aDataPdtParams = array(
                                    'tDocNo'            => '',
                                    'tBchCode'          => $this->session->userdata("tSesUsrBchCodeDefault"),
                                    'tPdtCode'          => $tPdtCode,
                                    'tBarCode'          => $tBarCode,
                                    'tPunCode'          => $tPunCode,
                                    'nLngID'            => FCNaHGetLangEdit()
                                );
                                $aDataPdtMaster = $this->mPurchaseOrder->FSaMPOGetDataPdt($aDataPdtParams);
                                if($aDataPdtMaster['rtCode']=='1'){
                                    $aObject    = array(
                                        'FTBchCode'         => $tImportFrmBchCode,
                                        'FTXthDocNo'        => $tImportDocumentNo,
                                        'FNXtdSeqNo'        => $nSeqNo,
                                        'FTXthDocKey'       => $tTableRefPK,
                                        'FTPdtCode'         => $aDataPdtMaster['raItem']['FTPdtCode'],
                                        'FTXtdPdtName'      => $aDataPdtMaster['raItem']['FTPdtName'],
                                        'FCXtdFactor'       => $aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                        'FTPunCode'         => $aDataPdtMaster['raItem']['FTPunCode'],
                                        'FTPunName'         => $aDataPdtMaster['raItem']['FTPunName'],
                                        'FTXtdBarCode'      => $tBarCode,
                                        'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVatBuy'],
                                        // 'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVat'],
                                        'FTVatCode'         => $tImportSplVatCode,
                                        'FCXtdVatRate'      => $tImportSplVatRate,
                                        'FTXtdStaAlwDis'    => $aDataPdtMaster['raItem']['FTPdtStaAlwDis'],
                                        'FTXtdSaleType'     => $aDataPdtMaster['raItem']['FTPdtSaleType'],
                                        'FCXtdSalePrice'    => $cPrice,
                                        'FCXtdQty'          => $cQty,
                                        'FCXtdQtyAll'       => $cQty*$aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                        'FCXtdSetPrice'     => $cPrice * 1,
                                        'FCXtdNet'          => $cPrice * $cQty,
                                        'FCXtdNetAfHD'      => $cPrice * $cQty,
                                        'FTSrnCode'         => $nSrnCode,
                                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                        'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                                        'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                        'FTTmpRemark'       => $tErrDes,
                                    );
                                }else{
                                    $aObject    = array(
                                        'FTBchCode'         => $tImportFrmBchCode,
                                        'FTXthDocNo'        => $tImportDocumentNo,
                                        'FNXtdSeqNo'        => $nSeqNo,
                                        'FTXthDocKey'       => $tTableRefPK,
                                        'FTPdtCode'         => $tPdtCode,
                                        'FTXtdPdtName'      => NULL,
                                        'FCXtdFactor'       => NULL,
                                        'FTPunCode'         => $tPunCode,
                                        'FTPunName'         => NULL,
                                        'FTXtdBarCode'      => $tBarCode,
                                        'FTXtdVatType'      => '1',
                                        // 'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVat'],
                                        'FTVatCode'         => $tImportSplVatCode,
                                        'FCXtdVatRate'      => $tImportSplVatRate,
                                        'FTXtdStaAlwDis'    => 0,
                                        'FTXtdSaleType'     => '1',
                                        'FCXtdSalePrice'    => $cPrice,
                                        'FCXtdQty'          => $cQty,
                                        'FCXtdQtyAll'       => $cQty*1,
                                        'FCXtdSetPrice'     => $cPrice * 1,
                                        'FCXtdNet'          => $cPrice * $cQty,
                                        'FCXtdNetAfHD'      => $cPrice * $cQty,
                                        'FTSrnCode'         => '0',
                                        'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                        'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                        'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                        'FDCreateOn'        => date('Y-m-d h:i:s'),
                                        'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                        'FTTmpRemark'       => language('document/purchaseorder/purchaseorder','tPONotFoundPdtCodeAndBarcodeImp'),
                                    );
                                }
                        break;
                        case "purchaseinvoice":

                            $tPdtCode = (isset($aPackData[$i][0]) == '') ? '' : $aPackData[$i][0];
                            $tPunCode = (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1];
                            $tBarCode = (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2];
                            $nSeqNo   = $i;
                            $cQty     = (isset($aPackData[$i][3]) == '') ? 0 : $aPackData[$i][3];
                            $cPrice   = (isset($aPackData[$i][4]) == '') ? 0 : $aPackData[$i][4];

                            $aDataPdtParams = array(
                                'tDocNo'            => '',
                                'tBchCode'          => $this->session->userdata("tSesUsrBchCodeDefault"),
                                'tPdtCode'          => $tPdtCode,
                                'tBarCode'          => $tBarCode,
                                'tPunCode'          => $tPunCode,
                                'nLngID'            => FCNaHGetLangEdit()
                            );
                            $aDataPdtMaster  = $this->mPurchaseInvoice->FSaMPIGetDataPdt($aDataPdtParams);
                            if($aDataPdtMaster['rtCode']=='1'){

                                $aObject    = array(
                                    'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                    'FTXthDocNo'        => '',
                                    'FNXtdSeqNo'        => $nSeqNo,
                                    'FTXthDocKey'       => $tTableRefPK,
                                    'FTPdtCode'         => $aDataPdtMaster['raItem']['FTPdtCode'],
                                    'FTXtdPdtName'      => $aDataPdtMaster['raItem']['FTPdtName'],
                                    'FCXtdFactor'       => $aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                    'FTPunCode'         => $aDataPdtMaster['raItem']['FTPunCode'],
                                    'FTPunName'         => $aDataPdtMaster['raItem']['FTPunName'],
                                    'FTXtdBarCode'      => $tBarCode,
                                    'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVatBuy'],
                                    // 'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVat'],
                                    'FTVatCode'         => $aDataPdtMaster['raItem']['FTVatCode'],
                                    'FCXtdVatRate'      => $aDataPdtMaster['raItem']['FCVatRate'],
                                    'FTXtdStaAlwDis'    => $aDataPdtMaster['raItem']['FTPdtStaAlwDis'],
                                    'FTXtdSaleType'     => $aDataPdtMaster['raItem']['FTPdtSaleType'],
                                    'FCXtdSalePrice'    => $cPrice,
                                    'FCXtdQty'          => $cQty,
                                    'FCXtdQtyAll'       => $cQty*$aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                    'FCXtdSetPrice'     => $cPrice * 1,
                                    'FCXtdNet'          => $cPrice * $cQty,
                                    'FCXtdNetAfHD'      => $cPrice * $cQty,
                                    'FTSrnCode'         => 1,
                                    'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                );
                            }else{
                                $aObject    = array(
                                    'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                    'FTXthDocNo'        => '',
                                    'FNXtdSeqNo'        => $nSeqNo,
                                    'FTXthDocKey'       => $tTableRefPK,
                                    'FTPdtCode'         => $tPdtCode,
                                    'FTXtdPdtName'      => NULL,
                                    'FCXtdFactor'       => NULL,
                                    'FTPunCode'         => $tPunCode,
                                    'FTPunName'         => NULL,
                                    'FTXtdBarCode'      => $tBarCode,
                                    'FTXtdVatType'      => '1',
                                    'FTVatCode'         => NULL,
                                    'FCXtdVatRate'      => '7.0000',
                                    'FTXtdStaAlwDis'    => 0,
                                    'FTXtdSaleType'     => '1',
                                    'FCXtdSalePrice'    => $cPrice,
                                    'FCXtdQty'          => $cQty,
                                    'FCXtdQtyAll'       => $cQty*1,
                                    'FCXtdSetPrice'     => $cPrice * 1,
                                    'FCXtdNet'          => $cPrice * $cQty,
                                    'FCXtdNetAfHD'      => $cPrice * $cQty,
                                    'FTSrnCode'         => 0,
                                    'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                );
                            }
                        break;
                        case "adjcost":
                            $aObject = array(
                                'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                'FTXthDocKey'       => $tTableRefPK,
                                'FNXtdSeqNo'        => $i,
                                'FTPdtCode'         => trim($aPackData[$i][0]),
                                'FCXtdCostEx'       => (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1],
                                'FTTmpStatus'       => (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2],
                                'FTTmpRemark'       => (isset($aPackData[$i][3]) == '') ? '' : $aPackData[$i][3],
                                'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                'FDCreateOn'        => date('Y-m-d')
                            );
                        break;
                        case "transferreceipt":
                            $tPdtCode   = (isset($aPackData[$i][0]) == '') ? '' : $aPackData[$i][0];
                            $tPunCode   = (isset($aPackData[$i][1]) == '') ? '' : $aPackData[$i][1];
                            $tBarCode   = (isset($aPackData[$i][2]) == '') ? '' : $aPackData[$i][2];
                            $nSeqNo     = $i;
                            $cQty       = (isset($aPackData[$i][4]) == '') ? 0 : $aPackData[$i][4];
                            $tLotBatch  = (isset($aPackData[$i][3]) == '') ? 0 : $aPackData[$i][3];
                            $tStatus    = (isset($aPackData[$i][5]) == '') ? '' : $aPackData[$i][5];
                            $tRemark    = (isset($aPackData[$i][6]) == '') ? '' : $aPackData[$i][6];

                            if($tPunCode == '' && $tBarCode == ''){
                                $aDataPdtGetPun  = $this->mTransferreceiptNew->FSaMTWIGetDataPunBarPdt($tPdtCode);
                                $tPunCode = $aDataPdtGetPun['raItem']['FTPunCode'];
                                $tBarCode = $aDataPdtGetPun['raItem']['FTBarCode'];
                            }elseif($tPunCode == '' && $tBarCode != ''){
                                $aDataPdtGetBAR  = $this->mTransferreceiptNew->FSaMTWIGetDataBarPdt($tPdtCode,$tBarCode);
                                if($aDataPdtGetBAR['rtCode'] != '1'){
                                    $tStatus = '2';
                                    $tRemark = 'ข้อมูลสินค้าไม่ถูกต้อง';
                                }else{
                                    $tPunCode = $aDataPdtGetBAR['raItem']['FTPunCode'];
                                }
                            }elseif($tPunCode != '' && $tBarCode == ''){
                                $aDataPdtGetBAR  = $this->mTransferreceiptNew->FSaMTWIGetDataBarPdt($tPdtCode,$tBarCode);
                                if($aDataPdtGetBAR['rtCode'] != '1'){
                                    $tStatus = '2';
                                    $tRemark = 'ข้อมูลสินค้าไม่ถูกต้อง';
                                }else{
                                    $tPunCode = $aDataPdtGetBAR['raItem']['FTPunCode'];
                                }
                            }


                            $aDataPdtParams = array(
                                'tDocNo'            => '',
                                'tBchCode'          => $this->session->userdata("tSesUsrBchCodeDefault"),
                                'tPdtCode'          => $tPdtCode,
                                'tBarCode'          => $tBarCode,
                                'tPunCode'          => $tPunCode,
                                'nLngID'            => FCNaHGetLangEdit()
                            );

                            $aDataPdtMaster  = $this->mTransferreceiptNew->FSaMTWIGetDataPdt($aDataPdtParams);

                            if($aDataPdtMaster['rtCode']=='1'){
                                $aObject    = array(
                                    'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                    'FTXthDocNo'        => $tImportDocumentNo,
                                    'FNXtdSeqNo'        => $nSeqNo,
                                    'FTXthDocKey'       => $tTableRefPK,
                                    'FTPdtCode'         => $aDataPdtMaster['raItem']['FTPdtCode'],
                                    'FTXtdPdtName'      => $aDataPdtMaster['raItem']['FTPdtName'],
                                    'FCXtdFactor'       => $aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                    'FTPunCode'         => $aDataPdtMaster['raItem']['FTPunCode'],
                                    'FTPunName'         => $aDataPdtMaster['raItem']['FTPunName'],
                                    'FTXtdBarCode'      => $tBarCode,
                                    'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVatBuy'],
                                    // 'FTXtdVatType'      => $aDataPdtMaster['raItem']['FTPdtStaVat'],
                                    'FTVatCode'         => $aDataPdtMaster['raItem']['FTVatCode'],
                                    'FCXtdVatRate'      => $aDataPdtMaster['raItem']['FCVatRate'],
                                    'FTXtdStaAlwDis'    => $aDataPdtMaster['raItem']['FTPdtStaAlwDis'],
                                    'FTXtdSaleType'     => $aDataPdtMaster['raItem']['FTPdtSaleType'],
                                    'FCXtdSalePrice'    => $aDataPdtMaster['raItem']['FTPdtSalePrice'],
                                    'FCXtdQty'          => $cQty,
                                    'FCXtdQtyAll'       => $cQty*$aDataPdtMaster['raItem']['FCPdtUnitFact'],
                                    'FCXtdSetPrice'     => $aDataPdtMaster['raItem']['FTPdtSalePrice'] * 1,
                                    'FCXtdNet'          => $aDataPdtMaster['raItem']['FTPdtSalePrice'] * $cQty,
                                    'FCXtdNetAfHD'      => $aDataPdtMaster['raItem']['FTPdtSalePrice'] * $cQty,
                                    'FTSrnCode'         => 1,
                                    'FTPdtSetOrSN'      => $aDataPdtMaster['raItem']['FTPdtStaLot'],
                                    'FTPgpChain'        => $tLotBatch,
                                    'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                    'FTTmpStatus'       => $tStatus,
                                    'FTTmpRemark'       => $tRemark,
                                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                );
                            }else{
                                $aObject    = array(
                                    'FTBchCode'         => $this->session->userdata("tSesUsrBchCodeDefault"),
                                    'FTXthDocNo'        => $tImportDocumentNo,
                                    'FNXtdSeqNo'        => $nSeqNo,
                                    'FTXthDocKey'       => $tTableRefPK,
                                    'FTPdtCode'         => $tPdtCode,
                                    'FTXtdPdtName'      => NULL,
                                    'FCXtdFactor'       => NULL,
                                    'FTPunCode'         => $tPunCode,
                                    'FTPunName'         => NULL,
                                    'FTXtdBarCode'      => $tBarCode,
                                    'FTXtdVatType'      => '1',
                                    'FTVatCode'         => NULL,
                                    'FCXtdVatRate'      => '7.0000',
                                    'FTXtdStaAlwDis'    => 0,
                                    'FTXtdSaleType'     => '1',
                                    'FCXtdSalePrice'    => NULL,
                                    'FCXtdQty'          => $cQty,
                                    'FCXtdQtyAll'       => $cQty*1,
                                    'FCXtdSetPrice'     => 1 * 1,
                                    'FCXtdNet'          => 1 * $cQty,
                                    'FCXtdNetAfHD'      => 1 * $cQty,
                                    'FTSrnCode'         => 0,
                                    'FTPdtSetOrSN'      => '0',
                                    'FTPgpChain'        => $tLotBatch,
                                    'FTTmpStatus'       => '2',
                                    'FTTmpRemark'       => 'ข้อมูลสินค้าไม่ถูกต้อง',
                                    'FTSessionID'       => $this->session->userdata("tSesSessionID"),
                                    'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                                    'FDCreateOn'        => date('Y-m-d h:i:s'),
                                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                                );
                            }
                        break;
                    }
                    array_push($aInsPackdata,$aObject);
                }

                //Insert ลง Tmp แล้ว
                $this->mCommon->FCNaMCMMImportExcelToTmp($aWhereData,$aInsPackdata);
            }
        }

        //Validate พวกอ่างอิงไม่เจอ + ตรวจสอบข้อมูลว่ามีจริงไหม
        switch ($tNameModule) {
            case "branch":
                //validate ข้อมูลซ้ำในตาราง Tmp
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTBchCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTBchCode',
                    'tTableName'        => 'TCNMBranch'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPplCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPplCode',
                    'tTableName'        => 'TCNMPdtPriList',
                    'tErrMsg'           => 'ไม่พบกลุ่มราคาในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);
        
                //validate ข้อมูลอ้างอิงมีจริงไหม
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTAgnCode',
                    'tTableName'        => 'TCNMAgency',
                    'tErrMsg'           => 'ไม่พบตัวแทนขายในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);
            break;
            case "adjprice":
                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPdtCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableName'        => 'TCNMPDT',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPunCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode',
                    'tTableName'        => 'TCNMPdtPackSize',
                    'tErrMsg'           => 'ไม่พบหน่วยสินค้าในระบบ'
                );
                // FCNnDocTmpChkCodeInDB($aValidateData);
                FCNnDocTmpChkCodeInDBPack($aValidateData);

                //validate เช็คซ้ำกันใน temp
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'aFieldName'        => ['FTPdtCode','FTPunCode']
                );
                FCNnDocTmpChkCodeMultiDupInTemp($aValidateData,$aWhereData);
            break;
            case "user":
                
                //validate ข้อมูลซ้ำในตาราง Tmp _ รหัสผู้ใช้
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTUsrCode'
                );
                FCNnMasTmpChkCodeDupInTemp($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ สาขา
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTBchCode',
                    'tTableName'        => 'TCNMBranch',
                    'tErrMsg'           => 'ไม่พบรหัสสาขาในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                // Create By : 17/11/2020 Napat(jame) เพื่มการตรวจสอบ สาขาที่ผูก Agency Code
                // validate ข้อมูลอ้างอิงมีจริงไหม _ สาขา + ตัวแทนขาย
                $aValidateData = array(
                    'tImportFrom'       => 'user',
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tTableName'        => 'TCNMBranch',
                    'aFieldName'        => ['FTBchCode','FTAgnCode'],
                    'tErrMsg'           => 'ไม่พบสาขาที่อยู่ภายใต้ตัวแทนขายในระบบ'
                );
                FCNnMasTmpChkCodeMultiInDB($aValidateData,$aWhereData);                

                //validate ข้อมูลอ้างอิงมีจริงไหม _ กลุ่มสิทธิ
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTRolCode',
                    'tTableName'        => 'TCNMUsrRole',
                    'tErrMsg'           => 'ไม่พบกลุ่มสิทธิ์ในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                //validate มีสิทหรือไม่ _ กลุ่มสิทธิ
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tUsrRoleSpc'       => $this->session->userdata('tSesUsrRoleSpcCodeMulti'),
                    'tFieldName'        => 'FTRolCode',
                    'tErrMsg'           => 'ไม่มีสิทธิ์นำเข้าผู้ใช้ในกลุ่มสิทธิ์นี้'
                );
                FCNnMasTmpChkRoleInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ รหัสตัวแทนขาย	
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTAgnCode',
                    'tTableName'        => 'TCNMAgency',
                    'tErrMsg'           => 'ไม่พบตัวแทนขายในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ กลุ่มธุรกิจ	
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTMerCode',
                    'tTableName'        => 'TCNMMerchant',
                    'tErrMsg'           => 'ไม่พบกลุ่มธุรกิจในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ ร้านค้า	
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTShpCode',
                    'tTableName'        => 'TCNMShop_L',
                    'tErrMsg'           => 'ไม่พบร้านค้าในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ แผนก	
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTDptCode',
                    'tTableName'        => 'TCNMUsrDepart_L',
                    'tErrMsg'           => 'ไม่พบแผนกในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                //validate มีข้อมูลอยู่เเล้วในตารางจริง _ รหัสผู้ใช้
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTUsrCode',
                    'tTableName'        => 'TCNMUser'
                );
                FCNnMasTmpChkCodeDupInDB($aValidateData);

            break;
            case "pos":

                // ตรวจสอบสาขาว่ามีอยู่จริงหรือไม่
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTBchCode',
                    'tTableName'        => 'TCNMBranch',
                    'tErrMsg'           => 'ไม่พบสาขาในระบบ'
                );
                FCNnMasTmpChkCodeInDB($aValidateData);

                 // ตรวจสอบข้อมูลซ้ำในตาราง Temp
                 $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'aFieldName'        => ['FTBchCode','FTPosCode']
                );
                FCNnMasTmpChkCodeMultiDupInTemp($aValidateData);

                // ตรวจสอบข้อมูลซ้ำในตาราง Master
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tTableName'        => 'TCNMPos',
                    'aFieldName'        => ['FTBchCode','FTPosCode']
                );
                FCNnMasTmpChkCodeMultiDupInDB($aValidateData);
            break;
            case "purchaseorder":
                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPdtCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableName'        => 'TCNMPDT',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPunCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode',
                    'tTableName'        => 'TCNMPdtUnit',
                    'tErrMsg'           => 'ไม่พบหน่วยสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTBarCode
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTXtdBarCode',
                //     'tTableName'        => 'TCNMPdtBar',
                //     'tErrMsg'           => 'ไม่พบบาร์โคดในระบบ'
                // );
                // FCNnDocTmpChkCodeInDB($aValidateData);

                //validate เช็คซ้ำกันใน temp
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'aFieldName'        => ['FTPdtCode','FTPunCode']
                // );
                // FCNnDocTmpChkCodeMultiDupInTemp($aValidateData,$aWhereData);
            break;
            case "purchaseinvoice":
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableName'        => 'TCNMPDT',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPunCode',
                    'tTableName'        => 'TCNMPdtUnit',
                    'tErrMsg'           => 'ไม่พบหน่วยสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);
            break;
            case "adjcost":
                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPdtCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableName'        => 'TCNMPDT',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                //validate เช็คซ้ำกันใน temp
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tAGNCode'          =>  $this->session->userdata('tSesUsrAgnCode'),
                    'tTableName'        => 'TCNTPdtAdjCostHD',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                //ถ้าไม่ใช่ HQ ต้อง validate ว่าสินค้านั้น อยู่ใน AD ของคุณหรือเปล่า
                if($this->session->userdata('tSesUsrLevel') != 'HQ'){
                    FCNnDocTmpChkPDTCodeINADSelf($aValidateData,$aWhereData);
                }

                //validate เช็คซ้ำกันใน temp
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'aFieldName'        => ['FTBchCode','FTPdtCode']
                );
                FCNnDocTmpChkCodeMultiDupInTemp($aValidateData,$aWhereData);
            break;
            case "transferreceipt":
                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPdtCode
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'tFieldName'        => 'FTPdtCode',
                    'tTableName'        => 'TCNMPDT',
                    'tErrMsg'           => 'ไม่พบสินค้าในระบบ'
                );
                FCNnDocTmpChkCodeInDB($aValidateData);

                //validate ข้อมูลอ้างอิงมีจริงไหม _ FTPunCode
                // $aValidateData = array(
                //     'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                //     'tFieldName'        => 'FTPunCode',
                //     'tTableName'        => 'TCNMPdtUnit',
                //     'tErrMsg'           => 'ไม่พบหน่วยสินค้าในระบบ'
                // );
                // FCNnDocTmpChkCodeInDB($aValidateData);
                // FCNnDocTmpChkCodeInDBPack($aValidateData);

                //validate เช็คซ้ำกันใน temp
                $aValidateData = array(
                    'tUserSessionID'    => $this->session->userdata("tSesSessionID"),
                    'aFieldName'        => ['FTPdtCode','FTPunCode']
                );
                FCNnDocTmpChkCodeMultiDupInTemp($aValidateData,$aWhereData);
            break;
        }

    }

    public function FCNxEventSwiftRoute(){
        $this->load->view ('common/wHeader', array('title' => "Home"));

        $aParameter = array(
            'aParams' => str_replace(" ","+",$this->input->get('oParams'))
        );
        $this->load->view('common/wSwitchRoute', $aParameter);
    }
}

