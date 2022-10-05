<!-- Filter -->
<section>
    <div class='panel-heading' id="ofmPRBSerchAdv">
    <div class='row'>
    <form id='ofmExportExcelNoStock' name='ofmExportExcelNoStock' method="post" enctype="multipart/form-data" target="_blank" action="docPRBNoStockEventExport">
        <div class="col-md-3 col-xs-3 col-sm-3">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                            id="oetPRBRefIntBchCode"
                            name="oetPRBRefIntBchCode"
                            maxlength="5"
                            value="<?=$tBCHCode?>"
                            data-bchcodeold = ""
                        >
                        <input
                            type="text"
                            class="form-control xWPointerEventNone"
                            id="oetPRBRefIntBchName"
                            name="oetPRBRefIntBchName"
                            maxlength="100"
                            value="<?=$tBCHName?>"
                            readonly
                        >
                        <span class="input-group-btn xWConditionSearchPdt">
                            <button id="obtPRBBrowseBchNoStock" type="button" class="btn xCNBtnBrowseAddOn "    >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        <!-- คลังสินค้า -->
        <div class="col-md-3 col-xs-3 col-sm-3">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmWah')?></label>
                <div class="input-group">
                        <input
                            type="text"
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                            id="oetPRBRefIntWahCode"
                            name="oetPRBRefIntWahCode"
                            maxlength="5"
                            value=""
                        >
                        <input
                            type="text"
                            class="form-control xWPointerEventNone"
                            id="oetPRBRefIntWahName"
                            name="oetPRBRefIntWahName"
                            maxlength="100"
                            value=""
                            readonly
                        >
                        <span class="input-group-btn xWConditionSearchPdt">
                            <button id="obtPRBBrowseWahNostock" type="button" class="btn xCNBtnBrowseAddOn "    >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- จากรหัสสินค้า -->
         <div class="col-md-3 col-xs-3 col-sm-3">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tAdjPdtFilFrom')?></label>
                <div class="input-group">
                        <input
                            type="text"
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                            id="oetPRBRefIntPDTCodeFrm"
                            name="oetPRBRefIntPDTCodeFrm"
                            maxlength="5"
                            value=""
                        >
                        <input
                            type="text"
                            class="form-control xWPointerEventNone"
                            id="oetPRBRefIntPDTNameFrm"
                            name="oetPRBRefIntPDTNameFrm"
                            maxlength="100"
                            value=""
                            readonly
                        >
                        <span class="input-group-btn xWConditionSearchPdt">
                            <button id="obtPRBBrowsePDTNostockFRM" type="button" class="btn xCNBtnBrowseAddOn "    >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- ถึงรหัสสินค้า -->
         <div class="col-md-3 col-xs-3 col-sm-3">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tAdjPdtFilTo')?></label>
                <div class="input-group">
                        <input
                            type="text"
                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                            id="oetPRBRefIntPDTCodeTo"
                            name="oetPRBRefIntPDTCodeTo"
                            maxlength="5"
                            value=""
                        >
                        <input
                            type="text"
                            class="form-control xWPointerEventNone"
                            id="oetPRBRefIntPdtNameTo"
                            name="oetPRBRefIntPdtNameTo"
                            maxlength="100"
                            value=""
                            readonly
                        >
                        <span class="input-group-btn xWConditionSearchPdt">
                            <button id="obtPRBBrowsePDTNostockTo" type="button" class="btn xCNBtnBrowseAddOn "    >
                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
         
         
    <div class="row">
        <div class='col-md-12 text-right' style="margin-bottom:10px;">
    <!-- ปุ่มExcel -->
        <button id="obtNoStockDocExcel" class="btn xCNBTNDefult xCNBTNDefult1Btn" type="button" onclick="JStNostockExport()" >ส่งออก Excel</button>
    <!-- ปุ่มล้างข้อมูลค้นหา -->
        <button id="obtNoStockDocReset" class="btn xCNBTNDefult xCNBTNDefult1Btn" type="button" >ล้างข้อมูลค้นหา</button>
    <!-- ปุ่มค้นหา -->
        <button id="obtNoStocktDocFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" >ค้นหา</button>
        </div>
    </div>
</form>
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

<?php include('script/jPurchasebranchRefDoc.php');?>
