<input type="hidden" name="ohdBCMnCurrentPage" id="ohdBCMnCurrentPage" value="<?php echo $paData['rnCurrentPage'] ?>">
<div class="table-responsive ">
    <table class="table table-striped">
        <thead>
            <tr>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tLMSDocDate');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdDocNo');?></th>
                <th nowarp class="text-center xCNTextBold " width="10%"><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdID');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tLMSTxnPntB4Bill');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tLMSTxnPntBillQty');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tLMSTxnTotalPntToday');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdCrdCode');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'LMS Order ID');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdType');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdMode');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdUpd');?></th>
                <th nowarp class="text-center xCNTextBold " ><?php echo language('document/card/main', 'tExcelNewCardRemark');?></th>
            </tr>
        </thead>
        <tbody>
           <?php 
           if(!empty($paData['raItems'])){   
               $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
                ?>
                <?php foreach($paData['raItems'] as $aData){ ?>
                    <tr>  
                        <td nowarp class="text-center"><?php echo date('Y-m-d', strtotime($aData['FDCreateOn'])); ?></td> 
                        <td nowarp class="text-left"><?php echo $aData['FTXshDocNo'] ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTTxnStandID'] ?></td>
                        <td nowarp class="text-right"><?php echo number_format($aData['FCTxnPntB4Bill'],$nOptDecimalShow) ?></td>
                        <td nowarp class="text-right"><?php echo number_format($aData['FCTxnPntBillQty'],$nOptDecimalShow) ?></td>
                        <td nowarp class="text-right"><?php echo number_format($aData['FCTxnTotalPntToday'],$nOptDecimalShow) ?></td>
                        <td nowarp class="text-left"><?php echo $aData['FTTxnCrdCode'] ?></td>
                        <td nowarp class="text-left"><?php echo ($aData['FTTxnRefTranID'] == '') ? '-' : $aData['FTTxnRefTranID']  ?></td>
                        <td nowarp class="text-left">
                            <?php 
                                if($aData['FTTxnType']=='1'){ 
                                    echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdType1'); 
                                }else if($aData['FTTxnType']=='4'){
                                    echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdType4'); 
                                }else{ 
                                    echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdType2'); 
                                } ?></td>
                        <td nowarp class="text-left"><?php if($aData['FTTxnStaOnline']=='1'){ echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdMode1'); }else{ echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdMode2'); } ?></td>
                        <td nowarp class="text-left"><?php if($aData['FTTxnStaUpload']=='1'){ echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdUpd1'); }else{ echo language('sale/salemonitor/salemonitor', 'tBCMBatTabStdUpd2'); } ?></td>
                        <td nowarp class="text-left"><?php if($aData['FTTxnRmk'] ==''){ echo '-'; }else{ echo $aData['FTTxnRmk']; } ?></td>
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
            <button onclick="JSvBCMStdClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvBCMStdClickPage('<?php echo $i ?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i ?></button>
            <?php } ?>
            <?php if ($nPage >= $paData['rnAllPage']) {
                $tDisabledRight = 'disabled';
            } else {
                $tDisabledRight = '-';
            } ?>
            <button onclick="JSvBCMStdClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<?php include "script/jStandMonitorDataTable.php";?>
