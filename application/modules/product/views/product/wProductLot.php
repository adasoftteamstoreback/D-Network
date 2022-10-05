<?php $nDecShow = FCNxHGetOptionDecimalShow(); ?>
<input type="hidden" class="form-control" id="ohdPdtStockLotCode" name="ohdPdtStockLotCode">
<input type="hidden" class="form-control" id="ohdPdtStockLotNo" name="ohdPdtStockLotNo">
<div class="text-right">
    <!-- <button id="obtPdtStockLotAdd" class="xCNBTNPrimeryPlus" style="margin-bottom:5px;" type="button" onclick="JSvPdtStocklotsAdd()">+</button> -->
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbPdtLotsdata" class="table table-striped">
                <thead>
                    <tr>
                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotNo'); ?></th>
                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotBatchNo'); ?></th>
                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotYear'); ?></th>
                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotBrand'); ?></th>
                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotModel'); ?></th>
                        <!-- <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotCost'); ?></th> -->
                        <!-- <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotDateMFG'); ?></th> -->
                        <!-- <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTLotDateEXP'); ?></th>               -->
                        <!-- <th nowrap class="text-center xCNTextBold" style="width:8%;"><?php echo language('product/product/product','tPdtStockLotsDelete')?></th> -->
                        <!-- <th nowrap class="text-center xCNTextBold" style="width:8%;"><?php echo language('product/product/product','tPdtStockLotsUpdate')?></th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($oList) && !empty($oList)) : ?>
                        <?php foreach ($oList as $nKey => $aValue) : ?>
                            <tr>
                                <td class="text-left"><?php echo $aValue['FTLotNo']; ?></td>
                                <td class="text-left"><?php echo $aValue['FTLotBatchNo']; ?></td>
                                <td class="text-left"><?php echo $aValue['FTLotYear']; ?></td>
                                <td class="text-left"><?php echo $aValue['FTPbnName']; ?></td>
                                <td class="text-left"><?php echo $aValue['FTPmoName']; ?></td>
                                <!-- <td class="text-right"><?php echo number_format($aValue['FCPdtCost'], $nDecShow); ?></td> -->
                                <!-- <td class="text-center"><?php echo date_format(date_create($aValue['FDPdtDateMFG']), 'd/m/Y'); ?></td> -->
                                <!-- <td class="text-center"><?php echo date_format(date_create($aValue['FDPdtDateEXP']), 'd/m/Y'); ?></td> -->
                                <!-- <td class="text-center"><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtStockLotDelete('<?php echo $aValue['FTPdtCode'];?>','<?php echo $aValue['FTLotNo'];?>');"></td> -->
                                <!-- <td class="text-center"><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvPdtStockLotPageEdit('<?php echo $aValue['FTPdtCode'];?>','<?php echo $aValue['FTLotNo'];?>');"></td>     -->
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100'><?php echo language('common/main/main', 'tCMNNotFoundData'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>