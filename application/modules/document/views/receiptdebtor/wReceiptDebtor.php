<input id="oetRCBStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetRCBCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<style>
.xWRCBDotStatus {
    width: 8px;
    height: 8px;
    border-radius: 100%;
    background: black;
    display: inline-block;
    margin-right: 5px;
}
.xWRCBStatusColor{
    font-weight: bold;
}
.xWRCBGreenColor{
    color:#2ECC71;
}
.xWRCBYellowColor{
    color:#F1C71F;
}
.xWRCBGrayColor{
    color:#7B7B7B;
}
.xWRCBGreenBG{
    background-color:#2ECC71;
}
.xWRCBYellowBG{
    background-color:#F1C71F;
}
.xWRCBGrayBG{
    background-color:#7B7B7B;
}
</style>

<div id="odvRCBMainMenu" class="main-menu">
	<div class="xCNMrgNavMenu">
		<div class="xCNavRow" style="width:inherit;">

			<div class="xCNRCBMaster row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docRCB/0/0');?>
						<li id="oliRCBTitle"     class="xCNLinkClick" onclick="JSxRCBPageList('')"><?= language('document/receiptdebtor/receiptdebtor','tRCBTitle')?></li>
						<li id="oliRCBTitleEdit" class="active"><a href="javascrip:;"><?= language('document/receiptdebtor/receiptdebtor','tRCBEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
						<div id="odvRCBBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSxRCBPageList()"><?=language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aPermission['tAutStaFull'] == 1 || ($aPermission['tAutStaAdd'] == 1 || $aPermission['tAutStaEdit'] == 1)): ?>
                                    <button id="obtRCBDownloadDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" 	type="button" onclick="JSxRCBPrintDoc()"><?=language('common/main/main', 'tCMNPrint'); ?></button>
                                <?php endif; ?>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="xCNMenuCump xCNRCBLine" id="odvMenuCump">
	&nbsp;
</div>

<input type="hidden" id="ohdRCBOldFilterList" value="">
<input type="hidden" id="ohdRCBFilter" value="">
<input type="hidden" id="ohdRCBOldPageList" value="1">

<div class="main-content" id="odvRCBMainContent" style="background-color: #F0F4F7;">    
	<div id="odvRCBContent"></div>
</div>
<iframe id="oifRCBPrint" height="0"></iframe>
<iframe id="oifRCBPrintFullTax" height="0"></iframe>

<?php include('script/jReceiptDebtor.php') ?>