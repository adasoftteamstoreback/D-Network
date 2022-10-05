<div class="panel-heading">
<div class="row">
		<div class="col-lg-4 col-md-4 col-xs-12 col-sm-8">
			<div class="form-group">
				<label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAISearch')?></label>
				<div class="input-group">
					<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvCAICarInfoDataTable()" autocomplete="off" name="oetSearchAll" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
					<span class="input-group-btn">
						<button id="oimSearchCard" class="btn xCNBtnSearch" type="button">
							<img onclick="JSvCAICarInfoDataTable()" class="xCNIconBrowse" src="<?= base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
						</button>
					</span>
				</div>
			</div>
		</div>
		<?php if($aAlwEventCarInfo['tAutStaFull'] == 1 || ($aAlwEventCarInfo['tAutStaAdd'] == 1 || $aAlwEventCarInfo['tAutStaEdit'] == 1)) : ?>
			<div class="col-lg-8 col-md-8 col-xs-12 col-sm-4 text-right">
				<div class="form-group">
					<label class="xCNLabelFrm hidden-xs" wfd-id="732"></label>
					<div>
						<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
							<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
								<?= language('common/main/main','tCMNOption')?>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li id="oliBtnDeleteAll" class="disabled">
									<a data-toggle="modal" data-target="#odvModalDelCarInfo"><?= language('common/main/main','tDelAll')?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?php endif;?>
	</div>
</div>
<div class="panel-body">
	<section id="ostDataCarinfo"></section>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
	$('#oimSearchCard').click(function(){
		JCNxOpenLoading();
		JSvCAICarInfoDataTable();
	});
	$('#oetSearchAll').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvCAICarInfoDataTable();
		}
	});
</script>