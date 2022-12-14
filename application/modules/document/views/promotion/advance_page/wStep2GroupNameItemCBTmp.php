
<?php foreach ($aDataList as $key => $aValue) { ?>
    
    <div
    data-grpname="<?php echo $aValue['FTPmdGrpName']; ?>" 
    data-stalisttype="<?php echo $aValue['FTPbyStaPdtDT']; ?>" 
    class="xCNPromotionStep2GroupNameType1Item alert alert-success" 
    role="alert" 
    style="min-width: 60%; width: fit-content; z-index: 100;">
        <?php echo $aValue['FTPmdGrpName']; ?> 
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    </div>

<?php } ?>

<script>
    $(document).ready(function(){
        if(JSbPromotionConditionBuyIsRange()){ // เงื่อนไขการซื้อแบบช่วง
            $('.xCNPromotionStep2GroupBuy .xCNPromotionStep2GroupNameType1Item .close').unbind().bind('click', function(){
                var tGroupName = $(this).parents(".xCNPromotionStep2GroupNameType1Item").data('grpname');
                JSvPromotionStep3DeletePmtCBInTemp(tGroupName);
                JSvPromotionStep3DeletePmtCGInTemp(tGroupName);
                $(".xCNPromotionStep2GroupBuy .xCNPromotionStep2GroupNameType1Item[data-grpname='" + tGroupName + "']").remove();
                $(".xCNPromotionStep2GroupGet .xCNPromotionStep2GroupNameType1Item[data-grpname='" + tGroupName + "']").remove();  
            });
        }
        if(JSbPromotionConditionBuyIsNormal()){ // เงื่อนไขการซื้อแบบปกติ
            $('.xCNPromotionStep2GroupBuy .xCNPromotionStep2GroupNameType1Item .close').unbind().bind('click', function(){
                var tGroupName = $(this).parents(".xCNPromotionStep2GroupNameType1Item").data('grpname');
                var tStaListType = $(this).parents(".xCNPromotionStep2GroupNameType1Item").data('stalisttype');
                if(tStaListType == '8'){
                    JSvPromotionStep3DeletePmtCBInTemp(tGroupName);
                    JSvPromotionStep3DeletePmtCGInTemp(tGroupName);
                    $(".xCNPromotionStep2GroupBuy .xCNPromotionStep2GroupNameType1Item[data-grpname='" + tGroupName + "']").remove();
                    $(".xCNPromotionStep2GroupGet .xCNPromotionStep2GroupNameType1Item[data-grpname='" + tGroupName + "']").remove();  
                }else{
                    JSvPromotionStep3DeletePmtCBInTemp(tGroupName);
                    $(this).parents(".xCNPromotionStep2GroupNameType1Item").remove();
                }
            });
        }
    });
</script>