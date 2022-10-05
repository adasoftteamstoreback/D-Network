
<style>
    .xCNIconContentAPI  {  width:15px; height:15px; background-color:#e84393; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentDOC  {  width:15px; height:15px; background-color:#ffca28; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentPOS  {  width:15px; height:15px; background-color:#42a5f5; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentSL   {  width:15px; height:15px; background-color:#ff9030; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentWEB  {  width:15px; height:15px; background-color:#99cc33; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentVD   {  width:15px; height:15px; background-color:#dbc559; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentALL  {  width:15px; height:15px; background-color:#ff5733; display: inline-block; margin-right: 10px; margin-top: 0px; }
    .xCNIconContentETC  {  width:15px; height:15px; background-color:#92918c; display: inline-block; margin-right: 10px; margin-top: 0px; }

    .xCNTableScrollY{
        overflow-y      : auto; 
    }

    .xCNCheckboxBlockDefault:before{
        background      : #ededed !important;
    }

    .xCNInputBlock{
        background      : #ededed !important;
        pointer-events  : none;
    }

    #ospDetailFooter{
        font-weight     : bold;
    }

</style>
<div class="row">
    <div class="col-xs-8 col-md-4 col-lg-4">
        <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting','tMappingsetup')?></label>
        <div class="form-group">
            <div class="input-group">
                <input type="text" 
                    class="form-control xCNInputWithoutSingleQuote" 
                    id="oetSearchMapping" 
                    name="oetSearchMapping" 
                    placeholder="<?php echo language('interface/connectionsetting/connectionsetting','tSearch')?>"
                    value="<?=$tSearchAllMapping;?>">
                <span class="input-group-btn">
                    <button id="oimSearchSetUpMapping" class="btn xCNBtnSearch" type="button">
                        <img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                    </button>
                </span>
            </div>
        </div>
    </div><br>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%" id="otbTableForCheckbox">
                <thead>
					<tr class="xCNCenter">
						<!-- <th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPOrder'); ?></th> -->
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPName'); ?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPComparison'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tMPActive'); ?></th>
						<th nowrap class="xCNTextBold"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBEdit'); ?></th>
					</tr>
                </thead>
                <tbody>
                    <?php if($aMappingData['rtCode'] == 1 ):?>
                        <?php foreach($aMappingData['raItems'] AS $key=>$aValue){ ?>
                            <tr class="text-center xCNTextDetail2" data-code="<?=$aValue['FTMapCode']?>">
								<!-- <td style="text-align:center;"><?=$aValue['FNMapSeqNo']?></td> -->
								<td style="text-align:left;"><?=$aValue['FTMapName']?></td>
                                <td style="text-align:left;"><?=$aValue['FTMapDefValue']?></td>
								<td style="text-align:left;"><?=$aValue['FTMapUsrValue']?></td>
								<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
									<td><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageEditConnectionSettingMapping('<?=$aValue['FTMapCode']?>','<?=$aValue['FNMapSeqNo']?>')"></td>
								<?php endif; ?>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='100'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script> 
    // คลังสินค้าที่ตั้งค่าแล้ว
    $('#oimSearchSetUpMapping').click(function(){
        JCNxOpenLoading();
        JSxCallGetContentMapping();
    });

    $('#oetSearchMapping').keypress(function(event){
        if(event.keyCode == 13){
            JCNxOpenLoading();
            JSxCallGetContentMapping();
        }
    });
</script>

