<?php 
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
    $bIsView = $tIsView == '1' ? true : false;
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th class="xCNTextBold" width="30%"><?=language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaChannelName')?></th>
                        <th class="xCNTextBold" width="20%"><?=language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaRow')?></th>
                        <th class="xCNTextBold" width="20%"><?=language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaCol')?></th>
                        <th class="xCNTextBold" width="20%"><?=language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaAdjSta')?></th>
                        <?php if(!$bIsView) { ?>
                        <th class="xCNTextBold" width="10%"><?=language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaDelete')?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php if($aDataList['rtCode'] == 1 ) { ?>
                    <input type="hidden" id="ohdSMLKAdjStaTempRows" value="<?php echo FCNnHSizeOf($aDataList['raItems']); ?>">
                    <?php foreach($aDataList['raItems'] AS $key => $aItem) { ?>
                        <tr 
                            class="text-center xWRackChannelTemp"
                            data-rack-bchcode="<?=$aItem['FTBchCode']?>"
                            data-rack-mercode="<?=$aItem['FTMerCode']?>"
                            data-rack-shpcode="<?=$aItem['FTShpCode']?>"
                            data-rack-layno="<?=$aItem['FNLayNo']?>"
                            data-rack-layrow="<?=$aItem['FNLayRow']?>"
                            data-rack-laycol="<?=$aItem['FNLayCol']?>"
                            data-rack-laystause="<?=$aItem['FTLayStaUse']?>">
                            
                            <td class="text-center"><?=$aItem['FTLayName']?></td>
                            <td class="text-center"><?=$aItem['FNLayRow']?></td>
                            <td class="text-center"><?=$aItem['FNLayCol']?></td>
                            <?php 
                            $tLayStaUse = '';
                            if($aItem['FTLayStaUse'] == '1'){$tLayStaUse = language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaEmpty');}
                            if($aItem['FTLayStaUse'] == '2'){$tLayStaUse = language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaUse');}
                            if($aItem['FTLayStaUse'] == '3'){$tLayStaUse = language('company/smart_locker_adjust_status/smartlockeradjsta', 'tSMLKAdjStaDisable');}
                            ?>
                            <td class="text-center"><?=$tLayStaUse?></td>
                            
                            <?php if(!$bIsView) { ?>
                            <td class="text-center">
                                <img class="xCNIconTable xCNIconDel" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" title="" onclick="JSvSMLKAdjStaDeleteRackChannelInTemp(this)">
                            </td>  
                            <?php } ?>
                            
                        </tr>
                    <?php } ?>
                <?php }else {?>
                    <tr><td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if(false) { ?>
<div class="row">
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <p><?= language('common/main/main', 'tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <!-- ????????????????????? -->
    <div class="col-md-6">
        <div class="xWPage btn-toolbar pull-right"> <!-- ????????????????????????????????? Class ?????????????????????????????????????????????????????? --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvSMLKAdjStaTempDataTableClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'], $nPage+2)); $i++){?> <!-- ????????????????????????????????? Parameter Loop ?????????????????????????????????????????????????????? --> 
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
                <button onclick="JSvSMLKAdjStaTempDataTableClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvSMLKAdjStaTempDataTableClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- ????????????????????????????????? Onclick ?????????????????????????????????????????????????????? --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<?php } ?>
<?php include('script/jAdjustStatusTempDataTable.php'); ?>
