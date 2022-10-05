<?php
if ($aResult['rtCode'] == "1") {
    $tMapCode          =   $aResult['raItems']['FTMapCode'];
    $tMapName          =   $aResult['raItems']['FTMapName'];
    $tMapSeqNo         =   $aResult['raItems']['FNMapSeqNo'];
    $tMapDefValue      =   $aResult['raItems']['FTMapDefValue'];
    $tMapUsrValue      =   $aResult['raItems']['FTMapUsrValue'];
    $tFTMapType        =   $aResult['raItems']['FTMapType'];
    $tFTRcvName        =   $aResult['raItems']['FTRcvName'];

    $tRoute             = "connectionsettingEventEditMapping";

    // echo print_r($tMapDefValue);
}
?>


<div class="row">
    <!--ปุ่มตัวเลือก กับ ปุ่มเพิ่ม-->
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContentMapping();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
        </button>
        <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
            <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSettingMapping').click()"> <?php echo  language('common/main/main', 'tSave') ?></button>
        <?php endif; ?>
    </div>


    <form id="ofmAddConnectionSettingMapping" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
        <button style="display:none" type="submit" id="obtSubmitConnectionSettingMapping" onclick="JSnAddEditConnectionSettingMapping('<?php echo $tRoute; ?>')"></button>
        <?php
        $tDisabled      = 'disabled';
        if ($tFTMapType == '2') {
            $tDisabled2     = '';
        } else {
            $tDisabled2     = 'disabled';
        }
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <input type="hidden" id="ohdMpCode" name="ohdMpCode" value="<?php echo @$tMapCode; ?>">
                <input type="hidden" id="ohdMpSeqNo" name="ohdMpSeqNo" value="<?php echo @$tMapSeqNo; ?>">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tMPCode') ?></label>
                    <input type="text" class="form-control" <?= @$tDisabled ?> id="oetMPCode" name="oetMPCode" maxlength="25" value="<?= $tMapCode; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPName') ?></label>
                    <input type="text" class="form-control" id="oetMPName" name="oetMPName" maxlength="100" value="<?= $tMapName; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPOrder') ?></label>
                    <input type="text" class="form-control" id="oetMPOrder" name="oetMPOrder" <?= @$tDisabled ?> value="<?= $tMapSeqNo; ?>">
                </div>
            </div>
        </div>
        <?php
        if ($tFTRcvName == '') {
            $tFTRcvName = $tMapDefValue;
        }
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                        endif; ?>">
                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPComparison') ?></label>
                    <div class="input-group"><input type="text" class="form-control xCNHide" id="oetMPCompar" name="oetMPCompar" maxlength="100" value="<?= $tMapDefValue; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetMPComparName" name="oetMPComparName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tMPComparison') ?>" value="<?= $tFTRcvName; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiAgency') ?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowseRcv" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabled2 ?>>
                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tMPActive') ?></label>
                    <input type="text" class="form-control" id="oetMPActive" name="oetMPActive" maxlength="100" value="<?= $tMapUsrValue; ?>">
                </div>
            </div>
        </div>
    </form>


    <?php include "script/jConnectionsettingAddMapping.php"; ?>
    <script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>