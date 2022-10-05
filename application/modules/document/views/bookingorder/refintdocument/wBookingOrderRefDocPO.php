<!-- Filter -->
<section>
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetTWXRefIntBchCode"
                        name="oetTWXRefIntBchCode"
                        maxlength="5"
                        value="<?=$tBCHCode?>"
                        data-bchcodeold = ""
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetTWXRefIntBchName"
                        name="oetTWXRefIntBchName"
                        maxlength="100"
                        value="<?=$tBCHName?>"
                        readonly
                    >
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtTWXBrowseBchRefIntDoc" type="button" class="btn xCNBtnBrowseAddOn "    >
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- เลขที่เอกสาร -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo')?></label>
                <input
                    type="text"
                    class="form-control"
                    id="oetTWXRefIntDocNo"
                    name="oetTWXRefIntDocNo"
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
                                id="oetTWXRefIntDocDateFrm"
                                name="oetTWXRefIntDocDateFrm"
                                placeholder="YYYY-MM-DD"
                                value=""
                            >
                            <span class="input-group-btn">
                                <button id="obtTWXBrowseRefExtDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                        id="oetTWXRefIntDocDateTo"
                        name="oetTWXRefIntDocDateTo"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtTWXBrowseRefExtDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- สถานะเอกสาร -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPORefIntDocStatus');?></label>
            <select class="selectpicker form-control  " id="oetTWXRefIntStaDoc" name="oetTWXRefIntStaDoc" maxlength="1" value="<?php echo $tTWXSplPayMentType;?>">
                <option value="1" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaApv1');?></option>
                <option value="2" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaApv');?></option>
                <option value="3" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaDoc3');?></option>
            </select>
        </div>
    </div>
                
                
    <!-- ปุ่มค้นหา -->
    <div class="col-md-1 col-xs-1 col-sm-1" style="padding-top: 24px;">
        <button id="obtRefIntDocFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" ><?= language('document/purchaseorder/purchaseorder', 'tPORefIntDocFilter')?></button>
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

<?php include('script/jBookingOrderRefDocPO.php');?>