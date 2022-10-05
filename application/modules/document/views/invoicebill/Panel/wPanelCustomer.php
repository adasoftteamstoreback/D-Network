<!-- Panel ลูกค้า-->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'tIVTitlePanelSPL'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelSPL" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelSPL" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ผู้จำหน่าย -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('supplier\supplier\supplier', 'tName'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetPanel_SplName" name="oetPanel_SplName" placeholder="<?= language('supplier\supplier\supplier', 'tName'); ?>" readonly value='<?=@$tIVBSPLName?>' >
            </div>

            <!-- ที่อยู่ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/invoicebill/invoicebill','tIVBTitleAddress');?></label>
                <textarea
                    class="form-control xControlRmk xWConditionSearchPdt"
                    id="otaIVBAdress"
                    name="otaIVBAdress"
                    rows="10"
                    maxlength="200"
                    style="resize: none;height:86px;"
                    readonly
                ><?= @$tIVBAddress; ?></textarea>
            </div>


            <!-- ประเภทภาษี -->
            <div class="form-group">
                <!-- <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQVatInOrEx');?></label> -->
                <!-- <?php
                    switch($tIVVatInOrEx){
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
                ?> -->
                <!-- <select class="selectpicker form-control" id="ocmIVfoVatInOrEx" name="ocmIVfoVatInOrEx" maxlength="1">
                    <option value="1" <?= @$tOptionVatIn;?>><?= language('document/quotation/quotation','tTQVatInclusive');?></option>
                    <option value="2" <?= @$tOptionVatEx;?>><?= language('document/quotation/quotation','tTQVatExclusive');?></option>
                </select> -->
            </div>

            <!-- การชำระเงิน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid');?></label>
                <select class="selectpicker form-control" id="ocmIVDstPaid" name="ocmIVDstPaid" maxlength="1">
                    <option value="1" <?php if(@$tIVBDstPaid=='1'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid1');?></option>
                    <option value="2" <?php if(@$tIVBDstPaid=='2'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid2');?></option>
                </select>
            </div>

            <!-- ประเภทการชำระ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQPayment');?></label>
                <select class="selectpicker form-control" id="ocmIVPaymentType" name="ocmIVPaymentType" maxlength="1">
                    <option value="1" <?php if(@$tIVBCshorCrd=='1'){ echo 'selected'; } ?>><?= language('document/quotation/quotation','tTQPaymentType1');?></option>
                    <option value="2" <?php if(@$tIVBCshorCrd=='2'){ echo 'selected'; } ?> ><?= language('document/quotation/quotation','tTQPaymentType2');?></option>
                </select>
            </div>

            <!-- ระยะเครดิต -->
            <div class="form-group xCNPanel_CreditTerm">
                <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                <input style="text-align: right;" maxlength="5" autocomplete="off" value="<?=@$tIVBCrTerm?>" type="text" class="form-control xCNInputNumericWithDecimal" id="oetIVCreditTerm" name="oetIVCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
            </div>

            <!-- วันที่มีผล -->
            <!-- <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQEffectiveDate'); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetIVEffectiveDate" name="oetIVEffectiveDate" value="<?= @$tIVAffectDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVEffectiveDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div> -->
            
        </div>
    </div>
</div>

<script>
    //เปลี่ยนประเภทการชำระ
    if('<?=@$tIVBCshorCrd ?>' == '2'){
       $('.xCNPanel_CreditTerm').show();
    }

    $('#ocmIVPaymentType').on('change', function() {
        if(this.value == 1){
            $('.xCNPanel_CreditTerm').hide();
        }else{
            $('.xCNPanel_CreditTerm').show();
        }
    });
</script>