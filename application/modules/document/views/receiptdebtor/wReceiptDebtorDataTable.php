<?php
    if ( $aDataList['tCode'] == '1' ) {
        $nCurrentPage = $aDataList['nCurrentPage'];
    } else {
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" width="5%"><?= language('document/document/document', 'tDocNumber') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/abbsalerefund/abbsalerefund', 'tABBBranch') ?></th>
                        <th nowrap class="xCNTextBold"><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocNo') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocDate') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/abbsalerefund/abbsalerefund', 'tABBStaApv') ?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?= language('document/abbsalerefund/abbsalerefund', 'tABBCustomer') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            <th nowrap class="xCNTextBold" width="5%"><?= language('document/abbsalerefund/abbsalerefund', 'tABBManage') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                <?php 
                    if ($aDataList['tCode'] == 1) : 
                        if ( FCNnHSizeOf($aDataList['aItems']) > 0 ){
                            foreach ($aDataList['aItems'] as $nKey => $aValue) : 
                ?>
                                <tr class="text-center xCNTextDetail2">
                                    <td nowrap class="text-center"><?php echo $aValue['FNRowID']; ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName'])) ? $aValue['FTBchName'] : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo'])) ? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center"><?php echo date_format(date_create($aValue['FDXshDocDate']),'d/m/Y'); ?></td>
                                    <td nowrap class="text-left">
                                        <?php
                                            $tStyleText = '';
                                            $tStaDocText = '';
                                            if ($aValue['FTXshStaDoc'] == 1) {
                                                $tStyleText = 'text-success';
                                                $tStaDocText = language('common/main/main', 'tStaDocComplete');  
                                            }elseif ($aValue['FTXshStaDoc'] == 2) {
                                                $tStyleText = 'text-warning';
                                                $tStaDocText = language('common/main/main', 'tStaDocinComplete');
                                            }else{
                                                $tStyleText = 'text-danger';
                                                $tStaDocText = language('common/main/main', 'tStaDocCancel');
                                            }
                                        ?>
                                        <label class="xCNTDTextStatus <?=$tStyleText?>"><?=$tStaDocText?></label>
                                    </td>
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTCstName'])) ? $aValue['FTCstName'] : language('document/document/document', 'tDocRegularCustomers') ?></td>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap>
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/view2.png' ?>" onClick="JSxRCBPageEdit('<?= $aValue['FTXshDocNo'] ?>')">
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach;
                        } else { ?>
                            <tr>
                                <td nowrap class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                            </tr>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td nowrap class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main', 'tResultTotalRecord') ?> <?php echo $aDataList['nAllRow'] ?> <?php echo language('common/main/main', 'tRecord') ?> <?php echo language('common/main/main', 'tCurrentPage') ?> <?php echo $aDataList['nCurrentPage'] ?> / <?php echo $aDataList['nAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageRCBPdt btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSxRCBEventClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['nAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSxRCBEventClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['nAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSxRCBEventClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>