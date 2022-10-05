<div class="table-responsive">
    <table class="table table-striped xWPdtTableFont" id="otbTransferBchOutPdtTable">
        <thead>
            <tr class="xCNCenter">
                <?php if(!$bIsApvOrCancel){?>
                    <th><?= language('document/purchaseorder/purchaseorder', 'tPOTBChoose')?></th>
                <?php } ?>

                <th ><?= language('document/purchaseorder/purchaseorder', 'ลำดับ')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'รหัสสินค้า')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'ชื่อสินค้า')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'บาร์โค้ด')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'หน่วยสินค้า')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'สถานะสต็อค')?></th>
                <th ><?= language('document/purchaseorder/purchaseorder', 'จำนวน')?></th>


                <?php if(!$bIsApvOrCancel){?>
                    <th ><?= language('document/purchaseorder/purchaseorder', 'tPOTBDelete')?></th>
                <?php }else { ?>
                    <!-- <th></th> -->
                <?php } ?>
            </tr>
        </thead>

        <tbody class="xCNTransferBchOutTBodyPdtItem">
            <?php $nNumSeq = 1; ?>
            <?php if($aDataList['rtCode'] == 1) { ?>
              
                <?php foreach($aDataList['raItems'] as $DataTableKey=>$aDataTableVal) { ?>     
                    <tr 
                    class="text-center xCNTextDetail2 nItem<?=$nNumSeq?> xWTransferBchOutPdtItem"  
                    data-index="<?=$DataTableKey?>" 
                    data-docno="<?=$aDataTableVal['FTXthDocNo']?>" 
                    data-pdtname="<?=$aDataTableVal['FTXtdPdtName']?>" 
                    data-pdtcode="<?=$aDataTableVal['FTPdtCode']?>" 
                    data-puncode="<?=$aDataTableVal['FTPunCode']?>" 
                    data-seqno="<?=$aDataTableVal['FNXtdSeqNo']?>">
                        <?php if(!$bIsApvOrCancel){?>
                            <td class="text-center">
                                <label class="fancy-checkbox" style="width: auto;">
                                    <input id="ocbTransferBchOutPdtListItem<?=$aDataTableVal['FNXtdSeqNo']?>" type="checkbox" class="ocbTransferBchOutPdtListItem" name="ocbTransferBchOutPdtListItem[]">
                                    <span>&nbsp;</span>
                                </label>
                            </td>
                        <?php } ?>
                            <td class="text-center" ><label class="text-center xCNPdtFont xWShowValueFNXtdSeqNo<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FNXtdSeqNo']?></label></td>
                            <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTPdtCode<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTPdtCode']?></label></td>
                            <td class="text-left" ><label class="text-left xCNPdtFont xWShowValueFTXtdPdtName<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTXtdPdtName']?></label></td>
                            <td class="text-left"><label class="text-left xCNPdtFont xWShowValueFTXtdBarCode<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTXtdBarCode']?></label></td>
                            <td class="text-left"><label class="text-left xCNPdtFont xWShowValueFTPunName<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo $aDataTableVal['FTPunName']?></label></td>
                            <td nowrap class="text-center">
                            <?php
                                        switch ($aDataTableVal['FTXtdStaPrcStk']) {
                                            case '1':
                                                switch ($aDataTableVal['FTXtdRmk']) {
                                                    case '1':
                                                        echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                                        break;
                                                    case '2':
                                                        echo "<span class='xCNTextConfirm'>ไม่ตรวจสอบสต็อค</span>";
                                                        break;
                                                }
                                                break;
                                            default:
                                                switch ($aDataTableVal['FTXtdRmk']) {
                                                    case '1':
                                                        echo "<span class='xCNTextConfirm'>ยืนยันแล้ว</span>";
                                                        break;
                                                    default:
                                                        echo "<span class='xCNTextWaitConfirm'>รอยืนยัน</span>";
                                                        break;
                                                }
                                                break;
                                        }
                                    ?>
                            </td>
                            <?php if(!$bIsApvOrCancel){ ?>
                            <!-- <td>
                                <label 
                                data-field="FCXtdQty"
                                data-seq="<?=$aDataTableVal['FNXtdSeqNo']?>"
                                class="xCNPdtFont xCNApvOrCanCelDisabledPdt xWShowInLine<?=$aDataTableVal['FNXtdSeqNo']?> xWShowValueFCXtdQty<?=$aDataTableVal['FNXtdSeqNo']?>"><?= $aDataTableVal['FCXtdQty'] != '' ? "".$aDataTableVal['FCXtdQty'] : '-'; ?>
                                </label>
                                <div class="xCNHide xWEditInLine<?=$aDataTableVal['FNXtdSeqNo']?>">
                                    <input 
                                    type="text" 
                                    class="form-control xCNApvOrCanCelDisabledPdt xCNPdtEditInLine xWValueEditInLine<?=$aDataTableVal['FNXtdSeqNo']?> xCNInputWithoutSpc xWEditPdtInline" 
                                    id="ohdFCXtdQty<?=$aDataTableVal['FNXtdSeqNo']?>" 
                                    name="ohdFCXtdQty<?=$aDataTableVal['FNXtdSeqNo']?>" 
                                    maxlength="11" 
                                    value="<?=number_format($aDataTableVal['FCXtdQty'], $nOptDecimalShow, '.', ',')?>"
                                    <?= $aDataTableVal['FCXtdQty'] == 'FTXtdDisChgTxt' ? 'readonly' : '' ?> <?= $aDataTableVal['FCXtdQty']  == 'FCXtdQty' ?>>
                                </div>
                            </td> -->

                                <td class="otdQty">
                                    <div class="xWEditInLine<?=$aDataTableVal['FNXtdSeqNo']?>">
                                        <input type="text" class="xCNQty form-control xWEditPdtInline xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$aDataTableVal['FNXtdSeqNo']?> xWShowInLine<?=$aDataTableVal['FNXtdSeqNo']?> " id="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" name="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" data-seq="<?=$aDataTableVal['FNXtdSeqNo']?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" autocomplete="off">
                                    </div>
                                </td>
                            <?php }else{ ?>
                                <td class="text-right"><label class="text-left xCNPdtFont xWShowValueFCXtdQty<?php echo $aDataTableVal['FNXtdSeqNo']?>"><?php echo number_format($aDataTableVal['FCXtdQty'], $nOptDecimalShow, '.', ',')?></label></td>
                            <?php } ?>

                        <?php if(!$bIsApvOrCancel){?>
                            <td nowrap class="text-center">
                                <lable class="xCNTextLink">
                                    <img class="xCNIconTable xCNIconDel" src="<?php echo base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove">
                                </lable>
                            </td>    
                            <!-- <td></td> -->
                        <?php }else { ?>
                            <!-- <td></td> -->
                        <?php } ?>
                    </tr>
                    <?php $nNumSeq++; ?>
                <?php } ?>

            <?php }else { ?>
                    <tr><td class='text-center xCNTextDetail2 xWTransferBchOutTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php if($aDataList['rnAllPage'] > 1) { ?>
    <div class="row odvTransferBchOutPdtDataTable">
        <div class="col-md-6">
            <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
        </div>
        <div class="col-md-6">
            <div class="xWPage btn-toolbar pull-right">
                <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
                <button onclick="JSvTransferBchOutPdtDataTableClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                    <button onclick="JSvTransferBchOutPdtDataTableClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
                <?php } ?>
                <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
                <button onclick="JSvTransferBchOutPdtDataTableClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                    <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
                </button>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    $( document ).ready(function() {
        JSxEditQtyAndPrice(); 
    });

    //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
    $('.xCNPdtEditInLine').click(function() {
        $(this).focus().select();
    });
    // $('.xCNQty').change(function(e){
    $('.xCNQty').off().on('change keyup', function(e) {
        if(e.type === 'change' || e.keyCode === 13){
            var nSeq    = $(this).attr('data-seq');
            var nQty        = $('#ohdQty'+nSeq).val();
            nNextTab = parseInt(nSeq)+1;
            $('.xWValueEditInLine'+nNextTab).focus().select();
            JSxGetDisChgList(nSeq);
        }
    });

    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq){

        var nQty        = $('#ohdQty'+pnSeq).val();
        var tTRBDocNo        = $("#oetTransferBchOutDocNo").val();
        var tTRBBchCode      = $("#oetTransferBchOutBchCode").val();
        JSxTransferBchOutPdtDataTableUpdateBySeq(pnSeq, "FCXtdQty", nQty);
    }
</script>
<?php include('script/jTransferBchOutPdtDataTable.php'); ?>