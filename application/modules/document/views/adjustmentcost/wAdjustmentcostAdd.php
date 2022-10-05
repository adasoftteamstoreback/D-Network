<?php
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    $tADCRoute              = "docADCEventEdit";
    $tADCDocNo              = $aDataDocHD['raItems']['FTXchDocNo'];
    $dADCDocDate            = $aDataDocHD['raItems']['FDXchDocDate'];
    $dADCDocTime            = $aDataDocHD['raItems']['FTXchDocTime'];
    $tADCCreateBy           = $aDataDocHD['raItems']['FTUsrNameCreate'];
    $tADCStaDoc             = $aDataDocHD['raItems']['FTXchStaDoc'];
    $tADCStaApv             = $aDataDocHD['raItems']['FTXchStaApv'];
    $tADCStaPrc             = $aDataDocHD['raItems']['FTXchStaPrcDoc'];
    $tADCApvCode            = $aDataDocHD['raItems']['FTXchApvCode'];
    $tADCBchCode            = $aDataDocHD['raItems']['FTBchCode'];
    $tADCBchName            = $aDataDocHD['raItems']['FTBchName'];
    $tADCAffect             = $aDataDocHD['raItems']['FDXchAffect'];
    $tADCDptCode            = $aDataDocHD['raItems']['FTDptCode'];
    $tADCDptName            = $aDataDocHD['raItems']['FTDptName'];
    $tADCUsrCodeCreateBy    = $aDataDocHD['raItems']['FTCreateBy'];
    $tADCDocType            = $aDataDocHD['raItems']['FNXchDocType'];
    $tADCRefInt             = $aDataDocHD['raItems']['FTXchRefInt'];
    $tADCRefIntDate         = $aDataDocHD['raItems']['FDXchRefIntDate'];
    $tADCRmk                = $aDataDocHD['raItems']['FTXchRmk'];
    $tADCUsrNameApv         = $aDataDocHD['raItems']['FTUsrNameApv'];
    $tADCDocType            = $tADCDocType;
    $nStaUploadFile        = 2;
} else {
    $tADCRoute              = "docADCEventAdd";
    $tADCDocNo              = '';
    $dADCDocDate            = '';
    $dADCDocTime            = date('H:i');
    $tADCCreateBy           = $this->session->userdata('tSesUsrUsername');
    $tADCStaDoc             = '';
    $tADCStaApv             = '';
    $tADCStaPrc             = '';
    $tADCApvCode            = $this->session->userdata('tSesUserCode');
    $tADCBchCode            = $this->session->userdata("tSesUsrBchCodeDefault");
    $tADCBchName            = $this->session->userdata("tSesUsrBchNameDefault");
    $tADCAffect             = '';
    $tADCDptCode            = $this->session->userdata("tSesUsrDptCode");
    $tADCDptName            = $this->session->userdata("tSesUsrDptName");
    $tADCUsrCodeCreateBy    = $this->session->userdata('tSesUserCode');
    $tADCDocType            = '';
    $tADCRefInt             = '';
    $tADCRefIntDate         = '';
    $tADCRmk                = '';
    $tADCDocType            = '';
    $tADCUsrNameApv         = $this->session->userdata('tSesUsrUsername');
    $nStaUploadFile        = 1;
}
$tASTUserType = $this->session->userdata("tSesUsrLevel");

if ($tADCStaDoc == 3) {
    $tNewProcess =  language('document/adjustmentcost/adjustmentcost', 'tADCStaDoc3'); //ยกเลิก
    $tClassStaDoc = 'text-danger';
} else {
    if ($tADCStaApv == 1) {
        $tNewProcess =  language('document/adjustmentcost/adjustmentcost', 'tADCStaApv1'); //อนุมัติแล้ว
        $tClassStaDoc = 'text-success';
    } else {
        $tNewProcess = language('document/adjustmentcost/adjustmentcost', 'tADCStaApv'); //รออนุมัติ
        $tClassStaDoc = 'text-warning';
    }
}

?>

<!-- ** ========================== Start Tab ปุ่ม เปิด Side Bar =============================================== * -->
<div class="xCNDivSideBarOpen xCNHide">
    <div class="xCNAbsoluteClick" onclick="JCNxOpenDiv()"></div>
    <div class="xCNAbsoluteOpen">
        <div class="input-group-btn xCNDivSideBarOpenGroup">
            <label class="xCNDivSideBarOpenWhite"><?php echo language('document/adjustmentcost/adjustmentcost', 'tDIDocumentInformation'); ?></label>
            <button class="xCNDivSideBarOpenWhite">
                <i class="fa fa-angle-double-down xCNDivSideBarOpenIcon" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>
<!-- ** ========================== End Tab ปุ่ม เปิด Side Bar =============================================== * -->

<form id="ofmADCFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdADCCountDocRemark" name="ohdADCCountDocRemark" value="0">
    <input type="hidden" id="ohdADCPdtDupCode" name="ohdADCPdtDupCode" value="">
    <input type="hidden" id="ohdADCRoute" name="ohdADCRoute" value="<?php echo $tADCRoute ?>">
    <input type="hidden" id="ohdADCDocNo" name="ohdADCDocNo" value="<?php echo $tADCDocNo ?>">
    <input type="hidden" id="ohdADCStaApv" name="ohdADCStaApv" value="<?php echo $tADCStaApv ?>">
    <input type="hidden" id="ohdADCStaDoc" name="ohdADCStaDoc" value="<?php echo $tADCStaDoc ?>">
    <input type="hidden" id="ohdADCStaPrc" name="ohdADCStaPrc" value="<?php echo $tADCStaPrc ?>">
    <input type="hidden" id="ohdADCUsrApvMQ" name="ohdADCUsrApvMQ" value="<?php echo $this->session->userdata('tSesUserCode') ?>">
    <input type="hidden" id="ohdADCUsrApv" name="ohdADCUsrApv" value="<?php echo $tADCApvCode ?>">
    <input type="hidden" id="ohdADCLang" name="ohdADCLang" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdADCUsrCode" name="ohdADCUsrCode" value="<?php echo $this->session->userdata('tSesUsername') ?>">
    <input type="hidden" id="ohdADCLangEdit" name="ohdADCLangEdit" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <div class="row">

        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar"> <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvADCHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocument'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvADCDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                      <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvADCDataStatusInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCApproved'); ?></label>
                                </div>
                                <input type="hidden" value="0" id="ohdCheckADCSubmitByButton" name="ohdCheckADCSubmitByButton">
                                <input type="hidden" value="0" id="ohdCheckADCClearValidate" name="ohdCheckADCClearValidate">

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocNo'); ?></label>
                                <?php if (empty($tADCDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbADCStaAutoGenCode" name="ocbADCStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetADCDocNo" name="oetADCDocNo" maxlength="20" value="<?php echo $tADCDocNo; ?>" data-validate-required="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdADCCheckDuplicateCode" name="ohdADCCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetADCDocDate" name="oetADCDocDate" value="<?php echo $dADCDocDate; ?>" data-validate-required="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtADCDocDate" type="button" class="btn xCNBtnDateTime xCNBtnDateTimeControl"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker" id="oetADCDocTime" name="oetADCDocTime" value="<?php echo $dADCDocTime; ?>" data-validate-required="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtADCDocTime" type="button" class="btn xCNBtnDateTime xCNBtnDateTimeControl"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdADCCreateBy" name="ohdADCCreateBy" value="<?php echo $tADCCreateBy ?>">
                                            <label><?php echo $tADCCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCTBStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCStaDoc' . $tADCStaDoc); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCTBStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right <?= $tClassStaDoc ?>">
                                            <label><?php echo $tNewProcess; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tADCDocNo) && !empty($tADCDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdADCApvCode" name="ohdADCApvCode" maxlength="20" value="<?php echo $tADCApvCode ?>">
                                                <label>
                                                    <?php
                                                    if ($tADCStaApv == "" || $tADCStaDoc == 3) {
                                                        $tUsrApv = language('document/salepriceadj/salepriceadj', 'tSpaStaEmtpy');
                                                    } else {
                                                        $tUsrApv = $tADCUsrNameApv;
                                                    }
                                                    echo $tUsrApv
                                                    ?>
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

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCConditionDoc'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvADCWarehouse" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvADCWarehouse" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <?php
                        if ($this->session->userdata("tSesUsrLevel") != "HQ") {
                            if ($this->session->userdata("nSesUsrBchCount") <= 1) {
                                $tBrowseBchDisabled = 'disabled';
                            } else {
                                $tBrowseBchDisabled = '';
                            }
                        } else {
                            $tBrowseBchDisabled = '';
                        }
                        ?>
                        <!-- สาขา -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCBranch'); ?></label>
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="input100 xCNHide" id="ohdADCBchCode" name="ohdADCBchCode" value="<?php echo $tADCBchCode; ?>">
                                <input class="form-control xControlForm xWPointerEventNone" type="text" id="ohdADCBchName" name="ohdADCBchName" value="<?php echo $tADCBchName; ?>" readonly placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCBranch'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtADCBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tBrowseBchDisabled; ?>>
                                        <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- วันที่มีผล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCEffectiveDate'); ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetADCEffectiveDate" name="oetADCEffectiveDate" value="<?php echo $tADCAffect; ?>" data-validate-required="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPlsEffectiveDate'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtADCEffectiveDate" type="button" class="btn xCNBtnDateTime xCNBtnDateTimeControl"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>

                        <!-- ประเภท -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('document/adjustmentcost/adjustmentcost', 'tADCEffectiveDocType'); ?></label>
                            <input type="hidden" id="ohdADCDocType" name="ohdADCDocType" value="<?= $tADCDocType ?>">
                            <select class="selectpicker form-control xControlForm" id="ocmADCDocType" name="ocmADCDocType" maxlength="1">
                                <option value="12" <?= $tADCDocType == "12" ? "selected" : ""; ?>><?= language('document/adjustmentcost/adjustmentcost', 'tADCEffectiveDocTypeStd') ?></option>
                                <option value="10" <?= $tADCDocType == "10" ? "selected" : ""; ?>><?= language('document/adjustmentcost/adjustmentcost', 'tADCEffectiveDocTypeAvg') ?></option>
                            </select>
                        </div>
                        <label id="olbDoctype10"><span style="color: red;">*</span><?= language('document/adjustmentcost/adjustmentcost', 'tASTAStdActive') ?> <span id="ospDoctype10"></span></label>
                        <label id="olbDoctype12"><span style="color: red;">*</span><?= language('document/adjustmentcost/adjustmentcost', 'tASTAvgActive') ?> <span id="ospDoctype12"></span></label>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvADCHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCReference'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvADCDataGeneralInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvADCDataGeneralInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div style="border:1px solid #ccc;position:relative;padding:15px;margin-top:30px;">
                            <label class="xCNLabelFrm" style="position:absolute;top:-15px;left:15px;
								background: #fff;
								padding-left: 10px;
								padding-right: 10px;"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCLabelFrmHead'); ?></label>


                            <!-- ใบซื้อสินค้า -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input xCNRadioPrint" type="radio" name="ocbADCPurchase" id="ocbADCPurchase" value="1" checked>
                                <label class="form-check-label" for="ocbADCPurchase">&nbsp;<?= language('document/adjustmentcost/adjustmentcost', 'tADCCbPurchase'); ?></label>
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPurchase'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="xCNHide" id="ohdADCPurchaseCode" name="ohdADCPurchaseCode" maxlength="5" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCPurchaseName" name="oetADCPurchaseName" value="" readonly placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPurchase'); ?>">
                                    <span class="input-group-btn">
                                        <button id="obtADCPurchase" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- ใบซื้อสินค้า -->

                            <div class="form-check form-check-inline">
                                <input class="form-check-input xCNRadioPrint" type="radio" name="ocbADCAddDoc" id="ocbADCAddDoc" value="1">
                                <label class="form-check-label" for="ocbADCAddDoc">&nbsp;<?= language('document/adjustmentcost/adjustmentcost', 'tADCCbAddDoc'); ?></label>
                            </div>

                            <!-- เอกสารการรับเข้า -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCAddDoc'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="xCNHide" id="ohdADCAddDocCode" name="ohdADCAddDocCode" maxlength="5" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCAddDocName" name="oetADCAddDocName" value="" readonly placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCAddDoc'); ?>">
                                    <span class="input-group-btn">
                                        <button id="obtADCAddDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- เอกสารการรับเข้า -->

                        </div>
                        <!-- วันที่เอกสารภายใน -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                                    <input type="text" class="form-control xControlForm xCNInputWithoutSpc" id="oetADCRefInt" name="oetADCRefInt" maxlength="20" value="<?php echo $tADCRefInt; ?>">
                                </div>
                            </div>
                        </div>
                        <!-- วันที่เอกสารภายใน -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCRefIntDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetADCRefIntDate" name="oetADCRefIntDate" value="<?php echo $tADCRefIntDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtADCRefIntDate" type="button" class="btn xCNBtnDateTime xCNBtnDateTimeControl">
                                                <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pull-right">
                                <button type="button" id="obtImportPDTInCN" class="btn btn-primary xCNApvOrCanCelDisabled" style="width:100%; font-size: 17px; margin-top: 10px;"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCImpPDT'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvADCHeadAllow" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvOther" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCNote'); ?></label>
                            <textarea class="form-control" id="otaADCRmk" name="otaADCRmk" maxlength="200"><?php echo $tADCRmk ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('common/main/main', 'tUPFPanelFile'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIVDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvShowDataTable"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var oIVCallDataTableFile = {
                    ptElementID     : 'odvShowDataTable',
                    ptBchCode       : '<?= $tADCBchCode ?>',
                    ptDocNo         : $('#oetADCDocNo').val(),
                    ptDocKey        : 'TCNTPdtAdjCostHD',
                    ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                    pnEvent         : '<?= $nStaUploadFile ?>',
                    ptCallBackFunct : '',
                    ptStaApv        : $('#ohdADCStaApv').val(),
                    ptStaDoc        : $('#ohdADCStaDoc').val()
                }
                JCNxUPFCallDataTable(oIVCallDataTableFile);
            </script>
        </div>





        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางรายการนับสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xControlForm" maxlength="100" id="oetADCSearchPdtHTML" name="oetADCSearchPdtHTML" onkeyup="JSxADCSearch()" placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCSearchPdt'); ?>">
                                                <input style="display:none;" type="text" class="form-control xControlForm" maxlength="100" id="oetADCScanPdtHTML" name="oetADCScanPdtHTML" onkeyup="Javascript:if(event.keyCode==13) JSxADCSearch()" placeholder="<?php echo language('document/adjustmentcost/adjustmentcost', 'tASTScanPdt'); ?>" data-validate="<?php echo language('document/adjustmentcost/adjustmentcost', 'tASTScanPdtNotFound'); ?>">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn xCNBtnDateTime">
                                                        <img class="xCNIconSearch">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                        <div class="right" id="odvGrpOptionFrom">
                                            <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                                                <button type="button" class="btn xCNBTNMngTable xCNImportBtn" onclick="JSxOpenImportForm()">
                                                    <?= language('common/main/main', 'tImport') ?>
                                                </button>
                                            </div>
                                            <div id="odvADCDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                <button type="button" class="btn xCNBTNMngTable xWBtnDelPdt" data-toggle="dropdown">
                                                    <?= language('common/main/main', 'tCMNOption') ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliADCBtnDeleteMulti" class="disabled">
                                                        <a data-toggle="modal" data-target="#odvTWIModalDelPdtInDTTempMultiple"><?= language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <button <?php if (!empty($tADCStaApv) || $tADCStaDoc == 3) {
                                                            echo "disabled";
                                                        }
                                                        ?> id="obtADCFilterDataCondition" type="button" class="btn btn-primary xWASTDisabledOnApv" style="font-size: 16px;"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterCondition'); ?></button>
                                            </div>
                                            <div class="btn-group">
                                                <input type="text" class="form-control xControlForm" id="oetADCInsertBarcode" autocomplete="off" name="oetADCInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="odvADCPdtTablePanal" style="padding: 15px;">
                                    <div class="table-responsive">
                                        <table class="table xWPdtTableFont" id="otbDOCPdtTable">
                                            <thead>
                                                <tr class="xCNCenter">
                                                    <th class="xCNHideWhenApvOrCancel">
                                                        <label class="fancy-checkbox">
                                                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxDOSelectAll(this)">
                                                            <span class="">&nbsp;</span>
                                                        </label>
                                                    </th>
                                                    <th><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCOrder'); ?></th>
                                                    <th nowrap title="รหัสสินค้า">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPdtCode'); ?></th>
                                                    <th nowrap title="ชื่อสินค้า">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPdtName'); ?> </th>
                                                    <th nowrap title="บาร์โค้ด" class="hidden">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'บาร์โค้ด'); ?> </th>
                                                    <th nowrap title="หน่วยสินค้า">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPdtUnit'); ?> </th>
                                                    <th class="text-right" nowrap title="ต้นทุนเดิม">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCOldCost'); ?> </th>
                                                    <th class="text-right" nowrap title="ผลต่าง">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDiffCost'); ?> </th>
                                                    <th class="text-right" nowrap title="ต้นทุนใหม่">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCNewCost'); ?> </th>
                                                    <th class="text-left xWRemark hidden" nowrap title="หมายเหตุ">
                                                        <?php echo language('document/adjustmentcost/adjustmentcost', 'หมายเหตุ'); ?> </th>
                                                    <th class="xCNHideWhenApvOrCancel"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDelete'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="odvADCTable">

                                            </tbody>
                                        </table>
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








<div class="modal fade" id="odvADCFilterDataCondition">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead"><label class="xCNTextModalHeard"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterTitle'); ?></label></div>
            <div class="modal-body">
                <div class="xCNTabCondition">
                    <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterTitleProduct'); ?></label>
                    <!-- Browse Pdt -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- จากรหัสสินค้า -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterCodeFrom'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="input100 xCNHide" id="oetADCFilterPdtCodeFrom" name="oetADCFilterPdtCodeFrom" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCFilterPdtNameFrom" name="oetADCFilterPdtCodeName" value="" readonly="">
                                    <span class="input-group-btn xWConditionSearchPdt">
                                        <button id="obtADCBrowseFilterProductFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                            <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- จากรหัสสินค้า -->
                        </div>
                        <div class="col-md-6">
                            <!-- ถึงรหัสสินค้า -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterCodeTo'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="input100 xCNHide" id="oetADCFilterPdtCodeTo" name="oetADCFilterPdtCodeTo" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCFilterPdtNameTo" name="oetADCFilterPdtNameTo" value="" readonly="">
                                    <span class="input-group-btn xWConditionSearchPdt">
                                        <button id="obtADCBrowseFilterProductTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                            <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- ถึงรหัสสินค้า -->
                        </div>
                    </div>
                    <!-- Browse Pdt -->
                </div>

                <div class="xCNTabCondition">
                    <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterTitleBarcode'); ?></label>
                    <!-- Browse Barcode -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- จากรหัสบาร์โค้ด -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterCodeFrom'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="input100 xCNHide" id="oetADCFilterBarCodeFrom" name="oetADCFilterBarCodeFrom" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCFilterBarCodeNameFrom" name="oetADCFilterBarCodeNameFrom" value="" readonly="">
                                    <span class="input-group-btn xWConditionSearchPdt">
                                        <button id="obtADCBrowseFilterBarCodeFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                            <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- จากรหัสบาร์โค้ด -->
                        </div>
                        <div class="col-md-6">
                            <!-- ถึงรหัสบาร์โค้ด -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCFilterCodeTo'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="input100 xCNHide" id="oetADCFilterBarCodeCodeTo" name="oetADCFilterBarCodeCodeTo" value="">
                                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetADCFilterBarCodeNameTo" name="oetADCFilterBarCodeNameTo" value="" readonly="">
                                    <span class="input-group-btn xWConditionSearchPdt">
                                        <button id="obtADCBrowseFilterBarCodeTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                            <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <!-- ถึงรหัสบาร์โค้ด -->
                        </div>
                    </div>
                    <!-- Browse Barcode -->
                </div>
            </div>
            <div class="modal-footer">
                <button id="obtADCConfirmFilter" type="button" class="btn xCNBTNPrimery"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubBtnFilterConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="odvADCPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/adjuststock/adjuststock', 'tASTCanDoc'); ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/adjuststock/adjuststock', 'tASTDocRemoveCantEdit'); ?></p>
                <p><strong><?php echo language('document/adjuststock/adjuststock', 'tASTCancel'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnADCCancelDoc(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<div id="odvADCModalAppoveDoc" class="modal fade xCNModalApprove">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?php echo language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxADCApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvADCPopupChangeTypeAdj">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/adjustmentcost/adjustmentcost', 'tASTWarning'); ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocChangeType'); ?></p>
                <p><strong><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCDocChangeTypeConfrim'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxADCClearData()" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button onclick="JSxADCNotClearData()" type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================== -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvADCModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/deliveryorder/deliveryorder', 'tDOPdtNotFound') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvADCModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/deliveryorder/deliveryorder', 'tDOSelectPdt') ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/deliveryorder/deliveryorder', 'tDOChoose') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/deliveryorder/deliveryorder', 'tDOClose') ?></button>
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
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvADCModalPDTConfirmDel" class="modal fade">
    <div class="modal-dialog" id="modal-customsWanning" role="document" style="margin: 1.75rem auto;top:20%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('common/main/main', 'tModalWarning') ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-striped xCNTablePDTMoreOne">
                    <thead>
                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPdtDelConfirm1'); ?>
                        <br>
                        <?php echo language('document/adjustmentcost/adjustmentcost', 'tADCPdtDelConfirm2'); ?>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTDel(2)" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tCancel') ?>
                </button>
                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTDel(1)" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jAdjustmentcostAdd.php'); ?>


<script>
    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();

        JSxCheckPinMenuClose();
        if (tValue.length === 0) {

        } else {
            // JCNxOpenLoading();
            $('#oetADCInsertBarcode').attr('readonly', true);
            JCNSearchBarcodePdt(tValue);
            $('#oetADCInsertBarcode').val('');
        }

        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {
        var tWhereCondition = "";
        // if( tPISplCode != "" ){
        //     tWhereCondition = " AND FTPdtSetOrSN IN('1','2') ";
        // }

        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                // aPriceType      : ['Price4Cst',tDOPplCode],
                aPriceType: ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc: "",
                SPL: $("#oetADCFrmSplCode").val(),
                BCH: $("#ohdADCBchCode").val(),
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode: $('#ohdADCUsrCode').val(),
                tInpLangEdit: $('#ohdADCLangEdit').val(),
                tInpSesUsrLevel: $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                Where: [tWhereCondition],
                tTextScan: ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $('#oetADCInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetADCInsertBarcode').attr('readonly', false);
                    $('#odvADCModalPDTNotFound').modal('show');
                    $('#oetADCInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvADCModalPDTMoreOne').modal('show');
                        $('#odvADCModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for (i = 0; i < oText.length; i++) {
                            var aNewReturn = JSON.stringify(oText[i]);
                            var tTest = "[" + aNewReturn + "]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne" + i + " xCNColumnPDTMoreOne' data-information='" + oEncodePackData + "' style='cursor: pointer;'>";
                            tHTML += "<td>" + oText[i].pnPdtCode + "</td>";
                            tHTML += "<td>" + oText[i].packData.PDTName + "</td>";
                            tHTML += "<td>" + oText[i].packData.PUNName + "</td>";
                            tHTML += "<td>" + oText[i].ptBarCode + "</td>";
                            tHTML += "</tr>";
                            $('#odvADCModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvADCModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            $.ajax({
                                type: "POST",
                                url: "docADCGetPdtFromProductCode",
                                data: {
                                    'aProduct': JSON.parse(tJSON),
                                    'tBchCode': $('#oetADCFrmBchCode').val()
                                },
                                cache: false,
                                timeout: 0,
                                success: function(oResult) {
                                    $('#oetADCInsertBarcode').attr('readonly', false);
                                    var oText = JSON.parse(oResult);
                                    var tChkPdt = oText.aData[0].FTPdtCode;
                                    var tFlag = '0';
                                    $(".xCNTextDetail").each(function(indexInArray, valueOfElement) {
                                        if ($(this).data('pdt') == tChkPdt) {
                                            tFlag = '1';
                                        }
                                    });
                                    if (tFlag == '0') {
                                        $('#oetADCInsertBarcode').attr('readonly', false);
                                        JSxADCShowTable(oText)
                                    } else {
                                        $('#oetADCInsertBarcode').attr('readonly', false);
                                        $('#odvADCModalPDTMoreOne').modal('hide');
                                        $('#odvADCModalPDTConfirmDel').modal('show');
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {

                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'left');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'left');
                        });
                    } else {
                        //มีตัวเดียว
                        var aNewReturn = JSON.stringify(oText);
                        $.ajax({
                            type: "POST",
                            url: "docADCGetPdtFromProductCode",
                            data: {
                                'aProduct': JSON.parse(aNewReturn),
                                'tBchCode': $('#oetADCFrmBchCode').val()
                            },
                            cache: false,
                            timeout: 0,
                            success: function(oResult) {
                                $('#oetADCInsertBarcode').attr('readonly', false);
                                var oText = JSON.parse(oResult);
                                var tChkPdt = oText.aData[0].FTPdtCode;
                                var tcount = 0;
                                $(".xCNTextDetail").each(function(indexInArray, valueOfElement) {
                                    if ($(this).data('pdt') == tChkPdt) {
                                        tcount++;
                                    }
                                });
                                if (tcount == 0) {
                                    JSxADCShowTable(oText)
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR,textStatus,errorThrown);
                JCNSearchBarcodePdt(ptTextScan);
            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType) {
        if ($ptType == 1) {
            $("#odvADCModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                $.ajax({
                    type: "POST",
                    url: "docADCGetPdtFromProductCode",
                    data: {
                        'aProduct': JSON.parse(tJSON),
                        'tBchCode': $('#oetADCFrmBchCode').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var oText = JSON.parse(oResult);
                        var tChkPdt = oText.aData[0].FTPdtCode;
                        var tFlag = '0';
                        $(".xCNTextDetail").each(function(indexInArray, valueOfElement) {
                            if ($(this).data('pdt') == tChkPdt) {
                                tFlag = '1';
                            }
                        });
                        if (tFlag == '0') {
                            $('#oetADCInsertBarcode').attr('readonly', false);
                            JSxADCShowTable(oText)
                        } else {
                            $('#oetADCInsertBarcode').attr('readonly', false);
                            $('#odvADCModalPDTMoreOne').modal('hide');
                            $('#odvADCModalPDTConfirmDel').modal('show');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        } else {
            $('#oetADCInsertBarcode').attr('readonly', false);
            $('#oetADCInsertBarcode').val('');
        }
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTDel($ptType) {
        if ($ptType == 1) {
            $("#odvADCModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                $.ajax({
                    type: "POST",
                    url: "docADCGetPdtFromProductCode",
                    data: {
                        'aProduct': JSON.parse(tJSON),
                        'tBchCode': $('#oetADCFrmBchCode').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult) {
                        var oText = JSON.parse(oResult);
                        var tChkPdt = oText.aData[0].FTPdtCode;
                        $(".xCNTextDetail").each(function(indexInArray, valueOfElement) {
                            if ($(this).data('pdt') == tChkPdt) {
                                JSxADCAutoRemoveDTRow(this);
                            }
                        });
                        $('#oetADCInsertBarcode').attr('readonly', false);
                        JSxADCShowTable(oText)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        } else {
            $('#odvADCModalPDTMoreOne').modal('show');
            $('#odvADCModalPDTConfirmDel').modal('hide');
        }
    }

    function JSxNotFoundClose() {
        $('#oetADCInsertBarcode').focus();
    }
</script>
