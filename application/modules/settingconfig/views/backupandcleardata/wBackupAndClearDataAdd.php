<?php
if ($aResult['rtCode'] == "1") {
    // Data
    $FTPrgKey               = $aResult['raItems']['FTPrgKey'];
    $tPrgType               = $aResult['raItems']['FNPrgType'];
    $tPrgDocType            = $aResult['raItems']['FNPrgDocType'];
    $FNPrgKeepSpl           = $aResult['raItems']['FNPrgKeepSpl'];
    $FNPrgKeep              = $aResult['raItems']['FNPrgKeep'];
    $FTPrgStaUseSpl         = $aResult['raItems']['FTPrgStaUseSpl'];
    $FTPrgStaUse            = $aResult['raItems']['FTPrgStaUse'];
    $FTPrgStaPrgSpl         = $aResult['raItems']['FTPrgStaPrgSpl'];
    $FTPrgStaPrg            = $aResult['raItems']['FTPrgStaPrg'];
    $FTPrgName              = $aResult['raItems']['FTPrgName'];
    $FTPrgGroup             = $aResult['raItems']['FTPrgGroup'];
    $FNPrgTypeName          = $aResult['raItems']['FNPrgTypeName'];
    $FDPrgLast              = date_format(date_create($aResult['raItems']['FDPrgLast']), 'd/m/Y');

    // print_r($aResult);

    if ($FNPrgKeepSpl != '') {
        $FNPrgKeep      = $FNPrgKeepSpl;
    } else {
        $FNPrgKeep      = '';
    }
    if ($FTPrgStaUseSpl != '') {
        $FTPrgStaUse = $FTPrgStaUseSpl;
    } else {
        $FTPrgStaUse = $FTPrgStaUse;
    }
    if ($FTPrgStaPrgSpl != '') {
        $FTPrgStaPrg = $FTPrgStaPrgSpl;
    } else {
        $FTPrgStaPrg = $FTPrgStaPrg;
    }


    $tRoute         = "BACEditEvent";
}
?>
<style>
    .xWEJBoxFilter {
        border: 1px solid #ccc !important;
        position: relative !important;
        padding: 15px !important;
        margin-top: 10px !important;
        padding-bottom: 0px !important;
        margin-bottom: 10px !important;
    }

    .xWEJBoxFilter .xWEJLabelFilter {
        position: absolute !important;
        top: -15px;
        left: 15px !important;
        background: #fff !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .nav {
        cursor: pointer;
    }

    .xWtextbold {
        font-weight: bold;
    }

    .fancy-checkbox input[type="checkbox"]+span:before {
        margin-right: 4px !important;
        /* cursor: not-allowed; */
    }
</style>
<div class="">
    <label class="xCNLabelFrm xWEJLabelFilter" wfd-id="2186"><?php echo language('service/question/question', 'tQAHSelectType4') ?></label>
    <div class="form-group" style='border-top: 1px solid #e3e3e3;'>
        <div class='row' style="padding-top: 10px;">
            <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                <label class="xWtextbold"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACDocName') ?></label>
            </div>
            <div class="col-xs-12 col-md-10 text-left">
                <label><?php echo $FTPrgName ?></label>
            </div>
            <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                <label class="xWtextbold"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType') ?></label>
            </div>
            <div class="col-xs-12 col-md-10 text-left">
                <label><?php echo $FNPrgTypeName ?></label>
            </div>
            <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                <label class="xWtextbold"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup') ?></label>
            </div>
            <div class="col-xs-12 col-md-10 text-left">
                <label><?php echo $FTPrgGroup ?></label>
            </div>
            <div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
                <label class="xWtextbold"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgLast') ?></label>
            </div>
            <div class="col-xs-12 col-md-10 text-left">
                <label><?php echo $FDPrgLast ?></label>
            </div>
        </div>
    </div>
</div>

<div class="">
    <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmBackupAndClean">
        <button style="display:none" type="submit" id="obtSubmitBackup" onclick="JSnAddEditBackupAndClean('<?php echo $tRoute ?>')"></button>
        <div class="" style="padding-top:20px !important;">
            <input type="hidden" id="ohdBacPrgKey" name="ohdBacPrgKey" value="<?= $FTPrgKey; ?>">
            <input type="hidden" id="ohdBacPrgDocType" name="ohdBacPrgDocType" value="<?= $tPrgDocType; ?>">
        </div>



        <div class="col-lg-12 col-md-12" style='padding-left : 0px;'>
            <div class="col-lg-9 col-md-9" style='padding-left : 0px;'>
                <div class="panel panel-default" style="margin-bottom: 25px;">
                    <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?= language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgeConfig'); ?></label>
                    </div>
                    <div id="odvResultFormat">
                        <div class="panel-body">

                            <!--ใช้รหัสเริ่มต้นเท่าไหร่-->
                            <div class="row form-group">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <label class="xCNLabelFrm">
                                        <?php if ($tPrgType == '3') { ?>
                                            <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACTitleKeepFile') ?>
                                        <? } else { ?>
                                            <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACTitleKeepDay') ?>
                                        <?php } ?>
                                    </label>
                                    <div id="odvTextTypeContainer">
                                        <div>
                                            <div class="col-xs-10 col-md-10 col-lg-10" style='padding : 0px'>
                                                <input class="xCNInputNumericWithDecimal" style="text-align: right;" name="oetBacKeep" id="oetBacKeep" type="text" value="<?= $FNPrgKeep ?>" autocomplete="off">
                                            </div>
                                            <div class="col-xs-2 col-md-2 col-lg-2">
                                                <?php if ($tPrgType == '3') { ?>
                                                    <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanFile') ?>
                                                <? } else { ?>
                                                    <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanDay') ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($aResult['raItems']['FTPrgStaPrgSpl'] == '1') {
                                $tCheck = 'checked';
                            } else {
                                $tCheck = '';
                            } ?>
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="ocbPrgStaPrg" name="ocbPrgStaPrg" <?= $tCheck; ?> value="1">
                                <span><?= language('settingconfig/settingconfig/settingconfig', 'tBACPrgStaPrg'); ?></span>
                            </label>

                            <!--อนุญาตให้ใช้คั่น-->
                            <?php if ($aResult['raItems']['FTPrgStaUseSpl'] == '1') {
                                $tCheck2 = 'checked';
                            } else {
                                $tCheck2 = '';
                            } ?>
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="ocbPrgStaUse" name="ocbPrgStaUse" <?= $tCheck2; ?> value="1">
                                <span><?= language('settingconfig/settingconfig/settingconfig', 'tBACPrgUse1'); ?></span>
                            </label>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3" style='padding-left : 0px;'>
            </div>
        </div>

        <div class="form-group">
            <label class="fancy-checkbox">
                <input type="checkbox" id="ocbUseStandart" value="1" name="ocbUseStandart">
                <span><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgUseStd') ?></span>
            </label>
        </div>

        <div class="col-lg-9 col-md-8" style='padding-left : 0px;'>
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgeStd'); ?></label>
                </div>
                <div id="odvResultFormat">
                    <div class="panel-body">

                        <!--ใช้รหัสเริ่มต้นเท่าไหร่-->
                        <div class="row form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <label class="xCNLabelFrm">
                                    <?php if ($tPrgType == '3') { ?>
                                        <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACTitleKeepFile') ?>
                                    <? } else { ?>
                                        <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACTitleKeepDay') ?>
                                    <?php } ?>
                                </label>
                                <div id="odvTextTypeContainer">
                                    <div>
                                        <div class="col-xs-10 col-md-10 col-lg-10" style='padding : 0px'>
                                            <input class="xCNInputNumericWithDecimal" readonly style="text-align: right;" name="oetOldKeep" id="oetOldKeep" type="text" value="<?= $aResult['raItems']['FNPrgKeep'] ?>" autocomplete="off" placeholder="<?= language('settingconfig/settingconfig/settingconfig', 'tFmtUserStart'); ?>">
                                        </div>
                                        <div class="col-xs-2 col-md-2 col-lg-2">
                                            <?php if ($tPrgType == '3') { ?>
                                                <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanFile') ?>
                                            <? } else { ?>
                                                <?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACSpanDay') ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($aResult['raItems']['FTPrgStaPrg'] == '1') {
                            $tCheck = 'checked';
                        } else {
                            $tCheck = '';
                        } ?>
                        <label class="fancy-checkbox">
                            <input disabled type="checkbox" id="ocbOldOurge" name="ocbOldOurge" <?= $tCheck; ?> value="1">
                            <span><?= language('settingconfig/settingconfig/settingconfig', 'tBACPrgStaPrg'); ?></span>
                        </label>

                        <!--อนุญาตให้ใช้คั่น-->
                        <?php if ($aResult['raItems']['FTPrgStaUse'] == '1') {
                            $tCheck2 = 'checked';
                        } else {
                            $tCheck2 = '';
                        } ?>
                        <label class="fancy-checkbox">
                            <input disabled type="checkbox" id="ocbOldUse" name="ocbOldUse" <?= $tCheck2; ?> value="1">
                            <span><?= language('settingconfig/settingconfig/settingconfig', 'tBACPrgUse1'); ?></span>
                        </label>

                    </div>
                </div>
            </div>
        </div>
</div>

</form>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php
include 'script/jBackupAndClearDataAdd.php';
?>

<script type="text/javascript">

</script>