<style>
    #odvRowDataEndOfBill .panel-heading{
        padding-top     : 10px !important;
        padding-bottom  : 10px !important;
    }
    #odvRowDataEndOfBill .panel-body{
        padding-top     : 0px !important;
        padding-bottom  : 0px !important;
    }
    #odvRowDataEndOfBill .list-group-item {
        padding-left    : 0px !important;
        padding-right   : 0px !important;
        border          : 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color           : #232C3D !important;
        font-weight     : 900;
    }
</style>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbIVDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNHideWhenCancelOrApprove">
                        <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                            <span class="ospListItem">&nbsp;</span>
                        </label>
                    </th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTBNo')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_unit')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_qty')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_price')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_discount')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_grand')?></th>
                    <th class="xCNHideWhenCancelOrApprove"><?=language('document/purchaseorder/purchaseorder', 'tPOTBDelete');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($aDataDocDTTemp['rtCode'] == 1){
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal){ ?>
                        <?php $nKey = $aDataTableVal['FNXtdSeqNo']; ?>
                        <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?=$aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                            data-alwvat="<?=$aDataTableVal['FTXtdVatType'];?>"
                            data-vat="<?=$aDataTableVal['FCXtdVatRate']?>"
                            data-key="<?=$nKey?>"
                            data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>"
                            data-seqno="<?=$nKey?>"
                            data-setprice="<?=$aDataTableVal['FCXtdSetPrice'];?>"
                            data-qty="<?=$aDataTableVal['FCXtdQty'];?>"
                            data-netafhd="<?=$aDataTableVal['FCXtdNetAfHD'];?>"
                            data-net="<?=$aDataTableVal['FCXtdNet'];?>"
                            data-stadis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>"
                            data-TypePdt="<?=$aDataTableVal['FTTmpStatus'];?>"
                            style="background-color: rgb(255, 255, 255);" >
                            <td class="xCNHideWhenCancelOrApprove" style="text-align:center">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxIVSelectMulDel(this)">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </td>
                            <td style="text-align:center"><?=$nKey?></td>
                            <td><?=$aDataTableVal['FTPdtCode'];?></td>

                            <?php if($aDataTableVal['FTTmpStatus'] == '5' && $tIVStaDoc == '3'){?>
                                <td>
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty xCNHide form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                                </div>
                                <?=$aDataTableVal['FTXtdPdtName'];?>
                            </td>
                            <?php }elseif($aDataTableVal['FTTmpStatus'] == '5' && $tIVStaApv != '1'){?>
                            <td class="otdQty">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                                </div>
                            </td>
                            <?php }else{ ?>
                            <td>
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty xCNHide form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                                </div>
                                <?=$aDataTableVal['FTXtdPdtName'];?>
                            </td>
                            <?php } ?>

                            <!-- <td><?=$aDataTableVal['FTXtdPdtName'];?></td> -->

                            <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                            <td><?=$aDataTableVal['FTPunName'];?></td>
                            <td class="otdQty">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                                </div>
                            </td>
                            <td class="otdPrice">
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> " id="ohdPrice<?=$nKey?>" name="ohdPrice<?=$nKey?>" maxlength="10" data-alwdis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" data-seq="<?=$nKey?>" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdSetPrice'],2));?>" autocomplete="off">
                                </div> 
                            </td>
                            <td>
                                <?php if($aDataTableVal['FTXtdStaAlwDis'] == 1){ ?>
                                <div>
                                    <button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvIVCallModalDisChagDT(this)" type="button">+</button>
                                    <label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp<?=$nKey?>"><?=$aDataTableVal['FTXtdDisChgTxt'];?></label>
                                </div>
                                <?php }else{ ?>
                                    <label><?=language('document/purchaseorder/purchaseorder','tPODiscountisnotallowed');?></label>
                                <?php } ?>
                            </td>
                            <td class="text-right">
                                <span id="ospGrandTotal<?=$nKey?>"><?=number_format($aDataTableVal['FCXtdNet'],2);?></span>
                                <span id="ospnetAfterHD<?=$nKey?>" style="display: none;"><?=number_format($aDataTableVal['FCXtdNetAfHD'],2);?></span>
                            </td>
                            <td  class="text-center xCNHideWhenCancelOrApprove">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">
                                </label>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">
                            <?=language('common/main/main','tCMNNotFoundData')?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ ลบสินค้าตัวเดียว =============================================== -->
<div id="odvIVModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPIMsgNotificationChangeData') ?></label>
            </div>
            <div class="modal-body">
                <label><?=language('document/purchaseinvoice/purchaseinvoice','tPIMsgTextNotificationChangeData');?></label>
            </div>
            <div class="modal-footer">
                <button id="obtIVConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                <button id="obtIVCancelDeleteDTDis" type="button" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tCancel');?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
     $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    //คลิกเลือกทั้งหมดในสินค้า DT Tmp
    $('#ocmCENCheckDeleteAll').change(function(){
        var bStatus = $(this).is(":checked") ? true : false;
		if(bStatus == false){
			localStorage.removeItem("IV_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
		}else{
            localStorage.removeItem("IV_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
			$('.ocbListItem').each(function (e) {
                $(this).on( "click", FSxIVSelectMulDel(this) );
            });
		}
    });


    $(document).ready(function() {
        JSxAddScollBarInTablePdt();

        JSxRendercalculate();

        JSxEditQtyAndPrice();

        //ลบแบบหลายรายการ
        $('#odvIVModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSnIVRemovePdtDTTempMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        var tStatusDoc = $('#ohdIVStaDoc').val();
        var tStatusApv = $('#ohdIVStaApv').val();
        if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){ //เอกสารยกเลิก + เอกสารอนุมัติแล้ว
            //อินพุต
            $('#oetSearchPdtHTML').attr('readonly', false);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();

            //ปุ่มส่วนลดรายการ
            $('.xCNBTNPrimeryDisChgPlus').hide();

            //จำนวน และราคา
            $('.xCNPdtEditInLine').attr('readonly',true);
        }

    });

    //เอาข้อมูลสินค้าลง Temp
    function JSxIVEventInsertToTemp(paData){
        if($("#ohdIVRoute").val() == "docInvoiceEventAdd"){
            var tIVDocNo    = "";
        }else{
            var tIVDocNo    = $("#oetIVDocNo").val();
        }

        var tIVVATInOrEx    = $('#ocmIVfoVatInOrEx').val();
        var tIVOptionAddPdt = $('#ocmIVFrmInfoOthReAddPdt').val();

        var tCheckIteminTableClass = $('#otbIVDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        
        if(tCheckIteminTableClass == true){
            var nKey        = 1;
        }else{
            var nKey        = parseInt($('#otbIVDocPdtAdvTableList tr:last').attr('data-seqno'));
        }

        $.ajax({
            type    : "POST",
            url     : "docInvoiceAddPdtIntoDTDocTemp",
            data    :{
                'tBCHCode'              : $('#ohdIVBchCode').val(),
                'tIVDocNo'              : tIVDocNo,
                'tIVVATInOrEx'          : tIVVATInOrEx,
                'tIVOptionAddPdt'       : tIVOptionAddPdt,
                'oPdtData'              : paData,
                'tSeqNo'                : nKey
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if(aResult['nStaEvent']==1){
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(jqXHR);
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Render ตาราง
    function JSxIVEventRenderTemp(paData){
        JCNxCloseLoading();

        //ช่องสแกนต้องเปิดเมื่อมีรายการใหม่เพิ่มขึ้นไป
        $('#oetIVInsertBarcode').attr('readonly',false);
        $('#oetIVInsertBarcode').val('');

        var aPackData = JSON.parse(paData);

        var tCheckIteminTableClass = $('#otbIVDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        if(tCheckIteminTableClass == true){
            $('#otbIVDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbIVDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        for(var i=0; i<nLen; i++){
            var oData           = aPackData[i];

            var oResult         = oData.packData;
            oResult.NetAfHD     = (oResult.nCostSTD == '' || oResult.nCostSTD === undefined ? 0 : oResult.nCostSTD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.nCostSTD == '' || oResult.nCostSTD === undefined ? oResult.Price : oResult.nCostSTD);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);
            var tPdtforSys      = oResult.PDTSpc;           //ประเภทสินค้า
            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tPunCode        = oResult.PUNCode;          //รหัสหน่วย
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            var nPrice          = (parseFloat(accounting.unformat(oResult.nCostSTD))).toFixed(2);                           //ราคา
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //อนุญาตคำนวณลด
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //อนุญาตคำนวณภาษี
            var nVat            = (oResult.nVat == ''   || oResult.nVat === undefined   ? 7 : parseFloat(oResult.nVat).toFixed(2));  //ภาษีจากผู้จำหน่าย
            var nQty            = parseInt(oResult.Qty);       //จำนวน
            var nNetAfHD        = (parseFloat(oResult.NetAfHD)).toFixed(2);
            var cNet            = (parseFloat(oResult.Net)).toFixed(2);
            var tDisChgTxt      = oResult.tDisChgTxt;
            var tDuplicate      = $('#otbIVDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmIVFrmInfoOthReAddPdt').val();
            var tTypePDT        = oResult.tTypePDT

            if(tDuplicate == true && InfoOthReAddPdt==1){
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                // var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                // var nNewValue   = parseInt(nValOld) + parseInt(1);
                // $('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);
                // $('.otr'+tProductCode+tBarCode).find('.xCNQtyFhn').text(nNewValue.toFixed(2));


                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);
                var tCname      = 'otr'+tProductCode+tBarCode;
                $('.'+tCname).each(function (e) {
                    if(e == '0'){
                        $(this).find('.xCNQty').val(nNewValue);
                    }
                });

                var nGrandOld   = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').val();
                var nGrand      = parseInt(nNewValue) * parseFloat(nGrandOld);
                var nSeqOld     = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').attr('data-seq');
                $('#ospGrandTotal'+nSeqOld).text(numberWithCommas(nGrand.toFixed(2)));
            }else{
                //ถ้าสินค้าไม่ซ้ำ ก็บวกเพิ่มต่อเลย
                if(nAlwDiscount == 1){ //อนุญาตลด
                    var oAlwDis = ' <div class="xWIVDisChgDTForm">';
                        oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvIVCallModalDisChagDT(this)" type="button">+</button>';
                        oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp'+nKey+'">'+tDisChgTxt+'</label>';
                        oAlwDis += '</div>';
                }else{
                    var oAlwDis = 'ไม่อนุญาตให้ส่วนลด';
                }

                //ราคา
                var oPrice = '<div class="xWEditInLine'+nKey+'">';
                    oPrice += '<input ';
                    oPrice += 'type="text" ';
                    oPrice += 'class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' "';
                    oPrice += 'id="ohdPrice'+nKey+'" ';
                    oPrice += 'name="ohdPrice'+nKey+'" ';
                    oPrice += 'maxlength="10" ';
                    oPrice += 'data-alwdis='+nAlwDiscount+' ';
                    oPrice += 'data-seq='+nKey+' ';
                    oPrice += 'value="'+nPrice+'"';
                    oPrice += 'autocomplete="off" >';
                    oPrice += '</div>';

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
                    tHTML += '  data-index="'+nKey+'"';
                    tHTML += '  data-alwvat="'+nAlwVat+'"';
                    tHTML += '  data-vat="'+nVat+'"';
                    tHTML += '  data-seqno="'+nKey+'"';
                    tHTML += '  data-key="'+nKey+'"';
                    tHTML += '  data-pdtcode="'+tProductCode+'"';
                    tHTML += '  data-seqno="'+nKey+'"';
                    tHTML += '  data-setprice="'+nPrice+'"';
                    tHTML += '  data-qty="'+nQty+'"';
                    tHTML += '  data-netafhd="'+nNetAfHD+'"';
                    tHTML += '  data-net="'+cNet+'"';
                    tHTML += '  data-stadis="'+nAlwDiscount+'"';
                    tHTML += '  data-TypePdt="'+tTypePDT+'"';
                    tHTML += '>';
                    tHTML += '<td align="center">';
                    tHTML += '  <label class="fancy-checkbox">';
                    tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxIVSelectMulDel(this)">';
                    tHTML += '      <span class="ospListItem">&nbsp;</span>';
                    tHTML += '  </label>';
                    tHTML += '</td>';
                    tHTML += '<td align="center">'+nKey+'</td>';
                    tHTML += '<td>'+tProductCode+'</td>';

                    if(tTypePDT == '5'){
                        tHTML += '<td><div class="xWEditInLine'+nKey+'">';
                        tHTML += '<input ';
                        tHTML += 'type="text" ';
                        tHTML += 'class="xCNName form-control xCNPdtEditInLine text-left xWValueEditNameInLine'+nKey+' xWShowNameInLine'+nKey+' "';
                        tHTML += 'id="ohdPdtName'+nKey+'" ';
                        tHTML += 'name="ohdPdtName'+nKey+'" '; 
                        tHTML += 'data-seq='+nKey+' ';
                        tHTML += 'value="'+tProductName+'"';
                        tHTML += 'autocomplete="off" >';
                        tHTML += '</div></td>';  
                    }else{
                        tHTML += '<td><div class="xWEditInLine'+nKey+'">';
                        tHTML += '<input ';
                        tHTML += 'type="text" ';
                        tHTML += 'class="xCNName xCNHide form-control xCNPdtEditInLine text-left xWValueEditNameInLine'+nKey+' xWShowNameInLine'+nKey+' "';
                        tHTML += 'id="ohdPdtName'+nKey+'" ';
                        tHTML += 'name="ohdPdtName'+nKey+'" '; 
                        tHTML += 'data-seq='+nKey+' ';
                        tHTML += 'value="'+tProductName+'"';
                        tHTML += 'autocomplete="off" >';
                        tHTML += tProductName+'</div></td>';  
                    }
                    
                    tHTML += '<td>'+tBarCode+'</td>';
                    tHTML += '<td>'+tUnitName+'</td>';
                    tHTML += '<td class="otdQty text-right" >'+oQty+'</td>';
                    tHTML += '<td class="otdPrice">'+oPrice+'</td>';
                    tHTML += '<td>'+oAlwDis+'</td>';
                    tHTML += '<td class="text-right"><span id="ospGrandTotal'+nKey+'">'+cNet+'</span>';
                    tHTML += '    <span id="ospnetAfterHD'+nKey+'" style="display: none;">'+nNetAfHD+'</span>';
                    tHTML += '</td>';
                    tHTML += '<td nowrap class="text-center">';
                    tHTML += '  <label class="xCNTextLink">';
                    tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">';
                    tHTML += '  </label>';
                    tHTML += '</td>';
                    tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbIVDocPdtAdvTableList tbody').append(tHTML);

        //สกอร์บาร์
        JSxAddScollBarInTablePdt();

        //คำนวณเงิน
        JSxRendercalculate();

        //เพิ่มฟังก์ชั่นเเก้ไขจำนวน + ราคาต่อหน่วย
        JSxEditQtyAndPrice();
    }

    //คำนวณจำนวนเงินจากตางราง DT
    function JSxRendercalculate(){
        var nTotal              = 0;
        var nTotal_alwDiscount  = 0;
        $(".xCNPrice").each(function(e) {
            var nSeq    = $(this).attr('data-seq');
            var nValue  = $('#ospGrandTotal'+nSeq).text();
            var nValue  = nValue.replace(/,/g, '');
            nTotal      = parseFloat(nTotal) + parseFloat(nValue);
            if($(this).attr('data-alwdis') == 1){
                nTotal_alwDiscount  = parseFloat(nTotal_alwDiscount) + parseFloat(nValue);
            };
           
        });
        
        //จำนวนเงินรวม
        $('#olbIVSumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(2)));

        //จำนวนเงินรวม ที่อนุญาตลด
        $('#olbIVSumFCXtdNetAlwDis').val(nTotal_alwDiscount);

        //คิดส่วนลดใหม่
        var tChgHD          = $('#olbIVDisChgHD').text();
        var tChgHDNoComma   = $('#ohdIVHiddenDisChgHD').val();
        var nNewDiscount    = 0;
        if(tChgHD != '' && tChgHD != null){ //มีส่วนลดท้ายบิล
            var aChgHD          = tChgHD.split(",");
            var aChgHDNoComma   = tChgHDNoComma.split(",");
            var nNetAlwDis      = $('#olbIVSumFCXtdNetAlwDis').val();
            for(var i=0; i<aChgHDNoComma.length; i++){
                if(aChgHDNoComma[i] != '' && aChgHDNoComma[i] != null){
                    if(aChgHDNoComma[i].search("%") == -1){
                        //ไม่เจอ = ต้องคำนวณแบบบาท
                        var nVal        = aChgHDNoComma[i];
                        var nCal        = (parseFloat(nNetAlwDis) + parseFloat(nVal));
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }else{
                        //เจอ = ต้องคำนวณแบบ %
                        var nPercent    = aChgHDNoComma[i];
                        var nPercent    = nPercent.replace("%", "");
                        var tCondition  = nPercent.substr(0, 1);
                        var nValPercent = Math.abs(nPercent);
                        if(tCondition == '-'){
                            var nCal        = parseFloat(nNetAlwDis) - ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                            if(nCal == 0){
                                var nCal    = -nNetAlwDis;
                            }else{
                                var nCal    = nCal;
                            }
                        }else if(tCondition == '+'){
                            var nCal        = parseFloat(nNetAlwDis) + ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                        }
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }
                }
            }
            var nDiscount   = (nNetAlwDis - parseFloat($('#olbIVSumFCXtdNetAlwDis').val()));
            $('#olbIVSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(2)));
            
            //Prorate
            JSxProrate();
        }
        var nTotalFisrt = $('#olbIVSumFCXtdNet').text().replace(/,/g, '');
        var nDiscount   = $('#olbIVSumFCXtdAmt').text().replace(/,/g, '');
        var nResult     = parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount);
        $('#olbIVSumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(2)));

        //คำนวณภาษี
        JSxCalculateVat();
    }

    //คำนวณค่าภาษีแบบเฉลี่ย
    function JSxProrate(){
        var pnSumDiscount       = $('#olbIVSumFCXtdAmt').text().replace(/,/g, '');
        var pnSum               = $('#olbIVSumFCXtdNetAlwDis').val().replace(/,/g, '');
        var length              = $(".xCNPrice").length;
        var nSumProrate         = 0;
        var nDifferenceProrate  = 0;
        $(".xCNPrice").each(function(index,e) {

            var nSeq    = $(this).attr('data-seq');
            var alwdis  = $(this).attr('data-alwdis');
            var nValue  = $('#ospGrandTotal'+nSeq).text();
            var nValue  = parseFloat(nValue.replace(/,/g, ''));
            var nProrate = (pnSumDiscount * nValue) / pnSum;
            var netAfterHD = 0 ;
            if(alwdis==1){
                nSumProrate     = parseFloat(nSumProrate) + parseFloat(nProrate);
                if(index === (length - 1)){
                    nDifferenceProrate = pnSumDiscount - nSumProrate;
                    nProrate = nProrate + nDifferenceProrate;
                    netAfterHD =  nValue + nProrate;
                }else{
                    nProrate = nProrate;
                    netAfterHD =  nValue + nProrate;
                }
                $('#ospnetAfterHD'+nSeq).text(numberWithCommas(parseFloat(nValue+nProrate).toFixed(2)));
            }
        });
    }

    //คำนวณภาษี
    $('#ocmIVfoVatInOrEx').change(function (){ JSxRendercalculate(); });

    function JSxCalculateVat(){
        // ยอดรวมภาษีมูลค่าเพิ่ม
        var tIVVat      = parseFloat($('#ohdIVVat').val());
        var tIVVatCal   = parseFloat($('#ohdIVVatCal').val());


        var nDecimalShow    = $('#ohdIVDecimalShow').val();
        var tVatList        = '';
        var aVat            = [];
        var cXphAmtV        = 0;
        var cXphAmtNV       = 0;
        $('#otbIVDocPdtAdvTableList tbody tr').each(function(){
            var nAlwVat     = $(this).attr('data-alwvat');
            var nVat        = parseFloat($(this).attr('data-vat'));
            var nKey        = $(this).attr('data-seqno');
            var tTypeVat    = $('#ocmIVfoVatInOrEx').val();
            // Get Amt Value 
            var nValue  = $('#ospGrandTotal'+nKey).text();
            nValue      = nValue.replace(/,/g, '');
            if(nAlwVat == 1){
                //อนุญาตคิด VAT
                if(tTypeVat == 1){
                    // ภาษีรวมใน tSoot = net - ((net * 100) / (100 + rate));
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult   = parseFloat(nTotalVat).toFixed(nDecimalShow);
                }else if(tTypeVat == 2){
                    // ภาษีแยกนอก tSoot = net - (net * (100 + 7) / 100) - net;
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult   = parseFloat(nTotalVat).toFixed(nDecimalShow);
                }
                var oVat    = { VAT: nVat , VALUE: nResult };
                aVat.push(oVat);
                cXphAmtV    += parseFloat(nValue);
            }else{
                cXphAmtNV   += parseFloat(nValue);
            }
        });


        //เรียงลำดับ array ใหม่
        aVat.sort(function (a, b) {
            return a.VAT - b.VAT;
        });
        //รวมค่าใน array กรณี vat ซ้ำ
        var nVATStart       = 0;
        var nSumValueVat    = 0;
        var aSumVat         = [];
        for(var i=0; i<aVat.length; i++){


            if(nVATStart == aVat[i].VAT){
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                aSumVat.pop();
            }else{
                nSumValueVat = 0;
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                nVATStart    = aVat[i].VAT;
            }



            var oSum = { VAT: nVATStart , VALUE: nSumValueVat };




            aSumVat.push(oSum);
        }


        //เอา VAT ไปทำในตาราง
        
        if(tIVVat == tIVVatCal){
            var nSumVatHD   = parseFloat($('#ohdIVSumFCXtdVat').val());
            var nSumVat     = 0;
            var nCount      = 1;
            for(var j=0; j<aSumVat.length; j++){
                var tVatRate    = aSumVat[j].VAT;
                if(nCount != aSumVat.length){
                    var tSumVat = parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow);
                }else{
                    var tSumVat = (aSumVat[j].VALUE - nSumVat).toFixed(nDecimalShow);
                }
                tVatList    += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nDecimalShow)) + '</label><div class="clearfix"></div></li>';
                nSumVat     += parseFloat(aSumVat[j].VALUE);
                nCount++;
            }

            // Vat Table List
            $('#oulIVDataListVat').html(tVatList);


            // Cheack Vat กรณีมีการแก้ไข Vat จาก User 
            // ยอดรวมภาษีมูลค่าเพิ่ม
            $('#olbIVVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));
            
            $('#oetIVSumFCXtdVat').val(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));
            $('#ohdIVSumFCXtdVat').val(nSumVat.toFixed(nDecimalShow));
        }else{

            // ยอดรวมภาษีมูลค่าเพิ่ม
            $('#olbIVVatSum').text(numberWithCommas(parseFloat(tIVVat).toFixed(nDecimalShow)));

            $('#oetIVSumFCXtdVat').val(numberWithCommas(parseFloat(tIVVat).toFixed(nDecimalShow)));
            $('#ohdIVSumFCXtdVat').val(tIVVat.toFixed(nDecimalShow));
        }
        
        //สรุปราคารวม
        var tTypeVat = $('#ocmIVfoVatInOrEx').val();
        if(tTypeVat == 1){ //คิดแบบรวมใน
            var nTotal          = parseFloat($('#olbIVSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#oetIVSumFCXtdVat').val().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal);
        }else if(tTypeVat == 2){ //คิดแบบแยกนอก
            var nTotal          = parseFloat($('#olbIVSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#oetIVSumFCXtdVat').val().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal) + parseFloat(nVat);
        }

        $('#olbIVCalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));

        //ราคารวมทั้งหมด ตัวเลขบาท
        var tTextTotal  = $('#olbIVCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath 	= ArabicNumberToText(tTextTotal);
        $('#odvIVDataTextBath').text(tThaibath);

    }

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }

    //คำนวณท้ายบิล
    function JSxIVSetFooterEndOfBill(poParams){
        console.log(poParams);
        /* ================================================= Left End Of Bill ================================================= */
        var tTextBath   = poParams.tTextBath;
        $('#odvIVDataTextBath').text(tTextBath);
        // รายการ vat
        var aVatItems   = poParams.aEndOfBillVat.aItems;
        var tVatList    = "";
        if(aVatItems.length > 0){
            for(var i = 0; i < aVatItems.length; i++){
                var tVatRate = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow;?>) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(<?=$nOptDecimalShow?>)) + '</label><div class="clearfix"></div></li>';
            }
        }else{
            tVatList += '<li class="list-group-item"><label class="pull-left">0%</label><label class="pull-right">0.00</label><div class="clearfix"></div></li>';
        }
        
        $('#oulIVDataListVat').html(tVatList);
        // ยอดรวมภาษีมูลค่าเพิ่ม
        var cSumVat     = poParams.aEndOfBillVat.cVatSum;
        $('#olbIVVatSum').text(cSumVat);
        /* ================================================= Right End Of Bill ================================================ */
        var cCalFCXphGrand      = poParams.aEndOfBillCal.cCalFCXphGrand;
        var cSumFCXtdAmt        = poParams.aEndOfBillCal.cSumFCXtdAmt;
        var cSumFCXtdNet        = poParams.aEndOfBillCal.cSumFCXtdNet;
        var cSumFCXtdNetAfHD    = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
        var cSumFCXtdVat        = poParams.aEndOfBillCal.cSumFCXtdVat;
        var tDisChgTxt          = poParams.aEndOfBillCal.tDisChgTxt;

        if(tDisChgTxt == '' || tDisChgTxt == null){

        }else{
            var tTextDisChg    = '';
            var aExplode       = tDisChgTxt.split(",");
            for(var i=0; i<aExplode.length; i++){
                if(aExplode[i].indexOf("%") != '-1'){
                    tTextDisChg += aExplode[i] + ',';
                }else{
                    tTextDisChg += accounting.formatNumber(aExplode[i], 2, ',') + ',';
                }

                //ถ้าเป็นตัวท้ายให้ลบ comma ออก
                if(i == aExplode.length - 1 ){
                    tTextDisChg = tTextDisChg.substring(tTextDisChg.length-1,-1);
                }
            }
        }

        // จำนวนเงินรวม
        $('#olbIVSumFCXtdNet').text(cSumFCXtdNet);
        // ลด/ชาร์จ
        $('#olbIVSumFCXtdAmt').text(cSumFCXtdAmt);

        // ยอดรวมหลังลด/ชาร์จ
        $('#olbIVSumFCXtdNetAfHD').text(accounting.formatNumber(cSumFCXtdNetAfHD, 2, ','));

        // ยอดรวมภาษีมูลค่าเพิ่ม
        $('#oetIVSumFCXtdVat').val(cSumFCXtdVat);
        $('#ohdIVSumFCXtdVat').val(cSumFCXtdVat.replace(",", ""));



        // จำนวนเงินรวมทั้งสิ้น
        $('#olbIVCalFCXphGrand').text(cCalFCXphGrand);
        //จำนวนลด/ชาร์จ ท้ายบิล
        $('#olbIVDisChgHD').text(tTextDisChg);
        $('#ohdIVHiddenDisChgHD').val(tDisChgTxt);
    }

    //เเก้ไขจำนวน และ ราคา (เช็คก่อน)
    function JSxEditQtyAndPrice(){
        $('.xCNPdtEditInLine').click(function(){
            $(this).focus().select();
        });

        $('.xCNPdtEditInLine').off().on('change keyup',function(e){
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $('.xWPdtItemList'+nSeq).attr('data-qty');
                var cPrice  = $('.xWPdtItemList'+nSeq).attr('data-setprice');

                // ตรวจสอบลดรายการ
                var tDisChgDTTmp = $('#xWDisChgDTTmp'+nSeq).text().replace(/,/g, '');
                if(tDisChgDTTmp == ''){
                    JSxGetDisChgList(nSeq,0);
                    $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                }else{
                    // มีลด/ชาร์จ
                    $('#odvIVModalConfirmDeleteDTDis').modal({
                        backdrop    : 'static',
                        show        : true
                    });

                    //กดยืนยันที่จะเปลี่ยน
                    $('#odvIVModalConfirmDeleteDTDis #obtIVConfirmDeleteDTDis').off('click');
                    $('#odvIVModalConfirmDeleteDTDis #obtIVConfirmDeleteDTDis').on('click',function(){
                        $('#odvIVModalConfirmDeleteDTDis').modal('hide');
                        JSxGetDisChgList(nSeq,1);
                        $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                    });

                    //กดยกเลิกที่จะไม่เปลี่ยน
                    $('#odvIVModalConfirmDeleteDTDis #obtIVCancelDeleteDTDis').off('click');
                    $('#odvIVModalConfirmDeleteDTDis #obtIVCancelDeleteDTDis').on('click',function(){
                        $('#odvIVModalConfirmDeleteDTDis').modal('hide');
                        e.preventDefault();
                        nQty    = nQty.replace(/,/g, '');
                        cPrice  = cPrice.replace(/,/g, '');
                        $('#ohdQty'+nSeq).val(parseFloat(nQty).toFixed(2));
                        $('#ohdPrice'+nSeq).val(parseFloat(cPrice).toFixed(2));
                    });
                }
            }
        });
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq,pnStaDelDis){
        // ptStaDelDis = 1 ลบ DTDis
        // ptStaDelDis = 0 ไม่ลบ DTDis

        var tChgDT      = $('#xWDisChgDTTmp'+pnSeq).text().replace(/,/g, '');
        var cPrice      = $('#ohdPrice'+pnSeq).val();
        var nQty        = $('#ohdQty'+pnSeq).val();
        var tName        = $('#ohdPdtName'+pnSeq).val();
        var cResult     = parseFloat(cPrice * nQty);

        // Fixed ราคาต่อหน่วย 2 ตำแหน่ง
        $('#ohdPrice'+pnSeq).val(parseFloat(cPrice).toFixed(2));

        // Update Value
        $('#ospGrandTotal'+pnSeq).text(numberWithCommas(parseFloat(cResult).toFixed(2)));
        $('.xWPdtItemList'+pnSeq).attr('data-qty',nQty);
        $('.xWPdtItemList'+pnSeq).attr('data-setprice',parseFloat(cPrice).toFixed(2));
        $('.xWPdtItemList'+pnSeq).attr('data-net',parseFloat(cResult).toFixed(2));

        if(pnStaDelDis == 1){
            $('#xWDisChgDTTmp'+pnSeq).text('');
        }

        // ถ้าไม่มีลดท้ายบิล ให้ปรับ NetAfHD
        if($('#olbIVDisChgHD').text() == ''){
            $('#ospnetAfterHD'+pnSeq).text(parseFloat(cResult).toFixed(2));
            $('.xWPdtItemList'+pnSeq).attr('data-netafhd',parseFloat(cResult).toFixed(2));
        }

        JSxRendercalculate();

        var tIVDocNo        = $("#oetIVDocNo").val();
        var tIVBchCode      = $("#ohdIVBchCode").val();
        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docInvoiceEditPdtIntoDTDocTemp",
                data    : {
                    'tIVBchCode'        : tIVBchCode,
                    'tIVDocNo'          : tIVDocNo,
                    'nIVSeqNo'          : pnSeq,
                    'nQty'              : nQty,
                    'FTXtdPdtName'      : tName,
                    'cPrice'            : cPrice,
                    'cNet'              : cResult,
                    'nStaDelDis'        : pnStaDelDis
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){ },
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }

    //Hi-light คอลัมส์สุดท้าย
    function JSxAddScollBarInTablePdt(){
        $('#otbIVDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbIVDocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbIVDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');

        }

        if(rowCount >= 7){
            // $('.xCNTablescroll').css('height','450px');
            $('.xWShowInLine' + rowCount).focus();
            $('html, body').animate({
                scrollTop: ($("#oetIVInsertBarcode").offset().top)-80
            }, 0);
        }
    }

    //ลบคอลัมน์ใน Temp
    function JSnRemoveDTRow(ele) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tVal    = $(ele).parent().parent().parent().attr("data-pdtcode");
            var tSeqno  = $(ele).parent().parent().parent().attr("data-seqno");
                          $(ele).parent().parent().parent().remove();
            JSxIVRemoveDTTemp(tSeqno, tVal, ele);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล [รายการเดียว]
    function JSxIVRemoveDTTemp(pnSeqNo,ptPDTCode,elem){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var ptXthDocNo  = $("#oetIVDocNo").val();

            $.ajax({
                type    : "POST",
                url     : "docInvoiceRemovePdtInDTTmp",
                data    : {
                    ptXthDocNo  : ptXthDocNo,
                    pnSeqNo     : pnSeqNo,
                    ptPDTCode   : ptPDTCode
                },
                cache   : false,
                timeout : 0,
                success: function (oResult) {
                    var aResult = $.parseJSON(oResult);
                    if(aResult['rtCode'] == '1'){
                        $(elem).fadeOut();

                        //คำนวณเงินใหม่อีกครั้ง
                        JSxRendercalculate();

                        //ถ้าลบจนหมดเเล้วให้โชว์ว่าไม่พบข้อมูล
                        var tCheckIteminTable = $('#otbIVDocPdtAdvTableList tbody tr').length;
                        if(tCheckIteminTable == 0){
                            $('#otbIVDocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                        }

                    }else{
                        alert(aResult['rtDesc']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ทำส่วนลดรายการ
    function JCNvIVCallModalDisChagDT(poEl){
        var nStaSession = 1;
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo          = $(poEl).parents('.xWPdtItem').data('docno');
            var tPdtCode        = $(poEl).parents('.xWPdtItem').data('pdtcode');
            var tPdtName        = $(poEl).parents('.xWPdtItem').data('pdtname');
            var tPunCode        = $(poEl).parents('.xWPdtItem').data('puncode');
            var tNet            = $(poEl).parents('.xWPdtItem').data('netafhd');
            var tSetPrice       = $(poEl).parents('.xWPdtItem').attr('data-setprice');
            var tQty            = $(poEl).parents('.xWPdtItem').attr('data-qty');
            var tStaDis         = $(poEl).parents('.xWPdtItem').data('stadis');
            var tSeqNo          = $(poEl).parents('.xWPdtItem').data('seqno');
            var bHaveDisChgDT   = $(poEl).parents('.xWIVDisChgDTForm').find('label.xWDisChgDTTmp').text() == ''? false : true;
            window.DisChgDataRowDT  = {
                tDocNo          : tDocNo,
                tPdtCode        : tPdtCode,
                tPdtName        : tPdtName,
                tPunCode        : tPunCode,
                tNet            : tNet,
                tSetPrice       : tSetPrice,
                tQty            : tQty,
                tStadis         : tStaDis,
                tSeqNo          : tSeqNo,
                bHaveDisChgDT   : bHaveDisChgDT
            };
            var oIVDisChgParams = {
                DisChgType: 'disChgDT'
            };
            JSxIVOpenDisChgPanel(oIVDisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //ทำส่วนลดท้ายบิล
    function JCNvIVMngDocDisChagHD(event){
        var oIVDisChgParams = {
            DisChgType: 'disChgHD'
        };
        JSxIVOpenDisChgPanel(oIVDisChgParams);
    }

    //ลบคอลัมน์ในฐานข้อมูล ฐานข้อมูล [หลายรายการ]
    function JSnIVRemovePdtDTTempMultiple(){
        JCNxOpenLoading();
        var tDocNo          = $("#oetIVDocNo").val();
        var tBchCode        = $('#ohdIVBchCode').val();
        var aDataSeqNo      = JSoIVRemoveCommaData($('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVSeqNoDelete').val());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvIVModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvIVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVDocNoDelete').val('');
        $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVSeqNoDelete').val('');
        $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVPdtCodeDelete').val('');

        setTimeout(function(){
            $('.modal-backdrop').remove();
            JCNxLayoutControll();
        }, 500);

        JSxRendercalculate();

        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docInvoiceRemovePdtMultiDTTmp",
            data    : {
                'tBchCode'          : tBchCode,
                'tDocNo'            : tDocNo,
                'tSeqCode'          : aDataSeqNo
            },
            cache: false,
            timeout: 0,
            success: function (oResult) {
                var aResult = $.parseJSON(oResult);
                if(aResult['nStaEvent'] == '1'){

                    //เช็คทั้งหมดปลดล็อค
                    $('#ocmCENCheckDeleteAll').attr("checked",false);

                    //คำนวณเงินใหม่อีกครั้ง
                    JSxRendercalculate();

                    //ถ้าลบจนหมดเเล้วให้โชว์ว่าไม่พบข้อมูล
                    var tCheckIteminTable = $('#otbIVDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable == 0){
                        $('#otbIVDocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                    }

                    //ลบค่าใน local
                    localStorage.removeItem('IV_LocalItemDataDelDtTemp');

                    //บล็อกปุ่มลบทั้งหมด
                    $('#oliIVBtnDeleteMulti').addClass('disabled');
                }else{
                    alert(aResult['tStaMessg']);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }

    //ลบคอลัมน์ในฐานข้อมูล ลบคอม่า [หลายรายการ]
    function JSoIVRemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    function FSxIVSelectMulDel(ptElm){
        let tDocNo    = $('#oetIVDocNo').val();
        let tSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("IV_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("IV_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tDocNo,
                'tSeqNo'    : tSeqNo,
                'tPdtCode'  : tPdtCode,
                'tBarCode'  : tBarCode,
            });
            localStorage.setItem("IV_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxIVTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStIVFindObjectByKey(aArrayConvert[0],'tSeqNo',tSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tDocNo,
                    'tSeqNo'    : tSeqNo,
                    'tPdtCode'  : tPdtCode,
                    'tBarCode'  : tBarCode,
                });
                localStorage.setItem("IV_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxIVTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("IV_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("IV_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxIVTextInModalDelPdtDtTemp();
            }
        }
        JSxIVShowButtonDelMutiDtTemp();
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    function JSxIVTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("IV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tIVTextDocNo   = "";
            var tIVTextSeqNo   = "";
            var tIVTextPdtCode = "";
            var tIVTextPunCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tIVTextDocNo    += aValue.tDocNo;
                tIVTextDocNo    += " , ";

                tIVTextSeqNo    += aValue.tSeqNo;
                tIVTextSeqNo    += " , ";

                tIVTextPdtCode  += aValue.tPdtCode;
                tIVTextPdtCode  += " , ";

                tIVTextPunCode  += aValue.tPunCode;
                tIVTextPunCode  += " , ";
            });
            $('#odvIVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVDocNoDelete').val(tIVTextDocNo);
            $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVSeqNoDelete').val(tIVTextSeqNo);
            $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVPdtCodeDelete').val(tIVTextPdtCode);
            $('#odvIVModalDelPdtInDTTempMultiple #ohdConfirmIVPunCodeDelete').val(tIVTextPunCode);
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล เปิดปุ่มลบทั้งหมด [หลายรายการ]
    function JSxIVShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("IV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#oliIVBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#oliIVBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#oliIVBtnDeleteMulti").addClass("disabled");
            }
        }
    }

</script>
