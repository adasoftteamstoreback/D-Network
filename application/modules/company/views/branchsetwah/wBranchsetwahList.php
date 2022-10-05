
<div class="row">
	<div class="col-xs-8 col-md-4 col-lg-4">
		<div class="form-group"> 
			<label class="xCNLabelFrm"><?= language('company/warehouse/warehouse','tWahSearch')?></label>
			<div class="input-group">
				<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" onkeyup="Javascript:if(event.keyCode==13) JSvBranchSetWahDataTable()" value="" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
				<span class="input-group-btn">
					<button class="btn xCNBtnSearch" type="button" onclick="JSvBranchSetWahDataTable()">
						<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
					</button>
				</span>
			</div>
		</div>
	</div>
</div>

<section id="ostBranchsetWah" style="margin-top: 10px;"></section>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>