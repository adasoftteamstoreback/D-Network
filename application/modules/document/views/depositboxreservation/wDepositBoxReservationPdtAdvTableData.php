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
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tDBRPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tDBRPunCode;?>">
        <input type="text" class="xCNHide" id="ohdDBRRtCode" value="<?php echo $aDataDocDTTemp['rtCode'];?>">
        <input type="text" class="xCNHide" id="ohdDBRStaDoc" value="<?php echo $tDBRStaDoc;?>">
        <input type="text" class="xCNHide" id="ohdDBRStaApv" value="<?php echo $tDBRStaApv;?>">
        <input type="text" class="xCNHide" id="ohdUserCode" value="<?php echo $this->session->userdata('tSesUsername');?>">

        

        <table id="otbDBRDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <!-- <th class="text-center" id="othCheckboxHide">
                        <label class="fancy-checkbox">
                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxDBRSelectAll(this)">
                            <span class="">&nbsp;</span>
                        </label>
                    </th> -->
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_qty')?></th>
                    <th class="xCNTextBold" width="10%"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_unit')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝาก')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่อง')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','หมายเหตุ')?></th>
                    <th class="xCNPIBeHideMQSS xCNHide"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyDBRPdtAdvTableList">
            <?php 
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal): 
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
            ?>
            <input type="text" class="xCNHide" id="ohdDBRStaPshType<?=$nKey?>" value="<?php echo $aDataTableVal['FTPshType'];?>">
                    <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>" 
                        data-alwvat="<?=$aDataTableVal['FTXtdVatType'];?>" 
                        data-vat="<?=$aDataTableVal['FCXtdVatRate']?>" 
                        data-key="<?=$nKey?>" 
                        data-pdtcode="<?=$aDataTableVal['FTPdtCode'];?>" 
                        data-poscode="<?=$aDataTableVal['FTPosCode'];?>" 
                        data-shpcode="<?=$aDataTableVal['FTShpCode'];?>" 
                        data-layno="<?=$aDataTableVal['FTLayNo'];?>" 
                        data-pdtName="<?=$aDataTableVal['FTXtdPdtName'];?>" 
                        data-seqno="<?=$nKey?>" 
                        data-setprice="<?=$aDataTableVal['FCXtdSetPrice'];?>" 
                        data-qty="<?=$aDataTableVal['FCXtdQty'];?>" 
                        data-netafhd="<?=$aDataTableVal['FCXtdNetAfHD'];?>" 
                        data-net="<?=$aDataTableVal['FCXtdNet'];?>" 
                        data-stadis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" 
                    >
                        <!-- <td class="otdListItem">
                            <label class="fancy-checkbox text-center">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxDBRSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td> -->
                        <td><?=$aDataTableVal['FTPdtCode'];?></td>

                        <?php if($aDataTableVal['FTTmpStatus'] == '5' && $tDBRStaDoc == '3'){?>
                            <td>
                                <div class="xWEditInLine<?=$nKey?>">
                                    <input type="text" class="xCNQty1 xCNHide form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                                </div>
                                <?=$aDataTableVal['FTXtdPdtName'];?>
                            </td>
                        <?php }elseif($aDataTableVal['FTTmpStatus'] == '5' && $tDBRStaApv != '1'){?>
                        <td>
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty1 form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                            </div>
                        </td>
                        <?php }else{ ?>
                        <td>
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty1 xCNHide form-control xCNPdtEditInLine xWValueEditNameInLine<?=$nKey?> xWShowNameInLine<?=$nKey?> " id="ohdPdtName<?=$nKey?>" name="ohdPdtName<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=$aDataTableVal['FTXtdPdtName'];?>" autocomplete="off">
                            </div>
                            <?=$aDataTableVal['FTXtdPdtName'];?>
                        </td>
                        <?php } ?>

                        <!-- <td><?=$aDataTableVal['FTXtdPdtName'];?></td> -->


                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>

                        <td class="otdQty">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" 
                                class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " 
                                id="ohdQty<?=$nKey?>" 
                                name="ohdQty<?=$nKey?>" 
                                data-seq="<?=$nKey?>" 
                                maxlength="10" 
                                value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" 
                                autocomplete="off" 
                                readonly>
                            </div>
                        </td>
                        <?php if($aDataTableVal['FTPshType'] == '1'){
                            $PosName = $aDataTableVal['FTLayName'];
                        }elseif($aDataTableVal['FTPshType'] == '2' && $aDataTableVal['FTPshType'] != 'FTPosName'){
                            $PosName = 'Virtual';
                        }elseif($aDataTableVal['FTPosName'] == ''){
                            $PosName = '';
                        }else{
                            $PosName = 'Virtual';
                        } ?>
                        <td nowrap class="text-center otdBox">
                            <div class="xWEditInLine<?=$nKey?>">
                                <div class="input-group">
                                    <input type="hidden" class="xCNBox form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="ohdShopBox<?=$nKey?>"
                                    name="ohdShopBox<?=$nKey?>"
                                    maxlength="100"
                                    value="<?=$aDataTableVal['FTShpCode']?>" readonly>

                                    <input type="hidden" class="xCNBox form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="ohdBox<?=$nKey?>"
                                    name="ohdBox<?=$nKey?>"
                                    maxlength="20"
                                    value="<?=$aDataTableVal['FTPosCode']?>" readonly>

                                    <input type="hidden" class="xCNBox form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="oetBoxHiddenOld<?=$nKey?>"
                                    name="oetBoxHiddenOld<?=$nKey?>"
                                    maxlength="100"
                                    placeholder="<?php echo language('common/main/main', 'ตู้ฝาก') ?>"
                                    value="<?=$aDataTableVal['FTPosName']?>" readonly>

                                    <input type="text" class="xCNBox form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="oetBox<?=$nKey?>"
                                    name="oetBox<?=$nKey?>"
                                    maxlength="100"
                                    placeholder="<?php echo language('common/main/main', 'ตู้ฝาก') ?>"
                                    value="<?=$aDataTableVal['FTPosName']?>" readonly>

                                <span class="input-group-btn">
                                    <button id="obtDBRBrowseBox" onclick="JSnDBRBrowseBox(this,1)" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td nowrap class="text-center otdSlot">
                            <div class="xWEditInLine<?=$nKey?>">
                                <div class="input-group">
                                    <input type="hidden" class="xCNShp form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="ohdShp<?=$nKey?>"
                                    name="ohdShp<?=$nKey?>"
                                    maxlength="20"
                                    value="<?=$aDataTableVal['FTShpCode']?>" readonly>
                                    <input type="hidden" class="xCNSlot form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="ohdSlot<?=$nKey?>" 
                                    name="ohdSlot<?=$nKey?>"
                                    maxlength="20"
                                    value="<?=$aDataTableVal['FTLayNo']?>" readonly>

                                    <input type="hidden" class="xCNSlot form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="oetSlotHiddenOld<?=$nKey?>" 
                                    name="oetSlotHiddenOld<?=$nKey?>"
                                    maxlength="20"
                                    placeholder="<?php echo language('common/main/main', 'ช่อง') ?>"
                                    value="<?=$PosName?>" readonly>

                                    <input type="text" class="xCNSlot form-control xCNPdtEditInLine text-left<?=$nKey?> xWShowInLine<?=$nKey?>"
                                    id="oetSlot<?=$nKey?>" 
                                    name="oetSlot<?=$nKey?>"
                                    maxlength="20"
                                    placeholder="<?php echo language('common/main/main', 'ช่อง') ?>"
                                    value="<?=$PosName?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtDBRBrowseBoxsub<?=$nKey?>" onclick="JSnDBRBrowseBox(this,2)" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td nowrap class="text-center">
                            <input type="text" class="form-control xCNRmkInRow" data-seq=<?=$nKey?> id="oetRmkInRow<?=$nKey?>" name="oetRmkInRow<?=$nKey?>" value="<?=$aDataTableVal['FTXtdRmkInRow']?>" autocomplete="off"> 
                        </td>
                         
                        <td nowrap="" class="text-center xCNPIBeHideMQSS xCNHide">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable xWDelDocRef" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnDBRDelPdtInDTTempSingle(this)">
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
    <div id="odvDBRModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tDBRMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/purchaseorder/purchaseorder','tDBRMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtDBRConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtDBRCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ================================================================================================================================= -->



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
<?php  include("script/jDepositBoxReservationPdtAdvTableData.php");?>

<script>  
    
    $( document ).ready(function() {
        $(".xWPdtItem").each(function () { 
            var tSeqno = $(this).data('seqno');
            var tChkType = $("#ohdDBRStaPshType"+tSeqno).val();
            if(tChkType == '2'){
                $("#obtDBRBrowseBoxsub"+tSeqno).prop('disabled', true);
            }else if(tChkType == '1'){
                $("#obtDBRBrowseBoxsub"+tSeqno).prop('disabled', false);
            }else{
                $("#obtDBRBrowseBoxsub"+tSeqno).prop('disabled', true);
            }
        });
        
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();    
        var tStaDoc = $('#ohdDBRStaDoc').val();
        var tStaApv = $('#ohdDBRStaApv').val();
        
        if( tStaDoc == 3 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $("#othCheckboxHide").hide();
            $(".xCNPIBeHideMQSS").hide();
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDBRBrowseCustomer').attr('disabled',true);
            $('.otdListItem').hide();
            $('#otbDBRDocPdtAdvTableList .xCNBtnBrowseAddOn').attr('disabled',true);
            $('#otbDBRDocPdtAdvTableList .xCNRmkInRow').attr('disabled',true);
        }else if( tStaApv == 1 && tStaDoc == 1 ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $("#othCheckboxHide").hide();
            $(".xCNPIBeHideMQSS").hide();
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtDBRBrowseCustomer').attr('disabled',true);
            $('.otdListItem').hide();
            $('#otbDBRDocPdtAdvTableList .xCNBtnBrowseAddOn').attr('disabled',true);
            $('#otbDBRDocPdtAdvTableList .xCNRmkInRow').attr('disabled',true);
        }

        JSxDBRCountPdtItems();


        var tChkTax = $("#ohdDBRDocRefTAX").val();
        var tChkPrint = $("#ocmDBRFrmInfoOthDocPrint").val();

        if(tChkTax == ''){
            $("#obtDBRPrintDoc2").hide();
            $("#obtDBRPrintDoc").text('พิมพ์ใบจองช่องฝาก');
        }

        if(tChkPrint == '1'){
            $("#obtDBRPrintDoc2").prop('disabled',false);
            if(tChkTax == ''){
                $("#obtDBRPrintDoc").prop('disabled',false);
            }
        }else if(tChkPrint == '2'){
            $("#obtDBRPrintDoc").prop('disabled',false);
            $("#obtDBRPrintDoc2").prop('disabled',false);
        }

    
    });


    // Next Func จาก Browse PDT Center
    function FSvDBRNextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        // console.log(aPackData[0]);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvDBRAddPdtIntoDocDTTemp(aNewPackData);         // Append HMTL
            FSvDBRAddBarcodeIntoDocDTTemp(aNewPackData);     // Insert Database
        }
    }

    // Append PDT
    function FSvDBRAddPdtIntoDocDTTemp(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        //console.log(aPackData[0]);
        var tCheckIteminTableClass = $('#otbDBRDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nDBRODecimalShow = $('#ohdDBRODecimalShow').val();
        // var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList tbody tr').length;
        if(tCheckIteminTableClass==true){
            $('#otbDBRDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbDBRDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        // var nKey    = parseInt($('#otbDBRDocPdtAdvTableList tbody tr').length) + parseInt(1);
        
        for(var i=0; i<nLen; i++){

            var oData           = aPackData[i];
            var oResult         = oData.packData;

            //console.log(oResult);

            oResult.NetAfHD     = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.Net == '' || oResult.Net === undefined ? oResult.Price : oResult.Net);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);

            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            var nQty            = parseInt(oResult.Qty);             //จำนวน
            var tTypePDT        = oResult.tTypePDT


            // console.log(oData);

            var tDuplicate = $('#otbDBRDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmDBRFrmInfoOthReAddPdt').val();
            if(tDuplicate == true && InfoOthReAddPdt==1){
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                console.log(nValOld);
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
                    oQty += 'autocomplete="off" readonly>';
                    oQty += '</div>';  

                //ตู้ฝาก
                var oBox = '<div class="xWEditInLine'+nKey+'">';
                    oBox += '<div class="input-group">'
                    oBox += '<input type="hidden" class="xCNBox form-control xCNPdtEditInLine text-left'+nKey+' xWShowInLine'+nKey+'"'
                    oBox += 'id="ohdBox'+nKey+'"' 
                    oBox += 'name="ohdBox'+nKey+'"'
                    oBox += 'maxlength="20"'
                    oBox += 'value="" readonly>'
                    oBox += '<input type="text" class="xCNBox form-control xCNPdtEditInLine text-left'+nKey+' xWShowInLine'+nKey+'"'
                    oBox += 'id="oetBox'+nKey+'"' 
                    oBox += 'name="oetBox'+nKey+'"'
                    oBox += 'maxlength="20"'
                    oBox += 'placeholder="<?php echo language('common/main/main', 'ตู้ฝาก') ?>"'
                    oBox += 'value="" readonly>'
                    oBox += '<span class="input-group-btn">'
                    oBox += '<button id="obtDBRBrowseBox" onclick="JSnDBRBrowseBox(this,1)" type="button" class="btn xCNBtnBrowseAddOn">'
                    oBox += '<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">'
                    oBox += '</button>'
                    oBox += '</span>'
                    oBox += '</div>'

                //ช่อง
                var oSlot = '<div class="xWEditInLine'+nKey+'">';
                    oSlot += '<div class="input-group">'
                    oSlot += '<input type="hidden" class="xCNShp form-control xCNPdtEditInLine text-left'+nKey+' xWShowInLine'+nKey+'"'
                    oSlot += 'id="ohdShp'+nKey+'"' 
                    oSlot += 'name="ohdShp'+nKey+'"'
                    oSlot += 'maxlength="20"'
                    oSlot += 'value="" readonly>'
                    oSlot += '<input type="hidden" class="xCNSlot form-control xCNPdtEditInLine text-left'+nKey+' xWShowInLine'+nKey+'"'
                    oSlot += 'id="ohdSlot'+nKey+'"' 
                    oSlot += 'name="ohdSlot'+nKey+'"'
                    oSlot += 'maxlength="20"'
                    oSlot += 'value="" readonly>'
                    oSlot += '<input type="text" class="xCNSlot form-control xCNPdtEditInLine text-left'+nKey+' xWShowInLine'+nKey+'"'
                    oSlot += 'id="oetSlot'+nKey+'"' 
                    oSlot += 'name="oetSlot'+nKey+'"'
                    oSlot += 'maxlength="20"'
                    oSlot += 'placeholder="<?php echo language('common/main/main', 'ช่อง') ?>"'
                    oSlot += 'value="" readonly>'
                    oSlot += '<span class="input-group-btn">'
                    oSlot += '<button id="obtDBRBrowseBoxsub'+nKey+'" onclick="JSnDBRBrowseBox(this,2)" type="button" class="btn xCNBtnBrowseAddOn">'
                    oSlot += '<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">'
                    oSlot += '</button>'
                    oSlot += '</span>'
                    oSlot += '</div>'  

                tHTML += '<tr class="otr'+tProductCode+''+tBarCode+' xWPdtItem xWPdtItemList'+nKey+'"';
                tHTML += '  data-key="'+nKey+'"';
                tHTML += '  data-pdtcode="'+tProductCode+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-qty="'+nQty+'"';
                tHTML += '  data-TypePdt="'+tTypePDT+'"';

                tHTML += '>';
                // tHTML += '<td class="otdListItem">';
                // tHTML += '  <label class="fancy-checkbox text-center">';
                // tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxDBRSelectMulDel(this)">';
                // tHTML += '      <span class="ospListItem">&nbsp;</span>';
                // tHTML += '  </label>';
                // tHTML += '</td>';
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
                // tHTML += '<td>'+tProductName+'</td>';

                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="otdQty">'+oQty+'</td>';
                if($('#ohdPOSTaImport').val()==1){
                tHTML += '<td class="xDBRImportDT"> </td>';
                }
                tHTML += '<td nowrap class="text-center otdBox">'+oBox+'</td>';
                tHTML += '<td nowrap class="text-center otdSlot">'+oSlot+'</td>';
                tHTML += '<td nowrap class="text-center"><input type="text" class="form-control xCNRmkInRow" data-seq='+nKey+' id="oetRmkInRow'+nKey+'" name="oetRmkInRow'+nKey+'" autocomplete="off"> </td>';
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS xCNHide">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable xWDelDocRef" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnDBRDelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbDBRDocPdtAdvTableList tbody').append(tHTML);

        JSxDBRCountPdtItems();
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();
    }
    // Check All
    $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvDBRMngDelPdtInTableDT #oliDBRBtnDeleteMulti").addClass("disabled");
        }
    });

    function FSxDBRSelectMulDel(ptElm){
    // $('#otbDBRDocPdtAdvTableList #odvTBodyDBRPdtAdvTableList .ocbListItem').click(function(){
        let tDBRDocNo    = $('#oetDBRDocNo').val();
        let tDBRSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tDBRPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tDBRBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        var nDBRODecimalShow = $('#ohdDBRODecimalShow').val();
        // let tDBRPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("DBR_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("DBR_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tDBRDocNo,
                'tSeqNo'    : tDBRSeqNo,
                'tPdtCode'  : tDBRPdtCode,
                'tBarCode'  : tDBRBarCode,
                // 'tPunCode'  : tDBRPunCode,
            });
            localStorage.setItem("DBR_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxDBRTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStDBRFindObjectByKey(aArrayConvert[0],'tSeqNo',tDBRSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tDBRDocNo,
                    'tSeqNo'    : tDBRSeqNo,
                    'tPdtCode'  : tDBRPdtCode,
                    'tBarCode'  : tDBRBarCode,
                    // 'tPunCode'  : tDBRPunCode,
                });
                localStorage.setItem("DBR_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxDBRTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("DBR_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tDBRSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("DBR_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxDBRTextInModalDelPdtDtTemp();
            }
        }
        JSxDBRShowButtonDelMutiDtTemp();
        // });
    }

    function JSxAddScollBarInTablePdt(){
        $('#otbDBRDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbDBRDocPdtAdvTableList >tbody >tr').length;
            if(rowCount >= 2){
                $('#otbDBRDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');
        
            }
            
        if(rowCount >= 7){
            $('.xWShowInLine' + rowCount).focus();

            $('html, body').animate({
                scrollTop: ($("#oetDBRInsertBarcode").offset().top)-80
            }, 0);
        }

        if($('#oetDBRFrmCstCode').val() != ''){
            $('#oetDBRInsertBarcode').focus();
        }
    }

        //เเก้ไขจำนวน
    function JSxEditQtyAndPrice() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        // $('.xCNQty').change(function(e){
        $('.xCNPdtEditInLine').off().on('change keyup', function(e) {
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
        var nQty         = $('#ohdQty'+pnSeq).val();
        var tRmk         = $('#oetRmkInRow'+pnSeq).val();
        var tDBRDocNo    = $("#oetDBRDocNo").val();
        var tDBRBchCode  = $("#ohdDBRBchCode").val();
        var tName        = $('#ohdPdtName'+pnSeq).val();

        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docDBREditPdtInDTDocTemp",
                data    : {
                    'tDBRBchCode'        : tDBRBchCode,
                    'tDBRDocNo'          : tDBRDocNo,
                    'nDBRSeqNo'          : pnSeq,
                    'FTXtdPdtName'      : tName,
                    'nQty'              : nQty,
                    'tRmk'              : tRmk,
                    'nBrowseType'       : ''
                },
                catch   : false,
                timeout : 0,
                success : function (oResult){ },
                error   : function (jqXHR, textStatus, errorThrown) { }
            });
        }
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
        
        $('.xCNRmkInRow').off().on('change keyup', function(e) {
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var tRmk        = $('#oetRmkInRow'+nSeq).val();
                nNextTab = parseInt(nSeq)+1;
                
                JSxGetDisChgList(nSeq);
            }
        });
    });

    function FSxDBRSelectAll(){
    if($('.ocbListItemAll').is(":checked")){
        $('.ocbListItem').each(function (e) { 
            if(!$(this).is(":checked")){
                $(this).on( "click", FSxDBRSelectMulDel(this) );
            }
    });
    }else{
        $('.ocbListItem').each(function (e) { 
            if($(this).is(":checked")){
                $(this).on( "click", FSxDBRSelectMulDel(this) );
            }
    });
    }
    
}

</script>


