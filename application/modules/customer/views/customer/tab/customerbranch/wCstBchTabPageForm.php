<?php
	if($aResult['rtCode']=='1'){
		$tRoute 			= "customerBchEventEdit";
		$nCbrSeq 			=	$aResult['raItems']['rnCbrSeq'];
		$tCstBchCode  		=	$aResult['raItems']['FTCbrBchCode'];
		$tCstBchName  		=	$aResult['raItems']['FTCbrBchName'];
		$nCstBchRegNo 		=	$aResult['raItems']['FTCbrRegNo'];
		$nCstBchShipTo 		=	$aResult['raItems']['FTCbrShipTo'];
		$nCstBchSoldto 		=	$aResult['raItems']['FTCbrSoldTo'];
		$nCstBchStatus 		=	$aResult['raItems']['FTCbrStatus'];
		$tPageEvent 		=	language('customerlicense/customerlicense/customerlicense','tCLBPageEdit');
		$tCreateBy 			= '';
		$tUpdateBy 			= $this->session->userdata('tSesUsrUsername');
		if($tCstBchCode != ''){
		$tabDisabled 		= '';
		$dataToggle 		= 'tab';  
		$tServerDisabled 	= 'disabled';
		}else{
		$tabDisabled 		= 'disabled';
		$tServerDisabled 	= 'disabled';
		$dataToggle 		= 'false';  
		}
	}else{
		$tRoute 			= "customerBchEventAdd";
		$nCbrSeq 			=	'';
		$tCstBchCode  		=	'';
		$tCstBchName  		=	'';
		$nCstBchRegNo 		=	'';
		$nCstBchShipTo 		= '';	
		$nCstBchStatus 		= '';	
		$nCstBchSoldto 		= '';
		$tPageEvent 		=	language('customerlicense/customerlicense/customerlicense','tCLBPageAdd');
		$tabDisabled 		= 'disabled';
		$tServerDisabled 	= '';
		$dataToggle 		= 'false'; 
		$tCreateBy 			= $this->session->userdata('tSesUsrUsername');
		$tUpdateBy 			= '';
	}
?>

<div class="row">
	<div class="col-xl-12 col-lg-12">
		<div class="custom-tabs-line tabs-line-bottom left-aligned">
			<div class="row">
				<div id="odvNavMenuTab" class="col-xl-12 col-lg-12">
			
					<input type="hidden" id="ohdNavActiveTab" value="oliBchInfo1">

					<ul class="nav" role="tablist" data-typetab="main" data-tabtitle="Bchinfo">
						<li id="oliBchInfo1" class="xWMenu active">
							<a 
								role="tab" 
								data-toggle="tab" 
								data-target="#odvBchTabInfo1"
								aria-expanded="true">ข้อมูลทั่วไป</a>
						</li>
						<li id="oliCstBchAddr" class="xWMenu <?=$tabDisabled?>" data-typetab="main" data-tabtitle="bchaddr">
							<a  
								role="tab" 
								data-toggle="<?=$dataToggle?>" 
								data-target="#odvBchTabAddr"
								aria-expanded="false"><?php echo language('customerlicense/customerlicense/customerlicense','tCBLBchAddr')?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<form id="ofmCstBchFormAdd" action="javascript:void(0)" method="post" enctype="multipart/form-data">
	<input type="hidden" name="ohdCstCode" id="ohdCstCode" value="<?=$tCstCode?>">
	<input type="hidden" name="ohdCstBchSeq" id="ohdCstBchSeq" value="<?=$nCbrSeq?>" >
	<input type="hidden" name="ohdCstBchRoute" id="ohdCstBchRoute" value="<?=$tRoute?>" >
	<input type="hidden" name="ohdCstBchStatus" id="ohdCstBchStatus" value="<?=$nCstBchStatus?>" >
	<input type="hidden" name="ohdCstBchCreateBy" id="ohdCstBchCreateBy" value="<?=$tCreateBy?>" >
	<input type="hidden" name="ohdCstBchUpdateBy" id="ohdCstBchUpdateBy" value="<?=$tUpdateBy?>" >
	<input type="hidden" name="ohdCstBchCode" id="ohdCstBchCode" value="<?=$tCstBchCode?>">

	<div class="tab-content">
		<div id="odvBchTabInfo1" class="tab-pane fade active in">
			<div class="">
				<div class="row">
					<div class="col-xs-8 col-md-6 col-lg-6">
						<div class="form-group"> 
							<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('customer/customer/customer','tCSTBranchCode')?></label>
								<input type="text" name="oetCstBchCode_InTab" id="oetCstBchCode_InTab" class="form-control" placeholder="<?= language('customer/customer/customer','tCSTBranchCode')?>"  maxlength="5" autocomplete="off"  value="<?=$tCstBchCode?>">
						</div>
						<div class="form-group"> 
							<label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTBranchName')?></label>
							<input type="text" name="oetCstBchName_InTab" id="oetCstBchName_InTab" placeholder="<?= language('customer/customer/customer','tCSTBranchName')?>" maxlength="50" autocomplete="off" class="form-control" value="<?=$tCstBchName?>">
						</div>
						<div class="form-group">
							<label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTBranchRegNo')?></label>
							<input type="text" name="oetCstBchRegNo" id="oetCstBchRegNo" placeholder="<?= language('customer/customer/customer','tCSTBranchRegNo')?>" maxlength="30" autocomplete="off" class="form-control" value="<?=$nCstBchRegNo?>">
						</div>

						<div class="form-group">
							<label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTBranchSoldto')?></label>
							<input type="text" name="oetCstBchSoldTo" id="oetCstBchSoldTo" placeholder="<?= language('customer/customer/customer','tCSTBranchSoldto')?>" maxlength="20"autocomplete="off" class="form-control" value="<?=$nCstBchSoldto?>">
						</div>

						<div class="form-group">
							<label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTBranchShipto')?></label>
							<input type="text" name="oetCstBchShipTo" id="oetCstBchShipTo" placeholder="<?= language('customer/customer/customer','tCSTBranchShipto')?>" maxlength="20" autocomplete="off" class="form-control" value="<?=$nCstBchShipTo?>">
						</div>
						
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCstStaActive');?></label>
							<select class="selectpicker xWDODisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmCstBchStatus" name="ocmCstBchStatus" maxlength="1">  
								<option value="1" selected><?php echo language('customer/customer/customer','tCstLActive');?></option>
								<option value="2" ><?php echo language('customer/customer/customer','tCstLInactive');?></option>
							</select>
						</div>
					</div>

					<div class="col-xs-6 col-md-6 col-lg-6" style="margin-top: 25px;">
						<button type="button" class="btn btn-primary" onclick="JSxCstBchAddUpdateEvent()" style="float: right;" ><?= language('common/main/main','tSave')?></button>
						<button type="button" class="btn btn-default" onclick="JSvCstBchGetPageList();" style="background-color: #D4D4D4; color: white; float: right;  margin-right: 5px;" ><?= language('common/main/main','tCancel')?></button>  
					</div>
				</div>
			</div>
		</div>
	
		<?php include "tab_address/wBranchAddress.php"; ?>

	</div>
</form>

<script>
	$(document).ready(function() {
		$('.selectpicker').selectpicker('refresh');
	});
</script>

<?php Include 'tab_address/script/jBranchAddress.php'; ?>