<style>
    #odvPXRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvPXRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvPXRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<div class="row p-t-10" id="odvPXRowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <!-- <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvPXDataTextBath"></div>
        </div> -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left mark-font"><?php echo language('document/expenserecord/expenserecord','ภาษี & หมายเหตุ');?></div>
                <div class="pull-right mark-font"></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body" style="margin-top:15px;margin-bottom:15px;">
                <textarea
                    class="form-control"
                    id="otaPXFrmInfoOthRmk"
                    name="otaPXFrmInfoOthRmk"
                    rows="10"
                    maxlength="200"
                    style="resize: none;height:86px;"
                ></textarea>
            </div>
            <div class="panel-heading">
                <div class="pull-left mark-font"><?php echo language('document/expenserecord/expenserecord','tPXTBVatRate');?></div>
                <div class="pull-right mark-font"><?php echo language('document/expenserecord/expenserecord','tPXTBAmountVat');?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ul class="list-group" id="oulPXDataListVat">
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?php echo language('document/expenserecord/expenserecord','tPXTBTotalValVat');?></label>
                <label class="pull-right mark-font" id="olbPXVatSum">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvPXDataTextBath"></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?php echo language('document/expenserecord/expenserecord','tPXTBSumFCXtdNet');?></label>
                        <label class="pull-right mark-font" id="olbPXSumFCXtdNet">0.00</label>
                        <input type="hidden" id="olbPXSumFCXtdNetAlwDis"></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?php echo language('document/expenserecord/expenserecord','tPXTBDisChg');?>
                            <?php if(empty($tPXStaApv) && $tPXStaDoc != 3):?>
                                <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvPXMngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                            <?php endif; ?>
                        </label>
                        <label class="pull-left" style="margin-left: 5px;" id="olbPXDisChgHD"></label>
                        <input type="hidden" id="ohdPXHiddenDisChgHD" />
                        <label class="pull-right" id="olbPXSumFCXtdAmt">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?php echo language('document/expenserecord/expenserecord','tPXTBSumFCXtdNetAfHD');?></label>
                        <label class="pull-right" id="olbPXSumFCXtdNetAfHD">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?php echo language('document/expenserecord/expenserecord','tPXTBSumFCXtdVat');?></label>
                        <label class="pull-right" id="olbPXSumFCXtdVat">0.00</label>
                        <input type="hidden" name="ohdSumFCXtdVat" id="ohdSumFCXtdVat" value="0.00">
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?php echo language('document/expenserecord/expenserecord','tPXTBFCXphGrand');?></label>
                <label class="pull-right mark-font" id="olbPXCalFCXphGrand">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var tMsgVatDataNotFound = '<?php echo language('common/main/main','tCMNNotFoundData')?>';


    /**
        * Function: Set Data Value End Of Bile
        * Parameters: Document Type
        * Creator: 01/07/2019 wasin(Yoshi)
        * LastUpdate: -
        * Return: Set Value In Text From
        * ReturnType: None
    */
    function JSxPXSetFooterEndOfBill(poParams){
        /* ================================================= Left End Of Bill ================================================= */
            // Set Text Bath
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
                    tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' +  numberWithCommas(parseFloat(tSumVat).toFixed(<?php echo $nOptDecimalShow?>))  + '</label><div class="clearfix"></div></li>';
                }
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

            // จำนวนเงินรวม
            $('#olbPXSumFCXtdNet').text(cSumFCXtdNet);
            // ลด/ชาร์จ
            $('#olbPXSumFCXtdAmt').text(cSumFCXtdAmt);
            // ยอดรวมหลังลด/ชาร์จ
            $('#olbPXSumFCXtdNetAfHD').text(cSumFCXtdNetAfHD);
            // ยอดรวมภาษีมูลค่าเพิ่ม
            $('#olbPXSumFCXtdVat').text(cSumFCXtdVat);
            // จำนวนเงินรวมทั้งสิ้น
            $('#olbPXCalFCXphGrand').text(cCalFCXphGrand);
            //จำนวนลด/ชาร์จ ท้ายบิล
            $('#olbPXDisChgHD').text(tDisChgTxt);
            $('#ohdPXHiddenDisChgHD').val(tDisChgTxt);
        /* ==================================================================================================================== */
    }

    /**
        * Functionality: Save Discount And Chage Footer HD (ลดท้ายบิล)
        * Parameters: Event Proporty
        * Creator: 22/05/2019 Piya  
        * Return: Open Modal Discount And Change HD
        * Return Type: View
    */
    function JCNvPXMngDocDisChagHD(event){

        $.ajax({
            type    : "POST",
            url     : "GetPriceAlwDiscount",
            data    : { 'tDocno' : $('#oetPXDocNo').val() , 'tBCHCode' : $('#oetPXFrmBchCode').val() },
            cache   : false,
            timeout : 0,
            success : function(oResult) {
                var aTotal = JSON.parse(oResult);
                cSumFCXtdNet = aTotal.nTotal;
                $('#olbPXSumFCXtdNetAlwDis').val(cSumFCXtdNet);
            }
        });

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var oPXDisChgParams = {
                DisChgType: 'disChgHD'
            };
            JSxPXOpenDisChgPanel(oPXDisChgParams);
        }else{
            JCNxShowMsgSessionExpired();
        } 
    }
  
</script>