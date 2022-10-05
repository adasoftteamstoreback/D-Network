<?php 
    if($aLotDataList['tCode'] == '1'){
        $nCurrentPage = $aLotDataList['nCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<style>
    .xWTdDisable {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }

    .xWImgDisable {
        pointer-events: none;
    }

    .table > tbody > tr > td > i {
        display : block;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="otbLotDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>   
                        <?php endif; ?>
                        <th class="text-center xCNTextBold" style="width:15%;"><?= language('service/pdtlot/pdtlot','tLOTCode')?></th>
                        <th class="text-center xCNTextBold" style="width:35%;"><?= language('service/pdtlot/pdtlot','tLOTLotBatchNo')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('service/pdtlot/pdtlot','tLOTYear')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('service/pdtlot/pdtlot','tLOTStatus')?></th>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('service/pdtlot/pdtlot','tLOTDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || ($aAlwEventPdtLot['tAutStaEdit'] == 1 || $aAlwEventPdtLot['tAutStaRead'] == 1))  : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('service/pdtlot/pdtlot','tLOTEdit')?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aLotDataList['tCode'] == 1 ):?>
                        <?php foreach($aLotDataList['aItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2 otrPdtLot" id="otrPdtLot<?=$nKey?>" data-code="<?=$aValue['FTLotNo']?>">
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                                    <?php

                                      if($aValue['PLOT'] != ''){
                                            $tDisableTD     = "xWTdDisable";
                                            $tDisableImg    = "xWImgDisable";
                                            $tDisabledItem  = "disabled ";
                                            $tDisabledItem2  = "xCNDisabled ";
                                            $tDisabledcheckrow  = "true";
                                      }else{
                                            $tDisableTD     = "";
                                            $tDisableImg    = "";
                                            $tDisabledItem  = "";
                                            $tDisabledItem2  = " ";
                                            $tDisabledcheckrow  = "false";
                                        }
                                    ?>

                                <td class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?php echo $nKey; ?>" type="checkbox"
                                        <?php echo $tDisabledItem; ?>
                                        data-checkrow="<?php echo $tDisabledcheckrow; ?>"
                                        data-checkrowid="<?php echo $aValue['FTLotNo'].$aValue['FTLotBatchNo']?>" checked="false"  class="ocbListItem" name="ocbListItem[]">
                                        <span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
                                    </label>
                                </td>

                                <?php endif; ?>
                                <td class="text-left"><?=$aValue['FTLotNo']?></td>
                                <td class="text-left"><?=$aValue['FTLotBatchNo']?></td>
                                <td class="text-center"><?=$aValue['FTLotYear']?></td>
                                    <?php if($aValue['FTLotStaUse'] == '1') : ?>
                                        <td class="text-center"><?= language('service/pdtlot/pdtlot','tLOTStaUse')?></td>
                                    <?php else: ?>
                                        <td class="text-center"><?= language('service/pdtlot/pdtlot','tLOTStaNoUse')?></td>
                                    <?php endif; ?>
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaDelete'] == 1) : ?>
                                <td class="<?=$tDisableTD?>">
                                    <img class="xCNIconTable <?php echo $tDisableImg; ?>" id="oimDel<?php echo $aValue['FTLotNo'].$aValue['FTLotBatchNo']; ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtLotDel('<?=$nCurrentPage?>','<?php echo $aValue['FTLotNo']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                </td>
                                <?php endif; ?>
                                <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || ($aAlwEventPdtLot['tAutStaEdit'] == 1 || $aAlwEventPdtLot['tAutStaRead'] == 1)) : ?>
                                <td>
                                    <!-- เปลี่ยน -->
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPagePdtLotEdit('<?php echo $aValue['FTLotNo']?>')">
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('common/main/main','tMainRptNotFoundDataInDB')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aLotDataList['nAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aLotDataList['nCurrentPage']?> / <?=$aLotDataList['nAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPagePdtLot btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPdtLotClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aLotDataList['nAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
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
                <button onclick="JSvPdtLotClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aLotDataList['nAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPdtLotClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>


<div class="modal fade" id="odvModalDelPdtLot">
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
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoPdtLotDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('document').ready(function(){
    $('.ocbListItem').prop('checked',false);
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#odvRGPList').children('tr').length;
	for($i=0; $i < nlength; $i++){
		var tDataCode = $('#otrPdtLot'+$i).data('code')
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
			var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
			if(aReturnRepeat == 'Dupilcate'){
				$('#ocbListItem'+$i).prop('checked', true);
			}else{ }
		}
	}
});

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
</script>