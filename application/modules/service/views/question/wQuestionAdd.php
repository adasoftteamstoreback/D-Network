<?php
if ($aResult['rtCode'] == 1) {
	$tQahCode   	= $aResult['raItems']['rtQahDocNo'];
	$tQahName   	= $aResult['raItems']['rtQahName'];
	$tMenuTabDisable = "";
	$tMenuTabToggle = "tab";
	$tRoute     	= "questionEventEdit";

	$tQahStart         = $aResult['raItems']['rtQahDateStart'];
	$tQahStop          = $aResult['raItems']['rtQahDateStop'];
	$tQgpCode          = $aResult['raItems']['rtQgpCode'];
	$tQgpName          = $aResult['raItems']['rtQgpName'];
	$tQsgCode          = $aResult['raItems']['rtQsgCode'];
	$tQsgName          = $aResult['raItems']['rtQsgName'];
	$tQahStaActive     = $aResult['raItems']['rtQahStaActive'];
	
} else {
	$tQahCode   		= "";
	$tQahName   		= "";
	$tRoute     		= "questionEventAdd";
	$tMenuTabToggle 	= "false";
	$tMenuTabDisable 	= " disabled xCNCloseTabNav";

	$tQahStart      	= $dGetDataNow;
	$tQahStop       	= $dGetDataFuture;
	$tQAHAgnCode       = $tSesAgnCode;
	$tQgpCode          = "";
	$tQgpName          = "";
	$tQsgCode          = "";
	$tQsgName          = "";

	$tQahStaActive     = '1';
}

?>
<style>
	.xCNQuestionLabel {
		padding: 5px 10px;
		color: #232C3D !important;
		font-weight: 900;
	}

	.xCNQuestionLabelWidth {
		width: 260px;
	}

	.xCNQuestionHeadLabel {
		background-color: #f5f5f5;
		padding: 5px 10px;
		color: #232C3D !important;
		font-weight: 900;
	}

	.xWEJBoxFilter {
		border: 1px solid #ccc !important;
		position: relative !important;
		padding: 15px !important;
		margin-top: 10px !important;
		padding-bottom: 0px !important;
		margin-bottom: 10px !important;
	}

	.xWEJBoxFilter .xWEJLabelFilter {
		position: absolute !important;
		top: -15px;
		left: 15px !important;
		background: #fff !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
	}
	
	.nav{
		cursor: pointer;
	}
	.xWtextbold{
		font-weight: bold;
	}
</style>
<div id="odvQahPanelBody" class="panel-body" style="padding-top:20px !important;">
	<input type="hidden" id="ohdCheckAgn" name="ohdCheckAgn" value="<?php echo @$tQAHAgnCode; ?>">
	<input type="hidden" id="ohdCheckType" name="ohdCheckType" value="<?php echo @$tQahType; ?>">
	<div class="custom-tabs-line tabs-line-bottom left-aligned">
		<ul class="nav" role="tablist">
			<!-- ข้อมูลหลัก Tab -->
			<li id="oliQuestionTab" class="xCNQAHTab active" data-typetab="main" data-tabtitle="question">
				<a role="tab" data-toggle="tab" id='oahQuestionTab' data-target="#odvInforGeneralTap" aria-expanded="true">
					<?php echo language('service/question/question', 'tQAHDefault') ?>
				</a>
			</li>
			<!-- รายละเอียด -->
			<li id="oliDetailTap" class="xCNQAHTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="questiondetail">
				<a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" id='oahDetailTab' data-target="#odvDetailTap" aria-expanded="true">
					<?php echo language('service/question/question', 'tQAHTabQuestion'); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane active" style="margin-top:10px;" id="odvInforGeneralTap" role="tabpanel" aria-expanded="true">
			<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddQuestion">
				<button style="display:none" type="submit" id="obtSubmitQuestion" onclick="JSnAddEditQuestion('<?php echo $tRoute ?>')"></button>
				<div class="row">
					<div class="col-xs-12 col-md-5 col-lg-5">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/question/question', 'tQAHCode') ?></label>
						<div id="odvQuestionAutoGenCode" class="form-group">
							<div class="validate-input">
								<label class="fancy-checkbox">
									<input type="checkbox" id="ocbQuestionAutoGenCode" name="ocbQuestionAutoGenCode" checked="true" value="1">
									<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
								</label>
							</div>
						</div>

						<div id="odvQuestionCodeForm" class="form-group">
							<input type="hidden" id="ohdCheckDuplicateQahCode" name="ohdCheckDuplicateQahCode" value="1">
							<div class="validate-input">
								<input type="text" class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" maxlength="20" id="oetQahCode" name="oetQahCode" data-is-created="<?php echo $tQahCode; ?>" placeholder="<?php echo language('service/question/question', 'tQAHCode') ?>" ; value="<?php echo $tQahCode; ?>" data-validate-required="<?php echo language('service/question/question', 'tQAHValidCode') ?>" data-validate-dublicateCode="<?php echo language('service/question/question', 'tQAHValidCodeDup') ?>">
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-5 col-lg-5">
						<div class="form-group">
							<div class="validate-input">
								<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/question/question', 'tQAHName') ?></label>
								<input type="text" class="form-control" maxlength="200" id="oetQahName" name="oetQahName" placeholder="<?php echo language('service/question/question', 'tQAHName') ?>" value="<?php echo $tQahName ?>" data-validate-required="<?php echo language('service/question/question', 'tQAHValidName') ?>" required>
							</div>
						</div>

						<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
												endif; ?>">
							<label class="xCNLabelFrm"></span><?php echo language('service/question/question', 'tQAHQasGroup') ?></label>
							<div class="input-group"><input class="form-control xCNHide" type="text" id="oetQasGroupCode" name="oetQasGroupCode" maxlength="5" value="<?php echo @$tQgpCode; ?>">
								<input type="text" class="form-control xWPointerEventNone" id="oetQasGroupName" name="oetQasGroupName" maxlength="100" placeholder="<?php echo language('service/question/question', 'tQAHQasGroup'); ?>" value="<?php echo @$tQgpName; ?>" readonly>
								<span class="input-group-btn">
									<button id="oimBrowseQasGroup" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
										<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>

						<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
												endif; ?>">
							<label class="xCNLabelFrm"></span><?php echo language('service/question/question', 'tQAHQasSubGroup') ?></label>
							<div class="input-group"><input class="form-control xCNHide" type="text" id="oetQasSubCode" name="oetQasSubCode" maxlength="5" value="<?php echo @$tQsgCode; ?>">
								<input type="text" class="form-control xWPointerEventNone" id="oetQasSubName" name="oetQasSubName" maxlength="100" placeholder="<?php echo language('service/question/question', 'tQAHQasSubGroup'); ?>" value="<?php echo @$tQsgName; ?>" readonly>
								<span class="input-group-btn">
									<button id="oimBrowseQasSub" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
										<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>

						<!-- <div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('service/question/question', 'tQAHSelectAnwType'); ?></label>
							<select name="ocmQahType" id="ocmQahType" class="form-control">
								<option value="1" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType1'); ?></option>
								<option value="2" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType2'); ?></option>
								<option value="3" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType3'); ?></option>
								<option value="4" class='xWCheckSelect'><?php echo  language('service/question/question', 'tQAHSelectType4'); ?></option>
							</select>
						</div> -->

						<div class="form-group">
							<div class="validate-input">
								<label class="xCNLabelFrm"><?php echo language('service/question/question', 'tQAHStartDate') ?></label>
								<div class="input-group">
									<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetQahStart" name="oetQahStart" autocomplete="off" value="<?php if ($tQahStart != "") {
																																																		echo $tQahStart;
																																																	} ?>" data-validate="<?php echo language('service/question/question', 'tQAHStartDate') ?>">
									<span class="input-group-btn">
										<button id="obtQahStartDate" type="button" class="btn xCNBtnDateTime">
											<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="validate-input">
								<label class="xCNLabelFrm"><?php echo language('service/question/question', 'tQAHFinishDate') ?></label>
								<div class="input-group">
									<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetQahFinish" name="oetQahFinish" autocomplete="off" value="<?php if ($tQahStop != "") {
																																																		echo $tQahStop;
																																																	} ?>" data-validate="<?php echo language('service/question/question', 'tQAHFinishDate') ?>">
									<span class="input-group-btn">
										<button id="obtQahFinishDate" type="button" class="btn xCNBtnDateTime">
											<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>

						<!-- ใช้งาน -->
						<div class="form-group">
							<label class="fancy-checkbox">
								<input type="checkbox" id="ocbQahStaActive" name="ocbQahStaActive"  <?php if($tQahStaActive=='1'){ echo 'checked="true"'; } ?> value="1">
								<span> <?php echo language('service/question/question', 'tQAHStatus1'); ?></span>
							</label> 
						</div>
						<!-- end ใช้งาน -->


					</div>
				</div>
			</form>
		</div>

		<div class="tab-pane" style="margin-top:10px;" id="odvDetailTap" role="tabpanel" aria-expanded="true">
			<?php
			if ($tRoute == "questionEventEdit") {
			?>
				<div id="odvQuestionDetailControlPage">
					<div class="row">
						<div class="xWEJBoxFilter">
							<label class="xCNLabelFrm xWEJLabelFilter" wfd-id="2186"><?php echo language('service/question/question', 'tQAHSelectType4') ?></label>
							<div class="form-group">
									<div class='row'>
										<div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
											<label class="xWtextbold"><?php echo language('service/question/question', 'tQAHCode') ?></label>
										</div>
										<div class="col-xs-12 col-md-10 text-left">
											<label><?php echo $tQahCode ?></label>
										</div>
										<div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;" >
											<label class="xWtextbold"><?php echo language('service/question/question', 'tQAHName') ?></label>
										</div>
										<div class="col-xs-12 col-md-10 text-left">
											<label><?php echo $tQahName ?></label>
										</div>
										<div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
											<label class="xWtextbold"><?php echo language('service/question/question', 'tQAHQasGroup') ?></label>
										</div>
										<div class="col-xs-12 col-md-10 text-left">
											<label><?php echo $tQgpName ?></label>
										</div>
										<div class="col-xs-12 col-md-2 text-left" style="border-right: 1px solid #ccc !important;">
											<label class="xWtextbold"><?php echo language('service/question/question', 'tQAHQasSubGroup') ?></label>
										</div>
										<div class="col-xs-12 col-md-10 text-left">
											<label><?php echo $tQsgName ?></label>
										</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-4">
							<ol id="oliMenuNav" class="breadcrumb">
								<li id="oliQahDetailTitle" class="xCNLinkClick" onclick="JSvQuestionDetailDataTable(1,'<?= $tQahCode ?>');" style="cursor:pointer"><?php echo language('service/question/question', 'tQAHTabQuestion') ?></li>
								<li id="oliQahDetailTitletmp" class="active"><a><?php echo language('service/question/question', 'tQAHTitleEdit') ?></a></li>
								<li id="oliQahDetailTitleEdit" class="active"><a><?php echo language('service/question/question', 'tQAHTitleEdit') ?></a></li>
								<li id="oliQahDetailTitleAdd" class="active"><a><?php echo language('service/question/question', 'tQAHTitleAdd') ?></a></li>
							</ol>
						</div>
						<div class="col-xs-12 col-md-8 text-right">
							<div id="odvBtnQahDetailInfo">
								<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageQuestionDetailAdd()">+</button>
							</div>
							<div id="odvBtnQahDetailAddEdit">

								<button type="button" onclick="JSvQuestionDetailDataTable(1,'<?= $tQahCode ?>');" class="btn" style="background-color: #D4D4D4; color: #000000;">
									<?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
								</button>
								<button type="submit" class="btn xCNBTNSubSave" onclick="JSxSetStatusClickDetailSubmit();$('#obtSubmitQuestionDetail').click()">
									<?php echo  language('common/main/main', 'tSave') ?>
								</button>

							</div>
							<div class="text-right" id="odvMngMargin" style="width:100%; margin-bottom:10px;">
								<div id="odvMngTableList" class="btn-group xCNDropDrownGroup" style="margin-right:10px;">
									<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
										<?php echo language('common/main/main', 'tCMNOption') ?>
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li id="oliBtnDeleteAll" class="disabled">
											<a data-toggle="modal" data-target="#odvModalDelQuestionDetail"><?php echo language('common/main/main', 'tDelAll') ?></a>
										</li>
									</ul>
								</div>
						</div>
						</div>
					</div>
					<div class="row">
						
					</div>
				</div>
				<div id="odvQuestionDetailContentPage">

				</div>
			<?php
			}
			?>
		</div>
	</div>

</div>
<?php include "script/jQuestionAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>