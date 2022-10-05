<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

class Car_controller extends MX_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('service/car/Car_model');
    }

    public function index($nCarBrowseType, $tCarBrowseOption){
        $vBtnSave       = FCNaHBtnSaveActiveHTML('masCARView/0/0');
        $aAlwEventCar   = FCNaHCheckAlwFunc('masCARView/0/0');
        $this->load->view('service/car/wCar', array(
            'vBtnSave'          => $vBtnSave,
            'nCarBrowseType'    => $nCarBrowseType,
            'tCarBrowseOption'  => $tCarBrowseOption,
            'aAlwEventCar'      => $aAlwEventCar
        ));
        unset($vBtnSave,$aAlwEventCar,$nCarBrowseType, $tCarBrowseOption);
    }

    //Functionality : Function Call Page Car List
    //Parameters : Ajax jCar()
    //Creator : 09/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCARListPage(){
        $aNewData   = array(
            'aAlwEventCar' => FCNaHCheckAlwFunc('masCARView/0/0')
        );
        $this->load->view('service/car/wCarList', $aNewData);
        unset($aNewData);
    }

    //Functionality : Function Call DataTables Car List
    //Parameters : Ajax jCar()
    //Creator : 09/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCARDataList(){
        $nPage      = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if (!$tSearchAll) {
            $tSearchAll = '';
        }
        $aSearchType    = array(
            'FTCarType'         => $this->input->post('tSearchType1'),
            'FTCarBrand'        => $this->input->post('tSearchType2'),
            'FTCarModel'        => $this->input->post('tSearchType3'),
            'FTCarColor'        => $this->input->post('tSearchType4'),
            'FTCarGear'         => $this->input->post('tSearchType5'),
            'FTCarPowerType'    => $this->input->post('tSearchType6'),
            'FTCarEngineSize'   => $this->input->post('tSearchType7'),
            'FTCarCategory'     => $this->input->post('tSearchType8'),
        );
        $aSearchType    = array_filter($aSearchType,'strlen');
        //Lang ภาษา
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData          = array(
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,
            'tSesAgnCode'   => $this->session->userdata("tSesUsrAgnCode"),
            'aSearchType'   => $aSearchType,
        );
        $aResList   = $this->Car_model->FSaMCARList($aData);
        $aAlwEvent  = FCNaHCheckAlwFunc('masCARView/0/0'); //Controle Event
        $aGenTable  = array(
            'aAlwEventCar'  => $aAlwEvent,
            'aDataList'     => $aResList,
        );
        $this->load->view('service/car/wCarDataTable', $aGenTable);
    }

    //Functionality : Function Call Add Page Car
    //Parameters : Ajax jCar()
    //Creator : 10/06/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCARAddPage(){
        $aDataAdd       = array(
            'aResult'   => array('rtCode' => '99'),
        );
        $this->load->view('service/car/wCarAdd', $aDataAdd);
        unset($aDataAdd);
    }

    //Functionality : Function Call Edit Page Car
    //Parameters : Ajax jCar()
    //Creator : 25/05/2021 Off
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCAREditPage(){
        $tCarCode       = $this->input->post('tCarCode');
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData          = array(
            'FTCarCode' => $tCarCode,
            'FNLngID'   => $nLangEdit
        );
        $aCldData       = $this->Car_model->FSaMCCARSearchByID($aData);
        $aDataEdit      = array(
            'aResult'   => $aCldData,
        );
        $this->load->view('service/car/wCarAdd', $aDataEdit);
        unset($tCarCode,$nLangEdit,$aData,$aCldData,$aDataEdit);
    }

    //Functionality : Event Add Car
    //Parameters : Ajax jCar()
    //Creator : 10/06/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCCARAddEvent(){
        // print_r($this->input->post());
        try {
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbCalendarAutoGenCode'),
                'FTCarCode'             => $this->input->post('oetCarCode'),
                'FTCarRegProvince'      => $this->input->post('oetCarProvinceCode'),
                'FTCarRegNo'            => $this->input->post('oetCarNoreq'),
                'FTCarEngineNo'         => $this->input->post('oetCarEnginereq'),
                'FTCarVIDRef'           => $this->input->post('oetCarPowerreq'),
                'FTCarType'             => $this->input->post('oetCarOptionID1'),
                'FTCarBrand'            => $this->input->post('oetCarOptionID2'),
                'FTCarModel'            => $this->input->post('oetCarOptionID3'),
                'FTCarColor'            => $this->input->post('oetCarOptionID4'),
                'FTCarGear'             => $this->input->post('oetCarOptionID5'),
                'FTCarPowerType'        => $this->input->post('oetCarOptionID6'),
                'FTCarEngineSize'       => $this->input->post('oetCarOptionID7'),
                'FTCarCategory'         => $this->input->post('oetCarOptionID8'),
                'FTCarStaRedLabel'      => (!empty($this->input->post('ocbCarRedLabel')))? 1 : 2,
                'FDCarDOB'              => $this->input->post('oetCarStart'),
                'FDCarOwnChg'           => $this->input->post('oetCarFinish'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTCstCode'             => $this->input->post('oetCarUserCode'),
                'FTCbrBchCode'          => $this->input->post('oetCarRefBCHCode'),
            );
            if ($aDataMaster['tIsAutoGenCode'] == '1') {
                $aStoreParam = array(
                    "tTblName"   => 'TSVMCar',
                    "tDocType"   => 0,
                    "tBchCode"   => "",
                    "tShpCode"   => "",
                    "tPosCode"   => "",
                    "dDocDate"   => date("Y-m-d")
                );
                $aAutogen                   = FCNaHAUTGenDocNo($aStoreParam);
                $aDataMaster['FTCarCode']   = $aAutogen[0]["FTXxhDocNo"];
            }
            $oCountDup  = $this->Car_model->FSoMCARCheckDuplicate($aDataMaster['FTCarCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if ($nStaDup == 0) {
                $this->db->trans_begin();
                $this->Car_model->FSaMCARAddUpdateMaster($aDataMaster);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                } else {
                    $this->db->trans_commit();
                    $tImgInputCar           = $this->input->post("oetImgInputCar");
                    $tImgInputCarOld        = $this->input->post("oetImgInputCarOld");
                    $aPackUplode = array(
                        'tModuleName'       => 'service',
                        'tImgFolder'        => 'car',
                        'tImgRefID'         => $aDataMaster['FTCarCode'],
                        'tImgTable'         => 'TSVMCar',
                        'tTableInsert'      => 'TCNMImgObj',
                        'tImgKey'           => 'main',
                        'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                        'FDCreateOn'        => date('Y-m-d H:i:s'),
                        'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                        'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                        'nStaDelBeforeEdit' => 1,
                        'dDateTimeOn'       => date('Y-m-d H:i:s'),
                        'tWhoBy'            => $this->session->userdata('tSesUsername'),
                    );
                    if (isset($tImgInputCar) && !empty($tImgInputCar) && $tImgInputCar != $tImgInputCarOld) {
                        $aPackUplode['tImgObj'] = $tImgInputCar;
                        FCNnHAddImgObj($aPackUplode);
                    } else {
                        $tCheckedColor  = $this->input->post('orbChecked');
                        $tInputColor    = $this->input->post('oetImgColorCar');

                        if ((isset($tCheckedColor) && !empty($tCheckedColor)) || (isset($tInputColor) && !empty($tInputColor))) {
                            $aPackUplode['tImgObj'] = (isset($tCheckedColor) && !empty($tCheckedColor) ? $tCheckedColor : $tInputColor);
                            FCNxHAddColorObj($aPackUplode);
                        }
                    }
                    $aReturn = array(
                        'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'    => $aDataMaster['FTCarCode'],
                        'nStaEvent'        => '1',
                        'tStaMessg'        => 'Success Add Event'
                    );
                }
            } else {
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Event Edit Car
    //Parameters : Ajax jCar()
    //Creator : 10/06/2021 Off
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCCAREditEvent(){
        try {
            $aDataMaster    = array(
                'tIsAutoGenCode'        => $this->input->post('ocbCalendarAutoGenCode'),
                'FTCarCode'             => $this->input->post('oetCarCode'),
                'FTCarRegNo'            => $this->input->post('oetCarNoreq'),
                'FTCarRegProvince'      => $this->input->post('oetCarProvinceCode'),
                'FTCarEngineNo'         => $this->input->post('oetCarEnginereq'),
                'FTCarVIDRef'           => $this->input->post('oetCarPowerreq'),
                'FTCarType'             => $this->input->post('oetCarOptionID1'),
                'FTCarBrand'            => $this->input->post('oetCarOptionID2'),
                'FTCarModel'            => $this->input->post('oetCarOptionID3'),
                'FTCarColor'            => $this->input->post('oetCarOptionID4'),
                'FTCarGear'             => $this->input->post('oetCarOptionID5'),
                'FTCarPowerType'        => $this->input->post('oetCarOptionID6'),
                'FTCarEngineSize'       => $this->input->post('oetCarOptionID7'),
                'FTCarCategory'         => $this->input->post('oetCarOptionID8'),
                'FTCarStaRedLabel'      => (!empty($this->input->post('ocbCarRedLabel')))? 1 : 2,
                'FDCarDOB'              => $this->input->post('oetCarStart'),
                'FDCarOwnChg'           => $this->input->post('oetCarFinish'),
                'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                'FDCreateOn'            => date('Y-m-d H:i:s'),
                'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                'FTCreateBy'            => $this->session->userdata('tSesUsername'),
                'FNLngID'               => $this->session->userdata("tLangEdit"),
                'FTCstCode'             => $this->input->post('oetCarUserCode'),
                'FTCbrBchCode'          => $this->input->post('oetCarRefBCHCode'),
            );
            $this->db->trans_begin();
            $this->Car_model->FSaMCARAddUpdateMaster($aDataMaster);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Event"
                );
            } else {
                $this->db->trans_commit();
                $tImgInputCar           = $this->input->post("oetImgInputCar");
                $aPackUplode = array(
                    'tModuleName'       => 'service',
                    'tImgFolder'        => 'car',
                    'tImgRefID'         => $aDataMaster['FTCarCode'],
                    'tImgTable'         => 'TSVMCar',
                    'tTableInsert'      => 'TCNMImgObj',
                    'tImgKey'           => 'main',
                    'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                    'FDCreateOn'        => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                    'nStaDelBeforeEdit' => 1,
                    'dDateTimeOn'       => date('Y-m-d H:i:s'),
                    'tWhoBy'            => $this->session->userdata('tSesUsername'),
                );
                if (isset($tImgInputCar) && !empty($tImgInputCar)) {
                    $aPackUplode['tImgObj'] = $tImgInputCar;
                    FCNnHAddImgObj($aPackUplode);
                } else {
                    $tCheckedColor  = $this->input->post('orbChecked');
                    $tInputColor    = $this->input->post('oetImgColorCar');
                    if ((isset($tCheckedColor) && !empty($tCheckedColor)) || (isset($tInputColor) && !empty($tInputColor))) {
                        $aPackUplode['tImgObj'] = (isset($tCheckedColor) && !empty($tCheckedColor) ? $tCheckedColor : $tInputColor);
                        FCNxHAddColorObj($aPackUplode);
                    }
                }
                $aReturn    = array(
                    'nStaCallBack'  => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'   => $aDataMaster['FTCarCode'],
                    'nStaEvent'     => '1',
                    'tStaMessg'     => 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Event Delete Car
    //Parameters : Ajax jCar()
    //Creator : 19/05/2021 Off
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCCARDeleteEvent(){
        $tIDCode        = $this->input->post('tIDCode');
        $aDataMaster    = array(
            'FTCarCode' => $tIDCode
        );
        $tAPIReq        = 'API/Car/Delete';
        $tMethodReq     = 'POST';
        $aCldDel        = $this->Car_model->FSnMCARDel($tAPIReq, $tMethodReq, $aDataMaster);
        $aDeleteImage           = array(
            'tModuleName'  => 'service',
            'tImgFolder'   => 'car',
            'tImgRefID'    => $tIDCode,
            'tTableDel'    => 'TCNMImgObj',
            'tImgTable'    => 'TSVMCar'
        );
        //ลบข้อมูลในตาราง         
        $nStaDelImgInDB = FSnHDelectImageInDB($aDeleteImage);
        if ($nStaDelImgInDB == 1) {
            //ลบรูปในโฟลเดอ
            FSnHDeleteImageFiles($aDeleteImage);
        }


        $aReturn    = array(
            'nStaEvent'     => $aCldDel['rtCode'],
            'tStaMessg'     => $aCldDel['rtDesc']
        );
        echo json_encode($aReturn);
    }


    //Functionality : Function Call DataTables UserCalendar
    //Parameters : Ajax Call View DataTable
    //Creator : 28/05/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCARHistoryDataList(){
        try {
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nPosCode       = $this->input->post('nPosCode');
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tCarRegNo      = $this->input->post('tCarRegNo');
            $aAdvanceSearch = $this->input->post('aAdvanceSearchData');
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'tSearchAll'    => $tSearchAll,
                'nPosCode'      => $nPosCode,
                'FNLngID'       => $nLangEdit,
                'tCarRegNo'     => $tCarRegNo,
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aCarHistoryDataList   = $this->Car_model->FSaMCARHistoryList($aData);
            $aGenTable  = array(
                'aCarHistoryDataList'  => $aCarHistoryDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll
            );
            $this->load->view('service/car/tabservicejob/wServiceJobDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //ข้อมูลการขายของรถคันนี้
    public function FSvCCARSaleHistoryDataList(){
        try {
            $nCarCode               = $this->input->post('nCarCode');
            $aData  = array(
                'nCarCode'          => $nCarCode,
                'tSearchAll'        => $this->input->post('tSearchAll')
            );
            $aResult                = $this->Car_model->FSaMCARSaleHistoryList($aData);
            $aGenTable  = array(
                'aResult'           => $aResult,
            );
            $this->load->view('service/car/tabservicejob/wServiceSaleHistory', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    //Functionality : Function Call DataTables UserCalendar
    //Parameters : Ajax Call View DataTable
    //Creator : 28/05/2021 Off
    //Return : String View
    //Return Type : View
    public function FSvCCAROrderHistoryDataList(){
        try {
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null) ? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nPosCode       = $this->input->post('nPosCode');
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tCarRegNo      = $this->input->post('tCarRegNo');
            $aAdvanceSearch = $this->input->post('aAdvanceSearchData');
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'tSearchAll'    => $tSearchAll,
                'nPosCode'      => $nPosCode,
                'FNLngID'       => $nLangEdit,
                'tCarRegNo'     => $tCarRegNo,
                'aAdvanceSearch' => $aAdvanceSearch
            );
            $aCarOrderHistoryDataList   = $this->Car_model->FSaMCAROrderHistoryList($aData);
            $aGenTable  = array(
                'aCarOrderHistoryDataList'  => $aCarOrderHistoryDataList,
                'nPage'             => $nPage,
                'tSearchAll'        => $tSearchAll
            );
            $this->load->view('service/car/tabservicejob/wOrderHistoryDataTable', $aGenTable);
        } catch (Exception $Error) {
            echo $Error;
        }
    }
}
