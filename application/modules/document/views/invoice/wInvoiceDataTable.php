<?php
    if($aDataList['rtCode'] == '1'){ $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{ $nCurrentPage = '1'; }
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
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','tIVTitlePanelConditionAD')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBBchCreate')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTBranchTo')?></th>
						<th class="xCNTextBold" style="width:8%;"><?=language('document/adjuststock/adjuststock','tASTTBDocNo')?></th>
                        <th class="xCNTextBold" style="width:7%;"><?=language('document/adjuststock/adjuststock','tASTTBDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;">เอกสารอ้างอิง</th>
                        <th nowrap class="xCNTextBold" style="width:7%;">วันที่เอกสารอ้างอิง</th>
                        <th class="xCNTextBold"><?=language('document/invoice/invoice','tIVTitleBuySPL')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBStaDoc')?></th>
                        <!-- <th nowrap class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPITBStaPrc')?></th> -->
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBCreateBy')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBApvBy')?></th>

                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th class="xCNTextBold" style="width:5%;"><?= language('common/main/main','tCMNActionDelete')?></th>
                        <?php endif; ?>
                        
                        <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						    <th class="xCNTextBold" style="width:5%;"><?= language('common/main/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                    <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php $tKeepDocNo = ''; ?>
                    <?php 
                        if(FCNnHSizeOf($aDataList['raItems'])!=0){
                            foreach($aDataList['raItems'] AS $nKey => $aValue):?>
                                <?php
                                    $tQTDocNo  =   $aValue['FTXphDocNo'];
                                    if($aValue['FTXphStaApv'] == 1 || $aValue['FTXphStaApv'] == 2 || $aValue['FTXphStaDoc'] == 3){
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = "xCNDocDisabled";
                                        $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                        $tOnclick           = '';
                                    }else{
                                        $tCheckboxDisabled  = "";
                                        $tClassDisabled     = '';
                                        $tTitle             = '';
                                        $tOnclick           = "onclick=JSoIVDelDocSingle('".$nCurrentPage."','".$tQTDocNo."')";
                                    }
                                    
                                    // เช็ค Text Color
                                    if($aValue['FTXphStaDoc'] == 3){
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'tStaDoc3');
                                    }else if(!empty($aValue['FTXphStaApv'])){
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1'); 
                                    }else{
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }

                                    // เช็ค Text Color
                                    if ($aValue['FTXphStaPrcDoc'] == 1) {
                                        $tClassPrcStk = 'text-success';
                                        $tStaPrcDoc = language('document/purchaseinvoice/purchaseinvoice', 'tPITBStaPrc1');
                                    } else if ($aValue['FTXphStaPrcDoc'] == 2) {
                                        $tClassPrcStk = 'text-warning';
                                        $tStaPrcDoc = language('document/purchaseinvoice/purchaseinvoice', 'tPITBStaPrc2');
                                    } else if ($aValue['FTXphStaPrcDoc'] == 0 || $aValue['FTXphStaPrcDoc'] == '') {
                                        if($aValue['FTXphStaDoc'] == 3){ //ถ้ายกเลิก
                                            $tClassPrcStk = 'text-danger';
                                            $tStaPrcDoc = language('common/main/main', 'tStaDoc3');
                                        }else{
                                            $tClassPrcStk = 'text-warning';
                                            $tStaPrcDoc = language('document/purchaseinvoice/purchaseinvoice', 'tPITBStaPrc3');
                                        }
                                    }

                                    $bIsApvOrCancel = ($aValue['FTXphStaApv'] == 1 || $aValue['FTXphStaApv'] == 2) || ($aValue['FTXphStaDoc'] == 3 );

                                ?>
                                <tr id="otrQT<?=$nKey?>" class="text-center xCNTextDetail2 otrQT" data-code="<?=$aValue['FTXphDocNo']?>" data-name="<?=$aValue['FTXphDocNo']?>">
                                    <?php  
                                        //รวมคอลัมน์
                                        if($aValue['PARTITIONBYDOC'] == 1 || $aValue['PARTITIONBYDOC'] == 0){
                                            $nRowspan   = '';
                                        }else{
                                            $nRowspan   = "rowspan=".$aValue['PARTITIONBYDOC'];
                                        } 
                                    ?>
                                    <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td class="text-center" <?=$nRowspan?>>
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" <?=$tCheckboxDisabled;?>>
                                                <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTAgnName']))? $aValue['FTAgnName'] : '-' ?></td>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTBchName']))? $aValue['FTBchName'] : '-' ?></td>
                                    <?php if(!empty($aValue['BchNameTo'])){ ?>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['BchNameTo']))? $aValue['BchNameTo'] : '-' ?></td>
                                    <?php }else{?>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['BchNameToDO']))? $aValue['BchNameToDO'] : '-' ?></td>
                                    <?php } ?>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTXphDocNo']))? $aValue['FTXphDocNo'] : '-' ?></td>
                                    <td class="text-center" <?=$nRowspan?>><?=(!empty($aValue['FDXphDocDate']))? $aValue['FDXphDocDate'] : '-' ?></td>
                                    <?php } ?>
                                    <td class="text-left"><?=(!empty($aValue['DocRefIn']))? $aValue['DocRefIn'] : '-' ?></td>
                                    <td class="text-center"><?=(!empty($aValue['DateRefIn']))? $aValue['DateRefIn'] : '-' ?></td>
                                    <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                    <td class="text-left" <?=$nRowspan?>><?=(!empty($aValue['FTSplName']))? $aValue['FTSplName'] : '-' ?></td>
                                    <td class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus <?=$tClassStaDoc;?>">
                                            <?=$tStaDoc?>
                                        </label>
                                    </td>
                                    <!-- <td nowrap class="text-left" <?=$nRowspan?>>
                                        <label class="xCNTDTextStatus <?=$tClassPrcStk;?>">
                                            <?=$tStaPrcDoc; ?>
                                        </label>
                                    </td> -->
                                    <td class="text-left" <?=$nRowspan?>>
                                        <?=(!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?>
                                    </td>
                                    <td class="text-left" <?=$nRowspan?>>
                                        <?=(!empty($aValue['FTXphStaApv']))? $aValue['FTXphApvName'] : '-' ?>
                                    </td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td <?=$nRowspan?>>
                                            <img
                                                class="xCNIconTable xCNIconDel <?=$tClassDisabled?>"
                                                src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?= $tOnclick?>
                                                title="<?=$tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td nowrap <?=$nRowspan?>>
                                        <?php if($bIsApvOrCancel) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onClick="JSvIVCallPageEdit('<?= $aValue['FTXphDocNo'] ?>')">
                                            <?php }else{ ?>
                                                <img class="xCNIconTable xCNIconEdit" onClick="JSvIVCallPageEdit('<?php echo $aValue['FTXphDocNo']?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php } ?>
                                </tr>
                                <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
                            <?php endforeach;
                        } else{ ?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php } ?>
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
    <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageIVPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvIVClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvIVClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvIVClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div> -->
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvIVModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmIVConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvIVModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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

<script type="text/javascript">
    //ลบหลายตัว
    $('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //name
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxTextinModal();
        }else{
            var aReturnRepeat = JStIVFindObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxTextinModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nCode == nCode){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })

    $('#odvIVModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSoIVDelDocMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
</script>