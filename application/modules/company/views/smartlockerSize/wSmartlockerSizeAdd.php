<?php
    $nDecimalShow = FCNxHGetOptionDecimalShow();
    if($aResult['rtCode'] == "1"){
        //route
        $tRoute     = "SHPSmartLockerSizeEventEdit";
        $tPzeCode   = $aResult['raItems']['FTPzeCode'];
        $tPzeDim    = $aResult['raItems']['FCPzeDim'];
        $tPzeHigh   = $aResult['raItems']['FCPzeHigh'];
        $tPzeWide   = $aResult['raItems']['FCPzeWide'];
        $tSizName   = $aResult['raItems']['FTSizName'];
        $tRemark    = $aResult['raItems']['FTSizRemark'];
        //Event Control
        if(isset($aAlwEventSmartlockerSize)){
            if($aAlwEventSmartlockerSize['tAutStaFull'] == 1 || $aAlwEventSmartlockerSize['tAutStaEdit'] == 1){
                $nAutStaEdit = 1;
            }else{
                $nAutStaEdit = 0;
            }
        }else{
            $nAutStaEdit = 0;
        }
    }else{
        //route
        $tRoute         = "SHPSmartLockerSizeEventAdd";
        $tPzeCode       = "";
        $tPzeDim        = 0;
        $tPzeHigh       = 0;
        $tPzeWide       = 0;
        $tSizName       = "";
        $tRemark        = "";
        $nAutStaEdit    = 0; //Event Control
    }
?>
<style>
    .NumberDuplicate{
        font-size   : 15px !important;
        color       : red;
        font-style  : italic;
    }

    .xCNSearchpadding{
        padding     : 0px 3px;
    }
</style>
<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddSms">
    <input type="hidden" name="oetSMLRouteUrl" id="oetSMLRouteUrl" value="<?=$tRoute?>">
    <button class="btn btn-default xCNHide" id="obtSubmitSml" type="submit" onclick="JSvSMSAddEdit('<?php echo $tRoute ?>')">
    <i class="fa fa-floppy-o"></i> <?php echo language('common/main/main', 'tSave')?>
	</button>
<div id="odvSMLPanelBody" class="panel-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row">
            <!-- Add image -->


            <!-- End GenCode -->
            <div class="col-lg-6 col-md-6 col-xs-6">
                <div class="form-group">
                <label class="xCNLabelFrm"><span class="text-danger">*</span><?= language('company/smartlockerSize/smartlockerSize', 'tSizeCode'); ?><?= language('company/smartlockerSize/smartlockerSize', 'tSMSSize'); ?></label>                    
                        <div id="odvShopSizeAutoGenCode" class="form-group">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbShopSizeAutoGenCode" name="ocbShopSizeAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                </label>
                            </div>
                        </div>
                    <div class="form-group" id="odvShopSizeCodeForm">
                        <input type="hidden" id="ohdCheckDuplicateSMSCode" name="ohdCheckDuplicateSMSCode" value="1"> 
                            <div class="validate-input">
                                <input 
                                    type="text" 
                                    class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
                                    maxlength="5" 
                                    id="oetPzeCode" 
                                    name="oetPzeCode"
                                    data-is-created="<?php echo $tPzeCode;?>"
                                    autocomplete="off"
                                    placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSizeCode')?>"
                                    value="<?php echo $tPzeCode;?>" 
                                    data-validate-required = "<?php echo language('company/smartlockerSize/smartlockerSize','tSMSVaSizeCode')?>"
                                    data-validate-dublicateCode = "<?php echo language('company/smartlockerSize/smartlockerSize','tSMSVaSizeCheckCode')?>"
                                >
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?= language('company/smartlockerSize/smartlockerSize','tSizeName'); ?></label>
                    <input type="text" class="form-control" id="oetSizName" name="oetSizName" maxlength="30" autocomplete="off" value="<?php echo $tSizName; ?>" placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSizeName')?>"
                    data-validate-required = "<?= language('company/smartlockerSize/smartlockerSize','tSMSVaSizeName')?>"
                    >
                </div>
                <div class="form-group">
                <label class="xCNLabelFrm"><span class="text-danger">*</span><?= language('company/smartlockerSize/smartlockerSize','tSizeDim'); ?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?></label>
                    <input type="text" class="form-control xCNInputNumericWithDecimal text-right" autocomplete="off" id="oetPzeDim" name="oetPzeDim" maxlength="30" value="<?php echo number_format($tPzeDim,$nDecimalShow); ?>" placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSizeDim')?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?>"
                    data-validate-required = "<?= language('company/smartlockerSize/smartlockerSize','tSMSVaSizeDim')?>"
                    >
                </div>
                <div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?= language('company/smartlockerSize/smartlockerSize','tSizeWidth'); ?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?></label>
                    <input type="text" class="form-control xCNInputNumericWithDecimal text-right"  autocomplete="off" id="oetPzeHigh" name="oetPzeHigh" maxlength="30" value="<?php echo number_format($tPzeHigh,$nDecimalShow); ?>" placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSizeWidth')?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?>"
                    data-validate-required = "<?= language('company/smartlockerSize/smartlockerSize','tSMSVaSizeWidth')?>"
                    >
                </div>
                <div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?= language('company/smartlockerSize/smartlockerSize','tSizeHeight'); ?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?></label>
                    <input type="text" class="form-control  xCNInputNumericWithDecimal text-right" id="oetPzeWide" autocomplete="off"  name="oetPzeWide" maxlength="30" value="<?php echo number_format($tPzeWide,$nDecimalShow); ?>" placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSizeHeight')?> <?= language('company/smartlockerSize/smartlockerSize','tSizeMm'); ?>"
                    data-validate-required = "<?= language('company/smartlockerSize/smartlockerSize','tSMSVaSizeHeight')?>"
                    >
                </div>
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('company/smartlockerSize/smartlockerSize','tSMSSizeRemark'); ?></label>
                    <textarea class="form-group" rows="4" maxlength="100" id="oetSizRemark" name="oetSizRemark" autocomplete="off" placeholder="<?php echo language('company/smartlockerSize/smartlockerSize','tSMSSizeRemark')?>"><?php echo $tRemark; ?></textarea>
                </div>

            </div>
        </div>
    </div>
</div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>

$(document).ready(function () {

    if(JSbShopSizeIsCreatePage()){
        $("#oetPzeCode").attr("disabled", true);
    $('#ocbShopSizeAutoGenCode').change(function(){
   if($('#ocbShopSizeAutoGenCode').is(':checked')) {
       $('#oetPzeCode').val('');
       $("#oetPzeCode").attr("disabled", true);
       $('#odvShopSizeCodeForm').removeClass('has-error');
       $('#odvShopSizeCodeForm em').remove();
   }else{
       $("#oetPzeCode").attr("disabled", false);
    }
    });
        JSxShopSizeVisibleComponent('#odvShopSizeAutoGenCode', true);
    }

    if(JSbShopSizeIsUpdatePage()){
    // ShopSize Code
    $("#oetPzeCode").attr("readonly", true);
    $('#odvShopSizeAutoGenCode input').attr('disabled', true);
    JSxShopSizeVisibleComponent('#odvShopSizeAutoGenCode', false);    

    }
    });
    $('#oetPzeCode').blur(function(){
    JSxChecShopSizeCodeDupInDB();
    });



</script>
