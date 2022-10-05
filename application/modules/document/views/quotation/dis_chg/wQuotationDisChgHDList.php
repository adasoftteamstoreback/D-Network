<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage   = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive" style="min-height: 300px; max-height: 300px; overflow-y: scroll;">
            <table id="otbQTDisChgDataDocHDList" class="table">
                <thead>
                    <tr class="xCNCenter">
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIsequence')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIBeforereducing')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIValuereducingcharging')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIAfterReducing')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIType')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIDiscountcharge')?></th>
                        <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPITBDelete')?></th>
                    </tr>    
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tHideClass = 'xCNHide';?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="xWQTDisChgTrTag">
                                <input type="hidden" class="xWQTDisChgCreatedAt" value="<?=$aValue['FDXtdDateIns'];?>">
                                <td nowrap class="text-center"><label class="xWQTDisChgIndex"><?=$aValue['FNRowID']; ?></label></td>
                                <td nowrap class="text-right"><label class="xWQTDisChgBeforeDisChg"></label></td>
                                <td nowrap class="text-right"><label class="xWQTDisChgValue"></label></td>
                                <td nowrap class="text-right"><label class="xWQTDisChgAfterDisChg"><?=$aValue['FNRowID']; ?></label></td>
                                <td nowrap style="padding-left: 5px !important;">
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        <select class="dischgselectpicker form-control xWQTDisChgType" onchange="JSxQTCalcDisChg(this);" value="<?=$aValue['FNRowID']; ?>">
                                            <option value='1' <?=$aValue['FTXtdDisChgType'] == '1' ? 'selected="true"' : ''; ?>><?=language('common/main/main', 'ลดบาท'); ?></option>
                                            <option value='2' <?=$aValue['FTXtdDisChgType'] == '2' ? 'selected="true"' : ''; ?>><?=language('common/main/main', 'ลด %'); ?></option>
                                            <option value='3' <?=$aValue['FTXtdDisChgType'] == '3' ? 'selected="true"' : ''; ?>><?=language('common/main/main', 'ชาร์จบาท'); ?></option>
                                            <option value='4' <?=$aValue['FTXtdDisChgType'] == '4' ? 'selected="true"' : ''; ?>><?=language('common/main/main', 'ชาร์ท %'); ?></option>
                                        </select>
                                    </div>
                                </td>
                                <td nowrap style="padding-left: 5px !important;">
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        <input 
                                            class="form-control 
                                            xCNInputNumericWithDecimal xWQTDisChgNum" 
                                            onchange="JSxQTCalcDisChg(this); JCNxQTDisChgSetCreateAt(this)"
                                            onkeyup="javascript:if(event.keyCode==13) JSxQTCalcDisChg(this);"
                                            value="<?=$aValue['FCXtdDisChg']; /*preg_replace("([-,+,%]+)", "", $aValue['FTXtdDisChgTxt']);*/ ?>"
                                            type="text">
                                    </div>
                                </td>
                                <td nowrap class="text-center">
                                    <label class="xCNTextLink">
                                        <img class="xCNIconTable xWQTDisChgRemoveIcon" src="<?=base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSxQTResetDisChgRemoveRow(this)">
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <?php $tHideClass = '';?>
                    <?php endif;?>
                        <tr id="otrQTDisChgHDNotFound" class="<?=$tHideClass;?>"><td class="text-center xCNTextDetail2" colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if($nPage > 1) : ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="xWPage btn-toolbar pull-right">
                <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
                <button onclick="JSvQTDisChgHDClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>>
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
                    <button onclick="JSvQTDisChgHDClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
                <?php } ?>

                <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
                <button onclick="JSvQTDisChgHDClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                    <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    $(document).ready(function(){
        $('.dischgselectpicker').selectpicker();
        JSxQTCalcDisChg();
    });
</script>