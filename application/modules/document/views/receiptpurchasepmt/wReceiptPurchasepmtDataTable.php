<?php
    if ($aDataList['rtCode'] == '1') {
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    } else {
        $nCurrentPage   = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBAgnCreate') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBBch') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBDocNo') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBDocDate') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBSpl') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBDateRC') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBStaDoc') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBCreateBy') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?= language('common/main/main', 'tCMNActionDelete') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?= language('common/main/main', 'tCMNActionEdit') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRPPList">
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php if (FCNnHSizeOf($aDataList['raItems']) != 0): ?>
                            <?php foreach ($aDataList['raItems'] as $nKey => $aValue): ?>
                                <?php
                                    $tDocNo       = $aValue['FTXshDocNo'];
                                    $tBchCode     = $aValue['FTBchCode'];

                                    if($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaDoc'] == 3){
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = 'xCNDocDisabled';
                                        $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                        $tOnclick           = '';
                                    }else{
                                        $tCheckboxDisabled  = "";
                                        $tClassDisabled     = '';
                                        $tTitle             = '';
                                        $tOnclick           = "onclick=JSoRPPDelDocSingle('".$nCurrentPage."','".$tDocNo."','".$tBchCode."')";
                                    }

                                    if($aValue['FTXshStaDoc'] == 3){
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'tStaDoc3');
                                    }else if(!empty($aValue['FTXshStaApv'])){
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1'); 
                                    }else{
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }
                                ?>
                                <tr class="text-center xCNTextDetail2" id="otrRPP<?= $nKey ?>" data-code="<?= $tDocNo ?>" data-name="<?= $tDocNo ?>">
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?= $tDocNo ?>" data-bchcode="<?= $tBchCode ?>" <?= $tCheckboxDisabled; ?>>
                                                <span class="<?= $tClassDisabled ?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td nowrap class="text-left"><?= (!empty($aValue['FTAgnName'])) ? $aValue['FTAgnName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?= (!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?= (!empty($aValue['FTXshDocNo'])) ? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center"><?= (!empty($aValue['FDXshDocDate'])) ? $aValue['FDXshDocDate'] : '-' ?></td>
                                    <td nowrap class="text-left"><?= (!empty($aValue['FTSplName'])) ? $aValue['FTSplName'] : '-' ?></td>
                                    <td nowrap class="text-center"><?= (!empty($aValue['FDXshDueDate'])) ? $aValue['FDXshDueDate'] : '-' ?></td>
                                    <td nowrap class="text-left">
                                        <label class="<?= $tClassStaDoc; ?>"><?php echo $tStaDoc;?></label>
                                    </td>
                                    <td nowrap class="text-left"><?= (!empty($aValue['FTCreateByName'])) ? $aValue['FTCreateByName'] : '-' ?></td>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap>
                                            <img class="xCNIconTable xCNIconDel <?= $tClassDisabled ?>" src="<?= base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" <?= $tOnclick ?> title="<?= $tTitle ?>">
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap>
                                            <?php if ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaDoc'] == 3) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvRPPCallPageEdit('<?= $tDocNo ?>')">
                                            <?php } else { ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvRPPCallPageEdit('<?= $tDocNo ?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aDataList['rnCurrentPage'] ?> / <?= $aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageRPPPdt btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvRPPClickPageList('previous')" class="btn btn-white btn-sm" <?= $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvRPPClickPageList('<?= $i ?>')" type="button" class="btn xCNBTNNumPagenation <?= $tActive ?>" <?= $tDisPageNumber ?>><?= $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvRPPClickPageList('next')" class="btn btn-white btn-sm" <?= $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvRPPModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmRPPConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvRPPModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //ลบหลายตัว
    $('.ocbListItem').unbind().click(function() {
        var nCode = $(this).parent().parent().parent().data('code'); //code
        var tName = $(this).parent().parent().parent().data('name'); //name
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
            JSxTextinModal();
        } else {
            var aReturnRepeat = JStRPPFindObjectByKey(aArrayConvert[0], 'nCode', nCode);
            if (aReturnRepeat == 'None') { //ยังไม่ถูกเลือก
                obj.push({
                    "nCode": nCode,
                    "tName": tName
                });
                localStorage.setItem("LocalItemData", JSON.stringify(obj));
                JSxTextinModal();
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
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    });

    $('#odvRPPModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSoRPPDelDocMultiple();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
</script>
