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
            <table id="otbPRSTblDataDocHDList" class="table">
                <thead>
                    <tr class="xCNCenter">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <?php //ถ้าเป็นการเข้าใช้งานแบบ AGN จะเห็นใบขอซื้อของตัวเองด้วย
                            if($this->session->userdata("bIsHaveAgn") == true){ ?>
                                <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','tIVTitlePanelConditionAD')?></th>
                            <?php } ?>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSBchName') ?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocDate') ?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocRefIntNo') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDocRefIntDate') ?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSSplName') ?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaApv') ?></th>
                        <?php if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์ ?>
                            <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleStaPrcDoc') ?></th>
                        <?php }else{ //สำนักงานใหญ่ ?>
                            
                        <?php } ?>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSCreateBy') ?></th>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main', 'tCMNActionDelete') ?></th>
                        <?php endif; ?>
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

                                if ((str_replace(' ', '', $aValue['FTCreateBy'])) == 'MQReceivePrc') {
                                    $tCheckboxDisabled = "disabled";
                                    $tClassDisabled = 'xCNDocDisabled';
                                    $tTitle = language('document/document/document', 'tPRSCMsgCanNotDel');;
                                    $tOnclick = "";
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

                                if ($aValue['FTXphStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else {
                                    if ($aValue['FTXphStaDoc'] == 1 && $aValue['FTXphStaApv'] == '') {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    } else {
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }
                                }

                                $bIsApvOrCancel = ($aValue['FTXphStaApv'] == 1 || $aValue['FTXphStaApv'] == 2) || ($aValue['FTXphStaDoc'] == 3);
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice<?=$nKey ?>" data-code="<?=$aValue['FTXphDocNo'] ?>" data-name="<?=$aValue['FTXphDocNo'] ?>">

                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYDOC'];
                                    } 
                                ?>

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td <?=$nRowspan?> nowrap class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?= $tPRSDocNo ?>" data-bchcode="<?= $tPRSBchCode ?>" data-refcode="<?= $tPRSRefInCode ?>" <?=$tCheckboxDisabled; ?>>
                                                <span class="<?=$tClassDisabled ?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <?php //ถ้าเป็นการเข้าใช้งานแบบ AGN จะเห็นใบขอซื้อของตัวเองด้วย
                                        if($this->session->userdata("bIsHaveAgn") == true){ ?>
                                            <td <?=$nRowspan?> class="text-left"><?=(!empty($aValue['FTAgnName']))? $aValue['FTAgnName'] : '-' ?></td>
                                        <?php } ?>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTXphDocNo'])) ? $aValue['FTXphDocNo'] : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-center"><?=(!empty($aValue['FDXphDocDate'])) ? $aValue['FDXphDocDate'] : '-' ?></td>
                                <?php } ?>

                                <!--เอกสารอ้างอิง-->
                                <td nowrap class="text-left"><?=($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTSplName'])) ? $aValue['FTSplName'] : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-left">
                                        <label class="xCNTDTextStatus <?=$tClassStaDoc; ?>">
                                            <?=$tStaDoc; ?>
                                        </label>
                                    </td>
                                    <?php if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์ ?>
                                        <?php 
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
                                        ?>
                                        <td <?=$nRowspan?> nowrap class="text-left"> 
                                            <label class="xCNTDTextStatus <?=$tClassStaPrcDoc; ?>">
                                                <?=$tStaPrcDoc; ?>
                                            </label>
                                        </td>
                                    <?php }else{ //สำนักงานใหญ่ ?>
                                        
                                    <?php } ?>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['CreateByName'])) ? $aValue['CreateByName'] : '-' ?></td>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td <?=$nRowspan?> nowrap>
                                            <img class="xCNIconTable xCNIconDel <?=$tClassDisabled ?>" src="<?= base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" <?=$tOnclick ?> title="<?=$tTitle ?>">
                                        </td>
                                    <?php endif; ?>

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
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main', 'tResultTotalRecord') ?> <?php echo $aDataList['rnAllRow'] ?> <?php echo language('common/main/main', 'tRecord') ?> <?php echo language('common/main/main', 'tCurrentPage') ?> <?php echo $aDataList['rnCurrentPage'] ?> / <?php echo $aDataList['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPRSPageDataTable btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvPRSClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvPRSClickPageList('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>

            <?php if ($nPage >= $aDataList['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvPRSClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvPRSModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvPRSModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jSupplierPurchaseRequisitionDataTable.php') ?>