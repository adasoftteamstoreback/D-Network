<?php $nDecimal    = FCNxHGetOptionDecimalShow(); ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep1Point2DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold" style="width:5%;"><?= language('document/invoice/invoice', 'ลำดับ') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'ประเภทเอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'วันที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'เลขที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'เอกสารอ้างอิง') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'ครบกำหนด') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'จำนวนเงิน') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'ชำระแล้ว') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'ค้างชำระ') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($aDataList['rtCode'] == 1) : ?>
                    <?php
                    if (FCNnHSizeOf($aDataList['raItems']) != 0) {
                        $SumAmt = 0;
                        $SumAlreadyPaid = 0;
                        $SumDiscount = 0;
                        $SumAddcount = 0;
                        foreach ($aDataList['raItems'] as $nKey => $aDataTableVal) : ?>
                            <?php $nKey = $nKey + 1;
                            $SumKey = $nKey;
                            $SumAmt = $SumAmt + $aDataTableVal['FCXtdVatable'];
                            if($aDataTableVal['FTSrnCode'] == 'PC'){
                                $SumDiscount = $SumDiscount + $aDataTableVal['FCXtdAmt'];
                            }
                            if($aDataTableVal['FTSrnCode'] == 'PD'){
                                $SumAddcount = $SumAddcount + $aDataTableVal['FCXtdAmt'];
                            }
                            $SumAlreadyPaid = $aDataTableVal['FCXtdSetPrice'];
                            ?>
                            <tr class="otr<?= $aDataTableVal['FTPdtCode']; ?> xWPdtItemStep2 xWPdtItemList<?= $nKey ?>" data-key="<?= $nKey ?>" data-pdtcode="<?= $aDataTableVal['FTPdtCode']; ?>" data-seqno="<?= $nKey ?>">
                                <td style="text-align:center"><?= $nKey ?></td>
                                <!-- <td><?= $aDataTableVal['FTPdtCode']; ?></td> -->
                                <?php 
                                $TypeDoc = '';
                                if($aDataTableVal['FTSrnCode'] == 'IV'){
                                    $TypeDoc = 'ใบซื้อ';    
                                }elseif($aDataTableVal['FTSrnCode'] == 'PC'){
                                    $TypeDoc = 'ใบลดหนี้';    
                                }elseif($aDataTableVal['FTSrnCode'] == 'PD'){
                                    $TypeDoc = 'ใบเพิ่มหนี้';    
                                }
                                ?>
                                <td><?= $TypeDoc; ?></td>
                                <td class="text-center"><?= $aDataTableVal['DateSplGet']; ?></td>
                                <td><?= $aDataTableVal['FTPdtCode']; ?></td>
                                <td>
                                    <?= (!empty($aDataTableVal['FTXtdDocNoRef']))? $aDataTableVal['FTXtdDocNoRef'] : '-'; ?>
                                </td>
                                <td class="text-center">
                                    <?= (!empty($aDataTableVal['DateReq']))? $aDataTableVal['DateReq'] : '-'; ?>
                                </td>
                                <?php if($aDataTableVal['FTSrnCode'] == 'PC'){?>  
                                    <td class="text-right">-<?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
                                <?php }else{ ?>
                                    <td class="text-right"><?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
                                <?}?>
                                <td class="text-right"><?= number_format($aDataTableVal['FCXtdSetPrice'],$nDecimal); ?></td>
                                <td class="text-right"><?= number_format($aDataTableVal['FCXtdVatable'],$nDecimal); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>
                <?php else : ?>
                    <tr>
                        <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php @$SumAmt = $SumAmt - $SumDiscount + $SumAddcount; ?>
<input type="hidden" id="ohdIVBGrand" name="ohdIVBGrand" value="<?php echo @$SumAmt ?>">
<input type="hidden" id="ohdIVBAlreadyPaid" name="ohdIVBAlreadyPaid" value="<?php echo @$SumAlreadyPaid ?>">
<input type="hidden" id="ohdIVBGrandText" name="ohdIVBGrandText">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;">
    <div class="table-responsive" style="height: 160px;">
        <table id="otbCLMStep1Point2DocPdtAdvTableList2" class="table table-striped">
            <thead>
                <tr class="">
                    <th class="xCNTextBold" style="width:60%;">
                        <div class='row'>
                            <div class='col-xs-3'>
                                จำนวนบิล
                            </div>
                            <div class='col-xs-3' style="font-weight: bolder;">
                                <?php echo @$SumKey ?> ฉบับ
                            </div>
                            <div class='col-xs-3' style="text-align: right;">
                                รวมเป็นเงิน
                            </div>
                            <div class='col-xs-3' style="text-align: right;font-weight: bolder;">
                                <?php echo number_format(@$SumAmt,$nDecimal) ?>
                            </div>
                        </div>
                    </th>
                    <th class="xCNTextBold" style="text-align: center;"><?= language('document/invoice/invoice', 'รายการชำระเงิน') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="otrSumDetail1">
                    <td>
                        <div class='row'>
                            <div class='col-xs-3'>
                                จำนวนเงิน
                            </div>
                            <div class='col-xs-9' id="odvIVBDataTextBath">
                                <script>
                                    var tTextTotal = $('#ohdIVBGrand').val().replace(/,/g, '');
                                    var tThaibath = ArabicNumberToText(tTextTotal.replace("-", ""));
                                    
                                    $('#odvIVBDataTextBath').text(tThaibath);
                                    $('#ohdIVBGrandText').val(tThaibath);
                                </script>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class='row'>
                            <div class='col-xs-6'>
                                ยอดเงินที่ชำระแล้ว
                            </div>
                            <div class='col-xs-6' style="text-align:right">
                                <?php echo number_format(@$SumAlreadyPaid,$nDecimal) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="otrSumDetail2">
                    <td></td>
                    <td>
                        <div class='row'>
                            <div class='col-xs-6'>
                                ยอดเงินที่ค้างชำระ
                            </div>
                            <div class='col-xs-6' style="text-align:right">
                                <?php echo number_format(@$SumAmt,$nDecimal) ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        //แก้ไขจำนวน
        // JSxCLMStep1Point1EditQty();

        if ($('#oetCLMRefIntDate').val() == '') {
            $('.xCNChangeStatusClaim option[value="2"]').attr("selected", "selected");
        } else {
            const dDateDoc = new Date($('#oetCLMRefIntDate').val());
            const dDateCurrent = new Date();
            const dDiffTime = Math.abs(dDateCurrent - dDateDoc);
            const dDiffDays = Math.ceil(dDiffTime / (1000 * 60 * 60 * 24));
            if (dDiffDays <= $('.xCNPsvWaQtyDay').val()) {
                $('.xCNChangeStatusClaim option[value="1"]').attr("selected", "selected");
                JSxStep1Point2ChangeStatusClaim(1, 1);
            } else {
                $('.xCNChangeStatusClaim option[value="0"]').attr("selected", "selected");
            }
        }

        $('.selectpicker').selectpicker();
    });

    //ปรับสถานะเคลม
    function JSxStep1Point2ChangeStatusClaim(pnValue, pnKey) {
        //อัพเดท
        JSxStep1Point2UpdateDTTmp(pnKey, pnValue, 'StatusClaim');
    }

    //อัพเดทหมายเหตุ
    $('.xCNRemark').off().on('change keyup', function(e) {
        if (e.type === 'change' || e.keyCode === 13) {
            var nSeq = $(this).attr('data-seq');
            var tRmk = $('#ohdRmk' + nSeq).val();
            nNextTab = parseInt(nSeq) + 1;
            $('.xWValueEditInLine' + nNextTab).focus().select();

            //อัพเดท
            JSxStep1Point2UpdateDTTmp(nSeq, tRmk, 'RmkClaim');
        }
    });

</script>