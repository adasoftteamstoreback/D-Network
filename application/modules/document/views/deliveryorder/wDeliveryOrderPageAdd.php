<?php
$tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
$tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
$tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
$tUserWahName   = $this->session->userdata('tSesUsrWahName');
$tUserWahCode   = $this->session->userdata('tSesUsrWahCode');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    $aDataDocHD             = @$aDataDocHD['raItems'];
    $tDORoute               = "docDOEventEdit";
    $nDOAutStaEdit          = 1;
    $tDODocNo               = $aDataDocHD['FTXphDocNo'];
    $dDODocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXphDocDate']));
    $dDODocTime             = date("H:i", strtotime($aDataDocHD['FDXphDocDate']));
    $tDOCreateBy            = $aDataDocHD['FTCreateBy'];
    $tDOUsrNameCreateBy     = $aDataDocHD['FTUsrName'];

    $tDOStaDoc              = $aDataDocHD['FTXphStaDoc'];
    $tDOStaApv              = $aDataDocHD['FTXphStaApv'];
    $tDORefPO               = $aDataDocHD['rtPORef'];
    $tDORefIV               = $aDataDocHD['rtPIRef'];
    $tDORefSO               = $aDataDocHD['rtSORef'];

    $tDOStaPrcStk           = '';
    $tDOStaDelMQ            = '';

    $tDOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tDODptCode             = $aDataDocHD['FTDptCode'];
    $tDOUsrCode             = $this->session->userdata('tSesUsername');
    $tDOLangEdit            = $this->session->userdata("tLangEdit");

    $tDOApvCode             = $aDataDocHD['FTXphApvCode'];
    $tDOUsrNameApv          = $aDataDocHD['FTXphApvName'];
    $tDORefPoDoc            = "";
    $tDORefIntDoc           = $aDataDocHD['FTXphRefInt'];
    $dDORefIntDocDate       = $aDataDocHD['FDXphRefIntDate'];
    $tDORefExtDoc           = $aDataDocHD['FTXphRefExt'];
    $dDORefExtDocDate       = $aDataDocHD['FDXphRefExtDate'];

    $nDOStaRef              = $aDataDocHD['FNXphStaRef'];

    $tDOBchCode             = $aDataDocHD['FTBchCode'];
    $tDOBchName             = $aDataDocHD['FTBchName'];
    $tDOUserBchCode         = $tUserBchCode;
    $tDOUserBchName         = $tUserBchName;

    $tDOWahCode             = $aDataDocHD['FTWahCode'];
    $tDOWahName             = $aDataDocHD['rtWahName'];
    $nDOStaDocAct           = $aDataDocHD['FNXphStaDocAct'];
    $tDOFrmDocPrint         = $aDataDocHD['FNXphDocPrint'];
    $tDOFrmRmk              = $aDataDocHD['FTXphRmk'];
    $tDOSplCode             = $aDataDocHD['FTSplCode'];
    $tDOSplName             = $aDataDocHD['FTSplName'];

    $tDOCmpRteCode          = $aDataDocHD['FTRteCode'];
    $cDORteFac              = $aDataDocHD['FTRteName'];

    $tDOVatInOrEx           = $aDataDocHD['FTXphVATInOrEx'];
    $tDOSplPayMentType      = $aDataDocHD['FTXphCshOrCrd'];

    // ข้อมูลผู้จำหน่าย Supplier
    $tDOSplCrTerm           = $aDataDocHD['FNXphCrTerm'];
    $tDOSplCtrName          = $aDataDocHD['FTXphCtrName'];
    $dDOSplTnfDate          = $aDataDocHD['FDXphTnfDate'];
    $tDOSplRefTnfID         = $aDataDocHD['FTXphRefTnfID'];
    $tDOSplRefVehID         = $aDataDocHD['FTXphRefVehID'];
    $tDOSplRefInvNo         = $aDataDocHD['FTXphRefInvNo'];
    $nStaUploadFile         = 2;
    $nPOStaDocAct           = $aDataDocHD['FNXphStaDocAct'];
    $tDOAgnCode             = $aDataDocHD['rtAgnCode'];
    $tDOAgnName             = $aDataDocHD['rtAgnName'];
} else {
    $tDORoute               = "docDOEventAdd";
    $nDOAutStaEdit          = 0;
    $tDODocNo               = "";
    $dDODocDate             = "";
    $dDODocTime             = date('H:i:s');
    $tDOCreateBy            = $this->session->userdata('tSesUsrUsername');
    $tDOUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
    $nDOStaRef              = 0;
    $tDOStaDoc              = 1;
    $tDOStaApv              = NULL;
    $tDOStaPrcStk           = NULL;
    $tDOStaDelMQ            = NULL;

    $tDOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tDODptCode             = '';
    $tDOUsrCode             = $this->session->userdata('tSesUsername');
    $tDOLangEdit            = $this->session->userdata("tLangEdit");

    $tDOApvCode             = "";
    $tDOUsrNameApv          = "";
    $tDORefPoDoc            = "";
    $tDORefIntDoc           = "";
    $dDORefIntDocDate       = "";
    $tDORefExtDoc           = "";
    $dDORefExtDocDate       = "";
    $tDORefPO               = "";
    $tDORefIV               = "";
    $tDORefSO               = "";

    $tDOBchCode             = "";
    $tDOBchName             = "";
    $tDOUserBchCode         = "";
    $tDOUserBchName         = "";

    $tDOWahCode             = "";
    $tDOWahName             = "";
    $nDOStaDocAct           = "";
    $tDOFrmDocPrint         = "";
    $tDOFrmRmk              = "";

    $aSPLConfig             = $aSPLConfig;
    if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์
        $tDOSplCode         = $aSPLConfig['rtSPLCode'];
        $tDOSplName         = $aSPLConfig['rtSPLName'];
    }else{ //สำนักงานใหญ่
        $tDOSplCode         = "";
        $tDOSplName         = "";
    }

    $tDOCmpRteCode          = "";
    $cDORteFac              = "";

    $tDOVatInOrEx           = "";
    $tDOSplPayMentType      = "";

    // ข้อมูลผู้จำหน่าย Supplier
    $tDOSplDstPaid          = "1";
    $tDOSplCrTerm           = "";
    $dDOSplDueDate          = "";
    $dDOSplBillDue          = "";
    $tDOSplCtrName          = "";
    $dDOSplTnfDate          = "";
    $tDOSplRefTnfID         = "";
    $tDOSplRefVehID         = "";
    $tDOSplRefInvNo         = "";
    $tDOSplQtyAndTypeUnit   = "";
    $nStaUploadFile         = 1;
    $nPOStaDocAct           = "";
    $tDOAgnCode             = $this->session->userdata('tSesUsrAgnCode');
    $tDOAgnName             = $this->session->userdata('tSesUsrAgnName');
}
if (empty($tDOBchCode) && empty($tDOShopCode)) {
    $tASTUserType   = "HQ";
} else {
    if (!empty($tDOBchCode) && empty($tDOShopCode)) {
        $tASTUserType   = "BCH";
    } else if (!empty($tDOBchCode) && !empty($tDOShopCode)) {
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
</style>
<form id="ofmDOFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdDOPage" name="ohdDOPage" value="1">
    <input type="hidden" id="ohdDOStaaImport" name="ohdDOStaaImport" value="0">
    <input type="hidden" id="ohdDORoute" name="ohdDORoute" value="<?php echo $tDORoute; ?>">
    <input type="hidden" id="ohdDOCheckClearValidate" name="ohdDOCheckClearValidate" value="0">
    <input type="hidden" id="ohdDOCheckSubmitByButton" name="ohdDOCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdDOAutStaEdit" name="ohdDOAutStaEdit" value="<?php echo $nDOAutStaEdit; ?>">
    <input type="hidden" id="ohdDOODecimalShow" name="ohdDOODecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdDOStaDoc" name="ohdDOStaDoc" value="<?php echo $tDOStaDoc; ?>">
    <input type="hidden" id="ohdDOStaApv" name="ohdDOStaApv" value="<?php echo $tDOStaApv; ?>">
    <input type="hidden" id="ohdDOPORef" name="ohdDOPORef" value="<?php echo $tDORefPO; ?>">
    <input type="hidden" id="ohdDOPIRef" name="ohdDOPIRef" value="<?php echo $tDORefIV; ?>">
    <input type="hidden" id="ohdDOSORef" name="ohdDOSORef" value="<?php echo $tDORefSO; ?>">

    <input type="hidden" id="ohdDOStaDelMQ" name="ohdDOStaDelMQ" value="<?php echo $tDOStaDelMQ; ?>">
    <input type="hidden" id="ohdDOStaPrcStk" name="ohdDOStaPrcStk" value="<?php echo $tDOStaPrcStk; ?>">

    <input type="hidden" id="ohdDOSesUsrBchCode" name="ohdDOSesUsrBchCode" value="<?php echo $tDOSesUsrBchCode; ?>">
    <input type="hidden" id="ohdDOBchCode" name="ohdDOBchCode" value="<?php echo $tDOBchCode; ?>">
    <input type="hidden" id="ohdDODptCode" name="ohdDODptCode" value="<?php echo $tDODptCode; ?>">
    <input type="hidden" id="ohdDOUsrCode" name="ohdDOUsrCode" value="<?php echo $tDOUsrCode ?>">
    <input type="hidden" id="ohdDOPayType" name="ohdDOPayType" value="<?php echo $cDORteFac; ?>">

    <input type="hidden" id="ohdDOCmpRteCode" name="ohdDOCmpRteCode" value="<?php echo $tDOCmpRteCode; ?>">
    <input type="hidden" id="ohdDORteFac" name="ohdDORteFac" value="<?php echo $tDOSplPayMentType; ?>">
    <input type="hidden" id="ohdDOStaRef" name="ohdDOStaRef" value="<?php echo $nDOStaRef; ?>">
    <input type="hidden" id="ohdDOVatInOrEx" name="ohdDOVatInOrEx" value="<?php echo $tDOVatInOrEx; ?>">

    <input type="hidden" id="ohdDOApvCodeUsrLogin" name="ohdDOApvCodeUsrLogin" value="<?php echo $tDOUsrCode; ?>">
    <input type="hidden" id="ohdDOLangEdit" name="ohdDOLangEdit" value="<?php echo $tDOLangEdit; ?>">
    <input type="hidden" id="ohdDOOptAlwSaveQty" name="ohdDOOptAlwSaveQty" value="<?php echo $nOptDocSave ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdSesSessionName" name="ohdSesSessionName" value="<?= $this->session->userdata('tSesUsrUsername') ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden" id="ohdDOValidatePdt" name="ohdDOValidatePdt" value="<?= language('document/deliveryorder/deliveryorder', 'tDOPleaseSeletedPDTIntoTable') ?>">
    <input type="hidden" id="ohdDOSubmitWithImp" name="ohdDOSubmitWithImp" value="0">
    <input type="hidden" id="ohdDOVATInOrEx" name="ohdDOVATInOrEx" value="">
    <input type="hidden" id="ohdDODocType" name="ohdDODocType" value="">
    <input type="hidden" id="ohdDOApvOrSave" name="ohdDOApvOrSave" value="">

    <input type="hidden" id="ohdDOValidatePdtImp" name="ohdDOValidatePdtImp" value="<?= language('document/deliveryorder/deliveryorder', 'tDONotFoundPdtCodeAndBarcodeImpList') ?>">

    <button style="display:none" type="submit" id="obtDOSubmitDocument" onclick="JSxDOAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDOHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDODataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDODataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?></label>
                                <?php if (isset($tDODocNo) && empty($tDODocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbDOStaAutoGenCode" name="ocbDOStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetDODocNo" name="oetDODocNo" maxlength="20" value="<?php echo $tDODocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tDOPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tDOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdDOCheckDuplicateCode" name="ohdDOCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <?php if ($dDODocDate == '') {
                                            $dDODocDate = '';
                                        } ?>
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDODocDate" name="oetDODocDate" value="<?php echo $dDODocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDODocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetDODocTime" name="oetDODocTime" value="<?php echo $dDODocTime; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDODocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <input type="hidden" id="ohdDOCreateBy" name="ohdDOCreateBy" value="<?php echo $tDOCreateBy ?>">
                                            <label><?php echo $tDOUsrNameCreateBy ?></label>
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
                                            if ($tDORoute == "docDOEventAdd") {
                                                $tDOLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tDOLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tDOStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tDOLabelStaDoc; ?></label>
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
                                            <?php 
                                                if ($tDOStaDoc == 3) {
                                                    $tDOLabelStaApv = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . 3);
                                                }else{
                                                    $tDOLabelStaApv = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv' . $tDOStaApv);
                                                } 
                                            ?>
                                            <label><?php echo $tDOLabelStaApv; ?></label>
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

                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmStaRef' . $nDOStaRef); ?></label>

                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tDODocNo) && !empty($tDODocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdDOApvCode" name="ohdDOApvCode" maxlength="20" value="<?php echo $tDOApvCode ?>">
                                                <label>
                                                    <?php echo (isset($tDOUsrNameApv) && !empty($tDOUsrNameApv)) ? $tDOUsrNameApv : "-" ?>
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

            <!-- Panel สาขาที่รับของ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabeAcpBch'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDODataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDODataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
                                <div class="form-group m-b-0">
                                    <?php
                                    $tDODataInputBchCode   = "";
                                    $tDODataInputBchName   = "";
                                    if ($tDORoute  == "docDOEventAdd") {
                                        $tDODataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                        $tDODataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                        $tDisabledBch = '';
                                    } else {
                                        $tDODataInputBchCode    = $tDOBchCode;
                                        $tDODataInputBchName    = $tDOBchName;
                                        $tDisabledBch = 'disabled';
                                    }
                                    ?>
                                    <!--สาขา-->
                                    <script>
                                        var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                        if (tUsrLevel != "HQ") {
                                            var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                                            if(tBchCount < 2){
                                                $('#obtDOBrowseBCH').attr('disabled', true);
                                            }
                                        }
                                    </script>
                                    <!--Agn Browse-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                        <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDOAgnCode" name="oetDOAgnCode" maxlength="5" value="<?=$tDOAgnCode;?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDOAgnName" name="oetDOAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?=$tDOAgnName; ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="oimDOBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <!--Agn Bch-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetDOFrmBchCode" name="oetDOFrmBchCode" maxlength="5" value="<?php echo @$tDODataInputBchCode ?>" data-bchcodeold="<?php echo @$tDODataInputBchCode ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDOFrmBchName" name="oetDOFrmBchName" maxlength="100" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterBch'); ?>" value="<?php echo @$tDODataInputBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtDOBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <!-- Condition คลังสินค้า -->
                                <?php
                                $tDODataInputWahCode   = "";
                                $tDODataInputWahName   = "";
                                if ($tDORoute  == "docDOEventAdd") {
                                    $tDODataInputWahCode    = $this->session->userdata('tSesUsrWahCode');
                                    $tDODataInputWahName    = $this->session->userdata('tSesUsrWahName');
                                    $tDisabledWah = '';
                                } else {
                                    $tDODataInputWahCode    = $tDOWahCode;
                                    $tDODataInputWahName    = $tDOWahName;
                                    $tDisabledWah = 'disabled';
                                }
                                ?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmWah'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetDOFrmWahCode" name="oetDOFrmWahCode" maxlength="5" value="<?php echo $tDODataInputWahCode; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDOFrmWahName" name="oetDOFrmWahName" value="<?php echo $tDODataInputWahName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterWah'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmWah'); ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtDOBrowseWahouse" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmWah'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetDOFrmWahCode" name="oetDOFrmWahCode" maxlength="5" value="<?php echo $tDOWahCode; ?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDOFrmWahName" name="oetDOFrmWahName" value="<?php echo $tDOWahName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterWah'); ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmWah'); ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtDOBrowseWahouse" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel จากผู้จำหน่าย / ผู้ขาย -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDOConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFormSpl'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDODataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDODataConditionDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelSplName'); ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetDOSplName" name="oetDOSplName" value="<?php echo $tDOSplName; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelSplName'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayType'); ?></label>
                                    <select class="selectpicker xWDODisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmDOTypePayment" name="ocmDOTypePayment" maxlength="1">
                                        <option value="1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCash'); ?></option>
                                        <option value="2"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCredit'); ?></option>
                                    </select>
                                </div>
                                <div class="form-group xCNPanel_CreditTerm">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelCredit'); ?></label>
                                    <input type="text" class="form-control xControlForm text-right" id="oetDOFrmSplInfoCrTerm" name="oetDOFrmSplInfoCrTerm" maxlength="20" value="<?php echo $tDOSplCrTerm; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelCredit'); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmSplInfoVatInOrEx'); ?></label>
                                    <select class="selectpicker xWDODisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmDOFrmSplInfoVatInOrEx" name="ocmDOFrmSplInfoVatInOrEx" maxlength="1">
                                        <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmSplInfoVatInclusive'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmSplInfoVatExclusive'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Panel การขนส่ง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPISupplierInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelTSTitle'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPIDataSupplierInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPIDataSupplierInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div id="odvRowPanelSplInfo" class="row" style="max-height:350px;overflow-x:auto">
                            <div class="col-xs-12 col-sm-12 col-col-md-12 col-lg-12">
                                <!-- ชื่อผู้ติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelTSName'); ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetDOFrmSplInfoCtrName" name="oetDOFrmSplInfoCtrName" maxlength="20" value="<?php echo $tDOSplCtrName; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelTSName'); ?>">
                                </div>

                                <!-- เลขอ้างอิงใบขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelRefTSDocCode'); ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetDOFrmSplInfoRefTnfID" name="oetDOFrmSplInfoRefTnfID" maxlength="20" value="<?php echo $tDOSplRefTnfID; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelRefTSDocCode'); ?>">
                                </div>
                                <!-- วันที่ในการขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelRefTSDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDOFrmSplInfoTnfDate" name="oetDOFrmSplInfoTnfDate" value="<?php echo $dDOSplTnfDate; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPHDRefTSCode'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDOTransDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- อ้างอิงเลขที่ยานพาหนะขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelRefVehicleCode'); ?></label>
                                    <input type="text" class="form-control xControlForm" id="oetDOFrmSplInfoRefVehID" name="oetDOFrmSplInfoRefVehID" maxlength="20" value="<?php echo $tDOSplRefVehID; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelRefVehicleCode'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDOInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDODataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDODataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbDOFrmInfoOthStaDocAct" name="ocbDOFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nPOStaDocAct == '1' || empty($nPOStaDocAct)) ? 'checked' : ''; ?> checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef'); ?></label>
                                    <select class="selectpicker xWDODisabledOnApv form-control xControlForm" id="ocmDOFrmInfoOthRef" name="ocmDOFrmInfoOthRef" maxlength="1">
                                        <option value="0" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef0'); ?></option>
                                        <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef1'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef2'); ?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthDocPrint'); ?></label>
                                    <input type="text" class="form-control xControlForm text-right" id="ocmDOFrmInfoOthDocPrint" name="ocmDOFrmInfoOthDocPrint" value="<?php echo $tDOFrmDocPrint; ?>" readonly>
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xControlForm selectpicker xWDODisabledOnApv" id="ocmDOFrmInfoOthReAddPdt" name="ocmDOFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                                    <textarea class="form-control xControlRmk xWConditionSearchPdt" id="otaDOFrmInfoOthRmk" name="otaDOFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?php echo $tDOFrmRmk ?></textarea>
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
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvDOShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvDOShowDataTable',
                        ptBchCode       : $('#oetDOFrmBchCode').val(),
                        ptDocNo         : $('#oetDODocNo').val(),
                        ptDocKey        : 'TAPTDoHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : '<?= $nStaUploadFile ?>',
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdDOStaApv').val(),
                        ptStaDoc        : $('#ohdDOStaDoc').val()
                        //JSxSoCallBackUploadFile -- ดูข้อมูลไฟล์แนบ
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvDODataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <!-- Tab -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;" id="oliDOContentProduct">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDOContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;" id="oliDOContentHDRef">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDOContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="odvDOContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-10">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelSplName'); ?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xControlForm xCNHide" id="oetDOFrmSplCode" name="oetDOFrmSplCode" value="<?php echo $tDOSplCode; ?>">
                                                        <input type="text" class="form-control xControlForm" id="oetDOFrmSplName" name="oetDOFrmSplName" value="<?php echo $tDOSplName; ?>" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelSplName') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterSplCode'); ?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="obtDOBrowseSupplier" type="button" class="btn xCNBtnBrowseAddOn">
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
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right">

                                                <div class="row">
                                                    <!--ตัวเลือก-->
                                                    <div id="odvDOMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                        <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                            <?php echo language('common/main/main', 'tCMNOption') ?>
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li id="oliDOBtnDeleteMulti">
                                                                <a data-toggle="modal" data-target="#odvDOModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <!--ค้นหาจากบาร์โค๊ด-->
                                                <div class="form-group" style="width: 85%;">
                                                    <input type="text" class="form-control xControlForm" id="oetDOInsertBarcode" autocomplete="off" name="oetDOInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                                </div>

                                                <!--เพิ่มสินค้าแบบปกติ-->
                                                <div class="form-group">
                                                    <div style="position: absolute;right: 15px;top:-5px;">
                                                        <button type="button" id="obtDODocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row p-t-10" id="odvDODataPdtTableDTTemp">
                                        </div>
                                        <!--ส่วนสรุปท้ายบิล-->
                                        <div class="odvRowDataEndOfBill" id="odvRowDataEndOfBill">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'จำนวนรับรวมทั้งสิ้น'); ?></label>
                                                    <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?= language('document/purchaseorder/purchaseorder', 'tPOItems'); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>

                                    <!-- อ้างอิง -->
                                    <div id="odvDOContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="xCNHideWhenCancelOrApprove" style="margin-top:10px;">
                                                    <button type="button" id="obtDOAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>
                                            <div id="odvDOTableHDRef"></div>
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

<!-- =================================================================== View Modal Shipping Purchase Invoice  =================================================================== -->
<div id="odvDOBrowseShipAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnDOShipAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliDOEditShipAddress">&nbsp;<?php echo language('document/deliveryorder/deliveryorder', 'tDOShipChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdDOShipAddSeqNo" class="form-control xControlForm">
                                <?php $tDOFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tDOFormatAddressType) && $tDOFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddAddV1No"><?php echo @$tDOShipAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1Soi"><?php echo @$tDOShipAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1Village"><?php echo @$tDOShipAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1Road"><?php echo @$tDOShipAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1SubDist"><?php echo @$tDOShipAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1DstCode"><?php echo @$tDOShipAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1PvnCode"><?php echo @$tDOShipAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOShipAddV1PostCode"><?php echo @$tDOShipAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV2Desc1') ?></label><br>
                                                <label id="ospDOShipAddV2Desc1"><?php echo @$tDOShipAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOShipADDV2Desc2') ?></label><br>
                                                <label id="ospDOShipAddV2Desc2"><?php echo @$tDOShipAddV2Desc2; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ================================================================== View Modal TexAddress Purchase Invoice  ================================================================== -->
<div id="odvDOBrowseTexAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnDOTexAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliDOEditTexAddress">&nbsp;<?php echo language('document/deliveryorder/deliveryorder', 'tDOTexChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdDOTexAddSeqNo" class="form-control xControlForm">
                                <?php $tDOFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tDOFormatAddressType) && $tDOFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddAddV1No"><?php echo @$tDOTexAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1Soi"><?php echo @$tDOTexAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1Village"><?php echo @$tDOTexAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1Road"><?php echo @$tDOTexAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1SubDist"><?php echo @$tDOTexAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1DstCode"><?php echo @$tDOTexAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1PvnCode"><?php echo @$tDOTexAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDOTexAddV1PostCode"><?php echo @$tDOTexAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV2Desc1') ?></label><br>
                                                <label id="ospDOTexAddV2Desc1"><?php echo @$tDOTexAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTexADDV2Desc2') ?></label><br>
                                                <label id="ospDOTexAddV2Desc2"><?php echo @$tDOTexAddV2Desc2; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvDOModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxDOApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvDOPopupCancel">
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
                <button onclick="JSnDOCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvDOOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="modal-body" id="odvDOModalBodyAdvTable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button id="obtDOSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvDOModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmDODocNoDelete" name="ohdConfirmDODocNoDelete">
                <input type="hidden" id="ohdConfirmDOSeqNoDelete" name="ohdConfirmDOSeqNoDelete">
                <input type="hidden" id="ohdConfirmDOPdtCodeDelete" name="ohdConfirmDOPdtCodeDelete">
                <input type="hidden" id="ohdConfirmDOPunCodeDelete" name="ohdConfirmDOPunCodeDelete">

            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvDOModalPleseselectSPL" class="modal fade">
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
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvDOModalPDTNotFound" class="modal fade">
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
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvDOModalPDTMoreOne" class="modal fade">
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
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal เปลี่ยนสาขา ======================================================================== -->
<div id="odvDOModalChangeData" class="modal fade" style="z-index: 1400;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ohdDOTypeChange" name="ohdDOTypeChange">
                <p><span id="ospDOTxtWarningAlert"></span></p>
                <?php //echo language('document/deliveryorder/deliveryorder', 'tDOBchNotFound') ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" id="obtDOChangeData" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvDOModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1400;">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                
                <label class="xCNTextModalHeard" id="olbTextModalHead"><?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบสั่งซื้อ') ?></label>

            </div>

            <div class="modal-body">
                <div class="row" id="odvDOFromRefIntDoc">

                </div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->
<div id="odvDOModalWahNoFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/deliveryorder/deliveryorder', 'tDOWahNotFound') ?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsSelectWah') ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>

        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<div id="odvDOModalPONoFound" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/deliveryorder/deliveryorder','tDOPONotFound')?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/deliveryorder/deliveryorder','tDOPleaseSelectPO')?></p>
            </div>

            <div class="modal-footer">
                <button type="button" id="obtConfirmPo" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvDOModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmDOFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetDORefDocNoOld" name="oetDORefDocNoOld">
                    <input type="text" class="form-control xCNHide" id="oetDORefDoc" name="oetDORefDoc">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbDORefType" name="ocbDORefType">
                                    <option value="1" selected><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbDORefDoc" name="ocbDORefDoc">
                                    <option value="1" ><?php echo language('common/main/main', 'ใบสั่งซื้อ'); ?></option>
                                    <option value="2" selected ><?php echo language('common/main/main', 'ใบขาย'); ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- อ้างอิงภายใน -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetDORefIntDoc" name="oetDORefIntDoc" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtDOBrowseRefDocInt" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDORefDocNo" name="oetDORefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="hidden" id="ohdDORefDocTime" name="ohdDORefDocTime" value=''>
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetDORefDocDate" name="oetDORefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtDORefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDORefKey" name="oetDORefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtDOConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jDeliveryOrderAdd.php'); ?>
<?php include("script/jDeliveryOrderPdtAdvTableData.php"); ?>
<script type="text/javascript">
    //บังคับให้เลือกลูกค้า
    function JSxFocusInputCustomer() {
        $('#oetDOFrmCstName').focus();
    }

    //ค้นหาสินค้าใน temp
    function JSvDOCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbDODocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxNotFoundClose() {
        $('#oetDOInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetDOFrmSplName').val() != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                // JCNxOpenLoading();
                $('#oetDOInsertBarcode').attr('readonly', true);
                JCNSearchBarcodePdt(tValue);
                $('#oetDOInsertBarcode').val('');
            }
        } else {
            $('#odvDOModalPleseselectSPL').modal('show');
            $('#oetDOInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {
        var tWhereCondition = "";
        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType: ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc: "",
                SPL: $("#oetDOFrmSplCode").val(),
                BCH: $("#oetDOFrmBchCode").val(),
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode: $('#ohdDOUsrCode').val(),
                tInpLangEdit: $('#ohdDOLangEdit').val(),
                tInpSesUsrLevel: $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                tWhere: [" AND FTPdtStkControl = 1 "],
                tTextScan: ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $('#oetDOInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetDOInsertBarcode').attr('readonly', false);
                    $('#odvDOModalPDTNotFound').modal('show');
                    $('#oetDOInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvDOModalPDTMoreOne').modal('show');
                        $('#odvDOModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
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
                            $('#odvDOModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvDOModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvDOAddPdtIntoDocDTTemp(tJSON); //Client
                            FSvDOAddBarcodeIntoDocDTTemp(tJSON);
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
                        FSvDOAddPdtIntoDocDTTemp(aNewReturn); //Client
                        // JCNxCloseLoading();
                        // $('#oetDOInsertBarcode').attr('readonly',false);
                        // $('#oetDOInsertBarcode').val('');
                        FSvDOAddBarcodeIntoDocDTTemp(aNewReturn); //Server
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
            $("#odvDOModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvDOAddPdtIntoDocDTTemp(tJSON);
                FSvDOAddBarcodeIntoDocDTTemp(tJSON);
            });
        } else {
            $('#oetDOInsertBarcode').attr('readonly', false);
            $('#oetDOInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvDOAddBarcodeIntoDocDTTemp(ptPdtData) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var ptXthDocNoSend = "";
            if ($("#ohdDORoute").val() == "docDOEventEdit") {
                ptXthDocNoSend = $("#oetDODocNo").val();
            }
            var tDOOptionAddPdt = $('#ocmDOFrmInfoOthReAddPdt').val();
            var nKey = parseInt($('#otbDODocPdtAdvTableList tr:last').attr('data-seqno'));

            $('#oetDOInsertBarcode').attr('readonly', false);
            $('#oetDOInsertBarcode').val('');

            $.ajax({
                type: "POST",
                url: "docDOAddPdtIntoDTDocTemp",
                data: {
                    'tSelectBCH': $('#oetDOFrmBchCode').val(),
                    'tDODocNo': ptXthDocNoSend,
                    'tDOOptionAddPdt': tDOOptionAddPdt,
                    'tDOPdtData': ptPdtData,
                    'ohdSesSessionID': $('#ohdSesSessionID').val(),
                    'ohdDOUsrCode': $('#ohdDOUsrCode').val(),
                    'ohdDOLangEdit': $('#ohdDOLangEdit').val(),
                    'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                    'ohdDOSesUsrBchCode': $('#ohdDOSesUsrBchCode').val(),
                    'tSeqNo': nKey,
                    'nVatRate': $('#ohdDOFrmSplVatRate').val(),
                    'nVatCode': $('#ohdDOFrmSplVatCode').val()
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // JSvDOLoadPdtDataTableHtml();
                    var aResult = JSON.parse(oResult);

                    if (aResult['nStaEvent'] == 1) {
                        JCNxCloseLoading();
                        // $('#oetDOInsertBarcode').attr('readonly',false);
                        // $('#oetDOInsertBarcode').val('');
                        // if(tDOOptionAddPdt=='1'){
                        //     JSvDOCallEndOfBill();
                        // }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvDOAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        } else {
            JCNxphowMsgSessionExpired();
        }
    }

    // เวลา Click Tab1 ให้ Button Hide
    $('#oliDOContentProduct').click(function() {
        $('#odvDOContentHDRef').hide();
        $('#odvDOContentProduct').show();
    });

    // เวลา Click Tab2 ให้ Button Show
    $('#oliDOContentHDRef').click(function() {
        $('#odvDOContentProduct').hide();
        $('#odvDOContentHDRef').show();
        $('#obtDOAddDocRef').show();
    });

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxDOEventCheckShowHDDocRef(){
        var tDORefType = $('#ocbDORefType').val();
        if( tDORefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }
</script>
