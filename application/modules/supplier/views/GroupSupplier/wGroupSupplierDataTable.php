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
            <table id="otbSgpDataList" class="table table-striped"> <!-- เปลี่ยน -->
                <thead>
                    <tr>
                        <th nowrap class="xCNTextBold text-center" style="width:5%;">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                        <th class="text-center xCNTextBold" style="width:15%;"><?= language('supplier/groupsupplier/groupsupplier','tSGPCode')?></th>
                        <th class="text-center xCNTextBold"><?= language('supplier/groupsupplier/groupsupplier','tSGPName')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('supplier/groupsupplier/groupsupplier','tSGPDelete')?></th>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('supplier/groupsupplier/groupsupplier','tSGPEdit')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aSgpDataList['rtCode'] == 1 ):?>
                        <?php foreach($aSgpDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-left xCNTextDetail2 otrGroupSupplier" id="otrGroupSupplier<?=$nKey?>" data-code="<?=$aValue['rtSgpCode']?>" data-name="<?=$aValue['rtSgpName']?>">
                              <?php

                                  if($aValue['rtSgpCodeLef'] != ''){
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
                                    data-checkrowid="<?php echo $aValue['rtSgpCode'].$aValue['rtAgnCode']?>" checked="false"  class="ocbListItem" name="ocbListItem[]">
                  									<span class="<?php echo $tDisabledItem2; ?>">&nbsp;</span>
                  								</label>
                  							</td>
                                <td><?=$aValue['rtSgpCode']?></td>
                                <td class="text-left"><?=$aValue['rtSgpName']?></td>

                                <td class="<?=$tDisableTD?> text-center" id="otdDel<?php echo $aValue['rtSgpCode'].$aValue['rtAgnCode']?>">
                                    <img id="oimDel<?php echo $aValue['rtSgpCode'].$aValue['rtAgnCode']; ?>" class="xCNIconTable <?php echo $tDisableImg; ?>" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>"  onClick="JSoGroupSupplierDel('<?php echo $aValue['rtSgpCode']?>','<?php echo $aValue['rtSgpName']?>')" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>">
                                </td>
                                <td class="text-center">
                                    <!-- เปลี่ยน -->
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageGroupSupplierEdit('<?php echo $aValue['rtSgpCode']?>')">
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='5'><?= language('supplier/groupsupplier/groupsupplier','tSGPNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <!-- เปลี่ยน -->
    <div class="col-md-6">

        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aSgpDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aSgpDataList['rnCurrentPage']?> / <?=$aSgpDataList['rnAllPage']?></p>
    </div>
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageGroupSupplier btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ -->
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvGroupSupplierClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aSgpDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ -->
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
                <button onclick="JSvGroupSupplierClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aSgpDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvGroupSupplierClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ -->
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
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
