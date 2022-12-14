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
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                        <th class="xCNTextBold text-center" style="width:15%;"><?= language('customer/customerType/customertype','tCstTypeTBCode')?></th>
                        <th class="xCNTextBold text-center" style="width:70%;"><?= language('customer/customerType/customerType','tCstTypeTBName')?></th>
                        <th class="xCNTextBold text-center" style="width:5%;"><?= language('customer/customerType/customerType','tCstTypeTBDelete')?></th>
                        <th class="xCNTextBold text-center" style="width:5%;"><?= language('customer/customerType/customerType','tCstTypeTBEdit')?></th>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2 otrCstType" id="otrCstType<?=$key?>" data-code="<?=$aValue['rtCstTypeCode']?>" data-name="<?=$aValue['rtCstTypeName']?>">
                          <?php
                              if($aValue['rtCstCtyCodeLef'] != ''){
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
              									<input id="ocbListItem<?php echo $key; ?>" type="checkbox"
                                <?php echo $tDisabledItem; ?>
                                onchange="JSxVisibledDelAllBtn(this, event)"
                                data-checkrow="<?php echo $tDisabledcheckrow; ?>"
                                data-checkrowid="<?php echo $aValue['rtCstTypeCode'].$aValue['rtCstAgnCode']?>" checked="false"  class="ocbListItem" name="ocbListItem[]">
              									<span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
              								</label>
              							</td>
                            <td class="text-left otdCstTypeCode"><?=$aValue['rtCstTypeCode']?></td>
                            <td class="text-left otdCstTypeName"><?=$aValue['rtCstTypeName']?></td>
                            <td class="<?=$tDisableTD?>" id="otdDel<?php echo $aValue['rtCstTypeCode'].$aValue['rtCstAgnCode']?>">
                                <img id="oimDel<?php echo $aValue['rtCstTypeCode'].$aValue['rtCstAgnCode']; ?>" class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSaCstTypeDelete(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
                            </td>
                            <td>
                                <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageCstTypeEdit('<?=$aValue['rtCstTypeCode']?>')" title="<?php echo language('customer/customerType/customerType', 'tCstTypeTBEdit'); ?>">
                            </td>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('customer/customerType/customerType','tCstTypeSearch')?></td></tr>
                <?php endif;?>
                </tbody>
			</table>
        </div>
    </div>
</div>

<div class="row">
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?>  <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <div class="xWPageCstType btn-toolbar pull-right"> <!-- ????????????????????????????????? Class ?????????????????????????????????????????????????????? -->
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSCstTypevClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- ????????????????????????????????? Parameter Loop ?????????????????????????????????????????????????????? -->
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <button onclick="JSCstTypevClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSCstTypevClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? -->
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<script type="text/javascript">
$('ducument').ready(function(){
  $('.ocbListItem').prop('checked',false);
});
</script>
