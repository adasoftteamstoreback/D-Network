<?php
if ($aResult['rtCode'] == 1) {
	$tCarCode   			= $aResult['raItems']['rtCarCode'];
	$tCarNoreq   			= $aResult['raItems']['rtCarRegNo'];
	$tCarEnginereq  		= $aResult['raItems']['rtCarEngineNo'];
	$tCarPowerreq   		= $aResult['raItems']['rtCarVIDRef'];
	$tMenuTabDisable 		= "";
	$tMenuTabToggle 		= "tab";
	$tRoute     			= "carEventEdit";
	$tCarStart      		= $aResult['raItems']['rtCarDOB'];
	$tCarStop       		= $aResult['raItems']['rtCarOwnChg'];
	$tImgObj            	= $aResult['raItems']['rtImgObj'];
	$tCarRedLabelStaActive  = $aResult['raItems']['rtCarStaRedLabel'];
	$tCstID       			= $aResult['raItems']['rtCstCode'];
	$tCstName       		= $aResult['raItems']['rtCstName'];
	$tCarTypeID       		= $aResult['raItems']['rtCarType'];
	$tCarTypeName       	= $aResult['raItems']['rtCarTypeName'];
	$tCarBrandID       		= $aResult['raItems']['rtCarBrand'];
	$tCarBrandName       	= $aResult['raItems']['rtCarBrandName'];
	$tCarModelID       		= $aResult['raItems']['rtCarModel'];
	$tCarModelName       	= $aResult['raItems']['rtCarModelName'];
	$tCarColorID       		= $aResult['raItems']['rtCarColor'];
	$tCarColorName       	= $aResult['raItems']['rtCarColorName'];
	$tCarGearID       		= $aResult['raItems']['rtCarGear'];
	$tCarGearName       	= $aResult['raItems']['rtCarGearName'];
	$tCarPowerTypeID       	= $aResult['raItems']['rtCarPowerType'];
	$tCarPowerTypeName      = $aResult['raItems']['rtCarPowerTypeName'];
	$tCarEngineSizeID       = $aResult['raItems']['rtCarEngineSize'];
	$tCarEngineSizeName     = $aResult['raItems']['rtCarEngineSizeName'];
	$tCarCategoryID       	= $aResult['raItems']['rtCarCategory'];
	$tCarCategoryName      	= $aResult['raItems']['rtCarCategoryName'];
	$tCarRegProvince      	= $aResult['raItems']['rtCarRegProvince'];
	$tCarPvnName      		= $aResult['raItems']['rtPvnName'];
	$tCarBCHCode      		= $aResult['raItems']['rtRefBCHCode'];
	$tCarBCHName      		= $aResult['raItems']['rtRefBCHName'];
} else {
	$tCarCode   			= "";
	$tCarNoreq   			= "";
	$tCarEnginereq  		= "";
	$tCarPowerreq  			= "";
	$tRoute     			= "carEventAdd";
	$tMenuTabToggle 		= "false";
	$tMenuTabDisable 		= " disabled xCNCloseTabNav";
	$tCarStart      		= "";
	$tCarStop       		= "";
	$tCarRegProvince    	= "";
	$tCarPvnName      		= "";
	$tCarRedLabelStaActive 	= "";
	$tCstID       			= "";
	$tCstName       		= "";
	$tCarTypeID       		= "";
	$tCarTypeName       	= "";
	$tCarBrandID       		= "";
	$tCarBrandName       	= "";
	$tCarModelID       		= "";
	$tCarModelName       	= "";
	$tCarColorID       		= "";
	$tCarColorName       	= "";
	$tCarGearID       		= "";
	$tCarGearName       	= "";
	$tCarCategoryID       	= "";
	$tCarCategoryName       = "";
	$tCarPowerTypeID       	= "";
	$tCarPowerTypeName      = "";
	$tCarEngineSizID       	= "";
	$tCarEngineSizName      = "";
	$tImgObj            	= "";
	$tCarBCHCode      		= "";
	$tCarBCHName      		= "";
}
?>
<div id="odvCarPanelBody" class="panel-body" style="padding-top:20px !important;">
	<div class="custom-tabs-line tabs-line-bottom left-aligned">
		<ul class="nav" role="tablist" style="cursor:pointer">
			<!-- ข้อมูลหลัก Tab -->
			<li id="oliCarTab" class="xCNCARTab active" data-typetab="main" data-tabtitle="posinfo">
				<a role="tab" data-toggle="tab" data-target="#odvInforGeneralTap" aria-expanded="true">
					<?php echo language('service/car/car', 'tCARTitleTap') ?>
				</a>
			</li>
			<!-- ประวัติเข้ารับบริการ -->
			<li id="oliInforUserTap" class="xCNCARTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="posinfouser">
				<a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" data-target="#odvInforAllHistoryTap" aria-expanded="true">
					<?php echo language('service/car/car', 'tCARHistoryAll'); ?>
				</a>
			</li>
			<!-- ประวัติการติดตาม -->
			<li id="oliInforUserTap2" class="xCNCARTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="posinfouser">
				<a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" data-target="#odvInforHistoryTap" aria-expanded="true">
					<?php echo language('service/car/car', 'tCARHistory'); ?>
				</a>
			</li>
			<!-- ประวัติตามบิลขาย -->
			<li id="oliInforUserTap3" class="xCNCARTab<?php echo @$tMenuTabDisable; ?>" data-typetab="sub" data-tabtitle="posinfouser">
				<a role="tab" data-toggle="<?php echo @$tMenuTabToggle; ?>" data-target="#odvInforHistorySaleTap" aria-expanded="true">
					ประวัติตามบิลขาย
				</a>
			</li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane active" style="margin-top:10px;" id="odvInforGeneralTap" role="tabpanel" aria-expanded="true">
			<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCalendar">
				<button style="display:none" type="submit" id="obtSubmitCar" onclick="JSnAddEditCar('<?php echo $tRoute ?>')"></button>
				<div class="row">

					<div class="col-sm-12">
						<div class="col-xs-4 col-sm-4">
							<?php
							echo FCNtHGetContentUploadImage(@$tImgObj, 'Car');
							?>
						</div>
						<div class="col-xs-8 col-sm-8">
							<div class="col-xs-10 col-sm-10">
								<label class="xCNLabelFrm"><span style="color:red">*</span><?= language('service/car/car', 'tCARCodeDetail') ?></label>
								<div id="odvCalendarAutoGenCode" class="form-group">
									<div class="validate-input">
										<label class="fancy-checkbox">
											<input type="checkbox" id="ocbCalendarAutoGenCode" name="ocbCalendarAutoGenCode" checked="true" value="1">
											<span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
										</label>
									</div>
								</div>
								<div id="odvCalendarCodeForm" class="form-group">
									<input type="hidden" id="ohdCheckDuplicateCarCode" name="ohdCheckDuplicateCarCode" value="1">
									<div class="validate-input">
										<input type="text" class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" maxlength="20" id="oetCarCode" name="oetCarCode" data-is-created="<?php echo $tCarCode; ?>" placeholder="<?php echo language('service/car/car', 'tCARCodeDetail') ?>" ; value="<?php echo $tCarCode; ?>" data-validate-required="<?php echo language('service/car/car', 'tCARValidCode') ?>" data-validate-dublicateCode="<?php echo language('service/car/car', 'tCARValidCodeDup') ?>">
									</div>
								</div>

								<div class="form-group">
									<div class="validate-input">
										<label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('service/car/car', 'tCARRegNumber') ?> <i style="color:red;">(เลขทะเบียนรถ ไม่เว้นวรรค ไม่ขีดกลาง,ไม่ต้องใส่จังหวัด เช่น 9กม9999)</i></label>
										<input type="text" class="form-control" maxlength="30" id="oetCarNoreq" name="oetCarNoreq" placeholder="<?php echo language('service/car/car', 'tCARRegNumber') ?>" value="<?php echo $tCarNoreq ?>" data-validate-required="<?php echo language('service/car/car', 'tCARValidRegNumber') ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARProvince') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarProvinceCode" name="oetCarProvinceCode" maxlength="20" value="<?php echo @$tCarRegProvince; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarProvinceName" name="oetCarProvinceName" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCARProvince'); ?>" value="<?php echo @$tCarPvnName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseProvince" type="button" class="btn xCNBtnBrowseAddOn">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCAREngineno') ?></label>
									<input type="text" class="form-control" maxlength="30" id="oetCarEnginereq" name="oetCarEnginereq" placeholder="<?php echo language('service/car/car', 'tCAREngineno') ?>" value="<?php echo $tCarEnginereq ?>">
								</div>

								<div class="form-group">
									<label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARPowerno') ?></label>
									<input type="text" class="form-control" maxlength="30" id="oetCarPowerreq" name="oetCarPowerreq" placeholder="<?php echo language('service/car/car', 'tCARPowerno') ?>" value="<?php echo $tCarPowerreq ?>">
								</div>

								<!-- เพิ่ม Browser เจ้าของรถยนต์  -->
								<div class="form-group ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCAROwner') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarUserCode" name="oetCarUserCode" maxlength="20" value="<?php echo @$tCstID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarUserName" name="oetCarUserName" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROwner'); ?>" value="<?php echo @$tCstName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseUser" type="button" class="btn xCNBtnBrowseAddOn">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- รหัสอ้างอิงสาขา  -->
								<?php
								if (@$tCarBCHCode == '' || @$tCarBCHCode == null) {
									$nValueKeepInBCH 		= '0';
									$tClassDisabledRefBCH 	= 'xCNHide';
								} else {
									$nValueKeepInBCH 		= '1';
									$tClassDisabledRefBCH 	= '';
								}
								?>
								<div class="form-group <?= $tClassDisabledRefBCH ?> xCNCarRefBCH">
									<input type="hidden" id="ohdSeqNoInBCH" name="ohdSeqNoInBCH" value="<?= $nValueKeepInBCH ?>">
									<label class="xCNLabelFrm"><span style="color:red">*</span></span>รหัสอ้างอิงสาขา</label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarRefBCHCode" name="oetCarRefBCHCode" maxlength="20" value="<?= @$tCarBCHCode; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarRefBCHName" name="oetCarRefBCHName" maxlength="100" placeholder="รหัสอ้างอิงสาขา" value="<?= @$tCarBCHName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseRefBCH" type="button" class="btn xCNBtnBrowseAddOn">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<div class="form-group">
									<div class="validate-input">
										<label class="xCNLabelFrm">
											<!-- <span style="color:red">*</span> -->
											<?php echo language('service/car/car', 'tCAREndDate') ?>
										</label>
										<div class="input-group">
											<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetCarFinish" name="oetCarFinish" autocomplete="off" value="<?php if ($tCarStop != "") {
																																																				echo $tCarStop;
																																																			} ?>">
											<!-- data-validate="<?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNValidDStart') ?>" -->
											<span class="input-group-btn">
												<button id="obtCarFinishDate" type="button" class="btn xCNBtnDateTime">
													<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
												</button>
											</span>
										</div>
									</div>
								</div>

								<!-- เพิ่ม Browser ลักษณะรถยนต์  -->
								<div class="form-group ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARCategory') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID8" name="oetCarOptionID8" maxlength="5" value="<?php echo @$tCarCategoryID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName1" name="oetCarOptionName1" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption8'); ?>" value="<?php echo @$tCarTypeName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="1">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser ประเภทรถยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARType') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID1" name="oetCarOptionID1" maxlength="5" value="<?php echo @$tCarTypeID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName8" name="oetCarOptionName8" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption1'); ?>" value="<?php echo @$tCarCategoryName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="8">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser ยี่ห้อรถยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARBrand') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID2" name="oetCarOptionID2" maxlength="5" value="<?php echo @$tCarBrandID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName2" name="oetCarOptionName2" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption2'); ?>" value="<?php echo @$tCarBrandName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="2">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser รุ่นรถยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARModel') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID3" name="oetCarOptionID3" maxlength="5" value="<?php echo @$tCarModelID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName3" name="oetCarOptionName3" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption3'); ?>" value="<?php echo @$tCarModelName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="3">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser สีรถยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARColor') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID4" name="oetCarOptionID4" maxlength="5" value="<?php echo @$tCarColorID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName4" name="oetCarOptionName4" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption4'); ?>" value="<?php echo @$tCarColorName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="4">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser เกียร์รถยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARGear') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID5" name="oetCarOptionID5" maxlength="5" value="<?php echo @$tCarGearID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName5" name="oetCarOptionName5" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption5'); ?>" value="<?php echo @$tCarGearName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="5">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser เครื่องยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCAREngine') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID6" name="oetCarOptionID6" maxlength="5" value="<?php echo @$tCarPowerTypeID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName6" name="oetCarOptionName6" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption6'); ?>" value="<?php echo @$tCarPowerTypeName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="6">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<!-- เพิ่ม Browser ขนาดเครื่องยนต์  -->
								<div class="form-group  ">
									<label class="xCNLabelFrm"></span><?php echo language('service/car/car', 'tCARSize') ?></label>
									<div class="input-group"><input class="form-control xCNHide" type="text" id="oetCarOptionID7" name="oetCarOptionID7" maxlength="5" value="<?php echo @$tCarEngineSizeID; ?>">
										<input type="text" class="form-control xWPointerEventNone" id="oetCarOptionName7" name="oetCarOptionName7" maxlength="100" placeholder="<?php echo language('service/car/car', 'tCAROption7'); ?>" value="<?php echo @$tCarEngineSizeName; ?>" readonly>
										<span class="input-group-btn">
											<button id="oimBrowseCarType" type="button" class="btn xWBrowseCarType xCNBtnBrowseAddOn <?= @$tDisabled ?>" option="7">
												<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
											</button>
										</span>
									</div>
								</div>

								<div class="form-group">
									<div class="validate-input">
										<label class="xCNLabelFrm">
											<!-- <span style="color:red">*</span> -->
											<?php echo language('service/car/car', 'tCARStartDate') ?>
										</label>
										<div class="input-group">
											<input type="text" placeholder="YYYY-MM-DD" class="form-control xCNDatePicker xCNInputMaskDate" id="oetCarStart" name="oetCarStart" autocomplete="off" value="<?php if ($tCarStart != "") {
																																																				echo $tCarStart;
																																																			} ?>">
											<!-- data-validate="<?php echo language('product/pdtnoslebyevn/pdtnoslebyevn', 'tEVNValidDStart') ?>" -->
											<span class="input-group-btn">
												<button id="obtCarStartDate" type="button" class="btn xCNBtnDateTime">
													<img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
												</button>
											</span>
										</div>
									</div>
								</div>

								<?php
								if (isset($tRoute) && $tRoute == "carEventEdit") {
									if (isset($tCarRedLabelStaActive) && $tCarRedLabelStaActive == 1) {
										$tRedLabelDisableStaActive   = ' checked';
									} else {
										$tRedLabelDisableStaActive   = '';
									}
								} else {
									$tRedLabelDisableStaActive   = '';
								}
								?>
								<div id="odvCarRedLabel" class="form-group">
									<div class="validate-input">
										<label class="fancy-checkbox">
											<input type="checkbox" id="ocbCarRedLabel" name="ocbCarRedLabel" <?php echo @$tRedLabelDisableStaActive; ?>>
											<span> <?php echo language('service/car/car', 'tCARRedLabel'); ?></span>
										</label>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

			</form>
		</div>

		<div class="tab-pane" style="margin-top:10px;" id="odvInforAllHistoryTap" role="tabpanel" aria-expanded="true">
			<?php
			if ($tRoute == "carEventEdit") {
			?>
				<div id="odvUserCalendarControlPage">
					<div class="row">
						<div class="col-xs-12 col-md-3">
							<div class="form-group" id="odvBtnCarUserSearch">
								<!-- <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tSearchNew') ?></label> -->
								<div class="input-group">
									<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchOrderCarHistory" name="oetSearchOrderCarHistory" autocomplete="off" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
									<span class="input-group-btn">
										<button class="btn xCNBtnSearch" type="button" id="obtSearchCarOrderHistory" name="obtSearchCarOrderHistory">
											<img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="odvAllCarHistoryContentPage">

				</div>
			<?php
			}
			?>
		</div>

		<div class="tab-pane" style="margin-top:10px;" id="odvInforHistoryTap" role="tabpanel" aria-expanded="true">
			<?php
			if ($tRoute == "carEventEdit") {
			?>
				<div id="odvUserCalendarControlPage">
					<div class="row">
						<div class="col-xs-12 col-md-3">
							<div class="form-group" id="odvBtnCarUserSearch">
								<!-- <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tSearchNew') ?></label> -->
								<div class="input-group">
									<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchCarHistory" name="oetSearchCarHistory" autocomplete="off" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
									<span class="input-group-btn">
										<button class="btn xCNBtnSearch" type="button" id="obtSearchCarHistory" name="obtSearchCarHistory">
											<img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>
						<button id="obtCarHistoryAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
						<button id="obtCarHistorySearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
					</div>
					<div id="odvCarHistoryAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
						<form id="ofmCarHistoryFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
							<div class="row">
								<!-- From Search Advanced  Branch -->
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<div class="form-group">
										<label class="xCNLabelFrm"><?= language('document/deliveryorder/deliveryorder', 'tDOAdvSearchBranch'); ?></label>
										<div class="input-group">
											<?php
											if ($this->session->userdata("tSesUsrLevel") != "HQ") {
												if ($this->session->userdata("nSesUsrBchCount") <= 1) { //ค้นหาขั้นสูง
													$tBCHCode   = $this->session->userdata("tSesUsrBchCodeDefault");
													$tBCHName   = $this->session->userdata("tSesUsrBchNameDefault");
												} else {
													$tBCHCode   = '';
													$tBCHName   = '';
												}
											} else {
												$tBCHCode       = "";
												$tBCHName       = "";
											}
											?>
											<input class="form-control xCNHide" type="text" id="oetCarHistoryAdvSearchBchCodeFrom" name="oetCarHistoryAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
											<input class="form-control xWPointerEventNone" type="text" id="oetCarHistoryAdvSearchBchNameFrom" name="oetCarHistoryAdvSearchBchNameFrom" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOAdvSearchFrom'); ?>" readonly value="<?= $tBCHName; ?>">
											<span class="input-group-btn">
												<button id="obtCarHistoryAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
											</span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<div class="form-group">
										<label class="xCNLabelFrm"><?= language('document/deliveryorder/deliveryorder', 'tDOAdvSearchTo'); ?></label>
										<div class="input-group">
											<input class="form-control xCNHide" id="oetCarHistoryAdvSearchBchCodeTo" name="oetCarHistoryAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
											<input class="form-control xWPointerEventNone" type="text" id="oetCarHistoryAdvSearchBchNameTo" name="oetCarHistoryAdvSearchBchNameTo" placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOAdvSearchTo'); ?>" readonly value="<?= $tBCHName; ?>">
											<span class="input-group-btn">
												<button id="obtCarHistoryAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
											</span>
										</div>
									</div>
								</div>
								<!-- From Search Advanced  DocDate -->
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<div class="form-group">
										<label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARDocdate'); ?></label>
										<div class="input-group">
											<input class="form-control xCNDatePicker2" type="text" id="oetCarHistoryAdvSearcDocDate" name="oetCarHistoryAdvSearcDocDate" placeholder="<?php echo language('service/car/car', 'tCARDocdate'); ?>" autocomplete="off">
											<span class="input-group-btn">
												<button id="obtCarHistoryAdvSearcDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
											</span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<label class="xCNLabelFrm"><?= language('service/car/car', 'tCARJointDate'); ?></label>
									<div class="input-group">
										<input class="form-control xCNDatePicker2" type="text" id="oetCarHistoryAdvSearcJointDate" name="oetCarHistoryAdvSearcJointDate" placeholder="<?php echo language('service/car/car', 'tCARJointDate'); ?>" autocomplete="off">
										<span class="input-group-btn">
											<button id="obtCarHistoryAdvSearcJointDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
										</span>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- From Search Advanced Status Doc -->
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<div class="form-group">
										<label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARStatusDoc'); ?></label>
										<select class="selectpicker form-control" id="ocmCarHistoryAdvSearchStaDoc" name="ocmCarHistoryAdvSearchStaDoc">
											<option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
											<option value='1'><?php echo language('service/car/car', 'tCARStatusDoc1'); ?></option>
											<option value='2'><?php echo language('service/car/car', 'tCARStatusDoc2'); ?></option>
											<option value='3'><?php echo language('service/car/car', 'tCARStatusDoc3'); ?></option>
											<option value='4'><?php echo language('service/car/car', 'tCARStatusDoc4'); ?></option>
										</select>
									</div>
								</div>
								<!-- Button Form Search Advanced -->
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
									<div class="form-group" style="width:60%;">
										<label class="xCNLabelFrm">&nbsp;</label>
										<button id="obtCarHistoryAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div id="odvCarHistoryContentPage"></div>
			<?php
			}
			?>
		</div>

		<!--ประวัติตามบิลขาย-->
		<div class="tab-pane" style="margin-top:10px;" id="odvInforHistorySaleTap" role="tabpanel" aria-expanded="true">
			<?php if ($tRoute == "carEventEdit") { ?>
				<div>
					<div class="row">
						<div class="col-xs-12 col-md-3">
							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchCarSaleHistory" name="oetSearchCarSaleHistory" autocomplete="off" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
									<span class="input-group-btn">
										<button class="btn xCNBtnSearch" type="button" id="obtSearchCarSaleHistory" name="obtSearchCarSaleHistory">
											<img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="odvCarSaleHistoryContentPage"></div>
			<?php } ?>
		</div>
	</div>

</div>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include "script/jCarAdd.php"; ?>