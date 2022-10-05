<?php 
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<?php 
// echo '<pre>';
// echo print_r($aAlwEventQasGroup); 
// echo '</pre>';
?>

<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?=$nCurrentPage;?>">
        <div class="table-responsive">
             <table class="table table-striped" style="width:100%">
					<thead>
						<tr>
                        <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
							<th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif;?>
							<th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/qasgroup/qasgroup','tQGPCode')?></th>
							<th nowrap class="xCNTextBold" style="width:30%;text-align:center;"><?= language('service/qasgroup/qasgroup','tQGPName')?></th>
                        <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
							<th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/qasgroup/qasgroup','tQGPTBDelete')?></th>
                        <?php endif;?>
                        <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
							<th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/qasgroup/qasgroup','tQGPTBEdit')?></th>
                        <?php endif;?>
						</tr>
					</thead>
					<tbody id="odvCLDList">
                        <?php if($aDataList['rtCode'] == 1 ):?>
                            <?php foreach($aDataList['raItems'] AS $key=>$aValue){  ?>
                                <tr class="text-center xCNTextDetail2 otrQasSubGroup" id="otrQasSubGroup<?=$key?>" data-code="<?=$aValue['rtQgpCode']?>" data-name="<?=$aValue['rtQgpName']?>">
                                <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
									<td class="text-center">
										<label class="fancy-checkbox">
											<input id="ocbListItem<?=$key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onchange="JSxQasSubGroupVisibledDelAllBtn(this, event)" <?php if ($aValue['rtQahCount'] > 0) {
                                                                echo "disabled";
                                                            } ?>>
											<span class="<?php if ($aValue['rtQahCount'] > 0) {
                                                                echo "xCNDisabled";
                                                            } ?>">&nbsp;</span>
										</label>
									</td>
                            <?php endif;?>
                                    <td nowrap class="text-left"><?= $aValue['rtQgpCode']?></td>
                                    <td nowrap class="text-left"><?= $aValue['rtQgpName']?></td>
                                    <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
                                    <td>                         
										<img class="xCNIconTable xCNIconDel <?php if ($aValue['rtQahCount'] > 0) {
                                                                                echo "xCNDocDisabled";
                                                                            } ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSnQasGroupDel('<?php echo $nCurrentPage?>','<?=$aValue['rtQgpName']?>','<?=$aValue['rtQgpCode']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')" title="<?php echo language('service/qasgroup/qasgroup', 'tQgpTBDelete'); ?>">
									</td>
                                <?php endif;?>
                                <?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
									<td>
										<img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageQasGroupEdit('<?php echo $aValue['rtQgpCode']; ?>')" title="<?php echo language('service/qasgroup/qasgroup', 'tQgpTBEdit'); ?>">
									</td>
                                <?php endif;?>
                                </tr>
                            <?php } ?>
                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                        <?php endif;?>
					</tbody>
			</table>
        </div>
    </div>
</div>

<div class="row">
	<!-- เปลี่ยน -->
	<div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
	<!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageQasSubGroupGrp btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
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
                <button onclick="JSvClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelQasGroup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnQasGroupDelChoose('<?=$nCurrentPage?>')">
					<?php echo language('common/main/main', 'tModalConfirm'); ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/Javascript">
$('ducument').ready(function() {
	$('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //code
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxPaseCodeDelInModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxPaseCodeDelInModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nCode == nCode){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxPaseCodeDelInModal();
            }
        }
        JSxShowButtonChoose();
	})
});
</script>