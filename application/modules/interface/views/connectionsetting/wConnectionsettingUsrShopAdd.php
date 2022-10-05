<?php
if ($aResult['rtCode'] == "1") {
    $tBchCode            =   $aResult['raItems']['FTBchCode'];
    $tBchName            =   $aResult['raItems']['FTBchName'];
    $tBchRefID           =   $aResult['raItems']['FTBchRefID'];
    $tCshCostCenter      =   $aResult['raItems']['FTCshCostCenter'];
    $tCshWhTaxCode       =   $aResult['raItems']['FTCshWhTaxCode'];
    $tCshShipTo          =   $aResult['raItems']['FTCshShipTo'];
    $tCshSoldTo          =   $aResult['raItems']['FTCshSoldTo'];

    //route
    $tRoute             = "connectionsettingEventEditUserShop";
} else {
    $tBchCode             =  "";
    $tBchName             =  "";
    $tBchRefID            =  "";
    $tCshCostCenter       =  "";
    $tCshShipTo           =  "";
    $tCshSoldTo           =  "";
    $tCshWhTaxCode        =  "";
    

    //route
    $tRoute             = "connectionsettingEventAddUserShop";
}

?>

<?php if(($aBranchUsed['rtCode'] == 800)){ ?>
    <input type="text" class="form-control xCNHide xWUsedBrc" value="">
<?php }else{ ?>
    <?php foreach ($aBranchUsed['raItems'] as $key => $aValue) { ?>
        <input type="text" class="form-control xCNHide xWUsedBrc"  value="<?= $aValue['FTBchCode'] ?>">
    <?php } ?>
<?php } ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContentUserShop();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
        </button>
        <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
            <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSettingUserShop').click()"> <?php echo  language('common/main/main', 'tSave') ?></button>
        <?php endif; ?>
    </div>

    <form id="ofmAddConnectionSetting" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
        <button style="display:none" type="submit" id="obtSubmitConnectionSettingUserShop" onclick="JSnAddEditConnectionSettingUserShop('<?php echo $tRoute; ?>')"></button>
        <?php
        if ($tRoute == "connectionsettingEventAddUserShop") {
            $tDisabled     = '';
        } else {
            $tDisabled      = 'disabled';
        }
        ?>
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tTBBanch') ?></label>
                <div class="form-group">
                    <div class="input-group"><input type="text" class="form-control xCNHide" id="oetUsrShopBchCode" name="oetUsrShopBchCode" maxlength="20" value="<?= $tBchCode; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetBchName" name="oetBchName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBBanch') ?>" value="<?= $tBchName; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiBanch') ?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabled ?>>
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
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShoppland') ?></label>
                    <input type="text" class="form-control" id="oetCssPlant" name="oetCssPlant" maxlength="100" readonly value="<?= $tBchRefID; ?>">
                    <input type="hidden" id="ohdCssPlant" name="ohdCssPlant" value="<?php echo @$tBchRefID; ?>">
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShopsold') ?></label>
                    <input type="text" class="form-control" id="oetCssShopsold" name="oetCssShopsold" maxlength="20" value="<?= $tCshSoldTo; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShopship') ?></label>
                    <input type="text" class="form-control" id="oetCssShopShip" name="oetCssShopShip" maxlength="20" value="<?= $tCshShipTo; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShopcost') ?></label>
                    <input type="text" class="form-control" id="oetCssShopCost" name="oetCssShopCost" maxlength="20" value="<?= $tCshCostCenter; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShopVat') ?></label>
                    <input type="text" class="form-control" id="oetCssShopVat" name="oetCssShopVat" maxlength="255" value="<?= $tCshWhTaxCode; ?>">
                </div>
            </div>
        </div>


    </form>


    <?php include "script/jConnectionsettingAddUserShop.php"; ?>
    <script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>