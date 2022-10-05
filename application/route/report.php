<?php

date_default_timezone_set('Asia/Bangkok');

// ** Center Route
$route['rptReport/(:any)/(:any)/(:any)']    = 'report/report/cReport/index/$1/$2/$3';
$route['rptReportMain']                     = 'report/report/cReport/FCNoCRPTViewPageMain';
$route['rptReportCondition']                = 'report/report/cReport/FCNoCRPTViewCondition';
$route['rptReportChkDataInTSysHisExport']   = 'report/report/cReport/FCNoCRPTChkDataInTSysHisExport';
$route['rptReportConfirmDownloadFile']      = 'report/report/cReport/FCNoCRPTConfirmDownloadFile';
$route['rptReportCancelDownloadFile']       = 'report/report/cReport/FCNoCRPTCancelDownloadFile';
$route['rptReportGetBchByAgenCode']         = 'report/report/cReport/FCNtGetBchByAgenCode';

// รายงานยอดขายตามการชำระเงิน
$route['rptRptSaleToPayment']                   = 'report/reportsale/cRptSalePayment/index';
$route['rptRptSaleToPaymentClickPage']          = 'report/reportsale/cRptSalePayment/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleToPaymentCallExportFile']     = "report/reportsale/cRptSalePayment/FSvCCallRptExportFile";

// รายงานยอดขายตามบิล (Pos)
$route['rptRptSaleByBill']                      = "report/reportsale/cRptSaleByBill/index";
$route['rptRptSaleByBillClickPage']             = "report/reportsale/cRptSaleByBill/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptSaleByBillCallExportFile']        = "report/reportsale/cRptSaleByBill/FSvCCallRptExportFile";

// รายงานยอดขายตามสินค้า
$route['rptRptSaleByProduct']                   = "report/reportsale/cRptSaleByProduct/index";
$route['rptRptSaleByProductClickPage']          = "report/reportsale/cRptSaleByProduct/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptSaleByProductCallExportFile']     = "report/reportsale/cRptSaleByProduct/FSvCCallRptExportFile";

// รายงานภาษีขาย (POS)
$route['rptRptTaxSalePos']                      = "report/reportsale/cRptTaxSalePos/index";
$route['rptRptTaxSalePosClickPage']             = "report/reportsale/cRptTaxSalePos/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosCallExportFile']        = "report/reportsale/cRptTaxSalePos/FSvCCallRptExportFile";

// รายงานภาษีขายตามวันที่ (POS)
$route['rptRptTaxSalePosByDate']                = "report/reportsale/cRptTaxSalePosByDate/index";
$route['rptRptTaxSalePosByDateClickPage']       = "report/reportsale/cRptTaxSalePosByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosByDateCallExportFile']  = "report/reportsale/cRptTaxSalePosByDate/FSvCCallRptExportFile";

//รายงานความเคลื่อนไหวสินค้า Pos+VD
$route['rtpMovePosVD']                          = 'report/reportMovePosVD/cRptMovePosVD/index';
$route['rtpMovePosVDClickPage']                 = 'report/reportMovePosVD/cRptMovePosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rtpMovePosVDCallExportFile']            = "report/reportMovePosVD/cRptMovePosVD/FSvCCallRptExportFile";

// รายงานสินค้าคงคลัง (Pos)
$route['rptRptInventoryPos']                    = 'report/reportInventoryPos/cRptInventoryPos/index';
$route['rptRptInventoryPosClickPage']           = 'report/reportInventoryPos/cRptInventoryPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoryPosCallExportFile']      = 'report/reportInventoryPos/cRptInventoryPos/FSvCCallRptExportFile';

// รายงานยอดขายตามการชำระเงินแบบสรุป  (Vending)
$route['rptSalePaymentSummary']                 = 'report/reportsalepaymentsummary/cRptSalePaymentSummary/index';
$route['rptRptSalePaymentSummaryClickPage']     = 'report/reportsalepaymentsummary/cRptSalePaymentSummary/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalePaymentSummaryCallExportFile']   = "report/reportsalepaymentsummary/cRptSalePaymentSummary/FSvCCallRptExportFile";

// รายงานสินค้าคงคลัง
// รายงานการตรวจนับสต็อค (Vending)
$route['rptRptAdjStockVending']                 = 'report/reportstkvd/cRptAdjStockVending/index';
$route['rptRptAdjStockVendingClickPage']        = 'report/reportstkvd/cRptAdjStockVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAdjStockVendingCallExportFile']   = "report/reportstkvd/cRptAdjStockVending/FSvCCallRptExportFile";

// รายงานสินค้าขายดี (Vending)
$route['rptRptBestSaleVending']                 = 'report/reportbestsalevd/cRptBestSaleVending/index';
$route['rptRptBestSaleVendingClickPage']        = 'report/reportbestsalevd/cRptBestSaleVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptBestSaleVendingCallExportFile']   = "report/reportbestsalevd/cRptBestSaleVending/FSvCCallRptExportFile";

// รายงานยอดขายตามการชำระเงินแบบละเอียด (Vending)
$route['rptRptSalePayDetailVending']                = 'report/reportsalerecivevd/cRptSaleReciveVD/index';
$route['rptRptSalePayDetailVendingClickPage']       = 'report/reportsalerecivevd/cRptSaleReciveVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalePayDetailVendingCallExportFile']  = "report/reportsalerecivevd/cRptSaleReciveVD/FSvCCallRptExportFile";

// รายงานเปลี่ยนสถานะช่องฝากขาย
$route['rptRptChangeStaSale']                       = 'report/reportlocker/cRptChangeStaSale/index';
$route['rptRptChangeStaSaleClickPage']              = 'report/reportlocker/cRptChangeStaSale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptChangeStaSaleCallExportFile']         = "report/reportlocker/cRptChangeStaSale/FSvCCallRptExportFile";

// รายงานการเปิดตู้โดยผู้ดูแลระบบ
$route['rptRptOpenSysAdmin']                        = 'report/reportlocker/cRptOpenSysAdmin/index';
$route['rptRptOpenSysAdminClickPage']               = 'report/reportlocker/cRptOpenSysAdmin/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptOpenSysAdminCallExportFile']          = "report/reportlocker/cRptOpenSysAdmin/FSvCCallRptExportFile";

// รายงานภาษีขาย (Locker)
$route['rptRptTaxSaleLocker']                       = 'report/reportlocker/cRptTaxSaleLocker/index';
$route['rptRptTaxSaleLockerClickPage']              = 'report/reportlocker/cRptTaxSaleLocker/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptTaxSaleLockerCallExportFile']         = "report/reportlocker/cRptTaxSaleLocker/FSvCCallRptExportFile";

// รายงานยอดขายตามการชำระเงินแบบละเอียด (Locker)
$route['rptRptSaleByPaymentDetail']                 = 'report/reportlocker/cRptSaleByPaymentDetail/index';
$route['rptRptSaleByPaymentDetailClickPage']        = 'report/reportlocker/cRptSaleByPaymentDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleByPaymentDetailCallExportFile']   = "report/reportlocker/cRptSaleByPaymentDetail/FSvCCallRptExportFile";

// Create By Witsarut 6/12/2019
// รายงานการฝากตามขนาดช่อง
$route['rptDepositAccSlotSize']                     = 'report/reportdepositaccslotsize/cRptDepositAccSlotSize/index';
$route['rptDepositAccSlotSizeClickPage']            = 'report/reportdepositaccslotsize/cRptDepositAccSlotSize/FSvCCallRptViewBeforePrintClickPage';
$route['rptDepositAccSlotSizeCallExportFile']       = "report/reportdepositaccslotsize/cRptDepositAccSlotSize/FSvCCallRptExportFile";

// รายงานยอดฝากตามบริษัทขนส่ง (Locker)
$route['rptRentAmountFollowCourier']                = 'report/reportlocker/cRptRentAmountFolloweCourier/index';
$route['rptRentAmountFollowCourierClickPage']       = 'report/reportlocker/cRptRentAmountFolloweCourier/FSvCCallRptViewBeforePrintClickPage';
$route['rptRentAmountFollowCourierCallExportFile']  = "report/reportlocker/cRptRentAmountFolloweCourier/FSvCCallRptExportFile";

// รายงานยอดฝากแบบละเอียด (Locker)
$route['rptRentAmountDetail']                       = 'report/reportlocker/cRptRentAmountDetail/index';
$route['rptRentAmountDetailClickPage']              = 'report/reportlocker/cRptRentAmountDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptRentAmountDetailCallExportFile']         = "report/reportlocker/cRptRentAmountDetail/FSvCCallRptExportFile";

// Create By Witsarut 03122019
// รายงานการฝากตามช่วงเวลา (Locker)
$route['rptTimeDeposit']                            = 'report/reportlocker/cRptTimeDeposit/index';
$route['rptTimeDepositClickPage']                   = 'report/reportlocker/cRptTimeDeposit/FSvCCallRptViewBeforePrintClickPage';
$route['rptTimeDepositCallExportFile']              = "report/reportlocker/cRptTimeDeposit/FSvCCallRptExportFile";

// รายงานการฝากตามช่วงเวลา แบบละเอียด (Locker)
$route['rptRptLockerDropByDate']                    = 'report/reportlocker/cRptDropByDate/index';
$route['rptRptLockerDropByDateClickPage']           = 'report/reportlocker/cRptDropByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptLockerDropByDateCallExportFile']      = "report/reportlocker/cRptDropByDate/FSvCCallRptExportFile";

// รายงานการรับตามช่วงเวลา แบบละเอียด (Locker)
$route['rptRptLockerPickByDate']                    = 'report/reportlocker/cRptPickByDate/index';
$route['rptRptLockerPickByDateClickPage']           = 'report/reportlocker/cRptPickByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptLockerPickByDateCallExportFile']      = "report/reportlocker/cRptPickByDate/FSvCCallRptExportFile";

// รายงาน - การจองช่องฝากของ
$route['rptRptBookingLocker']                       = 'report/reportlocker/cRptBookingLocker/index';
$route['rptRptBookingLockerClickPage']              = 'report/reportlocker/cRptBookingLocker/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptBookingLockerCallExportFile']         = "report/reportlocker/cRptBookingLocker/FSvCCallRptExportFile";

// รายงาน - สถานะตู้ฝาก
$route['rptLockerStatus']                           = 'report/reportlocker/cRptLockerStatus/index';
$route['rptLockerStatusClickPage']                  = 'report/reportlocker/cRptLockerStatus/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerStatusCallExportFile']             = "report/reportlocker/cRptLockerStatus/FSvCCallRptExportFile";

// รายงาน - การรับของ
$route['rptLockerPick']                           = 'report/reportlocker/cRptLockerPick/index';
$route['rptLockerPickClickPage']                  = 'report/reportlocker/cRptLockerPick/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerPickCallExportFile']             = "report/reportlocker/cRptLockerPick/FSvCCallRptExportFile";

// รายงาน - รับของผ่านตู้
$route['rptLockerReceive']                           = 'report/reportlocker/cRptLockerReceive/index';
$route['rptLockerReceiveClickPage']                  = 'report/reportlocker/cRptLockerReceive/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerReceiveCallExportFile']             = "report/reportlocker/cRptLockerReceive/FSvCCallRptExportFile";

// รายงาน - การจองช่องฝากของ
$route['rptLockerBooking']                           = 'report/reportlocker/cRptLockerBooking/index';
$route['rptLockerBookingClickPage']                  = 'report/reportlocker/cRptLockerBooking/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerBookingCallExportFile']             = "report/reportlocker/cRptLockerBooking/FSvCCallRptExportFile";

// รายงาน - รับโอนสินค้าตาม Lot
$route['rptReceiveLot']                           = 'report/reportlocker/cRptReceiveLot/index';
$route['rptReceiveLotClickPage']                  = 'report/reportlocker/cRptReceiveLot/FSvCCallRptViewBeforePrintClickPage';
$route['rptReceiveLotCallExportFile']             = "report/reportlocker/cRptReceiveLot/FSvCCallRptExportFile";

// รายงาน - ยอดฝากแบบละเอียด
$route['rptLockerDetailDepositAmount']                  = 'report/reportlocker/cRptDetailDepositAmount/index';
$route['rptLockerDetailDepositAmountClickPage']         = 'report/reportlocker/cRptDetailDepositAmount/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerDetailDepositAmountCallExportFile']    = "report/reportlocker/cRptDetailDepositAmount/FSvCCallRptExportFile";

// รายงาน - การชำระเงิน ตามบิล
$route['rptLockerPaymentByBill']                    = 'report/reportlocker/cRptPaymentByBill/index';
$route['rptLockerPaymentByBillClickPage']           = 'report/reportlocker/cRptPaymentByBill/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerPaymentByBillCallExportFile']      = "report/reportlocker/cRptPaymentByBill/FSvCCallRptExportFile";

// รายงาน - การชำระเงิน (New Create By Wasin 09-12-2019)
$route['rptLockerPayment']                          = 'report/reportlocker/cRptLockerPayment/index';
$route['rptLockerPaymentClickPage']                 = 'report/reportlocker/cRptLockerPayment/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerPaymentCallExportFile']            = "report/reportlocker/cRptLockerPayment/FSvCCallRptExportFile";

// รายงานการโอนสินค้า (ตู้ Vending)
$route['rptRptProductTransfer']                     = 'report/reportproducttransfer/cRptProductTransfer/index';
$route['rptRptProductTransferClickPage']            = 'report/reportproducttransfer/cRptProductTransfer/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptProductTransferCallExportFile']       = 'report/reportproducttransfer/cRptProductTransfer/FSvCCallRptExportFile';

// รายงานยอดขายตามการชำระเงินแบบละเอียด (Pos)
$route['rptRptSaleRecive']                          = 'report/reportsale/cRptSaleRecive/index';
$route['rptRptSaleReciveClickPage']                 = 'report/reportsale/cRptSaleRecive/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleReciveCallExportFile']            = 'report/reportsale/cRptSaleRecive/FSvCCallRptExportFile';

// รายงานสินค้าคงคลัง (Vending)
$route['rptRptInventory']                           = 'report/reportInventory/cRptInventory/index';
$route['rptRptInventoryClickPage']                  = 'report/reportInventory/cRptInventory/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoryCallExportFile']             = 'report/reportInventory/cRptInventory/FSvCCallRptExportFile';

// รายงานภาษีขายตามกลุ่มร้านค้า (Vending)
$route['rptRptSaleShopGroup']                       = 'report/reportsaleshopgroup/cRptsaleshopgroup/index';
$route['rptRptSaleShopGroupClickPage']              = 'report/reportsaleshopgroup/cRptsaleshopgroup/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleShopGroupCallExportFile']         = 'report/reportsaleshopgroup/cRptsaleshopgroup/FSvCCallRptExportFile';

// รายงานยอดขายตามบิล (Vending)
$route['rptRptSalesbyBill']                         = 'report/reportSalesbybill/cRptSalesbybill/index';
$route['rptRptSalesbyBillClickPage']                = 'report/reportSalesbybill/cRptSalesbybill/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalesbyBillCallExportFile']           = 'report/reportSalesbybill/cRptSalesbybill/FSvCCallRptExportFile';

// รายงานยอดขายตามสินค้า (Vending)
$route['rptRptSaleByProductVD']                     = 'report/reportSaleByProductVD/cRptSaleByProductVD/index';
$route['rptRptSaleByProductVDClickPage']            = 'report/reportSaleByProductVD/cRptSaleByProductVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleByProductVDCallExportFile']       = 'report/reportSaleByProductVD/cRptSaleByProductVD/FSvCCallRptExportFile';


// รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Vending)
$route['rptRptAnalysisProfitLossProductVending']                = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/index';
$route['rptRptAnalysisProfitLossProductVendingClickPage']       = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAnalysisProfitLossProductVendingCallExportFile']  = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/FSvCCallRptExportFile';


// รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Pos)
$route['rptRptAnalysisProfitLossProductPos']                = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/index';
$route['rptRptAnalysisProfitLossProductPosClickPage']       = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAnalysisProfitLossProductPosCallExportFile']  = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/FSvCCallRptExportFile';

// รายงานยอดขายตามการชำระเงิน Locker
$route['rptRptLocToPayment']                        = 'report/reportlocker/cRptLocPayment/index';
$route['rptRptLocToPaymentCallExportFile']          = 'report/reportlocker/cRptLocPayment/FSvCCallRptExportFile';

// รายงาน - สินค้าขายดีตามจำนวน
$route['rptBestSell']                               = 'report/rptbestsell/cRptBestSell/index';
$route['rptBestSellClickPage']                      = 'report/rptbestsell/cRptBestSell/FSvCCallRptViewBeforePrintClickPage';
$route['rptBestSellCallExportFile']                 = 'report/rptbestsell/cRptBestSell/FSvCCallRptExportFile';

// รายงาน - สินค้าขายดีตามมูลค่า
$route['rptBestSellByValue']                        = 'report/rptbestsellbyvalue/cRptBestSellByValue/index';
$route['rptBestSellByValueClickPage']               = 'report/rptbestsellbyvalue/cRptBestSellByValue/FSvCCallRptViewBeforePrintClickPage';
$route['rptBestSellByValueCallExportFile']          = 'report/rptbestsellbyvalue/cRptBestSellByValue/FSvCCallRptExportFile';

// 1. รายงานข้อมูลการใช้บัตร 004001001 rptCrdUseCard1
$route['rptCrdUseCard1']                            = 'report/reportcard/cRptUseCard1/index';
$route['rptCrdUseCard1ClickPage']                   = 'report/reportcard/cRptUseCard1/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdUseCard1CallExportFile']              = 'report/reportcard/cRptUseCard1/FSvCCallRptExportFile';

// 2. รายงานตรวจสอบสถานะบัตร 004001002 rptCrdCheckStatusCard
$route['rptCrdCheckStatusCard']                     = 'report/reportcard/cRptCheckStatusCard/index';
$route['rptCrdCheckStatusCardClickPage']            = 'report/reportcard/cRptCheckStatusCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckStatusCardCallExportFile']       = 'report/reportcard/cRptCheckStatusCard/FSvCCallRptExportFile';

// 3. รายงานโอนข้อมูลบัตร 004001003 rptCrdTransferCardInfo
$route['rptCrdTransferCardInfo']                    = 'report/reportcard/cRptTransferCardInfo/index';
$route['rptCrdTransferCardInfoClickPage']           = 'report/reportcard/cRptTransferCardInfo/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdTransferCardInfoCallExportFile']      = 'report/reportcard/cRptTransferCardInfo/FSvCCallRptExportFile';

// 4. รายงานการปรับมูลค่าเงินสดในบัตร 004001004 rptCrdAdjustCashInCard
$route['rptCrdAdjustCashInCard']                    = 'report/reportcard/cRptAdjustCashInCard/index';
$route['rptCrdAdjustCashInCardClickPage']           = 'report/reportcard/cRptAdjustCashInCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdAdjustCashInCardCallExportFile']      = 'report/reportcard/cRptAdjustCashInCard/FSvCCallRptExportFile';

// 5. รายงานการล้างมูลค่าบัตรเพื่อกลับมาใช้งานใหม่ 004001005 rptCrdClearCardValueForReuse
$route['rptCrdClearCardValueForReuse']              = 'report/reportcard/cRptClearCardValueForReuse/index';
$route['rptCrdClearCardValueForReuseClickPage']     = 'report/reportcard/cRptClearCardValueForReuse/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdClearCardValueForReuseCallExportFile'] = 'report/reportcard/cRptClearCardValueForReuse/FSvCCallRptExportFile';

// 6. รายงานการลบข้อมูลบัตรที่ไม่ใช้งาน 004001006 rptCrdCardNoActive
$route['rptCrdCardNoActive']                        = 'report/reportcard/cRptCardNoActive/index';
$route['rptCrdCardNoActiveClickPage']               = 'report/reportcard/cRptCardNoActive/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardNoActiveCallExportFile']          = 'report/reportcard/cRptCardNoActive/FSvCCallRptExportFile';

// 7. รายงานจำนวนรอบการใช้บัตร 004001007 rptCrdCardTimesUsed
$route['rptCrdCardTimesUsed']                       = 'report/reportcard/cRptCardTimesUsed/index';
$route['rptCrdCardTimesUsedClickPage']              = 'report/reportcard/cRptCardTimesUsed/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardTimesUsedCallExportFile']         = 'report/reportcard/cRptCardTimesUsed/FSvCCallRptExportFile';

// 8. รายงานบัตรคงเหลือ 004001008 rptCrdCardBalance
$route['rptCrdCardBalance']                         = 'report/reportcard/cRptCardBalance/index';
$route['rptCrdCardBalanceClickPage']                = 'report/reportcard/cRptCardBalance/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardBalanceCallExportFile']           = 'report/reportcard/cRptCardBalance/FSvCCallRptExportFile';

// 9. รายงานยอดสะสมบัตรหมดอายุ 004001009 rptCrdCollectExpireCard
$route['rptCrdCollectExpireCard']                   = 'report/reportcard/cRptCollectExpireCard/index';
$route['rptCrdCollectExpireCardClickPage']          = 'report/reportcard/cRptCollectExpireCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCollectExpireCardCallExportFile']     = 'report/reportcard/cRptCollectExpireCard/FSvCCallRptExportFile';

// 10. รายงานรายการต้นงวดบัตรและเงินสด 004001010 rptCrdPrinciple
$route['rptCrdCardPrinciple']                       = 'report/reportcard/cRptCardPrinciple/index';
$route['rptCrdCardPrincipleClickPage']              = 'report/reportcard/cRptCardPrinciple/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardPrincipleCallExportFile']         = 'report/reportcard/cRptCardPrinciple/FSvCCallRptExportFile';

// 11. รายงานข้อมูลบัตร 004001011 rptCrdCardDetail
$route['rptCrdCardDetail']                          = 'report/reportcard/cRptCardDetail/index';
$route['rptCrdCardDetailClickPage']                 = 'report/reportcard/cRptCardDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardDetailCallExportFile']            = 'report/reportcard/cRptCardDetail/FSvCCallRptExportFile';

// 12. รายงานตรวจสอบการเติมเงิน 004001012 rptCrdCheckPrepaid
$route['rptCrdCheckPrepaid']                        = 'report/reportcard/cRptCheckPrepaid/index';
$route['rptCrdCheckPrepaidClickPage']               = 'report/reportcard/cRptCheckPrepaid/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckPrepaidCallExportFile']          = 'report/reportcard/cRptCheckPrepaid/FSvCCallRptExportFile';

// 13. รายงานตรวจสอบข้อมูลการใช้บัตร 004001013 rptCrdCheckCardUseInfo
$route['rptCrdCheckCardUseInfo']                    = 'report/reportcard/cRptCheckCardUseInfo/index';
$route['rptCrdCheckCardUseInfoClickPage']           = 'report/reportcard/cRptCheckCardUseInfo/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckCardUseInfoCallExportFile']      = 'report/reportcard/cRptCheckCardUseInfo/FSvCCallRptExportFile';

// 14. รายงานการเติมเงิน 004001014 rptCrdTopUp
$route['rptCrdTopUp']                               = 'report/reportcard/cRptTopUp/index';
$route['rptCrdTopUpClickPage']                      = 'report/reportcard/cRptTopUp/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdTopUpCallExportFile']                 = 'report/reportcard/cRptTopUp/FSvCCallRptExportFile';

// 15. รายงานข้อมูลการใช้บัตร 004001015 (แบบละเอียด) rptCrdUseCard2
$route['rptCrdUseCard2']                            = 'report/reportcard/cRptUseCard2/index';
$route['rptCrdUseCard2ClickPage']                   = 'report/reportcard/cRptUseCard2/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdUseCard2CallExportFile']              = 'report/reportcard/cRptUseCard2/FSvCCallRptExportFile';

// 1. รายงานยอดขายร้านค้า-ตามวันที่ 005001001 rptSaleShopByDate
$route['rptSaleShopByDate']                         = 'report/reportanalysis/cRptSaleShopByDate/index';
$route['rptSaleShopByDateClickPage']                = 'report/reportanalysis/cRptSaleShopByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleShopByDateCallExportFile']           = 'report/reportanalysis/cRptSaleShopByDate/FSvCCallRptExportFile';

// 2. รายงานยอดขายร้านค้า-ตามร้านค้า 005001002 rptSaleShopByShop
$route['rptSaleShopByShop']                         = 'report/reportanalysis/cRptSaleShopByShop/index';
$route['rptSaleShopByShopClickPage']                = 'report/reportanalysis/cRptSaleShopByShop/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleShopByShopCallExportFile']           = 'report/reportanalysis/cRptSaleShopByShop/FSvCCallRptExportFile';

// 3. รายงานการเคลื่อนไหวบัตร-แบบสรุป 005001003 rptCrdCardActiveSummary
// 4. รายงานการเคลื่อนไหวบัตร-แบบละเอียด 005001004 rptCrdCardActiveDetail
// 5. รายงานสรุปยอดเงินคงเหลือบัตรไม่ได้แลกคืน 005001005 rptCrdUnExchangeBalance

// = รายงาน - การฝากที่ยังไม่มารับ (Locker)
$route['rptRptDepositsNotPicked']                     = 'report/reportlocker/cRptDepositsNotPicked/index';
$route['rptRptDepositsNotPickedClickPage']            = 'report/reportlocker/cRptDepositsNotPicked/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDepositsNotPickedCallExportFile']       = 'report/reportlocker/cRptDepositsNotPicked/FSvCCallRptExportFile';

// รายงาน - การรับตามช่วงเวลา (Locker)
$route['rptRptRecePtionByTime']                       = 'report/reportlocker/cRptRecePtionByTime/index';
$route['rptRptRecePtionByTimeClickPage']              = 'report/reportlocker/cRptRecePtionByTime/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptRecePtionByTimeCallExportFile']         = 'report/reportlocker/cRptRecePtionByTime/FSvCCallRptExportFile';

// รายงาน - การรับ-ฝากแบบละเอียด (Locker)
$route['rptRptDetailReceiveDeposit']                  = 'report/reportlocker/cRptDetailReceiveDeposit/index';
$route['rptRptDetailReceiveDepositClickPage']         = 'report/reportlocker/cRptDetailReceiveDeposit/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDetailReceiveDepositCallExportFile']    = 'report/reportlocker/cRptDetailReceiveDeposit/FSvCCallRptExportFile';

// กลุ่มรายงาน พิเศษ
$route['rptCRSaleTaxByWeekly']                        = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/index';
$route['rptCRSaleTaxByWeeklyClickPage']               = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/FSvCCallRptViewBeforePrintClickPage';
$route['rptCRSaleTaxByWeeklyCallExportFile']          = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/FSvCCallRptExportFile';

// รายงานยอดขาย (Pos Service)
$route['rptRptCrSale']                                = 'report/reportsalespecial/cRptCrSale/index';
$route['rptRptCrSaleClickPage']                       = 'report/reportsalespecial/cRptCrSale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleCallExportFile']                  = 'report/reportsalespecial/cRptCrSale/FSvCCallRptExportFile';

// รายงาน - ยอดขาย (POS+VD)
$route['rptRptSalePosVD']                             = 'report/reportsalespecial/cRptSalePosVD/index';
$route['rptRptSalePosVDClickPage']                    = 'report/reportsalespecial/cRptSalePosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalePosVDCallExportFile']               = 'report/reportsalespecial/cRptSalePosVD/FSvCCallRptExportFile';

// รายงาน - รายงานยอดขายผลิตภัณฑ์ของวัน (POS Vending)
$route['rptRptCrSaleProductByDay']                    = 'report/reportsalespecial/cRptSaleProductByDay/index';
$route['rptRptCrSaleProductByDayClickPage']           = 'report/reportsalespecial/cRptSaleProductByDay/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleProductByDayCallExportFile']      = 'report/reportsalespecial/cRptSaleProductByDay/FSvCCallRptExportFile';

// รายงาน - รายงานยอดขายผลิตภัณฑ์ของเดือน (POS Vending)
$route['rptRptCrSaleProductByMonth']                  = 'report/reportsalespecial/cRptSaleProductByMonth/index';
$route['rptRptCrSaleProductByMonthClickPage']         = 'report/reportsalespecial/cRptSaleProductByMonth/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleProductByMonthCallExportFile']    = 'report/reportsalespecial/cRptSaleProductByMonth/FSvCCallRptExportFile';

// รายงานภาษีขาย (วัน)
$route['rptDailySalesTax']                            = 'report/reportsalespecial/cRptDailySalesTax/index';
$route['rptDailySalesTaxClickPage']                   = 'report/reportsalespecial/cRptDailySalesTax/FSvCCallRptViewBeforePrintClickPage';
$route['rptDailySalesTaxCallExportFile']              = 'report/reportsalespecial/cRptDailySalesTax/FSvCCallRptExportFile';

// รายงานภาษีขาย (รายเดือน)
$route['rptRptSpecialSaleTaxByMonthly']               = 'report/reportsalespecial/cRptSaleTaxByMonthly/index';
$route['rptRptSpecialSaleTaxByMonthlyClickPage']      = 'report/reportsalespecial/cRptSaleTaxByMonthly/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSpecialSaleTaxByMonthlyCallExportFile'] = 'report/reportsalespecial/cRptSaleTaxByMonthly/FSvCCallRptExportFile';

// รายงานยอดขายผลิตภัณฑ์ของสัปดาห์ (POS Vending)
$route['rptProductSaleOfWeek']                        = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/index";
$route['rptProductSaleOfWeekClickPage']               = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/FSvCCallRptViewBeforePrintClickPage";
$route['rptProductSaleOfWeekCallExportFile']          = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/FSvCCallRptExportFile";

//  (CR) รายงานยอดขายรายวัน (POS Service)
$route['rptRptDailySalesPosSv']                       = 'report/reportsalespecial/cRptDailySalesPosSv/index';
$route['rptRptDailySalesPosSvClickPage']              = 'report/reportsalespecial/cRptDailySalesPosSv/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDailySalesPosSvCallExportFile']         = 'report/reportsalespecial/cRptDailySalesPosSv/FSvCCallRptExportFile';

//  (CR) รายงานยอดขายรายสัปดาห์ (POS Service)
$route['rptRptWeeklySale']                            = 'report/reportsalespecial/cRptWeeklySale/index';
$route['rptRptWeeklySaleClickPage']                   = 'report/reportsalespecial/cRptWeeklySale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptWeeklySaleCallExportFile']              = 'report/reportsalespecial/cRptWeeklySale/FSvCCallRptExportFile';

//  รายงานยอดขายรายเดือน (Pos Service)
$route['rptRptCrSaleMonth']                           = 'report/reportsalespecial/cRptCrSaleMonth/index';
$route['rptRptCrSaleMonthClickPage']                  = 'report/reportsalespecial/cRptCrSaleMonth/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleMonthCallExportFile']             = 'report/reportsalespecial/cRptCrSaleMonth/FSvCCallRptExportFile';

//  (CR) รายงานยอดขายผลิตภัณฑ์ (POS Vending) (แบบรายละเอียดรายวัน)
$route['rptProductSalesPosVD']                        = 'report/reportsalespecial/cRptProductSalesPosVD/index';
$route['rptProductSalesPosVDClickPage']               = 'report/reportsalespecial/cRptProductSalesPosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductSalesPosVDCallExportFile']          = 'report/reportsalespecial/cRptProductSalesPosVD/FSvCCallRptExportFile';

//  รายงานการนำฝากแบบละเอียด สาขา
$route['rptBankDepositBch']                           = 'report/reportlocker/cRptBankDepositBch/index';
$route['rptBankDepositBchClickPage']                  = 'report/reportlocker/cRptBankDepositBch/FSvCCallRptViewBeforePrintClickPage';
$route['rptBankDepositBchCallExportFile']             = 'report/reportlocker/cRptBankDepositBch/FSvCCallRptExportFile';

//  รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ (ประจำวัน)
$route['rptMnyShotOver']                              = 'report/reportsale/cRptMnyShotOver/index';
$route['rptMnyShotOverClickPage']                     = 'report/reportsale/cRptMnyShotOver/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverCallExportFile']                = 'report/reportsale/cRptMnyShotOver/FSvCCallRptExportFile';

//  รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ ประจำวัน(ละเอียด)
$route['rptMnyShotOverDairy']                         = 'report/reportsale/cRptMnyShotOverDaily/index';
$route['rptMnyShotOverDairyClickPage']                = 'report/reportsale/cRptMnyShotOverDaily/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverDairyCallExportFile']           = 'report/reportsale/cRptMnyShotOverDaily/FSvCCallRptExportFile';

//  รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ รายเดือน(ละเอียด)
$route['rptMnyShotOverMonthly']                       = 'report/reportsale/cRptMnyShotOverMonthly/index';
$route['rptMnyShotOverMonthlyClickPage']              = 'report/reportsale/cRptMnyShotOverMonthly/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverMonthlyCallExportFile']         = 'report/reportsale/cRptMnyShotOverMonthly/FSvCCallRptExportFile';

//  รายงานยอดขาย - ตามจุดขาย
$route['rptsaledailybypos']                         = 'report/rptsaledailybypos/cRptSaleDailyByPos/index';
$route['rptsaledailybyposClickPage']                = 'report/rptsaledailybypos/cRptSaleDailyByPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptsaledailybyposCallExportFile']           = 'report/rptsaledailybypos/cRptSaleDailyByPos/FSvCCallRptExportFile';

//  รายงานยอดขาย - ตามแคชเชียร์
$route['rptSalesDailyByCashier']                    = 'report/reportsale/cRptSalesDailyByCashier/index';
$route['rptSalesDailyByCashierClickPage']           = 'report/reportsale/cRptSalesDailyByCashier/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalesDailyByCashierCallExportFile']      = 'report/reportsale/cRptSalesDailyByCashier/FSvCCallRptExportFile';

//  รายงาน - จำนวนขายประจำเดือน - ตามสินค้า
$route['rptSMP']                                    = 'report/reportsale/cRptSalesMonthProduct/index';
$route['rptSMPClickPage']                           = 'report/reportsale/cRptSalesMonthProduct/FSvCCallRptViewBeforePrintClickPage';
$route['rptSMPCallExportFile']                      = 'report/reportsale/cRptSalesMonthProduct/FSvCCallRptExportFile';

//  รายงาน - การคืนสินค้าตามวันที่
$route['rptRPD']                                    = 'report/reportsale/cRptReturnProductByDate/index';
$route['rptRPDClickPage']                           = 'report/reportsale/cRptReturnProductByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRPDCallExportFile']                      = 'report/reportsale/cRptReturnProductByDate/FSvCCallRptExportFile';

//  รายงาน - การคืนสินค้าข้ามวัน
$route['rptRPDNextDate']                            = 'report/reportsale/cRptReturnProductNextDate/index';
$route['rptRPDNextDateClickPage']                   = 'report/reportsale/cRptReturnProductNextDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRPDNextDateCallExportFile']              = 'report/reportsale/cRptReturnProductNextDate/FSvCCallRptExportFile';

// รายางานการเติมสินค้า
$route['rptProductRefill']                          = 'report/reportproductrefill/cRptProductRefill/index';
$route['rptProductRefillClickPage']                 = 'report/reportproductrefill/cRptProductRefill/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductRefillCallExportFile']            = 'report/reportproductrefill/cRptProductRefill/FSvCCallRptExportFile';

// รายงานสินค้าคงคลังตามสินค้าตามตู้
$route['rptProductByCabinet']                       = 'report/reportproductbycabinet/cRptPdtByCabinet/index';
$route['rptProductByCabinetClickPage']              = 'report/reportproductbycabinet/cRptPdtByCabinet/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductByCabinetCallExportFile']         = 'report/reportproductbycabinet/cRptPdtByCabinet/FSvCCallRptExportFile';

//รายงานการสั่งขาย
$route['rptSaleOrder']                              = 'report/reportsale/cRptSaleOrder/index';
$route['rptSaleOrderClickPage']                     = 'report/reportsale/cRptSaleOrder/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleOrderCallExportFile']                = 'report/reportsale/cRptSaleOrder/FSvCCallRptExportFile';

// รายงานสินค้าไม่ผ่านอนุมัติ
$route['rptSaleSoNotPass']                          = 'report/reportsalesonotpass/cRptSaleSoNotPass/index';
$route['rptSaleSoNotPassClickPage']                 = 'report/reportsalesonotpass/cRptSaleSoNotPass/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleSoNotPassCallExportFile']            = 'report/reportsalesonotpass/cRptSaleSoNotPass/FSvCCallRptExportFile';

// รายงานยอดขายสิ้นวัน
$route['rptRptDayEndSales']                         = 'report/reportsale/cRptDayEndSales/index';
$route['rptRptDayEndSalesClickPage']                = 'report/reportsale/cRptDayEndSales/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDayEndSalesCallExportFile']           = "report/reportsale/cRptDayEndSales/FSvCCallRptExportFile";

// รายงาน- ภาษีขาย (เต็มรูป)
$route['rptRptTaxSaleFull']                         = "report/reportsale/cRptTaxSaleFull/index";
$route['rptRptTaxSaleFullClickPage']                = "report/reportsale/cRptTaxSaleFull/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSaleFullCallExportFile']           = "report/reportsale/cRptTaxSaleFull/FSvCCallRptExportFile";

// รายงาน- ภาษีขายตามวันที่ (เต็มรูป)
$route['rptRptTaxSalePosByDateFull']                = "report/reportsale/cRptTaxSalePosByDateFull/index";
$route['rptRptTaxSalePosByDateFullClickPage']       = "report/reportsale/cRptTaxSalePosByDateFull/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosByDateFullCallExportFile']  = "report/reportsale/cRptTaxSalePosByDateFull/FSvCCallRptExportFile";

// รายงาน ยอดขายตามแคชเชียร์ - ตามเครื่องจุดขาย
$route['rptSaleByCashierAndPos']                    = "report/reportsale/cRptSaleByCashierAndPos/index";
$route['rptSaleByCashierAndPosClickPage']           = "report/reportsale/cRptSaleByCashierAndPos/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleByCashierAndPosCallExportFile']      = "report/reportsale/cRptSaleByCashierAndPos/FSvCCallRptExportFile";

// รายงาน - ยกเลิกบิลตามวันที่
$route['rptCancelBillByDate']                       = "report/reportsale/cRptCancelBillByDate/index";
$route['rptCancelBillByDateClickPage']              = "report/reportsale/cRptCancelBillByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptCancelBillByDateCallExportFile']         = "report/reportsale/cRptCancelBillByDate/FSvCCallRptExportFile";

// รายงาน - ยกเลิกรายการตามวันที่
$route['rptCancelPdtDetailByDate']                  = "report/reportsale/cRptCancelPdtDetailByDate/index";
$route['rptCancelPdtDetailByDateClickPage']         = "report/reportsale/cRptCancelPdtDetailByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptCancelPdtDetailByDateCallExportFile']    = "report/reportsale/cRptCancelPdtDetailByDate/FSvCCallRptExportFile";

//รายงาน - ยอดขายตามสมาชิก
$route['rptSaleMember']                         = "report/reportsale/cRptSaleMember/index";
$route['rptSaleMemberClickPage']                = "report/reportsale/cRptSaleMember/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleMemberCallExportFile']           = "report/reportsale/cRptSaleMember/FSvCCallRptExportFile";

//รายงาน - แต้มแบบสรุป (Point By Customer)
$route['rptPointByCst']                         = "report/reportsale/cRptPointByCst/index";
$route['rptPointByCstClickPage']                = "report/reportsale/cRptPointByCst/FSvCCallRptViewBeforePrintClickPage";
$route['rptPointByCstCallExportFile']           = "report/reportsale/cRptPointByCst/FSvCCallRptExportFile";

// รายงาน - ภาษีตามสินค้า
$route['rptTaxByProduct']                       = "report/reportsale/cRptTaxByProduct/index";
$route['rptTaxByProductClickPage']              = "report/reportsale/cRptTaxByProduct/FSvCCallRptViewBeforePrintClickPage";
$route['rptTaxByProductCallExportFile']         = "report/reportsale/cRptTaxByProduct/FSvCCallRptExportFile";

// รายงาน - การแลก/คืน บัตรเงินสด
$route['rptRedeemReturnCard']                   = 'report/reportcard/cRptRedeemReturnCard/index';
$route['rptRedeemReturnCardClickPage']          = 'report/reportcard/cRptRedeemReturnCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptRedeemReturnCardCallExportFile']     = 'report/reportcard/cRptRedeemReturnCard/FSvCCallRptExportFile';

// รายงาน - รายได้เนื่องจากการไม่คืนบัตร
$route['rptIncomeNotReturnCard']                = 'report/reportcard/cRptIncomeNotReturnCard/index';
$route['rptIncomeNotReturnCardClickPage']       = 'report/reportcard/cRptIncomeNotReturnCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptIncomeNotReturnCardCallExportFile']  = 'report/reportcard/cRptIncomeNotReturnCard/FSvCCallRptExportFile';

// รายงาน - ยอดขายตามบิลตามวันที่ตามการชำระเงิน (ละเอียด)
$route['rptSaleBillPaymentDate']                = "report/reportsale/cRptSaleBillPaymentDate/index";
$route['rptSaleBillPaymentDateClickPage']       = "report/reportsale/cRptSaleBillPaymentDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleBillPaymentDateCallExportFile']  = "report/reportsale/cRptSaleBillPaymentDate/FSvCCallRptExportFile";

// รายงาน การปรับราคาสินค้า
$route['rptADJPrice']                           = 'report/reportmaster/cRptAdjPrice/index';
$route['rptADJPriceClickPage']                  = 'report/reportmaster/cRptAdjPrice/FSvCCallRptViewBeforePrintClickPage';
$route['rptADJPriceCallExportFile']             = 'report/reportmaster/cRptAdjPrice/FSvCCallRptExportFile';

// รายงานการปรับราคาสินค้าตามกลุ่มราคา
$route['rptADJPriceByGroup']                    = 'report/reportproductpricebygroup/cRptProductADJPriceByGroup/index';
$route['rptADJPriceByGroupClickPage']           = 'report/reportproductpricebygroup/cRptProductADJPriceByGroup/FSvCCallRptViewBeforePrintClickPage';
$route['rptADJPriceByGroupCallExportFile']      = 'report/reportproductpricebygroup/cRptProductADJPriceByGroup/FSvCCallRptExportFile';

// รายงาน - ยอดขายตามวันที่ตามการชำระเงิน (สรุป)
$route['rptSalesByDatePayment']                 = "report/reportsale/cRptSalesByDatePayment/index";
$route['rptSalesByDatePaymentClickPage']        = "report/reportsale/cRptSalesByDatePayment/FSvCCallRptViewBeforePrintClickPage";
$route['rptSalesByDatePaymentCallExportFile']   = "report/reportsale/cRptSalesByDatePayment/FSvCCallRptExportFile";

//รายงาน - ยอดขายตามบิลตามสินค้า
$route['rptSalByBillPdt']                       = "report/reportsalespecial/cRptSalByBillPdt/index";
$route['rptSalByBillPdtClickPage']              = "report/reportsalespecial/cRptSalByBillPdt/FSvCCallRptViewBeforePrintClickPage";
$route['rptSalByBillPdtCallExportFile']         = "report/reportsalespecial/cRptSalByBillPdt/FSvCCallRptExportFile";

//รายงาน - ยอดขายตามสาขาตามวันที่
$route['rptSalBchByDate']                       = "report/reportsale/cRptSalBchByDate/index";
$route['rptSalBchByDateClickPage']              = "report/reportsale/cRptSalBchByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptSalBchByDateCallExportFile']         = "report/reportsale/cRptSalBchByDate/FSvCCallRptExportFile";

//รายงาน - ยอดขายตามช่วงเวลา
$route['rptSalTimePrdTmp']                      = "report/reportsale/cRptSalTimePrdTmp/index";
$route['rptSalTimePrdTmpClickPage']             = "report/reportsale/cRptSalTimePrdTmp/FSvCCallRptViewBeforePrintClickPage";
$route['rptSalTimePrdTmpCallExportFile']        = "report/reportsale/cRptSalTimePrdTmp/FSvCCallRptExportFile";

//รายงาน - ยอดขายตามสาขาตามสินค้า - สินค้าชุด
$route['rptSalByPdtSet']                        = "report/reportsale/cRptSalByPdtSet/index";
$route['rptSalByPdtSetClickPage']               = "report/reportsale/cRptSalByPdtSet/FSvCCallRptViewBeforePrintClickPage";
$route['rptSalByPdtSetCallExportFile']          = "report/reportsale/cRptSalByPdtSet/FSvCCallRptExportFile";

// รายงาน - สินค้าถึงจุดสั่งซื้อตามคลัง
$route['rptPdtPointWah']                        = "report/reportPointWah/cPdtPointWah/index";
$route['rptPdtPointWahClickPage']               = "report/reportPointWah/cPdtPointWah/FSvCCallRptViewBeforePrintClickPage";
$route['rptPdtPointWahExportFile']              = "report/reportPointWah/cPdtPointWah/FSvCCallRptExportFile";

// รายงาน - รับเข้าสินค้าตามวันที่
$route['rptDocPdtTwi']                          = "report/reportdocpdttwi/cRptDocPdtTwi/index";
$route['rptDocPdtTwiClickPage']                 = "report/reportdocpdttwi/cRptDocPdtTwi/FSvCCallRptViewBeforePrintClickPage";
$route['rptDocPdtTwiExportFile']                = "report/reportdocpdttwi/cRptDocPdtTwi/FSvCCallRptExportFile";

// รายงาน - การคืนสืนค้าตามวันที่
$route['rptRetPdtVd']                           = 'report/reportRetPdtVdByDate/cRptRetPdtVdByDate/index';
$route['rptRetPdtVdClickPage']                  = 'report/reportRetPdtVdByDate/cRptRetPdtVdByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRetPdtVdCallExportFile']             = 'report/reportRetPdtVdByDate/cRptRetPdtVdByDate/FSvCCallRptExportFile';

// รายงาน - การนำสินค้าออกจากตู้
$route['rptTransferVendingOut']                 = 'report/rptTransferVendingOut/cRptTransferVendingOut/index';
$route['rptTransferVendingOutClickPage']        = 'report/rptTransferVendingOut/cRptTransferVendingOut/FSvCCallRptViewBeforePrintClickPage';
$route['rptTransferVendingOutCallExportFile']   = 'report/rptTransferVendingOut/cRptTransferVendingOut/FSvCCallRptExportFile';

// รายงาน - การเบิกออกสินค้าตามวันที่
$route['rptRequisitionProductByDate']               = 'report/rptRequisitionProductByDate/cRptRequisitionProductByDate/index';
$route['rptRequisitionProductByDateClickPage']      = 'report/rptRequisitionProductByDate/cRptRequisitionProductByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRequisitionProductByDateCallExportFile'] = 'report/rptRequisitionProductByDate/cRptRequisitionProductByDate/FSvCCallRptExportFile';

// // รายงาน - การขายสินค้าโปรโมชัน
$route['rptPdtSalePromotion']                       = 'report/reportsale/cRptPdtSalePromotion/index';
$route['rptPdtSalePromotionClickPage']              = 'report/reportsale/cRptPdtSalePromotion/FSvCCallRptViewBeforePrintClickPage';
$route['rptPdtSalePromotionCallExportFile']         = 'report/reportsale/cRptPdtSalePromotion/FSvCCallRptExportFile';


// รายงาน - การขายสินค้าโปรโมชันตามเอกสาร
$route['rptPdtSalePromotionPerDoc']                 = 'report/reportsale/cRptPdtSalePromotionPerDoc/index';
$route['rptPdtSalePromotionPerDocClickPage']        = 'report/reportsale/cRptPdtSalePromotionPerDoc/FSvCCallRptViewBeforePrintClickPage';
$route['rptPdtSalePromotionPerDocCallExportFile']   = 'report/reportsale/cRptPdtSalePromotionPerDoc/FSvCCallRptExportFile';

// // รายงาน - การขายสินค้าโปรโมชันตามเอกสาร
// $route['rptPdtSalePromotionPerDoc']                 = 'report/reportsale/cRptPdtSalePromotionPerDoc/index';
// $route['rptPdtSalePromotionPerDocClickPage']        = 'report/reportsale/cRptPdtSalePromotionPerDoc/FSvCCallRptViewBeforePrintClickPage';
// $route['rptPdtSalePromotionPerDocCallExportFile']   = 'report/reportsale/cRptPdtSalePromotionPerDoc/FSvCCallRptExportFile';

// รายงาน - การขายสินค้าตามคูปอง (ตามสินค้า)
$route['rptPdtSaleCoupon']                       = 'report/reportsale/RptPdtCouponPromotion_controller/index';
$route['rptPdtSaleCouponClickPage']              = 'report/reportsale/RptPdtCouponPromotion_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptPdtSaleCouponCallExportFile']         = 'report/reportsale/RptPdtCouponPromotion_controller/FSvCCallRptExportFile';

// รายงาน - เปลียบเทียบยอดขายตามสินค้า (MTD)
$route['rptCompareSaleByPdt']                       = 'report/reportsale/cRptCompareSaleByPdt/index';
$route['rptCompareSaleByPdtClickPage']              = 'report/reportsale/cRptCompareSaleByPdt/FSvCCallRptViewBeforePrintClickPage';
$route['rptCompareSaleByPdtCallExportFile']         = 'report/reportsale/cRptCompareSaleByPdt/FSvCCallRptExportFile';
$route['dashboardsaleMainReportPage/(:any)']        = 'report/reportsale/cRptCompareSaleByPdt/FSvCDSHSALMainPageReport/$1';
$route['dashboardsaleMainReportPageQty/(:any)']     = 'report/reportsale/cRptCompareSaleByPdt/FSvCDSHSALMainPageReportQty/$1';
$route['dashboardsaleRptCompareSaleByPdt']          = 'report/reportsale/cRptCompareSaleByPdt/FSvCDSHSALViewRptCompareSaleByPdt';
$route['dashboardsaleRptCompareSaleByPdtQty']       = 'report/reportsale/cRptCompareSaleByPdt/FSvCDSHSALViewRptCompareSaleByPdtQty';

// รายงาน - เปรียบเทียบยอดขายตามประเภทสินค้า
$route['rptCompareSaleByPdtType']                           = 'report/reportsale/cRptCompareSaleByPdtType/index';
$route['rptCompareSaleByPdtTypeClickPage']                  = 'report/reportsale/cRptCompareSaleByPdtType/FSvCCallRptViewBeforePrintClickPage';
$route['rptCompareSaleByPdtTypeCallExportFile']             = 'report/reportsale/cRptCompareSaleByPdtType/FSvCCallRptExportFile';
$route['dashboardsaleReportCompareSaleByPdtTypeQTY/(:any)'] = 'report/reportsale/cRptCompareSaleByPdtType/FSvCDSHSALViewReportCompareSaleByPdtTypeQTY/$1';
$route['dashboardsaleReportCompareSaleByPdtType/(:any)']    = 'report/reportsale/cRptCompareSaleByPdtType/FSvCDSHSALViewReportCompareSaleByPdtType/$1';

// รายงาน - ประวัติการเบิกออกสินค้า
$route['rptPickingHistory']                         = 'report/reporthistory/cRptPickingHistory/index';
$route['rptPickingHistoryClickPage']                = 'report/reporthistory/cRptPickingHistory/FSvCCallRptViewBeforePrintClickPage';
$route['rptPickingHistoryCallExportFile']           = 'report/reporthistory/cRptPickingHistory/FSvCCallRptExportFile';

// รายงาน - ประวัติการโอนสินค้าระหว่างสาขา
$route['rptTransferHistoryBch']                     = 'report/reporthistory/cRptTransferHistoryBch/index';
$route['rptTransferHistoryBchClickPage']            = 'report/reporthistory/cRptTransferHistoryBch/FSvCCallRptViewBeforePrintClickPage';
$route['rptTransferHistoryBchCallExportFile']       = 'report/reporthistory/cRptTransferHistoryBch/FSvCCallRptExportFile';

// รายงาน - สินค้าสินค้าคงเหลือ - ตามสาขา
$route['rptRptInventoriesByBch']                    = 'report/reportinventoriesbybch/cRptInventoriesByBch/index';
$route['rptRptInventoriesByBchClickPage']           = 'report/reportinventoriesbybch/cRptInventoriesByBch/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoriesByBchCallExportFile']      = 'report/reportinventoriesbybch/cRptInventoriesByBch/FSvCCallRptExportFile';

// รายงาน - สินค้าคงเหลือ - กลุ่มสินค้า
$route['rptRptInventoryPdtGrp']                     = 'report/reportInventoryPdtGrp/cRptInventoryPdtGrp/index';
$route['rptRptInventoryPdtGrpClickPage']            = 'report/reportInventoryPdtGrp/cRptInventoryPdtGrp/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoryPdtGrpCallExportFile']       = 'report/reportInventoryPdtGrp/cRptInventoryPdtGrp/FSvCCallRptExportFile';

// รายงาน - สินค้าถึงจุดสั่งซื้อตามสินค้า
$route['rptReorderPointPerPdt']                     = "report/reportReorderPointPerPdt/cReorderPointPerPdt/index";
$route['rptReorderPointPerPdtClickPage']            = "report/reportReorderPointPerPdt/cReorderPointPerPdt/FSvCCallRptViewBeforePrintClickPage";
$route['rptReorderPointPerPdtExportFile']           = "report/reportReorderPointPerPdt/cReorderPointPerPdt/FSvCCallRptExportFile";

// รายงาน - ประวัติการรับเข้าสินค้า
$route['rptPdtHisTnfIN']                            = "report/reportProductTnfIN/cPdtHisTnfIN/index";
$route['rptPdtHisTnfINClickPage']                   = "report/reportProductTnfIN/cPdtHisTnfIN/FSvCCallRptViewBeforePrintClickPage";
$route['rptPdtHisTnfINExportFile']                  = "report/reportProductTnfIN/cPdtHisTnfIN/FSvCCallRptExportFile";

// Create By Witsarut 01022021
$route['rptPdtHisTnfBch']                           = "report/reportrptPdtHisTnfBch/cPdtHisTnfBch/index";
$route['rptPdtHisTnfBchClickPage']                  = "report/reportrptPdtHisTnfBch/cPdtHisTnfBch/FSvCCallRptViewBeforePrintClickPage";
$route['rptPdtHisTnfBchExportFile']                 = "report/reportrptPdtHisTnfBch/cPdtHisTnfBch/FSvCCallRptExportFile";

// Create By Worakorn 01022021
$route['rptPdtStock']                               = "report/reportpdtstock/cRptPdtStock/index";
$route['rptPdtStockClickPage']                      = "report/reportpdtstock/cRptPdtStock/FSvCCallRptViewBeforePrintClickPage";
$route['rptPdtStockExportFile']                     = "report/reportpdtstock/cRptPdtStock/FSvCCallRptExportFile";

// Create By Sooksanti 16/02/2021
$route['rptLicClosetExpir']                         = "report/rptLicClosetExpir/cRptLicClosetExpir/index";
$route['rptLicClosetExpirClickPage']                = "report/rptLicClosetExpir/cRptLicClosetExpir/FSvCCallRptViewBeforePrintClickPage";
$route['rptLicClosetExpirExportFile']               = "report/rptLicClosetExpir/cRptLicClosetExpir/FSvCCallRptExportFile";

// รายงาน - การขายรอการชำระเงิน
$route['rptSalePending']                            = 'report/reportsale/cRptSalePending/index';
$route['rptSalePendingClickPage']                   = 'report/reportsale/cRptSalePending/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalePendingExportFile']                  = 'report/reportsale/cRptSalePending/FSvCCallRptExportFile';

// รายงาน - ประวัติการใช้บริการลูกค้า
$route['rptCustomerHistoryService']                 = 'report/reportFitauto/Rptcustomerhistoryservice_controller/index';
$route['rptCustomerHistoryServiceClickPage']        = 'report/reportFitauto/Rptcustomerhistoryservice_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptCustomerHistoryServiceExportFile']       = 'report/reportFitauto/Rptcustomerhistoryservice_controller/FSvCCallRptExportFile';

// รายงาน - ลูกค้าที่ขาดการติดต่อ
$route['rptCstLostCont']                            = 'report/reportFitauto/Rptcstlostcont_controller/index';
$route['rptCstLostContClickPage']                   = 'report/reportFitauto/Rptcstlostcont_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptCstLostContExportFile']                  = 'report/reportFitauto/Rptcstlostcont_controller/FSvCCallRptExportFile';

// รายงาน - บริการครั้งต่อไป
$route['rptCstForCastByCar']                        = 'report/reportFitauto/Rptcstforcastbycar_controller/index';
$route['rptCstForCastByCarClickPage']               = 'report/reportFitauto/Rptcstforcastbycar_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptCstForCastByCarExportFile']              = 'report/reportFitauto/Rptcstforcastbycar_controller/FSvCCallRptExportFile';

//รายงาน - ยอดซื้อตามผู้จำหน่าย ตามสินค้า
$route['rptBuyPurSplByPdt']                         = 'report/reportbuy/Rptpursplbypdt_controller/index';
$route['rptBuyPurSplByPdtClickPage']                = 'report/reportbuy/Rptpursplbypdt_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - รายงานวิเคราะห์ความพึงพอใจ
$route['rptSatificationAnalysis']                   = 'report/reportFitauto/Rptsatificationanalysis_controller/index';
$route['rptSatificationAnalysisClickPage']          = 'report/reportFitauto/Rptsatificationanalysis_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptSatificationAnalysisExportFile']         = 'report/reportFitauto/Rptsatificationanalysis_controller/FSvCCallRptExportFile';

// รายงาน - สรุปยอดซื้อตามสินค้า
$route['rptBuyByPdt']                               = 'report/reportbuy/Rptpurbypdt_controller/index';
$route['rptBuyByPdtClickPage']                      = 'report/reportbuy/Rptpurbypdt_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - ภาษีซื้อ
$route['rptPurVat']                                 = 'report/reportbuy/Rptpurvat_controller/index';
$route['rptPurVatClickPage']                        = 'report/reportbuy/Rptpurvat_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - รายงานติดตามหลังให้บริการ 3 วัน
$route['rptCstFollowAft']                           = 'report/reportFitauto/RptCstFollowAft_controller/index';
$route['rptCstFollowAftClickPage']                  = 'report/reportFitauto/RptCstFollowAft_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptCstFollowAftExportFile']                 = 'report/reportFitauto/RptCstFollowAft_controller/FSvCCallRptExportFile';

// รายงาน - รายงานแสดงข้อมูลลูกค้า
$route['rptPreviewCustomer']                        = "report/reportFitauto/Rptpreviewcustomer_controller/index";
$route['rptPreviewCustomerClickPage']               = "report/reportFitauto/Rptpreviewcustomer_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptPreviewCustomerExportFile']              = "report/reportFitauto/Rptpreviewcustomer_controller/FSvCCallRptExportFile";

// รายงาน - การขายตามประเภทการชำระเงิน
$route['rptSaleByPaymentType']                      = "report/reportFitauto/Rptsalebypaymenttype_controller/index";
$route['rptSaleByPaymentTypeClickPage']             = "report/reportFitauto/Rptsalebypaymenttype_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleByPaymentTypeExportFile']            = "report/reportFitauto/Rptsalebypaymenttype_controller/FSvCCallRptExportFile";

// รายงาน - เบิกจ่ายกองยาน (ลูกค้า Fleet)
$route['rptCstCreditMoneyFleet']                    = "report/reportFitauto/Rptcstcreditmoney_controller/index";
$route['rptCstCreditMoneyFleetClickPage']           = "report/reportFitauto/Rptcstcreditmoney_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptCstCreditMoneyFleetExportFile']          = "report/reportFitauto/Rptcstcreditmoney_controller/FSvCCallRptExportFile";

// รายงาน - แสดงลูกค้าที่ต้องเข้าใช้บริการ
$route['rptServiceByCustomer']                      = "report/reportFitauto/Rptservicebycustomer_controller/index";
$route['rptServiceByCustomerClickPage']             = "report/reportFitauto/Rptservicebycustomer_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptServiceByCustomerExportFile']            = "report/reportFitauto/Rptservicebycustomer_controller/FSvCCallRptExportFile";

// รายงาน - ใบรับเงินมัดจำ
$route['rptRptDepositDoc']                          = "report/reportFitauto/Rptdepositdoc_controller/index";
$route['rptRptDepositDocClickPage']                 = "report/reportFitauto/Rptdepositdoc_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptDepositDocExportFile']                = "report/reportFitauto/Rptdepositdoc_controller/FSvCCallRptExportFile";

// รายงาน - ผลต่างการตรวจนับสต๊อก
$route['rptStkCountVariance']                       = "report/reportFitauto/Rptstkcountvariance_controller/index";
$route['rptStkCountVarianceClickPage']              = "report/reportFitauto/Rptstkcountvariance_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptStkCountVarianceExportFile']             = "report/reportFitauto/Rptstkcountvariance_controller/FSvCCallRptExportFile";

// รายงาน - การสั่งซื้อตามสาขา
// Create By Wasin 22/10/2021
$route['rptPoByBchByPdt']                           = "report/reportFitauto/Rptpobybchbypdt_controller/index";
$route['rptPoByBchByPdtClickPage']                  = "report/reportFitauto/Rptpobybchbypdt_controller/FSvCCallRptViewBeforePrintClickPage";
$route['rptPoByBchByPdtExportFile']                 = "report/reportFitauto/Rptpobybchbypdt_controller/FSvCCallRptExportFile";

//รายงาน - ยอดซื้อตามผู้จำหน่าย ตามสินค้า
$route['rptBuyHistoryPdt']                         = 'report/reportFitauto/Rptpurhistorypdt_controller/index';
$route['rptBuyHistoryPdtClickPage']                = 'report/reportFitauto/Rptpurhistorypdt_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - ภาษีซื้อ
$route['rptPurVatNew']                              = 'report/reportFitauto/Rptpurvat_controller/index';
$route['rptPurVatNewClickPage']                     = 'report/reportFitauto/Rptpurvat_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - อายุเจ้าหนี้
$route['rptCreditAging']                            = 'report/reportFitauto/Rptcreditaging_controller/index';
$route['rptCreditAgingClickPage']                   = 'report/reportFitauto/Rptcreditaging_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - รายงานใบเปิดงาน
$route['rptOpenJob']                                = 'report/reportFitauto/Rptopenjob_controller/index';
$route['rptOpenJobClickPage']                       = 'report/reportFitauto/Rptopenjob_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptOpenJobExportFile']                      = 'report/reportFitauto/Rptopenjob_controller/FSvCCallRptExportFile';

//รายงาน - อายุลูกหนี้
$route['rptCreditDebtor']                           = 'report/reportFitauto/Rptcredebtor_controller/index';
$route['rptCreditDebtorClickPage']                  = 'report/reportFitauto/Rptcredebtor_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน  - ลูกหนี้ค้างชำระ
$route['rptDebtorOverdue']                          = 'report/reportFitauto/Rptdebtoroverdue_controller/index';
$route['rptDebtorOverdueClickPage']                 = 'report/reportFitauto/Rptdebtoroverdue_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน  - สรุปการจ่ายชำระประจำวัน
$route['rptDailypayment']                           = 'report/reportFitauto/Rptdailypayment_controller/index';
$route['rptDailypaymentClickPage']                  = 'report/reportFitauto/Rptdailypayment_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน  - สรุปการจ่ายชำระประจำวัน
$route['rptSaleconditiongroup']                     = 'report/reportFitauto/Rptsaleconditiongroup_controller/index';
$route['rptSaleconditiongroupClickPage']            = 'report/reportFitauto/Rptsaleconditiongroup_controller/FSvCCallRptViewBeforePrintClickPage';

//rptHisPayDePt
$route['rptHisPayDePt']                             = 'report/reportFitauto/Rpthispaydept_controller/index';
$route['rptHisPayDePtClickPage']                    = 'report/reportFitauto/Rpthispaydept_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptHisPayDePtExportFile']                   = 'report/reportFitauto/Rpthispaydept_controller/FSvCCallRptExportFile';


// รายงาน - รายงานใบเสนอราคา
$route['rptSaleQuantation']                         = 'report/reportFitauto/RptSaleQuantation_controller/index';
$route['rptSaleQuantationClickPage']                = 'report/reportFitauto/RptSaleQuantation_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleQuantationExportFile']               = 'report/reportFitauto/RptSaleQuantation_controller/FSvCCallRptExportFile';

// รายงาน - รายงานใบเสนอราคาต่ำกว่าทุน
$route['rptSaleQuantationBlc']                      = 'report/reportFitauto/RptSaleQuantationBlc_controller/index';
$route['rptSaleQuantationBlcClickPage']             = 'report/reportFitauto/RptSaleQuantationBlc_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleQuantationBlcExportFile']            = 'report/reportFitauto/RptSaleQuantationBlc_controller/FSvCCallRptExportFile';

// รายงาน - รายงานใบสั่งซื้อ
$route['rptPerchaseOrder']                          = 'report/reportFitauto/RptPerchaseOrder_controller/index';
$route['rptPerchaseOrderClickPage']                 = 'report/reportFitauto/RptPerchaseOrder_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptPerchaseOrderExportFile']                = 'report/reportFitauto/RptPerchaseOrder_controller/FSvCCallRptExportFile';

// รายงาน - รายได้จากระบบสินเชื่อ
$route['rptIncomeFromCreditSystem']                  = 'report/reportFitauto/RptIncomeFromCreditSystem_controller/index';
$route['rptIncomeFromCreditSystemClickPage']         = 'report/reportFitauto/RptIncomeFromCreditSystem_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptIncomeFromCreditSystemExportFile']        = 'report/reportFitauto/RptIncomeFromCreditSystem_controller/FSvCCallRptExportFile';


// รายงาน - ยอดขายเฉลี่ย (ชิ้น) ดามวันต่อสัปดาห์
$route['rptAverageDayToWeekSales']                   = 'report/reportFitauto/Rptaveragedaytoweeksales_controller/index';
$route['rptAverageDayToWeekSalesClickPage']          = 'report/reportFitauto/Rptaveragedaytoweeksales_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptAverageDayToWeekSalesExportFile']         = 'report/reportFitauto/Rptaveragedaytoweeksales_controller/FSvCCallRptExportFile';

// รายงาน - รายงานสินค้าค้างรับ
$route['rptProductUnreceived']                      = 'report/reportFitauto/Rptproductunreceived_controller/index';
$route['rptProductUreceivedClickPage']              = 'report/reportFitauto/Rptproductunreceived_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductUnreceivedExportFile']            = 'report/reportFitauto/Rptproductunreceived_controller/FSvCCallRptExportFile';

//รายงาน  - ยอดขายตามเงื่อนไข
$route['rptSaleGrpByCond']                          = 'report/reportFitauto/Rptsalegrpbycond_controller/index';
$route['rptSaleGrpByCondClickPage']                 = 'report/reportFitauto/Rptsalegrpbycond_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - รายงาน DOT ยาง
$route['rptTireDot']                                = 'report/reportFitauto/RptTireDot_controller/index';
$route['rptTireDotClickPage']                       = 'report/reportFitauto/RptTireDot_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptTireDotExportFile']                      = 'report/reportFitauto/RptTireDot_controller/FSvCCallRptExportFile';

// รายงาน - รายงาน DOT ยางตาม Brand
$route['rptTireDotBrand']                                = 'report/reportFitauto/RptTireDotBrand_controller/index';
$route['rptTireDotBrandClickPage']                       = 'report/reportFitauto/RptTireDotBrand_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptTireDotBrandExportFile']                      = 'report/reportFitauto/RptTireDotBrand_controller/FSvCCallRptExportFile';

// รายงาน - รายงาน DOT ยางตาม Product
$route['rptTireDotProduct']                                = 'report/reportFitauto/RptTireDotProduct_controller/index';
$route['rptTireDotProductClickPage']                       = 'report/reportFitauto/RptTireDotProduct_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptTireDotProductExportFile']                      = 'report/reportFitauto/RptTireDotProduct_controller/FSvCCallRptExportFile';

// รายงาน - รายงานร่องราคา
$route['rptPricePdtGrp']                            = 'report/reportFitauto/RptPricePdtGrp_controller/index';
$route['rptPricePdtGrpClickPage']                   = 'report/reportFitauto/RptPricePdtGrp_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptPricePdtGrpExportFile']                  = 'report/reportFitauto/RptPricePdtGrp_controller/FSvCCallRptExportFile';

//รายงาน  - ยอดขายตามเงื่อนไข
$route['rptAnalysPurchase']                         = 'report/reportFitauto/Rptanalyspurchase_controller/index';
$route['rptAnalysPurchaseClickPage']                = 'report/reportFitauto/Rptanalyspurchase_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - ประวัติการติดตาม
$route['rptTrackingHistory']                        = 'report/reportFitauto/Rpttrackinghistory_controller/index';
$route['rptTrackingHistoryClickPage']               = 'report/reportFitauto/Rpttrackinghistory_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptTrackingHistoryExportFile']              = 'report/reportFitauto/Rpttrackinghistory_controller/FSvCCallRptExportFile';

// รายงาน - เจ้าหนี้ค้างชำระ
$route['rptPurCrOverDue']                           = 'report/reportFitauto/Rptpurcroverdue_controller/index';
$route['rptPurCrOverDueClickPage']                  = 'report/reportFitauto/Rptpurcroverdue_controller/FSvCCallRptViewBeforePrintClickPage';
$route['rptPurCrOverDueExportFile']                 = 'report/reportFitauto/Rptpurcroverdue_controller/FSvCCallRptExportFile';

// รายงาน สินค้า
$route['rptEntryProduct']                           = 'report/reportbuy/Rptentryproduct_controller/index';
$route['rptEntryProductClickPage']                  = 'report/reportbuy/Rptentryproduct_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน
$route['rptEntryProductUnit']                       = 'report/reportbuy/Rptentryproductunit_controller/index';
$route['rptEntryProductUnitClickPage']              = 'report/reportbuy/Rptentryproductunit_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - โอนสินค้าระหว่างคลัง
$route['rptRptInventoryTranfer']                    = 'report/reportFitauto/Rptinventorytranfer_controller/index';
$route['rptRptInventoryTranferClickPage']           = 'report/reportFitauto/Rptinventorytranfer_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน สินค้าคงคลังตามช่วงวัน
$route['rptStockbydate']                            = 'report/reportFitauto/Rptstockbydate_controller/index';
$route['rptStockbydateClickPage']                   = 'report/reportFitauto/Rptstockbydate_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานใบสั่งขาย
$route['rptPoForm']                                 = 'report/reportFitauto/RptPoForm_controller/index';
$route['rptPoFormClickPage']                        = 'report/reportFitauto/RptPoForm_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานยอดขายตามบิลตามสาขา
$route['rptSaleByBillAndBch']                       = "report/reportsale/cRptSaleByBillAndBch/index";
$route['rptSaleByBillAndBchClickPage']              = "report/reportsale/cRptSaleByBillAndBch/FSvCCallRptViewBeforePrintClickPage";

// รายยอดขายตามหมวดตาม Template
$route['rptSalesByCategoryTemplate']                = 'report/reportFitauto/RptSalesByCategoryTemplate_controller/index';
$route['rptSalesByCategoryTemplateClickPage']       = 'report/reportFitauto/RptSalesByCategoryTemplate_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานยอดขายรวมตามจำนวนบิล
$route['rptSalesByBills']                           = 'report/reportFitauto/RptSalesByBills_controller/index';
$route['rptSalesByBillsClickPage']                  = 'report/reportFitauto/RptSalesByBills_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานข้อมูล WHT
$route['rptRptWHT']                                 = 'report/reportFitauto/RptWithholdingTax_controller/index';
$route['rptRptWHTClickPage']                        = 'report/reportFitauto/RptWithholdingTax_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานสต๊อก รายวันตามการเคลื่อนไหวตามวัน
$route['rptStkOrderByDate']                         = 'report/reportFitauto/RptStkOrderByDay_controller/index';
$route['rptStkOrderByDateClickPage']                = 'report/reportFitauto/RptStkOrderByDay_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานต้นทุนสินค้าตามการส่ง Textfile ต้นทุน
$route['rptStkAllCompTextFile']                     = 'report/reportFitauto/RptStkAllCompTextFile_controller/index';
$route['rptStkAllCompTextFileClickPage']            = 'report/reportFitauto/RptStkAllCompTextFile_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงานสต๊อกรวมทุกสาขา แยกตามหมวดสินค้า
$route['rptCheckSTKAllBch']                         = 'report/reportFitauto/RptCheckSTKAllBch_controller/index';

// รายงานยอดขายรวมทุกสาขา 
$route['rptSalesAllBchByPDT']                       = 'report/reportFitauto/RptSalesAllBchByPDT_controller/index';

// รายงานยอดขายรวมทุกสาขา ตามจำนวนสินค้า 
$route['rptSalesAllBchByUNIT']                      = 'report/reportFitauto/RptSalesAllBchByUNIT_controller/index';

// รายงานยอดขายรวมทุกสาขา และจำนวนสินค้า
$route['rptSalesSumQtyAndPrice']                    = 'report/reportFitauto/RptSalesSumQtyAndPrice_controller/index';

// รายงานตามใบรับของ
$route['rptDeliveryOrder']                          = 'report/reportFitauto/RptDeliveryOrder_controller/index';

// รายงานตามใบรับเข้า
$route['rptTranferIn']                              = 'report/reportFitauto/RptTranferIn_controller/index';


// รายงาน - เพิ่มหนี้เจ้าหนี้
$route['rptCreditorDebt']                           = 'report/reportFitauto/Rptcreditordebt_controller/index';
$route['rptCreditorDebtClickPage']                  = 'report/reportFitauto/Rptcreditordebt_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - เพิ่มหนี้เจ้าหนี้ตามใบรับเข้า
$route['rptCreditorDebtByRcv']                      = 'report/reportFitauto/Rptcreditordebtbyrcv_controller/index';
$route['rptCreditorDebtByRcvClickPage']             = 'report/reportFitauto/Rptcreditordebtbyrcv_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - ลดหนี้เจ้าหนี้
$route['rptCreditorDis']                            = 'report/reportFitauto/Rptcreditordis_controller/index';
$route['rptCreditorDisClickPage']                   = 'report/reportFitauto/Rptcreditordis_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - การรับชำระลูกหนี้
$route['rptDebtorReceive']                          = 'report/reportFitauto/Rptdebtorreceive_controller/index';
$route['rptDebtorReceiveClickPage']                 = 'report/reportFitauto/Rptdebtorreceive_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - ลดหนี้ลูกหนี้
$route['rptDebtReceivable']                         = 'report/reportFitauto/Rptdebtreceivable_controller/index';
$route['rptDebtReceivableClickPage']                = 'report/reportFitauto/Rptdebtreceivable_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - ใบสั่งขายกว่าทุน
$route['rptSaleOrderLowCost']                       = 'report/reportFitauto/Rptsaleorderlowcost_controller/index';
$route['rptSaleOrderLowCostClickPage']              = 'report/reportFitauto/Rptsaleorderlowcost_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - เพิ่มลูกหนี้ตามใบเสร็จ
$route['rptDebtorByBills']                          = 'report/reportFitauto/Rptdebtorbybills_controller/index';
$route['rptDebtorByBillsClickPage']                 = 'report/reportFitauto/Rptdebtorbybills_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - ลดหนี้ลูกหนี้ตามใบเสร็จ
$route['rptReduceDebtByBills']                      = 'report/reportFitauto/Rptreducedebtbybills_controller/index';
$route['rptReduceDebtByBillsClickPage']             = 'report/reportFitauto/Rptreducedebtbybills_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - ลดหนี้เจ้าหนี้ตามใบเบิกออก
$route['rptDebtorByTnf']                            = 'report/reportFitauto/Rptdebtorbytnf_controller/index';
$route['rptDebtorByTnfClickPage']                   = 'report/reportFitauto/Rptdebtorbytnf_controller/FSvCCallRptViewBeforePrintClickPage';

//รายงาน - เพิ่มหนี้ลูกหนี้
$route['rptIncreaseDebtReceivable']                 = 'report/reportFitauto/Rptincreasedebtreceivable_controller/index';
$route['rptIncreaseDebtReceivableClickPage']        = 'report/reportFitauto/Rptincreasedebtreceivable_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - การวางบิลลูกหนี้
$route['rptReceivableBill']                         = 'report/reportFitauto/Rptreceivablebill_controller/index';
$route['rptReceivableBillClickPage']                = 'report/reportFitauto/RptReceivablebill_controller/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - การจ่ายโอนคลัง
$route['rptRptWahhouseTranfer']                    = 'report/reportFitauto/Rptwahhousetranfer_controller/index';
$route['rptRptWahhouseTranferClickPage']           = 'report/reportFitauto/Rptwahhousetranfer_controller/FSvCCallRptViewBeforePrintClickPage';