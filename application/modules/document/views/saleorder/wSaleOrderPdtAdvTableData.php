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

    .xCNBTNPrimeryDisChgPlus{
        border-radius   : 50%;
        float           : left;
        width           : 20px;
        height          : 20px;
        line-height     : 20px;
        background-color: #1eb32a;
        text-align      : center;
        margin-top      : 6px;
        font-size       : 22px;
        color           : #ffffff;
        cursor          : pointer;
        -webkit-border-radius   : 50%;
        -moz-border-radius      : 50%;
        -ms-border-radius       : 50%;
        -o-border-radius        : 50%;
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tSOPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tSOPunCode;?>">
        <table id="otbSODocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <?php if($tSOStaApv  == '1' || $tSOStaDoc == '3'){}else{ ?>
                        <th class="text-center">
                            <label class="fancy-checkbox">
                                <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxSOSelectAll(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </th>
                    <?php } ?>
                    <th class="xCNTextBold"><?php echo language('document/saleorder/saleorder','tSOTBNo')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_unit')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_qty')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_price')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_discount')?></th>
                    <th class="xCNTextBold"><?=language('document/saleorder/saleorder','tSOTable_grand')?></th>
                    <th class="xCNPIBeHideMQSS"><?php echo language('document/saleorder/saleorder', 'tSOTBDelete');?></th>
                </tr>
            </thead>
            <tbody id="odvTBodySOPdtAdvTableList">
            <?php 
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
                        // print_r($aDataTableVal);
            ?>
                    <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>" 
                        data-alwvat="<?=$aDataTableVal['FTXtdVatType'];?>" 
                        data-vat="<?=$aDataTableVal['FCXtdVatRate']?>" 
                        data-key="<?=$nKey?>" 
                        data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>" 
                        data-seqno="<?=$nKey?>" 
                        data-setprice="<?=number_format($aDataTableVal['FCXtdSetPrice'],2);?>" 
                        data-qty="<?=$aDataTableVal['FCXtdQty'];?>" 
                        data-netafhd="<?=number_format($aDataTableVal['FCXtdNetAfHD'],2);?>" 
                        data-net="<?=number_format($aDataTableVal['FCXtdNet'],2);?>" 
                        data-stadis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" 
                        style="background-color: rgb(255, 255, 255);" 
                    >
                        <td class="xCNPIBeHideMQSS text-center">
                            <label class="fancy-checkbox">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxSOSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <td><?=$nKey?></td>
                        <td><?=$aDataTableVal['FTPdtCode'];?></td>
                        <td><?=$aDataTableVal['FTXtdPdtName'];?></td>
                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>
                        <td class="otdQty">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty form-control xCNControllForm xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(',','',number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <td class="otdPrice">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNPrice form-control xCNControllForm xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> " id="ohdPrice<?=$nKey?>" name="ohdPrice<?=$nKey?>" maxlength="10" data-alwdis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" data-seq="<?=$nKey?>" value="<?=str_replace(',','',number_format($aDataTableVal['FCXtdSetPrice'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <td>
                        <?php 
                            if($aDataTableVal['FTXtdStaAlwDis'] == 1): 
                        ?>
                            <div>
                                <button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOCallModalDisChagDT(this)" type="button" style="background-color: #1866ae !important;">+</button>
                                <label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp<?=$nKey?>"><?=$aDataTableVal['FTXtdDisChgTxt'];?></label>
                            </div>
                        <?php 
                            else: 
                                echo "?????????????????????????????????????????????????????????"; 
                            endif;
                        ?>
                        </td>
                        <td class="text-right">
                            <span id="ospGrandTotal<?=$nKey?>"><?=number_format($aDataTableVal['FCXtdNet'],2);?></span>
                            <span id="ospnetAfterHD<?=$nKey?>" style="display: none;"><?=number_format($aDataTableVal['FCXtdNetAfHD'],2);?></span>
                        </td>
                        <td nowrap="" class="text-center xCNPIBeHideMQSS">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnSODelPdtInDTTempSingle(this)">
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
    <div id="odvSOModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tSOMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/saleorder/saleorder','tSOMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtSOConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtSOCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', '??????????????????');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ================================================================================================================================= -->



<!--?????????????????????????????????????????????????????????-->
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

<!--??????????????????????????????????????????????????????-->
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

<!--??????????????????????????????????????????-->
<div id="odvModalDiscount" class="modal fade" tabindex="-1" role="dialog" style="max-width: 1500px; margin: 1.75rem auto; width: 85%;">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content">
                <!--?????????????????????-->
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block">??????????????????/??????????????? ?????????????????????</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!--??????????????????????????????-->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="btn-group pull-right" style="margin-bottom: 20px; width: 300px;">
                                <button 
                                    type="button" 
                                    id="obtAddDisChg" 
                                    class="btn xCNBTNPrimery pull-right" 
                                    onclick="JCNvAddDisChgRow()" 
                                    style="width: 100%;"><?=language('document/purchaseinvoice/purchaseinvoice','tPIMDAddEditDisChg') ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive" style="min-height: 300px; max-height: 300px; overflow-y: scroll;">
                                <table id="otbDisChgDataDocHDList" class="table">
                                    <thead>
                                        <tr class="xCNCenter">
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIsequence')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIBeforereducing')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIValuereducingcharging')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIAfterReducing')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIType')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPIDiscountcharge')?></th>
                                            <th class="xCNTextBold"><?=language('document/purchaseinvoice/purchaseinvoice','tPITBDelete')?></th>
                                        </tr>    
                                    </thead>
                                    <tbody>
                                        <tr class="otrDisChgHDNotFound"><td class="text-center xCNTextDetail2" colspan='100%'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--????????????????????????????????????????????????????????????-->
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main','tCancel');?></button>
                    <button onclick="JSxDisChgSave()" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main','tCMNOK');?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  include("script/jSaleOrderPdtAdvTableData.php");?>

<script>  
    $( document ).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();    
        if($('#ohdSOStaApv').val()==1){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $('.xCNIconTable').hide();
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtSOBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
        }

    });


    // Next Func ????????? Browse PDT Center
    function FSvSONextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvSOAddPdtIntoDocDTTemp(aNewPackData);         // Append HMTL
            FSvSOAddBarcodeIntoDocDTTemp(aNewPackData);     // Insert Database
        }
    }

    // Append PDT
    function FSvSOAddPdtIntoDocDTTemp(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        var tCheckIteminTableClass = $('#otbSODocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        // var tCheckIteminTable = $('#otbSODocPdtAdvTableList tbody tr').length;
        if(tCheckIteminTableClass==true){
            $('#otbSODocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbSODocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        // var nKey    = parseInt($('#otbSODocPdtAdvTableList tbody tr').length) + parseInt(1);
        
        for(var i=0; i<nLen; i++){

            var oData           = aPackData[i];
            var oResult         = oData.packData;

            oResult.NetAfHD     = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.Net == '' || oResult.Net === undefined ? oResult.PriceRet : oResult.Net);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);

            var tBarCode        = oResult.Barcode;          //????????????????????????
            var tProductCode    = oResult.PDTCode;          //??????????????????????????????
            var tProductName    = oResult.PDTName;          //??????????????????????????????
            var tUnitName       = oResult.PUNName;          //?????????????????????????????????????????????
            var nPrice          = (parseFloat(oResult.PriceRet.replace(/,/g, ''))).toFixed(2);            //????????????
            // var nGrandTotal     = (nPrice * 1).toFixed(2);  //?????????????????????
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //??????????????????????????????????????????
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //????????????????????????????????????????????????
            var nVat            = (parseFloat(oResult.nVat)).toFixed(2);              //????????????
            var nQty            = parseInt(oResult.Qty);             //???????????????
            var nNetAfHD        = (parseFloat(oResult.NetAfHD.replace(/,/g, ''))).toFixed(2);
            var cNet            = (parseFloat(oResult.Net.replace(/,/g, ''))).toFixed(2);
            var tDisChgTxt      = oResult.tDisChgTxt;

            var tDuplicate = $('#otbSODocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();
            if(tDuplicate == true && InfoOthReAddPdt==1){
                //???????????????????????????????????? ?????????????????? Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);
                $('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);

                var nGrandOld   = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').val();
                var nGrand      = parseInt(nNewValue) * parseFloat(nGrandOld);
                var nSeqOld     = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').attr('data-seq');
                $('#ospGrandTotal'+nSeqOld).text(numberWithCommas(nGrand.toFixed(2)));
            }else{
                //????????????????????????????????????????????? ????????????????????????????????????????????????
                if(nAlwDiscount == 1){ //???????????????????????????
                    var oAlwDis = '<div>';
                        oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOCallModalDisChagDT(this)" type="button" style="background-color: #1866ae !important;">+</button>'; //JCNvDisChgCallModalDT(this)
                        oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp'+nKey+'">'+tDisChgTxt+'</label>';
                        oAlwDis += '</div>';
                }else{
                    var oAlwDis = '?????????????????????????????????????????????????????????';
                }

                //????????????
                var oPrice = '<div class="xWEditInLine'+nKey+'">';
                    oPrice += '<input ';
                    oPrice += 'type="text" ';
                    oPrice += 'class="xCNPrice form-control xCNControllForm xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' "';
                    oPrice += 'id="ohdPrice'+nKey+'" ';
                    oPrice += 'name="ohdPrice'+nKey+'" '; 
                    oPrice += 'maxlength="10" '; 
                    oPrice += 'data-alwdis='+nAlwDiscount+' ';
                    oPrice += 'data-seq='+nKey+' ';
                    oPrice += 'value="'+nPrice+'"';
                    oPrice += 'autocomplete="off" >';
                    oPrice += '</div>';  

                //???????????????
                var oQty = '<div class="xWEditInLine'+nKey+'">';
                    oQty += '<input ';
                    oQty += 'type="text" ';
                    oQty += 'class="xCNQty form-control xCNControllForm xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' xWShowInLine'+nKey+' "';
                    oQty += 'id="ohdQty'+nKey+'" ';
                    oQty += 'name="ohdQty'+nKey+'" '; 
                    oQty += 'data-seq='+nKey+' ';
                    oQty += 'maxlength="10" '; 
                    oQty += 'value="'+nQty+'"';
                    oQty += 'autocomplete="off" >';
                    oQty += '</div>';  

                tHTML += '<tr class="otr'+tProductCode+''+tBarCode+' xWPdtItem xWPdtItemList'+nKey+'"';
                tHTML += '  data-alwvat="'+nAlwVat+'"';
                tHTML += '  data-vat="'+nVat+'"';
                tHTML += '  data-key="'+nKey+'"';
                tHTML += '  data-pdtcode="'+tProductCode+'"';
                // tHTML += '  data-puncode="'+tProductCode+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-setprice="'+nPrice+'"';
                tHTML += '  data-qty="'+nQty+'"';
                tHTML += '  data-netafhd="'+nNetAfHD+'"';
                tHTML += '  data-net="'+cNet+'"';
                tHTML += '  data-stadis="'+nAlwDiscount+'"';

                tHTML += '>';
                tHTML += '<td class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="fancy-checkbox">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxSOSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td>'+nKey+'</td>';
                tHTML += '<td>'+tProductCode+'</td>';
                tHTML += '<td>'+tProductName+'</td>';
                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="otdQty">'+oQty+'</td>';
                tHTML += '<td class="otdPrice">'+oPrice+'</td>';
                tHTML += '<td>'+oAlwDis+'</td>';
                tHTML += '<td class="text-right"><span id="ospGrandTotal'+nKey+'">'+cNet+'</span>';
                tHTML += '    <span id="ospnetAfterHD'+nKey+'" style="display: none;">'+nNetAfHD+'</span>';
                tHTML += '</td>';    
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnSODelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //??????????????????????????????
        $('#otbSODocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
    }

function FSxSOSelectMulDel(ptElm){
    // $('#otbSODocPdtAdvTableList #odvTBodySOPdtAdvTableList .ocbListItem').click(function(){
        let tSODocNo    = $('#oetSODocNo').val();
        let tSOSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tSOPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tSOBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        // let tSOPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("SO_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("SO_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tSODocNo,
                'tSeqNo'    : tSOSeqNo,
                'tPdtCode'  : tSOPdtCode,
                'tBarCode'  : tSOBarCode,
                // 'tPunCode'  : tSOPunCode,
            });
            localStorage.setItem("SO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxSOTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStSOFindObjectByKey(aArrayConvert[0],'tSeqNo',tSOSeqNo);
            if(aReturnRepeat == 'None' ){
                //??????????????????????????????????????????
                oDataObj.push({
                    'tDocNo'    : tSODocNo,
                    'tSeqNo'    : tSOSeqNo,
                    'tPdtCode'  : tSOPdtCode,
                    'tBarCode'  : tSOBarCode,
                    // 'tPunCode'  : tSOPunCode,
                });
                localStorage.setItem("SO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxSOTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("SO_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tSOSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("SO_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxSOTextInModalDelPdtDtTemp();
            }
        }
        JSxSOShowButtonDelMutiDtTemp();
    // });
}

function JSxAddScollBarInTablePdt(){
    $('#otbSODocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
    var rowCount = $('#otbSODocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbSODocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');
       
        }

    if(rowCount >= 7){
        $('.xCNTablescroll').css('height','450px');
        $('.xWShowInLine' + rowCount).focus();

        $('html, body').animate({
             scrollTop: ($("#oetSOInsertBarcode").offset().top)-80
         }, 0);
    }



        
        if($('#oetSOFrmCstCode').val() != ''){
            $('#oetSOInsertBarcode').focus();
        }

    }
    //????????????????????????????????????????????????????????????????????? DT
    function JSxRendercalculate(){

        var nTotal                  = 0;
        var nTotal_alwDiscount      = 0;

        $(".xCNPrice").each(function(e) {
            var nSeq   = $(this).attr('data-seq');
            var nValue = $('#ospGrandTotal'+nSeq).text();
            var nValue = nValue.replace(/,/g, '');

            nTotal = parseFloat(nTotal) + parseFloat(nValue);
         
            if($(this).attr('data-alwdis') == 1){
                nTotal_alwDiscount = parseFloat(nTotal_alwDiscount) + parseFloat(nValue);
            };
        });
   
        //????????????????????????????????????
        $('#olbSumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(2)));

        //???????????????????????????????????? ????????????????????????????????????
        $('#olbSumFCXtdNetAlwDis').val(nTotal_alwDiscount);

        //???????????????????????????????????????
        var tChgHD          = $('#olbDisChgHD').text();
        var tChgHDNoComma   = $('#ohdSODisChgHD').val();
        var tChgType        = $('#ohdSODisChgType').val();
        var nNewDiscount    = 0;
        if(tChgHD != '' && tChgHD != null){ //?????????????????????????????????????????????
            var aChgHD      = tChgHD.split(",");
            var aChgHDType      = tChgType.split(",");
            var aChgHDNoComma       = tChgHDNoComma.split(",");
            var nNetAlwDis  = $('#olbSumFCXtdNetAlwDis').val();
            // console.log(aChgHDType);
            // console.log(tChgHD);

        
            for(var i=0; i<aChgHDNoComma.length; i++){
                if(aChgHDNoComma[i] != '' && aChgHDNoComma[i] != null){

                    // if(aChgHDNoComma[i].search("%") == -1){ 
                    if(aChgHDType[i] == '1' || aChgHDType[i] == '3'){ 
                        //?????????????????? = ?????????????????????????????????????????????
                        var nVal        = aChgHDNoComma[i];
                        var nabsnum = Math.abs(parseFloat(nVal));
                        if(aChgHDType[i] == '1'){
                            var nCal        = (parseFloat(nNetAlwDis) - parseFloat(nabsnum));

                        }else if(aChgHDType[i] == '3'){
                            var nCal        = (parseFloat(nNetAlwDis) + parseFloat(nabsnum));
                        }
                        // console.log(nCal);
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }else{ 
                        //????????? = ???????????????????????????????????? %
                        var nPercent    = aChgHDNoComma[i];
                        var nPercent    = nPercent.replace("%", "");
                        var tCondition  = nPercent.substr(0, 1);
                        var nValPercent = Math.abs(nPercent);
                        console.log(nPercent);
                        if(aChgHDType[i] == '2'){
                            var nCal        = parseFloat(nNetAlwDis) - ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                            if(nCal == 0){
                                var nCal        = -nNetAlwDis;
                            }else{
                                var nCal        = nCal;
                            }
                        }else if(aChgHDType[i] == '4'){
                            var nCal        = parseFloat(nNetAlwDis) + ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                        }

                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }
                }
            }
            var nDiscount = (nNetAlwDis - parseFloat($('#olbSumFCXtdNetAlwDis').val()));
                        
            $('#olbSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(2)));

            //Prorate
            JSxProrate();
        }

        //????????????????????????????????????/???????????????
        var nTotalFisrt = $('#olbSumFCXtdNet').text().replace(/,/g, '');
        var nDiscount   = $('#olbSumFCXtdAmt').text().replace(/,/g, '');
        var nResult     = parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount);
        $('#olbSumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(2)));

        //???????????????????????????
        JSxCalculateVat();
    }

 //Prorate ?????????????????????????????????????????????????????????
 function JSxProrate(){
        //pnSumDiscount         : ????????????????????????????????????????????????????????????
        //pnSum                 : ?????????????????????????????????????????????????????????????????????????????????????????????
        var pnSumDiscount       = $('#olbSumFCXtdAmt').text().replace(/,/g, '');
        var pnSum               = $('#olbSumFCXtdNetAlwDis').val().replace(/,/g, '');
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
           //??????????????? prorate ??????????????????????????????????????????????????? + ??????????????????????????????
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


    //????????????????????????????????? ????????? ????????????
    function JSxEditQtyAndPrice(){

        $('.xCNPdtEditInLine').click(function(){
            $(this).focus().select();
        });

        // $('.xCNQty').change(function(e){
        $('.xCNPdtEditInLine').off().on('change keyup',function(e){
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $('.xWPdtItemList'+nSeq).attr('data-qty');
                var cPrice  = $('.xWPdtItemList'+nSeq).attr('data-setprice');

                // ?????????????????????????????????????????????
                var tDisChgDTTmp = $('#xWDisChgDTTmp'+nSeq).text();
                if(tDisChgDTTmp == ''){
                    JSxGetDisChgList(nSeq,0);
                    $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                }else{
                    // ????????????/???????????????
                    $('#odvSOModalConfirmDeleteDTDis').modal({
                        backdrop: 'static',
                        show: true
                    });
                    
                    // $('#odvSOModalConfirmDeleteDTDis #obtSOConfirmDeleteDTDis').unbind();
                    $('#odvSOModalConfirmDeleteDTDis #obtSOConfirmDeleteDTDis').off('click');
                    $('#odvSOModalConfirmDeleteDTDis #obtSOConfirmDeleteDTDis').on('click',function(){
                        // $('#odvSOModalConfirmDeleteDTDis').modal('hide');
                        // $('.modal-backdrop').remove();
                        JSxGetDisChgList(nSeq,1);
                        $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                    });

                    // $('#odvSOModalConfirmDeleteDTDis #obtSOCancelDeleteDTDis').unbind();
                    $('#odvSOModalConfirmDeleteDTDis #obtSOCancelDeleteDTDis').off('click');
                    $('#odvSOModalConfirmDeleteDTDis #obtSOCancelDeleteDTDis').on('click',function(){
                        // $('.modal-backdrop').remove();
                        e.preventDefault();
                        $('#ohdQty'+nQty).val(nQty);
                        $('#ohdPrice'+nQty).val(cPrice);
                    });

                    // $('#odvSOModalConfirmDeleteDTDis').modal('show');
                }
            }
        });

    }

    // ptStaDelDis = 1 ?????? DTDis
    // ptStaDelDis = 0 ??????????????? DTDis
    function JSxGetDisChgList(pnSeq,pnStaDelDis){
        var tChgDT      = $('#xWDisChgDTTmp'+pnSeq).text();
        var cPrice      = $('#ohdPrice'+pnSeq).val();
        var nQty        = $('#ohdQty'+pnSeq).val();
        var cResult     = parseFloat(cPrice * nQty);

        // Fixed ???????????????????????????????????? 2 ?????????????????????
        $('#ohdPrice'+pnSeq).val(parseFloat(cPrice).toFixed(2));

        // Update Value
        $('#ospGrandTotal'+pnSeq).text(numberWithCommas(parseFloat(cResult).toFixed(2)));
        $('.xWPdtItemList'+pnSeq).attr('data-qty',nQty);
        $('.xWPdtItemList'+pnSeq).attr('data-setprice',parseFloat(cPrice).toFixed(2));
        $('.xWPdtItemList'+pnSeq).attr('data-net',parseFloat(cResult).toFixed(2));
        if(pnStaDelDis == 1){
            $('#xWDisChgDTTmp'+pnSeq).text('');
        }

        // ??????????????????????????????????????????????????? ????????????????????? NetAfHD
        if($('#olbDisChgHD').text() == ''){
            $('#ospnetAfterHD'+pnSeq).text(parseFloat(cResult).toFixed(2));
            $('.xWPdtItemList'+pnSeq).attr('data-netafhd',parseFloat(cResult).toFixed(2));
        }

        JSxRendercalculate();

        var tSODocNo        = $("#oetSODocNo").val();
        var tSOBchCode      = $("#oetSOFrmBchCode").val();
        $.ajax({
            type: "POST",
            url: "dcmSOEditPdtIntoDTDocTemp",
            data: {
                'tSOBchCode'    : tSOBchCode,
                'tSODocNo'      : tSODocNo,
                'nSOSeqNo'      : pnSeq,
                'nQty'          : nQty,
                'cPrice'        : cPrice,
                'cNet'          : cResult,
                'nStaDelDis'    : pnStaDelDis,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdSOUsrCode'        : $('#ohdSOUsrCode').val(),
                'ohdSOLangEdit'       : $('#ohdSOLangEdit').val(),
                'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                'ohdSOSesUsrBchCode'  : $('#ohdSOSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JSxGetDisChgList(pnSeq,pnStaDelDis);
            }
        });
       

    }



    //???????????????????????????
    $('#ocmVatInOrEx').change(function (){ JSxCalculateVat(); });  
    function JSxCalculateVat(){
        var tVatList  = '';
        var aVat      = [];
        $('#otbSODocPdtAdvTableList tbody tr').each(function(){
            var nAlwVat  = $(this).attr('data-alwvat');
            var nVat     = parseFloat($(this).attr('data-vat'));
            var nKey     = $(this).attr('data-key');
            var tTypeVat = $('#ohdSOCmpRetInOrEx').val();;

            if(nAlwVat == 1){ 
                //?????????????????????????????? VAT
                if(tTypeVat == 1){ 
                    // ??????????????????????????? tSoot = net - ((net * 100) / (100 + rate));
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult   = parseFloat(nTotalVat).toFixed(2);
                }else if(tTypeVat == 2){
                    // ?????????????????????????????? tSoot = net - (net * (100 + 7) / 100) - net;
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult   = parseFloat(nTotalVat).toFixed(2);
                }

                var oVat = { VAT: nVat , VALUE: nResult };
                aVat.push(oVat);
            }
        });


        //?????????????????????????????? array ????????????
        aVat.sort(function (a, b) {
            return a.VAT - b.VAT;
        });

        //???????????????????????? array ???????????? vat ?????????
        var nVATStart       = 0;
        var nSumValueVat    = 0;
        var nSumVat = 0;
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
            nSumVat += parseFloat(aVat[i].VALUE);
            aSumVat.push(oSum);
        }

        var SOVatRate =  parseFloat($('#ohdSOVatRate').val());
        var SOCmpRetInOrEx = $('#ohdSOCmpRetInOrEx').val();
        // ??????????????????????????? tSoot = net - ((net * 100) / (100 + rate));
        var cSumFCXtdNetAfHD  = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
        var nSumVatHD = cSumFCXtdNetAfHD - ((cSumFCXtdNetAfHD * 100)/(100 + SOVatRate));
        
        //???????????????????????????????????????????????????????????????
        $('#olbVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(2)));

                //????????? VAT ?????????????????????????????????
        var nSumVat = 0;
        var nCount = 1;
        for(var j=0; j<aSumVat.length; j++){
            var tVatRate    = aSumVat[j].VAT;
                  if(nCount!=aSumVat.length){
                    var tSumVat     = parseFloat(aSumVat[j].VALUE).toFixed(2) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE).toFixed(2);
                    }else{
                    var tSumVat     = (aSumVat[j].VALUE - nSumVat).toFixed(2);
                    }
            tVatList    += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + tSumVat + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }
        
        $('#oulDataListVat').html(tVatList);

        //???????????????????????????????????????????????????????????????
        $('#olbSumFCXtdVat').text(numberWithCommas(parseFloat(nSumVat).toFixed(2)));
        $('#ohdSumFCXtdVat').val(nSumVat.toFixed(2));
        //?????????????????????????????????
        var tTypeVat = $('#ohdSOCmpRetInOrEx').val();;
        if(tTypeVat == 1){ //?????????????????????????????????
            var nTotal          = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nResultTotal    = nTotal;
        }else if(tTypeVat == 2){ //????????????????????????????????????
            var nTotal          = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#olbSumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal) + parseFloat(nVat);
        }

        //????????????????????????????????????????????????????????????
        $('#olbCalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));

        //?????????????????????????????????????????? ???????????????????????????
        var tTextTotal  = $('#olbCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath 	= ArabicNumberToText(tTextTotal);
        $('#odvDataTextBath').text(tThaibath);
    }


    //???????????????????????????????????? comma ??????????????????
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }
    



    //Modal ????????????????????????????????? HD
    function JCNLoadPanelDisChagHD(){
        $('#odvModalDiscount').modal({backdrop: 'static', keyboard: false})  
        $('#odvModalDiscount').modal('show');
    }

    //?????????????????????????????????
    function JCNvAddDisChgRow(){
        var tDuplicate = $('#otrSODisChgHDNotFound tbody tr').hasClass('otrSODisChgHDNotFound');
        if(tDuplicate == true){
            //?????????????????????
            $('#otrSODisChgHDNotFound tbody').html('');
        }

        //????????????????????????
        var nKey = parseInt($('#otrSODisChgHDNotFound tbody tr').length) + parseInt(1);

        //???????????????????????????????????? ????????????????????????????????????
        var nRowCount   = $('.xWDiscountChgTrTag').length;
        if(nRowCount > 0){
            var oLastRow   = $('.xWDiscountChgTrTag').last();
            var nNetAlwDis = oLastRow.find('td label.xCNDisChgAfterDisChg').text();
        }else{
            var nNetAlwDis = ($('#olbSumFCXtdNetAlwDis').val() == 0) ? '0.00' : $('#olbSumFCXtdNetAlwDis').val();
        }

        var     tSelectTypeDiscount =  '<td nowrap style="padding-left: 5px !important;">';
                tSelectTypeDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
                tSelectTypeDiscount += '<select class="dischgselectpicker form-control xCNControllForm xCNDisChgType" onchange="JSxCalculateDiscountChg(this);">';
                tSelectTypeDiscount += '<option value="1"><?=language('common/main/main', '???????????????'); ?></option>';
                tSelectTypeDiscount += '<option value="2"><?=language('common/main/main', '?????? %'); ?></option>';
                tSelectTypeDiscount += '<option value="3"><?=language('common/main/main', '????????????????????????'); ?></option>';
                tSelectTypeDiscount += '<option value="4"><?=language('common/main/main', '??????????????? %'); ?></option>';
                tSelectTypeDiscount += '</select>';
                tSelectTypeDiscount += '</div>';
                tSelectTypeDiscount += '</td>';

        var     tDiscount =  '<td nowrap style="padding-left: 5px !important;">';
                tDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
                tDiscount += '<input class="form-control xCNControllForm xCNInputNumericWithDecimal xCNDisChgNum" onchange="JSxCalculateDiscountChg(this);" onkeyup="javascript:if(event.keyCode==13) JSxCalculateDiscountChg(this);" type="text">';
                tDiscount += '</div>';
                tDiscount += '</td>';

        var     tHTML = '';
                tHTML += '<tr class="xWDiscountChgTrTag" >';
                tHTML += '<td>'+nKey+'</td>';
                tHTML += '<td class="text-right"><label class="xCNBeforeDisChg">'+numberWithCommas(parseFloat(nNetAlwDis).toFixed(2))+'</label></td>';
                tHTML += '<td class="text-right"><label class="xCNDisChgValue">'+'0.00'+'</label></td>';
                tHTML += '<td class="text-right"><label class="xCNDisChgAfterDisChg">'+'0.00'+'</label></td>'; 
                tHTML += tSelectTypeDiscount;
                tHTML += tDiscount;
                tHTML += '<td nowrap="" class="text-center">';
                tHTML += '<label class="xCNTextLink">';
                tHTML += '<img class="xCNIconTable xWDisChgRemoveIcon" src="<?=base_url('application/modules/common/assets/images/icons/delete.png')?>" title="Remove" onclick="JSxRemoveDiscountRow(this)">';
                tHTML += '</label>';
                tHTML += '</td>';
                tHTML += '</tr>';
        $('#otbDisChgDataDocHDList tbody').append(tHTML);
        JSxCalculateDiscountChg();
    }

    //????????????????????????
    function JSxRemoveDiscountRow(elem){

    }

    //??????????????????????????????
    function JSxCalculateDiscountChg(){
        $('.xWDiscountChgTrTag').each(function(index){
            if($('.xWDiscountChgTrTag').length == 1){
                // $('img.xWPIDisChgRemoveIcon').first().attr('onclick','JSxPIResetDisChgRemoveRow(this)').css('opacity', '1');
            }else{
                // $('img.xWPIDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            var cBeforeDisChg = $('#olbSumFCXtdNetAlwDis').val();
            $(this).find('td label.xCNBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));
            
            var cCalc;
            var nDisChgType         = $(this).find('td select.xCNDisChgType').val();
            var cDisChgNum          = $(this).find('td input.xCNDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xCNBeforeDisChg').text());
            var cDisChgValue        = $(this).find('td label.xCNDisChgValue').text();
            var cDisChgAfterDisChg  = $(this).find('td label.xCNDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ???????????????
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 2){ // ?????? %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }
            
            if(nDisChgType == 3){ // ????????????????????????
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }
            
            if(nDisChgType == 4){ // ??????????????? %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xCNDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xCNBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }



    /**
        * Functionality: Save Discount And Chage Footer HD (???????????????????????????)
        * Parameters: Event Proporty
        * Creator: 22/05/2019 Piya  
        * Return: Open Modal Discount And Change HD
        * Return Type: View
    */
    function JCNvSOMngDocDisChagHD(event){
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var oSODisChgParams = {
                DisChgType: 'disChgHD'
            };
            JSxSOOpenDisChgPanel(oSODisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        } 
}


    /**
        * Function: Set Data Value End Of Bile
        * Parameters: Document Type
        * Creator: 01/07/2019 wasin(Yoshi)
        * LastUpdate: -
        * Return: Set Value In Text From
        * ReturnType: None
    */
    function JSxSOSetFooterEndOfBill(poParams){
        /* ================================================= Left End Of Bill ================================================= */
            // Set Text Bath
            var tTextBath   = poParams.tTextBath;
            $('#odvDataTextBath').text(tTextBath);

            // ?????????????????? vat
            var aVatItems   = poParams.aEndOfBillVat.aItems;
            var tVatList    = "";
            if(aVatItems.length > 0){
                for(var i = 0; i < aVatItems.length; i++){
                    var tVatRate = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                    var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                    var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow;?>) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                    tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + tSumVat + '</label><div class="clearfix"></div></li>';
                    
                    // var tVatRate    = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                    // var tSumVat     = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(2);
                    // tVatList        += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + tSumVat + '</label><div class="clearfix"></div></li>';
                }
            }
            $('#oulDataListVat').html(tVatList);
            
            // ???????????????????????????????????????????????????????????????
            var cSumVat     = poParams.aEndOfBillVat.cVatSum;
            $('#olbVatSum').text(cSumVat);
        /* ==================================================================================================================== */

        /* ================================================= Right End Of Bill ================================================ */
            var cCalFCXphGrand      = poParams.aEndOfBillCal.cCalFCXphGrand;
            var cSumFCXtdAmt        = poParams.aEndOfBillCal.cSumFCXtdAmt;
            var cSumFCXtdNet        = poParams.aEndOfBillCal.cSumFCXtdNet;
            var cSumFCXtdNetAfHD    = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
            var cSumFCXtdVat        = poParams.aEndOfBillCal.cSumFCXtdVat;
            var tDisChgTxt          = poParams.aEndOfBillCal.tDisChgTxt;
            var tDisChgType         = poParams.aEndOfBillCal.tDisChgType;

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

                    //????????????????????????????????????????????????????????? comma ?????????
                    if(i == aExplode.length - 1 ){
                        tTextDisChg = tTextDisChg.substring(tTextDisChg.length-1,-1);
                    }
                }
            }
            
            // ????????????????????????????????????
            $('#olbSumFCXtdNet').text(cSumFCXtdNet);
            // ??????/???????????????
            $('#olbSumFCXtdAmt').text(cSumFCXtdAmt);
            // ????????????????????????????????????/???????????????
            $('#olbSumFCXtdNetAfHD').text(cSumFCXtdNetAfHD);
            // ???????????????????????????????????????????????????????????????
            $('#olbSumFCXtdVat').text(cSumFCXtdVat);
            $('#ohdSumFCXtdVat').val(cSumFCXtdVat.replace(",", ""));
            // ????????????????????????????????????????????????????????????
            $('#olbCalFCXphGrand').text(cCalFCXphGrand);
            //?????????????????????/??????????????? ?????????????????????
            $('#olbDisChgHD').text(tTextDisChg);
            $('#ohdSODisChgHD').val(tDisChgTxt);
            $('#ohdSODisChgType').val(tDisChgType);
            
        /* ==================================================================================================================== */
    }

    //Check All
    function FSxSOSelectAll(){
        if($('.ocbListItemAll').is(":checked")){
            $('.ocbListItem').each(function (e) { 
                if(!$(this).is(":checked")){
                    $(this).on( "click", FSxSOSelectMulDel(this) );
                }
        });
        }else{
            $('.ocbListItem').each(function (e) { 
                if($(this).is(":checked")){
                    $(this).on( "click", FSxSOSelectMulDel(this) );
                }
        });
        }
        
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
    

</script>


