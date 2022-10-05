<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetPXSearchAllDocument"
                            name="oetPXSearchAllDocument"
                            placeholder="<?php echo language('document/expenserecord/expenserecord','tPXFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtPXSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
                <button id="obtPXAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
                <button id="obtPXSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
            </div>
        </div>
        <div id="odvPXAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmPXFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/document/document', 'tDocAdvSearchBchFrom'); ?></label>
                            <div class="input-group">
                                <?php
                                    if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                                        if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                                            $tBCHCode   = $this->session->userdata("tSesUsrBchCodeDefault");
                                            $tBCHName   = $this->session->userdata("tSesUsrBchNameDefault");
                                        }else{
                                            $tBCHCode   = '';
                                            $tBCHName   = '';
                                        }
                                    } else {
                                        $tBCHCode       = "";
                                        $tBCHName       = "";
                                    }
                                ?>
                                <input class="form-control xCNHide" type="text" id="oetPXAdvSearchBchCodeFrom" name="oetPXAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPXAdvSearchBchNameFrom"
                                    name="oetPXAdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/document/document','tDocAdvSearchBchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtPXAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/document/document', 'tDocAdvSearchBchTo'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetPXAdvSearchBchCodeTo"name="oetPXAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPXAdvSearchBchNameTo"
                                    name="oetPXAdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/document/document','tDocAdvSearchBchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtPXAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/document/document','tDocAdvSearchDateFrom'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetPXAdvSearcDocDateFrom"
                                    name="oetPXAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/document/document', 'tDocAdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtPXAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/document/document','tDocAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input
                                class="form-control xCNDatePicker"
                                type="text"
                                id="oetPXAdvSearcDocDateTo"
                                name="oetPXAdvSearcDocDateTo"
                                placeholder="<?php echo language('document/document/document', 'tDocAdvSearchDateTo'); ?>"
                            >
                            <span class="input-group-btn" >
                                <button id="obtPXAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/document/document','tDocStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmPXAdvSearchStaDoc" name="ocmPXAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXAdvSearchStaPrcStk'); ?></label>
                            <select class="selectpicker form-control" id="ocmPXAdvSearchStaPrcStk" name="ocmPXAdvSearchStaPrcStk">
                                <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocProcessor'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocProcessing'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocPendingProcessing'); ?></option>
                            </select>
                        </div>
                    </div> -->
                    <!-- From Search Advanced Status Doc Aaction -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                            <select class="selectpicker form-control" id="ocmCardNewCardStaDocAct" name="ocmCardNewCardStaDocAct">
                                <option value='0' selected><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                        <div class="form-group" style="width:60%;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtPXAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div style="padding: 0px 15px;">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9 text-right">
                <div id="odvPXMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?php echo language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
						<li id="oliPXBtnDeleteAll" class="disabled">
							<a data-toggle="modal" data-target="#odvPXModalDelDocMultiple"><?php echo language('common/main/main','tCMNDeleteAll')?></a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostPXDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jExpenseRecordFormSearchList.php')?>