<!-- ใบเคลม -->
<link rel="stylesheet" href="<?=base_url(); ?>application/modules/document/views/receiptpurchasepmt/css/adaClaim.css">

<!-- รองรับการเข้ามาแบบ Noti -->
<input id="oetRPPJumpDocNo" 	type="hidden" value="<?=$aParams['tDocNo'] ?>">
<input id="oetRPPJumpBchCode" 	type="hidden" value="<?=$aParams['tBchCode'] ?>">
<input id="oetRPPJumpAgnCode" 	type="hidden" value="<?=$aParams['tAgnCode'] ?>">
<input id="oetRPPJumpBrwType" 	type="hidden" value="<?=$nRPPBrowseType?>">
<input id="oetRPPJumpBrwOption" type="hidden" value="<?=$tRPPBrowseOption?>">
<div id="odvRPPMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliRPPMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite(@$tRoute);?>
                    <li id="oliRPPTitle" style="cursor:pointer;">
                        <?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleMenu'); ?>
                    </li>
                    <li id="oliRPPTitleAdd"     class="active"><a><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleAdd'); ?></a></li>
                    <li id="oliRPPTitleEdit"    class="active"><a><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleEdit'); ?></a></li>
                    <li id="oliRPPTitleDetail"  class="active"><a><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleDetail'); ?></a></li>
                    <li id="oliRPPTitleAprove"  class="active"><a><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleAprove'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvRPPBtnGrpInfo">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                            <button id="obtRPPCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvRPPBtnGrpAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button id="obtRPPCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack'); ?></button>
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                <button id="obtRPPPrintDoc" onclick="JSxRPPPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tCMNPrint'); ?></button>
                                <button id="obtRPPSaveAndApvDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tSave').'และ'.language('common/main/main', 'tCMNApprove') ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNRPPBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvRPPContentPageDocument"></div>
</div>
<script type="text/javascript" src="<?=base_url(); ?>application/modules/document/assets/src/receiptpurchasepmt/jReceiptPurchasepmt.js"></script>