<?php
    if($aDataList['rtCode'] == '1'){ $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{ $nCurrentPage = '1'; }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped">
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
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBBchCreate')?></th>
						<th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBDocNo')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBDocDate')?></th>
                        <th class="xCNTextBold"><?=language('document/adjuststock/adjuststock','tASTTBStaDoc')?></th>
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
                    <?php 
                        if(FCNnHSizeOf($aDataList['raItems'])!=0){
                            foreach($aDataList['raItems'] AS $nKey => $aValue):?>
                                <?php
                                    $tQTDocNo  =   $aValue['FTXshDocNo'];
                                    $tQTBchCode  =   $aValue['FTBchCode'];
                                    if($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2 || $aValue['FTXshStaDoc'] == 3){
                                        $tCheckboxDisabled  = "disabled";
                                        $tClassDisabled     = "xCNDocDisabled";
                                        $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                        $tOnclick           = '';
                                    }else{
                                        $tCheckboxDisabled  = "";
                                        $tClassDisabled     = '';
                                        $tTitle             = '';
                                        $tOnclick           = "onclick=JSoQTDelDocSingle('".$nCurrentPage."','".$tQTDocNo."','".$tQTBchCode."')";
                                    }
                                    
                                    // เช็ค Text Color FTXthStaDoc
                                    if($aValue['FTXshStaDoc'] == 3){
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'tStaDoc3');
                                    }else if(!empty($aValue['FTXshStaApv'])){
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1'); 
                                    }else{
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }

                                ?>
                                <tr id="otrQT<?=$nKey?>" class="text-center xCNTextDetail2 otrQT" data-code="<?=$aValue['FTXshDocNo']?>" data-name="<?=$aValue['FTXshDocNo']?>">
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td class="text-center">
                                            <label class="fancy-checkbox ">
                                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" <?=$tCheckboxDisabled;?>>
                                                <span class="<?=$tClassDisabled?>">&nbsp;</span>
                                            </label>
                                        </td>
                                    <?php endif; ?>
                                    <td class="text-left"><?=(!empty($aValue['FTBchName']))? $aValue['FTBchName'] : '-' ?></td>
                                    <td class="text-left"><?=(!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                    <td class="text-center"><?=(!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                    <td class="text-left">
                                        <label class="xCNTDTextStatus <?=$tClassStaDoc;?>"><?=$tStaDoc?></label>
                                    </td>
                                    <td class="text-left">
                                        <?=(!empty($aValue['FTCreateByName']))? $aValue['FTCreateByName'] : '-' ?>
                                    </td>
                                    <td class="text-left">
                                        <?=(!empty($aValue['FTXshStaApv']))? $aValue['FTXshApvName'] : '-' ?>
                                    </td>
                                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                                        <td>
                                            <img
                                                class="xCNIconTable xCNIconDel <?=$tClassDisabled?>"
                                                src="<?=base_url().'/application/modules/common/assets/images/icons/delete.png'?>"
                                                <?= $tOnclick?>
                                                title="<?=$tTitle?>"
                                            >
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                                        <td>
                                            <?php if ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaDoc'] == 3) { ?>
                                                <img class="xCNIconTable" style="width: 17px;" src="<?= base_url() . '/application/modules/common/assets/images/icons/view2.png' ?>" onClick="JSvQTCallPageEdit('<?= $aValue['FTXshDocNo'] ?>')">
                                            <?php } else { ?>
                                                <img class="xCNIconTable" src="<?= base_url() . '/application/modules/common/assets/images/icons/edit.png' ?>" onClick="JSvQTCallPageEdit('<?= $aValue['FTXshDocNo'] ?>')">
                                            <?php } ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
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
        <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageQTPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvQTClickPageList('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                <button onclick="JSvQTClickPageList('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvQTClickPageList('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Single ===================================================== -->
<div id="odvQTModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmQTConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?=language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== Modal Delete Document Multiple =================================================== -->
<div id="odvQTModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
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
            var aReturnRepeat = JStQTFindObjectByKey(aArrayConvert[0],'nCode',nCode);
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

    $('#odvQTModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSoQTDelDocMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
</script>