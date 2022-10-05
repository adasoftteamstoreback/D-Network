<style type="text/css">
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
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbJR1DocPdtAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th nowrap class="xCNHideWhenCancelOrApprove">
                        <label class="fancy-checkbox">
                            <input type="checkbox" class="ocmJR1CheckDeleteAll" id="ocmJR1CheckDeleteAll">
                            <span class="ospListItem"></span>
                        </label>
                    </th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_pdtcode') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_pdtname') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_type') ?></th>
                    <th style="width:80px;" nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_qty') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'หน่วย') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'สถานะสต็อค') ?></th>
                    <th style="min-width:90px; width: 140px;" nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_price') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_discount') ?></th>
                    <th nowrap class="xCNTextBold"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_grand') ?></th>
                    <th nowrap class="xCNTextBold xCNHideWhenCancelOrApprove"><?= language('document/jobrequest1/jobrequest1', 'tJR1Table_delete') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($aDataDocDTTemp['rtCode'] == 1) : ?>
                    <?php 
                        $tKeepPDTCode   = '';
                        $nSeq           = 0;
                    ?>
                    <?php foreach ($aDataDocDTTemp['raItems'] as $nKey => $aValue) { ?>
                        <?php
                            $nKey       = $aValue['FNXtdSeqNo'];
                            $nSetKey    = $aValue['FNPstSeqNo'];
                        ?>
                        <?php if ($nSeq != $aValue['FNXtdSeqNo']) { ?>
                            <tr class="xWJR1ItemPdtMain xWPdtItem xWPdtItemList<?= $nKey ?>  otr<?= @$aValue['FTPdtCode']; ?><?= @$aValue['FTXtdBarCode']; ?>" data-bchcode="<?= @$aValue['FTBchCode']; ?>" data-docno="<?= @$aValue['FTXthDocNo']; ?>" data-puncode="<?= @$aValue['FTPunCode']; ?>" data-alwvat="<?= @$aValue['FTXtdVatType']; ?>" data-vat="<?= @$aValue['FCXtdVatRate'] ?>" data-key="<?= @$aValue['FNXtdSeqNo'] ?>" data-pdtcode="<?= @$aValue['FTPdtCode']; ?>" data-seqno="<?= @$aValue['FNXtdSeqNo']; ?>" data-setprice="<?= @$aValue['FCXtdSetPrice']; ?>" data-qty="<?= @$aValue['FCXtdQty']; ?>" data-netafhd="<?= $aValue['FCXtdNetAfHD']; ?>" data-net="<?= $aValue['FCXtdNet']; ?>" data-stadis="<?= $aValue['FTXtdStaAlwDis']; ?>" data-pdtname="<?= $aValue['FTXtdPdtName']; ?>">
                        <?php } else { ?>
                            <tr>
                        <?php } ?>

                            <?php if ($nSeq != $aValue['FNXtdSeqNo']) { ?>
                                <td class="xCNHideWhenCancelOrApprove" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>" style="text-align:center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?= $nKey ?>" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxJR1SelectMulDel(this)">
                                        <span class="ospListItem"></span>
                                    </label>
                                </td>
                                <td nowrap class="text-left" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>"><?= $aValue['FTPdtCode']; ?></td>
                            <?php } ?>

                            <?php
                                // Check Product Set Or SN
                                $tTextPdtSetOrSN    = "";
                                switch ($aValue['FTPdtSetOrSN']) {
                                    case '1':
                                        $tTextPdtSetOrSN = "ทั่วไป";
                                        break;
                                    case '2':
                                        $tTextPdtSetOrSN = "สินค้าชุดปกติ";
                                        break;
                                    case '5':
                                        $tTextPdtSetOrSN = "บริการชุด";
                                        break;
                                }
                                // Check Product Sevice Type
                                $tTextPsvType   = "";
                                switch ($aValue['FTPsvType']) {
                                    case '1':
                                        $tTextPsvType   = "ต้องเปลี่ยน";
                                        break;
                                    case '2':
                                        $tTextPsvType   = "ต้องตรวจสอบ";
                                        break;
                                    case '0':
                                        $tTextPsvType   = "สินค้าชุดปกติ";
                                        break;
                                }
                            ?>

                            <?php if ($aValue['FTXtdStaDTSub'] == '1') { ?>
                                <?php
                                    //ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                    if ($aValue['PARTITIONBYDOC'] > 1) {
                                        $tCssBorder     = "";
                                    } else {
                                        $tCssBorder     = "";
                                    }

                                    if ($aValue['FTPdtSetOrSN'] == '5' || $aValue['FTPdtSetOrSN'] == '2') {
                                        $tClassEditService  = "xWEditInPdtService";
                                        $tCssClickLink      = "font-weight: bold;text-decoration: underline;color: #1866ae !important;cursor: pointer !important;";
                                        $tClassHidden       = "";
                                    } else {
                                        $tClassEditService  = "";
                                        $tCssClickLink      = "";
                                        $tClassHidden       = "xCNHide";
                                    }
                                ?>

                                <!--แก้ไขชื่อได้-->
                                <?php if($aValue['FTXtdSaleType'] == '5') {?>
                                    <td style = '<?= $tCssBorder ?>'>
                                        <div class="xWEditInLine<?= $nKey ?>" >
                                            <input type="text" class=" form-control  xCNPdtEditInLine" id="ohdPdtName<?= $nKey ?>" name="ohdPdtName<?= $nKey ?>" style="padding: 0px 10px !important;" data-seq="<?= $nKey ?>" data-field="PdtName" value="<?= $aValue['FTXtdPdtName']; ?>" autocomplete="off">
                                            <span nowrap style="<?= $tCssBorder ?> <?= $tCssClickLink ?>" data-PdtType="<?= $aValue['FTXtdSaleType'] ?>" class="<?= $tClassEditService ?> <?= $tClassHidden ?>"> จัดการสินค้า</span>
                                        </div>
                                    </td>
                                <?php  }else{ ?>
                                    <?php 
                                        if($aValue['FTPdtSetOrSN'] == '5' || $aValue['FTPdtSetOrSN'] == '2'){
                                            $tClassSpanDetail   = 'xWSpanDetail';
                                            $tTextSpan          = '+   ';
                                        }else{
                                            $tClassSpanDetail   = '';
                                            $tTextSpan          = '';
                                        }
                                    ?>
                                    <td nowrap style="<?= $tCssBorder ?>" data-PdtType="<?= $aValue['FTXtdSaleType'] ?>">
                                        <input type="text" class=" form-control  xCNPdtEditInLine xCNHide" id="ohdPdtName<?= $nKey ?>" name="ohdPdtName<?= $nKey ?>" style="padding: 0px 10px !important;" data-seq="<?= $nKey ?>" data-field="PdtName" value="<?= $aValue['FTXtdPdtName']; ?>" autocomplete="off">
                                        <span style="<?= $tCssClickLink ?>" data-dtset="1" class="<?=$tClassEditService?>"><?=$tTextSpan?></span>
                                        <span style="<?= $tCssClickLink ?>" data-dtset="2" class="<?=$tClassEditService?>"><?=$aValue['FTXtdPdtName'] ?></span></td>
                                <?php } ?>

                                <td nowrap style="<?= $tCssBorder ?>"><?php echo $tTextPdtSetOrSN ?></td>
                                <td class="text-center otdQty" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <div class="xWEditInLine<?= $nKey ?>">
                                        <?php $tStaReadOnly = ""; ?>
                                        <input type="text" class="xCNQty form-control xCNInputNumericWithDecimalReplace1 xCNPdtEditInLine text-right xWValueEditInLine<?= $nKey ?> xWShowInLine<?= $nKey ?> " id="ohdQty<?= $nKey ?>" name="ohdQty<?= $nKey ?>" data-seq="<?= $nKey ?>" data-field="Qty" data-stk="<?=$aValue['FTXtdStaPrcStk']?>" style="padding: 0px 10px !important;" maxlength="10" value="<?= str_replace(",", "", number_format($aValue['FCXtdQty'], 0)); ?>" autocomplete="off" <?= $tStaReadOnly ?>>
                                    </div>
                                </td>
                                <td nowrap class="text-left" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>"><?php echo $aValue['FTPunName'] ?></td>
                                <td nowrap class="text-left" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <span class='xWTextStaStk<?=$nKey ?> xWTextConfirm<?=$nKey ?> xCNTextConfirm' <?php if($aValue['FTXtdStaPrcStk']!='1'){ echo "style='display:none;'"; }?>>ยืนยันแล้ว</span>
                                    <span class='xWTextStaStk<?=$nKey ?> xWTextCancel<?=$nKey ?> xCNTextCancel' <?php if($aValue['FTXtdStaPrcStk']!='2'){ echo "style='display:none;'"; }?>>สต็อคไม่พอ</span>
                                    <span class='xWTextStaStk<?=$nKey ?> xWTextWaitConfirm<?=$nKey ?> xCNTextWaitConfirm' <?php if($aValue['FTXtdStaPrcStk']=='1' || $aValue['FTXtdStaPrcStk']=='2'){ echo "style='display:none;'"; }?>>รอยืนยัน</span>
                                </td>
                                <td class="text-center otdPrice" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <div class="xWEditInLine<?= $nKey ?>">
                                        <input type="text" class="xCNPrice form-control xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine<?= $nKey ?> " id="ohdPrice<?= $nKey ?>" name="ohdPrice<?= $nKey ?>" style="padding: 0px 10px !important;" maxlength="10" data-alwdis="<?= $aValue['FTXtdStaAlwDis']; ?>" data-seq="<?= $nKey ?>" data-field="SetPrice" value="<?= str_replace(",", "", number_format($aValue['FCXtdSetPrice'], 2)); ?>" autocomplete="off">
                                    </div>
                                </td>
                                <td class="otdDisChagePdt" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <?php if ($aValue['FTXtdStaAlwDis'] == 1) { ?>
                                        <div>
                                            <button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvJR1CallModalDisChagDT(this)" type="button">+</button>
                                            <label class="xWDisChgDTTmp" style="padding-left: 5px;padding-top: 3px;" id="xWDisChgDTTmp<?= $nKey ?>"><?= @$aValue['FTXtdDisChgTxt']; ?></label>
                                        </div>
                                    <?php } else { ?>
                                        <label><?= language('document/purchaseorder/purchaseorder', 'tPODiscountisnotallowed'); ?></label>
                                    <?php } ?>
                                </td>
                                <td class="text-right" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <span id="ospGrandTotal<?= $nKey ?>"><?= number_format(@$aValue['FCXtdNet'], 2); ?></span>
                                    <span id="ospnetAfterHD<?= $nKey ?>" style="display: none;"><?= number_format(@$aValue['FCXtdNetAfHD'], 2); ?></span>
                                </td>
                                <td class="text-center xCNHideWhenCancelOrApprove" rowspan="<?= $aValue['PARTITIONBYDOC']; ?>">
                                    <label class="xCNTextLink">
                                        <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnJR1RemoveInDTTempSingle(this)">
                                    </label>
                                </td>
                            <?php } else { ?>
                                <?php
                                    // ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                    if ($aValue['PARTITIONBYDOC'] == $nSetKey) {
                                        $tCssBorder     = "";
                                    } else {
                                        $tCssBorder     = "";
                                    }
                                ?>
                                <td nowrap style="<?= $tCssBorder ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $aValue['FTXtdPdtName'] ?></td>
                                <td nowrap style="<?= $tCssBorder ?>"><?php echo $tTextPsvType ?></td>
                            <?php } ?>
                            </tr>
                        <?php $nSeq = $aValue['FNXtdSeqNo']; ?>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">
                                <?= language('common/main/main', 'tCMNNotFoundData') ?>
                            </td>
                        </tr>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ ลบสินค้าตัวเดียว =============================================== -->
<div id="odvJR1ModalConfirmDeleteDTDis" class="modal fade" style="z-index: 7000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('document/jobrequest1/jobrequest1', 'tJR1MsgNotificationChangeData') ?></label>
            </div>
            <div class="modal-body">
                <label><?= language('document/jobrequest1/jobrequest1', 'tJR1MsgTextNotificationChangeData'); ?></label>
            </div>
            <div class="modal-footer">
                <button id="obtJR1ConfirmDeleteDTDis" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button id="obtJR1CancelDeleteDTDis" type="button" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tCancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Script Load Table Event -->
<script type="text/javascript">
    $(document).on("keypress", 'form', function(e) {
        let code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    //คลิกเลือกทั้งหมดในสินค้า DT Tmp
    $('#ocmJR1CheckDeleteAll').change(function() {
        let bStatus = $(this).is(":checked") ? true : false;
        if (bStatus == false) {
            localStorage.removeItem("JR1_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
            JSxJR1ShowButtonDelMutiDtTemp();
        } else {
            localStorage.removeItem("JR1_LocalItemDataDelDtTemp");
            $('.ocbListItem').prop('checked', false);
            $('.ocbListItem').each(function(e) {
                $(this).on("click", FSxJR1SelectMulDel(this));
            });
        }
    });

    $(document).ready(function() {
        JSxAddScollBarInTablePdt();
        JSxRendercalculate();
        JSxEditQtyAndPrice();
        JSxSpanDTSet();

        //ลบแบบหลายรายการ
        $('#odvJR1ModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {
                JSnJR1RemovePdtDTTempMultiple();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Check เอกสารยกเลิก + เอกสารอนุมัติแล้ว
        var tStatusDoc = $('#ohdJR1StaDoc').val();
        var tStatusApv = $('#ohdJR1StaApv').val();
        if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) {
            //อินพุต ค้นหารายการสินค้า
            $('#oetSearchPdtHTML').attr('readonly', false);

            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();

            //ปุ่มส่วนลดรายการ
            $('.xCNBTNPrimeryDisChgPlus').hide();

            //จำนวน และราคา
            $('.xCNPdtEditInLine').attr('readonly', true);
        }
    });

    //Render ตาราง
    function JSxJRQEventRenderTemp(paData){
        JCNxCloseLoading();

        //ช่องสแกนต้องเปิดเมื่อมีรายการใหม่เพิ่มขึ้นไป
        $('#oetJR1InsertBarcode').attr('readonly',false);
        $('#oetJR1InsertBarcode').val('');

        var aPackData = JSON.parse(paData);
        var tCheckIteminTableClass = $('#otbJR1DocPdtAdvTableList tbody tr td').hasClass('xCNTextNotfoundDataPdtTable');
        if(tCheckIteminTableClass == true){
            $('#otbJR1DocPdtAdvTableList tbody').html('');
            var nKey    = 1;
        }else{
            var nKey    = parseInt($('#otbJR1DocPdtAdvTableList tr:last').attr('data-seqno')) + parseInt(1);
        }

        var nLen    = aPackData.length;
        var tHTML   = '';
        for(var i=0; i<nLen; i++){
            var oData           = aPackData[i];
            var oResult         = oData.packData;
            
            oResult.NetAfHD     = (oResult.PriceRet == '' || oResult.PriceRet === undefined ? 0 : oResult.PriceRet.replace(/,/g, ''));
            oResult.Qty         = (oResult.Qty == '' || oResult.Qty === undefined ? 1 : oResult.Qty);
            oResult.Net         = (oResult.PriceRet == '' || oResult.PriceRet === undefined ? 0 : oResult.PriceRet.replace(/,/g, ''));
            oResult.tDisChgTxt  = (oResult.tDisChgTxt == '' || oResult.tDisChgTxt === undefined ? '' : oResult.tDisChgTxt);
            var tPdtforSys      = oResult.PDTSpc;           //ประเภทสินค้า
            var tBarCode        = oResult.Barcode;          //บาร์โค๊ด
            var tProductCode    = oResult.PDTCode;          //รหัสสินค้า
            var tProductName    = oResult.PDTName;          //ชื่อสินค้า
            var tPunCode        = oResult.PUNCode;          //รหัสหน่วย
            var tUnitName       = oResult.PUNName;          //ชื่อหน่วยสินค้า
            var nPrice          = (parseFloat(accounting.unformat(oResult.PriceRet))).toFixed(2);                        //ราคา
            var nAlwDiscount    = (oResult.AlwDis == '' || oResult.AlwDis === undefined ? 2 : oResult.AlwDis);           //อนุญาตคำนวณลด
            var nAlwVat         = (oResult.AlwVat == '' || oResult.AlwVat === undefined ? 0 : oResult.AlwVat);           //อนุญาตคำนวณภาษี
            var nVat            = (oResult.nVat == ''   || oResult.nVat === undefined   ? 7 : parseFloat(oResult.nVat).toFixed(2));  //ภาษีจากผู้จำหน่าย
            var nQty            = parseInt(oResult.Qty);                        //จำนวน
            var nNetAfHD        = (parseFloat(oResult.NetAfHD.replace(/,/g, ''))).toFixed(2);
            var cNet            = (parseFloat(oResult.Net.replace(/,/g, ''))).toFixed(2);
            var tDisChgTxt      = oResult.tDisChgTxt;
            var tDuplicate      = $('#otbJR1DocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var tTypePDT        = oResult.tTypePDT;

            var tDuplicate = $('#otbJR1DocPdtAdvTableList tbody tr').hasClass('otr'+tProductCode+tBarCode);
            var InfoOthReAddPdt = $('#ocmJR1FrmInfoOthReAddPdt').val();
            if(tDuplicate == true && InfoOthReAddPdt==1){
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
                    var oAlwDis = ' <div>';
                        oAlwDis += '<button class="xCNBTNPrimeryDisChgPlus" onclick="JCNvJR1CallModalDisChagDT(this)" type="button">+</button>';
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
                    oPrice += 'autocomplete="off" ';
                    oPrice += 'data-field="SetPrice" ';
                    oPrice += 'style="padding: 0px 10px !important;" >'; 
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
                    oQty += 'autocomplete="off" ';
                    oQty += 'data-field="Qty" '; 
                    oQty += 'data-stk="" '; 
                    oQty += 'style="padding: 0px 10px !important;" >'; 
                    oQty += '</div>';

                    tHTML += '<tr class="xWJR1ItemPdtMain xWPdtItem xWPdtItemList'+nKey+' otr'+tProductCode+''+tBarCode+' "';
                    tHTML += '  data-index="'+nKey+'"';
                    tHTML += '  data-bchcode="" ';
                    tHTML += '  data-docno="" ';
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
                    tHTML += '  data-pdtname="'+tProductName+'"';
                    tHTML += '  data-stadis="'+nAlwDiscount+'"';
                    tHTML += '  data-TypePdt="'+tTypePDT+'"';
                    tHTML += '  data-puncode="'+tPunCode+'"';
                    tHTML += '>';
                    tHTML += '<td align="center">';
                    tHTML += '  <label class="fancy-checkbox">';
                    tHTML += '      <input id="ocbListItem'+nKey+'" type="checkbox" class="ocbListItem" name="ocbListItem[]" onclick="FSxIVSelectMulDel(this)">';
                    tHTML += '      <span class="ospListItem">&nbsp;</span>';
                    tHTML += '  </label>';
                    tHTML += '</td>';
                    tHTML += '<td>'+tProductCode+'</td>';
                
                    if(tTypePDT == '5' ){
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

                        if(oResult.SetOrSN == '5' || oResult.SetOrSN == '2'){
                            tClassEditService  = "xWEditInPdtService";
                            tCssClickLink      = "font-weight: bold;text-decoration: underline;color: #1866ae !important;cursor: pointer !important;";
                            tClassSpanDetail   = 'xWSpanDetail';
                            tTextSpan          = '+   ';
                        }else{
                            tClassEditService  = "";
                            tCssClickLink      = "";
                            tClassSpanDetail   = '';
                            tTextSpan          = '';
                        }
                        tHTML += '<td data-PdtType="'+tTypePDT+'">';
                        tHTML += '<input type="text" class=" form-control  xCNPdtEditInLine xCNHide" id="ohdPdtName'+nKey+'" name="ohdPdtName'+nKey+'" style="padding: 0px 10px !important;" data-seq="'+nKey+'" data-field="PdtName" value="'+tProductName+'" autocomplete="off">';
                        tHTML += '<span style="'+tCssClickLink+'" data-dtset="1" class="'+tClassEditService+'" >'+tTextSpan+'</span>';
                        tHTML += '<span style="'+tCssClickLink+'" data-dtset="2" class="'+tClassEditService+'" >'+tProductName+'</span>';
                        tHTML += '</td>';  
                    }

                    var tTextPdtSetOrSN    = "";
                        switch (oResult.SetOrSN) {
                        case '1':
                            tTextPdtSetOrSN = "ทั่วไป";
                            break;
                        case '2':
                            tTextPdtSetOrSN = "สินค้าชุดปกติ";
                            break;
                        case '5':
                            tTextPdtSetOrSN = "บริการชุด";
                            break;
                    }
                    
                    tHTML += '<td>'+tTextPdtSetOrSN+'</td>';
                    tHTML += '<td class="otdQty text-right" >'+oQty+'</td>';
                    tHTML += '<td>'+tUnitName+'</td>';
                    tHTML += '<td>'+'<span class="xWTextStaStk'+nKey+' xWTextWaitConfirm'+nKey+' xCNTextWaitConfirm">รอยืนยัน</span>'+'</td>';
                    tHTML += '<td class="otdPrice">'+oPrice+'</td>';
                    tHTML += '<td class="otdDisChagePdt">'+oAlwDis+'</td>';
                    tHTML += '<td class="text-right"><span id="ospGrandTotal'+nKey+'">'+cNet+'</span>';
                    tHTML += '    <span id="ospnetAfterHD'+nKey+'" style="display: none;">'+nNetAfHD+'</span>';
                    tHTML += '</td>';
                    tHTML += '<td nowrap class="text-center">';
                    tHTML += '  <label class="xCNTextLink">';
                    tHTML += '      <img class="xCNIconTable" src="application/modules/common/assets/images/icons/delete.png" title="Remove" onclick="JSnJR1RemoveInDTTempSingle(this)">';
                    tHTML += '  </label>';
                    tHTML += '</td>';
                    tHTML += '</tr>';
                nKey++;
            }
        }

        //สร้างตาราง
        $('#otbJR1DocPdtAdvTableList tbody').append(tHTML);

        //คำนวณเงิน
        JSxRendercalculate();

        //เพิ่มฟังก์ชั่นเเก้ไขจำนวน + ราคาต่อหน่วย
        JSxEditQtyAndPrice();

        //สามารถกดดูรายการสินค้า SET ได้
        JSxSpanDTSet();
    }

    //คำนวณจำนวนเงินจากตางราง DT
    function JSxRendercalculate() {
        var nTotal = 0;
        var nTotal_alwDiscount = 0;
        $(".xCNPrice").each(function(e) {
            var nSeq = $(this).attr('data-seq');
            var nValue = $('#ospGrandTotal' + nSeq).text();
            var nValue = nValue.replace(/,/g, '');
            nTotal = parseFloat(nTotal) + parseFloat(nValue);
            if ($(this).attr('data-alwdis') == 1) {
                nTotal_alwDiscount = parseFloat(nTotal_alwDiscount) + parseFloat(nValue);
            };
        });

        // จำนวนเงินรวม
        $('#olbJR1SumFCXtdNet').text(numberWithCommas(parseFloat(nTotal).toFixed(2)));

        //จำนวนเงินรวม ที่อนุญาตลด
        $('#olbJR1SumFCXtdNetAlwDis').val(nTotal_alwDiscount);


        //คิดส่วนลดใหม่
        var tChgHD          = $('#olbJR1DisChgHD').text();
        var tChgHDNoComma   = $('#ohdJR1HiddenDisChgHD').val();
        var nNewDiscount    = 0;

        if (tChgHD != '' && tChgHD != null) { //มีส่วนลดท้ายบิล
            var aChgHD = tChgHD.split(",");
            var aChgHDNoComma       = tChgHDNoComma.split(",");
            var nNetAlwDis = $('#olbJR1SumFCXtdNetAlwDis').val();
            for (var i = 0; i < aChgHDNoComma.length; i++) {
                // console.log('ยอดที่มันเอาไปคิดทำส่วนลด : ' + nNetAlwDis);
                if (aChgHDNoComma[i] != '' && aChgHDNoComma[i] != null) {
                    if (aChgHDNoComma[i].search("%") == -1) {
                        //ไม่เจอ = ต้องคำนวณแบบบาท

                        var nVal = aChgHDNoComma[i];
                        var nCal = (parseFloat(nNetAlwDis) + parseFloat(nVal));
                        nNewDiscount = parseFloat(nCal);
                        nNetAlwDis = nNewDiscount;
                        nNewDiscount = 0;
                    } else {
                        //เจอ = ต้องคำนวณแบบ %
                        var nPercent = aChgHDNoComma[i];
                        var nPercent = nPercent.replace("%", "");
                        var tCondition = nPercent.substr(0, 1);
                        var nValPercent = Math.abs(nPercent);
                        if (tCondition == '-') {
                            var nCal = parseFloat(nNetAlwDis) - ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                            if (nCal == 0) {
                                var nCal = -nNetAlwDis;
                            } else {
                                var nCal = nCal;
                            }
                        } else if (tCondition == '+') {
                            var nCal = parseFloat(nNetAlwDis) + ((parseFloat(nNetAlwDis) * nValPercent) / 100);
                        }
                        nNewDiscount = parseFloat(nCal);
                        nNetAlwDis = nNewDiscount;
                        nNewDiscount = 0;
                    }
                }
            }
            var nDiscount = (nNetAlwDis - parseFloat($('#olbJR1SumFCXtdNetAlwDis').val()));

            $('#olbJR1SumFCXtdAmt').text(numberWithCommas(parseFloat(nDiscount).toFixed(2)));

            //Prorate
            JSxProrate();
        }

        var nTotalFisrt     = $('#olbJR1SumFCXtdNet').text().replace(/,/g, '');
        var nDiscount       = $('#olbJR1SumFCXtdAmt').text().replace(/,/g, '');
        var nResult         = parseFloat(Math.abs(nTotalFisrt)) + parseFloat(nDiscount);
        $('#olbJR1SumFCXtdNetAfHD').text(numberWithCommas(parseFloat(nResult).toFixed(2)));

        //คำนวณภาษี
        JSxCalculateVat();
    }

    //คำนวณค่าภาษีแบบเฉลี่ย
    function JSxProrate() {
        var pnSumDiscount   = $('#olbJR1SumFCXtdAmt').text().replace(/,/g, '');
        var pnSum           = $('#olbJR1SumFCXtdNetAlwDis').val().replace(/,/g, '');
        var length          = $(".xCNPrice").length;
        var nSumProrate     = 0;
        var nDifferenceProrate = 0;
        $(".xCNPrice").each(function(index, e) {
            var nSeq        = $(this).attr('data-seq');
            var alwdis      = $(this).attr('data-alwdis');
            var nValue      = $('#ospGrandTotal' + nSeq).text();
            var nValue      = parseFloat(nValue.replace(/,/g, ''));
            var nProrate    = (pnSumDiscount * nValue) / pnSum;
            var netAfterHD = 0;
            if (alwdis == 1) {
                nSumProrate = parseFloat(nSumProrate) + parseFloat(nProrate);
                if (index === (length - 1)) {
                    nDifferenceProrate = pnSumDiscount - nSumProrate;
                    nProrate = nProrate + nDifferenceProrate;
                    netAfterHD = nValue + nProrate;
                } else {
                    nProrate = nProrate;
                    netAfterHD = nValue + nProrate;
                }
                $('#ospnetAfterHD' + nSeq).text(numberWithCommas(parseFloat(nValue + nProrate).toFixed(2)));
            }else{
                $('#ospnetAfterHD' + nSeq).text(numberWithCommas(parseFloat(nValue).toFixed(2)));
            }
        });
    }

    //คำนวณภาษี
    function JSxCalculateVat() {
        var nDecimalShow    = $('#ohdJR1DecimalShow').val();
        var tVatList        = '';
        var aVat            = [];
        $('#otbJR1DocPdtAdvTableList tbody tr').each(function() {
            var nAlwVat         = $(this).attr('data-alwvat');
            var nVat            = parseFloat($(this).attr('data-vat'));
            var nKey            = $(this).attr('data-seqno');
            var tTypeVat        = 1;
            if (nAlwVat == 1) {
                //อนุญาตคิด VAT
                if (tTypeVat == 1) {
                    // ภาษีรวมใน tSoot = net - ((net * 100) / (100 + rate));
                    var net = parseFloat($('#ospnetAfterHD' + nKey).text().replace(/,/g, ''));
                    var nTotalVat = net - (net * 100 / (100 + nVat));
                    var nResult = parseFloat(nTotalVat).toFixed(nDecimalShow);
                } else if (tTypeVat == 2) {
                    // ภาษีแยกนอก tSoot = net - (net * (100 + 7) / 100) - net;
                    var net = parseFloat($('#ospnetAfterHD' + nKey).text().replace(/,/g, ''));
                    var nTotalVat = (net * (100 + nVat) / 100) - net;
                    var nResult = parseFloat(nTotalVat).toFixed(nDecimalShow);
                }
                var oVat = {
                    VAT: nVat,
                    VALUE: nResult
                };
                aVat.push(oVat);
            }
        });
        // เรียงลำดับ array ใหม่
        aVat.sort(function(a, b) {
            return a.VAT - b.VAT;
        });
        // รวมค่าใน array กรณี vat ซ้ำ
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
        // เอา VAT ไปทำในตาราง
        var nSumVatHD = parseFloat($('#ohdJR1SumFCXtdVat').val());
        var nSumVat = 0;
        var nCount = 1;
        for (var j = 0; j < aSumVat.length; j++) {
            var tVatRate = aSumVat[j].VAT;
            if (nCount != aSumVat.length) {
                var tSumVat = parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow) == 0 ? '0.00' : parseFloat(aSumVat[j].VALUE).toFixed(nDecimalShow);
            } else {
                var tSumVat = (aSumVat[j].VALUE - nSumVat).toFixed(nDecimalShow);
            }
            tVatList += '<li class="list-group-item"><label class="pull-left">' + tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(nDecimalShow)) + '</label><div class="clearfix"></div></li>';
            nSumVat += parseFloat(aSumVat[j].VALUE);
            nCount++;
        }
        $('#oulJR1DataListVat').html(tVatList);
        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbJR1VatSum').text(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));
        //ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbJR1SumFCXtdVat').text(numberWithCommas(parseFloat(nSumVat).toFixed(nDecimalShow)));
        $('#ohdJR1SumFCXtdVat').val(nSumVat.toFixed(nDecimalShow));
        //สรุปราคารวม

        var tTypeVat = 1;
        if (tTypeVat == 1) { //คิดแบบรวมใน
            var nTotal = parseFloat($('#olbJR1SumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat = parseFloat($('#olbJR1SumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal = parseFloat(nTotal);
        } else if (tTypeVat == 2) { //คิดแบบแยกนอก
            var nTotal = parseFloat($('#olbJR1SumFCXtdNetAfHD').text().replace(/,/g, ''));
            var nVat = parseFloat($('#olbJR1SumFCXtdVat').text().replace(/,/g, ''));
            var nResultTotal = parseFloat(nTotal) + parseFloat(nVat);
        }

        $('#olbJR1CalFCXphGrand').text(numberWithCommas(parseFloat(nResultTotal).toFixed(2)));
        //ราคารวมทั้งหมด ตัวเลขบาท
        var tTextTotal = $('#olbJR1CalFCXphGrand').text().replace(/,/g, '');
        // console.log(tTextTotal);
        var tThaibath = ArabicNumberToText(tTextTotal);
        // console.log(tThaibath);
        $('#odvJR1DataTextBath').text(tThaibath);
    }

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }

    //เเก้ไขจำนวน และ ราคา (เช็คก่อน)
    function JSxEditQtyAndPrice() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        $('.xCNPdtEditInLine').off().on('change keyup', function(e) {
            if (e.type === 'change' || e.keyCode === 13) {
                var nSeq = $(this).attr('data-seq');
                var nQty = $('.xWPdtItemList' + nSeq).attr('data-qty');
                var cPrice = $('.xWPdtItemList' + nSeq).attr('data-setprice');

                var tField = $(this).attr('data-field');
                var nAdjStaStk = 'false';
                if( tField == 'Qty' ){
                    nAdjStaStk = 'true';
                }

                // ตรวจสอบลดรายการ
                var tDisChgDTTmp = $('#xWDisChgDTTmp' + nSeq).text().replace(/,/g, '');
                if (tDisChgDTTmp == '') {
                    JSxGetDisChgList(nSeq, 0, nAdjStaStk);
                    $(':input:eq(' + ($(':input').index(this) + 1) + ')').focus().select();
                } else {
                    // มีลด/ชาร์จ
                    $('#odvJR1ModalConfirmDeleteDTDis').modal({
                        backdrop: 'static',
                        show: true
                    });

                    //กดยืนยันที่จะเปลี่ยน
                    $('#odvJR1ModalConfirmDeleteDTDis #obtJR1ConfirmDeleteDTDis').off('click');
                    $('#odvJR1ModalConfirmDeleteDTDis #obtJR1ConfirmDeleteDTDis').on('click', function() {
                        $('#odvJR1ModalConfirmDeleteDTDis').modal('hide');
                        JSxGetDisChgList(nSeq, 1, nAdjStaStk);
                        $(':input:eq(' + ($(':input').index(this) + 1) + ')').focus().select();
                    });

                    //กดยกเลิกที่จะไม่เปลี่ยน
                    $('#odvJR1ModalConfirmDeleteDTDis #obtJR1CancelDeleteDTDis').off('click');
                    $('#odvJR1ModalConfirmDeleteDTDis #obtJR1CancelDeleteDTDis').on('click', function() {
                        $('#odvJR1ModalConfirmDeleteDTDis').modal('hide');
                        e.preventDefault();
                        nQty = nQty.replace(/,/g, '');
                        cPrice = cPrice.replace(/,/g, '');
                        $('#ohdQty' + nSeq).val(parseFloat(nQty).toFixed(2));
                        $('#ohdPrice' + nSeq).val(parseFloat(cPrice).toFixed(2));
                    });
                }
            }
        });
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisChgList(pnSeq, pnStaDelDis, pnAdjStaStk) {
        var tChgDT      = $('#xWDisChgDTTmp' + pnSeq).text().replace(/,/g, '');
        var cPrice      = $('#ohdPrice' + pnSeq).val();
        var nQty        = $('#ohdQty' + pnSeq).val();
        var tPdtName    = $('#ohdPdtName' + pnSeq).val();        
        var cResult     = parseFloat(cPrice * nQty);

        if( pnAdjStaStk == 'true' ){
            $('.xWTextStaStk'+pnSeq).hide();
            $('.xWTextWaitConfirm'+pnSeq).show();
        }

        // Fixed ราคาต่อหน่วย 2 ตำแหน่ง
        $('#ohdPrice' + pnSeq).val(parseFloat(cPrice).toFixed(2));

        // Update Value
        $('#ospGrandTotal' + pnSeq).text(numberWithCommas(parseFloat(cResult).toFixed(2)));
        $('.xWPdtItemList' + pnSeq).attr('data-qty', nQty);
        $('.xWPdtItemList' + pnSeq).attr('data-pdtname', tPdtName);
        $('.xWPdtItemList' + pnSeq).attr('data-setprice', parseFloat(cPrice).toFixed(2));
        $('.xWPdtItemList' + pnSeq).attr('data-net', parseFloat(cResult).toFixed(2));

        if (pnStaDelDis == 1) {
            $('#xWDisChgDTTmp' + pnSeq).text('');
        }

        // ถ้าไม่มีลดท้ายบิล ให้ปรับ NetAfHD
        if ($('#olbJR1DisChgHD').text() == '') {
            $('#ospnetAfterHD' + pnSeq).text(parseFloat(cResult).toFixed(2));
            $('.xWPdtItemList' + pnSeq).attr('data-netafhd', parseFloat(cResult).toFixed(2));
        }

        JSxRendercalculate();

        var tJR1DocNo = $("#oetJR1DocNo").val();
        var tJR1BchCode = $("#ohdJR1BchCode").val();
        if (pnSeq != undefined) {
            $.ajax({
                type: "POST",
                url: "docJR1EditPdtIntoDTDocTemp",
                data: {
                    'tJR1BchCode'       : tJR1BchCode,
                    'tJR1DocNo'         : tJR1DocNo,
                    'nJR1SeqNo'         : pnSeq,
                    'nQty'              : nQty,
                    'cPrice'            : cPrice,
                    'tPdtName'          : tPdtName,
                    'cNet'              : cResult,
                    'nStaDelDis'        : pnStaDelDis,
                    'nAdjStaStk'        : pnAdjStaStk
                },
                catch: false,
                timeout: 0,
                success: function(oResult) {
                    // JSxJR1SetFooterEndOfBill();
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            });
        }
    }

    //Hi-light คอลัมส์สุดท้าย
    function JSxAddScollBarInTablePdt() {
        // $('#otbJR1DocPdtAdvTableList >tbody >tr').css('background-color','#ffffff');
        var rowCount = $('#otbJR1DocPdtAdvTableList >tbody >tr').length;
        if (rowCount >= 2) {
            // $('#otbJR1DocPdtAdvTableList >tbody >tr').last().css('background-color','rgb(226, 243, 255)');
        }
        if (rowCount >= 7) {
            $('.xCNTablescroll').css('height', '450px');
            $('.xWShowInLine' + rowCount).focus();
        }
    }

    // ทำส่วนลดรายการสินค้า
    function JCNvJR1CallModalDisChagDT(poEl) {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tDocNo = $(poEl).parents('.xWPdtItem').data('docno');
            var tPdtCode = $(poEl).parents('.xWPdtItem').data('pdtcode');
            var tPdtName = $(poEl).parents('.xWPdtItem').data('pdtname');
            var tPunCode = $(poEl).parents('.xWPdtItem').data('puncode');
            var tNet = $(poEl).parents('.xWPdtItem').data('netafhd');
            var tSetPrice = $(poEl).parents('.xWPdtItem').attr('data-setprice');
            var tQty = $(poEl).parents('.xWPdtItem').attr('data-qty');
            var tStaDis = $(poEl).parents('.xWPdtItem').data('stadis');
            var tSeqNo = $(poEl).parents('.xWPdtItem').data('seqno');
            var bHaveDisChgDT = $(poEl).parents('.xWJR1DisChgDTForm').find('label.xWDisChgDTTmp').text() == '' ? false : true;
            window.DisChgDataRowDT = {
                tDocNo: tDocNo,
                tPdtCode: tPdtCode,
                tPdtName: tPdtName,
                tPunCode: tPunCode,
                tNet: tNet,
                tSetPrice: tSetPrice,
                tQty: tQty,
                tStadis: tStaDis,
                tSeqNo: tSeqNo,
                bHaveDisChgDT: bHaveDisChgDT
            };
            var oJR1DisChgParams = {
                DisChgType: 'disChgDT'
            };
            JSxJR1OpenDisChgPanel(oJR1DisChgParams);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ทำส่วนลดท้ายบิล
    function JCNvJR1MngDocDisChagHD(event) {
        var oJR1DisChgParams = {
            DisChgType: 'disChgHD'
        };
        JSxJR1OpenDisChgPanel(oJR1DisChgParams);
    }

    //คำนวณท้ายบิล
    function JSxJR1SetFooterEndOfBill(poParams) {
        /* ================================================= Left End Of Bill ================================================= */
        var tTextBath = poParams.tTextBath;
        $('#odvJR1DataTextBath').text(tTextBath);
        // รายการ vat
        var aVatItems = poParams.aEndOfBillVat.aItems;
        var tVatList = "";
        if (aVatItems.length > 0) {
            for (var i = 0; i < aVatItems.length; i++) {
                var tVatRate = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow ?>);
                var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow; ?>) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow ?>);
                tVatList += '<li class="list-group-item"><label class="pull-left">' + tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(<?= $nOptDecimalShow ?>)) + '</label><div class="clearfix"></div></li>';
            }
        } else {
            tVatList += '<li class="list-group-item"><label class="pull-left">0%</label><label class="pull-right">0.00</label><div class="clearfix"></div></li>';
        }
        $('#oulJR1DataListVat').html(tVatList);

        // ยอดรวมภาษีมูลค่าเพิ่ม
        var cSumVat = poParams.aEndOfBillVat.cVatSum;
        $('#olbJR1VatSum').text(cSumVat);
        /* ================================================= Right End Of Bill ================================================ */
        var cCalFCXphGrand = poParams.aEndOfBillCal.cCalFCXphGrand;
        var cSumFCXtdAmt = poParams.aEndOfBillCal.cSumFCXtdAmt;
        var cSumFCXtdNet = poParams.aEndOfBillCal.cSumFCXtdNet;
        var cSumFCXtdNetAfHD = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
        var cSumFCXtdVat = poParams.aEndOfBillCal.cSumFCXtdVat;
        var tDisChgTxt = poParams.aEndOfBillCal.tDisChgTxt;
        if (tDisChgTxt == '' || tDisChgTxt == null) {} else {
            var tTextDisChg = '';
            var aExplode = tDisChgTxt.split(",");
            for (var i = 0; i < aExplode.length; i++) {
                if (aExplode[i].indexOf("%") != '-1') {
                    tTextDisChg += aExplode[i] + ',';
                } else {
                    // tTextDisChg += aExplode[i] + ',';
                    tTextDisChg += accounting.formatNumber(aExplode[i], 2, ',') + ',';
                }

                //ถ้าเป็นตัวท้ายให้ลบ comma ออก
                if (i == aExplode.length - 1) {
                    tTextDisChg = tTextDisChg.substring(tTextDisChg.length - 1, -1);
                }
            }
        }
        // จำนวนเงินรวม
        $('#olbJR1SumFCXtdNet').text(cSumFCXtdNet);
        // ลด/ชาร์จ
        $('#olbJR1SumFCXtdAmt').text(cSumFCXtdAmt);
        // ยอดรวมหลังลด/ชาร์จ
        $('#olbJR1SumFCXtdNetAfHD').text(accounting.formatNumber(cSumFCXtdNetAfHD, 2, ','));
        // ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbJR1SumFCXtdVat').text(cSumFCXtdVat);
        $('#ohdJR1SumFCXtdVat').val(cSumFCXtdVat.replace(",", ""));
        // จำนวนเงินรวมทั้งสิ้น
        $('#olbJR1CalFCXphGrand').text(cCalFCXphGrand);
        //จำนวนลด/ชาร์จ ท้ายบิล
        $('#olbJR1DisChgHD').text(tTextDisChg);
        $('#ohdJR1HiddenDisChgHD').val(tDisChgTxt);
    }

    //ลบคอลัมน์ในฐานข้อมูล [รายการเดียว]
    function JSnJR1RemoveInDTTempSingle(elem) {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            //JSxJR1SubmitEventByButton();
            var tJR1AgnCode     = $('#ohdJR1ADCode').val();
            var tJR1BchCode     = $(elem).parents('.xWJR1ItemPdtMain').data('bchcode');
            var tJR1DocNo       = $(elem).parents('.xWJR1ItemPdtMain').data('docno');
            var tJR1SeqNo       = $(elem).parents('.xWJR1ItemPdtMain').data('seqno');
            var tJR1PdtCode     = $(elem).parents('.xWJR1ItemPdtMain').data('pdtcode');
            var tJR1PunCode     = $(elem).parents('.xWJR1ItemPdtMain').data('puncode');

            $.ajax({
                type    : "POST",
                url     : "docJR1RemovePdtInDTTmp",
                data    : {
                    'tJR1AgnCode'   : tJR1AgnCode,
                    'tJR1BchCode'   : tJR1BchCode,
                    'tJR1DocNo'     : tJR1DocNo,
                    'tJR1SeqNo'     : tJR1SeqNo,
                    'tJR1PdtCode'   : tJR1PdtCode,
                    'tJR1PunCode'   : tJR1PunCode
                },
                cache   : false,
                timeout : 0,
                success : function(oResult) {
                    var aResult = $.parseJSON(oResult);
                    if (aResult['nStaEvent'] == '1') {
                        var tVal = $(elem)
                            .parent()
                            .parent()
                            .parent()
                            .attr("data-pdtcode");
                        var tSeqno = $(elem)
                            .parent()
                            .parent()
                            .parent()
                            .attr("data-seqno");
                        $(elem)
                            .parent()
                            .parent()
                            .parent()
                            .remove();

                        //ลบสินค้าชุด
                        $('.xCNShowItemDTSet'+tSeqno).remove();

                        //คำนวณเงินใหม่อีกครั้ง
                        JSxRendercalculate();

                        //ถ้าลบจนหมดเเล้วให้โชว์ว่าไม่พบข้อมูล
                        var tCheckIteminTable = $('#otbJR1DocPdtAdvTableList tbody tr').length;
                        if(tCheckIteminTable == 0){
                            $('#otbJR1DocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                        }
                    } else {
                        JCNxResponseError(aResult['rtDesc']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // ลบข้อมูลใน Temp แบบ Multiple
    function JSnJR1RemovePdtDTTempMultiple() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var tDocNo = $("#oetJR1DocNo").val();
            var tBchCode = $('#ohdJR1BchCode').val();
            var aDataSeqNo = JSoJR1RemoveCommaData($('#odvJR1ModalDelPdtInDTTempMultiple #ohdConfirmJR1SeqNoDelete').val());

            $.ajax({
                type: "POST",
                url: "docJR1RemovePdtInDTMutiTmp",
                data: {
                    'tDocNo': $("#oetJR1DocNo").val(),
                    'tBchCode': $('#ohdJR1BchCode').val(),
                    'aDataSeqNo': aDataSeqNo
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    var aResult = $.parseJSON(oResult);
                    if (aResult['nStaEvent'] == '1') {
                        JSvJR1LoadPdtDataTableHtml();
                    } else {
                        JCNxResponseError(aResult['rtDesc']);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบ comma
    function JSoJR1RemoveCommaData(paData) {
        var aTexts = paData.substring(0, paData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    function FSxJR1SelectMulDel(ptElm) {
        let tDocNo = $('#oetJR1DocNo').val();
        let tSeqNo = $(ptElm).parents('.xWPdtItem').data('key');
        let tPdtCode = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        let tBarCode = $(ptElm).parents('.xWPdtItem').data('barcode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp = localStorage.getItem("JR1_LocalItemDataDelDtTemp");
        let oDataObj = [];
        if (oLocalItemDTTemp) {
            oDataObj = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert = [JSON.parse(localStorage.getItem("JR1_LocalItemDataDelDtTemp"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            oDataObj.push({
                'tDocNo': tDocNo,
                'tSeqNo': tSeqNo,
                'tPdtCode': tPdtCode,
                'tBarCode': tBarCode,
            });
            localStorage.setItem("JR1_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
            JSxJR1TextInModalDelPdtDtTemp();
        } else {
            let aReturnRepeat = JStJR1FindObjectByKey(aArrayConvert[0], 'tSeqNo', tSeqNo);
            if (aReturnRepeat == 'None') {
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo': tDocNo,
                    'tSeqNo': tSeqNo,
                    'tPdtCode': tPdtCode,
                    'tBarCode': tBarCode,
                });
                localStorage.setItem("JR1_LocalItemDataDelDtTemp", JSON.stringify(oDataObj));
                JSxJR1TextInModalDelPdtDtTemp();
            } else if (aReturnRepeat == 'Dupilcate') {
                localStorage.removeItem("JR1_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                let nLength = aArrayConvert[0].length;
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i].tSeqNo == tSeqNo) {
                        delete aArrayConvert[0][$i];
                    }
                }
                let aNewarraydata = [];
                for ($i = 0; $i < nLength; $i++) {
                    if (aArrayConvert[0][$i] != undefined) {
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("JR1_LocalItemDataDelDtTemp", JSON.stringify(aNewarraydata));
                JSxJR1TextInModalDelPdtDtTemp();
            }
        }
        JSxJR1ShowButtonDelMutiDtTemp();
    }

    //ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
    function JStJR1FindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    //ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    function JSxJR1TextInModalDelPdtDtTemp() {
        let aArrayConvert = [JSON.parse(localStorage.getItem("JR1_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {} else {
            var tJR1TextDocNo = "";
            var tJR1TextSeqNo = "";
            var tJR1TextPdtCode = "";
            var tJR1TextPunCode = "";
            $.each(aArrayConvert[0], function(nKey, aValue) {
                tJR1TextDocNo += aValue.tDocNo;
                tJR1TextDocNo += " , ";

                tJR1TextSeqNo += aValue.tSeqNo;
                tJR1TextSeqNo += " , ";

                tJR1TextPdtCode += aValue.tPdtCode;
                tJR1TextPdtCode += " , ";

                tJR1TextPunCode += aValue.tPunCode;
                tJR1TextPunCode += " , ";
            });
            $('#odvJR1ModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvJR1ModalDelPdtInDTTempMultiple #ohdConfirmJR1DocNoDelete').val(tJR1TextDocNo);
            $('#odvJR1ModalDelPdtInDTTempMultiple #ohdConfirmJR1SeqNoDelete').val(tJR1TextSeqNo);
            $('#odvJR1ModalDelPdtInDTTempMultiple #ohdConfirmJR1PdtCodeDelete').val(tJR1TextPdtCode);
            $('#odvJR1ModalDelPdtInDTTempMultiple #ohdConfirmJR1PunCodeDelete').val(tJR1TextPunCode);
        }
    }

    //ลบคอลัมน์ในฐานข้อมูล เปิดปุ่มลบทั้งหมด [หลายรายการ]
    function JSxJR1ShowButtonDelMutiDtTemp() {
        let aArrayConvert = [JSON.parse(localStorage.getItem("JR1_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $("#oliJR1BtnDeleteMulti").addClass("disabled");
        } else {
            var nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $("#oliJR1BtnDeleteMulti").removeClass("disabled");
            } else {
                $("#oliJR1BtnDeleteMulti").addClass("disabled");
            }
        }
    }

    //ดูรายละเอียดของ DT Set
    function JSxSpanDTSet(){
        $('.xWEditInPdtService').off('click').on('click', function() {
            
            var tPDTCode        = $(this).parents().parents().attr('data-pdtcode');
            var nSeqCode        = $(this).parents().parents().attr('data-seqno');
            var tClassShowSpan  = 'xCNShowSpanItemDTSet'+nSeqCode; //เก็บ class ไว้ว่า กดโชว์หรือยัง
            var tClassShow      = 'xCNShowItemDTSet'+nSeqCode
            var oObject         = $(this);
            var nDTSet          = $(this).attr('data-dtset');

            if(nDTSet == 1){//กดปุ่ม + จะ span 
                var tHasClassDTSet = $(this).parent().parent().hasClass(tClassShowSpan);
                if(tHasClassDTSet == true){
                    var tFindClassShow = $(this).parent().parent().hasClass('xCNRowHide');
                    if(tFindClassShow == true){
                        $('.'+tClassShow).show();
                        $(this).parent().parent().removeClass('xCNRowHide');
                        $(this).text('-    ');
                    }else{
                        $('.'+tClassShow).hide();
                        $(this).parent().parent().addClass('xCNRowHide');
                        $(this).text('+    ');
                    }
                }else{
                    $.ajax({
                        type    : "POST",
                        url     : "docJR1FindDTSet",
                        data    : {
                            'tDocNo'    : $("#oetJR1DocNo").val(),
                            'tBchCode'  : $('#ohdJR1BchCode').val(),
                            'tPDTCode'  : tPDTCode,
                            'nSeqno'    : nSeqCode
                        },
                        cache   : false,
                        timeout : 0,
                        success : function(oResult) {
                            var oResultItem     = JSON.parse(oResult);
                            var tHTMLAppend     = '';
                            var tClassCSSBottom = '';
                            var tClassCSSTop    = '';
                            if(oResultItem.length > 0){
                                for(var i=0; i<oResultItem.length; i++){

                                    if(i == 0){
                                        tClassCSSTop    = 'border-top: 2px solid white !important;';
                                    }else{
                                        tClassCSSTop    = '';
                                    }

                                    if(i == (oResultItem.length - 1)){
                                        tClassCSSBottom = '';
                                    }else{
                                        tClassCSSBottom = 'border-bottom: 1px solid #ffffff !important;';
                                    }

                                    switch (oResultItem[i]['FTPsvType']) {
                                        case '1':
                                            var tTextPsvType   = "ต้องเปลี่ยน";
                                            break;
                                        case '2':
                                            var tTextPsvType   = "ต้องตรวจสอบ";
                                            break;
                                        case '0':
                                            var tTextPsvType   = "สินค้าชุดปกติ";
                                            break;
                                    }

                                   

                                    tHTMLAppend += "<tr class='"+tClassShow+"'>";

                                    //ไม่นับ checkbox all
                                    var tStaApv = $('#ohdJR1StaApv').val();
                                    if(tStaApv != 1){
                                        tHTMLAppend += "<td nowrap style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    }

                                    tHTMLAppend += "<td nowrap style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td nowrap style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'>&nbsp;&nbsp;&nbsp;&nbsp;"+oResultItem[i]['FTPdtName']+"</td>";
                                    tHTMLAppend += "<td nowrap style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'>"+tTextPsvType+"</td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    tHTMLAppend += "<td style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";

                                    //ไม่นับ ปุ่มถึงขยะ
                                    var tStaApv = $('#ohdJR1StaApv').val();
                                    if(tStaApv != 1){
                                        tHTMLAppend += "<td nowrap style='"+tClassCSSTop + ' ' +tClassCSSBottom+"'></td>";
                                    }
                                    tHTMLAppend += "</tr>";
                                }

                                oObject.text('-    ');
                                oObject.parent().parent().after(tHTMLAppend);
                                oObject.parent().parent().addClass(tClassShowSpan);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            }else if(nDTSet == 2){ //กดที่ชื่อสินค้า
                var tStaApv = $('#ohdJR1StaApv').val();
                if (tStaApv != '1') {
                    var aPdtData = [];
                    if(oObject.parent().attr('data-PdtType') == '5'){
                        var aPackData = {
                            "PDTCode": oObject.parent().parent().attr('data-pdtcode'),
                        };
                    }else{
                        var aPackData = {
                            "PDTCode": oObject.parent().parent().attr('data-pdtcode'),
                        };
                    }

                    aPdtData.push({
                        'packData': aPackData,
                        'nSeqno': nSeqCode,
                    });

                    var poParam = JSON.stringify(aPdtData);
                    JSvJR1LoadModalShowDTSetCstFollow(poParam, 'edit');
                }
            }
        });
    }

    
</script>