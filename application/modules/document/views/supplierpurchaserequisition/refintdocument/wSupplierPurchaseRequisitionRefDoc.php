<!-- Filter -->
<section>
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetPRSRefIntBchCode"
                        name="oetPRSRefIntBchCode"
                        maxlength="5"
                        value="<?=$tBCHCode?>"
                        data-bchcodeold = ""
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetPRSRefIntBchName"
                        name="oetPRSRefIntBchName"
                        maxlength="100"
                        value="<?=$tBCHName?>"
                        readonly
                    >

                    <script>
                        var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                        if( tUsrLevel != "HQ" ){
                            //BCH - SHP
                            var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                            if(tBchCount < 2){
                                $('#obtPRSBrowseBchRefIntDoc').attr('disabled',true);
                            }
                        }
                    </script>
                    
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtPRSBrowseBchRefIntDoc" type="button" class="btn xCNBtnBrowseAddOn "    >
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
                    id="oetPRSRefIntDocNo"
                    name="oetPRSRefIntDocNo"
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
                                id="oetPRSRefIntDocDateFrm"
                                name="oetPRSRefIntDocDateFrm"
                                placeholder="YYYY-MM-DD"
                                value=""
                            >
                            <span class="input-group-btn">
                                <button id="obtPRSBrowseRefExtDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                        id="oetPRSRefIntDocDateTo"
                        name="oetPRSRefIntDocDateTo"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtPRSBrowseRefExtDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- สถานะเอกสาร -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPORefIntDocStatus');?></label>
            <select class="selectpicker form-control  " id="oetPRSRefIntStaDoc" name="oetPRSRefIntStaDoc" maxlength="1" value="<?php echo $tPRSSplPayMentType;?>">
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

<?php include('script/jSupplierPurchaseRequisitionRefDoc.php');?>