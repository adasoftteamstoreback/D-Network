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
    $tHDPcSPLCrLimit        = $aSpl['FCSplCrLimit'];
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
    $tRoute                 = "docPNEventEdit";
    $tUserBchCode           = $aResult['raItems']['FTBchCode'];
    $tUserBchName           = $aResult['raItems']['FTBchName'];
    $tUserWahCode           = $aResult['raItems']['FTWahCode'];
    $tUserWahName           = $aResult['raItems']['FTWahName'];
    $tUserShpCode           = $aResult['raItems']['FTShpCode'];
    $tUserShpName           = $aResult['raItems']['FTShpName'];
    $tPCVatRateBySPL        = '';
    $tPCVatCodeBySPL        = '';
    $nStaUploadFile         = 2;

    $tChkType = substr($tRefPICode,0,2);
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
    $tHDPcSPLCrLimit        = 0;
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
    $tRoute                 = "docPNEventAdd";
    $tUserBchCode           = $this->session->userdata("tSesUsrBchCodeDefault");
    $tUserBchName           = $this->session->userdata("tSesUsrBchNameDefault");

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
    $nStaUploadFile         = 1;
    $tChkType = '';
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
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddPN">
    <input type="hidden" id="ohdPNStaApv" name="ohdPNStaApv" value="<?php echo $tStaApv; ?>">
    <input type="hidden" id="ohdPNStaDoc" name="ohdPNStaDoc" value="<?php echo $tStaDoc; ?>">
    <input type="hidden" id="ohdPNStaDelMQ" name="ohdPNStaDelMQ" value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden" id="ohdPNAjhStaPrcStk" name="ohdPNStaPrcStk" value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden" id="ohdPNDptCode" name="ohdPNDptCode" value="<?php echo $tUserDptCode; ?>">
    <input type="hidden" id="ohdPNUsrCode" name="ohdPNUsrCode" value="<?php echo $tUserCode; ?>">
    <input type="hidden" id="ohdPNUsrApvCode" name="ohdPNUsrApvCode" value="<?php echo $tUserApvCode; ?>">
    <input type="hidden" id="ohdPNDocType" name="ohdPNDocType" value="<?php echo $tDocType; ?>">
    <button style="display:none" type="submit" id="obtSubmitPN" onclick="JSnAddEditPN();"></button>
    <div class="row">
        <div class="col-md-3">
            <!-- ข้อมูลหลัก -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocLabel'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPNSubHeadDocPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPNSubHeadDocPanel" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="form-group xCNHide" style="text-align: right;">
                            <label class="xCNTitleFrom "><?php echo language('document/purchasereturn/purchasereturn', 'tPNApproved'); ?></label>
                        </div>
                        <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasereturn/purchasereturn', 'tPNDocNo'); ?></label>

                        <div class="form-group" id="odvPNAutoGenDocNoForm">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbPNAutoGenCode" name="ocbPNAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('document/purchasereturn/purchasereturn', 'tPNAutoGenCode'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="odvPNDocNoForm">
                            <div class="validate-input">
                                <input
                                    type="text"
                                    class="form-control input100 xCNGenarateCodeTextInputValidate"
                                    id="oetPNDocNo"
                                    aria-invalid="false"
                                    name="oetPNDocNo"
                                    data-is-created="<?php echo $tDocNo; ?>"
                                    data-validate-required="<?= language('document/purchasereturn/purchasereturn', 'tPNPlsEnterOrRunDocNo') ?>"
                                    data-validate-dublicateCode="<?= language('document/purchasereturn/purchasereturn', 'tPNMsgDuplicate') ?>"
                                    placeholder="<?= language('document/purchasereturn/purchasereturn', 'tPNDocNo') ?>"
                                    value="<?php echo $tDocNo; ?>"
                                    data-validate="Plese Generate Code">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/purchasereturn/purchasereturn', 'tPNDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNXphDocDate"
                                    name="oetPNXphDocDate"
                                    value="<?php echo $tDocDate; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNXphDocDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/purchasereturn/purchasereturn', 'tPNDocTime'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNTimePicker"
                                    id="oetPNDocTime"
                                    name="oetPNDocTime"
                                    value="<?php echo $tDocTime; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocTime'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button id="obtPNDocTime" type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNDocTime').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNCreateBy'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetPNCreateBy" name="oetPNCreateBy" value="<?php echo $tUserCode ?>">
                                <label><?php echo $tUserName; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNTBStaDoc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/purchasereturn/purchasereturn', 'tPNStaDoc' . $tStaDoc); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNTBStaApv'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/purchasereturn/purchasereturn', 'tPNStaApv' . $tStaApv); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNTBStaPrc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/purchasereturn/purchasereturn', 'tPNStaPrcStk' . $tStaApv); ?></label>
                            </div>
                        </div>

                        <?php if($tDocNo != '') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNApvBy'); ?></label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="text" class="xCNHide" id="oetPNAjhApvCode" name="oetPNAjhApvCode" maxlength="20" value="<?php echo $tApvCode?>">
                                    <label><?php echo $tUserApvName != '' ? $tUserApvName : language('document/purchasereturn/purchasereturn', 'tPNStaDoc'); ?></label>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <!-- ข้อมูลอ้างอิง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadRefInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tPNReference'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPNRefInfoPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPNRefInfoPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- อ้างอิงเอกสาร ใบรับของ/ใบซื้อ -->
                        <!-- Select Refin -->
                        <?php
                            $tDoSelect = '';
                            $tLanguage = language('document/purchasereturn/purchasereturn', 'tPNRefRectPurchDoc');
                            if($tChkType == 'DO'){
                                $tDoSelect = 'selected';
                                $tLanguage = language('document/purchasereturn/purchasereturn', 'tPNRefRectDODoc');
                            }
                        ?>
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                            <select class="selectpicker xWDPSDisabledOnApv form-control xControlForm" id="ocmPNSelectBrowse" name="ocmPNSelectBrowse" maxlength="1">
                                <option value="0"><?php echo language('document/bookingorder/bookingorder', 'tPNRefIV'); ?></option>
                                <option value="1" <?php echo $tDoSelect ?>><?php echo language('document/bookingorder/bookingorder', 'tPNRefDO'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="xCNLabelFrm" id="lbDocrefType"><?php echo $tLanguage; ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetPNRefPICodeOld" name="oetPNRefPICodeOld" value="<?php echo $tRefPICode; ?>">
                                <input type="text" class="input100 xCNHide" id="oetPNRefPICode" name="oetPNRefPICode" value="<?php echo $tRefPICode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetPNRefPIName" name="oetPNRefPIName" placeholder="<?php echo $tLanguage; ?>" value="<?php echo $tRefPICode; ?>" readonly>
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtPNBrowseRefIntDoc"  type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <?php if(false) { ?>
                            <!-- อ้างอิงเอกสารภายใน -->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNRefInDoc'); ?></label>
                                <input class="form-control" type="text" id="oetPNXphRefInt" name="oetPNXphRefInt" value="<?php ?>" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                            </div>
                        <?php } ?>

                        <!-- วันที่อ้างอิงเอกสารภายใน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNRefInDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNXphRefIntDate"
                                    name="oetPNXphRefIntDate"
                                    value="<?php echo $tRefIntDate; ?>"
                                    placeholder="YYYY-MM-DD"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button id="obtPNRefIntDate" type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNXphRefIntDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- อ้างอิงเอกสารภายนอก -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNRefExDoc'); ?></label>
                            <input class="form-control" type="text" id="oetPNXphRefExt" name="oetPNXphRefExt" placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNRefExDoc'); ?>" value="<?php echo $tRefExt; ?>" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>

                        <!-- วันที่อ้างอิงเอกสารภายนอก -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNRefExDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNXphRefExtDate"
                                    name="oetPNXphRefExtDate"
                                    placeholder="YYYY-MM-DD"
                                    value="<?php echo $tRefExtDate; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNXphRefExtDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่อ้างอิงเอกสารภายนอก -->

                    </div>
                </div>
            </div>

            <!-- เงื่อนไข -->
            <div id="odvPNCondition" class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocCondition'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPNConditionPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPNConditionPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <?php
                            if($tRoute == "docPNEventAdd"){
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
                                    $('#obtPNBrowseBch').attr('disabled',true);
                                }
                            }
                        </script>

                        <!-- สาขา -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNBranch'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdPNBchCode" name="ohdPNBchCode" value="<?=$tUserBchCode; ?>">
                                <input class="form-control xCNHide" id="oetPNBchCode" name="oetPNBchCode" maxlength="5" value="<?=$tUserBchCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPNBchName"
                                    name="oetPNBchName"
                                    value="<?php echo $tUserBchName; ?>"
                                    readonly>
                                <span class="input-group-btn">
                                    <button id="obtPNBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledBch; ?> >
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- กลุ่มธุรกิจ --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNMerChant');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetPNMchCode" name="oetPNMchCode" maxlength="5" value="<?php echo $tUserMchCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetPNMchName" name="oetPNMchName" value="<?php echo $tUserMchName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtPNBrowseMch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- กลุ่มธุรกิจ -->

                        <!-- ร้านค้า --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNShop'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdPNWahCodeInShp" name="ohdPNWahCodeInShp" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdPNWahNameInShp" name="ohdPNWahNameInShp" value="<?php echo $tUserWahName; ?>">
                                <input class="form-control xCNHide" id="oetPNShpCode" name="oetPNShpCode" maxlength="5" value="<?php echo $tUserShpCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetPNShpName" name="oetPNShpName" value="<?php echo $tUserShpName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtPNBrowseShp" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- ร้านค้า -->

                        <!-- เครื่องจุดขาย --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNPos'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetPNPosCode" name="oetPNPosCode" maxlength="5" value="<?php // echo $tUserPosCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetPNPosName" name="oetPNPosName" value="<?php // echo $tUserPosName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtPNBrowsePos" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- เครื่องจุดขาย -->

                        <!-- คลัง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNWarehouse'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdPNWahCode" name="ohdPNWahCode" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdPNWahName" name="ohdPNWahName" value="<?php echo $tUserWahName; ?>">
                                <input type="text" class="input100 xCNHide" id="oetPNWahCode" name="oetPNWahCode" maxlength="5" value="<?php echo $tUserWahCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetPNWahName" name="oetPNWahName" value="<?php echo $tUserWahName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtPNBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- คลัง -->

                    </div>
                </div>
            </div>

            <!-- ผู้จำหน่าย -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <input type="hidden" id="ohdPNSplVatCode" name="ohdPNSplVatCode" value="<?php echo $tSplVatCode; ?>">
                <div id="odvHeadSpl" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasereturn/purchasereturn', 'tPNSpl'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPNSplPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPNSplPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <!-- ประเภทภาษี -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNVATInOrEx'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNXphVATInOrEx" name="ocmPNXphVATInOrEx" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?> onchange="JSbPNChangeSplVatType()">
                                <option value="1" <?php echo $tXphVATInOrEx == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatIn'); ?></option>
                                <option value="2" <?php echo $tXphVATInOrEx == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatEx'); ?></option>
                            </select>
                        </div>
                        <input type="hidden" id="ohdCNFrmSplVatRate" name="ohdCNFrmSplVatRate" value="<?=$tPCVatRateBySPL?>">
                        <input type="hidden" id="ohdCNFrmSplVatCode" name="ohdCNFrmSplVatCode" value="<?=$tPCVatCodeBySPL?>">
                        <!-- ประเภทภาษี -->

                        <!-- ประเภทชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNPaymentType'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNXphCshOrCrd" name="ocmPNXphCshOrCrd" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tXphCshOrCrd == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocCash'); ?></option>
                                <option value="2" <?php echo $tXphCshOrCrd == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocCredit'); ?></option>
                            </select>
                        </div>
                        <!-- ประเภทชำระเงิน -->

                        <!-- การชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNPaymentPoint'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNHDPcSplXphDstPaid" name="ocmPNHDPcSplXphDstPaid" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tHDPcSplXphDstPaid == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocPaid'); ?></option>
                                <option value="2" <?php echo $tHDPcSplXphDstPaid == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocDst'); ?></option>
                            </select>
                        </div>
                        <!-- การชำระเงิน -->

                        <!-- ระยะเครดิต -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNCreditTerm'); ?></label>
                            <input
                                class="form-control text-right xCNInputNumericWithoutDecimal"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNCreditTerm'); ?>"
                                type="text"
                                maxlength="10"
                                id="oetPNHDPcSplXphCrTerm"
                                name="oetPNHDPcSplXphCrTerm"
                                value="<?php echo $tHDPcSplXphCrTerm; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ระยะเครดิต -->

                        <!-- วงเงินเครดิต -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNCreditLimit'); ?></label>
                            <input
                                class="form-control text-right"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNCreditLimit'); ?>"
                                type="text"
                                id="oetPNHDPcSplCreditLimit"
                                readonly
                                name="oetPNHDPcSplCreditLimit"
                                value="<?php echo number_format($tHDPcSPLCrLimit,2); ?>">
                        </div>
                        <!-- วงเงินเครดิต -->

                        <!-- วันครบกำหนดการชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNPaymentDueDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="YYYY-MM-DD"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNHDPcSplXphDueDate"
                                    name="oetPNHDPcSplXphDueDate"
                                    value="<?php echo $tHDPcSplXphDueDate; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNHDPcSplXphDueDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันครบกำหนดการชำระเงิน -->

                        <!-- วันวางบิล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNBillingDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="YYYY-MM-DD"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNHDPcSplXphBillDue"
                                    name="oetPNHDPcSplXphBillDue"
                                    value="<?php echo $tHDPcSplXphBillDue; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNHDPcSplXphBillDue').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันวางบิล -->

                        <!-- วันที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNTnfDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="YYYY-MM-DD"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetPNHDPcSplXphTnfDate"
                                    name="oetPNHDPcSplXphTnfDate"
                                    value="<?php echo $tHDPcSplXphTnfDate; ?>"
                                    data-validate-required="<?php echo language('document/purchasereturn/purchasereturn', 'tPNPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetPNHDPcSplXphTnfDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่ขนส่ง -->

                        <!-- ชื่อผู้ติดต่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNCtrName'); ?></label>
                            <input
                                class="form-control"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNCtrName'); ?>"
                                type="text"
                                id="oetPNHDPcSplXphCtrName"
                                name="oetPNHDPcSplXphCtrName"
                                value="<?php echo $tHDPcSplXphCtrName; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ชื่อผู้ติดต่อ -->

                        <!-- เลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNTransportNumber'); ?></label>
                            <input
                                class="form-control"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNTransportNumber'); ?>"
                                type="text"
                                id="oetPNHDPcSplXphRefTnfID"
                                name="oetPNHDPcSplXphRefTnfID"
                                value="<?php echo $tHDPcSplXphRefTnfID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่ขนส่ง -->

                        <!-- อ้างอิงเลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNReferenceTransportationNumber'); ?></label>
                            <input
                                class="form-control"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNReferenceTransportationNumber'); ?>"
                                type="text"
                                id="oetPNHDPcSplXphRefVehID"
                                name="oetPNHDPcSplXphRefVehID"
                                value="<?php echo $tHDPcSplXphRefVehID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- อ้างอิงเลขที่ขนส่ง -->

                        <!-- เลขที่บัญชีราคาสินค้า -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNProductPriceAccountNumber'); ?></label>
                            <input
                                class="form-control"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNProductPriceAccountNumber'); ?>"
                                type="text"
                                id="oetPNHDPcSplXphRefInvNo"
                                name="oetPNHDPcSplXphRefInvNo"
                                value="<?php echo $tHDPcSplXphRefInvNo; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่บัญชีราคาสินค้า -->

                        <!-- จำนวนและลักษณะหีบห่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNQuantityAndPackaging'); ?></label>
                            <input
                                class="form-control"
                                placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tPNQuantityAndPackaging'); ?>"
                                type="text"
                                id="oetPNHDPcSplXphQtyAndTypeUnit"
                                name="oetPNHDPcSplXphQtyAndTypeUnit"
                                value="<?php echo $tHDPcSplXphQtyAndTypeUnit; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- จำนวนและลักษณะหีบห่อ -->
                    </div>
                </div>
            </div>

            <!-- อื่นๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadAnother" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasereturn/purchasereturn', 'tPNOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPNOtherPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPNOtherPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- สถานะความเคลื่อนไหว -->
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input
                                    type="checkbox"
                                    value="1"
                                    id="ocbPNXphStaDocAct"
                                    name="ocbPNXphStaDocAct"
                                    maxlength="1" <?php echo ($nStaDocAct == '1' || empty($nStaDocAct)) ? 'checked' : ''; ?>
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span>&nbsp;</span>
                                <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPNStaDocAct'); ?></span>
                            </label>
                        </div>
                        <!-- สถานะความเคลื่อนไหว -->

                        <!-- สถานะอ้างอิง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNStaRef'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNXphStaRef" name="ocmPNXphStaRef" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="0" <?php echo $tStaRef == '0' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocNeverReference'); ?></option>
                                <option value="1" <?php echo $tStaRef == '1' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocSomeReference'); ?></option>
                                <option value="2" <?php echo $tStaRef == '2' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocAllReference'); ?></option>
                            </select>
                        </div>
                        <!-- สถานะอ้างอิง -->

                        <!-- จำนวนครั้งที่พิมพ์ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNCountDocPrint'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="ocmPNXphDocPrint"
                                name="ocmPNXphDocPrint"
                                value="<?php echo $tDocPrint; ?>"
                                readonly="true"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- จำนวนครั้งที่พิมพ์ -->

                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNAddPdtAgain'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNOptionAddPdt" name="ocmPNOptionAddPdt" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1"><?php echo language('document/purchasereturn/purchasereturn','tCreditAddamounttolist');?></option>
                                <option value="2"><?php echo language('document/purchasereturn/purchasereturn','tCreditAddnewitem');?></option>
                            </select>
                        </div>
                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->

                        <!-- หมายเหตุ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'tPNNote'); ?></label>
                            <textarea
                                class="form-control xCNInputWithoutSpc"
                                id="otaPNXphRmk"
                                name="otaPNXphRmk"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>><?php echo $tXphRmk; ?></textarea>
                        </div>
                        <!-- หมายเหตุ -->

                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvPNReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPNDataFile" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>
                    <div id="odvPNDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPNShowDataTable">


                                </div>
                            </div>
                        </div>
                    </div>
                    <script>


                        var oPNCallDataTableFile = {
                            ptElementID     : 'odvPNShowDataTable',
                            ptBchCode       : $('#oetPNBchCode').val(),
                            ptDocNo         : $('#oetPNDocNo').val(),
                            ptDocKey        : 'TAPTPnHD',
                            ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                            pnEvent         : <?= $nStaUploadFile ?>,
                            ptCallBackFunct : '',
                            ptStaApv        : $('#ohdPNStaApv').val(),
                            ptStaDoc        : $('#ohdPNStaDoc').val()
                        }
                        JCNxUPFCallDataTable(oPNCallDataTableFile);
                    </script>
                </div>


            </div>

        <!-- Right Panel -->
        <div class="col-md-9" id="odvPNRightPanal">
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
                                        <input type="text" class="input100 xCNHide" id="oetPNSplCode" name="oetPNSplCode" maxlength="5" value="<?php echo $tSplCode; ?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetPNSplName" name="oetPNSplName" value="<?php echo $tSplName; ?>" placeholder="<?php echo language('document/purchasereturn/purchasereturn', 'tCreditChooseSuplyer'); ?>" readonly>
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtPNBrowseSpl" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
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

                                <!--ค้นหา-->
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvPNDOCSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                            <span class="input-group-btn">
                                                <button class="btn xCNBtnSearch" type="button" onclick="JSvPNDOCSearchPdtHTML()">
                                                    <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 text-right">
                                    <div class="btn-group xCNDropDrownGroup right">
                                        <?php if(!$bIsApvOrCancel) { ?>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn xCNBTNMngTable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <?php echo language('common/main/main', 'tCMNOption') ?><span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliPNBtnDeleteAll" class="disabled">
                                                        <a data-toggle="modal" data-target="#odvModalDelPdtPN"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php if(!$bIsApvOrCancel) { ?>
                                    <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                        <div class="form-group">
                                            <div style="position: absolute;right: 15px;top:-5px;">
                                                <button
                                                    id="obtPNDocBrowsePdt"
                                                    class="xCNBTNPrimeryPlus xCNDocBrowsePdt"
                                                    onclick="JCNvPNBrowsePdt()"
                                                    type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }; ?>

                        <div id="odvPNPdtTablePanal"></div>
                        <?php include('wPurchasereturnEndOfBill.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade xCNModalApprove" id="odvPNPopupApv">
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
                <button onclick="JSnPNApprove(true)" type="button" class="btn xCNBTNPrimery">
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

<div class="modal fade" id="odvModalEditPNDisHD">
    <div class="modal-dialog xCNDisModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="display:inline-block"><label class="xCNLabelFrm"><?php echo language('common/main/main', 'tPNDisEndOfBill'); ?></label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tPNDisType'); ?></label>
                            <select class="selectpicker form-control" id="ostXthHDDisChgText" name="ostXthHDDisChgText">
                                <option value="3"><?php echo language('document/purchasereturn/purchasereturn', 'tDisChgTxt3') ?></option>
                                <option value="4"><?php echo language('document/purchasereturn/purchasereturn', 'tDisChgTxt4') ?></option>
                                <option value="1"><?php echo language('document/purchasereturn/purchasereturn', 'tDisChgTxt1') ?></option>
                                <option value="2"><?php echo language('document/purchasereturn/purchasereturn', 'tDisChgTxt2') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tPNValue'); ?></label>
                        <input type="text" class="form-control xCNInputNumericWithDecimal" id="oetXddHDDis" name="oetXddHDDis" maxlength="11" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary xCNBtnAddDis" onclick="FSvPNAddHDDis()">
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

<div class="modal fade" id="odvPNPopupCancel">
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
                <button onclick="JSnPNCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvPNPopupChangeSplConfirm">
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
                <button onclick="JSxPNClearTemp()" type="button" class="btn xCNBTNPrimery">
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
<div id="odvPNModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard" id='labelmodalheadpn'><?php echo language('document/purchasereturn/purchasereturn', 'tPNRefRectPurchDoc'); ?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvPNFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="oetPNBchCode" name="oetPNBchCode" value="<?php echo $tUserBchCode;?>">

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jPurchasereturnAdd.php'); ?>
<?php include('dis_chg/wPurchasereturnDisChgModal.php'); ?>
