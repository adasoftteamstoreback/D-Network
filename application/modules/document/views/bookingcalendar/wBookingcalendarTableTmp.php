<div class="row" style="margin-top: 10px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive" id="odvBKContent" style="overflow-y: scroll;">
            <table class="table" id="otbBKPdtTemp">
                <thead>
                    <tr>
                        <th class="text-center xCNTextBold xCNPanelDelete" style="width:5%;"><?= language('common/main/main', 'tCMNActionDelete') ?></th>
                        <th class="text-center xCNTextBold"><?= language('document/bookingcalendar/bookingcalendar', 'รหัสสินค้า') ?></th>
                        <th class="text-center xCNTextBold"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_Topic') ?></th>
                        <th class="text-center xCNTextBold"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_Type') ?></th>
                        <th class="text-center xCNTextBold" style="width:8%;"><?= language('document/depositdoc/depositdoc', 'tSOLabelFrmStatus') ?></th>
                        <th class="text-center xCNTextBold" style="width:8%;"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_PunName') ?></th>
                        <th class="text-right xCNTextBold" style="width:8%;"><?= language('document/bookingcalendar/bookingcalendar', 'ราคาต่อหน่วย') ?></th>
                        <th class="text-right xCNTextBold" style="width:8%;"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTBPDT_Qty') ?></th>
                        <th class="text-right xCNTextBold" style="width:10%;"><?= language('document/bookingcalendar/bookingcalendar', 'ราคารวม') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($aDataList['rtCode'] == 1) : ?>
                        <?php $tKeepPDTCode = ''; ?>
                        <?php $nKeepSEQCode = ''; ?>
                        <?php $nSeqPDTSet   = 0; ?>

                        <?php foreach ($aDataList['raItems'] as $nKey => $aValue) { ?>
                            <tr class="text-center xCNHavePDT xCNPDTAndPDTSet<?= $aValue['FNXtdSeqNo'] ?><?= $aValue['FTPdtCode'] ?>" data-keycode="<?= $nKey ?>" data-pdtcode="<?= $aValue['FTPdtCode'] ?>" data-seqno="<?= $aValue['FNXtdSeqNo'] ?>" data-alwvat="<?= $aValue['FTXtdVatType'] ?>" data-vat="<?= $aValue['FCXtdVatRate'] ?>" data-saletype="<?= $aValue['FTPdtSetOrSN'] ?>" data-prcstk="<?= ($aValue['FTXtdStaPrcStk'] == '') ? '0' : $aValue['FTXtdStaPrcStk'] ?>">
                                <?php
                                    $nPartitiion = $aValue['PARTITIONBYPDTSET'];
                                    if ($nPartitiion > 0) {
                                        $tRowSpan   = "rowspan='$nPartitiion'";
                                        $tCssBorder = 'border-bottom: 1px solid white !important;';
                                    } else {
                                        $tRowSpan   = "";
                                        $tCssBorder = '';
                                    }
                                    $nDecShow =  FCNxHGetOptionDecimalShow();
                                ?>

                                <?php if ($tKeepPDTCode != $aValue['FTPdtCode']) { ?>
                                    <td <?= $tRowSpan ?> class="xCNPanelDelete">
                                        <?php
                                        if ($aValue['FTXtdStaPrcStk'] == 0 || $aValue['FTXtdStaPrcStk'] == '' || $aValue['FTXtdStaPrcStk'] == 2) { //ยังไม่ได้ยืนยัน
                                            $tClassDisabledDelete = '';
                                            $oEventDelete         = 'JSnRemoveProductInTemp(this);';
                                        } else {  //ยืนยันเเล้วลบไม่ได้ 
                                            $tClassDisabledDelete = 'xCNDocDisabled';
                                            $oEventDelete         = '';
                                        }
                                        ?>
                                        <img class="xCNIconTable xCNIconDel <?= $tClassDisabledDelete ?>" onclick="<?= $oEventDelete ?>" src="<?= base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                                    </td>
                                    <td <?= $tRowSpan ?> class="text-left xCNClassPDTCode"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>
                                    <?php $nSeqPDTSet = 0; ?>
                                <?php } ?>

                                <?php
                                    // ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                    if($aValue['PARTITIONBYPDTSET'] == ($nSeqPDTSet + 1) ){
                                        $tCssBorder     = "";
                                    }else{
                                        $tCssBorder     = "border-bottom: 1px solid #ffffff !important;";
                                    }
                                ?>
                                
                                <td nowarp class="text-left" style="<?=$tCssBorder?>">
                                    <?php if($aValue['FTPdtSetOrSN'] == 1 || $aValue['FTPdtSetOrSN'] == 5 || $aValue['FTPdtSetOrSN'] == 2){ ?>
                                        <?= $aValue['FTXtdPdtName'] ?>
                                    <?php }else{ ?>
                                        &nbsp&nbsp&nbsp&nbsp&nbsp<?= '(' . $aValue['rtPDTCodeSet'] . ') - ' . $aValue['rtNamePDTSet'] ?>
                                    <?php } ?>
                                </td>

                                <?php if ($aValue['FTPdtSetOrSN'] == 2 || $aValue['FTPdtSetOrSN'] == 5 || $aValue['FTPdtSetOrSN'] == '') { //ถ้าเป็นสินค้าชุด 
                                ?>  
                                    <!--ประเภท-->
                                    <td nowarp class="text-left" style="<?=$tCssBorder?>" data-hiddencode1="<?=$aValue['PARTITIONBYPDTSET']?>" data-hiddencode='<?=$nSeqPDTSet?>'>
                                        <?php
                                            if ($aValue['rtPsyType'] == 1) {
                                                $tTypeProduct = language('document/bookingcalendar/bookingcalendar', 'tBKTableChangPDT');
                                            } else if ($aValue['rtPsyType'] == 2) {
                                                $tTypeProduct = language('document/bookingcalendar/bookingcalendar', 'tBKTablePreview');
                                            } else {
                                                $tTypeProduct = language('document/bookingcalendar/bookingcalendar', 'tBKTablePdtSET');
                                            }
                                        ?>
                                         <?= $tTypeProduct ?>
                                    </td>
                                    <input type="hidden" class="xCNValuePDTSet<?= $aValue['rtPDTCodeSet'] ?> xCNValuePDTMain<?= $aValue['FTPdtCode'] ?>">
                                    <?php $nSeqPDTSet = $nSeqPDTSet + 1; ?>
                                <?php } else { //ถ้าเป็นสินค้าปกติ 
                                ?>
                                    <td class="text-left"><?= language('product/product/product', 'tPDTOtrPTypGeneral') ?></td>
                                    <input type="hidden" class="xCNValuePDTSet<?= $aValue['FTPdtCode'] ?>">
                                    <?php $nSeqPDTSet = 0; ?>
                                <?php } ?>

                                <?php
                                    $tClassCheckSTK = "xCNCHKStock".$aValue['rtPDTCodeSet'];
                                    if ($aValue['FTXtdStaPrcStk'] == 0 || $aValue['FTXtdStaPrcStk'] == '' || $aValue['FTXtdStaPrcStk'] == 2) {
                                        if($aValue['FTXtdStaPrcStk'] == 2){
                                            $tTextStaPrcStk     = 'สต๊อกไม่พอ';
                                            $tClassTextStatus   = 'xCNBookingCancel';
                                        }else{
                                            if($aValue['rtPsyType'] == 2){
                                                $tTextStaPrcStk     = 'ไม่ตรวจสอบสต็อค';
                                                $tClassTextStatus   = 'xCNBookingNotCheck';
                                                $tClassCheckSTK     = "";
                                            }else{
                                                $tTextStaPrcStk     = language('document/bookingcalendar/bookingcalendar', 'tBKWaitConfirm');
                                                $tClassTextStatus   = 'xCNBookingWaitConfirm';
                                            }
                                        }
                                    }else {
                                        $tTextStaPrcStk     = language('document/bookingcalendar/bookingcalendar', 'tBKConfirm');
                                        $tClassTextStatus   = 'xCNBookingConfirm';
                                    }
                                ?>
                                <?php 
                                    // if($aValue['rtPDTCodeSet'] == '' || $aValue['rtPDTCodeSet'] == null){
                                    //     $tClassInStatus = "xCNCHKStock".$aValue['rtPDTCodeSet'].'_SET';
                                    // }else{
                                        $tClassInStatus = "xCNCHKStock".$aValue['rtPDTCodeSet'];
                                    // }
                                ?>
                                <td nowarp style="<?=$tCssBorder?>" class="<?=$tClassCheckSTK?> text-left <?=$tClassInStatus?>">
                                    <label class="<?= $tClassTextStatus ?>_text"><?= $tTextStaPrcStk ?></label>
                                </td>

                                <?php if ($tKeepPDTCode != $aValue['FTPdtCode']) { ?>
                                   
                                    <td <?= $tRowSpan ?> class="text-left"><?= $aValue['FTPunName']; ?></td>

                                    <!--ราคาต่อหน่วย-->
                                    <td <?= $tRowSpan ?> class="text-right"><?= number_format($aValue['FCXtdSetPrice'], 2) ?></td>

                                    <?php if ($aValue['FTXtdStaPrcStk'] == 0 || $aValue['FTXtdStaPrcStk'] == '' || $aValue['FTXtdStaPrcStk'] == 2 ) { ?>
                                        <!--จำนวน-->
                                        <td <?= $tRowSpan ?> class="text-right">
                                            <div class="xWEditInLine<?= $nKey ?>">
                                                <input type="text" maxlength="4" class="xCNQty form-control xCNInputNumericWithoutDecimal xCNPdtEditInLine text-right xWValueEditInLine<?= $nKey ?> xWShowInLine<?= $nKey ?> " id="ohdQty<?= $nKey ?>" name="ohdQty<?= $nKey ?>" data-seq="<?= $nKey ?>" data-seqno="<?= $aValue['FNXtdSeqNo']; ?>" data-docno="<?= $aValue['FTXthDocNo']; ?>" data-factor="<?= $aValue['FCXtdFactor']; ?>" maxlength="10" value="<?= str_replace(",", "", number_format($aValue['FCXtdQty'], 0)); ?>" autocomplete="off">
                                            </div>
                                        </td>
                                    <?php }else{ ?>
                                        <!--จำนวน-->
                                        <td <?= $tRowSpan ?> class="text-right"><?= number_format($aValue['FCXtdQty'], 0) ?></td>
                                    <?php } ?>

                                    <!--ราคารวม-->
                                    <td <?= $tRowSpan ?> class="text-right xCNPriceProductAll"><?= number_format($aValue['FCXtdNetAfHD'], 2) ?></td>

                                <?php } ?>
                                <span id="ospnetAfterHD<?= $nKey ?>" style="display: none;"><?= $aValue['FCXtdNetAfHD']; ?></span>
                            </tr>
                            <?php $nKeepSEQCode = $aValue['FNXtdSeqNo']; ?>
                            <?php $tKeepPDTCode = $aValue['FTPdtCode']; ?>
                        <?php } ?>
                    <?php else : ?>
                        <tr>
                            <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        JSxEditQtyBookingCalen();
    });

    //เเก้ไขจำนวน
    function JSxEditQtyBookingCalen() {
        $('.xCNPdtEditInLine').click(function() {
            $(this).focus().select();
        });

        $('.xCNQty').off().on('change keyup', function(e) {
            if (e.type === 'change' || e.keyCode === 13) {
                var nSeq    = $(this).attr('data-seq');
                var nSeqNo  = $(this).attr('data-seqno');
                var tDocNo  = $(this).attr('data-docno');
                var nFactor = $(this).attr('data-factor');
                var nQty    = $('#ohdQty' + nSeq).val();
                nNextTab = parseInt(nSeq) + 1;
                $('.xWValueEditInLine' + nNextTab).focus().select();
                JSxGetDisBookingCalenChgList(nSeq, nSeqNo, tDocNo, nFactor);
            }
        });
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxGetDisBookingCalenChgList(pnSeq, pnSeqNo, ptDocNo, pnFactor) {
        var nQty    = $('#ohdQty' + pnSeq).val();
        var nSeqNo  = pnSeqNo;
        var tDocNo  = ptDocNo;
        var nFactor = pnFactor;
        if (pnSeq != undefined) {
            $.ajax({
                type: "POST",
                url: "docBookingCalendarEventUpdateToDT",
                data: {
                    'nQty'      : nQty,
                    'nSeqNo'    : nSeqNo,
                    'tDocNo'    : tDocNo,
                    'nFactor'   : nFactor
                },
                catch: false,
                timeout: 0,
                success: function(oResult) { JSxLoadTablePDTBookingCalendar() },
                error: function(jqXHR, textStatus, errorThrown) { console.log(jqXHR); }
            });
        }
    }

    //Control ปุ่ม
    if ('<?= @$tStaDoc ?>' == 3 || '<?= @$tStaPrcDoc ?>' == 2) { //เอกสารยกเลิก + เอกสารนัดหมาย และยืนยันเเล้ว
        $('.xCNPanelDelete').hide();
    }

    //ลบคอลัมน์ใน Temp
    function JSnRemoveProductInTemp(ele) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tSeqno = $(ele).parent().parent().attr("data-seqno");
            var tPdtCode = $(ele).parent().parent().attr("data-pdtcode");

            $('.xCNPDTAndPDTSet' + tSeqno + tPdtCode).remove();
            JSxRemoveProductInTableTemp(tSeqno, tPdtCode);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ลบข้อมูล
    function JSxRemoveProductInTableTemp(tSeqno, tPdtCode) {
        $.ajax({
            type: "POST",
            url: "docBookingCalendarDeleteTmp",
            data: {
                'nSeqNo': tSeqno,
                'tPdtCode': tPdtCode,
                "tDocumentNumber": $('#ohdNameDocumentBooking').val(),
                "tBchCode": $('#ohdBKFindBchCode').val()
            },
            cache: false,
            timeout: 5000,
            success: function(tResult) {
                JSnBKCalculatePrice();

                var nLen = $('#otbBKPdtTemp tbody tr').length;
                if(nLen > 5 ){
                    $('#odvBKContent').css('height','250px');
                    $('#odvBKContent').css('overflow-y','scroll');
                }else{
                    $('#odvBKContent').css('height','auto');
                    $('#odvBKContent').css('overflow-y','');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>