<!-- Panel ข้อมูลรถ-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoicebill/invoicebill', 'tIVCTitleRef'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIVCCar" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVCCar" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- งวดบัญชี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleRefNo') ?></label>
                <input type="text" class="form-control text-left xCNInputReadOnly " id="oetIVCPrdCode" name="oetIVCPrdCode" autocomplete="off" maxlength="20" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleRefNo') ?>" value="<?= @$tIVCFTPrdCode ?>">
            </div>

            <!-- ระยะเครดิต -->
            <div class="form-group xCNPanel_CreditTerm">
                <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                <input style="text-align: right;" maxlength="5" autocomplete="off" value="<?=@$tIVCCrTerm?>" type="text" class="form-control xCNInputNumericWithDecimal" id="oetIVCreditTerm" name="oetIVCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
            </div>

            <!-- วันที่นัดชำระ -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/invoicebill/invoicebill', 'tIVBTitlePaidDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD" id="oetIVCPaidDate" name="oetIVCPaidDate" autocomplete="off" value="<?= @$tIVCFTDueDate ?>">
                            <span class="input-group-btn">
                                <button id="obtIVCRefPanelPaidDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ประเภทการชำระ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation','tTQPayment');?></label>
                <select class="selectpicker form-control" id="ocmIVPaymentType" name="ocmIVPaymentType" maxlength="1">
                    <option value="1" <?php if(@$tIVCCshorCrd=='1'){ echo 'selected'; } ?>><?= language('document/quotation/quotation','tTQPaymentType1');?></option>
                    <option value="2" <?php if(@$tIVCCshorCrd=='2'){ echo 'selected'; } ?> ><?= language('document/quotation/quotation','tTQPaymentType2');?></option>
                </select>
            </div>

             <!-- เงื่อนไข -->
             <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'เงื่อนไขการชำระเงิน') ?></label>
                <input type="text" class="form-control text-left xCNInputReadOnly" id="otaIVCCondition" name="otaIVCCondition" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'เงื่อนไขการชำระเงิน') ?>" value="<?= @$tIVCCondition ?>">
            </div>


            
            <!-- พนักงานสินเชื่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleCreditCst') ?></label>
                <input type="text" class="form-control text-left xCNInputReadOnly " id="otaIVCFrmInfoOthRmk" name="otaIVCFrmInfoOthRmk" autocomplete="off" maxlength="200" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleCreditCst') ?>" value="<?= @$tIVCRmk ?>">
            </div>

        </div>
    </div>
</div>