<div class="panel panel-headline">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-12 cols-sm-2 col-md-4 col-lg-2">
           		<div class="form-group">
              		<label class="xCNLabelFrm"><?=language('service/overduel/overduel','tOdlTypeFinesearch');?></label>
                    <select class="selectpicker form-control" id="ocmSearchProductType" name="ocmSearchProductType" maxlength="1" >
				   						<option class="" value="0"><?=language('service/overduel/overduel','tOdlTypeFinesearch')?></option>
                    	<option class="" value="1"><?=language('service/overduel/overduel','tOdloption1')?></option>
                    	<option class="" value="2"><?=language('service/overduel/overduel','tOdloption2')?></option>
                	</select>
            	</div>
        	</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 ">
				<div class="form-group">
					<label class="xCNLabelFrm"><?php echo language('service/overduel/overduel','tOdlFine')?></label>
					<div class="input-group">
						<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchOdl" name="oetSearchOdl" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
						<span class="input-group-btn">
							<button id="oimSearchOdl" class="btn xCNBtnSearch" type="button">
								<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
							</button>
						</span>
					</div>
				</div>
			</div>
			<?php if($aAlwEventOdl['tAutStaFull'] == 1 || $aAlwEventOdl['tAutStaDelete'] == 1 ) : ?>
				<div class="col-lg-6 col-md-4 col-xs-4 col-sm-4 text-right">
					<div class="form-group">
						<label class="xCNLabelFrm hidden-xs"></label>
						<div >
							<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
								<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
									<?php echo language('common/main/main','tCMNOption')?>
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li id="oliBtnDeleteAll">
										<a data-toggle="modal" data-target="#odvModalDelOdl"><?php echo language('common/main/main','tDelAll')?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="panel-body">
		<section id="ostDataOdl"></section>
	</div>
</div>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<script>
  $(document).ready(function() {
    $('.selectpicker').selectpicker();
	})
	$('#oimSearchOdl').click(function(){
		JCNxOpenLoading();
		JSvOdlDataTable();
	});
	$('#oetSearchOdl').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvOdlDataTable();
	}
	});
</script>
