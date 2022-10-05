<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?=$tTWOPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?=$tTWOPunCode;?>">
        <table id="otbTWODocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                
                <tr class="xCNCenter">
                    <th class="text-center xCNTWOBeHideMQSS" id="othCheckboxHide">
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
                    <th class="xCNTextBold"><?= language('document/purchaseorder/purchaseorder', 'สถานะสต็อค')?></th>
                    <th class="xCNTextBold"><?=language('document/transferrequestbranch/transferrequestbranch','tTRBTable_unit')?></th>
                    <th class="xCNTWOBeHideMQSS"><?=language('document/purchaseinvoice/purchaseinvoice', 'tPITBDelete');?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyTWOPdtAdvTableList">
                <?php if($aDataDocDTTemp['rtCode'] == 1):?>
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
                                <td class="otdListItem xCNTWOBeHideMQSS">
                                    <label class="fancy-checkbox text-center">
                                        <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxTRBSelectMulDel(this)">
                                        <span class="ospListItem">&nbsp;</span>
                                    </label>
                                </td>
                            <?php } ?>
                            <td><?=$nKey;?></td>
                            <td><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td><?=$aDataTableVal['FTXtdPdtName'];?></td>
                            <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                            <td><?=$aDataTableVal['FTPunName'];?></td>
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
                            <td class="otdQty">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                                </div>
                            </td>
                            <td nowrap class="text-center xCNPIBeHideMQSS xCNTWOBeHideMQSS">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSnTWIDelPdtInDTTempSingle(this)">
                                </label>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr><td class="text-center xCNTextDetail2 xWTWOTextNotfoundDataPdtTable" colspan="100%"><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
<?php if($aDataDocDTTemp['rnAllPage'] > 1) : ?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataDocDTTemp['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataDocDTTemp['rnCurrentPage']?> / <?php echo $aDataDocDTTemp['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPageTWOPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvTWOPDTDocDTTempClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataDocDTTemp['rnAllPage'],$nPage+2)); $i++){?> 
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvTWOPDTDocDTTempClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataDocDTTemp['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvTWOPDTDocDTTempClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
<?php endif;?>

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
    $('#otbTWODocPdtAdvTableList #odvTBodyTWOPdtAdvTableList .ocbListItem').unbind().click(function(){
        var tTWODocNo    = $('#oetTWODocNo').val();
        var tTWOSeqNo    = $(this).parents('.xWPdtItem').data('seqno');
        var tTWOPdtCode  = $(this).parents('.xWPdtItem').data('pdtcode');
        var tTWOPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(this).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("TWO_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("TWO_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tTWODocNo,
                'tSeqNo'    : tTWOSeqNo,
                'tPdtCode'  : tTWOPdtCode,
                'tPunCode'  : tTWOPunCode,
            });
            localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxTWOTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStTWOFindObjectByKey(aArrayConvert[0],'tSeqNo',tTWOSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tTWODocNo,
                    'tSeqNo'    : tTWOSeqNo,
                    'tPdtCode'  : tTWOPdtCode,
                    'tPunCode'  : tTWOPunCode,
                });
                localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxTWOTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("TWO_LocalItemDataDelDtTemp");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tTWOSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxTWOTextInModalDelPdtDtTemp();
            }
        }
        JSxTWOShowButtonDelMutiDtTemp();
    });

    //ลบสินค้าใน Tmp - หลายตัว
    function FSxDOSelectMulDel(ptElm){
        var tTWODocNo    = $('#oetTWODocNo').val();
        var tTWOSeqNo    = $(ptElm).parents('.xWPdtItem').data('seqno');
        var tTWOPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        var tTWOPunCode  = $(ptElm).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("TWO_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("TWO_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tTWODocNo,
                'tSeqNo'    : tTWOSeqNo,
                'tPdtCode'  : tTWOPdtCode,
                'tPunCode'  : tTWOPunCode,
            });
            localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxTWOTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStTWOFindObjectByKey(aArrayConvert[0],'tSeqNo',tTWOSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tTWODocNo,
                    'tSeqNo'    : tTWOSeqNo,
                    'tPdtCode'  : tTWOPdtCode,
                    'tPunCode'  : tTWOPunCode,
                });
                localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxTWOTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("TWO_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tTWOSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("TWO_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxTWOTextInModalDelPdtDtTemp();
            }
        }
        JSxTWOShowButtonDelMutiDtTemp();
    
    }

    //กดโมเดลลบสินค้าใน Tmp
    function JSnTWIDelPdtInDTTempSingle(poEl) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal = $(poEl).parents("tr.xWPdtItem").attr("data-pdtcode");
            var tSeqno = $(poEl).parents("tr.xWPdtItem").attr("data-seqno");
            $(poEl).parents("tr.xWPdtItem").remove();
            JSnTWIRemovePdtDTTempSingle(tSeqno, tVal);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบสินค้าใน Tmp - ตัวเดียว
    function JSnTWIRemovePdtDTTempSingle(ptSeqNo, ptPdtCode) {
        var tTWIDocNo = $("#oetTWIDocNo").val();
        var tTWIBchCode = $('#oetSOFrmBchCode').val();
        var tTWIVatInOrEx = 1;
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "TXOOutTransferReceiptRemovePdtInDTTmp",
            data: {
                'tBchCode': tTWIBchCode,
                'tDocNo': tTWIDocNo,
                'nSeqNo': ptSeqNo,
                'tPdtCode': ptPdtCode,
                'tVatInOrEx': tTWIVatInOrEx,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['nStaEvent'] == '1') {
                    JSvTRNLoadPdtDataTableHtml();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    
    $(document).ready(function(){
        JSxEditQtyAndPrice();
        if((tTWOStaDoc == 3) || (tTWOStaApvDoc == 1 || tTWOStaPrcStkDoc == 1)){
            $('#otbTWODocPdtAdvTableList .xCNTWOBeHideMQSS').hide();
            $('.xCNPdtEditInLine ').attr('readonly', true);
        }

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
                    
                    FSvTWOEditPdtIntoTableDT(nSeq, tFieldName, nQty);
                }
            });

        }
    });

        // Check All
        $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvTWOMngDelPdtInTableDT #oliTWOBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvTWOMngDelPdtInTableDT #oliTWOBtnDeleteMulti").addClass("disabled");
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