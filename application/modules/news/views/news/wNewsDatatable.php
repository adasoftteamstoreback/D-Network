<?php 
    if($aNewDataList['rtCode'] == '1'){
        $nCurrentPage = $aNewDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="otbNewDataList" class="table table-striped">
                <thead>
                    <tr>
                        <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaDelete'] == 1) :*/ ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmNewCheckDeleteAll" id="ocmNewCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php /*endif;*/ ?>
                        <th class="text-center xCNTextBold" style="width:15%;"><?= language('news/news/news','tNewSendBch')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('news/news/news','tNewTBCode')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('news/news/news','tNewDate')?></th>
                        <th class="text-center xCNTextBold" style="width:30%;"><?= language('news/news/news','tNewTBName')?></th>
                        <th class="text-center xCNTextBold" style="width:20%;"><?= language('news/news/news','tNewUserCreate')?></th>
                        <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaDelete'] == 1) :*/ ?>
                        <th class="text-center xCNTextBold" style="width:5%;"><?= language('news/news/news','tNewTBDelete')?></th>
                        <?php /*endif;*/ ?>
                        <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || ($aAlwEventNews['tAutStaEdit'] == 1 || $aAlwEventNews['tAutStaRead'] == 1)) : */ ?>
                        <th class="text-center xCNTextBold" style="width:5%;"><?= language('news/news/news','tNewTBEdit')?></th>
                        <?php /*endif;*/ ?>
                    </tr>                
                </thead>
                <tbody>
                    <?php if($aNewDataList['rtCode'] == 1 ):?>  
                        <?php foreach($aNewDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2 otrNew" id="otrotrNew<?=$nKey?>" data-code="<?=$aValue['rtNewCode']?>" data-name="<?=$aValue['rtNewName']?>">
                                <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaDelete'] == 1) :*/ ?>
                                    <td class="text-center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                            <span>&nbsp;</span>
                                        </label>
                                    </td>
                                <?php /*endif;*/ ?>
                                <td class="text-left"><?=$aValue['rtBchName'];?></td>
                                <td class="text-left"><?=$aValue['rtNewCode'];?></td>
                                <td class="text-left"><?=date('d/m/Y',strtotime($aValue['rtCreateOn']));?></td>
                                <td class="text-left"><?=$aValue['rtNewName']?></td>
                                <td class="text-left"><?=$aValue['rtUsrName']?></td>
                                <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaDelete'] == 1) :*/ ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoNewsDel('<?= $nCurrentPage?>','<?= $aValue['rtNewCode']?>','<?=$aValue['rtNewName'];?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                    </td>
                                <?php /*endif;*/ ?>
                                <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaDelete'] == 1) :*/ ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageNewsEdit('<?php echo $aValue['rtNewCode']?>')">
                                    </td>
                                <?php /*endif;*/ ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='99'><?= language('news/news/news','tNewNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aNewDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aNewDataList['rnCurrentPage']?> / <?=$aNewDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPageNews btn-toolbar pull-right"> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvNewsClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aNewDataList['rnAllPage'],$nPage+2)); $i++){?> 
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvNewsClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aNewDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvNewsClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelNew">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoNewsDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('ducument').ready(function(){
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#odvRGPList').children('tr').length;
	for($i=0; $i < nlength; $i++){
		var tDataCode = $('#otrPdtPbn'+$i).data('code')
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
			var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
			if(aReturnRepeat == 'Dupilcate'){
				$('#ocbListItem'+$i).prop('checked', true);
			}else{ }
		}
	}

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
            JSxTextinModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxTextinModal();
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
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
});

$('#ocmNewCheckDeleteAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
        }else{
            $('.ocbListItem').prop('checked',false);
        }
})
</script>