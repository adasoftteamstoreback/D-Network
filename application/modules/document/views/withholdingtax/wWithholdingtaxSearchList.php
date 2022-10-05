<style>
    /* สี highlight datepicker */
    .datepicker table tr td.today, .datepicker table tr td.today:hover, .datepicker table tr td.today.disabled, .datepicker table tr td.today.disabled:hover {
        background-color: #fde19a;
        background-image: linear-gradient(to bottom, #08c, #0044cc);
        background-repeat: repeat-x;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdd49a', endColorstr='#fdf59a', GradientType=0);
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        color: #fff;
    }

    .xCNIconView2 {
	    content: url(<?php echo base_url().'/application/modules/common/assets/images/icons/view2.png'?>);
	}
</style>
<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetWhTaxSearchAllDocument"
                            name="oetWhTaxSearchAllDocument"
                            placeholder="<?php echo language('document/withholdingtax/withholdingtax','tWhTaxFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtWhTaxSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtWhTaxAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtWhTaxSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvWhTaxAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmWhTaxFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <?php
                                if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                                    if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                                        $tBCHCode 	= $this->session->userdata("tSesUsrBchCodeDefault");
                                        $tBCHName 	= $this->session->userdata("tSesUsrBchNameDefault");
                                    }else{
                                        $tBCHCode 	= '';
                                        $tBCHName 	= '';
                                    }
                                }else{
                                    $tBCHCode 		= '';
                                    $tBCHName 		= '';
                                }
                            ?>
                            <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxAdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetWhTaxAdvSearchBchCodeFrom" name="oetWhTaxAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetWhTaxAdvSearchBchNameFrom"
                                    name="oetWhTaxAdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/withholdingtax/withholdingtax','tWhTaxAdvSearchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtWhTaxAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOAdvSearchBranchTo'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetWhTaxAdvSearchBchCodeTo"name="oetWhTaxAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetWhTaxAdvSearchBchNameTo"
                                    name="oetWhTaxAdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/withholdingtax/withholdingtax','tWhTaxAdvSearchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtWhTaxAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetWhTaxAdvSearcDocDateFrom"
                                    name="oetWhTaxAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxAdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtWhTaxAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input
                                class="form-control xCNDatePicker"
                                type="text"
                                id="oetWhTaxAdvSearcDocDateTo"
                                name="oetWhTaxAdvSearcDocDateTo"
                                placeholder="<?php echo language('document/withholdingtax/withholdingtax', 'tWhTaxAdvSearchDateTo'); ?>"
                            >
                            <span class="input-group-btn" >
                                <button id="obtWhTaxAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/withholdingtax/withholdingtax','tWhTaxAdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmWhTaxAdvSearchStaDoc" name="ocmWhTaxAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                        <div class="form-group" style="float:right;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtWhTaxAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel-body">
        <section id="ostWhTaxDataTable"></section>
    </div>
</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jWhTaxFormSearchList.php')?>