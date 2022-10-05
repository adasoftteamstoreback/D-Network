<?php $nDecimal = FCNxHGetOptionDecimalShow(); ?>
<?php if ($aDataList['rtCode'] == 1) : ?>
    <?php if(FCNnHSizeOf($aDataList['raItems']) != 0): ?>
        <?php foreach($aDataList['raItems'] as $nKey => $aDataTableVal) :?>
            <?php 
                $nKey  = $nKey + 1;
                // Case Document Type Text
                $TypeDoc    = "";
                switch($aDataTableVal['FTSrnCode']){
                    case 'IV':
                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPIBill');
                    break;
                    case 'PC':
                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPCBill');
                    break;
                    case 'PD':
                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPDBill');
                    break;
                }
            ?>
            <tr 
                class="otr<?= $aDataTableVal['FTPdtCode']; ?> xWPdtItemStep2 xWPdtItemList<?= $nKey ?>"
                data-key="<?= $nKey ?>" 
                data-pdtcode="<?= $aDataTableVal['FTPdtCode']; ?>"
                data-seqno="<?= $nKey ?>"
            >
                <td style="text-align:center;vertical-align:middle;"><?= $nKey ?></td>
                <td style="vertical-align:middle;"><?= $TypeDoc; ?></td>
                <td style="vertical-align:middle;" class="text-center"><?= $aDataTableVal['DateSplGet']; ?></td>
                <td style="vertical-align:middle;"><?= $aDataTableVal['FTPdtCode']; ?></td>
                <td style="vertical-align:middle;">
                    <?= (!empty($aDataTableVal['FTXtdDocNoRef']))? $aDataTableVal['FTXtdDocNoRef'] : '-'; ?>
                </td>
                <td style="vertical-align:middle;" class="text-center">
                    <?= (!empty($aDataTableVal['DateReq']))? $aDataTableVal['DateReq'] : '-'; ?>
                </td>
                <?php if($aDataTableVal['FTSrnCode'] == 'PC'){ ?>  
                    <td class="text-right">-<?= number_format($aDataTableVal['FCXtdInvGrand'],$nDecimal); ?></td>
                <?php }else{ ?>
                    <td class="text-right"><?= number_format($aDataTableVal['FCXtdInvGrand'],$nDecimal); ?></td>
                <?}?>

                <td style="vertical-align:middle;" class="text-right"><?= number_format($aDataTableVal['FCXtdInvPaid'],$nDecimal); ?></td>
                <td style="vertical-align:middle;" class="text-right"><?= number_format($aDataTableVal['FCXtdInvRem'],$nDecimal); ?></td>
                <td style="vertical-align:middle;" class="text-right">
                    <input type="text" class="from-control text-right xCNPricePay" value="<?= number_format($aDataTableVal['FCXtdInvPay'],$nDecimal); ?>" readonly>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endif; ?>
<?php else :?>
    <tr>
        <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
    </tr>
<?php endif; ?>