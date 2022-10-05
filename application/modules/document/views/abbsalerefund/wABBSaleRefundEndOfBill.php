<style>
    #odvABBRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvABBRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvABBRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<div class="row p-t-10" id="odvABBRowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvABBDataTextBath"></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left mark-font"><?=language('document/saleorder/saleorder','tSOTBVatRate');?></div>
                <div class="pull-right mark-font"><?=language('document/saleorder/saleorder','tSOTBAmountVat');?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ul class="list-group" id="oulABBDataListVat">
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?=language('document/saleorder/saleorder','tSOTBTotalValVat');?></label>
                <label class="pull-right mark-font" id="olbABBVatSum">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?=language('document/saleorder/saleorder','tSOTBSumFCXtdNet');?></label>
                        <label class="pull-right mark-font" id="olbABBSumFCXtdNet">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/saleorder/saleorder','tSOTBDisChg');?></label>
                        <label class="pull-left" style="margin-left: 5px;" id="olbABBDisChgHD"></label>
                        <label class="pull-right" id="olbABBSumFCXtdAmt">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/saleorder/saleorder','tSOTBSumFCXtdNetAfHD');?></label>
                        <label class="pull-right" id="olbABBSumFCXtdNetAfHD">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/saleorder/saleorder','tSOTBSumFCXtdVat');?></label>
                        <label class="pull-right" id="olbABBSumFCXtdVat">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?=language('document/saleorder/saleorder','tSOTBFCXphGrand');?></label>
                <label class="pull-right mark-font" id="olbABBCalFCXphGrand">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var tMsgVatDataNotFound = '<?=language('common/main/main','tCMNNotFoundData')?>';


    //Set Data Value End Of Bile
    function JSxABBEventSetEndOfBill(poParams){
            // Set Text Bath
            var tTextBath   = poParams.tTextBath;
            $('#odvABBDataTextBath').text(tTextBath);

            // รายการ vat
            var aVatItems   = poParams.aEndOfBillVat.aItems;
            var tVatList    = "";
            if(aVatItems.length > 0){
                for(var i = 0; i < aVatItems.length; i++){
                    var tVatRate = parseFloat(aVatItems[i]['FCXtdVatRate']).toFixed(0);
                    var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(0) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                    var tSumVat = parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow;?>) == 0 ? '0.00' : parseFloat(aVatItems[i]['FCXtdVat']).toFixed(<?php echo $nOptDecimalShow?>);
                    tVatList += '<li class="list-group-item"><label class="pull-left">'+ tVatRate + '%</label><label class="pull-right">' + tSumVat + '</label><div class="clearfix"></div></li>';
                }
            }
            $('#oulABBDataListVat').html(tVatList);
            
            // ยอดรวมภาษีมูลค่าเพิ่ม
            var cSumVat     = poParams.aEndOfBillVat.cVatSum;
            $('#olbABBVatSum').text(cSumVat);

            /* ================================================= Right End Of Bill ================================================ */
            var cCalFCXphGrand      = poParams.aEndOfBillCal.cCalFCXphGrand;
            var cSumFCXtdAmt        = poParams.aEndOfBillCal.cSumFCXtdAmt;
            var cSumFCXtdNet        = poParams.aEndOfBillCal.cSumFCXtdNet;
            var cSumFCXtdNetAfHD    = poParams.aEndOfBillCal.cSumFCXtdNetAfHD;
            var cSumFCXtdVat        = poParams.aEndOfBillCal.cSumFCXtdVat;
            var tDisChgTxt          = poParams.aEndOfBillCal.tDisChgTxt;
            
            // จำนวนเงินรวม
            $('#olbABBSumFCXtdNet').text(cSumFCXtdNet);
            // ลด/ชาร์จ
            $('#olbABBSumFCXtdAmt').text(cSumFCXtdAmt);
            // ยอดรวมหลังลด/ชาร์จ
            $('#olbABBSumFCXtdNetAfHD').text(cSumFCXtdNetAfHD);
            // ยอดรวมภาษีมูลค่าเพิ่ม
            $('#olbABBSumFCXtdVat').text(cSumFCXtdVat);
            // จำนวนเงินรวมทั้งสิ้น
            $('#olbABBCalFCXphGrand').text(cCalFCXphGrand);
            //จำนวนลด/ชาร์จ ท้ายบิล
            $('#olbABBDisChgHD').text(tDisChgTxt);
    }

 
</script>