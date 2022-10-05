<style type="text/css">
	fieldset.scheduler-border {border: 1px groove #ffffffa1 !important;padding: 0 20px 20px 20px !important;margin: 0 0 10px 0 !important;}
	legend.scheduler-border {text-align: left !important;width: auto;padding: 0 5px;border-bottom: none;font-weight: bold;}
	.xCNBTNPrimeryCusPlus {border-radius: 50%;float: right;width: 30px;height: 30px;line-height: 30px;background-color: #1866ae;text-align: center;margin-top: 8px;font-size: 29px;color: #ffffff;cursor: pointer;-webkit-border-radius: 50%;-moz-border-radius: 50%;-ms-border-radius: 50%;-o-border-radius: 50%;}
	.fancy-checkbox {display: inline-block;font-weight: normal;width: 120px;}
	.xCNPCKTotalLabel {background-color: #f5f5f5;padding: 5px 10px;color: #232C3D !important;font-weight: 900;}
	.xCNPCKLabel {padding: 5px 10px;color: #232C3D !important;font-weight: 900;}
	.xCNPCKLabelFullWidth {width: 100%;}
	.xCNPCKLabelWidth {width: 260px;}
</style>

<?php
	if ($aResult['rtCode'] == "1") { // Edit
		$tBchCode 		= $aResult['raItems']['FTBchCode'];
		$tBchName 		= $aResult['raItems']['FTBchName'];
		$tAgnCode 		= $aResult['raItems']['FTAgnCode'];
		$tAgnName 		= $aResult['raItems']['FTAgnName'];
		$tDocNo 		= $aResult['raItems']['FTXthDocNo'];
		$tDocDate 		= $aResult['raItems']['FDXthDocDate'];
		$tDocTime 		= $aResult['raItems']['FTXthDocTime'];
		$tVATInOrEx 	= '';
		$tRefExt 		= $aResult['raItems']['FTXthRefExt'];
		$tRefExtDate	= $aResult['raItems']['FDXthRefExtDate'];
		$tRefInt 		= $aResult['raItems']['FTXthRefInt'];
		$tRefIntDate 	= $aResult['raItems']['FDXthRefIntDate'];
		$cTotal 		= '';
		$cVat 			= '';
		$cVatable 		= '';
		$tStaPrcStk 	= '';
		$tStaRef 		= $aResult['raItems']['FNXthStaRef'];
		$tDptCode 		= $aResult['raItems']['FTDptCode'];
		$tSpnCode 		= '';
		$tRsnCode 		= $aResult['raItems']['FTRsnCode'];
		$tRsnName 		= $aResult['raItems']['FTRsnName'];
		$tCreateByCode 	= $aResult['raItems']['FTCreateBy'];
		$tCreateByName 	= $aResult['raItems']['FTCreateByName'];
		$tUsrApvCode 	= $aResult['raItems']['FTXthApvCode'];
		$tUsrApvName 	= $aResult['raItems']['FTXthApvName'];
		$tStaDoc 		= $aResult['raItems']['FTXthStaDoc'];
		$tStaApv 		= $aResult['raItems']['FTXthStaApv'];
		$tUsrKeyCode 	= $aResult['raItems']['FTUsrCode']; // พนักงาน Key
		$tStaDelMQ 		= $aResult['raItems']['FTXthStaDelMQ'];
		$nStaDocAct 	= $aResult['raItems']['FNXthStaDocAct'];
		$nDocPrint 		= $aResult['raItems']['FNXthDocPrint'];
		$tRmk 			= $aResult['raItems']['FTXthRmk'];
		$tUsrCode		= $aResult['raItems']['FTUsrCode'];
		$tUsrName		= $aResult['raItems']['FTUsrName'];
		$tCtrName		= '';
		$tTnfDate		= '';
		$tRefTnfID		= '';
		$tRefVehID		= '';
		$tQtyAndTypeUnit	= '';
		$nShipAdd 		= '';
		$tViaCode 		= '';
		$tViaName 		= '';


		$tRoute = "docPCKEventEdit";

		
		$tUserBchCodeFrom = '';
		$tUserBchNameFrom = $aResult['raItems']['FTXthBchFrmName'];
		
		$tUserMchCodeFrom = '';
		$tUserMchNameFrom = '';
		$tUserShpCodeFrom = '';
		$tUserShpNameFrom = '';
		$tUserWahCodeFrom = '';
		$tUserWahNameFrom = $aResult['raItems']['FTXthWhFrmName'];

		$tUserBchCodeTo = '';
		$tUserBchNameTo = $aResult['raItems']['FTXthBchToName'];
		$tUserWahCodeTo = '';
		$tUserWahNameTo = $aResult['raItems']['FTXthWhToName'];

		//ลูกค้า
		$tCstCode = @$aCSTAddress[0]['FTCstCode'];
		// $tCstName = @$tCstName;
		$tCstName = @$aCSTAddress[0]['FTCstName'];
		$tCstTel = @$aCSTAddress[0]['FTCstTel'];
		$tCstEmail = @$aCSTAddress[0]['FTCstEmail'];
		$tCstCarRegNo = @$aCSTAddress[0]['FTCarRegNo'];
		$tCstBndName = @$aCSTAddress[0]['FTBndName'];

		$tPCKStaDoc = '';
		$nStaUploadFile        = 2;
		// Status Ref Key Type
        $tRefInType = $aResult['raItems']['FTXthRefType'];
	} else { // New
		$tUserLevel = $this->session->userdata('tSesUsrLevel');
		$tAgnCode = $this->session->userdata("tSesUsrAgnCode");
		$tAgnName = $this->session->userdata("tSesUsrAgnName");
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

		$tRoute = "docPCKEventAdd";

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

		$tUsrCode = $this->session->userdata("tSesUserCode");
		$tUsrName = $this->session->userdata("tSesUsrUsername");

		$tCstCode = '';
		$tCstName = '';
		$tCstTel = '';
		$tCstEmail = '';
		$tCstCarRegNo = '';
		$tCstBndName = '';

		$tPCKStaDoc = '';
		$nStaUploadFile        = 1;
		// Status Ref Key Type
        $tRefInType = '';
	}
	$nLangEdit 			= $this->session->userdata("tLangEdit");
	$tUsrApv 			= $this->session->userdata("tSesUsername");
	$tUserLoginLevel 	= $this->session->userdata("tSesUsrLevel");
	$bIsAddPage			= empty($tDocNo) ? true : false;
	$bIsApv 			= empty($tStaApv) ? false : true;
	$bIsCancel 			= ($tStaDoc == "3") ? true : false;
	$bIsApvOrCancel 	= ($bIsApv || $bIsCancel);
	$bIsMultiBch 		= $this->session->userdata("nSesUsrBchCount") > 1;
	$bIsShpEnabled 		= FCNbGetIsShpEnabled();
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
	var bIsMultiBch		= <?php echo ($bIsMultiBch) ? 'true' : 'false'; ?>;
	var bIsShpEnabled 	= <?php echo ($bIsShpEnabled) ? 'true' : 'false'; ?>;
</script>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmPCKForm">
	<input type="hidden" id="ohdPCKBchLogin" name="ohdPCKBchLogin" value="<?php echo $tBchCode; ?>">
	<input type="hidden" id="ohdPCKAgnLogin" name="ohdPCKAgnLogin" value="<?php echo $tAgnCode; ?>">
	<input type="hidden" id="ohdPCKStaApv" name="ohdPCKStaApv" value="<?php echo $tStaApv; ?>">
	<input type="hidden" id="ohdPCKStaDoc" name="ohdPCKStaDoc" value="<?php echo $tPCKStaDoc; ?>">
	<input type="hidden" id="ohdPCKStaDelMQ" name="ohdPCKStaDelMQ" value="<?php echo $tStaDelMQ; ?>">
	<input type="text" class="xCNHide" id="oetPCKApvCodeUsrLogin" name="oetPCKApvCodeUsrLogin" maxlength="20" value="<?php echo $this->session->userdata('tSesUsername'); ?>">
	<input type="text" class="xCNHide" id="ohdLangEdit" name="ohdLangEdit" maxlength="1" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
	<input type="hidden" id="ohdTWOnStaWasteWAH" name="ohdTWOnStaWasteWAH" value="<?= $nStaWasteWAH ?>">
	<input type="hidden" id="ohdPCKRoute" name="ohdPCKRoute" value="<?php echo $tRoute; ?>">
	<input type="hidden" id="oetPCKXthRefInt" name="oetPCKXthRefInt" value="">
	<input type="hidden" id="oetPCKXthRefIntDate" name="oetPCKXthRefIntDate" value="">
	<!-- เช็ค เดือน ถ้า เดือนไม่เท่ากับเอกสาร จะวิ่งไปเช็คสิทธิ -->
    <input type="hidden" id="ohdPCKRefInType"       name="ohdPCKRefInType"      value="<?=@$tRefInType;?>">
    <input type="hidden" id="ohdPCKAutStaCancel"    name="ohdPCKAutStaCancel"   value="<?=@$aAlwEvent['tAutStaCancel'];?>">
    <input type="hidden" id="ohdPCKDocDateCreate"   name="ohdPCKDocDateCreate"  value="<?=date("m", strtotime(@$tDocDate))?>">
    <input type="hidden" id="ohdPCKDateNowToday"    name="ohdPCKDateNowToday"   value="<?=date('m');?>">

	<button style="display:none" type="submit" id="obtPCKSubmit" onclick="JSxPCKValidateForm();"></button>
	<div class="row">
		<div class="col-md-3">
			<!--Section : รายละเอียดเอกสาร-->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?= language('document/transfer_branch_out/transfer_branch_out', 'tStatus'); ?></label>
					<a class="xCNMenuplus <?php echo ($bIsAddPage) ? 'collapsed' : ''; ?>" role="button" data-toggle="collapse" href="#odvPCKDocDetailPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvPCKDocDetailPanel" class="panel-collapse collapse <?php echo ($bIsAddPage) ? '' : 'in'; ?>" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<div class="form-group xCNHide" style="text-align: right;">
							<label class="xCNTitleFrom "><?= language('document/transfer_branch_out/transfer_branch_out', 'tApproved'); ?></label>
						</div>
						<input type="hidden" value="0" id="ohdCheckTFWSubmitByButton" name="ohdCheckTFWSubmitByButton">
						<input type="hidden" value="0" id="ohdCheckTFWClearValidate" name="ohdCheckTFWClearValidate">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNo'); ?></label>
						<?php if ($bIsAddPage) { ?>
							<div class="form-group" id="odvPCKAutoGenCode">
								<div class="validate-input">
									<label class="fancy-checkbox">
										<input type="checkbox" id="ocbPCKAutoGenCode" name="ocbPCKAutoGenCode" checked="true" value="1">
										<span><?= language('document/transfer_branch_out/transfer_branch_out', 'tAutoGenCode'); ?></span>
									</label>
								</div>
							</div>
							<div class="form-group" id="odvPunCodeForm">
								<input type="text" class="form-control xCNInputWithoutSpcNotThai" maxlength="20" id="oetPCKDocNo" name="oetPCKDocNo" data-is-created="<?php  ?>" placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNo') ?>" value="<?php  ?>" data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNoRequired') ?>" data-validate-dublicateCode="<?= language('document/transfer_branch_out/transfer_branch_out', 'tDocNoDuplicate') ?>" disabled readonly>
								<input type="hidden" value="2" id="ohdCheckDuplicateTFW" name="ohdCheckDuplicateTFW">
							</div>
						<?php } else { ?>
							<div class="form-group" id="odvPunCodeForm">
								<div class="validate-input">
									<input type="text" class="form-control xCNInputWithoutSpcNotThai " maxlength="20" id="oetPCKDocNo" name="oetPCKDocNo" data-is-created="<?php  ?>" placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWDocNo') ?>" value="<?php echo $tDocNo; ?>" readonly onfocus="this.blur()">
								</div>
							</div>
						<?php } ?>

						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'tDocDate'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNApvOrCanCelDisabled" id="oetPCKDocDate" name="oetPCKDocDate" value="<?= $tDocDate; ?>" data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWPlsEnterDocDate'); ?>">
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
								<input type="text" class="form-control xCNTimePicker xCNApvOrCanCelDisabled" id="oetPCKDocTime" name="oetPCKDocTime" value="<?= $tDocTime; ?>" data-validate-required="<?= language('document/transfer_branch_out/transfer_branch_out', 'tTFWPlsEnterDocTime'); ?>">
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
								<label><?= language('document/transfer_branch_out/transfer_branch_out', 'tStaApv' . $tStaApv); ?></label>
							</div>
						</div>
						<?php if ($tDocNo != '') { ?>
							<div class="row">
								<div class="col-md-6">
									<label class="xCNLabelFrm"><?= language('document/transfer_branch_out/transfer_branch_out', 'tApvBy'); ?></label>
								</div>
								<div class="col-md-6 text-right">
									<input type="text" class="xCNHide" id="oetXthApvCode" name="oetXthApvCode" maxlength="20" value="<?= $tUsrApvCode ?>">
									<label><?= $tUsrApvName != '' ? $tUsrApvName : '-'; ?></label>
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
					<a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPCKDocConditionPanel" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvPCKDocConditionPanel" class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">

						<div class="row">
							<div class="col-md-12">
								<script>
									var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
									if (tUsrLevel != "HQ") {
										$('#oimPCKBrowseAgn').attr("disabled", true);
										$('#obtPCKBrowseBCH').attr('disabled', true);
									}
								</script>
								<!--Agn Browse-->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
									<div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPCKAgnCode" name="oetPCKAgnCode" maxlength="5" value="<?php echo $tAgnCode; ?>">
										<input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPCKAgnName" name="oetPCKAgnName" maxlength="100" placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>" value="<?= @$tAgnName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimPCKBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>


								<!-- สาขาที่สร้าง -->
								<div class="form-group">
									<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBBchCreate'); ?></label>
									<div class="input-group">
										<input type="text" class="input100 xCNHide" id="oetPCKBchCode" name="oetPCKBchCode" maxlength="5" value="<?php echo $tBchCode; ?>">
										<input class="form-control xWPointerEventNone" type="text" id="oetPCKBchName" name="oetPCKBchName" value="<?php echo $tBchName; ?>" readonly placeholder="สาขาที่สร้าง">
										<span class="input-group-btn xWConditionSearchPdt">
											<button id="obtPCKBrowseBch" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
												<img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>
								<!-- สาขาที่สร้าง -->
							</div>
						</div>

						<!-- เหตุผล -->
						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/transfer_branch_out/transfer_branch_out', 'เหตุผล'); ?></label>
							<div class="input-group">
								<input name="oetPCKRsnName" id="oetPCKRsnName" class="form-control xWPointerEventNone xCNApvOrCanCelDisabled" value="<?= $tRsnName ?>" type="text" readonly placeholder="<?= language('document/transfer_branch_out/transfer_branch_out', 'เหตุผล') ?>">
								<input name="oetPCKRsnCode" id="oetPCKRsnCode" value="<?= $tRsnCode ?>" class="form-control xCNHide xCNApvOrCanCelDisabled" type="text">
								<span class="input-group-btn">
									<button class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled" id="obtPCKBrowseReason" type="button">
										<img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>
						<!-- เหตุผล -->

						<!-- ผู้หยิบสินค้า -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ผู้หยิบสินค้า'); ?></label>
							<div class="input-group">
								<input type="text" class="input100 xCNHide" id="oetPCKUsrCode" name="oetPCKUsrCode" maxlength="5" value="<?= $tUsrCode ?>">
								<input class="form-control xWPointerEventNone" type="text" id="oetPCKUsrName" name="oetPCKUsrName" value="<?= $tUsrName ?>" readonly placeholder="ผู้หยิบสินค้า">
								<span class="input-group-btn xWConditionSearchPdt">
									<button id="obtPCKBrowseUsr" type="button" class="btn xCNBtnBrowseAddOn xCNApvOrCanCelDisabled">
										<img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>
						<!-- ผู้หยิบสินค้า -->

					</div>
				</div>
			</div>



			<!-- ข้อมูลลูกค้า -->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div id="odvPCKRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'ข้อมูลลูกค้า'); ?></label>
					<a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPCKRefInfo" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvPCKRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
					<div class="panel-body" style="padding-top: 0px !important">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
								<!-- Browse ชื่อลูกค้า -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
									<div class="form-group">
										<input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="ohdPCKCstCode" name="ohdPCKCstCode" maxlength="5" value="<?= $tCstCode ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetPCKCstName" name="oetPCKCstName" maxlength="100" value="<?= $tCstName ?>" readonly data-validate-required="<?php echo language('document/inspectionafterservice/inspectionafterservice', 'tJOBCstValidate'); ?>" placeholder="<?php echo language('customer/customer/customer', 'tCSTTitle'); ?>">
									</div>
								</div>

								<!-- ที่อยู่ -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'ที่อยู่'); ?></label>
									<?php @$tAddress = $aCSTAddress[0]; ?>
									<?php //if (@$tAddress['FTAddVersion'] == 1) { 
									?>
									<!-- <textarea name="otaPCKCstAddress" id="otaPCKCstAddress" cols="30" rows="4" readonly>
                                            <?= @$tAddress['FTAddV1No'] ?> <?= @$tAddress['FTAddV1Soi'] ?> <?= @$tAddress['FTAddV1Road'] ?> <?= @$tAddress['FTSudName'] ?> <?= @$tAddress['FTDstName'] ?> <?= @$tAddress['FTPvnName'] ?> <?= @$tAddress['FTAddV1PostCode'] ?>
                                        </textarea>  -->
									<?php //} elseif (@$tAddress['FTAddVersion'] == 2) { 
									?>
									<textarea name="otaPCKCstAddress" id="otaPCKCstAddress" cols="30" rows="4" readonly><?= @$tAddress['FTAddV2Desc1'] ?> <?= @$tAddress['FTAddV2Desc2'] ?></textarea>
									<?php //} else { 
									?>
									<!-- <textarea name="otaPCKCstAddress" id="otaPCKCstAddress" cols="30" rows="4" readonly>-</textarea> -->
									<?php //} 
									?>
								</div>

								<!-- เบอร์โทรศัพท์ -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCstTelNo'); ?></label>
									<input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetPCKCstTel" name="oetPCKCstTel" placeholder="<?php echo language('customer/customer/customer', 'tCstTelNo'); ?>" value="<?= $tCstTel ?>" readonly>
								</div>

								<!-- e-mail -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTEmail'); ?></label>
									<input type="email" class="form-control xCNInputWhenStaCancelDoc" id="oetPCKCstMail" name="oetPCKCstMail" placeholder="<?php echo language('customer/customer/customer', 'tCSTEmail'); ?>" value="<?= $tCstEmail ?>" readonly>
								</div>

								<!-- ทะเบียน -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'ทะเบียน'); ?></label>
									<input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetPCKCstRegCar" name="oetPCKCstRegCar" placeholder="<?php echo language('customer/customer/customer', 'ทะเบียน'); ?>" value="<?= $tCstCarRegNo ?>" readonly>
								</div>

								<!-- ยี่ห้อ -->
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'ยี่ห้อ'); ?></label>
									<input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetPCKCstCarBrand" name="oetPCKCstCarBrand" placeholder="<?php echo language('customer/customer/customer', 'ยี่ห้อ'); ?>" value="<?= $tCstBndName ?>" readonly>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>




			<!--Section : อื่นๆ-->
			<div class="panel panel-default" style="margin-bottom: 25px;">
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
							<textarea class="form-control xCNInputWithoutSpc " id="otaPCKXthRmk" name="otaPCKXthRmk" maxlength="200"><?php echo $tRmk; ?></textarea>
						</div>
						<!-- หมายเหตุ -->

						<!-- เคลื่อนไหว -->
						<div class="form-group">
							<label class="fancy-checkbox">
								<input class="xCNApvOrCanCelDisabled" type="checkbox" value="1" <?php echo ($nStaDocAct == 1) ? 'checked' : ''; ?> id="ocbPCKXthStaDocAct" name="ocbPCKXthStaDocAct" maxlength="1">
								<span>&nbsp;</span>
								<span class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เคลื่อนไหว'); ?></span>
							</label>
						</div>
						<!-- เคลื่อนไหว -->

						<!-- สถานะอ้างอิง -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'สถานะอ้างอิง'); ?></label>
							<select class="selectpicker form-control xCNApvOrCanCelDisabled" id="ostPCKXthStaRef" name="ostPCKXthStaRef" maxlength="1">
								<option value="0" <?php echo ($tStaRef == "0") ? 'checked' : ''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ไม่เคยอ้างอิง'); ?></option>
								<option value="1" <?php echo ($tStaRef == "1") ? 'checked' : ''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงบางส่วน'); ?></option>
								<option value="2" <?php echo ($tStaRef == "2") ? 'checked' : ''; ?>><?php echo language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงทั้งหมด'); ?></option>
							</select>
						</div>
						<!-- สถานะอ้างอิง -->

						<!-- จำนวนครั้งที่ปริ้น -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'จำนวนครั้งที่ปริ้น'); ?></label>
							<input readonly type="text" class="form-control xCNInputWithoutSpc xCNApvOrCanCelDisabled" maxlength="100" id="oetPCKXthDocPrint" name="oetPCKXthDocPrint" maxlength="1" value="">
						</div>
						<!-- จำนวนครั้งที่ปริ้น -->

						<!-- ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม -->
						<!-- <div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม'); ?></label>
							<select class="selectpicker form-control xCNApvOrCanCelDisabled" id="ocmPCKOptionAddPdt" name="ocmPCKOptionAddPdt">
								<option value="1"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'บวกจำนวนในรายการเดิม'); ?></option>
								<option value="2"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'เพิ่มแถวใหม่'); ?></option>
							</select>
						</div> -->
						<!-- ตัวเลือกในการเพิ่มรายการสินค้าจากเมนูสแกนสินค้าในหน้าเอกสาร * กรณีเพิ่มสินค้าเดิม -->
					</div>
				</div>
			</div>


			<!-- Panel ไฟลแนบ -->
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div id="odvSOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataFile" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvSODataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPCKShowDataTable">


							</div>
						</div>
					</div>
				</div>
				<script>
					var oPCKCallDataTableFile = {
						ptElementID: 'odvPCKShowDataTable',
						ptBchCode: $('#oetPCKBchCode').val(),
						ptDocNo: $('#oetPCKDocNo').val(),
						ptDocKey: 'TCNTPdtPickHD',
						ptSessionID: '<?= $this->session->userdata("tSesSessionID") ?>',
						pnEvent: '<?= $nStaUploadFile ?>',
						ptCallBackFunct: '',
						ptStaApv: $('#ohdPCKStaApv').val(),
						ptStaDoc: $('#ohdPCKStaDoc').val()
						//JSxSoCallBackUploadFile -- ดูข้อมูลไฟล์แนบ
					}
					JCNxUPFCallDataTable(oPCKCallDataTableFile);
				</script>
			</div>
		</div>

		<!--Panel ตารางฝั่งขวา-->
		<div class="col-md-9" id="odvRightPanal">
			<div class="panel panel-default xCNPCKPCKPdtContainer" style="margin-bottom: 25px;">

				<div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="custom-tabs-line tabs-line-bottom left-aligned">
									<ul class="nav" role="tablist">

										<!-- สินค้า -->
										<li class="xWMenu active xCNStaHideShow" style="cursor:pointer;" id="oliPCKContentProduct">
											<a role="tab" data-toggle="tab" data-target="#odvPCKContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
										</li>

										<!-- อ้างอิง -->
										<li class="xWMenu xCNStaHideShow" style="cursor:pointer;" id="oliPCKContentHDRef">
											<a role="tab" data-toggle="tab" data-target="#odvPCKContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
										</li>

									</ul>
								</div>
							</div>
						</div>

						<div class="tab-content">
							<!-- รายการสินค้า -->
							<div id="odvPCKContentProduct" class="panel-collapse collapse in" role="tabpanel">
								<!-- <div class="panel-body xCNPDModlue"> -->
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
									<!-- <div class="col-xs-12 <?php echo (!$bIsApvOrCancel) ? 'col-sm-5 col-md-5 col-lg-7' : 'col-sm-6 col-md-6 col-lg-6'; ?> text-right"> -->
									<div class="col-xs-12 <?php echo (!$bIsApvOrCancel) ? 'col-sm-5 col-md-5 col-lg-8' : 'col-sm-6 col-md-6 col-lg-8'; ?> text-right">
										<?php if (!$bIsApvOrCancel) { ?>
											<div id="odvPCKMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
												<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
													<?= language('common/main/main', 'tCMNOption') ?> <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li id="oliPCKBtnDeleteMulti" class="disabled">
														<a data-toggle="modal" data-target="#odvPCKModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
													</li>
												</ul>
											</div>
										<?php } ?>
									</div>
									<?php if (!$bIsApvOrCancel) { ?>
										<!-- <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
											<div class="form-group">
												<div style="position: absolute;right: 15px;">
													<button type="button" class="xCNBTNPrimeryPlus xCNPCKBtnBrowsePdt" onclick="JCNvPCKBrowsePdt()">+</button>
												</div>
											</div>
										</div> -->
									<?php } ?>
								</div>
								<div id="odvPCKPdtDataTable"></div>
								<!-- </div> -->
							</div>

							<div id="odvPCKContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
								<div class="row p-t-15">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<div style="margin-top:-2px;">
											<button type="button" id="obtPCKAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
										</div>
									</div>
									<div id="odvPCKTableHDRef"></div>

								</div>

							</div>



						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php if (!$bIsApvOrCancel) { ?>
	<!-- Begin Approve Doc -->
	<div class="modal fade xCNModalApprove" id="odvPCKPopupApv">
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
					<button onclick="JSvPCKApprove(true)" type="button" class="btn xCNBTNPrimery">
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

	<!-- Begin Cancel Doc -->
	<div class="modal fade" id="odvPCKPopupCancel">
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
					<button onclick="JSvPCKCancel(true)" type="button" class="btn xCNBTNPrimery">
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
<?php } ?>

<!-- Begin Pdt Column Control Panel -->
<div class="modal fade" id="odvPCKPdtColumnControlPanel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="odvPCKPdtColummControlDetail">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
				<button type="button" class="btn xCNBTNPrimery" onclick="JSxPCKUpdatePdtColumn()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
			</div>
		</div>
	</div>
</div>
<!-- End Add Cash Panel -->

<!-- =========================================== อ้างอิงเอกสารภายใน ======================================= -->
<div id="odvPCKBchModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document" style="width: 1200px;">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?= language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงเอกสารภายใน') ?></label>
			</div>
			<div class="modal-body">
				<div class="row" id="odvPCKBchFromRefIntDoc"></div>
			</div>
			<div class="modal-footer">
				<button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
			</div>
		</div>
	</div>
</div>


<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvPCKModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="ofmPCKFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
				<div class="modal-header xCNModalHead">
					<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
				</div>
				<div class="modal-body">
					<input type="text" class="form-control xCNHide" id="oetPCKRefDocNoOld" name="oetPCKRefDocNoOld">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
								<select class="selectpicker form-control" id="ocbPCKRefType" name="ocbPCKRefType">
									<option value="1" selected><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
									<option value="3"><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
								<select class="selectpicker form-control" id="ocbPCKRefDoc" name="ocbPCKRefDoc">
									<option value="1" selected><?php echo language('common/main/main', 'ใบสั่งงาน'); ?></option>
								</select>
							</div>
						</div>
						<!-- อ้างอิงภายใน -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
								<div class="input-group">
									<input type="text" class="form-control xWPointerEventNone" id="oetPCKRefIntDoc" name="oetPCKRefIntDoc" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
									<span class="input-group-btn">
										<button id="obtPCKBrowseRefDocInt" type="button" class="btn xCNBtnBrowseAddOn">
											<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
							<div class="form-group">
								<label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
								<input type="text" class="form-control" id="oetPCKRefDocNo" name="oetPCKRefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
								<div class="input-group">
									<input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPCKRefDocDate" name="oetPCKRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
									<span class="input-group-btn">
										<button id="obtPCKRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
								<input type="text" class="form-control" id="oetPCKRefKey" name="oetPCKRefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="obtPCKConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
					<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- ============================================================================================================================================================================= -->
<div id="odvPCKModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document" style="width: 1200px;">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<!-- <label class="xCNTextModalHeard"><?= language('document/saleorder/saleorder', 'tPCKTitlePanelRefQT') ?></label>-->
				<label class="xCNTextModalHeard"><?= language('document/saleorder/saleorder', 'อ้างอิงใบสั่งงาน') ?></label>
			</div>
			<div class="modal-body">
				<div class="row" id="odvPCKFromRefIntDoc"></div>
			</div>
			<div class="modal-footer">
				<button id="obtConfirmRefDocPCK" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
			</div>
		</div>
	</div>
</div>


<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvPCKModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
			</div>
			<div class="modal-body">
				<span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
				<input type="hidden" id="ohdConfirmPCKDocNoDelete" name="ohdConfirmPCKDocNoDelete">
				<input type="hidden" id="ohdConfirmPCKSeqNoDelete" name="ohdConfirmPCKSeqNoDelete">
				<input type="hidden" id="ohdConfirmPCKPdtCodeDelete" name="ohdConfirmPCKPdtCodeDelete">
				<input type="hidden" id="ohdConfirmPCKPunCodeDelete" name="ohdConfirmPCKPunCodeDelete">

			</div>
			<div class="modal-footer">
				<button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
			</div>
		</div>
	</div>
</div>
<!-- ============================================================================================================================================================================= -->

<?php include('script/jProductPickPageadd.php') ?>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script type="text/javascript">
	$('#obtPCKRefInt').on('click', function() {
		JSxCallPagePCKRefIntDoc();
	});

	//Ref เอกสาร
	function JSxCallPagePCKRefIntDoc() {
		var tBCHCode = $('#oetPCKBchCode').val()
		var tBCHName = $('#oetPCKBchName').val()

		JCNxOpenLoading();
		$.ajax({
			type: "POST",
			url: "docPCKRefIntDoc",
			data: {
				'tBCHCode': tBCHCode,
				'tBCHName': tBCHName,
			},
			cache: false,
			Timeout: 0,
			success: function(oResult) {
				JCNxCloseLoading();
				$('#odvPCKBchModalRefIntDoc #odvPCKBchFromRefIntDoc').html(oResult);
				$('#odvPCKBchModalRefIntDoc').modal({
					backdrop: 'static',
					show: true
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}

	// เวลา Click Tab1 ให้ Button Hide
	$('#oliPCKContentProduct').click(function() {
		$('#odvPCKContentHDRef').hide();
		$('#odvPCKContentProduct').show();
	});

	// เวลา Click Tab2 ให้ Button Show
	$('#oliPCKContentHDRef').click(function() {
		$('#odvPCKContentProduct').hide();
		$('#odvPCKContentHDRef').show();
	});

	$('#obtPCKBrowseRefDocInt').on('click', function() {
		JSxCallPagePCKBrowseRefDoc();
	});

	//Ref เอกสารใบเสนอราคา
	function JSxCallPagePCKBrowseRefDoc() {
		var tBCHCode = $('#oetPCKBchCode').val()
		var tBCHName = $('#oetPCKBchName').val()

		JCNxOpenLoading();
		$.ajax({
			type: "POST",
			url: "docPCKRefIntDoc",
			data: {
				'tBCHCode': tBCHCode,
				'tBCHName': tBCHName,
			},
			cache: false,
			Timeout: 0,
			success: function(oResult) {
				JCNxCloseLoading();
				$('#odvPCKModalRefIntDoc #odvPCKFromRefIntDoc').html(oResult);
				$('#odvPCKModalRefIntDoc').modal({
					backdrop: 'static',
					show: true
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				JCNxResponseError(jqXHR, textStatus, errorThrown);
			}
		});
	}

	//Browse Event Users
	$('#obtPCKBrowseUsr').unbind().click(function() {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
			JSxCheckPinMenuClose();
			window.oOptionReturnUsers = undefined;
			oOptionReturnUsers = oRptBrowseUsers({
				'tReturnInputUsersCode': 'oetPCKUsrCode',
				'tReturnInputUsersName': 'oetPCKUsrName',
				'tNextFuncName': 'JSxRptConsNextFuncBrowseUsers',
				'aArgReturn': ['FTUsrCode', 'FTUsrName']
			});
			JCNxBrowseData('oOptionReturnUsers');
		} else {
			JCNxShowMsgSessionExpired();
		}
	});

	// Option Users
	var oRptBrowseUsers = function(poUsersReturnInput) {
		let tReturnInputUsersCode = poUsersReturnInput.tReturnInputUsersCode;
		let tReturnInputUsersName = poUsersReturnInput.tReturnInputUsersName;
		let tNextFuncName = poUsersReturnInput.tNextFuncName;
		let aArgReturn = poUsersReturnInput.aArgReturn;
		let tWhereFilter = "";
		if (tUserLoginLevel != "HQ") {
			if ($('#oetPCKAgnCode').val() != '') {
				tWhereFilter = " AND TCNTUsrGroup.FTAgnCode = '" + $('#oetPCKAgnCode').val() + "' ";
			} else {
				tWhereFilter = " AND TCNTUsrGroup.FTBchCode IN(<?php echo $this->session->userdata('tSesUsrBchCodeMulti'); ?>) ";
			}
		} else {
			if ($('#oetPCKAgnCode').val() != '') {
				tWhereFilter = " AND TCNTUsrGroup.FTAgnCode = '" + $('#oetPCKAgnCode').val() + "' ";
			} else {
				tWhereFilter = " ";
			}
		}

		let oOptionReturnUsers = {
			Title: ['report/report/report', 'ผู้หยิบสินค้า'],
			Table: {
				Master: 'TCNMUser',
				PK: 'FTUsrCode'
			},
			Join: {
				Table: ['TCNMUser_L', 'TCNTUsrGroup'],
				On: [
					'TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
					'TCNMUser.FTUsrCode = TCNTUsrGroup.FTUsrCode'
				]
			},
			Where: {
				Condition: [tWhereFilter]
			},
			GrideView: {
				ColumnPathLang: 'report/report/report',
				ColumnKeyLang: ['tUsrCashierCode', 'ชื่อผู้หยิบสินค้า', ''],
				ColumnsSize: ['15%', '85%'],
				WidthModal: 50,
				DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
				DataColumnsFormat: ['', ''],
				Perpage: 10,
				OrderBy: ['TCNMUser.FDCreateOn DESC'],
			},
			CallBack: {
				ReturnType: 'S',
				Value: [tReturnInputUsersCode, "TCNMUser.FTUsrCode"],
				Text: [tReturnInputUsersName, "TCNMUser_L.FTUsrName"],
			},
			NextFunc: {
				FuncName: tNextFuncName,
				ArgReturn: aArgReturn
			},
			// DebugSQL: true
		}
		return oOptionReturnUsers;
	}

	function JSxRptConsNextFuncBrowseUsers(poDataNextFunc) {
		if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
			var aDataNextFunc = JSON.parse(poDataNextFunc);
			var tUsersCode = aDataNextFunc[0];
			var tUsersName = aDataNextFunc[1];

			$('#oetPCKUsrCode').val(tUsersCode);
			$('#oetPCKUsrName').val(tUsersName);

		}
	}
</script>