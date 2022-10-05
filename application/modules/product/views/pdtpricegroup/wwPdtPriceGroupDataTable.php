<?php
    if($aPplDataList['rtCode'] == '1'){
        $nCurrentPage = $aPplDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
 <style>
    .xWTdDisable {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }

    .xWImgDisable {
        pointer-events: none;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?=$nCurrentPage?>">
        <div class="table-responsive">
            <table id="otbPplDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || $aAlwEventPdtPrice['tAutStaDelete'] == 1) : ?>
                            <th nowrap class="xCNTextBold text-center" style="width:5%;">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </th>
                        <?php endif; ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdtpricelist/pdtpricelist','tPPLTBCode')?></th>
                        <th class="text-center xCNTextBold" style="width:60%;"><?= language('product/pdtpricelist/pdtpricelist','tPPLTBName')?></th>
                        <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || $aAlwEventPdtPrice['tAutStaDelete'] == 1) : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdtpricelist/pdtpricelist','tPPLTBDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || ($aAlwEventPdtPrice['tAutStaEdit'] == 1 || $aAlwEventPdtPrice['tAutStaRead'] == 1))  : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdtpricelist/pdtpricelist','tPPLTBEdit')?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aPplDataList['rtCode'] == 1 ):?>
                        <?php foreach($aPplDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2 otrPdtPrice" id="otrPdtPrice<?=$nKey?>" data-code="<?=$aValue['rtPplCode']?>" data-name="<?=$aValue['rtPplName']?>">
                                <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || $aAlwEventPdtPrice['tAutStaDelete'] == 1) : ?>
                                  <?php

                                      if($aValue['rtPplCodeLef'] != ''){
                                          $tDisableTD     = "xWTdDisable";
                                          $tDisableImg    = "xWImgDisable";
                                          $tDisabledItem  = "disabled ";
                                          $tDisabledItem2  = "xCNDisabled ";
                                          $tDisabledcheckrow  = "true";
                                      }else{
                                          $tDisableTD     = "";
                                          $tDisableImg    = "";
                                          $tDisabledItem  = "";
                                          $tDisabledItem2  = " ";
                                          $tDisabledcheckrow  = "false";
                                      }
                                  ?>

                                <td class="text-center">
                  								<label class="fancy-checkbox">
                  									<input id="ocbListItem<?php echo $nKey; ?>" type="checkbox"
                                    <?php echo $tDisabledItem; ?>
                                    data-checkrow="<?php echo $tDisabledcheckrow; ?>"
                                    data-checkrowid="<?php echo $aValue['rtPplCode'].$aValue['rtAgnCode']?>" checked="false"  class="ocbListItem" name="ocbListItem[]">
                  									<span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
                  								</label>
                  							</td>
                                <?php endif; ?>
                                <td><?=$aValue['rtPplCode']?></td>
                                <td class="text-left"><?=$aValue['rtPplName']?></td>
                                <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || $aAlwEventPdtPrice['tAutStaDelete'] == 1) : ?>
                                <td class="<?=$tDisableTD?>" id="otdDel<?php echo $aValue['rtPplCode'].$aValue['rtAgnCode'];?>">
                                    <img id="oimDel<?php echo $aValue['rtPplCode'].$aValue['rtAgnCode']; ?>" class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtPriceDel('<?=$nCurrentPage?>','<?=$aValue['rtPplName']?>','<?=$aValue['rtPplCode']?>','<?= language('common/main/main','tModalConfirmDeleteItemsYN')?>')">
                                </td>
                                <?php endif; ?>
                                <?php if($aAlwEventPdtPrice['tAutStaFull'] == 1 || ($aAlwEventPdtPrice['tAutStaEdit'] == 1 || $aAlwEventPdtPrice['tAutStaRead'] == 1))  : ?>
                                <td><img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPagePdtPriceEdit('<?php echo $aValue['rtPplCode']?>')"></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='5'><?= language('product/pdtpricelist/pdtpricelist','tPPLTBNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aPplDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aPplDataList['rnCurrentPage']?> / <?=$aPplDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPagePdtPrice btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ -->
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPdtPriceClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aPplDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ -->
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <button onclick="JSvPdtPriceClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aPplDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPdtPriceClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelPdtPrice">
	<div class="modal-dialog">
  		<div class="modal-content">
        	<div class="modal-header xCNModalHead">
    		<label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
   		</div>
        <div class="modal-body">
   			<span id="ospConfirmDelete"> - </span>
    		<input type='hidden' id="ohdConfirmIDDelete">
   		</div>
   		<div class="modal-footer">
    		<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoPdtPriceDelChoose('<?=$nCurrentPage?>')">
     			<?=language('common/main/main', 'tModalConfirm')?>
    		</button>
    		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
     			<?=language('common/main/main', 'tModalCancel')?>
    		</button>
   		</div>
  	</div>
</div>


<script type="text/javascript">
    $('ducument').ready(function(){
        JSxShowButtonChoose();
      $('.ocbListItem').prop('checked',false);

    })
    $('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //code
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
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
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
</script>
