<style>
    #odvAPDEndOfBill .panel-heading{
            padding-top: 10px !important;
            padding-bottom: 10px !important;
    }
    #odvAPDEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvAPDEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<div class="row" id="odvAPDEndOfBill">
    <div class="col-lg-6">
        <span class="xCNHide" id="ospAPDCalEndOfBillNonePdt"></span>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left mark-font"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDNote') ?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <!-- หมายเหตุ -->
                <div class="form-group" style="padding: 10px 10px 0px 10px;">
                    <textarea
                        class="form-control"
                        id="otaAPDXphRmk"
                        name="otaAPDXphRmk"
                        ><?php echo $tXphRmk; ?></textarea>
                </div>
                <!-- หมายเหตุ -->
            </div>
            <div class="panel-heading">
                <div class="pull-left mark-font"><?php echo language('document/document/document', 'tDocVat') ?></div>
                <div class="pull-right mark-font"><?php echo language('document/document/document', 'tDocTaxAmount') ?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ul class="list-group" id="oulAPDListVat"></ul>
                <ul id="oulAPDListVatNonePdt">
                    <li><label class="pull-left" id="olbAPDVatrate"></label><label class="pull-right" id="oblAPDSumVat"></label><div class="clearfix"></div></li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?php echo language('document/document/document', 'tDocTotalVat') ?></label>
                <label class="pull-right mark-font" id="olbAPDVatSum">0.00</label>
                <input type="hidden" id="olbCrdSumFCXtdNetAlwDis"></label>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvAPDTextBath"></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?php echo language('document/document/document', 'tDocTotalCash') ?></label>
                        <label class="pull-right mark-font" id="olbAPDSumFCXtdNet">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <?php if($bIsDocTypeHavePdt) { ?>
                        <li class="list-group-item">
                            <label class="pull-left"><?php echo language('document/document/document', 'tDocDisChg') ?>
                                <?php if(empty($tStaApv) && $tStaDoc != 3) { ?>
                                <button 
                                    class="xCNBTNPrimeryDisChgPlus" 
                                    onclick="JCNvAPDDisChagHD(this)" 
                                    type="button" 
                                    style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                                <?php } ?>
                            </label>
                            <input type="hidden" id="ohdAPDHiddenDisChgHD" />
                            <label class="pull-left" style="margin-left: 5px;" id="olbAPDDisChgHD"></label>
                            <label class="pull-right" id="olbAPDSumFCXtdAmt">0.00</label>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <label class="pull-left"><?php echo language('document/document/document', 'tDocTotalDisChg') ?></label>
                            <label class="pull-right" id="olbAPDSumFCXtdNetAfHD">0.00</label>
                            <div class="clearfix"></div>
                        </li>
                    <?php } ?>
                    <li class="list-group-item">
                        <label class="pull-left"><?php echo language('document/document/document', 'tDocTotalVat') ?></label>
                        <label class="pull-right" id="olbAPDSumFCXtdVat">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?php echo language('document/document/document', 'tDocTotalAmount') ?></label>
                <label class="pull-right mark-font" id="olbAPDCalFCXphGrand">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        
    });

    //พวกตัวเลขใส่ comma ให้มัน
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }


    /**
     * Functionality : Display End Of Bill Calc
     * Parameters : poParams
     * Creator : 04/03/2022 Wasin
     * Last Modified : -
     * Return : -
     * Return Type : -
    */
    function JSxAPDSetEndOfBill(poParams) {
        // console.log('JSxAPDSetEndOfBill');
        
        /*================ Left End Of Bill ========================*/
        // Text
        var tTextBath = poParams.tTextBath;
        $('#odvAPDTextBath').text(tTextBath);
        
        // รายการ vat
        var aVatItems = poParams.aEndOfBillVat.aItems;
        // console.log('aVatItems: ', aVatItems);
        var tVatList = "";
        for(var i=0; i<aVatItems.length; i++){
            var tVatRate    = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
            var tSumVat     = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?=$nOptDecimalShow?>) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?=$nOptDecimalShow?>);
            tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + numberWithCommas(parseFloat(tSumVat).toFixed(<?=$nOptDecimalShow?>)) + '</label><div class="clearfix"></div></li>';
        }
        $('#oulAPDListVat').html(tVatList);
        
        // ยอดรวมภาษีมูลค่าเพิ่ม
        var cSumVat = poParams.aEndOfBillVat.cVatSum;
        $('#olbAPDVatSum').text(cSumVat);
        
        /*================ Right End Of Bill ========================*/
        var cCalFCXphGrand = poParams.aEndOfBillCal.cCalFCXphGrand;
        var cSumFCXtdAmt = poParams.aEndOfBillCal.cSumFCXtdAmt;
        var cSumFCXtdNet = poParams.aEndOfBillCal.cSumFCXtdNet;
        var cSumFCXtdNetAfHD = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
        var cSumFCXtdVat = poParams.aEndOfBillCal.cSumFCXtdVat;
        var tDisChgTxt = poParams.aEndOfBillCal.tDisChgTxt;

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
        $('#olbAPDSumFCXtdNet').text(cSumFCXtdNet);
        // ลด/ชาร์จ
        $('#olbAPDSumFCXtdAmt').text(cSumFCXtdAmt);
        // ยอดรวมหลังลด/ชาร์จ
        $('#olbAPDSumFCXtdNetAfHD').text(cSumFCXtdNetAfHD);
        // ยอดรวมภาษีมูลค่าเพิ่ม
        $('#olbAPDSumFCXtdVat').text(cSumFCXtdVat);
        // จำนวนเงินรวมทั้งสิ้น
        $('#olbAPDCalFCXphGrand').text(cCalFCXphGrand);
        // Text
        $('#olbAPDDisChgHD').text(tTextDisChg);
        $('#ohdAPDHiddenDisChgHD').val(tDisChgTxt);
    }
    
    /**
     * Functionality: Save Pdt And Calculate Field
     * Parameters: Event Proporty
     * Creator : 04/03/2022 Wasin
     * Return:  Cpntroll input And Call Function Edit
     * Return Type: number
    */
    function JCNvAPDDisChagHD(event) {
        //หาราคาที่อนุญาตลดเท่านั้น - วัฒน์
        $.ajax({
            type    : "POST",
            url     : "GetPriceAlwDiscount",
            data    : { 'tDocno' : $('#oetAPDDocNo').val() , 'tBCHCode' : $('#oetAPDBchCode').val() },
            cache   : false,
            timeout : 0,
            success : function(oResult) {
                var aTotal = JSON.parse(oResult);
                cSumFCXtdNet = aTotal.nTotal;
                $('#olbCrdSumFCXtdNetAlwDis').val(cSumFCXtdNet);
            }
        });
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var oDisChgParams = {
                DisChgType: 'disChgHD'
            };
            JSxAPDOpenDisChgPanel(oDisChgParams);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }
</script>
