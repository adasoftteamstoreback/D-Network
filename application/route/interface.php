<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

$route ['interfaceimport/(:any)/(:any)']                = 'Interface/Interfaceimport/cInterfaceimport/index/$1/$2';
$route ['interfaceimportAction']                        = 'Interface/Interfaceimport/cInterfaceimport/FSxCINMCallRabitMQ';

//Interfacehistory ประวัตินำเข้า - นำออก
$route ['interfacehistory/(:any)/(:any)']               = 'interface/interfacehistory/cInterfaceHistory/index/$1/$2';
$route ['interfacehistorylist']                         = 'interface/interfacehistory/cInterfaceHistory/FSxCIFHList';
$route ['interfaceihistorydatatable']                   = 'interface/interfacehistory/cInterfaceHistory/FSaCIFHGetDataTable';

//InterfaceExport ส่งออก 
$route ['interfaceexport/(:any)/(:any)']                = 'interface/interfaceexport/cInterfaceExport/index/$1/$2';
$route ['interfaceexportAction']                        = 'Interface/interfaceexport/cInterfaceExport/FSxCIFXCallRabitMQ';
$route ['interfaceexportCheckDocument']                 = 'Interface/interfaceexport/cInterfaceExport/FSxCIFXCheckDocumentADJ';
$route ['interfaceexportFilterBill']                    = 'Interface/interfaceexport/cInterfaceExport/FSnCIFXFillterBill';
$route ['interfaceexportSendNotiForDocNotApv']          = 'Interface/interfaceexport/cInterfaceExport/FSxCIFXSendNotiForDocNotApv';
$route ['interfaceexportExportForDocNotApv']            = 'Interface/interfaceexport/cInterfaceExport/FSxCIFXExportForDocNotApv';

//ตั้งค่า 
$route ['connectionsetting/(:any)/(:any)']              = 'interface/connectionsetting/cConnectionSetting/index/$1/$2';
$route ['connectionsettingCallPageList']                = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageWahouse';
$route ['connectionsettingDataTable']                   = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataList';
$route ['connectionsettingCallPageAddWahouse']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddWahouse';
$route ['connectionsettingEventAdd']                    = 'interface/connectionsetting/cConnectionSetting/FSxCCCSWahouseEventAdd';
$route ['connectionsettingCallPageEdit']                = 'interface/connectionsetting/cConnectionSetting/FSxCCCSWahousePageEdit';
$route ['connectionsettingEventEdit']                   = 'interface/connectionsetting/cConnectionSetting/FSxCCCSWahouseEventEdit';
$route ['connectionsettingEventDelete']                 = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDeleteEvent';
$route ['connectionsettingEventDeleteMultiple']         = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDelMultipleEvent';

//ตั้งค่า Tab รหัสลูกค้าร้าน 
$route ['connectionsettingCallPageListUrsShop']         = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageUserShop';
$route ['connectionsettingUsrShopDataTable']            = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListUserShop';
$route ['connectionsettingCallPageAddUsrShop']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddUsrShop';
$route ['connectionsettingEventAddUserShop']            = 'interface/connectionsetting/cConnectionSetting/FSxCCCSUserShopEventAdd';
$route ['connectionsettingCallPageEditUserShop']        = 'interface/connectionsetting/cConnectionSetting/FSxCCCSCstShpPageEdit';
$route ['connectionsettingEventEditUserShop']           = 'interface/connectionsetting/cConnectionSetting/FSxCCCSCstShpEventEdit';
$route ['connectionsettingEventDeleteCstShp']           = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDeleteEventCstShp';
$route ['connectionsettingEventDeleteMultipleCstShp']   = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDelMultipleEventCstShp';

//ตั้งค่า Tab ข้อมูลรถหน่วยงาน 
$route ['connectionsettingCallPageListAgcCar']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageCarInter';
$route ['connectionsettingAgcCarDataTable']             = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListCarInter';
$route ['connectionsettingCallPageAddCarInter']         = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddCarInter';
$route ['connectionsettingEventAddCarInter']            = 'interface/connectionsetting/cConnectionSetting/FSxCCCSCarInterEventAdd';
$route ['connectionsettingCallPageEditCarInter']        = 'interface/connectionsetting/cConnectionSetting/FSxCCCSCarInterPageEdit';
$route ['connectionsettingEventEditCarInter']           = 'interface/connectionsetting/cConnectionSetting/FSxCCCSCarInterEventEdit';
$route ['connectionsettingEventDeleteCarInter']         = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDeleteEventCarInter';
$route ['connectionsettingEventDeleteMultipleCarInter'] = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDelMultipleEventCarInter';

//ตั้งค่า Tab ข้อมูล Mapping 
$route ['connectionsettingCallPageListMapping']         = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageMapping';
$route ['connectionsettingMappingDataTable']            = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListMapping';
$route ['connectionsettingCallPageAddMapping']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddMapping';
$route ['connectionsettingCallPageEditMapping']         = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMappingPageEdit';
$route ['connectionsettingEventEditMapping']            = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMappingEventEdit';

//ตั้งค่า Tab ข้อมูล UMS 
$route ['connectionsettingCallPageListUMS']             = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageUMS';
$route ['connectionsettingUMSDataTable']                = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListUMS';

// Tab MSSHOP
$route ['connectionsettingCallPageListMSShop']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageMSShop';
$route ['connectionsettingMSShopDataTable']             = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListMSShop';
$route ['connectionsettingCallPageAddMSShop']           = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddMSShop';
$route ['connectionsettingEventAddMSShop']              = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMSShopEventAdd';
$route ['connectionsettingMSShopTestHost']              = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMSShopTestHost';
$route ['connectionsettingCallPageEditMSShop']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMSShopPageEdit';
$route ['connectionsettingEventEditMSShop']             = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMSShopEventEdit';
$route ['connectionsettingEventDeleteMSShop']           = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDeleteEventMSShop';
$route ['connectionsettingEventDeleteMultipleMSShop']   = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDelMultipleEventMSShop';

// Tab ErrMessage
$route ['connectionsettingCallPageListErrMsg']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageErrMsg';
$route ['connectionsettingErrMsgDataTable']             = 'interface/connectionsetting/cConnectionSetting/FSvCCCSDataListErrMsg';
$route ['connectionsettingCallPageAddRespond']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSPageAddRespond';
$route ['connectionsettingEventAddRespond']             = 'interface/connectionsetting/cConnectionSetting/FSxCCCSRespondEventAdd';
$route ['connectionsettingCallPageEditErrMsg']          = 'interface/connectionsetting/cConnectionSetting/FSxCCCSErrMsgPageEdit';
$route ['connectionsettingEventEditRespond']            = 'interface/connectionsetting/cConnectionSetting/FSxCCCSMSShopEventRespond';
$route ['connectionsettingEventDeleteRespond']          = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDeleteEventRespond';
$route ['connectionsettingEventDeleteMultipleErrMsg']   = 'interface/connectionsetting/cConnectionSetting/FSaCCCSDelMultipleEventErrMsg';

//ตั้งค่า Tab ทั่วไป 
$route ['connectSetGenaral']                        = 'interface/connectionsetting/cConSettingGenaral/FSxSETMainPage';
$route ['connsetGenDataTable']                      = 'interface/connectionsetting/cConSettingGenaral/FSvSETDataList';
$route ['consetgenEventedit']                       = 'interface/connectionsetting/cConSettingGenaral/FSxSETEventAdd';
$route ['ConSettingGanPageEdit']                    = 'interface/connectionsetting/cConSettingGenaral/FSvSETPageEdit';
$route ['ConSettingGanPageEditApiAuth']             = 'interface/connectionsetting/cConSettingGenaral/FSvSETPageEditApiAuth';
$route ['ConSettingGanPageAdd']                     = 'interface/connectionsetting/cConSettingGenaral/FSvSETPageAdd';
$route ['ConnSetGenaralEventAuthorEdit']            = 'interface/connectionsetting/cConSettingGenaral/FSvSETEventAuthorEdit';
$route ['ConnSetGenaralEventAuthorAdd']             = 'interface/connectionsetting/cConSettingGenaral/FSvSETEventAuthorAdd';
$route ['ConSettingGenaralEventDelete']             = 'interface/connectionsetting/cConSettingGenaral/FSaSETDeleteEvent';

//Check Import List
$route['masChkImport/(:any)/(:any)']                = 'interface/interfacecheckimport/cInterfaceCheckImport/index/$1/$2';
$route['masChkImportList']                          = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkListPage';
$route['masChkImportFine']                          = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportSelectPage';
$route['maspageChkImportList']                      = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportList';
$route['maspageChkImportCostCenterToProfiCenter']   = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkCostCenterToProfiCenter';
$route['maspageChkImportInterBA']                   = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkInterBA';
$route['maspageChkImportSaleStaff']                 = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkSaleStaff';
$route['maspageChkImportCustomer']                  = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkCustomer';
$route['maspageChkImportRole']                      = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkRole';
$route['maspageChkImportSaleforStore']              = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkSaleForStore';
$route['maspageChkImportCar']                       = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportCar';
$route['maspageChkImportProducts']                  = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProducts';
$route['maspageChkImportProductGroup']              = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProductGroup';
$route['maspageChkImportProductDept']               = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProductDept';
$route['maspageChkImportUnitSmall']                 = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProductUnitSmalls';
$route['maspageChkImportProductComponent']          = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProductComponent';
$route['maspageChkImportProductPrice']              = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportProductPrice';
$route['maspageChkImportSelectPage']                = 'interface/interfacecheckimport/cInterfaceCheckImport/FSvCChkImportSelectPage';
