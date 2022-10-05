<?php 
if($aResult['rtCode'] == 1){
    $tQGPCode   	= $aResult['raItems']['rtQgpCode'];
    $tQGPName   	= $aResult['raItems']['rtQgpName'];
    $tQGPRemark 	= $aResult['raItems']['rtQgpRmk'];
	$tRoute     	= "qasgroupEventEdit";

	$tQGPAgnCode       = $aResult['raItems']['rtAgnCode'];
    $tQGPAgnName       = $aResult['raItems']['rtAgnName'];


}else{
    $tQGPCode   	= "";
    $tQGPName   	= "";
    $tQGPRemark 	= "";
	$tRoute     	= "qasgroupEventAdd";
	
	$tQGPAgnCode       = $tSesAgnCode;
    $tQGPAgnName       = $tSesAgnName;
}
?>
<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddQasGroup">
	<button style="display:none" type="submit" id="obtSubmitQasGroup" onclick="JSnAddEditQasGroup('<?php echo $tRoute?>')"></button>
	<div class="panel-body"  style="padding-top:20px !important;">
	
		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/qasgroup/qasgroup','tQGPCode')?></label>
				<div id="odvQasGroupAutoGenCode" class="form-group">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbQasGroupAutoGenCode" name="ocbQasGroupAutoGenCode" checked="true" value="1">
							<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
						</label>
					</div>
				</div>

				<div id="odvQasGroupCodeForm" class="form-group">
					<input type="hidden" id="ohdCheckDuplicateQGPCode" name="ohdCheckDuplicateQGPCode" value="1"> 
					<div class="validate-input">
						<input 
							type="text" 
							class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
							maxlength="5" 
							id="oetQGPCode" 
							name="oetQGPCode"
							data-is-created="<?php echo $tQGPCode; ?>"
							placeholder="<?php echo language('service/qasgroup/qasgroup','tQGPCode')?>";
							value="<?php echo $tQGPCode; ?>" 
							data-validate-required = "<?php echo language('service/qasgroup/qasgroup','tQGPValidCode')?>"
							data-validate-dublicateCode = "<?php echo language('service/qasgroup/qasgroup','tQGPValidCodeDup')?>"
						>
					</div>
				</div>
				
			</div>
		</div>

		<?php 
			if($tRoute == "qasgroupEventAdd"){
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
					<label class="xCNLabelFrm"></span><?php echo language('service/qasgroup/qasgroup','tQGPAgency')?></label>	
					<div class="input-group"><input class="form-control xCNHide" type="text" id="oetQGPAgnCode" name="oetQGPAgnCode" maxlength="5" value="<?php echo @$tQGPAgnCode;?>">
						<input type="text" class="form-control xWPointerEventNone" 
							id="oetQGPAgnName" 
							name="oetQGPAgnName" 
							maxlength="100" 
							placeholder ="<?php echo language('service/qasgroup/qasgroup','tQGPAgency');?>"
							value="<?php echo @$tQGPAgnName;?>" readonly>
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
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/qasgroup/qasgroup','tQGPName')?></label>
						<input
							type="text"
							class="form-control"
							maxlength="200"
							id="oetQGPName"
							name="oetQGPName"
							placeholder="<?php echo language('service/qasgroup/qasgroup','tQGPName')?>"
							value="<?php echo $tQGPName?>"
							data-validate-required="<?php echo language('service/qasgroup/qasgroup','tQGPValidName')?>"
							required
						>
					</div>
				</div>			
				<div class="form-group">
					
						<label class="xCNLabelFrm"><?= language('service/qasgroup/qasgroup','tQGPRemark')?></label>
						<textarea class="form-control" rows="4" maxlength="100" id="otaQGPRemark" name="otaQGPRemark"><?php echo $tQGPRemark?></textarea>
				
				</div>
			</div>
		</div>
	</div>
</form>
<?php include "script/jQasGroupAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>