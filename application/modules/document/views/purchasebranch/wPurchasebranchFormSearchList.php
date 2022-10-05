<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetPRBSearchAllDocument"
                            name="oetPRBSearchAllDocument"
                            placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtPRBSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtPRBAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtPRBSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvPRBAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmPRBFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
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
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetPRBAdvSearchBchCodeFrom" name="oetPRBAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPRBAdvSearchBchNameFrom"
                                    name="oetPRBAdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtPRBAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchTo');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetPRBAdvSearchBchCodeTo"name="oetPRBAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPRBAdvSearchBchNameTo"
                                    name="oetPRBAdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtPRBAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetPRBAdvSearcDocDateFrom"
                                    name="oetPRBAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBAdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtPRBAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input
                                class="form-control xCNDatePicker"
                                type="text"
                                id="oetPRBAdvSearcDocDateTo"
                                name="oetPRBAdvSearcDocDateTo"
                                placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBAdvSearchDateTo'); ?>"
                            >
                            <span class="input-group-btn" >
                                <button id="obtPRBAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBAdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmPRBAdvSearchStaDoc" name="ocmPRBAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Doc Aaction -->
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                                <select class="selectpicker form-control" id="ocmStaDocAct" name="ocmStaDocAct">
                                    <option value='0' selected><?php echo language('common/main/main', 'tAll'); ?></option>
                                    <option value='1'><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                    <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                                </select>
                        </div>
                    </div>
                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group" style="width:60%;float:left;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtPRBAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
            </div>
            <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvPRBMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?php echo language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
						<li id="oliPRBBtnDeleteAll">
							<a data-toggle="modal" data-target="#odvPRBModalDelDocMultiple"><?php echo language('common/main/main','tCMNDeleteAll')?></a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostPRBDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jPurchasebranchFormSearchList.php')?>
