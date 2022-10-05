<?php
// echo "<pre>";
// print_r($aDataDocHD);
// echo "</pre>";
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    $tIVRoute              = "docInvoiceEventEdit";
    $tIVDocNo              = $aDataDocHD['raItems']['FTXphDocNo'];
    $dIVDocDate            = date('Y-m-d', strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    // $dIVDocDate            = $aDataDocHD['raItems']['FDXphDocDate'];
    $dIVDocTime            = date("H:i", strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    $tIVCreateBy           = $aDataDocHD['raItems']['FTCreateBy'];
    $tIVCreateByName       = $aDataDocHD['raItems']['FTUsrName'];
    $tIVStaDoc             = $aDataDocHD['raItems']['FTXphStaDoc'];
    $tIVStaApv             = $aDataDocHD['raItems']['FTXphStaApv'];
    $tIVApvCode            = $aDataDocHD['raItems']['FTXphApvCode'];
    $tIVUsrNameApv         = $aDataDocHD['raItems']['FTXphApvName'];
    $tIVSPLCode            = $aDataDocSPLHD['raItems']['FTSplCode'];
    $tIVSPLName            = $aDataDocSPLHD['raItems']['FTSplName'];
    $tIVSPLStaLocal        = $aDataDocSPLHD['raItems']['FTSplStaLocal'];
    $tIVTelephone          = $aDataDocSPLHD['raItems']['FTSplTel'];
    $tIVEmail              = $aDataDocSPLHD['raItems']['FTSplEmail'];
    $tIVFTBchCode          = $aDataDocHD['raItems']['FTBchCode'];
    $tIVFTBchName          = $aDataDocHD['raItems']['FTBchName'];
    $tIVVatInOrEx          = $aDataDocHD['raItems']['FTXphVATInOrEx'];
    $tIVCshorCrd           = $aDataDocHD['raItems']['FTXphCshOrCrd'];
    $tIVDstPaid            = $aDataDocSPLHD['raItems']['FTXphDstPaid'];
    $tIVCrTerm             = $aDataDocSPLHD['raItems']['FNXphCrTerm'];
    if (!empty($aDataDocSPLHD['raItems']['FDXphDueDate'])) {
        $tIVAffectDate         = date('Y-m-d', strtotime($aDataDocSPLHD['raItems']['FDXphDueDate']));
    } else {
        $tIVAffectDate      = '';
    }
    // print_r($aDataDocSPLHD['raItems']['FDXphDueDate']);
    // $tIVAffectDate         = $aDataDocSPLHD['raItems']['FDXphDueDate'];
    $nIVStaDocAct          = $aDataDocHD['raItems']['FNXphStaDocAct'];
    $nIVFNXshStaRef        = $aDataDocHD['raItems']['FNXphStaRef'];
    $nStaUploadFile        = 2;
    $tIVRateCode           = $aDataDocHD['raItems']['FTRteCode'];
    $tIVRateName           = $aDataDocHD['raItems']['FTRteName'];
    $tIVFNXshDocPrint      = $aDataDocHD['raItems']['FNXphDocPrint'];
    $tIVFTXshRmk           = $aDataDocHD['raItems']['FTXphRmk'];

    $tFTXphRefInt          = $aDataDocHD['raItems']['DocRefIn'];
    if (isset($aDataDocHD['raItems']['DateRefIn'])) {
        $tFDXphRefIntDate      = date('Y-m-d', strtotime($aDataDocHD['raItems']['DateRefIn']));
    } else {
        $tFDXphRefIntDate      = '';
    }
    $tFTXphBillDoc         = $aDataDocHD['raItems']['DocRefEx_Bill'];
    if (isset($aDataDocHD['raItems']['DateRefEx_Bill'])) {
        $tFDXphBillDue         = date('Y-m-d', strtotime($aDataDocHD['raItems']['DateRefEx_Bill']));
    } else {
        $tFDXphBillDue      = '';
    }


    $tIVFTAgnCode          = $aDataDocHD['raItems']['rtAgnCode'];
    $tIVFTAgnName          = $aDataDocHD['raItems']['rtAgnName'];
    $tIVFTWahCode          = $aDataDocHD['raItems']['rtWahCode'];
    $tIVFTWahName          = $aDataDocHD['raItems']['rtWahName'];
    $nFNXphShipAdd         = ($aDataDocSPLHD['raItems']['FNXphShipAdd']  == 0) ? '' : $aDataDocSPLHD['raItems']['FNXphShipAdd'];
    $nFNXphTaxAdd          = ($aDataDocSPLHD['raItems']['FNXphTaxAdd'] == 0) ? '' : $aDataDocSPLHD['raItems']['FNXphTaxAdd'];
    $tIVFTXphRmk           = $aDataDocHD['raItems']['FTXphRmk'];
    $tFTXphRefTnfID        = $aDataDocSPLHD['raItems']['FTXphRefTnfID'];
    if (isset($aDataDocSPLHD['raItems']['FDXphTnfDate'])) {
        $tFDXphTnfDate         = date('Y-m-d', strtotime($aDataDocSPLHD['raItems']['FDXphTnfDate']));
    } else {
        $tFDXphTnfDate      = '';
    }
    $tFTXphCtrName         = $aDataDocSPLHD['raItems']['FTXphCtrName'];
    $tFTXphRefVehID        = $aDataDocSPLHD['raItems']['FTXphRefVehID'];

    //ที่อยู่จัดส่ง
    $tSHIP_FNAddSeqNo        = @$aDataDocAddr['raItems'][0]['FNAddSeqNo'];
    $tSHIP_FTAddV1No         = @$aDataDocAddr['raItems'][0]['FTAddV1No'];
    $tSHIP_FTAddV1Soi        = @$aDataDocAddr['raItems'][0]['FTAddV1Soi'];
    $tSHIP_FTAddV1Village    = @$aDataDocAddr['raItems'][0]['FTAddV1Village'];
    $tSHIP_FTAddV1Road       = @$aDataDocAddr['raItems'][0]['FTAddV1Road'];
    $tSHIP_FTSudName         = @$aDataDocAddr['raItems'][0]['FTSudName'];
    $tSHIP_FTDstName         = @$aDataDocAddr['raItems'][0]['FTDstName'];
    $tSHIP_FTPvnName         = @$aDataDocAddr['raItems'][0]['FTPvnName'];
    $tSHIP_FTAddV1PostCode   = @$aDataDocAddr['raItems'][0]['FTAddV1PostCode'];
    $tSHIP_FTAddTel          = @$aDataDocAddr['raItems'][0]['FTAddTel'];
    $tSHIP_FTAddFax          = @$aDataDocAddr['raItems'][0]['FTAddFax'];
    $tSHIP_FTAddTaxNo        = @$aDataDocAddr['raItems'][0]['FTAddTaxNo'];
    $tSHIP_FTAddV2Desc1      = @$aDataDocAddr['raItems'][0]['FTAddV2Desc1'];
    $tSHIP_FTAddV2Desc2      = @$aDataDocAddr['raItems'][0]['FTAddV2Desc2'];
    $tSHIP_FTAddName         = @$aDataDocAddr['raItems'][0]['FTAddName'];


    //ที่อยู่ออกใบกำกับภาษี
    $tTAX_FNAddSeqNo        = @$aDataDocAddr['raItems'][1]['FNAddSeqNo'];
    $tTAX_FTAddV1No         = @$aDataDocAddr['raItems'][1]['FTAddV1No'];
    $tTAX_FTAddV1Soi        = @$aDataDocAddr['raItems'][1]['FTAddV1Soi'];
    $tTAX_FTAddV1Village    = @$aDataDocAddr['raItems'][1]['FTAddV1Village'];
    $tTAX_FTAddV1Road       = @$aDataDocAddr['raItems'][1]['FTAddV1Road'];
    $tTAX_FTSudName         = @$aDataDocAddr['raItems'][1]['FTSudName'];
    $tTAX_FTDstName         = @$aDataDocAddr['raItems'][1]['FTDstName'];
    $tTAX_FTPvnName         = @$aDataDocAddr['raItems'][1]['FTPvnName'];
    $tTAX_FTAddV1PostCode   = @$aDataDocAddr['raItems'][1]['FTAddV1PostCode'];
    $tTAX_FTAddTel          = @$aDataDocAddr['raItems'][1]['FTAddTel'];
    $tTAX_FTAddFax          = @$aDataDocAddr['raItems'][1]['FTAddFax'];
    $tTAX_FTAddTaxNo        = @$aDataDocAddr['raItems'][1]['FTAddTaxNo'];
    $tTAX_FTAddV2Desc1      = @$aDataDocAddr['raItems'][1]['FTAddV2Desc1'];
    $tTAX_FTAddV2Desc2      = @$aDataDocAddr['raItems'][1]['FTAddV2Desc2'];
    $tTAX_FTAddName         = @$aDataDocAddr['raItems'][1]['FTAddName'];

    $tXphVat    = $aDataDocHD['raItems']['FCXphVat'];
    $tXphVatCal = $aDataDocHD['raItems']['FCXphVatCal'];
} else {
    $tIVRoute              = "docInvoiceEventAdd";
    $tIVDocNo              = '';
    $dIVDocDate            = date('Y-m-d');
    // $dIVDocDate            = $aDataDocHD['raItems']['FDXphDocDate'];
    $dIVDocTime            = date('Y-m-d');
    $tIVCreateBy           = '';
    $tIVCreateByName       = $this->session->userdata('tSesUsrUsername');
    $tIVStaDoc             = '1';
    $tIVStaApv             = '';
    $tIVApvCode            = '';
    $tIVUsrNameApv         = '';
    $tIVSPLCode            = '';
    $tIVSPLName            = '';
    $tIVSPLStaLocal        = '';
    $tIVTelephone          = '';
    $tIVEmail              = '';
    $tIVFTBchCode          = '';
    $tIVFTBchName          = '';
    $tIVVatInOrEx          = '1';
    $tIVCshorCrd           = '1';
    $tIVDstPaid            = '';
    $tIVCrTerm             = '';
    $tIVAffectDate         = '';
    $nIVStaDocAct          = 1;
    $nIVFNXshStaRef        = '';
    $nStaUploadFile        = 1;
    $tIVRateCode           = $aRateDefault['raItems']['rtCmpRteCode'];
    $tIVRateName           = $aRateDefault['raItems']['rtCmpRteName'];
    $tIVFNXshDocPrint      = '';
    $tIVFTXshRmk           = '';
    $tFTXphRefInt          = '';
    $tFDXphRefIntDate      = '';
    $tFTXphBillDoc         = '';
    $tFDXphBillDue         = '';

    $tIVFTAgnCode          = '';
    $tIVFTAgnName          = '';
    $tIVFTWahCode          = '';
    $tIVFTWahName          = '';
    $nFNXphShipAdd         = '';
    $nFNXphTaxAdd          = '';
    $tIVFTXphRmk           = '';
    $tFTXphRefTnfID        = '';
    $tFDXphTnfDate         = '';
    $tFTXphCtrName         = '';
    $tFTXphRefVehID        = '';

    //ที่อยู่จัดส่ง
    $tSHIP_FNAddSeqNo        = '';
    $tSHIP_FTAddV1No         = '';
    $tSHIP_FTAddV1Soi        = '';
    $tSHIP_FTAddV1Village    = '';
    $tSHIP_FTAddV1Road       = '';
    $tSHIP_FTSudName         = '';
    $tSHIP_FTDstName         = '';
    $tSHIP_FTPvnName         = '';
    $tSHIP_FTAddV1PostCode   = '';
    $tSHIP_FTAddTel          = '';
    $tSHIP_FTAddFax          = '';
    $tSHIP_FTAddTaxNo        = '';
    $tSHIP_FTAddV2Desc1      = '';
    $tSHIP_FTAddV2Desc2      = '';
    $tSHIP_FTAddName         = '';


    //ที่อยู่ออกใบกำกับภาษี
    $tTAX_FNAddSeqNo        = '';
    $tTAX_FTAddV1No         = '';
    $tTAX_FTAddV1Soi        = '';
    $tTAX_FTAddV1Village    = '';
    $tTAX_FTAddV1Road       = '';
    $tTAX_FTSudName         = '';
    $tTAX_FTDstName         = '';
    $tTAX_FTPvnName         = '';
    $tTAX_FTAddV1PostCode   = '';
    $tTAX_FTAddTel          = '';
    $tTAX_FTAddFax          = '';
    $tTAX_FTAddTaxNo        = '';
    $tTAX_FTAddV2Desc1      = '';
    $tTAX_FTAddV2Desc2      = '';
    $tTAX_FTAddName         = '';

    $tXphVat        = 0.00;
    $tXphVatCal     = 0.00;
}

?>

<form id="ofmIVFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtIVSubmitDocument" onclick="JSxIVAddEditDocument()"></button>
    <input type="hidden" id="ohdIVDecimalShow" name="ohdIVDecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdIVRoute"    name="ohdIVRoute" value="<?= @$tIVRoute ?>">
    <input type="hidden" id="ohdIVDocNo"    name="ohdIVDocNo" value="<?= @$tIVDocNo ?>">
    <input type="hidden" id="ohdIVStaApv"   name="ohdIVStaApv" value="<?= @$tIVStaApv ?>">
    <input type="hidden" id="ohdIVStaDoc"   name="ohdIVStaDoc" value="<?= @$tIVStaDoc ?>">

    <input type="hidden" id="ohdIVVat"      name="ohdIVVat"     value="<?=@$tXphVat;?>">
    <input type="hidden" id="ohdIVVatCal"   name="ohdIVVatCal"  value="<?=@$tXphVatCal?>">

    <input type="hidden" id="ohdIVApvOrSave" name="ohdIVApvOrSave" value="">
    <input type="hidden" id="ohdIVCheckClearValidate" name="ohdIVCheckClearValidate" value="0">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIVDataStatusInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/quotation/quotation', 'tTQApproved'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (empty($tIVDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbIVStaAutoGenCode" name="ocbIVStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetIVDocNo" name="oetIVDocNo" maxlength="20" value="<?= $tIVDocNo; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?= language('document/quotation/quotation', 'tTQPlsDocNoDuplicate'); ?>" placeholder="<?= language('document/quotation/quotation', 'tTQDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdIVCheckDuplicateCode" name="ohdIVCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetIVDocDate" name="oetIVDocDate" value="<?= $dIVDocDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker" id="oetIVDocTime" name="oetIVDocTime" value="<?= $dIVDocTime; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= $tIVCreateByName ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTBStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tIVRoute == "docInvoiceEventEdit") {
                                                $tIVLabelStaDoc  = language('document/quotation/quotation', 'tTQStaDoc' . $tIVStaDoc);
                                            } else {
                                                $tIVLabelStaDoc  = '-';
                                            }
                                            ?>
                                            <label><?= $tIVLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTBStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= language('document/quotation/quotation', 'tTQStaApv' . $tIVStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tIVDocNo) && !empty($tIVDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdIVApvCode" name="ohdIVApvCode" maxlength="20" value="<?= $tIVApvCode ?>">
                                                <label>
                                                    <?= (isset($tIVUsrNameApv) && !empty($tIVUsrNameApv)) ? $tIVUsrNameApv : "-"; ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel เงื่อนไข อ้างอิงเอกสารภายใน และ ภายนอก-->
            <?php include('Panel/wPanelCondition.php'); ?>

            <!-- Panel ผู้จำหน่าย-->
            <?php include('Panel/wPanelSupplier.php'); ?>

            <!-- Panel ขนส่ง -->
            <?php include('Panel/wPanelTransport.php'); ?>

            <!-- Panel อื่นๆ -->
            <?php include('Panel/wPanelOther.php'); ?>

            <!-- Panel ไฟลแนบ -->
            <?php include('Panel/wPanelFileImport.php'); ?>
        </div>

        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">
                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvIVContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvIVContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    <div id="odvIVContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-10">

                                            <!-- ผู้จำหน่าย -->
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <!-- <input type="hidden" id="ohdIVFrmSplVatCode"></label>
                                            <input type="hidden" id="ohdIVFrmSplVatRate"></label> -->
                                                    <label class="xCNLabelFrm"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPITBSpl'); ?></label>
                                                    <div class="input-group" style="width:100%;">
                                                        <input type="text" class="input100 xCNHide" id="ohdIVSPLCode" name="ohdIVSPLCode" value="<?= @$tIVSPLCode; ?>">
                                                        <input class="form-control xWPointerEventNone" type="text" id="oetIVSPLName" name="oetIVSPLName" value="<?= @$tIVSPLName; ?>" readonly placeholder="<?= language('document/purchaseinvoice/purchaseinvoice', 'tPIMsgValidSplCode'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="obtIVBrowseSPL" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--ค้นหา-->
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--ตัวเลือก-->
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right xCNHideWhenCancelOrApprove">
                                                <div class="btn-group xCNDropDrownGroup" style="margin-bottom:10px;">
                                                    <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                        <?= language('common/main/main', 'tCMNOption') ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliIVBtnDeleteMulti" class="disabled">
                                                            <a data-toggle="modal" data-target="#odvIVModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!--ค้นหาจากบาร์โค๊ด-->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
                                                <div class="form-group">
                                                    <input type="text" class="form-control xCNPdtEditInLine" id="oetIVInsertBarcode" autocomplete="off" name="oetIVInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                                </div>
                                            </div>

                                            <!--เพิ่มสินค้า-->
                                            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 xCNHideWhenCancelOrApprove">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtIVDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>

                                        </div>

                                        <!--สินค้า-->
                                        <div class="row p-t-10" id="odvIVDataPdtTableDTTemp"></div>

                                        <!--สรุปบิล-->
                                        <?php include('wInvoiceEndOfBill.php'); ?>
                                    </div>
                                    <!-- อ้างอิง -->
                                    <div id="odvIVContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtIVAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvIVTableHDRef"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- =========================================== ไม่พบลูกค้า ============================================= -->
<div id="odvIVModalPleseselectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/deliveryorder/deliveryorder', 'tDOSplNotFound') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvIVPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('document/document/document', 'tDocDocumentCancel') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?= language('document/document/document', 'tDocCancelText1') ?></p>
                <p><strong><?= language('document/document/document', 'tDocCancelText2') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxIVDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อนุมัติเอกสาร  ============================================= -->
<div class="modal fade xCNModalApprove" id="odvIVPopupApv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?= language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?= language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?= language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxIVDocumentApv(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ลบสินค้าใน Temp แบบหลายตัว  ============================================= -->
<div id="odvIVModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmIVDocNoDelete" name="ohdConfirmIVDocNoDelete">
                <input type="hidden" id="ohdConfirmIVSeqNoDelete" name="ohdConfirmIVSeqNoDelete">
                <input type="hidden" id="ohdConfirmIVPdtCodeDelete" name="ohdConfirmIVPdtCodeDelete">
                <input type="hidden" id="ohdConfirmIVPunCodeDelete" name="ohdConfirmIVPunCodeDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณาเลือกคลังสินค้า ก่อนบันทึก =========================================== -->
<div id="odvIVModalPleseSelectWah" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกคลังสินค้า ก่อนบันทึก</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงภายในไปแล้ว =========================================== -->
<div id="odvIVModalPleseDelRefCode" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>มีการอ้างอิงเอกสารแล้ว</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณาเลือกผู้จำหน่าย ก่อนเลือกสินค้า =========================================== -->
<div id="odvIVModalPleseSelectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกผู้จำหน่าย ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ไม่พบสินค้าหลังจากสแกนบาร์โค๊ด (สแกน)  ============================================= -->
<div id="odvIVModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== พบสินค้ามากกว่าหนึ่งรายการ (สแกน)  ============================================= -->
<div id="odvIVModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;">กรุณาเลือกสินค้า</label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal">เลือก</button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-striped xCNTablePDTMoreOne">
                    <thead>
                        <tr>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalcodePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalnamePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalPriceUnit') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalbarcodePDT') ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== เปลี่ยนผู้จำหน่ายใหม่ ============================================= -->
<div class="modal fade" id="odvIVPopupChangeSPL">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">ยืนยันการเปลี่ยนผู้จำหน่าย</label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv">การเปลี่ยนผู้จำหน่ายระบบจะทำการล้างข้อมูลสินค้าเดิมทั้งหมด</p>
                <p><strong>คุณต้องการเปลี่ยนผู้จำหน่ายหรือไม่</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNIVPopupChangeSPLAgain" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== พบรายการสินค้าที่ ราคาต่อหน่วยเป็น 0 บาท ============================================= -->
<div class="modal fade" id="odvIVSumIsNull">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">แจ้งเตือน</label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv">พบรายการสินค้าที่ ราคาต่อหน่วยเป็น 0 บาท กดปุ่มยืนยัน เพื่อทำรายการต่อ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery xCNIVSumIsNull" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvIVModalAddDocRef" class="modal fade" tabindex="-1" role="dialog" style='z-index:1045'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmIVFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?=language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetIVRefDocNoOld" name="oetIVRefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbIVRefType" name="ocbIVRefType">
                                    <option value="1" selected><?=language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?=language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbIVRefDoc" name="ocbIVRefDoc">
                                    <?php if($tIVSPLStaLocal == '1'){?>
                                        <option value="1" selected><?=language('common/main/main', 'ใบรับของ'); ?></option>
                                    <?php }else{?>
                                        <option value="2" selected><?=language('common/main/main', 'ใบสั่งซื้อ'); ?></option>
                                        <option value="3"><?=language('common/main/main', 'ใบขาย'); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetIVDocRefInt" name="oetIVDocRefInt" maxlength="20" value="">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetIVDocRefIntName" name="oetIVDocRefIntName" maxlength="20" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtIVBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetIVRefDocNo" name="oetIVRefDocNo" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetIVRefDocDate" name="oetIVRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtIVRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetIVRefKey" name="oetIVRefKey" placeholder="<?=language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtIVConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?=language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('script/jInvoicePageAdd.php'); ?>
<?php include('dis_chg/wInvoiceDisChgModal.php'); ?>