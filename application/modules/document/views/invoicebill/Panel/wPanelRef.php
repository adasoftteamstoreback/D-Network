<!-- Panel ข้อมูลรถ-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoicebill/invoicebill', 'tIVBTitleRef'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIVBCar" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVBCar" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- งวดบัญชี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleRefNo') ?></label>
                <input type="text" class="form-control text-left xCNInputReadOnly xCNInputNumericWithDecimal" id="oetIVBPrdCode" name="oetIVBPrdCode" autocomplete="off" maxlength="20" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleRefNo') ?>" value="<?= @$tIVBFTPrdCode ?>">
            </div>

            <!-- วันที่นัดชำระ -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/invoicebill/invoicebill', 'tIVBTitlePaidDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD" id="oetIVBPaidDate" name="oetIVBPaidDate" autocomplete="off" value="<?= @$tIVBFTDueDate ?>">
                            <span class="input-group-btn">
                                <button id="obtIVBRefPanelPaidDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- เงื่อนไข -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleCondition') ?></label>
                <input type="text" class="form-control text-left xCNInputReadOnly" id="otaIVBCondition" name="otaIVBCondition" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleCondition') ?>" value="<?= @$tIVBCondition ?>">
            </div>

            <!-- ผู้ติดต่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBTitleContack') ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetIVBCtrCode" name="oetIVBCtrCode" maxlength="5" value="<?= @$tIVBCTRCode ?>">
                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIVBCtrName" name="oetIVBCtrName" maxlength="100" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBTitleContack') ?>" value="<?= @$tIVBCTRName ?>" readonly>
                    <span class="input-group-btn">
                        <button id="oimIVBBrowseCtr" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>