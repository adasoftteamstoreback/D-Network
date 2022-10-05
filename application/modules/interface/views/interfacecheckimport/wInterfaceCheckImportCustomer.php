<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped"> 
				<thead>
				<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALES_ORG'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALES_ORG_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIST_CHANNEL'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIST_CHANNEL_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIVISION'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIVISION_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_NUMBER'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_NAME1'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_NAME2'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_GR'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_GR_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_GR1'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_GR1_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALES_DISTRICT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALES_DISTRICT_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePRICE_GR'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePRICE_GR_DESC'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
						
					
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>
						<?php 
						if ($aValue['SALES_ORG'] == null){
							$tShowSALES_ORG  =  "-" ;
						}else{ 
							$tShowSALES_ORG = $aValue['SALES_ORG'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALES_ORG  ?></td>

						<?php 
						if ($aValue['SALES_ORG_DESC'] == null){
							$tShowSALES_ORG_DESC  =  "-" ;
						}else{ 
							$tShowSALES_ORG_DESC = $aValue['SALES_ORG_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALES_ORG_DESC ?></td>

						<?php 
						if ($aValue['DIST_CHANNEL'] == null){
							$tShowDIST_CHANNEL  =  "-" ;
						}else{ 
							$tShowDIST_CHANNEL = $aValue['DIST_CHANNEL'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIST_CHANNEL ?></td>

						<?php 
						if ($aValue['DIST_CHANNEL_DESC'] == null){
							$tShowDIST_CHANNEL_DESC  =  "-" ;
						}else{ 
							$tShowDIST_CHANNEL_DESC = $aValue['DIST_CHANNEL_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIST_CHANNEL_DESC ?></td>

						<?php 
						if ($aValue['DIVISION'] == null){
							$tShowDIVISION  =  "-" ;
						}else{ 
							$tShowDIVISION = $aValue['DIVISION'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIVISION ?></td>

						<?php 
						if ($aValue['DIVISION_DESC'] == null){
							$tShowDIVISION_DESC  =  "-" ;
						}else{ 
							$tShowDIVISION_DESC = $aValue['DIVISION_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIVISION_DESC ?></td>

						<?php 
						if ($aValue['CUST_NUMBER'] == null){
							$tShowCUST_NUMBER  =  "-" ;
						}else{ 
							$tShowCUST_NUMBER = $aValue['CUST_NUMBER'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_NUMBER ?></td>

						<?php 
						if ($aValue['CUST_NAME1'] == null){
							$tShowCUST_NAME1  =  "-" ;
						}else{ 
							$tShowCUST_NAME1 = $aValue['CUST_NAME1'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_NAME1 ?></td>

						<?php 
						if ($aValue['CUST_NAME2'] == null){
							$tShowCUST_NAME2  =  "-" ;
						}else{ 
							$tShowCUST_NAME2 = $aValue['CUST_NAME2'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_NAME2 ?></td>

						<?php 
						if ($aValue['CUST_GR'] == null){
							$tShowCUST_GR  =  "-" ;
						}else{ 
							$tShowCUST_GR = $aValue['CUST_GR'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_GR ?></td>

						<?php 
						if ($aValue['CUST_GR_DESC'] == null){
							$tShowCUST_GR_DESC  =  "-" ;
						}else{ 
							$tShowCUST_GR_DESC = $aValue['CUST_GR_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_GR_DESC ?></td>

						<?php 
						if ($aValue['CUST_GR1'] == null){
							$tShowCUST_GR1  =  "-" ;
						}else{ 
							$tShowCUST_GR1 = $aValue['CUST_GR1'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_GR1 ?></td>

						<?php 
						if ($aValue['CUST_GR1_DESC'] == null){
							$tShowCUST_GR1_DESC  =  "-" ;
						}else{ 
							$tShowCUST_GR1_DESC = $aValue['CUST_GR1_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_GR1_DESC ?></td>



						<?php 
						if ($aValue['SALES_DISTRICT'] == null){
							$tShowSALES_DISTRICT  =  "-" ;
						}else{ 
							$tShowSALES_DISTRICT = $aValue['SALES_DISTRICT'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALES_DISTRICT ?></td>

						<?php 
						if ($aValue['SALES_DISTRICT_DESC'] == null){
							$tShowSALES_DISTRICT_DESC  =  "-" ;
						}else{ 
							$tShowSALES_DISTRICT_DESC = $aValue['SALES_DISTRICT_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALES_DISTRICT_DESC ?></td>

						<?php 
						if ($aValue['PRICE_GR'] == null){
							$tShowPRICE_GR =  "-" ;
						}else{ 
							$tShowPRICE_GR = $aValue['PRICE_GR'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowPRICE_GR ?></td>

						<?php 
						if ($aValue['PRICE_GR_DESC'] == null){
							$tShowPRICE_GR_DESC  =  "-" ;
						}else{ 
							$tShowPRICE_GR_DESC = $aValue['PRICE_GR_DESC'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowPRICE_GR_DESC ?></td>

						<?php 
						if ($aValue['FDCreateOn'] == null){
							$tShowCreateOn  =  "-" ;
						}else{ 
							$tShowCreateOn = date_format(date_create($aValue['FDCreateOn']), 'Y-m-d H:i:s');
						}?>
						<td class="text-center"><?php echo $tShowCreateOn ?></td>
					</tr>
					<?php endforeach; ?>
					<?php else :  ?>	
					<tr><td class='text-center xCNTextDetail2' colspan='89'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>	
					<?php endif ?>

				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-6" style="padding-left: 40px" >
			<p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataListImport['nAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataListImport['nCurrentPage']?> / <?=$aDataListImport['nAllPage']?></p>
			</div>
				<div class="col-md-6">
					<div class="xWPagechk btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
							<?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
							<button onclick="JSvCHkClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
							<i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
							</button>
							<?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataListImport['nAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
							<?php 
							if($nPage == $i){ 
								$tActive = 'active'; 
								$tDisPageNumber = 'disabled';
							}else{ 
								$tActive = '';
								$tDisPageNumber = '';
							}
						?>
							<!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
						<button onclick="JSvCHkClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
								<?php } ?>
								<?php if($nPage >= $aDataListImport['nAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
									<button onclick="JSvCHkClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
									<i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
								</button>
					</div>
				</div>
		</div>		
	</div>
</div>
<script src="<?=base_url('application/modules/interface/assets/src/interfacecheckimport/jInterfaceCheckImport.js')?>"></script>
<script>
	function JSvCHkClickPage(ptPage) { // รับ paramiter หน้า 
		var nPageCurrent = '';
		switch (ptPage) {
			case 'next': //กดปุ่ม Next
				$('.xWBtnNext').addClass('disabled');
				nPageOld = $('.xWPagechk .active').text(); // Get เลขก่อนหน้า
				nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
				nPageCurrent = nPageNew
				break;
			case 'previous': //กดปุ่ม Previous
				nPageOld = $('.xWPagechk .active').text(); // Get เลขก่อนหน้า
				nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
				nPageCurrent = nPageNew
				break;
			default:
				nPageCurrent = ptPage
		}
		JCNxOpenLoading();
		JSvInterfaceImportCustomer(nPageCurrent);  // ทำงาน JSvInterfaceImportCustomer ส่ง paramiter ไป
		JCNxCloseLoading();
	}
</script>