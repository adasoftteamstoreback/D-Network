<!-- Filter -->
<section>
    <!-- สาขา -->
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetDBRBoxBchCode"
                        name="oetDBRBoxBchCode"
                        maxlength="5"
                        value="<?=$tBCHCode?>"
                        data-bchcodeold = ""
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetDBRBoxBchName"
                        name="oetDBRBoxBchName"
                        maxlength="100"
                        value="<?=$tBCHName?>"
                        readonly
                    >
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtDBRBoxBrowseBch" type="button" class="btn xCNBtnBrowseAddOn " disabled >
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- จุดขาย -->
    <div class="col-md-3 col-xs-3 col-sm-3 xCNHide">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'ตู้ฝาก')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetDBRBoxPosCode"
                        name="oetDBRBoxPosCode"
                        maxlength="5"
                        value=""
                        data-bchcodeold = ""
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetDBRBoxPosName"
                        name="oetDBRBoxPosName"
                        placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'ตู้ฝาก')?>"
                        maxlength="100"
                        value=""
                        readonly
                    >
                    <input type="hidden" id="ohdSeqNo" value="<?=$tSeqNo?>">
                    <input type="hidden" id="ohdBrowseType" value="<?=$nStaBrowseType?>">
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtDBRBoxBrowsePos" type="button" class="btn xCNBtnBrowseAddOn "    >
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ชื่อตู้ -->
    <div class="col-md-5 col-xs-5 col-sm-5">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'ตู้ฝาก')?></label>
                <input
                    type="text"
                    class="form-control"
                    id="oetDBRBrowseBoxNo"
                    name="oetDBRBrowseBoxNo"
                    maxlength="100"
                    value=""
                    placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'ตู้ฝาก')?>"
                >
            </div>
        </div>
    </div>
                
                
    <!-- ปุ่มค้นหา -->
    <div class="col-md-1 col-xs-1 col-sm-1" style="padding-top: 24px;">
        <button id="obtBrowseBoxFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" ><?= language('document/purchaseorder/purchaseorder', 'tPORefIntDocFilter')?></button>
    </div>
</section>
<!-- Document -->
<section>
    <div id="odvBrowseBoxHDDataTable"></div>
</section>

<?php include('script/jDepositBoxReservationBoxDoc.php');?>