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
                        <th nowrap class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTBBchCreate')?></th>
						<th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOTBDocNo')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOTBDocDate')?></th>
                        <th nowrap class="xCNTextBold" width="10%">ลูกค้า</th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOLabelFrmRefIntDoc')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOLabelFrmRefIntDocDate')?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?=language('document/saleorder/saleorder','tSOAdvSearchStaDoc')?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?=language('document/saleorder/saleorder','tSOStaSale')?></th>
                        <th nowrap class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTBCreateBy')?></th>
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
                                $tSODocNo  = $aValue['FTXshDocNo'];
                                if(!empty($aValue['FTXshStaApv']) || $aValue['FTXshStaDoc'] == 3){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoSODelDocSingle('".$nCurrentPage."','".$tSODocNo."')";
                                }

                                //สถานะเอกสาร
                                if($aValue['FTXshStaDoc'] == 3){
                                    $tClassStaApv   = 'text-danger';
                                    $tTextStatus    = 'ยกเลิก';
                                }else{
                                    if($aValue['FTXshStaApv'] == 1){
                                        $tClassStaApv   = 'text-success';
                                        $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv1');
                                    }else  if($aValue['FTXshStaDoc'] == 3){
                                        $tClassStaApv   = 'text-danger';
                                        $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv3');
                                    }else if(($aValue['FTXshStaPrcDoc'] == 1 || $aValue['FTXshStaPrcDoc'] == '') && $aValue['FTXshStaApv'] == ''){
                                        $tClassStaApv   = 'text-warning';  
                                        $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv');
                                    }else if($aValue['FTXshStaPrcDoc'] == 5 && $aValue['FTXshStaApv'] == ''){
                                        $tClassStaApv   = 'text-warning';  
                                        $tTextStatus    = language('document/saleorder/saleorder','รอจัดสินค้า');
                                    }else if($aValue['FTXshStaPrcDoc'] == 6 && $aValue['FTXshStaApv'] == ''){
                                        $tClassStaApv   = 'text-warning';  
                                        $tTextStatus    = language('document/saleorder/saleorder','จัดแล้วบางส่วน'); 
                                    }else if($aValue['FTXshStaPrcDoc'] == 7 && $aValue['FTXshStaApv'] == ''){
                                        $tClassStaApv   = 'text-warning';  
                                        $tTextStatus    = language('document/saleorder/saleorder','จัดครบแล้วรออนุมัติ');
                                    }else{
                                        $tClassStaApv   = '';
                                        $tTextStatus    = '';
                                    }
                                }

                                //สถานะอ้างอิงใบขาย
                                if($aValue['FTXshStaDoc'] == 3){
                                    $tClassStaSale      = 'text-danger';
                                    $tStaSale           = 'ยกเลิก';
                                }else{
                                    if($aValue['SALEABB'] != '' || $aValue['SALEABB'] != null){
                                        $tClassStaSale  = 'text-success';
                                        $tStaSale       = language('document/saleorder/saleorder','tSOSaled');
                                    }else{
                                        $tClassStaSale  = 'text-warning';  
                                        $tStaSale       = language('document/saleorder/saleorder','tSOWaitSale');  
                                    }
                                }
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" data-code="<?=$aValue['FTXshDocNo']?>" data-name="<?=$aValue['FTXshDocNo']?>">
                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYDOC'];
                                    } 
                                ?>
                                <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td <?=$nRowspan?> nowrap class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" <?=$tCheckboxDisabled;?>>
                                                <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-center"><?=(!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTCstName']))? $aValue['FTCstName'] : '-' ?></td>
                                <?php } ?>

                                <!--เอกสารอ้างอิง-->
                                <td nowrap class="text-left"><?=($aValue['DOCREF'] == '') ? '-' : $aValue['DOCREF']?></td>
                                <td nowrap class="text-center"><?=($aValue['DATEREF'] == '') ? '-' : $aValue['DATEREF']?></td>
                                
                                <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                    <td <?=$nRowspan?> nowrap class="text-left"><label class="xCNTDTextStatus <?=$tClassStaApv;?>"><?=$tTextStatus?></label></td>
                                    <td <?=$nRowspan?> nowrap class="text-left"><label class="xCNTDTextStatus <?=$tClassStaSale;?>"><?=$tStaSale?></label></td>
                                    <td <?=$nRowspan?> nowrap class="text-left"><?=(!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?></td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td <?=$nRowspan?> nowrap >
                                            <img
                                                class="xCNIconTable xCNIconDel <?=$tClassDisabled?>"
                                                src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?=$tOnclick?>
                                                title="<?=$tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <?php if ($aValue['FTXshStaDoc'] == 1 && $aValue['FTXshStaApv'] == '') { ?>
                                            <td <?=$nRowspan?> nowrap>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvSOCallPageEditDoc('<?=$aValue['FTXshDocNo']?>')">
                                            </td>
                                        <?php }else{ ?>
                                            <td <?=$nRowspan?> nowrap>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvSOCallPageEditDoc('<?=$aValue['FTXshDocNo']?>')">
                                            </td>
                                        <?php } ?>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXshDocNo']; ?>
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
        <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPIPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvSOClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvSOClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvSOClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvSOModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
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
<div id="odvSOModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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

<?php include('script/jSaleOrderDataTable.php')?>

