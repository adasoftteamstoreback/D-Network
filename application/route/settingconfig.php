<?php

// ตั้งค่าระบบ
$route['SettingConfig/(:any)/(:any)']      = 'settingconfig/settingconfig/cSettingconfig/index/$1/$2';
$route['SettingConfigGetList']             = 'settingconfig/settingconfig/cSettingconfig/FSvSETGetPageList';

//Content ในตั้งค่าระบบ
$route['SettingConfigLoadViewSearch']      = 'settingconfig/settingconfig/cSettingconfig/FSvSETGetPageListSearch';
$route['SettingConfigLoadTable']           = 'settingconfig/settingconfig/cSettingconfig/FSvSETSettingGetTable';
$route['SettingConfigSave']                = 'settingconfig/settingconfig/cSettingconfig/FSxSETSettingEventSave';
$route['SettingConfigUseDefaultValue']     = 'settingconfig/settingconfig/cSettingconfig/FSxSETSettingUseDefaultValue';

//Content รหัสอัตโนมัติ
$route['SettingAutonumberLoadViewSearch']  = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberGetPageListSearch';
$route['SettingAutonumberLoadTable']       = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberSettingGetTable';
$route['SettingAutonumberLoadPageEdit']    = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberPageEdit';
$route['SettingAutonumberSave']            = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberEventSave';

//กำหนดเงื่อนไขส่วนลด
$route['discountpolicy/(:any)/(:any)']       = 'settingconfig/discountpolicy/cDiscountpolicy/index/$1/$2';
$route['discountpolicyList']                 = 'settingconfig/discountpolicy/cDiscountpolicy/FSvDPCDisPageList';
$route['discountpolicyLoadTable']            = 'settingconfig/discountpolicy/cDiscountpolicy/FSvDPCDisGetdataTable';
$route['discountpolicySaveData']             = 'settingconfig/discountpolicy/cDiscountpolicy/FSvDPCDisSaveData';

//CompanySetingConnection (ตั้งค่าการเชื่อมต่อ API)
$route['CompSettingCon/(:any)/(:any)']      = 'settingconfig/compsetting/cCompSetting/index/$1/$2';
$route['CompSettingConfigGetList']          = 'settingconfig/compsetting/cCompSetting/FSvSETCompGetPageList';
$route['CompSettingConfigLoadViewSearch']   = 'settingconfig/compsetting/cCompSetting/FSvSETCompGetPageListSearch';
$route['CompSettingDataTable']              = 'company/compsettingconnection/cCompSettingConnection/FSvCCompConnectDataList';

//ตั้งค่าเมนู
$route['settingmenu/(:any)/(:any)']          = 'settingconfig/settingmenu/cSettingmenu/index/$1/$2';
$route['SettingMenuGetPage']                 = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUGetPageSettingmenu';

//Module
$route['SettingMenuAddEditModule']           = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditModule';
$route['CallModalModulEdit']                 = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditModule';
$route['SettingMenuDelModule']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelModule';
$route['CheckDupSeq']                        = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCheckDupSeq';

//MenuGrp
$route['SettingMenuAddEditMenuGrp']          = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditMenuGrp';
$route['CallModalMenuGrpEdit']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditMenuGrp';
$route['SettingMenuDelMenuGrp']              = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelMenuGrp';


//MenuList
$route['SettingMenuAddEditMenuList']          = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditMenuList';
$route['CallModalMenuListEdit']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditMenuList';
$route['SettingMenuDelMenuList']              = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelMenuList';

//StaUse
$route['UpdateStaUse']                        = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUUpdateStaUse';
$route['CallMaxValueSequence']                = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallMaxSequence';

//Report
$route['SettingReportGetPage']                = 'settingconfig/settingmenu/cSettingreport/FSxCSRTGetPageSettingreport';
$route['CallMaxValueSequenceRpt']             = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallMaxSequence';
$route['GenCodeRpt']                          = 'settingconfig/settingmenu/cSettingreport/FSxCSRTGencode';
$route['CallMaxValueSequenceAndRptCode']      = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallMaxSequence';

//Module Rpt
$route['SettingReportAddUpdateModule']       = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportAddUpdateModule';
$route['SettingReportCallEditModuleRpt']     = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportCallMoalEditModulRpt';
$route['SettingReportDelModule']             = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelModuleReport';

//ReportGrp
$route['SettingReportAddEditRptGrp']           = 'settingconfig/settingmenu/cSettingreport/FSxCSRTAddEditRptGrp';
$route['CallModalReportGrpEdit']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallModalEditRptGrp';
$route['SettingReportDelRptGrp']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelReportGrp';

//ReportMenu
$route['SettingReportAddEditRptMenu']           = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportAddUpdateMenu';
$route['CallModalReportMenuEdit']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallModalEditRptMenu';
$route['SettingReportDelMenu']                  = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelMenuReport';

// กำหนดเงื่อนไขช่วงการตรวจสอบ
$route['settingconperiod/(:any)/(:any)']       = 'settingconfig/settingconperiod/cSettingconperiod/index/$1/$2';
$route['settingconperiodList']                 = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMListPage';
$route['settingconperiodDataTable']            = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMDataList';
$route['settingconperiodPageAdd']              = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMAddPage';
$route['settingconperiodDataCheckRolCode']     = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMChkRole';
$route['settingconperiodPageEdit']             = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMEditPage';
$route['settingconperiodEventDelete']          = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMDeleteEvent';
$route['settingconperiodEventDeleteMultiple']  = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMDeleteMultiEvent';
$route['settingconperiodEventAdd']             = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMAddEvent';
$route['settingconperiodEventEdit']            = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMEditEvent';

//Export Data Settingconfig
$route['configExportData']                     = 'settingconfig/settingconfig/cSettingconfig/FSxSETSettingConfigExport';
$route['configInsertData']                     = 'settingconfig/settingconfig/cSettingconfig/FSxSETConfigInsertData';

//การสำรองข้อมูลและการล้างข้อมูล
$route['BAC/(:any)/(:any)']                    = 'settingconfig/backupandcleardata/backupandcleardata_controller/index/$1/$2';
$route['BACList']                              = 'settingconfig/backupandcleardata/backupandcleardata_controller/FSvBACListPage';
$route['BACDataTable']                         = 'settingconfig/backupandcleardata/backupandcleardata_controller/FSvBACDataList';
$route['BACEditPage']                          = 'settingconfig/backupandcleardata/backupandcleardata_controller/FSvBACEditPage';
$route['BACEditEvent']                         = 'settingconfig/backupandcleardata/backupandcleardata_controller/FSvBACEditEvent';

//Log monitor
$route['monLog/(:any)/(:any)']                    = 'settingconfig/logmonitor/logmonitor_controller/index/$1/$2';
$route['monLogList']                              = 'settingconfig/logmonitor/logmonitor_controller/FSvLOGListPage';
$route['monLogDataTable']                         = 'settingconfig/logmonitor/logmonitor_controller/FSvLOGDataList';
$route['monLogDataTableWebView']                  = 'settingconfig/logmonitor/logmonitor_controller/FSvLOGDataListWebView';
$route['monLogSendMQ']                            = 'settingconfig/logmonitor/logmonitor_controller/FSoCLOGPackDataToLogMQ';
$route['monLogExportExcel']                       = 'settingconfig/logmonitor/logmonitor_controller/FSoCLOGRenderExcel';
