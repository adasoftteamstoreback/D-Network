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
            <table id="" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <?php //if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php //endif; ?>
                        <th nowrap class="xCNTextBold" style="width:11%;"><?php echo language('document/deliveryorder/deliveryorder','tDOBchName')?></th>
						<th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('document/deliveryorder/deliveryorder','tDODocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/deliveryorder/deliveryorder','tDODocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;"><?php echo language('document/deliveryorder/deliveryorder','tDODocRefIntNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/deliveryorder/deliveryorder','tDODocRefIntDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/deliveryorder/deliveryorder','tDOStaApv')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/deliveryorder/deliveryorder','tDOCreateBy')?></th>
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
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tSatSvAgnCode  = $aValue['FTAgnCode'];
                                $tSatSvBchCode  = $aValue['FTBchCode'];
                                $tSatSvDocCode  = $aValue['FTXshDocNo'];
                                
                                if(!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3){
                                    $tCheckboxDisabled = "disabled";
                                    $tClassDisabled = 'xCNDocDisabled';
                                    $tTitle = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick = '';
                                }else{
                                    $tCheckboxDisabled = "";
                                    $tClassDisabled = '';
                                    $tTitle = '';
                                    $tOnclick = "onclick=JSoSATDelDocSingle('".$nCurrentPage."','".$tSatSvDocCode."')";
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
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice<?php echo $nKey?>" data-code="<?php echo $tSatSvDocCode?>" data-name="<?php echo $tSatSvDocCode?>">
                                <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap class="text-center">
                                        <label class="fancy-checkbox ">
                                            <input id="ocbListItem<?php echo $nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" value="<?=$tSatSvDocCode?>" data-code="<?php echo $tSatSvDocCode?>" data-name="<?php echo $tSatSvDocCode?>" <?php echo $tCheckboxDisabled;?>>
                                            <span class="<?php echo $tClassDisabled?>">&nbsp;</span>
                                        </label>
                                    </td>
                                <?php endif; ?>

                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshRefDocNo']))? $aValue['FTXshRefDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshRefDocDate']))? $aValue['FDXshRefDocDate'] : '-' ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc;?>
                                    </label>
                                </td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                <?php //f($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                    <td nowrap >
                                        <img
                                            class="xCNIconTable xCNIconDel <?php echo $tClassDisabled?>"
                                            src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                            <?php echo $tOnclick?>
                                            title="<?php echo $tTitle?>"
                                        >
                                    </td>
                                <?php //endif; ?>
                                
                                <?php //if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                    <td nowrap>
                                    <?php if($bIsApvOrCancel) { ?>
                                            <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvSatSvCallPageEdit('<?= $aValue['FTAgnCode'] ?>','<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>')">
                                        <?php }else{ ?>
                                            <img class="xCNIconTable xCNIconEdit" onClick="JSvSatSvCallPageEdit('<?= $aValue['FTAgnCode'] ?>','<?= $aValue['FTBchCode'] ?>','<?= $aValue['FTXshDocNo'] ?>')">
                                        <?php } ?>
                                    </td>
                                <?php //endif; ?>
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
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPIPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvSATClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvSATClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvSATClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
    <div id="odvSATModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
    <div id="odvSATModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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
<?php include('script/jSatisfactionSurveyDataTable.php')?>

