<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php if(!empty($aDocType['raItems'])){
        foreach($aDocType['raItems'] as $key => $aVale){
            if(empty($aDataNumByNotCode[$aVale['FTNotCode']])){
                    continue;
            } ?>
            <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2" style="padding:0px; padding-right: 10px; margin-top: 10px;">
                <div style="border: 1px solid #989898; padding: 10px; border-radius: 5px;">
                    <h3 style="text-align: center;"><?= language('checkdocument/checkdocument','tMntMassageCheck')?></h3>
                    <h2 class='xCNImageInformationBuy xCNImageIconFisrt' style="font-weight: bold;" ><?=$aDataNumByNotCode[$aVale['FTNotCode']]?></h2>
                    <h3 style="text-align: center;"><?=$aVale['FTNotTypeName']?></h3>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>