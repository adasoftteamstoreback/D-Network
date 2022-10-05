<input type="hidden" name="ohdBCMnCurrentPage" id="ohdBCMnCurrentPage" value="<?php echo $paData['rnCurrentPage'] ?>">
<div class="table-responsive ">
    <table  class="table table-striped">
        <thead>
            <tr>
                 <th nowarp class="text-center xCNTextBold ">     
                      <label class="fancy-checkbox ">
                        <input type="checkbox" class="" id="ocbBCMListItemAll" name="ocbBCMListItemAll"  >
                        <span class="">&nbsp;</span>
                    </label> 
                    </th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabBch');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabPos');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabSht');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtBlue');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStandBlueFrm');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStandBlueTo');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtSalAmt');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaClose');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaVerti');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtStaRepiar');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtCallStand');?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           if(!empty($paData['raItems'])){   
               $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
                ?>
                <?php foreach($paData['raItems'] as $aData){ ?>
                    <tr>   
                    <td nowarp  class="text-center">
                        <label class="fancy-checkbox ">
                              <input  type="checkbox" class="ocbBCMListItem" name="ocbBCMListItem[]"  data-bchcode="<?php echo $aData['FTBchCode'] ?>" data-poscode="<?php echo $aData['FTPosRefTID'] ?>" value="<?php echo $aData['FTShfCode'] ?>">
                                 <span class="">&nbsp;</span>
                            </label>
                        </td>
                        <td nowarp class="text-left"><?php echo $aData['FTBchName'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTPosRefTID'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTShfCode'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTBatID'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTBatStandFrm'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTBatStandTo'] ?></td>
                        <td nowarp class="text-right"><?php echo number_format($aData['FCBatSumAmt'],$nOptDecimalShow) ?></td>
                        <td nowarp class="text-left"><?php if($aData['FTBatStaClosed']=='1'){ echo language('sale/salemonitor/salemonitor', 'tBCMBatStaClosed1'); }else{ echo language('sale/salemonitor/salemonitor', 'tBCMBatStaClosed2'); } ?></td>
                        <td nowarp class="text-left">
                            <?php 
                                $tStaVertify1 = 0;
                                if(!empty($aData['FTBatStaVerify'])){
                                    if($aData['FTBatStaVerify']=='1'){
                                        echo  language('sale/salemonitor/salemonitor', 'tBCMBatStaVerify1'); 
                                        $tStaVertify1 = 1;
                                    }else{
                                        echo  language('sale/salemonitor/salemonitor', 'tBCMBatStaVerify2'); 
                                    }
                                }else{ 
                                    $tStaVertify1 = 2;
                                    echo language('sale/salemonitor/salemonitor', 'tBCMBatStaVerify3'); 
                                } 
                            ?>
                        </td>
                        <td nowarp class="text-left">
                            <?php 
                                if($tStaVertify1 == 2 || $tStaVertify1 == 1){
                                    echo '-';
                                }else{
                                    if($aData['FTBatStaInsBat']=='1'){ 
                                        echo language('sale/salemonitor/salemonitor', 'tBCMBatStaInsBat1'); 
                                    }else{ 
                                        echo language('sale/salemonitor/salemonitor', 'tBCMBatStaInsBat2'); 
                                    } 
                                }
                            ?>
                        </td>
                        <td nowarp class="text-center"><img class="xCNIconTable" style="width: 17px;" src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>" onclick="JSxBCMCallPageStand('<?php echo $aData['FTBatID'] ?>')" ></td>
                    </tr>
                    <?php } ?>
             <?php }else{ ?>
                <tr> 
                        <td colspan='12' class="text-center"><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabShtEmpty');?></td>
                </tr>  
                <?php } ?>
        </tbody>
    </table>
</div>


<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main', 'tResultTotalRecord') ?> <?php echo $paData['rnAllRow'] ?> <?php echo language('common/main/main', 'tRecord') ?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $paData['rnCurrentPage'] ?> / <?php echo $paData['rnAllPage'] ?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageTotalByBranch btn-toolbar pull-right">
            <?php if ($nPage == 1) {
                $tDisabledLeft = 'disabled';
            } else {
                $tDisabledLeft = '-';
            } ?>
            <button onclick="JSvBCMClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for ($i = max($nPage - 2, 1); $i <= max(0, min($paData['rnAllPage'], $nPage + 2)); $i++) { ?>
                <?php
                if ($nPage == $i) {
                    $tActive = 'active';
                    $tDisPageNumber = 'disabled';
                } else {
                    $tActive = '';
                    $tDisPageNumber = '';
                }
                ?>
                <button onclick="JSvBCMClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>
            <?php if ($nPage >= $paData['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvBCMClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<?php include "script/jBatchMonitorDataTable.php";?>
