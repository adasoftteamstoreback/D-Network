<?php
defined ('BASEPATH') or exit ( 'No direct script access allowed' );

// Defaule Controller
$route ['default_controller']           = 'common/cHome';

// Browse Modal
$route ['BrowseData']                   = 'common/cBrowser/index';

// Browse Modal สินค้าแบบใหม่ เรียก view ของพี่รันต์ (10 กันยายน 2562)
$route ['BrowseDataPDT']                        = 'common/cBrowserPDTCallView/index';
$route ['BrowseDataPDTTableCallView']           = 'common/cBrowserPDTCallView/FSxGetProductfotPDT';
$route ['CallModalAddPDTConfig']                = "common/cBrowserPDTCallView/FSvCallViewModalPdtConfig";
$route ['LoadViewProductSerialandFashion']      = "common/cBrowserPDTCallView/FSvCallViewProductSerialandFashion";
$route ['LoadViewProductLot']                   = "common/cBrowserPDTCallView/FSvCallViewProductLot";
$route ['LoadViewProductSetOrSVSet']            = "common/cBrowserPDTCallView/FSvCallViewProductSetOrSVSet";

// language
$route ['ChangeLang/(:any)/(:num)']     = 'common/cLanguage/index/$1/$2';
$route ['ChangeLangEdit']               = 'common/cLanguage/FSxChangeLangEdit';
$route ['ChangeBtnSaveAction']          = 'common/cLanguage/FSxChangeBtnSaveAction';

// GenCode
$route ['generateCode']                 = 'common/cCommon/FCNtCCMMGenCode';
$route ['generateCodeV5']               = 'common/cCommon/FCNtCCMMGenCodeV5';
$route ['CheckInputGenCode']            = 'common/cCommon/FCNtCCMMCheckInputGenCode';
$route ['GetPanalLangSystemHTML']       = 'common/cCommon/FCNtCCMMGetLangSystem';
$route ['GetPanalLangListHTML']         = 'common/cCommon/FCNtCCMMChangeLangList';

// Image Temp.
$route ['ImageCallMaster']              = 'common/cTempImg/FSaCallMasterImage';
$route ['ImageCallTemp']                = 'common/cTempImg/FSaCallTempImage';
$route ['ImageCallTempNEW']             = 'common/cTempImg/FSaCallTempImageNEW';
$route ['ImageDeleteFileNEW']           = 'common/cTempImg/FSoImageDeleteNEW';
$route ['ImageUplodeNEW']               = 'common/cTempImg/FSaImageUplodeNEW';
$route ['ImageUplode']                  = 'common/cTempImg/FSaImageUplode';
$route ['ImageConvertCrop']             = 'common/cTempImg/FSoConvertSizeCrop';
$route ['ImageDeleteFile']              = 'common/cTempImg/FSoImageDelete';

// Rabbit MQ
$route ['RabbitMQDeleteQname']          = 'common/rabbitmq/cRabbitMQ/FStDeleteQname';
$route ['RabbitMQUpdateStaDeleteQname'] = 'common/rabbitmq/cRabbitMQ/FStUpdateStaDeleteQname';

// VatRate
$route ['getVateActiveByVatCode']       = 'common/cCommon/FStGetVateActiveByVatCode';

// Route Browse Multiple Select Data (Last Update: 12/12/2019 Wasin(Yoshi))
$route ['BrowseMultiple']               = 'common/browsemultiselect/cBrowseMultiSelect/index';

//เลือกภาษาในการเพิ่มข้อมูล
$route ['SwitchLang']                   = 'common/cLanguage/FSxSwitchLang';
$route ['InsertSwitchLang']             = 'common/cLanguage/FSxEventInsertSwitchLang';

//Addfavorit
$route['Addfavorite']                   = "common/cAddfavorite/FSxAddfavorite";
$route['ChkStafavorite']                = "common/cAddfavorite/FSxChkStaDisable";
$route['CallModalOptionFavorite']       = "common/cAddfavorite/FSvCallViewModalFavorite";
$route['GetDatafavname']                = "common/cAddfavorite/FSxGetDatafavName";

//Notification
$route['GetDataNotification']           = "common/cHome/FSxGetDataNoti";
$route['GetDataNotificationRead']       = "common/cHome/FSxGetDataNotiRead";
$route['InsDataNotification']           = "common/cHome/FSxAddDataNoti";
$route['mntGetDataNotification']        = "common/Notification_controller/FSxNFTGetData";
$route['mntCallNotification']           = "common/Notification_controller/index";
$route['mntSubDataNotification']        = "common/Notification_controller/FSxNFTSubMassage";
$route['mntReadNotification']           = "common/Notification_controller/FSxNFTReadData";

//Import
$route['ImportFileExcel']               = "common/cHome/FSxImpImportFileExcel";

//Get Massage
$route['GetMassageQueue']               = "common/cCommon/FCNtCCMMGetMassageProgress";
$route['GetMassageQueueMutiDocument']   = "common/cCommon/FCNtCCMMGetMassageProgressMutiDocument";

//Upload File
$route['UPFDataTable']                  = "common/UploadFile_Controller/FCNvCUPFCallDataTable";
$route['UPFEventAdd']                   = "common/UploadFile_Controller/FCNaCUPFEventAdd";
$route['UPFEventEdit']                  = "common/UploadFile_Controller/FCNvCUPFEventEdit";
$route['UPFEventDelete']                = "common/UploadFile_Controller/FCNvCUPFEventDelete";
$route['UPFDataTableForNew']            = "common/UploadFile_Controller/FCNvCUPFCallDataTableForNew";

//Get Address Customer
$route['GetAddressCustomer']            = "common/cCommon/FCNtCCMMGetCustomerAddress";

// e-Tax
$route['cenEventCallApiETAX']           = "common/cCommon/FCNaCallApiETAX";

//Pack Data  To Log MQ
$route['PackDataToLogMQ']               = "common/cCommon/FCNoCCMMPackDataToLogMQ";
$route['PackDataToLogClient']           = "common/cCommon/FCNoCCMMPackDataToLogClient";