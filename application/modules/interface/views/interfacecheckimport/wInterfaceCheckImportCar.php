<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:0px 20px 20px 20px">
			<table id="otbInterfaceImport" class="table table-striped"> 
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCARID'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceREGNO'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIO'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCOSTCENTER'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfacePAY_VAT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCARSTATUS'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCONTRACTSTATUS'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceOWNERID'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceOWNERNAME'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceBRANDNAME'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceMODELNAME'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceCOLOR'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceENGINENO'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceENGINESIZE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterCARTYPE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="7%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
						
						
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>

						<?php 
						if ($aValue['CARID'] == null){
							$tShowCARID  =  "-" ;
						}else{ 
							$tShowCARID = $aValue['CARID'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCARID ?></td>

						<?php 
						if ($aValue['REGNO'] == null){
							$tShowREGNO  =  "-" ;
						}else{ 
							$tShowREGNO = $aValue['REGNO'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowREGNO ?></td>
						
						<?php 
						if ($aValue['IO'] == null){
							$tShowIO  =  "-" ;
						}else{ 
							$tShowIO = $aValue['IO'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowIO ?></td>
						
						<?php 
						if ($aValue['COSTCENTER'] == null){
							$tShowCOSTCENTER  =  "-" ;
						}else{ 
							$tShowCOSTCENTER = $aValue['COSTCENTER'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCOSTCENTER ?></td>
						
						<?php 
						if ($aValue['PAY_VAT'] == null){
							$tShowPAY_VAT  =  "-" ;
						}else{ 
							$tShowPAY_VAT = $aValue['PAY_VAT'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowPAY_VAT ?></td>	

						<?php 
						if ($aValue['CARSTATUS'] == null){
							$tShowCARSTATUS  =  "-" ;
						}else{ 
							$tShowCARSTATUS = $aValue['CARSTATUS'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCARSTATUS ?></td>

						<?php 
						if ($aValue['CONTRACTSTATUS'] == null){
							$tShowCONTRACTSTATUS  =  "-" ;
						}else{ 
							$tShowCONTRACTSTATUS = $aValue['CONTRACTSTATUS'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCONTRACTSTATUS ?></td>
						
						<?php 
						if ($aValue['OWNERID'] == null){
							$tShowOWNERID  =  "-" ;
						}else{ 
							$tShowOWNERID = $aValue['OWNERID'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowOWNERID ?></td>

						<?php 
						if ($aValue['OWNERNAME'] == null){
							$tShowOWNERNAME  =  "-" ;
						}else{ 
							$tShowOWNERNAME = $aValue['OWNERNAME'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowOWNERNAME ?></td>
						
						<?php 
						if ($aValue['BRANDNAME'] == null){
							$tShowBRANDNAME  =  "-" ;
						}else{ 
							$tShowBRANDNAME = $aValue['BRANDNAME'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowBRANDNAME ?></td>

						<?php 
						if ($aValue['MODELNAME'] == null){
							$tShowMODELNAME  =  "-" ;
						}else{ 
							$tShowMODELNAME = $aValue['MODELNAME'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowMODELNAME ?></td>
						
						<?php 
						if ($aValue['COLOR'] == null){
							$tShowCOLOR  =  "-" ;
						}else{ 
							$tShowCOLOR = $aValue['COLOR'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCOLOR ?></td>
						
						<?php 
						if ($aValue['ENGINENO'] == null){
							$tShowENGINENO  =  "-" ;
						}else{ 
							$tShowENGINENO = $aValue['ENGINENO'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowENGINENO ?></td>

						<?php 
						if ($aValue['ENGINESIZE'] == null){
							$tShowENGINESIZE  =  "-" ;
						}else{ 
							$tShowENGINESIZE = $aValue['ENGINESIZE'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowENGINESIZE ?></td>	

						<?php 
						if ($aValue['CARTYPE'] == null){
							$tShowCARTYPE  =  "-" ;
						}else{ 
							$tShowCARTYPE = $aValue['CARTYPE'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowCARTYPE ?></td>

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
					<tr><td class='text-center xCNTextDetail2' colspan='19'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>	
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
		JSvInterfaceImportCar(nPageCurrent);  // ทำงาน JSvInterfaceImportCar ส่ง paramiter ไป
		JCNxCloseLoading();
}
</script>