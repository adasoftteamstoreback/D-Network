<!-- Panel เงื่อนไขการชำระเงิน--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQConditionDoc'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTQCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvTQCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!-- ประเภทภาษี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQVatInOrEx');?></label>
                <?php
                    switch($tTQVatInOrEx){
                        case '1':
                            $tOptionVatIn   = "selected";
                            $tOptionVatEx   = "";
                        break;
                        case '2':
                            $tOptionVatIn   = "";
                            $tOptionVatEx   = "selected";
                        break;
                        default:
                            $tOptionVatIn   = "selected";
                            $tOptionVatEx   = "";
                    }
                ?>
                <select class="selectpicker form-control xCNSelectDisabledPicker" id="ocmTQfoVatInOrEx" name="ocmTQfoVatInOrEx" maxlength="1">
                    <option value="1" <?= @$tOptionVatIn;?>><?= language('document/quotation/quotation','tTQVatInclusive');?></option>
                    <option value="2" <?= @$tOptionVatEx;?>><?= language('document/quotation/quotation','tTQVatExclusive');?></option>
                </select>
            </div>

            <!-- ประเภทการชำระ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQPayment');?></label>
                <select class="selectpicker form-control xCNSelectDisabledPicker" id="ocmTQPaymentType" name="ocmTQPaymentType" maxlength="1">
                    <option value="1" <?php if(@$tTQCshorCrd=='1'){ echo 'selected'; } ?>><?= language('document/quotation/quotation','tTQPaymentType1');?></option>
                    <option value="2" <?php if(@$tTQCshorCrd=='2'){ echo 'selected'; } ?> ><?= language('document/quotation/quotation','tTQPaymentType2');?></option>
                </select>
            </div>

            <!-- ระยะเครดิต -->
            <div class="form-group xCNPanel_CreditTerm" style="display:none;">
                <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                <input style="text-align: right;" maxlength="5" autocomplete="off" value="<?=@$tTQCrTerm?>" type="text" class="form-control xCNInputNumericWithDecimal" id="oetQTCreditTerm" name="oetQTCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
            </div>

            <!-- วันที่มีผล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQEffectiveDate'); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetTQEffectiveDate" name="oetTQEffectiveDate" value="<?= @$tTQAffectDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                    <span class="input-group-btn">
                        <button id="obtTQEffectiveDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>

            <!-- สกุลเงิน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('payment/rate/rate', 'tRTETitle'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdQTRateCode" name="ohdQTRateCode" value="<?= @$tQTRateCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetQTRateName" name="oetQTRateName" value="<?= @$tQTRateName; ?>" readonly placeholder="<?= language('payment/rate/rate', 'tRTETitle'); ?>">
                    <span class="input-group-btn">
                        <button id="obtQTBrowseRate" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    //เปลี่ยนประเภทการชำระ
    $('#ocmTQPaymentType').on('change', function() {
        if(this.value == 1){
            $('.xCNPanel_CreditTerm').hide();
        }else{
            $('.xCNPanel_CreditTerm').show();
        }
    });

    //ถ้าเอกสารอนุมัติแล้ว หรือ ยกเลิก
    if('<?=@$tTQStaDoc?>' == 3 || '<?=@$tTQStaApv?>' == 1){
        $('.xCNSelectDisabledPicker').attr("disabled",true);
    }
</script>