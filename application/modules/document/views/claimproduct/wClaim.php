<!-- ใบเคลม -->
<link rel="stylesheet" href="<?=base_url(); ?>application/modules/document/views/claimproduct/css/adaClaim.css">

<!-- รองรับการเข้ามาแบบ Noti -->
<input id="oetCLMJumpDocNo" 	type="hidden" value="<?=$aParams['tDocNo'] ?>">
<input id="oetCLMJumpBchCode" 	type="hidden" value="<?=$aParams['tBchCode'] ?>">
<input id="oetCLMJumpAgnCode" 	type="hidden" value="<?=$aParams['tAgnCode'] ?>">
<input id="oetCLMJumpBrwType" 	type="hidden" value="<?=$nCLMBrowseType?>">
<input id="oetCLMJumpBrwOption" type="hidden" value="<?=$tCLMBrowseOption?>">

<div id="odvCLMMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNCLMMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docClaim/0/0');?>
						<li id="oliCLMTitle"     class="xCNLinkClick" onclick="JSvCLMCallPageList('')"><?= language('document/invoice/invoice','ใบเคลม')?></li>
                        <li id="oliCLMTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','เพิ่มข้อมูล')?></a></li>
						<li id="oliCLMTitleEdit" class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','ตรวจสอบข้อมูล')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
                        <?php if($aAlwEvent["tAutStaFull"] == "1" || $aAlwEvent["tAutStaAdd"] == "1") : ?>
						<div id="odvBtnCLMPageAddorEdit">
							<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCLMCallPageAdd('pageadd')">+</button>
						</div>
						<?php endif; ?>
						
						<div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSvCLMCallPageList('')"><?=language('common/main/main', 'tBack'); ?></button>
								<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" id="obtCLMCancelDoc" style="display:none;" onclick="JSxCLMDocumentCancel(false)"> <?=language('common/main/main', 'tCancel'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <div class="btn-group xCNBTNSaveDoc">
                                        <button id="obtCLMSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
                                        <?php echo $vBtnSave ?>
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
<div class="xCNMenuCump" id="odvMenuCump">
	&nbsp;
</div>
<div class="main-content" id="odvMainContent" style="background-color: #F0F4F7;">    
	<div id="odvContentCLM"></div>
</div>

<?php include('script/jClaim.php') ?>
