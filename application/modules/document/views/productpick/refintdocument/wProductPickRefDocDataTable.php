<style>
    .my-custom-scrollbar {
        position: relative;
        height: 350px;
        overflow: auto;
    }

    .table-wrapper-scroll-y {
        display: block;
    }
</style>


<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage   = $aDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>

<div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive my-custom-scrollbar table-wrapper-scroll-y">
          
                <table class="table table-striped" id='obtsrollbar'>
                    <thead>
                        <tr class="xCNCenter">
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch') ?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo') ?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'ชื่อลูกค้า') ?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocDate') ?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBStaDoc') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($aDataList['rtCode'] == 1) : ?>
                            <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                                <?php
                                $tBchCode  = $aValue['FTBchCode'];

                                //FTXshStaDoc
                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else if ($aValue['FTXshStaApv'] == 1) {
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = language('common/main/main', 'tStaDoc');
                                }

                                $aDateDoc = explode("/", $aValue['FDXshDocDate']);
                                $tDateDoc = $aDateDoc[2] . '-' . $aDateDoc[1] . '-' . $aDateDoc[0];
                                ?>
                                <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xDocuemntRefInt" id="otrPurchaseInvoiceRefInt<?php echo $nKey ?>" data-docno="<?= $aValue['FTXshDocNo'] ?>" data-docdate="<?= $tDateDoc ?>" data-bchcode="<?= $tBchCode ?>" data-vatinroex="1" data-cstcode="<?= $aValue['FTCstCode'] ?>" data-cstname="<?= $aValue['FTCstName'] ?>" data-csttaxno="<?= $aValue['FTCstTaxNo'] ?>" data-csttel="<?= $aValue['FTCstTel'] ?>" data-cstemail="<?= $aValue['FTCstEmail'] ?>" data-cstaddl="<?= $aValue['FTAddV2Desc1'] ?>" data-carregno="<?= $aValue['FTCarRegNo'] ?>" data-bndname="<?= $aValue['FTBndName'] ?>">
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo'])) ? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($aValue['FTCstName'])) ? $aValue['FTCstName'] : '-' ?></td>
                                    <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshDocDate'])) ? $aValue['FDXshDocDate'] : '-' ?></td>
                                    <td nowrap class="text-left">
                                        <label class="xCNTDTextStatus <?php echo $tClassStaDoc; ?>">
                                            <?php echo $tStaDoc ?>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            
        </div>
    </div>
</div>

<div class="">
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?= $nShowRecord ?> รายการ</p>
    </div>
    <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main', 'tResultTotalRecord') ?> <?php echo $aDataList['rnAllRow'] ?> <?php echo language('common/main/main', 'tRecord') ?> <?php echo language('common/main/main', 'tCurrentPage') ?> <?php echo $aDataList['rnCurrentPage'] ?> / <?php echo $aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWQTPageDataTable btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSxRefIntDocHDDataTable('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSxRefIntDocHDDataTable('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSxRefIntDocHDDataTable('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div> -->
</div>

<div class="">
    <div id="odvRefIntDocDetail"></div>
</div>

<?php include('script/jProductPickRefDocDataTable.php') ?>