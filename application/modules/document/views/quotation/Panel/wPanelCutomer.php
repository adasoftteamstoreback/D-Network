<!-- Panel ลูกค้า-->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQPanelCustomer'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelCustomer" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelCustomer" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <?php
                $tQTDataInputBchCode   = "";
                $tQTDataInputBchName   = "";
                if($tTQRoute  == "docQuotationEventAdd"){
                    $tQTDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tQTDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tQTDataInputBchCode    = @$tTQFTBchCode;
                    $tQTDataInputBchName    = @$tTQFTBchName;
                    $tBrowseBchDisabled     = 'disabled';
                }
            ?>
            <!--สาขา-->
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtTQBrowseBranch').attr('disabled',true);
                    }
                }
            </script>

            <!-- สาขา -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdTQBchCode" name="ohdTQBchCode" value="<?= @$tQTDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetTQBchName" name="oetTQBchName" value="<?= @$tQTDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtTQBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- ลูกค้า -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCustomerName'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQCustomerName'); ?>" id="oetPanel_CustomerName" name="oetPanel_CustomerName" readonly value='<?=@$tTQCusName?>'>
            </div>

            <!-- เลขประจำตัวผู้เสียภาษี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('report/report/report', 'tRptTaxSalePosTaxID'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tRptTaxSalePosTaxID'); ?>" id="oetPanel_CustomerTaxID" name="oetPanel_CustomerTaxID" readonly value='<?=@$tTQCardID?>'>
            </div>

            <!-- เบอร์โทรศัพท์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTelephone'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQTelephone'); ?>" id="oetPanel_CustomerTelephone" name="oetPanel_CustomerTelephone" maxlength="20" value="<?= @$tTQTelephone; ?>" readonly>
            </div>

            <!-- อีเมล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('customer/customer/customer', 'tCSTEmail'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tCSTEmail'); ?>" id="oetPanel_CustomerEmail" name="oetPanel_CustomerEmail" maxlength="100" value="<?=@$tTQEmail;?>" readonly>
            </div>

            <!-- ที่อยู่-->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAddress'); ?></label>
                <textarea readonly class="form-control xCNInputWithoutSpc" placeholder="<?= language('document/quotation/quotation', 'tTQAddress'); ?>" id="oetPanel_CustomerAddress" name="oetPanel_CustomerAddress" maxlength="200"><?= @$tTQAddress ?></textarea>
            </div>


        </div>
    </div>
</div>