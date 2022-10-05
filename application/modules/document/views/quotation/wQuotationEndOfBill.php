<style>
    #odvTQRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvTQRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvTQRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>

<div class="row p-t-10" id="odvTQRowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvTQDataTextBath"></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBVatRate');?></div>
                <div class="pull-right mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBAmountVat');?></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ul class="list-group" id="oulTQDataListVat">
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBTotalValVat');?></label>
                <label class="pull-right mark-font" id="olbTQVatSum">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNet');?></label>
                        <label class="pull-right mark-font" id="olbTQSumFCXtdNet">0.00</label>
                        <input type="hidden" id="olbTQSumFCXtdNetAlwDis"></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBDisChg');?>
                            <?php if(empty($tTQStaApv) && $tTQStaApv != 3):?>
                                <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvQTMngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                            <?php endif; ?>
                        </label>
                        <label class="pull-left" style="margin-left: 5px;" id="olbTQDisChgHD"></label>
                        <input type="hidden" id="ohdTQHiddenDisChgHD" />
                        <input type="hidden" id="ohdTQDisChgHD" />
                        <label class="pull-right" id="olbTQSumFCXtdAmt">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNetAfHD');?></label>
                        <label class="pull-right" id="olbTQSumFCXtdNetAfHD">0.00</label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdVat');?></label>
                        <label class="pull-right" id="olbTQSumFCXtdVat">0.00</label>
                        <input type="hidden" name="ohdTQSumFCXtdVat" id="ohdTQSumFCXtdVat" value="0.00">
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBFCXphGrand');?></label>
                <label class="pull-right mark-font" id="olbTQCalFCXphGrand">0.00</label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>