<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetJR1SearchAllDocument"
                            name="oetJR1SearchAllDocument"
                            placeholder="<?php echo language('document/jobrequest1/jobrequest1','tJR1FillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtJR1SerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtJR1AdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtJR1SearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvJR1AdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmJR1FromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
                    <?php
                        if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                            if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                                $tBCHCode   = $this->session->userdata("tSesUsrBchCodeDefault");
                                $tBCHName   = $this->session->userdata("tSesUsrBchNameDefault");
                            }else{
                                $tBCHCode   = '';
                                $tBCHName   = '';
                            }
                        }else{
                            $tBCHCode   = '';
                            $tBCHName   = '';
                        }
                    ?>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetJR1AdvSearchBchCodeFrom" name="oetJR1AdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetJR1AdvSearchBchNameFrom"
                                    name="oetJR1AdvSearchBchNameFrom"
                                    placeholder="<?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchFrom'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtJR1AdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchTo');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetJR1AdvSearchBchCodeTo"name="oetJR1AdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetJR1AdvSearchBchNameTo"
                                    name="oetJR1AdvSearchBchNameTo"
                                    placeholder="<?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchTo'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtJR1AdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  Document Date -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetJR1AdvSearcDocDateFrom"
                                    name="oetJR1AdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1AdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtJR1AdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1AdvSearchDateTo'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetJR1AdvSearcDocDateTo"
                                    name="oetJR1AdvSearcDocDateTo"
                                    placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1AdvSearchDateTo'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtJR1AdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced Status Document -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1','tJR1AdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmJR1AdvSearchStaDoc" name="ocmJR1AdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Document Aaction -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                            <select class="selectpicker form-control" id="ocmJR1StaDocAct" name="ocmJR1StaDocAct">
                                <option value='0' selected><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- Button Form Search Document Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group" style="width:60%;float:left;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtJR1AdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
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
                <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?=language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliBtnDeleteAll" class="disabled">
                            <a data-toggle="modal" data-target="#odvJR1ModalDelDocMultiple"><?= language('common/main/main','tDelAll')?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostJR1DataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jJobReqStep1FormSearchList.php')?>
