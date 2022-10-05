<div class="xCNtableFixHead">
  <table class="table xRefIntTable" >
    <thead>
        <tr>
            <th class="xRefIntTh text-center">
                <label class="fancy-checkbox ">
                    <input id="ocbRefIntDocDTAll" type="checkbox" class="ocbRefIntDocDTAll" name="ocbListItem[]"  checked>
                    <span class="">&nbsp;</span>
                </label>
            </th>
            <th class="xRefIntTh text-center"><?=language('document/purchaseorder/purchaseorder','tPOTBNo');?></th>
            <th class="xRefIntTh"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtcode');?></th>
            <th class="xRefIntTh"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtname');?></th>
            <th class="xRefIntTh"><?=language('document/purchaseorder/purchaseorder','tPOTable_barcode');?></th>
            <th class="xRefIntTh"><?=language('document/purchaseorder/purchaseorder','tPOTable_unit');?></th>
            <?php 
                if($tDocType == 1){ //ใบขอเสนอราคา ?>
                    <th class="xRefIntTh text-right"><?=language('document/purchaseorder/purchaseorder','tPOTable_qty');?></th>
                <?php }else{ //ใบสั่งซื้อ ?>
                    <th class="xRefIntTh text-right">จำนวนสั่งซื้อ</th>
                    <th class="xRefIntTh text-right">จำนวนสั่งขายแล้ว</th>
                    <th class="xRefIntTh text-right">จำนวนพร้อมสั่งขาย</th>
                <?php }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php if($aDataList['rtCode'] == 1 ):?>
            <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                <tr class="xCNTextDetail2" >
                    <?php 
                        if($tDocType == 1){ //ใบขอเสนอราคา ?>
                             <td class="xRefIntTd text-center">
                                <label class="fancy-checkbox ">
                                    <input type="checkbox" class="ocbRefIntDocDT" name="ocbRefIntDocDT[]" value="<?=$aValue['FNXsdSeqNo']?>"  checked>
                                    <span>&nbsp;</span>
                                </label>
                            </td>
                        <?php }else{ //ใบสั่งซื้อ ?>

                            <?php 
                                $tClassDisabledCheckbox = 'checked';
                                $tClassCheckNormal      = 'ocbRefIntDocDT';
                                if($aValue['QTY'] == 0){
                                    $tClassDisabledCheckbox = "disabled";
                                    $tClassCheckNormal     = '';
                                }
                            ?>
                            <td class="xRefIntTd text-center">
                                <label class="fancy-checkbox ">
                                    <input type="checkbox" class="<?=$tClassCheckNormal?>" name="ocbRefIntDocDT[]" value="<?=$aValue['FNXsdSeqNo']?>" <?=$tClassDisabledCheckbox?>>
                                    <span class="<?=$tClassCheckNormal?>" <?=$tClassDisabledCheckbox?>>&nbsp;</span>
                                </label>
                            </td>
                        <?php }
                    ?>

                    <td class="xRefIntTd text-center"><?=$nKey+1?></td>
                    <td class="xRefIntTd"><?=$aValue['FTPdtCode']?></td>
                    <td class="xRefIntTd"><?=$aValue['FTXsdPdtName']?></td>
                    <td class="xRefIntTd"><?=$aValue['FTXsdBarCode']?></td>
                    <td class="xRefIntTd"><?=$aValue['FTPunName']?></td>

                    <?php 
                        if($tDocType == 1){ //ใบขอเสนอราคา ?>
                            <td class="xRefIntTd text-right"><?=number_format($aValue['QTY'],$nOptDecimalShow)?></td>
                        <?php }else{ //ใบสั่งซื้อ ?>
                            <td class="xRefIntTd text-right"><?=number_format($aValue['FCXpdQtyApv'],$nOptDecimalShow)?></td>
                            <td class="xRefIntTd text-right"><?=number_format($aValue['FCXpdQtySo'],$nOptDecimalShow)?></td>
                            <td class="xRefIntTd text-right"><?=number_format($aValue['QTY'],$nOptDecimalShow)?></td>
                        <?php }
                    ?>
                </tr>
            <?php endforeach;?>
        <?php else:?>
            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php endif;?>
    </tbody>
  </table>
</div>

<script>
    $('#ocbRefIntDocDTAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbRefIntDocDT').prop('checked',true);
        }else{
            $('.ocbRefIntDocDT').prop('checked',false);
        }
    });
</script>