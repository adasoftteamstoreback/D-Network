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
            <table id="otbSOTblDataDocHDList" class="table">
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
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOPanelAgency') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBBchCreate') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'สั่งเพื่อสาขา') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBSpl') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocDate') ?></th>
                        <th nowrap class="xCNTextBold">เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:5%;">วันที่เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBStaDoc') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'สถานะถูกอ้างอิงเอกสาร') ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBCreateBy') ?></th>
                        <!-- <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBApvBy') ?></th> -->
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main', 'tCMNActionDelete') ?></th>
                        <?php endif; ?>
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main', 'tCMNActionEdit') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) : ?>
                            <?php
                            $tPODocNo       = $aValue['FTXphDocNo'];
                            $tPOBchCode     = $aValue['FTBchCode'];
                            $tPORefInCode   = null;

                            if ((str_replace(' ', '', $aValue['FTCreateBy'])) == 'MQReceivePrc') {
                                $tCheckboxDisabled  = "disabled";
                                $tClassDisabled     = 'xCNDocDisabled';
                                $tTitle             = language('document/document/document', 'tPRSCMsgCanNotDel');;
                                $tOnclick           = "";
                            } else {
                                if (!empty($aValue['FTXphStaApv']) || $aValue['FTXphStaDoc'] == 3) {
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document', 'tDOCMsgCanNotDel');
                                    $tOnclick           = '';
                                } else {
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoPODelDocSingle('" . $nCurrentPage . "','" . $tPODocNo . "','" . $tPOBchCode . "','" . $tPORefInCode . "')";
                                }
                            }
                            
                            //FTXphStaDoc
                            if ($aValue['FTXphStaDoc'] == 3) {
                                $tClassStaDoc = 'text-danger';
                                $tStaDoc = language('common/main/main', 'tStaDoc3');
                            } else if ($aValue['FTXphStaApv'] == 1) {
                                $tClassStaDoc = 'text-success';
                                $tStaDoc = language('common/main/main', 'tStaDoc1');
                            } else {
                                $tClassStaDoc = 'text-warning';
                                $tStaDoc = language('common/main/main', 'tStaDoc');
                            }

                            // FNXphStaRef
                            /*if($aValue['FNXphStaRef'] == 2){
                                    $tClassStaRef = 'text-success';
                                }else if($aValue['FNXphStaRef'] == 1){
                                    $tClassStaRef = 'text-warning';    
                                }else if($aValue['FNXphStaRef'] == 0){
                                    $tClassStaRef = 'text-danger';
                                }*/

                            $tClassPrcStk = 'text-success';
                            $bIsApvOrCancel = ($aValue['FTXphStaApv'] == 1 || $aValue['FTXphStaApv'] == 2) || ($aValue['FTXphStaDoc'] == 3);
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice<?php echo $nKey ?>" data-code="<?php echo $aValue['FTXphDocNo'] ?>" data-name="<?php echo $aValue['FTXphDocNo'] ?>">
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
                                        <td nowrap class="text-center" <?=$nRowspan?>>
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?php echo $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?= $tPODocNo ?>" data-bchcode="<?= $tPOBchCode ?>" data-refincode="<?= $tPORefInCode ?>" <?php echo $tCheckboxDisabled; ?>>
                                                <span class="<?php echo $tClassDisabled ?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTAgnName'])) ? $aValue['FTAgnName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTBchName'])) ? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['BCHNameTo'])) ? $aValue['BCHNameTo']   : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTSplName'])) ? $aValue['FTSplName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTXphDocNo'])) ? $aValue['FTXphDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center" <?=$nRowspan?>><?php echo (!empty($aValue['FDXphDocDate'])) ? $aValue['FDXphDocDate'] : '-' ?></td>
                                <?php } ?>

                                <td nowrap class="text-left"><?=($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <td nowrap class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus <?php echo $tClassStaDoc; ?>">
                                            <?php echo $tStaDoc ?>
                                        </label>
                                    </td>
                                    <td nowrap class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus">
                                            <?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmStaRef' . $aValue['FNXphStaRef']) ?>
                                        </label>
                                    </td>
                                    <td nowrap class="text-left" <?=$nRowspan?>>
                                        <?php echo (!empty($aValue['FTCreateByName'])) ? $aValue['FTCreateByName'] : '-' ?>
                                    </td>
                                    <!-- <td nowrap class="text-left" <?=$nRowspan?>>
                                        <?php echo (!empty($aValue['FTXphApvName'])) ? $aValue['FTXphApvName'] : '-' ?>
                                    </td> -->
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                            <img class="xCNIconTable xCNIconDel <?php echo $tClassDisabled ?>" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" <?php echo $tOnclick ?> title="<?php echo $tTitle ?>">
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                            <?php if ($bIsApvOrCancel) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvPOCallPageEditDoc('<?= $aValue['FTXphDocNo'] ?>')">
                                            <?php } else { ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvPOCallPageEditDoc('<?php echo $aValue['FTXphDocNo'] ?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
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
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord  = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvPOModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
<!-- ======================================================================================================================================== -->

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvPOModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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
<!-- ======================================================================================================================================== -->
<?php include('script/jPurchaseOrderDataTable.php') ?>