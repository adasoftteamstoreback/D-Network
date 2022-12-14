<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage = $aDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>

<?php
// echo '<pre>';
// echo print_r($aAlwEventQuestion); 
// echo '</pre>';
?>

<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage; ?>">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('service/question/question', 'tQAHQasGroupCode') ?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?= language('service/question/question', 'tQAHQasGroupName') ?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?= language('service/question/question', 'tQAHQasGroup') ?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?= language('service/question/question', 'tQAHQasSubGroup') ?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?= language('service/question/question', 'tQAHStartDate') ?></th>
                        <th nowrap class="xCNTextBold" style="text-align:center;"><?= language('service/question/question', 'tQAHFinishDate') ?></th>
                        <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('service/question/question', 'tQAHTBDelete') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('service/question/question', 'tQAHTBEdit') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('service/question/question', 'tQAHFShowPreview') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvQAHList">
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) {  ?>
                            <tr class="text-center xCNTextDetail2 otrQuestion" id="otrQuestion<?= $key ?>" data-code="<?= $aValue['rtQahDocNo'] ?>" data-name="<?= $aValue['rtQahName'] ?>">
                                <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                                    <td class="text-center">
                                        <label class="fancy-checkbox">
                                            <input id="ocbListItem<?= $key ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onchange="JSxQuestionVisibledDelAllBtn(this, event)" <?php if ($aValue['rtQadSeqNo'] > 0) {
                                                                echo "disabled";
                                                            } ?>>
                                            <span class="<?php if ($aValue['rtQadSeqNo'] > 0) {
                                                                echo "xCNDisabled";
                                                            } ?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>
                                <td nowrap class="text-left"><?= $aValue['rtQahDocNo'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtQahName'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtQgpName'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['rtQsgName'] ?></td>
                                <?php if ($aValue['rtQahDateStart'] != '') { ?>
                                    <td nowarp class="text-center"><?php echo date("d/m/Y", strtotime($aValue['rtQahDateStart'])); ?></td>
                                <?php } else { ?>
                                    <td nowarp class="text-center"> - </td>
                                <?php }
                                if ($aValue['rtQahDateStop'] != '') { ?>
                                    <td nowarp class="text-center"><?php echo date("d/m/Y", strtotime($aValue['rtQahDateStop'])); ?></td>
                                <?php } else { ?>
                                    <td nowarp class="text-center"> - </td>
                                <?php } ?>
                                <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable xCNIconDel <?php if ($aValue['rtQadSeqNo'] > 0) {
                                                                                echo "xCNDocDisabled";
                                                                            } ?>" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onClick="JSnQuestionDel('<?php echo $nCurrentPage ?>','<?= $aValue['rtQahName'] ?>','<?= $aValue['rtQahDocNo'] ?>','<?= language('common/main/main', 'tModalConfirmDeleteItemsYN') ?>')" title="<?php echo language('service/question/question', 'tQAHTBDelete'); ?>">
                                    </td>
                                <?php endif; ?>
                                <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvCallPageQuestionEdit('<?php echo $aValue['rtQahDocNo']; ?>')" title="<?php echo language('service/question/question', 'tQAHTBEdit'); ?>">
                                    </td>
                                <?php endif; ?>
                                <?php if ($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
                                    <td>
                                        <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/view2.png' ?>" onClick="JSvCallPreviewQuestion('<?php echo $aValue['rtQahDocNo']; ?>')" title="<?php echo language('service/question/question', 'tQAHTBEdit'); ?>">
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
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aDataList['rnCurrentPage'] ?> / <?= $aDataList['rnAllPage'] ?></p>
    </div>
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <div class="xWPageQuestionGrp btn-toolbar pull-right">
            <!-- ????????????????????????????????? Class ?????????????????????????????????????????????????????? -->
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <!-- ????????????????????????????????? Parameter Loop ?????????????????????????????????????????????????????? -->
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <button onclick="JSvClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>
            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelQuestion">
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
                <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnQuestionDelChoose('<?= $nCurrentPage ?>')">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ===================================================== Modal Product SV Detail ============================================================= -->
<div id="odvModalPreviewQuestion" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">????????????????????????????????????</label>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 180px);overflow-y: auto;">
                <div id="odvPreview">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ====================================================== End Modal Delete Product Set ======================================================= -->

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
            if(aReturnRepeat == 'None' ){           //??????????????????????????????????????????
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxPaseCodeDelInModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//?????????????????????????????????????????????
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