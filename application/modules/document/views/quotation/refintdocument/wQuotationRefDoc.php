<!-- Filter -->

<section>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row">
            <div class=" col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetQTRefIntBchCode" name="oetQTRefIntBchCode" maxlength="5" value="<?= $tBCHCode ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetQTRefIntBchName" name="oetQTRefIntBchName" maxlength="100" value="<?= $tBCHName ?>" readonly>
                        <span class="input-group-btn xWConditionSearchPdt">
                            <button id="obtQTBrowseBchRefIntDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- เลขที่เอกสาร -->
            <div class=" col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo') ?></label>
                    <input type="text" class="form-control" id="oetQTRefIntDocNo" name="oetQTRefIntDocNo" maxlength="100" value="" placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo') ?>">
                </div>
            </div>

            <!-- วันที่เอกสารเริ่ม -->
            <div class=" col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateFrm') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetQTRefIntDocDateFrm" name="oetQTRefIntDocDateFrm" placeholder="YYYY-MM-DD" value="">
                        <span class="input-group-btn">
                            <button id="obtQTBrowseRefExtDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- วันที่เอกสารสิ้นสุด -->
            <div class=" col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateTo') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetQTRefIntDocDateTo" name="oetQTRefIntDocDateTo" placeholder="YYYY-MM-DD" value="">
                        <span class="input-group-btn">
                            <button id="obtQTBrowseRefExtDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- สถานะเอกสาร -->
            <div class=" col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocStatus'); ?></label>
                    <select class="selectpicker form-control  " id="oetQTRefIntStaDoc" name="oetQTRefIntStaDoc" maxlength="1" value="">
                        <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv1'); ?></option>
                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv'); ?></option>
                        <option value="3"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc3'); ?></option>
                    </select>
                </div>
            </div>

            <!-- ปุ่มค้นหา -->
            <div style="padding-top: 25px;">
                <button id="obtRefIntDocFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('document/purchaseorder/purchaseorder', 'tPORefIntDocFilter') ?></button>
            </div>
        </div>
    </div>
</section>

<!-- Document -->
<section>
    <div id="odvRefIntDocHDDataTable"></div>
</section>
<!-- Items -->
<section>
    <div id="odvRefIntDocDTDataTable"></div>
</section>

<?php include('script/jQuotationRefDoc.php'); ?>