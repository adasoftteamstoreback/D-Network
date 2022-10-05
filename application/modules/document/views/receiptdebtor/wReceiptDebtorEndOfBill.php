<style>
    #odvRCBRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvRCBRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvRCBRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
        margin-bottom: -10px;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }
</style>
<div class="row p-t-10" id="odvRCBRowDataEndOfBill" >
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading mark-font" id="odvRCBDataTextBath"><?=$tXshGndText?></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/receiptdebtor/receiptdebtor', 'tRCBRemark'); ?></label>
                        <textarea id="otaRemark2" class="xWETaxDisabled xWETaxEnabledOniNetError" rows="4" style="resize: none;" readonly><?=$tXshRmk?></textarea>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?= language('document/receiptdebtor/receiptdebtor', 'tRCBRemark2'); ?></label>
                        <textarea id="otaRemark2" class="xWETaxDisabled xWETaxEnabledOniNetError" rows="4" style="resize: none;" readonly><?=$tXshCond?></textarea>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left mark-font"><?=language('document/receiptdebtor/receiptdebtor','tRCBTotal');?></label>
                        <label class="pull-right mark-font" id="olbRCBSumFCXtdNet"><?=number_format($nXshTotal,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBWht');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdAmt"><?=number_format($nXshWht,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBAfWht');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdNetAfHD"><?=number_format($nXshAfWht,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBInterest');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdVat"><?=number_format($nXshInterest,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBDisc');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdAmt"><?=number_format($nXshDisc,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBAfDisc');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdAmt"><?=number_format($nXshAfDisc,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBAmt');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdNetAfHD"><?=number_format($nXshAmt,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBPay');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdVat"><?=number_format($nXshPay,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left"><?=language('document/receiptdebtor/receiptdebtor','tRCBChgCredit');?></label>
                        <label class="pull-right" id="olbRCBSumFCXtdVat"><?=number_format($nXshChgCredit,$nOptDecimalShow)?></label>
                        <div class="clearfix"></div>
                    </li>
                </ul>
            </div>
            <div class="panel-heading">
                <label class="pull-left mark-font"><?=language('document/receiptdebtor/receiptdebtor','tRCBGnd');?></label>
                <label class="pull-right mark-font" id="olbRCBCalFCXphGrand"><?=number_format($nXshGnd,$nOptDecimalShow)?></label>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>