<style>
    .mark-font,
    .panel-default>.panel-heading.mark-font {
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<?php
$tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
$tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
$tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
$nAddressVersion = FCNaHAddressFormat('TCNMCst');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    // echo "<pre>";
    // print_r($aDataDocHD);
    // echo "</pre>";
    $aDataDocHD             = @$aDataDocHD['raItems'];
    $tTWXRoute               = "docBKOEventEdit";
    $tTWXDocNo               = $aDataDocHD['FTXthDocNo'];
    $dTWXDocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXthDocDate']));
    $dTWXDocTime             = date("H:i:s", strtotime($aDataDocHD['FDXthDocDate']));
    $tTWXCreateBy            = $aDataDocHD['FTCreateBy'];
    $tTWXUsrNameCreateBy     = $aDataDocHD['FTUsrName'];
    $tTWXBchCode             = $aDataDocHD['FTBchCode'];
    $tTWXBchName             = $aDataDocHD['FTBchName'];

    $tTWXStaDoc              = $aDataDocHD['FTXthStaDoc'];
    $tTWXStaApv              = $aDataDocHD['FTXthStaApv'];
    $tTWXStaPrcStk           = '';

    $tTWXSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tTWXDptCode             = $aDataDocHD['FTDptCode'];
    $tTWXUsrCode             = $this->session->userdata('tSesUsername');
    $tTWXLangEdit            = $this->session->userdata("tLangEdit");

    $tTWXApvCode             = $aDataDocHD['FTXthApvCode'];
    $tTWXUsrNameApv          = $aDataDocHD['FTXphApvName'];
    $tTWXRefPoDoc            = "";
    $tTWXRefIntDoc           = $aDataDocHD['FTXthRefInt'];
    $tTWXRefExtDoc           = $aDataDocHD['FTXthRefExt'];
    $nTWXStaRef              = $aDataDocHD['FNXthStaRef'];

    $tTWXBchName             = $aDataDocHD['FTBchName'];

    $nTWXStaDocAct           = $aDataDocHD['FNXthStaDocAct'];
    $tTWXFrmDocPrint         = $aDataDocHD['FNXthDocPrint'];
    $tTWXFrmRmk              = $aDataDocHD['FTXthRmk'];


    $nStaUploadFile         = 2;

    $tTWXCstCode = $aDataDocHD['rtCstCode'];
    $tTWXCstName = $aDataDocHD['rtCstName'];
    $tTWXCstCarRegno = $aDataDocHD['rtCarRegno'];
    $tTWXWahCode = $aDataDocHD['rtWahCode'];
    $tTWXWahName = $aDataDocHD['rtWahName'];
    $tTWXWahCodeTo = $aDataDocHD['rtWahCodeTo'];
    $tTWXWahNameTo = $aDataDocHD['rtWahNameTo'];
    $tTWXCarCode = $aDataDocHD['rtCarCode'];
    $tTWXCarBrand = $aDataDocHD['rtCarBrand'];
    $tTWXAddSeq = $aDataDocHD['rtAddSeq'];
    if($nAddressVersion == '2'){
        $tTWXAddress            = $aDataDocHD['rtAddress'];
    }elseif($nAddressVersion == '1'){
        $tTWXAddress            = $aDataDocHD['FTAddV1Desc'];
    }
    $tTWXTel    = $aDataDocHD['rtCstTel'];
    $tTWXEmail    = $aDataDocHD['rtCstEmail'];
    if (isset($aDataDocHD['FDXthRefExtDate'])) {
        $dTWXRefExtDocDate       = date("Y-m-d", strtotime($aDataDocHD['FDXthRefExtDate']));
    } else {
        $dTWXRefExtDocDate   = "";
    }
    if (isset($aDataDocHD['FDXthRefIntDate'])) {
        $dTWXRefIntDocDate       = date("Y-m-d", strtotime($aDataDocHD['FDXthRefIntDate']));
    } else {
        $dTWXRefIntDocDate   = "";
    }
} else {
    $tTWXRoute               = "docBKOEventAdd";
    $tTWXDocNo               = "";
    $dTWXDocDate             = date('Y-m-d');
    $dTWXDocTime             = date('H:i:s');
    $tTWXCreateBy            = $this->session->userdata('tSesUsrUsername');
    $tTWXUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
    $nTWXStaRef              = 0;
    $tTWXStaDoc              = 1;
    $tTWXStaApv              = NULL;
    $tTWXStaPrcStk           = NULL;

    $tTWXSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tTWXDptCode             = $tDptCode;
    $tTWXUsrCode             = $this->session->userdata('tSesUsername');
    $tTWXLangEdit            = $this->session->userdata("tLangEdit");

    $tTWXApvCode             = "";
    $tTWXUsrNameApv          = "";
    $tTWXRefPoDoc            = "";
    $tTWXRefIntDoc           = "";
    $dTWXRefIntDocDate       = "";
    $tTWXRefExtDoc           = "";
    $dTWXRefExtDocDate       = "";
    $tTWXCstCarRegno         = "";
    $tTWXWahCode             = $tWahCodeFrm;
    $tTWXWahName             = $tWahNameFrm;
    $tTWXWahCodeTo           = $tWahCodeTo;
    $tTWXWahNameTo           = $tWahNameTo;
    $tTWXCarCode             = "";
    $tTWXCarBrand            = "";
    $tTWXAddSeq              = "";
    $tTWXAddress             = "";
    $tTWXTel                 = "";
    $tTWXEmail               = "";

    $tTWXBchCode             = $tBchCode;
    $tTWXBchName             = $tBchName;

    $nTWXStaDocAct           = "";
    $tTWXFrmDocPrint         = "";
    $tTWXFrmRmk              = "";

    $tTWXCstCode             = "";
    $tTWXCstName             = "";

    // ข้อมูลผู้จำหน่าย Supplier
    $nStaUploadFile         = 1;
    $tTWXDataInputBchCode = "";
    $tTWXDataInputBchName = "";
}
if (empty($tTWXBchCode) && empty($tTWXShopCode)) {
    $tASTUserType   = "HQ";
} else {
    if (!empty($tTWXBchCode) && empty($tTWXShopCode)) {
        $tASTUserType   = "BCH";
    } else if (!empty($tTWXBchCode) && !empty($tTWXShopCode)) {
        $tASTUserType   = "SHP";
    } else {
        $tASTUserType   = "";
    }
}
?>

<style>
    #odvRowDataEndOfBill .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvRowDataEndOfBill .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }

    .mark-font,
    .panel-default>.panel-heading.mark-font {
        color: #232C3D !important;
        font-weight: 900;
    }
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
<form id="ofmTWXFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdTWXPage" name="ohdTWXPage" value="1">
    <input type="hidden" id="ohdTWXStaaImport" name="ohdTWXStaaImport" value="0">
    <input type="hidden" id="ohdTWXRoute" name="ohdTWXRoute" value="<?php echo $tTWXRoute; ?>">
    <input type="hidden" id="ohdTWXOldDocno" name="ohdTWXOldDocno" value="<?php echo $tTWXDocNo; ?>">
    <input type="hidden" id="ohdTWXCheckClearValidate" name="ohdTWXCheckClearValidate" value="0">
    <input type="hidden" id="ohdTWXCheckSubmitByButton" name="ohdTWXCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdTWXDecimalShow" name="ohdTWXDecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdTWXStaDoc" name="ohdTWXStaDoc" value="<?php echo $tTWXStaDoc; ?>">
    <input type="hidden" id="ohdTWXStaApv" name="ohdTWXStaApv" value="<?php echo $tTWXStaApv; ?>">
    <input type="hidden" id="ohdTWXStaPrcStk" name="ohdTWXStaPrcStk" value="<?php echo $tTWXStaPrcStk; ?>">
    <input type="hidden" id="ohdTWXRefIntDoc" name="ohdTWXRefIntDoc" value="<?php echo $tTWXRefIntDoc; ?>">


    <input type="hidden" id="ohdTWXSesUsrBchCode" name="ohdTWXSesUsrBchCode" value="<?php echo $tTWXSesUsrBchCode; ?>">
    <input type="hidden" id="ohdTWXBchCode" name="ohdTWXBchCode" value="<?php echo $tTWXBchCode; ?>">
    <input type="hidden" id="ohdTWXDptCode" name="ohdTWXDptCode" value="<?php echo $tTWXDptCode; ?>">
    <input type="hidden" id="ohdTWXUsrCode" name="ohdTWXUsrCode" value="<?php echo $tTWXUsrCode ?>">

    <input type="hidden" id="ohdTWXLangEdit" name="ohdTWXLangEdit" value="<?php echo $tTWXLangEdit; ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdSesSessionName" name="ohdSesSessionName" value="<?= $this->session->userdata('tSesUsrUsername') ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden" id="ohdTWXValidatePdt" name="ohdTWXValidatePdt" value="<?= language('document/bookingorder/bookingorder', 'tTWXPleaseSeletedPDTIntoTable') ?>">
    <input type="hidden" id="ohdTWXSubmitWithImp" name="ohdTWXSubmitWithImp" value="0">

    <input type="hidden" id="ohdTWXValidatePdtImp" name="ohdTWXValidatePdtImp" value="<?= language('document/bookingorder/bookingorder', 'tTWXNotFoundPdtCodeAndBarcodeImpList') ?>">

    <button style="display:none" type="submit" id="obtTWXSubmitDocument" onclick="JSxTWXAddEditDocument()"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvTWXHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/bookingorder/bookingorder', 'tTWXDoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvTWXDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvTWXDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmDocNo'); ?></label>
                                <?php if (isset($tTWXDocNo) && empty($tTWXDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbTWXStaAutoGenCode" name="ocbTWXStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetTWXDocNo" name="oetTWXDocNo" maxlength="20" value="<?php echo $tTWXDocNo; ?>" data-validate-required="<?php echo language('document/bookingorder/bookingorder', 'tTWXPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/bookingorder/bookingorder', 'tTWXPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdTWXCheckDuplicateCode" name="ohdTWXCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <?php if ($dTWXDocDate == '') {
                                            $dTWXDocDate = '';
                                        } ?>
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetTWXDocDate" name="oetTWXDocDate" value="<?php echo $dTWXDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTWXDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetTWXDocTime" name="oetTWXDocTime" value="<?php echo $dTWXDocTime; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTWXDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdTWXCreateBy" name="ohdTWXCreateBy" value="<?php echo $tTWXCreateBy ?>">
                                            <label><?php echo $tTWXUsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tTWXRoute == "docBKOEventAdd") {
                                                $tTWXLabelStaDoc  = language('document/bookingorder/bookingorder', 'tTWXLabelFrmValStaDoc');
                                            } else {
                                                $tTWXLabelStaDoc  = language('document/bookingorder/bookingorder', 'tTWXLabelFrmValStaDoc' . $tTWXStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tTWXLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmValStaApv' . $tTWXStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">

                                            <label><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmStaRef' . $nTWXStaRef); ?></label>

                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tTWXDocNo) && !empty($tTWXDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdTWXApvCode" name="ohdTWXApvCode" maxlength="20" value="<?php echo $tTWXApvCode ?>">
                                                <label>
                                                    <?php echo (isset($tTWXUsrNameApv) && !empty($tTWXUsrNameApv)) ? $tTWXUsrNameApv : "-" ?>
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

            <!-- Panel อ้างอิงเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvTWXReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabeAcpBch'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTWXDataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvTWXDataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
                                <div class="form-group m-b-0">
                                    <?php
                                    $tTWXDataInputBchCode   = "";
                                    $tTWXDataInputBchName   = "";
                                    if ($tTWXRoute  == "docBKOEventAdd") {
                                        $tTWXDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                        $tTWXDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                        $tDisabledBch = '';
                                    } else {
                                        $tTWXDataInputBchCode    = $aDataDocHD['FTBchCode'];
                                        $tTWXDataInputBchName    = $aDataDocHD['FTBchName'];
                                        $tDisabledBch = 'disabled';
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefHead'); ?></label>
                                    <select class="selectpicker xWTWXDisabledOnApv form-control xControlForm" id="ocmTWXSelectBrowse" name="ocmTWXSelectBrowse" maxlength="1">
                                        <option value="0"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefSODoc'); ?></option>
                                        <option value="1"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefQTDoc'); ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="xCNHide" id="ohdTWXRefInAllCode" name="ohdTWXRefSOCode" maxlength="5" value="">
                                        <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetTWXRefInAllName" name="oetTWXRefInAllName" value="<?php echo $tTWXRefIntDoc; ?>" readonly placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTWXBrowseRefDocIntMulti" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- <div style="border:1px solid #ccc;position:relative;padding:15px;">
                                    <label class="xCNLabelFrm" style="position:absolute;top:-15px;left:15px;
								background: #fff;
								padding-left: 10px;
								padding-right: 10px;"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefHead'); ?></label>


                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input xCNRadioPrint" type="radio" name="ocbPOPurchase" id="ocbPOPurchase" value="1">
                                        <label class="form-check-label" for="ocbPOPurchase">&nbsp;<?= language('document/bookingorder/bookingorder', 'tTWXRefSODoc'); ?></label>
                                    </div>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefSO'); ?></label>
                                        <div class="input-group">
                                            <input type="text" class="xCNHide" id="ohdTWXRefSOCode" name="ohdTWXRefSOCode" maxlength="5" value="">
                                            <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetTWXRefSOName" name="oetTWXRefSOName" value="" readonly placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXRefSO'); ?>">
                                            <span class="input-group-btn">
                                                <button id="obtTWXBrowseRefDocInt" type="button" class="btn xCNBtnBrowseAddOn" disabled>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input xCNRadioPrint" type="radio" name="ocbTWXSO" id="ocbTWXSO" value="1">
                                        <label class="form-check-label" for="ocbTWXSO">&nbsp;<?= language('document/bookingorder/bookingorder', 'tTWXRefPODoc'); ?></label>
                                    </div>


                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefPO'); ?></label>
                                        <div class="input-group">
                                            <input type="text" class="xCNHide" id="ohdTWXRefPOCode" name="ohdTWXRefPOCode" maxlength="5" value="">
                                            <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetTWXRefPOName" name="oetTWXRefPOName" value="" readonly placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXRefPO'); ?>">
                                            <span class="input-group-btn">
                                                <button id="obtTWXSO" type="button" class="btn xCNBtnBrowseAddOn" disabled>
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>


                                </div> -->

                                <!-- อ้างอิงเลขที่เอกสารภายใน -->
                                <!-- <div class="">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc') ?></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="oetTWXShowRefInt" name="oetTWXShowRefInt" maxlength="20" value="<?php echo $tTWXRefIntDoc; ?>" placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc') ?>" readonly>
                                    </div>
                                </div> -->

                                <!-- Ref Doc Int Datepicker -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocIntDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetTWXRefIntDocDate" name="oetTWXRefIntDocDate" value="<?php echo $dTWXRefIntDocDate ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXPHDRefTSCode') ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTWXBrowseRefIntDocDate" name="obtTWXBrowseRefIntDocDate" type="button" class="btn xCNDatePicker xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- Ref Doc Ext input -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocExt'); ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetTWXRefDocExt" name="oetTWXRefDocExt" maxlength="20" value="<?php echo $tTWXRefExtDoc; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocExt'); ?>">
                                </div>

                                <!-- Ref Doc Ext Datepicker -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocExtDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetTWXRefDocExtDate" name="oetTWXRefDocExtDate" value="<?php echo $dTWXRefExtDocDate; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXPHDRefTSCode'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTWXRefDocExtDate" name="obtTWXRefDocExtDate" type="button" class="btn xCNDatePicker xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ลูกค้า-->
            <?php include('panel/wPanelCutomer.php'); ?>

            <!-- Panel เงื่อนไขคลังสินค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvTWXWahCondit" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/bookingorder/bookingorder', 'tTWWahCondition'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvTWXWahCon" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvTWXWahCon" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                            $tTWXDataInputBchCode   = "";
                            $tTWXDataInputBchName   = "";
                            if ($tTWXRoute  == "docBKOEventAdd") {
                                $tTWXDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                $tTWXDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                $tDisabledBch = '';
                            } else {
                                $tTWXDataInputBchCode    = $tTWXBchCode;
                                $tTWXDataInputBchName    = $tTWXBchName;
                                $tDisabledBch = 'disabled';
                            }
                            ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmBranch'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahBrcCode" name="oetTWXWahBrcCode" value="<?php echo $tTWXDataInputBchCode ?>" readonly>
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahBrcName" name="oetTWXWahBrcName" value="<?php echo $tTWXDataInputBchName ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmBranch') ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtTWXBrowseWahBch" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/bookingorder/bookingorder', 'tTWXWahFrm'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahFrmCode" name="oetTWXWahFrmCode" value="<?php echo $tTWXWahCode ?>" readonly>
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahFrmName" name="oetTWXWahFrmName" value="<?php echo $tTWXWahName ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXWahFrm') ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtTWXBrowseWahFrm" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/bookingorder/bookingorder', 'tTWXWahBooking'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahBookCode" name="oetTWXWahBookCode" value="<?php echo $tTWXWahCodeTo ?>" readonly>
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetTWXWahBookName" name="oetTWXWahBookName" value="<?php echo $tTWXWahNameTo ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXWahBooking') ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtTWXBrowseWahBook" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
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
                <div id="odvTWXInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/bookingorder/bookingorder', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvTWXDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvTWXDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbTWXFrmInfoOthStaDocAct" name="ocbTWXFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nTWXStaDocAct == '1' || empty($nTWXStaDocAct)) ? 'checked' : ''; ?> checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <?php if ($nTWXStaRef == 0) {
                                    $tSelect = "selected";
                                    $tSelect2 = "";
                                    $tSelect3 = "";
                                } elseif ($nTWXStaRef == 1) {
                                    $tSelect = "";
                                    $tSelect2 = "selected";
                                    $tSelect3 = "";
                                } elseif ($nTWXStaRef == 2) {
                                    $tSelect = "";
                                    $tSelect2 = "";
                                    $tSelect3 = "selected";
                                } ?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthRef'); ?></label>
                                    <select class="selectpicker xWTWXDisabledOnApv form-control xControlForm" id="ocmTWXFrmInfoOthRef" name="ocmTWXFrmInfoOthRef" maxlength="1">
                                        <option value="0" <?php echo $tSelect; ?>><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthRef0'); ?></option>
                                        <option value="1" <?php echo $tSelect2; ?>><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthRef1'); ?></option>
                                        <option value="2" <?php echo $tSelect3; ?>><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthRef2'); ?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthDocPrint'); ?></label>
                                    <input type="text" class="form-control xControlForm text-right" id="ocmTWXFrmInfoOthDocPrint" name="ocmTWXFrmInfoOthDocPrint" value="<?php echo $tTWXFrmDocPrint; ?>" readonly>
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xControlForm selectpicker xWTWXDisabledOnApv" id="ocmTWXFrmInfoOthReAddPdt" name="ocmTWXFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelFrmInfoOthRemark'); ?></label>
                                    <textarea class="form-control xControlRmk xWConditionSearchPdt" id="otaTWXFrmInfoOthRmk" name="otaTWXFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?php echo $tTWXFrmRmk ?></textarea>
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
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvTWXShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oTWXCallDataTableFile = {
                        ptElementID     : 'odvTWXShowDataTable',
                        ptBchCode       : $('#oetTWXWahBrcCode').val(),
                        ptDocNo         : $('#oetTWXDocNo').val(),
                        ptDocKey        : 'TCNTPdtTwxHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : '<?= $nStaUploadFile ?>',
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdTWXStaApv').val(),
                        ptStaDoc        : $('#ohdTWXStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oTWXCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvTWXDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div class="row p-t-10">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXCstBooking'); ?></label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xControlForm xCNHide" id="oetTWXFrmCstCode" name="oetTWXFrmCstCode" value="<?php echo $tTWXCstCode; ?>">
                                                <input type="text" class="form-control xControlForm" id="oetTWXFrmCstName" name="oetTWXFrmCstName" value="<?php echo $tTWXCstName; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstBooking') ?>" data-validate-required="<?php echo language('document/bookingorder/bookingorder', 'tTWXPlsEnterSplCode'); ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtTWXBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img class="xCNIconFind">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-t-10">

                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvTWXCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvTWXCSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-4 text-right">

                                        <div class="row">
                                            <!--ตัวเลือก-->
                                            <div id="odvTWXMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                    <?php echo language('common/main/main', 'tCMNOption') ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliTWXBtnDeleteMulti">
                                                        <a data-toggle="modal" data-target="#odvTWXModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                                        <!--ค้นหาจากบาร์โค๊ด-->
                                        <div class="form-group" style="width: 85%;">
                                            <input type="text" class="form-control xControlForm" id="oetTWXInsertBarcode" autocomplete="off" name="oetTWXInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                        </div>

                                        <!--เพิ่มสินค้าแบบปกติ-->
                                        <div class="form-group">
                                            <div style="position: absolute;right: 15px;top:-5px;">
                                                <button type="button" id="obtTWXDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="row p-t-10" id="odvTWXDataPdtTableDTTemp">
                                </div>
                                <!--ส่วนสรุปท้ายบิล-->
                                <div class="odvRowDataEndOfBill" id="odvRowDataEndOfBill">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <label class="pull-left mark-font"><?= language('document/bookingorder/bookingorder', 'จำนวนจองรวมทั้งสิ้น'); ?></label>
                                            <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?= language('document/bookingorder/bookingorder', 'tTWXItems'); ?></label>
                                            <div class="clearfix"></div>
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
<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvTWXModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxTWXApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvTWXPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/bookingorder/bookingorder', 'tTWXCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/bookingorder/bookingorder', 'tTWXCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/bookingorder/bookingorder', 'tTWXCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnTWXCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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

<!-- =====================================================================  Modal Advance Table Product DT Temp ==================================================================-->
<div class="modal fade" id="odvTWXOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('common/main/main', 'tModalAdvTable'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="odvTWXModalBodyAdvTable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button id="obtTWXSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvTWXModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmTWXDocNoDelete" name="ohdConfirmTWXDocNoDelete">
                <input type="hidden" id="ohdConfirmTWXSeqNoDelete" name="ohdConfirmTWXSeqNoDelete">
                <input type="hidden" id="ohdConfirmTWXPdtCodeDelete" name="ohdConfirmTWXPdtCodeDelete">
                <input type="hidden" id="ohdConfirmTWXPunCodeDelete" name="ohdConfirmTWXPunCodeDelete">

            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบตัวแทนขาย   ======================================================================== -->
<div id="odvTWXModalPleseselectCST" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/bookingorder/bookingorder', 'tTWXCstNotFound') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->
<!-- ======================================================================== Modal ไม่พบตัวแทนขาย   ======================================================================== -->
<div id="odvTWXModalPleseselectCSTCar" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/bookingorder/bookingorder', 'tTWXCstNotFoundCar') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvTWXModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/bookingorder/bookingorder', 'tTWXPdtNotFound') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvTWXModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/bookingorder/bookingorder', 'tTWXSelectPdt') ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/bookingorder/bookingorder', 'tTWXChoose') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/bookingorder/bookingorder', 'tTWXClose') ?></button>
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

<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvTWXModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocInt') ?></label>
            </div>

            <div class="modal-body">
                <div class="row" id="odvTWXFromRefIntDoc"></div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->
<div id="odvTWXModalWahNoFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/bookingorder/bookingorder', 'tTWXWahNotFound') ?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/bookingorder/bookingorder', 'tTWXPlsSelectWah') ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jBookingOrderAdd.php'); ?>
<?php include("script/jBookingOrderAdvTableData.php"); ?>

<script>
    //ค้นหาสินค้าใน temp
    function JSvTWXCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbTWXDocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxNotFoundClose() {
        $('#oetTWXInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetTWXFrmCstName').val() != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                // JCNxOpenLoading();
                $('#oetTWXInsertBarcode').attr('readonly', true);
                JCNSearchBarcodePdt(tValue);
                $('#oetTWXInsertBarcode').val('');
            }
        } else {
            $('#odvTWXModalPleseselectCST').modal('show');
            $('#oetTWXInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {

        var tWhereCondition = "";
        // if( tPISplCode != "" ){
        //     tWhereCondition = " AND FTPdtSetOrSN IN('1','2') ";
        // }

        //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
        var aWhereItem      = [];
        tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType      : ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc        : "",
                SPL             : $("#oetTWXFrmCstCode").val(),
                BCH             : $("#oetTWXWahBrcCode").val(),
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode     : $('#ohdTWXUsrCode').val(),
                tInpLangEdit    : $('#ohdTWXLangEdit').val(),
                tInpSesUsrLevel : $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                tWhere          : aWhereItem,
                tTextScan       : ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $('#oetTWXInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetTWXInsertBarcode').attr('readonly', false);
                    $('#odvTWXModalPDTNotFound').modal('show');
                    $('#oetTWXInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvTWXModalPDTMoreOne').modal('show');
                        $('#odvTWXModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
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
                            $('#odvTWXModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvTWXModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvTWXAddPdtIntoDocDTTemp(tJSON); //Client
                            FSvTWXAddBarcodeIntoDocDTTemp(tJSON);
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {
                            //เลือกสินค้าแบบหลายตัว
                            // var tCheck = $(this).hasClass('xCNActivePDT');
                            // if($(this).hasClass('xCNActivePDT')){
                            //     //เอาออก
                            //     $(this).removeClass('xCNActivePDT');
                            //     $(this).children().attr('style', 'background-color:transparent !important; color:#232C3D !important');
                            // }else{
                            //     //เลือก
                            //     $(this).addClass('xCNActivePDT');
                            //     $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important');
                            // }

                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'right');
                        });
                    } else {
                        //มีตัวเดียว
                        var aNewReturn = JSON.stringify(oText);
                        console.log('aNewReturn: ' + aNewReturn);
                        // var aNewReturn  = '[{"pnPdtCode":"00009","ptBarCode":"ca2020010003","ptPunCode":"00001","packData":{"SHP":null,"BCH":null,"PDTCode":"00009","PDTName":"ขนม_03","PUNCode":"00001","Barcode":"ca2020010003","PUNName":"ขวด","PriceRet":"17.00","PriceWhs":"0.00","PriceNet":"0.00","IMAGE":"D:/xampp/htdocs/Moshi-Moshi/application/modules/product/assets/systemimg/product/00009/Img200128172902CEHHRSS.jpg","LOCSEQ":"","Remark":"ขนม_03","CookTime":0,"CookHeat":0}}]';
                        FSvTWXAddPdtIntoDocDTTemp(aNewReturn); //Client
                        // JCNxCloseLoading();
                        // $('#oetTWXInsertBarcode').attr('readonly',false);
                        // $('#oetTWXInsertBarcode').val('');
                        FSvTWXAddBarcodeIntoDocDTTemp(aNewReturn); //Server
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
            $("#odvTWXModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvTWXAddPdtIntoDocDTTemp(tJSON);
                FSvTWXAddBarcodeIntoDocDTTemp(tJSON);
            });
        } else {
            $('#oetTWXInsertBarcode').attr('readonly', false);
            $('#oetTWXInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvTWXAddBarcodeIntoDocDTTemp(ptPdtData) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var ptXthDocNoSend = "";
            if ($("#ohdTWXRoute").val() == "docBKOEventEdit") {
                ptXthDocNoSend = $("#oetTWXDocNo").val();
            }
            var tTWXOptionAddPdt = $('#ocmTWXFrmInfoOthReAddPdt').val();
            var nKey = parseInt($('#otbTWXDocPdtAdvTableList tr:last').attr('data-seqno'));

            $('#oetTWXInsertBarcode').attr('readonly', false);
            $('#oetTWXInsertBarcode').val('');

            $.ajax({
                type: "POST",
                url: "docBKOAddPdtIntoDTDocTemp",
                data: {
                    'tSelectBCH': $('#oetTWXWahBrcCode').val(),
                    'tTWXDocNo': ptXthDocNoSend,
                    'tTWXOptionAddPdt': tTWXOptionAddPdt,
                    'tTWXPdtData': ptPdtData,
                    'ohdSesSessionID': $('#ohdSesSessionID').val(),
                    'ohdTWXUsrCode': $('#ohdTWXUsrCode').val(),
                    'ohdTWXLangEdit': $('#ohdTWXLangEdit').val(),
                    'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                    'ohdTWXSesUsrBchCode': $('#ohdTWXSesUsrBchCode').val(),
                    'tSeqNo': nKey
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // JSvTWXLoadPdtDataTableHtml();
                    var aResult = JSON.parse(oResult);

                    if (aResult['nStaEvent'] == 1) {
                        JCNxCloseLoading();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvTWXAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        } else {
            JCNxphowMsgSessionExpired();
        }
    }
</script>
