<?php
if ($aResult['rtCode'] == "1") {
    $tCarRegNo          =   $aResult['raItems']['FTCarRegNo'];
    $tCstName          =   $aResult['raItems']['FTCstName'];
    $tCbaIO            =   $aResult['raItems']['FTCbaIO'];
    $tCbaCostCenter    =   $aResult['raItems']['FTCbaCostCenter'];
    $tCbaStaTax       =   $aResult['raItems']['FTCbaStaTax'];

    //route
    $tRoute             = "connectionsettingEventEditCarInter";
} else {

    $tCarRegNo         =   "";
    $tCstName          =   "";
    $tCbaIO            =   "";
    $tCbaCostCenter    =   "";
    $tCbaStaTax       =   "2";

    //route
    $tRoute             = "connectionsettingEventAddCarInter";
}
// echo print_r($aResult);
?>
<?php foreach ($aCarUsed['raItems'] as $key => $aValue) { ?>
    <input type="text" class="form-control xCNHide xWUsedcar" id="odhUsedcar" name="odhUsedcar" value="<?= $aValue['FTCarRegNo'] ?>">
<?php } ?>
<input type="hidden" id="ohdStaTax" name="ohdStaTax" value="<?php echo @$tCbaStaTax; ?>">
<div class="row">
    <!--ปุ่มตัวเลือก กับ ปุ่มเพิ่ม-->
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContentCarInter();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
        </button>
        <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
            <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSettingCarInter').click()"> <?php echo  language('common/main/main', 'tSave') ?></button>
        <?php endif; ?>
    </div>

    <form id="ofmAddConnectionSettingCarInter" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
        <button style="display:none" type="submit" id="obtSubmitConnectionSettingCarInter" onclick="JSnAddEditConnectionSettingCarInter('<?php echo $tRoute; ?>')"></button>
        <?php
        if ($tRoute == "connectionsettingEventAddCarInter") {
            $tDisabled     = '';
            $tTaxSelect = 'selected';
        } else {
            $tDisabled      = 'disabled';
            $tTaxSelect = '';
        }
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                        endif; ?>">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUsrCarID') ?></label>
                    <div class="input-group"><input type="text" class="form-control xCNHide" id="oetCssCarID" name="oetCssCarID" maxlength="30" value="<?= $tCarRegNo; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetCssCarName" name="oetCssCarName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tUsrCarID') ?>" value="<?= $tCarRegNo; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiCar') ?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowseCar" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabled ?>>
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
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tCarOwner') ?></label>
                    <input type="text" class="form-control" id="oetCssCarOwner" name="oetCssCarOwner" maxlength="100" readonly value="<?= $tCstName; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tIOCode') ?></label>
                    <input type="text" class="form-control" id="oetIOCode" name="oetIOCode" maxlength="20" value="<?= $tCbaIO; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrCostCode') ?></label>
                    <input type="text" class="form-control" id="oetCssCostCode" name="oetCssCostCode" maxlength="20" value="<?= $tCbaCostCenter; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tVatStatu') ?></label>
                    <select class="form-control" id="oetVatStatus" name="oetVatStatus">
                        <option class="xWSelectVat" value='1'><?php echo language('interface/connectionsetting/connectionsetting', 'tVatSta1'); ?></option>
                        <option class="xWSelectVat" value='2' <?= @$tTaxSelect ?>><?php echo language('interface/connectionsetting/connectionsetting', 'tVatSta2'); ?></option>
                    </select>
                </div>
            </div>
        </div>


    </form>
    
    <?php include "script/jConnectionsettingAddCarInter.php"; ?>
    <script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>