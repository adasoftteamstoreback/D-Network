<div class="panel panel-headline">
    <div class="panel-heading" id="SoSearch1">
        <div class="row">
        </div>
        <div id="odvSOAdvanceSearchContainer" style="margin-bottom:20px;">
            <form id="ofmSOFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced Status Sale -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODDocStatus'); ?></label>
                            <select class="selectpicker form-control" id="ocmSOAdvSearchStaSale" name="ocmSOAdvSearchStaSale">
                            <option value='0' selected><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1'><?php echo language('document/saleorder/saleorder', 'รอสร้างใบจัดสินค้า'); ?></option>
                                <option value='2'><?php echo language('document/saleorder/saleorder', 'Stock ไม่พอ'); ?></option>
                                <option value='8'><?php echo language('document/saleorder/saleorder', 'รอยืนยันจัดสินค้า'); ?></option>
                                <option value='4'><?php echo language('document/saleorder/saleorder', 'รอจองช่องฝาก'); ?></option>
                                <option value='5'><?php echo language('document/saleorder/saleorder', 'รอยืนยันจองช่องฝาก'); ?></option>
                                <option value='6'><?php echo language('document/saleorder/saleorder', 'ฝากสินค้าแล้ว'); ?></option>
                                <option value='3'><?php echo language('document/saleorder/saleorder', 'รับสินค้าแล้ว'); ?></option>
                                <option value='7'><?php echo language('document/saleorder/saleorder', 'ยกเลิก'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- From Search Advanced เลขที่ใบสั่งขาย -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSONO'); ?></label>
                            <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSODSearchSoNo" name="oetSODSearchSoNo" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSONO') ?>" autocomplete="off">
                        </div>
                    </div>

                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSODate'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNDatePicker" type="text" id="oetSODAdvSearcSODocDateFrom" name="oetSODAdvSearcSODocDateFrom" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSODate'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtSOAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- From Search Advanced เลขที่เอกสารอ้างอิง -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSORefNO'); ?></label>
                            <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSODSearchSoRefNo" name="oetSODSearchSoRefNo" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchSORefNO') ?>" autocomplete="off">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <!-- From Search Advanced วันที่อ้างอิง -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchRefDate'); ?></label>
                        <div class="input-group">
                            <input class="form-control xCNDatePicker" type="text" id="oetSOAdvSearcDocDateTo" name="oetSOAdvSearcDocDateTo" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchRefDate'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSOAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>

                    <!-- From Search Advanced ชื่อ - สกุลลูกค้า -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchName'); ?></label>
                            <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSODSearchName" name="oetSODSearchName" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchName') ?>" autocomplete="off">
                        </div>
                    </div>

                    <!-- From Search Advanced เบอร์โทร -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 xCNHide">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchTell'); ?></label>
                            <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSODSearchSoTell" name="oetSODSearchSoTell" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchTell') ?>" autocomplete="off">
                        </div>
                    </div>

                    <!-- From Search Advanced Status Process Stock -->
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class='row'>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:50%;float:left;">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button id="obtSODSearchReset" type='button' class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="width:50%;float:left;">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button id="obtSODAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('document/saleorderdata/saleorderdata', 'tSODSearchFill'); ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading" id="SoSearch2">
        <div class="row">
            <!-- From Search Advanced Status Doc -->
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOTable_StatusGenSO'); ?></label>
                    <select class="selectpicker form-control" id="ocmSOAdvSearchStaGenSO" name="ocmSOAdvSearchStaGenSO" onchange="JSvSOCallPageGenPODataTable()">
                        <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                        <option value='1'><?php echo language('common/main/main', 'สั่งขายบางส่วน'); ?></option>
                        <option value='2'><?php echo language('common/main/main', 'ส่งขายครบแล้ว'); ?></option>
                        <option value='3' selected><?php echo language('common/main/main', 'รอสั่งขาย'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="xCNLabelFrm"></label>
                    <div class="input-group">
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSOGenSearchAllDocument" name="oetSOGenSearchAllDocument" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOFillTextSearch') ?>" autocomplete="off">
                        <span class="input-group-btn">
                            <button id="obtSOGenSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtSOGenAdvanceSearch" style='margin-top : 25px' class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtSOGenSearchReset" style='margin-top : 25px' class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvSOGenAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmSOFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                <div class="row">
                    <!-- From Search Advanced  Branch -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <?php
                            if ($this->session->userdata("tSesUsrLevel") != "HQ") {
                                if ($this->session->userdata("nSesUsrBchCount") <= 1) { //ค้นหาขั้นสูง
                                    $tBCHCode     = $this->session->userdata("tSesUsrBchCodeDefault");
                                    $tBCHName     = $this->session->userdata("tSesUsrBchNameDefault");
                                } else {
                                    $tBCHCode     = '';
                                    $tBCHName     = '';
                                }
                            } else {
                                $tBCHCode         = '';
                                $tBCHName         = '';
                            }
                            ?>
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchBranch'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetSOGenAdvSearchBchCodeFrom" name="oetSOGenAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetSOGenAdvSearchBchNameFrom" name="oetSOGenAdvSearchBchNameFrom" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchBranch'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtSOGenAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchBranchTo'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetSOGenAdvSearchBchCodeTo" name="oetSOGenAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetSOGenAdvSearchBchNameTo" name="oetSOGenAdvSearchBchNameTo" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchBranchTo'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtSOGenAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchDocDate'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNDatePicker" type="text" id="oetSOGenAdvSearcDocDateFrom" name="oetSOGenAdvSearcDocDateFrom" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchDateFrom'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtSOGenAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchDateTo'); ?></label>
                        <div class="input-group">
                            <input class="form-control xCNDatePicker" type="text" id="oetSOGenAdvSearcDocDateTo" name="oetSOGenAdvSearcDocDateTo" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchDateTo'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSOGenAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'tSOAdvSearchStaDoc'); ?></label>
                            <select class="selectpicker form-control" id="ocmSOGenAdvSearchStaApprove" name="ocmSOGenAdvSearchStaApprove">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'tStaDocApv'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocPendingApv'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'tStaDocCancel'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Approve -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                            <select class="selectpicker form-control" id="ocmSOGenAdvSearchStaDoc" name="ocmSOGenAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tAll'); ?></option>
                                <option value='1' selected><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- From Search Advanced Status Process Stock -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group" style="width:60%;float:left;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtSOGenAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
            </div>
            <div class="xCNHide col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvSOMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?php echo language('common/main/main', 'tCMNOption') ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliSOBtnDeleteAll" class="disabled">
                            <a data-toggle="modal" data-target="#odvSOModalDelDocMultiple"><?php echo language('common/main/main', 'tCMNDeleteAll') ?></a>
                        </li>
                        <li id="oliSOBtnToGenPo">
                            <a><?php echo language('document/saleorderdata/saleorderdata', 'tPoFrancise') ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostSODataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jSaleOrderDataFormSearchList.php') ?>