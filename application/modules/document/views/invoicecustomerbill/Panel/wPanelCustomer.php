<!-- Panel ลูกค้า-->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'tIVCTitlePanelCst'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelSPL" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelSPL" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ผู้จำหน่าย -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVCCustoner'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetPanel_CstName" name="oetPanel_CstName" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVCCustoner'); ?>" readonly value='<?=@$tIVCSPLName?>' >
            </div>

            <!-- ที่อยู่ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/invoicebill/invoicebill','tIVCTitleAddress');?></label>
                <textarea
                    class="form-control xControlRmk xWConditionSearchPdt"
                    id="otaIVCAdress"
                    name="otaIVCAdress"
                    rows="10"
                    maxlength="200"
                    style="resize: none;height:86px;"
                    readonly
                ><?= @$tIVCAddress; ?></textarea>
            </div>

            <!-- เบอร์โทร -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVCCustomerTell'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetPanel_CstTell" name="oetPanel_CstTell" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVCCustomerTell'); ?>" readonly value='<?=@$tIVCCstTel?>' >
            </div>
            
            <!-- อีเมล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVCCustomerMail'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetPanel_CstMail" name="oetPanel_CstMail" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVCCustomerMail'); ?>" readonly value='<?=@$tIVCCstEmail?>' >
            </div>

            
            <!-- ผู้ติดต่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleContack') ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetIVCCtrCode" name="oetIVCCtrCode" maxlength="5" value="<?= @$tIVCCTRCode ?>">
                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIVCCtrName" name="oetIVCCtrName" maxlength="100" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleContack') ?>" value="<?= @$tIVCCTRName ?>" readonly>
                    <span class="input-group-btn">
                        <button id="oimIVCBrowseCtr" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
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
            <div class="form-group xCNHide">
                <label class="xCNLabelFrm"><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid');?></label>
                <select class="selectpicker form-control" id="ocmIVDstPaid" name="ocmIVDstPaid" maxlength="1">
                    <option value="1" <?php if(@$tIVCDstPaid=='1'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid1');?></option>
                    <option value="2" <?php if(@$tIVCDstPaid=='2'){ echo 'selected'; } ?>><?= language('document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoDstPaid2');?></option>
                </select>
            </div>

            <!-- หมายเหตุ -->
            <!-- <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                <input class="" id="otaIVCFrmInfoOthRmk" name="otaIVCFrmInfoOthRmk" maxlength="200" placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?>" style="resize: none;height:86px;"><?php echo $tIVCRmk ?></input>
            </div> -->


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
    if('<?=@$tIVCCshorCrd ?>' == '2'){
       $('.xCNPanel_CreditTerm').show();
    }

    $('#ocmIVPaymentType').on('change', function() {
        // if(this.value == 1){
        //     $('.xCNPanel_CreditTerm').hide();
        // }else{
        //     $('.xCNPanel_CreditTerm').show();
        // }
    });
</script>