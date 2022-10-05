<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cConnectionSetting extends MX_Controller {

    
    public function __construct(){
        parent::__construct ();
        $this->load->model('interface/connectionsetting/mConnectionSetting');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nBrowseType,$tBrowseOption){
        $aDataSetting = array(
            'nBrowseType'                   => $nBrowseType,
            'tBrowseOption'                 => $tBrowseOption,
            'aAlwEventConnectionSetting'    => FCNaHCheckAlwFunc('ConnectionSetting/0/0'), //Controle Event
            'vBtnSave'                      => FCNaHBtnSaveActiveHTML('ConnectionSetting/0/0'), //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        );
        $this->load->view('interface/connectionsetting/wConnectionsetting',$aDataSetting);

    }

    // Call page list
    // Create WItsarut 28052020
     public function FSvCCCSDataList(){

        $tSearchAllNotSet  = $this->input->post('tSearchAllNotSet');
        $tSearchAllSetUp   = $this->input->post('tSearchAllSetUp');

        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 


        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPageCurrent'      => $this->input->post('nPageCurrent'),
            'tSearchAllNotSet'  => $tSearchAllNotSet,
            'tSearchAllSetUp'   => $tSearchAllSetUp,
            'tStaUsrLevel'      => $tStaUsrLevel,
            'tUsrBchCode'       => $tUsrBchCode,
        );

        $aWaHouseListup      = $this->mConnectionSetting->FSaMCCSListDataUP($aData);
        $aWaHouseListdown    = $this->mConnectionSetting->FSaMCCSListDataDown($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aWaHouseListup'     => $aWaHouseListup,
            'aWaHouseListdown'   => $aWaHouseListdown,
            'aAlwEvent'          => $aAlwEventConnectionSetting,
            'tSearchAllNotSet'   => $tSearchAllNotSet,
            'tSearchAllSetUp'    => $tSearchAllSetUp
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingWahouse',$aDataResult);

     }  

    // Call page list
    // Create WItsarut 28052020
    public function FSvCCCSDataListUserShop(){

        $tSearchAllCstShp  = $this->input->post('tSearchAllCstShp');

        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 


        $aData = array(
            'FNLngID'             => $this->session->userdata("tLangEdit"),
            'nPageCurrent'        => $this->input->post('nPageCurrent'),
            'tSearchAllUserShop'  => $tSearchAllCstShp,
            'tStaUsrLevel'        => $tStaUsrLevel,
            'tUsrBchCode'         => $tUsrBchCode,
        );

        $aUsrShopData               = $this->mConnectionSetting->FSaMCCSListDataUsrShop($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aUsrShopData'       => $aUsrShopData,
            'aAlwEvent'          => $aAlwEventConnectionSetting,
            'tSearchAllUserShop'   => $tSearchAllCstShp
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingUserShop',$aDataResult);

     }  

    // Call page list CarInter
    // Create WItsarut 28052020
    public function FSvCCCSDataListCarInter(){
        $nPage  = $this->input->post('nPageCurrent');

        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}

        $tSearchAllCarInter  = $this->input->post('tSearchAllCarInter');
        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 

        $aData = array(
            'nPage'                 => $nPage,
            'nRow'                  => 10,
            'FNLngID'               => $this->session->userdata("tLangEdit"),
            'nPageCurrent'          => $this->input->post('nPageCurrent'),
            'tSearchAllCarInter'    => $tSearchAllCarInter,
            'tStaUsrLevel'          => $tStaUsrLevel,
            'tUsrBchCode'           => $tUsrBchCode,
        );

        $aCarInterData               = $this->mConnectionSetting->FSaMCCSListDataCarInter($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aCarInterData'         => $aCarInterData,
            'aAlwEvent'             => $aAlwEventConnectionSetting,
            'nPage'                 => $nPage,
            'tSearchAllCarInter'    => $tSearchAllCarInter
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingCarInter',$aDataResult);

     }  

    // Call page list Mapping
    // Create WItsarut 28052020
    public function FSvCCCSDataListMapping(){

        $tSearchAllMapping   = $this->input->post('tSearchMapping');

        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 

        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPageCurrent'      => $this->input->post('nPageCurrent'),
            'tSearchAllMapping'   => $tSearchAllMapping,
            'tStaUsrLevel'      => $tStaUsrLevel,
            'tUsrBchCode'       => $tUsrBchCode,
        );

        $aMappingData           = $this->mConnectionSetting->FSaMCCSListDataMapping($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aMappingData'          => $aMappingData,
            'aAlwEvent'             => $aAlwEventConnectionSetting,
            'tSearchAllMapping'     => $tSearchAllMapping
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingMapping',$aDataResult);

     } 
     
    // Call page list MsShop
    // Create WItsarut 28052020
    public function FSvCCCSDataListMSShop(){

        $tSearchAllMSShop   = $this->input->post('tSearchMSShop');

        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 

        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPageCurrent'      => $this->input->post('nPageCurrent'),
            'tSearchAllMSShop'   => $tSearchAllMSShop,
            'tStaUsrLevel'      => $tStaUsrLevel,
            'tUsrBchCode'       => $tUsrBchCode,
        );

        $aMSShopData           = $this->mConnectionSetting->FSaMCCSListDataMSShop($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aMSShopData'          => $aMSShopData,
            'aAlwEvent'             => $aAlwEventConnectionSetting,
            'tSearchAllMSShop'     => $tSearchAllMSShop
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingMSShop',$aDataResult);

     }

         // Call page list MsShop
    // Create WItsarut 28052020
    public function FSvCCCSDataListErrMsg(){

        $tSearchAllRespond   = $this->input->post('tSearchAllRespond');

        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 

        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPageCurrent'      => $this->input->post('nPageCurrent'),
            'tSearchAllRespond'   => $tSearchAllRespond,
            'tStaUsrLevel'      => $tStaUsrLevel,
            'tUsrBchCode'       => $tUsrBchCode,
        );

        $aErrMsgData           = $this->mConnectionSetting->FSaMCCSListDataErrMsg($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');

        $aDataResult  = [
            'aErrMsgData'          => $aErrMsgData,
            'aAlwEvent'             => $aAlwEventConnectionSetting,
            'tSearchAllRespond'     => $tSearchAllRespond
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingRespond',$aDataResult);

     }

    // Call page list Mapping
    // Create WItsarut 28052020
    public function FSvCCCSDataListUMS(){
        $tSearchAllNotSet  = $this->input->post('tSearchAllNotSet');
        $tSearchAllSetUp   = $this->input->post('tSearchAllSetUp');
        $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCodeMulti"); 
        $aData = array(
            'FNLngID'           => $this->session->userdata("tLangEdit"),
            'nPageCurrent'      => $this->input->post('nPageCurrent'),
            'tSearchAllNotSet'  => $tSearchAllNotSet,
            'tSearchAllSetUp'   => $tSearchAllSetUp,
            'tStaUsrLevel'      => $tStaUsrLevel,
            'tUsrBchCode'       => $tUsrBchCode,
        );
        $aWaHouseListup      = $this->mConnectionSetting->FSaMCCSListDataUP($aData);
        $aWaHouseListdown    = $this->mConnectionSetting->FSaMCCSListDataDown($aData);
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $aDataResult  = [
            'aWaHouseListup'     => $aWaHouseListup,
            'aWaHouseListdown'   => $aWaHouseListdown,
            'aAlwEvent'          => $aAlwEventConnectionSetting,
            'tSearchAllNotSet'   => $tSearchAllNotSet,
            'tSearchAllSetUp'    => $tSearchAllSetUp
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingUMS',$aDataResult);

     }  
     
    //Functionality :  Load Page UserShop 
    //Parameters : 
    //Creator : 19/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageUserShop(){
        $this->load->view('interface/connectionsetting/wConnectionsettingUserShop');
    }

    //Functionality :  Load Page CarInter 
    //Parameters : 
    //Creator : 19/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageCarInter(){
        $this->load->view('interface/connectionsetting/wConnectionsettingCarInter');
    }

    //Functionality :  Load Page CarInter 
    //Parameters : 
    //Creator : 19/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageMapping(){
        $this->load->view('interface/connectionsetting/wConnectionsettingMapping');
    }

    //Functionality :  Load Page CarInter 
    //Parameters : 
    //Creator : 19/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageUMS(){
        $this->load->view('interface/connectionsetting/wConnectionsettingUMS');
    }

    //Functionality :  Load Page MSShop 
    //Parameters : 
    //Creator : 23/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageMSShop(){
        $this->load->view('interface/connectionsetting/wConnectionsettingMSShop');
    }

    //Functionality :  Load Page ErrMsg 
    //Parameters : 
    //Creator : 23/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageErrMsg(){
        $this->load->view('interface/connectionsetting/wConnectionsettingRespond');
    }


    //Functionality :  Load Page Add settingUserShop 
    //Parameters : 
    //Creator : 19/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageWahouse(){
        $this->load->view('interface/connectionsetting/wConnectionsettingWahouse');
    }

    //Functionality :  Load Page Add Wahouse 
    //Parameters : 
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddWahouse(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingAdd',$aDataAdd);

    }

    //Functionality :  Load Page Add UserShop
    //Parameters : 
    //Creator : 20/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddUsrShop(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $tTable = "TLKMCstShp";
        $aBranchUsed        = $this->mConnectionSetting->FSaMCCGetDataUsedBranch($tTable);
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aBranchUsed'   => $aBranchUsed,
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingUsrShopAdd',$aDataAdd);

    }

    //Functionality :  Load Page Add MID
    //Parameters : 
    //Creator : 20/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddMSShop(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $aMSUsed        = $this->mConnectionSetting->FSaMCCGetDataUsedMS();
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'aUsedPos'     => $aMSUsed,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingMSShopAdd',$aDataAdd);

    }

    //Functionality :  Load Page Add Respond
    //Parameters : 
    //Creator : 20/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddRespond(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingRespondAdd',$aDataAdd);

    }

    //Functionality :  Load Page Add CarInter 
    //Parameters : 
    //Creator : 20/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddCarInter(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $tTable = "TLKMCarInterBA";
        $aCarUsed        = $this->mConnectionSetting->FSaMCCGetDataUsedCar($tTable);
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'aCarUsed'   => $aCarUsed,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingCarInterAdd',$aDataAdd);

    }

    //Functionality :  Load Page Add Mapping 
    //Parameters : 
    //Creator : 20/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSPageAddMapping(){
        $aAlwEventConnectionSetting = FCNaHCheckAlwFunc('ConnectionSetting/0/0');
        $aDataAdd = [
            'aResult'       => array('rtCode'=>'99'),
            'aAlwEvent'     => $aAlwEventConnectionSetting,
            'tBchCompCode'  => $this->session->userdata("tSesUsrBchCodeDefault"),
            'tBchCompName'  => $this->session->userdata("tSesUsrBchNameDefault"),
            'tShpCompCode'  => $this->session->userdata("tSesUsrShpCodeDefault"),
            'tShpCompName'  => $this->session->userdata("tSesUsrShpNameDefault"),
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'tSesAgnName'   => $this->session->userdata("tSesUsrAgnName"),
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingMappingAdd',$aDataAdd);

    }

    //Functionality : Event Add settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/202020 saharat(Golf)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSxCCCSWahouseEventAdd(){
        try{
           
            $aDataMaster        = [
                'FTAgnCode'         => $this->input->post('oetCssAgnCode'),
                'FTBchCode'         => $this->input->post('oetCssBchCode'),
                'FTShpCode'         => $this->input->post('oetCssShpCode'),
                'FTWahCode'         => $this->input->post('oetCssWahCode'),
                'FTWahRefNo'        => $this->input->post('oetCssWahRefNo'),
                // 'FTWahStaChannel'   => $this->input->post('ocmStaChannel'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FNLngID'           => $this->session->userdata("tLangEdit"),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add UserShop
    //Parameters : Ajax jConnectionSetting()
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSxCCCSUserShopEventAdd(){
        try{
            $aDataMaster        = [
                'FTCshSoldTo'         => $this->input->post('oetCssShopsold'),
                'FTBchCode'           => $this->input->post('oetUsrShopBchCode'),
                'FTCshShipTo'         => $this->input->post('oetCssShopShip'),
                'FTCshCostCenter'     => $this->input->post('oetCssShopCost'),
                'FTCshWhTaxCode'      => $this->input->post('oetCssShopVat'),
                'FNLngID'   => $this->session->userdata("tLangEdit")
            ];
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateCstShpMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add UserShop
    //Parameters : Ajax jConnectionSetting()
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSxCCCSCarInterEventAdd(){
        try{
            $aDataMaster        = [
                'FTCarRegNo'        => $this->input->post('oetCssCarName'),
                'FTCbaIO'           => $this->input->post('oetIOCode'),
                'FTCbaCostCenter'   => $this->input->post('oetCssCostCode'),
                'FTCbaStaTax'       => $this->input->post('oetVatStatus'),
                'FTCbaID'                => $this->input->post('oetCssCarID'),
                'FNLngID'           => $this->session->userdata("tLangEdit")
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateCarInterMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add UserShop
    //Parameters : Ajax jConnectionSetting()
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSxCCCSMSShopEventAdd(){
        try{
            $aDataMaster        = [
                'FTStaBlueCode'     => $this->input->post('oetBlueCardStatus'),
                'FTApiLoginUsr'     => $this->input->post('oetMSShopUser'),
                'FTApiLoginPwd'     => $this->input->post('oetMSShopPasswordEncode'),
                'FTApiToken'        => $this->input->post('oetMSShopApiToken'),
                'FTBchCode'         => $this->input->post('oetUsrShopBchCode'),
                'FTPosCode'         => $this->input->post('oetMSShopPosCode'),
                'FTLmsMID'          => $this->input->post('oetMSShopMid'),
                'FTLmsTID'          => $this->input->post('oetMSShopTid'),
                'FNLngID'           => $this->session->userdata("tLangEdit"),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            ];
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateMSShopMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add UserShop
    //Parameters : Ajax jConnectionSetting()
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSxCCCSRespondEventAdd(){
        try{
            $aDataMaster        = [
                'FTErrCode'                 => $this->input->post('oetErrCode'),
                'FTErrStaApp'               => $this->input->post('oetStaApp'),
                'FTErrDescription'          => $this->input->post('otaErrDetail'),
                'FNLngID'                   => $this->session->userdata("tLangEdit"),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            ];

                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateErrMsgMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality :  Load Page settingWahouse
    //Parameters : 
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSWahousePageEdit(){
        $aData  = [
            'FTAgnCode' => $this->input->post('tMerCode'),
            'FTBchCode' => $this->input->post('tBchCode'),
            'FTShpCode' => $this->input->post('tShpCode'),
            'FTWahCode' => $this->input->post('tWahCode'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];

        $aResult        = $this->mConnectionSetting->FSaMCCGetDataDown($aData);
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0')
        ];

        $this->load->view('interface/connectionsetting/wConnectionsettingAdd',$aDataEdit);

    }

    //Functionality :  Load Page settingWahouse
    //Parameters : 
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSMSShopPageEdit(){
        $aData  = [
            'FTPosCode' => $this->input->post('tPosCode'),
            'FTBchCode' => $this->input->post('tBchCode'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];

        $aResult        = $this->mConnectionSetting->FSaMCCGetDataMSShop($aData);
        $aMSUsed        = $this->mConnectionSetting->FSaMCCGetDataUsedMS();
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0'),
            'aUsedPos'     => $aMSUsed
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingMSShopAdd',$aDataEdit);

    }

    //Functionality :  Load Page setting ErrMsg
    //Parameters : 
    //Creator : 23/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSErrMsgPageEdit(){
        $aData  = [
            'FTErrCode' => $this->input->post('tErrCode'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];

        $aResult        = $this->mConnectionSetting->FSaMCCGetDataErrMsg($aData);
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0')
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingRespondAdd',$aDataEdit);

    }

    //Functionality :  Load Page settingWahouse
    //Parameters : 
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSMSShopTestHost(){
        $nTimeout = $this->input->post('nTimeout');
        $tMid = $this->input->post('tMid');
        $tTid = $this->input->post('tTid');
        $tUser = $this->input->post('tUserName');
        $tPass = $this->input->post('tPassWord');
        $tURL = $this->input->post('tURL');

        $aGetTokenAPI    = $this->mConnectionSetting->FSaMCCGetToken();
        $aGetTestHost    = $this->mConnectionSetting->FSaMCCGetTestHost();
        $tUrlApiToken = $tURL.'/?grant_type=client_credentials';
        $tUrlTestHost = $aGetTestHost[0]->FTApiURL;

        $aAPIKey    = array();
        $aParam1     = array(
            'ptLang'        => $this->session->userdata("tLangEdit")
        );
        $aParam     = array(
            'ptLang'        => $this->session->userdata("tLangEdit"),
            "AddressData"        => array(
                "Method"        => "TestHost",
                "MethodParam"   => 0
            ),
            "SystemData"        => array(
                "Batch_ID"        => 0,
                "MID"   => $tMid,
                "Stand_ID"   => 0,
                "TID"   => $tTid
            ),
        );
        $aLogin = array(
            "login" => $tUser,
            "password" => $tPass
        );

        $oResultGetToken  = FCNaHCallAPIGetToken($tUrlApiToken,'POST',$aParam1,$aAPIKey,'json',$nTimeout,$aLogin);
        if(isset($oResultGetToken['error'])){
            echo 'Error Get Token';
        }else{
            $tGettoken = $oResultGetToken['access_token'];
            $oResultTestHost  = FCNaHCallAPITestHost($tUrlTestHost,'POST',$aParam,$aAPIKey,'json',$nTimeout,$tGettoken);
            echo json_encode($oResultTestHost);
        }
    }

    //Functionality :  Load Page settingMapping
    //Parameters : 
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSMappingPageEdit(){
        $aData  = [
            'FTMapCode' => $this->input->post('tMappingCode'),
            'FNMapSeqNo' => $this->input->post('tMappingSeqNo'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];
        $aResult        = $this->mConnectionSetting->FSaMCCGetDataMapping($aData);
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0')
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingMappingAdd',$aDataEdit);
    }

    //Functionality :  Load Page settingMapping
    //Parameters : 
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSCstShpPageEdit(){
        $aData  = [
            'FTBchCode' => $this->input->post('tBchCode'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];
        $tTable             = "TLKMCstShp";
        $aBranchUsed        = $this->mConnectionSetting->FSaMCCGetDataUsedBranch($tTable); 
        $aResult            = $this->mConnectionSetting->FSaMCCGetDataCstShp($aData);
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aBranchUsed'   => $aBranchUsed,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0')
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingUsrShopAdd',$aDataEdit);
    }

    //Functionality :  Load Page settingCarInter
    //Parameters : 
    //Creator : 21/07/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCCCSCarInterPageEdit(){
        $aData  = [
            'FTCarRegNo' => $this->input->post('tCarReq'),
            'FNLngID'   => $this->session->userdata("tLangEdit")
        ];
        $tTable = "TLKMCarInterBA";
        $aCarUsed           = $this->mConnectionSetting->FSaMCCGetDataUsedCar($tTable);
        $aResult            = $this->mConnectionSetting->FSaMCCGetDataCarInter($aData);
        $aDataEdit  = [
            'aResult'    => $aResult,
            'aCarUsed'   => $aCarUsed,
            'aAlwEvent'  => FCNaHCheckAlwFunc('ConnectionSetting/0/0')
        ];
        $this->load->view('interface/connectionsetting/wConnectionsettingCarInterAdd',$aDataEdit);
    }
    

    //Functionality : Event Edit settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSWahouseEventEdit(){
        try{
           
            $aDataMaster        = [
                'FTAgnCode'         => $this->input->post('oetCssAgnCode'),
                'FTBchCode'         => $this->input->post('oetCssBchCode'),
                'FTShpCode'         => $this->input->post('oetCssShpCode'),
                'FTWahCode'         => $this->input->post('oetCssWahCode'),
                'FTWahRefNo'        => $this->input->post('oetCssWahRefNo'),
                // 'FTWahStaChannel'   => $this->input->post('ocmStaChannel'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FNLngID'           => $this->session->userdata("tLangEdit"),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

        //Functionality : Event Edit settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSCstShpEventEdit(){
        try{
           
            $aDataMaster        = [
                'FTCshSoldTo'         => $this->input->post('oetCssShopsold'),
                'FTBchCode'           => $this->input->post('oetUsrShopBchCode'),
                'FTCshShipTo'         => $this->input->post('oetCssShopShip'),
                'FTCshCostCenter'     => $this->input->post('oetCssShopCost'),
                'FTCshWhTaxCode'      => $this->input->post('oetCssShopVat'),
                'FNLngID'             => $this->session->userdata("tLangEdit")
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateCstShpMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSCarInterEventEdit(){
        try{
           
            $aDataMaster        = [
                'FTCarRegNo'        => $this->input->post('oetCssCarName'),
                'FTCbaIO'           => $this->input->post('oetIOCode'),
                'FTCbaCostCenter'   => $this->input->post('oetCssCostCode'),
                'FTCbaStaTax'       => $this->input->post('oetVatStatus'),
                'ID'                => $this->input->post('oetCssCarID'),
                'FNLngID'           => $this->session->userdata("tLangEdit")
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateCarInterMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    
    //Functionality : Event Edit settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSMappingEventEdit(){
        try{
           
            $aDataMaster        = [
                'FTMapName'         => $this->input->post('oetMPName'),
                'FTMapDefValue'     => $this->input->post('oetMPCompar'),
                'FTMapUsrValue'     => $this->input->post('oetMPActive'),
                'FTMapCode'         => $this->input->post('ohdMpCode'),
                'FNMapSeqNo'        => $this->input->post('ohdMpSeqNo'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateMappingMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit settingWahouse
    //Parameters : Ajax jConnectionSetting()
    //Creator : 15/05/2020 saharat(Golf)
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSMSShopEventEdit(){
        try{
           
            $aDataMaster        = [
                'FTStaBlueCode'     => $this->input->post('oetBlueCardStatus'),
                'FTApiLoginUsr'     => $this->input->post('oetMSShopUser'),
                'FTApiLoginPwd'     => $this->input->post('oetMSShopPasswordEncode'),
                'FTApiToken'        => $this->input->post('oetMSShopApiToken'),
                'FTBchCode'         => $this->input->post('oetUsrShopBchCode'),
                'FTPosCode'         => $this->input->post('oetMSShopPosCode'),
                'FTLmsMID'          => $this->input->post('oetMSShopMid'),
                'FTLmsTID'          => $this->input->post('oetMSShopTid'),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            ];
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateMSShopMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit ErrMsg
    //Parameters : Ajax jConnectionSetting()
    //Creator : 23/07/2021 Off
    //Last Modified : -
    //Return : Status Edit Event
    //Return Type : View 
    public function FSxCCCSMSShopEventRespond(){
        try{
           
            $aDataMaster        = [
                'FTErrCode'                 => $this->input->post('oetErrCode'),
                'FTErrStaApp'               => $this->input->post('oetStaApp'),
                'FTErrDescription'          => $this->input->post('otaErrDetail'),
                'FNLngID'                   => $this->session->userdata("tLangEdit"),
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            ];
            
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mConnectionSetting->FSaMCSSAddUpdateErrMsgMaster($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
       
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : Delete Siggle
	//Parameters : From Ajax File Userlogin
	//Creator : 04/07/2020 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSaCCCSDeleteEvent(){

       $tAgnCode  = $this->input->post('tAgnCode');
       $tBchCode  = $this->input->post('tBchCode');
       $tShpCode  = $this->input->post('tShpCode');
       $tWahCode  = $this->input->post('tWahCode');


        $aDataDel  = array(
            'FTAgnCode'   => $tAgnCode,
            'FTBchCode'   => $tBchCode,
            'FTShpCode'   => $tShpCode,
            'FTWahCode'   => $tWahCode
        );
        $tTable ='TLKMWaHouse';

         $aResult       =  $this->mConnectionSetting->FSnMConnSetDel($aDataDel);
         $nNumRowRsnLoc  = $this->mConnectionSetting->FSnMLOCGetAllNumRow($tTable);

         if($nNumRowRsnLoc){
            $aReturn    = array(
                'nStaEvent'     => $aResult['rtCode'],
                'tStaMessg'     => $aResult['rtDesc'],
                'nNumRowRsnLoc' => $nNumRowRsnLoc
            );
            echo json_encode($aReturn);
        }else{
            echo "database error";
        }

    }

    //Functionality : Delete Siggle
	//Parameters : From Ajax File Userlogin
	//Creator : 04/07/2020 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSaCCCSDeleteEventMSShop(){

        $tPosCode  = $this->input->post('tPosCode');
        $tBchCode  = $this->input->post('tBchCode');
 
 
         $aDataDel  = array(
             'FTBchCode'   => $tBchCode,
             'FTPosCode'   => $tPosCode
         );
         $tTable ='TLKMLMSShop';
 
          $aResult       =  $this->mConnectionSetting->FSnMConnSetDelMSShop($aDataDel);
          $nNumRowRsnLoc  = $this->mConnectionSetting->FSnMLOCGetAllNumRow($tTable);
 
          if($nNumRowRsnLoc){
             $aReturn    = array(
                 'nStaEvent'     => $aResult['rtCode'],
                 'tStaMessg'     => $aResult['rtDesc'],
                 'nNumRowRsnLoc' => $nNumRowRsnLoc
             );
             echo json_encode($aReturn);
         }else{
             echo "database error";
         }
 
     }

    //Functionality : Delete Siggle CstShp
	//Parameters : From Ajax File Userlogin
	//Creator : 22/07/2021 Off
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSaCCCSDeleteEventCstShp(){

        $tBchCode  = $this->input->post('tBchCode');
 
 
         $aDataDel  = array(
             'FTBchCode'   => $tBchCode,
         );

         $tTable ='TLKMCstShp';
          $aResult       =  $this->mConnectionSetting->FSnMConnSetDelCstShp($aDataDel);
          $nNumRowRsnLoc  = $this->mConnectionSetting->FSnMLOCGetAllNumRow($tTable);
 
          if($nNumRowRsnLoc){
             $aReturn    = array(
                 'nStaEvent'     => $aResult['rtCode'],
                 'tStaMessg'     => $aResult['rtDesc'],
                 'nNumRowRsnLoc' => $nNumRowRsnLoc
             );
             echo json_encode($aReturn);
         }else{
             echo "database error";
         }
 
     }

    //Functionality : Delete Siggle CstShp
	//Parameters : From Ajax File Userlogin
	//Creator : 22/07/2021 Off
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSaCCCSDeleteEventRespond(){

        $tErrReq  = $this->input->post('tErrReq');
 
 
         $aDataDel  = array(
             'FTErrCode'   => $tErrReq,
         );

         $tTable ='TLKMErrMsg';
          $aResult       =  $this->mConnectionSetting->FSnMConnSetDelErrMsg($aDataDel);
          $nNumRowRsnLoc  = $this->mConnectionSetting->FSnMLOCGetAllNumRow($tTable);
 
          if($nNumRowRsnLoc){
             $aReturn    = array(
                 'nStaEvent'     => $aResult['rtCode'],
                 'tStaMessg'     => $aResult['rtDesc'],
                 'nNumRowRsnLoc' => $nNumRowRsnLoc
             );
             echo json_encode($aReturn);
         }else{
             echo "database error";
         }
 
     }

    //Functionality : Delete Siggle CarInter
	//Parameters : From Ajax File Userlogin
	//Creator : 22/07/2021 Off
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSaCCCSDeleteEventCarInter(){

        $tCarReq  = $this->input->post('tCarReq');
 
 
         $aDataDel  = array(
             'FTCarRegNo'   => $tCarReq,
         );

         $tTable ='TLKMCarInterBA';
          $aResult       =  $this->mConnectionSetting->FSnMConnSetDelCarInter($aDataDel);
          $nNumRowRsnLoc  = $this->mConnectionSetting->FSnMLOCGetAllNumRow($tTable);
 
          if($nNumRowRsnLoc){
             $aReturn    = array(
                 'nStaEvent'     => $aResult['rtCode'],
                 'tStaMessg'     => $aResult['rtDesc'],
                 'nNumRowRsnLoc' => $nNumRowRsnLoc
             );
             echo json_encode($aReturn);
         }else{
             echo "database error";
         }
 
     }

    //Parameters : Ajax jUserlogin()
    //Creator : 20/08/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaCCCSDelMultipleEvent(){
        try{
            $this->db->trans_begin();

            $aDataDelete    = array(
                'aDataAgnCode'  => $this->input->post('paDataAgnCode'),
                'aDataBchCode'  => $this->input->post('paDataBchCode'),
                'aDataShphCode'  => $this->input->post('paDataShphCode'),
                'aDataWahCode'  => $this->input->post('paDataWahCode'),
            );

            $tResult    = $this->mConnectionSetting->FSaMConnDeleteMultiple($aDataDelete);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Pos Ads Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Pos Ads Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }

    //Parameters : Ajax jUserlogin()
    //Creator : 20/08/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaCCCSDelMultipleEventMSShop(){
        try{
            $this->db->trans_begin();

            $aDataDelete    = array(
                'aDataPosCode'  => $this->input->post('paDataPosCode'),
                'aDataBchCode'  => $this->input->post('paDataBchCode'),
            );

            $tResult    = $this->mConnectionSetting->FSaMConnDeleteMultipleMSShop($aDataDelete);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Pos Ads Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Pos Ads Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }

    //Parameters : Ajax jUserlogin()
    //Creator : 20/08/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaCCCSDelMultipleEventCstShp(){
        try{
            $this->db->trans_begin();

            $aDataDelete    = array(
                'aDataBchCode'  => $this->input->post('paDataBchCode'),
            );

            $tResult    = $this->mConnectionSetting->FSaMConnDeleteMultipleCstShp($aDataDelete);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Pos Ads Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Pos Ads Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }

    //Parameters : Ajax jUserlogin()
    //Creator : 22/07/2021 Off
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaCCCSDelMultipleEventCarInter(){
        try{
            $this->db->trans_begin();

            $aDataDelete    = array(
                'aDataCarReq'  => $this->input->post('paDataCarReq'),
            );

            $tResult    = $this->mConnectionSetting->FSaMConnDeleteMultipleCarInter($aDataDelete);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Pos Ads Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Pos Ads Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }

    //Parameters : Ajax jUserlogin()
    //Creator : 22/07/2021 Off
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSaCCCSDelMultipleEventErrMsg(){
        try{
            $this->db->trans_begin();

            $aDataDelete    = array(
                'aDataErrCode'  => $this->input->post('paDataErrCode'),
            );

            $tResult    = $this->mConnectionSetting->FSaMConnDeleteMultipleErrMsg($aDataDelete);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Pos Ads Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Pos Ads Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }











}
