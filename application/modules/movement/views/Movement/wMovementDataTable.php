<?php
    $nCurrentPage   = '1';
?>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?= $nCurrentPage; ?>">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBOrder') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBPdtCode') ?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBPdtName') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBDate') ?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBDocNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBWaHouse') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBMonthEnd') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBIn') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBOut') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBSale') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBReturn') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBUpdate') ?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?= language('movement/movement/movement', 'tMMTBTreasury') ?></th>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) {  ?>
                            <tr class="xCNTextDetail2 otrReason" id="otrReason<?= $key ?>" data-code="<?= $aValue['FTPdtCode'] ?>" data-name="<?= $aValue['FTPdtName'] ?>">
                                <td nowrap class="text-center" style="text-align: center;"><?=$key+1;?></td>
                                <td nowrap class="text-left"><?= $aValue['FTPdtCode'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['FTPdtName'] ?></td>
                                <td nowrap class="text-center"><?= $aValue['FDStkDate'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['FTStkDocNo'] ?></td>
                                <td nowrap class="text-left"><?= $aValue['FTWahName'] ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkMonthEnd'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkIN'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkOUT'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkSale'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkReturn'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkAdjust'], $nOptDecimalShow); ?></td>
                                <td nowrap class="text-right"><?php echo number_format($aValue['FCStkQtyInWah'], $nOptDecimalShow); ?></td>
                            </tr>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='13' style="text-align: center;"><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:10px;">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>