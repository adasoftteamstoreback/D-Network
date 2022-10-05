<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="xCNTextBold text-center" style="width:5%;"><?= language('customerlicense/customerlicense/customerlicense','tCLBCstSeq')?></th>
                        <th class="xCNTextBold text-left" style="width:15%;"><?= language('customer/customer/customer','tCSTBranchCode')?></th>
                        <th class="xCNTextBold text-left" style="width:20%;"><?= language('customer/customer/customer','tCSTBranchName')?></th>
                        <th class="xCNTextBold text-left" style="width:20%;"><?= language('customer/customer/customer','tCSTBranchRegNo')?></th>
                        <th class="xCNTextBold text-left" style="width:20%;"><?= language('customer/customer/customer','tCSTBranchShipto')?></th>
                        <th class="xCNTextBold text-left" style="width:10%;"><?= language('customer/customer/customer','tCstStaActive')?></th>
                        <th class="xCNTextBold text-center" style="width:5%;"><?= language('customerlicense/customerlicense/customerlicense','tCSTEdit')?></th>
                        <th class="xCNTextBold text-center" style="width:5%;"><?= language('customerlicense/customerlicense/customerlicense','tCSTDelete')?></th>
                    </tr>
                </thead>
                <tbody id="">
                        <?php
                            if(!empty($aDataList['raItems'])){
                                foreach($aDataList['raItems'] as $nKey => $aData){
                        ?>
                        <tr class="text-center xCNTextDetail2 otrCustomer" >
                            <td class="text-center"><?$aData['rnCbrSeq']?><?=($nKey+1)?></td>
                            <td class="text-left"><?=$aData['FTCbrBchCode']?></td>
                            <td class="text-left"><?=$aData['FTCbrBchName']?></td>
                            <td class="text-left"><?=$aData['FTCbrRegNo']?></td>
                            <td class="text-left"><?=$aData['FTCbrShipTo']?></td>
                            <td class="text-left">
                                <?php 
                                    $tStatus = '';
                                    if ($aData['FTCbrStatus'] == 1) {
                                        $tStatus = 'ใช้งาน';
                                    }else{
                                        $tStatus = 'ไม่ใช้งาน';
                                    }
                                ?>
                                <?= language('customerlicense/customerlicense/customerlicense', $tStatus)?>
                            </td>
                            <td>
                                <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSaCstBchCallEditEvent(<?=$aData['rnCbrSeq']?>)">
                            </td>
                            <td>
                                <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSaCstBchCallDelete(<?=$aData['rnCbrSeq']?>, '<?=$aData['FTCbrBchName']?>')">
                            </td>
                        </tr>
               
                        <?php 
                                }
                            }else{ ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='8'><?= language('common/main/main','tCMNNotFoundData')?> </td></tr>
                      <?php } ?>
                </tbody>
			</table>
        </div>
    </div>
</div>

<div class="row">
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <p><?php echo language('common/main/main','tResultTotalRecord');?> <?=$aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord');?> <?php echo language('common/main/main','tCurrentPage');?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPageCstBch btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvCLBClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
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
                <button onclick="JSvCLBClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCLBClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<script>

/**
 * Functionality : Delete one select
 */
function JSaCstBchCallDelete(pnCstBchSeq,pnCstBchName){
    try{
   
        $('#ohdCstBchConfirmIDDelete').val(pnCstBchSeq);
        $('#oetTextComfirmDel').text($('#oetTextComfirmDel').val() + " " + pnCstBchSeq + " " + pnCstBchName);
        $('#odvCstBchModalDel').modal('show');

    
    }catch(err){
        console.log('JSaCLNDelete Error: ', err);
    }
}


/**
 * Functionality : Delete one select
 */
function JSaCstBchDelete(){

    var oDataCstBch = {
        tCstCode : $('#ohdCstCode').val(),
        nCstBchSeq :$('#ohdCstBchConfirmIDDelete').val()
    }
    $.ajax({
          type: "POST",
          url: "customerBchDelete",
          cache: false,
          timeout: 0,
          data:oDataCstBch,
          success: function(tResult) {
            var aReturn = JSON.parse(tResult);
            $('#odvCstBchModalDel').modal('hide');
            if(aReturn['nStaEvent']=='01'){
                JSvCstBchGetDataTable();
            }else{
                FSvCMNSetMsgSucessDialog('Error');
            }
      
          },
          error: function(jqXHR, textStatus, errorThrown) {
              JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
          }
      });

}
</script>
