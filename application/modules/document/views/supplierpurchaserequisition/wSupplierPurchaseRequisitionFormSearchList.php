<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetPRSSearchAllDocument"
                            name="oetPRSSearchAllDocument"
                            placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtPRSSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtPRSAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtPRSSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvPRSAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmPRSFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchBranch'); ?></label>
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
                                <input class="form-control xCNHide" type="text" id="oetPRSAdvSearchBchCodeFrom" name="oetPRSAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPRSAdvSearchBchNameFrom"
                                    name="oetPRSAdvSearchBchNameFrom"
                                    value="<?= $tBCHName; ?>"
                                    placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchFrom'); ?>"
                                    readonly
                                >
                                <span class="input-group-btn">
                                    <button id="obtPRSAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchTo');?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetPRSAdvSearchBchCodeTo"name="oetPRSAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetPRSAdvSearchBchNameTo"
                                    name="oetPRSAdvSearchBchNameTo"
                                    value="<?= $tBCHName; ?>"
                                    placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchTo'); ?>"
                                    readonly
                                >
                                <span class="input-group-btn">
                                    <button id="obtPRSAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input
                                    class="form-control xCNDatePicker"
                                    type="text"
                                    id="oetPRSAdvSearcDocDateFrom"
                                    name="oetPRSAdvSearcDocDateFrom"
                                    placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSAdvSearchDateFrom'); ?>"
                                >
                                <span class="input-group-btn" >
                                    <button id="obtPRSAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input
                                class="form-control xCNDatePicker"
                                type="text"
                                id="oetPRSAdvSearcDocDateTo"
                                name="oetPRSAdvSearcDocDateTo"
                                placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSAdvSearchDateTo'); ?>">
                            <span class="input-group-btn" >
                                <button id="obtPRSAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if(@$tPRSTypeDocument == 1){ //ใบขอซื้อ ?>
                        <!-- สถานะเอกสาร -->
                        <input type="hidden" name="ocmPRSAdvSearchStaPrcDoc" id="ocmPRSAdvSearchStaPrcDoc" value="0">
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSAdvSearchStaDoc'); ?></label>
                                <select class="selectpicker form-control" id="ocmPRSAdvSearchStaDoc" name="ocmPRSAdvSearchStaDoc">
                                    <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                    <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                    <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                    <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                                </select>
                            </div>
                        </div>
                    <?php }else{ //ใบขอซื้อ แฟรนไซส์ ?>
                        <!-- สถานะยืนยัน -->
                        <input type="hidden" name="ocmPRSAdvSearchStaDoc" id="ocmPRSAdvSearchStaDoc" value="0">
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSTitleStaPrcDoc'); ?></label>
                                <select class="selectpicker form-control" id="ocmPRSAdvSearchStaPrcDoc" name="ocmPRSAdvSearchStaPrcDoc">
                                    <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                    <option value='1'><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaPrcDoc2'); ?></option>
                                    <option value='2'><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaPrcDoc3'); ?></option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>


                    <!-- สถานะเคลื่อนไหว -->
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
                            <button id="obtPRSAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
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
                <div id="odvPRSMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?=language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliPRSBtnDeleteAll">
                            <a data-toggle="modal" data-target="#odvPRSModalDelDocMultiple"><?=language('common/main/main','tCMNDeleteAll')?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostPRSDataTableDocument"></section>
    </div>
</div>

<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jSupplierPurchaseRequisitionFormSearchList.php')?>


