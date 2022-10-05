<?php
$tRoute = $tRoute;
if (isset($tRoute) && $tRoute == 'docIASEventEdit') {
    $nStaPage = 2; //ขาแก้ไข
    $aDetail = $aDataGetDetail['raItems'][0];

    //เอกอสารหลัก
    $tIASDocNo = $aDetail['FTXshDocNo'];
    $dIASDocDate = date("Y-m-d", strtotime($aDetail['FDXshDocDate']));
    $dIASDocTime = $aDetail['FTXshDocTime'];
    $tIASCreateBy = $aDetail['FTCreateBy'];
    $tIASUsrNameCreateBy = $aDetail['FTNameCreateBy'];


    $tIASApvCode = $aDetail['FTXshApvCode'];
    $tIASUsrNameApv = $aDetail['ApvBy'];
    $tIASStaDoc  = $aDetail['FTXshStaDoc'];
    $tIASApv = $aDetail['FTXshStaApv'];

    //ตัวแทนขาย
    $tIASAgnCode = $aDetail['FTAgnCode'];
    $tIASAgnName = $aDetail['FTAgnName'];

    //สาขา
    $tIASBchCode = $aDetail['FTBchCode'];
    $tIASBchName = $aDetail['FTBchName'];

    //ลูกค้า
    $tCstCode = $aDetail['FTCstCode'];
    $tCstName = $aDetail['FTCstName'];
    $tCstTel = $aDetail['FTCstTel'];
    $tCstEmail = $aDetail['FTCstEmail'];

    //เอกสารอ้างอิง
    $tIASRefType = $aDetail['FTXshRefType1'];
    $tDocRefNo = $aDetail['FTXshRefDocNo1'];
    if ($aDetail['FDXshRefDocDate1'] != '') {
        $tDocRefDate = date("Y-m-d", strtotime($aDetail['FDXshRefDocDate1']));
    } else {
        $tDocRefDate = '';
    }
    $tDocToPos = $aDetail['FTSpsName'];
    $tIASRefType3 = $aDetail['FTXshRefType3'];
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
    $tCarMileage = number_format($aDetail['FCXshCarMileage'], 2);
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
    $nIASStaDocAct = $aDetail['FNXshStaDocAct'];
    $tIASFrmRmk = $aDetail['FTXshRmk'];

    // คำตอบ
    $nRateScore = $aDetail['FNXshScoreValue'];
    $tIASComment = $aDetail['FTXshAdditional'];
    $nStaUploadFile        = 2;
} else {
    $nStaPage               = 1; //ขาเพิ่ม

    //เอกอสารหลัก
    $tIASDocNo              = '';
    $dIASDocDate            = '';
    $dIASDocTime            = '';
    $tIASCreateBy           = $this->session->userdata('tSesUsrUsername');
    $tIASUsrNameCreateBy    = $this->session->userdata('tSesUsrUsername');
    $tUsrLevel              = $this->session->userdata('tSesUsrLevel');
    $tIASStaDoc             = '';
    $tClassStaDoc           = '';
    $tStaDoc                = '';
    $tIASApvCode            = '';
    $tIASApv                = '';
    $tIASUsrNameApv         = '';
    $tIASRefType            = '';

    //ผู้ประเมิน
    $tUsrCode               = $this->session->userdata('tSesUserCode');
    $tUsrName               = $this->session->userdata('tSesUsrUsername');
    $tUsrDateCreate         = '';
    $tDateCreate            = '';
    $tReadonly              = '';
    $tDisabled              = '';

    //อื่นๆ
    $nIASStaDocAct          = '';
    $tIASFrmRmk             = '';

    // คำตอบ
    $nRateScore             = '';
    $tIASComment            = '';
    $nStaUploadFile         = 1;
    $tDocToPos              = '';

    //ลูกค้า
    $tCstCode               = '';
    $tCstName               = '';
    $tCstTel                = '';
    $tCstEmail              = '';

    $dStartDateChk          = '';
    $dStartTimeChk          = '';
    $dEndDateChk            = '';
    $dEndTimeChk            = '';

    //ข้อมูลรถ
    $tCarCode               = '';
    $tCarRegNo              = '';
    $tCarEngineNo           = '';
    $tCarVIDRef             = '';
    $tCarType               = '';
    $tCarBrand              = '';
    $tCarModel              = '';
    $tCarColor              = '';
    $tCarGear               = '';
    $tCarPowerType          = '';
    $tCarEngineSize         = '';
    $tCarCategory           = '';
    $tCarMileage            = '';
    $tCarProvince           = '';

    if ($aDataGetDetail['tReturn'] == 1) {

        if ($tUsrLevel != "HQ") {
            $tIASAgnCode = $this->session->userdata('tSesUsrAgnCode');
            $tIASAgnName = $this->session->userdata('tSesUsrAgnName');
        }

        //สาขา
        $tIASBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
        $tIASBchName    = $this->session->userdata('tSesUsrBchNameDefault');

        //เอกสารอ้างอิง
        $tDocRefNo      = '';
        $tDocRefDate    = '';
        $tIASRefType3   = '';
        $tDocRefNo3     = '';
        $tDocRefDate3   = '';
    }
}

if ($tIASStaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tIASStaDoc == 1 && $tIASApv == '') {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tIASStaDoc == 1 && $tIASApv == 1) {
        $tClassStaDoc = 'text-success';
        $tStaDoc = language('common/main/main', 'tStaDoc1');
    } else {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    }
}
?>
<input type="hidden" id="ohdIASRoute" name="ohdIASRoute" value="<?= $tRoute ?>">
<input type="hidden" id="ohdIASCheckClearValidate" name="ohdIASCheckClearValidate" value="0">
<input type="hidden" id="ohdIASCheckSubmitByButton" name="ohdIASCheckSubmitByButton" value="0">


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


<form id="ofmIASAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">

    <!-- sta Doc -->
    <input type="hidden" id="ohdIASStaDoc" name="ohdIASStaDoc" value="<?= $tIASStaDoc ?>">
    <input type="hidden" id="ohdIASStaApv" name="ohdIASStaApv" value="<?= $tIASApv ?>">
    <input type="hidden" id="ohdIASStaApvCode" name="ohdIASStaApvCode" value="<?= $tIASApvCode ?>">
    <input type="hidden" id="ohdIASCreateOn" name="ohdIASCreateOn" value="<?= $tDateCreate ?>">
    <input type="hidden" id="ohdIASOldDocRefCode" name="ohdIASOldDocRefCode" value="<?= $tDocRefNo; ?>">

    <button style="display:none" type="submit" id="obtSubmitIAS" onclick="JSoAddEditIAS('<?= $tRoute ?>')"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar"> <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvIASHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIASDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvIASDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?></label>
                                <?php if (isset($tIASDocNo) && empty($tIASDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbIASStaAutoGenCode" name="ocbIASStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetIASDocNo" name="oetIASDocNo" maxlength="20" value="<?php echo $tIASDocNo; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tSATPlsEnterOrRunDocNo'); ?>"
                                    data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsDocNoDuplicate'); ?>"
                                    placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdIASCheckDuplicateCode" name="ohdIASCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetIASDocDate" name="oetIASDocDate" value="<?php echo $dIASDocDate; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetIASDocTime" name="oetIASDocTime" value="<?php echo $dIASDocTime; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <input type="hidden" id="ohdIASCreateBy" name="ohdIASCreateBy" value="<?php echo $tIASCreateBy ?>">
                                            <label><?php echo $tIASUsrNameCreateBy ?></label>
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
                                            if ($tRoute == "docIASisfactionSurveyEventAdd") {
                                                $tIASLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tIASLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tIASStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tIASLabelStaDoc; ?></label>
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
                                            if ($tRoute == "docIASisfactionSurveyEventAdd") {
                                                $tIASLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                $tRefType = '-';
                                            } else {
                                                if ($tIASRefType == 1) {
                                                    $tRefType = language('document/inspectionafterservice/inspectionafterservice', 'tIASRefType1');
                                                } elseif ($tIASRefType == 2) {
                                                    $tRefType = language('document/inspectionafterservice/inspectionafterservice', 'tIASRefType2');
                                                } elseif ($tIASRefType == 3) {
                                                    $tRefType = language('document/inspectionafterservice/inspectionafterservice', 'tIASRefType3');
                                                } else {
                                                    $tRefType = '-';
                                                }
                                            }
                                            ?>
                                            <label><?php echo $tRefType; ?></label>

                                        </div>
                                    </div>
                                </div>

                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvIASApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdIASApvCode" name="ohdIASApvCode" maxlength="20" value="<?php echo $tIASApvCode ?>">
                                            <label>
                                                <?php echo (isset($tIASUsrNameApv) && !empty($tIASUsrNameApv)) ? $tIASUsrNameApv : "-" ?>
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
                <div id="odvIASRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'ข้อมูลลูกค้า'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIASRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIASRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse ตัวแทนขาย -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide" id="oetIASAgnCode" name="oetIASAgnCode" maxlength="5" value="<?= @$tIASAgnCode; ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIASAgnName" name="oetIASAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tIASAgnName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterSplCode'); ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="oimIASBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
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
                                            <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdIASBchCode" name="ohdIASBchCode" maxlength="5" value="<?php echo @$tIASBchCode ?>" data-bchcodeold="<?php echo @$tIASBchCode ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIASBchName" name="oetIASBchName" maxlength="100" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterBch'); ?>" value="<?php echo @$tIASBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtIASBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Browse ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdIASCstCode" name="ohdIASCstCode" maxlength="5" value="<?php echo $tCstCode; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetIASCstName" name="oetIASCstName" maxlength="100" value="<?php echo $tCstName; ?>" readonly data-validate-required="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCstValidate'); ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTitle'); ?>">
                                            <span class="input-group-btn">
                                                <button id="oimBrowseIASCst" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- เบอร์โทรศัพท์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstTelNo'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetIASCstTel" name="oetIASCstTel" placeholder="<?php echo language('customer/customer/customer', 'tCstTelNo'); ?>" value="<?php echo $tCstTel; ?>" readonly>
                                </div>

                                <!-- e-mail -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTEmail'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetIASCstMail" name="oetIASCstMail" placeholder="<?php echo language('customer/customer/customer', 'tCSTEmail'); ?>" value="<?php echo $tCstEmail; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อ้างอิงเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvIASInfoCst" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อ้างอิงเอกสาร'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIASDataInfoCst" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIASDataInfoCst" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- อ้างอืงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefTask'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote xCNClaerValWhenCstChange" id="ohdIASDocRefCode" name="ohdIASDocRefCode" maxlength="5" value="<?php echo $tDocRefNo; ?>">
                                            <input type="text" class="form-control xWPointerEventNone xCNClaerValWhenCstChange" id="oetIASDocRefCode" name="oetIASDocRefCode" maxlength="100" value="<?php echo $tDocRefNo; ?>" readonly data-validate-required="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocNoValidate'); ?>" placeholder="<?php echo language('customer/customer/customer', 'tIASDocRefTask'); ?>">
                                            <span class="input-group-btn">
                                                <button id="oimBrowseDocRef" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- วันที่อ้างอิงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceDate'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASDateStaService" name="oetIASDateStaService" value="<?= $tDocRefDate ?>" placeholder="YYYY-MM-DD" readonly>
                                </div>

                                <!-- ช่องให้บริการ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceToPos'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASServiceToPos" name="oetIASServiceToPos" value="<?= $tDocToPos ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceToPos'); ?>" readonly>
                                </div>

                                <!-- อ้างอิงเอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASDocRefExt'); ?></label>
                                    <input type="hidden" id="ohdIASDocRefExtCode" name="ohdIASDocRefExtCode" value="<?= $tDocRefNo3 ?>">
                                    <input type="text" class="form-control xControlForm xCNInputWhenStaCancelDoc" id="oetIASDocRefExtCode" name="oetIASDocRefExtCode" maxlength="100" value="<?php
                                                                                                                                                                                                if ($tIASRefType3 == 3) {
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
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetIASDocRefExtDate" name="oetIASDocRefExtDate" value="<?php
                                                                                                                                                                                        if ($tIASRefType3 == 3) {
                                                                                                                                                                                            echo $tDocRefDate3;
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo '';
                                                                                                                                                                                        }
                                                                                                                                                                                        ?>" placeholder="YYYY-MM-DD">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocRefExtDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                <div id="odvIASInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIASDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIASDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <?php
                                        $tIASStaAct = '';
                                        if ($nIASStaDocAct == 1) {
                                            $tIASStaAct = 'checked';
                                        } elseif ($nIASStaDocAct == 2) {
                                            $tIASStaAct = '';
                                        } else {
                                            $tIASStaAct = 'checked';
                                        }
                                        ?>
                                        <input type="checkbox" value="1" id="ocbIASStaDocAct" name="ocbIASStaDocAct" maxlength="1" <?php echo $tIASStaAct; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- ผู้ตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASUsr'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdIASTaskRefUsrCode" name="ohdIASTaskRefUsrCode" maxlength="5" value="<?php echo $tUsrCode; ?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetIASTaskRefUsrName" name="oetIASTaskRefUsrName" maxlength="100" value="<?php echo $tUsrName; ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="oimBrowseUsrBch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled; ?>>
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่เริ่มตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocDateBegin'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetIASDocDateBegin" name="oetIASDocDateBegin" value="<?php echo $dStartDateChk; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocDateBegin" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาที่เริ่มตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocTimeBegin'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetIASDocTimeBegin" name="oetIASDocTimeBegin" value="<?php echo $dStartTimeChk; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocTimeBegin" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่เสร็จสิ้นการตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocDateEnd'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetIASDocDateEnd" name="oetIASDocDateEnd" value="<?php echo $dEndDateChk; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocDateEnd" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาที่เสร็จสิ้นการตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tIASLabelFrmDocTimeEnd'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetIASDocTimeEnd" name="oetIASDocTimeEnd" value="<?php echo $dEndTimeChk; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIASDocTimeEnd" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                                    <textarea class="form-control xControlRmk xWConditionSearchPdt" id="otaIASFrmInfoOthRmk" name="otaIASFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?php echo $tIASFrmRmk ?></textarea>
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
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIASDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIASDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvIASShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>
                
                    var oIASCallDataTableFile = '';
                    oIASCallDataTableFile = {
                        ptElementID     : 'odvIASShowDataTable',
                        ptBchCode       : $('#ohdIASBchCode').val(),
                        ptDocNo         : $('#oetIASDocNo').val(),
                        ptDocKey        : 'TSVTJob4ApvHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdIASStaApv').val(),
                        ptStaDoc        : $('#ohdIASStaDoc').val()
                    };
                    JCNxUPFCallDataTable(oIASCallDataTableFile);
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
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"> <?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceCarNo') ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xControlForm xCNHide xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarNo" name="oetIASCarNo" maxlength="5" value="<?= @$tCarCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarNoName" name="oetIASCarNoName" maxlength="100" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASServiceCarNo') ?>" value="<?= @$tCarRegNo; ?>" readonly>
                                                    <span class="input-group-btn">
                                                        <button id="oimInsBrowseCarRegNo" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- จังหวัด -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarProvince'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASProvinceName" name="oetIASProvinceName" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarProvince'); ?>" value="<?php echo $tCarProvince; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- เลขเครื่องยนต์ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarEngineCode'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarEngineCode" name="oetIASCarEngineCode" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarEngineCode'); ?>" value="<?php echo $tCarEngineNo; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- เลขตัวถัง -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarPowerCode'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarPowerCode" name="oetIASCarPowerCode" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarPowerCode'); ?>" value="<?php echo $tCarVIDRef; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- ประเภท/ลักษณะ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle1'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarType" name="oetIASCarType" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle1'); ?>" value="<?php echo $tCarCategory; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- ประเภท/เจ้าของ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle8'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarOwnerType" name="oetIASCarOwnerType" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle8'); ?>" value="<?php echo $tCarType; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- ยี่ห้อ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle2'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarBrand" name="oetIASCarBrand" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle2'); ?>" value="<?php echo $tCarBrand; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- รุ่น -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle3'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarModel" name="oetIASCarModel" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle3'); ?>" value="<?php echo $tCarModel; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- สี -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle4'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarColor" name="oetIASCarColor" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle4'); ?>" value="<?php echo $tCarColor; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- เกียร์ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle5'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarGear" name="oetIASCarGear" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle5'); ?>" value="<?php echo $tCarGear; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- เครื่องยนต์ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo', 'tCAITitle6'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarEngineOil" name="oetIASCarEngineOil" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle6'); ?>" value="<?php echo $tCarPowerType; ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- ขนาดเครื่องยนต์ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarCldVol'); ?></label>
                                                <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetIASCarCldVol" name="oetIASCarCldVol" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarCldVol'); ?>" value="<?php echo $tCarEngineSize; ?>" readonly>
                                            </div>
                                        </div>

                                        <!-- เลขไมล์ -->
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarMileAge'); ?></label>
                                                <input type="text"  class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange xCNInputNumericWithDecimal" autocomplete="off" id="oetIASCarMileAge" name="oetIASCarMileAge" maxlength="20" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarMileAge'); ?>" value="<?php echo $tCarMileage; ?>">
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
                                <div class="row p-t-10">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div style="position: absolute;left: 15px;top:-5px;">
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASRateing'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <div class="form-group">
                                            <div style="position: absolute;right: 15px;top:-3px;">
                                                <!--ซ่อนคะแนนการให้ดาว-->
                                                <input type="hidden" id="orbIASSueveyRate" name="orbIASSueveyRate" value="0">
                                                <!-- <div class="xWRateing xWDisabled">
                                                    <input type="radio" class="xWRadioRate" name="orbIASSueveyRate" id="orbIASSueveyRate1" value="5" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 5) ? 'checked' : ''; ?>><label for="orbIASSueveyRate1"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbIASSueveyRate" id="orbIASSueveyRate2" value="4" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 4) ? 'checked' : ''; ?>><label for="orbIASSueveyRate2"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbIASSueveyRate" id="orbIASSueveyRate3" value="3" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 3) ? 'checked' : ''; ?>><label for="orbIASSueveyRate3"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbIASSueveyRate" id="orbIASSueveyRate4" value="2" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 2) ? 'checked' : ''; ?>><label for="orbIASSueveyRate4"></label>
                                                    <input type="radio" class="xWRadioRate" name="orbIASSueveyRate" id="orbIASSueveyRate5" value="1" <?php echo $tDisabled; ?> <?php echo ($nRateScore == 1) ? 'checked' : ''; ?>><label for="orbIASSueveyRate5"></label>
                                                </div> -->
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
                                                                <th class="xCNTextBold"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASQaGrp') ?></th>
                                                                <th class="xCNTextBold"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASQaName') ?></th>
                                                                <th class="xCNTextBold" style="width:350px;"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASAnswer') ?></th>
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
                                                                    <tr class="xWIAStr xWIASLng<?= $tVal['FTQahDocNo'] ?>" data-docno='<?= $tVal['FTQahDocNo'] ?>'>
                                                                        <?php if ($nSeqRowsB4 == 0) { ?>
                                                                            <td nowrap class="xWIAStd<?= $tVal['FTQahDocNo'] ?>"><?= $tVal['FTQsgName']; ?></td>
                                                                        <?php } ?>
                                                                        <td nowrap data-docno='<?= $tVal['FTQahDocNo'] ?>' data-seqdt='<?= $tVal['FNQadSeqNo'] ?>' data-quetype='<?= $tVal['FTQadType'] ?>' class="xWQuestion">
                                                                            <?= $tVal['FTQadName']; ?>
                                                                        </td>
                                                                        <td nowrap>
                                                                            <?php foreach ($aDataQA['raItems'] as $nSubKey => $aSubVal) { ?>
                                                                                <?php if ($aSubVal['FTQadType'] == 1) { ?>
                                                                                    <input type="hidden" name="ohdIASQahType1" value="<?= $aSubVal['FTQadType'] ?>">
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <label style="margin-left: 10px; display: inline-block;">
                                                                                            <input type="radio" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>'
                                                                                            data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>'
                                                                                            class="xCNIASAns xWDisabled xWAnsType1" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" maxlength="1"
                                                                                                <?php
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
                                                                                    <input type="hidden" name="ohdIASQahType2" value="<?= $aSubVal['FTQadType'] ?>">
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <label class="fancy-checkbox" style="margin-left: 10px; display: inline-block;">
                                                                                            <input type="checkbox" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>'
                                                                                            data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>'
                                                                                            class="xCNIASAns xWAnsType2" autocomplete="off" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" maxlength="1" value=""
                                                                                            <?php
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
                                                                                                ?> <?php echo $tObjectCheck ?> <?php echo $tDisabled; ?>
                                                                                            >
                                                                                            <span>&nbsp;</span>
                                                                                            <span><?= $aSubVal['FNQasResuitName'] ?></span>
                                                                                        </label>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($aSubVal['FTQadType'] == 3) { ?>
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>

                                                                                        <input type="text" data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>'
                                                                                        data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>'
                                                                                        class="form-control xCNIASAns xWAnsType3" autocomplete="off" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" value="<?php if (isset($nStaPage) && $nStaPage == 2) {
                                                                                            echo $aSubVal['ANS_NAME'];
                                                                                        } ?>" <?php echo $tReadonly; ?>>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($aSubVal['FTQadType'] == 4) { ?>
                                                                                    <?php if ($tVal['FNQadSeqNo'] == $aSubVal['FNQadSeqNo'] && $tVal['FTQahDocNo'] == $aSubVal['FTQahDocNo']) { ?>
                                                                                        <textarea data-docno='<?= $aSubVal['FTQahDocNo'] ?>' data-sgname='<?= $aSubVal['FTQsgName'] ?>' data-seqdt='<?= $aSubVal['FNQadSeqNo'] ?>' data-seqas='<?= $aSubVal['FNQasResuitSeq'] ?>'
                                                                                        data-quename='<?= $aSubVal['FTQadName'] ?>' data-resname='<?= $aSubVal['FNQasResuitName'] ?>' data-resval='<?= $aSubVal['FNQasResuitSeq'] ?>' data-quetype='<?= $aSubVal['FTQadType'] ?>'
                                                                                        class="form-control xCNIASAns xWAnsType4" maxlength="100" rows="3" name="ocbAns<?= $aSubVal['FTQahDocNo'] ?><?= $tVal['FNQadSeqNo'] ?>" <?php echo $tReadonly; ?>><?php if (isset($nStaPage) && $nStaPage == 2) {
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
                                                <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCstComment'); ?></label>
                                                <textarea class="form-control" maxlength="100" rows="10" id="otaIASRmk" name="otaIASRmk" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice', ''); ?>" <?php echo $tReadonly; ?>><?php echo $tIASComment ?></textarea>
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

<?php include('script/jInspectionafterservicePageForm.php'); ?>

<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function() {
        JSxIASSetRowSpan();
    })

    function JSxIASSetRowSpan() {
        $('.xWIAStr').each(function() {
            var tDataIASCode = $(this).data('docno');
            var nContDataRowSpan = $('.xWIASLng' + tDataIASCode).length;
            $('.xWIAStd' + tDataIASCode).attr('rowspan', nContDataRowSpan);
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
<div id="odvIASModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvIASModalvalidate" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvIASModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxIASApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvIASPopupCancel">
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
                <button onclick="JSnIASCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
