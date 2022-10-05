<?php $nDecimal = FCNxHGetOptionDecimalShow(); ?>
<?php 
    if($aDataList['rtCode'] == '1'){
        $cAmtB4DisChg   = $aDataList['raItems']['FCXtdAmtB4DisChg'];
        $cXtdChg        = $aDataList['raItems']['FCXtdChg'];
        $cXtdNetAfHD    = $aDataList['raItems']['FCXtdNetAfHD'];
        $cXtdDocNoRef   = $aDataList['raItems']['FTXtdDocNoRef'];
    }else{
        $cAmtB4DisChg   = 0;
        $cXtdChg        = 0;
        $cXtdNetAfHD    = 0;
        $cXtdDocNoRef   = "";
    }
?>
<style type="text/css">
    .xWRPPFontBold{
        font-weight: bold !important;
        vertical-align: middle !important;
    }
    .xWRPPFontTotalAll{
        font-size: 22px !important;
        font-weight: bold !important;
        vertical-align: middle !important;
        margin-bottom: 0 !important;

    }
    .xWRPPTitilRC{
        font-size: 22px !important;
        font-weight: bold !important;
        vertical-align: middle !important;
        margin-bottom: 0 !important;
    }

    .xWRPPTDRCButtom{
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }

    .xWRPPButtomRC{
        font-size: 22px !important;
    }

    #otbRPPStep1Point3FooterTotal thead th{
        font-size: 22px !important;
    }

    .btn-outline-primary{color:#007bff;border-color:#007bff}
    .btn-outline-primary:hover{color:#fff;background-color:#007bff;border-color:#007bff}
    .btn-outline-primary.focus,
    .btn-outline-primary:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}
    .btn-outline-primary.disabled,
    .btn-outline-primary:disabled{color:#007bff;background-color:transparent}
    .btn-outline-primary:not(:disabled):not(.disabled).active,
    .btn-outline-primary:not(:disabled):not(.disabled):active,.show>
    .btn-outline-primary.dropdown-toggle{color:#fff;background-color:#007bff;border-color:#007bff}
    .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
    .btn-outline-primary:not(:disabled):not(.disabled):active:focus,.show>
    .btn-outline-primary.dropdown-toggle:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <input type="hidden" class="form-control" id="ohdRPPTotalAll"     name="ohdRPPTotalAll"   value="<?=@$cXtdNetAfHD;?>">
        <input type="hidden" class="form-control" id="ohdRPPTotalPayFT"   name="ohdRPPTotalPayFT" value="">
        <table id="otbRPPStep1Point3DocPdtAdvTableList" class="table table-striped">
            <thead>
                <th class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitlelist') ?></th>
                <th class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleDocPayOff') ?></th>
                <th class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleAmtPayOff') ?></th>
                <th class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleTotalAmt') ?></th>
            </thead>
            <tbody>
                <tr id="otrRPPTotalAmt">
                    <!-- จำนวนเงินรวม -->
                    <td class="text-left" ><label class="xWRPPFontBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelTotalAmt');?></label></td>
                    <td class="text-right"><label class="xWRPPFontBold">-</label></td>
                    <td class="text-right"><label class="xWRPPFontBold"><?=number_format(0.00,$nDecimal)?></label></td>
                    <td class="text-right"><label class="xWRPPFontBold" id="oliRPPTotalAmt"><?=number_format(@$cAmtB4DisChg,$nDecimal)?></label></td>
                </tr>
                <tr id="otrRPPXshTotal">
                    <!-- มูลค่าก่อนภาษี ณ ที่จ่าย -->
                    <td class="text-left" ><label class="xWRPPFontBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelXshTotal');?></label></td>
                    <td class="text-right"><label class="xWRPPFontBold">-</label></td>
                    <td class="text-right"><label class="xWRPPFontBold"><?=number_format(0.00,$nDecimal)?></label></td>
                    <td class="text-right"><label class="xWRPPFontBold" id="oliRPPXshTotal"><?=number_format(@$cAmtB4DisChg,$nDecimal)?></label></td>
                </tr>
                <tr id="otrRPPXshWht">
                    <!-- หักภาษี ณ ที่จ่าย -->
                    <td class="text-left" ><label class="xWRPPFontBold"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelXshWht');?></label></td>
                    <td class="text-right">
                        <div class="input-group">
                            <input type="text" class="form-control xCNHide" id="oetRPPWhtCode" name="oetRPPWhtCode" maxlength="5" value="<?=@$cXtdDocNoRef;?>">
                            <input 
                                type="text"
                                class="form-control xWPointerEventNone"
                                id="oetRPPWhtName"
                                name="oetRPPWhtName"
                                placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelXshWht');?>"
                                lavudate-label="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelXshWht');?>"
                                value="<?=@$cXtdDocNoRef;?>" 
                                readonly=""
                            >
                            <span class="xWConditionSearchPdt input-group-btn">
                                <button id="obtRPPBrowseWht" type="button" class=" btn xCNBtnBrowseAddOn">
                                    <img class="xCNIconFind">
                                </button>
                            </span>
                        </div>
                    </td>
                    <td class="text-right"><label class="xWRPPFontBold" id="oliRPPXshWhtChg"><?=number_format(@$cXtdChg,$nDecimal)?></label></td>
                    <td class="text-right"><label class="xWRPPFontBold" id="oliRPPXshWhtNet"><?=number_format(@$cXtdNetAfHD,$nDecimal)?></label></td>
                </tr>
                <tr id="otrRPPTotalAll">
                    <td colspan="3" class="text-left">
                        <label class="xWRPPFontTotalAll"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelTotalAll');?></label>
                    </td>
                    <td class="text-right"><label class="xWRPPFontTotalAll" id="oliRPPTotalAll"><?=number_format(@$cXtdNetAfHD,$nDecimal)?></label></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbRPPStep1Point3RCV" class="table table-striped">
            <tbody>
                <tr id="otrRPPRCButtomTitle">
                    <td colspan="99"><label class="xWRPPTitilRC"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitleSeleteRCV');?></label></td>
                </tr>
                <tr id="otrRPPRCButtomData">
                    <?php if($aDataRCV['rtCode'] == '1'): ?>
                        <?php if (FCNnHSizeOf($aDataRCV['raItems']) != 0): ?>
                            <?php foreach ($aDataRCV['raItems'] as $nKey => $aDataRcvVal): ?>
                                <?php 
                                        $tRcvCode   = (!empty($aDataRcvVal['FTRcvCode']))? $aDataRcvVal['FTRcvCode'] : 'N/A';
                                        $tRcvName   = (!empty($aDataRcvVal['FTRcvName']))? $aDataRcvVal['FTRcvName'] : 'N/A';
                                        ?>
                                    <td nowrap class="xWRPPTDRCButtom" style="width: 20%;">
                                        <button type="button" class="btn btn-outline-primary btn-lg xWRPPButtomRC" data-value="<?=@$tRcvCode;?>" data-name="<?=@$tRcvName;?>"style="width: 100%;"><?=@$tRcvName;?></button>
                                    </td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbRPPStep1Point3RCVList" class="table table-striped">
            <thead>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVNo');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVPaidby');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVDocRef');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVDatePaid');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVBankName');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVBankBch');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVPayAmt');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVChargeBal');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVTotalAmt');?></th>
                <th nowrap class="xCNTextBold text-center"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTBRCVManage');?></th>
            </thead>
            <tbody id="otbRPPRCVListTable">
                <tr>
                    <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="table-responsive">
        <table id="otbRPPStep1Point3FooterTotal" class="table table-striped" style="margin:0;">
            <thead>
                <tr>
                    <th class="xCNTextBold text-left"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFooterTotalPay');?></th>
                    <th class="xCNTextBold text-right" id="othRPPTotalPayFT"><?=number_format(@$cXtdNetAfHD,$nDecimal)?></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/html" id="oscRPPRowNoData">
    <tr>
        <td class='text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable' colspan='100%'><?= language('common/main/main', 'tCMNNotFoundData');?></td>
    </tr>                 
</script>


<script type="text/javascript">
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tSesUsrLevel    = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    
    // Function : Option Browse Document Wht
    // Creator  : 01/04/2022 Wasin
    var oDocWhtOption   = function(poReturnInput) {
        let tInputReturnCode    = poReturnInput.tReturnInputCode;
        let tInputReturnName    = poReturnInput.tReturnInputName;
        let tNextFuncName       = poReturnInput.tNextFuncName;
        let aDataReturn         = poReturnInput.aDataReturn;

        let tBchMulti           = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        let tWhereCondition     = '';
        if (tSesUsrLevel != "HQ") {
            // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
            tWhereCondition += " AND TPSTWhTaxHD.FTBchCode IN ("+tBchMulti+") ";
        }
        tWhereCondition += " AND ( TPSTWhTaxHDDocRef.FTXshDocNo IS NULL OR TPSTWhTaxHDDocRef.FTXshRefType <> 2 ) ";
        // Data Return
        let oBrowseWhtDoc   = {
            Title   : ['document/receiptpurchasepmt/receiptpurchasepmt','ใบหักภาษี ณ.ที่จ่าย'],
            Table   : {
                Master  : 'TPSTWhTaxHD',
                PK      : 'FTXshDocNo'
            },
            Join    : {
                Table : ['TCNMBranch_L','TPSTWhTaxHDDocRef'],
                On: [
                    'TPSTWhTaxHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID =' + nLangEdits,
                    'TPSTWhTaxHD.FTBchCode = TPSTWhTaxHDDocRef.FTBchCode AND TPSTWhTaxHD.FTXshDocNo = TPSTWhTaxHDDocRef.FTXshDocNo '
                ]
            },
            Where   : {
                Condition   : [
                    " AND TPSTWhTaxHD.FTXshStaDoc = '1' AND TPSTWhTaxHD.FTXshStaApv = '1' AND TPSTWhTaxHD.FNXshStaDocAct = 1 ",
                    tWhereCondition
                ]
            },
            GrideView   : {
                ColumnPathLang      : 'document/purchaseorder/purchaseorder',
                ColumnKeyLang       : ['สาขา','เลขที่เอกสาร','วันที่เอกสาร','ยอดรวม'],
                ColumnsSize         : ['20%','50%','15%','15%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch_L.FTBchName', 'TPSTWhTaxHD.FTXshDocNo','TPSTWhTaxHD.FDXshDocDate','TPSTWhTaxHD.FCXshTotal'],
                DataColumnsFormat   : ['', '','Date:0',''],
                Perpage             : 10,
                OrderBy             : ['TPSTWhTaxHD.FDCreateOn DESC'],
            },
            CallBack    : {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TPSTWhTaxHD.FTXshDocNo"],
                Text: [tInputReturnName, "TPSTWhTaxHD.FTXshDocNo"]
            },
            NextFunc    : {
                FuncName    : tNextFuncName,
                ArgReturn   : aDataReturn
            },
            // DebugSQL: true,
        };
        return oBrowseWhtDoc;
    }
    $('#obtRPPBrowseWht').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPPDocWhtOption = undefined;
            oRPPDocWhtOption        = oDocWhtOption({
                'tReturnInputCode'  : 'oetRPPWhtCode',
                'tReturnInputName'  : 'oetRPPWhtName',
                'tNextFuncName'     : 'JSxRPPWhenSeletedDocWht',
                'aDataReturn'       : ['FTXshDocNo','FCXshTotal']
            });
            JCNxBrowseData('oRPPDocWhtOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxRPPWhenSeletedDocWht(paDataNextFunc){
        if(paDataNextFunc != undefined && paDataNextFunc != ""){
            var aDataNextFunc   = JSON.parse(paDataNextFunc);
            var tWhtDocNo       = aDataNextFunc[0];
            var tWhtTotal       = aDataNextFunc[1];
            $.ajax({
                type : "POST",
                url  : "docRPPEventUpdWhTaxHD",
                data : {
                    'tAGNCode'  : $('#oetRPPAgnCode').val(),
                    'tBCHCode'  : $('#oetRPPBchCode').val(),
                    'tRPPDocNo' : $('#ohdRPPDocNo').val(),
                    'tWhtDocNo' : tWhtDocNo,
                    'tWhtTotal' : tWhtTotal,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    let aReturnData = JSON.parse(oResult);
                    if(aReturnData['nStaEvent'] == '1'){
                        let cAmtB4DisChg    = aReturnData['cAmtB4DisChg'];
                        let cChgWhtaxHD     = aReturnData['cChgWhtaxHD'];
                        let cNetAfHD        = aReturnData['cNetAfHD'];
                        // Set Lable Value Net
                        $('#otbRPPStep1Point3DocPdtAdvTableList #oliRPPTotalAmt').text(cAmtB4DisChg);
                        $('#otbRPPStep1Point3DocPdtAdvTableList #oliRPPXshTotal').text(cAmtB4DisChg);
                        $('#otbRPPStep1Point3DocPdtAdvTableList #oliRPPXshWhtChg').text(cChgWhtaxHD);
                        $('#otbRPPStep1Point3DocPdtAdvTableList #oliRPPXshWhtNet').text(cNetAfHD);
                        $('#otbRPPStep1Point3DocPdtAdvTableList #oliRPPTotalAll').text(cNetAfHD);
                    }else{
                        var tMessageError   = aReturnData['tStaMessg'];
                        FSvCMNSetMsgErrorDialog(tMessageError);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            })
        }
    }

    // Function : Event Click ประเภทการชำระ 
    // Creator  : 01/04/2022 Wasin
    $('.xWRPPButtomRC').unbind().click(function(){
        var tRcvCode        = $(this).data('value');
        var nFindRcvInTable = $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable #otrRPPRowInputRCV'+tRcvCode).length;
        if(nFindRcvInTable == 0){
            FSxRPPAddInputRecivePayment(this);
        }else{
            var tMessage   = '<?=language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPAlertHaveRCVInProgram');?>';
            FSvCMNSetMsgErrorDialog(tMessage);
        }
    });

    // Function : Event Click ประเภทการชำระ 
    // Creator  : 01/04/2022 Wasin
    function FSxRPPAddInputRecivePayment(evn){
        let tRPPRcvCode     = $(evn).data('value');
        let tRPPRcvName     = $(evn).data('name');
        let tRPPDocNo       = $('#ohdRPPDocNo').val();
        let tCountInputAll  = $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable .otrInputRCV').length;
        $.ajax({
            type    : "POST",
            url     : "docRPPEventAddInputRCV",
            data    : {
                'tAGNCode'      : $('#oetRPPAgnCode').val(),
                'tBCHCode'      : $('#oetRPPBchCode').val(),
                'tRPPDocNo'     : tRPPDocNo,
                'tRPPRcvCode'   : tRPPRcvCode,
                'tRPPRcvName'   : tRPPRcvName,
                'tRcvSeq'       : tCountInputAll
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var aReturnData = JSON.parse(oResult);
                if(aReturnData['nStaEvent'] == '1'){
                    // ลบ TR No Data
                    $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable .xCNTextNotfoundDataPdtTable').parent().remove();
                    // เพิ่ม Append Row ประเภทการชำระ
                    $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable').append(aReturnData['tViewDataTable']);

                    JCNxCloseLoading();
                    // ============= Load Default JAVASCRIPT =============
                    $('.selectpicker').selectpicker('refresh');
                    $('.xCNDatePicker').datepicker({
                        format: "yyyy-mm-dd",
                        todayHighlight: true,
                        enableOnReadonly: false,
                        disableTouchKeyboard: true,
                        autoclose: true
                    });
                    $('.xCNTimePicker').datetimepicker({
                        format: 'HH:mm:ss'
                    });
                    // ====================================================
                    // Set ปุ่ม Default Input Edit In Line
                    JSxRPPSetDefEditInLineStep1P3(evn);

                    // เช็คข้อมูลการชำระและเปิดปุ่ม Appove
                    JSxRPPCheckRCInTableOpenBtnAppove();
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }            
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function : Set ปุ่ม Default 
    // Creator  : 07/04/2022 Wasin
    function JSxRPPSetDefEditInLineStep1P3(evn){
        let tRPPRcvCode = $(evn).data('value');
        switch(tRPPRcvCode){
            case '001':
                $('#oimRPPBrowseBank'+tRPPRcvCode).attr("disabled","disabled");
                $('#oetRPPRcvBankBranch'+tRPPRcvCode).attr("disabled","disabled");
            break
            case '058':
                $('#oimRPPBrowseBank'+tRPPRcvCode).attr("disabled","disabled");
            break;
            default:
                $('#oimRPPBrowseBank'+tRPPRcvCode).removeAttr("disabled");
        }
    }

    // Function : Event Delete ประเภทการชำระ 
    // Creator  : 01/04/2022 Wasin
    function JSxRPPEventDelMNGRCV(evn){
        let tCountInputRcv  = $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable .otrInputRCV').length;
        $(evn).parents('.otrInputRCV').remove();
        if(tCountInputRcv == 1){
            var tHtmlNoData = $('#oscRPPRowNoData').html();
            $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable').html(tHtmlNoData);
        }
        // Loop Table Reset No.
        var nRowNo  = 1;
        $("#otbRPPStep1Point3RCVList #otbRPPRCVListTable .otrInputRCV" ).each(function(index){
            $(this).attr('data-seqno',nRowNo);
            $(this).find("td:first").text(nRowNo);
            nRowNo++;
        });
        JSxRPPCheckRCInTableOpenBtnAppove();
    }

    // Function : Event Delete ประเภทการชำระ 
    // Creator  : 01/04/2022 Wasin
    function JSxRPPRcvCalcPaymentChg(evn){
        let tRPPRcvCode     = $(evn).parents('.otrInputRCV').data('rcvcode');
        let cRPPRcvFAmt     = $(evn).parents('.otrInputRCV').find('#oetRPPRcvFAmt'+tRPPRcvCode).val();
        let cRPPRcvFChg     = $(evn).parents('.otrInputRCV').find('#oetRPPRcvXrcChg'+tRPPRcvCode).val();
        let cRPPRcvXrcNet   = 0;
        if(cRPPRcvFAmt != '' && cRPPRcvFChg != ''){
            cRPPRcvXrcNet   += parseFloat(cRPPRcvFAmt);
            cRPPRcvXrcNet   += parseFloat(cRPPRcvFChg);
            $('#oetRPPRcvXrcNet'+tRPPRcvCode).val(cRPPRcvXrcNet.toFixed(2));
        }
        JSxRPPCheckRCInTableOpenBtnAppove();
    }


    // Function : เช็คข้อมูลการชำระและเปิดปุ่ม Appove
    // Creator  : 01/04/2022 Wasin
    function JSxRPPCheckRCInTableOpenBtnAppove(){
        let nCountRcvAppove = $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable .otrInputRCV').length;
        if(nCountRcvAppove > 0){
            let tRPPHavePdtChk  = $('#ohdConfirmRPPInsertPDT').val();
            let tRPPTotalAll    = parseFloat($('#ohdRPPTotalAll').val());
            // วนลูปเข็คยอดรวมการชำระ
            let cPaymentValue   = 0;
            $('#otbRPPStep1Point3RCVList #otbRPPRCVListTable .otrInputRCV').each(function(index) {
                let tRcvCode    = $(this).data('rcvcode');
                let tXrcNetVal  = $('#oetRPPRcvXrcNet'+tRcvCode).val();
                cPaymentValue   += parseFloat(tXrcNetVal);
            });
            if(tRPPHavePdtChk != "" && tRPPTotalAll == cPaymentValue){
                $('#obtRPPSaveAndApvDoc').show();
            }else{
                $('#obtRPPSaveAndApvDoc').hide();
            }
        }else{
            $('#obtRPPSaveAndApvDoc').hide();
        }
    }

</script>