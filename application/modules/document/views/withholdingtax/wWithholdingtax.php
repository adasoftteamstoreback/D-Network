	<div id="odvQahMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('docWhTax');?> 
						<li id="oliWhTaxTitle" style="cursor:pointer" onclick="JSvCallPageWhTaxList()"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxTitle')?></li>
						<li id="oliWhTaxDetail" class="active"><a><?php echo language('document/withholdingtax/withholdingtax','tWhTaxDetail')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
					<div class="demo-button xCNBtngroup" style="width:100%;">
						<button id="obtWhTaxCancelDoc" onclick="JSvCallPageWhTaxList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
						<button id="obtWhTaxPrintDoc" onclick="JSxWhTaxPrintDoc()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNQahBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageWhTax">
		</div>
	</div>

<script src="<?php echo base_url('application/modules/document/assets/src/withholdingtax/jWithholdingtax.js'); ?>"></script>
