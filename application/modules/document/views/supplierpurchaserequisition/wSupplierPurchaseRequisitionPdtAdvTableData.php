<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPRSPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPRSPunCode;?>">
        <input type="text" class="xCNHide" id="ohdPRSRtCode" value="<?php echo $aDataDocDTTemp['rtCode'];?>">
        <input type="text" class="xCNHide" id="ohdPRSStaDoc" value="<?php echo $tPRSStaDoc;?>">
        <input type="text" class="xCNHide" id="ohdPRSStaApv" value="<?php echo $tPRSStaApv;?>">
        <table id="otbPRSDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="text-center" id="othCheckboxHide">
                        <label class="fancy-checkbox">
                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxPRSSelectAll(this)">
                            <span class="">&nbsp;</span>
                        </label>
                    </th>
                    <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTable_qty')?></th>
                    <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTable_unit')?>ขอซื้อ</th>
                    <?php if(@$tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ ?>

                    <?php }else{ //ใบขอซื้อแบบแฟรนไชส์ ?>
                        <th class="xCNTextBold"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','จำนวนยืนยัน')?></th>
                    <?php } ?>
                    <th class="xCNPIBeHideMQSS"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyPRSPdtAdvTableList">
            <?php 
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
                        $nQTYDone = $aDataTableVal['FCXtdQtyOrd']; ?>
                    <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>" 
                        data-alwvat="<?=$aDataTableVal['FTXtdVatType'];?>" 
                        data-vat="<?=$aDataTableVal['FCXtdVatRate']?>" 
                        data-key="<?=$nKey?>" 
                        data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>" 
                        data-pdtName="<?=$aDataTableVal['FTXtdPdtName'];?>" 
                        data-seqno="<?=$nKey?>" 
                        data-setprice="<?=$aDataTableVal['FCXtdSetPrice'];?>" 
                        data-qty="<?=$aDataTableVal['FCXtdQty'];?>" 
                        data-netafhd="<?=$aDataTableVal['FCXtdNetAfHD'];?>" 
                        data-net="<?=$aDataTableVal['FCXtdNet'];?>" 
                        data-stadis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" 
                    >
                        <td class="otdListItem">
                            <label class="fancy-checkbox text-center">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPRSSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <td><?=$aDataTableVal['FTPdtCode'];?></td>
                        <td><?=$aDataTableVal['FTXtdPdtName'];?></td>
                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>
                        <td class="otdQty">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?>" id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <?php if(@$tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ ?>

                        <?php }else{ //ใบขอซื้อแบบแฟรนไชส์ ?>
                            <td class="otdQtyConfirm">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQtyConfirm form-control xCNInputNumericWithDecimal text-right xWValueEditInLineConfirm<?=$nKey?> xWShowInLine<?=$nKey?>" id="ohdQtyConfirm<?=$nKey?>" name="ohdQtyConfirm<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($nQTYDone,2));?>" autocomplete="off">
                                </div>
                            </td>
                        <?php } ?>

                        <td class="text-center xCNPIBeHideMQSS">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPRSDelPdtInDTTempSingle(this)">
                            </label>
                        </td>
                    </tr>
            <?php 
                    endforeach;
                else:
            ?>
                <tr><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ Modal Confirm Delete Documet Detail Dis ============================================ -->
<div id="odvPRSModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPRSMsgNotificationChangeData') ?></label>
            </div>
            <div class="modal-body">
                <label><?php echo language('document/purchaseorder/purchaseorder','tPRSMsgTextNotificationChangeData');?></label>
            </div>
            <div class="modal-footer">
                <button id="obtPRSConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                <button id="obtPRSCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก');?></button>
            </div>
        </div>
    </div>
</div>

<!--ลบสินค้าแบบตัวเดียว-->
<div id="odvModalDelDocSingle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmTWIConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!--ลบสินค้าแบบหลายตัว-->
<div id="odvModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<?php  include("script/jSupplierPurchaseRequisitionAdvTableData.php");?>

<script>  
    
    $( document ).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();    

        <?php if(@$tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ ?>
            if($('#ohdPRSStaApv').val() == 1 && $('#ohdPRSStaDoc').val() == 1){ //เอกสารอนุมัติเเล้ว
                $('.xCNBTNPrimeryDisChgPlus').hide();
                $("#othCheckboxHide").hide();
                $(".xCNPIBeHideMQSS").hide();
                $(".xCNIconTable").attr("onclick", "").unbind("click");
                $('.xCNPdtEditInLine').attr('readonly',true);
                $('#obtPRSBrowseCustomer').attr('disabled',true);
                $('.otdListItem').hide();
            }else if($('#ohdPRSStaDoc').val() == 3 ){ //เอกสารยกเลิก
                $('.xCNBTNPrimeryDisChgPlus').hide();
                $("#othCheckboxHide").hide();
                $(".xCNPIBeHideMQSS").hide();
                $(".xCNIconTable").attr("onclick", "").unbind("click");
                $('.xCNPdtEditInLine').attr('readonly',true);
                $('#obtPRSBrowseCustomer').attr('disabled',true);
                $('.otdListItem').hide();
            }
        <?php }else{ //ใบขอซื้อแบบแฟรนไชส์ ?>
            $('#oetPRSInsertBarcode').hide();
            $('#obtPRSApproveDocHQ').show();
            
            var tStaPrcDoc = $('#ohdPRSStaPrcDoc').val();
            if(tStaPrcDoc == 3){//สำนักงานใหญ่อนุมัติเเล้ว
                $(".xCNQtyConfirm").attr('readonly',true);
                $("#obtPRSApproveDocHQ").hide();
                $("#othCheckboxHide").hide();
                $('.otdListItem').hide();
                $(".xCNPIBeHideMQSS").hide();
                $(".xCNMsgDeletePDTInTableDT ").hide();
            }
        <?php } ?>

        //สรุปจำนวน
        JSxPRSCountPdtItems();
    });


    // Next Func จาก Browse PDT Center
    function FSvPRSNextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvPRSAddPdtIntoDocDTTemp(aNewPackData);         // Append HMTL
            FSvPRSAddBarcodeIntoDocDTTemp(aNewPackData);     // Insert Database
        }
    }

    // Append PDT
    function FSvPRSAddPdtIntoDocDTTemp(ptPdtData){
        JCNxCloseLoading();
        var aPackData               = JSON.parse(ptPdtData);
        var tCheckIteminTableClass  = $('#otbPRSDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nPRSODecimalShow        = $('#ohdPRSDecimalShow').val();
        if(tCheckIteminTableClass == true){
            $('#otbPRSDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbPRSDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        
        for(var i=0; i<nLen; i++){

            var oData           = aPackData[i];
            var oResult         = oData.packData;
            oResult.NetAfHD     = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.Net == '' || oResult.Net === undefined ? oResult.Price : oResult.Net);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);
            var tBarCode        = oResult.Barcode;              //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;              //รหัสสินค้า
            var tProductName    = oResult.PDTName;              //ชื่อสินค้า
            var tUnitName       = oResult.PUNName;              //ชื่อหน่วยสินค้า
            var nQty            = parseInt(oResult.Qty);        //จำนวน

            var tDuplicate      = $('#otbPRSDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmPRSFrmInfoOthReAddPdt').val();
            if(tDuplicate == true && InfoOthReAddPdt==1){
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);

                // รวมสินค้าซ้ำกรณีที่เปลี่ยนจากเลือกแบบแยกรายการเป็นบวกในรายการเดียวกัน
                var tCname = 'otr'+tProductCode+tBarCode;
                $('.'+tCname).each(function (e) { 
                        if(e == '0'){
                            $(this).find('.xCNQty').val(nNewValue);
                        }
                });

                //$('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);
            }else{//ถ้าสินค้าไม่ซ้ำ ก็บวกเพิ่มต่อเลย
                //จำนวน
                var oQty = '<div class="xWEditInLine'+nKey+'">';
                    oQty += '<input ';
                    oQty += 'type="text" ';
                    oQty += 'class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' xWShowInLine'+nKey+' "';
                    oQty += 'id="ohdQty'+nKey+'" ';
                    oQty += 'name="ohdQty'+nKey+'" '; 
                    oQty += 'data-seq='+nKey+' ';
                    oQty += 'maxlength="10" '; 
                    oQty += 'value="'+nQty+'"';
                    oQty += 'autocomplete="off" >';
                    oQty += '</div>';  

                tHTML += '<tr class="otr'+tProductCode+''+tBarCode+' xWPdtItem xWPdtItemList'+nKey+'"';
                tHTML += '  data-key="'+nKey+'"';
                tHTML += '  data-pdtcode="'+tProductCode+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-qty="'+nQty+'"';

                tHTML += '>';
                tHTML += '<td class="otdListItem">';
                tHTML += '  <label class="fancy-checkbox text-center">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPRSSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td>'+tProductCode+'</td>';
                tHTML += '<td>'+tProductName+'</td>';
                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="otdQty">'+oQty+'</td>';
                if($('#ohdPOSTaImport').val()==1){
                tHTML += '<td class="xPRSImportDT"> </td>';
                }
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPRSDelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbPRSDocPdtAdvTableList tbody').append(tHTML);

        JSxPRSCountPdtItems();
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();
    }

    // Check All
    $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvPRSMngDelPdtInTableDT #oliPRSBtnDeleteMulti").addClass("disabled");
        }
    });

    function FSxPRSSelectMulDel(ptElm){
        let tPRSDocNo    = $('#oetPRSDocNo').val();
        let tPRSSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPRSPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tPRSBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        var nPRSODecimalShow = $('#ohdPRSDecimalShow').val();
        // let tPRSPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("PRS_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("PRS_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tPRScNo'    : tPRSDocNo,
                'tSeqNo'    : tPRSSeqNo,
                'tPdtCode'  : tPRSPdtCode,
                'tBarCode'  : tPRSBarCode,
                // 'tPunCode'  : tPRSPunCode,
            });
            localStorage.setItem("PRS_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxPRSTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStPRSFindObjectByKey(aArrayConvert[0],'tSeqNo',tPRSSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tPRScNo'    : tPRSDocNo,
                    'tSeqNo'    : tPRSSeqNo,
                    'tPdtCode'  : tPRSPdtCode,
                    'tBarCode'  : tPRSBarCode,
                    // 'tPunCode'  : tPRSPunCode,
                });
                localStorage.setItem("PRS_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxPRSTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("PRS_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tPRSSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PRS_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxPRSTextInModalDelPdtDtTemp();
            }
        }
        JSxPRSShowButtonDelMutiDtTemp();
        // });
    }

    function JSxAddScollBarInTablePdt(){
        $('#otbPRSDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbPRSDocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbPRSDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');
        }
            
        if(rowCount >= 7){
            $('.xWShowInLine' + rowCount).focus();

            $('html, body').animate({
                scrollTop: ($("#oetPRSInsertBarcode").offset().top)-80
            }, 0);
        }

        if($('#oetPRSFrmCstCode').val() != ''){
            $('#oetPRSInsertBarcode').focus();
        }
    }

    //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        //จำนวนขอซื้อ
        $('.xCNQty').off().on('change keyup', function(e) {
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty        = $('#ohdQty'+nSeq).val();
                nNextTab = parseInt(nSeq)+1;
                $('.xWValueEditInLine'+nNextTab).focus().select();
                JSxGetDisChgList(nSeq);
            }
        });

        //จำนวนยืนยัน
        $('.xCNQtyConfirm').off().on('change keyup', function(e) {
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $('#ohdQtyConfirm'+nSeq).val();
                nNextTab    = parseInt(nSeq)+1;
                $('.xWValueEditInLineConfirm'+nNextTab).focus().select();
                JSxGetDisChgList(nSeq);
            }
        });

    }

    //เเก้ไขจำนวน
    function JSxGetDisChgList(pnSeq){

        if($('#ohdPRSTypeDocument').val() == 1){ //ใบขอซื้อแบบสำนักงานใหญ่
            var nQty            = $('#ohdQty'+pnSeq).val();
        }else{ //ใบขอซื้อแบบแฟรนไชส์
            var nQty            = $('#ohdQtyConfirm'+pnSeq).val();
        }

        var tPRSDocNo       = $("#oetPRSDocNo").val();
        var tPRSBchCode     = $("#ohdPRSBchCode").val();
        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docPRSEditPdtInDTDocTemp",
                data    : {
                    'tPRSBchCode'        : tPRSBchCode,
                    'tPRSDocNo'          : tPRSDocNo,
                    'nPRSSeqNo'          : pnSeq,
                    'nQty'               : nQty,
                    'tPRSTypeDocument'   : $('#ohdPRSTypeDocument').val()
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){ },
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }

    //ห้ามกด Enter
    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    function FSxPRSSelectAll(){
        if($('.ocbListItemAll').is(":checked")){
            $('.ocbListItem').each(function (e) { 
                if(!$(this).is(":checked")){
                    $(this).on( "click", FSxPRSSelectMulDel(this) );
                }
            });
        }else{
            $('.ocbListItem').each(function (e) { 
                if($(this).is(":checked")){
                    $(this).on( "click", FSxPRSSelectMulDel(this) );
                }
            });
        }
    }

</script>


