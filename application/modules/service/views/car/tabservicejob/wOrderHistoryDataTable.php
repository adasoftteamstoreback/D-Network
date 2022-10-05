<?php 
    $nCurrentPage = '1';
?>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?=$nCurrentPage?>">
        <div class="table-responsive">
            <table id="otbCldUserDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHAgency')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHBranch')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHDocNo')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHDocDate')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHPos')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHCst')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHCarRegNo')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHStatusDoc')?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/car/car','tCAROHInfor')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aCarOrderHistoryDataList['rtCode'] == 1 ):?>
                        <?php foreach($aCarOrderHistoryDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2" id="otrOrderHistory<?=$nKey?>">
                                <td nowarp class="text-left"><?=$aValue['rtAgnName']?></td>
                                <td nowarp class="text-left"><?=$aValue['rtBchName']?></td>
                                <td nowarp class="text-left"><?=$aValue['rtXshDocNo']?></td>
                                <td nowarp class="text-center"><?=$aValue['rtXshDocDate']?></td>
                                <td nowarp class="text-left"><?=$aValue['rtSpsName']?></td>
                                <td nowarp class="text-left"><?=$aValue['rtCstName']?></td>
                                <td nowarp class="text-left"><?=$aValue['rtCarRegNo']?></td>
                                <td nowarp class="text-center"><?=$aValue['rtXshStaClosed']?></td>
                                <td nowrap>
                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvCarCallOrderDetail('<?= $aValue['rtXshDocNo'] ?>','<?= $aValue['rtBchCode'] ?>','<?= $aValue['rtAgnCode'] ?>'),'1'">
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='10'><?= language('service/car/car','tCARNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>
<div class="modal fade" id="odvModalDelUserCalendar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
                <input type='hidden' id="ohdConfirmBchDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoUserCalendarDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $('ducument').ready(function(){
        JSxUserCalendarShowButtonChoose();
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
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
                    JSxUserCalendarPaseCodeDelInModal();
            }else{
                var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxUserCalendarPaseCodeDelInModal();
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
                    JSxUserCalendarPaseCodeDelInModal();
                }
            }
            JSxUserCalendarShowButtonChoose();
        });
    });
</script>