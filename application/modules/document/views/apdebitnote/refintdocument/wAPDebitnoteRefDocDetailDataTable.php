<div class="xCNtableFixHead">
  <table class="table xRefIntTable" >
    <thead >
        <tr>
            <th class="xRefIntTh text-center">
                <label class="fancy-checkbox ">
                    <input id="ocbRefIntDocDTAll" type="checkbox" class="ocbRefIntDocDTAll" name="ocbListItem[]"  checked>
                    <span class="">&nbsp;</span>
                </label>
            </th>
            <th class="xRefIntTh text-center"><?php echo language('document/purchaseorder/purchaseorder','tPOTBNo');?></th>
            <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder','tPOTable_pdtcode');?></th>
            <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder','tPOTable_pdtname');?></th>
            <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder','tPOTable_barcode');?></th>
            <th class="xRefIntTh"><?php echo language('document/purchaseorder/purchaseorder','tPOTable_unit');?></th>
            <th class="xRefIntTh text-right"><?php echo language('document/purchaseorder/purchaseorder','จำนวนที่ซื้อ');?></th>
        </tr>
    </thead>
    <tbody>

    <?php if($aDataList['rtCode'] == 1 ):?>
         <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
            <?php 
                if($tAPDDocType == '1'){
                    // ใบซื้อสินค้า Invoice 
                    $nSeqNo     = $aValue['FNXpdSeqNo'];
                    $tPdtCode   = $aValue['FTPdtCode'];
                    $tPdtName   = $aValue['FTXpdPdtName'];
                    $tBarCode   = $aValue['FTXpdBarCode'];
                    $tPunName   = $aValue['FTPunName'];
                    $nQty       = $aValue['FCXpdQty'];
                }else{
                    // ใบรับเข้า
                    $nSeqNo     = $aValue['FNXtdSeqNo'];
                    $tPdtCode   = $aValue['FTPdtCode'];
                    $tPdtName   = $aValue['FTXtdPdtName'];
                    $tBarCode   = $aValue['FTXtdBarCode'];
                    $tPunName   = $aValue['FTPunName'];
                    $nQty       = $aValue['FCXtdQty'];
                }
            ?>

            <tr class="xCNTextDetail2" >
                <td class="xRefIntTd text-center">
                        <label class="fancy-checkbox ">
                            <input type="checkbox" class="ocbRefIntDocDT" name="ocbRefIntDocDT[]" value="<?=$nSeqNo;?>"  checked>
                            <span class="">&nbsp;</span>
                        </label>
                </td>
                <td class="xRefIntTd text-center"><?=$nKey+1?></td>
                <td class="xRefIntTd"><?=$tPdtCode?></td>
                <td class="xRefIntTd"><?=$tPdtName?></td>
                <td class="xRefIntTd"><?=$tBarCode?></td>
                <td class="xRefIntTd"><?=$tPunName?></td>
                <td class="xRefIntTd text-right"><?=number_format($nQty,$nOptDecimalShow)?></td>
            </tr>
      <?php endforeach;?>
    <?php else:?>
        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
    <?php endif;?>
    </tbody>
  </table>
</div>
<script>

//เลือกทั้งหมด
$('#ocbRefIntDocDTAll').click(function(){
    if($(this).is(':checked')==true){
        $('.ocbRefIntDocDT').prop('checked',true);
    }else{
        $('.ocbRefIntDocDT').prop('checked',false);
    }
});
$('.ocbRefIntDocDT').click(function(){
    if($(this).is(':checked')==false){
        $('#ocbRefIntDocDTAll').prop('checked',false);
    }
});
</script>