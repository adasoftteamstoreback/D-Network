<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive xCNTablescroll">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tPXPdtCode;?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tPXPunCode;?>">

        <table id="otbPXDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNTextBold xWHideColumOnApv text-center" style="width:5%;">
                        <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmCENCheckDeleteAll" id="ocmCENCheckDeleteAll" >
                            <span class="ospListItem"></span>
                        </label>
                    </th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','ลำดับ')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','รหัสสินค้า')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','ชื่อสินค้า')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','หน่วยสินค้า')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','จำนวน')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','ราคา/หน่วย')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','ส่วนลด')?></th>
                    <th nowrap><?php echo language('document/expenserecord/expenserecord','จำนวนเงิน')?></th>
                    <th class="xWHideColumOnApv" nowrap class="xCNPXBeHideMQSS"><?php echo language('document/expenserecord/expenserecord', 'tPXTBDelete');?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyPXPdtAdvTableList">
            <?php
                if($aDataDocDTTemp['rtCode'] == 1):
                    foreach($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal):
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
            ?>
                        <tr class="otr<?=$aDataTableVal['FTPdtCode'];?><?php echo $aDataTableVal['FTXtdBarCode'];?> xWPdtItem xWPdtItemList<?=$nKey?>"
                            data-index="<?php echo $aDataTableVal['rtRowID'];?>"
                            data-docno="<?php echo $aDataTableVal['FTXthDocNo'];?>"
                            data-pdtcode="<?php echo $aDataTableVal['FTPdtCode'];?>"
                            data-pdtname="<?php echo $aDataTableVal['FTXtdPdtName'];?>"
                            data-puncode="<?php echo $aDataTableVal['FTPunCode'];?>"
                            data-qty="<?php echo $aDataTableVal['FCXtdQty'];?>"
                            data-setprice="<?php echo number_format($aDataTableVal['FCXtdSetPrice'],2);?>"
                            data-stadis="<?php echo $aDataTableVal['FTXtdStaAlwDis']?>"
                            data-netafhd="<?php echo number_format($aDataTableVal['FCXtdNetAfHD'],2);?>"
                            data-alwvat="<?php echo $aDataTableVal['FTXtdVatType'];?>"
                            data-vat="<?php echo $aDataTableVal['FCXtdVatRate']?>"
                            data-net="<?php echo number_format($aDataTableVal['FCXtdNet'],2);?>"
                            data-key="<?=$nKey?>"
                            data-seqno="<?=$nKey?>"
                            style="background-color: rgb(255, 255, 255);"
                        >
                            <td nowrap class="xWHideColumOnApv text-center">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPXSelectMulDel(this)">
                                    <span class="ospListItem"></span>
                                </label>
                            </td>
                            <td nowrap class="text-center" nowrap><?=$aDataTableVal['rtRowID']?></td>
                            <td nowrap><?=$aDataTableVal['FTPdtCode'];?></td>
                            <td nowrap><?=$aDataTableVal['FTXtdPdtName'];?></td>
                            <td nowrap><?=$aDataTableVal['FTPunName'];?></td>
                            <td nowrap class="otdQty text-right">
                                <div class="xWEditInLine<?=$nKey?> xWEditPdtInLine">
                                    <input type="text" class="xCNQty form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> xWShowInLine<?=$nKey?> " id="ohdQty<?=$nKey?>" name="ohdQty<?=$nKey?>" data-seq="<?=$nKey?>" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?>" autocomplete="off" maxlength="18">
                                </div>
                                <div class="xWShowPdtInLine" style="display: none;"><?=str_replace(",","",number_format($aDataTableVal['FCXtdQty'],$nOptDecimalShow));?></div>
                            </td>
                            <td nowrap class="otdPrice text-right">
                                <div class="xWEditInLine<?=$nKey?> xWEditPdtInLine">
                                    <input type="text" class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?=$nKey?> " id="ohdPrice<?=$nKey?>" name="ohdPrice<?=$nKey?>" data-alwdis="<?=$aDataTableVal['FTXtdStaAlwDis'];?>" data-seq="<?=$nKey?>" value="<?=str_replace(",","",number_format($aDataTableVal['FCXtdSetPrice'],$nOptDecimalShow));?>" autocomplete="off" maxlength="18"> <!-- maxlength="10.00" -->
                                </div>
                                <div class="xWShowPdtInLine" style="display: none;"><?=str_replace(",","",number_format($aDataTableVal['FCXtdSetPrice'],$nOptDecimalShow));?></div>
                            </td>
                            <td nowrap>
                                <?php
                                    if($aDataTableVal['FTXtdStaAlwDis'] == 1):
                                ?>
                                    <div>
                                        <button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPXCallModalDisChagDT(this)" type="button">+</button>
                                        <?php
                                            if($aDataTableVal['FTXtdDisChgTxt'] != '' || $aDataTableVal['FTXtdDisChgTxt'] != null){ //ถ้ามีส่วนลด
                                                $tTextDisChg    = '';
                                                $aExplode       = explode(",",$aDataTableVal['FTXtdDisChgTxt']);
                                                for($i=0; $i<FCNnHSizeOf($aExplode); $i++){
                                                    if(strpos($aExplode[$i],'%')){
                                                        $tTextDisChg .= $aExplode[$i] . ',';
                                                    }else{
                                                        $tTextDisChg .= number_format($aExplode[$i],2) . ',';
                                                    }

                                                    //ถ้าเป็นตัวท้ายให้ลบ comma ออก
                                                    if($i == FCNnHSizeOf($aExplode) - 1 ){
                                                        $tTextDisChg = substr($tTextDisChg,0,-1);
                                                    }
                                                }
                                            }else{//ไม่มีส่วนลด
                                                $tTextDisChg = '';
                                            }
                                        ?>
                                        <div class="xWDisChgDTTmp" id="xWDisChgDTTmp<?=$nKey?>"><?=$tTextDisChg;?></div>
                                    </div>
                                <?php
                                    else:
                                        echo "ไม่อนุญาตให้ส่วนลด";
                                    endif;
                                ?>
                            </td>
                            <td nowrap class="text-right">
                                <span id="ospGrandTotal<?=$nKey?>"><?=number_format($aDataTableVal['FCXtdNet'],$nOptDecimalShow);?></span>
                                <span id="ospnetAfterHD<?=$nKey?>" style="display: none;"><?=number_format($aDataTableVal['FCXtdNetAfHD'],$nOptDecimalShow);?></span>
                            </td>
                            <td nowrap="" class="text-center xWHideColumOnApv">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPXDelPdtInDTTempSingle(this)">
                                </label>
                            </td>
                        </tr>
            <?php
                    endforeach;
                else:
            ?>
                    <tr><td class="text-center xCNTextDetail2 xWPXTextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
            <?php 
                endif;
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php if($aDataDocDTTemp['rnAllPage'] > 1) : ?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataDocDTTemp['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataDocDTTemp['rnCurrentPage']?> / <?php echo $aDataDocDTTemp['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPagePXPdt btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPXPDTDocDTTempClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvPXPDTDocDTTempClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataDocDTTemp['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPXPDTDocDTTempClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
<?php endif;?>

<!-- ============================================ Modal Confirm Delete Documet Detail Dis ============================================ -->
    <div id="odvPXModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?= language('common/main/main', 'tPXMsgNotificationChangeData') ?></label>
                </div>
                <div class="modal-body">
                    <label><?php echo language('document/expenserecord/expenserecord','tPXMsgTextNotificationChangeData');?></label>
                </div>
                <div class="modal-footer">
                    <button id="obtPXConfirmDeleteDTDis" type="button"  class="btn xCNBTNPrimery"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button id="obtPXCancelDeleteDTDis" type="button" class="btn xCNBTNDefult"  data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก');?></button>
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
                                style="width: 100%;"><?=language('document/expenserecord/expenserecord','tPXMDAddEditDisChg') ?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive" style="min-height: 300px; max-height: 300px; overflow-y: scroll;">
                            <table id="otbDisChgDataDocHDList" class="table">
                                <thead>
                                    <tr class="xCNCenter">
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXsequence')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXBeforereducing')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXValuereducingcharging')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXAfterReducing')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXType')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXDiscountcharge')?></th>
                                        <th class="xCNTextBold"><?=language('document/expenserecord/expenserecord','tPXTBDelete')?></th>
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
<?php  include("script/jExpenseRecordPdtAdvTableData.php");?>

<script>

    $( document ).ready(function() {

        var tRemartFromPageAdd = $('#ohdPXRemark').val();
        $('#otaPXFrmInfoOthRmk').val(tRemartFromPageAdd);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
        var tStaApv = $('#ohdPXStaApv').val();
        var tStaDoc = $('#ohdPXStaDoc').val();
        if( tStaApv == '1' || tStaDoc == '3' ){
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $(".xCNIconTable").addClass("xCNDocDisabled");
            $(".xCNIconTable").attr("onclick", "").unbind("click");
            $('.xCNPdtEditInLine').attr('readonly',true);
            $('#obtPXBrowseSupplier').attr('disabled',true);
            $('.ocbListItem').attr('disabled',true);
            $('.xWHideColumOnApv').hide();

            $('.xWEditPdtInLine').hide();
            $('.xWShowPdtInLine').show();
        }

    });

    // Next Func จาก Browse PDT Center
    function  FSvPXNextFuncB4SelPDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        console.log('Event Scan',aPackData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            FSvPXAddPdtIntoDocDTTempScan(aNewPackData);   // Append HMTL
            FSvPXAddBarcodeIntoDocDTTemp(aNewPackData);   // Insert Database

            if((i+1)==aPackData.length){
                var oOptionForFashion = {
                    'bListItemAll'  : false,
                    'tSpcControl'  : 0,
                    'tNextFunc' : 'FSvPXPDTAddPdtIntoTableDT'
                }
                JSxCheckProductSerialandFashion(aPackData,oOptionForFashion,'insert');
            }

        }

         
    }



// Create By: Nattakit(Nale) 24/05/2021
function FSvPXPDTAddPdtIntoTableDT_PDTSerialorFashion(ptPdtDataFhn){
    console.log(ptPdtDataFhn);
    var aData = JSON.parse(ptPdtDataFhn);
    var ptXthDocNoSend = "";
    if ($("#ohdPXRoute").val() != "docPXEventAdd") {
        ptXthDocNoSend = $("#oetPXDocNo").val();
    }
    var tPXVATInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
    var tPXOptionAddPdt = $('#ocmPXFrmInfoOthReAddPdt').val();
    $.ajax({
        type: "POST",
        url: "docPXEventAddPdtIntoDTFhnTemp",
        data: {
            'tPXDocNo'         : ptXthDocNoSend,
            'tPXPdtDataFhn'    : ptPdtDataFhn,
            'tPXWBCH'          : $('#oetPXFrmBchCode').val(),
            'tPXType'          : 'PDT',
            'nPXVATInOrEx'     : tPXVATInOrEx,
            'nEvent'           : 1,
            'tPXOptionAddPdt'  : tPXOptionAddPdt

        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            if( aData['nStaLastSeq'] == '1' ){ //ถ้าเป็นสินค้าแฟชั่นตัวสุดท้ายให้โหลด Table 
                $('#ohdPXStaWaitScanOn').val(1);
                 JSvPXLoadPdtDataTableHtml(1);
            }
        },  
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

}



// Create By: Nattakit(Nale) 24/05/2021
function FSvPXPDTEditPdtIntoTableDT_PDTSerialorFashion(ptPdtDataFhn){
    console.log(ptPdtDataFhn);
    var aData = JSON.parse(ptPdtDataFhn);

    var ptXthDocNoSend = "";
    if ($("#ohdPXRoute").val() != "docPXEventAdd") {
        ptXthDocNoSend = $("#oetPXDocNo").val();
    }
    var tPXVATInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
    var tPXOptionAddPdt = $('#ocmPXFrmInfoOthReAddPdt').val();
    $.ajax({
        type: "POST",
        url: "docPXEventAddPdtIntoDTFhnTemp",
        data: {
            'tPXDocNo'         : ptXthDocNoSend,
            'tPXPdtDataFhn'    : ptPdtDataFhn,
            'tPXWBCH'          : $('#oetPXFrmBchCode').val(),
            'tPXType'          : 'PDT',
            'nPXVATInOrEx'     : tPXVATInOrEx,
            'nEvent'           : 2,
            'tPXOptionAddPdt'  : tPXOptionAddPdt,

        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
       
            if( aData['nStaLastSeq'] == '1' ){ //ถ้าเป็นสินค้าแฟชั่นตัวสุดท้ายให้โหลด Table 
                JSvPXLoadPdtDataTableHtml(1);
            }
        },  
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

}


    // Append PDT
    function FSvPXAddPdtIntoDocDTTempScan(ptPdtData){
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        var tCheckIteminTableClass = $('#otbPXDocPdtAdvTableList tbody tr td').hasClass('xWPXTextNotfoundDataPdtTable');

        if(tCheckIteminTableClass == true){
            $('#otbPXDocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbPXDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
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
            var nPrice          = (parseFloat(accounting.unformat(oResult.Price))).toFixed(2);                                            //ราคา
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //อนุญาตคำนวณลด
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //อนุญาตคำนวณภาษี
            var nVat            = (parseFloat($('#ohdPXFrmSplVatRate').val())).toFixed(2);                               //ภาษีจากผู้จำหน่าย
            var nQty            = parseInt(oResult.Qty);             //จำนวน
            var nNetAfHD        = (parseFloat(oResult.NetAfHD)).toFixed(2);
            var cNet            = (parseFloat(oResult.Net)).toFixed(2);
            var tDisChgTxt      = oResult.tDisChgTxt;

            var tDuplicate = $('#otbPXDocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmPXFrmInfoOthReAddPdt').val();

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
                    var oAlwDis = ' <div class="xWPXDisChgDTForm">';
                    oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPXCallModalDisChagDT(this)" type="button">+</button>'; //JCNvDisChgCallModalDT(this)
                    oAlwDis += '<div class="xWDisChgDTTmp" id="xWDisChgDTTmp'+nKey+'">'+tDisChgTxt+'</div>';
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
                            'tDocumentBranch' : $('#oetPXFrmBchCode').val(),
                            'tDocumentNumber' : $('#oetPXDocNo').val(),
                            'tDocumentProduct' : tProductCode,
                            'tDocumentDocKey' : 'TAPTPxHD',
                            'nDTSeq' : nKey,
                            'tDTBarCode' : tBarCode,
                            'tDTPunCode' : tPunCode,
                            'tNextFunc' : 'FSvPXPDTEditPdtIntoTableDT',
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
                tHTML += '  data-key="'+nKey+'"';
                tHTML += '  data-alwvat="'+nAlwVat+'"';
                tHTML += '  data-vat="'+nVat+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-pdtcode="'+tProductCode+'"';
                // tHTML += '  data-puncode="'+tProductCode+'"';
                tHTML += '  data-seqno="'+nKey+'"';
                tHTML += '  data-setprice="'+nPrice+'"';
                tHTML += '  data-qty="'+nQty+'"';
                tHTML += '  data-netafhd="'+nNetAfHD+'"';
                tHTML += '  data-net="'+cNet+'"';
                tHTML += '  data-stadis="'+nAlwDiscount+'"';

                tHTML += '>';
                tHTML += '<td>';
                tHTML += '  <label class="fancy-checkbox">';
                tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxPXSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td>'+nKey+'</td>';
                tHTML += '<td>'+tProductCode+'</td>';
                tHTML += '<td>'+tProductName+'</td>';
                // tHTML += '<td>'+tBarCode+'</td>';
                tHTML += '<td>'+tUnitName+'</td>';
                tHTML += '<td class="otdQty text-right">'+oQty+'</td>';
                tHTML += '<td class="otdPrice">'+oPrice+'</td>';
                tHTML += '<td>'+oAlwDis+'</td>';
                tHTML += '<td class="text-right"><span id="ospGrandTotal'+nKey+'">'+cNet+'</span>';
                tHTML += '    <span id="ospnetAfterHD'+nKey+'" style="display: none;">'+nNetAfHD+'</span>';
                tHTML += '</td>';
                if($('#ohdPXStaImport').val()==1){
                tHTML += '<td class="xPXImportDT"> </td>';
                }
                tHTML += '<td nowrap class="text-center xCNPXBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnPXDelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;

            }
        }

        //สร้างตาราง
        $('#otbPXDocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();

    }


    function FSxPXSelectMulDel(ptElm){

        let tPXDocNo    = $('#oetPXDocNo').val();
        let tPXSeqNo    = $(ptElm).parents('.xWPdtItem').data('key');
        let tPXPdtCode  = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tPXBarCode  = $(ptElm).parents('.xWPdtItem').data('barcode');

        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("PX_LocalItemDataDelDtTemp");
        let oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert   = [JSON.parse(localStorage.getItem("PX_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tPXDocNo,
                'tSeqNo'    : tPXSeqNo,
                'tPdtCode'  : tPXPdtCode,
                'tBarCode'  : tPXBarCode,
            });
            localStorage.setItem("PX_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxPXTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStPXFindObjectByKey(aArrayConvert[0],'tSeqNo',tPXSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tPXDocNo,
                    'tSeqNo'    : tPXSeqNo,
                    'tPdtCode'  : tPXPdtCode,
                    'tBarCode'  : tPXBarCode,
                    // 'tPunCode'  : tSOPunCode,
                });
                localStorage.setItem("PX_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxPXTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("PX_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tPXSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PX_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxPXTextInModalDelPdtDtTemp();
            }
        }
        JSxPXShowButtonDelMutiDtTemp();
    }

    function JSxAddScollBarInTablePdt(){
        $('#otbPXDocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbPXDocPdtAdvTableList >tbody >tr').length;
        if(rowCount >= 2){
            $('#otbPXDocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');

        }

        if(rowCount >= 7){
            $('.xCNTablescroll').css('height','450px');
            $('.xWShowInLine' + rowCount).focus();

            $('html, body').animate({
             scrollTop: ($("#oetPXInsertBarcode").offset().top)-80
         }, 0);
        }


        if($('#oetPXFrmSplCode').val() != ''){
            $('#oetPXInsertBarcode').focus();
        }
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxEditQtyAndPrice(){
        $('.xCNPdtEditInLine').click(function(){
            $(this).focus().select();
        });

        $('.xCNPdtEditInLine').off().on('change keyup',function(e){
            if(e.type === 'change' || e.keyCode === 13){
                console.log(e.type);
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
                    $('#odvPXModalConfirmDeleteDTDis').modal({
                        backdrop: 'static',
                        show: true
                    });

                    //กดยืนยันที่จะเปลี่ยน
                    $('#odvPXModalConfirmDeleteDTDis #obtPXConfirmDeleteDTDis').off('click');
                    $('#odvPXModalConfirmDeleteDTDis #obtPXConfirmDeleteDTDis').on('click',function(){
                        JSxGetDisChgList(nSeq,1);
                        $(':input:eq(' + ($(':input').index(this) + 1) +')').focus().select();
                    });

                    //กดยกเลิกที่จะไม่เปลี่ยน
                    $('#odvPXModalConfirmDeleteDTDis #obtPXCancelDeleteDTDis').off('click');
                    $('#odvPXModalConfirmDeleteDTDis #obtPXCancelDeleteDTDis').on('click',function(){
                        // $('.modal-backdrop').remove();
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

    // ptStaDelDis = 1 ลบ DTDis
    // ptStaDelDis = 0 ไม่ลบ DTDis
    function JSxGetDisChgList(pnSeq,pnStaDelDis){
  
        var tChgDT      = $('#xWDisChgDTTmp'+pnSeq).text().replace(/,/g, '');
        var cPrice      = $('#ohdPrice'+pnSeq).val();
        var nQty        = $('#ohdQty'+pnSeq).val();
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
        if($('#olbPXDisChgHD').text() == ''){
            $('#ospnetAfterHD'+pnSeq).text(parseFloat(cResult).toFixed(2));
            $('.xWPdtItemList'+pnSeq).attr('data-netafhd',parseFloat(cResult).toFixed(2));
        }

        JSxRendercalculate();

        var tPXDocNo        = $("#oetPXDocNo").val();
        var tPXBchCode      = $("#oetPXFrmBchCode").val();
        if(pnSeq!=undefined){
        $.ajax({
            type: "POST",
            url: "docPXEditPdtIntoDTDocTemp",
            data:{
                'tPXBchCode'    : tPXBchCode,
                'tPXDocNo'      : tPXDocNo,
                'nPXSeqNo'      : pnSeq,
                'nQty'          : nQty,
                'cPrice'        : cPrice,
                'cNet'          : cResult,
                'nStaDelDis'    : pnStaDelDis,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdPXUsrCode'      : $('#ohdPXUsrCode').val(),
                'ohdPXLangEdit'     : $('#ohdPXLangEdit').val(),
                'ohdSesUsrLevel'    : $('#ohdSesUsrLevel').val(),
                'ohdPXSesUsrBchCode'  : $('#ohdPXSesUsrBchCode').val(),
            },
            catch: false,
            timeout: 0,
            success: function (oResult){


            },
            error: function (jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR, textStatus, errorThrown);
                // JSxGetDisChgList(pnSeq,pnStaDelDis);
            }
        });
       }
    }

    //คำนวณจำนวนเงินจากตางราง DT
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

        //จำนวนเงินรวม
        $('#olbPXSumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(2)));

        //จำนวนเงินรวม ที่อนุญาตลด
        $('#olbPXSumFCXtdNetAlwDis').val(nTotal_alwDiscount);

        //คิดส่วนลดใหม่
        var tChgHD          = $('#ohdPXHiddenDisChgHD').text().replace(/,/g, '');
        var nNewDiscount    = 0;
        if(tChgHD != '' && tChgHD != null){ //มีส่วนลดท้ายบิล
            var aChgHD      = tChgHD.split(",");
            var nNetAlwDis  = $('#olbPXSumFCXtdNetAlwDis').val();

            for(var i=0; i<aChgHD.length; i++){
                // console.log('ยอดที่มันเอาไปคิดทำส่วนลด : ' + nNetAlwDis);
                if(aChgHD[i] != '' && aChgHD[i] != null){
                    if(aChgHD[i].search("%") == -1){
                        //ไม่เจอ = ต้องคำนวณแบบบาท
                        var nVal        = aChgHD[i];
                        var nCal        = (parseFloat(nNetAlwDis) + parseFloat(nVal));
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }else{
                        //เจอ = ต้องคำนวณแบบ %
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
                        nNewDiscount    = parseFloat(nCal);
                        nNetAlwDis      = nNewDiscount;
                        nNewDiscount    = 0;
                    }
                }
            }
            var nDiscount = (nNetAlwDis - parseFloat($('#olbPXSumFCXtdNetAlwDis').val()));
            $('#olbPXSumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(2)));

            //Prorate
            JSxProrate();
        }

        //คำนวณภาษี
        JSxCalculateVat();

        //ยอดรวมหลังลด/ชาร์จ
        // var tTypeVat = $('#ocmPXFrmSplInfoVatInOrEx').val();
        // if(tTypeVat == 1){
        //     // ภาษีรวมใน
        //     var nTotalFisrt = $('#olbPXSumFCXtdNet').text().replace(/,/g, '');
        //     var nDiscount   = $('#olbPXSumFCXtdAmt').text().replace(/,/g, '');
        //     var nVat        = $('#olbPXSumFCXtdVat').text().replace(/,/g, '');
        //     var nResult     = (parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount)) - parseFloat(nVat);
        // }else if(tTypeVat == 2){
        //     // ภาษีแยกนอก
        //     var nTotalFisrt = $('#olbPXSumFCXtdNet').text().replace(/,/g, '');
        //     var nDiscount   = $('#olbPXSumFCXtdAmt').text().replace(/,/g, '');
        //     var nResult     = parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount);
        // }

        var nTotalFisrt = $('#olbPXSumFCXtdNet').text().replace(/,/g, '');
        var nDiscount   = $('#olbPXSumFCXtdAmt').text().replace(/,/g, '');
        var nResult     = parseFloat(Math.abs(nTotalFisrt))+parseFloat(nDiscount);
        $('#olbPXSumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(2)));

        //คำนวณภาษี
        JSxCalculateVat();
    }

    function JSxProrate(){
        var pnSumDiscount       = $('#olbPXSumFCXtdAmt').text().replace(/,/g, '');
        var pnSum               = $('#olbPXSumFCXtdNetAlwDis').val().replace(/,/g, '');
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
    $('#ocmPXFrmSplInfoVatInOrEx').change(function (){ JSxRendercalculate(); });
    function JSxCalculateVat(){
        var nPXDecimalShow = $('#ohdPXDecimalShow').val();
        var tVatList  = '';
        var aVat      = [];
        $('#otbPXDocPdtAdvTableList tbody tr').each(function(){
            var nAlwVat  = $(this).attr('data-alwvat');
            var nVat     = parseFloat($(this).attr('data-vat'));
            var nKey     = $(this).attr('data-seqno');
            var tTypeVat = $('#ocmPXFrmSplInfoVatInOrEx').val();

            if(nAlwVat == 1){
                //อนุญาตคิด VAT
                if(tTypeVat == 1){
                    // ภาษีรวมใน tSoot = net - ((net * 100) / (100 + rate));
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult   = parseFloat(nTotalVat).toFixed(nPXDecimalShow);
                }else if(tTypeVat == 2){
                    // ภาษีแยกนอก tSoot = net - (net * (100 + 7) / 100) - net;
                    var net       = parseFloat($('#ospnetAfterHD'+nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult   = parseFloat(nTotalVat).toFixed(nPXDecimalShow);
                }

                var oVat = { VAT: nVat , VALUE: nResult };
                aVat.push(oVat);
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

        // var PXVatRate =  parseFloat($('#ohdPXVatRate').val());
        // var PXCmpRetInOrEx = $('#ohdPXCmpRetInOrEx').val();
        // var cSumFCXtdNetAfHD  = parseFloat($('#olbPXSumFCXtdNetAfHD').text().replace(/,/g, ''));
        // var nSumVatHD = cSumFCXtdNetAfHD - ((cSumFCXtdNetAfHD * 100)/(100 + PXVatRate));

        //เอา VAT ไปทำในตาราง
        var nSumVatHD = parseFloat($('#ohdSumFCXtdVat').val());
        var nSumVat = 0;
        var nCount  = 1;
        for(var j=0; j<aSumVat.length; j++){
            var tVatRate    = aSumVat[j].VAT;
            if(nCount != aSumVat.length){
                var tSumVat     = parseFloat(aSumVat[j].VALUE).toFixed(nPXDecimalShow) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE).toFixed(nPXDecimalShow);
            }else{
                var tSumVat     = (aSumVat[j].VALUE - nSumVat).toFixed(nPXDecimalShow);
            }
            tVatList    += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nPXDecimalShow)) + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }

        $('#oulPXDataListVat').html(tVatList);

        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbPXVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nPXDecimalShow)));

        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbPXSumFCXtdVat').text(numberWithCommas(parseFloat(nSumVat).toFixed(nPXDecimalShow)));
        $('#ohdSumFCXtdVat').val(nSumVat.toFixed(nPXDecimalShow));

        //สรุปราคารวม
        var tTypeVat = $('#ocmPXFrmSplInfoVatInOrEx').val();
        if(tTypeVat == 1){ //คิดแบบรวมใน
            var nTotal          = parseFloat($('#olbPXSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#olbPXSumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal);
        }else if(tTypeVat == 2){ //คิดแบบแยกนอก
            var nTotal          = parseFloat($('#olbPXSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat            = parseFloat($('#olbPXSumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal    = parseFloat(nTotal) + parseFloat(nVat);
        }

        //จำนวนเงินรวมทั้งสิ้น
        $('#olbPXCalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));

        //ราคารวมทั้งหมด ตัวเลขบาท
        var tTextTotal  = $('#olbPXCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath 	= ArabicNumberToText(tTextTotal);
        $('#odvPXDataTextBath').text(tThaibath);
    }

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }

    //Save Discount And Chage Footer HD (ลดท้ายบิล)
    function JCNvPXMngDocDisChagHD(event){
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var oPXDisChgParams = {
                DisChgType: 'disChgHD'
            };
            JSxPXOpenDisChgPanel(oPXDisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //Modal กำหนดส่วนลด HD
    function JCNLoadPanelDisChagHD(){
        $('#odvModalDiscount').modal({backdrop: 'static', keyboard: false})
        $('#odvModalDiscount').modal('show');
    }

    //เพิ่มส่วนลด
    function JCNvAddDisChgRow(){
        var tDuplicate = $('#otrPXDisChgHDNotFound tbody tr').hasClass('otrPXDisChgHDNotFound');
        if(tDuplicate == true){
            //ล้างค่า
            $('#otrPXDisChgHDNotFound tbody').html('');
        }

        //เพิ่มค่า
        var nKey = parseInt($('#otrPXDisChgHDNotFound tbody tr').length) + parseInt(1);

        //จำนวนเงินรวม ที่อนุญาตลด
        var nRowCount   = $('.xWDiscountChgTrTag').length;
        if(nRowCount > 0){
            var oLastRow   = $('.xWDiscountChgTrTag').last();
            var nNetAlwDis = oLastRow.find('td label.xCNDisChgAfterDisChg').text();
        }else{
            var nNetAlwDis = ($('#olbPXSumFCXtdNetAlwDis').val() == 0) ? '0.00' : $('#olbPXSumFCXtdNetAlwDis').val();
        }

        var     tSelectTypeDiscount =  '<td nowrap style="padding-left: 5px !important;">';
                tSelectTypeDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
                tSelectTypeDiscount += '<select class="dischgselectpicker form-control xCNDisChgType" onchange="JSxCalculateDiscountChg(this);">';
                tSelectTypeDiscount += '<option value="1"><?=language('common/main/main', 'ลดบาท'); ?></option>';
                tSelectTypeDiscount += '<option value="2"><?=language('common/main/main', 'ลด %'); ?></option>';
                tSelectTypeDiscount += '<option value="3"><?=language('common/main/main', 'ชาร์จบาท'); ?></option>';
                tSelectTypeDiscount += '<option value="4"><?=language('common/main/main', 'ชาร์ท %'); ?></option>';
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

    //ลบส่วนลด
    function JSxRemoveDiscountRow(elem){

    }

    //คีย์ส่วนลด
    function JSxCalculateDiscountChg(){
        $('.xWDiscountChgTrTag').each(function(index){

            var cBeforeDisChg = $('#olbPXSumFCXtdNetAlwDis').val();
            $(this).find('td label.xCNBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));

            var cCalc;
            var nDisChgType         = $(this).find('td select.xCNDisChgType').val();
            var cDisChgNum          = $(this).find('td input.xCNDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xCNBeforeDisChg').text());
            var cDisChgValue        = $(this).find('td label.xCNDisChgValue').text();
            var cDisChgAfterDisChg  = $(this).find('td label.xCNDisChgAfterDisChg').text();

            if(nDisChgType == 1){ // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }

            if(nDisChgType == 2){ // ลด %
                var cDisChgPercent  = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            if(nDisChgType == 3){ // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }

            if(nDisChgType == 4){ // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xCNDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xCNBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    //Function: Set Data Value End Of Bile
    function JSxPXSetFooterEndOfBill(poParams){
        /* ================================================= Left End Of Bill ================================================= */
        var tTextBath   = poParams.tTextBath;
        $('#odvPXDataTextBath').text(tTextBath);

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

        $('#oulPXDataListVat').html(tVatList);

        // ยอดรวมภาษีมูลค่าเพิ่ม
        var cSumVat     = poParams.aEndOfBillVat.cVatSum;
        $('#olbPXVatSum').text(cSumVat);
        /* ==================================================================================================================== */

        /* ================================================= Right End Of Bill ================================================ */
        var cCalFCXphGrand      = poParams.aEndOfBillCal.cCalFCXphGrand;
        var cSumFCXtdAmt        = poParams.aEndOfBillCal.cSumFCXtdAmt;
        var cSumFCXtdNet        = poParams.aEndOfBillCal.cSumFCXtdNet;
        var cSumFCXtdNetAfHD    = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
        var cSumFCXtdVat        = poParams.aEndOfBillCal.cSumFCXtdVat;
        var tDisChgTxt          = poParams.aEndOfBillCal.tDisChgTxt;

        // var tTypeVat = $('#ocmPXFrmSplInfoVatInOrEx').val();
        // if(tTypeVat == 1){ //คิดแบบรวมใน
        //     cNewSumFCXtdNetAfHD = cSumFCXtdNetAfHD.replace(/,/g, '') - cSumFCXtdVat.replace(/,/g, '')
        // }else{ //คิดแบบแยกนอก
        //     cNewSumFCXtdNetAfHD = cSumFCXtdNetAfHD.replace(/,/g, '');
        // }

        if(tDisChgTxt == '' || tDisChgTxt == null){
            //console.log('NULL');
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
        $('#olbPXSumFCXtdNet').text(cSumFCXtdNet);
        // ลด/ชาร์จ
        $('#olbPXSumFCXtdAmt').text(cSumFCXtdAmt);
        // ยอดรวมหลังลด/ชาร์จ
        $('#olbPXSumFCXtdNetAfHD').text(accounting.formatNumber(cSumFCXtdNetAfHD, 2, ','));
        // ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbPXSumFCXtdVat').text(cSumFCXtdVat);
        $('#ohdSumFCXtdVat').val(cSumFCXtdVat.replace(",", ""));
        // จำนวนเงินรวมทั้งสิ้น
        $('#olbPXCalFCXphGrand').text(cCalFCXphGrand);
        //จำนวนลด/ชาร์จ ท้ายบิล
        $('#olbPXDisChgHD').text(tTextDisChg);
        $('#ohdPXHiddenDisChgHD').val(tDisChgTxt);
        /* ==================================================================================================================== */
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    // $('.xCNPdtEditInLine').on('keyup',function(e){
    //     var tInputVal = $(this).val();
        // var tCharacterReg = /^\d{1,10}(\.\d{2})?$/;
        // if (!tCharacterReg.test(tInputVal) && tInputVal != '') {
        //     console.log('Doesn\'t match');
        //     event.preventDefault();
        // }else{
        //     console.log('match');
        // }
        // var tCharacterReg = /^\d{1,10}(\.)?$/;
        // if (!tCharacterReg.test(tInputVal) && tInputVal != '') {
        //     console.log('Doesn\'t match');
        //     event.preventDefault();
        // }else{
        //     console.log('match');
        // }

    // });

    // $('.xCNPdtEditInLine').on('keyup',function(e){
    //     // console.log( $(this).val().length );
    //     // console.log( $(this).attr('maxlength') );

    //     var nCode = e.keyCode || e.which;
    //     if ( nCode != 13 ) {
    //         e.preventDefault();
    //         var nValueCurrent = $(this).val().length;
    //         var nMaxLength    = parseInt($(this).attr('maxlength')) - 3;

    //         console.log( 'nValueCurrent: '+nValueCurrent );
    //         console.log( 'nMaxLength: '+nMaxLength );

    //         if( nValueCurrent > nMaxLength ){
    //             $(this).val($(this).val().slice(0, -1));
    //         }
    //     }

    // });

</script>
