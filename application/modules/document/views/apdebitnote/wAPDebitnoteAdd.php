<?php 
    if($aResult['rtCode'] == "1"){
        $tAPDAgnCode                = $aResult['raItems']['FTAgnCode'];
        $tAPDAgnName                = $aResult['raItems']['FTAgnName'];

        // ข้อมูลหลัก
        $tDocNo                     = $aResult['raItems']['FTXphDocNo'];
        $tDocType                   = $aResult['raItems']['FNXphDocType'];
        $tDocDate                   = date('Y-m-d', strtotime($aResult['raItems']['FDXphDocDate']));
        $tDocTime                   = date('H:i:s', strtotime($aResult['raItems']['FDXphDocDate']));
        $tCreateBy                  = $aResult['raItems']['FTCreateBy'];
        $tStaDoc                    = $aResult['raItems']['FTXphStaDoc'];
        $nStaDocAct                 = $aResult['raItems']['FNXphStaDocAct'];
        $tStaApv                    = $aResult['raItems']['FTXphStaApv'];
        $tApvCode                   = $aResult['raItems']['FTXphApvCode'];
        $tStaPrcStk                 = $aResult['raItems']['FTXphStaPrcStk'];
        $tStaDelMQ                  = $aResult['raItems']['FTXphStaDelMQ'];
        $tBchCode                   = $aResult['raItems']['FTBchCode'];
        $tBchName                   = $aResult['raItems']['FTBchName'];
        $tSplCode                   = $aResult['raItems']['FTSplCode'];
        $tSplName                   = $aResult['raItems']['FTSplName'];
        $tSplVatCode                = $aSpl['FTVatCode'];
        // ข้อมูลอ้างอิง
        $tRefPICode                 = $aResult['raItems']['FTXphRefInt'];
        $tRefIntDate                = $aResult['raItems']['FDXphRefIntDate'];
        $tRefExt                    = $aResult['raItems']['FTXphRefExt'];
        $tRefExtDate                = $aResult['raItems']['FDXphRefExtDate'];
        // เงื่อนไข
        $tMchCode                   = "";
        $tMchName                   = "";
        $tShpCode                   = "";
        $tShpName                   = "";
        $tPosCode                   = "";
        $tPosName                   = "";
        $tWahCode                   = "";
        $tWahName                   = "";
        // ผู้จำหน่าย
        $tXphVATInOrEx              = $aResult['raItems']['FTXphVATInOrEx'];
        $tXphCshOrCrd               = $aResult['raItems']['FTXphCshOrCrd'];
        $tHDPcSplXphDstPaid         = $aHDSpl['FTXphDstPaid'];
        $tHDPcSplXphCrTerm          = $aHDSpl['FNXphCrTerm'];
        $tHDPcSplXphDueDate         = $aHDSpl['FDXphDueDate'];
        $tHDPcSplXphBillDue         = $aHDSpl['FDXphBillDue'];
        $tHDPcSplXphTnfDate         = $aHDSpl['FDXphTnfDate'];
        $tHDPcSplXphCtrName         = $aHDSpl['FTXphCtrName'];
        $tHDPcSplXphRefTnfID        = $aHDSpl['FTXphRefTnfID'];
        $tHDPcSplXphRefVehID        = $aHDSpl['FTXphRefVehID'];
        $tHDPcSplXphRefInvNo        = $aHDSpl['FTXphRefInvNo'];
        $tHDPcSplXphQtyAndTypeUnit  = $aHDSpl['FTXphQtyAndTypeUnit'];
        // อื่นๆ
        $tStaDocAct                 = $aResult['raItems']['FNXphStaDocAct'];
        $tStaRef                    = $aResult['raItems']['FNXphStaRef'];
        $tDocPrint                  = $aResult['raItems']['FNXphDocPrint'];
        $tXphRmk                    = $aResult['raItems']['FTXphRmk'];

        // Event Control
        $tRoute                     = "docAPDebitnoteEventEdit";
        $tUserBchCode               = $aResult['raItems']['FTBchCode'];
        $tUserBchName               = $aResult['raItems']['FTBchName'];
        $tUserWahCode               = $aResult['raItems']['FTWahCode'];
        $tUserWahName               = $aResult['raItems']['FTWahName'];
        $tUserShpCode               = $aResult['raItems']['FTShpCode'];
        $tUserShpName               = $aResult['raItems']['FTShpName'];
        $tPCVatRateBySPL            = '';
        $nStaUploadFile             = 2;
        $tPCVatCodeBySPL            = '';
        
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
        $tRoute                 = "docAPDebitnoteEventAdd";
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
        $tUserCreatedCode   = $aUserCreated["raItems"]["rtUsrCode"];
        $tUserCreatedName   = $aUserCreated["raItems"]["rtUsrName"];
    }else{
        $tUserCreatedCode   = "";
        $tUserCreatedName   = "";
    }
    
    if($aUserApv["rtCode"] == "1"){
        $tUserApvCode   = $aUserApv["raItems"]["rtUsrCode"];
        $tUserApvName   = $aUserApv["raItems"]["rtUsrName"];
    }else{
        $tUserApvCode   = "";
        $tUserApvName   = "";
    }
    
    $bIsDocTypeHavePdt  = $tDocType == '6' ? true : false;
    $bIsDocTypeNonePdt  = $tDocType == '7' ? true : false;
    $bIsApvOrCancel     = !empty($tStaApv) || $tStaDoc == 3 ? true : false;
?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddAPD">
    <input type="hidden"    id="ohdAPDStaApv"       name="ohdAPDStaApv"     value="<?php echo $tStaApv; ?>">
    <input type="hidden"    id="ohdAPDStaDoc"       name="ohdAPDStaDoc"     value="<?php echo $tStaDoc; ?>">
    <input type="hidden"    id="ohdAPDStaDelMQ"     name="ohdAPDStaDelMQ"   value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden"    id="ohdAPDAjhStaPrcStk" name="ohdAPDStaPrcStk"  value="<?php echo $tStaPrcStk; ?>">
    <input type="hidden"    id="ohdAPDDptCode"      name="ohdAPDDptCode"    value="<?php echo $tUserDptCode; ?>">
    <input type="hidden"    id="ohdAPDUsrCode"      name="ohdAPDUsrCode"    value="<?php echo $tUserCode; ?>">
    <input type="hidden"    id="ohdAPDUsrApvCode"   name="ohdAPDUsrApvCode" value="<?php echo $tUserApvCode; ?>">
    <input type="hidden"    id="ohdAPDDocType"      name="ohdAPDDocType"    value="<?php echo $tDocType; ?>">
    <input type="hidden"    id="ohdRoute"           name="ohdRoute"         value="<?php echo $tRoute; ?>">
    <input type="hidden"    id="ohdSesSessionID"    name="ohdSesSessionID"  value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden"    id="ohdUsrCode"         name="ohdUsrCode"       value="<?php echo $this->session->userdata('tSesUsername') ?>">
    <input type="hidden"    id="ohdLangEdit"        name="ohdLangEdit"      value="<?php echo $this->session->userdata("tLangEdit"); ?>">
    <input type="hidden"    id="ohdSesUsrLevel"     name="ohdSesUsrLevel"   value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden"    id="ohdSesUsrBchCom"    name="ohdSesUsrBchCom"  value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden"    id="ohdSesUsrBchCode"   name="ohdSesUsrBchCode" value="<?php echo $this->session->userdata("tSesUsrBchCode"); ?>"> 
    <input type="hidden"    id="ohdOptScanSku"      name="ohdOptScanSku"    value="<?php echo $nOptScanSku?>">
    <input type="hidden"    id="ohdAPDDocTypeRef"   name="ohdAPDDocTypeRef" value="">
    
    <button style="display:none" type="submit" id="obtSubmitAPD" onclick="JSnAddEditAPD();"></button>
    <div class="row">
        <!-- left Panel -->
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- ข้อมูลหลัก -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocLabel'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvAPDSubHeadDocPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAPDSubHeadDocPanel" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="form-group xCNHide" style="text-align: right;">
                            <label class="xCNTitleFrom "><?php echo language('document/apdebitnote/apdebitnote', 'tAPDApproved'); ?></label>
                        </div>
                        <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/apdebitnote/apdebitnote', 'tAPDDocNo'); ?></label>

                        <div class="form-group" id="odvAPDAutoGenDocNoForm">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbAPDAutoGenCode" name="ocbAPDAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('document/apdebitnote/apdebitnote', 'tAPDAutoGenCode'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="odvAPDDocNoForm">
                            <div class="validate-input">
                                <input
                                    type="text"
                                    class="form-control input100 xCNGenarateCodeTextInputValidate"
                                    id="oetAPDDocNo"
                                    aria-invalid="false"
                                    name="oetAPDDocNo"
                                    data-is-created="<?php echo $tDocNo; ?>"
                                    data-validate-required="<?= language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterOrRunDocNo') ?>"
                                    data-validate-dublicateCode="<?= language('document/apdebitnote/apdebitnote', 'tAPDMsgDuplicate') ?>"
                                    placeholder="<?= language('document/apdebitnote/apdebitnote', 'tAPDDocNo') ?>"
                                    value="<?php echo $tDocNo; ?>"
                                    data-validate="Plese Generate Code"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/apdebitnote/apdebitnote', 'tAPDDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetAPDXphDocDate"
                                    name="oetAPDXphDocDate"
                                    value="<?php echo $tDocDate; ?>"
                                    data-validate-required="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetAPDXphDocDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/apdebitnote/apdebitnote', 'tAPDDocTime'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNTimePicker"
                                    id="oetAPDDocTime"
                                    name="oetAPDDocTime"
                                    value="<?php echo $tDocTime; ?>"
                                    data-validate-required="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterDocTime'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button id="obtAPDDocTime" type="button" class="btn xCNBtnDateTime" onclick="$('#oetAPDDocTime').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDCreateBy'); ?></label>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                                <input type="text" class="xCNHide" id="oetAPDCreateBy" name="oetAPDCreateBy" value="<?php echo $tUserCode ?>">
                                <label><?php echo $tUserName; ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBStaDoc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/apdebitnote/apdebitnote', 'tAPDStaDoc' . $tStaDoc); ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBStaApv'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/apdebitnote/apdebitnote', 'tAPDStaApv' . $tStaApv); ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBStaPrc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/apdebitnote/apdebitnote', 'tAPDStaPrcStk' . $tStaApv); ?></label>
                            </div>
                        </div>

                        <?php if($tDocNo != ''): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDApvBy'); ?></label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="text" class="xCNHide" id="oetAPDAjhApvCode" name="oetAPDAjhApvCode" maxlength="20" value="<?php echo $tApvCode?>">
                                    <label><?php echo $tUserApvName != '' ? $tUserApvName : language('document/apdebitnote/apdebitnote', 'tAPDStaDoc'); ?></label>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <!-- ข้อมูลหลัก -->

            <!-- เงื่อนไข -->
            <div id="odvAPDCondition" class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocCondition'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvAPDConditionPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAPDConditionPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">

                        <!-- ตัวแทนขาย -->
                        <?php
                            $tAPDDataInputADCode   = "";
                            $tAPDDataInputADName   = "";
                            if($tRoute  == "docAPDebitnoteEventAdd"){
                                $tAPDDataInputADCode    = $this->session->userdata('tSesUsrAgnCode');
                                $tAPDDataInputADName    = $this->session->userdata('tSesUsrAgnName');
                                $tBrowseADDisabled      = '';
                                if($this->session->userdata('tSesUsrLevel') != "HQ"){
                                    $tBrowseADDisabled  = 'disabled';
                                }
                            }else{
                                $tAPDDataInputADCode    = @$tAPDAgnCode;
                                $tAPDDataInputADName    = @$tAPDAgnName;
                                $tBrowseADDisabled      = 'disabled';
                            }
                        ?>
                        <script type="text/javascript">
                            var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                            if( tUsrLevel != "HQ" ){
                                // $('.xCNBrowseAD').hide();
                            }
                        </script>
                        <div class="form-group xCNBrowseAD">
                            <label class="xCNLabelFrm"><?= language('document/apdebitnote/apdebitnote', 'tAPDAgency'); ?></label>
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="input100 xCNHide" id="oetAPDAgnCode" name="oetAPDAgnCode" value="<?=$tAPDDataInputADCode?>">
                                <input 
                                    class="form-control xWPointerEventNone" 
                                    type="text" 
                                    id="oetAPDAgnName"
                                    name="oetAPDAgnName"
                                    value="<?=$tAPDDataInputADName?>" 
                                    readonly 
                                    placeholder="<?= language('document/apdebitnote/apdebitnote', 'tAPDAgency'); ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseADDisabled; ?>>
                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                    </button>
                                </span>
                            </div>
                        </div>


                        <?php
                            if($tRoute == "docAPDebitnoteEventAdd"){
                                $tUserBchCode       = $this->session->userdata('tSesUsrBchCodeDefault');
                                $tUserBchName       = $this->session->userdata('tSesUsrBchNameDefault');
                                $tDisabledBch       = '';
                            }else{
                                $tUserBchCode       = $tUserBchCode;
                                $tUserBchName       = $tUserBchName;
                                $tDisabledBch       = 'disabled';
                            }
                        ?>
                        <script type="text/javascript">
                            var tUsrLevel   = '<?=$this->session->userdata('tSesUsrLevel')?>';
                            if( tUsrLevel != "HQ" ){
                                var tBchCount   = '<?=$this->session->userdata("nSesUsrBchCount")?>';
                                if(tBchCount < 2){
                                    $('#obtAPDBrowseBch').attr('disabled',true);
                                }
                            }
                        </script>
            
                        <!-- สาขา -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDBranch'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdAPDBchCode" name="ohdAPDBchCode" value="<?=$tUserBchCode; ?>">
                                <input class="form-control xCNHide" id="oetAPDBchCode" name="oetAPDBchCode" maxlength="5" value="<?=$tUserBchCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetAPDBchName"
                                    name="oetAPDBchName"
                                    value="<?php echo $tUserBchName; ?>"
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDBranch'); ?>"
                                    readonly>
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledBch; ?> >
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- สาขา -->

                        <!-- กลุ่มธุรกิจ --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDMerChant');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetAPDMchCode" name="oetAPDMchCode" maxlength="5" value="<?php echo $tUserMchCode; ?>">
                                <input 
                                    class="form-control xWPointerEventNone" 
                                    type="text" 
                                    id="oetAPDMchName" 
                                    name="oetAPDMchName" 
                                    value="<?php echo $tUserMchName; ?>" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDMerChant');?>" readonly
                                >
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowseMch" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- กลุ่มธุรกิจ -->

                        <!-- ร้านค้า --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDShop'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdAPDWahCodeInShp" name="ohdAPDWahCodeInShp" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdAPDWahNameInShp" name="ohdAPDWahNameInShp" value="<?php echo $tUserWahName; ?>">
                                <input class="form-control xCNHide" id="oetAPDShpCode" name="oetAPDShpCode" maxlength="5" value="<?php echo $tUserShpCode; ?>">
                                <input 
                                    class="form-control xWPointerEventNone" 
                                    type="text" id="oetAPDShpName" 
                                    name="oetAPDShpName" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDShop'); ?>" 
                                    value="<?php echo $tUserShpName; ?>" 
                                    readonly
                                >
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowseShp" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- ร้านค้า -->

                        <!-- เครื่องจุดขาย --> <!-- ซ่อนไว้ในโปรเจค MoShi (Jame 27/03/63) -->
                        <div class="form-group xCNHide <?php if(!FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDPos'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetAPDPosCode" name="oetAPDPosCode" maxlength="5" value="<?php // echo $tUserPosCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAPDPosName" name="oetAPDPosName" placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPos'); ?>" value="<?php // echo $tUserPosName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowsePos" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- เครื่องจุดขาย -->

                        <!-- คลัง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDWarehouse'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdAPDWahCode" name="ohdAPDWahCode" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdAPDWahName" name="ohdAPDWahName" value="<?php echo $tUserWahName; ?>">
                                <input type="text" class="input100 xCNHide" id="oetAPDWahCode" name="oetAPDWahCode" maxlength="5" value="<?php echo $tUserWahCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAPDWahName" name="oetAPDWahName" placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDWarehouse'); ?>" value="<?php echo $tUserWahName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAPDBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
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
                <input type="hidden" id="ohdAPDSplVatCode" name="ohdAPDSplVatCode" value="<?php echo $tSplVatCode; ?>">
                <div id="odvHeadSpl" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDSpl'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvAPDSplPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAPDSplPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!--ชื่อผู้จำหน่าย -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBSpl'); ?></label>
                            <input 
                                type="text" 
                                class="form-control"
                                id="oetAPDFrmSplNameShow"
                                name="oetAPDFrmSplNameShow"
                                value="<?php echo $tSplName; ?>"
                                placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOTBSpl'); ?>" 
                                readonly
                            >
                        </div>
                        <!-- ประเภทภาษี -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDVATInOrEx'); ?></label>
                            <select class="selectpicker form-control" id="ocmAPDXphVATInOrEx" name="ocmAPDXphVATInOrEx" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?> onchange="JSbAPDChangeSplVatType()">
                                <option value="1" <?php echo $tXphVATInOrEx == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatIn'); ?></option>
                                <option value="2" <?php echo $tXphVATInOrEx == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatEx'); ?></option>
                            </select>
                        </div>
                        <input type="hidden" id="ohdCNFrmSplVatRate" name="ohdCNFrmSplVatRate" value="<?=$tPCVatRateBySPL?>">
                        <input type="hidden" id="ohdCNFrmSplVatCode" name="ohdCNFrmSplVatCode" value="<?=$tPCVatCodeBySPL?>">
                        <!-- ประเภทภาษี -->

                        <!-- ประเภทชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDPaymentType'); ?></label>
                            <select class="selectpicker form-control" id="ocmAPDXphCshOrCrd" name="ocmAPDXphCshOrCrd" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tXphCshOrCrd == '1' ? 'selected' : ''; ?>><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCash'); ?></option>
                                <option value="2" <?php echo $tXphCshOrCrd == '2' ? 'selected' : ''; ?>><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelPayCredit'); ?></option>
                            </select>
                        </div>
                        <!-- ประเภทชำระเงิน -->

                        <!-- การชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDPaymentPoint'); ?></label>
                            <select class="selectpicker form-control" id="ocmAPDHDPcSplXphDstPaid" name="ocmAPDHDPcSplXphDstPaid" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1" <?php echo $tHDPcSplXphDstPaid == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocPaid'); ?></option>
                                <option value="2" <?php echo $tHDPcSplXphDstPaid == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocDst'); ?></option>
                            </select>
                        </div>
                        <!-- การชำระเงิน -->

                        <!-- ระยะเครดิต -->
                        <div class="form-group xCNPanel_CreditTerm">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDCreditTerm'); ?></label>
                            <input
                                class="form-control text-right"
                                type="text"
                                id="oetAPDHDPcSplXphCrTerm"
                                name="oetAPDHDPcSplXphCrTerm"
                                value="<?php echo $tHDPcSplXphCrTerm; ?>"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDCreditTerm'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ระยะเครดิต -->

                        <!-- วงเงินเครดิต -->
                        <div class="form-group xCNHide">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDCreditLimit'); ?></label>
                            <input
                                class="form-control text-right"
                                type="text"
                                id="oetAPDHDPcSplCreditLimit"
                                name="oetAPDHDPcSplCreditLimit"
                                value="<?php echo $tHDPcSplXphCrTerm; ?>"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDCreditLimit'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- วงเงินเครดิต -->

                        <!-- วันครบกำหนดการชำระเงิน -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDPaymentDueDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetAPDHDPcSplXphDueDate"
                                    name="oetAPDHDPcSplXphDueDate"
                                    value="<?php echo $tHDPcSplXphDueDate; ?>"
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPaymentDueDate'); ?>"
                                    data-validate-required="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetAPDHDPcSplXphDueDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันครบกำหนดการชำระเงิน -->

                        <!-- วันวางบิล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDBillingDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetAPDHDPcSplXphBillDue"
                                    name="oetAPDHDPcSplXphBillDue"
                                    value="<?php echo $tHDPcSplXphBillDue; ?>"
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDBillingDate'); ?>"
                                    data-validate-required="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetAPDHDPcSplXphBillDue').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันวางบิล -->

                        <!-- วันที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTnfDate'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control xCNDatePicker xCNInputMaskDate"
                                    id="oetAPDHDPcSplXphTnfDate"
                                    name="oetAPDHDPcSplXphTnfDate"
                                    value="<?php echo $tHDPcSplXphTnfDate; ?>"
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDTnfDate'); ?>"
                                    data-validate-required="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDPlsEnterDocDate'); ?>"
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span class="input-group-btn">
                                    <button type="button" class="btn xCNBtnDateTime" onclick="$('#oetAPDHDPcSplXphTnfDate').focus()" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- วันที่ขนส่ง -->

                        <!-- ชื่อผู้ติดต่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDCtrName'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetAPDHDPcSplXphCtrName"
                                name="oetAPDHDPcSplXphCtrName"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDCtrName'); ?>"
                                value="<?php echo $tHDPcSplXphCtrName; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- ชื่อผู้ติดต่อ -->

                        <!-- เลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTransportNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetAPDHDPcSplXphRefTnfID"
                                name="oetAPDHDPcSplXphRefTnfID"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDTransportNumber'); ?>"
                                value="<?php echo $tHDPcSplXphRefTnfID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่ขนส่ง -->

                        <!-- อ้างอิงเลขที่ขนส่ง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDReferenceTransportationNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetAPDHDPcSplXphRefVehID"
                                name="oetAPDHDPcSplXphRefVehID"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDReferenceTransportationNumber'); ?>"
                                value="<?php echo $tHDPcSplXphRefVehID; ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- อ้างอิงเลขที่ขนส่ง -->

                        <!-- เลขที่บัญชีราคาสินค้า -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDProductPriceAccountNumber'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetAPDHDPcSplXphRefInvNo"
                                name="oetAPDHDPcSplXphRefInvNo"
                                value="<?php echo $tHDPcSplXphRefInvNo; ?>"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDProductPriceAccountNumber'); ?>"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- เลขที่บัญชีราคาสินค้า -->

                        <!-- จำนวนและลักษณะหีบห่อ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDQuantityAndPackaging'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="oetAPDHDPcSplXphQtyAndTypeUnit"
                                name="oetAPDHDPcSplXphQtyAndTypeUnit"
                                value="<?php echo $tHDPcSplXphQtyAndTypeUnit; ?>"
                                placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDQuantityAndPackaging'); ?>"
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
                    <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvAPDOtherPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAPDOtherPanel" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- สถานะความเคลื่อนไหว -->
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input
                                    type="checkbox"
                                    value="1"
                                    id="ocbAPDXphStaDocAct"
                                    name="ocbAPDXphStaDocAct"
                                    maxlength="1" <?php echo ($nStaDocAct == '1' || empty($nStaDocAct)) ? 'checked' : ''; ?>
                                    <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <span>&nbsp;</span>
                                <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tAPDStaDocAct'); ?></span>
                            </label>
                        </div>
                        <!-- สถานะความเคลื่อนไหว -->

                        <!-- สถานะอ้างอิง -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDStaRef'); ?></label>
                            <select class="selectpicker form-control" id="ocmAPDXphStaRef" name="ocmAPDXphStaRef" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="0" <?php echo $tStaRef == '0' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocNeverReference'); ?></option>
                                <option value="1" <?php echo $tStaRef == '1' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocSomeReference'); ?></option>
                                <option value="2" <?php echo $tStaRef == '2' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocAllReference'); ?></option>
                            </select>
                        </div>
                        <!-- สถานะอ้างอิง -->

                        <!-- จำนวนครั้งที่พิมพ์ -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDCountDocPrint'); ?></label>
                            <input
                                class="form-control"
                                type="text"
                                id="ocmAPDXphDocPrint"
                                name="ocmAPDXphDocPrint"
                                value="<?php echo $tDocPrint; ?>"
                                readonly="true"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                        </div>
                        <!-- จำนวนครั้งที่พิมพ์ -->

                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                        <div class="form-group">
                            <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAddPdtAgain'); ?></label>
                            <select class="selectpicker form-control" id="ocmAPDOptionAddPdt" name="ocmAPDOptionAddPdt" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                <option value="1"><?php echo language('document/apdebitnote/apdebitnote','tCreditAddamounttolist');?></option>
                                <option value="2"><?php echo language('document/apdebitnote/apdebitnote','tCreditAddnewitem');?></option>
                            </select>
                        </div>
                        <!-- กรณีเพิ่มสินค้ารายการเดิม -->

                        <!-- หมายเหตุ -->
                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDNote'); ?></label>
                            <textarea
                                class="form-control xCNInputWithoutSpc"
                                id="otaAPDXphRmk"
                                name="otaAPDXphRmk"
                                <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>><?php echo $tXphRmk; ?></textarea>
                        </div> -->
                        <!-- หมายเหตุ -->

                    </div>
                </div>
            </div>
            <!-- อื่นๆ -->

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvCDNReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab">
                    <label class="xCNTextDetail1"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDFileAttachment'); ?></label>
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
                <script type="text/javascript">
                    var oCDNCallDataTableFile = {
                        ptElementID     : 'odvCDNShowDataTable',
                        ptBchCode       : $('#ohdAPDBchCode').val(),
                        ptDocNo         : $('#oetAPDDocNo').val(),
                        ptDocKey        : 'TAPTPdHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdAPDStaApv').val(),
                        ptStaDoc        : $('#ohdAPDStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oCDNCallDataTableFile);
                </script>
            </div>

        </div>
        <!-- left Panel -->

        <!-- Right Panel -->
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" id="odvAPDRightPanal">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvAPDDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:300px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <!-- Nav Tab Menu Right -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">
                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvAPDContentProduct" aria-expanded="true">
                                                        <?= language('document/apdebitnote/apdebitnote', 'tAPDTabRightPdt') ?>
                                                    </a>
                                                </li>
                                                <!-- สินค้า -->

                                                <!-- อ้างอิงเอกสาร -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvAPDContentHDRef" aria-expanded="false">
                                                        <?= language('document/apdebitnote/apdebitnote', 'tAPDTabRightDocRef') ?>
                                                    </a>
                                                <li>
                                                <!-- อ้างอิงเอกสาร -->
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nav Tab Menu Right -->

                                <!-- Content Data Panel -->
                                <div class="tab-content">

                                    <!-- ตารางรายการ สินค้า -->
                                    <div id="odvAPDContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <!-- เลือกผู้จำหน่าย -->
                                                <label class="xCNLabelFrm"><?php echo language('document/purchaseinvoice/purchaseinvoice','tPITBSpl');?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="input100 xCNHide" id="oetAPDSplCode" name="oetAPDSplCode" maxlength="5" value="<?php echo $tSplCode; ?>">
                                                        <input 
                                                            type="text"
                                                            class="form-control xWPointerEventNone"
                                                            id="oetAPDSplName"
                                                            name="oetAPDSplName"
                                                            value="<?php echo $tSplName; ?>" placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDChooseSuplyer'); ?>" 
                                                            readonly
                                                        >
                                                        <span class="input-group-btn xWConditionSearchPdt">
                                                            <button id="obtAPDBrowseSpl" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                                <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- เลือกผู้จำหน่าย -->
                                            </div>
                                        </div>
                                        <?php if($bIsDocTypeHavePdt) : // ใบลดหนี้แบบมีสินค้า ?>
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
                                                                    <li id="oliAPDBtnDeleteAll" class="disabled">
                                                                        <a data-toggle="modal" data-target="#odvModalDelPdtAPD"><?php echo language('common/main/main', 'tDelAll') ?></a>
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
                                                                id="obtAPDDocBrowsePdt"
                                                                class="xCNBTNPrimeryPlus xCNDocBrowsePdt"
                                                                onclick="JCNvAPDBrowsePdt()"
                                                                type="button">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        <?php endif; ?>
                                        <div id="odvAPDPdtTablePanal"></div>
                                        <?php include('wAPDebitnoteEndOfBill.php'); ?>
                                    </div>
                                    <!-- ตารางรายการ สินค้า -->

                                    <!-- ตารางรายการ เอกสารอ้างอิง -->
                                    <div id="odvAPDContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtAPDAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvAPDTableHDRef"></div>
                                        </div>
                                    </div>
                                    <!-- ตารางรายการ เอกสารอ้างอิง -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Panel -->
    </div>
</form>

<!-- Modal Popup Appove -->
<div class="modal fade xCNModalApprove" id="odvAPDPopupApv">
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
                <button onclick="JSnAPDApprove(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup Order Column -->
<div class="modal fade" id="odvShowOrderColumn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button type="button" class="btn btn-primary" onclick="JSxSaveColumnShow()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup HD Discount -->
<div class="modal fade" id="odvModalEditAPDDisHD">
    <div class="modal-dialog xCNDisModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="display:inline-block"><label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAPDDisEndOfBill'); ?></label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAPDDisType'); ?></label>
                            <select class="selectpicker form-control" id="ostXthHDDisChgText" name="ostXthHDDisChgText">
                                <option value="3"><?php echo language('document/apdebitnote/apdebitnote', 'tDisChgTxt3') ?></option>
                                <option value="4"><?php echo language('document/apdebitnote/apdebitnote', 'tDisChgTxt4') ?></option>
                                <option value="1"><?php echo language('document/apdebitnote/apdebitnote', 'tDisChgTxt1') ?></option>
                                <option value="2"><?php echo language('document/apdebitnote/apdebitnote', 'tDisChgTxt2') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAPDValue'); ?></label>
                        <input type="text" class="form-control xCNInputNumericWithDecimal" id="oetXddHDDis" name="oetXddHDDis" maxlength="11" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary xCNBtnAddDis" onclick="FSvAPDAddHDDis()">
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

<!-- Modal Popup Cancel -->
<div class="modal fade" id="odvAPDPopupCancel">
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
                <button onclick="JSnAPDCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup Change Spl Confirm -->
<div class="modal fade" id="odvAPDPopupChangeSplConfirm">
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
                <button onclick="JSxAPDClearTemp()" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
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

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvAPDModalAddDocRef" class="modal fade" tabindex="-1" role="dialog" style='z-index:1045'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmAPDFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?=language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetAPDRefDocNoOld"  name="oetAPDRefDocNoOld">
                    <input type="text" class="form-control xCNHide" id="oetAPDRefDoc"       name="oetAPDRefDoc">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbAPDRefType" name="ocbAPDRefType">
                                    <option value="1" selected><?=language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?=language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbAPDRefDoc" name="ocbAPDRefDoc">
                                    <option value="1" selected><?=language('common/main/main', 'เอกสารใบซื้อสินค้า'); ?></option>
                                    <option value="2"><?=language('common/main/main', 'เอกสารใบรับเข้า'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetAPDDocRefInt" name="oetAPDDocRefInt" maxlength="20" value="">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetAPDDocRefIntName" name="oetAPDDocRefIntName" maxlength="20" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtAPDBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetAPDRefDocNo" name="oetAPDRefDocNo" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetAPDRefDocDate" name="oetAPDRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtAPDRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetAPDRefKey" name="oetAPDRefKey" placeholder="<?=language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtAPDConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?=language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบ ผู้จำหน่าย   ======================================================================== -->
<div id="odvAPDModalPleseselectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกผู้จำหน่าย ก่อนเพิ่มสินค้า</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvAPDModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard" id="olbTextModalHead"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDRefIntDocPITital') ?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvAPDFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal Change Data ======================================================================== -->
<div id="odvAPDModalChangeData" class="modal fade" style="z-index: 1400;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ohdAPDTypeChange" name="ohdAPDTypeChange">
                <p><span id="ospAPDTxtWarningAlert"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" id="obtAPDChangeData" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>


<input type="hidden" id="oetAPDBchCode" name="oetAPDBchCode" value="<?php echo $tUserBchCode;?>">

<?php include('ref_pi/wAPDebitnotePIModal.php'); ?>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jAPDAdd.php'); ?>
<?php include('dis_chg/wAPDebitnoteDisChgModal.php'); ?>

<script type="text/javascript">
    // Function : กดเลือกบาร์โค๊ด
    // Creator  : 09/03/2022 Wasin
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetAPDSplName').val() != "") {
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

    // Function : ค้นหาบาร์โค๊ด
    // Creator  : 09/03/2022 Wasin
    function JCNSearchBarcodePdt(ptTextScan) {
        var tWhereCondition = "";
        var aMulti          = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType: ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc: "",
                SPL: $("#oetAPDSplCode").val(),
                BCH: $("#oetAPDBchCode").val(),
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

    // Function : ค้นหาสินค้าใน Temp
    // Creator  : 09/03/2022 Wasin
    function JSvCDNCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbAPDDOCPdtTable tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
</script>