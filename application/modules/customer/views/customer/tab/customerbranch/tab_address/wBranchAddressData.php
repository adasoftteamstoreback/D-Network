<div class="row">
    <input type="hidden" id="ohdCstBchAddressBchCode" name="ohdCstBchAddressBchCode" value="<?php echo @$tCstBchCode;?>">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <label id="olbBranchAddressInfo"  class="xCNLabelFrm xCNLinkClick"><?php echo language('company/branch/branch','tBCHAddressTitle');?></label>
        <label id="olbBranchAddressAdd"   class="xCNLabelFrm"><?php echo language('company/branch/branch','tBCHAddressTitleAdd');?></label>
        <label id="olbBranchAddressEdit"  class="xCNLabelFrm"><?php echo language('company/branch/branch','tBCHAddressTitleEdit');?></label>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
        <div class="demo-button xCNBtngroup" style="width:100%;">
            <?php if($aAlwBranchAddress['tAutStaFull'] == 1 || ($aAlwBranchAddress['tAutStaAdd'] == 1 || $aAlwBranchAddress['tAutStaEdit'] == 1)) : ?>
                <div id="odvBranchAddressBtnGrpInfo">
                    <button id="obtBranchAddressCallPageAdd"  onclick="JSvCstBchAddrCallPageAdd();" class="xCNBTNPrimeryPlus" type="button">+</button>
                </div>
            <?php endif; ?>
            <div id="odvBranchAddressBtnGrpAddEdit">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtBranchAddressCancle" onclick="JSvCstBchAddrGetPageList();" type="button" class="btn" style="background-color:#D4D4D4; color:white;">
                        <?php echo language('common/main/main','tCancel')?>
                    </button>
                    <?php if($aAlwBranchAddress['tAutStaFull'] == 1 || ($aAlwBranchAddress['tAutStaAdd'] == 1 || $aAlwBranchAddress['tAutStaEdit'] == 1)) : ?>
                        <button id="obtBranchAddressSave"  onclick="JSxTBAAddUpdateEvent();" type="button" class="btn xCNBTNSubSave">
                            <?php echo language('common/main/main', 'tSave')?>
                        </button>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div id="odvCstBchAddressContent"></div>
</div>



<div class="modal fade" id="odvCstBchAddrModalDel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tModalDelete')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <label><?= language('common/main/main','tModalConfirmDeleteItems')?></label><span id="oetTextComfirmDel"></span>
				<input type='hidden' id="ohdCstBchConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" onClick="JSaTBABchAddrDelete()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">
                        <?php echo language('common/main/main', 'tModalConfirm')?>
				</button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div>
