<?php
$tRoute = $tRoute;
if (isset($tRoute) && $tRoute == 'docPreRepairResultEventEdit') {
    $nStaPage = 2; //ขาแก้ไข
    $aDataDocHDItem = $aDataDocHD['raItems'];

    //สาขา
    $tPreBchCode = $aDataDocHDItem['FTBchCode'];
    $tPreBchName = $aDataDocHDItem['FTBchName'];

    if ($aDataDocHDItem['FTXshStaDoc'] == '3') {
        $nChkStage = 0;
        $nStaPage = 1; //ขาเพิ่ม
        //เอกอสารหลัก
        $tPreCarRedLabel = '2';
        $nFuelStatus = '';
        $tPreTimeBook = '';
        $FTXshRmk = '';
        //เอกอสารหลัก
        $tPreDocNo = $aDataDocHDItem['FTXshDocNo'];
        $dPreDocDate = date("Y-m-d", strtotime($aDataDocHDItem['FDXshDocDate']));
        $dPreDocTime = '';
        $tPreCarMileage = '';
        $tPreCreateBy = $this->session->userdata('tSesUsrUsername');
        $tPreUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');
        $tUsrLevel = $this->session->userdata('tSesUsrLevel');
        $tPreAgnCode = '';
        if ($tUsrLevel != "HQ") {
            $tPreAgnCode = $this->session->userdata('tSesUsrAgnCode');
            $tPreAgnName = $this->session->userdata('tSesUsrAgnName');
        }
        $tPreStaDoc  = '';
        $tClassStaDoc = '';
        $tStaDoc = '';
        $tPreApvCode    = $aDataDocHDItem['FTXshApvCode'];
        $tPreStaApv     = '';
        $tPreUsrNameApv = '';
        $tPreRefType = '';
        //สาขา
        $tPreBchCode = $aDataDocHDItem['FTBchCode'];
        $tPreBchName = $aDataDocHDItem['FTBchName'];
        //ลูกค้า
        $tCstCode = '';
        $tCstName = '';
        $tCstTel = '';
        $tCstEmail = '';
        $tPreRmk = '';
        $tPreCarChkRmk1 = '';
        $tPreCarChkRmk2 = '';
        //เอกสาร
        $tDocRefNo = '';
        $tDocRefDate = '';
        $tCarBrand = '';
        $tCarModel = '';
        $tCarRegNo = '';
        //ผู้ประเมิน
        $tUsrName = $this->session->userdata('tSesUsrUsername');
        $tUsrDateCreate = '';
        $tDateCreate = '';
        $tReadonly = '';
        $tDisabled = '';
        //อื่นๆ
        $tPreStaDoc  = $aDataDocHDItem['FTXshStaDoc'];
        $tPreFrmRmk = '';
        // คำตอบ
        $nRateScore = '';
        $tPreComment = '';
        $nStaUploadFile        = 2;
        $tPreRefExtDoc = $aDataDocHDItem['FTXshRefExDocNo'];
        if (isset($aDataDocHDItem['FDXshRefExDocDate'])) {
            $dPreRefExtDocDate = date("Y-m-d", strtotime($aDataDocHDItem['FDXshRefExDocDate']));
        } else {
            $dPreRefExtDocDate = '';
        }
    } else {
        $aDataJob2Item      = $aDataJob2['raItems'];

        if ($aDataCstAddr['rtCode'] == 800) {
            $aDataCstAddrItem   = array();
            $tPreCstTelFax      = '';
        } else {
            $aDataCstAddrItem   = $aDataCstAddr['raItems'];
            $tPreCstTelFax      = $aDataCstAddrItem['FTAddFax'];
        }

        $aDataCarCstItem    = @$aDataCarCst['raItems'];
        if (isset($aDataJob1HD['raItems'])) {
            $aDataJob1HDItem  = $aDataJob1HD['raItems'];
        }

        //เอกอสารหลัก
        $tPreDocNo = $aDataDocHDItem['FTXshDocNo'];
        $dPreDocDate = date("Y-m-d", strtotime($aDataDocHDItem['FDXshDocDate']));

        $dPreDocTime = date("H:i", strtotime($aDataDocHDItem['FDXshDocDate']));
        $tPreCreateBy = $aDataDocHDItem['FTCreateBy'];
        $tPreUsrNameCreateBy = $aDataDocHDItem['FTNameCreateBy'];

        $tPreApvCode = $aDataDocHDItem['FTXshApvCode'];
        $tPreStaApv  = $aDataDocHDItem['FTXshStaApv'];
        $tPreRefType = '';
        $tPreStaDoc  = $aDataDocHDItem['FTXshStaDoc'];

        //เอกสารอ้างอิง
        $tDocRefNo = $aDataDocHDItem['FTXshRefDocNo'];
        $tDocRefDate = date("Y-m-d", strtotime($aDataDocHDItem['FDXshRefDocDate']));

        $tPreAgnCode = $aDataDocHDItem['FTAgnCode'];
        $tPreBchCode = $aDataDocHDItem['FTBchCode'];
        $FTXshRmk = $aDataDocHDItem['FTXshRmk'];
        $tPreRefExtDoc = $aDataDocHDItem['FTXshRefExDocNo'];



        $nStaUploadFile        = 2;
        $nChkStage = 1;

        //ข้อมูลลูกค้า
        $tPreCstCode = $aDataJob2Item['FTCstCode'];
        $tPreCstName = $aDataJob2Item['FTCstName'];
        $tPreCstTel = $aDataJob2Item['FTCstTel'];
        $tPreCstEmail = $aDataJob2Item['FTCstEmail'];
        $tPreUsrValetName = $aDataJob2Item['FTUsrName'];

        $tPreRmk = $aDataJob2Item['FTXshRmk'];
        $tPreCarChkRmk1 = $aDataJob2Item['FTXshCarChkRmk1'];
        $tPreCarChkRmk2 = $aDataJob2Item['FTXshCarChkRmk2'];


        //ข้อมูลรถ
        $tPreCarRegName = $aDataCarCstItem['FTCarRegNo'];
        $tPrePvnName = $aDataCarCstItem['FTCarRegPvnName'];
        $tPreCarTypeName = $aDataCarCstItem['FTCarTypeName'];
        $tPreCarTypeBrand = $aDataCarCstItem['FTCarBrandName'];
        $tPreCarTypeModel = $aDataCarCstItem['FTCarModelName'];
        $tPreCarTypeColor = $aDataCarCstItem['FTCarColorName'];
        $tPreCarOwnerName = $aDataCarCstItem['FTCarCategoryName'];
        $tPreCarGearName = $aDataCarCstItem['FTCarGearName'];
        $tPreCarVIDRef = $aDataCarCstItem['FTCarVIDRef'];
        $tPreCarRedLabel = $aDataCarCstItem['FTCarStaRedLabel'];

        //ข้อมูล Job 1
        if (isset($aDataJob1HDItem)) {
            $nFuelStatus    = $aDataJob1HDItem['ftxshcarfuel'];
        }else{
            $nFuelStatus    = $aDataDocHDItem['FTXshCarfuel'];
        }
        
        if (isset($aDataJob2Item)) {
            $tPreCarMileage = number_format($aDataJob2Item['FCXshCarMileage'], 2);
        }

        if (isset($aDataJob2Item['FDXshTimeStart'])) {
            $dPreBookDate = date("Y-m-d", strtotime($aDataJob2Item['FDXshTimeStart']));
        } else {
            $dPreBookDate = '';
        }

        if (isset($aDataDocHDItem['FDXshRefExDocDate'])) {
            $dPreRefExtDocDate = date("Y-m-d", strtotime($aDataDocHDItem['FDXshRefExDocDate']));
        } else {
            $dPreRefExtDocDate = '';
        }

        if (isset($aDataJob1HDItem['fdxshrefdocdate'])) {
            $tPreTimeBook = date("Y-m-d H:i:s", strtotime($aDataJob1HDItem['fdxshrefdocdate']));
        } else {
            $tPreTimeBook = '';
        }

        if ($aDataCstAddr['rtCode'] == '1') {
            if ($aDataCstAddrItem['FTAddVersion'] == '1') {

            } else {
                $tPreCstAddr = $aDataCstAddrItem['FTAddV2Desc1'] . ' ' . $aDataCstAddrItem['FTAddV2Desc2'];
            }
        }
    }
} else {
    $nChkStage = 0;
    $nStaPage = 1; //ขาเพิ่ม
    //เอกอสารหลัก
    $tPreCarRedLabel = '2';
    $nFuelStatus = '';
    $tPreTimeBook = '';
    $FTXshRmk = '';
    $tPreDocNo = '';
    $dPreDocDate = '';
    $dPreDocTime = '';
    $tPreCarMileage = '';
    $tPreStaApv = '';
    $tPreCreateBy = $this->session->userdata('tSesUsrUsername');
    $tPreUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');

    $tUsrLevel = $this->session->userdata('tSesUsrLevel');

    $tPreAgnCode = '';
    if ($tUsrLevel != "HQ") {
        $tPreAgnCode = $this->session->userdata('tSesUsrAgnCode');
        $tPreAgnName = $this->session->userdata('tSesUsrAgnName');
    }

    $tPreStaDoc  = '';
    $tClassStaDoc = '';
    $tStaDoc = '';
    $tPreApvCode = '';
    $tPreUsrNameApv = '';
    $tPreRefType = '';

    //สาขา
    $tPreBchCode = $this->session->userdata('tSesUsrBchCodeDefault');
    $tPreBchName = $this->session->userdata('tSesUsrBchNameDefault');

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
    $tUsrName = $this->session->userdata('tSesUsrUsername');
    $tUsrDateCreate = '';
    $tDateCreate = '';
    $tReadonly = '';
    $tDisabled = '';

    //อื่นๆ
    $nPreStaDocAct = '';
    $tPreFrmRmk = '';

    // คำตอบ
    $nRateScore = '';
    $tPreComment = '';
    $nStaUploadFile        = 1;
}

$tUsrCode = $this->session->userdata('tSesUserCode');

if ($tPreStaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tPreStaDoc == 1 && $tPreStaApv != 1) {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tPreStaDoc == 1 && $tPreStaApv == 1) {
        $tClassStaDoc = 'text-success';
        $tStaDoc = language('common/main/main', 'tStaDoc1');
    } else {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    }
}
?>

<style type="text/css">
    /** ======================== Start Css Componant Car Fuel ================================================ */
    .xCNClickMile {
        display: block;
        text-align: center;
        cursor: pointer;
    }

    .xCNClickMile span {
        font-weight: bold;
    }

    .xCNRowMilePadding {
        padding: 0px;
    }

    .xCNActiveMile {
        background: #2c82b6;
        color: #FFF;
    }

    .xCNActiveMile span {
        font-weight: bold;
    }

    .xCNLineMileFirstAndLast {
        background: black;
        width: 1px;
        height: 50px;
        display: block;
        margin: 0px auto;
    }

    .xCNLineMileCenter {
        background: transparent;
        width: 1px;
        height: 50px;
        display: block;
        margin: 0px auto;
        border-right: 2px dotted black;
    }

    .xCNLineHorizontalMileCenterFisrt {
        background: black;
        width: 50%;
        height: 1px;
        position: absolute;
        margin-top: -25%;
        left: 50%;
    }

    .xCNLineHorizontalMileCenterLast {
        background: black;
        width: 50%;
        height: 1px;
        position: absolute;
        margin-top: -25%;
        right: 50%;
    }

    .xCNLineHorizontalMileCenter {
        background: black;
        width: 100%;
        height: 1px;
        margin-top: -25%;
    }
</style>
<input type="hidden" id="ohdPreSvRoute" name="ohdPreSvRoute" value="<?= $tRoute ?>">
<input type="hidden" id="ohdPreSvCheckClearValidate" name="ohdPreSvCheckClearValidate" value="0">
<input type="hidden" id="ohdPreSvCheckSubmitByButton" name="ohdPreSvCheckSubmitByButton" value="0">


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

<form id="ofmPreSurveyAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <!-- Job2Order -->
    <input type="hidden" id="ohdPreSvOldAgnCode" name="ohdPreSvOldAgnCode" value="<?= $tPreAgnCode ?>">
    <input type="hidden" id="ohdPreSvOldBchCode" name="ohdPreSvOldBchCode" value="<?= $tPreBchCode ?>">
    <input type="hidden" id="ohdPreSvOldDecRefNo" name="ohdPreSvOldDecRefNo" value="<?= $tDocRefNo ?>">
    <input type="hidden" id="ohdPreTaskRefUsrCode" name="ohdPreTaskRefUsrCode" value="<?= $tUsrCode ?>">
    <input type="hidden" id="ohdPreChkStage" name="ohdPreChkStage" value="<?= $nChkStage ?>">

    <!-- Car -->
    <input type="hidden" id="ohdPreRedLaber" name="ohdPreRedLaber" value="<?= $tPreCarRedLabel ?>">
    <input type="hidden" id="ohdPreFuelStatus" name="ohdPreFuelStatus" value="<?= @$nFuelStatus ?>">
    <input type="hidden" id="ohdPreTimeBook" name="ohdPreTimeBook" value="<?= $tPreTimeBook ?>">

    <!-- sta Doc -->
    <input type="hidden" id="ohdPreStaDoc" name="ohdPreStaDoc" value="<?= $tPreStaDoc ?>">
    <input type="hidden" id="ohdPreStaApvCode" name="ohdPreStaApvCode" value="<?= $tPreApvCode ?>">
    <input type="hidden" id="ohdPreStaApv" name="ohdPreStaApv" value="<?= $tPreStaApv ?>">

    <button style="display:none" type="submit" id="obtSubmitPre" onclick="JSoAddEditPre('<?= $tRoute ?>')"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPreHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPreDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvPreDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?></label>
                                <?php if (isset($tPreDocNo) && empty($tPreDocNo)) :
                                ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbPreStaAutoGenCode" name="ocbPreStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif;
                                ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetPreDocNo" name="oetPreDocNo" maxlength="20" value="<?php echo $tPreDocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdPreCheckDuplicateCode" name="ohdPreCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetPreDocDate" name="oetPreDocDate" value="<?php echo $dPreDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtPreDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetPreDocTime" name="oetPreDocTime" value="<?php echo $dPreDocTime;
                                                                                                                                                                            ?>">
                                        <span class="input-group-btn">
                                            <button id="obtPreDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <input type="hidden" id="ohdPreCreateBy" name="ohdPreCreateBy" value="<?php echo $tPreCreateBy ?>">
                                            <label><?php echo $tPreUsrNameCreateBy ?></label>
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
                                            if ($tRoute == "docPreisfactionSurveyEventAdd") {
                                                $tPreLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tPreLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tPreStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tPreLabelStaDoc; ?></label>
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
                                <div class="form-group" style="margin:0" id="odvPreApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdPreApvCode" name="ohdPreApvCode" maxlength="20" value="<?php echo $tPreApvCode ?>">
                                            <label>
                                                <?php echo (isset($tPreUsrNameApv) && !empty($tPreUsrNameApv)) ? $tPreUsrNameApv : "-" ?>
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
                <div id="odvPreSurveyRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/prerepairresult/prerepairresult', 'tPreSurveyRefData'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPreSurveyRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPreSurveyRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">

                                <?php
                                $tPreDataInputBchCode   = "";
                                $tPreDataInputBchName   = "";
                                if ($tRoute  == "docPreRepairResultEventAdd") {
                                    $tPreDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                    $tPreDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                    $tDisabledBch            = '';
                                } else {
                                    $tPreDataInputBchCode    = $tPreBchCode;
                                    $tPreDataInputBchName    = $tPreBchName;
                                    $tDisabledBch            = 'disabled';
                                }
                                ?>
                                <!--สาขา-->
                                <script>
                                    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                    if (tUsrLevel != "HQ") {
                                        $('#obtPreSvBrowseBCH').attr('disabled', true);
                                    }
                                </script>

                                <!--สาขาเอกสาร-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetPreFrmBchCode" name="oetPreFrmBchCode" maxlength="5" value="<?= @$tPreDataInputBchCode ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreFrmBchName" name="oetPreFrmBchName" maxlength="100" placeholder="<?= language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?>" value="<?= @$tPreDataInputBchName ?>" readonly>
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtPreSvBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- อ้างอืงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/prerepairresult/prerepairresult', 'tPreRefInt'); ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdPreDocRefCode" name="ohdPreDocRefCode" maxlength="5" value="<?php echo $tDocRefNo; ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetPreDocRefCode" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreRefInt'); ?>" name="oetPreDocRefCode" maxlength="100" value="<?php echo $tDocRefNo; ?>" readonly data-validate-required="<?php echo language('document/prerepairresult/prerepairresult', 'tPreDocNoValidate'); ?>">
                                            <span class="input-group-btn">
                                                <button id="oimPreBrowseDocRef" type="button" class="btn xCNBtnBrowseAddOn">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Browse วันที่อ้างอิงเอกสารภายใน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreRefIntDate') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetJPreDocRefBookDate" name="oetJPreDocRefBookDate" placeholder="YYYY-MM-DD" value="<?php echo $tDocRefDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJPreBrowseBookingDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Browse อ้างอิงเอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreRefExtDate') ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetJPreDocRefExtDoc" name="oetJPreDocRefExtDoc" placeholder="<?= language('document/prerepairresult/prerepairresult', 'tPreRefExtDate'); ?>" value="<?php echo @$tPreRefExtDoc; ?>">
                                </div>
                                <!-- วันที่เอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreRefIntDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetJPreDocRefExtDocDate" name="oetJPreDocRefExtDocDate" placeholder="YYYY-MM-DD" value="<?php echo @$dPreRefExtDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJPreBrowseRefExtDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvatJPreCustomerPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelFrmCustomer'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJPreCustomerInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJPreCustomerInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCustomer') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetPreFrmCstCode" name="oetPreFrmCstCode" maxlength="5" value="<?= @$tPreCstCode; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJPreFrmCstName" name="oetJPreFrmCstName" maxlength="100" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCustomer') ?>" value="<?= @$tPreCstName; ?>" data-validate-required="<?php echo language('document/prerepairresult/prerepairresult', 'tJPrePlsEnterCustomer'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="oimPreBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>

                                </div>
                                <!-- ที่อยู่ลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstAddr') ?></label>
                                    <textarea readonly class="form-control" id="oetJPreFrmCstAddr" name="oetJPreFrmCstAddr" rows="10" maxlength="200" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstAddr') ?>" style="resize: none;height:86px;"><?php echo @$tPreCstAddr ?></textarea>
                                </div>
                                <!-- เบอร์โทรติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstTel') ?></label>
                                    <input readonly type="text" class="form-control" id="oetJPreFrmCstTel" name="oetJPreFrmCstTel" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstTel') ?>" value="<?php echo @$tPreCstTel; ?>">
                                </div>
                                <!-- เบอร์แฟกซ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstTelFax') ?></label>
                                    <input readonly type="text" class="form-control" id="oetJPreFrmCstTelFax" name="oetJPreFrmCstTelFax" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstTelFax') ?>" value="<?php echo @$tPreCstTelFax; ?>">
                                </div>
                                <!-- อีเมล์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstEmail') ?></label>
                                    <input readonly type="text" class="form-control" id="oetJPreFrmCstEmail" name="oetJPreFrmCstEmail" placeholder="<?php echo language('document/prerepairresult/prerepairresult', 'tPreLabelCstEmail') ?>" value="<?php echo @$tPreCstEmail; ?>">
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
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPreSvDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPreSvDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPreSvShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oPreSvCallDataTableFile = {
                        ptElementID     : 'odvPreSvShowDataTable',
                        ptBchCode       : $('#ohdPreSvOldBchCode').val(),
                        ptDocNo         : $('#oetPreDocNo').val(),
                        ptDocKey        : 'TSVTJob3ChkHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : '<?= $nStaUploadFile ?>',
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdPreStaApv').val(),
                        ptStaDoc        : $('#ohdPreStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oPreSvCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <div id="odvPreDataCarDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;">
                        <div id="odvPreHeadCarDetail" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarDetail'); ?></label>
                        </div>
                        <div id="odvPreCarDetailInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body" style="padding-top: 0px !important">
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                        <div class="row">
                                            <!-- ทะเบียนรถ -->
                                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"> <?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarRegNo') ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xControlForm xCNHide" id="oetPreCarRegCode" name="oetPreCarRegCode" maxlength="5" value="<?= @$tJR1CarRegCode; ?>">
                                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarRegName" name="oetPreCarRegName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarRegNo') ?>" value="<?= @$tPreCarRegName; ?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="oimPreBrowseCarRegNo" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- จังหวัด -->
                                            <div class="col-xs-6 col-sm-4 col-md-7 col-lg-7">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelProvince') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPrePvnName" name="oetPrePvnName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelProvince') ?>" value="<?= @$tPrePvnName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- Check Box ป้ายแดง -->
                                            <div class="col-xs-6 col-sm-4 col-md-2 col-lg-2 p-t-30">
                                                <div class="form-group">
                                                    <label class="fancy-checkbox">
                                                        <input type="checkbox" id="oetPreCarRedLabel" name="oetPreCarRedLabel" disabled>
                                                        <span><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarRedLabel') ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- ประเภท / ลักษณะ -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarType') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarTypeName" name="oetPreCarTypeName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarType') ?>" value="<?= @$tPreCarTypeName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- ยี่ห้อ -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarBrand') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarBrandName" name="oetPreCarBrandName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarBrand') ?>" value="<?= @$tPreCarTypeBrand; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- รุ่น -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarModel') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarModelName" name="oetPreCarModelName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarModel') ?>" value="<?= @$tPreCarTypeModel; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- สี -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarColor') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarColorName" name="oetPreCarColorName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarColor') ?>" value="<?= @$tPreCarTypeColor; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- ประเภท / เจ้าของ -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarOwner') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarOwnerName" name="oetPreCarOwnerName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarOwner') ?>" value="<?= @$tPreCarOwnerName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- ระบบเกียร์ -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarGear') ?></label>
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarGearName" name="oetPreCarGearName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarGear') ?>" value="<?= @$tPreCarGearName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- เลขตัวถัง -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarVIDRef') ?></label>
                                                    <input type="text" class="form-control" id="oetPreCarVIDRef" name="oetPreCarVIDRef" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarVIDRef') ?>" value="<?= @$tPreCarVIDRef; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- เลขไมล์ -->
                                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarMiter') ?></label>
                                                    <input type="text" class="form-control xCNInputNumericWithDecimal" maxlength="20" id="oetPreCarMiter" autocomplete="off" name="oetPreCarMiter" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarMiter') ?>" value="<?= @$tPreCarMileage ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="row" style="border-left:solid #cbc9c9 1px !important">
                                            <!-- จองล่วงหน้าเวลาจอง -->
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-6 col-md-5 col-lg-5 p-t-30">
                                                            <label class="fancy-checkbox">
                                                                <input type="checkbox" id="oetPreBookUse" name="oetPreBookUse" disabled>
                                                                <span><?php echo language('document/jobrequest1/jobrequest1', 'วันที่ / เวลาจอง') ?></span>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelTimeBook') ?></label>
                                                            <input type="text" class="form-control" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelTimeBook') ?>" id="oetPreTimeBook" name="oetPreTimeBook" data-validate-required="tTQPlsEnterDocTime" value="<?= @$tPreTimeBook; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- พนังงานรับรถ -->
                                            <div class="col-xs-6 col-sm-12 col-md-3 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelUsrValet') ?></label>
                                                    <div class="">
                                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreUsrValetName" name="oetPreUsrValetName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelUsrValet') ?>" value="<?= @$tPreUsrValetName; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- วันที่รับรถ -->
                                            <div class="col-xs-6 col-sm-12 col-md-3 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelBookDate') ?></label>
                                                    <div class="">
                                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelBookDate') ?>" id="oetPreBookDate" name="oetPreBookDate" value="<?= @$dPreBookDate; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20" style="border-top: solid #cbc9c9 1px !important">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <!-- ปริมาณน้ำมันก่อนให้บริการ -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelCarOilBfServing') ?></label>
                                                    <div class="xWTemplateCarFuel" style="width: 100%; margin-top:30px;">
                                                        <!--เก็บค่า-->
                                                        <input type="hidden" id="ohdPreFrmCarFuel" name="ohdPreFrmCarFuel" value="0">
                                                        <!--CSS เส้น-->
                                                        <div class="row">
                                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileFirstAndLast"></div>
                                                                <div class="xCNLineHorizontalMileCenterFisrt"></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileFirstAndLast"></div>
                                                                <div class="xCNLineHorizontalMileCenterLast"></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                                                        </div>
                                                        <!--CSS ตัวอักษร-->
                                                        <div class="row">
                                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile" data-hiddenval="1"><span>E</span></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile" data-hiddenval="2"><span>1/4</span></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile" data-hiddenval="3"><span>1/2</span></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile" data-hiddenval="4"><span>3/4</span></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile" data-hiddenval="5"><span>F</span></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                                                        </div>
                                                        <script type="text/javascript">
                                                        $('.xCNClickMile').click(function() {
                                                            //เก็บค่า
                                                            var nHiddenVal = $(this).attr("data-hiddenval");
                                                            $('#ohdPreFrmCarFuel').val(nHiddenVal);
                                                            //ปุ่ม Active
                                                            $('.xCNClickMile').removeClass('xCNActiveMile')
                                                            $(this).addClass('xCNActiveMile')
                                                        });
                                                    </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <!-- ไฟแจ้งเตือนความผิดปกติบนหน้าปัด (โปรดระบุ ) -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelWalningLightCar') ?></label>
                                                    <textarea class="form-control" id="oetPreRmk" name="oetPreRmk" rows="10" maxlength="200" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tPreLabelWalningLightCar'); ?>" style="resize: none;height:50px;" value=<?= $FTXshRmk ?>><?= $FTXshRmk ?></textarea>
                                                </div>

                                                <div style="border: 1px solid #cbc9c9; padding: 15px;">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- หมายเหตุ -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm">หมายเหตุ (ใบสั่งงาน) : </label><label><?php echo @$tPreRmk; ?></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <!-- หมายเหตุ การบำรุงรักษา เพิ่มเติม 1 -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm">หมายเหตุ เพิ่มเติม 1 (ใบสั่งงาน) : </label><label><?php echo @$tPreCarChkRmk1; ?></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <!-- หมายเหตุ การบำรุงรักษา เพิ่มเติม 2 -->
                                                            <div class="">
                                                                <label class="xCNLabelFrm">หมายเหตุ เพิ่มเติม 2 (ใบสั่งงาน) : </label><label><?php echo @$tPreCarChkRmk2; ?></label>
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
                </div>
            </div>
            <div id="odvPrePageDatatable">

            </div>
        </div>
    </div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jPreRepairResultPageForm.php'); ?>


<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function() {
        JSxPreSetRowSpan();
    })

    function JSxPreSetRowSpan() {
        $('.xWPretr').each(function() {
            var tDataPreCode = $(this).data('docno');
            var nContDataRowSpan = $('.xWPreLng' + tDataPreCode).length;
            $('.xWPretd' + tDataPreCode).attr('rowspan', nContDataRowSpan);
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
<div id="odvPreModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvPreModalvalidate" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvPreModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxPreApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvPrePopupCancel">
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
                <button onclick="JSnPreCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
