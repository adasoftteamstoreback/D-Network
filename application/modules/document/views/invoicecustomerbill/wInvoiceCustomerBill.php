<!-- ใบเคลม -->
<link rel="stylesheet" href="<?=base_url(); ?>application/modules/document/views/claimproduct/css/adaClaim.css">

<!-- รองรับการเข้ามาแบบ Noti -->
<input id="oetIVCJumpDocNo" 	type="hidden" value="<?=$aParams['tDocNo'] ?>">
<input id="oetIVCJumpBchCode" 	type="hidden" value="<?=$aParams['tBchCode'] ?>">
<input id="oetIVCJumpAgnCode" 	type="hidden" value="<?=$aParams['tAgnCode'] ?>">
<input id="oetIVCJumpBrwType" 	type="hidden" value="<?=$nIVCBrowseType?>">
<input id="oetIVCJumpBrwOption" type="hidden" value="<?=$tIVCBrowseOption?>">
<div id="odvIVCMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNIVCMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docInvoiceCustomerBill/0/0');?>
						<li id="oliIVCTitle"     class="xCNLinkClick" onclick="JSvIVCCallPageList('')"><?= language('document/invoicebill/invoicebill','tIVCTitle')?></li>
                        <li id="oliIVCTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/invoicebill/invoicebill','tIVCtAddData')?></a></li>
						<li id="oliIVCTitleEdit" class="active"><a href="javascrip:;"><?= language('document/invoicebill/invoicebill','tIVCInspect')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
                        <?php if($aAlwEvent["tAutStaFull"] == "1" || $aAlwEvent["tAutStaAdd"] == "1") : ?>
							<div id="odvBtnIVCPageAddorEdit">
							<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvIVCCallPageAdd('pageadd')">+</button>
						</div>
						<?php endif; ?>
						
						<div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSvIVCCallPageList('')"><?=language('common/main/main', 'tBack'); ?></button>
								<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" id="obtIVCCancelDoc" style="display:none;" onclick="JSxIVCDocumentCancel(false)"> <?=language('common/main/main', 'tCancel'); ?></button>
								<button id="obtIVCPrintDoc" 	    class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxIVPrintDoc()"><?=language('common/main/main', 'tCMNPrint'); ?></button>
								<button id="obtIVCApproveDoc" 	class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxIVCDocumentApv(false)"> <?=language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <div class="btn-group xCNBTNSaveDoc">
                                        <button id="obtIVCSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
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
	<div id="odvContentIVC"></div>
</div>

<?php include('script/jInvoicecustomerbill.php') ?>
