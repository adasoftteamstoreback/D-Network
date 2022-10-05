<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbSOTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','สาขาที่สร้างเอกสาร')?></th>
						<th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocNo')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocDate')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBStaDoc')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                if ($tAPDDocType == 1) {
                                    // ใบซื้อสินค้า Invoice 
                                    $tAPDDocNo      = $aValue['FTXphDocNo'];
                                    $tAPDStaDoc     = $aValue['FTXphStaDoc'];
                                    $tAPDStaRef     = $aValue['FNXphStaRef'];
                                    $tAPDStaApv     = $aValue['FTXphStaApv'];
                                    $tAPDDocDate    = $aValue['FDXphDocDate'];
                                    $tAPDDocTime    = $aValue['FTXshDocTime'];
                                    $tAPDVATInOrEx  = $aValue['FTXphVATInOrEx'];
                                    $tAPDCrTerm     = $aValue['FNXphCrTerm'];
                                    $tSplCode       = $aValue['FTSplCode'];
                                    $tSplName       = $aValue['FTSplName'];
                                    $tAPDDocType    = 1;
                                }else{
                                    // ใบรับเข้า
                                    $tAPDDocNo      = $aValue['FTXthDocNo'];
                                    $tAPDStaDoc     = $aValue['FTXthStaDoc'];
                                    $tAPDStaRef     = $aValue['FNXthStaRef'];
                                    $tAPDStaApv     = $aValue['FTXthStaApv'];
                                    $tAPDDocDate    = $aValue['FDXphDocDate'];
                                    $tAPDDocTime    = $aValue['FTXshDocTime'];
                                    $tAPDVATInOrEx  = $aValue['FTXthVATInOrEx'];
                                    $tAPDCrTerm     = $aValue['FNXthCrTerm'];
                                    $tSplCode       = $aValue['FTSplCode'];
                                    $tSplName       = $aValue['FTSplName'];
                                    $tAPDDocType    = $tAPDDocType;
                                }

                                $tAPDBchCode    = $aValue['FTBchCode'];
                                $tAPDBchName    = $aValue['FTBchName'];

    
                                // FTXphStaDoc
                                if ($tAPDStaDoc == 3) {
                                    $tClassStaDoc   = 'text-danger';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc3');
                                } else if ($tAPDStaDoc == 1) {
                                    $tClassStaDoc   = 'text-success';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc   = 'text-warning';
                                    $tStaDoc        = language('common/main/main', 'tStaDoc');
                                }
                                
                                // FTXphStaDoc
                                if($tAPDStaRef == 2){
                                    $tClassStaRef   = 'text-success';
                                }else if($tAPDStaRef == 1){
                                    $tClassStaRef   = 'text-warning';    
                                }else if($tAPDStaRef == 0){
                                    $tClassStaRef   = 'text-danger';
                                }

                                $tClassPrcStk   = 'text-success';
                                $bIsApvOrCancel = ($tAPDStaApv == 1 || $tAPDStaApv == 2) || ($tAPDStaDoc == 3 );
                            ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xPurchaseInvoiceRefInt" 
                                id="otrPurchaseInvoiceRefInt<?php echo $nKey?>" 
                                data-docno="<?php echo $tAPDDocNo?>"
                                data-docdate="<?php echo $tAPDDocDate?>"
                                data-doctime="<?php echo $tAPDDocTime?>"
                                data-bchcode="<?php echo $tAPDBchCode?>"
                                data-bchname="<?php echo $tAPDBchName?>"
                                data-vatinroex="<?php echo $tAPDVATInOrEx?>"
                                data-crtrem="<?php echo $tAPDCrTerm?>"
                                data-splcode="<?php echo $tSplCode?>"
                                data-splname="<?php echo $tSplName?>"
                                data-doctype="<?php echo $tAPDDocType?>"
                            >
                                <td nowrap class="text-left"><?php echo (!empty($tAPDBchName))? $tAPDBchName : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tAPDDocNo))? $tAPDDocNo : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($tAPDDocDate))? $tAPDDocDate : '-' ?></td>

                                <!-- <td nowrap class="text-left">
                                <?php echo (!empty($tSplName))? $tSplName : '-' ?>
                                </td> -->

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
            <button onclick="JSvAPDRefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvAPDRefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvAPDRefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>


<div class="">
        <div id="odvPiRefIntDocDetail">

        </div>
</div>

<?php include('script/jAPDebitnoteRefDocDataTable.php')?>

