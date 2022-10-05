<?php
    $nCurrentPage   = '1';
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="" class="table">
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
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRBchName')?></th>
						<th nowrap class="xCNTextBold" style="width:12%;"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:12%;">เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:8%;">วันที่เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRCstName')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRDepositBy')?></th>
                        <th nowrap class="xCNTextBold" ><?php echo language('document/depositboxreservation/depositboxreservation','tDBRStaApv')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRCreateBy')?></th>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tDBRDocNo  = $aValue['FTXshDocNo'];
                                $tDBRBchCode  = $aValue['FTBchCode'];
                                $tDBRRefInCode  = $aValue['DBRCREF'];
                                
                                if(!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3){
                                    $tCheckboxDisabled = "disabled";
                                    $tClassDisabled = 'xCNDocDisabled';
                                    $tTitle = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick = '';
                                }else{
                                    $tCheckboxDisabled = "";
                                    $tClassDisabled = '';
                                    $tTitle = '';
                                    $tOnclick = "onclick=JSoDBRDelDocSingle('".$nCurrentPage."','".$tDBRDocNo."','".$tDBRBchCode."','".$tDBRRefInCode."')";
                                }

                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXshStaDoc'] == 1 && $aValue['FTXshStaApv'] == '') {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }
                                }
                               
                            $bIsApvOrCancel = ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2) || ($aValue['FTXshStaDoc'] == 3 );
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice<?php echo $nKey?>" data-code="<?php echo $aValue['FTXshDocNo']?>" data-name="<?php echo $aValue['FTXshDocNo']?>">
                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYDBRC'] == 1 || $aValue['PARTITIONBYDBRC'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYDBRC'];
                                    } 
                                ?>
                                <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap class="text-center" <?=$nRowspan?>>
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tDBRDocNo?>" data-bchcode="<?=$tDBRBchCode?>" data-refcode="<?=$tDBRRefInCode?>" <?php echo $tCheckboxDisabled;?>>
                                                <span class="<?php echo $tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>

                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center" <?=$nRowspan?>><?php echo (!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                <?php } ?>
                                <td nowrap class="text-left"><?=($aValue['DBRCREF'] == '') ? '-' : $aValue['DBRCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>

                                <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTCstName']))? $aValue['FTCstName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTDepositByName']))? $aValue['FTDepositByName'] : '-' ?></td>
                                    <td nowrap class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                            <?php echo $tStaDoc;?>
                                        </label>
                                    </td>
                                    <td nowrap class="text-left" <?=$nRowspan?>><?php echo (!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                            <img
                                                class="xCNIconTable xCNIconDel <?php echo $tClassDisabled?>"
                                                src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?php echo $tOnclick?>
                                                title="<?php echo $tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>
                                
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                        <?php if($bIsApvOrCancel) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvDBRCallPageEdit('<?= $aValue['FTXshDocNo'] ?>')">
                                            <?php }else{ ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvDBRCallPageEdit('<?php echo $aValue['FTXshDocNo']?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXshDocNo']; ?>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
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
    <div id="odvDBRModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                </div>
                <div class="modal-footer">
                    <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ======================================================================================================================================== -->

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
    <div id="odvDBRModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                    <input type='hidden' id="ohdConfirmIDDelMultiple">
                </div>
                <div class="modal-footer">
                    <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </div>
        </div>
    </div>        
<!-- ======================================================================================================================================== -->
<?php include('script/jDepositBoxReservationDataTable.php')?>

