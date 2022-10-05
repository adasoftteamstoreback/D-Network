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
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetPanel_SplName" name="oetPanel_SplName" placeholder="<?= language('supplier\supplier\supplier', 'tName'); ?>" readonly value='<?=@$tIVSPLName?>' >
            </div>

            <!--สถานะ local-->
            <input type="hidden" id="ohdIVSPLStaLocal" name="ohdIVSPLStaLocal" value='<?=@$tIVSPLStaLocal?>'>

            <!-- ประเภทภาษี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQVatInOrEx');?></label>
                <?php
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
                ?>
                <select class="selectpicker form-control" id="ocmIVfoVatInOrEx" name="ocmIVfoVatInOrEx" maxlength="1">
                    <option value="1" <?= @$tOptionVatIn;?>><?= language('document/quotation/quotation','tTQVatInclusive');?></option>
                    <option value="2" <?= @$tOptionVatEx;?>><?= language('document/quotation/quotation','tTQVatExclusive');?></option>
                </select>
            </div>

            <!-- การชำระเงิน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid');?></label>
                <select class="selectpicker form-control" id="ocmIVDstPaid" name="ocmIVDstPaid" maxlength="1">
                    <option value="1" <?php if(@$tIVDstPaid=='1'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid1');?></option>
                    <option value="2" <?php if(@$tIVDstPaid=='2'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid2');?></option>
                </select>
            </div>

            <!-- ประเภทการชำระ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQPayment');?></label>
                <select class="selectpicker form-control" id="ocmIVPaymentType" name="ocmIVPaymentType" maxlength="1">
                    <option value="1" <?php if(@$tIVCshorCrd=='1'){ echo 'selected'; } ?>><?= language('document/quotation/quotation','tTQPaymentType1');?></option>
                    <option value="2" <?php if(@$tIVCshorCrd=='2'){ echo 'selected'; } ?> ><?= language('document/quotation/quotation','tTQPaymentType2');?></option>
                </select>
            </div>

            <!-- ระยะเครดิต -->
            <div class="form-group xCNPanel_CreditTerm" style="display:none;">
                <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm2'); ?></label>
                <input style="text-align: right;" maxlength="5" autocomplete="off" value="<?=@$tIVCrTerm?>" type="text" class="form-control xCNInputNumericWithDecimal" id="oetIVCreditTerm" name="oetIVCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
            </div>

            <!-- วันที่มีผล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQEffectiveDate'); ?></label>
                <div class="input-group">
                    <input type="hidden" id="oetIVEffectiveDate_Old" value="<?=@$tIVAffectDate?>" >
                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetIVEffectiveDate" name="oetIVEffectiveDate" placeholder="YYYY-MM-DD" autocomplete="off" value="<?= @$tIVAffectDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVEffectiveDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>

            <!-- สกุลเงิน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('payment/rate/rate', 'tRTETitle'); ?></label>
                <div class="input-group" style="width:100%;">
                    <input type="text" class="input100 xCNHide" id="ohdIVRateCode" name="ohdIVRateCode" value="<?= @$tIVRateCode; ?>">
                    <input class="form-control xWPointerEventNone" type="text" id="oetIVRateName" name="oetIVRateName" value="<?= @$tIVRateName; ?>" readonly placeholder="<?= language('payment/rate/rate', 'tRTETitle'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVBrowseRate" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- ที่อยู่สำหรับจัดส่ง -->
            <div class="row xCNIVFrmBrowseAddrAdd">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <input type="hidden" id="ohdIVFrmShipAdd" name="ohdIVFrmShipAdd" value="<?=@$nFNXphShipAdd?>">

                            <input type="hidden" id="ohdIVShipAddSeqNo" name="ohdIVShipAddSeqNo" value="<?=@$tSHIP_FNAddSeqNo?>">
                            <input type="hidden" id="ohdIVShipAddTaxNo" name="ohdIVShipAddTaxNo" value="<?=@$tSHIP_FTAddTaxNo?>">
                            <input type="hidden" id="ohdIVShipAddName" name="ohdIVShipAddName" value="<?=@$tSHIP_FTAddName?>">
                            <input type="hidden" id="ohdIVShipTel" name="ohdIVShipTel" value="<?=@$tSHIP_FTAddTel?>">
                            <input type="hidden" id="ohdIVShipFax" name="ohdIVShipFax" value="<?=@$tSHIP_FTAddFax?>">

                            <!-- Addr Version 1 -->
                            <input type="hidden" id="ohdIVShipAddV1No" name="ohdIVShipAddV1No" value="<?=@$tSHIP_FTAddV1No?>">
                            <input type="hidden" id="ohdIVShipV1Soi" name="ohdIVShipV1Soi" value="<?=@$tSHIP_FTAddV1Soi?>">
                            <input type="hidden" id="ohdIVShipV1Village" name="ohdIVShipV1Village" value="<?=@$tSHIP_FTAddV1Village?>">
                            <input type="hidden" id="ohdIVShipV1Road" name="ohdIVShipV1Road" value="<?=@$tSHIP_FTAddV1Road?>">
                            <input type="hidden" id="ohdIVShipV1SubDistrict" name="ohdIVShipV1SubDistrict" value="<?=@$tSHIP_FTSudName?>">
                            <input type="hidden" id="ohdIVShipV1District" name="ohdIVShipV1District" value="<?=@$tSHIP_FTDstName?>">
                            <input type="hidden" id="ohdIVShipV1Province" name="ohdIVShipV1Province" value="<?=@$tSHIP_FTPvnName?>">
                            <input type="hidden" id="ohdIVShipV1PostCode" name="ohdIVShipV1PostCode" value="<?=@$tSHIP_FTAddV1PostCode?>">

                            <!-- Addr Version 2 -->
                            <input type="hidden" id="ohdIVShipAddV2Desc1" name="ohdIVShipAddV2Desc1" value="<?=@$tSHIP_FTAddV2Desc1?>">
                            <input type="hidden" id="ohdIVShipAddV2Desc2" name="ohdIVShipAddV2Desc2" value="<?=@$tSHIP_FTAddV2Desc2?>">

                            <button type="button" id="obtIVFrmBrowseAddrAdd" class="btn xCNBTNSubSave" style="width:100%;" data-codebrowse="1">
                                <?=language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoShipAddress');?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    //เปลี่ยนประเภทการชำระ
    if('<?=@$tIVCshorCrd ?>' == '2'){
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