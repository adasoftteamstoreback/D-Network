<input id="oetBKOStaBrowse"         type="hidden" value="<?php echo $nBKOBrowseType ?>">
<input id="oetBKOCallBackOption"    type="hidden" value="<?php echo $tBKOBrowseOption ?>">
<input id="oetBKOJumpDocNo"        type="hidden" value="<?= $aParams['tDocNo'] ?>">
<input id="oetBKOJumpBchCode"      type="hidden" value="<?= $aParams['tBchCode'] ?>">
<input id="oetBKOJumpAgnCode"      type="hidden" value="<?= $aParams['tAgnCode'] ?>">

<div id="odvTWXMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliTWXMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docBKO/0/0');?>
                    <li id="oliTWXTitle" style="cursor:pointer;"><?php echo language('document/bookingorder/bookingorder', 'tTWXTitleMenu'); ?></li>
                    <li id="oliTWXTitleAdd" class="active"><a><?php echo language('document/bookingorder/bookingorder', 'tTWXTitleAdd'); ?></a></li>
                    <li id="oliTWXTitleEdit" class="active"><a><?php echo language('document/bookingorder/bookingorder', 'tTWXTitleEdit'); ?></a></li>
                    <li id="oliTWXTitleDetail" class="active"><a><?php echo language('document/bookingorder/bookingorder', 'tTWXTitleDetail'); ?></a></li>
                    <li id="oliTWXTitleAprove" class="active"><a><?php echo language('document/bookingorder/bookingorder', 'tTWXTitleAprove'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvTWXBtnGrpInfo">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                            <button id="obtTWXCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvTWXBtnGrpAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button id="obtTWXCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                <button id="obtTWXPrintDoc" onclick="JSxTWXPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                <button id="obtTWXCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                                <button id="obtTWXApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                <div  id="odvTWXBtnGrpSave" class="btn-group">
                                    <button id="obtTWXSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
                                    <?php echo $vBtnSave ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNTWXBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvTWXContentPageDocument">
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/bookingorder/jBookingorder.js"></script>








