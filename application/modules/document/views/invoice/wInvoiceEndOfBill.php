<style>
    #odvIVRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvIVRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvIVRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>

<div class="row p-t-10" id="odvIVRowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
   
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left mark-font"><?= language('document/depositdoc/depositdoc', 'tDPSVatAndRmk'); ?></div>
                <div class="clearfix"></div>
            </div>
            <div style='padding: 10px 10px 0px 10px;'>
                <!-- หมายเหตุ -->
                <div class="form-group">
                    <textarea class="form-control" id="otaIVRemark" name="otaIVRemark" maxlength="200"><?=@$tIVFTXphRmk;?></textarea>
                </div>
            </div>

            <div class="panel-heading">
                <div class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBVatRate');?></div>
                <div class="pull-right mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBAmountVat');?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ul class="list-group" id="oulIVDataListVat">
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBTotalValVat');?></label>
                <label class="pull-right mark-font" id="olbIVVatSum">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvIVDataTextBath"></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group" style="margin-bottom: 0;">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNet');?></label>
                        <label class="pull-right mark-font" id="olbIVSumFCXtdNet">0.00</label>
                        <input type="hidden" id="olbIVSumFCXtdNetAlwDis"></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBDisChg');?>
                            <?php if(empty($tIVStaApv) && $tIVStaApv != 3):?>
                                <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvIVMngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                            <?php endif; ?>
                        </label>
                        <label class="pull-left" style="margin-left: 5px;" id="olbIVDisChgHD"></label>
                        <input type="hidden" id="ohdIVHiddenDisChgHD" />
                        <label class="pull-right" id="olbIVSumFCXtdAmt">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNetAfHD');?></label>
                        <label class="pull-right" id="olbIVSumFCXtdNetAfHD">0.00</label>
                        <div class="clearfix"></div>
                    </li>

                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdVat');?></label>
                        <label class="pull-right" id="olbIVSumFCXtdVat">
                            <input 
                                type="text"
                                class="form-control xCNInputNumericWithDecimal text-right"
                                id="oetIVSumFCXtdVat"
                                name="oetIVSumFCXtdVat"
                                value="0.00"
                            >
                        </label>
                        <input type="hidden" name="ohdIVSumFCXtdVat" id="ohdIVSumFCXtdVat" value="0.00">
                        <div class="clearfix"></div>
                    </li>
                   
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBFCXphGrand');?></label>
                <label class="pull-right mark-font" id="olbIVCalFCXphGrand">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>