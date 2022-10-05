<?php

date_default_timezone_set('Asia/Bangkok');

// Modal Browse Product Document
$route['BrowseGetPdtList']         = 'document/browseproduct/cBrowseProduct/FMvCBWSPDTGetPdtList';
$route['BrowseGetPdtDetailList']   = 'document/browseproduct/cBrowseProduct/FMvCBWSPDTGetPdtDetailList';

// Document Image Product
$route['DOCGetPdtImg']             = 'document/document/cDocument/FMvCDOCGetPdtImg';

// ใบลดหนี้, ใบรับของ-ใบซื้อสินค้า/บริการ Center
$route['DOCEndOfBillCalVat']        = 'document/document/cDocEndOfBill/FStCDOCEndOfBillCalVat';
$route['DOCEndOfBillCal']           = 'document/document/cDocEndOfBill/FStCDOCEndOfBillCal';

// PO (เอกสารสั่งซื้อ)
// $route['po/(:any)/(:any)']         = 'document/purchaseorder/cPurchaseorder/index/$1/$2';
// $route['POFormSearchList']         = 'document/purchaseorder/cPurchaseorder/FSxCPOFormSearchList';
// $route['POPageAdd']                = 'document/purchaseorder/cPurchaseorder/FSxCPOAddPage';
// $route['POPageEdit']               = 'document/purchaseorder/cPurchaseorder/FSvCPOEditPage';
// $route['POEventAdd']               = 'document/purchaseorder/cPurchaseorder/FSaCPOAddEvent';
// $route['POEventEdit']              = 'document/purchaseorder/cPurchaseorder/FSaCPOEditEvent';
// $route['POEventDelete']            = 'document/purchaseorder/cPurchaseorder/FSaCPODeleteEvent';
// $route['PODataTable']              = 'document/purchaseorder/cPurchaseorder/FSxCPODataTable';
// $route['POGetShpByBch']            = 'document/purchaseorder/cPurchaseorder/FSvCPOGetShpByBch';
// $route['POAddPdtIntoTableDT']      = 'document/purchaseorder/cPurchaseorder/FSvCPOAddPdtIntoTableDT';
// $route['POEditPdtIntoTableDT']     = 'document/purchaseorder/cPurchaseorder/FSvCPOEditPdtIntoTableDT';
// $route['PORemovePdtInFile']        = 'document/purchaseorder/cPurchaseorder/FSvCPORemovePdtInFile';
// $route['PORemoveAllPdtInFile']     = 'document/purchaseorder/cPurchaseorder/FSvCPORemoveAllPdtInFile';
// $route['POAdvanceTableShowColList'] = 'document/purchaseorder/cPurchaseorder/FSvCPOAdvTblShowColList';
// $route['POAdvanceTableShowColSave'] = 'document/purchaseorder/cPurchaseorder/FSvCPOShowColSave';
// $route['POGetDTDisTableData']      = 'document/purchaseorder/cPurchaseorder/FSvCPOGetDTDisTableData';
// $route['POAddDTDisIntoTable']      = 'document/purchaseorder/cPurchaseorder/FSvCPOAddDTDisIntoTable';
// $route['PORemoveDTDisInFile']      = 'document/purchaseorder/cPurchaseorder/FSvCPORemoveDTDisInFile';
// $route['POGetHDDisTableData']      = 'document/purchaseorder/cPurchaseorder/FSvCPOGetHDDisTableData';
// $route['POAddHDDisIntoTable']      = 'document/purchaseorder/cPurchaseorder/FSvCPOAddHDDisIntoTable';
// $route['PORemoveHDDisInFile']      = 'document/purchaseorder/cPurchaseorder/FSvCPORemoveHDDisInFile';
// $route['POEditDTDis']              = 'document/purchaseorder/cPurchaseorder/FSvCPOEditDTDis';
// $route['POSetSessionVATInOrEx']    = 'document/purchaseorder/cPurchaseorder/FSvCPOSetSessionVATInOrEx';
// $route['POEditHDDis']              = 'document/purchaseorder/cPurchaseorder/FSvCPOEditHDDis';
// $route['POGetAddress']             = 'document/purchaseorder/cPurchaseorder/FSvCPOGetShipAdd';
// $route['POGetPdtBarCode']          = 'document/purchaseorder/cPurchaseorder/FSvCPOGetPdtBarCode';
// $route['POPdtAdvanceTableLoadData'] = 'document/purchaseorder/cPurchaseorder/FSvCPOPdtAdvTblLoadData';
// $route['POApprove']                = 'document/purchaseorder/cPurchaseorder/FSvCPOApprove';
// $route['POCancel']                 = 'document/purchaseorder/cPurchaseorder/FSvCPOCancel';

// TFW (ใบโอนสินค้าระหว่างคลัง)
$route['TFW/(:any)/(:any)']             = 'document/producttransferwahouse/cProducttransferwahouse/index/$1/$2';
$route['TFWFormSearchList']             = 'document/producttransferwahouse/cProducttransferwahouse/FSxCTFWFormSearchList';
$route['TFWPageAdd']                    = 'document/producttransferwahouse/cProducttransferwahouse/FSxCTFWAddPage';
$route['TFWPageEdit']                   = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWEditPage';
$route['TFWEventAdd']                   = 'document/producttransferwahouse/cProducttransferwahouse/FSaCTFWAddEvent';
$route['TFWCheckPdtTmpForTransfer']     = 'document/producttransferwahouse/cProducttransferwahouse/FSbCheckHaveProductForTransfer';
$route['TFWCheckHaveProductInDT']       = 'document/producttransferwahouse/cProducttransferwahouse/FSbCheckHaveProductInDT';
$route['TFWEventEdit']                  = 'document/producttransferwahouse/cProducttransferwahouse/FSaCTFWEditEvent';
$route['TFWEventDelete']                = 'document/producttransferwahouse/cProducttransferwahouse/FSaCTFWDeleteEvent';
$route['TFWDataTable']                  = 'document/producttransferwahouse/cProducttransferwahouse/FSxCTFWDataTable';
$route['TFWGetShpByBch']                = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWGetShpByBch';
$route['TFWAddPdtIntoTableDT']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWAddPdtIntoTableDT';
$route['TFWEditPdtIntoTableDT']         = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWEditPdtIntoTableDT';
$route['TFWRemovePdtInDTTmp']           = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWRemovePdtInDTTmp';
$route['TFWRemovePdtInFile']            = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWRemovePdtInFile';
$route['TFWRemoveAllPdtInFile']         = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWRemoveAllPdtInFile';
$route['TFWAdvanceTableShowColList']    = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWAdvTblShowColList';
$route['TFWAdvanceTableShowColSave']    = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWShowColSave';
$route['TFWGetDTDisTableData']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWGetDTDisTableData';
$route['TFWAddDTDisIntoTable']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWAddDTDisIntoTable';
$route['TFWRemoveDTDisInFile']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWRemoveDTDisInFile';
$route['TFWGetHDDisTableData']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWGetHDDisTableData';
$route['TFWAddHDDisIntoTable']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWAddHDDisIntoTable';
$route['TFWRemoveHDDisInFile']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWRemoveHDDisInFile';
$route['TFWEditDTDis']                  = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWEditDTDis';
$route['TFWEditHDDis']                  = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWEditHDDis';
$route['TFWGetAddress']                 = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWGetShipAdd';
$route['TFWGetPdtBarCode']              = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWGetPdtBarCode';
$route['TFWPdtAdvanceTableLoadData']    = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWPdtAdvTblLoadData';
$route['TFWVatTableLoadData']           = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWVatLoadData';
$route['TFWCalculateLastBill']          = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWCalculateLastBill';
$route['TFWPdtMultiDeleteEvent']        = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWPdtMultiDeleteEvent';
$route['TFWApprove']                    = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWApprove';
$route['TFWCancel']                     = 'document/producttransferwahouse/cProducttransferwahouse/FSvCTFWCancel';
$route['TFWClearDocTemForChngCdt']      = 'document/producttransferwahouse/cProducttransferwahouse/FSxCTFXClearDocTemForChngCdt';
$route['TFWCheckViaCodeForApv']         = 'document/producttransferwahouse/cProducttransferwahouse/FSxCTWXCheckViaCodeForApv';
$route['TFWCheckProductWahouse']        = 'document/producttransferwahouse/cProducttransferwahouse/FSoCTWXCheckProductWahouse';

// TFW (ใบโอนสินค้าระหว่างคลัง ตู้ VD) -
// $route['TWXVD/(:any)/(:any)']         = 'document/producttransferwahousevd/cProducttransferwahousevd/index/$1/$2';
$route['TWXVDFormSearchList']          = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTFWFormSearchList';
$route['TWXVDPageAdd']                 = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTFWAddPage';
$route['TWXVDPageEdit']                = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWEditPage';
$route['TWXVDEventAdd']                = 'document/producttransferwahousevd/cProducttransferwahousevd/FSaCTFWAddEvent';
$route['TWXVDCheckPdtTmpForTransfer']  = 'document/producttransferwahousevd/cProducttransferwahousevd/FSbCheckHaveProductForTransfer';
$route['TWXVDCheckHaveProductInDT']    = 'document/producttransferwahousevd/cProducttransferwahousevd/FSbCheckHaveProductInDT';
$route['TWXVDEventEdit']              = 'document/producttransferwahousevd/cProducttransferwahousevd/FSaCTFWEditEvent';
$route['TWXVDEventDelete']            = 'document/producttransferwahousevd/cProducttransferwahousevd/FSaCTFWDeleteEvent';
$route['TWXVDDataTable']              = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTFWDataTable';
$route['TWXVDGetShpByBch']            = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWGetShpByBch';
$route['TWXVDAddPdtIntoTableDT']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWAddPdtIntoTableDT';
$route['TWXVDEditPdtIntoTableDT']     = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWEditPdtIntoTableDT';
$route['TWXVDRemovePdtInDTTmp']       = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWRemovePdtInDTTmp';
$route['TWXVDRemovePdtInFile']        = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWRemovePdtInFile';
$route['TWXVDRemoveAllPdtInFile']     = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWRemoveAllPdtInFile';
$route['TWXVDAdvanceTableShowColList']= 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWAdvTblShowColList';
$route['TWXVDAdvanceTableShowColSave']= 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWShowColSave';
$route['TWXVDGetDTDisTableData']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWGetDTDisTableData';
$route['TWXVDAddDTDisIntoTable']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWAddDTDisIntoTable';
$route['TWXVDRemoveDTDisInFile']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWRemoveDTDisInFile';
$route['TWXVDGetHDDisTableData']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWGetHDDisTableData';
$route['TWXVDAddHDDisIntoTable']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWAddHDDisIntoTable';
$route['TWXVDRemoveHDDisInFile']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWRemoveHDDisInFile';
$route['TWXVDEditDTDis']              = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWEditDTDis';
$route['TWXVDEditHDDis']              = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWEditHDDis';
$route['TWXVDGetAddress']             = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWGetShipAdd';
$route['TWXVDGetPdtBarCode']          = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWGetPdtBarCode';
$route['TWXVDPdtAdvanceTableLoadData']= 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWPdtAdvTblLoadData';
$route['TWXVDVatTableLoadData']       = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWVatLoadData';
$route['TWXVDCalculateLastBill']      = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWCalculateLastBill';
$route['TWXVDPdtMultiDeleteEvent']    = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWPdtMultiDeleteEvent';
$route['TWXVDApprove']                = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWApprove';
$route['TWXVDCancel']                 = 'document/producttransferwahousevd/cProducttransferwahousevd/FSvCTFWCancel';
$route['TWXVDClearDocTemForChngCdt']  = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTFXClearDocTemForChngCdt';
$route['TWXVDCheckViaCodeForApv']     = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTWXCheckViaCodeForApv';
$route['TWXVDPdtDtLoadToTem']         = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTWXVDPdtDtLoadToTem';
$route['TWXVDPdtUpdateTem']           = 'document/producttransferwahousevd/cProducttransferwahousevd/FSxCTWXVDPdtUpdateTem';

//ADJVD - ใบปรับสต็อค ตู้ VD / Supawat 26-08-2020
$route['ADJSTKVD/(:any)/(:any)']         = 'document/adjuststockvd/cAdjstockvd/index/$1/$2';
$route['ADJSTKVDFormSearchList']         = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDFormSearchList';
$route['ADJSTKVDPageAdd']                = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDAddPage';
$route['ADJSTKVDPageEdit']               = 'document/adjuststockvd/cAdjstockvd/FSvCADJVDEditPage';
$route['ADJSTKVDPdtDtLoadToTem']         = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDPdtDtLoadToTem';
$route['ADJSTKVDPdtAdvanceTableLoadData']= 'document/adjuststockvd/cAdjstockvd/FSvCADJVDPdtAdvTblLoadData';
$route['ADJSTKVDEventAdd']               = 'document/adjuststockvd/cAdjstockvd/FSaCADJVDAddEvent';
$route['ADJSTKVDEditPdtIntoTableDT']     = 'document/adjuststockvd/cAdjstockvd/FSvCADJVDEditPdtIntoTableDT';
$route['ADJSTKVDCheckPdtInTmp']          = 'document/adjuststockvd/cAdjstockvd/FSbCADJVDheckHaveProductInTemp';
$route['ADJSTKVDEventEdit']              = 'document/adjuststockvd/cAdjstockvd/FSaCADJVDEditEvent';
$route['ADJSTKVDCancel']                 = 'document/adjuststockvd/cAdjstockvd/FSvCADJVDCancel';
$route['ADJSTKVDEventDelete']            = 'document/adjuststockvd/cAdjstockvd/FSaCADJVDDeleteEvent';
$route['ADJSTKVDApprove']                = 'document/adjuststockvd/cAdjstockvd/FSvCADJVDApprove';
$route['ADJSTKVDCheckHaveProductInDT']   = 'document/adjuststockvd/cAdjstockvd/FSbCADJVDCheckHaveProductInDT';
$route['ADJSTKVDDeletePDTInTemp']        = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDDeletePDTInTemp';
$route['ADJSTKVDDataTable']              = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDDataTable';
$route['ADJSTKVDRemoveMultiPdtInDTTmp']  = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDDeleteMultiPDTInTemp';
$route['ADJSTKVDRemoveItemAllInTemp']    = 'document/adjuststockvd/cAdjstockvd/FSxCADJVDDeleteItemAllInTemp';


// ADJPL (ใบปรับราคาสินค้า ตู้ locker)
$route['ADJPL/(:any)/(:any)']          = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/index/$1/$2';
$route['ADJPLFormSearchList']          = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTFWFormSearchList';
$route['ADJPLPageAdd']                 = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTFWAddPage';
$route['ADJPLPageEdit']                = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWEditPage';
$route['ADJPLEventAdd']                = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSaCTFWAddEvent';
$route['ADJPLCheckPdtTmpForTransfer']  = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSbCheckHaveProductForTransfer';
$route['ADJPLCheckHaveProductInDT']    = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSbCheckHaveProductInDT';
$route['ADJPLEventEdit']               = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSaCTFWEditEvent';
$route['ADJPLEventDelete']             = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSaCTFWDeleteEvent';
$route['ADJPLDataTable']               = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTFWDataTable';
$route['ADJPLGetShpByBch']             = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWGetShpByBch';
$route['ADJPLAddPdtIntoTableDT']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWAddPdtIntoTableDT';
$route['ADJPLEditPdtIntoTableDT']      = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWEditPdtIntoTableDT';
$route['ADJPLRemovePdtInDTTmp']        = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWRemovePdtInDTTmp';
$route['ADJPLRemovePdtInFile']         = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWRemovePdtInFile';
$route['ADJPLRemoveAllPdtInFile']      = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWRemoveAllPdtInFile';
$route['ADJPLAdvanceTableShowColList'] = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWAdvTblShowColList';
$route['ADJPLAdvanceTableShowColSave'] = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWShowColSave';
$route['ADJPLGetDTDisTableData']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWGetDTDisTableData';
$route['ADJPLAddDTDisIntoTable']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWAddDTDisIntoTable';
$route['ADJPLRemoveDTDisInFile']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWRemoveDTDisInFile';
$route['ADJPLGetHDDisTableData']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWGetHDDisTableData';
$route['ADJPLAddHDDisIntoTable']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWAddHDDisIntoTable';
$route['ADJPLRemoveHDDisInFile']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWRemoveHDDisInFile';
$route['ADJPLEditDTDis']               = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWEditDTDis';
$route['ADJPLEditHDDis']               = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWEditHDDis';
$route['ADJPLGetAddress']              = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWGetShipAdd';
$route['ADJPLGetPdtBarCode']           = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWGetPdtBarCode';
$route['ADJPLPdtAdvanceTableLoadData'] = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWPdtAdvTblLoadData';
$route['ADJPLVatTableLoadData']        = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWVatLoadData';
$route['ADJPLCalculateLastBill']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWCalculateLastBill';
$route['ADJPLPdtMultiDeleteEvent']     = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWPdtMultiDeleteEvent';
$route['ADJPLApprove']                 = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWApprove';
$route['ADJPLCancel']                  = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSvCTFWCancel';
$route['ADJPLClearDocTemForChngCdt']   = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTFXClearDocTemForChngCdt';
$route['ADJPLCheckViaCodeForApv']      = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTWXCheckViaCodeForApv';
$route['ADJPLPdtDtLoadToTem']          = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTWXVDPdtDtLoadToTem';
$route['ADJPLPdtUpdateTem']            = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCTWXVDPdtUpdateTem';
$route['ADJPLPdtGetRateInfor']         = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCADJPLPdtGetRateInfor';
$route['ADJPLPdtGetRateDTInfor']       = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCADJPLPdtGetRateDTInfor';
$route['ADJPLPdtSaveRateDTInTmp']      = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCADJPLPdtSaveRateDTInTmp';
$route['ADJPLCheckDateTime']           = 'document/rentalproductpriceadjustmentlocker/cRentalproductpriceadjustmentlocker/FSxCADJPLCheckDateTime';

// TBX (ใบโอนสินค้าระหว่างสาขา)
$route['TBX/(:any)/(:any)']         = 'document/producttransferbranch/cProducttransferbranch/index/$1/$2';
$route['TBXFormSearchList']         = 'document/producttransferbranch/cProducttransferbranch/FSxCTBXFormSearchList';
$route['TBXPageAdd']                = 'document/producttransferbranch/cProducttransferbranch/FSxCTBXAddPage';
$route['TBXPageEdit']               = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXEditPage';
$route['TBXEventAdd']               = 'document/producttransferbranch/cProducttransferbranch/FSaCTBXAddEvent';
$route['TBXCheckPdtTmpForTransfer'] = 'document/producttransferbranch/cProducttransferbranch/FSbCheckHaveProductForTransfer';
$route['TBXCheckHaveProductInDT']   = 'document/producttransferbranch/cProducttransferbranch/FSbCheckHaveProductInDT';
$route['TBXEventEdit']              = 'document/producttransferbranch/cProducttransferbranch/FSaCTBXEditEvent';
$route['TBXEventDelete']            = 'document/producttransferbranch/cProducttransferbranch/FSaCTBXDeleteEvent';
$route['TBXDataTable']              = 'document/producttransferbranch/cProducttransferbranch/FSxCTBXDataTable';
$route['TBXAddPdtIntoTableDT']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXAddPdtIntoTableDT';
$route['TBXEditPdtIntoTableDT']     = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXEditPdtIntoTableDT';
$route['TBXEditQtyInDT']            = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXEditQtyInDT';
$route['TBXRemovePdtInDTTmp']       = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXRemovePdtInDTTmp';
$route['TBXRemovePdtInFile']        = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXRemovePdtInFile';
$route['TBXRemoveAllPdtInFile']     = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXRemoveAllPdtInFile';
$route['TBXAdvanceTableShowColList']= 'document/producttransferbranch/cProducttransferbranch/FSvCTBXAdvTblShowColList';
$route['TBXAdvanceTableShowColSave']= 'document/producttransferbranch/cProducttransferbranch/FSvCTBXShowColSave';
$route['TBXGetDTDisTableData']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXGetDTDisTableData';
$route['TBXAddDTDisIntoTable']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXAddDTDisIntoTable';
$route['TBXRemoveDTDisInFile']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXRemoveDTDisInFile';
$route['TBXGetHDDisTableData']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXGetHDDisTableData';
$route['TBXAddHDDisIntoTable']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXAddHDDisIntoTable';
$route['TBXRemoveHDDisInFile']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXRemoveHDDisInFile';
$route['TBXEditDTDis']              = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXEditDTDis';
$route['TBXEditHDDis']              = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXEditHDDis';
$route['TBXGetAddress']             = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXGetShipAdd';
$route['TBXGetPdtBarCode']          = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXGetPdtBarCode';
$route['TBXPdtAdvanceTableLoadData']= 'document/producttransferbranch/cProducttransferbranch/FSvCTBXPdtAdvTblLoadData';
$route['TBXVatTableLoadData']       = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXVatLoadData';
$route['TBXCalculateLastBill']      = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXCalculateLastBill';
$route['TBXPdtMultiDeleteEvent']    = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXPdtMultiDeleteEvent';
$route['TBXApprove']                = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXApprove';
$route['TBXCancel']                 = 'document/producttransferbranch/cProducttransferbranch/FSvCTBXCancel';
$route['TBXClearDocTemForChngCdt']  = 'document/producttransferbranch/cProducttransferbranch/FSxCTFXClearDocTemForChngCdt';
$route['TBXCheckViaCodeForApv']     = 'document/producttransferbranch/cProducttransferbranch/FSxCTBXCheckViaCodeForApv';
$route['TBXCheckProductWahouse']    = 'document/producttransferbranch/cProducttransferbranch/FSoCTbxEventCheckProductWahouse';

// SalePriceAdj ใบปรับราคาขาย
$route['dcmSPA/(:any)/(:any)']             = 'document/salepriceadj/cSalePriceAdj/index/$1/$2';
$route['dcmSPAMain']                       = 'document/salepriceadj/cSalePriceAdj/FSvCSPAMainPage';
$route['dcmSPADataTable']                  = 'document/salepriceadj/cSalePriceAdj/FSvCSPADataList';
$route['dcmSPAPageAdd']                    = 'document/salepriceadj/cSalePriceAdj/FSvCSPAAddPage';
$route['dcmSPAPageEdit']                   = 'document/salepriceadj/cSalePriceAdj/FSvCSPAEditPage';
$route['dcmSPAEventEdit']                  = 'document/salepriceadj/cSalePriceAdj/FSoCSPAEditEvent';
$route['dcmSPAEventAdd']                   = 'document/salepriceadj/cSalePriceAdj/FSoCSPAAddEvent';
$route['dcmSPAEventDelete']                = 'document/salepriceadj/cSalePriceAdj/FSoCSPADeleteEvent';
$route['dcmSPAPdtPriDataTable']            = 'document/salepriceadj/cSalePriceAdj/FSvCSPAPdtPriDataList'; // Get Pdt List
$route['dcmSPAPdtPriEventAddTmp']          = 'document/salepriceadj/cSalePriceAdj/FSvCSPAPdtPriAddTmpEvent';
$route['dcmSPAPdtPriEventAddDT']           = 'document/salepriceadj/cSalePriceAdj/FSvCSPAPdtPriAddDTEvent';
$route['dcmSPAPdtPriEventDelete']          = 'document/salepriceadj/cSalePriceAdj/FSoCSPAPdtPriDeleteEvent';
$route['dcmSPAPdtPriEventDelAll']          = 'document/salepriceadj/cSalePriceAdj/FSoCSPAProductDeleteAllEvent';
$route['dcmSPAPdtPriEventUpdPriTmp']       = 'document/salepriceadj/cSalePriceAdj/FSoCSPAUpdatePriceTemp';
$route['dcmSPAGetBchComp']                 = 'document/salepriceadj/cSalePriceAdj/FSoCSPAGetBchComp';
$route['dcmSPAAdvanceTableShowColList']    = 'document/salepriceadj/cSalePriceAdj/FSvCSPAAdvTblShowColList';
$route['dcmSPAAdvanceTableShowColSave']    = 'document/salepriceadj/cSalePriceAdj/FSvCSPAShowColSave';
$route['dcmSPAOriginalPrice']              = 'document/salepriceadj/cSalePriceAdj/FSoCSPAOriginalPrice';
$route['dcmSPAPdtPriAdjust']               = 'document/salepriceadj/cSalePriceAdj/FSoCSPAPdtPriAdjustEvent';
$route['dcmSPAEventApprove']               = 'document/salepriceadj/cSalePriceAdj/FSoCSPAApproveEvent';
$route['dcmSPAUpdateStaDocCancel']         = 'document/salepriceadj/cSalePriceAdj/FSoCSPAUpdateStaDocCancel';
$route['dcmSPAPdtPriEventUpdPriPunTmp']    = 'document/salepriceadj/cSalePriceAdj/FSoCSPAUpdatePunTemp';


// จ่ายโอนสินค้า
// $route['TWO/(:any)/(:any)']            = 'document/transferwarehouseout/cTransferwarehouseout/index/$1/$2';
// $route['TWOFormSearchList']         = 'document/transferwarehouseout/cTransferwarehouseout/FSxCTWOFormSearchList';
// $route['TWOPageAdd']                = 'document/transferwarehouseout/cTransferwarehouseout/FSxCTWOAddPage';
// $route['TWOPageEdit']               = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOEditPage';
// $route['TWOEventAdd']               = 'document/transferwarehouseout/cTransferwarehouseout/FSaCTWOAddEvent';
// $route['TWOEventEdit']              = 'document/transferwarehouseout/cTransferwarehouseout/FSaCTWOEditEvent';
// $route['TWOEventDelete']            = 'document/transferwarehouseout/cTransferwarehouseout/FSaCTWODeleteEvent';
// $route['TWODataTable']              = 'document/transferwarehouseout/cTransferwarehouseout/FSxCTWODataTable';
// $route['TWOGetShpByBch']            = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOGetShpByBch';
// $route['TWOAddPdtIntoTableDT']      = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOAddPdtIntoTableDT';
// $route['TWOEditPdtIntoTableDT']     = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOEditPdtIntoTableDT';
// $route['TWORemovePdtInDTTmp']       = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWORemovePdtInDTTmp';
// $route['TWORemoveAllPdtInFile']     = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWORemoveAllPdtInFile';
// $route['TWOAdvanceTableShowColList'] = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOAdvTblShowColList';
// $route['TWOAdvanceTableShowColSave'] = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOShowColSave';
// $route['TWOGetAddress']             = 'document/transferwarehouseout/cTransferwarehouseout/TFSvCTWOGetShipAdd';
// $route['TWOGetPdtBarCode']          = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOGetPdtBarCode';
// $route['TWOPdtAdvanceTableLoadData'] = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOPdtAdvTblLoadData';
// $route['TWOVatTableLoadData']       = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOVatLoadData';
// $route['TWOCalculateLastBill']      = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOCalculateLastBill';
// $route['TWOPdtMultiDeleteEvent']    = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOPdtMultiDeleteEvent';
// $route['TWOApprove']                = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOApprove';
// $route['TWOCancel']                 = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOCancel';

// Card Import - Export (นำเข้า-ส่งออก ข้อมูลบัตร)
$route['cardmngdata/(:any)/(:any)']            = 'document/cardmngdata/cCardMngData/index/$1/$2';
$route['cardmngdataFromList']                  = 'document/cardmngdata/cCardMngData/FSvCCMDFromList';
$route['cardmngdataImpFileDataList']           = 'document/cardmngdata/cCardMngData/FSvCCMDImpFileDataList';
$route['cardmngdataExpFileDataList']           = 'document/cardmngdata/cCardMngData/FSvCCMDExpFileDataList';
$route['cardmngdataTopUpUpdateInlineOnTemp']   = 'document/cardmngdata/cCardMngData/FSxCTopUpUpdateInlineOnTemp';
$route['cardmngdataNewCardUpdateInlineOnTemp'] = 'document/cardmngdata/cCardMngData/FSxCNewCardUpdateInlineOnTemp';
$route['cardmngdataClearUpdateInlineOnTemp']   = 'document/cardmngdata/cCardMngData/FSxCClearUpdateInlineOnTemp';
$route['cardmngdataProcessImport']             = 'document/cardmngdata/cCardMngData/FSoCCMDProcessImport';
$route['cardmngdataProcessExport']             = 'document/cardmngdata/cCardMngData/FSoCCMDProcessExport';

// Call Table Temp
$route['CallTableTemp']                         = 'document/cardmngdata/cCardMngData/FSaSelectDataTableRight';
$route['CallDeleteTemp']                        = 'document/cardmngdata/cCardMngData/FSaDeleteDataTableRight';
$route['CallClearTempByTable']                  = 'document/cardmngdata/cCardMngData/FSaClearTempByTable';
$route['CallUpdateDocNoinTempByTable']          = 'document/cardmngdata/cCardMngData/FSaUpdateDocnoinTempByTable';

// Card Shift New Card(สร้างบัตรใหม่)
$route['cardShiftNewCard/(:any)/(:any)']                   = 'document/cardshiftnewcard/cCardShiftNewCard/index/$1/$2';
$route['cardShiftNewCardList']                             = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardListPage';
$route['cardShiftNewCardDataTable']                        = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardDataList';
$route['cardShiftNewCardDataSourceTable']                  = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardDataSourceList';
$route['cardShiftNewCardDataSourceTableByFile']            = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardDataSourceListByFile';
$route['cardShiftNewCardPageAdd']                          = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardAddPage';
$route['cardShiftNewCardEventAdd']                         = 'document/cardshiftnewcard/cCardShiftNewCard/FSaCardShiftNewCardAddEvent';
$route['cardShiftNewCardPageEdit']                         = 'document/cardshiftnewcard/cCardShiftNewCard/FSvCardShiftNewCardEditPage';
$route['cardShiftNewCardEventEdit']                        = 'document/cardshiftnewcard/cCardShiftNewCard/FSaCardShiftNewCardEditEvent';
$route['cardShiftNewCardEventUpdateApvDocAndCancelDoc']    = 'document/cardshiftnewcard/cCardShiftNewCard/FSaCardShiftNewCardUpdateApvDocAndCancelDocEvent';
$route['cardShiftNewCardUpdateInlineOnTemp']               = 'document/cardshiftnewcard/cCardShiftNewCard/FSxCardShiftNewCardUpdateInlineOnTemp';
$route['cardShiftNewCardInsertToTemp']                     = 'document/cardshiftnewcard/cCardShiftNewCard/FSxCardShiftNewCardInsertToTemp';
$route['cardShiftNewCardUniqueValidate/(:any)']            = 'document/cardshiftnewcard/cCardShiftNewCard/FStCardShiftNewCardUniqueValidate/$1';
$route['cardShiftNewCardChkCardCodeDup']                   = 'document/cardshiftnewcard/cCardShiftNewCard/FSnCardShiftNewCardChkCardCodeDup';
$route['generateCode_Card']                                = 'document/cardshiftnewcard/cCardShiftNewCard/FCNaGenCodeCard';
$route['dcmCardShifNewCardEventDelete']                    = 'document/cardshiftnewcard/cCardShiftNewCard/FSoCardShiftNewCardEventDelete';
$route['dcmCardShifNewCardEventDeleteMulti']               = 'document/cardshiftnewcard/cCardShiftNewCard/FSoCardShiftNewCardEventDeleteMulti';

// Card Shift Out
$route['cardShiftOut/(:any)/(:any)']                       = 'document/cardshiftout/cCardShiftOut/index/$1/$2';
$route['cardShiftOutList']                                 = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutListPage';
$route['cardShiftOutDataTable']                            = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutDataList';
$route['cardShiftOutDataSourceTable']                      = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutDataSourceList';
$route['cardShiftOutDataSourceTableByFile']                = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutDataSourceListByFile';
$route['cardShiftOutPageAdd']                              = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutAddPage';
$route['cardShiftOutEventAdd']                             = 'document/cardshiftout/cCardShiftOut/FSaCardShiftOutAddEvent';
$route['cardShiftOutPageEdit']                             = 'document/cardshiftout/cCardShiftOut/FSvCardShiftOutEditPage';
$route['cardShiftOutEventEdit']                            = 'document/cardshiftout/cCardShiftOut/FSaCardShiftOutEditEvent';
$route['cardShiftOutEventUpdateApvDocAndCancelDoc']        = 'document/cardshiftout/cCardShiftOut/FSaCardShiftOutUpdateApvDocAndCancelDocEvent';
$route['cardShiftOutEventScanner']                         = 'document/cardshiftout/cCardShiftOut/FSaCardShiftOutScannerEvent';
$route['cardShiftOutUpdateInlineOnTemp']                   = 'document/cardshiftout/cCardShiftOut/FSxCardShiftOutUpdateInlineOnTemp';
$route['cardShiftOutInsertToTemp']                         = 'document/cardshiftout/cCardShiftOut/FSxCardShiftOutInsertToTemp';
$route['cardShiftOutUniqueValidate/(:any)']                = 'document/cardshiftout/cCardShiftOut/FStCardShiftOutUniqueValidate/$1';
$route['cardShifOutDelDoc']                                = 'document/cardshiftout/cCardShiftOut/FSoCardShiftOutDelete';
$route['cardShiftOutDelDocMulti']                          = 'document/cardshiftout/cCardShiftOut/FSoCardShiftOutDeleteMulti';

// Card Shift Return
$route['cardShiftReturn/(:any)/(:any)']                    = 'document/cardshiftreturn/cCardShiftReturn/index/$1/$2';
$route['cardShiftReturnList']                              = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnListPage';
$route['cardShiftReturnDataTable']                         = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnDataList';
$route['cardShiftReturnDataSourceTable']                   = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnDataSourceList';
$route['cardShiftReturnDataSourceTableByFile']             = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnDataSourceListByFile';
$route['cardShiftReturnPageAdd']                           = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnAddPage';
$route['cardShiftReturnEventAdd']                          = 'document/cardshiftreturn/cCardShiftReturn/FSaCardShiftReturnAddEvent';
$route['cardShiftReturnPageEdit']                          = 'document/cardshiftreturn/cCardShiftReturn/FSvCardShiftReturnEditPage';
$route['cardShiftReturnEventEdit']                         = 'document/cardshiftreturn/cCardShiftReturn/FSaCardShiftReturnEditEvent';
$route['cardShiftReturnEventUpdateApvDocAndCancelDoc']     = 'document/cardshiftreturn/cCardShiftReturn/FSaCardShiftReturnUpdateApvDocAndCancelDocEvent';
$route['cardShiftReturnGetCardOnHD']                       = 'document/cardshiftreturn/cCardShiftReturn/FSaCardShiftReturnGetCardOnHD';
$route['cardShiftReturnUniqueValidate/(:any)']             = 'document/cardshiftreturn/cCardShiftReturn/FStCardShiftReturnUniqueValidate/$1';
$route['cardShiftReturnUpdateInlineOnTemp']                = 'document/cardshiftreturn/cCardShiftReturn/FSxCardShiftReturnUpdateInlineOnTemp';
$route['cardShiftReturnInsertToTemp']                      = 'document/cardshiftreturn/cCardShiftReturn/FSxCardShiftReturnInsertToTemp';
$route['cardShifReturnEventDelete']                        = 'document/cardshiftreturn/cCardShiftReturn/FSoCardShiftReturnEventDelete';
$route['cardShifReturnEventDeleteMulti']                   = 'document/cardshiftreturn/cCardShiftReturn/FSoCardShiftReturnEventDeleteMulti';
$route['cardShiftReturnEventScanner']                      = 'document/cardshiftreturn/cCardShiftReturn/FSaCardShiftReturnScannerEvent';

// Card Shift TopUp
$route['cardShiftTopUp/(:any)/(:any)']                 = 'document/cardshifttopup/cCardShiftTopUp/index/$1/$2';
$route['cardShiftTopUpList']                           = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpListPage';
$route['cardShiftTopUpDataTable']                      = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpDataList';
$route['cardShiftTopUpDataSourceTable']                = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpDataSourceList';
$route['cardShiftTopUpDataSourceTableByFile']          = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpDataSourceListByFile';
$route['cardShiftTopUpPageAdd']                        = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpAddPage';
$route['cardShiftTopUpEventAdd']                       = 'document/cardshifttopup/cCardShiftTopUp/FSaCardShiftTopUpAddEvent';
$route['cardShiftTopUpPageEdit']                       = 'document/cardshifttopup/cCardShiftTopUp/FSvCardShiftTopUpEditPage';
$route['cardShiftTopUpEventEdit']                      = 'document/cardshifttopup/cCardShiftTopUp/FSaCardShiftTopUpEditEvent';
$route['cardShiftTopUpEventUpdateApvDocAndCancelDoc']  = 'document/cardshifttopup/cCardShiftTopUp/FSaCardShiftTopUpUpdateApvDocAndCancelDocEvent';
$route['cardShiftTopUpUniqueValidate/(:any)']          = 'document/cardshifttopup/cCardShiftTopUp/FStCardShiftTopUpUniqueValidate/$1';
$route['cardShiftTopUpUpdateInlineOnTemp']             = 'document/cardshifttopup/cCardShiftTopUp/FSxCardShiftTopUpUpdateInlineOnTemp';
$route['cardShiftTopUpInsertToTemp']                   = 'document/cardshifttopup/cCardShiftTopUp/FSxCardShiftTopUpInsertToTemp';
$route['cardTopupEventDelete']                         = 'document/cardshifttopup/cCardShiftTopUp/FSoCardTopUpEventDelete';
$route['cardTopupEventDeleteMulti']                    = 'document/cardshifttopup/cCardShiftTopUp/FSoCardTopUpEventDeleteMulti';
$route['cardShiftTopUpEventScanner']                   = 'document/cardshifttopup/cCardShiftTopUp/FSaCardShiftTopUpScannerEvent';

// Card Shift Refund
$route['cardShiftRefund/(:any)/(:any)']                = 'document/cardshiftrefund/cCardShiftRefund/index/$1/$2';
$route['cardShiftRefundList']                          = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundListPage';
$route['cardShiftRefundDataTable']                     = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundDataList';
$route['cardShiftRefundDataSourceTable']               = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundDataSourceList';
$route['cardShiftRefundDataSourceTableByFile']         = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundDataSourceListByFile';
$route['cardShiftRefundPageAdd']                       = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundAddPage';
$route['cardShiftRefundEventAdd']                      = 'document/cardshiftrefund/cCardShiftRefund/FSaCardShiftRefundAddEvent';
$route['cardShiftRefundPageEdit']                      = 'document/cardshiftrefund/cCardShiftRefund/FSvCardShiftRefundEditPage';
$route['cardShiftRefundEventEdit']                     = 'document/cardshiftrefund/cCardShiftRefund/FSaCardShiftRefundEditEvent';
$route['cardShiftRefundEventUpdateApvDocAndCancelDoc'] = 'document/cardshiftrefund/cCardShiftRefund/FSaCardShiftRefundUpdateApvDocAndCancelDocEvent';
$route['cardShiftRefundUpdateInlineOnTemp']            = 'document/cardshiftrefund/cCardShiftRefund/FSxCardShiftRefundUpdateInlineOnTemp';
$route['cardShiftRefundInsertToTemp']                  = 'document/cardshiftrefund/cCardShiftRefund/FSxCardShiftRefundInsertToTemp';
$route['cardShiftRefundUniqueValidate/(:any)']         = 'document/cardshiftrefund/cCardShiftRefund/FStCardShiftRefundUniqueValidate/$1';
$route['cardShifRefundDelDoc']                         = 'document/cardshiftrefund/cCardShiftRefund/FSoCardShiftRefundDelete';
$route['cardShiftRefundDelDocMulti']                   = 'document/cardshiftrefund/cCardShiftRefund/FSoCardShiftRefundDeleteMulti';
$route['cardShiftRefundEventScanner']                  = 'document/cardshiftrefund/cCardShiftRefund/FSaCardShiftRefundScannerEvent';

//Card Shift Status
$route['cardShiftStatus/(:any)/(:any)']                 = 'document/cardshiftstatus/cCardShiftStatus/index/$1/$2';
$route['cardShiftStatusList']                           = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusListPage';
$route['cardShiftStatusDataTable']                      = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusDataList';
$route['cardShiftStatusDataSourceTable']                = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusDataSourceList';
$route['cardShiftStatusDataSourceTableByFile']          = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusDataSourceListByFile';
$route['cardShiftStatusPageAdd']                        = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusAddPage';
$route['cardShiftStatusEventAdd']                       = 'document/cardshiftstatus/cCardShiftStatus/FSaCardShiftStatusAddEvent';
$route['cardShiftStatusPageEdit']                       = 'document/cardshiftstatus/cCardShiftStatus/FSvCardShiftStatusEditPage';
$route['cardShiftStatusEventEdit']                      = 'document/cardshiftstatus/cCardShiftStatus/FSaCardShiftStatusEditEvent';
$route['cardShiftStatusEventUpdateApvDocAndCancelDoc']  = 'document/cardshiftstatus/cCardShiftStatus/FSaCardShiftStatusUpdateApvDocAndCancelDocEvent';
$route['cardShiftStatusUpdateInlineOnTemp']             = 'document/cardshiftstatus/cCardShiftStatus/FSxCardShiftStatusUpdateInlineOnTemp';
$route['cardShiftStatusInsertToTemp']                   = 'document/cardshiftstatus/cCardShiftStatus/FSxCardShiftStatusInsertToTemp';
$route['cardShiftStatusUniqueValidate/(:any)']          = 'document/cardshiftstatus/cCardShiftStatus/FStCardShiftStatusUniqueValidate/$1';
$route['cardShifStatusDelDoc']                          = 'document/cardshiftstatus/cCardShiftStatus/FSoCardShiftStatusDelete';
$route['cardShiftStatusDelDocMulti']                    = 'document/cardshiftstatus/cCardShiftStatus/FSoCardShiftStatusDeleteMulti';
$route['cardShiftStatusEventScanner']                   = 'document/cardshiftstatus/cCardShiftStatus/FSaCardShiftStatusScannerEvent';

//Card Shift Change
$route['cardShiftChange/(:any)/(:any)']                 = 'document/cardshiftchange/cCardShiftChange/index/$1/$2';
$route['cardShiftChangeList']                           = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeListPage';
$route['cardShiftChangeDataTable']                      = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeDataList';
$route['cardShiftChangeDataSourceTable']                = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeDataSourceList';
$route['cardShiftChangeDataSourceTableByFile']          = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeDataSourceListByFile';
$route['cardShiftChangePageAdd']                        = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeAddPage';
$route['cardShiftChangeEventAdd']                       = 'document/cardshiftchange/cCardShiftChange/FSaCardShiftChangeAddEvent';
$route['cardShiftChangePageEdit']                       = 'document/cardshiftchange/cCardShiftChange/FSvCardShiftChangeEditPage';
$route['cardShiftChangeEventEdit']                      = 'document/cardshiftchange/cCardShiftChange/FSaCardShiftChangeEditEvent';
$route['cardShiftChangeEventUpdateApvDocAndCancelDoc']  = 'document/cardshiftchange/cCardShiftChange/FSaCardShiftChangeUpdateApvDocAndCancelDocEvent';
$route['cardShiftChangeUpdateInlineOnTemp']             = 'document/cardshiftchange/cCardShiftChange/FSxCardShiftChangeUpdateInlineOnTemp';
$route['cardShiftChangeInsertToTemp']                   = 'document/cardshiftchange/cCardShiftChange/FSxCardShiftChangeInsertToTemp';
$route['cardShiftChangeUniqueValidate/(:any)']          = 'document/cardshiftchange/cCardShiftChange/FStCardShiftChangeUniqueValidate/$1';
$route['cardShiftChangeCardUniqueValidate/(:any)']      = 'document/cardshiftchange/cCardShiftChange/FStCardShiftChangeCardUniqueValidate/$1';
$route['cardShifChangeDelDoc']                          = 'document/cardshiftchange/cCardShiftChange/FSoCardShiftChangeDelete';
$route['cardShiftChangeDelDocMulti']                    = 'document/cardshiftchange/cCardShiftChange/FSoCardShiftChangeDeleteMulti';

//dcmTXII (ใบรับโอนสินค้า)
$route['dcmTXI/(:any)/(:any)/(:any)']  = 'document/transferreceipt/cTransferreceipt/index/$1/$2/$3';
$route['dcmTXIFormSearchList']         = 'document/transferreceipt/cTransferreceipt/FSxCTXIFormSearchList';
$route['dcmTXIPageAdd']                = 'document/transferreceipt/cTransferreceipt/FSxCTXIAddPage';
$route['dcmTXIPageEdit']               = 'document/transferreceipt/cTransferreceipt/FSvCTXIEditPage';
$route['dcmTXIEventAdd']               = 'document/transferreceipt/cTransferreceipt/FSaCTXIAddEvent';
$route['dcmTXIEventEdit']              = 'document/transferreceipt/cTransferreceipt/FSaCTXIEditEvent';
$route['dcmTXIEventDelete']            = 'document/transferreceipt/cTransferreceipt/FSaCTXIDeleteEvent';
$route['dcmTXIPdtMultiDeleteEvent']    = 'document/transferreceipt/cTransferreceipt/FSvCTXIPdtMultiDeleteEvent';
$route['dcmTXIDataTable']              = 'document/transferreceipt/cTransferreceipt/FSxCTXIDataTable';
$route['dcmTXIGetShpByBch']            = 'document/transferreceipt/cTransferreceipt/FSvCTXIGetShpByBch';
$route['dcmTXIAddPdtIntoTableDT']      = 'document/transferreceipt/cTransferreceipt/FSvCTXIAddPdtIntoTableDT';
$route['dcmTXIEditPdtIntoTableDT']     = 'document/transferreceipt/cTransferreceipt/FSvCTXIEditPdtIntoTableDT';
$route['dcmTXIRemovePdtInTemp']        = 'document/transferreceipt/cTransferreceipt/FSvCTXIRemovePdtInTemp';
$route['dcmTXIRemoveAllPdtInFile']     = 'document/transferreceipt/cTransferreceipt/FSvCTXIRemoveAllPdtInFile';
$route['dcmTXIAdvanceTableShowColList'] = 'document/transferreceipt/cTransferreceipt/FSvCTXIAdvTblShowColList';
$route['dcmTXIAdvanceTableShowColSave'] = 'document/transferreceipt/cTransferreceipt/FSvCTXIShowColSave';
$route['dcmTXIGetAddress']             = 'document/transferreceipt/cTransferreceipt/FSvCTXIGetShipAdd';
$route['dcmTXIGetPdtBarCode']          = 'document/transferreceipt/cTransferreceipt/FSvCTXIGetPdtBarCode';
$route['dcmTXIPdtAdvanceTableLoadData'] = 'document/transferreceipt/cTransferreceipt/FSvCTXIPdtAdvTblLoadData';
$route['dcmTXIVatTableLoadData']       = 'document/transferreceipt/cTransferreceipt/FSvCTXIVatLoadData';
$route['dcmTXIApprove']                = 'document/transferreceipt/cTransferreceipt/FSvCTXIApprove';
$route['dcmTXICancel']                 = 'document/transferreceipt/cTransferreceipt/FSvCTXICancel';
$route['dcmTXICalculateLastBill']      = 'document/transferreceipt/cTransferreceipt/FSvCTXICalculateLastBill';
$route['dcmTXIGetDataRefInt']          = 'document/transferreceipt/cTransferreceipt/FSvCTXIGetDataRefInt';
$route['dcmTXIClearDTTemp']            = 'document/transferreceipt/cTransferreceipt/FSvCTXIClearDTTemp';
$route['dcmTXIBrowseDataPDT']          = 'document/transferreceipt/cTransferreceipt/FSvCTXIBrowseDataPDT';
$route['dcmTXIBrowseDataPDTTable']      = 'document/transferreceipt/cTransferreceipt/FSvCTXIBrowseDataTXIPDTTable';

//Adjust Stock (ใบปรับสต็อค)
$route['adjStkSub/(:any)/(:any)']         = 'document/adjuststocksub/cAdjustStockSub/index/$1/$2';
$route['adjStkSubFormSearchList']         = 'document/adjuststocksub/cAdjustStockSub/FSxCAdjStkSubFormSearchList';
$route['adjStkSubDataTable']              = 'document/adjuststocksub/cAdjustStockSub/FSxCAdjStkSubDataTable';
$route['adjStkSubPageAdd']                = 'document/adjuststocksub/cAdjustStockSub/FSxCAdjStkSubAddPage';
$route['adjStkSubPageEdit']               = 'document/adjuststocksub/cAdjustStockSub/FSvCAdjStkSubEditPage';
$route['adjStkSubEventAdd']               = 'document/adjuststocksub/cAdjustStockSub/FSaCAdjStkSubAddEvent';
$route['adjStkSubEventEdit']              = 'document/adjuststocksub/cAdjustStockSub/FSaCAdjStkSubEditEvent';
$route['adjStkSubEventDelete']            = 'document/adjuststocksub/cAdjustStockSub/FSaCASTDeleteEvent';
$route['adjStkSubApproved']               = 'document/adjuststocksub/cAdjustStockSub/FSaCASTApprove';
$route['adjStkSubCancel']                 = 'document/adjuststocksub/cAdjustStockSub/FSvCAdjStkSubCancel';
$route['adjStkSubRemovePdtInDTTmp']       = 'document/adjuststocksub/cAdjustStockSub/FSvCAdjStkSubRemovePdtInDTTmp';
$route['adjStkSubPdtAdvanceTableLoadData']= 'document/adjuststocksub/cAdjustStockSub/FSvCAdjStkSubPdtAdvTblLoadData';
$route['adjStkSubPdtMultiDeleteEvent']    = 'document/adjuststocksub/cAdjustStockSub/FSvCAdjStkSubPdtMultiDeleteEvent';
$route['docASTEventAddProducts']           = 'document/adjuststocksub/cAdjustStockSub/FSaCASTEventAddProducts';
$route['docASTEventEditInLine']            = 'document/adjuststocksub/cAdjustStockSub/FSxCASTEditInLine';
$route['docASTEventUpdateDateTime']        = 'document/adjuststocksub/cAdjustStockSub/FSaCASTUpdateDateTime';

//Credit Note (ใบลดหนี้)
$route['creditNote/(:any)/(:any)']         = 'document/creditnote/cCreditNote/index/$1/$2';
$route['creditNoteFormSearchList']         = 'document/creditnote/cCreditNote/FSxCCreditNoteFormSearchList';
$route['creditNotePageAdd']                = 'document/creditnote/cCreditNote/FSxCCreditNoteAddPage';
$route['creditNotePageEdit']               = 'document/creditnote/cCreditNote/FSvCCreditNoteEditPage';
$route['creditNoteEventAdd']               = 'document/creditnote/cCreditNote/FSaCCreditNoteAddEvent';
$route['creditNoteCheckHaveProductInDT']   = 'document/creditnote/cCreditNote/FSbCheckHaveProductInDT';
$route['creditNoteEventDeleteMultiDoc']    = 'document/creditnote/cCreditNote/FSoCreditNoteDeleteMultiDoc';
$route['creditNoteEventDeleteDoc']         = 'document/creditnote/cCreditNote/FSoCreditNoteDeleteDoc';
$route['creditNoteUniqueValidate/(:any)']  = 'document/creditnote/cCreditNote/FStCCreditNoteUniqueValidate/$1';
$route['creditNoteEventEdit']              = 'document/creditnote/cCreditNote/FSaCCreditNoteEditEvent';
$route['creditNoteDataTable']              = 'document/creditnote/cCreditNote/FSxCCreditNoteDataTable';
$route['creditNoteGetShpByBch']            = 'document/creditnote/cCreditNote/FSvCCreditNoteGetShpByBch';
$route['creditNoteAddPdtIntoTableDT']      = 'document/creditnote/cCreditNote/FSvCCreditNoteAddPdtIntoTableDT';
$route['creditNoteEditPdtIntoTableDT']     = 'document/creditnote/cCreditNote/FSvCCreditNoteEditPdtIntoTableDT';
$route['creditNoteRemovePdtInDTTmp']       = 'document/creditnote/cCreditNote/FSvCCreditNoteRemovePdtInDTTmp';
$route['creditNoteRemovePdtInFile']         = 'document/creditnote/cCreditNote/FSvCCreditNoteRemovePdtInFile';
$route['creditNoteRemoveAllPdtInFile']      = 'document/creditnote/cCreditNote/FSvCCreditNoteRemoveAllPdtInFile';
$route['creditNoteAdvanceTableShowColList'] = 'document/creditnote/cCreditNote/FSvCCreditNoteAdvTblShowColList';
$route['creditNoteAdvanceTableShowColSave'] = 'document/creditnote/cCreditNote/FSvCCreditNoteShowColSave';
$route['creditNoteClearTemp']              = 'document/creditnote/cCreditNote/FSaCreditNoteClearTemp';
$route['creditNoteGetDTDisTableData']      = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteGetDTDisTableData';
$route['creditNoteAddDTDisIntoTable']      = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteAddDTDisIntoTable';
$route['creditNoteGetHDDisTableData']      = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteGetHDDisTableData';
$route['creditNoteAddHDDisIntoTable']      = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteAddHDDisIntoTable';
$route['creditNoteAddEditDTDis']           = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteAddEditDTDis';
$route['creditNoteAddEditHDDis']           = 'document/creditnote/cCreditNoteDisChgModal/FSvCCreditNoteAddEditHDDis';
$route['creditNoteGetPdtBarCode']          = 'document/creditnote/cCreditNote/FSvCCreditNoteGetPdtBarCode';
$route['creditNotePdtAdvanceTableLoadData']= 'document/creditnote/cCreditNote/FSvCCreditNotePdtAdvTblLoadData';
$route['creditNoteNonePdtAdvanceTableLoadData']= 'document/creditnote/cCreditNote/FSvCCreditNoteNonePdtAdvTblLoadData';
$route['creditNoteCalculateLastBill']      = 'document/creditnote/cCreditNote/FSvCCreditNoteCalculateLastBill';
$route['creditNotePdtMultiDeleteEvent']    = 'document/creditnote/cCreditNote/FSvCCreditNotePdtMultiDeleteEvent';
$route['creditNoteApprove']                = 'document/creditnote/cCreditNote/FSvCCreditNoteApprove';
$route['creditNoteCancel']                 = 'document/creditnote/cCreditNote/FSvCCreditNoteCancel';
$route['creditNoteClearDocTemForChngCdt']  = 'document/creditnote/cCreditNote/FSxCTFXClearDocTemForChngCdt';
$route['creditNoteRefPIHDList']            = 'document/creditnote/cCreditNoteRefPIModal/FSoCreditNoteRefPIHDList';
$route['creditNoteRefPIDTList']            = 'document/creditnote/cCreditNoteRefPIModal/FSoCreditNoteRefPIDTList';
$route['creditNoteDisChgHDList']           = 'document/creditnote/cCreditNoteDisChgModal/FSoCreditNoteDisChgHDList';
$route['creditNoteDisChgDTList']           = 'document/creditnote/cCreditNoteDisChgModal/FSoCreditNoteDisChgDTList';
$route['creditNoteCalEndOfBillNonePdt']    = 'document/creditnote/cCreditNote/FSoCreditNoteCalEndOfBillNonePdt';
$route['creditNoteChangeSPLAffectNewVAT']  = 'document/creditnote/cCreditNote/FSoCCreditNoteChangeSPLAffectNewVAT';
$route['creditNoteCallRefIntDoc']                        = 'document/creditnote/cCreditNote/FSoCCreditNoteCallRefIntDoc';
$route['creditNoteCallRefIntDocDataTable']               = 'document/creditnote/cCreditNote/FSoCCreditNoteCallRefIntDocDataTable';
$route['creditNoteCallRefIntDocDetailDataTable']         = 'document/creditnote/cCreditNote/FSoCCreditNoteCallRefIntDocDetailDataTable';
$route['creditNoteCallRefIntDocInsertDTToTemp']          = 'document/creditnote/cCreditNote/FSoCCreditNoteCallRefIntDocInsertDTToTemp';

//ใบจ่ายโอนระหว่างคลัง - ใบจ่ายโอนระหว่างสาขา - ใบเบิกออก
$route['dcmTXO/(:any)/(:any)/(:any)']       = 'document/transferout/cTransferout/index/$1/$2/$3';
$route['dcmTXOFormSearchList']              = 'document/transferout/cTransferout/FSvCTXOFormSearchList';
$route['dcmTXODataTable']                   = 'document/transferout/cTransferout/FSxCTXODataTable';
$route['dcmTXOPageAdd']                     = 'document/transferout/cTransferout/FSoCTXOAddPage';
$route['dcmTXOPageEdit']                    = 'document/transferout/cTransferout/FSoCTXOEditPage';
$route['dcmTXOPdtAdvanceTableLoadData']     = 'document/transferout/cTransferout/FSoCTXOPdtAdvTblLoadData';
$route['dcmTXOVatTableLoadData']            = 'document/transferout/cTransferout/FSoCTXOVatLoadData';
$route['dcmTXOCalculateLastBill']           = 'document/transferout/cTransferout/FSoCTXOCalculateLastBill';
$route['dcmTXOAdvanceTableShowColList']     = 'document/transferout/cTransferout/FSoCTXOAdvTblShowColList';
$route['dcmTXOAdvanceTableShowColSave']     = 'document/transferout/cTransferout/FSoCTXOShowColSave';
$route['dcmTXOAddPdtIntoTableDTTmp']        = 'document/transferout/cTransferout/FSoCTXOAddPdtIntoTableDTTmp';
$route['dcmTXOEditPdtIntoTableDTTmp']       = 'document/transferout/cTransferout/FSoCTXOEditPdtIntoTableDTTmp';
$route['dcmTXORemovePdtInDTTmp']            = 'document/transferout/cTransferout/FSoCTXORemovePdtInDTTmp';
$route['dcmTXORemoveMultiPdtInDTTmp']       = 'document/transferout/cTransferout/FSoCTXORemovePdtMultiInDTTmp';
$route['dcmTXOChkHavePdtForTnf']            = 'document/transferout/cTransferout/FSoCTXOChkHavePdtForTnf';
$route['dcmTXOEventAdd']                    = 'document/transferout/cTransferout/FSoCTXOAddEventDoc';
$route['dcmTXOEventEdit']                   = 'document/transferout/cTransferout/FSoCTXOEditEventDoc';
$route['dcmTXOEventDelete']                 = 'document/transferout/cTransferout/FSoCTXODeleteEventDoc';
$route['dcmTXOApproveDoc']                  = 'document/transferout/cTransferout/FSoCTXOApproveDocument';
$route['dcmTXOCancelDoc']                   = 'document/transferout/cTransferout/FSoCTXOCancelDoc';
$route['dcmTXOPrintDoc']                    = 'document/transferout/cTransferout/FSoCTXOPrintDoc';
$route['dcmTXOClearDataDocTemp']            = 'document/transferout/cTransferout/FSoCTXOClearDataDocTemp';
$route['dcmTXOCheckViaCodeForApv']          = 'document/transferout/cTransferout/FSoCTXOCheckViaCodeForApv';

//ใบตรวจนับสินค้า
$route['dcmAST/(:any)/(:any)']                  = 'document/adjuststock/cAdjustStock/index/$1/$2';
$route['dcmASTFormSearchList']                  = 'document/adjuststock/cAdjustStock/FSvCASTFormSearchList';
$route['dcmASTDataTable']                       = 'document/adjuststock/cAdjustStock/FSoCASTDataTable';
$route['dcmASTEventDelete']                     = 'document/adjuststock/cAdjustStock/FSoCASTDeleteEventDoc';
$route['dcmASTPageAdd']                         = 'document/adjuststock/cAdjustStock/FSoCASTAddPage';
$route['dcmASTPageEdit']                        = 'document/adjuststock/cAdjustStock/FSoCASTEditPage';
$route['dcmASTPdtAdvanceTableLoadData']         = 'document/adjuststock/cAdjustStock/FSoCASTPdtAdvTblLoadData';
$route['dcmASTAdvanceTableShowColList']         = 'document/adjuststock/cAdjustStock/FSoCASTAdvTblShowColList';
$route['dcmASTAdvanceTableShowColSave']         = 'document/adjuststock/cAdjustStock/FSoCASTShowColSave';
$route['dcmASTCheckPdtTmpForTransfer']          = 'document/adjuststock/cAdjustStock/FSbCheckHaveProductForTransfer';
$route['dcmASTAddPdtIntoTableDT']               = 'document/adjuststock/cAdjustStock/FSvCASTAddPdtIntoTableDT';
$route['dcmASTEventAdd']                        = 'document/adjuststock/cAdjustStock/FSaCASTAddEvent';
$route['dcmASTEventEdit']                       = 'document/adjuststock/cAdjustStock/FSaCASTEditEvent';
$route['dcmASTEditPdtIntoTableDT']              = 'document/adjuststock/cAdjustStock/FSvCASTEditPdtIntoTableDT';
$route['dcmASTRemovePdtInDTTmp']                = 'document/adjuststock/cAdjustStock/FSvCASTRemovePdtInDTTmp';
$route['dcmASTPdtMultiDeleteEvent']             = 'document/adjuststock/cAdjustStock/FSvCASTPdtMultiDeleteEvent';
$route['dcmASTUpdateInline']                    = 'document/adjuststock/cAdjustStock/FSoCASTUpdateDataInline';
$route['dcmASTCancel']                          = 'document/adjuststock/cAdjustStock/FSvCASTCancel';
$route['dcmASTApprove']                         = 'document/adjuststock/cAdjustStock/FSvCASTApprove';
$route['dcmASTHQApprove']                       = 'document/adjuststock/cAdjustStock/FSvCASTHQApprove';
$route['dcmASTGetPdtBarCode']                   = 'document/adjuststock/cAdjustStock/FSvCASTGetPdtBarCode';
$route['docAdjStkEventAddProducts']             = 'document/adjuststock/cAdjustStock/FSvCAdjStkEventAddProducts';
$route['docAdjStkEventAddProductsByBarCode']    = 'document/adjuststock/cAdjustStock/FSvCAdjStkEventAddProductsByBarCode';
$route['docAdjStkUpdateAdj']                    = 'document/adjuststock/cAdjustStock/FSvCAdjStkEventUpdateAdjust';
$route['dcmASTCheckDocAllAproveINBCH']          = 'document/adjuststock/cAdjustStock/FSxCASTCheckDocAllAproveINBCH';
$route['dcmASTSendNotiForDocNotApv']            = 'document/adjuststock/cAdjustStock/FSxCASTSendNotiForDocNotApv';


//ใบรับของ-ใบซื้อสินค้า/บริการ
$route['dcmPI/(:any)/(:any)']           = 'document/purchaseinvoice/cPurchaseInvoice/index/$1/$2';
$route['dcmPIFormSearchList']           = 'document/purchaseinvoice/cPurchaseInvoice/FSvCPIFormSearchList';
$route['dcmPIDataTable']                = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIDataTable';
$route['dcmPIPageAdd']                  = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIAddPage';
$route['dcmPIPageEdit']                 = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIEditPage';
$route['dcmPIPdtAdvanceTableLoadData']  = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIPdtAdvTblLoadData';
$route['dcmPIVatTableLoadData']         = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIVatLoadData';
$route['dcmPICalculateLastBill']        = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPICalculateLastBill';
$route['dcmPIEventDelete']              = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIDeleteEventDoc';
$route['dcmPIAdvanceTableShowColList']  = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIAdvTblShowColList';
$route['dcmPIAdvanceTableShowColSave']  = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIAdvTalShowColSave';
$route['dcmPIAddPdtIntoDTDocTemp']      = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIAddPdtIntoDocDTTemp';
$route['dcmPIEditPdtIntoDTDocTemp']     = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIEditPdtIntoDocDTTemp';
$route['dcmPIChkHavePdtForDocDTTemp']   = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIChkHavePdtForDocDTTemp';
$route['dcmPIEventAdd']                 = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIAddEventDoc';
$route['dcmPIEventEdit']                = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIEditEventDoc';
$route['dcmPIRemovePdtInDTTmp']         = 'document/purchaseinvoice/cPurchaseInvoice/FSvCPIRemovePdtInDTTmp';
$route['dcmPIRemovePdtInDTTmpMulti']    = 'document/purchaseinvoice/cPurchaseInvoice/FSvCPIRemovePdtInDTTmpMulti';
$route['dcmPICancelDocument']           = 'document/purchaseinvoice/cPurchaseInvoice/FSvCPICancelDocument';
$route['dcmPIApproveDocument']          = 'document/purchaseinvoice/cPurchaseInvoice/FSvCPIApproveDocument';
$route['dcmPISerachAndAddPdtIntoTbl']   = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPISearchAndAddPdtIntoTbl';
$route['dcmPIClearDataDocTemp']         = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIClearDataInDocTemp';
$route['dcmPIDisChgHDList']             = 'document/purchaseinvoice/cPurchaseInvoiceDisChgModal/FSoCPIDisChgHDList';
$route['dcmPIDisChgDTList']             = 'document/purchaseinvoice/cPurchaseInvoiceDisChgModal/FSoCPIDisChgDTList';
$route['dcmPIAddEditDTDis']             = 'document/purchaseinvoice/cPurchaseInvoiceDisChgModal/FSoCPIAddEditDTDis';
$route['dcmPIAddEditHDDis']             = 'document/purchaseinvoice/cPurchaseInvoiceDisChgModal/FSoCPIAddEditHDDis';
$route['docPIEventCallEndOfBill']       = 'document/purchaseinvoice/cPurchaseInvoice/FSaPICallEndOfBillOnChaheVat';
$route['dcmPIChangeSPLAffectNewVAT']    = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIChangeSPLAffectNewVAT';
$route['dcmPIMovePODTToDocTmp']         = 'document/purchaseinvoice/cPurchaseInvoice/FSoCPIMovePODTToDocTmp';

//การกำหนดอัตราค่าเช่า (Locker)
$route['dcmPriRentLocker/(:any)/(:any)']    = 'document/pricerentlocker/cPriceRentLocker/index/$1/$2';
$route['dcmPriRntLkFormSearchList']         = 'document/pricerentlocker/cPriceRentLocker/FSvCPriRntLkFormSearchList';
$route['dcmPriRntLkDataTable']              = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkDataTable';
$route['dcmPriRntLkPageAdd']                = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkAddPage';
$route['dcmPriRntLkPageEdit']               = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkEditPage';
$route['dcmPriRntLkLoadDataDT']             = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkLoadDataDT';
$route['dcmPriRntLkEventAdd']               = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkEventAdd';
$route['dcmPriRntLkEventEdit']              = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkEventEdit';
$route['dcmPriRntLkEvemtDeleteSingle']      = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkEventDelSingle';
$route['dcmPriRntLkEvemtDeleteMulti']       = 'document/pricerentlocker/cPriceRentLocker/FSoCPriRntLkEventDelMultiple';

//การกำหนดคูปอง
$route['dcmCouponSetup/(:any)/(:any)']      = 'document/couponsetup/cCouponSetup/index/$1/$2';
$route['dcmCouponSetupFormSearchList']      = 'document/couponsetup/cCouponSetup/FSvCCPHFormSearchList';
$route['dcmCouponSetupGetDataTable']        = 'document/couponsetup/cCouponSetup/FSoCCPHGetDataTable';
$route['dcmCouponSetupPageAdd']             = 'document/couponsetup/cCouponSetup/FSoCCPHCallPageAdd';
$route['dcmCouponSetupPageEdit']            = 'document/couponsetup/cCouponSetup/FSoCCPHCallPageEdit';
$route['dcmCouponSetupPageDetailDT']        = 'document/couponsetup/cCouponSetup/FSoCCPHCallPageDetailDT';
$route['dcmCouponSetupEventAddCouponToDT']  = 'document/couponsetup/cCouponSetup/FSoCCPHCallEventAddCouponToDT';
$route['dcmCouponSetupEventAddCouponToDTDef']  = 'document/couponsetup/cCouponSetup/FSoCCPHCallEventAddCouponDefault';
$route['dcmCouponSetupEventAdd']            = 'document/couponsetup/cCouponSetup/FSoCCPHEventAdd';
$route['dcmCouponSetupEventEdit']           = 'document/couponsetup/cCouponSetup/FSoCCPHEventEdit';
$route['dcmCouponSetupEventDelete']         = 'document/couponsetup/cCouponSetup/FSoCCPHEventDelete';
$route['dcmCouponSetupEvenApprove']         = 'document/couponsetup/cCouponSetup/FSaCCPHEventAppove';
$route['dcmCouponSetupEvenCancel']          = 'document/couponsetup/cCouponSetup/FSaCCPHEventCancel';
$route['dcmCouponSetupChangStatusAfApv']    = 'document/couponsetup/cCouponSetup/FSaCCPHChangStatusAfApv';
$route['dcmCouponSetupEvenCopy']            = 'document/couponsetup/cCouponSetup/FSaCCPHCopyDoc';

//ใบเติมสินค้า
$route['TWXVD/(:any)/(:any)']                      = 'document/topupVending/cTopupVending/index/$1/$2';
$route['TopupVendingList']                         = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingList';
$route['TopupVendingDataTable']                    = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingDataTable';
$route['TopupVendingCallPageAdd']                  = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingAddPage';
$route['TopupVendingEventAdd']                     = 'document/topupVending/cTopupVending/FSaCTUVTopupVendingAddEvent';
$route['TopupVendingCallPageEdit']                 = 'document/topupVending/cTopupVending/FSvCTUVTopupVendingEditPage';
$route['TopupVendingEventEdit']                    = 'document/topupVending/cTopupVending/FSaCTUVTopupVendingEditEvent';
$route['TopupVendingDocApprove']                   = 'document/topupVending/cTopupVending/FStCTopUpVendingDocApprove';
$route['TopupVendingDocCancel']                    = 'document/topupVending/cTopupVending/FStCTopUpVendingDocCancel';
$route['TopupVendingDelDoc']                       = 'document/topupVending/cTopupVending/FStTopUpVendingDeleteDoc';
$route['TopupVendingDelDocMulti']                  = 'document/topupVending/cTopupVending/FStTopUpVendingDeleteMultiDoc';
$route['TopupVendingGetWahByShop']                 = 'document/topupVending/cTopupVending/FStGetWahByShop';
$route['TopupVendingUniqueValidate']               = 'document/topupVending/cTopupVending/FStCTopUpVendingUniqueValidate/$1';
$route['TopupVendingInsertPdtLayoutToTmp']         = 'document/topupVending/cTopupVending/FSaCTUVTopupVendingInsertPdtLayoutToTmp';
$route['TopupVendingGetPdtLayoutDataTableInTmp']   = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingGetPdtLayoutDataTableInTmp';
$route['TopupVendingUpdatePdtLayoutInTmp']         = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingUpdatePdtLayoutInTmp';
$route['TopupVendingDeletePdtLayoutInTmp']         = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingDeletePdtLayoutInTmp';
$route['TopupVendingDeleteMultiPdtLayoutInTmp']    = 'document/topupVending/cTopupVending/FSxCTUVTopupVendingDeleteMultiPdtLayoutInTmp';
$route['dcmTVDEventDelPdtValueZero']               = 'document/topupVending/cTopupVending/FSxCTVDDelPdtValueZero';

//ใบนำฝาก
$route['deposit/(:any)/(:any)']            = 'document/deposit/cDeposit/index/$1/$2';
$route['depositList']                      = 'document/deposit/cDeposit/FSxCDepositList';
$route['depositDataTable']                 = 'document/deposit/cDeposit/FSxCDepositDataTable';
$route['depositCallPageAdd']               = 'document/deposit/cDeposit/FSxCDepositAddPage';
$route['depositEventAdd']                  = 'document/deposit/cDeposit/FSaCDepositAddEvent';
$route['depositCallPageEdit']              = 'document/deposit/cDeposit/FSvCDepositEditPage';
$route['depositEventEdit']                 = 'document/deposit/cDeposit/FSaCDepositEditEvent';
$route['depositUniqueValidate']            = 'document/deposit/cDeposit/FStCDepositUniqueValidate/$1';
$route['depositDocApprove']                = 'document/deposit/cDeposit/FStCDepositDocApprove';
$route['depositDocCancel']                 = 'document/deposit/cDeposit/FStCDepositDocCancel';
$route['depositDelDoc']                    = 'document/deposit/cDeposit/FStDepositDeleteDoc';
$route['depositDelDocMulti']               = 'document/deposit/cDeposit/FStDepositDeleteMultiDoc';
$route['depositInsertCashToTmp']           = 'document/deposit/cDepositCash/FSaCDepositInsertCashToTmp';
$route['depositGetCashInTmp']              = 'document/deposit/cDepositCash/FSxCDepositGetCashInTmp';
$route['depositUpdateCashInTmp']           = 'document/deposit/cDepositCash/FSxCDepositUpdateCashInTmp';
$route['depositDeleteCashInTmp']           = 'document/deposit/cDepositCash/FSxCDepositDeleteCashInTmp';
$route['depositClearCashInTmp']            = 'document/deposit/cDepositCash/FSxCDepositClearCashInTmp';
$route['depositInsertChequeToTmp']         = 'document/deposit/cDepositCheque/FSaCDepositInsertChequeToTmp';
$route['depositGetChequeInTmp']            = 'document/deposit/cDepositCheque/FSxCDepositGetChequeInTmp';
$route['depositUpdateChequeInTmp']         = 'document/deposit/cDepositCheque/FSxCDepositUpdateChequeInTmp';
$route['depositDeleteChequeInTmp']         = 'document/deposit/cDepositCheque/FSxCDepositDeleteChequeInTmp';
$route['depositClearChequeInTmp']          = 'document/deposit/cDepositCheque/FSxCDepositClearChequeInTmp';
$route['depositFindShiftValue']            = 'document/deposit/cDeposit/FSaCDepositFindShiftValue';

//เงื่อนไขการแลกแต้ม
$route['dcmRDH/(:any)/(:any)']             = 'document/conditionredeem/cConditionRedeem/index/$1/$2';
$route['dcmRDHFormSearchList']             = 'document/conditionredeem/cConditionRedeem/FSvCRDHFormSearchList';
$route['dcmRDHGetDataTable']               = 'document/conditionredeem/cConditionRedeem/FSoCRDHGetDataTable';
$route['dcmRDHPageAdd']                    = 'document/conditionredeem/cConditionRedeem/FSoCRDHCallPageAdd';
$route['dcmRDHPageEdit']                   = 'document/conditionredeem/cConditionRedeem/FSoCRDHCallPageEdit';
$route['dcmRDHPageDetailDT']               = 'document/conditionredeem/cConditionRedeem/FSoCRDHCallPageDetailDT';
$route['dcmRDHEventAddCouponToDT']         = 'document/conditionredeem/cConditionRedeem/FSoCRDHCallEventAddCouponToDT';
$route['dcmRDHEventAdd']                   = 'document/conditionredeem/cConditionRedeem/FSoCRDHEventAdd';
$route['dcmRDHEventEdit']                  = 'document/conditionredeem/cConditionRedeem/FSoCRDHEventEdit';
$route['dcmRDHEventDelete']                = 'document/conditionredeem/cConditionRedeem/FSoCRDHEventDelete';
$route['dcmRDHEvenApprove']                = 'document/conditionredeem/cConditionRedeem/FSaCRDHEventAppove';
$route['dcmRDHEvenCancel']                 = 'document/conditionredeem/cConditionRedeem/FSaCRDHEventCancel';
$route['dcmRDHAddPdtIntoDTDocTemp']        = 'document/conditionredeem/cConditionRedeem/FSaCRDHEventAddPdtTemp';
$route['dcmRDHPdtAdvanceTableLoadData']    = 'document/conditionredeem/cConditionRedeem/FSaCRDHECallEventPdtTemp';
$route['dcmRDHPdtAdvanceTableDeleteSingle'] = 'document/conditionredeem/cConditionRedeem/FSaCRDHPdtAdvanceTableDeleteSingle';
$route['dcmRDHPdtClearConditionRedeemTmp']  = 'document/conditionredeem/cConditionRedeem/FSxCRDHClearConditionRedeemTmp';
$route['dcmRDHSaveGrpNameDTTemp']           = 'document/conditionredeem/cConditionRedeem/FSaCRDHInsertGrpNamePDTToTemp';
$route['dcmRDHGetGrpDTTemp']                = 'document/conditionredeem/cConditionRedeem/FSaCRDHGetGrpNamePDTToTemp';
$route['dcmRDHSetPdtGrpDTTemp']             = 'document/conditionredeem/cConditionRedeem/FSaCRDHSetPdtGrpDTTemp';
$route['dcmRDHDelGroupInDTTemp']            = 'document/conditionredeem/cConditionRedeem/FSaCRDHDelGroupInDTTemp';
$route['dcmRDHChangStatusAfApv']            = 'document/conditionredeem/cConditionRedeem/FSaCRDHChangStatusAfApv';

/*===== Begin โปรโมชั่น ===================================================================*/
// Master
$route['promotion/(:any)/(:any)']           = 'document/promotion/cPromotion/index/$1/$2';
$route['promotionList']                     = 'document/promotion/cPromotion/FSxCPromotionList';
$route['promotionDataTable']                = 'document/promotion/cPromotion/FSxCPromotionDataTable';
$route['promotionCallPageAdd']              = 'document/promotion/cPromotion/FSxCPromotionAddPage';
$route['promotionEventAdd']                 = 'document/promotion/cPromotion/FSaCPromotionAddEvent';
$route['promotionCallPageEdit']             = 'document/promotion/cPromotion/FSvCPromotionEditPage';
$route['promotionEventEdit']                = 'document/promotion/cPromotion/FSaCPromotionEditEvent';
$route['promotionUniqueValidate']           = 'document/promotion/cPromotion/FStCPromotionUniqueValidate/$1';
$route['promotionDocApprove']               = 'document/promotion/cPromotion/FStCPromotionDocApprove';
$route['promotionDocCancel']                = 'document/promotion/cPromotion/FStCPromotionDocCancel';
$route['promotionDelDoc']                   = 'document/promotion/cPromotion/FStPromotionDeleteDoc';
$route['promotionDelDocMulti']              = 'document/promotion/cPromotion/FStPromotionDeleteMultiDoc';
$route['promotionEvenCopy']                 = 'document/promotion/cPromotion/FStPromotionCopyDoc';

// Step1 PMTDT Tmp
$route['promotionStep1ConfirmPmtDtInTmp']               = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionConfirmPmtDtInTmp';
$route['promotionStep1CancelPmtDtInTmp']                = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionCancelPmtDtInTmp';
$route['promotionStep1PmtDtInTmpToBin']                 = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionPmtDtInTmpToBin';
$route['promotionStep1DeletePmtDtInTmp']                = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionDeletePmtDtInTmp';
$route['promotionStep1DeletePmtDtInTmpLot']             = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionDeletePmtDtInTmpLot';
$route['promotionStep1DeleteMorePmtDtInTmp']            = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionDeleteMorePmtDtInTmp';
$route['promotionStep1ClearPmtDtInTmp']                 = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionClearPmtDtInTmp';
// Step1 Group Name
$route['promotionStep1GetPmtDtGroupNameInTmp']          = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionGetPmtDtGroupNameInTmp';
$route['promotionStep1DeletePmtDtGroupNameInTmp']       = 'document/promotion/cPromotionStep1PmtDt/FSxCPromotionDeletePmtDtGroupNameInTmp';
$route['promotionStep1UniqueValidateGroupName']         = 'document/promotion/cPromotionStep1PmtDt/FStCPromotionPmtDtUniqueValidate';
// Step1 PDT Tmp
$route['promotionStep1InsertPmtPdtDtToTmp']             = 'document/promotion/cPromotionStep1PmtPdtDt/FSaCPromotionInsertPmtPdtDtToTmp';
$route['promotionStep1GetPmtPdtDtInTmp']                = 'document/promotion/cPromotionStep1PmtPdtDt/FSxCPromotionGetPmtPdtDtInTmp';
$route['promotionStep1GetPmtPriDtInTmp']                = 'document/promotion/cPromotionStep1PmtPdtDt/FSxCPromotionGetPmtPriDtInTmp';
$route['promotionStep1UpdatePmtPdtDtInTmp']             = 'document/promotion/cPromotionStep1PmtPdtDt/FSxCPromotionUpdatePmtPdtDtInTmp';
$route['promotionStep1GetLotDetail']                    = 'document/promotion/cPromotionStep1PmtPdtDt/FSxCPromotionGetLotDetail';
$route['docPromotionLotInsertTmp']                      = 'document/promotion/cPromotionStep1PmtPdtDt/FSxCPromotionInsertLotTmp';
// Step1 Brand Tmp
$route['promotionStep1InsertPmtBrandDtToTmp']           = 'document/promotion/cPromotionStep1PmtBrandDt/FSaCPromotionInsertPmtBrandDtToTmp';
$route['promotionStep1InsertPmtPriDtToTmp']             = 'document/promotion/cPromotionStep1PmtBrandDt/FSaCPromotionInsertPmtPriDtToTmp';
$route['promotionStep1GetPmtBrandDtInTmp']              = 'document/promotion/cPromotionStep1PmtBrandDt/FSxCPromotionGetPmtBrandDtInTmp';
$route['promotionStep1UpdatePmtBrandDtInTmp']           = 'document/promotion/cPromotionStep1PmtBrandDt/FSxCPromotionUpdatePmtBrandDtInTmp';
$route['promotionStep1GetLotBrandDetail']               = 'document/promotion/cPromotionStep1PmtBrandDt/FSxCPromotionGetLotBrandDetail';
// Step1 Import PmtDt from Excel
$route['promotionStep1ImportExcelPmtDtToTmp']           = 'document/promotion/cPromotionStep1ImportPmtExcel/FStPromotionImportFromExcel';
// Step2 Group Name
$route['promotionStep2GetPmtDtGroupNameInTmp']          = 'document/promotion/cPromotionStep2PmtDt/FSxCPromotionGetPmtDtGroupNameInTmp';
$route['promotionStep2GetPmtCBInTmp']                   = 'document/promotion/cPromotionStep2PmtDt/FStCPromotionGetPmtCBInTmp';
$route['promotionStep2GetPmtCGInTmp']                   = 'document/promotion/cPromotionStep2PmtDt/FStCPromotionGetPmtCGInTmp';
// Step3 PmtCB
$route['promotionStep3GetPmtCBInTmp']                   = 'document/promotion/cPromotionStep3PmtCB/FSxCPromotionGetPmtCBInTmp';
$route['promotionStep3InsertPmtCBToTmp']                = 'document/promotion/cPromotionStep3PmtCB/FSaCPromotionInsertPmtCBToTmp';
$route['promotionStep3UpdatePmtCBInTmp']                = 'document/promotion/cPromotionStep3PmtCB/FSxCPromotionUpdatePmtCBInTmp';
$route['promotionStep3DeletePmtCBInTmp']                = 'document/promotion/cPromotionStep3PmtCB/FSaCPromotionDeletePmtCBInTmp';
$route['promotionStep3UpdatePmtCGAndPmtCBPerAvgDisInTmp'] = 'document/promotion/cPromotionStep3PmtCB/FSxCPromotionUpdatePmtCGAndPmtCBPerAvgDisInTmp';
// Step3 PmtCG
$route['promotionStep3GetPmtCGInTmp']                   = 'document/promotion/cPromotionStep3PmtCG/FSxCPromotionGetPmtCGInTmp';
$route['promotionStep3InsertPmtCGToTmp']                = 'document/promotion/cPromotionStep3PmtCG/FSaCPromotionInsertPmtCGToTmp';
$route['promotionStep3UpdatePmtCGInTmp']                = 'document/promotion/cPromotionStep3PmtCG/FSxCPromotionUpdatePmtCGInTmp';
$route['promotionStep3UpdatePmtCGPgtStaGetTypeInTmp']   = 'document/promotion/cPromotionStep3PmtCG/FSxCPromotionUpdatePmtCGPgtStaGetTypeInTmp';
$route['promotionStep3DeletePmtCGInTmp']                = 'document/promotion/cPromotionStep3PmtCG/FSaCPromotionDeletePmtCGInTmp';
$route['promotionStep3ClearPmtCGInTmp']                 = 'document/promotion/cPromotionStep3PmtCG/FSxCPromotionClearPmtCGInTmp';
// Step3 PmtCB With PmtCG
$route['promotionStep3GetPmtCBWithPmtCGInTmp']          = 'document/promotion/cPromotionStep3PmtCB/FSxCPromotionGetPmtCBWithPmtCGInTmp';
$route['promotionStep3InsertPmtCBAndPmtCGToTmp']        = 'document/promotion/cPromotionStep3PmtCB/FSaCPromotionInsertPmtCBAndPmtCGToTmp';
$route['promotionStep3DeletePmtCBAndPmtCGInTmpBySeq']   = 'document/promotion/cPromotionStep3PmtCB/FSaCPromotionDeletePmtCBAndPmtCGInTmpBySeq';
$route['promotionStep3GetPmtCBAndPmtCGPgtPerAvgDisInTmp'] = 'document/promotion/cPromotionStep3PmtCB/FStCPromotionGetPmtCBAndPmtCGPgtPerAvgDisInTmp';
// Step3 Coupon
$route['promotionStep3InsertOrUpdateCouponToTmp']       = 'document/promotion/cPromotionStep3Coupon/FSaCPromotionInsertOrUpdateCouponToTmp';
$route['promotionStep3GetCouponInTmp']                  = 'document/promotion/cPromotionStep3Coupon/FStCPromotionGetCouponInTmp';
$route['promotionStep3DeleteCouponInTmp']               = 'document/promotion/cPromotionStep3Coupon/FSxCPromotionDeleteCouponInTmp';
// Step3 Point
$route['promotionStep3InsertOrUpdatePointToTmp']        = 'document/promotion/cPromotionStep3Point/FSaCPromotionInsertOrUpdatePointToTmp';
$route['promotionStep3GetPointInTmp']                   = 'document/promotion/cPromotionStep3Point/FStCPromotionGetPointInTmp';
$route['promotionStep3DeletePointInTmp']                = 'document/promotion/cPromotionStep3Point/FSxCPromotionDeletePointInTmp';
// Step4 PriceGroup Condition
$route['promotionStep4GetPriceGroupConditionInTmp']     = 'document/promotion/cPromotionStep4PriceGroupCondition/FSxCPromotionGetPdtPmtHDCstPriInTmp';
$route['promotionStep4InsertPriceGroupConditionToTmp']  = 'document/promotion/cPromotionStep4PriceGroupCondition/FSaCPromotionInsertPriceGroupToTmp';
$route['promotionStepeUpdatePriceGroupConditionInTmp']  = 'document/promotion/cPromotionStep4PriceGroupCondition/FSxCPromotionUpdatePriceGroupInTmp';
$route['promotionStep4DeletePriceGroupConditionInTmp']  = 'document/promotion/cPromotionStep4PriceGroupCondition/FSxCPromotionDeletePriceGroupInTmp';
// Step4 Branch Condition
$route['promotionStep4GetBchConditionInTmp']            = 'document/promotion/cPromotionStep4BchCondition/FSxCPromotionGetBchConditionInTmp';
$route['promotionStep4InsertBchConditionToTmp']         = 'document/promotion/cPromotionStep4BchCondition/FSaCPromotionInsertBchConditionToTmp';
$route['promotionStepeUpdateBchConditionInTmp']         = 'document/promotion/cPromotionStep4BchCondition/FSxCPromotionUpdateBchConditionInTmp';
$route['promotionStep4DeleteBchConditionInTmp']         = 'document/promotion/cPromotionStep4BchCondition/FSxCPromotionDeleteBchConditionInTmp';
// Step4 Channel Condition
$route ['promotionStep4GetChnConditionInTmp'] = 'document/promotion/cPromotionStep4ChnCondition/FSxCPromotionGetHDChnInTmp';
$route ['promotionStep4InsertChnConditionToTmp'] = 'document/promotion/cPromotionStep4ChnCondition/FSaCPromotionInsertChnToTmp';
$route ['promotionStepeUpdateChnConditionInTmp'] = 'document/promotion/cPromotionStep4ChnCondition/FSxCPromotionUpdateChnInTmp';
$route ['promotionStep4DeleteChnConditionInTmp'] = 'document/promotion/cPromotionStep4ChnCondition/FSxCPromotionDeleteChnInTmp';
// Step4 Payment Type Condition
$route ['promotionStep4GetRcvConditionInTmp'] = 'document/promotion/cPromotionStep4RcvCondition/FSxCPromotionGetHDRcvInTmp';
$route ['promotionStep4InsertRcvConditionToTmp'] = 'document/promotion/cPromotionStep4RcvCondition/FSaCPromotionInsertRcvToTmp';
$route ['promotionStepeUpdateRcvConditionInTmp'] = 'document/promotion/cPromotionStep4RcvCondition/FSxCPromotionUpdateRcvInTmp';
$route ['promotionStep4DeleteRcvConditionInTmp'] = 'document/promotion/cPromotionStep4RcvCondition/FSxCPromotionDeleteRcvInTmp';
// Step4 Customer Level Condition
$route ['promotionStep4GetCstConditionInTmp'] = 'document/promotion/cPromotionStep4CstCondition/FSxCPromotionGetHDCstInTmp';
$route ['promotionStep4InsertCstConditionToTmp'] = 'document/promotion/cPromotionStep4CstCondition/FSaCPromotionInsertCstToTmp';
$route ['promotionStepeUpdateCstConditionInTmp'] = 'document/promotion/cPromotionStep4CstCondition/FSxCPromotionUpdateCstInTmp';
$route ['promotionStep4DeleteCstConditionInTmp'] = 'document/promotion/cPromotionStep4CstCondition/FSxCPromotionDeleteCstInTmp';
// Step4 Promotion On Promotion Condition
$route ['promotionStep4GetPnpConditionInTmp']           = 'document/promotion/cPromotionStep4PnpCondition/FSxCPromotionGetHDPnpInTmp';
$route ['promotionStep4InsertPnpConditionToTmp']        = 'document/promotion/cPromotionStep4PnpCondition/FSaCPromotionInsertPnpToTmp';
$route ['promotionStepeUpdatePnpConditionInTmp']        = 'document/promotion/cPromotionStep4PnpCondition/FSxCPromotionUpdatePnpInTmp';
$route ['promotionStep4DeletePnpConditionInTmp']        = 'document/promotion/cPromotionStep4PnpCondition/FSxCPromotionDeletePnpInTmp';

// Step5 Check and Confirm
$route['promotionStep5GetCheckAndConfirmPage']          = 'document/promotion/cPromotionStep5CheckAndConfirm/FSxCPromotionGetCheckAndConfirmPage';
$route['promotionStep5UpdatePmtCBStaCalSumInTmp']       = 'document/promotion/cPromotionStep5CheckAndConfirm/FSxCPromotionUpdatePmtCBStaCalSumInTmp';
$route['promotionStep5UpdatePmtCGStaGetEffectInTmp']    = 'document/promotion/cPromotionStep5CheckAndConfirm/FSxCPromotionUpdatePmtCGStaGetEffectInTmp';
// Create Promotion By Import
$route['promotionImportExcelToTmp']                     = 'document/promotion/cPromotionStep1ImportPmtExcel/FStPromotionImportExcelToTmp';
$route['promotionGetImportExcelMainPage']               = 'document/promotion/cPromotionStep1ImportPmtExcel/FStPromotionGetImportExcelMainPage';
$route['promotionImportExcelTempToMaster']              = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportTempToMaster';
$route['promotionClearImportExcelInTmp']                = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportClearInTemp';
// Summary HD
// Product Group
$route['promotionGetImportExcelPdtGroupInTmp']          = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetPdtGroupInTmp';
$route['promotionGetImportExcelPdtGroupDataJsonInTmp']  = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetPdtGroupDataJsonInTmp';
$route['promotionDeleteImportExcelPdtGroupInTempBySeq'] = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportDeletePdtGroupInTempBySeqNo';
$route['promotionGetImportExcelPdtGroupStaInTmp']       = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetStaPdtGroupInTemp';
// Condition-กลุ่มซื้อ
$route['promotionGetImportExcelCBInTmp']                = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetCBInTmp';
$route['promotionGetImportExcelCBDataJsonInTmp']        = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetCBDataJsonInTmp';
$route['promotionDeleteImportExcelCBInTempBySeq']       = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportDeleteCBInTempBySeqNo';
$route['promotionGetImportExcelCBStaInTmp']             = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetStaCBInTemp';
// Option1-กลุ่มรับ(กรณีส่วนลด)
$route['promotionGetImportExcelCGInTmp']                = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetCGInTmp';
$route['promotionGetImportExcelCGDataJsonInTmp']        = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetCGDataJsonInTmp';
$route['promotionDeleteImportExcelCGInTempBySeq']       = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportDeleteCGInTempBySeqNo';
$route['promotionGetImportExcelCGStaInTmp']             = 'document/promotion/cPromotionStep1ImportPmtExcel/FSoCImportGetStaCGInTemp';
// Option2-กลุ่มรับ(กรณีcoupon)
// Option3-กลุ่มรับ(กรณีแต้ม)
/*===== End โปรโมชั่น ====================================================================*/

//ใบจ่ายโอน - เนลว์ 06/03/2020
$route['TWO/(:any)/(:any)/(:any)']                          = 'document/transferwarehouseout/cTransferwarehouseout/index/$1/$2/$3';
$route['TWOTransferwarehouseoutList']                       = 'document/transferwarehouseout/cTransferwarehouseout/FSxCTWOTransferwarehouseoutList';
$route['TWOTransferwarehouseoutDataTable']                  = 'document/transferwarehouseout/cTransferwarehouseout/FSxCTWOTransferwarehouseoutDataTable';
$route['TWOTransferwarehouseoutPageAdd']                    = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOTransferwarehouseoutPageAdd';
$route['TWOTransferwarehouseoutPageEdit']                   = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWOTransferwarehouseoutPageEdit';
$route['TWOTransferwarehouseoutPdtAdvanceTableLoadData']    = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOPdtAdvTblLoadData';
$route['TWOTransferAdvanceTableShowColList']                = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOAdvTblShowColList';
$route['TWOTransferAdvanceTableShowColSave']                = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOAdvTalShowColSave';
$route['TWOTransferwarehouseoutAddPdtIntoDTDocTemp']        = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOAddPdtIntoDocDTTemp';
$route['TWOTransferwarehouseoutRemovePdtInDTTmp']           = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWORemovePdtInDTTmp';
$route['TWOTransferwarehouseoutRemovePdtInDTTmpMulti']      = 'document/transferwarehouseout/cTransferwarehouseout/FSvCTWORemovePdtInDTTmpMulti';
$route['dcmTWOEventEdit']                                   = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOEditEventDoc';
$route['dcmTWOEventAdd']                                    = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOAddEventDoc';
$route['TWOTransferwarehouseoutEventDelete']                = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWODeleteEventDoc';
$route['TWOTransferwarehouseoutEventCencel']                = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOEventCancel';
$route['TWOTransferwarehouseoutEventEditInline']            = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOEditPdtIntoDocDTTemp';
$route['TWOTransferwarehouseoutEventApproved']              = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOApproved';
$route['TWOTransferwarehouseoutCheckProductWahouse']        = 'document/transferwarehouseout/cTransferwarehouseout/FSoCTWOEventCheckProductWahouse';


//ใบจ่ายโอน - สาขา
$route['docTransferBchOut/(:any)/(:any)']                   = 'document/transfer_branch_out/cTransferBchOut/index/$1/$2';
$route['docTransferBchOutList']                             = 'document/transfer_branch_out/cTransferBchOut/FSxCTransferBchOutList';
$route['docTransferBchOutDataTable']                        = 'document/transfer_branch_out/cTransferBchOut/FSxCTransferBchOutDataTable';
$route['docTransferBchOutCallPageAdd']                      = 'document/transfer_branch_out/cTransferBchOut/FSxCTransferBchOutAddPage';
$route['docTransferBchOutEventAdd']                         = 'document/transfer_branch_out/cTransferBchOut/FSaCTransferBchOutAddEvent';
$route['docTransferBchOutCallPageEdit']                     = 'document/transfer_branch_out/cTransferBchOut/FSvCTransferBchOutEditPage';
$route['docTransferBchOutEventEdit']                        = 'document/transfer_branch_out/cTransferBchOut/FSaCTransferBchOutEditEvent';
$route['docTransferBchOutUniqueValidate']                   = 'document/transfer_branch_out/cTransferBchOut/FStCTransferBchOutUniqueValidate/$1';
$route['docTransferBchOutDocApprove']                       = 'document/transfer_branch_out/cTransferBchOut/FStCTransferBchOutDocApprove';
$route['docTransferBchOutDocCancel']                        = 'document/transfer_branch_out/cTransferBchOut/FStCTransferBchOutDocCancel';
$route['docTransferBchOutDelDoc']                           = 'document/transfer_branch_out/cTransferBchOut/FStTransferBchOutDeleteDoc';
$route['docTransferBchOutDelDocMulti']                      = 'document/transfer_branch_out/cTransferBchOut/FStTransferBchOutDeleteMultiDoc';
$route['docTransferBchOutInsertPdtToTmp']                   = 'document/transfer_branch_out/cTransferBchOutPdt/FSaCTransferBchOutInsertPdtToTmp';
$route['docTransferBchOutGetPdtInTmp']                      = 'document/transfer_branch_out/cTransferBchOutPdt/FSxCTransferBchOutGetPdtInTmp';
$route['docTransferBchOutUpdatePdtInTmp']                   = 'document/transfer_branch_out/cTransferBchOutPdt/FSxCTransferBchOutUpdatePdtInTmp';
$route['docTransferBchOutDeletePdtInTmp']                   = 'document/transfer_branch_out/cTransferBchOutPdt/FSxCTransferBchOutDeletePdtInTmp';
$route['docTransferBchOutDeleteMorePdtInTmp']               = 'document/transfer_branch_out/cTransferBchOutPdt/FSxCTransferBchOutDeleteMorePdtInTmp';
$route['docTransferBchOutClearPdtInTmp']                    = 'document/transfer_branch_out/cTransferBchOutPdt/FSxCTransferBchOutClearPdtInTmp';
$route['docTransferBchOutGetPdtColumnList']                 = 'document/transfer_branch_out/cTransferBchOutPdt/FStCTransferBchOutGetPdtColumnList';
$route['docTransferBchOutUpdatePdtColumn']                  = 'document/transfer_branch_out/cTransferBchOutPdt/FStCTransferBchOutUpdatePdtColumn';
$route['docTransferBchOutRefIntDoc']                        = 'document/transfer_branch_out/cTransferBchOutPdt/FSoCTransferBchOutRefIntDoc';
$route['docTransferBchOutRefIntDocDataTable']               = 'document/transfer_branch_out/cTransferBchOutPdt/FSoCTransferBchOutCallRefIntDocDataTable';
$route['docTransferBchOutRefIntDocDetailDataTable']         = 'document/transfer_branch_out/cTransferBchOutPdt/FSoCTransferBchOutCallRefIntDocDetailDataTable';
$route['docTransferBchOutRefIntDocInsertDTToTemp']          = 'document/transfer_branch_out/cTransferBchOutPdt/FSoCTransferBchOutCallRefIntDocInsertDTToTemp';
$route['docTransferBchOutCheckProductWahouse']              = 'document/transfer_branch_out/cTransferBchOutPdt/FSoCTransferBchOutEventCheckProductWahouse';
$route['docTransferBchOutCheckWahouseInBCH']                = 'document/transfer_branch_out/cTransferBchOut/FSoCTransferBchOutCheckWahouseInBCH';

//ใบรับโอน - สาขา เนลว์ 20/03/2020
$route['docTBI/(:any)/(:any)/(:any)']          = 'document/transferreceiptbranch/cTransferreceiptbranch/index/$1/$2/$3';
$route['docTBIPageList']                       = 'document/transferreceiptbranch/cTransferreceiptbranch/FSxCTBIPageList';
$route['docTBIPageDataTable']                  = 'document/transferreceiptbranch/cTransferreceiptbranch/FSxCTBIPageDataTable';
$route['docTBIPageAdd']                        = 'document/transferreceiptbranch/cTransferreceiptbranch/FSvCTBIPageAdd';
$route['docTBIPageEdit']                       = 'document/transferreceiptbranch/cTransferreceiptbranch/FSvCTBIPageEdit';
$route['docTBIPagePdtAdvanceTableLoadData']    = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIPagePdtAdvTblLoadData';
$route['docTBIPageTableShowColList']           = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIPageAdvTblShowColList';
$route['docTBIEventTableShowColSave']          = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventAdvTalShowColSave';
$route['docTBIEventAddPdtIntoDTDocTemp']       = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventAddPdtIntoDocDTTemp';
$route['docTBIEventRemovePdtInDTTmp']          = 'document/transferreceiptbranch/cTransferreceiptbranch/FSvCTBIEventRemovePdtInDTTmp';
$route['docTBIEventRemovePdtInDTTmpMulti']     = 'document/transferreceiptbranch/cTransferreceiptbranch/FSvCTBIEventRemovePdtInDTTmpMulti';
$route['docTBIEventEdit']                      = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventEdit';
$route['docTBIEventAdd']                       = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventAdd';
$route['docTBIEventDelete']                    = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventDelete';
$route['docTBIEventCencel']                    = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventCancel';
$route['docTBIEventEditInline']                = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventEditPdtIntoDocDTTemp';
$route['docTBIPageSelectPDTInCN']              = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIPageSelectPDTInCN';
$route['docTBIEventApproved']                  = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventApproved';
$route['docTBIEventClearTemp']                 = 'document/transferreceiptbranch/cTransferreceiptbranch/FSxCTBIEventClearTemp';
$route['docTBIEventGetPdtIntDTBch']            = 'document/transferreceiptbranch/cTransferreceiptbranch/FSoCTBIEventGetPdtIntDTBch';

//ใบรับโอน - คลังสินค้า - วัฒน์ 20/02/2020
$route['TWI/(:any)/(:any)']                         = 'document/transferreceiptNew/cTransferreceiptNew/index/$1/$2';
$route['TWITransferReceiptList']                    = 'document/transferreceiptNew/cTransferreceiptNew/FSxCTWITransferReceiptList';
$route['TWITransferReceiptDataTable']               = 'document/transferreceiptNew/cTransferreceiptNew/FSxCTWITransferReceiptDataTable';
$route['TWITransferReceiptPageAdd']                 = 'document/transferreceiptNew/cTransferreceiptNew/FSvCTWITransferReceiptPageAdd';
$route['TWITransferReceiptPageEdit']                = 'document/transferreceiptNew/cTransferreceiptNew/FSvCTWITransferReceiptPageEdit';
$route['TWITransferReceiptPdtAdvanceTableLoadData'] = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIPdtAdvTblLoadData';
$route['TWITransferAdvanceTableShowColList']        = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIAdvTblShowColList';
$route['TWITransferAdvanceTableShowColSave']        = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIAdvTalShowColSave';
$route['TWITransferReceiptAddPdtIntoDTDocTemp']     = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIAddPdtIntoDocDTTemp';
$route['TWITransferReceiptRemovePdtInDTTmp']        = 'document/transferreceiptNew/cTransferreceiptNew/FSvCTWIRemovePdtInDTTmp';
$route['TWITransferReceiptRemovePdtInDTTmpMulti']   = 'document/transferreceiptNew/cTransferreceiptNew/FSvCTWIRemovePdtInDTTmpMulti';
$route['dcmTWIEventEdit']                           = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIEditEventDoc';
$route['dcmTWIEventAdd']                            = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIAddEventDoc';
$route['TWITransferReceiptEventDelete']             = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIDeleteEventDoc';
$route['TWITransferReceiptEventCencel']             = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIEventCancel';
$route['TWITransferReceiptEventEditInline']         = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIEditPdtIntoDocDTTemp';
$route['TWITransferReceiptSelectPDTInCN']           = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWISelectPDTInCN';
$route['TWITransferReceiptEventApproved']           = 'document/transferreceiptNew/cTransferreceiptNew/FSoCTWIApproved';
$route['TWITransferReceiptRefDoc']                  = 'document/transferreceiptNew/cTransferreceiptNew/FSaCTWIRefDoc';
$route['TWITransferReceiptRefGetWah']               = 'document/transferreceiptNew/cTransferreceiptNew/FSaCTWIGetWahRefDoc';

//ใบรับเข้า - คลังสินค้า - วัฒน์ 20/02/2020
$route['TXOOut/(:any)/(:any)']                         = 'document/transferreceiptOut/cTransferreceiptOut/index/$1/$2';
$route['TXOOutTransferReceiptList']                    = 'document/transferreceiptOut/cTransferreceiptOut/FSxCTWOTransferReceiptList';
$route['TXOOutTransferReceiptDataTable']               = 'document/transferreceiptOut/cTransferreceiptOut/FSxCTWOTransferReceiptDataTable';
$route['TXOOutTransferReceiptPageAdd']                 = 'document/transferreceiptOut/cTransferreceiptOut/FSvCTWOTransferReceiptPageAdd';
$route['TXOOutTransferReceiptPageEdit']                = 'document/transferreceiptOut/cTransferreceiptOut/FSvCTWOTransferReceiptPageEdit';
$route['TXOOutTransferReceiptPdtAdvanceTableLoadData'] = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOPdtAdvTblLoadData';
$route['TXOOutTransferAdvanceTableShowColList']        = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOAdvTblShowColList';
$route['TXOOutTransferAdvanceTableShowColSave']        = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOAdvTalShowColSave';
$route['TXOOutTransferReceiptAddPdtIntoDTDocTemp']     = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOAddPdtIntoDocDTTemp';
$route['TXOOutTransferReceiptAddPdtIntoDTFhnTemp']     = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOAddPdtIntoDocDTFhnTemp';
$route['TXOOutTransferReceiptRemovePdtInDTTmp']        = 'document/transferreceiptOut/cTransferreceiptOut/FSvCTWORemovePdtInDTTmp';
$route['TXOOutTransferReceiptRemovePdtInDTTmpMulti']   = 'document/transferreceiptOut/cTransferreceiptOut/FSvCTWORemovePdtInDTTmpMulti';
$route['dcmTXOOutEventEdit']                           = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOEditEventDoc';
$route['dcmTXOOutEventAdd']                            = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOAddEventDoc';
$route['TXOOutTransferReceiptEventDelete']             = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWODeleteEventDoc';
$route['TXOOutTransferReceiptEventCencel']             = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOEventCancel';
$route['TXOOutTransferReceiptEventEditInline']         = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOEditPdtIntoDocDTTemp';
$route['TXOOutTransferReceiptSelectPDTInCN']           = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOSelectPDTInCN';
$route['TXOOutTransferReceiptEventApproved']           = 'document/transferreceiptOut/cTransferreceiptOut/FSoCTWOApproved';

//หาราคาที่มีส่วนลด
$route['GetPriceAlwDiscount']                          = 'document/creditnote/cCreditNoteDisChgModal/FSaCCENGetPriceAlwDiscount';

//เอกสารใบตรวจนับ - รวม สินค้าคงคลัง
$route['docSM/(:any)/(:any)']                           = 'document/adjuststocksum/cAdjustStockSum/index/$1/$2';
$route['docSMFormSearchList']                           = 'document/adjuststocksum/cAdjustStockSum/FSvCSMFormSearchList';
$route['docSMDataTable']                                = 'document/adjuststocksum/cAdjustStockSum/FSoCSMGetDataTable';
$route['docSMPageAdd']                                  = 'document/adjuststocksum/cAdjustStockSum/FSoCSMCallPageAdd';
$route['docSMPageEdit']                                 = 'document/adjuststocksum/cAdjustStockSum/FSoCSMCallPageEdit';
$route['docSMEventCallPdtStkSum']                       = 'document/adjuststocksum/cAdjustStockSum/FSoCSMEventCallPdtStkSum';
$route['docSMTableLoadData']                            = 'document/adjuststocksum/cAdjustStockSum/FSoCSMCallTableLoadData';
$route['docSMEventEditInLine']                          = 'document/adjuststocksum/cAdjustStockSum/FSoCSMEventEditInLine';
$route['docSMEventRemovePdtInDTTmp']                    = 'document/adjuststocksum/cAdjustStockSum/FSvCEventRemovePdtInDTTmp';
$route['docSMEventRemoveMultiPdtInDTTmp']               = 'document/adjuststocksum/cAdjustStockSum/FSvCEventRemoveMultiPdtInDTTmp';
$route['docSMEventClearTemp']                           = 'document/adjuststocksum/cAdjustStockSum/FSxCSMEventClearTemp';
$route['docSMEventDelete']                              = 'document/adjuststocksum/cAdjustStockSum/FSaCSMEventDelete';
$route['docSMEventAdd']                                 = 'document/adjuststocksum/cAdjustStockSum/FSoCSMEventAdd';
$route['docSMEventEdit']                                = 'document/adjuststocksum/cAdjustStockSum/FSoCSMEventEdit';
$route['docSMEventApprove']                             = 'document/adjuststocksum/cAdjustStockSum/FSaCSMEventAppove';
$route['docSMEventHQApprove']                           = 'document/adjuststocksum/cAdjustStockSum/FSaCSMEventHQAppove';
$route['docSMEventCancel']                              = 'document/adjuststocksum/cAdjustStockSum/FSaCSMEventCancel';

//ใบคืนสินค้า - ตู้สินค้า : Napat(Jame) 03/09/2020
$route['docTVO/(:any)/(:any)']                         = 'document/TransferVendingOut/cTransferVendingOut/index/$1/$2';
$route['docTVOPageList']                               = 'document/TransferVendingOut/cTransferVendingOut/FSvCTVOPageList';
$route['docTVOPageDataTable']                          = 'document/TransferVendingOut/cTransferVendingOut/FSvCTVOPageDataTable';
$route['docTVOPageAdd']                                = 'document/TransferVendingOut/cTransferVendingOut/FSvCTVOPageAdd';
$route['docTVOPageEdit']                               = 'document/TransferVendingOut/cTransferVendingOut/FSvCTVOPageEdit';
$route['docTVOEventAdd']                               = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOEventAdd';
$route['docTVOEventEdit']                              = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOEventEdit';
$route['docTVOEventMoveDTFromRefInt']                  = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOEventMoveDTFromRefInt';
$route['docTVOEventInsertPdtLayoutToTmp']              = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOEventInsertPdtLayoutToTmp';
$route['dcmTVOEventEditInline']                        = 'document/TransferVendingOut/cTransferVendingOut/FSvCTVOEventEditInline';
$route['docTVOEventApprove']                           = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOEventApprove';
$route['docTVOPageDataTablePdtLayout']                 = 'document/TransferVendingOut/cTransferVendingOut/FSaCTVOPageDataTablePdtLayout';
$route['docTVOEventDeletePdtLayoutInTmp']              = 'document/TransferVendingOut/cTransferVendingOut/FSxCTVOEventDeletePdtLayoutInTmp';
$route['docTVOEventCancleDoc']                         = 'document/TransferVendingOut/cTransferVendingOut/FStCTVOEventDocCancel';
/* ยังไม่ได้ตรวจสอบ อาจเป็น route ขยะ Napat(Jame) */
$route['TopupVendingDelDoc']                           = 'document/topupVending/cTopupVending/FStTopUpVendingDeleteDoc';
$route['TopupVendingDelDocMulti']                      = 'document/topupVending/cTopupVending/FStTopUpVendingDeleteMultiDoc';
$route['TopupVendingGetWahByShop']                     = 'document/topupVending/cTopupVending/FStGetWahByShop';
$route['TopupVendingUniqueValidate']                   = 'document/topupVending/cTopupVending/FStCTopUpVendingUniqueValidate/$1';

//ใบกับกำภาษีอย่างย่อ
$route ['dcmTXIN/(:any)/(:any)']                         = 'document/taxinvoice/cTaxinvoice/index/$1/$2';
$route ['dcmTXINLoadList']                               = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadList';
$route ['dcmTXINLoadListDataTable']                      = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadListDatatable';
$route ['dcmTXINLoadPageAdd']                            = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadPageAdd';
$route ['dcmTXINLoadDatatable']                          = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatable';
$route ['dcmTXINLoadDatatableABB']                       = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatableABB';
$route ['dcmTXINCheckABB']                               = 'document/taxinvoice/cTaxinvoice/FSaCTAXCheckABBNumber';
$route ['dcmTXINLoadAddress']                            = 'document/taxinvoice/cTaxinvoice/FSaCTAXLoadAddress';
$route ['dcmTXINCheckTaxNO']                             = 'document/taxinvoice/cTaxinvoice/FSaCTAXCheckTaxno';
$route ['dcmTXINLoadDatatableTaxNO']                     = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatableTaxno';
$route ['dcmTXINLoadDatatableCustomerAddress']           = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatableCustomerAddress';
$route ['dcmTXINCustomerAddress']                        = 'document/taxinvoice/cTaxinvoice/FSaCTAXLoadCustomerAddress';
$route ['dcmTXINApprove']                                = 'document/taxinvoice/cTaxinvoice/FSaCTAXApprove';
$route ['dcmTXINLoadDatatableTax']                       = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatableTax';
$route ['dcmTXINLoadDatatableDTTax']                     = 'document/taxinvoice/cTaxinvoice/FSvCTAXLoadDatatableDTTax';
$route ['dcmTXINUpdateWhenApprove']                      = 'document/taxinvoice/cTaxinvoice/FSxCTAXUpdateWhenApprove';
$route ['dcmTXINCallTaxNoLastDoc']                       = 'document/taxinvoice/cTaxinvoice/FSxCTAXCallTaxNoLastDoc';
$route ['dcmTXINCheckBranchInComp']                      = 'document/taxinvoice/cTaxinvoice/FSxCTAXCheckBranchInComp';
$route ['docTAXEventApvETax']                            = 'document/taxinvoice/cTaxinvoice/FSaCTAXEventApvETax';

//ใบกับกำภาษีอย่างย่อ (FC)
$route ['dcmTXFC/(:any)/(:any)']                         = 'document/taxinvoicefc/cTaxinvoicefc/index/$1/$2';
$route ['dcmTXFCLoadList']                               = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadList';
$route ['dcmTXFCLoadListDataTable']                      = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadListDatatable';
$route ['dcmTXFCLoadPageAdd']                            = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadPageAdd';
$route ['dcmTXFCLoadDatatable']                          = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatable';
$route ['dcmTXFCLoadDatatableABB']                       = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatableABB';
$route ['dcmTXFCCheckABB']                               = 'document/taxinvoicefc/cTaxinvoicefc/FSaCTXFCheckABBNumber';
$route ['dcmTXFCLoadAddress']                            = 'document/taxinvoicefc/cTaxinvoicefc/FSaCTXFLoadAddress';
$route ['dcmTXFCCheckTaxNO']                             = 'document/taxinvoicefc/cTaxinvoicefc/FSaCTXFCheckTaxno';
$route ['dcmTXFCLoadDatatableTaxNO']                     = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatableTaxno';
$route ['dcmTXFCLoadDatatableCustomerAddress']           = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatableCustomerAddress';
$route ['dcmTXFCCustomerAddress']                        = 'document/taxinvoicefc/cTaxinvoicefc/FSaCTXFLoadCustomerAddress';
$route ['dcmTXFCApprove']                                = 'document/taxinvoicefc/cTaxinvoicefc/FSaCTXFApprove';
$route ['dcmTXFCLoadDatatableTax']                       = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatableTax';
$route ['dcmTXFCLoadDatatableDTTax']                     = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFLoadDatatableDTTax';
$route ['dcmTXFCUpdateWhenApprove']                      = 'document/taxinvoicefc/cTaxinvoicefc/FSxCTXFUpdateWhenApprove';
$route ['dcmTXFCFindABB']                                = 'document/taxinvoicefc/cTaxinvoicefc/FSxCTXFFindABB';
$route ['CallTaxInvoice/(:any)/(:any)']                  = 'document/taxinvoicefc/cTaxinvoicefc/FSvCTXFCallTaxInvoice/$1/$2';
$route ['dcmTXFCCheckBranchInComp']                      = 'document/taxinvoicefc/cTaxinvoicefc/FSxCTAXCheckBranchInComp';

// ========================================= ใบสั้งขาย - STD =========================================== //
$route['dcmSO/(:any)/(:any)']                       = 'document/saleorder/cSaleOrder/index/$1/$2';
$route['dcmSOFormSearchList']                       = 'document/saleorder/cSaleOrder/FSvCSOFormSearchList';
$route['dcmSODataTable']                            = 'document/saleorder/cSaleOrder/FSoCSODataTable';
$route['dcmSOPageAdd']                              = 'document/saleorder/cSaleOrder/FSoCSOAddPage';
$route['dcmSOPageEdit']                             = 'document/saleorder/cSaleOrder/FSoCSOEditPage';
$route['dcmSOPdtAdvanceTableLoadData']              = 'document/saleorder/cSaleOrder/FSoCSOPdtAdvTblLoadData';
$route['dcmSOVatTableLoadData']                     = 'document/saleorder/cSaleOrder/FSoCSOVatLoadData';
$route['dcmSOCalculateLastBill']                    = 'document/saleorder/cSaleOrder/FSoCSOCalculateLastBill';
$route['dcmSOEventDelete']                          = 'document/saleorder/cSaleOrder/FSoCSODeleteEventDoc';
$route['dcmSOAdvanceTableShowColList']              = 'document/saleorder/cSaleOrder/FSoCSOAdvTblShowColList';
$route['dcmSOAdvanceTableShowColSave']              = 'document/saleorder/cSaleOrder/FSoCSOAdvTalShowColSave';
$route['dcmSOAddPdtIntoDTDocTemp']                  = 'document/saleorder/cSaleOrder/FSoCSOAddPdtIntoDocDTTemp';
$route['dcmSOEditPdtIntoDTDocTemp']                 = 'document/saleorder/cSaleOrder/FSoCSOEditPdtIntoDocDTTemp';
$route['dcmSOChkHavePdtForDocDTTemp']               = 'document/saleorder/cSaleOrder/FSoCSOChkHavePdtForDocDTTemp';
$route['dcmSOEventAdd']                             = 'document/saleorder/cSaleOrder/FSoCSOAddEventDoc';
$route['dcmSOEventEdit']                            = 'document/saleorder/cSaleOrder/FSoCSOEditEventDoc';
$route['dcmSORemovePdtInDTTmp']                     = 'document/saleorder/cSaleOrder/FSvCSORemovePdtInDTTmp';
$route['dcmSORemovePdtInDTTmpMulti']                = 'document/saleorder/cSaleOrder/FSvCSORemovePdtInDTTmpMulti';
$route['dcmSOCancelDocument']                       = 'document/saleorder/cSaleOrder/FSvCSOCancelDocument';
$route['dcmSOApproveDocument']                      = 'document/saleorder/cSaleOrder/FSvCSOApproveDocument';
$route['dcmSOSerachAndAddPdtIntoTbl']               = 'document/saleorder/cSaleOrder/FSoCSOSearchAndAddPdtIntoTbl';
$route['dcmSOClearDataDocTemp']                     = 'document/saleorder/cSaleOrder/FSoCSOClearDataInDocTemp';
$route['dcmSODisChgHDList']                         = 'document/saleorder/cSaleOrderDisChgModal/FSoCSODisChgHDList';
$route['dcmSODisChgDTList']                         = 'document/saleorder/cSaleOrderDisChgModal/FSoCSODisChgDTList';
$route['dcmSOAddEditDTDis']                         = 'document/saleorder/cSaleOrderDisChgModal/FSoCSOAddEditDTDis';
$route['dcmSOAddEditHDDis']                         = 'document/saleorder/cSaleOrderDisChgModal/FSoCSOAddEditHDDis';
$route['dcmSOPocessAddDisTmpCst']                   = 'document/saleorder/cSaleOrderDisChgModal/FSoCSOPocessAddDisTmpCst';
$route['dcmSOPageEditMonitor']                      = 'document/saleorder/cSaleOrder/FSoCSOEditPageMonitor';
$route['dcmSOPdtAdvanceTableLoadDataMonitor']       = 'document/saleorder/cSaleOrder/FSoCSOPdtAdvTblLoadDataMonitor';
$route['dcmSORejectDocument']                       = 'document/saleorder/cSaleOrder/FSvCSORejectDocument';
$route['dcmCheckSO/(:any)/(:any)']                  = 'document/checksaleorderapprove/cChkSaleOrderApprove/index/$1/$2';
$route['dcmCheckSoPageMain']                        = 'document/checksaleorderapprove/cChkSaleOrderApprove/FSvCCHKSoCallPageMain';
$route['docSORefIntDoc']                            = 'document/saleorder/cSaleOrder/FSoCSOCallRefIntDoc';
$route['docSORefIntDocDataTable']                   = 'document/saleorder/cSaleOrder/FSoCSOCallRefIntDocDataTable';
$route['docSORefIntDocDetailDataTable']             = 'document/saleorder/cSaleOrder/FSoCSOCallRefIntDocDetailDataTable';
$route['docSORefIntDocInsertDTToTemp']              = 'document/saleorder/cSaleOrder/FSoCSOCallRefIntDocInsertDTToTemp';
$route['docSOPageHDDocRef']                         = 'document/saleorder/cSaleOrder/FSoCSOPageHDDocRef';
$route['docSOEventAddEditHDDocRef']                 = 'document/saleorder/cSaleOrder/FSoCSOEventAddEditHDDocRef';
$route['docSOEventDelHDDocRef']                     = 'document/saleorder/cSaleOrder/FSoCSOEventDelHDDocRef';
$route['docSOEventGenPCK']                          = 'document/saleorder/cSaleOrder/FSoCSOEventGenPCK';
$route['dcmSODataTableGenPO']                       = 'document/saleorder/cSaleOrder/FSoCSODataTableGenPO';
$route['dcmSODataTableGenPOGetCst']                 = 'document/saleorder/cSaleOrder/FSoCSODataTableGenPOGetCst';
$route['dcmSODataTableGenPOGetProduct']             = 'document/saleorder/cSaleOrder/FSoCSODataTableGenPOGetProduct';


// ========================================= ใบมัดจำ - DepositDoc =========================================== //
$route['dcmDPS/(:any)/(:any)']                       = 'document/depositdoc/Deposit_controller/index/$1/$2';
$route['dcmDPSFormSearchList']                       = 'document/depositdoc/Deposit_controller/FSvCDPSFormSearchList';
$route['dcmDPSDataTable']                            = 'document/depositdoc/Deposit_controller/FSoCDPSDataTable';
$route['dcmDPSPageAdd']                              = 'document/depositdoc/Deposit_controller/FSoCDPSAddPage';
$route['dcmDPSEventAdd']                             = 'document/depositdoc/Deposit_controller/FSoCDPSAddEventDoc';
$route['dcmDPSEventEdit']                            = 'document/depositdoc/Deposit_controller/FSoCDPSEditEventDoc';
$route['dcmDPSClearDataDocTemp']                     = 'document/depositdoc/Deposit_controller/FSoCDPSClearDataInDocTemp';
$route['dcmDPSPocessAddDisTmpCst']                   = 'document/saleorder/cSaleOrderDisChgModal/FSoCSOPocessAddDisTmpCst';
$route['dcmDPSPdtAdvanceTableLoadData']              = 'document/depositdoc/Deposit_controller/FSoCDPSPdtAdvTblLoadData';
$route['dcmDPSAddPdtIntoDTDocTemp']                  = 'document/depositdoc/Deposit_controller/FSoCDPSAddPdtIntoDocDTTemp';
$route['dcmDPSAddPdtSOIntoDTDocTemp']                = 'document/depositdoc/Deposit_controller/FSoCDPSAddPdtSOIntoDocDTTemp';
$route['dcmDPSEditPdtIntoDTDocTemp']                 = 'document/depositdoc/Deposit_controller/FSoCDPSEditPdtIntoDocDTTemp';
$route['dcmDPSChkHavePdtForDocDTTemp']               = 'document/depositdoc/Deposit_controller/FSoCDPSChkHavePdtForDocDTTemp';
$route['dcmDPSPageEdit']                             = 'document/depositdoc/Deposit_controller/FSoCDPSEditPage';
$route['dcmDPSCancelDocument']                       = 'document/depositdoc/Deposit_controller/FSvCDPSCancelDocument';
$route['dcmDPSApproveDocument']                      = 'document/depositdoc/Deposit_controller/FSvCDPSApproveDocument';
$route['dcmDPSApprovePaidDocument']                  = 'document/depositdoc/Deposit_controller/FSvCDPSApprovePaidDocument';
$route['dcmDPSEventDelete']                          = 'document/depositdoc/Deposit_controller/FSoCDPSDeleteEventDoc';
$route['dcmDPSRemovePdtInDTTmp']                     = 'document/depositdoc/Deposit_controller/FSvCDPSRemovePdtInDTTmp';
$route['dcmDPSRemovePdtInDTTmpMulti']                = 'document/depositdoc/Deposit_controller/FSvCDPSRemovePdtInDTTmpMulti';
$route['dcmDPSCallRefIntDocQT']                      = 'document/depositdoc/Deposit_controller/FSoCDPSCallRefIntDoc';
$route['dcmDPSCallRefIntDocDataTable']               = 'document/depositdoc/Deposit_controller/FSoCDPSCallRefIntDocDataTable';
$route['dcmDPSCallRefIntDocDetailDataTableQT']       = 'document/depositdoc/Deposit_controller/FSoCDPSCallRefIntDocDetailDataTable';
$route['dcmDPSCallRefIntDocInsertDTToTempQT']        = 'document/depositdoc/Deposit_controller/FSoCDPSCallRefIntDocInsertDTToTemp';
$route['dcmDPSFindeDataCstBehideRefIn']              = 'document/depositdoc/Deposit_controller/FSoCDPSFindCstBehideRefIn';

// ========================================= ใบสั้งขาย - STATDOSE =========================================== //
$route['dcmSOSTD/(:any)/(:any)']                        = 'document/saleorder_statdose/cSaleOrder/index/$1/$2';
$route['dcmSOFormSearchList_STD']                       = 'document/saleorder_statdose/cSaleOrder/FSvCSOFormSearchList';
$route['dcmSODataTable_STD']                            = 'document/saleorder_statdose/cSaleOrder/FSoCSODataTable';
$route['dcmSOPageAdd_STD']                              = 'document/saleorder_statdose/cSaleOrder/FSoCSOAddPage';
$route['dcmSOPageEdit_STD']                             = 'document/saleorder_statdose/cSaleOrder/FSoCSOEditPage';
$route['dcmSOPdtAdvanceTableLoadData_STD']              = 'document/saleorder_statdose/cSaleOrder/FSoCSOPdtAdvTblLoadData';
$route['dcmSOEventDelete_STD']                          = 'document/saleorder_statdose/cSaleOrder/FSoCSODeleteEventDoc';
$route['dcmSOAdvanceTableShowColList_STD']              = 'document/saleorder_statdose/cSaleOrder/FSoCSOAdvTblShowColList';
$route['dcmSOAdvanceTableShowColSave_STD']              = 'document/saleorder_statdose/cSaleOrder/FSoCSOAdvTalShowColSave';
$route['dcmSOAddPdtIntoDTDocTemp_STD']                  = 'document/saleorder_statdose/cSaleOrder/FSoCSOAddPdtIntoDocDTTemp';
$route['dcmSOEditPdtIntoDTDocTemp_STD']                 = 'document/saleorder_statdose/cSaleOrder/FSoCSOEditPdtIntoDocDTTemp';
$route['dcmSOChkHavePdtForDocDTTemp_STD']               = 'document/saleorder_statdose/cSaleOrder/FSoCSOChkHavePdtForDocDTTemp';
$route['dcmSOEventAdd_STD']                             = 'document/saleorder_statdose/cSaleOrder/FSoCSOAddEventDoc';
$route['dcmSOEventEdit_STD']                            = 'document/saleorder_statdose/cSaleOrder/FSoCSOEditEventDoc';
$route['dcmSORemovePdtInDTTmp_STD']                     = 'document/saleorder_statdose/cSaleOrder/FSvCSORemovePdtInDTTmp';
$route['dcmSORemovePdtInDTTmpMulti_STD']                = 'document/saleorder_statdose/cSaleOrder/FSvCSORemovePdtInDTTmpMulti';
$route['dcmSOCancelDocument_STD']                       = 'document/saleorder_statdose/cSaleOrder/FSvCSOCancelDocument';
$route['dcmSOApproveDocument_STD']                      = 'document/saleorder_statdose/cSaleOrder/FSvCSOApproveDocument';
$route['dcmSOUpdateReasoninDT_STD']                     = 'document/saleorder_statdose/cSaleOrder/FSoCSOUpdateReasonInDT';
$route['dcmSOGetReasoninDT_STD']                        = 'document/saleorder_statdose/cSaleOrder/FSxCSOGetReasonInDT';
$route['dcmSOInsertDTAndCN_STD']                        = 'document/saleorder_statdose/cSaleOrder/FSoCSOInsertDTAndCN';
$route['dcmSOPdtLoadTablePDT_STD']                      = 'document/saleorder_statdose/cSaleOrder/FSoCSOLoadTablePDT';
$route['dcmSOSerachAndAddPdtIntoTbl_STD']               = 'document/saleorder_statdose/cSaleOrder/FSoCSOSearchAndAddPdtIntoTbl';
$route['dcmSOClearDataDocTemp_STD']                     = 'document/saleorder_statdose/cSaleOrder/FSoCSOClearDataInDocTemp';
$route['dcmSODisChgHDList_STD']                         = 'document/saleorder_statdose/cSaleOrderDisChgModal/FSoCSODisChgHDList';
$route['dcmSODisChgDTList_STD']                         = 'document/saleorder_statdose/cSaleOrderDisChgModal/FSoCSODisChgDTList';
$route['dcmSOAddEditDTDis_STD']                         = 'document/saleorder_statdose/cSaleOrderDisChgModal/FSoCSOAddEditDTDis';
$route['dcmSOAddEditHDDis_STD']                         = 'document/saleorder_statdose/cSaleOrderDisChgModal/FSoCSOAddEditHDDis';
$route['dcmSOPageEditMonitor_STD']                      = 'document/saleorder_statdose/cSaleOrder/FSoCSOEditPageMonitor';
$route['dcmSOPdtAdvanceTableLoadDataMonitor_STD']       = 'document/saleorder_statdose/cSaleOrder/FSoCSOPdtAdvTblLoadDataMonitor';
$route['dcmSORejectDocument_STD']                       = 'document/saleorder_statdose/cSaleOrder/FSvCSORejectDocument';
$route['dcmCheckSO_STD/(:any)/(:any)']                  = 'document/checksaleorderapprove/cChkSaleOrderApprove/index/$1/$2';
$route['dcmCheckSOPageMain_STD']                        = 'document/checksaleorderapprove/cChkSaleOrderApprove/FSvCCHKSoCallPageMain';
$route['dcmSOMonitorGetMassge']                         = 'document/checksaleorderapprove/cChkSaleOrderApprove/FSvCCHKSoGetMassage';

// ========================================= ใบเติมสินค้าชุด - ตู้สินค้า =========================================== //
$route['docRVDRefillPDTVD/(:any)/(:any)']              = 'document/RefillProductVD/cRefillProductVD/index/$1/$2';
$route['docRVDRefillPDTVDPageList']                    = 'document/RefillProductVD/cRefillProductVD/FSvCRVDPageList';
$route['docRVDRefillPDTVDDataTable']                   = 'document/RefillProductVD/cRefillProductVD/FSvCRVDDatatable';
$route['docRVDRefillPDTVDDeleteDocument']              = 'document/RefillProductVD/cRefillProductVD/FSaCRVDDeleteDocument';
$route['docRVDRefillPDTVDPageAdd']                     = 'document/RefillProductVD/cRefillProductVD/FSvCRVDPageAdd';
$route['docRVDRefillPDTVDPageEdit']                    = 'document/RefillProductVD/cRefillProductVD/FSvCRVDPageEdit';
$route['docRVDRefillPDTVDEventAdd']                    = 'document/RefillProductVD/cRefillProductVD/FSxCRVDEventSave';
$route['docRVDRefillPDTVDEventEdit']                   = 'document/RefillProductVD/cRefillProductVD/FSxCRVDEventEdit';
$route['docRVDRefillPDTVDLoadTableStep1']              = 'document/RefillProductVD/cRefillProductVD/FSvCRVDLoadTableStep1';
$route['docRVDRefillPDTVDInsStep1']                    = 'document/RefillProductVD/cRefillProductVD/FSvCRVDInsStep1';
$route['docRVDRefillPDTVDDeleteStep1']                 = 'document/RefillProductVD/cRefillProductVD/FSxCRVDDeleteStep1';
$route['docRVDRefillPDTVDLoadTableStep2']              = 'document/RefillProductVD/cRefillProductVD/FSvCRVDLoadTableStep2';
$route['docRVDRefillPDTVDDeleteStep2']                 = 'document/RefillProductVD/cRefillProductVD/FSxCRVDDeleteStep2';
$route['docRVDRefillPDTVDShowProrate']                 = 'document/RefillProductVD/cRefillProductVD/FSxCRVDProrateStep2';
$route['docRVDRefillPDTVDStep2SaveInTemp']             = 'document/RefillProductVD/cRefillProductVD/FSxCRVDProrateSaveStep2InTemp';
$route['docRVDRefillPDTVDDeleteMultiStep2']            = 'document/RefillProductVD/cRefillProductVD/FSxCRVDDeleteMultiStep2';
$route['docRVDRefillPDTVDUpdateStep2']                 = 'document/RefillProductVD/cRefillProductVD/FSxCRVDUpdateStep2';
$route['docRVDRefillPDTVDLoadTableStep3']              = 'document/RefillProductVD/cRefillProductVD/FSvCRVDLoadTableStep3';
$route['docRVDRefillPDTVDLoadTableStep4']              = 'document/RefillProductVD/cRefillProductVD/FSvCRVDLoadTableStep4';
$route['docRVDRefillPDTCancelDocument']                = 'document/RefillProductVD/cRefillProductVD/FSxCRVDCancelDocument';
$route['docRVDRefillPDTApprovedDocument']              = 'document/RefillProductVD/cRefillProductVD/FSxCRVDApprovedDocument';
$route['docRVDRefillPDTCheckStockWhenApv']             = 'document/RefillProductVD/cRefillProductVD/FSxCRVDCheckStockWhenApv';

// ========================================= ใบคืนสินค้า (ตู้สินค้า) =========================================== //
$route['dcmRS/(:any)/(:any)']                       = 'document/returnsale/cReturnSale/index/$1/$2';
$route['dcmRSFormSearchList']                       = 'document/returnsale/cReturnSale/FSvCRSFormSearchList';
$route['dcmRSDataTable']                            = 'document/returnsale/cReturnSale/FSoCRSDataTable';
$route['dcmRSPageAdd']                              = 'document/returnsale/cReturnSale/FSoCRSAddPage';
$route['dcmRSPageEdit']                             = 'document/returnsale/cReturnSale/FSoCRSEditPage';
$route['dcmRSPdtAdvanceTableLoadData']              = 'document/returnsale/cReturnSale/FSoCRSPdtAdvTblLoadData';
$route['dcmRSVatTableLoadData']                     = 'document/returnsale/cReturnSale/FSoCRSVatLoadData';
$route['dcmRSCalculateLastBill']                    = 'document/returnsale/cReturnSale/FSoCRSCalculateLastBill';
$route['dcmRSEventDelete']                          = 'document/returnsale/cReturnSale/FSoCRSDeleteEventDoc';
$route['dcmRSAddPdtIntoDTDocTemp']                  = 'document/returnsale/cReturnSale/FSoCRSAddPdtIntoDocDTTemp';
$route['dcmRSEditPdtIntoDTDocTemp']                 = 'document/returnsale/cReturnSale/FSoCRSEditPdtIntoDocDTTemp';
$route['dcmRSChkHavePdtForDocDTTemp']               = 'document/returnsale/cReturnSale/FSoCRSChkHavePdtForDocDTTemp';
$route['dcmRSEventAdd']                             = 'document/returnsale/cReturnSale/FSoCRSAddEventDoc';
$route['dcmRSEventEdit']                            = 'document/returnsale/cReturnSale/FSoCRSEditEventDoc';
$route['dcmRSRemovePdtInDTTmp']                     = 'document/returnsale/cReturnSale/FSvCRSRemovePdtInDTTmp';
$route['dcmRSRemovePdtInDTTmpMulti']                = 'document/returnsale/cReturnSale/FSvCRSRemovePdtInDTTmpMulti';
$route['dcmRSCancelDocument']                       = 'document/returnsale/cReturnSale/FSvCRSCancelDocument';
$route['dcmRSApproveDocument']                      = 'document/returnsale/cReturnSale/FSvCRSApproveDocument';
$route['dcmRSSerachAndAddPdtIntoTbl']               = 'document/returnsale/cReturnSale/FSoCRSSearchAndAddPdtIntoTbl';
$route['dcmRSClearDataDocTemp']                     = 'document/returnsale/cReturnSale/FSoCRSClearDataInDocTemp';
$route['dcmRSFindBillSaleVDDocNo']                  = 'document/returnsale/cReturnSale/FSvCRSFindBillSaleVDDocNo';
$route['dcmRSFindBillSaleVDDetail']                 = 'document/returnsale/cReturnSale/FSvCRSFindBillSaleVDDetail';
$route['dcmRSDisChgHDList']                         = 'document/returnsale/cReturnSaleDisChgModal/FSoCRSDisChgHDList';
$route['dcmRSDisChgDTList']                         = 'document/returnsale/cReturnSaleDisChgModal/FSoCRSDisChgDTList';
$route['dcmRSAddEditDTDis']                         = 'document/returnsale/cReturnSaleDisChgModal/FSoCRSAddEditDTDis';
$route['dcmRSAddEditHDDis']                         = 'document/returnsale/cReturnSaleDisChgModal/FSoCRSAddEditHDDis';
$route['dcmRSPocessAddDisTmpCst']                   = 'document/returnsale/cReturnSaleDisChgModal/FSoCRSPocessAddDisTmpCst';
$route['dcmRSInsertBillToTemp']                     = 'document/returnsale/cReturnSale/FSoCRSInsertBillToTemp';
$route['dcmRSFindWahouseDefaultByShop']             = 'document/returnsale/cReturnSale/FSoCRSFindWahouseDefaultByShop';
$route['dcmRSQtyLimitRetunItem']                    = 'document/returnsale/cReturnSale/FSaCRSQtyLimitRetunItem';

// ========================================= ใบสั้งซื้อ 2021 - STD =========================================== //
$route['docPO/(:any)/(:any)']                       = 'document/purchaseorder/cPurchaseOrder/index/$1/$2';
$route['docPOFormSearchList']                       = 'document/purchaseorder/cPurchaseOrder/FSvCPOFormSearchList';
$route['docPODataTable']                            = 'document/purchaseorder/cPurchaseOrder/FSoCPODataTable';
$route['docPOPageAdd']                              = 'document/purchaseorder/cPurchaseOrder/FSoCPOAddPage';
$route['docPOPageEdit']                             = 'document/purchaseorder/cPurchaseOrder/FSoCPOEditPage';
$route['docPOPdtAdvanceTableLoadData']              = 'document/purchaseorder/cPurchaseOrder/FSoCPOPdtAdvTblLoadData';
$route['docPOVatTableLoadData']                     = 'document/purchaseorder/cPurchaseOrder/FSoCPOVatLoadData';
$route['docPOCalculateLastBill']                    = 'document/purchaseorder/cPurchaseOrder/FSoCPOCalculateLastBill';
$route['docPOEventDelete']                          = 'document/purchaseorder/cPurchaseOrder/FSoCPODeleteEventDoc';
$route['docPOAdvanceTableShowColList']              = 'document/purchaseorder/cPurchaseOrder/FSoCPOAdvTblShowColList';
$route['docPOAdvanceTableShowColSave']              = 'document/purchaseorder/cPurchaseOrder/FSoCPOAdvTalShowColSave';
$route['docPOAddPdtIntoDTDocTemp']                  = 'document/purchaseorder/cPurchaseOrder/FSoCPOAddPdtIntoDocDTTemp';
$route['docPOEditPdtIntoDTDocTemp']                 = 'document/purchaseorder/cPurchaseOrder/FSoCPOEditPdtIntoDocDTTemp';
$route['docPOChkHavePdtForDocDTTemp']               = 'document/purchaseorder/cPurchaseOrder/FSoCPOChkHavePdtForDocDTTemp';
$route['docPOEventAdd']                             = 'document/purchaseorder/cPurchaseOrder/FSoCPOAddEventDoc';
$route['docPOEventEdit']                            = 'document/purchaseorder/cPurchaseOrder/FSoCPOEditEventDoc';
$route['docPORemovePdtInDTTmp']                     = 'document/purchaseorder/cPurchaseOrder/FSvCPORemovePdtInDTTmp';
$route['docPORemovePdtInDTTmpMulti']                = 'document/purchaseorder/cPurchaseOrder/FSvCPORemovePdtInDTTmpMulti';
$route['docPOCancelDocument']                       = 'document/purchaseorder/cPurchaseOrder/FSvCPOCancelDocument';
$route['docPOApproveDocument']                      = 'document/purchaseorder/cPurchaseOrder/FSvCPOApproveDocument';
$route['docPOSerachAndAddPdtIntoTbl']               = 'document/purchaseorder/cPurchaseOrder/FSoCPOSearchAndAddPdtIntoTbl';
$route['docPOClearDataDocTemp']                     = 'document/purchaseorder/cPurchaseOrder/FSoCPOClearDataInDocTemp';
$route['docPODisChgHDList']                         = 'document/purchaseorder/cPurchaseOrderDisChgModal/FSoCPODisChgHDList';
$route['docPODisChgDTList']                         = 'document/purchaseorder/cPurchaseOrderDisChgModal/FSoCPODisChgDTList';
$route['docPOAddEditDTDis']                         = 'document/purchaseorder/cPurchaseOrderDisChgModal/FSoCPOAddEditDTDis';
$route['docPOAddEditHDDis']                         = 'document/purchaseorder/cPurchaseOrderDisChgModal/FSoCPOAddEditHDDis';
$route['docPOPocessAddDisTmpCst']                   = 'document/purchaseorder/cPurchaseOrderDisChgModal/FSoCPOPocessAddDisTmpCst';
$route['docPOEventCallEndOfBill']                   = 'document/purchaseorder/cPurchaseOrder/FSaPOCallEndOfBillOnChaheVat';
$route['dcmPOChangeSPLAffectNewVAT']                = 'document/purchaseorder/cPurchaseOrder/FSoCPOChangeSPLAffectNewVAT';
$route['docPOCallRefIntDoc']                        = 'document/purchaseorder/cPurchaseOrder/FSoCPOCallRefIntDoc';
$route['docPOCallRefIntDocDataTable']               = 'document/purchaseorder/cPurchaseOrder/FSoCPOCallRefIntDocDataTable';
$route['docPOCallRefIntDocDetailDataTable']         = 'document/purchaseorder/cPurchaseOrder/FSoCPOCallRefIntDocDetailDataTable';
$route['docPOCallRefIntDocInsertDTToTemp']          = 'document/purchaseorder/cPurchaseOrder/FSoCPOCallRefIntDocInsertDTToTemp';
$route['docPOEventExportDT']                        = 'document/purchaseorder/cPurchaseOrder/FSoCPOEventExportDT';
$route['docPOPageHDDocRef']                         = 'document/purchaseorder/cPurchaseOrder/FSoCPOPageHDDocRef';
$route['docPOEventDelHDDocRef']                     = 'document/purchaseorder/cPurchaseOrder/FSoCPOEventDelHDDocRef';
$route['docPOEventAddEditHDDocRef']                 = 'document/purchaseorder/cPurchaseOrder/FSoCPOEventAddEditHDDocRef';

// ========================================= ใบปรับราคาทุน =========================================== //
$route['docADCCost/(:any)/(:any)']                  = 'document/adjustmentcost/cAdjustmentcost/index/$1/$2';
$route['docADCDataTable']                           = 'document/adjustmentcost/cAdjustmentcost/FSoCASTDataTable';
$route['docADCFormSearchList']                      = 'document/adjustmentcost/cAdjustmentcost/FSvCADCFormSearchList';
$route['docADCPageAdd']                             = 'document/adjustmentcost/cAdjustmentcost/FSvCADCAddPage';
$route['docADCPageEdit']                            = 'document/adjustmentcost/cAdjustmentcost/FSvCADCEditPage';
$route['docADCGetPdtFromProductCode']               = 'document/adjustmentcost/cAdjustmentcost/FSoCADCGetPdtFromPdtCode';
$route['docADCGetPdtFromDoc']                       = 'document/adjustmentcost/cAdjustmentcost/FSoCADCGetPdtFromDoc';
$route['docADCGetPdtFromFilter']                    = 'document/adjustmentcost/cAdjustmentcost/FSoCADCGetPdtFromFilter';
$route['docADCGetPdtFromImportExcel']               = 'document/adjustmentcost/cAdjustmentcost/FSoCADCGetPdtFromImportExcel';
$route['docADCGetPdtFromDT']                        = 'document/adjustmentcost/cAdjustmentcost/FSoCADCGetPdtFromDT';
$route['docADCEventAdd']                            = 'document/adjustmentcost/cAdjustmentcost/FSoCADCEventAdd';
$route['docADCEventEdit']                           = 'document/adjustmentcost/cAdjustmentcost/FSoCADCEventEdit';
$route['docADCCancel']                              = 'document/adjustmentcost/cAdjustmentcost/FSoCADCCancel';
$route['docADCApproveDocument']                     = 'document/adjustmentcost/cAdjustmentcost/FSvCADCApproveDocument';
$route['docADCEventDelete']                         = 'document/adjustmentcost/cAdjustmentcost/FSoCADCDeleteEventDoc';

// ========================================= ใบเสนอราคา =========================================== //
$route['docQuotation/(:any)/(:any)']                = 'document/quotation/Quotation_controller/index/$1/$2';
$route['docQuotationSearchList']                    = 'document/quotation/Quotation_controller/FSvCQTFormSearchList';
$route['docQuotationDataTable']                     = 'document/quotation/Quotation_controller/FSvCQTDataTable';
$route['docQuotationPageAdd']                       = 'document/quotation/Quotation_controller/FSvCQTAddPage';
$route['docQuotationPageEdit']                      = 'document/quotation/Quotation_controller/FSvCQTEditPage';
$route['docQuotationTableDTTemp']                   = 'document/quotation/Quotation_controller/FSvCQTTableDTTemp';
$route['docQuotationRemovePdtInDTTmp']              = 'document/quotation/Quotation_controller/FSvCQTRemovePdtInDTTmp';
$route['docQuotationRemovePdtMultiDTTmp']           = 'document/quotation/Quotation_controller/FSvCQTPdtMultiDeleteEvent';
$route['docQuotationAddPdtIntoDTDocTemp']           = 'document/quotation/Quotation_controller/FSoCQTAddPdtInDTTmp';
$route['docQuotationChkHavePdtForDocDTTemp']        = 'document/quotation/Quotation_controller/FSoCQTChkHavePdtForDocDTTemp';
$route['docQuotationEventDelete']                   = 'document/quotation/Quotation_controller/FSoCQTEventDelete';
$route['docQuotationEventEdit']                     = 'document/quotation/Quotation_controller/FSxCQTEventEdit';
$route['docQuotationEventAdd']                      = 'document/quotation/Quotation_controller/FSxCQTEventAdd';
$route['dcmQuotationDisChgHDList']                  = 'document/quotation/QuotationDiscount_controller/FSoCQTDisChgHDList';
$route['dcmQuotationDisChgDTList']                  = 'document/quotation/QuotationDiscount_controller/FSoCQTDisChgDTList';
$route['dcmQuotationAddEditDTDis']                  = 'document/quotation/QuotationDiscount_controller/FSoCQTAddEditDTDis';
$route['dcmQuotationAddEditHDDis']                  = 'document/quotation/QuotationDiscount_controller/FSoCQTAddEditHDDis';
$route['docQuotationEditPdtIntoDTDocTemp']          = 'document/quotation/Quotation_controller/FSoCQTEditPdtIntoDocDTTemp';
$route['docQuotationCancel']                        = 'document/quotation/Quotation_controller/FSoCQTUpdateStaDocCancel';
$route['docQuotationApprove']                       = 'document/quotation/Quotation_controller/FSoCQTApproveEvent';
$route['docQuotationPageHDDocRef']                  = 'document/quotation/Quotation_controller/FSoCQTPageHDDocRef';
$route['docQuotationEventAddEditHDDocRef']          = 'document/quotation/Quotation_controller/FSoCQTEventAddEditHDDocRef';
$route['docQuotationEventDelHDDocRef']              = 'document/quotation/Quotation_controller/FSoCQTEventDelHDDocRef';
$route['docQuotationRefIntDoc']                     = 'document/quotation/Quotation_controller/FSoCQTCallRefIntDoc';
$route['docQuotationRefIntDocDataTable']            = 'document/quotation/Quotation_controller/FSoCQTCallRefIntDocDataTable';
$route['docQuotationRefIntDocDetailDataTable']      = 'document/quotation/Quotation_controller/FSoCQTCallRefIntDocDetailDataTable';
$route['docQuotationRefIntDocInsertDTToTemp']       = 'document/quotation/Quotation_controller/FSoCQTCallRefIntDocInsertDTToTemp';
$route['docQuotationRefIntDocFindDocCarInfo']       = 'document/quotation/Quotation_controller/FSoCQTCallRefIntDocFindDocCarInfo';
$route['docQuotationFindCarInfo']                   = 'document/quotation/Quotation_controller/FSoCQTReturnCarInfo';

// ========================================= ใบขอซื้อ =========================================== //
$route['docInvoice/(:any)/(:any)']                  = 'document/invoice/Invoice_controller/index/$1/$2';
$route['docInvoiceSearchList']                      = 'document/invoice/Invoice_controller/FSvCIVFormSearchList';
$route['docInvoiceDataTable']                       = 'document/invoice/Invoice_controller/FSvCIVDataTable';
$route['docInvoicePageAdd']                         = 'document/invoice/Invoice_controller/FSvCIVAddPage';
$route['docInvoicePageEdit']                        = 'document/invoice/Invoice_controller/FSvCIVEditPage';
$route['docInvoiceTableDTTemp']                     = 'document/invoice/Invoice_controller/FSvCIVTableDTTemp';
$route['docInvoiceRemovePdtInDTTmp']                = 'document/invoice/Invoice_controller/FSvCIVRemovePdtInDTTmp';
$route['docInvoiceRemovePdtMultiDTTmp']             = 'document/invoice/Invoice_controller/FSvCIVPdtMultiDeleteEvent';
$route['docInvoiceAddPdtIntoDTDocTemp']             = 'document/invoice/Invoice_controller/FSoCIVAddPdtInDTTmp';
$route['docInvoiceChkHavePdtForDocDTTemp']          = 'document/invoice/Invoice_controller/FSoCIVChkHavePdtForDocDTTemp';
$route['docInvoiceEventDelete']                     = 'document/invoice/Invoice_controller/FSoCIVEventDelete';
$route['docInvoiceEventEdit']                       = 'document/invoice/Invoice_controller/FSxCIVEventEdit';
$route['docInvoiceEventAdd']                        = 'document/invoice/Invoice_controller/FSxCIVEventAdd';
$route['docInvoiceDisChgHDList']                    = 'document/invoice/InvoiceDiscount_controller/FSoCIVDisChgHDList';
$route['docInvoiceDisChgDTList']                    = 'document/invoice/InvoiceDiscount_controller/FSoCIVDisChgDTList';
$route['docInvoiceAddEditDTDis']                    = 'document/invoice/InvoiceDiscount_controller/FSoCIVAddEditDTDis';
$route['docInvoiceAddEditHDDis']                    = 'document/invoice/InvoiceDiscount_controller/FSoCIVAddEditHDDis';
$route['docInvoiceEditPdtIntoDTDocTemp']            = 'document/invoice/Invoice_controller/FSoCIVEditPdtIntoDocDTTemp';
$route['docInvoiceCancel']                          = 'document/invoice/Invoice_controller/FSoCIVUpdateStaDocCancel';
$route['docInvoiceApprove']                         = 'document/invoice/Invoice_controller/FSoCIVApproveEvent';
$route['docInvoiceRefIntDoc']                       = 'document/invoice/Invoice_controller/FSoCIVCallRefIntDoc';
$route['docInvoiceRefIntDocDataTable']              = 'document/invoice/Invoice_controller/FSoCIVCallRefIntDocDataTable';
$route['docInvoiceRefIntDocDetailDataTable']        = 'document/invoice/Invoice_controller/FSoCIVCallRefIntDocDetailDataTable';
$route['docInvoiceRefIntDocInsertDTToTemp']         = 'document/invoice/Invoice_controller/FSoCIVCallRefIntDocInsertDTToTemp';
$route['docInvoiceClearTemp']                       = 'document/invoice/Invoice_controller/FSoCIVClearDataTemp';
$route['docIVPageHDDocRef']                         = 'document/invoice/Invoice_controller/FSoCIVPageHDDocRef';
$route['docIVEventDelHDDocRef']                     = 'document/invoice/Invoice_controller/FSoCIVEventDelHDDocRef';
$route['docIVEventAddEditHDDocRef']                 = 'document/invoice/Invoice_controller/FSoCIVEventAddEditHDDocRef';
/** Update New Vat Input BY Wasin 13/06/2022 */
$route['docIVEventUpdChangNewVat']                  = 'document/invoice/Invoice_controller/FSxCIVEventCalcVatKeyInputUser';

// ========================================= ตารางนัดหมาย ========================================= //
$route['docBookingCalendar/(:any)/(:any)']          = 'document/bookingcalendar/Bookingcalendar_controller/index/$1/$2';
$route['docBookingCalendarList']                    = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKFormList';
$route['docBookingCalendarTable']                   = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKFormTable';
$route['docBookingCalendarPageAdd']                 = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKAddPage';
$route['docBookingCalendarItemDT']                  = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKLoadTableItemDT';
$route['docBookingCalendarEventAdd']                = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventAdd';
$route['docBookingCalendarEventInsertToDT']         = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventInsertToDT';
$route['docBookingCalendarEventInsertToDTPDTSet']   = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventInsertToDTPDTSet';
$route['docBookingCalendarCusDatatable']            = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKCusDatatable';
$route['docBookingCalendarHistoryService']          = 'document/bookingcalendar/Bookingcalendar_controller/FSvCBKHistoryService';
$route['docBookingCalendarDeleteTmp']               = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKDeletePDTInDB';
$route['docBookingCalendarDeleteFollow']            = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKDeleteFollow';
$route['docBookingCalendarConfirmFollow']           = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKConfirmFollow';
$route['docBookingCalendarGetInforCar']             = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKGetInforCar';
$route['docBookingCalendarGetDetailBooking']        = 'document/bookingcalendar/Bookingcalendar_controller/FSaCBKGetDetailBooking';
$route['docBookingCalendarCancel']                  = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventCancel';
$route['docBookingCheckStock']                      = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKCheckStock';
$route['docBookingCheckStockInTemp']                = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKCheckStockInTemp';
$route['docBookingCalendarEventPostpone']           = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventPostpone';
$route['docBookingCalendarConfirmByTelDone']        = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventConfirmByTelDone';
$route['docBookingCalendarEventUpdateToDT']         = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKEventUpdateToDT';
$route['docBookingCalendarExportExcel']             = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKRenderExcel';
$route['docBookingUpdateSTKFail']                   = 'document/bookingcalendar/Bookingcalendar_controller/FSxCBKUpdateSTKFail';
$route['docBookingFindCar']                         = 'document/bookingcalendar/Bookingcalendar_controller/FSaCBKFindCar';

// ========================================= แบบสอบถามประเมินความพึงพอใจ ============================//
$route['docSatisfactionSurvey/(:any)/(:any)']       = 'document/satisfactionsurvey/Satisfactionsurvey_controller/index/$1/$2';
$route['docSatisfactionSurveyPageList']             = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSxCSATPageList';
$route['docSatisfactionSurveyDataTable']            = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSxCSATDatatable';
$route['docSatisfactionSurveyPageAdd']              = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSvCSATAddPage';
$route['docSatisfactionSurveyEventAdd']             = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSxCSATAddEvent';
$route['docSatisfactionSurveyPageEdit']             = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSvCSATEditPage';
$route['docSatisfactionSurveyEventEdit']            = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSxCSATEditEvent';
$route['docSatisfactionSurveyApproveDocument']      = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSoCSATApproveEvent';
$route['docSatisfactionSurveyCancelDocument']       = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSvCSATCancelDocument';
$route['docSatisfactionSurveyEventDelete']          = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSoCSATEventDelete';
$route['docSatisfactionSurveyChkTypeAddOrUpdate']   = 'document/satisfactionsurvey/Satisfactionsurvey_controller/FSoCSATChkTypeAddOrUpdate';

//========================================== ใบหักภาษี ณ ที่จ่าย ======================================//
$route['docWhTax']                                  = 'document/withholdingtax/Withholdingtax_controller/index';
$route['docWhTaxSearchList']                        = 'document/withholdingtax/Withholdingtax_controller/FSvCWhTaxFormSearchList';
$route['docWhTaxDataTable']                         = 'document/withholdingtax/Withholdingtax_controller/FSvCWhTaxDataTable';
$route['docWhTaxViewDataPage']                      = 'document/withholdingtax/Withholdingtax_controller/FSvCWhTaxViewDataPage';

//========================================== ใบรับของ ======================================//
$route['docDO/(:any)/(:any)']                       = 'document/deliveryorder/Deliveryorder_controller/index/$1/$2';
$route['dcmDOFormSearchList']                       = 'document/deliveryorder/Deliveryorder_controller/FSvCDOFormSearchList';
$route['docDODataTable']                            = 'document/deliveryorder/Deliveryorder_controller/FSoCDODataTable';
$route['docDOPageAdd']                              = 'document/deliveryorder/Deliveryorder_controller/FSoCDOPageAdd';
$route['docDOPdtAdvanceTableLoadData']              = 'document/deliveryorder/Deliveryorder_controller/FSoCDOPdtAdvTblLoadData';
$route['docDOAddPdtIntoDTDocTemp']                  = 'document/deliveryorder/Deliveryorder_controller/FSoCDOAddPdtIntoDocDTTemp';
$route['docDORemovePdtInDTTmp']                     = 'document/deliveryorder/Deliveryorder_controller/FSvCDORemovePdtInDTTmp';
$route['docDORemovePdtInDTTmpMulti']                = 'document/deliveryorder/Deliveryorder_controller/FSvCDORemovePdtInDTTmpMulti';
$route['docDOEditPdtInDTDocTemp']                   = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEditPdtIntoDocDTTemp';
$route['docDOChkHavePdtForDocDTTemp']               = 'document/deliveryorder/Deliveryorder_controller/FSoCDOChkHavePdtForDocDTTemp';
$route['docDOEventAdd']                             = 'document/deliveryorder/Deliveryorder_controller/FSoCDOAddEventDoc';
$route['docDOEventEdit']                            = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEditEventDoc';
$route['docDOPageEdit']                             = 'document/deliveryorder/Deliveryorder_controller/FSvCDOEditPage';
$route['docDOCallRefIntDoc']                        = 'document/deliveryorder/Deliveryorder_controller/FSoCDOCallRefIntDoc';
$route['docDOCallRefIntDocDataTable']               = 'document/deliveryorder/Deliveryorder_controller/FSoCDOCallRefIntDocDataTable';
$route['docDOCallRefIntDocDetailDataTable']         = 'document/deliveryorder/Deliveryorder_controller/FSoCDOCallRefIntDocDetailDataTable';
$route['docDOCallRefIntDocInsertDTToTemp']          = 'document/deliveryorder/Deliveryorder_controller/FSoCDOCallRefIntDocInsertDTToTemp';
$route['docDOEventDelete']                          = 'document/deliveryorder/Deliveryorder_controller/FSoCDODeleteEventDoc';
$route['docDOCancelDocument']                       = 'document/deliveryorder/Deliveryorder_controller/FSvCDOCancelDocument';
$route['docDOCancelCheckDocref']                    = 'document/deliveryorder/Deliveryorder_controller/FSvCDOCancelCheckRef';
$route['docDOApproveDocument']                      = 'document/deliveryorder/Deliveryorder_controller/FSoCDOApproveEvent';
$route['docDOPageHDDocRef']                         = 'document/deliveryorder/Deliveryorder_controller/FSoCDOPageHDDocRef';
$route['docDOEventAddEditHDDocRef']                 = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEventAddEditHDDocRef';
$route['docDOEventDelHDDocRef']                     = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEventDelHDDocRef';
$route['docDOClearTempWhenChangeData']              = 'document/deliveryorder/Deliveryorder_controller/FSoCDOClearTempWhenChangeData';
$route['docDOEventGenSO']                           = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEventGenSO';

//========================================== ใบขอโอนสาขา (Transfer Request Branch) ======================================//
$route['docTRB/(:any)/(:any)']                      = 'document/transferrequestbranch/Transferrequestbranch_controller/index/$1/$2';
$route['docTRBFormSearchList']                      = 'document/transferrequestbranch/Transferrequestbranch_controller/FSvCTRBFormSearchList';
$route['docTRBDataTable']                           = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBDataTable';
$route['docTRBPageAdd']                             = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBPageAdd';
$route['docTRBPdtAdvanceTableLoadData']             = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBPdtAdvTblLoadData';
$route['docTRBAddPdtIntoDTDocTemp']                 = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBAddPdtIntoDocDTTemp';
$route['docTRBRemovePdtInDTTmp']                    = 'document/transferrequestbranch/Transferrequestbranch_controller/FSvCTRBRemovePdtInDTTmp';
$route['docTRBRemovePdtInDTTmpMulti']               = 'document/transferrequestbranch/Transferrequestbranch_controller/FSvCTRBRemovePdtInDTTmpMulti';
$route['docTRBEditPdtInDTDocTemp']                  = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBEditPdtIntoDocDTTemp';
$route['docTRBChkHavePdtForDocDTTemp']              = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBChkHavePdtForDocDTTemp';
$route['docTRBEventAdd']                            = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBAddEventDoc';
$route['docTRBEventEdit']                           = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBEditEventDoc';
$route['docTRBPageEdit']                            = 'document/transferrequestbranch/Transferrequestbranch_controller/FSvCTRBEditPage';
$route['docTRBCallRefIntDoc']                       = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBCallRefIntDoc';
$route['docTRBCallRefIntDocDataTable']              = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBCallRefIntDocDataTable';
$route['docTRBCallRefIntDocDetailDataTable']        = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBCallRefIntDocDetailDataTable';
$route['docTRBCallRefIntDocInsertDTToTemp']         = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBCallRefIntDocInsertDTToTemp';
$route['docTRBEventDelete']                         = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBDeleteEventDoc';
$route['docTRBCancelDocument']                      = 'document/transferrequestbranch/Transferrequestbranch_controller/FSvCTRBCancelDocument';
$route['docTRBApproveDocument']                     = 'document/transferrequestbranch/Transferrequestbranch_controller/FSoCTRBApproveEvent';

//========================================== ใบสั่งซื้อสาขา (purchase Request Branch) ======================================//
$route['docPreOrderb/(:any)/(:any)']                = 'document/purchasebranch/Purchasebranch_controller/index/$1/$2';
$route['docPRBFormSearchList']                      = 'document/purchasebranch/Purchasebranch_controller/FSvCPRBFormSearchList';
$route['docPRBDataTable']                           = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBDataTable';
$route['docPRBPageAdd']                             = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBPageAdd';
$route['docPRBPdtAdvanceTableLoadData']             = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBPdtAdvTblLoadData';
$route['docPRBAddPdtIntoDTDocTemp']                 = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBAddPdtIntoDocDTTemp';
$route['docPRBRemovePdtInDTTmp']                    = 'document/purchasebranch/Purchasebranch_controller/FSvCPRBRemovePdtInDTTmp';
$route['docPRBRemovePdtInDTTmpMulti']               = 'document/purchasebranch/Purchasebranch_controller/FSvCPRBRemovePdtInDTTmpMulti';
$route['docPRBEditPdtInDTDocTemp']                  = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBEditPdtIntoDocDTTemp';
$route['docPRBChkHavePdtForDocDTTemp']              = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBChkHavePdtForDocDTTemp';
$route['docPRBEventAdd']                            = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBAddEventDoc';
$route['docPRBEventEdit']                           = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBEditEventDoc';
$route['docPRBPageEdit']                            = 'document/purchasebranch/Purchasebranch_controller/FSvCPRBEditPage';
$route['docPRBCallRefIntDoc']                       = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCallRefIntDoc';
$route['docPRBCallRefIntDocDataTable']              = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCallRefIntDocDataTable';
$route['docPRBCallRefIntDocDetailDataTable']        = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCallRefIntDocDetailDataTable';
$route['docPRBCallRefIntDocInsertDTToTemp']         = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCallRefIntDocInsertDTToTemp';
$route['docPRBEventDelete']                         = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBDeleteEventDoc';
$route['docPRBCancelDocument']                      = 'document/purchasebranch/Purchasebranch_controller/FSvCPRBCancelDocument';
$route['docPRBApproveDocument']                     = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBApproveEvent';
$route['docPRBCheckPdtInDTDocTemp']                 = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCheckPdtWah';
$route['docPRBCheckAutoPdtInDTDocTemp']             = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCheckPdtWahAuto';
$route['docPRBCheckAutoPdtInDTDocTempPlus']         = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCheckPdtWahAutoPlus';
$route['docPRBCheckAutoPdtInDTDocTempAddPlus']      = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCheckPdtWahAddButton';
$route['docPRBCheckAutoRentPdtInDTDocTemp']         = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBCheckRentPdtWahAuto';
$route['docPRBEditGroupSugges']                     = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBEditGroupSugges';
$route['docPRBNoStockEventExport']                  = 'document/purchasebranch/Purchasebranch_controller/FSoCPRBNoStockExcel';

//========================================== ใบขอซื้อผู้จำหน่าย ======================================//
$route['docPrs/(:any)/(:any)']                      = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/index/$1/$2';
$route['dcmPrsFormSearchList']                      = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSvCPRSFormSearchList';
$route['docPrsDataTable']                           = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSDataTable';
$route['docPRSPageAdd']                             = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSPageAdd';
$route['docPRSPdtAdvanceTableLoadData']             = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSPdtAdvTblLoadData';
$route['docPRSAddPdtIntoDTDocTemp']                 = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSAddPdtIntoDocDTTemp';
$route['docPRSRemovePdtInDTTmp']                    = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSvCPRSRemovePdtInDTTmp';
$route['docPRSRemovePdtInDTTmpMulti']               = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSvCPRSRemovePdtInDTTmpMulti';
$route['docPRSEditPdtInDTDocTemp']                  = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSEditPdtIntoDocDTTemp';
$route['docPRSChkHavePdtForDocDTTemp']              = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSChkHavePdtForDocDTTemp';
$route['docPRSEventAdd']                            = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSAddEventDoc';
$route['docPRSEventEdit']                           = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSEditEventDoc';
$route['docPRSPageEdit']                            = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSvCPRSEditPage';
$route['docPRSEventDelete']                         = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSDeleteEventDoc';
$route['docPRSCancelDocument']                      = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSvCPRSCancelDocument';
$route['docPRSApproveDocument']                     = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSApproveEvent';
$route['docPRSCallRefIntDoc']                       = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSCallRefIntDoc';
$route['docPRSCallRefIntDocDataTable']              = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSCallRefIntDocDataTable';
$route['docPRSCallRefIntDocDetailDataTable']        = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSCallRefIntDocDetailDataTable';
$route['docPRSCallRefIntDocInsertDTToTemp']         = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSCallRefIntDocInsertDTToTemp';
$route['docPrsDataTable_FN']                        = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSDataTable_FN';
$route['docPRSPageHDDocRef']                        = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSPageHDDocRef';
$route['docPRSEventDelHDDocRef']                    = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSEventDelHDDocRef';
$route['docPRSEventAddEditHDDocRef']                = 'document/supplierpurchaserequisition/Supplierpurchaserequisition_controller/FSoCPRSEventAddEditHDDocRef';

//========================================== ใบตรวจสอบสภาพหลังบริการ ======================================//
$route['docIAS/(:any)/(:any)']                      = 'document/inspectionafterservice/Inspectionafterservice_controller/index/$1/$2';
$route['docIASPageList']                            = 'document/inspectionafterservice/Inspectionafterservice_controller/FSxCIASPageList';
$route['docIASDataTable']                           = 'document/inspectionafterservice/Inspectionafterservice_controller/FSxCIASDatatable';
$route['docIASPageAdd']                             = 'document/inspectionafterservice/Inspectionafterservice_controller/FSvCIASAddPage';
$route['docIASEventAdd']                            = 'document/inspectionafterservice/Inspectionafterservice_controller/FSxCIASAddEvent';
$route['docIASPageEdit']                            = 'document/inspectionafterservice/Inspectionafterservice_controller/FSvCIASEditPage';
$route['docIASEventEdit']                           = 'document/inspectionafterservice/Inspectionafterservice_controller/FSxCIASEditEvent';
$route['docIASApproveDocument']                     = 'document/inspectionafterservice/Inspectionafterservice_controller/FSoCIASApproveEvent';
$route['docIASCancelDocument']                      = 'document/inspectionafterservice/Inspectionafterservice_controller/FSvCIASCancelDocument';
$route['docIASEventDelete']                         = 'document/inspectionafterservice/Inspectionafterservice_controller/FSoCIASEventDelete';
$route['docIASChkTypeAddOrUpdate']                  = 'document/inspectionafterservice/Inspectionafterservice_controller/FSoCIASChkTypeAddOrUpdate';
$route['docIASResultFindCar']                       = 'document/inspectionafterservice/Inspectionafterservice_controller/FSaCIASFindCar';
$route['docIASFindStaStock']                        = 'document/inspectionafterservice/Inspectionafterservice_controller/FSaCIASFindCar';
$route['docIASRefJobOrder']                         = 'document/inspectionafterservice/Inspectionafterservice_controller/FSaCIASRefJobOrder';

//========================================== ใบจองสินค้า ======================================//
$route['docBKO/(:any)/(:any)']                      = 'document/bookingorder/Bookingorder_controller/index/$1/$2';
$route['dcmBKOFormSearchList']                      = 'document/bookingorder/Bookingorder_controller/FSvCBKOFormSearchList';
$route['docBKODataTable']                           = 'document/bookingorder/Bookingorder_controller/FSoCBKODataTable';
$route['docBKOPageAdd']                             = 'document/bookingorder/Bookingorder_controller/FSoCBKOPageAdd';
$route['docBKOPageEdit']                            = 'document/bookingorder/Bookingorder_controller/FSvCBKOEditPage';
$route['docBKOPdtAdvanceTableLoadData']             = 'document/bookingorder/Bookingorder_controller/FSoCBKOPdtAdvTblLoadData';
$route['docBKOAddPdtIntoDTDocTemp']                 = 'document/bookingorder/Bookingorder_controller/FSoCBKOAddPdtIntoDocDTTemp';
$route['docBKOChkHavePdtForDocDTTemp']              = 'document/bookingorder/Bookingorder_controller/FSoCBKOChkHavePdtForDocDTTemp';
$route['docBKORemovePdtInDTTmp']                    = 'document/bookingorder/Bookingorder_controller/FSvCBKORemovePdtInDTTmp';
$route['docBKORemovePdtInDTTmpMulti']               = 'document/bookingorder/Bookingorder_controller/FSvCBKORemovePdtInDTTmpMulti';
$route['docBKOEditPdtInDTDocTemp']                  = 'document/bookingorder/Bookingorder_controller/FSoCBKOEditPdtIntoDocDTTemp';
$route['docBKOCallRefIntDoc']                       = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDoc';
$route['docBKOCallRefIntDocDataTable']              = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocDataTable';
$route['docBKOCallRefIntDocDetailDataTable']        = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocDetailDataTable';
$route['docBKOCallRefIntDocInsertDTToTemp']         = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocInsertDTToTemp';
$route['docBKOCallRefIntDocInsertDTToTempPO']       = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocInsertDTToTempPO';
$route['docBKOCallRefIntDocPO']                     = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocPO';
$route['docBKOCallRefIntDocDataTablePO']            = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocDataTablePO';
$route['docBKOCallRefIntDocDetailDataTablePO']      = 'document/bookingorder/Bookingorder_controller/FSoCBKOCallRefIntDocDetailDataTablePO';
$route['docBKOEventAdd']                            = 'document/bookingorder/Bookingorder_controller/FSoCBKOAddEventDoc';
$route['docBKOEventEdit']                           = 'document/bookingorder/Bookingorder_controller/FSoCBKOEditEventDoc';
$route['docBKOGetAddress']                          = 'document/bookingorder/Bookingorder_controller/FSoCBKOGetAddress';
$route['docBKOEventDelete']                         = 'document/bookingorder/Bookingorder_controller/FSoCBKODeleteEventDoc';
$route['docBKOCancelDocument']                      = 'document/bookingorder/Bookingorder_controller/FSvCBKOCancelDocument';
$route['docBKOApproveDocument']                     = 'document/bookingorder/Bookingorder_controller/FSoCBKOApproveEvent';
$route['docBKOFindCstDocRefInfo']                   = 'document/bookingorder/Bookingorder_controller/FSoCBKOFindCstDocRefInfo';

//========================================== ใบรับรถ / ตรวจสอบบริการก่อนซ่อม ======================================//
$route['docJR1/(:any)/(:any)']                      = 'document/jobrequeststep1/Jobrequeststep1_controller/index/$1/$2';
$route['docJR1PageList']                            = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1PageList';
$route['docJR1DataTable']                           = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1Datatable';
$route['docJR1PageAdd']                             = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1AddPage';
$route['docJR1ChkHavePdtForDocDTTemp']              = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1ChkHavePdtForDocDTTemp';
$route['docJR1EventAdd']                            = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1AddEvent';
$route['docJR1PageEdit']                            = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1EditPage';
$route['docJR1EventEdit']                           = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EditEvent';
$route['docJR1CheckApproveDocument']                = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1CheckApproveEvent';
$route['docJR1ApproveDocument']                     = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1ApproveEvent';
$route['docJR1CancelDocument']                      = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1CancelDocument';
$route['docJR1EventDelete']                         = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1EventDelete';
$route['docJR1TableDTTemp']                         = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1TableDTTemp';
$route['docJR1EventInsertToDT']                     = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventInsertToDT';
$route['docJR1EventInsertToDTCaseDTBooking']        = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventInsertToDTCaseDTBooking';
$route['docJR1RemovePdtInDTTmp']                    = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1RemovePdtInDTTmp';
$route['docJR1PdtSetBehindSltPage']                 = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1PdtSetBehindSltPage';
$route['docJR1EventInsertToDTPDTSet']               = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventInsertToDTPDTSet';
$route['docJR1LoadViewTblPDTSetCstFlw']             = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventLoadTblDTPDTSetCstFlw';
$route['docJR1EventDelPDTDTSet']                    = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventDeleteToDTPDTSet';
$route['docJR1EditPdtIntoDTDocTemp']                = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1EditPdtIntoDocDTTemp';
$route['docJR1FindBookCalendar']                    = 'document/jobrequeststep1/Jobrequeststep1_controller/FSaCJR1FindBookCalendar';
$route['docJR1FindCst']                             = 'document/jobrequeststep1/Jobrequeststep1_controller/FSaCJR1FindCst';
$route['docJR1FindCstCar']                          = 'document/jobrequeststep1/Jobrequeststep1_controller/FSaCJR1FindCstCar';
$route['docJR1DisChgHDList']                        = 'document/jobrequeststep1/JobRequeststep1Discount_controller/FSoCJR1DisChgHDList';
$route['docJR1DisChgDTList']                        = 'document/jobrequeststep1/JobRequeststep1Discount_controller/FSoCJR1DisChgDTList';
$route['docJR1AddEditDTDis']                        = 'document/jobrequeststep1/JobRequeststep1Discount_controller/FSoCJR1AddEditDTDis';
$route['docJR1AddEditHDDis']                        = 'document/jobrequeststep1/JobRequeststep1Discount_controller/FSoCJR1AddEditHDDis';
$route['docJR1DeleteDTSetAndDTCaseCloseModal']      = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCJR1EventDeleteDTSetAndDT';
$route['docJR1EventUpdInlinePdtSet']                = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCHR1EventUpdInlinePdtSet';
$route['docJR1EventRejectInlinePdtSet']             = 'document/jobrequeststep1/Jobrequeststep1_controller/FSxCHR1EventRejInlinePdtSet';
$route['docJR1EventInsPdtSetType2']                 = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1EventInsDTSetPdtSetTyp2';
$route['docJR1CheckProductWahouse']                 = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1EventCheckProductWahouse';
$route['docJR1RemovePdtInDTMutiTmp']                = 'document/jobrequeststep1/Jobrequeststep1_controller/FSvCJR1RemovePdtInDTMutiTmp';
$route['docJR1MoveDTToTemp']                        = 'document/jobrequeststep1/Jobrequeststep1_controller/FSoCJR1EventMoveDTToTemp';
$route['docJR1FindDTSet']                           = 'document/jobrequeststep1/Jobrequeststep1_controller/FSaCJR1FindDTSet';

//========================================== จัดการใบสั่งซื้อสินค้าจากสาขา ======================================//
$route['docMngDocPreOrdB/(:any)/(:any)']                = 'document/managedocorderbranch/Managedocorderbranch_controller/index/$1/$2';
$route['docMngDocPreOrdBSearchList']                    = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGFormSearchList';
$route['docMngDocPreOrdBDataTable']                     = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGDataTable';
$route['docMngDocPreOrdBManagePDT']                     = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGManagePDT';
$route['docMngDocPreOrdBPDTTemp']                       = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGManageByPDT';
$route['docMngDocPreOrdBSavePDTInTable']                = 'document/managedocorderbranch/Managedocorderbranch_controller/FSxCMNGManageSavePDTInTable';
$route['docMngDocPreOrdBUpdateQTY']                     = 'document/managedocorderbranch/Managedocorderbranch_controller/FSxCMNGUpdateQTY';
$route['docMngDocPreOrdBCreateDocRef']                  = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGCreateDocRef';
$route['docMngDocPreOrdBCallMQCreateDoc']               = 'document/managedocorderbranch/Managedocorderbranch_controller/FSxCMNGCallMQCreateDoc';
$route['docMngDocPreOrdBAproveDocRef']                  = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGAproveDocRef';
$route['docMngDocPreOrdBExport']                        = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGExport';
$route['docMngDocPreOrdBChkPdtStkBal']                  = 'document/managedocorderbranch/Managedocorderbranch_controller/FSvCMNGPageChkPdtStkBal';
$route['docMngDocPreOrdBNoti']                          = 'document/managedocorderbranch/Managedocorderbranch_controller/FSxCMNGNoti';
$route['docMngDocPreOrdBUpdateQTYAll']                  = 'document/managedocorderbranch/Managedocorderbranch_controller/FSxCMNGUpdateQTYAll';

//========================================== ตารางงาน ใบสั่งงานรายวัน  ======================================//
$route['docDWO/(:any)/(:any)']                          = 'document/dailyworkorder/Dailyworkorder_controller/index/$1/$2';
$route['docDWOSearchList']                              = 'document/dailyworkorder/Dailyworkorder_controller/FSvCDWOSearchList';
$route['docDWOPageMonitor']                             = 'document/dailyworkorder/Dailyworkorder_controller/FSvCDWOPageMonitor';

//========================================== ใบสั่งงาน ======================================//
$route['docJOB/(:any)/(:any)']                          = 'document/joborder/Joborder_controller/index/$1/$2';
$route['docJOBPageList']                                = 'document/joborder/Joborder_controller/FSxCJOBPageList';
$route['docJOBDataTable']                               = 'document/joborder/Joborder_controller/FSxCJOBDatatable';
$route['docJOBPageAdd']                                 = 'document/joborder/Joborder_controller/FSvCJOBAddPage';
$route['docJOBEventAdd']                                = 'document/joborder/Joborder_controller/FSxCJOBAddEvent';
$route['docJOBPageEdit']                                = 'document/joborder/Joborder_controller/FSvCJOBEditPage';
$route['docJOBEventEdit']                               = 'document/joborder/Joborder_controller/FSxCJOBEditEvent';
$route['docJOBApproveDocument']                         = 'document/joborder/Joborder_controller/FSoCJOBApproveEvent';
$route['docJOBCancelDocument']                          = 'document/joborder/Joborder_controller/FSvCJOBCancelDocument';
$route['docJOBEventDelete']                             = 'document/joborder/Joborder_controller/FSoCJOBEventDelete';
$route['docJOBChkTypeAddOrUpdate']                      = 'document/joborder/Joborder_controller/FSoCJOBChkTypeAddOrUpdate';

//=========================================== สร้างใบสั่งซื้ออัตโนมัติ ======================================//
$route['docMnpDocPO/(:any)/(:any)']                     = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/index/$1/$2';
$route['docMnpDocPOSearchList']                         = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPFormSearchList';
$route['docMnpDocPOTableImport']                        = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPTableImport';
$route['docMnpDocPOSearchListPO']                       = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPPurchaseOrder';
$route['docMnpDocPOTableDetail']                        = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPTableDetail';
$route['docMnpDocPOPageAdd']                            = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPPageAdd';
$route['docMnpDocPOPageEdit']                           = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPPageEdit';
$route['docMnpDocPOTableDTTemp']                        = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPPOTableDTTemp';
$route['docMnpDocPOImportFile']                         = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSvCMNPPOImportFile';
$route['docMnpDocPODeleteDTMuti']                       = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSxCMNPPODeleteEventMuti';
$route['docMnpDocPODeleteDTSingle']                     = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSxCMNPPODeleteEventSingle';
$route['docMnpDocPOEventEdit']                          = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSxCMNPPOEventEdit';
$route['docMnpDocPOEventAdd']                           = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSxCMNPPOEventAdd';
$route['docMnpDocPOCancel']                             = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSoCMNPUpdateStaDocCancel';
$route['docMnpDocPOCreateDoc']                          = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSoCMNPCreateDoc';
$route['docMnpDocPOAproveDoc']                          = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSoCMNPAproveDoc';
$route['docMnpDocPOGenFileAndSendMail']                 = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSoCMNPGenFileAndSendMail';
$route['docMnpDocUpdateSeq']                            = 'document/managedocpurchaseorder/Managedocpurchaseorder_controller/FSxCMNPUpdateSeqInTemp';

//===========================================  ใบกำกับภาษีอย่างย่อ(ใบขาย/ใบคืน) ======================================//
$route['docABB/(:any)/(:any)']                          = 'document/abbsalerefund/Abbsalerefund_controller/index/$1/$2';
$route['docABBPageList']                                = 'document/abbsalerefund/Abbsalerefund_controller/FSvCABBPageList';
$route['docABBPageDataTable']                           = 'document/abbsalerefund/Abbsalerefund_controller/FSvCABBPageDataTable';
$route['docABBPageEdit']                                = 'document/abbsalerefund/Abbsalerefund_controller/FSvCABBPageEdit';
$route['docABBPagePdtDataTable']                        = 'document/abbsalerefund/Abbsalerefund_controller/FSvCABBPageProductDataTable';
$route['docABBEventGetDataPdtSN']                       = 'document/abbsalerefund/Abbsalerefund_controller/FSaCABBEventGetDataPdtSN';
$route['docABBEventUpdatePdtSNTmp']                     = 'document/abbsalerefund/Abbsalerefund_controller/FSaCABBEventUpdatePdtSNTmp';
$route['docABBEventMoveTmpToDT']                        = 'document/abbsalerefund/Abbsalerefund_controller/FSaCABBEventMoveTmpToDT';
$route['docABBEventApproved']                           = 'document/abbsalerefund/Abbsalerefund_controller/FSaCABBEventApproved';
$route['docABBPagePdtSN']                               = 'document/abbsalerefund/Abbsalerefund_controller/FSaCABBPagePdtSN';

// ========================================= ใบบันทึกผลตรวจเช็คก่อนซ่อม ============================//
$route['docPreRepairResult/(:any)/(:any)']              = 'document/prerepairresult/PreRepairResult_controller/index/$1/$2';
$route['docPreRepairResultPageList']                    = 'document/prerepairresult/PreRepairResult_controller/FSxCPrePageList';
$route['docPreRepairResultDataTable']                   = 'document/prerepairresult/PreRepairResult_controller/FSxCPreDatatable';
$route['docPreRepairResultPageAdd']                     = 'document/prerepairresult/PreRepairResult_controller/FSvCPreAddPage';
$route['docPreRepairResultEventAdd']                    = 'document/prerepairresult/PreRepairResult_controller/FSxCPreAddEvent';
$route['docPreRepairResultPageEdit']                    = 'document/prerepairresult/PreRepairResult_controller/FSvCPreEditPage';
$route['docPreRepairResultEventEdit']                   = 'document/prerepairresult/PreRepairResult_controller/FSxCPreEditEvent';
$route['docPreRepairResultApproveDocument']             = 'document/prerepairresult/PreRepairResult_controller/FSoCPreApproveEvent';
$route['docPreRepairResultCancelDocument']              = 'document/prerepairresult/PreRepairResult_controller/FSvCPreCancelDocument';
$route['docPreRepairResultEventDelete']                 = 'document/prerepairresult/PreRepairResult_controller/FSoCPreEventDelete';
$route['docPreRepairResultGetAnwser']                   = 'document/prerepairresult/PreRepairResult_controller/FSvCPreDetailDataTable';
$route['docPreRepairResultEditAnwser']                  = 'document/prerepairresult/PreRepairResult_controller/FSvCPreDetailEditDataTable';
$route['docPreRepairResultEditAnwserInLine']            = 'document/prerepairresult/PreRepairResult_controller/FSvCPreDetailEditDataTableInLine';
$route['docPreRepairResultFindCst']                     = 'document/prerepairresult/PreRepairResult_controller/FSaCPreFindCst';
$route['docPreRepairResultFindCstAddress']              = 'document/prerepairresult/PreRepairResult_controller/FSaCPreFindCstAddress';
$route['docPreRepairResultFindCar']                     = 'document/prerepairresult/PreRepairResult_controller/FSaCPreFindCar';
$route['docPreRepairResultChkTypeAddOrUpdate']          = 'document/prerepairresult/PreRepairResult_controller/FSoCPreChkTypeAddOrUpdate';

//========================================== ใบหยิบสินค้า ======================================//
$route['docPCK/(:any)/(:any)']                          = 'document/productpick/Productpick_controller/index/$1/$2';
$route['docPCKList']                                    = 'document/productpick/Productpick_controller/FSxCPCKList';
$route['docPCKDataTable']                               = 'document/productpick/Productpick_controller/FSxCPCKDataTable';
$route['docPCKCallPageAdd']                             = 'document/productpick/Productpick_controller/FSxCPCKAddPage';
$route['docPCKEventAdd']                                = 'document/productpick/Productpick_controller/FSaCPCKAddEvent';
$route['docPCKCallPageEdit']                            = 'document/productpick/Productpick_controller/FSvCPCKEditPage';
$route['docPCKEventEdit']                               = 'document/productpick/Productpick_controller/FSaCPCKEditEvent';
$route['docPCKUniqueValidate']                          = 'document/productpick/Productpick_controller/FStCPCKUniqueValidate/$1';
$route['docPCKDocApprove']                              = 'document/productpick/Productpick_controller/FStCPCKDocApprove';
$route['docPCKDocCancel']                               = 'document/productpick/Productpick_controller/FStCPCKDocCancel';
$route['docPCKDelDoc']                                  = 'document/productpick/Productpick_controller/FStPCKDeleteDoc';
$route['docPCKDelDocMulti']                             = 'document/productpick/Productpick_controller/FStPCKDeleteMultiDoc';
$route['docPCKInsertPdtToTmp']                          = 'document/productpick/Productpick_controller/FSaCPCKInsertPdtToTmp';
$route['docPCKGetPdtInTmp']                             = 'document/productpick/Productpick_controller/FSxCPCKGetPdtInTmp';
$route['docPCKUpdatePdtInTmp']                          = 'document/productpick/Productpick_controller/FSxCPCKUpdatePdtInTmp';
$route['docPCKDeletePdtInTmp']                          = 'document/productpick/Productpick_controller/FSxCPCKDeletePdtInTmp';
$route['docPCKDeleteMorePdtInTmp']                      = 'document/productpick/Productpick_controller/FSxCPCKDeleteMorePdtInTmp';
$route['docPCKClearPdtInTmp']                           = 'document/productpick/Productpick_controller/FSxCPCKClearPdtInTmp';
$route['docPCKGetPdtColumnList']                        = 'document/productpick/Productpick_controller/FStCPCKGetPdtColumnList';
$route['docPCKUpdatePdtColumn']                         = 'document/productpick/Productpick_controller/FStCPCKUpdatePdtColumn';
$route['docPCKRefIntDoc']                               = 'document/productpick/Productpick_controller/FSoCPCKRefIntDoc';
$route['docPCKRefIntDocDataTable']                      = 'document/productpick/Productpick_controller/FSoCPCKCallRefIntDocDataTable';
$route['docPCKRefIntDocDetailDataTable']                = 'document/productpick/Productpick_controller/FSoCPCKCallRefIntDocDetailDataTable';
$route['docPCKRefIntDocInsertDTToTemp']                 = 'document/productpick/Productpick_controller/FSoCPCKCallRefIntDocInsertDTToTemp';
$route['docPCKCheckProductWahouse']                     = 'document/productpick/Productpick_controller/FSoCPCKEventCheckProductWahouse';
$route['docPCKCheckWahouseInBCH']                       = 'document/productpick/Productpick_controller/FSoCPCKCheckWahouseInBCH';
$route['docPCKDataTable_FN']                            = 'document/productpick/Productpick_controller/FSoCPCKDataTable_FN';
$route['docPCKPageHDDocRef']                            = 'document/productpick/Productpick_controller/FSoCPCKPageHDDocRef';
$route['docPCKEventDelHDDocRef']                        = 'document/productpick/Productpick_controller/FSoCPCKEventDelHDDocRef';
$route['docPCKEventAddEditHDDocRef']                    = 'document/productpick/Productpick_controller/FSoCPCKEventAddEditHDDocRef';
$route['docPCKEditPdtInDTDocTemp']                      = 'document/productpick/Productpick_controller/FSoCPCKEditPdtIntoDocDTTemp';
$route['docPCKRemovePdtInDTTmp']                        = 'document/productpick/Productpick_controller/FSvCPCKRemovePdtInDTTmp';
$route['docPCKRemovePdtInDTTmpMulti']                   = 'document/productpick/Productpick_controller/FSvCPCKRemovePdtInDTTmpMulti';

//========================================== ใบเบิกจ่าย (หน่วยงาน) ======================================//
$route['docTXOWithdraw/(:any)/(:any)']                  = 'document/reimbursement/Reimbursement_controller/index/$1/$2';
$route['docTXOWithdrawPageList']                        = 'document/reimbursement/Reimbursement_controller/FSxCRBMPageList';
$route['docTXOWithdrawDataTable']                       = 'document/reimbursement/Reimbursement_controller/FSxCRBMDatatable';
$route['docTXOWithdrawPageAdd']                         = 'document/reimbursement/Reimbursement_controller/FSvCRBMAddPage';
$route['docTXOWithdrawEventAdd']                        = 'document/reimbursement/Reimbursement_controller/FSxCRBMAddEvent';
$route['docTXOWithdrawPageEdit']                        = 'document/reimbursement/Reimbursement_controller/FSvCRBMEditPage';
$route['docTXOWithdrawEventEdit']                       = 'document/reimbursement/Reimbursement_controller/FSxCRBMEditEvent';
$route['docTXOWithdrawApproveDocument']                 = 'document/reimbursement/Reimbursement_controller/FSoCRBMApproveEvent';
$route['docTXOWithdrawCancelDocument']                  = 'document/reimbursement/Reimbursement_controller/FSvCRBMCancelDocument';
$route['docTXOWithdrawEventDelete']                     = 'document/reimbursement/Reimbursement_controller/FSoCRBMEventDelete';
$route['docTXOWithdrawChkTypeAddOrUpdate']              = 'document/reimbursement/Reimbursement_controller/FSoCRBMChkTypeAddOrUpdate';

//========================================== ใบเพิ่มหนี้ (ลูกค้า) ======================================//
$route['docDBN/0/0']                                    = "document/debitnote/Debitnote_controller/index/$1/$2";
$route['docDBNPageList']                                = "document/debitnote/Debitnote_controller/FSxCDBNPageList";
$route['docDBNDataTable']                               = "document/debitnote/Debitnote_controller/FSxCDBNDatatable";
$route['docDBNPageAdd']                                 = "document/debitnote/Debitnote_controller/FSvCDBNAddPage";
$route['docDBNPageEdit']                                = "document/debitnote/Debitnote_controller/FSvCDBNEditPage";
$route['docDBNEventAdd']                                = "document/debitnote/Debitnote_controller/FSxCDBNAddEvent";
$route['docDBNEventEdit']                               = "document/debitnote/Debitnote_controller/FSxCDBNEditEvent";
$route['docDBNApproveDocument']                         = "document/debitnote/Debitnote_controller/FSxCDBNApproveEvent";
$route['docDBNCancelDocument']                          = "document/debitnote/Debitnote_controller/FSxCDBNCancelDocument";
$route['docDBNEventDelete']                             = "document/debitnote/Debitnote_controller/FSxCDBNDeleteEvent";
$route['docDBNEventChkTypeAddOrUpdate']                 = "document/debitnote/Debitnote_controller/FSxCDBNChkTypeAddOrUpdate";

//=========================================== ใบบันทึกค่าใช้จ่าย ======================================//
$route['docPX/(:any)/(:any)']                           = 'document/expenserecord/Expenserecord_controller/index/$1/$2';
$route['docPXFormSearchList']                           = 'document/expenserecord/Expenserecord_controller/FSvCPXFormSearchList';
$route['docPXDataTable']                                = 'document/expenserecord/Expenserecord_controller/FSoCPXDataTable';
$route['docPXPageAdd']                                  = 'document/expenserecord/Expenserecord_controller/FSoCPXAddPage';
$route['docPXPageEdit']                                 = 'document/expenserecord/Expenserecord_controller/FSoCPXEditPage';
$route['docPXPdtAdvanceTableLoadData']                  = 'document/expenserecord/Expenserecord_controller/FSoCPXPdtAdvTblLoadData';
$route['docPXVatTableLoadData']                         = 'document/expenserecord/Expenserecord_controller/FSoCPXVatLoadData';
$route['docPXCalculateLastBill']                        = 'document/expenserecord/Expenserecord_controller/FSoCPXCalculateLastBill';
$route['docPXEventDelete']                              = 'document/expenserecord/Expenserecord_controller/FSoCPXDeleteEventDoc';
$route['docPXAdvanceTableShowColList']                  = 'document/expenserecord/Expenserecord_controller/FSoCPXAdvTblShowColList';
$route['docPXAdvanceTableShowColSave']                  = 'document/expenserecord/Expenserecord_controller/FSoCPXAdvTalShowColSave';
$route['docPXAddPdtIntoDTDocTemp']                      = 'document/expenserecord/Expenserecord_controller/FSoCPXAddPdtIntoDocDTTemp';
$route['docPXEditPdtIntoDTDocTemp']                     = 'document/expenserecord/Expenserecord_controller/FSoCPXEditPdtIntoDocDTTemp';
$route['docPXChkHavePdtForDocDTTemp']                   = 'document/expenserecord/Expenserecord_controller/FSoCPXChkHavePdtForDocDTTemp';
$route['docPXEventAdd']                                 = 'document/expenserecord/Expenserecord_controller/FSoCPXAddEventDoc';
$route['docPXEventEdit']                                = 'document/expenserecord/Expenserecord_controller/FSoCPXEditEventDoc';
$route['docPXRemovePdtInDTTmp']                         = 'document/expenserecord/Expenserecord_controller/FSvCPXRemovePdtInDTTmp';
$route['docPXRemovePdtInDTTmpMulti']                    = 'document/expenserecord/Expenserecord_controller/FSvCPXRemovePdtInDTTmpMulti';
$route['docPXCancelDocument']                           = 'document/expenserecord/Expenserecord_controller/FSvCPXCancelDocument';
$route['docPXApproveDocument']                          = 'document/expenserecord/Expenserecord_controller/FSvCPXApproveDocument';
$route['docPXSerachAndAddPdtIntoTbl']                   = 'document/expenserecord/Expenserecord_controller/FSoCPXSearchAndAddPdtIntoTbl';
$route['docPXClearDataDocTemp']                         = 'document/expenserecord/Expenserecord_controller/FSoCPXClearDataInDocTemp';
$route['docPXEventCallEndOfBill']                       = 'document/expenserecord/Expenserecord_controller/FSaPXCallEndOfBillOnChaheVat';
$route['docPXChangeSPLAffectNewVAT']                    = 'document/expenserecord/Expenserecord_controller/FSoCPXChangeSPLAffectNewVAT';
$route['docPXMovePODTToDocTmp']                         = 'document/expenserecord/Expenserecord_controller/FSoCPXMovePODTToDocTmp';
$route['docPXDisChgHDList']                             = 'document/expenserecord/ExpenserecordDisChgModal_controller/FSoCPXDisChgHDList';
$route['docPXDisChgDTList']                             = 'document/expenserecord/ExpenserecordDisChgModal_controller/FSoCPXDisChgDTList';
$route['docPXAddEditDTDis']                             = 'document/expenserecord/ExpenserecordDisChgModal_controller/FSoCPXAddEditDTDis';
$route['docPXAddEditHDDis']                             = 'document/expenserecord/ExpenserecordDisChgModal_controller/FSoCPXAddEditHDDis';
$route['docPXPageHDDocRef']                             = 'document/expenserecord/Expenserecord_controller/FSoCPXPageHDDocRef';
$route['docPXEventAddEditHDDocRef']                     = 'document/expenserecord/Expenserecord_controller/FSoCPXEventAddEditHDDocRef';
$route['docPXEventDelHDDocRef']                         = 'document/expenserecord/Expenserecord_controller/FSoCPXEventDelHDDocRef';

//=========================================== เอกสาร - การส่งคืนสินค้าซื้อ ======================================//
$route['docPN/(:any)/(:any)']                           = 'document/purchasereturn/Purchasereturn_controller/index/$1/$2';
$route['docPNFormSearchList']                           = 'document/purchasereturn/Purchasereturn_controller/FSxCPNFormSearchList';
$route['docPNPageAdd']                                  = 'document/purchasereturn/Purchasereturn_controller/FSxCPNAddPage';
$route['docPNPageEdit']                                 = 'document/purchasereturn/Purchasereturn_controller/FSvCPNEditPage';
$route['docPNEventAdd']                                 = 'document/purchasereturn/Purchasereturn_controller/FSaCPNAddEvent';
$route['docPNCheckHaveProductInDT']                     = 'document/purchasereturn/Purchasereturn_controller/FSbCheckHaveProductInDT';
$route['docPNEventDeleteMultiDoc']                      = 'document/purchasereturn/Purchasereturn_controller/FSoCPNDeleteMultiDoc';
$route['docPNEventDeleteDoc']                           = 'document/purchasereturn/Purchasereturn_controller/FSoCPNDeleteDoc';
$route['docPNUniqueValidate/(:any)']                    = 'document/purchasereturn/Purchasereturn_controller/FStCPNUniqueValidate/$1';
$route['docPNEventEdit']                                = 'document/purchasereturn/Purchasereturn_controller/FSaCPNEditEvent';
$route['docPNDataTable']                                = 'document/purchasereturn/Purchasereturn_controller/FSxCPNDataTable';
$route['docPNGetShpByBch']                              = 'document/purchasereturn/Purchasereturn_controller/FSvCPNGetShpByBch';
$route['docPNAddPdtIntoTableDT']                        = 'document/purchasereturn/Purchasereturn_controller/FSvCPNAddPdtIntoTableDT';
$route['docPNEditPdtIntoTableDT']                       = 'document/purchasereturn/Purchasereturn_controller/FSvCPNEditPdtIntoTableDT';
$route['docPNRemovePdtInDTTmp']                         = 'document/purchasereturn/Purchasereturn_controller/FSvCPNRemovePdtInDTTmp';
$route['docPNRemovePdtInFile']                          = 'document/purchasereturn/Purchasereturn_controller/FSvCPNRemovePdtInFile';
$route['docPNRemoveAllPdtInFile']                       = 'document/purchasereturn/Purchasereturn_controller/FSvCPNRemoveAllPdtInFile';
$route['docPNAdvanceTableShowColList']                  = 'document/purchasereturn/Purchasereturn_controller/FSvCPNAdvTblShowColList';
$route['docPNAdvanceTableShowColSave']                  = 'document/purchasereturn/Purchasereturn_controller/FSvCPNShowColSave';
$route['docPNClearTemp']                                = 'document/purchasereturn/Purchasereturn_controller/FSaCPNClearTemp';
$route['docPNGetDTDisTableData']                        = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNGetDTDisTableData';
$route['docPNAddDTDisIntoTable']                        = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNAddDTDisIntoTable';
$route['docPNGetHDDisTableData']                        = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNGetHDDisTableData';
$route['docPNAddHDDisIntoTable']                        = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNAddHDDisIntoTable';
$route['docPNAddEditDTDis']                             = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNAddEditDTDis';
$route['docPNAddEditHDDis']                             = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSvCPNAddEditHDDis';
$route['docPNGetPdtBarCode']                            = 'document/purchasereturn/Purchasereturn_controller/FSvCPNGetPdtBarCode';
$route['docPNPdtAdvanceTableLoadData']                  = 'document/purchasereturn/Purchasereturn_controller/FSvCPNPdtAdvTblLoadData';
$route['docPNNonePdtAdvanceTableLoadData']              = 'document/purchasereturn/Purchasereturn_controller/FSvCPNNonePdtAdvTblLoadData';
$route['docPNCalculateLastBill']                        = 'document/purchasereturn/Purchasereturn_controller/FSvCPNCalculateLastBill';
$route['docPNPdtMultiDeleteEvent']                      = 'document/purchasereturn/Purchasereturn_controller/FSvCPNPdtMultiDeleteEvent';
$route['docPNApprove']                                  = 'document/purchasereturn/Purchasereturn_controller/FSvCPNApprove';
$route['docPNCancel']                                   = 'document/purchasereturn/Purchasereturn_controller/FSvCPNCancel';
$route['docPNClearDocTemForChngCdt']                    = 'document/purchasereturn/Purchasereturn_controller/FSxCTFXClearDocTemForChngCdt';
$route['docPNRefPIHDList']                              = 'document/purchasereturn/Purchasereturnrefpimodel_controller/FSoCPNRefPIHDList';
$route['docPNRefPIDTList']                              = 'document/purchasereturn/Purchasereturnrefpimodel_controller/FSoCPNRefPIDTList';
$route['docPNDisChgHDList']                             = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSoCPNDisChgHDList';
$route['docPNDisChgDTList']                             = 'document/purchasereturn/Purchasereturndischgmodal_controller/FSoCPNDisChgDTList';
$route['docPNCalEndOfBillNonePdt']                      = 'document/purchasereturn/Purchasereturn_controller/FSoCPNCalEndOfBillNonePdt';
$route['docPNChangeSPLAffectNewVAT']                    = 'document/purchasereturn/Purchasereturn_controller/FSoCPNChangeSPLAffectNewVAT';
$route['docPNCallRefIntDoc']                            = 'document/purchasereturn/Purchasereturn_controller/FSoCPNCallRefIntDoc';
$route['docPNCallRefIntDocDataTable']                   = 'document/purchasereturn/Purchasereturn_controller/FSoCPNCallRefIntDocDataTable';
$route['docPNCallRefIntDocDetailDataTable']             = 'document/purchasereturn/Purchasereturn_controller/FSoCPNCallRefIntDocDetailDataTable';
$route['docPNCallRefIntDocInsertDTToTemp']              = 'document/purchasereturn/Purchasereturn_controller/FSoCPNCallRefIntDocInsertDTToTemp';

//========================================== ใบเคลมสินค้า ======================================//
$route['docClaim/(:any)/(:any)']                        = 'document/claimproduct/Claimproduct_controller/index/$1/$2';
$route['docClaimPageList']                              = 'document/claimproduct/Claimproduct_controller/FSvCCLMPageList';
$route['docClaimDataTable']                             = 'document/claimproduct/Claimproduct_controller/FSvCCLMDatatable';
$route['docClaimPageAdd']                               = 'document/claimproduct/Claimproduct_controller/FSvCCLMPageAdd';
$route['docClaimPageEdit']                              = 'document/claimproduct/Claimproduct_controller/FSvCCLMPageEdit';
$route['docClaimEventAdd']                              = 'document/claimproduct/Claimproduct_controller/FSxCCLMEventAdd';
$route['docClaimEventEdit']                             = 'document/claimproduct/Claimproduct_controller/FSxCCLMEventEdit';
$route['docClaimEventDelete']                           = 'document/claimproduct/Claimproduct_controller/FSoCCLMEventDelete';
$route['docClaimEventCheckWahAndSPL']                   = 'document/claimproduct/Claimproduct_controller/FSaCCLMEventCheckWahAndSPL';
$route['docClaimStep1Point1Datatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep1Point1Datatable';
$route['docClaimStep1Point1Insert']                     = 'document/claimproduct/Claimproduct_controller/FSoCCLMAddPdtInDTTmp';
$route['docClaimStep1Point1Remove']                     = 'document/claimproduct/Claimproduct_controller/FSvCCLMRemovePdtInDTTmp';
$route['docClaimStep1Point1UpdateQTY']                  = 'document/claimproduct/Claimproduct_controller/FSoCCLMUpdateQTYDTTemp';
$route['docClaimStep1Point2Datatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep1Point2Datatable';
$route['docClaimStep1Point2UpdateStaAndRmk']            = 'document/claimproduct/Claimproduct_controller/FSoCCLMUpdateStaAndRmk';
$route['docClaimStep1Point3Datatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep1Point3Datatable';
$route['docClaimStep1Point3UpdateSPLAndDate']           = 'document/claimproduct/Claimproduct_controller/FSoCCLMUpdateSPLAndDate';
$route['docClaimStep1Point4Datatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep1Point4Datatable';
$route['docClaimStep1Point4UpdatePickPDT']              = 'document/claimproduct/Claimproduct_controller/FSoCCLMUpdatePickPDT';
$route['docClaimStep1ResultDatatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep1ResultDatatable';
$route['docClaimRefIntDoc']                             = 'document/claimproduct/Claimproduct_controller/FSvCCLMCallRefIntDoc';
$route['docClaimRefIntDocDataTable']                    = 'document/claimproduct/Claimproduct_controller/FSoCCLMCallRefIntDocDataTable';
$route['docClaimRefIntDocDetailDataTable']              = 'document/claimproduct/Claimproduct_controller/FSoCCLMCallRefIntDocDetailDataTable';
$route['docClaimRefIntDocInsertDTToTemp']               = 'document/claimproduct/Claimproduct_controller/FSoCCLMCallRefIntDocInsertDTToTemp';
$route['docClaimStep2ResultDatatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep2ResultDatatable';
$route['docClaimStep2Update']                           = 'document/claimproduct/Claimproduct_controller/FSoCCLMStep2Update';
$route['docClaimStep2CreateDoc']                        = 'document/claimproduct/Claimproduct_controller/FSoCCLMStep2CreateDoc';
$route['docClaimStep3ResultDatatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep3ResultDatatable';
$route['docClaimStep3SaveAndGet']                       = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep3SaveAndGet';
$route['docClaimStep3TableCNDT']                        = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep3Table';
$route['docClaimStep3SaveInTemp']                       = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep3Save';
$route['docClaimStep3Delete']                           = 'document/claimproduct/Claimproduct_controller/FSxCCLMRemovePdtInDTStep3Tmp';
$route['docClaimStep3Save']                             = 'document/claimproduct/Claimproduct_controller/FSxCCLMStep3SaveInDB';
$route['docClaimStep3Update']                           = 'document/claimproduct/Claimproduct_controller/FSxCCLMStep3Update';
$route['docClaimStep4ResultDatatable']                  = 'document/claimproduct/Claimproduct_controller/FSvCCLMStep4ResultDatatable';
$route['docClaimStep4SaveReturnDatatable']              = 'document/claimproduct/Claimproduct_controller/FSxCCLMStep4ReturnDatatable';
$route['docClaimStep4Save']                             = 'document/claimproduct/Claimproduct_controller/FSxCCLMStep4Save';
$route['docClaimStep4Update']                           = 'document/claimproduct/Claimproduct_controller/FSoCCLMStep4Update';
$route['docClaimEventCancel']                           = 'document/claimproduct/Claimproduct_controller/FSoCCLMClaimEventCancel';
$route['docClaimResultFindCstAddress']                  = 'document/claimproduct/Claimproduct_controller/FSaCCLMFindCstAddress';

//========================================== ใบรับชำระ(ลูกหนี้) ======================================//
$route['docRCB/(:any)/(:any)']                          = 'document/receiptdebtor/Receiptdebtor_controller/index/$1/$2';
$route['docRCBPageList']                                = 'document/receiptdebtor/Receiptdebtor_controller/FSvCRCBPageList';
$route['docRCBPageDataTable']                           = 'document/receiptdebtor/Receiptdebtor_controller/FSvCRCBPageDataTable';
$route['docRCBPageEdit']                                = 'document/receiptdebtor/Receiptdebtor_controller/FSvCRCBPageEdit';
$route['docRCBPagePdtDataTable']                        = 'document/receiptdebtor/Receiptdebtor_controller/FSvCABBPageProductDataTable';
$route['docRCBEventGetDataPdtSN']                       = 'document/receiptdebtor/Receiptdebtor_controller/FSaCABBEventGetDataPdtSN';
$route['docRCBEventUpdatePdtSNTmp']                     = 'document/receiptdebtor/Receiptdebtor_controller/FSaCABBEventUpdatePdtSNTmp';
$route['docRCBEventMoveTmpToDT']                        = 'document/receiptdebtor/Receiptdebtor_controller/FSaCABBEventMoveTmpToDT';
$route['docRCBEventApproved']                           = 'document/receiptdebtor/Receiptdebtor_controller/FSaCABBEventApproved';
$route['docRCBPagePdtSN']                               = 'document/receiptdebtor/Receiptdebtor_controller/FSaCABBPagePdtSN';

//========================================== ใบรับวางบิล ======================================//
$route['docInvoiceBill/(:any)/(:any)']                  = 'document/InvoiceBill/Invoicebill_controller/index/$1/$2';
$route['docInvoiceBillPageList']                        = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBPageList';
$route['docInvoiceBillDataTable']                       = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBDatatable';
$route['docInvoiceBillPageAdd']                         = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBPageAdd';
$route['docInvoiceBillPageEdit']                        = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBPageEdit';
$route['docInvoiceBillEventAdd']                        = 'document/InvoiceBill/Invoicebill_controller/FSxCIVBEventAdd';
$route['docInvoiceBillEventEdit']                       = 'document/InvoiceBill/Invoicebill_controller/FSxCIVBEventEdit';
$route['docInvoiceBillEventDelete']                     = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBEventDelete';
$route['docInvoiceBillStep1Point1Remove']               = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBRemovePdtInDTTmp';
$route['docInvoiceBillStep1Point2Datatable']            = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBStep1Point2Datatable';
$route['docInvoiceBillStep1ResultDatatable']            = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBStep1ResultDatatable';
$route['docInvoiceBillEventCancel']                     = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBInvoiceBillEventCancel';
$route['docInvoiceBillFinding']                         = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBFindinghBill';
$route['docInvoiceBillFindingPoint2']                   = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBFindinghBillPoint2';
$route['docInvoiceBillFindingSplAddress']               = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBFindingSplAddress';
$route['docInvoiceBillApprove']                         = 'document/InvoiceBill/Invoicebill_controller/FSoCIVBApproveEvent';
$route['docInvoiceBillStep1Point1Datatable']            = 'document/InvoiceBill/Invoicebill_controller/FSvCIVBStep1Point1Datatable';

//========================================== ใบวางบิล ======================================//
$route['docInvoiceCustomerBill/(:any)/(:any)']          = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/index/$1/$2';
$route['docInvoiceCustomerBillPageList']                = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCPageList';
$route['docInvoiceCustomerBillDataTable']               = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCDatatable';
$route['docInvoiceCustomerBillPageAdd']                 = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCPageAdd';
$route['docInvoiceCustomerBillPageEdit']                = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCPageEdit';
$route['docInvoiceCustomerBillEventAdd']                = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSxCIVCEventAdd';
$route['docInvoiceCustomerBillEventEdit']               = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSxCIVCEventEdit';
$route['docInvoiceCustomerBillEventDelete']             = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCEventDelete';
$route['docInvoiceCustomerBillStep1Point1Remove']       = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCRemovePdtInDTTmp';
$route['docInvoiceCustomerBillStep1Point2Datatable']    = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCStep1Point2Datatable';
$route['docInvoiceCustomerBillStep1ResultDatatable']    = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCStep1ResultDatatable';
$route['docInvoiceCustomerBillEventCancel']             = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCInvoiceBillEventCancel';
$route['docInvoiceCustomerBillFinding']                 = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCFindinghBill';
$route['docInvoiceCustomerBillFindingPoint2']           = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCFindinghBillPoint2';
$route['docInvoiceCustomerBillFindingCstAddress']       = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCFindingCstAddress';
$route['docInvoiceCustomerBillFindingCstBch']           = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCFindingCstBch';
$route['docInvoiceCustomerBillApprove']                 = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCApproveEvent';
$route['docInvoiceCustomerBillStep1Point1Datatable']    = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSvCIVCStep1Point1Datatable';
$route['docInvoiceCustomerBillClearTemp']               = 'document/InvoicecustomerBill/Invoicecustomerbill_controller/FSoCIVCClearTmp';

//========================================== ใบจัด ======================================//
$route['docPAM/(:any)/(:any)']                          = 'document/arrangementproduct/arrangementproduct_controller/index/$1/$2';
$route['docPAMFormSearchList']                          = 'document/arrangementproduct/arrangementproduct_controller/FSvCPAMFormSearchList';
$route['docPAMDataTable']                               = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMDataTable';
$route['docPAMPageAdd']                                 = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMPageAdd';
$route['docPAMPdtAdvanceTableLoadData']                 = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMPdtAdvTblLoadData';
$route['docPAMAddPdtIntoDTDocTemp']                     = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMAddPdtIntoDocDTTemp';
$route['docPAMEditPdtInDTDocTemp']                      = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMEditPdtIntoDocDTTemp';
$route['docPAMRemovePdtInDTTmp']                        = 'document/arrangementproduct/arrangementproduct_controller/FSvCPAMRemovePdtInDTTmp';
$route['docPAMPageHDDocRefList']                        = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMPageHDDocRefList';
$route['docPAMCallRefIntDoc']                           = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMCallRefIntDoc';
$route['docPAMCallRefIntDocDataTable']                  = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMCallRefIntDocDataTable';
$route['docPAMCallRefIntDocDetailDataTable']            = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMCallRefIntDocDetailDataTable';
$route['docPAMCallRefIntDocInsertDTToTemp']             = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMCallRefIntDocInsertDTToTemp';
$route['docPAMEventAddEditHDDocRef']                    = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMEventAddEditHDDocRef';
$route['docPAMChkHavePdtForDocDTTemp']                  = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMChkHavePdtForDocDTTemp';
$route['docPAMEventAdd']                                = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMAddEventDoc';
$route['docPAMEventEdit']                               = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMEditEventDoc';
$route['docPAMPageEdit']                                = 'document/arrangementproduct/arrangementproduct_controller/FSvCPAMEditPage';
$route['docPAMCancelDocument']                          = 'document/arrangementproduct/arrangementproduct_controller/FSvCPAMCancelDocument';
$route['docPAMEventDelHDDocRef']                        = 'document/arrangementproduct/arrangementproduct_controller/FSoCPRSEventDelHDDocRef';
$route['docPAMEventDelete']                             = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMDeleteEventDoc';
$route['docPAMApproveDocument']                         = 'document/arrangementproduct/arrangementproduct_controller/FSoCPAMApproveEvent';

//========================================== ใบเพิ่มหนี้ ======================================//
$route['docAPDebitnote/(:any)/(:any)']                  = 'document/apdebitnote/APDebitnote_controller/index/$1/$2';
$route['docAPDebitnoteFormSearchList']                  = 'document/apdebitnote/APDebitnote_controller/FSxCAPDFormSearchList';
$route['docAPDebitnotePageAdd']                         = 'document/apdebitnote/APDebitnote_controller/FSxCAPDAddPage';
$route['docAPDebitnotePageEdit']                        = 'document/apdebitnote/APDebitnote_controller/FSvCAPDEditPage';
$route['docAPDebitnoteEventAdd']                        = 'document/apdebitnote/APDebitnote_controller/FSaCAPDAddEvent';
$route['docAPDebitnoteCheckHaveProductInDT']            = 'document/apdebitnote/APDebitnote_controller/FSbCAPDCheckHaveProductInDT';
$route['docAPDebitnoteEventDeleteMultiDoc']             = 'document/apdebitnote/APDebitnote_controller/FSoCAPDDeleteMultiDoc';
$route['docAPDebitnoteEventDeleteDoc']                  = 'document/apdebitnote/APDebitnote_controller/FSoCAPDDeleteDoc';
$route['docAPDebitnoteUniqueValidate/(:any)']           = 'document/apdebitnote/APDebitnote_controller/FStCAPDUniqueValidate/$1';
$route['docAPDebitnoteEventEdit']                       = 'document/apdebitnote/APDebitnote_controller/FSaCAPDEditEvent';
$route['docAPDebitnoteDataTable']                       = 'document/apdebitnote/APDebitnote_controller/FSxCAPDDataTable';
$route['docAPDebitnoteGetShpByBch']                     = 'document/apdebitnote/APDebitnote_controller/FSvCAPDGetShpByBch';
$route['docAPDebitnoteAddPdtIntoTableDT']               = 'document/apdebitnote/APDebitnote_controller/FSvCAPDAddPdtIntoTableDT';
$route['docAPDebitnoteEditPdtIntoTableDT']              = 'document/apdebitnote/APDebitnote_controller/FSvCAPDEditPdtIntoTableDT';
$route['docAPDebitnoteRemovePdtInDTTmp']                = 'document/apdebitnote/APDebitnote_controller/FSvCAPDRemovePdtInDTTmp';
$route['docAPDebitnoteRemovePdtInFile']                 = 'document/apdebitnote/APDebitnote_controller/FSvCAPDRemovePdtInFile';
$route['docAPDebitnoteRemoveAllPdtInFile']              = 'document/apdebitnote/APDebitnote_controller/FSvCAPDRemoveAllPdtInFile';
$route['docAPDebitnoteAdvanceTableShowColList']         = 'document/apdebitnote/APDebitnote_controller/FSvCAPDAdvTblShowColList';
$route['docAPDebitnoteAdvanceTableShowColSave']         = 'document/apdebitnote/APDebitnote_controller/FSvCAPDShowColSave';
$route['docAPDebitnoteClearTemp']                       = 'document/apdebitnote/APDebitnote_controller/FSaCAPDClearTemp';
$route['docAPDebitnoteGetPdtBarCode']                   = 'document/apdebitnote/APDebitnote_controller/FSvCAPDGetPdtBarCode';
$route['docAPDebitnotePdtAdvanceTableLoadData']         = 'document/apdebitnote/APDebitnote_controller/FSvCAPDPdtAdvTblLoadData';
$route['docAPDebitnoteNonePdtAdvanceTableLoadData']     = 'document/apdebitnote/APDebitnote_controller/FSvCAPDNonePdtAdvTblLoadData';
$route['docAPDebitnoteCalculateLastBill']               = 'document/apdebitnote/APDebitnote_controller/FSvCAPDCalculateLastBill';
$route['docAPDebitnotePdtMultiDeleteEvent']             = 'document/apdebitnote/APDebitnote_controller/FSvCAPDPdtMultiDeleteEvent';
$route['docAPDebitnoteApprove']                         = 'document/apdebitnote/APDebitnote_controller/FSvCAPDApprove';
$route['docAPDebitnoteCancel']                          = 'document/apdebitnote/APDebitnote_controller/FSvCAPDCancel';
$route['docAPDebitnoteClearDocTemForChngCdt']           = 'document/apdebitnote/APDebitnote_controller/FSxCAPDClearDocTemForChngCdt';
$route['docAPDebitnoteCalEndOfBillNonePdt']             = 'document/apdebitnote/APDebitnote_controller/FSoCAPDCalEndOfBillNonePdt';
$route['docAPDebitnoteChangeSPLAffectNewVAT']           = 'document/apdebitnote/APDebitnote_controller/FSoCAPDChangeSPLAffectNewVAT';
$route['docAPDebitnoteCallRefIntDoc']                   = 'document/apdebitnote/APDebitnote_controller/FSoCAPDCallRefIntDoc';
$route['docAPDebitnoteCallRefIntDocDataTable']          = 'document/apdebitnote/APDebitnote_controller/FSoCAPDCallRefIntDocDataTable';
$route['docAPDebitnoteCallRefIntDocDetailDataTable']    = 'document/apdebitnote/APDebitnote_controller/FSoCAPDCallRefIntDocDetailDataTable';
$route['docAPDebitnoteCallRefIntDocInsertDTToTemp']     = 'document/apdebitnote/APDebitnote_controller/FSoCAPDCallRefIntDocInsertDTToTemp';
$route['docAPDebitnoteGetDTDisTableData']               = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDGetDTDisTableData';
$route['docAPDebitnoteAddDTDisIntoTable']               = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDAddDTDisIntoTable';
$route['docAPDebitnoteGetHDDisTableData']               = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDGetHDDisTableData';
$route['docAPDebitnoteAddHDDisIntoTable']               = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDAddHDDisIntoTable';
$route['docAPDebitnoteAddEditDTDis']                    = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDAddEditDTDis';
$route['docAPDebitnoteAddEditHDDis']                    = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSvCAPDAddEditHDDis';
$route['docAPDebitnoteDisChgHDList']                    = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSoCAPDDisChgHDList';
$route['docAPDebitnoteDisChgDTList']                    = 'document/apdebitnote/APDebitnoteDisChgModal_controller/FSoCAPDDisChgDTList';
$route['docAPDebitnoteRefPIHDList']                     = 'document/apdebitnote/APDebitnoteRefPIModal_controller/FSoCAPDRefPIHDList';
$route['docAPDebitnoteRefPIDTList']                     = 'document/apdebitnote/APDebitnoteRefPIModal_controller/FSoCAPDRefPIDTList';
$route['docAPDebitnotePageHDDocRef']                    = 'document/apdebitnote/APDebitnote_controller/FSoCAPDPageHDDocRef';
$route['docAPDebitnoteEventDelHDDocRef']                = 'document/apdebitnote/APDebitnote_controller/FSoCAPDEventDelHDDocRef';
$route['docAPDebitnoteEventAddEditHDDocRef']            = 'document/apdebitnote/APDebitnote_controller/FSoCAPDEventAddEditHDDocRef';

//========================================== ใบส่งของ ======================================//
$route['docDLV/(:any)/(:any)']                          = 'document/delivery/delivery_controller/index/$1/$2';
$route['docDLVFormSearchList']                          = 'document/delivery/delivery_controller/FSvCDLVFormSearchList';
$route['docDLVDataTable']                               = 'document/delivery/delivery_controller/FSoCDLVDataTable';
$route['docDLVPageAdd']                                 = 'document/delivery/delivery_controller/FSoCDLVPageAdd';
$route['docDLVPdtAdvanceTableLoadData']                 = 'document/delivery/delivery_controller/FSoCDLVPdtAdvTblLoadData';
$route['docDLVAddPdtIntoDTDocTemp']                     = 'document/delivery/delivery_controller/FSoCDLVAddPdtIntoDocDTTemp';
$route['docDLVEditPdtInDTDocTemp']                      = 'document/delivery/delivery_controller/FSoCDLVEditPdtIntoDocDTTemp';
$route['docDLVRemovePdtInDTTmp']                        = 'document/delivery/delivery_controller/FSvCDLVRemovePdtInDTTmp';
$route['docDLVPageHDDocRefList']                        = 'document/delivery/delivery_controller/FSoCDLVPageHDDocRefList';
$route['docDLVCallRefIntDoc']                           = 'document/delivery/delivery_controller/FSoCDLVCallRefIntDoc';
$route['docDLVCallRefIntDocDataTable']                  = 'document/delivery/delivery_controller/FSoCDLVCallRefIntDocDataTable';
$route['docDLVCallRefIntDocDetailDataTable']            = 'document/delivery/delivery_controller/FSoCDLVCallRefIntDocDetailDataTable';
$route['docDLVCallRefIntDocInsertDTToTemp']             = 'document/delivery/delivery_controller/FSoCDLVCallRefIntDocInsertDTToTemp';
$route['docDLVEventAddEditHDDocRef']                    = 'document/delivery/delivery_controller/FSoCDLVEventAddEditHDDocRef';
$route['docDLVChkHavePdtForDocDTTemp']                  = 'document/delivery/delivery_controller/FSoCDLVChkHavePdtForDocDTTemp';
$route['docDLVEventAdd']                                = 'document/delivery/delivery_controller/FSoCDLVAddEventDoc';
$route['docDLVEventEdit']                               = 'document/delivery/delivery_controller/FSoCDLVEditEventDoc';
$route['docDLVPageEdit']                                = 'document/delivery/delivery_controller/FSvCDLVEditPage';
$route['docDLVCancelDocument']                          = 'document/delivery/delivery_controller/FSvCDLVCancelDocument';
$route['docDLVEventDelHDDocRef']                        = 'document/delivery/delivery_controller/FSoCDLVEventDelHDDocRef';
$route['docDLVEventDelete']                             = 'document/delivery/delivery_controller/FSoCDLVDeleteEventDoc';
$route['docDLVApproveDocument']                         = 'document/delivery/delivery_controller/FSoCDLVApproveEvent';

//========================================== ใบรับชำระ(เจ้าหนี้) ======================================//
$route['docRPP/(:any)/(:any)']                          = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/index/$1/$2';
$route['docRPPPageList']                                = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPPageList';
$route['docRPPPageDataTable']                           = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPPageDataTable';
$route['docRPPPageAdd']                                 = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPPageAdd';
$route['docRPPPageEdit']                                = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPPageEdit';
$route['docRPPEventAdd']                                = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSaCRPPABBEventAdd';
$route['docRPPEventEdit']                               = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSaCRPPABBEventEdit';
$route['docRPPEventDelete']                             = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPEventDelete';
$route['docRPPFindingPoint1']                           = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPFindinghBillPoint1';
$route['docRPPFindingPoint2']                           = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPFindinghBillPoint2';
$route['docRPPFindingPoint3']                           = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPFindinghBillPoint3';
$route['docRPPFindingSplAddress']                       = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPFindingSplAddress';
$route['docRPPEventApprove']                            = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPApproveEvent';
$route['docRPPStep1Point1Datatable']                    = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPStep1Point1Datatable';
$route['docRPPEventAddInputRCV']                        = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSvCRPPStep1Point3InputADDRCV';
$route['docRPPEventProRatePayment']                     = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPEventProRatePayment';
$route['docRPPEventUpdWhTaxHD']                         = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPEventUpdWhTaxHD';
$route['docRPPChkHavePdtForDocDTTemp']                  = 'document/receiptpurchasepmt/Receiptpurchasepmt_controller/FSoCRPPChkHavePdtForDocDTTemp';


// ========================================= ตรวจสอบข้อมูลใบสั่งขาย =========================================== //
$route['dcmSOData/(:any)/(:any)']                       = 'document/saleorderdata/cSaleOrderData/index/$1/$2';
$route['dcmSODataFormSearchList']                       = 'document/saleorderdata/cSaleOrderData/FSvCSODFormSearchList';
$route['dcmSODataDataTable']                            = 'document/saleorderdata/cSaleOrderData/FSoCSODDataTable';
$route['dcmSODataCheckCRV']                             = 'document/saleorderdata/cSaleOrderData/FSoCSODChkCRV';
$route['dcmSOCheckUserLogin']                           = 'document/saleorderdata/cSaleOrderData/FSoCSODChkUserNameLogin';
$route['dcmSOCheckUpdateMQ']                            = 'document/saleorderdata/cSaleOrderData/FSoCSODCheckUpdateMQ';
$route['dcmSODataCheckByPass']                          = 'document/saleorderdata/cSaleOrderData/FSoCSODChkByPass';




//========================================== ใบจองช่องฝาก ======================================//
$route['docDBR/(:any)/(:any)']                       = 'document/depositboxreservation/Depositboxreservation_controller/index/$1/$2';
$route['dcmDBRFormSearchList']                       = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBRFormSearchList';
$route['docDBRDataTable']                            = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRDataTable';
$route['docDBRPageAdd']                              = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRPageAdd';
$route['docDBRPdtAdvanceTableLoadData']              = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRPdtAdvTblLoadData';
$route['docDBRAddPdtIntoDTDocTemp']                  = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRAddPdtIntoDocDTTemp';
$route['docDBRRemovePdtInDTTmp']                     = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBRRemovePdtInDTTmp';
$route['docDBRRemovePdtInDTTmpMulti']                = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBRRemovePdtInDTTmpMulti';
$route['docDBREditPdtInDTDocTemp']                   = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBREditPdtIntoDocDTTemp';
$route['docDBRChkHavePdtForDocDTTemp']               = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRChkHavePdtForDocDTTemp';
$route['docDBREventAdd']                             = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRAddEventDoc';
$route['docDBREventEdit']                            = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBREditEventDoc';
$route['docDBRPageEdit']                             = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBREditPage';
$route['docDBRCallRefIntDoc']                        = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallRefIntDoc';
$route['docDBRCallRefIntDocDataTable']               = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallRefIntDocDataTable';
$route['docDBRCallRefIntDocDetailDataTable']         = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallRefIntDocDetailDataTable';
$route['docDBRCallRefIntDocInsertDTToTemp']          = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallRefIntDocInsertDTToTemp';
$route['docDBRCallRefIntDocInsertDTToTempByJump']    = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallRefIntDocInsertDTToTempByJump';
$route['docDBREventDelete']                          = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRDeleteEventDoc';
$route['docDBRCancelDocument']                       = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBRCancelDocument';
$route['docDBRCancelCheckDocref']                    = 'document/depositboxreservation/Depositboxreservation_controller/FSvCDBRCancelCheckRef';
$route['docDBRApproveDocument']                      = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRApproveEvent';
$route['docDBRPageHDDocRef']                         = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRPageHDDocRef';
$route['docDBREventAddEditHDDocRef']                 = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBREventAddEditHDDocRef';
$route['docDBREventDelHDDocRef']                     = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBREventDelHDDocRef';
$route['docDBRClearTempWhenChangeData']              = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRClearTempWhenChangeData';
$route['docDBRCallBrowseBox']                        = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallBrowseBox';
$route['docDBRCallBrowseBoxDataTable']               = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCallBrowseBoxDataTable';
$route['docDBRSuggestLay']                           = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRSuggestLay';
$route['docDBRGetMsgSuggestLay']                     = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRGetMsgSuggestLay';
$route['docDBRUpdatePrictCount']                     = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCountPrint';
$route['docDBRSuggestEdit']                          = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRSuggestEdit';
$route['docDBRGetMsgSuggestEdit']                    = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRGetMsgSuggestEdit';
$route['docDBRApproveDocumentAfterSugges']           = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRApproveAfterSuggesEvent';
$route['docDBRCheckRtsaleDocref']                    = 'document/depositboxreservation/Depositboxreservation_controller/FSoCDBRCheckRtsaleDocref';






// $route['docDOEventGenSO']                           = 'document/deliveryorder/Deliveryorder_controller/FSoCDOEventGenSO';


//========================================== ลูกค้ารับของ ======================================//
$route['docCRV/(:any)/(:any)']                       = 'document/customerreceived/Customerreceived_controller/index/$1/$2';
$route['dcmCRVFormSearchList']                       = 'document/customerreceived/Customerreceived_controller/FSvCCRVFormSearchList';
$route['docCRVDataTable']                            = 'document/customerreceived/Customerreceived_controller/FSoCCRVDataTable';
$route['docCRVPageEdit']                             = 'document/customerreceived/Customerreceived_controller/FSvCCRVEditPage';
$route['docCRVPageHDDocRef']                         = 'document/customerreceived/Customerreceived_controller/FSoCCRVPageHDDocRef';
$route['docCRVPdtAdvanceTableLoadData']              = 'document/customerreceived/Customerreceived_controller/FSoCCRVPdtAdvTblLoadData';
$route['docCRVEventSave']                            = 'document/customerreceived/Customerreceived_controller/FSoCCRVEventSave';

