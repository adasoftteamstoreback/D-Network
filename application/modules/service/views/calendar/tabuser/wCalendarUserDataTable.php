<?php
if ($aCldUserDataList['rtCode'] == '1') {
    $nCurrentPage = $aCldUserDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>


<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage ?>">
        <div class="table-responsive">
            <table id="otbCldUserDataList" class="table table-striped">
                <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/calendar/calendar', 'tCLDTBChoose') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/calendar/calendar', 'tCLDCode') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/calendar/calendar', 'tCLDUserCode') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/calendar/calendar', 'tCLDUserName') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/calendar/calendar', 'tCLDStartDate') ?></th>
                        <th nowarp class="text-center xCNTextBold" style=""><?= language('service/calendar/calendar', 'tCLDFinishDate') ?></th>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/calendar/calendar', 'tCLDTBDelete') ?></th>
                        <th nowarp class="text-center xCNTextBold" style="width:10%;"><?= language('service/calendar/calendar', 'tCLDTBEdit') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aCldUserDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aCldUserDataList['raItems'] as $nKey => $aValue) : ?>
                            <tr class="text-center xCNTextDetail2 otrUserCalendar" id="otrUserCalendar<?= $nKey ?>" data-code="<?= $aValue['rtUsrCode'] ?>" data-name="<?= $aValue['rtObjCode'] ?>">
                                <td nowarp class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                        <span>&nbsp;</span>
                                    </label>
                                </td>
                                <td nowarp class="text-left"><?= $aValue['rtObjCode'] ?></td>
                                <td nowarp class="text-left"><?= $aValue['rtUsrCode'] ?></td>
                                <td nowarp class="text-left"><?= $aValue['FTUsrName'] ?></td>
                                <?php if ($aValue['rtObjDutyStart'] != '') { ?>
                                    <td nowarp class="text-center"><?php echo date("Y-m-d", strtotime($aValue['rtObjDutyStart'])); ?></td>
                                <?php } else { ?>
                                    <td nowarp class="text-center"> - </td>
                                <?php }
                                if ($aValue['rtObjDutyFinish'] != '') { ?>
                                    <td nowarp class="text-center"><?php echo date("Y-m-d", strtotime($aValue['rtObjDutyFinish'])); ?></td>
                                <?php } else { ?>
                                    <td nowarp class="text-center"> - </td>
                                <?php } ?>
                                <td nowarp>
                                    <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" onClick="JSoUserCalendarDel('<?= $nCurrentPage ?>','<?= $aValue['FTUsrName'] ?>','<?= $aValue['rtUsrCode'] ?>','<?= language('common/main/main', 'tModalConfirmDeleteItemsYN') ?>','<?= $aValue['rtObjCode'] ?>')">
                                </td>
                                <td nowarp>
                                    <!-- <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>"> -->
                                    <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvCallPageUserCalendarEdit('<?php echo $aValue['rtUsrCode'] ?>')">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='8'><?= language('service/calendar/calendar', 'tCLDNoData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aCldUserDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aCldUserDataList['rnCurrentPage'] ?> / <?= $aCldUserDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageUserCalendar btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabled = 'disabled';
            } else {
                $tDisabled = '-';
            } ?>
            <button onclick="JSvUserCalendarClickPage('previous')" class="btn btn-white btn-sm" <?= $tDisabled ?>><i class="fa fa-chevron-left f-s-14 t-plus-1"></i></button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aCldUserDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvUserCalendarClickPage('<?= $i ?>')" type="button" class="btn xCNBTNNumPagenation <?= $tActive ?>" <?= $tDisPageNumber ?>><?= $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aCldUserDataList['rnAllPage']) {
                $tDisabled = 'disabled';
            } else {
                $tDisabled = '-';
            } ?>
            <button onclick="JSvUserCalendarClickPage('next')" class="btn btn-white btn-sm" <?= $tDisabled ?>><i class="fa fa-chevron-right f-s-14 t-plus-1"></i></button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelUserCalendar">
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
                <button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoUserCalendarDelChoose('<?= $nCurrentPage ?>')"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('ducument').ready(function() {
        JSxUserCalendarShowButtonChoose();
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
                JSxUserCalendarPaseCodeDelInModal();
            } else {
                var aReturnRepeat = findObjectByKey(aArrayConvert[0], 'nCode', nCode);
                if (aReturnRepeat == 'None') { //ยังไม่ถูกเลือก
                    obj.push({
                        "nCode": nCode,
                        "tName": tName
                    });
                    localStorage.setItem("LocalItemData", JSON.stringify(obj));
                    JSxUserCalendarPaseCodeDelInModal();
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
                    JSxUserCalendarPaseCodeDelInModal();
                }
            }
            JSxUserCalendarShowButtonChoose();
        })
    });
</script>