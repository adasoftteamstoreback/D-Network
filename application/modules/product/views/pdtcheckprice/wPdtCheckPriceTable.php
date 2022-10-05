<input id="oetPCPPdtForSys" type="hidden"   value="<?php echo $tPdtForSys; ?>">
<input id="oetPCPPage"      type="hidden"   value="<?php echo $nPage; ?>">
<input type="hidden" id="ohdPCPProductAllRow" name="ohdProductAllRow" value="<?= get_cookie('nShowRecordInPageList'); ?>">
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvPCPDataTableProduct" class="table-responsive">
            <table id="otbPCPCheckPriceTable" class="table" border="1" cellspacing="0">
                <thead>
                    <tr>
                        <?php if ($tDisplayType == '2') {
                            echo "<th class='text-center'>" . language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplName') . "</th>";
                        } ?>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtCode'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtName'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtUnit'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtDateStart'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtDateStop'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtTimeStart'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtTimeStop'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtDocNo'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'ปรับราคา'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPriceTypeSub'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPriceType'); ?></th>
                        <th class="text-center"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPdtPrice'); ?></th>
                        <?php if ($tDisplayType == '1') {
                            echo "<th class='text-center'>" . language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplName') . "</th>";
                        } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == '1') { ?>
                        <?php foreach ($aDataList['raItems'] as $key => $aValue) : ?>
                            <?php
                                if ($aValue['FTPplName'] == NULL || $aValue['FTPplName'] == '') {
                                    $FTPplName = language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplNameAll');;
                                } else {
                                    $FTPplName = $aValue['FTPplName'];
                                }

                                if ($aValue['FTXphDocType'] == 1) {
                                    $tXphDocType = 'Base Price';
                                } else if ($aValue['FTXphDocType'] == 2) {
                                    $tXphDocType = 'Price Off';
                                } else if($aValue['FTXphDocType'] == 3){
                                    $tXphDocType = 'Promotion';
                                } else if($aValue['FTXphDocType'] == 4){
                                    $tXphDocType = 'Coupon';
                                } else {
                                    $tXphDocType = '';
                                }
                            ?>
                            <tr>
                                <?php if ($tDisplayType == '2') {
                                    echo "<td>" . $FTPplName . "</td>";
                                } ?>
                                <td><?php echo $aValue['FTPdtCode']; ?></td>
                                <td><?php echo $aValue['FTPdtName']; ?></td>
                                <td><?php echo $aValue['FTPunName']; ?></td>
                                <td class="text-center"><?php echo $aValue['FDXphDStart']; ?></td>
                                <td class="text-center"><?php echo $aValue['FDXphDStop']; ?></td>
                                <td class="text-center"><?php echo $aValue['FTXphTStart']; ?></td>
                                <td class="text-center"><?php echo $aValue['FTXphTStop']; ?></td>
                                <td><?php echo $aValue['FTXphDocNo']; ?></td>
                                <?php if($aValue['FTXphStaAdj'] == '1') {
                                        echo "<td class='text-right'>" . number_format($aValue['FCXpdPriceRet'], $nOptDecimalShow) . "</td>";
                                    }elseif($aValue['FTXphStaAdj'] == '2'){
                                        echo "<td class='text-right'>"."-" . number_format($aValue['FCXpdPriceRet'], 0) . "%"."</td>";
                                    }elseif($aValue['FTXphStaAdj'] == '3'){
                                        echo "<td class='text-right'>"."-" . number_format($aValue['FCXpdPriceRet'], $nOptDecimalShow) ."</td>";
                                    }elseif($aValue['FTXphStaAdj'] == '4'){
                                        echo "<td class='text-right'>" . number_format($aValue['FCXpdPriceRet'], 0) . "%"."</td>";
                                    }elseif($aValue['FTXphStaAdj'] == '5'){
                                        echo "<td class='text-right'>" . number_format($aValue['FCXpdPriceRet'], $nOptDecimalShow) ."</td>";
                                    }
                                ?>
                                <td class="text-left"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDXphStaAdj'.$aValue['FTXphStaAdj']) ?></td>
                                <td class="text-left"><?php echo $tXphDocType; ?></td>
                                <td class="text-right"><?php echo number_format($aValue['SumPrice'], $nOptDecimalShow); ?></td>
                                <?php if ($tDisplayType == '1') {
                                    echo "<td>" . $FTPplName . "</td>";
                                } ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php } else { ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='12'>
                                <?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="hidden">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>