<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?=language('company/warehouse/warehouse','ลำดับของคลัง')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('company/warehouse/warehouse','ประเภทของคลัง')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('company/warehouse/warehouse','tWahCode')?></th>
						<th nowrap class="xCNTextBold" style="width:20%;text-align:center;"><?=language('company/warehouse/warehouse','tWahName')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('common/main/main','tCMNActionDelete')?></th>
						<?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaEdit'] == 1 || $aAlwEvent['tAutStaRead'] == 1))  : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?=language('common/main/main','tCMNActionEdit')?></th>
                        <?php endif; ?>
                    </tr>
				</thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTWahCode']?>" data-bchcode='<?=$aValue['FTBchCode']?>' data-name="<?=$aValue['FTWahName']?>">
                                <td class="text-center"><?=$aValue['FNBchOptSeqNo']?></td>
                                <td class="text-left"><?= $aValue['FTObjName']?></td>
                                <td class="text-left"><?=$aValue['FTWahCode']?></td>
                                <td class="text-left"><?=$aValue['FTWahName']?></td>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <img class="xCNIconTable xCNIconDelete" onClick="JSxBranchSetWahDelete('<?=$aValue['FNBchOptSeqNo']?>','<?=$aValue['FTBchCode']?>','<?=$aValue['FTWahCode']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                    </td>
                                <?php endif; ?>
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaEdit'] == 1 || $aAlwEvent['tAutStaRead'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable xCNIconEdit" onClick="JSxBranchSetWahPageEdit('<?=$aValue['FNBchOptSeqNo']?>','<?=$aValue['FTWahCode']?>')">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='99'><?=language('common/main/main', 'tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
			</table>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-md-6">
		<p><?= language('common/main/main','tResultTotalRecord')?> <?= $aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPageWah btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?> 
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelBranchWah">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery">
        			<?= language('common/main/main', 'tModalConfirm')?>
				</button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
        			<?= language('common/main/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    //ลบข้อมูล
    function JSxBranchSetWahDelete(nSeq, tBchCode, tWahCode, tYesOnNo){
        $('#odvModalDelBranchWah').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + nSeq + ' ( ' + tWahCode + ' ) ' + tYesOnNo);
        $('#osmConfirm').on('click', function (evt) {

            $.ajax({
                type    : "POST",
                url     : "branchSettingWahouseEventDelete",
                data    : { 'nSeq': nSeq , 'tBchCode': tBchCode , 'tWahCode' : tWahCode },
                cache   : false,
                success : function (oResult) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == '1') {
                        $('#odvModalDelBranchWah').modal('hide');
                        $('#ospConfirmDelete').empty();
                        localStorage.removeItem('LocalItemData');
                        setTimeout(function () {
                            JSvCallPageBranchSetWah()
                        }, 500);
                    } else {
                        JCNxCloseLoading();
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });

        });
    }
</script>
