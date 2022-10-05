<?php $nDecimal = FCNxHGetOptionDecimalShow(); ?>
<style type="text/css">
    #odvRPPRowDataFoolter .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvRPPRowDataFoolter .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvRPPRowDataFoolter .list-group-item {
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
        <table id="otbRPPStep1Point2DocPdtAdvTableList" class="table table-striped">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold" style="width:5%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2No') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocType') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocDate') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocNo') ?></th>
                    <th class="xCNTextBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocRef') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocDateExp') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocAmt') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocPaid') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocRem') ?></th>
                    <th class="xCNTextBold" style="width:10%;"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSTP1P2DocPay') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($aDataList['rtCode'] == 1) : ?>
                    <?php if(FCNnHSizeOf($aDataList['raItems']) != 0): ?>
                        <?php 
                            $SumAmt         = 0;
                            $SumAlreadyRem  = 0;
                            $SumDiscount    = 0;
                            $SumAddcount    = 0;
                        ?>
                        <?php foreach($aDataList['raItems'] as $nKey => $aDataTableVal) :?>
                            <?php 
                                $nKey   = $nKey + 1;
                                $SumKey = $nKey;
                                $SumAmt = $SumAmt + $aDataTableVal['FCXtdVatable'];
                                if($aDataTableVal['FTSrnCode'] == 'PC'){
                                    $SumDiscount    = $SumDiscount + $aDataTableVal['FCXtdAmt'];
                                }
                                if($aDataTableVal['FTSrnCode'] == 'PD'){
                                    $SumAddcount    = $SumAddcount + $aDataTableVal['FCXtdAmt'];
                                }
                                $SumAlreadyRem      = $aDataTableVal['FCXtdSetPrice'];

                                // Case Document Type Text
                                $TypeDoc = "";
                                switch($aDataTableVal['FTSrnCode']){
                                    case 'IV':
                                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPIBill');
                                    break;
                                    case 'PC':
                                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPCBill');
                                    break;
                                    case 'PD':
                                        $TypeDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPDBill');
                                    break;
                                }
                                $SumPricePay    = 0.00;
                            ?>
                            <tr 
                                class="otr<?= $aDataTableVal['FTPdtCode']; ?> xWPdtItemStep2 xWPdtItemList<?= $nKey ?>"
                                data-key="<?= $nKey ?>" 
                                data-pdtcode="<?= $aDataTableVal['FTPdtCode']; ?>"
                                data-seqno="<?= $nKey ?>"
                            >
                                <td style="text-align:center;vertical-align:middle;"><?= $nKey ?></td>
                                <td style="vertical-align:middle;"><?= $TypeDoc; ?></td>
                                <td style="vertical-align:middle;" class="text-center"><?= $aDataTableVal['DateSplGet']; ?></td>
                                <td style="vertical-align:middle;"><?= $aDataTableVal['FTPdtCode']; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= (!empty($aDataTableVal['FTXtdDocNoRef']))? $aDataTableVal['FTXtdDocNoRef'] : '-'; ?>
                                </td>
                                <td style="vertical-align:middle;" class="text-center">
                                    <?= (!empty($aDataTableVal['DateReq']))? $aDataTableVal['DateReq'] : '-'; ?>
                                </td>
                                <?php if($aDataTableVal['FTSrnCode'] == 'PC'){?>  
                                    <td class="text-right">-<?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
                                <?php }else{ ?>
                                    <td class="text-right"><?= number_format($aDataTableVal['FCXtdAmt'],$nDecimal); ?></td>
                                <?}?>
                                <td style="vertical-align:middle;" class="text-right"><?= number_format($aDataTableVal['FCXtdSetPrice'],$nDecimal); ?></td>
                                <td style="vertical-align:middle;" class="text-right"><?= number_format($aDataTableVal['FCXtdVatable'],$nDecimal); ?></td>
                                <td style="vertical-align:middle;" class="text-right">
                                    <input type="text" class="from-control text-right xCNPricePay" value="<?=@$SumPricePay;?>" readonly>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif;?>
                <?php else :?>
                    <tr>
                        <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php 
    // คำนวณยอดที่ทั้งหมด
    $cTotalGrand    = 0;
    $cTotalGrand    = floatval($SumAmt) - floatval($SumDiscount) + floatval($SumAddcount);
    // คำนวณยอดที่ค้างชำระ
    $cTotalRem      = 0;
    $cTotalRem      = floatval($SumAlreadyRem);
    // ยอดรวมที่จ่าย
?>
<input type="hidden" class="form-control" id="ohdRPPGrand" name="ohdRPPGrand" value="<?=@$cTotalGrand;?>">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;">
    <div class="row" id="odvRPPRowDataFoolter">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-group">
                        <label class="xCNLabelFrm active"><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleInvPay');?></label>
                    </div>
                    <input 
                        type="text"
                        class="form-control text-right input-lg xCNInputNumericWithDecimal xCNInputMaskCurrency"
                        id="oetRPPPriceInvPay"
                        name="oetRPPPriceInvPay"
                        placeholder="<?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPPlcholderInvPay');?>"
                        value=""
                        autocomplete="off"
                    >
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <label class="pull-left  mark-font"><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleAmtBill');?></label>
                            <label class="pull-right mark-font" id="olbRPPAmtBill"><?=@$nKey;?></label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left  mark-font"><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleTotalAmt');?></label>
                            <label class="pull-right mark-font" id="olbRPPTotalAmt"><?=number_format($cTotalGrand,$nDecimal);?></label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left  mark-font"><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleTotalPaid');?></label>
                            <label class="pull-right mark-font" id="olbRPPTotalRem"><?=number_format($cTotalRem,$nDecimal);?></label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left  mark-font"><?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleTotalPay');?></label>
                            <label class="pull-right mark-font" id="olbRPPTotalPay"></label>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    // Function : Delay Time Out Key Up
    // Creator  : 30/03/2022 Wasin
    function FSxRPPDelay(callback, ms){
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
    }



    // Function : Event Key Up เช็คยอดเงินที่ต้องการชำระ กับ จำนวนเงินรวม ต้องกรอกไม่เกืน
    // Creator  : 30/03/2022 Wasin
    $('#oetRPPPriceInvPay').keyup(FSxRPPDelay(function () {
        var cRPPInvPay  = $(this).val();
        if(typeof(cRPPInvPay) != undefined && cRPPInvPay != ""){
            // Check Payment Condition
            JWbRPPCheckInValPayCompareAmt(cRPPInvPay);
        }
    },500));

    // Function : Event Key ยอดเงินที่ต้องการชำระ
    // Creator  : 30/03/2022 Wasin
    function JWbRPPCheckInValPayCompareAmt(pcInvPay){
        var tRPPTotalAmt    = parseFloat($('#ohdRPPGrand').val());
        var tRPPInvPay      = parseFloat(pcInvPay);
        if(tRPPInvPay > tRPPTotalAmt){
            // เช็คว่า ยอดเงินที่ต้องการชำระ กรอกเยอะกว่า ยอดรวมที่ต้องชำระ
            $('#oetRPPPriceInvPay').val(tRPPTotalAmt);
        }
        return true;
    }


    // Function : Event Key Enter ยอดเงินที่ต้องการชำระ
    // Creator  : 30/03/2022 Wasin
    $('#oetRPPPriceInvPay').on('keypress',function(e) {
        if(e.which == 13) {
            var cRPPInvPay  = $(this).val();
            if(typeof(cRPPInvPay) != undefined && cRPPInvPay != ""){
                $('.xCNRPPStep1Point3').attr('data-toggle','tab');
                // เอาราคาไป กระจาย ให้แต่ละเอกสาร
                JSxRPPSendProratePayment(cRPPInvPay);
            }else{
                $('.xCNRPPStep1Point3').attr('data-toggle','false');
            }
        }
    });

    // Function : ส่งจำนวนยอดเงินที่ต้องการจ่ายไป Prorate ให้แต่ละเอกสาร
    // Creator  : 02/04/2022 Wasin
    function JSxRPPSendProratePayment(pcInvPay){
        if(typeof(pcInvPay) != undefined && pcInvPay != ""){
            JCNxOpenLoading();
            var tAgnCode    = $('#oetRPPAgnCode').val();
            var tBchCode    = $('#oetRPPBchCode').val();
            var tSplCode    = $('#ohdRPPSPLCode').val();
            var tRPPDocNo   = $('#ohdRPPDocNo').val();
            var tPdtCode    = JSoRPPRemoveCommaData($("#ohdConfirmRPPInsertPDT").val());
            $.ajax({
                type    : "POST",
                url     : "docRPPEventProRatePayment",
                data    : {
                    'tAGNCode'      : tAgnCode,
                    'tBCHCode'      : tBchCode,
                    'tSplCode'      : tSplCode,
                    'tRPPDocNo'     : tRPPDocNo,
                    'cInvPayment'   : pcInvPay,
                    'tPdtCode'      : tPdtCode
                },
                cache   : false,
                Timeout : 0,
                success : function (oResult){
                    var aDataReturn = JSON.parse(oResult);
                    if(aDataReturn['nStaEvent'] == '1'){
                        $('#otbRPPStep1Point2DocPdtAdvTableList tbody').html(aDataReturn['tTalbleHtml']);
                        if(aDataReturn['aDataEndOfBill'] != ''){
                            // Set End Of Bill
                            let cInvGrandSum    = aDataReturn['aDataEndOfBill']['cInvGrandSum'];
                            let cInvRemSum      = aDataReturn['aDataEndOfBill']['cInvRemSum'];
                            let cInvPaySum      = aDataReturn['aDataEndOfBill']['cInvPaySum'];
                            $("#olbRPPTotalAmt").text(cInvGrandSum);
                            $("#olbRPPTotalRem").text(cInvRemSum);
                            $("#olbRPPTotalPay").text(cInvPaySum);
                        }
                        JCNxCloseLoading();
                    }else{
                        var tMessageError   = aDataReturn['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                        JCNxCloseLoading();
                    }
                },
                error   : function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }







</script>