<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?=$tTWIPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?=$tTWIPunCode;?>">
        
        <table id="otbTWIDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="text-center xCNPIBeHideMQSS" id="othCheckboxHide">
                        <label class="fancy-checkbox" style="padding-left:7px">
                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxDOSelectAll(this)" >
                            <span class="">&nbsp;</span>
                        </label>
                    </th>
                    <th><?=language('document/purchaseinvoice/purchaseinvoice','tPITBNo')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_qty')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_unit')?></th>
                    <th class="xCNTextBold xCNPIBeHideMQSS"><?=language('common/main/main','tCMNActionDelete')?></th>
                    <!-- //xCNPIBeHideMQSS -->
                </tr>
            </thead>
            <tbody id="odvTBodyTWIPdtAdvTableList">
                <?php if($aDataDocDTTemp['rtCode'] == 1):?>
                    
                    <?php $i = 1; ?>
                    <?php foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
                    ?>
                        <tr
                            class="text-center xCNTextDetail2 nItem<?=$nKey?> xWPdtItem"
                            data-index="<?=$aDataTableVal['rtRowID'];?>"
                            data-key="<?=$nKey?>" 
                            data-seqno="<?=$nKey?>" 
                            data-docno="<?=$aDataTableVal['FTXthDocNo'];?>"
                            data-seqno="<?=$aDataTableVal['FNXtdSeqNo']?>"
                            data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>" 
                            data-pdtname="<?=$aDataTableVal['FTXtdPdtName'];?>"
                            data-puncode="<?=$aDataTableVal['FTPunCode'];?>"
                            data-qty="<?=$aDataTableVal['FCXtdQty'];?>"
                            data-setprice="<?=$aDataTableVal['FCXtdSetPrice'];?>"
                            data-stadis="<?=$aDataTableVal['FTXtdStaAlwDis']?>"
                            data-netafhd="<?=$aDataTableVal['FCXtdNetAfHD'];?>"
                        >   
                        <?php if((@$tTWIStaApv == '') && @$tTWIStaDoc != 3) { ?>
                            <td class="otdListItem">
                                <label class="fancy-checkbox text-center">
                                    <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxTRBSelectMulDel(this)">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </td>
                        <?php } ?>
                            <td><?=$i;?></td>
                            <td class="text-left"><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td class="text-left"><?=$aDataTableVal['FTXtdPdtName'];?></td>
                            <td class="text-left"><?=$aDataTableVal['FTXtdBarCode'];?></td>
                            <td class="text-left"><?=$aDataTableVal['FTPunName'];?></td>
                            <td class="otdQty">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                                </div>
                            </td>
                            <td nowrap class="text-center xCNPIBeHideMQSS">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSnTWIDelPdtInDTTempSingle(this)">
                                </label>
                            </td>
                        </tr>
                        <?php $i++;?>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td class="text-center xCNTextDetail2 xWTWITextNotfoundDataPdtTable" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ Modal Confirm Delete Documet Detail Dis ============================================ -->
    <div id="odvPIModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPIMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/purchaseinvoice/purchaseinvoice','tPIMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtPIConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtPICancelDeleteDTDis" type="button" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'ยกเลิก');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ================================================================================================================================= -->

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
    //ลบสินค้าใน Tmp - หลายตัว
    $('#otbTWIDocPdtAdvTableList #odvTBodyTWIPdtAdvTableList .ocbListItem').unbind().click(function(){
        var tTWIDocNo    = $('#oetTWIDocNo').val();
        var tTWISeqNo    = $(this).parents('.xWPdtItem').data('seqno');
        var tTWIPdtCode  = $(this).parents('.xWPdtItem').data('pdtcode');
        var tTWIPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(this).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("TWI_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("TWI_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tTWIDocNo,
                'tSeqNo'    : tTWISeqNo,
                'tPdtCode'  : tTWIPdtCode,
                'tPunCode'  : tTWIPunCode,
            });
            localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxTWITextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStTWIFindObjectByKey(aArrayConvert[0],'tSeqNo',tTWISeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tTWIDocNo,
                    'tSeqNo'    : tTWISeqNo,
                    'tPdtCode'  : tTWIPdtCode,
                    'tPunCode'  : tTWIPunCode,
                });
                localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxTWITextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("TWI_LocalItemDataDelDtTemp");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tTWISeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxTWITextInModalDelPdtDtTemp();
            }
        }
        JSxTWIShowButtonDelMutiDtTemp();
    });

     //ลบสินค้าใน Tmp - หลายตัว
     function FSxDOSelectMulDel(ptElm){
        var tTWIDocNo    = $('#oetTWIDocNo').val();
        var tTWISeqNo    = $(ptElm).parents('.xWPdtItem').data('seqno');
        var tTWIPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        var tTWIPunCode  = $(ptElm).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("TWI_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("TWI_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tTWIDocNo,
                'tSeqNo'    : tTWISeqNo,
                'tPdtCode'  : tTWIPdtCode,
                'tPunCode'  : tTWIPunCode,
            });
            localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxTWITextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStTWIFindObjectByKey(aArrayConvert[0],'tSeqNo',tTWISeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tTWIDocNo,
                    'tSeqNo'    : tTWISeqNo,
                    'tPdtCode'  : tTWIPdtCode,
                    'tPunCode'  : tTWIPunCode,
                });
                localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxTWITextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("TWI_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tTWISeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("TWI_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxTWITextInModalDelPdtDtTemp();
            }
        }
        JSxTWIShowButtonDelMutiDtTemp();
    
    }
    
    $(document).ready(function(){
        JSxEditQtyAndPrice();
        if((tTWIStaDoc == 3) || (tTWIStaApvDoc == 1 || tTWIStaPrcStkDoc == 1)){
            $('#otbTWIDocPdtAdvTableList .xCNPIBeHideMQSS').hide();
            $('.xCNPdtEditInLine ').attr('readonly', true)
        }
    });

    //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        $('.xCNQty').off().on('change keyup', function(e) {
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty        = $('#ohdQty'+nSeq).val();
                var tFieldName = "FCXtdQty";
                nNextTab = parseInt(nSeq)+1;
                $('.xWValueEditInLine'+nNextTab).focus().select();
                
                FSvTWIEditPdtIntoTableDT(nSeq, tFieldName, nQty);
            }
        });

    }

    $('.xWEditInlineElement').css('text-align','right');
     // Check All
     $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvTWIMngDelPdtInTableDT #oliTWIBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvTWIMngDelPdtInTableDT #oliTWIBtnDeleteMulti").addClass("disabled");
        }
    });

      function FSxDOSelectAll(){
    if($('.ocbListItemAll').is(":checked")){
        $('.ocbListItem').each(function (e) { 
            if(!$(this).is(":checked")){
                $(this).on( "click", FSxDOSelectMulDel(this) );
            }
    });
    }else{
        $('.ocbListItem').each(function (e) { 
            if($(this).is(":checked")){
                $(this).on( "click", FSxDOSelectMulDel(this) );
            }
    });
    }
    
}
</script>