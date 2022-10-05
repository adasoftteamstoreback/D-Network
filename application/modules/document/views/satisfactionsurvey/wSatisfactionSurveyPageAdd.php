<?php
$tRoute = $tRoute;
if (isset($tRoute) && $tRoute == 'docSatisfactionSurveyEventEdit') {
    $nStaPage = 2; //ขาแก้ไข
    $aDetailSurvey = $aDataGetDetail['raItems'][0];

    //เอกอสารหลัก
    $tSatDocNo = $aDetailSurvey['FTXshDocNo'];
    $dSatDocDate = date("Y-m-d", strtotime($aDetailSurvey['FDXshDocDate']));
    $dSatDocTime = $aDetailSurvey['FTXshDocTime'];
    $tSatCreateBy = $aDetailSurvey['FTCreateBy'];
    $tSatUsrNameCreateBy = $aDetailSurvey['FTNameCreateBy'];


    $tSatApvCode = $aDetailSurvey['FTXshApvCode'];
    $tSatUsrNameApv = $aDetailSurvey['ApvBy'];
    $tSatRefType = $aDetailSurvey['FTXshRefType'];
    $tSatStaDoc  = $aDetailSurvey['FTXshStaDoc'];
    $tSatApv = $aDetailSurvey['FTXshStaApv'];

    //ตัวแทนขาย
    $tSatAgnCode = $aDetailSurvey['FTAgnCode'];
    $tSatAgnName = $aDetailSurvey['FTAgnName'];

    //สาขา
    $tSatBchCode = $aDetailSurvey['FTBchCode'];
    $tSatBchName = $aDetailSurvey['FTBchName'];

    //ลูกค้า
    $tCstCode = $aDetailSurvey['FTCstCode'];
    $tCstName = $aDetailSurvey['FTCstName'];
    $tCstTel = $aDetailSurvey['FTCstTel'];
    $tCstEmail = $aDetailSurvey['FTCstEmail'];

    //เอกสารอ้างอิง
    $tDocRefNo = $aDetailSurvey['FTXshRefDocNo'];
    $tDocRefDate = $aDetailSurvey['FDXshRefDocDate'];
    $tCarBrand = $aDetailSurvey['FTCarBrand'];
    $tCarModel = $aDetailSurvey['FTCarModel'];
    $tCarRegNo = $aDetailSurvey['FTCarRegNo'];
    $tBchCode = $aDetailSurvey['FTBchCode'];

    //ผู้ประเมิน
    $tUsrCode = $aDetailSurvey['FTUsrCode'];
    $tUsrName = $aDetailSurvey['SatSvBy'];
    $tUsrDateCreate = date("Y-m-d", strtotime($aDetailSurvey['FDCreateOn']));
    $tDateCreate = $aDetailSurvey['FDCreateOn'];
    $tReadonly = '';
    $tDisabled = '';

    //อื่นๆ
    $nSatStaDocAct = $aDetailSurvey['FNXshStaDocAct'];
    $tSatFrmRmk = $aDetailSurvey['FTXshRmk'];

    // คำตอบ
    $nRateScore = $aDetailSurvey['FNXshScoreValue'];
    $tSatComment = $aDetailSurvey['FTXshAdditional'];
    $nStaUploadFile        = 2;
} else {
    if ($aDataGetDetail['tReturn'] == 1) {
        $nStaPage = 1; //ขาเพิ่ม
        //เอกอสารหลัก
        $tSatDocNo = '';
        $dSatDocDate = '';
        $dSatDocTime = '';
        $tSatCreateBy = $this->session->userdata('tSesUsrUsername');
        $tSatUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');

        $tUsrLevel = $this->session->userdata('tSesUsrLevel');

        $tSatAgnCode = '';

        if ($tUsrLevel != "HQ") {
            $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
            $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
        }

        $tSatStaDoc  = '';
        $tClassStaDoc = '';
        $tStaDoc = '';
        $tSatApvCode = '';
        $tSatApv = '';
        $tSatUsrNameApv = '';
        $tSatRefType = '';

        //สาขา
        $tSatBchCode = '';
        $tSatBchName = '';

        //ลูกค้า
        $tCstCode = '';
        $tCstName = '';
        $tCstTel = '';
        $tCstEmail = '';

        //เอกสาร
        $tDocRefNo = '';
        $tDocRefDate = '';
        $tCarBrand = '';
        $tCarModel = '';
        $tCarRegNo = '';

        //ผู้ประเมิน
        $tUsrCode = $this->session->userdata('tSesUserCode');
        $tUsrName = $this->session->userdata('tSesUsrUsername');
        $tUsrDateCreate = '';
        $tDateCreate = '';
        $tReadonly = '';
        $tDisabled = '';

        //อื่นๆ
        $nSatStaDocAct = '';
        $tSatFrmRmk = '';

        // คำตอบ
        $nRateScore = '';
        $tSatComment = '';
        $nStaUploadFile        = 1;
    } else {
        $nStaPage = 1; //ขาเพิ่ม
        //เอกอสารหลัก
        $tSatDocNo = '';
        $dSatDocDate = '';
        $dSatDocTime = '';
        $tSatCreateBy = $this->session->userdata('tSesUsrUsername');
        $tSatUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');

        $tUsrLevel = $this->session->userdata('tSesUsrLevel');

        $tSatAgnCode = $aDataGetDetail['tAgnCode'];
        $tSatAgnName = $aDataGetDetail['tAgnName'];

        // if( $tUsrLevel != "HQ" ){
        //     $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
        //     $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
        // }

        $tSatStaDoc  = '';
        $tClassStaDoc = '';
        $tStaDoc = '';
        $tSatApvCode = '';
        $tSatApv = '';
        $tSatUsrNameApv = '';
        $tSatRefType = '';

        //สาขา
        $tSatBchCode = $aDataGetDetail['tBchCode'];
        $tSatBchName = $aDataGetDetail['tBchName'];

        //ลูกค้า
        $tCstCode = $aDataGetDetail['tCstCode'];
        $tCstName = $aDataGetDetail['tCstName'];
        $tCstTel = $aDataGetDetail['tCstTel'];
        $tCstEmail = $aDataGetDetail['tCstEmail'];

        //เอกสาร
        $tDocRefNo = $aDataGetDetail['tDocNo'];
        $tDocRefDate = $aDataGetDetail['dDocDate'];
        $tCarBrand = $aDataGetDetail['tCarBrand'];
        $tCarModel = $aDataGetDetail['tCarModel'];
        $tCarRegNo = $aDataGetDetail['tCarRegNo'];

        //ผู้ประเมิน
        $tUsrCode = $this->session->userdata('tSesUserCode');
        $tUsrName = $this->session->userdata('tSesUsrUsername');
        $tUsrDateCreate = '';
        $tDateCreate = '';
        $tReadonly = '';
        $tDisabled = '';

        //อื่นๆ
        $nSatStaDocAct = '';
        $tSatFrmRmk = '';

        // คำตอบ
        $nRateScore = '';
        $tSatComment = '';
        $nStaUploadFile        = 1;
    }
}

if ($tSatStaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tSatStaDoc == 1 && $tSatApv == '') {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tSatStaDoc == 1 && $tSatApv == 1) {
        $tClassStaDoc = 'text-success';
        $tStaDoc = language('common/main/main', 'tStaDoc1');
    } else {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    }
}
?>
<input type="hidden" id="ohdSatSvRoute" name="ohdSatSvRoute" value="<?= $tRoute ?>">
<input type="hidden" id="ohdSatSvCheckClearValidate" name="ohdSatSvCheckClearValidate" value="0">
<input type="hidden" id="ohdSatSvCheckSubmitByButton" name="ohdSatSvCheckSubmitByButton" value="0">

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


<form id="ofmSatSurveyAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <!-- Job2Order -->
    <input type="hidden" id="ohdSatSvOldAgnCode" name="ohdSatSvOldAgnCode" value="<?= $tSatAgnCode ?>">
    <input type="hidden" id="ohdSatSvOldBchCode" name="ohdSatSvOldBchCode" value="<?= $tSatBchCode ?>">
    <input type="hidden" id="ohdSatSvOldDecRefNo" name="ohdSatSvOldDecRefNo" value="<?= $tDocRefNo ?>">
    <!-- end -->

    <!-- sta Doc -->
    <input type="hidden" id="ohdSatStaDoc" name="ohdSatStaDoc" value="<?= $tSatStaDoc ?>">
    <input type="hidden" id="ohdSatStaApv" name="ohdSatStaApv" value="<?= $tSatApv ?>">
    <input type="hidden" id="ohdSatStaApvCode" name="ohdSatStaApvCode" value="<?= $tSatApvCode ?>">
    <!-- end -->

    <button style="display:none" type="submit" id="obtSubmitSat" onclick="JSoAddEditSat('<?= $tRoute ?>')"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSatHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSatDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvSatDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?></label>
                                <?php //if(isset($tSatDocNo) && empty($tSatDocNo)):
                                ?>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbSatStaAutoGenCode" name="ocbSatStaAutoGenCode" maxlength="1" checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmAutoGenCode'); ?></span>
                                    </label>
                                </div>
                                <?php //endif;
                                ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetSatDocNo" name="oetSatDocNo" maxlength="20" value="<?php echo $tSatDocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdSatCheckDuplicateCode" name="ohdSatCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetSatDocDate" name="oetSatDocDate" value="<?php echo $dSatDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSatDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetSatDocTime" name="oetSatDocTime" value="<?php //echo $dSatDocTime;
                                                                                                                                                                            ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSatDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <input type="hidden" id="ohdSatCreateBy" name="ohdSatCreateBy" value="<?php echo $tSatCreateBy ?>">
                                            <label><?php echo $tSatUsrNameCreateBy ?></label>
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
                                            if ($tRoute == "docSatisfactionSurveyEventAdd") {
                                                $tSatLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tSatLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tSatStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tSatLabelStaDoc; ?></label>
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
                                <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tRoute == "docSatisfactionSurveyEventAdd") {
                                                $tSatLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                $tRefType = '-';
                                            } else {
                                                if ($tSatRefType == 1) {
                                                    $tRefType = language('document/satisfactionsurvey/satisfactionsurvey', 'tSatRefType1');
                                                } elseif ($tSatRefType == 2) {
                                                    $tRefType = language('document/satisfactionsurvey/satisfactionsurvey', 'tSatRefType2');
                                                } else {
                                                    $tRefType = language('document/satisfactionsurvey/satisfactionsurvey', 'tSatRefType3');
                                                }
                                            }
                                            ?>
                                            <label><?php echo $tRefType; ?></label>

                                        </div>
                                    </div>
                                </div>

                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvSatApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdSatApvCode" name="ohdSatApvCode" maxlength="20" value="<?php echo $tSatApvCode ?>">
                                            <label>
                                                <?php echo (isset($tSatUsrNameApv) && !empty($tSatUsrNameApv)) ? $tSatUsrNameApv : "-" ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ข้อมูลอ้างอิงการประเมิน -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSatSurveyRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyRefData'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSatSurveyRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSatSurveyRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse ตัวแทนขาย -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide" id="oetSatAgnCode" name="oetSatAgnCode" maxlength="5" value="<?= @$tSatAgnCode; ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetSatAgnName" name="oetSatAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tSatAgnName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterSplCode'); ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="oimSatSvBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Browse สาขา -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSatBchCode" name="ohdSatBchCode" maxlength="5" value="<?php echo @$tSatBchCode ?>" data-bchcodeold="<?php echo @$tSatBchCode ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetSatBchName" name="oetSatBchName" maxlength="100" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterBch'); ?>" value="<?php echo @$tSatBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtSatSvBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <!-- Browse ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSatSurveyCstCode" name="ohdSatSurveyCstCode" maxlength="5" value="<?php echo $tCstCode; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetSatSurveyCstName" name="oetSatSurveyCstName" maxlength="100" value="<?php echo $tCstName; ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTitle'); ?>" readonly data-validate-required="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatCstValidate'); ?>">
                                            <span class="input-group-btn">
                                                <button id="oimBrowseSatSurveyCst" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- เบอร์โทรศัพท์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstTelNo'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSatSurveyCstTel" name="oetSatSurveyCstTel" placeholder="<?php echo language('customer/customer/customer', 'tCstTelNo'); ?>" value="<?php echo $tCstTel; ?>" readonly>
                                </div>

                                <!-- e-mail -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTEmail'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSatSurveyCstMail" name="oetSatSurveyCstMail" placeholder="<?php echo language('customer/customer/customer', 'tCSTEmail'); ?>" value="<?php echo $tCstEmail; ?>" readonly>
                                </div>

                                <!-- อ้างอืงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDocRefTask'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSatDocRefCode" name="ohdSatDocRefCode" maxlength="5" value="<?php echo $tDocRefNo; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetSatDocRefCode" name="oetSatDocRefCode" maxlength="100" value="<?php echo $tDocRefNo; ?>" placeholder="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDocRefTask'); ?>" readonly data-validate-required="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatDocNoValidate'); ?>">
                                            <span class="input-group-btn">
                                                <button id="oimBrowseDocRef" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- วันที่เข้ารับบริการ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceDate'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSatSurveyDateStaService" name="oetSatSurveyDateStaService" placeholder="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceDate'); ?>" value="<?php echo $tDocRefDate ?>" readonly>
                                </div>

                                <!-- รถที่เข้ารับบริการ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceCarName'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSatSurveySrvCar" name="oetSatSurveySrvCar" placeholder="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceCarName'); ?>" value="<?php echo $tCarBrand; ?> <?php echo $tCarModel; ?>" readonly>
                                </div>

                                <!-- ทะเบียน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceCarNo'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetSatSurveyCarNo" name="oetSatSurveyCarNo" placeholder="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyServiceCarNo'); ?>" value="<?php echo $tCarRegNo; ?>" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลพนักงาน -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSetUsrInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyUsrTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSatUsrData" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSatUsrData" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- ฺBrowse ข้อมูลพนักงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyUsr'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdSatTaskRefUsrCode" name="ohdSatTaskRefUsrCode" maxlength="5" value="<?php echo $tUsrCode; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetSatTaskRefUsrName" name="oetSatTaskRefUsrName" maxlength="100" value="<?php echo $tUsrName; ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="oimBrowseUsrBch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- วันที่ในการประเมิน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDate'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" id="ohdDateCreate" name="ohdDateCreate" value="<?php echo $tDateCreate; ?>">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate xCNInputWhenStaCancelDoc" id="oetSatSvDate" name="oetSatSvDate" value="<?php echo $tUsrDateCreate; ?>" <?php echo $tReadonly; ?>>
                                        <span class="input-group-btn">
                                            <button id="obtSatSvDate" type="button" class="btn xCNBtnDateTime xCNInputWhenStaCancelDoc"><img class="xCNIconCalendar"></button>
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
                <div id="odvSatInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSatDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSatDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <?php
                                        $tSatSvStaAct = '';
                                        if ($nSatStaDocAct == 1) {
                                            $tSatSvStaAct = 'checked';
                                        } elseif ($nSatStaDocAct == 2) {
                                            $tSatSvStaAct = '';
                                        } else {
                                            $tSatSvStaAct = 'checked';
                                        }
                                        ?>
                                        <input type="checkbox" value="1" id="ocbSatStaDocAct" name="ocbSatStaDocAct" maxlength="1" <?php echo $tSatSvStaAct; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                                    <textarea class="form-control" id="otaSatFrmInfoOthRmk" name="otaSatFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?php echo $tSatFrmRmk ?></textarea>
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
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSatSvDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSatSvDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvSatSvShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSatSvCallDataTableFile = {
                        ptElementID     : 'odvSatSvShowDataTable',
                        ptBchCode       : $('#ohdSatBchCode').val(),
                        ptDocNo         : $('#oetSatDocNo').val(),
                        ptDocKey        : 'TSVTJob5ScoreHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdSatStaApv').val(),
                        ptStaDoc        : $('#ohdSatStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oSatSvCallDataTableFile);
                </script>
            </div>

        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางรายการคำถาม -->
                <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="row p-t-10">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div style="position: absolute;left: 15px;top:-5px;">
                                                <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyRateing'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <div class="form-group">
                                            <div style="position: absolute;right: 15px;top:-3px;">
                                                <div class="xWRateing xWDisabled">
                                                    <input type="radio" class="xWRadioRate" name="orbSatSueveyRate" id="orbSatSueveyRate1" value="5" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 5) ? 'checked' : ''; ?>><label for="orbSatSueveyRate1"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbSatSueveyRate" id="orbSatSueveyRate2" value="4" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 4) ? 'checked' : ''; ?>><label for="orbSatSueveyRate2"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbSatSueveyRate" id="orbSatSueveyRate3" value="3" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 3) ? 'checked' : ''; ?>><label for="orbSatSueveyRate3"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbSatSueveyRate" id="orbSatSueveyRate4" value="2" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 2) ? 'checked' : ''; ?>><label for="orbSatSueveyRate4"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbSatSueveyRate" id="orbSatSueveyRate5" value="1" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 1) ? 'checked' : ''; ?>><label for="orbSatSueveyRate5"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div style="padding-top: 25px;">
                                                <div class="table-responsive">
                                                    <table class="table" id="otbDataTable">
                                                        <thead>
                                                            <tr class="xCNCenter">
                                                                <th class="xCNTextBold"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyQaGrp') ?></th>
                                                                <th class="xCNTextBold"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyQaName') ?></th>
                                                                <th class="xCNTextBold" style="width:350px;"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyAnswer') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $nSeqRowsB4  = 0; ?>
                                                            <?php $nSeqOld = 0; ?>
                                                            <?php $nSeqRowsB5  = ''; ?>
                                                            <?php foreach ($aDataQA['raItems'] as $key => $tVal) { ?>
                                                                <?php
                                                                if ($tVal['FTQahDocNo'] != $nSeqRowsB5) {
                                                                    $nSeqRowsB4  = 0;
                                                                }
                                                                if ($tVal['FNQadSeqNo'] != $nSeqRowsB4) { ?>
                                                                    <tr class="xWSattr xWSatLng<?= $tVal['FTQahDocNo'] ?>" data-docno='<?= $tVal['FTQahDocNo'] ?>'>
                                                                        <?php if ($nSeqRowsB4 == 0) { ?>
                                                                            <td nowrap class="xWSattd<?= $tVal['FTQahDocNo'] ?>"><?= $tVal['FTQsgName']; ?></td>
                                                                        <?php } ?>
                                                                        <td nowrap data-docno='<?= $tVal['FTQahDocNo'] ?>' data-seqdt='<?= $tVal['FNQadSeqNo'] ?>' data-quetype='<?= $tVal['FTQadType'] ?>' class="xWQuestion">
                                                                            <?= $tVal['FTQadName']; ?>
                                                                        </td>
                                                                        <td nowrap>
                                                                            <?php foreach ($aDataQA['raItems'] as $nSubKey => $aSubVal) { ?>
                                                                                <?php if ($aSubVal['FTQadType'] == 1) { ?>
                                                                                    <input type="hidden" name="ohdSatQahType1" value="<?= $aSubVal['FTQadType'] ?>">
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <label style="margin-left: 10px; display: inline-block;">
                                                                                            <input type="radio" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>' class="xCNSatAns xWDisabled xWAnsType1" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" maxlength="1" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        if (isset($nStaPage) && $nStaPage == 2) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            echo ($aSubVal['ANS_VALUE'] == $aSubVal['FNQasResuitSeq']) ? 'checked' : '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?> value="<?php //echo $tDataVal['FTQasResultValue']
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ?>" <?php echo $tDisabled; ?>>
                                                                                            <span>&nbsp;</span>
                                                                                            <span class="xWFontSpan"><?= $aSubVal['FNQasResuitName'] ?></span>
                                                                                        </label>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($aSubVal['FTQadType'] == 2) { ?>
                                                                                    <input type="hidden" name="ohdSatQahType2" value="<?= $aSubVal['FTQadType'] ?>">
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <label class="fancy-checkbox" style="margin-left: 10px; display: inline-block;">
                                                                                            <input type="checkbox" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>' class="xCNSatAns xWAnsType2" autocomplete="off" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" maxlength="1" value="" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            if (isset($nStaPage) && $nStaPage == 2) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $tTextFormat = $aSubVal['FNQasResuitSeq'] . '(' . $aSubVal['FTQahDocNo'] . ')';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $tFindText = strpos(strval($aSubVal['ANS_VALUE']), strval($tTextFormat));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if ($tFindText === false) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $tObjectCheck = '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $tObjectCheck = 'checked';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $tObjectCheck = '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ?> <?php echo $tObjectCheck ?> <?php echo $tDisabled; ?>>
                                                                                            <span>&nbsp;</span>
                                                                                            <span><?= $aSubVal['FNQasResuitName'] ?></span>
                                                                                        </label>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($aSubVal['FTQadType'] == 3) { ?>
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>

                                                                                        <input type="text" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>' class="form-control xCNSatAns xWAnsType3" autocomplete="off" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" value="<?php if (isset($nStaPage) && $nStaPage == 2) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo $aSubVal['ANS_NAME'];
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } ?>" <?php echo $tReadonly; ?>>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($aSubVal['FTQadType'] == 4) { ?>
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <textarea data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>' class="form-control xCNSatAns xWAnsType4" maxlength="100" rows="3" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" <?php echo $tReadonly; ?>><?php if (isset($nStaPage) && $nStaPage == 2) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo $aSubVal['ANS_NAME'];
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } ?></textarea>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <label>N/A</label>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                <?php //}
                                                                ?>
                                                                <?php $nSeqRowsB4 = $tVal['FNQadSeqNo']; ?>
                                                                <?php $nSeqRowsB5 = $tVal['FTQahDocNo']; ?>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div style="padding-top: 25px;">
                                                <label class="xCNLabelFrm"><?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyCstComment'); ?></label>
                                                <textarea class="form-control" maxlength="100" rows="10" id="otaSatRmk" name="otaSatRmk" placeholder="<?php echo language('document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyCstCommentEx'); ?>" <?php echo $tReadonly; ?>><?php echo $tSatComment ?></textarea>
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
    </div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jSatisfactionSurveyPageForm.php'); ?>


<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function() {
        JSxSatSetRowSpan();
    })

    function JSxSatSetRowSpan() {
        $('.xWSattr').each(function() {
            var tDataSatCode = $(this).data('docno');
            var nContDataRowSpan = $('.xWSatLng' + tDataSatCode).length;
            $('.xWSattd' + tDataSatCode).attr('rowspan', nContDataRowSpan);
        });
    }

    $('.xCNCheckBoxPoint').click(function(elem) {
        var nSeqdt = $(this).attr('data-seqdt');
        var tDocno = $(this).attr('data-docno');
        $('.xCNCheckBoxPoint' + tDocno + nSeqdt).prop("checked", false);
        $(this).prop("checked", true);
    });
</script>


<!-- madal insert success -->
<div id="odvSatModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvSatModalvalidate" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvSatModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxSatApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvSATPopupCancel">
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
                <button onclick="JSnSATCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
