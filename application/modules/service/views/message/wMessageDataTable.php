<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage = $aDataList['rnCurrentPage'];
} else {
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
</style>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage; ?>">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/message/message', 'tMSGCode') ?></th>
                        <th nowrap class="xCNTextBold" style="width:50%;text-align:center;"><?= language('service/message/message', 'tMSGName') ?></th>
                        <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/message/message', 'tMSGTBDelete') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/message/message', 'tMSGTBEdit') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvCLDList">
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) {  ?>
                            <tr class="text-center xCNTextDetail2 otrMessage" id="otrMessage<?= $key ?>" data-code="<?= $aValue['rtMshCode'] ?>" data-name="<?= $aValue['rtMshName'] ?>">
                                <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
                                  <?php

                                      if($aValue['rtMshCodeLeft'] != ''){
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
                                            <input id="ocbListItem<?= $key ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]"
                                             checked="false"
                                             <?php echo $tDisabledItem; ?>
                                            data-checkrow="<?php echo $tDisabledcheckrow; ?>"
                                            data-checkrowid="<?php echo $aValue['rtMshCode']?>"
                                            onchange="JSxCalendarVisibledDelAllBtn(this, event)">
                                            <span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?= $aValue['rtMshCode'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtMshName'] ?></td>
                                <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>

                                    <td class="<?=$tDisableTD?>" id="otdDel<?php echo $aValue['rtMshCode']?>">
                                        <img  class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>"
                                         id="oimDel<?php echo $aValue['rtMshCode']; ?>"
                                        onClick="JSnMessageDel('<?php echo $nCurrentPage ?>','<?= $aValue['rtMshName'] ?>','<?= $aValue['rtMshCode'] ?>','<?= language('common/main/main', 'tModalConfirmDeleteItemsYN') ?>')"
                                        title="<?php echo language('service/message/message', 'tMSGTBDelete'); ?>">
                                    </td>
                                <?php endif; ?>
                                <?php if ($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvCallPageMessageEdit('<?php echo $aValue['rtMshCode']; ?>')" title="<?php echo language('service/message/message', 'tMSGTBEdit'); ?>">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='6'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aDataList['rnCurrentPage'] ?> / <?= $aDataList['rnAllPage'] ?></p>
    </div>
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageCalendarGrp btn-toolbar pull-right">
            <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ -->
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ -->
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <button onclick="JSvClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>
            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelMessage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospConfirmDelete" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnMessageDelChoose('<?= $nCurrentPage ?>')">
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
$('ducument').ready(function(){
    JSxShowButtonChoose();
    console.log('asdasd');
  $('.ocbListItem').prop('checked',false);
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#otrMessage').children('tr').length;
	for($i=0; $i < nlength; $i++){
		var tDataCode = $('#otrMessage'+$i).data('code')
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
			var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
			// if(aReturnRepeat == 'Dupilcate'){
			// 	$('#ocbListItem'+$i).prop('checked', true);
			// }else{ }
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
