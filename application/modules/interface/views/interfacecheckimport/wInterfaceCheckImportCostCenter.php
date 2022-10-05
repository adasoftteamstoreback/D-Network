<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped">
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="40%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCostCenter'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="40%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProfitCenter'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="15%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceTime'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>
						<?php
						if ($aValue['CostCenter'] == null){
							$tShowCostCenter  =  "-" ;
						}else{
							$tShowCostCenter = $aValue['CostCenter'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCostCenter?></td>

						<?php
						if ($aValue['ProfitCenter'] == null){
							$tShowProfitCenter  =  "-" ;
						}else{
							$tShowProfitCenter =$aValue['ProfitCenter'];;
						}?>
						<td nowrap class="text-left"><?php echo $tShowProfitCenter ?></td>

						<?php
						if ($aValue['FDCreateOn'] == null){
							$tShowCreateOn  =  "-" ;
						}else{
							$tShowCreateOn = date_format(date_create($aValue['FDCreateOn']), 'Y-m-d  H:i:s');
						}?>
						<td class="text-center"><?php echo $tShowCreateOn ?></td>

					</tr>
					<?php endforeach; ?>
					<?php else :  ?>
					<tr><td class='text-center xCNTextDetail2' colspan='4'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>
					<?php endif ?>

				</tbody>
			</table>
			<div class="row">
				<div class="col-md-6">
					<p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataListImport['nAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataListImport['nCurrentPage']?> / <?=$aDataListImport['nAllPage']?></p>
				</div>
				<div class="col-md-6">
					<div class="xWPagechk btn-toolbar pull-right">
						<?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
						<button onclick="JSvCHkClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
							<i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
						</button>
						<?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataListImport['nAllPage'],$nPage+2)); $i++){?>
						<?php
							if($nPage == $i){
								$tActive = 'active';
								$tDisPageNumber = 'disabled';
							}else{
								$tActive = '';
								$tDisPageNumber = '';
							}
						?>
						<button onclick="JSvCHkClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
							<?php } ?>
							<?php if($nPage >= $aDataListImport['nAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
								<button onclick="JSvCHkClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
								<i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
						</button>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function JSvCHkClickPage(ptPage) {   // รับ paramiter หน้า
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
			JSvInterfaceImportCostCenter(nPageCurrent);  // ทำงาน JSvInterfaceImportCostCenter ส่ง paramiter ไป
			JCNxCloseLoading();
	}


</script>
