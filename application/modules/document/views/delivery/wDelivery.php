<input id="oetDLVStaBrowse"         type="hidden" value="<?=$nDLVBrowseType ?>">
<input id="oetDLVCallBackOption"    type="hidden" value="<?=$tDLVBrowseOption ?>">

<div id="odvDLVMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliDLVMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docDLV/0/0');?>
                    <li id="oliDLVTitle" style="cursor:pointer;" onclick="JSvDLVCallPageList('')"><?=language('document/delivery/delivery', 'tDLVTitleMenu'); ?></li>
                    <li id="oliDLVTitleAdd" class="active"><a><?=language('document/delivery/delivery', 'tDLVTitleAdd'); ?></a></li>
                    <li id="oliDLVTitleEdit" class="active"><a><?=language('document/delivery/delivery', 'tDLVTitleEdit'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvDLVBtnGrpInfo">
                        <?php
                        $aAlwEvent['tAutStaFull'] = 1;
                        if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                            <button id="obtDLVCallPageAdd" class="xCNBTNPrimeryPlus" type="button" onclick="JSvDLVCallPageAddDoc();">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvDLVBtnGrpAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button id="obtDLVCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack'); ?></button>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaPrint'] == 1)): ?>
                                <button id="obtDLVPrintDoc" onclick="JSxDLVPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tCMNPrint'); ?></button>
                            <?php endif; ?>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaCancel'] == 1)): ?>
                                <button id="obtDLVCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tCancel'); ?></button>
                            <?php endif; ?>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAppv'] == 1)): ?>
                                <button id="obtDLVApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?=language('common/main/main', 'tCMNApprove'); ?></button>
                            <?php endif; ?>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                <div  id="odvDLVBtnGrpSave" class="btn-group">
                                    <button id="obtDLVSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?=language('common/main/main', 'tSave'); ?></button>
                                    <?=$vBtnSave ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNDLVBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvDLVContentPageDocument"></div>
</div>

<script type="text/javascript" src="<?=base_url(); ?>application/modules/document/assets/src/delivery/jDelivery.js"></script>
