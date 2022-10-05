<?php
if ($aQagDetailDataList['rtCode'] == '1') {
    $nCurrentPage = $aQagDetailDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}

?>


<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage ?>">
        <div class="table-responsive">
            <table id="otbQahDetailDataList" class="table table-striped">
                <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/question/question', 'tQAHTBChoose') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/question/question', 'tQAHCount') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/question/question', 'tQAHQuestion') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/question/question', 'tQAHOptionAnwser') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/question/question', 'tQAHSelectAnwType') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/question/question', 'tQAHStatus') ?></th>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/question/question', 'tQAHTBDelete') ?></th>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/question/question', 'tQAHTBEdit') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aQagDetailDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aQagDetailDataList['raItems'] as $nKey => $aValue) : ?>
                            <tr class="text-center xCNTextDetail2 otrQuestionDetail" id="otrQuestionDetail<?= $nKey ?>" data-code="<?= $aValue['rtQadSeqNo'] ?>" data-name="<?= $aValue['rtQadName'] ?>">
                                <td nowarp class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                        <span>&nbsp;</span>
                                    </label>
                                </td>
                                <td nowarp class="text-center"><?= $nKey + 1 ?></td>
                                <td nowarp class="text-left"><?= $aValue['rtQadName'] ?></td>
                                <?php $tAnwsweOption = ""; 
                                ?>
                                <?php foreach ($aQagDetailDataList['raItems2'] as $nKey2 => $aValue2) { ?>
                                <?php if ($aValue2['rtQadSeqNo'] == $aValue['rtQadSeqNo']) { ?>
                                        <?php 
                                            $tAnwsweOption .=$aValue2['rtQasResuitName'].','  
                                        ?>
                                <?php } ?>
                                <?php } ?>
                                <td nowarp class="text-left">
                                    <?php echo substr($tAnwsweOption,0,-1) ?>
                                </td>
                                <td nowarp class="text-left"><?= language('service/question/question', 'tQAHSelectType'.$aValue['rtQadType']) ?></td>
                                <td nowarp class="text-left"><?= language('service/question/question', 'tQAHStatus'.$aValue['rtQadStaUse']) ?></td>
                                <td nowarp>
                                    <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onClick="JSoQuestionDetailDel('<?= $nCurrentPage ?>','<?= $aValue['rtQadName'] ?>','<?= $aValue['rtQahDocNo'] ?>','<?= language('common/main/main', 'tModalConfirmDeleteItemsYN') ?>','<?= $aValue['rtQadSeqNo'] ?>')">
                                </td>
                                <td nowarp>
                                    <!-- <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>"> -->
                                    <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvCallPageQuestionDetailEdit('<?php echo $aValue['rtQadSeqNo'] ?>','<?php echo $aValue['rtQadType'] ?>')">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='8'><?= language('service/question/question', 'tQAHNoData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aQagDetailDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aQagDetailDataList['rnCurrentPage'] ?> / <?= $aQagDetailDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageQuestionDetail btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabled = 'disabled';
            } else {
                $tDisabled = '-';
            } ?>
            <button onclick="JSvQuestionDetailClickPage('previous')" class="btn btn-white btn-sm" <?= $tDisabled ?>><i class="fa fa-chevron-left f-s-14 t-plus-1"></i></button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aQagDetailDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvQuestionDetailClickPage('<?= $i ?>')" type="button" class="btn xCNBTNNumPagenation <?= $tActive ?>" <?= $tDisPageNumber ?>><?= $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aQagDetailDataList['rnAllPage']) {
                $tDisabled = 'disabled';
            } else {
                $tDisabled = '-';
            } ?>
            <button onclick="JSvQuestionDetailClickPage('next')" class="btn btn-white btn-sm" <?= $tDisabled ?>><i class="fa fa-chevron-right f-s-14 t-plus-1"></i></button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelQuestionDetail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospConfirmDelete"> - </span>
                <input type='hidden' id="ohdConfirmIDDelete">
                <input type='hidden' id="ohdConfirmBchDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoQuestionDetailDelChoose('<?= $nCurrentPage ?>')"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('document').ready(function() {
        JSxQuestionDetailShowButtonChoose();
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        $('.ocbListItem').click(function() {
            var nCode = $(this).parent().parent().parent().data('code'); //code
            var tName = $(this).parent().parent().parent().data('name'); //code
            $(this).prop('checked', true);
            var LocalItemData = localStorage.getItem("LocalItemData");
            var obj = [];
            if (LocalItemData) {
                obj = JSON.parse(LocalItemData);
            } else {}
            var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
            if (aArrayConvert == '' || aArrayConvert == null) {
                obj.push({
                    "nCode": nCode,
                    "tName": tName
                });
                localStorage.setItem("LocalItemData", JSON.stringify(obj));
                JSxQuestionDetailPaseCodeDelInModal();
            } else {
                var aReturnRepeat = findObjectByKey(aArrayConvert[0], 'nCode', nCode);
                if (aReturnRepeat == 'None') { //ยังไม่ถูกเลือก
                    obj.push({
                        "nCode": nCode,
                        "tName": tName
                    });
                    localStorage.setItem("LocalItemData", JSON.stringify(obj));
                    JSxQuestionDetailPaseCodeDelInModal();
                } else if (aReturnRepeat == 'Dupilcate') { //เคยเลือกไว้แล้ว
                    localStorage.removeItem("LocalItemData");
                    $(this).prop('checked', false);
                    var nLength = aArrayConvert[0].length;
                    for ($i = 0; $i < nLength; $i++) {
                        if (aArrayConvert[0][$i].nCode == nCode) {
                            delete aArrayConvert[0][$i];
                        }
                    }
                    var aNewarraydata = [];
                    for ($i = 0; $i < nLength; $i++) {
                        if (aArrayConvert[0][$i] != undefined) {
                            aNewarraydata.push(aArrayConvert[0][$i]);
                        }
                    }
                    localStorage.setItem("LocalItemData", JSON.stringify(aNewarraydata));
                    JSxQuestionDetailPaseCodeDelInModal();
                }
            }
            JSxQuestionDetailShowButtonChoose();
        })
    });
</script>