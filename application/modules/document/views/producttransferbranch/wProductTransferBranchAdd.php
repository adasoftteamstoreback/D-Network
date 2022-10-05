<style>
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
</style>

<?php
	if($aResult['rtCode'] == "1"){
		$tXthDocNo 				= $aResult['raItems']['FTXthDocNo'];
		$dXthDocDate 			= $aResult['raItems']['FDXthDocDate'];
		$tXthDocTime 			= $aResult['raItems']['FTXthDocTime'];
		$tCreateBy 				= $aResult['raItems']['FTCreateBy'];
		$tXthStaDoc 			= $aResult['raItems']['FTXthStaDoc'];
		$tXthStaApv 			= $aResult['raItems']['FTXthStaApv'];
		$tXthApvCode 			= $aResult['raItems']['FTXthApvCode'];
		$tXthStaPrcStk 			= $aResult['raItems']['FTXthStaPrcStk'];
		$tXthStaDelMQ 			= $aResult['raItems']['FTXthStaDelMQ'];
		$tBchCode 				= $aResult['raItems']['FTBchCode'];
		$tBchName 				= $aResult['raItems']['FTBchName'];
		$tXthRefExt 			= $aResult['raItems']['FTXthRefExt'];
		$dXthRefExtDate 		= $aResult['raItems']['FDXthRefExtDate'];
		$tXthRefInt 			= $aResult['raItems']['FTXthRefInt'];
		$tXthCtrName			= $aResult['raItems']['FTXthCtrName'];
		$dXthTnfDate			= $aResult['raItems']['FDXthTnfDate'];
		$tXthRefTnfID			= $aResult['raItems']['FTXthRefTnfID'];
		$tViaCode				= $aDataHDRef['raItems']['FTViaCode'];
		$tViaName				= $aDataHDRef['raItems']['FTViaName'];
		$tXthRefVehID 			= $aResult['raItems']['FTXthRefVehID'];
		$tXthQtyAndTypeUnit		= $aResult['raItems']['FTXthQtyAndTypeUnit'];
		$tXthShipAdd			= $aResult['raItems']['FNXthShipAdd'];
		$nXthStaDocAct 			= $aResult['raItems']['FNXthStaDocAct'];
		$nXthDocPrint 			= $aResult['raItems']['FNXthDocPrint'];
		$tXthRmk 				= $aResult['raItems']['FTXthRmk'];
		$tXthVATInOrEx			= $aResult['raItems']['FTXthVATInOrEx'];
		$tDptCode 				= $aResult['raItems']['FTDptCode'];
		$tDptName 				= $aResult['raItems']['FTDptName'];
		$tUsrCode 				= $aResult['raItems']['FTUsrCode'];
		$tUsrNameCreateBy		= $aResult['raItems']['FTUsrName'];
		$tXthUsrNameApv 		= $aResult['raItems']['FTUsrNameApv'];
		$dXthRefIntDate 		= $aResult['raItems']['FDXthRefIntDate'];
		$cXthVat 				= $aResult['raItems']['FCXthVat'];
		$cXthVatable 			= $aResult['raItems']['FCXthVatable'];

		if(isset($aAlwEvent)){
			if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaEdit'] == 1){
				$nAutStaEdit = 1;
			}else{
				$nAutStaEdit = 0;
			}
		}else{
			$nAutStaEdit = 0;
		}

		$tFNAddSeqNo 			= $aDataHDRef["raItems"]["FNAddSeqNo"];
		$tFTAddV1No 			= $aDataHDRef["raItems"]["FTAddV1No"];
		$tFTAddV1Soi 			= $aDataHDRef["raItems"]["FTAddV1Soi"];
		$tFTAddV1Village 		= $aDataHDRef["raItems"]["FTAddV1Village"];
		$tFTAddV1Road 			= $aDataHDRef["raItems"]["FTAddV1Road"];
		$tFTSudName 			= $aDataHDRef["raItems"]["FTSudName"];
		$tFTDstName 			= $aDataHDRef["raItems"]["FTDstName"];
		$tFTPvnName 			= $aDataHDRef["raItems"]["FTPvnName"];
		$tFTAddV1PostCode 		= $aDataHDRef["raItems"]["FTAddV1PostCode"];
		$tRoute         		= "TBXEventEdit";

		//เงื่อนไขการโอน
		//ต้นทาง
		$tBchCodeFrom      		= $aResult['raItems']['FTBchCodeFrm'];
		$tBchNameFrom      		= $aResult['raItems']['FTBchNameFrm'];
		$tMerCodeFrom      		= $aResult['raItems']['FTMerCodeFrm'];
		$tMerNameFrom      		= $aResult['raItems']['FTMerNameFrm'];
		$tShpCodeFrom      		= $aResult['raItems']['FTShpCodeFrm'];
		$tShpNameFrom      		= $aResult['raItems']['FTShpNameFrm'];
		$tWahCodeFrom			= $aResult['raItems']['FTWahCodeFrm'];
		$tWahNameFrom			= $aResult['raItems']['FTWahNameFrm'];

		//ปลายทาง
		$tBchCodeTo				= $aResult['raItems']['FTBchCodeTo'];
		$tBchNameTo				= $aResult['raItems']['FTBchNameTo'];
		$tWahCodeTo				= $aResult['raItems']['FTWahCodeTo'];
		$tWahNameTo				= $aResult['raItems']['FTWahNameTo'];
		$tRsnCode				= $aResult['raItems']['FTRsnCode'];
		$tRsnName				= $aResult['raItems']['FTRsnName'];
	}else{

		$tBchCode 				= $this->session->userdata("tSesUsrBchCodeDefault");
		$tBchName 				= $this->session->userdata("tSesUsrBchNameDefault");
		$tUsrNameCreateBy		= $this->session->userdata('tSesUsrUsername');
		$tRoute					= "TBXEventAdd";
		$nAutStaEdit 			= 0; 

		//สถานะ
		$tXthDocNo 				= "";
		$dXthDocDate 			= "";
		$tXthDocTime 			= date('H:i:s');
		$tXthStaDoc 			= "";
		$tXthStaApv 			= "";
		$tXthStaPrcStk 			= "";
		$tXthStaDelMQ 			= "";
		$tDptCode				= $tDptCode;
		$tDptName				= $tDptName;
		$tUsrCode				= $tUsrCode;

		//เงื่อนไขการโอน
		//ต้นทาง
		$tBchCodeFrom      		= $tBchCodeFrom;
		$tBchNameFrom      		= $tBchNameFrom;
		$tMerCodeFrom      		= $tMerCodeFrom;
		$tMerNameFrom      		= $tMerNameFrom;
		$tShpCodeFrom      		= $tShpCodeFrom;
		$tShpNameFrom      		= $tShpNameFrom;
		$tWahCodeFrom			= $this->session->userdata("tSesUsrWahCode");
		$tWahNameFrom			= $this->session->userdata("tSesUsrWahName");

		//ปลายทาง
		$tBchCodeTo				= "";
		$tBchNameTo				= "";
		$tWahCodeTo				= "";
		$tWahNameTo				= "";
		$tXthRefExt 			= "";
		$dXthRefExtDate 		= "";
		$tXthRefInt 			= "";
		$tXthCtrName	 		= "";
		$dXthTnfDate			= "";
		$tXthRefTnfID			= "";
		$tViaCode				= "";
		$tViaName				= "";
		$tXthRefVehID 			= "";
		$tXthQtyAndTypeUnit		= "";
		$tXthShipAdd			= "";
		$nXthStaDocAct 			= "1";
		$nXthDocPrint 			= "0";
		$dXthRefIntDate 		= "";
		$tRsnCode				= "";
		$tRsnName				= "";
	}

	$tUserType 		= $this->session->userdata("tSesUsrLevel");
	$bIsApv         = empty($tXthStaApv) ? false : true;
    $bIsCancel      = ($tXthStaDoc == "3") ? true : false;
    $bIsApvOrCancel = ($bIsApv || $bIsCancel);
?>

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddTB">
    <input type="hidden" id="ohdTbxObjPdtFhnCallBack" name="ohdTbxObjPdtFhnCallBack" value="">
	<input type="hidden" id="ohdTbxStaWaitScanOn" name="ohdTbxStaWaitScanOn" value="0">
	<input type="hidden" id="ohdTBAutStaEdit" name="ohdTBAutStaEdit" value="<?php echo $nAutStaEdit; ?>">
	<input type="hidden" id="ohdXthStaApv" name="ohdXthStaApv" value="<?php echo $tXthStaApv; ?>">
	<input type="hidden" class="" id="ohdXthStaDoc" name="ohdXthStaDoc" value="<?php echo $tXthStaDoc; ?>">
	<input type="hidden" id="ohdXthStaPrcStk" name="ohdXthStaPrcStk" value="<?php echo $tXthStaPrcStk; ?>">
	<input type="hidden" id="ohdXthStaDelMQ" name="ohdXthStaDelMQ" value="<?php echo $tXthStaDelMQ; ?>">
	<input type="hidden" id="ohdTBRoute" name="ohdTBRoute" value="<?php echo $tRoute; ?>">
	<button style="display:none" type="submit" id="obtSubmitTB" onclick="JSnAddEditTB();"></button>
	<input type="text" class="xCNHide" id="ohdSesUsrBchCode" name="ohdSesUsrBchCode" value="<?php echo $this->session->userdata("tSesUsrBchCode"); ?>">
	<input type="text" class="xCNHide" id="ohdBchCode" name="ohdBchCode" value="<?=$tBchCodeFrom;?>">
	<!-- <input type="text" class="xCNHide" id="ohdTbxBchCode" name="ohdTbxBchCode" value="<?=$tBchCode;?>">  -->
	<input type="text" class="xCNHide" id="ohdOptAlwSavQty0" name="ohdOptAlwSavQty0" value="<?php echo $nOptDocSave?>">
	<input type="text" class="xCNHide" id="ohdOptScanSku" name="ohdOptScanSku" value="<?php echo $nOptScanSku?>">
	<input type="text" class="xCNHide" id="ohdDptCode" name="ohdDptCode" maxlength="5" value="<?php echo $tDptCode;?>">
	<input type="text" class="xCNHide" d="oetUsrCode" name="oetUsrCode" maxlength="20" value="<?php echo $tUsrCode?>">
	<input type="text" class="xCNHide" id="oetXthApvCodeUsrLogin" name="oetXthApvCodeUsrLogin" maxlength="20" value="<?php echo $this->session->userdata('tSesUsername'); ?>">
	<input type="text" class="xCNHide" id="ohdLangEdit" name="ohdLangEdit" maxlength="1" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
	<input type="hidden" id="ohdTWOnStaWasteWAH" name="ohdTWOnStaWasteWAH" value="<?= $nStaWasteWAH ?>">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-3 col-md-3">
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBStatus'); ?></label>
					<a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataPromotion" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvDataPromotion" class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<div class="form-group xCNHide" style="text-align: right;">
							<label class="xCNTitleFrom "><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBApproved'); ?></label>
						</div>
						<input type="hidden" value="0" id="ohdCheckTBSubmitByButton" name="ohdCheckTBSubmitByButton">
						<input type="hidden" value="0" id="ohdCheckTBClearValidate" name="ohdCheckTBClearValidate">
						<label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBDocNo'); ?></label>
						<?php
						if($tRoute=="TBXEventAdd"){
						?>
						<div class="form-group" id="odvPunAutoGenCode">
							<div class="validate-input">
								<label class="fancy-checkbox">
									<input type="checkbox" id="ocbTBAutoGenCode" name="ocbTBAutoGenCode" checked="true" value="1">
									<span> <?php echo language('document/producttransferbranch/producttransferbranch', 'tTBAutoGenCode'); ?></span>
								</label>
							</div>
						</div>
						<div class="form-group" id="odvPunCodeForm">
							<input
									type="text"
									class="form-control xCNInputWithoutSpcNotThai"
									maxlength="20"
									id="oetXthDocNo"
									name="oetXthDocNo"
									data-is-created=""
									placeholder="<?= language('document/producttransferbranch/producttransferbranch', 'tTBDocNo')?>"
									value=""
									data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch','tTBPlsEnterOrRunDocNo')?>"
									data-validate-dublicateCode="<?php echo language('document/producttransferbranch/producttransferbranch','tTBDocNoDuplicate')?>"
									readonly
									onfocus="this.blur()">
							<input type="hidden" value="2" id="ohdCheckDuplicateTB" name="ohdCheckDuplicateTB">
						</div>
						<?php
						}else{
						?>
						<div class="form-group" id="odvPunCodeForm">
							<div class="validate-input">
								<input
										type="text"
										class="form-control xCNInputWithoutSpcNotThai"
										maxlength="20"
										id="oetXthDocNo"
										name="oetXthDocNo"
										data-is-created=""
										placeholder="<?= language('document/producttransferbranch/producttransferbranch', 'tTBDocNo')?>"
										value="<?php echo $tXthDocNo; ?>"
										readonly
										onfocus="this.blur()">
							</div>
						</div>
						<?php
						}
						?>
						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBDocDate'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXthDocDate" name="oetXthDocDate" value="<?php echo $dXthDocDate; ?>" data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBPlsEnterDocDate'); ?>">
								<span class="input-group-btn">
									<button id="obtXthDocDate" type="button" class="btn xCNBtnDateTime">
										<img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
									</button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBDocTime'); ?></label>
							<div class="input-group">
								<input type="text" class="form-control xCNTimePicker" id="oetXthDocTime" name="oetXthDocTime" value="<?php echo $tXthDocTime; ?>" data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBPlsEnterDocTime'); ?>">
								<span class="input-group-btn">
									<button id="obtXthDocTime" type="button" class="btn xCNBtnDateTime">
										<img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
									</button>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBCreateBy'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<input type="text" class="xCNHide" id="oetCreateBy" name="oetCreateBy" value="<?=$tUsrNameCreateBy;?>">
								<label><?=$tUsrNameCreateBy;?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTBStaDoc'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<label><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBStaDoc'.$tXthStaDoc); ?></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTBStaApv'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<?php if($tXthStaDoc == 3){ ?>
									<label><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBStaDoc'.$tXthStaDoc); ?></label>
								<?php }else{ ?>
									<label><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBStaApv'.$tXthStaApv); ?></label>
								<?php } ?>
											
							</div>
						</div>
						<?php
						if($tXthDocNo != ''){
						?>
						<div class="row">
							<div class="col-md-6">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBApvBy'); ?></label>
							</div>
							<div class="col-md-6 text-right">
								<input type="text" class="xCNHide" id="oetXthApvCode" name="oetXthApvCode" maxlength="20" value="<?php echo $tXthApvCode?>">
								<label><?php echo $tXthUsrNameApv != '' ? $tXthUsrNameApv : language('document/producttransferbranch/producttransferbranch', 'tTBStaDoc'); ?></label>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div id="odvHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTnfCondition'); ?></label>
					<a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvWarehouse" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvWarehouse" class="panel-collapse collapse in" role="tabpanel">
					<div class="panel-body xCNPDModlue">

						<!-- สาขาที่สร้าง -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/transfer_branch_out/transfer_branch_out', 'tTBBchCreate'); ?></label>
							<div class="input-group" style="width:100%;">
								<input type="text" class="input100 xCNHide" id="ohdTbxBchCode" name="ohdTbxBchCode" value="<?=$tBchCode;?>">
								<input class="form-control xWPointerEventNone"  type="text" id="ohdTbxBchName" name="ohdTbxBchName" value="<?=$tBchName;?>" readonly
										placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTBBchCreate'); ?>"
								>
								<span class="input-group-btn">
									<button id="obtBrowseTBXBranchCreate" type="button" class="btn xCNBtnBrowseAddOn">
										<img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
									</button>
								</span>
							</div>
						</div>

						<!-- ต้นทาง -->
						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?=language('document/transferreceiptbranch/transferreceiptbranch','tTBIOrigin');?></legend>

							<!-- สาขา -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/purchaseinvoice/purchaseinvoice', 'tPILabelFrmBranch')?></label>
								<div class="input-group">
									<input
										type="text"
										class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
										id="oetTBBchCodeFrom"
										name="oetTBBchCodeFrom"
										maxlength="5"
										value="<?=$tBchCodeFrom?>"
									>
									<input
										type="text"
										class="form-control xWPointerEventNone"
										id="oetTBBchNameFrom"
										name="oetTBBchNameFrom"
										maxlength="100"
										placeholder="<?php echo language('document/purchaseinvoice/purchaseinvoice', 'tPILabelFrmBranch')?>"
										value="<?=$tBchNameFrom?>"
										readonly
									>
									<span class="input-group-btn">
										<button id="obtBrowseTWOBCH" type="button" class="btn xCNBtnBrowseAddOn">
											<img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

							<!-- กลุ่มร้านค้า -->
							<div class="form-group" style="display:none">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBMerShp'); ?></label>
								<div class="input-group">
									<input type="text" class="input100 xCNHide" id="oetTBMerCodeFrom" name="oetTBMerCodeFrom" maxlength="5" value="<?=$tMerCodeFrom;?>">
									<input class="form-control xWPointerEventNone" type="text" id="oetTBMerNameFrom" name="oetTBMerNameFrom" value="<?=$tMerNameFrom;?>" readonly
										data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBWahNameStartRequired'); ?>"
										placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBMerShp'); ?>"
									>
									<span class="input-group-btn xWConditionSearchPdt ">
										<button id="obtTBBrowseMerFrom" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

							<!-- ร้านค้า -->
							<div class="form-group" style="display:none">
								<label class="xCNLabelFrm"><?php echo language('company/shop/shop', 'tSHPTitle'); ?></label>
								<div class="input-group">
									<input class="form-control xCNHide" id="oetTBShopCodeFrom" name="oetTBShopCodeFrom" maxlength="5" value="<?=$tShpCodeFrom;?>">
									<input type="text" class="form-control xWPointerEventNone" id="oetTBShopNameFrom" name="oetTBShopNameFrom" value="<?=$tShpNameFrom;?>" readonly
										placeholder="<?php echo language('company/shop/shop', 'tSHPTitle'); ?>"
									>
									<span class="xWConDisDocument input-group-btn">
										<button id="obtTBBrowseShp" type="button" class="xWConDisDocument btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
									</span>
								</div>
							</div>

							<!-- คลังเริ่ม -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBWahPdt'); ?></label>
								<div class="input-group">
									<input type="text" class="input100 xCNHide" id="ohdWahCodeStart" name="ohdWahCodeStart" maxlength="5"
										value="<?=$tWahCodeFrom;?>">
									<input class="form-control xWPointerEventNone" type="text" id="oetWahNameStart" name="oetWahNameStart" value="<?=$tWahNameFrom;?>" readonly
										data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBPlsEnterWah'); ?>"
										placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBWahPdt'); ?>"
									>
									<span class="input-group-btn xWConditionSearchPdt">
										<button id="obtTBBrowseWahStart" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>
						</fieldset>

						<!-- ปลายทาง -->
						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?=language('document/transferreceiptbranch/transferreceiptbranch','tTBITo');?></legend>

							<!-- สาขา -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTBBch'); ?></label>
								<div class="input-group">
									<input class="form-control xCNHide" id="oetOldBchCodeEnd" name="oetOldBchCodeEnd" maxlength="5" value="">
									<input class="form-control xCNHide" id="oetTBBchCodeTo" name="oetTBBchCodeTo" maxlength="5" value="<?=$tBchCodeTo;?>">
									<input class="form-control xWPointerEventNone" type="text" id="oetTBBchNameTo" name="oetTBBchNameTo" value="<?=$tBchNameTo;?>" readonly
										data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBPlsEnterBch'); ?>"
										placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTBBch'); ?>"
									>
									<span class="xWConditionSearchPdt input-group-btn">
										<button id="obtTBBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

							<!-- คลัง -->
							<div class="form-group">
								<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBWahPdt'); ?></label>
								<div class="input-group">
									<input type="text" class="input100 xCNHide" id="oetTBWahCodeTo" name="oetTBWahCodeTo" maxlength="5" value="<?=$tWahCodeTo;?>">
									<input class="form-control xWPointerEventNone" type="text" id="oetTBWahNameTo" name="oetTBWahNameTo" value="<?=$tWahNameTo;?>" readonly
										data-validate-required="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBPlsEnterWah'); ?>"
										placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBWahPdt'); ?>"
									>
									<span class="input-group-btn xWConditionSearchPdt">
										<button id="obtTBBrowseWahTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
											<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
										</button>
									</span>
								</div>
							</div>

						</fieldset>
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div id="odvHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBReference'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDataGeneralInfo" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvDataGeneralInfo" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<!-- เลขที่อ้างอิงเอกสารภายนอก -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?></label>
									<input type="text" placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>" class="form-control xCNInputWithoutSpc" id="oetXthRefExt" name="oetXthRefExt" maxlength="20" value="<?php echo $tXthRefExt?>">
								</div>
							</div>
						</div>
						<!-- วันที่เอกสารภายนอก -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
									<div class="input-group">
										<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXthRefExtDate" name="oetXthRefExtDate" value="<?php echo $dXthRefExtDate?>">
										<span class="input-group-btn">
											<button id="obtXthRefExtDate" type="button" class="btn xCNBtnDateTime">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
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
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefInt'); ?></label>
									<input type="text" placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefInt'); ?>" class="form-control xCNInputWithoutSpc" id="oetXthRefInt" name="oetXthRefInt" maxlength="20" value="<?php echo $tXthRefInt?>">
								</div>
							</div>
						</div>
						<!-- วันที่เอกสารภายใน -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefIntDate'); ?></label>
									<div class="input-group">
										<input type="text"  placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXthRefIntDate" name="oetXthRefIntDate" value="<?php echo $dXthRefIntDate?>">
										<span class="input-group-btn">
											<button id="obtXthRefIntDate" type="button" class="btn xCNBtnDateTime">
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
			<div class="panel panel-default" style="margin-bottom: 25px;">
				<div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBDelivery'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDelivery" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvDelivery" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBCtrName'); ?></label>
									<input type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetXthCtrName" name="oetXthCtrName" value="<?php echo $tXthCtrName?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBTnfDate'); ?></label>
									<div class="input-group">
										<input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXthTnfDate" name="oetXthTnfDate" value="<?php echo $dXthTnfDate?>">
										<span class="input-group-btn">
											<button id="obtXthTnfDate" type="button" class="btn xCNBtnDateTime">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefTnfID'); ?></label>
									<input type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetXthRefTnfID" name="oetXthRefTnfID" value="<?php echo $tXthRefTnfID?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBViaCode'); ?></label>
									<div class="input-group">
										<input class="form-control xWPointerEventNone" type="text" id="oetViaName" name="oetViaName" value="<?php echo $tViaName?>" readonly>
										<input type="text" class="input100 xCNHide" id="oetViaCode" name="oetViaCode" value="<?php echo $tViaCode?>">
										<span class="input-group-btn">
											<button id="obtSearchShipVia" type="button" class="btn xCNBtnBrowseAddOn">
												<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefVehID'); ?></label>
									<input type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetXthRefVehID" name="oetXthRefVehID" value="<?php echo $tXthRefVehID?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBQtyAndTypeUnit'); ?></label>
									<input type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetXthQtyAndTypeUnit" name="oetXthQtyAndTypeUnit" value="<?php echo $tXthQtyAndTypeUnit?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input tyle="text" class="xCNHide" id="ohdXthShipAdd" name="ohdXthShipAdd" value="<?php echo $tXthShipAdd?>">
								<button type="button" id="obtTBBrowseShipAdd" class="btn btn-primary <?php
									if($tRoute=="TBXEventAdd"){
										echo " xWConditionSearchPdt disabled";
									}else{

									}

								?>" style="font-size: 17px;"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBShipAddress'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default" style="margin-bottom: 60px;">
				<div id="odvHeadAllow" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
					<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBOther'); ?></label>
					<a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvOther" aria-expanded="true">
						<i class="fa fa-plus xCNPlus"></i>
					</a>
				</div>
				<div id="odvOther" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body xCNPDModlue">
						
						<!-- เหตุผล -->
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('other/reason/reason', 'tRSNTitle'); ?></label>
							<div class="input-group">
								<input type="text" class="input100 xCNHide" id="oetTBRsnCode" name="oetTBRsnCode" maxlength="5" value="<?=$tRsnCode;?>">
								<input class="form-control xWPointerEventNone" type="text" id="oetTBRsnName" name="oetTBRsnName" value="<?=$tRsnName;?>" readonly placeholder="<?php echo language('other/reason/reason', 'tRSNTitle'); ?>">
								<span class="input-group-btn xWConditionSearchPdt">
									<button id="obtTBBrowseRsn" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
										<img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
									</button>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBNote'); ?></label>
							<textarea class="form-control xCNInputWithoutSpc" id="otaTBRmk" name="otaTBRmk" maxlength="200"><?php
							if($tRoute == "TBXEventEdit"){
								echo $tXthRmk;
							}
							?></textarea>
						</div>

						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBDocPrint'); ?></label>
							<input readonly type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetXthDocPrint" name="oetXthDocPrint" maxlength="1" value="<?=$nXthDocPrint?>">
						</div>

						<div class="form-group">
							<label class="fancy-checkbox">
								<input type="checkbox" value="1" id="ocbXthStaDocAct" name="ocbXthStaDocAct" maxlength="1" <?php echo $nXthStaDocAct == '' ? '' : $nXthStaDocAct == '1' ? 'checked' : ''; ?> >
								<span>&nbsp;</span>
								<span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tTBStaDocAct'); ?></span>
							</label>
						</div>

						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBOptionAddPdt'); ?></label>
							<select class="selectpicker form-control" id="ocmTBOptionAddPdt" name="ocmTBOptionAddPdt">
								<option value="1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBOptionAddPdtAddNumPdt'); ?></option>
								<option value="2" selected><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBOptionAddPdtAddNewRow'); ?></option>
							</select>
						</div> 
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" id="odvRightPanal">
			<div class="row">
				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-12">
					<div class="panel panel-default" style="position: relative;">
						<div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
							<div class="panel-body xCNPDModlue">
								<div class="row" style="margin-top: 10px;">

                                    <!--ค้นหาสินค้าใน Temp-->
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetTXFrmFilterPdtHTML" name="oetTXFrmFilterPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                                <span class="input-group-btn">
                                                    <button id="obtTXMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right xCNHideWhenCancelOrApprove">
											<div class="btn-group xCNDropDrownGroup">
												<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
													<?php echo language('common/main/main','tCMNOption')?>
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li id="oliTbxBtnDeleteMulti" class="disabled">
														<a data-toggle="modal" data-target="#odvTbxModalDelPdtInDTTempMultiple"><?php echo language('common/main/main','tDelAll')?></a>
													</li>
												</ul>
											</div>
									</div>

									<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
										<!--ค้นหาจากบาร์โค๊ด-->
										<div class="form-group">
											<input type="text" class="form-control xCNPdtEditInLine" id="oetTbxInsertBarcode"  autocomplete="off" name="oetTbxInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
										</div>
									</div>

									<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 xCNHideWhenCancelOrApprove">
										<div class="form-group">
											<button id="obtDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt" onclick="JCNvTBBrowsePdt()" type="button">+</button>
										</div>
									</div>
								</div>

								<div id="odvPdtTablePanal"></div>
								<div id="odvPdtTablePanalDataHide"></div>

								<!-- ท้ายบิล -->
								<!-- <?php //include('wProductTransferBranchEndOfBill.php');?> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Modal Address-->
<div class="modal fade" id="odvTBBrowseShipAdd">
	<div class="modal-dialog" style="width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBShipAddress'); ?></label>
					</div>
					<div class="col-xs-12 col-md-6 text-right">
							<button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnTBAddShipAdd()"><?php echo language('common/main/main', 'tModalConfirm')?></button>
							<button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<div class="panel panel-default" style="margin-bottom: 5px;">
							<div class="panel-heading xCNPanelHeadColor" style="padding-top:5px !important;padding-bottom:5px !important;">
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNTextDetail1"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBAddInfo'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6 text-right">
											<a style="font-size: 14px !important;color: #1866ae;">
												<i class="fa fa-pencil" id="oliBtnEditShipAdd"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBChange'); ?></i>
											</a>
										</div>
									</div>
							</div>
							<div>
								<div class="panel-body xCNPDModlue">
									<input type="text" class="xCNHide" id="ohdShipAddSeqNo" value="<?php
											if($tFNAddSeqNo!=""){
												echo $tFNAddSeqNo;
											}else{
												echo "";
											}
											?>">
									<?php
										$tFormat = FCNaHAddressFormat('TCNMBranch');//1 ที่อยู่ แบบแยก  ,2  แบบรวม
										if($tFormat == '1'):
									?>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1No'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddAddV1No"><?php
											if($tFNAddSeqNo!=""){
												echo $tFNAddSeqNo;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1Village'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1Soi"><?php
											if($tFTAddV1Soi!=""){
												echo $tFTAddV1Soi;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1Soi'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1Village"><?php
											if($tFTAddV1Village!=""){
												echo $tFTAddV1Village;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1Road'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1Road"><?php
											if($tFTAddV1Road!=""){
												echo $tFTAddV1Road;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1SubDist'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1SubDist"><?php
											if($tFTSudName!=""){
												echo $tFTSudName;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1DstCode'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1DstCode"><?php
											if($tFTDstName!=""){
												echo $tFTDstName;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1PvnCode'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1PvnCode"><?php
											if($tFTPvnName!=""){
												echo $tFTPvnName;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6">
											<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tBrowseADDV1PostCode'); ?></label>
										</div>
										<div class="col-xs-6 col-md-6">
											<label id="ospShipAddV1PostCode"><?php
											if($tFTAddV1PostCode!=""){
												echo $tFTAddV1PostCode;
											}else{
												echo "-";
											}
											?></label>
										</div>
									</div>
									<?php else:?>
										<div class="row">
												<div class="col-lg-12">
														<div class="form-group">
																<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch','tBrowseADDV2Desc1')?></label><br>
																<label id="ospShipAddV2Desc1" name="ospShipAddV2Desc1">-</label>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-lg-12">
														<div class="form-group">
																<label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch','tBrowseADDV2Desc2')?></label><br>
																<label id="ospShipAddV2Desc2" name="ospShipAddV2Desc2">-</label>
														</div>
												</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade xCNModalApprove" id="odvTBPopupApv">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main','tApproveTheDocument'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p><?php echo language('common/main/main','tMainApproveStatus'); ?></p>
					<ul>
						<li><?php echo language('common/main/main','tMainApproveStatus1'); ?></li>
						<li><?php echo language('common/main/main','tMainApproveStatus2'); ?></li>
						<li><?php echo language('common/main/main','tMainApproveStatus3'); ?></li>
						<li><?php echo language('common/main/main','tMainApproveStatus4'); ?></li>
					</ul>
				<p><?php echo language('common/main/main','tMainApproveStatus5'); ?></p>
				<p><strong><?php echo language('common/main/main','tMainApproveStatus6'); ?></strong></p>
			</div>
			<div class="modal-footer">
				<button onclick="JSnTBApprove(true)" type="button" class="btn xCNBTNPrimery">
					<?php echo language('common/main/main', 'tModalConfirm'); ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="odvShowOrderColumn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
        <button type="button" class="btn btn-primary" onclick="JSxSaveColumnShow()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="odvModalEditTBDisHD">
	<div class="modal-dialog xCNDisModal">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="display:inline-block"><label class="xCNLabelFrm"><?php echo language('common/main/main', 'tTBDisEndOfBill'); ?></label></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
						<label class="xCNLabelFrm"><?php echo language('common/main/main', 'tTBDisType'); ?></label>
						<select class="selectpicker form-control" id="ostXthHDDisChgText" name="ostXthHDDisChgText">
							<option value="3"><?php echo  language('document/producttransferbranch/producttransferbranch','tDisChgTxt3')?></option>
							<option value="4"><?php echo  language('document/producttransferbranch/producttransferbranch','tDisChgTxt4')?></option>
							<option value="1"><?php echo  language('document/producttransferbranch/producttransferbranch','tDisChgTxt1')?></option>
							<option value="2"><?php echo  language('document/producttransferbranch/producttransferbranch','tDisChgTxt2')?></option>
						</select>
					</div>
					</div>
					<div class="col-md-4">
						<label class="xCNLabelFrm"><?php echo language('common/main/main', 'tTBValue'); ?></label>
						<input type="text" class="form-control xCNInputNumericWithDecimal" id="oetXddHDDis" name="oetXddHDDis" maxlength="11" placeholder="">
					</div>
					<div class="col-md-2">
					<div class="form-group">
						<button type="button" class="btn btn-primary xCNBtnAddDis" onclick="FSvTBAddHDDis()">
						<label class="xCNLabelAddDis">+</label>
						</button>
					</div>
					</div>
				</div>
        		<div id="odvHDDisListPanal"></div>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="odvTBPopupCancel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('document/producttransferbranch/producttransferbranch','tTBPMsgCancel')?></label>
			</div>
			<div class="modal-body">
                <p id="obpMsgApv"><?=language('document/producttransferbranch/producttransferbranch','tTBPMsgDocProcess')?></p>
                <p><strong><?=language('document/producttransferbranch/producttransferbranch','tTBPMsgCanCancel')?></strong></p>
			</div>
			<div class="modal-footer">
                <button onclick="JSnTBCancel(true)" type="button" class="btn xCNBTNPrimery">
					<?=language('common/main/main', 'tModalConfirm'); ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<?=language('common/main/main', 'tModalCancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>


<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvTbxModalPDTNotFound" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();" >
                        <?php echo language('common/main/main', 'tModalConfirm'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<div id="odvTbxModalPDTMoreOne" class="modal fade">
        <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;">กรุณาเลือกสินค้า</label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal">เลือก</button>
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-striped xCNTablePDTMoreOne">
                        <thead>
                            <tr>
                                <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalcodePDT')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalnamePDT')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalPriceUnit')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalbarcodePDT')?></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->


<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvTbxModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                    <input type="hidden" id="ohdConfirmPIDocNoDelete"   name="ohdConfirmPIDocNoDelete">
                    <input type="hidden" id="ohdConfirmPISeqNoDelete"   name="ohdConfirmPISeqNoDelete">
                    <input type="hidden" id="ohdConfirmPIPdtCodeDelete" name="ohdConfirmPIPdtCodeDelete">
                    <input type="hidden" id="ohdConfirmPIPunCodeDelete" name="ohdConfirmPIPunCodeDelete">

                </div>
                <div class="modal-footer">
                    <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->


<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jProducttransferbranchAdd.php')?>

<script>
    function JSxNotFoundClose(){
        $('#oetTbxInsertBarcode').focus();
    }

	//กดเลือกบาร์โค๊ด
	function  JSxSearchFromBarcode(e,elem){
        var tValue = $(elem).val();
        // if($('#oetPIFrmSplCode').val() != ""){
            JSxCheckPinMenuClose();
            if(tValue.length === 0){
            }else{
                $('#oetTbxInsertBarcode').attr('readonly',true);
                JCNSearchBarcodePdt(tValue);
                $('#oetTbxInsertBarcode').val('');
            }
        // }else{
        //     $('#odvPIModalPleseselectCustomer').modal('show');
        //     $('#oetTbxInsertBarcode').val('');
        // }
        e.preventDefault();
    }

	//ค้นหาบาร์โค๊ด
	function JCNSearchBarcodePdt(ptTextScan){

        var tWhereCondition = " AND FTPdtStkControl = 1 ";

        $.ajax({
            type : "POST",
            url : "BrowseDataPDTTableCallView",
            data :{
                Qualitysearch   : [],
                ReturnType  : "M",
                aPriceType  : ["Cost","tCN_Cost","Company","1"],
                // aPriceType  : ['Price4Cst',tPISplCode],
                NextFunc    : "",
                SelectTier  : ["Barcode"],
                SPL         : $("#oetPIFrmSplCode").val(),
                BCH         : $("#oetPIFrmBchCode").val(),
                MCH         : $("#oetPIFrmMerCode").val(),
                SHP         : $("#oetPIFrmShpCode").val(),
                tInpSesSessionID : $('#ohdSesSessionID').val(),
                tInpSesUsrLevel  : $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom : $('#ohdSesUsrBchCom').val(),
                tInpLangEdit     : $('#ohdPILangEdit').val(),
                tWhere       : [tWhereCondition],
                tTextScan   : ptTextScan,
            },
            catch : false,
            timeout : 0,
            success : function (tResult){
				// localStorage.removeItem('TBX_LocalItemDataDelDtTemp');
                JCNxCloseLoading();
                $('#ohdTbxObjPdtFhnCallBack').val(tResult);
                var oText = JSON.parse(tResult);
                console.log('Event Scan',oText);
                if(oText == '800'){
                    $('#oetTbxInsertBarcode').attr('readonly',false);
                    $('#odvTbxModalPDTNotFound').modal('show');
                    $('#oetTbxInsertBarcode').val('');
                }else{
                    // พบสินค้ามีหลายบาร์โค้ด
                    if(oText.length > 1){
                        $('#odvTbxModalPDTMoreOne').modal('show');
                        $('#odvTbxModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');

                        for(i=0; i<oText.length; i++){
                            var aNewReturn      = JSON.stringify(oText[i]);
                            var tTest = "["+aNewReturn+"]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne"+i+" xCNColumnPDTMoreOne' data-information='"+oEncodePackData+"' style='cursor: pointer;'>";
                                tHTML += "<td>"+oText[i].pnPdtCode+"</td>";
                                tHTML += "<td>"+oText[i].packData.PDTName+"</td>";
                                tHTML += "<td>"+oText[i].packData.PUNName+"</td>";
                                tHTML += "<td>"+oText[i].ptBarCode+"</td>";
                                tHTML += "</tr>";
                            $('#odvTbxModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                            $('#odvTbxModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvTBXAddPdtIntoDocDTTempScan(tJSON); //Client
                            FSvTBXPDTAddPdtIntoTableDT(tJSON); //Server
                            // var oPIObjPdtFhnCallBack =  $('#ohdTbxObjPdtFhnCallBack').val();
                            var oJSONPdt = JSON.parse(tJSON);
                            var oOptionForFashion = {
                                    'bListItemAll'  : false,
                                    'tSpcControl'  : 0,
                                    'tNextFunc' : 'FSvTBXPDTAddPdtIntoTableDT'
                                }
                                JSxCheckProductSerialandFashion(oJSONPdt,oOptionForFashion,'insert');

                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click',function(e){
                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            // $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align','right');
                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            // $(this).children().last().css('text-align','right');
                        });

                    }else{
                        //มีตัวเดียว
                        var aNewReturn  = JSON.stringify(oText);
                        console.log('aNewReturn: '+aNewReturn);
                        FSvTBXAddPdtIntoDocDTTempScan(aNewReturn); //Client
                        FSvTBXPDTAddPdtIntoTableDT(aNewReturn); //Server
               
                        var oOptionForFashion = {
                                'bListItemAll'  : false,
                                'tSpcControl'  : 0,
                                'tNextFunc' : 'FSvTBXPDTAddPdtIntoTableDT'
                            }
                    JSxCheckProductSerialandFashion(oText,oOptionForFashion,'insert');
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown){
                JCNSearchBarcodePdt(ptTextScan);
            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType){
        if($ptType == 1){
            $("#odvTbxModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvTBXAddPdtIntoDocDTTempScan(tJSON);
                FSvTBXPDTAddPdtIntoTableDT(tJSON);
   
                var oJSONPdt = JSON.parse(tJSON);
                var oOptionForFashion = {
                                    'bListItemAll'  : false,
                                    'tSpcControl'  : 0,
                                    'tNextFunc' : 'FSvTBXPDTAddPdtIntoTableDT'
                                }
             JSxCheckProductSerialandFashion(oJSONPdt,oOptionForFashion,'insert');
            });
        }else{
            $('#oetTbxInsertBarcode').attr('readonly',false);
            $('#oetTbxInsertBarcode').val('');
        }
    }

	//Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
	function FSvTBXPDTAddPdtIntoTableDT(pjPdtData) {
		var nStaSession = JCNxFuncChkSessionExpired();
		if (typeof nStaSession !== "undefined" && nStaSession == 1) {
			pnXthVATInOrEx = $("#ostXthVATInOrEx").val();

			var ptXthDocNoSend = "";
			if ($("#ohdTBRoute").val() == "TBXEventEdit") {
				ptXthDocNoSend = $("#oetXthDocNo").val();
			}

			$('#oetTbxInsertBarcode').attr('readonly',false);
			$('#oetTbxInsertBarcode').val('');

			$.ajax({
				type: "POST",
				url: "TBXAddPdtIntoTableDT",
				data: {
					ptXthBchCode        : $("#ohdTbxBchCode").val(),
					ptXthDocNo          : ptXthDocNoSend,
					pnXthVATInOrEx      : pnXthVATInOrEx,
					pjPdtData           : pjPdtData,
					pnTBOptionAddPdt    : $("#ocmTBOptionAddPdt").val()
				},
				cache: false,
				timeout: 0,
				success: function (oResult) {
					var aResult = JSON.parse(oResult);
					if(aResult['nStaEvent']==1){
						// JCNxCloseLoading();
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					JCNxResponseError(jqXHR, textStatus, errorThrown);
				}
			});
		} else {
			JCNxShowMsgSessionExpired();
		}
	}

	//ค้นหาสินค้าใน temp
	function JSvDOCSearchPdtHTML() {
		var value = $("#oetTXFrmFilterPdtHTML").val().toLowerCase();
		$("#otbTbxDocPdtAdvTableList tbody tr ").filter(function () {
			tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	}
</script>