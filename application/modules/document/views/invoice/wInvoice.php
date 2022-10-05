<!-- ใบสั่งซื้อ-->

<input id="oetIVStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetIVCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvIVMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNIVMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docInvoice/0/0');?>
						<li id="oliIVTitle"     class="xCNLinkClick" onclick="JSvIVCallPageList('')"><?= language('document/invoice/invoice','tIVTitle')?></li>
                        <li id="oliIVTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','tIVTitleAdd')?></a></li>
						<li id="oliIVTitleEdit" class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','tIVTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
                        <?php if($aPermission["tAutStaFull"] == "1" || $aPermission["tAutStaAdd"] == "1") : ?>
						<div id="odvBtnIVPageAddorEdit">
							<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvIVCallPageAdd('pageadd')">+</button>
						</div>
						<?php endif; ?>
						
						<div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSvIVCallPageList('')"><?=language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aPermission['tAutStaFull'] == 1 || ($aPermission['tAutStaAdd'] == 1 || $aPermission['tAutStaEdit'] == 1)): ?>
                                    <button id="obtIVPrintDoc" 	    class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxIVPrintDoc()"><?=language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtIVCancelDoc" 	class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxIVDocumentCancel(false)"> <?=language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtIVApproveDoc" 	class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?=language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                    <div class="btn-group xCNBTNSaveDoc">
                                        <button id="obtIVSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
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
	<div id="odvContentIV"></div>
</div>

<?php include('script/jInvoice.php') ?>
