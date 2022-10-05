<?php 
if($aResult['rtCode'] == 1){
    $tRckCode   = $aResult['raItems']['rtRckCode'];
    $tRckName   = $aResult['raItems']['rtRckName'];
	$tRckRemark = $aResult['raItems']['rtRckRmk'];
	//Route
    $tRoute     = "SHPSmartLockerEventEdit";
}else{
    $tRckCode   = "";
    $tRckName   = "";
	$tRckRemark = "";
	//Route
    $tRoute     = "SHPSmartLockerEventAdd";
}

?>
<style>
    .NumberDuplicate{
        font-size   : 15px !important;
        color       : red;
        font-style  : italic;
    }

    .xCNSearchpadding{
        padding     : 0px 3px;
    }
</style>

<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddRac">
	<div class="panel-body">

	<input type="hidden" name="oetRackRouteUrl" id="oetRackRouteUrl" value="<?=$tRoute?>">
    <button class="btn btn-default xCNHide" id="obtSubmitRack" type="submit" onclick="JSnAddEditRack('<?php echo $tRoute ?>')">
    <i class="fa fa-floppy-o"></i> <?php echo language('common/main/main', 'tSave')?>
	</button>



	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row">
			<!-- Add image -->


			<!-- End GenCode -->
			<div class="col-lg-6 col-md-6 col-xs-6">
				<div class="form-group">
				<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('company/rack/rack','tRacCode')?></label>
						<div id="odvRacAutoGenCode" class="form-group">
							<div class="validate-input">
								<label class="fancy-checkbox">
								<input type="checkbox" id="ocbRacAutoGenCode" name="ocbRacAutoGenCode" checked="true" value="1">
								<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
							</label>
						</div>
					</div>
					<div id="odvRacCodeForm" class="form-group">
						<input type="hidden" id="ohdCheckDuplicateRacCode" name="ohdCheckDuplicateRacCode" value="1"> 
							<div class="validate-input">
							<input 
								type="text" 
								class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
								maxlength="5" 
								id="oetRacCode" 
								name="oetRacCode"
								data-is-created="<?php echo $tRckCode; ?>"
								placeholder="<?php echo language('company/rack/rack','tRacCode')?>"
								value="<?php echo $tRckCode; ?>" 
								data-validate-required = "<?php echo language('company/rack/rack','tRacValidCode')?>"
								data-validate-dublicateCode = "<?php echo language('company/rack/rack','tRacValidCodeDup')?>"
							>
						</div>
					</div>
				
				<div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('company/rack/rack','tRacName')?></label>
						<input
							type="text"
							class="form-control"
							maxlength="200"
							id="oetRacName"
							name="oetRacName"
							placeholder="<?php echo language('company/rack/rack','tRacName')?>"
							autocomplete="off"
							value="<?php echo $tRckName?>"
							data-validate-required="<?php echo language('company/rack/rack','tRacValidName')?>"
						>
					</div>
				</div>
				<div class="form-group">
				<label class="xCNLabelFrm"><?= language('company/rack/rack','tRacRemark')?></label>
							<textarea class="form-control" rows="4"  maxlength="100" id="otaRacRemark" name="oetRacRemark"><?php echo $tRckRemark?></textarea>
				</div>
				
			</div>
		</div>
	</div>
	</div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include "script/jRackAdd.php"; ?>
