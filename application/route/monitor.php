<?php
//defined ('BASEPATH') or exit ('No direct script access allowed');
$route['monSPL/(:any)/(:any)']  = 'monitor/monitor/Monitor_controller/index/$1/$2';
$route['monList']               = 'monitor/monitor/Monitor_controller/FSvCMONListPage';
$route['monListSearchData']     = 'monitor/monitor/Monitor_controller/FSvCMONSearchData';
$route['monListSuplDetail']     = 'monitor/monitor/Monitor_controller/FSvCMONSearchDataSuplDetail';
$route['monExportExcel']        = 'monitor/monitor/Monitor_controller/FSvCMONExportExcel';


$route['monPAP/(:any)/(:any)']     = 'monitor/productaliveatpurchase/Productaliveatpurchase_controller/index/$1/$2';
$route['monPAPList']               = 'monitor/productaliveatpurchase/Productaliveatpurchase_controller/FSvCPAPListPage';
$route['monPAPGetSplBuyList']      = 'monitor/productaliveatpurchase/Productaliveatpurchase_controller/FSvCPAPGetSplBuyList';
$route['monPAPExportExcel']        = 'monitor/productaliveatpurchase/Productaliveatpurchase_controller/FSvCPAPExportExcel';

//ตรวจสอบสถานะลูกหนี้ค้างชำระ
$route['monDelay/(:any)/(:any)']     = 'monitor/delaystatus/Delaystatus_controller/index/$1/$2';
$route['monDelayList']               = 'monitor/delaystatus/Delaystatus_controller/FSvCDLSListPage';
$route['monDelayListSearchData']     = 'monitor/delaystatus/Delaystatus_controller/FSvCDLSSearchData';
$route['monDelayListSuplDetail']     = 'monitor/delaystatus/Delaystatus_controller/FSvCDLSSearchDataSuplDetail';
$route['monDelayExportExcel']        = 'monitor/delaystatus/Delaystatus_controller/FSvCDLSExportExcel';
?>
