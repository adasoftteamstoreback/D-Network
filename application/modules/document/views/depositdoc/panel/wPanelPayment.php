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
            <?php
                $tQTDataInputBchCode   = "";
                $tQTDataInputBchName   = "";
                if($tDPSRoute  == "dcmDPSEventAdd"){
                    $tQTDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                    $tQTDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                    $tBrowseBchDisabled     = '';
                }else{
                    $tQTDataInputBchCode    = @$tSOBchCode;
                    $tQTDataInputBchName    = @$tSOBchName;
                    $tBrowseBchDisabled     = 'disabled';
                    // $tBrowseBchDisabled     = '';
                }
            ?>
            <!--สาขา-->
            <script>
                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                if( tUsrLevel != "HQ" ){
                    //BCH - SHP
                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                    if(tBchCount < 2){
                        $('#obtDPSBrowseBranch').attr('disabled',true);
                    }
                }
            </script>

            <!-- สาขา -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQBranch'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdDPSBchCode" name="ohdDPSBchCode" value="<?= @$tQTDataInputBchCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetDPSBchName" name="oetDPSBchName" value="<?= @$tQTDataInputBchName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQBranch'); ?>">
                    <span class="input-group-btn">
                        <button id="obtDPSBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- ประเภทภาษี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQVatInOrEx');?></label>
                <?php
                    switch($tDPSVatInOrEx){
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
                <select class="selectpicker form-control" id="ocmVatInOrEx" name="ocmVatInOrEx" maxlength="1">
                    <option value="1" <?= @$tOptionVatIn;?>><?= language('document/quotation/quotation','tTQVatInclusive');?></option>
                    <option value="2" <?= @$tOptionVatEx;?>><?= language('document/quotation/quotation','tTQVatExclusive');?></option>
                </select>
            </div>
            <?php if (isset($tTQVatRateBySPL) && isset($tTQVatCodeBySPL)) { ?>
                <input type="hidden" id="ohdTQFrmSplVatRate" name="ohdTQFrmSplVatRate" value="<?=@$tTQVatRateBySPL?>">
                <input type="hidden" id="ohdTQFrmSplVatCode" name="ohdTQFrmSplVatCode" value="<?=@$tTQVatCodeBySPL?>">
            <?php }else{ ?>
                <input type="hidden" id="ohdTQFrmSplVatRate" name="ohdTQFrmSplVatRate" value="">
                <input type="hidden" id="ohdTQFrmSplVatCode" name="ohdTQFrmSplVatCode" value="">
            <?php } ?>

            <!-- ประเภทการชำระ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQPayment');?></label>
                <select class="selectpicker form-control" id="ocmTQPaymentType" name="ocmTQPaymentType" maxlength="1">
                    <option value="1" selected="true"><?= language('document/quotation/quotation','tTQPaymentType1');?></option>
                    <option value="2"><?= language('document/quotation/quotation','tTQPaymentType2');?></option>
                </select>
            </div>

            <!-- ระยะเครดิต -->
            <div class="form-group xCNPanel_CreditTerm" style="display:none;">
                <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                <input style="text-align: right;" maxlength="5" autocomplete="off" type="text" class="form-control xCNInputNumericWithDecimal" id="oetQTCreditTerm" name="oetQTCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
            </div>

            <!-- วันที่มีผล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQEffectiveDate'); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetTQEffectiveDate" name="oetTQEffectiveDate" value="<?= @$tDPSAffectDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                    <span class="input-group-btn">
                        <button id="obtTQEffectiveDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>

            <!-- สกุลเงิน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('payment/rate/rate', 'tRTETitle'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdDPSRateCode" name="ohdDPSRateCode" value="<?= @$tDPSRateCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetDPSRateName" name="oetDPSRateName" value="<?= @$tDPSRateName; ?>" readonly placeholder="<?= language('payment/rate/rate', 'tRTETitle'); ?>">
                    <span class="input-group-btn">
                        <button id="obtDPSBrowseRate" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseBchDisabled; ?>>
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
</script>