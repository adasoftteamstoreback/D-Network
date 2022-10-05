<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetDPSSearchAllDocument"
                            name="oetDPSSearchAllDocument"
                            placeholder="<?php echo language('document/saleorder/saleorder','tSOFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtDPSSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtDPSAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtDPSSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvDPSAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmDPSFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
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
                            <label class="xCNLabelFrm"><?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBAdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetDPSAdvSearchBchCodeFrom" name="oetDPSAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetDPSAdvSearchBchNameFrom"
                                    name="oetDPSAdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBAdvSearchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtDPSAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBAdvSearchTo');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetDPSAdvSearchBchCodeTo"name="oetDPSAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetDPSAdvSearchBchNameTo"
                                    name="oetDPSAdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/transferrequestbranch/transferrequestbranch','tTRBAdvSearchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtDPSAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetDPSAdvSearcDocDateFrom"
                                    name="oetDPSAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/saleorder/saleorder', 'tSOAdvSearchDocDate'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtSOAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOAdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmDPSAdvSearchStaDoc" name="ocmDPSAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Approve -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 hide">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOAdvSearchStaApprove'); ?></label>
                            <select class="selectpicker form-control" id="ocmDPSAdvSearchStaApprove" name="ocmDPSAdvSearchStaApprove">
                                <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocProcessor'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocProcessing'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocPendingProcessing'); ?></option>
                            </select>
                        </div>    
                    </div>
                    <!-- From Search Advanced Status Doc Aaction -->
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                                <select class="selectpicker form-control" id="ocmStaDocAct" name="ocmStaDocAct">
                                    <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                                    <option value='1' selected><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                    <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                                </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group" style="width:60%;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtDPSAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
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
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvSOMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?php echo language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
						<li id="oliDPSBtnDeleteAll" class="disabled">
							<a data-toggle="modal" data-target="#odvDPSModalDelDocMultiple"><?php echo language('common/main/main','tCMNDeleteAll')?></a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostDPSDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jDepositFormSearchList.php')?>