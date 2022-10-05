<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbPRBTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOTBNo')?></th>
                        <th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOARPPdtCode')?></th>
						<th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOARPPdtName')?></th>
                        <th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOAdvSearchBranch')?></th>
                        <th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmWah')?></th>
                        <th nowrap class="xCNTextBold text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmWahQTY')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPRBDocItems xPurchaseInvoiceRefInt"
                                    id="otrPurchaseInvoiceRefInt<?php echo $nKey?>"
                            >
                                <td nowrap class="text-left"><?php echo $nKey+1 ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPdtCode']))? $aValue['FTPdtCode']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTPdtName']))? $aValue['FTPdtName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTWahName']))? $aValue['FTWahName'] : '-' ?></td>
                                <td nowrap class="text-right"><?php echo (!empty($aValue['FCStkQty']))? $aValue['FCStkQty'] : '-' ?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPRBPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPRBRefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvPRBRefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPRBRefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<?php include('script/jPurchasebranchRefDocRefDocDataTable.php')?>
