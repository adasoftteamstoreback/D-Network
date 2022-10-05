
<div class="row xCNavRow" style="width:inherit;">
	<div class="col-xs-12 col-md-8">
		<label id="oliBranchSetWahTitle" 	class="xCNLabelFrm xCNLinkClick" onclick="JSvCallPageBranchSetWah()"  style="cursor:pointer" ><?= language('company/warehouse/warehouse','tWAHSubTitle')?></li>
		<label id="oliBranchSetWahAdd"  	class="active xCNLabelFrm">/ <?= language('company/warehouse/warehouse','tWAHAddWarehouse')?></li>
		<label id="oliBranchSetWahEdit"  	class="active xCNLabelFrm">/ <?= language('company/warehouse/warehouse','tWAHEditWarehouse')?></li>
	</div>
	<div class="col-xs-12 col-md-4 text-right">
		<div class="demo-button xCNBtngroup" style="width:100%;">
			<div id="odvBtnBranchSetWahEditInfo">
				<button type="button" class="btn" style="background-color:#D4D4D4; color:white;" onclick="JSvCallPageBranchSetWah()">
					<?= language('common/main/main','tBack')?>
				</button>
				<?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
					<button type="button" class="btn xCNBTNSubSave" onclick="JSxCallEventSaveAndEdit();">
						<?= language('common/main/main', 'tSave')?>
					</button>
				<?php endif;?>
			</div>
			<div id="odvBtnBranchSetWahInfo">
				<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
					<button class="btn xCNBTNPrimery xCNBTNPrimery2Btn obtChoose" type="submit" data-toggle="modal" data-target="#odlmodaldelete"> <?= language('common/main/main', 'tDelAll')?></button>
					<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageBranchSetWahAdd()">+</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div id="odvContentPageBranchSetWah"></div>

<?php include "script/jBranchsetwah.php"; ?>