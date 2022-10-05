<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="xCNCenter">
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/delivery/delivery','tDLVBchName')?></th>
						<th nowrap class="xCNTextBold" style="width:12%;"><?=language('document/delivery/delivery','tDLVDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?=language('document/delivery/delivery','tDLVDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;">เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:8%;">วันที่เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/delivery/delivery','tDLVStaApv')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?=language('document/delivery/delivery','tDLVCreateBy')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tDLVDocNo  = $aValue['FTXthDocNo'];
                                $tDLVBchCode  = $aValue['FTBchCode'];

                                if(!empty($aValue['FTXthStaApv']) || $aValue['FTXthStaDoc'] == 3){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tPAMCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoDLVDelDocSingle('".$nCurrentPage."','".$tDLVDocNo."','".$tDLVBchCode."')";
                                }

                                if ($aValue['FTXthStaDoc'] == 3) {
                                    $tClassStaDoc   = 'text-danger';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXthStaDoc'] == 1 && $aValue['FTXthStaApv'] == '') {
                                        $tClassStaDoc   = 'text-warning';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc   = 'text-success';
                                        $tStaDoc        = language('common/main/main', 'tStaDoc1');
                                    }
                                }

                            $bIsApvOrCancel = ($aValue['FTXthStaApv'] == 1 || $aValue['FTXthStaApv'] == 2) || ($aValue['FTXthStaDoc'] == 3 );
                            ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTXthDocNo']?>" data-name="<?=$aValue['FTXthDocNo']?>">
                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYDOC'];
                                    } 
                                ?>
                                <?php if($tKeepDocNo != $aValue['FTXthDocNo'] ) { ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap class="text-center" <?=$nRowspan?>>
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tDLVDocNo?>" data-bchcode="<?=$tDLVBchCode?>" <?=$tCheckboxDisabled;?>> 
                                                <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>

                                    <td nowrap class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTXthDocNo']))? $aValue['FTXthDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center" <?=$nRowspan?>><?=(!empty($aValue['FDXthDocDate']))? $aValue['FDXthDocDate'] : '-' ?></td>
                                <?php } ?>

                                <td nowrap class="text-left"><?=($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>

                                <?php if($tKeepDocNo != $aValue['FTXthDocNo'] ) { ?>
                                    <td nowrap class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus <?=$tClassStaDoc;?>">
                                            <?=$tStaDoc;?>
                                        </label>
                                    </td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                            <img
                                                class="xCNIconTable xCNIconDel <?=$tClassDisabled?>"
                                                src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?=$tOnclick?>
                                                title="<?=$tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                            <?php if($bIsApvOrCancel) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvDLVCallPageEdit('<?= $aValue['FTXthDocNo'] ?>')">
                                            <?php }else{ ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvDLVCallPageEdit('<?=$aValue['FTXthDocNo']?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXthDocNo']; ?>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
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
<div id="odvDLVModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvDLVModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jDeliveryDataTable.php')?>
