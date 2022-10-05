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
<?php 
// print_r($aDataDocDTTemp['raItems']) 
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPRBPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPRBPunCode;?>">
        <input type="text" class="xCNHide" id="ohdPRBRtCode" value="<?php echo $aDataDocDTTemp['rtCode'];?>">
        <input type="text" class="xCNHide" id="ohdPRBStaDoc" value="<?php echo $tPRBStaDoc;?>">
        <input type="text" class="xCNHide" id="ohdPRBStaApv" value="<?php echo $tPRBStaApv;?>">
        <table id="otbPRBDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="text-center" id="othCheckboxHide">
                        <label class="fancy-checkbox">
                            <input id="ocbCheckAll" type="checkbox" class="ocbListItemAll" name="ocbCheckAll" onclick="FSxPRBSelectAll(this)">
                            <span class="">&nbsp;</span>
                        </label>
                    </th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_pdtcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_qty')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_Factor')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_barcode')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_stock')?></th>
                    <!-- <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_stockb')?></th> -->
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_stockw')?></th>
                    <th class="xCNTextBold"><?=language('document/purchasebranch/purchasebranch','tPRBTable_unit')?></th>
                    <th class="xCNPIBeHideMQSS"><?php echo language('common/main/main','tCMNActionDelete')?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyPRBPdtAdvTableList">
            <?php
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal):
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
            ?>
                    <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtMerge<?=$aDataTableVal['FTPdtCode'];?> xWPdtMergeSeq<?=$aDataTableVal['FTPdtCode'];?><?=$nKey?> xWPdtItemList<?=$nKey?>"
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
                        <td class="otdListItem<?=$aDataTableVal['FTPdtCode'];?> xCNPIBeHideMQSS">
                            <label class="fancy-checkbox text-center">
                                <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPRBSelectMulDel(this)">
                                <span class="ospListItem">&nbsp;</span>
                            </label>
                        </td>
                        <td id="otdPDT<?=$aDataTableVal['FTPdtCode'];?>"><?=$aDataTableVal['FTPdtCode'];?></td>
                        <td id="otdPDTName<?=$aDataTableVal['FTPdtCode'];?>"><?=$aDataTableVal['FTXtdPdtName'];?></td>
                        <td><?=$aDataTableVal['FTPunName'];?></td>
                        <td id="otdFactor<?=$aDataTableVal['FTPdtCode'];?>" class='text-right'><?= number_format($aDataTableVal['FCXtdFactor'],2);?></td>
                        <td><?=$aDataTableVal['FTXtdBarCode'];?></td>
                        <td id="otdStkQty<?=$aDataTableVal['FTPdtCode'];?>" class='text-right'><?= number_format($aDataTableVal['FCStkQty'],2);?></td>
                        <!-- <td id="otdPdtQtyOrdBuy<?=$aDataTableVal['FTPdtCode'];?>" class='text-right'><?= number_format($aDataTableVal['FCPdtQtyOrdBuy'],2);?></td> -->
                        <td id="otdPdtQtySugges<?=$aDataTableVal['FTPdtCode'];?>" class='text-right'><?= number_format($aDataTableVal['FCXtdSetPrice'],2);?></td>
                        <td class="otdQty">
                            <div class="xWEditInLine<?=$nKey?>">
                                <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],2));?>" autocomplete="off">
                            </div>
                        </td>
                        <td nowrap="" class="text-center xCNPIBeHideMQSS">
                            <label class="xCNTextLink">
                                <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPRBDelPdtInDTTempSingle(this)">
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
    <div id="odvPRBModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPRBMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/purchaseorder/purchaseorder','tPRBMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtPRBConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtPRBCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก');?></button>
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
<?php  //include("script/jDeliveryOrderAdd.php");?>
<?php  include("script/jPurchasebranchPdtAdvTableData.php");?>

<script>

    $( document ).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();
        if($('#ohdPRBStaApv').val()==1 && $('#ohdPRBStaDoc').val()==1){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $("#othCheckboxHide").hide();
            $(".xCNPIBeHideMQSS").hide();
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPRBBrowseCustomer').attr('disabled',true);
            $('.otdListItem').hide();
        }else if($('#ohdPRBStaDoc').val()==3){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $("#othCheckboxHide").hide();
            $(".xCNPIBeHideMQSS").hide();
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPRBBrowseCustomer').attr('disabled',true);
            $('.otdListItem').hide();
        }

        JSxPRBCountPdtItems()

    });

    //mos
    // Next Func จาก Browse PDT Center
    function FSvPRBNextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            if(typeof(aPackData[i]['packData']['Sugges']) != "undefined" && aPackData[i]['packData']['Sugges'] !== null){
                var aNewPackData = JSON.stringify(aPackData[i]);
                var aNewPackData = "["+aNewPackData+"]";
                FSvPRBAddPdtIntoDocDTTemp(aNewPackData);         // Append HMTL
                FSvPRBAddBarcodeIntoDocDTTemp(aNewPackData);     // Insert Database
            }else{
                $.ajax({
                    type: "POST",
                    url: "docPRBCheckAutoPdtInDTDocTempAddPlus",
                    data: {
                                'aProduct'       : aPackData[i],
                                'tBchCode'       : $('#oetPRBFrmBchCode').val(),
                                'tWahCode'       : $('#oetPRBFrmWahCodeShip').val()
                            },
                    cache: false,
                    // timeout: 0,
                    async: false,
                    success: function (oResult){
                        var aResult =  JSON.parse(oResult);
                        if(aResult.rtCode == '1'){
                        aNewData = [];
                        aNewData.push(aResult.raItems);
                        var aNewReturn  = JSON.stringify(aNewData);
                        FSvPRBAddPdtIntoDocDTTemp(aNewReturn);
                        FSvPRBAddBarcodeIntoDocDTTemp(aNewReturn);
                        }
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }
    }
    function FSvPRBCheckPdtIntoDocDTTemp(ptPdtData) {
      var aPackData = JSON.parse(ptPdtData);
      var nStkQty = "";
      var nPdtQtyOrdBuy = "";
      var nPdtQtySugges = "";
      var nPdtCode = aPackData[0]['pnPdtCode'];
      $.ajax({
          type    : "POST",
          url     : "docPRBCheckPdtInDTDocTemp",
          data    : {
              'nPdtCode'        : aPackData[0]['pnPdtCode'],
          },
          catch   : false,
          timeout : 0,
          success : function (oResult){
            var aData = JSON.parse(oResult);
            if (aData['rtCode']=="1") {
              if (aData['raItems'][0]['FCStkQty']==null) {
                nStkQty = 0;
              }else {
                nStkQty = aData['raItems'][0]['FCStkQty'];
              }
              nPdtQtyOrdBuy = aData['raItems'][0]['FCPdtQtyOrdBuy'];
              nPdtQtySugges = aData['raItems'][0]['FCPdtQtySugges'];
            }else {
              nStkQty= 0;
              nPdtQtyOrdBuy= 0;
              nPdtQtySugges= 0;
            }
            $("#otdStkQty"+nPdtCode+"").html(nStkQty);
            $("#otdPdtQtyOrdBuy"+nPdtCode+"").html(nPdtQtySugges);
            $("#otdPdtQtySugges"+nPdtCode+"").html(nPdtQtyOrdBuy);
          },
          error   : function (jqXHR, textStatus, errorThrown) { }
      });
    }

    // Merge Product
    function JSxPRBMergeTable() {
        var aArr = [];
        $('.xWPdtItem ').each(function (indexInArray, valueOfElement) {
            aArr.push($(this).data("pdtcode")); 
        });

        let aObjPdt={}
        for(var i=0;i<aArr.length;i++){
            aObjPdt[aArr[i]]=aObjPdt[aArr[i]]!=null ?aObjPdt[aArr[i]]+1:1 
        }
        var tChkrow = '';
        $.each( aObjPdt, function( key, value ) {
            $('.xWPdtItem ').each(function (indexInArray, valueOfElement) {
                if($(this).data("pdtcode") != tChkrow){
                    $(this).find( ".otdListItem"+key ).removeClass("XCNHide");
                    $(this).find( "#otdPDT"+key ).removeClass("XCNHide");
                    $(this).find( "#otdPDTName"+key ).removeClass("XCNHide");
                    $(this).find( "#otdPdtQtyOrdBuy"+key ).removeClass("XCNHide");
                    $(this).find( "#otdStkQty"+key ).removeClass("XCNHide");

                    $(".otdListItem"+key).attr('rowspan', value);
                    $("#otdPDT"+key).attr('rowspan', value);
                    $("#otdPDTName"+key).attr('rowspan', value);
                    $("#otdPdtQtyOrdBuy"+key).attr('rowspan', value);
                    $("#otdStkQty"+key).attr('rowspan', value);
                }else{
                    $(this).find( ".otdListItem"+key ).addClass("XCNHide");
                    $(this).find( "#otdPDT"+key ).addClass("XCNHide");
                    $(this).find( "#otdPDTName"+key ).addClass("XCNHide");
                    $(this).find( "#otdPdtQtyOrdBuy"+key ).addClass("XCNHide");
                    $(this).find( "#otdStkQty"+key ).addClass("XCNHide");
                }
                tChkrow = $(this).data("pdtcode");
            });
        });
    }
    // Append PDT
    function FSvPRBAddPdtIntoDocDTTemp(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);

        // var tCheckIteminTableClass = $('#otbPRBDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nPRBODecimalShow = $('#ohdPRBODecimalShow').val();
        var tCheckIteminTable = $('.xWPdtItem').length;
        if(tCheckIteminTable==0){
            $('#otbPRBDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            // var nKey    = parseInt($('#otbPRBDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
            var max = 0;
            $('#otbPRBDocPdtAdvTableList tr').each(function (indexInArray, valueOfElement) { 
                var nChaeckNumber = parseInt($(this).attr('data-seqno'));
                if(nChaeckNumber > max){
                    max = nChaeckNumber;
                }
            });
            var nKey    = max + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        // var nKey    = parseInt($('#otbPRBDocPdtAdvTableList tbody tr').length) + parseInt(1);

        for(var i=0; i<nLen; i++){

            var oData           = aPackData[i];
            var oResult         = oData.packData;
            var oInserData      = 0;

            if(oResult.FCStkQty == null){
                oResult.FCStkQty = 0;
            }else{
                oResult.FCStkQty    = (oResult.FCStkQty == .0 || oResult.FCStkQty === undefined ? 0 : oResult.FCStkQty);
            }

            oResult.NetAfHD     = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.Net == '' || oResult.Net === undefined ? oResult.Price : oResult.Net);
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);

            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            var nrowspan        = oResult.rowspan;          //ชื่อหน่วยสินค้า
            var nQty            = parseInt(oResult.Qty);             //จำนวน
            var Sugges          = parseInt(oResult.Sugges);             //จำนวนแนะนำ
            var FCStkQty        = parseInt(oResult.FCStkQty);             //จำนวนแนะนำ
            var FCPdtQtyOrdBuy  = parseInt(oResult.FCPdtQtyOrdBuy);             //จำนวนแนะนำ
            var FCFactor        = parseInt(oResult.Factor);             //จำนวนแนะนำ

            if(typeof oResult.Sugges !== "undefined" && oResult.Sugges){
                Sugges          = parseInt(oResult.Sugges);   
            }else if(typeof oResult.Sugges2 !== "undefined" && oResult.Sugges2){
                Sugges          = parseInt(oResult.Sugges2);   
            }


            var tDuplicate = $('#otbPRBDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmPRBFrmInfoOthReAddPdt').val();
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
                var tMergeDuplicate = $('#otbPRBDocPdtAdvTableList tbody tr').hasClass('xWPdtMerge'+tProductCode);
                var oQty = '<div class="xWEditInLine'+nKey+'">';
                    oQty += '<input ';
                    oQty += 'type="text" ';
                    oQty += 'class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' xWShowInLine'+nKey+' "';
                    oQty += 'id="ohdQty'+nKey+'" ';
                    oQty += 'name="ohdQty'+nKey+'" ';
                    oQty += 'data-seq='+nKey+' ';
                    oQty += 'maxlength="10" ';
                    oQty += 'value="'+Sugges+'"';
                    oQty += 'autocomplete="off" >';
                    oQty += '</div>';

                tHTML += '<tr class="otr'+tProductCode+''+tBarCode+' xWPdtItem xWPdtMerge'+tProductCode+' xWPdtMergeSeq'+tProductCode+''+nKey+' xWPdtItemList'+nKey+'"';
                tHTML += '  data-key="'+nKey+'"';
                tHTML += '  data-pdtcode="'+tProductCode+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-qty="'+nQty+'"';

                tHTML += '>';
    
                tHTML += '<td class="otdListItem'+tProductCode+'">';
                tHTML += '  <label class="fancy-checkbox text-center">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPRBSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td id="otdPDT'+tProductCode+'">'+tProductCode+'</td>';
                tHTML += '<td id="otdPDTName'+tProductCode+'">'+tProductName+'</td>';


                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="text-right" >'+FCFactor+'</td>';
                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td class="text-right" id="otdStkQty'+tProductCode+'">'+FCStkQty+'</td>';
                // tHTML += '<td class="text-right" id="otdPdtQtyOrdBuy'+tProductCode+'">'+FCPdtQtyOrdBuy+'</td>';
                tHTML += '<td class="text-right" id="otdPdtQtySugges'+tProductCode+'">'+Sugges+'</td>';
                tHTML += '<td class="otdQty">'+oQty+'</td>';
                if($('#ohdPOSTaImport').val()==1){
                tHTML += '<td class="xPRBImportDT"> </td>';
                }
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPRBDelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        if(tMergeDuplicate == true){
            // alert('If Statment');
            $(".xWPdtMerge"+tProductCode).each( function (indexInArray, valueOfElement) { 
                tInserData = $(this).data('seqno');
            });
            $(tHTML).insertAfter($(".xWPdtMergeSeq"+tProductCode+tInserData).closest('tr'));
            // $.ajax({
            //     type    : "POST",
            //     url     : "docPRBEditGroupSugges",
            //     data    : {
            //         'nPdtCode'        : tProductCode,
            //     },
            //     catch   : false,
            //     timeout : 0,
            //     success : function (oResult){
            //         var aData = JSON.parse(oResult);
            //         if (aData['rtCode']=="1") {
            //         if (aData['raItems'][0]['FCStkQty']==null) {
            //             nStkQty = 0;
            //         }else {
            //             nStkQty = aData['raItems'][0]['FCStkQty'];
            //         }
            //         nPdtQtyOrdBuy = aData['raItems'][0]['FCPdtQtyOrdBuy'];
            //         nPdtQtySugges = aData['raItems'][0]['FCPdtQtySugges'];
            //         }else {
            //         nStkQty= 0;
            //         nPdtQtyOrdBuy= 0;
            //         nPdtQtySugges= 0;
            //         }
            //         $("#otdStkQty"+nPdtCode+"").html(nStkQty);
            //         $("#otdPdtQtyOrdBuy"+nPdtCode+"").html(nPdtQtySugges);
            //         $("#otdPdtQtySugges"+nPdtCode+"").html(nPdtQtyOrdBuy);
            //     },
            //     error   : function (jqXHR, textStatus, errorThrown) { }
            // });

        }else{
            $('#otbPRBDocPdtAdvTableList tbody').append(tHTML);
        }



        // $(".otdListItem"+tProductCode).attr('rowspan', nrowspan);
        // $("#otdPDT"+tProductCode).attr('rowspan', nrowspan);
        JSxPRBMergeTable();
        // FSvPRBCheckPdtIntoDocDTTemp(ptPdtData);
        JSxPRBCountPdtItems();
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();
    }
    // Check All
    $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvPRBMngDelPdtInTableDT #oliDOBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvPRBMngDelPdtInTableDT #oliDOBtnDeleteMulti").addClass("disabled");
        }
    });

    function FSxPRBSelectMulDel(ptElm){
    // $('#otbPRBDocPdtAdvTableList #odvTBodyPRBPdtAdvTableList .ocbListItem').click(function(){
        let tPRBDocNo    = $('#oetPRBDocNo').val();
        let tPRBSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPRBPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tPRBBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        var nPRBODecimalShow = $('#ohdPRBODecimalShow').val();
        // let tPRBPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("PRB_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("PRB_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tPRBcNo'    : tPRBDocNo,
                'tSeqNo'    : tPRBSeqNo,
                'tPdtCode'  : tPRBPdtCode,
                'tBarCode'  : tPRBBarCode,
                // 'tPunCode'  : tPRBPunCode,
            });
            localStorage.setItem("PRB_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxPRBTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStPRBFindObjectByKey(aArrayConvert[0],'tSeqNo',tPRBSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tPRBcNo'    : tPRBDocNo,
                    'tSeqNo'    : tPRBSeqNo,
                    'tPdtCode'  : tPRBPdtCode,
                    'tBarCode'  : tPRBBarCode,
                    // 'tPunCode'  : tPRBPunCode,
                });
                localStorage.setItem("PRB_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxPRBTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("PRB_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tPRBSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PRB_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxPRBTextInModalDelPdtDtTemp();
            }
        }
        JSxPRBShowButtonDelMutiDtTemp();
        // });
    }

    function JSxAddScollBarInTablePdt(){
        $('#otbPRBDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbPRBDocPdtAdvTableList >tbody >tr').length;
            if(rowCount >= 2){
                //$('#otbPRBDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');

            }

        if(rowCount >= 7){
            $('.xWShowInLine' + rowCount).focus();

            $('html, body').animate({
                scrollTop: ($("#oetPRBInsertBarcode").offset().top)-80
            }, 0);
        }

        if($('#oetPRBFrmCstCode').val() != ''){
            $('#oetPRBInsertBarcode').focus();
        }
    }

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
        var tPRBDocNo        = $("#oetPRBDocNo").val();
        var tPRBBchCode      = $("#oetPRBFrmBchCode").val();
        if(pnSeq != undefined){
            $.ajax({
                type    : "POST",
                url     : "docPRBEditPdtInDTDocTemp",
                data    : {
                    'tPRBBchCode'        : tPRBBchCode,
                    'tPRBDocNo'          : tPRBDocNo,
                    'nPRBSeqNo'          : pnSeq,
                    'nQty'              : nQty
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
    });

    function FSxPRBSelectAll(){
    if($('.ocbListItemAll').is(":checked")){
        $('.ocbListItem').each(function (e) {
            if(!$(this).is(":checked")){
                $(this).on( "click", FSxPRBSelectMulDel(this) );
            }
    });
    }else{
        $('.ocbListItem').each(function (e) {
            if($(this).is(":checked")){
                $(this).on( "click", FSxPRBSelectMulDel(this) );
            }
    });
    }

}

</script>
