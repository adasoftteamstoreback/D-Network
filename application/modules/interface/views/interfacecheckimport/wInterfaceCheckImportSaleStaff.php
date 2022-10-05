<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped">
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUST_NUMBER'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALE_ORG'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIST_CHANNEL'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDIVISION'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSTART_DATE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceEND_DATE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceOLD_SALE_DISTRICT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceNEW_SALE_DISTRICT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCUSTOMER_GROUP'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePRICE_GROUP'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>

					<tr>
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>

						<?php
						if ($aValue['CUST_NUMBER'] == null){
							$tShowCUST_NUMBER  =  "-" ;
						}else{
							$tShowCUST_NUMBER  = $aValue['CUST_NUMBER'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCUST_NUMBER ?></td>

							<?php
						if ($aValue['SALE_ORG'] == null){
							$tShowSALE_ORG  =  "-" ;
						}else{
							$tShowSALE_ORG = $aValue['SALE_ORG'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALE_ORG ?></td>

						<?php
						if ($aValue['DIST_CHANNEL'] == null){
							$tShowDIST_CHANNEL  =  "-" ;
						}else{
							$tShowDIST_CHANNEL = $aValue['DIST_CHANNEL'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIST_CHANNEL ?></td>

						<?php
						if ($aValue['DIVISION'] == null){
							$tShowDIVISION  =  "-" ;
						}else{
							$tShowDIVISION = $aValue['DIVISION'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDIVISION ?></td>

						<?php
						if ($aValue['START_DATE'] == null){
							$tShowSTART_DATE  =  "-" ;
						}else{
							$tShowSTART_DATE = date_format(date_create($aValue['START_DATE']), 'Y/m/d');
						}?>
						<td class="text-center"><?php echo $tShowSTART_DATE; ?></td>

						<?php
						if ($aValue['END_DATE'] == null){
							$tShowEND_DATE  =  "-" ;
						}else{
							$tShowEND_DATE = date_format(date_create($aValue['END_DATE']), 'Y/m/d');
						}?>
						<td class="text-center"><?php echo $tShowEND_DATE ?></td>

						<?php
						if ($aValue['OLD_SALE_DISTRICT'] == null){
							$tShowOLD_SALE_DISTRICT  =  "-" ;
						}else{
							$tShowOLD_SALE_DISTRICT  = $aValue['OLD_SALE_DISTRICT'];
							}?>
						<td nowrap class="text-left"><?php echo $tShowOLD_SALE_DISTRICT ?></td>

						<?php
						if ($aValue['NEW_SALE_DISTRICT'] == null){
							$tShowNEW_SALE_DISTRICT  =  "-" ;
						}else{
							$tShowNEW_SALE_DISTRICT  = $aValue['NEW_SALE_DISTRICT'];
							}?>
						<td nowrap class="text-left"><?php echo $tShowNEW_SALE_DISTRICT ?></td>

						<?php
						if ($aValue['CUSTOMER_GROUP'] == null){
							$tShowCUSTOMER_GROUP  =  "-" ;
						}else{
							$tShowCUSTOMER_GROUP  = $aValue['CUSTOMER_GROUP'];
							}?>
						<td nowrap class="text-left"><?php echo $tShowCUSTOMER_GROUP ?></td>

						<?php
						if ($aValue['PRICE_GROUP'] == null){
							$tShowPRICE_GROUP  =  "-" ;
						}else{
							$tShowPRICE_GROUP  = $aValue['PRICE_GROUP'];
							}?>
						<td nowrap class="text-left"><?php echo $tShowPRICE_GROUP ?></td>

						<?php
						if ($aValue['FDCreateOn'] == null){
							$tShowCreateOn  =  "-" ;
						}else{
							$tShowCreateOn = date_format(date_create($aValue['FDCreateOn']), 'Y-m-d H:i:s');
						}?>
						<td class="text-center"><?php echo $tShowCreateOn; ?></td>
					</tr>
					<?php endforeach; ?>
					<?php else :  ?>
					<tr><td class='text-center xCNTextDetail2' colspan='12'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>
					<?php endif ?>

				</tbody>
			</table>
			<div class="row">
				<div class="col-md-6">
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
		JSvInterfaceImportSale(nPageCurrent);  // ทำงาน JSvInterfaceImportSale ส่ง paramiter ไป
		JCNxCloseLoading();
	}
</script>
