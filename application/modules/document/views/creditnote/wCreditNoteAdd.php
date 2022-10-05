<?php
if($aResult['rtCode'] == "1"){
    // ข้อมูลหลัก
    $tDocNo                 = $aResult['raItems']['FTXphDocNo'];
    $tDocType               = $aResult['raItems']['FNXphDocType'];
    $tDocDate               = date('Y-m-d', strtotime($aResult['raItems']['FDXphDocDate']));
    $tDocTime               = date('H:i:s', strtotime($aResult['raItems']['FDXphDocDate']));
    $tCreateBy              = $aResult['raItems']['FTCreateBy'];
    $tStaDoc                = $aResult['raItems']['FTXphStaDoc'];
    $nStaDocAct             = $aResult['raItems']['FNXphStaDocAct'];
    $tStaApv                = $aResult['raItems']['FTXphStaApv'];
    $tApvCode               = $aResult['raItems']['FTXphApvCode'];
    $tStaPrcStk             = $aResult['raItems']['FTXphStaPrcStk'];
    $tStaDelMQ              = $aResult['raItems']['FTXphStaDelMQ'];
    $tBchCode               = $aResult['raItems']['FTBchCode'];
    $tBchName               = $aResult['raItems']['FTBchName'];
    $tSplCode               = $aResult['raItems']['FTSplCode'];
    $tSplName               = $aResult['raItems']['FTSplName'];
    $tSplVatCode            = $aSpl['FTVatCode'];

    // ข้อมูลอ้างอิง
    $tRefPICode             = $aResult['raItems']['FTXphRefInt'];
    $tRefIntDate            = $aResult['raItems']['FDXphRefIntDate'];
    $tRefExt                = $aResult['raItems']['FTXphRefExt'];
    $tRefExtDate            = $aResult['raItems']['FDXphRefExtDate'];

    // เงื่อนไข
    $tMchCode               = "";
    $tMchName               = "";
    $tShpCode               = "";
    $tShpName               = "";
    $tPosCode               = "";
    $tPosName               = "";
    $tWahCode               = "";
    $tWahName               = "";

    // ผู้จำหน่าย
    $tXphVATInOrEx          = $aResult['raItems']['FTXphVATInOrEx'];
    $tXphCshOrCrd           = $aResult['raItems']['FTXphCshOrCrd'];
    $tHDPcSplXphDstPaid     = $aHDSpl['FTXphDstPaid'];
    $tHDPcSplXphCrTerm      = $aHDSpl['FNXphCrTerm'];
    $tHDPcSplXphDueDate     = $aHDSpl['FDXphDueDate'];
    $tHDPcSplXphBillDue     = $aHDSpl['FDXphBillDue'];
    $tHDPcSplXphTnfDate     = $aHDSpl['FDXphTnfDate'];
    $tHDPcSplXphCtrName     = $aHDSpl['FTXphCtrName'];
    $tHDPcSplXphRefTnfID    = $aHDSpl['FTXphRefTnfID'];
    $tHDPcSplXphRefVehID    = $aHDSpl['FTXphRefVehID'];
    $tHDPcSplXphRefInvNo    = $aHDSpl['FTXphRefInvNo'];
    $tHDPcSplXphQtyAndTypeUnit = $aHDSpl['FTXphQtyAndTypeUnit'];

    // อื่นๆ
    $tStaDocAct             = $aResult['raItems']['FNXphStaDocAct'];
    $tStaRef                = $aResult['raItems']['FNXphStaRef'];
    $tDocPrint              = $aResult['raItems']['FNXphDocPrint'];
    $tXphRmk                = $aResult['raItems']['FTXphRmk'];

    // Event Control
    $tRoute                 = "creditNoteEventEdit";
    $tUserBchCode           = $aResult['raItems']['FTBchCode'];
    $tUserBchName           = $aResult['raItems']['FTBchName'];
    $tUserWahCode           = $aResult['raItems']['FTWahCode'];
    $tUserWahName           = $aResult['raItems']['FTWahName'];
    $tUserShpCode           = $aResult['raItems']['FTShpCode'];
    $tUserShpName           = $aResult['raItems']['FTShpName'];
    $tPCVatRateBySPL        = '';
    $nStaUploadFile         = 2;
    $tPCVatCodeBySPL        = '';
}else{
    // ข้อมูลหลัก
    $tDocNo                 = "";
    $tDocType               = $nDocType;
    $tDocDate               = date('Y-m-d');
    $tDocTime               = date('H:i:s');
    $tCreateBy              = $this->session->userdata('tSesUsrUsername');
    $tStaDoc                = "";
    $nStaDocAct             = "";
    $tStaApv                = "";
    $tApvCode               = "";
    $tStaPrcStk             = "";
    $tStaDelMQ              = "";
    $tBchCode               = "";
    $tBchName               = "";
    $tSplCode               = "";
    $tSplName               = "";
    $tSplVatCode            = "";

    // ข้อมูลอ้างอิง
    $tRefPICode             = "";
    $tRefIntDate            = "";
    $tRefExt                = "";
    $tRefExtDate            = "";

    // เงื่อนไข
    $tMchCode               = "";
    $tMchName               = "";
    $tShpCode               = "";
    $tShpName               = "";
    $tPosCode               = "";
    $tPosName               = "";
    $tWahCode               = "";
    $tWahName               = "";

    // ผู้จำหน่าย
    $tXphVATInOrEx          = "";
    $tXphCshOrCrd           = "";
    $tHDPcSplXphDstPaid     = "";
    $tHDPcSplXphCrTerm      = "";
    $tHDPcSplXphDueDate     = "";
    $tHDPcSplXphBillDue     = "";
    $tHDPcSplXphTnfDate     = "";
    $tHDPcSplXphCtrName     = "";
    $tHDPcSplXphRefTnfID    = "";
    $tHDPcSplXphRefVehID    = "";
    $tHDPcSplXphRefInvNo    = "";
    $tHDPcSplXphQtyAndTypeUnit = "";

    // อื่นๆ
    $tStaDocAct             = "";
    $tStaRef                = "";
    $tDocPrint              = "";
    $tXphRmk                = "";

    // Event Control
    $tRoute                 = "creditNoteEventAdd";
    $tUserBchCode           = $this->session->userdata("tSesUsrBchCodeDefault");
    $tUserBchName           = $this->session->userdata("tSesUsrBchNameDefault");
    $nStaUploadFile         = 1;

    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserMchCode = '';
        $tUserMchName = '';
    }
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserShpCode = '';
        $tUserShpName = '';
    }
    if($this->session->userdata('tSesUsrLevel') == 'HQ' || $this->session->userdata('tSesUsrLevel') == 'BCH' || $this->session->userdata('tSesUsrLevel') == 'SHP'){
        $tUserPosCode = '';
        $tUserPosName = '';
    }
    $tUserWahCode = $this->session->userdata("tSesUsrWahCode");
    $tUserWahName = $this->session->userdata("tSesUsrWahName");
    $tPCVatRateBySPL = '';
    $tPCVatCodeBySPL = '';
}

if($aUserCreated["rtCode"] == "1"){
    $tUserCreatedCode = $aUserCreated["raItems"]["rtUsrCode"];
    $tUserCreatedName = $aUserCreated["raItems"]["rtUsrName"];
}else{
    $tUserCreatedCode = "";
    $tUserCreatedName = "";
}

if($aUserApv["rtCode"] == "1"){
    $tUserApvCode = $aUserApv["raItems"]["rtUsrCode"];
    $tUserApvName = $aUserApv["raItems"]["rtUsrName"];
}else{
    $tUserApvCode = "";
    $tUserApvName = "";
}

$bIsDocTypeHavePdt = $tDocType == '6' ? true : false;
$bIsDocTypeNonePdt = $tDocType == '7' ? true : false;
$bIsApvOrCancel = !empty($tStaApv) || $tStaDoc == 3 ? true : false;
?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCreditNote">
    <input type="hidden" id="ohdCreditNoteStaApv" name="ohdCreditNoteStaApv" value="<?php echo $tStaApv; ?>">
    <input type="hidden" id="ohdCreditNoteStaDoc" name="ohdCreditNoteStaDoc" value="<?php echo $tStaDoc; ?>">
    <input type="hidden" id="ohdCreditNoteStaDelMQ" name="ohdCreditNoteStaDelMQ" value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden" id="ohdCreditNoteAjhStaPrcStk" name="ohdCreditNoteStaPrcStk" value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden" id="ohdCreditNoteDptCode" name="ohdCreditNoteDptCode" value="<?php echo $tUserDptCode; ?>">
    <input type="hidden" id="ohdCreditNoteUsrCode" name="ohdCreditNoteUsrCode" value="<?php echo $tUserCode; ?>">
    <input type="hidden" id="ohdCreditNoteUsrApvCode" name="ohdCreditNoteUsrApvCode" value="<?php echo $tUserApvCode; ?>">
    <input type="hidden" id="ohdCreditNoteDocType" name="ohdCreditNoteDocType" value="<?php echo $tDocType; ?>">
    <input type="hidden" id="ohdRoute" name="ohdRoute" value="<?php echo $tRoute; ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdCDNUsrCode" name="ohdCDNUsrCode" value="<?php echo $this->session->userdata('tSesUsername') ?>">
    <input type="hidden" id="ohdCDNLangEdit" name="ohdCDNLangEdit" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <button style="display:none" type="submit" id="obtSubmitCreditNote" onclick="JSnAddEditCreditNote();"></button>
    <div class="row">
        <div class="col-md-3">
            <!-- ข้อมูลหลัก -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocLabel'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvCreditNoteSubHeadDocPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCreditNoteSubHeadDocPanel" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="form-group xCNHide" style="text-align: right;">
                            <label class="xCNTitleFrom "><?php echo language('document/creditnote/creditnote', 'tCreditNoteApproved'); ?></label>
                        </div>
                        <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/creditnote/creditnote', 'tCreditNoteDocNo'); ?></label>

                        <div class="form-group" id="odvCreditNoteAutoGenDocNoForm">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbCreditNoteAutoGenCode" name="ocbCreditNoteAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('document/creditnote/creditnote', 'tCreditNoteAutoGenCode'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="odvCreditNoteDocNoForm">
                            <div class="validate-input">
                                <input
                                    type="text"
                                    class="form-control input100 xCNGenarateCodeTextInputValidate"
                                    id="oetCreditNoteDocNo"
                                    aria-invalid="false"
                                    name="oetCreditNoteDocNo"
                                    data-is-created="<?php echo $tDocNo; ?>"
                                    data-validate-required="<?= language('document/creditnote/creditnote', 'tCreditNotePlsEnterOrRunDocNo') ?>"
                                    data-validate-dublicateCode="<?= language('document/creditnote/creditnote', 'tCreditNoteMsgDuplicate') ?>"
                                    placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteDocNo') ?>"
                                    value="<?php echo $tDocNo; ?>"
                                    data-validate="Plese Generate Code">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/creditnote/creditnote', 'tCreditNoteDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteXphDocDate"
                                    name="oetCreditNoteXphDocDate"
                                    value="<?php echo $tDocDate; ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteXphDocDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/creditnote/creditnote', 'tCreditNoteDocTime'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNTimePicker"
                                    id="oetCreditNoteDocTime"
                                    name="oetCreditNoteDocTime"
                                    value="<?php echo $tDocTime; ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocTime'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteDocTime" type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteDocTime').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteCreateBy'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetCreditNoteCreateBy" name="oetCreditNoteCreateBy" value="<?php echo $tUserCode ?>">
                                <label><?php echo $tUserName; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteTBStaDoc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/creditnote/creditnote', 'tCreditNoteStaDoc' . $tStaDoc); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteTBStaApv'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/creditnote/creditnote', 'tCreditNoteStaApv' . $tStaApv); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteTBStaPrc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/creditnote/creditnote', 'tCreditNoteStaPrcStk' . $tStaApv); ?></label>
                            </div>
                        </div>

                        <?php if($tDocNo != '') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteApvBy'); ?></label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="text" class="xCNHide" id="oetCreditNoteAjhApvCode" name="oetCreditNoteAjhApvCode" maxlength="20" value="<?php echo $tApvCode?>">
                                    <label><?php echo $tUserApvName != '' ? $tUserApvName : language('document/creditnote/creditnote', 'tCreditNoteStaDoc'); ?></label>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <!-- ข้อมูลหลัก -->

            <!-- ข้อมูลอ้างอิง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadRefInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tCreditNoteReference'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCreditNoteRefInfoPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCreditNoteRefInfoPanel" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- อ้างอิงเอกสาร ใบรับของ/ใบซื้อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteRefRectPurchDoc'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetCreditNoteRefPICode" name="oetCreditNoteRefPICode" maxlength="5" value="<?php echo $tRefPICode; ?>" >
                                <input class="form-control xWPointerEventNone" type="text" id="oetCreditNoteRefPIName" name="oetCreditNoteRefPIName" value="<?php echo $tRefPICode; ?>" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteRefRectPurchDoc'); ?> " readonly>
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtCreditNoteBrowseRefPI" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- อ้างอิงเอกสาร ใบรับของ/ใบซื้อ -->

                        <?php if(false) { ?>
                        <!-- อ้างอิงเอกสารภายใน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteRefInDoc'); ?></label>
                            <input class="form-control" type="text" id="oetCreditNoteXphRefInt" name="oetCreditNoteXphRefInt" value="<?php ?>" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- อ้างอิงเอกสารภายใน -->
                        <?php } ?>

                        <!-- วันที่อ้างอิงเอกสารภายใน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteRefInDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteXphRefIntDate"
                                    name="oetCreditNoteXphRefIntDate"
                                    value="<?php echo $tRefIntDate; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteRefInDocDate'); ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteRefIntDate" type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteXphRefIntDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่อ้างอิงเอกสารภายใน -->

                        <!-- อ้างอิงเอกสารภายนอก -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteRefExDoc'); ?></label>
                            <input class="form-control" type="text" id="oetCreditNoteXphRefExt" name="oetCreditNoteXphRefExt" value="<?php echo $tRefExt; ?>" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteRefExDoc'); ?>" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- อ้างอิงเอกสารภายนอก -->

                        <!-- วันที่อ้างอิงเอกสารภายนอก -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteRefExDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteXphRefExtDate"
                                    name="oetCreditNoteXphRefExtDate"
                                    value="<?php echo $tRefExtDate; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteRefExDocDate'); ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteXphRefExtDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่อ้างอิงเอกสารภายนอก -->

                    </div>
                </div>
            </div>
            <!-- ข้อมูลอ้างอิง -->

            <!-- เงื่อนไข -->
            <div id="odvCreditNoteCondition" class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocCondition'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvCreditNoteConditionPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCreditNoteConditionPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <?php
                            if($tRoute == "creditNoteEventAdd"){
                                $tUserBchCode       = $this->session->userdata('tSesUsrBchCodeDefault');
                                $tUserBchName       = $this->session->userdata('tSesUsrBchNameDefault');
                                $tDisabledBch       = '';
                            }else{
                                $tUserBchCode       = $tUserBchCode;
                                $tUserBchName       = $tUserBchName;
                                $tDisabledBch       = 'disabled';
                            }
                        ?>

                        <script>
                            var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                            if( tUsrLevel != "HQ" ){
                                var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount")?>';
                                if(tBchCount < 2){
                                    $('#obtCreditNoteBrowseBch').attr('disabled',true);
                                }
                            }
                        </script>

                        <!-- สาขา -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteBranch'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdCreditNoteBchCode" name="ohdCreditNoteBchCode" value="<?=$tUserBchCode; ?>">
                                <input class="form-control xCNHide" id="oetCreditNoteBchCode" name="oetCreditNoteBchCode" maxlength="5" value="<?=$tUserBchCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetCreditNoteBchName"
                                    name="oetCreditNoteBchName"
                                    value="<?php echo $tUserBchName; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteBranch'); ?>"
                                    readonly>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledBch; ?> >
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- กลุ่มธุรกิจ --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteMerChant');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetCreditNoteMchCode" name="oetCreditNoteMchCode" maxlength="5" value="<?php echo $tUserMchCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCreditNoteMchName" name="oetCreditNoteMchName" value="<?php echo $tUserMchName; ?>" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteMerChant');?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteBrowseMch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- กลุ่มธุรกิจ -->

                        <!-- ร้านค้า --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteShop'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdCreditNoteWahCodeInShp" name="ohdCreditNoteWahCodeInShp" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdCreditNoteWahNameInShp" name="ohdCreditNoteWahNameInShp" value="<?php echo $tUserWahName; ?>">
                                <input class="form-control xCNHide" id="oetCreditNoteShpCode" name="oetCreditNoteShpCode" maxlength="5" value="<?php echo $tUserShpCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCreditNoteShpName" name="oetCreditNoteShpName" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteShop'); ?>" value="<?php echo $tUserShpName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteBrowseShp" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- ร้านค้า -->

                        <!-- เครื่องจุดขาย --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNotePos'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetCreditNotePosCode" name="oetCreditNotePosCode" maxlength="5" value="<?php // echo $tUserPosCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCreditNotePosName" name="oetCreditNotePosName" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNotePos'); ?>" value="<?php // echo $tUserPosName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteBrowsePos" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- เครื่องจุดขาย -->

                        <!-- คลัง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteWarehouse'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdCreditNoteWahCode" name="ohdCreditNoteWahCode" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdCreditNoteWahName" name="ohdCreditNoteWahName" value="<?php echo $tUserWahName; ?>">
                                <input type="text" class="input100 xCNHide" id="oetCreditNoteWahCode" name="oetCreditNoteWahCode" maxlength="5" value="<?php echo $tUserWahCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCreditNoteWahName" name="oetCreditNoteWahName" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteWarehouse'); ?>" value="<?php echo $tUserWahName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtCreditNoteBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- คลัง -->

                    </div>
                </div>
            </div>
            <!-- เงื่อนไข -->

            <!-- ผู้จำหน่าย -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <input type="hidden" id="ohdCreditNoteSplVatCode" name="ohdCreditNoteSplVatCode" value="<?php echo $tSplVatCode; ?>">
                <div id="odvHeadSpl" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/creditnote/creditnote', 'tCreditNoteSpl'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvCreditNoteSplPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCreditNoteSplPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <!-- ประเภทภาษี -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteVATInOrEx'); ?></label>
                            <select class="selectpicker form-control" id="ocmCreditNoteXphVATInOrEx" name="ocmCreditNoteXphVATInOrEx" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?> onchange="JSbCreditNoteChangeSplVatType()">
                                <option value="1" <?php echo $tXphVATInOrEx == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatIn'); ?></option>
                                <option value="2" <?php echo $tXphVATInOrEx == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatEx'); ?></option>
                            </select>
                        </div>
                        <input type="hidden" id="ohdCNFrmSplVatRate" name="ohdCNFrmSplVatRate" value="<?=$tPCVatRateBySPL?>">
                        <input type="hidden" id="ohdCNFrmSplVatCode" name="ohdCNFrmSplVatCode" value="<?=$tPCVatCodeBySPL?>">
                        <!-- ประเภทภาษี -->

                        <!-- ประเภทชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNotePaymentType'); ?></label>
                            <select class="selectpicker form-control" id="ocmCreditNoteXphCshOrCrd" name="ocmCreditNoteXphCshOrCrd" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tXphCshOrCrd == '1' ? 'selected' : ''; ?>><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCash'); ?></option>
                                <option value="2" <?php echo $tXphCshOrCrd == '2' ? 'selected' : ''; ?>><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCredit'); ?></option>
                            </select>
                        </div>
                        <!-- ประเภทชำระเงิน -->

                        <!-- การชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNotePaymentPoint'); ?></label>
                            <select class="selectpicker form-control" id="ocmCreditNoteHDPcSplXphDstPaid" name="ocmCreditNoteHDPcSplXphDstPaid" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tHDPcSplXphDstPaid == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocPaid'); ?></option>
                                <option value="2" <?php echo $tHDPcSplXphDstPaid == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocDst'); ?></option>
                            </select>
                        </div>
                        <!-- การชำระเงิน -->

                        <!-- ระยะเครดิต -->
                        <div class="form-group xCNPanel_CreditTerm">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                            <input
                                class="form-control text-right"
                                type="text"
                                id="oetCreditNoteHDPcSplXphCrTerm"
                                name="oetCreditNoteHDPcSplXphCrTerm"
                                value="<?php echo $tHDPcSplXphCrTerm; ?>"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ระยะเครดิต -->

                        <!-- วงเงินเครดิต -->
                        <div class="form-group xCNHide">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteCreditLimit'); ?></label>
                            <input
                                class="form-control text-right"
                                type="text"
                                id="oetCreditNoteHDPcSplCreditLimit"
                                name="oetCreditNoteHDPcSplCreditLimit"
                                value="<?php echo $tHDPcSplXphCrTerm; ?>"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteCreditLimit'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- วงเงินเครดิต -->

                        <!-- วันครบกำหนดการชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNotePaymentDueDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteHDPcSplXphDueDate"
                                    name="oetCreditNoteHDPcSplXphDueDate"
                                    value="<?php echo $tHDPcSplXphDueDate; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNotePaymentDueDate'); ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteHDPcSplXphDueDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันครบกำหนดการชำระเงิน -->

                        <!-- วันวางบิล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteBillingDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteHDPcSplXphBillDue"
                                    name="oetCreditNoteHDPcSplXphBillDue"
                                    value="<?php echo $tHDPcSplXphBillDue; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteBillingDate'); ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteHDPcSplXphBillDue').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันวางบิล -->

                        <!-- วันที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteTnfDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetCreditNoteHDPcSplXphTnfDate"
                                    name="oetCreditNoteHDPcSplXphTnfDate"
                                    value="<?php echo $tHDPcSplXphTnfDate; ?>"
                                    placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteTnfDate'); ?>"
                                    data-validate-required="<?php echo language('document/creditnote/creditnote', 'tCreditNotePlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetCreditNoteHDPcSplXphTnfDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่ขนส่ง -->

                        <!-- ชื่อผู้ติดต่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteCtrName'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetCreditNoteHDPcSplXphCtrName"
                                name="oetCreditNoteHDPcSplXphCtrName"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteCtrName'); ?>"
                                value="<?php echo $tHDPcSplXphCtrName; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ชื่อผู้ติดต่อ -->

                        <!-- เลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteTransportNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetCreditNoteHDPcSplXphRefTnfID"
                                name="oetCreditNoteHDPcSplXphRefTnfID"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteTransportNumber'); ?>"
                                value="<?php echo $tHDPcSplXphRefTnfID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่ขนส่ง -->

                        <!-- อ้างอิงเลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteReferenceTransportationNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetCreditNoteHDPcSplXphRefVehID"
                                name="oetCreditNoteHDPcSplXphRefVehID"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteReferenceTransportationNumber'); ?>"
                                value="<?php echo $tHDPcSplXphRefVehID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- อ้างอิงเลขที่ขนส่ง -->

                        <!-- เลขที่บัญชีราคาสินค้า -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteProductPriceAccountNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetCreditNoteHDPcSplXphRefInvNo"
                                name="oetCreditNoteHDPcSplXphRefInvNo"
                                value="<?php echo $tHDPcSplXphRefInvNo; ?>"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteProductPriceAccountNumber'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่บัญชีราคาสินค้า -->

                        <!-- จำนวนและลักษณะหีบห่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteQuantityAndPackaging'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetCreditNoteHDPcSplXphQtyAndTypeUnit"
                                name="oetCreditNoteHDPcSplXphQtyAndTypeUnit"
                                value="<?php echo $tHDPcSplXphQtyAndTypeUnit; ?>"
                                placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditNoteQuantityAndPackaging'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- จำนวนและลักษณะหีบห่อ -->
                    </div>
                </div>
            </div>
            <!-- ผู้จำหน่าย -->

            <!-- อื่นๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadAnother" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/creditnote/creditnote', 'tCreditNoteOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvCreditNoteOtherPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCreditNoteOtherPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- สถานะความเคลื่อนไหว -->
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input
                                    type="checkbox"
                                    value="1"
                                    id="ocbCreditNoteXphStaDocAct"
                                    name="ocbCreditNoteXphStaDocAct"
                                    maxlength="1" <?php echo ($nStaDocAct == '1' || empty($nStaDocAct)) ? 'checked' : ''; ?>
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span>&nbsp;</span>
                                <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tCreditNoteStaDocAct'); ?></span>
                            </label>
                        </div>
                        <!-- สถานะความเคลื่อนไหว -->

                        <!-- สถานะอ้างอิง -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/creditnote/creditnote', 'tCreditNoteStaRef'); ?></label>
                            <select class="selectpicker form-control" id="ocmCreditNoteXphStaRef" name="ocmCreditNoteXphStaRef" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="0" <?php echo $tStaRef == '0' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocNeverReference'); ?></option>
                                <option value="1" <?php echo $tStaRef == '1' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocSomeReference'); ?></option>
                                <option value="2" <?php echo $tStaRef == '2' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocAllReference'); ?></option>
                            </select>
                        </div>
                        <!-- สถานะอ้างอิง -->

                        <!-- จำนวนครั้งที่พิมพ์ -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/creditnote/creditnote', 'tCreditNoteCountDocPrint'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="ocmCreditNoteXphDocPrint"
                                name="ocmCreditNoteXphDocPrint"
                                value="<?php echo $tDocPrint; ?>"
                                readonly="true"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- จำนวนครั้งที่พิมพ์ -->

                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/creditnote/creditnote', 'tCreditNoteAddPdtAgain'); ?></label>
                            <select class="selectpicker form-control" id="ocmCreditNoteOptionAddPdt" name="ocmCreditNoteOptionAddPdt" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1"><?php echo language('document/creditnote/creditnote','tCreditAddamounttolist');?></option>
                                <option value="2"><?php echo language('document/creditnote/creditnote','tCreditAddnewitem');?></option>
                            </select>
                        </div>
                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->

                        <!-- หมายเหตุ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tCreditNoteNote'); ?></label>
                            <textarea
                                class="form-control xCNInputWithoutSpc"
                                id="otaCreditNoteXphRmk"
                                name="otaCreditNoteXphRmk"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>><?php echo $tXphRmk; ?></textarea>
                        </div>
                        <!-- หมายเหตุ -->

                    </div>
                </div>
            </div>
            <!-- อื่นๆ -->

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvCDNReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvCDNDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCDNDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvCDNShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oCDNCallDataTableFile = {
                        ptElementID     : 'odvCDNShowDataTable',
                        ptBchCode       : $('#ohdCreditNoteBchCode').val(),
                        ptDocNo         : $('#oetCreditNoteDocNo').val(),
                        ptDocKey        : 'TAPTPcHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdCreditNoteStaApv').val(),
                        ptStaDoc        : $('#ohdCreditNoteStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oCDNCallDataTableFile);
                </script>
            </div>

        </div>

        <!-- Right Panel -->
        <div class="col-md-9" id="odvCreditNoteRightPanal">
            <!-- Pdt -->
            <div class="panel panel-default" style="margin-bottom: 25px;position: relative;min-height: 200px;">
                <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                    <div class="panel-body xCNPDModlue">

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <!-- เลือกผู้จำหน่าย -->
                                <label class="xCNLabelFrm"><?php echo language('document/purchaseinvoice/purchaseinvoice','tPITBSpl');?></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetCreditNoteSplCode" name="oetCreditNoteSplCode" maxlength="5" value="<?php echo $tSplCode; ?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetCreditNoteSplName" name="oetCreditNoteSplName" value="<?php echo $tSplName; ?>" placeholder="<?php echo language('document/creditnote/creditnote', 'tCreditChooseSuplyer'); ?>" readonly>
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtCreditNoteBrowseSpl" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เลือกผู้จำหน่าย -->
                            </div>
                        </div>

                        <?php if($bIsDocTypeHavePdt) { // ใบลดหนี้แบบมีสินค้า ?>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-5 no-padding">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvCDNCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvCDNCSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="btn-group xCNDropDrownGroup right">
                                        <?php if(!$bIsApvOrCancel) { ?>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn xCNBTNMngTable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <?php echo language('common/main/main', 'tCMNOption') ?><span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliCreditNoteBtnDeleteAll" class="disabled">
                                                        <a data-toggle="modal" data-target="#odvModalDelPdtCreditNote"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if(!$bIsApvOrCancel) { ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div style="width: 85%;">
                                            <input type="text" class="form-control xControlForm" id="oetCDNInsertBarcode" autocomplete="off" name="oetCDNInsertBarcode" maxlength="50" value="" onkeyup="javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this)" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                        </div>
                                        <div style="position: absolute;right: 15px;top:-5px;">
                                            <button
                                                id="obtCreditNoteDocBrowsePdt"
                                                class="xCNBTNPrimeryPlus xCNDocBrowsePdt"
                                                onclick="JCNvCreditNoteBrowsePdt()"
                                                type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        <?php }; ?>

                        <div id="odvCreditNotePdtTablePanal"></div>
                        <?php include('wCreditNoteEndOfBill.php'); ?>
                    </div>
                </div>
            </div>
            <!-- Pdt -->
        </div>
        <!-- Right Panel -->
    </div>
</form>

<div class="modal fade xCNModalApprove" id="odvCreditNotePopupApv">
    <div class="modal-dialog">
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
                <button onclick="JSnCreditNoteApprove(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvShowOrderColumn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button type="button" class="btn btn-primary" onclick="JSxSaveColumnShow()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalEditCreditNoteDisHD">
    <div class="modal-dialog xCNDisModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="display:inline-block"><label class="xCNLabelFrm"><?php echo language('common/main/main', 'tCreditNoteDisEndOfBill'); ?></label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tCreditNoteDisType'); ?></label>
                            <select class="selectpicker form-control" id="ostXthHDDisChgText" name="ostXthHDDisChgText">
                                <option value="3"><?php echo language('document/creditnote/creditnote', 'tDisChgTxt3') ?></option>
                                <option value="4"><?php echo language('document/creditnote/creditnote', 'tDisChgTxt4') ?></option>
                                <option value="1"><?php echo language('document/creditnote/creditnote', 'tDisChgTxt1') ?></option>
                                <option value="2"><?php echo language('document/creditnote/creditnote', 'tDisChgTxt2') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tCreditNoteValue'); ?></label>
                        <input type="text" class="form-control xCNInputNumericWithDecimal" id="oetXddHDDis" name="oetXddHDDis" maxlength="11" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary xCNBtnAddDis" onclick="FSvCreditNoteAddHDDis()">
                                <label class="xCNLabelAddDis">+</label>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="odvHDDisListPanal"></div>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvCreditNotePopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/document/document', 'tDocDocumentCancel') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/document/document', 'tDocCancelText1') ?></p>
                <p><strong><?php echo language('document/document/document', 'tDocCancelText2') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnCreditNoteCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvCreditNotePopupChangeSplConfirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/document/document', 'ยืนยันการเปลี่ยนผู้จำหน่าย') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/document/document', 'การเปลี่ยนผู้จำหน่ายระบบจะทำการล้างข้อมูลสินค้าเดิมทั้งหมด') ?></p>
                <p><strong><?php echo language('document/document/document', 'คุณต้องการเปลี่ยนผู้จำหน่ายหรือไม่') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxCreditNoteClearTemp()" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvPiModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchaseorder/purchaseorder','อ้างอิงเอกสารใบซื้อสินค้า')?></label>

            </div>

            <div class="modal-body">
                <div class="row" id="odvPiFromRefIntDoc">

                </div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvCDNModalPDTMoreOne" class="modal fade">
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

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvCDNModalPDTNotFound" class="modal fade">
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
<input type="hidden" id="oetCreditNoteBchCode" name="oetCreditNoteBchCode" value="<?php echo $tUserBchCode;?>">

<?php include('ref_pi/wCreditNotePIModal.php'); ?>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jCreditNoteAdd.php'); ?>
<?php include('dis_chg/wCreditNoteDisChgModal.php'); ?>

<script>
    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetCreditNoteSplName').val() != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                JCNxOpenLoading();
                JCNSearchBarcodePdt(tValue);
                $('#oetCDNInsertBarcode').val('');
            }
        } else {
            var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
            FSvCMNSetMsgWarningDialog(tWarningMessage);
            $('#oetCDNInsertBarcode').val('');
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
                SPL: $("#oetCreditNoteSplCode").val(),
                BCH: $("#oetCreditNoteBchCode").val(),
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode: $('#ohdCDNUsrCode').val(),
                tInpLangEdit: $('#ohdCDNLangEdit').val(),
                tInpSesUsrLevel: $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                Where: [tWhereCondition],
                tTextScan: ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetCDNInsertBarcode').attr('readonly', false);
                    $('#odvCDNModalPDTNotFound').modal('show');
                    $('#oetCDNInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvCDNModalPDTMoreOne').modal('show');
                        $('#odvCDNModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
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
                            $('#odvCDNModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvCDNModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvPDTAddPdtIntoTableDT(tJSON); //Client
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {

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
                        FSvPDTAddPdtIntoTableDT(aNewReturn); //Client
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR,textStatus,errorThrown);
                JCNSearchBarcodePdt(ptTextScan);
            }
        });
    }

    //ค้นหาสินค้าใน temp
    function JSvCDNCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbCreditNoteDOCPdtTable tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
</script>
