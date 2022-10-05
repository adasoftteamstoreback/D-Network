
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbCLMStep1Point1DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold" style="width:5%;"><?=language('document/invoice/invoice','ลำดับ')?></th>
                    <th class="xCNTextBold"><?=language('document/invoice/invoice','รหัสสินค้า')?></th>
                    <th class="xCNTextBold"><?=language('document/invoice/invoice','ชื่อสินค้า')?></th>
                    <th class="xCNTextBold"><?=language('document/invoice/invoice','บาร์โค้ด')?></th>
                    <th class="xCNTextBold"><?=language('document/invoice/invoice','หน่วย')?></th>
                    <th class="xCNTextBold" style="width:10%;"><?=language('document/invoice/invoice','จำนวนส่งเคลม')?></th>
                    <?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        <th class="xCNTextBold" style="width:5%;"><?= language('common/main/main','tCMNActionDelete')?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataList['rtCode'] == 1 ):?>
                <?php 
                    if(FCNnHSizeOf($aDataList['raItems'])!=0){
                        foreach($aDataList['raItems'] AS $nKey => $aDataTableVal):?>
                            <?php $nKey = $aDataTableVal['FNPcdSeqNo']; ?>
                            <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?=$aDataTableVal['FTPcdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                                data-key="<?=$nKey?>"
                                data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                                data-seqno="<?=$nKey?>"
                                data-qty="<?=$aDataTableVal['FCPcdQty'];?>" >
                                <td style="text-align:center"><?=$nKey?></td>
                                <td><?=$aDataTableVal['FTPdtCode'];?></td>
                                <td><?=$aDataTableVal['FTPcdPdtName'];?></td>
                                <td><?=$aDataTableVal['FTPcdBarCode'];?></td>
                                <td><?=$aDataTableVal['FTPunName'];?></td>
                                <td class="otdQty">
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCPcdQty'],2));?>" autocomplete="off">
                                    </div>
                                </td>
                                <td  class="text-center xCNHideWhenCancelOrApprove">
                                    <label class="xCNTextLink">
                                        <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $( document ).ready(function() {
        //แก้ไขจำนวน
        JSxCLMStep1Point1EditQty();
    });
</script>