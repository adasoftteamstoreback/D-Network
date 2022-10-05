<?php
//พี่รัตน์บอกให้ใช้ route sale (8 ตุลาคม 2562)

//ออกใบกำกับภาษีเต็มรูป
$route['TaxinvoiceABB/(:any)/(:any)']          = 'sale/Taxinvoice/cTaxinvoice/index/$1/$2';
$route['TaxinvoiceABBList']                    = 'sale/Taxinvoice/cTaxinvoice/FSvCTAXListPage';
$route['TaxinvoiceABBTable']                   = 'sale/Taxinvoice/cTaxinvoice/FSvCTAXDataTable';

// พิมพ์เอกสาร EJ
$route['dcmReprintEJ/(:any)/(:any)']            = 'sale/reprintej/cReprintEJ/index/$1/$2';
$route['dcmReprintEJCallPageMainFormPrint']     = 'sale/reprintej/cReprintEJ/FSvCEJCallPageMainFormPrint';
$route['dcmReprintEJFilterDataABB']             = 'sale/reprintej/cReprintEJ/FSoCEJGetDataAbbInDB';
$route['dcmReprintEJCallPageRenderPrintABB']    = 'sale/reprintej/cReprintEJ/FSoCEJCallPageRenderPrintABB';

// จองช่องฝากของ
$route['salBookingLocker/(:any)/(:any)']       = 'sale/bookinglocker/cBookingLocker/index/$1/$2';
$route['salBookingLockerPageMain']             = 'sale/bookinglocker/cBookingLocker/FSvCBKLCallPageMain';
$route['salBookingLockerGetViewRack']          = 'sale/bookinglocker/cBookingLocker/FSvCBKLGetViewRack';
$route['salBookingLockerGetModalBooking']      = 'sale/bookinglocker/cBookingLocker/FSvCBKLGetViewBooking';
$route['salBookingLockerConfirmBookingLocker'] = 'sale/bookinglocker/cBookingLocker/FSoCBKLConfirmBookingLocker';
$route['salBookingLockerCancelBookingLocker']  = 'sale/bookinglocker/cBookingLocker/FSoCBKLCancelBookingLocker';
$route['salBookingLockerDeleteQueues']         = 'sale/bookinglocker/cBookingLocker/FSoCBKLDeleteQueue';

// Dash Board Sale
$route['dashboardsale/(:any)/(:any)']          = 'sale/dashboardsale/cDashBoardSale/index/$1/$2';
$route['dashboardsaleMainPage']                = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALMainPage';
$route['dashboardsaleCallModalFilter']         = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalFilter';
$route['dashboardsaleConfirmFilter']           = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALConfirmFilter';
$route['dashboardsaleBillAllAndTotalSale']     = 'sale/dashboardsale/cDashBoardSale/FSoCDSHSALViewBillAllAndTotalSale';
$route['dashboardsaleTotalSaleByRecive']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByRecive';
$route['dashboardsalePdtStockBarlance']        = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewPdtStockBarlance';
$route['dashboardsaleTopTenNewPdt']            = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenNewPdt';
$route['dashboardsaleTotalSaleByPdtGrp']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByPdtGrp';
$route['dashboardsaleTotalSaleByPdtPty']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByPdtPty';
$route['dashboardsaleTopTenBestSeller']        = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenBestSaller';
$route['dashboardsaleTotalByBranch']           = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalByBranch';
$route['dashboardsaleTopTenBestSellerByValue'] = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenBestSallerByValue';

$route['dashboardRptCompareSaleByPdtQty']      = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewCompareSaleByPdtQty';
$route['dashboardRptCompareSaleByPdtAmt']      = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewCompareSaleByPdtAmt';





// Dash Board Modal Config
$route['dashboardsaleCallModalConfigPage']     = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalConfigPage';
$route['dashboardsaleCallModalConfigPageSaveCookie']     = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalConfigPageSaveCookie';

// Dash Board Sale
$route['salemonitor/(:any)/(:any)']          = 'sale/salemonitor/cSaleMonitor/index/$1/$2';
$route['salemonitorMainPage']                = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALMainPage';
$route['salemonitorCallModalFilter']         = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALCallModalFilter';
$route['salemonitorConfirmFilter']           = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALConfirmFilter';
$route['salemonitorCallSaleDataTable']       = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallSaleDataTable';
$route['salemonitorCallApiDataTable']        = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallApiDataTable';
$route['salemonitorCallMQRequestSaleData']   = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallMQRequestSaleData';
$route['salemonitorCallMQRequestApiData']    = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallMQRequestApiData';
$route['salemonitorRequestAPIInOnLine']      = 'sale/salemonitor/cSaleMonitor/FSaCSMTRequestAPIIsOnLine';

// Dash Board Sale
$route['dashboardsaleTable/(:any)/(:any)']     = 'sale/dashboardsale/cDashBoardSaleTable/index/$1/$2';
$route['dashboardsaleTableMainPage']           = 'sale/dashboardsale/cDashBoardSaleTable/FSvCDSHSALMainPage';
$route['dashboardsaleTableCallModalFilter']    = 'sale/dashboardsale/cDashBoardSaleTable/FSvCDSHSALCallModalFilter';
$route['dashboardsaleTableConfirmFilter']      = 'sale/dashboardsale/cDashBoardSaleTable/FSvCDSHSALConfirmFilter';
$route['dashboardsaleTableTotalByBranch']      = 'sale/dashboardsale/cDashBoardSaleTable/FSvCDSHSALViewTotalByBranch';

// Create By : Napat(Jame) 17/09/2020
$route['docPTU/(:any)/(:any)']                  = 'sale/promotiontopup/cPromotionTopup/index/$1/$2';
$route['docPTUPageList']                        = 'sale/promotiontopup/cPromotionTopup/FSxCPTUPageList';
$route['docPTUPageDataTable']                   = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageDataTable';
$route['docPTUEventDeleteDoc']                  = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventDeleteDoc';
$route['docPTUEventAdd']                        = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventAdd';
$route['docPTUPageAdd']                         = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageAdd';
$route['docPTUPageEdit']                        = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageEdit';
$route['docPTUEventEdit']                       = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventEdit';
$route['docPTUEventCancelDoc']                  = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventCancelDoc';
$route['docPTUEventApproveDoc']                 = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventApproveDoc';
$route['docPTUEventStepDelete']                 = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventStepDelete';
$route['docPTUPageStep1DataTable']              = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageStep1DataTable';
$route['docPTUEventStep1AddEditCardType']       = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventStep1AddEditCardType';
$route['docPTUPageStep2DataTable']              = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageStep2DataTable';
$route['docPTUEventStep2AddRow']                = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventStep2AddRow';
$route['docPTUEventStep2EditInline']            = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventStep2EditInline';
$route['docPTUPageStep3DataTable']              = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageStep3DataTable';
$route['docPTUEventStep3AddEditHDBch']          = 'sale/promotiontopup/cPromotionTopup/FSaCPTUEventStep3AddEditHDBch';
$route['docPTUPageStep4CheckAndConfirm']        = 'sale/promotiontopup/cPromotionTopup/FSvCPTUPageStep4CheckAndConfirm';
$route['docPTUEventChangeBchInTemp']            = 'sale/promotiontopup/cPromotionTopup/FSxCPTUEventChangeBchInTemp';

// MQ Information
$route['dasMQICallMianPage']                 = 'sale/salemonitor/cMqInfomation/FSvMQICallMainPage';
$route['dasMQICallDataTable']                = 'sale/salemonitor/cMqInfomation/FSvMQICallDataTable';
$route['dasMQIEventReConsumer']              = 'sale/salemonitor/cMqInfomation/FSvMQIEventReConsumer';

// Sale Tools
$route['dasSTLCallMianPage']                 = 'sale/salemonitor/cSaleTools/FSvSTLCallMainPage';
$route['dasSTLCallDataTable']                = 'sale/salemonitor/cSaleTools/FSvSTLCallDataTable';
$route['dasSTLEventRepair']                  = 'sale/salemonitor/cSaleTools/FSvSTLEventRePair';

// Sale Import
$route['dasIMPCallMianPage']                 = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallMianPage';
$route['dasIMPCallPageFrom']                 = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallPageFrom';
$route['dasIMPCallDataTable']                = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallDataTable';
$route['dasIMPUploadFile']                   = 'sale/salemonitor/cSaleImportBill/FSaCIMPUploadFile';
$route['dasIMPLoadDatatable']                = 'sale/salemonitor/cSaleImportBill/FSvCIMPLoadDatatable';
$route['dasIMPInsertBillData']               = 'sale/salemonitor/cSaleImportBill/FSaCIMPInsertBillData';

$route['dasDOV/(:any)/(:any)']                = 'sale/dashboardoverview/cDashboardOverview/index/$1/$2';
$route['dasDOVPageChart']                     = 'sale/dashboardoverview/cDashboardOverview/FSvCDOVPageChart';
$route['dasDOVEventCheckLastData']            = 'sale/dashboardoverview/cDashboardOverview/FStCDOVEventCheckLastData';

$route['dasBCM']                              = 'sale/bluecardmonitor/Bluecardmonitor_controller/index';
$route['dasBCMCallPageBatch']                 = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCallPageBatch';
$route['dasBCMCallBatchDataTable']            = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCallDataTable';
$route['dasBCMCallPageStand']                 = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCallPageStand';
$route['dasBCMCallPageStandDataTable']        = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCallStandDataTable';
$route['dasBCMExportBatch']                   = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCExportBatch';
$route['dasBCMExportStand']                   = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCExportStand';
$route['dasBCMCallMQRequestSaleData']         = 'sale/bluecardmonitor/Bluecardmonitor_controller/FSvCBCMCallMQRequestSaleData';
