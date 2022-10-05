<?php
$tJR1Route  = $tRoute;
if (isset($tJR1Route) && $tJR1Route == 'docJR1EventEdit') {
    $nStaUploadFile         = 2;
    $tJR1DocNo              = $aDataDocHD['raItems']['FTXshDocNo'];
    $dJR1DocDate            = $aDataDocHD['raItems']['FDXshDocDate'];
    $dJR1DocTime            = date("H:i:s", strtotime($aDataDocHD['raItems']['FDXshDocDate']));
    $tJR1UsrNameCreateBy    = $aDataDocHD['raItems']['FTUsrName'];
    $tJR1AgnCode            = $aDataDocHD['raItems']['FTAgnCode'];
    $tJR1StaDoc             = $aDataDocHD['raItems']['FTXshStaDoc'];
    $tJR1StaApv             = $aDataDocHD['raItems']['FTXshStaApv'];
    $tJR1ApvCode            = $aDataDocHD['raItems']['FTXshApvCode'];
    $tJR1UsrNameApv         = $aDataDocHD['raItems']['FTXshApvName'];
    $tJR1CarChkRmk1         = $aDataDocHD['raItems']['FTXshCarChkRmk1'];
    //สาขา
    $tJR1BchCode            = $aDataDocHD['raItems']['FTBchCode'];
    $tJR1BchName            = $aDataDocHD['raItems']['FTBchName'];

    $tUserGetCarCode        = $aDataDocHD['raItems']['rtUserGetCarCode'];
    $tUserGetCarName        = $aDataDocHD['raItems']['rtUserGetCarName'];

    //ลูกค้า
    $tJR1CusCode            = $aDataDocCSTHD['raItems']['FTCstCode'];
    $tJR1CstName            = $aDataDocCSTHD['raItems']['FTCstName'];
    $tJR1CstAddr            = $aDataDocCSTHD['raItems']['FTAddV2Desc1'];
    $tJR1CstTel             = $aDataDocCSTHD['raItems']['FTCstTel'];
    $tJR1CstEmail           = $aDataDocCSTHD['raItems']['FTCstEmail'];
    $tPPLCode               = $aDataDocCSTHD['raItems']['FTPplCode'];

    //ข้อมูลรถ
    $tJR1CarRegCode         = $aDataDocCar['raItems']['FTCarCode'];
    $tJR1CarRegName         = $aDataDocCar['raItems']['FTCarRegNo'];
    $tJR1PvnCode            = $aDataDocCar['raItems']['FTCarRegPvnCode'];
    $tJR1PvnName            = $aDataDocCar['raItems']['FTCarRegPvnName'];
    $tJR1CarEngineNo        = $aDataDocCar['raItems']['FTCarEngineNo'];
    $tJR1CarTypeCode        = $aDataDocCar['raItems']['FTCarTypeCode'];
    $tJR1CarTypeName        = $aDataDocCar['raItems']['FTCarTypeName'];
    $tJR1CarBrandCode       = $aDataDocCar['raItems']['FTCarBrandCode'];
    $tJR1CarBrandName       = $aDataDocCar['raItems']['FTCarBrandName'];
    $tJR1CarModelCode       = $aDataDocCar['raItems']['FTCarModelCode'];
    $tJR1CarModelName       = $aDataDocCar['raItems']['FTCarModelName'];
    $tJR1CarColorCode       = $aDataDocCar['raItems']['FTCarColorCode'];
    $tJR1CarColorName       = $aDataDocCar['raItems']['FTCarColorName'];
    $tJR1CarOwnerCode       = $aDataDocCar['raItems']['FTCarOwnerCode'];
    $tJR1CarOwnerName       = $aDataDocCar['raItems']['FTCarOwnerName'];
    $tJR1CarGearCode        = $aDataDocCar['raItems']['FTCarGearCode'];
    $tJR1CarGearName        = $aDataDocCar['raItems']['FTCarGearName'];
    $tJR1CarVIDRef          = $aDataDocCar['raItems']['FTCarVIDRef'];
    $tJR1CarRedLabel        = $aDataDocCar['raItems']['FTCarStaRedLabel'];
    $tJR1CarMiter           = str_replace(",", "", number_format($aDataDocHD['raItems']['FCXshCarMileage'], $nOptDecimalShow));
    $tJR1CarFuel            = $aDataDocHD['raItems']['FTXshCarFuel'];
    //อ้างอิงเอกสาร
    $tJR1DocRefBooking      = $aDataDocHD['raItems']['DocRefIn'];
    $dJR1DocRefBookDate     = $aDataDocHD['raItems']['DateRefIn'];
    $tJR1RefExtDoc          = $aDataDocHD['raItems']['DocRefEx'];
    $dJR1RefExtDocDate      = $aDataDocHD['raItems']['DateRefEx'];
    $tJR1DocRemark          = $aDataDocHD['raItems']['FTXshRmk'];
    // ช่องให้บริการ
    $tJR1BayCode            = $aDataDocHD['raItems']['FTXshToPos'];
    $tJR1BayName            = $aDataDocHD['raItems']['FTXshToPosName'];
    //วันที่จอง - เวลาที่จอง
    if (isset($aDataDocHD['raItems']['FDXshTimeStart']) && !empty($aDataDocHD['raItems']['FDXshTimeStart'])) {
        $dJR1BookDate       = date('Y-m-d', strtotime($aDataDocHD['raItems']['FDXshTimeStart']));
        $dJR1BookTime       = date("H:i:s", strtotime($aDataDocHD['raItems']['FDXshTimeStart']));
    } else {
        $dJR1BookDate       = date('Y-m-d');
        $dJR1BookTime       = date('H:i:s');
    }
    // วันที่รับรถ - เวลาที่รับรถ
    if (isset($aDataDocHD['raItems']['FDXshVchRecDate']) && !empty($aDataDocHD['raItems']['FDXshVchRecDate'])) {
        $dJR1PickInDate     = date('Y-m-d', strtotime($aDataDocHD['raItems']['FDXshVchRecDate']));
        $dJR1PickInTime     = date("H:i:s", strtotime($aDataDocHD['raItems']['FDXshVchRecDate']));
    } else {
        $dJR1PickInDate     = date('Y-m-d');
        $dJR1PickInTime     = date('H:i:s');
    }

    //ตัวแทนขาย
    $tJR1FTAgnCode  = $aDataDocHD['raItems']['FTAgnCode'];
    $tJR1FTAgnName  = $aDataDocHD['raItems']['FTAgnName'];

    // สาขา
    $tJR1FTBchCode  = $aDataDocHD['raItems']['FTBchCode'];
    $tJR1FTBchName  = $aDataDocHD['raItems']['FTBchName'];

    // สถานะการจอง
    $tJR1StaBook    = $aDataDocHD['raItems']['FTXshStaBook'];
} else {
    $nStaUploadFile         = 1;
    $tJR1DocNo              = '';
    $dJR1DocDate            = date('Y-m-d');
    $dJR1DocTime            = date('H:i:s');
    $tJR1UsrNameCreateBy    = $this->session->userdata('tSesUsrUsername');
    $tJR1UsrLevel           = $this->session->userdata('tSesUsrLevel');
    // เช็ค Agency Code
    $tJR1AgnCode = '';
    if ($tJR1UsrLevel != "HQ") {
        $tJR1AgnCode    = $this->session->userdata('tSesUsrAgnCode');
    }
    $tJR1StaDoc         = '1';
    $tJR1StaApv         = '';
    $tJR1ApvCode        = '';
    $tJR1UsrNameApv     = '';
    //สาขา
    $tJR1BchCode        = $this->session->userdata('tSesUsrBchCodeDefault');
    $tJR1BchName        = $this->session->userdata('tSesUsrBchNameDefault');
    // ช่องให้บริการ
    $tJR1BayCode        = "";
    $tJR1BayName        = "";
    // วันที่นัดหมาย
    $dJR1BookDate       = date('Y-m-d');
    $dJR1BookTime       = date('H:i:s');
    // วันที่รับรถ
    $dJR1PickInDate     = date('Y-m-d');
    $dJR1PickInTime     = date('H:i:s');

    //ตัวแทนขาย
    $tJR1FTAgnCode  = '';
    $tJR1FTAgnName  = '';

    // สาขา
    $tJR1FTBchCode  = "";
    $tJR1FTBchName  = "";

    $tJR1StaBook    = '';

    // พนักงานรับรถ
    $aSesUsrInfo        = $this->session->userdata('tSesUsrInfo');
    $tUserGetCarCode    = $aSesUsrInfo['FTUsrCode'];
    $tUserGetCarName    = $aSesUsrInfo['FTUsrName'];
}

//สถานะเอกสาร
if ($tJR1StaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tJR1StaDoc == 1 && $tJR1StaApv == '') {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tJR1StaDoc == 1 && $tJR1StaApv == 1) {
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

    /** ========================== End Css Componant Car Fuel =============================================== */
</style>



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


<form id="ofmJR1AddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtJR1SubmitDocument" onclick="JSxJR1AddEditDocument()"></button>
    <input type="hidden" id="ohdJR1DecimalShow" name="ohdJR1DecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdJR1Route" name="ohdJR1Route" value="<?= $tJR1Route ?>">
    <input type="hidden" id="ohdJR1CheckClearValidate" name="ohdJR1CheckClearValidate" value="0">
    <input type="hidden" id="ohdJR1CheckSubmitByButton" name="ohdJR1CheckSubmitByButton" value="0">

    <!-- Job1Request -->
    <input type="hidden" id="ohdJR1OldAgnCode"  name="ohdJR1OldAgnCode" value="<?= $tJR1AgnCode ?>">
    <input type="hidden" id="ohdJR1OldBchCode"  name="ohdJR1OldBchCode" value="<?= $tJR1BchCode ?>">
    <input type="hidden" id="ohdJR1UseInJOB2"   name="ohdJR1UseInJOB2" value="<?= @$tStaFindDocNoUseInJOB2 ?>">

    <!-- Status Document -->
    <input type="hidden" id="ohdJR1StaDoc" name="ohdJR1StaDoc" value="<?= $tJR1StaDoc ?>">
    <input type="hidden" id="ohdJR1StaApv" name="ohdJR1StaApv" value="<?= $tJR1StaApv ?>">
    <input type="hidden" id="ohdJR1StaApvCode" name="ohdJR1StaApvCode" value="<?= $tJR1ApvCode ?>">

    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom:25px;">
                <div id="odvJR1HeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1Doucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvJR1DataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvJR1DataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmAppove'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (isset($tJR1DocNo) && empty($tJR1DocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbJR1StaAutoGenCode" name="ocbJR1StaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetJR1DocNo" name="oetJR1DocNo" maxlength="20" value="<?php echo $tJR1DocNo; ?>" data-validate-required="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdJR1CheckDuplicateCode" name="ohdJR1CheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetJR1DocDate" name="oetJR1DocDate" value="<?php echo $dJR1DocDate; ?>" data-validate-required="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJR1DocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker xCNInputMaskTime" id="oetJR1DocTime" name="oetJR1DocTime" value="<?php echo $dJR1DocTime; ?>" data-validate-required="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJR1DocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo $tJR1UsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tJR1Route == "docJR1EventAdd") {
                                                $tJR1LabelStaDoc  = language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmValStaDoc');
                                            } else {
                                                $tJR1LabelStaDoc  = language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmValStaDoc' . $tJR1StaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tJR1LabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label class="<?php echo $tClassStaDoc; ?>">
                                                <?php echo $tStaDoc; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvJR1ApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdJR1ApvCode" name="ohdJR1ApvCode" maxlength="20" value="<?php echo $tJR1ApvCode ?>">
                                            <label>
                                                <?php echo (isset($tJR1UsrNameApv) && !empty($tJR1UsrNameApv)) ? $tJR1UsrNameApv : "-" ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ข้อมูลอ้างอิงเอกสารภายใน -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvatJR1DocRefPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelRefDocIntTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJR1DocRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJR1DocRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse อ้างอิงเอกสารใบนัดหมาย -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmRefBooking') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetJR1OldDocRefBookCode" name="oetJR1OldDocRefBookCode">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetJR1DocRefBookCode" name="oetJR1DocRefBookCode" maxlength="5" value="<?= @$tJR1DocRefBooking; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1DocRefBookName" name="oetJR1DocRefBookName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmRefBooking') ?>" value="<?= @$tJR1DocRefBooking; ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="oimJR1BrowseBooking" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Browse วันที่อ้างอิงเอกสารใบนัดหมาย -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmRefBookingDate') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" autocomplete="off" id="oetJR1DocRefBookDate" name="oetJR1DocRefBookDate" placeholder="YYYY-MM-DD" value="<?php echo @$dJR1DocRefBookDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJR1BrowseBookingDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Browse อ้างอิงเอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">อ้างอิงเอกสารภายนอก</label>
                                    <input type="text" class="form-control" id="oetJR1DocRefExtDoc" name="oetJR1DocRefExtDoc" placeholder="อ้างอิงเอกสารภายนอก" value="<?php echo @$tJR1RefExtDoc; ?>">
                                </div>
                                <!-- วันที่เอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmRefExtDoc'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" autocomplete="off" id="oetJR1DocRefExtDocDate" name="oetJR1DocRefExtDocDate" placeholder="YYYY-MM-DD" value="<?php echo @$dJR1RefExtDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtJR1BrowseRefExtDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvatJR1CustomerPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmCustomer'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJR1CustomerInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJR1CustomerInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCustomer') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetJR1FrmCstCode" name="oetJR1FrmCstCode" maxlength="5" value="<?= @$tJR1CusCode; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1FrmCstName" name="oetJR1FrmCstName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCustomer') ?>" value="<?= @$tJR1CstName; ?>" data-validate-required="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCustomer'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="oimJR1BrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!--กลุ่มราคาของลูกค้า-->
                                <input type="hidden" id="ohdJOB1CustomerPPLCode" name="ohdJOB1CustomerPPLCode" value="<?= @$tPPLCode; ?>" >

                                <!-- ที่อยู่ลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCstAddr') ?></label>
                                    <textarea class="form-control" id="oetJR1FrmCstAddr" name="oetJR1FrmCstAddr" rows="10" maxlength="200" disabled placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCstAddr') ?>" style="resize: none;height:86px;"><?php echo @$tJR1CstAddr ?></textarea>
                                </div>
                                <!-- เบอร์โทรติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCstTel') ?></label>
                                    <input type="text" class="form-control" id="oetJR1FrmCstTel" name="oetJR1FrmCstTel" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCstTel') ?>" readonly value="<?php echo @$tJR1CstTel; ?>">
                                </div>
                                <!-- อีเมล์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">อีเมล</label>
                                    <input type="text" class="form-control" id="oetJR1FrmCstEmail" name="oetJR1FrmCstEmail" readonly placeholder="อีเมล" value="<?php echo @$tJR1CstEmail; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel เอกสารเงื่อนไข AD , สาขา -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvatJR1ADPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1ConditionBch'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJR1ADInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJR1ADInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- ตัวแทนขาย -->
                                <?php
                                $tJR1DataInputADCode   = "";
                                $tJR1DataInputADName   = "";
                                if ($tJR1Route  == "docJR1EventAdd") {
                                    $tJR1DataInputADCode    = $this->session->userdata('tSesUsrAgnCode');
                                    $tJR1DataInputADName    = $this->session->userdata('tSesUsrAgnName');
                                    $tBrowseADDisabled     = '';
                                } else {
                                    $tJR1DataInputADCode    = @$tJR1FTAgnCode;
                                    $tJR1DataInputADName    = @$tJR1FTAgnName;
                                    $tBrowseADDisabled     = '';
                                }
                                ?>
                                <script type="text/javascript">
                                    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                    if (tUsrLevel != "HQ") {
                                        $('.xCNBrowseAD').hide();
                                    }
                                </script>
                                <div class="form-group xCNBrowseAD">
                                    <label class="xCNLabelFrm"><?= language('document/jobrequest1/jobrequest1', 'TJR1LabelAD'); ?></label>
                                    <div class="input-group" style="width:100%;">
                                        <input type="text" class="input100 xCNHide" id="ohdJR1ADCode" name="ohdJR1ADCode" value="<?= $tJR1DataInputADCode ?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="ohdJR1ADName" name="ohdJR1ADName" value="<?= $tJR1DataInputADName ?>" placeholder="<?= language('document/jobrequest1/jobrequest1', 'TJR1LabelAD'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtJR1BrowseAgency" type="button" class="btn xCNBtnBrowseAddOn xWBtnDisabledOnSave" <?= $tBrowseADDisabled; ?>>
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!--สาขา-->
                                <?php
                                $tJR1DataInputBchCode   = "";
                                $tJR1DataInputBchName   = "";
                                if ($tJR1Route  == "docJR1EventAdd") {
                                    $tJR1DataInputBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
                                    $tJR1DataInputBchName   = $this->session->userdata('tSesUsrBchNameDefault');
                                    $tBrowseBchDisabled     = '';
                                } else {
                                    $tJR1DataInputBchCode   = @$tJR1FTBchCode;
                                    $tJR1DataInputBchName   = @$tJR1FTBchName;
                                    $tBrowseBchDisabled     = '';
                                }
                                ?>
                                <script type="text/javascript">
                                    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                    if (tUsrLevel != "HQ") {
                                        //BCH - SHP
                                        var tBchCount = '<?= $this->session->userdata("nSesUsrBchCount"); ?>';
                                        if (tBchCount < 2) {
                                            $('#obtJR1BrowseBranch').attr('disabled', true);
                                        }
                                    }
                                </script>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/jobrequest1/jobrequest1', 'tJR1BchName'); ?></label>
                                    <div class="input-group" style="width:100%;">
                                        <input type="text" class="input100 xCNHide" id="ohdJR1BchCode" name="ohdJR1BchCode" value="<?= @$tJR1DataInputBchCode; ?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetJR1BchName" name="oetJR1BchName" value="<?= @$tJR1DataInputBchName; ?>" placeholder="<?= language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterBch'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtJR1BrowseBranch" type="button" class="btn xCNBtnBrowseAddOn xWBtnDisabledOnSave" <?= $tBrowseBchDisabled; ?>>
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
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
                <div id="odvJR1InfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJR1DataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJR1DataInfoOther" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xControlForm selectpicker xWDODisabledOnApv" id="ocmJR1FrmInfoOthReAddPdt" name="ocmJR1FrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ข้อมูลไฟล์แนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvatJR1FilesPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelFrmFiles'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvJR1FilesInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJR1FilesInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvJR1FilesDataTable">
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    var oJR1SvCallDataTableFile = '';
                    var oJR1SvCallDataTableFile = {
                        ptElementID: 'odvJR1FilesDataTable',
                        ptBchCode: $('#ohdJR1BchCode').val(),
                        ptDocNo: $('#oetJR1DocNo').val(),
                        ptDocKey: 'TSVTJob1ReqHD',
                        ptSessionID: '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent: '<?= $nStaUploadFile ?>',
                        ptCallBackFunct: '',
                        ptStaApv: $('#ohdJR1StaApv').val(),
                        ptStaDoc: $('#ohdJR1StaDoc').val()
                    };
                    JCNxUPFCallDataTable(oJR1SvCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <div id="odvJR1DataCarDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;">
                        <div id="odvJR1HeadCarDetail" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarDetail'); ?></label>
                        </div>
                        <div id="odvJR1CarDetailInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body" style="padding-top: 0px !important">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 p-t-20">
                                        <div class="row">
                                            <!-- ทะเบียนรถ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarRegNo') ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarRegCode" name="oetJR1CarRegCode" maxlength="5" value="<?= @$tJR1CarRegCode; ?>">
                                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarRegName" name="oetJR1CarRegName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarRegNo') ?>" value="<?= @$tJR1CarRegName; ?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="oimJR1BrowseCarRegNo" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- จังหวัด -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelProvince') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1PvnCode" name="oetJR1PvnCode" maxlength="5" value="<?= @$tJR1PvnCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1PvnName" name="oetJR1PvnName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelProvince') ?>" value="<?= @$tJR1PvnName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- ป้ายแดง / สถานะการจอง -->
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 p-t-30">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <!-- ป้ายแดง -->
                                                        <div class="form-group" style="cursor: not-allowed;">
                                                            <label class="fancy-checkbox" style="pointer-events:none">
                                                                <?php
                                                                if (isset($tJR1CarRedLabel) && !empty($tJR1CarRedLabel)) {
                                                                    if ($tJR1CarRedLabel == '1') {
                                                                        $tRedLabelDisableStaActive  = 'checked';
                                                                    } else {
                                                                        $tRedLabelDisableStaActive  = '';
                                                                    }
                                                                } else {
                                                                    $tRedLabelDisableStaActive  = '';
                                                                }
                                                                ?>
                                                                <input type="checkbox" id="oetJR1CarRedLabel" name="oetJR1CarRedLabel" <?= $tRedLabelDisableStaActive ?>>
                                                                <span class=""><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarRedLabel') ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <!-- สถานะการจอง -->
                                                        <div class="form-group" style="cursor: not-allowed;">
                                                            <label class="fancy-checkbox" style="pointer-events:none">
                                                                <?php

                                                                if (isset($tJR1StaBook) && !empty($tJR1StaBook)) {
                                                                    if ($tJR1StaBook == '2') {
                                                                        $tRedLableDisStaBookActive  = '';
                                                                    } else {
                                                                        $tRedLableDisStaBookActive  = 'checked';
                                                                    }
                                                                } else {
                                                                    $tRedLableDisStaBookActive  = '';
                                                                }
                                                                ?>
                                                                <input type="checkbox" id="oetJR1BookUse" name="oetJR1BookUse" <?= @$tRedLableDisStaBookActive ?>>
                                                                <span><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelBookUse') ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- ประเภท / ลักษณะ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarType') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarTypeCode" name="oetJR1CarTypeCode" maxlength="5" value="<?= @$tJR1CarTypeCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarTypeName" name="oetJR1CarTypeName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarType') ?>" value="<?= @$tJR1CarTypeName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- ยี่ห้อ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarBrand') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarBrandCode" name="oetJR1CarBrandCode" maxlength="5" value="<?= @$tJR1CarBrandCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarBrandName" name="oetJR1CarBrandName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarBrand') ?>" value="<?= @$tJR1CarBrandName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- รุ่น -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarModel') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarModelCode" name="oetJR1CarModelCode" maxlength="5" value="<?= @$tJR1CarModelCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarModelName" name="oetJR1CarModelName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarModel') ?>" value="<?= @$tJR1CarModelName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- สี -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarColor') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarColorCode" name="oetJR1CarColorCode" maxlength="5" value="<?= @$tJR1CarColorCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarColorName" name="oetJR1CarColorName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarColor') ?>" value="<?= @$tJR1CarColorName; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- เลขเครื่องยนต์ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'เลขเครื่องยนต์') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarEnginereqCode" name="oetJR1CarEnginereqCode" maxlength="5" value="<?= @$tJR1CarEngineNo; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarEnginereqName" name="oetJR1CarEnginereqName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'เลขเครื่องยนต์') ?>" value="<?= @$tJR1CarEngineNo; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- ระบบเกียร์ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarGear') ?></label>
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetJR1CarGearCode" name="oetJR1CarGearCode" maxlength="5" value="<?= @$tJR1CarGearCode; ?>">
                                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1CarGearName" name="oetJR1CarGearName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarGear') ?>" value="<?= @$tJR1CarGearName; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- เลขตัวถัง -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarVIDRef') ?></label>
                                                    <input type="text" class="form-control" id="oetJR1CarVIDRef" name="oetJR1CarVIDRef" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarVIDRef') ?>" value="<?= @$tJR1CarVIDRef; ?>" readonly>
                                                </div>
                                            </div>
                                            <!-- เลขมิเตอร์ -->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarMiter') ?></label>
                                                    <input type="text" class="form-control xCNInputNumericWithDecimal" id="oetJR1CarMiter" name="oetJR1CarMiter" maxlength="10" autocomplete="off" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarMiter') ?>" value="<?= @$tJR1CarMiter; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 p-t-20">
                                        <div class="row" style="border-left:solid #cbc9c9 1px !important">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <!-- ช่องบริการ -->
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelBay') ?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xCNHide" id="oetJR1BayCode" name="oetJR1BayCode" value="<?= @$tJR1BayCode ?>">
                                                                <input type="text" class="form-control" id="oetJR1BayName" name="oetJR1BayName" readonly placeholder="<?= language('document/jobrequest1/jobrequest1', 'tJR1LabelBay'); ?>" value="<?= @$tJR1BayName; ?>" data-validate-required="กรุณาเลือกช่องให้บริการสำหรับรับรถ">
                                                                <span class="input-group-btn">
                                                                    <button id="oetJR1LastBrowseBay" type="button" class="btn xCNBtnBrowseAddOn">
                                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- พนักงานรับรถ -->
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelUsrValet') ?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xControlForm xCNHide" id="oetJR1UsrValetCode" name="oetJR1UsrValetCode" maxlength="5" value="<?= @$tUserGetCarCode; ?>">
                                                                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetJR1UsrValetName" name="oetJR1UsrValetName" maxlength="100" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelUsrValet') ?>" value="<?= @$tUserGetCarName; ?>" readonly>
                                                                <span class="input-group-btn">
                                                                    <button id="oimJR1BrowseUsrValet" type="button" class="btn xCNBtnBrowseAddOn">
                                                                        <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- วันที่จอง / เวลาจองรถ -->
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelBookDate') ?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetJR1BookDate" name="oetJR1BookDate" value="<?= @$dJR1BookDate; ?>">
                                                                <span class="input-group-btn">
                                                                    <button id="obtJR1BookDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelBookTime') ?></label>
                                                            <input type="text" class="form-control xCNTimePicker" id="oetJR1TimeBook" name="oetJR1TimeBook" value="<?= @$dJR1BookTime; ?>" data-validate-required="tTQPlsEnterDocTime">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- วันที่รับรถ / เวลารับรถ -->
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelPickInDate') ?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetJR1PickInDate" name="oetJR1PickInDate" value="<?= @$dJR1PickInDate; ?>">
                                                                <span class="input-group-btn">
                                                                    <button id="obtJR1PickInDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelPickInTime') ?></label>
                                                            <input type="text" class="form-control xCNTimePicker" id="oetJR1PickInTime" name="oetJR1PickInTime" value="<?= @$dJR1PickInTime; ?>" data-validate-required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10" style="border-top: solid #cbc9c9 1px !important" ;">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <!-- ปริมาณน้ำมันก่อนให้บริการ -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCarOilBfServing') ?></label>
                                                    <div class="xWTemplateCarFuel" style="width: 100%; margin-top:15px;">
                                                        <!--เก็บค่า-->
                                                        <input type="hidden" id="ohdJR1FrmCarFuel" name="ohdJR1FrmCarFuel" value="<?= @$tJR1CarFuel ?>">
                                                        <!--CSS เส้น-->
                                                        <div class="row">
                                                            <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1"></div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileFirstAndLast"></div>
                                                                <div class="xCNLineHorizontalMileCenterFisrt"></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileCenter"></div>
                                                                <div class="xCNLineHorizontalMileCenter"></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2 xCNRowMilePadding">
                                                                <div class="xCNLineMileFirstAndLast"></div>
                                                                <div class="xCNLineHorizontalMileCenterLast"></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1"></div>
                                                        </div>
                                                        <!--CSS ตัวอักษร-->
                                                        <div class="row">
                                                            <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1"></div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile <?= (@$tJR1CarFuel == 1) ? 'xCNActiveMile' : '' ?>  " data-hiddenval="1"><span>E</span></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile <?= (@$tJR1CarFuel == 2) ? 'xCNActiveMile' : '' ?>" data-hiddenval="2"><span>1/4</span></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile <?= (@$tJR1CarFuel == 3) ? 'xCNActiveMile' : '' ?>" data-hiddenval="3"><span>1/2</span></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile <?= (@$tJR1CarFuel == 4) ? 'xCNActiveMile' : '' ?>" data-hiddenval="4"><span>3/4</span></div>
                                                            </div>
                                                            <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                                                <div class="xCNClickMile <?= (@$tJR1CarFuel == 5) ? 'xCNActiveMile' : '' ?>" data-hiddenval="5"><span>F</span></div>
                                                            </div>
                                                            <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1"></div>
                                                        </div>
                                                    </div>
                                                    <script type="text/javascript">
                                                        $('.xCNClickMile').click(function() {
                                                            //เก็บค่า
                                                            var nHiddenVal = $(this).attr("data-hiddenval");
                                                            $('#ohdJR1FrmCarFuel').val(nHiddenVal);
                                                            //ปุ่ม Active
                                                            $('.xCNClickMile').removeClass('xCNActiveMile')
                                                            $(this).addClass('xCNActiveMile')
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <!-- ไฟแจ้งเตือนความผิดปกติบนหน้าปัด (โปรดระบุ ) -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelWalningLightCar') ?></label>
                                                    <textarea class="form-control" id="oetJR1FrmCarChkRmk1" name="oetJR1FrmCarChkRmk1" rows="4" maxlength="200" placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelWalningLightCar'); ?>"><?= @$tJR1CarChkRmk1 ?></textarea>
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
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="row p-t-10">
                                    <!-- ค้นหารายการสินค้า -->
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="">
                                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelPdtSearch'); ?></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvJR1SearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvJR1SearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ปุ่มจัดการตารางรายการสินค้า -->
                                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 p-t-25 text-right xCNHideWhenCancelOrApprove">
                                        <div class="">
                                            <div class="btn-group xCNDropDrownGroup">
                                                <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                    <?= language('common/main/main', 'tCMNOption'); ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliJR1BtnDeleteMulti" class="disabled">
                                                        <a data-toggle="modal" data-target="#odvJR1ModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!--ค้นหาจากบาร์โค๊ด-->
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 p-t-25 text-right xCNHideWhenCancelOrApprove">
                                        <div class="form-group">
                                            <input type="text" class="form-control xCNPdtEditInLine" id="oetJR1InsertBarcode" autocomplete="off" name="oetJR1InsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                        </div>
                                    </div>

                                    <!--เพิ่มสินค้า-->
                                    <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 p-t-25 xCNHideWhenCancelOrApprove">
                                        <div style="margin-top:-2px;">
                                            <button type="button" id="obtJR1DocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-t-10" id="odvJR1DataPdtTableDTTemp"></div>

                                <!-- สรุปท้ายบิล -->
                                <?php include('wJobRequestStep1EndOfBill.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvJR1PopupCancel">
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
                <button onclick="JSxJR1DocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อนุมัติเอกสาร =========================================== -->
<div class="modal fade xCNModalApprove" id="odvJR1PopupApv">
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
                <button onclick="JSxJR1DocumentApv(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ลบสินค้าใน Temp แบบหลายตัว =========================================== -->
<div id="odvJR1ModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmJR1DocNoDelete" name="ohdConfirmJR1DocNoDelete">
                <input type="hidden" id="ohdConfirmJR1SeqNoDelete" name="ohdConfirmJR1SeqNoDelete">
                <input type="hidden" id="ohdConfirmJR1PdtCodeDelete" name="ohdConfirmJR1PdtCodeDelete">
                <input type="hidden" id="ohdConfirmJR1PunCodeDelete" name="ohdConfirmJR1PunCodeDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ตารางรายการ Append Modal DTSet ===========================================-->
<div id="odvJR1HtmlPopUpDTSet"></div>

<!-- =========================================== พบสินค้ามากกว่าหนึ่งตัว =========================================== -->
<div id="odvJS1ModalPDTMoreOne" class="modal fade">
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

<!-- =========================================== สต็อกไม่พออนุมัติไม่ได้ ===========================================-->
<div id="odvJR1ModalNotAproveBecauseStockFail" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospTextModalNotAproveStockFail">ไม่สามารถอนุมัติเอกสารได้เนื่องจากมีสินค้าบางรายการมีสต๊อกไม่เพียงพอ</p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxJR1ReloadDatatableDT()" type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jJobReqStep1PageForm.php'); ?>
<?php include('dis_chg/wJobReq1DisChgModal.php'); ?>