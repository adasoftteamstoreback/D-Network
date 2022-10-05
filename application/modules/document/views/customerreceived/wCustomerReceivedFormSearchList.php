<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">

            <!-- From Search Advanced Status Doc -->
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <select class="selectpicker form-control" id="ocmCRVSearchTypeChg" name="ocmCRVSearchTypeChg">
                        <option value='0'><?php echo language('common/main/main', 'รับของ'); ?></option>
                        <option value='1'><?php echo language('common/main/main', 'ดูประวัติ'); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" id='odvSearchType1'>
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control xCNInpuTXOthoutSingleQuote " type="text" id="oetCRVSearchSoSCAN" name="oetCRVSearchSoSCAN" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOFillTextScan') ?>" autocomplete="off" value=''>
                        <input class="form-control xCNInpuTXOthoutSingleQuote xCNHide" type="text" id="ohdCRVInCendition" name="ohdCRVInCendition" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'text') ?>" autocomplete="off" value=''>
                        <span class="input-group-btn">
                            <button id="obtCRVSerchForCRV" type="button" class="btn xCNBtnDateTime"><img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/scanner.png'); ?>"></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" id='odvSearchType2'>
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetCRVSearchAllDocument" name="oetCRVSearchAllDocument" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'กรอกคำค้นหา') ?>" autocomplete="off">
                        <span class="input-group-btn">
                            <button id="obtCRVSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtCRVAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtCRVSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvCRVAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmCRVFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
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
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'สาขา'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetCRVAdvSearchBchCodeFrom" name="oetCRVAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCRVAdvSearchBchNameFrom" name="oetCRVAdvSearchBchNameFrom" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'สาขา'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtCRVAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ถึงสาขา'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetCRVAdvSearchBchCodeTo" name="oetCRVAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCRVAdvSearchBchNameTo" name="oetCRVAdvSearchBchNameTo" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'ถึงสาขา'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtCRVAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div> -->

                    <!-- ร้านค้า -->
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ร้านค้า'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetCRVAdvSearchShpCode" name="oetCRVAdvSearchShpCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCRVAdvSearchShpName" name="oetCRVAdvSearchShpName" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'ร้านค้า'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtCRVAdvSearchBrowseShp" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ตู้ฝาก -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ตู้ฝาก'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetCRVAdvSearchPosCode" name="oetCRVAdvSearchPosCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetCRVAdvSearchPosName" name="oetCRVAdvSearchPosName" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'ตู้ฝาก'); ?>" readonly value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtCRVAdvSearchBrowsePos" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ชนิดตู้ฝาก'); ?></label>
                            <select class="selectpicker form-control" id="ocmCRVAdvSearchPshCode" name="ocmCRVAdvSearchPshCode">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'ตู้ฝากของ'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'จุดบริการ'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- From Search Advanced  DocDate -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'จากวันที่'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNDatePicker" type="text" id="oetCRVAdvSearcDocDateFrom" name="oetCRVAdvSearcDocDateFrom" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'จากวันที่'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtCRVAdvSearchDocDateForm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'ถึงวันที่'); ?></label>
                        <div class="input-group">
                            <input class="form-control xCNDatePicker" type="text" id="oetCRVAdvSearcDocDateTo" name="oetCRVAdvSearcDocDateTo" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'ถึงวันที่'); ?>">
                            <span class="input-group-btn">
                                <button id="obtCRVAdvSearchDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                            </span>
                        </div>
                    </div>

                    <!-- From Search Advanced Status Doc -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'สถานะรับของ'); ?></label>
                            <select class="selectpicker form-control" id="ocmCRVAdvSearchStaDoc" name="ocmCRVAdvSearchStaDoc">
                                <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('common/main/main', 'รับทั้งหมด'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'รับบางส่วน'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'ยังไม่ได้รับ'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Button Form Search Advanced -->
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="form-group" style="width:60%;float:left;">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtCRVAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostCRVDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jCustomerReceivedFormSearchList.php') ?>