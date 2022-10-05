<?php
if ($aResult['rtCode'] == 1) {
	$tCldCode   	= $aResult['raItems']['rtObjCode'];
	$tCldName   	= $aResult['raItems']['rtObjName'];
	$tCldRefcode   	= $aResult['raItems']['rtObjRefCode'];
	$tCldRemark 	= $aResult['raItems']['rtObjRmk'];
	$tCldStatus 	= $aResult['raItems']['rtObjStaUse'];
	$tMenuTabDisable = "";
    $tMenuTabToggle = "tab";
	$tRoute     	= "calendarEventEdit";

	$tCLDAgnCode       = $aResult['raItems']['rtAgnCode'];
	$tCLDAgnName       = $aResult['raItems']['rtAgnName'];
	$tCLDApvCode       = $aResult['raItems']['rtApvCode'];
	$tCLDApvName       = $aResult['raItems']['rtApvName'];
	$tCLDBchName       = $aResult['raItems']['rtBchName'];
	$tCLDBchCode       = $aResult['raItems']['rtBchCode'];
} else {
	$tCldCode   		= "";
	$tCldName   		= "";
	$tCldRefcode   		= "";
	$tCldRemark 		= "";
	$tCldStatus 		= "1";
	$tRoute     		= "calendarEventAdd";
	$tMenuTabToggle 	= "false";
	$tMenuTabDisable 	= " disabled xCNCloseTabNav";
	$tCLDBchName       = $this->session->userdata('tSesUsrBchNameDefault');
	$tCLDBchCode       = $this->session->userdata('tSesUsrBchCodeDefault');
	$tCLDApvCode       = "";
	$tCLDApvName       = "";
	$tCLDAgnCode       = $tSesAgnCode;
	$tCLDAgnName       = $tSesAgnName;
}
$tUserLevel   = $this->session->userdata('tSesUsrLevel');
?>
<div id="odvCldPanelBody" class="panel-body" style="padding-top:20px !important;">
<input type="hidden" id="ohdCldUsrLevel" name="ohdCldUsrLevel" value="<?php echo $tUserLevel; ?>">
	<div class="custom-tabs-line tabs-line-bottom left-aligned">
		<ul class="nav" role="tablist" style="cursor:pointer">
			<!-- ข้อมูลหลัก Tab -->
			<li id="oliCalendarTab" class="xCNCLDTab active" data-typetab="main" data-tabtitle="posinfo">
				<a role="tab" data-toggle="tab" data-target="#odvInforGeneralTap" aria-expanded="true">
					<?php echo language('service/calendar/calendar', 'tCLDMain') ?>
				</a>
			</li>
			<!-- ผู้ใช้ -->
			<li id="oliInforUserTap" class="xCNCLDTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="posinfouser">
				<a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" data-target="#odvInforUserTap" aria-expanded="true">
					<?php echo language('service/calendar/calendar', 'tCLDUser'); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane active" style="margin-top:10px;" id="odvInforGeneralTap" role="tabpanel" aria-expanded="true">
			<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCalendar">
				<button style="display:none" type="submit" id="obtSubmitCalendar" onclick="JSnAddEditCalendar('<?php echo $tRoute ?>')"></button>
				<input type="hidden" class="form-control" id="ohdCalendarRoute" name="ohdCalendarRoute" value="<?php echo $tRoute ?>">
				<div class="row">
					<div class="col-xs-12 col-md-5 col-lg-5">
						<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/calendar/calendar', 'tCLDCode') ?></label>
						<div id="odvCalendarAutoGenCode" class="form-group">
							<div class="validate-input">
								<label class="fancy-checkbox">
									<input type="checkbox" id="ocbCalendarAutoGenCode" name="ocbCalendarAutoGenCode" checked="true" value="1">
									<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
								</label>
							</div>
						</div>

						<div id="odvCalendarCodeForm" class="form-group">
							<input type="hidden" id="ohdCheckDuplicateCldCode" name="ohdCheckDuplicateCldCode" value="1">
							<div class="validate-input">
								<input
									type="text"
									class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
									maxlength="5"
									id="oetCldCode"
									name="oetCldCode"
									data-is-created="<?php echo $tCldCode; ?>"
									placeholder="<?php echo language('service/calendar/calendar', 'tCLDCode') ?>"
									value="<?php echo $tCldCode; ?>"
									data-validate-required="<?php echo language('service/calendar/calendar', 'tCLDValidCode') ?>"
									data-validate-dublicateCode="<?php echo language('service/calendar/calendar', 'tCLDValidCodeDup') ?>"
								>
							</div>
						</div>

					</div>
				</div>

				<?php
				if ($tRoute == "calendarEventAdd") {
					$tDisabled     = '';
					$tNameElmIDAgn = 'oimBrowseAgn';
				} else {
					$tDisabled      = 'disabled';
					$tNameElmIDAgn  = 'oimBrowseAgn';
				}
				$tDisabled2     = 'disabled';
				?>

				<!-- เพิ่ม Browser AD  -->

				<div class="row">
					<div class="col-xs-12 col-md-5 col-lg-5">
						<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
												endif; ?>">
							<label class="xCNLabelFrm"></span><?php echo language('service/calendar/calendar', 'tCLDAgency') ?></label>
							<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCldAgnCode" name="oetCldAgnCode" maxlength="5" value="<?php echo @$tCLDAgnCode; ?>">
								<input type="text" class="form-control xWPointerEventNone" id="oetCldAgnName" name="oetCldAgnName" maxlength="100" placeholder="<?php echo language('service/calendar/calendar', 'tCLDAgency'); ?>" value="<?php echo @$tCLDAgnName; ?>" readonly>
								<span class="input-group-btn">
									<button id="<?= @$tNameElmIDAgn; ?>" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabled ?>> 
										<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>

				<!-- เพิ่ม Browser Bch  -->
				<?php
					if($this->session->userdata('tSesUsrLevel') == 'HQ'){
						$tBrowseBCHDisabled	= '';
					}else if($this->session->userdata('tSesUsrLevel') == 'BCH'){
						if($this->session->userdata("nSesUsrBchCount") < 2){
							$tBrowseBCHDisabled	= 'disabled';
						}else{
							$tBrowseBCHDisabled	= '';
						}
					}
				?>
				<div class="row">
					<div class="col-xs-12 col-md-5 col-lg-5">
						<div class="form-group ">
							<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubBranch') ?></label>
							<div class="input-group">
								<input class="form-control xCNHide" type="text" id="oetCldBchCode" name="oetCldBchCode" maxlength="5" value="<?php echo @$tCLDBchCode; ?>">
								<input type="text" class="form-control xWPointerEventNone" id="oetCldBchName" name="oetCldBchName" maxlength="100" placeholder="<?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubBranch'); ?>" value="<?php echo @$tCLDBchName; ?>" readonly>
								<span class="input-group-btn">
									<button id="oimBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tBrowseBCHDisabled ?>>
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
								<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/calendar/calendar', 'tCLDName') ?></label>
								<input type="text" class="form-control" maxlength="100" id="oetCldName" name="oetCldName" placeholder="<?php echo language('service/calendar/calendar', 'tCLDName') ?>" value="<?php echo $tCldName ?>" data-validate-required="<?php echo language('service/calendar/calendar', 'tCLDValidName') ?>" maxlength="100">
							</div>
						</div>

						<div class="form-group  <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
												endif; ?>">
							<label class="xCNLabelFrm"></span><?php echo language('service/calendar/calendar', 'tCLDApv') ?></label>
							<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCldAvgCode" name="oetCldAvgCode" maxlength="5" value="<?php echo @$tCLDApvCode; ?>">
								<input type="text" class="form-control xWPointerEventNone" id="oetCldAvgName" name="oetCldAvgName" maxlength="100" placeholder="<?php echo language('service/calendar/calendar', 'tCLDApv'); ?>" value="<?php echo @$tCLDApvName; ?>" readonly>
								<span class="input-group-btn">
									<button id="oimBrowseAvg" type="button" class="btn xCNBtnBrowseAddOn">
										<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
									</button>
								</span>
							</div>
						</div>

						<div class="form-group">
							<div class="validate-input">
								<label class="xCNLabelFrm"><?php echo language('service/calendar/calendar', 'tCLDCodeRef') ?></label>
								<input type="text" class="form-control" maxlength="30" id="oetCldRef" name="oetCldRef" placeholder="<?php echo language('service/calendar/calendar', 'tCLDCodeRef') ?>" value="<?php echo $tCldRefcode ?>">
							</div>
						</div>


						<div class="form-group">

							<label class="xCNLabelFrm"><?= language('service/calendar/calendar', 'tCLDRemark') ?></label>
							<textarea class="form-control" rows="4" maxlength="255" id="otaCldRemark" name="otaCldRemark" maxlength="255" ><?php echo $tCldRemark ?></textarea>

						</div>
						<label class="xCNLabelFrm"><?= language('service/calendar/calendar', 'tCLDStatus') ?></label>
						<div id="odvCalendarStatus" class="form-group">
							<input type="hidden" id="ohdCheckStatus" name="ohdCheckStatus" value="<?php echo $tCldStatus ?>">
							<div class="validate-input">
								<label class="fancy-checkbox">
									<input type="checkbox" id="ocbCalendarStatus" name="ocbCalendarStatus" checked="true" value="1">
									<span> <?php echo language('service/calendar/calendar', 'tCLDStatus'); ?></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="tab-pane" style="margin-top:10px;" id="odvInforUserTap" role="tabpanel" aria-expanded="true">
		<?php
                    if ($tRoute == "calendarEventEdit") {
                    ?>
                        <div id="odvUserCalendarControlPage">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <ol id="oliMenuNav" class="breadcrumb">
                                        <li id="oliCldUserTitle" class="xCNLinkClick" onclick="JSvCalendarUserDataTable(1,'<?= $tCldCode ?>');" style="cursor:pointer"><?php echo language('service/calendar/calendar', 'tCLDUser') ?></li>
                                        <li id="oliCldUserTitletmp" class="active"><a><?php echo language('service/calendar/calendar', 'tCLDTitleEdit') ?></a></li>
                                        <li id="oliCldUserTitleEdit" class="active"><a><?php echo language('service/calendar/calendar', 'tCLDTitleEdit') ?></a></li>
                                        <li id="oliCldUserTitleAdd" class="active"><a><?php echo language('service/calendar/calendar', 'tCLDTitleAdd') ?></a></li>
                                    </ol>
                                </div>
                                <div class="col-xs-12 col-md-8 text-right">
                                    <div id="odvBtnCldUserInfo">
                                        <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageUserCalendarAdd()">+</button>
                                    </div>
                                    <div id="odvBtnCldUserAddEdit">

									<button type="button" onclick="JSvCalendarUserDataTable(1,'<?= $tCldCode ?>');" class="btn" style="background-color: #D4D4D4; color: #000000;">
                                            <?=language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel') ?>
                                        </button>
                                        <button type="submit" class="btn xCNBTNSubSave" onclick="JSxSetStatusClickCldUserSubmit();$('#obtSubmitUserCalendar').click()">
                                            <?=language('common/main/main', 'tSave') ?>
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group" id="odvBtnCldUserSearch">
                                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tSearchNew') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchUserCalendar" name="oetSearchUserCalendar" autocomplete="off" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn xCNBtnSearch" type="button" id="obtSearchUserCalendar" name="obtSearchUserCalendar">
                                                    <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-8 text-right">
                                    <div class="text-right" id="odvMngMargin" style="width:100%; margin-top:25px;">
                                        <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                                            <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                                <?php echo language('common/main/main', 'tCMNOption') ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li id="oliBtnDeleteAll" class="disabled">
                                                    <a data-toggle="modal" data-target="#odvModalDelUserCalendar"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="odvUserCalendarContentPage">

                        </div>
                    <?php
                    }
                    ?>
		</div>
	</div>

</div>
<?php include "script/jCalendarAdd.php"; ?>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
