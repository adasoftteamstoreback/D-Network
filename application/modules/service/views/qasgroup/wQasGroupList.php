<?php 
// echo '<pre>';
// echo print_r($aAlwEventQasGroup); 
// echo '</pre>';
?>



<div class="panel-heading">
<div class="row">
		<div class="col-lg-6 col-md-6 col-xs-12 col-sm-8">
			<div class="form-group">
				<label class="xCNLabelFrm"><?php echo language('service/qasgroup/qasgroup','tQGPTBCSearch')?></label>
				<div class="input-group">
					<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvQgpGroupDataTable()" autocomplete="off" name="oetSearchAll" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
					<span class="input-group-btn">
						<button id="oimSearchCard" class="btn xCNBtnSearch" type="button">
							<img onclick="JSvQgpGroupDataTable()" class="xCNIconBrowse" src="<?= base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
						</button>
					</span>
				</div>
			</div>
		</div>
		<?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
			<div class="col-lg-6 col-md-6 col-xs-12 col-sm-4 text-right">
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
						<a data-toggle="modal" data-target="#odvModalDelQasGroup"><?= language('common/main/main','tDelAll')?></a>
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
	<section id="ostDataQasGroup"></section>
</div>

<div class="modal fade" id="odvModalDelQasGroupChoose">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="display:inline-block"><?=language('common/main/main', 'tModalDelete')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> <?php echo language('common/main/main', 'tModalDelete')?> </span>
				<input type='hidden' id="ospConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnQasGroupDel()">
					<i class="fa fa-check-circle" aria-hidden="true"></i> <?=language('common/main/main', 'tModalConfirm')?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<i class="fa fa-times-circle" aria-hidden="true"></i> <?=language('common/main/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
	$('#oimSearchCard').click(function(){
		JCNxOpenLoading();
		JSvQgpGroupDataTable();
	});
	$('#oetSearchAll').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvQgpGroupDataTable();
		}
	});
</script>