<?php
if ($aResult['rtCode'] == "1") {
    $tBchCode            =   $aResult['raItems']['FTBchCode'];
    $tPosCode            =   $aResult['raItems']['FTPosCode'];
    $tBchName            =   $aResult['raItems']['FTBchName'];
    $tPosName            =   $aResult['raItems']['FTPosName'];
    $tBchRefID           =   $aResult['raItems']['FTBchRefID'];
    $tLmsMID             =   $aResult['raItems']['FTLmsMID'];
    $tLmsTID             =   $aResult['raItems']['FTLmsTID'];
    $tLmsUsr              =  $aResult['raItems']['FTApiLoginUsr'];
    $tLmsApi              =  $aResult['raItems']['FTApiToken'];
    $tLmsPwn              =  $aResult['raItems']['FTApiLoginPwd'];
    $tLmsBlueCard         =  $aResult['raItems']['FTStaBlueCode'];

    //route
    $tRoute             = "connectionsettingEventEditMSShop";
} else {
    $tBchCode             =  "";
    $tBchName             =  "";
    $tPosCode             =   "";
    $tPosName             =   "";
    $tBchRefID            =  "";
    $tLmsMID              =  "";
    $tLmsTID              =  "";
    $tLmsUsr              =  "";
    $tLmsApi              =  "";
    $tLmsPwn              =  "";
    $tLmsBlueCard         =  "";

    //route
    $tRoute             = "connectionsettingEventAddMSShop";
}
?>


<?php if(($aUsedPos['rtCode'] == 800)){ ?>
    <input type="text" class="form-control xCNHide xWUsedBrc" value="">
    <input type="text" class="form-control xCNHide xWUsedPos" value="">
<?php }else{ ?>
    <?php foreach ($aUsedPos['raItems'] as $key => $aValue) { ?>
        <input type="text" class="form-control xCNHide  xWUsedBrc" id="odhUsedBrc<?= $key ?>" name="odhUsedBrc" value="<?= @$aValue['FTBchCode'] ?>">
        <input type="text" class="form-control xCNHide  xWUsedPos" id="odhUsedPos<?= $key ?>" name="odhUsedPos" value="<?= @$aValue['FTPosCode'] ?>">
    <?php } ?>
<?php } ?>

<div class="row">
    <input type="hidden" id="ohdBlueCard" name="ohdBlueCard" value="<?php echo @$tLmsBlueCard; ?>">
    <div class="col-lg-12 col-md-12 col-xs-12   text-right">
        <button type="button" onclick="JSxCallGetContentMSSHOP();" id="obtGpShopCancel" class="btn" style="background-color: #D4D4D4; color: #000000;">
            <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
        </button>
        <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
            <button type="submit" class="btn xCNBTNSubSave" onclick="$('#obtSubmitConnectionSettingUserShop').click()"> <?php echo  language('common/main/main', 'tSave') ?></button>
        <?php endif; ?>
    </div>

    <form id="ofmAddConnectionSettingMSShop" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
        <button style="display:none" type="submit" id="obtSubmitConnectionSettingUserShop" onclick="JSnAddEditConnectionSettingMSShop('<?php echo $tRoute; ?>')"></button>
        <?php
        if ($tRoute == "connectionsettingEventAddMSShop") {
            $tDisabled     = '';
        } else {
            $tDisabled      = 'disabled';
        }
        ?>
        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                        endif; ?>">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tTBBanch') ?></label>
                    <div class="input-group"><input type="text" class="form-control xCNHide" id="oetUsrShopBchCode" name="oetUsrShopBchCode" maxlength="5" value="<?= $tBchCode; ?>">
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
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('company/branch/branch', 'tUsrShoppland') ?></label>
                    <input type="text" class="form-control" id="oetCssPlant" name="oetCssPlant" maxlength="100" readonly value="<?= $tBchRefID; ?>">
                    <input type="hidden" id="ohdMSShopPlant" name="ohdMSShopPlant" value="<?php echo @$tBchRefID; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSMID') ?></label>
                    <input type="text" class="form-control" id="oetMSShopMid" name="oetMSShopMid" maxlength="20" value="<?= $tLmsMID; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiMid') ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSTID') ?></label>
                    <input type="text" class="form-control" id="oetMSShopTid" name="oetMSShopTid" maxlength="20" value="<?= $tLmsTID; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiTid') ?>">
                </div>
            </div>
            <?php if($tRoute == 'connectionsettingEventAddMSShop'){

            }else{?>
            <div class="col-md-5 col-lg-5" style="padding-top: 25px;">
                <button id="obtCallTimeOut" class="btn" style="background-color: #D4D4D4; color: #000000;" onclick="JSnCallTimeOut()">Test Host</button>
            </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                        endif; ?>">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSPosCode') ?></label>
                    <div class="input-group"><input type="text" class="form-control xCNHide" id="oetMSShopPosCode" name="oetMSShopPosCode" maxlength="5" value="<?= $tPosCode; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetMSShopPosName" name="oetMSShopPosName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tUMSPosCode') ?>" value="<?= $tPosName; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiPos') ?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimBrowsePos" type="button" class="btn xCNBtnBrowseAddOn" disabled <?= @$tDisabled ?>>
                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSUserNameT') ?></label>
                    <input type="text" class="form-control" id="oetMSShopUser" autocomplete="off" name="oetMSShopUser" maxlength="50" value="<?= $tLmsUsr; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiUserName') ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSUserPassT') ?></label>
                    <input type="password" class="form-control" id="oetMSShopPassword" autocomplete="new-password" name="oetMSShopPassword" maxlength="50" value="<?= $tLmsPwn; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiPass') ?>">
                    <input type="hidden" class="form-control" id="oetMSShopPasswordEncode" name="oetMSShopPasswordEncode" maxlength="50" value="<?= $tLmsPwn; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiPass') ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSUserSecuT') ?></label>
                    <input type="text" class="form-control" id="oetMSShopApiToken" name="oetMSShopApiToken" maxlength="255" value="<?= $tLmsApi; ?>" data-validate-required="<?php echo language('interface/connectionsetting/connectionsetting', 'tValiURL') ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSBlueSta') ?></label>
                    <select class="form-control" id="oetBlueCardStatus" name="oetBlueCardStatus">
                        <option class="xWSelectBlueCard" value='1'><?php echo language('interface/connectionsetting/connectionsetting', 'tLMSBluecardSta1'); ?></option>
                        <option class="xWSelectBlueCard" value='2'><?php echo language('interface/connectionsetting/connectionsetting', 'tLMSBluecardSta2'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <!-- 
        <div class="row">
            <div class="col-xs-10 col-md-5 col-lg-5">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tUMSTimeOuts') ?></label>
                    <input type="text" class="form-control" id="oetMSShopTime" name="oetMSShopTime" maxlength="100" value="<?= $tTimeout; ?>">
                </div>
            </div>
            <div class="col-md-5 col-lg-5" style="padding-top: 25px;">
                <button id="obtCallTimeOut" class="btn" style="background-color: #D4D4D4; color: #000000;" onclick="JSnCallTimeOut()">Test Host</button>
            </div>
        </div> -->


    </form>

    <div id="odvModalMSShopRespondCode" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard">Return Code</label>
                </div>
                <div class="modal-body">
                    <span id="ospConfirm"> - </span>
                    <input type='hidden' id="ohdConfirmIDDelete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </div>
        </div>
    </div>


    <?php include "script/jConnectionsettingAddMSShop.php"; ?>
    <script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>