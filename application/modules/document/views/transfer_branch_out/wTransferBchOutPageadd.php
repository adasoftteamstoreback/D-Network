<style type="text/css">
    fieldset.scheduler-border {
        border      : 1px groove #ffffffa1 !important;
        padding     : 0 20px 20px 20px !important;
        margin      : 0 0 10px 0 !important;
    }

    legend.scheduler-border {
        text-align      : left !important;
        width           : auto;
        padding         : 0 5px;
        border-bottom   : none;
        font-weight     : bold;
    }

	.xCNBTNPrimeryCusPlus {
		border-radius: 50%;
		float: right;
		width: 30px;
		height: 30px;
		line-height: 30px;
		background-color: #1866ae;
		text-align: center;
		margin-top: 8px;
		/* margin-right: -15px; */
		font-size: 29px;
		color: #ffffff;
		cursor: pointer;
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		-ms-border-radius: 50%;
		-o-border-radius: 50%;
	}
	.fancy-checkbox {
		display: inline-block;
		font-weight: normal;
		width: 120px;
	}
	.xCNTransferBchOutTotalLabel {
		background-color: #f5f5f5;
		padding: 5px 10px;
		color: #232C3D !important;
		font-weight: 900;
	}
	.xCNTransferBchOutLabel {
		padding: 5px 10px;
		color: #232C3D !important;
		font-weight: 900;
	}
	.xCNTransferBchOutLabelFullWidth{
		width: 100%;
	}
	.xCNTransferBchOutLabelWidth{
		width: 260px;
	}
</style>

<?php
	if ($aResult['rtCode'] == "1") { // Edit
		$tBchCode = $aResult['raItems']['FTBchCode'];
		$tBchName = $aResult['raItems']['FTBchName'];
		$tDocNo = $aResult['raItems']['FTXthDocNo'];
		$tDocDate = $aResult['raItems']['FDXthDocDate'];
		$tDocTime = $aResult['raItems']['FTXthDocTime'];

		$tVATInOrEx = $aResult['raItems']['FTXthVATInOrEx'];
		$tRefExt = $aResult['raItems']['FTXthRefExt'];
		$tRefExtDate = $aResult['raItems']['FDXthRefExtDate'];
		$tRefInt = $aResult['raItems']['FTXthRefInt'];
		$tRefIntDate = $aResult['raItems']['FDXthRefIntDate'];
		$cTotal = $aResult['raItems']['FCXthTotal'];
		$cVat = $aResult['raItems']['FCXthVat'];
		$cVatable = $aResult['raItems']['FCXthVatable'];
		$tStaPrcStk = $aResult['raItems']['FTXthStaPrcStk'];
		$tStaRef = $aResult['raItems']['FNXthStaRef'];

		$tDptCode = $aResult['raItems']['FTDptCode'];

		$tSpnCode = $aResult['raItems']['FTSpnCode'];

		$tRsnCode = $aResult['raItems']['FTRsnCode'];
		$tRsnName = $aResult['raItems']['FTRsnName'];

		$tCreateByCode = $aResult['raItems']['FTCreateBy'];
		$tCreateByName = $aResult['raItems']['FTCreateByName'];

		$tUsrApvCode = $aResult['raItems']['FTXthApvCode'];
		$tUsrApvName = $aResult['raItems']['FTXthApvName'];

		$tStaDoc = $aResult['raItems']['FTXthStaDoc'];
		$tStaApv = $aResult['raItems']['FTXthStaApv'];
		$tUsrKeyCode = $aResult['raItems']['FTUsrCode']; // พนักงาน Key
		$tStaDelMQ = $aResult['raItems']['FTXthStaDelMQ'];
		$nStaDocAct = $aResult['raItems']['FNXthStaDocAct'];
		$nDocPrint = $aResult['raItems']['FNXthDocPrint'];
		$tRmk = $aResult['raItems']['FTXthRmk'];

		$tCtrName = $aResult['raItems']['FTXthCtrName'];
		$tTnfDate = $aResult['raItems']['FDXthTnfDate'];
		$tRefTnfID = $aResult['raItems']['FTXthRefTnfID'];
		$tRefVehID = $aResult['raItems']['FTXthRefVehID'];
		$tQtyAndTypeUnit = $aResult['raItems']['FTXthQtyAndTypeUnit'];
		$nShipAdd = $aResult['raItems']['FNXthShipAdd'];

		$tViaCode = $aResult['raItems']['FTViaCode'];
		$tViaName = $aResult['raItems']['FTViaName'];

		$tRoute = "docTransferBchOutEventEdit";

		$tUserBchCodeFrom = $aResult['raItems']['FTXthBchFrm'];
		$tUserBchNameFrom = $aResult['raItems']['FTXthBchFrmName'];
		$tUserMchCodeFrom = $aResult['raItems']['FTXthMerchantFrm'];
		$tUserMchNameFrom = $aResult['raItems']['FTXthMerchantFrmName'];
		$tUserShpCodeFrom = $aResult['raItems']['FTXthShopFrm'];
		$tUserShpNameFrom = $aResult['raItems']['FTXthShopFrmName'];
		$tUserWahCodeFrom = $aResult['raItems']['FTXthWhFrm'];
		$tUserWahNameFrom = $aResult['raItems']['FTXthWhFrmName'];

		$tUserBchCodeTo = $aResult['raItems']['FTXthBchTo'];
		$tUserBchNameTo = $aResult['raItems']['FTXthBchToName'];
		$tUserWahCodeTo = $aResult['raItems']['FTXthWhTo'];
		$tUserWahNameTo = $aResult['raItems']['FTXthWhToName'];

	} else { // New
		$tUserLevel = $this->session->userdata('tSesUsrLevel');
		$tBchCode = $this->session->userdata("tSesUsrBchCodeDefault");
		$tBchName = $this->session->userdata("tSesUsrBchNameDefault");
		$tDocNo = "";
		$tDocDate = date('Y-m-d');
		$tDocTime = date('H:i');

		$tVATInOrEx = "";
		$tRefExt = "";
		$tRefExtDate = "";
		$tRefInt = "";
		$tRefIntDate = "";
		$cTotal = 0;
		$cVat = 0;
		$cVatable = 0;
		$tStaPrcStk = "";
		$tStaRef = "0";

		$tDptCode = "";

		$tSpnCode = "";

		$tRsnCode = "";
		$tRsnName = "";

		$tCreateByCode =  $this->session->userdata('tSesUsername');
		$tCreateByName = $this->session->userdata('tSesUsrUsername');

		$tUsrApvCode = "";
		$tUsrApvName = "";

		$tStaDoc = "";
		$tStaApv = "";
		$tUsrKeyCode = $this->session->userdata('tSesUsername'); // พนักงาน Key
		$tStaDelMQ = "";
		$nStaDocAct = 1;
		$nDocPrint = 0;
		$tRmk = "";

		$tCtrName = "";
		$tTnfDate = "";
		$tRefTnfID = "";
		$tRefVehID = "";
		$tQtyAndTypeUnit = "";
		$nShipAdd = "";

		$tViaCode = "";
		$tViaName = "";

		$tRoute = "docTransferBchOutEventAdd";

		$tUserBchCodeFrom = $this->session->userdata('tSesUsrBchCodeDefault');
		$tUserBchNameFrom = $this->session->userdata('tSesUsrBchNameDefault');
		$tUserMchCodeFrom = $this->session->userdata('tSesUsrMerCode');
		$tUserMchNameFrom = $this->session->userdata('tSesUsrMerName');
		$tUserShpCodeFrom = $this->session->userdata('tSesUsrShpCodeDefault');
		$tUserShpNameFrom = $this->session->userdata('tSesUsrShpNameDefault');
		$tUserWahCodeFrom = $this->session->userdata('tSesUsrWahCode');
		$tUserWahNameFrom = $this->session->userdata('tSesUsrWahName');

		$tUserBchCodeTo = "";
		$tUserBchNameTo = "";
		$tUserWahCodeTo = "";
		$tUserWahNameTo = "";

	}

	$nLangEdit = $this->session->userdata("tLangEdit");
	$tUsrApv = $this->session->userdata("tSesUsername");
	$tUserLoginLevel = $this->session->userdata("tSesUsrLevel");
	$bIsAddPage = empty($tDocNo) ? true : false;
	$bIsApv = empty($tStaApv) ? false : true;
	$bIsCancel = ($tStaDoc == "3") ? true : false;
	$bIsApvOrCancel = ($bIsApv || $bIsCancel);
	$bIsMultiBch = $this->session->userdata("nSesUsrBchCount") > 1;
	$bIsShpEnabled = FCNbGetIsShpEnabled();
?>

<script type="text/javascript">
	var nLangEdit		= '<?php echo $nLangEdit; ?>';
	var tUsrApv 		= '<?php echo $tUsrApv; ?>';
	var tUserLoginLevel = '<?php echo $tUserLoginLevel; ?>';
	var bIsAddPage 		= <?php echo ($bIsAddPage) ? 'true' : 'false'; ?>;
	var bIsApv 			= <?php echo ($bIsApv) ? 'true' : 'false'; ?>;
	var bIsCancel 		= <?php echo ($bIsCancel) ? 'true' : 'false'; ?>;
	var bIsApvOrCancel 	= <?php echo ($bIsApvOrCancel) ? 'true' : 'false'; ?>;
	var tStaApv 		= '<?php echo $tStaApv; ?>';
	var bIsMultiBch 	= <?php echo ($bIsMultiBch) ? 'true' : 'false'; ?>;
	var bIsShpEnabled 	= <?php echo ($bIsShpEnabled) ? 'true' : 'false'; ?>;
	// เช็ค เดือน ถ้า เดือนไม่เท่ากับเอกสาร จะวิ่งไปเช็คสิทธิ ว่า มีสิทธิเห็นปุ่มยกเลิกไหม ถ้ามี ยกเลิกได้ ถ้าไม่มี ยกเลิกไม่ได้
	var tAutStaCancel	= '<?=@$aAlwEvent['tAutStaCancel'];?>'
	var tDocDateCreate	= '<?=date("Y-m-d", strtotime($tDocDate));?>'
	var tDocDateToday	= '<?=date('Y-m-d');?>'
</script>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmTransferBchOutForm">
	<input type="hidden" id="ohdTransferBchOutBchLogin" name="ohdTransferBchOutBchLogin" value="<?php echo $tBchCode; ?>">
	<input type="hidden" id="ohdTransferBchOutStaDoc" name="ohdTransferBchOutStaDoc" value="<?php echo $tStaDoc; ?>">
	<input type="hidden" id="ohdTransferBchOutStaApv" name="ohdTransferBchOutStaApv" value="<?php echo $tStaApv; ?>">
	<input type="hidden" id="ohdTransferBchOutStaDelMQ" name="ohdTransferBchOutStaDelMQ" value="<?php echo $tStaDelMQ; ?>">
	<input type="text" class="xCNHide" id="oetTransferBchOutApvCodeUsrLogin" name="oetTransferBchOutApvCodeUsrLogin" maxlength="20" value="<?php echo $this->session->userdata('tSesUsername'); ?>">
	<input type="text" class="xCNHide" id="ohdLangEdit" name="ohdLangEdit" maxlength="1" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
	<input type="hidden" id="ohdTWOnStaWasteWAH" name="ohdTWOnStaWasteWAH" value="<?= $nStaWasteWAH ?>">
	<button style="display:none" type="submit" id="obtTransferBchOutSubmit" onclick="JSxTransferBchOutValidateForm();"></button>



	<div class="row">
		<div class="col-md-3">
			<!--Section : รายละเอียดเอกสาร-->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?= language('document/transfer_branch_out/transfer_branch_out', 'tStatus'); ?></label>
					<a class="xCNMenuplus <?php echo ($bIsAddPage)?'collapsed':''; ?>" role="button" data-toggle="collapse" href="#odvTransferBchOutDocDetailPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvTransferBchOutDocDetailPanel" class="panel-collapse collapse <?php echo ($bIsAddPage)?'':'in'; ?>" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<div class="form-group xCNHide" style="text-align: right;">
							<label class="xCNTitleFrom "><?= language('document/transfer_branch_out/transfer_branch_out', 'tApproved'); ?></label>
						</div>
						<input type="hidden" value="0" id="ohdCheckTFWSubmitByButton" name="ohdCheckTFWSubmitByButton">
						<input type="hidden" value="0" id="ohdCheckTFWClearValidate" name="ohdCheckTFWClearValidate">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNo'); ?></label>
						<?php if ($bIsAddPage) { ?>
							<div class="form-group" id="odvTransferBchOutAutoGenCode">
								<div class="validate-input">
									<label class="fancy-checkbox">
										<input type="checkbox" id="ocbTransferBchOutAutoGenCode" name="ocbTransferBchOutAutoGenCode" checked="true" value="1">
										<span><?= language('document/transfer_branch_out/transfer_branch_out', 'tAutoGenCode'); ?></span>
									</label>
								</div>
							</div>
							<div class="form-group" id="odvPunCodeForm">
								<input
								type="text"
								class="form-control xCNInputWithoutSpcNotThai"
								maxlength="20"
								id="oetTransferBchOutDocNo"
								name="oetTransferBchOutDocNo"
								data-is-created="<?php  ?>"
								placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNo') ?>"
								value="<?php  ?>"
								data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNoRequired') ?>"
								data-validate-dublicateCode="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNoDuplicate') ?>"
								disabled readonly>
								<input type="hidden" value="2" id="ohdCheckDuplicateTFW" name="ohdCheckDuplicateTFW">
							</div>
						<?php } else { ?>
							<div class="form-group" id="odvPunCodeForm">
								<div class="validate-input">
									<input type="text" class="form-control xCNInputWithoutSpcNotThai " maxlength="20" id="oetTransferBchOutDocNo" name="oetTransferBchOutDocNo" data-is-created="<?php  ?>" placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWDocNo') ?>" value="<?php echo $tDocNo; ?>" readonly onfocus="this.blur()">
								</div>
							</div>
						<?php } ?>

						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'tDocDate'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNApvOrCanCelDisabled" id="oetTransferBchOutDocDate" name="oetTransferBchOutDocDate" value="<?= $tDocDate; ?>" data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWPlsEnterDocDate'); ?>">
								<span class="input-group-btn">
									<button id="obtXthDocDate" type="button" class="btn xCNBtnDateTime xCNApvOrCanCelDisabled">
										<img src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
									</button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'tDocTime'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNTimePicker xCNApvOrCanCelDisabled" id="oetTransferBchOutDocTime" name="oetTransferBchOutDocTime" value="<?= $tDocTime; ?>" data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWPlsEnterDocTime'); ?>">
								<span class="input-group-btn">
									<button id="obtXthDocTime" type="button" class="btn xCNBtnDateTime xCNApvOrCanCelDisabled">
										<img src="<?= base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
									</button>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?= language('document/transfer_branch_out/transfer_branch_out', 'tCreateBy'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<input type="text" class="xCNHide" id="oetCreateBy" name="oetCreateBy" value="<?= $tCreateByCode ?>">
								<label><?= $tCreateByName ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBStaDoc'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<label><?= language('document/transfer_branch_out/transfer_branch_out', 'tStaDoc' . $tStaDoc); ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBStaApv'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<?php if($tStaDoc == 3){ ?>
									<label><?= language('document/transfer_branch_out/transfer_branch_out', 'tStaDoc' . $tStaDoc); ?></label>
								<?php }else{ ?>
									<label><?= language('document/transfer_branch_out/transfer_branch_out', 'tStaApv' . $tStaApv); ?></label>
								<?php } ?>
							</div>
						</div>
						<?php if ($tDocNo != '') { ?>
							<div class="row">
								<div class="col-md-6">
									<label class="xCNLabelFrm"><?= language('document/transfer_branch_out/transfer_branch_out', 'tApvBy'); ?></label>
								</div>
								<div class="col-md-6 text-right">
									<input type="text" class="xCNHide" id="oetXthApvCode" name="oetXthApvCode" maxlength="20" value="<?= $tUsrApvCode ?>">
									<label><?= $tUsrApvName != '' ? $tUsrApvName : '-' ?></label>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<!--Section : เงื่อนไขเอกสาร -->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เงื่อนไข'); ?></label>
					<a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTransferBchOutDocConditionPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvTransferBchOutDocConditionPanel" class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">

						<div class="row">
							<div class="col-md-12">
								<!-- สาขาที่สร้าง -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBBchCreate'); ?></label>
									<div class="input-group">
										<input
										type="text"
										class="input100 xCNHide"
										id="oetTransferBchOutBchCode"
										name="oetTransferBchOutBchCode"
										maxlength="5"
										value="<?php echo $tBchCode; ?>">
										<input
										class="form-control xWPointerEventNone"
										type="text"
										id="oetTransferBchOutBchName"
										name="oetTransferBchOutBchName"
										value="<?php echo $tBchName; ?>"
										readonly>
										<span class="input-group-btn xWConditionSearchPdt">
											<button id="obtTransferBchOutBrowseBch" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
											</button>
										</span>
									</div>
								</div>
								<!-- สาขาที่สร้าง -->
							</div>
						</div>

						<!-- ต้นทาง -->
						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?=language('document/transferreceiptbranch/transferreceiptbranch','tTBIOrigin');?></legend>

							<!-- จากสาขา -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'สาขา'); ?></label>
								<div class="input-group">
									<input
									type="text"
									class="input100 xCNHide xCNApvOrCanCelDisabled"
									id="oetTransferBchOutXthBchFrmCode"
									name="oetTransferBchOutXthBchFrmCode"
									maxlength="5"
									value="<?php echo $tUserBchCodeFrom; ?>">
									<input
									class="form-control xWPointerEventNone xCNApvOrCanCelDisabled"
									type="text" id="oetTransferBchOutXthBchFrmName"
									name="oetTransferBchOutXthBchFrmName"
									value="<?php echo $tUserBchNameFrom; ?>"
									readonly
									data-validate-required="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBWahNameStartRequired'); ?>">
									<span class="input-group-btn xWConditionSearchPdt">
										<button id="obtTransferBchOutBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

							<!-- จากคลัง -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'คลังสินค้า'); ?></label>
								<div class="input-group">
									<input
									type="text"
									class="input100 xCNHide xCNApvOrCanCelDisabled"
									id="oetTransferBchOutXthWhFrmCode"
									name="oetTransferBchOutXthWhFrmCode"
									maxlength="5"
									value="<?php echo $tUserWahCodeFrom; ?>">
									<input
									class="form-control xWPointerEventNone xCNApvOrCanCelDisabled"
									type="text"
									id="oetTransferBchOutXthWhFrmName"
									name="oetTransferBchOutXthWhFrmName"
									value="<?php echo $tUserWahNameFrom; ?>"
									readonly
									data-validate-required="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBPlsEnterWah'); ?>">
									<span class="input-group-btn xWConditionSearchPdt">
										<button id="obtTransferBchOutBrowseWahFrom" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>
						</fieldset>

						<!-- ปลายทาง -->
						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?=language('document/transferreceiptbranch/transferreceiptbranch','tTBITo');?></legend>

							<!-- ถึงสาขา -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/transferreceiptbranch/transferreceiptbranch', 'tTBIBranch'); ?></label>
								<div class="input-group">
									<input
									class="form-control xCNHide xCNApvOrCanCelDisabled"
									id="oetTransferBchOutXthBchToCode"
									name="oetTransferBchOutXthBchToCode"
									maxlength="5"
									value="<?php echo $tUserBchCodeTo; ?>">
									<input
									class="form-control xWPointerEventNone xCNApvOrCanCelDisabled"
									type="text"
									id="oetTransferBchOutXthBchToName"
									name="oetTransferBchOutXthBchToName"
									placeholder="<?php echo language('document/transferreceiptbranch/transferreceiptbranch', 'tTBIBranch'); ?>"
									value="<?php echo $tUserBchNameTo; ?>"
									readonly
									data-validate-required="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBPlsEnterBch'); ?>">
									<span class="xWConditionSearchPdt input-group-btn">
										<button id="obtTransferBchOutBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

							<!-- ถึงคลัง -->
							<div class="form-group" style="display: none;">
								<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'คลังสินค้า'); ?></label>
								<div class="input-group">
									<input
									type="text"
									class="input100 xCNHide xCNApvOrCanCelDisabled"
									id="oetTransferBchOutXthWhToCode"
									name="oetTransferBchOutXthWhToCode"
									maxlength="5"
									value="<?php echo $tUserWahCodeTo; ?>">
									<input
									class="form-control xWPointerEventNone xCNApvOrCanCelDisabled"
									placeholder="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'คลังสินค้า'); ?>"
									type="text"
									id="oetTransferBchOutXthWhToName"
									name="oetTransferBchOutXthWhToName"
									value="<?php echo $tUserWahNameTo; ?>"
									readonly
									data-validate-required="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBPlsEnterWah'); ?>">
									<span class="input-group-btn xWConditionSearchPdt">
										<button id="obtTransferBchOutBrowseWahTo" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>



						</fieldset>

						<!-- เหตุผล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'เหตุผล'); ?></label>
                            <div class="input-group">
								<input
								name="oetTransferBchOutRsnName"
								id="oetTransferBchOutRsnName"
								class="form-control xWPointerEventNone xCNApvOrCanCelDisabled"
								value="<?=$tRsnName?>"
								type="text"
								readonly
                                placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'เหตุผล') ?>">
								<input
								name="oetTransferBchOutRsnCode"
								id="oetTransferBchOutRsnCode"
								value="<?=$tRsnCode?>"
								class="form-control xCNHide xCNApvOrCanCelDisabled"
								type="text">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled" id="obtTransferBchOutBrowseReason" type="button">
                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
						</div>
						<!-- เหตุผล -->

					</div>
				</div>
			</div>

			<!--Section : อ้างอิงเอกสาร -->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงเอกสาร'); ?></label>
					<a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTransferBchOutDocReferPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvTransferBchOutDocReferPanel" class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="xCNLabelFrm"><?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
								<div class="input-group" style="width:100%;">
									<input type="hidden" id="oetTransferBchOutXthRefIntOld" name="oetTransferBchOutXthRefIntOld" value="<?=@$tRefInt?>">
									<input type="text" class="input100 xCNHide" id="oetTransferBchOutXthRefInt" name="oetTransferBchOutXthRefInt" value="<?=@$tRefInt?>">
									<input class="form-control xWPointerEventNone" type="text" id="oetTransferBchOutXthRefIntName" name="oetTransferBchOutXthRefIntName" value="<?=@$tRefInt?>" readonly placeholder="<?= language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?>">
									<span class="input-group-btn">
										<button id="obtTransferBchOutRefInt" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
											<img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
						<!-- วันที่เอกสารภายใน -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'วันที่เอกสารภายใน'); ?></label>
									<div class="input-group">
										<input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNApvOrCanCelDisabled" 
										placeholder="YYYY-MM-DD" id="oetTransferBchOutXthRefIntDate" name="oetTransferBchOutXthRefIntDate" value="<?php echo $tRefIntDate; ?>">
										<span class="input-group-btn">
											<button id="obtTransferBchOutXthRefIntDate" type="button" class="btn xCNBtnDateTime xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<!-- เลขที่อ้างอิงเอกสารภายนอก -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เอกสารอ้างอิงภายนอก'); ?></label>
									<input type="text" placeholder="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'เอกสารอ้างอิงภายนอก'); ?>" class="form-control xCNApvOrCanCelDisabled" id="oetTransferBchOutXthRefExt" name="oetTransferBchOutXthRefExt" maxlength="20" value="<?php echo $tRefExt; ?>">
								</div>
							</div>
						</div>
						<!-- วันที่เอกสารภายนอก -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'วันที่เอกสารภายนอก'); ?></label>
									<div class="input-group">
										<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate xCNApvOrCanCelDisabled" id="oetTransferBchOutXthRefExtDate" name="oetTransferBchOutXthRefExtDate" value="<?php echo $tRefExtDate; ?>">
										<span class="input-group-btn">
											<button id="obtTransferBchOutXthRefExtDate" type="button" class="btn xCNBtnDateTime xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>

			<!--Section : การขนส่ง -->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'การขนส่ง'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvTransferBchOutDeliveryPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvTransferBchOutDeliveryPanel" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body xCNPDModlue">

						<!-- ชื่อผู้ติดต่อ -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ชื่อผู้ติดต่อ'); ?></label>
									<input type="text" class="form-control xCNApvOrCanCelDisabled" maxlength="100" id="oetTransferBchOutXthCtrName" name="oetTransferBchOutXthCtrName" placeholder="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'ชื่อผู้ติดต่อ'); ?>" value="<?php echo $tCtrName; ?>">
								</div>
							</div>
						</div>
						<!-- ชื่อผู้ติดต่อ -->

						<!-- วันที่ขนส่ง -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'วันที่ขนส่ง'); ?></label>
									<div class="input-group">
										<input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNApvOrCanCelDisabled" id="oetTransferBchOutXthTnfDate" placeholder="YYYY-MM-DD" name="oetTransferBchOutXthTnfDate" value="<?php echo $tTnfDate; ?>">
										<span class="input-group-btn">
											<button id="obtTransferBchOutXthTnfDate" type="button" class="btn xCNBtnDateTime xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<!-- วันที่ขนส่ง -->

						<!-- อ้างอิงเลขที่ใบขนส่ง -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงเลขที่ใบขนส่ง'); ?></label>
									<input type="text" class="form-control  xCNApvOrCanCelDisabled" maxlength="80" id="oetTransferBchOutXthRefTnfID" placeholder="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงเลขที่ใบขนส่ง'); ?>" name="oetTransferBchOutXthRefTnfID" value="<?php echo $tRefTnfID; ?>">
								</div>
							</div>
						</div>
						<!-- อ้างอิงเลขที่ใบขนส่ง -->

						<!-- อ้างอิงเลขที่ยานพาหนะขนส่ง -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงเลขที่ยานพาหนะขนส่ง'); ?></label>
									<input type="text" class="form-control  xCNApvOrCanCelDisabled" maxlength="80" id="oetTransferBchOutXthRefVehID" name="oetTransferBchOutXthRefVehID" value="<?php echo $tRefVehID; ?>">
								</div>
							</div>
						</div>
						<!-- อ้างอิงเลขที่ยานพาหนะขนส่ง -->

						<!-- ลักษณะหีบห่อ -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ลักษณะหีบห่อ'); ?></label>
									<input type="text" class="form-control  xCNApvOrCanCelDisabled" maxlength="80" id="oetTransferBchOutXthQtyAndTypeUnit" placeholder="<?php echo language('document/transfer_branch_out/transfer_branch_out', 'ลักษณะหีบห่อ'); ?>" name="oetTransferBchOutXthQtyAndTypeUnit" value="<?php echo $tQtyAndTypeUnit; ?>">
								</div>
							</div>
						</div>
						<!-- ลักษณะหีบห่อ -->

						<!-- ขนส่งโดย -->
                        <!-- Fit Auto ไม่ใช้ -->
					
						<!-- ที่อยู่สำหรับจัดส่ง -->
					</div>
				</div>
			</div>

			<!--Section : อื่นๆ-->
			<div class="panel panel-default" style="margin-bottom: 60px;">
				<div id="odvHeadAllow" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'tOther'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvOther" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvOther" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						

						<!-- หมายเหตุ -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'หมายเหตุ'); ?></label>
							<textarea class="form-control" id="otaTransferBchOutXthRmk" name="otaTransferBchOutXthRmk" maxlength="200"><?php echo $tRmk; ?></textarea>
						</div>
						<!-- หมายเหตุ -->

						<!-- เคลื่อนไหว -->
						<div class="form-group">
							<label class="fancy-checkbox">
								<input
								class="xCNApvOrCanCelDisabled"
								type="checkbox"
								value="1"
								<?php echo ($nStaDocAct == 1)?'checked':''; ?>
								id="ocbTransferBchOutXthStaDocAct"
								name="ocbTransferBchOutXthStaDocAct"
								maxlength="1">
								<span>&nbsp;</span>
								<span class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เคลื่อนไหว'); ?></span>
							</label>
						</div>
						<!-- เคลื่อนไหว -->

						<!-- สถานะอ้างอิง -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'สถานะอ้างอิง'); ?></label>
							<select class="selectpicker form-control xCNApvOrCanCelDisabled" id="ostTransferBchOutXthStaRef" name="ostTransferBchOutXthStaRef" maxlength="1">
								<option value="0" <?php echo ($tStaRef == "0")?'checked':''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ไม่เคยอ้างอิง'); ?></option>
								<option value="1" <?php echo ($tStaRef == "1")?'checked':''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงบางส่วน'); ?></option>
								<option value="2" <?php echo ($tStaRef == "2")?'checked':''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงทั้งหมด'); ?></option>
							</select>
						</div>
						<!-- สถานะอ้างอิง -->

						<!-- จำนวนครั้งที่ปริ้น -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'จำนวนครั้งที่ปริ้น'); ?></label>
							<input readonly type="text" class="form-control xCNInputWithoutSpc xCNApvOrCanCelDisabled" maxlength="100" id="oetTransferBchOutXthDocPrint" name="oetTransferBchOutXthDocPrint" maxlength="1" value="">
						</div>
						<!-- จำนวนครั้งที่ปริ้น -->

						<!-- ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม'); ?></label>
							<select class="selectpicker form-control xCNApvOrCanCelDisabled" id="ocmTransferBchOutOptionAddPdt" name="ocmTransferBchOutOptionAddPdt">
								<option value="1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'บวกจำนวนในรายการเดิม'); ?></option>
								<option value="2"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เพิ่มแถวใหม่'); ?></option>
							</select>
						</div>
						<!-- ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม -->
					</div>
				</div>
			</div>
		</div>

		<!--Panel ตารางฝั่งขวา-->
		<div class="col-md-9" id="odvRightPanal">
			<div class="panel panel-default xCNTransferBchOutPdtContainer" style="margin-bottom: 25px;">

				<!-- รายการสินค้า -->
				<div class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">

						<!-- Options รายการสินค้า-->
						<div class="row p-t-10">

							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
										<span class="input-group-btn">
											<button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
												<img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
											</button>
										</span>
									</div>
								</div>
							</div>

							<div class="col-xs-12 <?php echo (!$bIsApvOrCancel)?'col-sm-5 col-md-5 col-lg-7':'col-sm-6 col-md-6 col-lg-6'; ?> text-right">
								<?php if(!$bIsApvOrCancel) { ?>
									<div id="odvTransferBchOutMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
										<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
											<?=language('common/main/main','tCMNOption')?> <span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li id="oliTransferBchOutPdtBtnDeleteMulti" class="disabled">
												<a href="javascript:;" onclick="JSxTransferBchOutCallPdtDataTableDeleteMore()"><?=language('common/main/main','tDelAll')?></a>
											</li>
										</ul>
									</div>
								<?php } ?>
							</div>
							<?php if(!$bIsApvOrCancel) { ?>
								<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
									<div class="form-group">
										<div style="position: absolute;right: 15px;">
											<button type="button" class="xCNBTNPrimeryPlus xCNTransferBchOutBtnBrowsePdt" onclick="JCNvTransferBchOutBrowsePdt()">+</button>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>

						<div id="odvTransferBchOutPdtDataTable"></div>
					</div>
				</div>
			</div>

		</div>
	</div>
</form>

<?php if(!$bIsApvOrCancel) { ?>
	<!-- Begin Approve Doc -->
	<div class="modal fade xCNModalApprove" id="odvTransferBchOutPopupApv">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tApproveTheDocument'); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p><?php echo language('common/main/main', 'tMainApproveStatus'); ?></p>
					<ul>
						<li><?php echo language('common/main/main', 'tMainApproveStatus1'); ?></li>
						<li><?php echo language('common/main/main', 'tMainApproveStatus2'); ?></li>
						<li><?php echo language('common/main/main', 'tMainApproveStatus3'); ?></li>
						<li><?php echo language('common/main/main', 'tMainApproveStatus4'); ?></li>
					</ul>
					<p><?php echo language('common/main/main', 'tMainApproveStatus5'); ?></p>
					<p><strong><?php echo language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
				</div>
				<div class="modal-footer">
					<button onclick="JSvTransferBchOutApprove(true)" type="button" class="btn xCNBTNPrimery">
						<?php echo language('common/main/main', 'tModalConfirm'); ?>
					</button>
					<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
						<?php echo language('common/main/main', 'tModalCancel'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Approve Doc -->


<?php } ?>

<!-- Begin Cancel Doc -->
<div class="modal fade" id="odvTransferBchOutPopupCancel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?php echo language('document/document/document', 'tDocDocumentCancel') ?></label>
			</div>
			<div class="modal-body">
				<p id="obpMsgApv"><?php echo language('document/document/document', 'tDocCancelText1') ?></p>
				<p><strong><?php echo language('document/document/document', 'tDocCancelText2') ?></strong></p>
			</div>
			<div class="modal-footer">
				<button onclick="JSvTransferBchOutCancel(true)" type="button" class="btn xCNBTNPrimery">
					<?php echo language('common/main/main', 'tModalConfirm'); ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Cancel Doc -->

<!-- Begin Pdt Column Control Panel -->
<div class="modal fade" id="odvTransferBchOutPdtColumnControlPanel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="odvTransferBchOutPdtColummControlDetail">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
				<button type="button" class="btn xCNBTNPrimery" onclick="JSxTransferBchOutUpdatePdtColumn()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
			</div>
		</div>
	</div>
</div>
<!-- End Add Cash Panel -->

<!-- =========================================== อ้างอิงเอกสารภายใน ======================================= -->
<div id="odvTransferBchOutBchModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/transfer_branch_out/transfer_branch_out','อ้างอิงเอกสารภายใน')?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvTransferBchOutBchFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jTransferBchOutPageadd.php') ?>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
	$('#obtTransferBchOutRefInt').on('click',function(){
        JSxCallPageTransferBchOutRefIntDoc();
    });

	//Ref เอกสาร
	function JSxCallPageTransferBchOutRefIntDoc(){
        var tBCHCode = $('#oetTransferBchOutBchCode').val()
        var tBCHName = $('#oetTransferBchOutBchName').val()

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docTransferBchOutRefIntDoc",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvTransferBchOutBchModalRefIntDoc #odvTransferBchOutBchFromRefIntDoc').html(oResult);
                $('#odvTransferBchOutBchModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

	//กดยืนยัน Ref เอกสารใบรับของ
	$('#obtConfirmRefDocInt').click(function(){
		var tRefIntDocNo    =  $('.xDocuemntRefInt.active').data('docno');
		var tRefIntDocDate  =  $('.xDocuemntRefInt.active').data('docdate');
		var tRefIntBchCode  =  $('.xDocuemntRefInt.active').data('bchcode');
		var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
			return $(this).val();
		}).get();

		//ถ้าไม่เลือกเอกสารอ้างอิงมา
		if(tRefIntDocNo != undefined){

			var tSplStaVATInOrEx =  $('.xDocuemntRefInt.active').data('vatinroex');
			var cSplCrLimit      =  $('.xDocuemntRefInt.active').data('crtrem');
			var nSplCrTerm       =  $('.xDocuemntRefInt.active').data('crlimit');
			var tSplCode         =  $('.xDocuemntRefInt.active').data('splcode');
			var tSplName         =  $('.xDocuemntRefInt.active').data('splname');
			var tSPlPaidType     =  $('.xDocuemntRefInt.active').data('dstpain');
			var tVatcode         =  $('.xDocuemntRefInt.active').data('vatcode');
			var nVatrate         =  $('.xDocuemntRefInt.active').data('vatrate');
			var tBchcodeto       =  $('.xDocuemntRefInt.active').data('bchcodeto');
			var tBchnameto       =  $('.xDocuemntRefInt.active').data('bchnameto');
			var tBchcodeFrm      =  $('.xDocuemntRefInt.active').data('bchcodefrm');
			var tBchnameFrm      =  $('.xDocuemntRefInt.active').data('bchnamefrm');
			var tWahcodeto       =  $('.xDocuemntRefInt.active').data('wahcodeto');
			var tWatnameto       =  $('.xDocuemntRefInt.active').data('watnameto');

			var poParams = {
				FTSplCode           : tSplCode,
				FTSplName           : tSplName,
				FTSplStaVATInOrEx   : tSplStaVATInOrEx,
				FTRefIntDocNo       : tRefIntDocNo,
				FTRefIntDocDate     : tRefIntDocDate,
				FNXphCrTerm         : nSplCrTerm
			};

			//อ้างอิงเอกสารภายใน
			$('#oetTransferBchOutXthRefInt').val(tRefIntDocNo);
			$('#oetTransferBchOutXthRefIntName').val(tRefIntDocNo);

			//วันที่อ้างอิงเอกสารใน
			$('#oetTransferBchOutXthRefIntDate').val(tRefIntDocDate).datepicker("refresh");

			//ในส่วนของต้นทาง
			$('#oetTransferBchOutXthBchFrmCode').val(tBchcodeFrm);
			$('#oetTransferBchOutXthBchFrmName').val(tBchnameFrm);
			
			//ในส่วนของปลายทาง
			$('#oetTransferBchOutXthBchToCode').val(tBchcodeto);
			$('#oetTransferBchOutXthBchToName').val(tBchnameto);
			$('#oetTransferBchOutXthWhToCode').val(tWahcodeto);
			$('#oetTransferBchOutXthWhToName').val(tWatnameto);
			$('#obtTransferBchOutBrowseWahTo').attr('disabled',true);

			JCNxOpenLoading();
			$.ajax({
				type    : "POST",
				url     : "docTransferBchOutRefIntDocInsertDTToTemp",
				data    : {
					'tTransferBchOutDocNo'          : $('#oetTransferBchOutDocNo').val(),
					'tTransferBchOutFrmBchCode'     : $('#oetTransferBchOutBchCode').val(),
					'tRefIntDocNo'      			: tRefIntDocNo,
					'tRefIntBchCode'    			: tRefIntBchCode,
					'tSplStaVATInOrEx'  			: tSplStaVATInOrEx,
					'aSeqNo'            			: aSeqNo
				},
				cache: false,
				Timeout: 0,
				success: function (oResult){
					//โหลดสินค้าใน Temp
					JSxTransferBchOutGetPdtInTmp();

					JCNxCloseLoading();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});

		}else{
			//อ้างอิงเอกสารภายใน
			$('#oetTransferBchOutRefInt').val('');
			$('#oetTransferBchOutRefIntName').val('');

			//วันที่อ้างอิงเอกสารใน
			$('#oetTransferBchOutRefIntDate').val('').datepicker("refresh");
		}
	});

	//ค้นหาสินค้าใน temp
	function JSvDOCSearchPdtHTML() {
		var value = $("#oetSearchPdtHTML").val().toLowerCase();
		$("#otbTransferBchOutPdtTable tbody tr ").filter(function () {
			tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	}

</script>