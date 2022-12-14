<?php
    if(isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1"){
        $tASTRoute              = "dcmASTEventEdit";
        $nASTAutStaEdit         = 1;
        $tASTDocNo              = $aDataDocHD['raItems']['FTAjhDocNo'];
        $dASTDocDate            = $aDataDocHD['raItems']['FDAjhDocDate'];
        $dASTDocTime            = $aDataDocHD['raItems']['FDAjhDocTime'];
        $tASTCreateBy           = $aDataDocHD['raItems']['FTAjhUsrNameCreateBy'];
        $tASTStaDoc             = $aDataDocHD['raItems']['FTAjhStaDoc'];
        $tASTStaApv             = $aDataDocHD['raItems']['FTAjhStaApv'];
        $tASTApvCode            = $aDataDocHD['raItems']['FTAjhUsrCodeAppove'];
        $tASTApvHQCode          = $aDataDocHD['raItems']['FTAjhStkApvCode'];
        $tASTStaPrcStk          = $aDataDocHD['raItems']['FTAjhStaPrcStk'];
        $tASTBchCode            = $aDataDocHD['raItems']['FTAjhBchCodeFilter'];
        $tASTBchName            = $aDataDocHD['raItems']['FTAjhBchNameFilter'];
        $tASTMerCode            = $aDataDocHD['raItems']['FTAjhMerCodeFilter'];
        $tASTMerName            = $aDataDocHD['raItems']['FTAjhMerNameFilter'];
        $tASTShpCode            = $aDataDocHD['raItems']['FTAjhShopCodeFilter'];
        $tASTShpName            = $aDataDocHD['raItems']['FTAjhShopNameFilter'];
        $tASTPosCode            = $aDataDocHD['raItems']['FTAjhPosCodeFilter'];
        $tASTPosName            = $aDataDocHD['raItems']['FTAjhPosNameFilter'];
        $tASTWahCode            = $aDataDocHD['raItems']['FTAjhWahCodeFilter'];
        $tASTWahName            = $aDataDocHD['raItems']['FTAjhWahNameFilter'];
        $tASTDptCode            = $aDataDocHD['raItems']['FTAjhDptCode'];
        $tASTDptName            = $aDataDocHD['raItems']['FTAjhDptName'];
        $tASTUsrCode            = $aDataDocHD['raItems']['FTAjhUsrCode'];
        $tASTUsrNameCreateBy    = $aDataDocHD['raItems']['FTAjhUsrName'];
        $tASTRsnCode            = $aDataDocHD['raItems']['FTAjhRsnCode'];
        $tASTRsnName            = $aDataDocHD['raItems']['FTAjhRsnName'];
        $tASTLangEdit           = $this->session->userdata("tLangEdit");
        $tUserShpType           = $aDataDocHD['raItems']['FTShpType'];
        $tASTAjhRmk             = $aDataDocHD['raItems']['FTAjhRmk'];
        $tAjhFTAgnCode          = $aDataDocHD['raItems']['rtAgnCode'];
        $tAjhFTAgnName          = $aDataDocHD['raItems']['rtAgnName'];
        $tASTUsrNameApv         = $aDataDocHD['raItems']['FTAjsUsrNameAppove'];
        $tASTUsrNameApvHQ       = $aDataDocHD['raItems']['FTAjhUsrHQName'];
        $tASTStaDelMQ           = "";
        $tASTSesUsrBchCode      = $this->session->userdata("tSesUsrBchCode");
        $nASTStaDocAct          = $aDataDocHD['raItems']['FNAjhStaDocAct'];
        $tASTAjhCountType       = $aDataDocHD['raItems']['FTAjhCountType'];
        $tASTAjhDateFrm         = $aDataDocHD['raItems']['FDAjhDateFrm'];
        $tASTAjhDateTo          = $aDataDocHD['raItems']['FDAjhDateTo'];
        $tASTAjhStaStkApv       = $aDataDocHD['raItems']['FTAjhStaPrcStk'];
        $nStaUploadFile         = 2;
    }else{
        $tASTRoute              = "dcmASTEventAdd";
        $nASTAutStaEdit         = 0;
        $tASTDocNo              = "";
        $dASTDocDate            = "";
        $tASTApvHQCode          = "";
        $dASTDocTime            = date('H:i');
        $tASTCreateBy           = $this->session->userdata('tSesUsrUsername');
        $tASTStaDoc             = "";
        $tASTStaApv             = "";
        $tASTApvCode            = "";
        $tASTStaPrcStk          = "";
        $tASTStaDelMQ           = "";
        $tASTUsrNameApvHQ       = "";
        $tASTSesUsrBchCode      = $this->session->userdata("tSesUsrBchCode");
        $tASTBchCode            = $this->session->userdata("tSesUsrBchCodeDefault");
        $tASTBchName            = $this->session->userdata("tSesUsrBchNameDefault");
        $tASTMerCode            = $this->session->userdata("tSesUsrMerCode");
        $tASTMerName            = $this->session->userdata("tSesUsrMerName");
        $tASTShpCode            = $this->session->userdata("tSesUsrShpCodeDefault");
        $tASTShpName            = $this->session->userdata("tSesUsrShpNameDefault");
        $tASTPosCode            = "";
        $tASTPosName            = "";
        $tASTWahCode            = $this->session->userdata("tSesUsrWahCode");
        $tASTWahName            = $this->session->userdata("tSesUsrWahName");
        $tASTDptCode            = $this->session->userdata("tSesUsrDptCode");
        $tASTDptName            = $this->session->userdata("tSesUsrDptName");
        $tASTUsrCode            = $this->session->userdata('tSesUsername');
        $tASTUsrNameCreateBy    = $this->session->userdata('tSesUsrUsername');
        $tASTUsrNameApv         = "";
        $tASTLangEdit           = $this->session->userdata("tLangEdit");
        $tUserShpType           = "";
        $nASTStaDocAct          = "99";
        $tASTRsnName            = "";
        $tASTRsnCode            = "";
        $tASTAjhRmk             = "";
        $tASTAjhCountType       = "";
        $tASTAjhDateFrm         = "";
        $tASTAjhDateTo          = "";
        $tASTAjhStaStkApv       = "";
        $nStaUploadFile         = 1;
    }
    $tASTUserType = $this->session->userdata("tSesUsrLevel");
    $tApproveUser = $this->session->userdata('tSesUsername');
    $tUserCode = 'N/A';
    $tUserName = 'N/A';
?>
<form id="ofmASTFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtSubmitAST" onclick="JSxASTAddEditDocument()"></button>

    <!-- ============= Create By Witsarut 27/08/2019 ==================== -->
    <input type="hidden" id="ohdASTCompCode" name="ohdASTCompCode" value='<?=FCNtGetCompanyCode(); ?>'>
    <input type="hidden" id="ohdBaseUrl" name="ohdBaseUrl" value="<?php echo base_url(); ?>">
    <!-- ============= Create By Witsarut 27/08/2019 ==================== -->

    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?=$this->session->userdata('tSesSessionID')?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?=$this->session->userdata('tSesUsrLevel')?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?=$this->session->userdata('tSesUsrBchCom')?>">
    <input type="hidden" id="ohdLangEdit" name="ohdLangEdit" value="<?=$this->session->userdata("tLangEdit");?>">

    <input type="hidden" id="ohdASTAjhStaStkApv" name="ohdASTAjhStaStkApv" value="<?php echo $tASTAjhStaStkApv; ?>">
    <input type="hidden" id="ohdASTAjhCountType" name="ohdASTAjhCountType" value="<?php echo $tASTAjhCountType; ?>">
    <input type="hidden" id="ohdASTUserType" name="ohdASTUserType" value="<?php echo $tASTUserType; ?>">
    <input type="hidden" id="ohdASTRoute" name="ohdASTRoute" value="<?php echo $tASTRoute; ?>">
    <input type="hidden" id="ohdASTCheckClearValidate" name="ohdASTCheckClearValidate" value="0">
    <input type="hidden" id="ohdASTCheckSubmitByButton" name="ohdASTCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdASTAutStaEdit" name="ohdASTAutStaEdit" value="<?php echo $nASTAutStaEdit; ?>">

    <input type="hidden" id="ohdASTStaApv" name="ohdASTStaApv" value="<?php echo $tASTStaApv; ?>">
    <input type="hidden" id="ohdASTStaDoc" name="ohdASTStaDoc" value="<?php echo $tASTStaDoc; ?>">
	<input type="hidden" id="ohdASTStaPrcStk" name="ohdASTStaPrcStk" value="<?php echo $tASTStaPrcStk; ?>">
	<input type="hidden" id="ohdASTStaDelMQ" name="ohdASTStaDelMQ" value="<?php echo $tASTStaDelMQ; ?>">
    <input type="hidden" id="ohdASTSesUsrBchCode" name="ohdASTSesUsrBchCode" value="<?php echo $tASTSesUsrBchCode; ?>">
    <input type="hidden" id="ohdASTBchCode" name="ohdASTBchCode" value="<?php echo $tASTBchCode; ?>">

    <input type="hidden" id="ohdASTProveUser" name="ohdASTProveUser" value="<?php echo $tApproveUser;?>">
    <input type="hidden" id="ohdASTDptCode" name="ohdASTDptCode" value="<?php echo $tASTDptCode;?>">
    <input type="hidden" id="ohdASTUsrCode" name="ohdASTUsrCode" value="<?php echo $tASTUsrCode?>">
    <input type="hidden" id="ohdASTApvCodeUsrLogin" name="ohdASTApvCodeUsrLogin" value="<?php echo $tUserCode; ?>">
    <input type="hidden" id="ohdASTLangEdit" name="ohdASTLangEdit" value="<?php echo $tASTLangEdit; ?>">
    <input type="hidden" id="ohdASTOptAlwSavQty" name="ohdASTOptAlwSavQty" value="<?php echo $nOptDocSave?>">
    <input type="hidden" id="ohdASTOptScanSku" name="ohdASTOptScanSku" value="<?php echo $nOptScanSku?>">

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel ???????????????????????????????????????????????????????????????????????? -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvASTHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjuststock/adjuststock', 'tASTDocument'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvASTDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvASTDataStatusInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?php echo language('document/adjuststock/adjuststock', 'tASTApproved'); ?></label>
                                </div>
                                <input type="hidden" value="0" id="ohdCheckASTSubmitByButton" name="ohdCheckASTSubmitByButton">
                                <input type="hidden" value="0" id="ohdCheckASTClearValidate" name="ohdCheckASTClearValidate">
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/adjuststock/adjuststock', 'tASTDocNo'); ?></label>
                                <?php if(empty($tASTDocNo)):?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbASTStaAutoGenCode" name="ocbASTStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif;?>
                                <!-- ??????????????????????????????????????? -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                        id="oetASTDocNo"
                                        name="oetASTDocNo"
                                        maxlength="20"
                                        value="<?php echo $tASTDocNo;?>"
                                        data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/adjuststock/adjuststock','tASTDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                    <input type="hidden" id="ohdASTCheckDuplicateCode" name="ohdASTCheckDuplicateCode" value="2">
                                </div>
                                <!-- ???????????????????????????????????????????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTDocDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate"
                                            id="oetASTDocDate"
                                            name="oetASTDocDate"
                                            value="<?php echo $dASTDocDate;?>"
                                            data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterDocDate');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtASTDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ?????????????????????????????????????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNTimePicker"
                                            id="oetASTDocTime"
                                            name="oetASTDocTime"
                                            value="<?php echo $dASTDocTime;?>"
                                            data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterDocTime');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtASTDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>


                                <!-- ????????????????????????????????????????????????????????? -->
                                <div class="xCNSpcFormatCoupon" style="border:1px solid #ccc;position:relative;padding:15px; margin-top: 30px; margin-bottom: 10px;">
                                    <label class="xCNLabelFrm" style="position:absolute;top:-15px;left:15px;
                                        background: #fff;
                                        padding-left: 10px;
                                        padding-right: 10px;"><?php echo language('document/adjuststock/adjuststock', 'tASTAjdType');?></label>

                                    <input type="hidden" id="ohdCPHStaChkHQ" name="ohdCPHStaChkHQ">
                                    <!-- ?????????????????????????????????????????????????????????????????? -->
                                <div class="form-group">
                                    <select class="selectpicker form-control" id="oetASTCountType" name="oetASTCountType" maxlength="1">
                                        <option value="1"<?php if ($tASTAjhCountType == 1) {
                                                                echo "selected";
                                                            } ?>><?= language('document/adjuststock/adjuststock', 'tASTAdvCountType1') ?></option>
                                        <option value="2"<?php if ($tASTAjhCountType == 2) {
                                                                echo "selected";
                                                            } ?>><?= language('document/adjuststock/adjuststock', 'tASTAdvCountType2') ?></option>
                                    </select>
                                </div>
                                <div id="odvASTCircle">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/adjuststock/adjuststock', 'tASTAdvCountTypeRound') ?></label>
                                        <select class="selectpicker form-control" id="oetASTTypeRound" name="oetASTTypeRound" maxlength="1">
                                            <option class="xWRoundType" value="1" data-date="0"><?= language('document/adjuststock/adjuststock', 'tASTAdvCountTypeRound1') ?></option>
                                            <option class="xWRoundType" value="2" data-date="7"><?= language('document/adjuststock/adjuststock', 'tASTAdvCountTypeRound2') ?></option>
                                            <option class="xWRoundType" value="3" data-date="30"><?= language('document/adjuststock/adjuststock', 'tASTAdvCountTypeRound3') ?></option>
                                            <option class="xWRoundType xWElseSattment" value="4"><?= language('document/adjuststock/adjuststock', 'tASTAdvCountTypeRound4') ?></option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTAdvDateFrm');?></label>
                                        <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWDateControl"
                                            id="oetASTDateFrm"
                                            name="oetASTDateFrm"
                                            value="<?php echo $tASTAjhDateFrm;?>"
                                            data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterDocDate');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtASTDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTAdvDateTo');?></label>
                                        <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWDateControl"
                                            id="oetASTDateTo"
                                            name="oetASTDateTo"
                                            value="<?php echo $tASTAjhDateTo;?>"
                                            data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterDocDate');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtASTDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                    </div>
                                    </div>
                                </div>


                                <!-- ?????????????????????????????????????????? -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdASTCreateBy" name="ohdASTCreateBy" value="<?php echo $tASTCreateBy?>">
                                            <label><?php echo $tASTUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- ????????????????????????????????? -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTTBStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/adjuststock/adjuststock','tASTStaDoc'.$tASTStaDoc); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- ?????????????????????????????????????????????????????? -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTTBStaApv');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/adjuststock/adjuststock','tASTStaApv'.$tASTStaApv);?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($tASTDocNo) && !empty($tASTDocNo)):?>
                                    <!-- ???????????????????????????????????????????????? -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTTBApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdASTApvCode" name="ohdASTApvCode" maxlength="20" value="<?php echo $tASTApvCode?>">
                                                <label>
                                                    <?php echo (isset($tASTUsrNameApv) && !empty($tASTUsrNameApv)) ? $tASTUsrNameApv : "-"; ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- ????????????????????????????????????????????????????????? -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTTBStaPrc');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/adjuststock/adjuststock','tASTStaPrcStk'.$tASTStaPrcStk);?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($tASTDocNo) && !empty($tASTDocNo)):?>
                                    <!-- ???????????????????????????????????????????????? -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTTBApvStkBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdASTApvCode" name="ohdASTApvCode" maxlength="20" value="<?php echo $tASTApvHQCode?>">
                                                <label>
                                                    <?php echo (isset($tASTUsrNameApvHQ) && !empty($tASTUsrNameApvHQ)) ? $tASTUsrNameApvHQ : "-"; ?>
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
            <!-- Panel ?????????????????????????????????????????? -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvASTConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjuststock/adjuststock','tASTConditionDoc');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvASTDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvASTDataConditionDoc" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php
                                $tASTDataInputADCode   = "";
                                $tASTDataInputADName   = "";
                                if($tASTRoute  == "dcmASTEventAdd"){
                                    $tASTDataInputADCode    = $this->session->userdata('tSesUsrAgnCode');
                                    $tASTDataInputADName    = $this->session->userdata('tSesUsrAgnName');
                                    $tBrowseADDisabled     = '';
                                }else{
                                    $tASTDataInputADCode    = @$tAjhFTAgnCode;
                                    $tASTDataInputADName    = @$tAjhFTAgnName;
                                    $tBrowseADDisabled     = 'disabled';
                                }
                            ?>
                                <!-- Condition ?????????????????? -->
                                <div class="form-group xCNBrowseAD">
                                    <label class="xCNLabelFrm"><?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?></label>
                                    <div class="input-group" style="width:100%;">
                                        <input type="text" class="input100 xCNHide" id="ohdASTADCode" name="ohdASTADCode" value="<?=$tASTDataInputADCode?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="ohdASTADName" name="ohdASTADName" value="<?=$tASTDataInputADName?>" readonly placeholder="<?= language('document/invoice/invoice', 'tIVTitlePanelConditionAD'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtASTBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseADDisabled; ?>>
                                                <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Condition ???????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock','tASTBranch');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                            id="oetASTBchCode"
                                            name="oetASTBchCode"
                                            maxlength="5"
                                            value="<?php echo $tASTBchCode;?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTBchName"
                                            name="oetASTBchName"
                                            maxlength="100"
                                            placeholder="<?php echo language('document/adjuststock/adjuststock','tASTBranch');?>"
                                            value="<?php echo $tASTBchName;?>"
                                            readonly
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtBrowseASTBCH" type="button" class="btn xCNBtnBrowseAddOn " >
                                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Condition ???????????????????????????????????? -->
                                <!-- <div class="form-group <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : ''; ?>">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTMerchant'); ?></label>
                                    <div class="input-group">
                                        <?php
                                            // ????????????????????????????????????
                                            // if($tASTUserType == 'HQ'){
                                            //     $tASTDataInputMerCode   = '';
                                            //     $tASTDataInputMerName   = '';
                                            //     $tASTDataInputShpCode   = '';
                                            //     $tASTDataInputShpName   = '';
                                            //     $tASTDataInputWahCode   = '';
                                            //     $tASTDataInputWahName   = '';
                                            // }else{
                                                $tASTDataInputMerCode   = $tASTMerCode;
                                                $tASTDataInputMerName   = $tASTMerName;
                                                $tASTDataInputShpCode   = $tASTShpCode;
                                                $tASTDataInputShpName   = $tASTShpName;
                                                $tASTDataInputWahCode   = $tASTWahCode;
                                                $tASTDataInputWahName   = $tASTWahName;
                                            // }

                                        ?>
                                        <input class="form-control xCNHide" id="oetASTMerCode" name="oetASTMerCode" maxlength="5" value="<?php echo $tASTDataInputMerCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTMerName"
                                            name="oetASTMerName"
                                            value="<?php echo $tASTDataInputMerName;?>"
                                            placeholder="<?=language('document/adjuststock/adjuststock', 'tASTMerchant'); ?>"
                                            readonly
                                        >
                                        <span class="xWConDisDocument input-group-btn">
                                            <button id="obtASTBrowseMer" type="button" class="xWConDisDocument btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->

                                <!-- Condition ????????????????????? -->
                                <!-- <script>
                                    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                    if( tUsrLevel == "SHP" ){
                                        var tShpcount = '<?=$this->session->userdata("nSesUsrShpCount");?>';
                                        if(tShpcount < 2){
                                            $('#obtASTBrowseShp').attr('disabled',true);
                                        }
                                    }
                                </script>
                                <div class="form-group <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : ''; ?>">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTShop'); ?></label>
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetASTShopCode" name="oetASTShopCode" maxlength="5" value="<?php echo $tASTDataInputShpCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTShopName"
                                            name="oetASTShopName"
                                            value="<?php echo $tASTDataInputShpName;?>"
                                            placeholder="<?=language('document/adjuststock/adjuststock', 'tASTShop'); ?>"
                                            readonly
                                        >
                                        <span class="xWConDisDocument input-group-btn">
                                            <button id="obtASTBrowseShp" type="button" class="xWConDisDocument btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                        </span>
                                    </div>
                                </div> -->
                                <!-- Condition ??????????????????????????????????????? -->
                                <!-- ?????????????????????????????????  tASTPosName  ????????????????????????????????? tASTPosCode -->
                                <!-- <div class="form-group <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : '' ?>">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTPos'); ?></label>
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetASTPosType" value="<?php echo $tUserShpType;?>">
                                        <input class="form-control xCNHide" id="oetASTPosCode" name="oetASTPosCode" maxlength="5" value="<?php echo $tASTPosCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTPosName"
                                            name="oetASTPosName"
                                            value="<?php echo $tASTPosName;?>"
                                            placeholder="<?=language('document/adjuststock/adjuststock', 'tASTPos'); ?>"
                                            readonly
                                        >
                                        <span class="xWConDisDocument input-group-btn">
                                            <button id="obtASTBrowsePos" type="button" class="xWConDisDocument btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                        </span>
                                    </div>
                                </div> -->

                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTWarehouse'); ?></label>
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetASTWahCode" name="oetASTWahCode" maxlength="5" value="<?php echo $tASTDataInputWahCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTWahName"
                                            name="oetASTWahName"
                                            value="<?php //echo $tASTDataInputWahName;?>"
                                             data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterWahFrom'); ?>"
                                        >
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtASTBrowseWah" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                        </span>
                                    </div>
                                </div> -->

                                <!-- Condition ?????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/adjuststock/adjuststock', 'tASTReason'); ?></label>
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetASTRsnCode" name="oetASTRsnCode" maxlength="5" value="<?=$tASTRsnCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetASTRsnName"
                                            name="oetASTRsnName"
                                            value="<?php echo $tASTRsnName;?>"
                                            placeholder="<?=language('document/adjuststock/adjuststock', 'tASTReason'); ?>"
                                            readonly data-validate-required="<?php echo language('document/adjuststock/adjuststock', 'tASTPlsEnterReason'); ?>"
                                        >
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtASTBrowseRsn" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Condition ???????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTRemark'); ?></label>
                                    <textarea class="form-control2" id="otaASTRmk" name="otaASTRmk" rows="10" maxlength="200" style="resize:none;height:86px;"><?=$tASTAjhRmk;?></textarea>
                                </div>

                                  <!-- ?????????????????????????????????????????????-->
                                  <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbASTStaDocAct" name="ocbASTStaDocAct" maxlength="1" <?php if ($nASTStaDocAct == 1 && $nASTStaDocAct != 0) {
                                            echo 'checked';
                                        } else if ($nASTStaDocAct == 99) {
                                            echo 'checked';
                                        }  ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?= language('document/adjuststock/adjuststock', 'tASTStaDocAct'); ?></span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('common/main/main', 'tUPFPanelFile'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIVDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvShowDataTable"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script>

                var oIVCallDataTableFile = {
                    ptElementID     : 'odvShowDataTable',
                    ptBchCode       : $('#ohdASTBchCode').val(),
                    ptDocNo         : $('#oetASTDocNo').val(),
                    ptDocKey        : 'TCNTPdtAdjStkHD',
                    ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                    pnEvent         : '<?= $nStaUploadFile ?>',
                    ptCallBackFunct : 'JSxSoCallBackUploadFile',
                    ptStaApv        : $('#ohdASTStaApv').val(),
                    ptStaDoc        : $('#ohdASTStaDoc').val()
                }
                JCNxUPFCallDataTable(oIVCallDataTableFile);
            </script>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ???????????????????????????????????????????????????????????? -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Condition ?????????????????????????????? -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTWarehouse'); ?></label>
                                            <div class="input-group">
                                                <input name="oetASTWahName" id="oetASTWahName" class="form-control" value="<?php echo $tASTDataInputWahName;?>" type="text" readonly=""
                                                    data-validate="<?= language('document/transferreceiptbranch/transferreceiptbranch', 'tTBIPlsEnterWahTo') ?>"
                                                    placeholder="<?= language('document/transferreceiptbranch/transferreceiptbranch', 'tTBITablePDTWah') ?>"
                                                >
                                                <input name="oetASTWahCode" id="oetASTWahCode" value="<?php echo $tASTDataInputWahCode;?>" class="form-control xCNHide xCNClearValue" type="text">
                                                <span class="input-group-btn">
                                                    <button class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled" id="obtASTBrowseWah" type="button">
                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    maxlength="100"
                                                    id="oetASTSearchPdtHTML"
                                                    name="oetASTSearchPdtHTML"
                                                    onkeyup="JSvASTSearchPdtHTML()"
                                                    placeholder="<?php echo language('document/adjuststock/adjuststock', 'tASTSearchPdt');?>"
                                                >
                                                <input
                                                    style="display:none;"
                                                    type="text"
                                                    class="form-control"
                                                    maxlength="100"
                                                    id="oetASTScanPdtHTML"
                                                    name="oetASTScanPdtHTML"
                                                    onkeyup="Javascript:if(event.keyCode==13) JSvASTScanPdtHTML()"
                                                    placeholder="<?php echo language('document/adjuststock/adjuststock', 'tASTScanPdt');?>"
                                                    data-validate="<?php echo language('document/adjuststock/adjuststock','tASTScanPdtNotFound');?>"
                                                >
                                                <!-- <span class="input-group-btn">
                                                    <div id="odvASTSearchBtnGrp" class="xCNDropDrownGroup input-group-append">
                                                        <button id="obtASTMngPdtIconSearch" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan">
                                                            <img class="xCNIconSearch" style="width:20px;">
                                                        </button>
                                                        <button id="obtASTMngPdtIconScan" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan" style="display:none;">
                                                            <img class="xCNIconScanner" style="width:20px;">
                                                        </button>
                                                    </div>
                                                </span> -->
                                                <span class="input-group-btn">
                                                    <button id="obtASTMngPdtIconSearch" class="btn xCNBtnSearch" type="button">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <select class="form-control selectpicker disabled" disabled="disabled" id="ostASTApvSeqChk" name="ostASTApvSeqChk" maxlength="1">
                                                <option value="1"><?php echo language('document/adjuststock/adjuststock', 'tASTApvSeqChk1'); ?></option>
                                                <option value="2" ><?php echo language('document/adjuststock/adjuststock', 'tASTApvSeqChk2'); ?></option>
                                                <option value="3" selected><?php echo language('document/adjuststock/adjuststock', 'tASTApvSeqChk3'); ?></option>
                                                <option value="4"><?php echo language('document/adjuststock/adjuststock', 'tASTApvSeqChk4'); ?></option>
                                            </select>
                                        </div>
                                    </div> -->


                                    <!-- <div  id="olbASTAdvTableLists" class="btn-group xCNDropDrownGroup">
                                        <div class="text-right">
                                            <button type="button" class="btn xCNBTNMngTable"><?php echo language('common/main/main','tModalAdvMngTable')?></button>
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div id="odvASTMngAdvPdtDataTable" class="btn-group xCNDropDrownGroup right">
                                            <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                                <?php echo language('common/main/main','tCMNOption')?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li id="oliASTDelPdtDT" class="disabled">
                                                    <a data-toggle="modal" data-target="#odvASTModalDelPdtDTTemp"><?php echo language('common/main/main','tDelAll')?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> -->

                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-right">
                                        <div id="odvASTButtonGroup">
                                            <div class="btn-group xCNDropDrownGroup">
                                                <button <?php if(!empty($tASTStaApv) || $tASTStaDoc == 3){ echo "disabled style='display:none;'"; }?> type="button" class="btn xCNBTNMngTable xWASTDisabledOnApv" data-toggle="dropdown">
                                                    <?php echo language('common/main/main', 'tCMNOption') ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li id="oliASTDelPdtDT" class="disabled">
                                                        <a data-toggle="modal" data-target="#odvASTModalDelPdtDTTemp"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- ???????????????????????????????????????????????????????????? -->
                                            <div class="btn-group" style="margin-left: 5px;">
                                                <input <?php if(!empty($tASTStaApv) || $tASTStaDoc == 3){ echo "disabled style='display:none;'"; }?> type="text" class="form-control xWASTDisabledOnApv" id="oetAdjStkScan" name="oetAdjStkScan" autocomplete="off" maxlength="50" onkeypress="Javascript:if(event.keyCode==13) JSxASTEventScan(event,this);"  placeholder="????????????????????????????????????????????????????????????????????? ???????????? ??????????????????????????????" style="width: 205px;" >
                                            </div>

                                            <div class="btn-group" style="margin-left: 5px;">
                                                <button <?php if(!empty($tASTStaApv) || $tASTStaDoc == 3){ echo "disabled style='display:none;'"; }?> id="obtAdjStkFilterDataCondition" type="button" class="btn btn-primary xWASTDisabledOnApv" style="font-size: 16px;">???????????????????????????????????????????????????????????????</button>
                                            </div>

                                        </div>
                                    </div>


                                    <!-- <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <div style="position: absolute;right: -135px;top:-38px;">
                                                <button type="button" id="obtASTDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt
                                                <?php
                                                    if($tASTRoute == "dcmASTEventAdd") {
                                                ?>
                                                    disabled
                                                <?php
                                                    }else{
                                                      if($tASTMerCode != ""){
                                                ?>
                                                    disabled
                                                <?php
                                                    }
                                                }
                                                ?>" onclick="JCNvAdjStkBrowsePdt()" type="button">+</button>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row" id="odvASTPdtTablePanal"></div>
                                <input type="hidden" id="ohdConfirmIDDelete">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ================================================================== View Modal Advance Table ================================================================== -->
    <div id="odvASTOrderAdvTblColumns" class="modal fade">
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
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                    <button id="obtASTSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
                </div>
            </div>
        </div>
    </div>

<!-- ================================================================= View Modal Appove Document ================================================================= -->
<div id="odvASTModalAppoveDoc" class="modal fade xCNModalApprove">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main','tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('common/main/main','tMainApproveStatus'); ?></p>
                    <ul>
                        <li><?php echo language('common/main/main','tMainApproveStatus1'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus2'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus3'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus4'); ?></li>
                    </ul>
                <p><?php echo language('common/main/main','tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main','tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button  id="obtASTConfirmApprDoc" type="button" class="btn xCNBTNPrimery"  data-dismiss="modal" onclick="JSxASTBCHAprroveDoc()"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ================================================================= View Modal Appove HQ ================================================================= -->
<div id="odvASTModalHQAppoveDoc" class="modal fade xCNModalApprove">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main','tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('common/main/main','tMainApproveStatus'); ?></p>
                    <ul>
                        <li><?php echo language('common/main/main','tMainApproveStatus1'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus2'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus3'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus4'); ?></li>
                    </ul>
                <p><?php echo language('common/main/main','tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main','tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button  id="obtASTHQConfirmApprDoc" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ????????????????????????????????????????????? ======================================================================== -->
<div id="odvASTModalPDTNotFound" class="modal fade">
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

<!-- ======================================================================== Modal ???????????????????????????????????????????????????????????? ======================================================================== -->
<div id="odvASTModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;">????????????????????????????????????????????????</label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal">???????????????</button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal">?????????</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-striped xCNTablePDTMoreOne">
                    <thead>
                        <tr>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalcodePDT')?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalnamePDT')?></th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalPriceUnit')?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalbarcodePDT')?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ???????????????????????????????????? ======================================================================== -->
<div class="modal fade" id="odvASTPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/adjuststock/adjuststock', 'tASTCanDoc'); ?></label>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/adjuststock/adjuststock', '???????????????????????????????????????????????????????????????????????????????????????????????????????????? - ??????????????????????????????????????????????????????'); ?></p>
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/adjuststock/adjuststock', 'tASTReason'); ?></label>
                    <div class="input-group">
                        <input class="form-control xCNHide" id="oetASTRsnCodeCancel" name="oetASTRsnCodeCancel" maxlength="5" value="">
                        <input
                            type="text"
                            class="form-control"
                            id="oetASTRsnNameCancel"
                            name="oetASTRsnNameCancel"
                            value=""
                            placeholder="<?=language('document/adjuststock/adjuststock', 'tASTReason'); ?>"
                            readonly >
                        <span class="input-group-btn">
                            <button id="obtASTBrowseRsnCancel" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="JSnASTCancelDoc(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvAdjStkFilterDataCondition">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead"><label class="xCNTextModalHeard"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterTitle'); ?></label></div>
            <div class="modal-body" style="height: 450px;overflow-y: auto;">

                <form id="ofmASTFilterDataCondition">

                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitleProduct'); ?></label>
                        <!-- Browse Pdt -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- ??????????????????????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeFrom'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterPdtCodeFrom" name="oetASTFilterPdtCodeFrom" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterPdtNameFrom" name="oetASTFilterPdtNameFrom" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterProductFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ??????????????????????????????????????? -->
                            </div>
                            <div class="col-md-6">
                                <!-- ??????????????????????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeTo'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterPdtCodeTo" name="oetASTFilterPdtCodeTo" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterPdtNameTo" name="oetASTFilterPdtNameTo" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterProductTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ??????????????????????????????????????? -->
                            </div>
                        </div>
                        <!-- Browse Pdt -->
                    </div>

                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitleSpl'); ?></label>
                        <!-- Browse Supplier -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeFrom'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterSplCodeFrom" name="oetASTFilterSplCodeFrom" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterSplNameFrom" name="oetASTFilterSplNameFrom" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterSupplierFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>
                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeTo'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterSplCodeTo" name="oetASTFilterSplCodeTo" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterSplNameTo" name="oetASTFilterSplNameTo" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterSupplierTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>
                        </div>
                        <!-- Browse Supplier -->
                    </div>

                    <div class="xCNTabCondition" style="display:none;">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitleUserPI'); ?></label>
                        <!-- Browse User -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeFrom'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterUsrCodeFrom" name="oetASTFilterUsrCodeFrom" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterUsrNameFrom" name="oetASTFilterUsrNameFrom" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterUserFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>
                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubFilterCodeTo'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterUsrCodeTo" name="oetASTFilterUsrCodeTo" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterUsrNameTo" name="oetASTFilterUsrNameTo" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterUserTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>
                        </div>
                        <!-- Browse User -->
                    </div>

                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitlePdtGroup'); ?></label>
                        <!-- Browse Product Group -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterPgpCode" name="oetASTFilterPgpCode" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterPgpName" name="oetASTFilterPgpName" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterProductGroup" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <!-- Browse Product Group -->
                    </div>

                    <!-- Browse Product Location Seq -->
                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitleLocation'); ?></label>
                        <div class="row">

                            <div class="col-md-6">
                                <!-- ????????????????????? -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetASTFilterPlcCode" name="oetASTFilterPlcCode" value="">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetASTFilterPlcName" name="oetASTFilterPlcName" value="" readonly="">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtASTBrowseFilterProductLocation" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ????????????????????? -->
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input name="ocbASTPdtLocChkSeq" class="form-check-input xWASTDisabledOnApv" type="checkbox" value="1" id="ocbASTPdtLocChkSeq">
                                        <label class="form-check-label" for="ocbASTPdtLocChkSeq"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTextPdtLocSeqOnly'); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <!-- Browse Product Location Seq -->

                    <!-- Product StockCard -->
                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitlePdtStkCard'); ?></label>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <div class="form-check">
                                        <input name="ocbASTUsePdtStkCard" class="form-check-input xWASTDisabledOnApv" type="checkbox" id="ocbASTUsePdtStkCard">
                                        <label class="form-check-label" for="ocbASTUsePdtStkCard"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTextUsePdtStkCard'); ?></label>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-left: 20px;margin-top: -10px;">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input xWASTDisabledOnCheckUsePdtStkCard" id="orbASTPdtStkCard_1" name="orbASTPdtStkCard" value="1" disabled>
                                        <label class="custom-control-label" for="orbASTPdtStkCard_1"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTextPdtNotMove'); ?></label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input xWASTDisabledOnCheckUsePdtStkCard" id="orbASTPdtStkCard_2" name="orbASTPdtStkCard" value="2" disabled>
                                        <label class="custom-control-label" for="orbASTPdtStkCard_2">
                                            <?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTextPrePdtMove'); ?>
                                            <input class="form-control xWASTDisabledOnCheckUsePdtStkCard" type="number" id="onbASTPdtStkCardBack" name="onbASTPdtStkCardBack" min="1" style="width: 60px;display: inline;" disabled>
                                            <?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTextMonth'); ?>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Product StockCard -->

                    <!-- Product StockCondition -->
                    <div class="xCNTabCondition">
                        <label class="xCNTabConditionHeader xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterTitlePdtStkCondition'); ?></label>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="margin-left: 20px;margin-top: -10px;">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input xWASTDisabledOnCheckUsePdtStkCard" id="orbASTPdtStkCondition_1" name="orbASTPdtStkCondition" value="1" >
                                        <label class="custom-control-label" for="orbASTPdtStkCondition_1">
                                            <?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterStockcondition1'); ?>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input xWASTDisabledOnCheckUsePdtStkCard" id="orbASTPdtStkCondition_2" name="orbASTPdtStkCondition" value="2" checked>
                                        <label class="custom-control-label" for="orbASTPdtStkCondition_2">
                                            <?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterStockcondition2'); ?>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input xWASTDisabledOnCheckUsePdtStkCard" id="orbASTPdtStkCondition_3" name="orbASTPdtStkCondition" value="3" >
                                        <label class="custom-control-label" for="orbASTPdtStkCondition_3">
                                            <?php echo language('document/adjuststocksub/adjuststocksub', 'tASTFilterStockcondition3'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product StockCard -->
                </form>

            </div>
            <div class="modal-footer">
                <button id="obtAdjStkConfirmFilter" type="button" class="btn xCNBTNPrimery xWASTDisabledOnApv"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubBtnFilterConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ?????????????????????????????????????????????????????????????????????????????????????????? ======================================================================== -->
<div id="odvASTModalDocNotApv" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>??????????????????????????????????????????????????????????????????????????? ??????????????????????????? ?????????????????????????????????????????????????????????????????????????????????????????????????????? ???????????????????????????????????????????????????????????????????????? ??????????????????????????????????????????????????????????????? ???????????????????????????????????????????????? ???????????????????????????????????????????????????????????????????????????????????????????????????????????? ????????????????????????????????????</p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxASTHQSendNotiForDocumentNotApv()" data-dismiss="modal" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tCMNOK'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jAdjustStockAdd.php'); ?>
