<?php
if ($aResult['rtCode'] == 1) {
	$tMshCode   	= $aResult['raItems']['rtMshCode'];
	$tMshName   	= $aResult['raItems']['rtMshName'];
	$tMshRemark 	= $aResult['raItems']['rtMshRmk'];
	$tMshStatus 	= $aResult['raItems']['rtMshStaActive'];
	$tAvdStart      = $aResult['raItems']['rtMshStart'];
	$tAdvStop       = $aResult['raItems']['rtMshFinish'];
	$tMenuTabDisable = "";
	$tMenuTabToggle = "tab";
	$tRoute     	= "messageEventEdit";
	$tMSGAgnCode       = $aResult['raItems']['rtAgnCode'];
	$tMSGAgnName       = $aResult['raItems']['rtAgnName'];
	$aMshDetail 	 	= $aResult['raDetailMessage'];
} else {
	$tMshCode   		= "";
	$tMshName   		= "";
	$tMshRemark 		= "";
	$tMshStatus 		= "1";
	$tRoute     		= "messageEventAdd";
	$tMenuTabToggle 	= "false";
	$tMenuTabDisable 	= " disabled xCNCloseTabNav";

	$tMSGAgnCode       = $tSesAgnCode;
	$tMSGAgnName       = $tSesAgnName;

	$tAvdStart      = $dGetDataNow;
	$tAdvStop       = $dGetDataFuture;
	$aMshDetail  	= [];
}

$aSelectType = array(
	'1' => 'ข้อความ',
	'2' => 'บาร์โค้ด',
	'3' => 'คิวอาร์โค้ด',
	'4' => 'รูปภาพ',
	'5' => 'รหัสอัตโนมัติ',
	'6' => 'วันที่',
	'7' => 'ขึ้นบรรทัดใหม่'
);
?>

<style>
	.xWSmgMoveIcon {
		cursor: move !important;
		border-radius: 0px;
		box-shadow: none;
		padding: 0px 10px;
	}
</style>
<div id="odvMsgPanelBody" class="panel-body" style="padding-top:20px !important;">

	<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddMessage">
		<button style="display:none" type="submit" id="obtSubmitMessage" onclick="JSnAddEditMessage('<?php echo $tRoute ?>')"></button>
		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/message/message', 'tMSGCode') ?></label>
				<div id="odvMessageAutoGenCode" class="form-group">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbMessageAutoGenCode" name="ocbMessageAutoGenCode" checked="true" value="1">
							<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
						</label>
					</div>
				</div>

				<div id="odvMessageCodeForm" class="form-group">
					<input type="hidden" id="ohdCheckDuplicateMsgCode" name="ohdCheckDuplicateMsgCode" value="1">
					<input type="hidden" id="ohdBaseurl" name="ohdBaseurl" value="<?php echo base_url() ?>">
					<div class="validate-input">
						<input type="text" class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" maxlength="5" id="oetMsgCode" name="oetMsgCode" data-is-created="<?php echo $tMshCode; ?>" placeholder="<?php echo language('service/message/message', 'tMSGCode') ?>" ; value="<?php echo $tMshCode; ?>" data-validate-required="<?php echo language('service/message/message', 'tMSGValidCode') ?>" data-validate-dublicateCode="<?php echo language('service/message/message', 'tMSGValidCodeDup') ?>">
					</div>
				</div>

			</div>
		</div>

		<?php
		if ($tRoute == "messageEventAdd") {
			$tDisabled     = '';
			$tNameElmIDAgn = 'oimBrowseAgn';
		} else {
			$tDisabled      = '';
			$tNameElmIDAgn  = 'oimBrowseAgn';
		}
		?>

		<div class="row">
			<div class="col-xs-12 col-md-5 col-lg-5">
				<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
										endif; ?>">
					<label class="xCNLabelFrm"></span><?php echo language('service/message/message', 'tMSGAgency') ?></label>
					<div class="input-group"><input class="form-control xCNHide" type="text" id="oetMsgAgnCode" name="oetMsgAgnCode" maxlength="5" value="<?php echo @$tMSGAgnCode; ?>">
						<input type="text" class="form-control xWPointerEventNone" id="oetMsgAgnName" name="oetMsgAgnName" maxlength="100" placeholder="<?php echo language('service/message/message', 'tMSGAgency'); ?>" value="<?php echo @$tMSGAgnName; ?>" readonly>
						<span class="input-group-btn">
							<button id="<?= @$tNameElmIDAgn; ?>" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
								<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
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
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/message/message', 'tMSGName') ?></label>
						<input type="text" class="form-control" maxlength="200" id="oetMsgName" name="oetMsgName" placeholder="<?php echo language('service/message/message', 'tMSGName') ?>" value="<?php echo $tMshName ?>" data-validate-required="<?php echo language('service/message/message', 'tMSGValidName') ?>">
					</div>
				</div>

				<div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNFrmDStart') ?></label>
						<div class="input-group">
							<input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMsgStart" name="oetMsgStart" autocomplete="off" value="<?php if ($tAvdStart != "") {
																																										echo $tAvdStart;
																																									} ?>" data-validate="<?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNValidDStart') ?>">
							<span class="input-group-btn">
								<button id="obtMsgStartDate" type="button" class="btn xCNBtnDateTime">
									<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="validate-input">
						<label class="xCNLabelFrm"><?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNFrmDFinish') ?></label>
						<div class="input-group">
							<input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMsgFinish" name="oetMsgFinish" autocomplete="off" value="<?php if ($tAdvStop != "") {
																																										echo $tAdvStop;
																																									} ?>" data-validate="<?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNValidDFinish') ?>">
							<span class="input-group-btn">
								<button id="obtMsgFinishDate" type="button" class="btn xCNBtnDateTime">
									<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
								</button>
							</span>
						</div>
					</div>
				</div>
				<div id="odvMessageStatus" class="form-group">
					<input type="hidden" id="ohdCheckStatus" name="ohdCheckStatus" value="<?php echo $tMshStatus ?>">
					<div class="validate-input">
						<label class="fancy-checkbox">
							<input type="checkbox" id="ocbMessageStatus" name="ocbMessageStatus" checked="true" value="1">
							<span> <?php echo language('service/message/message', 'tMSGStatus'); ?></span>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/message/message', 'tMSGDetail'); ?></label>
					</div>
					<div class="col-md-7">
						<label class="xCNLabelFrm" ><span style="color:red">*</span>กรณีรูปภาพต้องมีขนาดไม่เกิน 500 x 500 พิกเซล</label>
					</div>
				</div>
				<div class="xWSmgSortContainer" id="odvSmgSlipHeadContainer">
					<?php foreach ($aMshDetail as $nHIndex => $oHeadItem) : $nHIndex++; ?>
						<div class="form-group xWSmgItemSelect" id="<?php echo $nHIndex; ?>">
							<div class="input-group validate-input">
								<span class="input-group-btn">
									<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
								</span>
								<div class="row">
									<div class="col-md-5">
										<select class="form-control xWMsgSelectType" name="oetMsgType[<?php echo $nHIndex; ?>]" id="oetMsgType[<?php echo $nHIndex; ?>]" getattr="<?php echo $nHIndex; ?>" onchange="JSxChangeType(this)">
											<?php foreach ($aSelectType as $nkey => $tValue) {
												$tCheck = '';
												if ($nkey == $oHeadItem['Type'])
													$tCheck = 'selected';
											?>
												<option value="<?php echo $nkey ?>" <?php echo $tCheck ?>><?php echo $tValue ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-7 XWMsgType<?php echo $nHIndex; ?>">
										<?php if ($oHeadItem['Type'] == 4) { ?>
											<div class="input-group xWMsgImageButton<?php echo $nHIndex; ?>">
												<input type="hidden" readonly class="form-control" id="oetImgInputPDTDEMOOld<?php echo $nHIndex; ?>" name="oetImgInputPDTDEMOOld<?php echo $nHIndex; ?>" autocomplete="off" value="<?php echo $oHeadItem['Value']; ?>">
												<input required type='text' readonly name='oetImgInputPDTDEMO<?php echo $nHIndex; ?>' id='oetImgInputPDTDEMO<?php echo $nHIndex; ?>' value="<?php echo $oHeadItem['Value']; ?>">
												<input type='file' style="display:none; visibility:none" accept='image/png, image/jpeg' id='oetInputUplodePDTDEMO<?php echo $nHIndex; ?>' onchange="JSxImageUplodeResizeNEW(this,'','PDTDEMO<?php echo $nHIndex; ?>','1')">
												<span class="input-group-btn">
													<button id="obtMsgFinishDate" style='height: 34px;color: #FFFFFF !important;background-color: #aba9a9 !important;border-color: #aba9a9 !important;' type="button" class="btn xCNBtnDateTime">
														<label style='cursor: pointer;' for="oetInputUplodePDTDEMO<?php echo $nHIndex; ?>">
															<i class='fa fa-picture-o xCNImgButton'></i> เลือกรูป</label>
													</button>
												</span>
											</div>
										<?php } elseif ($oHeadItem['Type'] == 6 || $oHeadItem['Type'] == 7) { ?>
											<input type="text" class="form-control xWSmgDyForm" readonly maxlength="100" id="oetMsgValue<?php echo $nHIndex; ?>" name="oetMsgValue[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Value']; ?>">
										<?php } else {  ?>
											<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetMsgValue<?php echo $nHIndex; ?>" name="oetMsgValue[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Value']; ?>">
										<?php } ?>
										<input type="hidden" id="ohdMsgSeq<?php echo $nHIndex; ?>" name="ohdMsgSeq[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Seq']; ?>">
									</div>

								</div>
								<span class="input-group-btn">
									<img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>" onclick="JSxMessageEventDeleteDetail(this, event)">
								</span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div>
					<p class="text-primary text-right" onclick="JSxMessageAddRow()" style="margin-right: 25px;cursor: pointer;"><i class="fa fa-plus" style="font-size: 15px;"></i> <strong><?php echo language('pos/slipMessage/slipmessage', 'tSMGAddRow'); ?></strong></p>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- ===================================================== Modal Delete ===================================================== -->
<div id="odvModalDeleteMessageDetail" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
			</div>
			<div class="modal-body">
				<span id="ospTextConfirmDelMessageSet" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
			</div>
			<div class="modal-footer">
				<button id="osmConfirmDelMessage" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
				<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
			</div>
		</div>
	</div>
</div>
<!-- ====================================================== End Modal Delete ======================================================= -->

<?php include "script/jMessageAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<input type="text" class="xCNHide" id="ohdRetionCropper" name="ohdRetionCropper" value="0">
<script type="text/html" id="oscSlipHeadRowTemplate">
	<div class="form-group xWSmgItemSelect" id="{0}">
		<div class="input-group validate-input">
			<span class="input-group-btn">
				<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
			</span>
			<div class="row">
				<div class="col-md-5">
					<select class="form-control xWMsgSelectType" name="oetMsgType[{0}]" id="oetMsgType[{0}]" getattr={0} onchange="JSxChangeType(this)">
						<option value="1">ข้อความ</option>
						<option value="2">บาร์โค้ด</option>
						<option value="3">คิวอาร์โค้ด</option>
						<option value="4">รูปภาพ</option>
						<option value="5">รหัสอัตโนมัติ</option>
						<option value="6">วันที่</option>
						<option value="7">ขึ้นบรรทัดใหม่</option>
					</select>
				</div>
				<div class="col-md-7 XWMsgType{0}">
					<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetMsgValue{0}" name="oetMsgValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
				</div>
				<div>
				</div>
			</div>
			<span class="input-group-btn">
				<img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onclick="JSxMessageEventDeleteDetail(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
			</span>
</script>