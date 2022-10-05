<!-- Panel ลูกค้า-->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQPanelCustomer'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelCustomer" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
            <input type="hidden" id="ocmDPSFrmSplInfoVatInOrEx" name="ocmDPSFrmSplInfoVatInOrEx" value="<?= $tDPSVatInOrEx ?>">
        </a>
    </div>
    <div id="odvPanelCustomer" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ลูกค้า -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCustomerName'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQCustomerName'); ?>" id="oetPanel_CustomerName" name="oetPanel_CustomerName" value="<?= @$tDPSCstName; ?>" readonly>
            </div>

            <!-- เลขประจำตัวผู้เสียภาษี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('report/report/report', 'tRptTaxSalePosTaxID'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tRptTaxSalePosTaxID'); ?>" id="oetPanel_CustomerTaxID" name="oetPanel_CustomerTaxID" value="<?= @$tDPSCstCardID; ?>" readonly>
            </div>

            <!-- เบอร์โทรศัพท์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTelephone'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQTelephone'); ?>" id="oetPanel_CustomerTelephone" name="oetPanel_CustomerTelephone" maxlength="20" value="<?= @$tDPSTelephone; ?>" readonly>
            </div>

            <!-- อีเมล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('customer/customer/customer', 'tCSTEmail'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tCSTEmail'); ?>" id="oetPanel_CustomerEmail" name="oetPanel_CustomerEmail" maxlength="100" value="" readonly>
            </div>

            <!-- ที่อยู่-->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAddress'); ?></label>
                <textarea readonly class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQAddress'); ?>" id="oetPanel_CustomerAddress" name="oetPanel_CustomerAddress" maxlength="200"><?= @$tDPSAddress ?></textarea>
            </div>

        </div>
    </div>
</div>