<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <!--ค้นหาธรรมดา-->
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInputWithoutSingleQuote"
                            type="text"
                            id="oetSearchAll"
                            name="oetSearchAll"
                            placeholder="<?=language('common/main/main','tPlaceholder')?>"
                            onkeyup="Javascript:if(event.keyCode==13) JSvMNGCallPageDataTable()"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime" onclick="JSvMNGCallPageDataTable()">
                                <img class="xCNIconSearch">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <!--ค้นหาขั้นสูง-->
                <a id="oahMNGAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>
        
                <!--ล้างข้อมูลค้นหา-->
                <a class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxMNGClearSearchData()"><?=language('common/main/main', 'tClearSearch'); ?></a>
            </div>
        </div>

        <!--ค้นหาขั้นสูง-->
        <div class="hidden" id="odvMNGAdvanceSearchContainer" style="margin-bottom:20px;">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12">
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
                                <button id="obtMNGBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12">
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
                                <button id="obtMNGBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-xs-12">
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
                <div class="col-lg-2 col-md-3 col-xs-12">
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
                <div class="col-lg-2 col-md-3 col-xs-12">
                    <?php 
                        if(@$tMNGTypeDocument == 2){
                            $tTextLabelSearch = " (ใบขอโอน,สั่งขาย,ใบขอซื้อ)";
                        }else{
                            $tTextLabelSearch = "(ใบขอโอน,ใบขอซื้อ)";
                        } 
                    ?>
                    <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder', 'tDOAdvSearchDocDate'); ?> <?=$tTextLabelSearch?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input 
                            class="form-control input100 xCNDatePicker" 
                            type="text" id="oetSearchDocDateRef" 
                            name="oetSearchDocDateRef" 
                            placeholder="<?=language('document/deliveryorder/deliveryorder', 'tDOAdvSearchDateTo'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSearchDocDateRef" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?=base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12">
                    <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTSUPFrom'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control xCNHide" id="oetSplCodeFrom" name="oetSplCodeFrom" maxlength="5" value="">
                            <input 
                            class="form-control xWPointerEventNone" 
                            type="text" id="oetSplNameFrom" 
                            name="oetSplNameFrom" 
                            placeholder="<?=language('common/main/main','tCenterModalPDTSUPFrom'); ?>" 
                            readonly
                            value="">
                            <span class="input-group-btn">
                                <button id="obtMNGBrowseSplFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12">
                    <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTSUPTo'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control xCNHide" id="oetSplCodeTo" name="oetSplCodeTo" maxlength="5" value=""> 
                            <input 
                            class="form-control xWPointerEventNone" 
                            type="text" 
                            id="oetSplNameTo" 
                            name="oetSplNameTo" 
                            placeholder="<?=language('common/main/main','tCenterModalPDTSUPTo'); ?>" 
                            readonly
                            value="">
                            <span class="input-group-btn">
                                <button id="obtMNGBrowseSplTo" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-xs-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/managedocorderbranch/managedocorderbranch','tMNGTHDocApv')?><?=$tTextLabelSearch ?></label>
                        <select class="selectpicker form-control" id="ocmStaApv" name="ocmStaApv">
                            <option value='0'><?=language('common/main/main', 'tStaDocAll'); ?></option>
                            <option value='4'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusDocProcess'); ?></option>
                            <option value='1'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusWait'); ?></option>
                            <option value='2'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusConfrimAndWaitAprove'); ?></option>
                            <option value='3'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusAprove'); ?></option>
                        </select>
                    </div>
                </div>
                <?php if(@$tMNGTypeDocument == 2){ //ใบสั่งสินค้าจากสาขา - ลูกค้า ?>
                    <div class="col-lg-2 col-md-3 col-xs-12">
                        <div class="form-group">
                            <label class="xCNLabelFrm">ประเภทเอกสาร</label>
                            <select class="selectpicker form-control" id="ocmStaTypeDocument" name="ocmStaTypeDocument">
                                <option value='0'><?=language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'ใบสั่งซื้อ'); ?></option>
                                <option value='2'><?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'ใบสั่งสินค้าสำนักงานใหญ่'); ?></option>
                            </select>
                        </div>
                    </div>
                <?php }else{ ?>
                    <!--ประเภทเอกสาร : ค้นหาทั้งหมด-->
                    <input type="hidden" id="ocmStaTypeDocument" name="ocmStaTypeDocument" value="0">
                <?php } ?>
                <div class="col-lg-2 col-md-3 col-xs-6">
                    <div class="form-group" style="width: 60%;">
                        <label class="xCNLabelFrm">&nbsp;</label>
                        <button id="oahIVAdvanceSearchSubmit" class="btn xCNBTNPrimery" style="width:100%" onclick="JSvMNGCallPageDataTable(1)"><?=language('common/main/main', 'tSearch'); ?></button>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="panel-heading"></div>
    <div class="panel-body">
		<section id="ostContentMNG"></section>
	</div>
</div>

<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jAdvancedSearch.php')?>