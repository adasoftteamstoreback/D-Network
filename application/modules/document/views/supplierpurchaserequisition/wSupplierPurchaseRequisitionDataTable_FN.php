<?php
if ($aDataList['rtCode'] == '1') {
    $nCurrentPage   = $aDataList['rnCurrentPage'];
} else {
    $nCurrentPage = '1';
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?=language('document/invoice/invoice','tIVTitlePanelConditionAD')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'ขอซื้อจากสาขา') ?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocDate') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocRefIntNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocRefIntDate') ?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'สถานะเอกสาร') ?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleStaPrcDoc'); ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSCreateBy') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main', 'tCMNActionEdit') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                            <?php
                                $tPRSDocNo      = $aValue['FTXphDocNo'];
                                $tPRSBchCode    = $aValue['FTBchCode'];
                                $tPRSRefInCode  = '-';

                                if ((str_replace(' ', '', $aValue['FTCreateBy'])) == 'MQReceivePrc') { //มาจากการ Gen
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document', 'tPRSCMsgCanNotDel');;
                                    $tOnclick           = "";
                                } else {
                                    if (!empty($aValue['FTXphStaApv']) || $aValue['FTXphStaDoc'] == 3) {
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = 'xCNDocDisabled';
                                        $tTitle             = language('document/document/document', 'tPRSCMsgCanNotDel');
                                        $tOnclick           = '';
                                    } else {
                                        $tCheckboxDisabled  = "";
                                        $tClassDisabled     = '';
                                        $tTitle             = '';
                                        $tOnclick           = "onclick=JSoPRSDelDocSingle('" . $nCurrentPage . "','" . $tPRSDocNo . "','" . $tPRSBchCode . "','" . $tPRSRefInCode . "')";
                                    }
                                }

                                //สถานะเอกสาร
                                if ($aValue['FTXphStaDoc'] == 3) {
                                    $tClassStaDoc   = 'text-danger';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc3');
                                } else {
                                    if ($aValue['FTXphStaDoc'] == 1 && $aValue['FTXphStaApv'] == '') {
                                        $tClassStaDoc   = 'text-warning';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc');
                                    } else {
                                        $tClassStaDoc   = 'text-success';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc1');
                                    }
                                }

                                //สถานะอนุมัติ
                                if ($aValue['FTXphStaDoc'] == 3) {
                                    $tClassStaPrcDoc       = 'text-danger';
                                    $tStaPrcDoc            = language('common/main/main', 'tStaDoc3');
                                } else {
                                    if ($aValue['FTXphStaPrcDoc'] == 2) { // แฟรนไซด์อนุมัติ
                                        $tClassStaPrcDoc   = 'text-warning';
                                        $tStaPrcDoc        = language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaPrcDoc2');
                                    } else if ($aValue['FTXphStaPrcDoc'] == 3 ) { // สำนักงานใหญ่อนุมัติ
                                        $tClassStaPrcDoc   = 'text-success';
                                        $tStaPrcDoc        = language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaPrcDoc3'); 
                                    }else{
                                        $tClassStaPrcDoc   = 'text-success';
                                        $tStaPrcDoc        = '-';
                                    }
                                }

                                $bIsApvOrCancel = ($aValue['FTXphStaPrcDoc'] == 3) || ($aValue['FTXphStaDoc'] == 3);
                            ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTXphDocNo'] ?>" data-name="<?=$aValue['FTXphDocNo'] ?>">

                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYDOC'];
                                    } 
                                ?>

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <td <?=$nRowspan?>  class="text-left"><?=(!empty($aValue['FTAgnName']))? $aValue['FTAgnName'] : '-' ?></td>
                                    <td <?=$nRowspan?>  nowrap class="text-left"><?=(!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td <?=$nRowspan?>  nowrap class="text-left"><?=(!empty($aValue['FTXphDocNo'])) ? $aValue['FTXphDocNo'] : '-' ?></td>
                                    <td <?=$nRowspan?>  nowrap class="text-center"><?=(!empty($aValue['FDXphDocDate'])) ? $aValue['FDXphDocDate'] : '-' ?></td>
                                <?php } ?>

                                <!--เอกสารอ้างอิง-->
                                <td nowrap class="text-left"><?=($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <!--สถานะเอกสาร-->
                                    <td <?=$nRowspan?>  nowrap class="text-left">
                                        <label class="xCNTDTextStatus <?=$tClassStaDoc; ?>">
                                            <?=$tStaDoc; ?>
                                        </label>
                                    </td>
                                    <!--สถานะอนุมัติ-->
                                    <td <?=$nRowspan?>  nowrap class="text-left">
                                        <label class="xCNTDTextStatus <?=$tClassStaPrcDoc; ?>">
                                            <?=$tStaPrcDoc; ?>
                                        </label>
                                    </td>
                                    <td <?=$nRowspan?>  nowrap class="text-left"><?=(!empty($aValue['CreateByName'])) ? $aValue['CreateByName'] : '-' ?></td>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td <?=$nRowspan?> nowrap>
                                            <?php if ($bIsApvOrCancel) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvPRSCallPageEdit('<?= $aValue['FTXphDocNo'] ?>')">
                                            <?php } else { ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvPRSCallPageEdit('<?=$aValue['FTXphDocNo'] ?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('script/jSupplierPurchaseRequisitionDataTable.php') ?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?=language('common/main/main', 'tResultTotalRecord') ?> <?=$aDataList['rnAllRow'] ?> <?=language('common/main/main', 'tRecord') ?> <?=language('common/main/main', 'tCurrentPage') ?> <?=$aDataList['rnCurrentPage'] ?> / <?=$aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPRS_FN_PageDataTable btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvPRSClickPageList_FN('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($aDataList['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive            = 'active';
                    $tDisPageNumber     = 'disabled';
                } else {
                    $tActive            = '';
                    $tDisPageNumber     = '';
                }
                ?>
                <button onclick="JSvPRSClickPageList_FN('<?=$i ?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvPRSClickPageList_FN('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<script>
    //ไม่มีลบทั้งหมด
    $('#odvPRSMngTableList').hide();
</script>