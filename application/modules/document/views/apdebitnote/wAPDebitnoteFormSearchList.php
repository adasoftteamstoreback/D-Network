<div class="panel panel-headline">
    <div class="panel-heading">
        <section id="ostSearchAPD">
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <div class="input-group">
                            <input 
                                class="form-control xCNInputWithoutSingleQuote" 
                                type="text"
                                id="oetSearchAll"
                                name="oetSearchAll"
                                placeholder="<?= language('document/apdebitnote/apdebitnote', 'tAPDFillTextSearch') ?>"
                                onkeyup="javascript: if(event.keyCode == 13) {JSvCallPageAPDPdtDataTable()}" 
                                autocomplete="off"
                            >
                            <span class="input-group-btn">
                                <button type="button" class="btn xCNBtnDateTime" onclick="JSvCallPageAPDPdtDataTable()">
                                    <img src="<?php echo base_url('application/modules/common/assets/images/icons/search-24.png'); ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <a id="oahAPDAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></a>
                <a id="oahAPDSearchReset"   class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxAPDClearSearchData()"><?php echo language('common/main/main', 'tClearSearch'); ?></a>
            </div>
            <div class="row hidden" id="odvAPDAdvanceSearchContainer" style="margin-bottom:20px;">

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="col-lg-6 col-sm-6  col-md-6 col-xs-6 no-padding padding-right-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchBranch'); ?></label>
                            <div class="input-group">
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
                                <input class="form-control xCNHide" id="oetBchCodeFrom" name="oetBchCodeFrom" maxlength="5" value="<?=$tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone" 
                                    type="text"
                                    id="oetBchNameFrom" 
                                    name="oetBchNameFrom" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchBranch'); ?>" 
                                    readonly 
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtAPDBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6 no-padding padding-left-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchBranchTo'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetBchCodeTo" name="oetBchCodeTo" maxlength="5"  value="<?= $tBCHCode; ?>">
                                <input 
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetBchNameTo"
                                    name="oetBchNameTo" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchBranchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <!-- ถ้า user มีสาขาจะไม่สามารถ Brw ได้ -->
                                <span class="input-group-btn" >
                                    <button id="obtAPDBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="col-lg-6 col-sm-6  col-md-6 col-xs-6 no-padding padding-right-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchSpl'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetSplCodeFrom" name="oetSplCodeFrom" maxlength="5">
                                <input
                                    class="form-control xWPointerEventNone" 
                                    type="text"
                                    id="oetSplNameFrom" 
                                    name="oetSplNameFrom" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchSpl'); ?>" 
                                    readonly 
                                    value=""
                                >
                                <span class="input-group-btn" >
                                    <button id="obtAPDBrowseSplFrom" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6  col-md-6 col-xs-6 no-padding padding-right-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchSplTo'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetSplCodeTo" name="oetSplCodeTo" maxlength="5">
                                <input
                                    class="form-control xWPointerEventNone" 
                                    type="text"
                                    id="oetSplNameTo" 
                                    name="oetSplNameTo" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchSplTo'); ?>" 
                                    readonly 
                                    value=""
                                >
                                <span class="input-group-btn" >
                                    <button id="obtAPDBrowseSplTo" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-md-4 col-lg-4">
                    <div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-right-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input  
                                    type="text"
                                    class="form-control input100 xCNDatePicker" 
                                    id="oetSearchDocDateFrom"
                                    name="oetSearchDocDateFrom" 
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchDocDate'); ?>">
                                <span class="input-group-btn" >
                                    <button id="obtSearchDocDateFrom" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-left-15">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchDocDateTo'); ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control input100 xCNDatePicker"
                                    id="oetSearchDocDateTo"
                                    name="oetSearchDocDateTo"
                                    placeholder="<?php echo language('document/apdebitnote/apdebitnote', 'tAPDAdvSearchDocDateTo'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtSearchDocDateTo" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2 col-lg-2">
                    <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                        <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDConditionType'); ?></label>
                    </div>
                    <div class="form-group">
                        <select class="selectpicker form-control" id="ocmDocType" name="ocmDocType">
                            <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                            <option value='1'><?php echo language('document/apdebitnote/apdebitnote', 'tAPDSendAndReceive'); ?></option>
                            <option value='2'><?php echo language('document/apdebitnote/apdebitnote', 'tAPDProductAmount'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2 col-lg-2">
                    <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                        <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBStaDoc'); ?></label>
                    </div>
                    <div class="form-group">
                        <select class="selectpicker form-control" id="ocmStaDoc" name="ocmStaDoc">
                            <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                            <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                            <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                            <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-2 col-lg-2">
                    <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                        <label class="xCNLabelFrm"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBStaPrc'); ?></label>
                    </div>
                    <div class="form-group">
                        <select class="selectpicker form-control" id="ocmStaPrcStk" name="ocmStaPrcStk">
                            <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                            <option value='1'><?php echo language('common/main/main', 'tStaDocProcessor'); ?></option>
                            <option value='3'><?php echo language('common/main/main', 'tStaDocPendingProcessing'); ?></option>
                        </select>
                    </div>
                </div>

				<div class="col-xs-12 col-md-2 col-lg-2">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                        <select class="selectpicker form-control" id="ocmStaDocAct" name="ocmStaDocAct">
                            <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                            <option value='1' selected><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                            <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <div class="form-group" style="width: 60%;">
                        <label class="xCNLabelFrm">&nbsp;</label>
                        <button id="oahAPDAdvanceSearchSubmit" class="btn xCNBTNPrimery" style="width:100%" onclick="JSvCallPageAPDPdtDataTable()"><?php echo language('common/main/main', 'tSearch'); ?></button>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
            </div>
            <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvAPDMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?php echo language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
						<li id="oliAPDBtnDeleteAll" class="disabled">
							<a data-toggle="modal" data-target="#odvModalDel"><?php echo language('common/main/main','tCMNDeleteAll')?></a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <section id="odvContentAPDList"></section>
    </div>
</div>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jAPDFormSearchList.php'); ?>