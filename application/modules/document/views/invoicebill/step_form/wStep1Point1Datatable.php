<?php $nDecimal    = FCNxHGetOptionDecimalShow(); ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbIVBStep1Point1DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNHideWhenCancelOrApprove">
                        <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmIVBCheckInsertAll" id="ocmIVBCheckInsertAll">
                            <span class="ospListItem">&nbsp;</span>
                        </label>
                    </th>
                    <th class="xCNTextBold" style="width:5%;"><?= language('document/invoice/invoice', 'ลำดับ') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'ประเภทเอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'วันที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'เลขที่เอกสาร') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'วันที่เอกสารอ้างอิง') ?></th>
                    <th class="xCNTextBold"><?= language('document/invoice/invoice', 'เอกสารอ้างอิง') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'ครบกำหนด') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/invoice/invoice', 'จำนวนเงิน') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($aDataList['rtCode'] == 1) : ?>
                    <?php
                    if (FCNnHSizeOf($aDataList['raItems']) != 0) {
                        foreach ($aDataList['raItems'] as $nKey => $aDataTableVal) : ?>
                            <?php $nKey = $nKey + 1 ?>
                            <tr class="otr<?= $aDataTableVal['FTPdtCode']; ?> xWPdtItemPoint1 xWPdtItemList<?= $nKey ?>" data-key="<?= $nKey ?>" data-pdtcode="<?= $aDataTableVal['FTPdtCode']; ?>" data-seqno="<?= $nKey ?>">
                                <td class="xCNHideWhenCancelOrApprove" style="text-align:center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxQTSelectMulInsert(this)">
                                        <span class="ospListItem">&nbsp;</span>
                                    </label>
                                </td>
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
                                <td class="text-center">
                                <?= (!empty($aDataTableVal['DateRefDoc']))? $aDataTableVal['DateRefDoc'] : '-'; ?>
                                </td>
                                <td>
                                <?= (!empty($aDataTableVal['FTXtdDocNoRef']))? $aDataTableVal['FTXtdDocNoRef'] : '-'; ?>
                                </td>
                                <td class="text-center">
                                <?= (!empty($aDataTableVal['DateReq']))? $aDataTableVal['DateReq'] : '-'; ?>
                                </td>
                                <td class="text-right"><?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
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
<?php
if (isset($aDataList['Step2Item'])) {
    ?>
    <script>
        localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
    </script>
    <?php
    foreach ($aDataList['Step2Item'] as $key => $value) {
        $tFindName = 'otr'.$value['FTPdtCode'];
        ?>
        <script>
            var tJFindName = "<?php echo $tFindName; ?>";
            $( "tr."+tJFindName ).prop('checked', false);
            $( "tr."+tJFindName ).find( "input" ).trigger( "click" );
        </script>
    <?php
    }
}
?>

<script>
    $(document).ready(function() {
        //แก้ไขจำนวน
        // JSxCLMStep1Point1EditQty();
        // localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
    });

    //คลิกเลือกทั้งหมดในสินค้า DT Tmp
    $('#ocmIVBCheckInsertAll').change(function() {
        var bStatus = $(this).is(":checked") ? true : false;
        if (bStatus == false) {
            localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
            $('.ocbListItem').prop('checked', false);
            $('#ohdConfirmIVBInsertPDT').val('');
            $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
        } else {
            localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
            $('.ocbListItem').prop('checked', false);
            $('.ocbListItem').each(function(e) {
                $(this).on("click", FSxQTSelectMulInsert(this));
            });
        }
    });

    //ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
    function JStIVBFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }
</script>