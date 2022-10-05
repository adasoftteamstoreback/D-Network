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
                    <?php if ($tDBRBrowseType == 1) { ?>
                        <tr class="xCNCenter">
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','รหัสสาขา')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อสาขา')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อร้านค้า')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','รหัสตู้ฝาก')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อตู้ฝาก')?></th>
                        </tr>
                    <?php }else{ ?>
                        <tr class="xCNCenter">
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','รหัสสาขา')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อสาขา')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','รหัสตู้ฝาก')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อตู้ฝาก')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','รหัสช่องฝาก')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','ชื่อช่องฝาก')?></th>
                            <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','สถานะช่องฝาก')?></th>
                        </tr>
                    <?php } ?>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                if ($tDBRBrowseType == 1) {
                                    $tDBRBoxOrSlotCode  = $aValue['FTPosCode'];
                                    $tDBRBoxOrSlotName  = $aValue['FTPosName'];
                                    $tDBRPshType  = $aValue['FTPshType'];
                                    $tDBRBoxOrShpName  = $aValue['FTShpName'];
                                    $tDBRBoxOrShpCode  = $aValue['FTShpCode'];
                                }else{
                                    $tDBRBoxOrSlotCode  = $aValue['FTPosCode'];
                                    $tDBRBoxOrSlotName  = $aValue['FTPosName'];
                                    $tDBRBoxOrSlotShpCode  = $aValue['FTShpCode'];
                                    $tDBRBoxOrSlotLayNo  = $aValue['FNLayNo'];
                                    $tDBRBoxOrSlotLayName  = $aValue['FTLayName'];
                                    $tDBRBoxOrSlotLayStaUse  = $aValue['FTLayStaUse'];

                                    if ($tDBRBoxOrSlotLayStaUse == 3) {
                                        $tClassStaDoc = 'text-danger';
                                        $tStaDoc = language('common/main/main', 'ปิดการใช้งาน');
                                    }elseif ($tDBRBoxOrSlotLayStaUse == 2) {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'ใช้งาน');
                                    }elseif ($tDBRBoxOrSlotLayStaUse == 4) {
                                        $tClassStaDoc = 'text-warning';
                                        $tStaDoc = language('common/main/main', 'จอง');
                                    }else{
                                        $tClassStaDoc = 'text-success';
                                        $tStaDoc = language('common/main/main', 'ว่าง');
                                    }
                                    $tDBRPshType  = '';

                                }
                                
                                $tDBRBchCode  = $aValue['FTBchCode'];
                                $tDBRBchName  = $aValue['FTBchName'];
                                
                            ?>

                            <?php if ($tDBRBrowseType == 1) { ?>
                                <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWBoxOrSlotItems xBoxOrSlot" 
                                    id="otrBox<?php echo $nKey?>" 
                                    data-boxno="<?php echo $tDBRBoxOrSlotCode?>"
                                    data-boxname="<?php echo $tDBRBoxOrSlotName?>"
                                    data-bchcode="<?php echo $tDBRBchCode?>"
                                    data-bchname="<?php echo $tDBRBchName?>"
                                    data-pshtype="<?php echo $tDBRPshType?>"
                                    data-ShpCode="<?php echo $tDBRBoxOrShpCode?>"
                                    ondblclick="JSxDoubleClickSelection(this)"
                                >
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBchCode))? $tDBRBchCode   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBchName))? $tDBRBchName   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrShpName))? $tDBRBoxOrShpName   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotCode))? $tDBRBoxOrSlotCode   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotName))? $tDBRBoxOrSlotName   : '-' ?></td>
                                </tr>
                            <?php }else{ ?>
                                <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWBoxOrSlotItems xBoxOrSlot" 
                                    id="otrBox<?php echo $nKey?>" 
                                    data-boxno="<?php echo $tDBRBoxOrSlotCode?>"
                                    data-boxname="<?php echo $tDBRBoxOrSlotName?>"
                                    data-bchcode="<?php echo $tDBRBchCode?>"
                                    data-bchname="<?php echo $tDBRBchName?>"
                                    data-shpcode="<?php echo $tDBRBoxOrSlotShpCode?>"
                                    data-laycode="<?php echo $tDBRBoxOrSlotLayNo?>"
                                    data-layname="<?php echo $tDBRBoxOrSlotLayName?>"
                                    data-pshstause="<?php echo $tDBRBoxOrSlotLayStaUse?>"
                                    ondblclick="JSxDoubleClickSelection(this)"
                                >
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBchCode))? $tDBRBchCode   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBchName))? $tDBRBchName   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotCode))? $tDBRBoxOrSlotCode   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotName))? $tDBRBoxOrSlotName   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotLayNo))? $tDBRBoxOrSlotLayNo   : '-' ?></td>
                                    <td nowrap class="text-left"><?php echo (!empty($tDBRBoxOrSlotLayName))? $tDBRBoxOrSlotLayName   : '-' ?></td>
                                    <td nowrap class="text-left <?php echo $tClassStaDoc;?>"><?php echo $tStaDoc ?></td>
                                </tr>
                            <?php } ?>
                            
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
            <!-- <button onclick="JSvDBRRefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvDBRRefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvDBRRefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button> -->
        </div>
    </div>
</div>

<div id="odvDBRRefIntDocDetail"></div>

<?php include('script/jDepositBoxReservationBoxDataTable.php')?>

