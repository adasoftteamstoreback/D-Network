<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetDBNSearchAllDocument"
                            name="oetDBNSearchAllDocument"
                            placeholder="<?php echo language('document/debitnote/debitnote','tDBNFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtDBNSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtDBNAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtDBNSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvDBNAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmDBNFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
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
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNAdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetDBNAdvSearchBchCodeFrom" name="oetDBNAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetDBNAdvSearchBchNameFrom"
                                    name="oetDBNAdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/debitnote/debitnote','tDBNAdvSearchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtDBNAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNAdvSearchTo');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetDBNAdvSearchBchCodeTo"name="oetDBNAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetDBNAdvSearchBchNameTo"
                                    name="oetDBNAdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/debitnote/debitnote','tDBNAdvSearchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtDBNAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetDBNAdvSearcDocDateFrom"
                                    name="oetDBNAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNAdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtDBNAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input
                                class="form-control xCNDatePicker"
                                type="text"
                                id="oetDBNAdvSearcDocDateTo"
                                name="oetDBNAdvSearcDocDateTo"
                                placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNAdvSearchDateTo'); ?>"
                            >
                            <span class="input-group-btn" >
                                <button id="obtDBNAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNAdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmDBNAdvSearchStaDoc" name="ocmDBNAdvSearchStaDoc">
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
                                <select class="selectpicker form-control" id="ocmDBNAdvSearchStaAct" name="ocmDBNAdvSearchStaAct">
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
                            <button id="obtDBNAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4"></div>
            <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <!-- <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?=language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliBtnDeleteAll" class="disabled">
                            <a data-toggle="modal" data-target="#odvDBNModalDelDocMultiple"><?= language('common/main/main','tDelAll')?></a>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostDBNDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jDebitNoteFormSearchList.php')?>
