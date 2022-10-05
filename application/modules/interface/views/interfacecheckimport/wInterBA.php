<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped">
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceInterBaType'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceBusinessArea'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePartner_BusinessArea'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceInterBaID'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="15%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>

					<tr>
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>
						<?php
						if ($aValue['InterBaType'] == null){
							$tShowInterBaType  =  "-" ;
						}else{
							$tShowInterBaType = $aValue['InterBaType'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowInterBaType ?></td>

						<?php
						if ($aValue['BusinessArea'] == null){
							$tShowBusinessArea  =  "-" ;
						}else{
							$tShowBusinessArea = $aValue['BusinessArea'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowBusinessArea ?></td>

						<?php
						if ($aValue['Partner_BusinessArea'] == null){
							$tShowPartner_BusinessArea  =  "-" ;
						}else{
							$tShowPartner_BusinessArea = $aValue['Partner_BusinessArea'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowPartner_BusinessArea ?></td>

						<?php
						if ($aValue['InterBaID'] == null){
							$tShowInterBaID  =  "-" ;
						}else{
							$tShowInterBaID = $aValue['InterBaID'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowInterBaID ?></td>

						<?php
						if ($aValue['FDCreateOn'] == null){
							$tShowCreateOn  =  "-" ;
						}else{
							$tShowCreateOn = date_format(date_create($aValue['FDCreateOn']), 'Y-m-d H:i:s');
						}?>
						<td class="text-center"><?php echo $tShowCreateOn; ?></td>
					</tr>
					<?php endforeach; ?>
					<?php else : ?>
					<tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>
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
			JCNxOpenLoading(); // โหลดหน้าใหม่
			JSvInterfaceImportInterBa(nPageCurrent);  // ทำงาน JSvInterfaceImportInterBa ส่ง paramiter ไป
			JCNxCloseLoading(); // ปิดหน้าใหม่
	}
</script>
