<div class="xCNtableFixHead">
    <table class="table xRefIntTable">
        <thead>
            <tr>
                <th class="xRefIntTh text-center" width="5%">
                    <label class="fancy-checkbox ">
                        <input id="ocbRefIntDocDTAll" type="checkbox" class="ocbRefIntDocDTAll" name="ocbListItem[]" checked>
                        <span class="">&nbsp;</span>
                    </label>
                </th>
                <th class="xRefIntTh text-center"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBNo'); ?></th>
                <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTable_pdtcode'); ?></th>
                <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTable_pdtname'); ?></th>
                <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTable_barcode'); ?></th>
                <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTable_unit'); ?></th>
                <th class="xRefIntTh text-right" width="5%"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTable_qty'); ?></th>
                <th class="xRefIntTh text-right" width="5%"><?php echo language('document/purchaseorder/purchaseorder', 'จำนวนหยิบแล้ว'); ?></th>
                <th class="xRefIntTh text-right" width="5%"><?php echo language('document/purchaseorder/purchaseorder', 'จำนวนค้างหยิบ'); ?></th>

            </tr>
        </thead>
        <tbody>

            <?php if ($aDataList['rtCode'] == 1) : ?>
                <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                    <?php
                    // $nXsdQty = 0;
                    // if ($aValue['FTPdtCode'] == @$aDataParamRefPCK['raItems'][$nKey]['FTPdtCode']) {
                    //     $nXsdQty = $aValue['FCXsdQty'] - @$aDataParamRefPCK['raItems'][$nKey]['FCXtdQtyOrd'];
                    // } else {
                    // $nXsdQty = $aValue['FCXsdQty'];
                    // $nXsdQtyC = $aValue['FCXsdQty'] - $aValue['FCXsdQtyL'];
                    // $nXsdQtyL = $aValue['FCXsdQtyL'];
                    $nXsdQty = $aValue['FCXsdQtyAll'];
                    $nXsdQtyC = $aValue['QTYUSE'];
                    $nXsdQtyL = $aValue['FCXtdQtyLeft'];



                    // }
                    if ($aValue['FTPdtStaSet'] == 2 || $aValue['FTPdtStaSet'] == 5 || $nXsdQtyL < 1) {
                        $tClassDisChk = 'xCNDisabled';
                        $tDisabledChk  = "disabled ";
                        $tDisabledcheckrow  = "true";
                        $tCheckDisRefDoc = '';
                    } else {
                        $tClassDisChk = 'xWUNDisabled';
                        $tDisabledChk  = "";
                        $tDisabledcheckrow  = "false";
                        $tCheckDisRefDoc = 'checked';
                    }

                    if ($aValue['FTPdtStaSet'] != '') {
                        $tSpacSet = '';
                    } else {
                        $tSpacSet = '&nbsp;&nbsp;&nbsp;&nbsp;';
                    }

                    if ($aValue['FTPdtStaSet'] == 5) {
                        $tSetName = '<B>' . $aValue['FTXsdPdtName'] . '<B>';
                    } else {
                        $tSetName = $aValue['FTXsdPdtName'];
                    }
                    ?>
                    <tr class="xCNTextDetail2">
                        <td class="xRefIntTd text-center">
                            <label class="fancy-checkbox ">
                                <input type="checkbox" <?php echo $tDisabledChk; ?> class="ocbRefIntDocDT <?php echo $tClassDisChk; ?>" name="ocbRefIntDocDT[]" value="<?= $aValue['FNXsdSeqNo'] ?>" data-checkrow="<?php echo $tDisabledcheckrow; ?>" data-checkrowid="<?php echo $aValue['FNXsdSeqNo']; ?>" <?php echo $tCheckDisRefDoc; ?>>
                                <span class="<?php echo $tClassDisChk; ?>">&nbsp;</span>
                            </label>
                        </td>
                        <td class="xRefIntTd text-center"><?= $nKey + 1 ?></td>
                        <td class="xRefIntTd"><?= $aValue['FTPdtCode'] ?></td>
                        <td class="xRefIntTd"><?= $tSpacSet; ?><?= $tSetName; ?></td>
                        <td class="xRefIntTd"><?= $aValue['FTXsdBarCode'] ?></td>
                        <td class="xRefIntTd"><?= $aValue['FTPunName'] ?></td>
                        <?php if ($aValue['FTPdtStaSet'] == 2 || $aValue['FTPdtStaSet'] == 5) { ?>
                            <td class="xRefIntTd" colspan='3'></td>
                        <?php } else { ?>
                            <td class="xRefIntTd text-right"><?= number_format($nXsdQty, $nOptDecimalShow) ?></td>
                            <td class="xRefIntTd text-right"><?= number_format($nXsdQtyC, $nOptDecimalShow) ?></td>
                            <td class="xRefIntTd text-right"><?= number_format($nXsdQtyL, $nOptDecimalShow) ?></td>
                        <?php } ?>
                    </tr>

                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    $('#ocbRefIntDocDTAll').click(function() {
        if ($(this).is(':checked') == true) {
            $('.xWUNDisabled').prop('checked', true);
        } else {
            $('.xWUNDisabled').prop('checked', false);
        }
    });
</script>