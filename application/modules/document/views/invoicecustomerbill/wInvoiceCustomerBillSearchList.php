<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <!--ค้นหาธรรมดา-->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInputWithoutSingleQuote"
                            type="text"
                            id="oetSearchAll"
                            name="oetSearchAll"
                            placeholder="<?=language('common/main/main','tPlaceholder')?>"
                            onkeyup="Javascript:if(event.keyCode==13) JSvIVCCallPageDataTable()"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime" onclick="JSvIVCCallPageDataTable()">
                                <img class="xCNIconSearch">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <!--ค้นหาขั้นสูง-->
            <a id="oahIVCAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>

            <!--ล้างข้อมูลค้นหา-->
            <a class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxIVCClearSearchData()"><?=language('common/main/main', 'tClearSearch'); ?></a>
        </div>

        <!--ค้นหาขั้นสูง-->
        <div class="row hidden" id="odvIVCAdvanceSearchContainer" style="margin-bottom:20px;">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','tDOAdvSearchBranch'); ?></label>
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
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetBchCodeFrom" name="oetBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" id="oetBchNameFrom" 
                                name="oetBchNameFrom" 
                                placeholder="<?=language('document/deliveryorder/deliveryorder','tDOAdvSearchFrom'); ?>" 
                                readonly
                                value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtIVCBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','tDOAdvSearchTo'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetBchCodeTo" name="oetBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>"> 
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" 
                                id="oetBchNameTo" 
                                name="oetBchNameTo" 
                                placeholder="<?=language('document/deliveryorder/deliveryorder','tDOAdvSearchTo'); ?>" 
                                readonly
                                value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtIVCBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('monitor/monitor/monitor','tDLSlabelSplFrom'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetSplCodeFrom" name="oetSplCodeFrom" maxlength="5" value="">
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" id="oetSplNameFrom" 
                                name="oetSplNameFrom" 
                                placeholder="<?=language('monitor/monitor/monitor','tDLSlabelSplFrom'); ?>" 
                                readonly
                                value="">
                                <span class="input-group-btn">
                                    <button id="obtIVCBrowseCSTFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('monitor/monitor/monitor','tDLSlabelSplTo'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetSplCodeTo" name="oetSplCodeTo" maxlength="5" value=""> 
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" 
                                id="oetSplNameTo" 
                                name="oetSplNameTo" 
                                placeholder="<?=language('monitor/monitor/monitor','tDLSlabelSplTo'); ?>" 
                                readonly
                                value="">
                                <span class="input-group-btn">
                                    <button id="obtIVCBrowseCSTTo" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','tDOAdvSearchDocDate'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input 
                                class="form-control input100 xCNDatePicker" 
                                type="text" id="oetSearchDocDateFrom" 
                                name="oetSearchDocDateFrom" 
                                placeholder="<?=language('document/deliveryorder/deliveryorder', 'tDOAdvSearchDateFrom'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtSearchDocDateFrom" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?=base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder', 'tDOAdvSearchDateTo'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input 
                                class="form-control input100 xCNDatePicker" 
                                type="text" id="oetSearchDocDateTo" 
                                name="oetSearchDocDateTo" 
                                placeholder="<?=language('document/deliveryorder/deliveryorder', 'tDOAdvSearchDateTo'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtSearchDocDateTo" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?=base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--สถานะเอกสาร-->
                    <div class="col-lg-2 col-md-6 col-xs-6">
                        <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                            <label class="xCNLabelFrm"><?=language('document/topupVending/topupVending', 'tTBStaDoc'); ?></label>
                        </div>
                        <div class="form-group">
                            <select class="selectpicker form-control" id="ocmStaDoc" name="ocmStaDoc">
                                <option value='0'><?=language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!--ปุ่มค้นหา-->
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-3">
                        <div class="form-group" style="width: 60%;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="oahIVCAdvanceSearchSubmit" class="btn xCNBTNPrimery" style="width:100%" onclick="JSvIVCCallPageDataTable()"><?=language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
            </div>
            <!--ตัวเลือกลบหลายตัว-->
            <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?=language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliBtnDeleteAll" class="disabled">
                            <a data-toggle="modal" data-target="#odvIVCModalDelDocMultiple"><?= language('common/main/main','tDelAll')?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!--Content-->
		<section id="ostContentIVC"></section>
	</div>
</div>

<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jAdvancedSearch.php')?>