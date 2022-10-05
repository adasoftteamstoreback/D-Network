<div class="table-responsive">
    <table class="table table-striped xWPdtTableFont" id="otbPromotionStep1PmtBrandDtTable">
        <thead>
            <tr>
                <th width="18%" class="text-left"><?php echo language('document/promotion/promotion', 'tTBDocNo'); ?></th>
                <th width="40%" class="text-left"><?php echo language('document/promotion/promotion', 'tTBDocDate'); ?></th>
                <th width="5%" class="text-center"><?php echo language('document/promotion/promotion', 'tTBDelete'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($aDataList['rtCode'] == 1) { ?>
                <?php foreach ($aDataList['raItems'] as $key => $aValue) { ?>
                    <?php $bIsShopAll = (empty($aValue['FTPmdRefCode']) && empty($aValue['FTPmdRefName']) && empty($aValue['FTPmdSubRefName']) && empty($aValue['FTPmdBarCode'])); ?>
                    <?php if(!$bIsShopAll) { ?>
                        <tr class="xCNTextDetail2 xCNPromotionPmtBrandDtRow" data-seq-no="<?php echo $aValue['FNPmdSeq']; ?>">
                            <td class="text-left xCNPromotionStep1PmtDtPmdRefCode"><?php echo $aValue['FTPmdRefCode']; ?></td>
                            
                                <td class="text-left xCNPromotionStep1PmtDtPmdRefName">
                                <div>
                                <span><?php echo $aValue['FTPmdRefName']; ?></span>
                                <?php if($aValue['FTPdtStaLot'] == '1') {?>
                                    <?php if(!isset($aValue['LotNumber'])){
                                        $aValue['LotNumber'] = '0'; 
                                    }?>
                                    <div class='row'>
                                        <div class="col-md-6">
                                            <span class="xWCheckEffectLot" style='font-weight: 700!important;' data-effectlot="<?php echo $aValue['LotNumber'];?>"><?php echo language('document/promotion/promotion', 'tPMTLotEffective'); ?> <?php echo substr($aValue['LotNumber'],2); ?></span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                        <a href="#"><span class="xWChangeLot" data-pdt-code="<?php echo $aValue['FTPmdRefCode'];?>" data-seq-no="<?php echo $aValue['FNPmdSeq']; ?>" onclick="JSxPromotionLotChange(this);"><?php echo language('document/promotion/promotion', 'tPMTLotChange'); ?></span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                </div>
                                </td>
                            <td class="text-center">
                                <img class="xCNIconTable xCNIconDel" onclick="JSxPromotionStep1PmtPridDtDataTableDeleteBySeq(this)" src="<?= base_url('application/modules/common/assets/images/icons/delete.png') ?>">
                            </td>
                        </tr>
                    <?php }else{ ?>
                        <tr>
                            <td class='text-center xCNTextDetail2 xCNPromotionStep1PmtDtShopAll' data-status="1" colspan='100%'><?= language('common/main/main', 'ทั้งร้าน') ?></td>
                        </tr>
                    <?php } ?>        
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
</div>

<?php if($aDataList['rnAllPage'] > 1) { ?>
    <div class="row xCNPromotionPmtPdtDtPage">
        <div class="col-md-6">
            <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
        </div>
        <div class="col-md-6">
            <div class="xWPage btn-toolbar pull-right">
                <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
                <button onclick="JSvPromotionStep1PmtPdtDtDataTableClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                    <button onclick="JSvPromotionStep1PmtPdtDtDataTableClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
                <?php } ?>
                <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
                <button onclick="JSvPromotionStep1PmtPdtDtDataTableClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                    <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
                </button>
            </div>
        </div>
    </div>
<?php } ?>

<script>
function JSxPromotionLotChange(evn){
    $('#odvPromotionAddPmtGroupModal').modal('hide');
    var tPdtCode = $(evn).data("pdt-code");
    var tSeqNo   = $(evn).data("seq-no");
    
    var aLot = new Array();
    var aLotSeq = new Array();
    aLot.push(tPdtCode);
    aLotSeq.push(tSeqNo);
    JSvPromotionLoadModalShowPdtLotDT(aLot,aLotSeq,0);
}
</script>

<?php
//  include('script/jStep1PmtPdtDtTableTmp.php'); 
?>
<?php include('script/jStep1PmtBrandDtTableTmp.php'); ?>
