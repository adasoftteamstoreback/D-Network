<div class="row">
	<div class="col-md-12">
		<div class="table-responsive" style="padding:5px 20px 20px 20px" >
			<table id="otbInterfaceImport" class="table table-striped"> 
				<thead>
					<tr>
					<th nowrap class="text-center xCNTextBold" width="5%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceIdT'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductID'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductDeptID'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductCode'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductBarCode'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="15%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductName'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%"> <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceProductName1'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDiscountAllow'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceDeleted'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="20%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceVATTYPE'); ?></th>
						<th nowrap class="text-center xCNTextBold" width="15%">  <?php echo language('interface/interfacechkimport/interfacechkimport','tInterfaceFDCreateOn'); ?></th>
					</tr>
				</thead>
				<tbody>	
					<?php if (isset($aDataListImport['aItems']) && is_array($aDataListImport['aItems']) && !empty($aDataListImport['aItems'])) : ?>
					<?php foreach ($aDataListImport['aItems'] as $key => $aValue) : ?>
						
					<tr>
						<td nowrap class="text-center"><?php echo $aValue['rtRowID'] ?></td>
						<?php 
						if ($aValue['ProductID'] == null){
							$tShowProductID  =  "-" ;
						}else{ 
							$tShowProductID = $aValue['ProductID'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductID ?></td>
						
						<?php 
						if ($aValue['ProductDeptID'] == null){
							$tShowProductDeptID  =  "-" ;
						}else{ 
							$tShowProductDeptID = $aValue['ProductDeptID'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductDeptID ?></td>	

						<?php 
						if ($aValue['ProductCode'] == null){
							$tShowProductCode  =  "-" ;
						}else{ 
							$tShowProductCode = $aValue['ProductCode'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductCode ?></td>	

						<?php 
						if ($aValue['ProductBarCode'] == null){
							$tShowProductBarCode  =  "-" ;
						}else{ 
							$tShowProductBarCode = $aValue['ProductBarCode'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductBarCode ?></td>
						
						<?php 
						if ($aValue['ProductName'] == null){
							$tShowProductName  =  "-" ;
						}else{ 
							$tShowProductName = $aValue['ProductName'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductName ?></td>		


						<?php 
						if ($aValue['ProductName1'] == null){
							$tShowProductName1  =  "-" ;
						}else{ 
							$tShowProductName1 = $aValue['ProductName1'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowProductName1 ?></td>
						
						<?php 
						if ($aValue['DiscountAllow'] == null){
							$tShowDiscountAllow  =  "-" ;
						}else if($aValue['DiscountAllow'] == 0){
							$tShowDiscountAllow  = language('interface/interfacechkimport/interfacechkimport','tInterfaceAllow'); 
						}else if($aValue['DiscountAllow'] == 1)  { 
							$tShowDiscountAllow  = language('interface/interfacechkimport/interfacechkimport','tInterfaceNoAllow');
						}else{
							$tShowDiscountAllow  = $aValue['DiscountAllow'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDiscountAllow ?></td>	

						<?php 
						if ($aValue['Deleted'] == null){
							$tShowDeleted  =  "-" ;
						}else if ($aValue['Deleted'] == 0){
							$tShowDeleted  = language('interface/interfacechkimport/interfacechkimport','tInterfaceActive'); 
						}else if ($aValue['Deleted'] == 1){
							$tShowDeleted  = language('interface/interfacechkimport/interfacechkimport','tInterfaceNoActive');
						}else{
							$tShowDeleted = $aValue['Deleted'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowDeleted ?></td>	

						<?php 
						if ($aValue['VATTYPE'] == null){
							$tShowVATTYPE  =  "-" ;
						}else if($aValue['VATTYPE'] == 1){
							$tShowVATTYPE  = language('interface/interfacechkimport/interfacechkimport','tInterfaceVat'); 
						}else if($aValue['VATTYPE'] == 0){ 
							$tShowVATTYPE  = language('interface/interfacechkimport/interfacechkimport','tInterfaceNoVat');	 
						}else{
							$tShowVATTYPE = $aValue['VATTYPE'];
						}?>
						<td nowrap class="text-left"><?php echo $tShowVATTYPE ?></td>	

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
						<tr><td class='text-center xCNTextDetail2' colspan='11'><?= language('interface/interfaceimport/interfaceimport','tInterfaceNodata')?></td></tr>		
					<?php endif ?>
				</tbody>
			</table>	
			<div class="row">
				<div class="col-md-6" id = "page">
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
    JSvInterfaceImportProducts(nPageCurrent );	// ทำงาน JSvInterfaceImportProducts ส่ง paramiter ไป
	JCNxCloseLoading();
}


</script>