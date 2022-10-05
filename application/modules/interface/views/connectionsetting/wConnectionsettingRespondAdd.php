<?php
if ($aResult['rtCode'] == "1") {
    $FTErrCode          =   $aResult['raItems']['FTErrCode'];
    $FTErrDescription   =   $aResult['raItems']['FTErrDescription'];
    //route
    $tRoute             = "connectionsettingEventEditRespond";
} else {


    $FTErrCode          =   "";
    $FTErrDescription   =   "";

    //route
    $tRoute             = "connectionsettingEventAddRespond";
}
?>


<div class="row">
    <!--ปุ่มตัวเลือก กับ ปุ่มเพิ่ม-->
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContentRespond();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
        </button>
        <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
            <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSetting').click()"> <?php echo  language('common/main/main', 'tSave') ?></button>
        <?php endif; ?>
    </div>


    <form id="ofmAddConnectionSettingRespond" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
        <button style="display:none" type="submit" id="obtSubmitConnectionSetting" onclick="JSnAddEditConnectionSettingRespond('<?php echo $tRoute; ?>')"></button>
        <?php
        if ($tRoute == "connectionsettingEventAddRespond") {
            $tDisabled     = '';
        } else {
            $tDisabled      = 'readonly';
        }
        ?>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSAPI') ?></label>
                    <select class="form-control" id="oetStaApp" name="oetStaApp">
                        <option class="xWSelectVat" value='1' selected>LMS</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSRespondCode') ?></label>
                    <input type="hidden" id="ohdCheckDuplicateErrCode" name="ohdCheckDuplicateErrCode" value="1">
                    <input type="text" class="form-control" <?= @$tDisabled ?> id="oetErrCode" name="oetErrCode" maxlength="20" value="<?= $FTErrCode; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiErrCode') ?>" data-validate-dublicateCode="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiErrCodedub') ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('interface/connectionsetting/connectionsetting', 'tUMSMessage') ?></label>
                    <textarea class="form-control" rows="4" maxlength="100" id="otaErrDetail" name="otaErrDetail" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiErrDes') ?>"><?php echo $FTErrDescription ?></textarea>
                </div>
            </div>
        </div>


    </form>


    <?php include "script/jConnectionsettingAddRespond.php"; ?>
    <script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>