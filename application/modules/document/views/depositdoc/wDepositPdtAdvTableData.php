<style>
    #odvRowDataEndOfBill .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvRowDataEndOfBill .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }

    .mark-font,
    .panel-default>.panel-heading.mark-font {
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<?php 
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive xCNTablescroll">
        <input type="text" class="xCNHide" id="ohdBrowseDataPdtCode" value="">
        <input type="text" class="xCNHide" id="ohdBrowseDataPunCode" value="">
        <input type="text" class="xCNHide" id="ohdEditInlinePdtCode" value="<?php echo $tSOPdtCode; ?>">
        <input type="text" class="xCNHide" id="ohdEditInlinePunCode" value="<?php echo $tSOPunCode; ?>">
        <table id="otbDPSDocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                <?php if($tDPSStaApv  == '1' || $tDPSStaDoc == '3'){}else{ ?>
                    <th>
                    <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmDPSCheckDeleteAll" id="ocmDPSCheckDeleteAll" >
                            <span class="ospListItem">&nbsp;</span>
                        </label>
                    </th>
                    <?php } ?>
                    <th class="xCNTextBold"><?= language('document/depositdoc/depositdoc', 'tDPSList') ?></th>
                   
                    <th class="xCNTextBold"><?= language('document/depositdoc/depositdoc', 'tDPSPrice') ?></th>
                    <th class="xCNTextBold"><?= language('document/depositdoc/depositdoc', 'tDPSRemark') ?></th>
                    <th class="xCNTextBold"><?= language('document/depositdoc/depositdoc', 'tDPSDepositPrice') ?></th>
                    <?php if($tDPSStaApv  == '1' || $tDPSStaDoc == '3'){}else{ ?>
                    <th class="xCNPIBeHideMQSS"><?php echo language('document/depositdoc/depositdoc', 'tDPSDelete'); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="odvTBodySOPdtAdvTableList">
                <?php
                if ($aDataDocDTTemp['rtCode'] == 1) :
                    foreach ($aDataDocDTTemp['raItems'] as $DataTableKey => $aDataTableVal) :
                        $nKey = $aDataTableVal['FNXtdSeqNo'];
                        $nalwayvat = $aDataTableVal['FTXtdVatType'];
                        $nVat = $aDataTableVal['FCXtdVatRate']; //ภาษี
                ?>
                        <tr class="otr<?= $aDataTableVal['FTPdtCode']; ?><?php echo $aDataTableVal['FTXtdBarCode']; ?> xWPdtItem xWPdtItemList<?= $nKey ?>" data-alwvat="<?= $nalwayvat; ?>" data-vat="<?= $nVat ?>" data-key="<?= $nKey ?>" data-pdtcode="<?= $aDataTableVal['FTPdtCode'] ?>" data-pdtname="<?= $aDataTableVal['FTXtdPdtName'] ?>" data-seqno="<?= $nKey ?>" data-setprice="<?= $aDataTableVal['FCXtdSetPrice'] ?>" data-qty="<?= $aDataTableVal['FCXtdQty'] ?>" data-netafhd="<?= $aDataTableVal['FCXtdNetAfHD'] ?>" data-net="<?= $aDataTableVal['FCXtdNet'] ?>" data-stadis="<?= $aDataTableVal['FTXtdStaAlwDis'] ?>" style="background-color: rgb(255, 255, 255);">
                        <?php if($tDPSStaApv  == '1' || $tDPSStaDoc == '3'){}else{ ?>    
                        <td style="text-align:center">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxSOSelectMulDel(this)">
                                    <span class="ospListItem">&nbsp;</span>
                                </label>
                            </td>
                        <?php } ?>
                            <td>
                                <div class="xWEditInLine<?= $nKey ?>">
                                    <input type="text" class="form-control xCNPdtEditInLine xWPdtNotEdit xWChkSoRef xWChkSoID text-left xWValueEditInLine<?= $nKey ?> " id="ohdPdName<?= $nKey ?>" name="ohdPdName<?= $nKey ?>" data-seq="<?= $nKey ?>" data-sostatus="<?= $aDataTableVal['FTTmpStatus']; ?>" value="<?= $aDataTableVal['FTXtdPdtName']; ?>" autocomplete="off" maxlength="100">
                                    <span id="ospNameHD<?= $nKey ?>" style="display: none;"><?= $aDataTableVal['FTXtdPdtName']; ?></span>
                                </div>
                            </td>
                            <td class="otdPrice">
                                <div class="xWEditInLine<?= $nKey ?>">
                                    <input type="text" class="xCNPrice form-control xWPdtNotEdit xWChkSoRef xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?= $nKey ?> " id="ohdPrice<?= $nKey ?>" name="ohdPrice<?= $nKey ?>" data-sostatus="<?= $aDataTableVal['FTTmpStatus']; ?>" maxlength="18" data-alwdis="<?= $aDataTableVal['FTXtdStaAlwDis']; ?>" data-seq="<?= $nKey ?>" value="<?= str_replace(",", "", number_format($aDataTableVal['FCXtdSalePrice'], 2)); ?>" autocomplete="off">
                                    <span id="ospPrice<?= $nKey ?>" style="display: none;"><?= str_replace(",", "", number_format($aDataTableVal['FCXtdSalePrice'], 2)); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="xWEditInLine<?= $nKey ?>">
                                    <input type="text" class="form-control xCNPdtEditInLine xWPdtNotEdit text-left xWValueEditInLine<?= $nKey ?> " id="ohdRmk<?= $nKey ?>" name="ohdRmk<?= $nKey ?>" data-seq="<?= $nKey ?>" value="<?= $aDataTableVal['FTTmpRemark']; ?>" autocomplete="off" maxlength="100">
                                    <span id="ospRmk<?= $nKey ?>" style="display: none;"><?= $aDataTableVal['FTTmpRemark']; ?></span>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="xWEditInLine<?= $nKey ?>">
                                    <input type="text" class="form-control xCNInputNumericWithDecimal xWPdtNotEdit xCNPdtEditInLine text-right xWValueEditInLine<?= $nKey ?> " id="ohdTOtalGrand<?= $nKey ?>" name="ohdTOtalGrand<?= $nKey ?>" maxlength="18" data-alwdis="<?= $aDataTableVal['FTXtdStaAlwDis']; ?>" data-seq="<?= $nKey ?>" value="<?= str_replace(",", "", number_format($aDataTableVal['FCXtdNet'], 2)); ?>" autocomplete="off">
                                </div>
                                <span id="ospnetAfterHD<?= $nKey ?>" style="display: none;"><?= str_replace(",", "", number_format($aDataTableVal['FCXtdNet'], 2)); ?></span>
                            </td>
                            <?php if($tDPSStaApv  == '1' || $tDPSStaDoc == '3'){}else{ ?>
                            <td nowrap="" class="text-center xCNPIBeHideMQSS">
                                <label class="xCNTextLink">
                                    <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnDPSDelPdtInDTTempSingle(this)">
                                </label>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php
                    endforeach;
                else :
                    ?>
                    <tr>
                        <td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%"><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                    </tr>
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
                <label><?php echo language('document/depositdoc/depositdoc', 'tSOMsgTextNotificationChangeData'); ?></label>
            </div>
            <div class="modal-footer">
                <button id="obtSOConfirmDeleteDTDis" type="button" class="btn xCNBTNPrimery" data-dismiss="modal"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button id="obtSOCancelDeleteDTDis" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'ยกเลิก'); ?></button>
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
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmTWIConfirmPdtDTTemp" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!--ลบสินค้าแบบหลายตัว-->
<div id="odvModalDelDocMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelMultiple">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

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
                            <button type="button" id="obtAddDisChg" class="btn xCNBTNPrimery pull-right" onclick="JCNvAddDisChgRow()" style="width: 100%;"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIMDAddEditDisChg') ?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive" style="min-height: 300px; max-height: 300px; overflow-y: scroll;">
                            <table id="otbDisChgDataDocHDList" class="table">
                                <thead>
                                    <tr class="xCNCenter">
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIsequence') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIBeforereducing') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIValuereducingcharging') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIAfterReducing') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIType') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPIDiscountcharge') ?></th>
                                        <th class="xCNTextBold"><?= language('document/purchaseinvoice/purchaseinvoice', 'tPITBDelete') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="otrDisChgHDNotFound">
                                        <td class="text-center xCNTextDetail2" colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!--ปุ่มยืนยันหรือยกเลิก-->
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?= language('common/main/main', 'tCancel'); ?></button>
                <button onclick="JSxDisChgSave()" type="button" class="btn xCNBTNPrimery"><?= language('common/main/main', 'tCMNOK'); ?></button>
            </div>
        </div>
    </div>
</div>
</div>

<?php include("script/jDepositPdtAdvTableData.php"); ?>

<script>
    $(document).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
        if ($('#ohdSOStaApv').val() == 1) {
            $('.xCNBTNPrimeryDisChgPlus').hide();
            $('.xCNIconTable').hide();
            $('.xCNPdtEditInLine').attr('readonly', true);
            $('#obtSOBrowseCustomer').attr('disabled', true);
            $('.ocbListItem').attr('disabled', true);
        }

        var nDPSStaDoc       = $("#ohdDPSStaDoc").val();
        var nDPSStaApr       = $("#ohdDPSStaApv").val();
        if(nDPSStaDoc != 1 || nDPSStaApr == 1){
            $( ".xWPdtNotEdit" ).each(function( index ) {
                $(this).attr("disabled", true);
            });
        }

        $( ".xWChkSoRef" ).each(function( index ) {
                if($(this).data('sostatus') == '1'){
                    // $(this).attr("disabled", true);
                }
        });

        $( ".xWChkSoID" ).each(function( index ) {
                if($(this).data('sostatus') == '1'){
                    $(this).addClass("xWCheckID");
                }
        });
        
    });

        //คลิกเลือกทั้งหมดในสินค้า DT Tmp
        $('#ocmDPSCheckDeleteAll').change(function(){

        var bStatus = $(this).is(":checked") ? true : false;
		if(bStatus == false){
			localStorage.removeItem("SO_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
		}else{
            localStorage.removeItem("SO_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
			$('.ocbListItem').each(function (e) { 
                $(this).on( "click", FSxSOSelectMulDel(this) );
            });
		}
    });


    // Next Func จาก Browse PDT Center
    function FSvDPSNextFuncB4SelPDT(ptPdtData) {
        var aPackData = JSON.parse(ptPdtData);
        for (var i = 0; i < aPackData.length; i++) {
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "[" + aNewPackData + "]";
            FSvDPSAddPdtIntoDocDTTemp(aNewPackData); // Append HMTL
            FSvDPSAddBarcodeIntoDocDTTemp(aNewPackData); // Insert Database
        }
    }

    // Next Func จาก Browse SO
    function FSvDPSNextFuncB4SelPDTSO(ptPdtData) {
        var aPackData = JSON.parse(ptPdtData);
        FSvDPSAddPdtSOIntoDocDTTemp(ptPdtData); // Append HMTL
        FSvDPSAddSOIntoDocDTTemp(ptPdtData);     // Insert Database
    }

    // Append PDT
    function FSvDPSAddPdtIntoDocDTTemp(ptPdtData) {
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        // console.log('this'+aPackData);
        var tCheckIteminTableClass = $('#otbDPSDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nSOODecimalShow = $('#ohdSOODecimalShow').val();
        var nvatRate = $('#ohdDPSVatRate').val();

        if (tCheckIteminTableClass == true) {
            $('#otbDPSDocPdtAdvTableList tbody').html('');
            var nKey = 1;
        } else {
            var nKey = parseInt($('#otbDPSDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen = aPackData.length;
        var tHTML = '';

        for (var i = 0; i < nLen; i++) {

            var oData = aPackData[i];
            var oResult = oData.packData;

            oResult.NetAfHD = (oResult.NetAfHD == '' || oResult.NetAfHD === undefined ? 0 : oResult.NetAfHD);
            oResult.Qty = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net = (oResult.Net == '' || oResult.Net === undefined ? oResult.PriceRet : oResult.Net);
            oResult.tDisChgTxt = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);

            var tBarCode = oResult.Barcode; //บาร์โค๊ด
            var tProductCode = oResult.PDTCode; //รหัสสินค้า
            var tProductName = oResult.PDTName; //ชื่อสินค้า
            var tUnitName = oResult.PUNName; //ชื่อหน่วยสินค้า
            var nPrice = (parseFloat(oResult.PriceRet.replace(/,/, '')).toFixed(nSOODecimalShow)); //ราคา
            var nAlwDiscount = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis); //อนุญาตคำนวณลด
            var nAlwVat = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat); //อนุญาตคำนวณภาษี
            var nVat = (parseFloat(oResult.nVat)).toFixed(nSOODecimalShow); //ภาษี
            var nQty = parseInt(oResult.Qty); //จำนวน
            var nNetAfHD = (parseFloat(oResult.NetAfHD)).toFixed(nSOODecimalShow);
            var cNet = (parseFloat(oResult.Net)).toFixed(nSOODecimalShow);
            var tDisChgTxt = oResult.tDisChgTxt;

            var tDuplicate = $('#otbDPSDocPdtAdvTableList tbody tr').hasClass('otr' + tProductCode + tBarCode);
            var InfoOthReAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();
            if (tDuplicate == true && InfoOthReAddPdt == 1) {
                //ถ้าสินค้าซ้ำ ให้เอา Qty +1
                var nValOld = $('.otr' + tProductCode + tBarCode).find('.xCNQty').val();
                var nNewValue = parseInt(nValOld) + parseInt(1);
                $('.otr' + tProductCode + tBarCode).find('.xCNQty').val(nNewValue);

                var nGrandOld = $('.otr' + tProductCode + tBarCode).find('.xCNPrice').val();
                var nGrand = parseInt(nNewValue) * parseFloat(nGrandOld);
                var nSeqOld = $('.otr' + tProductCode + tBarCode).find('.xCNPrice').attr('data-seq');
                $('#ohdTOtalGrand' + nSeqOld).text(numberWithCommas(nGrand.toFixed(nSOODecimalShow)));
            } else {
                //ถ้าสินค้าไม่ซ้ำ ก็บวกเพิ่มต่อเลย
                if (nAlwDiscount == 1) { //อนุญาตลด
                    var oAlwDis = '<div>';
                    oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOCallModalDisChagDT(this)" type="button">+</button>'; //JCNvDisChgCallModalDT(this)
                    oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp' + nKey + '">' + tDisChgTxt + '</label>';
                    oAlwDis += '</div>';
                } else {
                    var oAlwDis = 'ไม่อนุญาตให้ส่วนลด';
                }

                //ราคา
                var oPrice = '<div class="xWEditInLine' + nKey + '">';
                oPrice += '<input ';
                oPrice += 'type="text" ';
                oPrice += 'class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine' + nKey + ' "';
                oPrice += 'id="ohdPrice' + nKey + '" ';
                oPrice += 'name="ohdPrice' + nKey + '" ';
                oPrice += 'maxlength="18" ';
                oPrice += 'data-alwdis=' + nAlwDiscount + ' ';
                oPrice += 'data-seq=' + nKey + ' ';
                oPrice += 'value="' + nPrice + '"';
                oPrice += 'autocomplete="off" >';
                oPrice += '<span id="ospPrice' + nKey + '" style="display: none;">' + nPrice + '</span></div>';

                //หมายเหตุ
                var oRmk = '<div class="xWEditInLine' + nKey + '">';
                oRmk += '<input ';
                oRmk += 'type="text" ';
                oRmk += 'class="xCNRmk form-control text-left xCNPdtEditInLine xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
                oRmk += 'id="ohdRmk' + nKey + '" ';
                oRmk += 'name="ohdRmk' + nKey + '" ';
                oRmk += 'data-seq=' + nKey + ' ';
                oRmk += 'maxlength="100" ';
                oRmk += 'value=""';
                oRmk += 'autocomplete="off" >';
                oRmk += '<span id="ospRmk' + nKey + '" style="display: none;"></span></div>';

                //มูลค่ามัดจำ
                var oTotalGrand = '<div class="xWEditInLine' + nKey + '">';
                oTotalGrand += '<input ';
                oTotalGrand += 'type="text" ';
                oTotalGrand += 'class="xCNGrand form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
                oTotalGrand += 'id="ohdTOtalGrand' + nKey + '" ';
                oTotalGrand += 'name="ohdTOtalGrand' + nKey + '" ';
                oTotalGrand += 'data-seq=' + nKey + ' ';
                oTotalGrand += 'maxlength="18" ';
                oTotalGrand += 'value="' + nPrice + '"';
                oTotalGrand += 'autocomplete="off" >';
                oTotalGrand += '<span id="ospnetAfterHD' + nKey + '" style="display: none;">' + nPrice + '</span></div>';

                //ชื่อสินค้า
                var oPdName = '<div class="xWEditInLine' + nKey + '">';
                oPdName += '<input ';
                oPdName += 'type="text" ';
                oPdName += 'class="xCNName form-control xWCheckID xCNPdtEditInLine text-left xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
                oPdName += 'id="ohdPdName' + nKey + '" ';
                oPdName += 'name="ohdPdName' + nKey + '" ';
                oPdName += 'data-seq=' + nKey + ' ';
                oPdName += 'maxlength="100" ';
                oPdName += 'value="' + tProductName + '"';
                oPdName += 'autocomplete="off" >';
                oPdName += '<span id="ospNameHD' + nKey + '" style="display: none;">' + tProductName + '</span></div>';

                tHTML += '<tr class="otr' + tProductCode + '' + tBarCode + ' xWPdtItem xWPdtItemList' + nKey + '"';
                tHTML += '  data-alwvat="' + nAlwVat + '"';
                tHTML += '  data-vat="' + nvatRate + '"';
                tHTML += '  data-key="' + nKey + '"';
                tHTML += '  data-pdtcode="' + tProductCode + '"';
                tHTML += '  data-seqno="' + nKey + '"';
                tHTML += '  data-setprice="' + nPrice + '"';
                tHTML += '  data-qty="' + nQty + '"';
                tHTML += '  data-netafhd="' + nNetAfHD + '"';
                tHTML += '  data-net="' + cNet + '"';
                tHTML += '  data-stadis="' + nAlwDiscount + '"';

                tHTML += '>';
                tHTML += '<td style="text-align:center">';
                tHTML += '  <label class="fancy-checkbox">';
                tHTML += '      <input id="ocbListItem' + nKey + '" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxSOSelectMulDel(this)">';
                tHTML += '      <span class="ospListItem">&nbsp;</span>';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '<td>' + oPdName + '</td>';
                tHTML += '<td class="otdPrice">' + oPrice + '</td>';
                tHTML += '<td>' + oRmk + '</td>';
                tHTML += '<td>' + oTotalGrand + '</td>';

                tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
                tHTML += '  <label class="xCNTextLink">';
                tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnDPSDelPdtInDTTempSingle(this)">';
                tHTML += '  </label>';
                tHTML += '</td>';
                tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbDPSDocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
    }

    // Append PDT SO
    function FSvDPSAddPdtSOIntoDocDTTemp(ptPdtData) {
        JCNxCloseLoading();
        var aPackData = JSON.parse(ptPdtData);
        var tCheckIteminTableClass = $('#otbDPSDocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        var nSOODecimalShow = $('#ohdSOODecimalShow').val();
        var nvatRate = $('#ohdDPSVatRate').val();

        if (tCheckIteminTableClass == true) {
            $('#otbDPSDocPdtAdvTableList tbody').html('');
            var nKey = 1;
        } else {
            var nKey = parseInt($('#otbDPSDocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }
        var nLen = aPackData.length;
        var tHTML = '';

        var nPrice = (parseFloat(aPackData[1])).toFixed(nSOODecimalShow); //ราคา
        var cNet = (parseFloat(aPackData[1])).toFixed(nSOODecimalShow);
        var tProductName = aPackData[0]; //ชื่อสินค้า
        var tProductCode = aPackData[0]; //รหัสสินค้า
        var nVat = (parseFloat(7)).toFixed(nSOODecimalShow); //ภาษี
        var nNetAfHD = (parseFloat(aPackData[1])).toFixed(nSOODecimalShow);
        var nAlwVat = 1; //อนุญาตคำนวณภาษี


        var tDuplicate = $('#otbDPSDocPdtAdvTableList tbody tr').hasClass('otr' + tProductCode);
        var InfoOthReAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();


        // //ถ้าสินค้าไม่ซ้ำ ก็บวกเพิ่มต่อเลย
        // if (nAlwDiscount == 1) { //อนุญาตลด
        //     var oAlwDis = '<div>';
        //     oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOCallModalDisChagDT(this)" type="button">+</button>'; //JCNvDisChgCallModalDT(this)
        //     oAlwDis += '<label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp' + nKey + '">' + tDisChgTxt + '</label>';
        //     oAlwDis += '</div>';
        // } else {
        //     var oAlwDis = 'ไม่อนุญาตให้ส่วนลด';
        // }
        //ราคา
        var oPrice = '<div class="xWEditInLine' + nKey + '">';
        oPrice += '<input ';
        oPrice += 'type="text" readonly ';
        oPrice += 'class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine' + nKey + ' "';
        oPrice += 'id="ohdPrice' + nKey + '" ';
        oPrice += 'name="ohdPrice' + nKey + '" ';
        oPrice += 'maxlength="18" ';
        oPrice += 'data-alwdis="" ';
        oPrice += 'data-seq=' + nKey + ' ';
        oPrice += 'value="' + nPrice + '"';
        oPrice += 'autocomplete="off" >';
        oPrice += '<span id="ospPrice' + nKey + '" style="display: none;">' + nPrice + '</span></div>';

        //หมายเหตุ
        var oRmk = '<div class="xWEditInLine' + nKey + '">';
        oRmk += '<input ';
        oRmk += 'type="text" ';
        oRmk += 'class="xCNRmk form-control text-left xCNPdtEditInLine xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
        oRmk += 'id="ohdRmk' + nKey + '" ';
        oRmk += 'name="ohdRmk' + nKey + '" ';
        oRmk += 'data-seq=' + nKey + ' ';
        oRmk += 'maxlength="100" ';
        oRmk += 'value=""';
        oRmk += 'autocomplete="off" >';
        oRmk += '<span id="ospRmk' + nKey + '" style="display: none;"></span></div>';

        //มูลค่ามัดจำ
        var oTotalGrand = '<div class="xWEditInLine' + nKey + '">';
        oTotalGrand += '<input ';
        oTotalGrand += 'type="text" ';
        oTotalGrand += 'class="xCNGrand form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
        oTotalGrand += 'id="ohdTOtalGrand' + nKey + '" ';
        oTotalGrand += 'name="ohdTOtalGrand' + nKey + '" ';
        oTotalGrand += 'data-seq=' + nKey + ' ';
        oTotalGrand += 'maxlength="18" ';
        oTotalGrand += 'value="' + nPrice + '"';
        oTotalGrand += 'autocomplete="off" >';
        oTotalGrand += '<span id="ospnetAfterHD' + nKey + '" style="display: none;">' + nPrice + '</span></div>';

        //ชื่อสินค้า
        var oPdName = '<div class="xWEditInLine' + nKey + '">';
        oPdName += '<input ';
        oPdName += 'type="text" readonly ';
        oPdName += 'class="xCNName form-control xWCheckID xCNPdtEditInLine text-left xWValueEditInLine' + nKey + ' xWShowInLine' + nKey + ' "';
        oPdName += 'id="ohdPdName' + nKey + '" ';
        oPdName += 'name="ohdPdName' + nKey + '" ';
        oPdName += 'data-seq=' + nKey + ' ';
        oPdName += 'maxlength="100" ';
        oPdName += 'value="' + tProductName + '"';
        oPdName += 'autocomplete="off" >';
        oPdName += '<span id="ospNameHD' + nKey + '" style="display: none;">' + tProductName + '</span></div>';

        tHTML += '<tr class="otr' + tProductCode + ' xWPdtItem xWPdtItemList' + nKey + '"';
        tHTML += '  data-alwvat="' + nAlwVat + '"';
        tHTML += '  data-vat="' + nvatRate + '"';
        tHTML += '  data-key="' + nKey + '"';
        tHTML += '  data-pdtcode="' + tProductCode + '"';
        // tHTML += '  data-puncode="'+tProductCode+'"';
        tHTML += '  data-seqno="' + nKey + '"';
        tHTML += '  data-setprice="' + nPrice + '"';
        tHTML += '  data-qty=""';
        tHTML += '  data-netafhd="' + nNetAfHD + '"';
        tHTML += '  data-net="' + cNet + '"';
        tHTML += '  data-stadis=""';

        tHTML += '>';
        tHTML += '<td style="text-align:center">';
        tHTML += '  <label class="fancy-checkbox">';
        tHTML += '      <input id="ocbListItem' + nKey + '" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxSOSelectMulDel(this)">';
        tHTML += '      <span class="ospListItem">&nbsp;</span>';
        tHTML += '  </label>';
        tHTML += '</td>';
        tHTML += '<td>' + oPdName + '</td>';
        tHTML += '<td class="otdPrice">' + oPrice + '</td>';
        tHTML += '<td>' + oRmk + '</td>';
        tHTML += '<td>' + oTotalGrand + '</td>';

        tHTML += '<td nowrap class="text-center xCNPIBeHideMQSS">';
        tHTML += '  <label class="xCNTextLink">';
        tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnDPSDelPdtInDTTempSingle(this)">';
        tHTML += '  </label>';
        tHTML += '</td>';
        tHTML += '</tr>';
        nKey++;



        //สร้างตาราง
        $('#otbDPSDocPdtAdvTableList tbody').append(tHTML);

        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
    }

    function FSxSOSelectMulDel(ptElm) {
        // $('#otbDPSDocPdtAdvTableList #odvTBodySOPdtAdvTableList .ocbListItem').click(function(){
        let tSODocNo = $('#oetDPSDocNo').val();
        let tSOSeqNo = $(ptElm).parents('.xWPdtItem').data('key');
        let tSOPdtCode = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tSOBarCode = $(ptElm).parents('.xWPdtItem').data('barcode');
        // let tSOPunCode  = $(this).parents('.xWPdtItem').data('puncode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp = localStorage.getItem("SO_LocalItemDataDelDtTemp");
        let oDataObj = [];
        if (oLocalItemDTTemp) {
            oDataObj = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert = [JSON.parse(localStorage.getItem("SO_LocalItemDataDelDtTemp"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            oDataObj.push({
                'tDocNo': tSODocNo,
                'tSeqNo': tSOSeqNo,
                'tPdtCode': tSOPdtCode,
                'tBarCode': tSOBarCode,
                // 'tPunCode'  : tSOPunCode,
            });
            localStorage.setItem("SO_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
            JSxDPSTextInModalDelPdtDtTemp();
        } else {
            var aReturnRepeat = JStDPSFindObjectByKey(aArrayConvert[0], 'tSeqNo', tSOSeqNo);
            if (aReturnRepeat == 'None') {
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo': tSODocNo,
                    'tSeqNo': tSOSeqNo,
                    'tPdtCode': tSOPdtCode,
                    'tBarCode': tSOBarCode,
                    // 'tPunCode'  : tSOPunCode,
                });
                localStorage.setItem("SO_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
                JSxDPSTextInModalDelPdtDtTemp();
            } else if (aReturnRepeat == 'Dupilcate') {
                localStorage.removeItem("SO_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i].tSeqNo == tSOSeqNo) {
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i] != undefined) {
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("SO_LocalItemDataDelDtTemp", JSON.stringify(aNewarraydata));
                JSxDPSTextInModalDelPdtDtTemp();
            }
        }
        JSxSOShowButtonDelMutiDtTemp();
        // });
    }

    function JSxAddScollBarInTablePdt() {
        $('#otbDPSDocPdtAdvTableList >tbody >tr').css('background-color', '#ffffff');
        var rowCount = $('#otbDPSDocPdtAdvTableList >tbody >tr').length;
        if (rowCount >= 2) {
            $('#otbDPSDocPdtAdvTableList >tbody >tr').last().css('background-color', 'rgb(226, 243, 255)');
        }
        if (rowCount >= 7) {
            $('.xCNTablescroll').css('height', '450px');
            $('.xWShowInLine' + rowCount).focus();
            var myObj   = $('#oetSOInsertBarcode');
            if (myObj.length){
                $('html, body').animate({
                    scrollTop : myObj.offset().top - 20
                })
            }
        }
        if ($('#oetSOFrmCstCode').val() != '') {
            $('#oetSOInsertBarcode').focus();
        }
    }
    //คำนวณจำนวนเงินจากตางราง DT
    function JSxRendercalculate() {

        var nTotal = 0;
        var nTotal_alwDiscount = 0;
        var nSOODecimalShow = $('#ohdSOODecimalShow').val();
        $(".xCNPrice").each(function(e) {
            var nSeq = $(this).attr('data-seq');
            var nValue = $('#ohdTOtalGrand' + nSeq).val();
            var nValue = nValue.replace(/,/g, '');

            nTotal = parseFloat(nTotal) + parseFloat(nValue);

            if ($(this).attr('data-alwdis') == 1) {
                nTotal_alwDiscount = parseFloat(nTotal_alwDiscount) + parseFloat(nValue);
            };
        });

        //จำนวนเงินรวม
        $('#olbSumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(nSOODecimalShow)));

        //จำนวนเงินรวม ที่อนุญาตลด
        $('#olbSumFCXtdNetAlwDis').val(nTotal_alwDiscount);

        //คิดส่วนลดใหม่
        var tChgHD = $('#olbDisChgHD').text();

        //ยอดรวมหลังลด/ชาร์จ
        var nTotalFisrt = $('#olbSumFCXtdNet').text().replace(/,/g, '');
        var nDiscount = $('#olbSumFCXtdAmt').text().replace(/,/g, '');
        var nResult = parseFloat(Math.abs(nTotalFisrt));
        $('#olbSumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(nSOODecimalShow)));

        //คำนวณภาษี
        JSxCalculateVat();
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxEditQtyAndPrice() {

        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        // $('.xCNQty').change(function(e){
        $('.xCNPdtEditInLine').off().on('change keyup', function(e) {
            if (e.type === 'change' || e.keyCode === 13) {
                var nSeq = $(this).attr('data-seq');
                var cPrice = $('.xWPdtItemList' + nSeq).attr('data-setprice');
                // ตรวจสอบลดรายการ
                var tDisChgDTTmp = $('#xWDisChgDTTmp' + nSeq).text();
                JSxGetDisChgList(nSeq, 0);
                $(':input:eq(' + ($(':input').index(this) + 1) + ')').focus().select();
            }
        });
        

    }

    function JSxGetDisChgList(pnSeq, pnStaDelDis) {
        var nSOODecimalShow = $('#ohdSOODecimalShow').val();
        var tChgDT = $('#xWDisChgDTTmp' + pnSeq).text();
        // var cPrice = $('#ohdTOtalGrand' + pnSeq).val();
        var cPrice      = $('#ohdPrice'+pnSeq).val();
        var tName = $('#ohdPdName' + pnSeq).val();
        var tRemark = $('#ohdRmk' + pnSeq).val();
        var nGrand = $('#ohdTOtalGrand' + pnSeq).val();
        var cResult = parseFloat(nGrand.replace(/,/, '')).toFixed(nSOODecimalShow);

        // Fixed ราคาต่อหน่วย 2 ตำแหน่ง
        $('#ohdPrice'+pnSeq).val(parseFloat(cPrice).toFixed(nSOODecimalShow));
        $('#ohdTOtalGrand'+pnSeq).val(parseFloat(nGrand).toFixed(nSOODecimalShow));

        // Update Value
        $('.xWPdtItemList' + pnSeq).attr('data-setprice', parseFloat(cPrice).toFixed(nSOODecimalShow));
        $('.xWPdtItemList' + pnSeq).attr('data-net', parseFloat(cResult).toFixed(nSOODecimalShow));
        if (pnStaDelDis == 1) {
            $('#xWDisChgDTTmp' + pnSeq).text('');
        }

        if ($('#olbDisChgHD').text() == '') {
            $('#ospnetAfterHD' + pnSeq).text(parseFloat(cResult).toFixed(nSOODecimalShow));
            $('#ospPrice' + pnSeq).text(parseFloat(cPrice).toFixed(nSOODecimalShow));
            $('#ospNameHD' + pnSeq).text(tName);
            $('#ospRmk' + pnSeq).text(tRemark);
            $('.xWPdtItemList' + pnSeq).attr('data-netafhd', parseFloat(cResult).toFixed(nSOODecimalShow));
        }
        
        JSxRendercalculate();

        var tSODocNo = $("#oetDPSDocNo").val();
        var tSOBchCode = $("#ohdDPSBchCode").val();
        var tDPSVatInOrEx = $("#ocmVatInOrEx").val();
        var tDPSVatRate = $("#ohdDPSVatRate").val();
        $.ajax({
            type: "POST",
            url: "dcmDPSEditPdtIntoDTDocTemp",
            data: {
                'tSOBchCode': tSOBchCode,
                'tSODocNo': tSODocNo,
                'nSOSeqNo': pnSeq,
                'cPrice': cPrice,
                'tName': tName,
                'nGrand': cResult,
                'tRemark': tRemark,
                'cNet': cResult,
                'nVatInOrEx': tDPSVatInOrEx,
                'nVatRate': tDPSVatRate,
                'nStaDelDis': pnStaDelDis,
                'ohdSesSessionID': $('#ohdSesSessionID').val(),
                'ohdSOUsrCode': $('#ohdSOUsrCode').val(),
                'ohdSOLangEdit': $('#ohdSOLangEdit').val(),
                'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                'ohdSOSesUsrBchCode': $('#ohdSOSesUsrBchCode').val(),
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {},
            error: function(jqXHR, textStatus, errorThrown) {
                JSxGetDisChgList(pnSeq, pnStaDelDis);
            }
        });
    }



    //คำนวณภาษี
    $('#ocmVatInOrEx').change(function() {
        JSxCalculateVat();
    });

    function JSxCalculateVat() {
        var nSOODecimalShow = $('#ohdSOODecimalShow').val();
        var tVatList = '';
        var aVat = [];
        $('#otbDPSDocPdtAdvTableList tbody tr').each(function() {
            var nAlwVat = $(this).attr('data-alwvat');
            var nVat = parseFloat($(this).attr('data-vat'));
            var nKey = $(this).attr('data-key');
            var tTypeVat = $('#ocmVatInOrEx').val();
            if (nAlwVat == 1) {
                //อนุญาตคิด VAT
                if (tTypeVat == 1) {
                    // ภาษีรวมใน tSoot = net - ((net * 100) / (100 + rate));
                    var net = parseFloat($('#ospnetAfterHD' + nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult = parseFloat(nTotalVat).toFixed(2);
                } else if (tTypeVat == 2) {
                    // ภาษีแยกนอก tSoot = net - (net * (100 + 7) / 100) - net;
                    var net = parseFloat($('#ospnetAfterHD' + nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult = parseFloat(nTotalVat).toFixed(2);
                }

                var oVat = {
                    VAT: nVat,
                    VALUE: nResult
                };
                aVat.push(oVat);
            }
        });

        //เรียงลำดับ array ใหม่
        aVat.sort(function(a, b) {
            return a.VAT - b.VAT;
        });

        //รวมค่าใน array กรณี vat ซ้ำ
        var nVATStart = 0;
        var nSumValueVat = 0;
        var aSumVat = [];
        for (var i = 0; i < aVat.length; i++) {
            if (nVATStart == aVat[i].VAT) {
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                aSumVat.pop();
            } else {
                nSumValueVat = 0;
                nSumValueVat = nSumValueVat + parseFloat(aVat[i].VALUE);
                nVATStart = aVat[i].VAT;
            }

            var oSum = {
                VAT: nVATStart,
                VALUE: nSumValueVat
            };
            aSumVat.push(oSum);
        }
        //  console.log(aSumVat);

        //ยอดรวมภาษีมูลค่าเพิ่ม
        // $('#olbVatSum').text(numberWithCommas(parseFloat(nSumVatHD).toFixed(2)));

        //เอา VAT ไปทำในตาราง
        var cSumFCXtdVat = parseFloat($('#ohdSumFCXtdVat').val());
        var nSumVat = 0;
        var nCount = 1;
        for (var j = 0; j < aSumVat.length; j++) {
            var tVatRate = aSumVat[j].VAT;
            if (nCount != aSumVat.length) {
                var tSumVat = parseFloat(aSumVat[j].VALUE) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE);
            } else {
                var tSumVat = (cSumFCXtdVat - nSumVat);
            }
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }

        $('#ohdSumFCXtdVat').val(nSumVat.toFixed(nSOODecimalShow));
        var cSumFCXtdVat = parseFloat($('#ohdSumFCXtdVat').val());
        var nSumVat = 0;
        var nCount = 1;
        for (var j = 0; j < aSumVat.length; j++) {
            var tVatRate = aSumVat[j].VAT;
            if (nCount != aSumVat.length) {
                var tSumVat = parseFloat(aSumVat[j].VALUE) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE);
            } else {
                var tSumVat = (cSumFCXtdVat - nSumVat);
                
            }
            tVatList += '<li class="list-group-item"><label class="pull-left">' + tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nSOODecimalShow)) + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }

        
        $('#oulDPSDataListVat').html(tVatList);
        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbVatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nSOODecimalShow)));
        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbSumFCXtdVat').text(numberWithCommas(parseFloat(nSumVat).toFixed(nSOODecimalShow)));
        
        
        //สรุปราคารวม
        var tTypeVat = $('#ocmVatInOrEx').val();;
        if (tTypeVat == 1) { //คิดแบบรวมใน
            var nTotal = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nResultTotal = nTotal
        } else if (tTypeVat == 2) { //คิดแบบแยกนอก
            var nTotal = parseFloat($('#olbSumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat = parseFloat($('#olbSumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal = parseFloat(nTotal) + parseFloat(nVat);
        }


        //จำนวนเงินรวมทั้งสิ้น
        $('#olbCalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));

        //ราคารวมทั้งหมด ตัวเลขบาท
        var tTextTotal = $('#olbCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath = ArabicNumberToText(tTextTotal);
        $('#odvDataTextBath').text(tThaibath);
    }


    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }




    //Modal กำหนดส่วนลด HD
    function JCNLoadPanelDisChagHD() {
        $('#odvModalDiscount').modal({
            backdrop: 'static',
            keyboard: false
        })
        $('#odvModalDiscount').modal('show');
    }

    //เพิ่มส่วนลด
    function JCNvAddDisChgRow() {
        var tDuplicate = $('#otrSODisChgHDNotFound tbody tr').hasClass('otrSODisChgHDNotFound');
        if (tDuplicate == true) {
            //ล้างค่า
            $('#otrSODisChgHDNotFound tbody').html('');
        }

        //เพิ่มค่า
        var nKey = parseInt($('#otrSODisChgHDNotFound tbody tr').length) + parseInt(1);

        //จำนวนเงินรวม ที่อนุญาตลด
        var nRowCount = $('.xWDiscountChgTrTag').length;
        if (nRowCount > 0) {
            var oLastRow = $('.xWDiscountChgTrTag').last();
            var nNetAlwDis = oLastRow.find('td label.xCNDisChgAfterDisChg').text();
        } else {
            var nNetAlwDis = ($('#olbSumFCXtdNetAlwDis').val() == 0) ? '0.00' : $('#olbSumFCXtdNetAlwDis').val();
        }

        var tSelectTypeDiscount = '<td nowrap style="padding-left: 5px !important;">';
        tSelectTypeDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
        tSelectTypeDiscount += '<select class="dischgselectpicker form-control xCNDisChgType" onchange="JSxCalculateDiscountChg(this);">';
        tSelectTypeDiscount += '<option value="1"><?= language('common/main/main', 'ลดบาท'); ?></option>';
        tSelectTypeDiscount += '<option value="2"><?= language('common/main/main', 'ลด %'); ?></option>';
        tSelectTypeDiscount += '<option value="3"><?= language('common/main/main', 'ชาร์จบาท'); ?></option>';
        tSelectTypeDiscount += '<option value="4"><?= language('common/main/main', 'ชาร์ท %'); ?></option>';
        tSelectTypeDiscount += '</select>';
        tSelectTypeDiscount += '</div>';
        tSelectTypeDiscount += '</td>';

        var tDiscount = '<td nowrap style="padding-left: 5px !important;">';
        tDiscount += '<div class="form-group" style="margin-bottom: 0px !important;">';
        tDiscount += '<input class="form-control xCNInputNumericWithDecimal xCNDisChgNum" onchange="JSxCalculateDiscountChg(this);" onkeyup="javascript:if(event.keyCode==13) JSxCalculateDiscountChg(this);" type="text">';
        tDiscount += '</div>';
        tDiscount += '</td>';

        var tHTML = '';
        tHTML += '<tr class="xWDiscountChgTrTag" >';
        tHTML += '<td>' + nKey + '</td>';
        tHTML += '<td class="text-right"><label class="xCNBeforeDisChg">' + numberWithCommas(parseFloat(nNetAlwDis).toFixed(nSOODecimalShow)) + '</label></td>';
        tHTML += '<td class="text-right"><label class="xCNDisChgValue">' + '0.00' + '</label></td>';
        tHTML += '<td class="text-right"><label class="xCNDisChgAfterDisChg">' + '0.00' + '</label></td>';
        tHTML += tSelectTypeDiscount;
        tHTML += tDiscount;
        tHTML += '<td nowrap="" class="text-center">';
        tHTML += '<label class="xCNTextLink">';
        tHTML += '<img class="xCNIconTable xWDisChgRemoveIcon" src="<?= base_url('application/modules/common/assets/images/icons/delete.png') ?>" title="Remove" onclick="JSxRemoveDiscountRow(this)">';
        tHTML += '</label>';
        tHTML += '</td>';
        tHTML += '</tr>';
        $('#otbDisChgDataDocHDList tbody').append(tHTML);
        JSxCalculateDiscountChg();
    }

    //ลบส่วนลด
    function JSxRemoveDiscountRow(elem) {

    }

    //คีย์ส่วนลด
    function JSxCalculateDiscountChg() {
        $('.xWDiscountChgTrTag').each(function(index) {
            if ($('.xWDiscountChgTrTag').length == 1) {
                // $('img.xWPIDisChgRemoveIcon').first().attr('onclick','JSxPIResetDisChgRemoveRow(this)').css('opacity', '1');
            } else {
                // $('img.xWPIDisChgRemoveIcon').first().attr('onclick','').css('opacity','0.2');
            }

            var cBeforeDisChg = $('#olbSumFCXtdNetAlwDis').val();
            $(this).find('td label.xCNBeforeDisChg').first().text(accounting.formatNumber(cBeforeDisChg, 2, ','));

            var cCalc;
            var nDisChgType = $(this).find('td select.xCNDisChgType').val();
            var cDisChgNum = $(this).find('td input.xCNDisChgNum').val();
            var cDisChgBeforeDisChg = accounting.unformat($(this).find('td label.xCNBeforeDisChg').text());
            var cDisChgValue = $(this).find('td label.xCNDisChgValue').text();
            var cDisChgAfterDisChg = $(this).find('td label.xCNDisChgAfterDisChg').text();

            if (nDisChgType == 1) { // ลดบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) - parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }

            if (nDisChgType == 2) { // ลด %
                var cDisChgPercent = (cDisChgBeforeDisChg * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) - cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            if (nDisChgType == 3) { // ชาร์จบาท
                cCalc = parseFloat(cDisChgBeforeDisChg) + parseFloat(cDisChgNum);
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgNum, 2, ','));
            }

            if (nDisChgType == 4) { // ชาร์ท %
                var cDisChgPercent = (parseFloat(cDisChgBeforeDisChg) * parseFloat(cDisChgNum)) / 100;
                cCalc = parseFloat(cDisChgBeforeDisChg) + cDisChgPercent;
                $(this).find('td label.xCNDisChgValue').text(accounting.formatNumber(cDisChgPercent, 2, ','));
            }

            $(this).find('td label.xCNDisChgAfterDisChg').text(accounting.formatNumber(cCalc, 2, ','));
            $(this).next().find('td label.xCNBeforeDisChg').text(accounting.formatNumber(cCalc, 2, ','));
        });
    }

    /**
     * Functionality: Save Discount And Chage Footer HD (ลดท้ายบิล)
     * Parameters: Event Proporty
     * Creator: 22/05/2019 Piya  
     * Return: Open Modal Discount And Change HD
     * Return Type: View
     */
    function JCNvSOMngDocDisChagHD(event) {

        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var oSODisChgParams = {
                DisChgType: 'disChgHD'
            };
            JSxSOOpenDisChgPanel(oSODisChgParams);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    $(document).on("keypress", 'form', function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
</script>