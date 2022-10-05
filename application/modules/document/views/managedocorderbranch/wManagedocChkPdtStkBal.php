<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="xCNCenter">
                        <th class="xCNTextBold" width="50%"><?=language('common/main/main','สาขา'); ?></th>
						<th class="xCNTextBold" width="35%"><?=language('common/main/main','คลัง'); ?></th>
                        <th class="xCNTextBold" width="15%"><?=language('common/main/main','จำนวนคงคลัง'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $aGetPdtStkBal['tCode'] == '1' ):?>
                        <?php foreach($aGetPdtStkBal['aItems'] AS $nKey => $aValue): ?>
                            <tr>
                                <?php if( $aValue['FNByBch'] == '1' ){ ?>
                                    <td rowspan="<?=$aValue['FNMaxBch']?>"><?=$aValue['FTBchCode'].' - '.$aValue['FTBchName']?></td>
                                <?php } ?>
                                <td><?=$aValue['FTWahName']?></td>
                                <td class="text-right"><?=number_format($aValue['FCStkQty'],$nOptDecimalShow)?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>