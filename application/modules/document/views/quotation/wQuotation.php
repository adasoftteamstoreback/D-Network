<!-- ใ บ เ ส น อ ร า ค า -->

<input id="oetQTStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetQTCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvQTMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNQTMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docQuotation/0/0');?>
						<li id="oliQTTitle"     class="xCNLinkClick" onclick="JSvQTCallPageList('')"><?= language('document/quotation/quotation','tQTTitle')?></li>
                        <li id="oliQTTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/quotation/quotation','tQTTitleAdd')?></a></li>
						<li id="oliQTTitleEdit" class="active"><a href="javascrip:;"><?= language('document/quotation/quotation','tQTTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
                        <?php if($aPermission["tAutStaFull"] == "1" || $aPermission["tAutStaAdd"] == "1") : ?>
						<div id="odvBtnQTPageAddorEdit">
							<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvQTCallPageAdd('pageadd')">+</button>
						</div>
						<?php endif; ?>
						
						<div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtBtnBack" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"><?=language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aPermission['tAutStaFull'] == 1 || ($aPermission['tAutStaAdd'] == 1 || $aPermission['tAutStaEdit'] == 1)): ?>
                                    <button id="obtQTPrintDoc" 	    class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxQTPrintDoc()" ><?=language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtQTCancelDoc" 	class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxQTDocumentCancel(false)"> <?=language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtQTApproveDoc" 	class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxQTDocumentApv(false)"> <?=language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                    <div class="btn-group xCNBTNSaveDoc">
                                        <button id="obtQTSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
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
	<div id="odvContentQT"></div>
</div>

<?php include('script/jQuotation.php') ?>
