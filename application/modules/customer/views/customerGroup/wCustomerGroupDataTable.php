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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                        <th class="xCNTextBold text-center" style="width:30%;"><?= language('customer/customerGroup/customerGroup','tCstGrpTBCode')?></th>
                        <th class="xCNTextBold text-center" style="width:30%;"><?= language('customer/customerGroup/customerGroup','tCstGrpTBName')?></th>
                        <th class="xCNTextBold text-center" style="width:10%;"><?= language('customer/customergroup/customerGroup','tCstGrpTBDelete')?></th>
                        <th class="xCNTextBold text-center" style="width:10%;"><?= language('customer/customerGroup/customerGroup','tCstGrpTBEdit')?></th>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2 otrCstGrp" id="otrCstGrp<?=$key?>" data-code="<?=$aValue['rtCstGrpCode']?>" data-name="<?=$aValue['rtCstGrpName']?>">

                            <?php

                                if($aValue['rtCstGrpCodeLef'] != ''){
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
                                data-checkrow="<?php echo $tDisabledcheckrow; ?>"
                                onchange="JSxCstGrpVisibledDelAllBtn(this, event)"
                                data-checkrowid="<?php echo $aValue['rtCstGrpCode'].$aValue['rtCstAgnCode']?>" checked="false"  class="ocbListItem" name="ocbListItem[]">
                                <span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
                              </label>
                            </td>
                            <td class="text-left otdCstGrpCode"><?=$aValue['rtCstGrpCode']?></td>
                            <td class="text-left otdCstGrpName"><?=$aValue['rtCstGrpName']?></td>
                            <td class="<?=$tDisableTD?>" id="otdDel<?php echo $aValue['rtCstGrpCode'].$aValue['rtCstAgnCode']?>">
                                <img id="oimDel<?php echo $aValue['rtCstGrpCode'].$aValue['rtCstAgnCode']; ?>" class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"  onClick="JSaCstGrpDelete(this, event)" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
                            </td>
                            <td>
                                <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageCstGrpEdit('<?=$aValue['rtCstGrpCode']?>')" title="<?php echo language('customer/customerGroup/customerGroup', 'tCstGrpTBEdit'); ?>">
                            </td>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('customer/customerType/customerType','tCstGrpSearch')?></td></tr>
                <?php endif;?>
                </tbody>
			</table>
        </div>
    </div>
</div>

<div class="row">
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?>  <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageCstGrp btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ -->
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvCstGrpClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ -->
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
                <button onclick="JSvCstGrpClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCstGrpClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
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
