<?php $nDecimal = FCNxHGetOptionDecimalShow(); ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbRPPStep1Point1DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNHideWhenCancelOrApprove">
                        <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmRPPCheckInsertAll" id="ocmRPPCheckInsertAll">
                            <span class="ospListItem">&nbsp;</span>
                        </label>
                    </th>
                    <th class="xCNTextBold" style="width:5%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'ลำดับ') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'ประเภทเอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'เลขที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'วันที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'เอกสารอ้างอิง') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'ครบกำหนด') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'จำนวนเงิน') ?></th>
                </tr>
            </thead>
            </tbody>
                <?php if ($aDataList['rtCode'] == 1) : ?>
                    <?php if (FCNnHSizeOf($aDataList['raItems']) != 0): ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aDataTableVal): ?>
                            <?php 
                                $nKey = $nKey + 1
                            ?>
                            <tr 
                                class="otr<?= $aDataTableVal['FTPdtCode']; ?> xWPdtItemPoint1 xWPdtItemList<?= $nKey ?>"
                                data-key="<?= $nKey ?>"
                                data-pdtcode="<?= $aDataTableVal['FTPdtCode']; ?>"
                                data-seqno="<?= $nKey ?>"
                            >
                                <td class="xCNHideWhenCancelOrApprove" style="text-align:center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxRPPSelectMulInsert(this)">
                                        <span class="ospListItem">&nbsp;</span>
                                    </label>
                                </td>
                                <td style="text-align:center"><?= $nKey ?></td>
                                <?php 
                                    $TypeDoc    = '';
                                    if($aDataTableVal['FTSrnCode'] == 'IV'){
                                        $TypeDoc    = 'ใบซื้อ';    
                                    }elseif($aDataTableVal['FTSrnCode'] == 'PC'){
                                        $TypeDoc    = 'ใบลดหนี้';   
                                    }elseif($aDataTableVal['FTSrnCode'] == 'PD'){
                                        $TypeDoc    = 'ใบเพิ่มหนี้';   
                                    }
                                ?>
                                <td><?= $TypeDoc;?></td>
                                <td><?= $aDataTableVal['FTPdtCode']; ?></td>
                                <td class="text-center"><?= $aDataTableVal['DateSplGet']; ?></td>
                                <td>
                                    <?= (!empty($aDataTableVal['FTXtdDocNoRef']))? $aDataTableVal['FTXtdDocNoRef'] : '-'; ?>
                                </td>
                                <td class="text-center">
                                    <?= (!empty($aDataTableVal['DateReq']))? $aDataTableVal['DateReq'] : '-'; ?>
                                </td>
                                <td class="text-right"><?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php else : ?>
                    <tr>
                        <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (isset($aDataList['Step2Item'])): ?>
    <script type="text/javascript">
        localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
    </script>
    <?php foreach ($aDataList['Step2Item'] AS $key => $value): ?>
        <?php $tFindName    = 'otr'.$value['FTPdtCode']; ?>
        <script type="text/javascript">
            var tJFindName = "<?php echo $tFindName; ?>";
            $( "tr."+tJFindName ).prop('checked', false);
            $( "tr."+tJFindName ).find( "input" ).trigger( "click" );
        </script>
    <?php endforeach; ?>
<?php endif; ?>
<script type="text/javascript">
    
    // คลิกเลือกทั้งหมดในสินค้า DT Tmp
    $('#ocmRPPCheckInsertAll').change(function() {
        let bStatus = $(this).is(":checked") ? true : false;
        if (bStatus == false) {
            localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
            $('.ocbListItem').prop('checked', false);
            $('#ohdConfirmRPPInsertPDT').val('');
            $('.xCNRPPStep1Point2').attr('data-toggle','false');
        } else {
            localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
            $('.ocbListItem').prop('checked', false);
            $('.ocbListItem').each(function(e) {
                $(this).on("click", FSxRPPSelectMulInsert(this));
            });
        }
    });

    
    
</script>

