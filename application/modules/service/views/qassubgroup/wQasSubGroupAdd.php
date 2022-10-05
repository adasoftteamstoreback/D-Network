<?php 
if($aResult['rtCode'] == 1){
    $tQSGCode   	= $aResult['raItems']['rtQsgCode'];
    $tQSGName   	= $aResult['raItems']['rtQsgName'];
    $tQSGRemark 	= $aResult['raItems']['rtQsgRmk'];
	$tRoute     	= "qassubgroupEventEdit";

	$tQSGAgnCode       = $aResult['raItems']['rtAgnCode'];
    $tQSGAgnName       = $aResult['raItems']['rtAgnName'];


}else{
    $tQSGCode   	= "";
    $tQSGName   	= "";
    $tQSGRemark 	= "";
	$tRoute     	= "qassubgroupEventAdd";
	
	$tQSGAgnCode       = $tSesAgnCode;
    $tQSGAgnName       = $tSesAgnName;
}
?>
<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddQasSubGroup">
	<button style="display:none" type="submit" id="obtSubmitQasSubGroup" onclick="JSnAddEditQasSubGroup('<?php echo $tRoute?>')"></button>
	<div class="panel-body"  style="padding-top:20px !important;">
	
		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/qassubgroup/qassubgroup','tQSGCode')?></label>
				<div id="odvQasSubGroupAutoGenCode" class="form-group">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbQasSubGroupAutoGenCode" name="ocbQasSubGroupAutoGenCode" checked="true" value="1">
							<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
						</label>
					</div>
				</div>

				<div id="odvQasSubGroupCodeForm" class="form-group">
					<input type="hidden" id="ohdCheckDuplicateQSGCode" name="ohdCheckDuplicateQSGCode" value="1"> 
					<div class="validate-input">
						<input 
							type="text" 
							class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
							maxlength="5" 
							id="oetQSGCode" 
							name="oetQSGCode"
							data-is-created="<?php echo $tQSGCode; ?>"
							placeholder="<?php echo language('service/qassubgroup/qassubgroup','tQSGCode')?>";
							value="<?php echo $tQSGCode; ?>" 
							data-validate-required = "<?php echo language('service/qassubgroup/qassubgroup','tQSGValidCode')?>"
							data-validate-dublicateCode = "<?php echo language('service/qassubgroup/qassubgroup','tQSGValidCodeDup')?>"
						>
					</div>
				</div>
				
			</div>
		</div>

		<?php 
			if($tRoute == "qassubgroupEventAdd"){
				$tDisabled     = '';
				$tNameElmIDAgn = 'oimBrowseAgn';
			}else{
				$tDisabled      = '';
				$tNameElmIDAgn  = 'oimBrowseAgn';
			}
		?>

		<!-- เพิ่ม Browser AD  -->

		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<div class="form-group  <?php if( !FCNbGetIsAgnEnabled()) : echo 'xCNHide';  endif;?>">
					<label class="xCNLabelFrm"></span><?php echo language('service/qassubgroup/qassubgroup','tQSGAgency')?></label>	
					<div class="input-group"><input class="form-control xCNHide" type="text" id="oetQSGAgnCode" name="oetQSGAgnCode" maxlength="5" value="<?php echo @$tQSGAgnCode;?>">
						<input type="text" class="form-control xWPointerEventNone" 
							id="oetQSGAgnName" 
							name="oetQSGAgnName" 
							maxlength="100" 
							placeholder ="<?php echo language('service/qassubgroup/qassubgroup','tQSGAgency');?>"
							value="<?php echo @$tQSGAgnName;?>" readonly>
						<span class="input-group-btn">
							<button id="<?=@$tNameElmIDAgn;?>" type="button" class="btn xCNBtnBrowseAddOn <?=@$tDisabled?>">
								<img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
							</button>
						</span>
					</div>
				</div>	
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/qassubgroup/qassubgroup','tQSGName')?></label>
						<input
							type="text"
							class="form-control"
							maxlength="200"
							id="oetQSGName"
							name="oetQSGName"
							placeholder="<?php echo language('service/qassubgroup/qassubgroup','tQSGName')?>"
							value="<?php echo $tQSGName?>"
							data-validate-required="<?php echo language('service/qassubgroup/qassubgroup','tQSGValidName')?>"
							required
						>
					</div>
				</div>			
				<div class="form-group">
					
						<label class="xCNLabelFrm"><?= language('service/qassubgroup/qassubgroup','tQSGRemark')?></label>
						<textarea class="form-control" rows="4" maxlength="100" id="otaQSGRemark" name="otaQSGRemark"><?php echo $tQSGRemark?></textarea>
				
				</div>
			</div>
		</div>
	</div>
</form>
<?php include "script/jQasSubGroupAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>