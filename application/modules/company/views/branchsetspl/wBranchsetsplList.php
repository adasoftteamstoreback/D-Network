
<div class="row">
	<div class="col-xs-8 col-md-4 col-lg-4">
		<div class="form-group"> 
			<label class="xCNLabelFrm"><?= language('company/warehouse/warehouse','tWahSearch')?></label>
			<div class="input-group">
				<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" onkeyup="Javascript:if(event.keyCode==13) JSvBranchSetsplDataTable()" value="" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
				<span class="input-group-btn">
					<button class="btn xCNBtnSearch" type="button" onclick="JSvBranchSetsplDataTable()">
						<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
					</button>
				</span>
			</div>
		</div>
	</div>
	<div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:20px;">
		<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1 ) : ?>
			<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
				<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
				<?= language('common/main/main','tCMNOption')?>
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li id="oliBtnDeleteAll" class="disabled">
						<a href="javascript:;" data-toggle="modal" data-target="#odvModalDelDocMultipleSpl"><?= language('common/main/main','tCMNDeleteAll')?></a>
					</li>
				</ul>
			</div>
		<?php endif; ?>
		<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1 ) : ?>
			<div class="demo-button xCNBtngroup" style="margin-left: 20px; margin-top: -3px;">
				<div id="odvBtnBranchSetsplInfo">
					<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
						<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageBranchSetsplAdd()">+</button>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<section id="ostBranchsetspl" style="margin-top: 10px;"></section>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>