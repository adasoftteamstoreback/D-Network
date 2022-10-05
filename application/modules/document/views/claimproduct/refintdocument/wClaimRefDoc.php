<!-- Filter -->
<section>
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetCLMRefIntBchCode"
                        name="oetCLMRefIntBchCode"
                        maxlength="5"
                        value="<?=$tBCHCode?>"
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetCLMRefIntBchName"
                        name="oetCLMRefIntBchName"
                        maxlength="100"
                        value="<?=$tBCHName?>"
                        readonly
                    >
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtCLMBrowseBchRefIntDoc" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- เลขที่เอกสาร -->
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo')?></label>
                <input
                    type="text"
                    class="form-control"
                    id="oetCLMRefIntDocNo"
                    name="oetCLMRefIntDocNo"
                    maxlength="100"
                    value=""
                    placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo')?>"
                >
            </div>
        </div>
    </div>
    <!-- วันที่เอกสารเริ่ม -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateFrm')?></label>
                    <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNDatePicker xCNInputMaskDate"
                        id="oetCLMRefIntDocDateFrm"
                        name="oetCLMRefIntDocDateFrm"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtCLMBrowseRefExtDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- วันที่เอกสารสิ้นสุด -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateTo')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNDatePicker xCNInputMaskDate"
                        id="oetCLMRefIntDocDateTo"
                        name="oetCLMRefIntDocDateTo"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtCLMBrowseRefExtDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
                
    <!-- ปุ่มค้นหา -->
    <div class="col-md-2 col-xs-2 col-sm-2" style="padding-top: 24px;">
        <button id="obtRefIntDocFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" style="width: 100%;" type="button" ><?= language('document/purchaseorder/purchaseorder', 'tPORefIntDocFilter')?></button>
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

<?php include('script/jClaimRefDoc.php');?>