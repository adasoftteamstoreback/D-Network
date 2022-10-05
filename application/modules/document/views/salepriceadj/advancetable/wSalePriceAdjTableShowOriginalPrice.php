<?php
// echo $tSQL;
// if ($nStaEvent == 1) {
// echo "<h3>" . $aPdtData4PDT['FTPdtCode'] . " " . $aPdtData4PDT['FTPdtName'] . " " . $tPplName . "</h3>";
// }
if ($tPplName != '') {
    $tShowPplName = 'กลุ่มราคา : ' . $tPplName . '';
} else {
    $tShowPplName = '';
}

echo "<h3>" . $tFTPdtCode . " " . $tFTPdtName . "    " . $tShowPplName . "</h3>";
?>

<table class="table table-bordered table-striped" id="otbOrderListDetail">
    <tr class="xCNCenter">
        <th nowrap width="40%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBPunCode') ?></th>
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceRet') ?></th>
        <!-- <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceWhs') ?></th>
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceNet') ?></th> -->
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBStartDate') ?></th>
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBStopDate') ?></th>
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBStartTime') ?></th>
        <th nowrap width="20%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBEndTime') ?></th>
        <th nowrap width="30%"><?= language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceType') ?></th>
    </tr>
    <?php if ($nStaEvent == 1) { ?>
        <tr>
            <td nowrap class="text-center"><?= $aPdtData4PDT['FTPunName'] ?></td>
            <td nowrap class="text-right"><?= number_format($aPdtData4PDT['FCPgdPriceRet'], $nOptDecimal); ?></td>
            <!-- <td nowrap class="text-right"><?= number_format($aPdtData4PDT['FCPgdPriceWhs'], $nOptDecimal); ?></td>
        <td nowrap class="text-right"><?= number_format($aPdtData4PDT['FCPgdPriceNet'], $nOptDecimal); ?></td> -->
            <td nowrap class="text-center"><?= $aPdtData4PDT['FDPghDStart'] ?></td>
            <td nowrap class="text-center"><?= $aPdtData4PDT['FDPghDStop'] ?></td>
            <td nowrap class="text-center"><?= $aPdtData4PDT['FTPghTStart'] ?></td>
            <td nowrap class="text-center"><?= $aPdtData4PDT['FTPghTStop'] ?></td>
            <?php
            if ($aPdtData4PDT['FTPghDocType'] == 1) {
                $tPghDocType = 'Base Price';
            } else if ($aPdtData4PDT['FTPghDocType'] == 2) {
                $tPghDocType = 'Price Off';
            } else {
                $tPghDocType = '';
            }
            ?>
            <td nowrap class="text-center"><?= $tPghDocType; ?></td>

        </tr>
    <?php } else { ?>
        <tr>
            <td nowrap colspan="7" class="text-center">ไม่พบข้อมูล</td>
        </tr>
    <?php } ?>

</table>