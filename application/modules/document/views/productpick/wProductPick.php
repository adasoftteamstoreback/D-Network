<input id="oetPCKStaBrowse" type="hidden" value="<?= $nBrowseType ?>">
<input id="oetPCKCallBackOption" type="hidden" value="<?= $tBrowseOption ?>">
<input id="oetPCKJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetPCKJumpBchCode" type="hidden" value="<?php echo $aParams['tBchCode'] ?>">
<input id="oetPCKJumpAgnCode" type="hidden" value="<?php echo $aParams['tAgnCode'] ?>">
<div id="odvPCKMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNPCKVMaster">
                <div class="col-xs-12 col-md-6">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('deposit/0/0');?> 
                        <li id="oliPCKTitle" class="xCNLinkClick" onclick="JSvPCKCallPageList()"><?= language('document/productpick/productpick', 'tPCKTitle') ?></li>
                        <li id="oliPCKTitleAdd" class="active"><a><?= language('document/transfer_branch_out/transfer_branch_out', 'tTitleAdd') ?></a></li>
                        <li id="oliPCKTitleEdit" class="active"><a><?= language('document/transfer_branch_out/transfer_branch_out', 'tTitleEdit') ?></a></li>
                        <li id="oliPCKTitleDetail" class="active"><a><?= language('document/purchaseinvoice/purchaseinvoice', 'tPITitleDetail'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvPCKBtnInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvPCKCallPageAdd()">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button onclick="JSvPCKCallPageList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack') ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                                    <button id="obtPCKPrint" onclick="JSxPCKPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint') ?></button>
                                    <button id="obtPCKCancel" onclick="JSvPCKCancel(false)" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel') ?></button>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAppv'] == 1) {?>
                                    <button id="obtPCKApprove" onclick="JSvPCKApprove(false)" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove') ?></button>         
                                    <?php } ?> 
                                    <div class="btn-group">
                                        <button id="obtTFBO" type="button" class="btn xWBtnGrpSaveLeft" onclick="$('#obtPCKSubmit').click()"> <?php echo language('common/main/main', 'tSave') ?></button>
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
</div>

<div class="xCNMenuCump xCNPCKBrowseLine" id="odvMenuCump">&nbsp;</div>

<div class="main-content">
    <div id="odvPCKContentPage"></div>
</div>

<script>
	var tBaseURL		= '<?php echo base_url(); ?>';
	var nOptDecimalShow = '<?php echo $nOptDecimalShow; ?>';
	var nOptDecimalSave = '<?php echo $nOptDecimalSave; ?>';
	var nLangEdits		= '<?php echo $this->session->userdata("tLangEdit"); ?>';
	var tUsrApv			= '<?php echo $this->session->userdata("tSesUsername"); ?>';
</script>

<?php include "script/jProductPick.php"; ?>
