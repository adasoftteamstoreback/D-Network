<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped"> 
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
					<th nowrap class="text-center xCNTextBold" width="15%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePERSONNEL'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSTARTDATE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceENDDATE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALES_ORG'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALESOFF'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALESGRP'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceSALESDIST'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceNAME'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="10%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>

						<?php 
						if ($aValue['PERSONNEL'] == null){
							$tShowPERSONNEL  =  "-" ;
						}else{ 
							$tShowPERSONNEL = $aValue['PERSONNEL'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowPERSONNEL ?></td>

						<?php 
						if ($aValue['STARTDATE'] == null){
							$tShowSTARTDATE  =  "-" ;
						}else{ 
							$tShowSTARTDATE = date_format(date_create($aValue['STARTDATE']), 'Y/m/d');
						}?>
						<td nowrap class="text-center"><?php echo $tShowSTARTDATE ?></td>	

						<?php 
						if ($aValue['ENDDATE'] == null){
							$tShowENDDATE  =  "-" ;
						}else{ 
							$tShowENDDATE = date_format(date_create($aValue['ENDDATE']), 'Y/m/d');
						}?>
						<td nowrap class="text-center"><?php echo $tShowENDDATE  ?></td>	

						<?php 
						if ($aValue['SALES_ORG'] == null){
							$tShowSALES_ORG  =  "-" ;
						}else{ 
							$tShowSALES_ORG = $aValue['SALES_ORG'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALES_ORG ?></td>
						
						<?php 
						if ($aValue['SALESOFF'] == null){
							$tShowSALESOFF  =  "-" ;
						}else{ 
							$tShowSALESOFF = $aValue['SALESOFF'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALESOFF ?></td>

						<?php 
						if ($aValue['SALESGRP'] == null){
							$tShowSALESGRP  =  "-" ;
						}else{ 
							$tShowSALESGRP = $aValue['SALESGRP'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALESGRP ?></td>	

						<?php 
						if ($aValue['SALESDIST'] == null){
							$tShowSALESDIST  =  "-" ;
						}else{ 
							$tShowSALESDIST = $aValue['SALESDIST'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowSALESDIST ?></td>	

						<?php 
						if ($aValue['NAME'] == null){
							$tShowNAME =  "-" ;
						}else{ 
							$tShowNAME = $aValue['NAME'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowNAME  ?></td>	

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
		JSvInterfaceImportSaleforStore(nPageCurrent);  // ทำงาน JSvInterfaceImportSaleforStore ส่ง paramiter ไป
		JCNxCloseLoading();
}		
</script>