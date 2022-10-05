<style>
    #odvSALRowDataEndOfBill .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvSALRowDataEndOfBill .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvSALRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }

    .mark-font,
    .panel-default>.panel-heading.mark-font {
        color: #232C3D !important;
        font-weight: 900;
    }

    .xCNHideTD {
        border: none;
    }

    .xCNViewDetailBtn {
        cursor: pointer;
        color: #0081c2;
    }
</style>
<?php
$tRoute = $tRoute;
if (isset($tRoute) && $tRoute == 'docTXOWithdrawEventEdit') {
    $nStaPage = 2; //ขาแก้ไข
    $aDetail = $aDataGetDetail['raItems'][0];
    $aDataSumVat = $aSumVat['raItems'][0];

    //เอกอสารหลัก
    $tSALDocNo = $aDetail['FTXshDocNo'];
    $dSALDocDate = date("Y-m-d", strtotime($aDetail['FDXshDocDate']));
    $dSALDocTime = $aDetail['FTXshDocTime'];
    $tSALCreateBy = $aDetail['FTCreateBy'];
    $tSALUsrNameCreateBy = $aDetail['FTNameCreateBy'];


    $tSALApvCode = $aDetail['FTXshApvCode'];
    $tSALUsrNameApv = $aDetail['ApvBy'];
    $tSALStaDoc  = $aDetail['FTXshStaDoc'];
    $tSALApv = $aDetail['FTXshStaApv'];

    //ตัวแทนขาย
    $tSALAgnCode = $aDetail['FTAgnCode'];
    $tSALAgnName = $aDetail['FTAgnName'];

    //สาขา
    $tSALBchCode = $aDetail['FTBchCode'];
    $tSALBchName = $aDetail['FTBchName'];

    //ลูกค้า
    $tCstCode = $aDetail['FTCstCode'];
    $tCstName = $aDetail['FTCstName'];
    $tCstTel = $aDetail['FTCstTel'];
    $tCstEmail = $aDetail['FTCstEmail'];

    //เอกสารอ้างอิง
    $tSALRefType = $aDetail['FTXshRefType1'];
    $tDocRefNo = $aDetail['FTXshRefDocNo1'];
    if ($aDetail['FDXshRefDocDate1'] != '') {
        $tDocRefDate = date("Y-m-d", strtotime($aDetail['FDXshRefDocDate1']));
    } else {
        $tDocRefDate = '';
    }
    $tDocToPos = $aDetail['FTSpsName'];
    $tSALRefType3 = $aDetail['FTXshRefType3'];
    $tDocRefNo3 = $aDetail['FTXshRefDocNo3'];
    $tDocRefDate3 = date("Y-m-d", strtotime($aDetail['FDXshRefDocDate3']));

    //ข้อมูลรถ
    $tCarCode = $aDetail['FTCarCode'];
    $tCarRegNo = $aDetail['FTCarRegNo'];
    $tCarEngineNo = $aDetail['FTCarEngineNo'];
    $tCarVIDRef = $aDetail['FTCarVIDRef'];
    $tCarType = $aDetail['FTCarType'];
    $tCarBrand = $aDetail['FTCarBrand'];
    $tCarModel = $aDetail['FTCarModel'];
    $tCarColor = $aDetail['FTCarColor'];
    $tCarGear = $aDetail['FTCarGear'];
    $tCarPowerType = $aDetail['FTCarPowerType'];
    $tCarEngineSize = $aDetail['FTCarEngineSize'];
    $tCarCategory = $aDetail['FTCarCategory'];
    $tCarMileage = $aDetail['FCXshCarMileage'];
    $tCarProvince = $aDetail['FTPvnName'];

    //ผู้ประเมิน
    $tUsrCode = $aDetail['FTUsrCode'];
    $tUsrName = $aDetail['FTNameCreateBy'];
    $tUsrDateCreate = date("Y-m-d", strtotime($aDetail['FDCreateOn']));
    $tDateCreate = $aDetail['FDCreateOn'];
    $tReadonly = '';
    $tDisabled = '';
    $dStartDateChk = date("Y-m-d", strtotime($aDetail['FDXshStartChk']));
    $dStartTimeChk = $aDetail['FDXshStartChkTime'];
    $dEndDateChk = date("Y-m-d", strtotime($aDetail['FDXshFinishChk']));
    $dEndTimeChk = $aDetail['FDXshFinishChkTime'];

    //อื่นๆ
    $nSALStaDocAct = $aDetail['FNXshStaDocAct'];
    $tSALFrmRmk = $aDetail['FTXshRmk'];
    $nStaUploadFile        = 2;
    $nXshGrand              = $aDetail['FCXshGrand'];
    $nXshTotal              = $aDetail['FCXshTotal'];
    $tXshDisChgTxt          = $aDetail['FTXshDisChgTxt'];
    $nXshDis                = $aDetail['FCXshDis'];
    $nXshTotalAfDisChgV     = $aDetail['FCXshTotalAfDisChgV'];
    $nXshVat                = $aDataSumVat['FCXsdVat'];
}

if ($tSALStaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tSALStaDoc == 1 && $tSALApv == '') {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tSALStaDoc == 1 && $tSALApv == 1) {
        $tClassStaDoc = 'text-success';
        $tStaDoc = language('common/main/main', 'tStaDoc1');
    } else {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    }
}
?>
<input type="hidden" id="ohdSALRoute" name="ohdSALRoute" value="<?= $tRoute ?>">
<input type="hidden" id="ohdSALCheckClearValidate" name="ohdSALCheckClearValidate" value="0">
<input type="hidden" id="ohdSALCheckSubmitByButton" name="ohdSALCheckSubmitByButton" value="0">

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
<form id="ofmSALAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <!-- Job2Order -->
    <input type="hidden" id="ohdSALOldAgnCode" name="ohdSALOldAgnCode" value="<?= $tSALAgnCode ?>">
    <input type="hidden" id="ohdSALOldBchCode" name="ohdSALOldBchCode" value="<?= $tSALBchCode ?>">
    <input type="hidden" id="ohdSALOldDecRefNo" name="ohdSALOldDecRefNo" value="<?= $tDocRefNo ?>">
    <!-- end -->

    <!-- sta Doc -->
    <input type="hidden" id="ohdSALStaDoc" name="ohdSALStaDoc" value="<?= $tSALStaDoc ?>">
    <input type="hidden" id="ohdSALStaApv" name="ohdSALStaApv" value="<?= $tSALApv ?>">
    <input type="hidden" id="ohdSALStaApvCode" name="ohdSALStaApvCode" value="<?= $tSALApvCode ?>">
    <!-- end -->

    <input type="hidden" id="ohdSALCarCode" name="ohdSALCarCode" value="<?= $tCarCode ?>">
    <input type="hidden" id="ohdSALCreateOn" name="ohdSALCreateOn" value="<?= $tDateCreate ?>">
    <input type="hidden" id="ohdSALOldDocRefCode" name="ohdSALOldDocRefCode" value="<?php echo $tDocRefNo; ?>">

    <button style="display:none" type="submit" id="obtSubmitSAL" onclick="JSoAddEditSAL('<?= $tRoute ?>')"></button>
    <div class="row">
    <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSALHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSALDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                     <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                     <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvSALDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?></label>
                                <?php if (isset($tSALDocNo) && empty($tSALDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbSALStaAutoGenCode" name="ocbSALStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetSALDocNo" name="oetSALDocNo" maxlength="20" value="<?php echo $tSALDocNo; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tSATPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdSALCheckDuplicateCode" name="ohdSALCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetSALDocDate" name="oetSALDocDate" value="<?php echo $dSALDocDate; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSALDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetSALDocTime" name="oetSALDocTime" value="<?php echo $dSALDocTime; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSALDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdSALCreateBy" name="ohdSALCreateBy" value="<?php echo $tSALCreateBy ?>">
                                            <label><?php echo $tSALUsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tRoute == "docSALEventAdd") {
                                                $tSALLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tSALLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tSALStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tSALLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label class="<?php echo $tClassStaDoc; ?>">
                                                <?php echo $tStaDoc; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvSALApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdSALApvCode" name="ohdSALApvCode" maxlength="20" value="<?php echo $tSALApvCode ?>">
                                            <label>
                                                <?php echo (isset($tSALUsrNameApv) && !empty($tSALUsrNameApv)) ? $tSALUsrNameApv : "-" ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSALRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'ข้อมูลลูกค้า'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSALRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSALRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse ตัวแทนขาย -->
                                <div class="form-group xCNHide">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetSALAgnCode" name="oetSALAgnCode" maxlength="5" value="<?= @$tSALAgnCode; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetSALAgnName" name="oetSALAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tSALAgnName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterSplCode'); ?>" readonly>
                                    </div>
                                </div>

                                <!-- Browse สาขา -->
                                <div class="form-group xCNHide">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSALBchCode" name="ohdSALBchCode" maxlength="5" value="<?php echo @$tSALBchCode ?>" data-bchcodeold="<?php echo @$tSALBchCode ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetSALBchName" name="oetSALBchName" maxlength="100" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterBch'); ?>" value="<?php echo @$tSALBchName ?>" readonly>
                                    </div>
                                </div>
                                <!-- Browse ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSALCstCode" name="ohdSALCstCode" maxlength="5" value="<?php echo $tCstCode; ?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetSALCstName" name="oetSALCstName" maxlength="100" value="<?php echo $tCstName; ?>" readonly data-validate-required="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tSALCstValidate'); ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTitle'); ?>">
                                    </div>
                                </div>

                                <!-- ที่อยู่ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'ที่อยู่'); ?></label>
                                    <?php @$tAddress = $aCSTAddress[0]; ?>
                                    <?php if (@$tAddress['FTAddVersion'] == 1) { ?>
                                        <textarea name="otaSALCstAddress" id="otaSALCstAddress" cols="30" rows="4" readonly>
                                            <?= @$tAddress['FTAddV1No'] ?> <?= @$tAddress['FTAddV1Soi'] ?> <?= @$tAddress['FTAddV1Road'] ?> <?= @$tAddress['FTSudName'] ?> <?= @$tAddress['FTDstName'] ?> <?= @$tAddress['FTPvnName'] ?> <?= @$tAddress['FTAddV1PostCode'] ?>
                                        </textarea>
                                    <?php } elseif (@$tAddress['FTAddVersion'] == 2) { ?>
                                        <textarea name="otaSALCstAddress" id="otaSALCstAddress" cols="30" rows="4" readonly><?= @$tAddress['FTAddV2Desc1'] ?> <?= @$tAddress['FTAddV2Desc2'] ?></textarea>
                                    <?php } else { ?>
                                        <textarea name="otaSALCstAddress" id="otaSALCstAddress" cols="30" rows="4" readonly>-</textarea>
                                    <?php } ?>
                                </div>

                                <!-- เบอร์โทรศัพท์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstTelNo'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSALCstTel" name="oetSALCstTel" placeholder="<?php echo language('customer/customer/customer', 'tCstTelNo'); ?>" value="<?php echo $tCstTel; ?>" readonly>
                                </div>

                                <!-- e-mail -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTEmail'); ?></label>
                                    <input type="email" class="form-control xCNInputWhenStaCancelDoc" id="oetSALCstMail" name="oetSALCstMail" placeholder="<?php echo language('customer/customer/customer', 'tCSTEmail'); ?>" value="<?php echo $tCstEmail; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อ้างอิงเอกสาร -->
            <div class="panel panel-default xCNHide" style="margin-bottom: 25px;">
                <div id="odvSALInfoCst" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อ้างอิงเอกสาร'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSALDataInfoCst" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSALDataInfoCst" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- อ้างอืงใบรับรถ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/joborder/joborder', 'tJOBDocRefIntNo'); ?></label>
                                    <div class="form-group">
                                        <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote xCNClaerValWhenCstChange" id="ohdSALDocRefCode" name="ohdSALDocRefCode" maxlength="5" value="<?php echo $tDocRefNo; ?>">
                                        <input type="text" class="form-control xWPointerEventNone xCNClaerValWhenCstChange" id="oetSALDocRefCode" name="oetSALDocRefCode" maxlength="100" value="<?php echo $tDocRefNo; ?>" readonly data-validate-required="<?php echo language('document/joborder/joborder', 'tJOBDocRefIntNo'); ?>" placeholder="<?php echo language('customer/customer/customer', 'tIASDocRefTask'); ?>">
                                    </div>
                                </div>

                                <!-- วันที่อ้างอิงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/joborder/joborder', 'tJOBDocRefIntDate'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALDateStaService" name="oetSALDateStaService" value="<?= $tDocRefDate ?>" placeholder="<?php echo language('document/joborder/joborder', 'tJOBDocRefIntDate'); ?>" readonly>
                                </div>

                                <!-- อ้างอิงเอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefExt'); ?></label>
                                    <input type="hidden" id="ohdSALDocRefExtCode" name="ohdSALDocRefExtCode" value="<?= $tDocRefNo3 ?>">
                                    <input type="text" class="form-control xControlForm xCNInputWhenStaCancelDoc" id="oetSALDocRefExtCode" name="oetSALDocRefExtCode" maxlength="100" value="<?php
                                                                                                                                                                                                if ($tSALRefType3 == 3) {
                                                                                                                                                                                                    echo $tDocRefNo3;
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo '';
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefExt'); ?>">
                                </div>
                                <!-- วันที่อ้างอิงเอกสารภายนอก  -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefDateExt'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetSALDocRefExtDate" name="oetSALDocRefExtDate" value="<?php
                                                                                                                                                                                        if ($tSALRefType3 == 3) {
                                                                                                                                                                                            echo $tDocRefDate3;
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo '';
                                                                                                                                                                                        }
                                                                                                                                                                                        ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefDateExt'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSALDocRefExtDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSALInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/joborder/joborder', 'tJOBOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSALDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSALDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <?php
                                        $tSALStaAct = '';
                                        if ($nSALStaDocAct == 1) {
                                            $tSALStaAct = 'checked';
                                        } elseif ($nSALStaDocAct == 2) {
                                            $tSALStaAct = '';
                                        } else {
                                            $tSALStaAct = 'checked';
                                        }
                                        ?>
                                        <input type="checkbox" value="1" id="ocbSALStaDocAct" name="ocbSALStaDocAct" maxlength="1" <?php echo $tSALStaAct; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>

                                <!-- ช่องให้บริการ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceToPos'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALServiceToPos" name="oetSALServiceToPos" value="<?= $tDocToPos ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceToPos'); ?>" readonly>
                                </div>

                                <!-- วันที่เริ่มตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocDateBegin'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetSALDocDateBegin" name="oetSALDocDateBegin" value="<?php echo $dStartDateChk; ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASLabelFrmDocDateBegin'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSALDocDateBegin" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่เสร็จสิ้นการตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocDateEnd'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetSALDocDateEnd" name="oetSALDocDateEnd" value="<?php echo $dEndDateChk; ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASLabelFrmDocDateEnd'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSALDocDateEnd" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSALDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSALDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvSALShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSALCallDataTableFile = {
                        ptElementID     : 'odvSALShowDataTable',
                        ptBchCode       : $('#ohdSALBchCode').val(),
                        ptDocNo         : $('#oetSALDocNo').val(),
                        ptDocKey        : 'TSVTSalTwoHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdSALStaApv').val(),
                        ptStaDoc        : $('#ohdSALStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oSALCallDataTableFile);
                </script>
            </div>

        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ข้อมูลรถ -->
                <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarData'); ?></label>
                        </div>
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="row p-t-10">
                                    <div class="form-group">
                                        <!-- ทะเบียน -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceCarNo'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarNo" name="oetSALCarNo" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceCarNo'); ?>" value="<?php echo $tCarRegNo; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- จังหวัด -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarProvince'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALProvinceName" name="oetSALProvinceName" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarProvince'); ?>" value="<?php echo $tCarProvince; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- เลขเครื่องยนต์ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarEngineCode'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarEngineCode" name="oetSALCarEngineCode" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarEngineCode'); ?>" value="<?php echo $tCarEngineNo; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- เลขตัวถัง -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarPowerCode'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarPowerCode" name="oetSALCarPowerCode" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarPowerCode'); ?>" value="<?php echo $tCarVIDRef; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- ประเภท/ลักษณะ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle1'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarType" name="oetSALCarType" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle1'); ?>" value="<?php echo $tCarCategory; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- ประเภท/เจ้าของ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle8'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarOwnerType" name="oetSALCarOwnerType" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle8'); ?>" value="<?php echo $tCarType; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- ยี่ห้อ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle2'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarBrand" name="oetSALCarBrand" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle2'); ?>" value="<?php echo $tCarBrand; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- รุ่น -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle3'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarModel" name="oetSALCarModel" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle3'); ?>" value="<?php echo $tCarModel; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- สี -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle4'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarColor" name="oetSALCarColor" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle4'); ?>" value="<?php echo $tCarColor; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- เกียร์ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle5'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarGear" name="oetSALCarGear" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle5'); ?>" value="<?php echo $tCarGear; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- เครื่องยนต์ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle6'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarEngineOil" name="oetSALCarEngineOil" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle6'); ?>" value="<?php echo $tCarPowerType; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- ขนาดเครื่องยนต์ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarCldVol'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarCldVol" name="oetSALCarCldVol" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarCldVol'); ?>" value="<?php echo $tCarEngineSize; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- เลขไมล์ -->
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarMileAge'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetSALCarMileAge" name="oetSALCarMileAge" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarMileAge'); ?>" value="<?php echo $tCarMileage; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ตารางรายการคำถาม -->
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBSeq'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBPdtCode'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBList'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBPdtType'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBQty'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBPun'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBPrice'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBDiscount'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder', 'tJOBPriceTotal'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $nSeq = 0 ?>
                                            <?php foreach ($aDataDetail['raItems'] as $key => $tVal) { ?>
                                                <?php
                                                $nSetKey    = $tVal['FNPstSeqNo'];
                                                ?>
                                                <tr>
                                                    <?php if ($nSeq != $tVal['FNXsdSeqNo']) { ?>
                                                        <td class="text-center" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?= $tVal['FNXsdSeqNo']; ?></td>
                                                    <?php } ?>
                                                    <?php

                                                    $tTextPdtSetOrSN    = "";
                                                    switch ($tVal['FTPdtSetOrSN']) {
                                                        case '1':
                                                            $tTextPdtSetOrSN = "ทั่วไป";
                                                            break;
                                                        case '2':
                                                            $tTextPdtSetOrSN = "สินค้าชุดปกติ";
                                                            break;
                                                        case '5':
                                                            $tTextPdtSetOrSN = "บริการชุด";
                                                            break;
                                                        default:
                                                            $tTextPdtSetOrSN = "ไม่ได้ระบุ";
                                                            break;
                                                    }

                                                    $tPsvType = "";
                                                    switch ($tVal['FTPsvType']) {
                                                        case '1':
                                                            $tPsvType   = "ต้องเปลี่ยน";
                                                            break;
                                                        case '2':
                                                            $tPsvType   = "ต้องตรวจสอบ";
                                                            break;
                                                        case '0':
                                                            $tPsvType   = "สินค้าชุดปกติ";
                                                            break;
                                                    }

                                                    // if ($tVal['FTPsvType'] == 1) {
                                                    //     $tPsvType = language('document/joborder/joborder','tJOBChangeHavePrice');
                                                    // }elseif ($tVal['FTPsvType'] == 2) {
                                                    //     $tPsvType = language('document/joborder/joborder','tJOBCheckNoPrice');
                                                    // }else{
                                                    //     $tPsvType = language('document/joborder/joborder','tJOBServiceSet');
                                                    // }
                                                    ?>
                                                    <?php if ($tVal['PDTSetOrPDT'] == 1) { ?>

                                                        <?php
                                                        //ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                                        if ($tVal['PARTITIONBYDOC'] > 1) {
                                                            $tCssBorder = "border-bottom: 1px solid #ffffff !important;";
                                                        } else {
                                                            $tCssBorder = "";
                                                        }
                                                        ?>
                                                        <td rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo $tVal['FTPdtCode'] ?></td>
                                                        <td style="<?= $tCssBorder ?>"><?php echo $tVal['FTXsdPdtName'] ?></td>
                                                        <td style="<?= $tCssBorder ?>"><?php echo $tTextPdtSetOrSN ?></td>
                                                        <td class="text-right" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo number_format($tVal['FCXsdQty']) ?></td>
                                                        <td class="text-left" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo $tVal['FTPunName'] ?></td>
                                                        <td class="text-right" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo number_format($tVal['FCXsdSalePrice'], 2) ?></td>
                                                        <td class="text-right" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo number_format($tVal['FCXsdDis'], 2) ?></td>
                                                        <td class="text-right" rowspan="<?= $tVal['PARTITIONBYDOC']; ?>"><?php echo number_format($tVal['FCXsdNetAfHD'], 2) ?></td>
                                                    <?php } else { ?>
                                                        <?php
                                                        //ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                                        if ($tVal['PARTITIONBYDOC'] == $nSetKey) {
                                                            $tCssBorder = "";
                                                        } else {
                                                            $tCssBorder = "border-bottom: 1px solid #ffffff !important;";
                                                        }
                                                        ?>
                                                        <td style="<?= $tCssBorder ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tVal['FTXsdPdtName'] ?></td>
                                                        <td style="<?= $tCssBorder ?>"><?php echo $tPsvType ?></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php $nSeq = $tVal['FNXsdSeqNo']; ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row p-t-10" id="odvSALRowDataEndOfBill">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/depositdoc/depositdoc', 'tDPSVatAndRmk'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div style='padding: 10px 10px 0px 10px;'>
                                                <!-- หมายเหตุ -->
                                                <div class="form-group">
                                                    <textarea class="form-control" id="otaSALFrmInfoOthRmk" name="otaSALFrmInfoOthRmk" maxlength="200"><?php echo $tSALFrmRmk ?></textarea>
                                                </div>
                                            </div>

                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'tPOTBVatRate'); ?></div>
                                                <div class="pull-right mark-font"><?= language('document/purchaseorder/purchaseorder', 'tPOTBAmountVat'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group" id="oulIVDataListVat">
                                                    <?php foreach ($aVatRate['raItems'] as $key => $tVal) { ?>
                                                        <label class="pull-left"><?= $tVal['FCXsdVatRate'] ?>%</label>
                                                        <label class="pull-right" id="olbSALSumFCXtdNet"><?= $tVal['FCXsdVat'] ?></label><br>
                                                        <div class="clearfix"></div>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'tPOTBTotalValVat'); ?></label>
                                                <label class="pull-right mark-font" id="olbSALVatSum"><?= $nXshVat ?></label>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                                        <div class="panel panel-default">
                                            <div class="panel-heading mark-font" id="odvSALDataTextBath"></div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'tPOTBSumFCXtdNet'); ?></label>
                                                        <label class="pull-right mark-font" id="olbSALSumFCXtdNet"><?= number_format($nXshTotal, 2) ?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder', 'tPOTBDisChg'); ?></label>
                                                        <label class="pull-left" style="margin-left: 5px;" id="olbSALDisChgHD"><?= $tXshDisChgTxt ?></label>
                                                        <label class="pull-right" id="olbSALSumFCXtdAmt"><?= number_format($nXshDis, 2) ?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder', 'tPOTBSumFCXtdNetAfHD'); ?></label>
                                                        <label class="pull-right" id="olbSALSumFCXtdNetAfHD"><?= number_format($nXshTotalAfDisChgV, 2) ?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder', 'tPOTBSumFCXtdVat'); ?></label>
                                                        <label class="pull-right" id="olbSALSumFCXtdVat"><?= number_format($nXshVat, 2) ?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'tPOTBFCXphGrand'); ?></label>
                                                <input type="hidden" id="ohdSALCalFCXphGrand" value="<?php echo $nXshGrand; ?>">
                                                <label class="pull-right mark-font" id="olbSALCalFCXphGrand"><?php echo number_format($nXshGrand, 2); ?></label>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div id="odvSALDataDocRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th nowrap class="xCNTextBold text-center" width="20%"><?php echo language('document/joborder/joborder', 'tJOBDocRefType'); ?></th>
                                                        <th nowrap class="xCNTextBold text-center xCNHide" width="30%"><?php echo language('document/joborder/joborder', 'tJOBDocRefName'); ?></th>
                                                        <th nowrap class="xCNTextBold text-center" width="30%"><?php echo language('document/joborder/joborder', 'tJOBDocRefCode'); ?></th>
                                                        <th nowrap class="xCNTextBold text-center" width="20%"><?php echo language('document/joborder/joborder', 'tJOBDocRefDate'); ?></th>
                                                        <th nowrap class="xCNTextBold text-center xCNHide" width="10%"><?php //echo language('document/joborder/joborder','ตรวจสอบ');
                                                                                                                        ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($aAllDocRef['raItems'] && $aAllDocRef['raItems'] != "")) { ?>
                                                        <?php foreach ($aAllDocRef['raItems'] as $key => $tDocRef) { ?>
                                                            <?php
                                                            $tType = $tDocRef['FTXshRefKey'];

                                                            if ($tType == 'QT') {
                                                                $tTitle = language('document/quotation/quotation', 'tQTTitle');
                                                            } elseif ($tType == 'Job4Apv') {
                                                                $tTitle = language('document/inspectionafterservice/inspectionafterservice', 'tIASTitle');
                                                            } elseif ($tType == 'Job5Score') {
                                                                $tTitle = language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyTitle');
                                                            } elseif ($tType == 'Job1Req') {
                                                                $tTitle = language('document/jobrequest1/jobrequest1', 'tJR1Title');
                                                            } elseif ($tType == 'Job3Chk') {
                                                                $tTitle = language('document/prerepairresult/prerepairresult', 'tPreSurveyTitle');
                                                            } else {
                                                                $tTitle = 'tJOBDocRefExt';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <?php
                                                                if ($tDocRef['FTXshRefType'] == 1) {
                                                                    $tTitleType = "tJOBDocRefIn";
                                                                } elseif ($tDocRef['FTXshRefType'] == 2) {
                                                                    $tTitleType = "tJOBDocRefInDo";
                                                                } else {
                                                                    $tTitleType = "tJOBDocRefExt";
                                                                }
                                                                ?>
                                                                <td><label class="pull-left"><?= language('document/joborder/joborder', $tTitleType); ?></label></td>
                                                                <td class="xCNHide"><label class="pull-left"><?= language('document/quotation/quotation', $tTitle); ?></label></td>
                                                                <td class="text-left"><?= $tDocRef['FTXshRefDocNo'] ?></td>
                                                                <td class="text-center"><?= date("d/m/Y", strtotime($tDocRef['FDXshRefDocDate'])); ?></td>
                                                                <td class="text-center xCNHide" nowrap>
                                                                    <?php if ($tType == 'QT' || $tType == 'Job1Req' || $tType == 'Job3Chk' || $tType == 'Job4Apv' || $tType == 'Job5Score') { ?>
                                                                        <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSxGotoDocRefPage('<?= $tSALAgnCode ?>','<?= $tSALBchCode ?>','<?= $tDocRef['FTXshRefDocNo'] ?>','<?= $tType ?>')">
                                                                    <?php } else { ?>
                                                                        <img class="xCNIconTable" style="width: 17px; cursor:not-allowed;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>">
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>

                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="4"><label><?php echo language('document/reimbursement/reimbursement', 'tRBMNoDocRef') ?></label></td>

                                                        </tr>
                                                    <?php } ?>
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
    </div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jReimbursementPageForm.php'); ?>


<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function() {
        JSxSALSetRowSpan();

        var tTextTotal = $('#olbSALCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath = ArabicNumberToText(tTextTotal);
        $('#odvSALDataTextBath').text(tThaibath);
    })

    function JSxSALSetRowSpan() {
        $('.xWSALtr').each(function() {
            var tDataSALCode = $(this).data('seqno');
            var nContDataRowSpan = $('.xWSALLng' + tDataSALCode).length;
            $('.xWSALtd' + tDataSALCode).attr('rowspan', nContDataRowSpan);
        });
    }

    $('.xCNCheckBoxPoint').click(function(elem) {
        var nSeqdt = $(this).attr('data-seqdt');
        var tDocno = $(this).attr('data-docno');
        $('.xCNCheckBoxPoint' + tDocno + nSeqdt).prop("checked", false);
        $(this).prop("checked", true);
    });

    //กดเพื่อไปยังเอกสารอื่น
    function JSxGotoDocRefPage($ptSALAgnCode, $ptSALBchCode, $ptDocNo, $ptType) {
        var tDocNo = $('#oetSALDocNo').val();
        var tBchCode = $('#ohdSALBchCode').val();
        var tAgnCode = $('#oetSALAgnCode').val();
        var tCstCode = $('#ohdSALCstCode').val();

        if ($ptType == 'QT') { //ใบเสนอราคา
            var tRoute = 'docQuotation/0/0';
        } else if ($ptType == 'Job4Apv') { //ใบตรวจสภาพหลังบริการ
            var tRoute = 'docIAS/0/0';
        } else if ($ptType == 'Job5Score') {
            var tRoute = 'docSatisfactionSurvey/0/0';
        } else if ($ptType == 'Job3Chk') {
            var tRoute = 'docPreRepairResult/0/0';
        } else if ($ptType == 'Job1Req') {
            var tRoute = 'docJR1/0/0';
        }

        $.ajax({
            type: "GET",
            url: tRoute,
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                localStorage.tCheckBackStage = 'Job2';
                localStorage.tDocno = tDocNo;
                localStorage.tBchCode = tBchCode;
                localStorage.tAgnCode = tAgnCode;
                localStorage.tCstCode = tCstCode;

                if ($ptType == 'QT') { //ใบเสนอราคา
                    JSvQTCallPageEdit($ptDocNo);
                } else if ($ptType == 'Job4Apv') {
                    JSvIASCallPageEdit($ptSALAgnCode, $ptSALBchCode, $ptDocNo);
                } else if ($ptType == 'Job5Score') {
                    JSvSatSvCallPageEdit($ptSALAgnCode, $ptSALBchCode, $ptDocNo);
                } else if ($ptType == 'Job3Chk') {
                    JSvPreSvCallPageEdit($ptSALAgnCode, $ptSALBchCode, $ptDocNo);
                } else if ($ptType == 'Job1Req') {
                    var tRoute = 'docSatisfactionSurvey/0/0';
                }

            }
        });
    }
</script>


<!-- madal insert success -->
<div id="odvSALModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'บันทึกการประเมินสำเร็จ') ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- madal validate-->
<div id="odvSALModalvalidate" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'กรุณาตอบคำถามให้ครบถ้วน') ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvSALModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxSALApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvSALPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnSALCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->
