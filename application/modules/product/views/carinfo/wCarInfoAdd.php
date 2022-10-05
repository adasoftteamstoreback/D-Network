<?php
if ($aResult['rtCode'] == 1) {
	$tCaiCode   	= $aResult['raItems']['rtCaiCode'];
	$tCaiName   	= $aResult['raItems']['rtCaiName'];
	$tCaiRemark 	= $aResult['raItems']['rtCaiRmk'];
	$tRoute     	= "carinfoEventEdit";
	$tCaiStatus 	= $aResult['raItems']['rtCaiStaUse'];

	$tCAIAgnCode       = $aResult['raItems']['rtAgnCode'];
	$tCAIAgnName       = $aResult['raItems']['rtAgnName'];


	$tCAIType       = $nCarType;

	$tCAIBrandCode       = $aResult['raItems']['rtBrandCode'];
	$tCAIBrandName       = $aResult['raItems']['rtBrandName'];

} else {
	$tCaiCode   			= "";
	$tCaiName   			= "";
	$tCaiRemark 			= "";
	$tRoute     			= "carinfoEventAdd";
	$tCaiStatus 			= "";

	$tCAIBrandCode       	= "";
	$tCAIBrandName       	= "";

	$tCAIAgnCode    		= $tSesAgnCode;
	$tCAIAgnName    		= $tSesAgnName;
	$tCAIType       		= $nCarType;
}
?>
<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCarInfo">
	<button style="display:none" type="submit" id="obtSubmitCarInfo" onclick="JSnAddEditCarInfo('<?php echo $tRoute ?>')"></button>
	<div class="panel-body" style="padding-top:20px !important;">

		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('product/carinfo/carinfo', 'tCAICode' . $tCAIType) ?></label>
				<div id="odvCarInfoAutoGenCode" class="form-group">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbCarInfoAutoGenCode" name="ocbCarInfoAutoGenCode" checked="true" value="1">
							<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
						</label>
					</div>
				</div>

				<div id="odvCarInfoCodeForm" class="form-group">
					<input type="hidden" id="ohdCheckDuplicateCaiCode" name="ohdCheckDuplicateCaiCode" value="1">
					<input type="hidden" id="ohdCaiType" name="ohdCaiType" value="<?php echo $nCarType ?>">
					<div class="validate-input">
						<input type="text" class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" maxlength="5" id="oetCAICode" name="oetCAICode" data-is-created="<?php echo $tCaiCode; ?>" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAICode' . $tCAIType) ?>" ; value="<?php echo $tCaiCode; ?>" data-validate-required="<?php echo language('product/carinfo/carinfo', 'tCAIValidCode') ?>" data-validate-dublicateCode="<?php echo language('product/carinfo/carinfo', 'tCAIValidCodeDup') ?>">
					</div>
				</div>

			</div>
		</div>

		<?php
		if ($tRoute == "carinfoEventAdd") {
			$tDisabled     = '';
			$tNameElmIDAgn = 'oimBrowseAgn';
			$tNameElmIDBrand = 'oimBrowseBrand';
		} else {
			$tDisabled      = '';
			$tNameElmIDAgn  = 'oimBrowseAgn';
			$tNameElmIDBrand = 'oimBrowseBrand';
		}
		?>

		<!-- เพิ่ม Browser AD  -->



		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<?php if ($tCAIType == 3) { ?>
					<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
											endif; ?>">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/carinfo/carinfo', 'tCAITitle2') ?></label>
						<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCaiBrandCode" name="oetCaiBrandCode" maxlength="5" value="<?php echo $tCAIBrandCode ?>">
							<input type="hidden" id="ohdCaiAgnCodeTmp" name="ohdCaiAgnCodeTmp" value="">
							<input type="text" class="form-control xWPointerEventNone" id="oetCaiBrandName" name="oetCaiBrandName" maxlength="100" data-validate-required="<?php echo language('product/carinfo/carinfo', 'tCAIValidBrand') ?>" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAITitle2'); ?>" value="<?php echo $tCAIBrandName ?>" readonly>
							<span class="input-group-btn">
								<button id="<?= @$tNameElmIDBrand; ?>" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
									<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
								</button>
							</span>
						</div>
					</div>
				<?php } ?>

				<div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/carinfo/carinfo', 'tCAIName' . $nCarType) ?></label>
						<input type="text" class="form-control" maxlength="100" id="oetCAIName" name="oetCAIName" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAIName' . $nCarType) ?>" value="<?php echo $tCaiName ?>" data-validate-required="<?php echo language('product/carinfo/carinfo', 'tCAIValidName') ?>">
					</div>
				</div>
				<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
										endif; ?>">
					<label class="xCNLabelFrm"></span><?php echo language('product/carinfo/carinfo', 'tCAIAgency') ?></label>
					<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCaiAgnCode" name="oetCaiAgnCode" maxlength="5" value="<?php echo @$tCAIAgnCode; ?>">
						<input type="hidden" id="ohdCaiAgnCodeTmp" name="ohdCaiAgnCodeTmp" value="<?php echo $tCAIAgnCode ?>">
						<input type="text" class="form-control xWPointerEventNone" id="oetCaiAgnName" name="oetCaiAgnName" maxlength="100" placeholder="<?php echo language('product/carinfo/carinfo', 'tCAIAgency'); ?>" value="<?php echo @$tCAIAgnName; ?>" readonly>
						<span class="input-group-btn">
							<button id="<?= @$tNameElmIDAgn; ?>" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
								<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
							</button>
						</span>
					</div>
				</div>

				
				<div class="form-group">
					<label class="xCNLabelFrm"><?= language('product/carinfo/carinfo', 'tCAIRemark') ?></label>
					<textarea class="form-control" rows="4" maxlength="100" id="otaCAIRemark" name="otaCAIRemark"><?php echo $tCaiRemark ?></textarea>
				</div>
			</div>
		</div>





		<div id="odvCalendarStatus" class="form-group">
			<input type="hidden" id="ohdCheckStatus" name="ohdCheckStatus" value="<?php echo $tCaiStatus ?>">
			<div class="validate-input">
				<label class="fancy-checkbox">
					<input type="checkbox" id="ocbCarInfoStatus" name="ocbCarInfoStatus" checked="true" value="1">
					<span> <?php echo language('product/carinfo/carinfo', 'tCAIStatus'); ?></span>
				</label>
			</div>
		</div>

	</div>
</form>
<?php include "script/jCarinfoAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>