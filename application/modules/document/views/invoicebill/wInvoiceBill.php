<!-- ใบเคลม -->
<link rel="stylesheet" href="<?=base_url(); ?>application/modules/document/views/claimproduct/css/adaClaim.css">

<!-- รองรับการเข้ามาแบบ Noti -->
<input id="oetIVBJumpDocNo" 	type="hidden" value="<?=$aParams['tDocNo'] ?>">
<input id="oetIVBJumpBchCode" 	type="hidden" value="<?=$aParams['tBchCode'] ?>">
<input id="oetIVBJumpAgnCode" 	type="hidden" value="<?=$aParams['tAgnCode'] ?>">
<input id="oetIVBJumpBrwType" 	type="hidden" value="<?=$nIVBBrowseType?>">
<input id="oetIVBJumpBrwOption" type="hidden" value="<?=$tIVBBrowseOption?>">

<div id="odvIVBMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNIVBMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docInvoicebill/0/0');?>
						<li id="oliIVBTitle"     class="xCNLinkClick" onclick="JSvIVBCallPageList('')"><?= language('document/invoice/invoice','ใบรับวางบิล')?></li>
                        <li id="oliIVBTitleAdd"  class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','เพิ่มข้อมูล')?></a></li>
						<li id="oliIVBTitleEdit" class="active"><a href="javascrip:;"><?= language('document/invoice/invoice','ตรวจสอบข้อมูล')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
                        <?php if($aAlwEvent["tAutStaFull"] == "1" || $aAlwEvent["tAutStaAdd"] == "1") : ?>
						<div id="odvBtnIVBPageAddorEdit">
							<button class="xCNBTNPrimeryPlus" type="button" onclick="JSvIVBCallPageAdd('pageadd')">+</button>
						</div>
						<?php endif; ?>
						
						<div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSvIVBCallPageList('')"><?=language('common/main/main', 'tBack'); ?></button>
								<button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" id="obtIVBCancelDoc" style="display:none;" onclick="JSxIVBDocumentCancel(false)"> <?=language('common/main/main', 'tCancel'); ?></button>
								<button id="obtIVBPrintDoc" 	    class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxIVPrintDoc()"><?=language('common/main/main', 'tCMNPrint'); ?></button>
								<button id="obtIVBApproveDoc" 	class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxIVBDocumentApv(false)"> <?=language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <div class="btn-group xCNBTNSaveDoc">
                                        <button id="obtIVBSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"><?=language('common/main/main', 'tSave'); ?></button>
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
	<div id="odvContentIVB"></div>
</div>

<?php include('script/jInvoicebill.php') ?>
