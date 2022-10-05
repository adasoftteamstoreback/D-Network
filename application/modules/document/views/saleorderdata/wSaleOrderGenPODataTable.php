<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="xCNCenter">
						<th nowrap class="xCNTextBold" width="2%"><?=language('document/saleorder/saleorder','tSOsequence')?></th>
						<th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOTable_Agency')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOTable_CstFranchise')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOLabelFrmBranch')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOTBDocNoFS')?></th>
                        <th nowrap class="xCNTextBold" width="10%"><?=language('document/saleorder/saleorder','tSOAdvSearchDocDate')?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?=language('document/saleorder/saleorder','tSOAdvSearchStaDoc')?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?=language('document/saleorder/saleorder','tSOTable_StatusConPDT')?></th>
                        <th nowrap class="xCNTextBold" width="8%"><?=language('document/saleorder/saleorder','tSOTable_StatusGenSO')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;"><?=language('common/main/main','tSOTable_GenSO')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php $tKeepDocNo = ''; ?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                $tSODocNo  = $aValue['FTXphDocNo'];
                                if(!empty($aValue['FTXphStaApv']) || $aValue['FTXphStaDoc'] == 3){
                                    $tCheckboxDisabled  = "disabled";
                                    $tClassDisabled     = 'xCNDocDisabled';
                                    $tTitle             = language('document/document/document','tDOCMsgCanNotDel');
                                    $tOnclick           = '';
                                }else{
                                    $tCheckboxDisabled  = "";
                                    $tClassDisabled     = '';
                                    $tTitle             = '';
                                    $tOnclick           = "onclick=JSoSODelDocSingle('".$nCurrentPage."','".$tSODocNo."')";
                                }

                                //สถานะเอกสาร
                                if($aValue['FTXphStaApv'] == 1){
                                    $tClassStaApv   = 'text-success';
                                    $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv1');
                                }else  if($aValue['FTXphStaDoc'] == 3){
                                    $tClassStaApv   = 'text-success';
                                    $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv3');
                                }else if(($aValue['FTXphStaPrcDoc'] == 1 || $aValue['FTXphStaPrcDoc'] == '') && $aValue['FTXphStaApv'] == ''){
                                    $tClassStaApv   = 'text-warning';  
                                    $tTextStatus    = language('document/saleorder/saleorder','tSOStaApv');
                                }else if($aValue['FTXphStaPrcDoc'] == 5 && $aValue['FTXphStaApv'] == ''){
                                    $tClassStaApv   = 'text-warning';  
                                    $tTextStatus    = language('document/saleorder/saleorder','รอจัดสินค้า');
                                }else if($aValue['FTXphStaPrcDoc'] == 6 && $aValue['FTXphStaApv'] == ''){
                                    $tClassStaApv   = 'text-warning';  
                                    $tTextStatus    = language('document/saleorder/saleorder','จัดแล้วบางส่วน'); 
                                }else if($aValue['FTXphStaPrcDoc'] == 7 && $aValue['FTXphStaApv'] == ''){
                                    $tClassStaApv   = 'text-warning';  
                                    $tTextStatus    = language('document/saleorder/saleorder','จัดครบแล้วรออนุมัติ');
                                }

                                //สถานะยืนยันสินค้า
                                if($aValue['FTXphStaApvPdt'] == 1){
                                    $tClassStaApvPdt   = 'text-warning';
                                    $tTextStatusPdt    = language('document/saleorder/saleorder','ยืนยันบางส่วน');
                                }else  if($aValue['FTXphStaApvPdt'] == 2){
                                    $tClassStaApvPdt   = 'text-success';
                                    $tTextStatusPdt    = language('document/saleorder/saleorder','ยืนยันครบแล้ว');
                                }else if($aValue['FTXphStaApvPdt'] == '' || $aValue['FTXphStaApvPdt'] == NULL){
                                    $tClassStaApvPdt   = 'text-warning';  
                                    $tTextStatusPdt    = language('document/saleorder/saleorder','รอยืนยัน');
                                }

                                //สถานะยืนยันสั่งขาย
                                if($aValue['FTXphStaGenSO'] == 1){
                                    $tClassStaApvGenSO   = 'text-warning';
                                    $tTextStatusGenSO    = language('document/saleorder/saleorder','สั่งขายบางส่วน');
                                }else  if($aValue['FTXphStaGenSO'] == 2){
                                    $tClassStaApvGenSO   = 'text-success';
                                    $tTextStatusGenSO    = language('document/saleorder/saleorder','สั่งขายครบแล้ว');
                                }else if($aValue['FTXphStaGenSO'] == '' || $aValue['FTXphStaGenSO'] == NULL){
                                    $tClassStaApvGenSO   = 'text-warning';  
                                    $tTextStatusGenSO    = language('document/saleorder/saleorder','รอสั่งขาย');
                                }

                            ?>
                            <tr class="text-center xCNTextDetail2 xWPIDocItems" data-code="<?=$aValue['FTXphDocNo']?>" data-name="<?=$aValue['FTXphDocNo']?>">

                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>

                                    <td nowrap class="text-center"><?= $nKey+1 ?></td>
                                    <td nowrap class="text-left"><?=(!empty($aValue['FTAgnName']))? $aValue['FTAgnName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?=(!empty($aValue['FTCstName']))? $aValue['FTCstName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?=(!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                    <td nowrap class="text-left"><?=(!empty($aValue['FTXphDocNo']))? $aValue['FTXphDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center"><?=(!empty($aValue['FDXphDocDate']))? $aValue['FDXphDocDate'] : '-' ?></td>
                                <?php } ?>

                                <td nowrap class="text-center"><label class="xCNTDTextStatus <?=$tClassStaApv;?>"><?=$tTextStatus?></label></td>
                                <td nowrap class="text-center"><label class="xCNTDTextStatus <?=$tClassStaApvPdt;?>"><?=$tTextStatusPdt?></label></td>
                                <td nowrap class="text-center"><label class="xCNTDTextStatus <?=$tClassStaApvGenSO;?>"><?=$tTextStatusGenSO?></label></td>
                                
                                <?php if($tKeepDocNo != $aValue['FTXphDocNo'] ) { ?>
                                        <?php if ($aValue['FTXphStaGenSO'] == 1 || $aValue['FTXphStaGenSO'] == '') { ?>
                                            <td nowrap>
                                                <img class="xCNIconTable" style="width: 20px;" src="<?= base_url('application/modules/common/assets/images/icons/plus-50.png'); ?>" onClick="JSvSOCallPageAddDoc('<?=$aValue['FTAgnRefCst']?>','<?=$aValue['FTXphDocNo']?>','<?=$aValue['FTBchCode']?>','<?=$aValue['FDXphDocDate']?>')">
                                            </td>
                                        <?php }else{ ?>
                                            <td nowrap>
                                                <a></a>
                                            </td>
                                        <?php } ?>
                                <?php } ?>
                            </tr>
                            <?php $tKeepDocNo = $aValue['FTXphDocNo']; ?>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <?php $nShowRecord = get_cookie('nShowRecordInPageList'); ?>
        <p>แสดงข้อมูลรายการล่าสุด <?=$nShowRecord?> รายการ</p>
    </div>
</div>
 
<?php include('script/jSaleOrderDataTable.php')?>

