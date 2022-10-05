<?php
    // if($aDataList['rtCode'] == '1'){
    //     $nCurrentPage   = $aDataList['rnCurrentPage'];
    // }else{
    //     $nCurrentPage = '1';
    // }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbSOTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold" style="width:20%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxBranchName')?></th>
						<th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxRefDocNo')?></th>
                        <th nowrap class="xCNTextBold" style="width:8%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxRefDocDate')?></th>
                        <th nowrap class="xCNTextBold" style="width:20%;"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxCstName')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tWhTaxStaDoc')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;"><?php echo language('common/main/main','tWhTaxUsr')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;"><?php echo language('common/main/main','tWhTaxView')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php 
                                //FTXshStaDoc
                                if ($aValue['FTXshStaDoc'] == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                }else{
                                    if ($aValue['FTXshStaDoc'] == 1 && $aValue['FTXshStaApv'] == '') {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'tStaDoc');
                                    }else{
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'tStaDoc1');
                                    }
                                }
                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" id="otrPurchaseInvoice">
                                <td nowrap class="text-left"><?= $aValue['FTBchName'] ?></td>
                                <td nowrap class="text-center"><?= $aValue['FTXshDocNo'] ?></td>
                                <td nowrap class="text-center"><?= $aValue['FDXshDocDate'] ?></td>
                                <td nowrap class="text-center">
                                    <?php if ($aValue['FTXshRefInt'] != '') {
                                            echo $aValue['FTXshRefInt'];
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td nowrap class="text-center">
                                    <?php if ($aValue['FDXshRefIntDate']  != '') {
                                            echo $aValue['FDXshRefIntDate'];
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>           
                                <td nowrap class="text-left"><?= $aValue['FTCstName'] ?></td>
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc ?>
                                    </label>
                                </td>
                                <td nowrap class="text-left"><?= $aValue['FTUsrName'] ?></td>
                                <td nowrap class="text-center">
                                    <img class="xCNIconTable xCNIconView2" onClick="JSvWhTaxCallPageViewDoc('<?php echo $aValue['FTBchCode']?>', '<?php echo $aValue['FTXshDocNo']?>')">
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

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWTAXPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvTAXClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvTAXClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvTAXClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

