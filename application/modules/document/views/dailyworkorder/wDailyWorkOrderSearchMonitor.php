<?php
$dDateSerach = strtoupper(date("d M Y"));
$tDWODataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
$tDWODataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
?>
<style>
    .mark-font,
    .panel-default>.panel-heading.mark-font {
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<form id="ofmSatSurveyAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">

            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDWODateLan" class="xCNMenuPanelData panel-collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div style="font-size: 34px !important;" class="text-center mark-font" id="odvDateLanChg"><?= $dDateSerach ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ปฏิทิน -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSatHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkCalandar'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" aria-expanded="true">
                    </a>
                </div>
                <div id="odvSatDataStatusInfo" class="xCNMenuPanelData panel-collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="datepicker"></div>
                                <input type="hidden" id="ohdDWODateSearch">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel เงื่อนไขการค้นหา -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSatSurveyRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkCondition'); ?></label>
                </div>
                <div id="odvSatSurveyRefInfo" class="xCNMenuPanelData panel-collapse" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse สาขา -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkBch') ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide" id="ohdDWOBchCode" name="ohdDWOBchCode" maxlength="5" value="<?= @$tDWODataInputBchCode; ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDWOBchName" name="oetDWOBchName" maxlength="100" placeholder="<?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkBch') ?>" value="<?= @$tDWODataInputBchName; ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterSplCode'); ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="obtDWOBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Browse แผนก -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkDepartment') ?></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdDWODptCode" name="ohdDWODptCode" maxlength="5" value="<?php echo @$tSatBchCode ?>" data-bchcodeold="<?php echo @$tSatBchCode ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDWODptName" name="oetDWODptName" maxlength="100" placeholder="<?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkDepartment') ?>" data-validate-required="<?php echo language('document/deliveryorder/deliveryorder', 'tDOPlsEnterBch'); ?>" value="<?php echo @$tSatBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtDWOBrowseDepart" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- สถานะ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus'); ?></label>
                                    <select class="selectpicker form-control" id="ocmDWOStatusBay" name="ocmDWOStatusBay" maxlength="1">
                                        <option value="0" selected><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus0'); ?></option>
                                        <option value="1"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus1'); ?></option>
                                        <option value="2"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus2'); ?></option>
                                        <option value="3"><?php echo language('document/dailyworkorder/dailyworkorder', 'tDailyWorkStatus3'); ?></option>
                                    </select>
                                </div>
                                <!-- ค้นหา -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">&nbsp;</label>
                                    <button id="obtDWOFilterSearch" onclick="JSvDWOCallPageDataTableFilter()" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
            <div id="odvDWOPageDatatable"></div>
        </div>
    </div>
</form>

<?php include('script/jDailyWorkOrderMonitor.php'); ?>