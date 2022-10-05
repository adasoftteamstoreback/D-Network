<div class="">
    <div class="table-responsive xCNTablescroll">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPunCode;?>">
        <table id="otbTbxDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNHideWhenCancelOrApprove"><?php echo language('document/document/document','tDocChoose')?></th>
                    <th nowrap><?php echo language('document/document/document','tDocNumber')?></th>
                    <!-- <?php foreach($aColumnShow as $HeaderColKey => $HeaderColVal):?>
                        <th  title="<?php echo iconv_substr($HeaderColVal->FTShwNameUsr, 0,30,"UTF-8");?>">
                            <?php echo iconv_substr($HeaderColVal->FTShwNameUsr, 0,30, "UTF-8");?>
                        </th>
                        <?php if($HeaderColKey == '3'){ ?>
                            <th ><?= language('document/purchaseorder/purchaseorder', 'สถานะสต็อค')?></th>
                        <?php } ?>
                    <?php endforeach;?> -->
                    <th nowrap><?php echo language('document/document/document','รหัสสินค้า')?></th>
                    <th nowrap><?php echo language('document/document/document','ชื่อสินค้า')?></th>
                    <th nowrap><?php echo language('document/document/document','บาร์โค้ด')?></th>
                    <th nowrap><?php echo language('document/document/document','หน่วยสินค้า')?></th>
                    <th nowrap><?php echo language('document/document/document','สถานะสต็อค')?></th>
                    <th nowrap><?php echo language('document/document/document','จำนวน')?></th>
                    <th nowrap class="xCNTextBold xPIImportDT" style="display:none"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRemark')?></th>
                    <th nowrap class="xCNHideWhenCancelOrApprove"><?php echo language('document/document/document','tDocDelete');?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyPIPdtAdvTableList">
                <?php
                    if($aDataDT['rtCode'] == 1):
                    foreach($aDataDT['raItems'] as $DataTableKey => $aDataTableVal):
                        $nKey = $aDataTableVal['FNXtdSeqNo']; ?>
                        <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                            data-index="<?php echo $aDataTableVal['rtRowID'];?>"
                            data-docno="<?php echo $aDataTableVal['FTXthDocNo'];?>"
                            data-pdtcode="<?php echo $aDataTableVal['FTPdtCode'];?>"
                            data-pdtname="<?php echo $aDataTableVal['FTXtdPdtName'];?>"
                            data-puncode="<?php echo $aDataTableVal['FTPunCode'];?>"
                            data-qty="<?php echo $aDataTableVal['FCXtdQty'];?>"
                            data-setprice="<?php echo number_format($aDataTableVal['FCXtdSetPrice'],2);?>"
                            data-stadis=""
                            data-netafhd="<?php echo number_format(0,2);?>"
                            data-alwvat="<?php echo $aDataTableVal['FTXtdVatType'];?>"
                            data-vat="<?php echo $aDataTableVal['FCXtdVatRate']?>"
                            data-net="<?php echo number_format($aDataTableVal['FCXtdNet'],2);?>"
                            data-key="<?=$nKey?>"
                            data-seqno="<?=$nKey?>"
                            style="background-color: rgb(255, 255, 255);"
                        >
                            <td nowrap align="center" class="xCNHideWhenCancelOrApprove">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxTBXSelectMulDel(this)">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </td>
                            <td nowrap align="center"><?=$aDataTableVal['rtRowID']?></td>
                            <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTXtdPdtName'];?></td>
                            <td nowrap><?=$aDataTableVal['FTXtdBarCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPunName'];?></td>

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

                            <td class="otdQty" align="right">
                              <?php if ($aDataTableVal['FTXtdRmk']=='1') {
                                $tStatusUpdateInput = "readonly";
                              }else {
                                $tStatusUpdateInput = "";
                              }  ?>
                                <?php if(@$tXthStaDoc == 3 || @$tXthStaApv == 1){ ?>
                                    <div class="xWEditInLine">
                                        <input type="text" class="form-control" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" readonly >
                                    </div>
                                <?php }else { ?>
                                    <div class="xWEditInLine<?=$nKey?>">
                                        <input <?php //echo $tStatusUpdateInput ?> type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" maxlength="10" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" autocomplete="off">
                                    </div>
                                <?php } ?>
                            </td>

                            <td nowrap="" class="text-center xCNHideWhenCancelOrApprove">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">
                                </label>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                     else:
                    ?>
                    <tr><td class="text-center xCNTextDetail2 xWTbxTextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
<?php if($aDataDT['rnAllPage'] > 1) : ?>
    <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataDT['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataDT['rnCurrentPage']?> / <?php echo $aDataDT['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPagePIPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPIPDTDocDTTempClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataDT['rnAllPage'],$nPage+2)); $i++){?>
                <?php
                    if($nPage == $i){
                        $tActive = 'active';
                        $tDisPageNumber = 'disabled';
                    }else{
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvPIPDTDocDTTempClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataDT['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPIPDTDocDTTempClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div> -->
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
                    <button id="obtPIConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtPICancelDeleteDTDis" type="button" class="btn xCNBTNDefult"  data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ================================================================================================================================= -->

<!--ทำรายการส่วนลด-->
<div id="odvModalDiscount" class="modal fade" tabindex="-1" role="dialog" style="max-width: 1500px; margin: 1.75rem auto; width: 85%;">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content">
            <!--ส่วนหัว-->
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block">ส่วนลด/ชาร์จ ท้ายบิล</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <!--รายละเอียด-->
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
            <!--ปุ่มยืนยันหรือยกเลิก-->
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main','tCancel');?></button>
                <button onclick="JSxDisChgSave()" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main','tCMNOK');?></button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php  include("script/jProducttransferbranchPdtAdvTableData.php");?>

<script>

    $( document ).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();
        if($('#ohdPIStaApv').val()==1){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPIBrowseSupplier').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
        }

        if('<?=$tXthStaDoc?>' == 3 || '<?=$tXthStaApv?>' == 1){
            $('.xCNHideWhenCancelOrApprove').hide();
        }
    });

    // Next Func จาก Browse PDT Center
    function  FSvPINextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvTBXAddPdtIntoDocDTTempScan(aNewPackData);   // Append HMTL
            FSvTBXPDTAddPdtIntoTableDT(aNewPackData);   // Insert Database

            if((i+1)==aPackData.length){
                var oOptionForFashion = {
                    'bListItemAll'  : false,
                    'tSpcControl'  : 0,
                    'tNextFunc' : 'FSvTBXPDTAddPdtIntoTableDT'
                }
                JSxCheckProductSerialandFashion(aPackData,oOptionForFashion,'insert');
            }

        }


    }

    // Append PDT
    function FSvTBXAddPdtIntoDocDTTempScan(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        var tCheckIteminTableClass = $('#otbTbxDocPdtAdvTableList tbody tr td').hasClass('xWTbxTextNotfoundDataPdtTable');

        if(tCheckIteminTableClass == true){
            $('#otbTbxDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbTbxDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
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

            var tPdtforSys      = oResult.PDTSpc;          //ประเภทสินค้า
            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tPunCode        = oResult.PUNCode;          //รหัสหน่วย
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            var nPrice          = (parseFloat(accounting.unformat(oResult.Price))).toFixed(2);                                      //ราคา
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //อนุญาตคำนวณลด
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //อนุญาตคำนวณภาษี
            var nVat            = (parseFloat($('#ohdPIFrmSplVatRate').val())).toFixed(2);                               //ภาษีจากผู้จำหน่าย
            var nQty            = parseInt(oResult.Qty);             //จำนวน
            var nNetAfHD        = (parseFloat(oResult.NetAfHD)).toFixed(2);
            var cNet            = (parseFloat(oResult.Net)).toFixed(2);
            var tDisChgTxt      = oResult.tDisChgTxt;

            var tDuplicate = $('#otbTbxDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmTBOptionAddPdt').val();

            if(tDuplicate == true && InfoOthReAddPdt==1){
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld     = $('.otr'+tProductCode+tBarCode).find('.xCNQty').val();
                var nNewValue   = parseInt(nValOld) + parseInt(1);
                $('.otr'+tProductCode+tBarCode).find('.xCNQty').val(nNewValue);
                $('.otr'+tProductCode+tBarCode).find('.xCNQtyFhn').text(nNewValue.toFixed(2));

                var nGrandOld   = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').val();
                var nGrand      = parseInt(nNewValue) * parseFloat(nGrandOld);
                var nSeqOld     = $('.otr'+tProductCode+tBarCode).find('.xCNPrice').attr('data-seq');
                $('#ospGrandTotal'+nSeqOld).text(numberWithCommas(nGrand.toFixed(2)));
            }else{
                //ถ้าสินค้าไม่ซ้ำ ก็บวกเพิ่มต่อเลย
                if(nAlwDiscount == 1){ //อนุญาตลด
                    var oAlwDis = ' <div class="xWPIDisChgDTForm">';
                    oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPICallModalDisChagDT(this)" type="button">+</button>'; //JCNvDisChgCallModalDT(this)
                    oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp'+nKey+'">'+tDisChgTxt+'</label>';
                    oAlwDis += '</div>';
                }else{
                    var oAlwDis = 'ไม่อนุญาตให้ส่วนลด';
                }

                //จำนวน
                if(tPdtforSys!='FH'){

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

                }else{
                        var tClassName = 'xCNTextLink';
                        var tIconEdit = " <img class='xCNIconTable' src='<?=base_url('application/modules/common/assets/images/icons/edit.png')?>' title='Edit'>";
                        var  aOptionFashion   = {
                            'tDocumentBranch' : $('#ohdTbxBchCode').val(),
                            'tDocumentNumber' : $('#oetXthDocNo').val(),
                            'tDocumentProduct' : tProductCode,
                            'tDocumentDocKey' : 'TCNTPdtTbxHD',
                            'nDTSeq' : nKey,
                            'tDTBarCode' : tBarCode,
                            'tDTPunCode' : tPunCode,
                            'tNextFunc' : 'FSvTBXPDTEditPdtIntoTableDT',
                            'tSpcControl' : 0 ,
                            'tStaEdit' : 1
                    }
               var aNewPackData =  JSON.stringify(aOptionFashion);
               var aNewPackData = 'JSxUpdateProductSerialandFashion('+aNewPackData+')';
               var  oQty = '<div class="xWEditInLine'+nKey+'">';
                    oQty +='<label class="'+tClassName+' xCNPdtFont xWShowInLine"  onclick='+aNewPackData+' ><span class="xCNQtyFhn">'+nQty.toFixed(2)+'</span> '+tIconEdit+'</label>';
                    oQty += '<input  style="display:none" ';
                    oQty += 'type="text" ';
                    oQty += 'class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine'+nKey+' xWShowInLine'+nKey+' "';
                    oQty += 'id="ohdQty'+nKey+'" ';
                    oQty += 'name="ohdQty'+nKey+'" ';
                    oQty += 'data-seq='+nKey+' ';
                    oQty += 'maxlength="10" ';
                    oQty += 'value="'+nQty+'"';
                    oQty += 'autocomplete="off" >';
                    oQty += '</div>';
              }

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

                tHTML += '>';
                tHTML += '<td align="center">';
                tHTML += '  <label class="fancy-checkbox">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxTBXSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td align="center">'+nKey+'</td>';
                tHTML += '<td>'+tProductCode+'</td>';
                tHTML += '<td>'+tProductName+'</td>';
                tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="text-center"><span class="xCNTextWaitConfirm">รอยืนยัน</span></td>';
                tHTML += '<td class="otdQty text-right" >'+oQty+'</td>';
                tHTML += '';
                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnRemoveDTRow(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;

            }
        }

        //สร้างตาราง
        $('#otbTbxDocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxEditQtyAndPrice();

    }

    function FSxTBXSelectMulDel(ptElm){

        let tTbxDocNo    = $('#oetXthDocNo').val();
        let tTbxSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tTbxPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tTbxBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');
        // alert(1111);
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("TBX_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("TBX_LocalItemDataDelDtTemp"))];
        // console.log(aArrayConvert);
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tTbxDocNo,
                'tSeqNo'    : tTbxSeqNo,
                'tPdtCode'  : tTbxPdtCode,
                'tBarCode'  : tTbxBarCode,
            });
            localStorage.setItem("TBX_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxTBXTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStTBXFindObjectByKey(aArrayConvert[0],'tSeqNo',tTbxSeqNo);
            // console.log(tTbxSeqNo);
            // console.log(aReturnRepeat);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tTbxDocNo,
                    'tSeqNo'    : tTbxSeqNo,
                    'tPdtCode'  : tTbxPdtCode,
                    'tBarCode'  : tTbxBarCode,
                    // 'tPunCode'  : tSOPunCode,
                });
                localStorage.setItem("TBX_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxTBXTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("TBX_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tTbxSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("TBX_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxTBXTextInModalDelPdtDtTemp();
            }
        }
        JSxTBXShowButtonDelMutiDtTemp();
    }

    function JSxAddScollBarInTablePdt(){
        $('#otbTbxDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbTbxDocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbTbxDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');

        }
        // alert(rowCount);
        if(rowCount >= 7){
            // $('.xCNTablescroll').css('height','450px');
            $('.xWShowInLine' + rowCount).focus();
            // $('#otbTbxDocPdtAdvTableList').height()
            $('.xCNTablescroll').scrollTop($('#otbTbxDocPdtAdvTableList').height());
            $('html, body').animate({
             scrollTop: ($("#oetTbxInsertBarcode").offset().top)-80
         }, 0);
        }
        $('#oetTbxInsertBarcode').focus();

    }

    //เเก้ไขจำนวน และ ราคา
    function JSxEditQtyAndPrice(){
        $('.xCNPdtEditInLine').click(function(){
            $(this).focus().select();
        });

        $('.xCNPdtEditInLine').off().on('change keyup',function(e){
            if(e.type === 'change' || e.keyCode === 13){
                var nSeq    = $(this).attr('data-seq');
                var nQty    = $(this).val();

                $.ajax({
                    type    : "POST",
                    url     : "TBXEditQtyInDT",
                    data    : {
                        'tDocNo'            : $('#oetXthDocNo').val(),
                        'nSeq'              : nSeq,
                        'nQty'              : nQty
                    },
                    catch   : false,
                    timeout : 0,
                    success : function (oResult){ console.log(oResult) },
                    error   : function (jqXHR, textStatus, errorThrown) { console.log(textStatus) }
                });
            }
        });
    }

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

</script>
