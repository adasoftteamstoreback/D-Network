<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tTBIPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tTBIPunCode;?>">
        <table id="otbTBIDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNPIBeHideMQSS"><?php echo language('document/saleorder/saleorder','tSOTBChoose')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOTBNo')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOARPPdtCode')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOARPPdtName')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOTable_barcode')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOTable_unit')?></th>
                    <th><?php echo language('document/saleorder/saleorder','tSOARPPdtQty')?></th>
                    <th class="xCNPIBeHideMQSS"><?php echo language('document/saleorder/saleorder', 'tSOTBDelete');?></th>
                    <th class="xCNPIBeHideMQSS xWPIDeleteBtnEditButtonPdt"><?php echo language('document/saleorder/saleorder','tSOTBEdit');?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyTBIPdtAdvTableList">
                <?php 
                    $nNumSeq    = 0; 
                    $nRowID     = 1;
                ?>
                <?php if($aDataDocDTTemp['rtCode'] == 1):?>
                    <?php foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): ?>
                        <tr
                            class="text-center xCNTextDetail2 nItem<?php echo $nNumSeq?> xWPdtItem"
                            data-index="<?php echo $nRowID;?>"
                            data-docno="<?php echo $aDataTableVal['FTXthDocNo'];?>"
                            data-seqno="<?php echo $aDataTableVal['FNXtdSeqNo']?>"
                            data-pdtcode="<?php echo $aDataTableVal['FTPdtCode'];?>" 
                            data-pdtname="<?php echo $aDataTableVal['FTXtdPdtName'];?>"
                            data-puncode="<?php echo $aDataTableVal['FTPunCode'];?>"
                            data-qty="<?php echo $aDataTableVal['FCXtdQty'];?>"
                            data-setprice="<?php echo $aDataTableVal['FCXtdSetPrice'];?>"
                            data-stadis="<?php echo $aDataTableVal['FTXtdStaAlwDis']?>"
                            data-netafhd="<?php echo $aDataTableVal['FCXtdNetAfHD'];?>"
                            data-remark="<?=$aDataTableVal['FTXtdRmk']?>"
                        >
                            <td class="text-center xCNPIBeHideMQSS">
                                <label class="fancy-checkbox">
                                    <?php if(empty($tTBIStaApv)){  ?>
                                        <input id="ocbListItem<?php echo $nRowID;?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                    <?php } ?> 
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-left" ><label><?php echo $nRowID;?></label></td>
                            <td class="text-left" ><label class="text-left xCNPdtFont xWShowInLine xWShowValueFTPdtCode<?php echo $nRowID?>"><?php echo $aDataTableVal['FTPdtCode']?></label></td>
                            <td class="text-left" ><label class="text-left xCNPdtFont xWShowInLine xWShowValueFTXtdPdtName<?php echo $nRowID?>"><?php echo $aDataTableVal['FTXtdPdtName']?></label></td>
                            <td class="text-left" ><label class="text-left xCNPdtFont xWShowInLine xWShowValueFTXtdBarCode<?php echo $nRowID?>"><?php echo $aDataTableVal['FTXtdBarCode']?></label></td>
                            <td class="text-left"><label class="text-left xCNPdtFont xWShowInLine xWShowValueFTPunName<?php echo $nRowID?>"><?php echo $aDataTableVal['FTPunName']?></label></td>
                            <td nowrap class="text-right" class="otdQty">
                                <?php if($aDataTableVal['FTXtdRmk'] == 'RefDoc'){ ?>
                                    <label id="ohdFCXtdQty<?=$nRowID?>" name="ohdFCXtdQty<?=$nRowID?>" ><?=$aDataTableVal['FCXtdQty'] != '' ? number_format($aDataTableVal['FCXtdQty'], $nOptDecimalShow, '.', ',') : number_format(0, $nOptDecimalShow,'.',',');?></label>
                                <?php }else{ ?>
                                    <!-- <label 
                                    data-field="FCXtdQty"
                                    data-seq="<?php echo $aDataTableVal['FNXtdSeqNo']?>"
                                    data-demo="TextDEmo"
                                    class="xCNPdtFont xWShowInLine<?php echo $nRowID?> xWShowValueFCXtdQty<?php echo $nRowID?>">
                                    <?php echo  $aDataTableVal['FCXtdQty'] != '' ? "".$aDataTableVal['FCXtdQty'] : '1'; ?>
                                    </label>
                                    <div class="xCNHide xWEditInLine<?php echo $aDataTableVal['FNXtdSeqNo']?>">
                                        <input 
                                            type="text" 
                                            class="form-control xCNPdtEditInLine xWValueEditInLine<?php echo $nRowID?> xCNInputNumericWithDecimal text-right"
                                            id="ohdFCXtdQty<?php echo $nRowID?>" 
                                            name="ohdFCXtdQty<?php echo $nRowID?>" 
                                            maxlength="11" 
                                            value=""
                                            >
                                    </div>    -->

                                    <div class="xWEditInLine<?=$aDataTableVal['FNXtdSeqNo']?>">
                                        <input type="text" class="xCNQty form-control xWEditPdtInline xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$aDataTableVal['FNXtdSeqNo']?> xWShowInLine<?=$aDataTableVal['FNXtdSeqNo']?> " id="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" name="ohdQty<?=$aDataTableVal['FNXtdSeqNo']?>" data-seq="<?=$aDataTableVal['FNXtdSeqNo']?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" autocomplete="off">
                                    </div>

                                <?php } ?>
                            </td>
                            <td nowrap class="text-center xCNPIBeHideMQSS">
                                <label class="xCNTextLink">
                                <?php if(empty($tTBIStaApv)){ ?>
                                    <img class="xCNIconTable" src="<?php echo  base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSnTBIDelPdtInDTTempSingle(this)">
                                <?php } ?>
                                </label>
                            </td>
                            <td class="xCNPIBeHideMQSS"></td>
                        </tr>
                        <?php 
                            $nNumSeq++;
                            $nRowID++;
                        ?>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td class="text-center xCNTextDetail2 xWPITextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
<?php if($aDataDocDTTemp['rnAllPage'] > 1) : ?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataDocDTTemp['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> </p>
    </div>
<?php endif;?>
<!-- ============================================ Modal Confirm Delete Documet Detail Dis ============================================ -->
<div id="odvTIBModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tTBIMsgNotificationChangeData') ?></label>
            </div>
            <div class="modal-body">
                <label><?php echo language('document/saleorder/saleorder','tSOMsgTextNotificationChangeData');?></label>
            </div>
            <div class="modal-footer">
                <button id="obtTBIConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                <button id="obtTBICancelDeleteDTDis" type="button" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'ยกเลิก');?></button>
            </div>
        </div>
    </div>
</div>

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
        FSvTBIEditPdtIntoTableDT(pnSeq, "FCXtdQty", nQty);
    }
</script>

<?php  include("script/jTransferReceiptbranchDataTable.php");?>