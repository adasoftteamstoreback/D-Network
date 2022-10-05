<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbSOTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmBranch')?></th>
						<th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocNo')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocDate')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBStaDoc')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                @$tSODocNo      = $aValue['FTXshDocNo'];
                                @$tSOBchCode    = $aValue['FTBchCode'];
                                @$tSOBchCodeto  = $aValue['BCHCodeTo'];
                                @$tSOCstCode    = $aValue['FTCstCode'];
                                @$tSOCstName    = $aValue['FTCstName'];
                                @$tSOCstCardID  = $aValue['FTCstCardID'];
                                @$tSOCstTel     = $aValue['FTCstTel'];
                                @$tSOCstAddr    = $aValue['FTAddV2Desc1'];
                                @$tSOCshOrCrd   = $aValue['FTXshCshOrCrd'];
                                @$tSOCrTerm     = $aValue['FNXshCrTerm'];
                                @$tSODueDate    = date("Y-m-d",strtotime($aValue['FDXshDueDate']));
                            
                                //FTXshStaDoc
                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else if ($aValue['FTXshStaApv'] == 1) {
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = language('common/main/main', 'tStaDoc');
                                }

                                 //FTXshStaDoc
                                 if(@$aValue['FNXshStaRef'] == 2){
                                    $tClassStaRef = 'text-success';
                                }else if(@$aValue['FNXshStaRef'] == 1){
                                    $tClassStaRef = 'text-warning';    
                                }else if(@$aValue['FNXshStaRef'] == 0){
                                    $tClassStaRef = 'text-danger';
                                }
                               
                                $tClassPrcStk = 'text-success';
                                $bIsApvOrCancel = ($aValue['FTXshStaApv'] == 1 || $aValue['FTXshStaApv'] == 2) || ($aValue['FTXshStaDoc'] == 3 );

                                $aDateDoc = explode("/",$aValue['FDXshDocDate']);
                                $tDateDoc = $aDateDoc[2].'-'.$aDateDoc[1].'-'.$aDateDoc[0];
                            ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xDocuemntRefInt" 
                                    data-docno="<?=$aValue['FTXshDocNo']?>"
                                    data-docdate="<?=$tDateDoc?>"
                                    data-bchcode="<?=$tSOBchCode?>"
                                    data-cstcode="<?=$tSOCstCode?>"
                                    data-cstname="<?=$tSOCstName?>"
                                    data-cstcardid="<?=$tSOCstCardID?>"
                                    data-csttel="<?=$tSOCstTel?>"
                                    data-cstaddr="<?=$tSOCstAddr?>"
                                    data-cshorcrd="<?=$tSOCshOrCrd?>"
                                    data-crterm="<?=$tSOCrTerm?>"
                                    data-duedate="<?=$tSODueDate?>"
                                    data-doctype="<?=$tRefType?>"
                                    data-bchcodeto="<?=$tSOBchCodeto?>" >
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTXshDocNo']))? $aValue['FTXshDocNo'] : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($aValue['FDXshDocDate']))? $aValue['FDXshDocDate'] : '-' ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc ?>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPIPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvSORefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvSORefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvSORefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="">
    <div id="odvRefIntDocDetail"></div>
</div>

<?php include('script/jSaleOrderRefDocDataTable.php')?>

