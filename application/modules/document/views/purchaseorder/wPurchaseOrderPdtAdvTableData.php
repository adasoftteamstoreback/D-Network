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
    <div class="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPOPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPOPunCode;?>">
        <table id="otbPODocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCWHidden">
                    <label class="fancy-checkbox">
                                <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxPOSelectAll(this)">
                                <span class="ospListItem"></span>
                            </label>
                    </th>
                    <th class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBNo')?>
                    </th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchaseorder/purchaseorder','tPOTable_unit')?></th>
                    <th class="xCNTextBold" style="width:15%"><?=language('document/purchaseorder/purchaseorder','tPOTable_qty')?></th>
                    <th class="xCNTextBold xCNHide"><?=language('document/purchaseorder/purchaseorder','tPOTable_price')?></th>
                    <th class="xCNTextBold xCNHide"><?=language('document/purchaseorder/purchaseorder','tPOTable_discount')?></th>
                    <th class="xCNTextBold xCNHide"><?=language('document/purchaseorder/purchaseorder','tPOTable_grand')?></th>
                    <th class="xCNTextBold xCNHide xPOImportDT" style="display:none"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRemark')?></th>
                    <th class="xCNPIBeHideMQSS xCWHidden"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDelete');?></th>
                    <!-- <th class="xCNPIBeHideMQSS xWPIDeleteBtnEditButtonPdt"><?php echo language('document/purchaseorder/purchaseorder','tPOTBEdit');?></th> -->
                </tr>
            </thead>
            <tbody id="odvTBodyPOPdtAdvTableList">
            <?php 
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
            ?>
                    <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>" 
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
                        style="background-color: rgb(255, 255, 255);" 
                    >
                        <td style='text-align:center' class="xCWHidden">
                            <label class="fancy-checkbox">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPOSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <td style='text-align:center'><?=$nKey?></td>
                        <td><?=$aDataTableVal['FTPdtCode'];?></td>
                        <?php if($aDataTableVal['FTPgpChain'] == '5' && $tPOStaDoc == '3'){?>
                            <td>
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty xCNHide form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                            </div>
                            <?=$aDataTableVal['FTXtdPdtName'];?>
                        </td>
                        <?php }elseif($aDataTableVal['FTPgpChain'] == '5' && $tPOStaApv != '1'){?>
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
                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>
                        <td class="otdQty">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <td class="otdPrice xCNHide">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> " id="ohdPrice<?=$nKey?>" name="ohdPrice<?=$nKey?>" maxlength="10" data-alwdis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" data-seq="<?=$nKey?>" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdSetPrice'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <td class="xCNHide">
                        <?php 
                            if($aDataTableVal['FTXtdStaAlwDis'] == 1): 
                        ?>
                            <div>
                                <button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPOCallModalDisChagDT(this)" type="button">+</button>
                                <label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp<?=$nKey?>"><?=$aDataTableVal['FTXtdDisChgTxt'];?></label>
                            </div>
                        <?php 
                            else: 
                                echo language('document/purchaseorder/purchaseorder','tPODiscountisnotallowed'); 
                            endif;
                        ?>
                        </td>

                        <td class="text-right xCNHide">
                            <span id="ospGrandTotal<?=$nKey?>"><?=number_format($aDataTableVal['FCXtdNet'],2);?></span>
                            <span id="ospnetAfterHD<?=$nKey?>" style="display: none;"><?=number_format($aDataTableVal['FCXtdNetAfHD'],2);?></span>
                        </td>
                        <!-- <td class="xPOImportDT xPOStaValidate<?=$aDataTableVal['FTSrnCode']?>" style="display:none;"> -->
                        <?php 
                            if($aDataTableVal['FTSrnCode'] == 0): 
                                echo '<span style="color:red">'.$aDataTableVal['FTTmpRemark'].'</span>'; 
                            else: 
                                echo ""; 
                            endif;
                        ?>
                        </td>
                        <td nowrap="" class="text-center xCNPIBeHideMQSS xCWHidden"> 
                            <label class="xCNTextLink">
                                <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPODelPdtInDTTempSingle(this)">
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
    <div id="odvPOModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPOMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/purchaseorder/purchaseorder','tPOMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtPOConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtPOCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', '??????????????????');?></button>
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

<?php  include("script/jPurchaseOrderPdtAdvTableData.php");?>

<script>  
    $( document ).ready(function() {

        $('#oetPOFrmFilterPdtHTML').attr('readonly',false);
        
        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();    
        if($('#ohdPOStaApv').val()==1){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPOBrowseCustomer').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $('.ocbListItemAll').attr('disabled',true);
            $('.xCWHidden').hide();
        }

        if($('#ohdPOStaDoc').val()==3){
            $('.xCWHidden').hide();
            $('.xCNPdtEditInLine').attr('readonly',true);
        }
    });


    // Next Func ????????? Browse PDT Center
    function FSvPONextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvPOAddPdtIntoDocDTTemp(aNewPackData);         // Append HMTL
            FSvPOAddBarcodeIntoDocDTTemp(aNewPackData);     // Insert Database
        }
    }

    // Append PDT
    function FSvPOAddPdtIntoDocDTTemp(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        console.log(aPackData[0]);
        var tCheckIteminTableClass = $('#otbPODocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        // var tCheckIteminTable = $('#otbPODocPdtAdvTableList tbody tr').length;
        if(tCheckIteminTableClass==true){
            $('#otbPODocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbPODocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        // var nKey    = parseInt($('#otbPODocPdtAdvTableList tbody tr').length) + parseInt(1);
        
        for(var i=0; i<nLen; i++){

            var oData           = aPackData[i];
            var oResult         = oData.packData;

            console.log(oResult);

            oResult.NetAfHD     = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.Net == '' || oResult.Net === undefined ? oResult.Price : oResult.Net);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);

            var tBarCode        = oResult.Barcode;          //????????????????????????
            var tProductCode    = oResult.PDTCode;          //??????????????????????????????
            var tProductName    = oResult.PDTName;          //??????????????????????????????
            var tUnitName       = oResult.PUNName;          //?????????????????????????????????????????????
            var tPdtType        = oResult.tTypePDT;         //???????????????????????????????????????
            var nPrice          = (parseFloat(oResult.Price)).toFixed(nPOODecimalShow);            //????????????
            // var nGrandTotal     = (nPrice * 1).toFixed(nPOODecimalShow);  //?????????????????????
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //???????????????????????????????????????
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //?????????????????????????????????????????????
            var nVat            = (parseFloat($('#ohdPOFrmSplVatRate').val())).toFixed(nPOODecimalShow);              //????????????
            var nQty            = parseInt(oResult.Qty);             //???????????????
            var nNetAfHD        = (parseFloat(oResult.NetAfHD)).toFixed(nPOODecimalShow);
            var cNet            = (parseFloat(oResult.Net)).toFixed(nPOODecimalShow);
            var tDisChgTxt      = oResult.tDisChgTxt;

            // console.log(oData);

            var tDuplicate = $('#otbPODocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmPOFrmInfoOthReAddPdt').val();
            if(tDuplicate == true && InfoOthReAddPdt==1){
                //???????????????????????????????????? ?????????????????? Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);
                var tCname = 'otr'+tProductCode+tBarCode;

                $('.'+tCname).each(function (e) { 
                        if(e == '0'){
                            $(this).find('.xCNQty').val(nNewValue);
                        }
                });
                // $('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);

                var nGrandOld   = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').val();
                var nGrand      = parseInt(nNewValue) * parseFloat(nGrandOld);
                var nSeqOld     = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').attr('data-seq');
                $('#ospGrandTotal'+nSeqOld).text(numberWithCommas(nGrand.toFixed(nPOODecimalShow)));
            }else{
                //????????????????????????????????????????????? ????????????????????????????????????????????????
                if(nAlwDiscount == 1){ //????????????????????????
                    var oAlwDis = '<div>';
                        oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPOCallModalDisChagDT(this)" type="button">+</button>'; //JCNvDisChgCallModalDT(this)
                        oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp'+nKey+'">'+tDisChgTxt+'</label>';
                        oAlwDis += '</div>';
                }else{
                    var oAlwDis = '??????????????????????????????????????????????????????';
                }

                //????????????
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


                //???????????? ????????????????????????????????? Type 5
                if(tPdtType == '5'){
                var oName = '<div class="xWEditInLine'+nKey+'">';
                    oName += '<input ';
                    oName += 'type="text" ';
                    oName += 'class="xCNName form-control xCNPdtEditInLine text-left xWValueEditNameInLine'+nKey+' xWShowNameInLine'+nKey+' "';
                    oName += 'id="ohdPdtName'+nKey+'" ';
                    oName += 'name="ohdPdtName'+nKey+'" '; 
                    oName += 'data-seq='+nKey+' ';
                    oName += 'value="'+tProductName+'"';
                    oName += 'autocomplete="off" >';
                    oName += '</div>';  
                }else{
                    var oName = '<div class="xWEditInLine'+nKey+'">';
                    oName += '<input ';
                    oName += 'type="text" ';
                    oName += 'class="xCNName xCNHide form-control xCNPdtEditInLine text-left xWValueEditNameInLine'+nKey+' xWShowNameInLine'+nKey+' "';
                    oName += 'id="ohdPdtName'+nKey+'" ';
                    oName += 'name="ohdPdtName'+nKey+'" '; 
                    oName += 'data-seq='+nKey+' ';
                    oName += 'value="'+tProductName+'"';
                    oName += 'autocomplete="off" >';
                    oName += tProductName+'</div>';  
                }
                //???????????????
               
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
                tHTML += '<td style="text-align:center">';
                tHTML += '  <label class="fancy-checkbox">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPOSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td style="text-align:center">'+nKey+'</td>';
                tHTML += '<td>'+tProductCode+'</td>';
                tHTML += '<td>'+oName+'</td>';
                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="otdQty">'+oQty+'</td>';
                tHTML += '<td class="otdPrice xCNHide">'+oPrice+'</td>';
                tHTML += '<td class="xCNHide">'+oAlwDis+'</td>';
                tHTML += '<td class="text-right xCNHide"><span id="ospGrandTotal'+nKey+'">'+cNet+'</span>';
                tHTML += '    <span id="ospnetAfterHD'+nKey+'" style="display: none;">'+nNetAfHD+'</span>';
                tHTML += '</td>';    
                // if($('#ohdPOStaImport').val()==1){
                // tHTML += '<td class="xPOImportDT"> </td>';
                // }
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPODelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //??????????????????????????????
        $('#otbPODocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
    }

function FSxPOSelectMulDel(ptElm){
    // $('#otbPODocPdtAdvTableList #odvTBodyPOPdtAdvTableList .ocbListItem').click(function(){
        let tPODocNo    = $('#oetPODocNo').val();
        let tPOSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPOPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tPOBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        // let tPOPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("PO_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("PO_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tPODocNo,
                'tSeqNo'    : tPOSeqNo,
                'tPdtCode'  : tPOPdtCode,
                'tBarCode'  : tPOBarCode,
                // 'tPunCode'  : tPOPunCode,
            });
            localStorage.setItem("PO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxPOTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStPOFindObjectByKey(aArrayConvert[0],'tSeqNo',tPOSeqNo);
            if(aReturnRepeat == 'None' ){
                //??????????????????????????????????????????
                oDataObj.push({
                    'tDocNo'    : tPODocNo,
                    'tSeqNo'    : tPOSeqNo,
                    'tPdtCode'  : tPOPdtCode,
                    'tBarCode'  : tPOBarCode,
                    // 'tPunCode'  : tPOPunCode,
                });
                localStorage.setItem("PO_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxPOTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("PO_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tPOSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PO_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxPOTextInModalDelPdtDtTemp();
            }
        }
        JSxPOShowButtonDelMutiDtTemp();
    // });
}

function FSxPOSelectAll(){
    if($('.ocbListItemAll').is(":checked")){
        $('.ocbListItem').each(function (e) { 
            if(!$(this).is(":checked")){
                $(this).on( "click", FSxPOSelectMulDel(this) );
            }
    });
    }else{
        $('.ocbListItem').each(function (e) { 
            if($(this).is(":checked")){
                $(this).on( "click", FSxPOSelectMulDel(this) );
            }
    });
    }
    
}

function JSxAddScollBarInTablePdt(){
    $('#otbPODocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
    var rowCount = $('#otbPODocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbPODocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');
       
        }

    if(rowCount >= 7){
        $('.xCNTablescroll').css('height','450px');
        $('.xWShowInLine' + rowCount).focus();

        $('html, body').animate({
             scrollTop: ($("#oetPOInsertBarcode").offset().top)-80
         }, 0);
    }



        
        if($('#oetPOFrmCstCode').val() != ''){
            $('#oetPOInsertBarcode').focus();
        }

    }
    //????????????????????????????????????????????????????????????????????? DT
    function JSxRendercalculate(){

        var nTotal                  = 0;
        var nTotal_alwDiscount      = 0;
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
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
        $('#olbSumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(nPOODecimalShow)));

        //???????????????????????????????????? ?????????????????????????????????
        $('#olbSumFCXtdNetAlwDis').val(nTotal_alwDiscount);

        //???????????????????????????????????????
        var tChgHD          = $('#olbDisChgHD').text();
        console.log(tChgHD);
        var nNewDiscount    = 0;
        if(tChgHD != '' && tChgHD != null){ //?????????????????????????????????????????????
            var aChgHD      = tChgHD.split(",");
            var nNetAlwDis  = $('#olbSumFCXtdNetAlwDis').val();
        
            for(var i=0; i<aChgHD.length; i++){
                // console.log('??????????????????????????????????????????????????????????????????????????? : ' + nNetAlwDis);
                if(aChgHD[i] != '' && aChgHD[i] != null){
                    if(aChgHD[i].search("%") == -1){ 
                     
                        //?????????????????? = ?????????????????????????????????????????????
                        var nVal        = aChgHD[i];
                        var nCal        = (parseFloat(nNetAlwDis) + parseFloat(nVal));
                        // console.log('???????????????????????????????????? : ' + nCal)
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }else{ 
               
                        //????????? = ???????????????????????????????????? %
                        var nPercent    = aChgHD[i];
                        var nPercent    = nPercent.replace("%", "");
                        var tCondition  = nPercent.substr(0, 1);
                        var nValPercent = Math.abs(nPercent);
                        if(tCondition == '-'){
                            var nCal        = parseFloat(nNetAlwDis) - ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                            if(nCal == 0){
                                var nCal        = -nNetAlwDis;
                            }else{
                                var nCal        = nCal;
                            }
                        }else if(tCondition == '+'){
                            var nCal        = parseFloat(nNetAlwDis) + ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                        }

                        // console.log('???????????????????????????????????? : ' + nCal);
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }
                }
            }
            var nDiscount = (nNetAlwDis - parseFloat($('#olbSumFCXtdNetAlwDis').val()));
                            console.log(nDiscount,"nDiscount");
                        
            $('#olbSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(nPOODecimalShow)));

            //Prorate
            JSxProrate();
        }

        //????????????????????????????????????/???????????????
        var nTotalFisrt = $('#olbSumFCXtdNet').text().replace(/,/g, '');
        var nDiscount   = $('#olbSumFCXtdAmt').text().replace(/,/g, '');
        var nResult     = parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount);
        $('#olbSumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(nPOODecimalShow)));

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
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        $(".xCNPrice").each(function(index,e) {
            var nSeq    = $(this).attr('data-seq');
            var alwdis  = $(this).attr('data-alwdis');

            var nValue  = $('#ospGrandTotal'+nSeq).text();
            var nValue  = parseFloat(nValue.replace(/,/g, ''));
           var nProrate = (pnSumDiscount * nValue) / pnSum;
           var netAfterHD = 0 ;
            // console.log(alwdis,'alwdis');
           if(alwdis==1){
            // console.log(pnSumDiscount,'pnSumDiscount');
            // console.log(nValue,'nProrate');
            // console.log(nProrate,'netAfterHD');
        //    console.log(nValue,'nValue');
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
       
        //    $('#ospnetAfterHD'+nSeq).text(numberWithCommas(Math.abs(parseFloat(nProrate).toFixed(nPOODecimalShow))));
          
            // var nNetb4hd = parseFloat($('#ospnetAfterHD'+nSeq).text().replace(/,/g, ''));
            // console.log(nNetb4hd,'nNetb4hd');
            // console.log(nProrate,'nProrate');
           $('#ospnetAfterHD'+nSeq).text(numberWithCommas(parseFloat(nValue+nProrate).toFixed(nPOODecimalShow)));
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
                console.log(e.type);
                // var nValue      = $(this).val();
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $('.xWPdtItemList'+nSeq).attr('data-qty');
                var cPrice  = $('.xWPdtItemList'+nSeq).attr('data-setprice');
                // var nNewValue   = parseInt(nValue) * parseFloat($('#ohdPrice'+nSeq).val());
                // $('#ospGrandTotal'+nSeq).text(numberWithCommas(nNewValue.toFixed(nPOODecimalShow)));
                // JSxRendercalculate();
                nNextTab = parseInt(nSeq)+1;
                $('.xWValueEditInLine'+nNextTab).focus().select();
                // ?????????????????????????????????????????????
                var tDisChgDTTmp = $('#xWDisChgDTTmp'+nSeq).text();
                if(tDisChgDTTmp == ''){
                    JSxGetDisChgList(nSeq,0);
                    $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                }else{
                    // ????????????/???????????????
                    $('#odvPOModalConfirmDeleteDTDis').modal({
                        backdrop: 'static',
                        show: true
                    });
                    
                    // $('#odvPOModalConfirmDeleteDTDis #obtPOConfirmDeleteDTDis').unbind();
                    $('#odvPOModalConfirmDeleteDTDis #obtPOConfirmDeleteDTDis').off('click');
                    $('#odvPOModalConfirmDeleteDTDis #obtPOConfirmDeleteDTDis').on('click',function(){
                        // $('#odvPOModalConfirmDeleteDTDis').modal('hide');
                        // $('.modal-backdrop').remove();
                        JSxGetDisChgList(nSeq,1);
                        $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                    });

                    // $('#odvPOModalConfirmDeleteDTDis #obtPOCancelDeleteDTDis').unbind();
                    $('#odvPOModalConfirmDeleteDTDis #obtPOCancelDeleteDTDis').off('click');
                    $('#odvPOModalConfirmDeleteDTDis #obtPOCancelDeleteDTDis').on('click',function(){
                        // $('.modal-backdrop').remove();
                        e.preventDefault();
                        $('#ohdQty'+nQty).val(nQty);
                        $('#ohdPrice'+nQty).val(cPrice);
                    });

                    // $('#odvPOModalConfirmDeleteDTDis').modal('show');
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
        var tName        = $('#ohdPdtName'+pnSeq).val();
        var cResult     = parseFloat(cPrice * nQty);
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        console.log(cPrice);
        // Fixed ???????????????????????????????????? 2 ?????????????????????
        $('#ohdPrice'+pnSeq).val(parseFloat(cPrice).toFixed(nPOODecimalShow));

        // Update Value
        $('#ospGrandTotal'+pnSeq).text(numberWithCommas(parseFloat(cResult).toFixed(nPOODecimalShow)));
        $('.xWPdtItemList'+pnSeq).attr('data-qty',nQty);
        $('.xWPdtItemList'+pnSeq).attr('data-setprice',parseFloat(cPrice).toFixed(nPOODecimalShow));
        $('.xWPdtItemList'+pnSeq).attr('data-net',parseFloat(cResult).toFixed(nPOODecimalShow));
        if(pnStaDelDis == 1){
            $('#xWDisChgDTTmp'+pnSeq).text('');
        }

        // ??????????????????????????????????????????????????? ????????????????????? NetAfHD
        if($('#olbDisChgHD').text() == ''){
            $('#ospnetAfterHD'+pnSeq).text(parseFloat(cResult).toFixed(nPOODecimalShow));
            $('.xWPdtItemList'+pnSeq).attr('data-netafhd',parseFloat(cResult).toFixed(nPOODecimalShow));
        }

        JSxRendercalculate();

        var tPODocNo        = $("#oetPODocNo").val();
        var tPOBchCode      = $("#oetPOFrmBchCode").val();
        $.ajax({
            type: "POST",
            url: "docPOEditPdtIntoDTDocTemp",
            data: {
                'tPOBchCode'          : tPOBchCode,
                'tPODocNo'            : tPODocNo,
                'nPOSeqNo'            : pnSeq,
                'nQty'                : nQty,
                'cPrice'              : cPrice,
                'cNet'                : cResult,
                'FTXtdPdtName'        : tName,
                'nStaDelDis'          : pnStaDelDis,
                'ohdSesSessionID'     : $('#ohdSesSessionID').val(),
                'ohdPOUsrCode'        : $('#ohdPOUsrCode').val(),
                'ohdPOLangEdit'       : $('#ohdPOLangEdit').val(),
                'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                'ohdPOSesUsrBchCode'  : $('#ohdPOSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                JSxPOCountPdtItems();
                // if(oResult == 'expire'){
                //     JCNxShowMsgSessionExpired();
                // }else{
                //     JSvSOLoadPdtDataTableHtml();
                // }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                JSxGetDisChgList(pnSeq,pnStaDelDis);
            }
        });
       

    }



    //???????????????????????????
    $('#ocmVatInOrEx').change(function (){ JSxCalculateVat(); });  
    function JSxCalculateVat(){
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        var tVatList  = '';
        var aVat      = [];
        $('#otbPODocPdtAdvTableList tbody tr').each(function(){
            var nAlwVat  = $(this).attr('data-alwvat');
            var nVat     = parseFloat($(this).attr('data-vat'));
            var nKey     = $(this).attr('data-key');
            var tTypeVat = $('#ocmPOFrmSplInfoVatInOrEx').val();

            console.log(nVat);    
            if(nAlwVat == 1){ 
                //??????????????????????????? VAT
                if(tTypeVat == 1){ 
                    // ??????????????????????????? tPoot = net - ((net * 100) / (100 + rate));
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult   = parseFloat(nTotalVat);
                }else if(tTypeVat == 2){
                    // ?????????????????????????????? tPoot = net - (net * (100 + 7) / 100) - net;
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult   = parseFloat(nTotalVat);
                }

                var oVat = { VAT: nVat , VALUE: nResult };
                aVat.push(oVat);
            }
        });
        console.log(aVat);

        //?????????????????????????????? array ????????????
        aVat.sort(function (a, b) {
            return a.VAT - b.VAT;
        });

        //???????????????????????? array ???????????? vat ?????????
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

        //  console.log(aVat);


        // var nPOVatRate =  parseFloat($('#ohdPOVatRate').val());
        // var nPOCmpRetInOrEx = $('#ocmPOFrmSplInfoVatInOrEx').val();
        // // ??????????????????????????? tPoot = net - ((net * 100) / (100 + rate));
        // var cSumFCXtdNetAfHD  = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
        // if(nPOCmpRetInOrEx==1){
        //     var nSumVatHD = cSumFCXtdNetAfHD - ((cSumFCXtdNetAfHD * 100)/(100 + nPOVatRate));
        // }else{
        //     var nSumVatHD =  ((cSumFCXtdNetAfHD * nPOVatRate)/100);
        // }
        
 
        
                //????????? VAT ?????????????????????????????????
        var cSumFCXtdVat = parseFloat($('#ohdSumFCXtdVat').val());
        var nSumVat = 0;
        var nCount = 1;
        for(var j=0; j<aSumVat.length; j++){
            var tVatRate    = aSumVat[j].VAT;
                  if(nCount!=aSumVat.length){
                    var tSumVat     = parseFloat(aSumVat[j].VALUE) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE);
                    }else{
                    var tSumVat     = (cSumFCXtdVat - nSumVat);
                    }
            tVatList    += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nPOODecimalShow)) + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }
        
        $('#oulDataListVat').html(tVatList);
       //???????????????????????????????????????????????????????????????
       $('#olbVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nPOODecimalShow)));
        //???????????????????????????????????????????????????????????????
        $('#olbSumFCXtdVat').text(numberWithCommas(parseFloat(nSumVat).toFixed(nPOODecimalShow)));
        $('#ohdSumFCXtdVat').val(nSumVat.toFixed(nPOODecimalShow));
        //?????????????????????????????????
        var tTypeVat = $('#ocmPOFrmSplInfoVatInOrEx').val();;
        if(tTypeVat == 1){ //?????????????????????????????????
            var nTotal          = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nResultTotal    = nTotal;
        }else if(tTypeVat == 2){ //????????????????????????????????????
            var nTotal          = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#olbSumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal) + parseFloat(nVat);
        }

        //????????????????????????????????????????????????????????????
        $('#olbCalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(nPOODecimalShow)));

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
        var tDuplicate = $('#otrPODisChgHDNotFound tbody tr').hasClass('otrPODisChgHDNotFound');
        var nPOODecimalShow = $('#ohdPOODecimalShow').val();
        if(tDuplicate == true){
            //?????????????????????
            $('#otrPODisChgHDNotFound tbody').html('');
        }

        //????????????????????????
        var nKey = parseInt($('#otrPODisChgHDNotFound tbody tr').length) + parseInt(1);

        //???????????????????????????????????? ?????????????????????????????????
        var nRowCount   = $('.xWDiscountChgTrTag').length;
        if(nRowCount > 0){
            var oLastRow   = $('.xWDiscountChgTrTag').last();
            var nNetAlwDis = oLastRow.find('td label.xCNDisChgAfterDisChg').text();
        }else{
            var nNetAlwDis = ($('#olbSumFCXtdNetAlwDis').val() == 0) ? '0.00' : $('#olbSumFCXtdNetAlwDis').val();
        }

        var     tSelectTypeDiscount =  '<td nowrap style="padding-left: 5px !important;">';
                tSelectTypeDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
                tSelectTypeDiscount += '<select class="dischgselectpicker form-control xCNDisChgType" onchange="JSxCalculateDiscountChg(this);">';
                tSelectTypeDiscount += '<option value="1"><?=language('common/main/main', '???????????????'); ?></option>';
                tSelectTypeDiscount += '<option value="2"><?=language('common/main/main', '?????? %'); ?></option>';
                tSelectTypeDiscount += '<option value="3"><?=language('common/main/main', '????????????????????????'); ?></option>';
                tSelectTypeDiscount += '<option value="4"><?=language('common/main/main', '??????????????? %'); ?></option>';
                tSelectTypeDiscount += '</select>';
                tSelectTypeDiscount += '</div>';
                tSelectTypeDiscount += '</td>';

        var     tDiscount =  '<td nowrap style="padding-left: 5px !important;">';
                tDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
                tDiscount += '<input class="form-control xCNInputNumericWithDecimal xCNDisChgNum" onchange="JSxCalculateDiscountChg(this);" onkeyup="javascript:if(event.keyCode==13) JSxCalculateDiscountChg(this);" type="text">';
                tDiscount += '</div>';
                tDiscount += '</td>';

        var     tHTML = '';
                tHTML += '<tr class="xWDiscountChgTrTag" >';
                tHTML += '<td>'+nKey+'</td>';
                tHTML += '<td class="text-right"><label class="xCNBeforeDisChg">'+numberWithCommas(parseFloat(nNetAlwDis).toFixed(nPOODecimalShow))+'</label></td>';
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
    function JCNvPOMngDocDisChagHD(event){

        //??????????????????????????????????????????????????????????????????????????? - ???????????????
        // $.ajax({
        //     type    : "POST",
        //     url     : "GetPriceAlwDiscount",
        //     data    : { 'tDocno' : $('#oetPODocNo').val() , 'tBCHCode' : $('#oetPOFrmBchCode').val() },
        //     cache   : false,
        //     timeout : 0,
        //     success : function(oResult) {
        //         var aTotal = JSON.parse(oResult);
        //         cSumFCXtdNet = aTotal.nTotal;
        //         $('#olbSumFCXtdNetAlwDis').val(cSumFCXtdNet);
        //     }
        // });
        // var cSumFCXtdNet = $('#olbSumFCXtdNet').text().replace(/,/g, '');
        // $('#olbSumFCXtdNetAlwDis').val(cSumFCXtdNet);

        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if($('#ohdPOStaApv').val()!=1 && $('#ohdPOStaDoc').val()!=3){
                    var oPODisChgParams = {
                        DisChgType: 'disChgHD'
                    };
                    JSxPOOpenDisChgPanel(oPODisChgParams);
          }
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
    function JSxPOSetFooterEndOfBill(poParams){
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
                    tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(<?php echo $nOptDecimalShow?>)) + '</label><div class="clearfix"></div></li>';
                    
                    // var tVatRate    = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                    // var tSumVat     = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(nPOODecimalShow);
                    // tVatList        += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + tSumVat + '</label><div class="clearfix"></div></li>';
                }
            }else{
                     tVatList += '<li class="list-group-item"><label class="pull-left">0%</label><label class="pull-right">0.00</label><div class="clearfix"></div></li>';
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
            $('#olbDisChgHD').text(tDisChgTxt);
        /* ==================================================================================================================== */
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

</script>


