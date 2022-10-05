<?php
//    print_r($aCldUserList);

if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
	$tRoute             = "questiondetailEventEdit";
	$tQahCode           = $aQahData['raItems']['rtQahDocNo'];
	$tSeqCode           = $aQahData['raItems']['rtQadSeqNo'];
	$tQadName           = $aQahData['raItems']['rtQadName'];
	$tQadType           = $aQahData['raItems']['rtQadType'];
	$tQadStaUse           = $aQahData['raItems']['rtQadStaUse'];
	$aMshDetail 	 	= $aQahData['raDetailQuestion'];
} else {
	$tRoute             = "questiondetailEventAdd";
	$tQahCode           = $tQahCode;
	$tSeqCode           = "";
	$tObjCode           = "";
	$tUsrSeq            = "";
	$tQadName           = "";
	$tQadStaUse         = "1";
	$tUsrRemark         = "";
	$tUsrName           = "";
	$tQadType          = "";
	$aMshDetail  		= [];
}
$tQahType  		= $tQahType;
?>

<style>
	.xWSmgMoveIcon {
		cursor: move !important;
		border-radius: 0px;
		box-shadow: none;
		padding: 0px 10px;
	}
</style>

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddUserCalendar">
	<button style="display:none" type="submit" id="obtSubmitQuestionDetail" onclick="JSoAddEditQuestionDetail('<?= $tRoute ?>')"></button>
	<input type="hidden" id="ohdCldUserRoute" name="ohdCldUserRoute" value="<?php echo $tRoute; ?>">
	<input type="hidden" id="ohdObjCodeUserCalendar" name="ohdObjCodeUserCalendar" value="<?php echo $tQahCode; ?>">
	<input type="hidden" id="ohdQadType" name="ohdQadType" value="<?php echo $tQadType; ?>">

	<div class='row'>
		<div class="col-xs-12 col-md-5 col-lg-5">
			<!-- เปลี่ยน Col Class -->
			<div class="form-group">
				<input type="hidden" value="0" id="ohdCheckQuestionDetailClearValidate" name="ohdCheckQuestionDetailClearValidate">
				<?php
				if ($tRoute == "questiondetailEventAdd") {
				?>

				<?php
				} else {
				?>
					<div class="form-group" id="odvCldUserCodeForm">
						<div class="validate-input">
							<label class="fancy-checkbox">
								<input type="hidden" name="ohdTmpCode" id="ohdTmpCode" value="<?php echo $tQahCode; ?>">
							</label>
						</div>
					<?php
				}
					?>


					</div>


					<label class="xCNLabelFrm" id="odlUserCalendarName"><?php echo language('service/question/question', 'tQAHName') ?></label>
					<div class="">
						<input type="hidden" id="ohdCheckQahCode" name="ohdCheckQahCode" value="<?php echo @$tQahCode; ?>">
						<input type="hidden" id="ohdCheckSeqCode" name="ohdCheckSeqCode" value="<?php echo @$tSeqCode; ?>">
						<input type="text" class="form-control" maxlength="200" id="oetQadName" name="oetQadName" placeholder="<?php echo language('service/question/question', 'tQAHName') ?>" value="<?php echo $tQadName ?>" data-validate-required="<?php echo language('service/question/question', 'tCLDValidName') ?>" required>
					</div>

					<div class="form-group">
						<label class="xCNLabelFrm"><?php echo language('service/question/question', 'tQAHSelectAnwType'); ?></label>
						<select name="ocmQadType" id="ocmQadType" class="form-control">
							<option value="1" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType1'); ?></option>
							<option value="2" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType2'); ?></option>
							<option value="3" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType3'); ?></option>
							<option value="4" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType4'); ?></option>
						</select>
					</div>

					<div id="odvCalendarStatus" class="form-group" style='margin-top : 10px;'>
						<input type="hidden" id="ohdCheckStatus" name="ohdCheckStatus" value="<?php echo $tQadStaUse ?>">
						<div class="validate-input">
							<label class="fancy-checkbox">
								<input type="checkbox" id="ocbQuestionStatus" name="ocbQuestionStatus" checked="true" value="1">
								<span> <?php echo language('service/calendar/calendar', 'tCLDStatus'); ?></span>
							</label>
						</div>
					</div>


					<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/question/question', 'tQAHAnwserGroup'); ?></label>
					<div class="xWSmgSortContainer" id="odvSmgSlipHeadContainer">
						<?php foreach ($aMshDetail as $nHIndex => $oHeadItem) : $nHIndex++; ?>
							<?php if ($tQahType == '3' || $tQahType == '4') { ?>
								<div class="form-group xWSmgItemSelect" id="<?php echo $nHIndex; ?>">
									<div class="form-group validate-input">
										<div class=" XWMsgType<?php echo $nHIndex; ?>">
											<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue<?php echo $nHIndex; ?>" name="oetQadValue[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Value']; ?>">
											<input type="hidden" id="ohdMsgSeq<?php echo $nHIndex; ?>" name="ohdMsgSeq[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Seq']; ?>">
										</div>
									</div>
								</div>
							<?php } elseif ($tQahType == '2' || $tQahType == '1') { ?>
								<div class="form-group xWSmgItemSelect" id="<?php echo $nHIndex; ?>">
									<div class="input-group validate-input">
										<span class="input-group-btn">
											<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
										</span>
										<div class="row">
											<div class="col-md-12 XWMsgType<?php echo $nHIndex; ?>">
												<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue<?php echo $nHIndex; ?>" name="oetQadValue[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Value']; ?>">
												<input type="hidden" id="ohdMsgSeq<?php echo $nHIndex; ?>" name="ohdMsgSeq[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['Seq']; ?>">
											</div>
										</div>
										<span class="input-group-btn">
										<img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onclick="JSxMessageDeleteRowHead(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
										</span>
									</div>
								</div>
							<?php } ?>
						<?php endforeach; ?>
					</div>

					<div>
						<p id="odvAddMore" class="text-primary text-right" onclick="JSxMessageAddRow()" style="margin-right: 25px;cursor: pointer;"><i class="fa fa-plus" style="font-size: 15px;"></i> <strong><?php echo language('pos/slipMessage/slipmessage', 'tSMGAddRow'); ?></strong></p>
					</div>

			</div>
		</div>
	</div>
</form>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<?php include "application/modules/service/views/question/script/jQuestionDetailAdd.php"; ?>
<script>
</script>
<script type="text/html" id="oscSlipHeadRowTemplate">
	<div class="form-group xWSmgItemSelect" id="{0}">
		<div class="input-group validate-input" id='odvClassType'>
			<span class="input-group-btn xWMoveIcon">
				<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
			</span>
			<div class="row">
				<div class="col-md-12 XWMsgType{0}">
					<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue{0}" name="oetQadValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
				</div>
				<div>
				</div>
			</div>
			<span class="input-group-btn">
				<img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onclick="JSxMessageDeleteRowHead(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
			</span>
		</div>
	</div>
</script>
<!-- <?php if ($tQahType == '3' || $tQahType == '4') { ?>
	<script type="text/html" id="oscSlipHeadRowTemplate">
		<div class="form-group xWSmgItemSelect" id="{0}">
			<div class="XWMsgType{0}">
				<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue{0}" name="oetQadValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
			</div>
		</div>
	</script>
<?php } elseif ($tQahType == '5') { ?>
	<script type="text/html" id="oscSlipHeadRowTemplate">
		<div class="form-group xWSmgItemSelect" id="{0}">
			<div class="input-group validate-input">
				<span class="input-group-btn">
					<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
				</span>
				<div class="row">
					<div class="col-md-12 XWMsgType{0}">
						<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue{0}" name="oetQadValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
					</div>
				<div>
			</div>
		</div>
	</script>
<?php } elseif ($tQahType == '1' || $tQahType == '2') { ?>
	<script type="text/html" id="oscSlipHeadRowTemplate">
		<div class="form-group xWSmgItemSelect" id="{0}">
			<div class="input-group validate-input">
				<span class="input-group-btn">
					<div class="btn xWSmgMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
				</span>

				<div class="row">
					<div class="col-md-12 XWMsgType{0}">
						<input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetQadValue{0}" name="oetQadValue[{0}]" value="" data-validate="<?php echo language('pos/slipMessage/slipmessage', 'tSMGValidHead'); ?>" required>
					</div>
					<div>
					</div>
				</div>
				<span class="input-group-btn">
					<img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onclick="JSxMessageDeleteRowHead(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
				</span>
	</script>
<?php } ?> -->