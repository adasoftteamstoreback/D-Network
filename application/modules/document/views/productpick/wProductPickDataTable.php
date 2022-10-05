<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage = $aDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>
<style>
    .xWCptActive {
        color: #007b00 !important;
        font-weight: bold;
        font-size: 10px;
        cursor: default;
    }

    .xWCptInActive {
        color: #7b7f7b !important;
        font-weight: bold;
        cursor: default;
        font-size: 10px;
    }

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
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="xCNCenter">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) { ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll ocbListItem" id="ocmCENCheckDeleteAll">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php } ?>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBBchCreate') ?></th>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBDocNo') ?></th>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBDocDate') ?></th>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'อ้างอิงใบสั่งงาน') ?></th>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'วันที่อ้างอิงใบสั่งงาน') ?></th>

                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBStaDoc') ?></th>
                        <!-- <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBStaPrc') ?></th> -->
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'tTBCreateBy') ?></th>
                        <th class="xCNTextBold"><?= language('document/transfer_branch_out/transfer_branch_out', 'ผู้อนุมัติ') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) { ?>
                            <th class="xCNTextBold" style="width:5%;"><?= language('common/main/main', 'tCMNActionDelete') ?></th>
                        <?php } ?>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) { ?>
                            <th class="xCNTextBold" style="width:5%;"><?= language('common/main/main', 'tCMNActionEdit') ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                    <?php if ($aDataList['rtCode'] == 1) { ?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) { ?>
                            <?php
                            $tDocNo = $aValue['FTXthDocNo'];
                            if ($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaApv'] == 2 || $aValue['FTXthStaDoc'] == 3) {
                                $CheckboxDisabled = "disabled";
                                $ClassDisabled = 'xCNDocDisabled';
                                $Title = language('document/document/document', 'tDOCMsgCanNotDel');
                                $Onclick = '';
                            } else {
                                $CheckboxDisabled = "";
                                $ClassDisabled = '';
                                $Title = '';
                                $Onclick = "onclick=JSxPCKDocDel('" . $nCurrentPage . "','" . $tDocNo . "')";
                            }

                            if ($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaApv'] == 2 || $aValue['FTXthStaDoc'] == 3) {
                                $tDisableTD     = "xWTdDisable";
                                $tDisableImg    = "xWImgDisable";
                                $tDisabledItem  = "disabled ";
                                $tDisabledItem2  = "xCNDisabled ";
                                $tDisabledcheckrow  = "true";
                            } else {
                                $tDisableTD     = "";
                                $tDisableImg    = "";
                                $tDisabledItem  = "";
                                $tDisabledItem2  = " ";
                                $tDisabledcheckrow  = "false";
                            }
                            //FTXthStaDoc
                            if ($aValue['FTXthStaDoc'] == 3) {
                                $tClassStaDoc = 'text-danger';
                                $tStaDoc = language('common/main/main', 'tStaDoc3');
                            } else if ($aValue['FTXthStaApv'] == 1) {
                                $tClassStaDoc = 'text-success';
                                $tStaDoc = language('common/main/main', 'tStaDoc1');
                            } else {
                                $tClassStaDoc = 'text-warning';
                                $tStaDoc = language('common/main/main', 'tStaDoc');
                            }

                            // if ($aValue['FTXthStaPrcStk'] == 1) {
                            //     $tClassPrcStk = 'text-success';
                            //     $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc1');
                            // } else if ($aValue['FTXthStaPrcStk'] == 2) {
                            //     $tClassPrcStk = 'text-warning';
                            //     $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc2');
                            // } else if ($aValue['FTXthStaPrcStk'] == 0 || $aValue['FTXthStaPrcStk'] == '') {
                            //     $tClassPrcStk = 'text-warning';
                            //     $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc3');
                            // }
                            $tClassPrcStk = 'text-success';
                            $tStaPrcDoc = language('common/main/main', 'tStaPrcDoc1');

                            ?>

                            <tr class="text-center xCNTextDetail2" id="otrPCKHD<?= $nKey ?>" data-code="<?= $aValue['FTXthDocNo'] ?>" data-name="<?= $aValue['FTXthDocNo'] ?>">
                                <?php
                                //รวมคอลัมน์
                                if ($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0) {
                                    $nRowspan   = '';
                                } else {
                                    $nRowspan   = "rowspan=" . $aValue['PARTITIONBYDOC'];
                                }
                                ?>
                                <?php if ($tKeepDocNo != $aValue['FTXthDocNo']) { ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap class="text-center" <?= $nRowspan ?>>
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?php echo $nKey ?>" <?php echo  $tDisabledItem; ?> type="checkbox" class="ocbListItem" name="ocbListItem[]" data-checkrow="<?php echo $tDisabledcheckrow; ?>" data-checkrowid="<?php echo $aValue['FTXthDocNo'] . $aValue['FTBchCode'] ?>" checked="false">
                                                <span class="<?php echo $tDisabledItem2 ?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?= $nRowspan ?>><?php echo (!empty($aValue['FTXthDocNo'])) ? $aValue['FTXthDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center" <?= $nRowspan ?>><?php echo (!empty($aValue['FDXthDocDate'])) ? date('d/m/Y', strtotime($aValue['FDXthDocDate'])) : '-' ?></td>
                                <?php } ?>
                                <td nowrap class="text-left"><?= ($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF'] ?></td>
                                <td nowrap class="text-center"><?= ($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF'] ?></td>

                                <?php if ($tKeepDocNo != $aValue['FTXthDocNo']) { ?>

                                    <!-- <td nowrap <?= $nRowspan ?> class="text-left"><?= $aValue['FTXthRefDocNo'] != '' ? $aValue['FTXthRefDocNo'] : '-' ?></td>
                                    <td nowrap <?= $nRowspan ?> class="text-center"><?= $aValue['FDXthRefDocDate'] != '' ? date('d/m/Y', strtotime($aValue['FDXthRefDocDate'])) : '-' ?></td> -->
                                    <td nowrap <?= $nRowspan ?> class="text-left"><label class="xCNTDTextStatus <?= $tClassStaDoc ?>"><?php echo $tStaDoc ?></label></td>
                                    <td nowrap <?= $nRowspan ?> class="text-left"><?= $aValue['FTCreateByName'] != '' ? $aValue['FTCreateByName'] : '-' ?></td>
                                    <td nowrap <?= $nRowspan ?> class="text-left"> <?php echo (!empty($aValue['FTXthApvName'])) ? $aValue['FTXthApvName'] : '-' ?></td>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) { ?>

                                        <td nowrap <?= $nRowspan ?> class="<?= $tDisableTD ?>" id="otdDel<?php echo $aValue['FTXthDocNo'] . $aValue['FTBchCode'] ?>">
                                            <img id="oimDel<?php echo $aValue['FTXthDocNo'] . $aValue['FTBchCode']; ?>" class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" <?= $Onclick ?> title="<?= $Title ?>">
                                        </td>
                                    <?php } ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) { ?>
                                        <td nowrap <?= $nRowspan ?>>
                                            <?php if ($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaDoc'] == 3) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/view2.png' ?>" onClick="JSvPCKCallPageEdit('<?php echo $aValue['FTXthDocNo'] ?>')">
                                            <?php } else { ?>
                                                <img class="xCNIconTable" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvPCKCallPageEdit('<?php echo $aValue['FTXthDocNo'] ?>')">
                                            <?php } ?>
                                            <!-- <img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/edit.png') ?>" onClick="JSvPCKCallPageEdit('<?= $aValue['FTXthDocNo'] ?>')"> -->
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXthDocNo']; ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main', 'tResultTotalRecord') ?> <?= $aDataList['rnAllRow'] ?> <?= language('common/main/main', 'tRecord') ?> <?= language('common/main/main', 'tCurrentPage') ?> <?= $aDataList['rnCurrentPage'] ?> / <?= $aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPage btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvPCKDataTableClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvPCKDataTableClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>
            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvPCKDataTableClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospConfirmDelete"> - </span>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirm" onClick="JSxPCKDelChoose()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">
                    <?= language('common/main/main', 'tModalConfirm') ?>
                </button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('ducument').ready(function() {
        localStorage.removeItem("LocalItemData");
        JSxShowButtonChoose();
        $('.ocbListItem').prop('checked', false);
        // var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        // var nlength = $('#odvRGPList').children('tr').length;
        // for($i=0; $i < nlength; $i++){
        // 	var tDataCode = $('#otrCreditcard'+$i).data('code')
        // 	if(aArrayConvert == null || aArrayConvert == ''){
        // 	}else{
        // 		var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
        // 		// if(aReturnRepeat == 'Dupilcate'){
        // 		// 	$('#ocbListItem'+$i).prop('checked', true);
        // 		// }else{ }
        // 	}
        //   }
        //  JSxPaseCodeDelInModal();// เอารายการที่เลือกไว้มาเก็บไว้ในmodal ให้เรียบร้อยเผื่อ user สั่งลบรายการ
    })
    $('.ocbListItem').click(function() {
        //console.log('asdasdadadads');
        var nCode = $(this).parent().parent().parent().data('code'); // code
        var tName = $(this).parent().parent().parent().data('name'); // code
        $(this).prop('checked', true);
        var LocalPCKHDItemData = localStorage.getItem("LocalPCKHDItemData");
        var obj = [];
        if (LocalPCKHDItemData) {
            obj = JSON.parse(LocalPCKHDItemData);
        } else {}
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalPCKHDItemData"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            obj.push({
                "nCode": nCode,
                "tName": tName
            });
            localStorage.setItem("LocalPCKHDItemData", JSON.stringify(obj));
            JSxTextinModal();
        } else {
            var aReturnRepeat = findObjectByKey(aArrayConvert[0], 'nCode', nCode);
            if (aReturnRepeat == 'None') { // ยังไม่ถูกเลือก
                obj.push({
                    "nCode": nCode,
                    "tName": tName
                });
                localStorage.setItem("LocalPCKHDItemData", JSON.stringify(obj));
                JSxTextinModal();
            } else if (aReturnRepeat == 'Dupilcate') { // เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalPCKHDItemData");
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
                localStorage.setItem("LocalPCKHDItemData", JSON.stringify(aNewarraydata));
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
</script>